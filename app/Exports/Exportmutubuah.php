<?php

namespace App\Exports;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\WithEvents;


class Exportmutubuah implements FromView, WithEvents
{
    protected $regional;
    protected $date;



    public function __construct($regional, $tanggal)
    {
        $this->regional = $regional;
        $this->date = $tanggal;
    }
    public function view(): View
    {
        $date = $this->date;
        $regional = $this->regional;


        // dd($regional, $date);

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
            ->whereNotIn('estate.est', ['SRE', 'LDE', 'SKE'])
            ->get();
        $queryEste = json_decode($queryEste, true);
        $muaest = DB::connection('mysql2')->table('estate')
            ->select('estate.*')
            ->where('estate.emp', '!=', 1)
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->where('wil.regional', $regional)
            // ->where('estate.emp', '!=', 1)
            ->whereIn('estate.est', ['SRE', 'LDE', 'SKE'])
            ->get('est');
        $muaest = json_decode($muaest, true);
        // dd($queryEste);

        $estev2 = DB::connection('mysql2')->table('estate')
            ->select('estate.*')
            ->where('estate.emp', '!=', 1)
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->where('wil.regional', $regional)
            ->where('estate.emp', '!=', 1)
            ->whereNotIn('estate.est', ['SRE', 'LDE', 'SKE'])
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
            ->where('sidak_mutu_buah.datetime', 'like', '%' . $date . '%')

            // ->whereBetween('sidak_mutu_buah.datetime', ['2023-04-03', '2023-04-09'])
            ->get();
        $queryMTbuah = $queryMTbuah->groupBy(['estate', 'afdeling']);
        $queryMTbuah = json_decode($queryMTbuah, true);

        // dd($queryMTbuah);

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
        $mutu_buah = array();
        foreach ($queryEste as $key => $value) {
            foreach ($defPerbulanWil as $key2 => $value2) {
                if ($value['est'] == $key2) {
                    $mutu_buah[$value['wil']][$key2] = array_merge($mutu_buah[$value['wil']][$key2] ?? [], $value2);
                }
            }
        }

        // dd($mutu_buah);

        $sidak_buah = array();
        $jjg_sampleEST = 0;
        $blokEST = 0;
        $tnpBRDEST = 0;
        $krgBRDEST = 0;
        $abrEST = 0;
        $overripeEST = 0;
        $emptyEST = 0;
        $vcutEST = 0;
        $rdEST = 0;
        $sum_krEST = 0;
        foreach ($mutu_buah as $key => $value) {
            $jjg_samplew = 0;
            $tnpBRDw = 0;
            $krgBRDw = 0;
            $abrw = 0;
            $overripew = 0;
            $emptyw = 0;
            $vcutw = 0;
            $rdw = 0;
            $dataBLokw = 0;
            $sum_krw = 0;
            $csfxr2 = 0;
            foreach ($value as $key1 => $value1) {
                $jjg_samplex = 0;
                $tnpBRDx = 0;
                $krgBRDx = 0;
                $abrx = 0;
                $overripex = 0;
                $emptyx = 0;
                $vcutx = 0;
                $rdx = 0;
                $dataBLokx = 0;
                $sum_krx = 0;
                $csfxr1 = 0;
                foreach ($value1 as $key2 => $value2) if (is_array($value2)) {
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
                    $newblok = 0;
                    $csfxr = count($value2);
                    foreach ($value2 as $key3 => $value3) if (is_array($value3)) {

                        $combination = $value3['blok'] . ' ' . $value3['estate'] . ' ' . $value3['afdeling'] . ' ' . $value3['tph_baris'];
                        if (!isset($combination_counts[$combination])) {
                            $combination_counts[$combination] = 0;
                        }
                        $newblok = count($value2);
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
                    $dataBLok = $newblok;
                    if ($sum_kr != 0) {
                        $total_kr = round($sum_kr / $dataBLok, 3);
                    } else {
                        $total_kr = 0;
                    }
                    $per_kr = round($total_kr * 100, 3);
                    $skor_total = round((($tnpBRD + $krgBRD) / ($jjg_sample - $abr)) * 100, 3);
                    $skor_jjgMSk = round(($jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr)) / ($jjg_sample - $abr) * 100, 3);
                    $skor_lewatMTng =  round(($overripe / ($jjg_sample - $abr)) * 100, 3);
                    $skor_jjgKosong =  round(($empty / ($jjg_sample - $abr)) * 100, 3);
                    $skor_vcut =   round(($vcut / $jjg_sample) * 100, 3);
                    $allSkor = sidak_brdTotal($skor_total) +  sidak_matangSKOR($skor_jjgMSk) +  sidak_lwtMatang($skor_lewatMTng) + sidak_jjgKosong($skor_jjgKosong) + sidak_tangkaiP($skor_vcut) + sidak_PengBRD($per_kr);

                    $sidak_buah[$key][$key1][$key2]['Jumlah_janjang'] = $jjg_sample;
                    $sidak_buah[$key][$key1][$key2]['blok'] = $dataBLok;
                    $sidak_buah[$key][$key1][$key2]['est'] = $key1;
                    $sidak_buah[$key][$key1][$key2]['afd'] = $key2;
                    $sidak_buah[$key][$key1][$key2]['nama_staff'] = '-';
                    $sidak_buah[$key][$key1][$key2]['tnp_brd'] = $tnpBRD;
                    $sidak_buah[$key][$key1][$key2]['krg_brd'] = $krgBRD;
                    $sidak_buah[$key][$key1][$key2]['persenTNP_brd'] = round(($tnpBRD / ($jjg_sample - $abr)) * 100, 3);
                    $sidak_buah[$key][$key1][$key2]['persenKRG_brd'] = round(($krgBRD / ($jjg_sample - $abr)) * 100, 3);
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
                    $sidak_buah[$key][$key1][$key2]['karung'] = $sum_kr;
                    $sidak_buah[$key][$key1][$key2]['vcut_persen'] = $skor_vcut;
                    $sidak_buah[$key][$key1][$key2]['vcut_skor'] = sidak_tangkaiP($skor_vcut);
                    $sidak_buah[$key][$key1][$key2]['abnormal'] = $abr;
                    $sidak_buah[$key][$key1][$key2]['abnormal_persen'] = round(($abr / $jjg_sample) * 100, 3);
                    $sidak_buah[$key][$key1][$key2]['rat_dmg'] = $rd;
                    $sidak_buah[$key][$key1][$key2]['rd_persen'] = round(($rd / $jjg_sample) * 100, 3);
                    $sidak_buah[$key][$key1][$key2]['TPH'] = $total_kr;
                    $sidak_buah[$key][$key1][$key2]['persen_krg'] = $per_kr;
                    $sidak_buah[$key][$key1][$key2]['skor_kr'] = sidak_PengBRD($per_kr);
                    $sidak_buah[$key][$key1][$key2]['All_skor'] = $allSkor;
                    $sidak_buah[$key][$key1][$key2]['csfxr'] = $csfxr;
                    $sidak_buah[$key][$key1][$key2]['kategori'] = sidak_akhir($allSkor);
                    // dd($key2);
                    foreach ($queryAsisten as $ast => $asisten) {
                        if ($key1 === $asisten['est'] && $key2 === $asisten['afd']) {
                            $sidak_buah[$key][$key1][$key2]['nama_asisten'] = $asisten['nama'];
                            break;
                        }
                    }


                    $jjg_samplex += $jjg_sample;
                    $tnpBRDx += $tnpBRD;
                    $krgBRDx += $krgBRD;
                    $abrx += $abr;
                    $overripex += $overripe;
                    $emptyx += $empty;
                    $vcutx += $vcut;
                    $rdx += $rd;
                    $dataBLokx += $dataBLok;
                    $sum_krx += $sum_kr;
                    $csfxr1 += $csfxr;
                } else {
                    $sidak_buah[$key][$key1][$key2]['Jumlah_janjang'] = '-';
                    $sidak_buah[$key][$key1][$key2]['blok']  = '-';
                    $sidak_buah[$key][$key1][$key2]['est'] = $key1;
                    $sidak_buah[$key][$key1][$key2]['afd'] = $key2;
                    foreach ($queryAsisten as $ast => $asisten) {
                        if ($key1 === $asisten['est'] && $key2 === $asisten['afd']) {
                            $sidak_buah[$key][$key1][$key2]['nama_asisten'] = $asisten['nama'];
                            break;
                        }
                    }
                    $sidak_buah[$key][$key1][$key2]['nama_staff']  = '-';
                    $sidak_buah[$key][$key1][$key2]['tnp_brd']  = '-';
                    $sidak_buah[$key][$key1][$key2]['krg_brd']  = '-';
                    $sidak_buah[$key][$key1][$key2]['persenTNP_brd']  = '-';
                    $sidak_buah[$key][$key1][$key2]['persenKRG_brd']  = '-';
                    $sidak_buah[$key][$key1][$key2]['total_jjg']  = '-';
                    $sidak_buah[$key][$key1][$key2]['persen_totalJjg'] = '-';
                    $sidak_buah[$key][$key1][$key2]['skor_total']  = '-';
                    $sidak_buah[$key][$key1][$key2]['jjg_matang']  = '-';
                    $sidak_buah[$key][$key1][$key2]['persen_jjgMtang']  = '-';
                    $sidak_buah[$key][$key1][$key2]['skor_jjgMatang'] = '-';
                    $sidak_buah[$key][$key1][$key2]['lewat_matang'] = '-';
                    $sidak_buah[$key][$key1][$key2]['persen_lwtMtng']  = '-';
                    $sidak_buah[$key][$key1][$key2]['skor_lewatMTng']  = '-';
                    $sidak_buah[$key][$key1][$key2]['janjang_kosong']  = '-';
                    $sidak_buah[$key][$key1][$key2]['persen_kosong'] = '-';
                    $sidak_buah[$key][$key1][$key2]['skor_kosong']  = '-';
                    $sidak_buah[$key][$key1][$key2]['vcut']  = '-';
                    $sidak_buah[$key][$key1][$key2]['karung']  = '-';
                    $sidak_buah[$key][$key1][$key2]['vcut_persen']  = '-';
                    $sidak_buah[$key][$key1][$key2]['vcut_skor']  = '-';
                    $sidak_buah[$key][$key1][$key2]['abnormal']  = '-';
                    $sidak_buah[$key][$key1][$key2]['abnormal_persen']  = '-';
                    $sidak_buah[$key][$key1][$key2]['rat_dmg'] = '-';
                    $sidak_buah[$key][$key1][$key2]['rd_persen'] = '-';
                    $sidak_buah[$key][$key1][$key2]['TPH']  = '-';
                    $sidak_buah[$key][$key1][$key2]['persen_krg']  = '-';
                    $sidak_buah[$key][$key1][$key2]['skor_kr']  = '-';
                    $sidak_buah[$key][$key1][$key2]['All_skor']  = '-';
                    $sidak_buah[$key][$key1][$key2]['csfxr']  = '-';
                    $sidak_buah[$key][$key1][$key2]['kategori']  = '-';
                }
                if ($sum_krx != 0) {
                    $total_kr = round($sum_krx / $dataBLokx, 3);
                } else {
                    $total_kr = 0;
                }
                $per_kr = round($total_kr * 100, 3);
                $skor_total = round(($jjg_samplex - $abrx != 0 ? (($tnpBRDx + $krgBRDx) / ($jjg_samplex - $abrx)) * 100 : 0), 3);

                $skor_jjgMSk = round(($jjg_samplex - $abrx != 0 ? (($jjg_samplex - ($tnpBRDx + $krgBRDx + $overripex + $emptyx + $abrx)) / ($jjg_samplex - $abrx)) * 100 : 0), 3);

                $skor_lewatMTng = round(($jjg_samplex - $abrx != 0 ? ($overripex / ($jjg_samplex - $abrx)) * 100 : 0), 3);

                $skor_jjgKosong = round(($jjg_samplex - $abrx != 0 ? ($emptyx / ($jjg_samplex - $abrx)) * 100 : 0), 3);

                $skor_vcut = round(($jjg_samplex != 0 ? ($vcutx / $jjg_samplex) * 100 : 0), 3);

                $allSkor = sidak_brdTotal($skor_total) +  sidak_matangSKOR($skor_jjgMSk) +  sidak_lwtMatang($skor_lewatMTng) + sidak_jjgKosong($skor_jjgKosong) + sidak_tangkaiP($skor_vcut) + sidak_PengBRD($per_kr);

                $em = 'EM';

                $nama_em = '';

                // dd($key1);
                foreach ($queryAsisten as $ast => $asisten) {
                    if ($key1 === $asisten['est'] && $em === $asisten['afd']) {
                        $nama_em = $asisten['nama'];
                    }
                }
                $jjg_mth = $tnpBRDx + $krgBRDx + $overripex + $emptyx;
                $skor_jjgMTh = ($jjg_samplex - $abrx != 0) ? round($jjg_mth / ($jjg_samplex - $abrx) * 100, 3) : 0;
                if ($csfxr1 == 0) {
                    $check_arr = 'kosong';
                    $hasil = '-';
                } else {
                    $check_arr = 'ada';
                    $hasil = $allSkor;
                }


                $sidak_buah[$key][$key1]['EST'] = [
                    'jjg_mantah' => $csfxr1 == 0 ? '-' :  $jjg_mth,
                    'persen_jjgmentah' => $csfxr1 == 0 ? '-' :  $skor_jjgMTh,
                    'check_arr' => $csfxr1 == 0 ? '-' :  $check_arr,
                    'All_skor' => $csfxr1 == 0 ? '-' : $hasil,
                    'est' => $key1,
                    'afd' => 'EST',
                    'Jumlah_janjang' => $csfxr1 == 0 ? '-' : $jjg_samplex,
                    'blok' => $csfxr1 == 0 ? '-' :  $dataBLokx,
                    'EM' => 'EM',
                    'nama_staff' => $nama_em,
                    'nama_asisten' => $nama_em,
                    'tnp_brd' => $csfxr1 == 0 ? '-' :  $tnpBRDx,
                    'krg_brd' => $csfxr1 == 0 ? '-' :  $krgBRDx,
                    'persenTNP_brd' => $csfxr1 == 0 ? '-' :  round(($jjg_samplex - $abrx != 0 ? ($tnpBRDx / ($jjg_samplex - $abrx)) * 100 : 0), 3),
                    'persenKRG_brd' => $csfxr1 == 0 ? '-' :  round(($jjg_samplex - $abrx != 0 ? ($krgBRDx / ($jjg_samplex - $abrx)) * 100 : 0), 3),
                    'abnormal_persen' => $csfxr1 == 0 ? '-' :  round(($jjg_samplex != 0 ? ($abrx / $jjg_samplex) * 100 : 0), 3),
                    'rd_persen' => $csfxr1 == 0 ? '-' :  round(($jjg_samplex != 0 ? ($rdx / $jjg_samplex) * 100 : 0), 3),
                    'total_jjg' => $csfxr1 == 0 ? '-' :  $tnpBRDx + $krgBRDx,
                    'persen_totalJjg' => $csfxr1 == 0 ? '-' :  $skor_total,
                    'skor_total' =>   $csfxr1 == 0 ? '-' : sidak_brdTotal($skor_total),
                    'jjg_matang' => $csfxr1 == 0 ? '-' :  $jjg_samplex - ($tnpBRDx + $krgBRDx + $overripex + $emptyx + $abrx),
                    'persen_jjgMtang' => $csfxr1 == 0 ? '-' :  $skor_jjgMSk,
                    'skor_jjgMatang' => $csfxr1 == 0 ? '-' :  sidak_matangSKOR($skor_jjgMSk),
                    'lewat_matang' => $csfxr1 == 0 ? '-' :  $overripex,
                    'persen_lwtMtng' => $csfxr1 == 0 ? '-' :   $skor_lewatMTng,
                    'skor_lewatMTng' => $csfxr1 == 0 ? '-' : sidak_lwtMatang($skor_lewatMTng),
                    'janjang_kosong' => $csfxr1 == 0 ? '-' :  $emptyx,
                    'persen_kosong' => $csfxr1 == 0 ? '-' :  $skor_jjgKosong,
                    'skor_kosong' => $csfxr1 == 0 ? '-' : sidak_jjgKosong($skor_jjgKosong),
                    'vcut' => $csfxr1 == 0 ? '-' :  $vcutx,
                    'vcut_persen' => $csfxr1 == 0 ? '-' :  $skor_vcut,
                    'vcut_skor' => $csfxr1 == 0 ? '-' : sidak_tangkaiP($skor_vcut),
                    'abnormal' => $csfxr1 == 0 ? '-' :  $abrx,
                    'rat_dmg' => $csfxr1 == 0 ? '-' :  $rdx,
                    'karung' => $csfxr1 == 0 ? '-' :  $sum_krx,
                    'TPH' => $csfxr1 == 0 ? '-' :  $total_kr,
                    'persen_krg' => $csfxr1 == 0 ? '-' :  $per_kr,
                    'csfxr1' =>  $csfxr1,
                    'skor_kr' => $csfxr1 == 0 ? '-' :  sidak_PengBRD($per_kr),
                    'kategori' => $csfxr1 == 0 ? '-' :  sidak_akhir($allSkor),
                ];

                $jjg_samplew += $jjg_samplex;
                $tnpBRDw += $tnpBRDx;
                $krgBRDw += $krgBRDx;
                $abrw += $abrx;
                $overripew += $overripex;
                $emptyw += $emptyx;
                $vcutw += $vcutx;
                $rdw += $rdx;
                $dataBLokw += $dataBLokx;
                $sum_krw += $sum_krx;
                $csfxr2 += $csfxr1;
            }
            if ($sum_krw != 0) {
                $total_kr = round($sum_krw / $dataBLokw, 3);
            } else {
                $total_kr = 0;
            }
            $per_kr = round($total_kr * 100, 3);
            $skor_total = round(($jjg_samplew - $abrw != 0 ? (($tnpBRDw + $krgBRDw) / ($jjg_samplew - $abrw)) * 100 : 0), 3);

            $skor_jjgMSk = round(($jjg_samplew - $abrw != 0 ? (($jjg_samplew - ($tnpBRDw + $krgBRDw + $overripew + $emptyw + $abrw)) / ($jjg_samplew - $abrw)) * 100 : 0), 3);

            $skor_lewatMTng = round(($jjg_samplew - $abrw != 0 ? ($overripew / ($jjg_samplew - $abrw)) * 100 : 0), 3);

            $skor_jjgKosong = round(($jjg_samplew - $abrw != 0 ? ($emptyw / ($jjg_samplew - $abrw)) * 100 : 0), 3);

            $skor_vcut = round(($jjg_samplew != 0 ? ($vcutw / $jjg_samplew) * 100 : 0), 3);

            $allSkor = sidak_brdTotal($skor_total) +  sidak_matangSKOR($skor_jjgMSk) +  sidak_lwtMatang($skor_lewatMTng) + sidak_jjgKosong($skor_jjgKosong) + sidak_tangkaiP($skor_vcut) + sidak_PengBRD($per_kr);



            switch ($key) {
                case 1:
                    $mutu_bhWil[$key]['est'] = 'WIl-I';
                    $wil = 'WIL-I';
                    break;
                case 2:
                    $mutu_bhWil[$key]['est'] = 'WIl-II';
                    $wil = 'WIL-II';
                    break;
                case 3:
                    $mutu_bhWil[$key]['est'] = 'WIl-III';
                    $wil = 'WIL-III';
                    break;
                case 4:
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
            $sidak_buah[$key]['WIL']['WIL'] = [
                'Jumlah_janjang' => $csfxr2 == 0 ? '-' : $jjg_samplew,
                'blok' => $csfxr2 == 0 ? '-' : $dataBLokw,
                'est' => 'WIL-' . $key,
                'afd' => 'WIL',
                'nama_asisten' => $nama_em,
                'tnp_brd'  => $csfxr2 == 0 ? '-' : $tnpBRDw,
                'krg_brd'  => $csfxr2 == 0 ? '-' : $krgBRDw,
                'persenTNP_brd'  => $csfxr2 == 0 ? '-' : round(($jjg_samplew - $abrw != 0 ? ($tnpBRDw / ($jjg_samplew - $abrw)) * 100 : 0), 3),
                'persenKRG_brd'  => $csfxr2 == 0 ? '-' : round(($jjg_samplew - $abrw != 0 ? ($krgBRDw / ($jjg_samplew - $abrw)) * 100 : 0), 3),
                'abnormal_persen'  => $csfxr2 == 0 ? '-' : round(($jjg_samplew != 0 ? ($abrw / $jjg_samplew) * 100 : 0), 3),
                'rd_persen'  => $csfxr2 == 0 ? '-' : round(($jjg_samplew != 0 ? ($rdw / $jjg_samplew) * 100 : 0), 3),
                'total_jjg'  => $csfxr2 == 0 ? '-' : $tnpBRDw + $krgBRDw,
                'persen_totalJjg'  => $csfxr2 == 0 ? '-' : $skor_total,
                'skor_total'  => $csfxr2 == 0 ? '-' : sidak_brdTotal($skor_total),
                'jjg_matang'  => $csfxr2 == 0 ? '-' : $jjg_samplew - ($tnpBRDw + $krgBRDw + $overripew + $emptyw + $abrw),
                'persen_jjgMtang'  => $csfxr2 == 0 ? '-' : $skor_jjgMSk,
                'skor_jjgMatang'  => $csfxr2 == 0 ? '-' : sidak_matangSKOR($skor_jjgMSk),
                'lewat_matang'  => $csfxr2 == 0 ? '-' : $overripew,
                'persen_lwtMtng'  => $csfxr2 == 0 ? '-' :  $skor_lewatMTng,
                'skor_lewatMTng'  => $csfxr2 == 0 ? '-' : sidak_lwtMatang($skor_lewatMTng),
                'janjang_kosong'  => $csfxr2 == 0 ? '-' : $emptyw,
                'persen_kosong'  => $csfxr2 == 0 ? '-' : $skor_jjgKosong,
                'skor_kosong'  => $csfxr2 == 0 ? '-' : sidak_jjgKosong($skor_jjgKosong),
                'vcut'  => $csfxr2 == 0 ? '-' : $vcutw,
                'vcut_persen'  => $csfxr2 == 0 ? '-' : $skor_vcut,
                'vcut_skor'  => $csfxr2 == 0 ? '-' : sidak_tangkaiP($skor_vcut),
                'abnormal'  => $csfxr2 == 0 ? '-' : $abrw,
                'rat_dmg'  => $csfxr2 == 0 ? '-' : $rdw,
                'karung'  => $csfxr2 == 0 ? '-' : $sum_krw,
                'TPH'  => $csfxr2 == 0 ? '-' : $total_kr,
                'persen_krg'  => $csfxr2 == 0 ? '-' : $per_kr,
                'skor_kr'  => $csfxr2 == 0 ? '-' : sidak_PengBRD($per_kr),
                'All_skor'  => $csfxr2 == 0 ? '-' : $allSkor,
                'kategori'  => $csfxr2 == 0 ? '-' : sidak_akhir($allSkor),
            ];
            $jjg_sampleEST += $jjg_samplew;
            $blokEST += $dataBLokw;
            $tnpBRDEST +=    $tnpBRDw;
            $krgBRDEST +=    $krgBRDw;
            $abrEST +=    $abrw;
            $overripeEST +=    $overripew;
            $emptyEST +=    $emptyw;
            $vcutEST +=    $vcutw;
            $rdEST +=    $rdw;
            $sum_krEST +=    $sum_krw;
        }
        if ($sum_krEST != 0) {
            $total_krEST = round($sum_krEST / $blokEST, 3);
        } else {
            $total_krEST = 0;
        }
        $per_krEST = round($total_krEST * 100, 3);
        $skor_totalEST = ($jjg_sampleEST - $abrEST) !== 0 ? round((($tnpBRDEST + $krgBRDEST) / ($jjg_sampleEST - $abrEST)) * 100, 3) : 0;
        $skot_jjgmskEST = round(($jjg_sampleEST - $abrEST != 0 ? (($jjg_sampleEST - ($tnpBRDEST + $krgBRDEST + $overripeEST + $emptyEST + $abrEST)) / ($jjg_sampleEST - $abrEST)) * 100 : 0), 3);

        $skor_lewatmatangEST = ($jjg_sampleEST - $abrEST) !== 0 ? round(($overripeEST / ($jjg_sampleEST - $abrEST)) * 100, 3) : 0;
        $skor_jjgKosongEST = ($jjg_sampleEST - $abrEST) !== 0 ? round(($emptyEST / ($jjg_sampleEST - $abrEST)) * 100, 3) : 0;
        $skor_vcutEST = $jjg_sampleEST !== 0 ? round(($vcutEST / $jjg_sampleEST) * 100, 3) : 0;

        $allSkorEST = sidak_brdTotal($skor_totalEST) +  sidak_matangSKOR($skot_jjgmskEST) +  sidak_lwtMatang($skor_lewatmatangEST) + sidak_jjgKosong($skor_jjgKosongEST) + sidak_tangkaiP($skor_vcutEST) + sidak_PengBRD($per_krEST);

        $sidak_buah['Reg']['Reg']['Reg'] = [
            'Jumlah_janjang' => $jjg_sampleEST,
            "blok" => '-',
            'afd' => 'Reg',
            "est" => 'Regional-' . $regional,
            "nama_asisten" => "Achmad Kursani",
            'tnp_brd' => $tnpBRDEST,
            'krg_brd' => $krgBRDEST,
            'persenTNP_brd' => ($jjg_sampleEST - $abrEST) !== 0 ? round(($krgBRDEST / ($jjg_sampleEST - $abrEST)) * 100, 3) : 0,
            'persenKRG_brd' => ($jjg_sampleEST - $abrEST) !== 0 ? round(($krgBRDEST / ($jjg_sampleEST - $abrEST)) * 100, 3) : 0,
            'abnormal_persen' => $jjg_sampleEST !== 0 ? round(($abrEST / $jjg_sampleEST) * 100, 3) : 0,
            'total_jjg' => $tnpBRDEST + $krgBRDEST,
            'persen_totalJjg' => $skor_totalEST,
            'skor_total' => sidak_brdTotal($skor_totalEST),
            'jjg_matang' => $skot_jjgmskEST,
            'persen_jjgMtang' => $skot_jjgmskEST,
            'skor_jjgMatang' => sidak_matangSKOR($skot_jjgmskEST),
            'lewat_matang' => $overripeEST,
            'persen_lwtMtng' => $skor_lewatmatangEST,
            'skor_lewatMTng' => sidak_lwtMatang($skor_lewatmatangEST),
            'janjang_kosong' => $emptyEST,
            'persen_kosong' => $skor_jjgKosongEST,
            'skor_kosong' => sidak_jjgKosong($skor_jjgKosongEST),
            'vcut' => $vcutEST,
            'vcut_persen' => $skor_vcutEST,
            'vcut_skor' => sidak_tangkaiP($skor_vcutEST),
            'abnormal' => $abrEST,
            'rat_dmg' => $rdEST,
            'rd_persen' => $jjg_sampleEST !== 0 ? round(($rdEST / $jjg_sampleEST) * 100, 3) : 0,
            'karung' => $sum_krEST,
            'TPH' => $total_krEST,
            'persen_krg' => $per_krEST,
            'skor_kr' => sidak_PengBRD($per_krEST),
            'All_skor' => $allSkorEST,
            'kategori' => sidak_akhir($allSkorEST),
        ];

        // dd($sidak_buah);

        if ($regional == 1) {

            $defaultmua = array();

            foreach ($muaest as $key2 => $value2) {
                foreach ($queryAfd as $key3 => $value3) {
                    if ($value2['est'] == $value3['est']) {
                        $defaultmua[$value2['est']][$value3['est']] = 0;
                    }
                }
            }
            foreach ($defaultmua as $estateKey => $afdelingArray) {
                foreach ($afdelingArray as $afdelingKey => $afdelingValue) {
                    if (isset($databulananBuah[$estateKey]) && isset($databulananBuah[$estateKey][$afdelingKey])) {
                        $defaultmua[$estateKey][$afdelingKey] = $databulananBuah[$estateKey][$afdelingKey];
                    }
                }
            }

            $sidak_buah_mua = array();
            // dd($defaultmua);
            $jjg_samplexy = 0;
            $tnpBRDxy = 0;
            $krgBRDxy = 0;
            $abrxy = 0;
            $overripexy = 0;
            $emptyxy = 0;
            $vcutxy = 0;
            $rdxy = 0;
            $dataBLokxy = 0;
            $sum_krxy = 0;
            $csrmsy = 0;
            foreach ($defaultmua as $key => $value) {
                $jjg_samplex = 0;
                $tnpBRDx = 0;
                $krgBRDx = 0;
                $abrx = 0;
                $overripex = 0;
                $emptyx = 0;
                $vcutx = 0;
                $rdx = 0;
                $dataBLokx = 0;
                $sum_krx = 0;
                $csrms = 0;
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
                        $newblok = 0;
                        $csfxr = count($value1);
                        foreach ($value1 as $key2 => $value2) {
                            $combination = $value2['blok'] . ' ' . $value2['estate'] . ' ' . $value2['afdeling'] . ' ' . $value2['tph_baris'];
                            if (!isset($combination_counts[$combination])) {
                                $combination_counts[$combination] = 0;
                            }
                            $newblok = count($value1);
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
                        // $dataBLok = count($combination_counts);
                        $dataBLok = $newblok;
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

                        $sidak_buah_mua[$key][$key1]['Jumlah_janjang'] = $jjg_sample;
                        $sidak_buah_mua[$key][$key1]['blok'] = $dataBLok;
                        $sidak_buah_mua[$key][$key1]['est'] = $key;
                        $sidak_buah_mua[$key][$key1]['afd'] = $key1;
                        $sidak_buah_mua[$key][$key1]['nama_staff'] = '-';
                        $sidak_buah_mua[$key][$key1]['tnp_brd'] = $tnpBRD;
                        $sidak_buah_mua[$key][$key1]['krg_brd'] = $krgBRD;
                        $sidak_buah_mua[$key][$key1]['persenTNP_brd'] = round(($tnpBRD / ($jjg_sample - $abr)) * 100, 2);
                        $sidak_buah_mua[$key][$key1]['persenKRG_brd'] = round(($krgBRD / ($jjg_sample - $abr)) * 100, 2);
                        $sidak_buah_mua[$key][$key1]['total_jjg'] = $tnpBRD + $krgBRD;
                        $sidak_buah_mua[$key][$key1]['persen_totalJjg'] = $skor_total;
                        $sidak_buah_mua[$key][$key1]['skor_total'] = sidak_brdTotal($skor_total);
                        $sidak_buah_mua[$key][$key1]['jjg_matang'] = $jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr);
                        $sidak_buah_mua[$key][$key1]['persen_jjgMtang'] = $skor_jjgMSk;
                        $sidak_buah_mua[$key][$key1]['skor_jjgMatang'] = sidak_matangSKOR($skor_jjgMSk);
                        $sidak_buah_mua[$key][$key1]['lewat_matang'] = $overripe;
                        $sidak_buah_mua[$key][$key1]['persen_lwtMtng'] =  $skor_lewatMTng;
                        $sidak_buah_mua[$key][$key1]['skor_lewatMTng'] = sidak_lwtMatang($skor_lewatMTng);
                        $sidak_buah_mua[$key][$key1]['janjang_kosong'] = $empty;
                        $sidak_buah_mua[$key][$key1]['persen_kosong'] = $skor_jjgKosong;
                        $sidak_buah_mua[$key][$key1]['skor_kosong'] = sidak_jjgKosong($skor_jjgKosong);
                        $sidak_buah_mua[$key][$key1]['vcut'] = $vcut;
                        $sidak_buah_mua[$key][$key1]['karung'] = $sum_kr;
                        $sidak_buah_mua[$key][$key1]['vcut_persen'] = $skor_vcut;
                        $sidak_buah_mua[$key][$key1]['vcut_skor'] = sidak_tangkaiP($skor_vcut);
                        $sidak_buah_mua[$key][$key1]['abnormal'] = $abr;
                        $sidak_buah_mua[$key][$key1]['abnormal_persen'] = round(($abr / $jjg_sample) * 100, 2);
                        $sidak_buah_mua[$key][$key1]['rat_dmg'] = $rd;
                        $sidak_buah_mua[$key][$key1]['rd_persen'] = round(($rd / $jjg_sample) * 100, 2);
                        $sidak_buah_mua[$key][$key1]['TPH'] = $total_kr;
                        $sidak_buah_mua[$key][$key1]['persen_krg'] = $per_kr;
                        $sidak_buah_mua[$key][$key1]['skor_kr'] = sidak_PengBRD($per_kr);
                        $sidak_buah_mua[$key][$key1]['All_skor'] = $allSkor;
                        $sidak_buah_mua[$key][$key1]['csfxr'] = $csfxr;
                        $sidak_buah_mua[$key][$key1]['kategori'] = sidak_akhir($allSkor);

                        foreach ($queryAsisten as $ast => $asisten) {
                            if ($key === $asisten['est'] && $key1 === $asisten['afd']) {
                                $sidak_buah_mua[$key][$key1]['nama_asisten'] = $asisten['nama'];
                            }
                        }
                        $jjg_samplex += $jjg_sample;
                        $tnpBRDx += $tnpBRD;
                        $krgBRDx += $krgBRD;
                        $abrx += $abr;
                        $overripex += $overripe;
                        $emptyx += $empty;
                        $vcutx += $vcut;

                        $rdx += $rd;

                        $dataBLokx += $newblok;
                        $sum_krx += $sum_kr;
                        $csrms += $csfxr;
                    } else {

                        $sidak_buah_mua[$key][$key1]['Jumlah_janjang'] = 0;
                        $sidak_buah_mua[$key][$key1]['blok'] = 0;
                        $sidak_buah_mua[$key][$key1]['est'] = $key;
                        $sidak_buah_mua[$key][$key1]['afd'] = $key1;
                        $sidak_buah_mua[$key][$key1]['nama_staff'] = '-';
                        $sidak_buah_mua[$key][$key1]['tnp_brd'] = 0;
                        $sidak_buah_mua[$key][$key1]['krg_brd'] = 0;
                        $sidak_buah_mua[$key][$key1]['persenTNP_brd'] = 0;
                        $sidak_buah_mua[$key][$key1]['persenKRG_brd'] = 0;
                        $sidak_buah_mua[$key][$key1]['total_jjg'] = 0;
                        $sidak_buah_mua[$key][$key1]['persen_totalJjg'] = 0;
                        $sidak_buah_mua[$key][$key1]['skor_total'] = 0;
                        $sidak_buah_mua[$key][$key1]['jjg_matang'] = 0;
                        $sidak_buah_mua[$key][$key1]['persen_jjgMtang'] = 0;
                        $sidak_buah_mua[$key][$key1]['skor_jjgMatang'] = 0;
                        $sidak_buah_mua[$key][$key1]['lewat_matang'] = 0;
                        $sidak_buah_mua[$key][$key1]['persen_lwtMtng'] =  0;
                        $sidak_buah_mua[$key][$key1]['skor_lewatMTng'] = 0;
                        $sidak_buah_mua[$key][$key1]['janjang_kosong'] = 0;
                        $sidak_buah_mua[$key][$key1]['persen_kosong'] = 0;
                        $sidak_buah_mua[$key][$key1]['skor_kosong'] = 0;
                        $sidak_buah_mua[$key][$key1]['vcut'] = 0;
                        $sidak_buah_mua[$key][$key1]['karung'] = 0;
                        $sidak_buah_mua[$key][$key1]['vcut_persen'] = 0;
                        $sidak_buah_mua[$key][$key1]['vcut_skor'] = 0;
                        $sidak_buah_mua[$key][$key1]['abnormal'] = 0;
                        $sidak_buah_mua[$key][$key1]['abnormal_persen'] = 0;
                        $sidak_buah_mua[$key][$key1]['rat_dmg'] = 0;
                        $sidak_buah_mua[$key][$key1]['rd_persen'] = 0;
                        $sidak_buah_mua[$key][$key1]['TPH'] = 0;
                        $sidak_buah_mua[$key][$key1]['persen_krg'] = 0;
                        $sidak_buah_mua[$key][$key1]['skor_kr'] = 0;
                        $sidak_buah_mua[$key][$key1]['All_skor'] = 0;
                        $sidak_buah_mua[$key][$key1]['kategori'] = 0;
                        $sidak_buah_mua[$key][$key1]['csfxr'] = 0;
                        foreach ($queryAsisten as $ast => $asisten) {
                            if ($key === $asisten['est'] && $key1 === $asisten['afd']) {
                                $sidak_buah_mua[$key][$key1]['nama_asisten'] = $asisten['nama'];
                            }
                        }
                    }
                }
                if ($sum_krx != 0) {
                    $total_kr = round($sum_krx / $dataBLokx, 2);
                } else {
                    $total_kr = 0;
                }
                $per_kr = round($total_kr * 100, 2);
                $skor_total = round(($jjg_samplex - $abrx != 0 ? (($tnpBRDx + $krgBRDx) / ($jjg_samplex - $abrx)) * 100 : 0), 2);

                $skor_jjgMSk = round(($jjg_samplex - $abrx != 0 ? (($jjg_samplex - ($tnpBRDx + $krgBRDx + $overripex + $emptyx + $abrx)) / ($jjg_samplex - $abrx)) * 100 : 0), 2);

                $skor_lewatMTng = round(($jjg_samplex - $abrx != 0 ? ($overripex / ($jjg_samplex - $abrx)) * 100 : 0), 2);

                $skor_jjgKosong = round(($jjg_samplex - $abrx != 0 ? ($emptyx / ($jjg_samplex - $abrx)) * 100 : 0), 2);

                $skor_vcut = round(($jjg_samplex != 0 ? ($vcutx / $jjg_samplex) * 100 : 0), 2);

                $allSkor = sidak_brdTotal($skor_total) +  sidak_matangSKOR($skor_jjgMSk) +  sidak_lwtMatang($skor_lewatMTng) + sidak_jjgKosong($skor_jjgKosong) + sidak_tangkaiP($skor_vcut) + sidak_PengBRD($per_kr);

                $em = 'OA';

                $nama_em = '';

                // dd($key);
                foreach ($queryAsisten as $ast => $asisten) {
                    if ($key == $asisten['est'] && $em == $asisten['afd']) {
                        $nama_em = $asisten['nama'];
                    }
                }
                $jjg_mth = $tnpBRDx + $krgBRDx + $overripex + $emptyx;

                $skor_jjgMTh = ($jjg_samplex - $abrx != 0) ? round($jjg_mth / ($jjg_samplex - $abrx) * 100, 2) : 0;

                $sidak_buah_mua[$key]['jjg_mantah'] = $jjg_mth;
                $sidak_buah_mua[$key]['persen_jjgmentah'] = $skor_jjgMTh;

                if ($csrms == 0) {
                    $sidak_buah_mua[$key]['check_arr'] = 'kosong';
                    $sidak_buah_mua[$key]['All_skor'] = '-';
                } else {
                    $sidak_buah_mua[$key]['check_arr'] = 'ada';
                    $sidak_buah_mua[$key]['All_skor'] = $allSkor;
                }

                $sidak_buah_mua[$key]['Jumlah_janjang'] = $jjg_samplex;
                $sidak_buah_mua[$key]['csrms'] = $csrms;
                $sidak_buah_mua[$key]['blok'] = $dataBLokx;
                $sidak_buah_mua[$key]['EM'] = 'EM';
                $sidak_buah_mua[$key]['Nama_assist'] = $nama_em;
                $sidak_buah_mua[$key]['nama_staff'] = '-';
                $sidak_buah_mua[$key]['tnp_brd'] = $tnpBRDx;
                $sidak_buah_mua[$key]['krg_brd'] = $krgBRDx;
                $sidak_buah_mua[$key]['persenTNP_brd'] = round(($jjg_samplex - $abrx != 0 ? ($tnpBRDx / ($jjg_samplex - $abrx)) * 100 : 0), 2);
                $sidak_buah_mua[$key]['persenKRG_brd'] = round(($jjg_samplex - $abrx != 0 ? ($krgBRDx / ($jjg_samplex - $abrx)) * 100 : 0), 2);
                $sidak_buah_mua[$key]['abnormal_persen'] = round(($jjg_samplex != 0 ? ($abrx / $jjg_samplex) * 100 : 0), 2);
                $sidak_buah_mua[$key]['rd_persen'] = round(($jjg_samplex != 0 ? ($rdx / $jjg_samplex) * 100 : 0), 2);


                $sidak_buah_mua[$key]['total_jjg'] = $tnpBRDx + $krgBRDx;
                $sidak_buah_mua[$key]['persen_totalJjg'] = $skor_total;
                $sidak_buah_mua[$key]['skor_total'] = sidak_brdTotal($skor_total);
                $sidak_buah_mua[$key]['jjg_matang'] = $jjg_samplex - ($tnpBRDx + $krgBRDx + $overripex + $emptyx + $abrx);
                $sidak_buah_mua[$key]['persen_jjgMtang'] = $skor_jjgMSk;
                $sidak_buah_mua[$key]['skor_jjgMatang'] = sidak_matangSKOR($skor_jjgMSk);
                $sidak_buah_mua[$key]['lewat_matang'] = $overripex;
                $sidak_buah_mua[$key]['persen_lwtMtng'] =  $skor_lewatMTng;
                $sidak_buah_mua[$key]['skor_lewatMTng'] = sidak_lwtMatang($skor_lewatMTng);
                $sidak_buah_mua[$key]['janjang_kosong'] = $emptyx;
                $sidak_buah_mua[$key]['persen_kosong'] = $skor_jjgKosong;
                $sidak_buah_mua[$key]['skor_kosong'] = sidak_jjgKosong($skor_jjgKosong);
                $sidak_buah_mua[$key]['vcut'] = $vcutx;
                $sidak_buah_mua[$key]['vcut_persen'] = $skor_vcut;
                $sidak_buah_mua[$key]['vcut_skor'] = sidak_tangkaiP($skor_vcut);
                $sidak_buah_mua[$key]['abnormal'] = $abrx;

                $sidak_buah_mua[$key]['rat_dmg'] = $rdx;

                $sidak_buah_mua[$key]['karung'] = $sum_krx;
                $sidak_buah_mua[$key]['TPH'] = $total_kr;
                $sidak_buah_mua[$key]['persen_krg'] = $per_kr;
                $sidak_buah_mua[$key]['skor_kr'] = sidak_PengBRD($per_kr);
                // $sidak_buah_mua[$key]['All_skor'] = $allSkor;
                $sidak_buah_mua[$key]['kategori'] = sidak_akhir($allSkor);

                $jjg_samplexy += $jjg_samplex;
                $tnpBRDxy += $tnpBRDx;
                $krgBRDxy += $krgBRDx;
                $abrxy += $abrx;
                $overripexy += $overripex;
                $emptyxy += $emptyx;
                $vcutxy += $vcutx;
                $rdxy += $rdx;
                $dataBLokxy += $dataBLokx;
                $sum_krxy += $sum_krx;
                $csrmsy += $csrms;
            }
            if ($sum_krxy != 0) {
                $total_kr = round($sum_krxy / $dataBLokxy, 2);
            } else {
                $total_kr = 0;
            }
            $per_kr = round($total_kr * 100, 2);
            $skor_total = round(($jjg_samplexy - $abrxy != 0 ? (($tnpBRDxy + $krgBRDxy) / ($jjg_samplexy - $abrxy)) * 100 : 0), 2);

            $skor_jjgMSk = round(($jjg_samplexy - $abrxy != 0 ? (($jjg_samplexy - ($tnpBRDxy + $krgBRDxy + $overripexy + $emptyxy + $abrxy)) / ($jjg_samplexy - $abrxy)) * 100 : 0), 2);

            $skor_lewatMTng = round(($jjg_samplexy - $abrxy != 0 ? ($overripexy / ($jjg_samplexy - $abrxy)) * 100 : 0), 2);

            $skor_jjgKosong = round(($jjg_samplexy - $abrxy != 0 ? ($emptyxy / ($jjg_samplexy - $abrxy)) * 100 : 0), 2);

            $skor_vcut = round(($jjg_samplexy != 0 ? ($vcutxy / $jjg_samplexy) * 100 : 0), 2);

            $allSkor = sidak_brdTotal($skor_total) +  sidak_matangSKOR($skor_jjgMSk) +  sidak_lwtMatang($skor_lewatMTng) + sidak_jjgKosong($skor_jjgKosong) + sidak_tangkaiP($skor_vcut) + sidak_PengBRD($per_kr);

            $em = 'EM';

            $nama_em = '';

            // dd($key1);
            foreach ($queryAsisten as $ast => $asisten) {
                if ('PT.MUA' === $asisten['est'] && $em === $asisten['afd']) {
                    $nama_em = $asisten['nama'];
                }
            }
            $jjg_mthxy = $tnpBRDxy + $krgBRDxy + $overripexy + $emptyxy;

            $skor_jjgMTh = ($jjg_samplexy - $abrxy != 0) ? round($jjg_mth / ($jjg_samplexy - $abrxy) * 100, 2) : 0;
            if ($csrmsy == 0) {
                $check_arr = 'kosong';
                $All_skor = '-';
            } else {
                $check_arr = 'ada';
                $All_skor = $allSkor;
            };
            $sidak_buah_mua['PT.MUA'] = [
                'jjg_mantah' => $jjg_mthxy,
                'persen_jjgmentah' => $skor_jjgMTh,
                'check_arr' => $check_arr,
                'All_skor' => $All_skor,
                'Jumlah_janjang' => $jjg_samplexy,
                'csrms' => $csrmsy,
                'blok' => $dataBLokxy,
                'EM' => 'EM',
                'Nama_assist' => $nama_em,
                'nama_staff' => '-',
                'tnp_brd' => $tnpBRDxy,
                'krg_brd' => $krgBRDxy,
                'persenTNP_brd' => round(($jjg_samplexy - $abrxy != 0 ? ($tnpBRDxy / ($jjg_samplexy - $abrxy)) * 100 : 0), 2),
                'persenKRG_brd' => round(($jjg_samplexy - $abrxy != 0 ? ($krgBRDxy / ($jjg_samplexy - $abrxy)) * 100 : 0), 2),
                'abnormal_persen' => round(($jjg_samplexy != 0 ? ($abrxy / $jjg_samplexy) * 100 : 0), 2),
                'rd_persen' => round(($jjg_samplexy != 0 ? ($rdxy / $jjg_samplexy) * 100 : 0), 2),
                'total_jjg' => $tnpBRDxy + $krgBRDxy,
                'persen_totalJjg' => $skor_total,
                'skor_total' => sidak_brdTotal($skor_total),
                'jjg_matang' => $jjg_samplexy - ($tnpBRDxy + $krgBRDxy + $overripexy + $emptyxy + $abrxy),
                'persen_jjgMtang' => $skor_jjgMSk,
                'skor_jjgMatang' => sidak_matangSKOR($skor_jjgMSk),
                'lewat_matang' => $overripexy,
                'persen_lwtMtng' =>  $skor_lewatMTng,
                'skor_lewatMTng' => sidak_lwtMatang($skor_lewatMTng),
                'janjang_kosong' => $emptyxy,
                'persen_kosong' => $skor_jjgKosong,
                'skor_kosong' => sidak_jjgKosong($skor_jjgKosong),
                'vcut' => $vcutxy,
                'vcut_persen' => $skor_vcut,
                'vcut_skor' => sidak_tangkaiP($skor_vcut),
                'abnormal' => $abrxy,
                'rat_dmg' => $rdxy,
                'karung' => $sum_krxy,
                'TPH' => $total_kr,
                'persen_krg' => $per_kr,
                'skor_kr' => sidak_PengBRD($per_kr),
                'kategori' => sidak_akhir($allSkor),
            ];
        } else {
            $sidak_buah_mua = [];
        }
        // dd($sidak_buah_mua);

        $arrView = array();

        $arrView['sidak_buah_mua'] =  $sidak_buah_mua;
        $arrView['mutu_buah'] =  $sidak_buah;
        $arrView['regionaltab'] =  $regional;
        $arrView['reg'] =  $regional;
        $arrView['tanggal'] =  $date;

        // dd($sidak_buah);

        return view('sidakmutubuah.mutubuahdatapdf', ['data' => $sidak_buah]);
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

                $event->sheet->getStyle('E2')->applyFromArray($styleHeader2);
                $event->sheet->getStyle('D')->applyFromArray($styleHeader2);
                $event->sheet->getStyle('AG')->applyFromArray($styleHeader2);
                // $event->sheet->getStyle('A1:B1')->applyFromArray($styleHeader);
                // $event->sheet->getStyle('AE2:AG2')->applyFromArray($styleHeader);
                // $event->sheet->getStyle('BB2')->applyFromArray($styleHeader);
                // $event->sheet->getStyle('BC1:BD1')->applyFromArray($styleHeader);
                // $event->sheet->getStyle('W2:X2')->applyFromArray($styleHeader);
                // $event->sheet->getStyle('BD')->applyFromArray($styleHeader2);
            },
        ];
    }
}
