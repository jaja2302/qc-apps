<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Illuminate\Support\Arr;
use Nette\Utils\DateTime;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExportSidaktph;
use App\Models\Regional;

require_once(app_path('helpers.php'));

class SidaktphController extends Controller
{
    //
    public $search;
    public function index(Request $request)
    {
        $queryEst = DB::connection('mysql2')->table('estate')->whereIn('wil', [1, 2, 3])
            ->where('estate.emp', '!=', 1)
            ->whereNotIn('estate.est', ['LDE', 'SRE'])
            ->pluck('est');

        $queryEst[] = 'PT.MUA';
        // dd($queryEst);
        $queryEste = DB::connection('mysql2')->table('estate')->whereIn('wil', [1, 2, 3])->where('estate.est', '!=', 'CWS1')->where('estate.est', '!=', 'PLASMA')->get();
        $queryEste = $queryEste->groupBy(function ($item) {
            return $item->wil;
        });

        $queryEste = json_decode($queryEste, true);

        // dd($queryEste);
        $queryAfd = DB::connection('mysql2')->Table('afdeling')
            ->select(
                'afdeling.id',
                'afdeling.nama',
                'estate.est'

            ) //buat mengambil data di estate db dan willayah db

            ->join('estate', 'estate.id', '=', 'afdeling.estate') //kemudian di join untuk mengambil est perwilayah
            ->where('estate.est', '!=', 'CWS1')->where('estate.est', '!=', 'PLASMA')
            ->get();

        $queryAfd = json_decode($queryAfd, true);
        // dd($queryAfd);



        $querySidak = DB::connection('mysql2')->table('sidak_tph')
            ->whereBetween('sidak_tph.datetime', ['2023-01-22', '2023-01-29'])
            ->get();
        $querySidak = json_decode($querySidak, true);

        $dataAfdEst = array();
        // menyimpan array nested dari  wil -> est -> afd
        foreach ($queryEste as $key => $value) {
            foreach ($value as $key2 => $value2) {
                foreach ($queryAfd as $key3 => $value3) {
                    if ($value2['est'] == $value3['est']) {
                        foreach ($querySidak as $key4 => $value4) {
                            if (($value2['est'] == $value4['est']) && ($value3['nama'] == $value4['afd'])) {
                                if (!isset($dataAfdEst[$value2['est']][$value3['nama']])) {
                                    $dataAfdEst[$value2['est']][$value3['nama']] = array();
                                }
                                $dataAfdEst[$value2['est']][$value3['nama']][] = $value4;
                            }
                        }
                    }
                }
            }
        }

        foreach ($dataAfdEst as $key => $value) {
            foreach ($value as $key2 => $value2) {
                if (empty($value2)) {
                    unset($dataAfdEst[$key][$key2]);
                }
            }
            if (empty($dataAfdEst[$key])) {
                unset($dataAfdEst[$key]);
            }
        }

        // dd($dataSkorAkhirPerWil);
        $queryWill = DB::connection('mysql2')
            ->table('wil')
            ->whereIn('regional', [1])
            ->pluck('nama');
        $queryTph = DB::connection('mysql2')
            ->table('sidak_tph')
            ->orderBy('datetime', 'desc')
            ->groupBy(DB::raw('YEAR(datetime)'))
            ->pluck('datetime')->toArray();

        $optYear = array();
        foreach ($queryTph as $datetime) {
            $carbon = Carbon::createFromFormat('Y-m-d H:i:s', $datetime);
            array_push($optYear, $carbon->format('Y'));
        }
        $listMonth = ['JAN', 'FEB', 'MAR', 'APR', 'MEI', 'JUN', 'JUL', 'AGS', 'SEP', 'OKT', 'NOV', 'DES'];

        $queryEstate = DB::connection('mysql2')->table('estate')
            ->select('estate.*')
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->where('wil.regional', '1')
            ->get();
        $queryEstate = json_decode($queryEstate, true);


        // dd($dataSkorPlas);
        $optionREg = DB::connection('mysql2')->table('reg')
            ->select('reg.*')
            ->whereNotIn('reg.id', [5])
            // ->where('wil.regional', 1)
            ->get();


        $optionREg = json_decode($optionREg, true);



        $lok = trim(session('lok'));

        $getreg = DB::connection('mysql2')->table('reg')
            ->select('*')
            ->where('nama', '=', $lok)
            ->pluck('id');
        // dd($lok);
        if ($getreg != []) {
            $regdata = 1;
        } else {
            $regdata = $getreg;
        }

        // dd($regdata);
        $sidaktph = DB::connection('mysql2')->table('sidak_tph')
            ->select('sidak_tph.*')
            ->join('estate', 'estate.est', '=', 'sidak_tph.est')
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->where('estate.emp', '!=', 1)
            ->where('wil.regional', $regdata)
            ->whereDate('datetime', today())
            ->get();
        // dd($sidaktph);
        $columns = [
            'qc',
            'status',
            'est',
            'afd',
            'blok',
            'luas',
            'no_tph',
            'bt_tph',
            'bt_jalan',
            'bt_bin',
            'jum_karung',
            'buah_tinggal',
            'restan_unreported',
            'tph_semak',
            'foto_temuan',
            'foto_semak',
            'komentar',
            'foto_temuan',
            'datetime',
            'app_version'
        ];

        $records = detectDuplicates($sidaktph, $columns);

        // dd($records);
        DB::connection('mysql2')->table('sidak_tph')
            ->select('*')
            ->whereIn('id', $records)
            ->delete();
        $getdata = DB::connection('mysql2')->table('sidak_tph')
            ->select('*')
            ->whereIn('id', $records)
            ->get();

        // // dd($getdata);

        // $getdata = json_decode($getdata, true);
        // $tt_duplicate = count($records);
        $tt_duplicate = 0;

        if ($tt_duplicate != 0) {
            $check = 'ada';
        } else {
            $check = 'kosong';
        }

        // dd($queryEst);
        return view('sidaktph/dashboardtph', [
            'list_estate' => $queryEst,
            'list_wilayah' => $queryWill,
            'optYear' => $optYear,
            'list_month' => $listMonth,
            'option_reg' => $optionREg,
            'check' => $check,
            'idduplicate' => $records,
            'check_data' => $getdata,
        ]);
    }

    public function changeDataTph(Request $request)
    {
        $tanggal = $request->get('date');
        $regional = $request->get('regional');

        $result = get_sidaktph_perbulan($tanggal, $regional);



        // dd($result);
        $arrView = array();
        $arrView['week1'] = $result['week1'];
        $arrView['week2'] = $result['week2'];
        $arrView['week3'] = $result['week3'];
        $arrView['week4'] = $result['week4'];
        $arrView['week5'] = $result['week5'];



        echo json_encode($arrView); //di decode ke dalam bentuk json dalam vaiavel arrview yang dapat menampung banyak isi array
        exit();
    }
    public function listAsisten(Request $request)
    {
        $query = DB::connection('mysql2')->table('asisten_qc')
            ->select('asisten_qc.*')
            // ->whereIn('estate.wil', [1, 2, 3])
            // ->join('estate', 'estate.est', '=', 'asisten_qc.est')
            ->get();

        $queryEst = DB::connection('mysql2')->table('estate')->whereIn('wil', [1, 2, 3])->get();
        $queryAfd = DB::connection('mysql2')->table('afdeling')->select('nama')->groupBy('nama')->get();

        return view('listAsisten', ['asisten' => $query, 'estate' => $queryEst, 'afdeling' => $queryAfd]);
    }

    public function tambahAsisten(Request $request)
    {
        $query = DB::connection('mysql2')->table('asisten_qc')
            ->where('est', $request->input('est'))
            ->where('afd', $request->input('afd'))
            ->first();

        if (empty($query)) {
            DB::connection('mysql2')->table('asisten_qc')->insert([
                'nama' => $request->input('nama'),
                'est' => $request->input('est'),
                'afd' => $request->input('afd')
            ]);

            return redirect()->route('listAsisten')->with('status', 'Data asisten berhasil ditambahkan!');
        } else {
            return redirect()->route('listAsisten')->with('error', 'Gagal ditambahkan, asisten dengan Estate dan Afdeling tersebut sudah ada!');
        }
    }

    public function perbaruiAsisten(Request $request)
    {

        $est = $request->input('est');
        $afd = $request->input('afd');
        $nama = $request->input('nama');
        $id = $request->input('id');

        $query = DB::connection('mysql2')->table('asisten_qc')
            ->where('id', $id)
            ->first();


        // dd($est, $query->est);

        if ($query->nama != $nama && $query->est == $est && $query->afd == $afd) {
            DB::connection('mysql2')->table('asisten_qc')->where('id', $request->input('id'))
                ->update([
                    'nama' => $request->input('nama'),
                    'est' => $request->input('est'),
                    'afd' => $request->input('afd')
                ]);

            return redirect()->route('listAsisten')->with('status', 'Data asisten berhasil diperbarui!');
        } else if ($est != $query->est) {
            $queryWill2 = DB::connection('mysql2')->table('asisten_qc')
                ->where('est', $est)
                ->where('afd', $afd)
                ->first();

            if (empty($queryWill2)) {
                DB::connection('mysql2')->table('asisten_qc')->where('id', $request->input('id'))
                    ->update([
                        'nama' => $request->input('nama'),
                        'est' => $request->input('est'),
                        'afd' => $request->input('afd')
                    ]);

                return redirect()->route('listAsisten')->with('status', 'Data asisten berhasil diperbarui!');
            } else {
                return redirect()->route('listAsisten')->with('error', 'Gagal diperbarui, asisten dengan Estate dan Afdeling tersebut sudah ada!');
            }
        } else if ($afd != $query->afd) {
            $queryWill2 = DB::connection('mysql2')->table('asisten_qc')
                ->where('est', $est)
                ->where('afd', $afd)
                ->first();

            if (empty($queryWill2)) {
                DB::connection('mysql2')->table('asisten_qc')->where('id', $request->input('id'))
                    ->update([
                        'nama' => $request->input('nama'),
                        'est' => $request->input('est'),
                        'afd' => $request->input('afd')
                    ]);

                return redirect()->route('listAsisten')->with('status', 'Data asisten berhasil diperbarui!');
            } else {
                return redirect()->route('listAsisten')->with('error', 'Gagal diperbarui, asisten dengan Estate dan Afdeling tersebut sudah ada!');
            }
        } else {
            return redirect()->route('listAsisten')->with('error', 'Gagal diperbarui, asisten dengan Estate dan Afdeling tersebut sudah ada!');
        }

        // $query = DB::connection('mysql2')->table('asisten_qc')
        //     ->where('est', $request->input('est'))
        //     ->where('afd', $request->input('afd'))
        //     ->first();

        // // dd($query);
        // if (empty($query)) {
        //     DB::connection('mysql2')->table('asisten_qc')->where('id', $request->input('id'))
        //         ->update([
        //             'nama' => $request->input('nama'),
        //             'est' => $request->input('est'),
        //             'afd' => $request->input('afd')
        //         ]);

        //     return redirect()->route('listAsisten')->with('status', 'Data asisten berhasil diperbarui!');
        // } else {
        //     return redirect()->route('listAsisten')->with('error', 'Gagal diperbarui, asisten dengan Estate dan Afdeling tersebut sudah ada!');
        // }
    }

    public function hapusAsisten(Request $request)
    {
        DB::connection('mysql2')->table('asisten_qc')->where('id', $request->input('id'))->delete();
        return redirect()->route('listAsisten')->with('status', 'Data asisten berhasil dihapus!');
    }

    public function downloadPDF(Request $request)
    {
        $url = $request->get('url');
        $arrView = array();
        $file_headers = @get_headers($url);
        if (!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
            $arrView['status'] = '404';
            $arrView['url'] = $url;
        } else {
            $arrView['status'] = '200';
            $arrView['url'] = $url;
        }
        echo json_encode($arrView);
        exit();
    }
    public function getBtTph(Request $request)
    {
        $week = $request->input('start');
        $weekDateTime = new DateTime($week);
        $weekDateTime->setISODate((int)$weekDateTime->format('o'), (int)$weekDateTime->format('W'));

        $startDate = $weekDateTime->format('Y-m-d');
        $weekDateTime->modify('+6 days');
        $endDate = $weekDateTime->format('Y-m-d');
        $newparamsdate = '2024-03-01';

        $tanggalDateTime = new DateTime($startDate);
        // dd($tanggalDateTime);
        $newparamsdateDateTime = new DateTime($newparamsdate);
        // dd($newparamsdateDateTime);

        if ($tanggalDateTime >= $newparamsdateDateTime) {
            $dataparams = 'new';
        } else {
            $dataparams = 'old';
        }
        // dd($startDate, $endDate);
        $regional = $request->input('reg');


        $queryAsisten = DB::connection('mysql2')->table('asisten_qc')
            ->select('asisten_qc.*')
            ->get();
        $queryAsisten = json_decode($queryAsisten, true);
        // dd($value2['datetime'], $endDate);
        $queryEste = DB::connection('mysql2')->table('estate')
            ->select('estate.*')
            ->where('estate.emp', '!=', 1)
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->where('wil.regional', $regional)
            ->where('estate.emp', '!=', 1)
            ->whereNotIn('estate.est', ['SRE', 'LDE'])
            ->get();
        $queryEste = json_decode($queryEste, true);

        $muaest = DB::connection('mysql2')->table('estate')
            ->select('estate.*')
            ->where('estate.emp', '!=', 1)
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->where('wil.regional', $regional)
            // ->where('estate.emp', '!=', 1)
            ->whereIn('estate.est', ['SRE', 'LDE'])
            ->get('est');
        $muaest = json_decode($muaest, true);


        $queryAfd = DB::connection('mysql2')->table('afdeling')
            ->select(
                'afdeling.id',
                'afdeling.nama',
                'estate.est'
            ) //buat mengambil data di estate db dan willayah db
            ->join('estate', 'estate.id', '=', 'afdeling.estate') //kemudian di join untuk mengambil est perwilayah
            ->get();
        $queryAfd = json_decode($queryAfd, true);

        $ancakFA = DB::connection('mysql2')
            ->table('sidak_tph')
            ->select(
                "sidak_tph.*",
                DB::raw('DATE_FORMAT(sidak_tph.datetime, "%Y-%m-%d") as tanggal'),
                DB::raw('DATE_FORMAT(sidak_tph.datetime, "%M") as bulan'),
                DB::raw("
                CASE 
                WHEN status = '' THEN 1
                WHEN status = '0' THEN 1
                WHEN LOCATE('>H+', status) > 0 THEN '8'
                WHEN LOCATE('H+', status) > 0 THEN 
                    CASE 
                        WHEN SUBSTRING_INDEX(SUBSTRING_INDEX(status, 'H+', -1), ' ', 1) > 8 THEN '8'
                        ELSE SUBSTRING_INDEX(SUBSTRING_INDEX(status, 'H+', -1), ' ', 1)
                    END
                WHEN status REGEXP '^[0-9]+$' AND status > 8 THEN '8'
                WHEN LENGTH(status) > 1 AND status NOT LIKE '%H+%' AND status NOT LIKE '%>H+%' AND LOCATE(',', status) > 0 THEN SUBSTRING_INDEX(status, ',', 1)
                ELSE status
            END AS statuspanen")
            ) // Change the format to "%Y-%m-%d"
            ->whereBetween('sidak_tph.datetime', [$startDate, $endDate])
            ->orderBy('status', 'asc')
            ->get();

        $ancakFA = $ancakFA->groupBy(['est', 'afd', 'statuspanen', 'tanggal', 'blok']);
        $ancakFA = json_decode($ancakFA, true);


        $dateString = $startDate;
        $dateParts = date_parse($dateString);
        $year = $dateParts['year'];
        $month = $dateParts['month'];

        // dd($ancakFA);
        $year = $year; // Replace with the desired year
        $month = $month;   // Replace with the desired month (September in this example)


        if ($regional == 3) {

            $weeks = [];
            $firstDayOfMonth = strtotime("$year-$month-01");
            $lastDayOfMonth = strtotime(date('Y-m-t', $firstDayOfMonth));

            $weekNumber = 1;

            // Find the first Saturday of the month or the last Saturday of the previous month
            $firstSaturday = strtotime("last Saturday", $firstDayOfMonth);

            // Set the start date to the first Saturday
            $startDate = $firstSaturday;

            while ($startDate <= $lastDayOfMonth) {
                $endDate = strtotime("next Friday", $startDate);
                if ($endDate > $lastDayOfMonth) {
                    $endDate = $lastDayOfMonth;
                }

                $weeks[$weekNumber] = [
                    'start' => date('Y-m-d', $startDate),
                    'end' => date('Y-m-d', $endDate),
                ];

                // Update start date to the next Saturday
                $startDate = strtotime("next Saturday", $endDate);

                $weekNumber++;
            }
        } else {
            $weeks = [];
            $firstDayOfMonth = strtotime("$year-$month-01");
            $lastDayOfMonth = strtotime(date('Y-m-t', $firstDayOfMonth));

            $weekNumber = 1;
            $startDate = $firstDayOfMonth;

            while ($startDate <= $lastDayOfMonth) {
                $endDate = strtotime("next Sunday", $startDate);
                if ($endDate > $lastDayOfMonth) {
                    $endDate = $lastDayOfMonth;
                }

                $weeks[$weekNumber] = [
                    'start' => date('Y-m-d', $startDate),
                    'end' => date('Y-m-d', $endDate),
                ];

                $nextMonday = strtotime("next Monday", $endDate);

                // Check if the next Monday is still within the current month.
                if (date('m', $nextMonday) == $month) {
                    $startDate = $nextMonday;
                } else {
                    // If the next Monday is in the next month, break the loop.
                    break;
                }

                $weekNumber++;
            }
        }



        $WeekStatus = [];

        // dd($weeks);
        // dd($startDate, $endDate, $weeks);

        foreach ($ancakFA as $key => $value) {
            $WeekStatus[$key] = [];

            foreach ($value as $estKey => $est) {
                $WeekStatus[$key][$estKey] = [];

                foreach ($weeks as $weekKey => $week) {
                    // dd($weekKey);
                    $WeekStatus[$key][$estKey]["week" . ($weekKey + 0)] = [];

                    foreach ($est as $statusKey => $status) {
                        $newStatus = [];

                        foreach ($status as $date => $data) {
                            if (strtotime($date) >= strtotime($week["start"]) && strtotime($date) <= strtotime($week["end"])) {
                                $newStatus[$date] = $data;
                            }
                        }

                        if (!empty($newStatus)) {
                            $WeekStatus[$key][$estKey]["week" . ($weekKey + 0)][$statusKey] = $newStatus;
                        }
                    }
                }
            }
        }
        // dd($WeekStatus);
        $defaultWeek = array();

        foreach ($queryEste as $est) {
            foreach ($queryAfd as $afd) {
                if ($est['est'] == $afd['est']) {
                    $defaultWeek[$est['est']][$afd['nama']] = 0;
                }
            }
        }

        // dd($defaultWeek);
        foreach ($defaultWeek as $key => $estValue) {
            foreach ($estValue as $monthKey => $monthValue) {
                foreach ($WeekStatus as $dataKey => $dataValue) {

                    if ($dataKey == $key) {
                        foreach ($dataValue as $dataEstKey => $dataEstValue) {

                            if ($dataEstKey == $monthKey) {
                                $defaultWeek[$key][$monthKey] = $dataEstValue;
                            }
                        }
                    }
                }
            }
        }
        $dividen = [];

        foreach ($defaultWeek as $key => $value) {
            foreach ($value as $key1 => $value1) if (is_array($value1)) {
                foreach ($value1 as $key2 => $value2) if (is_array($value2)) {

                    $dividenn = count($value1);
                }
                $dividen[$key][$key1]['dividen'] = $dividenn;
            } else {
                $dividen[$key][$key1]['dividen'] = 0;
            }
        }

        $newSidak = array();
        foreach ($defaultWeek as $key => $value) {
            $dividen_afd = 0;
            $total_skoreest = 0;
            $tot_estAFd = 0;
            $new_dvdAfd = 0;
            $new_dvdAfdest = 0;
            $total_estkors = 0;
            $total_skoreafd = 0;

            $deviden = 0;
            $devest = count($value);
            // dd($devest);
            // dd($value);
            $v2check5 = 0;
            $newpembagi3 = 0;
            $divwil2 = 0;
            $total_brondolanchart3 = 0;
            $total_janjangchart3 = 0;
            foreach ($value as $key1 => $value2)  if (is_array($value2)) {

                $tot_afdscore = 0;
                $totskor_brd1 = 0;
                $totskor_janjang1 = 0;
                $total_skoreest = 0;
                $newpembagi1 = 0;
                $v2check4 = 0;
                $total_brondolanchart2 = 0;
                $total_janjangchart2 = 0;
                foreach ($value2 as $key2 => $value3) {


                    $total_brondolan = 0;
                    $total_janjang = 0;
                    $tod_brd = 0;
                    $tod_jjg = 0;
                    $totskor_brd = 0;
                    $totskor_janjang = 0;
                    $tot_brdxm = 0;
                    $tod_janjangxm = 0;
                    $v2check3 = 0;
                    $total_brondolanchart1 = 0;
                    $total_janjangchart1 = 0;

                    foreach ($value3 as $key3 => $value4) if (is_array($value4)) {
                        $tph1 = 0;
                        $jalan1 = 0;
                        $bin1 = 0;
                        $karung1 = 0;
                        $buah1 = 0;
                        $restan1 = 0;
                        $v2check2 = 0;

                        foreach ($value4 as $key4 => $value5) if (is_array($value5)) {
                            $tph = 0;
                            $jalan = 0;
                            $bin = 0;
                            $karung = 0;
                            $buah = 0;
                            $restan = 0;
                            $v2check = count($value5);
                            foreach ($value5 as $key5 => $value6) {
                                $sum_bt_tph = 0;
                                $sum_bt_jalan = 0;
                                $sum_bt_bin = 0;
                                $sum_jum_karung = 0;
                                $sum_buah_tinggal = 0;
                                $sum_restan_unreported = 0;
                                $sum_all_restan_unreported = 0;

                                foreach ($value6 as $key6 => $value7) {
                                    // dd($value7);
                                    // dd($value7);
                                    $sum_bt_tph += $value7['bt_tph'];
                                    $sum_bt_jalan += $value7['bt_jalan'];
                                    $sum_bt_bin += $value7['bt_bin'];
                                    $sum_jum_karung += $value7['jum_karung'];


                                    $sum_buah_tinggal += $value7['buah_tinggal'];
                                    $sum_restan_unreported += $value7['restan_unreported'];
                                }
                                $newSidak[$key][$key1][$key2][$key3][$key4][$key5]['tph'] = $sum_bt_tph;
                                $newSidak[$key][$key1][$key2][$key3][$key4][$key5]['jalan'] = $sum_bt_jalan;
                                $newSidak[$key][$key1][$key2][$key3][$key4][$key5]['bin'] = $sum_bt_bin;
                                $newSidak[$key][$key1][$key2][$key3][$key4][$key5]['karung'] = $sum_jum_karung;

                                $newSidak[$key][$key1][$key2][$key3][$key4][$key5]['buah'] = $sum_buah_tinggal;
                                $newSidak[$key][$key1][$key2][$key3][$key4][$key5]['restan'] = $sum_restan_unreported;


                                $tph += $sum_bt_tph;
                                $jalan += $sum_bt_jalan;
                                $bin += $sum_bt_bin;
                                $karung += $sum_jum_karung;
                                $buah += $sum_buah_tinggal;
                                $restan += $sum_restan_unreported;
                            }

                            $newSidak[$key][$key1][$key2][$key3][$key4]['tph'] = $tph;
                            $newSidak[$key][$key1][$key2][$key3][$key4]['jalan'] = $jalan;
                            $newSidak[$key][$key1][$key2][$key3][$key4]['bin'] = $bin;
                            $newSidak[$key][$key1][$key2][$key3][$key4]['karung'] = $karung;

                            $newSidak[$key][$key1][$key2][$key3][$key4]['buah'] = $buah;
                            $newSidak[$key][$key1][$key2][$key3][$key4]['restan'] = $restan;
                            $newSidak[$key][$key1][$key2][$key3][$key4]['v2check'] = $v2check;

                            $tph1 += $tph;
                            $jalan1 += $jalan;
                            $bin1 += $bin;
                            $karung1 += $karung;
                            $buah1 += $buah;
                            $restan1 += $restan;
                            $v2check2 += $v2check;
                        }
                        // dd($key3);
                        $status_panen = $key3;

                        if ($dataparams === 'new') {
                            [$panen_brd, $panen_jjg] = calculatePanennew($status_panen);
                        } else {
                            [$panen_brd, $panen_jjg] = calculatePanen($status_panen);
                        }


                        // untuk brondolan gabungan dari bt-tph,bt-jalan,bt-bin,jum-karung 
                        $total_brondolan =  round(($tph1 + $jalan1 + $bin1 + $karung1) * $panen_brd / 100, 1);
                        $total_brondolanchart = $tph1 + $jalan1 + $bin1 + $karung1;
                        $total_janjang =  round(($buah1 + $restan1) * $panen_jjg / 100, 1);
                        $total_janjangchart =  $buah1 + $restan1;
                        $tod_brd = $tph1 + $jalan1 + $bin1 + $karung1;
                        $tod_jjg = $buah1 + $restan1;
                        $newSidak[$key][$key1][$key2][$key3]['tphx'] = $tph1;
                        $newSidak[$key][$key1][$key2][$key3]['jalan'] = $jalan1;
                        $newSidak[$key][$key1][$key2][$key3]['bin'] = $bin1;
                        $newSidak[$key][$key1][$key2][$key3]['karung'] = $karung1;
                        $newSidak[$key][$key1][$key2][$key3]['tot_brd'] = $tod_brd;

                        $newSidak[$key][$key1][$key2][$key3]['buah'] = $buah1;
                        $newSidak[$key][$key1][$key2][$key3]['restan'] = $restan1;
                        $newSidak[$key][$key1][$key2][$key3]['skor_brd'] = $total_brondolan;
                        $newSidak[$key][$key1][$key2][$key3]['skor_janjang'] = $total_janjang;
                        $newSidak[$key][$key1][$key2][$key3]['tod_jjg'] = $tod_jjg;
                        $newSidak[$key][$key1][$key2][$key3]['v2check2'] = $v2check2;

                        $totskor_brd += $total_brondolan;
                        $totskor_janjang += $total_janjang;
                        $tot_brdxm += $tod_brd;
                        $tod_janjangxm += $tod_jjg;
                        $v2check3 += $v2check2;
                        $total_brondolanchart1 += $total_brondolanchart;
                        $total_janjangchart1 += $total_janjangchart;
                    } else {
                        $newSidak[$key][$key1][$key2][$key3]['tphx'] = 0;
                        $newSidak[$key][$key1][$key2][$key3]['jalan'] = 0;
                        $newSidak[$key][$key1][$key2][$key3]['bin'] = 0;
                        $newSidak[$key][$key1][$key2][$key3]['karung'] = 0;

                        $newSidak[$key][$key1][$key2][$key3]['buah'] = 0;
                        $newSidak[$key][$key1][$key2][$key3]['restan'] = 0;

                        $newSidak[$key][$key1][$key2][$key3]['skor_brd'] = 0;
                        $newSidak[$key][$key1][$key2][$key3]['skor_janjang'] = 0;
                        $newSidak[$key][$key1][$key2][$key3]['tot_brd'] = 0;
                        $newSidak[$key][$key1][$key2][$key3]['tod_jjg'] = 0;
                        $newSidak[$key][$key1][$key2][$key3]['v2check2'] = 0;
                    }


                    $total_estkors = $totskor_brd + $totskor_janjang;
                    if ($total_estkors != 0) {

                        $checkscore = 100 - ($total_estkors);

                        if ($checkscore < 0) {
                            $newscore = 0;
                            $newSidak[$key][$key1][$key2]['mines'] = 'ada';
                        } else {
                            $newscore = $checkscore;
                            $newSidak[$key][$key1][$key2]['mines'] = 'tidak';
                        }

                        $newSidak[$key][$key1][$key2]['all_score'] = $newscore;
                        $newSidak[$key][$key1][$key2]['check_data'] = 'ada';

                        $total_skoreafd = $newscore;
                        $newpembagi = 1;
                    } else if ($v2check3 != 0) {
                        $checkscore = 100 - ($total_estkors);

                        if ($checkscore < 0) {
                            $newscore = 0;
                            $newSidak[$key][$key1][$key2]['mines'] = 'ada';
                        } else {
                            $newscore = $checkscore;
                            $newSidak[$key][$key1][$key2]['mines'] = 'tidak';
                        }
                        $newSidak[$key][$key1][$key2]['all_score'] = 100 - ($total_estkors);
                        $newSidak[$key][$key1][$key2]['check_data'] = 'ada';

                        $total_skoreafd = $newscore;

                        $newpembagi = 1;
                    } else {
                        $newSidak[$key][$key1][$key2]['all_score'] = 0;
                        $newSidak[$key][$key1][$key2]['check_data'] = 'null';
                        $total_skoreafd = 0;
                        $newpembagi = 0;
                    }
                    // $newSidak[$key][$key1][$key2]['all_score'] = 100 - ($total_estkors);
                    $newSidak[$key][$key1][$key2]['total_brd'] = $tot_brdxm;
                    $newSidak[$key][$key1][$key2]['total_brdSkor'] = $totskor_brd;
                    $newSidak[$key][$key1][$key2]['total_janjang'] = $tod_janjangxm;
                    $newSidak[$key][$key1][$key2]['total_janjangSkor'] = $totskor_janjang;
                    $newSidak[$key][$key1][$key2]['total_skor'] = $total_skoreafd;
                    $newSidak[$key][$key1][$key2]['janjang_brd'] = $totskor_brd + $totskor_janjang;
                    $newSidak[$key][$key1][$key2]['v2check3'] = $v2check3;
                    $newSidak[$key][$key1][$key2]['newpembagi'] = $newpembagi;
                    $newSidak[$key][$key1][$key2]['chartrst'] = $total_brondolanchart1;
                    $newSidak[$key][$key1][$key2]['chartrst'] = $total_janjangchart1;

                    $totskor_brd1 += $totskor_brd;
                    $totskor_janjang1 += $totskor_janjang;
                    $total_skoreest += $total_skoreafd;
                    $newpembagi1 += $newpembagi;
                    $v2check4 += $v2check3;
                    $total_brondolanchart2 += $total_brondolanchart1;
                    $total_janjangchart2 += $total_janjangchart1;
                }



                // dd($deviden);

                $namaGM = '-';
                foreach ($queryAsisten as $asisten) {

                    // dd($asisten);
                    if ($asisten['est'] == $key && $asisten['afd'] == $key1) {
                        $namaGM = $asisten['nama'];
                        break;
                    }
                }

                $deviden = count($value2);

                $new_dvd = $dividen_x ?? 0;
                $new_dvdest = $devidenEst_x ?? 0;


                if ($v2check4 != 0 && $total_skoreest == 0) {
                    $tot_afdscore = 100;
                    $newpembagi2 = 1;
                } else if ($v2check4 != 0) {
                    $tot_afdscore = round($total_skoreest / $newpembagi1, 1);
                    $newpembagi2 = 1;
                } else if ($newpembagi1 == 0 && $v2check4 == 0) {
                    $tot_afdscore = 0;
                    $newpembagi2 = 0;
                }


                if ($tot_afdscore < 0) {
                    # code...
                    $newscore = 0;
                } else {
                    $newscore = $tot_afdscore;
                }
                // $newSidak[$key][$key1]['deviden'] = $deviden;

                $newSidak[$key][$key1]['total_brd'] = $totskor_brd1;
                $newSidak[$key][$key1]['total_janjang'] = $totskor_janjang1;
                $newSidak[$key][$key1]['new_deviden'] = $new_dvd;
                $newSidak[$key][$key1]['nama'] = $namaGM;
                $newSidak[$key][$key1]['total_skoreest'] = $total_skoreest;
                $newSidak[$key][$key1]['chartbrd'] = $total_brondolanchart2;
                $newSidak[$key][$key1]['chartrst'] = $total_janjangchart2;
                if ($v2check4 == 0) {
                    $newSidak[$key][$key1]['total_score'] = '-';
                } else {
                    $newSidak[$key][$key1]['total_score'] = $newscore;
                }

                $newSidak[$key][$key1]['est'] = $key;
                $newSidak[$key][$key1]['afd'] = $key1;
                $newSidak[$key][$key1]['devidenest'] = $newpembagi1;
                $newSidak[$key][$key1]['v2check4'] = $v2check4;
                if ($v2check4 != 0) {
                    $newSidak[$key][$key1]['divwil'] = 1;
                    $divwil = 1;
                } else {
                    $newSidak[$key][$key1]['divwil'] = 0;
                    $divwil = 0;
                }


                $tot_estAFd += $newscore;
                $new_dvdAfd += $new_dvd;
                $new_dvdAfdest += $new_dvdest;
                $v2check5 += $v2check4;
                $newpembagi3 += $newpembagi2;
                $divwil2 += $divwil;
                $total_brondolanchart3 += $total_brondolanchart2;
                $total_janjangchart3 += $total_janjangchart2;
            } else {
                $namaGMx = '-';
                foreach ($queryAsisten as $asisten) {

                    // dd($asisten);
                    if ($asisten['est'] == $key && $asisten['afd'] == $key1) {
                        $namaGMx = $asisten['nama'];
                        break;
                    }
                }
                $newSidak[$key][$key1]['total_brd'] = 0;
                $newSidak[$key][$key1]['total_janjang'] = 0;
                $newSidak[$key][$key1]['new_deviden'] = 0;
                $newSidak[$key][$key1]['nama'] = $namaGMx;
                $newSidak[$key][$key1]['total_skoreest'] = 0;
                $newSidak[$key][$key1]['chartbrd'] = 0;
                $newSidak[$key][$key1]['chartrst'] = 0;

                $newSidak[$key][$key1]['total_score'] = '-';

                $newSidak[$key][$key1]['est'] = $key;
                $newSidak[$key][$key1]['afd'] = $key1;
                $newSidak[$key][$key1]['devidenest'] = 0;
                $newSidak[$key][$key1]['v2check4'] = 0;
                $newSidak[$key][$key1]['divwil'] = 0;
            }

            if ($v2check5 != 0) {
                $total_skoreest = round($tot_estAFd / $newpembagi3, 1);
            } else if ($v2check5 != 0 && $tot_estAFd == 0) {
                $total_skoreest = 100;
            } else {
                $total_skoreest = '-';
            }

            // dd($value);

            $namaGM = '-';
            foreach ($queryAsisten as $asisten) {
                if ($asisten['est'] == $key && $asisten['afd'] == 'EM') {
                    $namaGM = $asisten['nama'];
                    break;
                }
            }
            if ($v2check5 != 0) {
                $deviden = 1;
            } else {
                $deviden = 0;
            }

            $newSidak[$key]['est'] = [
                'total_skorest' => $tot_estAFd,
                'total_score' => $total_skoreest,
                'nama' => $namaGM,
                'est' => $key,
                'deviden' => $deviden,
                'afd' => 'EM',
                'afdeling' => $newpembagi3,
                'v2check5' => $v2check5,
                'divwil2' => $divwil2,
                'chartbrd' => $total_brondolanchart3,
                'chartrst' => $total_janjangchart3,
            ];
        }
        // dd($newSidak);

        $sidaktph = array();
        foreach ($queryEste as $key => $value) {
            foreach ($newSidak as $key2 => $value2) {
                if ($value['est'] == $key2) {
                    $sidaktph[$value['wil']][$key2] = array_merge($sidaktph[$value['wil']][$key2] ?? [], $value2);
                }
            }
        }

        if ($regional == 1) {
            $defaultweekmua = array();

            foreach ($muaest as $est) {
                foreach ($queryAfd as $afd) {
                    if ($est['est'] == $afd['est']) {
                        $defaultweekmua[$est['est']][$afd['est']] = 0;
                    }
                }
            }
            foreach ($defaultweekmua as $key => $estValue) {
                foreach ($estValue as $monthKey => $monthValue) {
                    foreach ($WeekStatus as $dataKey => $dataValue) {

                        if ($dataKey == $key) {
                            foreach ($dataValue as $dataEstKey => $dataEstValue) {

                                if ($dataEstKey == $monthKey) {
                                    $defaultweekmua[$key][$monthKey] = $dataEstValue;
                                }
                            }
                        }
                    }
                }
            }
            $dividenmua = [];

            foreach ($defaultweekmua as $key => $value) {
                foreach ($value as $key1 => $value1) if (is_array($value1)) {
                    foreach ($value1 as $key2 => $value2) if (is_array($value2)) {

                        $dividenn = count($value1);
                    }
                    $dividenmua[$key][$key1]['dividen'] = $dividenn;
                } else {
                    $dividenmua[$key][$key1]['dividen'] = 0;
                }
            }

            $tot_estAFdx = 0;
            $new_dvdAfdx = 0;
            $new_dvdAfdesx = 0;
            $v2check5x = 0;
            $chartbrd4 = 0;
            $chartrst4 = 0;
            $newSidak_mua = array();

            foreach ($defaultweekmua as $key => $value) {
                $total_skoreest = 0;
                $tot_estAFd = 0;
                $new_dvdAfd = 0;
                $new_dvdAfdest = 0;
                $total_estkors = 0;
                $total_skoreafd = 0;
                $devest = count($value);
                // dd($devest);
                // dd($value);
                $v2check5 = 0;
                $newpembagi3 = 0;
                $chartbrd3 = 0;
                $chartrst3 = 0;
                foreach ($value as $key1 => $value2)  if (is_array($value2)) {

                    $tot_afdscore = 0;
                    $totskor_brd1 = 0;
                    $totskor_janjang1 = 0;
                    $total_skoreest = 0;
                    $newpembagi1 = 0;
                    $v2check4 = 0;
                    $chartbrd2 = 0;
                    $chartrst2 = 0;
                    foreach ($value2 as $key2 => $value3) {


                        $total_brondolan = 0;
                        $total_janjang = 0;
                        $tod_brd = 0;
                        $tod_jjg = 0;
                        $totskor_brd = 0;
                        $totskor_janjang = 0;
                        $tot_brdxm = 0;
                        $tod_janjangxm = 0;
                        $v2check3 = 0;
                        $chartbrd1 = 0;
                        $chartrst1 = 0;

                        foreach ($value3 as $key3 => $value4) if (is_array($value4)) {
                            $tph1 = 0;
                            $jalan1 = 0;
                            $bin1 = 0;
                            $karung1 = 0;
                            $buah1 = 0;
                            $restan1 = 0;
                            $v2check2 = 0;

                            foreach ($value4 as $key4 => $value5) if (is_array($value5)) {
                                $tph = 0;
                                $jalan = 0;
                                $bin = 0;
                                $karung = 0;
                                $buah = 0;
                                $restan = 0;
                                $v2check = count($value5);
                                foreach ($value5 as $key5 => $value6) {
                                    $sum_bt_tph = 0;
                                    $sum_bt_jalan = 0;
                                    $sum_bt_bin = 0;
                                    $sum_jum_karung = 0;
                                    $sum_buah_tinggal = 0;
                                    $sum_restan_unreported = 0;
                                    $sum_all_restan_unreported = 0;

                                    foreach ($value6 as $key6 => $value7) {
                                        // dd($value7);
                                        // dd($value7);
                                        $sum_bt_tph += $value7['bt_tph'];
                                        $sum_bt_jalan += $value7['bt_jalan'];
                                        $sum_bt_bin += $value7['bt_bin'];
                                        $sum_jum_karung += $value7['jum_karung'];


                                        $sum_buah_tinggal += $value7['buah_tinggal'];
                                        $sum_restan_unreported += $value7['restan_unreported'];
                                    }
                                    $newSidak_mua[$key][$key1][$key2][$key3][$key4][$key5]['tph'] = $sum_bt_tph;
                                    $newSidak_mua[$key][$key1][$key2][$key3][$key4][$key5]['jalan'] = $sum_bt_jalan;
                                    $newSidak_mua[$key][$key1][$key2][$key3][$key4][$key5]['bin'] = $sum_bt_bin;
                                    $newSidak_mua[$key][$key1][$key2][$key3][$key4][$key5]['karung'] = $sum_jum_karung;

                                    $newSidak_mua[$key][$key1][$key2][$key3][$key4][$key5]['buah'] = $sum_buah_tinggal;
                                    $newSidak_mua[$key][$key1][$key2][$key3][$key4][$key5]['restan'] = $sum_restan_unreported;


                                    $tph += $sum_bt_tph;
                                    $jalan += $sum_bt_jalan;
                                    $bin += $sum_bt_bin;
                                    $karung += $sum_jum_karung;
                                    $buah += $sum_buah_tinggal;
                                    $restan += $sum_restan_unreported;
                                }

                                $newSidak_mua[$key][$key1][$key2][$key3][$key4]['tph'] = $tph;
                                $newSidak_mua[$key][$key1][$key2][$key3][$key4]['jalan'] = $jalan;
                                $newSidak_mua[$key][$key1][$key2][$key3][$key4]['bin'] = $bin;
                                $newSidak_mua[$key][$key1][$key2][$key3][$key4]['karung'] = $karung;

                                $newSidak_mua[$key][$key1][$key2][$key3][$key4]['buah'] = $buah;
                                $newSidak_mua[$key][$key1][$key2][$key3][$key4]['restan'] = $restan;
                                $newSidak_mua[$key][$key1][$key2][$key3][$key4]['v2check'] = $v2check;

                                $tph1 += $tph;
                                $jalan1 += $jalan;
                                $bin1 += $bin;
                                $karung1 += $karung;
                                $buah1 += $buah;
                                $restan1 += $restan;
                                $v2check2 += $v2check;
                            }
                            // dd($key3);
                            $status_panen = $key3;
                            if ($dataparams === 'new') {
                                [$panen_brd, $panen_jjg] = calculatePanennew($status_panen);
                            } else {
                                [$panen_brd, $panen_jjg] = calculatePanen($status_panen);
                            }



                            // untuk brondolan gabungan dari bt-tph,bt-jalan,bt-bin,jum-karung 
                            $total_brondolan =  round(($tph1 + $jalan1 + $bin1 + $karung1) * $panen_brd / 100, 1);
                            $total_janjang =  round(($buah1 + $restan1) * $panen_jjg / 100, 1);
                            $chartbrd =  $tph1 + $jalan1 + $bin1 + $karung1;
                            $chartrst =  $buah1 + $restan1;
                            $tod_brd = $tph1 + $jalan1 + $bin1 + $karung1;
                            $tod_jjg = $buah1 + $restan1;
                            $newSidak_mua[$key][$key1][$key2][$key3]['tphx'] = $tph1;
                            $newSidak_mua[$key][$key1][$key2][$key3]['jalan'] = $jalan1;
                            $newSidak_mua[$key][$key1][$key2][$key3]['bin'] = $bin1;
                            $newSidak_mua[$key][$key1][$key2][$key3]['karung'] = $karung1;
                            $newSidak_mua[$key][$key1][$key2][$key3]['tot_brd'] = $tod_brd;

                            $newSidak_mua[$key][$key1][$key2][$key3]['buah'] = $buah1;
                            $newSidak_mua[$key][$key1][$key2][$key3]['restan'] = $restan1;
                            $newSidak_mua[$key][$key1][$key2][$key3]['skor_brd'] = $total_brondolan;
                            $newSidak_mua[$key][$key1][$key2][$key3]['skor_janjang'] = $total_janjang;
                            $newSidak_mua[$key][$key1][$key2][$key3]['tod_jjg'] = $tod_jjg;
                            $newSidak_mua[$key][$key1][$key2][$key3]['v2check2'] = $v2check2;

                            $totskor_brd += $total_brondolan;
                            $totskor_janjang += $total_janjang;
                            $tot_brdxm += $tod_brd;
                            $tod_janjangxm += $tod_jjg;
                            $v2check3 += $v2check2;
                            $chartbrd1 += $chartbrd;
                            $chartrst1 += $chartrst;
                        } else {
                            $newSidak_mua[$key][$key1][$key2][$key3]['tphx'] = 0;
                            $newSidak_mua[$key][$key1][$key2][$key3]['jalan'] = 0;
                            $newSidak_mua[$key][$key1][$key2][$key3]['bin'] = 0;
                            $newSidak_mua[$key][$key1][$key2][$key3]['karung'] = 0;

                            $newSidak_mua[$key][$key1][$key2][$key3]['buah'] = 0;
                            $newSidak_mua[$key][$key1][$key2][$key3]['restan'] = 0;

                            $newSidak_mua[$key][$key1][$key2][$key3]['skor_brd'] = 0;
                            $newSidak_mua[$key][$key1][$key2][$key3]['skor_janjang'] = 0;
                            $newSidak_mua[$key][$key1][$key2][$key3]['tot_brd'] = 0;
                            $newSidak_mua[$key][$key1][$key2][$key3]['tod_jjg'] = 0;
                            $newSidak_mua[$key][$key1][$key2][$key3]['v2check2'] = 0;
                        }


                        $total_estkors = $totskor_brd + $totskor_janjang;
                        if ($total_estkors != 0) {

                            $checkscore = 100 - ($total_estkors);

                            if ($checkscore < 0) {
                                $newscore = 0;
                                $newSidak_mua[$key][$key1][$key2]['mines'] = 'ada';
                            } else {
                                $newscore = $checkscore;
                                $newSidak_mua[$key][$key1][$key2]['mines'] = 'tidak';
                            }

                            $newSidak_mua[$key][$key1][$key2]['all_score'] = $newscore;
                            $newSidak_mua[$key][$key1][$key2]['check_data'] = 'ada';

                            $total_skoreafd = $newscore;
                            $newpembagi = 1;
                        } else if ($v2check3 != 0) {
                            $checkscore = 100 - ($total_estkors);

                            if ($checkscore < 0) {
                                $newscore = 0;
                                $newSidak_mua[$key][$key1][$key2]['mines'] = 'ada';
                            } else {
                                $newscore = $checkscore;
                                $newSidak_mua[$key][$key1][$key2]['mines'] = 'tidak';
                            }
                            $newSidak_mua[$key][$key1][$key2]['all_score'] = 100 - ($total_estkors);
                            $newSidak_mua[$key][$key1][$key2]['check_data'] = 'ada';

                            $total_skoreafd = $newscore;

                            $newpembagi = 1;
                        } else {
                            $newSidak_mua[$key][$key1][$key2]['all_score'] = 0;
                            $newSidak_mua[$key][$key1][$key2]['check_data'] = 'null';
                            $total_skoreafd = 0;
                            $newpembagi = 0;
                        }
                        // $newSidak_mua[$key][$key1][$key2]['all_score'] = 100 - ($total_estkors);
                        $newSidak_mua[$key][$key1][$key2]['total_brd'] = $tot_brdxm;
                        $newSidak_mua[$key][$key1][$key2]['total_brdSkor'] = $totskor_brd;
                        $newSidak_mua[$key][$key1][$key2]['total_janjang'] = $tod_janjangxm;
                        $newSidak_mua[$key][$key1][$key2]['total_janjangSkor'] = $totskor_janjang;
                        $newSidak_mua[$key][$key1][$key2]['total_skor'] = $total_skoreafd;
                        $newSidak_mua[$key][$key1][$key2]['janjang_brd'] = $totskor_brd + $totskor_janjang;
                        $newSidak_mua[$key][$key1][$key2]['v2check3'] = $v2check3;
                        $newSidak_mua[$key][$key1][$key2]['newpembagi'] = $newpembagi;

                        $totskor_brd1 += $totskor_brd;
                        $totskor_janjang1 += $totskor_janjang;
                        $total_skoreest += $total_skoreafd;
                        $newpembagi1 += $newpembagi;
                        $v2check4 += $v2check3;
                        $chartbrd2 += $chartbrd1;
                        $chartrst2 += $chartrst1;
                    }



                    // dd($deviden);

                    $namaGM = '-';
                    foreach ($queryAsisten as $asisten) {

                        // dd($asisten);
                        if ($asisten['est'] == $key && $asisten['afd'] == $key1) {
                            $namaGM = $asisten['nama'];
                            break;
                        }
                    }

                    $deviden = count($value2);

                    $new_dvd = $dividen_x ?? 0;
                    $new_dvdest = $devidenEst_x ?? 0;


                    $total_estkors = $totskor_brd1 + $totskor_janjang1;
                    if ($total_estkors != 0) {

                        // $checkscore = 100 - ($total_estkors);
                        $checkscore = round($total_skoreest / $newpembagi1, 1);
                        if ($checkscore < 0) {
                            $newscore = 0;
                            $newSidak_mua[$key][$key1][$key2]['mines'] = 'ada';
                        } else {
                            $newscore = $checkscore;
                            $newSidak_mua[$key][$key1][$key2]['mines'] = 'tidak';
                        }

                        $newSidak_mua[$key][$key1]['all_score'] = $newscore;
                        $newSidak_mua[$key][$key1]['check_data'] = 'ada';

                        $total_skoreafd = $newscore;
                        $newpembagi2 = 1;
                    } else if ($v2check4 != 0) {
                        $checkscore = round($total_skoreest / $newpembagi1, 1);
                        if ($checkscore < 0) {
                            $newscore = 0;
                            $newSidak_mua[$key][$key1]['mines'] = 'ada';
                        } else {
                            $newscore = $checkscore;
                            $newSidak_mua[$key][$key1]['mines'] = 'tidak';
                        }
                        $newSidak_mua[$key][$key1]['all_score'] = 100 - ($total_estkors);
                        $newSidak_mua[$key][$key1]['check_data'] = 'ada';

                        $total_skoreafd = $newscore;

                        $newpembagi2 = 1;
                        $newSidak_mua[$key][$key1]['checkdata'] = 'ada';
                    } else {
                        $newSidak_mua[$key][$key1]['all_score'] = 0;
                        $newSidak_mua[$key][$key1]['check_data'] = 'null';
                        $total_skoreafd = 0;
                        $newpembagi2 = 0;
                        $newSidak_mua[$key][$key1]['checkdata'] = 'kosong';
                    }
                    $newSidak_mua[$key][$key1]['total_brd'] = $totskor_brd1;
                    $newSidak_mua[$key][$key1]['total_janjang'] = $totskor_janjang1;
                    $newSidak_mua[$key][$key1]['new_deviden'] = $new_dvd;
                    $newSidak_mua[$key][$key1]['asisten'] = $namaGM;
                    $newSidak_mua[$key][$key1]['total_skoreest'] = $total_skoreest;
                    if ($v2check4 == 0) {
                        $newSidak_mua[$key][$key1]['total_score'] = '-';
                    } else {
                        $newSidak_mua[$key][$key1]['total_score'] = $newscore;
                    }

                    $newSidak_mua[$key][$key1]['est'] = $key;
                    $newSidak_mua[$key][$key1]['afd'] = $key1;
                    $newSidak_mua[$key][$key1]['devidenest'] = $newpembagi1;
                    $newSidak_mua[$key][$key1]['v2check4'] = $v2check4;

                    $tot_estAFd += $newscore;
                    $new_dvdAfd += $new_dvd;
                    $new_dvdAfdest += $new_dvdest;
                    $v2check5 += $v2check4;
                    $newpembagi3 += $newpembagi2;
                    $chartbrd3 += $chartbrd2;
                    $chartrst3 += $chartrst2;
                } else {
                    $newSidak_mua[$key][$key1]['total_brd'] = 0;
                    $newSidak_mua[$key][$key1]['total_janjang'] = 0;
                    $newSidak_mua[$key][$key1]['new_deviden'] = 0;
                    $newSidak_mua[$key][$key1]['asisten'] = 0;
                    $newSidak_mua[$key][$key1]['total_skoreest'] = 0;
                    $newSidak_mua[$key][$key1]['total_score'] = '-';
                    $newSidak_mua[$key][$key1]['est'] = $key;
                    $newSidak_mua[$key][$key1]['checkdata'] = 'kosong';
                    $newSidak_mua[$key][$key1]['afd'] = $key1;
                    $newSidak_mua[$key][$key1]['devidenest'] = 0;
                    $newSidak_mua[$key][$key1]['v2check4'] = 0;
                }


                if ($v2check5 != 0) {
                    $total_skoreest = round($tot_estAFd / $newpembagi3, 1);
                    $newSidak_mua[$key]['checkdata'] = 'ada';
                } else if ($v2check5 != 0 && $tot_estAFd == 0) {
                    $total_skoreest = 100;
                    $newSidak_mua[$key]['checkdata'] = 'ada';
                } else {
                    $total_skoreest = 0;
                    $newSidak_mua[$key]['checkdata'] = 'kosong';
                }

                // dd($value);

                $namaGM = '-';
                foreach ($queryAsisten as $asisten) {
                    if ($asisten['est'] == $key && $asisten['afd'] == 'EM') {
                        $namaGM = $asisten['nama'];
                        break;
                    }
                }
                if ($new_dvdAfd != 0) {
                    $newSidak_mua[$key]['deviden'] = 1;
                } else {
                    $newSidak_mua[$key]['deviden'] = 0;
                }

                $newSidak_mua[$key]['total_skorest'] = $tot_estAFd;
                $newSidak_mua[$key]['score_estate'] = $total_skoreest;
                $newSidak_mua[$key]['asisten'] = $namaGM;
                $newSidak_mua[$key]['estate'] = $key;
                $newSidak_mua[$key]['afd'] = 'GM';
                $newSidak_mua[$key]['afdeling'] = $newpembagi3;
                $newSidak_mua[$key]['v2check5'] = $v2check5;
                if ($v2check5 != 0) {
                    $devidenlast = 1;
                } else {
                    $devidenlast = 0;
                }
                $devmuxa[] = $devidenlast;

                $tot_estAFdx  += $tot_estAFd;
                $new_dvdAfdx  += $new_dvdAfd;
                $new_dvdAfdesx += $new_dvdAfdest;
                $v2check5x += $v2check5;
                $chartbrd4 += $chartbrd3;
                $chartrst4 += $chartrst3;
            }
            $devmuxax = array_sum($devmuxa);

            if ($v2check5x != 0) {
                $total_skoreestxyz = round($tot_estAFdx / $devmuxax, 1);
                $checkdata = 'ada';
            } else if ($v2check5x != 0 && $devmuxax != 0) {
                $total_skoreestxyz = 0;
                $checkdata = 'ada';
            } else {
                $total_skoreestxyz = '-';
                $checkdata = 'kosong';
            }

            // dd($value);

            $namaGMnewSidak_mua = '-';
            foreach ($queryAsisten as $asisten) {
                if ($asisten['est'] == $key && $asisten['afd'] == 'EM') {
                    $namaGMnewSidak_mua = $asisten['nama'];
                    break;
                }
            }
            $newSidak_mua['PT.MUA'] = [
                'deviden' => $devmuxax,
                'checkdata' => $checkdata,
                'total_skorest' => $tot_estAFdx,
                'score_estate' => $total_skoreestxyz,
                'asisten' => $namaGM,
                'estate' => $key,
                'chartbrd' => $chartbrd4,
                'chartrst' => $chartrst4,
                'afd' => $namaGMnewSidak_mua,
                'afdeling' => $devmuxax,
                'v2check6' => $v2check5,
            ];


            $newSidak['PT.MUA']['est'] = $newSidak_mua['PT.MUA'];
            $testing['PT.MUA']['est']  = $newSidak_mua['PT.MUA'];

            $sidaktph[3] = array_merge($sidaktph[3], $testing);
        } else {
            $newSidak_mua = [];
        }

        // dd($newSidak);
        foreach ($newSidak as $key => $value) {
            // Check if the sub-array contains the "est" key
            if (isset($value['est'])) {
                // dd($value);
                $estValue = $value['est'];
                $listest[] = $key;
                $brdchart[] = $estValue['chartbrd'];
                $chartrstx[] = $estValue['chartrst'];
            }
        }

        // dd($sidaktph, $testing);

        // dd($newSidak, $listest, $brdchart, $chartrstx);
        $arr = array();
        $arr['rekapafd'] = $sidaktph;
        $arr['rekapmua'] = $newSidak_mua;
        $arr['listest'] = $listest;
        $arr['brdchart'] = $brdchart;
        $arr['chartrst'] = $chartrstx;
        echo json_encode($arr);
        exit();
    }

    public function getBtTphMonth(Request $request)
    {

        $regSidak = $request->get('reg');
        $monthSidak = $request->get('month');

        $result = get_sidaktph_perbulan($monthSidak, $regSidak);

        // dd($result);

        // dd($mtancakWIltab1);

        return response()->json($result);
    }


    public function getBtTphYear(Request $request)
    {
        $regional = $request->get('reg');
        $tahun = $request->get('year');
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');


        $result = rekap_pertahun_sidaktph($regional, $tahun, $start_date, $end_date);

        // dd($result);

        $arr = array();
        $arr['newsidakend'] = $result['newsidakend'];
        $arr['rhdata'] = $result['rhdata'];
        $arr['rekapmua'] = $result['rekapmua'];

        echo json_encode($arr);
        exit();
    }
    public function graphFilterYear(Request $request)
    {
        $regData = $request->get('reg');
        $estData = $request->get('est');
        $yearGraph = $request->get('yearGraph');

        // dd($regData, $estData, $yearGraph);


        $queryAsisten = DB::connection('mysql2')->table('asisten_qc')
            ->select('asisten_qc.*')
            ->get();
        $queryAsisten = json_decode($queryAsisten, true);
        // dd($value2['datetime'], $endDate);
        $queryEste = DB::connection('mysql2')->table('estate')
            ->select('estate.*')
            ->where('estate.emp', '!=', 1)
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->where('wil.regional', $regData)
            ->where('estate.emp', '!=', 1)
            ->whereNotIn('estate.est', ['SRE', 'LDE'])
            ->get();
        $queryEste = json_decode($queryEste, true);

        $defafd = DB::connection('mysql2')->table('afdeling')
            ->select('afdeling.*', 'estate.*', 'afdeling.nama as afdnama')
            ->join('estate', 'estate.id', '=', 'afdeling.estate')
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->where('wil.regional', $regData)
            ->where('estate.emp', '!=', 1)
            ->get();
        $defafd = $defafd->groupBy(['wil', 'est', 'afdnama']);
        $defafd = json_decode($defafd, true);

        // dd($defafd);

        $datatph = [];


        $chunkSize = 1000;

        // Overview:
        // The CASE statement is like a series of if-else conditions used within SQL queries.

        // Breakdown:
        // WHEN status = '' THEN 1:

        // If status is an empty string, set statuspanen as 1.
        // WHEN status = '0' THEN 1:

        // If status is '0', also set statuspanen as 1.
        // WHEN LOCATE('H+', status) > 0 THEN ... and WHEN LOCATE('>H+', status) > 0 THEN ...:

        // If status contains 'H+' or '>H+', the subsequent SUBSTRING_INDEX extracts the portion after these substrings (e.g., 'H+11' -> '11').
        // If the extracted number is greater than 8, it sets statuspanen to 8; otherwise, it uses the extracted number.
        // WHEN status REGEXP '^[0-9]+$' AND status > 8 THEN '8':

        // If status contains only digits (0-9) and is greater than 8, it directly sets statuspanen to 8.
        // WHEN LENGTH(status) > 1 AND status NOT LIKE '%H+%' AND status NOT LIKE '%>H+%' AND LOCATE(',', status) > 0 THEN SUBSTRING_INDEX(status, ',', 1):

        // If the length of status is greater than 1 and doesn't contain 'H+' or '>H+', but has a comma, it extracts the portion before the comma as statuspanen.
        // ELSE status:

        // If none of the conditions match, it sets statuspanen as the original status value.
        // Overall Purpose:
        // This construction aims to provide a specific value for statuspanen based on different patterns and conditions observed in the status column, ensuring it's properly processed and normalized for further usage within the query result.
        DB::connection('mysql2')->table('sidak_tph')
            ->select(
                "sidak_tph.*",
                DB::raw('DATE_FORMAT(sidak_tph.datetime, "%M") as bulan'),
                DB::raw('DATE_FORMAT(sidak_tph.datetime, "%Y") as tahun'),
                DB::raw('DATE_FORMAT(sidak_tph.datetime, "%Y-%m-%d") as tanggal'),
                DB::raw("
            CASE 
            WHEN status = '' THEN 1
            WHEN status = '0' THEN 1
            WHEN LOCATE('>H+', status) > 0 THEN '8'
            WHEN LOCATE('H+', status) > 0 THEN 
                CASE 
                    WHEN SUBSTRING_INDEX(SUBSTRING_INDEX(status, 'H+', -1), ' ', 1) > 8 THEN '8'
                    ELSE SUBSTRING_INDEX(SUBSTRING_INDEX(status, 'H+', -1), ' ', 1)
                END
            WHEN status REGEXP '^[0-9]+$' AND status > 8 THEN '8'
            WHEN LENGTH(status) > 1 AND status NOT LIKE '%H+%' AND status NOT LIKE '%>H+%' AND LOCATE(',', status) > 0 THEN SUBSTRING_INDEX(status, ',', 1)
            ELSE status
        END AS statuspanen")
            )

            ->join('estate', 'estate.est', '=', 'sidak_tph.est')
            ->join('wil', 'wil.id', '=', 'estate.wil')
            // ->where('wil.regional', $regData)
            ->where('estate.est', '=', $estData)
            ->where('estate.emp', '!=', 1)
            ->whereYear('datetime', $yearGraph)
            ->orderBy('afd', 'asc')
            ->orderBy('datetime', 'asc')
            ->chunk($chunkSize, function ($results) use (&$datatph) {
                foreach ($results as $result) {
                    // Grouping logic here, if needed
                    $datatph[] = $result;
                    // Adjust this according to your grouping requirements
                }
            });


        $datatph = collect($datatph)->groupBy(['est', 'bulan', 'afd']);
        $ancakFA = json_decode($datatph, true);

        // dd($ancakFA);

        $default = DB::connection('mysql2')->table('afdeling')
            ->select('afdeling.*', 'estate.*', 'afdeling.nama as afdnama')
            ->join('estate', 'estate.id', '=', 'afdeling.estate')
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->where('estate.est', $estData)
            // ->where('wil.regional', $regData)
            ->where('estate.emp', '!=', 1)
            ->get();
        $default = $default->groupBy(['est', 'afdnama']);

        // dd($default);
        $default = json_decode($default, true);


        $montharr = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $arrdeff = [];
        // Iterate through BDE array
        foreach ($default as $bdeKey => $bdeValue) {
            // Initialize the nested array for the current BDE key
            $arrdeff[$bdeKey] = [];

            // Iterate through months
            foreach ($montharr as $month) {
                // Initialize the nested array for the current month
                $arrdeff[$bdeKey][$month] = [];

                // Iterate through subarrays (OA, OB, OC, OD, OE)
                foreach ($bdeValue as $subKey => $subValue) {
                    // Set the default value (0 in this case)
                    $arrdeff[$bdeKey][$month][$subKey] = 0;
                }
            }
        }

        // dd($arrdeff);


        // dd($ancakFA, $arrdeff);
        $result = [];

        foreach ($arrdeff as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $result[$key][$key1] = [];
                foreach ($ancakFA[$key][$key1] ?? [] as $key2 => $value2) {
                    $result[$key][$key1][$key2] = $value2;
                }
                foreach ($value1 as $key2 => $defaultValue) {
                    $result[$key][$key1][$key2] = $result[$key][$key1][$key2] ?? $defaultValue;
                }
            }
        }


        // dd($result);
        // dd($result, $arrdeff);

        // dd($arrdeff, $result, $finalarr);

        $newSidak = array();

        foreach ($result as $key => $value1) {
            foreach ($value1 as $key1 => $value2) {
                $tph = 0;
                $jalan = 0;
                $bin = 0;
                $karung = 0;
                $buah = 0;
                $restan = 0;
                foreach ($value2 as $key2 => $value3) if (is_array($value3)) {
                    $sum_bt_tph = 0;
                    $sum_bt_jalan = 0;
                    $sum_bt_bin = 0;
                    $sum_jum_karung = 0;
                    $sum_buah_tinggal = 0;
                    $sum_restan_unreported = 0;
                    foreach ($value3 as $key3 => $value4) {
                        $sum_bt_tph += $value4['bt_tph'];
                        $sum_bt_jalan += $value4['bt_jalan'];
                        $sum_bt_bin += $value4['bt_bin'];
                        $sum_jum_karung += $value4['jum_karung'];

                        $sum_buah_tinggal += $value4['buah_tinggal'];
                        $sum_restan_unreported += $value4['restan_unreported'];
                    }
                    $newSidak[$key][$key1][$key2]['tph'] = $sum_bt_tph;
                    $newSidak[$key][$key1][$key2]['jalan'] = $sum_bt_jalan;
                    $newSidak[$key][$key1][$key2]['bin'] = $sum_bt_bin;
                    $newSidak[$key][$key1][$key2]['karung'] = $sum_jum_karung;
                    $newSidak[$key][$key1][$key2]['buah'] = $sum_buah_tinggal;
                    $newSidak[$key][$key1][$key2]['restan'] = $sum_restan_unreported;

                    $tph += $sum_bt_tph;
                    $jalan += $sum_bt_jalan;
                    $bin += $sum_bt_bin;
                    $karung += $sum_jum_karung;
                    $buah += $sum_buah_tinggal;
                    $restan += $sum_restan_unreported;
                }

                $total_brondolan =  $tph + $jalan + $bin + $karung;
                $total_janjang =  $buah + $restan;

                $newSidak[$key][$key1]['tot_brd'] = $total_brondolan;
                $newSidak[$key][$key1]['tod_buah'] = $total_janjang;
            }
        }

        // dd($newSidak);


        $graphbrd = [];

        foreach ($newSidak as $key => $value) {
            foreach ($value as $key1 => $value1) {

                $graphbrd[] = $value1['tot_brd'];
            }
        }
        $graphbuah = [];

        foreach ($newSidak as $key => $value) {
            foreach ($value as $key1 => $value1) {

                $graphbuah[] = $value1['tod_buah'];
            }
        }


        // dd($newSidak, $graphbrd);
        // dd($result);
        $arrView = array();
        $arrView['brdgraph'] = $graphbrd;
        $arrView['graphbuah'] = $graphbuah;
        $arrView['ktg'] = $montharr;

        echo json_encode($arrView);
        exit();
    }

    public function notfound()
    {

        return view('404');
    }

    public function detailSidakTph($est, $afd, $start, $last)
    {
        $query = DB::connection('mysql2')->Table('sidak_tph')
            ->select('sidak_tph.*', 'estate.wil') //buat mengambil data di estate db dan willayah db
            ->join('estate', 'estate.est', '=', 'sidak_tph.est') //kemudian di join untuk mengambil est perwilayah
            ->where('sidak_tph.est', $est)
            ->where('sidak_tph.afd', $afd)
            ->whereBetween('sidak_tph.datetime', [$start, $last])
            ->get();

        $query = $query->groupBy(function ($item) {
            return $item->blok;
        });

        // dd($query);
        $datas = array();
        $img = array();
        foreach ($query as $key => $value) {
            $inc = 0;
            foreach ($value as $key2 => $value2) {
                $datas[] = $value2;
                if (!empty($value2->foto_temuan)) {
                    $img[$key][$inc]['foto'] = $value2->foto_temuan;
                    $img[$key][$inc]['title'] = $value2->est . ' ' .  $value2->afd . ' - ' . $value2->blok;
                    $inc++;
                }
            }
        }

        $imgNew = array();
        foreach ($img as $key => $value) {
            foreach ($value as $key2 => $value2) {
                $imgNew[] = $value2;
            }
        }
        // dd($img);

        $queryBlok = DB::connection('mysql2')
            ->Table('sidak_tph')
            ->select('sidak_tph.*', 'estate.wil') //buat mengambil data di estate db dan willayah db
            ->join('estate', 'estate.est', '=', 'sidak_tph.est') //kemudian di join untuk mengambil est perwilayah
            ->where('sidak_tph.est', $est)
            ->where('sidak_tph.afd', $afd)
            ->whereBetween('sidak_tph.datetime', [$start, $last])
            ->groupBy('sidak_tph.blok')
            ->orderBy('sidak_tph.blok', 'asc')
            ->get()->toArray();

        return view('detailSidakTPH', ['est' => $est, 'afd' => $afd, 'start' => $start, 'last' => $last, 'data' => $datas, 'img' => $imgNew, 'blok' => $queryBlok]);
    }


    public function getPlotLine(Request $request)
    {
        $afd = $request->get('afd');
        $est = $request->get('est');
        $start = $request->get('start');
        $last = $request->get('last');

        $query = DB::connection('mysql2')->Table('sidak_tph')
            ->select('sidak_tph.*', 'estate.wil') //buat mengambil data di estate db dan willayah db
            ->join('estate', 'estate.est', '=', 'sidak_tph.est') //kemudian di join untuk mengambil est perwilayah
            ->where('sidak_tph.est', $est)
            ->where('sidak_tph.afd', $afd)
            ->whereBetween('sidak_tph.datetime', [$start, $last])
            ->get();

        $query = $query->groupBy(function ($item) {
            return $item->blok;
        });

        $datas = array();
        $img = array();
        foreach ($query as $key => $value) {
            foreach ($value as $key2 => $value2) {
                $datas[] = $value2;
                if (!empty($value2->foto_temuan)) {
                    $img[] = $value2->foto_temuan;
                }
            }
        }

        $plotTitik = array();
        $plotMarker = array();
        $inc = 0;

        foreach ($datas as $key => $value) {
            if (!empty($value->lat)) {
                $plotTitik[] = '[' . $value->lon . ',' . $value->lat . ']';
                $plotMarker[$inc]['latln'] = '[' . $value->lat . ',' . $value->lon . ']';
                $plotMarker[$inc]['notph'] = $value->no_tph;
                $plotMarker[$inc]['blok'] = $value->blok;
                $plotMarker[$inc]['brondol_tinggal'] = $value->bt_tph + $value->bt_jalan + $value->bt_bin;
                $plotMarker[$inc]['jum_karung'] = $value->jum_karung;
                $plotMarker[$inc]['buah_tinggal'] = $value->buah_tinggal;
                $plotMarker[$inc]['restan_unreported'] = $value->restan_unreported;
                $plotMarker[$inc]['datetime'] = $value->datetime;

                $fotoTemuan = explode('; ', $value->foto_temuan);
                $komentar = explode('; ', $value->komentar);

                // If the number of items is the same for both arrays
                if (count($fotoTemuan) == count($komentar)) {
                    for ($i = 0; $i < count($fotoTemuan); $i++) {
                        $plotMarker[$inc]['foto_temuan' . ($i + 1)] = $fotoTemuan[$i];
                        $plotMarker[$inc]['komentar' . ($i + 1)] = $komentar[$i];
                        $plotMarker[$inc]['jam'] = Carbon::parse($value->datetime)->format('H:i');
                    }
                } else {
                    // Handle the case where the number of items is different
                    // This assumes that the number of items in `foto_temuan` and `komentar` will always match
                    // If they don't match, you'll need to handle it accordingly
                    // For example, you can ignore the extra items or take specific action
                    // In this code, it simply uses the first item of each array and ignores the rest

                    $plotMarker[$inc]['foto_temuan'] = $fotoTemuan[0];
                    $plotMarker[$inc]['komentar'] = $komentar[0];
                    $plotMarker[$inc]['jam'] = Carbon::parse($value->datetime)->format('H:i');
                }

                $inc++;
            }
        }

        // dd($datas);

        $list_blok = array();
        foreach ($datas as $key => $value) {
            $list_blok[$est][] = $value->blok;
        }

        $blokPerEstate = array();
        $estateQuery = DB::connection('mysql2')->Table('estate')
            ->join('afdeling', 'afdeling.estate', 'estate.id')
            ->where('est', $est)->get();

        $listIdAfd = array();
        // dd($estateQuery);

        foreach ($estateQuery as $key => $value) {

            $blokPerEstate[$est][$value->nama] =  DB::connection('mysql2')->Table('blok')
                // ->join('blok', 'blok.afdeling', 'afdeling.id')
                // ->where('afdeling.estate', $value->id)->get();
                ->where('afdeling', $value->id)->pluck('nama', 'id');
            $listIdAfd[] = $value->id;
        }

        // dd($blokPerEstate);


        $result_list_blok = array();
        foreach ($list_blok as $key => $value) {
            foreach ($value as $key2 => $data) {
                if (strlen($data) == 5) {
                    $result_list_blok[$key][$data] = substr($data, 0, -2);
                } else if (strlen($data) == 6) {
                    $sliced = substr_replace($data, '', 1, 1);
                    $result_list_blok[$key][$data] = substr($sliced, 0, -2);
                } else if (strlen($data) == 3) {
                    $result_list_blok[$key][$data] = $data;
                } else if (strpos($data, 'CBI') !== false) {
                    $result_list_blok[$key][$data] = substr($data, 0, -4);
                } else if (strpos($data, 'CB') !== false) {
                    $sliced = substr_replace($data, '', 1, 1);
                    $result_list_blok[$key][$data] = substr($sliced, 0, -3);
                }
            }
        }

        $result_list_all_blok = array();
        foreach ($blokPerEstate as $key2 => $value) {
            foreach ($value as $key3 => $afd) {
                foreach ($afd as $key4 => $data) {
                    if (strlen($data) == 4) {
                        $result_list_all_blok[$key2][] = substr_replace($data, '', 1, 1);
                    }
                }
            }
        }

        // //bandingkan list blok query dan list all blok dan get hanya blok yang cocok
        $result_blok = array();
        if (array_key_exists($est, $result_list_all_blok)) {
            $query = array_unique($result_list_all_blok[$est]);
            $result_blok[$est] = array_intersect($result_list_blok[$est], $query);
        }
        // dd($result_list_blok, $result_blok, $listIdAfd);


        //get lat lang dan key $result_blok atau semua list_blok

        $blokLatLn = array();

        foreach ($result_list_blok as $key => $value) {
            $inc = 0;
            foreach ($value as $key2 => $data) {
                $newData = substr_replace($data, '0', 1, 0);
                $query = '';
                $query = DB::connection('mysql2')->table('blok')
                    ->select('blok.*')
                    // ->where('blok.nama', $newData)
                    // ->orWhere('blok.nama', $data)
                    ->whereIn('blok.afdeling', $listIdAfd)
                    ->get();

                // dd($newData, $data);

                $latln = '';
                foreach ($query as $key3 => $val) {
                    if ($val->nama == $newData || $val->nama == $data) {
                        $latln .= '[' . $val->lon . ',' . $val->lat . '],';
                    }
                }

                $estate = DB::connection('mysql2')->table('estate')
                    ->select('estate.*')
                    ->where('estate.est', $est)
                    ->first();

                $nama_estate = $estate->nama;

                $blokLatLn[$inc]['blok'] = $key2;
                $blokLatLn[$inc]['estate'] = $nama_estate;
                $blokLatLn[$inc]['latln'] = rtrim($latln, ',');
                $inc++;
            }
        }

        // dd($plotMarker);
        $plot['plot'] = $plotTitik;
        $plot['marker'] = $plotMarker;
        $plot['blok'] = $blokLatLn;
        // dd($plot);
        echo json_encode($plot);
    }

    public function hapusDetailSidak(Request $request)
    {
        $ids = $request->input('ids');
        $start = $request->input('start');
        $last = $request->input('last');
        $est = $request->input('est');
        $afd = $request->input('afd');

        if (is_array($ids)) {
            // Delete each item with the corresponding id
            foreach ($ids as $id) {
                DB::connection('mysql2')->table('sidak_tph')
                    ->where('id', $id)
                    ->delete();
            }
        } else {
            // If only one id is present, delete the item with that id
            DB::connection('mysql2')->table('sidak_tph')
                ->where('id', $ids)
                ->delete();
        }

        session()->flash('status', 'Data Sidak berhasil dihapus!');
        return redirect()->route('detailSidakTph', ['est' => $est, 'afd' => $afd, 'start' => $start, 'last' => $last]);
    }


    public function BasidakTph($est, $start, $last, $regional)
    {


        $query = DB::connection('mysql2')
            ->table('sidak_tph')
            ->select('sidak_tph.*', 'estate.wil')
            ->join('estate', 'estate.est', '=', 'sidak_tph.est')
            ->where('sidak_tph.est', $est)
            ->where(function ($query) use ($start, $last) {
                $query->where('sidak_tph.datetime', '>=', $start)
                    ->where('sidak_tph.datetime', '<=', $last . ' 23:59:59');
            })
            ->get();


        $query = $query->groupBy(['afd']);

        $query = json_decode($query, true);
        // dd($query);
        // Extract unique dates from the array
        $unique_dates = [];
        foreach ($query as $key => $subArray) {
            foreach ($subArray as $item) {
                $date = explode(" ", $item['datetime'])[0]; // Extract only the date part
                if (!in_array($date, $unique_dates)) {
                    $unique_dates[] = $date;
                }
            }
        }

        // Sort unique dates
        sort($unique_dates);

        // dd($unique_dates);

        // dd($unique_dates, $start, $last, $est);
        // Generate the HTML select element with options
        $query2 = DB::connection('mysql2')->Table('sidak_tph')
            ->select('sidak_tph.*', 'estate.wil') //buat mengambil data di estate db dan willayah db
            ->join('estate', 'estate.est', '=', 'sidak_tph.est') //kemudian di join untuk mengambil est perwilayah
            ->where('sidak_tph.est', $est)
            // ->where('sidak_tph.afd', $afd)
            // ->where('sidak_tph.datetime', 'like', '%' . $tanggal . '%')
            ->whereBetween('sidak_tph.datetime', [$start, $last])
            ->get();

        $query2 = $query2->groupBy(function ($item) {
            return $item->blok;
        });

        // dd($query2);
        $datas = array();
        $img = array();
        foreach ($query2 as $key => $value) {
            $inc = 0;
            foreach ($value as $key2 => $value2) {
                $datas[] = $value2;
                if (!empty($value2->foto_temuan)) {
                    $img[$key][$inc]['foto'] = $value2->foto_temuan;
                    $img[$key][$inc]['title'] = $value2->est . ' ' .  $value2->afd . ' - ' . $value2->blok;
                    $inc++;
                }
            }
        }

        $imgNew = array();
        foreach ($img as $key => $value) {
            foreach ($value as $key2 => $value2) {
                $imgNew[] = $value2;
            }
        }
        // dd($img);

        $queryBlok = DB::connection('mysql2')
            ->Table('sidak_tph')
            ->select('sidak_tph.*', 'estate.wil') //buat mengambil data di estate db dan willayah db
            ->join('estate', 'estate.est', '=', 'sidak_tph.est') //kemudian di join untuk mengambil est perwilayah
            ->where('sidak_tph.est', $est)
            // ->where('sidak_tph.afd', $afd)
            // ->where('sidak_tph.datetime', 'like', '%' . $tanggal . '%')
            ->whereBetween('sidak_tph.datetime', [$start, $last])
            ->groupBy('sidak_tph.blok')
            ->orderBy('sidak_tph.blok', 'asc')
            ->get()->toArray();

        $edit_permittion = check_edit_permittion($est);

        $arrView = array();
        $afd = '-';

        $arrView['est'] =  $est;
        $arrView['tanggal'] =  $start;
        $arrView['regional'] =  $regional;
        $arrView['afd'] =  $afd;
        $arrView['filter'] =  $unique_dates;
        $arrView['edit_permittion'] = $edit_permittion;
        $arrView['jabatan'] = auth()->user()->Jabatan->nama ?? auth()->user()->jabatan;
        $arrView['user_name'] = auth()->user()->nama_lengkap;
        // $formattedStartDate = $startDate->format('d-m-Y');
        // $formattedEndDate = $endDate->format('d-m-Y');
        json_encode($arrView);

        // return view('BaSidakTPH', $arrView);

        return view('sidaktph.BaSidakTPH', ['est' => $est, 'afd' => $afd, 'data' => $datas, 'img' => $imgNew, 'blok' => $queryBlok], $arrView);
    }


    public function filtersidaktphrekap(Request $request)
    {
        $dates = $request->input('tanggal');
        // $Reg = $request->input('est');
        $estate = $request->input('estate');
        $afd = $request->input('afd');


        // dd($estate, $afd);

        $perPage = 10;

        $sidak_tph = DB::connection('mysql2')->table('sidak_tph')
            ->select("sidak_tph.*", DB::raw('DATE_FORMAT(sidak_tph.datetime, "%M") as bulan'), DB::raw('DATE_FORMAT(sidak_tph.datetime, "%Y") as tahun'))
            ->where('datetime', 'like', '%' . $dates . '%')
            ->where('sidak_tph.est', $estate)
            // ->where('sidak_tph.afd', $afd)

            ->orderBy('blok', 'asc')
            ->paginate($perPage, ['*'], 'page');


        $sidak_tph2 = DB::connection('mysql2')->table('sidak_tph')
            ->select("sidak_tph.*", DB::raw('DATE_FORMAT(sidak_tph.datetime, "%M") as bulan'), DB::raw('DATE_FORMAT(sidak_tph.datetime, "%Y") as tahun'))
            ->where('datetime', 'like', '%' . $dates . '%')
            ->where('sidak_tph.est', $estate)
            ->get();
        $sidak_tph2 = json_decode($sidak_tph2, true);

        $arrView = array();
        $arrView['sidak_tph'] =  $sidak_tph;
        $arrView['sidak_tph2'] =  $sidak_tph2;
        $arrView['tanggal'] =  $dates;

        // dd($sidak_tph);
        echo json_encode($arrView);
        exit();
    }


    public function updateBASidakTPH(Request $request)
    {

        $est = $request->input('Estate');
        $afd = $request->input('Afdeling');
        $qc = $request->input('QC');

        // dd($date, $afd, $est);
        // mutu ancak 
        $notph = $request->input('no_tph');
        $id = $request->input('id');
        $bttph = $request->input('bttph');
        $btjalan = $request->input('btjalan');
        $btbin = $request->input('btbin');
        $jumkrng = $request->input('jumkrng');
        $buahtgl = $request->input('buahtgl');
        $restanunr = $request->input('restanunr');
        $tphsemak = $request->input('tphsemak');


        // dd($id, $qc);

        try {
            DB::connection('mysql2')->table('sidak_tph')->where('id', $id)->update([
                'no_tph' => $notph,
                'est' => $est,
                'afd' => $afd,
                'qc' => $qc,

                'bt_tph' => $bttph,
                'bt_jalan' => $btjalan,
                'bt_bin' => $btbin,
                'jum_karung' => $jumkrng,
                'buah_tinggal' => $buahtgl,
                'restan_unreported' => $restanunr,
                'tph_semak' => $tphsemak,

            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function deleteBAsidakTPH(Request $request)
    {

        $idBuah = $request->input('id');

        try {
            DB::connection('mysql2')->table('sidak_tph')
                ->where('id', $idBuah)
                ->delete();

            return response()->json(['status' => 'success']);
        } catch (\Throwable $th) {
            throw $th;
        }
        // dd($idBuah);
    }


    public function pdfBAsidak(Request $request)
    {
        $est = $request->input('est');

        // $afd = $request->input('afdling');
        // $awal = $request->input('inputDates');

        $tanggal = $request->get('inputDates');

        $status = DB::connection('mysql2')->table('verification')
            ->where('est', $est)
            ->where('afd', '-')
            ->where('menu', 'sidaktph')
            ->where('datetime', 'LIKE', '%' . $tanggal . '%')
            ->get();
        // dd($est, $tanggal);
        if ($status->isEmpty()) {
            // $statusdata = 'not_approved';
            $statusdata = [
                'status' =>  'not_approved',
            ];
        } else {
            $statusdata = [
                'status' =>  'have_data',
                'nama_maneger' => $status[0]->nama_maneger,
                'detail_manager' => $status[0]->detail_manager,
                'approve_maneger' => $status[0]->approve_maneger,
                'lok_manager' => $status[0]->lok_manager,

                'nama_askep' => $status[0]->nama_askep,
                'detail_askep' => $status[0]->detail_askep,
                'approve_askep' => $status[0]->approve_askep,
                'lok_askep' => $status[0]->lok_askep,

                'nama_asisten' => $status[0]->nama_asisten,
                'detail_asisten' => $status[0]->detail_asisten,
                'approve_asisten' => $status[0]->approve_asisten,
                'lok_asisten' => $status[0]->lok_asisten,
            ];
        }

        // dd($status);
        $newparamsdate = '2024-03-01';

        $tanggalDateTime = new DateTime($tanggal);
        // dd($tanggalDateTime);
        $newparamsdateDateTime = new DateTime($newparamsdate);
        // dd($newparamsdateDateTime);

        if ($tanggalDateTime >= $newparamsdateDateTime) {
            $dataparams = 'new';
        } else {
            $dataparams = 'old';
        }

        // $regional = $request->get('regional');
        $ancakFA = DB::connection('mysql2')
            ->table('sidak_tph')
            ->select(
                "sidak_tph.*",
                DB::raw('DATE_FORMAT(sidak_tph.datetime, "%Y-%m-%d") as tanggal'),
                DB::raw('DATE_FORMAT(sidak_tph.datetime, "%M") as bulan'),
                DB::raw("
            CASE 
                WHEN status = '' THEN 1
                WHEN status = '0' THEN 1
                WHEN LOCATE('>H+', status) > 0 THEN '8'
                WHEN LOCATE('H+', status) > 0 THEN 
                    CASE 
                        WHEN SUBSTRING_INDEX(SUBSTRING_INDEX(status, 'H+', -1), ' ', 1) > 8 THEN '8'
                        ELSE SUBSTRING_INDEX(SUBSTRING_INDEX(status, 'H+', -1), ' ', 1)
                    END
                WHEN status REGEXP '^[0-9]+$' AND status > 8 THEN '8'
                WHEN LENGTH(status) > 1 AND status NOT LIKE '%H+%' AND status NOT LIKE '%>H+%' AND LOCATE(',', status) > 0 THEN SUBSTRING_INDEX(status, ',', 1)
                ELSE status
            END AS statuspanen")
            )
            ->where('sidak_tph.est', $est)
            ->where('sidak_tph.datetime', 'like', '%' . $tanggal . '%')
            ->orderBy('afd', 'asc')
            ->orderBy('status', 'asc')
            ->get();

        $ancakFA = $ancakFA->groupBy(['est', 'afd', 'statuspanen', 'tanggal', 'blok']);
        $ancakFA = json_decode($ancakFA, true);
        $buahpetugas1 = [];
        foreach ($ancakFA as $key => $value) {

            foreach ($value as $key1 => $value1) {
                foreach ($value1 as $key2 => $value2) {
                    foreach ($value2 as $key3 => $value3) {
                        foreach ($value3 as $key4 => $value4) {
                            foreach ($value4 as $key5 => $value5) {
                                $buahpetugas1[] = $value5['qc'];
                            }
                        }
                    }
                }
            }
        }
        // dd($buahpetugas1);
        $petugas = array_unique($buahpetugas1);
        $petugasnama = array_values($petugas);

        // dd($petugasnama, $petugas);
        $dateString = $tanggal;
        $dateParts = date_parse($dateString);
        $year = $dateParts['year'];
        $month = $dateParts['month'];

        $year = $year; // Replace with the desired year
        $month = $month;   // Replace with the desired month (September in this example)

        $weeks = [];
        $firstDayOfMonth = strtotime("$year-$month-01");
        $lastDayOfMonth = strtotime(date('Y-m-t', $firstDayOfMonth));

        $weekNumber = 1;
        $startDate = $firstDayOfMonth;

        while ($startDate <= $lastDayOfMonth) {
            $endDate = strtotime("next Sunday", $startDate);
            if ($endDate > $lastDayOfMonth) {
                $endDate = $lastDayOfMonth;
            }

            $weeks[$weekNumber] = [
                'start' => date('Y-m-d', $startDate),
                'end' => date('Y-m-d', $endDate),
            ];

            $nextMonday = strtotime("next Monday", $endDate);

            // Check if the next Monday is still within the current month.
            if (date('m', $nextMonday) == $month) {
                $startDate = $nextMonday;
            } else {
                // If the next Monday is in the next month, break the loop.
                break;
            }

            $weekNumber++;
        }


        // dd($weeks);
        $result = [];

        // Iterate through the original array
        foreach ($ancakFA as $mainKey => $mainValue) {
            $result[$mainKey] = [];

            foreach ($mainValue as $subKey => $subValue) {
                $result[$mainKey][$subKey] = [];

                foreach ($subValue as $dateKey => $dateValue) {
                    // Remove 'H+' prefix if it exists
                    $numericIndex = is_numeric($dateKey) ? $dateKey : (strpos($dateKey, 'H+') === 0 ? substr($dateKey, 2) : $dateKey);

                    if (!isset($result[$mainKey][$subKey][$numericIndex])) {
                        $result[$mainKey][$subKey][$numericIndex] = [];
                    }

                    foreach ($dateValue as $statusKey => $statusValue) {
                        // Handle 'H+' prefix in status
                        $statusIndex = is_numeric($statusKey) ? $statusKey : (strpos($statusKey, 'H+') === 0 ? substr($statusKey, 2) : $statusKey);

                        if (!isset($result[$mainKey][$subKey][$numericIndex][$statusIndex])) {
                            $result[$mainKey][$subKey][$numericIndex][$statusIndex] = [];
                        }

                        foreach ($statusValue as $blokKey => $blokValue) {
                            $result[$mainKey][$subKey][$numericIndex][$statusIndex][$blokKey] = $blokValue;
                        }
                    }
                }
            }
        }

        // result by statis week 
        $newResult = [];

        foreach ($result as $key => $value) {
            $newResult[$key] = [];

            foreach ($value as $estKey => $est) {
                $newResult[$key][$estKey] = [];

                foreach ($est as $statusKey => $status) {
                    $newResult[$key][$estKey][$statusKey] = [];

                    foreach ($weeks as $weekKey => $week) {
                        $newStatus = [];

                        foreach ($status as $date => $data) {
                            if (strtotime($date) >= strtotime($week["start"]) && strtotime($date) <= strtotime($week["end"])) {
                                $newStatus[$date] = $data;
                            }
                        }

                        if (!empty($newStatus)) {
                            $newResult[$key][$estKey][$statusKey]["week" . ($weekKey + 1)] = $newStatus;
                        }
                    }
                }
            }
        }

        // dd($newResult);

        // result by week status 
        $WeekStatus = [];

        foreach ($result as $key => $value) {
            $WeekStatus[$key] = [];

            foreach ($value as $estKey => $est) {
                $WeekStatus[$key][$estKey] = [];

                foreach ($weeks as $weekKey => $week) {
                    $WeekStatus[$key][$estKey]["week" . ($weekKey + 1)] = []; // Note: Use "week" . ($weekKey + 1) instead of "week" . ($weekKey + 0)

                    foreach ($est as $statusKey => $status) {
                        $newStatus = [];

                        foreach ($status as $date => $data) {
                            if (strtotime($date) >= strtotime($week["start"]) && strtotime($date) <= strtotime($week["end"])) {
                                $newStatus[$date] = $data;
                            }
                        }

                        if (!empty($newStatus)) {
                            $WeekStatus[$key][$estKey]["week" . ($weekKey + 1)][$statusKey] = $newStatus;
                        }
                    }

                    // Remove the week if it's empty
                    if (empty($WeekStatus[$key][$estKey]["week" . ($weekKey + 1)])) {
                        unset($WeekStatus[$key][$estKey]["week" . ($weekKey + 1)]);
                    }
                }
            }
        }

        // dd($WeekStatus);



        // dd($WeekStatus);

        $newDefaultWeek = [];

        foreach ($WeekStatus as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $key1 => $value1) {
                    if (is_array($value1)) {
                        foreach ($value1 as $subKey => $subValue) {
                            if (is_array($subValue)) {
                                // Check if both key 0 and key 1 exist
                                $hasKeyZero = isset($subValue[0]);
                                $hasKeyOne = isset($subValue[1]);

                                // Merge key 0 into key 1
                                if ($hasKeyZero && $hasKeyOne) {
                                    $subValue[1] = array_merge_recursive((array)$subValue[1], (array)$subValue[0]);
                                    unset($subValue[0]);
                                } elseif ($hasKeyZero && !$hasKeyOne) {
                                    // Create key 1 and merge key 0 into it
                                    $subValue[1] = $subValue[0];
                                    unset($subValue[0]);
                                }

                                // Check if keys 1 through 7 don't exist, add them with a default value of 0
                                for ($i = 1; $i <= 7; $i++) {
                                    if (!isset($subValue[$i])) {
                                        $subValue[$i] = 0;
                                    }
                                }

                                // Ensure key 8 exists, and if not, create it with a default value of an empty array
                                if (!isset($subValue[8])) {
                                    $subValue[8] = 0;
                                }

                                // Check if keys higher than 8 exist, merge them into index 8
                                for ($i = 9; $i <= 100; $i++) {
                                    if (isset($subValue[$i])) {
                                        $subValue[8] = array_merge_recursive((array)$subValue[8], (array)$subValue[$i]);
                                        unset($subValue[$i]);
                                    }
                                }
                            }
                            $newDefaultWeek[$key][$key1][$subKey] = $subValue;
                        }
                    }
                }
            }
        }
        // dd($newDefaultWeek['Plasma1']['WIL-III']);
        // dd($newDefaultWeek);

        function removeZeroFromDatetime3(&$array)
        {
            foreach ($array as $key => &$value) {
                if (is_array($value)) {
                    foreach ($value as $key1 => &$value2) {
                        if (is_array($value2)) {
                            foreach ($value2 as $key2 => &$value3) {
                                if (is_array($value3)) {
                                    foreach ($value3 as $key3 => &$value4) if (is_array($value4)) {
                                        foreach ($value4 as $key4 => $value5) {
                                            if ($key4 === 0 && $value5 === 0) {
                                                unset($value4[$key4]); // Unset the key 0 => 0 within the current nested array
                                            }
                                            removeZeroFromDatetime3($value4);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        removeZeroFromDatetime3($newDefaultWeek);
        // dd($newDefaultWeek);
        $calculation = [];

        foreach ($newDefaultWeek as $key => $value) {
            foreach ($value as $key1 => $value1) {
                if (is_array($value1)) {
                    $totalKeys = [];

                    foreach ($value1 as $key2 => $value2) {
                        if (is_array($value2)) {
                            foreach ($value2 as $key3 => $value3) {
                                if (is_array($value3)) {
                                    foreach ($value3 as $key4 => $value4) {
                                        if (is_array($value4)) {
                                            $totalKeys = array_merge($totalKeys, array_keys($value4));

                                            // dd($value4);
                                        }
                                    }
                                }
                            }
                        }
                    }

                    $uniqueArray = array_unique($totalKeys);

                    // Extract the first 4 characters from each key and remove '0'
                    $shortenedKeys = array_map(function ($key) {
                        $shortenedKey = substr($key, 0, 4);
                        return str_replace('0', '', $shortenedKey);
                    }, $uniqueArray);

                    // dd($totalKeys);

                    // Concatenate the modified keys with a comma separator
                    $calculation[$key][$key1] = implode('-', $shortenedKeys);
                }
            }
        }

        $cariluas = DB::connection('mysql2')->table('sidak_tph')
            ->select('*')
            ->where('sidak_tph.est', $request->input('est'))
            ->where('sidak_tph.datetime', 'like', '%' . $request->input('inputDates') . '%')
            ->orderBy('afd', 'asc')
            ->orderBy('status', 'asc')
            ->get();


        $cariluas = $cariluas->groupBy(['est', 'afd', 'blok']);
        $cariluas = json_decode($cariluas, true);

        // dd($request->input('Estate'), $est);
        // dd($cariluas);

        foreach ($cariluas as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $getluas = [];
                foreach ($value1 as $key2 => $value2) {

                    foreach ($value2 as $key3 => $value3) {
                        $luas = $value3['luas'];
                    }

                    $getluas[] = $luas;
                }

                $sumluas = array_sum($getluas);

                $luastod[$key][$key1]['Luas'] = $sumluas;
                $luastod[$key][$key1]['getluas'] = $getluas;
            }
        }

        // dd($luastod);
        $hitung = [];
        // dd($newDefaultWeek);

        foreach ($newDefaultWeek as $key => $value) {
            foreach ($value as $key1 => $value1) {
                foreach ($value1 as $key2 => $value2) {
                    foreach ($value2 as $key3 => $value3) if (is_array($value3)) {
                        $brd2 = 0;
                        $janjang2 = 0;
                        $luas2 = 0;
                        foreach ($value3 as $key4 => $value4) if (is_array($value4)) {
                            $brd1 = 0;
                            $janjang1 = 0;
                            $luas1 = 0;
                            foreach ($value4 as $key5 => $value5) {
                                $bt_tph  = 0;
                                $bt_jalan = 0;
                                $bt_bin  = 0;
                                $jum_karung = 0;
                                $buah_tinggal = 0;
                                $restan_unreported = 0;
                                $brd = 0;
                                $janjang = 0;
                                $total_brondolan = 0;
                                $total_janjang = 0;
                                // dd($key3);
                                foreach ($value5 as $key6 => $value6) {
                                    $bt_tph += $value6['bt_tph'];
                                    $bt_jalan += $value6['bt_jalan'];
                                    $bt_bin += $value6['bt_bin'];
                                    $jum_karung += $value6['jum_karung'];
                                    $buah_tinggal += $value6['buah_tinggal'];
                                    $restan_unreported += $value6['restan_unreported'];
                                    // $luas = $value6['luas'];
                                }

                                $hitung[$key][$key1][$key2][$key3][$key4][$key5]['brondolan_tph'] = $bt_tph;
                                $hitung[$key][$key1][$key2][$key3][$key4][$key5]['brondolan_jalan'] = $bt_jalan;
                                $hitung[$key][$key1][$key2][$key3][$key4][$key5]['brondolan_bin'] = $bt_bin;
                                $hitung[$key][$key1][$key2][$key3][$key4][$key5]['brondolan_karung'] = $jum_karung;
                                $hitung[$key][$key1][$key2][$key3][$key4][$key5]['luas'] = $luas;
                                $hitung[$key][$key1][$key2][$key3][$key4][$key5]['janjnang_tinggal'] = $buah_tinggal;
                                $hitung[$key][$key1][$key2][$key3][$key4][$key5]['janjang_unreported'] = $restan_unreported;


                                $brd = $bt_bin + $bt_jalan + $bt_tph + $jum_karung;
                                $janjang = $restan_unreported + $buah_tinggal;
                                $brd1 += $brd;
                                $janjang1 += $janjang;
                                // $luas1 += $luas;
                            }

                            $hitung[$key][$key1][$key2][$key3][$key4]['tot_janjnag'] = $janjang1;
                            $hitung[$key][$key1][$key2][$key3][$key4]['tod_brd'] = $brd1;
                            // $hitung[$key][$key1][$key2][$key3][$key4]['tod_luas'] = $luas1;


                            $janjang2 += $janjang1;
                            $brd2 += $brd1;
                            // $luas2 += $luas1;
                        }


                        $status_panen = $key3;
                        if ($dataparams === 'new') {
                            [$panen_brd, $panen_jjg] = calculatePanennew($status_panen);
                        } else {
                            [$panen_brd, $panen_jjg] = calculatePanen($status_panen);
                        }

                        $total_brondolan =  round(($brd2) * $panen_brd / 100, 1);
                        $total_janjang =  round(($janjang2) * $panen_jjg / 100, 1);
                        $hitung[$key][$key1][$key2][$key3]['tot_janjnag'] = $janjang2;
                        $hitung[$key][$key1][$key2][$key3]['tod_brd'] = $brd2;

                        $hitung[$key][$key1][$key2][$key3]['skor_brd'] = $total_brondolan;
                        $hitung[$key][$key1][$key2][$key3]['skor_jjg'] = $total_janjang;
                        $hitung[$key][$key1][$key2][$key3]['avg'] = 1;
                    } else {
                        $hitung[$key][$key1][$key2][$key3]['tot_janjnag'] = 0;
                        $hitung[$key][$key1][$key2][$key3]['tod_brd'] = 0;
                        // $hitung[$key][$key1][$key2][$key3]['tod_luas'] = 0;
                        $hitung[$key][$key1][$key2][$key3]['skor_brd'] = 0;
                        $hitung[$key][$key1][$key2][$key3]['skor_jjg'] = 0;
                        $hitung[$key][$key1][$key2][$key3]['avg'] = 0;
                    }
                }
            }
        }
        // dd($newDefaultWeek, $hitung);
        $final = [];
        foreach ($hitung as $key => $value) {

            foreach ($value as $key1 => $value1) {

                foreach ($value1 as $key2 => $value2) {
                    $tot_luas = 0;
                    $tot_janjnag = 0;
                    $tod_brd = 0;
                    $avg = 0;
                    $skorakhir = 0;
                    $totskor = 0;
                    foreach ($value2 as $key3 => $value3) {
                        foreach ($calculation as $keyx => $value4) if ($key == $keyx) {
                            foreach ($value4 as $keyx1 => $value5) if ($key1 == $keyx1) {
                                // dd($calculation);
                                $blok = $value5;
                            } # code...
                        }

                        foreach ($luastod as $keyx => $valuex1) if ($keyx == $key) {
                            foreach ($valuex1 as $keyx1 => $valuex1) if ($keyx1 == $key1) {
                                $luasbw = $valuex1['Luas'];
                            }
                        }

                        $weekestate = [
                            'est' => $key,
                            'afd' => $key1,
                            'status' => $key3,
                            'janjang' => $value3['tot_janjnag'],
                            'brd' => $value3['tod_brd'],
                            'luas' => $luasbw,
                            'skor_brd' => $value3['skor_brd'],
                            'skor_luas' => $value3['skor_jjg'],
                        ];

                        $final[$key][$key1][$key3] = $weekestate;

                        // $tot_luas += $value3['tod_luas'];
                        $tot_janjnag += $value3['skor_jjg'];
                        $tod_brd += $value3['skor_brd'];
                        $avg += $value3['avg'];
                    } # code...

                    $skorakhir = 100 - ($tot_janjnag + $tod_brd);

                    if ($skorakhir <= 0) {
                        $newskor = 0;
                    } else {
                        $newskor = $skorakhir;
                    }

                    $totskor = $tot_janjnag + $tod_brd;
                    $final[$key][$key1]['blok'] = $blok;
                    $final[$key][$key1]['luas'] = $luasbw;
                    $final[$key][$key1]['total_skor'] = $totskor;
                    $final[$key][$key1]['skor_akhir'] = $newskor;
                } # code...
                $estakhir[] = $newskor;
                $esttod[] = $totskor;
            }  # code...
            $div = count($estakhir);
            $sum = array_sum($estakhir);
            $divtod = count($esttod);
            $sumtod = array_sum($esttod);

            $final[$key][''] = [
                'blok' => '-',
                'luas' => '-',
                'total_skor' => 'EST',
                'skor_akhir' => round($sum / $div, 2)
            ];
        }
        // dd($estakhir);
        // Now $keysCollection contains the keys as you described, including the date values.

        // dd($final);




        // dd($newDefaultWeek['Plasma1']['WIL-III']);
        $newSidak = array();
        $asisten_qc = DB::connection('mysql2')
            ->Table('asisten_qc')
            ->get();
        $asisten_qc = json_decode($asisten_qc, true);

        // dd($pdf, $total_pdf);

        $arrView = array();
        $arrView['hitung'] =  $final;
        $arrView['total_hitung'] =  '-';

        $arrView['est'] =  $request->input('est');
        $arrView['afd'] =  '-';
        $arrView['awal'] =  $tanggal;
        $arrView['statusdata'] =  $statusdata;
        $arrView['finalpetugas'] =  $petugasnama;



        $pdf = PDF::loadView('sidaktph.Pdfsidaktphba', ['data' => $arrView]);

        $customPaper = array(360, 360, 360, 360);
        $pdf->set_paper('A2', 'landscape');
        // $pdf->set_paper('A2', 'potrait');

        $filename = 'BA Sidak TPH -' . $arrView['awal'] . '-' . $arrView['est']  . '.pdf';
        // $filename = 'BA Sidak TPH -' . '.pdf';

        return $pdf->stream($filename);
    }


    public function changeRegionEst(Request $request)
    {
        $reg = $request->get('region');

        // Split the string into an array of numbers
        $queryReg2 = DB::connection('mysql2')
            ->table('wil')
            ->whereIn('regional', [$reg])
            ->pluck('id')
            ->toArray();

        $EstMapVal = DB::connection('mysql2')->table('estate')
            ->where('estate.emp', '!=', 1)
            ->whereIn('wil', $queryReg2)->pluck('est')->toArray();

        // Return the estates as JSON data
        return response()->json([
            'estates' => $EstMapVal
        ]);
    }


    public function getMapsTph(Request $request)
    {
        $afd = $request->get('afd');
        $est = $request->get('est');
        $date = $request->get('date');
        $afd2 = $request->get('afd');

        // dd($afd, $est, $date);

        $query = DB::connection('mysql2')->Table('sidak_tph')
            ->select('sidak_tph.*', 'estate.wil') //buat mengambil data di estate db dan willayah db
            ->join('estate', 'estate.est', '=', 'sidak_tph.est') //kemudian di join untuk mengambil est perwilayah
            ->where('sidak_tph.est', $est)
            // ->where('sidak_tph.afd', $afd)
            ->where('datetime', 'like', '%' . $date . '%')
            // ->where('sidak_tph.datetime', $date)
            ->get();

        $query = $query->groupBy(function ($item) {
            return $item->blok;
        });

        // dd($query);

        $datas = array();
        $img = array();
        foreach ($query as $key => $value) {
            foreach ($value as $key2 => $value2) {
                $datas[] = $value2;
                if (!empty($value2->foto_temuan)) {
                    $img[] = $value2->foto_temuan;
                }
            }
        }

        $plotTitik = array();
        $plotMarker = array();
        $inc = 0;
        // dd($datas);
        foreach ($datas as $key => $value) {
            if (!empty($value->lat)) {
                $plotTitik[] = '[' . $value->lon . ',' . $value->lat . ']';
                $plotMarker[$inc]['latln'] = '[' . $value->lat . ',' . $value->lon . ']';
                $plotMarker[$inc]['notph'] = $value->no_tph;
                $plotMarker[$inc]['blok'] = $value->blok;
                $plotMarker[$inc]['afd'] = $value->afd;
                $plotMarker[$inc]['brondol_tinggal'] = $value->bt_tph + $value->bt_jalan + $value->bt_bin;
                $plotMarker[$inc]['jum_karung'] = $value->jum_karung;
                $plotMarker[$inc]['buah_tinggal'] = $value->buah_tinggal;
                $plotMarker[$inc]['restan_unreported'] = $value->restan_unreported;
                $plotMarker[$inc]['datetime'] = $value->datetime;

                $fotoTemuan = explode('; ', $value->foto_temuan);
                $komentar = explode('; ', $value->komentar);

                // If the number of items is the same for both arrays
                if (count($fotoTemuan) == count($komentar)) {
                    for ($i = 0; $i < count($fotoTemuan); $i++) {
                        $plotMarker[$inc]['foto_temuan' . ($i + 1)] = $fotoTemuan[$i];
                        $plotMarker[$inc]['komentar' . ($i + 1)] = $komentar[$i];
                        $plotMarker[$inc]['jam'] = Carbon::parse($value->datetime)->format('H:i');
                    }
                } else {


                    $plotMarker[$inc]['foto_temuan'] = $fotoTemuan[0];
                    $plotMarker[$inc]['komentar'] = $komentar[0];
                    $plotMarker[$inc]['jam'] = Carbon::parse($value->datetime)->format('H:i');
                }

                $inc++;
            }
        }

        // dd($plotMarker);

        $list_blok = array();
        foreach ($datas as $key => $value) {
            $list_blok[$est][] = $value->blok;
        }

        $blokPerEstate = array();
        $estateQuery = DB::connection('mysql2')->Table('estate')
            ->join('afdeling', 'afdeling.estate', 'estate.id')
            ->where('est', $est)->get();

        $listIdAfd = array();
        // dd($estateQuery);

        foreach ($estateQuery as $key => $value) {

            $blokPerEstate[$est][$value->nama] =  DB::connection('mysql2')->Table('blok')
                // ->join('blok', 'blok.afdeling', 'afdeling.id')
                // ->where('afdeling.estate', $value->id)->get();
                ->where('afdeling', $value->id)->pluck('nama', 'id');
            $listIdAfd[] = $value->id;
        }

        // dd($blokPerEstate);





        $query2 = DB::connection('mysql2')->Table('sidak_tph')
            ->select('sidak_tph.*', 'estate.wil') //buat mengambil data di estate db dan willayah db
            ->join('estate', 'estate.est', '=', 'sidak_tph.est') //kemudian di join untuk mengambil est perwilayah
            ->where('sidak_tph.est', $est)
            // ->where('sidak_tph.afd', $afd2)
            ->where('datetime', 'like', '%' . $date . '%')
            ->get();

        $query2 = $query2->groupBy(function ($item) {
            return $item->blok;
        });


        $datas = array();
        $img = array();
        foreach ($query2 as $key => $value) {
            $inc = 0;
            foreach ($value as $key2 => $value2) {
                $datas[] = $value2;
                if (!empty($value2->foto_temuan)) {
                    $img[$key][$inc]['foto'] = $value2->foto_temuan;
                    $img[$key][$inc]['title'] = $value2->est . ' ' .  $value2->afd . ' - ' . $value2->blok;
                    $inc++;
                }
            }
        }

        $imgNew = array();
        foreach ($img as $key => $value) {
            foreach ($value as $key2 => $value2) {
                $imgNew[] = $value2;
            }
        }
        // dd($imgNew);

        function isPointInPolygon($point, $polygon)
        {
            $splPoint = explode(',', $point);
            $x = $splPoint[0];
            $y = $splPoint[1];

            $vertices = array_map(function ($vertex) {
                return explode(',', $vertex);
            }, explode('$', $polygon));

            $numVertices = count($vertices);
            $isInside = false;

            for ($i = 0, $j = $numVertices - 1; $i < $numVertices; $j = $i++) {
                $xi = $vertices[$i][0];
                $yi = $vertices[$i][1];
                $xj = $vertices[$j][0];
                $yj = $vertices[$j][1];

                $intersect = (($yi > $y) != ($yj > $y)) && ($x < ($xj - $xi) * ($y - $yi) / ($yj - $yi) + $xi);

                if ($intersect) {
                    $isInside = !$isInside;
                }
            }

            return $isInside;
        }

        $estateQuery = DB::connection('mysql2')->table('estate')
            ->select('*')
            ->join('afdeling', 'afdeling.estate', '=', 'estate.id')
            ->where('estate.est', $est)
            ->get();
        $estateQuery = json_decode($estateQuery, true);

        $listIdAfd = array();
        foreach ($estateQuery as $key => $value) {
            $listIdAfd[] = $value['id'];
        }

        $blokEstate = DB::connection('mysql2')->table('blok')
            ->select(DB::raw('DISTINCT nama, MIN(id) as id, afdeling'))
            ->whereIn('afdeling', $listIdAfd)
            ->groupBy('nama', 'afdeling')
            ->get();
        $blokEstate = json_decode($blokEstate, true);

        $blokEstateFix = array();
        foreach ($blokEstate as $key => $value) {
            $blokEstateFix[$value['afdeling']][] = $value['nama'];
        }

        // dd($blokEstateFix);
        $qrAfd = DB::connection('mysql2')->table('afdeling')
            ->select('*')
            ->get();
        $qrAfd = json_decode($qrAfd, true);

        $blokEstNewFix = array();
        foreach ($blokEstateFix as $key => $value) {
            foreach ($qrAfd as $key1 => $value1) {
                if ($value1['id'] == $key) {
                    $afdelingNama = $value1['nama'];
                }
            }
            $blokEstNewFix[$afdelingNama] = $value;
        }

        $queryBlok = DB::connection('mysql2')->table('blok')
            ->select('*')
            ->whereIn('afdeling', $listIdAfd)
            ->get();
        $queryBlok = json_decode($queryBlok, true);

        $blokLatLnEw = array();
        $inc = 0;
        foreach ($blokEstNewFix as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $latln = '';
                $latln2 = '';
                foreach ($queryBlok as $key3 => $value4) {
                    if ($value4['nama'] == $value1) {
                        $latln .= $value4['lat'] . ',' . $value4['lon'] . '$';
                        $latln2 .= '[' . $value4['lon'] . ',' . $value4['lat'] . '],';
                    }
                }

                $blokLatLnEw[$inc]['afd'] = $key;
                $blokLatLnEw[$inc]['blok'] = $value1;
                $blokLatLnEw[$inc]['latln'] = rtrim($latln, '$');
                $blokLatLnEw[$inc]['latinnew'] = rtrim($latln2, ',');
                $inc++;
            }
        }

        $dtQuery = DB::connection('mysql2')->table('sidak_tph')
            ->select('*', DB::raw("DATE_FORMAT(datetime, '%H:%i:%s') AS time"))
            ->where('est', $est)
            ->where('datetime', 'LiKE', '%' . $date . '%')
            ->orderBy('time', 'asc')
            ->get();
        $dtQuery = json_decode($dtQuery, true);


        // dd($dtQuery);

        $pkLatLn = array();
        $incr = 0;
        foreach ($dtQuery as $key => $value) {
            $pkLatLn[$incr]['id'] = $value['id'];
            $pkLatLn[$incr]['latln'] = $value['lat'] . ',' . $value['lon'];
            $incr++;
        }

        // dd($blokLatLnEw, $pkLatLn);
        // dd($blokLatLnEw);

        // Define an associative array to track unique combinations
        $uniqueCombinations = [];

        foreach ($blokLatLnEw as $value) {
            foreach ($pkLatLn as $marker) {
                if (isPointInPolygon($marker['latln'], $value['latln'])) {
                    // Create a unique key based on nama, estate, and latin
                    $key = $value['blok'] . '_' . $est . '_' . $value['latln'];

                    // $latln .= '[' . $val->lon . ',' . $val->lat . '],';

                    // Check if the combination already exists
                    if (!isset($uniqueCombinations[$key])) {
                        $uniqueCombinations[$key] = true; // Mark the combination as encountered
                        $messageResponse[] = [
                            'blok' => $value['blok'],
                            'estate' => $est,
                            'latln' => $value['latinnew']
                        ];
                    }
                }
            }
        }

        // dd($blokLatLnEw);
        $newArr = DB::connection('mysql2')->table('sidak_tph')
            ->select('*', DB::raw("DATE_FORMAT(datetime, '%H:%i:%s') AS time"))
            ->where('est', $est)
            ->where('datetime', 'LiKE', '%' . $date . '%')
            ->orderBy('time', 'asc')
            ->get();
        $newArr = $newArr->groupBy(['qc']);
        $newArr = json_decode($newArr, true);

        $pkLatLnnew = array();
        $incr = 0;
        foreach ($newArr as $key => $value) {
            $latln2 = '';
            foreach ($value as $value2) {
                # code...
                // dd($value2);
                $latln2 .= '[' . $value2['lon'] . ',' . $value2['lat'] . '],';
                $pkLatLnnew[$key]['qc'] = $value2['qc'];
                $pkLatLnnew[$key]['latln'] = $latln2;
            }
        }

        // dd($newArr, $pkLatLnnew);



        // dd($blokLatLn, $messageResponse);
        $plot['plot'] = $plotTitik;
        $plot['marker'] = $plotMarker;
        $plot['blok'] = $messageResponse;
        $plot['img'] = $imgNew;
        $plot['plotarrow'] = $pkLatLnnew;
        // dd($plot);
        echo json_encode($plot);
    }


    public function updatesidakTPhnew(Request $request)
    {

        // mutu buah 
        $ids = $request->input('id');
        $blok_bh = $request->input('blok_bh');
        $brdtgl = $request->input('brdtgl');
        $brdjln = $request->input('brdjln');
        $brdbin = $request->input('brdbin');
        $qc = $request->input('qc');
        $jumkrng = $request->input('jumkrng');
        $buahtgl = $request->input('buahtgl');
        $restan = $request->input('restan');
        $hplus = $request->input('hpluss');

        // dd($hplus);
        // Retrieve the data before updating
        $oldData = DB::connection('mysql2')->table('sidak_tph')->where('id', $ids)->first();

        // Perform the update
        try {
            DB::connection('mysql2')->table('sidak_tph')->where('id', $ids)->update([
                'blok' => $blok_bh,
                'bt_tph' => $brdtgl,
                'bt_jalan' => $brdjln,
                'bt_bin' => $brdbin,
                'qc' => $qc,
                'jum_karung' => $jumkrng,
                'buah_tinggal' => $buahtgl,
                'status' => $hplus,
                'restan_unreported' => $restan,
            ]);

            // Retrieve the updated data
            $updatedData = DB::connection('mysql2')->table('sidak_tph')->where('id', $ids)->first();
        } catch (\Throwable $th) {
            // Handle exceptions if needed
        } finally {
            $username = session('user_name');
            $userid = session('user_id');
            $date = Carbon::now();

            // Insert a record into the history table
            DB::connection('mysql2')->table('history_edit')->insert([
                'id_user' => $userid,
                'nama_user' => $username,
                'data_baru' => json_encode($updatedData), // Data after the update
                'data_lama' => json_encode($oldData), // Data before the update
                'tanggal' => $date,
                'menu' => 'edit_sidaktph',

            ]);

            $username = $request->session()->get('user_name');
            $dataarr = 'User:' . $username . ' ' . 'Tanggal:' . Carbon::now() . ' ' . 'Melakukan: edit_sidaktph';
            sendwhatsapp($dataarr);
        }
    }
    public function deletedetailtph(Request $request)
    {
        $ancaks = $request->input('delete_id');
        $oldData = DB::connection('mysql2')->table('sidak_tph')->where('id', $ancaks)->first();

        if (is_array($ancaks)) {
            // Delete multiple rows

            try {
                DB::connection('mysql2')->table('sidak_tph')->whereIn('id', $ancaks)->delete();
            } catch (\Throwable $th) {
                //throw $th;
            } finally {
                $username = session('user_name');
                $userid = session('user_id');
                $date = Carbon::now();

                // Insert a record into the history table
                DB::connection('mysql2')->table('history_edit')->insert([
                    'id_user' => $userid,
                    'nama_user' => $username,
                    'data_baru' => 'delete_action',
                    'data_lama' => json_encode($oldData),
                    'tanggal' => $date,
                    'menu' => 'delete_tph',

                ]);
            }
        } else {

            try {
                DB::connection('mysql2')->table('sidak_tph')->where('id', $ancaks)->delete();
            } catch (\Throwable $th) {
                //throw $th;
            } finally {
                $username = session('user_name');
                $userid = session('user_id');
                $date = Carbon::now();

                // Insert a record into the history table
                DB::connection('mysql2')->table('history_edit')->insert([
                    'id_user' => $userid,
                    'nama_user' => $username,
                    'data_baru' => 'delete_action',
                    'data_lama' => json_encode($oldData),
                    'tanggal' => $date,
                    'menu' => 'delete_tph',

                ]);
                $username = $request->session()->get('user_name');
                $dataarr = 'User:' . $username . ' ' . 'Tanggal:' . Carbon::now() . ' ' . 'Melakukan: delete_tph';
                sendwhatsapp($dataarr);
            }
            // Delete a single row

        }

        return response()->json(['status' => 'success']);
    }

    public function pdfsidaktphdata($reg, $month)
    {
        $tanggal = $month;
        $regional = $reg;
        $newparamsdate = '2024-03-01';

        $tanggalDateTime = new DateTime($tanggal);
        // dd($tanggalDateTime);
        $newparamsdateDateTime = new DateTime($newparamsdate);
        // dd($newparamsdateDateTime);

        if ($tanggalDateTime >= $newparamsdateDateTime) {
            $dataparams = 'new';
        } else {
            $dataparams = 'old';
        }

        $ancakFA = DB::connection('mysql2')
            ->table('sidak_tph')
            ->select(
                "sidak_tph.*",
                DB::raw('DATE_FORMAT(sidak_tph.datetime, "%Y-%m-%d") as tanggal'),
                DB::raw('DATE_FORMAT(sidak_tph.datetime, "%M") as bulan'),
                DB::raw("
        CASE 
            WHEN status = '' THEN 1
            WHEN status = '0' THEN 1
            WHEN LOCATE('>H+', status) > 0 THEN '8'
            WHEN LOCATE('H+', status) > 0 THEN 
                CASE 
                    WHEN SUBSTRING_INDEX(SUBSTRING_INDEX(status, 'H+', -1), ' ', 1) > 8 THEN '8'
                    ELSE SUBSTRING_INDEX(SUBSTRING_INDEX(status, 'H+', -1), ' ', 1)
                END
            WHEN status REGEXP '^[0-9]+$' AND status > 8 THEN '8'
            WHEN LENGTH(status) > 1 AND status NOT LIKE '%H+%' AND status NOT LIKE '%>H+%' AND LOCATE(',', status) > 0 THEN SUBSTRING_INDEX(status, ',', 1)
            ELSE status
        END AS statuspanen")
            )
            ->where('sidak_tph.datetime', 'like', '%' . $tanggal . '%')
            ->orderBy('status', 'asc')
            ->get();

        $ancakFA = $ancakFA->groupBy(['est', 'afd', 'statuspanen', 'tanggal', 'blok']);
        $ancakFA = json_decode($ancakFA, true);



        // dd($ancakFA);

        $dateString = $tanggal;
        $dateParts = date_parse($dateString);
        $year = $dateParts['year'];
        $month = $dateParts['month'];

        $year = $year; // Replace with the desired year
        $month = $month;   // Replace with the desired month (September in this example)

        if ($regional == 3) {

            $weeks = [];
            $firstDayOfMonth = strtotime("$year-$month-01");
            $lastDayOfMonth = strtotime(date('Y-m-t', $firstDayOfMonth));

            $weekNumber = 1;

            // Find the first Saturday of the month or the last Saturday of the previous month
            $firstSaturday = strtotime("last Saturday", $firstDayOfMonth);

            // Set the start date to the first Saturday
            $startDate = $firstSaturday;

            while ($startDate <= $lastDayOfMonth) {
                $endDate = strtotime("next Friday", $startDate);
                if ($endDate > $lastDayOfMonth) {
                    $endDate = $lastDayOfMonth;
                }

                $weeks[$weekNumber] = [
                    'start' => date('Y-m-d', $startDate),
                    'end' => date('Y-m-d', $endDate),
                ];

                // Update start date to the next Saturday
                $startDate = strtotime("next Saturday", $endDate);

                $weekNumber++;
            }
        } else {
            $weeks = [];
            $firstDayOfMonth = strtotime("$year-$month-01");
            $lastDayOfMonth = strtotime(date('Y-m-t', $firstDayOfMonth));

            $weekNumber = 1;
            $startDate = $firstDayOfMonth;

            while ($startDate <= $lastDayOfMonth) {
                $endDate = strtotime("next Sunday", $startDate);
                if ($endDate > $lastDayOfMonth) {
                    $endDate = $lastDayOfMonth;
                }

                $weeks[$weekNumber] = [
                    'start' => date('Y-m-d', $startDate),
                    'end' => date('Y-m-d', $endDate),
                ];

                $nextMonday = strtotime("next Monday", $endDate);

                // Check if the next Monday is still within the current month.
                if (date('m', $nextMonday) == $month) {
                    $startDate = $nextMonday;
                } else {
                    // If the next Monday is in the next month, break the loop.
                    break;
                }

                $weekNumber++;
            }
        }


        // dd($weeks);

        $result = [];

        // Iterate through the original array
        foreach ($ancakFA as $mainKey => $mainValue) {
            $result[$mainKey] = [];

            foreach ($mainValue as $subKey => $subValue) {
                $result[$mainKey][$subKey] = [];

                foreach ($subValue as $dateKey => $dateValue) {
                    // Remove 'H+' prefix if it exists
                    $numericIndex = is_numeric($dateKey) ? $dateKey : (strpos($dateKey, 'H+') === 0 ? substr($dateKey, 2) : $dateKey);

                    if (!isset($result[$mainKey][$subKey][$numericIndex])) {
                        $result[$mainKey][$subKey][$numericIndex] = [];
                    }

                    foreach ($dateValue as $statusKey => $statusValue) {
                        // Handle 'H+' prefix in status
                        $statusIndex = is_numeric($statusKey) ? $statusKey : (strpos($statusKey, 'H+') === 0 ? substr($statusKey, 2) : $statusKey);

                        if (!isset($result[$mainKey][$subKey][$numericIndex][$statusIndex])) {
                            $result[$mainKey][$subKey][$numericIndex][$statusIndex] = [];
                        }

                        foreach ($statusValue as $blokKey => $blokValue) {
                            $result[$mainKey][$subKey][$numericIndex][$statusIndex][$blokKey] = $blokValue;
                        }
                    }
                }
            }
        }

        // result by statis week 
        $newResult = [];

        foreach ($result as $key => $value) {
            $newResult[$key] = [];

            foreach ($value as $estKey => $est) {
                $newResult[$key][$estKey] = [];

                foreach ($est as $statusKey => $status) {
                    $newResult[$key][$estKey][$statusKey] = [];

                    foreach ($weeks as $weekKey => $week) {
                        $newStatus = [];

                        foreach ($status as $date => $data) {
                            if (strtotime($date) >= strtotime($week["start"]) && strtotime($date) <= strtotime($week["end"])) {
                                $newStatus[$date] = $data;
                            }
                        }

                        if (!empty($newStatus)) {
                            $newResult[$key][$estKey][$statusKey]["week" . ($weekKey + 1)] = $newStatus;
                        }
                    }
                }
            }
        }

        // result by week status 
        $WeekStatus = [];

        foreach ($result as $key => $value) {
            $WeekStatus[$key] = [];

            foreach ($value as $estKey => $est) {
                $WeekStatus[$key][$estKey] = [];

                foreach ($weeks as $weekKey => $week) {
                    $WeekStatus[$key][$estKey]["week" . ($weekKey + 0)] = [];

                    foreach ($est as $statusKey => $status) {
                        $newStatus = [];

                        foreach ($status as $date => $data) {
                            if (strtotime($date) >= strtotime($week["start"]) && strtotime($date) <= strtotime($week["end"])) {
                                $newStatus[$date] = $data;
                            }
                        }

                        if (!empty($newStatus)) {
                            $WeekStatus[$key][$estKey]["week" . ($weekKey + 0)][$statusKey] = $newStatus;
                        }
                    }
                }
            }
        }

        // dd($WeekStatus);



        $qrafd = DB::connection('mysql2')->table('afdeling')
            ->select(
                'afdeling.id',
                'afdeling.nama',
                'estate.est'
            ) //buat mengambil data di estate db dan willayah db
            ->join('estate', 'estate.id', '=', 'afdeling.estate') //kemudian di join untuk mengambil est perwilayah
            ->get();
        $qrafd = json_decode($qrafd, true);
        $queryEstereg = DB::connection('mysql2')->table('estate')
            ->select('estate.*')
            // ->whereNotIn('estate.est', ['PLASMA', 'CWS1', 'SRS'])
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->where('estate.emp', '!=', 1)
            ->where('wil.regional', $regional)
            ->get();
        $queryEstereg = json_decode($queryEstereg, true);

        // dd($queryEstereg);
        $defaultNew = array();

        foreach ($queryEstereg as $est) {
            foreach ($qrafd as $afd) {
                if ($est['est'] == $afd['est']) {
                    $defaultNew[$est['est']][$afd['nama']] = 0;
                }
            }
        }



        foreach ($defaultNew as $key => $estValue) {
            foreach ($estValue as $monthKey => $monthValue) {
                foreach ($newResult as $dataKey => $dataValue) {

                    if ($dataKey == $key) {
                        foreach ($dataValue as $dataEstKey => $dataEstValue) {

                            if ($dataEstKey == $monthKey) {
                                $defaultNew[$key][$monthKey] = $dataEstValue;
                            }
                        }
                    }
                }
            }
        }

        // dd($queryEstereg, $qrafd);

        $defaultWeek = array();
        foreach ($queryEstereg as $est) {
            foreach ($qrafd as $afd) {
                if ($est['est'] == $afd['est']) {
                    if (in_array($est['est'], ['LDE', 'SRE'])) {
                        $defaultWeek[$est['est']][$afd['est']] = 0;
                    } else {
                        $defaultWeek[$est['est']][$afd['nama']] = 0;
                    }
                }
            }
        }

        // dd($defaultWeek);
        foreach ($defaultWeek as $key => $estValue) {
            foreach ($estValue as $monthKey => $monthValue) {
                foreach ($WeekStatus as $dataKey => $dataValue) {

                    if ($dataKey == $key) {
                        foreach ($dataValue as $dataEstKey => $dataEstValue) {

                            if ($dataEstKey == $monthKey) {
                                $defaultWeek[$key][$monthKey] = $dataEstValue;
                            }
                        }
                    }
                }
            }
        }


        $newDefaultWeek = [];

        foreach ($defaultWeek as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $key1 => $value1) {
                    if (is_array($value1)) {
                        foreach ($value1 as $subKey => $subValue) {
                            if (is_array($subValue)) {
                                // Check if both key 0 and key 1 exist
                                $hasKeyZero = isset($subValue[0]);
                                $hasKeyOne = isset($subValue[1]);

                                // Merge key 0 into key 1
                                if ($hasKeyZero && $hasKeyOne) {
                                    $subValue[1] = array_merge_recursive((array)$subValue[1], (array)$subValue[0]);
                                    unset($subValue[0]);
                                } elseif ($hasKeyZero && !$hasKeyOne) {
                                    // Create key 1 and merge key 0 into it
                                    $subValue[1] = $subValue[0];
                                    unset($subValue[0]);
                                }

                                // Check if keys 1 through 7 don't exist, add them with a default value of 0
                                for ($i = 1; $i <= 7; $i++) {
                                    if (!isset($subValue[$i])) {
                                        $subValue[$i] = 0;
                                    }
                                }

                                // Ensure key 8 exists, and if not, create it with a default value of an empty array
                                if (!isset($subValue[8])) {
                                    $subValue[8] = 0;
                                }

                                // Check if keys higher than 8 exist, merge them into index 8
                                for ($i = 9; $i <= 100; $i++) {
                                    if (isset($subValue[$i])) {
                                        $subValue[8] = array_merge_recursive((array)$subValue[8], (array)$subValue[$i]);
                                        unset($subValue[$i]);
                                    }
                                }
                            }
                            $newDefaultWeek[$key][$key1][$subKey] = $subValue;
                        }
                    } else {
                        // Check if $value1 is equal to 0 and add "week1" to "week5" keys
                        if ($value1 === 0) {
                            $newDefaultWeek[$key][$key1] = [];
                            for ($i = 1; $i <= 5; $i++) {
                                $weekKey = "week" . $i;
                                $newDefaultWeek[$key][$key1][$weekKey] = [];
                                for ($j = 1; $j <= 8; $j++) {
                                    $newDefaultWeek[$key][$key1][$weekKey][$j] = 0;
                                }
                            }
                        } else {
                            $newDefaultWeek[$key][$key1] = $value1;
                        }
                    }
                }
            } else {
                $newDefaultWeek[$key] = $value;
            }
        }
        // dd($newDefaultWeek['Plasma1']['WIL-III']);
        // dd($newDefaultWeek);

        function removeZeroFromDatetime2x(&$array)
        {
            foreach ($array as $key => &$value) {
                if (is_array($value)) {
                    foreach ($value as $key1 => &$value2) {
                        if (is_array($value2)) {
                            foreach ($value2 as $key2 => &$value3) {
                                if (is_array($value3)) {
                                    foreach ($value3 as $key3 => &$value4) if (is_array($value4)) {
                                        foreach ($value4 as $key4 => $value5) {
                                            if ($key4 === 0 && $value5 === 0) {
                                                unset($value4[$key4]); // Unset the key 0 => 0 within the current nested array
                                            }
                                            removeZeroFromDatetime2x($value4);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        removeZeroFromDatetime2x($newDefaultWeek);

        function filterEmptyWeeksx(&$array)
        {
            foreach ($array as $key => &$value) {
                if (is_array($value)) {
                    filterEmptyWeeksx($value); // Recursively check nested arrays
                    if (empty($value) && $key !== 'week') {
                        unset($array[$key]);
                    }
                }
            }
        }

        // dd($defaultWeek);
        // Call the function on your array
        filterEmptyWeeksx($defaultWeek);




        // dd($defaultWeek);
        $dividen = [];

        foreach ($defaultWeek as $key => $value) {
            foreach ($value as $key1 => $value1) if (is_array($value1)) {
                foreach ($value1 as $key2 => $value2) if (is_array($value2)) {

                    $dividenn = count($value1);
                }
                $dividen[$key][$key1]['dividen'] = $dividenn;
            } else {
                $dividen[$key][$key1]['dividen'] = 0;
            }
        }
        // dd($newDefaultWeek['BKE']['OA']);

        // dd($newDefaultWeek['Plasma1']['WIL-III']);
        $newSidak = array();
        $asisten_qc = DB::connection('mysql2')
            ->Table('asisten_qc')
            ->get();
        $asisten_qc = json_decode($asisten_qc, true);
        // dd($newDefaultWeek['SLE']['OA']);
        $devest = 0;
        foreach ($newDefaultWeek as $key => $value) {
            $dividen_afd = 0;
            $total_skoreest = 0;
            $tot_estAFd = 0;
            $new_dvdAfd = 0;
            $new_dvdAfdest = 0;
            $total_estkors = 0;
            $total_skoreafd = 0;

            $deviden = 0;
            $devest = count($value);
            // dd($devest);
            // dd($value);

            foreach ($value as $key1 => $value2)  if (is_array($value2)) {

                $tot_afdscore = 0;
                $totskor_brd1 = 0;
                $totskor_janjang1 = 0;
                $total_skoreest = 0;
                $newpembagi1 = 0;
                foreach ($value2 as $key2 => $value3) {


                    $total_brondolan = 0;
                    $total_janjang = 0;
                    $tod_brd = 0;
                    $tod_jjg = 0;
                    $totskor_brd = 0;
                    $totskor_janjang = 0;
                    $tot_brdxm = 0;
                    $tod_janjangxm = 0;
                    $v2check3 = 0;

                    foreach ($value3 as $key3 => $value4) if (is_array($value4)) {
                        $tph1 = 0;
                        $jalan1 = 0;
                        $bin1 = 0;
                        $karung1 = 0;
                        $buah1 = 0;
                        $restan1 = 0;
                        $v2check2 = 0;

                        foreach ($value4 as $key4 => $value5) if (is_array($value5)) {
                            $tph = 0;
                            $jalan = 0;
                            $bin = 0;
                            $karung = 0;
                            $buah = 0;
                            $restan = 0;
                            $v2check = count($value5);
                            foreach ($value5 as $key5 => $value6) {
                                $sum_bt_tph = 0;
                                $sum_bt_jalan = 0;
                                $sum_bt_bin = 0;
                                $sum_jum_karung = 0;
                                $sum_buah_tinggal = 0;
                                $sum_restan_unreported = 0;
                                $sum_all_restan_unreported = 0;

                                foreach ($value6 as $key6 => $value7) {
                                    // dd($value7);
                                    // dd($value7);
                                    $sum_bt_tph += $value7['bt_tph'];
                                    $sum_bt_jalan += $value7['bt_jalan'];
                                    $sum_bt_bin += $value7['bt_bin'];
                                    $sum_jum_karung += $value7['jum_karung'];


                                    $sum_buah_tinggal += $value7['buah_tinggal'];
                                    $sum_restan_unreported += $value7['restan_unreported'];
                                }
                                $newSidak[$key][$key1][$key2][$key3][$key4][$key5]['tph'] = $sum_bt_tph;
                                $newSidak[$key][$key1][$key2][$key3][$key4][$key5]['jalan'] = $sum_bt_jalan;
                                $newSidak[$key][$key1][$key2][$key3][$key4][$key5]['bin'] = $sum_bt_bin;
                                $newSidak[$key][$key1][$key2][$key3][$key4][$key5]['karung'] = $sum_jum_karung;

                                $newSidak[$key][$key1][$key2][$key3][$key4][$key5]['buah'] = $sum_buah_tinggal;
                                $newSidak[$key][$key1][$key2][$key3][$key4][$key5]['restan'] = $sum_restan_unreported;


                                $tph += $sum_bt_tph;
                                $jalan += $sum_bt_jalan;
                                $bin += $sum_bt_bin;
                                $karung += $sum_jum_karung;
                                $buah += $sum_buah_tinggal;
                                $restan += $sum_restan_unreported;
                            }

                            $newSidak[$key][$key1][$key2][$key3][$key4]['tph'] = $tph;
                            $newSidak[$key][$key1][$key2][$key3][$key4]['jalan'] = $jalan;
                            $newSidak[$key][$key1][$key2][$key3][$key4]['bin'] = $bin;
                            $newSidak[$key][$key1][$key2][$key3][$key4]['karung'] = $karung;

                            $newSidak[$key][$key1][$key2][$key3][$key4]['buah'] = $buah;
                            $newSidak[$key][$key1][$key2][$key3][$key4]['restan'] = $restan;
                            $newSidak[$key][$key1][$key2][$key3][$key4]['v2check'] = $v2check;

                            $tph1 += $tph;
                            $jalan1 += $jalan;
                            $bin1 += $bin;
                            $karung1 += $karung;
                            $buah1 += $buah;
                            $restan1 += $restan;
                            $v2check2 += $v2check;
                        }
                        // dd($key3);
                        $status_panen = $key3;

                        if ($dataparams === 'new') {
                            [$panen_brd, $panen_jjg] = calculatePanennew($status_panen);
                        } else {
                            [$panen_brd, $panen_jjg] = calculatePanen($status_panen);
                        }



                        // untuk brondolan gabungan dari bt-tph,bt-jalan,bt-bin,jum-karung 
                        $total_brondolan =  round(($tph1 + $jalan1 + $bin1 + $karung1) * $panen_brd / 100, 1);
                        $total_janjang =  round(($buah1 + $restan1) * $panen_jjg / 100, 1);
                        $tod_brd = $tph1 + $jalan1 + $bin1 + $karung1;
                        $tod_jjg = $buah1 + $restan1;
                        $newSidak[$key][$key1][$key2][$key3]['tphx'] = $tph1;
                        $newSidak[$key][$key1][$key2][$key3]['jalan'] = $jalan1;
                        $newSidak[$key][$key1][$key2][$key3]['bin'] = $bin1;
                        $newSidak[$key][$key1][$key2][$key3]['karung'] = $karung1;
                        $newSidak[$key][$key1][$key2][$key3]['tot_brd'] = $tod_brd;

                        $newSidak[$key][$key1][$key2][$key3]['buah'] = $buah1;
                        $newSidak[$key][$key1][$key2][$key3]['restan'] = $restan1;
                        $newSidak[$key][$key1][$key2][$key3]['skor_brd'] = $total_brondolan;
                        $newSidak[$key][$key1][$key2][$key3]['skor_janjang'] = $total_janjang;
                        $newSidak[$key][$key1][$key2][$key3]['tod_jjg'] = $tod_jjg;
                        $newSidak[$key][$key1][$key2][$key3]['v2check2'] = $v2check2;

                        $totskor_brd += $total_brondolan;
                        $totskor_janjang += $total_janjang;
                        $tot_brdxm += $tod_brd;
                        $tod_janjangxm += $tod_jjg;
                        $v2check3 += $v2check2;
                    } else {
                        $newSidak[$key][$key1][$key2][$key3]['tphx'] = 0;
                        $newSidak[$key][$key1][$key2][$key3]['jalan'] = 0;
                        $newSidak[$key][$key1][$key2][$key3]['bin'] = 0;
                        $newSidak[$key][$key1][$key2][$key3]['karung'] = 0;

                        $newSidak[$key][$key1][$key2][$key3]['buah'] = 0;
                        $newSidak[$key][$key1][$key2][$key3]['restan'] = 0;

                        $newSidak[$key][$key1][$key2][$key3]['skor_brd'] = 0;
                        $newSidak[$key][$key1][$key2][$key3]['skor_janjang'] = 0;
                        $newSidak[$key][$key1][$key2][$key3]['tot_brd'] = 0;
                        $newSidak[$key][$key1][$key2][$key3]['tod_jjg'] = 0;
                        $newSidak[$key][$key1][$key2][$key3]['v2check2'] = 0;
                    }


                    $total_estkors = $totskor_brd + $totskor_janjang;
                    if ($total_estkors != 0) {

                        $checkscore = 100 - ($total_estkors);

                        if ($checkscore < 0) {
                            $newscore = 0;
                            $newSidak[$key][$key1][$key2]['mines'] = 'ada';
                        } else {
                            $newscore = $checkscore;
                            $newSidak[$key][$key1][$key2]['mines'] = 'tidak';
                        }

                        $newSidak[$key][$key1][$key2]['all_score'] = $newscore;
                        $newSidak[$key][$key1][$key2]['check_data'] = 'ada';

                        $total_skoreafd = $newscore;
                        $newpembagi = 1;
                    } else if ($v2check3 != 0) {
                        $checkscore = 100 - ($total_estkors);

                        if ($checkscore < 0) {
                            $newscore = 0;
                            $newSidak[$key][$key1][$key2]['mines'] = 'ada';
                        } else {
                            $newscore = $checkscore;
                            $newSidak[$key][$key1][$key2]['mines'] = 'tidak';
                        }
                        $newSidak[$key][$key1][$key2]['all_score'] = 100 - ($total_estkors);
                        $newSidak[$key][$key1][$key2]['check_data'] = 'ada';

                        $total_skoreafd = $newscore;

                        $newpembagi = 1;
                    } else {
                        $newSidak[$key][$key1][$key2]['all_score'] = 0;
                        $newSidak[$key][$key1][$key2]['check_data'] = 'null';
                        $total_skoreafd = 0;
                        $newpembagi = 0;
                    }
                    // $newSidak[$key][$key1][$key2]['all_score'] = 100 - ($total_estkors);
                    $newSidak[$key][$key1][$key2]['total_brd'] = $tot_brdxm;
                    $newSidak[$key][$key1][$key2]['total_brdSkor'] = $totskor_brd;
                    $newSidak[$key][$key1][$key2]['total_janjang'] = $tod_janjangxm;
                    $newSidak[$key][$key1][$key2]['total_janjangSkor'] = $totskor_janjang;
                    $newSidak[$key][$key1][$key2]['total_skor'] = $total_skoreafd;
                    $newSidak[$key][$key1][$key2]['janjang_brd'] = $totskor_brd + $totskor_janjang;
                    $newSidak[$key][$key1][$key2]['v2check3'] = $v2check3;
                    $newSidak[$key][$key1][$key2]['newpembagi'] = $newpembagi;

                    $totskor_brd1 += $totskor_brd;
                    $totskor_janjang1 += $totskor_janjang;
                    $total_skoreest += $total_skoreafd;
                    $newpembagi1 += $newpembagi;
                }



                $namaGM = '-';
                foreach ($asisten_qc as $asisten) {

                    // dd($asisten);
                    if ($asisten['est'] == $key && $asisten['afd'] == $key1) {
                        $namaGM = $asisten['nama'];
                        break;
                    }
                }

                $deviden = count($value2);


                if ($newpembagi1 != 0) {
                    $tot_afdscore = round($total_skoreest / $newpembagi1, 1);
                } else {
                    $tot_afdscore = 0;  # code...
                }
                if ($newpembagi1 != 0) {
                    $estbagi = 1;
                } else {
                    $estbagi = 0;
                }

                // $newSidak[$key][$key1]['deviden'] = $deviden;
                $newSidak[$key][$key1]['total_score'] = $tot_afdscore;
                $newSidak[$key][$key1]['total_brd'] = $totskor_brd1;
                $newSidak[$key][$key1]['total_janjang'] = $totskor_janjang1;
                $newSidak[$key][$key1]['new_deviden'] = 1;
                $newSidak[$key][$key1]['asisten'] = $namaGM;
                $newSidak[$key][$key1]['total_skor'] = $total_skoreest;
                $newSidak[$key][$key1]['est'] = $key;
                $newSidak[$key][$key1]['afd'] = $key1;
                $newSidak[$key][$key1]['devidenest'] = $devest;

                $tot_estAFd += $tot_afdscore;
                $new_dvdAfdest += $estbagi;
            } else {
                $namaGM = '-';
                foreach ($asisten_qc as $asisten) {

                    // dd($asisten);
                    if ($asisten['est'] == $key && $asisten['afd'] == $key1) {
                        $namaGM = $asisten['nama'];
                        break;
                    }
                }
                $newSidak[$key][$key1]['deviden'] = 0;
                $newSidak[$key][$key1]['total_score'] = 0;
                $newSidak[$key][$key1]['total_brd'] = 0;
                $newSidak[$key][$key1]['new_deviden'] = 0;
                $newSidak[$key][$key1]['total_janjang'] = 0;
                $newSidak[$key][$key1]['asisten'] = $namaGM;
            }
            if ($new_dvdAfdest != 0) {
                $total_skoreest = round($tot_estAFd / $new_dvdAfdest, 1);
            } else {
                $total_skoreest = 0;
            }

            // dd($value);

            $namaGM = '-';
            foreach ($asisten_qc as $asisten) {
                if ($asisten['est'] == $key && $asisten['afd'] == 'EM') {
                    $namaGM = $asisten['nama'];
                    break;
                }
            }
            if ($new_dvdAfd != 0) {
                $newSidak[$key]['deviden'] = 1;
            } else {
                $newSidak[$key]['deviden'] = 0;
            }

            $newSidak[$key]['total_skorest'] = $tot_estAFd;
            $newSidak[$key]['score_estate'] = $total_skoreest;
            $newSidak[$key]['asisten'] = $namaGM;
            $newSidak[$key]['estate'] = $key;
            $newSidak[$key]['afd'] = 'GM';
            $newSidak[$key]['dividen'] = $new_dvdAfdest;
            $newSidak[$key]['afdeling'] = $devest;
        }


        $week1 = []; // Initialize the new array
        foreach ($newSidak as $key => $value) {
            $estateValues = []; // Initialize an array to accumulate values for estate
            $est_brd = 0;
            $total_weeks = 0;
            $deviden = 0;
            $devnew = 0;
            $skor_akhir = 0;
            $nulldata = [];
            $afdcount = $value['afdeling'] ?? 0;
            $scoreest = 0;
            foreach ($value as $subKey => $subValue) {
                if (is_array($subValue) && isset($subValue['week1'])) {
                    $week1Data = $subValue['week1']; // Access "week1" data

                    // dd($week1Data);
                    foreach ($weeks as $keywk => $value) if ($keywk == 1) {
                        $start = $value['start'];
                        $end = $value['end'];
                    }
                    // week for afdeling 
                    // dd($week1Data);
                    if ($week1Data['all_score'] == 0 && $week1Data['v2check3'] != 0  && $week1Data['mines'] === 'ada') {
                        # code...
                        $total_score = 0;
                        $data = 'ada';
                    } else if ($week1Data['all_score'] == 0 && $week1Data['v2check3'] != 0 && $week1Data['mines'] === 'tidak') {
                        # code...
                        $total_score = 100;
                        $data = 'ada';
                    } else if ($week1Data['all_score'] == 0 && $week1Data['check_data'] == 'null') {
                        $total_score = 0;
                        $data = 'kosong';
                    } else {
                        $total_score =  round($week1Data['all_score'], 1);
                        $data = 'ada';
                    }

                    $week1Flat = [
                        'est' => $key,
                        'afd' => $subKey,
                        'total_score' => $total_score,
                        'kategori' => $week1Data['check_data'],
                        'inspek' => $data
                    ];

                    // Extract tphx values for keys 1 to 8 and flatten them
                    for ($i = 1; $i <= 8; $i++) {
                        $tphxKey = $i;
                        $tphxValue = $week1Data[$tphxKey]['tphx'];
                        $jalan = $week1Data[$tphxKey]['jalan'];
                        $bin = $week1Data[$tphxKey]['bin'];
                        $karung = $week1Data[$tphxKey]['karung'];
                        $buah = $week1Data[$tphxKey]['buah'];
                        $restan = $week1Data[$tphxKey]['restan'];
                        $skor_brd = $week1Data[$tphxKey]['skor_brd'];
                        $skor_janjang = $week1Data[$tphxKey]['skor_janjang'];
                        $tot_brd = $week1Data[$tphxKey]['tot_brd'];
                        $tod_jjg = $week1Data[$tphxKey]['tod_jjg'];

                        $week1Flat["tph$i"] = $tphxValue;
                        $week1Flat["jalan$i"] = $jalan;
                        $week1Flat["bin$i"] = $bin;
                        $week1Flat["karung$i"] = $karung;
                        $week1Flat["buah$i"] = $buah;
                        $week1Flat["restan$i"] = $restan;
                        $week1Flat["skor_brd$i"] = $skor_brd;
                        $week1Flat["skor_janjang$i"] = round($skor_janjang, 1);
                        $week1Flat["tot_brd$i"] = $tot_brd;
                        $week1Flat["tod_jjg$i"] = $tod_jjg;
                        if (!isset($estateValues["tph$i"])) {
                            $estateValues["tph$i"] = 0;
                            $estateValues["jalan$i"] = 0;
                            $estateValues["bin$i"] = 0;
                            $estateValues["karung$i"] = 0;
                            $estateValues["buah$i"] = 0;
                            $estateValues["restan$i"] = 0;
                            $estateValues["skor_brd$i"] = 0;
                            $estateValues["skor_janjang$i"] = 0;
                            $estateValues["tot_brd$i"] = 0;
                            $estateValues["tod_jjg$i"] = 0;
                        }
                        if ($dataparams === 'new') {
                            [$panen_brd, $panen_jjg] = calculatePanennew($i);
                        } else {
                            [$panen_brd, $panen_jjg] = calculatePanen($i);
                        }



                        // [$panen_brd, $panen_jjg] = calculatePanen($i);

                        $estateValues["tph$i"] += $tphxValue;
                        $estateValues["jalan$i"] += $jalan;
                        $estateValues["bin$i"] += $bin;
                        $estateValues["karung$i"] += $karung;
                        $estateValues["buah$i"] += $buah;
                        $estateValues["restan$i"] += $restan;
                        $estateValues["skor_brd$i"] += round(($tot_brd * $panen_brd) / 100, 1);
                        $estateValues["skor_janjang$i"] += round(($tod_jjg * $panen_jjg) / 100, 1);
                        $estateValues["tot_brd$i"] += $tot_brd;
                        $estateValues["tod_jjg$i"] += $tod_jjg;
                    }
                    $total_weeks += round($week1Data['all_score'], 2);
                    $deviden += $subValue['new_deviden'];
                    $devnew = $subValue['devidenest'];

                    // Add the flattened array to the result
                    $week1[] = $week1Flat;
                    $nulldata[] .= $week1Data['check_data'];
                    $v2check3 = $week1Data['v2check3'];
                    $scoreest += $total_score;
                }
            }


            $counts = array_count_values($nulldata);

            // Subtract the count of 'ada' from the total count
            $getnull = count($nulldata) - ($counts['ada'] ?? 0);

            // Set getnull to 0 if it's negative (in case 'ada' occurs more times than the array size)
            $getnull = max(0, $getnull);
            if ($devnew != 0 && $scoreest != 0) {
                // $skor_akhir = round($total_weeks / $deviden, 1);
                $skor_akhir = round($scoreest / $devnew, 2);
            } else {
                $skor_akhir = '-';
            }

            // week for estate 
            $weekestate = [
                'est' => $key,
                'afd' => 'EST',
                'kategori' => 'Test',
                'total_score' => $skor_akhir,
                'skore' => $scoreest,
                'pembagi' => $devnew,
                'start' => $start,
                'end' => $end,
                'reg' => $regional,

            ];
            $skor_brd = 0;
            $skor_janjang = 0;
            for ($i = 1; $i <= 8; $i++) {
                // Calculate the values for estate using $estateValues

                $skor_brd += round($estateValues["skor_brd$i"], 1);
                $skor_janjang += round($estateValues["skor_janjang$i"], 1);
                $weekestate["tph$i"] = $estateValues["tph$i"];
                $weekestate["jalan$i"] = $estateValues["jalan$i"];
                $weekestate["bin$i"] = $estateValues["bin$i"];
                $weekestate["karung$i"] = $estateValues["karung$i"];
                $weekestate["buah$i"] = $estateValues["buah$i"];
                $weekestate["restan$i"] = $estateValues["restan$i"];
                $weekestate["skor_brd$i"] = $estateValues["skor_brd$i"];
                $weekestate["skor_janjang$i"] = round($estateValues["skor_janjang$i"], 1);
                $weekestate["tot_brd$i"] = $estateValues["tot_brd$i"];
                $weekestate["tod_jjg$i"] = $estateValues["tod_jjg$i"];
                // $weekestate["total_score"] = round($skor_brd + $skor_janjang, 1);
            }


            $week1[] = $weekestate;
        }
        // dd($week1[57], $week1[58], $week1[59], $week1[60], $week1[61], $week1[62]);
        // dd($newSidak['KNE']);
        // dd($week1);

        $week2 = []; // Initialize the new array
        foreach ($newSidak as $key => $value) {
            $estateValues = []; // Initialize an array to accumulate values for estate
            $est_brd = 0;
            $total_weeks = 0;
            $deviden = 0;
            $skor_akhir = 0;
            $devnew = 0;
            $nulldata = [];
            $afdcount = $value['afdeling'];
            $scoreest = 0;
            foreach ($value as $subKey => $subValue) {
                if (is_array($subValue) && isset($subValue['week2'])) {
                    $week1Data = $subValue['week2']; // Access "week1" data
                    foreach ($weeks as $keywk => $value) if ($keywk == 2) {
                        $start = $value['start'];
                        $end = $value['end'];
                    }

                    if ($week1Data['all_score'] == 0 && $week1Data['v2check3'] != 0  && $week1Data['mines'] === 'ada') {
                        # code...
                        $total_score = 0;
                        $data = 'ada';
                    } else if ($week1Data['all_score'] == 0 && $week1Data['v2check3'] != 0 && $week1Data['mines'] === 'tidak') {
                        # code...
                        $total_score = 100;
                        $data = 'ada';
                    } else if ($week1Data['all_score'] == 0 && $week1Data['check_data'] == 'null') {
                        $total_score = 0;
                        $data = 'kosong';
                    } else {
                        $total_score =  round($week1Data['all_score'], 1);
                        $data = 'ada';
                    }


                    $week1Flat = [
                        'est' => $key,
                        'afd' => $subKey,
                        'total_score' => $total_score,
                        'kategori' => $week1Data['check_data'],
                        'inspek' => $data
                    ];

                    // Extract tphx values for keys 1 to 8 and flatten them
                    for ($i = 1; $i <= 8; $i++) {
                        $tphxKey = $i;
                        $tphxValue = $week1Data[$tphxKey]['tphx'];
                        $jalan = $week1Data[$tphxKey]['jalan'];
                        $bin = $week1Data[$tphxKey]['bin'];
                        $karung = $week1Data[$tphxKey]['karung'];
                        $buah = $week1Data[$tphxKey]['buah'];
                        $restan = $week1Data[$tphxKey]['restan'];
                        $skor_brd = $week1Data[$tphxKey]['skor_brd'];
                        $skor_janjang = $week1Data[$tphxKey]['skor_janjang'];
                        $tot_brd = $week1Data[$tphxKey]['tot_brd'];
                        $tod_jjg = $week1Data[$tphxKey]['tod_jjg'];

                        $week1Flat["tph$i"] = $tphxValue;
                        $week1Flat["jalan$i"] = $jalan;
                        $week1Flat["bin$i"] = $bin;
                        $week1Flat["karung$i"] = $karung;
                        $week1Flat["buah$i"] = $buah;
                        $week1Flat["restan$i"] = $restan;
                        $week1Flat["skor_brd$i"] = $skor_brd;
                        $week1Flat["skor_janjang$i"] = round($skor_janjang, 1);
                        $week1Flat["tot_brd$i"] = $tot_brd;
                        $week1Flat["tod_jjg$i"] = $tod_jjg;
                        if (!isset($estateValues["tph$i"])) {
                            $estateValues["tph$i"] = 0;
                            $estateValues["jalan$i"] = 0;
                            $estateValues["bin$i"] = 0;
                            $estateValues["karung$i"] = 0;
                            $estateValues["buah$i"] = 0;
                            $estateValues["restan$i"] = 0;
                            $estateValues["skor_brd$i"] = 0;
                            $estateValues["skor_janjang$i"] = 0;
                            $estateValues["tot_brd$i"] = 0;
                            $estateValues["tod_jjg$i"] = 0;
                        }


                        if ($dataparams === 'new') {
                            [$panen_brd, $panen_jjg] = calculatePanennew($i);
                        } else {
                            [$panen_brd, $panen_jjg] = calculatePanen($i);
                        }


                        $estateValues["tph$i"] += $tphxValue;
                        $estateValues["jalan$i"] += $jalan;
                        $estateValues["bin$i"] += $bin;
                        $estateValues["karung$i"] += $karung;
                        $estateValues["buah$i"] += $buah;
                        $estateValues["restan$i"] += $restan;
                        $estateValues["skor_brd$i"] += round(($tot_brd * $panen_brd) / 100, 1);
                        $estateValues["skor_janjang$i"] += round(($tod_jjg * $panen_jjg) / 100, 1);
                        $estateValues["tot_brd$i"] += $tot_brd;
                        $estateValues["tod_jjg$i"] += $tod_jjg;
                    }
                    $total_weeks += round($week1Data['all_score'], 1);
                    $deviden += $subValue['new_deviden'];
                    $devnew = $subValue['devidenest'];
                    // Add the flattened array to the result
                    $week2[] = $week1Flat;
                    $nulldata[] .= $week1Data['check_data'];
                    $v2check3 = $week1Data['v2check3'];
                    $scoreest += $total_score;
                }
            }

            $counts = array_count_values($nulldata);

            // Subtract the count of 'ada' from the total count
            $getnull = count($nulldata) - ($counts['ada'] ?? 0);

            // Set getnull to 0 if it's negative (in case 'ada' occurs more times than the array size)
            $getnull = max(0, $getnull);
            if ($devnew != 0 && $scoreest != 0) {
                // $skor_akhir = round($total_weeks / $deviden, 1);
                $skor_akhir = round($scoreest / $devnew, 1);
            } else {
                $skor_akhir = '-';
            }


            // week for estate 
            $weekestate = [
                'est' => $key,
                'afd' => 'EST',
                'kategori' => 'Test',
                'total_score' => $skor_akhir,
                'start' => $start,
                'end' => $end,
                'reg' => $regional,
            ];
            $skor_brd = 0;
            $skor_janjang = 0;
            for ($i = 1; $i <= 8; $i++) {
                // Calculate the values for estate using $estateValues

                $skor_brd += round($estateValues["skor_brd$i"], 1);
                $skor_janjang += round($estateValues["skor_janjang$i"], 1);
                $weekestate["tph$i"] = $estateValues["tph$i"];
                $weekestate["jalan$i"] = $estateValues["jalan$i"];
                $weekestate["bin$i"] = $estateValues["bin$i"];
                $weekestate["karung$i"] = $estateValues["karung$i"];
                $weekestate["buah$i"] = $estateValues["buah$i"];
                $weekestate["restan$i"] = $estateValues["restan$i"];
                $weekestate["skor_brd$i"] = $estateValues["skor_brd$i"];
                $weekestate["skor_janjang$i"] = round($estateValues["skor_janjang$i"], 1);
                $weekestate["tot_brd$i"] = $estateValues["tot_brd$i"];
                $weekestate["tod_jjg$i"] = $estateValues["tod_jjg$i"];
                // $weekestate["total_score"] = round($skor_brd + $skor_janjang, 1);
            }


            $week2[] = $weekestate;
        }

        // dd($week2[15]);

        $week3 = []; // Initialize the new array
        foreach ($newSidak as $key => $value) {
            $estateValues = []; // Initialize an array to accumulate values for estate
            $est_brd = 0;
            $total_weeks = 0;
            $deviden = 0;
            $skor_akhir = 0;
            $devnew = 0;
            $nulldata = [];
            $afdcount = $value['afdeling'];
            $scoreest = 0;
            foreach ($value as $subKey => $subValue) {
                if (is_array($subValue) && isset($subValue['week3'])) {
                    $week1Data = $subValue['week3']; // Access "week1" data

                    // dd($week1Data);
                    foreach ($weeks as $keywk => $value) if ($keywk == 3) {
                        $start = $value['start'];
                        $end = $value['end'];
                    }
                    // week for afdeling 
                    if ($week1Data['all_score'] == 0 && $week1Data['v2check3'] != 0  && $week1Data['mines'] === 'ada') {
                        # code...
                        $total_score = 0;
                        $data = 'ada';
                    } else if ($week1Data['all_score'] == 0 && $week1Data['v2check3'] != 0 && $week1Data['mines'] === 'tidak') {
                        # code...
                        $total_score = 100;
                        $data = 'ada';
                    } else if ($week1Data['all_score'] == 0 && $week1Data['check_data'] == 'null') {
                        $total_score = 0;
                        $data = 'kosong';
                    } else {
                        $total_score =  round($week1Data['all_score'], 1);
                        $data = 'ada';
                    }


                    $week1Flat = [
                        'est' => $key,
                        'afd' => $subKey,
                        'total_score' => $total_score,
                        'kategori' => $week1Data['check_data'],
                        'inspek' => $data
                    ];
                    // Extract tphx values for keys 1 to 8 and flatten them
                    for ($i = 1; $i <= 8; $i++) {
                        $tphxKey = $i;
                        $tphxValue = $week1Data[$tphxKey]['tphx'];
                        $jalan = $week1Data[$tphxKey]['jalan'];
                        $bin = $week1Data[$tphxKey]['bin'];
                        $karung = $week1Data[$tphxKey]['karung'];
                        $buah = $week1Data[$tphxKey]['buah'];
                        $restan = $week1Data[$tphxKey]['restan'];
                        $skor_brd = $week1Data[$tphxKey]['skor_brd'];
                        $skor_janjang = $week1Data[$tphxKey]['skor_janjang'];
                        $tot_brd = $week1Data[$tphxKey]['tot_brd'];
                        $tod_jjg = $week1Data[$tphxKey]['tod_jjg'];

                        $week1Flat["tph$i"] = $tphxValue;
                        $week1Flat["jalan$i"] = $jalan;
                        $week1Flat["bin$i"] = $bin;
                        $week1Flat["karung$i"] = $karung;
                        $week1Flat["buah$i"] = $buah;
                        $week1Flat["restan$i"] = $restan;
                        $week1Flat["skor_brd$i"] = $skor_brd;
                        $week1Flat["skor_janjang$i"] = round($skor_janjang, 1);
                        $week1Flat["tot_brd$i"] = $tot_brd;
                        $week1Flat["tod_jjg$i"] = $tod_jjg;
                        if (!isset($estateValues["tph$i"])) {
                            $estateValues["tph$i"] = 0;
                            $estateValues["jalan$i"] = 0;
                            $estateValues["bin$i"] = 0;
                            $estateValues["karung$i"] = 0;
                            $estateValues["buah$i"] = 0;
                            $estateValues["restan$i"] = 0;
                            $estateValues["skor_brd$i"] = 0;
                            $estateValues["skor_janjang$i"] = 0;
                            $estateValues["tot_brd$i"] = 0;
                            $estateValues["tod_jjg$i"] = 0;
                        }


                        if ($dataparams === 'new') {
                            [$panen_brd, $panen_jjg] = calculatePanennew($i);
                        } else {
                            [$panen_brd, $panen_jjg] = calculatePanen($i);
                        }


                        $estateValues["tph$i"] += $tphxValue;
                        $estateValues["jalan$i"] += $jalan;
                        $estateValues["bin$i"] += $bin;
                        $estateValues["karung$i"] += $karung;
                        $estateValues["buah$i"] += $buah;
                        $estateValues["restan$i"] += $restan;
                        $estateValues["skor_brd$i"] += round(($tot_brd * $panen_brd) / 100, 1);
                        $estateValues["skor_janjang$i"] += round(($tod_jjg * $panen_jjg) / 100, 1);
                        $estateValues["tot_brd$i"] += $tot_brd;
                        $estateValues["tod_jjg$i"] += $tod_jjg;
                    }
                    $total_weeks += round($week1Data['all_score'], 1);
                    $deviden += $subValue['new_deviden'];
                    $devnew = $subValue['devidenest'];
                    // Add the flattened array to the result
                    $week3[] = $week1Flat;
                    $nulldata[] .= $week1Data['check_data'];
                    $v2check3 = $week1Data['v2check3'];
                    $scoreest += $total_score;
                }
            }
            $counts = array_count_values($nulldata);

            // Subtract the count of 'ada' from the total count
            $getnull = count($nulldata) - ($counts['ada'] ?? 0);

            // Set getnull to 0 if it's negative (in case 'ada' occurs more times than the array size)
            $getnull = max(0, $getnull);
            if ($devnew != 0 && $scoreest != 0) {
                // $skor_akhir = round($total_weeks / $deviden, 1);
                $skor_akhir = round($scoreest / $devnew, 1);
            } else {
                $skor_akhir = '-';
            }
            // week for estate 
            $weekestate = [
                'est' => $key,
                'afd' => 'EST',
                'kategori' => $getnull,
                'total_score' => $skor_akhir,
                'start' => $start,
                'end' => $end,
                'reg' => $regional,
            ];
            $skor_brd = 0;
            $skor_janjang = 0;
            for ($i = 1; $i <= 8; $i++) {
                // Calculate the values for estate using $estateValues

                $skor_brd += round($estateValues["skor_brd$i"], 1);
                $skor_janjang += round($estateValues["skor_janjang$i"], 1);
                $weekestate["tph$i"] = $estateValues["tph$i"];
                $weekestate["jalan$i"] = $estateValues["jalan$i"];
                $weekestate["bin$i"] = $estateValues["bin$i"];
                $weekestate["karung$i"] = $estateValues["karung$i"];
                $weekestate["buah$i"] = $estateValues["buah$i"];
                $weekestate["restan$i"] = $estateValues["restan$i"];
                $weekestate["skor_brd$i"] = $estateValues["skor_brd$i"];
                $weekestate["skor_janjang$i"] = round($estateValues["skor_janjang$i"], 1);
                $weekestate["tot_brd$i"] = $estateValues["tot_brd$i"];
                $weekestate["tod_jjg$i"] = $estateValues["tod_jjg$i"];
                // $weekestate["total_score"] = round($skor_brd + $skor_janjang, 1);
            }


            $week3[] = $weekestate;
        }

        // dd($week3[4]);
        $week4 = []; // Initialize the new array
        foreach ($newSidak as $key => $value) {
            $estateValues = []; // Initialize an array to accumulate values for estate
            $est_brd = 0;
            $total_weeks = 0;
            $deviden = 0;
            $devnew = 0;
            $skor_akhir = 0;
            $nulldata = [];
            $scoreest = 0;
            $afdcount = $value['afdeling'];
            foreach ($value as $subKey => $subValue) {
                if (is_array($subValue) && isset($subValue['week4'])) {
                    $week1Data = $subValue['week4']; // Access "week1" data
                    foreach ($weeks as $keywk => $value) if ($keywk == 4) {
                        $start = $value['start'];
                        $end = $value['end'];
                    }
                    // week for afdeling 
                    if ($week1Data['all_score'] == 0 && $week1Data['v2check3'] != 0  && $week1Data['mines'] === 'ada') {
                        # code...
                        $total_score = 0;
                        $data = 'ada';
                    } else if ($week1Data['all_score'] == 0 && $week1Data['v2check3'] != 0 && $week1Data['mines'] === 'tidak') {
                        # code...
                        $total_score = 100;
                        $data = 'ada';
                    } else if ($week1Data['all_score'] == 0 && $week1Data['check_data'] == 'null') {
                        $total_score = 0;
                        $data = 'kosong';
                    } else {
                        $total_score =  round($week1Data['all_score'], 1);
                        $data = 'ada';
                    }


                    $week1Flat = [
                        'est' => $key,
                        'afd' => $subKey,
                        'total_score' => $total_score,
                        'kategori' => $week1Data['check_data'],
                        'inspek' => $data
                    ];
                    // Extract tphx values for keys 1 to 8 and flatten them
                    for ($i = 1; $i <= 8; $i++) {
                        $tphxKey = $i;
                        $tphxValue = $week1Data[$tphxKey]['tphx'];
                        $jalan = $week1Data[$tphxKey]['jalan'];
                        $bin = $week1Data[$tphxKey]['bin'];
                        $karung = $week1Data[$tphxKey]['karung'];
                        $buah = $week1Data[$tphxKey]['buah'];
                        $restan = $week1Data[$tphxKey]['restan'];
                        $skor_brd = $week1Data[$tphxKey]['skor_brd'];
                        $skor_janjang = $week1Data[$tphxKey]['skor_janjang'];
                        $tot_brd = $week1Data[$tphxKey]['tot_brd'];
                        $tod_jjg = $week1Data[$tphxKey]['tod_jjg'];

                        $week1Flat["tph$i"] = $tphxValue;
                        $week1Flat["jalan$i"] = $jalan;
                        $week1Flat["bin$i"] = $bin;
                        $week1Flat["karung$i"] = $karung;
                        $week1Flat["buah$i"] = $buah;
                        $week1Flat["restan$i"] = $restan;
                        $week1Flat["skor_brd$i"] = $skor_brd;
                        $week1Flat["skor_janjang$i"] = round($skor_janjang, 1);
                        $week1Flat["tot_brd$i"] = $tot_brd;
                        $week1Flat["tod_jjg$i"] = $tod_jjg;
                        if (!isset($estateValues["tph$i"])) {
                            $estateValues["tph$i"] = 0;
                            $estateValues["jalan$i"] = 0;
                            $estateValues["bin$i"] = 0;
                            $estateValues["karung$i"] = 0;
                            $estateValues["buah$i"] = 0;
                            $estateValues["restan$i"] = 0;
                            $estateValues["skor_brd$i"] = 0;
                            $estateValues["skor_janjang$i"] = 0;
                            $estateValues["tot_brd$i"] = 0;
                            $estateValues["tod_jjg$i"] = 0;
                        }


                        if ($dataparams === 'new') {
                            [$panen_brd, $panen_jjg] = calculatePanennew($i);
                        } else {
                            [$panen_brd, $panen_jjg] = calculatePanen($i);
                        }


                        $estateValues["tph$i"] += $tphxValue;
                        $estateValues["jalan$i"] += $jalan;
                        $estateValues["bin$i"] += $bin;
                        $estateValues["karung$i"] += $karung;
                        $estateValues["buah$i"] += $buah;
                        $estateValues["restan$i"] += $restan;
                        $estateValues["skor_brd$i"] += round(($tot_brd * $panen_brd) / 100, 1);
                        $estateValues["skor_janjang$i"] += round(($tod_jjg * $panen_jjg) / 100, 1);
                        $estateValues["tot_brd$i"] += $tot_brd;
                        $estateValues["tod_jjg$i"] += $tod_jjg;
                    }
                    $total_weeks += round($week1Data['all_score'], 1);
                    $deviden += $subValue['new_deviden'];
                    $devnew = $subValue['devidenest'];
                    // Add the flattened array to the result
                    $week4[] = $week1Flat;
                    $nulldata[] .= $week1Data['check_data'];
                    $v2check3 = $week1Data['v2check3'];
                    $scoreest += $total_score;
                }
            }

            $counts = array_count_values($nulldata);

            // Subtract the count of 'ada' from the total count
            $getnull = count($nulldata) - ($counts['ada'] ?? 0);

            // Set getnull to 0 if it's negative (in case 'ada' occurs more times than the array size)
            $getnull = max(0, $getnull);
            if ($devnew != 0 && $scoreest != 0) {
                // $skor_akhir = round($total_weeks / $deviden, 1);
                $skor_akhir = round($scoreest / $devnew, 1);
            } else {
                $skor_akhir = '-';
            }


            // week for estate 
            $weekestate = [
                'est' => $key,
                'afd' => 'EST',
                'kategori' => 'Test',
                'total_score' => $skor_akhir,
                'start' => $start,
                'end' => $end,
                'reg' => $regional,
            ];
            $skor_brd = 0;
            $skor_janjang = 0;
            for ($i = 1; $i <= 8; $i++) {
                // Calculate the values for estate using $estateValues

                $skor_brd += round($estateValues["skor_brd$i"], 1);
                $skor_janjang += round($estateValues["skor_janjang$i"], 1);
                $weekestate["tph$i"] = $estateValues["tph$i"];
                $weekestate["jalan$i"] = $estateValues["jalan$i"];
                $weekestate["bin$i"] = $estateValues["bin$i"];
                $weekestate["karung$i"] = $estateValues["karung$i"];
                $weekestate["buah$i"] = $estateValues["buah$i"];
                $weekestate["restan$i"] = $estateValues["restan$i"];
                $weekestate["skor_brd$i"] = $estateValues["skor_brd$i"];
                $weekestate["skor_janjang$i"] = round($estateValues["skor_janjang$i"], 1);
                $weekestate["tot_brd$i"] = $estateValues["tot_brd$i"];
                $weekestate["tod_jjg$i"] = $estateValues["tod_jjg$i"];
                // $weekestate["total_score"] = round($skor_brd + $skor_janjang, 1);
            }


            $week4[] = $weekestate;
        }



        $week5 = []; // Initialize the new array
        foreach ($newSidak as $key => $value) {
            $estateValues = []; // Initialize an array to accumulate values for estate
            $est_brd = 0;
            $total_weeks = 0;
            $deviden = 0;
            $skor_akhir = 0;
            $devnew = 0;
            $nulldata = [];
            $afdcount = $value['afdeling'];
            $scoreest = 0;
            foreach ($value as $subKey => $subValue) {
                if (is_array($subValue) && isset($subValue['week5'])) {
                    $week1Data = $subValue['week5']; // Access "week1" data
                    foreach ($weeks as $keywk => $value) if ($keywk == 5) {
                        $start = $value['start'];
                        $end = $value['end'];
                    }
                    // week for afdeling 
                    if ($week1Data['all_score'] == 0 && $week1Data['v2check3'] != 0  && $week1Data['mines'] === 'ada') {
                        # code...
                        $total_score = 0;
                        $data = 'ada';
                    } else if ($week1Data['all_score'] == 0 && $week1Data['v2check3'] != 0 && $week1Data['mines'] === 'tidak') {
                        # code...
                        $total_score = 100;
                        $data = 'ada';
                    } else if ($week1Data['all_score'] == 0 && $week1Data['check_data'] == 'null') {
                        $total_score = 0;
                        $data = 'kosong';
                    } else {
                        $total_score =  round($week1Data['all_score'], 1);
                        $data = 'ada';
                    }

                    $week1Flat = [
                        'est' => $key,
                        'afd' => $subKey,
                        'total_score' => $total_score,
                        'kategori' => $week1Data['check_data'],
                        'inspek' => $data
                    ];

                    // dd($subValue);

                    // Extract tphx values for keys 1 to 8 and flatten them
                    for ($i = 1; $i <= 8; $i++) {
                        $tphxKey = $i;
                        $tphxValue = $week1Data[$tphxKey]['tphx'];
                        $jalan = $week1Data[$tphxKey]['jalan'];
                        $bin = $week1Data[$tphxKey]['bin'];
                        $karung = $week1Data[$tphxKey]['karung'];
                        $buah = $week1Data[$tphxKey]['buah'];
                        $restan = $week1Data[$tphxKey]['restan'];
                        $skor_brd = $week1Data[$tphxKey]['skor_brd'];
                        $skor_janjang = $week1Data[$tphxKey]['skor_janjang'];
                        $tot_brd = $week1Data[$tphxKey]['tot_brd'];
                        $tod_jjg = $week1Data[$tphxKey]['tod_jjg'];

                        $week1Flat["tph$i"] = $tphxValue;
                        $week1Flat["jalan$i"] = $jalan;
                        $week1Flat["bin$i"] = $bin;
                        $week1Flat["karung$i"] = $karung;
                        $week1Flat["buah$i"] = $buah;
                        $week1Flat["restan$i"] = $restan;
                        $week1Flat["skor_brd$i"] = $skor_brd;
                        $week1Flat["skor_janjang$i"] = round($skor_janjang, 1);
                        $week1Flat["tot_brd$i"] = $tot_brd;
                        $week1Flat["tod_jjg$i"] = $tod_jjg;
                        if (!isset($estateValues["tph$i"])) {
                            $estateValues["tph$i"] = 0;
                            $estateValues["jalan$i"] = 0;
                            $estateValues["bin$i"] = 0;
                            $estateValues["karung$i"] = 0;
                            $estateValues["buah$i"] = 0;
                            $estateValues["restan$i"] = 0;
                            $estateValues["skor_brd$i"] = 0;
                            $estateValues["skor_janjang$i"] = 0;
                            $estateValues["tot_brd$i"] = 0;
                            $estateValues["tod_jjg$i"] = 0;
                        }


                        if ($dataparams === 'new') {
                            [$panen_brd, $panen_jjg] = calculatePanennew($i);
                        } else {
                            [$panen_brd, $panen_jjg] = calculatePanen($i);
                        }


                        $estateValues["tph$i"] += $tphxValue;
                        $estateValues["jalan$i"] += $jalan;
                        $estateValues["bin$i"] += $bin;
                        $estateValues["karung$i"] += $karung;
                        $estateValues["buah$i"] += $buah;
                        $estateValues["restan$i"] += $restan;
                        $estateValues["skor_brd$i"] += round(($tot_brd * $panen_brd) / 100, 1);
                        $estateValues["skor_janjang$i"] += round(($tod_jjg * $panen_jjg) / 100, 1);
                        $estateValues["tot_brd$i"] += $tot_brd;
                        $estateValues["tod_jjg$i"] += $tod_jjg;
                    }
                    $total_weeks += round($week1Data['all_score'], 1);
                    $deviden += $subValue['new_deviden'];
                    $devnew = $subValue['devidenest'];
                    // Add the flattened array to the result
                    $week5[] = $week1Flat;
                    $nulldata[] .= $week1Data['check_data'];
                    $v2check3 = $week1Data['v2check3'];
                    $scoreest += $total_score;
                }
            }
            $counts = array_count_values($nulldata);

            // Subtract the count of 'ada' from the total count
            $getnull = count($nulldata) - ($counts['ada'] ?? 0);

            // Set getnull to 0 if it's negative (in case 'ada' occurs more times than the array size)
            $getnull = max(0, $getnull);
            if ($devnew != 0 && $scoreest != 0) {
                // $skor_akhir = round($total_weeks / $deviden, 1);
                $skor_akhir = round($scoreest / $devnew, 1);
            } else {
                $skor_akhir = '-';
            }
            // week for estate 
            $weekestate = [
                'est' => $key,
                'afd' => 'EST',
                'kategori' => 'Test',
                'total_score' => $skor_akhir,
                'start' => $start,
                'end' => $end,
                'reg' => $regional,
            ];
            $skor_brd = 0;
            $skor_janjang = 0;
            for ($i = 1; $i <= 8; $i++) {
                // Calculate the values for estate using $estateValues

                $skor_brd += round($estateValues["skor_brd$i"], 1);
                $skor_janjang += round($estateValues["skor_janjang$i"], 1);
                $weekestate["tph$i"] = $estateValues["tph$i"];
                $weekestate["jalan$i"] = $estateValues["jalan$i"];
                $weekestate["bin$i"] = $estateValues["bin$i"];
                $weekestate["karung$i"] = $estateValues["karung$i"];
                $weekestate["buah$i"] = $estateValues["buah$i"];
                $weekestate["restan$i"] = $estateValues["restan$i"];
                $weekestate["skor_brd$i"] = $estateValues["skor_brd$i"];
                $weekestate["skor_janjang$i"] = round($estateValues["skor_janjang$i"], 1);
                $weekestate["tot_brd$i"] = $estateValues["tot_brd$i"];
                $weekestate["tod_jjg$i"] = $estateValues["tod_jjg$i"];
                // $weekestate["total_score"] = round($skor_brd + $skor_janjang, 1);
            }


            $week5[] = $weekestate;
        }


        // dd($week1);
        $arrView = array();
        $arrView['week1'] = $week1;
        $arrView['week2'] = $week2;
        $arrView['week3'] = $week3;
        $arrView['week4'] = $week4;
        $arrView['week5'] = $week5;
        $arrView['reg'] = $regional;
        $arrView['tanggal'] = $tanggal;

        return view('Pdf.sidaktphexcel', ['data' => $arrView]);
    }

    public function excelsidaktph(Request $request)
    {


        $regional = $request->input('getregionalexcel');
        $date = $request->input('getdateexcel');

        // $date = $this->date;
        // $regional = $this->regional;

        $newparamsdate = '2024-03-01';

        $tanggalDateTime = new DateTime($date);
        // dd($tanggalDateTime);
        $newparamsdateDateTime = new DateTime($newparamsdate);
        // dd($newparamsdateDateTime);
        // Start of Selection
        $collect_estate = Regional::where('id', $regional)
            ->with('wilayah.estate')
            ->get()
            ->flatMap(function ($regional) {
                return $regional->wilayah->flatMap(function ($wilayah) {
                    return $wilayah->estate->pluck('est');
                });
            });
        // dd($collect_estate);
        if ($tanggalDateTime >= $newparamsdateDateTime) {
            $dataparams = 'new';
        } else {
            $dataparams = 'old';
        }


        $ancakFA = DB::connection('mysql2')
            ->table('sidak_tph')
            ->whereIn('sidak_tph.est', $collect_estate)
            ->select(
                "sidak_tph.*",
                DB::raw('DATE_FORMAT(sidak_tph.datetime, "%Y-%m-%d") as tanggal'),
                DB::raw('DATE_FORMAT(sidak_tph.datetime, "%M") as bulan'),
                DB::raw("
        CASE 
            WHEN status = '' THEN 1
            WHEN status = '0' THEN 1
            WHEN LOCATE('>H+', status) > 0 THEN '8'
            WHEN LOCATE('H+', status) > 0 THEN 
                CASE 
                    WHEN SUBSTRING_INDEX(SUBSTRING_INDEX(status, 'H+', -1), ' ', 1) > 8 THEN '8'
                    ELSE SUBSTRING_INDEX(SUBSTRING_INDEX(status, 'H+', -1), ' ', 1)
                END
            WHEN status REGEXP '^[0-9]+$' AND status > 8 THEN '8'
            WHEN LENGTH(status) > 1 AND status NOT LIKE '%H+%' AND status NOT LIKE '%>H+%' AND LOCATE(',', status) > 0 THEN SUBSTRING_INDEX(status, ',', 1)
            ELSE status
        END AS statuspanen")
            )
            ->where('sidak_tph.datetime', 'like', '%' . $date . '%')
            ->orderBy('status', 'asc')
            ->get();

        $ancakFA = $ancakFA->groupBy(['est', 'afd', 'statuspanen', 'tanggal', 'blok']);
        $ancakFA = json_decode($ancakFA, true);



        // dd($ancakFA);

        $dateString = $date;
        $dateParts = date_parse($dateString);
        $year = $dateParts['year'];
        $month = $dateParts['month'];

        $year = $year; // Replace with the desired year
        $month = $month;   // Replace with the desired month (September in this example)

        if ($regional == 3) {

            $weeks = [];
            $firstDayOfMonth = strtotime("$year-$month-01");
            $lastDayOfMonth = strtotime(date('Y-m-t', $firstDayOfMonth));

            $weekNumber = 1;

            // Find the first Saturday of the month or the last Saturday of the previous month
            $firstSaturday = strtotime("last Saturday", $firstDayOfMonth);

            // Set the start date to the first Saturday
            $startDate = $firstSaturday;

            while ($startDate <= $lastDayOfMonth) {
                $endDate = strtotime("next Friday", $startDate);
                if ($endDate > $lastDayOfMonth) {
                    $endDate = $lastDayOfMonth;
                }

                $weeks[$weekNumber] = [
                    'start' => date('Y-m-d', $startDate),
                    'end' => date('Y-m-d', $endDate),
                ];

                // Update start date to the next Saturday
                $startDate = strtotime("next Saturday", $endDate);

                $weekNumber++;
            }
        } else {
            $weeks = [];
            $firstDayOfMonth = strtotime("$year-$month-01");
            $lastDayOfMonth = strtotime(date('Y-m-t', $firstDayOfMonth));

            $weekNumber = 1;
            $startDate = $firstDayOfMonth;

            while ($startDate <= $lastDayOfMonth) {
                $endDate = strtotime("next Sunday", $startDate);
                if ($endDate > $lastDayOfMonth) {
                    $endDate = $lastDayOfMonth;
                }

                $weeks[$weekNumber] = [
                    'start' => date('Y-m-d', $startDate),
                    'end' => date('Y-m-d', $endDate),
                ];

                $nextMonday = strtotime("next Monday", $endDate);

                // Check if the next Monday is still within the current month.
                if (date('m', $nextMonday) == $month) {
                    $startDate = $nextMonday;
                } else {
                    // If the next Monday is in the next month, break the loop.
                    break;
                }

                $weekNumber++;
            }
        }


        // dd($weeks);

        $result = [];

        // Iterate through the original array
        foreach ($ancakFA as $mainKey => $mainValue) {
            $result[$mainKey] = [];

            foreach ($mainValue as $subKey => $subValue) {
                $result[$mainKey][$subKey] = [];

                foreach ($subValue as $dateKey => $dateValue) {
                    // Remove 'H+' prefix if it exists
                    $numericIndex = is_numeric($dateKey) ? $dateKey : (strpos($dateKey, 'H+') === 0 ? substr($dateKey, 2) : $dateKey);

                    if (!isset($result[$mainKey][$subKey][$numericIndex])) {
                        $result[$mainKey][$subKey][$numericIndex] = [];
                    }

                    foreach ($dateValue as $statusKey => $statusValue) {
                        // Handle 'H+' prefix in status
                        $statusIndex = is_numeric($statusKey) ? $statusKey : (strpos($statusKey, 'H+') === 0 ? substr($statusKey, 2) : $statusKey);

                        if (!isset($result[$mainKey][$subKey][$numericIndex][$statusIndex])) {
                            $result[$mainKey][$subKey][$numericIndex][$statusIndex] = [];
                        }

                        foreach ($statusValue as $blokKey => $blokValue) {
                            $result[$mainKey][$subKey][$numericIndex][$statusIndex][$blokKey] = $blokValue;
                        }
                    }
                }
            }
        }

        // result by statis week 
        $newResult = [];

        foreach ($result as $key => $value) {
            $newResult[$key] = [];

            foreach ($value as $estKey => $est) {
                $newResult[$key][$estKey] = [];

                foreach ($est as $statusKey => $status) {
                    $newResult[$key][$estKey][$statusKey] = [];

                    foreach ($weeks as $weekKey => $week) {
                        $newStatus = [];

                        foreach ($status as $date => $data) {
                            if (strtotime($date) >= strtotime($week["start"]) && strtotime($date) <= strtotime($week["end"])) {
                                $newStatus[$date] = $data;
                            }
                        }

                        if (!empty($newStatus)) {
                            $newResult[$key][$estKey][$statusKey]["week" . ($weekKey + 1)] = $newStatus;
                        }
                    }
                }
            }
        }

        // result by week status 
        $WeekStatus = [];

        foreach ($result as $key => $value) {
            $WeekStatus[$key] = [];

            foreach ($value as $estKey => $est) {
                $WeekStatus[$key][$estKey] = [];

                foreach ($weeks as $weekKey => $week) {
                    $WeekStatus[$key][$estKey]["week" . ($weekKey + 0)] = [];

                    foreach ($est as $statusKey => $status) {
                        $newStatus = [];

                        foreach ($status as $date => $data) {
                            if (strtotime($date) >= strtotime($week["start"]) && strtotime($date) <= strtotime($week["end"])) {
                                $newStatus[$date] = $data;
                            }
                        }

                        if (!empty($newStatus)) {
                            $WeekStatus[$key][$estKey]["week" . ($weekKey + 0)][$statusKey] = $newStatus;
                        }
                    }
                }
            }
        }

        // dd($WeekStatus);



        $qrafd = DB::connection('mysql2')->table('afdeling')
            ->select(
                'afdeling.id',
                'afdeling.nama',
                'estate.est'
            ) //buat mengambil data di estate db dan willayah db
            ->join('estate', 'estate.id', '=', 'afdeling.estate') //kemudian di join untuk mengambil est perwilayah
            ->get();
        $qrafd = json_decode($qrafd, true);
        $queryEstereg = DB::connection('mysql2')->table('estate')
            ->select('estate.*')
            // ->whereNotIn('estate.est', ['PLASMA', 'CWS1', 'SRS'])
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->where('estate.emp', '!=', 1)
            ->where('wil.regional', $regional)
            ->get();
        $queryEstereg = json_decode($queryEstereg, true);

        // dd($queryEstereg);
        $defaultNew = array();

        foreach ($queryEstereg as $est) {
            foreach ($qrafd as $afd) {
                if ($est['est'] == $afd['est']) {
                    $defaultNew[$est['est']][$afd['nama']] = 0;
                }
            }
        }



        foreach ($defaultNew as $key => $estValue) {
            foreach ($estValue as $monthKey => $monthValue) {
                foreach ($newResult as $dataKey => $dataValue) {

                    if ($dataKey == $key) {
                        foreach ($dataValue as $dataEstKey => $dataEstValue) {

                            if ($dataEstKey == $monthKey) {
                                $defaultNew[$key][$monthKey] = $dataEstValue;
                            }
                        }
                    }
                }
            }
        }

        // dd($queryEstereg, $qrafd);

        $defaultWeek = array();
        foreach ($queryEstereg as $est) {
            foreach ($qrafd as $afd) {
                if ($est['est'] == $afd['est']) {
                    if (in_array($est['est'], ['LDE', 'SRE'])) {
                        $defaultWeek[$est['est']][$afd['est']] = 0;
                    } else {
                        $defaultWeek[$est['est']][$afd['nama']] = 0;
                    }
                }
            }
        }

        // dd($defaultWeek);
        foreach ($defaultWeek as $key => $estValue) {
            foreach ($estValue as $monthKey => $monthValue) {
                foreach ($WeekStatus as $dataKey => $dataValue) {

                    if ($dataKey == $key) {
                        foreach ($dataValue as $dataEstKey => $dataEstValue) {

                            if ($dataEstKey == $monthKey) {
                                $defaultWeek[$key][$monthKey] = $dataEstValue;
                            }
                        }
                    }
                }
            }
        }


        $newDefaultWeek = [];

        foreach ($defaultWeek as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $key1 => $value1) {
                    if (is_array($value1)) {
                        foreach ($value1 as $subKey => $subValue) {
                            if (is_array($subValue)) {
                                // Check if both key 0 and key 1 exist
                                $hasKeyZero = isset($subValue[0]);
                                $hasKeyOne = isset($subValue[1]);

                                // Merge key 0 into key 1
                                if ($hasKeyZero && $hasKeyOne) {
                                    $subValue[1] = array_merge_recursive((array)$subValue[1], (array)$subValue[0]);
                                    unset($subValue[0]);
                                } elseif ($hasKeyZero && !$hasKeyOne) {
                                    // Create key 1 and merge key 0 into it
                                    $subValue[1] = $subValue[0];
                                    unset($subValue[0]);
                                }

                                // Check if keys 1 through 7 don't exist, add them with a default value of 0
                                for ($i = 1; $i <= 7; $i++) {
                                    if (!isset($subValue[$i])) {
                                        $subValue[$i] = 0;
                                    }
                                }

                                // Ensure key 8 exists, and if not, create it with a default value of an empty array
                                if (!isset($subValue[8])) {
                                    $subValue[8] = 0;
                                }

                                // Check if keys higher than 8 exist, merge them into index 8
                                for ($i = 9; $i <= 100; $i++) {
                                    if (isset($subValue[$i])) {
                                        $subValue[8] = array_merge_recursive((array)$subValue[8], (array)$subValue[$i]);
                                        unset($subValue[$i]);
                                    }
                                }
                            }
                            $newDefaultWeek[$key][$key1][$subKey] = $subValue;
                        }
                    } else {
                        // Check if $value1 is equal to 0 and add "week1" to "week5" keys
                        if ($value1 === 0) {
                            $newDefaultWeek[$key][$key1] = [];
                            for ($i = 1; $i <= 5; $i++) {
                                $weekKey = "week" . $i;
                                $newDefaultWeek[$key][$key1][$weekKey] = [];
                                for ($j = 1; $j <= 8; $j++) {
                                    $newDefaultWeek[$key][$key1][$weekKey][$j] = 0;
                                }
                            }
                        } else {
                            $newDefaultWeek[$key][$key1] = $value1;
                        }
                    }
                }
            } else {
                $newDefaultWeek[$key] = $value;
            }
        }
        // dd($newDefaultWeek['Plasma1']['WIL-III']);
        // dd($newDefaultWeek);

        function removeZeroFromDatetime2xx(&$array)
        {
            foreach ($array as $key => &$value) {
                if (is_array($value)) {
                    foreach ($value as $key1 => &$value2) {
                        if (is_array($value2)) {
                            foreach ($value2 as $key2 => &$value3) {
                                if (is_array($value3)) {
                                    foreach ($value3 as $key3 => &$value4) if (is_array($value4)) {
                                        foreach ($value4 as $key4 => $value5) {
                                            if ($key4 === 0 && $value5 === 0) {
                                                unset($value4[$key4]); // Unset the key 0 => 0 within the current nested array
                                            }
                                            removeZeroFromDatetime2xx($value4);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        removeZeroFromDatetime2xx($newDefaultWeek);

        function filterEmptyWeeksxx(&$array)
        {
            foreach ($array as $key => &$value) {
                if (is_array($value)) {
                    filterEmptyWeeksxx($value); // Recursively check nested arrays
                    if (empty($value) && $key !== 'week') {
                        unset($array[$key]);
                    }
                }
            }
        }

        // dd($defaultWeek);
        // Call the function on your array
        filterEmptyWeeksxx($defaultWeek);




        // dd($defaultWeek);
        $dividen = [];

        foreach ($defaultWeek as $key => $value) {
            foreach ($value as $key1 => $value1) if (is_array($value1)) {
                foreach ($value1 as $key2 => $value2) if (is_array($value2)) {

                    $dividenn = count($value1);
                }
                $dividen[$key][$key1]['dividen'] = $dividenn;
            } else {
                $dividen[$key][$key1]['dividen'] = 0;
            }
        }
        // dd($newDefaultWeek['BKE']['OA']);

        // dd($newDefaultWeek['Plasma1']['WIL-III']);
        $newSidak = array();
        $asisten_qc = DB::connection('mysql2')
            ->Table('asisten_qc')
            ->get();
        $asisten_qc = json_decode($asisten_qc, true);
        // dd($newDefaultWeek['SLE']['OA']);
        $devest = 0;
        foreach ($newDefaultWeek as $key => $value) {
            $dividen_afd = 0;
            $total_skoreest = 0;
            $tot_estAFd = 0;
            $new_dvdAfd = 0;
            $new_dvdAfdest = 0;
            $total_estkors = 0;
            $total_skoreafd = 0;

            $deviden = 0;
            $devest = count($value);
            // dd($devest);
            // dd($value);

            foreach ($value as $key1 => $value2)  if (is_array($value2)) {

                $tot_afdscore = 0;
                $totskor_brd1 = 0;
                $totskor_janjang1 = 0;
                $total_skoreest = 0;
                $newpembagi1 = 0;
                foreach ($value2 as $key2 => $value3) {


                    $total_brondolan = 0;
                    $total_janjang = 0;
                    $tod_brd = 0;
                    $tod_jjg = 0;
                    $totskor_brd = 0;
                    $totskor_janjang = 0;
                    $tot_brdxm = 0;
                    $tod_janjangxm = 0;
                    $v2check3 = 0;

                    foreach ($value3 as $key3 => $value4) if (is_array($value4)) {
                        $tph1 = 0;
                        $jalan1 = 0;
                        $bin1 = 0;
                        $karung1 = 0;
                        $buah1 = 0;
                        $restan1 = 0;
                        $v2check2 = 0;

                        foreach ($value4 as $key4 => $value5) if (is_array($value5)) {
                            $tph = 0;
                            $jalan = 0;
                            $bin = 0;
                            $karung = 0;
                            $buah = 0;
                            $restan = 0;
                            $v2check = count($value5);
                            foreach ($value5 as $key5 => $value6) {
                                $sum_bt_tph = 0;
                                $sum_bt_jalan = 0;
                                $sum_bt_bin = 0;
                                $sum_jum_karung = 0;
                                $sum_buah_tinggal = 0;
                                $sum_restan_unreported = 0;
                                $sum_all_restan_unreported = 0;

                                foreach ($value6 as $key6 => $value7) {
                                    // dd($value7);
                                    // dd($value7);
                                    $sum_bt_tph += $value7['bt_tph'];
                                    $sum_bt_jalan += $value7['bt_jalan'];
                                    $sum_bt_bin += $value7['bt_bin'];
                                    $sum_jum_karung += $value7['jum_karung'];


                                    $sum_buah_tinggal += $value7['buah_tinggal'];
                                    $sum_restan_unreported += $value7['restan_unreported'];
                                }
                                $newSidak[$key][$key1][$key2][$key3][$key4][$key5]['tph'] = $sum_bt_tph;
                                $newSidak[$key][$key1][$key2][$key3][$key4][$key5]['jalan'] = $sum_bt_jalan;
                                $newSidak[$key][$key1][$key2][$key3][$key4][$key5]['bin'] = $sum_bt_bin;
                                $newSidak[$key][$key1][$key2][$key3][$key4][$key5]['karung'] = $sum_jum_karung;

                                $newSidak[$key][$key1][$key2][$key3][$key4][$key5]['buah'] = $sum_buah_tinggal;
                                $newSidak[$key][$key1][$key2][$key3][$key4][$key5]['restan'] = $sum_restan_unreported;


                                $tph += $sum_bt_tph;
                                $jalan += $sum_bt_jalan;
                                $bin += $sum_bt_bin;
                                $karung += $sum_jum_karung;
                                $buah += $sum_buah_tinggal;
                                $restan += $sum_restan_unreported;
                            }

                            $newSidak[$key][$key1][$key2][$key3][$key4]['tph'] = $tph;
                            $newSidak[$key][$key1][$key2][$key3][$key4]['jalan'] = $jalan;
                            $newSidak[$key][$key1][$key2][$key3][$key4]['bin'] = $bin;
                            $newSidak[$key][$key1][$key2][$key3][$key4]['karung'] = $karung;

                            $newSidak[$key][$key1][$key2][$key3][$key4]['buah'] = $buah;
                            $newSidak[$key][$key1][$key2][$key3][$key4]['restan'] = $restan;
                            $newSidak[$key][$key1][$key2][$key3][$key4]['v2check'] = $v2check;

                            $tph1 += $tph;
                            $jalan1 += $jalan;
                            $bin1 += $bin;
                            $karung1 += $karung;
                            $buah1 += $buah;
                            $restan1 += $restan;
                            $v2check2 += $v2check;
                        }
                        // dd($key3);
                        $status_panen = $key3;

                        if ($dataparams === 'new') {
                            [$panen_brd, $panen_jjg] = calculatePanennew($status_panen);
                        } else {
                            [$panen_brd, $panen_jjg] = calculatePanen($status_panen);
                        }



                        // untuk brondolan gabungan dari bt-tph,bt-jalan,bt-bin,jum-karung 
                        $total_brondolan =  round(($tph1 + $jalan1 + $bin1 + $karung1) * $panen_brd / 100, 1);
                        $total_janjang =  round(($buah1 + $restan1) * $panen_jjg / 100, 1);
                        $tod_brd = $tph1 + $jalan1 + $bin1 + $karung1;
                        $tod_jjg = $buah1 + $restan1;
                        $newSidak[$key][$key1][$key2][$key3]['tphx'] = $tph1;
                        $newSidak[$key][$key1][$key2][$key3]['jalan'] = $jalan1;
                        $newSidak[$key][$key1][$key2][$key3]['bin'] = $bin1;
                        $newSidak[$key][$key1][$key2][$key3]['karung'] = $karung1;
                        $newSidak[$key][$key1][$key2][$key3]['tot_brd'] = $tod_brd;

                        $newSidak[$key][$key1][$key2][$key3]['buah'] = $buah1;
                        $newSidak[$key][$key1][$key2][$key3]['restan'] = $restan1;
                        $newSidak[$key][$key1][$key2][$key3]['skor_brd'] = $total_brondolan;
                        $newSidak[$key][$key1][$key2][$key3]['skor_janjang'] = $total_janjang;
                        $newSidak[$key][$key1][$key2][$key3]['tod_jjg'] = $tod_jjg;
                        $newSidak[$key][$key1][$key2][$key3]['v2check2'] = $v2check2;

                        $totskor_brd += $total_brondolan;
                        $totskor_janjang += $total_janjang;
                        $tot_brdxm += $tod_brd;
                        $tod_janjangxm += $tod_jjg;
                        $v2check3 += $v2check2;
                    } else {
                        $newSidak[$key][$key1][$key2][$key3]['tphx'] = 0;
                        $newSidak[$key][$key1][$key2][$key3]['jalan'] = 0;
                        $newSidak[$key][$key1][$key2][$key3]['bin'] = 0;
                        $newSidak[$key][$key1][$key2][$key3]['karung'] = 0;

                        $newSidak[$key][$key1][$key2][$key3]['buah'] = 0;
                        $newSidak[$key][$key1][$key2][$key3]['restan'] = 0;

                        $newSidak[$key][$key1][$key2][$key3]['skor_brd'] = 0;
                        $newSidak[$key][$key1][$key2][$key3]['skor_janjang'] = 0;
                        $newSidak[$key][$key1][$key2][$key3]['tot_brd'] = 0;
                        $newSidak[$key][$key1][$key2][$key3]['tod_jjg'] = 0;
                        $newSidak[$key][$key1][$key2][$key3]['v2check2'] = 0;
                    }


                    $total_estkors = $totskor_brd + $totskor_janjang;
                    if ($total_estkors != 0) {

                        $checkscore = 100 - ($total_estkors);

                        if ($checkscore < 0) {
                            $newscore = 0;
                            $newSidak[$key][$key1][$key2]['mines'] = 'ada';
                        } else {
                            $newscore = $checkscore;
                            $newSidak[$key][$key1][$key2]['mines'] = 'tidak';
                        }

                        $newSidak[$key][$key1][$key2]['all_score'] = $newscore;
                        $newSidak[$key][$key1][$key2]['check_data'] = 'ada';

                        $total_skoreafd = $newscore;
                        $newpembagi = 1;
                    } else if ($v2check3 != 0) {
                        $checkscore = 100 - ($total_estkors);

                        if ($checkscore < 0) {
                            $newscore = 0;
                            $newSidak[$key][$key1][$key2]['mines'] = 'ada';
                        } else {
                            $newscore = $checkscore;
                            $newSidak[$key][$key1][$key2]['mines'] = 'tidak';
                        }
                        $newSidak[$key][$key1][$key2]['all_score'] = 100 - ($total_estkors);
                        $newSidak[$key][$key1][$key2]['check_data'] = 'ada';

                        $total_skoreafd = $newscore;

                        $newpembagi = 1;
                    } else {
                        $newSidak[$key][$key1][$key2]['all_score'] = 0;
                        $newSidak[$key][$key1][$key2]['check_data'] = 'null';
                        $total_skoreafd = 0;
                        $newpembagi = 0;
                    }
                    // $newSidak[$key][$key1][$key2]['all_score'] = 100 - ($total_estkors);
                    $newSidak[$key][$key1][$key2]['total_brd'] = $tot_brdxm;
                    $newSidak[$key][$key1][$key2]['total_brdSkor'] = $totskor_brd;
                    $newSidak[$key][$key1][$key2]['total_janjang'] = $tod_janjangxm;
                    $newSidak[$key][$key1][$key2]['total_janjangSkor'] = $totskor_janjang;
                    $newSidak[$key][$key1][$key2]['total_skor'] = $total_skoreafd;
                    $newSidak[$key][$key1][$key2]['janjang_brd'] = $totskor_brd + $totskor_janjang;
                    $newSidak[$key][$key1][$key2]['v2check3'] = $v2check3;
                    $newSidak[$key][$key1][$key2]['newpembagi'] = $newpembagi;

                    $totskor_brd1 += $totskor_brd;
                    $totskor_janjang1 += $totskor_janjang;
                    $total_skoreest += $total_skoreafd;
                    $newpembagi1 += $newpembagi;
                }



                $namaGM = '-';
                foreach ($asisten_qc as $asisten) {

                    // dd($asisten);
                    if ($asisten['est'] == $key && $asisten['afd'] == $key1) {
                        $namaGM = $asisten['nama'];
                        break;
                    }
                }

                $deviden = count($value2);


                if ($newpembagi1 != 0) {
                    $tot_afdscore = round($total_skoreest / $newpembagi1, 1);
                } else {
                    $tot_afdscore = 0;  # code...
                }
                if ($newpembagi1 != 0) {
                    $estbagi = 1;
                } else {
                    $estbagi = 0;
                }

                // $newSidak[$key][$key1]['deviden'] = $deviden;
                $newSidak[$key][$key1]['total_score'] = $tot_afdscore;
                $newSidak[$key][$key1]['total_brd'] = $totskor_brd1;
                $newSidak[$key][$key1]['total_janjang'] = $totskor_janjang1;
                $newSidak[$key][$key1]['new_deviden'] = 1;
                $newSidak[$key][$key1]['asisten'] = $namaGM;
                $newSidak[$key][$key1]['total_skor'] = $total_skoreest;
                $newSidak[$key][$key1]['est'] = $key;
                $newSidak[$key][$key1]['afd'] = $key1;
                $newSidak[$key][$key1]['devidenest'] = $devest;

                $tot_estAFd += $tot_afdscore;
                $new_dvdAfdest += $estbagi;
            } else {
                $namaGM = '-';
                foreach ($asisten_qc as $asisten) {

                    // dd($asisten);
                    if ($asisten['est'] == $key && $asisten['afd'] == $key1) {
                        $namaGM = $asisten['nama'];
                        break;
                    }
                }
                $newSidak[$key][$key1]['deviden'] = 0;
                $newSidak[$key][$key1]['total_score'] = 0;
                $newSidak[$key][$key1]['total_brd'] = 0;
                $newSidak[$key][$key1]['new_deviden'] = 0;
                $newSidak[$key][$key1]['total_janjang'] = 0;
                $newSidak[$key][$key1]['asisten'] = $namaGM;
            }
            if ($new_dvdAfdest != 0) {
                $total_skoreest = round($tot_estAFd / $new_dvdAfdest, 1);
            } else {
                $total_skoreest = 0;
            }

            // dd($value);

            $namaGM = '-';
            foreach ($asisten_qc as $asisten) {
                if ($asisten['est'] == $key && $asisten['afd'] == 'EM') {
                    $namaGM = $asisten['nama'];
                    break;
                }
            }
            if ($new_dvdAfd != 0) {
                $newSidak[$key]['deviden'] = 1;
            } else {
                $newSidak[$key]['deviden'] = 0;
            }

            $newSidak[$key]['total_skorest'] = $tot_estAFd;
            $newSidak[$key]['score_estate'] = $total_skoreest;
            $newSidak[$key]['asisten'] = $namaGM;
            $newSidak[$key]['estate'] = $key;
            $newSidak[$key]['afd'] = 'GM';
            $newSidak[$key]['dividen'] = $new_dvdAfdest;
            $newSidak[$key]['afdeling'] = $devest;
        }


        $week1 = []; // Initialize the new array
        foreach ($newSidak as $key => $value) {
            $estateValues = []; // Initialize an array to accumulate values for estate
            $est_brd = 0;
            $total_weeks = 0;
            $deviden = 0;
            $devnew = 0;
            $skor_akhir = 0;
            $nulldata = [];
            $afdcount = $value['afdeling'] ?? 0;
            $scoreest = 0;
            foreach ($value as $subKey => $subValue) {
                if (is_array($subValue) && isset($subValue['week1'])) {
                    $week1Data = $subValue['week1']; // Access "week1" data

                    // dd($week1Data);
                    foreach ($weeks as $keywk => $value) if ($keywk == 1) {
                        $start = $value['start'];
                        $end = $value['end'];
                    }
                    // week for afdeling 
                    // dd($week1Data);
                    if ($week1Data['all_score'] == 0 && $week1Data['v2check3'] != 0  && $week1Data['mines'] === 'ada') {
                        # code...
                        $total_score = 0;
                        $data = 'ada';
                    } else if ($week1Data['all_score'] == 0 && $week1Data['v2check3'] != 0 && $week1Data['mines'] === 'tidak') {
                        # code...
                        $total_score = 100;
                        $data = 'ada';
                    } else if ($week1Data['all_score'] == 0 && $week1Data['check_data'] == 'null') {
                        $total_score = 0;
                        $data = 'kosong';
                    } else {
                        $total_score =  round($week1Data['all_score'], 1);
                        $data = 'ada';
                    }

                    $week1Flat = [
                        'est' => $key,
                        'afd' => $subKey,
                        'total_score' => $total_score,
                        'kategori' => $week1Data['check_data'],
                        'inspek' => $data
                    ];

                    // Extract tphx values for keys 1 to 8 and flatten them
                    for ($i = 1; $i <= 8; $i++) {
                        $tphxKey = $i;
                        $tphxValue = $week1Data[$tphxKey]['tphx'];
                        $jalan = $week1Data[$tphxKey]['jalan'];
                        $bin = $week1Data[$tphxKey]['bin'];
                        $karung = $week1Data[$tphxKey]['karung'];
                        $buah = $week1Data[$tphxKey]['buah'];
                        $restan = $week1Data[$tphxKey]['restan'];
                        $skor_brd = $week1Data[$tphxKey]['skor_brd'];
                        $skor_janjang = $week1Data[$tphxKey]['skor_janjang'];
                        $tot_brd = $week1Data[$tphxKey]['tot_brd'];
                        $tod_jjg = $week1Data[$tphxKey]['tod_jjg'];

                        $week1Flat["tph$i"] = $tphxValue;
                        $week1Flat["jalan$i"] = $jalan;
                        $week1Flat["bin$i"] = $bin;
                        $week1Flat["karung$i"] = $karung;
                        $week1Flat["buah$i"] = $buah;
                        $week1Flat["restan$i"] = $restan;
                        $week1Flat["skor_brd$i"] = $skor_brd;
                        $week1Flat["skor_janjang$i"] = round($skor_janjang, 1);
                        $week1Flat["tot_brd$i"] = $tot_brd;
                        $week1Flat["tod_jjg$i"] = $tod_jjg;
                        if (!isset($estateValues["tph$i"])) {
                            $estateValues["tph$i"] = 0;
                            $estateValues["jalan$i"] = 0;
                            $estateValues["bin$i"] = 0;
                            $estateValues["karung$i"] = 0;
                            $estateValues["buah$i"] = 0;
                            $estateValues["restan$i"] = 0;
                            $estateValues["skor_brd$i"] = 0;
                            $estateValues["skor_janjang$i"] = 0;
                            $estateValues["tot_brd$i"] = 0;
                            $estateValues["tod_jjg$i"] = 0;
                        }
                        if ($dataparams === 'new') {
                            [$panen_brd, $panen_jjg] = calculatePanennew($i);
                        } else {
                            [$panen_brd, $panen_jjg] = calculatePanen($i);
                        }



                        // [$panen_brd, $panen_jjg] = calculatePanen($i);

                        $estateValues["tph$i"] += $tphxValue;
                        $estateValues["jalan$i"] += $jalan;
                        $estateValues["bin$i"] += $bin;
                        $estateValues["karung$i"] += $karung;
                        $estateValues["buah$i"] += $buah;
                        $estateValues["restan$i"] += $restan;
                        $estateValues["skor_brd$i"] += round(($tot_brd * $panen_brd) / 100, 1);
                        $estateValues["skor_janjang$i"] += round(($tod_jjg * $panen_jjg) / 100, 1);
                        $estateValues["tot_brd$i"] += $tot_brd;
                        $estateValues["tod_jjg$i"] += $tod_jjg;
                    }
                    $total_weeks += round($week1Data['all_score'], 2);
                    $deviden += $subValue['new_deviden'];
                    $devnew = $subValue['devidenest'];

                    // Add the flattened array to the result
                    $week1[] = $week1Flat;
                    $nulldata[] .= $week1Data['check_data'];
                    $v2check3 = $week1Data['v2check3'];
                    $scoreest += $total_score;
                }
            }


            $counts = array_count_values($nulldata);

            // Subtract the count of 'ada' from the total count
            $getnull = count($nulldata) - ($counts['ada'] ?? 0);

            // Set getnull to 0 if it's negative (in case 'ada' occurs more times than the array size)
            $getnull = max(0, $getnull);
            if ($devnew != 0 && $scoreest != 0) {
                // $skor_akhir = round($total_weeks / $deviden, 1);
                $skor_akhir = round($scoreest / $devnew, 2);
            } else {
                $skor_akhir = '-';
            }

            // week for estate 
            $weekestate = [
                'est' => $key,
                'afd' => 'EST',
                'kategori' => 'Test',
                'total_score' => $skor_akhir,
                'skore' => $scoreest,
                'pembagi' => $devnew,
                'start' => $start,
                'end' => $end,
                'reg' => $regional,

            ];
            $skor_brd = 0;
            $skor_janjang = 0;
            for ($i = 1; $i <= 8; $i++) {
                // Calculate the values for estate using $estateValues

                $skor_brd += round($estateValues["skor_brd$i"], 1);
                $skor_janjang += round($estateValues["skor_janjang$i"], 1);
                $weekestate["tph$i"] = $estateValues["tph$i"];
                $weekestate["jalan$i"] = $estateValues["jalan$i"];
                $weekestate["bin$i"] = $estateValues["bin$i"];
                $weekestate["karung$i"] = $estateValues["karung$i"];
                $weekestate["buah$i"] = $estateValues["buah$i"];
                $weekestate["restan$i"] = $estateValues["restan$i"];
                $weekestate["skor_brd$i"] = $estateValues["skor_brd$i"];
                $weekestate["skor_janjang$i"] = round($estateValues["skor_janjang$i"], 1);
                $weekestate["tot_brd$i"] = $estateValues["tot_brd$i"];
                $weekestate["tod_jjg$i"] = $estateValues["tod_jjg$i"];
                // $weekestate["total_score"] = round($skor_brd + $skor_janjang, 1);
            }


            $week1[] = $weekestate;
        }
        // dd($week1[57], $week1[58], $week1[59], $week1[60], $week1[61], $week1[62]);
        // dd($newSidak['KNE']);
        // dd($week1);

        $week2 = []; // Initialize the new array
        foreach ($newSidak as $key => $value) {
            $estateValues = []; // Initialize an array to accumulate values for estate
            $est_brd = 0;
            $total_weeks = 0;
            $deviden = 0;
            $skor_akhir = 0;
            $devnew = 0;
            $nulldata = [];
            $afdcount = $value['afdeling'];
            $scoreest = 0;
            foreach ($value as $subKey => $subValue) {
                if (is_array($subValue) && isset($subValue['week2'])) {
                    $week1Data = $subValue['week2']; // Access "week1" data
                    foreach ($weeks as $keywk => $value) if ($keywk == 2) {
                        $start = $value['start'];
                        $end = $value['end'];
                    }

                    if ($week1Data['all_score'] == 0 && $week1Data['v2check3'] != 0  && $week1Data['mines'] === 'ada') {
                        # code...
                        $total_score = 0;
                        $data = 'ada';
                    } else if ($week1Data['all_score'] == 0 && $week1Data['v2check3'] != 0 && $week1Data['mines'] === 'tidak') {
                        # code...
                        $total_score = 100;
                        $data = 'ada';
                    } else if ($week1Data['all_score'] == 0 && $week1Data['check_data'] == 'null') {
                        $total_score = 0;
                        $data = 'kosong';
                    } else {
                        $total_score =  round($week1Data['all_score'], 1);
                        $data = 'ada';
                    }


                    $week1Flat = [
                        'est' => $key,
                        'afd' => $subKey,
                        'total_score' => $total_score,
                        'kategori' => $week1Data['check_data'],
                        'inspek' => $data
                    ];

                    // Extract tphx values for keys 1 to 8 and flatten them
                    for ($i = 1; $i <= 8; $i++) {
                        $tphxKey = $i;
                        $tphxValue = $week1Data[$tphxKey]['tphx'];
                        $jalan = $week1Data[$tphxKey]['jalan'];
                        $bin = $week1Data[$tphxKey]['bin'];
                        $karung = $week1Data[$tphxKey]['karung'];
                        $buah = $week1Data[$tphxKey]['buah'];
                        $restan = $week1Data[$tphxKey]['restan'];
                        $skor_brd = $week1Data[$tphxKey]['skor_brd'];
                        $skor_janjang = $week1Data[$tphxKey]['skor_janjang'];
                        $tot_brd = $week1Data[$tphxKey]['tot_brd'];
                        $tod_jjg = $week1Data[$tphxKey]['tod_jjg'];

                        $week1Flat["tph$i"] = $tphxValue;
                        $week1Flat["jalan$i"] = $jalan;
                        $week1Flat["bin$i"] = $bin;
                        $week1Flat["karung$i"] = $karung;
                        $week1Flat["buah$i"] = $buah;
                        $week1Flat["restan$i"] = $restan;
                        $week1Flat["skor_brd$i"] = $skor_brd;
                        $week1Flat["skor_janjang$i"] = round($skor_janjang, 1);
                        $week1Flat["tot_brd$i"] = $tot_brd;
                        $week1Flat["tod_jjg$i"] = $tod_jjg;
                        if (!isset($estateValues["tph$i"])) {
                            $estateValues["tph$i"] = 0;
                            $estateValues["jalan$i"] = 0;
                            $estateValues["bin$i"] = 0;
                            $estateValues["karung$i"] = 0;
                            $estateValues["buah$i"] = 0;
                            $estateValues["restan$i"] = 0;
                            $estateValues["skor_brd$i"] = 0;
                            $estateValues["skor_janjang$i"] = 0;
                            $estateValues["tot_brd$i"] = 0;
                            $estateValues["tod_jjg$i"] = 0;
                        }


                        if ($dataparams === 'new') {
                            [$panen_brd, $panen_jjg] = calculatePanennew($i);
                        } else {
                            [$panen_brd, $panen_jjg] = calculatePanen($i);
                        }


                        $estateValues["tph$i"] += $tphxValue;
                        $estateValues["jalan$i"] += $jalan;
                        $estateValues["bin$i"] += $bin;
                        $estateValues["karung$i"] += $karung;
                        $estateValues["buah$i"] += $buah;
                        $estateValues["restan$i"] += $restan;
                        $estateValues["skor_brd$i"] += round(($tot_brd * $panen_brd) / 100, 1);
                        $estateValues["skor_janjang$i"] += round(($tod_jjg * $panen_jjg) / 100, 1);
                        $estateValues["tot_brd$i"] += $tot_brd;
                        $estateValues["tod_jjg$i"] += $tod_jjg;
                    }
                    $total_weeks += round($week1Data['all_score'], 1);
                    $deviden += $subValue['new_deviden'];
                    $devnew = $subValue['devidenest'];
                    // Add the flattened array to the result
                    $week2[] = $week1Flat;
                    $nulldata[] .= $week1Data['check_data'];
                    $v2check3 = $week1Data['v2check3'];
                    $scoreest += $total_score;
                }
            }

            $counts = array_count_values($nulldata);

            // Subtract the count of 'ada' from the total count
            $getnull = count($nulldata) - ($counts['ada'] ?? 0);

            // Set getnull to 0 if it's negative (in case 'ada' occurs more times than the array size)
            $getnull = max(0, $getnull);
            if ($devnew != 0 && $scoreest != 0) {
                // $skor_akhir = round($total_weeks / $deviden, 1);
                $skor_akhir = round($scoreest / $devnew, 1);
            } else {
                $skor_akhir = '-';
            }


            // week for estate 
            $weekestate = [
                'est' => $key,
                'afd' => 'EST',
                'kategori' => 'Test',
                'total_score' => $skor_akhir,
                'start' => $start,
                'end' => $end,
                'reg' => $regional,
            ];
            $skor_brd = 0;
            $skor_janjang = 0;
            for ($i = 1; $i <= 8; $i++) {
                // Calculate the values for estate using $estateValues

                $skor_brd += round($estateValues["skor_brd$i"], 1);
                $skor_janjang += round($estateValues["skor_janjang$i"], 1);
                $weekestate["tph$i"] = $estateValues["tph$i"];
                $weekestate["jalan$i"] = $estateValues["jalan$i"];
                $weekestate["bin$i"] = $estateValues["bin$i"];
                $weekestate["karung$i"] = $estateValues["karung$i"];
                $weekestate["buah$i"] = $estateValues["buah$i"];
                $weekestate["restan$i"] = $estateValues["restan$i"];
                $weekestate["skor_brd$i"] = $estateValues["skor_brd$i"];
                $weekestate["skor_janjang$i"] = round($estateValues["skor_janjang$i"], 1);
                $weekestate["tot_brd$i"] = $estateValues["tot_brd$i"];
                $weekestate["tod_jjg$i"] = $estateValues["tod_jjg$i"];
                // $weekestate["total_score"] = round($skor_brd + $skor_janjang, 1);
            }


            $week2[] = $weekestate;
        }

        // dd($week2[15]);

        $week3 = []; // Initialize the new array
        foreach ($newSidak as $key => $value) {
            $estateValues = []; // Initialize an array to accumulate values for estate
            $est_brd = 0;
            $total_weeks = 0;
            $deviden = 0;
            $skor_akhir = 0;
            $devnew = 0;
            $nulldata = [];
            $afdcount = $value['afdeling'];
            $scoreest = 0;
            foreach ($value as $subKey => $subValue) {
                if (is_array($subValue) && isset($subValue['week3'])) {
                    $week1Data = $subValue['week3']; // Access "week1" data

                    // dd($week1Data);
                    foreach ($weeks as $keywk => $value) if ($keywk == 3) {
                        $start = $value['start'];
                        $end = $value['end'];
                    }
                    // week for afdeling 
                    if ($week1Data['all_score'] == 0 && $week1Data['v2check3'] != 0  && $week1Data['mines'] === 'ada') {
                        # code...
                        $total_score = 0;
                        $data = 'ada';
                    } else if ($week1Data['all_score'] == 0 && $week1Data['v2check3'] != 0 && $week1Data['mines'] === 'tidak') {
                        # code...
                        $total_score = 100;
                        $data = 'ada';
                    } else if ($week1Data['all_score'] == 0 && $week1Data['check_data'] == 'null') {
                        $total_score = 0;
                        $data = 'kosong';
                    } else {
                        $total_score =  round($week1Data['all_score'], 1);
                        $data = 'ada';
                    }


                    $week1Flat = [
                        'est' => $key,
                        'afd' => $subKey,
                        'total_score' => $total_score,
                        'kategori' => $week1Data['check_data'],
                        'inspek' => $data
                    ];
                    // Extract tphx values for keys 1 to 8 and flatten them
                    for ($i = 1; $i <= 8; $i++) {
                        $tphxKey = $i;
                        $tphxValue = $week1Data[$tphxKey]['tphx'];
                        $jalan = $week1Data[$tphxKey]['jalan'];
                        $bin = $week1Data[$tphxKey]['bin'];
                        $karung = $week1Data[$tphxKey]['karung'];
                        $buah = $week1Data[$tphxKey]['buah'];
                        $restan = $week1Data[$tphxKey]['restan'];
                        $skor_brd = $week1Data[$tphxKey]['skor_brd'];
                        $skor_janjang = $week1Data[$tphxKey]['skor_janjang'];
                        $tot_brd = $week1Data[$tphxKey]['tot_brd'];
                        $tod_jjg = $week1Data[$tphxKey]['tod_jjg'];

                        $week1Flat["tph$i"] = $tphxValue;
                        $week1Flat["jalan$i"] = $jalan;
                        $week1Flat["bin$i"] = $bin;
                        $week1Flat["karung$i"] = $karung;
                        $week1Flat["buah$i"] = $buah;
                        $week1Flat["restan$i"] = $restan;
                        $week1Flat["skor_brd$i"] = $skor_brd;
                        $week1Flat["skor_janjang$i"] = round($skor_janjang, 1);
                        $week1Flat["tot_brd$i"] = $tot_brd;
                        $week1Flat["tod_jjg$i"] = $tod_jjg;
                        if (!isset($estateValues["tph$i"])) {
                            $estateValues["tph$i"] = 0;
                            $estateValues["jalan$i"] = 0;
                            $estateValues["bin$i"] = 0;
                            $estateValues["karung$i"] = 0;
                            $estateValues["buah$i"] = 0;
                            $estateValues["restan$i"] = 0;
                            $estateValues["skor_brd$i"] = 0;
                            $estateValues["skor_janjang$i"] = 0;
                            $estateValues["tot_brd$i"] = 0;
                            $estateValues["tod_jjg$i"] = 0;
                        }


                        if ($dataparams === 'new') {
                            [$panen_brd, $panen_jjg] = calculatePanennew($i);
                        } else {
                            [$panen_brd, $panen_jjg] = calculatePanen($i);
                        }


                        $estateValues["tph$i"] += $tphxValue;
                        $estateValues["jalan$i"] += $jalan;
                        $estateValues["bin$i"] += $bin;
                        $estateValues["karung$i"] += $karung;
                        $estateValues["buah$i"] += $buah;
                        $estateValues["restan$i"] += $restan;
                        $estateValues["skor_brd$i"] += round(($tot_brd * $panen_brd) / 100, 1);
                        $estateValues["skor_janjang$i"] += round(($tod_jjg * $panen_jjg) / 100, 1);
                        $estateValues["tot_brd$i"] += $tot_brd;
                        $estateValues["tod_jjg$i"] += $tod_jjg;
                    }
                    $total_weeks += round($week1Data['all_score'], 1);
                    $deviden += $subValue['new_deviden'];
                    $devnew = $subValue['devidenest'];
                    // Add the flattened array to the result
                    $week3[] = $week1Flat;
                    $nulldata[] .= $week1Data['check_data'];
                    $v2check3 = $week1Data['v2check3'];
                    $scoreest += $total_score;
                }
            }
            $counts = array_count_values($nulldata);

            // Subtract the count of 'ada' from the total count
            $getnull = count($nulldata) - ($counts['ada'] ?? 0);

            // Set getnull to 0 if it's negative (in case 'ada' occurs more times than the array size)
            $getnull = max(0, $getnull);
            if ($devnew != 0 && $scoreest != 0) {
                // $skor_akhir = round($total_weeks / $deviden, 1);
                $skor_akhir = round($scoreest / $devnew, 1);
            } else {
                $skor_akhir = '-';
            }
            // week for estate 
            $weekestate = [
                'est' => $key,
                'afd' => 'EST',
                'kategori' => $getnull,
                'total_score' => $skor_akhir,
                'start' => $start,
                'end' => $end,
                'reg' => $regional,
            ];
            $skor_brd = 0;
            $skor_janjang = 0;
            for ($i = 1; $i <= 8; $i++) {
                // Calculate the values for estate using $estateValues

                $skor_brd += round($estateValues["skor_brd$i"], 1);
                $skor_janjang += round($estateValues["skor_janjang$i"], 1);
                $weekestate["tph$i"] = $estateValues["tph$i"];
                $weekestate["jalan$i"] = $estateValues["jalan$i"];
                $weekestate["bin$i"] = $estateValues["bin$i"];
                $weekestate["karung$i"] = $estateValues["karung$i"];
                $weekestate["buah$i"] = $estateValues["buah$i"];
                $weekestate["restan$i"] = $estateValues["restan$i"];
                $weekestate["skor_brd$i"] = $estateValues["skor_brd$i"];
                $weekestate["skor_janjang$i"] = round($estateValues["skor_janjang$i"], 1);
                $weekestate["tot_brd$i"] = $estateValues["tot_brd$i"];
                $weekestate["tod_jjg$i"] = $estateValues["tod_jjg$i"];
                // $weekestate["total_score"] = round($skor_brd + $skor_janjang, 1);
            }


            $week3[] = $weekestate;
        }

        // dd($week3[4]);
        $week4 = []; // Initialize the new array
        foreach ($newSidak as $key => $value) {
            $estateValues = []; // Initialize an array to accumulate values for estate
            $est_brd = 0;
            $total_weeks = 0;
            $deviden = 0;
            $devnew = 0;
            $skor_akhir = 0;
            $nulldata = [];
            $scoreest = 0;
            $afdcount = $value['afdeling'];
            foreach ($value as $subKey => $subValue) {
                if (is_array($subValue) && isset($subValue['week4'])) {
                    $week1Data = $subValue['week4']; // Access "week1" data
                    foreach ($weeks as $keywk => $value) if ($keywk == 4) {
                        $start = $value['start'];
                        $end = $value['end'];
                    }
                    // week for afdeling 
                    if ($week1Data['all_score'] == 0 && $week1Data['v2check3'] != 0  && $week1Data['mines'] === 'ada') {
                        # code...
                        $total_score = 0;
                        $data = 'ada';
                    } else if ($week1Data['all_score'] == 0 && $week1Data['v2check3'] != 0 && $week1Data['mines'] === 'tidak') {
                        # code...
                        $total_score = 100;
                        $data = 'ada';
                    } else if ($week1Data['all_score'] == 0 && $week1Data['check_data'] == 'null') {
                        $total_score = 0;
                        $data = 'kosong';
                    } else {
                        $total_score =  round($week1Data['all_score'], 1);
                        $data = 'ada';
                    }


                    $week1Flat = [
                        'est' => $key,
                        'afd' => $subKey,
                        'total_score' => $total_score,
                        'kategori' => $week1Data['check_data'],
                        'inspek' => $data
                    ];
                    // Extract tphx values for keys 1 to 8 and flatten them
                    for ($i = 1; $i <= 8; $i++) {
                        $tphxKey = $i;
                        $tphxValue = $week1Data[$tphxKey]['tphx'];
                        $jalan = $week1Data[$tphxKey]['jalan'];
                        $bin = $week1Data[$tphxKey]['bin'];
                        $karung = $week1Data[$tphxKey]['karung'];
                        $buah = $week1Data[$tphxKey]['buah'];
                        $restan = $week1Data[$tphxKey]['restan'];
                        $skor_brd = $week1Data[$tphxKey]['skor_brd'];
                        $skor_janjang = $week1Data[$tphxKey]['skor_janjang'];
                        $tot_brd = $week1Data[$tphxKey]['tot_brd'];
                        $tod_jjg = $week1Data[$tphxKey]['tod_jjg'];

                        $week1Flat["tph$i"] = $tphxValue;
                        $week1Flat["jalan$i"] = $jalan;
                        $week1Flat["bin$i"] = $bin;
                        $week1Flat["karung$i"] = $karung;
                        $week1Flat["buah$i"] = $buah;
                        $week1Flat["restan$i"] = $restan;
                        $week1Flat["skor_brd$i"] = $skor_brd;
                        $week1Flat["skor_janjang$i"] = round($skor_janjang, 1);
                        $week1Flat["tot_brd$i"] = $tot_brd;
                        $week1Flat["tod_jjg$i"] = $tod_jjg;
                        if (!isset($estateValues["tph$i"])) {
                            $estateValues["tph$i"] = 0;
                            $estateValues["jalan$i"] = 0;
                            $estateValues["bin$i"] = 0;
                            $estateValues["karung$i"] = 0;
                            $estateValues["buah$i"] = 0;
                            $estateValues["restan$i"] = 0;
                            $estateValues["skor_brd$i"] = 0;
                            $estateValues["skor_janjang$i"] = 0;
                            $estateValues["tot_brd$i"] = 0;
                            $estateValues["tod_jjg$i"] = 0;
                        }


                        if ($dataparams === 'new') {
                            [$panen_brd, $panen_jjg] = calculatePanennew($i);
                        } else {
                            [$panen_brd, $panen_jjg] = calculatePanen($i);
                        }


                        $estateValues["tph$i"] += $tphxValue;
                        $estateValues["jalan$i"] += $jalan;
                        $estateValues["bin$i"] += $bin;
                        $estateValues["karung$i"] += $karung;
                        $estateValues["buah$i"] += $buah;
                        $estateValues["restan$i"] += $restan;
                        $estateValues["skor_brd$i"] += round(($tot_brd * $panen_brd) / 100, 1);
                        $estateValues["skor_janjang$i"] += round(($tod_jjg * $panen_jjg) / 100, 1);
                        $estateValues["tot_brd$i"] += $tot_brd;
                        $estateValues["tod_jjg$i"] += $tod_jjg;
                    }
                    $total_weeks += round($week1Data['all_score'], 1);
                    $deviden += $subValue['new_deviden'];
                    $devnew = $subValue['devidenest'];
                    // Add the flattened array to the result
                    $week4[] = $week1Flat;
                    $nulldata[] .= $week1Data['check_data'];
                    $v2check3 = $week1Data['v2check3'];
                    $scoreest += $total_score;
                }
            }

            $counts = array_count_values($nulldata);

            // Subtract the count of 'ada' from the total count
            $getnull = count($nulldata) - ($counts['ada'] ?? 0);

            // Set getnull to 0 if it's negative (in case 'ada' occurs more times than the array size)
            $getnull = max(0, $getnull);
            if ($devnew != 0 && $scoreest != 0) {
                // $skor_akhir = round($total_weeks / $deviden, 1);
                $skor_akhir = round($scoreest / $devnew, 1);
            } else {
                $skor_akhir = '-';
            }


            // week for estate 
            $weekestate = [
                'est' => $key,
                'afd' => 'EST',
                'kategori' => 'Test',
                'total_score' => $skor_akhir,
                'start' => $start,
                'end' => $end,
                'reg' => $regional,
            ];
            $skor_brd = 0;
            $skor_janjang = 0;
            for ($i = 1; $i <= 8; $i++) {
                // Calculate the values for estate using $estateValues

                $skor_brd += round($estateValues["skor_brd$i"], 1);
                $skor_janjang += round($estateValues["skor_janjang$i"], 1);
                $weekestate["tph$i"] = $estateValues["tph$i"];
                $weekestate["jalan$i"] = $estateValues["jalan$i"];
                $weekestate["bin$i"] = $estateValues["bin$i"];
                $weekestate["karung$i"] = $estateValues["karung$i"];
                $weekestate["buah$i"] = $estateValues["buah$i"];
                $weekestate["restan$i"] = $estateValues["restan$i"];
                $weekestate["skor_brd$i"] = $estateValues["skor_brd$i"];
                $weekestate["skor_janjang$i"] = round($estateValues["skor_janjang$i"], 1);
                $weekestate["tot_brd$i"] = $estateValues["tot_brd$i"];
                $weekestate["tod_jjg$i"] = $estateValues["tod_jjg$i"];
                // $weekestate["total_score"] = round($skor_brd + $skor_janjang, 1);
            }


            $week4[] = $weekestate;
        }



        $week5 = []; // Initialize the new array
        foreach ($newSidak as $key => $value) {
            $estateValues = []; // Initialize an array to accumulate values for estate
            $est_brd = 0;
            $total_weeks = 0;
            $deviden = 0;
            $skor_akhir = 0;
            $devnew = 0;
            $nulldata = [];
            $afdcount = $value['afdeling'];
            $scoreest = 0;
            foreach ($value as $subKey => $subValue) {
                if (is_array($subValue) && isset($subValue['week5'])) {
                    $week1Data = $subValue['week5']; // Access "week1" data
                    foreach ($weeks as $keywk => $value) if ($keywk == 5) {
                        $start = $value['start'];
                        $end = $value['end'];
                    }
                    // week for afdeling 
                    if ($week1Data['all_score'] == 0 && $week1Data['v2check3'] != 0  && $week1Data['mines'] === 'ada') {
                        # code...
                        $total_score = 0;
                        $data = 'ada';
                    } else if ($week1Data['all_score'] == 0 && $week1Data['v2check3'] != 0 && $week1Data['mines'] === 'tidak') {
                        # code...
                        $total_score = 100;
                        $data = 'ada';
                    } else if ($week1Data['all_score'] == 0 && $week1Data['check_data'] == 'null') {
                        $total_score = 0;
                        $data = 'kosong';
                    } else {
                        $total_score =  round($week1Data['all_score'], 1);
                        $data = 'ada';
                    }

                    $week1Flat = [
                        'est' => $key,
                        'afd' => $subKey,
                        'total_score' => $total_score,
                        'kategori' => $week1Data['check_data'],
                        'inspek' => $data
                    ];

                    // dd($subValue);

                    // Extract tphx values for keys 1 to 8 and flatten them
                    for ($i = 1; $i <= 8; $i++) {
                        $tphxKey = $i;
                        $tphxValue = $week1Data[$tphxKey]['tphx'];
                        $jalan = $week1Data[$tphxKey]['jalan'];
                        $bin = $week1Data[$tphxKey]['bin'];
                        $karung = $week1Data[$tphxKey]['karung'];
                        $buah = $week1Data[$tphxKey]['buah'];
                        $restan = $week1Data[$tphxKey]['restan'];
                        $skor_brd = $week1Data[$tphxKey]['skor_brd'];
                        $skor_janjang = $week1Data[$tphxKey]['skor_janjang'];
                        $tot_brd = $week1Data[$tphxKey]['tot_brd'];
                        $tod_jjg = $week1Data[$tphxKey]['tod_jjg'];

                        $week1Flat["tph$i"] = $tphxValue;
                        $week1Flat["jalan$i"] = $jalan;
                        $week1Flat["bin$i"] = $bin;
                        $week1Flat["karung$i"] = $karung;
                        $week1Flat["buah$i"] = $buah;
                        $week1Flat["restan$i"] = $restan;
                        $week1Flat["skor_brd$i"] = $skor_brd;
                        $week1Flat["skor_janjang$i"] = round($skor_janjang, 1);
                        $week1Flat["tot_brd$i"] = $tot_brd;
                        $week1Flat["tod_jjg$i"] = $tod_jjg;
                        if (!isset($estateValues["tph$i"])) {
                            $estateValues["tph$i"] = 0;
                            $estateValues["jalan$i"] = 0;
                            $estateValues["bin$i"] = 0;
                            $estateValues["karung$i"] = 0;
                            $estateValues["buah$i"] = 0;
                            $estateValues["restan$i"] = 0;
                            $estateValues["skor_brd$i"] = 0;
                            $estateValues["skor_janjang$i"] = 0;
                            $estateValues["tot_brd$i"] = 0;
                            $estateValues["tod_jjg$i"] = 0;
                        }


                        if ($dataparams === 'new') {
                            [$panen_brd, $panen_jjg] = calculatePanennew($i);
                        } else {
                            [$panen_brd, $panen_jjg] = calculatePanen($i);
                        }


                        $estateValues["tph$i"] += $tphxValue;
                        $estateValues["jalan$i"] += $jalan;
                        $estateValues["bin$i"] += $bin;
                        $estateValues["karung$i"] += $karung;
                        $estateValues["buah$i"] += $buah;
                        $estateValues["restan$i"] += $restan;
                        $estateValues["skor_brd$i"] += round(($tot_brd * $panen_brd) / 100, 1);
                        $estateValues["skor_janjang$i"] += round(($tod_jjg * $panen_jjg) / 100, 1);
                        $estateValues["tot_brd$i"] += $tot_brd;
                        $estateValues["tod_jjg$i"] += $tod_jjg;
                    }
                    $total_weeks += round($week1Data['all_score'], 1);
                    $deviden += $subValue['new_deviden'];
                    $devnew = $subValue['devidenest'];
                    // Add the flattened array to the result
                    $week5[] = $week1Flat;
                    $nulldata[] .= $week1Data['check_data'];
                    $v2check3 = $week1Data['v2check3'];
                    $scoreest += $total_score;
                }
            }
            $counts = array_count_values($nulldata);

            // Subtract the count of 'ada' from the total count
            $getnull = count($nulldata) - ($counts['ada'] ?? 0);

            // Set getnull to 0 if it's negative (in case 'ada' occurs more times than the array size)
            $getnull = max(0, $getnull);
            if ($devnew != 0 && $scoreest != 0) {
                // $skor_akhir = round($total_weeks / $deviden, 1);
                $skor_akhir = round($scoreest / $devnew, 1);
            } else {
                $skor_akhir = '-';
            }
            // week for estate 
            $weekestate = [
                'est' => $key,
                'afd' => 'EST',
                'kategori' => 'Test',
                'total_score' => $skor_akhir,
                'start' => $start,
                'end' => $end,
                'reg' => $regional,
            ];
            $skor_brd = 0;
            $skor_janjang = 0;
            for ($i = 1; $i <= 8; $i++) {
                // Calculate the values for estate using $estateValues

                $skor_brd += round($estateValues["skor_brd$i"], 1);
                $skor_janjang += round($estateValues["skor_janjang$i"], 1);
                $weekestate["tph$i"] = $estateValues["tph$i"];
                $weekestate["jalan$i"] = $estateValues["jalan$i"];
                $weekestate["bin$i"] = $estateValues["bin$i"];
                $weekestate["karung$i"] = $estateValues["karung$i"];
                $weekestate["buah$i"] = $estateValues["buah$i"];
                $weekestate["restan$i"] = $estateValues["restan$i"];
                $weekestate["skor_brd$i"] = $estateValues["skor_brd$i"];
                $weekestate["skor_janjang$i"] = round($estateValues["skor_janjang$i"], 1);
                $weekestate["tot_brd$i"] = $estateValues["tot_brd$i"];
                $weekestate["tod_jjg$i"] = $estateValues["tod_jjg$i"];
                // $weekestate["total_score"] = round($skor_brd + $skor_janjang, 1);
            }


            $week5[] = $weekestate;
        }

        $rekap_perbulan = getRekapPerbulanSidaktph($date, $collect_estate, $newparamsdate);
        // dd($rekap_perbulan, $week1);

        $dataweek = [
            'week1' => $week1,
            'week2' => $week2,
            'week3' => $week3,
            'week4' => $week4,
            'week5' => $week5,
            'rekap' => $rekap_perbulan,
        ];


        // dd($dataweek);
        // dd($regional, $tanggal);
        return Excel::download(new ExportSidaktph($dataweek), 'Excel Sidak TPH Regonal-' . $regional . '-' . 'Bulan-' . $date . '.xlsx');
    }
}
