<?php

namespace App\Http\Controllers\incpectcomponent;


use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use DateTime;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

require_once(app_path('helpers.php'));


class inspeksidashController extends Controller
{

    public function filterv2(Request $request)
    {

        $regional = $request->get('reg');
        $bulan = $request->get('date');


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

            ->get();
        $queryEste = json_decode($queryEste, true);

        // dd($queryEste);

        $muaest = DB::connection('mysql2')->table('estate')
            ->select('estate.*')
            ->where('estate.emp', '!=', 1)
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->where('wil.regional', $regional)
            ->get('est');
        $muaest = json_decode($muaest, true);

        // dd($muaest, $queryEste);

        $queryAfd = DB::connection('mysql2')->table('afdeling')
            ->select(
                'afdeling.id',
                'afdeling.nama',
                'estate.est'
            ) //buat mengambil data di estate db dan willayah db
            ->join('estate', 'estate.id', '=', 'afdeling.estate') //kemudian di join untuk mengambil est perwilayah
            ->get();
        $queryAfd = json_decode($queryAfd, true);


        $QueryMTancakWil = DB::connection('mysql2')->table('mutu_ancak_new')
            ->select("mutu_ancak_new.*", DB::raw('DATE_FORMAT(mutu_ancak_new.datetime, "%M") as bulan'), DB::raw('DATE_FORMAT(mutu_ancak_new.datetime, "%Y") as tahun'))
            // ->whereYear('datetime', '2023')
            // ->where('datetime', 'like', '%' . $getDate . '%')
            ->where('datetime', 'like', '%' . $bulan . '%')
            // ->whereYear('datetime', $year)
            ->orderBy('afdeling', 'asc')
            ->get();
        $QueryMTancakWil = $QueryMTancakWil->groupBy(['estate', 'afdeling']);
        $QueryMTancakWil = json_decode($QueryMTancakWil, true);

        $dataPerBulan = array();
        foreach ($QueryMTancakWil as $key => $value) {
            foreach ($value as $key2 => $value2) {
                foreach ($value2 as $key3 => $value3) {
                    $dataPerBulan[$key][$key2][$key3] = $value3;
                }
            }
        }

        // dd($QueryMTancakWil);
        // dd($QueryMTancakWil);

        $defaultNew = [];

        foreach ($queryEste as $est) {
            foreach ($queryAfd as $afd) {
                // dd($est);
                if ($est['est'] == $afd['est']) {
                    if ($est['est'] === 'LDE' || $est['est'] === 'SRE') {
                        $defaultNew[$est['est']][$afd['est']]['null'] = 0;
                    } else {
                        $defaultNew[$est['est']][$afd['nama']]['null'] = 0;
                    }
                }
            }
        }


        $defaultNewmua = array();
        foreach ($muaest as $est) {
            foreach ($queryAfd as $afd) {
                if ($est['est'] == $afd['est']) {
                    $defaultNewmua[$est['est']][$afd['est']]['null'] = 0;
                }
            }
        }

        // dd($defaultNewmua, $defaultNew);
        $mergedDatamua = array();
        foreach ($defaultNewmua as $estKey => $afdArray) {
            foreach ($afdArray as $afdKey => $afdValue) {
                if (array_key_exists($estKey, $dataPerBulan)) {
                    if (array_key_exists($afdKey, $dataPerBulan[$estKey])) {
                        if (!empty($dataPerBulan[$estKey][$afdKey])) {
                            $mergedDatamua[$estKey][$afdKey] = $dataPerBulan[$estKey][$afdKey];
                        } else {
                            $mergedDatamua[$estKey][$afdKey] = $afdValue;
                        }
                    } else {
                        $mergedDatamua[$estKey][$afdKey] = $afdValue;
                    }
                } else {
                    $mergedDatamua[$estKey][$afdKey] = $afdValue;
                }
            }
        }
        $mtancakWIltab1mua = array();
        foreach ($muaest as $key => $value) {
            foreach ($mergedDatamua as $key2 => $value2) {
                if ($value['est'] == $key2) {
                    $mtancakWIltab1mua[$value['wil']][$key2] = array_merge($mtancakWIltab1mua[$value['wil']][$key2] ?? [], $value2);
                }
            }
        }


        // dd($mtancakWIltab1mua, $mergedDatamua);
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

        $QueryMTbuahWil = DB::connection('mysql2')->table('mutu_buah')
            ->select(
                "mutu_buah.*",
                DB::raw('DATE_FORMAT(mutu_buah.datetime, "%M") as bulan'),
                DB::raw('DATE_FORMAT(mutu_buah.datetime, "%Y") as tahun')
            )
            ->where('datetime', 'like', '%' . $bulan . '%')
            ->orderBy('afdeling', 'asc')
            ->get();
        $QueryMTbuahWil = $QueryMTbuahWil->groupBy(['estate', 'afdeling']);
        $QueryMTbuahWil = json_decode($QueryMTbuahWil, true);

        // dd($QueryMTbuahWil);

        $dataMTBuah = array();
        foreach ($QueryMTbuahWil as $key => $value) {
            foreach ($value as $key2 => $value2) {
                foreach ($value2 as $key3 => $value3) {
                    $dataMTBuah[$key][$key2][$key3] = $value3;
                }
            }
        }

        $defaultMTbuah = array();
        foreach ($queryEste as $est) {
            foreach ($queryAfd as $afd) {
                if ($est['est'] == $afd['est']) {
                    if ($est['est'] === 'LDE' || $est['est'] === 'SRE') {
                        $defaultMTbuah[$est['est']][$afd['est']]['null'] = 0;
                    } else {
                        $defaultMTbuah[$est['est']][$afd['nama']]['null'] = 0;
                    }
                }
                // if ($est['est'] == $afd['est']) {
                //     $defaultMTbuah[$est['est']][$afd['nama']]['null'] = 0;
                // }
            }
        }



        // dd($mtBuahWIltab1mua);




        $mutuBuahMerge = array();
        foreach ($defaultMTbuah as $estKey => $afdArray) {
            foreach ($afdArray as $afdKey => $afdValue) {
                if (array_key_exists($estKey, $dataMTBuah)) {
                    if (array_key_exists($afdKey, $dataMTBuah[$estKey])) {
                        if (!empty($dataMTBuah[$estKey][$afdKey])) {
                            $mutuBuahMerge[$estKey][$afdKey] = $dataMTBuah[$estKey][$afdKey];
                        } else {
                            $mutuBuahMerge[$estKey][$afdKey] = $afdValue;
                        }
                    } else {
                        $mutuBuahMerge[$estKey][$afdKey] = $afdValue;
                    }
                } else {
                    $mutuBuahMerge[$estKey][$afdKey] = $afdValue;
                }
            }
        }
        $mtBuahWIltab1 = array();
        foreach ($queryEste as $key => $value) {
            foreach ($mutuBuahMerge as $key2 => $value2) {
                if ($value['est'] == $key2) {
                    $mtBuahWIltab1[$value['wil']][$key2] = array_merge($mtBuahWIltab1[$value['wil']][$key2] ?? [], $value2);
                }
            }
        }




        // dd($mtancakWIltab1);
        $rekap = [];
        foreach ($mtancakWIltab1 as $key => $value) if (!empty($value)) {
            $pokok_panenWil = 0;
            $jum_haWil = 0;
            $janjang_panenWilx = 0;
            $p_panenWil = 0;
            $k_panenWil = 0;
            $brtgl_panenWil = 0;
            $bhts_panenWil = 0;
            $bhtm1_panenWil = 0;
            $bhtm2_panenWil = 0;
            $bhtm3_oanenWil = 0;
            $pelepah_swil = 0;
            $totalPKTwil = 0;
            $data = [];
            $sumBHWil = 0;
            $akpWil = 0;
            $brdPerwil = 0;
            $sumPerBHWil = 0;
            $perPiWil = 0;
            $totalWil = 0;
            foreach ($value as $key1 => $value1) if (!empty($value2)) {
                $pokok_panenEst = 0;
                $jum_haEst =  0;
                $janjang_panenEst =  0;
                $akpEst =  0;
                $p_panenEst =  0;
                $k_panenEst =  0;
                $brtgl_panenEst = 0;
                $brdPerjjgEst =  0;
                $bhtsEST = 0;
                $bhtm1EST = 0;
                $bhtm2EST = 0;
                $bhtm3EST = 0;
                $pelepah_sEST = 0;
                foreach ($value1 as $key2 => $value2) if (!empty($value2)) {

                    $akp = 0;
                    $skor_bTinggal = 0;
                    $brdPerjjg = 0;

                    $ttlSkorMA = 0;
                    $listBlokPerAfd = array();
                    $jum_ha = 0;

                    $totalPokok = 0;
                    $totalPanen = 0;
                    $totalP_panen = 0;
                    $totalK_panen = 0;
                    $totalPTgl_panen = 0;
                    $totalbhts_panen = 0;
                    $totalbhtm1_panen = 0;
                    $totalbhtm2_panen = 0;
                    $totalbhtm3_oanen = 0;
                    $totalpelepah_s = 0;

                    $check_input = 'kosong';
                    foreach ($value2 as $key3 => $value3) if (is_array($value3)) {
                        if (!in_array($value3['estate'] . ' ' . $value3['afdeling'] . ' ' . $value3['blok'], $listBlokPerAfd)) {
                            $listBlokPerAfd[] = $value3['estate'] . ' ' . $value3['afdeling'] . ' ' . $value3['blok'];
                        }
                        $jum_ha = count($listBlokPerAfd);

                        $totalPokok += $value3["sample"];
                        $totalPanen +=  $value3["jjg"];
                        $totalP_panen += $value3["brtp"];
                        $totalK_panen += $value3["brtk"];
                        $totalPTgl_panen += $value3["brtgl"];

                        $totalbhts_panen += $value3["bhts"];
                        $totalbhtm1_panen += $value3["bhtm1"];
                        $totalbhtm2_panen += $value3["bhtm2"];
                        $totalbhtm3_oanen += $value3["bhtm3"];

                        $totalpelepah_s += $value3["ps"];
                        $check_input = $value3["jenis_input"];
                        $nilai_input = $value3["skor_akhir"];
                    }


                    if ($totalPokok != 0) {
                        $akp = round(($totalPanen / $totalPokok) * 100, 1);
                    } else {
                        $akp = 0;
                    }


                    $skor_bTinggal = $totalP_panen + $totalK_panen + $totalPTgl_panen;

                    if ($totalPanen != 0) {
                        $brdPerjjg = round($skor_bTinggal / $totalPanen, 3);
                    } else {
                        $brdPerjjg = 0;
                    }

                    $sumBH = $totalbhts_panen +  $totalbhtm1_panen +  $totalbhtm2_panen +  $totalbhtm3_oanen;
                    if ($sumBH != 0) {
                        $sumPerBH = round($sumBH / ($totalPanen + $sumBH) * 100, 3);
                    } else {
                        $sumPerBH = 0;
                    }

                    if ($totalpelepah_s != 0) {
                        $perPl = ($totalpelepah_s / $totalPokok) * 100;
                    } else {
                        $perPl = 0;
                    }
                    $nonZeroValues = array_filter([$totalP_panen, $totalK_panen, $totalPTgl_panen, $totalbhts_panen, $totalbhtm1_panen, $totalbhtm2_panen, $totalbhtm3_oanen]);

                    if (!empty($nonZeroValues)) {
                        $rekap[$key][$key1][$key2]['check_datacak'] = 'ada';
                    } else {
                        $rekap[$key][$key1][$key2]['check_datacak'] = 'kosong';
                    }

                    // $ttlSkorMA = $skor_bh + $skor_brd + $skor_ps;
                    $ttlSkorMA =  skor_buah_Ma($sumPerBH) + skor_brd_ma($brdPerjjg) + skor_palepah_ma($perPl);

                    $namaGM = '-';
                    foreach ($queryAsisten as $asisten) {

                        // dd($asisten);
                        if ($asisten['est'] == $key1 && $asisten['afd'] == $key2) {
                            $namaGM = $asisten['nama'];
                            break;
                        }
                    }
                    $rekap[$key][$key1][$key2]['pokok_samplecak'] = $totalPokok;
                    $rekap[$key][$key1][$key2]['namaGM'] = $namaGM;
                    $rekap[$key][$key1][$key2]['ha_samplecak'] = $jum_ha;
                    $rekap[$key][$key1][$key2]['jumlah_panencak'] = $totalPanen;
                    $rekap[$key][$key1][$key2]['akp_rlcak'] = $akp;
                    $rekap[$key][$key1][$key2]['pcak'] = $totalP_panen;
                    $rekap[$key][$key1][$key2]['kcak'] = $totalK_panen;
                    $rekap[$key][$key1][$key2]['tglcak'] = $totalPTgl_panen;
                    $rekap[$key][$key1][$key2]['total_brdcak'] = $skor_bTinggal;
                    $rekap[$key][$key1][$key2]['brd/jjgcak'] = $brdPerjjg;
                    // data untuk buah tinggal
                    $rekap[$key][$key1][$key2]['bhts_scak'] = $totalbhts_panen;
                    $rekap[$key][$key1][$key2]['bhtm1cak'] = $totalbhtm1_panen;
                    $rekap[$key][$key1][$key2]['bhtm2cak'] = $totalbhtm2_panen;
                    $rekap[$key][$key1][$key2]['bhtm3cak'] = $totalbhtm3_oanen;
                    $rekap[$key][$key1][$key2]['buah/jjgcak'] = $sumPerBH;
                    $rekap[$key][$key1][$key2]['total_buahcak'] = $sumBH;
                    $rekap[$key][$key1][$key2]['jjgperBuahcak'] = number_format($sumPerBH, 3);
                    // data untuk pelepah sengklek
                    $rekap[$key][$key1][$key2]['palepah_pokokcak'] = $totalpelepah_s;
                    $rekap[$key][$key1][$key2]['palepah_percak'] = $perPl;
                    $rekap[$key][$key1][$key2]['skor_bhcak'] = skor_buah_Ma($sumPerBH);
                    $rekap[$key][$key1][$key2]['skor_brdcak'] = skor_brd_ma($brdPerjjg);
                    $rekap[$key][$key1][$key2]['skor_pscak'] =  skor_palepah_ma($perPl);
                    // total skor akhir
                    $rekap[$key][$key1][$key2]['skor_akhircak'] = $ttlSkorMA;
                    $rekap[$key][$key1][$key2]['check_inputcak'] = $check_input;
                    $rekap[$key][$key1][$key2]['est'] = $key1;
                    $rekap[$key][$key1][$key2]['afd'] = $key2;
                    $rekap[$key][$key1][$key2]['mutuancak'] = '-----------------------------------';

                    $pokok_panenEst += $totalPokok;

                    $jum_haEst += $jum_ha;
                    $janjang_panenEst += $totalPanen;

                    $p_panenEst += $totalP_panen;
                    $k_panenEst += $totalK_panen;
                    $brtgl_panenEst += $totalPTgl_panen;

                    // bagian buah tinggal
                    $bhtsEST   += $totalbhts_panen;
                    $bhtm1EST += $totalbhtm1_panen;
                    $bhtm2EST   += $totalbhtm2_panen;
                    $bhtm3EST   += $totalbhtm3_oanen;
                    // data untuk pelepah sengklek
                    $pelepah_sEST += $totalpelepah_s;
                } else {
                    $rekap[$key][$key1][$key2]['pokok_samplecak'] = 0;
                    $rekap[$key][$key1][$key2]['ha_samplecak'] = 0;
                    $rekap[$key][$key1][$key2]['jumlah_panencak'] = 0;
                    $rekap[$key][$key1][$key2]['akp_rlcak'] =  0;
                    $rekap[$key][$key1][$key2]['est'] = $key1;
                    $rekap[$key][$key1][$key2]['afd'] = $key2;
                    $rekap[$key][$key1][$key2]['pcak'] = 0;
                    $rekap[$key][$key1][$key2]['kcak'] = 0;
                    $rekap[$key][$key1][$key2]['tglcak'] = 0;

                    // $rekap[$key][$key1][$key2]['total_brdcak'] = $skor_bTinggal;
                    $rekap[$key][$key1][$key2]['brd/jjgcak'] = 0;

                    // data untuk buah tinggal
                    $rekap[$key][$key1][$key2]['bhts_scak'] = 0;
                    $rekap[$key][$key1][$key2]['bhtm1cak'] = 0;
                    $rekap[$key][$key1][$key2]['bhtm2cak'] = 0;
                    $rekap[$key][$key1][$key2]['bhtm3cak'] = 0;
                    $rekap[$key][$key1][$key2]['total_buahcak'] = 0;

                    // $rekap[$key][$key1][$key2]['jjgperBuahcak'] = number_format($sumPerBH, 3);
                    // data untuk pelepah sengklek

                    $rekap[$key][$key1][$key2]['palepah_pokokcak'] = 0;
                    // total skor akhi0;

                    $rekap[$key][$key1][$key2]['skor_bhcak'] = 0;
                    $rekap[$key][$key1][$key2]['skor_brdcak'] = 0;
                    $rekap[$key][$key1][$key2]['skor_pscak'] = 0;
                    $rekap[$key][$key1][$key2]['skor_akhircak'] = 0;
                    $rekap[$key][$key1][$key2]['mutuancak'] = '-----------------------------------';
                }

                $sumBHEst = $bhtsEST +  $bhtm1EST +  $bhtm2EST +  $bhtm3EST;
                $totalPKT = $p_panenEst + $k_panenEst + $brtgl_panenEst;
                // dd($sumBHEst);
                if ($pokok_panenEst != 0) {
                    $akpEst = round(($janjang_panenEst / $pokok_panenEst) * 100, 3);
                } else {
                    $akpEst = 0;
                }

                if ($janjang_panenEst != 0) {
                    $brdPerjjgEst = round($totalPKT / $janjang_panenEst, 3);
                } else {
                    $brdPerjjgEst = 0;
                }



                // dd($sumBHEst);
                if ($sumBHEst != 0) {
                    $sumPerBHEst = round($sumBHEst / ($janjang_panenEst + $sumBHEst) * 100, 3);
                } else {
                    $sumPerBHEst = 0;
                }

                if ($pokok_panenEst != 0) {
                    $perPlEst = round(($pelepah_sEST / $pokok_panenEst) * 100, 3);
                } else {
                    $perPlEst = 0;
                }


                $nonZeroValues = array_filter([$p_panenEst, $k_panenEst, $brtgl_panenEst, $bhtsEST, $bhtm1EST, $bhtm2EST, $bhtm3EST]);

                if (!empty($nonZeroValues)) {
                    $check_data = 'ada';
                } else {
                    $check_data = 'kosong';
                }

                // $totalSkorEst = $skor_bh + $skor_brd + $skor_ps;

                $totalSkorEst =  skor_brd_ma($brdPerjjgEst) + skor_buah_Ma($sumPerBHEst) + skor_palepah_ma($perPlEst);
                //PENAMPILAN UNTUK PERESTATE
                $namaGM = '-';
                foreach ($queryAsisten as $asisten) {

                    // dd($asisten);
                    if ($asisten['est'] == $key1 && $asisten['afd'] == 'EM') {
                        $namaGM = $asisten['nama'];
                        break;
                    }
                }
                $rekap[$key][$key1][$key2]['pokok_samplecak'] = $totalPokok;
                $rekap[$key][$key1]['est']['estancak'] = [
                    'pokok_samplecak' => $totalPKT,
                    'namaGM' => $namaGM,
                    'ha_samplecak' =>  $jum_haEst,
                    'jumlah_panencak' => $janjang_panenEst,
                    'akp_rlcak' =>  $akpEst,
                    'pcak' => $p_panenEst,
                    'kcak' => $k_panenEst,
                    'tglcak' => $brtgl_panenEst,
                    'total_brdcak' => $skor_bTinggal,
                    'brd/jjgcak' => $brdPerjjgEst,
                    'bhts_scak' => $bhtsEST,
                    'bhtm1cak' => $bhtm1EST,
                    'bhtm2cak' => $bhtm2EST,
                    'bhtm3cak' => $bhtm3EST,
                    'buah/jjgcak' => $sumPerBHEst,
                    'total_buahcak' => $sumBHEst,
                    'palepah_pokokcak' => $pelepah_sEST,
                    'palepah_percak' => $perPlEst,
                    'skor_bhcak' => skor_buah_Ma($sumPerBHEst),
                    'skor_brdcak' => skor_brd_ma($brdPerjjgEst),
                    'skor_pscak' =>  skor_palepah_ma($perPlEst),
                    'skor_akhircak' =>  $totalSkorEst,
                    'check_datacak' => $check_data,
                    'est' => $key1,
                    'afd' => 'est',
                    'mutuancak' => '-----------------------------------'
                ];


                //perhitungn untuk perwilayah

                $pokok_panenWil += $pokok_panenEst;
                $jum_haWil += $jum_haEst;
                $janjang_panenWilx += $janjang_panenEst;
                $p_panenWil += $p_panenEst;
                $k_panenWil += $k_panenEst;
                $brtgl_panenWil += $brtgl_panenEst;
                // bagian buah tinggal
                $bhts_panenWil += $bhtsEST;
                $bhtm1_panenWil += $bhtm1EST;
                $bhtm2_panenWil += $bhtm2EST;
                $bhtm3_oanenWil += $bhtm3EST;
                $pelepah_swil += $pelepah_sEST;

                if ($key1 === 'LDE' || $key1 === 'SRE') {

                    $data[] = $janjang_panenEst;
                }
            } else {
                $rekap[$key][$key1]['est']['estancak'] = [
                    'pokok_samplecak' => 0,
                    'ha_samplecak' => 0,
                    'jumlah_panencak' => 0,
                    'akp_rlcak' => 0,
                    'pcak' => 0,
                    'kcak' => 0,
                    'tglcak' => 0,
                    'total_brdcak' => 0,
                    'brd/jjgcak' => 0,
                    'bhts_scak' => 0,
                    'bhtm1cak' => 0,
                    'bhtm2cak' => 0,
                    'bhtm3cak' => 0,
                    'buah/jjgcak' => 0,
                    'total_buahcak' => 0,
                    'palepah_pokokcak' => 0,
                    'palepah_percak' => 0,
                    'skor_bhcak' => 0,
                    'skor_brdcak' => 0,
                    'skor_pscak' => 0,
                    'skor_akhircak' => 0,
                    'check_datacak' => 'kosong',
                    'est' => $key1,
                    'afd' => 'est',
                    'mutuancak' => '-----------------------------------'
                ];
            }
            $totalPKTwil = $p_panenWil + $k_panenWil + $brtgl_panenWil;
            $sumBHWil = $bhts_panenWil +  $bhtm1_panenWil +  $bhtm2_panenWil +  $bhtm3_oanenWil;
            $janjang_panenWil = $janjang_panenWilx;

            if ($janjang_panenWil == 0 || $pokok_panenWil == 0) {
                $akpWil = 0;
            } else {

                $akpWil = round(($janjang_panenWil / $pokok_panenWil) * 100, 3);
            }

            if ($totalPKTwil != 0) {
                $brdPerwil = round($totalPKTwil / $janjang_panenWil, 3);
            } else {
                $brdPerwil = 0;
            }

            // dd($sumBHEst);
            if ($sumBHWil != 0) {
                $sumPerBHWil = round($sumBHWil / ($janjang_panenWil + $sumBHWil) * 100, 3);
            } else {
                $sumPerBHWil = 0;
            }

            if ($pokok_panenWil != 0) {
                $perPiWil = round(($pelepah_swil / $pokok_panenWil) * 100, 3);
            } else {
                $perPiWil = 0;
            }

            $nonZeroValues = array_filter([$p_panenWil, $k_panenWil, $brtgl_panenWil, $bhts_panenWil, $bhtm1_panenWil, $bhtm2_panenWil, $bhtm3_oanenWil]);

            if (!empty($nonZeroValues)) {
                // $rekap[$key]['check_data'] = 'ada';
                $check_data = 'ada';
                // $rekap[$key]['skor_brd'] = $skor_brd = skor_brd_ma($brdPerwil);
                // $rekap[$key]['skor_ps'] = $skor_ps = skor_palepah_ma($perPiWil);
            } else {
                // $rekap[$key]['check_data'] = 'kosong';
                $check_data = 'kosong';
                // $rekap[$key]['skor_brd'] = $skor_brd = 0;
                // $rekap[$key]['skor_ps'] = $skor_ps = 0;
            }

            // $totalWil = $skor_bh + $skor_brd + $skor_ps;
            $totalWil = skor_brd_ma($brdPerwil) + skor_buah_Ma($sumPerBHWil) + skor_palepah_ma($perPiWil);
            $namaGM = '-';
            $namewil = 'WIL-' . convertToRoman($key);
            foreach ($queryAsisten as $asisten) {

                // dd($asisten);
                if ($asisten['est'] == $namewil && $asisten['afd'] == 'GM') {
                    $namaGM = $asisten['nama'];
                    break;
                }
            }
            $rekap[$key]['wil']['wilancak'] = [
                'data' =>  $data,
                'namewil' =>  $namewil,
                'namaGM' =>  $namaGM,
                'pokok_samplecak' =>  $pokok_panenWil,
                'ha_samplecak' =>   $jum_haWil,
                'check_datacak' =>   $check_data,
                'jumlah_panencak' =>  $janjang_panenWil,
                'akp_rlcak' =>   $akpWil,
                'pcak' =>  $p_panenWil,
                'kcak' =>  $k_panenWil,
                'tglcak' =>  $brtgl_panenWil,
                'total_brdcak' =>  $totalPKTwil,
                'brd/jjgcak' =>  $brdPerwil,
                'buah/jjgwilcak' =>  $sumPerBHWil,
                'bhts_scak' =>  $bhts_panenWil,
                'bhtm1cak' =>  $bhtm1_panenWil,
                'bhtm2cak' =>  $bhtm2_panenWil,
                'bhtm3cak' =>  $bhtm3_oanenWil,
                'total_buahcak' =>  $sumBHWil,
                'buah/jjgcak' =>  $sumPerBHWil,
                'jjgperBuahcak' =>  number_format($sumPerBHWil, 3),
                'palepah_pokokcak' =>  $pelepah_swil,
                'palepah_percak' =>  $perPiWil,
                'skor_bhcak' =>  skor_buah_Ma($sumPerBHWil),
                'skor_brdcak' =>  skor_brd_ma($brdPerwil),
                'skor_pscak' =>  skor_palepah_ma($perPiWil),
                'skor_akhircak' =>  $totalWil,
                'afd' => convertToRoman($key),
                'est' => 'WIL',
                'mutuancak' => '-----------------------------------'
            ];
        } else {
            $rekap[$key]['wil']['wilancak'] = [
                'pokok_samplecak' => 0,
                'ha_samplecak' => 0,
                'check_datacak' => 'kosong',
                'jumlah_panencak' => 0,
                'akp_rlcak' => 0,
                'pcak' => 0,
                'kcak' => 0,
                'tglcak' => 0,
                'total_brdcak' => 0,
                'brd/jjgcak' => 0,
                'buah/jjgwilcak' => 0,
                'bhts_scak' => 0,
                'bhtm1cak' => 0,
                'bhtm2cak' => 0,
                'bhtm3cak' => 0,
                'total_buahcak' => 0,
                'buah/jjgcak' => 0,
                'jjgperBuahcak' => 0,
                'palepah_pokokcak' => 0,
                'palepah_percak' => 0,
                'skor_bhcak' => 0,
                'skor_brdcak' => 0,
                'skor_pscak' => 0,
                'skor_akhircak' => 0,
                'est' => $key,
                'afd' => 'wil',
                'mutuancak' => '-----------------------------------'
            ];
        }
        // dd($rekap);
        foreach ($mtBuahWIltab1 as $key => $value) if (is_array($value)) {
            $jum_haWil = 0;
            $sum_SamplejjgWil = 0;
            $sum_bmtWil = 0;
            $sum_bmkWil = 0;
            $sum_overWil = 0;
            $sum_abnorWil = 0;
            $sum_kosongjjgWil = 0;
            $sum_vcutWil = 0;
            $sum_krWil = 0;
            $no_Vcutwil = 0;

            foreach ($value as $key1 => $value1) if (is_array($value1)) {
                $jum_haEst  = 0;
                $sum_SamplejjgEst = 0;
                $sum_bmtEst = 0;
                $sum_bmkEst = 0;
                $sum_overEst = 0;
                $sum_abnorEst = 0;
                $sum_kosongjjgEst = 0;
                $sum_vcutEst = 0;
                $sum_krEst = 0;
                $no_VcutEst = 0;

                foreach ($value1 as $key2 => $value2) if (is_array($value2)) {
                    $sum_bmt = 0;
                    $sum_bmk = 0;
                    $sum_over = 0;
                    $dataBLok = 0;
                    $sum_Samplejjg = 0;
                    $PerMth = 0;
                    $PerMsk = 0;
                    $PerOver = 0;
                    $sum_abnor = 0;
                    $sum_kosongjjg = 0;
                    $Perkosongjjg = 0;
                    $sum_vcut = 0;
                    $PerVcut = 0;
                    $PerAbr = 0;
                    $sum_kr = 0;
                    $total_kr = 0;
                    $per_kr = 0;
                    $totalSkor = 0;
                    $jum_ha = 0;
                    $no_Vcut = 0;
                    $jml_mth = 0;
                    $jml_mtg = 0;
                    $dataBLok = 0;
                    $listBlokPerAfd = [];
                    $dtBlok = 0;
                    // $combination_counts = array();
                    foreach ($value2 as $key3 => $value3) if (is_array($value3)) {
                        $listBlokPerAfd[] = $value3['estate'] . ' ' . $value3['afdeling'] . ' ' . $value3['blok'] . ' ' . $value3['tph_baris'];
                        $dtBlok = count($listBlokPerAfd);

                        // $jum_ha = count($listBlokPerAfd);
                        $sum_bmt += $value3['bmt'];
                        $sum_bmk += $value3['bmk'];
                        $sum_over += $value3['overripe'];
                        $sum_kosongjjg += $value3['empty_bunch'];
                        $sum_vcut += $value3['vcut'];
                        $sum_kr += $value3['alas_br'];


                        $sum_Samplejjg += $value3['jumlah_jjg'];
                        $sum_abnor += $value3['abnormal'];
                    }

                    // $dataBLok = count($combination_counts);
                    $dataBLok = $dtBlok;
                    $jml_mth = ($sum_bmt + $sum_bmk);
                    $jml_mtg = $sum_Samplejjg - ($jml_mth + $sum_over + $sum_kosongjjg + $sum_abnor);

                    if ($sum_kr != 0) {
                        $total_kr = round($sum_kr / $dataBLok, 3);
                    } else {
                        $total_kr = 0;
                    }


                    $per_kr = round($total_kr * 100, 3);
                    if ($jml_mth != 0) {
                        $PerMth = round(($jml_mth / ($sum_Samplejjg - $sum_abnor)) * 100, 3);
                    } else {
                        $PerMth = 0;
                    }
                    if ($jml_mtg != 0) {
                        $PerMsk = round(($jml_mtg / ($sum_Samplejjg - $sum_abnor)) * 100, 3);
                    } else {
                        $PerMsk = 0;
                    }
                    if ($sum_over != 0) {
                        $PerOver = round(($sum_over / ($sum_Samplejjg - $sum_abnor)) * 100, 3);
                    } else {
                        $PerOver = 0;
                    }
                    if ($sum_kosongjjg != 0) {
                        $Perkosongjjg = round(($sum_kosongjjg / ($sum_Samplejjg - $sum_abnor)) * 100, 3);
                    } else {
                        $Perkosongjjg = 0;
                    }
                    if ($sum_vcut != 0) {
                        $PerVcut = round(($sum_vcut / $sum_Samplejjg) * 100, 3);
                    } else {
                        $PerVcut = 0;
                    }

                    if ($sum_abnor != 0) {
                        $PerAbr = round(($sum_abnor / $sum_Samplejjg) * 100, 3);
                    } else {
                        $PerAbr = 0;
                    }

                    $nonZeroValues = array_filter([$sum_Samplejjg, $jml_mth, $jml_mtg, $sum_over, $sum_abnor, $sum_kosongjjg, $sum_vcut, $dataBLok]);

                    if (!empty($nonZeroValues)) {
                        $rekap[$key][$key1][$key2]['check_databh'] = 'ada';
                    } else {
                        $rekap[$key][$key1][$key2]['check_databh'] = 'kosong';
                    }
                    $totalSkor =  skor_buah_mentah_mb($PerMth) + skor_buah_masak_mb($PerMsk) + skor_buah_over_mb($PerOver) + skor_vcut_mb($PerVcut) + skor_jangkos_mb($Perkosongjjg) + skor_abr_mb($per_kr);
                    $rekap[$key][$key1][$key2]['tph_baris_bloksbh'] = $dataBLok;
                    $rekap[$key][$key1][$key2]['sampleJJG_totalbh'] = $sum_Samplejjg;
                    $rekap[$key][$key1][$key2]['total_mentahbh'] = $jml_mth;
                    $rekap[$key][$key1][$key2]['total_perMentahbh'] = $PerMth;
                    $rekap[$key][$key1][$key2]['total_masakbh'] = $jml_mtg;
                    $rekap[$key][$key1][$key2]['total_perMasakbh'] = $PerMsk;
                    $rekap[$key][$key1][$key2]['total_overbh'] = $sum_over;
                    $rekap[$key][$key1][$key2]['total_perOverbh'] = $PerOver;
                    $rekap[$key][$key1][$key2]['total_abnormalbh'] = $sum_abnor;
                    $rekap[$key][$key1][$key2]['perAbnormalbh'] = $PerAbr;
                    $rekap[$key][$key1][$key2]['total_jjgKosongbh'] = $sum_kosongjjg;
                    $rekap[$key][$key1][$key2]['total_perKosongjjgbh'] = $Perkosongjjg;
                    $rekap[$key][$key1][$key2]['total_vcutbh'] = $sum_vcut;
                    $rekap[$key][$key1][$key2]['perVcutbh'] = $PerVcut;

                    $rekap[$key][$key1][$key2]['jum_krbh'] = $sum_kr;
                    $rekap[$key][$key1][$key2]['total_krbh'] = $total_kr;
                    $rekap[$key][$key1][$key2]['persen_krbh'] = $per_kr;

                    // skoring
                    $rekap[$key][$key1][$key2]['skor_mentahbh'] = skor_buah_mentah_mb($PerMth);
                    $rekap[$key][$key1][$key2]['skor_masakbh'] = skor_buah_masak_mb($PerMsk);
                    $rekap[$key][$key1][$key2]['skor_overbh'] = skor_buah_over_mb($PerOver);
                    $rekap[$key][$key1][$key2]['skor_jjgKosongbh'] = skor_jangkos_mb($Perkosongjjg);
                    $rekap[$key][$key1][$key2]['skor_vcutbh'] = skor_vcut_mb($PerVcut);
                    $rekap[$key][$key1][$key2]['skor_krbh'] = skor_abr_mb($per_kr);
                    $rekap[$key][$key1][$key2]['TOTAL_SKORbh'] = $totalSkor;
                    $rekap[$key][$key1][$key2]['mutubuah'] = '-----------------------------------------';

                    //perhitungan estate
                    $jum_haEst += $dataBLok;
                    $sum_SamplejjgEst += $sum_Samplejjg;
                    $sum_bmtEst += $jml_mth;
                    $sum_bmkEst += $jml_mtg;
                    $sum_overEst += $sum_over;
                    $sum_abnorEst += $sum_abnor;
                    $sum_kosongjjgEst += $sum_kosongjjg;
                    $sum_vcutEst += $sum_vcut;
                    $sum_krEst += $sum_kr;
                } else {
                    $rekap[$key][$key1][$key2]['tph_baris_bloksbh'] = 0;
                    $rekap[$key][$key1][$key2]['sampleJJG_totalbh'] = 0;
                    $rekap[$key][$key1][$key2]['total_mentahbh'] = 0;
                    $rekap[$key][$key1][$key2]['total_perMentahbh'] = 0;
                    $rekap[$key][$key1][$key2]['total_masakbh'] = 0;
                    $rekap[$key][$key1][$key2]['total_perMasakbh'] = 0;
                    $rekap[$key][$key1][$key2]['total_overbh'] = 0;
                    $rekap[$key][$key1][$key2]['total_perOverbh'] = 0;
                    $rekap[$key][$key1][$key2]['total_abnormalbh'] = 0;
                    $rekap[$key][$key1][$key2]['perAbnormalbh'] = 0;
                    $rekap[$key][$key1][$key2]['total_jjgKosongbh'] = 0;
                    $rekap[$key][$key1][$key2]['total_perKosongjjgbh'] = 0;
                    $rekap[$key][$key1][$key2]['total_vcutbh'] = 0;
                    $rekap[$key][$key1][$key2]['perVcutbh'] = 0;

                    $rekap[$key][$key1][$key2]['jum_krbh'] = 0;
                    $rekap[$key][$key1][$key2]['total_krbh'] = 0;
                    $rekap[$key][$key1][$key2]['persen_krbh'] = 0;

                    // skoring
                    $rekap[$key][$key1][$key2]['skor_mentahbh'] = 0;
                    $rekap[$key][$key1][$key2]['skor_masakbh'] = 0;
                    $rekap[$key][$key1][$key2]['skor_overbh'] = 0;
                    $rekap[$key][$key1][$key2]['skor_jjgKosongbh'] = 0;
                    $rekap[$key][$key1][$key2]['skor_vcutbh'] = 0;
                    $rekap[$key][$key1][$key2]['skor_abnormalbh'] = 0;;
                    $rekap[$key][$key1][$key2]['skor_krbh'] = 0;
                    $rekap[$key][$key1][$key2]['TOTAL_SKORbh'] = 0;
                    $rekap[$key][$key1][$key2]['mutubuah'] = '-----------------------------------------';
                }
                $no_VcutEst = $sum_SamplejjgEst - $sum_vcutEst;

                if ($sum_krEst != 0) {
                    $total_krEst = round($sum_krEst / $jum_haEst, 3);
                } else {
                    $total_krEst = 0;
                }

                if ($sum_bmtEst != 0) {
                    $PerMthEst = round(($sum_bmtEst / ($sum_SamplejjgEst - $sum_abnorEst)) * 100, 3);
                } else {
                    $PerMthEst = 0;
                }

                if ($sum_bmkEst != 0) {
                    $PerMskEst = round(($sum_bmkEst / ($sum_SamplejjgEst - $sum_abnorEst)) * 100, 3);
                } else {
                    $PerMskEst = 0;
                }

                if ($sum_overEst != 0) {
                    $PerOverEst = round(($sum_overEst / ($sum_SamplejjgEst - $sum_abnorEst)) * 100, 3);
                } else {
                    $PerOverEst = 0;
                }
                if ($sum_kosongjjgEst != 0) {
                    $PerkosongjjgEst = round(($sum_kosongjjgEst / ($sum_SamplejjgEst - $sum_abnorEst)) * 100, 3);
                } else {
                    $PerkosongjjgEst = 0;
                }
                if ($sum_vcutEst != 0) {
                    $PerVcutest = round(($sum_vcutEst / $sum_SamplejjgEst) * 100, 3);
                } else {
                    $PerVcutest = 0;
                }
                if ($sum_abnorEst != 0) {
                    $PerAbrest = round(($sum_abnorEst / $sum_SamplejjgEst) * 100, 3);
                } else {
                    $PerAbrest = 0;
                }
                // $per_kr = round($sum_kr * 100);
                $per_krEst = round($total_krEst * 100, 3);


                $nonZeroValues = array_filter([$sum_SamplejjgEst, $sum_bmtEst, $sum_bmkEst, $sum_overEst, $sum_abnorEst, $sum_kosongjjgEst, $sum_vcutEst]);

                if (!empty($nonZeroValues)) {
                    // $rekap[$key][$key1]['check_data'] = 'ada';
                    $check_data = 'ada';
                } else {
                    // $rekap[$key][$key1]['check_data'] = 'kosong';
                    $check_data = 'kosong';
                }

                // $totalSkorEst = $skor_mentah + $skor_masak + $skor_over + $skor_jjgKosong + $skor_vcut + $skor_kr;

                $totalSkorEst =   skor_buah_mentah_mb($PerMthEst) + skor_buah_masak_mb($PerMskEst) + skor_buah_over_mb($PerOverEst) + skor_jangkos_mb($PerkosongjjgEst) + skor_vcut_mb($PerVcutest) + skor_abr_mb($per_krEst);


                $rekap[$key][$key1]['est']['estbuah'] = [
                    'check_databh' => $check_data,
                    'tph_baris_bloksbh' => $jum_haEst,
                    'sampleJJG_totalbh' => $sum_SamplejjgEst,
                    'total_mentahbh' => $sum_bmtEst,
                    'total_perMentahbh' => $PerMthEst,
                    'total_masakbh' => $sum_bmkEst,
                    'total_perMasakbh' => $PerMskEst,
                    'total_overbh' => $sum_overEst,
                    'total_perOverbh' => $PerOverEst,
                    'total_abnormalbh' => $sum_abnorEst,
                    'perAbnormalbh' => $PerAbrest,
                    'total_jjgKosongbh' => $sum_kosongjjgEst,
                    'total_perKosongjjgbh' => $PerkosongjjgEst,
                    'total_vcutbh' => $sum_vcutEst,
                    'perVcutbh' => $PerVcutest,
                    'jum_krbh' => $sum_krEst,
                    'total_krbh' => $total_krEst,
                    'persen_krbh' => $per_krEst,
                    'skor_mentahbh' =>  skor_buah_mentah_mb($PerMthEst),
                    'skor_masakbh' => skor_buah_masak_mb($PerMskEst),
                    'skor_overbh' => skor_buah_over_mb($PerOverEst),
                    'skor_jjgKosongbh' => skor_jangkos_mb($PerkosongjjgEst),
                    'skor_vcutbh' => skor_vcut_mb($PerVcutest),
                    'skor_krbh' => skor_abr_mb($per_krEst),
                    'TOTAL_SKORbh' => $totalSkorEst,
                    'mutubuah' => '------------------------------------------',
                ];


                //hitung perwilayah
                $jum_haWil += $jum_haEst;
                $sum_SamplejjgWil += $sum_SamplejjgEst;
                $sum_bmtWil += $sum_bmtEst;
                $sum_bmkWil += $sum_bmkEst;
                $sum_overWil += $sum_overEst;
                $sum_abnorWil += $sum_abnorEst;
                $sum_kosongjjgWil += $sum_kosongjjgEst;
                $sum_vcutWil += $sum_vcutEst;
                $sum_krWil += $sum_krEst;
            } else {
                $rekap[$key][$key1]['est']['estbuah'] = [
                    'check_databh' => 'kosong',
                    'tph_baris_bloksbh'  => 0,
                    'sampleJJG_totalbh'  => 0,
                    'total_mentahbh' => 0,
                    'total_perMentahbh'  => 0,
                    'total_masakbh' => 0,
                    'total_perMasakbh' => 0,
                    'total_overbh' => 0,
                    'total_perOverbh' => 0,
                    'total_abnormalbh' => 0,
                    'perAbnormalbh' => 0,
                    'total_jjgKosongbh'  => 0,
                    'total_perKosongjjgbh' => 0,
                    'total_vcutbh' => 0,
                    'perVcutbh' => 0,
                    'jum_krbh'  => 0,
                    'total_krbh'  => 0,
                    'persen_krbh'  => 0,
                    'skor_mentahbh' => 0,
                    'skor_masakbh'  => 0,
                    'skor_overbh' => 0,
                    'skor_jjgKosongbh' => 0,
                    'skor_vcutbh'  => 0,
                    'skor_krbh'  => 0,
                    'TOTAL_SKORbh'  => 0,
                    'mutubuah' => '------------------------------------------',
                ];
            }

            // if ($sum_kr != 0) {
            //     $total_kr = round($sum_kr / $dataBLok, 3);
            // } else {
            //     $total_kr = 0;
            // }



            if ($sum_krWil != 0) {
                $total_krWil = round($sum_krWil / $jum_haWil, 3);
            } else {
                $total_krWil = 0;
            }

            if ($sum_bmtWil != 0) {
                $PerMthWil = round(($sum_bmtWil / ($sum_SamplejjgWil - $sum_abnorWil)) * 100, 3);
            } else {
                $PerMthWil = 0;
            }


            if ($sum_bmkWil != 0) {
                $PerMskWil = round(($sum_bmkWil / ($sum_SamplejjgWil - $sum_abnorWil)) * 100, 3);
            } else {
                $PerMskWil = 0;
            }
            if ($sum_overWil != 0) {
                $PerOverWil = round(($sum_overWil / ($sum_SamplejjgWil - $sum_abnorWil)) * 100, 3);
            } else {
                $PerOverWil = 0;
            }
            if ($sum_kosongjjgWil != 0) {
                $PerkosongjjgWil = round(($sum_kosongjjgWil / ($sum_SamplejjgWil - $sum_abnorWil)) * 100, 3);
            } else {
                $PerkosongjjgWil = 0;
            }
            if ($sum_vcutWil != 0) {
                $PerVcutWil = round(($sum_vcutWil / $sum_SamplejjgWil) * 100, 3);
            } else {
                $PerVcutWil = 0;
            }
            if ($sum_abnorWil != 0) {
                $PerAbrWil = round(($sum_abnorWil / $sum_SamplejjgWil) * 100, 3);
            } else {
                $PerAbrWil = 0;
            }
            $per_krWil = round($total_krWil * 100, 3);

            $nonZeroValues = array_filter([$sum_SamplejjgWil, $sum_bmtWil, $sum_bmkWil, $sum_overWil, $sum_abnorWil, $sum_kosongjjgWil, $sum_vcutWil]);

            if (!empty($nonZeroValues)) {
                // $rekap[$key]['check_data'] = 'ada';
                $check_data = 'ada';
            } else {
                // $rekap[$key]['check_data'] = 'kosong';
                $check_data = 'kosong';
            }

            $totalSkorWil =  skor_buah_mentah_mb($PerMthWil) + skor_buah_masak_mb($PerMskWil) + skor_buah_over_mb($PerOverWil) + skor_jangkos_mb($PerkosongjjgWil) + skor_vcut_mb($PerVcutWil) + skor_abr_mb($per_krWil);

            $rekap[$key]['wil']['wilbuah']  = [
                'check_databh' => $check_data,
                'tph_baris_bloksbh' => $jum_haWil,
                'sampleJJG_totalbh' => $sum_SamplejjgWil,
                'total_mentahbh' => $sum_bmtWil,
                'total_perMentahbh' => $PerMthWil,
                'total_masakbh' => $sum_bmkWil,
                'total_perMasakbh' => $PerMskWil,
                'total_overbh' => $sum_overWil,
                'total_perOverbh' => $PerOverWil,
                'total_abnormalbh' => $sum_abnorWil,
                'perAbnormalbh' => $PerAbrWil,
                'total_jjgKosongbh' => $sum_kosongjjgWil,
                'total_perKosongjjgbh' => $PerkosongjjgWil,
                'total_vcutbh' => $sum_vcutWil,
                'perVcutbh' => $PerVcutWil,
                'jum_krbh' => $sum_krWil,
                'total_krbh' => $total_krWil,
                'persen_krbh' => $per_krWil,

                // skoring
                'skor_mentahbh' => skor_buah_mentah_mb($PerMthWil),
                'skor_masakbh' => skor_buah_masak_mb($PerMskWil),
                'skor_overbh' => skor_buah_over_mb($PerOverWil),
                'skor_jjgKosongbh' => skor_jangkos_mb($PerkosongjjgWil),
                'skor_vcutbh' => skor_vcut_mb($PerVcutWil),
                'skor_krbh' => skor_abr_mb($per_krWil),
                'TOTAL_SKORbh' => $totalSkorWil,
                'mutubuah' => '------------------------------------------',
            ];
        } else {

            $rekap[$key]['wil']['wilbuah']  = [
                'check_databh' => 'kosong',
                'tph_baris_bloksbh' => 0,
                'sampleJJG_totalbh' => 0,
                'total_mentahbh' => 0,
                'total_perMentahbh' => 0,
                'total_masakbh' => 0,
                'total_perMasakbh' => 0,
                'total_overbh' => 0,
                'total_perOverbh' => 0,
                'total_abnormalbh' => 0,
                'perAbnormalbh' => 0,
                'total_jjgKosongbh' => 0,
                'total_perKosongjjgbh' => 0,
                'total_vcutbh' => 0,
                'perVcutbh' => 0,
                'jum_krbh' => 0,
                'total_krbh' => 0,
                'persen_krbh' => 0,
                // skoring
                'skor_mentahbh' => 0,
                'skor_masakbh' => 0,
                'skor_overbh' => 0,
                'skor_jjgKosongbh' => 0,
                'skor_vcutbh' => 0,
                'skor_krbh' => 0,
                'TOTAL_SKORbh' => 0,
                'mutubuah' => '------------------------------------------',
            ];
        }
        // dd($rekap[3]);

        $TranscakReg2 = DB::connection('mysql2')->table('mutu_transport')
            ->select(
                "mutu_transport.*",
                DB::raw('DATE_FORMAT(mutu_transport.datetime, "%Y-%m-%d") as date')
            )
            ->where('datetime', 'like', '%' . $bulan . '%')
            ->orderBy('datetime', 'DESC')
            ->orderBy(DB::raw('SECOND(datetime)'), 'DESC')
            ->get();
        $AncakCakReg2 = DB::connection('mysql2')->table('mutu_ancak_new')
            ->select(
                "mutu_ancak_new.*",
                DB::raw('DATE_FORMAT(mutu_ancak_new.datetime, "%Y-%m-%d") as date')
            )
            ->where('datetime', 'like', '%' . $bulan . '%')
            ->orderBy('datetime', 'DESC')
            ->orderBy(DB::raw('SECOND(datetime)'), 'DESC')
            ->get();

        $TranscakReg2 = $TranscakReg2->groupBy(['estate', 'afdeling', 'date', 'blok']);
        $AncakCakReg2 = $AncakCakReg2->groupBy(['estate', 'afdeling', 'date', 'blok']);

        // dd($TranscakReg2);

        // dd($TranscakReg2[1]);


        $DataTransGroupReg2 = json_decode($TranscakReg2, true);


        $groupedDataAcnakreg2 = json_decode($AncakCakReg2, true);
        // dd($groupedDataAcnakreg2);


        $dataMTTransRegs2 = array();
        foreach ($DataTransGroupReg2 as $key => $value) {
            foreach ($queryEste as $est => $estval)
                if ($estval['est'] === $key) {
                    foreach ($value as $key2 => $value2) {
                        foreach ($queryAfd as $afd => $afdval)
                            if ($afdval['est'] === $key && $afdval['nama'] === $key2) {
                                foreach ($value2 as $key3 => $value3) {

                                    foreach ($value3 as $key4 => $value4) {

                                        $dataMTTransRegs2[$afdval['est']][$afdval['nama']][$key3][$key4] = $value4;
                                    }
                                }
                            }
                    }
                }
        }

        // dd($dataMTTransRegs2, $dataMTTransRegs2);
        $dataAncaksRegs2 = array();
        foreach ($groupedDataAcnakreg2 as $key => $value) {
            foreach ($queryEste as $est => $estval)
                if ($estval['est'] === $key) {
                    foreach ($value as $key2 => $value2) {
                        foreach ($queryAfd as $afd => $afdval)
                            if ($afdval['est'] === $key && $afdval['nama'] === $key2) {
                                foreach ($value2 as $key3 => $value3) {
                                    foreach ($value3 as $key4 => $value4) {
                                        $dataAncaksRegs2[$afdval['est']][$afdval['nama']][$key3][$key4] = $value4;
                                    }
                                }
                            }
                    }
                }
        }
        // dd($dataMTTransRegs2);
        $ancakRegss2 = array();

        foreach ($dataAncaksRegs2 as $key => $value) {
            foreach ($value as $key1 => $value2) {
                foreach ($value2 as $key2 => $value3) {
                    $sum = 0; // Initialize sum variable
                    $count = 0; // Initialize count variable
                    foreach ($value3 as $key3 => $value4) {
                        $listBlok = array();
                        $firstEntry = $value4[0];
                        foreach ($value4 as $key4 => $value5) {
                            // dd($value5['sph']);
                            if (!in_array($value5['estate'] . ' ' . $value5['afdeling'] . ' ' . $value5['blok'], $listBlok)) {
                                if ($value5['sph'] != 0) {
                                    $listBlok[] = $value5['estate'] . ' ' . $value5['afdeling'] . ' ' . $value5['blok'];
                                }
                            }
                            $jml_blok = count($listBlok);

                            if ($firstEntry['luas_blok'] != 0) {
                                $first = $firstEntry['luas_blok'];
                            } else {
                                $first = '-';
                            }
                        }
                        if ($first != '-') {
                            $sum += $first;
                            $count++;
                        }
                        $ancakRegss2[$key][$key1][$key2][$key3]['luas_blok'] = $first;
                        if ($regional === '2') {
                            $status_panen = explode(",", $value5['status_panen']);
                            $ancakRegss2[$key][$key1][$key2][$key3]['status_panen'] = $status_panen[0];
                        } else {
                            $ancakRegss2[$key][$key1][$key2][$key3]['status_panen'] = $value5['status_panen'];
                        }
                    }
                }
            }
        }
        $transNewdata = array();
        foreach ($dataMTTransRegs2 as $key => $value) {
            foreach ($value as $key1 => $value1) {

                foreach ($value1 as $key2 => $value2) {

                    foreach ($value2 as $key3 => $value3) {
                        $sum_bt = 0;
                        $sum_Restan = 0;
                        $tph_sample = 0;
                        $listBlokPerAfd = array();
                        foreach ($value3 as $key4 => $value4) {
                            $listBlokPerAfd[] = $value4['estate'] . ' ' . $value4['afdeling'] . ' ' . $value4['blok'];
                            $sum_Restan += $value4['rst'];
                            $tph_sample = count($listBlokPerAfd);
                            $sum_bt += $value4['bt'];
                        }
                        $panenKey = 0;
                        $LuasKey = 0;
                        if (isset($ancakRegss2[$key][$key1][$key2][$key3]['status_panen'])) {
                            $transNewdata[$key][$key1][$key2][$key3]['status_panen'] = $ancakRegss2[$key][$key1][$key2][$key3]['status_panen'];
                            $panenKey = $ancakRegss2[$key][$key1][$key2][$key3]['status_panen'];
                        }
                        if (isset($ancakRegss2[$key][$key1][$key2][$key3]['luas_blok'])) {
                            $transNewdata[$key][$key1][$key2][$key3]['luas_blok'] = $ancakRegss2[$key][$key1][$key2][$key3]['luas_blok'];
                            $LuasKey = $ancakRegss2[$key][$key1][$key2][$key3]['luas_blok'];
                        }


                        if ($panenKey !== 0 && $panenKey <= 3) {
                            if (count($value4) == 1 && $value4[0]['blok'] == '0') {
                                $tph_sample = $value4[0]['tph_baris'];
                                $sum_bt = $value4[0]['bt'];
                            } else {
                                $transNewdata[$key][$key1][$key2][$key3]['tph_sample'] = round(floatval($LuasKey) * 1.3, 3);
                            }
                        } else {
                            $transNewdata[$key][$key1][$key2][$key3]['tph_sample'] = $tph_sample;
                        }



                        $transNewdata[$key][$key1][$key2][$key3]['estate'] = $value4['estate'];
                        $transNewdata[$key][$key1][$key2][$key3]['afdeling'] = $value4['afdeling'];
                        $transNewdata[$key][$key1][$key2][$key3]['estate'] = $value4['estate'];
                    }
                }
            }
        }
        foreach ($ancakRegss2 as $key => $value) {
            foreach ($value as $key1 => $value1) {

                foreach ($value1 as $key2 => $value2) {
                    $tph_tod = 0;
                    foreach ($value2 as $key3 => $value3) {
                        if (!isset($transNewdata[$key][$key1][$key2][$key3])) {
                            $transNewdata[$key][$key1][$key2][$key3] = $value3;

                            if ($value3['status_panen'] <= 3) {
                                $transNewdata[$key][$key1][$key2][$key3]['tph_sample'] = round(floatval($value3['luas_blok']) * 1.3, 3);
                            } else {
                                $transNewdata[$key][$key1][$key2][$key3]['tph_sample'] = 0;
                            }
                        }
                        // If 'tph_sample' key exists, add its value to $tph_tod
                        if (isset($value3['tph_sample'])) {
                            $tph_tod += $value3['tph_sample'];
                        }
                    }
                }
                // Store total_tph for each $key1 after iterating all $key2

            }
        }
        foreach ($transNewdata as $key => &$value) {
            foreach ($value as $key1 => &$value1) {
                $tph_sample_total = 0; // initialize the total
                foreach ($value1 as $key2 => $value2) {
                    if (is_array($value2)) {
                        foreach ($value2 as $key3 => $value3) {
                            if (isset($value3['tph_sample'])) {
                                $tph_sample_total += $value3['tph_sample'];
                            }
                        }
                    }
                }
                $value1['total_tph'] = $tph_sample_total;
            }
        }
        unset($value); // unset the reference
        unset($value1); // unset the reference
        // dd($transNewdata);

        $defaultMtTrans = array();
        foreach ($queryEste as $est) {
            // dd($est);
            foreach ($queryAfd as $afd) {
                // dd($afd);
                if ($est['est'] == $afd['est']) {
                    if ($est['est'] === 'LDE' || $est['est'] === 'SRE') {
                        $defaultMtTrans[$est['est']][$afd['est']]['null'] = 0;
                    } else {
                        $defaultMtTrans[$est['est']][$afd['nama']]['null'] = 0;
                    }
                }
                // if ($est['est'] == $afd['est']) {
                //     $defaultMtTrans[$est['est']][$afd['nama']]['null'] = 0;
                // }
            }
        }
        $QueryTransWil = DB::connection('mysql2')->table('mutu_transport')
            ->select(
                "mutu_transport.*",
                DB::raw('DATE_FORMAT(mutu_transport.datetime, "%M") as bulan'),
                DB::raw('DATE_FORMAT(mutu_transport.datetime, "%Y") as tahun')
            )
            ->where('datetime', 'like', '%' . $bulan . '%')
            // ->whereYear('datetime', $year)
            ->get();
        $QueryTransWil = $QueryTransWil->groupBy(['estate', 'afdeling']);
        $QueryTransWil = json_decode($QueryTransWil, true);


        // dd($QueryTransWil);
        $dataMTTrans = array();
        foreach ($QueryTransWil as $key => $value) {
            foreach ($value as $key2 => $value2) {
                foreach ($value2 as $key3 => $value3) {
                    $dataMTTrans[$key][$key2][$key3] = $value3;
                }
            }
        }
        $mutuAncakMerge = array();
        foreach ($defaultMtTrans as $estKey => $afdArray) {
            foreach ($afdArray as $afdKey => $afdValue) {
                if (array_key_exists($estKey, $dataMTTrans)) {
                    if (array_key_exists($afdKey, $dataMTTrans[$estKey])) {
                        if (!empty($dataMTTrans[$estKey][$afdKey])) {
                            $mutuAncakMerge[$estKey][$afdKey] = $dataMTTrans[$estKey][$afdKey];
                        } else {
                            $mutuAncakMerge[$estKey][$afdKey] = $afdValue;
                        }
                    } else {
                        $mutuAncakMerge[$estKey][$afdKey] = $afdValue;
                    }
                } else {
                    $mutuAncakMerge[$estKey][$afdKey] = $afdValue;
                }
            }
        }
        $mtTransWiltab1 = array();
        foreach ($queryEste as $key => $value) {
            foreach ($mutuAncakMerge as $key2 => $value2) {
                if ($value['est'] == $key2) {
                    $mtTransWiltab1[$value['wil']][$key2] = array_merge($mtTransWiltab1[$value['wil']][$key2] ?? [], $value2);
                }
            }
        }

        // dd($mtTranstab1Wilmua, $sidak_buah_mua);



        $mtTranstab1Wil = array();
        foreach ($mtTransWiltab1 as $key => $value) if (!empty($value)) {
            $dataBLokWil = 0;
            $sum_btWil = 0;
            $sum_rstWil = 0;
            foreach ($value as $key1 => $value1) if (!empty($value1)) {
                $dataBLokEst = 0;
                $sum_btEst = 0;
                $sum_rstEst = 0;
                foreach ($value1 as $key2 => $value2) if (!empty($value2)) {
                    $sum_bt = 0;
                    $sum_rst = 0;
                    $brdPertph = 0;
                    $buahPerTPH = 0;
                    $totalSkor = 0;
                    $dataBLok = 0;
                    $listBlokPerAfd = array();
                    foreach ($value2 as $key3 => $value3) if (is_array($value3)) {

                        // if (!in_array($value3['estate'] . ' ' . $value3['afdeling'] . ' ' . $value3['blok'] , $listBlokPerAfd)) {
                        $listBlokPerAfd[] = $value3['estate'] . ' ' . $value3['afdeling'] . ' ' . $value3['blok'];
                        // }
                        $dataBLok = count($listBlokPerAfd);
                        $sum_bt += $value3['bt'];
                        $sum_rst += $value3['rst'];
                    }
                    $tot_sample = 0;  // Define the variable outside of the foreach loop

                    foreach ($transNewdata as $keys => $trans) {
                        if ($keys == $key1) {
                            foreach ($trans as $keys2 => $trans2) {
                                if ($keys2 == $key2) {
                                    // $rekap[$key][$key1][$key2]['tph_sampleNew'] = $trans2['total_tph'];
                                    $tot_sample = $trans2['total_tph'];
                                }
                            }
                        }
                    }

                    if ($regional == '2' || $regional == 2) {
                        if ($dataBLok != 0) {
                            $brdPertph = round($sum_bt / $tot_sample, 3);
                        } else {
                            $brdPertph = 0;
                        }
                    } else {
                        if ($dataBLok != 0) {
                            $brdPertph = round($sum_bt / $dataBLok, 3);
                        } else {
                            $brdPertph = 0;
                        }
                    }

                    if ($regional == '2' || $regional == 2) {
                        if ($dataBLok != 0) {
                            $buahPerTPH = round($sum_rst / $tot_sample, 3);
                        } else {
                            $buahPerTPH = 0;
                        }
                    } else {
                        if ($dataBLok != 0) {
                            $buahPerTPH = round($sum_rst / $dataBLok, 3);
                        } else {
                            $buahPerTPH = 0;
                        }
                    }


                    $nonZeroValues = array_filter([$sum_bt, $sum_rst]);

                    if (!empty($nonZeroValues)) {
                        $rekap[$key][$key1][$key2]['check_datatrans'] = 'ada';
                    } else {
                        $rekap[$key][$key1][$key2]['check_datatrans'] = "kosong";
                    }
                    // dd($transNewdata);




                    $totalSkor =   skor_brd_tinggal($brdPertph) + skor_buah_tinggal($buahPerTPH);

                    if ($regional == '2' || $regional == 2) {
                        $rekap[$key][$key1][$key2]['tph_sampleNew'] = $tot_sample;
                    } else {
                        $rekap[$key][$key1][$key2]['tph_sampleNew'] = $dataBLok;
                    }

                    $rekap[$key][$key1][$key2]['total_brdtrans'] = $sum_bt;
                    $rekap[$key][$key1][$key2]['total_brdperTPHtrans'] = $brdPertph;
                    $rekap[$key][$key1][$key2]['total_buahtrans'] = $sum_rst;
                    $rekap[$key][$key1][$key2]['total_buahPerTPHtrans'] = $buahPerTPH;
                    $rekap[$key][$key1][$key2]['skor_brdPertphtrans'] = skor_brd_tinggal($brdPertph);
                    $rekap[$key][$key1][$key2]['skor_buahPerTPHtrans'] = skor_buah_tinggal($buahPerTPH);
                    $rekap[$key][$key1][$key2]['totalSkortrans'] = $totalSkor;
                    $rekap[$key][$key1][$key2]['mututrans'] = '-----------------------------------';

                    //PERHITUNGAN PERESTATE
                    if ($regional == '2' || $regional == 2) {
                        $dataBLokEst += $tot_sample;
                    } else {
                        $dataBLokEst += $dataBLok;
                    }

                    $sum_btEst += $sum_bt;
                    $sum_rstEst += $sum_rst;

                    if ($dataBLokEst != 0) {
                        $brdPertphEst = round($sum_btEst / $dataBLokEst, 3);
                    } else {
                        $brdPertphEst = 0;
                    }

                    if ($dataBLokEst != 0) {
                        $buahPerTPHEst = round($sum_rstEst / $dataBLokEst, 3);
                    } else {
                        $buahPerTPHEst = 0;
                    }

                    // dd($rekap);
                    $totalSkorEst = skor_brd_tinggal($brdPertphEst) + skor_buah_tinggal($buahPerTPHEst);
                } else {
                    $rekap[$key][$key1][$key2]['check_datatrans'] = 'kosong';
                    $rekap[$key][$key1][$key2]['tph_sampleNew'] = 0;
                    $rekap[$key][$key1][$key2]['tph_sampletrans'] = 0;
                    $rekap[$key][$key1][$key2]['total_brdtrans'] = 0;
                    $rekap[$key][$key1][$key2]['total_brdperTPHtrans'] = 0;
                    $rekap[$key][$key1][$key2]['total_buahtrans'] = 0;
                    $rekap[$key][$key1][$key2]['total_buahPerTPHtrans'] = 0;
                    $rekap[$key][$key1][$key2]['skor_brdPertphtrans'] = 0;
                    $rekap[$key][$key1][$key2]['skor_buahPerTPHtrans'] = 0;
                    $rekap[$key][$key1][$key2]['totalSkortrans'] = 0;
                    $rekap[$key][$key1][$key2]['mututrans'] = '-----------------------------------';
                }

                $nonZeroValues = array_filter([$sum_btEst, $sum_rstEst]);

                if (!empty($nonZeroValues)) {
                    $check_data = 'ada';
                    // $rekap[$key][$key1]['skor_buahPerTPH'] = $skor_buah =  skor_buah_tinggal($buahPerTPHEst);
                } else {
                    $check_data = 'kosong';
                    // $rekap[$key][$key1]['skor_buahPerTPH'] = $skor_buah = 0;
                }

                // $totalSkorEst = $skor_brd + $skor_buah ;


                $rekap[$key][$key1]['est']['esttrans'] = [
                    'tph_sampleNew' => $dataBLokEst,
                    'total_brdtrans' => $sum_btEst,
                    'check_datatrans' => $check_data,
                    'total_brdperTPHtrans' => $brdPertphEst,
                    'total_buahtrans' => $sum_rstEst,
                    'total_buahPerTPHtrans' => $buahPerTPHEst,
                    'skor_brdPertphtrans' => skor_brd_tinggal($brdPertphEst),
                    'skor_buahPerTPHtrans' => skor_buah_tinggal($buahPerTPHEst),
                    'totalSkortrans' => $totalSkorEst,
                    'mututrans' => '-----------------------------------'
                ];


                //perhitungan per wil
                $dataBLokWil += $dataBLokEst;
                $sum_btWil += $sum_btEst;
                $sum_rstWil += $sum_rstEst;

                if ($dataBLokWil != 0) {
                    $brdPertphWil = round($sum_btWil / $dataBLokWil, 3);
                } else {
                    $brdPertphWil = 0;
                }
                if ($dataBLokWil != 0) {
                    $buahPerTPHWil = round($sum_rstWil / $dataBLokWil, 3);
                } else {
                    $buahPerTPHWil = 0;
                }

                $totalSkorWil =   skor_brd_tinggal($brdPertphWil) + skor_buah_tinggal($buahPerTPHWil);
            } else {
                $rekap[$key][$key1]['est']['esttrans'] = [
                    'tph_sampleNew' => 0,
                    'total_brdtrans' => 0,
                    'check_datatrans' => 'kosong',
                    'total_brdperTPHtrans' => 0,
                    'total_buahtrans' => 0,
                    'total_buahPerTPHtrans' => 0,
                    'skor_brdPertphtrans' => 0,
                    'skor_buahPerTPHtrans' => 0,
                    'totalSkortrans' => 0,
                    'mututrans' => '-----------------------------------'
                ];
            }

            // dd($rekap);

            $nonZeroValues = array_filter([$sum_btWil, $sum_rstWil]);


            if (!empty($nonZeroValues)) {
                $check_data = 'ada';
                // $rekap[$key]['skor_brd'] = $skor_brd = skor_brd_ma($brdPerwil);
                // $rekap[$key]['skor_ps'] = $skor_ps = skor_palepah_ma($perPiWil);
            } else {
                $check_data = 'kosong';
                // $rekap[$key]['skor_brd'] = $skor_brd = 0;
                // $rekap[$key]['skor_ps'] = $skor_ps = 0;
            }
            $rekap[$key]['wil']['wiltrans'] = [
                'check_datatrans' => $check_data,
                'tph_sampleNew' => $dataBLokWil,
                'total_brdtrans' => $sum_btWil,
                'total_brdperTPHtrans' => $brdPertphWil,
                'total_buahtrans' => $sum_rstWil,
                'total_buahPerTPHtrans' => $buahPerTPHWil,
                'skor_brdPertphtrans' =>   skor_brd_tinggal($brdPertphWil),
                'skor_buahPerTPHtrans' => skor_buah_tinggal($buahPerTPHWil),
                'totalSkortrans' => $totalSkorWil,
                'mututrans' => '-----------------------------------'
            ];
        } else {
            $rekap[$key]['wil']['wiltrans'] = [
                'check_datatrans' => 'kosong',
                'tph_sampleNew' => 0,
                'total_brdtrans' => 0,
                'total_brdperTPHtrans' => 0,
                'total_buahtrans' => 0,
                'total_buahPerTPHtrans' => 0,
                'skor_brdPertphtrans' => 0,
                'skor_buahPerTPHtrans' => 0,
                'totalSkortrans' => 0,
                'mututrans' => '-----------------------------------'
            ];
        }

        foreach ($rekap as $key => $value) {
            foreach ($value as $key1 => $value1) {
                if (isset($value1["est"])) {
                    // Get the "est" array
                    $estArray = $value1["est"];

                    // Merge all arrays within "est"
                    $mergedEst = [];
                    foreach ($estArray as $subEst) {
                        $mergedEst = array_merge($mergedEst, $subEst);
                    }

                    // Unset the "est" key
                    unset($rekap[$key][$key1]["est"]);

                    // Replace the "est" key with the merged array
                    $rekap[$key][$key1]["estate"] = $mergedEst;
                }
            }
            if (isset($value["wil"])) {
                // Get the "est" array
                $estArray = $value["wil"];

                // Merge all arrays within "est"
                $mergedEst = [];
                foreach ($estArray as $subEst) {
                    $mergedEst = array_merge($mergedEst, $subEst);
                }

                // Unset the "est" key
                unset($rekap[$key]["wil"]);

                // Replace the "est" key with the merged array
                $rekap[$key]["wilayah"]['wil'] = $mergedEst;
            }
        }

        if ($regional == 1) {
            $muaarray = [
                'SRE' => $rekap[3]['SRE']['estate'] ?? [],
                'LDE' => $rekap[3]['LDE']['estate'] ?? [],
            ];


            $ha_samplecak = 0;
            $jumlah_panencak = 0;
            $pcak = 0;
            $kcak = 0;
            $tglcak = 0;
            $bhts_scak = 0;
            $bhtm1cak = 0;
            $bhtm2cak = 0;
            $bhtm3cak = 0;
            $palepah_pokokcak = 0;
            $pokok_samplecak = 0;
            $tph_sampleNew = 0;
            $total_brdtrans = 0;
            $total_buahtrans = 0;

            $tph_baris_bloksbh = 0;
            $sampleJJG_totalbh = 0;
            $total_mentahbh = 0;
            $total_overbh = 0;
            $total_abnormalbh = 0;
            $total_jjgKosongbh = 0;
            $total_vcutbh = 0;
            $jum_krbh = 0;
            foreach ($muaarray as $key => $value) {

                // ancak 
                $pokok_samplecak += $value['pokok_samplecak'];
                $ha_samplecak += $value['ha_samplecak'];
                $jumlah_panencak += $value['jumlah_panencak'];
                $pcak += $value['pcak'];
                $kcak += $value['kcak'];
                $tglcak += $value['tglcak'];
                $bhts_scak += $value['bhts_scak'];
                $bhtm1cak += $value['bhtm1cak'];
                $bhtm2cak += $value['bhtm2cak'];
                $bhtm3cak += $value['bhtm3cak'];
                $palepah_pokokcak += $value['palepah_pokokcak'];

                $tph_sampleNew += $value['tph_sampleNew'];
                $total_brdtrans += $value['total_brdtrans'];
                $total_buahtrans += $value['total_buahtrans'];

                $tph_baris_bloksbh += $value['tph_baris_bloksbh'];
                $sampleJJG_totalbh += $value['sampleJJG_totalbh'];
                $total_mentahbh += $value['total_mentahbh'];
                $total_overbh += $value['total_overbh'];
                $total_abnormalbh += $value['total_abnormalbh'];
                $total_jjgKosongbh += $value['total_jjgKosongbh'];
                $total_vcutbh += $value['total_vcutbh'];
                $jum_krbh += $value['jum_krbh'];
            }

            if ($ha_samplecak != 0) {
                $akp = round(($jumlah_panencak / $pokok_samplecak) * 100, 1);
                $datacak = 'ada';
            } else {
                $akp = 0;
                $datacak = 'kosong';
            }
            $skor_bTinggal = $pcak + $kcak + $tglcak;

            if ($jumlah_panencak != 0) {
                $brdPerjjg = round($skor_bTinggal / $jumlah_panencak, 3);
            } else {
                $brdPerjjg = 0;
            }
            $sumBH = $bhts_scak +  $bhtm1cak +  $bhtm2cak +  $bhtm3cak;
            if ($sumBH != 0) {
                $sumPerBH = round($sumBH / ($jumlah_panencak + $sumBH) * 100, 3);
            } else {
                $sumPerBH = 0;
            }
            if ($palepah_pokokcak != 0) {
                $perPl = $palepah_pokokcak / $pokok_samplecak * 100;
            } else {
                $perPl = 0;
            }

            if ($tph_sampleNew != 0) {
                $brdPertph = round($total_brdtrans / $tph_sampleNew, 3);
            } else {
                $brdPertph = 0;
            }
            if ($tph_sampleNew != 0) {
                $buahPerTPH = round($total_buahtrans / $tph_sampleNew, 3);
            } else {
                $buahPerTPH = 0;
            }


            $dataBLok = $tph_baris_bloksbh;
            $jml_mth = $total_mentahbh;
            $jml_mtg = $sampleJJG_totalbh - ($total_mentahbh + $total_overbh + $total_jjgKosongbh + $total_abnormalbh);

            if ($jum_krbh != 0) {
                $total_kr = round($jum_krbh / $dataBLok, 3);
            } else {
                $total_kr = 0;
            }


            $per_kr = round($total_kr * 100, 3);
            if ($jml_mth != 0) {
                $PerMth = round(($jml_mth / ($sampleJJG_totalbh - $total_abnormalbh)) * 100, 3);
            } else {
                $PerMth = 0;
            }
            if ($jml_mtg != 0) {
                $PerMsk = round(($jml_mtg / ($sampleJJG_totalbh - $total_abnormalbh)) * 100, 3);
            } else {
                $PerMsk = 0;
            }
            if ($total_overbh != 0) {
                $PerOver = round(($total_overbh / ($sampleJJG_totalbh - $total_abnormalbh)) * 100, 3);
            } else {
                $PerOver = 0;
            }
            if ($total_jjgKosongbh != 0) {
                $Perkosongjjg = round(($total_jjgKosongbh / ($sampleJJG_totalbh - $total_abnormalbh)) * 100, 3);
            } else {
                $Perkosongjjg = 0;
            }
            if ($total_vcutbh != 0) {
                $PerVcut = round(($total_vcutbh / $sampleJJG_totalbh) * 100, 3);
            } else {
                $PerVcut = 0;
            }

            if ($total_abnormalbh != 0) {
                $PerAbr = round(($total_abnormalbh / $sampleJJG_totalbh) * 100, 3);
            } else {
                $PerAbr = 0;
            }

            $totalSkorEsttrans = skor_brd_tinggal($brdPertph) + skor_buah_tinggal($buahPerTPH);
            $totalSkorEstancak =  skor_palepah_ma($perPl) + skor_buah_Ma($sumPerBH) + skor_brd_ma($brdPerjjg);
            $totalSkorBuah =  skor_buah_mentah_mb($PerMth) + skor_buah_masak_mb($PerMsk) + skor_buah_over_mb($PerOver) + skor_vcut_mb($PerVcut) + skor_jangkos_mb($Perkosongjjg) + skor_abr_mb($per_kr);

            $resultmua['pokok_samplecak'] = $pokok_samplecak;
            $resultmua['ha_samplecak'] = $ha_samplecak;
            $resultmua['jumlah_panencak'] = $jumlah_panencak;
            $resultmua['akp_rlcak'] = $akp;
            $resultmua['pcak'] = $pcak;
            $resultmua['kcak'] = $kcak;
            $resultmua['tglcak'] = $tglcak;
            $resultmua['total_brdcak'] = $skor_bTinggal;
            $resultmua['brd/jjgcak'] = $brdPerjjg;
            $resultmua['skor_brdcak'] = skor_brd_ma($brdPerjjg);
            $resultmua['bhts_scak'] = $bhts_scak;
            $resultmua['bhtm1cak'] = $bhtm1cak;
            $resultmua['bhtm2cak'] = $bhtm2cak;
            $resultmua['bhtm3cak'] = $bhtm3cak;
            $resultmua['buah/jjgcak'] = $sumPerBH;
            $resultmua['skor_bhcak'] = skor_buah_Ma($sumPerBH);
            $resultmua['palepah_pokokcak'] = $palepah_pokokcak;
            $resultmua['palepah_percak'] = $perPl;
            $resultmua['skor_pscak'] = skor_palepah_ma($perPl);
            $resultmua['skor_akhircak'] = $totalSkorEstancak;
            $resultmua['check_datacak'] = $datacak;
            $resultmua['est'] = 'PT.MUA';
            $resultmua['afd'] = 'est';
            $resultmua['mutuancak'] = '------------------------------------------------------';
            $resultmua['tph_sampleNew'] = $tph_sampleNew;
            $resultmua['total_brdtrans'] = $total_brdtrans;
            $resultmua['total_buahtrans'] = $total_buahtrans;
            $resultmua['total_brdperTPHtrans'] = $brdPertph;
            $resultmua['total_buahPerTPHtrans'] = $buahPerTPH;
            $resultmua['skor_brdPertphtrans'] = skor_brd_tinggal($brdPertph);
            $resultmua['skor_buahPerTPHtrans'] = skor_buah_tinggal($buahPerTPH);
            $resultmua['totalSkortrans'] = $totalSkorEsttrans;
            $resultmua['check_datatrans'] = 'ada';
            $resultmua['mututrans'] = '------------------------------------------------------';
            $resultmua['tph_baris_bloksbh'] = $tph_baris_bloksbh;
            $resultmua['sampleJJG_totalbh'] = $sampleJJG_totalbh;
            $resultmua['total_mentahbh'] = $total_mentahbh;
            $resultmua['total_perMentahbh'] = $PerMth;
            $resultmua['total_masakbh'] = $jml_mtg;
            $resultmua['total_perMasakbh'] = $PerMsk;
            $resultmua['total_overbh'] = $total_overbh;
            $resultmua['total_perOverbh'] = $PerOver;
            $resultmua['total_abnormalbh'] = $sum_abnor;
            $resultmua['perAbnormalbh'] = $PerAbr;
            $resultmua['total_jjgKosongbh'] = $sum_kosongjjg;
            $resultmua['total_perKosongjjgbh'] = $Perkosongjjg;
            $resultmua['total_vcutbh'] = $total_vcutbh;
            $resultmua['perVcutbh'] = $PerVcut;
            $resultmua['total_krbh'] = $jum_krbh;
            $resultmua['jum_krbh'] = $total_kr;
            $resultmua['persen_krbh'] = $per_kr;
            $resultmua['skor_mentahbh'] = skor_buah_mentah_mb($PerMth);
            $resultmua['skor_masakbh'] = skor_buah_masak_mb($PerMsk);
            $resultmua['skor_overbh'] = skor_buah_over_mb($PerOver);
            $resultmua['skor_jjgKosongbh'] = skor_jangkos_mb($Perkosongjjg);
            $resultmua['skor_vcutbh'] = skor_vcut_mb($PerVcut);
            $resultmua['skor_krbh'] = skor_abr_mb($per_kr);
            $resultmua['total_buahcak'] = $totalSkorBuah;
            $resultmua['TOTAL_SKORbh'] = $totalSkorBuah;
            $resultmua['check_databh'] = 'ada';

            // dd($resultmua);


            $rekap[3]['PT.MUA']['estate'] = $resultmua;

            foreach ($rekap as $key => $value) {
                if ($key == 3) {
                    // Save the "wilayah" value if needed
                    $wilayahValue = $value["wilayah"];

                    // Unset the "wilayah" key
                    unset($rekap[$key]["wilayah"]);

                    // Assign "wilayah" at the end of the array
                    $rekap[$key]["wilayah"] = $wilayahValue;
                }
            }

            // dd($rekap);
        } else {
            $resultmua = [];
        }


        $getwilx = [];

        foreach ($rekap as $key => $value) {
            if (isset($value['wilayah'])) {
                $getwilx[$key] = $value['wilayah']['wil'];
            }
        }

        // dd($rekap[3]);
        $dataReg = array();

        $ha_samplecak = 0;
        $jumlah_panencak = 0;
        $pcak = 0;
        $kcak = 0;
        $tglcak = 0;
        $bhts_scak = 0;
        $bhtm1cak = 0;
        $bhtm2cak = 0;
        $bhtm3cak = 0;
        $palepah_pokokcak = 0;
        $pokok_samplecak = 0;
        $tph_sampleNew = 0;
        $total_brdtrans = 0;
        $total_buahtrans = 0;

        $tph_baris_bloksbh = 0;
        $sampleJJG_totalbh = 0;
        $total_mentahbh = 0;
        $total_overbh = 0;
        $total_abnormalbh = 0;
        $total_jjgKosongbh = 0;
        $total_vcutbh = 0;
        $jum_krbh = 0;
        foreach ($getwilx as $keyx => $value) {
            $pokok_samplecak += $value['pokok_samplecak'];
            $ha_samplecak += $value['ha_samplecak'];
            $jumlah_panencak += $value['jumlah_panencak'];
            $pcak += $value['pcak'];
            $kcak += $value['kcak'];
            $tglcak += $value['tglcak'];
            $bhts_scak += $value['bhts_scak'];
            $bhtm1cak += $value['bhtm1cak'];
            $bhtm2cak += $value['bhtm2cak'];
            $bhtm3cak += $value['bhtm3cak'];
            $palepah_pokokcak += $value['palepah_pokokcak'];

            $tph_sampleNew += $value['tph_sampleNew'];
            $total_brdtrans += $value['total_brdtrans'];
            $total_buahtrans += $value['total_buahtrans'];

            $tph_baris_bloksbh += $value['tph_baris_bloksbh'];
            $sampleJJG_totalbh += $value['sampleJJG_totalbh'];
            $total_mentahbh += $value['total_mentahbh'];
            $total_overbh += $value['total_overbh'];
            $total_abnormalbh += $value['total_abnormalbh'];
            $total_jjgKosongbh += $value['total_jjgKosongbh'];
            $total_vcutbh += $value['total_vcutbh'];
            $jum_krbh += $value['jum_krbh'];
        }
        if ($ha_samplecak != 0) {
            $akp = round(($jumlah_panencak / $pokok_samplecak) * 100, 1);
            $datacak = 'ada';
        } else {
            $akp = 0;
            $datacak = 'kosong';
        }
        $skor_bTinggal = $pcak + $kcak + $tglcak;

        if ($jumlah_panencak != 0) {
            $brdPerjjg = round($skor_bTinggal / $jumlah_panencak, 3);
        } else {
            $brdPerjjg = 0;
        }
        $sumBH = $bhts_scak +  $bhtm1cak +  $bhtm2cak +  $bhtm3cak;
        if ($sumBH != 0) {
            $sumPerBH = round($sumBH / ($jumlah_panencak + $sumBH) * 100, 3);
        } else {
            $sumPerBH = 0;
        }
        if ($palepah_pokokcak != 0) {
            $perPl = ($palepah_pokokcak / $pokok_samplecak) * 100;
        } else {
            $perPl = 0;
        }

        if ($tph_sampleNew != 0) {
            $brdPertph = round($total_brdtrans / $tph_sampleNew, 3);
        } else {
            $brdPertph = 0;
        }
        if ($tph_sampleNew != 0) {
            $buahPerTPH = round($total_buahtrans / $tph_sampleNew, 3);
        } else {
            $buahPerTPH = 0;
        }


        $dataBLok = $tph_baris_bloksbh;
        $jml_mth = $total_mentahbh;
        $jml_mtg = $sampleJJG_totalbh - ($total_mentahbh + $total_overbh + $total_jjgKosongbh + $total_abnormalbh);

        if ($jum_krbh != 0) {
            $total_kr = round($jum_krbh / $dataBLok, 3);
        } else {
            $total_kr = 0;
        }


        $per_kr = round($total_kr * 100, 3);
        if ($jml_mth != 0) {
            $PerMth = round(($jml_mth / ($sampleJJG_totalbh - $total_abnormalbh)) * 100, 3);
        } else {
            $PerMth = 0;
        }
        if ($jml_mtg != 0) {
            $PerMsk = round(($jml_mtg / ($sampleJJG_totalbh - $total_abnormalbh)) * 100, 3);
        } else {
            $PerMsk = 0;
        }
        if ($total_overbh != 0) {
            $PerOver = round(($total_overbh / ($sampleJJG_totalbh - $total_abnormalbh)) * 100, 3);
        } else {
            $PerOver = 0;
        }
        if ($total_jjgKosongbh != 0) {
            $Perkosongjjg = round(($total_jjgKosongbh / ($sampleJJG_totalbh - $total_abnormalbh)) * 100, 3);
        } else {
            $Perkosongjjg = 0;
        }
        if ($total_vcutbh != 0) {
            $PerVcut = round(($total_vcutbh / $sampleJJG_totalbh) * 100, 3);
        } else {
            $PerVcut = 0;
        }

        if ($total_abnormalbh != 0) {
            $PerAbr = round(($total_abnormalbh / $sampleJJG_totalbh) * 100, 3);
        } else {
            $PerAbr = 0;
        }

        $totalSkorEsttrans = skor_brd_tinggal($brdPertph) + skor_buah_tinggal($buahPerTPH);
        $totalSkorEstancak =  skor_palepah_ma($perPl) + skor_buah_Ma($sumPerBH) + skor_brd_ma($brdPerjjg);
        $totalSkorBuah =  skor_buah_mentah_mb($PerMth) + skor_buah_masak_mb($PerMsk) + skor_buah_over_mb($PerOver) + skor_vcut_mb($PerVcut) + skor_jangkos_mb($Perkosongjjg) + skor_abr_mb($per_kr);
        $namaGM = '-';
        $namewil = 'REG-' . convertToRoman($regional);
        foreach ($queryAsisten as $asisten) {

            // dd($asisten);
            if ($asisten['est'] == $namewil && $asisten['afd'] == 'RH') {
                $namaGM = $asisten['nama'];
                break;
            }
        }
        $dataReg['pokok_samplecak'] = $pokok_samplecak;
        $dataReg['namaGM'] = $namaGM;
        $dataReg['namewil'] = $namewil;
        $dataReg['ha_samplecak'] = $ha_samplecak;
        $dataReg['jumlah_panencak'] = $jumlah_panencak;
        $dataReg['akp_rlcak'] = $akp;
        $dataReg['pcak'] = $pcak;
        $dataReg['kcak'] = $kcak;
        $dataReg['tglcak'] = $tglcak;
        $dataReg['total_brdcak'] = $skor_bTinggal;
        $dataReg['brd/jjgcak'] = $brdPerjjg;
        $dataReg['skor_brdcak'] = skor_brd_ma($brdPerjjg);
        $dataReg['bhts_scak'] = $bhts_scak;
        $dataReg['bhtm1cak'] = $bhtm1cak;
        $dataReg['bhtm2cak'] = $bhtm2cak;
        $dataReg['bhtm3cak'] = $bhtm3cak;
        $dataReg['buah/jjgcak'] = $sumPerBH;
        $dataReg['skor_bhcak'] = skor_buah_Ma($sumPerBH);
        $dataReg['palepah_pokokcak'] = $palepah_pokokcak;
        $dataReg['palepah_percak'] = $perPl;
        $dataReg['skor_pscak'] = skor_palepah_ma($perPl);
        $dataReg['skor_akhircak'] = $totalSkorEstancak;
        $dataReg['check_datacak'] = $datacak;
        $dataReg['est'] = 'Regional';
        $dataReg['afd'] = $regional;
        $dataReg['mutuancak'] = '------------------------------------------------------';
        $dataReg['tph_sampleNew'] = $tph_sampleNew;
        $dataReg['total_brdtrans'] = $total_brdtrans;
        $dataReg['total_buahtrans'] = $total_buahtrans;
        $dataReg['total_brdperTPHtrans'] = $brdPertph;
        $dataReg['total_buahPerTPHtrans'] = $buahPerTPH;
        $dataReg['skor_brdPertphtrans'] = skor_brd_tinggal($brdPertph);
        $dataReg['skor_buahPerTPHtrans'] = skor_buah_tinggal($buahPerTPH);
        $dataReg['totalSkortrans'] = $totalSkorEsttrans;
        $dataReg['check_datatrans'] = 'ada';
        $dataReg['mututrans'] = '------------------------------------------------------';
        $dataReg['tph_baris_bloksbh'] = $tph_baris_bloksbh;
        $dataReg['sampleJJG_totalbh'] = $sampleJJG_totalbh;
        $dataReg['total_mentahbh'] = $total_mentahbh;
        $dataReg['total_perMentahbh'] = $PerMth;
        $dataReg['total_masakbh'] = $jml_mtg;
        $dataReg['total_perMasakbh'] = $PerMsk;
        $dataReg['total_overbh'] = $total_overbh;
        $dataReg['total_perOverbh'] = $PerOver;
        $dataReg['total_abnormalbh'] = $sum_abnor;
        $dataReg['perAbnormalbh'] = $PerAbr;
        $dataReg['total_jjgKosongbh'] = $sum_kosongjjg;
        $dataReg['total_perKosongjjgbh'] = $Perkosongjjg;
        $dataReg['total_vcutbh'] = $total_vcutbh;
        $dataReg['perVcutbh'] = $PerVcut;
        $dataReg['total_krbh'] = $jum_krbh;
        $dataReg['jum_krbh'] = $total_kr;
        $dataReg['persen_krbh'] = $per_kr;
        $dataReg['skor_mentahbh'] = skor_buah_mentah_mb($PerMth);
        $dataReg['skor_masakbh'] = skor_buah_masak_mb($PerMsk);
        $dataReg['skor_overbh'] = skor_buah_over_mb($PerOver);
        $dataReg['skor_jjgKosongbh'] = skor_jangkos_mb($Perkosongjjg);
        $dataReg['skor_vcutbh'] = skor_vcut_mb($PerVcut);
        $dataReg['skor_krbh'] = skor_abr_mb($per_kr);
        $dataReg['total_buahcak'] = $totalSkorBuah;
        $dataReg['TOTAL_SKORbh'] = $totalSkorBuah;
        $dataReg['check_databh'] = 'ada';

        // dd($rekap);

        $forafdeling = $rekap;
        $dataafdeling = array();

        foreach ($forafdeling as $key => $value) {
            // Create a copy of the current element


            // Unset the "wilayah" key from the copy
            unset($value["wilayah"]);

            // Iterate over the inner arrays to unset the "estate" key if it exists
            foreach ($value as $subKey => $subValue) {
                if (is_array($subValue) && isset($subValue['estate'])) {
                    unset($value[$subKey]['estate']);
                }
            }

            // Add the modified copy to $dataafdeling
            $dataafdeling[] = $value;
        }

        $forestate = $rekap;

        // Create a new array to store the modified copy
        $dataestate = [];

        foreach ($forestate as $key => $value) {
            $dataestate[$key] = [];
            foreach ($value as $subKey => $subValue) {
                // Check if the "estate" key exists before accessing it
                if ($subKey !== 'wilayah' && isset($subValue["estate"])) {
                    $dataestate[$key][$subKey] = ["estate" => $subValue["estate"]];
                }
            }
        }


        foreach ($dataestate as $key => $value) {
            if ($key == 3) {
                unset($dataestate[$key]["SRE"]);
                unset($dataestate[$key]["LDE"]);
            }
        }


        $forwil = $rekap;

        $datawil = [];

        foreach ($forwil as $key => $value) {
            if (isset($value['wilayah'])) {
                $datawil[$key] = $value['wilayah']['wil'];
            }
        }
        // dd($dataafdeling[2]['LDE']);
        $tabelafdeling = array();
        foreach ($dataafdeling as $key => $value) {
            foreach ($value as $key1 => $value1) {
                foreach ($value1 as $key2 => $value2) if (is_array($value2)) {
                    // dd($value1);
                    if ($value2['check_datacak'] != 'kosong') {
                        $skor_akhircak = $value2['skor_akhircak'];
                    } else {
                        $skor_akhircak = 0;
                    }
                    if ($value2['check_databh'] != 'kosong') {
                        $TOTAL_SKORbh = $value2['TOTAL_SKORbh'];
                    } else {
                        $TOTAL_SKORbh = 0;
                    }
                    if ($value2['tph_sampleNew'] != 'kosong') {
                        $totalSkortrans = $value2['totalSkortrans'];
                    } else {
                        $totalSkortrans = 0;
                    }

                    if ($value2['check_datacak'] != 'kosong' || $value2['check_databh'] != 'kosong' || $value2['check_datatrans'] != 'kosong') {
                        $tabelafdeling[$key][$key1][$key2]['data'] = 'ada';
                    } else {
                        $tabelafdeling[$key][$key1][$key2]['data'] = 'kosong';
                    }

                    $tabelafdeling[$key][$key1][$key2]['total_skor'] = $skor_akhircak + $TOTAL_SKORbh + $totalSkortrans;
                    $tabelafdeling[$key][$key1][$key2]['est'] = $value2['est'];
                    $tabelafdeling[$key][$key1][$key2]['afd'] = $value2['afd'];
                    $tabelafdeling[$key][$key1][$key2]['nama'] = $value2['namaGM'];
                }
            }
        }
        // dd($tabelafdeling);
        $tableestate = array();
        foreach ($dataestate as $key => $value) {
            foreach ($value as $key1 => $value1) {
                foreach ($value1 as $key2 => $value2['estate']) {
                    // dd($key1);
                    if ($value2['estate']['check_datacak'] != 'kosong') {
                        $skor_akhircak = $value2['estate']['skor_akhircak'];
                    } else {
                        $skor_akhircak = 0;
                    }
                    if ($value2['estate']['check_databh'] != 'kosong') {
                        $TOTAL_SKORbh = $value2['estate']['TOTAL_SKORbh'];
                    } else {
                        $TOTAL_SKORbh = 0;
                    }
                    if ($value2['estate']['tph_sampleNew'] != 0) {
                        $totalSkortrans = $value2['estate']['totalSkortrans'];
                    } else {
                        $totalSkortrans = 0;
                    }

                    if ($value2['estate']['check_datacak'] != 'kosong' || $value2['estate']['check_databh'] != 'kosong' || $value2['estate']['check_datatrans'] != 'kosong') {
                        $tableestate[$key][$key1]['data'] = 'ada';
                    } else {
                        $tableestate[$key][$key1]['data'] = 'kosong';
                    }

                    if ($key1 === 'PT.MUA') {
                        foreach ($queryAsisten as $keycs => $casx) {
                            if ($casx['est'] === 'PT.MUA' && $casx['afd'] === 'EM') {
                                $namagm = $casx['nama'];
                            }
                        }
                    } else {
                        $namagm = $value2['estate']['namaGM'];
                    }
                    // $skor_akhircak = $value2['estate']['skor_akhircak'];
                    // $TOTAL_SKORbh = $value2['estate']['TOTAL_SKORbh'];
                    // $totalSkortrans = $value2['estate']['totalSkortrans'];
                    $tableestate[$key][$key1]['total_skor'] = $skor_akhircak + $TOTAL_SKORbh + $totalSkortrans;
                    $tableestate[$key][$key1]['total_skor_string'] = $skor_akhircak . '+' . $TOTAL_SKORbh . '+' . $totalSkortrans;
                    $tableestate[$key][$key1]['est'] = $value2['estate']['est'];
                    $tableestate[$key][$key1]['afd'] = $value2['estate']['afd'];
                    $tableestate[$key][$key1]['nama'] = $namagm;
                }
            }
        }
        // dd($tableestate);
        $tablewil = array();
        foreach ($datawil as $key => $value) {
            $skor_akhircak = $value['skor_akhircak'];
            $TOTAL_SKORbh = $value['TOTAL_SKORbh'];
            $totalSkortrans = $value['totalSkortrans'];
            $tablewil[$key]['total_skor'] = $skor_akhircak + $TOTAL_SKORbh + $totalSkortrans;
            $tablewil[$key]['est'] = $value['est'];
            $tablewil[$key]['afd'] = $value['afd'];
            $tablewil[$key]['nama'] = $value['namaGM'] ?? '-';
        }
        // dd($dataestate);
        // grafik 
        $getnameest = [];
        $cakbrd = [];
        $cakbuah = [];
        $brdtrans = [];
        $buahtrans = [];
        $mentahbuah = [];
        $masakbuah = [];
        $overbuah = [];
        $abrbuah = [];
        $emptybuah = [];
        $vcutbuah = [];
        foreach ($dataestate as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $getnameest[] = $key1;
                $cakbrd[] = $value1['estate']['brd/jjgcak'];
                $cakbuah[] = $value1['estate']['buah/jjgcak'];
                $brdtrans[] = $value1['estate']['total_brdperTPHtrans'];
                $buahtrans[] = $value1['estate']['total_buahPerTPHtrans'];
                $mentahbuah[] = $value1['estate']['total_perMentahbh'];
                $masakbuah[] = $value1['estate']['total_perMasakbh'];
                $overbuah[] = $value1['estate']['total_perOverbh'];
                $abrbuah[] = $value1['estate']['perAbnormalbh'];
                $emptybuah[] = $value1['estate']['total_perKosongjjgbh'];
                $vcutbuah[] = $value1['estate']['perVcutbh'];
            }
        }
        foreach ($datawil as $key => $value) {
            $getnamewil[] = 'WIL-' . convertToRoman($key);
            $cakbrdwiil[] = $value['brd/jjgcak'];
            $cakbuahwiil[] = $value['buah/jjgcak'];
            $brdtranswiil[] = $value['total_brdperTPHtrans'];
            $buahtranswiil[] = $value['total_buahPerTPHtrans'];
            $mentahbuahwiil[] = $value['total_perMentahbh'];
            $masakbuahwiil[] = $value['total_perMasakbh'];
            $overbuahwiil[] = $value['total_perOverbh'];
            $abrbuahwiil[] = $value['perAbnormalbh'];
            $emptybuahwiil[] = $value['total_perKosongjjgbh'];
            $vcutbuahwiil[] = $value['perVcutbh'];
        }
        // dd($getnamewil, $vcutbuahwiil, $emptybuahwiil);
        $arrView = array();
        // dd($tableestate);
        $arrView['tab_afdeling'] = $tabelafdeling;
        $arrView['tab_estate'] = $tableestate;
        $arrView['tab_wil'] = $tablewil;
        $arrView['dataReg'] = $dataReg;
        // chart est
        $arrView['getnameest'] = $getnameest;
        $arrView['cakbrd'] = $cakbrd;
        $arrView['cakbuah'] = $cakbuah;
        $arrView['brdtrans'] = $brdtrans;
        $arrView['buahtrans'] = $buahtrans;
        $arrView['mentahbuah'] = $mentahbuah;
        $arrView['masakbuah'] = $masakbuah;
        $arrView['overbuah'] = $overbuah;
        $arrView['abrbuah'] = $abrbuah;
        $arrView['emptybuah'] = $emptybuah;
        $arrView['vcutbuah'] = $vcutbuah;
        // chart Wil
        $arrView['getnamewil'] = $getnamewil;
        $arrView['cakbrdwil'] = $cakbrdwiil;
        $arrView['cakbuahwil'] = $cakbuahwiil;
        $arrView['brdtranswil'] = $brdtranswiil;
        $arrView['buahtranswil'] = $buahtranswiil;
        $arrView['mentahbuahwil'] = $mentahbuahwiil;
        $arrView['masakbuahwil'] = $masakbuahwiil;
        $arrView['overbuahwil'] = $overbuahwiil;
        $arrView['abrbuahwil'] = $abrbuahwiil;
        $arrView['emptybuahwil'] = $emptybuahwiil;
        $arrView['vcutbuahwil'] = $vcutbuahwiil;
        echo json_encode($arrView); //di decode ke dalam bentuk json dalam vaiavel arrview yang dapat menampung banyak isi array
        exit();
    }

    public function filterTahun(Request $request)
    {
        // dd('test');
        $year = $request->input('year');
        $RegData = $request->input('regData');

        $result = rekap_pertahun($year, $RegData);


        // dd($result);

        // dd($transNewdata[2]['January'][4]);

        // dd($defaultAncak, $defaultbuah, $defaulttrans);
        // dd($rekap[1]['July'][2]['BKE']);
        $arrView = array();

        $arrView['resultreg'] =  $result['resultreg'];
        $arrView['resultwil'] =  $result['resultwil'];
        $arrView['resultestate'] =  $result['resultestate'];
        $arrView['resultafdeling'] =  $result['resultafdeling'];


        echo json_encode($arrView); //di decode ke dalam bentuk json dalam vaiavel arrview yang dapat menampung banyak isi array
        exit();
    }

    public function graphfilter(Request $request)
    {
        $estData = $request->input('est');
        $yearGraph = $request->input('yearGraph');
        $reg = $request->input('reg');
        $wilayahGrafik = $request->input('wilayahGrafik');
        // dd($estData, $yearGraph,$reg);

        // dd($wilayahGrafik);
        $queryEste = DB::connection('mysql2')->table('estate')
            ->select('estate.*')
            ->whereNotIn('estate.est', ['Plasma1', 'Plasma2', 'Plasma3'])
            ->where('estate.emp', '!=', 1)
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->where('wil.regional', $reg)
            ->get();
        $queryEste = json_decode($queryEste, true);
        $querySidak = DB::connection('mysql2')->table('mutu_transport')
            ->select("mutu_transport.*")
            // ->where('datetime', 'like', '%' . $getDate . '%')
            // ->where('datetime', 'like', '%' . '2023-01' . '%')
            ->get();
        $DataEstate = $querySidak->groupBy(['estate', 'afdeling']);
        // dd($DataEstate);
        $DataEstate = json_decode($DataEstate, true);

        //menghitung buat table tampilkan pertahun

        //bagian querry
        //mutu ancak
        // $querytahun = DB::connection('mysql2')->table('mutu_ancak_new')
        //     ->select("mutu_ancak_new.*", DB::raw('DATE_FORMAT(mutu_ancak_new.datetime, "%M") as bulan'), DB::raw('DATE_FORMAT(mutu_ancak_new.datetime, "%Y") as tahun'))
        //     ->whereYear('datetime', $yearGraph)
        //     // ->where('estate', 'KNE')
        //     ->orderBy('datetime', 'DESC')
        //     ->orderBy(DB::raw('SECOND(datetime)'), 'DESC')
        //     ->get();

        // $querytahun = $querytahun->groupBy(['estate', 'afdeling']);
        // $querytahun = json_decode($querytahun, true);


        $querytahun = [];

        for ($month = 1; $month <= 12; $month++) {
            $monthName = date('Y-m', mktime(0, 0, 0, $month, 1));

            $data = DB::connection('mysql2')->table('mutu_ancak_new')
                ->select("mutu_ancak_new.*", 'estate.*', DB::raw('DATE_FORMAT(mutu_ancak_new.datetime, "%M") as bulan'), DB::raw('DATE_FORMAT(mutu_ancak_new.datetime, "%Y") as tahun'))
                ->join('estate', 'estate.est', '=', 'mutu_ancak_new.estate')
                ->join('wil', 'wil.id', '=', 'estate.wil')
                ->where('datetime', 'like', '%' . $monthName . '%')
                ->where('wil.regional', $reg)
                ->orderBy('estate', 'asc')
                ->orderBy('afdeling', 'asc')
                ->orderBy('blok', 'asc')
                ->orderBy('datetime', 'asc')
                ->get();

            $data = $data->groupBy(['estate', 'afdeling']);
            $data = json_decode($data, true);

            foreach ($data as $key1 => $value) {
                foreach ($value as $key2 => $value2) {
                    if (!isset($querytahun[$key1][$key2])) {
                        $querytahun[$key1][$key2] = [];
                    }

                    if (!empty($value2)) {
                        $querytahun[$key1][$key2] = array_merge($querytahun[$key1][$key2], $value2);
                    }
                }
            }
        }

        $queryMTbuah = [];

        for ($month = 1; $month <= 12; $month++) {
            $monthName = date('Y-m', mktime(0, 0, 0, $month, 1));

            $data = DB::connection('mysql2')->table('mutu_buah')
                ->select("mutu_buah.*", 'estate.*', DB::raw('DATE_FORMAT(mutu_buah.datetime, "%M") as bulan'), DB::raw('DATE_FORMAT(mutu_buah.datetime, "%Y") as tahun'))
                ->join('estate', 'estate.est', '=', 'mutu_buah.estate')
                ->join('wil', 'wil.id', '=', 'estate.wil')
                ->where('datetime', 'like', '%' . $monthName . '%')
                ->where('wil.regional', $reg)
                ->orderBy('estate', 'asc')
                ->orderBy('afdeling', 'asc')
                ->orderBy('blok', 'asc')
                ->orderBy('datetime', 'asc')
                ->get();

            $data = $data->groupBy(['estate', 'afdeling']);
            $data = json_decode($data, true);

            foreach ($data as $key1 => $value) {
                foreach ($value as $key2 => $value2) {
                    if (!isset($queryMTbuah[$key1][$key2])) {
                        $queryMTbuah[$key1][$key2] = [];
                    }

                    if (!empty($value2)) {
                        $queryMTbuah[$key1][$key2] = array_merge($queryMTbuah[$key1][$key2], $value2);
                    }
                }
            }
        }


        $queryMTtrans = [];

        for ($month = 1; $month <= 12; $month++) {
            $monthName = date('Y-m', mktime(0, 0, 0, $month, 1));

            $data = DB::connection('mysql2')->table('mutu_transport')
                ->select("mutu_transport.*", 'estate.*', DB::raw('DATE_FORMAT(mutu_transport.datetime, "%M") as bulan'), DB::raw('DATE_FORMAT(mutu_transport.datetime, "%Y") as tahun'))
                ->join('estate', 'estate.est', '=', 'mutu_transport.estate')
                ->join('wil', 'wil.id', '=', 'estate.wil')
                ->where('datetime', 'like', '%' . $monthName . '%')
                ->where('wil.regional', $reg)
                ->orderBy('estate', 'asc')
                ->orderBy('afdeling', 'asc')
                ->orderBy('blok', 'asc')
                ->orderBy('datetime', 'asc')
                ->get();

            $data = $data->groupBy(['estate', 'afdeling']);
            $data = json_decode($data, true);

            foreach ($data as $key1 => $value) {
                foreach ($value as $key2 => $value2) {
                    if (!isset($queryMTtrans[$key1][$key2])) {
                        $queryMTtrans[$key1][$key2] = [];
                    }

                    if (!empty($value2)) {
                        $queryMTtrans[$key1][$key2] = array_merge($queryMTtrans[$key1][$key2], $value2);
                    }
                }
            }
        }
        // dd($queryMTancak);

        //afdeling
        $queryAfd = DB::connection('mysql2')->table('afdeling')
            ->select(
                'afdeling.id',
                'afdeling.nama',
                'estate.est'
            ) //buat mengambil data di estate db dan willayah db
            ->join('estate', 'estate.id', '=', 'afdeling.estate') //kemudian di join untuk mengambil est perwilayah
            ->get();
        $queryAfd = json_decode($queryAfd, true);
        //estate
        // $queryEste = DB::connection('mysql2')->table('estate')->whereIn('wil', [1, 2, 3])->get();
        // $queryEste = json_decode($queryEste, true);

        // dd($queryEste);
        //end query
        $queryEste = DB::connection('mysql2')->table('estate')
            ->select('estate.*')
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->whereNotIn('estate.est', ['CWS1', 'CWS2', 'CWS3'])
            ->where('estate.emp', '!=', 1)
            ->where('wil.regional', $reg)
            ->get();


        $queryEste = json_decode($queryEste, true);

        $bulan = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        //mutu ancak membuat nilai berdasrakan bulan
        $dataPerBulan = array();
        foreach ($querytahun as $key => $value) {
            foreach ($value as $key2 => $value2) {
                foreach ($value2 as $key3 => $value3) {
                    $month = date('F', strtotime($value3['datetime']));
                    if (!array_key_exists($month, $dataPerBulan)) {
                        $dataPerBulan[$month] = array();
                    }
                    if (!array_key_exists($key, $dataPerBulan[$month])) {
                        $dataPerBulan[$month][$key] = array();
                    }
                    if (!array_key_exists($key2, $dataPerBulan[$month][$key])) {
                        $dataPerBulan[$month][$key][$key2] = array();
                    }
                    $dataPerBulan[$month][$key][$key2][$key3] = $value3;
                }
            }
        }
        //mutu buah  membuat nilai berdasrakan bulan
        $dataPerBulanMTbh = array();
        foreach ($queryMTbuah as $key => $value) {
            foreach ($value as $key2 => $value2) {
                foreach ($value2 as $key3 => $value3) {
                    $month = date('F', strtotime($value3['datetime']));
                    if (!array_key_exists($month, $dataPerBulanMTbh)) {
                        $dataPerBulanMTbh[$month] = array();
                    }
                    if (!array_key_exists($key, $dataPerBulanMTbh[$month])) {
                        $dataPerBulanMTbh[$month][$key] = array();
                    }
                    if (!array_key_exists($key2, $dataPerBulanMTbh[$month][$key])) {
                        $dataPerBulanMTbh[$month][$key][$key2] = array();
                    }
                    $dataPerBulanMTbh[$month][$key][$key2][$key3] = $value3;
                }
            }
        }

        // dd($dataPerBulanMTbh);
        //mutu transport memnuat nilai perbulan
        $dataBulananMTtrans = array();
        foreach ($queryMTtrans as $key => $value) {
            foreach ($value as $key2 => $value2) {
                foreach ($value2 as $key3 => $value3) {
                    $month = date('F', strtotime($value3['datetime']));
                    if (!array_key_exists($month, $dataBulananMTtrans)) {
                        $dataBulananMTtrans[$month] = array();
                    }
                    if (!array_key_exists($key, $dataBulananMTtrans[$month])) {
                        $dataBulananMTtrans[$month][$key] = array();
                    }
                    if (!array_key_exists($key2, $dataBulananMTtrans[$month][$key])) {
                        $dataBulananMTtrans[$month][$key][$key2] = array();
                    }
                    $dataBulananMTtrans[$month][$key][$key2][$key3] = $value3;
                }
            }
        }
        // dd($dataBulananMTtrans);

        //membuat nilai default 0 ke masing masing est-afdeling untuk di timpa nanti
        //membuat array estate -> bulan -> afdeling
        // mutu ancak
        $defaultNew = array();
        foreach ($bulan as $month) {
            foreach ($queryEste as $est) {
                foreach ($queryAfd as $afd) {
                    if ($est['est'] == $afd['est']) {
                        $defaultNew[$est['est']][$month][$afd['nama']] = 0;
                    }
                }
            }
        }


        // dd($defaultTabAFD);
        //mutu buah
        $defaultMTbh = array();
        foreach ($bulan as $month) {
            foreach ($queryEste as $est) {
                foreach ($queryAfd as $afd) {
                    if ($est['est'] == $afd['est']) {
                        $defaultMTbh[$est['est']][$month][$afd['nama']] = 0;
                    }
                }
            }
        }
        //mutu transport
        $defaultTrans = array();
        foreach ($bulan as $month) {
            foreach ($queryEste as $est) {
                foreach ($queryAfd as $afd) {
                    if ($est['est'] == $afd['est']) {
                        $defaultTrans[$est['est']][$month][$afd['nama']] = 0;
                    }
                }
            }
        }


        //membuat nilai default untuk table terakhir tahunan EST > AFD

        // dd($defaultMTbh);
        //end  nilai defalt
        //bagian menimpa nilai dengan menggunakan defaultNEw
        //menimpa nilai default dengan value mutu ancak yang ada isinya sehingga yang tidak ada value menjadi 0
        foreach ($defaultNew as $key => $estValue) {
            foreach ($estValue as $monthKey => $monthValue) {
                foreach ($dataPerBulan as $dataKey => $dataValue) {
                    if ($dataKey == $monthKey) {
                        foreach ($dataValue as $dataEstKey => $dataEstValue) {
                            if ($dataEstKey == $key) {
                                $defaultNew[$key][$monthKey] = array_merge($monthValue, $dataEstValue);
                            }
                        }
                    }
                }
            }
        }



        // dd($defaultTabAFD);
        // menimpa nilai defaultnew dengan value mutu buah yang ada isi nya
        // dd($defaultMTbh, $dataPerBulanMTbh);
        foreach ($defaultMTbh as $key => $estValue) {
            foreach ($estValue as $monthKey => $monthValue) {
                foreach ($dataPerBulanMTbh as $dataKey => $dataValue) {
                    if ($dataKey == $monthKey) {
                        foreach ($dataValue as $dataEstKey => $dataEstValue) {
                            if ($dataEstKey == $key) {
                                $defaultMTbh[$key][$monthKey] = array_merge($monthValue, $dataEstValue);
                            }
                        }
                    }
                }
            }
        }
        // dd($defaultMTbh);
        //menimpa nilai default mutu transport dengan yang memiliki value
        foreach ($defaultTrans as $key => $estValue) {
            foreach ($estValue as $monthKey => $monthValue) {
                foreach ($dataBulananMTtrans as $dataKey => $dataValue) {
                    if ($dataKey == $monthKey) {
                        foreach ($dataValue as $dataEstKey => $dataEstValue) {
                            if ($dataEstKey == $key) {
                                $defaultTrans[$key][$monthKey] = array_merge($monthValue, $dataEstValue);
                            }
                        }
                    }
                }
            }
        }

        // buat perhitungan regional 2... group berdasrakan blok
        // $newArrayANcak = [];
        // foreach ($defaultNew as $key1 => $value1) {
        //     $newArrayANcak[$key1] = [];
        //     foreach ($value1 as $key2 => $value2) {
        //         $newArrayANcak[$key1][$key2] = [];
        //         foreach ($value2 as $key3 => $value3) {
        //             if (is_array($value3)) {
        //                 foreach ($value3 as $item) {
        //                     $nestedKey = $item['blok'];
        //                     if (!isset($newArrayANcak[$key1][$key2][$key3][$nestedKey])) {
        //                         $newArrayANcak[$key1][$key2][$key3][$nestedKey] = [];
        //                     }
        //                     $newArrayANcak[$key1][$key2][$key3][$nestedKey][] = $item;
        //                 }
        //             } else {
        //                 $newArrayANcak[$key1][$key2][$key3] = $value3;
        //             }
        //         }
        //     }
        // }

        // $newArrayTrans = [];
        // foreach ($defaultTrans as $key1 => $value1) {
        //     $newArrayTrans[$key1] = [];
        //     foreach ($value1 as $key2 => $value2) {
        //         $newArrayTrans[$key1][$key2] = [];
        //         foreach ($value2 as $key3 => $value3) {
        //             if (is_array($value3)) {
        //                 foreach ($value3 as $item) {
        //                     $nestedKey = $item['blok'];
        //                     if (!isset($newArrayTrans[$key1][$key2][$key3][$nestedKey])) {
        //                         $newArrayTrans[$key1][$key2][$key3][$nestedKey] = [];
        //                     }
        //                     $newArrayTrans[$key1][$key2][$key3][$nestedKey][] = $item;
        //                 }
        //             } else {
        //                 $newArrayTrans[$key1][$key2][$key3] = $value3;
        //             }
        //         }
        //     }
        // }

        // $mutuTrans = array_replace_recursive($newArrayTrans, $newArrayANcak);

        $newArrayANcak = [];
        foreach ($defaultNew as $key1 => $value1) {
            $newArrayANcak[$key1] = [];
            foreach ($value1 as $key2 => $value2) {
                $newArrayANcak[$key1][$key2] = [];
                foreach ($value2 as $key3 => $value3) {
                    if (is_array($value3)) {
                        foreach ($value3 as $item) {
                            // Change the key "status_panen" to "status_panenMA"
                            $item['status_panenMA'] = $item['status_panen'];
                            unset($item['status_panen']);

                            $item['luas_blokMa'] = $item['luas_blok'];
                            unset($item['luas_blok']);

                            $nestedDate = date('Y-m-d', strtotime($item['datetime'])); // Format datetime as Y-m-d
                            $nestedBlok = $item['blok']; // Group by "blok"

                            if (!isset($newArrayANcak[$key1][$key2][$key3][$nestedDate])) {
                                $newArrayANcak[$key1][$key2][$key3][$nestedDate] = [];
                            }
                            if (!isset($newArrayANcak[$key1][$key2][$key3][$nestedDate][$nestedBlok])) {
                                $newArrayANcak[$key1][$key2][$key3][$nestedDate][$nestedBlok] = [];
                            }
                            $newArrayANcak[$key1][$key2][$key3][$nestedDate][$nestedBlok][] = $item;
                        }
                    } else {
                        $newArrayANcak[$key1][$key2][$key3] = $value3;
                    }
                }
            }
        }



        // dd($newArrayANcak['MRE']['June']);

        $newArrayTrans = [];
        foreach ($defaultTrans as $key1 => $value1) {
            $newArrayTrans[$key1] = [];
            foreach ($value1 as $key2 => $value2) {
                $newArrayTrans[$key1][$key2] = [];
                foreach ($value2 as $key3 => $value3) {
                    if (is_array($value3)) {
                        foreach ($value3 as $item) {
                            // Change the key "status_panen" to "status_panenMA"
                            $item['status_panenTran'] = $item['status_panen'];
                            unset($item['status_panen']);

                            $item['luas_blokTrans'] = $item['luas_blok'];
                            unset($item['luas_blok']);

                            $nestedDate = date('Y-m-d', strtotime($item['datetime'])); // Format datetime as Y-m-d
                            $nestedBlok = $item['blok']; // Group by "blok"

                            if (!isset($newArrayTrans[$key1][$key2][$key3][$nestedDate])) {
                                $newArrayTrans[$key1][$key2][$key3][$nestedDate] = [];
                            }
                            if (!isset($newArrayTrans[$key1][$key2][$key3][$nestedDate][$nestedBlok])) {
                                $newArrayTrans[$key1][$key2][$key3][$nestedDate][$nestedBlok] = [];
                            }
                            $newArrayTrans[$key1][$key2][$key3][$nestedDate][$nestedBlok][] = $item;
                        }
                    } else {
                        $newArrayTrans[$key1][$key2][$key3] = $value3;
                    }
                }
            }
        }

        // dd($newArrayTrans['MRE']['June']['OC'], $newArrayANcak['MRE']['June']['OC']);
        $mutuTrans = array_replace_recursive($newArrayTrans, $newArrayANcak);


        if ($reg == 2) {
            $newTransv2 = array();
            foreach ($mutuTrans as $key => $value) {
                foreach ($value as $key1 => $value1) if (!empty($value)) {
                    $reg_blok = 0;
                    if (is_array($value1)) {
                        foreach ($value1 as $key2 => $value2) {
                            $wil_blok = 0;
                            if (is_array($value2)) {

                                foreach ($value2 as $key3 => $value3) {

                                    if (is_array($value3)) {

                                        $est_blok = 0; // Moved outside the innermost loop
                                        $largestLuasBlokMa = 0;
                                        foreach ($value3 as $key4 => $value4) {
                                            if (is_array($value4)) {
                                                $tot_blok = count($value4);
                                                foreach ($value4 as $key5 => $value5) {
                                                    $status_panen = $value5['status_panenMA'] ?? 'kosong';
                                                    $luas_blok = $value5['luas_blokMa'] ?? 0;

                                                    // if ($luas_blok > $largestLuasBlokMa) {
                                                    //     $largestLuasBlokMa = $luas_blok; // Update the largest luas_blokMa value
                                                    // }

                                                    if ($status_panen <= 3 && $status_panen != 'kosong') {
                                                        $new_blok = round($luas_blok * 1.3, 2);
                                                    } else {
                                                        $new_blok = $tot_blok;
                                                    }
                                                    $newTransv2[$key][$key1][$key2][$key3][$key4]['luas_blok'] = $luas_blok;
                                                    $newTransv2[$key][$key1][$key2][$key3][$key4]['status_panen'] = $status_panen;
                                                    $newTransv2[$key][$key1][$key2][$key3][$key4]['tph_sampleNew'] = $new_blok;
                                                }
                                                $est_blok += $new_blok;
                                            }
                                        }
                                        $newTransv2[$key][$key1][$key2][$key3]['tph_sampleEst'] = $est_blok;
                                        $wil_blok += $est_blok;
                                    }
                                }
                            }
                            $newTransv2[$key][$key1][$key2]['tph_sampleWil'] = $wil_blok;
                            $reg_blok += $wil_blok;
                        }
                    }
                    $newTransv2[$key][$key1]['tph_sampleReg'] = $reg_blok;
                } else {
                    $newTransv2[$key][$key1]['tph_sampleReg'] = 0;
                }
            }
        }

        // dd($newTransv2['MRE']['June']);


        // dd($newTransv2['MRE']['June'],$mutuTrans['MRE']['June']);
        // dd($newTransTPh['MRE']['June']['OC'],$mutuTrans['MRE']['June']['OC']);
        // $tph_est = array();

        // foreach ($newTransTPh as $key => $value) {
        //     foreach ($value as $key1 => $value2) {
        //             $sum_tphest = 0;
        //         foreach ($value2 as $key2 => $value3) {
        //         //    dd($value3);
        //                 $sum_tphest += $value3['tph_sampNEW'];
        //         }# code...
        //         $tph_est[$key][$key1]['tph_est'] = $sum_tphest;
        //     }# code...
        // }



        // dd($newTransTPh,$tph_est);

        // dd($newArrayTrans['MRE']['June'],$newArrayANcak['MRE']['June'],$newTransTPh['MRE']['June'],$mutuTrans['MRE']['June']);



        // endperhitungan

        // dd($defaultMTbh);
        $bulananBh = array();
        foreach ($defaultMTbh as $key => $value) {
            foreach ($value as $key1 => $value1) if (!empty($value1)) {
                $tph_blok = 0;
                $jjgMth = 0;
                $sampleJJG = 0;
                $jjgAbn = 0;
                $PerMth = 0;
                $PerMsk = 0;
                $PerOver = 0;
                $Perkosongjjg = 0;
                $PerVcut = 0;
                $PerAbr = 0;
                $per_kr = 0;
                $jjgMsk = 0;
                $jjgOver = 0;
                $jjgKosng = 0;
                $vcut = 0;
                $jum_kr = 0;
                $total_kr = 0;
                $totalSkor = 0;
                $no_Vcut = 0;
                foreach ($value1 as $key2 => $value2)
                    if (is_array($value2)) {
                        $sum_bmt = 0;
                        $sum_bmk = 0;
                        $sum_over = 0;
                        $sum_Samplejjg = 0;
                        $PerMth = 0;
                        $PerMsk = 0;
                        $PerOver = 0;
                        $sum_abnor = 0;
                        $sum_kosongjjg = 0;
                        $Perkosongjjg = 0;
                        $sum_vcut = 0;
                        $PerVcut = 0;
                        $PerAbr = 0;
                        $sum_kr = 0;
                        $total_kr = 0;
                        $per_kr = 0;
                        $totalSkor = 0;
                        $no_Vcut = 0;
                        $jml_mth = 0;
                        $jml_mtg = 0;
                        $combination_counts = array();
                        foreach ($value2 as $key3 => $value3) {
                            $combination = $value3['blok'] . ' ' . $value3['estate'] . ' ' . $value3['afdeling'] . ' ' . $value3['tph_baris'];
                            if (!isset($combination_counts[$combination])) {
                                $combination_counts[$combination] = 0;
                            }
                            $combination_counts[$combination]++;
                            $sum_bmt += $value3['bmt'];
                            $sum_bmk += $value3['bmk'];
                            $sum_over += $value3['overripe'];
                            $sum_kosongjjg += $value3['empty_bunch'];
                            $sum_vcut += $value3['vcut'];
                            $sum_kr += $value3['alas_br'];


                            $sum_Samplejjg += $value3['jumlah_jjg'];
                            $sum_abnor += $value3['abnormal'];
                            // dd($sum_bmk);
                        }

                        $jml_mth = ($sum_bmt + $sum_bmk);
                        $jml_mtg = $sum_Samplejjg - ($jml_mth + $sum_over + $sum_kosongjjg + $sum_abnor);
                        // dd($sum_vcut);
                        $dataBLok = count($combination_counts);
                        if ($sum_kr != 0) {
                            $total_kr = round($sum_kr / $dataBLok, 2);
                        } else {
                            $total_kr = 0;
                        }

                        $per_kr = round($total_kr * 100, 2);
                        $denom1 = ($sum_Samplejjg - $sum_abnor) != 0 ? ($sum_Samplejjg - $sum_abnor) : 1;
                        $denom2 = $sum_Samplejjg != 0 ? $sum_Samplejjg : 1;

                        $PerMth = $denom1 != 0 ? round(($jml_mth / $denom1) * 100, 2) : 0;
                        $PerMsk = $denom1 != 0 ? round(($jml_mtg / $denom1) * 100, 2) : 0;
                        $PerOver = $denom1 != 0 ? round(($sum_over / $denom1) * 100, 2) : 0;
                        $Perkosongjjg = $denom1 != 0 ? round(($sum_kosongjjg / $denom1) * 100, 2) : 0;
                        $PerVcut = $denom2 != 0 ? round(($sum_vcut / $denom2) * 100, 2) : 0;
                        $PerAbr = $denom2 != 0 ? round(($sum_abnor / $denom2) * 100, 2) : 0;

                        // $PerMth = round(($jml_mth / ($sum_Samplejjg - $sum_abnor)) * 100, 2);
                        // $PerMsk = round(($jml_mtg / ($sum_Samplejjg - $sum_abnor)) * 100, 2);
                        // $PerOver = round(($sum_over / ($sum_Samplejjg - $sum_abnor)) * 100, 2);
                        // $Perkosongjjg = round(($sum_kosongjjg / ($sum_Samplejjg - $sum_abnor)) * 100, 2);
                        // $PerVcut = round(($sum_vcut / $sum_Samplejjg) * 100, 2);
                        // $PerAbr = round(($sum_abnor / $sum_Samplejjg) * 100, 2);

                        $totalSkor =  skor_buah_mentah_mb($PerMth) + skor_buah_masak_mb($PerMsk) + skor_buah_over_mb($PerOver) + skor_vcut_mb($PerVcut) + skor_jangkos_mb($Perkosongjjg) + skor_abr_mb($per_kr);

                        $bulananBh[$key][$key1][$key2]['tph_baris_blok'] = $dataBLok;
                        $bulananBh[$key][$key1][$key2]['sampleJJG_total'] = $sum_Samplejjg;
                        $bulananBh[$key][$key1][$key2]['total_mentah'] = $jml_mth;
                        $bulananBh[$key][$key1][$key2]['total_perMentah'] = $PerMth;
                        $bulananBh[$key][$key1][$key2]['total_masak'] = $jml_mtg;
                        $bulananBh[$key][$key1][$key2]['total_perMasak'] = $PerMsk;
                        $bulananBh[$key][$key1][$key2]['total_over'] = $sum_over;
                        $bulananBh[$key][$key1][$key2]['total_perOver'] = $PerOver;
                        $bulananBh[$key][$key1][$key2]['total_abnormal'] = $sum_abnor;
                        $bulananBh[$key][$key1][$key2]['total_jjgKosong'] = $sum_kosongjjg;
                        $bulananBh[$key][$key1][$key2]['total_perKosongjjg'] = $Perkosongjjg;
                        $bulananBh[$key][$key1][$key2]['total_vcut'] = $sum_vcut;

                        $bulananBh[$key][$key1][$key2]['jum_kr'] = $sum_kr;
                        $bulananBh[$key][$key1][$key2]['total_kr'] = $total_kr;
                        $bulananBh[$key][$key1][$key2]['persen_kr'] = $per_kr;

                        // skoring
                        $bulananBh[$key][$key1][$key2]['skor_mentah'] = skor_buah_mentah_mb($PerMth);
                        $bulananBh[$key][$key1][$key2]['skor_masak'] = skor_buah_masak_mb($PerMsk);
                        $bulananBh[$key][$key1][$key2]['skor_over'] = skor_buah_over_mb($PerOver);
                        $bulananBh[$key][$key1][$key2]['skor_jjgKosong'] = skor_jangkos_mb($Perkosongjjg);
                        $bulananBh[$key][$key1][$key2]['skor_vcut'] = skor_vcut_mb($PerVcut);
                        $bulananBh[$key][$key1][$key2]['skor_kr'] = skor_abr_mb($per_kr);
                        $bulananBh[$key][$key1][$key2]['TOTAL_SKOR'] = $totalSkor;

                        //perhitungan estate
                        $tph_blok += $dataBLok;
                        $sampleJJG += $sum_Samplejjg;
                        $jjgMth += $jml_mth;

                        $jjgOver += $sum_over;
                        $jjgKosng += $sum_kosongjjg;
                        $vcut += $sum_vcut;
                        $jum_kr +=  $sum_kr;

                        $jjgAbn +=  $sum_abnor;

                        $jjgMsk +=  $jml_mtg;
                    } else {

                        $bulananBh[$key][$key1][$key2]['tph_baris_blok'] = 0;
                        $bulananBh[$key][$key1][$key2]['sampleJJG_total'] = 0;
                        $bulananBh[$key][$key1][$key2]['total_mentah'] = 0;
                        $bulananBh[$key][$key1][$key2]['total_perMentah'] = 0;
                        $bulananBh[$key][$key1][$key2]['total_masak'] = 0;
                        $bulananBh[$key][$key1][$key2]['total_perMasak'] = 0;
                        $bulananBh[$key][$key1][$key2]['total_over'] = 0;
                        $bulananBh[$key][$key1][$key2]['total_perOver'] = 0;
                        $bulananBh[$key][$key1][$key2]['total_abnormal'] = 0;
                        $bulananBh[$key][$key1][$key2]['total_jjgKosong'] = 0;
                        $bulananBh[$key][$key1][$key2]['total_perKosongjjg'] = 0;
                        $bulananBh[$key][$key1][$key2]['total_vcut'] = 0;
                        $bulananBh[$key][$key1][$key2]['jum_kr'] = 0;
                        $bulananBh[$key][$key1][$key2]['total_kr'] = 0;
                        $bulananBh[$key][$key1][$key2]['persen_kr'] = 0;

                        // skoring
                        $bulananBh[$key][$key1][$key2]['skor_mentah'] = 0;
                        $bulananBh[$key][$key1][$key2]['skor_masak'] = 0;
                        $bulananBh[$key][$key1][$key2]['skor_over'] = 0;
                        $bulananBh[$key][$key1][$key2]['skor_jjgKosong'] = 0;
                        $bulananBh[$key][$key1][$key2]['skor_vcut'] = 0;

                        $bulananBh[$key][$key1][$key2]['skor_kr'] = 0;
                        $bulananBh[$key][$key1][$key2]['TOTAL_SKOR'] = 0;
                    }

                // dd($jjgMsk);
                if ($jum_kr != 0) {
                    $total_kr = round($jum_kr / $tph_blok, 2);
                } else {
                    $total_kr = 0;
                }

                if ($sampleJJG != 0) {
                    $PerMth = round(($jjgMth / ($sampleJJG - $jjgAbn)) * 100, 2);
                } else {
                    $PerMth = 0;
                }
                if ($sampleJJG != 0) {
                    $PerMsk = round(($jjgMsk / ($sampleJJG - $jjgAbn)) * 100, 2);
                } else {
                    $PerMsk = 0;
                }

                if ($sampleJJG != 0 && $jjgAbn != 0) {
                    $PerOver = round(($jjgOver / ($sampleJJG - $jjgAbn)) * 100, 2);
                } else {
                    $PerOver = 0;
                }

                if ($sampleJJG != 0 && $jjgAbn != 0) {
                    $Perkosongjjg = round(($jjgKosng / ($sampleJJG - $jjgAbn)) * 100, 2);
                } else {
                    $Perkosongjjg = 0;
                }

                if ($sampleJJG != 0) {
                    $PerVcut = round(($vcut / $sampleJJG) * 100, 2);
                } else {
                    $PerVcut = 0;
                }

                if ($sampleJJG != 0) {
                    $PerAbr = round(($jjgAbn / $sampleJJG) * 100, 2);
                } else {
                    $PerAbr = 0;
                }

                $per_kr = round($total_kr * 100, 2);


                $totalSkor = skor_buah_mentah_mb($PerMth) + skor_buah_masak_mb($PerMsk) + skor_buah_over_mb($PerOver) + skor_vcut_mb($PerVcut) + skor_jangkos_mb($Perkosongjjg) + skor_abr_mb($per_kr);


                $nonZeroValues = array_filter([
                    $tph_blok,
                    $sampleJJG,
                    $jjgMth,
                    $jjgOver,
                    $jjgKosng,
                    $vcut,
                    $jum_kr,
                    $jjgAbn,
                    $jjgMsk
                ]);

                if (!empty($nonZeroValues) && !in_array(0, $nonZeroValues)) {
                    $bulananBh[$key][$key1]['totalSkor'] = $totalSkor;
                } else {
                    $bulananBh[$key][$key1]['totalSkor'] = 0;
                }


                $bulananBh[$key][$key1]['blok'] = $tph_blok;
                $bulananBh[$key][$key1]['sample_jjg'] = $sampleJJG;
                $bulananBh[$key][$key1]['jjg_mentah'] = $jjgMth;
                $bulananBh[$key][$key1]['mentahPerjjg'] = $PerMth;

                $bulananBh[$key][$key1]['jjg_msk'] = $jjgMsk;
                $bulananBh[$key][$key1]['mskPerjjg'] = $PerMsk;

                $bulananBh[$key][$key1]['jjg_over'] = $jjgOver;
                $bulananBh[$key][$key1]['overPerjjg'] = $PerOver;

                $bulananBh[$key][$key1]['jjg_kosong'] = $jjgKosng;
                $bulananBh[$key][$key1]['kosongPerjjg'] = $Perkosongjjg;

                $bulananBh[$key][$key1]['v_cut'] = $vcut;
                $bulananBh[$key][$key1]['vcutPerjjg'] = $PerVcut;

                $bulananBh[$key][$key1]['jjg_abr'] = $jjgAbn;
                $bulananBh[$key][$key1]['krPer'] = $per_kr;

                $bulananBh[$key][$key1]['jum_kr'] = $jum_kr;
                $bulananBh[$key][$key1]['abrPerjjg'] = $PerAbr;

                $bulananBh[$key][$key1]['skor_mentah'] = skor_buah_mentah_mb($PerMth);;
                $bulananBh[$key][$key1]['skor_msak'] = skor_buah_masak_mb($PerMsk);;
                $bulananBh[$key][$key1]['skor_over'] = skor_buah_over_mb($PerOver);;
                $bulananBh[$key][$key1]['skor_kosong'] = skor_jangkos_mb($Perkosongjjg);;
                $bulananBh[$key][$key1]['skor_vcut'] = skor_vcut_mb($PerVcut);;
                $bulananBh[$key][$key1]['skor_karung'] = skor_abr_mb($per_kr);;
                // $bulananBh[$key][$key1]['totalSkor'] = $totalSkor;
            }
        }
        // dd($bulananBh);
        $mutuTransAFD = array();
        foreach ($defaultTrans as $key => $value) {
            foreach ($value as $key1 => $value2) if (!empty($value2)) {
                $total_sample = 0;
                $total_brd = 0;
                $total_buah = 0;
                $brdPertph = 0;
                $buahPerTPH = 0;
                $totalSkor = 0;
                foreach ($value2 as $key2 => $value3)
                    if (is_array($value3)) {
                        $sum_bt = 0;
                        $sum_rst = 0;
                        $brdPertph = 0;
                        $buahPerTPH = 0;
                        $totalSkor = 0;
                        $dataBLok = 0;
                        $listBlokPerAfd = array();
                        foreach ($value3 as $key3 => $value4) {
                            // if (!in_array($value3['estate'] . ' ' . $value3['afdeling'] . ' ' . $value3['blok'] , $listBlokPerAfd)) {
                            $listBlokPerAfd[] = $value4['estate'] . ' ' . $value4['afdeling'] . ' ' . $value4['blok'];
                            // }
                            $dataBLok = count($listBlokPerAfd);
                            $sum_bt += $value4['bt'];
                            $sum_rst += $value4['rst'];
                        }
                        if ($dataBLok != 0) {
                            $brdPertph = round($sum_bt / $dataBLok, 2);
                        } else {
                            $brdPertph = 0;
                        }
                        if ($dataBLok != 0) {
                            $buahPerTPH = round($sum_rst / $dataBLok, 2);
                        } else {
                            $buahPerTPH = 0;
                        }

                        $totalSkor =  skor_brd_tinggal($brdPertph) + skor_buah_tinggal($buahPerTPH);

                        $mutuTransAFD[$key][$key1][$key2]['tph_sample'] = $dataBLok;
                        $mutuTransAFD[$key][$key1][$key2]['total_brd'] = $sum_bt;
                        $mutuTransAFD[$key][$key1][$key2]['total_brd/TPH'] = $brdPertph;
                        $mutuTransAFD[$key][$key1][$key2]['total_buah'] = $sum_rst;
                        $mutuTransAFD[$key][$key1][$key2]['total_buahPerTPH'] = $buahPerTPH;
                        $mutuTransAFD[$key][$key1][$key2]['skor_brdPertph'] =  skor_brd_tinggal($brdPertph);
                        $mutuTransAFD[$key][$key1][$key2]['skor_buahPerTPH'] = skor_buah_tinggal($buahPerTPH);
                        $mutuTransAFD[$key][$key1][$key2]['totalSkor'] = $totalSkor;

                        //perhitungan untuk est
                        // dd($value3);

                        if ($reg == 2) {
                            foreach ($newTransv2 as $keyx => $value) if ($keyx ==  $key) {
                                foreach ($value as $keyx1 => $value1) if ($keyx1 ==  $key1) {
                                    $total_sample = $value1['tph_sampleReg'];
                                }
                            }
                        } else {
                            $total_sample += $dataBLok;
                        }
                        $total_brd += $sum_bt;
                        $total_buah += $sum_rst;
                    } else {
                        $mutuTransAFD[$key][$key1][$key2]['tph_sample'] = 0;
                        $mutuTransAFD[$key][$key1][$key2]['total_brd'] = 0;
                        $mutuTransAFD[$key][$key1][$key2]['total_brd/TPH'] = 0;
                        $mutuTransAFD[$key][$key1][$key2]['total_buah'] = 0;
                        $mutuTransAFD[$key][$key1][$key2]['total_buahPerTPH'] = 0;
                        $mutuTransAFD[$key][$key1][$key2]['skor_brdPertph'] = 0;
                        $mutuTransAFD[$key][$key1][$key2]['skor_buahPerTPH'] = 0;
                        $mutuTransAFD[$key][$key1][$key2]['totalSkor'] = 0;
                    }



                if ($total_sample != 0) {
                    $brdPertph = round($total_brd / $total_sample, 2);
                } else {
                    $brdPertph = 0;
                }

                if ($total_sample != 0) {
                    $buahPerTPH = round($total_buah / $total_sample, 2);
                } else {
                    $buahPerTPH = 0;
                }

                $nonZeroValues = array_filter([$total_sample, $total_brd, $total_buah]);

                if (!empty($nonZeroValues)) {
                    $totalSkor =   skor_brd_tinggal($brdPertph) + skor_buah_tinggal($buahPerTPH);
                } else {
                    $totalSkor =  0;
                }

                // $totalSkor =   skor_brd_tinggal($brdPertph) + skor_buah_tinggal($buahPerTPH);


                $mutuTransAFD[$key][$key1]['total_sampleEST'] = $total_sample;
                $mutuTransAFD[$key][$key1]['total_brdEST'] = $total_brd;
                $mutuTransAFD[$key][$key1]['total_brdPertphEST'] = $brdPertph;
                $mutuTransAFD[$key][$key1]['total_buahEST'] = $total_buah;
                $mutuTransAFD[$key][$key1]['total_buahPertphEST'] = $buahPerTPH;
                $mutuTransAFD[$key][$key1]['skor_brd'] =   skor_brd_tinggal($brdPertph);;
                $mutuTransAFD[$key][$key1]['skor_buah'] = skor_buah_tinggal($buahPerTPH);;
                $mutuTransAFD[$key][$key1]['total_skor'] = $totalSkor;
            } else {
                $mutuTransAFD[$key][$key1]['total_sampleEST'] = 0;
                $mutuTransAFD[$key][$key1]['total_brdEST'] = 0;
                $mutuTransAFD[$key][$key1]['total_brdPertphEST'] = 0;
                $mutuTransAFD[$key][$key1]['total_buahEST'] = 0;
                $mutuTransAFD[$key][$key1]['total_buahPertphEST'] = 0;
                $mutuTransAFD[$key][$key1]['skor_brd'] = 0;
                $mutuTransAFD[$key][$key1]['skor_buah'] = 0;
                $mutuTransAFD[$key][$key1]['total_skor'] = 0;
            }
        }
        // dd($mutuTransAFD);
        // dd($mutuTransAFD,$newTransv2);

        //mt ancak 
        $GraphMTancak = array();
        foreach ($defaultNew as $key => $value) {
            foreach ($value as $key1 => $value1) if (!empty($value1)) {
                $total_brd = 0;
                $total_buah = 0;
                $total_skor = 0;
                $sum_p = 0;
                $sum_k = 0;
                $sum_gl = 0;
                $sum_panen = 0;
                $total_BrdperJJG = 0;
                $sum_pokok = 0;
                $sum_Restan = 0;
                $sum_s = 0;
                $sum_m1 = 0;
                $sum_m2 = 0;
                $sum_m3 = 0;
                $sumPerBH = 0;

                $sum_pelepah = 0;
                $perPl = 0;
                foreach ($value1 as $key2 => $value2) if (!empty($value2)) {
                    $akp = 0;
                    $skor_bTinggal = 0;
                    $brdPerjjg = 0;
                    $pokok_panen = 0;
                    $janjang_panen = 0;
                    $p_panen = 0;
                    $k_panen = 0;
                    $bhts_panen  = 0;
                    $bhtm1_panen  = 0;
                    $bhtm2_panen  = 0;
                    $bhtm3_oanen  = 0;
                    $ttlSkorMA = 0;
                    $listBlokPerAfd = array();
                    $jum_ha = 0;
                    $pelepah_s = 0;

                    $totalPokok = 0;
                    $totalPanen = 0;
                    $totalP_panen = 0;
                    $totalK_panen = 0;
                    $totalPTgl_panen = 0;
                    $totalbhts_panen = 0;
                    $totalbhtm1_panen = 0;
                    $totalbhtm2_panen = 0;
                    $totalbhtm3_oanen = 0;
                    $totalpelepah_s = 0;
                    $total_brd = 0;
                    foreach ($value2 as $key3 => $value3) if (is_array($value3)) {
                        if (!in_array($value3['estate'] . ' ' . $value3['afdeling'] . ' ' . $value3['blok'], $listBlokPerAfd)) {
                            $listBlokPerAfd[] = $value3['estate'] . ' ' . $value3['afdeling'] . ' ' . $value3['blok'];
                        }
                        $jum_ha = count($listBlokPerAfd);


                        $totalPokok += $value3["sample"];
                        $totalPanen += $value3["jjg"];
                        $totalP_panen += $value3["brtp"];
                        $totalK_panen +=  $value3["brtk"];
                        $totalPTgl_panen += $value3["brtgl"];

                        $totalbhts_panen += $value3["bhts"];
                        $totalbhtm1_panen += $value3["bhtm1"];
                        $totalbhtm2_panen += $value3["bhtm2"];
                        $totalbhtm3_oanen += $value3["bhtm3"];

                        $totalpelepah_s += $value3["ps"];
                    }
                    $akp = round(($totalPanen / $totalPokok) * 100, 1);
                    $skor_bTinggal = $totalP_panen + $totalK_panen + $totalPTgl_panen;

                    if ($totalPanen != 0) {
                        $brdPerjjg = round($skor_bTinggal / $totalPanen, 2);
                    } else {
                        $brdPerjjg = 0;
                    }

                    $sumBH = $totalbhts_panen +  $totalbhtm1_panen +  $totalbhtm2_panen +  $totalbhtm3_oanen;
                    if ($sumBH != 0) {
                        $sumPerBH = round($sumBH / ($totalPanen + $sumBH) * 100, 2);
                    } else {
                        $sumPerBH = 0;
                    }

                    if ($totalpelepah_s != 0) {
                        $perPl = ($totalpelepah_s / $totalPokok) * 100;
                    } else {
                        $perPl = 0;
                    }

                    $ttlSkorMA = skor_brd_ma($brdPerjjg) + skor_buah_Ma($sumPerBH) + skor_palepah_ma($perPl);

                    $GraphMTancak[$key][$key1][$key2]['pokok_sample'] = $totalPokok;
                    $GraphMTancak[$key][$key1][$key2]['ha_sample'] = $jum_ha;
                    $GraphMTancak[$key][$key1][$key2]['jumlah_panen'] = $totalPanen;
                    $GraphMTancak[$key][$key1][$key2]['akp_rl'] =  $akp;
                    $GraphMTancak[$key][$key1][$key2]['p'] = $totalP_panen;
                    $GraphMTancak[$key][$key1][$key2]['k'] = $totalK_panen;
                    $GraphMTancak[$key][$key1][$key2]['tgl'] = $totalPTgl_panen;
                    // $GraphMTancak[$key][$key1][$key2]['total_brd'] = $skor_bTinggal;
                    $GraphMTancak[$key][$key1][$key2]['brd/jjg'] = $brdPerjjg;

                    // data untuk buah tinggal
                    $GraphMTancak[$key][$key1][$key2]['bhts_s'] = $totalbhts_panen;
                    $GraphMTancak[$key][$key1][$key2]['bhtm1'] = $totalbhtm1_panen;
                    $GraphMTancak[$key][$key1][$key2]['bhtm2'] = $totalbhtm2_panen;
                    $GraphMTancak[$key][$key1][$key2]['bhtm3'] = $totalbhtm3_oanen;


                    $GraphMTancak[$key][$key1][$key2]['jjgperBuah'] = $sumPerBH;
                    // data untuk pelepah sengklek

                    $GraphMTancak[$key][$key1][$key2]['palepah_pokok'] = $totalpelepah_s;
                    $GraphMTancak[$key][$key1][$key2]['palepahPerPk'] = $perPl;
                    // total skor akhir
                    $GraphMTancak[$key][$key1][$key2]['skor_bh'] = skor_buah_Ma($sumPerBH);
                    $GraphMTancak[$key][$key1][$key2]['skor_brd'] = skor_brd_ma($brdPerjjg);
                    $GraphMTancak[$key][$key1][$key2]['skor_ps'] = skor_palepah_ma($perPl);
                    $GraphMTancak[$key][$key1][$key2]['skor_akhir'] = $ttlSkorMA;

                    $sum_panen += $totalPanen;
                    $sum_pokok += $totalPokok;
                    //brondolamn
                    $sum_p += $totalP_panen;
                    $sum_k += $totalK_panen;
                    $sum_gl += $totalPTgl_panen;
                    //buah tianggal
                    $sum_s += $totalbhts_panen;
                    $sum_m1 += $totalbhtm1_panen;
                    $sum_m2 += $totalbhtm2_panen;
                    $sum_m3 += $totalbhtm3_oanen;
                    //pelepah
                    $sum_pelepah += $totalpelepah_s;
                } else {
                    $GraphMTancak[$key][$key1][$key2]['pokok_sample'] = 0;
                    $GraphMTancak[$key][$key1][$key2]['ha_sample'] = 0;
                    $GraphMTancak[$key][$key1][$key2]['jumlah_panen'] = 0;
                    $GraphMTancak[$key][$key1][$key2]['akp_rl'] = 0;

                    $GraphMTancak[$key][$key1][$key2]['p'] = 0;
                    $GraphMTancak[$key][$key1][$key2]['k'] = 0;
                    $GraphMTancak[$key][$key1][$key2]['tgl'] = 0;

                    // $GraphMTancak[$key][$key1][$key2]['total_brd'] = $skor_bTinggal;
                    $GraphMTancak[$key][$key1][$key2]['brd/jjg'] = 0;
                    $GraphMTancak[$key][$key1][$key2]['skor_brd'] = 0;
                    // data untuk buah tinggal
                    $GraphMTancak[$key][$key1][$key2]['bhts_s'] = 0;
                    $GraphMTancak[$key][$key1][$key2]['bhtm1'] = 0;
                    $GraphMTancak[$key][$key1][$key2]['bhtm2'] = 0;
                    $GraphMTancak[$key][$key1][$key2]['bhtm3'] = 0;

                    $GraphMTancak[$key][$key1][$key2]['skor_bh'] = 0;
                    // $GraphMTancak[$key][$key1][$key2]['jjgperBuah'] = number_format($sumPerBH, 2);
                    // data untuk pelepah sengklek
                    $GraphMTancak[$key][$key1][$key2]['skor_ps'] = 0;
                    $GraphMTancak[$key][$key1][$key2]['palepah_pokok'] = 0;
                    $GraphMTancak[$key][$key1][$key2]['palepahPerPk'] = 0;
                    // total skor akhir
                    $GraphMTancak[$key][$key1][$key2]['skor_akhir'] = 0;
                }
                $total_brd = $sum_p + $sum_k + $sum_gl;
                $total_buah = $sum_s + $sum_m1 + $sum_m2 + $sum_m3;
                // $persenPalepah = $sum_palepah/$sum_pokok 

                if ($sum_panen != 0) {
                    $total_BrdperJJG = round($total_brd / $sum_panen, 2);
                } else {
                    $total_BrdperJJG = 0;
                }

                if ($sum_panen != 0) {
                    $sumPerBH = round($total_buah / ($sum_panen + $total_buah) * 100, 2);
                } else {
                    $sumPerBH = 0;
                }

                if ($sum_pelepah != 0) {
                    $perPl = ($sum_pelepah / $sum_pokok) * 100;
                } else {
                    $perPl = 0;
                }



                $total_skor = skor_brd_ma($total_BrdperJJG) + skor_buah_Ma($sumPerBH) + skor_palepah_ma($perPl);


                $nonZeroValues = array_filter([$total_brd, $total_buah]);

                if (!empty($nonZeroValues) && !in_array(0, $nonZeroValues)) {
                    $GraphMTancak[$key][$key1]['skor_finals'] = $total_skor;
                } else {
                    $GraphMTancak[$key][$key1]['skor_finals'] = 0;
                }


                $GraphMTancak[$key][$key1]['total_p.k.gl'] = $total_brd;
                $GraphMTancak[$key][$key1]['total_jumPanen'] = $sum_panen;
                $GraphMTancak[$key][$key1]['total_jumPokok'] = $sum_pokok;
                $GraphMTancak[$key][$key1]['total_brd/jjg'] = $total_BrdperJJG;
                $GraphMTancak[$key][$key1]['skor_brd'] = skor_brd_ma($total_BrdperJJG);
                //buah tinggal
                $GraphMTancak[$key][$key1]['s'] = $sum_s;
                $GraphMTancak[$key][$key1]['m1'] = $sum_m1;
                $GraphMTancak[$key][$key1]['m2'] = $sum_m2;
                $GraphMTancak[$key][$key1]['m3'] = $sum_m3;
                $GraphMTancak[$key][$key1]['total_bh'] = $total_buah;
                $GraphMTancak[$key][$key1]['total_bh/jjg'] = $sumPerBH;
                $GraphMTancak[$key][$key1]['skor_bh'] = skor_buah_Ma($sumPerBH);
                $GraphMTancak[$key][$key1]['pokok_palepah'] = $sum_pelepah;
                $GraphMTancak[$key][$key1]['perPalepah'] = $perPl;
                $GraphMTancak[$key][$key1]['skor_perPl'] = skor_palepah_ma($perPl);
                //total skor akhir
                // $GraphMTancak[$key][$key1]['skor_finals'] = $total_skor;
            }
        }
        // dd($mutuTransAFD['RDE']['February'], $GraphMTancak['RDE']['February'], $bulananBh['RDE']['February']);
        //hitung untuk per estate
        // dd($bulananBh['RDE']['February']);
        // dd($mutuTransAFD);
        // TOTALAN SKOR
        $RekapBulan = array();
        foreach ($mutuTransAFD as $key => $value) {
            foreach ($value as $key2  => $value2) {
                foreach ($GraphMTancak as $key3 => $value3) {
                    foreach ($value3 as $key4 => $value4) {
                        foreach ($bulananBh as $key5 => $value5) {
                            foreach ($value5 as $key6 => $value6)
                                if ($key == $key3 && $key3 == $key5 && $key2 == $key4 && $key4 == $key6) {
                                    $RekapBulan[$key][$key2]['bulan_skor'] = $value2['total_skor'] + $value4['skor_finals'] + $value6['totalSkor'];
                                }
                        }
                    }
                }
            }
        }
        // dd($RekapBulan);
        $RekapBulan_wil = array();
        foreach ($queryEste as $key => $value) {
            foreach ($RekapBulan as $key1 => $value2) if ($value['est'] == $key1 && $wilayahGrafik == $value['wil']) {
                // dd($key ,$key1);
                $RekapBulan_wil[$key1] = $value2;
            }
        }

        $RekapEst_wil = [];
        // $estData = "PLE";
        if ($estData !== 'CWS1' && isset($RekapBulan_wil)) {
            foreach ($RekapBulan_wil as $month => $data) {

                foreach ($data as $months => $data2) {

                    $RekapEst_wil[$month][$months] = isset($data2['bulan_skor']) ? $data2['bulan_skor'] : 0;
                }
            }
        } else {
            $RekapEst_wil = [
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
        // dd($RekapEst_wil);
        $RekapEst = [];
        // $estData = "PLE";
        if ($estData !== 'CWS1' && isset($RekapBulan[$estData])) {
            foreach ($RekapBulan[$estData] as $month => $data) {
                $RekapEst[$estData][$month] = isset($data['bulan_skor']) ? $data['bulan_skor'] : 0;
            }
        } else {
            $RekapEst[$estData] = [
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

        // dd($RekapEst);
        $RekapSkor = array();
        foreach ($RekapEst as $key => $value) {
            foreach ($value as $key1 => $value1) {

                $RekapSkor[] = $value1;
            }
        }
        $rekapWilayah = array();
        foreach ($RekapBulan as $key => $value) {
            foreach ($value as $key1 => $value1) {

                $rekapWilayah[] = $value1;
            }
        }

        // dd($RekapBulan);
        //mutuancak totalBRD
        $ancakBRD = [];

        if ($estData !== 'CWS1' && isset($GraphMTancak[$estData])) {
            foreach ($GraphMTancak[$estData] as $month => $data) {
                $ancakBRD[$estData][$month] = isset($data['total_brd/jjg']) ? $data['total_brd/jjg'] : 0;
            }
        } else {
            $ancakBRD[$estData] = [
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

        // dd($ancakBRD);
        $chartBTT = array();
        foreach ($ancakBRD as $key => $value) {
            foreach ($value as $key1 => $value1) {

                $chartBTT[] = $value1;
            }
        }
        // dd($chartBTT);
        //mutuancak totalnuah
        $ancakBuah = [];

        if ($estData !== 'CWS1' && isset($GraphMTancak[$estData])) {
            foreach ($GraphMTancak[$estData] as $month => $data) {
                $ancakBuah[$estData][$month] = isset($data['total_bh/jjg']) ? $data['total_bh/jjg'] : 0;
            }
        } else {
            $ancakBuah[$estData] = [
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

        // dd($ancakBuah);

        $chartBuah = array();
        foreach ($ancakBuah as $key => $value) {
            foreach ($value as $key1 => $value1) {

                $chartBuah[] = $value1;
            }
        }

        $mtbuahMentah = [];

        if ($estData !== 'CWS1' && isset($bulananBh[$estData])) {
            foreach ($bulananBh[$estData] as $month => $data) {
                $mtbuahMentah[$estData][$month] = isset($data['mentahPerjjg']) ? $data['mentahPerjjg'] : 0;
            }
        } else {
            $mtbuahMentah[$estData] = [
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

        // dd($mtbuahMentah);

        $chartBuahMentah = array();
        foreach ($mtbuahMentah as $key => $value) {
            foreach ($value as $key1 => $value1) {

                $chartBuahMentah[] = $value1;
            }
        }

        $mtbuahMasak = [];

        if ($estData !== 'CWS1' && isset($bulananBh[$estData])) {
            foreach ($bulananBh[$estData] as $month => $data) {
                $mtbuahMasak[$estData][$month] = isset($data['mskPerjjg']) ? $data['mskPerjjg'] : 0;
            }
        } else {
            $mtbuahMasak[$estData] = [
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

        // dd($mtbuahMasak);

        $chartBuahMasak = array();
        foreach ($mtbuahMasak as $key => $value) {
            foreach ($value as $key1 => $value1) {

                $chartBuahMasak[] = $value1;
            }
        }

        $mtbuahOver = [];

        if ($estData !== 'CWS1' && isset($bulananBh[$estData])) {
            foreach ($bulananBh[$estData] as $month => $data) {
                $mtbuahOver[$estData][$month] = isset($data['overPerjjg']) ? $data['overPerjjg'] : 0;
            }
        } else {
            $mtbuahOver[$estData] = [
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



        $chartBuahOver = array();
        foreach ($mtbuahOver as $key => $value) {
            foreach ($value as $key1 => $value1) {

                $chartBuahOver[] = $value1;
            }
        }

        $mtbuahKsng = [];

        if ($estData !== 'CWS1' && isset($bulananBh[$estData])) {
            foreach ($bulananBh[$estData] as $month => $data) {
                $mtbuahKsng[$estData][$month] = isset($data['kosongPerjjg']) ? $data['kosongPerjjg'] : 0;
            }
        } else {
            $mtbuahKsng[$estData] = [
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



        $chartBuahKsng = array();
        foreach ($mtbuahKsng as $key => $value) {
            foreach ($value as $key1 => $value1) {

                $chartBuahKsng[] = $value1;
            }
        }

        $mtbuahVcut = [];

        if ($estData !== 'CWS1' && isset($bulananBh[$estData])) {
            foreach ($bulananBh[$estData] as $month => $data) {
                $mtbuahVcut[$estData][$month] = isset($data['vcutPerjjg']) ? $data['vcutPerjjg'] : 0;
            }
        } else {
            $mtbuahVcut[$estData] = [
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



        $chartBuahVcut = array();
        foreach ($mtbuahVcut as $key => $value) {
            foreach ($value as $key1 => $value1) {

                $chartBuahVcut[] = $value1;
            }
        }

        $mtbuahAbr = [];

        if ($estData !== 'CWS1' && isset($bulananBh[$estData])) {
            foreach ($bulananBh[$estData] as $month => $data) {
                $mtbuahAbr[$estData][$month] = isset($data['abrPerjjg']) ? $data['abrPerjjg'] : 0;
            }
        } else {
            $mtbuahAbr[$estData] = [
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



        $chartBuahAbr = array();
        foreach ($mtbuahAbr as $key => $value) {
            foreach ($value as $key1 => $value1) {

                $chartBuahAbr[] = $value1;
            }
        }
        $mtTransportbrd = [];

        if ($estData !== 'CWS1' && isset($mutuTransAFD[$estData])) {
            foreach ($mutuTransAFD[$estData] as $month => $data) {
                $mtTransportbrd[$estData][$month] = isset($data['total_brdPertphEST']) ? $data['total_brdPertphEST'] : 0;
            }
        } else {
            $mtTransportbrd[$estData] = [
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



        $chartTransBrd = array();
        foreach ($mtTransportbrd as $key => $value) {
            foreach ($value as $key1 => $value1) {

                $chartTransBrd[] = $value1;
            }
        }

        $mtTransportbuah = [];


        if ($estData !== 'CWS1' && isset($mutuTransAFD[$estData])) {
            foreach ($mutuTransAFD[$estData] as $month => $data) {
                $mtTransportbuah[$estData][$month] = isset($data['total_buahPertphEST']) ? $data['total_buahPertphEST'] : 0;
            }
        } else {
            $mtTransportbuah[$estData] = [
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



        $chartTransbuah = array();
        foreach ($mtTransportbuah as $key => $value) {
            foreach ($value as $key1 => $value1) {

                $chartTransbuah[] = $value1;
            }
        }


        // dd($RekapSkor, $chartBuah);

        $arrView = array();

        // dd($queryEste);

        $arrView['GraphBtt'] =  $chartBTT;
        $arrView['GraphBuah'] =  $chartBuah;
        $arrView['GraphSkorTotal'] =  $RekapSkor;
        $arrView['list_est'] =  $queryEste;
        $arrView['mtbuah_mth'] =  $chartBuahMentah;
        $arrView['mtbuah_masak'] =  $chartBuahMasak;
        $arrView['mtbuah_over'] =  $chartBuahOver;
        $arrView['mtbuah_ksng'] =  $chartBuahKsng;
        $arrView['mtbuah_vcut'] =  $chartBuahVcut;
        $arrView['mtbuah_abr'] =  $chartBuahAbr;
        $arrView['mttransbrd'] =  $chartTransBrd;
        $arrView['mttransbb'] =  $chartTransbuah;
        $arrView['rekap_wil'] =  $RekapEst_wil;

        echo json_encode($arrView); //di decode ke dalam bentuk json dalam vaiavel arrview yang dapat menampung banyak isi array
        exit();
    }


    public function editnilaidataestate(Request $request)
    {
        $regional = $request->input('regional');
        $date = $request->input('date');
        $estate = $request->input('estate');
        $nilai = $request->input('nilai');
        $type = $request->input('type');

        // dd($date, $estate, $nilai, $type);
        if ($type === 'true') {
            try {
                // Check if a record with the same 'type', 'estate', and 'date' already exists
                $existingRecord = DB::connection('mysql2')->table('list_estate_nilai')
                    ->where('est', $estate)
                    ->where('date', $date)
                    ->where('tipe', 'minus')
                    ->first();

                if ($existingRecord) {
                    // If the record exists, return a response indicating it already exists
                    return response()->json(['message' => 'Anda tidak dapat mengurangi atau menambah lagi estate ini'], 200);
                }

                // If no matching record is found, insert the new record
                DB::connection('mysql2')->table('list_estate_nilai')->insert([
                    'est' => $estate,
                    'date' => $date,
                    'nilai' => $nilai,
                    'tipe' => 'minus',
                ]);

                return response()->json(['message' => 'Nilai berhasil di kurang'], 200);
            } catch (\Throwable $th) {
                return response()->json(['error' => $th->getMessage()], 500);
            }
        } else {
            try {
                // Check if a record with the same 'type', 'estate', and 'date' already exists
                $existingRecord = DB::connection('mysql2')->table('list_estate_nilai')
                    ->where('est', $estate)
                    ->where('date', $date)
                    ->where('tipe', 'plus')
                    ->first();

                if ($existingRecord) {
                    // If the record exists, return a response indicating it already exists
                    return response()->json(['message' => 'Anda tidak dapat mengurangi atau menambah lagi estate ini'], 200);
                }

                // If no matching record is found, insert the new record
                DB::connection('mysql2')->table('list_estate_nilai')->insert([
                    'est' => $estate,
                    'date' => $date,
                    'nilai' => $nilai,
                    'tipe' => 'plus',
                ]);

                return response()->json(['message' => 'Nilai berhasil di tambah'], 200);
            } catch (\Throwable $th) {
                return response()->json(['error' => $th->getMessage()], 500);
            }
        }
    }
}
