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
use Termwind\Components\Dd;

require "../app/helpers.php";

class SidaktphController extends Controller
{
    //
    public $search;
    public function index(Request $request)
    {
        $queryEst = DB::connection('mysql2')->table('estate')->whereIn('wil', [1, 2, 3])->where('estate.est', '!=', 'CWS1')->where('estate.est', '!=', 'PLASMA')->pluck('est');

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

        $dataSkorPlas = array();
        foreach ($queryEstate as $value1) {
            $querySidaks = DB::connection('mysql2')->table('sidak_tph')
                ->select("sidak_tph.*")
                ->where('est', $value1['est'])
                ->where('datetime', 'like', '%' . '2023-04' . '%')
                ->whereIn('est', ['Plasma1', 'Plasma2', 'Plasma3'])
                ->orderBy('afd', 'asc')
                ->get();
            $DataEstate = $querySidaks->groupBy(['est', 'afd']);
            // dd($DataEstate);
            $DataEstate = json_decode($DataEstate, true);

            foreach ($DataEstate as $key => $value) {
                $luas_ha_est = 0;
                $jml_blok_est = 0;
                $sum_bt_tph_est = 0;
                $sum_bt_jln_est = 0;
                $sum_bt_bin_est = 0;
                $sum_krg_est = 0;
                $sumBuah_est = 0;
                $sumRst_est = 0;
                foreach ($value as $key2 => $value2) {
                    $luas_ha = 0;
                    $jml_blok = 0;
                    $sum_bt_tph = 0;
                    $sum_bt_jln = 0;
                    $sum_bt_bin = 0;
                    $sum_krg = 0;
                    $sumBuah = 0;
                    $sumRst = 0;
                    $listBlokPerAfd = array();
                    foreach ($value2 as $key3 => $value3) {
                        if (!in_array($value3['est'] . ' ' . $value3['afd'] . ' ' . $value3['blok'], $listBlokPerAfd)) {
                            $listBlokPerAfd[] = $value3['est'] . ' ' . $value3['afd'] . ' ' . $value3['blok'];
                            $luas_ha += $value3['luas'];
                        }
                        $jml_blok = count($listBlokPerAfd);
                        $sum_bt_tph += $value3['bt_tph'];
                        $sum_bt_jln += $value3['bt_jalan'];
                        $sum_bt_bin += $value3['bt_bin'];
                        $sum_krg += $value3['jum_karung'];
                        $sumBuah += $value3['buah_tinggal'];
                        $sumRst += $value3['restan_unreported'];
                    }
                    $luas_ha_est += $luas_ha;
                    $jml_blok_est += $jml_blok;
                    $sum_bt_tph_est += $sum_bt_tph;
                    $sum_bt_jln_est += $sum_bt_jln;
                    $sum_bt_bin_est += $sum_bt_bin;
                    $sum_krg_est += $sum_krg;
                    $sumBuah_est += $sumBuah;
                    $sumRst_est += $sumRst;

                    $tot_bt = ($sum_bt_tph + $sum_bt_jln + $sum_bt_bin);
                    $dataSkorPlas[$value1['wil']][$key][$key2]['jml_blok'] = $jml_blok;
                    $dataSkorPlas[$value1['wil']][$key][$key2]['luas_ha'] = $luas_ha;
                    $dataSkorPlas[$value1['wil']][$key][$key2]['bt_tph'] = $sum_bt_tph;
                    $dataSkorPlas[$value1['wil']][$key][$key2]['bt_jln'] = $sum_bt_jln;
                    $dataSkorPlas[$value1['wil']][$key][$key2]['bt_bin'] = $sum_bt_bin;
                    $dataSkorPlas[$value1['wil']][$key][$key2]['tot_bt'] = $tot_bt;
                    $dataSkorPlas[$value1['wil']][$key][$key2]['divBt'] = round($tot_bt / $jml_blok, 2);
                    $dataSkorPlas[$value1['wil']][$key][$key2]['skorBt'] = skor_bt_tph(round($tot_bt / $jml_blok, 2));
                    $dataSkorPlas[$value1['wil']][$key][$key2]['sum_krg'] = $sum_krg;
                    $dataSkorPlas[$value1['wil']][$key][$key2]['divKrg'] = round($sum_krg / $jml_blok, 2);
                    $dataSkorPlas[$value1['wil']][$key][$key2]['skorKrg'] = skor_krg_tph(round($sum_krg / $jml_blok, 2));
                    $dataSkorPlas[$value1['wil']][$key][$key2]['sumBuah'] = $sumBuah;
                    $dataSkorPlas[$value1['wil']][$key][$key2]['divBuah'] = round($sumBuah / $jml_blok, 2);
                    $dataSkorPlas[$value1['wil']][$key][$key2]['skorBuah'] = skor_buah_tph(round($sumBuah / $jml_blok, 2));
                    $dataSkorPlas[$value1['wil']][$key][$key2]['sumRst'] = $sumRst;
                    $dataSkorPlas[$value1['wil']][$key][$key2]['divRst'] = round($sumRst / $jml_blok, 2);
                    $dataSkorPlas[$value1['wil']][$key][$key2]['skorRst'] = skor_rst_tph(round($sumRst / $jml_blok, 2));
                    $dataSkorPlas[$value1['wil']][$key][$key2]['allSkor'] = skor_bt_tph(round($tot_bt / $jml_blok, 2)) + skor_krg_tph(round($sum_krg / $jml_blok, 2)) + skor_buah_tph(round($sumBuah / $jml_blok, 2)) + skor_rst_tph(round($sumRst / $jml_blok, 2));
                }
                $tot_bt_est = ($sum_bt_tph_est + $sum_bt_jln_est + $sum_bt_bin_est);
                $dataSkorPlas[$value1['wil']][$key]['jml_blok_est'] = $jml_blok_est;
                $dataSkorPlas[$value1['wil']][$key]['luas_ha_est'] = $luas_ha_est;
                $dataSkorPlas[$value1['wil']][$key]['bt_tph_est'] = $sum_bt_tph_est;
                $dataSkorPlas[$value1['wil']][$key]['bt_jln_est'] = $sum_bt_jln_est;
                $dataSkorPlas[$value1['wil']][$key]['bt_bin_est'] = $sum_bt_bin_est;
                $dataSkorPlas[$value1['wil']][$key]['tot_bt_est'] = $tot_bt_est;
                $dataSkorPlas[$value1['wil']][$key]['divBt_est'] = round($tot_bt_est / $jml_blok_est, 2);
                $dataSkorPlas[$value1['wil']][$key]['skorBt_est'] = skor_bt_tph(round($tot_bt_est / $jml_blok_est, 2));
                $dataSkorPlas[$value1['wil']][$key]['sum_krg_est'] = $sum_krg_est;
                $dataSkorPlas[$value1['wil']][$key]['divKrg_est'] = round($sum_krg_est / $jml_blok_est, 2);
                $dataSkorPlas[$value1['wil']][$key]['skorKrg_est'] = skor_krg_tph(round($sum_krg_est / $jml_blok_est, 2));
                $dataSkorPlas[$value1['wil']][$key]['sumBuah_est'] = $sumBuah_est;
                $dataSkorPlas[$value1['wil']][$key]['divBuah_est'] = round($sumBuah_est / $jml_blok_est, 2);
                $dataSkorPlas[$value1['wil']][$key]['skorBuah_est'] = skor_buah_tph(round($sumBuah_est / $jml_blok_est, 2));
                $dataSkorPlas[$value1['wil']][$key]['sumRst_est'] = $sumRst_est;
                $dataSkorPlas[$value1['wil']][$key]['divRst_est'] = round($sumRst_est / $jml_blok_est, 2);
                $dataSkorPlas[$value1['wil']][$key]['skorRst_est'] = skor_rst_tph(round($sumRst_est / $jml_blok_est, 2));
                $dataSkorPlas[$value1['wil']][$key]['allSkor_est'] = skor_bt_tph(round($tot_bt_est / $jml_blok_est, 2)) + skor_krg_tph(round($sum_krg_est / $jml_blok_est, 2)) + skor_buah_tph(round($sumBuah_est / $jml_blok_est, 2)) + skor_rst_tph(round($sumRst_est / $jml_blok_est, 2));
            }
        }
        // dd($dataSkorPlas);

        return view('dashboardtph', ['list_estate' => $queryEst, 'list_wilayah' => $queryWill, 'optYear' => $optYear, 'list_month' => $listMonth]);
    }



    public function changeDataTph(Request $request)
    {
        $tanggal = $request->get('date');
        $regional = $request->get('regional');
        $queryEstate = DB::connection('mysql2')->table('estate')
            ->select('estate.*')
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->where('wil.regional', $regional)
            ->get();
        $queryEstate = json_decode($queryEstate, true);

        $dataSkor = array();
        foreach ($queryEstate as $value1) {
            $queryTrans = DB::connection('mysql2')->table('sidak_tph')
                ->select("sidak_tph.*")
                ->where('est', $value1['est'])
                ->where('datetime', 'like', '%' . $tanggal . '%')
                ->whereNotIn('est', ['Plasma1', 'Plasma2', 'Plasma3'])
                ->orderBy('afd', 'asc')
                ->get();
            $DataEstate = $queryTrans->groupBy(['est', 'afd']);
            // dd($DataEstate);
            $DataEstate = json_decode($DataEstate, true);

            foreach ($DataEstate as $key => $value) {
                $luas_ha_est = 0;
                $jml_blok_est = 0;
                $sum_bt_tph_est = 0;
                $sum_bt_jln_est = 0;
                $sum_bt_bin_est = 0;
                $sum_krg_est = 0;
                $sumBuah_est = 0;
                $sumRst_est = 0;
                foreach ($value as $key2 => $value2) {
                    $luas_ha = 0;
                    $jml_blok = 0;
                    $sum_bt_tph = 0;
                    $sum_bt_jln = 0;
                    $sum_bt_bin = 0;
                    $sum_krg = 0;
                    $sumBuah = 0;
                    $sumRst = 0;
                    $listBlokPerAfd = array();
                    foreach ($value2 as $key3 => $value3) {
                        if (!in_array($value3['est'] . ' ' . $value3['afd'] . ' ' . $value3['blok'], $listBlokPerAfd)) {
                            $listBlokPerAfd[] = $value3['est'] . ' ' . $value3['afd'] . ' ' . $value3['blok'];
                            $luas_ha += $value3['luas'];
                        }
                        $jml_blok = count($listBlokPerAfd);
                        $sum_bt_tph += $value3['bt_tph'];
                        $sum_bt_jln += $value3['bt_jalan'];
                        $sum_bt_bin += $value3['bt_bin'];
                        $sum_krg += $value3['jum_karung'];
                        $sumBuah += $value3['buah_tinggal'];
                        $sumRst += $value3['restan_unreported'];
                    }
                    $luas_ha_est += $luas_ha;
                    $jml_blok_est += $jml_blok;
                    $sum_bt_tph_est += $sum_bt_tph;
                    $sum_bt_jln_est += $sum_bt_jln;
                    $sum_bt_bin_est += $sum_bt_bin;
                    $sum_krg_est += $sum_krg;
                    $sumBuah_est += $sumBuah;
                    $sumRst_est += $sumRst;

                    $tot_bt = ($sum_bt_tph + $sum_bt_jln + $sum_bt_bin);
                    $dataSkor[$value1['wil']][$key][$key2]['jml_blok'] = $jml_blok;
                    $dataSkor[$value1['wil']][$key][$key2]['luas_ha'] = $luas_ha;
                    $dataSkor[$value1['wil']][$key][$key2]['bt_tph'] = $sum_bt_tph;
                    $dataSkor[$value1['wil']][$key][$key2]['bt_jln'] = $sum_bt_jln;
                    $dataSkor[$value1['wil']][$key][$key2]['bt_bin'] = $sum_bt_bin;
                    $dataSkor[$value1['wil']][$key][$key2]['tot_bt'] = $tot_bt;
                    $dataSkor[$value1['wil']][$key][$key2]['divBt'] = round($tot_bt / $jml_blok, 2);
                    $dataSkor[$value1['wil']][$key][$key2]['skorBt'] = skor_bt_tph(round($tot_bt / $jml_blok, 2));
                    $dataSkor[$value1['wil']][$key][$key2]['sum_krg'] = $sum_krg;
                    $dataSkor[$value1['wil']][$key][$key2]['divKrg'] = round($sum_krg / $jml_blok, 2);
                    $dataSkor[$value1['wil']][$key][$key2]['skorKrg'] = skor_krg_tph(round($sum_krg / $jml_blok, 2));
                    $dataSkor[$value1['wil']][$key][$key2]['sumBuah'] = $sumBuah;
                    $dataSkor[$value1['wil']][$key][$key2]['divBuah'] = round($sumBuah / $jml_blok, 2);
                    $dataSkor[$value1['wil']][$key][$key2]['skorBuah'] = skor_buah_tph(round($sumBuah / $jml_blok, 2));
                    $dataSkor[$value1['wil']][$key][$key2]['sumRst'] = $sumRst;
                    $dataSkor[$value1['wil']][$key][$key2]['divRst'] = round($sumRst / $jml_blok, 2);
                    $dataSkor[$value1['wil']][$key][$key2]['skorRst'] = skor_rst_tph(round($sumRst / $jml_blok, 2));
                    $dataSkor[$value1['wil']][$key][$key2]['allSkor'] = skor_bt_tph(round($tot_bt / $jml_blok, 2)) + skor_krg_tph(round($sum_krg / $jml_blok, 2)) + skor_buah_tph(round($sumBuah / $jml_blok, 2)) + skor_rst_tph(round($sumRst / $jml_blok, 2));
                }
                $tot_bt_est = ($sum_bt_tph_est + $sum_bt_jln_est + $sum_bt_bin_est);
                $dataSkor[$value1['wil']][$key]['jml_blok_est'] = $jml_blok_est;
                $dataSkor[$value1['wil']][$key]['luas_ha_est'] = $luas_ha_est;
                $dataSkor[$value1['wil']][$key]['bt_tph_est'] = $sum_bt_tph_est;
                $dataSkor[$value1['wil']][$key]['bt_jln_est'] = $sum_bt_jln_est;
                $dataSkor[$value1['wil']][$key]['bt_bin_est'] = $sum_bt_bin_est;
                $dataSkor[$value1['wil']][$key]['tot_bt_est'] = $tot_bt_est;
                $dataSkor[$value1['wil']][$key]['divBt_est'] = round($tot_bt_est / $jml_blok_est, 2);
                $dataSkor[$value1['wil']][$key]['skorBt_est'] = skor_bt_tph(round($tot_bt_est / $jml_blok_est, 2));
                $dataSkor[$value1['wil']][$key]['sum_krg_est'] = $sum_krg_est;
                $dataSkor[$value1['wil']][$key]['divKrg_est'] = round($sum_krg_est / $jml_blok_est, 2);
                $dataSkor[$value1['wil']][$key]['skorKrg_est'] = skor_krg_tph(round($sum_krg_est / $jml_blok_est, 2));
                $dataSkor[$value1['wil']][$key]['sumBuah_est'] = $sumBuah_est;
                $dataSkor[$value1['wil']][$key]['divBuah_est'] = round($sumBuah_est / $jml_blok_est, 2);
                $dataSkor[$value1['wil']][$key]['skorBuah_est'] = skor_buah_tph(round($sumBuah_est / $jml_blok_est, 2));
                $dataSkor[$value1['wil']][$key]['sumRst_est'] = $sumRst_est;
                $dataSkor[$value1['wil']][$key]['divRst_est'] = round($sumRst_est / $jml_blok_est, 2);
                $dataSkor[$value1['wil']][$key]['skorRst_est'] = skor_rst_tph(round($sumRst_est / $jml_blok_est, 2));
                $dataSkor[$value1['wil']][$key]['allSkor_est'] = skor_bt_tph(round($tot_bt_est / $jml_blok_est, 2)) + skor_krg_tph(round($sum_krg_est / $jml_blok_est, 2)) + skor_buah_tph(round($sumBuah_est / $jml_blok_est, 2)) + skor_rst_tph(round($sumRst_est / $jml_blok_est, 2));
            }
        }
        // dd($dataSkor);
        $dataSkorPlas = array();
        foreach ($queryEstate as $value1) {
            $querySidaks = DB::connection('mysql2')->table('sidak_tph')
                ->select("sidak_tph.*")
                ->where('est', $value1['est'])
                ->where('datetime', 'like', '%' . $tanggal . '%')
                ->whereIn('est', ['Plasma1', 'Plasma2', 'Plasma3'])
                ->orderBy('afd', 'asc')
                ->get();
            $DataEstate = $querySidaks->groupBy(['est', 'afd']);
            // dd($DataEstate);
            $DataEstate = json_decode($DataEstate, true);

            foreach ($DataEstate as $key => $value) {
                $luas_ha_est = 0;
                $jml_blok_est = 0;
                $sum_bt_tph_est = 0;
                $sum_bt_jln_est = 0;
                $sum_bt_bin_est = 0;
                $sum_krg_est = 0;
                $sumBuah_est = 0;
                $sumRst_est = 0;
                foreach ($value as $key2 => $value2) {
                    $luas_ha = 0;
                    $jml_blok = 0;
                    $sum_bt_tph = 0;
                    $sum_bt_jln = 0;
                    $sum_bt_bin = 0;
                    $sum_krg = 0;
                    $sumBuah = 0;
                    $sumRst = 0;
                    $listBlokPerAfd = array();
                    foreach ($value2 as $key3 => $value3) {
                        if (!in_array($value3['est'] . ' ' . $value3['afd'] . ' ' . $value3['blok'], $listBlokPerAfd)) {
                            $listBlokPerAfd[] = $value3['est'] . ' ' . $value3['afd'] . ' ' . $value3['blok'];
                            $luas_ha += $value3['luas'];
                        }
                        $jml_blok = count($listBlokPerAfd);
                        $sum_bt_tph += $value3['bt_tph'];
                        $sum_bt_jln += $value3['bt_jalan'];
                        $sum_bt_bin += $value3['bt_bin'];
                        $sum_krg += $value3['jum_karung'];
                        $sumBuah += $value3['buah_tinggal'];
                        $sumRst += $value3['restan_unreported'];
                    }
                    $luas_ha_est += $luas_ha;
                    $jml_blok_est += $jml_blok;
                    $sum_bt_tph_est += $sum_bt_tph;
                    $sum_bt_jln_est += $sum_bt_jln;
                    $sum_bt_bin_est += $sum_bt_bin;
                    $sum_krg_est += $sum_krg;
                    $sumBuah_est += $sumBuah;
                    $sumRst_est += $sumRst;

                    $tot_bt = ($sum_bt_tph + $sum_bt_jln + $sum_bt_bin);
                    $dataSkorPlas[$value1['wil']][$key][$key2]['jml_blok'] = $jml_blok;
                    $dataSkorPlas[$value1['wil']][$key][$key2]['luas_ha'] = $luas_ha;
                    $dataSkorPlas[$value1['wil']][$key][$key2]['bt_tph'] = $sum_bt_tph;
                    $dataSkorPlas[$value1['wil']][$key][$key2]['bt_jln'] = $sum_bt_jln;
                    $dataSkorPlas[$value1['wil']][$key][$key2]['bt_bin'] = $sum_bt_bin;
                    $dataSkorPlas[$value1['wil']][$key][$key2]['tot_bt'] = $tot_bt;
                    $dataSkorPlas[$value1['wil']][$key][$key2]['divBt'] = round($tot_bt / $jml_blok, 2);
                    $dataSkorPlas[$value1['wil']][$key][$key2]['skorBt'] = skor_bt_tph(round($tot_bt / $jml_blok, 2));
                    $dataSkorPlas[$value1['wil']][$key][$key2]['sum_krg'] = $sum_krg;
                    $dataSkorPlas[$value1['wil']][$key][$key2]['divKrg'] = round($sum_krg / $jml_blok, 2);
                    $dataSkorPlas[$value1['wil']][$key][$key2]['skorKrg'] = skor_krg_tph(round($sum_krg / $jml_blok, 2));
                    $dataSkorPlas[$value1['wil']][$key][$key2]['sumBuah'] = $sumBuah;
                    $dataSkorPlas[$value1['wil']][$key][$key2]['divBuah'] = round($sumBuah / $jml_blok, 2);
                    $dataSkorPlas[$value1['wil']][$key][$key2]['skorBuah'] = skor_buah_tph(round($sumBuah / $jml_blok, 2));
                    $dataSkorPlas[$value1['wil']][$key][$key2]['sumRst'] = $sumRst;
                    $dataSkorPlas[$value1['wil']][$key][$key2]['divRst'] = round($sumRst / $jml_blok, 2);
                    $dataSkorPlas[$value1['wil']][$key][$key2]['skorRst'] = skor_rst_tph(round($sumRst / $jml_blok, 2));
                    $dataSkorPlas[$value1['wil']][$key][$key2]['allSkor'] = skor_bt_tph(round($tot_bt / $jml_blok, 2)) + skor_krg_tph(round($sum_krg / $jml_blok, 2)) + skor_buah_tph(round($sumBuah / $jml_blok, 2)) + skor_rst_tph(round($sumRst / $jml_blok, 2));
                }
                $tot_bt_est = ($sum_bt_tph_est + $sum_bt_jln_est + $sum_bt_bin_est);
                $dataSkorPlas[$value1['wil']][$key]['jml_blok_est'] = $jml_blok_est;
                $dataSkorPlas[$value1['wil']][$key]['luas_ha_est'] = $luas_ha_est;
                $dataSkorPlas[$value1['wil']][$key]['bt_tph_est'] = $sum_bt_tph_est;
                $dataSkorPlas[$value1['wil']][$key]['bt_jln_est'] = $sum_bt_jln_est;
                $dataSkorPlas[$value1['wil']][$key]['bt_bin_est'] = $sum_bt_bin_est;
                $dataSkorPlas[$value1['wil']][$key]['tot_bt_est'] = $tot_bt_est;
                $dataSkorPlas[$value1['wil']][$key]['divBt_est'] = round($tot_bt_est / $jml_blok_est, 2);
                $dataSkorPlas[$value1['wil']][$key]['skorBt_est'] = skor_bt_tph(round($tot_bt_est / $jml_blok_est, 2));
                $dataSkorPlas[$value1['wil']][$key]['sum_krg_est'] = $sum_krg_est;
                $dataSkorPlas[$value1['wil']][$key]['divKrg_est'] = round($sum_krg_est / $jml_blok_est, 2);
                $dataSkorPlas[$value1['wil']][$key]['skorKrg_est'] = skor_krg_tph(round($sum_krg_est / $jml_blok_est, 2));
                $dataSkorPlas[$value1['wil']][$key]['sumBuah_est'] = $sumBuah_est;
                $dataSkorPlas[$value1['wil']][$key]['divBuah_est'] = round($sumBuah_est / $jml_blok_est, 2);
                $dataSkorPlas[$value1['wil']][$key]['skorBuah_est'] = skor_buah_tph(round($sumBuah_est / $jml_blok_est, 2));
                $dataSkorPlas[$value1['wil']][$key]['sumRst_est'] = $sumRst_est;
                $dataSkorPlas[$value1['wil']][$key]['divRst_est'] = round($sumRst_est / $jml_blok_est, 2);
                $dataSkorPlas[$value1['wil']][$key]['skorRst_est'] = skor_rst_tph(round($sumRst_est / $jml_blok_est, 2));
                $dataSkorPlas[$value1['wil']][$key]['allSkor_est'] = skor_bt_tph(round($tot_bt_est / $jml_blok_est, 2)) + skor_krg_tph(round($sum_krg_est / $jml_blok_est, 2)) + skor_buah_tph(round($sumBuah_est / $jml_blok_est, 2)) + skor_rst_tph(round($sumRst_est / $jml_blok_est, 2));
            }
        }

        return view('dataSidakTph', [
            'dataSkor' => $dataSkor,
            'dataSkorPlasma' => $dataSkorPlas,
            'tanggal' => $tanggal
        ]);
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

    // chart ajax brondolan tinggal dan pencarian berdasarkan minggu
    public function getBtTph(Request $request)
    {
        $regSidak = $request->get('reg');
        $queryWill = DB::connection('mysql2')
            ->table('wil')
            ->whereIn('regional', [$regSidak])
            ->get();
        $queryReg = DB::connection('mysql2')
            ->table('wil')
            ->whereIn('regional', [$regSidak])
            ->pluck('regional');
        $queryReg2 = DB::connection('mysql2')
            ->table('wil')
            ->whereIn('regional', [$regSidak])
            ->pluck('id')
            ->toArray();

        // dapatkan data estate dari table estate dengan wilayah 1 , 2 , 3
        $queryEst = DB::connection('mysql2')
            ->table('estate')
            // ->whereNotIn('estate.est', ['PLASMA'])
            ->whereIn('wil', $queryReg2)
            ->get();
        // dd($queryEst);
        $queryEste = DB::connection('mysql2')
            ->table('estate')
            ->whereNotIn('estate.est', ['PLASMA', 'SRE', 'LDE', 'SKE'])
            ->whereIn('wil', $queryReg2)
            ->get();
        $queryEste = $queryEste->groupBy(function ($item) {
            return $item->wil;
        });

        $queryEste = json_decode($queryEste, true);

        // dd($queryEst);
        $queryAfd = DB::connection('mysql2')
            ->Table('afdeling')
            ->select('afdeling.id', 'afdeling.nama', 'estate.est') //buat mengambil data di estate db dan willayah db
            ->join('estate', 'estate.id', '=', 'afdeling.estate') //kemudian di join untuk mengambil est perwilayah
            ->whereNotIn('estate.est', ['SRE', 'LDE', 'SKE'])
            ->get();

        $queryAfd = json_decode($queryAfd, true);

        //array untuk tampung nilai bt tph per estate dari table bt_jalan & bt_tph dll
        $arrBtTPHperEst = []; //table dari brondolan di buat jadi array agar bisa di parse ke json
        $arrKRest = []; //table dari jum_jkarung di buat jadi array agar bisa di parse ke json
        $arrBHest = []; //table dari Buah di buat jadi array agar bisa di parse ke json
        $arrRSest = []; //table array untuk buah restant tidak di laporkan

        ///array untuk table nya

        $dataSkorAwal = [];

        $list_all_will = [];

        //memberi nilai 0 default kesemua estate
        foreach ($queryEst as $key => $value) {
            $arrBtTPHperEst[$value->est] = 0; //est mengambil value dari table estate
            $arrKRest[$value->est] = 0;
            $arrBHest[$value->est] = 0;
            $arrRSest[$value->est] = 0;
        }
        // dd($queryEst);
        foreach ($queryWill as $key => $value) {
            $arrBtTPHperWil[$value->nama] = 0; //est mengambil value dari table estate
            $arrKRestWil[$value->nama] = 0;
            $arrBHestWil[$value->nama] = 0;
            $arrRestWill[$value->nama] = 0;
        }

        $firstWeek = $request->get('start');
        $lastWeek = $request->get('finish');

        // dd($firstWeek, $lastWeek);
        $query = DB::connection('mysql2')
            ->Table('sidak_tph')
            ->select('sidak_tph.*', 'estate.wil') //buat mengambil data di estate db dan willayah db
            ->whereIn('estate.wil', $queryReg2)
            ->join('estate', 'estate.est', '=', 'sidak_tph.est') //kemudian di join untuk mengambil est perwilayah
            ->whereBetween('sidak_tph.datetime', [$firstWeek, $lastWeek])
            // ->where('sidak_tph.datetime', 'LIKE', '%' . $request->$firstDate . '%')
            ->get();
        //     ->get();
        $queryAFD = DB::connection('mysql2')
            ->Table('sidak_tph')
            ->select('sidak_tph.*', 'estate.wil') //buat mengambil data di estate db dan willayah db
            ->whereIn('estate.wil', $queryReg2)
            ->join('estate', 'estate.est', '=', 'sidak_tph.est') //kemudian di join untuk mengambil est perwilayah
            ->whereBetween('sidak_tph.datetime', [$firstWeek, $lastWeek])
            // ->where('sidak_tph.datetime', 'LIKE', '%' . $request->$firstDate . '%')
            ->get();
        // dd($query);
        $queryAsisten = DB::connection('mysql2')
            ->Table('asisten_qc')
            ->get();

        $dataAfdEst = [];

        $querySidak = DB::connection('mysql2')
            ->table('sidak_tph')
            ->whereBetween('sidak_tph.datetime', [$firstWeek, $lastWeek])
            // ->whereBetween('sidak_tph.datetime', ['2023-01-23', '202-12-25'])
            ->get();
        $querySidak = json_decode($querySidak, true);

        $allBlok = $query->groupBy(function ($item) {
            return $item->blok;
        });

        if (!empty($query && $queryAFD && $querySidak)) {
            $queryGroup = $queryAFD->groupBy(function ($item) {
                return $item->est;
            });
            // dd($queryGroup);
            $queryWi = DB::connection('mysql2')
                ->table('estate')
                ->whereIn('wil', $queryReg2)
                ->get();

            $queryWill = $queryWi->groupBy(function ($item) {
                return $item->nama;
            });

            $queryWill2 = $queryWi->groupBy(function ($item) {
                return $item->nama;
            });

            //untuk table!!
            // store wil -> est -> afd
            // menyimpan array nested dari  wil -> est -> afd
            foreach ($queryEste as $key => $value) {
                foreach ($value as $key2 => $value2) {
                    foreach ($queryAfd as $key3 => $value3) {
                        $est = $value2['est'];
                        $afd = $value3['nama'];
                        if ($value2['est'] == $value3['est']) {
                            foreach ($querySidak as $key4 => $value4) {
                                if ($est == $value4['est'] && $afd == $value4['afd']) {
                                    $dataAfdEst[$est][$afd][] = $value4;
                                } else {
                                    $dataAfdEst[$est][$afd]['null'] = 0;
                                }
                            }
                        }
                    }
                }
            }

            foreach ($dataAfdEst as $key => $value) {
                foreach ($value as $key2 => $value2) {
                    foreach ($value2 as $key3 => $value3) {
                        unset($dataAfdEst[$key][$key2]['null']);
                        if (empty($dataAfdEst[$key][$key2])) {
                            $dataAfdEst[$key][$key2] = 0;
                        }
                    }
                }
            }

            $listBlokPerAfd = [];
            foreach ($dataAfdEst as $key => $value) {
                foreach ($value as $key2 => $value2) {
                    if (is_array($value2)) {
                        foreach ($value2 as $key3 => $value3) {
                            // dd($key3);
                            foreach ($allBlok as $key4 => $value4) {
                                if ($value3['blok'] == $key4) {
                                    $listBlokPerAfd[$key][$key2][$key3] = $value4;
                                }
                            }
                        }
                    }
                }

                // //menghitung data skor untuk brd/blok
                foreach ($dataAfdEst as $key => $value) {
                    foreach ($value as $key2 => $value2) {
                        if (is_array($value2)) {
                            $jum_blok = 0;
                            $sum_bt_tph = 0;
                            $sum_bt_jalan = 0;
                            $sum_bt_bin = 0;
                            $sum_all = 0;
                            $sum_all_karung = 0;
                            $sum_jum_karung = 0;
                            $sum_buah_tinggal = 0;
                            $sum_all_bt_tgl = 0;
                            $sum_restan_unreported = 0;
                            $sum_all_restan_unreported = 0;
                            $listBlokPerAfd = [];
                            foreach ($value2 as $key3 => $value3) {
                                if (is_array($value3)) {
                                    $blok = $value3['est'] . ' ' . $value3['afd'] . ' ' . $value3['blok'];

                                    if (!in_array($blok, $listBlokPerAfd)) {
                                        array_push($listBlokPerAfd, $blok);
                                    }

                                    // $jum_blok = count(float)($listBlokPerAfd);
                                    $jum_blok = count($listBlokPerAfd);

                                    $sum_bt_tph += $value3['bt_tph'];
                                    $sum_bt_jalan += $value3['bt_jalan'];
                                    $sum_bt_bin += $value3['bt_bin'];

                                    $sum_jum_karung += $value3['jum_karung'];
                                    $sum_buah_tinggal += $value3['buah_tinggal'];
                                    $sum_restan_unreported += $value3['restan_unreported'];
                                }
                            }
                            //change value 3to float type
                            $sum_all = $sum_bt_tph + $sum_bt_jalan + $sum_bt_bin;
                            $sum_all_karung = $sum_jum_karung;
                            $sum_all_bt_tgl = $sum_buah_tinggal;
                            $sum_all_restan_unreported = $sum_restan_unreported;
                            $skor_brd = round($sum_all / $jum_blok, 1);
                            // dd($skor_brd);
                            $skor_kr = round($sum_all_karung / $jum_blok, 1);
                            $skor_buahtgl = round($sum_all_bt_tgl / $jum_blok, 1);
                            $skor_restan = round($sum_all_restan_unreported / $jum_blok, 1);

                            $skoreTotal = skorBRDsidak($skor_brd) + skorKRsidak($skor_kr) + skorBHsidak($skor_buahtgl) + skorRSsidak($skor_restan);

                            $dataSkorAwal[$key][$key2]['karung_tes'] = $sum_all_karung;
                            $dataSkorAwal[$key][$key2]['tph_test'] = $sum_all;
                            $dataSkorAwal[$key][$key2]['buah_test'] = $sum_all_bt_tgl;
                            $dataSkorAwal[$key][$key2]['restant_tes'] = $sum_all_restan_unreported;

                            $dataSkorAwal[$key][$key2]['jumlah_blok'] = $jum_blok;

                            $dataSkorAwal[$key][$key2]['brd_blok'] = skorBRDsidak($skor_brd);
                            $dataSkorAwal[$key][$key2]['kr_blok'] = skorKRsidak($skor_kr);
                            $dataSkorAwal[$key][$key2]['buah_blok'] = skorBHsidak($skor_buahtgl);
                            $dataSkorAwal[$key][$key2]['restan_blok'] = skorRSsidak($skor_restan);
                            $dataSkorAwal[$key][$key2]['skore_akhir'] = $skoreTotal;
                        } else {
                            $dataSkorAwal[$key][$key2]['karung_tes'] = 0;
                            $dataSkorAwal[$key][$key2]['restant_tes'] = 0;
                            $dataSkorAwal[$key][$key2]['jumlah_blok'] = 0;
                            $dataSkorAwal[$key][$key2]['tph_test'] = 0;
                            $dataSkorAwal[$key][$key2]['buah_test'] = 0;

                            $dataSkorAwal[$key][$key2]['brd_blok'] = 0;
                            $dataSkorAwal[$key][$key2]['kr_blok'] = 0;
                            $dataSkorAwal[$key][$key2]['buah_blok'] = 0;
                            $dataSkorAwal[$key][$key2]['restan_blok'] = 0;
                            $dataSkorAwal[$key][$key2]['skore_akhir'] = 0;
                        }
                    }
                }

                // dd($dataSkorAwal);

                foreach ($dataSkorAwal as $key => $value) {
                    $jum_blok = 0;
                    $jum_all_blok = 0;
                    $sum_all_tph = 0;
                    $sum_tph = 0;
                    $sum_all_karung = 0;
                    $sum_karung = 0;
                    $sum_all_buah = 0;
                    $sum_buah = 0;
                    $sum_all_restant = 0;
                    $sum_restant = 0;
                    foreach ($value as $key2 => $value2) {
                        if (is_array($value2)) {
                            $jum_blok += $value2['jumlah_blok'];
                            $sum_karung += $value2['karung_tes'];
                            $sum_restant += $value2['restant_tes'];
                            $sum_tph += $value2['tph_test'];
                            $sum_buah += $value2['buah_test'];
                        }
                    }
                    $sum_all_tph = $sum_tph;
                    $jum_all_blok = $jum_blok;
                    $sum_all_karung = $sum_karung;
                    $sum_all_buah = $sum_buah;
                    $sum_all_restant = $sum_restant;

                    if ($jum_all_blok != 0) {
                        $skor_tph = round($sum_all_tph / $jum_all_blok, 2);
                        $skor_karung = round($sum_all_karung / $jum_all_blok, 2);
                        $skor_buah = round($sum_all_buah / $jum_all_blok, 2);
                        $skor_restan = round($sum_all_restant / $jum_all_blok, 2);
                    } else {
                        $skor_tph = 0;
                        $skor_karung = 0;
                        $skor_buah = 0;
                        $skor_restan = 0;
                    }

                    $skoreTotal = skorBRDsidak($skor_tph) + skorKRsidak($skor_karung) + skorBHsidak($skor_buah) + skorRSsidak($skor_restan);

                    $dataSkorAwaltest[$key]['total_estate_brondol'] = $sum_all_tph;
                    $dataSkorAwaltest[$key]['total_estate_karung'] = $sum_all_karung;
                    $dataSkorAwaltest[$key]['total_estate_buah_tinggal'] = $sum_all_buah;
                    $dataSkorAwaltest[$key]['total_estate_restan_tinggal'] = $sum_all_restant;
                    $dataSkorAwaltest[$key]['tph'] = skorBRDsidak($skor_tph);
                    $dataSkorAwaltest[$key]['karung'] = skorKRsidak($skor_karung);
                    $dataSkorAwaltest[$key]['buah_tinggal'] = skorBHsidak($skor_buah);
                    $dataSkorAwaltest[$key]['restant'] = skorRSsidak($skor_restan);
                    $dataSkorAwaltest[$key]['total_blokokok'] = $jum_all_blok;
                    $dataSkorAwaltest[$key]['skor_akhir'] = $skoreTotal;
                }
                // dd($dataSkorAwaltest);

                foreach ($queryEste as $key => $value) {
                    foreach ($value as $key2 => $value2) {
                        foreach ($dataSkorAwal as $key3 => $value3) {
                            if ($value2['est'] == $key3) {
                                $dataSkorAkhirPerWil[$key][$key3] = $value3;
                            }
                        }
                    }
                }

                foreach ($queryEste as $key => $value) {
                    foreach ($value as $key2 => $value2) {
                        foreach ($dataSkorAwaltest as $key3 => $value3) {
                            if ($value2['est'] == $key3) {
                                $dataSkorAkhirPerWilEst[$key][$key3] = $value3;
                            }
                        }
                    }
                }
                // dd($dataSkorAkhirPerWilEst['3']);
                //menshort nilai masing masing
                $sortList = [];
                foreach ($dataSkorAkhirPerWil as $key => $value) {
                    $inc = 0;
                    foreach ($value as $key2 => $value2) {
                        foreach ($value2 as $key3 => $value3) {
                            $sortList[$key][$key2 . '_' . $key3] = $value3['skore_akhir'];
                            $inc++;
                        }
                    }
                }

                //short list untuk mengurutkan valuenya
                foreach ($sortList as &$value) {
                    arsort($value);
                }
                // dd($sortList);
                $sortListEstate = [];
                foreach ($dataSkorAkhirPerWilEst as $key => $value) {
                    $inc = 0;
                    foreach ($value as $key2 => $value2) {
                        $sortListEstate[$key][$key2] = $value2['skor_akhir'];
                        $inc++;
                    }
                }

                //short list untuk mengurutkan valuenya
                foreach ($sortListEstate as &$value) {
                    arsort($value);
                }

                // dd($sortListEstate);
                foreach ($dataSkorAkhirPerWilEst as $key => $value) {
                    foreach ($value as $key2 => $value2) {
                        foreach ($value2 as $key3 => $value3) {
                        }
                    }
                }

                //menambahkan nilai rank ketia semua total skor sudah di uritkan
                $test = [];
                $listRank = [];
                foreach ($dataSkorAkhirPerWil as $key => $value) {
                    // create an array to store the skore_akhir values
                    $skore_akhir_values = [];
                    foreach ($value as $key2 => $value2) {
                        foreach ($value2 as $key3 => $value3) {
                            $skore_akhir_values[] = $value3['skore_akhir'];
                        }
                    }
                    // sort the skore_akhir values in descending order
                    rsort($skore_akhir_values);
                    // assign ranks to each skore_akhir value
                    foreach ($value as $key2 => $value2) {
                        foreach ($value2 as $key3 => $value3) {
                            $rank = array_search($value3['skore_akhir'], $skore_akhir_values) + 1;
                            $dataSkorAkhirPerWil[$key][$key2][$key3]['rank'] = $rank;
                            $test[$key][] = $value3['skore_akhir'];
                        }
                    }
                }

                // perbaiki rank saya berdasarkan skore_akhir di mana jika $value3['skore_akhir'] terkecil merupakan rank 1 dan seterusnya
                $list_all_will = [];
                foreach ($dataSkorAkhirPerWil as $key => $value) {
                    $inc = 0;
                    foreach ($value as $key2 => $value2) {
                        foreach ($value2 as $key3 => $value3) {
                            $list_all_will[$key][$inc]['est_afd'] = $key2 . '_' . $key3;
                            $list_all_will[$key][$inc]['est'] = $key2;
                            $list_all_will[$key][$inc]['afd'] = $key3;
                            $list_all_will[$key][$inc]['skor'] = $value3['skore_akhir'];
                            foreach ($queryAsisten as $key4 => $value4) {
                                if ($value4->est == $key2 && $value4->afd == $key3) {
                                    $list_all_will[$key][$inc]['nama'] = $value4->nama;
                                }
                            }
                            if (empty($list_all_will[$key][$inc]['nama'])) {
                                $list_all_will[$key][$inc]['nama'] = '-';
                            }
                            $inc++;
                        }
                    }
                }

                $skor_gm_wil = [];
                foreach ($dataSkorAkhirPerWilEst as $key => $value) {
                    $sum_est_brondol = 0;
                    $sum_est_karung = 0;
                    $sum_est_buah_tinggal = 0;
                    $sum_est_restan_tinggal = 0;
                    $sum_blok = 0;
                    foreach ($value as $key2 => $value2) {
                        $sum_est_brondol += $value2['total_estate_brondol'];
                        $sum_est_karung += $value2['total_estate_karung'];
                        $sum_est_buah_tinggal += $value2['total_estate_buah_tinggal'];
                        $sum_est_restan_tinggal += $value2['total_estate_restan_tinggal'];

                        // dd($value2['total_blokokok']);

                        // if ($value2['total_blokokok'] != 0) {
                        $sum_blok += $value2['total_blokokok'];
                        // } else {
                        //     $sum_blok = 1;
                        // }
                    }

                    if ($sum_blok != 0) {
                        $skor_total_brondol = round($sum_est_brondol / $sum_blok, 2);
                    } else {
                        $skor_total_brondol = 0;
                    }

                    if ($sum_blok != 0) {
                        $skor_total_karung = round($sum_est_karung / $sum_blok, 2);
                    } else {
                        $skor_total_karung = 0;
                    }

                    if ($sum_blok != 0) {
                        $skor_total_buah_tinggal = round($sum_est_buah_tinggal / $sum_blok, 2);
                    } else {
                        $skor_total_buah_tinggal = 0;
                    }

                    if ($sum_blok != 0) {
                        $skor_total_restan_tinggal = round($sum_est_restan_tinggal / $sum_blok, 2);
                    } else {
                        $skor_total_restan_tinggal = 0;
                    }

                    $skor_gm_wil[$key]['total_brondolan'] = $sum_est_brondol;
                    $skor_gm_wil[$key]['total_karung'] = $sum_est_karung;
                    $skor_gm_wil[$key]['total_buah_tinggal'] = $sum_est_buah_tinggal;
                    $skor_gm_wil[$key]['total_restan'] = $sum_est_restan_tinggal;
                    $skor_gm_wil[$key]['blok'] = $sum_blok;
                    $skor_gm_wil[$key]['skor'] = skorBRDsidak($skor_total_brondol) + skorKRsidak($skor_total_karung) + skorBHsidak($skor_total_buah_tinggal) + skorRSsidak($skor_total_restan_tinggal);
                }

                $GmSkorWil = [];

                $queryAsisten1 = DB::connection('mysql2')
                    ->Table('asisten_qc')
                    ->get();
                $queryAsisten1 = json_decode($queryAsisten1, true);

                foreach ($skor_gm_wil as $key => $value) {
                    // determine estWil value based on key
                    if ($key == 1) {
                        $estWil = 'WIL-I';
                    } elseif ($key == 2) {
                        $estWil = 'WIL-II';
                    } elseif ($key == 3) {
                        $estWil = 'WIL-III';
                    } elseif ($key == 4) {
                        $estWil = 'WIL-IV';
                    } elseif ($key == 5) {
                        $estWil = 'WIL-V';
                    } elseif ($key == 6) {
                        $estWil = 'WIL-VI';
                    } elseif ($key == 7) {
                        $estWil = 'WIL-VII';
                    } elseif ($key == 8) {
                        $estWil = 'WIL-VIII';
                    }

                    // get nama value from queryAsisten1
                    $namaGM = '-';
                    foreach ($queryAsisten1 as $asisten) {
                        if ($asisten['est'] == $estWil && $asisten['afd'] == 'GM') {
                            $namaGM = $asisten['nama'];
                            break; // stop searching once we find the matching asisten
                        }
                    }

                    // add the current skor_gm_wil value and namaGM value to GmSkorWil array
                    $GmSkorWil[] = [
                        'total_brondolan' => $value['total_brondolan'],
                        'total_karung' => $value['total_karung'],
                        'total_buah_tinggal' => $value['total_buah_tinggal'],
                        'total_restan' => $value['total_restan'],
                        'blok' => $value['blok'],
                        'skor' => $value['skor'],
                        'est' => $estWil,
                        'afd' => 'GM',
                        'namaGM' => $namaGM,
                    ];
                }

                $GmSkorWil = array_values($GmSkorWil);

                $sum_wil_blok = 0;
                $sum_wil_brondolan = 0;
                $sum_wil_karung = 0;
                $sum_wil_buah_tinggal = 0;
                $sum_wil_restan = 0;

                foreach ($skor_gm_wil as $key => $value) {
                    $sum_wil_blok += $value['blok'];
                    $sum_wil_brondolan += $value['total_brondolan'];
                    $sum_wil_karung += $value['total_karung'];
                    $sum_wil_buah_tinggal += $value['total_buah_tinggal'];
                    $sum_wil_restan += $value['total_restan'];
                }

                $skor_total_wil_brondol = $sum_wil_blok == 0 ? $sum_wil_brondolan : round($sum_wil_brondolan / $sum_wil_blok, 2);
                $skor_total_wil_karung = $sum_wil_blok == 0 ? $sum_wil_karung : round($sum_wil_karung / $sum_wil_blok, 2);
                $skor_total_wil_buah_tinggal = $sum_wil_blok == 0 ? $sum_wil_buah_tinggal : round($sum_wil_buah_tinggal / $sum_wil_blok, 2);
                $skor_total_wil_restan = $sum_wil_blok == 0 ? $sum_wil_restan : round($sum_wil_restan / $sum_wil_blok, 2);

                $skor_rh = [];
                foreach ($queryReg as $key => $value) {
                    if ($value == 1) {
                        $est = 'REG-I';
                    } elseif ($value == 2) {
                        $est = 'REG-II';
                    } else {
                        $est = 'REG-III';
                    }
                    foreach ($queryAsisten as $key2 => $value2) {
                        if ($value2->est == $est && $value2->afd == 'RH') {
                            $skor_rh[$value]['nama'] = $value2->nama;
                        }
                    }
                    if (empty($skor_rh[$value]['nama'])) {
                        $skor_rh[$value]['nama'] = '-';
                    }
                    $skor_rh[$value]['skor'] = skorBRDsidak($skor_total_wil_brondol) + skorKRsidak($skor_total_wil_karung) + skorBHsidak($skor_total_wil_buah_tinggal) + skorRSsidak($skor_total_wil_restan);
                }

                foreach ($list_all_will as $key => $value) {
                    array_multisort(array_column($list_all_will[$key], 'skor'), SORT_DESC, $list_all_will[$key]);
                    $rank = 1;
                    foreach ($value as $key1 => $value1) {
                        foreach ($value1 as $key2 => $value2) {
                            $list_all_will[$key][$key1]['rank'] = $rank;
                        }
                        $rank++;
                    }
                    array_multisort(array_column($list_all_will[$key], 'est_afd'), SORT_ASC, $list_all_will[$key]);
                }
                // $list_all_will = array();
                // foreach ($dataSkorAkhirPerWil as $key => $value) {
                //     $inc = 0;
                //     foreach ($value as $key2 => $value2) {
                //         foreach ($value2 as $key3 => $value3) {
                //             $list_all_will[$key][$inc]['est'] = $key2;
                //             $list_all_will[$key][$inc]['afd'] = $key3;
                //             $list_all_will[$key][$inc]['skor'] = $value3['skore_akhir'];
                //             $list_all_will[$key][$inc]['nama'] = '-';
                //             $list_all_will[$key][$inc]['rank'] = '-';
                //             $inc++;
                //         }
                //     }
                // }

                // foreach ($list_all_will as $key1 => $value1) {
                //     $filtered_subarray = array_filter($value1, function ($element) {
                //         return $element['skor'] != '-';
                //     });
                //     $rank = 1;
                //     foreach ($filtered_subarray as $key2 => $value2) {
                //         $filtered_subarray[$key2]['rank'] = $rank;
                //         $rank++;
                //     }
                //     $list_all_will[$key1] = $filtered_subarray;
                // }

                $list_all_est = [];
                foreach ($dataSkorAkhirPerWilEst as $key => $value) {
                    $inc = 0;
                    foreach ($value as $key2 => $value2) {
                        $list_all_est[$key][$inc]['est'] = $key2;
                        $list_all_est[$key][$inc]['skor'] = $value2['skor_akhir'];
                        $list_all_est[$key][$inc]['EM'] = 'EM';
                        foreach ($queryAsisten as $key4 => $value4) {
                            if ($value4->est == $key2 && $value4->afd == 'EM') {
                                $list_all_est[$key][$inc]['nama'] = $value4->nama;
                            }
                        }
                        if (empty($list_all_est[$key][$inc]['nama'])) {
                            $list_all_est[$key][$inc]['nama'] = '-';
                        }
                        $inc++;
                    }
                }

                foreach ($list_all_est as $key => $value) {
                    array_multisort(array_column($list_all_est[$key], 'skor'), SORT_DESC, $list_all_est[$key]);
                    $rank = 1;
                    foreach ($value as $key1 => $value1) {
                        foreach ($value1 as $key2 => $value2) {
                            $list_all_est[$key][$key1]['rank'] = $rank;
                        }
                        $rank++;
                    }
                    array_multisort(array_column($list_all_est[$key], 'est'), SORT_ASC, $list_all_est[$key]);
                }

                // dd($list_all_est);
                // dd($list_all_est);

                //untuk chart!!!
                foreach ($queryGroup as $key => $value) {
                    $sum_bt_tph = 0;
                    $skor_brd = 0;
                    $listBlokPerAfd = [];
                    foreach ($value as $val) {
                        if (!in_array($val->est . ' ' . $val->afd . ' ' . $val->blok, $listBlokPerAfd)) {
                            $listBlokPerAfd[] = $val->est . ' ' . $val->afd . ' ' . $val->blok;
                        }
                        $jum_blok = count($listBlokPerAfd);
                        $sum_bt_tph += $val->bt_tph;
                    }
                    $skor_brd = round($sum_bt_tph / $jum_blok, 2);
                    $arrBtTPHperEst[$key] = $skor_brd;
                }
                // dd($arrBtTPHperEst);
                //sebelum di masukan ke aray harus looping karung berisi brondolan untuk mengambil datanya 2 lapis
                foreach ($queryGroup as $key => $value) {
                    $sum_jum_karung = 0;
                    $skor_brd = 0;
                    $listBlokPerAfd = [];
                    foreach ($value as $val) {
                        if (!in_array($val->est . ' ' . $val->afd . ' ' . $val->blok, $listBlokPerAfd)) {
                            $listBlokPerAfd[] = $val->est . ' ' . $val->afd . ' ' . $val->blok;
                        }
                        $jum_blok = count($listBlokPerAfd);
                        $sum_jum_karung += $val->jum_karung;
                    }
                    $skor_brd = round($sum_jum_karung / $jum_blok, 2);
                    $arrKRest[$key] = $skor_brd;
                }
                //looping buah tinggal
                foreach ($queryGroup as $key => $value) {
                    $sum_buah_tinggal = 0;
                    $skor_brd = 0;
                    $listBlokPerAfd = [];
                    foreach ($value as $val) {
                        if (!in_array($val->est . ' ' . $val->afd . ' ' . $val->blok, $listBlokPerAfd)) {
                            $listBlokPerAfd[] = $val->est . ' ' . $val->afd . ' ' . $val->blok;
                        }
                        $jum_blok = count($listBlokPerAfd);
                        $sum_buah_tinggal += $val->buah_tinggal;
                    }
                    $skor_brd = round($sum_buah_tinggal / $jum_blok, 2);
                    $arrBHest[$key] = $skor_brd;
                }
                //looping buah restrant tidak di  laporkan
                foreach ($queryGroup as $key => $value) {
                    $sum_restan_unreported = 0;
                    $skor_brd = 0;
                    $listBlokPerAfd = [];
                    foreach ($value as $val) {
                        if (!in_array($val->est . ' ' . $val->afd . ' ' . $val->blok, $listBlokPerAfd)) {
                            $listBlokPerAfd[] = $val->est . ' ' . $val->afd . ' ' . $val->blok;
                        }
                        $jum_blok = count($listBlokPerAfd);
                        $sum_restan_unreported += $val->restan_unreported;
                    }
                    $skor_brd = round($sum_restan_unreported / $jum_blok, 2);
                    $arrRSest[$key] = $skor_brd;
                }

                //query untuk wilayah menambhakna data
                //jadikan dulu query dalam group memakai data querry untuk wilayah
                $queryGroupWil = $query->groupBy(function ($item) {
                    return $item->wil;
                });

                // dd($queryGroupWil);
                foreach ($queryGroupWil as $key => $value) {
                    $sum_bt_tph = 0;
                    foreach ($value as $key2 => $val) {
                        $sum_bt_tph += $val->bt_tph;
                    }
                    // if ($key == 1 || $key == 2 || $key == 3) {
                    if ($skor_gm_wil[$key]['blok'] != 0) {
                        $arrBtTPHperWil[$key] = round($sum_bt_tph / $skor_gm_wil[$key]['blok'], 2);
                    } else {
                        $arrBtTPHperWil[$key] = 0;
                    }
                    // }
                }

                //sebelum di masukan ke aray harus looping karung berisi brondolan untuk mengambil datanya 2 lapis
                foreach ($queryGroupWil as $key => $value) {
                    $sum_jum_karung = 0;
                    foreach ($value as $key2 => $vale) {
                        $sum_jum_karung += $vale->jum_karung;
                    }
                    if ($key == 1 || $key == 2 || $key == 3) {
                        if ($skor_gm_wil[$key]['blok'] != 0) {
                            $arrKRestWil[$key] = round($sum_jum_karung / $skor_gm_wil[$key]['blok'], 2);
                        } else {
                            $arrKRestWil[$key] = 0;
                        }
                    }
                }
                //looping buah tinggal
                foreach ($queryGroupWil as $key => $value) {
                    $sum_buah_tinggal = 0;
                    foreach ($value as $key2 => $val2) {
                        $sum_buah_tinggal += $val2->buah_tinggal;
                    }
                    if ($key == 1 || $key == 2 || $key == 3) {
                        if ($skor_gm_wil[$key]['blok'] != 0) {
                            $arrBHestWil[$key] = round($sum_buah_tinggal / $skor_gm_wil[$key]['blok'], 2);
                        } else {
                            $arrBHestWil[$key] = 0;
                        }
                    }
                }
                foreach ($queryGroupWil as $key => $value) {
                    $sum_restan_unreported = 0;
                    foreach ($value as $key2 => $val3) {
                        $sum_restan_unreported += $val3->restan_unreported;
                    }
                    if ($key == 1 || $key == 2 || $key == 3) {
                        if ($skor_gm_wil[$key]['blok'] != 0) {
                            $arrRestWill[$key] = round($sum_restan_unreported / $skor_gm_wil[$key]['blok'], 2);
                        } else {
                            $arrRestWill[$key] = 0;
                        }
                    }
                }
            }
            // dd($arrBtTPHperWil, $arrKRestWil, $arrBHestWil, $arrRestWill);
            // dd($queryGroup);

            //bagian plasma cuy
            $QueryPlasmaSIdak = DB::connection('mysql2')
                ->table('sidak_tph')
                ->select('sidak_tph.*', DB::raw('DATE_FORMAT(sidak_tph.datetime, "%M") as bulan'), DB::raw('DATE_FORMAT(sidak_tph.datetime, "%Y") as tahun'))
                ->whereBetween('sidak_tph.datetime', [$firstWeek, $lastWeek])
                ->get();
            $QueryPlasmaSIdak = $QueryPlasmaSIdak->groupBy(['est', 'afd']);
            $QueryPlasmaSIdak = json_decode($QueryPlasmaSIdak, true);
            // dd($QueryPlasmaSIdak['Plasma1']);
            $getPlasma = 'Plasma' . $regSidak;
            $queryEstePla = DB::connection('mysql2')
                ->table('estate')
                ->select('estate.*')
                ->whereIn('estate.est', [$getPlasma])
                ->join('wil', 'wil.id', '=', 'estate.wil')
                ->where('wil.regional', $regSidak)
                ->get();
            $queryEstePla = json_decode($queryEstePla, true);

            $queryAsisten = DB::connection('mysql2')
                ->Table('asisten_qc')
                ->get();
            $queryAsisten = json_decode($queryAsisten, true);

            $PlasmaAfd = DB::connection('mysql2')
                ->table('afdeling')
                ->select('afdeling.id', 'afdeling.nama', 'estate.est') //buat mengambil data di estate db dan willayah db
                ->join('estate', 'estate.id', '=', 'afdeling.estate') //kemudian di join untuk mengambil est perwilayah
                ->get();
            $PlasmaAfd = json_decode($PlasmaAfd, true);

            $SidakTPHPlA = [];
            foreach ($QueryPlasmaSIdak as $key => $value) {
                foreach ($value as $key2 => $value2) {
                    foreach ($value2 as $key3 => $value3) {
                        $SidakTPHPlA[$key][$key2][$key3] = $value3;
                    }
                }
            }
            // dd($SidakTPHPlA);
            $defPLASidak = [];
            foreach ($queryEstePla as $est) {
                // dd($est);
                foreach ($queryAfd as $afd) {
                    // dd($afd);
                    if ($est['est'] == $afd['est']) {
                        $defPLASidak[$est['est']][$afd['nama']]['null'] = 0;
                    }
                }
            }

            foreach ($defPLASidak as $key => $estValue) {
                foreach ($estValue as $monthKey => $monthValue) {
                    $mergedValues = [];
                    foreach ($SidakTPHPlA as $dataKey => $dataValue) {
                        if ($dataKey == $key && isset($dataValue[$monthKey])) {
                            $mergedValues = array_merge($mergedValues, $dataValue[$monthKey]);
                        }
                    }
                    $defPLASidak[$key][$monthKey] = $mergedValues;
                }
            }

            $arrPlasma = [];
            foreach ($defPLASidak as $key => $value) {
                if (!empty($value)) {
                    $jum_blokPla = 0;
                    $sum_tphPla = 0;
                    $sum_karungPla = 0;
                    $sum_buahPla = 0;
                    $sum_restantPla = 0;

                    $skor_tphPla = 0;
                    $skor_karungPla = 0;
                    $skor_buahPla = 0;
                    $skor_restanPla = 0;
                    $skoreTotalPla = 0;
                    foreach ($value as $key1 => $value1) {
                        if (!empty($value1)) {
                            $jum_blok = 0;
                            $sum_bt_tph = 0;
                            $sum_bt_jalan = 0;
                            $sum_bt_bin = 0;
                            $sum_all = 0;
                            $sum_all_karung = 0;
                            $sum_jum_karung = 0;
                            $sum_buah_tinggal = 0;
                            $sum_all_bt_tgl = 0;
                            $sum_restan_unreported = 0;
                            $sum_all_restan_unreported = 0;
                            $listBlokPerAfd = [];
                            foreach ($value1 as $key2 => $value2) {
                                if (is_array($value2)) {
                                    $blok = $value2['est'] . ' ' . $value2['afd'] . ' ' . $value2['blok'];

                                    if (!in_array($blok, $listBlokPerAfd)) {
                                        array_push($listBlokPerAfd, $blok);
                                    }

                                    // $jum_blok = count(float)($listBlokPerAfd);
                                    $jum_blok = count($listBlokPerAfd);

                                    $sum_bt_tph += $value2['bt_tph'];
                                    $sum_bt_jalan += $value2['bt_jalan'];
                                    $sum_bt_bin += $value2['bt_bin'];

                                    $sum_jum_karung += $value2['jum_karung'];
                                    $sum_buah_tinggal += $value2['buah_tinggal'];
                                    $sum_restan_unreported += $value2['restan_unreported'];
                                }
                            }
                            //change value 3to float type
                            $sum_all = $sum_bt_tph + $sum_bt_jalan + $sum_bt_bin;
                            $sum_all_karung = $sum_jum_karung;
                            $sum_all_bt_tgl = $sum_buah_tinggal;
                            $sum_all_restan_unreported = $sum_restan_unreported;
                            if ($jum_blok == 0) {
                                $skor_brd = 0;
                                $skor_kr = 0;
                                $skor_buahtgl = 0;
                                $skor_restan = 0;
                            } else {
                                $skor_brd = round($sum_all / $jum_blok, 1);
                                $skor_kr = round($sum_all_karung / $jum_blok, 1);
                                $skor_buahtgl = round($sum_all_bt_tgl / $jum_blok, 1);
                                $skor_restan = round($sum_all_restan_unreported / $jum_blok, 1);
                            }

                            $skoreTotal = skorBRDsidak($skor_brd) + skorKRsidak($skor_kr) + skorBHsidak($skor_buahtgl) + skorRSsidak($skor_restan);

                            $arrPlasma[$key][$key1]['karung_tes'] = $sum_all_karung;
                            $arrPlasma[$key][$key1]['tph_test'] = $sum_all;
                            $arrPlasma[$key][$key1]['buah_test'] = $sum_all_bt_tgl;
                            $arrPlasma[$key][$key1]['restant_tes'] = $sum_all_restan_unreported;

                            $arrPlasma[$key][$key1]['jumlah_blok'] = $jum_blok;

                            $arrPlasma[$key][$key1]['brd_blok'] = skorBRDsidak($skor_brd);
                            $arrPlasma[$key][$key1]['kr_blok'] = skorKRsidak($skor_kr);
                            $arrPlasma[$key][$key1]['buah_blok'] = skorBHsidak($skor_buahtgl);
                            $arrPlasma[$key][$key1]['restan_blok'] = skorRSsidak($skor_restan);
                            $arrPlasma[$key][$key1]['skorWil'] = $skoreTotal;

                            $jum_blokPla += $jum_blok;
                            $sum_karungPla += $sum_all_karung;
                            $sum_restantPla += $sum_all_restan_unreported;
                            $sum_tphPla += $sum_all;
                            $sum_buahPla += $sum_all_bt_tgl;
                        } else {
                            $arrPlasma[$key][$key1]['karung_tes'] = 0;
                            $arrPlasma[$key][$key1]['tph_test'] = 0;
                            $arrPlasma[$key][$key1]['buah_test'] = 0;
                            $arrPlasma[$key][$key1]['restant_tes'] = 0;

                            $arrPlasma[$key][$key1]['jumlah_blok'] = 0;

                            $arrPlasma[$key][$key1]['brd_blok'] = 0;
                            $arrPlasma[$key][$key1]['kr_blok'] = 0;
                            $arrPlasma[$key][$key1]['buah_blok'] = 0;
                            $arrPlasma[$key][$key1]['restan_blok'] = 0;
                            $arrPlasma[$key][$key1]['skorWil'] = 0;
                        }
                    }

                    if ($jum_blokPla != 0) {
                        $skor_tphPla = round($sum_tphPla / $jum_blokPla, 2);
                        $skor_karungPla = round($sum_karungPla / $jum_blokPla, 2);
                        $skor_buahPla = round($sum_buahPla / $jum_blokPla, 2);
                        $skor_restanPla = round($sum_restantPla / $jum_blokPla, 2);
                    } else {
                        $skor_tphPla = 0;
                        $skor_karungPla = 0;
                        $skor_buahPla = 0;
                        $skor_restanPla = 0;
                    }

                    $skoreTotalPla = skorBRDsidak($skor_tphPla) + skorKRsidak($skor_karungPla) + skorBHsidak($skor_buahPla) + skorRSsidak($skor_restanPla);
                    $arrPlasma[$key]['karung_tes'] = $sum_karungPla;
                    $arrPlasma[$key]['tph_test'] = $sum_tphPla;
                    $arrPlasma[$key]['buah_test'] = $sum_buahPla;
                    $arrPlasma[$key]['restant_tes'] = $sum_restantPla;

                    $arrPlasma[$key]['jumlah_blok'] = $jum_blokPla;

                    $arrPlasma[$key]['brd_blok'] = skorBRDsidak($skor_tphPla);
                    $arrPlasma[$key]['kr_blok'] = skorKRsidak($skor_karungPla);
                    $arrPlasma[$key]['buah_blok'] = skorBHsidak($skor_buahPla);
                    $arrPlasma[$key]['restan_blok'] = skorRSsidak($skor_restanPla);
                    $arrPlasma[$key]['SkorPlasma'] = $skoreTotalPla;
                } else {
                    $arrPlasma[$key]['karung_tes'] = 0;
                    $arrPlasma[$key]['tph_test'] = 0;
                    $arrPlasma[$key]['buah_test'] = 0;
                    $arrPlasma[$key]['restant_tes'] = 0;

                    $arrPlasma[$key]['jumlah_blok'] = 0;

                    $arrPlasma[$key]['brd_blok'] = 0;
                    $arrPlasma[$key]['kr_blok'] = 0;
                    $arrPlasma[$key]['buah_blok'] = 0;
                    $arrPlasma[$key]['restan_blok'] = 0;
                    $arrPlasma[$key]['SkorPlasma'] = 0;
                }
            }
            // dd($arrPlasma);
            foreach ($arrPlasma as $key1 => $estates) {
                if (is_array($estates)) {
                    // $sortedData = array();
                    $sortedDataEst = [];
                    foreach ($estates as $estateName => $data) {
                        // dd($data);
                        if (is_array($data)) {
                            $sortedDataEst[] = [
                                'key1' => $key1,
                                'estateName' => $estateName,
                                'data' => $data,
                            ];
                        }
                    }
                    usort($sortedDataEst, function ($a, $b) {
                        return $b['data']['skorWil'] - $a['data']['skorWil'];
                    });
                    $rank = 1;
                    foreach ($sortedDataEst as $sortedest) {
                        $arrPlasma[$key1][$sortedest['estateName']]['rank'] = $rank;
                        $rank++;
                    }
                    unset($sortedDataEst);
                }
            }
            // dd($arrPlasma);

            $PlasmaWIl = [];
            foreach ($arrPlasma as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $key1 => $value1) {
                        if (is_array($value1)) {
                            // dd($value1);
                            $inc = 0;
                            $est = $key;
                            $skor = $value1['skorWil'];
                            // dd($skor);
                            $EM = $key1;

                            $rank = $value1['rank'];
                            // $rank = $value1['rank'];
                            $nama = '-';
                            foreach ($queryAsisten as $key4 => $value4) {
                                if (is_array($value4) && $value4['est'] == $est && $value4['afd'] == $EM) {
                                    $nama = $value4['nama'];
                                    break;
                                }
                            }
                            $PlasmaWIl[] = [
                                'est' => $est,
                                'afd' => $EM,
                                'nama' => $nama,
                                'skor' => $skor,
                                'rank' => $rank,
                            ];
                            $inc++;
                        }
                    }
                }
            }

            $PlasmaWIl = array_values($PlasmaWIl);

            $PlasmaEM = [];
            $NamaEm = '-';
            foreach ($arrPlasma as $key => $value) {
                if (is_array($value)) {
                    $inc = 0;
                    $est = $key;
                    $skor = $value['SkorPlasma'];
                    $EM = 'EM';
                    // dd($value);
                    foreach ($queryAsisten as $key4 => $value4) {
                        if (is_array($value4) && $value4['est'] == $est && $value4['afd'] == $EM) {
                            $NamaEm = $value4['nama'];
                        }
                    }
                    $inc++;
                }
            }

            if (isset($EM)) {
                $PlasmaEM[] = [
                    'est' => $est,
                    'afd' => $EM,
                    'namaEM' => $NamaEm,
                    'Skor' => $skor,
                ];
            }

            $PlasmaEM = array_values($PlasmaEM);

            $plasmaGM = [];
            $namaGM = '-';
            foreach ($arrPlasma as $key => $value) {
                if (is_array($value)) {
                    $inc = 0;
                    $est = $key;
                    $skor = $value['SkorPlasma'];
                    $GM = 'GM';
                    // dd($value);
                    foreach ($queryAsisten as $key4 => $value4) {
                        if (is_array($value4) && $value4['est'] == $est && $value4['afd'] == $GM) {
                            $namaGM = $value4['nama'];
                        }
                    }
                    $inc++;
                }
            }

            if (isset($GM)) {
                $plasmaGM[] = [
                    'est' => $est,
                    'afd' => $GM,
                    'namaGM' => $namaGM,
                    'Skor' => $skor,
                ];
            }

            $plasmaGM = array_values($plasmaGM);
            //masukan semua yang sudah selese di olah di atas ke dalam vaiabel terserah kemudian masukan kedalam aray
            //karena chart hanya bisa menerima inputan json
            $queryWilChart = DB::connection('mysql2')
                ->table('wil')
                ->whereIn('regional', [$regSidak])
                ->pluck('nama');

            $arrView = [];

            $arrView['list_estate'] = $queryEst;
            $arrView['list_wilayah'] = $queryWill;
            $arrView['list_wilayah2'] = $queryWilChart;
            // $arrView['restant'] = $dataSkorAwalRestant;

            $arrView['list_all_wil'] = $list_all_will;
            $arrView['list_all_est'] = $list_all_est;
            $arrView['list_skor_gm'] = $skor_gm_wil;
            $arrView['list_skor_rh'] = $skor_rh;
            $arrView['PlasmaWIl'] = $PlasmaWIl;
            $arrView['PlasmaEM'] = $PlasmaEM;
            $arrView['plasmaGM'] = $plasmaGM;
            $arrView['list_skor_gmNew'] = $GmSkorWil;
            // $arrView['karung'] = $dataSkorAwalKr;
            // $arrView['buah'] = $dataSkorAwalBuah;
            // // dd($queryEst);
            // dd($arrBtTPHperEst);
            $keysToRemove = ['SRE', 'LDE', 'SKE'];

            // Loop through the array and remove the elements with the specified keys
            foreach ($keysToRemove as $key) {
                unset($arrBtTPHperEst[$key]);
                unset($arrKRest[$key]);
                unset($arrBHest[$key]);
                unset($arrRSest[$key]);
            }
            // dd($arrBtTPHperEst, $arrKRest, $arrBHest);

            // dd($arrKRestWil);
            // masukan ke array data penjumlahan dari estate
            $arrView['val_bt_tph'] = $arrBtTPHperEst; //data jsen brondolan tinggal di tph
            $arrView['val_kr_tph'] = $arrKRest; //data jsen karung yang berisi buah
            $arrView['val_bh_tph'] = $arrBHest; //data jsen buah yang tinggal
            $arrView['val_rs_tph'] = $arrRSest; //data jsen restan yang tidak dilaporkan
            //masukan ke array data penjumlahan dari wilayah
            $arrView['val_kr_tph_wil'] = $arrKRestWil; //data jsen karung yang berisi buah
            $arrView['val_bt_tph_wil'] = $arrBtTPHperWil; //data jsen brondolan tinggal di tph
            $arrView['val_bh_tph_wil'] = $arrBHestWil; //data jsen buah yang tinggal
            $arrView['val_rs_tph_wil'] = $arrRestWill; //data jsen restan yang tidak dilaporkan
            // dd($arrBtTPHperEst);
            echo json_encode($arrView); //di decode ke dalam bentuk json dalam vaiavel arrview yang dapat menampung banyak isi array
            exit();
        }
        // dd($queryEst);
        // dd($arrBtTPHperEst);

        // }
    }

    public function getBtTphMonth(Request $request)
    {
        $regSidak = $request->get('reg');
        $monthSidak = $request->get('month');
        $queryWill = DB::connection('mysql2')
            ->table('wil')
            ->whereIn('regional', [$regSidak])
            ->get();
        $queryReg = DB::connection('mysql2')
            ->table('wil')
            ->whereIn('regional', [$regSidak])
            ->pluck('regional');
        $queryReg2 = DB::connection('mysql2')
            ->table('wil')
            ->whereIn('regional', [$regSidak])
            ->pluck('id')
            ->toArray();

        // dapatkan data estate dari table estate dengan wilayah 1 , 2 , 3
        $queryEst = DB::connection('mysql2')
            ->table('estate')
            // ->whereNotIn('estate.est', ['PLASMA'])
            ->whereIn('wil', $queryReg2)
            ->get();
        // dd($queryEst);
        $queryEste = DB::connection('mysql2')
            ->table('estate')
            ->whereNotIn('estate.est', ['PLASMA', 'SRE', 'LDE', 'SKE'])
            ->whereIn('wil', $queryReg2)
            ->get();
        $queryEste = $queryEste->groupBy(function ($item) {
            return $item->wil;
        });

        $queryEste = json_decode($queryEste, true);

        // dd($queryEst);
        $queryAfd = DB::connection('mysql2')
            ->Table('afdeling')
            ->select('afdeling.id', 'afdeling.nama', 'estate.est') //buat mengambil data di estate db dan willayah db
            ->join('estate', 'estate.id', '=', 'afdeling.estate') //kemudian di join untuk mengambil est perwilayah
            ->whereNotIn('estate.est', ['SRE', 'LDE', 'SKE'])
            ->get();

        $queryAfd = json_decode($queryAfd, true);

        //array untuk tampung nilai bt tph per estate dari table bt_jalan & bt_tph dll
        $arrBtTPHperEst = []; //table dari brondolan di buat jadi array agar bisa di parse ke json
        $arrKRest = []; //table dari jum_jkarung di buat jadi array agar bisa di parse ke json
        $arrBHest = []; //table dari Buah di buat jadi array agar bisa di parse ke json
        $arrRSest = []; //table array untuk buah restant tidak di laporkan

        ///array untuk table nya

        $dataSkorAwal = [];

        $list_all_will = [];

        //memberi nilai 0 default kesemua estate
        foreach ($queryEst as $key => $value) {
            $arrBtTPHperEst[$value->est] = 0; //est mengambil value dari table estate
            $arrKRest[$value->est] = 0;
            $arrBHest[$value->est] = 0;
            $arrRSest[$value->est] = 0;
        }
        // dd($queryEst);
        foreach ($queryWill as $key => $value) {
            $arrBtTPHperWil[$value->nama] = 0; //est mengambil value dari table estate
            $arrKRestWil[$value->nama] = 0;
            $arrBHestWil[$value->nama] = 0;
            $arrRestWill[$value->nama] = 0;
        }

        // dd($firstWeek, $lastWeek);
        $query = DB::connection('mysql2')
            ->Table('sidak_tph')
            ->select('sidak_tph.*', 'estate.wil') //buat mengambil data di estate db dan willayah db
            ->whereIn('estate.wil', $queryReg2)
            ->join('estate', 'estate.est', '=', 'sidak_tph.est') //kemudian di join untuk mengambil est perwilayah
            ->where('sidak_tph.datetime', 'like', '%' . $monthSidak . '%')
            // ->where('sidak_tph.datetime', 'LIKE', '%' . $request->$firstDate . '%')
            ->get();
        //     ->get();
        $queryAFD = DB::connection('mysql2')
            ->Table('sidak_tph')
            ->select('sidak_tph.*', 'estate.wil') //buat mengambil data di estate db dan willayah db
            ->whereIn('estate.wil', $queryReg2)
            ->join('estate', 'estate.est', '=', 'sidak_tph.est') //kemudian di join untuk mengambil est perwilayah
            ->where('sidak_tph.datetime', 'like', '%' . $monthSidak . '%')
            // ->where('sidak_tph.datetime', 'LIKE', '%' . $request->$firstDate . '%')
            ->get();
        // dd($query);
        $queryAsisten = DB::connection('mysql2')
            ->Table('asisten_qc')
            ->get();

        $dataAfdEst = [];

        $querySidak = DB::connection('mysql2')
            ->table('sidak_tph')
            ->where('sidak_tph.datetime', 'like', '%' . $monthSidak . '%')
            // ->whereBetween('sidak_tph.datetime', ['2023-01-23', '202-12-25'])
            ->get();
        $querySidak = json_decode($querySidak, true);

        $allBlok = $query->groupBy(function ($item) {
            return $item->blok;
        });

        if (!empty($query && $queryAFD && $querySidak)) {
            $queryGroup = $queryAFD->groupBy(function ($item) {
                return $item->est;
            });
            // dd($queryGroup);
            $queryWi = DB::connection('mysql2')
                ->table('estate')
                ->whereIn('wil', $queryReg2)
                ->get();

            $queryWill = $queryWi->groupBy(function ($item) {
                return $item->nama;
            });

            $queryWill2 = $queryWi->groupBy(function ($item) {
                return $item->nama;
            });

            //untuk table!!
            // store wil -> est -> afd
            // menyimpan array nested dari  wil -> est -> afd
            foreach ($queryEste as $key => $value) {
                foreach ($value as $key2 => $value2) {
                    foreach ($queryAfd as $key3 => $value3) {
                        $est = $value2['est'];
                        $afd = $value3['nama'];
                        if ($value2['est'] == $value3['est']) {
                            foreach ($querySidak as $key4 => $value4) {
                                if ($est == $value4['est'] && $afd == $value4['afd']) {
                                    $dataAfdEst[$est][$afd][] = $value4;
                                } else {
                                    $dataAfdEst[$est][$afd]['null'] = 0;
                                }
                            }
                        }
                    }
                }
            }

            foreach ($dataAfdEst as $key => $value) {
                foreach ($value as $key2 => $value2) {
                    foreach ($value2 as $key3 => $value3) {
                        unset($dataAfdEst[$key][$key2]['null']);
                        if (empty($dataAfdEst[$key][$key2])) {
                            $dataAfdEst[$key][$key2] = 0;
                        }
                    }
                }
            }

            $listBlokPerAfd = [];
            foreach ($dataAfdEst as $key => $value) {
                foreach ($value as $key2 => $value2) {
                    if (is_array($value2)) {
                        foreach ($value2 as $key3 => $value3) {
                            // dd($key3);
                            foreach ($allBlok as $key4 => $value4) {
                                if ($value3['blok'] == $key4) {
                                    $listBlokPerAfd[$key][$key2][$key3] = $value4;
                                }
                            }
                        }
                    }
                }

                // //menghitung data skor untuk brd/blok
                foreach ($dataAfdEst as $key => $value) {
                    foreach ($value as $key2 => $value2) {
                        if (is_array($value2)) {
                            $jum_blok = 0;
                            $sum_bt_tph = 0;
                            $sum_bt_jalan = 0;
                            $sum_bt_bin = 0;
                            $sum_all = 0;
                            $sum_all_karung = 0;
                            $sum_jum_karung = 0;
                            $sum_buah_tinggal = 0;
                            $sum_all_bt_tgl = 0;
                            $sum_restan_unreported = 0;
                            $sum_all_restan_unreported = 0;
                            $listBlokPerAfd = [];
                            foreach ($value2 as $key3 => $value3) {
                                if (is_array($value3)) {
                                    $blok = $value3['est'] . ' ' . $value3['afd'] . ' ' . $value3['blok'];

                                    if (!in_array($blok, $listBlokPerAfd)) {
                                        array_push($listBlokPerAfd, $blok);
                                    }

                                    // $jum_blok = count(float)($listBlokPerAfd);
                                    $jum_blok = count($listBlokPerAfd);

                                    $sum_bt_tph += $value3['bt_tph'];
                                    $sum_bt_jalan += $value3['bt_jalan'];
                                    $sum_bt_bin += $value3['bt_bin'];

                                    $sum_jum_karung += $value3['jum_karung'];
                                    $sum_buah_tinggal += $value3['buah_tinggal'];
                                    $sum_restan_unreported += $value3['restan_unreported'];
                                }
                            }
                            //change value 3to float type
                            $sum_all = $sum_bt_tph + $sum_bt_jalan + $sum_bt_bin;
                            $sum_all_karung = $sum_jum_karung;
                            $sum_all_bt_tgl = $sum_buah_tinggal;
                            $sum_all_restan_unreported = $sum_restan_unreported;
                            $skor_brd = round($sum_all / $jum_blok, 1);
                            // dd($skor_brd);
                            $skor_kr = round($sum_all_karung / $jum_blok, 1);
                            $skor_buahtgl = round($sum_all_bt_tgl / $jum_blok, 1);
                            $skor_restan = round($sum_all_restan_unreported / $jum_blok, 1);

                            $skoreTotal = skorBRDsidak($skor_brd) + skorKRsidak($skor_kr) + skorBHsidak($skor_buahtgl) + skorRSsidak($skor_restan);

                            $dataSkorAwal[$key][$key2]['karung_tes'] = $sum_all_karung;
                            $dataSkorAwal[$key][$key2]['tph_test'] = $sum_all;
                            $dataSkorAwal[$key][$key2]['buah_test'] = $sum_all_bt_tgl;
                            $dataSkorAwal[$key][$key2]['restant_tes'] = $sum_all_restan_unreported;

                            $dataSkorAwal[$key][$key2]['jumlah_blok'] = $jum_blok;

                            $dataSkorAwal[$key][$key2]['brd_blok'] = skorBRDsidak($skor_brd);
                            // $dataSkorAwal[$key][$key2]['brd'] = skorBRDsidak($skor_brd);
                            $dataSkorAwal[$key][$key2]['kr_blok'] = skorKRsidak($skor_kr);
                            $dataSkorAwal[$key][$key2]['buah_blok'] = skorBHsidak($skor_buahtgl);
                            $dataSkorAwal[$key][$key2]['restan_blok'] = skorRSsidak($skor_restan);
                            $dataSkorAwal[$key][$key2]['skore_akhir'] = $skoreTotal;
                        } else {
                            $dataSkorAwal[$key][$key2]['karung_tes'] = 0;
                            $dataSkorAwal[$key][$key2]['restant_tes'] = 0;
                            $dataSkorAwal[$key][$key2]['jumlah_blok'] = 0;
                            $dataSkorAwal[$key][$key2]['tph_test'] = 0;
                            $dataSkorAwal[$key][$key2]['buah_test'] = 0;

                            $dataSkorAwal[$key][$key2]['brd_blok'] = 0;
                            $dataSkorAwal[$key][$key2]['kr_blok'] = 0;
                            $dataSkorAwal[$key][$key2]['buah_blok'] = 0;
                            $dataSkorAwal[$key][$key2]['restan_blok'] = 0;
                            $dataSkorAwal[$key][$key2]['skore_akhir'] = 0;
                        }
                    }
                }

                // dd($dataSkorAwal);

                foreach ($dataSkorAwal as $key => $value) {
                    $jum_blok = 0;
                    $jum_all_blok = 0;
                    $sum_all_tph = 0;
                    $sum_tph = 0;
                    $sum_all_karung = 0;
                    $sum_karung = 0;
                    $sum_all_buah = 0;
                    $sum_buah = 0;
                    $sum_all_restant = 0;
                    $sum_restant = 0;
                    foreach ($value as $key2 => $value2) {
                        if (is_array($value2)) {
                            $jum_blok += $value2['jumlah_blok'];
                            $sum_karung += $value2['karung_tes'];
                            $sum_restant += $value2['restant_tes'];
                            $sum_tph += $value2['tph_test'];
                            $sum_buah += $value2['buah_test'];
                        }
                    }
                    $sum_all_tph = $sum_tph;
                    $jum_all_blok = $jum_blok;
                    $sum_all_karung = $sum_karung;
                    $sum_all_buah = $sum_buah;
                    $sum_all_restant = $sum_restant;

                    if ($jum_all_blok != 0) {
                        $skor_tph = round($sum_all_tph / $jum_all_blok, 2);
                        $skor_karung = round($sum_all_karung / $jum_all_blok, 2);
                        $skor_buah = round($sum_all_buah / $jum_all_blok, 2);
                        $skor_restan = round($sum_all_restant / $jum_all_blok, 2);
                    } else {
                        $skor_tph = 0;
                        $skor_karung = 0;
                        $skor_buah = 0;
                        $skor_restan = 0;
                    }

                    $skoreTotal = skorBRDsidak($skor_tph) + skorKRsidak($skor_karung) + skorBHsidak($skor_buah) + skorRSsidak($skor_restan);

                    $dataSkorAwaltest[$key]['total_estate_brondol'] = $sum_all_tph;
                    $dataSkorAwaltest[$key]['total_estate_karung'] = $sum_all_karung;
                    $dataSkorAwaltest[$key]['total_estate_buah_tinggal'] = $sum_all_buah;
                    $dataSkorAwaltest[$key]['total_estate_restan_tinggal'] = $sum_all_restant;
                    $dataSkorAwaltest[$key]['tph'] = skorBRDsidak($skor_tph);
                    $dataSkorAwaltest[$key]['karung'] = skorKRsidak($skor_karung);
                    $dataSkorAwaltest[$key]['buah_tinggal'] = skorBHsidak($skor_buah);
                    $dataSkorAwaltest[$key]['restant'] = skorRSsidak($skor_restan);
                    $dataSkorAwaltest[$key]['total_blokokok'] = $jum_all_blok;
                    $dataSkorAwaltest[$key]['skor_akhir'] = $skoreTotal;
                }
                // dd($dataSkorAwal);

                foreach ($queryEste as $key => $value) {
                    foreach ($value as $key2 => $value2) {
                        foreach ($dataSkorAwal as $key3 => $value3) {
                            if ($value2['est'] == $key3) {
                                $dataSkorAkhirPerWil[$key][$key3] = $value3;
                            }
                        }
                    }
                }

                foreach ($queryEste as $key => $value) {
                    foreach ($value as $key2 => $value2) {
                        foreach ($dataSkorAwaltest as $key3 => $value3) {
                            if ($value2['est'] == $key3) {
                                $dataSkorAkhirPerWilEst[$key][$key3] = $value3;
                            }
                        }
                    }
                }
                // dd($dataSkorAkhirPerWil);
                //menshort nilai masing masing
                $sortList = [];
                foreach ($dataSkorAkhirPerWil as $key => $value) {
                    $inc = 0;
                    foreach ($value as $key2 => $value2) {
                        foreach ($value2 as $key3 => $value3) {
                            $sortList[$key][$key2 . '_' . $key3] = $value3['skore_akhir'];
                            $inc++;
                        }
                    }
                }

                //short list untuk mengurutkan valuenya
                foreach ($sortList as &$value) {
                    arsort($value);
                }
                // dd($sortList);
                $sortListEstate = [];
                foreach ($dataSkorAkhirPerWilEst as $key => $value) {
                    $inc = 0;
                    foreach ($value as $key2 => $value2) {
                        $sortListEstate[$key][$key2] = $value2['skor_akhir'];
                        $inc++;
                    }
                }

                //short list untuk mengurutkan valuenya
                foreach ($sortListEstate as &$value) {
                    arsort($value);
                }

                // dd($sortListEstate);
                foreach ($dataSkorAkhirPerWilEst as $key => $value) {
                    foreach ($value as $key2 => $value2) {
                        foreach ($value2 as $key3 => $value3) {
                        }
                    }
                }

                //menambahkan nilai rank ketia semua total skor sudah di uritkan
                $test = [];
                $listRank = [];
                foreach ($dataSkorAkhirPerWil as $key => $value) {
                    // create an array to store the skore_akhir values
                    $skore_akhir_values = [];
                    foreach ($value as $key2 => $value2) {
                        foreach ($value2 as $key3 => $value3) {
                            $skore_akhir_values[] = $value3['skore_akhir'];
                        }
                    }
                    // sort the skore_akhir values in descending order
                    rsort($skore_akhir_values);
                    // assign ranks to each skore_akhir value
                    foreach ($value as $key2 => $value2) {
                        foreach ($value2 as $key3 => $value3) {
                            $rank = array_search($value3['skore_akhir'], $skore_akhir_values) + 1;
                            $dataSkorAkhirPerWil[$key][$key2][$key3]['rank'] = $rank;
                            $test[$key][] = $value3['skore_akhir'];
                        }
                    }
                }

                // dd($dataSkorAkhirPerWil);
                // perbaiki rank saya berdasarkan skore_akhir di mana jika $value3['skore_akhir'] terkecil merupakan rank 1 dan seterusnya
                $list_all_will = [];
                foreach ($dataSkorAkhirPerWil as $key => $value) {
                    $inc = 0;
                    foreach ($value as $key2 => $value2) {
                        foreach ($value2 as $key3 => $value3) {
                            $list_all_will[$key][$inc]['est_afd'] = $key2 . '_' . $key3;
                            $list_all_will[$key][$inc]['est'] = $key2;
                            $list_all_will[$key][$inc]['afd'] = $key3;
                            $list_all_will[$key][$inc]['skor'] = $value3['skore_akhir'];
                            foreach ($queryAsisten as $key4 => $value4) {
                                if ($value4->est == $key2 && $value4->afd == $key3) {
                                    $list_all_will[$key][$inc]['nama'] = $value4->nama;
                                }
                            }
                            if (empty($list_all_will[$key][$inc]['nama'])) {
                                $list_all_will[$key][$inc]['nama'] = '-';
                            }
                            $inc++;
                        }
                    }
                }

                // dd($dataSkorAkhirPerWilEst);
                foreach ($dataSkorAkhirPerWilEst as $key => $value) {
                    foreach ($value as $subKey => $subValue) {
                        if (strpos($subKey, 'Plasma') !== false) {
                            unset($dataSkorAkhirPerWilEst[$key][$subKey]);
                        }
                    }
                }
                // dd($dataSkorAkhirPerWilEst);
                $skor_gm_wil = [];
                foreach ($dataSkorAkhirPerWilEst as $key => $value) {
                    $sum_est_brondol = 0;
                    $sum_est_karung = 0;
                    $sum_est_buah_tinggal = 0;
                    $sum_est_restan_tinggal = 0;
                    $sum_blok = 0;
                    foreach ($value as $key2 => $value2) {
                        $sum_est_brondol += $value2['total_estate_brondol'];
                        $sum_est_karung += $value2['total_estate_karung'];
                        $sum_est_buah_tinggal += $value2['total_estate_buah_tinggal'];
                        $sum_est_restan_tinggal += $value2['total_estate_restan_tinggal'];

                        // dd($value2['total_blokokok']);

                        // if ($value2['total_blokokok'] != 0) {
                        $sum_blok += $value2['total_blokokok'];
                        // } else {
                        //     $sum_blok = 1;
                        // }
                    }

                    if ($sum_blok != 0) {
                        $skor_total_brondol = round($sum_est_brondol / $sum_blok, 2);
                    } else {
                        $skor_total_brondol = 0;
                    }

                    if ($sum_blok != 0) {
                        $skor_total_karung = round($sum_est_karung / $sum_blok, 2);
                    } else {
                        $skor_total_karung = 0;
                    }

                    if ($sum_blok != 0) {
                        $skor_total_buah_tinggal = round($sum_est_buah_tinggal / $sum_blok, 2);
                    } else {
                        $skor_total_buah_tinggal = 0;
                    }

                    if ($sum_blok != 0) {
                        $skor_total_restan_tinggal = round($sum_est_restan_tinggal / $sum_blok, 2);
                    } else {
                        $skor_total_restan_tinggal = 0;
                    }

                    $skor_gm_wil[$key]['total_brondolan'] = $sum_est_brondol;
                    $skor_gm_wil[$key]['total_karung'] = $sum_est_karung;
                    $skor_gm_wil[$key]['total_buah_tinggal'] = $sum_est_buah_tinggal;
                    $skor_gm_wil[$key]['total_restan'] = $sum_est_restan_tinggal;
                    $skor_gm_wil[$key]['blok'] = $sum_blok;
                    $skor_gm_wil[$key]['skor'] = skorBRDsidak($skor_total_brondol) + skorKRsidak($skor_total_karung) + skorBHsidak($skor_total_buah_tinggal) + skorRSsidak($skor_total_restan_tinggal);
                }
                // dd($skor_gm_wil);
                $GmSkorWil = [];

                $queryAsisten1 = DB::connection('mysql2')
                    ->Table('asisten_qc')
                    ->get();
                $queryAsisten1 = json_decode($queryAsisten1, true);

                foreach ($skor_gm_wil as $key => $value) {
                    // determine estWil value based on key
                    if ($key == 1) {
                        $estWil = 'WIL-I';
                    } elseif ($key == 2) {
                        $estWil = 'WIL-II';
                    } elseif ($key == 3) {
                        $estWil = 'WIL-III';
                    } elseif ($key == 4) {
                        $estWil = 'WIL-IV';
                    } elseif ($key == 5) {
                        $estWil = 'WIL-V';
                    } elseif ($key == 6) {
                        $estWil = 'WIL-VI';
                    } elseif ($key == 7) {
                        $estWil = 'WIL-VII';
                    } elseif ($key == 8) {
                        $estWil = 'WIL-VIII';
                    }

                    // get nama value from queryAsisten1
                    $namaGM = '-';
                    foreach ($queryAsisten1 as $asisten) {
                        if ($asisten['est'] == $estWil && $asisten['afd'] == 'GM') {
                            $namaGM = $asisten['nama'];
                            break; // stop searching once we find the matching asisten
                        }
                    }

                    // add the current skor_gm_wil value and namaGM value to GmSkorWil array
                    $GmSkorWil[] = [
                        'total_brondolan' => $value['total_brondolan'],
                        'total_karung' => $value['total_karung'],
                        'total_buah_tinggal' => $value['total_buah_tinggal'],
                        'total_restan' => $value['total_restan'],
                        'blok' => $value['blok'],
                        'skor' => $value['skor'],
                        'est' => $estWil,
                        'afd' => 'GM',
                        'namaGM' => $namaGM,
                    ];
                }

                $GmSkorWil = array_values($GmSkorWil);

                $sum_wil_blok = 0;
                $sum_wil_brondolan = 0;
                $sum_wil_karung = 0;
                $sum_wil_buah_tinggal = 0;
                $sum_wil_restan = 0;

                foreach ($skor_gm_wil as $key => $value) {
                    $sum_wil_blok += $value['blok'];
                    $sum_wil_brondolan += $value['total_brondolan'];
                    $sum_wil_karung += $value['total_karung'];
                    $sum_wil_buah_tinggal += $value['total_buah_tinggal'];
                    $sum_wil_restan += $value['total_restan'];
                }

                $skor_total_wil_brondol = $sum_wil_blok == 0 ? $sum_wil_brondolan : round($sum_wil_brondolan / $sum_wil_blok, 2);
                $skor_total_wil_karung = $sum_wil_blok == 0 ? $sum_wil_karung : round($sum_wil_karung / $sum_wil_blok, 2);
                $skor_total_wil_buah_tinggal = $sum_wil_blok == 0 ? $sum_wil_buah_tinggal : round($sum_wil_buah_tinggal / $sum_wil_blok, 2);
                $skor_total_wil_restan = $sum_wil_blok == 0 ? $sum_wil_restan : round($sum_wil_restan / $sum_wil_blok, 2);

                $skor_rh = [];
                foreach ($queryReg as $key => $value) {
                    if ($value == 1) {
                        $est = 'REG-I';
                    } elseif ($value == 2) {
                        $est = 'REG-II';
                    } else {
                        $est = 'REG-III';
                    }
                    foreach ($queryAsisten as $key2 => $value2) {
                        if ($value2->est == $est && $value2->afd == 'RH') {
                            $skor_rh[$value]['nama'] = $value2->nama;
                        }
                    }
                    if (empty($skor_rh[$value]['nama'])) {
                        $skor_rh[$value]['nama'] = '-';
                    }
                    $skor_rh[$value]['skor'] = skorBRDsidak($skor_total_wil_brondol) + skorKRsidak($skor_total_wil_karung) + skorBHsidak($skor_total_wil_buah_tinggal) + skorRSsidak($skor_total_wil_restan);
                }

                foreach ($list_all_will as $key => $value) {
                    array_multisort(array_column($list_all_will[$key], 'skor'), SORT_DESC, $list_all_will[$key]);
                    $rank = 1;
                    foreach ($value as $key1 => $value1) {
                        foreach ($value1 as $key2 => $value2) {
                            $list_all_will[$key][$key1]['rank'] = $rank;
                        }
                        $rank++;
                    }
                    array_multisort(array_column($list_all_will[$key], 'est_afd'), SORT_ASC, $list_all_will[$key]);
                }


                $list_all_est = [];
                foreach ($dataSkorAkhirPerWilEst as $key => $value) {
                    $inc = 0;
                    foreach ($value as $key2 => $value2) {
                        $list_all_est[$key][$inc]['est'] = $key2;
                        $list_all_est[$key][$inc]['skor'] = $value2['skor_akhir'];
                        $list_all_est[$key][$inc]['EM'] = 'EM';
                        foreach ($queryAsisten as $key4 => $value4) {
                            if ($value4->est == $key2 && $value4->afd == 'EM') {
                                $list_all_est[$key][$inc]['nama'] = $value4->nama;
                            }
                        }
                        if (empty($list_all_est[$key][$inc]['nama'])) {
                            $list_all_est[$key][$inc]['nama'] = '-';
                        }
                        $inc++;
                    }
                }

                foreach ($list_all_est as $key => $value) {
                    array_multisort(array_column($list_all_est[$key], 'skor'), SORT_DESC, $list_all_est[$key]);
                    $rank = 1;
                    foreach ($value as $key1 => $value1) {
                        foreach ($value1 as $key2 => $value2) {
                            $list_all_est[$key][$key1]['rank'] = $rank;
                        }
                        $rank++;
                    }
                    array_multisort(array_column($list_all_est[$key], 'est'), SORT_ASC, $list_all_est[$key]);
                }

                // dd($list_all_est);
                // dd($list_all_est);

                //untuk chart!!!
                foreach ($queryGroup as $key => $value) {
                    $sum_bt_tph = 0;
                    $skor_brd = 0;
                    $listBlokPerAfd = [];
                    foreach ($value as $val) {
                        if (!in_array($val->est . ' ' . $val->afd . ' ' . $val->blok, $listBlokPerAfd)) {
                            $listBlokPerAfd[] = $val->est . ' ' . $val->afd . ' ' . $val->blok;
                        }
                        $jum_blok = count($listBlokPerAfd);
                        $sum_bt_tph += $val->bt_tph;
                    }
                    $skor_brd = round($sum_bt_tph / $jum_blok, 2);
                    $arrBtTPHperEst[$key] = $skor_brd;
                }
                // dd($arrBtTPHperEst);
                //sebelum di masukan ke aray harus looping karung berisi brondolan untuk mengambil datanya 2 lapis
                foreach ($queryGroup as $key => $value) {
                    $sum_jum_karung = 0;
                    $skor_brd = 0;
                    $listBlokPerAfd = [];
                    foreach ($value as $val) {
                        if (!in_array($val->est . ' ' . $val->afd . ' ' . $val->blok, $listBlokPerAfd)) {
                            $listBlokPerAfd[] = $val->est . ' ' . $val->afd . ' ' . $val->blok;
                        }
                        $jum_blok = count($listBlokPerAfd);
                        $sum_jum_karung += $val->jum_karung;
                    }
                    $skor_brd = round($sum_jum_karung / $jum_blok, 2);
                    $arrKRest[$key] = $skor_brd;
                }
                //looping buah tinggal
                foreach ($queryGroup as $key => $value) {
                    $sum_buah_tinggal = 0;
                    $skor_brd = 0;
                    $listBlokPerAfd = [];
                    foreach ($value as $val) {
                        if (!in_array($val->est . ' ' . $val->afd . ' ' . $val->blok, $listBlokPerAfd)) {
                            $listBlokPerAfd[] = $val->est . ' ' . $val->afd . ' ' . $val->blok;
                        }
                        $jum_blok = count($listBlokPerAfd);
                        $sum_buah_tinggal += $val->buah_tinggal;
                    }
                    $skor_brd = round($sum_buah_tinggal / $jum_blok, 2);
                    $arrBHest[$key] = $skor_brd;
                }
                //looping buah restrant tidak di  laporkan
                foreach ($queryGroup as $key => $value) {
                    $sum_restan_unreported = 0;
                    $skor_brd = 0;
                    $listBlokPerAfd = [];
                    foreach ($value as $val) {
                        if (!in_array($val->est . ' ' . $val->afd . ' ' . $val->blok, $listBlokPerAfd)) {
                            $listBlokPerAfd[] = $val->est . ' ' . $val->afd . ' ' . $val->blok;
                        }
                        $jum_blok = count($listBlokPerAfd);
                        $sum_restan_unreported += $val->restan_unreported;
                    }
                    $skor_brd = round($sum_restan_unreported / $jum_blok, 2);
                    $arrRSest[$key] = $skor_brd;
                }

                //query untuk wilayah menambhakna data
                //jadikan dulu query dalam group memakai data querry untuk wilayah
                $queryGroupWil = $query->groupBy(function ($item) {
                    return $item->wil;
                });

                // dd($queryGroupWil);
                foreach ($queryGroupWil as $key => $value) {
                    $sum_bt_tph = 0;
                    foreach ($value as $key2 => $val) {
                        $sum_bt_tph += $val->bt_tph;
                    }
                    // if ($key == 1 || $key == 2 || $key == 3) {
                    if ($skor_gm_wil[$key]['blok'] != 0) {
                        $arrBtTPHperWil[$key] = round($sum_bt_tph / $skor_gm_wil[$key]['blok'], 2);
                    } else {
                        $arrBtTPHperWil[$key] = 0;
                    }
                    // }
                }

                //sebelum di masukan ke aray harus looping karung berisi brondolan untuk mengambil datanya 2 lapis
                foreach ($queryGroupWil as $key => $value) {
                    $sum_jum_karung = 0;
                    foreach ($value as $key2 => $vale) {
                        $sum_jum_karung += $vale->jum_karung;
                    }
                    if ($key == 1 || $key == 2 || $key == 3) {
                        if ($skor_gm_wil[$key]['blok'] != 0) {
                            $arrKRestWil[$key] = round($sum_jum_karung / $skor_gm_wil[$key]['blok'], 2);
                        } else {
                            $arrKRestWil[$key] = 0;
                        }
                    }
                }
                //looping buah tinggal
                foreach ($queryGroupWil as $key => $value) {
                    $sum_buah_tinggal = 0;
                    foreach ($value as $key2 => $val2) {
                        $sum_buah_tinggal += $val2->buah_tinggal;
                    }
                    if ($key == 1 || $key == 2 || $key == 3) {
                        if ($skor_gm_wil[$key]['blok'] != 0) {
                            $arrBHestWil[$key] = round($sum_buah_tinggal / $skor_gm_wil[$key]['blok'], 2);
                        } else {
                            $arrBHestWil[$key] = 0;
                        }
                    }
                }
                foreach ($queryGroupWil as $key => $value) {
                    $sum_restan_unreported = 0;
                    foreach ($value as $key2 => $val3) {
                        $sum_restan_unreported += $val3->restan_unreported;
                    }
                    if ($key == 1 || $key == 2 || $key == 3) {
                        if ($skor_gm_wil[$key]['blok'] != 0) {
                            $arrRestWill[$key] = round($sum_restan_unreported / $skor_gm_wil[$key]['blok'], 2);
                        } else {
                            $arrRestWill[$key] = 0;
                        }
                    }
                }
            }
            // dd($arrBtTPHperWil, $arrKRestWil, $arrBHestWil, $arrRestWill);
            // dd($queryGroup);

            //bagian plasma cuy
            $QueryPlasmaSIdak = DB::connection('mysql2')
                ->table('sidak_tph')
                ->select('sidak_tph.*', DB::raw('DATE_FORMAT(sidak_tph.datetime, "%M") as bulan'), DB::raw('DATE_FORMAT(sidak_tph.datetime, "%Y") as tahun'))
                ->where('sidak_tph.datetime', 'like', '%' . $monthSidak . '%')
                ->get();
            $QueryPlasmaSIdak = $QueryPlasmaSIdak->groupBy(['est', 'afd']);
            $QueryPlasmaSIdak = json_decode($QueryPlasmaSIdak, true);
            // dd($QueryPlasmaSIdak['Plasma1']);
            $getPlasma = 'Plasma' . $regSidak;
            $queryEstePla = DB::connection('mysql2')
                ->table('estate')
                ->select('estate.*')
                ->whereIn('estate.est', [$getPlasma])
                ->join('wil', 'wil.id', '=', 'estate.wil')
                ->where('wil.regional', $regSidak)
                ->get();
            $queryEstePla = json_decode($queryEstePla, true);

            $queryAsisten = DB::connection('mysql2')
                ->Table('asisten_qc')
                ->get();
            $queryAsisten = json_decode($queryAsisten, true);

            $PlasmaAfd = DB::connection('mysql2')
                ->table('afdeling')
                ->select('afdeling.id', 'afdeling.nama', 'estate.est') //buat mengambil data di estate db dan willayah db
                ->join('estate', 'estate.id', '=', 'afdeling.estate') //kemudian di join untuk mengambil est perwilayah
                ->get();
            $PlasmaAfd = json_decode($PlasmaAfd, true);

            $SidakTPHPlA = [];
            foreach ($QueryPlasmaSIdak as $key => $value) {
                foreach ($value as $key2 => $value2) {
                    foreach ($value2 as $key3 => $value3) {
                        $SidakTPHPlA[$key][$key2][$key3] = $value3;
                    }
                }
            }
            // dd($SidakTPHPlA);
            $defPLASidak = [];
            foreach ($queryEstePla as $est) {
                // dd($est);
                foreach ($queryAfd as $afd) {
                    // dd($afd);
                    if ($est['est'] == $afd['est']) {
                        $defPLASidak[$est['est']][$afd['nama']]['null'] = 0;
                    }
                }
            }

            foreach ($defPLASidak as $key => $estValue) {
                foreach ($estValue as $monthKey => $monthValue) {
                    $mergedValues = [];
                    foreach ($SidakTPHPlA as $dataKey => $dataValue) {
                        if ($dataKey == $key && isset($dataValue[$monthKey])) {
                            $mergedValues = array_merge($mergedValues, $dataValue[$monthKey]);
                        }
                    }
                    $defPLASidak[$key][$monthKey] = $mergedValues;
                }
            }

            $arrPlasma = [];
            foreach ($defPLASidak as $key => $value) {
                if (!empty($value)) {
                    $jum_blokPla = 0;
                    $sum_tphPla = 0;
                    $sum_karungPla = 0;
                    $sum_buahPla = 0;
                    $sum_restantPla = 0;

                    $skor_tphPla = 0;
                    $skor_karungPla = 0;
                    $skor_buahPla = 0;
                    $skor_restanPla = 0;
                    $skoreTotalPla = 0;
                    foreach ($value as $key1 => $value1) {
                        if (!empty($value1)) {
                            $jum_blok = 0;
                            $sum_bt_tph = 0;
                            $sum_bt_jalan = 0;
                            $sum_bt_bin = 0;
                            $sum_all = 0;
                            $sum_all_karung = 0;
                            $sum_jum_karung = 0;
                            $sum_buah_tinggal = 0;
                            $sum_all_bt_tgl = 0;
                            $sum_restan_unreported = 0;
                            $sum_all_restan_unreported = 0;
                            $listBlokPerAfd = [];
                            foreach ($value1 as $key2 => $value2) {
                                if (is_array($value2)) {
                                    $blok = $value2['est'] . ' ' . $value2['afd'] . ' ' . $value2['blok'];

                                    if (!in_array($blok, $listBlokPerAfd)) {
                                        array_push($listBlokPerAfd, $blok);
                                    }

                                    // $jum_blok = count(float)($listBlokPerAfd);
                                    $jum_blok = count($listBlokPerAfd);

                                    $sum_bt_tph += $value2['bt_tph'];
                                    $sum_bt_jalan += $value2['bt_jalan'];
                                    $sum_bt_bin += $value2['bt_bin'];

                                    $sum_jum_karung += $value2['jum_karung'];
                                    $sum_buah_tinggal += $value2['buah_tinggal'];
                                    $sum_restan_unreported += $value2['restan_unreported'];
                                }
                            }
                            //change value 3to float type
                            $sum_all = $sum_bt_tph + $sum_bt_jalan + $sum_bt_bin;
                            $sum_all_karung = $sum_jum_karung;
                            $sum_all_bt_tgl = $sum_buah_tinggal;
                            $sum_all_restan_unreported = $sum_restan_unreported;
                            if ($jum_blok == 0) {
                                $skor_brd = 0;
                                $skor_kr = 0;
                                $skor_buahtgl = 0;
                                $skor_restan = 0;
                            } else {
                                $skor_brd = round($sum_all / $jum_blok, 1);
                                $skor_kr = round($sum_all_karung / $jum_blok, 1);
                                $skor_buahtgl = round($sum_all_bt_tgl / $jum_blok, 1);
                                $skor_restan = round($sum_all_restan_unreported / $jum_blok, 1);
                            }

                            $skoreTotal = skorBRDsidak($skor_brd) + skorKRsidak($skor_kr) + skorBHsidak($skor_buahtgl) + skorRSsidak($skor_restan);

                            $arrPlasma[$key][$key1]['karung_tes'] = $sum_all_karung;
                            $arrPlasma[$key][$key1]['tph_test'] = $sum_all;
                            $arrPlasma[$key][$key1]['buah_test'] = $sum_all_bt_tgl;
                            $arrPlasma[$key][$key1]['restant_tes'] = $sum_all_restan_unreported;

                            $arrPlasma[$key][$key1]['jumlah_blok'] = $jum_blok;

                            $arrPlasma[$key][$key1]['brd_blok'] = skorBRDsidak($skor_brd);
                            $arrPlasma[$key][$key1]['kr_blok'] = skorKRsidak($skor_kr);
                            $arrPlasma[$key][$key1]['buah_blok'] = skorBHsidak($skor_buahtgl);
                            $arrPlasma[$key][$key1]['restan_blok'] = skorRSsidak($skor_restan);
                            $arrPlasma[$key][$key1]['skorWil'] = $skoreTotal;

                            $jum_blokPla += $jum_blok;
                            $sum_karungPla += $sum_all_karung;
                            $sum_restantPla += $sum_all_restan_unreported;
                            $sum_tphPla += $sum_all;
                            $sum_buahPla += $sum_all_bt_tgl;
                        } else {
                            $arrPlasma[$key][$key1]['karung_tes'] = 0;
                            $arrPlasma[$key][$key1]['tph_test'] = 0;
                            $arrPlasma[$key][$key1]['buah_test'] = 0;
                            $arrPlasma[$key][$key1]['restant_tes'] = 0;

                            $arrPlasma[$key][$key1]['jumlah_blok'] = 0;

                            $arrPlasma[$key][$key1]['brd_blok'] = 0;
                            $arrPlasma[$key][$key1]['kr_blok'] = 0;
                            $arrPlasma[$key][$key1]['buah_blok'] = 0;
                            $arrPlasma[$key][$key1]['restan_blok'] = 0;
                            $arrPlasma[$key][$key1]['skorWil'] = 0;
                        }
                    }

                    if ($jum_blokPla != 0) {
                        $skor_tphPla = round($sum_tphPla / $jum_blokPla, 2);
                        $skor_karungPla = round($sum_karungPla / $jum_blokPla, 2);
                        $skor_buahPla = round($sum_buahPla / $jum_blokPla, 2);
                        $skor_restanPla = round($sum_restantPla / $jum_blokPla, 2);
                    } else {
                        $skor_tphPla = 0;
                        $skor_karungPla = 0;
                        $skor_buahPla = 0;
                        $skor_restanPla = 0;
                    }

                    $skoreTotalPla = skorBRDsidak($skor_tphPla) + skorKRsidak($skor_karungPla) + skorBHsidak($skor_buahPla) + skorRSsidak($skor_restanPla);
                    $arrPlasma[$key]['karung_tes'] = $sum_karungPla;
                    $arrPlasma[$key]['tph_test'] = $sum_tphPla;
                    $arrPlasma[$key]['buah_test'] = $sum_buahPla;
                    $arrPlasma[$key]['restant_tes'] = $sum_restantPla;

                    $arrPlasma[$key]['jumlah_blok'] = $jum_blokPla;

                    $arrPlasma[$key]['brd_blok'] = skorBRDsidak($skor_tphPla);
                    $arrPlasma[$key]['kr_blok'] = skorKRsidak($skor_karungPla);
                    $arrPlasma[$key]['buah_blok'] = skorBHsidak($skor_buahPla);
                    $arrPlasma[$key]['restan_blok'] = skorRSsidak($skor_restanPla);
                    $arrPlasma[$key]['SkorPlasma'] = $skoreTotalPla;
                } else {
                    $arrPlasma[$key]['karung_tes'] = 0;
                    $arrPlasma[$key]['tph_test'] = 0;
                    $arrPlasma[$key]['buah_test'] = 0;
                    $arrPlasma[$key]['restant_tes'] = 0;

                    $arrPlasma[$key]['jumlah_blok'] = 0;

                    $arrPlasma[$key]['brd_blok'] = 0;
                    $arrPlasma[$key]['kr_blok'] = 0;
                    $arrPlasma[$key]['buah_blok'] = 0;
                    $arrPlasma[$key]['restan_blok'] = 0;
                    $arrPlasma[$key]['SkorPlasma'] = 0;
                }
            }
            // dd($arrPlasma);
            foreach ($arrPlasma as $key1 => $estates) {
                if (is_array($estates)) {
                    // $sortedData = array();
                    $sortedDataEst = [];
                    foreach ($estates as $estateName => $data) {
                        // dd($data);
                        if (is_array($data)) {
                            $sortedDataEst[] = [
                                'key1' => $key1,
                                'estateName' => $estateName,
                                'data' => $data,
                            ];
                        }
                    }
                    usort($sortedDataEst, function ($a, $b) {
                        return $b['data']['skorWil'] - $a['data']['skorWil'];
                    });
                    $rank = 1;
                    foreach ($sortedDataEst as $sortedest) {
                        $arrPlasma[$key1][$sortedest['estateName']]['rank'] = $rank;
                        $rank++;
                    }
                    unset($sortedDataEst);
                }
            }
            // dd($arrPlasma);

            $PlasmaWIl = [];
            foreach ($arrPlasma as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $key1 => $value1) {
                        if (is_array($value1)) {
                            // dd($value1);
                            $inc = 0;
                            $est = $key;
                            $skor = $value1['skorWil'];
                            // dd($skor);
                            $EM = $key1;

                            $rank = $value1['rank'];
                            // $rank = $value1['rank'];
                            $nama = '-';
                            foreach ($queryAsisten as $key4 => $value4) {
                                if (is_array($value4) && $value4['est'] == $est && $value4['afd'] == $EM) {
                                    $nama = $value4['nama'];
                                    break;
                                }
                            }
                            $PlasmaWIl[] = [
                                'est' => $est,
                                'afd' => $EM,
                                'nama' => $nama,
                                'skor' => $skor,
                                'rank' => $rank,
                            ];
                            $inc++;
                        }
                    }
                }
            }

            $PlasmaWIl = array_values($PlasmaWIl);

            $PlasmaEM = [];
            $NamaEm = '-';
            foreach ($arrPlasma as $key => $value) {
                if (is_array($value)) {
                    $inc = 0;
                    $est = $key;
                    $skor = $value['SkorPlasma'];
                    $EM = 'EM';
                    // dd($value);
                    foreach ($queryAsisten as $key4 => $value4) {
                        if (is_array($value4) && $value4['est'] == $est && $value4['afd'] == $EM) {
                            $NamaEm = $value4['nama'];
                        }
                    }
                    $inc++;
                }
            }

            if (isset($EM)) {
                $PlasmaEM[] = [
                    'est' => $est,
                    'afd' => $EM,
                    'namaEM' => $NamaEm,
                    'Skor' => $skor,
                ];
            }

            $PlasmaEM = array_values($PlasmaEM);

            $plasmaGM = [];
            $namaGM = '-';
            foreach ($arrPlasma as $key => $value) {
                if (is_array($value)) {
                    $inc = 0;
                    $est = $key;
                    $skor = $value['SkorPlasma'];
                    $GM = 'GM';
                    // dd($value);
                    foreach ($queryAsisten as $key4 => $value4) {
                        if (is_array($value4) && $value4['est'] == $est && $value4['afd'] == $GM) {
                            $namaGM = $value4['nama'];
                        }
                    }
                    $inc++;
                }
            }

            if (isset($GM)) {
                $plasmaGM[] = [
                    'est' => $est,
                    'afd' => $GM,
                    'namaGM' => $namaGM,
                    'Skor' => $skor,
                ];
            }

            $plasmaGM = array_values($plasmaGM);
            //masukan semua yang sudah selese di olah di atas ke dalam vaiabel terserah kemudian masukan kedalam aray
            //karena chart hanya bisa menerima inputan json
            $queryWilChart = DB::connection('mysql2')
                ->table('wil')
                ->whereIn('regional', [$regSidak])
                ->pluck('nama');

            $arrView = [];

            $arrView['list_estate'] = $queryEst;
            $arrView['list_wilayah'] = $queryWill;
            $arrView['list_wilayah2'] = $queryWilChart;
            // $arrView['restant'] = $dataSkorAwalRestant;
            // dd($skor_gm_wil);
            $arrView['list_all_wil'] = $list_all_will;
            $arrView['list_all_est'] = $list_all_est;
            $arrView['list_skor_gm'] = $skor_gm_wil;
            $arrView['list_skor_rh'] = $skor_rh;
            $arrView['PlasmaWIl'] = $PlasmaWIl;
            $arrView['PlasmaEM'] = $PlasmaEM;
            $arrView['plasmaGM'] = $plasmaGM;
            $arrView['list_skor_gmNew'] = $GmSkorWil;
            // $arrView['karung'] = $dataSkorAwalKr;
            // $arrView['buah'] = $dataSkorAwalBuah;
            // // dd($queryEst);
            $keysToRemove = ['SRE', 'LDE', 'SKE'];

            // Loop through the array and remove the elements with the specified keys
            foreach ($keysToRemove as $key) {
                unset($arrBtTPHperEst[$key]);
                unset($arrKRest[$key]);
                unset($arrBHest[$key]);
                unset($arrRSest[$key]);
            }
            // masukan ke array data penjumlahan dari estate
            $arrView['val_bt_tph'] = $arrBtTPHperEst; //data jsen brondolan tinggal di tph
            $arrView['val_kr_tph'] = $arrKRest; //data jsen karung yang berisi buah
            $arrView['val_bh_tph'] = $arrBHest; //data jsen buah yang tinggal
            $arrView['val_rs_tph'] = $arrRSest; //data jsen restan yang tidak dilaporkan
            //masukan ke array data penjumlahan dari wilayah
            $arrView['val_kr_tph_wil'] = $arrKRestWil; //data jsen karung yang berisi buah
            $arrView['val_bt_tph_wil'] = $arrBtTPHperWil; //data jsen brondolan tinggal di tph
            $arrView['val_bh_tph_wil'] = $arrBHestWil; //data jsen buah yang tinggal
            $arrView['val_rs_tph_wil'] = $arrRestWill; //data jsen restan yang tidak dilaporkan
            // dd($arrBtTPHperEst);
            echo json_encode($arrView); //di decode ke dalam bentuk json dalam vaiavel arrview yang dapat menampung banyak isi array
            exit();
        }
    }
    public function getBtTphYear(Request $request)
    {
        $regSidak = $request->get('reg');
        $yearSidak = $request->get('year');
        $queryWill = DB::connection('mysql2')
            ->table('wil')
            ->whereIn('regional', [$regSidak])
            ->get();
        $queryReg = DB::connection('mysql2')
            ->table('wil')
            ->whereIn('regional', [$regSidak])
            ->pluck('regional');
        $queryReg2 = DB::connection('mysql2')
            ->table('wil')
            ->whereIn('regional', [$regSidak])
            ->pluck('id')
            ->toArray();

        // dapatkan data estate dari table estate dengan wilayah 1 , 2 , 3
        $queryEst = DB::connection('mysql2')
            ->table('estate')
            // ->whereNotIn('estate.est', ['PLASMA'])
            ->whereIn('wil', $queryReg2)
            ->get();
        // dd($queryEst);
        $queryEste = DB::connection('mysql2')
            ->table('estate')
            ->whereNotIn('estate.est', ['PLASMA', 'SRE', 'LDE', 'SKE'])
            ->whereIn('wil', $queryReg2)
            ->get();
        $queryEste = $queryEste->groupBy(function ($item) {
            return $item->wil;
        });

        $queryEste = json_decode($queryEste, true);

        // dd($queryEst);
        $queryAfd = DB::connection('mysql2')
            ->Table('afdeling')
            ->select('afdeling.id', 'afdeling.nama', 'estate.est') //buat mengambil data di estate db dan willayah db
            ->join('estate', 'estate.id', '=', 'afdeling.estate') //kemudian di join untuk mengambil est perwilayah
            ->whereNotIn('estate.est', ['SRE', 'LDE', 'SKE'])
            ->get();

        $queryAfd = json_decode($queryAfd, true);

        //array untuk tampung nilai bt tph per estate dari table bt_jalan & bt_tph dll
        $arrBtTPHperEst = []; //table dari brondolan di buat jadi array agar bisa di parse ke json
        $arrKRest = []; //table dari jum_jkarung di buat jadi array agar bisa di parse ke json
        $arrBHest = []; //table dari Buah di buat jadi array agar bisa di parse ke json
        $arrRSest = []; //table array untuk buah restant tidak di laporkan

        ///array untuk table nya

        $dataSkorAwal = [];

        $list_all_will = [];

        //memberi nilai 0 default kesemua estate
        foreach ($queryEst as $key => $value) {
            $arrBtTPHperEst[$value->est] = 0; //est mengambil value dari table estate
            $arrKRest[$value->est] = 0;
            $arrBHest[$value->est] = 0;
            $arrRSest[$value->est] = 0;
        }
        // dd($queryEst);
        foreach ($queryWill as $key => $value) {
            $arrBtTPHperWil[$value->nama] = 0; //est mengambil value dari table estate
            $arrKRestWil[$value->nama] = 0;
            $arrBHestWil[$value->nama] = 0;
            $arrRestWill[$value->nama] = 0;
        }

        // dd($firstWeek, $lastWeek);
        $query = DB::connection('mysql2')
            ->Table('sidak_tph')
            ->select('sidak_tph.*', 'estate.wil') //buat mengambil data di estate db dan willayah db
            ->whereIn('estate.wil', $queryReg2)
            ->join('estate', 'estate.est', '=', 'sidak_tph.est') //kemudian di join untuk mengambil est perwilayah
            ->where('sidak_tph.datetime', 'like', '%' . $yearSidak . '%')
            // ->where('sidak_tph.datetime', 'LIKE', '%' . $request->$firstDate . '%')
            ->get();
        //     ->get();
        $queryAFD = DB::connection('mysql2')
            ->Table('sidak_tph')
            ->select('sidak_tph.*', 'estate.wil') //buat mengambil data di estate db dan willayah db
            ->whereIn('estate.wil', $queryReg2)
            ->join('estate', 'estate.est', '=', 'sidak_tph.est') //kemudian di join untuk mengambil est perwilayah
            ->where('sidak_tph.datetime', 'like', '%' . $yearSidak . '%')
            // ->where('sidak_tph.datetime', 'LIKE', '%' . $request->$firstDate . '%')
            ->get();
        // dd($query);
        $queryAsisten = DB::connection('mysql2')
            ->Table('asisten_qc')
            ->get();

        $dataAfdEst = [];

        $querySidak = DB::connection('mysql2')
            ->table('sidak_tph')
            ->where('sidak_tph.datetime', 'like', '%' . $yearSidak . '%')
            // ->whereBetween('sidak_tph.datetime', ['2023-01-23', '202-12-25'])
            ->get();
        $querySidak = json_decode($querySidak, true);

        $allBlok = $query->groupBy(function ($item) {
            return $item->blok;
        });

        if (!empty($query && $queryAFD && $querySidak)) {
            $queryGroup = $queryAFD->groupBy(function ($item) {
                return $item->est;
            });
            // dd($queryGroup);
            $queryWi = DB::connection('mysql2')
                ->table('estate')
                ->whereIn('wil', $queryReg2)
                ->get();

            $queryWill = $queryWi->groupBy(function ($item) {
                return $item->nama;
            });

            $queryWill2 = $queryWi->groupBy(function ($item) {
                return $item->nama;
            });

            //untuk table!!
            // store wil -> est -> afd
            // menyimpan array nested dari  wil -> est -> afd
            foreach ($queryEste as $key => $value) {
                foreach ($value as $key2 => $value2) {
                    foreach ($queryAfd as $key3 => $value3) {
                        $est = $value2['est'];
                        $afd = $value3['nama'];
                        if ($value2['est'] == $value3['est']) {
                            foreach ($querySidak as $key4 => $value4) {
                                if ($est == $value4['est'] && $afd == $value4['afd']) {
                                    $dataAfdEst[$est][$afd][] = $value4;
                                } else {
                                    $dataAfdEst[$est][$afd]['null'] = 0;
                                }
                            }
                        }
                    }
                }
            }

            foreach ($dataAfdEst as $key => $value) {
                foreach ($value as $key2 => $value2) {
                    foreach ($value2 as $key3 => $value3) {
                        unset($dataAfdEst[$key][$key2]['null']);
                        if (empty($dataAfdEst[$key][$key2])) {
                            $dataAfdEst[$key][$key2] = 0;
                        }
                    }
                }
            }

            $listBlokPerAfd = [];
            foreach ($dataAfdEst as $key => $value) {
                foreach ($value as $key2 => $value2) {
                    if (is_array($value2)) {
                        foreach ($value2 as $key3 => $value3) {
                            // dd($key3);
                            foreach ($allBlok as $key4 => $value4) {
                                if ($value3['blok'] == $key4) {
                                    $listBlokPerAfd[$key][$key2][$key3] = $value4;
                                }
                            }
                        }
                    }
                }

                // //menghitung data skor untuk brd/blok
                foreach ($dataAfdEst as $key => $value) {
                    foreach ($value as $key2 => $value2) {
                        if (is_array($value2)) {
                            $jum_blok = 0;
                            $sum_bt_tph = 0;
                            $sum_bt_jalan = 0;
                            $sum_bt_bin = 0;
                            $sum_all = 0;
                            $sum_all_karung = 0;
                            $sum_jum_karung = 0;
                            $sum_buah_tinggal = 0;
                            $sum_all_bt_tgl = 0;
                            $sum_restan_unreported = 0;
                            $sum_all_restan_unreported = 0;
                            $listBlokPerAfd = [];
                            foreach ($value2 as $key3 => $value3) {
                                if (is_array($value3)) {
                                    $blok = $value3['est'] . ' ' . $value3['afd'] . ' ' . $value3['blok'];

                                    if (!in_array($blok, $listBlokPerAfd)) {
                                        array_push($listBlokPerAfd, $blok);
                                    }

                                    // $jum_blok = count(float)($listBlokPerAfd);
                                    $jum_blok = count($listBlokPerAfd);

                                    $sum_bt_tph += $value3['bt_tph'];
                                    $sum_bt_jalan += $value3['bt_jalan'];
                                    $sum_bt_bin += $value3['bt_bin'];

                                    $sum_jum_karung += $value3['jum_karung'];
                                    $sum_buah_tinggal += $value3['buah_tinggal'];
                                    $sum_restan_unreported += $value3['restan_unreported'];
                                }
                            }
                            //change value 3to float type
                            $sum_all = $sum_bt_tph + $sum_bt_jalan + $sum_bt_bin;
                            $sum_all_karung = $sum_jum_karung;
                            $sum_all_bt_tgl = $sum_buah_tinggal;
                            $sum_all_restan_unreported = $sum_restan_unreported;
                            $skor_brd = round($sum_all / $jum_blok, 1);
                            // dd($skor_brd);
                            $skor_kr = round($sum_all_karung / $jum_blok, 1);
                            $skor_buahtgl = round($sum_all_bt_tgl / $jum_blok, 1);
                            $skor_restan = round($sum_all_restan_unreported / $jum_blok, 1);

                            $skoreTotal = skorBRDsidak($skor_brd) + skorKRsidak($skor_kr) + skorBHsidak($skor_buahtgl) + skorRSsidak($skor_restan);

                            $dataSkorAwal[$key][$key2]['karung_tes'] = $sum_all_karung;
                            $dataSkorAwal[$key][$key2]['tph_test'] = $sum_all;
                            $dataSkorAwal[$key][$key2]['buah_test'] = $sum_all_bt_tgl;
                            $dataSkorAwal[$key][$key2]['restant_tes'] = $sum_all_restan_unreported;

                            $dataSkorAwal[$key][$key2]['jumlah_blok'] = $jum_blok;

                            $dataSkorAwal[$key][$key2]['brd_blok'] = skorBRDsidak($skor_brd);
                            $dataSkorAwal[$key][$key2]['kr_blok'] = skorKRsidak($skor_kr);
                            $dataSkorAwal[$key][$key2]['buah_blok'] = skorBHsidak($skor_buahtgl);
                            $dataSkorAwal[$key][$key2]['restan_blok'] = skorRSsidak($skor_restan);
                            $dataSkorAwal[$key][$key2]['skore_akhir'] = $skoreTotal;
                        } else {
                            $dataSkorAwal[$key][$key2]['karung_tes'] = 0;
                            $dataSkorAwal[$key][$key2]['restant_tes'] = 0;
                            $dataSkorAwal[$key][$key2]['jumlah_blok'] = 0;
                            $dataSkorAwal[$key][$key2]['tph_test'] = 0;
                            $dataSkorAwal[$key][$key2]['buah_test'] = 0;

                            $dataSkorAwal[$key][$key2]['brd_blok'] = 0;
                            $dataSkorAwal[$key][$key2]['kr_blok'] = 0;
                            $dataSkorAwal[$key][$key2]['buah_blok'] = 0;
                            $dataSkorAwal[$key][$key2]['restan_blok'] = 0;
                            $dataSkorAwal[$key][$key2]['skore_akhir'] = 0;
                        }
                    }
                }

                // dd($dataSkorAwal);

                foreach ($dataSkorAwal as $key => $value) {
                    $jum_blok = 0;
                    $jum_all_blok = 0;
                    $sum_all_tph = 0;
                    $sum_tph = 0;
                    $sum_all_karung = 0;
                    $sum_karung = 0;
                    $sum_all_buah = 0;
                    $sum_buah = 0;
                    $sum_all_restant = 0;
                    $sum_restant = 0;
                    foreach ($value as $key2 => $value2) {
                        if (is_array($value2)) {
                            $jum_blok += $value2['jumlah_blok'];
                            $sum_karung += $value2['karung_tes'];
                            $sum_restant += $value2['restant_tes'];
                            $sum_tph += $value2['tph_test'];
                            $sum_buah += $value2['buah_test'];
                        }
                    }
                    $sum_all_tph = $sum_tph;
                    $jum_all_blok = $jum_blok;
                    $sum_all_karung = $sum_karung;
                    $sum_all_buah = $sum_buah;
                    $sum_all_restant = $sum_restant;

                    if ($jum_all_blok != 0) {
                        $skor_tph = round($sum_all_tph / $jum_all_blok, 2);
                        $skor_karung = round($sum_all_karung / $jum_all_blok, 2);
                        $skor_buah = round($sum_all_buah / $jum_all_blok, 2);
                        $skor_restan = round($sum_all_restant / $jum_all_blok, 2);
                    } else {
                        $skor_tph = 0;
                        $skor_karung = 0;
                        $skor_buah = 0;
                        $skor_restan = 0;
                    }

                    $skoreTotal = skorBRDsidak($skor_tph) + skorKRsidak($skor_karung) + skorBHsidak($skor_buah) + skorRSsidak($skor_restan);

                    $dataSkorAwaltest[$key]['total_estate_brondol'] = $sum_all_tph;
                    $dataSkorAwaltest[$key]['total_estate_karung'] = $sum_all_karung;
                    $dataSkorAwaltest[$key]['total_estate_buah_tinggal'] = $sum_all_buah;
                    $dataSkorAwaltest[$key]['total_estate_restan_tinggal'] = $sum_all_restant;
                    $dataSkorAwaltest[$key]['tph'] = skorBRDsidak($skor_tph);
                    $dataSkorAwaltest[$key]['karung'] = skorKRsidak($skor_karung);
                    $dataSkorAwaltest[$key]['buah_tinggal'] = skorBHsidak($skor_buah);
                    $dataSkorAwaltest[$key]['restant'] = skorRSsidak($skor_restan);
                    $dataSkorAwaltest[$key]['total_blokokok'] = $jum_all_blok;
                    $dataSkorAwaltest[$key]['skor_akhir'] = $skoreTotal;
                }
                // dd($dataSkorAwaltest);

                foreach ($queryEste as $key => $value) {
                    foreach ($value as $key2 => $value2) {
                        foreach ($dataSkorAwal as $key3 => $value3) {
                            if ($value2['est'] == $key3) {
                                $dataSkorAkhirPerWil[$key][$key3] = $value3;
                            }
                        }
                    }
                }

                foreach ($queryEste as $key => $value) {
                    foreach ($value as $key2 => $value2) {
                        foreach ($dataSkorAwaltest as $key3 => $value3) {
                            if ($value2['est'] == $key3) {
                                $dataSkorAkhirPerWilEst[$key][$key3] = $value3;
                            }
                        }
                    }
                }
                // dd($dataSkorAkhirPerWilEst['3']);
                //menshort nilai masing masing
                $sortList = [];
                foreach ($dataSkorAkhirPerWil as $key => $value) {
                    $inc = 0;
                    foreach ($value as $key2 => $value2) {
                        foreach ($value2 as $key3 => $value3) {
                            $sortList[$key][$key2 . '_' . $key3] = $value3['skore_akhir'];
                            $inc++;
                        }
                    }
                }

                //short list untuk mengurutkan valuenya
                foreach ($sortList as &$value) {
                    arsort($value);
                }
                // dd($sortList);
                $sortListEstate = [];
                foreach ($dataSkorAkhirPerWilEst as $key => $value) {
                    $inc = 0;
                    foreach ($value as $key2 => $value2) {
                        $sortListEstate[$key][$key2] = $value2['skor_akhir'];
                        $inc++;
                    }
                }

                //short list untuk mengurutkan valuenya
                foreach ($sortListEstate as &$value) {
                    arsort($value);
                }

                // dd($sortListEstate);
                foreach ($dataSkorAkhirPerWilEst as $key => $value) {
                    foreach ($value as $key2 => $value2) {
                        foreach ($value2 as $key3 => $value3) {
                        }
                    }
                }

                //menambahkan nilai rank ketia semua total skor sudah di uritkan
                $test = [];
                $listRank = [];
                foreach ($dataSkorAkhirPerWil as $key => $value) {
                    // create an array to store the skore_akhir values
                    $skore_akhir_values = [];
                    foreach ($value as $key2 => $value2) {
                        foreach ($value2 as $key3 => $value3) {
                            $skore_akhir_values[] = $value3['skore_akhir'];
                        }
                    }
                    // sort the skore_akhir values in descending order
                    rsort($skore_akhir_values);
                    // assign ranks to each skore_akhir value
                    foreach ($value as $key2 => $value2) {
                        foreach ($value2 as $key3 => $value3) {
                            $rank = array_search($value3['skore_akhir'], $skore_akhir_values) + 1;
                            $dataSkorAkhirPerWil[$key][$key2][$key3]['rank'] = $rank;
                            $test[$key][] = $value3['skore_akhir'];
                        }
                    }
                }

                // perbaiki rank saya berdasarkan skore_akhir di mana jika $value3['skore_akhir'] terkecil merupakan rank 1 dan seterusnya
                $list_all_will = [];
                foreach ($dataSkorAkhirPerWil as $key => $value) {
                    $inc = 0;
                    foreach ($value as $key2 => $value2) {
                        foreach ($value2 as $key3 => $value3) {
                            $list_all_will[$key][$inc]['est_afd'] = $key2 . '_' . $key3;
                            $list_all_will[$key][$inc]['est'] = $key2;
                            $list_all_will[$key][$inc]['afd'] = $key3;
                            $list_all_will[$key][$inc]['skor'] = $value3['skore_akhir'];
                            foreach ($queryAsisten as $key4 => $value4) {
                                if ($value4->est == $key2 && $value4->afd == $key3) {
                                    $list_all_will[$key][$inc]['nama'] = $value4->nama;
                                }
                            }
                            if (empty($list_all_will[$key][$inc]['nama'])) {
                                $list_all_will[$key][$inc]['nama'] = '-';
                            }
                            $inc++;
                        }
                    }
                }

                $skor_gm_wil = [];
                foreach ($dataSkorAkhirPerWilEst as $key => $value) {
                    $sum_est_brondol = 0;
                    $sum_est_karung = 0;
                    $sum_est_buah_tinggal = 0;
                    $sum_est_restan_tinggal = 0;
                    $sum_blok = 0;
                    foreach ($value as $key2 => $value2) {
                        $sum_est_brondol += $value2['total_estate_brondol'];
                        $sum_est_karung += $value2['total_estate_karung'];
                        $sum_est_buah_tinggal += $value2['total_estate_buah_tinggal'];
                        $sum_est_restan_tinggal += $value2['total_estate_restan_tinggal'];

                        // dd($value2['total_blokokok']);

                        // if ($value2['total_blokokok'] != 0) {
                        $sum_blok += $value2['total_blokokok'];
                        // } else {
                        //     $sum_blok = 1;
                        // }
                    }

                    if ($sum_blok != 0) {
                        $skor_total_brondol = round($sum_est_brondol / $sum_blok, 2);
                    } else {
                        $skor_total_brondol = 0;
                    }

                    if ($sum_blok != 0) {
                        $skor_total_karung = round($sum_est_karung / $sum_blok, 2);
                    } else {
                        $skor_total_karung = 0;
                    }

                    if ($sum_blok != 0) {
                        $skor_total_buah_tinggal = round($sum_est_buah_tinggal / $sum_blok, 2);
                    } else {
                        $skor_total_buah_tinggal = 0;
                    }

                    if ($sum_blok != 0) {
                        $skor_total_restan_tinggal = round($sum_est_restan_tinggal / $sum_blok, 2);
                    } else {
                        $skor_total_restan_tinggal = 0;
                    }

                    $skor_gm_wil[$key]['total_brondolan'] = $sum_est_brondol;
                    $skor_gm_wil[$key]['total_karung'] = $sum_est_karung;
                    $skor_gm_wil[$key]['total_buah_tinggal'] = $sum_est_buah_tinggal;
                    $skor_gm_wil[$key]['total_restan'] = $sum_est_restan_tinggal;
                    $skor_gm_wil[$key]['blok'] = $sum_blok;
                    $skor_gm_wil[$key]['skor'] = skorBRDsidak($skor_total_brondol) + skorKRsidak($skor_total_karung) + skorBHsidak($skor_total_buah_tinggal) + skorRSsidak($skor_total_restan_tinggal);
                }

                $GmSkorWil = [];

                $queryAsisten1 = DB::connection('mysql2')
                    ->Table('asisten_qc')
                    ->get();
                $queryAsisten1 = json_decode($queryAsisten1, true);

                foreach ($skor_gm_wil as $key => $value) {
                    // determine estWil value based on key
                    if ($key == 1) {
                        $estWil = 'WIL-I';
                    } elseif ($key == 2) {
                        $estWil = 'WIL-II';
                    } elseif ($key == 3) {
                        $estWil = 'WIL-III';
                    } elseif ($key == 4) {
                        $estWil = 'WIL-IV';
                    } elseif ($key == 5) {
                        $estWil = 'WIL-V';
                    } elseif ($key == 6) {
                        $estWil = 'WIL-VI';
                    } elseif ($key == 7) {
                        $estWil = 'WIL-VII';
                    } elseif ($key == 8) {
                        $estWil = 'WIL-VIII';
                    }

                    // get nama value from queryAsisten1
                    $namaGM = '-';
                    foreach ($queryAsisten1 as $asisten) {
                        if ($asisten['est'] == $estWil && $asisten['afd'] == 'GM') {
                            $namaGM = $asisten['nama'];
                            break; // stop searching once we find the matching asisten
                        }
                    }

                    // add the current skor_gm_wil value and namaGM value to GmSkorWil array
                    $GmSkorWil[] = [
                        'total_brondolan' => $value['total_brondolan'],
                        'total_karung' => $value['total_karung'],
                        'total_buah_tinggal' => $value['total_buah_tinggal'],
                        'total_restan' => $value['total_restan'],
                        'blok' => $value['blok'],
                        'skor' => $value['skor'],
                        'est' => $estWil,
                        'afd' => 'GM',
                        'namaGM' => $namaGM,
                    ];
                }

                $GmSkorWil = array_values($GmSkorWil);

                $sum_wil_blok = 0;
                $sum_wil_brondolan = 0;
                $sum_wil_karung = 0;
                $sum_wil_buah_tinggal = 0;
                $sum_wil_restan = 0;

                foreach ($skor_gm_wil as $key => $value) {
                    $sum_wil_blok += $value['blok'];
                    $sum_wil_brondolan += $value['total_brondolan'];
                    $sum_wil_karung += $value['total_karung'];
                    $sum_wil_buah_tinggal += $value['total_buah_tinggal'];
                    $sum_wil_restan += $value['total_restan'];
                }

                $skor_total_wil_brondol = $sum_wil_blok == 0 ? $sum_wil_brondolan : round($sum_wil_brondolan / $sum_wil_blok, 2);
                $skor_total_wil_karung = $sum_wil_blok == 0 ? $sum_wil_karung : round($sum_wil_karung / $sum_wil_blok, 2);
                $skor_total_wil_buah_tinggal = $sum_wil_blok == 0 ? $sum_wil_buah_tinggal : round($sum_wil_buah_tinggal / $sum_wil_blok, 2);
                $skor_total_wil_restan = $sum_wil_blok == 0 ? $sum_wil_restan : round($sum_wil_restan / $sum_wil_blok, 2);

                $skor_rh = [];
                foreach ($queryReg as $key => $value) {
                    if ($value == 1) {
                        $est = 'REG-I';
                    } elseif ($value == 2) {
                        $est = 'REG-II';
                    } else {
                        $est = 'REG-III';
                    }
                    foreach ($queryAsisten as $key2 => $value2) {
                        if ($value2->est == $est && $value2->afd == 'RH') {
                            $skor_rh[$value]['nama'] = $value2->nama;
                        }
                    }
                    if (empty($skor_rh[$value]['nama'])) {
                        $skor_rh[$value]['nama'] = '-';
                    }
                    $skor_rh[$value]['skor'] = skorBRDsidak($skor_total_wil_brondol) + skorKRsidak($skor_total_wil_karung) + skorBHsidak($skor_total_wil_buah_tinggal) + skorRSsidak($skor_total_wil_restan);
                }

                foreach ($list_all_will as $key => $value) {
                    array_multisort(array_column($list_all_will[$key], 'skor'), SORT_DESC, $list_all_will[$key]);
                    $rank = 1;
                    foreach ($value as $key1 => $value1) {
                        foreach ($value1 as $key2 => $value2) {
                            $list_all_will[$key][$key1]['rank'] = $rank;
                        }
                        $rank++;
                    }
                    array_multisort(array_column($list_all_will[$key], 'est_afd'), SORT_ASC, $list_all_will[$key]);
                }
                // $list_all_will = array();
                // foreach ($dataSkorAkhirPerWil as $key => $value) {
                //     $inc = 0;
                //     foreach ($value as $key2 => $value2) {
                //         foreach ($value2 as $key3 => $value3) {
                //             $list_all_will[$key][$inc]['est'] = $key2;
                //             $list_all_will[$key][$inc]['afd'] = $key3;
                //             $list_all_will[$key][$inc]['skor'] = $value3['skore_akhir'];
                //             $list_all_will[$key][$inc]['nama'] = '-';
                //             $list_all_will[$key][$inc]['rank'] = '-';
                //             $inc++;
                //         }
                //     }
                // }

                // foreach ($list_all_will as $key1 => $value1) {
                //     $filtered_subarray = array_filter($value1, function ($element) {
                //         return $element['skor'] != '-';
                //     });
                //     $rank = 1;
                //     foreach ($filtered_subarray as $key2 => $value2) {
                //         $filtered_subarray[$key2]['rank'] = $rank;
                //         $rank++;
                //     }
                //     $list_all_will[$key1] = $filtered_subarray;
                // }

                $list_all_est = [];
                foreach ($dataSkorAkhirPerWilEst as $key => $value) {
                    $inc = 0;
                    foreach ($value as $key2 => $value2) {
                        $list_all_est[$key][$inc]['est'] = $key2;
                        $list_all_est[$key][$inc]['skor'] = $value2['skor_akhir'];
                        $list_all_est[$key][$inc]['EM'] = 'EM';
                        foreach ($queryAsisten as $key4 => $value4) {
                            if ($value4->est == $key2 && $value4->afd == 'EM') {
                                $list_all_est[$key][$inc]['nama'] = $value4->nama;
                            }
                        }
                        if (empty($list_all_est[$key][$inc]['nama'])) {
                            $list_all_est[$key][$inc]['nama'] = '-';
                        }
                        $inc++;
                    }
                }

                foreach ($list_all_est as $key => $value) {
                    array_multisort(array_column($list_all_est[$key], 'skor'), SORT_DESC, $list_all_est[$key]);
                    $rank = 1;
                    foreach ($value as $key1 => $value1) {
                        foreach ($value1 as $key2 => $value2) {
                            $list_all_est[$key][$key1]['rank'] = $rank;
                        }
                        $rank++;
                    }
                    array_multisort(array_column($list_all_est[$key], 'est'), SORT_ASC, $list_all_est[$key]);
                }

                // dd($list_all_est);
                // dd($list_all_est);

                //untuk chart!!!
                foreach ($queryGroup as $key => $value) {
                    $sum_bt_tph = 0;
                    $skor_brd = 0;
                    $listBlokPerAfd = [];
                    foreach ($value as $val) {
                        if (!in_array($val->est . ' ' . $val->afd . ' ' . $val->blok, $listBlokPerAfd)) {
                            $listBlokPerAfd[] = $val->est . ' ' . $val->afd . ' ' . $val->blok;
                        }
                        $jum_blok = count($listBlokPerAfd);
                        $sum_bt_tph += $val->bt_tph;
                    }
                    $skor_brd = round($sum_bt_tph / $jum_blok, 2);
                    $arrBtTPHperEst[$key] = $skor_brd;
                }
                // dd($arrBtTPHperEst);
                //sebelum di masukan ke aray harus looping karung berisi brondolan untuk mengambil datanya 2 lapis
                foreach ($queryGroup as $key => $value) {
                    $sum_jum_karung = 0;
                    $skor_brd = 0;
                    $listBlokPerAfd = [];
                    foreach ($value as $val) {
                        if (!in_array($val->est . ' ' . $val->afd . ' ' . $val->blok, $listBlokPerAfd)) {
                            $listBlokPerAfd[] = $val->est . ' ' . $val->afd . ' ' . $val->blok;
                        }
                        $jum_blok = count($listBlokPerAfd);
                        $sum_jum_karung += $val->jum_karung;
                    }
                    $skor_brd = round($sum_jum_karung / $jum_blok, 2);
                    $arrKRest[$key] = $skor_brd;
                }
                //looping buah tinggal
                foreach ($queryGroup as $key => $value) {
                    $sum_buah_tinggal = 0;
                    $skor_brd = 0;
                    $listBlokPerAfd = [];
                    foreach ($value as $val) {
                        if (!in_array($val->est . ' ' . $val->afd . ' ' . $val->blok, $listBlokPerAfd)) {
                            $listBlokPerAfd[] = $val->est . ' ' . $val->afd . ' ' . $val->blok;
                        }
                        $jum_blok = count($listBlokPerAfd);
                        $sum_buah_tinggal += $val->buah_tinggal;
                    }
                    $skor_brd = round($sum_buah_tinggal / $jum_blok, 2);
                    $arrBHest[$key] = $skor_brd;
                }
                //looping buah restrant tidak di  laporkan
                foreach ($queryGroup as $key => $value) {
                    $sum_restan_unreported = 0;
                    $skor_brd = 0;
                    $listBlokPerAfd = [];
                    foreach ($value as $val) {
                        if (!in_array($val->est . ' ' . $val->afd . ' ' . $val->blok, $listBlokPerAfd)) {
                            $listBlokPerAfd[] = $val->est . ' ' . $val->afd . ' ' . $val->blok;
                        }
                        $jum_blok = count($listBlokPerAfd);
                        $sum_restan_unreported += $val->restan_unreported;
                    }
                    $skor_brd = round($sum_restan_unreported / $jum_blok, 2);
                    $arrRSest[$key] = $skor_brd;
                }

                //query untuk wilayah menambhakna data
                //jadikan dulu query dalam group memakai data querry untuk wilayah
                $queryGroupWil = $query->groupBy(function ($item) {
                    return $item->wil;
                });

                // dd($queryGroupWil);
                foreach ($queryGroupWil as $key => $value) {
                    $sum_bt_tph = 0;
                    foreach ($value as $key2 => $val) {
                        $sum_bt_tph += $val->bt_tph;
                    }
                    // if ($key == 1 || $key == 2 || $key == 3) {
                    if ($skor_gm_wil[$key]['blok'] != 0) {
                        $arrBtTPHperWil[$key] = round($sum_bt_tph / $skor_gm_wil[$key]['blok'], 2);
                    } else {
                        $arrBtTPHperWil[$key] = 0;
                    }
                    // }
                }

                //sebelum di masukan ke aray harus looping karung berisi brondolan untuk mengambil datanya 2 lapis
                foreach ($queryGroupWil as $key => $value) {
                    $sum_jum_karung = 0;
                    foreach ($value as $key2 => $vale) {
                        $sum_jum_karung += $vale->jum_karung;
                    }
                    if ($key == 1 || $key == 2 || $key == 3) {
                        if ($skor_gm_wil[$key]['blok'] != 0) {
                            $arrKRestWil[$key] = round($sum_jum_karung / $skor_gm_wil[$key]['blok'], 2);
                        } else {
                            $arrKRestWil[$key] = 0;
                        }
                    }
                }
                //looping buah tinggal
                foreach ($queryGroupWil as $key => $value) {
                    $sum_buah_tinggal = 0;
                    foreach ($value as $key2 => $val2) {
                        $sum_buah_tinggal += $val2->buah_tinggal;
                    }
                    if ($key == 1 || $key == 2 || $key == 3) {
                        if ($skor_gm_wil[$key]['blok'] != 0) {
                            $arrBHestWil[$key] = round($sum_buah_tinggal / $skor_gm_wil[$key]['blok'], 2);
                        } else {
                            $arrBHestWil[$key] = 0;
                        }
                    }
                }
                foreach ($queryGroupWil as $key => $value) {
                    $sum_restan_unreported = 0;
                    foreach ($value as $key2 => $val3) {
                        $sum_restan_unreported += $val3->restan_unreported;
                    }
                    if ($key == 1 || $key == 2 || $key == 3) {
                        if ($skor_gm_wil[$key]['blok'] != 0) {
                            $arrRestWill[$key] = round($sum_restan_unreported / $skor_gm_wil[$key]['blok'], 2);
                        } else {
                            $arrRestWill[$key] = 0;
                        }
                    }
                }
            }
            // dd($arrBtTPHperWil, $arrKRestWil, $arrBHestWil, $arrRestWill);
            // dd($queryGroup);

            //bagian plasma cuy
            $QueryPlasmaSIdak = DB::connection('mysql2')
                ->table('sidak_tph')
                ->select('sidak_tph.*', DB::raw('DATE_FORMAT(sidak_tph.datetime, "%M") as bulan'), DB::raw('DATE_FORMAT(sidak_tph.datetime, "%Y") as tahun'))
                ->where('sidak_tph.datetime', 'like', '%' . $yearSidak . '%')
                ->get();
            $QueryPlasmaSIdak = $QueryPlasmaSIdak->groupBy(['est', 'afd']);
            $QueryPlasmaSIdak = json_decode($QueryPlasmaSIdak, true);
            // dd($QueryPlasmaSIdak['Plasma1']);
            $getPlasma = 'Plasma' . $regSidak;
            $queryEstePla = DB::connection('mysql2')
                ->table('estate')
                ->select('estate.*')
                ->whereIn('estate.est', [$getPlasma])
                ->join('wil', 'wil.id', '=', 'estate.wil')
                ->where('wil.regional', $regSidak)
                ->get();
            $queryEstePla = json_decode($queryEstePla, true);

            $queryAsisten = DB::connection('mysql2')
                ->Table('asisten_qc')
                ->get();
            $queryAsisten = json_decode($queryAsisten, true);

            $PlasmaAfd = DB::connection('mysql2')
                ->table('afdeling')
                ->select('afdeling.id', 'afdeling.nama', 'estate.est') //buat mengambil data di estate db dan willayah db
                ->join('estate', 'estate.id', '=', 'afdeling.estate') //kemudian di join untuk mengambil est perwilayah
                ->get();
            $PlasmaAfd = json_decode($PlasmaAfd, true);

            $SidakTPHPlA = [];
            foreach ($QueryPlasmaSIdak as $key => $value) {
                foreach ($value as $key2 => $value2) {
                    foreach ($value2 as $key3 => $value3) {
                        $SidakTPHPlA[$key][$key2][$key3] = $value3;
                    }
                }
            }
            // dd($SidakTPHPlA);
            $defPLASidak = [];
            foreach ($queryEstePla as $est) {
                // dd($est);
                foreach ($queryAfd as $afd) {
                    // dd($afd);
                    if ($est['est'] == $afd['est']) {
                        $defPLASidak[$est['est']][$afd['nama']]['null'] = 0;
                    }
                }
            }

            foreach ($defPLASidak as $key => $estValue) {
                foreach ($estValue as $monthKey => $monthValue) {
                    $mergedValues = [];
                    foreach ($SidakTPHPlA as $dataKey => $dataValue) {
                        if ($dataKey == $key && isset($dataValue[$monthKey])) {
                            $mergedValues = array_merge($mergedValues, $dataValue[$monthKey]);
                        }
                    }
                    $defPLASidak[$key][$monthKey] = $mergedValues;
                }
            }

            $arrPlasma = [];
            foreach ($defPLASidak as $key => $value) {
                if (!empty($value)) {
                    $jum_blokPla = 0;
                    $sum_tphPla = 0;
                    $sum_karungPla = 0;
                    $sum_buahPla = 0;
                    $sum_restantPla = 0;

                    $skor_tphPla = 0;
                    $skor_karungPla = 0;
                    $skor_buahPla = 0;
                    $skor_restanPla = 0;
                    $skoreTotalPla = 0;
                    foreach ($value as $key1 => $value1) {
                        if (!empty($value1)) {
                            $jum_blok = 0;
                            $sum_bt_tph = 0;
                            $sum_bt_jalan = 0;
                            $sum_bt_bin = 0;
                            $sum_all = 0;
                            $sum_all_karung = 0;
                            $sum_jum_karung = 0;
                            $sum_buah_tinggal = 0;
                            $sum_all_bt_tgl = 0;
                            $sum_restan_unreported = 0;
                            $sum_all_restan_unreported = 0;
                            $listBlokPerAfd = [];
                            foreach ($value1 as $key2 => $value2) {
                                if (is_array($value2)) {
                                    $blok = $value2['est'] . ' ' . $value2['afd'] . ' ' . $value2['blok'];

                                    if (!in_array($blok, $listBlokPerAfd)) {
                                        array_push($listBlokPerAfd, $blok);
                                    }

                                    // $jum_blok = count(float)($listBlokPerAfd);
                                    $jum_blok = count($listBlokPerAfd);

                                    $sum_bt_tph += $value2['bt_tph'];
                                    $sum_bt_jalan += $value2['bt_jalan'];
                                    $sum_bt_bin += $value2['bt_bin'];

                                    $sum_jum_karung += $value2['jum_karung'];
                                    $sum_buah_tinggal += $value2['buah_tinggal'];
                                    $sum_restan_unreported += $value2['restan_unreported'];
                                }
                            }
                            //change value 3to float type
                            $sum_all = $sum_bt_tph + $sum_bt_jalan + $sum_bt_bin;
                            $sum_all_karung = $sum_jum_karung;
                            $sum_all_bt_tgl = $sum_buah_tinggal;
                            $sum_all_restan_unreported = $sum_restan_unreported;
                            if ($jum_blok == 0) {
                                $skor_brd = 0;
                                $skor_kr = 0;
                                $skor_buahtgl = 0;
                                $skor_restan = 0;
                            } else {
                                $skor_brd = round($sum_all / $jum_blok, 1);
                                $skor_kr = round($sum_all_karung / $jum_blok, 1);
                                $skor_buahtgl = round($sum_all_bt_tgl / $jum_blok, 1);
                                $skor_restan = round($sum_all_restan_unreported / $jum_blok, 1);
                            }

                            $skoreTotal = skorBRDsidak($skor_brd) + skorKRsidak($skor_kr) + skorBHsidak($skor_buahtgl) + skorRSsidak($skor_restan);

                            $arrPlasma[$key][$key1]['karung_tes'] = $sum_all_karung;
                            $arrPlasma[$key][$key1]['tph_test'] = $sum_all;
                            $arrPlasma[$key][$key1]['buah_test'] = $sum_all_bt_tgl;
                            $arrPlasma[$key][$key1]['restant_tes'] = $sum_all_restan_unreported;

                            $arrPlasma[$key][$key1]['jumlah_blok'] = $jum_blok;

                            $arrPlasma[$key][$key1]['brd_blok'] = skorBRDsidak($skor_brd);
                            $arrPlasma[$key][$key1]['kr_blok'] = skorKRsidak($skor_kr);
                            $arrPlasma[$key][$key1]['buah_blok'] = skorBHsidak($skor_buahtgl);
                            $arrPlasma[$key][$key1]['restan_blok'] = skorRSsidak($skor_restan);
                            $arrPlasma[$key][$key1]['skorWil'] = $skoreTotal;

                            $jum_blokPla += $jum_blok;
                            $sum_karungPla += $sum_all_karung;
                            $sum_restantPla += $sum_all_restan_unreported;
                            $sum_tphPla += $sum_all;
                            $sum_buahPla += $sum_all_bt_tgl;
                        } else {
                            $arrPlasma[$key][$key1]['karung_tes'] = 0;
                            $arrPlasma[$key][$key1]['tph_test'] = 0;
                            $arrPlasma[$key][$key1]['buah_test'] = 0;
                            $arrPlasma[$key][$key1]['restant_tes'] = 0;

                            $arrPlasma[$key][$key1]['jumlah_blok'] = 0;

                            $arrPlasma[$key][$key1]['brd_blok'] = 0;
                            $arrPlasma[$key][$key1]['kr_blok'] = 0;
                            $arrPlasma[$key][$key1]['buah_blok'] = 0;
                            $arrPlasma[$key][$key1]['restan_blok'] = 0;
                            $arrPlasma[$key][$key1]['skorWil'] = 0;
                        }
                    }

                    if ($jum_blokPla != 0) {
                        $skor_tphPla = round($sum_tphPla / $jum_blokPla, 2);
                        $skor_karungPla = round($sum_karungPla / $jum_blokPla, 2);
                        $skor_buahPla = round($sum_buahPla / $jum_blokPla, 2);
                        $skor_restanPla = round($sum_restantPla / $jum_blokPla, 2);
                    } else {
                        $skor_tphPla = 0;
                        $skor_karungPla = 0;
                        $skor_buahPla = 0;
                        $skor_restanPla = 0;
                    }

                    $skoreTotalPla = skorBRDsidak($skor_tphPla) + skorKRsidak($skor_karungPla) + skorBHsidak($skor_buahPla) + skorRSsidak($skor_restanPla);
                    $arrPlasma[$key]['karung_tes'] = $sum_karungPla;
                    $arrPlasma[$key]['tph_test'] = $sum_tphPla;
                    $arrPlasma[$key]['buah_test'] = $sum_buahPla;
                    $arrPlasma[$key]['restant_tes'] = $sum_restantPla;

                    $arrPlasma[$key]['jumlah_blok'] = $jum_blokPla;

                    $arrPlasma[$key]['brd_blok'] = skorBRDsidak($skor_tphPla);
                    $arrPlasma[$key]['kr_blok'] = skorKRsidak($skor_karungPla);
                    $arrPlasma[$key]['buah_blok'] = skorBHsidak($skor_buahPla);
                    $arrPlasma[$key]['restan_blok'] = skorRSsidak($skor_restanPla);
                    $arrPlasma[$key]['SkorPlasma'] = $skoreTotalPla;
                } else {
                    $arrPlasma[$key]['karung_tes'] = 0;
                    $arrPlasma[$key]['tph_test'] = 0;
                    $arrPlasma[$key]['buah_test'] = 0;
                    $arrPlasma[$key]['restant_tes'] = 0;

                    $arrPlasma[$key]['jumlah_blok'] = 0;

                    $arrPlasma[$key]['brd_blok'] = 0;
                    $arrPlasma[$key]['kr_blok'] = 0;
                    $arrPlasma[$key]['buah_blok'] = 0;
                    $arrPlasma[$key]['restan_blok'] = 0;
                    $arrPlasma[$key]['SkorPlasma'] = 0;
                }
            }
            // dd($arrPlasma);
            foreach ($arrPlasma as $key1 => $estates) {
                if (is_array($estates)) {
                    // $sortedData = array();
                    $sortedDataEst = [];
                    foreach ($estates as $estateName => $data) {
                        // dd($data);
                        if (is_array($data)) {
                            $sortedDataEst[] = [
                                'key1' => $key1,
                                'estateName' => $estateName,
                                'data' => $data,
                            ];
                        }
                    }
                    usort($sortedDataEst, function ($a, $b) {
                        return $b['data']['skorWil'] - $a['data']['skorWil'];
                    });
                    $rank = 1;
                    foreach ($sortedDataEst as $sortedest) {
                        $arrPlasma[$key1][$sortedest['estateName']]['rank'] = $rank;
                        $rank++;
                    }
                    unset($sortedDataEst);
                }
            }
            // dd($arrPlasma);

            $PlasmaWIl = [];
            foreach ($arrPlasma as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $key1 => $value1) {
                        if (is_array($value1)) {
                            // dd($value1);
                            $inc = 0;
                            $est = $key;
                            $skor = $value1['skorWil'];
                            // dd($skor);
                            $EM = $key1;

                            $rank = $value1['rank'];
                            // $rank = $value1['rank'];
                            $nama = '-';
                            foreach ($queryAsisten as $key4 => $value4) {
                                if (is_array($value4) && $value4['est'] == $est && $value4['afd'] == $EM) {
                                    $nama = $value4['nama'];
                                    break;
                                }
                            }
                            $PlasmaWIl[] = [
                                'est' => $est,
                                'afd' => $EM,
                                'nama' => $nama,
                                'skor' => $skor,
                                'rank' => $rank,
                            ];
                            $inc++;
                        }
                    }
                }
            }

            $PlasmaWIl = array_values($PlasmaWIl);

            $PlasmaEM = [];
            $NamaEm = '-';
            foreach ($arrPlasma as $key => $value) {
                if (is_array($value)) {
                    $inc = 0;
                    $est = $key;
                    $skor = $value['SkorPlasma'];
                    $EM = 'EM';
                    // dd($value);
                    foreach ($queryAsisten as $key4 => $value4) {
                        if (is_array($value4) && $value4['est'] == $est && $value4['afd'] == $EM) {
                            $NamaEm = $value4['nama'];
                        }
                    }
                    $inc++;
                }
            }

            if (isset($EM)) {
                $PlasmaEM[] = [
                    'est' => $est,
                    'afd' => $EM,
                    'namaEM' => $NamaEm,
                    'Skor' => $skor,
                ];
            }

            $PlasmaEM = array_values($PlasmaEM);

            $plasmaGM = [];
            $namaGM = '-';
            foreach ($arrPlasma as $key => $value) {
                if (is_array($value)) {
                    $inc = 0;
                    $est = $key;
                    $skor = $value['SkorPlasma'];
                    $GM = 'GM';
                    // dd($value);
                    foreach ($queryAsisten as $key4 => $value4) {
                        if (is_array($value4) && $value4['est'] == $est && $value4['afd'] == $GM) {
                            $namaGM = $value4['nama'];
                        }
                    }
                    $inc++;
                }
            }

            if (isset($GM)) {
                $plasmaGM[] = [
                    'est' => $est,
                    'afd' => $GM,
                    'namaGM' => $namaGM,
                    'Skor' => $skor,
                ];
            }

            $plasmaGM = array_values($plasmaGM);
            //masukan semua yang sudah selese di olah di atas ke dalam vaiabel terserah kemudian masukan kedalam aray
            //karena chart hanya bisa menerima inputan json
            $queryWilChart = DB::connection('mysql2')
                ->table('wil')
                ->whereIn('regional', [$regSidak])
                ->pluck('nama');

            $arrView = [];

            $arrView['list_estate'] = $queryEst;
            $arrView['list_wilayah'] = $queryWill;
            $arrView['list_wilayah2'] = $queryWilChart;
            // $arrView['restant'] = $dataSkorAwalRestant;

            $arrView['list_all_wil'] = $list_all_will;
            $arrView['list_all_est'] = $list_all_est;
            $arrView['list_skor_gm'] = $skor_gm_wil;
            $arrView['list_skor_rh'] = $skor_rh;
            $arrView['PlasmaWIl'] = $PlasmaWIl;
            $arrView['PlasmaEM'] = $PlasmaEM;
            $arrView['plasmaGM'] = $plasmaGM;
            $arrView['list_skor_gmNew'] = $GmSkorWil;
            // $arrView['karung'] = $dataSkorAwalKr;
            // $arrView['buah'] = $dataSkorAwalBuah;
            // // dd($queryEst);
            $keysToRemove = ['SRE', 'LDE', 'SKE'];

            // Loop through the array and remove the elements with the specified keys
            foreach ($keysToRemove as $key) {
                unset($arrBtTPHperEst[$key]);
                unset($arrKRest[$key]);
                unset($arrBHest[$key]);
                unset($arrRSest[$key]);
            }
            // masukan ke array data penjumlahan dari estate
            $arrView['val_bt_tph'] = $arrBtTPHperEst; //data jsen brondolan tinggal di tph
            $arrView['val_kr_tph'] = $arrKRest; //data jsen karung yang berisi buah
            $arrView['val_bh_tph'] = $arrBHest; //data jsen buah yang tinggal
            $arrView['val_rs_tph'] = $arrRSest; //data jsen restan yang tidak dilaporkan
            //masukan ke array data penjumlahan dari wilayah
            $arrView['val_kr_tph_wil'] = $arrKRestWil; //data jsen karung yang berisi buah
            $arrView['val_bt_tph_wil'] = $arrBtTPHperWil; //data jsen brondolan tinggal di tph
            $arrView['val_bh_tph_wil'] = $arrBHestWil; //data jsen buah yang tinggal
            $arrView['val_rs_tph_wil'] = $arrRestWill; //data jsen restan yang tidak dilaporkan
            // dd($arrBtTPHperEst);
            echo json_encode($arrView); //di decode ke dalam bentuk json dalam vaiavel arrview yang dapat menampung banyak isi array
            exit();
        }
    }

    public function graphFilterYear(Request $request)
    {
        $regData = $request->get('reg');
        $estData = $request->get('est');
        $yearGraph = $request->get('yearGraph');

        $queryReg = DB::connection('mysql2')
            ->table('wil')
            ->whereIn('regional', [$regData])
            ->pluck('id')
            ->toArray();

        $querySidak = DB::connection('mysql2')->table('sidak_tph')
            ->select("sidak_tph.*")
            ->whereNotIn('est', ['Plasma1', 'Plasma2', 'Plasma3'])
            ->get();
        $DataEstate = $querySidak->groupBy(['est', 'afd']);
        $DataEstate = json_decode($DataEstate, true);

        //menghitung buat table tampilkan pertahun
        $queryTph = DB::connection('mysql2')->table('sidak_tph')
            ->select(
                "sidak_tph.*",
                DB::raw('DATE_FORMAT(sidak_tph.datetime, "%M") as bulan'),
                DB::raw('DATE_FORMAT(sidak_tph.datetime, "%Y") as tahun')
            )
            ->whereNotIn('est', ['Plasma1', 'Plasma2', 'Plasma3'])
            ->whereYear('datetime', $yearGraph)
            ->get();
        $queryTph = $queryTph->groupBy(['est', 'afd']);
        $queryTph = json_decode($queryTph, true);

        //afdeling
        $queryAfd = DB::connection('mysql2')->table('afdeling')
            ->select(
                'afdeling.id',
                'afdeling.nama',
                'estate.est'
            ) //buat mengambil data di estate db dan willayah db
            ->whereNotIn('estate.est', ['Plasma1', 'Plasma2', 'Plasma3'])
            ->join('estate', 'estate.id', '=', 'afdeling.estate') //kemudian di join untuk mengambil est perwilayah
            ->get();
        $queryAfd = json_decode($queryAfd, true);
        //estate
        $queryEste = DB::connection('mysql2')->table('estate')->whereIn('wil', $queryReg)->get();
        $queryEste = json_decode($queryEste, true);

        $bulan = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        $dataBulananTph = array();
        foreach ($queryTph as $key => $value) {
            foreach ($value as $key2 => $value2) {
                foreach ($value2 as $key3 => $value3) {
                    $month = date('F', strtotime($value3['datetime']));
                    if (!array_key_exists($month, $dataBulananTph)) {
                        $dataBulananTph[$month] = array();
                    }
                    if (!array_key_exists($key, $dataBulananTph[$month])) {
                        $dataBulananTph[$month][$key] = array();
                    }
                    if (!array_key_exists($key2, $dataBulananTph[$month][$key])) {
                        $dataBulananTph[$month][$key][$key2] = array();
                    }
                    $dataBulananTph[$month][$key][$key2][$key3] = $value3;
                }
            }
        }

        $defaultTph = array();
        foreach ($bulan as $month) {
            foreach ($queryEste as $est) {
                foreach ($queryAfd as $afd) {
                    if ($est['est'] == $afd['est']) {
                        $defaultTph[$est['est']][$month][$afd['nama']] = 0;
                    }
                }
            }
        }

        //menimpa nilai default mutu transport dengan yang memiliki value
        foreach ($defaultTph as $key => $estValue) {
            foreach ($estValue as $monthKey => $monthValue) {
                foreach ($dataBulananTph as $dataKey => $dataValue) {
                    if ($dataKey == $monthKey) {
                        foreach ($dataValue as $dataEstKey => $dataEstValue) {
                            if ($dataEstKey == $key) {
                                $defaultTph[$key][$monthKey] = array_merge($monthValue, $dataEstValue);
                            }
                        }
                    }
                }
            }
        }

        $sidakTphEst = array();
        foreach ($defaultTph as $key => $value) {
            foreach ($value as $key1 => $value2) if (!empty($value2)) {
                $luas_ha_est = 0;
                $jml_blok_est = 0;
                $sum_bt_tph_est = 0;
                $sum_bt_jln_est = 0;
                $sum_bt_bin_est = 0;
                $sum_krg_est = 0;
                $sumBuah_est = 0;
                $sumRst_est = 0;
                foreach ($value2 as $key2 => $value3) {
                    if (is_array($value3)) {
                        $luas_ha = 0;
                        $jml_blok = 0;
                        $sum_bt_tph = 0;
                        $sum_bt_jln = 0;
                        $sum_bt_bin = 0;
                        $sum_krg = 0;
                        $sumBuah = 0;
                        $sumRst = 0;
                        $listBlokPerAfd = array();
                        foreach ($value3 as $key3 => $value4) {
                            if (!in_array($value4['est'] . ' ' . $value4['afd'] . ' ' . $value4['blok'], $listBlokPerAfd)) {
                                $listBlokPerAfd[] = $value4['est'] . ' ' . $value4['afd'] . ' ' . $value4['blok'];
                                $luas_ha += $value4['luas'];
                            }
                            $jml_blok = count($listBlokPerAfd);
                            $sum_bt_tph += $value4['bt_tph'];
                            $sum_bt_jln += $value4['bt_jalan'];
                            $sum_bt_bin += $value4['bt_bin'];
                            $sum_krg += $value4['jum_karung'];
                            $sumBuah += $value4['buah_tinggal'];
                            $sumRst += $value4['restan_unreported'];
                        }
                        $luas_ha_est += $luas_ha;
                        $jml_blok_est += $jml_blok;
                        $sum_bt_tph_est += $sum_bt_tph;
                        $sum_bt_jln_est += $sum_bt_jln;
                        $sum_bt_bin_est += $sum_bt_bin;
                        $sum_krg_est += $sum_krg;
                        $sumBuah_est += $sumBuah;
                        $sumRst_est += $sumRst;

                        $tot_bt = ($sum_bt_tph + $sum_bt_jln + $sum_bt_bin);
                        $sidakTphEst[$key][$key1][$key2]['jml_blok'] = $jml_blok;
                        $sidakTphEst[$key][$key1][$key2]['luas_ha'] = $luas_ha;
                        $sidakTphEst[$key][$key1][$key2]['bt_tph'] = $sum_bt_tph;
                        $sidakTphEst[$key][$key1][$key2]['bt_jln'] = $sum_bt_jln;
                        $sidakTphEst[$key][$key1][$key2]['bt_bin'] = $sum_bt_bin;
                        $sidakTphEst[$key][$key1][$key2]['tot_bt'] = $tot_bt;
                        $sidakTphEst[$key][$key1][$key2]['divBt'] = round($tot_bt / $jml_blok, 2);
                        $sidakTphEst[$key][$key1][$key2]['skorBt'] = skor_bt_tph(round($tot_bt / $jml_blok, 2));
                        $sidakTphEst[$key][$key1][$key2]['sum_krg'] = $sum_krg;
                        $sidakTphEst[$key][$key1][$key2]['divKrg'] = round($sum_krg / $jml_blok, 2);
                        $sidakTphEst[$key][$key1][$key2]['skorKrg'] = skor_krg_tph(round($sum_krg / $jml_blok, 2));
                        $sidakTphEst[$key][$key1][$key2]['sumBuah'] = $sumBuah;
                        $sidakTphEst[$key][$key1][$key2]['divBuah'] = round($sumBuah / $jml_blok, 2);
                        $sidakTphEst[$key][$key1][$key2]['skorBuah'] = skor_buah_tph(round($sumBuah / $jml_blok, 2));
                        $sidakTphEst[$key][$key1][$key2]['sumRst'] = $sumRst;
                        $sidakTphEst[$key][$key1][$key2]['divRst'] = round($sumRst / $jml_blok, 2);
                        $sidakTphEst[$key][$key1][$key2]['skorRst'] = skor_rst_tph(round($sumRst / $jml_blok, 2));
                        $sidakTphEst[$key][$key1][$key2]['allSkor'] = skor_bt_tph(round($tot_bt / $jml_blok, 2)) + skor_krg_tph(round($sum_krg / $jml_blok, 2)) + skor_buah_tph(round($sumBuah / $jml_blok, 2)) + skor_rst_tph(round($sumRst / $jml_blok, 2));
                    } else {
                        $sidakTphEst[$key][$key1][$key2]['jml_blok'] = 0;
                        $sidakTphEst[$key][$key1][$key2]['luas_ha'] = 0;
                        $sidakTphEst[$key][$key1][$key2]['bt_tph'] = 0;
                        $sidakTphEst[$key][$key1][$key2]['bt_jln'] = 0;
                        $sidakTphEst[$key][$key1][$key2]['bt_bin'] = 0;
                        $sidakTphEst[$key][$key1][$key2]['tot_bt'] = 0;
                        $sidakTphEst[$key][$key1][$key2]['divBt'] = 0;
                        $sidakTphEst[$key][$key1][$key2]['skorBt'] = 0;
                        $sidakTphEst[$key][$key1][$key2]['sum_krg'] = 0;
                        $sidakTphEst[$key][$key1][$key2]['divKrg'] = 0;
                        $sidakTphEst[$key][$key1][$key2]['skorKrg'] = 0;
                        $sidakTphEst[$key][$key1][$key2]['sumBuah'] = 0;
                        $sidakTphEst[$key][$key1][$key2]['divBuah'] = 0;
                        $sidakTphEst[$key][$key1][$key2]['skorBuah'] = 0;
                        $sidakTphEst[$key][$key1][$key2]['sumRst'] = 0;
                        $sidakTphEst[$key][$key1][$key2]['divRst'] = 0;
                        $sidakTphEst[$key][$key1][$key2]['skorRst'] = 0;
                        $sidakTphEst[$key][$key1][$key2]['allSkor'] = 0;
                    }
                }
                $tot_bt_est = ($sum_bt_tph_est + $sum_bt_jln_est + $sum_bt_bin_est);
                $divBt_est = $jml_blok_est == 0 ? $tot_bt_est : round($tot_bt_est / $jml_blok_est, 2);
                $divKrg_est = $jml_blok_est == 0 ? $sum_krg_est : round($sum_krg_est / $jml_blok_est, 2);
                $divBuah_est = $jml_blok_est == 0 ? $sumBuah_est : round($sumBuah_est / $jml_blok_est, 2);
                $divRst_est = $jml_blok_est == 0 ? $sumRst_est : round($sumRst_est / $jml_blok_est, 2);
                $sidakTphEst[$key][$key1]['jml_blok_est'] = $jml_blok_est;
                $sidakTphEst[$key][$key1]['luas_ha_est'] = $luas_ha_est;
                $sidakTphEst[$key][$key1]['bt_tph_est'] = $sum_bt_tph_est;
                $sidakTphEst[$key][$key1]['bt_jln_est'] = $sum_bt_jln_est;
                $sidakTphEst[$key][$key1]['bt_bin_est'] = $sum_bt_bin_est;
                $sidakTphEst[$key][$key1]['tot_bt_est'] = $tot_bt_est;
                $sidakTphEst[$key][$key1]['divBt_est'] = $divBt_est;
                $sidakTphEst[$key][$key1]['skorBt_est'] = skor_bt_tph($divBt_est);
                $sidakTphEst[$key][$key1]['sum_krg_est'] = $sum_krg_est;
                $sidakTphEst[$key][$key1]['divKrg_est'] = $divKrg_est;
                $sidakTphEst[$key][$key1]['skorKrg_est'] = skor_krg_tph($divKrg_est);
                $sidakTphEst[$key][$key1]['sumBuah_est'] = $sumBuah_est;
                $sidakTphEst[$key][$key1]['divBuah_est'] = $divBuah_est;
                $sidakTphEst[$key][$key1]['skorBuah_est'] = skor_buah_tph($divBuah_est);
                $sidakTphEst[$key][$key1]['sumRst_est'] = $sumRst_est;
                $sidakTphEst[$key][$key1]['divRst_est'] = $divRst_est;
                $sidakTphEst[$key][$key1]['skorRst_est'] = skor_rst_tph($divRst_est);
                $sidakTphEst[$key][$key1]['allSkor_est'] = skor_bt_tph($divBt_est) + skor_krg_tph($divKrg_est) + skor_buah_tph($divBuah_est) + skor_rst_tph($divRst_est);
            } else {
                $sidakTphEst[$key][$key1]['jml_blok_est'] = 0;
                $sidakTphEst[$key][$key1]['luas_ha_est'] = 0;
                $sidakTphEst[$key][$key1]['bt_tph_est'] = 0;
                $sidakTphEst[$key][$key1]['bt_jln_est'] = 0;
                $sidakTphEst[$key][$key1]['bt_bin_est'] = 0;
                $sidakTphEst[$key][$key1]['tot_bt_est'] = 0;
                $sidakTphEst[$key][$key1]['divBt_est'] = 0;
                $sidakTphEst[$key][$key1]['skorBt_est'] = 0;
                $sidakTphEst[$key][$key1]['sum_krg_est'] = 0;
                $sidakTphEst[$key][$key1]['divKrg_est'] = 0;
                $sidakTphEst[$key][$key1]['skorKrg_est'] = 0;
                $sidakTphEst[$key][$key1]['sumBuah_est'] = 0;
                $sidakTphEst[$key][$key1]['divBuah_est'] = 0;
                $sidakTphEst[$key][$key1]['skorBuah_est'] = 0;
                $sidakTphEst[$key][$key1]['sumRst_est'] = 0;
                $sidakTphEst[$key][$key1]['divRst_est'] = 0;
                $sidakTphEst[$key][$key1]['skorRst_est'] = 0;
                $sidakTphEst[$key][$key1]['allSkor_est'] = 0;
            }
        }

        $brdGraphMonth = array();
        $krgGraphMonth = array();
        $buahGraphMonth = array();
        $rstGraphMonth = array();
        foreach ($sidakTphEst as $key => $value) {
            foreach ($value as $key2  => $value2) {
                $brdGraphMonth[$key][$key2]['brdGraph'] = $value2['divBt_est'];
                $krgGraphMonth[$key][$key2]['krgGraph'] = $value2['divKrg_est'];
                $buahGraphMonth[$key][$key2]['buahGraph'] = $value2['divBuah_est'];
                $rstGraphMonth[$key][$key2]['rstGraph'] = $value2['divRst_est'];
            }
        }

        $rekapBrdGraph = [];
        if ($estData !== 'CWS1' && isset($brdGraphMonth[$estData])) {
            foreach ($brdGraphMonth[$estData] as $month => $data) {
                $rekapBrdGraph[$estData][$month] = isset($data['brdGraph']) ? $data['brdGraph'] : 0;
            }
        } else {
            $rekapBrdGraph[$estData] = [
                "January" => 0,
                "February" => 0,
                "March" => 0,
                "April" => 0,
                "May" => 0,
                "June" => 0,
                "July" => 0,
                "August" => 0,
                "September" => 0,
                "October" => 0,
                "November" => 0,
                "December" => 0
            ];
        }

        $rekapKrgGraph = [];
        if ($estData !== 'CWS1' && isset($krgGraphMonth[$estData])) {
            foreach ($krgGraphMonth[$estData] as $month => $data) {
                $rekapKrgGraph[$estData][$month] = isset($data['krgGraph']) ? $data['krgGraph'] : 0;
            }
        } else {
            $rekapKrgGraph[$estData] = [
                "January" => 0,
                "February" => 0,
                "March" => 0,
                "April" => 0,
                "May" => 0,
                "June" => 0,
                "July" => 0,
                "August" => 0,
                "September" => 0,
                "October" => 0,
                "November" => 0,
                "December" => 0
            ];
        }

        $rekapBuahGraph = [];
        if ($estData !== 'CWS1' && isset($buahGraphMonth[$estData])) {
            foreach ($buahGraphMonth[$estData] as $month => $data) {
                $rekapBuahGraph[$estData][$month] = isset($data['buahGraph']) ? $data['buahGraph'] : 0;
            }
        } else {
            $rekapBuahGraph[$estData] = [
                "January" => 0,
                "February" => 0,
                "March" => 0,
                "April" => 0,
                "May" => 0,
                "June" => 0,
                "July" => 0,
                "August" => 0,
                "September" => 0,
                "October" => 0,
                "November" => 0,
                "December" => 0
            ];
        }

        $rekapRstGraph = [];
        if ($estData !== 'CWS1' && isset($rstGraphMonth[$estData])) {
            foreach ($rstGraphMonth[$estData] as $month => $data) {
                $rekapRstGraph[$estData][$month] = isset($data['rstGraph']) ? $data['rstGraph'] : 0;
            }
        } else {
            $rekapRstGraph[$estData] = [
                "January" => 0,
                "February" => 0,
                "March" => 0,
                "April" => 0,
                "May" => 0,
                "June" => 0,
                "July" => 0,
                "August" => 0,
                "September" => 0,
                "October" => 0,
                "November" => 0,
                "December" => 0
            ];
        }

        $scBrdGraph = array();
        foreach ($rekapBrdGraph as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $scBrdGraph[] = $value1;
            }
        }
        $scKrgGraph = array();
        foreach ($rekapKrgGraph as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $scKrgGraph[] = $value1;
            }
        }
        $scBuahGraph = array();
        foreach ($rekapBuahGraph as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $scBuahGraph[] = $value1;
            }
        }
        $scRstGraph = array();
        foreach ($rekapRstGraph as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $scRstGraph[] = $value1;
            }
        }
        $keysToRemove = ['SRE', 'LDE', 'SKE'];

        // Loop through the array and remove the elements with the specified keys
        // foreach ($keysToRemove as $key) {
        //     unset($arrBtTPHperEst[$key]);
        //     unset($arrKRest[$key]);
        //     unset($arrBHest[$key]);
        //     unset($arrRSest[$key]);
        // }

        // dd($scBrdGraph, $scKrgGraph, $scBuahGraph, $scRstGraph);
        $arrView = array();
        $arrView['brdGraph'] =  $scBrdGraph;
        $arrView['krgGraph'] =  $scKrgGraph;
        $arrView['buahGraph'] =  $scBuahGraph;
        $arrView['rstGraph'] =  $scRstGraph;
        echo json_encode($arrView); //di decode ke dalam bentuk json dalam vaiavel arrview yang dapat menampung banyak isi array
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
                $plotTitik[] =  '[' . $value->lon . ',' . $value->lat     . ']';
                $plotMarker[$inc]['latln'] =  '[' . $value->lat   . ',' . $value->lon . ']';
                $plotMarker[$inc]['notph'] = $value->no_tph;
                $plotMarker[$inc]['blok'] = $value->blok;
                $plotMarker[$inc]['brondol_tinggal'] = $value->bt_tph + $value->bt_jalan + $value->bt_bin;
                $plotMarker[$inc]['jum_karung'] = $value->jum_karung;
                $plotMarker[$inc]['buah_tinggal'] = $value->buah_tinggal;
                $plotMarker[$inc]['restan_unreported'] = $value->restan_unreported;
                $plotMarker[$inc]['jam'] = Carbon::parse($value->datetime)->format('H:i');
            }
            $inc++;
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

        // dd($plotTitik);
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



    public function BasidakTph($est, $start, $last)
    {
        $startDate = DateTime::createFromFormat('Y-m-d', $start);
        $endDate = clone $startDate;
        $endDate->modify('+6 days');

        $formattedStartDate = $startDate->format('d-m-Y');
        $formattedEndDate = $endDate->format('d-m-Y');

        $query = DB::connection('mysql2')->Table('sidak_tph')
            ->select('sidak_tph.*', 'estate.wil')
            ->join('estate', 'estate.est', '=', 'sidak_tph.est')
            ->where('sidak_tph.est', $est)
            ->whereBetween('sidak_tph.datetime', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
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
        // Generate the HTML select element with options


        $arrView = array();

        $arrView['est'] =  $est;
        $arrView['awal'] =  $formattedStartDate;
        $arrView['akhir'] =  $formattedEndDate;
        $arrView['filter'] =  $unique_dates;

        $formattedStartDate = $startDate->format('d-m-Y');
        $formattedEndDate = $endDate->format('d-m-Y');
        json_encode($arrView);

        return view('BaSidakTPH', $arrView);
    }


    public function filtersidaktphrekap(Request $request)
    {
        $dates = $request->input('tanggal');
        $Reg = $request->input('est');

        $perPage = 10;

        $sidak_tph = DB::connection('mysql2')->table('sidak_tph')
            ->select("sidak_tph.*", DB::raw('DATE_FORMAT(sidak_tph.datetime, "%M") as bulan'), DB::raw('DATE_FORMAT(sidak_tph.datetime, "%Y") as tahun'))
            ->where('datetime', 'like', '%' . $dates . '%')
            ->where('sidak_tph.est', $Reg)
            ->orderBy('afd', 'asc')
            ->paginate($perPage, ['*'], 'page');

        $arrView = array();
        $arrView['sidak_tph'] =  $sidak_tph;
        $arrView['tanggal'] =  $dates;

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



        // dd($id_trans, $afd_trans, $blok_trans, $bt_trans, $komentar_trans);

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
    }
    public function deleteBAsidakTPH(Request $request)
    {

        $idBuah = $request->input('id');

        // dd($idBuah);
        DB::connection('mysql2')->table('sidak_tph')
            ->where('id', $idBuah)
            ->delete();

        return response()->json(['status' => 'success']);
    }

    public function pdfBAsidak(Request $request)
    {
        $est = $request->input('est');
        $awal = $request->input('start');

        // dd($est,$awal);
        // $start = '2023-04-03';
        $startDate = DateTime::createFromFormat('d-m-Y', $awal);
        $endDate = clone $startDate;
        $endDate->modify('+6 days');

        $formattedStartDate = $startDate->format('d-m-Y');
        $formattedEndDate = $endDate->format('d-m-Y');

        // dd($start, $endDate);
          $query = DB::connection('mysql2')
            ->table('sidak_tph')
            ->select('sidak_tph.*', 'estate.wil')
            ->join('estate', 'estate.est', '=', 'sidak_tph.est')
            ->where('sidak_tph.est', $est)
            ->whereBetween('sidak_tph.datetime', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->orderBy('afd', 'asc')
            ->get();


        $query = $query->groupBy(['afd', 'blok']);
        // dd($query);
        $query = json_decode($query, true);
        // dd($query);

        $pdf = array();
        $totalblok = 0;
        $totaltph = 0;
        $totaljalan = 0;
        $totalbin = 0;
        $totalkr = 0;
        $totaltgl = 0;
        $totalunr = 0;
        foreach ($query as $key => $value) {
            $dtBlok = 0;
            $dtBlok = count($value);
            $btTPH = 0;
            $btJlan = 0;
            $btBin = 0;
            $jumKR = 0;
            $bhTGL = 0;
            $rsUNR  = 0;
            $sum_luas = 0; // Add
            foreach ($value as $key2 => $value2) {
                $sum_bt_tph = 0;
                $sum_bt_jalan = 0;
                $sum_bt_bin = 0;
                $sum_jum_karung = 0;
                $sum_buah_tinggal = 0;
                $sum_restan_unreported = 0;
                $luas_blok = 0;
                foreach ($value2 as $key3 => $value3) {
                    $sum_bt_tph += $value3['bt_tph'];
                    $sum_bt_jalan += $value3['bt_jalan'];
                    $sum_bt_bin += $value3['bt_bin'];

                    $sum_jum_karung += $value3['jum_karung'];
                    $sum_buah_tinggal += $value3['buah_tinggal'];
                    $sum_restan_unreported += $value3['restan_unreported'];
                    $luas_bk =  $value2[0]['luas'];
                }
                $pdf[$key][$key2]['bt_tph'] = $sum_bt_tph;
                $pdf[$key][$key2]['bt_jalan'] = $sum_bt_jalan;
                $pdf[$key][$key2]['bt_bin'] = $sum_bt_bin;
                $pdf[$key][$key2]['jum_karung'] = $sum_jum_karung;
                $pdf[$key][$key2]['buah_tinggal'] = $sum_buah_tinggal;
                $pdf[$key][$key2]['restan_unreported'] = $sum_restan_unreported;
                $pdf[$key][$key2]['TotalBRD'] = $sum_bt_bin + $sum_bt_jalan + $sum_bt_tph;
                $pdf[$key][$key2]['luas'] = $luas_bk;

                $sum_luas += $value2[0]['luas']; // Add this line
                $luas_blok += $luas_bk;
                $btTPH += $sum_bt_tph;
                $btJlan += $sum_bt_jalan;
                $btBin += $sum_bt_bin;
                $jumKR += $sum_jum_karung;
                $bhTGL += $sum_buah_tinggal;
                $rsUNR += $sum_restan_unreported;
            }
            $pdf[$key]['luas_blok'] = $sum_luas; //
            $pdf[$key]['jum_blok'] = $dtBlok;
            $pdf[$key]['bt_tph'] = $btTPH;
            $pdf[$key]['tph_blok'] = $dtBlok != 0 ? round($btTPH / $dtBlok, 2) : 0;
            $pdf[$key]['bt_jalan'] = $btJlan;
            $pdf[$key]['jalan_blok'] = $dtBlok != 0 ? round($btJlan / $dtBlok, 2) : 0;
            $pdf[$key]['bt_bin'] = $btBin;
            $pdf[$key]['bin_blok'] = $dtBlok != 0 ? round($btBin / $dtBlok, 2) : 0;
            $pdf[$key]['TotalBRD'] = $btTPH + $btJlan + $btBin;
            $pdf[$key]['Total_blok'] = $dtBlok != 0 ? round(($btTPH + $btJlan + $btBin) / $dtBlok, 2) : 0;
            $pdf[$key]['jum_karung'] = $jumKR;
            $pdf[$key]['blok_karung'] = $dtBlok != 0 ? round($jumKR / $dtBlok, 2) : 0;
            $pdf[$key]['buah_tinggal'] = $bhTGL;
            $pdf[$key]['blok_buah'] = $dtBlok != 0 ? round($bhTGL / $dtBlok, 2) : 0;
            $pdf[$key]['restan_unreported'] = $rsUNR;
            $pdf[$key]['blok_restanx'] = $dtBlok != 0 ? round($rsUNR / $dtBlok, 2) : 0;


            $totalblok += $dtBlok;
            $totaltph += $btTPH;
            $totaljalan += $btJlan;
            $totalbin += $btBin;

            $totalkr += $jumKR;
            $totaltgl += $bhTGL;
            $totalunr += $rsUNR;
        }
        $pdf['totalblok'] = $totalblok;
        $pdf['bt_tph'] = $totaltph;
        $pdf['tph_blok'] = $totalblok != 0 ? round($totaltph / $totalblok, 2) : 0;
        $pdf['bt_jalan'] = $totaljalan;
        $pdf['jalan_blok'] = $totalblok != 0 ? round($totaljalan / $totalblok, 2) : 0;
        $pdf['bt_bin'] = $totalbin;
        $pdf['bin_blok'] = $totalblok != 0 ? round($totalbin / $totalblok, 2) : 0;
        $pdf['TotalBRD'] = $totaltph + $totaljalan + $totalbin;
        $pdf['Total_blok'] = $totalblok != 0 ? round(($totaltph + $totaljalan + $totalbin) / $totalblok, 2) : 0;
        $pdf['jum_karung'] = $totalkr;
        $pdf['blok_karung'] = $totalblok != 0 ? round($totalkr / $totalblok, 2) : 0;
        $pdf['buah_tinggal'] = $totaltgl;
        $pdf['blok_buah'] = $totalblok != 0 ? round($totaltgl / $totalblok, 2) : 0;
        $pdf['restan_unreported'] = $totalunr;
        $pdf['blok_restan'] = $totalblok != 0 ? round($totalunr / $totalblok, 2) : 0;

        // dd($pdf);
        $arrView = array();
        $arrView['hitung'] =  $pdf;

        $arrView['est'] =  $est;
        $arrView['awal'] =  $formattedStartDate;
        $arrView['akhir'] =  $formattedEndDate;

        $pdf = PDF::loadView('Pdfsidaktphba', ['data' => $arrView]);

        $customPaper = array(360, 360, 360, 360);
        $pdf->set_paper('A2', 'landscape');
        // $pdf->set_paper('A2', 'potrait');

        $filename = 'BA Sidak TPH -' . $arrView['awal'] . '-' .  $arrView['akhir'] . '-' . $arrView['est']  . '.pdf';

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
            ->whereNotIn('estate.est', ['CWS1', 'CWS2', 'CWS3', 'SRE', 'LDE', 'SKE'])
            ->whereIn('wil', $queryReg2)->pluck('est')->toArray();

        // Return the estates as JSON data
        return response()->json([
            'estates' => $EstMapVal
        ]);
    }
}
