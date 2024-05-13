<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateTime;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Carbon;

require_once(app_path('helpers.php'));

class emplacementsController extends Controller
{


    public function dashboard_perum(Request $request)
    {


        $bulan = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        // dd($bulan);
        $shortMonth = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'AVE', 'STATUS'];

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
        $optionREg = DB::connection('mysql2')->table('reg')
            ->select('reg.*')
            ->whereNotIn('reg.id', [5])
            // ->where('wil.regional', 1)
            ->get();


        $optionREg = json_decode($optionREg, true);

        $perum = DB::connection('mysql2')->table('perumahan')
            ->select(DB::raw('DISTINCT YEAR(datetime) as year'))
            ->orderBy('year', 'desc')
            ->get();

        $years = [];
        foreach ($perum as $sidak) {
            $years[] = $sidak->year;
        }

        // dd($years);


        // $arrView['list_bulan'] =  $bulan;
        return view('Perumahan.dashboard_perum', [
            'arrHeader' => $arrHeader,
            'arrHeaderSc' => $arrHeaderSc,
            'arrHeaderTrd' => $arrHeaderTrd,
            'arrHeaderReg' => $arrHeaderReg,
            'list_bulan' => $bulan,
            'shortMonth' => $shortMonth,
            'option_reg' => $optionREg,
            'list_tahun' => $years,

        ]);
    }


    public function getAFD(Request $request)
    {
        $regional = $request->input('reg');
        $bulan = $request->input('tahun');

        // dd($regional, $bulan);

        $queryEste = DB::connection('mysql2')->table('estate')
            ->select('estate.*')
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->where('wil.regional', $regional)
            ->whereNotIn('estate.est', ['SRE', 'LDE', 'SKE', 'SRS', 'TC', 'SR', 'SLM', 'SGM', 'SKM', 'SYM', 'NBM', 'Plasma3'])
            ->get();
        $queryEste = json_decode($queryEste, true);


        $estates = array_column($queryEste, 'est');
        // dd($estates);
        $emplacement = DB::connection('mysql2')->table('perumahan')
            ->select(
                "perumahan.*",
                DB::raw('DATE_FORMAT(perumahan.datetime, "%M") as bulan'),
                DB::raw('DATE_FORMAT(perumahan.datetime, "%Y") as tahun'),
            )
            ->where('perumahan.datetime', 'like', '%' . $bulan . '%')
            ->whereIn('est', $estates)
            // ->whereNotIn('perumahan.est', 'REG%')
            ->whereNotIn('perumahan.afd', ['est', 'es'])
            ->orderBy('est', 'asc')
            ->orderBy('afd', 'asc')
            ->orderBy('datetime', 'asc')
            ->get();

        $emplacement = json_decode(json_encode($emplacement), true); // Convert the collection to an array
        $emplacement = collect($emplacement)->groupBy(['est', 'afd'])->toArray();

        $lingkungan = DB::connection('mysql2')->table('lingkungan')
            ->select(
                "lingkungan.*",
                DB::raw('DATE_FORMAT(lingkungan.datetime, "%M") as bulan'),
                DB::raw('DATE_FORMAT(lingkungan.datetime, "%Y") as tahun'),
            )
            ->where('lingkungan.datetime', 'like', '%' . $bulan . '%')
            ->whereIn('est', $estates)
            // ->whereNotIn('lingkungan.est', 'REG%')
            ->whereNotIn('lingkungan.afd', ['est', 'es'])
            ->orderBy('est', 'asc')
            ->orderBy('afd', 'asc')
            ->orderBy('datetime', 'asc')
            ->get();

        $lingkungan = json_decode(json_encode($lingkungan), true); // Convert the collection to an array
        $lingkungan = collect($lingkungan)->groupBy(['est', 'afd'])->toArray();


        $landscape = DB::connection('mysql2')->table('landscape')
            ->select(
                "landscape.*",
                DB::raw('DATE_FORMAT(landscape.datetime, "%M") as bulan'),
                DB::raw('DATE_FORMAT(landscape.datetime, "%Y") as tahun'),
            )
            ->where('landscape.datetime', 'like', '%' . $bulan . '%')
            ->whereIn('est', $estates)
            ->whereNotIn('landscape.afd', ['est', 'es'])
            ->orderBy('est', 'asc')
            ->orderBy('afd', 'asc')
            ->orderBy('datetime', 'asc')
            ->get();

        $landscape = json_decode(json_encode($landscape), true); // Convert the collection to an array
        $landscape = collect($landscape)->groupBy(['est', 'afd'])->toArray();

        // dd($emplacement, $landscape, $lingkungan);


        $queryAfd = DB::connection('mysql2')->table('afdeling')
            ->select(
                'afdeling.id',
                'afdeling.nama',
                'estate.est'
            ) //buat mengambil data di estate db dan willayah db
            ->join('estate', 'estate.id', '=', 'afdeling.estate') //kemudian di join untuk mengambil est perwilayah
            ->get();
        $queryAfd = json_decode($queryAfd, true);
        // dd($emplacement);
        $bulan = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        // untuk perumahan 
        $dataPerBulan = array();
        foreach ($emplacement as $key => $value) {
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
        // dd($defaultNew);
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


        foreach ($defaultNew as $key => $value) {
            // dd($key);
            foreach ($queryEste as $key2 => $value2) if ($key == $value2['est']) {
                $emplashmenOri[$value2['nama']] = $value;
            }
        }
        // dd($emplashmenOri);
        // untuk li dd($defaultNew);ngkungan 
        $perbulan_lingkungan = array();
        foreach ($lingkungan as $key => $value) {
            foreach ($value as $key2 => $value2) {
                foreach ($value2 as $key3 => $value3) {
                    $month = date('F', strtotime($value3['datetime']));
                    if (!array_key_exists($month, $perbulan_lingkungan)) {
                        $perbulan_lingkungan[$month] = array();
                    }
                    if (!array_key_exists($key, $perbulan_lingkungan[$month])) {
                        $perbulan_lingkungan[$month][$key] = array();
                    }
                    if (!array_key_exists($key2, $perbulan_lingkungan[$month][$key])) {
                        $perbulan_lingkungan[$month][$key][$key2] = array();
                    }
                    $perbulan_lingkungan[$month][$key][$key2][$key3] = $value3;
                }
            }
        }

        $def_lingkungan = array();
        foreach ($bulan as $month) {
            foreach ($queryEste as $est) {
                foreach ($queryAfd as $afd) {
                    if ($est['est'] == $afd['est']) {
                        $def_lingkungan[$est['est']][$month][$afd['nama']] = 0;
                    }
                }
            }
        }

        foreach ($def_lingkungan as $key => $estValue) {
            foreach ($estValue as $monthKey => $monthValue) {
                foreach ($perbulan_lingkungan as $dataKey => $dataValue) {
                    if ($dataKey == $monthKey) {
                        foreach ($dataValue as $dataEstKey => $dataEstValue) {
                            if ($dataEstKey == $key) {
                                $def_lingkungan[$key][$monthKey] = array_merge($monthValue, $dataEstValue);
                            }
                        }
                    }
                }
            }
        }

        // dd($def_lingkungan);
        foreach ($def_lingkungan as $key => $value) {
            // dd($key);
            foreach ($queryEste as $key2 => $value2) if ($key == $value2['est']) {
                $lingkunganOri[$value2['nama']] = $value;
            }
        }

        // dd($lingkunganOri);

        // untuk landscape 
        $perbulan_landscape = array();
        foreach ($landscape as $key => $value) {
            foreach ($value as $key2 => $value2) {
                foreach ($value2 as $key3 => $value3) {
                    $month = date('F', strtotime($value3['datetime']));
                    if (!array_key_exists($month, $perbulan_landscape)) {
                        $perbulan_landscape[$month] = array();
                    }
                    if (!array_key_exists($key, $perbulan_landscape[$month])) {
                        $perbulan_landscape[$month][$key] = array();
                    }
                    if (!array_key_exists($key2, $perbulan_landscape[$month][$key])) {
                        $perbulan_landscape[$month][$key][$key2] = array();
                    }
                    $perbulan_landscape[$month][$key][$key2][$key3] = $value3;
                }
            }
        }

        $def_lanscape = array();
        foreach ($bulan as $month) {
            foreach ($queryEste as $est) {
                foreach ($queryAfd as $afd) {
                    if ($est['est'] == $afd['est']) {
                        $def_lanscape[$est['est']][$month][$afd['nama']] = 0;
                    }
                }
            }
        }

        foreach ($def_lanscape as $key => $estValue) {
            foreach ($estValue as $monthKey => $monthValue) {
                foreach ($perbulan_landscape as $dataKey => $dataValue) {
                    if ($dataKey == $monthKey) {
                        foreach ($dataValue as $dataEstKey => $dataEstValue) {
                            if ($dataEstKey == $key) {
                                $def_lanscape[$key][$monthKey] = array_merge($monthValue, $dataEstValue);
                            }
                        }
                    }
                }
            }
        }


        foreach ($def_lanscape as $key => $value) {
            foreach ($queryEste as $key2 => $value2) if ($key == $value2['est']) {
                $landscapeOri[$value2['nama']] = $value;
            }
        }
        // dd($dataPerBulan);
        function grupwaktu($data)
        {
            $groupedArray = [];

            foreach ($data as $bulan => $locations) {
                if (is_array($locations)) {
                    foreach ($locations as $location => $estAfdData) {
                        foreach ($estAfdData as $estAfd => $items) if (is_array($items)) {
                            $groupedItems = [];

                            foreach ($items as $item) {
                                $key = $item['est'] . '-' . $item['afd'] . '-' . $item['petugas'] . '-' . $item['pendamping'] . '-' . substr($item['datetime'], 0, 10);

                                if (!isset($groupedItems[$key])) {
                                    $groupedItems[$key] = $item;
                                } else {
                                    $existingItem = $groupedItems[$key];
                                    $index = 1;

                                    // Find the next available index
                                    while (isset($existingItem['nilai' . $index])) {
                                        $index++;
                                    }

                                    // Set the new index for the current item
                                    foreach ($item as $field => $value) {
                                        $existingItem[$field . $index] = $value;
                                    }

                                    $groupedItems[$key] = $existingItem;
                                }
                            }

                            $groupedArray[$bulan][$location][$estAfd] = array_values($groupedItems);
                        }
                    }
                } else {
                    $groupedArray[$bulan] = 0;
                }
            }

            return $groupedArray;
        }

        $groupedArray = grupwaktu($emplashmenOri);
        $groupedArray_lcp = grupwaktu($landscapeOri);
        $groupedArray_lkn = grupwaktu($lingkunganOri);

        // dd($groupedArray, $emplashmenOri);


        // dd($emplashmenOri);
        $hitungRmh = array();
        foreach ($groupedArray as $key => $value) {
            foreach ($value as $key1 => $value2) {
                // Initialize the "nilai_total" for each month to 0
                $hitungRmh[$key][$key1] = [];
                if (is_array($value2)) {
                    foreach ($value2 as $key2 => $value3) {
                        if (is_array($value3)) {
                            // Initialize the "nilai_total" for each index (OA, OB, OC, OD) to 0
                            $hitungRmh[$key][$key1][$key2] = [];

                            foreach ($value3 as $key3 => $value4) {
                                $avg_nilai = 0;
                                if (is_array($value4)) {
                                    $totalNilai = 0;
                                    $nilaiKeys = [];
                                    // Check if the "nilai" key exists, otherwise set it to 0
                                    $sumNilai = isset($value4['nilai']) ? array_sum(array_map('intval', explode('$', $value4['nilai']))) : 0;
                                    // Store the sum in the "nilai_total" key of the corresponding index
                                    $date = $value4['datetime'];
                                    // Get the year and month from the datetime
                                    $yearMonth = date('Y-m-d', strtotime($date));
                                    foreach ($value4 as $innerKey => $innerValue) {
                                        if (strpos($innerKey, 'nilai') === 0) {
                                            $nilaiKeys[] = $innerKey;
                                            $nilaiValues = array_map('intval', explode('$', $innerValue));
                                            $totalNilai += array_sum($nilaiValues);
                                        }
                                    }

                                    $dividen = 0;
                                    foreach ($value4 as $key4 => $item) {
                                        if (strpos($key4, 'nilai') === 0) {
                                            $dividen++;
                                        }
                                    }

                                    if ($dividen != 1) {
                                        $avg_nilai = round($totalNilai / $dividen, 2);
                                    } else {
                                        $avg_nilai = $totalNilai;
                                    }
                                    $hitungRmh[$key][$key1][$key2][$key3]['nilai_total'] = $avg_nilai;
                                    $hitungRmh[$key][$key1][$key2][$key3]['total_nilai'] = $totalNilai;
                                    $hitungRmh[$key][$key1][$key2][$key3]['dividen'] = $dividen;

                                    $hitungRmh[$key][$key1][$key2][$key3]['date'] = $yearMonth;
                                    $hitungRmh[$key][$key1][$key2][$key3]['est'] = $value4['est'];
                                    $hitungRmh[$key][$key1][$key2][$key3]['afd'] = $value4['afd'];
                                    $hitungRmh[$key][$key1][$key2][$key3]['petugas'] = $value4['petugas'];
                                    $hitungRmh[$key][$key1][$key2][$key3]['pendamping'] = $value4['pendamping'];
                                    $hitungRmh[$key][$key1][$key2][$key3]['penghuni'] = $value4['penghuni'];
                                    $hitungRmh[$key][$key1][$key2][$key3]['tipe_rumah'] = $value4['tipe_rumah'];
                                    $hitungRmh[$key][$key1][$key2][$key3]['foto_temuan'] = $value4['foto_temuan'];
                                    $hitungRmh[$key][$key1][$key2][$key3]['komentar_temuan'] = $value4['komentar_temuan'];
                                    $hitungRmh[$key][$key1][$key2][$key3]['nilai'] = $value4['nilai'];
                                    $hitungRmh[$key][$key1][$key2][$key3]['komentar'] = $value4['komentar'];
                                }
                            }
                        }
                    }
                }
            }
        }

        // dd($hitungRmh);

        foreach ($emplashmenOri as $location => $months) {
            foreach ($months as $month => $values) {
                if (isset($hitungRmh[$location][$month])) {
                    // Merge the values from hitungRmh if the month exists in both arrays
                    foreach ($values as $key => $value) {
                        if (is_array($value) && isset($hitungRmh[$location][$month][$key])) {
                            $emplashmenOri[$location][$month][$key] = $hitungRmh[$location][$month][$key];
                        }
                    }
                } else {
                    // If the month does not exist in hitungRmh, set default values as 0
                    $emplashmenOri[$location][$month] = array_fill_keys(array_keys($values), 0);
                }
            }
        }

        // Resulting merged array with values from hitungRmh
        $mergedArray_rmh = $emplashmenOri;

        // dd($mergedArray_rmh);
        // Now, the "nilai" values will be updated with their respective sums in the $emplacement array.


        // dd($mergedArray_rmh, $hitungRmh);

        $FinalArr_rumah = array();
        foreach ($mergedArray_rmh as $key1 => $value1) {
            foreach ($value1 as $key2 => $value2) {
                foreach ($value2 as $key3 => $value3) {
                    if (is_array($value3)) {
                        foreach ($value3 as $value4) {
                            $FinalArr_rumah[$key1][$key2][$key3][$value4['date']] = $value4;
                        }
                    } else {
                        // For the innermost arrays that are not arrays themselves, just copy them as is
                        $FinalArr_rumah[$key1][$key2][$key3] = $value3;
                    }
                }
            }
        }
        // dd($FinalArr_rumah, $mergedArray_rmh);
        // dd($mergedArray_rmh, $FinalArr_rumah);

        // untuk landscape 
        $hitungLandscape = array();
        foreach ($groupedArray_lcp as $key => $value) {
            foreach ($value as $key1 => $value2) {
                // Initialize the "nilai_total" for each month to 0
                $hitungLandscape[$key][$key1] = [];
                if (is_array($value2)) {
                    foreach ($value2 as $key2 => $value3) {
                        if (is_array($value3)) {
                            // Initialize the "nilai_total" for each index (OA, OB, OC, OD) to 0
                            $hitungLandscape[$key][$key1][$key2] = [];
                            foreach ($value3 as $key3 => $value4) {
                                $avg_nilai = 0;
                                if (is_array($value4)) {
                                    $totalNilai = 0;
                                    $nilaiKeys = [];
                                    // Check if the "nilai" key exists, otherwise set it to 0
                                    $sumNilai = isset($value4['nilai']) ? array_sum(array_map('intval', explode('$', $value4['nilai']))) : 0;
                                    // Store the sum in the "nilai_total" key of the corresponding index
                                    $date = $value4['datetime'];
                                    // Get the year and month from the datetime
                                    $yearMonth = date('Y-m-d', strtotime($date));

                                    foreach ($value4 as $innerKey => $innerValue) {
                                        if (strpos($innerKey, 'nilai') === 0) {
                                            $nilaiKeys[] = $innerKey;
                                            $nilaiValues = array_map('intval', explode('$', $innerValue));
                                            $totalNilai += array_sum($nilaiValues);
                                        }
                                    }

                                    $dividen = 0;
                                    foreach ($value4 as $key4 => $item) {
                                        if (strpos($key4, 'nilai') === 0) {
                                            $dividen++;
                                        }
                                    }

                                    if ($dividen != 1) {
                                        $avg_nilai = round($totalNilai / $dividen, 2);
                                    } else {
                                        $avg_nilai = $totalNilai;
                                    }
                                    $hitungLandscape[$key][$key1][$key2][$key3]['nilai_total_LP'] = $avg_nilai;
                                    $hitungLandscape[$key][$key1][$key2][$key3]['date'] = $yearMonth;
                                    $hitungLandscape[$key][$key1][$key2][$key3]['est_LP'] = $value4['est'];
                                    $hitungLandscape[$key][$key1][$key2][$key3]['afd_LP'] = $value4['afd'];
                                    $hitungLandscape[$key][$key1][$key2][$key3]['petugas_LP'] = $value4['petugas'];
                                    $hitungLandscape[$key][$key1][$key2][$key3]['pendamping_LP'] = $value4['pendamping'];
                                    $hitungLandscape[$key][$key1][$key2][$key3]['foto_temuan_LP'] = $value4['foto_temuan'];
                                    $hitungLandscape[$key][$key1][$key2][$key3]['komentar_temuan_LP'] = $value4['komentar_temuan'];
                                    $hitungLandscape[$key][$key1][$key2][$key3]['nilai_LP'] = $value4['nilai'];
                                    $hitungLandscape[$key][$key1][$key2][$key3]['komentar_LP'] = $value4['komentar'];
                                }
                            }
                        }
                    }
                }
            }
        }

        foreach ($landscapeOri as $location => $months) {
            foreach ($months as $month => $values) {
                if (isset($hitungLandscape[$location][$month])) {
                    // Merge the values from hitungLandscape if the month exists in both arrays
                    foreach ($values as $key => $value) {
                        if (is_array($value) && isset($hitungLandscape[$location][$month][$key])) {
                            $landscapeOri[$location][$month][$key] = $hitungLandscape[$location][$month][$key];
                        }
                    }
                } else {
                    // If the month does not exist in hitungRmh, set default values as 0
                    $landscapeOri[$location][$month] = array_fill_keys(array_keys($values), 0);
                }
            }
        }

        // Resulting merged array with values from hitungRmh
        $mergedArray_lp = $landscapeOri;

        $FinalArr_LP = array();
        foreach ($mergedArray_lp as $key1 => $value1) {
            foreach ($value1 as $key2 => $value2) {
                foreach ($value2 as $key3 => $value3) {
                    if (is_array($value3)) {
                        foreach ($value3 as $value4) {
                            $FinalArr_LP[$key1][$key2][$key3][$value4['date']] = $value4;
                        }
                    } else {
                        // For the innermost arrays that are not arrays themselves, just copy them as is
                        $FinalArr_LP[$key1][$key2][$key3] = $value3;
                    }
                }
            }
        }

        // dd($FinalArr_LP);

        // hitungan lingkungan 

        $hitungLingkungan = array();
        foreach ($groupedArray_lkn as $key => $value) {
            foreach ($value as $key1 => $value2) {
                // Initialize the "nilai_total" for each month to 0
                $hitungLingkungan[$key][$key1] = [];
                if (is_array($value2)) {
                    foreach ($value2 as $key2 => $value3) {
                        if (is_array($value3)) {
                            // Initialize the "nilai_total" for each index (OA, OB, OC, OD) to 0
                            $hitungLingkungan[$key][$key1][$key2] = [];
                            foreach ($value3 as $key3 => $value4) {
                                $avg_nilai = 0;
                                if (is_array($value4)) {
                                    $totalNilai = 0;
                                    $nilaiKeys = [];
                                    // Check if the "nilai" key exists, otherwise set it to 0
                                    $sumNilai = isset($value4['nilai']) ? array_sum(array_map('intval', explode('$', $value4['nilai']))) : 0;
                                    // Store the sum in the "nilai_total" key of the corresponding index
                                    $date = $value4['datetime'];
                                    // Get the year and month from the datetime
                                    $yearMonth = date('Y-m-d', strtotime($date));

                                    foreach ($value4 as $innerKey => $innerValue) {
                                        if (strpos($innerKey, 'nilai') === 0) {
                                            $nilaiKeys[] = $innerKey;
                                            $nilaiValues = array_map('intval', explode('$', $innerValue));
                                            $totalNilai += array_sum($nilaiValues);
                                        }
                                    }

                                    $dividen = 0;
                                    foreach ($value4 as $key4 => $item) {
                                        if (strpos($key4, 'nilai') === 0) {
                                            $dividen++;
                                        }
                                    }

                                    if ($dividen != 1) {
                                        $avg_nilai = round($totalNilai / $dividen, 2);
                                    } else {
                                        $avg_nilai = $totalNilai;
                                    }

                                    $hitungLingkungan[$key][$key1][$key2][$key3]['nilai_total_Lngkl'] = $avg_nilai;
                                    $hitungLingkungan[$key][$key1][$key2][$key3]['date'] = $yearMonth;
                                    $hitungLingkungan[$key][$key1][$key2][$key3]['est_Lngkl'] = $value4['est'];
                                    $hitungLingkungan[$key][$key1][$key2][$key3]['afd_Lngkl'] = $value4['afd'];
                                    $hitungLingkungan[$key][$key1][$key2][$key3]['petugas_Lngkl'] = $value4['petugas'];
                                    $hitungLingkungan[$key][$key1][$key2][$key3]['pendamping_Lngkl'] = $value4['pendamping'];
                                    $hitungLingkungan[$key][$key1][$key2][$key3]['foto_temuan_Lngkl'] = $value4['foto_temuan'];
                                    $hitungLingkungan[$key][$key1][$key2][$key3]['komentar_temuan_Lngkl'] = $value4['komentar_temuan'];
                                    $hitungLingkungan[$key][$key1][$key2][$key3]['nilai_Lngkl'] = $value4['nilai'];
                                    $hitungLingkungan[$key][$key1][$key2][$key3]['komentar_Lngkl'] = $value4['komentar'];
                                }
                            }
                        }
                    }
                }
            }
        }

        foreach ($lingkunganOri as $location => $months) {
            foreach ($months as $month => $values) {
                if (isset($hitungLingkungan[$location][$month])) {
                    // Merge the values from hitungLingkungan if the month exists in both arrays
                    foreach ($values as $key => $value) {
                        if (is_array($value) && isset($hitungLingkungan[$location][$month][$key])) {
                            $lingkunganOri[$location][$month][$key] = $hitungLingkungan[$location][$month][$key];
                        }
                    }
                } else {
                    // If the month does not exist in hitungRmh, set default values as 0
                    $lingkunganOri[$location][$month] = array_fill_keys(array_keys($values), 0);
                }
            }
        }

        // Resulting merged array with values from hitungRmh
        $mrg_lingkn = $lingkunganOri;

        $FinalArr_Lingkn = array();
        foreach ($mrg_lingkn as $key1 => $value1) {
            foreach ($value1 as $key2 => $value2) {
                foreach ($value2 as $key3 => $value3) {
                    if (is_array($value3)) {
                        foreach ($value3 as $value4) {
                            $FinalArr_Lingkn[$key1][$key2][$key3][$value4['date']] = $value4;
                        }
                    } else {
                        // For the innermost arrays that are not arrays themselves, just copy them as is
                        $FinalArr_Lingkn[$key1][$key2][$key3] = $value3;
                    }
                }
            }
        }

        // dd($FinalArr_rumah, $FinalArr_LP, $FinalArr_Lingkn);

        function mergeArrays($arr1, $arr2, $arr3)
        {
            foreach ($arr1 as $key => $value) {
                if (isset($arr2[$key])) {
                    $arr1[$key] = array_merge_recursive($value, $arr2[$key]);
                }
                if (isset($arr3[$key])) {
                    $arr1[$key] = mergeArraysRecursive($arr1[$key] ?? [], $arr3[$key]);
                }
            }

            return $arr1;
        }

        function mergeArraysRecursive($arr1, $arr2)
        {
            foreach ($arr2 as $key => $value) {
                if (is_array($value)) {
                    $arr1[$key] = mergeArraysRecursive($arr1[$key] ?? [], $value);
                } else {
                    $arr1[$key] = $value ?? 0;
                }
            }

            return $arr1;
        }

        $result = mergeArrays($FinalArr_rumah, $FinalArr_LP, $FinalArr_Lingkn);



        foreach ($result as $key => $value) {
            foreach ($value as $key1 => $value1) {
                foreach ($value1 as $key2 => $value2) {
                    if (is_array($value2)) {
                        $containsNonZeroValue = false;
                        foreach ($value2 as $innerValue) {
                            if (!is_array($innerValue) && $innerValue === 0) {
                                $containsNonZeroValue = true;
                                break;
                            }
                        }

                        if ($containsNonZeroValue) {
                            $filteredValue = array_filter($value2, function ($val) {
                                return !(is_numeric($val) && $val === 0);
                            });

                            $result[$key][$key1][$key2] = $filteredValue;
                            if (empty($filteredValue)) {
                                unset($result[$key][$key1][$key2]);
                            }
                        }
                    }
                }
            }
        }


        $queryAsisten =  DB::connection('mysql2')->Table('asisten_qc')->get();

        $queryAsisten = json_decode($queryAsisten, true);


        $final_result = array();

        foreach ($result as $key => $value) {
            foreach ($value as $key1 => $value1) {
                foreach ($value1 as $key2 => $value2) {
                    $inc = 1;
                    if (is_array($value2)) {
                        foreach ($value2 as $key3 => $value3) {
                            $EM = 'GM';
                            $nama = '-';
                            foreach ($queryAsisten as $value4) {
                                if ($value4['est'] == ($value3['est'] ?? $value3['est_LP'] ??  $value3['est_Lngkl'] ?? null) && $value4['afd'] == $key2) {
                                    $nama = $value4['nama'];
                                    break;
                                }
                            }
                            $total_skor = ($value3['nilai_total'] ?? 0) + ($value3['nilai_total_LP'] ?? 0) + ($value3['nilai_total_Lngkl'] ?? 0);
                            $final_result[$key][$key1][$key2][$key3]['skor_total'] = $total_skor;
                            $final_result[$key][$key1][$key2][$key3]['visit'] = $inc++;
                            $final_result[$key][$key1][$key2][$key3]['est'] = $value3['est'] ?? $value3['est_LP'] ??  $value3['est_Lngkl'] ?? null;
                            $final_result[$key][$key1][$key2][$key3]['afd'] = $key2;
                            $final_result[$key][$key1][$key2][$key3]['asisten'] = $nama;
                            $final_result[$key][$key1][$key2][$key3]['date'] = $key3;
                        }
                    } else {
                        // If it's not an array, it means it's a direct value (e.g., February, March, etc.)
                        // Set default value of 0 for missing months
                        $final_result[$key][$key1][$key2] = $value2;
                    }
                }
            }
        }



        $resultArray = [];

        // Loop through the original array
        foreach ($final_result as $estate => $months) {
            foreach ($months as $month => $afdelings) {
                foreach ($afdelings as $afdeling => $data) {
                    $resultArray[$estate][$afdeling][$month] = $data;
                }
            }
        }
        // dd($resultArray);
        // dd($final_result, $resultArray);
        foreach ($resultArray as $estate => $months) {
            foreach ($months as $afdeling => $data) {
                foreach ($data as $month => $visits) {
                    if (is_array($visits)) {
                        $visitsWithoutDates = []; // Array to store visit data without individual dates
                        foreach ($visits as $date => $visitData) {
                            if (is_array($visitData)) { // Check if $visitData is an array (not an individual date)
                                $visitKey = 'visit' . (count($visitsWithoutDates) + 1);

                                $visitsWithoutDates[$visitKey] = $visitData;
                            }
                        }
                        // dd($visitData);

                        // Add default visit data for months with no visits (empty $visitsWithoutDates)
                        if (empty($visitsWithoutDates)) {
                            $visitsWithoutDates['visit1'] = [
                                'skor_total' => 0,
                                'visit' => 1,
                                'est' => $estate,
                                'afd' => $afdeling,

                                'date' => '-'
                            ];
                        }

                        // After the loop, set the resultArray to the updated visits without dates
                        $resultArray[$estate][$afdeling][$month] = $visitsWithoutDates;
                    } else {
                        $nama = '-';
                        foreach ($queryAsisten as $value4) {
                            if ($value4['est'] == $estate && $value4['afd'] == $afdeling) {
                                $nama = $value4['nama'];
                                break;
                            }
                        }
                        $resultArray[$estate][$afdeling][$month] = [
                            'visit1' => [
                                'skor_total' => 0,
                                'visit' => 1,
                                'est' => $estate,
                                'afd' => $afdeling,
                                'asisten' => $nama,
                                'date' => '-'
                            ]
                        ];
                    }
                }
            }
        }

        // dd($resultArray);
        $avarage = array();
        foreach ($resultArray as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $avg = 0;
                foreach ($value1 as $key2 => $value2) {
                    $avg = count($value2);
                    $totalNil = 0;
                    foreach ($value2 as $key3 => $value3) {
                        $totalNil += $value3['skor_total'];
                    }

                    // $tod_nilai = round($totalNil / $avg, 1);

                    if ($avg != 1 || ($value3['skor_total'] != 0 && $value3['date'] !== '-')) {
                        $avarage[$key][$key1][$key2]['dividen'] = $avg;
                    } else {
                        $avarage[$key][$key1][$key2]['dividen'] = 0;
                    }

                    $avarage[$key][$key1][$key2]['total'] = $totalNil;
                } # code...
            } # code...
        }
        // dd($avarage);

        $averages = array();
        $regAvg = array();

        foreach ($avarage as $key => $value) {
            $estAvg = array();

            foreach ($value as $key1 => $value1) {
                $allAvg = array(); // Initialize an array to store all the averages for each key2
                $totalNil = 0;
                $avgCount = 0;
                $todNilai = 0;
                foreach ($value1 as $key2 => $value2) {

                    // dd($value2);
                    $totalNil += $value2['total'];
                    $avgCount  += $value2['dividen'];
                }

                if ($avgCount != 0) {
                    $todNilai = round($totalNil / $avgCount, 1);
                } else {
                    $todNilai = $totalNil;
                }


                $averages[$key][$key1]['avgafd'] = $todNilai;
                $averages[$key][$key1]['totalNil'] = $totalNil;
                $averages[$key][$key1]['avgCount'] = $avgCount;
                $estAvg[] = $todNilai;
            }

            if (!empty($estAvg)) {
                $regAvg = array_merge($regAvg, $estAvg); // Merge the calculated averages
                // $averages[$key]['avgEst'] = $estAvg;
            }
        }
        // $averages['avgReg'] = $regAvg;
        // dd($avarage, $averages);


        $get_cell = array();
        foreach ($resultArray as $key => $value) {
            foreach ($value as $key1 => $value1) {
                foreach ($value1 as $key2 => $value2) {
                    $visit = count($value2);
                    $get_cell[$key2][$key][$key1]['visit'] = $visit;
                }
            }
        }

        $max_visit = array();
        foreach ($get_cell as $month => $monthData) {
            $max_visitEst = 0;
            foreach ($monthData as $location => $locationData) {
                $maxVisit = 0;
                foreach ($locationData as $visitData) {
                    // Check if the current visit value is greater than the current maximum visit
                    if (isset($visitData['visit']) && $visitData['visit'] > $maxVisit) {
                        // Update the maximum visit for this location
                        $maxVisit = $visitData['visit'];
                    }
                }
                // Add the maximum visit for this location to the $max_visit array
                $max_visit[$month][$location]['max_visit'] = $maxVisit;

                // Calculate the overall maximum visit for the entire array
                if ($maxVisit > $max_visitEst) {
                    $max_visitEst = $maxVisit;
                }
            }
            // Add the overall maximum visit for this month to the $max_visit array
            $max_visit[$month]['max_visitEst'] = $max_visitEst;
        }

        $header_cell = array();
        foreach ($max_visit as $key => $value) {
            $header_cell[$key] = $value['max_visitEst'];
        }

        function createEmptyVisit($visitNumber)
        {
            return [
                "skor_total" => 0,
                "visit" => $visitNumber,
                "est" => "Kenambui", // Replace with the appropriate value
                "afd" => "OB", // Replace with the appropriate value
                "asisten" => "-",
                "date" => "-",
            ];
        }


        foreach ($resultArray as $key1 => &$level1) {
            foreach ($level1 as $key2 => &$level2) {
                foreach ($level2 as $month => &$visits) {
                    if (isset($header_cell[$month])) {
                        $requiredVisits = $header_cell[$month];
                        $currentVisits = count($visits);

                        // Add empty visits if required
                        for ($i = $currentVisits + 1; $i <= $requiredVisits; $i++) {
                            $visits["visit" . $i] = createEmptyVisit($i);
                        }
                    }
                }
            }
        }



        $sum_header = [
            "head" => array_sum($header_cell) + 2,
        ];
        $AfdFinals = array();

        foreach ($resultArray as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $AfdFinals[$key][$key1] = array(); // Initialize the sub-array
                foreach ($value1 as $key2 => $value2) {
                    $AfdFinals[$key][$key1][$key2] = $value2;
                }
                if (isset($averages[$key][$key1]['avgafd'])) {
                    $AfdFinals[$key][$key1]['afd'] = $averages[$key][$key1]['avgafd'];
                }
            }
        }


        // dd($resultArray, $averages, $AfdFinals);
        // dd($averages);
        $arrView = array();
        $arrView['reg'] =  $regional;
        $arrView['bulan'] =  $bulan;
        $arrView['afd_rekap'] =  $AfdFinals;
        $arrView['header_cell'] =  $header_cell;
        $arrView['header_head'] =  $sum_header;
        $arrView['avg'] =  $averages;


        echo json_encode($arrView); //di decode ke dalam bentuk json dalam vaiavel arrview yang dapat menampung banyak isi array
        exit();

        // return view('dashboard_perum', [
        //     'afd_rekap' => $resultArray
        // ]);
    }

    public function estAFD(Request $request)
    {
        $regional = $request->input('reg');
        $bulan = $request->input('tahun');

        $queryEste = DB::connection('mysql2')->table('estate')
            ->select('estate.*')
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->where('wil.regional', $regional)
            ->whereNotIn('estate.est', ['SRE', 'LDE', 'SKE', 'Plasma3'])
            ->get();
        $queryEste = json_decode($queryEste, true);

        // dd($queryEste);


        $est_emp = DB::connection('mysql2')->table('estate_emp')
            ->select(
                'estate_emp.id',
                'estate.nama',
                'estate.est',
                'estate_emp.wil',
                'estate_emp.nama  as namaafd',
            )
            ->join('wil', 'wil.id', '=', 'estate_emp.wil')
            ->where('wil.regional', $regional)
            ->join('estate', 'estate.id', '=', 'estate_emp.estate')
            ->get();
        $est_emp = json_decode($est_emp, true);

        $filteredArray = array_filter($est_emp, function ($item) {
            $excludedEstValues = ['LDE', 'SRE', 'SKE'];
            return !in_array($item['nama'], $excludedEstValues);
        });

        // dd($filteredArray);
        $estates = array_column($filteredArray, 'est');

        $qerafd = array_column($filteredArray, 'namaafd');
        // dd($estates, $qerafd, $est_emp);
        $emplacement = DB::connection('mysql2')->table('perumahan')
            ->select(
                "perumahan.*",
                DB::raw('DATE_FORMAT(perumahan.datetime, "%M") as bulan'),
                DB::raw('DATE_FORMAT(perumahan.datetime, "%Y") as tahun'),
                DB::raw('CASE WHEN perumahan.est = "GDE" AND perumahan.afd = "OD" THEN "EST" ELSE perumahan.afd END AS newafd')
            )
            ->where('perumahan.datetime', 'like', '%' . $bulan . '%')
            ->whereIn('est', $estates)
            ->whereIn(DB::raw('CASE WHEN perumahan.est = "GDE" AND perumahan.afd = "OD" THEN "EST" ELSE perumahan.afd END'), $qerafd)

            ->orderBy('est', 'asc')
            ->orderBy('afd', 'asc')
            ->orderBy('datetime', 'asc')
            ->get();

        $emplacement = json_decode(json_encode($emplacement), true); // Convert the collection to an array
        $emplacement = collect($emplacement)->groupBy(['est', 'afd'])->toArray();

        // dd($emplacement);

        $lingkungan = DB::connection('mysql2')->table('lingkungan')
            ->select(
                "lingkungan.*",
                DB::raw('DATE_FORMAT(lingkungan.datetime, "%M") as bulan'),
                DB::raw('DATE_FORMAT(lingkungan.datetime, "%Y") as tahun'),
                DB::raw('CASE WHEN lingkungan.est = "GDE" AND lingkungan.afd = "OD" THEN "EST" ELSE lingkungan.afd END AS newafd')
            )
            ->where('lingkungan.datetime', 'like', '%' . $bulan . '%')
            ->whereIn('est', $estates)
            // ->whereIn('afd', $qerafd)
            ->whereIn(DB::raw('CASE WHEN lingkungan.est = "GDE" AND lingkungan.afd = "OD" THEN "EST" ELSE lingkungan.afd END'), $qerafd)

            ->orderBy('est', 'asc')
            ->orderBy('afd', 'asc')
            ->orderBy('datetime', 'asc')
            ->get();

        $lingkungan = json_decode(json_encode($lingkungan), true); // Convert the collection to an array
        $lingkungan = collect($lingkungan)->groupBy(['est', 'afd'])->toArray();


        $landscape = DB::connection('mysql2')->table('landscape')
            ->select(
                "landscape.*",
                DB::raw('DATE_FORMAT(landscape.datetime, "%M") as bulan'),
                DB::raw('DATE_FORMAT(landscape.datetime, "%Y") as tahun'),
                DB::raw('CASE WHEN landscape.est = "GDE" AND landscape.afd = "OD" THEN "EST" ELSE landscape.afd END AS newafd')

            )
            ->where('landscape.datetime', 'like', '%' . $bulan . '%')
            ->whereIn('est', $estates)
            // ->whereIn('afd', $qerafd)
            ->whereIn(DB::raw('CASE WHEN landscape.est = "GDE" AND landscape.afd = "OD" THEN "EST" ELSE landscape.afd END'), $qerafd)
            ->orderBy('est', 'asc')
            ->orderBy('afd', 'asc')
            ->orderBy('datetime', 'asc')
            ->get();

        $landscape = json_decode(json_encode($landscape), true); // Convert the collection to an array
        $landscape = collect($landscape)->groupBy(['est', 'afd'])->toArray();
        // dd($landscape, $emplacement, $lingkungan);


        $bulan = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];



        $dataPerBulan = array();
        foreach ($emplacement as $key => $value) {
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

        // // dd($dataPerBulan);
        // dd($queryEste, $filteredArray);

        $defaultNew = array();
        foreach ($bulan as $month) {
            foreach ($queryEste as $est) {
                foreach ($filteredArray as $afd) {
                    if ($est['est'] == $afd['est']) {
                        $defaultNew[$est['est']][$month][$afd['namaafd']] = 0;
                    }
                }
            }
        }



        foreach ($defaultNew as $key => $estValue) {
            foreach ($estValue as $monthKey => $monthValue) {
                foreach ($dataPerBulan as $dataKey => $dataValue) {
                    // dd($dataKey == $monthKey);
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
        // dd($defaultNew, $dataPerBulan);
        $emplashmenOri = array();


        // dd($defaultNew);
        foreach ($defaultNew as $key => $value) {
            foreach ($value as $key3 => $value3) {
                foreach ($value3 as $key4 => $value4) {
                    foreach ($filteredArray as $key2 => $value2) {
                        if ($key == $value2['est']) {
                            $exceptKeys = ['TC', 'SRS', 'SR', 'REG-1'];
                            // Check if the key is in the exceptKeys array or contains "Mill"
                            // dd($key4);
                            $new_key = $value2['nama'];
                            if (!in_array($key, $exceptKeys) && strpos($new_key, 'Mill') === false) {
                                // If it doesn't meet any of these conditions and doesn't end with "Mill", add '-EST'
                                $keyToAdd = $key . '-EST';
                            } else {
                                // Otherwise, keep the key as it is
                                $keyToAdd = $key;
                            }
                            $emplashmenOri[$value2['nama']][$keyToAdd][$key3] = $value4;
                        }
                    }
                }
            }
        }
        // dd($emplashmenOri);
        // dd($defaultNew);

        function combineItemsByDatetime($data)
        {
            $combinedArray = [];

            foreach ($data as $bulan => $locations) {
                foreach ($locations as $location => $estData) {
                    foreach ($estData as $estAfd => $items) {
                        $datetimeMap = [];

                        if (!is_array($items)) {
                            $combinedArray[$bulan][$location][$estAfd] = $items;
                            continue;
                        }

                        foreach ($items as $item) {
                            $datetime = explode(" ", $item['datetime'])[0];

                            if (!isset($datetimeMap[$datetime])) {
                                $datetimeMap[$datetime] = [];
                            }

                            $datetimeMap[$datetime][] = $item;
                        }

                        $combinedItems = [];
                        foreach ($datetimeMap as $datetimeItems) {
                            $combinedItem = [];

                            foreach ($datetimeItems as $index => $item) {
                                foreach ($item as $key => $value) {
                                    $combinedItem[$key . ($index + 1)] = $value;
                                }
                            }

                            $combinedItems[] = $combinedItem;
                        }

                        $combinedArray[$bulan][$location][$estAfd] = $combinedItems;
                    }
                }
            }

            return $combinedArray;
        }

        $combinedArray = combineItemsByDatetime($emplashmenOri);
        // dd($emplashmenOri, $combinedArray);
        $new_rumah = array();

        foreach ($combinedArray as $key => $value) {
            foreach ($value as $key1 => $value1) {
                foreach ($value1 as $key2 => $value2) {
                    if (is_array($value2)) {
                        foreach ($value2 as $key3 => $value3) {
                            $avg_nilai = 0;

                            if (is_array($value3)) {
                                $totalNilai = 0;
                                $nilaiKeys = [];

                                foreach ($value3 as $innerKey => $innerValue) {
                                    if (strpos($innerKey, 'nilai') === 0) {
                                        $nilaiKeys[] = $innerKey;
                                        $nilaiValues = array_map('intval', explode('$', $innerValue));
                                        $totalNilai += array_sum($nilaiValues);
                                    }
                                }

                                $dividen = count($nilaiKeys);
                                if ($dividen !== 0) {
                                    $avg_nilai = round($totalNilai / $dividen);
                                } else {
                                    $avg_nilai = $totalNilai;
                                }

                                $date = $value3['datetime1'];
                                $yearMonth = date('Y-m-d', strtotime($date));

                                $new_rumah[$key][$key1][$key2][$key3]['nilai_total'] = $avg_nilai;
                                $new_rumah[$key][$key1][$key2][$key3]['total_nilai'] = $totalNilai;
                                $new_rumah[$key][$key1][$key2][$key3]['dividen'] = $dividen;
                                $new_rumah[$key][$key1][$key2][$key3]['date'] = $yearMonth;
                                $new_rumah[$key][$key1][$key2][$key3]['est'] = $value3['est1'];
                                $new_rumah[$key][$key1][$key2][$key3]['afd'] = $value3['afd1'];
                            }
                        }
                    } else {
                        // dd($bulan);
                        $tahun = $request->input('tahun');


                        $parts = explode('-', $key1);

                        $part1 = isset($parts[0]) ? $parts[0] : '';
                        $part2 = isset($parts[1]) ? $parts[1] : '';

                        $arrDef = [
                            'nilai_total' => 0,
                            'total_nilai' => 0,
                            'dividen' => 0,
                            'date' => $tahun, // Make sure $tahun is defined somewhere
                            'est' => $part1,
                            'afd' => $part2,
                        ];
                        $new_rumah[$key][$key1][$key2][0] = $arrDef;
                    }
                }
            }
        }

        // dd($new_rumah);



        // dd($new_rumah, $combinedArray);

        // perhitungan landscape 


        $dataLcp = array();
        foreach ($landscape as $key => $value) {
            foreach ($value as $key2 => $value2) {
                foreach ($value2 as $key3 => $value3) {
                    $month = date('F', strtotime($value3['datetime']));
                    if (!array_key_exists($month, $dataLcp)) {
                        $dataLcp[$month] = array();
                    }
                    if (!array_key_exists($key, $dataLcp[$month])) {
                        $dataLcp[$month][$key] = array();
                    }
                    if (!array_key_exists($key2, $dataLcp[$month][$key])) {
                        $dataLcp[$month][$key][$key2] = array();
                    }
                    $dataLcp[$month][$key][$key2][$key3] = $value3;
                }
            }
        }

        $defaultLcp = array();
        foreach ($bulan as $month) {
            foreach ($queryEste as $est) {
                foreach ($filteredArray as $afd) {
                    if ($est['est'] == $afd['est']) {
                        $defaultLcp[$est['est']][$month][$afd['namaafd']] = 0;
                    }
                }
            }
        }

        foreach ($defaultLcp as $key => $estValue) {
            foreach ($estValue as $monthKey => $monthValue) {
                foreach ($dataLcp as $dataKey => $dataValue) {
                    // dd($dataKey == $monthKey);
                    if ($dataKey == $monthKey) {
                        foreach ($dataValue as $dataEstKey => $dataEstValue) {
                            if ($dataEstKey == $key) {
                                $defaultLcp[$key][$monthKey] = array_merge($monthValue, $dataEstValue);
                            }
                        }
                    }
                }
            }
        }

        $landscapeOri = array();

        // dd($defaultNew);
        foreach ($defaultLcp as $key => $value) {
            foreach ($value as $key3 => $value3) {
                foreach ($value3 as $key4 => $value4) {
                    foreach ($filteredArray as $key2 => $value2) if ($key == $value2['est']) {
                        $exceptKeys = ['TC', 'SRS', 'SR', 'REG-1'];
                        // Check if the key is in the exceptKeys array or contains "Mill"
                        // dd($key4);
                        $new_key = $value2['nama'];
                        if (!in_array($key, $exceptKeys) && strpos($new_key, 'Mill') === false) {
                            // If it doesn't meet any of these conditions and doesn't end with "Mill", add '-EST'
                            $keyToAdd = $key . '-EST';
                        } else {
                            // Otherwise, keep the key as it is
                            $keyToAdd = $key;
                        }
                        $landscapeOri[$value2['nama']][$keyToAdd][$key3] = $value4;
                    }
                }
            }
        }

        // dd($dataLcp, $landscapeOri);
        $cmbLandscape = combineItemsByDatetime($landscapeOri);
        $new_lcp = array();

        foreach ($cmbLandscape as $key => $value) {
            foreach ($value as $key1 => $value1) {
                foreach ($value1 as $key2 => $value2) {
                    if (is_array($value2)) {
                        foreach ($value2 as $key3 => $value3) {
                            $avg_nilai = 0;

                            if (is_array($value3)) {
                                $totalNilai = 0;
                                $nilaiKeys = [];

                                foreach ($value3 as $innerKey => $innerValue) {
                                    if (strpos($innerKey, 'nilai') === 0) {
                                        $nilaiKeys[] = $innerKey;
                                        $nilaiValues = array_map('intval', explode('$', $innerValue));
                                        $totalNilai += array_sum($nilaiValues);
                                    }
                                }

                                $dividen = count($nilaiKeys);
                                if ($dividen !== 0) {
                                    $avg_nilai = round($totalNilai / $dividen);
                                } else {
                                    $avg_nilai = $totalNilai;
                                }

                                $date = $value3['datetime1'];
                                $yearMonth = date('Y-m-d', strtotime($date));

                                $new_lcp[$key][$key1][$key2][$key3]['nilai_total_LP'] = $avg_nilai;
                                $new_lcp[$key][$key1][$key2][$key3]['total_nilai_LP'] = $totalNilai;
                                $new_lcp[$key][$key1][$key2][$key3]['dividen_LP'] = $dividen;
                                $new_lcp[$key][$key1][$key2][$key3]['date_LP'] = $yearMonth;
                                $new_lcp[$key][$key1][$key2][$key3]['est_LP'] = $value3['est1'];
                                $new_lcp[$key][$key1][$key2][$key3]['afd_LP'] = $value3['afd1'];
                            }
                        }
                    } else {
                        // dd($bulan);
                        $tahun = $request->input('tahun');


                        $parts = explode('-', $key1);

                        $part1 = isset($parts[0]) ? $parts[0] : '';
                        $part2 = isset($parts[1]) ? $parts[1] : '';

                        $arrDef = [
                            'nilai_total_LP' => 0,
                            'total_nilai_LP' => 0,
                            'dividen_LP' => 0,
                            'date_LP' => $tahun, // Make sure $tahun is defined somewhere
                            'est_LP' => $part1,
                            'afd_LP' => $part2,
                        ];
                        $new_lcp[$key][$key1][$key2][0] = $arrDef;
                    }
                }
            }
        }

        // dd($new_lcp);

        //perhitungan lingkungan

        $dataLkng = array();
        foreach ($lingkungan as $key => $value) {
            foreach ($value as $key2 => $value2) {
                foreach ($value2 as $key3 => $value3) {
                    $month = date('F', strtotime($value3['datetime']));
                    if (!array_key_exists($month, $dataLkng)) {
                        $dataLkng[$month] = array();
                    }
                    if (!array_key_exists($key, $dataLkng[$month])) {
                        $dataLkng[$month][$key] = array();
                    }
                    if (!array_key_exists($key2, $dataLkng[$month][$key])) {
                        $dataLkng[$month][$key][$key2] = array();
                    }
                    $dataLkng[$month][$key][$key2][$key3] = $value3;
                }
            }
        }

        $defaultLkng = array();
        foreach ($bulan as $month) {
            foreach ($queryEste as $est) {
                foreach ($filteredArray as $afd) {
                    if ($est['est'] == $afd['est']) {
                        $defaultLkng[$est['est']][$month][$afd['namaafd']] = 0;
                    }
                }
            }
        }

        foreach ($defaultLkng as $key => $estValue) {
            foreach ($estValue as $monthKey => $monthValue) {
                foreach ($dataLkng as $dataKey => $dataValue) {
                    // dd($dataKey == $monthKey);
                    if ($dataKey == $monthKey) {
                        foreach ($dataValue as $dataEstKey => $dataEstValue) {
                            if ($dataEstKey == $key) {
                                $defaultLkng[$key][$monthKey] = array_merge($monthValue, $dataEstValue);
                            }
                        }
                    }
                }
            }
        }
        $lingkuganOri = array();

        // dd($defaultNew);
        foreach ($defaultLkng as $key => $value) {
            foreach ($value as $key3 => $value3) {
                foreach ($value3 as $key4 => $value4) {
                    foreach ($filteredArray as $key2 => $value2) if ($key == $value2['est']) {
                        $exceptKeys = ['TC', 'SRS', 'SR', 'REG-1'];
                        // Check if the key is in the exceptKeys array or contains "Mill"
                        // dd($key4);
                        $new_key = $value2['nama'];
                        if (!in_array($key, $exceptKeys) && strpos($new_key, 'Mill') === false) {
                            // If it doesn't meet any of these conditions and doesn't end with "Mill", add '-EST'
                            $keyToAdd = $key . '-EST';
                        } else {
                            // Otherwise, keep the key as it is
                            $keyToAdd = $key;
                        }
                        $lingkuganOri[$value2['nama']][$keyToAdd][$key3] = $value4;
                    }
                }
            }
        }

        // dd($lingkuganOri);

        $cmbLingkungan = combineItemsByDatetime($lingkuganOri);
        // dd($cmbLingkungan);
        $new_lkng = array();

        foreach ($cmbLingkungan as $key => $value) {
            foreach ($value as $key1 => $value1) {
                foreach ($value1 as $key2 => $value2) {
                    if (is_array($value2)) {
                        foreach ($value2 as $key3 => $value3) {
                            $avg_nilai = 0;

                            if (is_array($value3)) {
                                $totalNilai = 0;
                                $nilaiKeys = [];

                                foreach ($value3 as $innerKey => $innerValue) {
                                    if (strpos($innerKey, 'nilai') === 0) {
                                        $nilaiKeys[] = $innerKey;
                                        $nilaiValues = array_map('intval', explode('$', $innerValue));
                                        $totalNilai += array_sum($nilaiValues);
                                    }
                                }

                                $dividen = count($nilaiKeys);
                                if ($dividen !== 0) {
                                    $avg_nilai = round($totalNilai / $dividen);
                                } else {
                                    $avg_nilai = $totalNilai;
                                }

                                $date = $value3['datetime1'];
                                $yearMonth = date('Y-m-d', strtotime($date));

                                $new_lkng[$key][$key1][$key2][$key3]['nilai_total_Lngkl'] = $avg_nilai;
                                $new_lkng[$key][$key1][$key2][$key3]['total_nilai_Lngkl'] = $totalNilai;
                                $new_lkng[$key][$key1][$key2][$key3]['dividen_Lngkl'] = $dividen;
                                $new_lkng[$key][$key1][$key2][$key3]['date_Lngkl'] = $yearMonth;
                                $new_lkng[$key][$key1][$key2][$key3]['est_Lngkl'] = $value3['est1'];
                                $new_lkng[$key][$key1][$key2][$key3]['afd_Lngkl'] = $value3['afd1'];
                            }
                        }
                    } else {
                        // dd($bulan);
                        $tahun = $request->input('tahun');


                        $parts = explode('-', $key1);

                        $part1 = isset($parts[0]) ? $parts[0] : '';
                        $part2 = isset($parts[1]) ? $parts[1] : '';

                        $arrDef = [
                            'nilai_total_Lngkl' => 0,
                            'total_nilai_Lngkl' => 0,
                            'dividen_Lngkl' => 0,
                            'date_Lngkl' => $tahun, // Make sure $tahun is defined somewhere
                            'est_Lngkl' => $part1,
                            'afd_Lngkl' => $part2,
                        ];
                        $new_lkng[$key][$key1][$key2][0] = $arrDef;
                    }
                }
            }
        }


        // dd($new_lkng);

        // dd($FinalArr_LK);

        $FinalArr_rumah = array();
        foreach ($new_rumah as $key1 => $value1) {
            foreach ($value1 as $key2 => $value2) if (is_array($value2)) {
                foreach ($value2 as $key3 => $value3) {
                    if (is_array($value3)) {
                        foreach ($value3 as $value4) {
                            $FinalArr_rumah[$key1][$key2][$key3][$value4['date']] = $value4;
                        }
                    }
                }
            }
        }

        // dd($new_lkng);
        $FinalArr_LK = array();
        foreach ($new_lkng as $key1 => $value1) {
            foreach ($value1 as $key2 => $value2) if (is_array($value2)) {
                foreach ($value2 as $key3 => $value3) {
                    if (is_array($value3)) {
                        foreach ($value3 as $value4) {
                            $FinalArr_LK[$key1][$key2][$key3][$value4['date_Lngkl']] = $value4;
                        }
                    }
                }
            }
        }

        $FinalArr_LS = array();
        foreach ($new_lcp as $key1 => $value1) {
            foreach ($value1 as $key2 => $value2) if (is_array($value2)) {
                foreach ($value2 as $key3 => $value3) {
                    if (is_array($value3)) {
                        foreach ($value3 as $value4) {
                            $FinalArr_LS[$key1][$key2][$key3][$value4['date_LP']] = $value4;
                        }
                    }
                }
            }
        }

        // dd($FinalArr_LS);

        function mergeArray_est($arr1, $arr2, $arr3)
        {
            $allKeys = array_unique(array_merge(array_keys($arr1), array_keys($arr2), array_keys($arr3)));
            $result = [];

            foreach ($allKeys as $key) {
                $result[$key] = mergeArraysRecursive_est($arr1[$key] ?? [], $arr2[$key] ?? [], $arr3[$key] ?? []);
            }

            return $result;
        }

        function mergeArraysRecursive_est(...$arrays)
        {
            $result = [];
            foreach ($arrays as $array) {
                if (is_array($array)) {
                    foreach ($array as $key => $value) {
                        if (is_array($value)) {
                            $result[$key] = mergeArraysRecursive_est($result[$key] ?? [], $value);
                        } else {
                            $result[$key] = $value ?? 0;
                        }
                    }
                }
            }

            return $result;
        }


        $result = mergeArray_est($FinalArr_rumah, $FinalArr_LK, $FinalArr_LS);


        // dd($FinalArr_rumah, $FinalArr_LK, $FinalArr_LS);

        //  delete index with 0 value 
        foreach ($result as $key => $months) {
            foreach ($months as $month => $value) {
                if (is_array($value)) {
                    // Check if the value is an array
                    $filteredValues = array_filter($value, function ($item) {
                        return $item !== 0; // Filter out entries with value 0
                    });

                    // Reassign the filtered array to the original array
                    $result[$key][$month] = $filteredValues;
                }
            }
        }

        // dd($result);
        $queryAsisten =  DB::connection('mysql2')->Table('asisten_qc')->get();

        $queryAsisten = json_decode($queryAsisten, true);
        // Now $result will have the entries with value 0 removed from nested arrays
        $final_result = array();

        foreach ($result as $key => $value) {
            foreach ($value as $key1 => $value1) if (is_array($value1)) {
                foreach ($value1 as $key2 => $value2) if (is_array($value2)) {
                    $inc = 1;
                    if (is_array($value2)) {
                        foreach ($value2 as $key3 => $value3) {
                            // dd($value3);
                            $EM = 'GM';
                            $nama = '-';
                            foreach ($queryAsisten as $value4) {
                                if ($value4['est'] == ($value3['est'] ?? $value3['est_LP'] ??  $value3['est_Lngkl'] ?? null) && $value4['afd'] == $key2) {
                                    $nama = $value4['nama'];
                                    break;
                                }
                            }
                            $total_skor = ($value3['nilai_total'] ?? 0) + ($value3['nilai_total_LP'] ?? 0) + ($value3['nilai_total_Lngkl'] ?? 0);
                            $final_result[$key][$key1][$key2][$key3]['skor_total'] = $total_skor;
                            $final_result[$key][$key1][$key2][$key3]['visit'] = $inc++;
                            $final_result[$key][$key1][$key2][$key3]['est'] = $value3['est'] ?? $value3['est_LP'] ??  $value3['est_Lngkl'] ?? null;
                            $final_result[$key][$key1][$key2][$key3]['afd'] = $key2;
                            $final_result[$key][$key1][$key2][$key3]['asisten'] = $nama;
                            $final_result[$key][$key1][$key2][$key3]['date'] = $key3;
                        }

                        // dd($value1);
                    }
                }
            } else {

                $final_result[$key][$key1] = $value1;
            }
        }
        $test = array();

        // dd($final_result);
        foreach ($final_result as $key => $value) {
            foreach ($value as $key3 => $value3) {
                foreach ($value3 as $key4 => $value4) {
                    foreach ($filteredArray as $key2 => $value2) if ($key == $value2['nama']) {

                        $test[$value2['nama']][$key4][$key3] = $value4;
                    }
                }
            }
        }
        // dd($test);

        $avarage = array();

        foreach ($test as $estate => $months) {
            $countMth = 0; // Initialize the month count with zero
            $est_avg = 0;
            $avg_tod = 0;
            foreach ($months as $month => $value) {
                if (is_array($value)) {
                    // If the month has data, increase the count
                    $countMth++;
                    $total_avg = 0;
                    foreach ($value as $key2 => $value2) {
                        $count = count($value2);
                        $total_val = 0;
                        $avg = 0;
                        foreach ($value2 as $key3 => $value3) {
                            // dd($value3);
                            $total_val += $value3['skor_total'];
                        }
                        $avg = round($total_val / $count, 1);

                        $avarage[$estate][$month][$key2]['test_tod'] = $count;
                        $avarage[$estate][$month][$key2]['total_nilai'] = $total_val;
                        $avarage[$estate][$month][$key2]['est'] = $value3['est'];
                        $avarage[$estate][$month][$key2]['afd'] = $value3['afd'];
                        $avarage[$estate][$month][$key2]['rata_rata'] = $avg;

                        $total_avg += $avg;
                    }

                    $avarage[$estate][$month]['tot_avg'] = $total_avg;

                    $est_avg += $total_avg;
                }
            }

            $avg_tod = round($est_avg / $countMth, 1);
            $avarage[$estate]['tot_avg'] = $est_avg;
            $avarage[$estate]['bulan'] = $countMth;
            $avarage[$estate]['est'] = $value3['est'];
            $avarage[$estate]['afd'] = $value3['afd'];
            $avarage[$estate]['avg'] = $avg_tod;
        }
        // "est" => "BKE"
        // "afd" => "BKE-EST"
        // "avg" => 28.5
        // dd($avarage);
        $extractedData = [];
        foreach ($avarage as $estate => $data) {
            $extractedData[$estate]['est'] = $data['est'];
            $extractedData[$estate]['afd'] = $data['afd'];
            $extractedData[$estate]['avg'] = $data['avg'];
        }

        // dd($test);

        // Now $avarage array contains the counts of months with data for each estate

        // dd($avarage, $extractedData);


        $resultArray = [];

        // Loop through the original array
        foreach ($test as $estate => $months) {
            // Initialize the data for the estate and afdeling
            $resultArray[$estate] = [];

            foreach ($months as $month => $afdelings) if (is_array($afdelings)) {
                // Initialize the data for the afdeling
                foreach ($afdelings as $afdeling => $data) {
                    $resultArray[$estate][$afdeling][$month] = $data;
                }

                // Handle the default months with value 0
                $monthsList = [
                    "January", "February", "March", "April", "May", "June", "July", "August",
                    "September", "October", "November", "December"
                ];

                foreach ($monthsList as $defaultMonth) {
                    if (!isset($resultArray[$estate][$afdeling][$defaultMonth])) {
                        $resultArray[$estate][$afdeling][$defaultMonth] = 0;
                    }
                }
            }
        }
        // dd($resultArray);


        foreach ($resultArray as $estate => $months) {
            foreach ($months as $afdeling => $data) {
                foreach ($data as $month => $visits) {
                    if (is_array($visits)) {
                        $visitsWithoutDates = []; // Array to store visit data without individual dates
                        foreach ($visits as $date => $visitData) {
                            if (is_array($visitData)) { // Check if $visitData is an array (not an individual date)
                                $visitKey = 'visit' . (count($visitsWithoutDates) + 1);
                                $visitsWithoutDates[$visitKey] = $visitData;
                            }
                        }
                        // dd($visitData);

                        // Add default visit data for months with no visits (empty $visitsWithoutDates)
                        if (empty($visitsWithoutDates)) {
                            $visitsWithoutDates['visit1'] = [
                                'skor_total' => 0,
                                'visit' => 1,
                                'est' => $estate,
                                'afd' => $afdeling,
                                'date' => '-',
                            ];
                        }

                        // After the loop, set the resultArray to the updated visits without dates
                        $resultArray[$estate][$afdeling][$month] = $visitsWithoutDates;
                    } else {
                        $nama = '-';
                        foreach ($queryAsisten as $value4) {
                            if ($value4['est'] == $estate && $value4['afd'] == $afdeling) {
                                $nama = $value4['nama'];
                                break;
                            }
                        }
                        $resultArray[$estate][$afdeling][$month] = [
                            'visit1' => [
                                'skor_total' => 0,
                                'visit' => 1,
                                'est' => $estate,
                                'afd' => $afdeling,
                                'asisten' => $nama,
                                'date' => '-'
                            ]
                        ];
                    }
                }
            }
        }
        // dd($resultArray);




        $result_skor = array();

        foreach ($resultArray as $key => $value) {
            foreach ($value as $key1 => $value2) {
                foreach ($value2 as $key2 => $value3) {
                    $tot_skor = 0;
                    $avarage = count($value3);
                    $tot_avg = 0;
                    foreach ($value3 as $key3 => $value4) {
                        if ($value4['skor_total'] != 0) {
                            $tot_skor += $value4['skor_total'];
                        } else {
                            $tot_skor = 0;
                        }
                    }
                    $tot_avg = round($tot_skor / $avarage, 2);
                    $result_skor[$key][$key1][$key2]['total_avg'] = $tot_avg;
                } # code...
            }   # code...
        }

        // Assuming $array1 and $array2 are the two arrays you have



        // dd($result_skor, $resultArray);
        // The $array2 now contains the combined values


        // dd($resultArray, $result_skor);

        $get_cell = array();
        foreach ($resultArray as $key => $value) {
            foreach ($value as $key1 => $value1) {
                foreach ($value1 as $key2 => $value2) {
                    $visit = count($value2);
                    $get_cell[$key2][$key][$key1]['visit'] = $visit;
                }
            }
        }

        // dd($get_cell);

        $max_visit = array();
        foreach ($get_cell as $month => $monthData) {
            $max_visitEst = 0;
            foreach ($monthData as $location => $locationData) {
                $maxVisit = 0;
                foreach ($locationData as $visitData) {
                    // Check if the current visit value is greater than the current maximum visit
                    if (isset($visitData['visit']) && $visitData['visit'] > $maxVisit) {
                        // Update the maximum visit for this location
                        $maxVisit = $visitData['visit'];
                    }
                }
                // Add the maximum visit for this location to the $max_visit array
                $max_visit[$month][$location]['max_visit'] = $maxVisit;

                // Calculate the overall maximum visit for the entire array
                if ($maxVisit > $max_visitEst) {
                    $max_visitEst = $maxVisit;
                }
            }
            // Add the overall maximum visit for this month to the $max_visit array
            $max_visit[$month]['max_visitEst'] = $max_visitEst;
        }

        // Create an array with the correct order of months
        $allMonths = array(
            "January", "February", "March", "April", "May", "June", "July",
            "August", "September", "October", "November", "December"
        );

        // Initialize the $header_cell array with all months set to 1 visit
        $header_cell = array_fill_keys($allMonths, 1);

        // Update the visits count based on the $max_visit array
        foreach ($max_visit as $key => $value) {
            if (in_array($key, $allMonths)) {
                $header_cell[$key] = $value['max_visitEst'];
            }
        }
        $header_cell2 = array_fill_keys($allMonths, 1);
        // dd($max_visit);
        // Update the visits count based on the $max_visit array
        // Check if $max_visit is empty
        if (empty($max_visit)) {
            // If it's empty, update $header_cell2 for all months
            foreach ($allMonths as $key) {
                $header_cell2[$key] = 2;
            }
        } else {
            // If it's not empty, update $header_cell2 based on $max_visit
            foreach ($max_visit as $key => $value) {
                if (in_array($key, $allMonths)) {
                    $header_cell2[$key] = $value['max_visitEst'] + 1;
                }
            }
        }


        // dd($header_cell2);

        function createEmptyVisit2($visitNumber)
        {
            return [
                "skor_total" => 0,
                "visit" => $visitNumber,
                "est" => "Kenambui", // Replace with the appropriate value
                "afd" => "OB", // Replace with the appropriate value
                "asisten" => "-",
                "date" => "-",
            ];
        }



        // dd($resultArray);
        $sum_header = [
            "head" => array_sum($header_cell2) + 2,
        ];
        // dd($header_cell, $sum_header);
        // dd($resultArray);


        function generateArray($inputArray)
        {
            $resultArray = [];

            foreach ($inputArray as $month => $value) {
                for ($i = 1; $i <= $value; $i++) {
                    $resultArray[] = $i;
                }
                $resultArray[] = 'Skor';
            }

            return $resultArray;
        }
        // dd($resultArray);


        // Function to adjust the last element to "Skor" in each month's section
        function adjustLastElementToSkor($inputArray)
        {
            $resultArray = [];

            foreach ($inputArray as $key => $value) {
                if ($value !== 'Skor') {
                    if (isset($inputArray[$key + 1]) && $inputArray[$key + 1] === 'Skor') {
                        $resultArray[] = 'Skor';
                    } else {
                        $resultArray[] = $value;
                    }
                }
            }

            return $resultArray;
        }
        // dd($resultArray);
        // Generate the array with numeric values and "Skor" as the last element for each month
        $numericSkorArray = generateArray($header_cell);

        // Adjust the last element to "Skor" for each month's section
        $desiredArray = adjustLastElementToSkor($numericSkorArray);

        // dd($desiredArray, $numericSkorArray);
        // dd($numericSkorArray, $desiredArray);
        // dd($result_skor);
        foreach ($result_skor as $key1 => $value1) {
            if (array_key_exists($key1, $resultArray)) {
                // Merge the sub-arrays for the common key
                $resultArray[$key1] = array_merge_recursive($resultArray[$key1], $value1);
            } else {
                // Add the new key and its value to the $resultArray
                $resultArray[$key1] = $value1;
            }
        }

        // dd($resultArray);

        $new_array = array();
        $inc = 1;

        // dd($resultArray);

        // Initialize an associative array to store 'skor_total_array' values for each month
        $skor_total_per_month = array();

        foreach ($resultArray as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $avg_per_month = array(); // Initialize an associative array to store 'total_avg' values for each month
                $max_values = max($header_cell); // Get the maximum number of values for any month from the $header_cell array

                foreach ($value1 as $key2 => $value2) {
                    if (in_array($key2, $allMonths)) { // Assuming $allMonths contains all the months in the correct order
                        $skor_total_array = array();
                        $dates_per_month_array = array(); // Initialize an array to store dates for each visit
                        // Create an array to store 'skor_total' values for each visit
                        foreach ($value2 as $key3 => $value3) {
                            if (strpos($key3, 'visit') === 0 && isset($value3['skor_total'])) {
                                $skor_total_array[] = $value3['skor_total'];

                                $date_str = $value3['date'];
                                $year_month = substr($date_str, 0, 7); // Extract year and month (e.g., "2023-08")
                                $dates_per_month_array[] = $year_month;
                            }
                        }
                        $skor_total_per_month[$key2] = $skor_total_array;
                        $dates_per_month[$key2] = $dates_per_month_array;
                        $avg_per_month[$key2] = $value2['total_avg'];
                    }
                }

                // Now, use the $skor_total_per_month and $avg_per_month arrays to populate 'January', 'February', etc. in $new_array
                $new_row = array(
                    'no' => $inc++,
                    'unit_kerja' => $key,
                    'kode' => $key1,
                    'pic' => '-',
                );


                foreach ($allMonths as $month) {
                    $values = $skor_total_per_month[$month] ?? array();
                    $dates = $dates_per_month[$month] ?? array();
                    $avg = $avg_per_month[$month] ?? 0;

                    // Check if the month is present in $header_cell and get its value
                    $headerValue = $header_cell[$month] ?? 1;

                    // Create an array with the appropriate number of indices based on $headerValue
                    $new_row[$month] = array_pad($values, $headerValue, 0);

                    $new_row[$month . '_avg'] = $avg;
                    $new_row[$month . '_dates'] = $dates; // Add the dates for the month
                }

                // Add the row to the $new_array
                $new_array[] = $new_row;
            }
        }




        // dd($resultArray, $new_array);
        // Return a JSON response
        $arrView = array();

        $arrView['reg'] =  $regional;
        $arrView['bulan'] =  $bulan;
        $arrView['afd_rekap'] =  $resultArray;
        $arrView['header_cell'] =  $header_cell2;
        $arrView['header_head'] =  $sum_header;
        $arrView['rata_rata'] =  $extractedData;
        $arrView['visit'] =  $numericSkorArray;
        $arrView['skoring'] =  $result_skor;
        $arrView['new_afd'] =  $new_array;


        echo json_encode($arrView); //di decode ke dalam bentuk json dalam vaiavel arrview yang dapat menampung banyak isi array
        exit();
    }

    public function detailEmplashmend($est,  $date)
    {

        // dd($est, $date);

        $new_date = $date;


        $prum_dates = DB::connection('mysql2')->table('perumahan')
            ->select(DB::raw('DATE(perumahan.datetime) as date'))
            ->where('perumahan.datetime', 'like', '%' . $date . '%')
            ->where('perumahan.est', $est)
            ->where('perumahan.afd', 'EST')
            ->groupBy(DB::raw('DATE(perumahan.datetime)'))
            ->get();

        $prum_dates = json_decode(json_encode($prum_dates), true);

        $lcp_dates = DB::connection('mysql2')->table('perumahan')
            ->select(DB::raw('DATE(perumahan.datetime) as date'))
            ->where('perumahan.datetime', 'like', '%' . $date . '%')
            ->where('perumahan.est', $est)
            ->where('perumahan.afd', 'EST')
            ->groupBy(DB::raw('DATE(perumahan.datetime)'))
            ->get();

        $lcp_dates = json_decode(json_encode($lcp_dates), true);

        $lkl_dates = DB::connection('mysql2')->table('lingkungan')
            ->select(DB::raw('DATE(lingkungan.datetime) as date'))
            ->where('lingkungan.datetime', 'like', '%' . $date . '%')
            ->where('lingkungan.est', $est)
            ->where('lingkungan.afd', 'EST')
            ->groupBy(DB::raw('DATE(lingkungan.datetime)'))
            ->get();

        $lkl_dates = json_decode(json_encode($lkl_dates), true);





        $prafd_dates = DB::connection('mysql2')->table('perumahan')
            ->select(DB::raw('DATE(perumahan.datetime) as date'))
            ->where('perumahan.datetime', 'like', '%' . $date . '%')
            // ->where('perumahan.est', $new_est)
            ->where('perumahan.est', $est)
            ->where('perumahan.afd', '!=', 'EST')
            ->groupBy(DB::raw('DATE(perumahan.datetime)'))
            ->get();

        $prafd_dates = json_decode(json_encode($prafd_dates), true);



        $lcafd_dates = DB::connection('mysql2')->table('landscape')
            ->select(DB::raw('DATE(landscape.datetime) as date'))
            ->where('landscape.datetime', 'like', '%' . $date . '%')
            // ->where('landscape.est', $new_est)
            ->where('landscape.est', $est)
            ->where('landscape.afd', '!=',  'EST')
            ->groupBy(DB::raw('DATE(landscape.datetime)'))
            ->get();

        $lcafd_dates = json_decode(json_encode($lcafd_dates), true);

        $lkafd_dates = DB::connection('mysql2')->table('lingkungan')
            ->select(DB::raw('DATE(lingkungan.datetime) as date'))
            ->where('lingkungan.datetime', 'like', '%' . $date . '%')
            ->where('lingkungan.est', $est)
            // ->where('lingkungan.est', $new_est)
            ->where('lingkungan.afd', '!=',  'EST')
            ->groupBy(DB::raw('DATE(lingkungan.datetime)'))
            ->get();

        $lkafd_dates = json_decode(json_encode($lkafd_dates), true);

        // dd($lcafd_dates);

        $combinedArray = array_merge(
            $prum_dates,
            $lcp_dates,
            $lkl_dates,
            $prafd_dates,
            $lcafd_dates,
            $lkafd_dates
        );

        // Extract the "date" values
        $dates = array_column($combinedArray, 'date');

        // Get unique dates
        $uniqueDates = array_unique($dates);

        // dd($uniqueDates);

        $listafd = DB::connection('mysql2')->table('afdeling')
            ->select('afdeling.*')
            ->join('estate', 'estate.id', '=', 'afdeling.estate')
            ->where('estate.est', '=', $est)
            ->pluck('nama');

        $listafd = json_decode($listafd, true);
        array_push($listafd, "EST");

        // dd($listafd);

        $dateString = $date;

        // Convert the string to a DateTime object
        $dateTimeObj = new DateTime($dateString);

        // Get the year and month from the DateTime object
        $yearAndMonth = $dateTimeObj->format('Y-m');
        // dd($pagianteafd_lk);
        $arrView = array();
        $arrView['est'] =  $est;
        $arrView['tanggal'] =  $yearAndMonth;
        $arrView['listafd'] =  $listafd;

        $arrView['date'] = $uniqueDates;
        return view('Perumahan.datailEmplashmend', $arrView);
    }


    public function getTemuan(Request $request)
    {
        $est = $request->input('estData');
        $tanggal = $request->input('tanggal');



        $emplacement = DB::connection('mysql2')->table('perumahan')
            ->select(
                "perumahan.*",
                DB::raw('DATE_FORMAT(perumahan.datetime, "%M") as bulan'),
                DB::raw('DATE_FORMAT(perumahan.datetime, "%Y") as tahun'),
            )
            ->where('perumahan.datetime', 'like', '%' . $tanggal . '%')
            ->where('perumahan.est', $est)
            ->where('perumahan.afd', 'EST')
            ->orderBy('est', 'asc')
            ->orderBy('afd', 'asc')
            ->orderBy('datetime', 'asc')
            ->get();

        $emplacement = json_decode(json_encode($emplacement), true); // Convert the collection to an array
        $emplacement = collect($emplacement)->groupBy(['est', 'afd'])->toArray();

        // dd($emplacement);
        $lingkungan = DB::connection('mysql2')->table('lingkungan')
            ->select(
                "lingkungan.*",
                DB::raw('DATE_FORMAT(lingkungan.datetime, "%M") as bulan'),
                DB::raw('DATE_FORMAT(lingkungan.datetime, "%Y") as tahun'),
            )
            ->where('lingkungan.datetime', 'like', '%' . $tanggal . '%')
            ->where('lingkungan.est', $est)
            ->where('lingkungan.afd', 'EST')
            ->orderBy('est', 'asc')
            ->orderBy('afd', 'asc')
            ->orderBy('datetime', 'asc')
            ->get();

        $lingkungan = json_decode(json_encode($lingkungan), true); // Convert the collection to an array
        $lingkungan = collect($lingkungan)->groupBy(['est', 'afd'])->toArray();

        // dd($lingkungan);
        $landscape = DB::connection('mysql2')->table('landscape')
            ->select(
                "landscape.*",
                DB::raw('DATE_FORMAT(landscape.datetime, "%M") as bulan'),
                DB::raw('DATE_FORMAT(landscape.datetime, "%Y") as tahun'),
            )
            ->where('landscape.datetime', 'like', '%' . $tanggal . '%')
            ->where('landscape.est', $est)
            ->where('landscape.afd', 'EST')
            ->orderBy('est', 'asc')
            ->orderBy('afd', 'asc')
            ->orderBy('datetime', 'asc')
            ->get();

        $landscape = json_decode(json_encode($landscape), true); // Convert the collection to an array
        $landscape = collect($landscape)->groupBy(['est', 'afd'])->toArray();

        // dd($landscape);
        // dd($date);
        // dd($emplacement, $landscape, $lingkungan);

        $hitungRmh = array();
        foreach ($emplacement as $key => $value) {
            foreach ($value as $key1 => $value2) {
                // Initialize the "nilai_total" for each month to 0
                $hitungRmh[$key][$key1] = [];
                if (is_array($value2)) {
                    foreach ($value2 as $key2 => $value3) {
                        if (is_array($value3)) {
                            $sumNilai = isset($value3['nilai']) ? array_sum(array_map('intval', explode('$', $value3['nilai']))) : 0;
                            // Store the sum in the "nilai_total" key of the corresponding index
                            $date = $value3['datetime'];
                            // Get the year and month from the datetime
                            $yearMonth = date('Y-m-d', strtotime($date));

                            $foto_temuan = explode('$', $value3['foto_temuan']);
                            $kom_temuan = explode('$', $value3['komentar_temuan']);
                            $nilai = explode('$', $value3['nilai']);
                            $komentar = explode('$', $value3['komentar']);



                            $hitungRmh[$key][$key1][$key2] = array_merge($value3, [
                                'nilai_total_Lngkl' => $sumNilai,
                                'date' => $yearMonth,
                                'est_afd' => $value3['est'] . '_' . $value3['afd'],
                            ]);

                            $apps = explode(';', $value3['app_version']);
                            $inc = 1;
                            $incc = 1;
                            if ($apps[0] == '1.5.32' || $apps[0] == '1.5.31' || $apps[0] == '1.5.30' || $apps[0] == '1.5.29') {
                                foreach ($foto_temuan as $keyx => $value) {
                                    foreach ($kom_temuan as $keyx2 => $value2) if ($keyx == $keyx2) {
                                        if ($value != '') {
                                            $hitungRmh[$key][$key1][$key2]['foto_temuan_rmh' . $inc++] = $value;
                                            $hitungRmh[$key][$key1][$key2]['komentar_temuan_rmh' . $incc++] = $value2;
                                        }
                                    }
                                }
                            } else {
                                foreach ($foto_temuan as $keyx => $value) {
                                    foreach ($komentar as $keyx2 => $value2) if ($keyx == $keyx2) {
                                        if ($value != '') {
                                            $hitungRmh[$key][$key1][$key2]['foto_temuan_rmh' . $inc++] = $value;
                                            $hitungRmh[$key][$key1][$key2]['komentar_temuan_rmh' . $incc++] = $value2;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        // dd($hitungRmh);


        $hitungLandscape = array();
        foreach ($landscape as $key => $value) {
            foreach ($value as $key1 => $value2) {
                // Initialize the "nilai_total" for each month to 0
                $hitungLandscape[$key][$key1] = [];

                if (is_array($value2)) {
                    foreach ($value2 as $key2 => $value3) {
                        if (is_array($value3)) {
                            $sumNilai = isset($value3['nilai']) ? array_sum(array_map('intval', explode('$', $value3['nilai']))) : 0;
                            // Store the sum in the "nilai_total" key of the corresponding index
                            $date = $value3['datetime'];
                            // Get the year and month from the datetime
                            $yearMonth = date('Y-m-d', strtotime($date));

                            $foto_temuan = explode('$', $value3['foto_temuan']);
                            $kom_temuan = explode('$', $value3['komentar_temuan']);
                            $nilai = explode('$', $value3['nilai']);
                            $komentar = explode('$', $value3['komentar']);
                            // dd($foto_temuan);

                            $hitungLandscape[$key][$key1][$key2] = array_merge($value3, [
                                'nilai_total_Lngkl' => $sumNilai,
                                'date' => $yearMonth,
                                'est_afd' => $value3['est'] . '_' . $value3['afd'],
                            ]);


                            $apps = explode(';', $value3['app_version']);
                            $inc = 1;
                            $incc = 1;
                            if ($apps[0] == '1.5.32' || $apps[0] == '1.5.31' || $apps[0] == '1.5.30' || $apps[0] == '1.5.29') {
                                foreach ($foto_temuan as $keyx => $value) {
                                    foreach ($kom_temuan as $keyx2 => $value2) if ($keyx == $keyx2) {
                                        if ($value != '') {
                                            $hitungLandscape[$key][$key1][$key2]['foto_temuan_ls' . $inc++] = $value;
                                            $hitungLandscape[$key][$key1][$key2]['komentar_temuan_ls' . $incc++] = $value2;
                                        }
                                    }
                                }
                            } else {
                                foreach ($foto_temuan as $keyx => $value) {
                                    foreach ($komentar as $keyx2 => $value2) if ($keyx == $keyx2) {
                                        if ($value != '') {
                                            $hitungLandscape[$key][$key1][$key2]['foto_temuan_ls' . $inc++] = $value;
                                            $hitungLandscape[$key][$key1][$key2]['komentar_temuan_ls' . $incc++] = $value2;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        // dd($hitungLandscape);

        $hitungLingkungan = array();

        foreach ($lingkungan as $key => $value) {
            foreach ($value as $key1 => $value2) {
                // Initialize the "nilai_total" for each month to 0
                $hitungLingkungan[$key][$key1] = [];

                if (is_array($value2)) {
                    foreach ($value2 as $key2 => $value3) {
                        if (is_array($value3)) {
                            $sumNilai = isset($value3['nilai']) ? array_sum(array_map('intval', explode('$', $value3['nilai']))) : 0;
                            // Store the sum in the "nilai_total" key of the corresponding index
                            $date = $value3['datetime'];
                            // Get the year and month from the datetime
                            $yearMonth = date('Y-m-d', strtotime($date));

                            $foto_temuan = explode('$', $value3['foto_temuan']);
                            $kom_temuan = explode('$', $value3['komentar_temuan']);
                            $nilai = explode('$', $value3['nilai']);
                            $komentar = explode('$', $value3['komentar']);

                            // dd($lingkungan);

                            $hitungLingkungan[$key][$key1][$key2] = array_merge($value3, [
                                'nilai_total_Lngkl' => $sumNilai,
                                'date' => $yearMonth,
                                'est_afd' => $value3['est'] . '_' . $value3['afd'],
                            ]);


                            $apps = explode(';', $value3['app_version']);
                            $inc = 1;
                            $incc = 1;
                            if ($apps[0] == '1.5.32' || $apps[0] == '1.5.31' || $apps[0] == '1.5.30' || $apps[0] == '1.5.29') {
                                foreach ($foto_temuan as $keyx => $value) {
                                    foreach ($kom_temuan as $keyx2 => $value2) if ($keyx == $keyx2) {
                                        if ($value != '') {
                                            $hitungLingkungan[$key][$key1][$key2]['foto_temuan_ll' . $inc++] = $value;
                                            $hitungLingkungan[$key][$key1][$key2]['komentar_temuan_ll' . $incc++] = $value2;
                                        }
                                    }
                                }
                            } else {
                                foreach ($foto_temuan as $keyx => $value) {
                                    foreach ($komentar as $keyx2 => $value2) if ($keyx == $keyx2) {
                                        if ($value != '') {
                                            $hitungLingkungan[$key][$key1][$key2]['foto_temuan_ll' . $inc++] = $value;
                                            $hitungLingkungan[$key][$key1][$key2]['komentar_temuan_ll' . $incc++] = $value2;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        // dd($hitungLingkungan);

        // dd($hitungRmh, $hitungLandscape, $hitungLingkungan);

        $new_Lscp = array();

        foreach ($hitungLandscape as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $index = 0; // Initialize index here
                foreach ($value1 as $key2 => $value2) {
                    $index++; // Increment the index inside the loop

                    // Group data based on "rmhX" keys
                    $number = 1;
                    $groupedData = array();
                    $numberOfFotoTemuanLsKeys = 0; // Initialize the counter for foto_temuan_ls keys
                    foreach ($value2 as $key3 => $value3) {
                        if (strpos($key3, 'foto_temuan_ls') === 0) {
                            $numberOfFotoTemuanLsKeys++;
                        }
                    }




                    if (!isset($value2['foto_temuan_ls1']) || empty($value2['foto_temuan_ls1'])) {
                        // If the key foto_temuan_ls1 does not exist or is empty, then the value of $new_Lscp[$key][$key1][$key2] should be empty.
                        $new_Lscp[$key][$key1][$key2] = '';
                    } else {
                        // If the key foto_temuan_ls1 exists and is not empty, then the code you have already written will be executed.
                        for ($i = 1; $i <= $numberOfFotoTemuanLsKeys; $i++) {
                            $number++;
                            $groupedData[] = array(
                                "foto_temuan_ls" => $value2["foto_temuan_ls" . $i],
                                "komentar_temuan_ls" => $value2["komentar_temuan_ls" . $i],
                                // "komentar_ls" => $value2["komentar_ls" . $i],
                                "title" => $value2["est"] . "-" . $value2["afd"],
                                "id" => $value2["id"]
                            );
                        }

                        $new_Lscp[$key][$key1][$key2] = $groupedData;
                    }
                }
            }
        }

        // dd($new_Lscp);
        $new_Rmh = array();
        // dd($hitungRmh);
        foreach ($hitungRmh as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $index = 0; // Initialize index here
                foreach ($value1 as $key2 => $value2) {
                    $index++; // Increment the index inside the loop
                    // dd($value2);
                    // Group data based on "rmhX" keys
                    $number = 1;
                    $groupedData = array();
                    $numberOfFotoTemuanLsKeys = 0; // Initialize the counter for foto_temuan_ls keys
                    foreach ($value2 as $key3 => $value3) {
                        if (strpos($key3, 'foto_temuan_rmh') === 0) {
                            $numberOfFotoTemuanLsKeys++;
                        }
                    }


                    if (!isset($value2['foto_temuan_rmh1']) || empty($value2['foto_temuan_rmh1'])) {
                        // If the key foto_temuan_ls1 does not exist or is empty, then the value of $new_Lscp[$key][$key1][$key2] should be empty.
                        $new_Rmh[$key][$key1][$key2] = '';
                    } else {
                        // If the key foto_temuan_ls1 exists and is not empty, then the code you have already written will be executed.
                        for ($i = 1; $i <= $numberOfFotoTemuanLsKeys; $i++) {
                            $number++;
                            $groupedData[] = array(
                                "foto_temuan_rmh" => $value2["foto_temuan_rmh" . $i],
                                "komentar_temuan_rmh" => $value2["komentar_temuan_rmh" . $i],
                                // "komentar_rmh" => $value2["komentar_temuan_rmh" . $i],
                                "title" => $value2["est"] . "-" . $value2["afd"],
                                "id" => $value2["id"]

                            );
                        }

                        $new_Rmh[$key][$key1][$key2] = $groupedData;
                    }

                    // Now, use the incremented index to create new arrays with desired keys and values

                }
            }
        }

        // dd($new_Rmh);
        $new_lkngan = array();

        foreach ($hitungLingkungan as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $index = 0; // Initialize index here
                foreach ($value1 as $key2 => $value2) {
                    $index++; // Increment the index inside the loop

                    // Group data based on "rmhX" keys
                    $number = 1;
                    $groupedData = array();
                    $numberOfFotoTemuanLsKeys = 0; // Initialize the counter for foto_temuan_ls keys
                    foreach ($value2 as $key3 => $value3) {
                        if (strpos($key3, 'foto_temuan_ll') === 0) {
                            $numberOfFotoTemuanLsKeys++;
                        }
                    }



                    if (!isset($value2['foto_temuan_ll1']) || empty($value2['foto_temuan_ll1'])) {
                        // If the key foto_temuan_ls1 does not exist or is empty, then the value of $new_Lscp[$key][$key1][$key2] should be empty.
                        $new_lkngan[$key][$key1][$key2] = '';
                    } else {
                        // If the key foto_temuan_ls1 exists and is not empty, then the code you have already written will be executed.
                        for ($i = 1; $i <= $numberOfFotoTemuanLsKeys; $i++) {
                            $number++;
                            $groupedData[] = array(
                                "foto_temuan_ll" => $value2["foto_temuan_ll" . $i],
                                "komentar_temuan_ll" => $value2["komentar_temuan_ll" . $i],
                                // "komentar_ll" => $value2["komentar_ll" . $i],
                                "title" => $value2["est"] . "-" . $value2["afd"],
                                "id" => $value2["id"]
                            );
                        }

                        $new_lkngan[$key][$key1][$key2] = $groupedData;
                    }
                }
            }
        }


        // Output the result

        // dd($new_Rmh);
        // dd($new_lkngan, $hitungLingkungan);
        $new_Rmh_result = array();

        foreach ($new_Rmh as $key => $value) if (is_array($value)) {
            foreach ($value as $key1 => $value1) if (is_array($value1)) {
                $merged_values = array();
                foreach ($value1 as $key2 => $value2) if (is_array($value2)) {
                    // Merge all the nested arrays into a single array
                    $merged_values = array_merge($merged_values, $value2);
                }
                // Add the merged array to the result
                $new_Rmh_result[$key][$key1] = $merged_values;
            }
            // Unset the unnecessary level of the array
            $new_Rmh_result[$key][$key1] = $new_Rmh_result[$key][$key1];
        }
        // dd($new_Rmh_result);

        $new_Lscp_result = array();

        foreach ($new_Lscp as $key => $value) if (is_array($value)) {
            foreach ($value as $key1 => $value1) if (is_array($value1)) {
                $merged_values = array();
                foreach ($value1 as $key2 => $value2) if (is_array($value2)) {
                    // Merge all the nested arrays into a single array
                    $merged_values = array_merge($merged_values, $value2);
                }
                // Add the merged array to the result
                $new_Lscp_result[$key][$key1] = $merged_values;
            }
            // Unset the unnecessary level of the array
            $new_Lscp_result[$key][$key1] = $new_Lscp_result[$key][$key1];
        }
        $new_lkngan_result = array();

        foreach ($new_lkngan as $key => $value) if (is_array($value)) {
            foreach ($value as $key1 => $value1) if (is_array($value1)) {
                $merged_values = array();
                foreach ($value1 as $key2 => $value2) if (is_array($value2)) {
                    // Merge all the nested arrays into a single array
                    $merged_values = array_merge($merged_values, $value2);
                }
                // Add the merged array to the result
                $new_lkngan_result[$key][$key1] = $merged_values;
            }
            // Unset the unnecessary level of the array
            $new_lkngan_result[$key][$key1] = $new_lkngan_result[$key][$key1];
        }



        // pagination perumahan 
        // dd($new_Rmh_result);
        $mergedArray_rmh = [];
        foreach ($new_Rmh_result as $key1 => $value1) {
            foreach ($value1 as $key2 => $value2) {
                $mergedArray_rmh = array_merge($mergedArray_rmh, $value2);
            }
        }

        // dd($mergedArray_rmh);
        $mergedArray_lscp = [];
        foreach ($new_Lscp_result as $key1 => $value1) {
            foreach ($value1 as $key2 => $value2) {
                $mergedArray_lscp = array_merge($mergedArray_lscp, $value2);
            }
        }
        $mergedArray_lkngn = [];
        foreach ($new_lkngan_result as $key1 => $value1) {
            foreach ($value1 as $key2 => $value2) {
                $mergedArray_lkngn = array_merge($mergedArray_lkngn, $value2);
            }
        }


        $prumahan_afd  = DB::connection('mysql2')->table('perumahan')
            ->select(
                "perumahan.*",
                DB::raw('DATE_FORMAT(perumahan.datetime, "%M") as bulan'),
                DB::raw('DATE_FORMAT(perumahan.datetime, "%Y") as tahun'),
            )
            ->where('perumahan.datetime', 'like', '%' . $tanggal . '%')
            // ->where('perumahan.est', $new_est)
            ->where('perumahan.est', $est)
            ->where('perumahan.afd', '!=', 'EST')
            ->orderBy('est', 'asc')
            ->orderBy('afd', 'asc')
            ->orderBy('datetime', 'asc')
            ->get();

        $prumahan_afd  = json_decode(json_encode($prumahan_afd), true); // Convert the collection to an array
        $prumahan_afd  = collect($prumahan_afd)->groupBy(['est', 'afd'])->toArray();

        $lingkungan_afd = DB::connection('mysql2')->table('lingkungan')
            ->select(
                "lingkungan.*",
                DB::raw('DATE_FORMAT(lingkungan.datetime, "%M") as bulan'),
                DB::raw('DATE_FORMAT(lingkungan.datetime, "%Y") as tahun'),
            )
            ->where('lingkungan.datetime', 'like', '%' . $tanggal . '%')
            // ->where('lingkungan.est', $new_est)
            ->where('lingkungan.est', $est)
            ->where('lingkungan.afd', '!=', 'EST')
            ->orderBy('est', 'asc')
            ->orderBy('afd', 'asc')
            ->orderBy('datetime', 'asc')
            ->get();

        $lingkungan_afd = json_decode(json_encode($lingkungan_afd), true); // Convert the collection to an array
        $lingkungan_afd = collect($lingkungan_afd)->groupBy(['est', 'afd'])->toArray();


        $landscape_afd = DB::connection('mysql2')->table('landscape')
            ->select(
                "landscape.*",
                DB::raw('DATE_FORMAT(landscape.datetime, "%M") as bulan'),
                DB::raw('DATE_FORMAT(landscape.datetime, "%Y") as tahun'),
            )
            ->where('landscape.datetime', 'like', '%' . $tanggal . '%')
            // ->where('landscape.est', $new_est)
            ->where('landscape.est', $est)
            ->where('landscape.afd', '!=', 'EST')
            ->orderBy('est', 'asc')
            ->orderBy('afd', 'asc')
            ->orderBy('datetime', 'asc')
            ->get();

        $landscape_afd = json_decode(json_encode($landscape_afd), true); // Convert the collection to an array
        $landscape_afd = collect($landscape_afd)->groupBy(['est', 'afd'])->toArray();


        // dd($prumahan_afd, $landscape_afd, $lingkungan_afd);

        $hitungRmh_afd = array();
        foreach ($prumahan_afd as $key => $value) {
            foreach ($value as $key1 => $value2) {
                // Initialize the "nilai_total" for each month to 0
                $hitungRmh_afd[$key][$key1] = [];

                if (is_array($value2)) {
                    foreach ($value2 as $key2 => $value3) {
                        if (is_array($value3)) {
                            $sumNilai = isset($value3['nilai']) ? array_sum(array_map('intval', explode('$', $value3['nilai']))) : 0;
                            // Store the sum in the "nilai_total" key of the corresponding index
                            $date = $value3['datetime'];
                            // Get the year and month from the datetime
                            $yearMonth = date('Y-m-d', strtotime($date));

                            $foto_temuan = explode('$', $value3['foto_temuan']);
                            $kom_temuan = explode('$', $value3['komentar_temuan']);
                            $nilai = explode('$', $value3['nilai']);
                            $komentar = explode('$', $value3['komentar']);



                            $hitungRmh_afd[$key][$key1][$key2] = array_merge($value3, [
                                'nilai_total_rmh' => $sumNilai,
                                'date' => $yearMonth,
                                'est_afd' => $value3['est'] . '_' . $value3['afd'],
                            ]);


                            $apps = explode(';', $value3['app_version']);
                            $inc = 1;
                            $incc = 1;
                            if ($apps[0] == '1.5.32' || $apps[0] == '1.5.31' || $apps[0] == '1.5.30' || $apps[0] == '1.5.29') {
                                foreach ($foto_temuan as $keyx => $value) {
                                    foreach ($kom_temuan as $keyx2 => $value2) if ($keyx == $keyx2) {
                                        if ($value != '') {
                                            $hitungRmh_afd[$key][$key1][$key2]['foto_temuan_rmh' . $inc++] = $value;
                                            $hitungRmh_afd[$key][$key1][$key2]['komentar_temuan_rmh' . $incc++] = $value2;
                                        }
                                    }
                                }
                            } else {
                                foreach ($foto_temuan as $keyx => $value) {
                                    foreach ($komentar as $keyx2 => $value2) if ($keyx == $keyx2) {
                                        if ($value != '') {
                                            $hitungRmh_afd[$key][$key1][$key2]['foto_temuan_rmh' . $inc++] = $value;
                                            $hitungRmh_afd[$key][$key1][$key2]['komentar_temuan_rmh' . $incc++] = $value2;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }



        // dd($hitungRmh);



        $new_Rmh_afd = array();

        foreach ($hitungRmh_afd as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $index = 0; // Initialize index here
                foreach ($value1 as $key2 => $value2) {
                    $index++; // Increment the index inside the loop

                    // Group data based on "rmhX" keys
                    $number = 1;
                    $groupedData = array();
                    $numberOfFotoTemuanLsKeys = 0; // Initialize the counter for foto_temuan_ls keys
                    foreach ($value2 as $key3 => $value3) {
                        if (strpos($key3, 'foto_temuan_rmh') === 0) {
                            $numberOfFotoTemuanLsKeys++;
                        }
                    }




                    if (!isset($value2['foto_temuan_rmh1']) || empty($value2['foto_temuan_rmh1'])) {
                        // If the key foto_temuan_ls1 does not exist or is empty, then the value of $new_Lscp[$key][$key1][$key2] should be empty.
                        $new_Rmh_afd[$key][$key1][$key2] = '';
                    } else {
                        // If the key foto_temuan_ls1 exists and is not empty, then the code you have already written will be executed.
                        for ($i = 1; $i <= $numberOfFotoTemuanLsKeys; $i++) {
                            $number++;
                            $groupedData[] = array(
                                "foto_temuan_rmh" => $value2["foto_temuan_rmh" . $i],
                                "komentar_temuan_rmh" => $value2["komentar_temuan_rmh" . $i],
                                // "komentar_rmh" => $value2["komentar_temuan_rmh" . $i],
                                "title" => $value2["est"] . "-" . $value2["afd"],
                                "id" => $value2["id"]

                            );
                        }

                        $new_Rmh_afd[$key][$key1][$key2] = $groupedData;
                    }
                }
            }
        }

        // dd($new_Rmh_afd);

        $rmh_afd = array();

        foreach ($new_Rmh_afd as $key => $value) if (is_array($value)) {
            foreach ($value as $key1 => $value1) if (is_array($value1)) {
                $merged_values = array();
                foreach ($value1 as $key2 => $value2) if (is_array($value2)) {
                    // Merge all the nested arrays into a single array
                    $merged_values = array_merge($merged_values, $value2);
                }
                // Add the merged array to the result
                $rmh_afd[$key][$key1] = $merged_values;
            }
            // Unset the unnecessary level of the array
            $rmh_afd[$key][$key1] = $rmh_afd[$key][$key1];
        }



        // dd($rmh_afd);

        $mergeAfdRmh = [];
        foreach ($rmh_afd as $key1 => $value1) {
            foreach ($value1 as $key2 => $value2) {
                $mergeAfdRmh = array_merge($mergeAfdRmh, $value2);
            }
        }
        // dd($mergeAfdRmh);





        $hitungLcp_afd = array();
        foreach ($landscape_afd as $key => $value) {
            foreach ($value as $key1 => $value2) {
                // Initialize the "nilai_total" for each month to 0
                $hitungLcp_afd[$key][$key1] = [];

                if (is_array($value2)) {
                    foreach ($value2 as $key2 => $value3) {
                        if (is_array($value3)) {
                            $sumNilai = isset($value3['nilai']) ? array_sum(array_map('intval', explode('$', $value3['nilai']))) : 0;
                            // Store the sum in the "nilai_total" key of the corresponding index
                            $date = $value3['datetime'];
                            // Get the year and month from the datetime
                            $yearMonth = date('Y-m-d', strtotime($date));

                            $foto_temuan = explode('$', $value3['foto_temuan']);
                            $kom_temuan = explode('$', $value3['komentar_temuan']);
                            $nilai = explode('$', $value3['nilai']);
                            $komentar = explode('$', $value3['komentar']);



                            $hitungLcp_afd[$key][$key1][$key2] = array_merge($value3, [
                                'nilai_total_lcp' => $sumNilai,
                                'date' => $yearMonth,
                                'est_afd' => $value3['est'] . '_' . $value3['afd'],
                            ]);




                            $apps = explode(';', $value3['app_version']);
                            $inc = 1;
                            $incc = 1;
                            if ($apps[0] == '1.5.32' || $apps[0] == '1.5.31' || $apps[0] == '1.5.30' || $apps[0] == '1.5.29') {
                                foreach ($foto_temuan as $keyx => $value) {
                                    foreach ($kom_temuan as $keyx2 => $value2) if ($keyx == $keyx2) {
                                        if ($value != '') {
                                            $hitungLcp_afd[$key][$key1][$key2]['foto_temuan_lcp' . $inc++] = $value;
                                            $hitungLcp_afd[$key][$key1][$key2]['komentar_temuan_lcp' . $incc++] = $value2;
                                        }
                                    }
                                }
                            } else {
                                foreach ($foto_temuan as $keyx => $value) {
                                    foreach ($komentar as $keyx2 => $value2) if ($keyx == $keyx2) {
                                        if ($value != '') {
                                            $hitungLcp_afd[$key][$key1][$key2]['foto_temuan_lcp' . $inc++] = $value;
                                            $hitungLcp_afd[$key][$key1][$key2]['komentar_temuan_lcp' . $incc++] = $value2;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        // dd($hitungLcp_afd);

        $new_Lcp_afd = array();

        foreach ($hitungLcp_afd as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $index = 0; // Initialize index here
                foreach ($value1 as $key2 => $value2) {
                    $index++; // Increment the index inside the loop

                    // Group data based on "rmhX" keys
                    $number = 1;
                    $groupedData = array();
                    $numberOfFotoTemuanLsKeys = 0; // Initialize the counter for foto_temuan_ls keys
                    foreach ($value2 as $key3 => $value3) {
                        if (strpos($key3, 'foto_temuan_lcp') === 0) {
                            $numberOfFotoTemuanLsKeys++;
                        }
                    }


                    if (!isset($value2['foto_temuan_lcp1']) || empty($value2['foto_temuan_lcp1'])) {
                        // If the key foto_temuan_ls1 does not exist or is empty, then the value of $new_Lscp[$key][$key1][$key2] should be empty.
                        $new_Lcp_afd[$key][$key1][$key2] = '';
                    } else {
                        // If the key foto_temuan_ls1 exists and is not empty, then the code you have already written will be executed.
                        for ($i = 1; $i <= $numberOfFotoTemuanLsKeys; $i++) {
                            $number++;
                            $groupedData[] = array(
                                "foto_temuan_lcp" => $value2["foto_temuan_lcp" . $i],
                                "komentar_temuan_lcp" => $value2["komentar_temuan_lcp" . $i],
                                // "komentar_lcp" => $value2["komentar_lcp" . $i],
                                "title" => $value2["est"] . "-" . $value2["afd"],
                                "id" => $value2["id"]
                            );
                        }

                        $new_Lcp_afd[$key][$key1][$key2] = $groupedData;
                    }
                }
            }
        }
        // dd($new_Lcp_afd);


        foreach ($new_Lcp_afd as $key => $value) if (is_array($value)) {
            foreach ($value as $key1 => $value1) if (is_array($value1)) {
                $merged_values = array();
                foreach ($value1 as $key2 => $value2) if (is_array($value2)) {
                    // Merge all the nested arrays into a single array
                    $merged_values = array_merge($merged_values, $value2);
                }
                // Add the merged array to the result
                $new_Lcp_afd[$key][$key1] = $merged_values;
            }
            // Unset the unnecessary level of the array
            $new_Lcp_afd[$key][$key1] = $new_Lcp_afd[$key][$key1];
        }



        $mergeAfdlcp = [];
        foreach ($new_Lcp_afd as $key1 => $value1) {
            foreach ($value1 as $key2 => $value2) {
                $mergeAfdlcp = array_merge($mergeAfdlcp, $value2);
            }
        }

        // dd($mergeAfdlcp);


        $hitungLk_afd = array();
        foreach ($lingkungan_afd as $key => $value) {
            foreach ($value as $key1 => $value2) {
                // Initialize the "nilai_total" for each month to 0
                $hitungLk_afd[$key][$key1] = [];

                if (is_array($value2)) {
                    foreach ($value2 as $key2 => $value3) {
                        if (is_array($value3)) {
                            $sumNilai = isset($value3['nilai']) ? array_sum(array_map('intval', explode('$', $value3['nilai']))) : 0;
                            // Store the sum in the "nilai_total" key of the corresponding index
                            $date = $value3['datetime'];
                            // Get the year and month from the datetime
                            $yearMonth = date('Y-m-d', strtotime($date));

                            $foto_temuan = explode('$', $value3['foto_temuan']);
                            $kom_temuan = explode('$', $value3['komentar_temuan']);
                            $nilai = explode('$', $value3['nilai']);
                            $komentar = explode('$', $value3['komentar']);



                            $hitungLk_afd[$key][$key1][$key2] = array_merge($value3, [
                                'nilai_total_lk' => $sumNilai,
                                'date' => $yearMonth,
                                'est_afd' => $value3['est'] . '_' . $value3['afd'],
                            ]);


                            $apps = explode(';', $value3['app_version']);
                            $inc = 1;
                            $incc = 1;
                            if ($apps[0] == '1.5.32' || $apps[0] == '1.5.31' || $apps[0] == '1.5.30' || $apps[0] == '1.5.29') {
                                foreach ($foto_temuan as $keyx => $value) {
                                    foreach ($kom_temuan as $keyx2 => $value2) if ($keyx == $keyx2) {
                                        if ($value != '') {
                                            $hitungLk_afd[$key][$key1][$key2]['foto_temuan_lk' . $inc++] = $value;
                                            $hitungLk_afd[$key][$key1][$key2]['komentar_temuan_lk' . $incc++] = $value2;
                                        }
                                    }
                                }
                            } else {
                                foreach ($foto_temuan as $keyx => $value) {
                                    foreach ($komentar as $keyx2 => $value2) if ($keyx == $keyx2) {
                                        if ($value != '') {
                                            $hitungLk_afd[$key][$key1][$key2]['foto_temuan_lk' . $inc++] = $value;
                                            $hitungLk_afd[$key][$key1][$key2]['komentar_temuan_lk' . $incc++] = $value2;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        // dd($hitungLk_afd);
        $new_Lk_afd = array();

        foreach ($hitungLk_afd as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $index = 0; // Initialize index here
                foreach ($value1 as $key2 => $value2) {
                    $index++; // Increment the index inside the loop

                    // Group data based on "rmhX" keys
                    $number = 1;
                    $groupedData = array();
                    $numberOfFotoTemuanLsKeys = 0; // Initialize the counter for foto_temuan_ls keys
                    foreach ($value2 as $key3 => $value3) {
                        if (strpos($key3, 'foto_temuan_lk') === 0) {
                            $numberOfFotoTemuanLsKeys++;
                        }
                    }



                    if (!isset($value2['foto_temuan_lk1']) || empty($value2['foto_temuan_lk1'])) {
                        // If the key foto_temuan_ls1 does not exist or is empty, then the value of $new_Lscp[$key][$key1][$key2] should be empty.
                        $new_Lk_afd[$key][$key1][$key2] = '';
                    } else {
                        // If the key foto_temuan_ls1 exists and is not empty, then the code you have already written will be executed.
                        for ($i = 1; $i <= $numberOfFotoTemuanLsKeys; $i++) {
                            $number++;
                            $groupedData[] = array(
                                "foto_temuan_lk" => $value2["foto_temuan_lk" . $i],
                                "komentar_temuan_lk" => $value2["komentar_temuan_lk" . $i],
                                // "komentar_lk" => $value2["komentar_lk" . $i],
                                "title" => $value2["est"] . "-" . $value2["afd"],
                                "id" => $value2["id"]
                            );
                        }

                        $new_Lk_afd[$key][$key1][$key2] = $groupedData;
                    }
                }
            }
        }

        // dd($new_Lk_afd);

        foreach ($new_Lk_afd as $key => $value) if (is_array($value)) {
            foreach ($value as $key1 => $value1) if (is_array($value1)) {
                $merged_values = array();
                foreach ($value1 as $key2 => $value2) if (is_array($value2)) {
                    // Merge all the nested arrays into a single array
                    $merged_values = array_merge($merged_values, $value2);
                }
                // Add the merged array to the result
                $new_Lk_afd[$key][$key1] = $merged_values;
            }
            // Unset the unnecessary level of the array
            $new_Lk_afd[$key][$key1] = $new_Lk_afd[$key][$key1];
        }




        $mergeAfdlk = [];
        foreach ($new_Lk_afd as $key1 => $value1) {
            foreach ($value1 as $key2 => $value2) {
                $mergeAfdlk = array_merge($mergeAfdlk, $value2);
            }
        }



        // dd($lingkungan);
        $tabLanscape = DB::connection('mysql2')->table('landscape')
            ->select(
                "landscape.*",
                DB::raw('DATE_FORMAT(landscape.datetime, "%M") as bulan'),
                DB::raw('DATE_FORMAT(landscape.datetime, "%Y") as tahun'),
            )
            ->where('landscape.datetime', 'like', '%' . $tanggal . '%')
            ->where('landscape.est', $est)
            ->orderBy('est', 'asc')
            ->orderBy('afd', 'asc')
            ->orderBy('datetime', 'asc')
            ->get();
        $tabLanscape = json_decode($tabLanscape, true);


        $tabPerum = DB::connection('mysql2')->table('perumahan')
            ->select(
                "perumahan.*",
                DB::raw('DATE_FORMAT(perumahan.datetime, "%M") as bulan'),
                DB::raw('DATE_FORMAT(perumahan.datetime, "%Y") as tahun'),
            )
            ->where('perumahan.datetime', 'like', '%' . $tanggal . '%')
            ->where('perumahan.est', $est)
            ->orderBy('est', 'asc')
            ->orderBy('afd', 'asc')
            ->orderBy('datetime', 'asc')
            ->get();

        $tabPerum = json_decode($tabPerum, true);


        $tabLingkn = DB::connection('mysql2')->table('lingkungan')
            ->select(
                "lingkungan.*",
                DB::raw('DATE_FORMAT(lingkungan.datetime, "%M") as bulan'),
                DB::raw('DATE_FORMAT(lingkungan.datetime, "%Y") as tahun'),
            )
            ->where('lingkungan.datetime', 'like', '%' . $tanggal . '%')
            ->where('lingkungan.est', $est)
            ->orderBy('est', 'asc')
            ->orderBy('afd', 'asc')
            ->orderBy('datetime', 'asc')
            ->get();

        $tabLingkn = json_decode($tabLingkn, true);



        // dd($tabLanscape, $tabLingkn, $tabPerum);
        $coount = 0;
        foreach ($tabPerum as $key => $value) {
            # code...

            $nilai = explode('$', $value['nilai']);
            $komentar = explode('$', $value['komentar']);

            $coount = count($nilai);

            // dd($nilai);

            for ($i = 0; $i < $coount; $i++) {
                $tabPerum[$key]["nilai_" . ($i + 1)] = $nilai[$i];
                $tabPerum[$key]["komen_" . ($i + 1)] = $komentar[$i];
            }

            $tabPerum[$key]["total_nilai"] = array_sum($nilai);
            // $tabPerum[$key][]
        }


        foreach ($tabLanscape as $key => $value) {
            # code...

            $nilai = explode('$', $value['nilai']);
            $komentar = explode('$', $value['komentar']);

            $coount = count($nilai);

            // dd($nilai);

            for ($i = 0; $i < $coount; $i++) {
                $tabLanscape[$key]["nilai_" . ($i + 1)] = $nilai[$i];
                $tabLanscape[$key]["komen_" . ($i + 1)] = $komentar[$i];
            }

            $tabLanscape[$key]["total_nilai"] = array_sum($nilai);
            // $tabLanscape[$key][]
        }
        // dd($tabLingkn);
        foreach ($tabLingkn as $key => $value) {
            # code...

            $nilai = explode('$', $value['nilai']);
            $komentar = explode('$', $value['komentar']);

            $coount = count($nilai);

            // dd($nilai);

            for ($i = 0; $i < $coount; $i++) {
                $tabLingkn[$key]["nilai_" . ($i + 1)] = $nilai[$i];
                $tabLingkn[$key]["komen_" . ($i + 1)] = $komentar[$i];
            }

            $tabLingkn[$key]["total_nilai"] = array_sum($nilai);
            // $tabLanscape[$key][]
        }


        // dd($tabLingkn);




        $dateString = $date;

        // Convert the string to a DateTime object
        $dateTimeObj = new DateTime($dateString);

        // Get the year and month from the DateTime object
        $yearAndMonth = $dateTimeObj->format('Y-m');
        // dd($pagianteafd_lk);
        $arrView = array();
        $arrView['est'] =  $est;
        // dd($mergedArray_rmh, $mergedArray_lscp, $mergedArray_lkngn);

        $arrView['Landscape'] =  $mergedArray_lscp;
        $arrView['lingkungan'] =  $mergedArray_lkngn;
        $arrView['tanggal'] =  $yearAndMonth;
        $arrView['li'] =  $hitungRmh;
        $arrView['Perumahan'] = $mergedArray_rmh;

        // dd($mergedArray_rmh);

        $arrView['rumah_afd'] = $mergeAfdRmh;
        $arrView['lcp_afd'] = $mergeAfdlcp;
        $arrView['lingkungan_afd'] = $mergeAfdlk;


        $arrView['tabPerum'] = $tabPerum;
        $arrView['tabLanscape'] = $tabLanscape;
        $arrView['tabLingkn'] = $tabLingkn;
        // dd($paginatedItems);

        echo json_encode($arrView);
        exit();
    }


    public function downloadBAemp(Request $request)
    {

        $est = $request->input('estBA');
        $tanggal = $request->input('tglPDF');

        // dd($est, $tanggal);

        $emplacement = DB::connection('mysql2')->table('perumahan')
            ->select(
                "perumahan.*",
                DB::raw('DATE_FORMAT(perumahan.datetime, "%M") as bulan'),
                DB::raw('DATE_FORMAT(perumahan.datetime, "%Y") as tahun'),
            )
            ->where('perumahan.datetime', 'like', '%' . $tanggal . '%')
            ->where('perumahan.est', $est)
            ->orderBy('est', 'asc')
            ->orderBy('afd', 'asc')
            ->orderBy('datetime', 'asc')
            ->get();

        $emplacement = json_decode(json_encode($emplacement), true); // Convert the collection to an array
        $emplacement = collect($emplacement)->groupBy(['est', 'afd'])->toArray();

        // dd($emplacement);
        $lingkungan = DB::connection('mysql2')->table('lingkungan')
            ->select(
                "lingkungan.*",
                DB::raw('DATE_FORMAT(lingkungan.datetime, "%M") as bulan'),
                DB::raw('DATE_FORMAT(lingkungan.datetime, "%Y") as tahun'),
            )
            ->where('lingkungan.datetime', 'like', '%' . $tanggal . '%')
            ->where('lingkungan.est', $est)
            // ->where('lingkungan.afd', 'EST')
            ->orderBy('est', 'asc')
            ->orderBy('afd', 'asc')
            ->orderBy('datetime', 'asc')
            ->get();

        $lingkungan = json_decode(json_encode($lingkungan), true); // Convert the collection to an array
        $lingkungan = collect($lingkungan)->groupBy(['est', 'afd'])->toArray();


        $landscape = DB::connection('mysql2')->table('landscape')
            ->select(
                "landscape.*",
                DB::raw('DATE_FORMAT(landscape.datetime, "%M") as bulan'),
                DB::raw('DATE_FORMAT(landscape.datetime, "%Y") as tahun'),
            )
            ->where('landscape.datetime', 'like', '%' . $tanggal . '%')
            ->where('landscape.est', $est)
            // ->where('landscape.afd', 'EST')
            ->orderBy('est', 'asc')
            ->orderBy('afd', 'asc')
            ->orderBy('datetime', 'asc')
            ->get();

        $landscape = json_decode(json_encode($landscape), true); // Convert the collection to an array
        $landscape = collect($landscape)->groupBy(['est', 'afd'])->toArray();


        // dd($date);
        // dd($emplacement, $landscape, $lingkungan);

        $hitungRmh = array();
        foreach ($emplacement as $key => $value) {
            foreach ($value as $key1 => $value2) {
                // Initialize the "nilai_total" for each month to 0
                $hitungRmh[$key][$key1] = [];

                if (is_array($value2)) {
                    foreach ($value2 as $key2 => $value3) {
                        if (is_array($value3)) {
                            $sumNilai = isset($value3['nilai']) ? array_sum(array_map('intval', explode('$', $value3['nilai']))) : 0;
                            // Store the sum in the "nilai_total" key of the corresponding index
                            $date = $value3['datetime'];
                            // Get the year and month from the datetime
                            $yearMonth = date('Y-m-d', strtotime($date));

                            $foto_temuan = explode('$', $value3['foto_temuan']);
                            $kom_temuan = explode('$', $value3['komentar_temuan']);
                            $nilai = explode('$', $value3['nilai']);
                            $komentar = explode('$', $value3['komentar']);

                            unset($value3['foto_temuan']);
                            unset($value3['komentar_temuan']);
                            unset($value3['nilai']);
                            unset($value3['komentar']);

                            $hitungRmh[$key][$key1][$key2] = array_merge($value3, [
                                'nilai_total_rmh' => $sumNilai,
                                'date' => $yearMonth,
                                'est_afd' => $value3['est'] . '_' . $value3['afd'],
                            ]);

                            foreach ($foto_temuan as $i => $foto) {
                                // Create new keys for each exploded value
                                $hitungRmh[$key][$key1][$key2]['foto_temuan_rmh' . ($i + 1)] = $foto;
                            }

                            foreach ($kom_temuan as $i => $komn) {
                                // Create new keys for each exploded value
                                $hitungRmh[$key][$key1][$key2]['komentar_temuan_rmh' . ($i + 1)] = $komn;
                            }

                            foreach ($nilai as $i => $nilai) {
                                // Create new keys for each exploded value
                                $hitungRmh[$key][$key1][$key2]['nilai_rmh' . ($i + 1)] = $nilai;
                            }

                            foreach ($komentar as $i => $komens) {
                                // Create new keys for each exploded value
                                $hitungRmh[$key][$key1][$key2]['komentar_rmh' . ($i + 1)] = $komens;
                            }
                        }
                    }
                }
            }
        }


        // dd($hitungRmh);

        $nila_akhir = array();

        foreach ($hitungRmh as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $avg = count($value1);
                $nilai = array_fill(1, 13, 0);
                $komentar_rmh = array_fill(1, 13, '');

                $tiperumah = '';
                $penghuni = '';
                foreach ($value1 as $key2 => $value3) {
                    $datetime = $value3['datetime'];
                    $time = date('Y-m-d', strtotime($datetime));

                    // for ($i = 1; $i <= 13; $i++) {
                    //     $nilai[$i] += $value3["nilai_rmh$i"] / $avg;
                    //     if ($komentar_rmh[$i]  != '-') {
                    //         $komentar_rmh[$i] .= ($value3["komentar_rmh$i"] . '(' . $value3['tipe_rumah'] . ')') . ', ';
                    //     } else {
                    //         $komentar_rmh[$i] .= $value3["komentar_rmh$i"] . ', ';
                    //     }
                    // }

                    $tiperumah .= $value3['tipe_rumah'] . ', ';
                    $penghuni .= $value3['penghuni'] . ', ';

                    for ($i = 1; $i <= 13; $i++) {
                        $nilai[$i] += $value3["nilai_rmh$i"] / $avg;
                        if ($value3["komentar_rmh$i"] != '-') { // Check if komentar_rmh is not equal to "-"
                            $komentar_rmh[$i] .= ($value3["komentar_rmh$i"] . '(' . $value3['tipe_rumah'] . ')') . ', ';
                        } else {
                            $komentar_rmh[$i] .= ' ' . ', ';
                        }
                    }
                }


                $nila_akhir[$key][$key1]['est'] = $value3['est'] . '-' . $value3['afd'];
                $nila_akhir[$key][$key1]['tipe_rumah'] = rtrim($tiperumah, ', ');
                $nila_akhir[$key][$key1]['penghuni'] = rtrim($penghuni, ', ');
                $nila_akhir[$key][$key1]['petugas'] = $value3['petugas'];
                $nila_akhir[$key][$key1]['tanggal'] = $time;

                for ($i = 1; $i <= 13; $i++) {
                    $nila_akhir[$key][$key1]["perumahan_nilai$i"] = $nilai[$i];
                    $nila_akhir[$key][$key1]["perumahan_komen$i"] = rtrim($komentar_rmh[$i], ', ');
                }
                $nila_akhir[$key][$key1]["total_nilairmh"] = array_sum($nilai); // Corrected line
            }
        }


        // dd($nila_akhir, $hitungRmh);

        $hitungLingkungan = array();

        foreach ($lingkungan as $key => $value) {
            foreach ($value as $key1 => $value2) {
                // Initialize the "nilai_total" for each month to 0
                $hitungLingkungan[$key][$key1] = [];

                if (is_array($value2)) {
                    foreach ($value2 as $key2 => $value3) {
                        if (is_array($value3)) {
                            $sumNilai = isset($value3['nilai']) ? array_sum(array_map('intval', explode('$', $value3['nilai']))) : 0;
                            // Store the sum in the "nilai_total" key of the corresponding index
                            $date = $value3['datetime'];
                            // Get the year and month from the datetime
                            $yearMonth = date('Y-m-d', strtotime($date));

                            $foto_temuan = explode('$', $value3['foto_temuan']);
                            $kom_temuan = explode('$', $value3['komentar_temuan']);
                            $nilai = explode('$', $value3['nilai']);
                            $komentar = explode('$', $value3['komentar']);

                            unset($value3['foto_temuan']);
                            unset($value3['komentar_temuan']);
                            unset($value3['nilai']);
                            unset($value3['komentar']);

                            $hitungLingkungan[$key][$key1][$key2] = array_merge($value3, [
                                'nilai_total_Lngkl' => $sumNilai,
                                'date' => $yearMonth,
                                'est_afd' => $value3['est'] . '_' . $value3['afd'],
                            ]);

                            foreach ($foto_temuan as $i => $foto) {
                                // Create new keys for each exploded value
                                $hitungLingkungan[$key][$key1][$key2]['foto_temuan_ll' . ($i + 1)] = $foto;
                            }

                            foreach ($kom_temuan as $i => $komn) {
                                // Create new keys for each exploded value
                                $hitungLingkungan[$key][$key1][$key2]['komentar_temuan_ll' . ($i + 1)] = $komn;
                            }

                            foreach ($nilai as $i => $nilai) {
                                // Create new keys for each exploded value
                                $hitungLingkungan[$key][$key1][$key2]['nilai_ll' . ($i + 1)] = $nilai;
                            }

                            foreach ($komentar as $i => $komens) {
                                // Create new keys for each exploded value
                                $hitungLingkungan[$key][$key1][$key2]['komentar_ll' . ($i + 1)] = $komens;
                            }
                        }
                    }
                }
            }
        }


        $nila_akhir_lingkungan = array();

        foreach ($hitungLingkungan as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $avg = count($value1);
                $nilai = array_fill(1, 14, 0);
                $komentar_rmh = array_fill(1, 14, '');

                $tiperumah = '';
                $penghuni = '';
                foreach ($value1 as $key2 => $value3) {
                    $datetime = $value3['datetime'];
                    $time = date('Y-m-d', strtotime($datetime));

                    // for ($i = 1; $i <= 14; $i++) {
                    //     $nilai[$i] += $value3["nilai_ll$i"] / $avg;
                    //     $komentar_rmh[$i] .= $value3["komentar_ll$i"] . ', ';
                    // }


                    for ($i = 1; $i <= 14; $i++) {
                        $nilai[$i] += $value3["nilai_ll$i"] / $avg;
                        if ($value3["komentar_ll$i"] != '-') { // Check if komentar_rmh is not equal to "-"
                            $komentar_rmh[$i] .= $value3["komentar_ll$i"]  . ', ';
                        } else {
                            $komentar_rmh[$i] .= ' ' . ', ';
                        }
                    }
                }



                for ($i = 1; $i <= 14; $i++) {
                    $nila_akhir_lingkungan[$key][$key1]["lingkungan_nilai$i"] = $nilai[$i];
                    $nila_akhir_lingkungan[$key][$key1]["lingkungan_komen$i"] = rtrim($komentar_rmh[$i], ', ');
                }
                $nila_akhir[$key][$key1]["total_nilailkng"] = array_sum($nilai); // Corrected line
            }
        }


        $hitungLandscape = array();
        foreach ($landscape as $key => $value) {
            foreach ($value as $key1 => $value2) {
                // Initialize the "nilai_total" for each month to 0
                $hitungLandscape[$key][$key1] = [];

                if (is_array($value2)) {
                    foreach ($value2 as $key2 => $value3) {
                        if (is_array($value3)) {
                            $sumNilai = isset($value3['nilai']) ? array_sum(array_map('intval', explode('$', $value3['nilai']))) : 0;
                            // Store the sum in the "nilai_total" key of the corresponding index
                            $date = $value3['datetime'];
                            // Get the year and month from the datetime
                            $yearMonth = date('Y-m-d', strtotime($date));

                            $foto_temuan = explode('$', $value3['foto_temuan']);
                            $kom_temuan = explode('$', $value3['komentar_temuan']);
                            $nilai = explode('$', $value3['nilai']);
                            $komentar = explode('$', $value3['komentar']);

                            unset($value3['foto_temuan']);
                            unset($value3['komentar_temuan']);
                            unset($value3['nilai']);
                            unset($value3['komentar']);

                            $hitungLandscape[$key][$key1][$key2] = array_merge($value3, [
                                'nilai_total_Lngkl' => $sumNilai,
                                'date' => $yearMonth,
                                'est_afd' => $value3['est'] . '_' . $value3['afd'],
                            ]);

                            foreach ($foto_temuan as $i => $foto) {
                                // Create new keys for each exploded value
                                $hitungLandscape[$key][$key1][$key2]['foto_temuan_ls' . ($i + 1)] = $foto;
                            }

                            foreach ($kom_temuan as $i => $komn) {
                                // Create new keys for each exploded value
                                $hitungLandscape[$key][$key1][$key2]['komentar_temuan_ls' . ($i + 1)] = $komn;
                            }

                            foreach ($nilai as $i => $nilai) {
                                // Create new keys for each exploded value
                                $hitungLandscape[$key][$key1][$key2]['nilai_ls' . ($i + 1)] = $nilai;
                            }

                            foreach ($komentar as $i => $komens) {
                                // Create new keys for each exploded value
                                $hitungLandscape[$key][$key1][$key2]['komentar_ls' . ($i + 1)] = $komens;
                            }
                        }
                    }
                }
            }
        }

        $nila_akhir_landscape = array();

        foreach ($hitungLandscape as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $avg = count($value1);
                $nilai = array_fill(1, 5, 0);
                $komentar_rmh = array_fill(1, 5, '');

                $tiperumah = '';
                $penghuni = '';
                foreach ($value1 as $key2 => $value3) {
                    $datetime = $value3['datetime'];
                    $time = date('Y-m-d', strtotime($datetime));
                    // dd($value3);

                    // for ($i = 1; $i <= 5; $i++) {
                    //     $nilai[$i] += $value3["nilai_ls$i"] / $avg;
                    //     $komentar_rmh[$i] .= $value3["komentar_ls$i"] . ', ';
                    // }

                    for ($i = 1; $i <= 5; $i++) {
                        $nilai[$i] += $value3["nilai_ls$i"] / $avg;
                        if ($value3["komentar_ls$i"] != '-') { // Check if komentar_rmh is not equal to "-"
                            $komentar_rmh[$i] .= $value3["komentar_ls$i"]  . ', ';
                        } else {
                            $komentar_rmh[$i] .= ' ' . ', ';
                        }
                    }
                }



                for ($i = 1; $i <= 5; $i++) {
                    $nila_akhir_landscape[$key][$key1]["landscape_nilai$i"] = $nilai[$i];
                    $nila_akhir_landscape[$key][$key1]["landscape_komen$i"] = rtrim($komentar_rmh[$i], ', ');
                }
                $nila_akhir[$key][$key1]["total_nilailcp"] = array_sum($nilai); // Corrected line
            }
        }


        $mergedArray = array();

        foreach ($nila_akhir as $key => $value) {
            if (isset($nila_akhir_lingkungan[$key]) && isset($nila_akhir_landscape[$key])) {
                foreach ($value as $subKey => $subValue) {
                    if (isset($nila_akhir_lingkungan[$key][$subKey]) && isset($nila_akhir_landscape[$key][$subKey])) {
                        $mergedArray[$key][$subKey] = array_merge(
                            $subValue,
                            $nila_akhir_lingkungan[$key][$subKey],
                            $nila_akhir_landscape[$key][$subKey]
                        );
                    }
                }
            }
        }

        $mergedArray2 = array();

        foreach ($hitungRmh as $key => $value) {
            if (isset($hitungLingkungan[$key]) && isset($hitungLandscape[$key])) {
                foreach ($value as $subKey => $subValue) {
                    if (isset($hitungLingkungan[$key][$subKey]) && isset($hitungLandscape[$key][$subKey])) {
                        $mergedArray2[$key][$subKey] = array_merge(
                            $subValue,
                            $hitungLingkungan[$key][$subKey],
                            $hitungLandscape[$key][$subKey]
                        );
                    }
                }
            }
        }
        // dd($hitungRmh, $hitungLingkungan, $hitungLandscape, $mergedArray2);
        // // dd($nila_akhir, $mergedArray);
        // dd($mergedArray);
        // dd($date, $est);
        $arrView = array();

        $arrView['test'] =  'oke';
        $arrView['total'] =  $mergedArray;
        // $arrView['lingkungan'] =  $nila_akhir_lingkungan;

        $pdf = PDF::loadView('Perumahan.baemp', ['data' => $arrView]);

        $customPaper = array(360, 360, 360, 360);
        $pdf->set_paper('A2', 'landscape');
        // $pdf->set_paper('A2', 'potrait');

        $filename = 'BA FORM PEMERIKSAAN PERUMAHAN' . $arrView['test'] . '.pdf';

        return $pdf->stream($filename);
    }


    public function downloadPDF(Request $request)
    {

        $est = $request->input('estPDF');
        $tanggal = $request->input('tglpdfnew');

        // dd($est, $tanggal);

        $emplacement = DB::connection('mysql2')->table('perumahan')
            ->select(
                "perumahan.*",
                DB::raw('DATE_FORMAT(perumahan.datetime, "%M") as bulan'),
                DB::raw('DATE_FORMAT(perumahan.datetime, "%Y") as tahun'),
            )
            ->where('perumahan.datetime', 'like', '%' . $tanggal . '%')
            ->where('perumahan.est', $est)
            ->orderBy('est', 'asc')
            ->orderBy('afd', 'asc')
            ->orderBy('datetime', 'asc')
            ->get();

        $emplacement = json_decode(json_encode($emplacement), true); // Convert the collection to an array
        $emplacement = collect($emplacement)->groupBy(['est', 'afd'])->toArray();

        // dd($emplacement);
        $lingkungan = DB::connection('mysql2')->table('lingkungan')
            ->select(
                "lingkungan.*",
                DB::raw('DATE_FORMAT(lingkungan.datetime, "%M") as bulan'),
                DB::raw('DATE_FORMAT(lingkungan.datetime, "%Y") as tahun'),
            )
            ->where('lingkungan.datetime', 'like', '%' . $tanggal . '%')
            ->where('lingkungan.est', $est)
            // ->where('lingkungan.afd', 'EST')
            ->orderBy('est', 'asc')
            ->orderBy('afd', 'asc')
            ->orderBy('datetime', 'asc')
            ->get();

        $lingkungan = json_decode(json_encode($lingkungan), true); // Convert the collection to an array
        $lingkungan = collect($lingkungan)->groupBy(['est', 'afd'])->toArray();


        $landscape = DB::connection('mysql2')->table('landscape')
            ->select(
                "landscape.*",
                DB::raw('DATE_FORMAT(landscape.datetime, "%M") as bulan'),
                DB::raw('DATE_FORMAT(landscape.datetime, "%Y") as tahun'),
            )
            ->where('landscape.datetime', 'like', '%' . $tanggal . '%')
            ->where('landscape.est', $est)
            // ->where('landscape.afd', 'EST')
            ->orderBy('est', 'asc')
            ->orderBy('afd', 'asc')
            ->orderBy('datetime', 'asc')
            ->get();

        $landscape = json_decode(json_encode($landscape), true); // Convert the collection to an array
        $landscape = collect($landscape)->groupBy(['est', 'afd'])->toArray();


        // dd($date);
        // dd($emplacement, $landscape, $lingkungan);
        $hitungRmh = array();

        foreach ($emplacement as $key => $value) {
            foreach ($value as $key1 => $value2) {
                $hitungRmh[$key][$key1] = [];

                if (is_array($value2)) {
                    foreach ($value2 as $key2 => $value3) {
                        if (is_array($value3)) {
                            $sumNilai = isset($value3['nilai']) ? array_sum(array_map('intval', explode('$', $value3['nilai']))) : 0;
                            $date = $value3['datetime'];
                            $yearMonth = date('Y-m-d', strtotime($date));

                            $foto_temuan = explode('$', $value3['foto_temuan']);
                            $kom_temuan = explode('$', $value3['komentar']);
                            $komentar_temuan = explode('$', $value3['komentar_temuan']);


                            // dd($apps);
                            $hitungRmh[$key][$key1][$key2] = array_merge($value3, [
                                'nilai_total_rmh' => $sumNilai,
                                'date' => $yearMonth,
                                'est_afd' => $value3['est'] . '_' . $value3['afd'],
                            ]);


                            $inc = 1;
                            $apps = explode(';', $value3['app_version']);
                            if ($apps[0] == '1.5.32' || $apps[0] == '1.5.31' || $apps[0] == '1.5.30' || $apps[0] == '1.5.29') {
                                foreach ($foto_temuan as $keyx => $value) {
                                    foreach ($komentar_temuan as $keyx2 => $value2) if ($keyx == $keyx2) {
                                        if ($value != '') {
                                            $hitungRmh[$key][$key1][$key2]['foto_temuan_rmh' . $inc++] = $value . '@' . $value2;
                                        }
                                    }
                                }
                            } else {
                                foreach ($foto_temuan as $keyx => $value) {
                                    foreach ($kom_temuan as $keyx2 => $value2) if ($keyx == $keyx2) {
                                        if ($value != '') {
                                            $hitungRmh[$key][$key1][$key2]['foto_temuan_rmh' . $inc++] = $value . '@' . $value2;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }



        // dd($hitungRmh);
        $hitungLingkungan = array();

        foreach ($lingkungan as $key => $value) {
            foreach ($value as $key1 => $value2) {
                // Initialize the "nilai_total" for each month to 0
                $hitungLingkungan[$key][$key1] = [];

                if (is_array($value2)) {
                    foreach ($value2 as $key2 => $value3) {
                        if (is_array($value3)) {
                            $sumNilai = isset($value3['nilai']) ? array_sum(array_map('intval', explode('$', $value3['nilai']))) : 0;
                            // Store the sum in the "nilai_total" key of the corresponding index
                            $date = $value3['datetime'];
                            // Get the year and month from the datetime
                            $yearMonth = date('Y-m-d', strtotime($date));

                            $foto_temuan = explode('$', $value3['foto_temuan']);
                            $kom_temuan = explode('$', $value3['komentar']);
                            $nilai = explode('$', $value3['nilai']);


                            // unset($value3['foto_temuan']);
                            // unset($value3['komentar_temuan']);
                            // unset($value3['nilai']);
                            // unset($value3['komentar']);

                            $hitungLingkungan[$key][$key1][$key2] = array_merge($value3, [
                                // 'nilai_total_Lngkl' => $sumNilai,
                                'date' => $yearMonth,
                                'est_afd' => $value3['est'] . '_' . $value3['afd'],
                            ]);
                            $komentar_temuan = explode('$', $value3['komentar_temuan']);
                            $inc = 1;
                            $apps = explode(';', $value3['app_version']);
                            if ($apps[0] == '1.5.32' || $apps[0] == '1.5.31' || $apps[0] == '1.5.30' || $apps[0] == '1.5.29') {
                                foreach ($foto_temuan as $keyx => $value) {
                                    foreach ($komentar_temuan as $keyx2 => $value2) if ($keyx == $keyx2) {
                                        if ($value != '') {
                                            $hitungLingkungan[$key][$key1][$key2]['foto_temuan_lkng' . $inc++] = $value . '@' . $value2;
                                        }
                                    }
                                }
                            } else {
                                foreach ($foto_temuan as $keyx => $value) {
                                    foreach ($kom_temuan as $keyx2 => $value2) if ($keyx == $keyx2) {
                                        if ($value != '') {
                                            $hitungLingkungan[$key][$key1][$key2]['foto_temuan_lkng' . $inc++] = $value . '@' . $value2;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        // dd($hitungLingkungan);
        $hitungLandscape = array();
        foreach ($landscape as $key => $value) {
            foreach ($value as $key1 => $value2) {
                // Initialize the "nilai_total" for each month to 0
                $hitungLandscape[$key][$key1] = [];

                if (is_array($value2)) {
                    foreach ($value2 as $key2 => $value3) {
                        if (is_array($value3)) {
                            $sumNilai = isset($value3['nilai']) ? array_sum(array_map('intval', explode('$', $value3['nilai']))) : 0;
                            // Store the sum in the "nilai_total" key of the corresponding index
                            $date = $value3['datetime'];
                            // Get the year and month from the datetime
                            $yearMonth = date('Y-m-d', strtotime($date));

                            $foto_temuan = explode('$', $value3['foto_temuan']);
                            $kom_temuan = explode('$', $value3['komentar']);
                            $nilai = explode('$', $value3['nilai']);
                            $komentar = explode('$', $value3['komentar']);




                            $hitungLandscape[$key][$key1][$key2] = array_merge($value3, [
                                // 'nilai_total_Lngkl' => $sumNilai,
                                'date' => $yearMonth,
                                'est_afd' => $value3['est'] . '_' . $value3['afd'],
                            ]);

                            $inc = 1;

                            $komentar_temuan = explode('$', $value3['komentar_temuan']);
                            $apps = explode(';', $value3['app_version']);
                            if ($apps[0] == '1.5.32' || $apps[0] == '1.5.31' || $apps[0] == '1.5.30' || $apps[0] == '1.5.29') {
                                foreach ($foto_temuan as $keyx => $value) {
                                    foreach ($komentar_temuan as $keyx2 => $value2) if ($keyx == $keyx2) {
                                        if ($value != '') {
                                            $hitungLandscape[$key][$key1][$key2]['foto_temuan_lcp' . $inc++] = $value . '@' . $value2;
                                        }
                                    }
                                }
                            } else {
                                foreach ($foto_temuan as $keyx => $value) {
                                    foreach ($kom_temuan as $keyx2 => $value2) if ($keyx == $keyx2) {
                                        if ($value != '') {
                                            $hitungLandscape[$key][$key1][$key2]['foto_temuan_lcp' . $inc++] = $value . '@' . $value2;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        // dd($hitungLandscape, $hitungRmh, $hitungLingkungan);
        $filter_rmh = [];

        foreach ($hitungRmh as $estKey => $afdArray) {
            foreach ($afdArray as $afdKey => $subArray) {
                foreach ($subArray as $item) {
                    if (!empty($item['foto_temuan_rmh1'])) {
                        $filter_rmh[$estKey][$afdKey][] = $item;
                    }
                }
            }
        }


        // Output the new arra

        $filter_lingkungan = [];

        foreach ($hitungLingkungan as $estKey => $afdArray) {
            foreach ($afdArray as $afdKey => $subArray) {
                foreach ($subArray as $item) {
                    if (!empty($item['foto_temuan_lkng1'])) {
                        $filter_lingkungan[$estKey][$afdKey][] = $item;
                    }
                }
            }
        }
        $filter_landscape = [];

        foreach ($hitungLandscape as $estKey => $afdArray) {
            foreach ($afdArray as $afdKey => $subArray) {
                foreach ($subArray as $item) {
                    if (!empty($item['foto_temuan_lcp1'])) {
                        $filter_landscape[$estKey][$afdKey][] = $item;
                    }
                }
            }
        }

        // dd($filter_landscape, $filter_lingkungan, $filter_rmh);

        $mergedArray = array();

        if (empty($filter_rmh) && empty($filter_lingkungan)) {
            // If both $filter_rmh and $filter_lingkungan are empty, use $filter_landscape
            $mergedArray = $filter_landscape;
        } elseif (empty($filter_landscape) && empty($filter_lingkungan)) {
            // If both $filter_landscape and $filter_lingkungan are empty, use $filter_rmh
            $mergedArray = $filter_rmh;
        } elseif (empty($filter_rmh) && empty($filter_landscape)) {
            // If both $filter_rmh and $filter_landscape are empty, use $filter_lingkungan
            $mergedArray = $filter_lingkungan;
        } else {

            foreach ($filter_rmh as $mainKey => $subArray) {
                // Initialize the merged array for this main key
                $mergedArray[$mainKey] = array();

                // Create a list of all subkeys across the arrays for this main key
                $subKeys = array_unique(array_merge(
                    array_keys($subArray),
                    isset($filter_lingkungan[$mainKey]) ? array_keys($filter_lingkungan[$mainKey]) : [],
                    isset($filter_landscape[$mainKey]) ? array_keys($filter_landscape[$mainKey]) : []
                ));

                foreach ($subKeys as $subKey) {
                    $mergedArray[$mainKey][$subKey] = array();

                    if (isset($filter_rmh[$mainKey][$subKey])) {
                        $mergedArray[$mainKey][$subKey] = array_merge($mergedArray[$mainKey][$subKey], $filter_rmh[$mainKey][$subKey]);
                    }

                    if (isset($filter_lingkungan[$mainKey][$subKey])) {
                        $mergedArray[$mainKey][$subKey] = array_merge($mergedArray[$mainKey][$subKey], $filter_lingkungan[$mainKey][$subKey]);
                    }

                    if (isset($filter_landscape[$mainKey][$subKey])) {
                        $mergedArray[$mainKey][$subKey] = array_merge($mergedArray[$mainKey][$subKey], $filter_landscape[$mainKey][$subKey]);
                    }
                }
            }
        }


        // dd($mergedArray);


        // Now $mergedArray contains the merged and combined data
        // dd($mergedArray);

        foreach ($mergedArray as $estKey => $estValue) {
            foreach ($estValue as $afdKey => $afdValue) {
                foreach ($afdValue as $key => $value) {
                    if (
                        isset($value['foto_temuan_lkng1']) && $value['foto_temuan_lkng1'] === '-' ||
                        isset($value['foto_temuan_rmh1']) && $value['foto_temuan_rmh1'] === '-' ||
                        isset($value['foto_temuan_lcp1']) && $value['foto_temuan_lcp1'] === '-'
                    ) {
                        unset($mergedArray[$estKey][$afdKey][$key]);
                    }
                }
            }
        }


        $newArray = [];

        foreach ($mergedArray as $estKey => $estValue) {
            foreach ($estValue as $afdKey => $afdValue) {
                $combinedIndex = [
                    "est" => $estKey,
                    "afd" => $afdKey,
                    "foto_temuan" => [],
                ];


                foreach ($afdValue as $indexData) {
                    // dd($indexData);
                    foreach ($indexData as $key => $value) {
                        if (strpos($key, 'foto_temuan_rmh') === 0) {
                            $combinedIndex['foto_temuan'][] = $value . "@" . "rmh";
                        }
                    }
                    foreach ($indexData as $key => $value) {
                        if (strpos($key, 'foto_temuan_lcp') === 0) {
                            $combinedIndex['foto_temuan'][] = $value . "@" . "lcp";
                        }
                    }
                    foreach ($indexData as $key => $value) {
                        if (strpos($key, 'foto_temuan_lkng') === 0) {
                            $combinedIndex['foto_temuan'][] = $value . "@" . "lkn";
                        }
                    }
                }

                $newArray[] = $combinedIndex;
            }
        }


        // dd($newArray, $mergedArray);

        $allPetugas = array();

        // Step 1: Gather all petugas names
        foreach ($mergedArray as $key => $value) {
            foreach ($value as $key1 => $value1) {
                foreach ($value1 as $key2 => $value3) {
                    $allPetugas[] = $value3['petugas'];
                }
            }
        }

        $allAfd = array();

        // Step 1: Gather all petugas names
        foreach ($mergedArray as $key => $value) {
            foreach ($value as $key1 => $value1) {
                foreach ($value1 as $key2 => $value3) {
                    $allAfd[] = $value3['afd'];
                }
            }
        }

        $allDate = array();

        // Step 1: Gather all petugas names
        foreach ($mergedArray as $key => $value) {
            foreach ($value as $key1 => $value1) {
                foreach ($value1 as $key2 => $value3) {
                    $datetimeString = $value3['datetime'];
                    $dateTime = new DateTime($datetimeString);

                    $datePart = $dateTime->format("Y-m-d");
                    $allDate[] = $datePart;
                }
            }
        }

        // Step 2: Create a unique list of petugas names
        $uniquePetugas = array_unique($allPetugas);
        $unique = array_unique($allAfd);


        // dd($mergedArray);
        // Step 3: Combine unique petugas names with ampersand (&)
        $combinedPetugas = implode(' & ', $uniquePetugas);
        $combinedAfd = implode(' & ', $unique);
        $header = array();

        foreach ($mergedArray as $key => $value) {
            foreach ($value as $key1 => $value1) {
                foreach ($value1 as $key2 => $value3) {
                    foreach ($newArray as $key4 => $value4) {
                        if ($value4['est'] == $key && $value4['afd'] == $key1) {
                            $header[$key4]['est'] = $value3['est'];
                            $header[$key4]['afd'] = $value3['afd'];
                            $header[$key4]['petugas'] = $combinedPetugas;
                            $header[$key4]['date'] = $value3['date'];
                            $header[$key4]['foto_temuan'] = $value4['foto_temuan'];
                        }
                    }
                }
            }
        }

        // Sort $header by "afd" key in ascending order
        $afdValues = array_column($header, 'afd');
        array_multisort($afdValues, SORT_ASC, $header);

        // Now $header is sorted by "afd" in ascending order


        // dd($header);

        $arrayMerge2 = [];
        // dd($header);

        foreach ($header as $item) {
            $arrayMerge2['est'] = $item['est'];
            $arrayMerge2['date'] = $item['date'];
            $arrayMerge2['afd'] = $combinedAfd;
            $arrayMerge2['petugas'] = $combinedPetugas;
            $arrayMerge2['foto_temuan'] = array_merge($arrayMerge2['foto_temuan'] ?? [], $item['foto_temuan']);

            $detail_temuan = [];
            foreach ($item['foto_temuan'] as $foto) {
                $parts = explode('_', $foto);
                // dd($parts, $header);
                if (count($parts) > 2) {
                    $detail_parts = explode('.', $parts[4]); // Split the fourth part by dot
                    if (count($detail_parts) > 1) {
                        $detail_temuan[] = $parts[3] . ' ' . $detail_parts[0]; // Concatenate the third part and the part after dot
                    }
                }
            }


            $arrayMerge2['detail_temuan'] = array_merge($arrayMerge2['detail_temuan'] ?? [], $detail_temuan);

            $komentar_temuan = [];

            foreach ($item['foto_temuan'] as $foto) {
                $parts = explode('_', $foto);

                if (count($parts) > 4) {
                    $detail_parts = explode('@', $parts[4]);

                    if (count($detail_parts) > 1) {
                        $komentar_temuan[] = $detail_parts[1];
                    }
                }
            }

            // dd($komentar_temuan);


            $arrayMerge2['komentar_temuan'] = array_merge($arrayMerge2['komentar_temuan'] ?? [], $komentar_temuan);


            $data_temuan = [];
            foreach ($item['foto_temuan'] as $foto) {
                $parts = explode('@', $foto);
                // dd($parts);
                if (count($parts) > 2) {

                    $data_temuan[] = $parts[0] . '@' . $parts[2]; // Concatenate the third part and the part after dot

                }
            }
            $arrayMerge2['data_temuan'] = array_merge($arrayMerge2['data_temuan'] ?? [], $data_temuan);
        }
        // dd($arrayMerge2);
        $baseURL = 'https://mobilepro.srs-ssms.com/storage/app/public/qc/';
        // $baseURL2 = 'https://mobilepro.srs-ssms.com/storage/app/public/qc/lingkungan/PLG_2023829_112324_RGE_OB.jpg';
        $delArr = [];

        // dd($baseURL);
        foreach ($arrayMerge2['data_temuan'] as $key => $imageURL) {
            // Extract the location from the image URL (e.g., 'rmh', 'lcp', 'lkn')
            $location = explode('@', $imageURL)[1];

            // dd($imageURL);
            // Build the full image URL
            $fullURL = $baseURL;

            if ($location === 'rmh') {
                $fullURL .= 'perumahan/';
            } elseif ($location === 'lcp') {
                $fullURL .= 'landscape/';
            } elseif ($location === 'lkn') {
                $fullURL .= 'lingkungan/';
            }

            $fullURL .= $imageURL;


            // dd($fullURL);

            $cleanedURL = explode('@', $fullURL);

            // dd($cleanedURL);

            // $finisurl = $cleanedURL[0] . '-' . $cleanedURL[1];
            $finisurl = $cleanedURL[0];
            // dd($finisurl, $cleanedURL);
            // Send a HEAD request to check the image status
            $headers = get_headers($finisurl);

            // Extract the HTTP status code
            $statusCode = (int) substr($headers[0], 9, 3);

            // Check if the status code is 404 (Not Found)
            if ($statusCode === 404) {
                $newurl =  explode('/', $finisurl);

                // dd($newurl);

                if (isset($newurl[8])) {
                    // $delArr['foto'] = $newurl[8];
                    $delArr['key'][] = $key;
                }
            }
        }
        // dd($delArr);

        if ($delArr !== []) {
            foreach ($delArr['key'] as $keyToDelete) {
                // Unset the keys in arrayMerge2 based on the value from delArr
                unset(
                    $arrayMerge2['foto_temuan'][$keyToDelete],
                    $arrayMerge2['detail_temuan'][$keyToDelete],
                    $arrayMerge2['komentar_temuan'][$keyToDelete],
                    $arrayMerge2['data_temuan'][$keyToDelete]
                );
            }

            $arrayMerge2['foto_temuan'] = array_values($arrayMerge2['foto_temuan']);
            $arrayMerge2['detail_temuan'] = array_values($arrayMerge2['detail_temuan']);
            $arrayMerge2['komentar_temuan'] = array_values($arrayMerge2['komentar_temuan']);
            $arrayMerge2['data_temuan'] = array_values($arrayMerge2['data_temuan']);
        }

        // dd($arrayMerge2);
        // dd($arrayMerge2);

        $arrView = array();

        $arrView['test'] =  $est;
        $arrView['total'] =  $arrayMerge2;
        // $arrView['lingkungan'] =  $nila_akhir_lingkungan;

        $pdf = PDF::loadView('Perumahan.emplPDF', ['data' => $arrView]);

        $customPaper = array(360, 360, 360, 360);
        $pdf->set_paper('A2', 'potrait');
        // $pdf->set_paper('A2', 'potrait');

        $filename = 'PDF PEMERIKSAAN PERUMAHAN' . ' ' . $arrView['test'] . '.pdf';

        return $pdf->stream($filename);
    }


    public function editkom(Request $request)
    {

        $id = $request->input('id');
        $komen = $request->input('komen');
        $old_koment = $request->input('old_koment');
        $dataType = $request->input('dataType');
        // $old_koment = 'Instalasi kurang baik';
        // dd($id, $komen, $old_koment, $dataType);

        switch ($dataType) {
            case 'perumahan':

                $emplacement = DB::connection('mysql2')->table('perumahan')
                    ->select("perumahan.*")
                    ->where('perumahan.id', '=', $id)
                    ->get();

                $emplacement = json_decode(json_encode($emplacement), true);

                // dd($emplacement);
                $getKomen = array();
                foreach ($emplacement as $key2 => $value3) {

                    $old_koments = $old_koment; // Set the old_koments variable
                    $komentar = explode('$', $value3['komentar']);
                    $komentar_temuan = explode('$', $value3['komentar_temuan']);


                    $apps = explode(';', $value3['app_version']);

                    // dd($apps);

                    $komens = $komen;

                    // Flag to check if the first "-" has been replaced
                    $replaced = false;

                    if ($apps[0] == '1.5.32' || $apps[0] == '1.5.31' || $apps[0] == '1.5.30' || $apps[0] == '1.5.29') {

                        // Loop through the komentar array and update where necessary
                        foreach ($komentar_temuan as $index => $item) {
                            if ($item === $old_koments && !$replaced) {
                                $komentar_temuan[$index] = $komens; // Update the value to the new komentar
                                $replaced = true; // Set the flag to true after the replacement
                            }
                        }

                        // Join the updated komentar array back into a string with '$' delimiter
                        $updated_komentar = implode('$', $komentar_temuan);

                        DB::connection('mysql2')->table('perumahan')->where('id', $id)->update([
                            'komentar_temuan' => $updated_komentar
                        ]);
                    } else {
                        // Loop through the komentar array and update where necessary
                        foreach ($komentar as $index => $item) {
                            if ($item === $old_koments && !$replaced) {
                                $komentar[$index] = $komens; // Update the value to the new komentar
                                $replaced = true; // Set the flag to true after the replacement
                            }
                        }

                        // Join the updated komentar array back into a string with '$' delimiter
                        $updated_komentar = implode('$', $komentar);

                        DB::connection('mysql2')->table('perumahan')->where('id', $id)->update([
                            'komentar' => $updated_komentar
                        ]);
                    }
                    // Add the updated value3 to the $getKomen array
                    $getKomen[] = $value3;
                }

                break;
            case 'landscape':
                $emplacement = DB::connection('mysql2')->table('landscape')
                    ->select("landscape.*")
                    ->where('landscape.id', '=', $id)
                    ->get();

                $emplacement = json_decode(json_encode($emplacement), true);

                // dd($emplacement);
                $getKomen = array();
                foreach ($emplacement as $key2 => $value3) {

                    $old_koments = $old_koment; // Set the old_koments variable
                    $komentar = explode('$', $value3['komentar']);
                    $komentar_temuan = explode('$', $value3['komentar_temuan']);

                    $apps = explode(';', $value3['app_version']);

                    // dd($apps);

                    $komens = $komen;

                    // Flag to check if the first "-" has been replaced
                    $replaced = false;

                    if ($apps[0] == '1.5.32' || $apps[0] == '1.5.31' || $apps[0] == '1.5.30' || $apps[0] == '1.5.29') {

                        // Loop through the komentar array and update where necessary
                        foreach ($komentar_temuan as $index => $item) {
                            if ($item === $old_koments && !$replaced) {
                                $komentar_temuan[$index] = $komens; // Update the value to the new komentar
                                $replaced = true; // Set the flag to true after the replacement
                            }
                        }

                        // Join the updated komentar array back into a string with '$' delimiter
                        $updated_komentar = implode('$', $komentar_temuan);

                        DB::connection('mysql2')->table('landscape')->where('id', $id)->update([
                            'komentar_temuan' => $updated_komentar
                        ]);
                    } else {
                        // Loop through the komentar array and update where necessary
                        foreach ($komentar as $index => $item) {
                            if ($item === $old_koments && !$replaced) {
                                $komentar[$index] = $komens; // Update the value to the new komentar
                                $replaced = true; // Set the flag to true after the replacement
                            }
                        }

                        // Join the updated komentar array back into a string with '$' delimiter
                        $updated_komentar = implode('$', $komentar);

                        DB::connection('mysql2')->table('landscape')->where('id', $id)->update([
                            'komentar' => $updated_komentar
                        ]);
                    }

                    // Add the updated value3 to the $getKomen array
                    $getKomen[] = $value3;
                }

                break;
            case 'lingkungan':
                $emplacement = DB::connection('mysql2')->table('lingkungan')
                    ->select("lingkungan.*")
                    ->where('lingkungan.id', '=', $id)
                    ->get();

                $emplacement = json_decode(json_encode($emplacement), true);

                $getKomen = array();
                foreach ($emplacement as $key2 => $value3) {

                    $old_koments = $old_koment; // Set the old_koments variable
                    $komentar = explode('$', $value3['komentar']);
                    $komentar_temuan = explode('$', $value3['komentar_temuan']);

                    $apps = explode(';', $value3['app_version']);

                    // dd($apps);

                    $komens = $komen;

                    // Flag to check if the first "-" has been replaced
                    $replaced = false;

                    if ($apps[0] == '1.5.32' || $apps[0] == '1.5.31' || $apps[0] == '1.5.30' || $apps[0] == '1.5.29') {

                        // Loop through the komentar array and update where necessary
                        foreach ($komentar_temuan as $index => $item) {
                            if ($item === $old_koments && !$replaced) {
                                $komentar_temuan[$index] = $komens; // Update the value to the new komentar
                                $replaced = true; // Set the flag to true after the replacement
                            }
                        }

                        // Join the updated komentar array back into a string with '$' delimiter
                        $updated_komentar = implode('$', $komentar_temuan);

                        DB::connection('mysql2')->table('lingkungan')->where('id', $id)->update([
                            'komentar_temuan' => $updated_komentar
                        ]);
                    } else {
                        // Loop through the komentar array and update where necessary
                        foreach ($komentar as $index => $item) {
                            if ($item === $old_koments && !$replaced) {
                                $komentar[$index] = $komens; // Update the value to the new komentar
                                $replaced = true; // Set the flag to true after the replacement
                            }
                        }

                        // Join the updated komentar array back into a string with '$' delimiter
                        $updated_komentar = implode('$', $komentar);

                        DB::connection('mysql2')->table('lingkungan')->where('id', $id)->update([
                            'komentar' => $updated_komentar
                        ]);
                    }
                    // Add the updated value3 to the $getKomen array
                    $getKomen[] = $value3;
                }
                // dd($getKomen, $emplacement);

                break;
            case 'perumahan_afd':
                $emplacement = DB::connection('mysql2')->table('perumahan')
                    ->select("perumahan.*")
                    ->where('perumahan.id', '=', $id)
                    ->get();

                $emplacement = json_decode(json_encode($emplacement), true);

                // dd($emplacement);

                $getKomen = array();
                foreach ($emplacement as $key2 => $value3) {

                    $old_koments = $old_koment; // Set the old_koments variable
                    $komentar = explode('$', $value3['komentar']);
                    $komentar_temuan = explode('$', $value3['komentar_temuan']);
                    $apps = explode(';', $value3['app_version']);

                    // dd($apps);

                    $komens = $komen;

                    // Flag to check if the first "-" has been replaced
                    $replaced = false;

                    if ($apps[0] == '1.5.32' || $apps[0] == '1.5.31' || $apps[0] == '1.5.30' || $apps[0] == '1.5.29') {

                        // Loop through the komentar array and update where necessary
                        foreach ($komentar_temuan as $index => $item) {
                            if ($item === $old_koments && !$replaced) {
                                $komentar_temuan[$index] = $komens; // Update the value to the new komentar
                                $replaced = true; // Set the flag to true after the replacement
                            }
                        }

                        // Join the updated komentar array back into a string with '$' delimiter
                        $updated_komentar = implode('$', $komentar_temuan);

                        DB::connection('mysql2')->table('perumahan')->where('id', $id)->update([
                            'komentar_temuan' => $updated_komentar
                        ]);
                    } else {
                        // Loop through the komentar array and update where necessary
                        foreach ($komentar as $index => $item) {
                            if ($item === $old_koments && !$replaced) {
                                $komentar[$index] = $komens; // Update the value to the new komentar
                                $replaced = true; // Set the flag to true after the replacement
                            }
                        }

                        // Join the updated komentar array back into a string with '$' delimiter
                        $updated_komentar = implode('$', $komentar);

                        DB::connection('mysql2')->table('perumahan')->where('id', $id)->update([
                            'komentar' => $updated_komentar
                        ]);
                    }
                    // Add the updated value3 to the $getKomen array
                    $getKomen[] = $value3;

                    // dd($getKomen, $emplacement);
                }

                break;
            case 'landscape_afd':
                $emplacement = DB::connection('mysql2')->table('landscape')
                    ->select("landscape.*")
                    ->where('landscape.id', '=', $id)
                    ->get();

                $emplacement = json_decode(json_encode($emplacement), true);

                // dd($emplacement);

                $getKomen = array();
                foreach ($emplacement as $key2 => $value3) {

                    $old_koments = $old_koment; // Set the old_koments variable
                    $komentar = explode('$', $value3['komentar']);
                    $komentar_temuan = explode('$', $value3['komentar_temuan']);


                    $apps = explode(';', $value3['app_version']);

                    // dd($apps);

                    $komens = $komen;

                    // Flag to check if the first "-" has been replaced
                    $replaced = false;


                    if ($apps[0] == '1.5.32' || $apps[0] == '1.5.31' || $apps[0] == '1.5.30' || $apps[0] == '1.5.29') {

                        // Loop through the komentar array and update where necessary
                        foreach ($komentar_temuan as $index => $item) {
                            if ($item === $old_koments && !$replaced) {
                                $komentar_temuan[$index] = $komens; // Update the value to the new komentar
                                $replaced = true; // Set the flag to true after the replacement
                            }
                        }

                        // Join the updated komentar array back into a string with '$' delimiter
                        $updated_komentar = implode('$', $komentar_temuan);

                        DB::connection('mysql2')->table('landscape')->where('id', $id)->update([
                            'komentar_temuan' => $updated_komentar
                        ]);
                    } else {
                        // Loop through the komentar array and update where necessary
                        foreach ($komentar as $index => $item) {
                            if ($item === $old_koments && !$replaced) {
                                $komentar[$index] = $komens; // Update the value to the new komentar
                                $replaced = true; // Set the flag to true after the replacement
                            }
                        }

                        // Join the updated komentar array back into a string with '$' delimiter
                        $updated_komentar = implode('$', $komentar);

                        DB::connection('mysql2')->table('landscape')->where('id', $id)->update([
                            'komentar' => $updated_komentar
                        ]);
                    }
                    // Add the updated value3 to the $getKomen array
                    $getKomen[] = $value3;
                }
                // dd($getKomen, $emplacement);

                break;
            case 'lingkunga_afd':
                $emplacement = DB::connection('mysql2')->table('lingkungan')
                    ->select("lingkungan.*")
                    ->where('lingkungan.id', '=', $id)
                    ->get();

                $emplacement = json_decode(json_encode($emplacement), true);

                $getKomen = array();
                foreach ($emplacement as $key2 => $value3) {

                    $old_koments = $old_koment; // Set the old_koments variable
                    $komentar = explode('$', $value3['komentar']);
                    $komentar_temuan = explode('$', $value3['komentar_temuan']);


                    $apps = explode(';', $value3['app_version']);

                    // dd($apps);

                    $komens = $komen;

                    // Flag to check if the first "-" has been replaced
                    $replaced = false;


                    if ($apps[0] == '1.5.32' || $apps[0] == '1.5.31' || $apps[0] == '1.5.30' || $apps[0] == '1.5.29') {

                        // Loop through the komentar array and update where necessary
                        foreach ($komentar_temuan as $index => $item) {
                            if ($item === $old_koments && !$replaced) {
                                $komentar_temuan[$index] = $komens; // Update the value to the new komentar
                                $replaced = true; // Set the flag to true after the replacement
                            }
                        }

                        // Join the updated komentar array back into a string with '$' delimiter
                        $updated_komentar = implode('$', $komentar_temuan);

                        DB::connection('mysql2')->table('lingkungan')->where('id', $id)->update([
                            'komentar_temuan' => $updated_komentar
                        ]);
                    } else {
                        // Loop through the komentar array and update where necessary
                        foreach ($komentar as $index => $item) {
                            if ($item === $old_koments && !$replaced) {
                                $komentar[$index] = $komens; // Update the value to the new komentar
                                $replaced = true; // Set the flag to true after the replacement
                            }
                        }

                        // Join the updated komentar array back into a string with '$' delimiter
                        $updated_komentar = implode('$', $komentar);

                        DB::connection('mysql2')->table('lingkungan')->where('id', $id)->update([
                            'komentar' => $updated_komentar
                        ]);
                    }
                    // Add the updated value3 to the $getKomen array
                    $getKomen[] = $value3;
                }

                break;
            default:
                # code...
                break;
        }
        $username = $request->session()->get('user_name');
        $dataarr = 'User:' . $username . ' ' . 'Tanggal:' . Carbon::now() . ' ' . 'Melakukan: edit komentar di qc emlasment';
        sendwhatsapp($dataarr);
    }


    public function editNilai(Request $request)
    {
        $id = $request->input('id');
        $type = $request->input('type');
        $nilaiArray = $request->input('nilai');

        // dd($nilaiArray);
        switch ($type) {
            case 'perumahan':
                $result = implode('$', $nilaiArray);

                try {
                    DB::connection('mysql2')->table('perumahan')
                        ->where('id', $id)
                        ->update([
                            'nilai' => $result
                        ]);

                    return response()->json(['status' => 'success']);
                } catch (\Throwable $th) {
                    return response()->json(['status' => 'error', 'message' => 'Error updating nilai']);
                }

                break;
            case 'lingkungan':

                $result = implode('$', $nilaiArray);

                try {
                    DB::connection('mysql2')->table('lingkungan')
                        ->where('id', $id)
                        ->update([
                            'nilai' => $result
                        ]);

                    return response()->json(['status' => 'success']);
                } catch (\Throwable $th) {
                    return response()->json(['status' => 'error', 'message' => 'Error updating nilai']);
                }


                break;
            case 'landscape':

                $result = implode('$', $nilaiArray);

                try {
                    DB::connection('mysql2')->table('landscape')
                        ->where('id', $id)
                        ->update([
                            'nilai' => $result
                        ]);

                    return response()->json(['status' => 'success']);
                } catch (\Throwable $th) {
                    return response()->json(['status' => 'error', 'message' => 'Error updating nilai']);
                }
                break;
            default:
                // Handle default case or any other type
                break;
        }

        $username = $request->session()->get('user_name');
        $dataarr = 'User:' . $username . ' ' . 'Tanggal:' . Carbon::now() . ' ' . 'Melakukan: edit Nilai di qc emlasment';
        sendwhatsapp($dataarr);
    }


    public function adingnewimg(Request $request)
    {

        // dd($test);
        $afd = $request->input('afd');
        $tanggal = $request->input('tanggal');
        $type = $request->input('type');
        $estate = $request->input('estate');
        $komentar = $request->input('komentar');
        $image = $request->file('image');
        // echo "AFD: $afd, Type: $type, Tanggal: $tanggal, Estate: $estate";
        function generateRandomString($length = 6)
        {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $randomString = '';

            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, strlen($characters) - 1)];
            }

            return $randomString;
        }

        // Usage:
        // dd($type);

        $randomNumber = generateRandomString(6);


        switch ($type) {
            case 'perumahan':
                $getdata = DB::connection('mysql2')->table('perumahan')
                    ->select('perumahan.*')
                    ->where('est', $estate)
                    ->where('afd', $afd)
                    ->where('datetime', 'LIKE', '%' . $tanggal . '%')
                    ->first();


                if (!$getdata) {

                    // No existing data found, perform insert operation
                    try {
                        $imgquery = '$$$$$$$$$$$$';
                        $newkomnt = '-$-$-$-$-$-$-$-$-$-$-$-$-' . '$' . $komentar;
                        $img_format = $image->getClientOriginalExtension();

                        $img_name = 'PPR_' . $randomNumber . '_' . $randomNumber . '_' . $estate . '_' . $afd . '.' . $img_format;

                        $newimg = $imgquery . '$' . $img_name;

                        DB::connection('mysql2')->table('perumahan')->insert([
                            'datetime' => $tanggal . ' ' . '06:00:00',
                            'est' => $estate,
                            'afd' => $afd,
                            'petugas' => '-',
                            'pendamping' => '-',
                            'foto_temuan' => $newimg,
                            'komentar_temuan' => '',
                            'nilai' => '0$0$0$0$0$0$0$0$0$0$0$0$0',
                            'komentar' => $newkomnt,
                            'app_version' => '1.5.35;13;RMX3521',
                        ]);

                        // Upload the image
                        $baseUrl = 'https://srs-ssms.com/qc_inspeksi/upload_emplasment.php';
                        $response = Http::attach('image', file_get_contents($image), $img_name)
                            ->post($baseUrl, ['action' => 'perum', 'filename' => $img_name]);

                        $responseData = $response->json();

                        // Check if image upload was successful
                        if (!$response->successful()) {
                            return response()->json(['status' => 'error', 'message' => 'Image upload failed']);
                        }

                        return response()->json(['status' => 'success']);
                    } catch (\Throwable $th) {
                        return response()->json(['status' => 'error', 'message' => 'Error inserting or uploading']);
                    }
                } else {

                    $id = $getdata->id;
                    $imgquery = $getdata->foto_temuan;
                    $komentarquery = $getdata->komentar;
                    $newkomnt = $komentarquery . '$' . $komentar;
                    $img_format = $image->getClientOriginalExtension();

                    $img_name = 'PPR_' . $randomNumber . '_' . $randomNumber . '_' . $estate . '_' . $afd . '.' . $img_format;

                    $newimg = $imgquery . '$' . $img_name;



                    // Perumahan; PPR
                    // Landscape:PLD
                    // Lingkungan:PLD

                    $baseUrl = 'https://srs-ssms.com/qc_inspeksi/upload_emplasment.php';
                    $response = Http::attach('image', file_get_contents($image), $img_name)
                        ->post($baseUrl, ['action' => 'perum', 'filename' => $img_name]);
                    $responseData = $response->json();

                    // Check if image upload was successful before database update
                    if (!$response->successful()) {
                        return response()->json(['status' => 'error', 'message' => 'Image upload failed']);
                    }


                    try {

                        DB::connection('mysql2')->table('perumahan')
                            ->where('id', $id)
                            ->update([
                                'komentar' => $newkomnt,
                                'foto_temuan' => $newimg,
                            ]);


                        return response()->json(['status' => 'success']);
                    } catch (\Throwable $th) {
                        return response()->json(['status' => 'error', 'message' => 'Error updating']);
                    }
                }




                break;
            case 'lingkungan':
                $getdata = DB::connection('mysql2')->table('lingkungan')
                    ->select('lingkungan.*')
                    ->where('est', $estate)
                    ->where('afd', $afd)
                    ->where('datetime', 'LIKE', '%' . $tanggal . '%')
                    ->first();

                // $getdata = json_decode($getdata, true);

                // dd($getdata);


                if (!$getdata) {

                    // No existing data found, perform insert operation
                    try {
                        $imgquery = '$$$$$$$$$$$$$';
                        $newkomnt = '-$-$-$-$-$-$-$-$-$-$-$-$-$-' . '$' . $komentar;
                        $img_format = $image->getClientOriginalExtension();

                        $img_name = 'PLD_' . $randomNumber . '_' . $randomNumber . '_' . $estate . '_' . $afd . '.' . $img_format;

                        $newimg = $imgquery . '$' . $img_name;

                        DB::connection('mysql2')->table('lingkungan')->insert([
                            'datetime' => $tanggal . ' ' . '06:00:00',
                            'est' => $estate,
                            'afd' => $afd,
                            'petugas' => '-',
                            'pendamping' => '-',
                            'foto_temuan' => $newimg,
                            'komentar_temuan' => '',
                            'nilai' => '0$0$0$0$0$0$0$0$0$0$0$0$0$0',
                            'komentar' => $newkomnt,
                            'app_version' => '1.5.35;13;RMX3521',
                        ]);

                        // Upload the image
                        $baseUrl = 'https://srs-ssms.com/qc_inspeksi/upload_emplasment.php';
                        $response = Http::attach('image', file_get_contents($image), $img_name)
                            ->post($baseUrl, ['action' => 'lingkn', 'filename' => $img_name]);

                        $responseData = $response->json();

                        // Check if image upload was successful
                        if (!$response->successful()) {
                            return response()->json(['status' => 'error', 'message' => 'Image upload failed']);
                        }

                        return response()->json(['status' => 'success']);
                    } catch (\Throwable $th) {
                        return response()->json(['status' => 'error', 'message' => 'Error inserting or uploading']);
                    }
                } else {
                    $id = $getdata->id;
                    $imgquery = $getdata->foto_temuan;
                    $komentarquery = $getdata->komentar;
                    $newkomnt = $komentarquery . '$' . $komentar;
                    $img_format = $image->getClientOriginalExtension();

                    $img_name = 'PLD_' . $randomNumber . '_' . $randomNumber . '_' . $estate . '_' . $afd . '.' . $img_format;

                    $newimg = $imgquery . '$' . $img_name;



                    // Perumahan; PPR
                    // Landscape:PLD
                    // Lingkungan:PLD

                    $baseUrl = 'https://srs-ssms.com/qc_inspeksi/upload_emplasment.php';
                    $response = Http::attach('image', file_get_contents($image), $img_name)
                        ->post($baseUrl, ['action' => 'lingkn', 'filename' => $img_name]);
                    $responseData = $response->json();

                    // Check if image upload was successful before database update
                    if (!$response->successful()) {
                        return response()->json(['status' => 'error', 'message' => 'Image upload failed']);
                    }


                    try {

                        DB::connection('mysql2')->table('lingkungan')
                            ->where('id', $id)
                            ->update([
                                'komentar' => $newkomnt,
                                'foto_temuan' => $newimg,
                            ]);


                        return response()->json(['status' => 'success']);
                    } catch (\Throwable $th) {
                        return response()->json(['status' => 'error', 'message' => 'Error updating']);
                    }
                }

                break;
            case 'landscape':
                $getdata = DB::connection('mysql2')->table('landscape')
                    ->select('landscape.*')
                    ->where('est', $estate)
                    ->where('afd', $afd)
                    ->where('datetime', 'LIKE', '%' . $tanggal . '%')
                    ->first();

                // $getdata = json_decode($getdata, true);

                // dd($getdata);


                if (!$getdata) {

                    // No existing data found, perform insert operation
                    try {
                        $imgquery = '$$$$';
                        $newkomnt = '$-$-$-$-' . '$' . $komentar;
                        $img_format = $image->getClientOriginalExtension();

                        $img_name = 'PLS_' . $randomNumber . '_' . $randomNumber . '_' . $estate . '_' . $afd . '.' . $img_format;

                        $newimg = $imgquery . '$' . $img_name;

                        DB::connection('mysql2')->table('landscape')->insert([
                            'datetime' => $tanggal . ' ' . '06:00:00',
                            'est' => $estate,
                            'afd' => $afd,
                            'petugas' => '-',
                            'pendamping' => '-',
                            'foto_temuan' => $newimg,
                            'komentar_temuan' => '',
                            'nilai' => '0$0$0$0$0',
                            'komentar' => $newkomnt,
                            'app_version' => '1.5.35;13;RMX3521',
                        ]);

                        // Upload the image
                        $baseUrl = 'https://srs-ssms.com/qc_inspeksi/upload_emplasment.php';
                        $response = Http::attach('image', file_get_contents($image), $img_name)
                            ->post($baseUrl, ['action' => 'lands', 'filename' => $img_name]);

                        $responseData = $response->json();

                        // Check if image upload was successful
                        if (!$response->successful()) {
                            return response()->json(['status' => 'error', 'message' => 'Image upload failed']);
                        }

                        return response()->json(['status' => 'success']);
                    } catch (\Throwable $th) {
                        return response()->json(['status' => 'error', 'message' => 'Error inserting or uploading']);
                    }
                } else {
                    $id = $getdata->id;
                    $imgquery = $getdata->foto_temuan;
                    $komentarquery = $getdata->komentar;
                    $newkomnt = $komentarquery . '$' . $komentar;
                    $img_format = $image->getClientOriginalExtension();

                    $img_name = 'PLS_' . $randomNumber . '_' . $randomNumber . '_' . $estate . '_' . $afd . '.' . $img_format;

                    $newimg = $imgquery . '$' . $img_name;



                    // Perumahan; PPR
                    // Landscape:PLD
                    // Lingkungan:PLD

                    $baseUrl = 'https://srs-ssms.com/qc_inspeksi/upload_emplasment.php';
                    $response = Http::attach('image', file_get_contents($image), $img_name)
                        ->post($baseUrl, ['action' => 'lands', 'filename' => $img_name]);
                    $responseData = $response->json();

                    // Check if image upload was successful before database update
                    if (!$response->successful()) {
                        return response()->json(['status' => 'error', 'message' => 'Image upload failed']);
                    }


                    try {

                        DB::connection('mysql2')->table('landscape')
                            ->where('id', $id)
                            ->update([
                                'komentar' => $newkomnt,
                                'foto_temuan' => $newimg,
                            ]);


                        return response()->json(['status' => 'success']);
                    } catch (\Throwable $th) {
                        return response()->json(['status' => 'error', 'message' => 'Error updating']);
                    }
                }




                break;
            default:
                // Handle default case or any other type
                break;
        }

        $username = $request->session()->get('user_name');
        $dataarr = 'User:' . $username . ' ' . 'Tanggal:' . Carbon::now() . ' ' . 'Melakukan: tambah foto di qc emlasment';
        sendwhatsapp($dataarr);
    }
}
