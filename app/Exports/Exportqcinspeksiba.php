<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\WithEvents;

class Exportqcinspeksiba implements FromView, WithEvents
{

    protected $regional;
    protected $date;
    protected $est;
    protected $afd;

    public function __construct($date, $regional, $est, $afd)
    {
        $this->regional = $regional;
        $this->date = $date;
        $this->est = $est;
        $this->afd = $afd;
    }
    public function view(): View
    {
        $regional = $this->regional;
        $date = $this->date;
        $est = $this->est;
        $afd = $this->afd;
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
            // ->whereNotIn('estate.est', ['SRE', 'LDE', 'SKE'])
            ->get();
        $queryEste = json_decode($queryEste, true);

        // dd($queryEste);

        $muaest = DB::connection('mysql2')->table('estate')
            ->select('estate.*')
            ->where('estate.emp', '!=', 1)
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->where('wil.regional', $regional)
            // ->where('estate.emp', '!=', 1)
            // ->whereIn('estate.est', ['SRE', 'LDE', 'SKE'])
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
            ->where('datetime', 'like', '%' . $date . '%')
            ->where('estate', $est)
            ->where('afdeling', $afd)
            ->orderBy('afdeling', 'asc')
            ->get();
        $QueryMTancakWil = $QueryMTancakWil->groupBy(['afdeling', 'blok']);
        $QueryMTancakWil = json_decode($QueryMTancakWil, true);

        $dataPerBulan = array();
        foreach ($QueryMTancakWil as $key => $value) {
            foreach ($value as $key2 => $value2) {
                foreach ($value2 as $key3 => $value3) {
                    $dataPerBulan[$key][$key2][$key3] = $value3;
                }
            }
        }

        $QueryTransWil = DB::connection('mysql2')->table('mutu_transport')
            ->select(
                "mutu_transport.*",
                DB::raw('DATE_FORMAT(mutu_transport.datetime, "%M") as bulan'),
                DB::raw('DATE_FORMAT(mutu_transport.datetime, "%Y") as tahun')
            )
            ->where('estate', $est)
            ->where('afdeling', $afd)
            ->where('datetime', 'like', '%' . $date . '%')
            // ->whereYear('datetime', $year)
            ->get();
        $QueryTransWil = $QueryTransWil->groupBy(['afdeling', 'blok']);
        $QueryTransWil = json_decode($QueryTransWil, true);

        $dataMTTrans = array();
        foreach ($QueryTransWil as $key => $value) {
            foreach ($value as $key2 => $value2) {
                foreach ($value2 as $key3 => $value3) {
                    $dataMTTrans[$key][$key2][$key3] = $value3;
                }
            }
        }
        $QueryMTbuahWil = DB::connection('mysql2')->table('mutu_buah')
            ->select(
                "mutu_buah.*",
                DB::raw('DATE_FORMAT(mutu_buah.datetime, "%M") as bulan'),
                DB::raw('DATE_FORMAT(mutu_buah.datetime, "%Y") as tahun')
            )
            ->where('estate', $est)
            ->where('afdeling', $afd)
            ->where('datetime', 'like', '%' . $date . '%')
            ->orderBy('afdeling', 'asc')
            ->get();
        $QueryMTbuahWil = $QueryMTbuahWil->groupBy(['afdeling', 'blok']);
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


        // dd($mergedata);
        $rekap = [];

        foreach ($dataPerBulan as $key1 => $value1) if (!empty($value2)) {
            $jum_haEst =  0;
            foreach ($value1 as $key2 => $value2) if (!empty($value2)) {
                // dd($value2);
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
                    // dd($value3);
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
                    $luas_blok = $value3["luas_blok"];
                    $status_panen = $value3["status_panen"];

                    // dd($value3);
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
                    $perPl = round(($totalpelepah_s / $totalPokok) * 100, 3);
                } else {
                    $perPl = 0;
                }





                $nonZeroValues = array_filter([$totalP_panen, $totalK_panen, $totalPTgl_panen, $totalbhts_panen, $totalbhtm1_panen, $totalbhtm2_panen, $totalbhtm3_oanen]);

                if (!empty($nonZeroValues)) {
                    $rekap[$key1][$key2]['check_datacak'] = 'ada';
                } else {
                    $rekap[$key1][$key2]['check_datacak'] = 'kosong';
                }

                // $ttlSkorMA = $skor_bh + $skor_brd + $skor_ps;
                $ttlSkorMA =  skor_buah_Ma($sumPerBH) + skor_brd_ma($brdPerjjg) + skor_palepah_ma($perPl);

                $rekap[$key1][$key2]['pokok_samplecak'] = $totalPokok;
                $rekap[$key1][$key2]['ha_samplecak'] = $jum_ha;
                $rekap[$key1][$key2]['jumlah_panencak'] = $totalPanen;
                $rekap[$key1][$key2]['akp_rlcak'] = $akp;
                $rekap[$key1][$key2]['pcak'] = $totalP_panen;
                $rekap[$key1][$key2]['kcak'] = $totalK_panen;
                $rekap[$key1][$key2]['tglcak'] = $totalPTgl_panen;
                $rekap[$key1][$key2]['total_brdcak'] = $skor_bTinggal;
                $rekap[$key1][$key2]['brd/jjgcak'] = $brdPerjjg;
                // data untuk buah tinggal
                $rekap[$key1][$key2]['bhts_scak'] = $totalbhts_panen;
                $rekap[$key1][$key2]['bhtm1cak'] = $totalbhtm1_panen;
                $rekap[$key1][$key2]['bhtm2cak'] = $totalbhtm2_panen;
                $rekap[$key1][$key2]['bhtm3cak'] = $totalbhtm3_oanen;
                $rekap[$key1][$key2]['buah/jjgcak'] = $sumPerBH;
                $rekap[$key1][$key2]['total_buahcak'] = $sumBH;
                $rekap[$key1][$key2]['jjgperBuahcak'] = number_format($sumPerBH, 3);
                // data untuk pelepah sengklek
                $rekap[$key1][$key2]['palepah_pokokcak'] = $totalpelepah_s;
                $rekap[$key1][$key2]['palepah_percak'] = $perPl;
                $rekap[$key1][$key2]['skor_bhcak'] = skor_buah_Ma($sumPerBH);
                $rekap[$key1][$key2]['skor_brdcak'] = skor_brd_ma($brdPerjjg);
                $rekap[$key1][$key2]['skor_pscak'] =  skor_palepah_ma($perPl);
                // total skor akhir
                $rekap[$key1][$key2]['skor_akhircak'] = $ttlSkorMA;
                $rekap[$key1][$key2]['check_inputcak'] = $check_input;
                $rekap[$key1][$key2]['est'] = $key1;
                $rekap[$key1][$key2]['afd'] = $key2;
                $rekap[$key1][$key2]['mutuancak'] = '-----------------------------------';

                // foreach ($dataMTTrans as $keyx => $valuex) {
                //     foreach ($valuex as $keyx1 => $valuex1) if ($key == $keyx) {
                //         foreach ($valuex1 as $keyx2 => $valuex2) {
                //             // dd($key2 === $keyx1);
                //             if ($keyx1 === $key2) {

                //                 $rekap[$key1][$key2]['status_trans'] = ($keyx1 === $key2);
                //                 $rekap[$key1][$key2]['mututrans'] = '-----------------------------------';
                //             } else {
                //                 $rekap[$keyx][$keyx1]['status_trans'] = ($keyx1 === $key2);
                //                 $rekap[$keyx][$keyx1]['mututrans'] = '-----------------------------------';
                //             }
                //         }
                //     }
                // }
            }
        }


        foreach ($dataMTBuah as $key1 => $value1) if (is_array($value1)) {
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
                    $rekap[$key1][$key2]['check_databh'] = 'ada';
                } else {
                    $rekap[$key1][$key2]['check_databh'] = 'kosong';
                }
                $totalSkor =  skor_buah_mentah_mb($PerMth) + skor_buah_masak_mb($PerMsk) + skor_buah_over_mb($PerOver) + skor_vcut_mb($PerVcut) + skor_jangkos_mb($Perkosongjjg) + skor_abr_mb($per_kr);
                $rekap[$key1][$key2]['tph_baris_bloksbh'] = $dataBLok;
                $rekap[$key1][$key2]['sampleJJG_totalbh'] = $sum_Samplejjg;
                $rekap[$key1][$key2]['total_mentahbh'] = $jml_mth;
                $rekap[$key1][$key2]['total_perMentahbh'] = $PerMth;
                $rekap[$key1][$key2]['total_masakbh'] = $jml_mtg;
                $rekap[$key1][$key2]['total_perMasakbh'] = $PerMsk;
                $rekap[$key1][$key2]['total_overbh'] = $sum_over;
                $rekap[$key1][$key2]['total_perOverbh'] = $PerOver;
                $rekap[$key1][$key2]['total_abnormalbh'] = $sum_abnor;
                $rekap[$key1][$key2]['perAbnormalbh'] = $PerAbr;
                $rekap[$key1][$key2]['total_jjgKosongbh'] = $sum_kosongjjg;
                $rekap[$key1][$key2]['total_perKosongjjgbh'] = $Perkosongjjg;
                $rekap[$key1][$key2]['total_vcutbh'] = $sum_vcut;
                $rekap[$key1][$key2]['perVcutbh'] = $PerVcut;

                $rekap[$key1][$key2]['jum_krbh'] = $sum_kr;
                $rekap[$key1][$key2]['total_krbh'] = $total_kr;
                $rekap[$key1][$key2]['persen_krbh'] = $per_kr;

                // skoring
                $rekap[$key1][$key2]['skor_mentahbh'] = skor_buah_mentah_mb($PerMth);
                $rekap[$key1][$key2]['skor_masakbh'] = skor_buah_masak_mb($PerMsk);
                $rekap[$key1][$key2]['skor_overbh'] = skor_buah_over_mb($PerOver);
                $rekap[$key1][$key2]['skor_jjgKosongbh'] = skor_jangkos_mb($Perkosongjjg);
                $rekap[$key1][$key2]['skor_vcutbh'] = skor_vcut_mb($PerVcut);
                $rekap[$key1][$key2]['skor_krbh'] = skor_abr_mb($per_kr);
                $rekap[$key1][$key2]['TOTAL_SKORbh'] = $totalSkor;
                $rekap[$key1][$key2]['mutubuah'] = '-----------------------------------------';
            }
        }



        // dd($rekap);

        // dd($dataMTTrans, $rekap);

        $getkeytrans = [];
        foreach ($dataMTTrans as $key => $value1) {
            foreach ($value1 as $key1 => $value2) {
                $getkeytrans[] = $key1;
            } # code...
        }
        // dd($getkeytrans);
        foreach ($dataMTTrans as $key1 => $value1) if (!empty($value1)) {

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

                if ($dataBLok != 0) {
                    $brdPertph = round($sum_bt / $dataBLok, 3);
                } else {
                    $brdPertph = 0;
                }

                if ($dataBLok != 0) {
                    $buahPerTPH = round($sum_rst / $dataBLok, 3);
                } else {
                    $buahPerTPH = 0;
                }


                $nonZeroValues = array_filter([$sum_bt, $sum_rst]);

                if (!empty($nonZeroValues)) {
                    $rekap[$key1][$key2]['check_datatrans'] = 'ada';
                } else {
                    $rekap[$key1][$key2]['check_datatrans'] = "kosong";
                }


                foreach ($dataPerBulan as $keyx => $valuex) {

                    foreach ($valuex as $keyx1 => $valuex1) {

                        foreach ($valuex1 as $keyx2 => $valuex2) {

                            if (in_array($getkeytrans, $keyx1)) {
                                $rekap[$keyx][$keyx1]['adatransancak'] = '-----------------------------------';
                            } elseif (!in_array($getkeytrans, $keyx1)) {
                                $rekap[$key1][$key2]['adaancak'] = '-----------------------------------';
                            } else {
                                $rekap[$key1][$key2]['adatrans'] = '-----------------------------------';
                            }
                        } # code...
                    }
                }
                $totalSkor =   skor_brd_tinggal($brdPertph) + skor_buah_tinggal($buahPerTPH);
                $rekap[$key1][$key2]['tph_sampleNew'] = $dataBLok;
                $rekap[$key1][$key2]['total_brdtrans'] = $sum_bt;
                $rekap[$key1][$key2]['total_brdperTPHtrans'] = $brdPertph;
                $rekap[$key1][$key2]['total_buahtrans'] = $sum_rst;
                $rekap[$key1][$key2]['total_buahPerTPHtrans'] = $buahPerTPH;
                $rekap[$key1][$key2]['skor_brdPertphtrans'] = skor_brd_tinggal($brdPertph);
                $rekap[$key1][$key2]['skor_buahPerTPHtrans'] = skor_buah_tinggal($buahPerTPH);
                $rekap[$key1][$key2]['totalSkortrans'] = $totalSkor;
                $rekap[$key1][$key2]['mututrans'] = '-----------------------------------';
            }
        }


        dd($rekap);

        // dd($rekap[3]['SKE']);



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


        $getwilx = [];

        foreach ($rekap as $key => $value) {
            if (isset($value['wilayah'])) {
                $getwilx[$key] = $value['wilayah']['wil'];
            }
        }

        // dd($getwilx);
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
            $perPl = round(($palepah_pokokcak / $pokok_samplecak) * 100, 3);
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

        $totalSkorEsttrans = skor_brd_tinggal($brdPertphEst) + skor_buah_tinggal($buahPerTPHEst);
        $totalSkorEstancak =  skor_palepah_ma($perPl) + skor_buah_Ma($sumPerBH) + skor_brd_ma($brdPerjjg);
        $totalSkorBuah =  skor_buah_mentah_mb($PerMth) + skor_buah_masak_mb($PerMsk) + skor_buah_over_mb($PerOver) + skor_vcut_mb($PerVcut) + skor_jangkos_mb($Perkosongjjg) + skor_abr_mb($per_kr);

        $dataReg['pokok_samplecak'] = $pokok_samplecak;
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
        $dataReg['skor_brdPertphtrans'] = skor_brd_tinggal($brdPertphEst);
        $dataReg['skor_buahPerTPHtrans'] = skor_buah_tinggal($buahPerTPHEst);
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


        return view('sidakmutubuah.qcinspeksiexcel', ['data' => $rekap]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $styleHeader = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => 'thin',
                            'color' => ['rgb' => '808080']
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                ];
                $styleHeader2 = [
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                ];

                $event->sheet->getStyle('C3:BA3')->applyFromArray($styleHeader);
                $event->sheet->getStyle('A1:B1')->applyFromArray($styleHeader);
                $event->sheet->getStyle('AE2:AG2')->applyFromArray($styleHeader);
                $event->sheet->getStyle('BB2')->applyFromArray($styleHeader);
                $event->sheet->getStyle('BC1:BD1')->applyFromArray($styleHeader);
                $event->sheet->getStyle('W2:X2')->applyFromArray($styleHeader);
                $event->sheet->getStyle('BD')->applyFromArray($styleHeader2);
            },
        ];
    }
}
