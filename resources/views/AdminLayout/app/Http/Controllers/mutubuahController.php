<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DateTime;
use Barryvdh\DomPDF\Facade\Pdf;


use function PHPUnit\Framework\isEmpty;

require '../app/helpers.php';
class mutubuahController extends Controller
{
    //
    public function dashboard_mutubuah(Request $request)
    {

        function findFraction($targetValue, $maxDenominator = 100)
        {
            $closestNumerator = 0;
            $closestDenominator = 1;
            $minDifference = PHP_INT_MAX;

            for ($denominator = 1; $denominator <= $maxDenominator; $denominator++) {
                $numerator = round($targetValue * $denominator);
                $currentValue = $numerator / $denominator;
                $difference = abs($targetValue - $currentValue);

                if ($difference < $minDifference) {
                    $minDifference = $difference;
                    $closestNumerator = $numerator;
                    $closestDenominator = $denominator;
                }
            }

            return [
                'numerator' => $closestNumerator,
                'denominator' => $closestDenominator,
            ];
        }

        $targetValue = 0.75;
        $fraction = findFraction($targetValue);

        // dd($fraction);


        function divide_janjang($jumlah_janjang)
        {
            $result = array(
                'bmk' => 0,
                'bmt' => 0,
                'overripe' => 0,
                'empty_bunch' => 0,
                'abnormal' => 0,
                'rd' => 0
            );

            while (array_sum($result) !== $jumlah_janjang || $result['abnormal'] > 50 || $result['abnormal'] == 0) {
                $result['bmk'] = rand(0, $jumlah_janjang);
                $result['bmt'] = rand(0, $jumlah_janjang - $result['bmk']);
                $result['overripe'] = rand(0, $jumlah_janjang - $result['bmk'] - $result['bmt']);
                $result['empty_bunch'] = rand(0, $jumlah_janjang - $result['bmk'] - $result['bmt'] - $result['overripe']);
                $result['abnormal'] = rand(0, $jumlah_janjang - $result['bmk'] - $result['bmt'] - $result['overripe'] - $result['empty_bunch']);
                $result['rd'] = $jumlah_janjang - $result['bmk'] - $result['bmt'] - $result['overripe'] - $result['empty_bunch'] - $result['abnormal'];
            }

            return $result;
        }


        $jumlah_janjang = 754;
        $result = divide_janjang($jumlah_janjang);

        // dd($result);
        $queryEst = DB::connection('mysql2')->table('estate')
            ->select('estate.*')
            ->whereNotIn('estate.est', ['CWS1', 'CWS2', 'CWS3'])
            ->get();

        $queryEst = json_decode($queryEst, true);

        $getDate = date('Y-m');


        $bulan = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        // dd($bulan);
        $shortMonth = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        $arrHeader = ['No', 'Estate', 'Kode', 'Estate Manager'];
        $arrHeader =  array_merge($arrHeader, $shortMonth);
        // array_push($arrHeader, date('Y'));

        $arrHeaderSc = ['WILAYAH', 'Group Manager'];
        $arrHeaderSc = array_merge($arrHeaderSc, $shortMonth);
        // array_push($arrHeaderSc, date('Y'));

        $arrHeaderReg = ['Region', 'Region Head'];
        $arrHeaderReg = array_merge($arrHeaderReg, $shortMonth);

        $arrHeaderTrd = ['No', 'Estate', 'Afdeling', 'Nama Asisten'];
        $arrHeaderTrd =  array_merge($arrHeaderTrd, $shortMonth);
        // array_push($arrHeaderTrd, date('Y'));

        $queryAsisten = DB::connection('mysql2')->table('asisten_qc')
            ->select('asisten_qc.*')
            ->get();

        $queryAsisten = json_decode($queryAsisten, true);


        $querySidaks = DB::connection('mysql2')->table('sidak_mutu_buah')
            ->select(DB::raw('DISTINCT YEAR(datetime) as year'))
            ->orderBy('year', 'asc')
            ->get();

        $years = [];
        foreach ($querySidaks as $sidak) {
            $years[] = $sidak->year;
        }




        // latihan

        $queryEste = DB::connection('mysql2')->table('estate')
            ->select('estate.*')
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->where('wil.regional', '1')
            ->get();
        $queryEste = json_decode($queryEste, true);

        $queryAfd = DB::connection('mysql2')->table('afdeling')
            ->select(
                'afdeling.id',
                'afdeling.nama',
                'estate.est'
            ) //buat mengambil data di estate db dan willayah db
            ->join('estate', 'estate.id', '=', 'afdeling.estate') //kemudian di join untuk mengambil est perwilayah
            ->get();
        $queryAfd = json_decode($queryAfd, true);

        $queryMTbuahv2 = DB::connection('mysql2')->table('sidak_mutu_buah')
            ->select(
                "sidak_mutu_buah.*",
                DB::raw('DATE_FORMAT(sidak_mutu_buah.datetime, "%M") as bulan'),
                DB::raw('DATE_FORMAT(sidak_mutu_buah.datetime, "%Y") as tahun')
            )
            ->where('sidak_mutu_buah.datetime', 'like', '%' . '2023-04' . '%')
            ->whereIn('estate', ['Plasma1', 'Plasma2', 'Plasma3'])
            // ->whereBetween('sidak_mutu_buah.datetime', ['2023-04-03', '2023-04-09'])
            ->get();
        $queryMTbuahv2 = $queryMTbuahv2->groupBy(['estate', 'afdeling']);
        $queryMTbuahv2 = json_decode($queryMTbuahv2, true);
        $databulananBuahv2 = array();
        foreach ($queryMTbuahv2 as $key => $value) {
            foreach ($value as $key2 => $value2) {
                foreach ($value2 as $key3 => $value3) {

                    $databulananBuahv2[$key][$key2][$key3] = $value3;
                }
            }
        }

        $defPerbulanWilv2 = array();

        foreach ($queryEste as $key2 => $value2) {
            foreach ($queryAfd as $key3 => $value3) {
                if ($value2['est'] == $value3['est']) {
                    $defPerbulanWilv2[$value2['est']][$value3['nama']] = 0;
                }
            }
        }

        $mtancakWIltab1 = array();
        foreach ($queryEste as $key => $value) {
            foreach ($defPerbulanWilv2 as $key2 => $value2) {
                if ($value['est'] == $key2) {
                    $mtancakWIltab1[$value['wil']][$key2] = array_merge($mtancakWIltab1[$value['wil']][$key2] ?? [], $value2);
                }
            }
        }


        $RegsTRy = DB::connection('mysql2')->table('reg')
            ->select('reg.*')
            ->get();
        $RegsTRy = json_decode($RegsTRy, true);
        // dd($RegsTRy);


        foreach ($defPerbulanWilv2 as $estateKey => $afdelingArray) {
            foreach ($afdelingArray as $afdelingKey => $afdelingValue) {
                if (isset($databulananBuahv2[$estateKey]) && isset($databulananBuahv2[$estateKey][$afdelingKey])) {
                    $defPerbulanWilv2[$estateKey][$afdelingKey] = $databulananBuahv2[$estateKey][$afdelingKey];
                }
            }
        }
        function luluv2($values)
        {
            foreach ($values as $value) {
                if ($value > 0) {
                    return true;
                }
            }
            return false;
        }
        // dd($defPerbulanWilv2);
        $sidak_buahv2 = array();

        foreach ($defPerbulanWilv2 as $key => $value) {
            $totalJJG = 0;
            $totaltnpBRD = 0;
            $totalkrgBRD = 0;
            $totalabr = 0;
            $TotPersenTNP = 0;
            $TotPersenKRG = 0;
            $totJJG = 0;
            $totPersenTOtaljjg = 0;
            $totSkor_total = 0;
            $totoverripe = 0;
            $totempty = 0;
            $totJJG_matang = 0;
            $totPer_jjgMtng = 0;
            $totPer_over = 0;
            $totSkor_Over = 0;
            $totPer_Empty = 0;
            $totSkor_Empty = 0;
            $totVcut = 0;
            $totPer_vcut =  0;
            $totSkor_Vcut =  0;
            $totPer_abr =  0;
            $totRD = 0;
            $totPer_rd = 0;
            $totBlok = 0;
            $totKR = 0;
            $tot_krS = 0;
            $totPer_kr = 0;
            $totSkor_kr = 0;
            $totALlskor = 0;
            $totKategor = 0;
            $estatex = '';
            $name_asist = '';
            foreach ($value as $key1 => $value1) {
                $totSkor_jjgMtng = 0;
                if (is_array($value1)) {
                    $jjg_sample = 0;
                    $tnpBRD = 0;
                    $krgBRD = 0;
                    $abr = 0;
                    $skor_total = 0;
                    $overripe = 0;
                    $empty = 0;
                    $vcut = 0;
                    $rd = 0;
                    $sum_kr = 0;
                    $allSkor = 0;
                    $combination_counts = array();


                    foreach ($value1 as $key2 => $value2) {
                        $combination = $value2['blok'] . ' ' . $value2['estate'] . ' ' . $value2['afdeling'] . ' ' . $value2['tph_baris'];
                        if (!isset($combination_counts[$combination])) {
                            $combination_counts[$combination] = 0;
                        }
                        $jjg_sample += $value2['jumlah_jjg'];
                        $tnpBRD += $value2['bmt'];
                        $krgBRD += $value2['bmk'];
                        $abr += $value2['abnormal'];
                        $overripe += $value2['overripe'];
                        $empty += $value2['empty_bunch'];
                        $vcut += $value2['vcut'];
                        $rd += $value2['rd'];
                        $sum_kr += $value2['alas_br'];
                    }
                    $dataBLok = count($combination_counts);
                    if ($sum_kr != 0) {
                        $total_kr = round($sum_kr / $dataBLok, 2);
                    } else {
                        $total_kr = 0;
                    }
                    $per_kr = round($total_kr * 100, 2);
                    $skor_total = round((($tnpBRD + $krgBRD) / ($jjg_sample - $abr)) * 100, 2);
                    $skor_jjgMSk = round(($jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr)) / ($jjg_sample - $abr) * 100, 2);
                    $skor_lewatMTng =  round(($overripe / ($jjg_sample - $abr)) * 100, 2);
                    $skor_jjgKosong =  round(($empty / ($jjg_sample - $abr)) * 100, 2);
                    $skor_vcut =   round(($vcut / $jjg_sample) * 100, 2);
                    $allSkor = sidak_brdTotal($skor_total) +  sidak_matangSKOR($skor_jjgMSk) +  sidak_lwtMatang($skor_lewatMTng) + sidak_jjgKosong($skor_jjgKosong) + sidak_tangkaiP($skor_vcut) + sidak_PengBRD($per_kr);


                    foreach ($queryAsisten as $ast => $asisten) {
                        if ($key === $asisten['est'] && $key1 === $asisten['afd']) {

                            $sidak_buahv2[$key][$key1]['nama_staff'] = $asisten['nama'];
                        }
                    }
                    $sidak_buahv2[$key][$key1]['reg'] = 'REG-I';
                    $sidak_buahv2[$key][$key1]['pt'] = 'SSMS';
                    $sidak_buahv2[$key][$key1]['Jumlah_janjang'] = $jjg_sample;
                    $sidak_buahv2[$key][$key1]['blok'] = $dataBLok;
                    $sidak_buahv2[$key][$key1]['est'] = $key;
                    $sidak_buahv2[$key][$key1]['afd'] = $key1;

                    $sidak_buahv2[$key][$key1]['tnp_brd'] = $tnpBRD;
                    $sidak_buahv2[$key][$key1]['krg_brd'] = $krgBRD;
                    $sidak_buahv2[$key][$key1]['persenTNP_brd'] = round(($tnpBRD / ($jjg_sample - $abr)) * 100, 2);
                    $sidak_buahv2[$key][$key1]['persenKRG_brd'] = round(($krgBRD / ($jjg_sample - $abr)) * 100, 2);
                    $sidak_buahv2[$key][$key1]['total_jjg'] = $tnpBRD + $krgBRD;
                    $sidak_buahv2[$key][$key1]['persen_totalJjg'] = $skor_total;
                    $sidak_buahv2[$key][$key1]['skor_total'] = sidak_brdTotal($skor_total);
                    $sidak_buahv2[$key][$key1]['jjg_matang'] = $jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr);
                    $sidak_buahv2[$key][$key1]['persen_jjgMtang'] = $skor_jjgMSk;
                    $sidak_buahv2[$key][$key1]['skor_jjgMatang'] = sidak_matangSKOR($skor_jjgMSk);
                    $sidak_buahv2[$key][$key1]['lewat_matang'] = $overripe;
                    $sidak_buahv2[$key][$key1]['persen_lwtMtng'] =  $skor_lewatMTng;
                    $sidak_buahv2[$key][$key1]['skor_lewatMTng'] = sidak_lwtMatang($skor_lewatMTng);
                    $sidak_buahv2[$key][$key1]['janjang_kosong'] = $empty;
                    $sidak_buahv2[$key][$key1]['persen_kosong'] = $skor_jjgKosong;
                    $sidak_buahv2[$key][$key1]['skor_kosong'] = sidak_jjgKosong($skor_jjgKosong);
                    $sidak_buahv2[$key][$key1]['vcut'] = $vcut;
                    $sidak_buahv2[$key][$key1]['vcut_persen'] = $skor_vcut;
                    $sidak_buahv2[$key][$key1]['vcut_skor'] = sidak_tangkaiP($skor_vcut);
                    $sidak_buahv2[$key][$key1]['abnormal'] = $abr;
                    $sidak_buahv2[$key][$key1]['abnormal_persen'] = round(($abr / $jjg_sample) * 100, 2);
                    $sidak_buahv2[$key][$key1]['rat_dmg'] = $rd;
                    $sidak_buahv2[$key][$key1]['rd_persen'] = round(($rd / $jjg_sample) * 100, 2);
                    $sidak_buahv2[$key][$key1]['TPH'] = $total_kr;
                    $sidak_buahv2[$key][$key1]['persen_krg'] = $per_kr;
                    $sidak_buahv2[$key][$key1]['skor_kr'] = sidak_PengBRD($per_kr);
                    $sidak_buahv2[$key][$key1]['All_skor'] = $allSkor;
                    $sidak_buahv2[$key][$key1]['kategori'] = sidak_akhir($allSkor);

                    $totalJJG += $jjg_sample;
                    $totaltnpBRD += $tnpBRD;
                    $totalkrgBRD += $krgBRD;
                    $totalabr += $abr;
                    $TotPersenTNP = round(($totaltnpBRD / ($totalJJG - $totalabr)) * 100, 2);
                    $TotPersenKRG = round(($totalkrgBRD / ($totalJJG - $totalabr)) * 100, 2);
                    $totJJG = $totaltnpBRD + $totalkrgBRD;
                    $totPersenTOtaljjg = round((($totaltnpBRD + $totalkrgBRD) / ($totalJJG - $totalabr)) * 100, 2);
                    $totSkor_total = sidak_brdTotal($totPersenTOtaljjg);
                    $totoverripe += $overripe;
                    $totempty += $empty;
                    $totJJG_matang = $totalJJG - ($totaltnpBRD + $totalkrgBRD + $totoverripe + $totempty + $totalabr);
                    $totPer_jjgMtng = round($totJJG_matang / ($totalJJG - $totalabr) * 100, 2);

                    $totSkor_jjgMtng = sidak_matangSKOR($totPer_jjgMtng);
                    $totPer_over = round(($totoverripe / ($totalJJG - $totalabr)) * 100, 2);
                    $totSkor_Over = sidak_lwtMatang($totPer_over);
                    $totPer_Empty = round(($totempty / ($totalJJG - $totalabr)) * 100, 2);
                    $totSkor_Empty = sidak_jjgKosong($totPer_Empty);
                    $totVcut += $vcut;
                    $totPer_vcut =   round(($totVcut / $totalJJG) * 100, 2);
                    $totSkor_Vcut =  sidak_tangkaiP($totPer_vcut);
                    $totPer_abr =  round(($totalabr / $totalJJG) * 100, 2);
                    $totRD += $rd;
                    $totPer_rd = round(($totRD / $totalJJG) * 100, 2);
                    $totBlok += $dataBLok;
                    $totKR += $sum_kr;
                    if ($totKR != 0) {
                        $tot_krS = round($totKR / $totBlok, 2);
                    } else {
                        $tot_krS = 0;
                    }
                    $totPer_kr = round($tot_krS * 100, 2);
                    $totSkor_kr = sidak_PengBRD($totPer_kr);
                    $totALlskor = sidak_brdTotal($totPersenTOtaljjg) + sidak_matangSKOR($totPer_jjgMtng) + sidak_lwtMatang($totPer_over) + sidak_jjgKosong($totPer_Empty) + sidak_tangkaiP($totPer_vcut) + sidak_PengBRD($totPer_kr);

                    $totKategor = sidak_akhir($totALlskor);

                    $estatex = $key;
                }

                $gm = 'GM';
                foreach ($queryAsisten as $ast => $asisten) {
                    if ($estatex === $asisten['est'] && $gm === $asisten['afd']) {
                        $name_asist = $asisten['nama'];
                    }
                }
                $totalValues = [

                    'reg' => '',
                    'pt' => '',
                    'nama_staff' => $name_asist,
                    'Jumlah_janjang' => $totalJJG,
                    'est' => $estatex,
                    'afd' => '',
                    'karung' => $totKR,
                    'blok' => $totBlok,
                    'tnp_brd' => $totaltnpBRD,
                    'krg_brd' => $totalkrgBRD,
                    'persenTNP_brd' => $TotPersenTNP,
                    'persenKRG_brd' => $TotPersenKRG,
                    'total_jjg' => $totJJG,
                    'persen_totalJjg' => $totPersenTOtaljjg,
                    'skor_total' => $totSkor_total,
                    'jjg_matang' => $totJJG_matang,
                    'persen_jjgMtang' => $totPer_jjgMtng,
                    'skor_jjgMatang' => $totSkor_jjgMtng,
                    'lewat_matang' => $totoverripe,
                    'persen_lwtMtng' => $totPer_over,
                    'skor_lewatMTng' => $totSkor_Over,
                    'janjang_kosong' => $totempty,
                    'persen_kosong' => $totPer_Empty,
                    'skor_kosong' => $totSkor_Empty,
                    'vcut' => $totVcut,
                    'vcut_persen' => $totPer_vcut,
                    'vcut_skor' => $totSkor_Vcut,
                    'abnormal' => $totalabr,
                    'abnormal_persen' => $totPer_abr,
                    'rat_dmg' => $totRD,
                    'rd_persen' => $totPer_rd,
                    'TPH' => $tot_krS,
                    'persen_krg' => $totPer_kr,
                    'skor_kr' => $totSkor_kr,
                    'All_skor' => $totALlskor,
                    'kategori' => $totKategor,
                    // Add more variables here
                ];

                if (luluv2($totalValues)) {
                    $sidak_buahv2[$key][$key] = $totalValues;
                }
            }
        }
        // dd($sidak_buahv2);

        $new_sidak_buahv2 = array();

        foreach ($sidak_buahv2 as $key => $value) {
            $new_subarray = array();

            foreach ($value as $sub_key => $sub_value) {
                if ($sub_key != $key) {
                    $new_subarray[$sub_key] = $sub_value;
                }
            }

            if (isset($value[$key])) {
                $new_subarray[$key] = $value[$key];
            }

            $new_sidak_buahv2[$key] = $new_subarray;
        }

        $sidak_buahv2 = $new_sidak_buahv2;




        $mutu_buahsv2 = array();
        foreach ($queryEste as $key => $value) {
            foreach ($sidak_buahv2 as $key2 => $value2) {
                if ($value['est'] == $key2) {
                    $mutu_buahsv2[$value['wil']][$key2] = array_merge($mutu_buahsv2[$value['wil']][$key2] ?? [], $value2);
                }
            }
        }

        $new_sidakBuahv2 = array();
        foreach ($mutu_buahsv2 as $primaryKey => $primaryValue) {
            $jjg_sample = 0;
            $tnpBRD = 0;
            $krgBRD = 0;
            $abr = 0;
            $skor_total = 0;
            $overripe = 0;
            $empty = 0;
            $vcut = 0;
            $rd = 0;
            $sum_kr = 0;
            $allSkor = 0;
            $blok = 0;
            foreach ($primaryValue as $key => $value) {
                if (isset($value[$key]['Jumlah_janjang'])) {

                    $jjg_sample += $value[$key]['Jumlah_janjang'];
                    $tnpBRD += $value[$key]['tnp_brd'];
                    $krgBRD += $value[$key]['krg_brd'];
                    $abr += $value[$key]['abnormal'];
                    $overripe += $value[$key]['lewat_matang'];
                    $empty += $value[$key]['janjang_kosong'];
                    $vcut += $value[$key]['vcut'];
                    $rd += $value[$key]['rat_dmg'];
                    $sum_kr += $value[$key]['karung'];
                    $blok += $value[$key]['blok'];
                }
                $new_sidakBuahv2[$primaryKey][$key] = $value;
            }


            if ($sum_kr != 0) {
                $total_kr = round($sum_kr / $blok, 2);
            } else {
                $total_kr = 0;
            }
            $per_kr = round($total_kr * 100, 2);
            $skor_total = round((($tnpBRD + $krgBRD) / ($jjg_sample - $abr)) * 100, 2);
            $skor_jjgMSk = round(($jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr)) / ($jjg_sample - $abr) * 100, 2);
            $skor_lewatMTng =  round(($overripe / ($jjg_sample - $abr)) * 100, 2);
            $skor_jjgKosong =  round(($empty / ($jjg_sample - $abr)) * 100, 2);
            $skor_vcut =   round(($vcut / $jjg_sample) * 100, 2);
            $allSkor = sidak_brdTotal($skor_total) +  sidak_matangSKOR($skor_jjgMSk) +  sidak_lwtMatang($skor_lewatMTng) + sidak_jjgKosong($skor_jjgKosong) + sidak_tangkaiP($skor_vcut) + sidak_PengBRD($per_kr);



            $gm = 'EM';
            if ($primaryKey === 1) {
                $namewil = 'WIL-I';
            } else if ($primaryKey === 2) {
                $namewil = 'WIL-II';
            } else if ($primaryKey === 3) {
                $namewil = 'WIL-III';
            } else if ($primaryKey === 4) {
                $namewil = 'WIL-IV';
            } else if ($primaryKey === 5) {
                $namewil = 'WIL-V';
            } else if ($primaryKey === 6) {
                $namewil = 'WIL-VI';
            } else if ($primaryKey === 7) {
                $namewil = 'WIL-VII';
            } else if ($primaryKey === 8) {
                $namewil = 'WIL-VIII';
            }
            $wil = 'Plasma1';

            $nestedData = [];

            $nestedData['reg'] = 'WiL';
            $nestedData['pt'] = 'SSMS';
            $nestedData['Jumlah_janjang'] = $jjg_sample;
            $nestedData['blok'] = $blok;
            $nestedData['est'] = 'Wil-' . $primaryKey;


            foreach ($queryAsisten as $ast => $asisten) {
                if ($wil === $asisten['est'] && $gm === $asisten['afd']) {
                    $nestedData['nama_asisten'] = $asisten['nama'];
                }
            }
            $nestedData['afd'] = $key1;
            $nestedData['tnp_brd'] = $tnpBRD;
            $nestedData['krg_brd'] = $krgBRD;
            $nestedData['persenTNP_brd'] = round(($tnpBRD / ($jjg_sample - $abr)) * 100, 2);
            $nestedData['persenKRG_brd'] = round(($krgBRD / ($jjg_sample - $abr)) * 100, 2);
            $nestedData['total_jjg'] = $tnpBRD + $krgBRD;
            $nestedData['persen_totalJjg'] = $skor_total;
            $nestedData['skor_total'] = sidak_brdTotal($skor_total);
            $nestedData['jjg_matang'] = $jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr);
            $nestedData['persen_jjgMtang'] = $skor_jjgMSk;
            $nestedData['skor_jjgMatang'] = sidak_matangSKOR($skor_jjgMSk);
            $nestedData['lewat_matang'] = $overripe;
            $nestedData['persen_lwtMtng'] =  $skor_lewatMTng;
            $nestedData['skor_lewatMTng'] = sidak_lwtMatang($skor_lewatMTng);
            $nestedData['janjang_kosong'] = $empty;
            $nestedData['persen_kosong'] = $skor_jjgKosong;
            $nestedData['skor_kosong'] = sidak_jjgKosong($skor_jjgKosong);
            $nestedData['vcut'] = $vcut;
            $nestedData['vcut_persen'] = $skor_vcut;
            $nestedData['vcut_skor'] = sidak_tangkaiP($skor_vcut);
            $nestedData['abnormal'] = $abr;
            $nestedData['abnormal_persen'] = round(($abr / $jjg_sample) * 100, 2);
            $nestedData['rat_dmg'] = $rd;
            $nestedData['rd_persen'] = round(($rd / $jjg_sample) * 100, 2);
            $nestedData['TPH'] = $total_kr;
            $nestedData['persen_krg'] = $per_kr;
            $nestedData['karung_est'] = $sum_kr;
            $nestedData['skor_kr'] = sidak_PengBRD($per_kr);

            // Store the nested data array inside the $new_sidakBuahv2 array with the key $primaryKey
            $new_sidakBuahv2[$primaryKey][$primaryKey] = $nestedData;
        }
        // dd($new_sidakBuahv2);



        $regional_arraysv2 = [
            'Regional' => $new_sidakBuahv2
        ];

        // dd($regional_arraysv2);

        $jjg_sum = 0;
        $jjg_sample = 0;
        $tnpBRD = 0;
        $krgBRD = 0;
        $abr = 0;
        $skor_total = 0;
        $overripe = 0;
        $empty = 0;
        $vcut = 0;
        $rd = 0;
        $sum_kr = 0;
        $allSkor = 0;
        $blok = 0;
        $primaryKey = '-';
        $nama_rh = '-';
        foreach ($regional_arraysv2["Regional"] as $key => $value) {
            if (isset($value[$key])) {
                $jjg_sample += $value[$key]['Jumlah_janjang'];
                $tnpBRD += $value[$key]['tnp_brd'];
                $krgBRD += $value[$key]['krg_brd'];
                $abr += $value[$key]['abnormal'];
                $overripe += $value[$key]['lewat_matang'];
                $empty += $value[$key]['janjang_kosong'];
                $vcut += $value[$key]['vcut'];
                $rd += $value[$key]['rat_dmg'];
                $sum_kr += $value[$key]['karung_est'];
                $blok += $value[$key]['blok'];
            }
        }

        if ($sum_kr != 0) {
            $total_kr = round($sum_kr / $blok, 2);
        } else {
            $total_kr = 0;
        }
        $per_kr = round($total_kr * 100, 2);
        $skor_total = round((($tnpBRD + $krgBRD) / ($jjg_sample - $abr !== 0 ? $jjg_sample - $abr : 1)) * 100, 2);
        $skor_jjgMSk = round(($jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr)) / ($jjg_sample - $abr !== 0 ? $jjg_sample - $abr : 1) * 100, 2);
        $skor_lewatMTng =  round(($overripe / ($jjg_sample - $abr !== 0 ? $jjg_sample - $abr : 1)) * 100, 2);
        $skor_jjgKosong =  round(($empty / ($jjg_sample - $abr !== 0 ? $jjg_sample - $abr : 1)) * 100, 2);
        $skor_vcut =   round(($vcut / ($jjg_sample !== 0 ? $jjg_sample : 1)) * 100, 2);

        $allSkor = sidak_brdTotal($skor_total) +  sidak_matangSKOR($skor_jjgMSk) +  sidak_lwtMatang($skor_lewatMTng) + sidak_jjgKosong($skor_jjgKosong) + sidak_tangkaiP($skor_vcut) + sidak_PengBRD($per_kr);

        if (in_array($key, [1, 2, 3])) {
            $regional = 'REG-I';
        } else if (in_array($key, [4, 5, 6])) {
            $regional = 'REG-II';
        } else {
            $regional = 'REG-III';
        }

        $rh = 'RH';

        foreach ($queryAsisten as $ast => $asisten) {
            if ($regional === $asisten['est'] && $rh === $asisten['afd']) {
                $nama_rh = $asisten['nama'];
            }
        }


        $regional_arrays["Regional"]['Total'] = [
            'reg' => $regional,
            'pt' => '-',
            'Jumlah_janjang' => $jjg_sample,
            'blok' => $blok,
            'est' => 'Wil-' . $primaryKey,
            'afd' => $key,
            'nama_staff' => $nama_rh,
            'tnp_brd' => $tnpBRD,
            'krg_brd' => $krgBRD,
            'persenTNP_brd' => round(($jjg_sample - $abr !== 0 ? ($tnpBRD / ($jjg_sample - $abr)) : 0) * 100, 2),
            'persenKRG_brd' => round(($jjg_sample - $abr !== 0 ? ($krgBRD / ($jjg_sample - $abr)) : 0) * 100, 2),

            'total_jjg' => $tnpBRD + $krgBRD,
            'persen_totalJjg' => $skor_total,
            'skor_total' => sidak_brdTotal($skor_total),
            'jjg_matang' => $jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr),
            'persen_jjgMtang' => $skor_jjgMSk,
            'skor_jjgMatang' => sidak_matangSKOR($skor_jjgMSk),
            'lewat_matang' => $overripe,
            'persen_lwtMtng' => $skor_lewatMTng,
            'skor_lewatMTng' => sidak_lwtMatang($skor_lewatMTng),
            'janjang_kosong' => $empty,
            'persen_kosong' => $skor_jjgKosong,
            'skor_kosong' => sidak_jjgKosong($skor_jjgKosong),
            'vcut' => $vcut,
            'vcut_persen' => $skor_vcut,
            'vcut_skor' => sidak_tangkaiP($skor_vcut),
            'abnormal' => $abr,
            'abnormal_persen' => round(($jjg_sample !== 0 ? ($abr / $jjg_sample) : 0) * 100, 2),
            'rat_dmg' => $rd,
            'rd_persen' => round(($jjg_sample !== 0 ? ($rd / $jjg_sample) : 0) * 100, 2),

            'TPH' => $total_kr,
            'persen_krg' => $per_kr,
            'skor_kr' => sidak_PengBRD($per_kr),
        ];


        $optionREg = DB::connection('mysql2')->table('reg')
            ->select('reg.*')
            ->whereNotIn('reg.id', [5])
            // ->where('wil.regional', 1)
            ->get();


        $optionREg = json_decode($optionREg, true);
        // dd($optionREg);

        // dd($mutu_buahs, $sidak_buah);
        // $arrView['list_bulan'] =  $bulan;
        return view('dashboard_mutubuah', [
            'arrHeader' => $arrHeader,
            'arrHeaderSc' => $arrHeaderSc,
            'arrHeaderTrd' => $arrHeaderTrd,
            'arrHeaderReg' => $arrHeaderReg,
            'list_bulan' => $bulan,
            'list_tahun' => $years,
            'option_reg' => $optionREg,
        ]);
    }

    
    public function getWeek(Request $request)
    {
        $regional = $request->input('reg');
        // $startWeek = $request->input('startWeek');
        // $lastWeek = $request->input('lastWeek');
        $bulan = $request->input('bulan');

        $queryAsisten = DB::connection('mysql2')->table('asisten_qc')
            ->select('asisten_qc.*')
            ->get();

        $queryAsisten = json_decode($queryAsisten, true);

        // dd($value2['datetime'], $endDate);
        $queryEste = DB::connection('mysql2')->table('estate')
            ->select('estate.*')
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->where('wil.regional', $regional)
            ->whereNotIn('estate.est', ['SRE', 'LDE', 'SKE', 'CWS1', 'CWS2', 'CWS3'])
            ->get();
        $queryEste = json_decode($queryEste, true);

        $estev2 = DB::connection('mysql2')->table('estate')
            ->select('estate.*')
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->where('wil.regional', $regional)
            ->whereNotIn('estate.est', ['SRE', 'LDE', 'SKE', 'CWS1', 'CWS2', 'CWS3'])
            ->pluck('est');
        $estev2 = json_decode($estev2, true);

        $queryAfd = DB::connection('mysql2')->table('afdeling')
            ->select(
                'afdeling.id',
                'afdeling.nama',
                'estate.est'
            ) //buat mengambil data di estate db dan willayah db
            ->join('estate', 'estate.id', '=', 'afdeling.estate') //kemudian di join untuk mengambil est perwilayah
            ->get();
        $queryAfd = json_decode($queryAfd, true);

        $queryMTbuah = DB::connection('mysql2')->table('sidak_mutu_buah')
            ->select(
                "sidak_mutu_buah.*",
                DB::raw('DATE_FORMAT(sidak_mutu_buah.datetime, "%M") as bulan'),
                DB::raw('DATE_FORMAT(sidak_mutu_buah.datetime, "%Y") as tahun')
            )
            // ->whereBetween('sidak_mutu_buah.datetime', [$startDate, $endDate])
            ->where('sidak_mutu_buah.datetime', 'like', '%' . $bulan . '%')

            // ->whereBetween('sidak_mutu_buah.datetime', ['2023-04-03', '2023-04-09'])
            ->get();
        $queryMTbuah = $queryMTbuah->groupBy(['estate', 'afdeling']);
        $queryMTbuah = json_decode($queryMTbuah, true);

        $databulananBuah = array();
        foreach ($queryMTbuah as $key => $value) {
            foreach ($value as $key2 => $value2) {
                foreach ($value2 as $key3 => $value3) {

                    $databulananBuah[$key][$key2][$key3] = $value3;
                }
            }
        }

        $defPerbulanWil = array();

        foreach ($queryEste as $key2 => $value2) {
            foreach ($queryAfd as $key3 => $value3) {
                if ($value2['est'] == $value3['est']) {
                    $defPerbulanWil[$value2['est']][$value3['nama']] = 0;
                }
            }
        }



        foreach ($defPerbulanWil as $estateKey => $afdelingArray) {
            foreach ($afdelingArray as $afdelingKey => $afdelingValue) {
                if (isset($databulananBuah[$estateKey]) && isset($databulananBuah[$estateKey][$afdelingKey])) {
                    $defPerbulanWil[$estateKey][$afdelingKey] = $databulananBuah[$estateKey][$afdelingKey];
                }
            }
        }

        // dd($defPerbulanWil);
        $sidak_buah = array();
        foreach ($defPerbulanWil as $key => $value) {
            $totalJJG = 0;
            $totaltnpBRD = 0;
            $totalkrgBRD = 0;
            $totalabr = 0;
            $TotPersenTNP = 0;
            $TotPersenKRG = 0;
            $totJJG = 0;
            $totPersenTOtaljjg = 0;
            $totSkor_total = 0;
            $totoverripe = 0;
            $totempty = 0;
            $totJJG_matang = 0;
            $totPer_jjgMtng = 0;
            $totPer_over = 0;
            $totSkor_Over = 0;
            $totPer_Empty = 0;
            $totSkor_Empty = 0;
            $totVcut = 0;
            $totPer_vcut =  0;
            $totSkor_Vcut =  0;
            $totPer_abr =  0;
            $totRD = 0;
            $totPer_rd = 0;
            $totBlok = 0;
            $totKR = 0;
            $tot_krS = 0;
            $totPer_kr = 0;
            $totSkor_kr = 0;
            $totALlskor = 0;
            $totKategor = 0;
            foreach ($value as $key1 => $value1) {
                if (is_array($value1)) {
                    $jjg_sample = 0;
                    $tnpBRD = 0;
                    $krgBRD = 0;
                    $abr = 0;
                    $skor_total = 0;
                    $overripe = 0;
                    $empty = 0;
                    $vcut = 0;
                    $rd = 0;
                    $sum_kr = 0;
                    $allSkor = 0;
                    $combination_counts = array();

                    foreach ($value1 as $key2 => $value2) {
                        $combination = $value2['blok'] . ' ' . $value2['estate'] . ' ' . $value2['afdeling'] . ' ' . $value2['tph_baris'];
                        if (!isset($combination_counts[$combination])) {
                            $combination_counts[$combination] = 0;
                        }
                        $jjg_sample += $value2['jumlah_jjg'];
                        $tnpBRD += $value2['bmt'];
                        $krgBRD += $value2['bmk'];
                        $abr += $value2['abnormal'];
                        $overripe += $value2['overripe'];
                        $empty += $value2['empty_bunch'];
                        $vcut += $value2['vcut'];
                        $rd += $value2['rd'];
                        $sum_kr += $value2['alas_br'];
                    }
                    $dataBLok = count($combination_counts);
                    if ($sum_kr != 0) {
                        $total_kr = round($sum_kr / $dataBLok, 2);
                    } else {
                        $total_kr = 0;
                    }
                    $per_kr = round($total_kr * 100, 2);
                    $skor_total = round((($tnpBRD + $krgBRD) / ($jjg_sample - $abr)) * 100, 2);
                    $skor_jjgMSk = round(($jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr)) / ($jjg_sample - $abr) * 100, 2);
                    $skor_lewatMTng =  round(($overripe / ($jjg_sample - $abr)) * 100, 2);
                    $skor_jjgKosong =  round(($empty / ($jjg_sample - $abr)) * 100, 2);
                    $skor_vcut =   round(($vcut / $jjg_sample) * 100, 2);
                    $allSkor = sidak_brdTotal($skor_total) +  sidak_matangSKOR($skor_jjgMSk) +  sidak_lwtMatang($skor_lewatMTng) + sidak_jjgKosong($skor_jjgKosong) + sidak_tangkaiP($skor_vcut) + sidak_PengBRD($per_kr);

                    $sidak_buah[$key][$key1]['Jumlah_janjang'] = $jjg_sample;
                    $sidak_buah[$key][$key1]['blok'] = $dataBLok;
                    $sidak_buah[$key][$key1]['est'] = $key;
                    $sidak_buah[$key][$key1]['afd'] = $key1;
                    $sidak_buah[$key][$key1]['nama_staff'] = '-';
                    $sidak_buah[$key][$key1]['tnp_brd'] = $tnpBRD;
                    $sidak_buah[$key][$key1]['krg_brd'] = $krgBRD;
                    $sidak_buah[$key][$key1]['persenTNP_brd'] = round(($tnpBRD / ($jjg_sample - $abr)) * 100, 2);
                    $sidak_buah[$key][$key1]['persenKRG_brd'] = round(($krgBRD / ($jjg_sample - $abr)) * 100, 2);
                    $sidak_buah[$key][$key1]['total_jjg'] = $tnpBRD + $krgBRD;
                    $sidak_buah[$key][$key1]['persen_totalJjg'] = $skor_total;
                    $sidak_buah[$key][$key1]['skor_total'] = sidak_brdTotal($skor_total);
                    $sidak_buah[$key][$key1]['jjg_matang'] = $jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr);
                    $sidak_buah[$key][$key1]['persen_jjgMtang'] = $skor_jjgMSk;
                    $sidak_buah[$key][$key1]['skor_jjgMatang'] = sidak_matangSKOR($skor_jjgMSk);
                    $sidak_buah[$key][$key1]['lewat_matang'] = $overripe;
                    $sidak_buah[$key][$key1]['persen_lwtMtng'] =  $skor_lewatMTng;
                    $sidak_buah[$key][$key1]['skor_lewatMTng'] = sidak_lwtMatang($skor_lewatMTng);
                    $sidak_buah[$key][$key1]['janjang_kosong'] = $empty;
                    $sidak_buah[$key][$key1]['persen_kosong'] = $skor_jjgKosong;
                    $sidak_buah[$key][$key1]['skor_kosong'] = sidak_jjgKosong($skor_jjgKosong);
                    $sidak_buah[$key][$key1]['vcut'] = $vcut;
                    $sidak_buah[$key][$key1]['karung'] = $sum_kr;
                    $sidak_buah[$key][$key1]['vcut_persen'] = $skor_vcut;
                    $sidak_buah[$key][$key1]['vcut_skor'] = sidak_tangkaiP($skor_vcut);
                    $sidak_buah[$key][$key1]['abnormal'] = $abr;
                    $sidak_buah[$key][$key1]['abnormal_persen'] = round(($abr / $jjg_sample) * 100, 2);
                    $sidak_buah[$key][$key1]['rat_dmg'] = $rd;
                    $sidak_buah[$key][$key1]['rd_persen'] = round(($rd / $jjg_sample) * 100, 2);
                    $sidak_buah[$key][$key1]['TPH'] = $total_kr;
                    $sidak_buah[$key][$key1]['persen_krg'] = $per_kr;
                    $sidak_buah[$key][$key1]['skor_kr'] = sidak_PengBRD($per_kr);
                    $sidak_buah[$key][$key1]['All_skor'] = $allSkor;
                    $sidak_buah[$key][$key1]['kategori'] = sidak_akhir($allSkor);

                    foreach ($queryAsisten as $ast => $asisten) {
                        if ($key === $asisten['est'] && $key1 === $asisten['afd']) {
                            $sidak_buah[$key][$key1]['nama_asisten'] = $asisten['nama'];
                        }
                    }


                    $totalJJG += $jjg_sample;
                    $totaltnpBRD += $tnpBRD;
                    $totalkrgBRD += $krgBRD;
                    $totalabr += $abr;
                    $TotPersenTNP = round(($totaltnpBRD / ($totalJJG - $totalabr)) * 100, 2);
                    $TotPersenKRG = round(($totalkrgBRD / ($totalJJG - $totalabr)) * 100, 2);
                    $totJJG = $totaltnpBRD + $totalkrgBRD;
                    $totPersenTOtaljjg = round((($totaltnpBRD + $totalkrgBRD) / ($totalJJG - $totalabr)) * 100, 2);
                    $totSkor_total = sidak_brdTotal($totPersenTOtaljjg);
                    $totoverripe += $overripe;
                    $totempty += $empty;
                    $totJJG_matang = $totalJJG - ($totaltnpBRD + $totalkrgBRD + $totoverripe + $totempty + $totalabr);
                    $totPer_jjgMtng = round($totJJG_matang / ($totalJJG - $totalabr) * 100, 2);

                    $totSkor_jjgMtng = sidak_matangSKOR($totPer_jjgMtng);
                    $totPer_over = round(($totoverripe / ($totalJJG - $totalabr)) * 100, 2);
                    $totSkor_Over = sidak_lwtMatang($totPer_over);
                    $totPer_Empty = round(($totempty / ($totalJJG - $totalabr)) * 100, 2);
                    $totSkor_Empty = sidak_jjgKosong($totPer_Empty);
                    $totVcut += $vcut;
                    $totPer_vcut =   round(($totVcut / $totalJJG) * 100, 2);
                    $totSkor_Vcut =  sidak_tangkaiP($totPer_vcut);
                    $totPer_abr =  round(($totalabr / $totalJJG) * 100, 2);
                    $totRD += $rd;
                    $totPer_rd = round(($totRD / $totalJJG) * 100, 2);
                    $totBlok += $dataBLok;
                    $totKR += $sum_kr;
                    if ($totKR != 0) {
                        $tot_krS = round($totKR / $totBlok, 2);
                    } else {
                        $tot_krS = 0;
                    }
                    $totPer_kr = round($tot_krS * 100, 2);
                    $totSkor_kr = sidak_PengBRD($totPer_kr);
                    $totALlskor = sidak_brdTotal($totPersenTOtaljjg) + sidak_matangSKOR($totPer_jjgMtng) + sidak_lwtMatang($totPer_over) + sidak_jjgKosong($totPer_Empty) + sidak_tangkaiP($totPer_vcut) + sidak_PengBRD($totPer_kr);

                    $totKategor = sidak_akhir($totALlskor);
                } else {

                    $sidak_buah[$key][$key1]['Jumlah_janjang'] = 0;
                    $sidak_buah[$key][$key1]['blok'] = 0;
                    $sidak_buah[$key][$key1]['est'] = $key;
                    $sidak_buah[$key][$key1]['afd'] = $key1;
                    $sidak_buah[$key][$key1]['nama_staff'] = '-';
                    $sidak_buah[$key][$key1]['tnp_brd'] = 0;
                    $sidak_buah[$key][$key1]['krg_brd'] = 0;
                    $sidak_buah[$key][$key1]['persenTNP_brd'] = 0;
                    $sidak_buah[$key][$key1]['persenKRG_brd'] = 0;
                    $sidak_buah[$key][$key1]['total_jjg'] = 0;
                    $sidak_buah[$key][$key1]['persen_totalJjg'] = 0;
                    $sidak_buah[$key][$key1]['skor_total'] = 0;
                    $sidak_buah[$key][$key1]['jjg_matang'] = 0;
                    $sidak_buah[$key][$key1]['persen_jjgMtang'] = 0;
                    $sidak_buah[$key][$key1]['skor_jjgMatang'] = 0;
                    $sidak_buah[$key][$key1]['lewat_matang'] = 0;
                    $sidak_buah[$key][$key1]['persen_lwtMtng'] =  0;
                    $sidak_buah[$key][$key1]['skor_lewatMTng'] = 0;
                    $sidak_buah[$key][$key1]['janjang_kosong'] = 0;
                    $sidak_buah[$key][$key1]['persen_kosong'] = 0;
                    $sidak_buah[$key][$key1]['skor_kosong'] = 0;
                    $sidak_buah[$key][$key1]['vcut'] = 0;
                    $sidak_buah[$key][$key1]['karung'] = 0;
                    $sidak_buah[$key][$key1]['vcut_persen'] = 0;
                    $sidak_buah[$key][$key1]['vcut_skor'] = 0;
                    $sidak_buah[$key][$key1]['abnormal'] = 0;
                    $sidak_buah[$key][$key1]['abnormal_persen'] = 0;
                    $sidak_buah[$key][$key1]['rat_dmg'] = 0;
                    $sidak_buah[$key][$key1]['rd_persen'] = 0;
                    $sidak_buah[$key][$key1]['TPH'] = 0;
                    $sidak_buah[$key][$key1]['persen_krg'] = 0;
                    $sidak_buah[$key][$key1]['skor_kr'] = 0;
                    $sidak_buah[$key][$key1]['All_skor'] = 0;
                    $sidak_buah[$key][$key1]['kategori'] = 0;
                    foreach ($queryAsisten as $ast => $asisten) {
                        if ($key === $asisten['est'] && $key1 === $asisten['afd']) {
                            $sidak_buah[$key][$key1]['nama_asisten'] = $asisten['nama'];
                        }
                    }
                }
            }
        }

        // dd($sidak_buah);

        $mutu_buah = array();
        foreach ($queryEste as $key => $value) {
            foreach ($sidak_buah as $key2 => $value2) {
                if ($value['est'] == $key2) {
                    $mutu_buah[$value['wil']][$key2] = array_merge($mutu_buah[$value['wil']][$key2] ?? [], $value2);
                }
            }
        }

        $mutu_buahv2 = array();
        foreach ($queryEste as $key => $value) {
            foreach ($sidak_buah as $key2 => $value2) {
                if ($value['est'] == $key2) {
                    $mutu_buahv2[$value['wil']][$key2] = array_merge($mutu_buah[$value['wil']][$key2] ?? [], $value2);
                }
            }
        }
        // dd($mutu_buah);
        // Remove the "Plasma1" element from the original array
        if (isset($mutu_buah[1]['Plasma1'])) {
            $plasma1 = $mutu_buah[1]['Plasma1'];
            unset($mutu_buah[1]['Plasma1']);
        } else {
            $plasma1 = null;
        }

        // Add the "Plasma1" element to its own index
        if ($plasma1 !== null) {
            $mutu_buah[4] = ['Plasma1' => $plasma1];
        }
        // Optional: Re-index the array to ensure the keys are in sequential order


        if (isset($mutu_buah[4]['Plasma2'])) {
            $Plasma2 = $mutu_buah[4]['Plasma2'];
            unset($mutu_buah[4]['Plasma2']);
        } else {
            $Plasma2 = null;
        }

        // Add the "Plasma2" element to its own index
        if ($Plasma2 !== null) {
            $mutu_buah[7] = ['Plasma2' => $Plasma2];
        }

        if (isset($mutu_buah[7]['Plasma3'])) {
            $Plasma3 = $mutu_buah[7]['Plasma3'];
            unset($mutu_buah[7]['Plasma3']);
        } else {
            $Plasma3 = null;
        }

        // Add the "Plasma3" element to its own index
        if ($Plasma3 !== null) {
            $mutu_buah[9] = ['Plasma3' => $Plasma3];
        }
        // Optional: Re-index the array to ensure the keys are in sequential order
        $mutu_buah = array_values($mutu_buah);
        // dd($mutu_buah);

        $mutubuah_est = array();
        foreach ($mutu_buah as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $jjg_sample = 0;
                $tnpBRD = 0;
                $krgBRD = 0;
                $abr = 0;
                $skor_total = 0;
                $overripe = 0;
                $empty = 0;
                $vcut = 0;
                $rd = 0;
                $sum_kr = 0;
                $allSkor = 0;
                $dataBLok = 0;
                foreach ($value1 as $key2 => $value2) {
                    $jjg_sample += $value2['Jumlah_janjang'];
                    $tnpBRD += $value2['tnp_brd'];
                    $krgBRD += $value2['krg_brd'];
                    $abr += $value2['abnormal'];
                    $overripe += $value2['lewat_matang'];
                    $empty += $value2['janjang_kosong'];
                    $vcut += $value2['vcut'];

                    $rd += $value2['rat_dmg'];

                    $dataBLok += $value2['blok'];
                    $sum_kr += $value2['karung'];
                }

                if ($sum_kr != 0) {
                    $total_kr = round($sum_kr / $dataBLok, 2);
                } else {
                    $total_kr = 0;
                }
                $per_kr = round($total_kr * 100, 2);
                $skor_total = round(($jjg_sample - $abr != 0 ? (($tnpBRD + $krgBRD) / ($jjg_sample - $abr)) * 100 : 0), 2);

                $skor_jjgMSk = round(($jjg_sample - $abr != 0 ? (($jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr)) / ($jjg_sample - $abr)) * 100 : 0), 2);

                $skor_lewatMTng = round(($jjg_sample - $abr != 0 ? ($overripe / ($jjg_sample - $abr)) * 100 : 0), 2);

                $skor_jjgKosong = round(($jjg_sample - $abr != 0 ? ($empty / ($jjg_sample - $abr)) * 100 : 0), 2);

                $skor_vcut = round(($jjg_sample != 0 ? ($vcut / $jjg_sample) * 100 : 0), 2);

                $allSkor = sidak_brdTotal($skor_total) +  sidak_matangSKOR($skor_jjgMSk) +  sidak_lwtMatang($skor_lewatMTng) + sidak_jjgKosong($skor_jjgKosong) + sidak_tangkaiP($skor_vcut) + sidak_PengBRD($per_kr);

                $em = 'EM';

                $nama_em = '';

                // dd($key1);
                foreach ($queryAsisten as $ast => $asisten) {
                    if ($key1 === $asisten['est'] && $em === $asisten['afd']) {
                        $nama_em = $asisten['nama'];
                    }
                }
                $jjg_mth = $tnpBRD + $krgBRD + $overripe + $empty;




                $skor_jjgMTh = ($jjg_sample - $abr != 0) ? round($jjg_mth / ($jjg_sample - $abr) * 100, 2) : 0;

                $mutubuah_est[$key][$key1]['jjg_mantah'] = $jjg_mth;
                $mutubuah_est[$key][$key1]['persen_jjgmentah'] = $skor_jjgMTh;

                if ($jjg_sample == 0 && $tnpBRD == 0 &&   $krgBRD == 0 && $abr == 0 && $overripe == 0 && $empty == 0 &&  $vcut == 0 &&  $rd == 0 && $sum_kr == 0) {
                    $mutubuah_est[$key][$key1]['check_arr'] = 'kosong';
                    $mutubuah_est[$key][$key1]['All_skor'] = 0;
                } else {
                    $mutubuah_est[$key][$key1]['check_arr'] = 'ada';
                    $mutubuah_est[$key][$key1]['All_skor'] = $allSkor;
                }
                $mutubuah_est[$key][$key1]['Jumlah_janjang'] = $jjg_sample;
                $mutubuah_est[$key][$key1]['blok'] = $dataBLok;
                $mutubuah_est[$key][$key1]['EM'] = 'EM';
                $mutubuah_est[$key][$key1]['Nama_assist'] = $nama_em;
                $mutubuah_est[$key][$key1]['nama_staff'] = '-';
                $mutubuah_est[$key][$key1]['tnp_brd'] = $tnpBRD;
                $mutubuah_est[$key][$key1]['krg_brd'] = $krgBRD;
                $mutubuah_est[$key][$key1]['persenTNP_brd'] = round(($jjg_sample - $abr != 0 ? ($tnpBRD / ($jjg_sample - $abr)) * 100 : 0), 2);
                $mutubuah_est[$key][$key1]['persenKRG_brd'] = round(($jjg_sample - $abr != 0 ? ($krgBRD / ($jjg_sample - $abr)) * 100 : 0), 2);
                $mutubuah_est[$key][$key1]['abnormal_persen'] = round(($jjg_sample != 0 ? ($abr / $jjg_sample) * 100 : 0), 2);
                $mutubuah_est[$key][$key1]['rd_persen'] = round(($jjg_sample != 0 ? ($rd / $jjg_sample) * 100 : 0), 2);


                $mutubuah_est[$key][$key1]['total_jjg'] = $tnpBRD + $krgBRD;
                $mutubuah_est[$key][$key1]['persen_totalJjg'] = $skor_total;
                $mutubuah_est[$key][$key1]['skor_total'] = sidak_brdTotal($skor_total);
                $mutubuah_est[$key][$key1]['jjg_matang'] = $jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr);
                $mutubuah_est[$key][$key1]['persen_jjgMtang'] = $skor_jjgMSk;
                $mutubuah_est[$key][$key1]['skor_jjgMatang'] = sidak_matangSKOR($skor_jjgMSk);
                $mutubuah_est[$key][$key1]['lewat_matang'] = $overripe;
                $mutubuah_est[$key][$key1]['persen_lwtMtng'] =  $skor_lewatMTng;
                $mutubuah_est[$key][$key1]['skor_lewatMTng'] = sidak_lwtMatang($skor_lewatMTng);
                $mutubuah_est[$key][$key1]['janjang_kosong'] = $empty;
                $mutubuah_est[$key][$key1]['persen_kosong'] = $skor_jjgKosong;
                $mutubuah_est[$key][$key1]['skor_kosong'] = sidak_jjgKosong($skor_jjgKosong);
                $mutubuah_est[$key][$key1]['vcut'] = $vcut;
                $mutubuah_est[$key][$key1]['vcut_persen'] = $skor_vcut;
                $mutubuah_est[$key][$key1]['vcut_skor'] = sidak_tangkaiP($skor_vcut);
                $mutubuah_est[$key][$key1]['abnormal'] = $abr;

                $mutubuah_est[$key][$key1]['rat_dmg'] = $rd;

                $mutubuah_est[$key][$key1]['karung'] = $sum_kr;
                $mutubuah_est[$key][$key1]['TPH'] = $total_kr;
                $mutubuah_est[$key][$key1]['persen_krg'] = $per_kr;
                $mutubuah_est[$key][$key1]['skor_kr'] = sidak_PengBRD($per_kr);

                $mutubuah_est[$key][$key1]['kategori'] = sidak_akhir($allSkor);
            }
        }

        // dd(($mutubuah_est));
        $mutu_buahEst = array();
        foreach ($mutu_buahv2 as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $jjg_sample = 0;
                $tnpBRD = 0;
                $krgBRD = 0;
                $abr = 0;
                $skor_total = 0;
                $overripe = 0;
                $empty = 0;
                $vcut = 0;
                $rd = 0;
                $sum_kr = 0;
                $allSkor = 0;
                $dataBLok = 0;
                foreach ($value1 as $key2 => $value2) {
                    $jjg_sample += $value2['Jumlah_janjang'];
                    $tnpBRD += $value2['tnp_brd'];
                    $krgBRD += $value2['krg_brd'];
                    $abr += $value2['abnormal'];
                    $overripe += $value2['lewat_matang'];
                    $empty += $value2['janjang_kosong'];
                    $vcut += $value2['vcut'];

                    $rd += $value2['rat_dmg'];

                    $dataBLok += $value2['blok'];
                    $sum_kr += $value2['karung'];
                }

                if ($sum_kr != 0) {
                    $total_kr = round($sum_kr / $dataBLok, 2);
                } else {
                    $total_kr = 0;
                }
                $per_kr = round($total_kr * 100, 2);
                $skor_total = round(($jjg_sample - $abr != 0 ? (($tnpBRD + $krgBRD) / ($jjg_sample - $abr)) * 100 : 0), 2);

                $skor_jjgMSk = round(($jjg_sample - $abr != 0 ? (($jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr)) / ($jjg_sample - $abr)) * 100 : 0), 2);

                $skor_lewatMTng = round(($jjg_sample - $abr != 0 ? ($overripe / ($jjg_sample - $abr)) * 100 : 0), 2);

                $skor_jjgKosong = round(($jjg_sample - $abr != 0 ? ($empty / ($jjg_sample - $abr)) * 100 : 0), 2);

                $skor_vcut = round(($jjg_sample != 0 ? ($vcut / $jjg_sample) * 100 : 0), 2);

                $allSkor = sidak_brdTotal($skor_total) +  sidak_matangSKOR($skor_jjgMSk) +  sidak_lwtMatang($skor_lewatMTng) + sidak_jjgKosong($skor_jjgKosong) + sidak_tangkaiP($skor_vcut) + sidak_PengBRD($per_kr);

                $em = 'EM';

                $nama_em = '';

                // dd($key1);
                foreach ($queryAsisten as $ast => $asisten) {
                    if ($key1 === $asisten['est'] && $em === $asisten['afd']) {
                        $nama_em = $asisten['nama'];
                    }
                }
                $jjg_mth = $tnpBRD + $krgBRD + $overripe + $empty;

                $skor_jjgMTh = ($jjg_sample - $abr != 0) ? round($jjg_mth / ($jjg_sample - $abr) * 100, 2) : 0;

                $mutu_buahEst[$key][$key1]['jjg_mantah'] = $jjg_mth;
                $mutu_buahEst[$key][$key1]['persen_jjgmentah'] = $skor_jjgMTh;


                $mutu_buahEst[$key][$key1]['Jumlah_janjang'] = $jjg_sample;
                $mutu_buahEst[$key][$key1]['blok'] = $dataBLok;
                $mutu_buahEst[$key][$key1]['EM'] = 'EM';
                $mutu_buahEst[$key][$key1]['Nama_assist'] = $nama_em;
                $mutu_buahEst[$key][$key1]['nama_staff'] = '-';
                $mutu_buahEst[$key][$key1]['tnp_brd'] = $tnpBRD;
                $mutu_buahEst[$key][$key1]['krg_brd'] = $krgBRD;
                $mutu_buahEst[$key][$key1]['persenTNP_brd'] = round(($jjg_sample - $abr != 0 ? ($tnpBRD / ($jjg_sample - $abr)) * 100 : 0), 2);
                $mutu_buahEst[$key][$key1]['persenKRG_brd'] = round(($jjg_sample - $abr != 0 ? ($krgBRD / ($jjg_sample - $abr)) * 100 : 0), 2);
                $mutu_buahEst[$key][$key1]['abnormal_persen'] = round(($jjg_sample != 0 ? ($abr / $jjg_sample) * 100 : 0), 2);
                $mutu_buahEst[$key][$key1]['rd_persen'] = round(($jjg_sample != 0 ? ($rd / $jjg_sample) * 100 : 0), 2);


                $mutu_buahEst[$key][$key1]['total_jjg'] = $tnpBRD + $krgBRD;
                $mutu_buahEst[$key][$key1]['persen_totalJjg'] = $skor_total;
                $mutu_buahEst[$key][$key1]['skor_total'] = sidak_brdTotal($skor_total);
                $mutu_buahEst[$key][$key1]['jjg_matang'] = $jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr);
                $mutu_buahEst[$key][$key1]['persen_jjgMtang'] = $skor_jjgMSk;
                $mutu_buahEst[$key][$key1]['skor_jjgMatang'] = sidak_matangSKOR($skor_jjgMSk);
                $mutu_buahEst[$key][$key1]['lewat_matang'] = $overripe;
                $mutu_buahEst[$key][$key1]['persen_lwtMtng'] =  $skor_lewatMTng;
                $mutu_buahEst[$key][$key1]['skor_lewatMTng'] = sidak_lwtMatang($skor_lewatMTng);
                $mutu_buahEst[$key][$key1]['janjang_kosong'] = $empty;
                $mutu_buahEst[$key][$key1]['persen_kosong'] = $skor_jjgKosong;
                $mutu_buahEst[$key][$key1]['skor_kosong'] = sidak_jjgKosong($skor_jjgKosong);
                $mutu_buahEst[$key][$key1]['vcut'] = $vcut;
                $mutu_buahEst[$key][$key1]['vcut_persen'] = $skor_vcut;
                $mutu_buahEst[$key][$key1]['vcut_skor'] = sidak_tangkaiP($skor_vcut);
                $mutu_buahEst[$key][$key1]['abnormal'] = $abr;

                $mutu_buahEst[$key][$key1]['rat_dmg'] = $rd;

                $mutu_buahEst[$key][$key1]['karung'] = $sum_kr;
                $mutu_buahEst[$key][$key1]['TPH'] = $total_kr;
                $mutu_buahEst[$key][$key1]['persen_krg'] = $per_kr;
                $mutu_buahEst[$key][$key1]['skor_kr'] = sidak_PengBRD($per_kr);
                $mutu_buahEst[$key][$key1]['All_skor'] = $allSkor;
                $mutu_buahEst[$key][$key1]['kategori'] = sidak_akhir($allSkor);
            }
        }


        // dd($mutu_buahEst);

        $mutu_bhWil = array();
        foreach ($mutu_buahEst as $key => $value) {
            $jjg_sample = 0;
            $tnpBRD = 0;
            $krgBRD = 0;
            $abr = 0;
            $skor_total = 0;
            $overripe = 0;
            $empty = 0;
            $vcut = 0;
            $rd = 0;
            $sum_kr = 0;
            $allSkor = 0;
            $dataBLok = 0;
            foreach ($value as $key1 => $value1) {
                // dd($value2);
                $jjg_sample += $value1['Jumlah_janjang'];
                $tnpBRD += $value1['tnp_brd'];
                $krgBRD += $value1['krg_brd'];
                $abr += $value1['abnormal'];
                $overripe += $value1['lewat_matang'];
                $empty += $value1['janjang_kosong'];
                $vcut += $value1['vcut'];

                $rd += $value1['rat_dmg'];

                $dataBLok += $value1['blok'];
                $sum_kr += $value1['karung'];
            }

            if ($sum_kr != 0) {
                $total_kr = round($sum_kr / $dataBLok, 2);
            } else {
                $total_kr = 0;
            }
            $per_kr = round($total_kr * 100, 2);
            $skor_total = round(($jjg_sample - $abr != 0 ? (($tnpBRD + $krgBRD) / ($jjg_sample - $abr)) * 100 : 0), 2);

            $skor_jjgMSk = round(($jjg_sample - $abr != 0 ? (($jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr)) / ($jjg_sample - $abr)) * 100 : 0), 2);

            $skor_lewatMTng = round(($jjg_sample - $abr != 0 ? ($overripe / ($jjg_sample - $abr)) * 100 : 0), 2);

            $skor_jjgKosong = round(($jjg_sample - $abr != 0 ? ($empty / ($jjg_sample - $abr)) * 100 : 0), 2);

            $skor_vcut = round(($jjg_sample != 0 ? ($vcut / $jjg_sample) * 100 : 0), 2);

            $allSkor = sidak_brdTotal($skor_total) +  sidak_matangSKOR($skor_jjgMSk) +  sidak_lwtMatang($skor_lewatMTng) + sidak_jjgKosong($skor_jjgKosong) + sidak_tangkaiP($skor_vcut) + sidak_PengBRD($per_kr);


            $mutu_bhWil[$key]['Jumlah_janjang'] = $jjg_sample;
            $mutu_bhWil[$key]['blok'] = $dataBLok;
            switch ($key) {
                case 0:
                    $mutu_bhWil[$key]['est'] = 'WIl-I';
                    $wil = 'WIL-I';
                    break;
                case 1:
                    $mutu_bhWil[$key]['est'] = 'WIl-II';
                    $wil = 'WIL-II';
                    break;
                case 2:
                    $mutu_bhWil[$key]['est'] = 'WIl-III';
                    $wil = 'WIL-III';
                    break;
                case 3:
                    $mutu_bhWil[$key]['est'] = 'Plasma1';
                    $wil = 'Plasma1';
                    break;
                default:
                    $mutu_bhWil[$key]['est'] = 'WIl' . $key;
                    $wil = '-';
                    break;
            }

            $wiles = $wil;

            $em = 'GM';

            $nama_em = '';

            // dd($key);
            foreach ($queryAsisten as $ast => $asisten) {
                if ($wiles === $asisten['est'] && $em === $asisten['afd']) {
                    $nama_em = $asisten['nama'];
                }
            }
            $mutu_bhWil[$key]['TEST'] = $wil;
            $mutu_bhWil[$key]['afd'] = $key1;
            $mutu_bhWil[$key]['nama_staff'] = $nama_em;
            $mutu_bhWil[$key]['tnp_brd'] = $tnpBRD;
            $mutu_bhWil[$key]['krg_brd'] = $krgBRD;
            $mutu_bhWil[$key]['persenTNP_brd'] = round(($jjg_sample - $abr != 0 ? ($tnpBRD / ($jjg_sample - $abr)) * 100 : 0), 2);
            $mutu_bhWil[$key]['persenKRG_brd'] = round(($jjg_sample - $abr != 0 ? ($krgBRD / ($jjg_sample - $abr)) * 100 : 0), 2);
            $mutu_bhWil[$key]['abnormal_persen'] = round(($jjg_sample != 0 ? ($abr / $jjg_sample) * 100 : 0), 2);
            $mutu_bhWil[$key]['rd_persen'] = round(($jjg_sample != 0 ? ($rd / $jjg_sample) * 100 : 0), 2);


            $mutu_bhWil[$key]['total_jjg'] = $tnpBRD + $krgBRD;
            $mutu_bhWil[$key]['persen_totalJjg'] = $skor_total;
            $mutu_bhWil[$key]['skor_total'] = sidak_brdTotal($skor_total);
            $mutu_bhWil[$key]['jjg_matang'] = $jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr);
            $mutu_bhWil[$key]['persen_jjgMtang'] = $skor_jjgMSk;
            $mutu_bhWil[$key]['skor_jjgMatang'] = sidak_matangSKOR($skor_jjgMSk);
            $mutu_bhWil[$key]['lewat_matang'] = $overripe;
            $mutu_bhWil[$key]['persen_lwtMtng'] =  $skor_lewatMTng;
            $mutu_bhWil[$key]['skor_lewatMTng'] = sidak_lwtMatang($skor_lewatMTng);
            $mutu_bhWil[$key]['janjang_kosong'] = $empty;
            $mutu_bhWil[$key]['persen_kosong'] = $skor_jjgKosong;
            $mutu_bhWil[$key]['skor_kosong'] = sidak_jjgKosong($skor_jjgKosong);
            $mutu_bhWil[$key]['vcut'] = $vcut;
            $mutu_bhWil[$key]['vcut_persen'] = $skor_vcut;
            $mutu_bhWil[$key]['vcut_skor'] = sidak_tangkaiP($skor_vcut);
            $mutu_bhWil[$key]['abnormal'] = $abr;

            $mutu_bhWil[$key]['rat_dmg'] = $rd;

            $mutu_bhWil[$key]['karung'] = $sum_kr;
            $mutu_bhWil[$key]['TPH'] = $total_kr;
            $mutu_bhWil[$key]['persen_krg'] = $per_kr;
            $mutu_bhWil[$key]['skor_kr'] = sidak_PengBRD($per_kr);
            $mutu_bhWil[$key]['All_skor'] = $allSkor;
            $mutu_bhWil[$key]['kategori'] = sidak_akhir($allSkor);
        }
        // dd($mutu_bhWil);

        foreach ($mutu_buah as $key1 => $estates)  if (is_array($estates)) {
            $sortedData = array();
            $sortedDataEst = array();

            foreach ($estates as $estateName => $data) {
                if (is_array($data)) {
                    // dd($data);
                    $sortedDataEst[] = array(
                        'key1' => $key1,
                        'estateName' => $estateName,
                        'data' => $data
                    );
                    foreach ($data as $key2 => $scores) {
                        if (is_array($scores)) {
                            // dd($scores);
                            $sortedData[] = array(
                                'estateName' => $estateName,
                                'key2' => $key2,
                                'scores' => $scores
                            );
                        }
                    }
                }
            }

            //mengurutkan untuk nilai afd
            usort($sortedData, function ($a, $b) {
                return $b['scores']['All_skor'] - $a['scores']['All_skor'];
            });
            // //mengurutkan untuk nilai estate
            // usort($sortedDataEst, function ($a, $b) {
            //     return $b['data']['TotalSkorEST'] - $a['data']['TotalSkorEST'];
            // });

            //menambahkan nilai rank ke dalam afd
            $rank = 1;
            foreach ($sortedData as $sortedEstate) {
                $mutu_buah[$key1][$sortedEstate['estateName']][$sortedEstate['key2']]['rankAFD'] = $rank;
                $rank++;
            }




            // unset($sortedData, $sortedDataEst);
            unset($sortedData);
        }

        // dd($mutu_buah);
        foreach ($mutubuah_est as $key1 => $estates)  if (is_array($estates)) {

            $sortedDataEst = array();

            foreach ($estates as $estateName => $data) {
                if (is_array($data)) {
                    // dd($data);
                    $sortedDataEst[] = array(
                        'key1' => $key1,
                        'estateName' => $estateName,
                        'data' => $data
                    );
                }
            }

            // //mengurutkan untuk nilai estate
            usort($sortedDataEst, function ($a, $b) {
                return $b['data']['All_skor'] - $a['data']['All_skor'];
            });

            // //menambahkan nilai rank ke dalam estate
            $rank = 1;
            foreach ($sortedDataEst as $sortedest) {
                $mutubuah_est[$key1][$sortedest['estateName']]['rankEST'] = $rank;
                $rank++;
            }
            // unset($sortedData, $sortedDataEst);
            unset($sortedData);
        }
        $mutuBuah_wil = array();
        foreach ($mutubuah_est as $key => $value) {
            $jjg_sample = 0;
            $tnpBRD = 0;
            $krgBRD = 0;
            $abr = 0;
            $skor_total = 0;
            $overripe = 0;
            $empty = 0;
            $vcut = 0;
            $rd = 0;
            $sum_kr = 0;
            $allSkor = 0;
            $dataBLok = 0;
            foreach ($value as $key1 => $value1) {
                // dd($value2);
                $jjg_sample += $value1['Jumlah_janjang'];
                $tnpBRD += $value1['tnp_brd'];
                $krgBRD += $value1['krg_brd'];
                $abr += $value1['abnormal'];
                $overripe += $value1['lewat_matang'];
                $empty += $value1['janjang_kosong'];
                $vcut += $value1['vcut'];

                $rd += $value1['rat_dmg'];

                $dataBLok += $value1['blok'];
                $sum_kr += $value1['karung'];
            }

            if ($sum_kr != 0) {
                $total_kr = round($sum_kr / $dataBLok, 2);
            } else {
                $total_kr = 0;
            }
            $per_kr = round($total_kr * 100, 2);
            $skor_total = round(($jjg_sample - $abr != 0 ? (($tnpBRD + $krgBRD) / ($jjg_sample - $abr)) * 100 : 0), 2);

            $skor_jjgMSk = round(($jjg_sample - $abr != 0 ? (($jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr)) / ($jjg_sample - $abr)) * 100 : 0), 2);

            $skor_lewatMTng = round(($jjg_sample - $abr != 0 ? ($overripe / ($jjg_sample - $abr)) * 100 : 0), 2);

            $skor_jjgKosong = round(($jjg_sample - $abr != 0 ? ($empty / ($jjg_sample - $abr)) * 100 : 0), 2);

            $skor_vcut = round(($jjg_sample != 0 ? ($vcut / $jjg_sample) * 100 : 0), 2);

            $allSkor = sidak_brdTotal($skor_total) +  sidak_matangSKOR($skor_jjgMSk) +  sidak_lwtMatang($skor_lewatMTng) + sidak_jjgKosong($skor_jjgKosong) + sidak_tangkaiP($skor_vcut) + sidak_PengBRD($per_kr);


            $mutuBuah_wil[$key]['Jumlah_janjang'] = $jjg_sample;
            $mutuBuah_wil[$key]['blok'] = $dataBLok;
            switch ($key) {
                case 0:
                    $mutuBuah_wil[$key]['est'] = 'WIl-I';
                    $wil = 'WIL-I';
                    break;
                case 1:
                    $mutuBuah_wil[$key]['est'] = 'WIl-II';
                    $wil = 'WIL-II';
                    break;
                case 2:
                    $mutuBuah_wil[$key]['est'] = 'WIl-III';
                    $wil = 'WIL-III';
                    break;
                case 3:
                    $mutuBuah_wil[$key]['est'] = 'Plasma1';
                    $wil = 'Plasma1';
                    break;
                default:
                    $mutuBuah_wil[$key]['est'] = 'WIl' . $key;
                    $wil = '-';
                    break;
            }

            $wiles = $wil;

            $em = 'GM';

            $nama_em = '';

            // dd($key);
            foreach ($queryAsisten as $ast => $asisten) {
                if ($wiles === $asisten['est'] && $em === $asisten['afd']) {
                    $nama_em = $asisten['nama'];
                }
            }
            if ($jjg_sample == 0 && $tnpBRD == 0 &&   $krgBRD == 0 && $abr == 0 && $overripe == 0 && $empty == 0 &&  $vcut == 0 &&  $rd == 0 && $sum_kr == 0) {
                $mutuBuah_wil[$key]['check_arr'] = 'kosong';
                $mutuBuah_wil[$key]['All_skor'] = 0;
            } else {
                $mutuBuah_wil[$key]['check_arr'] = 'ada';
                $mutuBuah_wil[$key]['All_skor'] = $allSkor;
            }
            $mutuBuah_wil[$key]['TEST'] = $wil;
            $mutuBuah_wil[$key]['afd'] = $key1;
            $mutuBuah_wil[$key]['nama_staff'] = $nama_em;
            $mutuBuah_wil[$key]['tnp_brd'] = $tnpBRD;
            $mutuBuah_wil[$key]['krg_brd'] = $krgBRD;
            $mutuBuah_wil[$key]['persenTNP_brd'] = round(($jjg_sample - $abr != 0 ? ($tnpBRD / ($jjg_sample - $abr)) * 100 : 0), 2);
            $mutuBuah_wil[$key]['persenKRG_brd'] = round(($jjg_sample - $abr != 0 ? ($krgBRD / ($jjg_sample - $abr)) * 100 : 0), 2);
            $mutuBuah_wil[$key]['abnormal_persen'] = round(($jjg_sample != 0 ? ($abr / $jjg_sample) * 100 : 0), 2);
            $mutuBuah_wil[$key]['rd_persen'] = round(($jjg_sample != 0 ? ($rd / $jjg_sample) * 100 : 0), 2);


            $mutuBuah_wil[$key]['total_jjg'] = $tnpBRD + $krgBRD;
            $mutuBuah_wil[$key]['persen_totalJjg'] = $skor_total;
            $mutuBuah_wil[$key]['skor_total'] = sidak_brdTotal($skor_total);
            $mutuBuah_wil[$key]['jjg_matang'] = $jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr);
            $mutuBuah_wil[$key]['persen_jjgMtang'] = $skor_jjgMSk;
            $mutuBuah_wil[$key]['skor_jjgMatang'] = sidak_matangSKOR($skor_jjgMSk);
            $mutuBuah_wil[$key]['lewat_matang'] = $overripe;
            $mutuBuah_wil[$key]['persen_lwtMtng'] =  $skor_lewatMTng;
            $mutuBuah_wil[$key]['skor_lewatMTng'] = sidak_lwtMatang($skor_lewatMTng);
            $mutuBuah_wil[$key]['janjang_kosong'] = $empty;
            $mutuBuah_wil[$key]['persen_kosong'] = $skor_jjgKosong;
            $mutuBuah_wil[$key]['skor_kosong'] = sidak_jjgKosong($skor_jjgKosong);
            $mutuBuah_wil[$key]['vcut'] = $vcut;
            $mutuBuah_wil[$key]['vcut_persen'] = $skor_vcut;
            $mutuBuah_wil[$key]['vcut_skor'] = sidak_tangkaiP($skor_vcut);
            $mutuBuah_wil[$key]['abnormal'] = $abr;

            $mutuBuah_wil[$key]['rat_dmg'] = $rd;

            $mutuBuah_wil[$key]['karung'] = $sum_kr;
            $mutuBuah_wil[$key]['TPH'] = $total_kr;
            $mutuBuah_wil[$key]['persen_krg'] = $per_kr;
            $mutuBuah_wil[$key]['skor_kr'] = sidak_PengBRD($per_kr);
            // $mutuBuah_wil[$key]['All_skor'] = $allSkor;
            $mutuBuah_wil[$key]['kategori'] = sidak_akhir($allSkor);
        }

        // dd($mutuBuah_wil);
        $sortedDataEst = array();
        foreach ($mutuBuah_wil as $key1 => $estates) {
            if (is_array($estates)) {
                $sortedDataEst[] = array(
                    'key1' => $key1,
                    'data' => $estates
                );
            }
        }

        usort($sortedDataEst, function ($a, $b) {
            return $b['data']['All_skor'] - $a['data']['All_skor'];
        });

        $rank = 1;
        foreach ($sortedDataEst as $sortedest) {
            $estateKey = $sortedest['key1'];
            $mutuBuah_wil[$estateKey]['rankWil'] = $rank;
            $rank++;
        }

        unset($sortedDataEst);

        $defaultMTbh = array();


        $regional_array = [
            'Regional' => $mutuBuah_wil
        ];
        // dd($regional_array);
        $regArr = array();
        foreach ($regional_array as $key => $value) {
            $jjg_sampleEST = 0;
            $tnpBRDEST = 0;
            $krgBRDEST = 0;
            $abrEST = 0;
            $overripeEST = 0;
            $emptyEST = 0;
            $vcutEST = 0;
            $rdEST = 0;
            $sum_krEST = 0;
            $blokEST = 0;
            foreach ($value as $key1 => $value1) {
                // dd($value1);
                $jjg_sampleEST += $value1['Jumlah_janjang'];
                $blokEST += $value1['blok'];
                $tnpBRDEST +=    $value1['tnp_brd'];
                $krgBRDEST +=    $value1['krg_brd'];
                $abrEST +=    $value1['abnormal'];
                $overripeEST +=    $value1['lewat_matang'];
                $emptyEST +=    $value1['janjang_kosong'];
                $vcutEST +=    $value1['vcut'];
                $rdEST +=    $value1['rat_dmg'];
                $sum_krEST +=    $value1['karung'];
                $afds = $value1['afd'];
            }
            if ($sum_krEST != 0) {
                $total_krEST = round($sum_krEST / $blokEST, 2);
            } else {
                $total_krEST = 0;
            }
            $per_krEST = round($total_krEST * 100, 2);
            $skor_totalEST = ($jjg_sampleEST - $abrEST) !== 0 ? round((($tnpBRDEST + $krgBRDEST) / ($jjg_sampleEST - $abrEST)) * 100, 2) : 0;
            $skot_jjgmskEST = ($jjg_sampleEST - $abrEST) !== 0 ? round(($jjg_sampleEST - ($tnpBRDEST + $krgBRDEST + $overripeEST + $emptyEST)) / ($jjg_sampleEST - $abrEST) * 100, 2) : 0;
            $skor_lewatmatangEST = ($jjg_sampleEST - $abrEST) !== 0 ? round(($overripeEST / ($jjg_sampleEST - $abrEST)) * 100, 2) : 0;
            $skor_jjgKosongEST = ($jjg_sampleEST - $abrEST) !== 0 ? round(($emptyEST / ($jjg_sampleEST - $abrEST)) * 100, 2) : 0;
            $skor_vcutEST = $jjg_sampleEST !== 0 ? round(($vcutEST / $jjg_sampleEST) * 100, 2) : 0;

            $allSkorEST = sidak_brdTotal($skor_totalEST) +  sidak_matangSKOR($skot_jjgmskEST) +  sidak_lwtMatang($skor_lewatmatangEST) + sidak_jjgKosong($skor_jjgKosongEST) + sidak_tangkaiP($skor_vcutEST) + sidak_PengBRD($per_krEST);


            $em = 'RH';
            $estkey = '';
            $estkey2 = '';
            $regArr[$key]['Jumlah_janjang'] = $jjg_sampleEST;
            $regArr[$key]['blok'] = $blokEST;
            $regArr[$key]['kode'] = $afds;

            if ($afds == 'Plasma1') {
                $estkey = 'REG-I';
                $estkey2 = 'RH-I';
            } else if ($afds == 'SCE') {
                $estkey = 'REG-II';
                $estkey2 = 'RH-II';
            } else {
                $estkey = 'REG-III';
                $estkey2 = 'RH-III';
            }
            $regArr[$key]['regional'] = $estkey;
            $regArr[$key]['jabatan'] = $estkey2;
            foreach ($queryAsisten as $ast => $asisten) {
                if ($estkey === $asisten['est'] && $em === $asisten['afd']) {
                    $regArr[$key]['nama_asisten'] = $asisten['nama'];
                }
            }
            $regArr[$key]['tnp_brd'] = $tnpBRDEST;
            $regArr[$key]['krg_brd'] = $krgBRDEST;
            $regArr[$key]['persenTNP_brd'] = ($jjg_sampleEST - $abrEST) !== 0 ? round(($krgBRDEST / ($jjg_sampleEST - $abrEST)) * 100, 2) : 0;
            $regArr[$key]['persenKRG_brd'] = ($jjg_sampleEST - $abrEST) !== 0 ? round(($krgBRDEST / ($jjg_sampleEST - $abrEST)) * 100, 2) : 0;
            $regArr[$key]['total_jjg'] = $tnpBRDEST + $krgBRDEST;
            $regArr[$key]['persen_totalJjg'] = $skor_totalEST;
            $regArr[$key]['skor_totalEST'] = sidak_brdTotal($skor_totalEST);
            $regArr[$key]['jjg_matang'] = $jjg_sampleEST - ($tnpBRDEST + $krgBRDEST + $overripeEST + $emptyEST + $abr);
            $regArr[$key]['persen_jjgMtang'] = $skot_jjgmskEST;
            $regArr[$key]['skor_jjgMatang'] = sidak_matangSKOR($skot_jjgmskEST);
            $regArr[$key]['lewat_matang'] = $overripeEST;
            $regArr[$key]['persen_lwtMtng'] =  $skor_lewatmatangEST;
            $regArr[$key]['skor_lewatmatangEST'] = sidak_lwtMatang($skor_lewatmatangEST);
            $regArr[$key]['janjang_kosong'] = $emptyEST;
            $regArr[$key]['persen_kosong'] = $skor_jjgKosongEST;
            $regArr[$key]['skor_kosong'] = sidak_jjgKosong($skor_jjgKosongEST);
            $regArr[$key]['vcut'] = $vcutEST;
            $regArr[$key]['vcut_persen'] = $skor_vcutEST;
            $regArr[$key]['vcut_skor'] = sidak_tangkaiP($skor_vcutEST);
            $regArr[$key]['abnormal'] = $abrEST;
            $regArr[$key]['abnormal_persen'] = $jjg_sampleEST !== 0 ? round(($abrEST / $jjg_sampleEST) * 100, 2) : 0;
            $regArr[$key]['rat_dmg'] = $rdEST;
            $regArr[$key]['rd_persen'] = $jjg_sampleEST !== 0 ? round(($rdEST / $jjg_sampleEST) * 100, 2) : 0;
            $regArr[$key]['karung'] = $sum_krEST;
            $regArr[$key]['TPH'] = $total_krEST;
            $regArr[$key]['persen_krg'] = $per_krEST;
            $regArr[$key]['skor_kr'] = sidak_PengBRD($per_krEST);
            $regArr[$key]['all_skorYear'] = $allSkorEST;
            $regArr[$key]['kategori'] = sidak_akhir($allSkorEST);

            // foreach ($variable as $key => $value) {
            //     # code...
            // }
        }
        // dd($regArr);

        //bagian chart untuk perweek
        $chartMatang = [];

        foreach ($mutubuah_est as $outer_array) {
            foreach ($outer_array as $key => $inner_array) {
                if (isset($inner_array['persen_jjgMtang'])) {
                    $chartMatang[$key] = $inner_array['persen_jjgMtang'];
                }
            }
        }

        $chartMentah = [];

        foreach ($mutubuah_est as $outer_array) {
            foreach ($outer_array as $key => $inner_array) {
                if (isset($inner_array['persen_jjgmentah'])) {
                    $chartMentah[$key] = $inner_array['persen_jjgmentah'];
                }
            }
        }
        $chartLwtMatang = [];

        foreach ($mutubuah_est as $outer_array) {
            foreach ($outer_array as $key => $inner_array) {
                if (isset($inner_array['persen_lwtMtng'])) {
                    $chartLwtMatang[$key] = $inner_array['persen_lwtMtng'];
                }
            }
        }
        $chartJjgKosong = [];

        foreach ($mutubuah_est as $outer_array) {
            foreach ($outer_array as $key => $inner_array) {
                if (isset($inner_array['persen_kosong'])) {
                    $chartJjgKosong[$key] = $inner_array['persen_kosong'];
                }
            }
        }
        $chartVcut = [];

        foreach ($mutubuah_est as $outer_array) {
            foreach ($outer_array as $key => $inner_array) {
                if (isset($inner_array['vcut_persen'])) {
                    $chartVcut[$key] = $inner_array['vcut_persen'];
                }
            }
        }
        $chartKarung = [];

        foreach ($mutubuah_est as $outer_array) {
            foreach ($outer_array as $key => $inner_array) {
                if (isset($inner_array['TPH'])) {
                    $chartKarung[$key] = $inner_array['TPH'];
                }
            }
        }


        $result = $mutu_bhWil;
        $persen_jjgMtang_values = array();
        $persen_jjgmentah_values = array();
        $persen_lwtMtng_values = array();
        $persen_kosong_values = array();
        $vcut_persen_values = array();
        $TPH_values = array();
        // dd($result);
        foreach ($result as $estate => $estateData) {


            $persen_jjgMtang_values[$estate] = $estateData['persen_jjgMtang'] ?? 0;
            $persen_jjgmentah_values[$estate] = $estateData['persen_jjgmentah'] ?? 0;
            $persen_lwtMtng_values[$estate] = $estateData['persen_lwtMtng'] ?? 0;
            $persen_kosong_values[$estate] = $estateData['persen_kosong'] ?? 0;
            $vcut_persen_values[$estate] = $estateData['vcut_persen'] ?? 0;
            $TPH_values[$estate] = $estateData['TPH'] ?? 0;
        }
        // dd($persen_jjgMtang_values, $persen_lwtMtng_values);

        $optionREg = DB::connection('mysql2')->table('reg')
            ->select('reg.*')
            ->where('reg.id', $regional)
            // ->where('wil.regional', 1)
            ->get();


        $optionReg = json_decode($optionREg, true);

        // $regional = array();
        // foreach ($optionReg as $key => $value) {
        //     $value['nama'] = str_replace('Regional', 'Reg-', $value['nama']);
        //     $value['nama'] = str_replace(' ', '', $value['nama']);
        //     $value['nama'] = strtoupper($value['nama']);
        //     $regional[] = $value;
        //     $regional[$key]['jabatan'] = 'RH';
        //     $regional['jabatan'] = 'RH-' . substr($value['nama'], strpos($value['nama'], 'Reg-') + strlen('Reg-'));
        // }



        // foreach ($regional as $key => $value) {
        //     $regional[$key]['nama_rh'] = '-';
        //     foreach ($queryAsisten as $ast => $asisten) {
        //         if ($asisten['est'] == $value['nama'] && $asisten['afd'] == 'RH') {
        //             $regional[$key]['nama_rh'] = $asisten['nama'];
        //             break; // exit the inner loop since a match is found
        //         }
        //     }
        // }

        $regional = array();
        foreach ($optionReg as $key => $value) {
            $value['nama'] = str_replace('Regional', 'Reg-', $value['nama']);
            $value['nama'] = str_replace(' ', '', $value['nama']);
            $value['nama'] = strtoupper($value['nama']);
            $regional[$key] = $value;
            $regional[$key]['jabatan'] = 'RH-' . substr($value['nama'], strpos($value['nama'], 'Reg-') + strlen('Reg-'));
        }

        foreach ($regional as $key => $value) {
            $regional[$key]['nama_rh'] = '-';
            foreach ($queryAsisten as $ast => $asisten) {
                // dd($value['nama']);
                if ($asisten['est'] == $value['nama'] && $asisten['afd'] == 'RH') {
                    $regional[$key]['nama_rh'] = $asisten['nama'];
                    break; // exit the inner loop since a match is found
                }
            }
        }
        // updateKeyRecursive($mutu_buah, "KTE4", "KTE");


        // // Change key "KTE4" to "KTE"
        // updateKeyRecursive3($mutubuah_est[0], "KTE4", "KTE");
        // $estev2 = updateKeyRecursive2($estev2);

        // dd($mutubuah_est);

        $arrView = array();

        $arrView['listregion'] =  $estev2;
        $arrView['mutu_buah'] =  $mutu_buah;
        $arrView['mutubuah_est'] =  $mutubuah_est;
        $arrView['mutuBuah_wil'] =  $mutuBuah_wil;
        $arrView['regional'] =  $regArr;
        $arrView['queryAsisten'] =  $queryAsisten;
        $arrView['chart_matang'] =  $chartMatang;
        $arrView['chart_mentah'] =  $chartMentah;
        $arrView['chart_lewatmatang'] =  $chartLwtMatang;
        $arrView['chart_janjangkosong'] =  $chartJjgKosong;
        $arrView['chart_vcut'] =  $chartVcut;
        $arrView['chart_karung'] =  $chartKarung;

        $arrView['chart_matangwil'] =  $persen_jjgMtang_values;
        $arrView['chart_mentahwil'] =  $persen_jjgmentah_values;
        $arrView['chart_lewatmatangwil'] =  $persen_lwtMtng_values;
        $arrView['chart_janjangkosongwil'] =  $persen_kosong_values;
        $arrView['chart_vcutwil'] =  $vcut_persen_values;
        $arrView['chart_karungwil'] =  $TPH_values;
        $arrView['optionREg'] =  $optionREg;
        $arrView['regionaltab'] =  $regional;


        echo json_encode($arrView); //di decode ke dalam bentuk json dalam vaiavel arrview yang dapat menampung banyak isi array
        exit();
    }


    public function getYear(Request $request)
    {


        $week = $request->input('week');
        // Convert the week format to start and end dates
        $weekDateTime = new DateTime($week);
        $weekDateTime->setISODate((int)$weekDateTime->format('o'), (int)$weekDateTime->format('W'));

        $startDate = $weekDateTime->format('Y-m-d');
        $weekDateTime->modify('+6 days');
        $endDate = $weekDateTime->format('Y-m-d');

        // dd($startDate, $endDate);
        $RegData = $request->input('regData');
        $optionREg = DB::connection('mysql2')->table('reg')
            ->select('reg.*')
            ->where('reg.id', $RegData)
            // ->where('wil.regional', 1)
            ->get();


        $optionReg = json_decode($optionREg, true);
        $queryAsisten = DB::connection('mysql2')->table('asisten_qc')
            ->select('asisten_qc.*')
            ->get();

        $queryAsisten = json_decode($queryAsisten, true);

        // dd($startDate, $endDate);
        $queryEste = DB::connection('mysql2')->table('estate')
            ->select('estate.*')
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->where('wil.regional', $RegData)
            ->whereNotIn('estate.est', ['SRE', 'LDE', 'SKE', 'CWS1', 'CWS2', 'CWS3'])
            ->get();
        $queryEste = json_decode($queryEste, true);

        $estev2 = DB::connection('mysql2')->table('estate')
            ->select('estate.*')
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->where('wil.regional', $RegData)
            ->whereNotIn('estate.est', ['SRE', 'LDE', 'SKE', 'CWS1', 'CWS2', 'CWS3'])
            ->pluck('est');
        $estev2 = json_decode($estev2, true);

        $queryAfd = DB::connection('mysql2')->table('afdeling')
            ->select(
                'afdeling.id',
                'afdeling.nama',
                'estate.est'
            ) //buat mengambil data di estate db dan willayah db
            ->join('estate', 'estate.id', '=', 'afdeling.estate') //kemudian di join untuk mengambil est perwilayah
            ->get();
        $queryAfd = json_decode($queryAfd, true);

        $queryMTbuah = DB::connection('mysql2')->table('sidak_mutu_buah')
            ->select(
                "sidak_mutu_buah.*",
                DB::raw('DATE_FORMAT(sidak_mutu_buah.datetime, "%M") as bulan'),
                DB::raw('DATE_FORMAT(sidak_mutu_buah.datetime, "%Y") as tahun')
            )
            ->whereBetween('sidak_mutu_buah.datetime', [$startDate, $endDate])
            // ->where('sidak_mutu_buah.datetime', 'like', '%' . $bulan . '%')

            // ->whereBetween('sidak_mutu_buah.datetime', ['2023-04-03', '2023-04-09'])
            ->get();
        $queryMTbuah = $queryMTbuah->groupBy(['estate', 'afdeling']);
        $queryMTbuah = json_decode($queryMTbuah, true);

        $databulananBuah = array();
        foreach ($queryMTbuah as $key => $value) {
            foreach ($value as $key2 => $value2) {
                foreach ($value2 as $key3 => $value3) {

                    $databulananBuah[$key][$key2][$key3] = $value3;
                }
            }
        }

        $defPerbulanWil = array();

        foreach ($queryEste as $key2 => $value2) {
            foreach ($queryAfd as $key3 => $value3) {
                if ($value2['est'] == $value3['est']) {
                    $defPerbulanWil[$value2['est']][$value3['nama']] = 0;
                }
            }
        }



        foreach ($defPerbulanWil as $estateKey => $afdelingArray) {
            foreach ($afdelingArray as $afdelingKey => $afdelingValue) {
                if (isset($databulananBuah[$estateKey]) && isset($databulananBuah[$estateKey][$afdelingKey])) {
                    $defPerbulanWil[$estateKey][$afdelingKey] = $databulananBuah[$estateKey][$afdelingKey];
                }
            }
        }

        // dd($defPerbulanWil);
        $sidak_buah = array();
        foreach ($defPerbulanWil as $key => $value) {
            $totalJJG = 0;
            $totaltnpBRD = 0;
            $totalkrgBRD = 0;
            $totalabr = 0;
            $TotPersenTNP = 0;
            $TotPersenKRG = 0;
            $totJJG = 0;
            $totPersenTOtaljjg = 0;
            $totSkor_total = 0;
            $totoverripe = 0;
            $totempty = 0;
            $totJJG_matang = 0;
            $totPer_jjgMtng = 0;
            $totPer_over = 0;
            $totSkor_Over = 0;
            $totPer_Empty = 0;
            $totSkor_Empty = 0;
            $totVcut = 0;
            $totPer_vcut =  0;
            $totSkor_Vcut =  0;
            $totPer_abr =  0;
            $totRD = 0;
            $totPer_rd = 0;
            $totBlok = 0;
            $totKR = 0;
            $tot_krS = 0;
            $totPer_kr = 0;
            $totSkor_kr = 0;
            $totALlskor = 0;
            $totKategor = 0;
            foreach ($value as $key1 => $value1) {
                if (is_array($value1)) {
                    $jjg_sample = 0;
                    $tnpBRD = 0;
                    $krgBRD = 0;
                    $abr = 0;
                    $skor_total = 0;
                    $overripe = 0;
                    $empty = 0;
                    $vcut = 0;
                    $rd = 0;
                    $sum_kr = 0;
                    $allSkor = 0;
                    $combination_counts = array();

                    foreach ($value1 as $key2 => $value2) {
                        $combination = $value2['blok'] . ' ' . $value2['estate'] . ' ' . $value2['afdeling'] . ' ' . $value2['tph_baris'];
                        if (!isset($combination_counts[$combination])) {
                            $combination_counts[$combination] = 0;
                        }
                        $jjg_sample += $value2['jumlah_jjg'];
                        $tnpBRD += $value2['bmt'];
                        $krgBRD += $value2['bmk'];
                        $abr += $value2['abnormal'];
                        $overripe += $value2['overripe'];
                        $empty += $value2['empty_bunch'];
                        $vcut += $value2['vcut'];
                        $rd += $value2['rd'];
                        $sum_kr += $value2['alas_br'];
                    }
                    $dataBLok = count($combination_counts);
                    if ($sum_kr != 0) {
                        $total_kr = round($sum_kr / $dataBLok, 2);
                    } else {
                        $total_kr = 0;
                    }
                    $per_kr = round($total_kr * 100, 2);
                    $skor_total = round((($tnpBRD + $krgBRD) / ($jjg_sample - $abr)) * 100, 2);
                    $skor_jjgMSk = round(($jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr)) / ($jjg_sample - $abr) * 100, 2);
                    $skor_lewatMTng =  round(($overripe / ($jjg_sample - $abr)) * 100, 2);
                    $skor_jjgKosong =  round(($empty / ($jjg_sample - $abr)) * 100, 2);
                    $skor_vcut =   round(($vcut / $jjg_sample) * 100, 2);
                    $allSkor = sidak_brdTotal($skor_total) +  sidak_matangSKOR($skor_jjgMSk) +  sidak_lwtMatang($skor_lewatMTng) + sidak_jjgKosong($skor_jjgKosong) + sidak_tangkaiP($skor_vcut) + sidak_PengBRD($per_kr);

                    $sidak_buah[$key][$key1]['Jumlah_janjang'] = $jjg_sample;
                    $sidak_buah[$key][$key1]['blok'] = $dataBLok;
                    $sidak_buah[$key][$key1]['est'] = $key;
                    $sidak_buah[$key][$key1]['afd'] = $key1;
                    $sidak_buah[$key][$key1]['nama_staff'] = '-';
                    $sidak_buah[$key][$key1]['tnp_brd'] = $tnpBRD;
                    $sidak_buah[$key][$key1]['krg_brd'] = $krgBRD;
                    $sidak_buah[$key][$key1]['persenTNP_brd'] = round(($tnpBRD / ($jjg_sample - $abr)) * 100, 2);
                    $sidak_buah[$key][$key1]['persenKRG_brd'] = round(($krgBRD / ($jjg_sample - $abr)) * 100, 2);
                    $sidak_buah[$key][$key1]['total_jjg'] = $tnpBRD + $krgBRD;
                    $sidak_buah[$key][$key1]['persen_totalJjg'] = $skor_total;
                    $sidak_buah[$key][$key1]['skor_total'] = sidak_brdTotal($skor_total);
                    $sidak_buah[$key][$key1]['jjg_matang'] = $jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr);
                    $sidak_buah[$key][$key1]['persen_jjgMtang'] = $skor_jjgMSk;
                    $sidak_buah[$key][$key1]['skor_jjgMatang'] = sidak_matangSKOR($skor_jjgMSk);
                    $sidak_buah[$key][$key1]['lewat_matang'] = $overripe;
                    $sidak_buah[$key][$key1]['persen_lwtMtng'] =  $skor_lewatMTng;
                    $sidak_buah[$key][$key1]['skor_lewatMTng'] = sidak_lwtMatang($skor_lewatMTng);
                    $sidak_buah[$key][$key1]['janjang_kosong'] = $empty;
                    $sidak_buah[$key][$key1]['persen_kosong'] = $skor_jjgKosong;
                    $sidak_buah[$key][$key1]['skor_kosong'] = sidak_jjgKosong($skor_jjgKosong);
                    $sidak_buah[$key][$key1]['vcut'] = $vcut;
                    $sidak_buah[$key][$key1]['karung'] = $sum_kr;
                    $sidak_buah[$key][$key1]['vcut_persen'] = $skor_vcut;
                    $sidak_buah[$key][$key1]['vcut_skor'] = sidak_tangkaiP($skor_vcut);
                    $sidak_buah[$key][$key1]['abnormal'] = $abr;
                    $sidak_buah[$key][$key1]['abnormal_persen'] = round(($abr / $jjg_sample) * 100, 2);
                    $sidak_buah[$key][$key1]['rat_dmg'] = $rd;
                    $sidak_buah[$key][$key1]['rd_persen'] = round(($rd / $jjg_sample) * 100, 2);
                    $sidak_buah[$key][$key1]['TPH'] = $total_kr;
                    $sidak_buah[$key][$key1]['persen_krg'] = $per_kr;
                    $sidak_buah[$key][$key1]['skor_kr'] = sidak_PengBRD($per_kr);
                    $sidak_buah[$key][$key1]['All_skor'] = $allSkor;
                    $sidak_buah[$key][$key1]['kategori'] = sidak_akhir($allSkor);

                    foreach ($queryAsisten as $ast => $asisten) {
                        if ($key === $asisten['est'] && $key1 === $asisten['afd']) {
                            $sidak_buah[$key][$key1]['nama_asisten'] = $asisten['nama'];
                        }
                    }


                    $totalJJG += $jjg_sample;
                    $totaltnpBRD += $tnpBRD;
                    $totalkrgBRD += $krgBRD;
                    $totalabr += $abr;
                    $TotPersenTNP = round(($totaltnpBRD / ($totalJJG - $totalabr)) * 100, 2);
                    $TotPersenKRG = round(($totalkrgBRD / ($totalJJG - $totalabr)) * 100, 2);
                    $totJJG = $totaltnpBRD + $totalkrgBRD;
                    $totPersenTOtaljjg = round((($totaltnpBRD + $totalkrgBRD) / ($totalJJG - $totalabr)) * 100, 2);
                    $totSkor_total = sidak_brdTotal($totPersenTOtaljjg);
                    $totoverripe += $overripe;
                    $totempty += $empty;
                    $totJJG_matang = $totalJJG - ($totaltnpBRD + $totalkrgBRD + $totoverripe + $totempty + $totalabr);
                    $totPer_jjgMtng = round($totJJG_matang / ($totalJJG - $totalabr) * 100, 2);

                    $totSkor_jjgMtng = sidak_matangSKOR($totPer_jjgMtng);
                    $totPer_over = round(($totoverripe / ($totalJJG - $totalabr)) * 100, 2);
                    $totSkor_Over = sidak_lwtMatang($totPer_over);
                    $totPer_Empty = round(($totempty / ($totalJJG - $totalabr)) * 100, 2);
                    $totSkor_Empty = sidak_jjgKosong($totPer_Empty);
                    $totVcut += $vcut;
                    $totPer_vcut =   round(($totVcut / $totalJJG) * 100, 2);
                    $totSkor_Vcut =  sidak_tangkaiP($totPer_vcut);
                    $totPer_abr =  round(($totalabr / $totalJJG) * 100, 2);
                    $totRD += $rd;
                    $totPer_rd = round(($totRD / $totalJJG) * 100, 2);
                    $totBlok += $dataBLok;
                    $totKR += $sum_kr;
                    if ($totKR != 0) {
                        $tot_krS = round($totKR / $totBlok, 2);
                    } else {
                        $tot_krS = 0;
                    }
                    $totPer_kr = round($tot_krS * 100, 2);
                    $totSkor_kr = sidak_PengBRD($totPer_kr);
                    $totALlskor = sidak_brdTotal($totPersenTOtaljjg) + sidak_matangSKOR($totPer_jjgMtng) + sidak_lwtMatang($totPer_over) + sidak_jjgKosong($totPer_Empty) + sidak_tangkaiP($totPer_vcut) + sidak_PengBRD($totPer_kr);

                    $totKategor = sidak_akhir($totALlskor);
                } else {

                    $sidak_buah[$key][$key1]['Jumlah_janjang'] = 0;
                    $sidak_buah[$key][$key1]['blok'] = 0;
                    $sidak_buah[$key][$key1]['est'] = $key;
                    $sidak_buah[$key][$key1]['afd'] = $key1;
                    $sidak_buah[$key][$key1]['nama_staff'] = '-';
                    $sidak_buah[$key][$key1]['tnp_brd'] = 0;
                    $sidak_buah[$key][$key1]['krg_brd'] = 0;
                    $sidak_buah[$key][$key1]['persenTNP_brd'] = 0;
                    $sidak_buah[$key][$key1]['persenKRG_brd'] = 0;
                    $sidak_buah[$key][$key1]['total_jjg'] = 0;
                    $sidak_buah[$key][$key1]['persen_totalJjg'] = 0;
                    $sidak_buah[$key][$key1]['skor_total'] = 0;
                    $sidak_buah[$key][$key1]['jjg_matang'] = 0;
                    $sidak_buah[$key][$key1]['persen_jjgMtang'] = 0;
                    $sidak_buah[$key][$key1]['skor_jjgMatang'] = 0;
                    $sidak_buah[$key][$key1]['lewat_matang'] = 0;
                    $sidak_buah[$key][$key1]['persen_lwtMtng'] =  0;
                    $sidak_buah[$key][$key1]['skor_lewatMTng'] = 0;
                    $sidak_buah[$key][$key1]['janjang_kosong'] = 0;
                    $sidak_buah[$key][$key1]['persen_kosong'] = 0;
                    $sidak_buah[$key][$key1]['skor_kosong'] = 0;
                    $sidak_buah[$key][$key1]['vcut'] = 0;
                    $sidak_buah[$key][$key1]['karung'] = 0;
                    $sidak_buah[$key][$key1]['vcut_persen'] = 0;
                    $sidak_buah[$key][$key1]['vcut_skor'] = 0;
                    $sidak_buah[$key][$key1]['abnormal'] = 0;
                    $sidak_buah[$key][$key1]['abnormal_persen'] = 0;
                    $sidak_buah[$key][$key1]['rat_dmg'] = 0;
                    $sidak_buah[$key][$key1]['rd_persen'] = 0;
                    $sidak_buah[$key][$key1]['TPH'] = 0;
                    $sidak_buah[$key][$key1]['persen_krg'] = 0;
                    $sidak_buah[$key][$key1]['skor_kr'] = 0;
                    $sidak_buah[$key][$key1]['All_skor'] = 0;
                    $sidak_buah[$key][$key1]['kategori'] = 0;
                    foreach ($queryAsisten as $ast => $asisten) {
                        if ($key === $asisten['est'] && $key1 === $asisten['afd']) {
                            $sidak_buah[$key][$key1]['nama_asisten'] = $asisten['nama'];
                        }
                    }
                }
            }
        }

        // dd($sidak_buah);

        $mutu_buah = array();
        foreach ($queryEste as $key => $value) {
            foreach ($sidak_buah as $key2 => $value2) {
                if ($value['est'] == $key2) {
                    $mutu_buah[$value['wil']][$key2] = array_merge($mutu_buah[$value['wil']][$key2] ?? [], $value2);
                }
            }
        }

        $mutu_buahv2 = array();
        foreach ($queryEste as $key => $value) {
            foreach ($sidak_buah as $key2 => $value2) {
                if ($value['est'] == $key2) {
                    $mutu_buahv2[$value['wil']][$key2] = array_merge($mutu_buah[$value['wil']][$key2] ?? [], $value2);
                }
            }
        }
        // dd($mutu_buahv2);
        // Remove the "Plasma1" element from the original array
        if (isset($mutu_buah[1]['Plasma1'])) {
            $plasma1 = $mutu_buah[1]['Plasma1'];
            unset($mutu_buah[1]['Plasma1']);
        } else {
            $plasma1 = null;
        }

        // Add the "Plasma1" element to its own index
        if ($plasma1 !== null) {
            $mutu_buah[4] = ['Plasma1' => $plasma1];
        }
        // Optional: Re-index the array to ensure the keys are in sequential order


        if (isset($mutu_buah[4]['Plasma2'])) {
            $Plasma2 = $mutu_buah[4]['Plasma2'];
            unset($mutu_buah[4]['Plasma2']);
        } else {
            $Plasma2 = null;
        }

        // Add the "Plasma2" element to its own index
        if ($Plasma2 !== null) {
            $mutu_buah[7] = ['Plasma2' => $Plasma2];
        }

        if (isset($mutu_buah[7]['Plasma3'])) {
            $Plasma3 = $mutu_buah[7]['Plasma3'];
            unset($mutu_buah[7]['Plasma3']);
        } else {
            $Plasma3 = null;
        }

        // Add the "Plasma3" element to its own index
        if ($Plasma3 !== null) {
            $mutu_buah[9] = ['Plasma3' => $Plasma3];
        }
        // Optional: Re-index the array to ensure the keys are in sequential order
        $mutu_buah = array_values($mutu_buah);

        $mutubuah_est = array();
        foreach ($mutu_buah as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $jjg_sample = 0;
                $tnpBRD = 0;
                $krgBRD = 0;
                $abr = 0;
                $skor_total = 0;
                $overripe = 0;
                $empty = 0;
                $vcut = 0;
                $rd = 0;
                $sum_kr = 0;
                $allSkor = 0;
                $dataBLok = 0;
                foreach ($value1 as $key2 => $value2) {
                    $jjg_sample += $value2['Jumlah_janjang'];
                    $tnpBRD += $value2['tnp_brd'];
                    $krgBRD += $value2['krg_brd'];
                    $abr += $value2['abnormal'];
                    $overripe += $value2['lewat_matang'];
                    $empty += $value2['janjang_kosong'];
                    $vcut += $value2['vcut'];

                    $rd += $value2['rat_dmg'];

                    $dataBLok += $value2['blok'];
                    $sum_kr += $value2['karung'];
                }

                if ($sum_kr != 0) {
                    $total_kr = round($sum_kr / $dataBLok, 2);
                } else {
                    $total_kr = 0;
                }
                $per_kr = round($total_kr * 100, 2);
                $skor_total = round(($jjg_sample - $abr != 0 ? (($tnpBRD + $krgBRD) / ($jjg_sample - $abr)) * 100 : 0), 2);

                $skor_jjgMSk = round(($jjg_sample - $abr != 0 ? (($jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr)) / ($jjg_sample - $abr)) * 100 : 0), 2);

                $skor_lewatMTng = round(($jjg_sample - $abr != 0 ? ($overripe / ($jjg_sample - $abr)) * 100 : 0), 2);

                $skor_jjgKosong = round(($jjg_sample - $abr != 0 ? ($empty / ($jjg_sample - $abr)) * 100 : 0), 2);

                $skor_vcut = round(($jjg_sample != 0 ? ($vcut / $jjg_sample) * 100 : 0), 2);

                $allSkor = sidak_brdTotal($skor_total) +  sidak_matangSKOR($skor_jjgMSk) +  sidak_lwtMatang($skor_lewatMTng) + sidak_jjgKosong($skor_jjgKosong) + sidak_tangkaiP($skor_vcut) + sidak_PengBRD($per_kr);

                $em = 'EM';

                $nama_em = '';

                // dd($key1);
                foreach ($queryAsisten as $ast => $asisten) {
                    if ($key1 === $asisten['est'] && $em === $asisten['afd']) {
                        $nama_em = $asisten['nama'];
                    }
                }
                $jjg_mth = $tnpBRD + $krgBRD + $overripe + $empty;

                $skor_jjgMTh = ($jjg_sample - $abr != 0) ? round($jjg_mth / ($jjg_sample - $abr) * 100, 2) : 0;

                $mutubuah_est[$key][$key1]['jjg_mantah'] = $jjg_mth;
                $mutubuah_est[$key][$key1]['persen_jjgmentah'] = $skor_jjgMTh;

                if ($jjg_sample == 0 && $tnpBRD == 0 &&   $krgBRD == 0 && $abr == 0 && $overripe == 0 && $empty == 0 &&  $vcut == 0 &&  $rd == 0 && $sum_kr == 0) {
                    $mutubuah_est[$key][$key1]['check_arr'] = 'kosong';
                    $mutubuah_est[$key][$key1]['All_skor'] = 0;
                } else {
                    $mutubuah_est[$key][$key1]['check_arr'] = 'ada';
                    $mutubuah_est[$key][$key1]['All_skor'] = $allSkor;
                }

                $mutubuah_est[$key][$key1]['Jumlah_janjang'] = $jjg_sample;
                $mutubuah_est[$key][$key1]['blok'] = $dataBLok;
                $mutubuah_est[$key][$key1]['EM'] = 'EM';
                $mutubuah_est[$key][$key1]['Nama_assist'] = $nama_em;
                $mutubuah_est[$key][$key1]['nama_staff'] = '-';
                $mutubuah_est[$key][$key1]['tnp_brd'] = $tnpBRD;
                $mutubuah_est[$key][$key1]['krg_brd'] = $krgBRD;
                $mutubuah_est[$key][$key1]['persenTNP_brd'] = round(($jjg_sample - $abr != 0 ? ($tnpBRD / ($jjg_sample - $abr)) * 100 : 0), 2);
                $mutubuah_est[$key][$key1]['persenKRG_brd'] = round(($jjg_sample - $abr != 0 ? ($krgBRD / ($jjg_sample - $abr)) * 100 : 0), 2);
                $mutubuah_est[$key][$key1]['abnormal_persen'] = round(($jjg_sample != 0 ? ($abr / $jjg_sample) * 100 : 0), 2);
                $mutubuah_est[$key][$key1]['rd_persen'] = round(($jjg_sample != 0 ? ($rd / $jjg_sample) * 100 : 0), 2);


                $mutubuah_est[$key][$key1]['total_jjg'] = $tnpBRD + $krgBRD;
                $mutubuah_est[$key][$key1]['persen_totalJjg'] = $skor_total;
                $mutubuah_est[$key][$key1]['skor_total'] = sidak_brdTotal($skor_total);
                $mutubuah_est[$key][$key1]['jjg_matang'] = $jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr);
                $mutubuah_est[$key][$key1]['persen_jjgMtang'] = $skor_jjgMSk;
                $mutubuah_est[$key][$key1]['skor_jjgMatang'] = sidak_matangSKOR($skor_jjgMSk);
                $mutubuah_est[$key][$key1]['lewat_matang'] = $overripe;
                $mutubuah_est[$key][$key1]['persen_lwtMtng'] =  $skor_lewatMTng;
                $mutubuah_est[$key][$key1]['skor_lewatMTng'] = sidak_lwtMatang($skor_lewatMTng);
                $mutubuah_est[$key][$key1]['janjang_kosong'] = $empty;
                $mutubuah_est[$key][$key1]['persen_kosong'] = $skor_jjgKosong;
                $mutubuah_est[$key][$key1]['skor_kosong'] = sidak_jjgKosong($skor_jjgKosong);
                $mutubuah_est[$key][$key1]['vcut'] = $vcut;
                $mutubuah_est[$key][$key1]['vcut_persen'] = $skor_vcut;
                $mutubuah_est[$key][$key1]['vcut_skor'] = sidak_tangkaiP($skor_vcut);
                $mutubuah_est[$key][$key1]['abnormal'] = $abr;

                $mutubuah_est[$key][$key1]['rat_dmg'] = $rd;

                $mutubuah_est[$key][$key1]['karung'] = $sum_kr;
                $mutubuah_est[$key][$key1]['TPH'] = $total_kr;
                $mutubuah_est[$key][$key1]['persen_krg'] = $per_kr;
                $mutubuah_est[$key][$key1]['skor_kr'] = sidak_PengBRD($per_kr);
                // $mutubuah_est[$key][$key1]['All_skor'] = $allSkor;
                $mutubuah_est[$key][$key1]['kategori'] = sidak_akhir($allSkor);
            }
        }
        $mutu_buahEst = array();
        foreach ($mutu_buahv2 as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $jjg_sample = 0;
                $tnpBRD = 0;
                $krgBRD = 0;
                $abr = 0;
                $skor_total = 0;
                $overripe = 0;
                $empty = 0;
                $vcut = 0;
                $rd = 0;
                $sum_kr = 0;
                $allSkor = 0;
                $dataBLok = 0;
                foreach ($value1 as $key2 => $value2) {
                    $jjg_sample += $value2['Jumlah_janjang'];
                    $tnpBRD += $value2['tnp_brd'];
                    $krgBRD += $value2['krg_brd'];
                    $abr += $value2['abnormal'];
                    $overripe += $value2['lewat_matang'];
                    $empty += $value2['janjang_kosong'];
                    $vcut += $value2['vcut'];

                    $rd += $value2['rat_dmg'];

                    $dataBLok += $value2['blok'];
                    $sum_kr += $value2['karung'];
                }

                if ($sum_kr != 0) {
                    $total_kr = round($sum_kr / $dataBLok, 2);
                } else {
                    $total_kr = 0;
                }
                $per_kr = round($total_kr * 100, 2);
                $skor_total = round(($jjg_sample - $abr != 0 ? (($tnpBRD + $krgBRD) / ($jjg_sample - $abr)) * 100 : 0), 2);

                $skor_jjgMSk = round(($jjg_sample - $abr != 0 ? (($jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr)) / ($jjg_sample - $abr)) * 100 : 0), 2);

                $skor_lewatMTng = round(($jjg_sample - $abr != 0 ? ($overripe / ($jjg_sample - $abr)) * 100 : 0), 2);

                $skor_jjgKosong = round(($jjg_sample - $abr != 0 ? ($empty / ($jjg_sample - $abr)) * 100 : 0), 2);

                $skor_vcut = round(($jjg_sample != 0 ? ($vcut / $jjg_sample) * 100 : 0), 2);

                $allSkor = sidak_brdTotal($skor_total) +  sidak_matangSKOR($skor_jjgMSk) +  sidak_lwtMatang($skor_lewatMTng) + sidak_jjgKosong($skor_jjgKosong) + sidak_tangkaiP($skor_vcut) + sidak_PengBRD($per_kr);

                $em = 'EM';

                $nama_em = '';

                // dd($key1);
                foreach ($queryAsisten as $ast => $asisten) {
                    if ($key1 === $asisten['est'] && $em === $asisten['afd']) {
                        $nama_em = $asisten['nama'];
                    }
                }
                $jjg_mth = $tnpBRD + $krgBRD + $overripe + $empty;

                $skor_jjgMTh = ($jjg_sample - $abr != 0) ? round($jjg_mth / ($jjg_sample - $abr) * 100, 2) : 0;

                $mutu_buahEst[$key][$key1]['jjg_mantah'] = $jjg_mth;
                $mutu_buahEst[$key][$key1]['persen_jjgmentah'] = $skor_jjgMTh;


                $mutu_buahEst[$key][$key1]['Jumlah_janjang'] = $jjg_sample;
                $mutu_buahEst[$key][$key1]['blok'] = $dataBLok;
                $mutu_buahEst[$key][$key1]['EM'] = 'EM';
                $mutu_buahEst[$key][$key1]['Nama_assist'] = $nama_em;
                $mutu_buahEst[$key][$key1]['nama_staff'] = '-';
                $mutu_buahEst[$key][$key1]['tnp_brd'] = $tnpBRD;
                $mutu_buahEst[$key][$key1]['krg_brd'] = $krgBRD;
                $mutu_buahEst[$key][$key1]['persenTNP_brd'] = round(($jjg_sample - $abr != 0 ? ($tnpBRD / ($jjg_sample - $abr)) * 100 : 0), 2);
                $mutu_buahEst[$key][$key1]['persenKRG_brd'] = round(($jjg_sample - $abr != 0 ? ($krgBRD / ($jjg_sample - $abr)) * 100 : 0), 2);
                $mutu_buahEst[$key][$key1]['abnormal_persen'] = round(($jjg_sample != 0 ? ($abr / $jjg_sample) * 100 : 0), 2);
                $mutu_buahEst[$key][$key1]['rd_persen'] = round(($jjg_sample != 0 ? ($rd / $jjg_sample) * 100 : 0), 2);


                $mutu_buahEst[$key][$key1]['total_jjg'] = $tnpBRD + $krgBRD;
                $mutu_buahEst[$key][$key1]['persen_totalJjg'] = $skor_total;
                $mutu_buahEst[$key][$key1]['skor_total'] = sidak_brdTotal($skor_total);
                $mutu_buahEst[$key][$key1]['jjg_matang'] = $jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr);
                $mutu_buahEst[$key][$key1]['persen_jjgMtang'] = $skor_jjgMSk;
                $mutu_buahEst[$key][$key1]['skor_jjgMatang'] = sidak_matangSKOR($skor_jjgMSk);
                $mutu_buahEst[$key][$key1]['lewat_matang'] = $overripe;
                $mutu_buahEst[$key][$key1]['persen_lwtMtng'] =  $skor_lewatMTng;
                $mutu_buahEst[$key][$key1]['skor_lewatMTng'] = sidak_lwtMatang($skor_lewatMTng);
                $mutu_buahEst[$key][$key1]['janjang_kosong'] = $empty;
                $mutu_buahEst[$key][$key1]['persen_kosong'] = $skor_jjgKosong;
                $mutu_buahEst[$key][$key1]['skor_kosong'] = sidak_jjgKosong($skor_jjgKosong);
                $mutu_buahEst[$key][$key1]['vcut'] = $vcut;
                $mutu_buahEst[$key][$key1]['vcut_persen'] = $skor_vcut;
                $mutu_buahEst[$key][$key1]['vcut_skor'] = sidak_tangkaiP($skor_vcut);
                $mutu_buahEst[$key][$key1]['abnormal'] = $abr;

                $mutu_buahEst[$key][$key1]['rat_dmg'] = $rd;

                $mutu_buahEst[$key][$key1]['karung'] = $sum_kr;
                $mutu_buahEst[$key][$key1]['TPH'] = $total_kr;
                $mutu_buahEst[$key][$key1]['persen_krg'] = $per_kr;
                $mutu_buahEst[$key][$key1]['skor_kr'] = sidak_PengBRD($per_kr);
                $mutu_buahEst[$key][$key1]['All_skor'] = $allSkor;
                $mutu_buahEst[$key][$key1]['kategori'] = sidak_akhir($allSkor);
            }
        }


        // dd($mutu_buahEst);

        $mutu_bhWil = array();
        foreach ($mutu_buahEst as $key => $value) {
            $jjg_sample = 0;
            $tnpBRD = 0;
            $krgBRD = 0;
            $abr = 0;
            $skor_total = 0;
            $overripe = 0;
            $empty = 0;
            $vcut = 0;
            $rd = 0;
            $sum_kr = 0;
            $allSkor = 0;
            $dataBLok = 0;
            foreach ($value as $key1 => $value1) {
                // dd($value2);
                $jjg_sample += $value1['Jumlah_janjang'];
                $tnpBRD += $value1['tnp_brd'];
                $krgBRD += $value1['krg_brd'];
                $abr += $value1['abnormal'];
                $overripe += $value1['lewat_matang'];
                $empty += $value1['janjang_kosong'];
                $vcut += $value1['vcut'];

                $rd += $value1['rat_dmg'];

                $dataBLok += $value1['blok'];
                $sum_kr += $value1['karung'];
            }

            if ($sum_kr != 0) {
                $total_kr = round($sum_kr / $dataBLok, 2);
            } else {
                $total_kr = 0;
            }
            $per_kr = round($total_kr * 100, 2);
            $skor_total = round(($jjg_sample - $abr != 0 ? (($tnpBRD + $krgBRD) / ($jjg_sample - $abr)) * 100 : 0), 2);

            $skor_jjgMSk = round(($jjg_sample - $abr != 0 ? (($jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr)) / ($jjg_sample - $abr)) * 100 : 0), 2);

            $skor_lewatMTng = round(($jjg_sample - $abr != 0 ? ($overripe / ($jjg_sample - $abr)) * 100 : 0), 2);

            $skor_jjgKosong = round(($jjg_sample - $abr != 0 ? ($empty / ($jjg_sample - $abr)) * 100 : 0), 2);

            $skor_vcut = round(($jjg_sample != 0 ? ($vcut / $jjg_sample) * 100 : 0), 2);

            $allSkor = sidak_brdTotal($skor_total) +  sidak_matangSKOR($skor_jjgMSk) +  sidak_lwtMatang($skor_lewatMTng) + sidak_jjgKosong($skor_jjgKosong) + sidak_tangkaiP($skor_vcut) + sidak_PengBRD($per_kr);


            $mutu_bhWil[$key]['Jumlah_janjang'] = $jjg_sample;
            $mutu_bhWil[$key]['blok'] = $dataBLok;
            switch ($key) {
                case 0:
                    $mutu_bhWil[$key]['est'] = 'WIl-I';
                    $wil = 'WIL-I';
                    break;
                case 1:
                    $mutu_bhWil[$key]['est'] = 'WIl-II';
                    $wil = 'WIL-II';
                    break;
                case 2:
                    $mutu_bhWil[$key]['est'] = 'WIl-III';
                    $wil = 'WIL-III';
                    break;
                case 3:
                    $mutu_bhWil[$key]['est'] = 'Plasma1';
                    $wil = 'Plasma1';
                    break;
                default:
                    $mutu_bhWil[$key]['est'] = 'WIl' . $key;
                    $wil = '-';
                    break;
            }

            $wiles = $wil;

            $em = 'GM';

            $nama_em = '';

            // dd($key);
            foreach ($queryAsisten as $ast => $asisten) {
                if ($wiles === $asisten['est'] && $em === $asisten['afd']) {
                    $nama_em = $asisten['nama'];
                }
            }
            $mutu_bhWil[$key]['TEST'] = $wil;
            $mutu_bhWil[$key]['afd'] = $key1;
            $mutu_bhWil[$key]['nama_staff'] = $nama_em;
            $mutu_bhWil[$key]['tnp_brd'] = $tnpBRD;
            $mutu_bhWil[$key]['krg_brd'] = $krgBRD;
            $mutu_bhWil[$key]['persenTNP_brd'] = round(($jjg_sample - $abr != 0 ? ($tnpBRD / ($jjg_sample - $abr)) * 100 : 0), 2);
            $mutu_bhWil[$key]['persenKRG_brd'] = round(($jjg_sample - $abr != 0 ? ($krgBRD / ($jjg_sample - $abr)) * 100 : 0), 2);
            $mutu_bhWil[$key]['abnormal_persen'] = round(($jjg_sample != 0 ? ($abr / $jjg_sample) * 100 : 0), 2);
            $mutu_bhWil[$key]['rd_persen'] = round(($jjg_sample != 0 ? ($rd / $jjg_sample) * 100 : 0), 2);


            $mutu_bhWil[$key]['total_jjg'] = $tnpBRD + $krgBRD;
            $mutu_bhWil[$key]['persen_totalJjg'] = $skor_total;
            $mutu_bhWil[$key]['skor_total'] = sidak_brdTotal($skor_total);
            $mutu_bhWil[$key]['jjg_matang'] = $jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr);
            $mutu_bhWil[$key]['persen_jjgMtang'] = $skor_jjgMSk;
            $mutu_bhWil[$key]['skor_jjgMatang'] = sidak_matangSKOR($skor_jjgMSk);
            $mutu_bhWil[$key]['lewat_matang'] = $overripe;
            $mutu_bhWil[$key]['persen_lwtMtng'] =  $skor_lewatMTng;
            $mutu_bhWil[$key]['skor_lewatMTng'] = sidak_lwtMatang($skor_lewatMTng);
            $mutu_bhWil[$key]['janjang_kosong'] = $empty;
            $mutu_bhWil[$key]['persen_kosong'] = $skor_jjgKosong;
            $mutu_bhWil[$key]['skor_kosong'] = sidak_jjgKosong($skor_jjgKosong);
            $mutu_bhWil[$key]['vcut'] = $vcut;
            $mutu_bhWil[$key]['vcut_persen'] = $skor_vcut;
            $mutu_bhWil[$key]['vcut_skor'] = sidak_tangkaiP($skor_vcut);
            $mutu_bhWil[$key]['abnormal'] = $abr;

            $mutu_bhWil[$key]['rat_dmg'] = $rd;

            $mutu_bhWil[$key]['karung'] = $sum_kr;
            $mutu_bhWil[$key]['TPH'] = $total_kr;
            $mutu_bhWil[$key]['persen_krg'] = $per_kr;
            $mutu_bhWil[$key]['skor_kr'] = sidak_PengBRD($per_kr);
            $mutu_bhWil[$key]['All_skor'] = $allSkor;
            $mutu_bhWil[$key]['kategori'] = sidak_akhir($allSkor);
        }
        // dd($mutu_bhWil);

        foreach ($mutu_buah as $key1 => $estates)  if (is_array($estates)) {
            $sortedData = array();
            $sortedDataEst = array();

            foreach ($estates as $estateName => $data) {
                if (is_array($data)) {
                    // dd($data);
                    $sortedDataEst[] = array(
                        'key1' => $key1,
                        'estateName' => $estateName,
                        'data' => $data
                    );
                    foreach ($data as $key2 => $scores) {
                        if (is_array($scores)) {
                            // dd($scores);
                            $sortedData[] = array(
                                'estateName' => $estateName,
                                'key2' => $key2,
                                'scores' => $scores
                            );
                        }
                    }
                }
            }

            //mengurutkan untuk nilai afd
            usort($sortedData, function ($a, $b) {
                return $b['scores']['All_skor'] - $a['scores']['All_skor'];
            });
            // //mengurutkan untuk nilai estate
            // usort($sortedDataEst, function ($a, $b) {
            //     return $b['data']['TotalSkorEST'] - $a['data']['TotalSkorEST'];
            // });

            //menambahkan nilai rank ke dalam afd
            $rank = 1;
            foreach ($sortedData as $sortedEstate) {
                $mutu_buah[$key1][$sortedEstate['estateName']][$sortedEstate['key2']]['rankAFD'] = $rank;
                $rank++;
            }




            // unset($sortedData, $sortedDataEst);
            unset($sortedData);
        }

        // dd($mutu_buah);
        foreach ($mutubuah_est as $key1 => $estates)  if (is_array($estates)) {

            $sortedDataEst = array();

            foreach ($estates as $estateName => $data) {
                if (is_array($data)) {
                    // dd($data);
                    $sortedDataEst[] = array(
                        'key1' => $key1,
                        'estateName' => $estateName,
                        'data' => $data
                    );
                }
            }

            // //mengurutkan untuk nilai estate
            usort($sortedDataEst, function ($a, $b) {
                return $b['data']['All_skor'] - $a['data']['All_skor'];
            });

            // //menambahkan nilai rank ke dalam estate
            $rank = 1;
            foreach ($sortedDataEst as $sortedest) {
                $mutubuah_est[$key1][$sortedest['estateName']]['rankEST'] = $rank;
                $rank++;
            }
            // unset($sortedData, $sortedDataEst);
            unset($sortedData);
        }
        $mutuBuah_wil = array();
        foreach ($mutubuah_est as $key => $value) {
            $jjg_sample = 0;
            $tnpBRD = 0;
            $krgBRD = 0;
            $abr = 0;
            $skor_total = 0;
            $overripe = 0;
            $empty = 0;
            $vcut = 0;
            $rd = 0;
            $sum_kr = 0;
            $allSkor = 0;
            $dataBLok = 0;
            foreach ($value as $key1 => $value1) {
                // dd($value2);
                $jjg_sample += $value1['Jumlah_janjang'];
                $tnpBRD += $value1['tnp_brd'];
                $krgBRD += $value1['krg_brd'];
                $abr += $value1['abnormal'];
                $overripe += $value1['lewat_matang'];
                $empty += $value1['janjang_kosong'];
                $vcut += $value1['vcut'];

                $rd += $value1['rat_dmg'];

                $dataBLok += $value1['blok'];
                $sum_kr += $value1['karung'];
            }

            if ($sum_kr != 0) {
                $total_kr = round($sum_kr / $dataBLok, 2);
            } else {
                $total_kr = 0;
            }
            $per_kr = round($total_kr * 100, 2);
            $skor_total = round(($jjg_sample - $abr != 0 ? (($tnpBRD + $krgBRD) / ($jjg_sample - $abr)) * 100 : 0), 2);

            $skor_jjgMSk = round(($jjg_sample - $abr != 0 ? (($jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr)) / ($jjg_sample - $abr)) * 100 : 0), 2);

            $skor_lewatMTng = round(($jjg_sample - $abr != 0 ? ($overripe / ($jjg_sample - $abr)) * 100 : 0), 2);

            $skor_jjgKosong = round(($jjg_sample - $abr != 0 ? ($empty / ($jjg_sample - $abr)) * 100 : 0), 2);

            $skor_vcut = round(($jjg_sample != 0 ? ($vcut / $jjg_sample) * 100 : 0), 2);

            $allSkor = sidak_brdTotal($skor_total) +  sidak_matangSKOR($skor_jjgMSk) +  sidak_lwtMatang($skor_lewatMTng) + sidak_jjgKosong($skor_jjgKosong) + sidak_tangkaiP($skor_vcut) + sidak_PengBRD($per_kr);


            $mutuBuah_wil[$key]['Jumlah_janjang'] = $jjg_sample;
            $mutuBuah_wil[$key]['blok'] = $dataBLok;
            switch ($key) {
                case 0:
                    $mutuBuah_wil[$key]['est'] = 'WIl-I';
                    $wil = 'WIL-I';
                    break;
                case 1:
                    $mutuBuah_wil[$key]['est'] = 'WIl-II';
                    $wil = 'WIL-II';
                    break;
                case 2:
                    $mutuBuah_wil[$key]['est'] = 'WIl-III';
                    $wil = 'WIL-III';
                    break;
                case 3:
                    $mutuBuah_wil[$key]['est'] = 'Plasma1';
                    $wil = 'Plasma1';
                    break;
                default:
                    $mutuBuah_wil[$key]['est'] = 'WIl' . $key;
                    $wil = '-';
                    break;
            }

            $wiles = $wil;

            $em = 'GM';

            $nama_em = '';

            // dd($key);
            foreach ($queryAsisten as $ast => $asisten) {
                if ($wiles === $asisten['est'] && $em === $asisten['afd']) {
                    $nama_em = $asisten['nama'];
                }
            }
            if ($jjg_sample == 0 && $tnpBRD == 0 &&   $krgBRD == 0 && $abr == 0 && $overripe == 0 && $empty == 0 &&  $vcut == 0 &&  $rd == 0 && $sum_kr == 0) {
                $mutuBuah_wil[$key]['check_arr'] = 'kosong';
                $mutuBuah_wil[$key]['All_skor'] = 0;
            } else {
                $mutuBuah_wil[$key]['check_arr'] = 'ada';
                $mutuBuah_wil[$key]['All_skor'] = $allSkor;
            }
            $mutuBuah_wil[$key]['TEST'] = $wil;
            $mutuBuah_wil[$key]['afd'] = $key1;
            $mutuBuah_wil[$key]['nama_staff'] = $nama_em;
            $mutuBuah_wil[$key]['tnp_brd'] = $tnpBRD;
            $mutuBuah_wil[$key]['krg_brd'] = $krgBRD;
            $mutuBuah_wil[$key]['persenTNP_brd'] = round(($jjg_sample - $abr != 0 ? ($tnpBRD / ($jjg_sample - $abr)) * 100 : 0), 2);
            $mutuBuah_wil[$key]['persenKRG_brd'] = round(($jjg_sample - $abr != 0 ? ($krgBRD / ($jjg_sample - $abr)) * 100 : 0), 2);
            $mutuBuah_wil[$key]['abnormal_persen'] = round(($jjg_sample != 0 ? ($abr / $jjg_sample) * 100 : 0), 2);
            $mutuBuah_wil[$key]['rd_persen'] = round(($jjg_sample != 0 ? ($rd / $jjg_sample) * 100 : 0), 2);


            $mutuBuah_wil[$key]['total_jjg'] = $tnpBRD + $krgBRD;
            $mutuBuah_wil[$key]['persen_totalJjg'] = $skor_total;
            $mutuBuah_wil[$key]['skor_total'] = sidak_brdTotal($skor_total);
            $mutuBuah_wil[$key]['jjg_matang'] = $jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr);
            $mutuBuah_wil[$key]['persen_jjgMtang'] = $skor_jjgMSk;
            $mutuBuah_wil[$key]['skor_jjgMatang'] = sidak_matangSKOR($skor_jjgMSk);
            $mutuBuah_wil[$key]['lewat_matang'] = $overripe;
            $mutuBuah_wil[$key]['persen_lwtMtng'] =  $skor_lewatMTng;
            $mutuBuah_wil[$key]['skor_lewatMTng'] = sidak_lwtMatang($skor_lewatMTng);
            $mutuBuah_wil[$key]['janjang_kosong'] = $empty;
            $mutuBuah_wil[$key]['persen_kosong'] = $skor_jjgKosong;
            $mutuBuah_wil[$key]['skor_kosong'] = sidak_jjgKosong($skor_jjgKosong);
            $mutuBuah_wil[$key]['vcut'] = $vcut;
            $mutuBuah_wil[$key]['vcut_persen'] = $skor_vcut;
            $mutuBuah_wil[$key]['vcut_skor'] = sidak_tangkaiP($skor_vcut);
            $mutuBuah_wil[$key]['abnormal'] = $abr;

            $mutuBuah_wil[$key]['rat_dmg'] = $rd;

            $mutuBuah_wil[$key]['karung'] = $sum_kr;
            $mutuBuah_wil[$key]['TPH'] = $total_kr;
            $mutuBuah_wil[$key]['persen_krg'] = $per_kr;
            $mutuBuah_wil[$key]['skor_kr'] = sidak_PengBRD($per_kr);
            // $mutuBuah_wil[$key]['All_skor'] = $allSkor;
            $mutuBuah_wil[$key]['kategori'] = sidak_akhir($allSkor);
        }

        // dd($mutuBuah_wil);
        $sortedDataEst = array();
        foreach ($mutuBuah_wil as $key1 => $estates) {
            if (is_array($estates)) {
                $sortedDataEst[] = array(
                    'key1' => $key1,
                    'data' => $estates
                );
            }
        }

        usort($sortedDataEst, function ($a, $b) {
            return $b['data']['All_skor'] - $a['data']['All_skor'];
        });

        $rank = 1;
        foreach ($sortedDataEst as $sortedest) {
            $estateKey = $sortedest['key1'];
            $mutuBuah_wil[$estateKey]['rankWil'] = $rank;
            $rank++;
        }

        unset($sortedDataEst);

        $defaultMTbh = array();


        $regional_array = [
            'Regional' => $mutuBuah_wil
        ];
        // dd($regional_array);
        $regArr = array();
        foreach ($regional_array as $key => $value) {
            $jjg_sampleEST = 0;
            $tnpBRDEST = 0;
            $krgBRDEST = 0;
            $abrEST = 0;
            $overripeEST = 0;
            $emptyEST = 0;
            $vcutEST = 0;
            $rdEST = 0;
            $sum_krEST = 0;
            $blokEST = 0;
            foreach ($value as $key1 => $value1) {
                // dd($value1);
                $jjg_sampleEST += $value1['Jumlah_janjang'];
                $blokEST += $value1['blok'];
                $tnpBRDEST +=    $value1['tnp_brd'];
                $krgBRDEST +=    $value1['krg_brd'];
                $abrEST +=    $value1['abnormal'];
                $overripeEST +=    $value1['lewat_matang'];
                $emptyEST +=    $value1['janjang_kosong'];
                $vcutEST +=    $value1['vcut'];
                $rdEST +=    $value1['rat_dmg'];
                $sum_krEST +=    $value1['karung'];
                $afds = $value1['afd'];
            }
            if ($sum_krEST != 0) {
                $total_krEST = round($sum_krEST / $blokEST, 2);
            } else {
                $total_krEST = 0;
            }
            $per_krEST = round($total_krEST * 100, 2);
            $skor_totalEST = ($jjg_sampleEST - $abrEST) !== 0 ? round((($tnpBRDEST + $krgBRDEST) / ($jjg_sampleEST - $abrEST)) * 100, 2) : 0;
            $skot_jjgmskEST = ($jjg_sampleEST - $abrEST) !== 0 ? round(($jjg_sampleEST - ($tnpBRDEST + $krgBRDEST + $overripeEST + $emptyEST)) / ($jjg_sampleEST - $abrEST) * 100, 2) : 0;
            $skor_lewatmatangEST = ($jjg_sampleEST - $abrEST) !== 0 ? round(($overripeEST / ($jjg_sampleEST - $abrEST)) * 100, 2) : 0;
            $skor_jjgKosongEST = ($jjg_sampleEST - $abrEST) !== 0 ? round(($emptyEST / ($jjg_sampleEST - $abrEST)) * 100, 2) : 0;
            $skor_vcutEST = $jjg_sampleEST !== 0 ? round(($vcutEST / $jjg_sampleEST) * 100, 2) : 0;

            $allSkorEST = sidak_brdTotal($skor_totalEST) +  sidak_matangSKOR($skot_jjgmskEST) +  sidak_lwtMatang($skor_lewatmatangEST) + sidak_jjgKosong($skor_jjgKosongEST) + sidak_tangkaiP($skor_vcutEST) + sidak_PengBRD($per_krEST);


            $em = 'RH';
            $estkey = '';
            $estkey2 = '';
            $regArr[$key]['Jumlah_janjang'] = $jjg_sampleEST;
            $regArr[$key]['blok'] = $blokEST;
            $regArr[$key]['kode'] = $afds;

            if ($afds == 'Plasma1') {
                $estkey = 'REG-I';
                $estkey2 = 'RH-I';
            } else if ($afds == 'SCE') {
                $estkey = 'REG-II';
                $estkey2 = 'RH-II';
            } else {
                $estkey = 'REG-III';
                $estkey2 = 'RH-III';
            }
            $regArr[$key]['regional'] = $estkey;
            $regArr[$key]['jabatan'] = $estkey2;
            foreach ($queryAsisten as $ast => $asisten) {
                if ($estkey === $asisten['est'] && $em === $asisten['afd']) {
                    $regArr[$key]['nama_asisten'] = $asisten['nama'];
                }
            }
            $regArr[$key]['tnp_brd'] = $tnpBRDEST;
            $regArr[$key]['krg_brd'] = $krgBRDEST;
            $regArr[$key]['persenTNP_brd'] = ($jjg_sampleEST - $abrEST) !== 0 ? round(($krgBRDEST / ($jjg_sampleEST - $abrEST)) * 100, 2) : 0;
            $regArr[$key]['persenKRG_brd'] = ($jjg_sampleEST - $abrEST) !== 0 ? round(($krgBRDEST / ($jjg_sampleEST - $abrEST)) * 100, 2) : 0;
            $regArr[$key]['total_jjg'] = $tnpBRDEST + $krgBRDEST;
            $regArr[$key]['persen_totalJjg'] = $skor_totalEST;
            $regArr[$key]['skor_totalEST'] = sidak_brdTotal($skor_totalEST);
            $regArr[$key]['jjg_matang'] = $jjg_sampleEST - ($tnpBRDEST + $krgBRDEST + $overripeEST + $emptyEST + $abrEST);
            $regArr[$key]['persen_jjgMtang'] = $skot_jjgmskEST;
            $regArr[$key]['skor_jjgMatang'] = sidak_matangSKOR($skot_jjgmskEST);
            $regArr[$key]['lewat_matang'] = $overripeEST;
            $regArr[$key]['persen_lwtMtng'] =  $skor_lewatmatangEST;
            $regArr[$key]['skor_lewatmatangEST'] = sidak_lwtMatang($skor_lewatmatangEST);
            $regArr[$key]['janjang_kosong'] = $emptyEST;
            $regArr[$key]['persen_kosong'] = $skor_jjgKosongEST;
            $regArr[$key]['skor_kosong'] = sidak_jjgKosong($skor_jjgKosongEST);
            $regArr[$key]['vcut'] = $vcutEST;
            $regArr[$key]['vcut_persen'] = $skor_vcutEST;
            $regArr[$key]['vcut_skor'] = sidak_tangkaiP($skor_vcutEST);
            $regArr[$key]['abnormal'] = $abrEST;
            $regArr[$key]['abnormal_persen'] = $jjg_sampleEST !== 0 ? round(($abrEST / $jjg_sampleEST) * 100, 2) : 0;
            $regArr[$key]['rat_dmg'] = $rdEST;
            $regArr[$key]['rd_persen'] = $jjg_sampleEST !== 0 ? round(($rdEST / $jjg_sampleEST) * 100, 2) : 0;
            $regArr[$key]['karung'] = $sum_krEST;
            $regArr[$key]['TPH'] = $total_krEST;
            $regArr[$key]['persen_krg'] = $per_krEST;
            $regArr[$key]['skor_kr'] = sidak_PengBRD($per_krEST);
            $regArr[$key]['all_skorYear'] = $allSkorEST;
            $regArr[$key]['kategori'] = sidak_akhir($allSkorEST);
        }
        // dd($regArr);

        //bagian chart untuk perweek
        $chartMatang = [];

        foreach ($mutubuah_est as $outer_array) {
            foreach ($outer_array as $key => $inner_array) {
                if (isset($inner_array['persen_jjgMtang'])) {
                    $chartMatang[$key] = $inner_array['persen_jjgMtang'];
                }
            }
        }

        $chartMentah = [];

        foreach ($mutubuah_est as $outer_array) {
            foreach ($outer_array as $key => $inner_array) {
                if (isset($inner_array['persen_jjgmentah'])) {
                    $chartMentah[$key] = $inner_array['persen_jjgmentah'];
                }
            }
        }
        $chartLwtMatang = [];

        foreach ($mutubuah_est as $outer_array) {
            foreach ($outer_array as $key => $inner_array) {
                if (isset($inner_array['persen_lwtMtng'])) {
                    $chartLwtMatang[$key] = $inner_array['persen_lwtMtng'];
                }
            }
        }
        $chartJjgKosong = [];

        foreach ($mutubuah_est as $outer_array) {
            foreach ($outer_array as $key => $inner_array) {
                if (isset($inner_array['persen_kosong'])) {
                    $chartJjgKosong[$key] = $inner_array['persen_kosong'];
                }
            }
        }
        $chartVcut = [];

        foreach ($mutubuah_est as $outer_array) {
            foreach ($outer_array as $key => $inner_array) {
                if (isset($inner_array['vcut_persen'])) {
                    $chartVcut[$key] = $inner_array['vcut_persen'];
                }
            }
        }
        $chartKarung = [];

        foreach ($mutubuah_est as $outer_array) {
            foreach ($outer_array as $key => $inner_array) {
                if (isset($inner_array['TPH'])) {
                    $chartKarung[$key] = $inner_array['TPH'];
                }
            }
        }


        $result = $mutu_bhWil;
        $persen_jjgMtang_values = array();
        $persen_jjgmentah_values = array();
        $persen_lwtMtng_values = array();
        $persen_kosong_values = array();
        $vcut_persen_values = array();
        $TPH_values = array();
        // dd($result);
        foreach ($result as $estate => $estateData) {


            $persen_jjgMtang_values[$estate] = $estateData['persen_jjgMtang'] ?? 0;
            $persen_jjgmentah_values[$estate] = $estateData['persen_jjgmentah'] ?? 0;
            $persen_lwtMtng_values[$estate] = $estateData['persen_lwtMtng'] ?? 0;
            $persen_kosong_values[$estate] = $estateData['persen_kosong'] ?? 0;
            $vcut_persen_values[$estate] = $estateData['vcut_persen'] ?? 0;
            $TPH_values[$estate] = $estateData['TPH'] ?? 0;
        }
        // dd($persen_jjgMtang_values, $persen_lwtMtng_values);

        $regional = array();
        foreach ($optionReg as $key => $value) {
            $value['nama'] = str_replace('Regional', 'Reg-', $value['nama']);
            $value['nama'] = str_replace(' ', '', $value['nama']);
            $value['nama'] = strtoupper($value['nama']);
            $regional[$key] = $value;
            $regional[$key]['jabatan'] = 'RH-' . substr($value['nama'], strpos($value['nama'], 'Reg-') + strlen('Reg-'));
        }

        foreach ($regional as $key => $value) {
            $regional[$key]['nama_rh'] = '-';
            foreach ($queryAsisten as $ast => $asisten) {
                // dd($value['nama']);
                if ($asisten['est'] == $value['nama'] && $asisten['afd'] == 'RH') {
                    $regional[$key]['nama_rh'] = $asisten['nama'];
                    break; // exit the inner loop since a match is found
                }
            }
        }



        updateKeyRecursive($mutu_buah, "KTE4", "KTE");


        // Change key "KTE4" to "KTE"
        updateKeyRecursive3($mutubuah_est[0], "KTE4", "KTE");
        $estev2 = updateKeyRecursive2($estev2);


        $arrView = array();

        $arrView['listregion'] =  $estev2;
        $arrView['mutu_buah'] =  $mutu_buah;
        $arrView['mutubuah_est'] =  $mutubuah_est;
        $arrView['mutuBuah_wil'] =  $mutuBuah_wil;
        $arrView['regional'] =  $regArr;
        $arrView['queryAsisten'] =  $queryAsisten;
        $arrView['chart_matang'] =  $chartMatang;
        $arrView['chart_mentah'] =  $chartMentah;
        $arrView['chart_lewatmatang'] =  $chartLwtMatang;
        $arrView['chart_janjangkosong'] =  $chartJjgKosong;
        $arrView['chart_vcut'] =  $chartVcut;
        $arrView['chart_karung'] =  $chartKarung;

        $arrView['chart_matangwil'] =  $persen_jjgMtang_values;
        $arrView['chart_mentahwil'] =  $persen_jjgmentah_values;
        $arrView['chart_lewatmatangwil'] =  $persen_lwtMtng_values;
        $arrView['chart_janjangkosongwil'] =  $persen_kosong_values;
        $arrView['chart_vcutwil'] =  $vcut_persen_values;
        $arrView['chart_karungwil'] =  $TPH_values;
        $arrView['regionaltab'] =  $regional;


        echo json_encode($arrView); //di decode ke dalam bentuk json dalam vaiavel arrview yang dapat menampung banyak isi array
        exit();
    }

    public function getYearData(Request $request)
    {
        $reg = $request->input('reg');
        $year = $request->input('tahun');

        $queryEste = DB::connection('mysql2')->table('estate')
            ->select('estate.*')
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->where('wil.regional', $reg)
            ->get();
        $queryEste = json_decode($queryEste, true);

        $queryAfd = DB::connection('mysql2')->table('afdeling')
            ->select(
                'afdeling.id',
                'afdeling.nama',
                'estate.est'
            ) //buat mengambil data di estate db dan willayah db
            ->join('estate', 'estate.id', '=', 'afdeling.estate') //kemudian di join untuk mengambil est perwilayah
            ->get();
        $queryAfd = json_decode($queryAfd, true);

        $queryMTbuah = DB::connection('mysql2')->table('sidak_mutu_buah')
            ->select(
                "sidak_mutu_buah.*",
                DB::raw('DATE_FORMAT(sidak_mutu_buah.datetime, "%M") as bulan'),
                DB::raw('DATE_FORMAT(sidak_mutu_buah.datetime, "%Y") as tahun')
            )
            // ->whereBetween('mutu_buah.datetime', [$startDate, $endDate])
            ->whereYear('datetime', $year)
            // ->whereBetween('sidak_mutu_buah.datetime', ['2023-04-03', '2023-04-09'])
            ->get();
        $queryMTbuah = $queryMTbuah->groupBy(['estate', 'afdeling']);
        $queryMTbuah = json_decode($queryMTbuah, true);
        $databulananBuah = array();
        foreach ($queryMTbuah as $key => $value) {
            foreach ($value as $key2 => $value2) {
                foreach ($value2 as $key3 => $value3) {

                    $databulananBuah[$key][$key2][$key3] = $value3;
                }
            }
        }

        $defPerbulanWil = array();

        foreach ($queryEste as $key2 => $value2) {
            foreach ($queryAfd as $key3 => $value3) {
                if ($value2['est'] == $value3['est']) {
                    $defPerbulanWil[$value2['est']][$value3['nama']] = 0;
                }
            }
        }



        foreach ($defPerbulanWil as $estateKey => $afdelingArray) {
            foreach ($afdelingArray as $afdelingKey => $afdelingValue) {
                if (isset($databulananBuah[$estateKey]) && isset($databulananBuah[$estateKey][$afdelingKey])) {
                    $defPerbulanWil[$estateKey][$afdelingKey] = $databulananBuah[$estateKey][$afdelingKey];
                }
            }
        }
        function tesko($values)
        {
            foreach ($values as $value) {
                if ($value > 0) {
                    return true;
                }
            }
            return false;
        }
        // dd($defPerbulanWil);
        $sidak_buah = array();

        foreach ($defPerbulanWil as $key => $value) {
            $totalJJG = 0;
            $totaltnpBRD = 0;
            $totalkrgBRD = 0;
            $totalabr = 0;
            $TotPersenTNP = 0;
            $TotPersenKRG = 0;
            $totJJG = 0;
            $totPersenTOtaljjg = 0;
            $totSkor_total = 0;
            $totoverripe = 0;
            $totempty = 0;
            $totJJG_matang = 0;
            $totPer_jjgMtng = 0;
            $totPer_over = 0;
            $totSkor_Over = 0;
            $totPer_Empty = 0;
            $totSkor_Empty = 0;
            $totVcut = 0;
            $totPer_vcut =  0;
            $totSkor_Vcut =  0;
            $totPer_abr =  0;
            $totRD = 0;
            $totPer_rd = 0;
            $totBlok = 0;
            $totKR = 0;
            $tot_krS = 0;
            $totPer_kr = 0;
            $totSkor_kr = 0;
            $totALlskor = 0;
            $totKategor = 0;
            foreach ($value as $key1 => $value1) {
                $totSkor_jjgMtng = 0;
                if (is_array($value1)) {
                    $jjg_sample = 0;
                    $tnpBRD = 0;
                    $krgBRD = 0;
                    $abr = 0;
                    $skor_total = 0;
                    $overripe = 0;
                    $empty = 0;
                    $vcut = 0;
                    $rd = 0;
                    $sum_kr = 0;
                    $allSkor = 0;
                    $combination_counts = array();

                    foreach ($value1 as $key2 => $value2) {
                        $combination = $value2['blok'] . ' ' . $value2['estate'] . ' ' . $value2['afdeling'] . ' ' . $value2['tph_baris'];
                        if (!isset($combination_counts[$combination])) {
                            $combination_counts[$combination] = 0;
                        }
                        $jjg_sample += $value2['jumlah_jjg'];
                        $tnpBRD += $value2['bmt'];
                        $krgBRD += $value2['bmk'];
                        $abr += $value2['abnormal'];
                        $overripe += $value2['overripe'];
                        $empty += $value2['empty_bunch'];
                        $vcut += $value2['vcut'];
                        $rd += $value2['rd'];
                        $sum_kr += $value2['alas_br'];
                    }
                    $dataBLok = count($combination_counts);
                    if ($sum_kr != 0) {
                        $total_kr = round($sum_kr / $dataBLok, 2);
                    } else {
                        $total_kr = 0;
                    }
                    $per_kr = round($total_kr * 100, 2);
                    $skor_total = round((($tnpBRD + $krgBRD) / ($jjg_sample - $abr)) * 100, 2);
                    $skor_jjgMSk = round(($jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr)) / ($jjg_sample - $abr) * 100, 2);
                    $skor_lewatMTng =  round(($overripe / ($jjg_sample - $abr)) * 100, 2);
                    $skor_jjgKosong =  round(($empty / ($jjg_sample - $abr)) * 100, 2);
                    $skor_vcut =   round(($vcut / $jjg_sample) * 100, 2);
                    $allSkor = sidak_brdTotal($skor_total) +  sidak_matangSKOR($skor_jjgMSk) +  sidak_lwtMatang($skor_lewatMTng) + sidak_jjgKosong($skor_jjgKosong) + sidak_tangkaiP($skor_vcut) + sidak_PengBRD($per_kr);

                    $sidak_buah[$key][$key1]['reg'] = 'REG-I';
                    $sidak_buah[$key][$key1]['pt'] = 'SSMS';
                    $sidak_buah[$key][$key1]['Jumlah_janjang'] = $jjg_sample;
                    $sidak_buah[$key][$key1]['blok'] = $dataBLok;
                    $sidak_buah[$key][$key1]['est'] = $key;
                    $sidak_buah[$key][$key1]['afd'] = $key1;
                    $sidak_buah[$key][$key1]['nama_staff'] = '-';
                    $sidak_buah[$key][$key1]['tnp_brd'] = $tnpBRD;
                    $sidak_buah[$key][$key1]['krg_brd'] = $krgBRD;
                    $sidak_buah[$key][$key1]['persenTNP_brd'] = round(($tnpBRD / ($jjg_sample - $abr)) * 100, 2);
                    $sidak_buah[$key][$key1]['persenKRG_brd'] = round(($krgBRD / ($jjg_sample - $abr)) * 100, 2);
                    $sidak_buah[$key][$key1]['total_jjg'] = $tnpBRD + $krgBRD;
                    $sidak_buah[$key][$key1]['persen_totalJjg'] = $skor_total;
                    $sidak_buah[$key][$key1]['skor_total'] = sidak_brdTotal($skor_total);
                    $sidak_buah[$key][$key1]['jjg_matang'] = $jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr);
                    $sidak_buah[$key][$key1]['persen_jjgMtang'] = $skor_jjgMSk;
                    $sidak_buah[$key][$key1]['skor_jjgMatang'] = sidak_matangSKOR($skor_jjgMSk);
                    $sidak_buah[$key][$key1]['lewat_matang'] = $overripe;
                    $sidak_buah[$key][$key1]['persen_lwtMtng'] =  $skor_lewatMTng;
                    $sidak_buah[$key][$key1]['skor_lewatMTng'] = sidak_lwtMatang($skor_lewatMTng);
                    $sidak_buah[$key][$key1]['janjang_kosong'] = $empty;
                    $sidak_buah[$key][$key1]['persen_kosong'] = $skor_jjgKosong;
                    $sidak_buah[$key][$key1]['skor_kosong'] = sidak_jjgKosong($skor_jjgKosong);
                    $sidak_buah[$key][$key1]['vcut'] = $vcut;
                    $sidak_buah[$key][$key1]['vcut_persen'] = $skor_vcut;
                    $sidak_buah[$key][$key1]['vcut_skor'] = sidak_tangkaiP($skor_vcut);
                    $sidak_buah[$key][$key1]['abnormal'] = $abr;
                    $sidak_buah[$key][$key1]['abnormal_persen'] = round(($abr / $jjg_sample) * 100, 2);
                    $sidak_buah[$key][$key1]['rat_dmg'] = $rd;
                    $sidak_buah[$key][$key1]['rd_persen'] = round(($rd / $jjg_sample) * 100, 2);
                    $sidak_buah[$key][$key1]['TPH'] = $total_kr;
                    $sidak_buah[$key][$key1]['persen_krg'] = $per_kr;
                    $sidak_buah[$key][$key1]['skor_kr'] = sidak_PengBRD($per_kr);
                    $sidak_buah[$key][$key1]['All_skor'] = $allSkor;
                    $sidak_buah[$key][$key1]['kategori'] = sidak_akhir($allSkor);

                    $totalJJG += $jjg_sample;
                    $totaltnpBRD += $tnpBRD;
                    $totalkrgBRD += $krgBRD;
                    $totalabr += $abr;
                    $TotPersenTNP = round(($totaltnpBRD / ($totalJJG - $totalabr)) * 100, 2);
                    $TotPersenKRG = round(($totalkrgBRD / ($totalJJG - $totalabr)) * 100, 2);
                    $totJJG = $totaltnpBRD + $totalkrgBRD;
                    $totPersenTOtaljjg = round((($totaltnpBRD + $totalkrgBRD) / ($totalJJG - $totalabr)) * 100, 2);
                    $totSkor_total = sidak_brdTotal($totPersenTOtaljjg);
                    $totoverripe += $overripe;
                    $totempty += $empty;
                    $totJJG_matang = $totalJJG - ($totaltnpBRD + $totalkrgBRD + $totoverripe + $totempty + $totalabr);
                    $totPer_jjgMtng = round($totJJG_matang / ($totalJJG - $totalabr) * 100, 2);

                    $totSkor_jjgMtng = sidak_matangSKOR($totPer_jjgMtng);
                    $totPer_over = round(($totoverripe / ($totalJJG - $totalabr)) * 100, 2);
                    $totSkor_Over = sidak_lwtMatang($totPer_over);
                    $totPer_Empty = round(($totempty / ($totalJJG - $totalabr)) * 100, 2);
                    $totSkor_Empty = sidak_jjgKosong($totPer_Empty);
                    $totVcut += $vcut;
                    $totPer_vcut =   round(($totVcut / $totalJJG) * 100, 2);
                    $totSkor_Vcut =  sidak_tangkaiP($totPer_vcut);
                    $totPer_abr =  round(($totalabr / $totalJJG) * 100, 2);
                    $totRD += $rd;
                    $totPer_rd = round(($totRD / $totalJJG) * 100, 2);
                    $totBlok += $dataBLok;
                    $totKR += $sum_kr;
                    if ($totKR != 0) {
                        $tot_krS = round($totKR / $totBlok, 2);
                    } else {
                        $tot_krS = 0;
                    }
                    $totPer_kr = round($tot_krS * 100, 2);
                    $totSkor_kr = sidak_PengBRD($totPer_kr);
                    $totALlskor = sidak_brdTotal($totPersenTOtaljjg) + sidak_matangSKOR($totPer_jjgMtng) + sidak_lwtMatang($totPer_over) + sidak_jjgKosong($totPer_Empty) + sidak_tangkaiP($totPer_vcut) + sidak_PengBRD($totPer_kr);

                    $totKategor = sidak_akhir($totALlskor);
                }

                $totalValues = [

                    'reg' => '',
                    'pt' => '',
                    'nama_staff' => '',
                    'Jumlah_janjang' => $totalJJG,
                    'est' => '',
                    'afd' => '',
                    'tnp_brd' => $totaltnpBRD,
                    'krg_brd' => $totalkrgBRD,
                    'persenTNP_brd' => $TotPersenTNP,
                    'persenKRG_brd' => $TotPersenKRG,
                    'total_jjg' => $totJJG,
                    'persen_totalJjg' => $totPersenTOtaljjg,
                    'skor_total' => $totSkor_total,
                    'jjg_matang' => $totJJG_matang,
                    'persen_jjgMtang' => $totPer_jjgMtng,
                    'skor_jjgMatang' => $totSkor_jjgMtng,
                    'lewat_matang' => $totoverripe,
                    'persen_lwtMtng' => $totPer_over,
                    'skor_lewatMTng' => $totSkor_Over,
                    'janjang_kosong' => $totempty,
                    'persen_kosong' => $totPer_Empty,
                    'skor_kosong' => $totSkor_Empty,
                    'vcut' => $totVcut,
                    'vcut_persen' => $totPer_vcut,
                    'vcut_skor' => $totSkor_Vcut,
                    'abnormal' => $totalabr,
                    'abnormal_persen' => $totPer_abr,
                    'rat_dmg' => $totRD,
                    'rd_persen' => $totPer_rd,
                    'TPH' => $tot_krS,
                    'persen_krg' => $totPer_kr,
                    'skor_kr' => $totSkor_kr,
                    'All_skor' => $totALlskor,
                    'kategori' => $totKategor,
                    // Add more variables here
                ];

                if (tesko($totalValues)) {
                    $sidak_buah[$key][$key] = $totalValues;
                }
            }
        }

        $new_sidak_buah = array();

        foreach ($sidak_buah as $key => $value) {
            $new_subarray = array();

            foreach ($value as $sub_key => $sub_value) {
                if ($sub_key != $key) {
                    $new_subarray[$sub_key] = $sub_value;
                }
            }

            if (isset($value[$key])) {
                $new_subarray[$key] = $value[$key];
            }

            $new_sidak_buah[$key] = $new_subarray;
        }

        $sidak_buah = $new_sidak_buah;


        // dd($sidak_buah);
        $arrView = array();

        $arrView['data_sidak'] =  $sidak_buah;

        echo json_encode($arrView); //di decode ke dalam bentuk json dalam vaiavel arrview yang dapat menampung banyak isi array
        exit();
    }

    public function findingIsueTahun(Request $request)
    {
        $reg = $request->input('reg');
        $year = $request->input('tahun');

        ///untuk perhitungan latihan nnti di hapus kalau sudah
        $queryAsisten = DB::connection('mysql2')->table('asisten_qc')
            ->select('asisten_qc.*')
            ->get();

        $queryAsisten = json_decode($queryAsisten, true);

        // dd($startDate, $endDate);
        $queryEste = DB::connection('mysql2')->table('estate')
            ->select('estate.*')
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->where('wil.regional', $reg)
            ->whereNotIn('estate.est', ['SRE', 'LDE', 'SKE', 'CWS1'])
            ->pluck('est');
        $queryEste = json_decode($queryEste, true);
        // $estatex = DB::connection('mysql2')->table('estate')
        //     ->select('estate.*')
        //     ->join('wil', 'wil.id', '=', 'estate.wil')
        //     ->where('wil.regional', $request->get('regional'))
        //     ->whereNotIn('estate.est', ['CWS1', 'CWS2', 'CWS3'])
        //     ->where('estate.est', '!=', 'PLASMA')


        // dd($queryEste);
        $queryAfd = DB::connection('mysql2')->table('afdeling')
            ->select(
                'afdeling.id',
                'afdeling.nama',
                'estate.est'
            ) //buat mengambil data di estate db dan willayah db
            ->join('estate', 'estate.id', '=', 'afdeling.estate') //kemudian di join untuk mengambil est perwilayah
            ->get();
        $queryAfd = json_decode($queryAfd, true);

        $queryMTbuah = DB::connection('mysql2')->table('sidak_mutu_buah')
            ->select(
                "sidak_mutu_buah.*",
                DB::raw('DATE_FORMAT(sidak_mutu_buah.datetime, "%M") as bulan'),
                DB::raw('DATE_FORMAT(sidak_mutu_buah.datetime, "%Y") as tahun')
            )
            // ->whereBetween('sidak_mutu_buah.datetime', [$startDate, $endDate])
            ->whereIn('estate', $queryEste)
            ->whereYear('sidak_mutu_buah.datetime', $year)
            ->get();


        $databuah = $queryMTbuah->groupBy(function ($item) {
            return $item->estate . ' ' . $item->afdeling . ' ' . $item->blok;
        });
        $databuah = json_decode($databuah, true);

        // dd($databuah);

        $mutu_all = [];

        foreach ($databuah as $key => $items) {
            if (!array_key_exists($key, $mutu_all)) {
                $mutu_all[$key] = [


                    'mutu_buah' => [],
                ];
            }
            $visit_count = [];


            foreach ($items as $item) {
                $date = substr($item['datetime'], 0, 10);
                $identifier = $item['blok'] . '_' . $item['tph_baris'];

                if (!array_key_exists($identifier, $visit_count)) {
                    $visit_count[$identifier] = [];
                }

                // If the item's date is not in the visit_count array, add it and assign a visit number
                if (!in_array($date, $visit_count[$identifier])) {
                    $visit_count[$identifier][] = $date;
                    // Sort the dates in ascending order
                    sort($visit_count[$identifier]);
                }

                $item['visit'] = array_search($date, $visit_count[$identifier]) + 1;
                if (!empty($item['foto_temuan']) ||  !empty($item['komentar'])) {
                    $mutu_all[$key]['mutu_buah'][] = $item;
                }
            }
        }
        $groupedArray = array();

        foreach ($mutu_all as $key => $value) {
            $groupKey = substr($key, 0, 3);

            if (!array_key_exists($groupKey, $groupedArray)) {
                $groupedArray[$groupKey] = array();
            }

            $groupedArray[$groupKey][$key] = $value;
        }

        $filteredGroupedArray = array_map(function ($data) {
            return array_filter($data, function ($item) {
                return !empty($item['mutu_buah']);
            });
        }, $groupedArray);

        // Remove empty subarrays after filtering
        $filteredGroupedArray = array_filter($filteredGroupedArray);

        // dd($filteredGroupedArray);

        // dd($groupedArray);
        $item_counts = [];

        foreach ($groupedArray as $key => $value) {
            $count = 0;
            $total_foto_temuan = 0;
            $total_followUP = 0;
            $total_visits = 0;
            $highest_visit = 1; // Add this line to keep track of the highest number of visits

            foreach ($value as $sub_key => $sub_value) {
                foreach ($sub_value as $key2 => $value2) {
                    $count += count($value2);
                    foreach ($value2 as $item) {
                        if (!empty($item["foto_temuan1"]) || !empty($item["foto_temuan"])) {
                            $total_foto_temuan++;
                        }
                        if (!empty($item["foto_fu1"]) || !empty($item["foto_fu"])) {
                            $total_followUP++;
                        }
                        // Increment the total_visits counter for each visit found
                        if (!empty($item["visit"])) {
                            $total_visits++;

                            // Update the highest_visit variable if a higher visit number is found
                            if ($item["visit"] > $highest_visit) {
                                $highest_visit = $item["visit"];
                            }
                        }
                    }
                }
            }
            $item_counts[$key]['est'] = $key;
            // $item_counts[$key]['total_temuan'] = $count; // Renamed this key to 'total_values'
            $item_counts[$key]['foto_temuan'] = $total_foto_temuan;
            // $item_counts[$key]['followUp'] = '-';
            // $item_counts[$key]['tuntas'] = '-';
            // $item_counts[$key]['no_tuntas'] = $total_foto_temuan - $total_followUP;
            // $item_counts[$key]['perTuntas'] = ($total_foto_temuan - $total_followUP == 0) ? 0 : round($total_followUP / $total_foto_temuan * 100, 2);
            // $item_counts[$key]['perNoTuntas'] = ($total_foto_temuan - $total_followUP == 0) ? 0 : round(($total_foto_temuan - $total_followUP) / $total_foto_temuan * 100, 2);
            $item_counts[$key]['visit'] = $highest_visit;
        }


        $arrView = array();

        $arrView['finding_nemo'] =  $item_counts;

        echo json_encode($arrView); //di decode ke dalam bentuk json dalam vaiavel arrview yang dapat menampung banyak isi array
        exit();
    }

    public function cetakmutubuahsidak($est, $tahun, $reg)
    {
        // dd($est, $tahun);

        $queryMTbuah = DB::connection('mysql2')->table('sidak_mutu_buah')
            ->select(
                "sidak_mutu_buah.*",
                DB::raw('DATE_FORMAT(sidak_mutu_buah.datetime, "%M") as bulan'),
                DB::raw('DATE_FORMAT(sidak_mutu_buah.datetime, "%Y") as tahun')
            )
            ->whereYear('sidak_mutu_buah.datetime', $tahun)
            ->whereIn('estate', [$est])
            ->get();

        $databuah = $queryMTbuah->groupBy(function ($item) {
            return $item->estate . ' ' . $item->afdeling . ' ' . $item->blok;
        });
        $databuah = json_decode($databuah, true);

        $foto_temuan = array();

        foreach ($databuah as $key => $value) {
            foreach ($value as $key2 => $value1) {
                if (!empty($value1['foto_temuan']) || !empty($value1['komentar'])) {
                    $uniqueKey = $key;
                    $i = 1;
                    while (array_key_exists($uniqueKey, $foto_temuan)) {
                        $uniqueKey = $key . " " . $i;
                        $i++;
                    }
                    $foto_temuan[$uniqueKey]['est'] = $key;
                    $foto_temuan[$uniqueKey]['temuan'] = $value1['foto_temuan'];
                    $foto_temuan[$uniqueKey]['komen'] = $value1['komentar'];
                }
            }
        }


        // dd($foto_temuan);


        $arrView = array();

        $arrView['est'] =  $est;
        $arrView['regional'] =  $reg;
        $arrView['tanggal'] =  $tahun;
        $arrView['temuan'] =  $foto_temuan;

        $pdf = PDF::loadView('cetakSidakmutubuah', ['data' => $arrView]);

        $customPaper = array(360, 360, 360, 360);
        $pdf->set_paper('A2', 'potrait');
        // $pdf->set_paper('A2', 'potrait');

        $filename = 'Finding Issue Mutu Buah' . $arrView['tanggal']  . $arrView['est'] . $arrView['regional'] . '.pdf';

        return $pdf->download($filename);


        // return view('cetakSidakmutubuah');
    }

    public function getWeekData(Request $request)
    {
        $regional = $request->input('reg');
        $bulan = $request->input('bulan');


        // dd($bulan);

        $queryEste = DB::connection('mysql2')->table('estate')
            ->select('estate.*')
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->where('wil.regional', $regional)
            ->get();
        $queryEste = json_decode($queryEste, true);

        $queryAfd = DB::connection('mysql2')->table('afdeling')
            ->select(
                'afdeling.id',
                'afdeling.nama',
                'estate.est'
            ) //buat mengambil data di estate db dan willayah db
            ->join('estate', 'estate.id', '=', 'afdeling.estate') //kemudian di join untuk mengambil est perwilayah
            ->get();
        $queryAfd = json_decode($queryAfd, true);

        $queryMTbuah = DB::connection('mysql2')->table('sidak_mutu_buah')
            ->select(
                "sidak_mutu_buah.*",
                DB::raw('DATE_FORMAT(sidak_mutu_buah.datetime, "%M") as bulan'),
                DB::raw('DATE_FORMAT(sidak_mutu_buah.datetime, "%Y") as tahun')
            )
            ->where('sidak_mutu_buah.datetime', 'like', '%' . $bulan . '%')
            // ->whereNotIn('estate', ['Plasma1', 'Plasma2', 'Plasma3'])
            // ->whereBetween('sidak_mutu_buah.datetime', ['2023-04-03', '2023-04-09'])
            ->get();
        $queryMTbuah = $queryMTbuah->groupBy(['estate', 'afdeling']);
        $queryMTbuah = json_decode($queryMTbuah, true);

        $queryAsisten = DB::connection('mysql2')->table('asisten_qc')
            ->select('asisten_qc.*')
            ->get();
        $queryAsisten = json_decode($queryAsisten, true);
        $databulananBuah = array();
        foreach ($queryMTbuah as $key => $value) {
            foreach ($value as $key2 => $value2) {
                foreach ($value2 as $key3 => $value3) {

                    $databulananBuah[$key][$key2][$key3] = $value3;
                }
            }
        }

        $defPerbulanWil = array();

        foreach ($queryEste as $key2 => $value2) {
            foreach ($queryAfd as $key3 => $value3) {
                if ($value2['est'] == $value3['est']) {
                    $defPerbulanWil[$value2['est']][$value3['nama']] = 0;
                }
            }
        }



        foreach ($defPerbulanWil as $estateKey => $afdelingArray) {
            foreach ($afdelingArray as $afdelingKey => $afdelingValue) {
                if (isset($databulananBuah[$estateKey]) && isset($databulananBuah[$estateKey][$afdelingKey])) {
                    $defPerbulanWil[$estateKey][$afdelingKey] = $databulananBuah[$estateKey][$afdelingKey];
                }
            }
        }
        function dodo($values)
        {
            foreach ($values as $value) {
                if ($value > 0) {
                    return true;
                }
            }
            return false;
        }
        // dd($defPerbulanWil);
        $sidak_buah = array();

        foreach ($defPerbulanWil as $key => $value) {
            $totalJJG = 0;
            $totaltnpBRD = 0;
            $totalkrgBRD = 0;
            $totalabr = 0;
            $TotPersenTNP = 0;
            $TotPersenKRG = 0;
            $totJJG = 0;
            $totPersenTOtaljjg = 0;
            $totSkor_total = 0;
            $totoverripe = 0;
            $totempty = 0;
            $totJJG_matang = 0;
            $totPer_jjgMtng = 0;
            $totPer_over = 0;
            $totSkor_Over = 0;
            $totPer_Empty = 0;
            $totSkor_Empty = 0;
            $totVcut = 0;
            $totPer_vcut =  0;
            $totSkor_Vcut =  0;
            $totPer_abr =  0;
            $totRD = 0;
            $totPer_rd = 0;
            $totBlok = 0;
            $totKR = 0;
            $tot_krS = 0;
            $totPer_kr = 0;
            $totSkor_kr = 0;
            $totALlskor = 0;
            $totKategor = 0;
            $esatate = '-';
            foreach ($value as $key1 => $value1) {
                $totSkor_jjgMtng = 0;
                if (is_array($value1)) {
                    $jjg_sample = 0;
                    $tnpBRD = 0;
                    $krgBRD = 0;
                    $abr = 0;
                    $skor_total = 0;
                    $overripe = 0;
                    $empty = 0;
                    $vcut = 0;
                    $rd = 0;
                    $sum_kr = 0;
                    $allSkor = 0;
                    $combination_counts = array();

                    foreach ($value1 as $key2 => $value2) {
                        $combination = $value2['blok'] . ' ' . $value2['estate'] . ' ' . $value2['afdeling'] . ' ' . $value2['tph_baris'];
                        if (!isset($combination_counts[$combination])) {
                            $combination_counts[$combination] = 0;
                        }
                        $jjg_sample += $value2['jumlah_jjg'];
                        $tnpBRD += $value2['bmt'];
                        $krgBRD += $value2['bmk'];
                        $abr += $value2['abnormal'];
                        $overripe += $value2['overripe'];
                        $empty += $value2['empty_bunch'];
                        $vcut += $value2['vcut'];
                        $rd += $value2['rd'];
                        $sum_kr += $value2['alas_br'];
                    }
                    $dataBLok = count($combination_counts);
                    if ($sum_kr != 0) {
                        $total_kr = round($sum_kr / $dataBLok, 2);
                    } else {
                        $total_kr = 0;
                    }
                    $per_kr = round($total_kr * 100, 2);
                    $skor_total = round((($tnpBRD + $krgBRD) / ($jjg_sample - $abr)) * 100, 2);
                    $skor_jjgMSk = round(($jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr)) / ($jjg_sample - $abr) * 100, 2);
                    $skor_lewatMTng =  round(($overripe / ($jjg_sample - $abr)) * 100, 2);
                    $skor_jjgKosong =  round(($empty / ($jjg_sample - $abr)) * 100, 2);
                    $skor_vcut =   round(($vcut / $jjg_sample) * 100, 2);
                    $allSkor = sidak_brdTotal($skor_total) +  sidak_matangSKOR($skor_jjgMSk) +  sidak_lwtMatang($skor_lewatMTng) + sidak_jjgKosong($skor_jjgKosong) + sidak_tangkaiP($skor_vcut) + sidak_PengBRD($per_kr);

                    $sidak_buah[$key][$key1]['reg'] = 'REG-I';
                    $sidak_buah[$key][$key1]['pt'] = 'SSMS';
                    $sidak_buah[$key][$key1]['Jumlah_janjang'] = $jjg_sample;
                    $sidak_buah[$key][$key1]['blok'] = $dataBLok;
                    $sidak_buah[$key][$key1]['est'] = $key;
                    $sidak_buah[$key][$key1]['afd'] = $key1;

                    foreach ($queryAsisten as $ast => $asisten) {
                        if ($key === $asisten['est'] && $key1 === $asisten['afd']) {

                            $sidak_buah[$key][$key1]['nama_staff'] = $asisten['nama'];
                        }
                    }
                    $sidak_buah[$key][$key1]['tnp_brd'] = $tnpBRD;
                    $sidak_buah[$key][$key1]['krg_brd'] = $krgBRD;
                    $sidak_buah[$key][$key1]['persenTNP_brd'] = round(($tnpBRD / ($jjg_sample - $abr)) * 100, 2);
                    $sidak_buah[$key][$key1]['persenKRG_brd'] = round(($krgBRD / ($jjg_sample - $abr)) * 100, 2);
                    $sidak_buah[$key][$key1]['total_jjg'] = $tnpBRD + $krgBRD;
                    $sidak_buah[$key][$key1]['persen_totalJjg'] = $skor_total;
                    $sidak_buah[$key][$key1]['skor_total'] = sidak_brdTotal($skor_total);
                    $sidak_buah[$key][$key1]['jjg_matang'] = $jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr);
                    $sidak_buah[$key][$key1]['persen_jjgMtang'] = $skor_jjgMSk;
                    $sidak_buah[$key][$key1]['skor_jjgMatang'] = sidak_matangSKOR($skor_jjgMSk);
                    $sidak_buah[$key][$key1]['lewat_matang'] = $overripe;
                    $sidak_buah[$key][$key1]['persen_lwtMtng'] =  $skor_lewatMTng;
                    $sidak_buah[$key][$key1]['skor_lewatMTng'] = sidak_lwtMatang($skor_lewatMTng);
                    $sidak_buah[$key][$key1]['janjang_kosong'] = $empty;
                    $sidak_buah[$key][$key1]['persen_kosong'] = $skor_jjgKosong;
                    $sidak_buah[$key][$key1]['skor_kosong'] = sidak_jjgKosong($skor_jjgKosong);
                    $sidak_buah[$key][$key1]['vcut'] = $vcut;
                    $sidak_buah[$key][$key1]['vcut_persen'] = $skor_vcut;
                    $sidak_buah[$key][$key1]['vcut_skor'] = sidak_tangkaiP($skor_vcut);
                    $sidak_buah[$key][$key1]['abnormal'] = $abr;
                    $sidak_buah[$key][$key1]['abnormal_persen'] = round(($abr / $jjg_sample) * 100, 2);
                    $sidak_buah[$key][$key1]['rat_dmg'] = $rd;
                    $sidak_buah[$key][$key1]['rd_persen'] = round(($rd / $jjg_sample) * 100, 2);
                    $sidak_buah[$key][$key1]['TPH'] = $total_kr;
                    $sidak_buah[$key][$key1]['persen_krg'] = $per_kr;
                    $sidak_buah[$key][$key1]['skor_kr'] = sidak_PengBRD($per_kr);
                    $sidak_buah[$key][$key1]['All_skor'] = $allSkor;
                    $sidak_buah[$key][$key1]['kategori'] = sidak_akhir($allSkor);

                    $totalJJG += $jjg_sample;
                    $totaltnpBRD += $tnpBRD;
                    $totalkrgBRD += $krgBRD;
                    $totalabr += $abr;
                    $TotPersenTNP = round(($totaltnpBRD / ($totalJJG - $totalabr)) * 100, 2);
                    $TotPersenKRG = round(($totalkrgBRD / ($totalJJG - $totalabr)) * 100, 2);
                    $totJJG = $totaltnpBRD + $totalkrgBRD;
                    $totPersenTOtaljjg = round((($totaltnpBRD + $totalkrgBRD) / ($totalJJG - $totalabr)) * 100, 2);
                    $totSkor_total = sidak_brdTotal($totPersenTOtaljjg);
                    $totoverripe += $overripe;
                    $totempty += $empty;
                    $totJJG_matang = $totalJJG - ($totaltnpBRD + $totalkrgBRD + $totoverripe + $totempty + $totalabr);
                    $totPer_jjgMtng = round($totJJG_matang / ($totalJJG - $totalabr) * 100, 2);

                    $totSkor_jjgMtng = sidak_matangSKOR($totPer_jjgMtng);
                    $totPer_over = round(($totoverripe / ($totalJJG - $totalabr)) * 100, 2);
                    $totSkor_Over = sidak_lwtMatang($totPer_over);
                    $totPer_Empty = round(($totempty / ($totalJJG - $totalabr)) * 100, 2);
                    $totSkor_Empty = sidak_jjgKosong($totPer_Empty);
                    $totVcut += $vcut;
                    $totPer_vcut =   round(($totVcut / $totalJJG) * 100, 2);
                    $totSkor_Vcut =  sidak_tangkaiP($totPer_vcut);
                    $totPer_abr =  round(($totalabr / $totalJJG) * 100, 2);
                    $totRD += $rd;
                    $totPer_rd = round(($totRD / $totalJJG) * 100, 2);
                    $totBlok += $dataBLok;
                    $totKR += $sum_kr;
                    if ($totKR != 0) {
                        $tot_krS = round($totKR / $totBlok, 2);
                    } else {
                        $tot_krS = 0;
                    }
                    $totPer_kr = round($tot_krS * 100, 2);
                    $totSkor_kr = sidak_PengBRD($totPer_kr);
                    $totALlskor = sidak_brdTotal($totPersenTOtaljjg) + sidak_matangSKOR($totPer_jjgMtng) + sidak_lwtMatang($totPer_over) + sidak_jjgKosong($totPer_Empty) + sidak_tangkaiP($totPer_vcut) + sidak_PengBRD($totPer_kr);

                    $totKategor = sidak_akhir($totALlskor);

                    $esatate = $key;
                }

                $colors = ['#b0d48c', '#b0d48c', '#b0d48c', '#b0d48c'];
                $estateInt = intval($esatate); // Convert the estate string to an integer
                $bgColor = $colors[$estateInt % count($colors)];

                $totalValues = [
                    'reg' => '',
                    'pt' => '',
                    'nama_staff' => '',
                    'Jumlah_janjang' => $totalJJG,
                    'est' => $esatate,
                    'afd' => '',
                    'karung' => $totKR,
                    'blok' => $totBlok,
                    'tnp_brd' => $totaltnpBRD,
                    'krg_brd' => $totalkrgBRD,
                    'persenTNP_brd' => $TotPersenTNP,
                    'persenKRG_brd' => $TotPersenKRG,
                    'total_jjg' => $totJJG,
                    'persen_totalJjg' => $totPersenTOtaljjg,
                    'skor_total' => $totSkor_total,
                    'jjg_matang' => $totJJG_matang,
                    'persen_jjgMtang' => $totPer_jjgMtng,
                    'skor_jjgMatang' => $totSkor_jjgMtng,
                    'lewat_matang' => $totoverripe,
                    'persen_lwtMtng' => $totPer_over,
                    'skor_lewatMTng' => $totSkor_Over,
                    'janjang_kosong' => $totempty,
                    'persen_kosong' => $totPer_Empty,
                    'skor_kosong' => $totSkor_Empty,
                    'vcut' => $totVcut,
                    'vcut_persen' => $totPer_vcut,
                    'vcut_skor' => $totSkor_Vcut,
                    'abnormal' => $totalabr,
                    'abnormal_persen' => $totPer_abr,
                    'rat_dmg' => $totRD,
                    'rd_persen' => $totPer_rd,
                    'TPH' => $tot_krS,
                    'persen_krg' => $totPer_kr,
                    'skor_kr' => $totSkor_kr,
                    'All_skor' => $totALlskor,
                    'kategori' => $totKategor,
                    'background_color' => $bgColor, // Add the background color here
                ];


                if (dodo($totalValues)) {
                    $sidak_buah[$key][$key] = $totalValues;
                }
            }
        }

        $new_sidak_buah = array();

        foreach ($sidak_buah as $key => $value) {
            $new_subarray = array();

            foreach ($value as $sub_key => $sub_value) {
                if ($sub_key != $key) {
                    $new_subarray[$sub_key] = $sub_value;
                }
            }

            if (isset($value[$key])) {
                $new_subarray[$key] = $value[$key];
            }

            $new_sidak_buah[$key] = $new_subarray;
        }

        $sidak_buah = $new_sidak_buah;

        // dd($sidak_buah);


        $mutu_buahs = array();
        foreach ($queryEste as $key => $value) {
            foreach ($sidak_buah as $key2 => $value2) {
                if ($value['est'] == $key2) {
                    $mutu_buahs[$value['wil']][$key2] = array_merge($mutu_buahs[$value['wil']][$key2] ?? [], $value2);
                }
            }
        }


        $new_sidakBuah = array();
        foreach ($mutu_buahs as $primaryKey => $primaryValue) {
            $jjg_sample = 0;
            $tnpBRD = 0;
            $krgBRD = 0;
            $abr = 0;
            $skor_total = 0;
            $overripe = 0;
            $empty = 0;
            $vcut = 0;
            $rd = 0;
            $sum_kr = 0;
            $allSkor = 0;
            $blok = 0;
            foreach ($primaryValue as $key => $value) {
                if (isset($value[$key]['Jumlah_janjang'])) {

                    $jjg_sample += $value[$key]['Jumlah_janjang'];
                    $tnpBRD += $value[$key]['tnp_brd'];
                    $krgBRD += $value[$key]['krg_brd'];
                    $abr += $value[$key]['abnormal'];
                    $overripe += $value[$key]['lewat_matang'];
                    $empty += $value[$key]['janjang_kosong'];
                    $vcut += $value[$key]['vcut'];
                    $rd += $value[$key]['rat_dmg'];
                    $sum_kr += $value[$key]['karung'];
                    $blok += $value[$key]['blok'];
                }
                $new_sidakBuah[$primaryKey][$key] = $value;
            }


            if ($sum_kr != 0) {
                $total_kr = round($sum_kr / $blok, 2);
            } else {
                $total_kr = 0;
            }
            $per_kr = round($total_kr * 100, 2);
            $skor_total = round((($tnpBRD + $krgBRD) / ($jjg_sample - $abr)) * 100, 2);
            $skor_jjgMSk = round(($jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr)) / ($jjg_sample - $abr) * 100, 2);
            $skor_lewatMTng =  round(($overripe / ($jjg_sample - $abr)) * 100, 2);
            $skor_jjgKosong =  round(($empty / ($jjg_sample - $abr)) * 100, 2);
            $skor_vcut =   round(($vcut / $jjg_sample) * 100, 2);
            $allSkor = sidak_brdTotal($skor_total) +  sidak_matangSKOR($skor_jjgMSk) +  sidak_lwtMatang($skor_lewatMTng) + sidak_jjgKosong($skor_jjgKosong) + sidak_tangkaiP($skor_vcut) + sidak_PengBRD($per_kr);

            $totKategor = sidak_akhir($allSkor);



            foreach ($queryAsisten as $ast => $asisten) {
                if ($key === $asisten['est'] && $key1 === $asisten['afd']) {
                    $sidak_buah[$key][$key1]['nama_asisten'] = $asisten['nama'];
                }
            }

            $gm = 'GM';
            if ($primaryKey === 1) {
                $namewil = 'WIL-I';
            } else if ($primaryKey === 2) {
                $namewil = 'WIL-II';
            } else if ($primaryKey === 3) {
                $namewil = 'WIL-III';
            } else if ($primaryKey === 4) {
                $namewil = 'WIL-IV';
            } else if ($primaryKey === 5) {
                $namewil = 'WIL-V';
            } else if ($primaryKey === 6) {
                $namewil = 'WIL-VI';
            } else if ($primaryKey === 7) {
                $namewil = 'WIL-VII';
            } else if ($primaryKey === 8) {
                $namewil = 'WIL-VIII';
            } else if ($primaryKey === 10) {
                $namewil = 'WIL-IX';
            } else if ($primaryKey === 11) {
                $namewil = 'WIL-X';
            }
            $wil = $namewil;



            $colors = ['#fffc04', '#fffc04', '#fffc04', '#fffc04'];
            $estateInt = intval($esatate); // Convert the estate string to an integer
            $bgColor = $colors[$estateInt % count($colors)];



            $nestedData = [];

            // Assign the values to the nested data array
            $nestedData['reg'] = 'WiL';
            $nestedData['pt'] = 'SSMS';
            $nestedData['Jumlah_janjang'] = $jjg_sample;
            $nestedData['blok'] = $blok;
            $nestedData['est'] = 'Wil-' . $primaryKey;
            foreach ($queryAsisten as $ast => $asisten) {
                if ($wil === $asisten['est'] && $gm === $asisten['afd']) {
                    $nestedData['nama_asisten'] = $asisten['nama'];
                }
            }
            $nestedData['afd'] = $key1;
            $nestedData['nama_staff'] = '-';
            $nestedData['tnp_brd'] = $tnpBRD;
            $nestedData['krg_brd'] = $krgBRD;
            $nestedData['persenTNP_brd'] = round(($tnpBRD / ($jjg_sample - $abr)) * 100, 2);
            $nestedData['persenKRG_brd'] = round(($krgBRD / ($jjg_sample - $abr)) * 100, 2);
            $nestedData['total_jjg'] = $tnpBRD + $krgBRD;
            $nestedData['persen_totalJjg'] = $skor_total;
            $nestedData['skor_total'] = sidak_brdTotal($skor_total);
            $nestedData['jjg_matang'] = $jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr);
            $nestedData['persen_jjgMtang'] = $skor_jjgMSk;
            $nestedData['skor_jjgMatang'] = sidak_matangSKOR($skor_jjgMSk);
            $nestedData['lewat_matang'] = $overripe;
            $nestedData['persen_lwtMtng'] =  $skor_lewatMTng;
            $nestedData['skor_lewatMTng'] = sidak_lwtMatang($skor_lewatMTng);
            $nestedData['janjang_kosong'] = $empty;
            $nestedData['persen_kosong'] = $skor_jjgKosong;
            $nestedData['skor_kosong'] = sidak_jjgKosong($skor_jjgKosong);
            $nestedData['vcut'] = $vcut;
            $nestedData['vcut_persen'] = $skor_vcut;
            $nestedData['vcut_skor'] = sidak_tangkaiP($skor_vcut);
            $nestedData['abnormal'] = $abr;
            $nestedData['abnormal_persen'] = round(($abr / $jjg_sample) * 100, 2);
            $nestedData['rat_dmg'] = $rd;
            $nestedData['rd_persen'] = round(($rd / $jjg_sample) * 100, 2);
            $nestedData['TPH'] = $total_kr;
            $nestedData['persen_krg'] = $per_kr;
            $nestedData['karung_est'] = $sum_kr;
            $nestedData['all_skor'] = $allSkor;
            $nestedData['skor_kr'] = sidak_PengBRD($per_kr);
            $nestedData['kategori'] = $totKategor;
            $nestedData['background_color'] = $bgColor;

            // Store the nested data array inside the $new_sidakBuah array with the key $primaryKey

            $new_sidakBuah[$primaryKey][$primaryKey] = $nestedData;
        }
        // dd($new_sidakBuah);



        $regional_arrays = [
            'Regional' => $new_sidakBuah
        ];
        // dd($regional_arrays);

        $jjg_sum = 0;
        $jjg_sample = 0;
        $tnpBRD = 0;
        $krgBRD = 0;
        $abr = 0;
        $skor_total = 0;
        $overripe = 0;
        $empty = 0;
        $vcut = 0;
        $rd = 0;
        $sum_kr = 0;
        $allSkor = 0;
        $blok = 0;
        foreach ($regional_arrays["Regional"] as $key => $value) {
            if (isset($value[$key])) {
                $jjg_sample += $value[$key]['Jumlah_janjang'];
                $tnpBRD += $value[$key]['tnp_brd'];
                $krgBRD += $value[$key]['krg_brd'];
                $abr += $value[$key]['abnormal'];
                $overripe += $value[$key]['lewat_matang'];
                $empty += $value[$key]['janjang_kosong'];
                $vcut += $value[$key]['vcut'];
                $rd += $value[$key]['rat_dmg'];
                $sum_kr += $value[$key]['karung_est'];
                $blok += $value[$key]['blok'];
            }
        }

        if ($sum_kr != 0) {
            $total_kr = round($sum_kr / $blok, 2);
        } else {
            $total_kr = 0;
        }
        $per_kr = round($total_kr * 100, 2);
        $skor_total = round((($tnpBRD + $krgBRD) / ($jjg_sample - $abr !== 0 ? ($jjg_sample - $abr) : 1)) * 100, 2);
        $skor_jjgMSk = round(($jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr)) / ($jjg_sample - $abr !== 0 ? ($jjg_sample - $abr) : 1) * 100, 2);
        $skor_lewatMTng =  round(($overripe / ($jjg_sample - $abr !== 0 ? ($jjg_sample - $abr) : 1)) * 100, 2);
        $skor_jjgKosong =  round(($empty / ($jjg_sample - $abr !== 0 ? ($jjg_sample - $abr) : 1)) * 100, 2);
        $skor_vcut =   round(($vcut / ($jjg_sample !== 0 ? $jjg_sample : 1)) * 100, 2);

        $allSkor = sidak_brdTotal($skor_total) +  sidak_matangSKOR($skor_jjgMSk) +  sidak_lwtMatang($skor_lewatMTng) + sidak_jjgKosong($skor_jjgKosong) + sidak_tangkaiP($skor_vcut) + sidak_PengBRD($per_kr);

        if (in_array($key, [1, 2, 3])) {
            $regional = 'REG-I';
        } else if (in_array($key, [4, 5, 6])) {
            $regional = 'REG-II';
        } else {
            $regional = 'REG-III';
        }

        $nama_rh = '-';

        $rh = 'RH';

        foreach ($queryAsisten as $ast => $asisten) {
            if ($regional === $asisten['est'] && $rh === $asisten['afd']) {
                $nama_rh = $asisten['nama'];
            }
        }

        $colors = ['#08b4f4', '#08b4f4', '#08b4f4', '#08b4f4'];
        $estateInt = intval($esatate); // Convert the estate string to an integer
        $bgColor = $colors[$estateInt % count($colors)];

        $regional_arrays["Regional"]['Total'] = [
            'reg' => $regional,
            'pt' => '-',
            'Jumlah_janjang' => $jjg_sample,
            'blok' => $blok,
            'est' => 'Wil-' . $primaryKey,
            'afd' => $key1,
            'nama_staff' => $nama_rh,
            'tnp_brd' => $tnpBRD,
            'krg_brd' => $krgBRD,
            'persenTNP_brd' => round(($tnpBRD / ($jjg_sample - $abr)) * 100, 2),
            'persenKRG_brd' => round(($krgBRD / ($jjg_sample - $abr)) * 100, 2),
            'total_jjg' => $tnpBRD + $krgBRD,
            'persen_totalJjg' => $skor_total,
            'skor_total' => sidak_brdTotal($skor_total),
            'jjg_matang' => $jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr),
            'persen_jjgMtang' => $skor_jjgMSk,
            'skor_jjgMatang' => sidak_matangSKOR($skor_jjgMSk),
            'lewat_matang' => $overripe,
            'persen_lwtMtng' => $skor_lewatMTng,
            'skor_lewatMTng' => sidak_lwtMatang($skor_lewatMTng),
            'janjang_kosong' => $empty,
            'persen_kosong' => $skor_jjgKosong,
            'skor_kosong' => sidak_jjgKosong($skor_jjgKosong),
            'vcut' => $vcut,
            'vcut_persen' => $skor_vcut,
            'vcut_skor' => sidak_tangkaiP($skor_vcut),
            'abnormal' => $abr,
            'abnormal_persen' => round(($abr / $jjg_sample) * 100, 2),
            'rat_dmg' => $rd,
            'rd_persen' => round(($rd / $jjg_sample) * 100, 2),
            'TPH' => $total_kr,
            'persen_krg' => $per_kr,
            'skor_kr' => sidak_PengBRD($per_kr),
            'all_skor' => $allSkor,
            'kategori' => sidak_akhir($allSkor),
            'background_color' => $bgColor,
        ];



        // dd($new_sidakBuah);
        $queryMTbuahv2 = DB::connection('mysql2')->table('sidak_mutu_buah')
            ->select(
                "sidak_mutu_buah.*",
                DB::raw('DATE_FORMAT(sidak_mutu_buah.datetime, "%M") as bulan'),
                DB::raw('DATE_FORMAT(sidak_mutu_buah.datetime, "%Y") as tahun')
            )
            ->where('sidak_mutu_buah.datetime', 'like', '%' . $bulan . '%')
            ->whereIn('estate', ['Plasma1', 'Plasma2', 'Plasma3'])
            // ->whereBetween('sidak_mutu_buah.datetime', ['2023-04-03', '2023-04-09'])
            ->get();
        $queryMTbuahv2 = $queryMTbuahv2->groupBy(['estate', 'afdeling']);
        $queryMTbuahv2 = json_decode($queryMTbuahv2, true);
        $databulananBuahv2 = array();
        foreach ($queryMTbuahv2 as $key => $value) {
            foreach ($value as $key2 => $value2) {
                foreach ($value2 as $key3 => $value3) {

                    $databulananBuahv2[$key][$key2][$key3] = $value3;
                }
            }
        }

        $defPerbulanWilv2 = array();

        foreach ($queryEste as $key2 => $value2) {
            foreach ($queryAfd as $key3 => $value3) {
                if ($value2['est'] == $value3['est']) {
                    $defPerbulanWilv2[$value2['est']][$value3['nama']] = 0;
                }
            }
        }



        foreach ($defPerbulanWilv2 as $estateKey => $afdelingArray) {
            foreach ($afdelingArray as $afdelingKey => $afdelingValue) {
                if (isset($databulananBuahv2[$estateKey]) && isset($databulananBuahv2[$estateKey][$afdelingKey])) {
                    $defPerbulanWilv2[$estateKey][$afdelingKey] = $databulananBuahv2[$estateKey][$afdelingKey];
                }
            }
        }
        function luluv2s($values)
        {
            foreach ($values as $value) {
                if ($value > 0) {
                    return true;
                }
            }
            return false;
        }
        // dd($defPerbulanWilv2);
        $sidak_buahv2 = array();

        foreach ($defPerbulanWilv2 as $key => $value) {
            $totalJJG = 0;
            $totaltnpBRD = 0;
            $totalkrgBRD = 0;
            $totalabr = 0;
            $TotPersenTNP = 0;
            $TotPersenKRG = 0;
            $totJJG = 0;
            $totPersenTOtaljjg = 0;
            $totSkor_total = 0;
            $totoverripe = 0;
            $totempty = 0;
            $totJJG_matang = 0;
            $totPer_jjgMtng = 0;
            $totPer_over = 0;
            $totSkor_Over = 0;
            $totPer_Empty = 0;
            $totSkor_Empty = 0;
            $totVcut = 0;
            $totPer_vcut =  0;
            $totSkor_Vcut =  0;
            $totPer_abr =  0;
            $totRD = 0;
            $totPer_rd = 0;
            $totBlok = 0;
            $totKR = 0;
            $tot_krS = 0;
            $totPer_kr = 0;
            $totSkor_kr = 0;
            $totALlskor = 0;
            $totKategor = 0;
            $estatex = '';
            $name_asist = '';
            foreach ($value as $key1 => $value1) {
                $totSkor_jjgMtng = 0;
                if (is_array($value1)) {
                    $jjg_sample = 0;
                    $tnpBRD = 0;
                    $krgBRD = 0;
                    $abr = 0;
                    $skor_total = 0;
                    $overripe = 0;
                    $empty = 0;
                    $vcut = 0;
                    $rd = 0;
                    $sum_kr = 0;
                    $allSkor = 0;
                    $combination_counts = array();


                    foreach ($value1 as $key2 => $value2) {
                        $combination = $value2['blok'] . ' ' . $value2['estate'] . ' ' . $value2['afdeling'] . ' ' . $value2['tph_baris'];
                        if (!isset($combination_counts[$combination])) {
                            $combination_counts[$combination] = 0;
                        }
                        $jjg_sample += $value2['jumlah_jjg'];
                        $tnpBRD += $value2['bmt'];
                        $krgBRD += $value2['bmk'];
                        $abr += $value2['abnormal'];
                        $overripe += $value2['overripe'];
                        $empty += $value2['empty_bunch'];
                        $vcut += $value2['vcut'];
                        $rd += $value2['rd'];
                        $sum_kr += $value2['alas_br'];
                    }
                    $dataBLok = count($combination_counts);
                    if ($sum_kr != 0) {
                        $total_kr = round($sum_kr / $dataBLok, 2);
                    } else {
                        $total_kr = 0;
                    }
                    $per_kr = round($total_kr * 100, 2);
                    $skor_total = round((($tnpBRD + $krgBRD) / ($jjg_sample - $abr)) * 100, 2);
                    $skor_jjgMSk = round(($jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr)) / ($jjg_sample - $abr) * 100, 2);
                    $skor_lewatMTng =  round(($overripe / ($jjg_sample - $abr)) * 100, 2);
                    $skor_jjgKosong =  round(($empty / ($jjg_sample - $abr)) * 100, 2);
                    $skor_vcut =   round(($vcut / $jjg_sample) * 100, 2);
                    $allSkor = sidak_brdTotal($skor_total) +  sidak_matangSKOR($skor_jjgMSk) +  sidak_lwtMatang($skor_lewatMTng) + sidak_jjgKosong($skor_jjgKosong) + sidak_tangkaiP($skor_vcut) + sidak_PengBRD($per_kr);


                    foreach ($queryAsisten as $ast => $asisten) {
                        if ($key === $asisten['est'] && $key1 === $asisten['afd']) {

                            $sidak_buahv2[$key][$key1]['nama_staff'] = $asisten['nama'];
                        }
                    }
                    $sidak_buahv2[$key][$key1]['reg'] = 'REG-I';
                    $sidak_buahv2[$key][$key1]['pt'] = 'SSMS';
                    $sidak_buahv2[$key][$key1]['Jumlah_janjang'] = $jjg_sample;
                    $sidak_buahv2[$key][$key1]['blok'] = $dataBLok;
                    $sidak_buahv2[$key][$key1]['est'] = $key;
                    $sidak_buahv2[$key][$key1]['afd'] = $key1;

                    $sidak_buahv2[$key][$key1]['tnp_brd'] = $tnpBRD;
                    $sidak_buahv2[$key][$key1]['krg_brd'] = $krgBRD;
                    $sidak_buahv2[$key][$key1]['persenTNP_brd'] = round(($tnpBRD / ($jjg_sample - $abr)) * 100, 2);
                    $sidak_buahv2[$key][$key1]['persenKRG_brd'] = round(($krgBRD / ($jjg_sample - $abr)) * 100, 2);
                    $sidak_buahv2[$key][$key1]['total_jjg'] = $tnpBRD + $krgBRD;
                    $sidak_buahv2[$key][$key1]['persen_totalJjg'] = $skor_total;
                    $sidak_buahv2[$key][$key1]['skor_total'] = sidak_brdTotal($skor_total);
                    $sidak_buahv2[$key][$key1]['jjg_matang'] = $jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr);
                    $sidak_buahv2[$key][$key1]['persen_jjgMtang'] = $skor_jjgMSk;
                    $sidak_buahv2[$key][$key1]['skor_jjgMatang'] = sidak_matangSKOR($skor_jjgMSk);
                    $sidak_buahv2[$key][$key1]['lewat_matang'] = $overripe;
                    $sidak_buahv2[$key][$key1]['persen_lwtMtng'] =  $skor_lewatMTng;
                    $sidak_buahv2[$key][$key1]['skor_lewatMTng'] = sidak_lwtMatang($skor_lewatMTng);
                    $sidak_buahv2[$key][$key1]['janjang_kosong'] = $empty;
                    $sidak_buahv2[$key][$key1]['persen_kosong'] = $skor_jjgKosong;
                    $sidak_buahv2[$key][$key1]['skor_kosong'] = sidak_jjgKosong($skor_jjgKosong);
                    $sidak_buahv2[$key][$key1]['vcut'] = $vcut;
                    $sidak_buahv2[$key][$key1]['vcut_persen'] = $skor_vcut;
                    $sidak_buahv2[$key][$key1]['vcut_skor'] = sidak_tangkaiP($skor_vcut);
                    $sidak_buahv2[$key][$key1]['abnormal'] = $abr;
                    $sidak_buahv2[$key][$key1]['abnormal_persen'] = round(($abr / $jjg_sample) * 100, 2);
                    $sidak_buahv2[$key][$key1]['rat_dmg'] = $rd;
                    $sidak_buahv2[$key][$key1]['rd_persen'] = round(($rd / $jjg_sample) * 100, 2);
                    $sidak_buahv2[$key][$key1]['TPH'] = $total_kr;
                    $sidak_buahv2[$key][$key1]['persen_krg'] = $per_kr;
                    $sidak_buahv2[$key][$key1]['skor_kr'] = sidak_PengBRD($per_kr);
                    $sidak_buahv2[$key][$key1]['All_skor'] = $allSkor;
                    $sidak_buahv2[$key][$key1]['kategori'] = sidak_akhir($allSkor);

                    $totalJJG += $jjg_sample;
                    $totaltnpBRD += $tnpBRD;
                    $totalkrgBRD += $krgBRD;
                    $totalabr += $abr;
                    $TotPersenTNP = round(($totaltnpBRD / ($totalJJG - $totalabr)) * 100, 2);
                    $TotPersenKRG = round(($totalkrgBRD / ($totalJJG - $totalabr)) * 100, 2);
                    $totJJG = $totaltnpBRD + $totalkrgBRD;
                    $totPersenTOtaljjg = round((($totaltnpBRD + $totalkrgBRD) / ($totalJJG - $totalabr)) * 100, 2);
                    $totSkor_total = sidak_brdTotal($totPersenTOtaljjg);
                    $totoverripe += $overripe;
                    $totempty += $empty;
                    $totJJG_matang = $totalJJG - ($totaltnpBRD + $totalkrgBRD + $totoverripe + $totempty + $totalabr);
                    $totPer_jjgMtng = round($totJJG_matang / ($totalJJG - $totalabr) * 100, 2);

                    $totSkor_jjgMtng = sidak_matangSKOR($totPer_jjgMtng);
                    $totPer_over = round(($totoverripe / ($totalJJG - $totalabr)) * 100, 2);
                    $totSkor_Over = sidak_lwtMatang($totPer_over);
                    $totPer_Empty = round(($totempty / ($totalJJG - $totalabr)) * 100, 2);
                    $totSkor_Empty = sidak_jjgKosong($totPer_Empty);
                    $totVcut += $vcut;
                    $totPer_vcut =   round(($totVcut / $totalJJG) * 100, 2);
                    $totSkor_Vcut =  sidak_tangkaiP($totPer_vcut);
                    $totPer_abr =  round(($totalabr / $totalJJG) * 100, 2);
                    $totRD += $rd;
                    $totPer_rd = round(($totRD / $totalJJG) * 100, 2);
                    $totBlok += $dataBLok;
                    $totKR += $sum_kr;
                    if ($totKR != 0) {
                        $tot_krS = round($totKR / $totBlok, 2);
                    } else {
                        $tot_krS = 0;
                    }
                    $totPer_kr = round($tot_krS * 100, 2);
                    $totSkor_kr = sidak_PengBRD($totPer_kr);
                    $totALlskor = sidak_brdTotal($totPersenTOtaljjg) + sidak_matangSKOR($totPer_jjgMtng) + sidak_lwtMatang($totPer_over) + sidak_jjgKosong($totPer_Empty) + sidak_tangkaiP($totPer_vcut) + sidak_PengBRD($totPer_kr);

                    $totKategor = sidak_akhir($totALlskor);

                    $estatex = $key;
                }

                $gm = 'GM';
                foreach ($queryAsisten as $ast => $asisten) {
                    if ($estatex === $asisten['est'] && $gm === $asisten['afd']) {
                        $name_asist = $asisten['nama'];
                    }
                }
                $totalValues = [

                    'reg' => '',
                    'pt' => '',
                    'nama_staff' => $name_asist,
                    'Jumlah_janjang' => $totalJJG,
                    'est' => $estatex,
                    'afd' => '',
                    'karung' => $totKR,
                    'blok' => $totBlok,
                    'tnp_brd' => $totaltnpBRD,
                    'krg_brd' => $totalkrgBRD,
                    'persenTNP_brd' => $TotPersenTNP,
                    'persenKRG_brd' => $TotPersenKRG,
                    'total_jjg' => $totJJG,
                    'persen_totalJjg' => $totPersenTOtaljjg,
                    'skor_total' => $totSkor_total,
                    'jjg_matang' => $totJJG_matang,
                    'persen_jjgMtang' => $totPer_jjgMtng,
                    'skor_jjgMatang' => $totSkor_jjgMtng,
                    'lewat_matang' => $totoverripe,
                    'persen_lwtMtng' => $totPer_over,
                    'skor_lewatMTng' => $totSkor_Over,
                    'janjang_kosong' => $totempty,
                    'persen_kosong' => $totPer_Empty,
                    'skor_kosong' => $totSkor_Empty,
                    'vcut' => $totVcut,
                    'vcut_persen' => $totPer_vcut,
                    'vcut_skor' => $totSkor_Vcut,
                    'abnormal' => $totalabr,
                    'abnormal_persen' => $totPer_abr,
                    'rat_dmg' => $totRD,
                    'rd_persen' => $totPer_rd,
                    'TPH' => $tot_krS,
                    'persen_krg' => $totPer_kr,
                    'skor_kr' => $totSkor_kr,
                    'All_skor' => $totALlskor,
                    'kategori' => $totKategor,
                    // Add more variables here
                ];

                if (luluv2s($totalValues)) {
                    $sidak_buahv2[$key][$key] = $totalValues;
                }
            }
        }
        // dd($sidak_buahv2);

        $new_sidak_buahv2 = array();

        foreach ($sidak_buahv2 as $key => $value) {
            $new_subarray = array();

            foreach ($value as $sub_key => $sub_value) {
                if ($sub_key != $key) {
                    $new_subarray[$sub_key] = $sub_value;
                }
            }

            if (isset($value[$key])) {
                $new_subarray[$key] = $value[$key];
            }

            $new_sidak_buahv2[$key] = $new_subarray;
        }

        $sidak_buahv2 = $new_sidak_buahv2;




        $mutu_buahsv2 = array();
        foreach ($queryEste as $key => $value) {
            foreach ($sidak_buahv2 as $key2 => $value2) {
                if ($value['est'] == $key2) {
                    $mutu_buahsv2[$value['wil']][$key2] = array_merge($mutu_buahsv2[$value['wil']][$key2] ?? [], $value2);
                }
            }
        }

        $new_sidakBuahv2 = array();
        foreach ($mutu_buahsv2 as $primaryKey => $primaryValue) {
            $jjg_sample = 0;
            $tnpBRD = 0;
            $krgBRD = 0;
            $abr = 0;
            $skor_total = 0;
            $overripe = 0;
            $empty = 0;
            $vcut = 0;
            $rd = 0;
            $sum_kr = 0;
            $allSkor = 0;
            $blok = 0;
            foreach ($primaryValue as $key => $value) {
                if (isset($value[$key]['Jumlah_janjang'])) {

                    $jjg_sample += $value[$key]['Jumlah_janjang'];
                    $tnpBRD += $value[$key]['tnp_brd'];
                    $krgBRD += $value[$key]['krg_brd'];
                    $abr += $value[$key]['abnormal'];
                    $overripe += $value[$key]['lewat_matang'];
                    $empty += $value[$key]['janjang_kosong'];
                    $vcut += $value[$key]['vcut'];
                    $rd += $value[$key]['rat_dmg'];
                    $sum_kr += $value[$key]['karung'];
                    $blok += $value[$key]['blok'];
                }
                $new_sidakBuahv2[$primaryKey][$key] = $value;
            }


            if ($sum_kr != 0) {
                $total_kr = round($sum_kr / $blok, 2);
            } else {
                $total_kr = 0;
            }
            $per_kr = round($total_kr * 100, 2);
            $skor_total = round((($tnpBRD + $krgBRD) / ($jjg_sample - $abr)) * 100, 2);
            $skor_jjgMSk = round(($jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr)) / ($jjg_sample - $abr) * 100, 2);
            $skor_lewatMTng =  round(($overripe / ($jjg_sample - $abr)) * 100, 2);
            $skor_jjgKosong =  round(($empty / ($jjg_sample - $abr)) * 100, 2);
            $skor_vcut =   round(($vcut / $jjg_sample) * 100, 2);
            $allSkor = sidak_brdTotal($skor_total) +  sidak_matangSKOR($skor_jjgMSk) +  sidak_lwtMatang($skor_lewatMTng) + sidak_jjgKosong($skor_jjgKosong) + sidak_tangkaiP($skor_vcut) + sidak_PengBRD($per_kr);



            $gm = 'EM';
            if ($primaryKey === 1) {
                $namewil = 'WIL-I';
            } else if ($primaryKey === 2) {
                $namewil = 'WIL-II';
            } else if ($primaryKey === 3) {
                $namewil = 'WIL-III';
            } else if ($primaryKey === 4) {
                $namewil = 'WIL-IV';
            } else if ($primaryKey === 5) {
                $namewil = 'WIL-V';
            } else if ($primaryKey === 6) {
                $namewil = 'WIL-VI';
            } else if ($primaryKey === 7) {
                $namewil = 'WIL-VII';
            } else if ($primaryKey === 8) {
                $namewil = 'WIL-VIII';
            }
            $wil = 'Plasma1';

            $nestedData = [];

            $nestedData['reg'] = 'WiL';
            $nestedData['pt'] = 'SSMS';
            $nestedData['Jumlah_janjang'] = $jjg_sample;
            $nestedData['blok'] = $blok;
            $nestedData['est'] = 'Wil-' . $primaryKey;


            foreach ($queryAsisten as $ast => $asisten) {
                if ($wil === $asisten['est'] && $gm === $asisten['afd']) {
                    $nestedData['nama_asisten'] = $asisten['nama'];
                }
            }
            $nestedData['afd'] = $key1;
            $nestedData['tnp_brd'] = $tnpBRD;
            $nestedData['krg_brd'] = $krgBRD;
            $nestedData['persenTNP_brd'] = round(($tnpBRD / ($jjg_sample - $abr)) * 100, 2);
            $nestedData['persenKRG_brd'] = round(($krgBRD / ($jjg_sample - $abr)) * 100, 2);
            $nestedData['total_jjg'] = $tnpBRD + $krgBRD;
            $nestedData['persen_totalJjg'] = $skor_total;
            $nestedData['skor_total'] = sidak_brdTotal($skor_total);
            $nestedData['jjg_matang'] = $jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr);
            $nestedData['persen_jjgMtang'] = $skor_jjgMSk;
            $nestedData['skor_jjgMatang'] = sidak_matangSKOR($skor_jjgMSk);
            $nestedData['lewat_matang'] = $overripe;
            $nestedData['persen_lwtMtng'] =  $skor_lewatMTng;
            $nestedData['skor_lewatMTng'] = sidak_lwtMatang($skor_lewatMTng);
            $nestedData['janjang_kosong'] = $empty;
            $nestedData['persen_kosong'] = $skor_jjgKosong;
            $nestedData['skor_kosong'] = sidak_jjgKosong($skor_jjgKosong);
            $nestedData['vcut'] = $vcut;
            $nestedData['vcut_persen'] = $skor_vcut;
            $nestedData['vcut_skor'] = sidak_tangkaiP($skor_vcut);
            $nestedData['abnormal'] = $abr;
            $nestedData['abnormal_persen'] = round(($abr / $jjg_sample) * 100, 2);
            $nestedData['rat_dmg'] = $rd;
            $nestedData['rd_persen'] = round(($rd / $jjg_sample) * 100, 2);
            $nestedData['TPH'] = $total_kr;
            $nestedData['persen_krg'] = $per_kr;
            $nestedData['karung_est'] = $sum_kr;
            $nestedData['skor_kr'] = sidak_PengBRD($per_kr);

            // Store the nested data array inside the $new_sidakBuahv2 array with the key $primaryKey
            $new_sidakBuahv2[$primaryKey][$primaryKey] = $nestedData;
        }
        // dd($new_sidakBuahv2);



        $regional_arraysv2 = [
            'Regional' => $new_sidakBuahv2
        ];

        updateKeyRecursive($sidak_buah, "KTE4", "KTE");
        // updateKeyRecursive($new_sidakBuah, "KTE4", "KTE");
        // updateKeyRecursive3($new_sidakBuah, "KTE4", "KTE");
        // $new_sidakBuah = updateKeyRecursive2($new_sidakBuah);


        // dd($new_sidakBuah);
        $arrView = array();

        $arrView['data_week'] =  $sidak_buah;
        $arrView['data_weekv2'] =  $new_sidakBuah;
        $arrView['plasma'] =  $regional_arraysv2;
        $arrView['reg_data'] =  $regional_arrays;

        echo json_encode($arrView); //di decode ke dalam bentuk json dalam vaiavel arrview yang dapat menampung banyak isi array
        exit();
    }


     public function getahun_sbi(Request $request)
    {
        $regional = $request->input('reg');
        $tahun = $request->input('tahun');

        // dd($regional, $tahun);
        $queryAsisten = DB::connection('mysql2')->table('asisten_qc')
            ->select('asisten_qc.*')
            ->get();

        $queryAsisten = json_decode($queryAsisten, true);

        // dd($startDate, $endDate);
        $queryEste = DB::connection('mysql2')->table('estate')
            ->select('estate.*')
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->where('wil.regional', $regional)
            ->whereNotIn('estate.est', ['SRE', 'LDE', 'SKE', 'CWS1'])
            ->get();
        $queryEste = json_decode($queryEste, true);

        $estev2 = DB::connection('mysql2')->table('estate')
            ->select('estate.*')
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->where('wil.regional', $regional)
            ->whereNotIn('estate.est', ['SRE', 'LDE', 'SKE', 'CWS1'])
            ->pluck('est');
        $estev2 = json_decode($estev2, true);

        $queryAfd = DB::connection('mysql2')->table('afdeling')
            ->select(
                'afdeling.id',
                'afdeling.nama',
                'estate.est'
            ) //buat mengambil data di estate db dan willayah db
            ->join('estate', 'estate.id', '=', 'afdeling.estate') //kemudian di join untuk mengambil est perwilayah
            ->get();
        $queryAfd = json_decode($queryAfd, true);

        $queryMTbuah = DB::connection('mysql2')->table('sidak_mutu_buah')
            ->select(
                "sidak_mutu_buah.*",
                DB::raw('DATE_FORMAT(sidak_mutu_buah.datetime, "%M") as bulan'),
                DB::raw('DATE_FORMAT(sidak_mutu_buah.datetime, "%Y") as tahun')
            )
            // ->whereBetween('sidak_mutu_buah.datetime', [$startDate, $endDate])
            ->whereYear('sidak_mutu_buah.datetime', '=', $tahun)

            // ->whereBetween('sidak_mutu_buah.datetime', ['2023-04-03', '2023-04-09'])
            ->get();
        $queryMTbuah = $queryMTbuah->groupBy(['estate', 'afdeling']);
        $queryMTbuah = json_decode($queryMTbuah, true);

        $databulananBuah = array();
        foreach ($queryMTbuah as $key => $value) {
            foreach ($value as $key2 => $value2) {
                foreach ($value2 as $key3 => $value3) {

                    $databulananBuah[$key][$key2][$key3] = $value3;
                }
            }
        }

        // dd($queryMTbuah);
        $defPerbulanWil = array();

        foreach ($queryEste as $key2 => $value2) {
            foreach ($queryAfd as $key3 => $value3) {
                if ($value2['est'] == $value3['est']) {
                    $defPerbulanWil[$value2['est']][$value3['nama']] = 0;
                }
            }
        }



        foreach ($defPerbulanWil as $estateKey => $afdelingArray) {
            foreach ($afdelingArray as $afdelingKey => $afdelingValue) {
                if (isset($databulananBuah[$estateKey]) && isset($databulananBuah[$estateKey][$afdelingKey])) {
                    $defPerbulanWil[$estateKey][$afdelingKey] = $databulananBuah[$estateKey][$afdelingKey];
                }
            }
        }

        // dd($defPerbulanWil);
        $sidak_buah = array();
        foreach ($defPerbulanWil as $key => $value) {
            $totalJJG = 0;
            $totaltnpBRD = 0;
            $totalkrgBRD = 0;
            $totalabr = 0;
            $TotPersenTNP = 0;
            $TotPersenKRG = 0;
            $totJJG = 0;
            $totPersenTOtaljjg = 0;
            $totSkor_total = 0;
            $totoverripe = 0;
            $totempty = 0;
            $totJJG_matang = 0;
            $totPer_jjgMtng = 0;
            $totPer_over = 0;
            $totSkor_Over = 0;
            $totPer_Empty = 0;
            $totSkor_Empty = 0;
            $totVcut = 0;
            $totPer_vcut =  0;
            $totSkor_Vcut =  0;
            $totPer_abr =  0;
            $totRD = 0;
            $totPer_rd = 0;
            $totBlok = 0;
            $totKR = 0;
            $tot_krS = 0;
            $totPer_kr = 0;
            $totSkor_kr = 0;
            $totALlskor = 0;
            $totKategor = 0;
            foreach ($value as $key1 => $value1) {
                if (is_array($value1)) {
                    $jjg_sample = 0;
                    $tnpBRD = 0;
                    $krgBRD = 0;
                    $abr = 0;
                    $skor_total = 0;
                    $overripe = 0;
                    $empty = 0;
                    $vcut = 0;
                    $rd = 0;
                    $sum_kr = 0;
                    $allSkor = 0;
                    $combination_counts = array();

                    foreach ($value1 as $key2 => $value2) {
                        $combination = $value2['blok'] . ' ' . $value2['estate'] . ' ' . $value2['afdeling'] . ' ' . $value2['tph_baris'];
                        if (!isset($combination_counts[$combination])) {
                            $combination_counts[$combination] = 0;
                        }
                        $jjg_sample += $value2['jumlah_jjg'];
                        $tnpBRD += $value2['bmt'];
                        $krgBRD += $value2['bmk'];
                        $abr += $value2['abnormal'];
                        $overripe += $value2['overripe'];
                        $empty += $value2['empty_bunch'];
                        $vcut += $value2['vcut'];
                        $rd += $value2['rd'];
                        $sum_kr += $value2['alas_br'];
                    }
                    $dataBLok = count($combination_counts);
                    if ($sum_kr != 0) {
                        $total_kr = round($sum_kr / $dataBLok, 2);
                    } else {
                        $total_kr = 0;
                    }
                    $per_kr = round($total_kr * 100, 2);
                    $skor_total = round((($tnpBRD + $krgBRD) / ($jjg_sample - $abr)) * 100, 2);
                    $skor_jjgMSk = round(($jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr)) / ($jjg_sample - $abr) * 100, 2);
                    $skor_lewatMTng =  round(($overripe / ($jjg_sample - $abr)) * 100, 2);
                    $skor_jjgKosong =  round(($empty / ($jjg_sample - $abr)) * 100, 2);
                    $skor_vcut =   round(($vcut / $jjg_sample) * 100, 2);
                    $allSkor = sidak_brdTotal($skor_total) +  sidak_matangSKOR($skor_jjgMSk) +  sidak_lwtMatang($skor_lewatMTng) + sidak_jjgKosong($skor_jjgKosong) + sidak_tangkaiP($skor_vcut) + sidak_PengBRD($per_kr);

                    $sidak_buah[$key][$key1]['Jumlah_janjang'] = $jjg_sample;
                    $sidak_buah[$key][$key1]['blok'] = $dataBLok;
                    $sidak_buah[$key][$key1]['est'] = $key;
                    $sidak_buah[$key][$key1]['afd'] = $key1;
                    $sidak_buah[$key][$key1]['nama_staff'] = '-';
                    $sidak_buah[$key][$key1]['tnp_brd'] = $tnpBRD;
                    $sidak_buah[$key][$key1]['krg_brd'] = $krgBRD;
                    $sidak_buah[$key][$key1]['persenTNP_brd'] = round(($tnpBRD / ($jjg_sample - $abr)) * 100, 2);
                    $sidak_buah[$key][$key1]['persenKRG_brd'] = round(($krgBRD / ($jjg_sample - $abr)) * 100, 2);
                    $sidak_buah[$key][$key1]['total_jjg'] = $tnpBRD + $krgBRD;
                    $sidak_buah[$key][$key1]['persen_totalJjg'] = $skor_total;
                    $sidak_buah[$key][$key1]['skor_total'] = sidak_brdTotal($skor_total);
                    $sidak_buah[$key][$key1]['jjg_matang'] = $jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr);
                    $sidak_buah[$key][$key1]['persen_jjgMtang'] = $skor_jjgMSk;
                    $sidak_buah[$key][$key1]['skor_jjgMatang'] = sidak_matangSKOR($skor_jjgMSk);
                    $sidak_buah[$key][$key1]['lewat_matang'] = $overripe;
                    $sidak_buah[$key][$key1]['persen_lwtMtng'] =  $skor_lewatMTng;
                    $sidak_buah[$key][$key1]['skor_lewatMTng'] = sidak_lwtMatang($skor_lewatMTng);
                    $sidak_buah[$key][$key1]['janjang_kosong'] = $empty;
                    $sidak_buah[$key][$key1]['persen_kosong'] = $skor_jjgKosong;
                    $sidak_buah[$key][$key1]['skor_kosong'] = sidak_jjgKosong($skor_jjgKosong);
                    $sidak_buah[$key][$key1]['vcut'] = $vcut;
                    $sidak_buah[$key][$key1]['karung'] = $sum_kr;
                    $sidak_buah[$key][$key1]['vcut_persen'] = $skor_vcut;
                    $sidak_buah[$key][$key1]['vcut_skor'] = sidak_tangkaiP($skor_vcut);
                    $sidak_buah[$key][$key1]['abnormal'] = $abr;
                    $sidak_buah[$key][$key1]['abnormal_persen'] = round(($abr / $jjg_sample) * 100, 2);
                    $sidak_buah[$key][$key1]['rat_dmg'] = $rd;
                    $sidak_buah[$key][$key1]['rd_persen'] = round(($rd / $jjg_sample) * 100, 2);
                    $sidak_buah[$key][$key1]['TPH'] = $total_kr;
                    $sidak_buah[$key][$key1]['persen_krg'] = $per_kr;
                    $sidak_buah[$key][$key1]['skor_kr'] = sidak_PengBRD($per_kr);
                    $sidak_buah[$key][$key1]['All_skor'] = $allSkor;
                    $sidak_buah[$key][$key1]['kategori'] = sidak_akhir($allSkor);

                    foreach ($queryAsisten as $ast => $asisten) {
                        if ($key === $asisten['est'] && $key1 === $asisten['afd']) {
                            $sidak_buah[$key][$key1]['nama_asisten'] = $asisten['nama'];
                        }
                    }


                    $totalJJG += $jjg_sample;
                    $totaltnpBRD += $tnpBRD;
                    $totalkrgBRD += $krgBRD;
                    $totalabr += $abr;
                    $TotPersenTNP = round(($totaltnpBRD / ($totalJJG - $totalabr)) * 100, 2);
                    $TotPersenKRG = round(($totalkrgBRD / ($totalJJG - $totalabr)) * 100, 2);
                    $totJJG = $totaltnpBRD + $totalkrgBRD;
                    $totPersenTOtaljjg = round((($totaltnpBRD + $totalkrgBRD) / ($totalJJG - $totalabr)) * 100, 2);
                    $totSkor_total = sidak_brdTotal($totPersenTOtaljjg);
                    $totoverripe += $overripe;
                    $totempty += $empty;
                    $totJJG_matang = $totalJJG - ($totaltnpBRD + $totalkrgBRD + $totoverripe + $totempty + $totalabr);
                    $totPer_jjgMtng = round($totJJG_matang / ($totalJJG - $totalabr) * 100, 2);

                    $totSkor_jjgMtng = sidak_matangSKOR($totPer_jjgMtng);
                    $totPer_over = round(($totoverripe / ($totalJJG - $totalabr)) * 100, 2);
                    $totSkor_Over = sidak_lwtMatang($totPer_over);
                    $totPer_Empty = round(($totempty / ($totalJJG - $totalabr)) * 100, 2);
                    $totSkor_Empty = sidak_jjgKosong($totPer_Empty);
                    $totVcut += $vcut;
                    $totPer_vcut =   round(($totVcut / $totalJJG) * 100, 2);
                    $totSkor_Vcut =  sidak_tangkaiP($totPer_vcut);
                    $totPer_abr =  round(($totalabr / $totalJJG) * 100, 2);
                    $totRD += $rd;
                    $totPer_rd = round(($totRD / $totalJJG) * 100, 2);
                    $totBlok += $dataBLok;
                    $totKR += $sum_kr;
                    if ($totKR != 0) {
                        $tot_krS = round($totKR / $totBlok, 2);
                    } else {
                        $tot_krS = 0;
                    }
                    $totPer_kr = round($tot_krS * 100, 2);
                    $totSkor_kr = sidak_PengBRD($totPer_kr);
                    $totALlskor = sidak_brdTotal($totPersenTOtaljjg) + sidak_matangSKOR($totPer_jjgMtng) + sidak_lwtMatang($totPer_over) + sidak_jjgKosong($totPer_Empty) + sidak_tangkaiP($totPer_vcut) + sidak_PengBRD($totPer_kr);

                    $totKategor = sidak_akhir($totALlskor);
                } else {

                    $sidak_buah[$key][$key1]['Jumlah_janjang'] = 0;
                    $sidak_buah[$key][$key1]['blok'] = 0;
                    $sidak_buah[$key][$key1]['est'] = $key;
                    $sidak_buah[$key][$key1]['afd'] = $key1;
                    $sidak_buah[$key][$key1]['nama_staff'] = '-';
                    $sidak_buah[$key][$key1]['tnp_brd'] = 0;
                    $sidak_buah[$key][$key1]['krg_brd'] = 0;
                    $sidak_buah[$key][$key1]['persenTNP_brd'] = 0;
                    $sidak_buah[$key][$key1]['persenKRG_brd'] = 0;
                    $sidak_buah[$key][$key1]['total_jjg'] = 0;
                    $sidak_buah[$key][$key1]['persen_totalJjg'] = 0;
                    $sidak_buah[$key][$key1]['skor_total'] = 0;
                    $sidak_buah[$key][$key1]['jjg_matang'] = 0;
                    $sidak_buah[$key][$key1]['persen_jjgMtang'] = 0;
                    $sidak_buah[$key][$key1]['skor_jjgMatang'] = 0;
                    $sidak_buah[$key][$key1]['lewat_matang'] = 0;
                    $sidak_buah[$key][$key1]['persen_lwtMtng'] =  0;
                    $sidak_buah[$key][$key1]['skor_lewatMTng'] = 0;
                    $sidak_buah[$key][$key1]['janjang_kosong'] = 0;
                    $sidak_buah[$key][$key1]['persen_kosong'] = 0;
                    $sidak_buah[$key][$key1]['skor_kosong'] = 0;
                    $sidak_buah[$key][$key1]['vcut'] = 0;
                    $sidak_buah[$key][$key1]['karung'] = 0;
                    $sidak_buah[$key][$key1]['vcut_persen'] = 0;
                    $sidak_buah[$key][$key1]['vcut_skor'] = 0;
                    $sidak_buah[$key][$key1]['abnormal'] = 0;
                    $sidak_buah[$key][$key1]['abnormal_persen'] = 0;
                    $sidak_buah[$key][$key1]['rat_dmg'] = 0;
                    $sidak_buah[$key][$key1]['rd_persen'] = 0;
                    $sidak_buah[$key][$key1]['TPH'] = 0;
                    $sidak_buah[$key][$key1]['persen_krg'] = 0;
                    $sidak_buah[$key][$key1]['skor_kr'] = 0;
                    $sidak_buah[$key][$key1]['All_skor'] = 0;
                    $sidak_buah[$key][$key1]['kategori'] = 0;
                    foreach ($queryAsisten as $ast => $asisten) {
                        if ($key === $asisten['est'] && $key1 === $asisten['afd']) {
                            $sidak_buah[$key][$key1]['nama_asisten'] = $asisten['nama'];
                        }
                    }
                }
            }
        }



        $mutu_buah = array();
        foreach ($queryEste as $key => $value) {
            foreach ($sidak_buah as $key2 => $value2) {
                if ($value['est'] == $key2) {
                    $mutu_buah[$value['wil']][$key2] = array_merge($mutu_buah[$value['wil']][$key2] ?? [], $value2);
                }
            }
        }

        // Remove the "Plasma1" element from the original array
        if (isset($mutu_buah[1]['Plasma1'])) {
            $plasma1 = $mutu_buah[1]['Plasma1'];
            unset($mutu_buah[1]['Plasma1']);
        } else {
            $plasma1 = null;
        }

        // Add the "Plasma1" element to its own index
        if ($plasma1 !== null) {
            $mutu_buah[4] = ['Plasma1' => $plasma1];
        }
        // Optional: Re-index the array to ensure the keys are in sequential order


        if (isset($mutu_buah[4]['Plasma2'])) {
            $Plasma2 = $mutu_buah[4]['Plasma2'];
            unset($mutu_buah[4]['Plasma2']);
        } else {
            $Plasma2 = null;
        }

        // Add the "Plasma2" element to its own index
        if ($Plasma2 !== null) {
            $mutu_buah[7] = ['Plasma2' => $Plasma2];
        }

        if (isset($mutu_buah[7]['Plasma3'])) {
            $Plasma3 = $mutu_buah[7]['Plasma3'];
            unset($mutu_buah[7]['Plasma3']);
        } else {
            $Plasma3 = null;
        }

        // Add the "Plasma3" element to its own index
        if ($Plasma3 !== null) {
            $mutu_buah[9] = ['Plasma3' => $Plasma3];
        }
        // Optional: Re-index the array to ensure the keys are in sequential order
        $mutu_buah = array_values($mutu_buah);

        $mutubuah_est = array();
        foreach ($mutu_buah as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $jjg_sample = 0;
                $tnpBRD = 0;
                $krgBRD = 0;
                $abr = 0;
                $skor_total = 0;
                $overripe = 0;
                $empty = 0;
                $vcut = 0;
                $rd = 0;
                $sum_kr = 0;
                $allSkor = 0;
                $dataBLok = 0;
                foreach ($value1 as $key2 => $value2) {
                    $jjg_sample += $value2['Jumlah_janjang'];
                    $tnpBRD += $value2['tnp_brd'];
                    $krgBRD += $value2['krg_brd'];
                    $abr += $value2['abnormal'];
                    $overripe += $value2['lewat_matang'];
                    $empty += $value2['janjang_kosong'];
                    $vcut += $value2['vcut'];

                    $rd += $value2['rat_dmg'];

                    $dataBLok += $value2['blok'];
                    $sum_kr += $value2['karung'];
                }

                if ($sum_kr != 0) {
                    $total_kr = round($sum_kr / $dataBLok, 2);
                } else {
                    $total_kr = 0;
                }
                $per_kr = round($total_kr * 100, 2);
                $skor_total = round(($jjg_sample - $abr != 0 ? (($tnpBRD + $krgBRD) / ($jjg_sample - $abr)) * 100 : 0), 2);

                $skor_jjgMSk = round(($jjg_sample - $abr != 0 ? (($jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr)) / ($jjg_sample - $abr)) * 100 : 0), 2);

                $skor_lewatMTng = round(($jjg_sample - $abr != 0 ? ($overripe / ($jjg_sample - $abr)) * 100 : 0), 2);

                $skor_jjgKosong = round(($jjg_sample - $abr != 0 ? ($empty / ($jjg_sample - $abr)) * 100 : 0), 2);

                $skor_vcut = round(($jjg_sample != 0 ? ($vcut / $jjg_sample) * 100 : 0), 2);

                $allSkor = sidak_brdTotal($skor_total) +  sidak_matangSKOR($skor_jjgMSk) +  sidak_lwtMatang($skor_lewatMTng) + sidak_jjgKosong($skor_jjgKosong) + sidak_tangkaiP($skor_vcut) + sidak_PengBRD($per_kr);

                $em = 'EM';

                $nama_em = '';

                // dd($key1);
                foreach ($queryAsisten as $ast => $asisten) {
                    if ($key1 === $asisten['est'] && $em === $asisten['afd']) {
                        $nama_em = $asisten['nama'];
                    }
                }
                $jjg_mth = $tnpBRD + $krgBRD + $overripe + $empty;

                $skor_jjgMTh = ($jjg_sample - $abr != 0) ? round($jjg_mth / ($jjg_sample - $abr) * 100, 2) : 0;

                $mutubuah_est[$key][$key1]['jjg_mantah'] = $jjg_mth;
                $mutubuah_est[$key][$key1]['persen_jjgmentah'] = $skor_jjgMTh;
                if ($jjg_sample == 0 && $tnpBRD == 0 &&   $krgBRD == 0 && $abr == 0 && $overripe == 0 && $empty == 0 &&  $vcut == 0 &&  $rd == 0 && $sum_kr == 0) {
                    $mutubuah_est[$key][$key1]['check_arr'] = 'kosong';
                    $mutubuah_est[$key][$key1]['All_skor'] = 0;
                } else {
                    $mutubuah_est[$key][$key1]['check_arr'] = 'ada';
                    $mutubuah_est[$key][$key1]['All_skor'] = $allSkor;
                }


                $mutubuah_est[$key][$key1]['Jumlah_janjang'] = $jjg_sample;
                $mutubuah_est[$key][$key1]['blok'] = $dataBLok;
                $mutubuah_est[$key][$key1]['EM'] = 'EM';
                $mutubuah_est[$key][$key1]['Nama_assist'] = $nama_em;
                $mutubuah_est[$key][$key1]['nama_staff'] = '-';
                $mutubuah_est[$key][$key1]['tnp_brd'] = $tnpBRD;
                $mutubuah_est[$key][$key1]['krg_brd'] = $krgBRD;
                $mutubuah_est[$key][$key1]['persenTNP_brd'] = round(($jjg_sample - $abr != 0 ? ($tnpBRD / ($jjg_sample - $abr)) * 100 : 0), 2);
                $mutubuah_est[$key][$key1]['persenKRG_brd'] = round(($jjg_sample - $abr != 0 ? ($krgBRD / ($jjg_sample - $abr)) * 100 : 0), 2);
                $mutubuah_est[$key][$key1]['abnormal_persen'] = round(($jjg_sample != 0 ? ($abr / $jjg_sample) * 100 : 0), 2);
                $mutubuah_est[$key][$key1]['rd_persen'] = round(($jjg_sample != 0 ? ($rd / $jjg_sample) * 100 : 0), 2);


                $mutubuah_est[$key][$key1]['total_jjg'] = $tnpBRD + $krgBRD;
                $mutubuah_est[$key][$key1]['persen_totalJjg'] = $skor_total;
                $mutubuah_est[$key][$key1]['skor_total'] = sidak_brdTotal($skor_total);
                $mutubuah_est[$key][$key1]['jjg_matang'] = $jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr);
                $mutubuah_est[$key][$key1]['persen_jjgMtang'] = $skor_jjgMSk;
                $mutubuah_est[$key][$key1]['skor_jjgMatang'] = sidak_matangSKOR($skor_jjgMSk);
                $mutubuah_est[$key][$key1]['lewat_matang'] = $overripe;
                $mutubuah_est[$key][$key1]['persen_lwtMtng'] =  $skor_lewatMTng;
                $mutubuah_est[$key][$key1]['skor_lewatMTng'] = sidak_lwtMatang($skor_lewatMTng);
                $mutubuah_est[$key][$key1]['janjang_kosong'] = $empty;
                $mutubuah_est[$key][$key1]['persen_kosong'] = $skor_jjgKosong;
                $mutubuah_est[$key][$key1]['skor_kosong'] = sidak_jjgKosong($skor_jjgKosong);
                $mutubuah_est[$key][$key1]['vcut'] = $vcut;
                $mutubuah_est[$key][$key1]['vcut_persen'] = $skor_vcut;
                $mutubuah_est[$key][$key1]['vcut_skor'] = sidak_tangkaiP($skor_vcut);
                $mutubuah_est[$key][$key1]['abnormal'] = $abr;

                $mutubuah_est[$key][$key1]['rat_dmg'] = $rd;

                $mutubuah_est[$key][$key1]['karung'] = $sum_kr;
                $mutubuah_est[$key][$key1]['TPH'] = $total_kr;
                $mutubuah_est[$key][$key1]['persen_krg'] = $per_kr;
                $mutubuah_est[$key][$key1]['skor_kr'] = sidak_PengBRD($per_kr);
                // $mutubuah_est[$key][$key1]['All_skor'] = $allSkor;
                $mutubuah_est[$key][$key1]['kategori'] = sidak_akhir($allSkor);
            }
        }


        // dd($mutu_buah);

        foreach ($mutu_buah as $key1 => $estates)  if (is_array($estates)) {
            $sortedData = array();
            $sortedDataEst = array();

            foreach ($estates as $estateName => $data) {
                if (is_array($data)) {
                    // dd($data);
                    $sortedDataEst[] = array(
                        'key1' => $key1,
                        'estateName' => $estateName,
                        'data' => $data
                    );
                    foreach ($data as $key2 => $scores) {
                        if (is_array($scores)) {
                            // dd($scores);
                            $sortedData[] = array(
                                'estateName' => $estateName,
                                'key2' => $key2,
                                'scores' => $scores
                            );
                        }
                    }
                }
            }

            //mengurutkan untuk nilai afd
            usort($sortedData, function ($a, $b) {
                return $b['scores']['All_skor'] - $a['scores']['All_skor'];
            });
            // //mengurutkan untuk nilai estate
            // usort($sortedDataEst, function ($a, $b) {
            //     return $b['data']['TotalSkorEST'] - $a['data']['TotalSkorEST'];
            // });

            //menambahkan nilai rank ke dalam afd
            $rank = 1;
            foreach ($sortedData as $sortedEstate) {
                $mutu_buah[$key1][$sortedEstate['estateName']][$sortedEstate['key2']]['rankAFD'] = $rank;
                $rank++;
            }




            // unset($sortedData, $sortedDataEst);
            unset($sortedData);
        }

        // dd($mutu_buah);
        foreach ($mutubuah_est as $key1 => $estates)  if (is_array($estates)) {

            $sortedDataEst = array();

            foreach ($estates as $estateName => $data) {
                if (is_array($data)) {
                    // dd($data);
                    $sortedDataEst[] = array(
                        'key1' => $key1,
                        'estateName' => $estateName,
                        'data' => $data
                    );
                }
            }

            // //mengurutkan untuk nilai estate
            usort($sortedDataEst, function ($a, $b) {
                return $b['data']['All_skor'] - $a['data']['All_skor'];
            });

            // //menambahkan nilai rank ke dalam estate
            $rank = 1;
            foreach ($sortedDataEst as $sortedest) {
                $mutubuah_est[$key1][$sortedest['estateName']]['rankEST'] = $rank;
                $rank++;
            }
            // unset($sortedData, $sortedDataEst);
            unset($sortedData);
        }
        $mutuBuah_wil = array();
        foreach ($mutubuah_est as $key => $value) {
            $jjg_sample = 0;
            $tnpBRD = 0;
            $krgBRD = 0;
            $abr = 0;
            $skor_total = 0;
            $overripe = 0;
            $empty = 0;
            $vcut = 0;
            $rd = 0;
            $sum_kr = 0;
            $allSkor = 0;
            $dataBLok = 0;
            foreach ($value as $key1 => $value1) {
                // dd($value2);
                $jjg_sample += $value1['Jumlah_janjang'];
                $tnpBRD += $value1['tnp_brd'];
                $krgBRD += $value1['krg_brd'];
                $abr += $value1['abnormal'];
                $overripe += $value1['lewat_matang'];
                $empty += $value1['janjang_kosong'];
                $vcut += $value1['vcut'];

                $rd += $value1['rat_dmg'];

                $dataBLok += $value1['blok'];
                $sum_kr += $value1['karung'];
            }

            if ($sum_kr != 0) {
                $total_kr = round($sum_kr / $dataBLok, 2);
            } else {
                $total_kr = 0;
            }
            $per_kr = round($total_kr * 100, 2);
            $skor_total = round(($jjg_sample - $abr != 0 ? (($tnpBRD + $krgBRD) / ($jjg_sample - $abr)) * 100 : 0), 2);

            $skor_jjgMSk = round(($jjg_sample - $abr != 0 ? (($jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr)) / ($jjg_sample - $abr)) * 100 : 0), 2);

            $skor_lewatMTng = round(($jjg_sample - $abr != 0 ? ($overripe / ($jjg_sample - $abr)) * 100 : 0), 2);

            $skor_jjgKosong = round(($jjg_sample - $abr != 0 ? ($empty / ($jjg_sample - $abr)) * 100 : 0), 2);

            $skor_vcut = round(($jjg_sample != 0 ? ($vcut / $jjg_sample) * 100 : 0), 2);

            $allSkor = sidak_brdTotal($skor_total) +  sidak_matangSKOR($skor_jjgMSk) +  sidak_lwtMatang($skor_lewatMTng) + sidak_jjgKosong($skor_jjgKosong) + sidak_tangkaiP($skor_vcut) + sidak_PengBRD($per_kr);


            $mutuBuah_wil[$key]['Jumlah_janjang'] = $jjg_sample;
            $mutuBuah_wil[$key]['blok'] = $dataBLok;
            switch ($key) {
                case 0:
                    $mutuBuah_wil[$key]['est'] = 'WIl-I';
                    $wil = 'WIL-I';
                    break;
                case 1:
                    $mutuBuah_wil[$key]['est'] = 'WIl-II';
                    $wil = 'WIL-II';
                    break;
                case 2:
                    $mutuBuah_wil[$key]['est'] = 'WIl-III';
                    $wil = 'WIL-III';
                    break;
                case 3:
                    $mutuBuah_wil[$key]['est'] = 'Plasma1';
                    $wil = 'Plasma1';
                    break;
                default:
                    $mutuBuah_wil[$key]['est'] = 'WIl' . $key;
                    $wil = '-';
                    break;
            }

            $wiles = $wil;

            $em = 'GM';

            $nama_em = '';

            // dd($key);
            foreach ($queryAsisten as $ast => $asisten) {
                if ($wiles === $asisten['est'] && $em === $asisten['afd']) {
                    $nama_em = $asisten['nama'];
                }
            }
            if ($jjg_sample == 0 && $tnpBRD == 0 &&   $krgBRD == 0 && $abr == 0 && $overripe == 0 && $empty == 0 &&  $vcut == 0 &&  $rd == 0 && $sum_kr == 0) {
                $mutuBuah_wil[$key]['check_arr'] = 'kosong';
                $mutuBuah_wil[$key]['All_skor'] = 0;
            } else {
                $mutuBuah_wil[$key]['check_arr'] = 'ada';
                $mutuBuah_wil[$key]['All_skor'] = $allSkor;
            }
            $mutuBuah_wil[$key]['TEST'] = $wil;
            $mutuBuah_wil[$key]['afd'] = $key1;
            $mutuBuah_wil[$key]['nama_staff'] = $nama_em;
            $mutuBuah_wil[$key]['tnp_brd'] = $tnpBRD;
            $mutuBuah_wil[$key]['krg_brd'] = $krgBRD;
            $mutuBuah_wil[$key]['persenTNP_brd'] = round(($jjg_sample - $abr != 0 ? ($tnpBRD / ($jjg_sample - $abr)) * 100 : 0), 2);
            $mutuBuah_wil[$key]['persenKRG_brd'] = round(($jjg_sample - $abr != 0 ? ($krgBRD / ($jjg_sample - $abr)) * 100 : 0), 2);
            $mutuBuah_wil[$key]['abnormal_persen'] = round(($jjg_sample != 0 ? ($abr / $jjg_sample) * 100 : 0), 2);
            $mutuBuah_wil[$key]['rd_persen'] = round(($jjg_sample != 0 ? ($rd / $jjg_sample) * 100 : 0), 2);


            $mutuBuah_wil[$key]['total_jjg'] = $tnpBRD + $krgBRD;
            $mutuBuah_wil[$key]['persen_totalJjg'] = $skor_total;
            $mutuBuah_wil[$key]['skor_total'] = sidak_brdTotal($skor_total);
            $mutuBuah_wil[$key]['jjg_matang'] = $jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr);
            $mutuBuah_wil[$key]['persen_jjgMtang'] = $skor_jjgMSk;
            $mutuBuah_wil[$key]['skor_jjgMatang'] = sidak_matangSKOR($skor_jjgMSk);
            $mutuBuah_wil[$key]['lewat_matang'] = $overripe;
            $mutuBuah_wil[$key]['persen_lwtMtng'] =  $skor_lewatMTng;
            $mutuBuah_wil[$key]['skor_lewatMTng'] = sidak_lwtMatang($skor_lewatMTng);
            $mutuBuah_wil[$key]['janjang_kosong'] = $empty;
            $mutuBuah_wil[$key]['persen_kosong'] = $skor_jjgKosong;
            $mutuBuah_wil[$key]['skor_kosong'] = sidak_jjgKosong($skor_jjgKosong);
            $mutuBuah_wil[$key]['vcut'] = $vcut;
            $mutuBuah_wil[$key]['vcut_persen'] = $skor_vcut;
            $mutuBuah_wil[$key]['vcut_skor'] = sidak_tangkaiP($skor_vcut);
            $mutuBuah_wil[$key]['abnormal'] = $abr;

            $mutuBuah_wil[$key]['rat_dmg'] = $rd;

            $mutuBuah_wil[$key]['karung'] = $sum_kr;
            $mutuBuah_wil[$key]['TPH'] = $total_kr;
            $mutuBuah_wil[$key]['persen_krg'] = $per_kr;
            $mutuBuah_wil[$key]['skor_kr'] = sidak_PengBRD($per_kr);
            // $mutuBuah_wil[$key]['All_skor'] = $allSkor;
            $mutuBuah_wil[$key]['kategori'] = sidak_akhir($allSkor);
        }

        // dd($mutuBuah_wil);
        $sortedDataEst = array();
        foreach ($mutuBuah_wil as $key1 => $estates) {
            if (is_array($estates)) {
                $sortedDataEst[] = array(
                    'key1' => $key1,
                    'data' => $estates
                );
            }
        }

        usort($sortedDataEst, function ($a, $b) {
            return $b['data']['All_skor'] - $a['data']['All_skor'];
        });

        $rank = 1;
        foreach ($sortedDataEst as $sortedest) {
            $estateKey = $sortedest['key1'];
            $mutuBuah_wil[$estateKey]['rankWil'] = $rank;
            $rank++;
        }

        unset($sortedDataEst);

        $defaultMTbh = array();


        $regional_array = [
            'Regional' => $mutuBuah_wil
        ];
        // dd($regional_array);
        $regArr = array();
        foreach ($regional_array as $key => $value) {
            $jjg_sampleEST = 0;
            $tnpBRDEST = 0;
            $krgBRDEST = 0;
            $abrEST = 0;
            $overripeEST = 0;
            $emptyEST = 0;
            $vcutEST = 0;
            $rdEST = 0;
            $sum_krEST = 0;
            $blokEST = 0;
            foreach ($value as $key1 => $value1) {
                // dd($value1);
                $jjg_sampleEST += $value1['Jumlah_janjang'];
                $blokEST += $value1['blok'];
                $tnpBRDEST +=    $value1['tnp_brd'];
                $krgBRDEST +=    $value1['krg_brd'];
                $abrEST +=    $value1['abnormal'];
                $overripeEST +=    $value1['lewat_matang'];
                $emptyEST +=    $value1['janjang_kosong'];
                $vcutEST +=    $value1['vcut'];
                $rdEST +=    $value1['rat_dmg'];
                $sum_krEST +=    $value1['karung'];
                $afds = $value1['afd'];
            }
            if ($sum_krEST != 0) {
                $total_krEST = round($sum_krEST / $blokEST, 2);
            } else {
                $total_krEST = 0;
            }
            $per_krEST = round($total_krEST * 100, 2);
            $skor_totalEST = ($jjg_sampleEST - $abrEST) !== 0 ? round((($tnpBRDEST + $krgBRDEST) / ($jjg_sampleEST - $abrEST)) * 100, 2) : 0;
            $skot_jjgmskEST = ($jjg_sampleEST - $abrEST) !== 0 ? round(($jjg_sampleEST - ($tnpBRDEST + $krgBRDEST + $overripeEST + $emptyEST)) / ($jjg_sampleEST - $abrEST) * 100, 2) : 0;
            $skor_lewatmatangEST = ($jjg_sampleEST - $abrEST) !== 0 ? round(($overripeEST / ($jjg_sampleEST - $abrEST)) * 100, 2) : 0;
            $skor_jjgKosongEST = ($jjg_sampleEST - $abrEST) !== 0 ? round(($emptyEST / ($jjg_sampleEST - $abrEST)) * 100, 2) : 0;
            $skor_vcutEST = $jjg_sampleEST !== 0 ? round(($vcutEST / $jjg_sampleEST) * 100, 2) : 0;

            $allSkorEST = sidak_brdTotal($skor_totalEST) +  sidak_matangSKOR($skot_jjgmskEST) +  sidak_lwtMatang($skor_lewatmatangEST) + sidak_jjgKosong($skor_jjgKosongEST) + sidak_tangkaiP($skor_vcutEST) + sidak_PengBRD($per_krEST);


            $em = 'RH';
            $estkey = '';
            $estkey2 = '';
            $regArr[$key]['Jumlah_janjang'] = $jjg_sampleEST;
            $regArr[$key]['blok'] = $blokEST;
            $regArr[$key]['kode'] = $afds;

            if ($afds == 'Plasma1') {
                $estkey = 'REG-I';
                $estkey2 = 'RH-I';
            } else if ($afds == 'SCE') {
                $estkey = 'REG-II';
                $estkey2 = 'RH-II';
            } else {
                $estkey = 'REG-III';
                $estkey2 = 'RH-III';
            }
            $regArr[$key]['regional'] = $estkey;
            $regArr[$key]['jabatan'] = $estkey2;
            foreach ($queryAsisten as $ast => $asisten) {
                if ($estkey === $asisten['est'] && $em === $asisten['afd']) {
                    $regArr[$key]['nama_asisten'] = $asisten['nama'];
                }
            }
            $regArr[$key]['tnp_brd'] = $tnpBRDEST;
            $regArr[$key]['krg_brd'] = $krgBRDEST;
            $regArr[$key]['persenTNP_brd'] = ($jjg_sampleEST - $abrEST) !== 0 ? round(($krgBRDEST / ($jjg_sampleEST - $abrEST)) * 100, 2) : 0;
            $regArr[$key]['persenKRG_brd'] = ($jjg_sampleEST - $abrEST) !== 0 ? round(($krgBRDEST / ($jjg_sampleEST - $abrEST)) * 100, 2) : 0;
            $regArr[$key]['total_jjg'] = $tnpBRDEST + $krgBRDEST;
            $regArr[$key]['persen_totalJjg'] = $skor_totalEST;
            $regArr[$key]['skor_totalEST'] = sidak_brdTotal($skor_totalEST);
            $regArr[$key]['jjg_matang'] = $jjg_sampleEST - ($tnpBRDEST + $krgBRDEST + $overripeEST + $emptyEST + $abrEST);
            $regArr[$key]['persen_jjgMtang'] = $skot_jjgmskEST;
            $regArr[$key]['skor_jjgMatang'] = sidak_matangSKOR($skot_jjgmskEST);
            $regArr[$key]['lewat_matang'] = $overripeEST;
            $regArr[$key]['persen_lwtMtng'] =  $skor_lewatmatangEST;
            $regArr[$key]['skor_lewatmatangEST'] = sidak_lwtMatang($skor_lewatmatangEST);
            $regArr[$key]['janjang_kosong'] = $emptyEST;
            $regArr[$key]['persen_kosong'] = $skor_jjgKosongEST;
            $regArr[$key]['skor_kosong'] = sidak_jjgKosong($skor_jjgKosongEST);
            $regArr[$key]['vcut'] = $vcutEST;
            $regArr[$key]['vcut_persen'] = $skor_vcutEST;
            $regArr[$key]['vcut_skor'] = sidak_tangkaiP($skor_vcutEST);
            $regArr[$key]['abnormal'] = $abrEST;
            $regArr[$key]['abnormal_persen'] = $jjg_sampleEST !== 0 ? round(($abrEST / $jjg_sampleEST) * 100, 2) : 0;
            $regArr[$key]['rat_dmg'] = $rdEST;
            $regArr[$key]['rd_persen'] = $jjg_sampleEST !== 0 ? round(($rdEST / $jjg_sampleEST) * 100, 2) : 0;
            $regArr[$key]['karung'] = $sum_krEST;
            $regArr[$key]['TPH'] = $total_krEST;
            $regArr[$key]['persen_krg'] = $per_krEST;
            $regArr[$key]['skor_kr'] = sidak_PengBRD($per_krEST);
            $regArr[$key]['all_skorYear'] = $allSkorEST;
            $regArr[$key]['kategori'] = sidak_akhir($allSkorEST);
        }
        // dd($regArr);
        $queryAsisten = DB::connection('mysql2')->table('asisten_qc')
            ->select('asisten_qc.*')
            ->get();

        $queryAsisten = json_decode($queryAsisten, true);


        // latihan
        // dd($startDate, $endDate);

        $queryEstv1 = DB::connection('mysql2')->table('estate')
            ->select('estate.*')
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->where('wil.regional', $regional)
            ->whereNotIn('estate.est', ['SRE', 'LDE', 'SKE', 'CWS1'])
            ->pluck('est');
        $queryEstv1 = json_decode($queryEstv1, true);

        $sbi_est = $request->input('selectedEstateText');

        $optionREg = DB::connection('mysql2')->table('reg')
            ->select('reg.*')
            ->where('reg.id', $regional)
            // ->where('wil.regional', 1)
            ->get();


        $optionReg = json_decode($optionREg, true);
        $regional = array();
        foreach ($optionReg as $key => $value) {
            $value['nama'] = str_replace('Regional', 'Reg-', $value['nama']);
            $value['nama'] = str_replace(' ', '', $value['nama']);
            $value['nama'] = strtoupper($value['nama']);
            $regional[$key] = $value;
            $regional[$key]['jabatan'] = 'RH-' . substr($value['nama'], strpos($value['nama'], 'Reg-') + strlen('Reg-'));
        }

        foreach ($regional as $key => $value) {
            $regional[$key]['nama_rh'] = '-';
            foreach ($queryAsisten as $ast => $asisten) {
                // dd($value['nama']);
                if ($asisten['est'] == $value['nama'] && $asisten['afd'] == 'RH') {
                    $regional[$key]['nama_rh'] = $asisten['nama'];
                    break; // exit the inner loop since a match is found
                }
            }
        }

        updateKeyRecursive($mutu_buah, "KTE4", "KTE");


        // Change key "KTE4" to "KTE"
        updateKeyRecursive3($mutubuah_est[0], "KTE4", "KTE");
        $estev2 = updateKeyRecursive2($estev2);


        $arrView = array();

        $arrView['listregion'] =  $estev2;
        $arrView['list_est'] =  $queryEstv1;
        $arrView['mutu_buah'] =  $mutu_buah;
        $arrView['mutubuah_est'] =  $mutubuah_est;
        $arrView['mutuBuah_wil'] =  $mutuBuah_wil;
        $arrView['regional'] =  $regArr;
        $arrView['queryAsisten'] =  $queryAsisten;
        $arrView['regionaltab'] =  $regional;



        echo json_encode($arrView); //di decode ke dalam bentuk json dalam vaiavel arrview yang dapat menampung banyak isi array
        exit();
    }

    public function chartsbi_oke(Request $request)
    {

        // dd($sbi_est);
        // dd($queryAfd);

        $Est = $request->input('estText');
        $year = $request->input('tahun');



        // dd($Est, $year);

        $queryAfd = DB::connection('mysql2')->table('afdeling')
            ->select(
                'afdeling.id',
                'afdeling.nama',
                'estate.est'
            ) //buat mengambil data di estate db dan willayah db
            ->join('estate', 'estate.id', '=', 'afdeling.estate') //kemudian di join untuk mengambil est perwilayah
            ->get();
        $queryAfd = json_decode($queryAfd, true);

        $queryAsisten = DB::connection('mysql2')->table('asisten_qc')
            ->select('asisten_qc.*')
            ->get();

        $queryAsisten = json_decode($queryAsisten, true);



        $queryMTbuah = DB::connection('mysql2')->table('sidak_mutu_buah')
            ->select(
                "sidak_mutu_buah.*",
                DB::raw('DATE_FORMAT(sidak_mutu_buah.datetime, "%M") as bulan'),
                DB::raw('DATE_FORMAT(sidak_mutu_buah.datetime, "%Y") as tahun')
            )
            ->where('sidak_mutu_buah.estate', '=', $Est)
            ->whereYear('datetime', $year)
            ->get();
        $queryMTbuah = $queryMTbuah->groupBy(['estate', 'afdeling']);
        $queryMTbuah = json_decode($queryMTbuah, true);


        $bulan = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        // dd($bulan);

        // Create a mapping of estate to their respective afdelings
        $estateAfdelings = [];
        foreach ($queryAfd as $afdeling) {
            $estateAfdelings[$afdeling['est']][] = $afdeling['nama'];
        }

        // Initialize dataPerBulan with default values (0)
        $dataPerBulan = array();
        foreach ($queryMTbuah as $estate => $afdelingData) {
            if (isset($estateAfdelings[$estate])) {
                foreach ($bulan as $month) {
                    foreach ($estateAfdelings[$estate] as $afdeling) {
                        $dataPerBulan[$estate][$month][$afdeling] = [];
                    }
                }
            }
        }

        //mutu ancak membuat nilai berdasrakan bulan
        foreach ($queryMTbuah as $key => $value) {
            foreach ($value as $key2 => $value2) {
                foreach ($value2 as $key3 => $value3) {
                    $month = date('F', strtotime($value3['datetime']));

                    $dataPerBulan[$key][$month][$key2][] = $value3; // Append the data instead of overwriting it
                }
            }
        }

        // dd($dataPerBulan);

        // dd($defaultNew);
        $sidak_buah = array();
        foreach ($dataPerBulan as $key => $value) if (isEmpty($value)) {
            $jjg_sampleYear = 0;
            $tnpBRDYear = 0;
            $krgBRDYear = 0;
            $abrYear = 0;
            $overripeYear = 0;
            $emptyYear = 0;
            $vcutYear = 0;
            $rdYear = 0;
            $sum_krYear = 0;
            $blokYear = 0;
            foreach ($value as $key1 => $value1) if (isEmpty($value1)) {
                $jjg_sampleEST = 0;
                $tnpBRDEST = 0;
                $krgBRDEST = 0;
                $abrEST = 0;
                $overripeEST = 0;
                $emptyEST = 0;
                $vcutEST = 0;
                $rdEST = 0;
                $sum_krEST = 0;
                $blokEST = 0;
                foreach ($value1 as $key2 => $value2)   if (is_array($value2)) {
                    $jjg_sample = 0;
                    $tnpBRD = 0;
                    $krgBRD = 0;
                    $abr = 0;
                    $skor_total = 0;
                    $overripe = 0;
                    $empty = 0;
                    $vcut = 0;
                    $rd = 0;
                    $sum_kr = 0;
                    $allSkor = 0;
                    $combination_counts = array();

                    foreach ($value2 as $key3 => $value3) {
                        $combination = $value3['blok'] . ' ' . $value3['estate'] . ' ' . $value3['afdeling'] . ' ' . $value3['tph_baris'];
                        if (!isset($combination_counts[$combination])) {
                            $combination_counts[$combination] = 0;
                        }
                        $jjg_sample += $value3['jumlah_jjg'];
                        $tnpBRD += $value3['bmt'];
                        $krgBRD += $value3['bmk'];
                        $abr += $value3['abnormal'];
                        $overripe += $value3['overripe'];
                        $empty += $value3['empty_bunch'];
                        $vcut += $value3['vcut'];
                        $rd += $value3['rd'];
                        $sum_kr += $value3['alas_br'];
                    }
                    $dataBLok = count($combination_counts);
                    if ($sum_kr != 0) {
                        $total_kr = round($sum_kr / $dataBLok, 2);
                    } else {
                        $total_kr = 0;
                    }
                    $per_kr = round($total_kr * 100, 2);
                    $skor_total = round((($tnpBRD + $krgBRD) / (($jjg_sample - $abr) != 0 ? ($jjg_sample - $abr) : 1)) * 100, 2);
                    $skor_jjgMSk = round((($jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr)) / (($jjg_sample - $abr) != 0 ? ($jjg_sample - $abr) : 1)) * 100, 2);
                    $skor_lewatMTng = round(($overripe / (($jjg_sample - $abr) != 0 ? ($jjg_sample - $abr) : 1)) * 100, 2);
                    $skor_jjgKosong = round(($empty / (($jjg_sample - $abr) != 0 ? ($jjg_sample - $abr) : 1)) * 100, 2);
                    $skor_vcut = round(($vcut / ($jjg_sample != 0 ? $jjg_sample : 1)) * 100, 2);

                    $allSkor = sidak_brdTotal($skor_total) +  sidak_matangSKOR($skor_jjgMSk) +  sidak_lwtMatang($skor_lewatMTng) + sidak_jjgKosong($skor_jjgKosong) + sidak_tangkaiP($skor_vcut) + sidak_PengBRD($per_kr);

                    $sidak_buah[$key][$key1][$key2]['Jumlah_janjang'] = $jjg_sample;
                    $sidak_buah[$key][$key1][$key2]['blok'] = $dataBLok;
                    $sidak_buah[$key][$key1][$key2]['est'] = $key;
                    $sidak_buah[$key][$key1][$key2]['afd'] = $key1;
                    $sidak_buah[$key][$key1][$key2]['nama_staff'] = '-';
                    $sidak_buah[$key][$key1][$key2]['tnp_brd'] = $tnpBRD;
                    $sidak_buah[$key][$key1][$key2]['krg_brd'] = $krgBRD;
                    $sidak_buah[$key][$key1][$key2]['persenTNP_brd'] = round(($jjg_sample - $abr != 0) ? ($tnpBRD / ($jjg_sample - $abr)) * 100 : 0, 2);
                    $sidak_buah[$key][$key1][$key2]['persenKRG_brd'] = round(($jjg_sample - $abr != 0) ? ($krgBRD / ($jjg_sample - $abr)) * 100 : 0, 2);

                    $sidak_buah[$key][$key1][$key2]['total_jjg'] = $tnpBRD + $krgBRD;
                    $sidak_buah[$key][$key1][$key2]['persen_totalJjg'] = $skor_total;
                    $sidak_buah[$key][$key1][$key2]['skor_total'] = sidak_brdTotal($skor_total);
                    $sidak_buah[$key][$key1][$key2]['jjg_matang'] = $jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr);
                    $sidak_buah[$key][$key1][$key2]['persen_jjgMtang'] = $skor_jjgMSk;
                    $sidak_buah[$key][$key1][$key2]['skor_jjgMatang'] = sidak_matangSKOR($skor_jjgMSk);
                    $sidak_buah[$key][$key1][$key2]['lewat_matang'] = $overripe;
                    $sidak_buah[$key][$key1][$key2]['persen_lwtMtng'] =  $skor_lewatMTng;
                    $sidak_buah[$key][$key1][$key2]['skor_lewatMTng'] = sidak_lwtMatang($skor_lewatMTng);
                    $sidak_buah[$key][$key1][$key2]['janjang_kosong'] = $empty;
                    $sidak_buah[$key][$key1][$key2]['persen_kosong'] = $skor_jjgKosong;
                    $sidak_buah[$key][$key1][$key2]['skor_kosong'] = sidak_jjgKosong($skor_jjgKosong);
                    $sidak_buah[$key][$key1][$key2]['vcut'] = $vcut;
                    $sidak_buah[$key][$key1][$key2]['vcut_persen'] = $skor_vcut;
                    $sidak_buah[$key][$key1][$key2]['vcut_skor'] = sidak_tangkaiP($skor_vcut);
                    $sidak_buah[$key][$key1][$key2]['abnormal'] = $abr;
                    $sidak_buah[$key][$key1][$key2]['abnormal_persen'] = round(($jjg_sample != 0) ? ($abr / $jjg_sample) * 100 : 0, 2);

                    $sidak_buah[$key][$key1][$key2]['rat_dmg'] = $rd;
                    $sidak_buah[$key][$key1][$key2]['rd_persen'] = round(($jjg_sample != 0) ? ($rd / $jjg_sample) * 100 : 0, 2);

                    $sidak_buah[$key][$key1][$key2]['karung'] = $sum_kr;
                    $sidak_buah[$key][$key1][$key2]['TPH'] = $total_kr;
                    $sidak_buah[$key][$key1][$key2]['persen_krg'] = $per_kr;
                    $sidak_buah[$key][$key1][$key2]['skor_kr'] = sidak_PengBRD($per_kr);
                    $sidak_buah[$key][$key1][$key2]['All_skor'] = $allSkor;
                    $sidak_buah[$key][$key1][$key2]['kategori'] = sidak_akhir($allSkor);


                    foreach ($queryAsisten as $ast => $asisten) {
                        if ($key === $asisten['est'] && $key2 === $asisten['afd']) {
                            $sidak_buah[$key][$key1][$key2]['nama_asisten'] = $asisten['nama'];
                        }
                    }

                    $jjg_sampleEST += $jjg_sample;
                    $blokEST += $dataBLok;
                    $tnpBRDEST +=    $tnpBRD;
                    $krgBRDEST +=    $krgBRD;
                    $abrEST +=    $abr;
                    $overripeEST +=    $overripe;
                    $emptyEST +=    $empty;
                    $vcutEST +=    $vcut;
                    $rdEST +=    $rd;
                    $sum_krEST +=    $sum_kr;
                } else {
                    $sidak_buah[$key][$key1][$key2]['Jumlah_janjang'] = 0;
                    $sidak_buah[$key][$key1][$key2]['blok'] = 0;
                    $sidak_buah[$key][$key1][$key2]['est'] = $key;
                    $sidak_buah[$key][$key1][$key2]['afd'] = $key1;
                    $sidak_buah[$key][$key1][$key2]['nama_staff'] = '-';
                    $sidak_buah[$key][$key1][$key2]['tnp_brd'] = 0;
                    $sidak_buah[$key][$key1][$key2]['krg_brd'] = 0;
                    $sidak_buah[$key][$key1][$key2]['persenTNP_brd'] = 0;
                    $sidak_buah[$key][$key1][$key2]['persenKRG_brd'] = 0;
                    $sidak_buah[$key][$key1][$key2]['total_jjg'] = 0;
                    $sidak_buah[$key][$key1][$key2]['persen_totalJjg'] = 0;
                    $sidak_buah[$key][$key1][$key2]['skor_total'] = 0;
                    $sidak_buah[$key][$key1][$key2]['jjg_matang'] = 0;
                    $sidak_buah[$key][$key1][$key2]['persen_jjgMtang'] = 0;
                    $sidak_buah[$key][$key1][$key2]['skor_jjgMatang'] = 0;
                    $sidak_buah[$key][$key1][$key2]['lewat_matang'] = 0;
                    $sidak_buah[$key][$key1][$key2]['persen_lwtMtng'] =  0;
                    $sidak_buah[$key][$key1][$key2]['skor_lewatMTng'] = 0;
                    $sidak_buah[$key][$key1][$key2]['janjang_kosong'] = 0;
                    $sidak_buah[$key][$key1][$key2]['persen_kosong'] = 0;
                    $sidak_buah[$key][$key1][$key2]['skor_kosong'] = 0;
                    $sidak_buah[$key][$key1][$key2]['vcut'] = 0;
                    $sidak_buah[$key][$key1][$key2]['vcut_persen'] = 0;
                    $sidak_buah[$key][$key1][$key2]['vcut_skor'] = 0;
                    $sidak_buah[$key][$key1][$key2]['abnormal'] = 0;
                    $sidak_buah[$key][$key1][$key2]['abnormal_persen'] = 0;
                    $sidak_buah[$key][$key1][$key2]['rat_dmg'] = 0;
                    $sidak_buah[$key][$key1][$key2]['rd_persen'] = 0;
                    $sidak_buah[$key][$key1][$key2]['karung'] = 0;
                    $sidak_buah[$key][$key1][$key2]['TPH'] = 0;
                    $sidak_buah[$key][$key1][$key2]['persen_krg'] = 0;
                    $sidak_buah[$key][$key1][$key2]['skor_kr'] = 0;
                    $sidak_buah[$key][$key1][$key2]['All_skor'] = 0;
                    $sidak_buah[$key][$key1][$key2]['kategori'] = 0;

                    foreach ($queryAsisten as $ast => $asisten) {
                        if ($key === $asisten['est'] && $key2 === $asisten['afd']) {
                            $sidak_buah[$key][$key1][$key2]['nama_asisten'] = $asisten['nama'];
                        }
                    }
                }

                if ($sum_krEST != 0) {
                    $total_krEST = round($sum_krEST / $blokEST, 2);
                } else {
                    $total_krEST = 0;
                }
                $per_krEST = round($total_krEST * 100, 2);
                $skor_totalEST = ($jjg_sampleEST - $abrEST) !== 0 ? round((($tnpBRDEST + $krgBRDEST) / ($jjg_sampleEST - $abrEST)) * 100, 2) : 0;
                $skot_jjgmskEST = ($jjg_sampleEST - $abrEST) !== 0 ? round(($jjg_sampleEST - ($tnpBRDEST + $krgBRDEST + $overripeEST + $emptyEST)) / ($jjg_sampleEST - $abrEST) * 100, 2) : 0;
                $skor_lewatmatangEST = ($jjg_sampleEST - $abrEST) !== 0 ? round(($overripeEST / ($jjg_sampleEST - $abrEST)) * 100, 2) : 0;
                $skor_jjgKosongEST = ($jjg_sampleEST - $abrEST) !== 0 ? round(($emptyEST / ($jjg_sampleEST - $abrEST)) * 100, 2) : 0;
                $skor_vcutEST = $jjg_sampleEST !== 0 ? round(($vcutEST / $jjg_sampleEST) * 100, 2) : 0;

                $allSkorEST = sidak_brdTotal($skor_totalEST) +  sidak_matangSKOR($skot_jjgmskEST) +  sidak_lwtMatang($skor_lewatmatangEST) + sidak_jjgKosong($skor_jjgKosongEST) + sidak_tangkaiP($skor_vcutEST) + sidak_PengBRD($per_krEST);


                $em = 'EM';
                $sidak_buah[$key][$key1]['Jumlah_janjang'] = $jjg_sampleEST;
                $sidak_buah[$key][$key1]['blok'] = $blokEST;

                $sidak_buah[$key][$key1]['kode'] = $key;

                foreach ($queryAsisten as $ast => $asisten) {
                    if ($key === $asisten['est'] && $em === $asisten['afd']) {
                        $sidak_buah[$key][$key1]['nama_asisten'] = $asisten['nama'];
                    }
                }
                $sidak_buah[$key][$key1]['tnp_brd'] = $tnpBRDEST;
                $sidak_buah[$key][$key1]['krg_brd'] = $krgBRDEST;
                $sidak_buah[$key][$key1]['persenTNP_brd'] = ($jjg_sampleEST - $abrEST) !== 0 ? round(($krgBRDEST / ($jjg_sampleEST - $abrEST)) * 100, 2) : 0;
                $sidak_buah[$key][$key1]['persenKRG_brd'] = ($jjg_sampleEST - $abrEST) !== 0 ? round(($krgBRDEST / ($jjg_sampleEST - $abrEST)) * 100, 2) : 0;
                $sidak_buah[$key][$key1]['total_jjg'] = $tnpBRDEST + $krgBRDEST;
                $sidak_buah[$key][$key1]['persen_totalJjg'] = $skor_totalEST;
                $sidak_buah[$key][$key1]['skor_totalEST'] = sidak_brdTotal($skor_totalEST);
                $sidak_buah[$key][$key1]['jjg_matang'] = $jjg_sampleEST - ($tnpBRDEST + $krgBRDEST + $overripeEST + $emptyEST + $abrEST);
                $sidak_buah[$key][$key1]['persen_jjgMtang'] = $skot_jjgmskEST;
                $sidak_buah[$key][$key1]['skor_jjgMatang'] = sidak_matangSKOR($skot_jjgmskEST);
                $sidak_buah[$key][$key1]['lewat_matang'] = $overripeEST;
                $sidak_buah[$key][$key1]['persen_lwtMtng'] =  $skor_lewatmatangEST;
                $sidak_buah[$key][$key1]['skor_lewatmatangEST'] = sidak_lwtMatang($skor_lewatmatangEST);
                $sidak_buah[$key][$key1]['janjang_kosong'] = $emptyEST;
                $sidak_buah[$key][$key1]['persen_kosong'] = $skor_jjgKosongEST;
                $sidak_buah[$key][$key1]['skor_kosong'] = sidak_jjgKosong($skor_jjgKosongEST);
                $sidak_buah[$key][$key1]['vcut'] = $vcutEST;
                $sidak_buah[$key][$key1]['vcut_persen'] = $skor_vcutEST;
                $sidak_buah[$key][$key1]['vcut_skor'] = sidak_tangkaiP($skor_vcutEST);
                $sidak_buah[$key][$key1]['abnormal'] = $abrEST;
                $sidak_buah[$key][$key1]['abnormal_persen'] = $jjg_sampleEST !== 0 ? round(($abrEST / $jjg_sampleEST) * 100, 2) : 0;
                $sidak_buah[$key][$key1]['rat_dmg'] = $rdEST;
                $sidak_buah[$key][$key1]['rd_persen'] = $jjg_sampleEST !== 0 ? round(($rdEST / $jjg_sampleEST) * 100, 2) : 0;
                $sidak_buah[$key][$key1]['karung'] = $sum_krEST;
                $sidak_buah[$key][$key1]['TPH'] = $total_krEST;
                $sidak_buah[$key][$key1]['persen_krg'] = $per_krEST;
                $sidak_buah[$key][$key1]['skor_kr'] = sidak_PengBRD($per_krEST);
                $sidak_buah[$key][$key1]['All_skor'] = $allSkorEST;
                $sidak_buah[$key][$key1]['kategori'] = sidak_akhir($allSkorEST);
            } else {
                $sidak_buah[$key][$key1]['Jumlah_janjang'] = 0;
                $sidak_buah[$key][$key1]['blok'] = 0;
                $sidak_buah[$key][$key1]['tnp_brd'] = 0;
                $sidak_buah[$key][$key1]['krg_brd'] = 0;
                $sidak_buah[$key][$key1]['persenTNP_brd'] = 0;
                $sidak_buah[$key][$key1]['persenKRG_brd'] = 0;
                $sidak_buah[$key][$key1]['total_jjg'] = 0;
                $sidak_buah[$key][$key1]['persen_totalJjg'] = 0;
                $sidak_buah[$key][$key1]['skor_totalEST'] = 0;
                $sidak_buah[$key][$key1]['jjg_matang'] = 0;
                $sidak_buah[$key][$key1]['persen_jjgMtang'] = 0;
                $sidak_buah[$key][$key1]['skor_jjgMatang'] = 0;
                $sidak_buah[$key][$key1]['lewat_matang'] = 0;
                $sidak_buah[$key][$key1]['persen_lwtMtng'] =  0;
                $sidak_buah[$key][$key1]['skor_lewatmatangEST'] = 0;
                $sidak_buah[$key][$key1]['janjang_kosong'] = 0;
                $sidak_buah[$key][$key1]['persen_kosong'] = 0;
                $sidak_buah[$key][$key1]['skor_kosong'] = 0;
                $sidak_buah[$key][$key1]['vcut'] = 0;
                $sidak_buah[$key][$key1]['vcut_persen'] = 0;
                $sidak_buah[$key][$key1]['vcut_skor'] = 0;
                $sidak_buah[$key][$key1]['abnormal'] = 0;
                $sidak_buah[$key][$key1]['abnormal_persen'] = 0;
                $sidak_buah[$key][$key1]['rat_dmg'] = 0;
                $sidak_buah[$key][$key1]['rd_persen'] = 0;
                $sidak_buah[$key][$key1]['karung'] = 0;
                $sidak_buah[$key][$key1]['TPH'] = 0;
                $sidak_buah[$key][$key1]['persen_krg'] = 0;
                $sidak_buah[$key][$key1]['skor_kr'] = 0;
                $sidak_buah[$key][$key1]['All_skor'] = 0;
                $sidak_buah[$key][$key1]['kategori'] = 0;
            }
        }



        $result = $sidak_buah;
        $persen_jjgMtang_values = array();
        $persen_jjgmentah_values = array();
        $persen_lwtMtng_values = array();
        $persen_kosong_values = array();
        $vcut_persen_values = array();
        $TPH_values = array();

        foreach ($result as $estate => $estateData) {
            foreach ($estateData as $month => $monthData) {
                $persen_jjgMtang_values[$estate][$month] = $monthData['persen_jjgMtang'] ?? 0;
                $persen_jjgmentah_values[$estate][$month] = $monthData['persen_jjgmentah'] ?? 0;
                $persen_lwtMtng_values[$estate][$month] = $monthData['persen_lwtMtng'] ?? 0;
                $persen_kosong_values[$estate][$month] = $monthData['persen_kosong'] ?? 0;
                $vcut_persen_values[$estate][$month] = $monthData['vcut_persen'] ?? 0;
                $TPH_values[$estate][$month] = $monthData['TPH'] ?? 0;
            }
        }

        // dd($persen_jjgMtang_values);
        $arrView = array();
        $arrView['chart_matang'] =  $persen_jjgMtang_values;
        $arrView['chart_mentah'] =  $persen_jjgmentah_values;
        $arrView['chart_lewatmatang'] =  $persen_lwtMtng_values;
        $arrView['chart_janjangkosong'] =  $persen_kosong_values;
        $arrView['chart_vcut'] =  $vcut_persen_values;
        $arrView['chart_karung'] =  $TPH_values;


        echo json_encode($arrView); //di decode ke dalam bentuk json dalam vaiavel arrview yang dapat menampung banyak isi array
        exit();
    }

    public function detailtmutubuah($est, $afd, $bulan)
    {

        // $ancakDates = DB::connection('mysql2')->table('mutu_ancak_new')
        //     ->select(DB::raw('DATE(mutu_ancak_new.datetime) as date'))
        //     ->where('mutu_ancak_new.estate', $est)
        //     ->where('mutu_ancak_new.afdeling', $afd)
        //     ->whereRaw("MONTH(mutu_ancak_new.datetime) = $selectedMonth")
        //     ->whereRaw("YEAR(mutu_ancak_new.datetime) = $selectedYear")
        //     ->distinct()
        //     ->orderBy('date', 'asc')
        //     ->get();

        $queryMTbuah = DB::connection('mysql2')->table('sidak_mutu_buah')
            ->select(
                "sidak_mutu_buah.*",
                DB::raw('DATE_FORMAT(sidak_mutu_buah.datetime, "%M") as bulan'),
                DB::raw('DATE_FORMAT(sidak_mutu_buah.datetime, "%Y") as tahun')
            )
            ->where('sidak_mutu_buah.estate', $est)
            ->where('sidak_mutu_buah.afdeling', $afd)
            ->where('sidak_mutu_buah.datetime', 'like', '%' . $bulan . '%')

            // ->whereBetween('sidak_mutu_buah.datetime', ['2023-04-03', '2023-04-09'])
            ->get();
        $queryMTbuah = $queryMTbuah->groupBy(['estate', 'afdeling']);
        $queryMTbuah = json_decode($queryMTbuah, true);

        $dates = [];

        foreach ($queryMTbuah as $estateGroup) {
            foreach ($estateGroup as $afdelingGroup) {
                foreach ($afdelingGroup as $data) {
                    $datetime = new DateTime($data['datetime']);
                    $date = $datetime->format('Y-m-d');
                    if (!in_array($date, $dates)) {
                        $dates[] = $date;
                    }
                }
            }
        }

        // dd($dates);
        // $ancakDatesArray = $ancakDates->pluck('date')->toArray();
        // $buahDatesArray = $buahDates->pluck('date')->toArray();
        // $TransportDatesArray = $TransportDates->pluck('date')->toArray();

        // $commonDates = collect([]);
        // foreach ($ancakDatesArray as $date) {
        //     if (in_array($date, $buahDatesArray) && in_array($date, $TransportDatesArray)) {
        //         $commonDates->push((object) ['date' => $date]);
        //     }
        // }

        $arrView = array();
        $arrView['query'] = $queryMTbuah;
        $arrView['est'] = $est;
        $arrView['afd'] = $afd;
        $arrView['bulan'] = $bulan;
        $arrView['tanggal'] = $dates;
        // $arrView['TransportDates'] = $TransportDates;
        // $arrView['est'] =  $est;
        // $arrView['afd'] =  $afd;
        // $arrView['tanggal'] =  $date;
        json_encode($arrView);

        return view('detailtmutubuah', $arrView);
    }

    public function filterdetialMutubuah(Request $request)
    {

        // $bulan = $request->input('bulan');
        $Reg = $request->input('est');
        $afd = $request->input('afd');
        $tanggal = $request->input('tanggal');

        // dd($tanggal);

        $mutuAncak = DB::connection('mysql2')->table('sidak_mutu_buah')
            ->select("sidak_mutu_buah.*", DB::raw('DATE_FORMAT(sidak_mutu_buah.datetime, "%M") as bulan'), DB::raw('DATE_FORMAT(sidak_mutu_buah.datetime, "%Y") as tahun'))
            ->where('sidak_mutu_buah.datetime', 'like', '%' . $tanggal . '%')

            ->where('sidak_mutu_buah.estate', $Reg)
            ->where('sidak_mutu_buah.afdeling', $afd)
            ->get();
        // $mutuAncak = $mutuAncak->groupBy(['blok']);
        $mutuAncak = json_decode($mutuAncak, true);


        // dd($mutuAncak);


        $arrView = array();

        $arrView['mutuAncak'] =  $mutuAncak;
        $arrView['tanggal'] =  $tanggal;
        echo json_encode($arrView);
        exit();
    }

    public function updateBA_mutubuah(Request $request)
    {

        $est = $request->input('est');
        $afd = $request->input('afd');
        $date = $request->input('date');

        // dd($date, $afd, $est);
        // mutu ancak 
        $id = $request->input('id');
        $blok = $request->input('blokCak');
        $petugas = $request->input('sph');
        $Tph_baris = $request->input('br1');
        $ancak_pemanen = $request->input('br2');
        $jml_jjg = $request->input('sampCak');
        $bmt = $request->input('pkKuning');
        $bmk = $request->input('prSmk');
        $overripe = $request->input('undrPR');
        $empty = $request->input('overPR');
        $abnormal = $request->input('jjgCak');
        $rd = $request->input('brtp');
        $vcut = $request->input('brtk');
        $alas_br = $request->input('brtgl');



        // dd($id, $blok, $petugas, $ancak_pemanen, $overripe);

        DB::connection('mysql2')->table('sidak_mutu_buah')->where('id', $id)->update([
            'blok' => $blok,
            'petugas' => $petugas,
            'tph_baris' => $Tph_baris,
            'ancak_pemanen' => $ancak_pemanen,
            'jumlah_jjg' => $jml_jjg,
            'bmt' => $bmt,
            'bmk' => $bmk,
            'overripe' => $overripe,
            'empty_bunch' => $empty,
            'abnormal' => $abnormal,
            'rd' => $rd,
            'vcut' => $vcut,
            'alas_br' => $alas_br,
        ]);
    }

    public function deleteBA_mutubuah(Request $request)
    {

        $id = $request->input('id');
        // dd($id);
        //mutu ancak
        DB::connection('mysql2')->table('sidak_mutu_buah')->where('id', $request->input('id'))->delete();
        //mutu buah

        return response()->json(['status' => 'success']);
    }

    public function pdfBA_sidakbuah(Request $request)
    {
        $est = $request->input('estBA');
        $afd = $request->input('afdBA');
        $date = $request->input('tglPDF');

        $mutuAncak = DB::connection('mysql2')->table('sidak_mutu_buah')
            ->select("sidak_mutu_buah.*", DB::raw('DATE_FORMAT(sidak_mutu_buah.datetime, "%M") as bulan'), DB::raw('DATE_FORMAT(sidak_mutu_buah.datetime, "%Y") as tahun'))
            ->where('datetime', 'like', '%' . $date . '%')
            ->where('sidak_mutu_buah.estate', $est)
            ->where('sidak_mutu_buah.afdeling', $afd)

            ->get();
        $mutuAncak = $mutuAncak->groupBy(['blok']);
        $mutuAncak = json_decode($mutuAncak, true);

        // dd($mutuAncak);

        // dd($defPerbulanWil);
        $sidak_buah = array();
        $bloks = 0;
        foreach ($mutuAncak as $key => $value) {
            $jjg_sample = 0;
            $tnpBRD = 0;
            $krgBRD = 0;
            $abr = 0;
            $skor_total = 0;
            $overripe = 0;
            $empty = 0;
            $vcut = 0;
            $rd = 0;
            $sum_kr = 0;
            $allSkor = 0;

            $combination_counts = array();
            $bloks = count($value);
            foreach ($value as $key1 => $value1) {
                // dd($key1);

                $jjg_sample += $value1['jumlah_jjg'];
                $tnpBRD += $value1['bmt'];
                $krgBRD += $value1['bmk'];
                $abr += $value1['abnormal'];
                $overripe += $value1['overripe'];
                $empty += $value1['empty_bunch'];
                $vcut += $value1['vcut'];
                $rd += $value1['rd'];
                $sum_kr += $value1['alas_br'];
            }

            $dataBLok = $bloks;
            if ($sum_kr != 0) {
                $total_kr = round($sum_kr / $dataBLok, 2);
            } else {
                $total_kr = 0;
            }
            $per_kr = round($total_kr * 100, 2);
            $skor_total = round((($tnpBRD + $krgBRD) / ($jjg_sample - $abr)) * 100, 2);
            $skor_jjgMSk = round(($jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr)) / ($jjg_sample - $abr) * 100, 2);
            $skor_lewatMTng =  round(($overripe / ($jjg_sample - $abr)) * 100, 2);
            $skor_jjgKosong =  round(($empty / ($jjg_sample - $abr)) * 100, 2);
            $skor_vcut =   round(($vcut / $jjg_sample) * 100, 2);
            $allSkor = sidak_brdTotal($skor_total) +  sidak_matangSKOR($skor_jjgMSk) +  sidak_lwtMatang($skor_lewatMTng) + sidak_jjgKosong($skor_jjgKosong) + sidak_tangkaiP($skor_vcut) + sidak_PengBRD($per_kr);

            $sidak_buah[$key]['Jumlah_janjang'] = $jjg_sample;
            $sidak_buah[$key]['blok'] = $dataBLok;
            $sidak_buah[$key]['est'] = $key;
            $sidak_buah[$key]['afd'] = $value1['afdeling'];
            $sidak_buah[$key]['tnp_brd'] = $tnpBRD;
            $sidak_buah[$key]['krg_brd'] = $krgBRD;
            $sidak_buah[$key]['persenTNP_brd'] = round(($tnpBRD / ($jjg_sample - $abr)) * 100, 2);
            $sidak_buah[$key]['persenKRG_brd'] = round(($krgBRD / ($jjg_sample - $abr)) * 100, 2);
            $sidak_buah[$key]['total_jjg'] = $tnpBRD + $krgBRD;
            $sidak_buah[$key]['persen_totalJjg'] = $skor_total;
            $sidak_buah[$key]['skor_total'] = sidak_brdTotal($skor_total);
            $sidak_buah[$key]['jjg_matang'] = $jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr);
            $sidak_buah[$key]['persen_jjgMtang'] = $skor_jjgMSk;
            $sidak_buah[$key]['skor_jjgMatang'] = sidak_matangSKOR($skor_jjgMSk);
            $sidak_buah[$key]['lewat_matang'] = $overripe;
            $sidak_buah[$key]['persen_lwtMtng'] =  $skor_lewatMTng;
            $sidak_buah[$key]['skor_lewatMTng'] = sidak_lwtMatang($skor_lewatMTng);
            $sidak_buah[$key]['janjang_kosong'] = $empty;
            $sidak_buah[$key]['persen_kosong'] = $skor_jjgKosong;
            $sidak_buah[$key]['skor_kosong'] = sidak_jjgKosong($skor_jjgKosong);
            $sidak_buah[$key]['vcut'] = $vcut;
            $sidak_buah[$key]['karung'] = $sum_kr;
            $sidak_buah[$key]['vcut_persen'] = $skor_vcut;
            $sidak_buah[$key]['vcut_skor'] = sidak_tangkaiP($skor_vcut);
            $sidak_buah[$key]['abnormal'] = $abr;
            $sidak_buah[$key]['abnormal_persen'] = round(($abr / $jjg_sample) * 100, 2);
            $sidak_buah[$key]['rat_dmg'] = $rd;
            $sidak_buah[$key]['rd_persen'] = round(($rd / $jjg_sample) * 100, 2);
            $sidak_buah[$key]['TPH'] = $total_kr;
            $sidak_buah[$key]['persen_krg'] = $per_kr;
            $sidak_buah[$key]['skor_kr'] = sidak_PengBRD($per_kr);
            $sidak_buah[$key]['All_skor'] = $allSkor;
            $sidak_buah[$key]['kategori'] = sidak_akhir($allSkor);
        }
        // dd($sidak_buah);

        $total_buah = array();
        $blok = 0;
        $jjg_sample = 0;
        $tnpBRD = 0;
        $krgBRD = 0;
        $abr = 0;
        $overripe = 0;
        $empty = 0;
        $vcut = 0;
        $rd = 0;
        $sum_kr = 0;
        foreach ($sidak_buah as $key => $value) {
            $jjg_sample += $value['Jumlah_janjang'];
            $tnpBRD += $value['tnp_brd'];
            $krgBRD += $value['krg_brd'];
            $abr += $value['abnormal'];
            $overripe += $value['lewat_matang'];
            $empty += $value['janjang_kosong'];
            $vcut += $value['vcut'];
            $rd += $value['rat_dmg'];
            $sum_kr += $value['karung'];
            $blok += $value['blok'];
        }
        $dataBLok = $blok;
        if ($sum_kr != 0) {
            $total_kr = round($sum_kr / $dataBLok, 2);
        } else {
            $total_kr = 0;
        }
        $per_kr = round($total_kr * 100, 2);
        $skor_total = round((($tnpBRD + $krgBRD) / ($jjg_sample - $abr)) * 100, 2);
        $skor_jjgMSk = round(($jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr)) / ($jjg_sample - $abr) * 100, 2);
        $skor_lewatMTng =  round(($overripe / ($jjg_sample - $abr)) * 100, 2);
        $skor_jjgKosong =  round(($empty / ($jjg_sample - $abr)) * 100, 2);
        $skor_vcut =   round(($vcut / $jjg_sample) * 100, 2);
        $allSkor = sidak_brdTotal($skor_total) +  sidak_matangSKOR($skor_jjgMSk) +  sidak_lwtMatang($skor_lewatMTng) + sidak_jjgKosong($skor_jjgKosong) + sidak_tangkaiP($skor_vcut) + sidak_PengBRD($per_kr);

        $total_buah['Jumlah_janjang'] = $jjg_sample;
        $total_buah['blok'] = $dataBLok;
        $total_buah['est'] = $key;
        $total_buah['afd'] = $value1['afdeling'];
        $total_buah['tnp_brd'] = $tnpBRD;
        $total_buah['krg_brd'] = $krgBRD;
        $total_buah['persenTNP_brd'] = round(($tnpBRD / ($jjg_sample - $abr)) * 100, 2);
        $total_buah['persenKRG_brd'] = round(($krgBRD / ($jjg_sample - $abr)) * 100, 2);
        $total_buah['total_jjg'] = $tnpBRD + $krgBRD;
        $total_buah['persen_totalJjg'] = $skor_total;
        $total_buah['skor_total'] = sidak_brdTotal($skor_total);
        $total_buah['jjg_matang'] = $jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr);
        $total_buah['persen_jjgMtang'] = $skor_jjgMSk;
        $total_buah['skor_jjgMatang'] = sidak_matangSKOR($skor_jjgMSk);
        $total_buah['lewat_matang'] = $overripe;
        $total_buah['persen_lwtMtng'] =  $skor_lewatMTng;
        $total_buah['skor_lewatMTng'] = sidak_lwtMatang($skor_lewatMTng);
        $total_buah['janjang_kosong'] = $empty;
        $total_buah['persen_kosong'] = $skor_jjgKosong;
        $total_buah['skor_kosong'] = sidak_jjgKosong($skor_jjgKosong);
        $total_buah['vcut'] = $vcut;
        $total_buah['karung'] = $sum_kr;
        $total_buah['vcut_persen'] = $skor_vcut;
        $total_buah['vcut_skor'] = sidak_tangkaiP($skor_vcut);
        $total_buah['abnormal'] = $abr;
        $total_buah['abnormal_persen'] = round(($abr / $jjg_sample) * 100, 2);
        $total_buah['rat_dmg'] = $rd;
        $total_buah['rd_persen'] = round(($rd / $jjg_sample) * 100, 2);
        $total_buah['TPH'] = $total_kr;
        $total_buah['persen_krg'] = $per_kr;
        $total_buah['skor_kr'] = sidak_PengBRD($per_kr);
        $total_buah['All_skor'] = $allSkor;
        $total_buah['kategori'] = sidak_akhir($allSkor);
        // dd($total_buah);


        // dd($sidak_buah, $total_buah);
        $arrView = array();

        $arrView['est'] =  $est;
        $arrView['afd'] =  $afd;
        $arrView['tanggal'] =  $date;
        $arrView['sidak_buah'] =  $sidak_buah;
        $arrView['total_buah'] =  $total_buah;

        $pdf = PDF::loadView('pdfBA_sidakbuah', ['data' => $arrView]);

        $customPaper = array(360, 360, 360, 360);
        $pdf->set_paper('A2', 'landscape');
        // $pdf->set_paper('A2', 'potrait');

        $filename = 'BA Inpeksi Quality control-' . $arrView['tanggal']  . $arrView['est'] . $arrView['afd'] . '.pdf';

        return $pdf->stream($filename);
        // return $pdf->download($filename);

        // return view('pdfBA', [$arrView ]);

    }

    public function findIssueSmb(Request $request)
    {
        $queryEstate = DB::connection('mysql2')->table('estate')
            ->select('estate.*')
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->where('wil.regional', $request->get('regional'))
            ->whereNotIn('estate.est', ['CWS1', 'CWS2', 'CWS3'])
            ->orderBy('estate.id', 'asc')
            ->get();
        $queryEstate = json_decode($queryEstate, true);

        foreach ($queryEstate as $value1) {
            $querySmb = DB::connection('mysql2')->table('sidak_mutu_buah')
                ->select("sidak_mutu_buah.*")
                ->where('estate', $value1['est'])
                ->where('datetime', 'like', '%' . $request->get('date') . '%')
                ->get();
            $dataSmb = $querySmb->groupBy('estate');
            $dataSmb = json_decode($dataSmb, true);

            foreach ($dataSmb as $key => $value) {
                $total_temuan = array();
                foreach ($value as $key2 => $value2) {
                    if (!in_array($value2['estate'] . ' ' . $value2['afdeling'] . ' ' . $value2['blok'], $total_temuan)) {
                        $splitPhoto = explode(";", str_replace(" ", "", $value2['foto_temuan']));
                        foreach ($splitPhoto as $value3) {
                            if (!empty($value3)) {
                                $total_temuan[] = $value3;
                            }
                        }
                    }
                    $tot_temuan = count($total_temuan);
                }
                $dataFinding[$value1['wil']][$key]['total_temuan'] = $tot_temuan;
            }
        }

        $arrView = array();
        $arrView['dataFinding'] = $dataFinding;
        echo json_encode($arrView);
        exit();
    }

    public function cetakFiSmb($est, $tgl)
    {
        $querySmb = DB::connection('mysql2')->table('sidak_mutu_buah')
            ->select("sidak_mutu_buah.*")
            ->where('estate', $est)
            ->where('datetime', 'like', '%' . $tgl . '%')
            ->orderBy('afdeling', 'asc')
            ->get();
        $dataSmb = $querySmb->groupBy('estate');
        $dataSmb = json_decode($dataSmb, true);

         $inc = 0;
        foreach ($dataSmb as $key => $value) {
            $totalTemuan = array();
            foreach ($value as $key2 => $value2) {
                if (!in_array($value2['estate'] . ' ' . $value2['afdeling'] . ' ' . $value2['blok'], $totalTemuan)) {
                    $splitKomen = explode(";", str_replace(" ", " ", $value2['komentar']));
                    $splitPhoto = explode(";", str_replace(" ", "", $value2['foto_temuan']));
                    foreach ($splitPhoto as $key3 => $value3) {
                        foreach ($splitKomen as $key4 => $value4) {
                            $isLastElement = ($key3 === array_key_last($splitPhoto));
                            if ($key3 == $key4) {
                                if (!empty($value3)) {
                                    $dataFinding[$inc]['estate'] = $value2['estate'];
                                    $dataFinding[$inc]['afdeling'] = $value2['afdeling'];
                                    $dataFinding[$inc]['blok'] = $value2['blok'];
                                    $dataFinding[$inc]['komen'] = $value4;
                                    $dataFinding[$inc]['fotoTemuan'] = $value3;
                                    if ($isLastElement) {
                                        $tglLast = Carbon::parse($value2['datetime'])->format('d F Y');
                                    }
                                    $totalTemuan[] = $value3;
                                }
                            }
                        }
                        $temuanResult = count($totalTemuan);
                        $inc++;
                    }
                }
            }
        }
        // dd($dataFinding);

        $pdf = pdf::loadview('cetakFiSmb', [
            'tgl' => $tglLast,
            'totalTemuan' => $temuanResult,
            'dataResult' => $dataFinding
        ]);
        $pdf->set_paper('A2', 'potraits');

        $filename = 'Finding Issue SMB - ' . $est . ' - ' . $tgl . '.pdf';
        return $pdf->stream($filename);
    }

    public function getDataRekap(Request $request)
    {
        $est = $request->input('est');
        $afd = $request->input('afd');
        $date = $request->input('tanggal');
        // dd($est, $afd, $date);
        $mutuAncak = DB::connection('mysql2')->table('sidak_mutu_buah')
            ->select("sidak_mutu_buah.*", DB::raw('DATE_FORMAT(sidak_mutu_buah.datetime, "%M") as bulan'), DB::raw('DATE_FORMAT(sidak_mutu_buah.datetime, "%Y") as tahun'))
            ->where('datetime', 'like', '%' . $date . '%')
            ->where('sidak_mutu_buah.estate', $est)
            ->where('sidak_mutu_buah.afdeling', $afd)

            ->get();
        $mutuAncak = $mutuAncak->groupBy(['blok']);
        $mutuAncak = json_decode($mutuAncak, true);

        // dd($mutuAncak);

        // dd($defPerbulanWil);
        $sidak_buah = array();
        $bloks = 0;
        foreach ($mutuAncak as $key => $value) {
            $jjg_sample = 0;
            $tnpBRD = 0;
            $krgBRD = 0;
            $abr = 0;
            $skor_total = 0;
            $overripe = 0;
            $empty = 0;
            $vcut = 0;
            $rd = 0;
            $sum_kr = 0;
            $allSkor = 0;

            $combination_counts = array();
            $bloks = count($value);
            foreach ($value as $key1 => $value1) {
                // dd($key1);

                $jjg_sample += $value1['jumlah_jjg'];
                $tnpBRD += $value1['bmt'];
                $krgBRD += $value1['bmk'];
                $abr += $value1['abnormal'];
                $overripe += $value1['overripe'];
                $empty += $value1['empty_bunch'];
                $vcut += $value1['vcut'];
                $rd += $value1['rd'];
                $sum_kr += $value1['alas_br'];
            }

            $dataBLok = $bloks;
            if ($sum_kr != 0) {
                $total_kr = round($sum_kr / $dataBLok, 2);
            } else {
                $total_kr = 0;
            }
            $per_kr = round($total_kr * 100, 2);
            $skor_total = round((($tnpBRD + $krgBRD) / ($jjg_sample - $abr)) * 100, 2);
            $skor_jjgMSk = round(($jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr)) / ($jjg_sample - $abr) * 100, 2);
            $skor_lewatMTng =  round(($overripe / ($jjg_sample - $abr)) * 100, 2);
            $skor_jjgKosong =  round(($empty / ($jjg_sample - $abr)) * 100, 2);
            $skor_vcut =   round(($vcut / $jjg_sample) * 100, 2);
            $allSkor = sidak_brdTotal($skor_total) +  sidak_matangSKOR($skor_jjgMSk) +  sidak_lwtMatang($skor_lewatMTng) + sidak_jjgKosong($skor_jjgKosong) + sidak_tangkaiP($skor_vcut) + sidak_PengBRD($per_kr);

            $sidak_buah[$key]['Jumlah_janjang'] = $jjg_sample;
            $sidak_buah[$key]['blok'] = $dataBLok;
            $sidak_buah[$key]['est'] = $key;
            $sidak_buah[$key]['estate'] = $value1['estate'];
            $sidak_buah[$key]['afd'] = $value1['afdeling'];
            $sidak_buah[$key]['tnp_brd'] = $tnpBRD;
            $sidak_buah[$key]['petugas'] = $value1['petugas'];
            $sidak_buah[$key]['krg_brd'] = $krgBRD;
            $sidak_buah[$key]['persenTNP_brd'] = round(($tnpBRD / ($jjg_sample - $abr)) * 100, 2);
            $sidak_buah[$key]['persenKRG_brd'] = round(($krgBRD / ($jjg_sample - $abr)) * 100, 2);
            $sidak_buah[$key]['total_jjg'] = $tnpBRD + $krgBRD;
            $sidak_buah[$key]['persen_totalJjg'] = $skor_total;
            $sidak_buah[$key]['skor_total'] = sidak_brdTotal($skor_total);
            $sidak_buah[$key]['jjg_matang'] = $jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr);
            $sidak_buah[$key]['persen_jjgMtang'] = $skor_jjgMSk;
            $sidak_buah[$key]['skor_jjgMatang'] = sidak_matangSKOR($skor_jjgMSk);
            $sidak_buah[$key]['lewat_matang'] = $overripe;
            $sidak_buah[$key]['persen_lwtMtng'] =  $skor_lewatMTng;
            $sidak_buah[$key]['skor_lewatMTng'] = sidak_lwtMatang($skor_lewatMTng);
            $sidak_buah[$key]['janjang_kosong'] = $empty;
            $sidak_buah[$key]['persen_kosong'] = $skor_jjgKosong;
            $sidak_buah[$key]['skor_kosong'] = sidak_jjgKosong($skor_jjgKosong);
            $sidak_buah[$key]['vcut'] = $vcut;
            $sidak_buah[$key]['karung'] = $sum_kr;
            $sidak_buah[$key]['vcut_persen'] = $skor_vcut;
            $sidak_buah[$key]['vcut_skor'] = sidak_tangkaiP($skor_vcut);
            $sidak_buah[$key]['abnormal'] = $abr;
            $sidak_buah[$key]['abnormal_persen'] = round(($abr / $jjg_sample) * 100, 2);
            $sidak_buah[$key]['rat_dmg'] = $rd;
            $sidak_buah[$key]['rd_persen'] = round(($rd / $jjg_sample) * 100, 2);
            $sidak_buah[$key]['TPH'] = $total_kr;
            $sidak_buah[$key]['persen_krg'] = $per_kr;
            $sidak_buah[$key]['skor_kr'] = sidak_PengBRD($per_kr);
            $sidak_buah[$key]['All_skor'] = $allSkor;
            $sidak_buah[$key]['kategori'] = sidak_akhir($allSkor);
        }
        // dd($sidak_buah);

        $total_buah = array();
        $blok = 0;
        $jjg_sample = 0;
        $tnpBRD = 0;
        $krgBRD = 0;
        $abr = 0;
        $overripe = 0;
        $empty = 0;
        $vcut = 0;
        $rd = 0;
        $sum_kr = 0;
        foreach ($sidak_buah as $key => $value) {
            $jjg_sample += $value['Jumlah_janjang'];
            $tnpBRD += $value['tnp_brd'];
            $krgBRD += $value['krg_brd'];
            $abr += $value['abnormal'];
            $overripe += $value['lewat_matang'];
            $empty += $value['janjang_kosong'];
            $vcut += $value['vcut'];
            $rd += $value['rat_dmg'];
            $sum_kr += $value['karung'];
            $blok += $value['blok'];
        }
        $dataBLok = $blok;
        if ($sum_kr != 0) {
            $total_kr = round($sum_kr / $dataBLok, 2);
        } else {
            $total_kr = 0;
        }
        $per_kr = round($total_kr * 100, 2);
        $skor_total = round((($tnpBRD + $krgBRD) / ($jjg_sample - $abr)) * 100, 2);
        $skor_jjgMSk = round(($jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr)) / ($jjg_sample - $abr) * 100, 2);
        $skor_lewatMTng =  round(($overripe / ($jjg_sample - $abr)) * 100, 2);
        $skor_jjgKosong =  round(($empty / ($jjg_sample - $abr)) * 100, 2);
        $skor_vcut =   round(($vcut / $jjg_sample) * 100, 2);
        $allSkor = sidak_brdTotal($skor_total) +  sidak_matangSKOR($skor_jjgMSk) +  sidak_lwtMatang($skor_lewatMTng) + sidak_jjgKosong($skor_jjgKosong) + sidak_tangkaiP($skor_vcut) + sidak_PengBRD($per_kr);

        $total_buah['Jumlah_janjang'] = $jjg_sample;
        $total_buah['blok'] = $dataBLok;
        $total_buah['est'] = $value1['estate'];
        $total_buah['afd'] = $value1['afdeling'];
        $total_buah['tnp_brd'] = $tnpBRD;
        $total_buah['krg_brd'] = $krgBRD;
        $total_buah['persenTNP_brd'] = round(($tnpBRD / ($jjg_sample - $abr)) * 100, 2);
        $total_buah['persenKRG_brd'] = round(($krgBRD / ($jjg_sample - $abr)) * 100, 2);
        $total_buah['total_jjg'] = $tnpBRD + $krgBRD;
        $total_buah['persen_totalJjg'] = $skor_total;
        $total_buah['skor_total'] = sidak_brdTotal($skor_total);
        $total_buah['jjg_matang'] = $jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr);
        $total_buah['persen_jjgMtang'] = $skor_jjgMSk;
        $total_buah['skor_jjgMatang'] = sidak_matangSKOR($skor_jjgMSk);
        $total_buah['lewat_matang'] = $overripe;
        $total_buah['persen_lwtMtng'] =  $skor_lewatMTng;
        $total_buah['skor_lewatMTng'] = sidak_lwtMatang($skor_lewatMTng);
        $total_buah['janjang_kosong'] = $empty;
        $total_buah['persen_kosong'] = $skor_jjgKosong;
        $total_buah['skor_kosong'] = sidak_jjgKosong($skor_jjgKosong);
        $total_buah['vcut'] = $vcut;
        $total_buah['karung'] = $sum_kr;
        $total_buah['vcut_persen'] = $skor_vcut;
        $total_buah['vcut_skor'] = sidak_tangkaiP($skor_vcut);
        $total_buah['abnormal'] = $abr;
        $total_buah['abnormal_persen'] = round(($abr / $jjg_sample) * 100, 2);
        $total_buah['rat_dmg'] = $rd;
        $total_buah['rd_persen'] = round(($rd / $jjg_sample) * 100, 2);
        $total_buah['TPH'] = $total_kr;
        $total_buah['persen_krg'] = $per_kr;
        $total_buah['skor_kr'] = sidak_PengBRD($per_kr);
        $total_buah['All_skor'] = $allSkor;
        $total_buah['kategori'] = sidak_akhir($allSkor);
        // dd($sidak_buah, $total_buah);


        // dd($sidak_buah, $total_buah);
        $arrView = array();

        $arrView['est'] =  $est;
        $arrView['afd'] =  $afd;
        $arrView['tanggal'] =  $date;
        $arrView['sidak_buah'] =  $sidak_buah;
        $arrView['total_buah'] =  $total_buah;

        echo json_encode($arrView); //di decode ke dalam bentuk json dalam vaiavel arrview yang dapat menampung banyak isi array
        exit();
    }
   public function weeklypdf(Request $request)
    {
        $reg = $request->input('regPDF');
        $date = $request->input('tglPDF');


        $startOfWeek = strtotime($date);


        $endOfWeek = strtotime('+6 days', $startOfWeek);

        // Format the dates
        $formattedStartDate = date('Y-m-d', $startOfWeek);
        $formattedEndDate = date('Y-m-d', $endOfWeek);

        // Create the final formatted string
        $starDate = $formattedStartDate;
        $endDate =  $formattedEndDate;

        // dd($reg, $starDate, $endDate);
        $QueryMTancakWil = DB::connection('mysql2')->table('sidak_mutu_buah')
            ->select("sidak_mutu_buah.*", DB::raw('DATE_FORMAT(sidak_mutu_buah.datetime, "%M") as bulan'), DB::raw('DATE_FORMAT(sidak_mutu_buah.datetime, "%Y") as tahun'))
            ->whereBetween('sidak_mutu_buah.datetime', [$starDate, $endDate])

            ->get();
        $QueryMTancakWil = $QueryMTancakWil->groupBy(['estate', 'afdeling']);
        $QueryMTancakWil = json_decode($QueryMTancakWil, true);

        // dd($starDate, $endDate);
        $QueryPerblok = DB::connection('mysql2')->table('sidak_mutu_buah')
            ->select("sidak_mutu_buah.*", DB::raw('DATE_FORMAT(sidak_mutu_buah.datetime, "%M") as bulan'), DB::raw('DATE_FORMAT(sidak_mutu_buah.datetime, "%Y") as tahun'))
            ->whereBetween('sidak_mutu_buah.datetime', [$starDate, $endDate])

            ->get();
        $QueryPerblok = $QueryPerblok->groupBy(['estate', 'afdeling', 'blok']);
        $QueryPerblok = json_decode($QueryPerblok, true);

        // dd($QueryMTancakWil);

        $queryAfd = DB::connection('mysql2')->table('afdeling')
            ->select(
                'afdeling.id',
                'afdeling.nama',
                'estate.est'
            ) //buat mengambil data di estate db dan willayah db
            ->join('estate', 'estate.id', '=', 'afdeling.estate') //kemudian di join untuk mengambil est perwilayah
            ->get();
        $queryAfd = json_decode($queryAfd, true);
        // dd($starDate, $endDate);
        $queryEste = DB::connection('mysql2')->table('estate')
            ->select('estate.*')
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->where('wil.regional', $reg)
            ->get();
        $queryEste = json_decode($queryEste, true);

        $queryAsisten =  DB::connection('mysql2')->Table('asisten_qc')->get();
        // dd($QueryMTancakWil);
        //end query
        $queryAsisten = json_decode($queryAsisten, true);


        $dataPerBulan = array();
        foreach ($QueryMTancakWil as $key => $value) {
            foreach ($value as $key2 => $value2) {
                foreach ($value2 as $key3 => $value3) {
                    $dataPerBulan[$key][$key2][$key3] = $value3;
                }
            }
        }
        $defaultNew = array();
        foreach ($queryEste as $est) {
            foreach ($queryAfd as $afd) {
                if ($est['est'] == $afd['est']) {
                    $defaultNew[$est['est']][$afd['nama']]['null'] = 0;
                }
            }
        }

        // dd($dataPerBulan);
        $mergedData = array();
        foreach ($defaultNew as $estKey => $afdArray) {
            foreach ($afdArray as $afdKey => $afdValue) {
                if (array_key_exists($estKey, $dataPerBulan)) {
                    if (array_key_exists($afdKey, $dataPerBulan[$estKey])) {
                        if (!empty($dataPerBulan[$estKey][$afdKey])) {
                            $mergedData[$estKey][$afdKey] = $dataPerBulan[$estKey][$afdKey];
                        } else {
                            $mergedData[$estKey][$afdKey] = $afdValue;
                        }
                    } else {
                        $mergedData[$estKey][$afdKey] = $afdValue;
                    }
                } else {
                    $mergedData[$estKey][$afdKey] = $afdValue;
                }
            }
        }

        $mtancakWIltab1 = array();
        foreach ($queryEste as $key => $value) {
            foreach ($mergedData as $key2 => $value2) {
                if ($value['est'] == $key2) {
                    $mtancakWIltab1[$value['wil']][$key2] = array_merge($mtancakWIltab1[$value['wil']][$key2] ?? [], $value2);
                }
            }
        }

        // dd($mtancakWIltab1);

        $weeklyReport = array();
        foreach ($mtancakWIltab1 as $key => $value) {
            $blok_Wil = 0;
            $kr_Wil = 0;
            $jjg_sampleWil = 0;
            $tnpBRDWil = 0;
            $krgBRDWil = 0;
            $overripeWil = 0;
            $emptyWil = 0;
            $abrWil = 0;
            $vcutWil = 0;
            $rdWil = 0;
            foreach ($value as $key1 => $value1) {
                $blok_est = 0;
                $kr_est = 0;
                $jjg_sampleEst = 0;
                $tnpBRDEst = 0;
                $krgBRDEst = 0;
                $overripeEst = 0;
                $emptyeST = 0;
                $abreST = 0;
                $vcuteST = 0;
                $rdeST = 0;
                foreach ($value1 as $key2 => $value2) {
                    $jjg_sample = 0;
                    $tnpBRD = 0;
                    $krgBRD = 0;
                    $abr = 0;
                    $skor_total = 0;
                    $overripe = 0;
                    $empty = 0;
                    $vcut = 0;
                    $rd = 0;
                    $sum_kr = 0;
                    $allSkor = 0;
                    $combination_counts = array();
                    foreach ($value2 as $key3 => $value3) {
                        if (is_array($value3)) {
                            $combination = $value3['blok'] . ' ' . $value3['estate'] . ' ' . $value3['afdeling'] . ' ' . $value3['tph_baris'];
                            if (!isset($combination_counts[$combination])) {
                                $combination_counts[$combination] = 0;
                            }
                            $jjg_sample += $value3['jumlah_jjg'];
                            $tnpBRD += $value3['bmt'];
                            $krgBRD += $value3['bmk'];
                            $abr += $value3['abnormal'];
                            $overripe += $value3['overripe'];
                            $empty += $value3['empty_bunch'];
                            $vcut += $value3['vcut'];
                            $rd += $value3['rd'];
                            $sum_kr += $value3['alas_br'];
                        }
                    }
                    $dataBLok = count($combination_counts);
                    if ($sum_kr != 0) {
                        $total_kr = round($sum_kr / $dataBLok, 2);
                    } else {
                        $total_kr = 0;
                    }
                    $per_kr = round($total_kr * 100, 2);
                    $denominator = ($jjg_sample - $abr);
                    $skor_total = $denominator !== 0 ? round((($tnpBRD + $krgBRD) / $denominator) * 100, 2) : 0;
                    $skor_jjgMSk = $denominator !== 0 ? round((($jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr)) / $denominator) * 100, 2) : 0;
                    $skor_lewatMTng = $denominator !== 0 ? round(($overripe / $denominator) * 100, 2) : 0;
                    $skor_jjgKosong = $denominator !== 0 ? round(($empty / $denominator) * 100, 2) : 0;
                    $skor_vcut = $jjg_sample !== 0 ? round(($vcut / $jjg_sample) * 100, 2) : 0;
                    $allSkor = sidak_brdTotal($skor_total) +  sidak_matangSKOR($skor_jjgMSk) +  sidak_lwtMatang($skor_lewatMTng) + sidak_jjgKosong($skor_jjgKosong) + sidak_tangkaiP($skor_vcut) + sidak_PengBRD($per_kr);

                    $weeklyReport[$key][$key1][$key2]['Jumlah_janjang'] = $jjg_sample;
                    $weeklyReport[$key][$key1][$key2]['blok'] = $dataBLok;
                    $weeklyReport[$key][$key1][$key2]['est'] = $key1;
                    $weeklyReport[$key][$key1][$key2]['afd'] = $key2;
                    $weeklyReport[$key][$key1][$key2]['tnp_brd'] = $tnpBRD;
                    $weeklyReport[$key][$key1][$key2]['krg_brd'] = $krgBRD;
                    $weeklyReport[$key][$key1][$key2]['jjg_matang'] = $jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr);
                    $weeklyReport[$key][$key1][$key2]['lewat_matang'] = $overripe;
                    $weeklyReport[$key][$key1][$key2]['janjang_kosong'] = $empty;
                    $weeklyReport[$key][$key1][$key2]['vcut'] = $vcut;
                    $weeklyReport[$key][$key1][$key2]['karung'] = $sum_kr;
                    $weeklyReport[$key][$key1][$key2]['abnormal'] = $abr;
                    $weeklyReport[$key][$key1][$key2]['rat_dmg'] = $rd;
                    $weeklyReport[$key][$key1][$key2]['TPH'] = $total_kr;
                    $weeklyReport[$key][$key1][$key2]['persen_krg'] = $per_kr;
                    foreach ($queryAsisten as $ast => $asisten) {
                        if ($key1 === $asisten['est'] && $key2 === $asisten['afd']) {
                            $weeklyReport[$key][$key1][$key2]['nama_asisten'] = $asisten['nama'];
                        }
                    }

                    $blok_est += $dataBLok;
                    $kr_est += $sum_kr;
                    $jjg_sampleEst += $jjg_sample;
                    $tnpBRDEst += $tnpBRD;
                    $krgBRDEst += $krgBRD;
                    $overripeEst += $overripe;
                    $emptyeST += $empty;
                    $abreST += $abr;
                    $vcuteST += $vcut;
                    $rdeST += $rd;
                    if ($kr_est != 0) {
                        $total_krEst = round($kr_est / $blok_est, 2);
                    } else {
                        $total_krEst = 0;
                    }
                    $per_krEst = round($total_krEst * 100, 2);
                    $denominatorEst = ($jjg_sampleEst - $abreST);
                    $skor_total = $denominatorEst !== 0 ? round((($tnpBRDEst + $krgBRDEst) / $denominatorEst) * 100, 2) : 0;
                    $skor_jjgMSk = $denominatorEst !== 0 ? round((($jjg_sampleEst - ($tnpBRDEst + $krgBRDEst + $overripeEst + $emptyeST + $abreST)) / $denominatorEst) * 100, 2) : 0;
                    $skor_lewatMTng = $denominatorEst !== 0 ? round(($overripeEst / $denominatorEst) * 100, 2) : 0;
                    $skor_jjgKosong = $denominatorEst !== 0 ? round(($emptyeST / $denominatorEst) * 100, 2) : 0;
                    $skor_vcut = $jjg_sampleEst !== 0 ? round(($vcuteST / $jjg_sampleEst) * 100, 2) : 0;
                }
                $ass = '-';
                foreach ($queryAsisten as $ast => $asisten) {
                    if ($key1 === $asisten['est'] && 'EM' === $asisten['afd']) {
                        $ass = $asisten['nama'];
                    }
                }
                $weeklyReport[$key][$key1]['nama_asistenEM'] = $ass;
                $weeklyReport[$key][$key1]['Jumlah_janjang'] = $jjg_sampleEst;
                $weeklyReport[$key][$key1]['blok'] = $blok_est;
                $weeklyReport[$key][$key1]['est'] = $key;
                $weeklyReport[$key][$key1]['afd'] = $key1;
                $weeklyReport[$key][$key1]['tnp_brd'] = $tnpBRDEst;
                $weeklyReport[$key][$key1]['krg_brd'] = $krgBRDEst;
                $weeklyReport[$key][$key1]['jjg_matang'] = $jjg_sampleEst - ($tnpBRDEst + $krgBRDEst + $overripeEst + $emptyeST + $abreST);
                $weeklyReport[$key][$key1]['lewat_matang'] = $overripeEst;
                $weeklyReport[$key][$key1]['janjang_kosong'] = $emptyeST;
                $weeklyReport[$key][$key1]['vcut'] = $vcuteST;
                $weeklyReport[$key][$key1]['karung'] = $kr_est;
                $weeklyReport[$key][$key1]['abnormal'] = $abreST;
                $weeklyReport[$key][$key1]['rat_dmg'] = $rdeST;
                $weeklyReport[$key][$key1]['TPH'] = $total_krEst;




                $blok_Wil += $blok_est;
                $kr_Wil += $kr_est;
                $jjg_sampleWil += $jjg_sampleEst;
                $tnpBRDWil += $tnpBRDEst;
                $krgBRDWil += $krgBRDEst;
                $overripeWil += $overripeEst;
                $emptyWil += $emptyeST;
                $abrWil += $abreST;
                $vcutWil += $vcuteST;
                $rdWil += $rdeST;
                if ($kr_Wil != 0) {
                    $total_krWil = round($kr_Wil / $blok_Wil, 2);
                } else {
                    $total_krWil = 0;
                }
            }
            foreach ($weeklyReport as $key => $value) {
                if ($key == 1) {
                    $wiles = 'I';
                } elseif ($key == 2) {
                    $wiles = 'II';
                } elseif ($key == 3) {
                    $wiles = 'III';
                } elseif ($key == 4) {
                    $wiles = 'IV';
                } elseif ($key == 5) {
                    $wiles = 'V';
                } elseif ($key == 6) {
                    $wiles = 'VI';
                } elseif ($key == 7) {
                    $wiles = 'VII';
                } elseif ($key == 8) {
                    $wiles = 'VIII';
                } elseif ($key == 9) {
                    $wiles = 'IX';
                } elseif ($key == 10) {
                    $wiles = 'X';
                } else {
                    $wiles = ''; // Handle other cases as needed
                }

                $wil = 'WIL-' . $wiles;
                $weeklyReport[$key]['est'] = $wil;
            }

            $weeklyReport[$key]['Jumlah_janjang'] = $jjg_sampleWil;
            $weeklyReport[$key]['blok'] = $blok_Wil;
            $weeklyReport[$key]['afd'] = $key1;
            $weeklyReport[$key]['tnp_brd'] = $tnpBRDWil;
            $weeklyReport[$key]['krg_brd'] = $krgBRDWil;
            $weeklyReport[$key]['jjg_matang'] = $jjg_sampleWil - ($tnpBRDWil + $krgBRDWil + $overripeWil + $emptyWil + $abrWil);
            $weeklyReport[$key]['lewat_matang'] = $overripeWil;
            $weeklyReport[$key]['janjang_kosong'] = $emptyWil;
            $weeklyReport[$key]['vcut'] = $vcutWil;
            $weeklyReport[$key]['karung'] = $kr_Wil;
            $weeklyReport[$key]['abnormal'] = $abrWil;
            $weeklyReport[$key]['rat_dmg'] = $rdWil;
            $weeklyReport[$key]['TPH'] = $total_krWil;


            foreach ($queryAsisten as $ast => $asisten) {
                if ($wil === $asisten['est'] && 'GM' === $asisten['afd']) {
                    $weeklyReport[$key]['nama_asistenWil'] = $asisten['nama'];
                }
            }
        }

        $weeklyReportV2 = array();
        foreach ($mtancakWIltab1 as $key => $value) {
            foreach ($value as $key1 => $value1) {
                foreach ($value1 as $key2 => $value2) {
                    $jjg_sample = 0;
                    $tnpBRD = 0;
                    $krgBRD = 0;
                    $abr = 0;
                    $skor_total = 0;
                    $overripe = 0;
                    $empty = 0;
                    $vcut = 0;
                    $rd = 0;
                    $sum_kr = 0;
                    $allSkor = 0;
                    $combination_counts = array();
                    foreach ($value2 as $key3 => $value3) {
                        if (is_array($value3)) {
                            $combination = $value3['blok'] . ' ' . $value3['estate'] . ' ' . $value3['afdeling'] . ' ' . $value3['tph_baris'];
                            if (!isset($combination_counts[$combination])) {
                                $combination_counts[$combination] = 0;
                            }
                            $jjg_sample += $value3['jumlah_jjg'];
                            $tnpBRD += $value3['bmt'];
                            $krgBRD += $value3['bmk'];
                            $abr += $value3['abnormal'];
                            $overripe += $value3['overripe'];
                            $empty += $value3['empty_bunch'];
                            $vcut += $value3['vcut'];
                            $rd += $value3['rd'];
                            $sum_kr += $value3['alas_br'];
                        }
                    }
                    $dataBLok = count($combination_counts);
                    if ($sum_kr != 0) {
                        $total_kr = round($sum_kr / $dataBLok, 2);
                    } else {
                        $total_kr = 0;
                    }
                    $per_kr = round($total_kr * 100, 2);
                    $denominator = ($jjg_sample - $abr);
                    $skor_total = $denominator !== 0 ? round((($tnpBRD + $krgBRD) / $denominator) * 100, 2) : 0;
                    $skor_jjgMSk = $denominator !== 0 ? round((($jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr)) / $denominator) * 100, 2) : 0;
                    $skor_lewatMTng = $denominator !== 0 ? round(($overripe / $denominator) * 100, 2) : 0;
                    $skor_jjgKosong = $denominator !== 0 ? round(($empty / $denominator) * 100, 2) : 0;
                    $skor_vcut = $jjg_sample !== 0 ? round(($vcut / $jjg_sample) * 100, 2) : 0;
                    $allSkor = sidak_brdTotal($skor_total) +  sidak_matangSKOR($skor_jjgMSk) +  sidak_lwtMatang($skor_lewatMTng) + sidak_jjgKosong($skor_jjgKosong) + sidak_tangkaiP($skor_vcut) + sidak_PengBRD($per_kr);

                    $weeklyReportV2[$key][$key1][$key2]['Jumlah_janjang'] = $jjg_sample;
                    $weeklyReportV2[$key][$key1][$key2]['blok'] = $dataBLok;
                    $weeklyReportV2[$key][$key1][$key2]['est'] = $key1;
                    $weeklyReportV2[$key][$key1][$key2]['afd'] = $key2;
                    $weeklyReportV2[$key][$key1][$key2]['tnp_brd'] = $tnpBRD;
                    $weeklyReportV2[$key][$key1][$key2]['krg_brd'] = $krgBRD;
                    $weeklyReportV2[$key][$key1][$key2]['jjg_matang'] = $jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr);
                    $weeklyReportV2[$key][$key1][$key2]['lewat_matang'] = $overripe;
                    $weeklyReportV2[$key][$key1][$key2]['janjang_kosong'] = $empty;
                    $weeklyReportV2[$key][$key1][$key2]['vcut'] = $vcut;
                    $weeklyReportV2[$key][$key1][$key2]['karung'] = $sum_kr;
                    $weeklyReportV2[$key][$key1][$key2]['abnormal'] = $abr;
                    $weeklyReportV2[$key][$key1][$key2]['rat_dmg'] = $rd;
                    $weeklyReportV2[$key][$key1][$key2]['TPH'] = $total_kr;
                    $weeklyReportV2[$key][$key1][$key2]['persen_krg'] = $per_kr;
                    foreach ($queryAsisten as $ast => $asisten) {
                        if ($key1 === $asisten['est'] && $key2 === $asisten['afd']) {
                            $weeklyReportV2[$key][$key1][$key2]['nama_asisten'] = $asisten['nama'];
                        }
                    }
                }
            }
        }
        // $highestCrot now contains the highest "tnp_brd" value for each nested array


        $highestCrot = array();

        foreach ($weeklyReportV2 as $key => $value) {
            $highestKeys = array();
            $maxValues = array(
                'tnp_brd' => -INF,
                'krg_brd' => -INF,
                'jjg_matang' => -INF,
                'lewat_matang' => -INF,
                'janjang_kosong' => -INF,
                'vcut' => -INF,
                'karung' => -INF,
                'abnormal' => -INF,
                'rat_dmg' => -INF,
            );

            foreach ($value as $key1 => $value1) {
                foreach ($value1 as $key2 => $value2) {
                    foreach ($maxValues as $field => &$maxValue) {
                        if (isset($value2[$field])) {
                            if ($value2[$field] > $maxValue) {
                                $maxValue = $value2[$field];
                                $highestKeys[$field] = array($key1 . '_' . $key2);
                            } elseif ($value2[$field] === $maxValue) {
                                $highestKeys[$field][] = $key1 . '_' . $key2;
                            }
                        }
                    }
                }
            }

            $highestCrot[$key] = array(
                'Highest_tnp' => $highestKeys['tnp_brd'],
                'value_Highest_tnp' => $maxValues['tnp_brd'],
                'Highest_krg' => $highestKeys['krg_brd'],
                'value_Highest_krg' => $maxValues['krg_brd'],
                'Highest_masak' => $highestKeys['jjg_matang'],
                'value_Highest_masak' => $maxValues['jjg_matang'],
                'Highest_lwtmtang' => $highestKeys['lewat_matang'],
                'value_Highest_lwtmtang' => $maxValues['lewat_matang'],
                'Highest_jjgkosng' => $highestKeys['janjang_kosong'],
                'value_Highest_jjgkosng' => $maxValues['janjang_kosong'],
                'Highest_vcut' => $highestKeys['vcut'],
                'value_Highest_vcut' => $maxValues['vcut'],
                'Highest_karung' => $highestKeys['karung'],
                'value_Highest_karung' => $maxValues['karung'],
                'Highest_abnormal' => $highestKeys['abnormal'],
                'value_Highest_abnormal' => $maxValues['abnormal'],
                'Highest_rat_dmg' => $highestKeys['rat_dmg'],
                'value_Highest_rat_dmg' => $maxValues['rat_dmg'],
            );

            // Merge the highestCrot array with the original array
            $weeklyReport[$key] = array_merge($weeklyReport[$key], $highestCrot[$key]);
        }


        // dd($highestCrot, $weeklyReport);
        // dd($weeklyReport);
        //untuk perhitungan perblok
        $QueryAncaksx = DB::connection('mysql2')->table('sidak_mutu_buah')
            ->select(
                "sidak_mutu_buah.*",
                DB::raw('DATE_FORMAT(sidak_mutu_buah.datetime, "%M") as bulan'),
                DB::raw('DATE_FORMAT(sidak_mutu_buah.datetime, "%Y") as tahun')
            )
            ->whereBetween('sidak_mutu_buah.datetime', [$starDate, $endDate])

            // ->whereYear('datetime', $year)
            ->get();
        $QueryAncaksx = $QueryAncaksx->groupBy(['estate', 'afdeling', 'blok']);
        $QueryAncaksx = json_decode($QueryAncaksx, true);
        $queryEstatesss = DB::connection('mysql2')->table('estate')
            ->select('estate.*')
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->where('wil.regional', $reg)
            ->get();

        $queryEstatesss = json_decode($queryEstatesss, true);

        $queryblok = DB::connection('mysql2')->table('blok')
            ->select('blok.*')
            ->get();


        // $queryblok = $queryblok->groupBy(['afdeling', 'nama']);
        $queryblok = json_decode($queryblok, true);
        // dd($queryblok);
        $dataAncaks = array();
        foreach ($QueryAncaksx as $key => $value) {
            foreach ($queryEstatesss as $est => $estval)
                if ($estval['est'] === $key) {
                    foreach ($value as $key2 => $value2) {
                        foreach ($queryAfd as $afd => $afdval)
                            if ($afdval['est'] === $key && $afdval['nama'] === $key2) {
                                foreach ($value2 as $key3 => $value3) {
                                    $dataAncaks[$afdval['est']][$afdval['nama']][$key3] = $value3;
                                }
                            }
                    }
                }
        }


        // dd($dataAncaks);

        $blokWeekly = array();
        foreach ($dataAncaks as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $bloks = 0;
                foreach ($value1 as $key2 => $value2) {
                    // dd($key2);
                    $bloks = count($value2);
                    foreach ($value2 as $key3 => $value3) {
                        // dd($value3);
                        $jjg_sample += $value3['jumlah_jjg'];
                        $tnpBRD += $value3['bmt'];
                        $krgBRD += $value3['bmk'];
                        $abr += $value3['abnormal'];
                        $overripe += $value3['overripe'];
                        $empty += $value3['empty_bunch'];
                        $vcut += $value3['vcut'];
                        $rd += $value3['rd'];
                        $sum_kr += $value3['alas_br'];
                    }
                    $dataBLok = $bloks;
                    if ($sum_kr != 0) {
                        $total_kr = round($sum_kr / $dataBLok, 2);
                    } else {
                        $total_kr = 0;
                    }
                    $per_kr = round($total_kr * 100, 2);
                    $skor_total = round((($tnpBRD + $krgBRD) / ($jjg_sample - $abr)) * 100, 2);
                    $skor_jjgMSk = round(($jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr)) / ($jjg_sample - $abr) * 100, 2);
                    $skor_lewatMTng =  round(($overripe / ($jjg_sample - $abr)) * 100, 2);
                    $skor_jjgKosong =  round(($empty / ($jjg_sample - $abr)) * 100, 2);
                    $skor_vcut =   round(($vcut / $jjg_sample) * 100, 2);
                    $allSkor = sidak_brdTotal($skor_total) +  sidak_matangSKOR($skor_jjgMSk) +  sidak_lwtMatang($skor_lewatMTng) + sidak_jjgKosong($skor_jjgKosong) + sidak_tangkaiP($skor_vcut) + sidak_PengBRD($per_kr);

                    $blokWeekly[$key][$key1][$key2]['Jumlah_janjang'] = $jjg_sample;
                    $blokWeekly[$key][$key1][$key2]['blok'] = $key2;
                    $blokWeekly[$key][$key1][$key2]['blokss'] = $dataBLok;
                    $blokWeekly[$key][$key1][$key2]['tnp_brd'] = $tnpBRD;
                    $blokWeekly[$key][$key1][$key2]['krg_brd'] = $krgBRD;
                    $blokWeekly[$key][$key1][$key2]['persenTNP_brd'] = round(($tnpBRD / ($jjg_sample - $abr)) * 100, 2);
                    $blokWeekly[$key][$key1][$key2]['persenKRG_brd'] = round(($krgBRD / ($jjg_sample - $abr)) * 100, 2);
                    $blokWeekly[$key][$key1][$key2]['total_jjg'] = $tnpBRD + $krgBRD;
                    $blokWeekly[$key][$key1][$key2]['persen_totalJjg'] = $skor_total;
                    $blokWeekly[$key][$key1][$key2]['skor_total'] = sidak_brdTotal($skor_total);
                    $blokWeekly[$key][$key1][$key2]['jjg_matang'] = $jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr);
                    $blokWeekly[$key][$key1][$key2]['persen_jjgMtang'] = $skor_jjgMSk;
                    $blokWeekly[$key][$key1][$key2]['skor_jjgMatang'] = sidak_matangSKOR($skor_jjgMSk);
                    $blokWeekly[$key][$key1][$key2]['lewat_matang'] = $overripe;
                    $blokWeekly[$key][$key1][$key2]['persen_lwtMtng'] =  $skor_lewatMTng;
                    $blokWeekly[$key][$key1][$key2]['skor_lewatMTng'] = sidak_lwtMatang($skor_lewatMTng);
                    $blokWeekly[$key][$key1][$key2]['janjang_kosong'] = $empty;
                    $blokWeekly[$key][$key1][$key2]['persen_kosong'] = $skor_jjgKosong;
                    $blokWeekly[$key][$key1][$key2]['skor_kosong'] = sidak_jjgKosong($skor_jjgKosong);
                    $blokWeekly[$key][$key1][$key2]['vcut'] = $vcut;
                    $blokWeekly[$key][$key1][$key2]['karung'] = $sum_kr;
                    $blokWeekly[$key][$key1][$key2]['vcut_persen'] = $skor_vcut;
                    $blokWeekly[$key][$key1][$key2]['vcut_skor'] = sidak_tangkaiP($skor_vcut);
                    $blokWeekly[$key][$key1][$key2]['abnormal'] = $abr;
                    $blokWeekly[$key][$key1][$key2]['abnormal_persen'] = round(($abr / $jjg_sample) * 100, 2);
                    $blokWeekly[$key][$key1][$key2]['rat_dmg'] = $rd;
                    $blokWeekly[$key][$key1][$key2]['rd_persen'] = round(($rd / $jjg_sample) * 100, 2);
                    $blokWeekly[$key][$key1][$key2]['TPH'] = $total_kr;
                    $blokWeekly[$key][$key1][$key2]['persen_krg'] = $per_kr;
                    $blokWeekly[$key][$key1][$key2]['skor_kr'] = sidak_PengBRD($per_kr);
                    $blokWeekly[$key][$key1][$key2]['All_skor'] = $allSkor;
                    $blokWeekly[$key][$key1][$key2]['kategori'] = sidak_akhir($allSkor);
                }
                // Set the start date and end date within the sub-array

            }
            // // Set the start date and end date within the sub-array
            // $blokWeekly[$key][$key1]['startDate'] = $value3['datetime'];
            // $blokWeekly[$key][$key1]['endDate'] = end($value3)['datetime'];
        }

        // $temuan = array();
        // foreach ($dataAncaks as $key => $value) {
        //     foreach ($value as $key1 => $value1) {
        //         $bloks = 0;
        //         foreach ($value1 as $key2 => $value2) {
        //             // dd($key2);
        //             $bloks = count($value2);
        //             foreach ($value2 as $key3 => $value3) {
        //                 // dd($value3);

        //                 $fu = $value3['foto_temuan'];
        //                 $kmn = $value3['komentar'];
        //                 $est = $value3['estate'];
        //                 $afd = $value3['afdeling'];
        //                 $blok = $value3['blok'];
        //             }

        //             $temuan[$key][$key1][$key2]['estate'] = $est;
        //             $temuan[$key][$key1][$key2]['afd'] = $afd;
        //             $temuan[$key][$key1][$key2]['blok'] = $blok;
        //             $temuan[$key][$key1][$key2]['foto_temuan'] = $fu;
        //             $temuan[$key][$key1][$key2]['komentar'] = $kmn;
        //         }
        //     }
        // }

        $temuan = array();
        foreach ($dataAncaks as $key => $value) {
            foreach ($value as $key1 => $value1) {
                foreach ($value1 as $key2 => $value2) {
                    foreach ($value2 as $key3 => $value3) {
                        $fu = $value3['foto_temuan'];
                        $kmn = $value3['komentar'];

                        // Skip the iteration if both "foto_temuan" and "komentar" are empty
                        if (empty($fu) && empty($kmn)) {
                            continue;
                        }

                        $est = $value3['estate'];
                        $afd = $value3['afdeling'];
                        $blok = $value3['blok'];

                        // Explode "foto_temuan" and "komentar" if they contain multiple values
                        $fotoTemuanArray = explode('; ', $fu);
                        $komentarArray = explode('; ', $kmn);

                        // Create separate keys for each exploded value
                        foreach ($fotoTemuanArray as $index => $foto) {
                            $temuan[$key][$key1][$key2 . $index]['estate'] = $est;
                            $temuan[$key][$key1][$key2 . $index]['afd'] = $afd;
                            $temuan[$key][$key1][$key2 . $index]['blok'] = $blok;
                            $temuan[$key][$key1][$key2 . $index]['foto_temuan'] = $foto;
                            $temuan[$key][$key1][$key2 . $index]['komentar'] = $komentarArray[$index] ?? '';
                        }
                    }
                }
            }
        }



        // dd($weeklyReport, $temuan);
        // dd($weeklyReport);
        $arrView = array();

        $arrView['est'] =  $reg;
        // $arrView['afd'] =  $afd;
        $arrView['tanggal'] =  $date;
        $arrView['mulai'] =  $starDate;
        $arrView['akhir'] =  $endDate;
        $arrView['WeekReport1'] =  $weeklyReport;
        $arrView['highest'] =  $weeklyReportV2;
        $arrView['blokReport'] =  $blokWeekly;
        $arrView['temuan'] =  $temuan;
        // $arrView['total_buah'] =  $total_buah;

        $pdf = PDF::loadView('mutubuahpdfWeekly', ['data' => $arrView]);

        $customPaper = array(360, 360, 360, 360);
        // $pdf->set_paper('A2', 'landscape');
        $pdf->set_paper('A2', 'potrait');

        $filename = 'Weekly report-' . $arrView['mulai'] .'-'. $arrView['akhir'] .'-'.'Regional-'. $arrView['est']  . '.pdf';

        return $pdf->stream($filename);


        // Instantiate Dompdf with options
        // $options = new Options();
        // $options->set('isRemoteEnabled', true);
        // $dompdf = new Dompdf($options);

        // // Load HTML into Dompdf
        // $html = view('mutubuahpdfWeekly', ['data' => $arrView])->render();
        // $dompdf->loadHtml($html);

        // // Render the PDF
        // $dompdf->render();

        // // Output the PDF as a stream
        // $filename = 'Weekly report-' . $arrView['tanggal']  . $arrView['est']  . '.pdf';
        // $dompdf->stream($filename);
    }
    
     public function getMapsData(Request $request)
    {
        $date = $request->input('Tanggal');
        $est = $request->input('est');
        $afd = $request->input('afd');
        // dd($date, $est, $afd);

        $queryPlotEst = DB::connection('mysql2')->table('estate_plot')
            ->select("estate_plot.*")
            ->where('est', $est)
            ->get();
        // $queryPlotEst = $queryPlotEst->groupBy(['estate', 'afdeling']);
        $queryPlotEst = json_decode($queryPlotEst, true);

        // dd($queryPlotEst);

        $convertedCoords = [];
        foreach ($queryPlotEst as $coord) {
            $convertedCoords[] = [$coord['lat'], $coord['lon']];
        }

        $afd = $request->input('afd');
        $queryAfd = DB::connection('mysql2')->table('afdeling')
            ->select(
                'afdeling.id',
                'afdeling.nama',
                'estate.est'
            ) //buat mengambil data di estate db dan willayah db
            ->join('estate', 'estate.id', '=', 'afdeling.estate') //kemudian di join untuk mengambil est perwilayah
            ->where('est', '=', $est)
            ->where('afdeling.nama', '=', $afd)
            ->get();
        $queryAfd = json_decode($queryAfd, true);


        // dd($queryAfd);
        $id = $queryAfd[0]['id'];

        $queryBlokMA = DB::connection('mysql2')->table('sidak_mutu_buah')
            ->select('sidak_mutu_buah.*', 'sidak_mutu_buah.blok as nama_blok')
            ->whereDate('sidak_mutu_buah.datetime', $date)
            ->where('estate', $est)
            ->where('afdeling', $afd)
            ->orderBy('sidak_mutu_buah.datetime', 'desc')
            ->groupBy('nama_blok')
            ->pluck('blok');
        $queryBlokMA = json_decode($queryBlokMA, true);

        // dd($queryBlokMA);

        $blokSidak = array();
        foreach ($queryBlokMA as $value) {
            $length = strlen($value);
            $blokSidak[] = substr($value, 0, $length - 3);
            $modifiedStr2  = substr($value, 0, $length - 2);
            $blokSidak[] = substr($value, 0, $length - 2);
            $blokSidak[] =  substr_replace($modifiedStr2, "0", 1, 0);
        }
        // dd($blokSidak);

        $blokSidakResult = DB::connection('mysql2')
            ->table('blok')
            ->select('blok.nama as nama_blok_visit')
            ->where('afdeling', $id)
            ->whereIn('nama', $blokSidak)
            ->groupBy('nama_blok_visit')
            ->pluck('nama_blok_visit');

        $queryBlok = DB::connection('mysql2')->table('blok')
            ->select("blok.*")
            ->where('afdeling', '=', $id)
            ->get();
        $queryBlok = json_decode($queryBlok, true);
        $bloks_afd = array_reduce($queryBlok, function ($carry, $item) {
            $carry[$item['nama']][] = $item;
            return $carry;
        }, []);

        $plotBlokAll = [];
        foreach ($bloks_afd as $key => $coord) {
            foreach ($coord as $key2 => $value) {
                $plotBlokAll[$key][] = [$value['lat'], $value['lon']];
            }
        }

        $queryTrans = DB::connection('mysql2')->table("sidak_mutu_buah")
            ->select("sidak_mutu_buah.*", "estate.wil")
            ->join('estate', 'estate.est', '=', 'sidak_mutu_buah.estate')
            ->where('sidak_mutu_buah.estate', $est)
            ->where('sidak_mutu_buah.afdeling', $afd)
            ->where('datetime', 'like', '%' . $date . '%')
            ->where('sidak_mutu_buah.afdeling', '!=', 'Pla')
            ->get();
        $queryTrans = json_decode($queryTrans, true);

        $groupedTrans = array_reduce($queryTrans, function ($carry, $item) {
            $carry[$item['blok']][] = $item;
            return $carry;
        }, []);


        // dd($groupedTrans);
        $trans_plot = [];
        foreach ($groupedTrans as $blok => $coords) {
            foreach ($coords as $coord) {
                $datetime = $coord['datetime'];
                $time = date('H:i:s', strtotime($datetime));

                $trans_plot[$blok][] = [
                    'blok' => $blok,
                    'lat' => $coord['lat'],
                    'lon' => $coord['lon'],
                    'bmt' => $coord['bmt'],
                    'bmk' => $coord['bmk'],
                    'overripe' => $coord['overripe'],
                    'empty_bunch' => $coord['empty_bunch'],
                    'abnormal' => $coord['abnormal'],
                    'rd' => $coord['rd'],
                    'vcut' => $coord['vcut'],
                    'alas_br' => $coord['alas_br'],
                    'foto_temuan' => $coord['foto_temuan'],
                    'komentar' => $coord['komentar'],
                    'bmt' => $coord['bmt'],
                    'bmk' => $coord['bmk'],
                    'time' => $time,
                ];
            }
        }
        // dd($blokSidakResult);
        return response()->json([
            // 'plot_line'=> $plotLine,             
            'coords' => $convertedCoords,
            'plot_blok_all' => $plotBlokAll,
            'trans_plot' => $trans_plot,
            // 'buah_plot' => $buah_plot,
            // 'ancak_plot' => $ancak_plot,
            'blok_sidak' => $blokSidakResult
        ]);
    }
}
