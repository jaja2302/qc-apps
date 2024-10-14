<?php

namespace App\Http\Controllers;

use App\Exports\Gradingperhari;
use App\Exports\Gradingregional;
use App\Models\Estate;
use App\Models\Gradingmill;
use App\Models\Regional;
use App\Models\Wilayah;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class GradingController extends Controller
{

    public function index(Request $request)
    {
        $regionalId = $request->input('regional_id');
        $reg = Regional::query()->where('id', '!=', 5)->with('Mill')->get();
        // dd($reg);
        $estates = collect(); // Initialize an empty collection

        if ($regionalId) {
            $wilayahs = Wilayah::where('regional', $regionalId)
                ->with(['estate' => function ($query) {
                    $query->where('emp', '!=', 1);
                }])
                ->get();

            foreach ($wilayahs as $wilayah) {
                foreach ($wilayah->estate as $estate) {
                    $estates->push($estate);
                }
            }
        }
        return view('Grading.index', [
            'regional' => $reg,
            'estates' => $estates,
            'selectedRegionalId' => $regionalId,
        ]);
    }

    public function rekapregional(Request $request)
    {
        $reg = $request->input('reg');
        $bulan = $request->input('bulan');
        $type = 'perbulan';
        $result = getdatamill($bulan, $reg, $type);
        echo json_encode($result);
        exit();
    }
    public function gradingrekapmill(Request $request)
    {
        $reg = $request->input('reg');
        $bulan = $request->input('bulan');
        $type = 'perbulan';
        $result = getdatamill($bulan, $reg, $type);
        echo json_encode($result);
        exit();
    }
    public function getrekapperhari(Request $request)
    {
        $estate = $request->input('estate');
        $afd = $request->input('afd');
        $date = $request->input('date');
        $result = getdatamildetail($estate, $afd, $date);
        echo json_encode($result);
        exit();
    }
    public function getrekapperhari_dashboard(Request $request)
    {
        $reg = $request->input('reg');
        $mill = $request->input('mill_id');
        $bulan = $request->input('bulan');
        // dd($reg, $mill);

        $result = rekap_estate_mill_perbulan_perhari($bulan, $reg, $mill);
        // dd($result);
        echo json_encode($result['result']);
        exit();
    }

    public function getrekapperafdeling(Request $request)
    {
        $reg = $request->input('reg');
        $bulan = $request->input('bulan');
        $type = 'perafdeling';
        $result = getdatamill($bulan, $reg, $type);
        echo json_encode($result);
        exit();
    }

    public function detailgradingmill($est, $afd, $bulan)
    {

        $get_date = DB::connection('mysql2')->table('grading_mill')
            ->select(DB::raw('DATE_FORMAT(datetime, "%Y-%m-%d") as formatted_date'))
            ->where('estate', $est)
            ->where('afdeling', $afd)
            ->where('datetime', 'LIKE', '%' . $bulan . '%')
            ->pluck('formatted_date')
            ->unique()
            ->toArray();
        // dd($get_date);


        $arrView = array();
        $arrView['get_date'] = $get_date;
        $arrView['est'] = $est;
        $arrView['afd'] = $afd;
        json_encode($arrView);

        return view('Grading.detailgrading', $arrView);
    }

    public function exportpdfgrading(Request $request)
    {
        $est = $request->input('estBA');
        $afd = $request->input('afdBA');
        $date = $request->input('datepdf');

        $data = Gradingmill::query()
            ->where('estate', $est)
            ->where('afdeling', $afd)
            ->where('datetime', 'LIKE', '%' . $date . '%')
            ->get();

        $result = [];
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                // Remove square brackets and split the string into an array
                $cleaned_string = str_replace(['[', ']'], '', $value['foto_temuan']);
                $foto = explode(',', $cleaned_string);

                // Trim spaces from each element in the array
                $foto = array_map('trim', $foto);
                $jumlah_janjang_grading = $value['jjg_grading'];
                $jumlah_janjang_spb = $value['jjg_spb'];
                $kelas_a = $value['kelas_a'];
                $kelas_b = $value['kelas_b'];
                $kelas_c = $value['kelas_c'];
                $persentage_kelas_a = ($kelas_a / $jumlah_janjang_grading) * 100;
                $persentage_kelas_b = ($kelas_b / $jumlah_janjang_grading) * 100;
                $persentage_kelas_c = ($kelas_c / $jumlah_janjang_grading) * 100;
                $brondol_0 = $value['unripe_tanpa_brondol'];
                $brondol_less = $value['unripe_kurang_brondol'];
                $vcut = $value['vcut'];
                $not_vcut = $jumlah_janjang_grading - $vcut;
                $overripe = $value['overripe'];
                $empty_bunch = $value['empty'];
                $rotten_bunch = $value['rotten'];
                $abnormal = $value['abn_partheno'] + $value['abn_hard'] + $value['abn_sakit'] +  $value['abn_kastrasi'];

                $loose_fruit_kg = round(($value['loose_fruit'] / $value['tonase']) * 100, 2);
                $dirt_kg = round(($value['dirt']  / $value['tonase']) * 100, 2);
                $unripe = $brondol_0 + $brondol_less;
                $ripeness = $value['jjg_grading'] - ($value['overripe'] + $value['empty'] + $value['rotten'] + $abnormal + $unripe);

                // Calculate percentages
                $percentage_ripeness = ($ripeness / $jumlah_janjang_grading) * 100;
                $percentage_unripe = ($unripe / $jumlah_janjang_grading) * 100;
                $percentage_brondol_0 = ($brondol_0 / $jumlah_janjang_grading) * 100;
                $percentage_brondol_less = ($brondol_less / $jumlah_janjang_grading) * 100;
                $percentage_overripe = ($overripe / $jumlah_janjang_grading) * 100;
                $percentage_empty_bunch = ($empty_bunch / $jumlah_janjang_grading) * 100;
                $percentage_vcut = ($vcut / $jumlah_janjang_grading) * 100;
                $percentage_not_vcut = ($not_vcut / $jumlah_janjang_grading) * 100;
                $percentage_rotten_bunch = ($rotten_bunch / $jumlah_janjang_grading) * 100;
                $percentage_abnormal = ($abnormal / $jumlah_janjang_grading) * 100;
                $percentage_tangkai_panjang = ($value['tangkai_panjang'] / $jumlah_janjang_grading) * 100;
                // Calculate selisih janjang and percentage
                $jumlah_selisih_janjang = $jumlah_janjang_grading - $jumlah_janjang_spb;
                $percentage_selisih_janjang = ($jumlah_selisih_janjang / $jumlah_janjang_spb) * 100;
                $no_pemanen = json_decode($value['no_pemanen'], true);

                $tanpaBrondol = [];
                $datakurang_brondol = [];
                // dd($no_pemanen);
                foreach ($no_pemanen as $keys1 => $values1) {
                    $get_pemanen = isset($values1['a']) ? $values1['a'] : (isset($values1['noPemanen']) ? $values1['noPemanen'] : null);
                    $get_kurangBrondol = isset($values1['b']) ? $values1['b'] : (isset($values1['kurangBrondol']) ? $values1['kurangBrondol'] : 0);
                    $get_tanpaBrondol = isset($values1['c']) ? $values1['c'] : (isset($values1['tanpaBrondol']) ? $values1['tanpaBrondol'] : 0);

                    if ($get_kurangBrondol != 0) {
                        $datakurang_brondol['kurangBrondol_list'][] = [
                            'no_pemanen' => ($get_pemanen == 999) ? 'X' : $get_pemanen,
                            'kurangBrondol' => $get_kurangBrondol,
                        ];
                    }

                    if ($get_tanpaBrondol != 0) {
                        $tanpaBrondol['tanpaBrondol_list'][] = [
                            'no_pemanen' => ($get_pemanen == 999) ? 'X' : $get_pemanen,
                            'tanpaBrondol' => $get_tanpaBrondol,
                        ];
                    }
                }

                // dd($tanpaBrondol);

                if ($datakurang_brondol == []) {
                    $resultKurangBrondol = '-';
                } else {
                    $resultKurangBrondol = implode(',', array_map(function ($item) {
                        return $item['no_pemanen'] . '(' . $item['kurangBrondol'] . ')';
                    }, $datakurang_brondol['kurangBrondol_list']));
                }

                if ($tanpaBrondol == []) {
                    $resultTanpaBrondol = '-';
                } else {
                    $resultTanpaBrondol = implode(',', array_map(function ($item) {
                        return $item['no_pemanen'] . '(' . $item['tanpaBrondol'] . ')';
                    }, $tanpaBrondol['tanpaBrondol_list']));
                }

                Carbon::setLocale('id');

                // Original datetime string
                $datetime = $value['datetime'];
                $getadds = explode(';', $value['app_version']);

                // Create a Carbon instance
                $date = Carbon::parse($datetime);
                $appvers = $getadds[0] ?? '-';
                $os_version = $getadds[1] ?? '-';
                $phone_version = $getadds[2] ?? '-';
                // Format the date
                $dayOfWeek = $date->isoFormat('dddd'); // Get the day of the week in Indonesian
                $formattedDate = $dayOfWeek . ', ' . $date->format('d-m-Y');
                $list_blok = str_replace(['[', ']'], '', $value['blok']);

                // dd($value['blok']);
                $dataakhir = [
                    'id' => $value['id'],
                    'estate' => $value['estate'],
                    'afdeling' => $value['afdeling'],
                    'mill' => $value['mill'],
                    'Tanggal' => $formattedDate,
                    'list_blok' => $list_blok,
                    'waktu_grading' => $date->format('H:i:s'),
                    'jjg_grading' => $value['jjg_grading'],
                    'no_plat' => $value['no_plat'],
                    'supir' => $value['driver'],
                    'jjg_spb' => $value['jjg_spb'],
                    'tonase' => $value['tonase'],
                    'abn_partheno' => $value['abn_partheno'],
                    'abn_partheno_percen' => round(($value['abn_partheno'] / $jumlah_janjang_grading) * 100, 2),
                    'abn_hard' => $value['abn_hard'],
                    'abn_hard_percen' => round(($value['abn_hard'] / $jumlah_janjang_grading) * 100, 2),
                    'abn_sakit' =>  $value['abn_sakit'],
                    'abn_sakit_percen' => round(($value['abn_sakit'] / $jumlah_janjang_grading) * 100, 2),
                    'abn_kastrasi' =>  $value['abn_kastrasi'],
                    'abn_kastrasi_percen' => round(($value['abn_kastrasi'] / $jumlah_janjang_grading) * 100, 2),
                    'bjr' => round($value['tonase'] / $value['jjg_spb'], 2),
                    'jjg_selisih' => $jumlah_selisih_janjang,
                    'persentase_selisih' => round($percentage_selisih_janjang, 2),
                    'Ripeness' => $ripeness,
                    'percentase_ripenes' => round($percentage_ripeness, 2),
                    'Unripe' => $unripe,
                    'persenstase_unripe' => round($percentage_unripe, 2),
                    'nol_brondol' => $brondol_0,
                    'persentase_nol_brondol' => round($percentage_brondol_0, 2),
                    'kurang_brondol' => $brondol_less,
                    'persentase_brondol' => round($percentage_brondol_less, 2),
                    'nomor_pemanen' => 'a',
                    'unripe_tanda_x' => 'a',
                    'Overripe' => $overripe,
                    'persentase_overripe' => round($percentage_overripe, 2),
                    'empty_bunch' => $empty_bunch,
                    'persentase_empty_bunch' => round($percentage_empty_bunch, 2),
                    'rotten_bunch' => $rotten_bunch,
                    'persentase_rotten_bunce' => round($percentage_rotten_bunch, 2),
                    'Abnormal' => $abnormal,
                    'persentase_abnormal' =>    round($percentage_abnormal, 2),
                    'loose_fruit' => $value['loose_fruit'],
                    'persentase_lose_fruit' => $loose_fruit_kg,
                    'Dirt' => $value['dirt'],
                    'persentase' => $dirt_kg,
                    'foto' => $foto,
                    'stalk' =>    $value['tangkai_panjang'],
                    'persentase_stalk' => round($percentage_tangkai_panjang, 2),
                    'pemanen_list_tanpabrondol' => $tanpaBrondol,
                    'pemanen_list_kurangbrondol' => $datakurang_brondol,
                    'resultKurangBrondol' => $resultKurangBrondol,
                    'resultTanpaBrondol' => $resultTanpaBrondol,
                    'vcut' => $vcut,
                    'percentage_vcut' => round($percentage_vcut, 2),
                    'not_vcut' => $not_vcut,
                    'percentage_not_vcut' => round($percentage_not_vcut, 2),
                    'kelas_a' => $kelas_a,
                    'kelas_b' => $kelas_b,
                    'kelas_c' => $kelas_c,
                    'percentage_kelas_a' => round($persentage_kelas_a, 2),
                    'percentage_kelas_b' => round($persentage_kelas_b, 2),
                    'percentage_kelas_c' =>  round($persentage_kelas_c, 2),
                    'tanggal_titel' => $date->format('d M Y'),
                    'appvers' => $appvers,
                    'os_version' => $os_version,
                    'phone_version' => $phone_version,
                ];
                // dd($dataakhir);
                $pdf = Pdf::loadView('Grading.pdfgrading_api', ['data' => $dataakhir]);

                // Set the paper size to A4 and orientation to portrait
                $pdf->setPaper('A4', 'portrait');

                // Generate a unique name for the PDF file
                $pdfName = 'Grading Mill Estate - ' . $est . ' ' . 'Afdeling - ' . $afd . ' ' . 'Tanggal : ' . $date . '.pdf';

                return $pdf->stream($pdfName);
                // Storage::disk('public')->put($pdfName, $pdf->output());
            }
        }
        // dd($est, $afd, $date);
    }

    public function exportgrading(Request $request)
    {

        $check = $request->input('tipedata');
        // dd($check);
        if ($check == 'rekapsatu') {
            $date = $request->input('getdate');
            $reg = $request->input('getregional');
            $type = 'perbulan';
            $result = getdatamill($date, $reg, $type);
            return Excel::download(new Gradingregional($result, $type), 'Excel Grading Regional-' . $date . '-' . 'Bulan-' . $reg . '.xlsx');
        } else if ($check == 'rekapdua') {
            $date = $request->input('getdatedua');
            $reg = $request->input('getregionaldua');
            $type = 'perbulan';
            $data = getdatamill($date, $reg, $type);
            // dd($data);

            $result['data_mill'] = $data['data_mill'];
            return Excel::download(new Gradingregional($result, $type), 'Excel Grading Regional-' . $date . '-' . 'Bulan-' . $reg . '.xlsx');
        } else if ($check == 'rekaptiga') {
            $date = $request->input('getdatetiga');
            $reg = $request->input('getregionaltiga');
            $mill_perhari = $request->input('mill_perhari');
            $type = 'perbulan';
            // dd($mill_perhari);
            $data = rekap_estate_mill_perbulan_perhari($date, $reg, $mill_perhari);
            // dd($data);
            return Excel::download(new Gradingperhari($data), 'Excel Grading Regional-' . $date . '-' . 'Bulan-' . $reg . '.xlsx');
        } else {
            $date = $request->input('getdateempat');
            $reg = $request->input('getregionalempat');
            $type = 'perafdeling';
            $result = getdatamill($date, $reg, $type);
            // dd($data);
            return Excel::download(new Gradingregional($result, $type), 'Excel Grading Regional-' . $date . '-' . 'Bulan-' . $reg . '.xlsx');
        }
    }

    public function getdataforform(Request $request)
    {
        $estate = $request->input('estate');
        $afdeling = $request->input('afdeling');
        $date = $request->input('date');

        $data = Gradingmill::query()->where('estate', $estate)
            ->where('afdeling', $afdeling)
            ->where('datetime', 'like', '%' . $date . '%')
            ->get();
        // dd($data);
        if (!$data) {
            return response()->json(['message' => 'Not found'], 404);
        }
        return response()->json([
            'message' => 'success',
            'data' => $data
        ], 200);
        // dd($Data);
    }
}
