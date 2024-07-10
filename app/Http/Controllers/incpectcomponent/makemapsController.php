<?php


namespace App\Http\Controllers\incpectcomponent;

use Illuminate\Http\Request;


use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use DateTime;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;

require_once(app_path('helpers.php'));

use Illuminate\Support\Facades\Storage;


class makemapsController extends Controller
{

    public function plotBlok(Request $request)
    {
        $est = $request->get('est');
        $regData = $request->get('regData');
        $date = $request->get('date');

        $regData = explode(',', $regData);

        $reg = $regData[0];

        $regional = DB::connection('mysql2')->table('wil')
            ->select('*')
            ->join('estate', 'estate.wil', '=', 'wil.id')
            ->where('estate.wil', $reg)
            ->pluck('regional');
        $regional = $regional[0];
        // dd($regData, $regional);

        $queryTrans = DB::connection('mysql2')->table("mutu_transport")
            ->select("mutu_transport.*", "estate.wil")
            ->join('estate', 'estate.est', '=', 'mutu_transport.estate')
            ->where('mutu_transport.estate', $est)
            ->whereYear('mutu_transport.datetime', $date)
            ->where('mutu_transport.afdeling', '!=', 'Pla')
            // ->where('mutu_transport.afd', 'OA')
            ->get();

        $DataEstate = $queryTrans->groupBy('blok');
        $DataEstate = json_decode($DataEstate, true);


        $queryAncak = DB::connection('mysql2')->table("mutu_ancak_new")
            ->select("mutu_ancak_new.*", "estate.wil", DB::raw('DATE_FORMAT(mutu_ancak_new.datetime, "%Y-%m-%d") as date'))
            ->join('estate', 'estate.est', '=', 'mutu_ancak_new.estate')
            ->where('mutu_ancak_new.estate', $est)
            ->whereYear('mutu_ancak_new.datetime', $date)
            ->orderBy('blok', 'desc')
            ->where('mutu_ancak_new.afdeling', '!=', 'Pla')
            // ->where('mutu_ancak_new.afd', 'OA')
            ->get();

        $DataMTAncak = $queryAncak->groupBy(['blok']);
        $DataMTAncak = json_decode($DataMTAncak, true);
        // dd($DataMTAncak);
        function normalizeBlock($block)
        {
            // Check for block identifiers like 'F006-CBI14'
            if (preg_match('/^[A-Z]+\d+-CBI\d+$/', $block)) {
                return substr($block, 0, strpos($block, 'CBI'));
            }
            // Check for block identifiers like 'O27012'
            elseif (preg_match('/^[A-Z]+\d+$/', $block)) {
                return substr($block, 0, -2);
            }
            // Return the original block if it doesn't match known patterns
            return $block;
        }

        // Step 3: Normalize the block identifiers
        $datamutuancak = [];
        foreach ($DataMTAncak as $block => $data) {
            // Normalize the block identifier
            $normalizedBlock = normalizeBlock($block);

            // Initialize the array for this normalized block if not exists
            if (!isset($datamutuancak[$normalizedBlock])) {
                $datamutuancak[$normalizedBlock] = [];
            }

            // Merge the data
            $datamutuancak[$normalizedBlock] = array_merge($datamutuancak[$normalizedBlock], $data);
        }
        $datamututrans = [];
        foreach ($DataEstate as $block => $data) {
            // Normalize the block identifier
            $normalizedBlock = normalizeBlock($block);

            // Initialize the array for this normalized block if not exists
            if (!isset($datamututrans[$normalizedBlock])) {
                $datamututrans[$normalizedBlock] = [];
            }

            // Merge the data
            $datamututrans[$normalizedBlock] = array_merge($datamututrans[$normalizedBlock], $data);
        }
        // dd($datamutuancak['R009']);
        // dd($datamututrans['D13'], $datamutuancak);

        // dd($DataMTAncak, $datamutuancak['O270']);
        // $newArray = array_filter($DataMTAncak, function ($key) {
        //     return in_array($key, ["O27017", "O27012"]);
        // }, ARRAY_FILTER_USE_KEY);

        // dd($newArray);
        // $testing = [];
        // foreach ($newArray as $key => $value) {
        //     $jgg = 0;
        //     foreach ($value as $key1 => $value1) {
        //         $jgg += $value1['jjg'];
        //     }
        //     $testing[$key]['jjg'] = $jgg;
        // }
        // dd($testing);

        $QueryEst = DB::connection('mysql2')
            ->table("estate")
            ->join('afdeling', 'afdeling.estate', 'estate.id')
            ->join('blok', 'blok.afdeling', '=', 'afdeling.id')
            ->select('blok.id', 'blok.nama', DB::raw('afdeling.nama as `afdeling`'), 'blok.lon', 'blok.lat')
            ->where('est', $est)
            // ->orderBy('lat', 'desc')
            ->get();


        $queryBlok = json_decode($QueryEst, true);
        $bloks_afd = array_reduce($queryBlok, function ($carry, $item) {
            $carry[$item['afdeling']][$item['nama']][] = $item;
            return $carry;
        }, []);

        $plotBlokAlls = [];
        foreach ($bloks_afd as $key => $coord) {
            foreach ($coord as $key2 => $value) {
                foreach ($value as $key3 => $value1) {
                    $plotBlokAlls[$key][] = [$value1['lat'], $value1['lon']];
                }
            }
        }
        // dd($plotBlokAlls);

        $dataAfdeling = $QueryEst->groupBy('afdeling', 'nama');
        $dataAfdeling = json_decode($dataAfdeling, true);
        $coordinates = [];

        foreach ($dataAfdeling as $key => $afdelingItems) {
            $coords = [];
            foreach ($afdelingItems as $item) {
                $coords[] = [
                    'lat' => $item['lat'],
                    'lon' => $item['lon'],
                ];
            }
            $coordinates[$key] = $coords;
        }

        // dd($coordinates);

        // dd($datamututrans['M180']);

        $dataSkor = array();
        foreach ($datamututrans as $key => $value) {
            $sum_bt = 0;
            $sum_Restan = 0;
            $tph_sample = 0;
            $listBlokPerAfd = array();
            foreach ($value as $key2 => $value2) {
                $listBlokPerAfd[] = $value2['estate'] . ' ' . $value2['afdeling'] . ' ' . $value2['blok'];
                $tph_sample = count($listBlokPerAfd);
                // $tph_sample = count($listBlokPerAfd);
                $sum_Restan += $value2['rst'];
                $sum_bt += $value2['bt'];
            }
            $skorTrans = skor_brd_tinggal(round($sum_bt / $tph_sample, 2)) + skor_buah_tinggal(round($sum_Restan / $tph_sample, 2));
            $dataSkor[$key][0]['skorTrans'] = $skorTrans;
            $dataSkor[$key][0]['tph_sample'] = $tph_sample;
            $dataSkor[$key][0]['sum_Restan'] = $sum_Restan;
            $dataSkor[$key][0]['sum_bt'] = $sum_bt;
            $dataSkor[$key][0]['latin'] = $value2['lat'] . ',' . $value2['lon'];
        }

        // dd($dataSkor['H58']);

        // dd($datamutuancak['R009']);

        // $testing = [];
        // $jgg = 0;
        // foreach ($datamutuancak['R009'] as $key => $value) {

        //     $jgg += $value['jjg'];
        // }
        // $testing['jjg'] = $jgg;
        // dd($testing);
        foreach ($datamutuancak as $key => $value) {
            $akp = 0;
            $skor_bTinggal = 0;
            $brdPerjjg = 0;
            $ttlSkorMA = 0;
            $ttlSkorMAess = 0;
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
            foreach ($value as $key2 => $value2) {
                if (!in_array($value2['estate'] . ' ' . $value2['afdeling'] . ' ' . $value2['blok'], $listBlokPerAfd)) {
                    if ($value2['sph'] != 0) {
                        $listBlokPerAfd[] = $value2['estate'] . ' ' . $value2['afdeling'] . ' ' . $value2['blok'];
                    }
                }

                $jml_blok = count($listBlokPerAfd);
                $totalPokok += $value2['sample'];
                $totalPanen +=  $value2['jjg'];
                $totalP_panen += $value2['brtp'];
                $totalK_panen += $value2['brtk'];
                $totalPTgl_panen += $value2['brtgl'];

                $totalbhts_panen += $value2['bhts'];
                $totalbhtm1_panen += $value2['bhtm1'];
                $totalbhtm2_panen += $value2['bhtm2'];
                $totalbhtm3_oanen += $value2['bhtm3'];
                $check_input = $value2['jenis_input'];
                $nilai_input = $value2['skor_akhir'];
                $totalpelepah_s += $value2['ps'];
            }
            if ($totalPokok != 0) {
                $akp = $totalPanen / $totalPokok * 100;
            } else {
                $akp = 0;
            }


            $skor_bTinggal = $totalP_panen + $totalK_panen + $totalPTgl_panen;

            if ($totalPanen != 0) {
                $brdPerjjg = $skor_bTinggal / $totalPanen;
            } else {
                $brdPerjjg = 0;
            }

            $sumBH = $totalbhts_panen +  $totalbhtm1_panen +  $totalbhtm2_panen +  $totalbhtm3_oanen;
            if ($sumBH != 0) {
                $sumPerBH = $sumBH / ($totalPanen + $sumBH) * 100;
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
                $dataSkor[$key][0]['check_datacak'] = 'ada';
            } else {
                $dataSkor[$key][0]['check_datacak'] = 'kosong';
            }

            // $ttlSkorMA = $skor_bh + $skor_brd + $skor_ps;
            $ttlSkorMA =  skor_buah_Ma($sumPerBH) + skor_brd_ma($brdPerjjg) + skor_palepah_ma($perPl);


            $dataSkor[$key][0]['skorAncak'] = $ttlSkorMA;
            $dataSkor[$key][0]['tot_brd'] = $brdPerjjg;
            $dataSkor[$key][0]['sumBH'] = $sumBH;
            $dataSkor[$key][0]['totalPanen'] = $totalPanen;
            $dataSkor[$key][0]['latin2'] = $value2['lat_awal'] . ',' . $value2['lon_awal'];
        }

        // dd($dataSkor['R009']);
        // dd($dataSkor['D13']);
        $dataSkorResult = array();
        $newData = '';
        // dd($regData);
        // dd($dataSkor, $est);
        foreach ($dataSkor as $key => $value) {
            foreach ($value as $key1 => $value1) {
                // dd($key);


                $skorTrans = check_array('skorTrans', $value1);
                $skorBuah = check_array('skorBuah', $value1);
                $skorAncak = check_array('skorAncak', $value1);



                if ($skorTrans != 0 || $skorAncak != 0) {
                    $skorTotal = $skorTrans + $skorAncak;
                    $skorAkhir = (int) round(($skorTotal * 100) / 65);

                    if ($skorAkhir == 100) {
                        $skorAkhir -= 1;
                    }
                } else {
                    $skorAkhir = 0;
                }

                if ($reg != 1) {
                    $skorAkhir -= 1;
                }


                // if ($skorTrans != 0 && $skorAncak != 0) {
                //     $skorAkhir = (int) round((($skorTrans + $skorAncak) * 100) / 65 - 1);
                // } elseif ($skorTrans != 0) {
                //     $skorAkhir = (int)round(($skorTrans * 100) / 65 - 1);
                // } elseif ($skorAncak != 0) {
                //     $skorAkhir = (int) round(($skorAncak * 100) / 65 - 1);
                // } else {
                //     $skorAkhir = 0;
                // }


                if ($skorTrans == 0 && $skorAncak == 0) {
                    $check = 'empty';
                } else {
                    $check = 'data';
                }

                if ($check == 'data') {
                    $skor_kategori_akhir_est = skor_kategori_akhir($skorAkhir);
                } else {
                    $skor_kategori_akhir_est = 'xxx';
                }



                $dataSkorResult[$key]['estate'] = $est;
                $dataSkorResult[$key]['skorTrans'] = $skorTrans;
                $dataSkorResult[$key]['skorBuah'] = $skorBuah;
                $dataSkorResult[$key]['skorAncak'] = $skorAncak;
                $dataSkorResult[$key]['blok'] = $key;
                $dataSkorResult[$key]['text'] = $skor_kategori_akhir_est[1];
                $dataSkorResult[$key]['skorAkhir'] = $skorAkhir;
                $dataSkorResult[$key]['check_data'] = $check;
                $dataSkorResult[$key]['latin'] = $value1['latin'] ?? $value1['latin2'];
            }
        }

        // dd($dataSkorResult);
        // dd($dataSkorResult['M180']);

        // dd($dataSkor['O27017']);

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


        // $values = array_values($blokEstateFix['BDE']);
        $uniqueCombinations = [];

        foreach ($blokLatLnEw as $value) {
            $key = $value['blok'] . '_' . $est . '_' . $value['latln']; // Initialize key before the inner loop

            $hasData = false; // Flag to track if there's data for the current latln

            foreach ($dataSkorResult as $marker) {
                if (isPointInPolygon($marker['latin'], $value['latln'])) {
                    $hasData = true; // Set the flag to true if there's data
                    // Check if the combination already exists
                    if (!isset($uniqueCombinations[$key])) {
                        $uniqueCombinations[$key] = true; // Mark the combination as encountered
                        $blokLatLn[] = [
                            'blok' => $value['blok'],
                            'blok_asli' => $marker['blok'] ?? '-',
                            'estate' => $est,
                            'latln' => $value['latinnew'],
                            'nilai' => $marker['skorAkhir'],
                            'afdeling' => $value['afd'],
                            'kategori' => $marker['text'],
                        ];
                    }
                }
            }

            // Check if there is no data for the current latln and add it to the result
            if (!$hasData) {
                if (!isset($uniqueCombinations[$key])) {
                    $uniqueCombinations[$key] = true; // Mark the combination as encountered
                    $blokLatLn[] = [
                        'blok' => $value['blok'],
                        'blok_asli' => '-',
                        'estate' => $est,
                        'latln' => $value['latinnew'],
                        'nilai' => 0,
                        'afdeling' => $value['afd'],
                        'kategori' => 'x',
                    ];
                }
            }
        }

        // dd($blokLatLn, $dataSkorResult);



        $dataLegend = array();
        $excellent = array();
        $good = array();
        $satis = array();
        $fair = array();
        $poor = array();
        $empty = array();
        $dataLegend = array();
        foreach ($blokLatLn as $key => $value) {
            $skor = $value['nilai'];
            $data = $value['kategori'];
            if ($data == 'EXCELLENT') {
                $excellent[] = $value['nilai'];
            } else if ($data == 'GOOD') {
                $good[] = $value['nilai'];
            } else if ($data == 'SATISFACTORY') {
                $satis[] = $value['nilai'];
            } else if ($data == 'FAIR') {
                $fair[] = $value['nilai'];
            } else if ($data == 'POOR') {
                $poor[] = $value['nilai'];
            } else if ($data == 'x') {
                $empty[] = $value['nilai'];
            }
        }

        $tot_exc = count($excellent);
        $tot_good = count($good);
        $tot_satis = count($satis);
        $tot_fair = count($fair);
        $tot_poor = count($poor);
        $tot_empty = count($empty);

        $totalSkor = $tot_exc + $tot_good + $tot_satis + $tot_fair + $tot_poor + $tot_empty;

        $dataLegend['excellent'] = $tot_exc;
        $dataLegend['good'] = $tot_good;
        $dataLegend['satis'] = $tot_satis;
        $dataLegend['fair'] = $tot_fair;
        $dataLegend['poor'] = $tot_poor;
        $dataLegend['empty'] = $tot_empty;
        $dataLegend['total'] = $totalSkor;
        $dataLegend['perExc'] = count_percent($tot_exc, $totalSkor);
        $dataLegend['perGood'] = count_percent($tot_good, $totalSkor);
        $dataLegend['perSatis'] = count_percent($tot_satis, $totalSkor);
        $dataLegend['perFair'] = count_percent($tot_fair, $totalSkor);
        $dataLegend['perPoor'] = count_percent($tot_poor, $totalSkor);
        $dataLegend['perEmpty'] = count_percent($tot_empty, $totalSkor);

        $highestValue = null;
        $estatesWithHighestNilai = [];
        $bloksWithHighestNilai = [];

        foreach ($blokLatLn as $value) {
            $nilai = $value['nilai'];
            $estate = $value['estate'];
            $blok = $value['blok'];

            if ($highestValue === null || $nilai > $highestValue) {
                $highestValue = $nilai;
                $estatesWithHighestNilai = [$estate];
                $bloksWithHighestNilai = [$blok];
            } elseif ($nilai === $highestValue) {
                $estatesWithHighestNilai[] = $estate;
                $bloksWithHighestNilai[] = $blok;
            }
        }

        $resultsHIgh = [
            'estate' => $estatesWithHighestNilai,
            'blok' => $bloksWithHighestNilai,
            'nilai' => $highestValue,
        ];

        $lowestValue = null;
        $estatesWithLowestNilai = [];
        $bloksWithLowestNilai = [];

        foreach ($blokLatLn as $value) {
            $nilai = $value['nilai'];
            $estate = $value['estate'];
            $blok = $value['blok'];

            if ($lowestValue === null && $nilai !== 0) {
                $lowestValue = $nilai;
                $estatesWithLowestNilai = [$estate];
                $bloksWithLowestNilai = [$blok];
            } elseif ($nilai < $lowestValue && $nilai !== 0) {
                $lowestValue = $nilai;
                $estatesWithLowestNilai = [$estate];
                $bloksWithLowestNilai = [$blok];
            } elseif ($nilai === $lowestValue && $nilai !== 0) {
                $estatesWithLowestNilai[] = $estate;
                $bloksWithLowestNilai[] = $blok;
            }
        }

        $resultsLow = [
            'estate' => $estatesWithLowestNilai,
            'blok' => $bloksWithLowestNilai,
            'nilai' => $lowestValue,
        ];

        // dd($dataSkorResult);
        // dd($blokLatLn, $dataSkorResult);


        $plot['blok'] = $blokLatLn;
        $plot['legend'] = $dataLegend;
        $plot['lowest'] = $resultsLow;
        $plot['highest'] = $resultsHIgh;
        $plot['afdeling'] = $plotBlokAlls;


        // dd($plotBlokAll);
        echo json_encode($plot);
    }

    public function getMapsdetail(Request $request)
    {
        $est = $request->input('est');
        $afd = $request->input('afd');
        $date = $request->input('Tanggal');

        // dd($est, $afd, $date);
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

        $id = $queryAfd[0]['id'];

        $queryBlokMA = DB::connection('mysql2')->table('mutu_ancak_new')
            ->select('mutu_ancak_new.*', 'mutu_ancak_new.blok as nama_blok')
            ->whereDate('mutu_ancak_new.datetime', $date)
            ->where('estate', $est)
            ->where('afdeling', $afd)
            ->orderBy('mutu_ancak_new.datetime', 'desc')
            ->groupBy('nama_blok')
            ->pluck('blok');
        $queryBlokMA = json_decode($queryBlokMA, true);


        $queryBlokMB = DB::connection('mysql2')->table('mutu_buah')
            ->select('mutu_buah.*', 'mutu_buah.blok as nama_blok')
            ->whereDate('mutu_buah.datetime', $date)
            ->where('estate', $est)
            ->where('afdeling', $afd)
            ->orderBy('mutu_buah.datetime', 'desc')
            ->groupBy('nama_blok')
            ->pluck('blok');

        $queryBlokMB = json_decode($queryBlokMB, true);
        $queryBlokMT = DB::connection('mysql2')->table('mutu_transport')
            ->select('mutu_transport.*', 'mutu_transport.blok as nama_blok')
            ->whereDate('mutu_transport.datetime', $date)
            ->where('estate', $est)
            ->where('afdeling', $afd)
            ->orderBy('mutu_transport.datetime', 'desc')
            ->groupBy('nama_blok')
            ->pluck('blok');

        $queryBlokMT = json_decode($queryBlokMT, true);

        //merge all blok untuk mendapatkan semua blok ma mb mt visit hari tersebut
        $allBlokSidakRaw = array_merge($queryBlokMA, $queryBlokMB, $queryBlokMT);

        //array unique mengambil eliminasi blok yg sama
        $allBlokSidakRaw = array_unique($allBlokSidakRaw);

        //sisipkan 0 setelah digit pertama


        $blokSidak = array();
        foreach ($allBlokSidakRaw as $value) {
            $length = strlen($value);
            $blokSidak[] = substr($value, 0, $length - 3);
            $modifiedStr2  = substr($value, 0, $length - 2);
            $blokSidak[] = substr($value, 0, $length - 2);
            $blokSidak[] =  substr_replace($modifiedStr2, "0", 1, 0);
        }

        // dd($allBlokRaw, $allBlok);
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

        $bloks_afds = [];
        foreach ($bloks_afd as $blok => $coords) {
            foreach ($coords as $coord) {
                $bloks_afds[] = [
                    'blok' => $blok,
                    'lat' => $coord['lat'],
                    'lon' => $coord['lon'],
                ];
            }
        }

        $plotBlokAll = [];
        foreach ($bloks_afd as $key => $coord) {
            foreach ($coord as $key2 => $value) {
                $plotBlokAll[$key][] = [$value['lat'], $value['lon']];
            }
        }

        // Sort the coordinates in ascending order based on the first value


        //  dd($plotBlokAll);

        $queryTrans = DB::connection('mysql2')->table("mutu_transport")
            ->select("mutu_transport.*", "estate.wil")
            ->join('estate', 'estate.est', '=', 'mutu_transport.estate')
            ->where('mutu_transport.estate', $est)
            ->where('mutu_transport.afdeling', $afd)
            ->where('datetime', 'like', '%' . $date . '%')
            ->where('mutu_transport.afdeling', '!=', 'Pla')
            ->get();
        $queryTrans = json_decode($queryTrans, true);

        // dd($queryTrans);

        $queryBuah = DB::connection('mysql2')->table("mutu_buah")
            ->select("mutu_buah.*", "estate.wil")
            ->join('estate', 'estate.est', '=', 'mutu_buah.estate')
            ->where('mutu_buah.estate', $est)
            ->where('mutu_buah.afdeling', $afd)
            ->where('datetime', 'like', '%' . $date . '%')
            ->where('mutu_buah.afdeling', '!=', 'Pla')
            ->get();
        $queryBuah = json_decode($queryBuah, true);

        $groupedTrans = array_reduce($queryTrans, function ($carry, $item) {
            $carry[$item['blok']][] = $item;
            return $carry;
        }, []);

        $groupedBuah = array_reduce($queryBuah, function ($carry, $item) {
            $carry[$item['blok']][] = $item;
            return $carry;
        }, []);

        // dd($groupedBuah);
        $buah_plot = [];

        foreach ($groupedBuah as $blok => $coords) {
            foreach ($coords as $coord) {
                $datetime = $coord['datetime'];
                $time = date('H:i:s', strtotime($datetime));

                $maps = $coord['app_version'];
                if (strpos($maps, ';GA') !== false) {
                    $maps = 'GPS Akurat';
                } elseif (strpos($maps, ';GL') !== false) {
                    $maps = 'GPS Liar';
                }

                $buah_plot[$blok][] = [
                    'blok' => $blok,
                    'lat' => $coord['lat'],
                    'lon' => $coord['lon'],
                    'foto_temuan' => $coord['foto_temuan'],
                    'komentar' => $coord['komentar'],
                    'tph_baris' => $coord['tph_baris'],
                    'status_panen' => $coord['status_panen'],
                    'jumlah_jjg' => $coord['jumlah_jjg'],
                    'bmt' => $coord['bmt'],
                    'bmk' => $coord['bmk'],
                    'overripe' => $coord['overripe'],
                    'empty_bunch' => $coord['empty_bunch'],
                    'abnormal' => $coord['abnormal'],
                    'vcut' => $coord['vcut'],
                    'alas_br' => $coord['alas_br'],
                    'maps' => $maps,
                    'time' => $time,
                ];
            }
        }




        // dd($buah_plot);
        $trans_plot = [];
        foreach ($groupedTrans as $blok => $coords) {
            foreach ($coords as $coord) {
                $datetime = $coord['datetime'];
                $time = date('H:i:s', strtotime($datetime));
                $maps = $coord['app_version'];
                if (strpos($maps, ';GA') !== false) {
                    $maps = 'GPS Akurat';
                } elseif (strpos($maps, ';GL') !== false) {
                    $maps = 'GPS Liar';
                }

                $trans_plot[$blok][] = [
                    'blok' => $blok,
                    'lat' => $coord['lat'],
                    'lon' => $coord['lon'],
                    'foto_temuan' => $coord['foto_temuan'],
                    'foto_fu' => $coord['foto_fu'],
                    'komentar' => $coord['komentar'],
                    'status_panen' => $coord['status_panen'],
                    'luas_blok' => $coord['luas_blok'],
                    'bt' => $coord['bt'],
                    'Rst' => $coord['rst'],
                    'maps' => $maps,
                    'time' => $time,
                ];
            }
        }



        $queryancak = DB::connection('mysql2')->table("mutu_ancak_new")
            ->select("mutu_ancak_new.*", "estate.wil")
            ->join('estate', 'estate.est', '=', 'mutu_ancak_new.estate')
            ->where('mutu_ancak_new.estate', $est)
            ->where('mutu_ancak_new.afdeling', $afd)
            ->where('datetime', 'like', '%' . $date . '%')
            ->where('mutu_ancak_new.afdeling', '!=', 'Pla')
            ->get();
        $queryancak = json_decode($queryancak, true);

        $groupedAncak = array_reduce($queryancak, function ($carry, $item) {
            $carry[$item['blok']][] = $item;
            return $carry;
        }, []);

        $plotLine = array();
        foreach ($queryancak as $key => $value) {
            $plotLine[] =  '[' . $value['lat_awal'] . ',' . $value['lon_awal'] . '],[' . $value['lat_akhir'] . ',' . $value['lon_akhir'] . ']';
        }

        // dd($plotLine);   
        $queryancakFL = DB::connection('mysql2')->table("follow_up_ma")
            ->select("follow_up_ma.*", "estate.wil")
            ->join('estate', 'estate.est', '=', 'follow_up_ma.estate')
            ->where('follow_up_ma.estate', $est)
            ->where('follow_up_ma.afdeling', $afd)
            ->where('waktu_temuan', 'like', '%' . $date . '%')
            ->where('follow_up_ma.afdeling', '!=', 'Pla')
            ->get();
        $queryancakFL = json_decode($queryancakFL, true);

        $groupedAncakFL = array_reduce($queryancakFL, function ($carry, $item) {
            $carry[$item['blok']][] = $item;
            return $carry;
        }, []);
        // dd($groupedAncak['T01505'],$groupedAncakFL['T01505']);
        // dd($ancak_plot);
        $ancak_fa = [];
        foreach ($groupedAncakFL as $blok => $coords) {
            foreach ($coords as $coord) {
                // dd($coord);
                $datetime = $coord['waktu_temuan'];
                $time = date('H:i:s', strtotime($datetime));
                $ancak_fa[] = [
                    'blok' => $blok,
                    'estate' => $coord['estate'],
                    'afdeling' => $coord['afdeling'],
                    'br1' => $coord['br1'],
                    'br2' => $coord['br2'],
                    'jalur_masuk' => $coord['jalur_masuk'],
                    'foto_temuan1' => $coord['foto_temuan1'],
                    'foto_temuan2' => $coord['foto_temuan2'],
                    'foto_fu1' => $coord['foto_fu1'],
                    'foto_fu2' => $coord['foto_fu2'],
                    'komentar' => $coord['komentar'],
                    'lat' => $coord['lat'],
                    'lon' => $coord['lon'],
                    'time' => $time,
                ];
            }
        }

        $groupedArray = [];

        foreach ($ancak_fa as $item) {
            $blok = $item['blok'];
            if (!isset($groupedArray[$blok])) {
                $groupedArray[$blok] = [];
            }
            $groupedArray[$blok][] = $item;
        }
        // dd($groupedArray,$ancak_fa);


        $ancak_plot = [];

        foreach ($groupedAncak as $blok => $coords) {
            foreach ($coords as $coord) {
                $matchingAncakFa = [];

                foreach ($ancak_fa as $key => $value) {
                    if ($coord['blok'] == $value['blok'] && $coord['br1'] == $value['br1'] && $coord['br2'] == $value['br2'] && $coord['jalur_masuk'] == $value['jalur_masuk']) {
                        $matchingAncakFa[] = $value;
                    }
                }
                // $maps = $coord['app_version'];
                // if (strpos($maps, ';GA') !== false) {
                //     $maps = 'GPS Akurat';
                // } elseif (strpos($maps, ';GL') !== false) {
                //     $maps = 'GPS Liar';
                // }

                $vers = $coord['app_version'];
                $parts = explode(';', $vers);

                $defaultparts = '{"awal":"GO","akhir":"GO"}';
                $version = $parts[3] ?? $defaultparts;

                if (strpos($version, 'awal')) {
                    if (strpos($version, 'awal":"GL') !== false && strpos($version, 'akhir":"GA') !== false) {
                        $maps = 'GPS Awal Liar : GPS Akhir Akurat';
                    } else  if (strpos($version, 'awal":"GL') !== false && strpos($version, 'akhir":"GL') !== false) {
                        $maps = 'GPS Awal Liar : GPS Akhir Liar';
                    } else  if (strpos($version, 'awal":"GA') !== false && strpos($version, 'akhir":"GL"') !== false) {
                        $maps = 'GPS Awal Akurat : GPS Akhir Liar';
                    } else  if (strpos($version, 'awal":"GA') !== false && strpos($version, 'akhir":"GA"') !== false) {
                        $maps = 'GPS Awal Akurat : GPS Akhir Akurat';
                    } else if (strpos($version, 'awal":"GA') !== false && strpos($version, 'akhir":"G') !== false) {
                        $maps = 'GPS Awal Akurat : GPS Akhir Uknown';
                    } else if (strpos($version, 'awal":"GL') !== false && strpos($version, 'akhir":"G') !== false) {
                        $maps = 'GPS Awal Akurat : GPS Akhir Uknown';
                    } else if (strpos($version, 'awal":"GO') !== false && strpos($version, 'akhir":"GO') !== false) {
                        $maps = 'GPS Awal Uknown : GPS Akhir Uknown';
                    } else {
                        $maps = 'GPS Uknown';
                    }
                } else {
                    if (strpos($coord['app_version'], ';GA') !== false) {
                        $maps = 'GPS Akurat';
                    } elseif (strpos($coord['app_version'], ';GL') !== false) {
                        $maps = 'GPS Liar';
                    } else {
                        $maps = 'GPS Awal Uknown : GPS Akhir Uknown';
                    }
                }
                $ancak_fa_item = [
                    'blok' => $blok,
                    'estate' => $coord['estate'],
                    'afdeling' => $coord['afdeling'],
                    'br1' => $coord['br1'],
                    'br2' => $coord['br2'],
                    'jalur_masuk' => $coord['jalur_masuk'],
                    'ket' => 'Lokasi akhir',
                    'lat_' => $coord['lat_akhir'],
                    'lon_' => $coord['lon_akhir'],
                    'luas_blok' => $coord['luas_blok'],
                    'sph' => $coord['sph'],
                    'sample' => $coord['sample'],
                    'pokok_kuning' => $coord['pokok_kuning'],
                    'piringan_semak' => $coord['piringan_semak'],
                    'underpruning' => $coord['underpruning'],
                    'overpruning' => $coord['overpruning'],
                    'jjg' => $coord['jjg'],
                    'brtp' => $coord['brtp'],
                    'brtk' => $coord['brtk'],
                    'brtgl' => $coord['brtgl'],
                    'bhts' => $coord['bhts'],
                    'bhtm1' => $coord['bhtm1'],
                    'bhtm2' => $coord['bhtm2'],
                    'bhtm3' => $coord['bhtm3'],
                    'maps' => $maps,
                    'ps' => $coord['ps'],
                    'sp' => $coord['sp'],
                    'time' => date('H:i:s', strtotime($coord['datetime'])),
                ];

                if (!empty($matchingAncakFa)) {
                    $foto_temuan1 = [];
                    $foto_temuan2 = [];
                    $foto_fu1 = [];
                    $foto_fu2 = [];
                    $lat_ancak_fa = [];
                    $lon_ancak_fa = [];
                    $komentar = [];

                    foreach ($matchingAncakFa as $match) {
                        $foto_temuan1[] = 'foto_temuan1:' . (!empty($match['foto_temuan1']) ? $match['foto_temuan1'] : '') . ',foto_temuan2:' . (!empty($match['foto_temuan2']) ? $match['foto_temuan2'] : ' ') . ',foto_fu1:' . (!empty($match['foto_fu1']) ? $match['foto_fu1'] : ' ') . ',foto_fu2:' . (!empty($match['foto_fu2']) ? $match['foto_fu2'] : ' ') . ',lat:' . $match['lat'] . ',lon:' . $match['lon'] . ',komentar:' . $match['komentar'];

                        $foto_fu1[] = 'foto_fu1:' . (!empty($match['foto_fu1']) ? $match['foto_fu1'] : '') . ',foto_fu2:' . (!empty($match['foto_fu2']) ? $match['foto_fu2'] : ' ') . ',lat:' . $match['lat'] . ',lon:' . $match['lon'];
                    }


                    $ancak_fa_item['foto_temuan'] = $foto_temuan1;

                    // $ancak_fa_item['foto_fu'] = $foto_fu1;

                }



                $ancak_plot[] = $ancak_fa_item;
            }
        }





        // dd($trans_plot,$buah_plot,$ancak_plot); 

        return response()->json([
            'plot_line' => $plotLine,
            'coords' => $convertedCoords,
            'plot_blok_all' => $plotBlokAll,
            'trans_plot' => $trans_plot,
            'buah_plot' => $buah_plot,
            'ancak_plot' => $ancak_plot,
            'blok_sidak' => $blokSidakResult
        ]);
    }




    public function downloadMaptahun(Request $request)
    {
        $imgData = $request->input('imgData');
        $estData = $request->input('estData');
        $regData = $request->input('regData');
        $decodedImage = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imgData));

        // dd($estData);
        // Generate a unique filename for the image, e.g., using a timestamp
        $filename = 'img_map_' . time() . '.png';

        // Define the public directory path
        $publicMapPath = public_path('img_map');
        $publicResultPath = public_path('img_result');

        // Check if the "img_map" directory exists, if not, create it
        if (!file_exists($publicMapPath)) {
            mkdir($publicMapPath, 0755, true);
        }

        // Save the image to the "img_map" directory
        file_put_contents($publicMapPath . '/' . $filename, $decodedImage);

        $files = File::files($publicMapPath);

        foreach ($files as $file) {
            $imageInfo = getimagesize($file);

            if ($imageInfo !== false) {
                list($width, $height, $type) = $imageInfo;

                $originalImage = imagecreatefromstring(file_get_contents($file));

                $minX = $width;
                $minY = $height;
                $maxX = 0;
                $maxY = 0;

                for ($x = 0; $x < $width; $x++) {
                    for ($y = 0; $y < $height; $y++) {
                        $pixelColor = imagecolorat($originalImage, $x, $y);
                        $color = imagecolorsforindex($originalImage, $pixelColor);
                        if ($color['red'] !== 0 || $color['green'] !== 0 || $color['blue'] !== 0) {
                            $minX = min($minX, $x);
                            $minY = min($minY, $y);
                            $maxX = max($maxX, $x);
                            $maxY = max($maxY, $y);
                        }
                    }
                }
                $cropWidth = $maxX - $minX + 1;
                $cropHeight = $maxY - $minY + 1;
                $croppedImage = imagecrop($originalImage, ['x' => $minX, 'y' => $minY, 'width' => $cropWidth, 'height' => $cropHeight]);

                $outputFile = $publicResultPath . '/' . basename($file);
                imagejpeg($croppedImage, $outputFile);

                imagedestroy($originalImage);
                imagedestroy($croppedImage);

                // Delete the original image from "img_map"
                unlink($file);
            }
        }


        // Provide the public URL for the saved image in "img_result"
        $publicUrl = asset('img_result/' . $filename);

        return response()->json(['message' => 'Image saved successfully', 'filename' => $filename, 'est' => $estData]);
    }




    public function pdfPage($filename, $est)
    {
        $url = $filename;
        $estData = $est;

        $pdf = PDF::loadView('pdfimgInspeksi', compact('url', 'estData'));

        $customPaper = array(360, 360, 360, 360);
        $pdf->set_paper('A2', 'landscape');

        $filename = 'Data Map Inspeksi per Blok' . '.pdf';

        return $pdf->stream($filename);
    }
}
