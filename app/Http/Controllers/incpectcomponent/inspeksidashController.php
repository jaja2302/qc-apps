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

        $result = rekap_qcinspeks_perbulan($regional, $bulan);
        // dd($result['data_wilayah']);

        $arr = array();

        $arr['rekap_per_afdeling'] = $result['data_afdeling'];
        $arr['rekap_per_estate'] = $result['data_estate'];
        $arr['rekap_per_wil'] = $result['data_wilayah'];
        $arr['rekap_per_reg'] = $result['datareg'];

        $chart_for_estate = [];
        foreach ($result['data_estate'] as $regional => $estates) {
            foreach ($estates as $estate_name => $estate_data) {
                if (isset($estate_data['estate'])) {
                    $chart_for_estate[$estate_name] = $estate_data['estate'];
                }
            }
        }

        $arr['chart_for_estate'] = $chart_for_estate;

        $chart_for_wilayah = [];
        foreach ($result['data_wilayah'] as $regional => $data) {
            if (isset($data['wilayah']['wil'])) {
                $wilayah_key = "WIL-" . $regional; // atau bisa menggunakan $data['wilayah']['wil']['est']
                $chart_for_wilayah[$wilayah_key] = $data['wilayah']['wil'];
            }
        }

        $arr['chart_for_wilayah'] = $chart_for_wilayah;

        // dd($chart_for_wilayah, $chart_for_estate);
        // dd($rekap_per_afdeling, $rekap_per_estate);
        // Return JSON response if needed
        return response()->json($arr);
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

        // dd($request->all());
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

        dd($chartBTT);

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
