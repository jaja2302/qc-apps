<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use DateTime;
use Illuminate\Support\Facades\Session;
use App\DataTables\InspeksimutuancakDataTable;
use App\Models\mutu_ancak;
use Yajra\DataTables\Facades\Datatables;

require_once(app_path('helpers.php'));

class inspectController extends Controller
{

    public function getFindData(Request $request)
    {

        //refvisi
        $estatex = DB::connection('mysql2')->table('estate')
            ->select('estate.*')
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->where('wil.regional', $request->get('regional'))
            ->whereNotIn('estate.est', ['CWS1', 'CWS2', 'CWS3'])
            ->where('estate.emp', '!=', 1)
            ->where('estate.est', '!=', 'PLASMA')
            ->pluck('est');


        $queryMtTrans = DB::connection('mysql2')->table('mutu_transport')
            ->select("mutu_transport.*")
            ->whereIn('estate', $estatex)
            ->where('datetime', 'like', '%' . $request->get('date') . '%')
            ->orderBy('estate', 'asc')
            ->orderBy('afdeling', 'asc')
            ->orderBy('blok', 'asc')
            ->orderBy('datetime', 'asc')
            ->get();

        // $dataTrans = $queryMtTrans->groupBy('estate');
        $dataTrans = $queryMtTrans->groupBy(function ($item) {
            return $item->estate . ' ' . $item->afdeling . ' ' . $item->blok;
        });
        $dataTrans = json_decode($dataTrans, true);

        $transv2 = $queryMtTrans->groupBy(['estate', 'afdeling']);
        $transv2 = json_decode($transv2, true);

        $datestrans = [];

        foreach ($transv2 as $key => $items) {
            $nestedDates = [];

            foreach ($items as $nestedItems) {
                $uniqueDates = array_unique(array_column($nestedItems, 'datetime'));
                $formattedDates = array_map(function ($date) {
                    return date('Y-m-d', strtotime($date));
                }, $uniqueDates);

                $nestedDates = array_merge($nestedDates, $formattedDates);
            }

            $datestrans[$key] = array_values(array_unique($nestedDates));
        }

        $queryFLWs = DB::connection('mysql2')->table('follow_up_ma')
            ->select("follow_up_ma.*")
            ->whereIn('estate', $estatex)
            ->where('waktu_temuan', 'like', '%' . $request->get('date') . '%')
            ->where('estate', 'not like', '%Plasma%')
            ->orderBy('estate', 'asc')
            ->orderBy('afdeling', 'asc')
            ->orderBy('blok', 'asc')
            ->orderBy('waktu_temuan', 'asc')
            ->get();


        $queryFLW = $queryFLWs->groupBy(function ($item) {
            return $item->estate . ' ' . $item->afdeling . ' ' . $item->blok;
        });
        $queryFLW = json_decode($queryFLW, true);
        $ancakv2 = $queryFLWs->groupBy(['estate', 'afdeling']);
        $ancakv2 = json_decode($ancakv2, true);

        $datesAncak = [];

        foreach ($ancakv2 as $key => $items) {
            $nestedDates = [];

            foreach ($items as $nestedItems) {
                $uniqueDates = array_unique(array_column($nestedItems, 'waktu_temuan'));
                $formattedDates = array_map(function ($date) {
                    return date('Y-m-d', strtotime($date));
                }, $uniqueDates);

                $nestedDates = array_merge($nestedDates, $formattedDates);
            }

            $datesAncak[$key] = array_values(array_unique($nestedDates));
        }

        $queryMutuBh = DB::connection('mysql2')->table('mutu_buah')
            ->select("mutu_buah.*")
            ->whereIn('estate', $estatex)
            ->where('datetime', 'like', '%' . $request->get('date') . '%')
            ->orderBy('estate', 'asc')
            ->orderBy('afdeling', 'asc')
            ->orderBy('blok', 'asc')
            ->orderBy('datetime', 'asc')
            ->get();
        // $mutuBuah = $queryMutuBh->groupBy('estate');
        $mutuBuah = $queryMutuBh->groupBy(function ($item) {
            return $item->estate . ' ' . $item->afdeling . ' ' . $item->blok;
        });
        $mutuBuah = json_decode($mutuBuah, true);
        $mtbuahv2 = $queryMutuBh->groupBy(['estate', 'afdeling']);
        $mtbuahv2 = json_decode($mtbuahv2, true);

        $datesBuah = [];

        foreach ($mtbuahv2 as $key => $items) {
            $nestedDates = [];

            foreach ($items as $nestedItems) {
                $uniqueDates = array_unique(array_column($nestedItems, 'datetime'));
                $formattedDates = array_map(function ($date) {
                    return date('Y-m-d', strtotime($date));
                }, $uniqueDates);

                $nestedDates = array_merge($nestedDates, $formattedDates);
            }

            $datesBuah[$key] = array_values(array_unique($nestedDates));
        }


        // dd($datesBuah, $datestrans, $datesAncak);

        $mutu_all = [];

        foreach ($dataTrans as $key => $items) {
            if (!array_key_exists($key, $mutu_all)) {
                $mutu_all[$key] = [
                    'mutu_transport' => [],
                    'mutu_ancak' => [],
                    'mutu_buah' => [],
                ];
            }
            $keyOnly = explode(' ', $key)[0];

            foreach ($items as &$item) {
                $date = substr($item['datetime'], 0, 10);


                $visit_count = 1; // Initialize visit count to 0

                // Get visit count from datestrans array
                if (isset($datestrans[$keyOnly])) {
                    $visit = array_search($date, $datestrans[$keyOnly]);
                    if ($visit !== false) {
                        $visit_count = $visit + 1; // Update visit count
                    }
                }

                $item['visit'] = $visit_count;

                // Check if foto_temuan or foto_fu is not empty and process them
                if (!empty($item['foto_temuan']) || !empty($item['foto_fu'])) {
                    // Remove brackets and explode the strings into arrays
                    $foto_temuan = explode(',', str_replace(['[', ']'], '', $item['foto_temuan']));
                    $komentar = explode(',', str_replace(['[', ']'], '', $item['komentar']));

                    // Loop through each foto_temuan and komentar
                    for ($i = 0; $i < count($foto_temuan); $i++) {
                        // Copy the item
                        $new_item = $item;

                        // Replace foto_temuan and komentar with their respective values
                        $new_item['foto_temuan'] = trim($foto_temuan[$i]); // trim is used to remove any unwanted spaces
                        $new_item['komentar'] = trim($komentar[$i]); // trim is used to remove any unwanted spaces


                        $mutu_all[$key]['mutu_transport'][] = $new_item;
                    }
                }
            }
        }

        foreach ($queryFLW as $key => $items) {
            if (!array_key_exists($key, $mutu_all)) {
                $mutu_all[$key] = [
                    'mutu_transport' => [],
                    'mutu_ancak' => [],
                    'mutu_buah' => [],
                ];
            }
            $keyOnly = explode(' ', $key)[0];

            foreach ($items as $item) {
                $date = substr($item['waktu_temuan'], 0, 10);


                $visit_count = 1; // Initialize visit count to 0

                // Get visit count from datestrans array
                if (isset($datesAncak[$keyOnly])) {
                    $visit = array_search($date, $datesAncak[$keyOnly]);
                    if ($visit !== false) {
                        $visit_count = $visit + 1; // Update visit count
                    }
                }

                $item['visit'] = $visit_count;
                if (!empty($item['foto_temuan1']) || !empty($item['foto_fu1']) || !empty($item['komentar'])) {
                    $mutu_all[$key]['mutu_ancak'][] = $item;
                }
            }
        }

        foreach ($mutuBuah as $key => $items) {
            if (!array_key_exists($key, $mutu_all)) {
                $mutu_all[$key] = [
                    'mutu_transport' => [],
                    'mutu_ancak' => [],
                    'mutu_buah' => [],
                ];
            }
            $keyOnly = explode(' ', $key)[0];


            foreach ($items as $item) {
                $date = substr($item['datetime'], 0, 10);
                $visit_count = 1; // Initialize visit count to 0

                // Get visit count from datestrans array
                if (isset($datesBuah[$keyOnly])) {
                    $visit = array_search($date, $datesBuah[$keyOnly]);
                    if ($visit !== false) {
                        $visit_count = $visit + 1; // Update visit count
                    }
                }

                $item['visit'] = $visit_count;
                if (!empty($item['foto_temuan']) || !empty($item['foto_fu'])) {
                    // Remove brackets and explode the strings into arrays
                    $foto_temuan = explode(';', str_replace(['[', ']'], '', $item['foto_temuan']));
                    $komentar = explode(';', str_replace(['[', ']'], '', $item['komentar']));

                    // Loop through each foto_temuan and komentar
                    for ($i = 0; $i < count($foto_temuan); $i++) {
                        // Copy the item
                        $new_item = $item;

                        // Replace foto_temuan and komentar with their respective values
                        $new_item['foto_temuan'] = trim($foto_temuan[$i]); // trim is used to remove any unwanted spaces
                        $new_item['komentar'] = trim($komentar[$i]); // trim is used to remove any unwanted spaces

                        $mutu_all[$key]['mutu_buah'][] = $new_item;
                    }
                }
            }
        }

        $mutu_all = array_filter($mutu_all, function ($item) {
            return !empty($item['mutu_transport']) || !empty($item['mutu_ancak']) || !empty($item['mutu_buah']);
        });



        // dd($mutu_all);

        foreach ($mutu_all as $outerKey => $value) {
            // Check if the key contains "KTE OE"
            if (strpos($outerKey, "KTE OE") !== false) {
                // Update the "visit" value to 1 in "mutu_transport"
                if (isset($value['mutu_transport']) && is_array($value['mutu_transport'])) {
                    foreach ($value['mutu_transport'] as $innerKey => &$transport) {
                        if (isset($transport['visit'])) {
                            $dateTime = new DateTime($transport['datetime']);

                            // Format the DateTime object to "yyyy-mm-dd"
                            $formattedDate = $dateTime->format("Y-m-d");

                            // Array of check dates
                            $checkdata = ['2023-11-08', '2023-11-09', '2023-11-10'];
                            // Check if the formatted date is in the array
                            if (in_array($formattedDate, $checkdata, true)) {
                                $mutu_all[$outerKey]['mutu_transport'][$innerKey]['visit'] = 1;
                            } else {
                                $mutu_all[$outerKey]['mutu_transport'][$innerKey]['visit'] = $transport['visit'];
                            }
                        }
                    }
                }


                // Update the "visit" value to 1 in "mutu_ancak"
                if (isset($value['mutu_ancak']) && is_array($value['mutu_ancak'])) {
                    foreach ($value['mutu_ancak'] as $innerKey => &$ancak) {
                        if (isset($ancak['visit'])) {

                            // dd($ancak);

                            $dateTime = new DateTime($ancak['waktu_temuan']);

                            // Format the DateTime object to "yyyy-mm-dd"
                            $formattedDate = $dateTime->format("Y-m-d");

                            // Array of check dates
                            $checkdata = ['2023-11-08', '2023-11-09', '2023-11-10'];
                            // Check if the formatted date is in the array
                            if (in_array($formattedDate, $checkdata, true)) {
                                $mutu_all[$outerKey]['mutu_ancak'][$innerKey]['visit'] = 1;
                            } else {
                                $mutu_all[$outerKey]['mutu_ancak'][$innerKey]['visit'] = $ancak['visit'];
                            }
                        }
                    }
                }

                // Update the "visit" value to 1 in "mutu_buah"
                if (isset($value['mutu_buah']) && is_array($value['mutu_buah'])) {
                    foreach ($value['mutu_buah'] as $innerKey => &$buah) {
                        if (isset($buah['visit'])) {
                            $dateTime = new DateTime($buah['datetime']);

                            // Format the DateTime object to "yyyy-mm-dd"
                            $formattedDate = $dateTime->format("Y-m-d");

                            // Array of check dates
                            $checkdata = ['2023-11-08', '2023-11-09', '2023-11-10'];
                            // Check if the formatted date is in the array
                            if (in_array($formattedDate, $checkdata, true)) {
                                $mutu_all[$outerKey]['mutu_buah'][$innerKey]['visit'] = 1;
                            } else {
                                $mutu_all[$outerKey]['mutu_buah'][$innerKey]['visit'] = $buah['visit'];
                            }
                        }
                    }
                }
            }
        }


        $groupedArray = array();

        foreach ($mutu_all as $key => $value) {
            $groupKeys = ['KTE4', 'LME1', 'LME2'];
            $found = false;

            foreach ($groupKeys as $groupKey) {
                if (strpos($key, $groupKey) !== false) {
                    $found = true;
                    $groupKey = $groupKey;
                    break;
                }
            }

            if (!$found) {
                $groupKey = substr($key, 0, 3);
            }

            if (!array_key_exists($groupKey, $groupedArray)) {
                $groupedArray[$groupKey] = array();
            }

            $groupedArray[$groupKey][$key] = $value;
        }

        // dd($mutu_all,$groupedArray);
        // dd($groupedArray);

        $mtTrans = DB::connection('mysql2')->table('mutu_transport')
            ->selectRaw("mutu_transport.*, DATE_FORMAT(datetime, '%Y-%m-%d') AS formatted_date")
            ->whereIn('estate', $estatex)
            ->where('datetime', 'like', '%' . $request->get('date') . '%')
            ->where('foto_temuan', '!=', ' ')
            ->orderBy('formatted_date', 'asc')
            ->orderBy('estate', 'asc')
            ->orderBy('afdeling', 'asc')
            ->orderBy('blok', 'asc')

            ->get();
        // dd($mtTrans);

        $mtTrans = $mtTrans->groupBy(['estate', 'formatted_date', 'afdeling']);
        $mtTrans = json_decode($mtTrans, true);


        $mtbuah = DB::connection('mysql2')->table('mutu_buah')
            ->selectRaw("mutu_buah.*, DATE_FORMAT(datetime, '%Y-%m-%d') AS formatted_date")
            ->whereIn('estate', $estatex)
            ->where('datetime', 'like', '%' . $request->get('date') . '%')
            ->where('foto_temuan', '!=', ' ')
            ->orderBy('formatted_date', 'asc')
            ->orderBy('estate', 'asc')
            ->orderBy('afdeling', 'asc')
            ->orderBy('blok', 'asc')

            ->get();

        $mtbuah = $mtbuah->groupBy(['estate', 'formatted_date', 'afdeling']);
        $mtbuah = json_decode($mtbuah, true);



        $mtancak = DB::connection('mysql2')->table('follow_up_ma')
            ->selectRaw("follow_up_ma.*, DATE_FORMAT(waktu_temuan, '%Y-%m-%d') AS formatted_date")
            ->whereIn('estate', $estatex)
            ->where('waktu_temuan', 'like', '%' . $request->get('date') . '%')
            ->orderBy('formatted_date', 'asc')
            ->orderBy('estate', 'asc')
            ->orderBy('afdeling', 'asc')
            ->orderBy('blok', 'asc')
            ->get();

        $mtancak = $mtancak->groupBy(['estate', 'formatted_date', 'afdeling']);
        $mtancak = json_decode($mtancak, true);

        $allDatestrans = [];

        // dd($mtTrans, $mtbuah, $mtancak);
        foreach ($mtTrans as $key => $value) {
            foreach ($value as $dateKey => $dateValue) {
                // dd($dateValue);
                $allDatestrans[$key][$dateKey] = ['dates' => $dateKey];
            }
        }

        $allDatesancak = [];

        foreach ($mtancak as $key => $value) {
            foreach ($value as $dateKey => $dateValue) {
                $allDatesancak[$key][$dateKey] = ['dates' => $dateKey];
            }
        }

        $allDatesbuah = [];

        foreach ($mtbuah as $key => $value) {
            foreach ($value as $dateKey => $dateValue) {
                $allDatesbuah[$key][$dateKey] = ['dates' => $dateKey];
            }
        }

        $mergedDates = array_merge_recursive($allDatestrans, $allDatesancak, $allDatesbuah);

        // Sort the merged array
        foreach ($mergedDates as $estate => $estateDates) {
            uksort($mergedDates[$estate], function ($a, $b) {
                return strtotime($a) - strtotime($b);
            });
        }

        // Displaying the sorted merged dates
        // dd($mergedDates);

        $regs =   $request->get('regional');
        // dd($regs);

        if ($regs == 3) {
            foreach ($mergedDates as $key => $value) {
                // dd($value);
                $inc = 1;
                foreach ($value as $ke2 => $value2) {
                    # code...
                    $getdate[$key][$ke2] = $inc++;

                    // break;
                }
            }

            $dateArr = [];

            foreach ($getdate as $key => $value) {
                foreach ($value as $date => $number) {
                    if ($number === 1) {
                        $dateArr[$key][$date] = $number;
                        break; // Stop the loop after finding the value equal to 1
                    }
                }
            }


            foreach ($dateArr as $key => $value) {
                foreach ($value as $key2 => $value2) {
                    $startDate = $key2;
                    $endDate = date("Y-m-t", strtotime($startDate)); // Get the last day of the month

                    $currentDate = $startDate;
                    $counter = 1;
                    while ($currentDate <= $endDate) {
                        if (!isset($dateArr[$key][$currentDate])) {
                            $dateArr[$key][$currentDate] = $value2;
                        } else {
                            $dateArr[$key][$currentDate] = $counter;
                        }

                        $currentDate = date("Y-m-d", strtotime($currentDate . " +1 day"));
                        if ($counter % 7 === 0) {
                            $value2 += 1;
                        }
                        $counter++;
                    }
                }
            }
        } else {
            foreach ($mergedDates as $key => $value) {
                // dd($value);
                $inc = 1;
                foreach ($value as $ke2 => $value2) {
                    # code...
                    $dateArr[$key][$ke2] = $inc++;

                    // break;
                }
            }
        }

        // dd($dateArr);

        $newtrans = array();

        // dd($mtTrans, $getdate);

        foreach ($mtTrans as $date => $items) {
            foreach ($items as $category => $categoryItems) {
                foreach ($categoryItems as $itemKey => $itemData) {
                    foreach ($itemData as $key => $value) {
                        $newkeyafd = $value['estate'] . ' ' . $value['afdeling'] . ' ' . $value['blok'];

                        // Create the 'visit' array based on the date
                        foreach ($dateArr as $key2 => $value2) {
                            if ($key2 == $date) {
                                foreach ($value2 as $key3 => $value3) if ($key3 == $category) {
                                    # code...
                                    $value['visit'] = $value3;
                                }
                            }
                        }

                        $newtrans[$value['estate']][$newkeyafd]['mutu_transport'][] = $value;
                    }
                }
            }
        }
        $newBuah = array();

        // dd($mtTrans, $dateArr);

        foreach ($mtbuah as $date => $items) {
            foreach ($items as $category => $categoryItems) {
                foreach ($categoryItems as $itemKey => $itemData) {
                    foreach ($itemData as $key => $value) {
                        $newkeyafd = $value['estate'] . ' ' . $value['afdeling'] . ' ' . $value['blok'];

                        // Create the 'visit' array based on the date
                        foreach ($dateArr as $key2 => $value2) {
                            if ($key2 == $date) {
                                foreach ($value2 as $key3 => $value3) if ($key3 == $category) {
                                    # code...
                                    $value['visit'] = $value3;
                                }
                            }
                        }

                        $newBuah[$value['estate']][$newkeyafd]['mutu_transport'][] = $value;
                    }
                }
            }
        }


        // dd($mtTrans, $dateArr);
        $newAncak = array();

        foreach ($mtancak as $date => $items) {
            foreach ($items as $category => $categoryItems) {
                foreach ($categoryItems as $itemKey => $itemData) {
                    foreach ($itemData as $key => $value) {
                        $newkeyafd = $value['estate'] . ' ' . $value['afdeling'] . ' ' . $value['blok'];

                        // Create the 'visit' array based on the date
                        foreach ($dateArr as $key2 => $value2) {
                            if ($key2 == $date) {
                                foreach ($value2 as $key3 => $value3) if ($key3 == $category) {
                                    # code...
                                    $value['visit'] = $value3;
                                }
                            }
                        }

                        $newAncak[$value['estate']][$newkeyafd]['mutu_transport'][] = $value;
                    }
                }
            }
        }

        $mergedArrays = array_merge_recursive($newtrans, $newAncak, $newBuah);


        // dd($mergedArrays['KTE'], $getdate);
        // Iterate through the merged array and fill missing keys with empty arrays
        foreach ($mergedArrays as $estate => &$estateData) {
            foreach ($estateData as $category => &$categoryData) {
                // Ensure all necessary keys exist, else set them as empty arrays
                $categoryData += [
                    'mutu_transport' => [],
                    'mutu_ancak' => [],
                    'mutu_buah' => [],
                ];
            }
        }

        unset($estateData, $categoryData);

        // dd($mergedArrays);
        // dd($mergedArrays['BHE'], $getdate);
        // dd($mergedArrays['KTE'], $groupedArray);
        $item_counts = [];

        foreach ($mergedArrays as $key => $value) {
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
            $item_counts[$key]['total_temuan'] = $count; // Renamed this key to 'total_values'
            $item_counts[$key]['foto_temuan'] = $total_foto_temuan;
            $item_counts[$key]['followUp'] = $total_followUP;
            $item_counts[$key]['tuntas'] = $total_followUP;
            $item_counts[$key]['no_tuntas'] = $total_foto_temuan - $total_followUP;
            $item_counts[$key]['perTuntas'] = ($total_foto_temuan - $total_followUP == 0) ? 0 : round($total_followUP / $total_foto_temuan * 100, 2);
            $item_counts[$key]['perNoTuntas'] = ($total_foto_temuan - $total_followUP == 0) ? 0 : round(($total_foto_temuan - $total_followUP) / $total_foto_temuan * 100, 2);
            $item_counts[$key]['visit'] = $highest_visit;
        }


        // Example usage:

        // dd($item_counts);
        $arrView = array();

        // $arrView['dataResFind'] = $dataResFind;
        $arrView['dataResFindes'] = $item_counts;

        echo json_encode($arrView);
        exit();
    }

    public function changeDataInspeksi(Request $request)
    {


        // $regs = $request->get('regional');
        // $tanggal = $request->get('date');
        $regional = $request->get('regional');
        $bulan = $request->get('date');

        // dd($bulan);
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
                    if ($est['est'] === 'LDE' || $est['est'] === 'SRE' || $est['est'] === 'SKE') {
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
                    if ($est['est'] === 'LDE' || $est['est'] === 'SRE' || $est['est'] === 'SKE') {
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
                    $nilai_input = 0;
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
                        $perPl = round(($totalpelepah_s / $totalPokok) * 100, 3);
                    } else {
                        $perPl = 0;
                    }





                    $nonZeroValues = array_filter([$totalP_panen, $totalK_panen, $totalPTgl_panen, $totalbhts_panen, $totalbhtm1_panen, $totalbhtm2_panen, $totalbhtm3_oanen]);

                    if (!empty($nonZeroValues)) {
                        $rekap[$key][$key1][$key2]['check_datacak'] = 'ada';
                        // $rekap[$key][$key1][$key2]['skor_brd'] = $skor_brd = skor_brd_ma($brdPerjjg);
                        // $rekap[$key][$key1][$key2]['skor_ps'] = $skor_ps = skor_palepah_ma($perPl);
                    } else {
                        $rekap[$key][$key1][$key2]['check_datacak'] = 'kosong';
                        // $rekap[$key][$key1][$key2]['skor_brd'] = $skor_brd = 0;
                        // $rekap[$key][$key1][$key2]['skor_ps'] = $skor_ps = 0;
                    }

                    // $ttlSkorMA = $skor_bh + $skor_brd + $skor_ps;
                    $ttlSkorMA =  skor_buah_Ma($sumPerBH) + skor_brd_ma($brdPerjjg) + skor_palepah_ma($perPl);

                    $rekap[$key][$key1][$key2]['pokok_samplecak'] = $totalPokok;
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
                    // $rekap[$key][$key1]['check_data'] = 'ada';
                    $check_data = 'ada';
                    // $rekap[$key][$key1]['skor_brd'] = $skor_brd = skor_brd_ma($brdPerjjgEst);
                    // $rekap[$key][$key1]['skor_ps'] = $skor_ps = skor_palepah_ma($perPlEst);
                } else {
                    // $rekap[$key][$key1]['check_data'] = 'kosong';
                    $check_data = 'kosong';
                    // $rekap[$key][$key1]['skor_brd'] = $skor_brd = 0;
                    // $rekap[$key][$key1]['skor_ps'] = $skor_ps = 0;
                }

                // $totalSkorEst = $skor_bh + $skor_brd + $skor_ps;

                $totalSkorEst =  skor_brd_ma($brdPerjjgEst) + skor_buah_Ma($sumPerBHEst) + skor_palepah_ma($perPlEst);
                //PENAMPILAN UNTUK PERESTATE
                $rekap[$key][$key1]['est']['estancak'] = [
                    'pokok_samplecak' => $pokok_panenEst,
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

                if ($key1 === 'LDE' || $key1 === 'SRE' || $key1 === 'SKE') {

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

            $rekap[$key]['wil']['wilancak'] = [
                'data' =>  $data,
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
                'jjgperBuahcak' =>  number_format($sumPerBH, 3),
                'palepah_pokokcak' =>  $pelepah_swil,
                'palepah_percak' =>  $perPiWil,
                'skor_bhcak' =>  skor_buah_Ma($sumPerBHWil),
                'skor_brdcak' =>  skor_brd_ma($brdPerwil),
                'skor_pscak' =>  skor_palepah_ma($perPiWil),
                'skor_akhircak' =>  $totalWil,
                'est' => $key,
                'afd' => 'wil',
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
                        // $rekap[$key][$key1][$key2]['skor_masak'] = $skor_masak =  skor_buah_masak_mb($PerMsk);
                        // $rekap[$key][$key1][$key2]['skor_over'] = $skor_over =  skor_buah_over_mb($PerOver);
                        // $rekap[$key][$key1][$key2]['skor_jjgKosong'] = $skor_jjgKosong =  skor_jangkos_mb($Perkosongjjg);
                        // $rekap[$key][$key1][$key2]['skor_vcut'] = $skor_vcut =  skor_vcut_mb($PerVcut);
                        // $rekap[$key][$key1][$key2]['skor_kr'] = $skor_kr =  skor_abr_mb($per_kr);
                    } else {
                        $rekap[$key][$key1][$key2]['check_databh'] = 'kosong';
                        // $rekap[$key][$key1][$key2]['skor_masak'] = $skor_masak = 0;
                        // $rekap[$key][$key1][$key2]['skor_over'] = $skor_over = 0;
                        // $rekap[$key][$key1][$key2]['skor_jjgKosong'] = $skor_jjgKosong = 0;
                        // $rekap[$key][$key1][$key2]['skor_vcut'] = $skor_vcut =  0;
                        // $rekap[$key][$key1][$key2]['skor_kr'] = $skor_kr = 0;
                    }

                    // $totalSkor = $skor_mentah + $skor_masak + $skor_over + $skor_jjgKosong + $skor_vcut + $skor_kr;


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
                // if ($sum_kr != 0) {
                //     $total_kr = round($sum_kr / $dataBLok, 3);
                // } else {
                //     $total_kr = 0;
                // }

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
        // dd($ancakRegss2['SBE']['OE'], $dataMTTransRegs2['SBE']['OE'], $transNewdata['SBE']['OE']);
        $defaultMtTrans = array();
        foreach ($queryEste as $est) {
            // dd($est);
            foreach ($queryAfd as $afd) {
                // dd($afd);
                if ($est['est'] == $afd['est']) {
                    if ($est['est'] === 'LDE' || $est['est'] === 'SRE' || $est['est'] === 'SKE') {
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

        // dd($transNewdata);

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
        // dd($rekap[5]);

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

        if ($regional == 1) {
            $muaarray = [
                'SRE' => $rekap[3]['SRE']['estate'] ?? [],
                'LDE' => $rekap[3]['LDE']['estate'] ?? [],
                'SKE' => $rekap[3]['SKE']['estate'] ?? [],
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


            $rekap[3]['MUA']['PT.MUA'] = $resultmua;

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

        // dd($rekap);
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

        $totalSkorEsttrans = skor_brd_tinggal($brdPertph) + skor_buah_tinggal($buahPerTPH);
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
        // dd($dataReg);
        return view('Qcinspeksi.dataInspeksi', ['data' => $rekap, 'reg' => $regional, 'bulan' => $bulan, 'datareg' => $dataReg]);
    }

    public function dashboard_inspeksi(Request $request)
    {



        $bulan = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
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

        $querySidaks = DB::connection('mysql2')->table('mutu_buah')
            ->select(DB::raw('DISTINCT YEAR(datetime) as year'))
            ->orderBy('year', 'desc')
            ->get();
        $querySidak_ancak = DB::connection('mysql2')->table('mutu_ancak_new')
            ->select(DB::raw('DISTINCT YEAR(datetime) as year'))
            ->orderBy('year', 'desc')
            ->get();
        $querySidaks_transport = DB::connection('mysql2')->table('mutu_transport')
            ->select(DB::raw('DISTINCT YEAR(datetime) as year'))
            ->orderBy('year', 'desc')
            ->get();


        $years = [];

        foreach ($querySidaks as $sidak) {
            $years[] = $sidak->year;
        }

        foreach ($querySidak_ancak as $sidak) {
            $years[] = $sidak->year;
        }

        foreach ($querySidaks_transport as $sidak) {
            $years[] = $sidak->year;
        }
        $listEst = DB::connection('mysql2')->table('estate')
            ->whereNotIn('estate.est', ['CWS1', 'CWS2', 'CWS3'])
            ->where('estate.emp', '!=', 1)
            ->whereIn('wil', [1, 2, 3])->pluck('est');
        $listEst = json_decode($listEst, true);
        // Remove duplicates and sort the array
        $years = array_unique($years);
        $optionREg = DB::connection('mysql2')->table('reg')
            ->select('reg.*')
            ->whereNotIn('reg.id', [5])
            // ->where('wil.regional', 1)
            ->get();

        $filterGrafik = DB::connection('mysql2')->table('estate')
            ->whereNotIn('estate.est', ['CWS1', 'CWS2', 'CWS3'])
            ->where('estate.emp', '!=', 1)
            ->get();

        $filterGrafik = json_decode($filterGrafik, true);
        $groupedArray = [];

        foreach ($filterGrafik as $item) {
            $wil = $item['wil'];
            $groupedArray[$wil][] = $item['est'];
        }

        $optionREg = json_decode($optionREg, true);
        //  dd($optionREg);
        // dd($dataSkor_ancak, $dataSkor_trans, $dataSkor_ancak);
        return view('Qcinspeksi.index', [
            'arrHeader' => $arrHeader,
            'arrHeaderSc' => $arrHeaderSc,
            'arrHeaderTrd' => $arrHeaderTrd,
            'arrHeaderReg' => $arrHeaderReg,
            'groupedArray' => $groupedArray,

            'listEstate' => $listEst,

            'datefilter' => $years,
            'option_reg' => $optionREg
        ]);
    }


    public function detailInpeksi($est, $afd, $date)
    {
        $mutuAncak = DB::connection('mysql2')->table('mutu_ancak_new')
            ->select("mutu_ancak_new.*", DB::raw('DATE_FORMAT(mutu_ancak_new.datetime, "%M") as bulan'), DB::raw('DATE_FORMAT(mutu_ancak_new.datetime, "%Y") as tahun'))
            ->where('datetime', 'like', '%' . $date . '%')
            ->where('mutu_ancak_new.estate', $est)
            ->where('mutu_ancak_new.afdeling', $afd)
            ->get();

        $mutuAncak = $mutuAncak->groupBy(['estate', 'afdeling']);
        $mutuAncak = json_decode($mutuAncak, true);

        $mutuBuah = DB::connection('mysql2')->table('mutu_buah')
            ->select("mutu_buah.*", DB::raw('DATE_FORMAT(mutu_buah.datetime, "%M") as bulan'), DB::raw('DATE_FORMAT(mutu_buah.datetime, "%Y") as tahun'))
            ->where('datetime', 'like', '%' . $date . '%')
            ->where('mutu_buah.estate', $est)
            ->where('mutu_buah.afdeling', $afd)

            ->get();
        $mutuBuah = $mutuBuah->groupBy(['estate', 'afdeling']);
        $mutuBuah = json_decode($mutuBuah, true);

        $mutuTransport = DB::connection('mysql2')->table('mutu_transport')
            ->select("mutu_transport.*", DB::raw('DATE_FORMAT(mutu_transport.datetime, "%M") as bulan'), DB::raw('DATE_FORMAT(mutu_transport.datetime, "%Y") as tahun'))
            ->where('datetime', 'like', '%' . $date . '%')
            ->where('mutu_transport.estate', $est)
            ->where('mutu_transport.afdeling', $afd)

            ->get();
        $mutuTransport = $mutuTransport->groupBy(['estate', 'afdeling']);
        $mutuTransport = json_decode($mutuTransport, true);

        // dd($mutuBuah);
        $datas = array();
        $img = array();
        foreach ($mutuAncak as $key => $value) {
            $inc = 0;
            foreach ($value as $key2 => $value2) {
                foreach ($value2 as $key3 => $value3) {
                    // dd($value3);
                    $datas[] = $value3;
                    if (!empty($value3['foto_temuan'])) {
                        $img[$key][$inc]['foto'] = $value3['foto_temuan'];
                        $img[$key][$inc]['title'] = $value3['estate'] . ' ' .  $value3['afdeling'] . ' - ' . $value3['blok'];
                        $inc++;
                    }
                }
            }
        }
        $buah = array();
        $BuahImg = array();
        foreach ($mutuBuah as $key => $value) {
            $inc = 0;
            foreach ($value as $key2 => $value2) {
                foreach ($value2 as $key3 => $value3) {
                    // dd($value3);
                    $buah[] = $value3;
                    if (!empty($value3['foto_temuan'])) {
                        $BuahImg[$key][$inc]['foto'] = $value3['foto_temuan'];
                        $BuahImg[$key][$inc]['title'] = $value3['estate'] . ' ' .  $value3['afdeling'] . ' - ' . $value3['blok'];
                        $inc++;
                    }
                }
            }
        }
        $Trans = array();
        $TransImg = array();
        foreach ($mutuTransport as $key => $value) {
            $inc = 0;
            foreach ($value as $key2 => $value2) {
                foreach ($value2 as $key3 => $value3) {
                    // dd($value3);
                    $Trans[] = $value3;
                    if (!empty($value3['foto_temuan'])) {
                        $TransImg[$key][$inc]['foto'] = $value3['foto_temuan'];
                        $TransImg[$key][$inc]['title'] = $value3['estate'] . ' ' .  $value3['afdeling'] . ' - ' . $value3['blok'];
                        $inc++;
                    }
                }
            }
        }
        // dd($BuahImg);
        $imgNew = array();
        foreach ($img as $key => $value) {
            foreach ($value as $key2 => $value2) {
                $fotoArr = explode(';', $value2['foto']);
                $value2['foto'] = $fotoArr;
                $imgNew[] = $value2;
            }
        }
        $imgBuah = array();
        foreach ($BuahImg as $key => $value) {
            foreach ($value as $key2 => $value2) {
                $fotoArr = explode(';', $value2['foto']);
                $value2['foto'] = $fotoArr;
                $imgBuah[] = $value2;
            }
        }
        $imgTrans = array();
        foreach ($TransImg as $key => $value) {
            foreach ($value as $key2 => $value2) {
                $fotoArr = explode(';', $value2['foto']);
                $value2['foto'] = $fotoArr;
                $imgTrans[] = $value2;
            }
        }

        // dd($imgTrans);
        //maps


        $queryEstate = DB::connection('mysql2')->table('estate_plot')
            ->select('*')
            ->join('estate', 'estate_plot.est', '=', 'estate.est')
            ->where('estate.est', $est)
            ->get();

        $estate_plot = array();
        $plot = '';
        $estate = '';

        foreach ($queryEstate as $key2 => $val) {

            $plot .= '[' . $val->lat . ',' .  $val->lon . '],';
            $estate = $val->nama;
        }
        $estate_plot['est'] = $estate . ' Estate';
        $estate_plot['plot'] =  rtrim($plot, ',');

        // json_encode($estate_plot);
        // $estate_plot = json_decode($estate_plot, true);
        // $json_estate_plot = json_encode($estate_plot);
        // dd($json_estate_plot);
        // dd($mutuBuah, $mutuTransport, $mutuAncak);
        return view('detailInpeksi', [
            'est' => $est,
            'afd' => $afd,
            'date' => $date,
            'ancak' => $mutuAncak,
            'buah' => $mutuBuah,
            'img' => $imgNew,
            'imgBuah' => $imgBuah,
            'estate_plot' => $estate_plot,
            'imgTrans' => $imgTrans,
            'transport' => $mutuTransport
        ]);
        // return view('detailInpeksi', ['est' => $est, 'afd' => $afd, 'date' => $date, 'reg' => $reg, 'data' => $datas, 'img' => $imgNew]);
    }

    public function dataDetail($est, $afd, $date, $reg, Request $request)
    {
        $selectedDate = new \DateTime($date);
        $selectedMonth = $selectedDate->format('m');
        $selectedYear = $selectedDate->format('Y');

        $ancakDates = DB::connection('mysql2')->table('mutu_ancak_new')
            ->select(DB::raw('DATE(mutu_ancak_new.datetime) as date'))
            ->where('mutu_ancak_new.estate', $est)
            ->where('mutu_ancak_new.afdeling', $afd)
            ->whereRaw("MONTH(mutu_ancak_new.datetime) = $selectedMonth")
            ->whereRaw("YEAR(mutu_ancak_new.datetime) = $selectedYear")
            ->distinct()
            ->orderBy('date', 'asc')
            ->get();

        $buahDates = DB::connection('mysql2')->table('mutu_buah')
            ->select(DB::raw('DATE(mutu_buah.datetime) as date'))
            ->where('mutu_buah.estate', $est)
            ->where('mutu_buah.afdeling', $afd)
            ->whereRaw("MONTH(mutu_buah.datetime) = $selectedMonth")
            ->whereRaw("YEAR(mutu_buah.datetime) = $selectedYear")
            ->distinct()
            ->orderBy('date', 'asc')
            ->get();

        $TransportDates = DB::connection('mysql2')->table('mutu_transport')
            ->select(DB::raw('DATE(mutu_transport.datetime) as date'))
            ->where('mutu_transport.estate', $est)
            ->where('mutu_transport.afdeling', $afd)
            ->whereRaw("MONTH(mutu_transport.datetime) = $selectedMonth")
            ->whereRaw("YEAR(mutu_transport.datetime) = $selectedYear")
            ->distinct()
            ->orderBy('date', 'asc')
            ->get();



        $ancakDatesArray = $ancakDates->pluck('date')->toArray();
        $buahDatesArray = $buahDates->pluck('date')->toArray();
        $TransportDatesArray = $TransportDates->pluck('date')->toArray();

        $commonDates = collect([]);
        foreach ($ancakDatesArray as $date) {
            if (in_array($date, $buahDatesArray) && in_array($date, $TransportDatesArray)) {
                $commonDates->push((object) ['date' => $date]);
            }
        }

        $arrView = array();
        $arrView['commonDates'] = $commonDates;
        $arrView['ancakDates'] = $ancakDates;
        $arrView['buahDates'] = $buahDates;
        $arrView['TransportDates'] = $TransportDates;
        $arrView['est'] =  $est;
        $arrView['afd'] =  $afd;
        $arrView['reg'] =  $reg;
        $arrView['tanggal'] =  $date;
        json_encode($arrView);
        // dd($dataTable);
        // if ($request->ajax()) {
        //     $data = mutu_ancak::select('*')
        //         ->where('estate', $request->est)
        //         ->where('afdeling', $request->afd)
        //         ->get();

        //     return Datatables::of($data)
        //         ->addIndexColumn()
        //         ->addColumn('action', function ($row) {
        //             $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">Edit</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
        //             return $actionBtn;
        //         })
        //         ->rawColumns(['action'])
        //         ->make(true);
        // }

        // return $dataTable->render('Qcinspeksi.dataDetail', $arrView);
        return view('Qcinspeksi.dataDetail', $arrView);
    }

    public function filterDataDetail(Request $request)
    {

        $dates = $request->input('Tanggal');
        $Reg = $request->input('est');
        $afd = $request->input('afd');

        // dd($dates);

        $mutuAncak = DB::connection('mysql2')->table('mutu_ancak_new')
            ->select("mutu_ancak_new.*", DB::raw('DATE_FORMAT(mutu_ancak_new.datetime, "%M") as bulan'), DB::raw('DATE_FORMAT(mutu_ancak_new.datetime, "%Y") as tahun'))
            ->where('datetime', 'like', '%' . $dates . '%')
            ->where('mutu_ancak_new.estate', $Reg)
            ->where('mutu_ancak_new.afdeling', $afd)
            ->get();
        // $mutuAncak = $mutuAncak->groupBy(['blok']);
        $mutuAncak = json_decode($mutuAncak, true);


        $mutuAncak2 = DB::connection('mysql2')->table('mutu_ancak_new')
            ->select("mutu_ancak_new.*", DB::raw('DATE_FORMAT(mutu_ancak_new.datetime, "%M") as bulan'), DB::raw('DATE_FORMAT(mutu_ancak_new.datetime, "%Y") as tahun'))
            ->where('datetime', 'like', '%' . $dates . '%')
            ->where('mutu_ancak_new.estate', $Reg)
            ->where('mutu_ancak_new.afdeling', $afd)
            ->get();
        $mutuAncak2 = json_decode($mutuAncak2, true);
        $mutuBuah = DB::connection('mysql2')->table('mutu_buah')
            ->select("mutu_buah.*", DB::raw('DATE_FORMAT(mutu_buah.datetime, "%M") as bulan'), DB::raw('DATE_FORMAT(mutu_buah.datetime, "%Y") as tahun'))
            ->where('datetime', 'like', '%' . $dates . '%')
            ->where('mutu_buah.estate', $Reg)
            ->where('mutu_buah.afdeling', $afd)
            ->get();

        $mutuBuah = json_decode($mutuBuah, true);

        $mutuTransport = DB::connection('mysql2')->table('mutu_transport')
            ->select("mutu_transport.*", DB::raw('DATE_FORMAT(mutu_transport.datetime, "%M") as bulan'), DB::raw('DATE_FORMAT(mutu_transport.datetime, "%Y") as tahun'))
            ->where('datetime', 'like', '%' . $dates . '%')
            ->where('mutu_transport.estate', $Reg)
            ->where('mutu_transport.afdeling', $afd)

            ->get();
        $mutuTransport = json_decode($mutuTransport, true);
        // dd($mutuAncak, $mutuBuah, $mutuTransport);
        // dd($mutuAncak);
        foreach ($mutuAncak as &$item) {
            if (isset($item['app_version'])) {


                $vers = $item['app_version'];
                $parts = explode(';', $vers);

                $defaultparts = '{"awal":"GO","akhir":"GO"}';
                // dd($parts);
                $version = $parts[3] ?? $defaultparts;

                if (strpos($version, 'awal')) {
                    if (strpos($version, 'awal":"GL') !== false && strpos($version, 'akhir":"GA') !== false) {
                        $item['app_version'] = 'GPS Awal Liar : GPS Akhir Akurat';
                    } else  if (strpos($version, 'awal":"GL') !== false && strpos($version, 'akhir":"GL') !== false) {
                        $item['app_version'] = 'GPS Awal Liar : GPS Akhir Liar';
                    } else  if (strpos($version, 'awal":"GA') !== false && strpos($version, 'akhir":"GL"') !== false) {
                        $item['app_version'] = 'GPS Awal Akurat : GPS Akhir Liar';
                    } else  if (strpos($version, 'awal":"GA') !== false && strpos($version, 'akhir":"GA"') !== false) {
                        $item['app_version'] = 'GPS Awal Akurat : GPS Akhir Akurat';
                    } else if (strpos($version, 'awal":"GA') !== false && strpos($version, 'akhir":"G') !== false) {
                        $item['app_version'] = 'GPS Awal Akurat : GPS Akhir Uknown';
                    } else if (strpos($version, 'awal":"GL') !== false && strpos($version, 'akhir":"G') !== false) {
                        $item['app_version'] = 'GPS Awal Akurat : GPS Akhir Uknown';
                    } else if (strpos($version, 'awal":"GO') !== false && strpos($version, 'akhir":"GO') !== false) {
                        $item['app_version'] = 'GPS Awal Uknown : GPS Akhir Uknown';
                    } else {
                        $item['app_version'] = 'GPS Uknown';
                    }

                    // $item['app_version'] = $version;
                    // dd($version);
                    // dd('awal');
                } else {
                    if (strpos($item['app_version'], ';GA') !== false) {
                        $item['app_version'] = 'GPS Akurat';
                    } elseif (strpos($item['app_version'], ';GL') !== false) {
                        $item['app_version'] = 'GPS Liar';
                    } else {
                        $item['app_version'] = 'GPS Awal Uknown : GPS Akhir Uknown';
                    }
                }
            }
        }


        // dd($mutuAncak);

        foreach ($mutuTransport as &$item) {
            // Check if "app_version" key exists in the current item
            if (isset($item['app_version'])) {
                // Check if the value contains ";GA" or ":GL"
                if (strpos($item['app_version'], ';GA') !== false) {
                    $item['app_version'] = 'GPS Akurat';
                } elseif (strpos($item['app_version'], ';GL') !== false) {
                    $item['app_version'] = 'GPS Liar';
                }
            }
        }

        foreach ($mutuBuah as &$item) {
            // Check if "app_version" key exists in the current item
            if (isset($item['app_version'])) {
                // Check if the value contains ";GA" or ":GL"
                if (strpos($item['app_version'], ';GA') !== false) {
                    $item['app_version'] = 'GPS Akurat';
                } elseif (strpos($item['app_version'], ';GL') !== false) {
                    $item['app_version'] = 'GPS Liar';
                }
            }
        }


        foreach ($mutuAncak2 as &$item) {
            // Check if "app_version" key exists in the current item
            if (isset($item['app_version'])) {
                // Check if the value contains ";GA" or ":GL"
                if (strpos($item['app_version'], ';GA') !== false) {
                    $item['app_version'] = 'GPS Akurat';
                } elseif (strpos($item['app_version'], ';GL') !== false) {
                    $item['app_version'] = 'GPS Liar';
                }
            }
        }
        // dd($mutuAncak,$mutuBuah,$mutuTransport);

        // dd($mutuAncak);

        $arrView = array();
        // dd($mutuAncak2);
        $arrView['mutuAncak'] =  $mutuAncak;
        $arrView['mutuBuah'] =  $mutuBuah;
        $arrView['AncakTest'] =  $mutuAncak2;
        $arrView['mutuTransport'] =  $mutuTransport;
        // $arrView['est'] =  $est;
        // $arrView['afd'] =  $afd;
        $arrView['tanggal'] =  $dates;
        echo json_encode($arrView);
        exit();
    }


    public function fetchEstatesByRegion(Request $request)
    {
        $reg = $request->input('region');

        // Split the string into an array of numbers
        $regArray = array_map('intval', explode(',', $reg));

        $EstMapVal = DB::connection('mysql2')->table('estate')
            ->whereNotIn('estate.est', ['CWS1', 'CWS2', 'CWS3'])
            ->where('estate.emp', '!=', 1)
            ->whereIn('wil', $regArray)->pluck('est');
        $EstMapVal = json_decode($EstMapVal, true);


        // dd($reg, $EstMapVal);
        // Return the estates as JSON data
        return response()->json([
            'estates' => $EstMapVal
        ]);
    }

    public function getWeekInpeksi(Request $request)
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





        // backend untuk halaman utama
        //mutu ancak
        $QueryMTancakWil = DB::connection('mysql2')->table('mutu_ancak_new')
            ->select("mutu_ancak_new.*", DB::raw('DATE_FORMAT(mutu_ancak_new.datetime, "%M") as bulan'), DB::raw('DATE_FORMAT(mutu_ancak_new.datetime, "%Y") as tahun'))
            ->whereBetween('mutu_ancak_new.datetime', [$startDate, $endDate])

            ->get();
        $QueryMTancakWil = $QueryMTancakWil->groupBy(['estate', 'afdeling']);
        $QueryMTancakWil = json_decode($QueryMTancakWil, true);

        foreach ($QueryMTancakWil as $estate => $afdelingArray) {
            $modifiedAfdelingArray = $afdelingArray;
            foreach ($afdelingArray as $afdeling => $data) {
                if ($estate === $afdeling) {
                    $modifiedAfdelingArray["OA"] = $data;
                    unset($modifiedAfdelingArray[$afdeling]);
                }
            }
            $QueryMTancakWil[$estate] = $modifiedAfdelingArray;
        }
        //mutu buah
        $QueryMTbuahWil = DB::connection('mysql2')->table('mutu_buah')
            ->select(
                "mutu_buah.*",
                DB::raw('DATE_FORMAT(mutu_buah.datetime, "%M") as bulan'),
                DB::raw('DATE_FORMAT(mutu_buah.datetime, "%Y") as tahun')
            )
            ->whereBetween('mutu_buah.datetime', [$startDate, $endDate])
            ->get();
        $QueryMTbuahWil = $QueryMTbuahWil->groupBy(['estate', 'afdeling']);
        $QueryMTbuahWil = json_decode($QueryMTbuahWil, true);
        foreach ($QueryMTbuahWil as $estate => $afdelingArray) {
            $modifiedAfdelingArray = $afdelingArray;
            foreach ($afdelingArray as $afdeling => $data) {
                if ($estate === $afdeling) {
                    $modifiedAfdelingArray["OA"] = $data;
                    unset($modifiedAfdelingArray[$afdeling]);
                }
            }
            $QueryMTbuahWil[$estate] = $modifiedAfdelingArray;
        }
        // dd($QueryMTbuahWil);
        //MUTU ANCAK
        $QueryTransWil = DB::connection('mysql2')->table('mutu_transport')
            ->select(
                "mutu_transport.*",
                DB::raw('DATE_FORMAT(mutu_transport.datetime, "%M") as bulan'),
                DB::raw('DATE_FORMAT(mutu_transport.datetime, "%Y") as tahun')
            )
            ->whereBetween('mutu_transport.datetime', [$startDate, $endDate])
            // ->whereYear('datetime', $year)
            ->get();
        $QueryTransWil = $QueryTransWil->groupBy(['estate', 'afdeling']);
        $QueryTransWil = json_decode($QueryTransWil, true);
        // dd($queryMTancak);
        foreach ($QueryTransWil as $estate => $afdelingArray) {
            $modifiedAfdelingArray = $afdelingArray;
            foreach ($afdelingArray as $afdeling => $data) {
                if ($estate === $afdeling) {
                    $modifiedAfdelingArray["OA"] = $data;
                    unset($modifiedAfdelingArray[$afdeling]);
                }
            }
            $QueryTransWil[$estate] = $modifiedAfdelingArray;
        }

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

        // $queryEste = DB::connection('mysql2')->table('estate')
        //     ->whereIn('wil', [1, 2, 3])->get();

        // dd($queryEste);
        $queryEste = DB::connection('mysql2')->table('estate')
            ->select('estate.*')
            ->whereNotIn('estate.est', ['Plasma1', 'Plasma2', 'Plasma3'])
            ->where('estate.emp', '!=', 1)
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->where('wil.regional', $RegData)
            ->get();
        $queryEste = json_decode($queryEste, true);
        $queryEstereg = DB::connection('mysql2')->table('estate')
            ->select('estate.*')
            // ->whereNotIn('estate.est', ['Plasma1'])
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->where('wil.regional', $RegData)
            ->get();
        $queryEstereg = json_decode($queryEstereg, true);
        $queryEstePla = DB::connection('mysql2')->table('estate')
            ->select('estate.*')
            ->whereIn('estate.est', ['Plasma1', 'Plasma2', 'Plasma3'])
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->where('wil.regional', $RegData)
            ->get();

        $queryEstePla = json_decode($queryEstePla, true);
        // dd($queryEstePla);

        $queryAsisten =  DB::connection('mysql2')->Table('asisten_qc')->get();
        // dd($QueryMTbuahWil);
        //end query
        $queryAsisten = json_decode($queryAsisten, true);
        $bulan = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        //membuat array estate -> bulan -> afdeling
        //mutu Trans mengambil nilai
        $dataMTTrans = array();
        foreach ($QueryTransWil as $key => $value) {
            foreach ($value as $key2 => $value2) {
                foreach ($value2 as $key3 => $value3) {
                    $dataMTTrans[$key][$key2][$key3] = $value3;
                }
            }
        }
        // dd($dataMTTrans);
        //membuat nilai default mutu Trans
        $defaultMtTrans = array();
        foreach ($queryEste as $est) {
            // dd($est);
            foreach ($queryAfd as $afd) {
                // dd($afd);
                if ($est['est'] == $afd['est']) {
                    $defaultMtTrans[$est['est']][$afd['nama']]['null'] = 0;
                }
            }
        }

        $defaultMtTransReg = array();
        foreach ($queryEstereg as $est) {
            // dd($est);
            foreach ($queryAfd as $afd) {
                // dd($afd);
                if ($est['est'] == $afd['est']) {
                    $defaultMtTransReg[$est['est']][$afd['nama']]['null'] = 0;
                }
            }
        }


        // dd($defPLAtrans, $defaultMtTrans);
        //mutu buah mengambil nilai
        $dataMTBuah = array();
        foreach ($QueryMTbuahWil as $key => $value) {
            foreach ($value as $key2 => $value2) {
                foreach ($value2 as $key3 => $value3) {
                    $dataMTBuah[$key][$key2][$key3] = $value3;
                }
            }
        }


        //membuat nilai default mutu buah
        $defaultMTbuah = array();
        foreach ($queryEste as $est) {
            foreach ($queryAfd as $afd) {
                if ($est['est'] == $afd['est']) {
                    $defaultMTbuah[$est['est']][$afd['nama']]['null'] = 0;
                }
            }
        }

        $defaultMTbuahReg = array();
        foreach ($queryEstereg as $est) {
            foreach ($queryAfd as $afd) {
                if ($est['est'] == $afd['est']) {
                    $defaultMTbuahReg[$est['est']][$afd['nama']]['null'] = 0;
                }
            }
        }
        // dd($defaultMTbuah, $dataMTBuah);
        // mutu ancak
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

        $defaultNew_reg = array();
        foreach ($queryEstereg as $est) {
            foreach ($queryAfd as $afd) {
                if ($est['est'] == $afd['est']) {
                    $defaultNew_reg[$est['est']][$afd['nama']]['null'] = 0;
                }
            }
        }
        // menimpa mutu trans nilai default dengan nilaiyang ada
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

        $mutuAncakMergeReg = array();
        foreach ($defaultMtTransReg as $estKey => $afdArray) {
            foreach ($afdArray as $afdKey => $afdValue) {
                if (array_key_exists($estKey, $dataMTTrans)) {
                    if (array_key_exists($afdKey, $dataMTTrans[$estKey])) {
                        if (!empty($dataMTTrans[$estKey][$afdKey])) {
                            $mutuAncakMergeReg[$estKey][$afdKey] = $dataMTTrans[$estKey][$afdKey];
                        } else {
                            $mutuAncakMergeReg[$estKey][$afdKey] = $afdValue;
                        }
                    } else {
                        $mutuAncakMergeReg[$estKey][$afdKey] = $afdValue;
                    }
                } else {
                    $mutuAncakMergeReg[$estKey][$afdKey] = $afdValue;
                }
            }
        }

        // dd($mutuAncakMerge);
        // menimpa mutu buah nilai default dengan nilaiyang ada
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

        $mutuBuahMergeReg = array();
        foreach ($defaultMTbuahReg as $estKey => $afdArray) {
            foreach ($afdArray as $afdKey => $afdValue) {
                if (array_key_exists($estKey, $dataMTBuah)) {
                    if (array_key_exists($afdKey, $dataMTBuah[$estKey])) {
                        if (!empty($dataMTBuah[$estKey][$afdKey])) {
                            $mutuBuahMergeReg[$estKey][$afdKey] = $dataMTBuah[$estKey][$afdKey];
                        } else {
                            $mutuBuahMergeReg[$estKey][$afdKey] = $afdValue;
                        }
                    } else {
                        $mutuBuahMergeReg[$estKey][$afdKey] = $afdValue;
                    }
                } else {
                    $mutuBuahMergeReg[$estKey][$afdKey] = $afdValue;
                }
            }
        }
        // dd($mutuBuahMerge);
        // menimpa mutu ancak nilai default dengan nilaiyang ada
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

        $mergedData_reg = array();
        foreach ($defaultNew_reg as $estKey => $afdArray) {
            foreach ($afdArray as $afdKey => $afdValue) {
                if (array_key_exists($estKey, $dataPerBulan)) {
                    if (array_key_exists($afdKey, $dataPerBulan[$estKey])) {
                        if (!empty($dataPerBulan[$estKey][$afdKey])) {
                            $mergedData_reg[$estKey][$afdKey] = $dataPerBulan[$estKey][$afdKey];
                        } else {
                            $mergedData_reg[$estKey][$afdKey] = $afdValue;
                        }
                    } else {
                        $mergedData_reg[$estKey][$afdKey] = $afdValue;
                    }
                } else {
                    $mergedData_reg[$estKey][$afdKey] = $afdValue;
                }
            }
        }
        // dd($mergedData);
        // //membuat data mutu ancak berdasarakan wilayah 1,2,3
        $mtancakWIltab1 = array();
        foreach ($queryEste as $key => $value) {
            foreach ($mergedData as $key2 => $value2) {
                if ($value['est'] == $key2) {
                    $mtancakWIltab1[$value['wil']][$key2] = array_merge($mtancakWIltab1[$value['wil']][$key2] ?? [], $value2);
                }
            }
        }

        // dd($mtancakWIltab1);
        $mtBuahWIltab1 = array();
        foreach ($queryEste as $key => $value) {
            foreach ($mutuBuahMerge as $key2 => $value2) {
                if ($value['est'] == $key2) {
                    $mtBuahWIltab1[$value['wil']][$key2] = array_merge($mtBuahWIltab1[$value['wil']][$key2] ?? [], $value2);
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

        ///untuk regional
        $mtTransWiltab1_reg = array();
        foreach ($queryEstereg as $key => $value) {
            foreach ($mutuAncakMergeReg as $key2 => $value2) {
                if ($value['est'] == $key2) {
                    $mtTransWiltab1_reg[$value['wil']][$key2] = array_merge($mtTransWiltab1_reg[$value['wil']][$key2] ?? [], $value2);
                }
            }
        }
        $mtBuahWIltab1_reg = array();
        foreach ($queryEstereg as $key => $value) {
            foreach ($mutuBuahMergeReg as $key2 => $value2) {
                if ($value['est'] == $key2) {
                    $mtBuahWIltab1_reg[$value['wil']][$key2] = array_merge($mtBuahWIltab1_reg[$value['wil']][$key2] ?? [], $value2);
                }
            }
        }
        $mtancakWIltab1_reg = array();
        foreach ($queryEstereg as $key => $value) {
            foreach ($mergedData_reg as $key2 => $value2) {
                if ($value['est'] == $key2) {
                    $mtancakWIltab1_reg[$value['wil']][$key2] = array_merge($mtancakWIltab1_reg[$value['wil']][$key2] ?? [], $value2);
                }
            }
        }

        $TranscakReg2 = DB::connection('mysql2')->table('mutu_transport')
            ->select(
                "mutu_transport.*",
                DB::raw('DATE_FORMAT(mutu_transport.datetime, "%Y-%m-%d") as date')
            )
            ->whereBetween('mutu_transport.datetime', [$startDate, $endDate])
            ->orderBy('datetime') // Optional: You can sort the results by datetime
            ->get();

        $AncakCakReg2 = DB::connection('mysql2')->table('mutu_ancak_new')
            ->select(
                "mutu_ancak_new.*",
                DB::raw('DATE_FORMAT(mutu_ancak_new.datetime, "%Y-%m-%d") as date')
            )
            ->whereBetween('mutu_ancak_new.datetime', [$startDate, $endDate])
            ->orderBy('datetime') // Optional: You can sort the results by datetime
            ->get();
        $DataTransGroupReg2 = [];
        foreach ($TranscakReg2 as $item) {
            $estate = $item->estate;
            $afdeling = $item->afdeling;
            $datetime = $item->datetime;
            $blok = $item->blok;
            $date = $item->date;

            if (!isset($DataTransGroupReg2[$estate])) {
                $DataTransGroupReg2[$estate] = [];
            }
            if (!isset($DataTransGroupReg2[$estate][$afdeling])) {
                $DataTransGroupReg2[$estate][$afdeling] = [];
            }
            if (!isset($DataTransGroupReg2[$estate][$afdeling][$date])) {
                $DataTransGroupReg2[$estate][$afdeling][$date] = [];
            }
            if (!isset($DataTransGroupReg2[$estate][$afdeling][$date][$blok])) {
                $DataTransGroupReg2[$estate][$afdeling][$date][$blok] = [];
            }

            $DataTransGroupReg2[$estate][$afdeling][$date][$blok][] = $item;
        }

        $DataTransGroupReg2 = json_decode(json_encode($DataTransGroupReg2), true);

        $groupedDataAcnakreg2 = [];
        foreach ($AncakCakReg2 as $item) {
            $estate = $item->estate;
            $afdeling = $item->afdeling;
            $datetime = $item->datetime;
            $blok = $item->blok;
            $date = $item->date;

            if (!isset($groupedDataAcnakreg2[$estate])) {
                $groupedDataAcnakreg2[$estate] = [];
            }
            if (!isset($groupedDataAcnakreg2[$estate][$afdeling])) {
                $groupedDataAcnakreg2[$estate][$afdeling] = [];
            }
            if (!isset($groupedDataAcnakreg2[$estate][$afdeling][$date])) {
                $groupedDataAcnakreg2[$estate][$afdeling][$date] = [];
            }
            if (!isset($groupedDataAcnakreg2[$estate][$afdeling][$date][$blok])) {
                $groupedDataAcnakreg2[$estate][$afdeling][$date][$blok] = [];
            }

            $groupedDataAcnakreg2[$estate][$afdeling][$date][$blok][] = $item;
        }

        $groupedDataAcnakreg2 = json_decode(json_encode($groupedDataAcnakreg2), true);

        $dataMTTransRegs2 = array();
        foreach ($DataTransGroupReg2 as $key => $value) {
            foreach ($queryEstereg as $est => $estval)
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
        $dataAncaksRegs2 = array();
        foreach ($groupedDataAcnakreg2 as $key => $value) {
            foreach ($queryEstereg as $est => $estval)
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
                        if ($RegData === '2') {
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
                                $transNewdata[$key][$key1][$key2][$key3]['tph_sample'] = round(floatval($LuasKey) * 1.3, 2);
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
                                $transNewdata[$key][$key1][$key2][$key3]['tph_sample'] = round(floatval($value3['luas_blok'] * 1.3), 2);
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
                $value1['total_Sample'] = $tph_sample_total;
            }
        }
        unset($value); // unset the reference
        unset($value1); // unset the reference

        $mtancaktab1Wil = array();
        foreach ($mtancakWIltab1 as $key => $value) if (!empty($value)) {
            $pokok_panenWil = 0;
            $jum_haWil = 0;
            $janjang_panenWil = 0;
            $p_panenWil = 0;
            $k_panenWil = 0;
            $brtgl_panenWil = 0;
            $bhts_panenWil = 0;
            $bhtm1_panenWil = 0;
            $bhtm2_panenWil = 0;
            $bhtm3_oanenWil = 0;
            $pelepah_swil = 0;
            $totalPKTwil = 0;
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
                $skor_bTinggalEst =  0;
                $brdPerjjgEst =  0;
                $bhtsEST = 0;
                $bhtm1EST = 0;
                $bhtm2EST = 0;
                $bhtm3EST = 0;
                $pelepah_sEST = 0;

                $skor_bhEst =  0;
                $skor_brdPerjjgEst =  0;

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
                    $skor_brdPerjjg = 0;
                    $skor_bh = 0;
                    $skor_perPl = 0;
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
                        $totalPanen +=  $value3["jjg"];
                        $totalP_panen += $value3["brtp"];
                        $totalK_panen += $value3["brtk"];
                        $totalPTgl_panen += $value3["brtgl"];

                        $totalbhts_panen += $value3["bhts"];
                        $totalbhtm1_panen += $value3["bhtm1"];
                        $totalbhtm2_panen += $value3["bhtm2"];
                        $totalbhtm3_oanen += $value3["bhtm3"];

                        $totalpelepah_s += $value3["ps"];
                    }


                    if ($totalPokok != 0) {
                        $akp = round(($totalPanen / $totalPokok) * 100, 1);
                    } else {
                        $akp = 0;
                    }


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
                        $perPl = round(($totalpelepah_s / $totalPokok) * 100, 2);
                    } else {
                        $perPl = 0;
                    }





                    $nonZeroValues = array_filter([$totalP_panen, $totalK_panen, $totalPTgl_panen, $totalbhts_panen, $totalbhtm1_panen, $totalbhtm2_panen, $totalbhtm3_oanen]);

                    if (!empty($nonZeroValues)) {
                        $mtancaktab1Wil[$key][$key1][$key2]['check_data'] = 'ada';
                        $ttlSkorMA = $skor_bh = skor_buah_Ma($sumPerBH) + $skor_brd = skor_brd_ma($brdPerjjg) + $skor_ps = skor_palepah_ma($perPl);

                        // $mtancaktab1Wil[$key][$key1][$key2]['skor_ps'] = $skor_ps = skor_palepah_ma($perPl);
                    } else {
                        $mtancaktab1Wil[$key][$key1][$key2]['check_data'] = 'kosong';
                        $ttlSkorMA = 0;

                        // $mtancaktab1Wil[$key][$key1][$key2]['skor_ps'] = $skor_ps = 0;
                    }

                    // $ttlSkorMA = $skor_bh + $skor_brd + $skor_ps;

                    $mtancaktab1Wil[$key][$key1][$key2]['pokok_sample'] = $totalPokok;
                    $mtancaktab1Wil[$key][$key1][$key2]['ha_sample'] = $jum_ha;
                    $mtancaktab1Wil[$key][$key1][$key2]['jumlah_panen'] = $totalPanen;
                    $mtancaktab1Wil[$key][$key1][$key2]['akp_rl'] = $akp;

                    $mtancaktab1Wil[$key][$key1][$key2]['p'] = $totalP_panen;
                    $mtancaktab1Wil[$key][$key1][$key2]['k'] = $totalK_panen;
                    $mtancaktab1Wil[$key][$key1][$key2]['tgl'] = $totalPTgl_panen;

                    $mtancaktab1Wil[$key][$key1][$key2]['total_brd'] = $skor_bTinggal;
                    $mtancaktab1Wil[$key][$key1][$key2]['brd/jjg'] = $brdPerjjg;

                    // data untuk buah tinggal
                    $mtancaktab1Wil[$key][$key1][$key2]['bhts_s'] = $totalbhts_panen;
                    $mtancaktab1Wil[$key][$key1][$key2]['bhtm1'] = $totalbhtm1_panen;
                    $mtancaktab1Wil[$key][$key1][$key2]['bhtm2'] = $totalbhtm2_panen;
                    $mtancaktab1Wil[$key][$key1][$key2]['bhtm3'] = $totalbhtm3_oanen;
                    $mtancaktab1Wil[$key][$key1][$key2]['buah/jjg'] = $sumPerBH;

                    $mtancaktab1Wil[$key][$key1][$key2]['jjgperBuah'] = number_format($sumPerBH, 2);
                    // data untuk pelepah sengklek

                    $mtancaktab1Wil[$key][$key1][$key2]['palepah_pokok'] = $totalpelepah_s;
                    $mtancaktab1Wil[$key][$key1][$key2]['palepah_per'] = $perPl;
                    // total skor akhir

                    $mtancaktab1Wil[$key][$key1][$key2]['skor_akhir'] = $ttlSkorMA;

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
                    $mtancaktab1Wil[$key][$key1][$key2]['pokok_sample'] = 0;
                    $mtancaktab1Wil[$key][$key1][$key2]['ha_sample'] = 0;
                    $mtancaktab1Wil[$key][$key1][$key2]['jumlah_panen'] = 0;
                    $mtancaktab1Wil[$key][$key1][$key2]['akp_rl'] =  0;

                    $mtancaktab1Wil[$key][$key1][$key2]['p'] = 0;
                    $mtancaktab1Wil[$key][$key1][$key2]['k'] = 0;
                    $mtancaktab1Wil[$key][$key1][$key2]['tgl'] = 0;

                    // $mtancaktab1Wil[$key][$key1][$key2]['total_brd'] = $skor_bTinggal;
                    $mtancaktab1Wil[$key][$key1][$key2]['brd/jjg'] = 0;

                    // data untuk buah tinggal
                    $mtancaktab1Wil[$key][$key1][$key2]['bhts_s'] = 0;
                    $mtancaktab1Wil[$key][$key1][$key2]['bhtm1'] = 0;
                    $mtancaktab1Wil[$key][$key1][$key2]['bhtm2'] = 0;
                    $mtancaktab1Wil[$key][$key1][$key2]['bhtm3'] = 0;

                    // $mtancaktab1Wil[$key][$key1][$key2]['jjgperBuah'] = number_format($sumPerBH, 2);
                    // data untuk pelepah sengklek

                    $mtancaktab1Wil[$key][$key1][$key2]['palepah_pokok'] = 0;
                    // total skor akhi0;

                    $mtancaktab1Wil[$key][$key1][$key2]['skor_bh'] = 0;
                    $mtancaktab1Wil[$key][$key1][$key2]['skor_brd'] = 0;
                    $mtancaktab1Wil[$key][$key1][$key2]['skor_ps'] = 0;
                    $mtancaktab1Wil[$key][$key1][$key2]['skor_akhir'] = 0;
                }

                $sumBHEst = $bhtsEST +  $bhtm1EST +  $bhtm2EST +  $bhtm3EST;
                $totalPKT = $p_panenEst + $k_panenEst + $brtgl_panenEst;
                // dd($sumBHEst);
                if ($pokok_panenEst != 0) {
                    $akpEst = round(($janjang_panenEst / $pokok_panenEst) * 100, 2);
                } else {
                    $akpEst = 0;
                }

                if ($janjang_panenEst != 0) {
                    $brdPerjjgEst = round($totalPKT / $janjang_panenEst, 2);
                } else {
                    $brdPerjjgEst = 0;
                }



                // dd($sumBHEst);
                if ($sumBHEst != 0) {
                    $sumPerBHEst = round($sumBHEst / ($janjang_panenEst + $sumBHEst) * 100, 2);
                } else {
                    $sumPerBHEst = 0;
                }

                if ($pokok_panenEst != 0) {
                    $perPlEst = round(($pelepah_sEST / $pokok_panenEst) * 100, 2);
                } else {
                    $perPlEst = 0;
                }


                $nonZeroValues = array_filter([$p_panenEst, $k_panenEst, $brtgl_panenEst, $bhtsEST, $bhtm1EST, $bhtm2EST, $bhtm3EST]);

                if (!empty($nonZeroValues)) {
                    $mtancaktab1Wil[$key][$key1]['check_data'] = 'ada';
                    $totalSkorEst =  skor_brd_ma($brdPerjjgEst) + skor_buah_Ma($sumPerBHEst) + skor_palepah_ma($perPlEst);
                    // $mtancaktab1Wil[$key][$key1]['skor_ps'] = $skor_ps = skor_palepah_ma($perPlEst);
                } else {
                    $mtancaktab1Wil[$key][$key1]['check_data'] = 'kosong';
                    $totalSkorEst = 0;
                    // $mtancaktab1Wil[$key][$key1]['skor_ps'] = $skor_ps = 0;
                }

                // $totalSkorEst = $skor_bh + $skor_brd + $skor_ps;


                //PENAMPILAN UNTUK PERESTATE
                $mtancaktab1Wil[$key][$key1]['skor_bh'] = $skor_bh = skor_buah_Ma($sumPerBHEst);
                $mtancaktab1Wil[$key][$key1]['skor_brd'] = $skor_brd = skor_brd_ma($brdPerjjgEst);
                $mtancaktab1Wil[$key][$key1]['skor_ps'] = $skor_ps = skor_palepah_ma($perPlEst);
                $mtancaktab1Wil[$key][$key1]['pokok_sample'] = $pokok_panenEst;
                $mtancaktab1Wil[$key][$key1]['ha_sample'] =  $jum_haEst;
                $mtancaktab1Wil[$key][$key1]['jumlah_panen'] = $janjang_panenEst;
                $mtancaktab1Wil[$key][$key1]['akp_rl'] =  $akpEst;

                $mtancaktab1Wil[$key][$key1]['p'] = $p_panenEst;
                $mtancaktab1Wil[$key][$key1]['k'] = $k_panenEst;
                $mtancaktab1Wil[$key][$key1]['tgl'] = $brtgl_panenEst;

                $mtancaktab1Wil[$key][$key1]['total_brd'] = $skor_bTinggal;
                $mtancaktab1Wil[$key][$key1]['brd/jjgest'] = $brdPerjjgEst;
                $mtancaktab1Wil[$key][$key1]['buah/jjg'] = $sumPerBHEst;

                // data untuk buah tinggal
                $mtancaktab1Wil[$key][$key1]['bhts_s'] = $bhtsEST;
                $mtancaktab1Wil[$key][$key1]['bhtm1'] = $bhtm1EST;
                $mtancaktab1Wil[$key][$key1]['bhtm2'] = $bhtm2EST;
                $mtancaktab1Wil[$key][$key1]['bhtm3'] = $bhtm3EST;
                $mtancaktab1Wil[$key][$key1]['palepah_pokok'] = $pelepah_sEST;
                $mtancaktab1Wil[$key][$key1]['palepah_per'] = $perPlEst;
                // total skor akhir

                $mtancaktab1Wil[$key][$key1]['skor_akhir'] = $totalSkorEst;

                //perhitungn untuk perwilayah

                $pokok_panenWil += $pokok_panenEst;
                $jum_haWil += $jum_haEst;
                $janjang_panenWil += $janjang_panenEst;
                $p_panenWil += $p_panenEst;
                $k_panenWil += $k_panenEst;
                $brtgl_panenWil += $brtgl_panenEst;
                // bagian buah tinggal
                $bhts_panenWil += $bhtsEST;
                $bhtm1_panenWil += $bhtm1EST;
                $bhtm2_panenWil += $bhtm2EST;
                $bhtm3_oanenWil += $bhtm3EST;
                $pelepah_swil += $pelepah_sEST;
            } else {
                $mtancaktab1Wil[$key][$key1]['pokok_sample'] = 0;
                $mtancaktab1Wil[$key][$key1]['ha_sample'] =  0;
                $mtancaktab1Wil[$key][$key1]['jumlah_panen'] = 0;
                $mtancaktab1Wil[$key][$key1]['akp_rl'] =  0;

                $mtancaktab1Wil[$key][$key1]['p'] = 0;
                $mtancaktab1Wil[$key][$key1]['k'] = 0;
                $mtancaktab1Wil[$key][$key1]['tgl'] = 0;

                // $mtancaktab1Wil[$key][$key1]['total_brd'] = $skor_bTinggal;
                $mtancaktab1Wil[$key][$key1]['brd/jjgest'] = 0;
                $mtancaktab1Wil[$key][$key1]['buah/jjg'] = 0;
                // data untuk buah tinggal
                $mtancaktab1Wil[$key][$key1]['bhts_s'] = 0;
                $mtancaktab1Wil[$key][$key1]['bhtm1'] = 0;
                $mtancaktab1Wil[$key][$key1]['bhtm2'] = 0;
                $mtancaktab1Wil[$key][$key1]['bhtm3'] = 0;
                $mtancaktab1Wil[$key][$key1]['palepah_pokok'] = 0;
                // total skor akhir
                $mtancaktab1Wil[$key][$key1]['skor_bh'] =  0;
                $mtancaktab1Wil[$key][$key1]['skor_brd'] = 0;
                $mtancaktab1Wil[$key][$key1]['skor_ps'] = 0;
                $mtancaktab1Wil[$key][$key1]['skor_akhir'] = 0;
            }
            $totalPKTwil = $p_panenWil + $k_panenWil + $brtgl_panenWil;
            $sumBHWil = $bhts_panenWil +  $bhtm1_panenWil +  $bhtm2_panenWil +  $bhtm3_oanenWil;

            if ($janjang_panenWil != 0) {
                $akpWil = round(($janjang_panenWil / $pokok_panenWil) * 100, 2);
            } else {
                $akpWil = 0;
            }

            if ($totalPKTwil != 0) {
                $brdPerwil = round($totalPKTwil / $janjang_panenWil, 2);
            } else {
                $brdPerwil = 0;
            }

            // dd($sumBHEst);
            if ($sumBHWil != 0) {
                $sumPerBHWil = round($sumBHWil / ($janjang_panenWil + $sumBHWil) * 100, 2);
            } else {
                $sumPerBHWil = 0;
            }

            if ($pokok_panenWil != 0) {
                $perPiWil = round(($pelepah_swil / $pokok_panenWil) * 100, 2);
            } else {
                $perPiWil = 0;
            }

            $nonZeroValues = array_filter([$p_panenWil, $k_panenWil, $brtgl_panenWil, $bhts_panenWil, $bhtm1_panenWil, $bhtm2_panenWil, $bhtm3_oanenWil]);

            if (!empty($nonZeroValues)) {
                $mtancaktab1Wil[$key]['check_data'] = 'ada';
                // $mtancaktab1Wil[$key]['skor_brd'] = $skor_brd = skor_brd_ma($brdPerwil);
                $totalWil = skor_brd_ma($brdPerwil) + skor_buah_Ma($sumPerBHWil) + skor_palepah_ma($perPiWil);
            } else {
                $mtancaktab1Wil[$key]['check_data'] = 'kosong';
                // $mtancaktab1Wil[$key]['skor_brd'] = $skor_brd = 0;
                $totalWil = 0;
            }

            // $totalWil = $skor_bh + $skor_brd + $skor_ps;


            $mtancaktab1Wil[$key]['pokok_sample'] = $pokok_panenWil;
            $mtancaktab1Wil[$key]['ha_sample'] =  $jum_haWil;
            $mtancaktab1Wil[$key]['jumlah_panen'] = $janjang_panenWil;
            $mtancaktab1Wil[$key]['akp_rl'] =  $akpWil;

            $mtancaktab1Wil[$key]['p'] = $p_panenWil;
            $mtancaktab1Wil[$key]['k'] = $k_panenWil;
            $mtancaktab1Wil[$key]['tgl'] = $brtgl_panenWil;
            $mtancaktab1Wil[$key]['total_brd'] = $totalPKTwil;

            $mtancaktab1Wil[$key]['total_brd'] = $skor_bTinggal;
            $mtancaktab1Wil[$key]['brd/jjgwil'] = $brdPerwil;
            $mtancaktab1Wil[$key]['buah/jjgwil'] = $sumPerBHWil;
            $mtancaktab1Wil[$key]['bhts_s'] = $bhts_panenWil;
            $mtancaktab1Wil[$key]['bhtm1'] = $bhtm1_panenWil;
            $mtancaktab1Wil[$key]['bhtm2'] = $bhtm2_panenWil;
            $mtancaktab1Wil[$key]['bhtm3'] = $bhtm3_oanenWil;
            $mtancaktab1Wil[$key]['total_buah'] = $sumBHWil;
            $mtancaktab1Wil[$key]['total_buah_per'] = $sumPerBHWil;
            $mtancaktab1Wil[$key]['jjgperBuah'] = number_format($sumPerBH, 2);
            // data untuk pelepah sengklek
            $mtancaktab1Wil[$key]['palepah_pokok'] = $pelepah_swil;

            $mtancaktab1Wil[$key]['palepah_per'] = $perPiWil;
            // total skor akhir
            $mtancaktab1Wil[$key]['skor_bh'] = skor_buah_Ma($sumPerBHWil);
            $mtancaktab1Wil[$key]['skor_brd'] = skor_brd_ma($brdPerwil);
            $mtancaktab1Wil[$key]['skor_ps'] = skor_palepah_ma($perPiWil);
            $mtancaktab1Wil[$key]['skor_akhir'] = $totalWil;
        } else {
            $mtancaktab1Wil[$key]['pokok_sample'] = 0;
            $mtancaktab1Wil[$key]['ha_sample'] =  0;
            $mtancaktab1Wil[$key]['jumlah_panen'] = 0;
            $mtancaktab1Wil[$key]['akp_rl'] =  0;

            $mtancaktab1Wil[$key]['p'] = 0;
            $mtancaktab1Wil[$key]['k'] = 0;
            $mtancaktab1Wil[$key]['tgl'] = 0;

            // $mtancaktab1Wil[$key]['total_brd'] = $skor_bTinggal;
            $mtancaktab1Wil[$key]['brd/jjgwil'] = 0;
            $mtancaktab1Wil[$key]['buah/jjgwil'] = 0;
            $mtancaktab1Wil[$key]['bhts_s'] = 0;
            $mtancaktab1Wil[$key]['bhtm1'] = 0;
            $mtancaktab1Wil[$key]['bhtm2'] = 0;
            $mtancaktab1Wil[$key]['bhtm3'] = 0;
            // $mtancaktab1Wil[$key]['jjgperBuah'] = number_format($sumPerBH, 2);
            // data untuk pelepah sengklek
            $mtancaktab1Wil[$key]['palepah_pokok'] = 0;
            // total skor akhir
            $mtancaktab1Wil[$key]['skor_bh'] = 0;
            $mtancaktab1Wil[$key]['skor_brd'] = 0;
            $mtancaktab1Wil[$key]['skor_ps'] = 0;
            $mtancaktab1Wil[$key]['skor_akhir'] = 0;
        }
        // dd($queryEstereg);
        //perhitungan untuk mutu trans perwilaya,estate dan afd
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
                                    $mtTranstab1Wil[$key][$key1][$key2]['tph_sampleNew'] = $trans2['total_Sample'];
                                    $tot_sample = $trans2['total_Sample'];
                                }
                            }
                        }
                    }

                    if ($RegData == '2' || $RegData == 2) {
                        if ($dataBLok != 0) {
                            $brdPertph = round($sum_bt / $tot_sample, 2);
                        } else {
                            $brdPertph = 0;
                        }
                    } else {
                        if ($dataBLok != 0) {
                            $brdPertph = round($sum_bt / $dataBLok, 2);
                        } else {
                            $brdPertph = 0;
                        }
                    }

                    if ($RegData == '2' || $RegData == 2) {
                        if ($dataBLok != 0) {
                            $buahPerTPH = round($sum_rst / $tot_sample, 2);
                        } else {
                            $buahPerTPH = 0;
                        }
                    } else {
                        if ($dataBLok != 0) {
                            $buahPerTPH = round($sum_rst / $dataBLok, 2);
                        } else {
                            $buahPerTPH = 0;
                        }
                    }


                    $nonZeroValues = array_filter([$sum_bt, $sum_rst]);

                    if (!empty($nonZeroValues)) {
                        $mtTranstab1Wil[$key][$key1][$key2]['check_data'] = 'ada';
                        $totalSkor =   skor_brd_tinggal($brdPertph) + skor_buah_tinggal($buahPerTPH);
                    } else {
                        $mtTranstab1Wil[$key][$key1][$key2]['check_data'] = "kosong";
                        $totalSkor = 0;
                    }
                    // dd($transNewdata);






                    $mtTranstab1Wil[$key][$key1][$key2]['tph_sample'] = $dataBLok;
                    $mtTranstab1Wil[$key][$key1][$key2]['total_brd'] = $sum_bt;
                    $mtTranstab1Wil[$key][$key1][$key2]['total_brd/TPH'] = $brdPertph;
                    $mtTranstab1Wil[$key][$key1][$key2]['total_buah'] = $sum_rst;
                    $mtTranstab1Wil[$key][$key1][$key2]['total_buahPerTPH'] = $buahPerTPH;

                    $mtTranstab1Wil[$key][$key1][$key2]['totalSkor'] = $totalSkor;

                    //PERHITUNGAN PERESTATE
                    if ($RegData == '2' || $RegData == 2) {
                        $dataBLokEst += $tot_sample;
                    } else {
                        $dataBLokEst += $dataBLok;
                    }

                    $sum_btEst += $sum_bt;
                    $sum_rstEst += $sum_rst;

                    if ($dataBLokEst != 0) {
                        $brdPertphEst = round($sum_btEst / $dataBLokEst, 2);
                    } else {
                        $brdPertphEst = 0;
                    }
                    if ($dataBLokEst != 0) {
                        $buahPerTPHEst = round($sum_rstEst / $dataBLokEst, 2);
                    } else {
                        $buahPerTPHEst = 0;
                    }
                    // dd($mtTranstab1Wil);

                } else {
                    $mtTranstab1Wil[$key][$key1][$key2]['tph_sample'] = 0;
                    $mtTranstab1Wil[$key][$key1][$key2]['total_brd'] = 0;
                    $mtTranstab1Wil[$key][$key1][$key2]['total_brd/TPH'] = 0;
                    $mtTranstab1Wil[$key][$key1][$key2]['total_buah'] = 0;
                    $mtTranstab1Wil[$key][$key1][$key2]['total_buahPerTPH'] = 0;
                    $mtTranstab1Wil[$key][$key1][$key2]['skor_brdPertph'] = 0;
                    $mtTranstab1Wil[$key][$key1][$key2]['skor_buahPerTPH'] = 0;
                    $mtTranstab1Wil[$key][$key1][$key2]['totalSkor'] = 0;
                }

                $nonZeroValues = array_filter([$sum_btEst, $sum_rstEst]);

                if (!empty($nonZeroValues)) {
                    $mtTranstab1Wil[$key][$key1]['check_data'] = 'ada';
                    $totalSkorEst = skor_brd_tinggal($brdPertphEst) + skor_buah_tinggal($buahPerTPHEst);
                } else {
                    $mtTranstab1Wil[$key][$key1]['check_data'] = 'kosong';
                    $totalSkorEst = 0;
                    // $mtTranstab1Wil[$key][$key1]['skor_buahPerTPH'] = $skor_buah = 0;
                }

                // $totalSkorEst = $skor_brd + $skor_buah ;


                $mtTranstab1Wil[$key][$key1]['tph_sample'] = $dataBLokEst;
                $mtTranstab1Wil[$key][$key1]['total_brd'] = $sum_btEst;
                $mtTranstab1Wil[$key][$key1]['total_brd/TPH'] = $brdPertphEst;
                $mtTranstab1Wil[$key][$key1]['total_buah'] = $sum_rstEst;
                $mtTranstab1Wil[$key][$key1]['total_buahPerTPH'] = $buahPerTPHEst;
                $mtTranstab1Wil[$key][$key1]['skor_brdPertph'] = skor_brd_tinggal($brdPertphEst);
                $mtTranstab1Wil[$key][$key1]['skor_buahPerTPH'] = skor_buah_tinggal($buahPerTPHEst);
                $mtTranstab1Wil[$key][$key1]['totalSkor'] = $totalSkorEst;


                //perhitungan per wil
                $dataBLokWil += $dataBLokEst;
                $sum_btWil += $sum_btEst;
                $sum_rstWil += $sum_rstEst;

                if ($dataBLokWil != 0) {
                    $brdPertphWil = round($sum_btWil / $dataBLokWil, 2);
                } else {
                    $brdPertphWil = 0;
                }
                if ($dataBLokWil != 0) {
                    $buahPerTPHWil = round($sum_rstWil / $dataBLokWil, 2);
                } else {
                    $buahPerTPHWil = 0;
                }
            } else {
                $mtTranstab1Wil[$key][$key1]['tph_sample'] = 0;
                $mtTranstab1Wil[$key][$key1]['total_brd'] = 0;
                $mtTranstab1Wil[$key][$key1]['total_brd/TPH'] = 0;
                $mtTranstab1Wil[$key][$key1]['total_buah'] = 0;
                $mtTranstab1Wil[$key][$key1]['total_buahPerTPH'] = 0;
                $mtTranstab1Wil[$key][$key1]['skor_brdPertph'] = 0;
                $mtTranstab1Wil[$key][$key1]['skor_buahPerTPH'] = 0;
                $mtTranstab1Wil[$key][$key1]['totalSkor'] = 0;
            }

            $nonZeroValues = array_filter([$sum_btWil, $sum_rstWil]);


            if (!empty($nonZeroValues)) {
                $mtTranstab1Wil[$key]['check_data'] = 'ada';
                $totalSkorWil =   skor_brd_tinggal($brdPertphWil) + skor_buah_tinggal($buahPerTPHWil);
                // $mtTranstab1Wil[$key]['skor_ps'] = $skor_ps = skor_palepah_ma($perPiWil);
            } else {
                $mtTranstab1Wil[$key]['check_data'] = 'kosong';
                // $mtTranstab1Wil[$key]['skor_brd'] = $skor_brd = 0;
                $totalSkorWil = 0;
            }
            $mtTranstab1Wil[$key]['tph_sample'] = $dataBLokWil;
            $mtTranstab1Wil[$key]['total_brd'] = $sum_btWil;
            $mtTranstab1Wil[$key]['total_brd/TPH'] = $brdPertphWil;
            $mtTranstab1Wil[$key]['total_buah'] = $sum_rstWil;
            $mtTranstab1Wil[$key]['total_buahPerTPH'] = $buahPerTPHWil;
            $mtTranstab1Wil[$key]['skor_brdPertph'] =   skor_brd_tinggal($brdPertphWil);
            $mtTranstab1Wil[$key]['skor_buahPerTPH'] = skor_buah_tinggal($buahPerTPHWil);
            $mtTranstab1Wil[$key]['totalSkor'] = $totalSkorWil;
        } else {
            $mtTranstab1Wil[$key]['tph_sample'] = 0;
            $mtTranstab1Wil[$key]['total_brd'] = 0;
            $mtTranstab1Wil[$key]['total_brd/TPH'] = 0;
            $mtTranstab1Wil[$key]['total_buah'] = 0;
            $mtTranstab1Wil[$key]['total_buahPerTPH'] = 0;
            $mtTranstab1Wil[$key]['skor_brdPertph'] = 0;
            $mtTranstab1Wil[$key]['skor_buahPerTPH'] = 0;
            $mtTranstab1Wil[$key]['totalSkor'] = 0;
        }

        // dd($mtTranstab1Wil);

        $mtTranstab1Wil_reg = array();
        foreach ($mtTransWiltab1_reg as $key => $value) if (!empty($value)) {
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
                    $tot_sample = 0;  // Define the variable outside of the foreach loop

                    foreach ($transNewdata as $keys => $trans) {
                        if ($keys == $key1) {
                            foreach ($trans as $keys2 => $trans2) {
                                if ($keys2 == $key2) {
                                    $mtTranstab1Wil_reg[$key][$key1][$key2]['tph_sampleNew'] = $trans2['total_Sample'];
                                    $tot_sample = $trans2['total_Sample'];
                                }
                            }
                        }
                    }

                    if ($RegData == '2' || $RegData == 2) {
                        if ($dataBLok != 0) {
                            $brdPertph = round($sum_bt / $tot_sample, 2);
                        } else {
                            $brdPertph = 0;
                        }
                    } else {
                        if ($dataBLok != 0) {
                            $brdPertph = round($sum_bt / $dataBLok, 2);
                        } else {
                            $brdPertph = 0;
                        }
                    }

                    if ($RegData == '2' || $RegData == 2) {
                        if ($dataBLok != 0) {
                            $buahPerTPH = round($sum_rst / $tot_sample, 2);
                        } else {
                            $buahPerTPH = 0;
                        }
                    } else {
                        if ($dataBLok != 0) {
                            $buahPerTPH = round($sum_rst / $dataBLok, 2);
                        } else {
                            $buahPerTPH = 0;
                        }
                    }


                    $nonZeroValues = array_filter([$sum_bt, $sum_rst]);

                    if (!empty($nonZeroValues)) {
                        $mtTranstab1Wil_reg[$key][$key1][$key2]['check_data'] = 'ada';
                    } else {
                        $mtTranstab1Wil_reg[$key][$key1][$key2]['check_data'] = "kosong";
                    }
                    // dd($transNewdata);




                    $totalSkor =   skor_brd_tinggal($brdPertph) + skor_buah_tinggal($buahPerTPH);

                    $mtTranstab1Wil_reg[$key][$key1][$key2]['tph_sample'] = $dataBLok;
                    $mtTranstab1Wil_reg[$key][$key1][$key2]['total_brd'] = $sum_bt;
                    $mtTranstab1Wil_reg[$key][$key1][$key2]['total_brd/TPH'] = $brdPertph;
                    $mtTranstab1Wil_reg[$key][$key1][$key2]['total_buah'] = $sum_rst;
                    $mtTranstab1Wil_reg[$key][$key1][$key2]['total_buahPerTPH'] = $buahPerTPH;

                    $mtTranstab1Wil_reg[$key][$key1][$key2]['totalSkor'] = $totalSkor;

                    //PERHITUNGAN PERESTATE
                    if ($RegData == '2' || $RegData == 2) {
                        $dataBLokEst += $tot_sample;
                    } else {
                        $dataBLokEst += $dataBLok;
                    }

                    //PERHITUNGAN PERESTATE

                    $sum_btEst += $sum_bt;
                    $sum_rstEst += $sum_rst;

                    if ($dataBLokEst != 0) {
                        $brdPertphEst = round($sum_btEst / $dataBLokEst, 2);
                    } else {
                        $brdPertphEst = 0;
                    }
                    if ($dataBLokEst != 0) {
                        $buahPerTPHEst = round($sum_rstEst / $dataBLokEst, 2);
                    } else {
                        $buahPerTPHEst = 0;
                    }

                    $totalSkorEst = skor_brd_tinggal($brdPertphEst) + skor_buah_tinggal($buahPerTPHEst);
                } else {
                    $mtTranstab1Wil_reg[$key][$key1][$key2]['tph_sample'] = 0;
                    $mtTranstab1Wil_reg[$key][$key1][$key2]['total_brd'] = 0;
                    $mtTranstab1Wil_reg[$key][$key1][$key2]['total_brd/TPH'] = 0;
                    $mtTranstab1Wil_reg[$key][$key1][$key2]['total_buah'] = 0;
                    $mtTranstab1Wil_reg[$key][$key1][$key2]['total_buahPerTPH'] = 0;
                    $mtTranstab1Wil_reg[$key][$key1][$key2]['skor_brdPertph'] = 0;
                    $mtTranstab1Wil_reg[$key][$key1][$key2]['skor_buahPerTPH'] = 0;
                    $mtTranstab1Wil_reg[$key][$key1][$key2]['totalSkor'] = 0;
                }

                $mtTranstab1Wil_reg[$key][$key1]['tph_sample'] = $dataBLokEst;
                $mtTranstab1Wil_reg[$key][$key1]['total_brd'] = $sum_btEst;
                $mtTranstab1Wil_reg[$key][$key1]['total_brd/TPH'] = $brdPertphEst;
                $mtTranstab1Wil_reg[$key][$key1]['total_buah'] = $sum_rstEst;
                $mtTranstab1Wil_reg[$key][$key1]['total_buahPerTPH'] = $buahPerTPHEst;
                $mtTranstab1Wil_reg[$key][$key1]['skor_brdPertph'] = skor_brd_tinggal($brdPertphEst);
                $mtTranstab1Wil_reg[$key][$key1]['skor_buahPerTPH'] = skor_buah_tinggal($buahPerTPHEst);
                $mtTranstab1Wil_reg[$key][$key1]['totalSkor'] = $totalSkorEst;

                //perhitungan per wil
                $dataBLokWil += $dataBLokEst;
                $sum_btWil += $sum_btEst;
                $sum_rstWil += $sum_rstEst;

                if ($dataBLokWil != 0) {
                    $brdPertphWil = round($sum_btWil / $dataBLokWil, 2);
                } else {
                    $brdPertphWil = 0;
                }
                if ($dataBLokWil != 0) {
                    $buahPerTPHWil = round($sum_rstWil / $dataBLokWil, 2);
                } else {
                    $buahPerTPHWil = 0;
                }

                $totalSkorWil =   skor_brd_tinggal($brdPertphWil) + skor_buah_tinggal($buahPerTPHWil);
            } else {
                $mtTranstab1Wil_reg[$key][$key1]['tph_sample'] = 0;
                $mtTranstab1Wil_reg[$key][$key1]['total_brd'] = 0;
                $mtTranstab1Wil_reg[$key][$key1]['total_brd/TPH'] = 0;
                $mtTranstab1Wil_reg[$key][$key1]['total_buah'] = 0;
                $mtTranstab1Wil_reg[$key][$key1]['total_buahPerTPH'] = 0;
                $mtTranstab1Wil_reg[$key][$key1]['skor_brdPertph'] = 0;
                $mtTranstab1Wil_reg[$key][$key1]['skor_buahPerTPH'] = 0;
                $mtTranstab1Wil_reg[$key][$key1]['totalSkor'] = 0;
            }
            $mtTranstab1Wil_reg[$key]['tph_sample'] = $dataBLokWil;
            $mtTranstab1Wil_reg[$key]['total_brd'] = $sum_btWil;
            $mtTranstab1Wil_reg[$key]['total_brd/TPH'] = $brdPertphWil;
            $mtTranstab1Wil_reg[$key]['total_buah'] = $sum_rstWil;
            $mtTranstab1Wil_reg[$key]['total_buahPerTPH'] = $buahPerTPHWil;
            $mtTranstab1Wil_reg[$key]['skor_brdPertph'] =   skor_brd_tinggal($brdPertphWil);
            $mtTranstab1Wil_reg[$key]['skor_buahPerTPH'] = skor_buah_tinggal($buahPerTPHWil);
            $mtTranstab1Wil_reg[$key]['totalSkor'] = $totalSkorWil;
        } else {
            $mtTranstab1Wil_reg[$key]['tph_sample'] = 0;
            $mtTranstab1Wil_reg[$key]['total_brd'] = 0;
            $mtTranstab1Wil_reg[$key]['total_brd/TPH'] = 0;
            $mtTranstab1Wil_reg[$key]['total_buah'] = 0;
            $mtTranstab1Wil_reg[$key]['total_buahPerTPH'] = 0;
            $mtTranstab1Wil_reg[$key]['skor_brdPertph'] = 0;
            $mtTranstab1Wil_reg[$key]['skor_buahPerTPH'] = 0;
            $mtTranstab1Wil_reg[$key]['totalSkor'] = 0;
        }
        // dd($mtTranstab1Wil_reg);
        //perhitungan untuk mutu buah wilayah,estate dan afd
        $mtBuahtab1Wil = array();
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
                        $total_kr = round($sum_kr / $dataBLok, 2);
                    } else {
                        $total_kr = 0;
                    }


                    $per_kr = round($total_kr * 100, 2);
                    if ($jml_mth != 0) {
                        $PerMth = round(($jml_mth / ($sum_Samplejjg - $sum_abnor)) * 100, 2);
                    } else {
                        $PerMth = 0;
                    }
                    if ($jml_mtg != 0) {
                        $PerMsk = round(($jml_mtg / ($sum_Samplejjg - $sum_abnor)) * 100, 2);
                    } else {
                        $PerMsk = 0;
                    }
                    if ($sum_over != 0) {
                        $PerOver = round(($sum_over / ($sum_Samplejjg - $sum_abnor)) * 100, 2);
                    } else {
                        $PerOver = 0;
                    }
                    if ($sum_kosongjjg != 0) {
                        $Perkosongjjg = round(($sum_kosongjjg / ($sum_Samplejjg - $sum_abnor)) * 100, 2);
                    } else {
                        $Perkosongjjg = 0;
                    }
                    if ($sum_vcut != 0) {
                        $PerVcut = round(($sum_vcut / $sum_Samplejjg) * 100, 2);
                    } else {
                        $PerVcut = 0;
                    }

                    if ($sum_abnor != 0) {
                        $PerAbr = round(($sum_abnor / $sum_Samplejjg) * 100, 2);
                    } else {
                        $PerAbr = 0;
                    }

                    $nonZeroValues = array_filter([$sum_Samplejjg, $jml_mth, $jml_mtg, $sum_over, $sum_abnor, $sum_kosongjjg, $sum_vcut, $dataBLok]);

                    if (!empty($nonZeroValues)) {
                        $mtBuahtab1Wil[$key][$key1][$key2]['check_data'] = 'ada';
                        $totalSkor =  skor_buah_mentah_mb($PerMth) + skor_buah_masak_mb($PerMsk) + skor_buah_over_mb($PerOver) + skor_vcut_mb($PerVcut) + skor_jangkos_mb($Perkosongjjg) + skor_abr_mb($per_kr);

                        // $mtBuahtab1Wil[$key][$key1][$key2]['skor_over'] = $skor_over =  skor_buah_over_mb($PerOver);
                        // $mtBuahtab1Wil[$key][$key1][$key2]['skor_jjgKosong'] = $skor_jjgKosong =  skor_jangkos_mb($Perkosongjjg);
                        // $mtBuahtab1Wil[$key][$key1][$key2]['skor_vcut'] = $skor_vcut =  skor_vcut_mb($PerVcut);
                        // $mtBuahtab1Wil[$key][$key1][$key2]['skor_kr'] = $skor_kr =  skor_abr_mb($per_kr);
                    } else {
                        $mtBuahtab1Wil[$key][$key1][$key2]['check_data'] = 'kosong';
                        $totalSkor = 0;
                        // $mtBuahtab1Wil[$key][$key1][$key2]['skor_over'] = $skor_over = 0;
                        // $mtBuahtab1Wil[$key][$key1][$key2]['skor_jjgKosong'] = $skor_jjgKosong = 0;
                        // $mtBuahtab1Wil[$key][$key1][$key2]['skor_vcut'] = $skor_vcut =  0;
                        // $mtBuahtab1Wil[$key][$key1][$key2]['skor_kr'] = $skor_kr = 0;
                    }

                    // $totalSkor = $skor_mentah + $skor_masak + $skor_over + $skor_jjgKosong + $skor_vcut + $skor_kr;



                    $mtBuahtab1Wil[$key][$key1][$key2]['tph_baris_bloks'] = $dataBLok;
                    $mtBuahtab1Wil[$key][$key1][$key2]['sampleJJG_total'] = $sum_Samplejjg;
                    $mtBuahtab1Wil[$key][$key1][$key2]['total_mentah'] = $jml_mth;
                    $mtBuahtab1Wil[$key][$key1][$key2]['total_perMentah'] = $PerMth;
                    $mtBuahtab1Wil[$key][$key1][$key2]['total_masak'] = $jml_mtg;
                    $mtBuahtab1Wil[$key][$key1][$key2]['total_perMasak'] = $PerMsk;
                    $mtBuahtab1Wil[$key][$key1][$key2]['total_over'] = $sum_over;
                    $mtBuahtab1Wil[$key][$key1][$key2]['total_perOver'] = $PerOver;
                    $mtBuahtab1Wil[$key][$key1][$key2]['total_abnormal'] = $sum_abnor;
                    $mtBuahtab1Wil[$key][$key1][$key2]['perAbnormal'] = $PerAbr;
                    $mtBuahtab1Wil[$key][$key1][$key2]['total_jjgKosong'] = $sum_kosongjjg;
                    $mtBuahtab1Wil[$key][$key1][$key2]['total_perKosongjjg'] = $Perkosongjjg;
                    $mtBuahtab1Wil[$key][$key1][$key2]['total_vcut'] = $sum_vcut;
                    $mtBuahtab1Wil[$key][$key1][$key2]['perVcut'] = $PerVcut;

                    $mtBuahtab1Wil[$key][$key1][$key2]['jum_kr'] = $sum_kr;
                    $mtBuahtab1Wil[$key][$key1][$key2]['total_kr'] = $total_kr;
                    $mtBuahtab1Wil[$key][$key1][$key2]['persen_kr'] = $per_kr;

                    // skoring
                    $mtBuahtab1Wil[$key][$key1][$key2]['skor_mentah'] = skor_buah_mentah_mb($PerMth);
                    $mtBuahtab1Wil[$key][$key1][$key2]['skor_masak'] = skor_buah_masak_mb($PerMsk);
                    $mtBuahtab1Wil[$key][$key1][$key2]['skor_over'] = skor_buah_over_mb($PerOver);
                    $mtBuahtab1Wil[$key][$key1][$key2]['skor_jjgKosong'] = skor_jangkos_mb($Perkosongjjg);
                    $mtBuahtab1Wil[$key][$key1][$key2]['skor_vcut'] = skor_vcut_mb($PerVcut);

                    $mtBuahtab1Wil[$key][$key1][$key2]['skor_kr'] = skor_abr_mb($per_kr);
                    $mtBuahtab1Wil[$key][$key1][$key2]['TOTAL_SKOR'] = $totalSkor;

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
                    $mtBuahtab1Wil[$key][$key1][$key2]['tph_baris_blok'] = 0;
                    $mtBuahtab1Wil[$key][$key1][$key2]['sampleJJG_total'] = 0;
                    $mtBuahtab1Wil[$key][$key1][$key2]['total_mentah'] = 0;
                    $mtBuahtab1Wil[$key][$key1][$key2]['total_perMentah'] = 0;
                    $mtBuahtab1Wil[$key][$key1][$key2]['total_masak'] = 0;
                    $mtBuahtab1Wil[$key][$key1][$key2]['total_perMasak'] = 0;
                    $mtBuahtab1Wil[$key][$key1][$key2]['total_over'] = 0;
                    $mtBuahtab1Wil[$key][$key1][$key2]['total_perOver'] = 0;
                    $mtBuahtab1Wil[$key][$key1][$key2]['total_abnormal'] = 0;
                    $mtBuahtab1Wil[$key][$key1][$key2]['perAbnormal'] = 0;
                    $mtBuahtab1Wil[$key][$key1][$key2]['total_jjgKosong'] = 0;
                    $mtBuahtab1Wil[$key][$key1][$key2]['total_perKosongjjg'] = 0;
                    $mtBuahtab1Wil[$key][$key1][$key2]['total_vcut'] = 0;
                    $mtBuahtab1Wil[$key][$key1][$key2]['perVcut'] = 0;

                    $mtBuahtab1Wil[$key][$key1][$key2]['jum_kr'] = 0;
                    $mtBuahtab1Wil[$key][$key1][$key2]['total_kr'] = 0;
                    $mtBuahtab1Wil[$key][$key1][$key2]['persen_kr'] = 0;

                    // skoring
                    $mtBuahtab1Wil[$key][$key1][$key2]['skor_mentah'] = 0;
                    $mtBuahtab1Wil[$key][$key1][$key2]['skor_masak'] = 0;
                    $mtBuahtab1Wil[$key][$key1][$key2]['skor_over'] = 0;
                    $mtBuahtab1Wil[$key][$key1][$key2]['skor_jjgKosong'] = 0;
                    $mtBuahtab1Wil[$key][$key1][$key2]['skor_vcut'] = 0;
                    $mtBuahtab1Wil[$key][$key1][$key2]['skor_abnormal'] = 0;;
                    $mtBuahtab1Wil[$key][$key1][$key2]['skor_kr'] = 0;
                    $mtBuahtab1Wil[$key][$key1][$key2]['TOTAL_SKOR'] = 0;
                }
                $no_VcutEst = $sum_SamplejjgEst - $sum_vcutEst;

                if ($sum_krEst != 0) {
                    $total_krEst = round($sum_krEst / $jum_haEst, 2);
                } else {
                    $total_krEst = 0;
                }
                // if ($sum_kr != 0) {
                //     $total_kr = round($sum_kr / $dataBLok, 2);
                // } else {
                //     $total_kr = 0;
                // }

                if ($sum_bmtEst != 0) {
                    $PerMthEst = round(($sum_bmtEst / ($sum_SamplejjgEst - $sum_abnorEst)) * 100, 2);
                } else {
                    $PerMthEst = 0;
                }

                if ($sum_bmkEst != 0) {
                    $PerMskEst = round(($sum_bmkEst / ($sum_SamplejjgEst - $sum_abnorEst)) * 100, 2);
                } else {
                    $PerMskEst = 0;
                }

                if ($sum_overEst != 0) {
                    $PerOverEst = round(($sum_overEst / ($sum_SamplejjgEst - $sum_abnorEst)) * 100, 2);
                } else {
                    $PerOverEst = 0;
                }
                if ($sum_kosongjjgEst != 0) {
                    $PerkosongjjgEst = round(($sum_kosongjjgEst / ($sum_SamplejjgEst - $sum_abnorEst)) * 100, 2);
                } else {
                    $PerkosongjjgEst = 0;
                }
                if ($sum_vcutEst != 0) {
                    $PerVcutest = round(($sum_vcutEst / $sum_SamplejjgEst) * 100, 2);
                } else {
                    $PerVcutest = 0;
                }
                if ($sum_abnorEst != 0) {
                    $PerAbrest = round(($sum_abnorEst / $sum_SamplejjgEst) * 100, 2);
                } else {
                    $PerAbrest = 0;
                }
                // $per_kr = round($sum_kr * 100);
                $per_krEst = round($total_krEst * 100, 2);


                $nonZeroValues = array_filter([$sum_SamplejjgEst, $sum_bmtEst, $sum_bmkEst, $sum_overEst, $sum_abnorEst, $sum_kosongjjgEst, $sum_vcutEst]);

                if (!empty($nonZeroValues)) {
                    $mtBuahtab1Wil[$key][$key1]['check_data'] = 'ada';
                    $totalSkorEst =   skor_buah_mentah_mb($PerMthEst) + skor_buah_masak_mb($PerMskEst) + skor_buah_over_mb($PerOverEst) + skor_jangkos_mb($PerkosongjjgEst) + skor_vcut_mb($PerVcutest) + skor_abr_mb($per_krEst);
                } else {
                    $mtBuahtab1Wil[$key][$key1]['check_data'] = 'kosong';
                    $totalSkorEst = 0;
                    // $mtBuahtab1Wil[$key][$key1]['skor_over'] = $skor_over = 0;
                    // $mtBuahtab1Wil[$key][$key1]['skor_jjgKosong'] = $skor_jjgKosong = 0;
                    // $mtBuahtab1Wil[$key][$key1]['skor_vcut'] = $skor_vcut =  0;
                    // $mtBuahtab1Wil[$key][$key1]['skor_kr'] = $skor_kr = 0;
                }

                // $totalSkorEst = $skor_mentah + $skor_masak + $skor_over + $skor_jjgKosong + $skor_vcut + $skor_kr;

                $mtBuahtab1Wil[$key][$key1]['tph_baris_blok'] = $jum_haEst;
                $mtBuahtab1Wil[$key][$key1]['sampleJJG_total'] = $sum_SamplejjgEst;
                $mtBuahtab1Wil[$key][$key1]['total_mentah'] = $sum_bmtEst;
                $mtBuahtab1Wil[$key][$key1]['total_perMentah'] = $PerMthEst;
                $mtBuahtab1Wil[$key][$key1]['total_masak'] = $sum_bmkEst;
                $mtBuahtab1Wil[$key][$key1]['total_perMasak'] = $PerMskEst;
                $mtBuahtab1Wil[$key][$key1]['total_over'] = $sum_overEst;
                $mtBuahtab1Wil[$key][$key1]['total_perOver'] = $PerOverEst;
                $mtBuahtab1Wil[$key][$key1]['total_abnormal'] = $sum_abnorEst;
                $mtBuahtab1Wil[$key][$key1]['total_perabnormal'] = $PerAbrest;
                $mtBuahtab1Wil[$key][$key1]['total_jjgKosong'] = $sum_kosongjjgEst;
                $mtBuahtab1Wil[$key][$key1]['total_perKosongjjg'] = $PerkosongjjgEst;
                $mtBuahtab1Wil[$key][$key1]['total_vcut'] = $sum_vcutEst;
                $mtBuahtab1Wil[$key][$key1]['perVcut'] = $PerVcutest;
                $mtBuahtab1Wil[$key][$key1]['jum_kr'] = $sum_krEst;
                $mtBuahtab1Wil[$key][$key1]['kr_blok'] = $total_krEst;

                $mtBuahtab1Wil[$key][$key1]['persen_kr'] = $per_krEst;

                // skoring
                $mtBuahtab1Wil[$key][$key1]['skor_mentah'] =  skor_buah_mentah_mb($PerMthEst);
                $mtBuahtab1Wil[$key][$key1]['skor_masak'] = skor_buah_masak_mb($PerMskEst);
                $mtBuahtab1Wil[$key][$key1]['skor_over'] = skor_buah_over_mb($PerOverEst);;
                $mtBuahtab1Wil[$key][$key1]['skor_jjgKosong'] = skor_jangkos_mb($PerkosongjjgEst);
                $mtBuahtab1Wil[$key][$key1]['skor_vcut'] = skor_vcut_mb($PerVcutest);
                $mtBuahtab1Wil[$key][$key1]['skor_kr'] = skor_abr_mb($per_krEst);
                $mtBuahtab1Wil[$key][$key1]['TOTAL_SKOR'] = $totalSkorEst;

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
                $mtBuahtab1Wil[$key][$key1]['tph_baris_blok'] = 0;
                $mtBuahtab1Wil[$key][$key1]['sampleJJG_total'] = 0;
                $mtBuahtab1Wil[$key][$key1]['total_mentah'] = 0;
                $mtBuahtab1Wil[$key][$key1]['total_perMentah'] = 0;
                $mtBuahtab1Wil[$key][$key1]['total_masak'] = 0;
                $mtBuahtab1Wil[$key][$key1]['total_perMasak'] = 0;
                $mtBuahtab1Wil[$key][$key1]['total_over'] = 0;
                $mtBuahtab1Wil[$key][$key1]['total_perOver'] = 0;
                $mtBuahtab1Wil[$key][$key1]['total_abnormal'] = 0;
                $mtBuahtab1Wil[$key][$key1]['total_perabnormal'] = 0;
                $mtBuahtab1Wil[$key][$key1]['total_jjgKosong'] = 0;
                $mtBuahtab1Wil[$key][$key1]['total_perKosongjjg'] = 0;
                $mtBuahtab1Wil[$key][$key1]['total_vcut'] = 0;
                $mtBuahtab1Wil[$key][$key1]['perVcut'] = 0;
                $mtBuahtab1Wil[$key][$key1]['jum_kr'] = 0;
                $mtBuahtab1Wil[$key][$key1]['kr_blok'] = 0;
                $mtBuahtab1Wil[$key][$key1]['persen_kr'] = 0;

                // skoring
                $mtBuahtab1Wil[$key][$key1]['skor_mentah'] = 0;
                $mtBuahtab1Wil[$key][$key1]['skor_masak'] = 0;
                $mtBuahtab1Wil[$key][$key1]['skor_over'] = 0;
                $mtBuahtab1Wil[$key][$key1]['skor_jjgKosong'] = 0;
                $mtBuahtab1Wil[$key][$key1]['skor_vcut'] = 0;
                $mtBuahtab1Wil[$key][$key1]['skor_abnormal'] = 0;;
                $mtBuahtab1Wil[$key][$key1]['skor_kr'] = 0;
                $mtBuahtab1Wil[$key][$key1]['TOTAL_SKOR'] = 0;
            }

            if ($sum_krWil != 0) {
                $total_krWil = round($sum_krWil / $jum_haWil, 2);
            } else {
                $total_krWil = 0;
            }

            if ($sum_bmtWil != 0) {
                $PerMthWil = round(($sum_bmtWil / ($sum_SamplejjgWil - $sum_abnorWil)) * 100, 2);
            } else {
                $PerMthWil = 0;
            }


            if ($sum_bmkWil != 0) {
                $PerMskWil = round(($sum_bmkWil / ($sum_SamplejjgWil - $sum_abnorWil)) * 100, 2);
            } else {
                $PerMskWil = 0;
            }
            if ($sum_overWil != 0) {
                $PerOverWil = round(($sum_overWil / ($sum_SamplejjgWil - $sum_abnorWil)) * 100, 2);
            } else {
                $PerOverWil = 0;
            }
            if ($sum_kosongjjgWil != 0) {
                $PerkosongjjgWil = round(($sum_kosongjjgWil / ($sum_SamplejjgWil - $sum_abnorWil)) * 100, 2);
            } else {
                $PerkosongjjgWil = 0;
            }
            if ($sum_vcutWil != 0) {
                $PerVcutWil = round(($sum_vcutWil / $sum_SamplejjgWil) * 100, 2);
            } else {
                $PerVcutWil = 0;
            }
            if ($sum_abnorWil != 0) {
                $PerAbrWil = round(($sum_abnorWil / $sum_SamplejjgWil) * 100, 2);
            } else {
                $PerAbrWil = 0;
            }
            $per_krWil = round($total_krWil * 100, 2);

            $nonZeroValues = array_filter([$sum_SamplejjgWil, $sum_bmtWil, $sum_bmkWil, $sum_overWil, $sum_abnorWil, $sum_kosongjjgWil, $sum_vcutWil]);

            if (!empty($nonZeroValues)) {
                $mtBuahtab1Wil[$key]['check_data'] = 'ada';
                $totalSkorWil =  skor_buah_mentah_mb($PerMthWil) + skor_buah_masak_mb($PerMskWil) + skor_buah_over_mb($PerOverWil) + skor_jangkos_mb($PerkosongjjgWil) + skor_vcut_mb($PerVcutWil) + skor_abr_mb($per_krWil);

                // $mtBuahtab1Wil[$key]['skor_over'] = $skor_over =  skor_buah_over_mb($PerOverWil);
                // $mtBuahtab1Wil[$key]['skor_jjgKosong'] = $skor_jjgKosong =  skor_jangkos_mb($PerkosongjjgWil);
                // $mtBuahtab1Wil[$key]['skor_vcut'] = $skor_vcut =  skor_vcut_mb($PerVcutWil);
                // $mtBuahtab1Wil[$key]['skor_kr'] = $skor_kr =  skor_abr_mb($per_krWil);
            } else {
                $mtBuahtab1Wil[$key]['check_data'] = 'kosong';
                $totalSkorWil = 0;
                // $mtBuahtab1Wil[$key]['skor_over'] = $skor_over = 0;
                // $mtBuahtab1Wil[$key]['skor_jjgKosong'] = $skor_jjgKosong = 0;
                // $mtBuahtab1Wil[$key]['skor_vcut'] = $skor_vcut =  0;
                // $mtBuahtab1Wil[$key]['skor_kr'] = $skor_kr = 0;
            }

            // $totalSkorWil = $skor_mentah + $skor_masak + $skor_over + $skor_jjgKosong + $skor_vcut + $skor_kr;



            $mtBuahtab1Wil[$key]['tph_baris_blok'] = $jum_haWil;
            $mtBuahtab1Wil[$key]['sampleJJG_total'] = $sum_SamplejjgWil;
            $mtBuahtab1Wil[$key]['total_mentah'] = $sum_bmtWil;
            $mtBuahtab1Wil[$key]['total_perMentah'] = $PerMthWil;
            $mtBuahtab1Wil[$key]['total_masak'] = $sum_bmkWil;
            $mtBuahtab1Wil[$key]['total_perMasak'] = $PerMskWil;
            $mtBuahtab1Wil[$key]['total_over'] = $sum_overWil;
            $mtBuahtab1Wil[$key]['total_perOver'] = $PerOverWil;
            $mtBuahtab1Wil[$key]['total_abnormal'] = $sum_abnorWil;
            $mtBuahtab1Wil[$key]['total_perabnormal'] = $PerAbrWil;
            $mtBuahtab1Wil[$key]['total_jjgKosong'] = $sum_kosongjjgWil;
            $mtBuahtab1Wil[$key]['total_perKosongjjg'] = $PerkosongjjgWil;
            $mtBuahtab1Wil[$key]['total_vcut'] = $sum_vcutWil;
            $mtBuahtab1Wil[$key]['per_vcut'] = $PerVcutWil;
            $mtBuahtab1Wil[$key]['jum_kr'] = $sum_krWil;
            $mtBuahtab1Wil[$key]['kr_blok'] = $total_krWil;

            $mtBuahtab1Wil[$key]['persen_kr'] = $per_krWil;

            // skoring
            $mtBuahtab1Wil[$key]['skor_mentah'] = skor_buah_mentah_mb($PerMthWil);
            $mtBuahtab1Wil[$key]['skor_masak'] = skor_buah_masak_mb($PerMskWil);
            $mtBuahtab1Wil[$key]['skor_over'] = skor_buah_over_mb($PerOverWil);;
            $mtBuahtab1Wil[$key]['skor_jjgKosong'] = skor_jangkos_mb($PerkosongjjgWil);
            $mtBuahtab1Wil[$key]['skor_vcut'] = skor_vcut_mb($PerVcutWil);
            $mtBuahtab1Wil[$key]['skor_kr'] = skor_abr_mb($per_krWil);
            $mtBuahtab1Wil[$key]['TOTAL_SKOR'] = $totalSkorWil;
        } else {
            $mtBuahtab1Wil[$key]['tph_baris_blok'] = 0;
            $mtBuahtab1Wil[$key]['sampleJJG_total'] = 0;
            $mtBuahtab1Wil[$key]['total_mentah'] = 0;
            $mtBuahtab1Wil[$key]['total_perMentah'] = 0;
            $mtBuahtab1Wil[$key]['total_masak'] = 0;
            $mtBuahtab1Wil[$key]['total_perMasak'] = 0;
            $mtBuahtab1Wil[$key]['total_over'] = 0;
            $mtBuahtab1Wil[$key]['total_perOver'] = 0;
            $mtBuahtab1Wil[$key]['total_abnormal'] = 0;
            $mtBuahtab1Wil[$key]['total_perabnormal'] = 0;
            $mtBuahtab1Wil[$key]['total_jjgKosong'] = 0;
            $mtBuahtab1Wil[$key]['total_perKosongjjg'] = 0;
            $mtBuahtab1Wil[$key]['total_vcut'] = 0;
            $mtBuahtab1Wil[$key]['per_vcut'] = 0;
            $mtBuahtab1Wil[$key]['jum_kr'] = 0;
            $mtBuahtab1Wil[$key]['kr_blok'] = 0;

            $mtBuahtab1Wil[$key]['persen_kr'] = 0;

            // skoring
            $mtBuahtab1Wil[$key]['skor_mentah'] = 0;
            $mtBuahtab1Wil[$key]['skor_masak'] = 0;
            $mtBuahtab1Wil[$key]['skor_over'] = 0;
            $mtBuahtab1Wil[$key]['skor_jjgKosong'] = 0;
            $mtBuahtab1Wil[$key]['skor_vcut'] = 0;

            $mtBuahtab1Wil[$key]['skor_kr'] = 0;
            $mtBuahtab1Wil[$key]['TOTAL_SKOR'] = 0;
        }
        // dd($mtBuahtab1Wil['1']);
        $mtBuahtab1Wil_reg = array();
        foreach ($mtBuahWIltab1_reg as $key => $value) if (is_array($value)) {
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
                    $combination_counts = array();
                    foreach ($value2 as $key3 => $value3) if (is_array($value3)) {
                        $combination = $value3['blok'] . ' ' . $value3['estate'] . ' ' . $value3['afdeling'] . ' ' . $value3['tph_baris'];
                        if (!isset($combination_counts[$combination])) {
                            $combination_counts[$combination] = 0;
                        }
                        $jum_ha = count($listBlokPerAfd);
                        $sum_bmt += $value3['bmt'];
                        $sum_bmk += $value3['bmk'];
                        $sum_over += $value3['overripe'];
                        $sum_kosongjjg += $value3['empty_bunch'];
                        $sum_vcut += $value3['vcut'];
                        $sum_kr += $value3['alas_br'];


                        $sum_Samplejjg += $value3['jumlah_jjg'];
                        $sum_abnor += $value3['abnormal'];
                    }

                    $dataBLok = count($combination_counts);
                    $jml_mth = ($sum_bmt + $sum_bmk);
                    $jml_mtg = $sum_Samplejjg - ($jml_mth + $sum_over + $sum_kosongjjg + $sum_abnor);

                    if ($sum_kr != 0) {
                        $total_kr = round($sum_kr / $dataBLok, 2);
                    } else {
                        $total_kr = 0;
                    }


                    $per_kr = round($total_kr * 100, 2);
                    if ($jml_mth != 0) {
                        $PerMth = round(($jml_mth / ($sum_Samplejjg - $sum_abnor)) * 100, 2);
                    } else {
                        $PerMth = 0;
                    }
                    if ($jml_mtg != 0) {
                        $PerMsk = round(($jml_mtg / ($sum_Samplejjg - $sum_abnor)) * 100, 2);
                    } else {
                        $PerMsk = 0;
                    }
                    if ($sum_over != 0) {
                        $PerOver = round(($sum_over / ($sum_Samplejjg - $sum_abnor)) * 100, 2);
                    } else {
                        $PerOver = 0;
                    }
                    if ($sum_kosongjjg != 0) {
                        $Perkosongjjg = round(($sum_kosongjjg / ($sum_Samplejjg - $sum_abnor)) * 100, 2);
                    } else {
                        $Perkosongjjg = 0;
                    }
                    if ($sum_vcut != 0) {
                        $PerVcut = round(($sum_vcut / $sum_Samplejjg) * 100, 2);
                    } else {
                        $PerVcut = 0;
                    }

                    if ($sum_abnor != 0) {
                        $PerAbr = round(($sum_abnor / $sum_Samplejjg) * 100, 2);
                    } else {
                        $PerAbr = 0;
                    }


                    $totalSkor =  skor_buah_mentah_mb($PerMth) + skor_buah_masak_mb($PerMsk) + skor_buah_over_mb($PerOver) + skor_vcut_mb($PerVcut) + skor_jangkos_mb($Perkosongjjg) + skor_abr_mb($per_kr);
                    $mtBuahtab1Wil_reg[$key][$key1][$key2]['tph_baris_bloks'] = $dataBLok;
                    $mtBuahtab1Wil_reg[$key][$key1][$key2]['sampleJJG_total'] = $sum_Samplejjg;
                    $mtBuahtab1Wil_reg[$key][$key1][$key2]['total_mentah'] = $jml_mth;
                    $mtBuahtab1Wil_reg[$key][$key1][$key2]['total_perMentah'] = $PerMth;
                    $mtBuahtab1Wil_reg[$key][$key1][$key2]['total_masak'] = $jml_mtg;
                    $mtBuahtab1Wil_reg[$key][$key1][$key2]['total_perMasak'] = $PerMsk;
                    $mtBuahtab1Wil_reg[$key][$key1][$key2]['total_over'] = $sum_over;
                    $mtBuahtab1Wil_reg[$key][$key1][$key2]['total_perOver'] = $PerOver;
                    $mtBuahtab1Wil_reg[$key][$key1][$key2]['total_abnormal'] = $sum_abnor;
                    $mtBuahtab1Wil_reg[$key][$key1][$key2]['perAbnormal'] = $PerAbr;
                    $mtBuahtab1Wil_reg[$key][$key1][$key2]['total_jjgKosong'] = $sum_kosongjjg;
                    $mtBuahtab1Wil_reg[$key][$key1][$key2]['total_perKosongjjg'] = $Perkosongjjg;
                    $mtBuahtab1Wil_reg[$key][$key1][$key2]['total_vcut'] = $sum_vcut;
                    $mtBuahtab1Wil_reg[$key][$key1][$key2]['perVcut'] = $PerVcut;

                    $mtBuahtab1Wil_reg[$key][$key1][$key2]['jum_kr'] = $sum_kr;
                    $mtBuahtab1Wil_reg[$key][$key1][$key2]['total_kr'] = $total_kr;
                    $mtBuahtab1Wil_reg[$key][$key1][$key2]['persen_kr'] = $per_kr;

                    // skoring
                    $mtBuahtab1Wil_reg[$key][$key1][$key2]['skor_mentah'] = skor_buah_mentah_mb($PerMth);
                    $mtBuahtab1Wil_reg[$key][$key1][$key2]['skor_masak'] = skor_buah_masak_mb($PerMsk);
                    $mtBuahtab1Wil_reg[$key][$key1][$key2]['skor_over'] = skor_buah_over_mb($PerOver);
                    $mtBuahtab1Wil_reg[$key][$key1][$key2]['skor_jjgKosong'] = skor_jangkos_mb($Perkosongjjg);
                    $mtBuahtab1Wil_reg[$key][$key1][$key2]['skor_vcut'] = skor_vcut_mb($PerVcut);

                    $mtBuahtab1Wil_reg[$key][$key1][$key2]['skor_kr'] = skor_abr_mb($per_kr);
                    $mtBuahtab1Wil_reg[$key][$key1][$key2]['TOTAL_SKOR'] = $totalSkor;

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
                    $mtBuahtab1Wil_reg[$key][$key1][$key2]['tph_baris_blok'] = 0;
                    $mtBuahtab1Wil_reg[$key][$key1][$key2]['sampleJJG_total'] = 0;
                    $mtBuahtab1Wil_reg[$key][$key1][$key2]['total_mentah'] = 0;
                    $mtBuahtab1Wil_reg[$key][$key1][$key2]['total_perMentah'] = 0;
                    $mtBuahtab1Wil_reg[$key][$key1][$key2]['total_masak'] = 0;
                    $mtBuahtab1Wil_reg[$key][$key1][$key2]['total_perMasak'] = 0;
                    $mtBuahtab1Wil_reg[$key][$key1][$key2]['total_over'] = 0;
                    $mtBuahtab1Wil_reg[$key][$key1][$key2]['total_perOver'] = 0;
                    $mtBuahtab1Wil_reg[$key][$key1][$key2]['total_abnormal'] = 0;
                    $mtBuahtab1Wil_reg[$key][$key1][$key2]['perAbnormal'] = 0;
                    $mtBuahtab1Wil_reg[$key][$key1][$key2]['total_jjgKosong'] = 0;
                    $mtBuahtab1Wil_reg[$key][$key1][$key2]['total_perKosongjjg'] = 0;
                    $mtBuahtab1Wil_reg[$key][$key1][$key2]['total_vcut'] = 0;
                    $mtBuahtab1Wil_reg[$key][$key1][$key2]['perVcut'] = 0;

                    $mtBuahtab1Wil_reg[$key][$key1][$key2]['jum_kr'] = 0;
                    $mtBuahtab1Wil_reg[$key][$key1][$key2]['total_kr'] = 0;
                    $mtBuahtab1Wil_reg[$key][$key1][$key2]['persen_kr'] = 0;

                    // skoring
                    $mtBuahtab1Wil_reg[$key][$key1][$key2]['skor_mentah'] = 0;
                    $mtBuahtab1Wil_reg[$key][$key1][$key2]['skor_masak'] = 0;
                    $mtBuahtab1Wil_reg[$key][$key1][$key2]['skor_over'] = 0;
                    $mtBuahtab1Wil_reg[$key][$key1][$key2]['skor_jjgKosong'] = 0;
                    $mtBuahtab1Wil_reg[$key][$key1][$key2]['skor_vcut'] = 0;
                    $mtBuahtab1Wil_reg[$key][$key1][$key2]['skor_abnormal'] = 0;;
                    $mtBuahtab1Wil_reg[$key][$key1][$key2]['skor_kr'] = 0;
                    $mtBuahtab1Wil_reg[$key][$key1][$key2]['TOTAL_SKOR'] = 0;
                }
                $no_VcutEst = $sum_SamplejjgEst - $sum_vcutEst;

                if ($sum_krEst != 0) {
                    $total_krEst = round($sum_krEst / $jum_haEst, 2);
                } else {
                    $total_krEst = 0;
                }
                // if ($sum_kr != 0) {
                //     $total_kr = round($sum_kr / $dataBLok, 2);
                // } else {
                //     $total_kr = 0;
                // }

                if ($sum_bmtEst != 0) {
                    $PerMthEst = round(($sum_bmtEst / ($sum_SamplejjgEst - $sum_abnorEst)) * 100, 2);
                } else {
                    $PerMthEst = 0;
                }

                if ($sum_bmkEst != 0) {
                    $PerMskEst = round(($sum_bmkEst / ($sum_SamplejjgEst - $sum_abnorEst)) * 100, 2);
                } else {
                    $PerMskEst = 0;
                }

                if ($sum_overEst != 0) {
                    $PerOverEst = round(($sum_overEst / ($sum_SamplejjgEst - $sum_abnorEst)) * 100, 2);
                } else {
                    $PerOverEst = 0;
                }
                if ($sum_kosongjjgEst != 0) {
                    $PerkosongjjgEst = round(($sum_kosongjjgEst / ($sum_SamplejjgEst - $sum_abnorEst)) * 100, 2);
                } else {
                    $PerkosongjjgEst = 0;
                }
                if ($sum_vcutEst != 0) {
                    $PerVcutest = round(($sum_vcutEst / $sum_SamplejjgEst) * 100, 2);
                } else {
                    $PerVcutest = 0;
                }
                if ($sum_abnorEst != 0) {
                    $PerAbrest = round(($sum_abnorEst / $sum_SamplejjgEst) * 100, 2);
                } else {
                    $PerAbrest = 0;
                }
                // $per_kr = round($sum_kr * 100);
                $per_krEst = round($total_krEst * 100, 2);

                $totalSkorEst =   skor_buah_mentah_mb($PerMthEst) + skor_buah_masak_mb($PerMskEst) + skor_buah_over_mb($PerOverEst) + skor_jangkos_mb($PerkosongjjgEst) + skor_vcut_mb($PerVcutest) + skor_abr_mb($per_krEst);
                $mtBuahtab1Wil_reg[$key][$key1]['tph_baris_blok'] = $jum_haEst;
                $mtBuahtab1Wil_reg[$key][$key1]['sampleJJG_total'] = $sum_SamplejjgEst;
                $mtBuahtab1Wil_reg[$key][$key1]['total_mentah'] = $sum_bmtEst;
                $mtBuahtab1Wil_reg[$key][$key1]['total_perMentah'] = $PerMthEst;
                $mtBuahtab1Wil_reg[$key][$key1]['total_masak'] = $sum_bmkEst;
                $mtBuahtab1Wil_reg[$key][$key1]['total_perMasak'] = $PerMskEst;
                $mtBuahtab1Wil_reg[$key][$key1]['total_over'] = $sum_overEst;
                $mtBuahtab1Wil_reg[$key][$key1]['total_perOver'] = $PerOverEst;
                $mtBuahtab1Wil_reg[$key][$key1]['total_abnormal'] = $sum_abnorEst;
                $mtBuahtab1Wil_reg[$key][$key1]['total_perabnormal'] = $PerAbrest;
                $mtBuahtab1Wil_reg[$key][$key1]['total_jjgKosong'] = $sum_kosongjjgEst;
                $mtBuahtab1Wil_reg[$key][$key1]['total_perKosongjjg'] = $PerkosongjjgEst;
                $mtBuahtab1Wil_reg[$key][$key1]['total_vcut'] = $sum_vcutEst;
                $mtBuahtab1Wil_reg[$key][$key1]['perVcut'] = $PerVcutest;
                $mtBuahtab1Wil_reg[$key][$key1]['jum_kr'] = $sum_krEst;
                $mtBuahtab1Wil_reg[$key][$key1]['kr_blok'] = $total_krEst;

                $mtBuahtab1Wil_reg[$key][$key1]['persen_kr'] = $per_krEst;

                // skoring
                $mtBuahtab1Wil_reg[$key][$key1]['skor_mentah'] =  skor_buah_mentah_mb($PerMthEst);
                $mtBuahtab1Wil_reg[$key][$key1]['skor_masak'] = skor_buah_masak_mb($PerMskEst);
                $mtBuahtab1Wil_reg[$key][$key1]['skor_over'] = skor_buah_over_mb($PerOverEst);;
                $mtBuahtab1Wil_reg[$key][$key1]['skor_jjgKosong'] = skor_jangkos_mb($PerkosongjjgEst);
                $mtBuahtab1Wil_reg[$key][$key1]['skor_vcut'] = skor_vcut_mb($PerVcutest);
                $mtBuahtab1Wil_reg[$key][$key1]['skor_kr'] = skor_abr_mb($per_krEst);
                $mtBuahtab1Wil_reg[$key][$key1]['TOTAL_SKOR'] = $totalSkorEst;

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
                $mtBuahtab1Wil_reg[$key][$key1]['tph_baris_blok'] = 0;
                $mtBuahtab1Wil_reg[$key][$key1]['sampleJJG_total'] = 0;
                $mtBuahtab1Wil_reg[$key][$key1]['total_mentah'] = 0;
                $mtBuahtab1Wil_reg[$key][$key1]['total_perMentah'] = 0;
                $mtBuahtab1Wil_reg[$key][$key1]['total_masak'] = 0;
                $mtBuahtab1Wil_reg[$key][$key1]['total_perMasak'] = 0;
                $mtBuahtab1Wil_reg[$key][$key1]['total_over'] = 0;
                $mtBuahtab1Wil_reg[$key][$key1]['total_perOver'] = 0;
                $mtBuahtab1Wil_reg[$key][$key1]['total_abnormal'] = 0;
                $mtBuahtab1Wil_reg[$key][$key1]['total_perabnormal'] = 0;
                $mtBuahtab1Wil_reg[$key][$key1]['total_jjgKosong'] = 0;
                $mtBuahtab1Wil_reg[$key][$key1]['total_perKosongjjg'] = 0;
                $mtBuahtab1Wil_reg[$key][$key1]['total_vcut'] = 0;
                $mtBuahtab1Wil_reg[$key][$key1]['perVcut'] = 0;
                $mtBuahtab1Wil_reg[$key][$key1]['jum_kr'] = 0;
                $mtBuahtab1Wil_reg[$key][$key1]['kr_blok'] = 0;
                $mtBuahtab1Wil_reg[$key][$key1]['persen_kr'] = 0;

                // skoring
                $mtBuahtab1Wil_reg[$key][$key1]['skor_mentah'] = 0;
                $mtBuahtab1Wil_reg[$key][$key1]['skor_masak'] = 0;
                $mtBuahtab1Wil_reg[$key][$key1]['skor_over'] = 0;
                $mtBuahtab1Wil_reg[$key][$key1]['skor_jjgKosong'] = 0;
                $mtBuahtab1Wil_reg[$key][$key1]['skor_vcut'] = 0;
                $mtBuahtab1Wil_reg[$key][$key1]['skor_abnormal'] = 0;;
                $mtBuahtab1Wil_reg[$key][$key1]['skor_kr'] = 0;
                $mtBuahtab1Wil_reg[$key][$key1]['TOTAL_SKOR'] = 0;
            }

            if ($sum_krWil != 0) {
                $total_krWil = round($sum_krWil / $jum_haWil, 2);
            } else {
                $total_krWil = 0;
            }

            if ($sum_bmtWil != 0) {
                $PerMthWil = round(($sum_bmtWil / ($sum_SamplejjgWil - $sum_abnorWil)) * 100, 2);
            } else {
                $PerMthWil = 0;
            }


            if ($sum_bmkWil != 0) {
                $PerMskWil = round(($sum_bmkWil / ($sum_SamplejjgWil - $sum_abnorWil)) * 100, 2);
            } else {
                $PerMskWil = 0;
            }
            if ($sum_overWil != 0) {
                $PerOverWil = round(($sum_overWil / ($sum_SamplejjgWil - $sum_abnorWil)) * 100, 2);
            } else {
                $PerOverWil = 0;
            }
            if ($sum_kosongjjgWil != 0) {
                $PerkosongjjgWil = round(($sum_kosongjjgWil / ($sum_SamplejjgWil - $sum_abnorWil)) * 100, 2);
            } else {
                $PerkosongjjgWil = 0;
            }
            if ($sum_vcutWil != 0) {
                $PerVcutWil = round(($sum_vcutWil / $sum_SamplejjgWil) * 100, 2);
            } else {
                $PerVcutWil = 0;
            }
            if ($sum_abnorWil != 0) {
                $PerAbrWil = round(($sum_abnorWil / $sum_SamplejjgWil) * 100, 2);
            } else {
                $PerAbrWil = 0;
            }
            $per_krWil = round($total_krWil * 100, 2);
            $totalSkorWil =  skor_buah_mentah_mb($PerMthWil) + skor_buah_masak_mb($PerMskWil) + skor_buah_over_mb($PerOverWil) + skor_jangkos_mb($PerkosongjjgWil) + skor_vcut_mb($PerVcutWil) + skor_abr_mb($per_krWil);
            $mtBuahtab1Wil_reg[$key]['tph_baris_blok'] = $jum_haWil;
            $mtBuahtab1Wil_reg[$key]['sampleJJG_total'] = $sum_SamplejjgWil;
            $mtBuahtab1Wil_reg[$key]['total_mentah'] = $sum_bmtWil;
            $mtBuahtab1Wil_reg[$key]['total_perMentah'] = $PerMthWil;
            $mtBuahtab1Wil_reg[$key]['total_masak'] = $sum_bmkWil;
            $mtBuahtab1Wil_reg[$key]['total_perMasak'] = $PerMskWil;
            $mtBuahtab1Wil_reg[$key]['total_over'] = $sum_overWil;
            $mtBuahtab1Wil_reg[$key]['total_perOver'] = $PerOverWil;
            $mtBuahtab1Wil_reg[$key]['total_abnormal'] = $sum_abnorWil;
            $mtBuahtab1Wil_reg[$key]['total_perabnormal'] = $PerAbrWil;
            $mtBuahtab1Wil_reg[$key]['total_jjgKosong'] = $sum_kosongjjgWil;
            $mtBuahtab1Wil_reg[$key]['total_perKosongjjg'] = $PerkosongjjgWil;
            $mtBuahtab1Wil_reg[$key]['total_vcut'] = $sum_vcutWil;
            $mtBuahtab1Wil_reg[$key]['per_vcut'] = $PerVcutWil;
            $mtBuahtab1Wil_reg[$key]['jum_kr'] = $sum_krWil;
            $mtBuahtab1Wil_reg[$key]['kr_blok'] = $total_krWil;

            $mtBuahtab1Wil_reg[$key]['persen_kr'] = $per_krWil;

            // skoring
            $mtBuahtab1Wil_reg[$key]['skor_mentah'] = skor_buah_mentah_mb($PerMthWil);
            $mtBuahtab1Wil_reg[$key]['skor_masak'] = skor_buah_masak_mb($PerMskWil);
            $mtBuahtab1Wil_reg[$key]['skor_over'] = skor_buah_over_mb($PerOverWil);;
            $mtBuahtab1Wil_reg[$key]['skor_jjgKosong'] = skor_jangkos_mb($PerkosongjjgWil);
            $mtBuahtab1Wil_reg[$key]['skor_vcut'] = skor_vcut_mb($PerVcutWil);
            $mtBuahtab1Wil_reg[$key]['skor_kr'] = skor_abr_mb($per_krWil);
            $mtBuahtab1Wil_reg[$key]['TOTAL_SKOR'] = $totalSkorWil;
        } else {
            $mtBuahtab1Wil_reg[$key]['tph_baris_blok'] = 0;
            $mtBuahtab1Wil_reg[$key]['sampleJJG_total'] = 0;
            $mtBuahtab1Wil_reg[$key]['total_mentah'] = 0;
            $mtBuahtab1Wil_reg[$key]['total_perMentah'] = 0;
            $mtBuahtab1Wil_reg[$key]['total_masak'] = 0;
            $mtBuahtab1Wil_reg[$key]['total_perMasak'] = 0;
            $mtBuahtab1Wil_reg[$key]['total_over'] = 0;
            $mtBuahtab1Wil_reg[$key]['total_perOver'] = 0;
            $mtBuahtab1Wil_reg[$key]['total_abnormal'] = 0;
            $mtBuahtab1Wil_reg[$key]['total_perabnormal'] = 0;
            $mtBuahtab1Wil_reg[$key]['total_jjgKosong'] = 0;
            $mtBuahtab1Wil_reg[$key]['total_perKosongjjg'] = 0;
            $mtBuahtab1Wil_reg[$key]['total_vcut'] = 0;
            $mtBuahtab1Wil_reg[$key]['per_vcut'] = 0;
            $mtBuahtab1Wil_reg[$key]['jum_kr'] = 0;
            $mtBuahtab1Wil_reg[$key]['kr_blok'] = 0;

            $mtBuahtab1Wil_reg[$key]['persen_kr'] = 0;

            // skoring
            $mtBuahtab1Wil_reg[$key]['skor_mentah'] = 0;
            $mtBuahtab1Wil_reg[$key]['skor_masak'] = 0;
            $mtBuahtab1Wil_reg[$key]['skor_over'] = 0;
            $mtBuahtab1Wil_reg[$key]['skor_jjgKosong'] = 0;
            $mtBuahtab1Wil_reg[$key]['skor_vcut'] = 0;

            $mtBuahtab1Wil_reg[$key]['skor_kr'] = 0;
            $mtBuahtab1Wil_reg[$key]['TOTAL_SKOR'] = 0;
        }
        // dd($mtBuahtab1Wil[1]['KNE']['OD']);

        // dd($mtancaktab1Wil);
        $mtancaktab1Wil_reg = array();
        foreach ($mtancakWIltab1_reg as $key => $value) if (!empty($value)) {
            $pokok_panenWil = 0;
            $jum_haWil = 0;
            $janjang_panenWil = 0;
            $p_panenWil = 0;
            $k_panenWil = 0;
            $brtgl_panenWil = 0;
            $bhts_panenWil = 0;
            $bhtm1_panenWil = 0;
            $bhtm2_panenWil = 0;
            $bhtm3_oanenWil = 0;
            $pelepah_swil = 0;
            $totalPKTwil = 0;
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
                $skor_bTinggalEst =  0;
                $brdPerjjgEst =  0;
                $bhtsEST = 0;
                $bhtm1EST = 0;
                $bhtm2EST = 0;
                $bhtm3EST = 0;
                $pelepah_sEST = 0;

                $skor_bhEst =  0;
                $skor_brdPerjjgEst =  0;

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
                    $skor_brdPerjjg = 0;
                    $skor_bh = 0;
                    $skor_perPl = 0;
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
                        $totalPanen +=  $value3["jjg"];
                        $totalP_panen += $value3["brtp"];
                        $totalK_panen += $value3["brtk"];
                        $totalPTgl_panen += $value3["brtgl"];

                        $totalbhts_panen += $value3["bhts"];
                        $totalbhtm1_panen += $value3["bhtm1"];
                        $totalbhtm2_panen += $value3["bhtm2"];
                        $totalbhtm3_oanen += $value3["bhtm3"];

                        $totalpelepah_s += $value3["ps"];
                    }


                    if ($totalPokok != 0) {
                        $akp = round(($totalPanen / $totalPokok) * 100, 1);
                    } else {
                        $akp = 0;
                    }


                    $skor_bTinggal = $totalP_panen + $totalK_panen + $totalPTgl_panen;

                    if ($totalPanen != 0) {
                        $brdPerjjg = round($skor_bTinggal / $totalPanen, 1);
                    } else {
                        $brdPerjjg = 0;
                    }

                    $sumBH = $totalbhts_panen +  $totalbhtm1_panen +  $totalbhtm2_panen +  $totalbhtm3_oanen;
                    if ($sumBH != 0) {
                        $sumPerBH = round($sumBH / ($totalPanen + $sumBH) * 100, 1);
                    } else {
                        $sumPerBH = 0;
                    }

                    if ($totalpelepah_s != 0) {
                        $perPl = round(($totalpelepah_s / $totalPokok) * 100, 2);
                    } else {
                        $perPl = 0;
                    }



                    $ttlSkorMA = skor_brd_ma($brdPerjjg) + skor_buah_Ma($sumPerBH) + skor_palepah_ma($perPl);

                    $mtancaktab1Wil_reg[$key][$key1][$key2]['pokok_sample'] = $totalPokok;
                    $mtancaktab1Wil_reg[$key][$key1][$key2]['ha_sample'] = $jum_ha;
                    $mtancaktab1Wil_reg[$key][$key1][$key2]['jumlah_panen'] = $totalPanen;
                    $mtancaktab1Wil_reg[$key][$key1][$key2]['akp_rl'] = $akp;

                    $mtancaktab1Wil_reg[$key][$key1][$key2]['p'] = $totalP_panen;
                    $mtancaktab1Wil_reg[$key][$key1][$key2]['k'] = $totalK_panen;
                    $mtancaktab1Wil_reg[$key][$key1][$key2]['tgl'] = $totalPTgl_panen;

                    // $mtancaktab1Wil_reg[$key][$key1][$key2]['total_brd'] = $skor_bTinggal;
                    $mtancaktab1Wil_reg[$key][$key1][$key2]['brd/jjg'] = $brdPerjjg;

                    // data untuk buah tinggal
                    $mtancaktab1Wil_reg[$key][$key1][$key2]['bhts_s'] = $totalbhts_panen;
                    $mtancaktab1Wil_reg[$key][$key1][$key2]['bhtm1'] = $totalbhtm1_panen;
                    $mtancaktab1Wil_reg[$key][$key1][$key2]['bhtm2'] = $totalbhtm2_panen;
                    $mtancaktab1Wil_reg[$key][$key1][$key2]['bhtm3'] = $totalbhtm3_oanen;
                    $mtancaktab1Wil_reg[$key][$key1][$key2]['buah/jjg'] = $sumPerBH;

                    // $mtancaktab1Wil_reg[$key][$key1][$key2]['jjgperBuah'] = number_format($sumPerBH, 2);
                    // data untuk pelepah sengklek

                    $mtancaktab1Wil_reg[$key][$key1][$key2]['palepah_pokok'] = $totalpelepah_s;
                    // total skor akhir
                    $mtancaktab1Wil_reg[$key][$key1][$key2]['skor_bh'] = skor_brd_ma($brdPerjjg);
                    $mtancaktab1Wil_reg[$key][$key1][$key2]['skor_brd'] = skor_buah_Ma($sumPerBH);
                    $mtancaktab1Wil_reg[$key][$key1][$key2]['skor_ps'] = skor_palepah_ma($perPl);
                    $mtancaktab1Wil_reg[$key][$key1][$key2]['skor_akhir'] = $ttlSkorMA;

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
                    $mtancaktab1Wil_reg[$key][$key1][$key2]['pokok_sample'] = 0;
                    $mtancaktab1Wil_reg[$key][$key1][$key2]['ha_sample'] = 0;
                    $mtancaktab1Wil_reg[$key][$key1][$key2]['jumlah_panen'] = 0;
                    $mtancaktab1Wil_reg[$key][$key1][$key2]['akp_rl'] =  0;

                    $mtancaktab1Wil_reg[$key][$key1][$key2]['p'] = 0;
                    $mtancaktab1Wil_reg[$key][$key1][$key2]['k'] = 0;
                    $mtancaktab1Wil_reg[$key][$key1][$key2]['tgl'] = 0;

                    // $mtancaktab1Wil_reg[$key][$key1][$key2]['total_brd'] = $skor_bTinggal;
                    $mtancaktab1Wil_reg[$key][$key1][$key2]['brd/jjg'] = 0;

                    // data untuk buah tinggal
                    $mtancaktab1Wil_reg[$key][$key1][$key2]['bhts_s'] = 0;
                    $mtancaktab1Wil_reg[$key][$key1][$key2]['bhtm1'] = 0;
                    $mtancaktab1Wil_reg[$key][$key1][$key2]['bhtm2'] = 0;
                    $mtancaktab1Wil_reg[$key][$key1][$key2]['bhtm3'] = 0;

                    // $mtancaktab1Wil_reg[$key][$key1][$key2]['jjgperBuah'] = number_format($sumPerBH, 2);
                    // data untuk pelepah sengklek

                    $mtancaktab1Wil_reg[$key][$key1][$key2]['palepah_pokok'] = 0;
                    // total skor akhi0;

                    $mtancaktab1Wil_reg[$key][$key1][$key2]['skor_bh'] = 0;
                    $mtancaktab1Wil_reg[$key][$key1][$key2]['skor_brd'] = 0;
                    $mtancaktab1Wil_reg[$key][$key1][$key2]['skor_ps'] = 0;
                    $mtancaktab1Wil_reg[$key][$key1][$key2]['skor_akhir'] = 0;
                }

                $sumBHEst = $bhtsEST +  $bhtm1EST +  $bhtm2EST +  $bhtm3EST;
                $totalPKT = $p_panenEst + $k_panenEst + $brtgl_panenEst;
                // dd($sumBHEst);
                if ($pokok_panenEst != 0) {
                    $akpEst = round(($janjang_panenEst / $pokok_panenEst) * 100, 2);
                } else {
                    $akpEst = 0;
                }

                if ($janjang_panenEst != 0) {
                    $brdPerjjgEst = round($totalPKT / $janjang_panenEst, 1);
                } else {
                    $brdPerjjgEst = 0;
                }



                // dd($sumBHEst);
                if ($sumBHEst != 0) {
                    $sumPerBHEst = round($sumBHEst / ($janjang_panenEst + $sumBHEst) * 100, 1);
                } else {
                    $sumPerBHEst = 0;
                }

                if ($pokok_panenEst != 0) {
                    $perPlEst = round(($pelepah_sEST / $pokok_panenEst) * 100, 1);
                } else {
                    $perPlEst = 0;
                }

                $totalSkorEst =  skor_brd_ma($brdPerjjgEst) + skor_buah_Ma($sumPerBHEst) + skor_palepah_ma($perPlEst);
                //PENAMPILAN UNTUK PERESTATE
                $mtancaktab1Wil_reg[$key][$key1]['pokok_sample'] = $pokok_panenEst;
                $mtancaktab1Wil_reg[$key][$key1]['ha_sample'] =  $jum_haEst;
                $mtancaktab1Wil_reg[$key][$key1]['jumlah_panen'] = $janjang_panenEst;
                $mtancaktab1Wil_reg[$key][$key1]['akp_rl'] =  $akpEst;

                $mtancaktab1Wil_reg[$key][$key1]['p'] = $p_panenEst;
                $mtancaktab1Wil_reg[$key][$key1]['k'] = $k_panenEst;
                $mtancaktab1Wil_reg[$key][$key1]['tgl'] = $brtgl_panenEst;

                // $mtancaktab1Wil_reg[$key][$key1]['total_brd'] = $skor_bTinggal;
                $mtancaktab1Wil_reg[$key][$key1]['brd/jjgest'] = $brdPerjjgEst;
                $mtancaktab1Wil_reg[$key][$key1]['buah/jjg'] = $sumPerBHEst;

                // data untuk buah tinggal
                $mtancaktab1Wil_reg[$key][$key1]['bhts_s'] = $bhtsEST;
                $mtancaktab1Wil_reg[$key][$key1]['bhtm1'] = $bhtm1EST;
                $mtancaktab1Wil_reg[$key][$key1]['bhtm2'] = $bhtm2EST;
                $mtancaktab1Wil_reg[$key][$key1]['bhtm3'] = $bhtm3EST;
                $mtancaktab1Wil_reg[$key][$key1]['palepah_pokok'] = $pelepah_sEST;
                $mtancaktab1Wil_reg[$key][$key1]['palepah_per'] = $perPlEst;
                // total skor akhir
                $mtancaktab1Wil_reg[$key][$key1]['skor_bh'] =  skor_brd_ma($brdPerjjgEst);
                $mtancaktab1Wil_reg[$key][$key1]['skor_brd'] = skor_buah_Ma($sumPerBHEst);
                $mtancaktab1Wil_reg[$key][$key1]['skor_ps'] = skor_palepah_ma($perPlEst);
                $mtancaktab1Wil_reg[$key][$key1]['skor_akhir'] = $totalSkorEst;

                //perhitungn untuk perwilayah

                $pokok_panenWil += $pokok_panenEst;
                $jum_haWil += $jum_haEst;
                $janjang_panenWil += $janjang_panenEst;
                $p_panenWil += $p_panenEst;
                $k_panenWil += $k_panenEst;
                $brtgl_panenWil += $brtgl_panenEst;
                // bagian buah tinggal
                $bhts_panenWil += $bhtsEST;
                $bhtm1_panenWil += $bhtm1EST;
                $bhtm2_panenWil += $bhtm2EST;
                $bhtm3_oanenWil += $bhtm3EST;
                $pelepah_swil += $pelepah_sEST;
            } else {
                $mtancaktab1Wil_reg[$key][$key1]['pokok_sample'] = 0;
                $mtancaktab1Wil_reg[$key][$key1]['ha_sample'] =  0;
                $mtancaktab1Wil_reg[$key][$key1]['jumlah_panen'] = 0;
                $mtancaktab1Wil_reg[$key][$key1]['akp_rl'] =  0;

                $mtancaktab1Wil_reg[$key][$key1]['p'] = 0;
                $mtancaktab1Wil_reg[$key][$key1]['k'] = 0;
                $mtancaktab1Wil_reg[$key][$key1]['tgl'] = 0;

                // $mtancaktab1Wil_reg[$key][$key1]['total_brd'] = $skor_bTinggal;
                $mtancaktab1Wil_reg[$key][$key1]['brd/jjgest'] = 0;
                $mtancaktab1Wil_reg[$key][$key1]['buah/jjg'] = 0;
                // data untuk buah tinggal
                $mtancaktab1Wil_reg[$key][$key1]['bhts_s'] = 0;
                $mtancaktab1Wil_reg[$key][$key1]['bhtm1'] = 0;
                $mtancaktab1Wil_reg[$key][$key1]['bhtm2'] = 0;
                $mtancaktab1Wil_reg[$key][$key1]['bhtm3'] = 0;
                $mtancaktab1Wil_reg[$key][$key1]['palepah_pokok'] = 0;
                // total skor akhir
                $mtancaktab1Wil_reg[$key][$key1]['skor_bh'] =  0;
                $mtancaktab1Wil_reg[$key][$key1]['skor_brd'] = 0;
                $mtancaktab1Wil_reg[$key][$key1]['skor_ps'] = 0;
                $mtancaktab1Wil_reg[$key][$key1]['skor_akhir'] = 0;
            }
            $totalPKTwil = $p_panenWil + $k_panenWil + $brtgl_panenWil;
            $sumBHWil = $bhts_panenWil +  $bhtm1_panenWil +  $bhtm2_panenWil +  $bhtm3_oanenWil;

            if ($janjang_panenWil != 0) {
                $akpWil = round(($janjang_panenWil / $pokok_panenWil) * 100, 2);
            } else {
                $akpWil = 0;
            }

            if ($totalPKTwil != 0) {
                $brdPerwil = round($totalPKTwil / $janjang_panenWil, 2);
            } else {
                $brdPerwil = 0;
            }

            // dd($sumBHEst);
            if ($sumBHWil != 0) {
                $sumPerBHWil = round($sumBHWil / ($janjang_panenWil + $sumBHWil) * 100, 2);
            } else {
                $sumPerBHWil = 0;
            }

            if ($pokok_panenWil != 0) {
                $perPiWil = round(($pelepah_swil / $pokok_panenWil) * 100, 2);
            } else {
                $perPiWil = 0;
            }


            $totalWil = skor_brd_ma($brdPerwil) + skor_buah_Ma($sumPerBHWil) + skor_palepah_ma($perPiWil);

            $mtancaktab1Wil_reg[$key]['pokok_sample'] = $pokok_panenWil;
            $mtancaktab1Wil_reg[$key]['ha_sample'] =  $jum_haWil;
            $mtancaktab1Wil_reg[$key]['jumlah_panen'] = $janjang_panenWil;
            $mtancaktab1Wil_reg[$key]['akp_rl'] =  $akpWil;

            $mtancaktab1Wil_reg[$key]['p'] = $p_panenWil;
            $mtancaktab1Wil_reg[$key]['k'] = $k_panenWil;
            $mtancaktab1Wil_reg[$key]['tgl'] = $brtgl_panenWil;

            // $mtancaktab1Wil_reg[$key]['total_brd'] = $skor_bTinggal;
            $mtancaktab1Wil_reg[$key]['brd/jjgwil'] = $brdPerwil;
            $mtancaktab1Wil_reg[$key]['buah/jjgwil'] = $sumPerBHWil;
            $mtancaktab1Wil_reg[$key]['bhts_s'] = $bhts_panenWil;
            $mtancaktab1Wil_reg[$key]['bhtm1'] = $bhtm1_panenWil;
            $mtancaktab1Wil_reg[$key]['bhtm2'] = $bhtm2_panenWil;
            $mtancaktab1Wil_reg[$key]['bhtm3'] = $bhtm3_oanenWil;
            // $mtancaktab1Wil_reg[$key]['jjgperBuah'] = number_format($sumPerBH, 2);
            // data untuk pelepah sengklek
            $mtancaktab1Wil_reg[$key]['palepah_pokok'] = $pelepah_swil;

            $mtancaktab1Wil_reg[$key]['palepah_per'] = $perPiWil;
            // total skor akhir
            $mtancaktab1Wil_reg[$key]['skor_bh'] = skor_brd_ma($brdPerwil);
            $mtancaktab1Wil_reg[$key]['skor_brd'] = skor_buah_Ma($sumPerBHWil);
            $mtancaktab1Wil_reg[$key]['skor_ps'] = skor_palepah_ma($perPiWil);
            $mtancaktab1Wil_reg[$key]['skor_akhir'] = $totalWil;
        } else {
            $mtancaktab1Wil_reg[$key]['pokok_sample'] = 0;
            $mtancaktab1Wil_reg[$key]['ha_sample'] =  0;
            $mtancaktab1Wil_reg[$key]['jumlah_panen'] = 0;
            $mtancaktab1Wil_reg[$key]['akp_rl'] =  0;

            $mtancaktab1Wil_reg[$key]['p'] = 0;
            $mtancaktab1Wil_reg[$key]['k'] = 0;
            $mtancaktab1Wil_reg[$key]['tgl'] = 0;

            // $mtancaktab1Wil_reg[$key]['total_brd'] = $skor_bTinggal;
            $mtancaktab1Wil_reg[$key]['brd/jjgwil'] = 0;
            $mtancaktab1Wil_reg[$key]['buah/jjgwil'] = 0;
            $mtancaktab1Wil_reg[$key]['bhts_s'] = 0;
            $mtancaktab1Wil_reg[$key]['bhtm1'] = 0;
            $mtancaktab1Wil_reg[$key]['bhtm2'] = 0;
            $mtancaktab1Wil_reg[$key]['bhtm3'] = 0;
            // $mtancaktab1Wil_reg[$key]['jjgperBuah'] = number_format($sumPerBH, 2);
            // data untuk pelepah sengklek
            $mtancaktab1Wil_reg[$key]['palepah_pokok'] = 0;
            // total skor akhir
            $mtancaktab1Wil_reg[$key]['skor_bh'] = 0;
            $mtancaktab1Wil_reg[$key]['skor_brd'] = 0;
            $mtancaktab1Wil_reg[$key]['skor_ps'] = 0;
            $mtancaktab1Wil_reg[$key]['skor_akhir'] = 0;
        }

        // dd($mtancaktab1Wil_reg);
        //Ancak regional
        $mtancakReg = array();
        $pkok = 0;
        $ha_sample = 0;
        $panen = 0;
        $p = 0;
        $k = 0;
        $tgl = 0;
        $bhts = 0;
        $bhtm1 = 0;
        $bhtm2 = 0;
        $bhtm3 = 0;
        $palepah = 0;
        foreach ($mtancaktab1Wil_reg as $key => $value) {
            $pkok += $value['pokok_sample'];
            $ha_sample += $value['ha_sample'];
            $panen += $value['jumlah_panen'];
            $p += $value['p'];
            $k += $value['k'];
            $tgl += $value['tgl'];
            $bhts += $value['bhts_s'];
            $bhtm1 += $value['bhtm1'];
            $bhtm2 += $value['bhtm2'];
            $bhtm3 += $value['bhtm3'];
            $palepah += $value['palepah_pokok'];
        }



        if ($panen != 0) {
            $akpWil = round(($panen / $pkok) * 100, 2);
        } else {
            $akpWil = 0;
        }

        $totalPKTwil = $p + $k + $tgl;

        if ($totalPKTwil != 0) {
            $brdPerwil = round($totalPKTwil / $panen, 2);
        } else {
            $brdPerwil = 0;
        }

        $sumBHWil = $bhts +  $bhtm1 +  $bhtm2 +  $bhtm3;

        if ($sumBHWil != 0) {
            $sumPerBHWil = round($sumBHWil / ($panen + $sumBHWil) * 100, 2);
        } else {
            $sumPerBHWil = 0;
        }


        if ($pkok != 0) {
            $perPiWil = round(($palepah / $pkok) * 100, 2);
        } else {
            $perPiWil = 0;
        }


        $totalWil = skor_brd_ma($brdPerwil) + skor_buah_Ma($sumPerBHWil) + skor_palepah_ma($perPiWil);

        $mtancakReg['reg']['pokok_sample'] = $pkok;
        $mtancakReg['reg']['ha_sample'] =  $ha_sample;
        $mtancakReg['reg']['jumlah_panen'] = $panen;
        $mtancakReg['reg']['akp_rl'] =  $akpWil;

        $mtancakReg['reg']['p'] = $p;
        $mtancakReg['reg']['k'] = $k;
        $mtancakReg['reg']['tgl'] = $tgl;

        // $mtancakReg['reg']['total_brd'] = $skor_bTinggal;
        $mtancakReg['reg']['palepah_pokok'] = $palepah;
        $mtancakReg['reg']['bhts_s'] = $bhts;
        $mtancakReg['reg']['bhtm1'] = $bhtm1;
        $mtancakReg['reg']['bhtm2'] = $bhtm2;
        $mtancakReg['reg']['bhtm3'] = $bhtm3;
        $mtancakReg['reg']['brd/jjgwil'] = $brdPerwil;
        $mtancakReg['reg']['perPalepah'] = $perPiWil;
        $mtancakReg['reg']['buah/jjgwil'] = $sumPerBHWil;
        // $mtancakReg['reg']['jjgperBuah'] = number_format($sumPerBH, 2);
        // data untuk pelepah sengklek

        // total skor akhir
        $mtancakReg['reg']['skor_bh'] = skor_brd_ma($brdPerwil);
        $mtancakReg['reg']['skor_brd'] = skor_buah_Ma($sumPerBHWil);
        $mtancakReg['reg']['skor_ps'] = skor_palepah_ma($perPiWil);
        $mtancakReg['reg']['skor_akhir'] = $totalWil;

        // dd($mtBuahtab1Wil);
        //endancak regional
        //Buah Regional
        $mtBuahreg = array();
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
        $jum_ha  = 0;
        $no_Vcut = 0;
        foreach ($mtBuahtab1Wil_reg as $key => $value) {
            $jum_ha += $value['tph_baris_blok'];
            $sum_bmt += $value['total_mentah'];
            $sum_bmk += $value['total_masak'];
            $sum_over += $value['total_over'];
            $sum_kosongjjg += $value['total_jjgKosong'];
            $sum_vcut += $value['total_vcut'];
            $sum_kr += $value['jum_kr'];
            $sum_Samplejjg += $value['sampleJJG_total'];
            $sum_abnor += $value['total_abnormal'];
        }
        // dd($sum_vcut);
        // $no_Vcut = $sum_Samplejjg - $sum_vcut;

        $dataBLok = $jum_ha;
        if ($sum_kr != 0) {
            $total_kr = round($sum_kr / $dataBLok, 2);
        } else {
            $total_kr = 0;
        }

        $per_kr = round($total_kr * 100, 2);
        if ($sum_bmt != 0) {
            $PerMth = round(($sum_bmt / ($sum_Samplejjg - $sum_abnor)) * 100, 2);
        } else {
            $PerMth = 0;
        }
        if ($sum_bmk != 0) {
            $PerMsk = round(($sum_bmk / ($sum_Samplejjg - $sum_abnor)) * 100, 2);
        } else {
            $PerMsk = 0;
        }
        if ($sum_over != 0) {
            $PerOver = round(($sum_over / ($sum_Samplejjg - $sum_abnor)) * 100, 2);
        } else {
            $PerOver = 0;
        }
        if ($sum_kosongjjg != 0) {
            $Perkosongjjg = round(($sum_kosongjjg / ($sum_Samplejjg - $sum_abnor)) * 100, 2);
        } else {
            $Perkosongjjg = 0;
        }
        if ($sum_Samplejjg != 0) {
            $PerVcut = round(($sum_vcut / $sum_Samplejjg) * 100, 2);
        } else {
            $PerVcut = 0;
        }
        if ($sum_abnor != 0) {
            $PerAbr = round(($sum_abnor / $sum_Samplejjg) * 100, 2);
        } else {
            $PerAbr = 0;
        }


        $totalSkor =  skor_buah_mentah_mb($PerMth) + skor_buah_masak_mb($PerMsk) + skor_buah_over_mb($PerOver) + skor_vcut_mb($PerVcut) + skor_jangkos_mb($Perkosongjjg) + skor_abr_mb($per_kr);
        $mtBuahreg['reg']['tph_baris_blok'] = $dataBLok;
        $mtBuahreg['reg']['sampleJJG_total'] = $sum_Samplejjg;
        $mtBuahreg['reg']['total_mentah'] = $sum_bmt;
        $mtBuahreg['reg']['total_perMentah'] = $PerMth;
        $mtBuahreg['reg']['total_masak'] = $sum_bmk;
        $mtBuahreg['reg']['total_perMasak'] = $PerMsk;
        $mtBuahreg['reg']['total_over'] = $sum_over;
        $mtBuahreg['reg']['total_perOver'] = $PerOver;
        $mtBuahreg['reg']['total_abnormal'] = $sum_abnor;
        $mtBuahreg['reg']['total_jjgKosong'] = $sum_kosongjjg;
        $mtBuahreg['reg']['total_perKosongjjg'] = $Perkosongjjg;
        $mtBuahreg['reg']['total_vcut'] = $sum_vcut;

        $mtBuahreg['reg']['jum_kr'] = $sum_kr;
        $mtBuahreg['reg']['total_kr'] = $total_kr;
        $mtBuahreg['reg']['persen_kr'] = $per_kr;

        // skoring
        $mtBuahreg['reg']['skor_mentah'] = skor_buah_mentah_mb($PerMth);
        $mtBuahreg['reg']['skor_masak'] = skor_buah_masak_mb($PerMsk);
        $mtBuahreg['reg']['skor_over'] = skor_buah_over_mb($PerOver);
        $mtBuahreg['reg']['skor_jjgKosong'] = skor_jangkos_mb($Perkosongjjg);
        $mtBuahreg['reg']['skor_vcut'] = skor_vcut_mb($PerVcut);

        $mtBuahreg['reg']['skor_kr'] = skor_abr_mb($per_kr);
        $mtBuahreg['reg']['TOTAL_SKOR'] = $totalSkor;

        // dd($mtBuahreg);
        //EndBuah regional
        //mutu trans reg
        $mttransReg = array();
        $sum_bt = 0;
        $sum_rst = 0;
        $brdPertph = 0;
        $buahPerTPH = 0;
        $totalSkor = 0;
        $dataBLok = 0;
        foreach ($mtTranstab1Wil_reg as $key => $value) {
            $dataBLok += $value['tph_sample'];
            $sum_bt += $value['total_brd'];
            $sum_rst += $value['total_buah'];
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


        $totalSkor =   skor_brd_tinggal($brdPertph) + skor_buah_tinggal($buahPerTPH);

        $mttransReg['reg']['tph_sample'] = $dataBLok;
        $mttransReg['reg']['total_brd'] = $sum_bt;
        $mttransReg['reg']['total_brd/TPH'] = $brdPertph;
        $mttransReg['reg']['total_buah'] = $sum_rst;
        $mttransReg['reg']['total_buahPerTPH'] = $buahPerTPH;
        $mttransReg['reg']['skor_brdPertph'] = skor_brd_tinggal($brdPertph);
        $mttransReg['reg']['skor_buahPerTPH'] = skor_buah_tinggal($buahPerTPH);
        $mttransReg['reg']['totalSkor'] = $totalSkor;


        // dd($mttransReg, $mtancakReg, $mtBuahreg);

        $RekapRegTable = array();
        foreach ($mttransReg as $key => $value) {
            foreach ($mtancakReg as $key1 => $value2) {
                foreach ($mtBuahreg as $key2 => $value3) if ($key == $key1 && $key1 == $key2) {
                    $RekapRegTable[$key] = $value['totalSkor'] + $value2['skor_akhir'] + $value3['TOTAL_SKOR'];
                }
            }
        }


        //endMututrans reg
        // dd($mtancaktab1Wil['1']['PLE']['skor_akhir'], $mtTranstab1Wil['1']['PLE']['totalSkor'], $mtBuahtab1Wil['1']['PLE']['TOTAL_SKOR']);
        $ptmuaAncak = DB::connection('mysql2')->table('mutu_ancak_new')
            ->select(
                "mutu_ancak_new.*",
                DB::raw('DATE_FORMAT(mutu_ancak_new.datetime, "%M") as bulan'),
                DB::raw('DATE_FORMAT(mutu_ancak_new.datetime, "%Y") as tahun')
            )
            // ->whereBetween('mutu_ancak_new.datetime', ['2023-04-06', '2023-04-12'])
            ->whereBetween('mutu_ancak_new.datetime', [$startDate, $endDate])
            ->whereIn('estate', ['LDE', 'SKE', 'SRE'])
            ->get();

        $ptmuaAncak = $ptmuaAncak->groupBy(['estate', 'afdeling']);
        $ptmuaAncak = json_decode($ptmuaAncak, true);

        // dd($ptmuaAncak);
        $ptMuaBuah = DB::connection('mysql2')->table('mutu_buah')
            ->select(
                "mutu_buah.*",
                DB::raw('DATE_FORMAT(mutu_buah.datetime, "%M") as bulan'),
                DB::raw('DATE_FORMAT(mutu_buah.datetime, "%Y") as tahun')
            )
            // ->whereBetween('mutu_buah.datetime', ['2023-04-06', '2023-04-12'])
            ->whereBetween('mutu_buah.datetime', [$startDate, $endDate])
            ->whereIn('estate', ['LDE', 'SKE', 'SRE'])
            ->get();

        $ptMuaBuah = $ptMuaBuah->groupBy(['estate', 'afdeling']);
        $ptMuaBuah = json_decode($ptMuaBuah, true);

        $ptMuaTrans = DB::connection('mysql2')->table('mutu_transport')
            ->select(
                "mutu_transport.*",
                DB::raw('DATE_FORMAT(mutu_transport.datetime, "%M") as bulan'),
                DB::raw('DATE_FORMAT(mutu_transport.datetime, "%Y") as tahun')
            )
            // ->whereBetween('mutu_transport.datetime', ['2023-04-06', '2023-04-12'])
            ->whereBetween('mutu_transport.datetime', [$startDate, $endDate])
            ->whereIn('estate', ['LDE', 'SKE', 'SRE'])
            ->get();

        $ptMuaTrans = $ptMuaTrans->groupBy(['estate', 'afdeling']);
        $ptMuaTrans = json_decode($ptMuaTrans, true);
        // dd($ptmuaAncak, $ptMuaTrans, $ptMuaBuah);
        //merubah jika key estate and afdeling same maka jadikan key afdeling jadi OA
        $modifiedPtmuaAncak = [];
        foreach ($ptmuaAncak as $estate => $afdelings) {
            foreach ($afdelings as $afdeling => $data) {
                if ($estate == $afdeling) {
                    $modifiedPtmuaAncak[$estate]['OA'] = $data;
                } else {
                    $modifiedPtmuaAncak[$estate][$afdeling] = $data;
                }
            }
        }

        $ptmuaAncak = $modifiedPtmuaAncak;

        $modifiedPtmuaBuah = [];
        foreach ($ptMuaBuah as $estate => $afdelings) {
            foreach ($afdelings as $afdeling => $data) {
                if ($estate == $afdeling) {
                    $modifiedPtmuaBuah[$estate]['OA'] = $data;
                } else {
                    $modifiedPtmuaBuah[$estate][$afdeling] = $data;
                }
            }
        }

        $ptmuaBuah = $modifiedPtmuaBuah;

        $modifiedPtmuaTrans = [];
        foreach ($ptMuaTrans as $estate => $afdelings) {
            foreach ($afdelings as $afdeling => $data) {
                if ($estate == $afdeling) {
                    $modifiedPtmuaTrans[$estate]['OA'] = $data;
                } else {
                    $modifiedPtmuaTrans[$estate][$afdeling] = $data;
                }
            }
        }

        $ptmuaTrans = $modifiedPtmuaTrans;



        // dd($ptmuaAncak, $ptmuaTrans, $ptmuaBuah);




        $mtAncakMua = array();
        $pokok_panenWil = 0;
        $jum_haWil = 0;
        $janjang_panenWil = 0;
        $p_panenWil = 0;
        $k_panenWil = 0;
        $brtgl_panenWil = 0;
        $bhts_panenWil = 0;
        $bhtm1_panenWil = 0;
        $bhtm2_panenWil = 0;
        $bhtm3_oanenWil = 0;
        $pelepah_swil = 0;
        $totalPKTwil = 0;
        $sumBHWil = 0;
        $akpWil = 0;
        $brdPerwil = 0;
        $sumPerBHWil = 0;
        $perPiWil = 0;
        $totalWil = 0;
        foreach ($ptmuaAncak as $key => $value)  if (!empty($value)) {
            $pokok_panenEst = 0;
            $jum_haEst =  0;
            $janjang_panenEst =  0;
            $akpEst =  0;
            $p_panenEst =  0;
            $k_panenEst =  0;
            $brtgl_panenEst = 0;
            $skor_bTinggalEst =  0;
            $brdPerjjgEst =  0;
            $bhtsEST = 0;
            $bhtm1EST = 0;
            $bhtm2EST = 0;
            $bhtm3EST = 0;
            $pelepah_sEST = 0;

            $skor_bhEst =  0;
            $skor_brdPerjjgEst =  0;
            foreach ($value as $key1 => $value1) if (!empty($value1)) {
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
                $skor_brdPerjjg = 0;
                $skor_bh = 0;
                $skor_perPl = 0;
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
                foreach ($value1 as $key2 => $value2) if (!empty($value2)) {
                    // dd($value2);
                    if (!in_array($value2['estate'] . ' ' . $value2['afdeling'] . ' ' . $value2['blok'], $listBlokPerAfd)) {
                        $listBlokPerAfd[] = $value2['estate'] . ' ' . $value2['afdeling'] . ' ' . $value2['blok'];
                    }
                    $jum_ha = count($listBlokPerAfd);
                    $totalPokok += $value2["sample"];
                    $totalPanen +=  $value2["jjg"];
                    $totalP_panen += $value2["brtp"];
                    $totalK_panen += $value2["brtk"];
                    $totalPTgl_panen += $value2["brtgl"];

                    $totalbhts_panen += $value2["bhts"];
                    $totalbhtm1_panen += $value2["bhtm1"];
                    $totalbhtm2_panen += $value2["bhtm2"];
                    $totalbhtm3_oanen += $value2["bhtm3"];

                    $totalpelepah_s += $value2["ps"];
                }

                if ($totalPokok != 0) {
                    $akp = round(($totalPanen / $totalPokok) * 100, 1);
                } else {
                    $akp = 0;
                }


                $skor_bTinggal = $totalP_panen + $totalK_panen + $totalPTgl_panen;

                if ($totalPanen != 0) {
                    $brdPerjjg = round($skor_bTinggal / $totalPanen, 1);
                } else {
                    $brdPerjjg = 0;
                }

                $sumBH = $totalbhts_panen +  $totalbhtm1_panen +  $totalbhtm2_panen +  $totalbhtm3_oanen;
                if ($sumBH != 0) {
                    $sumPerBH = round($sumBH / ($totalPanen + $sumBH) * 100, 1);
                } else {
                    $sumPerBH = 0;
                }

                if ($totalpelepah_s != 0) {
                    $perPl = round(($totalpelepah_s / $totalPokok) * 100, 2);
                } else {
                    $perPl = 0;
                }


                $nonZeroValues = array_filter([$totalP_panen, $totalK_panen, $totalPTgl_panen, $totalbhts_panen, $totalbhtm1_panen, $totalbhtm2_panen, $totalbhtm3_oanen]);

                if (!empty($nonZeroValues)) {
                    $mtAncakMua[$key][$key1]['check_data'] = 'ada';
                    // $mtAncakMua[$key][$key1]['skor_brd'] = $skor_brd = skor_brd_ma($sumPerBH);
                    // $mtAncakMua[$key][$key1]['skor_ps'] = $skor_ps = skor_palepah_ma($perPl);
                } else {
                    $mtAncakMua[$key][$key1]['check_data'] = 'kosong';
                    // $mtAncakMua[$key][$key1]['skor_brd'] = $skor_brd = 0;
                    // $mtAncakMua[$key][$key1]['skor_ps'] = $skor_ps = 0;
                }

                // $ttlSkorMA = $skor_bh + $skor_brd + $skor_ps;
                $ttlSkorMA = skor_brd_ma($brdPerjjg) + skor_buah_Ma($sumPerBH) + skor_palepah_ma($perPl);

                $mtAncakMua[$key][$key1]['pokok_sample'] = $totalPokok;
                $mtAncakMua[$key][$key1]['ha_sample'] = $jum_ha;
                $mtAncakMua[$key][$key1]['jumlah_panen'] = $totalPanen;
                $mtAncakMua[$key][$key1]['akp_rl'] = $akp;

                $mtAncakMua[$key][$key1]['p'] = $totalP_panen;
                $mtAncakMua[$key][$key1]['k'] = $totalK_panen;
                $mtAncakMua[$key][$key1]['tgl'] = $totalPTgl_panen;

                $mtAncakMua[$key][$key1]['total_brd'] = $skor_bTinggal;
                $mtAncakMua[$key][$key1]['brd/jjg'] = $brdPerjjg;

                // data untuk buah tinggal
                $mtAncakMua[$key][$key1]['bhts_s'] = $totalbhts_panen;
                $mtAncakMua[$key][$key1]['bhtm1'] = $totalbhtm1_panen;
                $mtAncakMua[$key][$key1]['bhtm2'] = $totalbhtm2_panen;
                $mtAncakMua[$key][$key1]['bhtm3'] = $totalbhtm3_oanen;
                $mtAncakMua[$key][$key1]['buah/jjg'] = $sumPerBH;

                $mtAncakMua[$key][$key1]['jjgperBuah'] = number_format($sumPerBH, 2);
                // data untuk pelepah sengklek

                $mtAncakMua[$key][$key1]['palepah_pokok'] = $totalpelepah_s;
                // total skor akhir
                $mtAncakMua[$key][$key1]['skor_bh'] = skor_brd_ma($brdPerjjg);
                $mtAncakMua[$key][$key1]['skor_brd'] = skor_buah_Ma($sumPerBH);
                $mtAncakMua[$key][$key1]['skor_ps'] = skor_palepah_ma($perPl);
                $mtAncakMua[$key][$key1]['skor_akhir'] = $ttlSkorMA;

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
                $mtAncakMua[$key][$key1]['pokok_sample'] = 0;
                $mtAncakMua[$key][$key1]['ha_sample'] = 0;
                $mtAncakMua[$key][$key1]['jumlah_panen'] = 0;
                $mtAncakMua[$key][$key1]['akp_rl'] =  0;

                $mtAncakMua[$key][$key1]['p'] = 0;
                $mtAncakMua[$key][$key1]['k'] = 0;
                $mtAncakMua[$key][$key1]['tgl'] = 0;

                // $mtAncakMua[$key][$key1]['total_brd'] = $skor_bTinggal;
                $mtAncakMua[$key][$key1]['brd/jjg'] = 0;

                // data untuk buah tinggal
                $mtAncakMua[$key][$key1]['bhts_s'] = 0;
                $mtAncakMua[$key][$key1]['bhtm1'] = 0;
                $mtAncakMua[$key][$key1]['bhtm2'] = 0;
                $mtAncakMua[$key][$key1]['bhtm3'] = 0;

                // $mtAncakMua[$key][$key1]['jjgperBuah'] = number_format($sumPerBH, 2);
                // data untuk pelepah sengklek

                $mtAncakMua[$key][$key1]['palepah_pokok'] = 0;
                // total skor akhi0;

                $mtAncakMua[$key][$key1]['skor_bh'] = 0;
                $mtAncakMua[$key][$key1]['skor_brd'] = 0;
                $mtAncakMua[$key][$key1]['skor_ps'] = 0;
                $mtAncakMua[$key][$key1]['skor_akhir'] = 0;
            }
            $sumBHEst = $bhtsEST +  $bhtm1EST +  $bhtm2EST +  $bhtm3EST;
            $totalPKT = $p_panenEst + $k_panenEst + $brtgl_panenEst;
            // dd($sumBHEst);
            if ($pokok_panenEst != 0) {
                $akpEst = round(($janjang_panenEst / $pokok_panenEst) * 100, 2);
            } else {
                $akpEst = 0;
            }

            if ($janjang_panenEst != 0) {
                $brdPerjjgEst = round($totalPKT / $janjang_panenEst, 1);
            } else {
                $brdPerjjgEst = 0;
            }



            // dd($sumBHEst);
            if ($sumBHEst != 0) {
                $sumPerBHEst = round($sumBHEst / ($janjang_panenEst + $sumBHEst) * 100, 1);
            } else {
                $sumPerBHEst = 0;
            }

            if ($pokok_panenEst != 0) {
                $perPlEst = round(($pelepah_sEST / $pokok_panenEst) * 100, 1);
            } else {
                $perPlEst = 0;
            }

            $nonZeroValues = array_filter([$p_panenEst, $k_panenEst, $brtgl_panenEst, $bhtsEST, $bhtm1EST, $bhtm2EST, $bhtm3EST]);

            if (!empty($nonZeroValues)) {
                $mtAncakMua[$key]['check_data'] = 'ada';
                // $mtAncakMua[$key]['skor_brd'] = $skor_brd = skor_brd_ma($sumPerBHEst);
                // $mtAncakMua[$key]['skor_ps'] = $skor_ps = skor_palepah_ma($perPlEst);
            } else {
                $mtAncakMua[$key]['check_data'] = 'kosong';
                // $mtAncakMua[$key]['skor_brd'] = $skor_brd = 0;
                // $mtAncakMua[$key]['skor_ps'] = $skor_ps = 0;
            }

            // $totalSkorEst = $skor_bh + $skor_brd + $skor_ps;
            $totalSkorEst =  skor_brd_ma($brdPerjjgEst) + skor_buah_Ma($sumPerBHEst) + skor_palepah_ma($perPlEst);
            //PENAMPILAN UNTUK PERESTATE
            $mtAncakMua[$key]['pokok_sample'] = $pokok_panenEst;
            $mtAncakMua[$key]['ha_sample'] =  $jum_haEst;
            $mtAncakMua[$key]['jumlah_panen'] = $janjang_panenEst;
            $mtAncakMua[$key]['akp_rl'] =  $akpEst;

            $mtAncakMua[$key]['p'] = $p_panenEst;
            $mtAncakMua[$key]['k'] = $k_panenEst;
            $mtAncakMua[$key]['tgl'] = $brtgl_panenEst;

            $mtAncakMua[$key]['total_brd'] = $skor_bTinggal;
            $mtAncakMua[$key]['brd/jjgest'] = $brdPerjjgEst;
            $mtAncakMua[$key]['buah/jjg'] = $sumPerBHEst;

            // data untuk buah tinggal
            $mtAncakMua[$key]['bhts_s'] = $bhtsEST;
            $mtAncakMua[$key]['bhtm1'] = $bhtm1EST;
            $mtAncakMua[$key]['bhtm2'] = $bhtm2EST;
            $mtAncakMua[$key]['bhtm3'] = $bhtm3EST;
            $mtAncakMua[$key]['palepah_pokok'] = $pelepah_sEST;
            $mtAncakMua[$key]['palepah_per'] = $perPlEst;
            // total skor akhir
            $mtAncakMua[$key]['skor_bh'] =  skor_brd_ma($brdPerjjgEst);
            $mtAncakMua[$key]['skor_brd'] = skor_buah_Ma($sumPerBHEst);
            $mtAncakMua[$key]['skor_ps'] = skor_palepah_ma($perPlEst);
            $mtAncakMua[$key]['skor_akhir'] = $totalSkorEst;


            $pokok_panenWil += $pokok_panenEst;
            $jum_haWil += $jum_haEst;
            $janjang_panenWil += $janjang_panenEst;
            $p_panenWil += $p_panenEst;
            $k_panenWil += $k_panenEst;
            $brtgl_panenWil += $brtgl_panenEst;
            // bagian buah tinggal
            $bhts_panenWil += $bhtsEST;
            $bhtm1_panenWil += $bhtm1EST;
            $bhtm2_panenWil += $bhtm2EST;
            $bhtm3_oanenWil += $bhtm3EST;
            $pelepah_swil += $pelepah_sEST;
        } else {
            $value[$key]['pokok_sample'] = 0;
            $value[$key]['ha_sample'] =  0;
            $value[$key]['jumlah_panen'] = 0;
            $value[$key]['akp_rl'] =  0;

            $value[$key]['p'] = 0;
            $value[$key]['k'] = 0;
            $value[$key]['tgl'] = 0;

            // $value[$key]['total_brd'] = $skor_bTinggal;
            $value[$key]['brd/jjgest'] = 0;
            $value[$key]['buah/jjg'] = 0;
            // data untuk buah tinggal
            $value[$key]['bhts_s'] = 0;
            $value[$key]['bhtm1'] = 0;
            $value[$key]['bhtm2'] = 0;
            $value[$key]['bhtm3'] = 0;
            $value[$key]['palepah_pokok'] = 0;
            // total skor akhir
            $value[$key]['skor_bh'] =  0;
            $value[$key]['skor_brd'] = 0;
            $value[$key]['skor_ps'] = 0;
            $value[$key]['skor_akhir'] = 0;
        }
        $totalPKTwil = $p_panenWil + $k_panenWil + $brtgl_panenWil;
        $sumBHWil = $bhts_panenWil +  $bhtm1_panenWil +  $bhtm2_panenWil +  $bhtm3_oanenWil;

        if ($janjang_panenWil != 0) {
            $akpWil = round(($janjang_panenWil / $pokok_panenWil) * 100, 2);
        } else {
            $akpWil = 0;
        }

        if ($totalPKTwil != 0) {
            $brdPerwil = round($totalPKTwil / $janjang_panenWil, 1);
        } else {
            $brdPerwil = 0;
        }

        // dd($sumBHEst);
        if ($sumBHWil != 0) {
            $sumPerBHWil = round($sumBHWil / ($janjang_panenWil + $sumBHWil) * 100, 1);
        } else {
            $sumPerBHWil = 0;
        }

        if ($pokok_panenWil != 0) {
            $perPiWil = round(($pelepah_swil / $pokok_panenWil) * 100, 1);
        } else {
            $perPiWil = 0;
        }


        $nonZeroValuesAncak = array_filter([$p_panenWil, $k_panenWil, $brtgl_panenWil, $bhts_panenWil, $bhtm1_panenWil, $bhtm2_panenWil, $bhtm3_oanenWil]);

        if (!empty($nonZeroValuesAncak)) {
            $mtAncakMua['check_data'] = 'ada';
            // $mtAncakMua['skor_brd'] = $skor_brd = skor_brd_ma($sumPerBHEst);
            // $mtAncakMua['skor_ps'] = $skor_ps = skor_palepah_ma($perPlEst);
        } else {
            $mtAncakMua['check_data'] = 'kosong';
            // $mtAncakMua['skor_brd'] = $skor_brd = 0;
            // $mtAncakMua['skor_ps'] = $skor_ps = 0;
        }

        // $totalWil = $skor_bh + $skor_brd + $skor_ps;

        $totalWil = skor_brd_ma($brdPerwil) + skor_buah_Ma($sumPerBHWil) + skor_palepah_ma($perPiWil);

        $mtAncakMua['pokok_sample'] = $pokok_panenWil;
        $mtAncakMua['ha_sample'] =  $jum_haWil;
        $mtAncakMua['jumlah_panen'] = $janjang_panenWil;
        $mtAncakMua['akp_rl'] =  $akpWil;

        $mtAncakMua['p'] = $p_panenWil;
        $mtAncakMua['k'] = $k_panenWil;
        $mtAncakMua['tgl'] = $brtgl_panenWil;

        $mtAncakMua['total_brd'] = $skor_bTinggal;
        $mtAncakMua['brd/jjgwil'] = $brdPerwil;
        $mtAncakMua['buah/jjgwil'] = $sumPerBHWil;
        $mtAncakMua['bhts_s'] = $bhts_panenWil;
        $mtAncakMua['bhtm1'] = $bhtm1_panenWil;
        $mtAncakMua['bhtm2'] = $bhtm2_panenWil;
        $mtAncakMua['bhtm3'] = $bhtm3_oanenWil;
        $mtAncakMua['jjgperBuah'] = number_format($sumPerBH, 2);
        // data untuk pelepah sengklek
        $mtAncakMua['palepah_pokok'] = $pelepah_swil;

        $mtAncakMua['palepah_per'] = $perPiWil;
        // total skor akhir
        $mtAncakMua['skor_bh'] = skor_brd_ma($brdPerwil);
        $mtAncakMua['skor_brd'] = skor_buah_Ma($sumPerBHWil);
        $mtAncakMua['skor_ps'] = skor_palepah_ma($perPiWil);
        $mtAncakMua['skor_akhir'] = $totalWil;
        // dd($mtAncakMua);
        // const sum_pokok_sample = array["SKE"]["pokok_sample"] + array["LDE"]["pokok_sample"];

        $mtBuahMua = array();
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
        foreach ($ptmuaBuah as $key => $value) if (is_array($value)) {
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
            foreach ($value as $key1 => $value1) if (is_array($value1)) {
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
                $combination_counts = array();
                foreach ($value1 as $key2 => $value2) if (is_array($value2)) {
                    $combination = $value2['blok'] . ' ' . $value2['estate'] . ' ' . $value2['afdeling'] . ' ' . $value2['tph_baris'];
                    if (!isset($combination_counts[$combination])) {
                        $combination_counts[$combination] = 0;
                    }
                    $jum_ha = count($listBlokPerAfd);
                    $sum_bmt += $value2['bmt'];
                    $sum_bmk += $value2['bmk'];
                    $sum_over += $value2['overripe'];
                    $sum_kosongjjg += $value2['empty_bunch'];
                    $sum_vcut += $value2['vcut'];
                    $sum_kr += $value2['alas_br'];


                    $sum_Samplejjg += $value2['jumlah_jjg'];
                    $sum_abnor += $value2['abnormal'];
                }

                $dataBLok = count($combination_counts);
                $jml_mth = ($sum_bmt + $sum_bmk);
                $jml_mtg = $sum_Samplejjg - ($jml_mth + $sum_over + $sum_kosongjjg + $sum_abnor);

                if ($sum_kr != 0) {
                    $total_kr = round($sum_kr / $dataBLok, 2);
                } else {
                    $total_kr = 0;
                }


                $per_kr = round($total_kr * 100, 2);
                if ($jml_mth != 0) {
                    $PerMth = round(($jml_mth / ($sum_Samplejjg - $sum_abnor)) * 100, 2);
                } else {
                    $PerMth = 0;
                }
                if ($jml_mtg != 0) {
                    $PerMsk = round(($jml_mtg / ($sum_Samplejjg - $sum_abnor)) * 100, 2);
                } else {
                    $PerMsk = 0;
                }
                if ($sum_over != 0) {
                    $PerOver = round(($sum_over / ($sum_Samplejjg - $sum_abnor)) * 100, 2);
                } else {
                    $PerOver = 0;
                }
                if ($sum_kosongjjg != 0) {
                    $Perkosongjjg = round(($sum_kosongjjg / ($sum_Samplejjg - $sum_abnor)) * 100, 2);
                } else {
                    $Perkosongjjg = 0;
                }
                if ($sum_vcut != 0) {
                    $PerVcut = round(($sum_vcut / $sum_Samplejjg) * 100, 2);
                } else {
                    $PerVcut = 0;
                }

                if ($sum_abnor != 0) {
                    $PerAbr = round(($sum_abnor / $sum_Samplejjg) * 100, 2);
                } else {
                    $PerAbr = 0;
                }
                $nonZeroValues = array_filter([$sum_Samplejjg, $PerMth, $PerMsk, $PerOver, $sum_abnor, $sum_kosongjjg, $sum_vcut]);

                if (!empty($nonZeroValues)) {
                    $mtBuahMua[$key][$key1]['check_data'] = 'ada';
                    // $mtBuahMua[$key][$key1]['skor_masak'] = $skor_masak =  skor_buah_masak_mb($PerMsk);
                    // $mtBuahMua[$key][$key1]['skor_over'] = $skor_over =  skor_buah_over_mb($PerOver);
                    // $mtBuahMua[$key][$key1]['skor_jjgKosong'] = $skor_jjgKosong =  skor_jangkos_mb($Perkosongjjg);
                    // $mtBuahMua[$key][$key1]['skor_vcut'] = $skor_vcut =  skor_vcut_mb($PerVcut);
                    // $mtBuahMua[$key][$key1]['skor_kr'] = $skor_kr =  skor_abr_mb($per_kr);
                } else {
                    $mtBuahMua[$key][$key1]['check_data'] = 'kosong';
                    // $mtBuahMua[$key][$key1]['skor_masak'] = $skor_masak = 0;
                    // $mtBuahMua[$key][$key1]['skor_over'] = $skor_over = 0;
                    // $mtBuahMua[$key][$key1]['skor_jjgKosong'] = $skor_jjgKosong = 0;
                    // $mtBuahMua[$key][$key1]['skor_vcut'] = $skor_vcut =  0;
                    // $mtBuahMua[$key][$key1]['skor_kr'] = $skor_kr = 0;
                }

                // $totalSkor = $skor_mentah + $skor_masak + $skor_over + $skor_jjgKosong + $skor_vcut + $skor_kr;


                $totalSkor =  skor_buah_mentah_mb($PerMth) + skor_buah_masak_mb($PerMsk) + skor_buah_over_mb($PerOver) + skor_vcut_mb($PerVcut) + skor_jangkos_mb($Perkosongjjg) + skor_abr_mb($per_kr);
                $mtBuahMua[$key][$key1]['tph_baris_bloks'] = $dataBLok;
                $mtBuahMua[$key][$key1]['sampleJJG_total'] = $sum_Samplejjg;
                $mtBuahMua[$key][$key1]['total_mentah'] = $jml_mth;
                $mtBuahMua[$key][$key1]['total_perMentah'] = $PerMth;
                $mtBuahMua[$key][$key1]['total_masak'] = $jml_mtg;
                $mtBuahMua[$key][$key1]['total_perMasak'] = $PerMsk;
                $mtBuahMua[$key][$key1]['total_over'] = $sum_over;
                $mtBuahMua[$key][$key1]['total_perOver'] = $PerOver;
                $mtBuahMua[$key][$key1]['total_abnormal'] = $sum_abnor;
                $mtBuahMua[$key][$key1]['perAbnormal'] = $PerAbr;
                $mtBuahMua[$key][$key1]['total_jjgKosong'] = $sum_kosongjjg;
                $mtBuahMua[$key][$key1]['total_perKosongjjg'] = $Perkosongjjg;
                $mtBuahMua[$key][$key1]['total_vcut'] = $sum_vcut;
                $mtBuahMua[$key][$key1]['perVcut'] = $PerVcut;

                $mtBuahMua[$key][$key1]['jum_kr'] = $sum_kr;
                $mtBuahMua[$key][$key1]['total_kr'] = $total_kr;
                $mtBuahMua[$key][$key1]['persen_kr'] = $per_kr;

                // skoring
                $mtBuahMua[$key][$key1]['skor_mentah'] = skor_buah_mentah_mb($PerMth);
                $mtBuahMua[$key][$key1]['skor_masak'] = skor_buah_masak_mb($PerMsk);
                $mtBuahMua[$key][$key1]['skor_over'] = skor_buah_over_mb($PerOver);
                $mtBuahMua[$key][$key1]['skor_jjgKosong'] = skor_jangkos_mb($Perkosongjjg);
                $mtBuahMua[$key][$key1]['skor_vcut'] = skor_vcut_mb($PerVcut);
                $mtBuahMua[$key][$key1]['skor_kr'] = skor_abr_mb($per_kr);

                $mtBuahMua[$key][$key1]['TOTAL_SKOR'] = $totalSkor;

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
                $mtBuahMua[$key][$key1]['tph_baris_blok'] = 0;
                $mtBuahMua[$key][$key1]['sampleJJG_total'] = 0;
                $mtBuahMua[$key][$key1]['total_mentah'] = 0;
                $mtBuahMua[$key][$key1]['total_perMentah'] = 0;
                $mtBuahMua[$key][$key1]['total_masak'] = 0;
                $mtBuahMua[$key][$key1]['total_perMasak'] = 0;
                $mtBuahMua[$key][$key1]['total_over'] = 0;
                $mtBuahMua[$key][$key1]['total_perOver'] = 0;
                $mtBuahMua[$key][$key1]['total_abnormal'] = 0;
                $mtBuahMua[$key][$key1]['perAbnormal'] = 0;
                $mtBuahMua[$key][$key1]['total_jjgKosong'] = 0;
                $mtBuahMua[$key][$key1]['total_perKosongjjg'] = 0;
                $mtBuahMua[$key][$key1]['total_vcut'] = 0;
                $mtBuahMua[$key][$key1]['perVcut'] = 0;

                $mtBuahMua[$key][$key1]['jum_kr'] = 0;
                $mtBuahMua[$key][$key1]['total_kr'] = 0;
                $mtBuahMua[$key][$key1]['persen_kr'] = 0;

                // skoring
                $mtBuahMua[$key][$key1]['skor_mentah'] = 0;
                $mtBuahMua[$key][$key1]['skor_masak'] = 0;
                $mtBuahMua[$key][$key1]['skor_over'] = 0;
                $mtBuahMua[$key][$key1]['skor_jjgKosong'] = 0;
                $mtBuahMua[$key][$key1]['skor_vcut'] = 0;
                $mtBuahMua[$key][$key1]['skor_abnormal'] = 0;;
                $mtBuahMua[$key][$key1]['skor_kr'] = 0;
                $mtBuahMua[$key][$key1]['TOTAL_SKOR'] = 0;
            }
            $no_VcutEst = $sum_SamplejjgEst - $sum_vcutEst;

            if ($sum_krEst != 0) {
                $total_krEst = round($sum_krEst / $jum_haEst, 2);
            } else {
                $total_krEst = 0;
            }
            // if ($sum_kr != 0) {
            //     $total_kr = round($sum_kr / $dataBLok, 2);
            // } else {
            //     $total_kr = 0;
            // }

            if ($sum_bmtEst != 0) {
                $PerMthEst = round(($sum_bmtEst / ($sum_SamplejjgEst - $sum_abnorEst)) * 100, 2);
            } else {
                $PerMthEst = 0;
            }

            if ($sum_bmkEst != 0) {
                $PerMskEst = round(($sum_bmkEst / ($sum_SamplejjgEst - $sum_abnorEst)) * 100, 2);
            } else {
                $PerMskEst = 0;
            }

            if ($sum_overEst != 0) {
                $PerOverEst = round(($sum_overEst / ($sum_SamplejjgEst - $sum_abnorEst)) * 100, 2);
            } else {
                $PerOverEst = 0;
            }
            if ($sum_kosongjjgEst != 0) {
                $PerkosongjjgEst = round(($sum_kosongjjgEst / ($sum_SamplejjgEst - $sum_abnorEst)) * 100, 2);
            } else {
                $PerkosongjjgEst = 0;
            }
            if ($sum_vcutEst != 0) {
                $PerVcutest = round(($sum_vcutEst / $sum_SamplejjgEst) * 100, 2);
            } else {
                $PerVcutest = 0;
            }
            if ($sum_abnorEst != 0) {
                $PerAbrest = round(($sum_abnorEst / $sum_SamplejjgEst) * 100, 2);
            } else {
                $PerAbrest = 0;
            }
            // $per_kr = round($sum_kr * 100);
            $per_krEst = round($total_krEst * 100, 2);

            $nonZeroValues = array_filter([$sum_SamplejjgEst, $PerMthEst, $PerMskEst, $PerOverEst, $sum_abnorEst, $sum_kosongjjgEst, $sum_vcutEst]);

            if (!empty($nonZeroValues)) {
                $mtBuahMua[$key]['check_data'] = 'ada';
                // $mtBuahMua[$key]['skor_masak'] = $skor_masak =  skor_buah_masak_mb($PerMskEst);
                // $mtBuahMua[$key]['skor_over'] = $skor_over =  skor_buah_over_mb($PerOverEst);
                // $mtBuahMua[$key]['skor_jjgKosong'] = $skor_jjgKosong =  skor_jangkos_mb($PerkosongjjgEst);
                // $mtBuahMua[$key]['skor_vcut'] = $skor_vcut =  skor_vcut_mb($PerVcutest);
                // $mtBuahMua[$key]['skor_kr'] = $skor_kr =  skor_abr_mb($per_krEst);
            } else {
                $mtBuahMua[$key]['check_data'] = 'kosong';
                // $mtBuahMua[$key]['skor_masak'] = $skor_masak = 0;
                // $mtBuahMua[$key]['skor_over'] = $skor_over = 0;
                // $mtBuahMua[$key]['skor_jjgKosong'] = $skor_jjgKosong = 0;
                // $mtBuahMua[$key]['skor_vcut'] = $skor_vcut =  0;
                // $mtBuahMua[$key]['skor_kr'] = $skor_kr = 0;
            }

            // $totalSkorEst = $skor_mentah + $skor_masak + $skor_over + $skor_jjgKosong + $skor_vcut + $skor_kr;
            $totalSkorEst =   skor_buah_mentah_mb($PerMthEst) + skor_buah_masak_mb($PerMskEst) + skor_buah_over_mb($PerOverEst) + skor_jangkos_mb($PerkosongjjgEst) + skor_vcut_mb($PerVcutest) + skor_abr_mb($per_krEst);
            $mtBuahMua[$key]['tph_baris_blok'] = $jum_haEst;
            $mtBuahMua[$key]['sampleJJG_total'] = $sum_SamplejjgEst;
            $mtBuahMua[$key]['total_mentah'] = $sum_bmtEst;
            $mtBuahMua[$key]['total_perMentah'] = $PerMthEst;
            $mtBuahMua[$key]['total_masak'] = $sum_bmkEst;
            $mtBuahMua[$key]['total_perMasak'] = $PerMskEst;
            $mtBuahMua[$key]['total_over'] = $sum_overEst;
            $mtBuahMua[$key]['total_perOver'] = $PerOverEst;
            $mtBuahMua[$key]['total_abnormal'] = $sum_abnorEst;
            $mtBuahMua[$key]['total_perabnormal'] = $PerAbrest;
            $mtBuahMua[$key]['total_jjgKosong'] = $sum_kosongjjgEst;
            $mtBuahMua[$key]['total_perKosongjjg'] = $PerkosongjjgEst;
            $mtBuahMua[$key]['total_vcut'] = $sum_vcutEst;
            $mtBuahMua[$key]['perVcut'] = $PerVcutest;
            $mtBuahMua[$key]['jum_kr'] = $sum_krEst;
            $mtBuahMua[$key]['kr_blok'] = $total_krEst;

            $mtBuahMua[$key]['persen_kr'] = $per_krEst;

            // skoring
            $mtBuahMua[$key]['skor_mentah'] =  skor_buah_mentah_mb($PerMthEst);
            $mtBuahMua[$key]['skor_masak'] = skor_buah_masak_mb($PerMskEst);
            $mtBuahMua[$key]['skor_over'] = skor_buah_over_mb($PerOverEst);;
            $mtBuahMua[$key]['skor_jjgKosong'] = skor_jangkos_mb($PerkosongjjgEst);
            $mtBuahMua[$key]['skor_vcut'] = skor_vcut_mb($PerVcutest);
            $mtBuahMua[$key]['skor_kr'] = skor_abr_mb($per_krEst);
            $mtBuahMua[$key]['TOTAL_SKOR'] = $totalSkorEst;

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
            $mtBuahMua[$key]['tph_baris_blok'] = 0;
            $mtBuahMua[$key]['sampleJJG_total'] = 0;
            $mtBuahMua[$key]['total_mentah'] = 0;
            $mtBuahMua[$key]['total_perMentah'] = 0;
            $mtBuahMua[$key]['total_masak'] = 0;
            $mtBuahMua[$key]['total_perMasak'] = 0;
            $mtBuahMua[$key]['total_over'] = 0;
            $mtBuahMua[$key]['total_perOver'] = 0;
            $mtBuahMua[$key]['total_abnormal'] = 0;
            $mtBuahMua[$key]['total_perabnormal'] = 0;
            $mtBuahMua[$key]['total_jjgKosong'] = 0;
            $mtBuahMua[$key]['total_perKosongjjg'] = 0;
            $mtBuahMua[$key]['total_vcut'] = 0;
            $mtBuahMua[$key]['perVcut'] = 0;
            $mtBuahMua[$key]['jum_kr'] = 0;
            $mtBuahMua[$key]['kr_blok'] = 0;
            $mtBuahMua[$key]['persen_kr'] = 0;

            // skoring
            $mtBuahMua[$key]['skor_mentah'] = 0;
            $mtBuahMua[$key]['skor_masak'] = 0;
            $mtBuahMua[$key]['skor_over'] = 0;
            $mtBuahMua[$key]['skor_jjgKosong'] = 0;
            $mtBuahMua[$key]['skor_vcut'] = 0;
            $mtBuahMua[$key]['skor_abnormal'] = 0;;
            $mtBuahMua[$key]['skor_kr'] = 0;
            $mtBuahMua[$key]['TOTAL_SKOR'] = 0;
        }

        if ($sum_krWil != 0) {
            $total_krWil = round($sum_krWil / $jum_haWil, 2);
        } else {
            $total_krWil = 0;
        }

        if ($sum_bmtWil != 0) {
            $PerMthWil = round(($sum_bmtWil / ($sum_SamplejjgWil - $sum_abnorWil)) * 100, 2);
        } else {
            $PerMthWil = 0;
        }


        if ($sum_bmkWil != 0) {
            $PerMskWil = round(($sum_bmkWil / ($sum_SamplejjgWil - $sum_abnorWil)) * 100, 2);
        } else {
            $PerMskWil = 0;
        }
        if ($sum_overWil != 0) {
            $PerOverWil = round(($sum_overWil / ($sum_SamplejjgWil - $sum_abnorWil)) * 100, 2);
        } else {
            $PerOverWil = 0;
        }
        if ($sum_kosongjjgWil != 0) {
            $PerkosongjjgWil = round(($sum_kosongjjgWil / ($sum_SamplejjgWil - $sum_abnorWil)) * 100, 2);
        } else {
            $PerkosongjjgWil = 0;
        }
        if ($sum_vcutWil != 0) {
            $PerVcutWil = round(($sum_vcutWil / $sum_SamplejjgWil) * 100, 2);
        } else {
            $PerVcutWil = 0;
        }
        if ($sum_abnorWil != 0) {
            $PerAbrWil = round(($sum_abnorWil / $sum_SamplejjgWil) * 100, 2);
        } else {
            $PerAbrWil = 0;
        }
        $per_krWil = round($total_krWil * 100, 2);


        $nonZeroValuesBuah = array_filter([$sum_SamplejjgWil, $PerMthWil, $PerMskWil, $PerOverWil, $sum_abnorWil, $sum_kosongjjgWil, $sum_vcutWil]);

        if (!empty($nonZeroValuesBuah)) {
            $mtBuahMua['check_data'] = 'ada';
            // $mtBuahMua['skor_masak'] = $skor_masak =  skor_buah_masak_mb($PerMskWil);
            // $mtBuahMua['skor_over'] = $skor_over =  skor_buah_over_mb($PerOverWil);
            // $mtBuahMua['skor_jjgKosong'] = $skor_jjgKosong =  skor_jangkos_mb($PerkosongjjgWil);
            // $mtBuahMua['skor_vcut'] = $skor_vcut =  skor_vcut_mb($PerVcutWil);
            // $mtBuahMua['skor_kr'] = $skor_kr =  skor_abr_mb($per_krWil);
        } else {
            $mtBuahMua['check_data'] = 'kosong';
            // $mtBuahMua['skor_masak'] = $skor_masak = 0;
            // $mtBuahMua['skor_over'] = $skor_over = 0;
            // $mtBuahMua['skor_jjgKosong'] = $skor_jjgKosong = 0;
            // $mtBuahMua['skor_vcut'] = $skor_vcut =  0;
            // $mtBuahMua['skor_kr'] = $skor_kr = 0;
        }

        // $totalSkorWil = $skor_mentah + $skor_masak + $skor_over + $skor_jjgKosong + $skor_vcut + $skor_kr;
        $totalSkorWil =  skor_buah_mentah_mb($PerMthWil) + skor_buah_masak_mb($PerMskWil) + skor_buah_over_mb($PerOverWil) + skor_jangkos_mb($PerkosongjjgWil) + skor_vcut_mb($PerVcutWil) + skor_abr_mb($per_krWil);
        $mtBuahMua['tph_baris_blok'] = $jum_haWil;
        $mtBuahMua['sampleJJG_total'] = $sum_SamplejjgWil;
        $mtBuahMua['total_mentah'] = $sum_bmtWil;
        $mtBuahMua['total_perMentah'] = $PerMthWil;
        $mtBuahMua['total_masak'] = $sum_bmkWil;
        $mtBuahMua['total_perMasak'] = $PerMskWil;
        $mtBuahMua['total_over'] = $sum_overWil;
        $mtBuahMua['total_perOver'] = $PerOverWil;
        $mtBuahMua['total_abnormal'] = $sum_abnorWil;
        $mtBuahMua['total_perabnormal'] = $PerAbrWil;
        $mtBuahMua['total_jjgKosong'] = $sum_kosongjjgWil;
        $mtBuahMua['total_perKosongjjg'] = $PerkosongjjgWil;
        $mtBuahMua['total_vcut'] = $sum_vcutWil;
        $mtBuahMua['per_vcut'] = $PerVcutWil;
        $mtBuahMua['jum_kr'] = $sum_krWil;
        $mtBuahMua['kr_blok'] = $total_krWil;

        $mtBuahMua['persen_kr'] = $per_krWil;

        // skoring
        $mtBuahMua['skor_mentah'] = skor_buah_mentah_mb($PerMthWil);
        $mtBuahMua['skor_masak'] = skor_buah_masak_mb($PerMskWil);
        $mtBuahMua['skor_over'] = skor_buah_over_mb($PerOverWil);;
        $mtBuahMua['skor_jjgKosong'] = skor_jangkos_mb($PerkosongjjgWil);
        $mtBuahMua['skor_vcut'] = skor_vcut_mb($PerVcutWil);
        $mtBuahMua['skor_kr'] = skor_abr_mb($per_krWil);
        $mtBuahMua['TOTAL_SKOR'] = $totalSkorWil;

        // dd($mtBuahMua);
        $mtTransMua = array();
        $dataBLokWil = 0;
        $sum_btWil = 0;
        $sum_rstWil = 0;
        foreach ($ptmuaTrans as $key => $value) if (!empty($value)) {
            $dataBLokEst = 0;
            $sum_btEst = 0;
            $sum_rstEst = 0;
            foreach ($value as $key1 => $value1) if (!empty($value1)) {
                $sum_bt = 0;
                $sum_rst = 0;
                $brdPertph = 0;
                $buahPerTPH = 0;
                $totalSkor = 0;
                $dataBLok = 0;
                $listBlokPerAfd = array();
                foreach ($value1 as $key2 => $value2) if (!empty($value2)) {

                    // if (!in_array($value3['estate'] . ' ' . $value3['afdeling'] . ' ' . $value3['blok'] , $listBlokPerAfd)) {
                    $listBlokPerAfd[] = $value2['estate'] . ' ' . $value2['afdeling'] . ' ' . $value2['blok'];
                    // }
                    $dataBLok = count($listBlokPerAfd);
                    $sum_bt += $value2['bt'];
                    $sum_rst += $value2['rst'];
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


                $nonZeroValues = array_filter([$sum_bt, $sum_rst]);

                if (!empty($nonZeroValues)) {
                    $mtTransMua[$key][$key1]['check_data'] = 'ada';
                    // $mtTransMua[$key][$key1]['skor_buahPerTPH'] = $skor_buah =  skor_buah_tinggal($buahPerTPH);
                } else {
                    $mtTransMua[$key][$key1]['check_data'] = 'kosong';
                    // $mtTransMua[$key][$key1]['skor_buahPerTPH'] = $skor_buah = 0;
                }

                // $totalSkor = $skor_brd + $skor_buah ;

                $totalSkor =   skor_brd_tinggal($brdPertph) + skor_buah_tinggal($buahPerTPH);

                $mtTransMua[$key][$key1]['tph_sample'] = $dataBLok;
                $mtTransMua[$key][$key1]['total_brd'] = $sum_bt;
                $mtTransMua[$key][$key1]['total_brd/TPH'] = $brdPertph;
                $mtTransMua[$key][$key1]['total_buah'] = $sum_rst;
                $mtTransMua[$key][$key1]['total_buahPerTPH'] = $buahPerTPH;
                $mtTransMua[$key][$key1]['skor_brdPertph'] = skor_brd_tinggal($brdPertph);
                $mtTransMua[$key][$key1]['skor_buahPerTPH'] = skor_buah_tinggal($buahPerTPH);
                $mtTransMua[$key][$key1]['totalSkor'] = $totalSkor;

                //PERHITUNGAN PERESTATE
                $dataBLokEst += $dataBLok;
                $sum_btEst += $sum_bt;
                $sum_rstEst += $sum_rst;

                if ($dataBLokEst != 0) {
                    $brdPertphEst = round($sum_btEst / $dataBLokEst, 2);
                } else {
                    $brdPertphEst = 0;
                }
                if ($dataBLokEst != 0) {
                    $buahPerTPHEst = round($sum_rstEst / $dataBLokEst, 2);
                } else {
                    $buahPerTPHEst = 0;
                }

                // $totalSkorEst = skor_brd_tinggal($brdPertphEst) + skor_buah_tinggal($buahPerTPHEst);


            } else {
                $mtTransMua[$key][$key1]['tph_sample'] = 0;
                $mtTransMua[$key][$key1]['total_brd'] = 0;
                $mtTransMua[$key][$key1]['total_brd/TPH'] = 0;
                $mtTransMua[$key][$key1]['total_buah'] = 0;
                $mtTransMua[$key][$key1]['total_buahPerTPH'] = 0;
                $mtTransMua[$key][$key1]['skor_brdPertph'] = 0;
                $mtTransMua[$key][$key1]['skor_buahPerTPH'] = 0;
                $mtTransMua[$key][$key1]['totalSkor'] = 0;
            }

            $nonZeroValues = array_filter([$sum_btEst, $sum_rstEst]);

            if (!empty($nonZeroValues)) {
                $mtTransMua[$key]['check_data'] = 'ada';
                // $mtTransMua[$key]['skor_buahPerTPH'] = $skor_buah =  skor_buah_tinggal($buahPerTPHEst);
            } else {
                $mtTransMua[$key]['check_data'] = 'kosong';
                // $mtTransMua[$key]['skor_buahPerTPH'] = $skor_buah = 0;
            }

            // $totalSkorEst = $skor_brd + $skor_buah ;
            $totalSkorEst = skor_brd_tinggal($brdPertphEst) + skor_buah_tinggal($buahPerTPHEst);

            $mtTransMua[$key]['tph_sample'] = $dataBLokEst;
            $mtTransMua[$key]['total_brd'] = $sum_btEst;
            $mtTransMua[$key]['total_brd/TPH'] = $brdPertphEst;
            $mtTransMua[$key]['total_buah'] = $sum_rstEst;
            $mtTransMua[$key]['total_buahPerTPH'] = $buahPerTPHEst;
            $mtTransMua[$key]['skor_brdPertph'] = skor_brd_tinggal($brdPertphEst);
            $mtTransMua[$key]['skor_buahPerTPH'] = skor_buah_tinggal($buahPerTPHEst);
            $mtTransMua[$key]['totalSkor'] = $totalSkorEst;

            //perhitungan per wil
            $dataBLokWil += $dataBLokEst;
            $sum_btWil += $sum_btEst;
            $sum_rstWil += $sum_rstEst;

            if ($dataBLokWil != 0) {
                $brdPertphWil = round($sum_btWil / $dataBLokWil, 2);
            } else {
                $brdPertphWil = 0;
            }
            if ($dataBLokWil != 0) {
                $buahPerTPHWil = round($sum_rstWil / $dataBLokWil, 2);
            } else {
                $buahPerTPHWil = 0;
            }

            $totalSkorWil =   skor_brd_tinggal($brdPertphWil) + skor_buah_tinggal($buahPerTPHWil);
        } else {
            $mtTransMua[$key]['tph_sample'] = 0;
            $mtTransMua[$key]['total_brd'] = 0;
            $mtTransMua[$key]['total_brd/TPH'] = 0;
            $mtTransMua[$key]['total_buah'] = 0;
            $mtTransMua[$key]['total_buahPerTPH'] = 0;
            $mtTransMua[$key]['skor_brdPertph'] = 0;
            $mtTransMua[$key]['skor_buahPerTPH'] = 0;
            $mtTransMua[$key]['totalSkor'] = 0;
        }

        $nonZeroValuesTrans = array_filter([$sum_btWil, $sum_rstWil]);

        // if (!empty($nonZeroValuesTrans)) {
        //     $mtTransMua[$key]['skor_brdPertph'] = $skor_brd =  skor_brd_tinggal($brdPertphWil);
        //     $mtTransMua[$key]['skor_buahPerTPH'] = $skor_buah =  skor_buah_tinggal($buahPerTPHWil);
        // } else {
        //     $mtTransMua[$key]['skor_brdPertph'] = $skor_brd = 0;
        //     $mtTransMua[$key]['skor_buahPerTPH'] = $skor_buah = 0;
        // }

        // $totalSkorWil = $skor_brd + $skor_buah ;
        $mtTransMua['tph_sample'] = $dataBLokWil;
        $mtTransMua['total_brd'] = $sum_btWil;
        $mtTransMua['total_brd/TPH'] = $brdPertphWil;
        $mtTransMua['total_buah'] = $sum_rstWil;
        $mtTransMua['total_buahPerTPH'] = $buahPerTPHWil;
        $mtTransMua['skor_brdPertph'] =   skor_brd_tinggal($brdPertphWil);
        $mtTransMua['skor_buahPerTPH'] = skor_buah_tinggal($buahPerTPHWil);
        $mtTransMua['totalSkor'] = $totalSkorWil;

        $mtBuahMuaTotalSkor = $mtBuahMua['TOTAL_SKOR'];
        $mtAncakMuaSkorAkhir = $mtAncakMua['skor_akhir'];
        $mtTransMuaTotalSkor = $mtTransMua['totalSkor'];


        // dd($mtBuahMua, $mtAncakMua, $mtTransMua);

        $sumOfAllScores = $mtBuahMuaTotalSkor + $mtAncakMuaSkorAkhir + $mtTransMuaTotalSkor;
        // dd($mtTranstab1Wil[5]['BTE']);

        //menggabunugkan smua total skor di mutu ancak transport dan buah jadi satu array
        $RekapWIlTabel = array();
        // dd($mtancaktab1Wil[4]['MRE'], $mtTranstab1Wil[4]['MRE'],$mtBuahtab1Wil[4]['MRE']);
        // dd($mtancaktab1Wil);
        foreach ($mtancaktab1Wil as $key => $value) {
            foreach ($value as $key1 => $value1) if (is_array($value1)) {
                foreach ($value1 as $key2 => $value2) if (is_array($value2)) {
                    foreach ($mtBuahtab1Wil as $bh => $buah) {
                        foreach ($buah as $bh1 => $buah1) if (is_array($buah1)) {
                            foreach ($buah1 as $bh2 => $buah2) if (is_array($buah2)) {
                                foreach ($mtTranstab1Wil as $tr => $trans) {
                                    foreach ($trans as $tr1 => $trans1) if (is_array($trans1)) {
                                        foreach ($trans1 as $tr2 => $trans2) if (is_array($trans2))
                                            if (
                                                $bh == $key
                                                && $bh == $tr
                                                && $bh1 == $key1
                                                && $bh1 == $tr1
                                                && $bh2 == $key2
                                                && $bh2 == $tr2
                                            ) {
                                                // dd($trans2);
                                                if ($trans2['check_data'] == 'kosong' && $buah2['check_data'] === 'kosong' && $value2['check_data'] === 'kosong') {
                                                    $RekapWIlTabel[$key][$key1][$key2]['data'] = 'kosong';
                                                }

                                                if ($trans2['check_data'] == 'kosong' && $buah2['check_data'] === 'kosong' && $value2['check_data'] === 'kosong') {
                                                    $RekapWIlTabel[$key][$key1][$key2]['TotalSkor'] = 0;
                                                } else {
                                                    $RekapWIlTabel[$key][$key1][$key2]['TotalSkor'] = $value2['skor_akhir'] + $buah2['TOTAL_SKOR'] + $trans2['totalSkor'];
                                                }


                                                if ($trans2['check_data'] == 'kosong' && $buah2['check_data'] === 'kosong' && $value2['check_data'] === 'kosong') {
                                                    $RekapWIlTabel[$key][$key1]['TotalSkorEST'] = 0;
                                                } else {
                                                    $RekapWIlTabel[$key][$key1]['TotalSkorEST'] = $value1['skor_akhir'] + $buah1['TOTAL_SKOR'] + $trans1['totalSkor'];
                                                }


                                                if ($value1['check_data'] == 'kosong' && $buah1['check_data'] === 'kosong' && $trans1['check_data'] === 'kosong') {
                                                    $RekapWIlTabel[$key][$key1]['dataEst'] = 'kosong';
                                                }

                                                // dd($value,$buah,$trans);
                                                if ($trans['check_data'] == 'kosong' && $buah['check_data'] === 'kosong' && $value['check_data'] === 'kosong') {
                                                    $RekapWIlTabel[$key]['TotalSkorWil'] = 0;
                                                } else {
                                                    $RekapWIlTabel[$key]['TotalSkorWil'] = $value['skor_akhir'] + $buah['TOTAL_SKOR'] + $trans['totalSkor'];
                                                }



                                                // if ($key1 === 'SKE' || $key1 === 'LDE' || $key1 === 'SRE') {
                                                //     unset($RekapWIlTabel[$key][$key1]['OA']['TotalSkorEST']);
                                                // }
                                            }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }





        // dd($RekapWIlTabel);

        foreach ($RekapWIlTabel as $key1 => $estates)  if (is_array($estates)) {
            $sortedData = array();
            $sortedDataEst = array();

            foreach ($estates as $estateName => $data) {
                if (is_array($data)) {
                    $sortedDataEst[] = array(
                        'key1' => $key1,
                        'estateName' => $estateName,
                        'data' => $data
                    );
                    foreach ($data as $key2 => $scores) {
                        if (is_array($scores)) {
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
                return $b['scores']['TotalSkor'] - $a['scores']['TotalSkor'];
            });
            //mengurutkan untuk nilai estate
            usort($sortedDataEst, function ($a, $b) {
                return $b['data']['TotalSkorEST'] - $a['data']['TotalSkorEST'];
            });

            //menambahkan nilai rank ke dalam afd
            $rank = 1;
            foreach ($sortedData as $sortedEstate) {
                $RekapWIlTabel[$key1][$sortedEstate['estateName']][$sortedEstate['key2']]['rankAFD'] = $rank;
                $rank++;
            }

            //menambahkan nilai rank ke dalam estate
            $rank = 1;
            foreach ($sortedDataEst as $sortedest) {
                $RekapWIlTabel[$key1][$sortedest['estateName']]['rankEST'] = $rank;
                $rank++;
            }


            unset($sortedData, $sortedDataEst);
        }

        // dd($RekapWIlTabel);
        $RankingFinal = $RekapWIlTabel;

        $sortedArray = [];

        foreach ($RankingFinal as $key => $value) {
            $sortedArray[$key] = $value['TotalSkorWil'];
        }

        // arsort($sortedArray);

        $rank = 1;
        foreach ($sortedArray as $key => $value) {
            $RankingFinal[$key]['rankWil'] = $rank++;
        }

        // dd($RankingFinal);
        // updateKeyRecursive($RankingFinal, "KTE4", "KTE");

        //membagi tiap tiap wilayah ke 1 2 dan 3
        $Wil1 = $RankingFinal[1] ?? $RankingFinal[4] ?? $RankingFinal[7] ?? $RankingFinal[10];
        $Wil2 = $RankingFinal[2] ?? $RankingFinal[5] ?? $RankingFinal[8] ?? $RankingFinal[11];
        $Wil3 = $RankingFinal[3] ?? $RankingFinal[6] ?? $RankingFinal[8] ?? $RankingFinal[11];

        //buat tabel plasma 

        // dd($RankingFinal[1] && [2]);
        $GmWil1['skorwil_1'] = $Wil1['TotalSkorWil'];
        $GmWil2['skorwil_2'] = $Wil2['TotalSkorWil'];
        $GmWil3['skorwil_3'] = $Wil3['TotalSkorWil'];
        // foreach ($Wil1 as $key => $value) {
        // dd($GMperwil1);
        // penambahan GM dan WIlayah untuk di ambil namanya
        function processWils($Wil, $estValues)
        {
            $processedWil = array();
            $processedWil['Skor'] = $Wil['TotalSkorWil'];
            $processedWil['key'] = array_diff(array_keys($Wil), ['TotalSkorWil', 'rankWil']);
            $processedWil['est'] = '';
            $processedWil['afd'] = 'GM';


            //cek jika ada kunci yang sama
            $key_counts = array_count_values(array_keys($Wil));
            foreach ($key_counts as $key => $count) {
                if ($count > 1) {
                    //jika kunci ada buat array baru
                    $new_array = [
                        'Skor' => $Wil['TotalSkorWil'],
                        'key' => [$key],
                        'est' => '',
                        'afd' => 'GM',
                    ];
                    //tamhahkan array ke dalam procesedwil
                    $processedWil[] = $new_array;
                }
            }
            // dd($processedWil);
            //tambah key est bedasarkan key value
            if (array_key_exists($processedWil['key'][0], $estValues)) {
                $processedWil['est'] = $estValues[$processedWil['key'][0]];
            }

            return $processedWil;
        }

        $estValues = [
            'KNE' => 'WIL-I',
            'MRE' => 'WIL-IV',
            'BDE' => 'WIL-VII',
            'BKE' => 'WIL-II',
            'BTE' => 'WIL-V',
            'BHE' => 'WIL-VIII',
            'BGE' => 'WIL-III',
            'MLE' => 'WIL-VI',
            'SJE' => 'WIL-IX',
            'LM1' => 'WIL-X',
        ];

        $WilGMsatu = processWils($Wil1, $estValues);
        $wilGM2 = processWils($Wil2, $estValues);
        $wilGM3 = processWils($Wil3, $estValues);


        $GMarr = array();

        $GMarr['0'] = $WilGMsatu;
        $GMarr['1'] = $wilGM2;
        $GMarr['2'] = $wilGM3;


        //mencocokan afd dan est yang di ubah menjadi wil untuk mendapatkan nama
        $RHGM = array();
        foreach ($GMarr as $key => $value) if (is_array($value)) {
            // dd($key);
            // dd($value);
            $est = $value['est'];
            $skor = $value['Skor'];
            $EM = 'GM';
            $nama = '-';
            foreach ($queryAsisten as $value4) {
                if (is_array($value4) && $value4['est'] == $est && $value4['afd'] == $EM) {
                    $nama = $value4['nama'];
                    break;
                }
            }

            $RHGM[] = array(
                'est' => $est,
                'skor' => $skor,
                'EM' => $EM,
                'nama' => $nama
            );
        }

        $RHGM = array_values($RHGM);

        // dd($RHGM);

        //     foreach ($value as $key1 => $value2) {
        //         $GmWil1['skor'] = $value2['TotalSkorWil'];
        //     }
        // }


        // $FormatTabEst1 = array_values($FormatTabEst1);
        // dd($Wil1);
        //table untuk bagian EM estate wil1
        $FormatTabEst1 = array();
        foreach ($Wil1 as $key => $value) if (is_array($value)) {
            $inc = 0;
            $est = $key;
            $skor = $value['TotalSkorEST'];
            $EM = 'EM';
            $rank = $value['rankEST'];
            $nama = '-';
            $data = $value2['dataEst'] ?? 'ada';
            foreach ($queryAsisten as $key4 => $value4) {
                if (is_array($value4) && $value4['est'] == $est && $value4['afd'] == $EM) {
                    $nama = $value4['nama'];
                    break;
                }
            }
            $FormatTabEst1[] = array(
                'est' => $est,
                'skor' => $skor,
                'EM' => $EM,
                'rank' => $rank,
                'nama' => $nama,
                'data' => $data
            );
            $inc++;
        }

        $FormatTabEst1 = array_values($FormatTabEst1);
        // dd($FormatTabEst1);
        usort($FormatTabEst1, function ($a, $b) {
            return $a['rank'] - $b['rank'];
        });
        //table untuk bagian EM estate wil2
        $FormatTabEst2 = array();
        foreach ($Wil2 as $key => $value) if (is_array($value)) {
            $inc = 0;
            $est = $key;
            $skor = $value['TotalSkorEST'];
            $EM = 'EM';
            $rank = $value['rankEST'];
            $nama = '-';
            $data = $value2['dataEst'] ?? 'ada';
            foreach ($queryAsisten as $key4 => $value4) {
                if (is_array($value4) && $value4['est'] == $est && $value4['afd'] == $EM) {
                    $nama = $value4['nama'];
                    break;
                }
            }
            $FormatTabEst2[] = array(
                'est' => $est,
                'skor' => $skor,
                'EM' => $EM,
                'rank' => $rank,
                'nama' => $nama,
                'data' => $data
            );
            $inc++;
        }
        $FormatTabEst2 = array_values($FormatTabEst2);

        usort($FormatTabEst2, function ($a, $b) {
            return $a['rank'] - $b['rank'];
        });
        //table untuk bagian EM estate wil3
        $FormatTabEst3 = array();
        foreach ($Wil3 as $key => $value) if (is_array($value)) {
            $inc = 0;
            $est = $key;
            $skor = $value['TotalSkorEST'];
            $EM = 'EM';
            $rank = $value['rankEST'];
            $nama = '-';
            $data = $value2['dataEst'] ?? 'ada';
            foreach ($queryAsisten as $key4 => $value4) {
                if (is_array($value4) && $value4['est'] == $est && $value4['afd'] == $EM) {
                    $nama = $value4['nama'];
                    break;
                }
            }
            $FormatTabEst3[] = array(
                'est' => $est,
                'skor' => $skor,
                'EM' => $EM,
                'rank' => $rank,
                'nama' => $nama,
                'data' => $data
            );
            $inc++;
        }

        $FormatTabEst3 = array_values($FormatTabEst3);
        // dd($FormatTabEst1);
        usort($FormatTabEst3, function ($a, $b) {
            return $a['rank'] - $b['rank'];
        });

        // dd($Wil1);
        //bagian unutk afdeling
        $FormatTable1 = array();
        foreach ($Wil1 as $key => $value) {
            if (is_array($value)) {
                $inc = 0;
                foreach ($value as $key2 => $value2) {

                    // dd($value2);
                    if (is_array($value2)) {

                        // dd($value2);
                        $est = $key;
                        $afd = $key2;
                        $skor = $value2['TotalSkor'];
                        $EM = 'EM';
                        $rank = $value2['rankAFD'];
                        $data = $value2['data'] ?? 'ada';
                        $nama = '-';
                        foreach ($queryAsisten as $key4 => $value4) {
                            if (is_array($value4) && $value4['est'] == $est && $value4['afd'] == $afd) {
                                $nama = $value4['nama'];
                                break;
                            }
                        }
                        $FormatTable1[] = array(
                            'est' => $est,
                            'afd' => $afd,
                            'skor' => $skor,
                            'EM' => $EM,
                            'rank' => $rank,
                            'nama' => $nama,
                            'data' => $data
                        );
                        $inc++;
                    }
                }
            }
        }

        $FormatTable1 = array_values($FormatTable1);
        // dd($FormatTable1);
        // $FormatTable3 = array_values($FormatTable3);
        usort($FormatTable1, function ($a, $b) {
            return $a['rank'] - $b['rank'];
        });

        // dd($FormatTable1);
        //wil2
        $FormatTable2 = array();
        foreach ($Wil2 as $key => $value) {
            if (is_array($value)) {
                $inc = 0;
                foreach ($value as $key2 => $value2) {
                    if (is_array($value2)) {
                        $est = $key;
                        $afd = $key2;
                        $skor = $value2['TotalSkor'];
                        $EM = 'EM';
                        $rank = $value2['rankAFD'];
                        $data = $value2['data'] ?? 'ada';
                        $nama = '-';
                        foreach ($queryAsisten as $key4 => $value4) {
                            if (is_array($value4) && $value4['est'] == $est && $value4['afd'] == $afd) {
                                $nama = $value4['nama'];
                                break;
                            }
                        }
                        $FormatTable2[] = array(
                            'est' => $est,
                            'afd' => $afd,
                            'skor' => $skor,
                            'EM' => $EM,
                            'rank' => $rank,
                            'nama' => $nama,
                            'data' => $data
                        );
                        $inc++;
                    }
                }
            }
        }

        $FormatTable2 = array_values($FormatTable2);
        usort($FormatTable2, function ($a, $b) {
            return $a['rank'] - $b['rank'];
        });

        //wil3
        $FormatTable3 = array();
        foreach ($Wil3 as $key => $value) {
            if (is_array($value)) {
                $inc = 0;
                foreach ($value as $key2 => $value2) {
                    if (is_array($value2)) {
                        $est = $key;
                        $afd = $key2;
                        $skor = $value2['TotalSkor'];
                        $EM = 'EM';
                        $rank = $value2['rankAFD'];
                        $nama = '-';
                        $data = $value2['data'] ?? 'ada';
                        foreach ($queryAsisten as $key4 => $value4) {
                            if (is_array($value4) && $value4['est'] == $est && $value4['afd'] == $afd) {
                                $nama = $value4['nama'];
                                break;
                            }
                        }
                        $FormatTable3[] = array(
                            'est' => $est,
                            'afd' => $afd,
                            'skor' => $skor,
                            'EM' => $EM,
                            'rank' => $rank,
                            'nama' => $nama,
                            'data' => $data
                        );
                        $inc++;
                    }
                }
            }
        }

        $FormatTable3 = array_values($FormatTable3);
        usort($FormatTable3, function ($a, $b) {
            return $a['rank'] - $b['rank'];
        });


        // dd($FormatTable1);
        // dd($mtancaktab1Wil);
        // dd($DataTable1);
        // $queryEsta = DB::connection('mysql2')->table('estate')
        //     ->where('est', '!=', 'CWS')
        //     ->whereIn('wil', [1, 2, 3])->pluck('est');
        // $queryEsta = json_decode($queryEsta, true);

        $queryEsta = DB::connection('mysql2')->table('estate')
            ->select('estate.*')
            ->whereNotIn('estate.est', ['CWS1', 'CWS2', 'CWS3'])
            ->where('estate.emp', '!=', 1)
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->where('wil.regional', $RegData)
            // ->where('wil.regional', '3')
            ->pluck('est');

        // Convert the result into an array with numeric keys
        $queryEsta = array_values(json_decode($queryEsta, true));

        function movePlasmaAfterUpeS($array)
        {
            // Find the index of "UPE"
            $indexUpe = array_search("UPE", $array);

            // Find the index of "PLASMA"
            $indexPlasma = array_search("Plasma1", $array);

            // Move "PLASMA" after "UPE"
            if ($indexUpe !== false && $indexPlasma !== false && $indexPlasma < $indexUpe) {
                $plasma = $array[$indexPlasma];
                array_splice($array, $indexPlasma, 1);
                array_splice($array, $indexUpe, 0, [$plasma]);
            }

            return $array;
        }

        // Usage:

        $queryEsta = movePlasmaAfterUpeS($queryEsta);
        // dd($queryEsta);



        // Display the updated array
        // dd($reyEst);



        // dd($mtancaktab1Wil);
        $chartBTT = array();
        foreach ($mtancaktab1Wil as $key => $value) {
            foreach ($value as $key2 => $value2) {
                if (is_array($value2)) {
                    $chartBTT[$key2] = $value2['brd/jjgest'];
                }
            }
        }
        $array = $chartBTT;

        // Find the index of "UPE"
        $index = array_search("UPE", array_keys($array));

        // Move "PLASMA" after "UPE"
        if ($index !== false && isset($array["Plasma1"])) {
            $plasma = $array["Plasma1"];
            unset($array["Plasma1"]);
            $array = array_slice($array, 0, $index + 1, true) + ["Plasma1" => $plasma] + array_slice($array, $index + 1, null, true);
        }

        // Find the index of "UPE"

        // $arrayEst now contains the modified array


        // dd($chartBTT);
        //     "brd/jjgwil" => "0.24"
        // "buah/jjgwil" => "0.00"
        // // dd($RankingFinal, $chartBTT);
        $chartBuahTT = array();
        foreach ($mtancaktab1Wil as $key => $value) {
            foreach ($value as $key2 => $value2) if (is_array($value2)) {

                $chartBuahTT[$key2] = $value2['buah/jjg'];
            }
        }
        $arrBuahBTT = $chartBuahTT;


        // Find the index of "UPE"
        $index = array_search("UPE", array_keys($arrBuahBTT));

        // Move "PLASMA" after "UPE"
        if ($index !== false && isset($arrBuahBTT["Plasma1"])) {
            $plasma = $arrBuahBTT["Plasma1"];
            unset($arrBuahBTT["Plasma1"]);
            $arrBuahBTT = array_slice($arrBuahBTT, 0, $index + 1, true) + ["Plasma1" => $plasma] + array_slice($arrBuahBTT, $index + 1, null, true);
        }


        $keysToRemove = ["SRE", "LDE", "SKE"];
        $filteredBuah = [];

        foreach ($arrBuahBTT as $key => $value) {
            if (!in_array($key, $keysToRemove)) {
                $filteredBuah[$key] = $value;
            }
        }

        // dd($filteredBuah);

        $chartPerwil = array();
        foreach ($mtancaktab1Wil as $key => $value) {
            // $sum = 0;
            // // dd($value);

            // if ($value['ha_sample'] != 0) {
            //     $sum = $value['brd/jjgwil'] / $value['ha_sample'];
            // } else {
            //     $sum = 0;
            // }

            // $chartPerwil[] = round($sum, 2);


            $chartPerwil[$key] = $value['brd/jjgwil'];
        }
        // dd($mtancaktab1Wil);
        $buahPerwil = array();
        foreach ($mtancaktab1Wil as $key => $value) {
            // $sum = 0;
            // // dd($value);

            // if ($value['ha_sample'] != 0) {
            //     $sum = $value['buah/jjgwil'] / $value['ha_sample'];
            // } else {
            //     $sum = 0;
            // }

            // $buahPerwil[] = round($sum, 2);
            $buahPerwil[$key] =  $value['buah/jjgwil'];
        }

        //table perbulan 
        // dd($queryEstePla);

        //untuk plasma tabel
        //transport
        $defPLAtrans = array();
        foreach ($queryEstePla as $est) {
            // dd($est);
            foreach ($queryAfd as $afd) {
                // dd($afd);
                if ($est['est'] == $afd['est']) {
                    $defPLAtrans[$est['est']][$afd['nama']]['null'] = 0;
                }
            }
        }
        $mutuTransPLAmerge = array();
        foreach ($defPLAtrans as $estKey => $afdArray) {
            foreach ($afdArray as $afdKey => $afdValue) {
                if (array_key_exists($estKey, $dataMTTrans)) {
                    if (array_key_exists($afdKey, $dataMTTrans[$estKey])) {
                        if (!empty($dataMTTrans[$estKey][$afdKey])) {
                            $mutuTransPLAmerge[$estKey][$afdKey] = $dataMTTrans[$estKey][$afdKey];
                        } else {
                            $mutuTransPLAmerge[$estKey][$afdKey] = $afdValue;
                        }
                    } else {
                        $mutuTransPLAmerge[$estKey][$afdKey] = $afdValue;
                    }
                } else {
                    $mutuTransPLAmerge[$estKey][$afdKey] = $afdValue;
                }
            }
        }
        //buah
        $defPlaBuah = array();
        foreach ($queryEstePla as $est) {
            // dd($est);
            foreach ($queryAfd as $afd) {
                // dd($afd);
                if ($est['est'] == $afd['est']) {
                    $defPlaBuah[$est['est']][$afd['nama']]['null'] = 0;
                }
            }
        }
        // dd($mutuTransPLAmerge);
        $mtBuahPlasma = array();
        foreach ($defPlaBuah as $estKey => $afdArray) {
            foreach ($afdArray as $afdKey => $afdValue) {
                if (array_key_exists($estKey, $dataMTBuah)) {
                    if (array_key_exists($afdKey, $dataMTBuah[$estKey])) {
                        if (!empty($dataMTBuah[$estKey][$afdKey])) {
                            $mtBuahPlasma[$estKey][$afdKey] = $dataMTBuah[$estKey][$afdKey];
                        } else {
                            $mtBuahPlasma[$estKey][$afdKey] = $afdValue;
                        }
                    } else {
                        $mtBuahPlasma[$estKey][$afdKey] = $afdValue;
                    }
                } else {
                    $mtBuahPlasma[$estKey][$afdKey] = $afdValue;
                }
            }
        }
        // dd($mtBuahPlasma);
        //ancak
        $defAncakPlasma = array();
        foreach ($queryEstePla as $est) {
            // dd($est);
            foreach ($queryAfd as $afd) {
                // dd($afd);
                if ($est['est'] == $afd['est']) {
                    $defAncakPlasma[$est['est']][$afd['nama']]['null'] = 0;
                }
            }
        }
        $mtAncakPlasma = array();
        foreach ($defPlaBuah as $estKey => $afdArray) {
            foreach ($afdArray as $afdKey => $afdValue) {
                if (array_key_exists($estKey, $dataPerBulan)) {
                    if (array_key_exists($afdKey, $dataPerBulan[$estKey])) {
                        if (!empty($dataPerBulan[$estKey][$afdKey])) {
                            $mtAncakPlasma[$estKey][$afdKey] = $dataPerBulan[$estKey][$afdKey];
                        } else {
                            $mtAncakPlasma[$estKey][$afdKey] = $afdValue;
                        }
                    } else {
                        $mtAncakPlasma[$estKey][$afdKey] = $afdValue;
                    }
                } else {
                    $mtAncakPlasma[$estKey][$afdKey] = $afdValue;
                }
            }
        }
        // dd($mtAncakPlasma);
        //perhitungan mutu transport
        $mtPLA = array();
        foreach ($mutuTransPLAmerge as $key => $value) if (!empty($value)) {
            $dataBLokEst = 0;
            $sum_btEst = 0;
            $sum_rstEst = 0;
            foreach ($value as $key1 => $value1) if (!empty($value1)) {
                $sum_bt = 0;
                $sum_rst = 0;
                $brdPertph = 0;
                $buahPerTPH = 0;
                $totalSkor = 0;
                $dataBLok = 0;
                $listBlokPerAfd = array();
                foreach ($value1 as $key2 => $value2) if (is_array($value2)) {

                    // if (!in_array($value3['estate'] . ' ' . $value3['afdeling'] . ' ' . $value3['blok'] , $listBlokPerAfd)) {
                    $listBlokPerAfd[] = $value2['estate'] . ' ' . $value2['afdeling'] . ' ' . $value2['blok'];
                    // }
                    $dataBLok = count($listBlokPerAfd);
                    $sum_bt += $value2['bt'];
                    $sum_rst += $value2['rst'];
                }


                $tot_sample = 0;  // Define the variable outside of the foreach loop

                foreach ($transNewdata as $keys => $trans) {
                    if ($keys == $key1) {
                        foreach ($trans as $keys2 => $trans2) {
                            if ($keys2 == $key2) {
                                $mtPLA[$key][$key1]['tph_sampleNew'] = $trans2['total_Sample'];
                                $tot_sample = $trans2['total_Sample'];
                            }
                        }
                    }
                }

                if ($RegData == '2' || $RegData == 2) {
                    if ($dataBLok != 0) {
                        $brdPertph = $tot_sample !== 0 ? round($sum_bt / $tot_sample, 2) : 0;
                    } else {
                        $brdPertph = 0;
                    }
                } else {
                    if ($dataBLok != 0) {
                        $brdPertph = $dataBLok !== 0 ? round($sum_bt / $dataBLok, 2) : 0;
                    } else {
                        $brdPertph = 0;
                    }
                }

                if ($RegData == '2' || $RegData == 2) {
                    if ($dataBLok != 0) {
                        $buahPerTPH = $tot_sample !== 0 ? round($sum_rst / $tot_sample, 2) : 0;
                    } else {
                        $buahPerTPH = 0;
                    }
                } else {
                    if ($dataBLok != 0) {
                        $buahPerTPH = $dataBLok !== 0 ? round($sum_rst / $dataBLok, 2) : 0;
                    } else {
                        $buahPerTPH = 0;
                    }
                }
                // if ($dataBLok != 0) {
                //     $brdPertph = round($sum_bt / $dataBLok, 2);
                // } else {
                //     $brdPertph = 0;
                // }
                // if ($dataBLok != 0) {
                //     $buahPerTPH = round($sum_rst / $dataBLok, 2);
                // } else {
                //     $buahPerTPH = 0;
                // }

                $nonZeroValues = array_filter([$sum_bt, $sum_rst]);

                if (!empty($nonZeroValues)) {
                    $mtPLA[$key][$key1]['check_data'] = 'ada';
                    // $mtPLA[$key][$key1]['skor_buahPerTPH'] = $skor_buah = skor_buah_tinggal($buahPerTPH);

                } else {
                    $mtPLA[$key][$key1]['check_data'] = 'kosong';
                    // $mtPLA[$key][$key1]['skor_buahPerTPH'] = $skor_buah = 0;

                }

                // $totalSkor = $skor_brd + $skor_buah ;

                $totalSkor =   skor_brd_tinggal($brdPertph) + skor_buah_tinggal($buahPerTPH);

                $mtPLA[$key][$key1]['tph_sample'] = $dataBLok;
                $mtPLA[$key][$key1]['total_brd'] = $sum_bt;
                $mtPLA[$key][$key1]['total_brd/TPH'] = $brdPertph;
                $mtPLA[$key][$key1]['total_buah'] = $sum_rst;
                $mtPLA[$key][$key1]['total_buahPerTPH'] = $buahPerTPH;
                $mtPLA[$key][$key1]['skor_brdPertph'] = skor_brd_tinggal($brdPertph);
                $mtPLA[$key][$key1]['skor_buahPerTPH'] = skor_buah_tinggal($buahPerTPH);
                $mtPLA[$key][$key1]['skorWil'] = $totalSkor;

                //PERHITUNGAN PERESTATE
                if ($RegData == '2' || $RegData == 2) {
                    $dataBLokEst += $tot_sample;
                } else {
                    $dataBLokEst += $dataBLok;
                }

                // $dataBLokEst += $dataBLok;
                $sum_btEst += $sum_bt;
                $sum_rstEst += $sum_rst;

                if ($dataBLokEst != 0) {
                    $brdPertphEst = round($sum_btEst / $dataBLokEst, 2);
                } else {
                    $brdPertphEst = 0;
                }
                if ($dataBLokEst != 0) {
                    $buahPerTPHEst = round($sum_rstEst / $dataBLokEst, 2);
                } else {
                    $buahPerTPHEst = 0;
                }

                $nonZeroValues = array_filter([$sum_btEst, $sum_rstEst]);

                if (!empty($nonZeroValues)) {
                    $mtPLA[$key]['check_data'] = 'ada';
                    // $mtPLA[$key]['skor_buahPerTPH'] = $skor_buah = skor_buah_tinggal($buahPerTPHEst);

                } else {
                    $mtPLA[$key]['check_data'] = 'kosong';
                    // $mtPLA[$key]['skor_buahPerTPH'] = $skor_buah = 0;

                }

                // $totalSkorEst = $skor_brd + $skor_buah ;
                $totalSkorEst = skor_brd_tinggal($brdPertphEst) + skor_buah_tinggal($buahPerTPHEst);
            } else {
                $mtPLA[$key][$key1]['tph_sample'] = 0;
                $mtPLA[$key][$key1]['total_brd'] = 0;
                $mtPLA[$key][$key1]['total_brd/TPH'] = 0;
                $mtPLA[$key][$key1]['total_buah'] = 0;
                $mtPLA[$key][$key1]['total_buahPerTPH'] = 0;
                $mtPLA[$key][$key1]['skor_brdPertph'] = 0;
                $mtPLA[$key][$key1]['skor_buahPerTPH'] = 0;
                $mtPLA[$key][$key1]['skorWil'] = 0;
            }
            $mtPLA[$key]['tph_sample'] = $dataBLokEst;
            $mtPLA[$key]['total_brd'] = $sum_btEst;
            $mtPLA[$key]['total_brd/TPH'] = $brdPertphEst;
            $mtPLA[$key]['total_buah'] = $sum_rstEst;
            $mtPLA[$key]['total_buahPerTPH'] = $buahPerTPHEst;
            // $mtPLA[$key]['skor_brdPertph'] = skor_brd_tinggal($brdPertphEst);
            // $mtPLA[$key]['skor_buahPerTPH'] = skor_buah_tinggal($buahPerTPHEst);
            $mtPLA[$key]['skorPlasma'] = $totalSkorEst;
        } else {
            $mtPLA[$key]['tph_sample'] = 0;
            $mtPLA[$key]['total_brd'] = 0;
            $mtPLA[$key]['total_brd/TPH'] = 0;
            $mtPLA[$key]['total_buah'] = 0;
            $mtPLA[$key]['total_buahPerTPH'] = 0;
            $mtPLA[$key]['skor_brdPertph'] = 0;
            $mtPLA[$key]['skor_buahPerTPH'] = 0;
            $mtPLA[$key]['skorPlasma'] = 0;
        }


        // dd($mtPLA);
        //perhitungan mutu buah
        $mtPLABuah = array();

        foreach ($mtBuahPlasma as $key => $value) if (is_array($value)) {
            $jum_haEst  = 0;
            $sum_SamplejjgEst = 0;
            $sum_bmtEst = 0;
            $sum_bmkEst = 0;
            $sum_overEst = 0;
            $sum_abnorEst = 0;
            $sum_kosongjjgEst = 0;
            $sum_vcutEst = 0;
            $sum_krEst = 0;
            foreach ($value as $key1 => $value1) if (is_array($value1)) {
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
                $listBlokPerAfd = [];
                foreach ($value1 as $key2 => $value2)  if (is_array($value2)) {

                    // if (!in_array($value3['estate'] . ' ' . $value3['afdeling'] . ' ' . $value3['blok'] . ' ' . $value3['tph_baris'], $listBlokPerAfd)) {
                    $listBlokPerAfd[] = $value2['estate'] . ' ' . $value2['afdeling'] . ' ' . $value2['blok'] . ' ' . $value2['tph_baris'];
                    // }
                    $dtBlok = count($listBlokPerAfd);
                    $sum_bmt += $value2['bmt'];
                    $sum_bmk += $value2['bmk'];
                    $sum_over += $value2['overripe'];
                    $sum_kosongjjg += $value2['empty_bunch'];
                    $sum_vcut += $value2['vcut'];
                    $sum_kr += $value2['alas_br'];
                    $sum_Samplejjg += $value2['jumlah_jjg'];
                    $sum_abnor += $value2['abnormal'];
                }
                $jml_mth = ($sum_bmt + $sum_bmk);
                $jml_mtg = $sum_Samplejjg - ($jml_mth + $sum_over + $sum_kosongjjg + $sum_abnor);
                // $dataBLok = count($combination_counts);
                if ($sum_kr != 0) {
                    $total_kr = round($sum_kr / $dtBlok, 2);
                } else {
                    $total_kr = 0;
                }


                $per_kr = round($total_kr * 100, 2);
                if ($jml_mth != 0) {
                    $PerMth = round(($jml_mth / ($sum_Samplejjg - $sum_abnor)) * 100, 2);
                } else {
                    $PerMth = 0;
                }
                if ($jml_mtg != 0) {
                    $PerMsk = round(($jml_mtg / ($sum_Samplejjg - $sum_abnor)) * 100, 2);
                } else {
                    $PerMsk = 0;
                }
                if ($sum_over != 0) {
                    $PerOver = round(($sum_over / ($sum_Samplejjg - $sum_abnor)) * 100, 2);
                } else {
                    $PerOver = 0;
                }
                if ($sum_kosongjjg != 0) {
                    $Perkosongjjg = round(($sum_kosongjjg / ($sum_Samplejjg - $sum_abnor)) * 100, 2);
                } else {
                    $Perkosongjjg = 0;
                }
                if ($sum_vcut != 0) {
                    $PerVcut = round(($sum_vcut / $sum_Samplejjg) * 100, 2);
                } else {
                    $PerVcut = 0;
                }

                if ($sum_abnor != 0) {
                    $PerAbr = round(($sum_abnor / $sum_Samplejjg) * 100, 2);
                } else {
                    $PerAbr = 0;
                }

                $nonZeroValues = array_filter([$sum_Samplejjg, $jml_mth, $jml_mtg, $sum_over, $sum_abnor, $sum_kosongjjg, $sum_vcut]);

                if (!empty($nonZeroValues)) {
                    $mtPLABuah[$key][$key1]['check_data'] = 'ada';
                    // $mtPLABuah[$key][$key1]['skor_masak'] = $skor_masak =  skor_buah_masak_mb($PerMsk);
                    // $mtPLABuah[$key][$key1]['skor_over'] = $skor_over =  skor_buah_over_mb($PerOver);
                    // $mtPLABuah[$key][$key1]['skor_jjgKosong'] = $skor_jjgKosong =  skor_jangkos_mb($Perkosongjjg);
                    // $mtPLABuah[$key][$key1]['skor_vcut'] = $skor_vcut =  skor_vcut_mb($PerVcut);
                    // $mtPLABuah[$key][$key1]['skor_kr'] = $skor_kr =  skor_abr_mb($per_kr);
                } else {
                    $mtPLABuah[$key][$key1]['check_data'] = 'kosong';
                    // $mtPLABuah[$key][$key1]['skor_masak'] = $skor_masak = 0;
                    // $mtPLABuah[$key][$key1]['skor_over'] = $skor_over = 0;
                    // $mtPLABuah[$key][$key1]['skor_jjgKosong'] = $skor_jjgKosong = 0;
                    // $mtPLABuah[$key][$key1]['skor_vcut'] = $skor_vcut =  0;
                    // $mtPLABuah[$key][$key1]['skor_kr'] = $skor_kr = 0;
                }

                // $totalSkor = $skor_mentah + $skor_masak + $skor_over + $skor_jjgKosong + $skor_vcut + $skor_kr;


                $totalSkor =  skor_buah_mentah_mb($PerMth) + skor_buah_masak_mb($PerMsk) + skor_buah_over_mb($PerOver) + skor_vcut_mb($PerVcut) + skor_jangkos_mb($Perkosongjjg) + skor_abr_mb($per_kr);
                $mtPLABuah[$key][$key1]['tph_baris_blok'] = $dtBlok;
                $mtPLABuah[$key][$key1]['sampleJJG_total'] = $sum_Samplejjg;
                $mtPLABuah[$key][$key1]['total_mentah'] = $jml_mth;
                $mtPLABuah[$key][$key1]['total_perMentah'] = $PerMth;
                $mtPLABuah[$key][$key1]['total_masak'] = $jml_mtg;
                $mtPLABuah[$key][$key1]['total_perMasak'] = $PerMsk;
                $mtPLABuah[$key][$key1]['total_over'] = $sum_over;
                $mtPLABuah[$key][$key1]['total_perOver'] = $PerOver;
                $mtPLABuah[$key][$key1]['total_abnormal'] = $sum_abnor;
                $mtPLABuah[$key][$key1]['perAbnormal'] = $PerAbr;
                $mtPLABuah[$key][$key1]['total_jjgKosong'] = $sum_kosongjjg;
                $mtPLABuah[$key][$key1]['total_perKosongjjg'] = $Perkosongjjg;
                $mtPLABuah[$key][$key1]['total_vcut'] = $sum_vcut;
                $mtPLABuah[$key][$key1]['perVcut'] = $PerVcut;

                $mtPLABuah[$key][$key1]['jum_kr'] = $sum_kr;
                $mtPLABuah[$key][$key1]['total_kr'] = $total_kr;
                $mtPLABuah[$key][$key1]['persen_kr'] = $per_kr;

                // skoring
                $mtPLABuah[$key][$key1]['skor_mentah'] = skor_buah_mentah_mb($PerMth);
                $mtPLABuah[$key][$key1]['skor_masak'] = skor_buah_masak_mb($PerMsk);
                $mtPLABuah[$key][$key1]['skor_over'] = skor_buah_over_mb($PerOver);
                $mtPLABuah[$key][$key1]['skor_jjgKosong'] = skor_jangkos_mb($Perkosongjjg);
                $mtPLABuah[$key][$key1]['skor_vcut'] = skor_vcut_mb($PerVcut);

                $mtPLABuah[$key][$key1]['skor_kr'] = skor_abr_mb($per_kr);
                $mtPLABuah[$key][$key1]['skorWil'] = $totalSkor;

                //perhitungan estate
                $jum_haEst += $dtBlok;
                $sum_SamplejjgEst += $sum_Samplejjg;
                $sum_bmtEst += $jml_mth;
                $sum_bmkEst += $jml_mtg;
                $sum_overEst += $sum_over;
                $sum_abnorEst += $sum_abnor;
                $sum_kosongjjgEst += $sum_kosongjjg;
                $sum_vcutEst += $sum_vcut;
                $sum_krEst += $sum_kr;
            } else {
                $mtPLABuah[$key][$key1]['tph_baris_blok'] = 0;
                $mtPLABuah[$key][$key1]['sampleJJG_total'] = 0;
                $mtPLABuah[$key][$key1]['total_mentah'] = 0;
                $mtPLABuah[$key][$key1]['total_perMentah'] = 0;
                $mtPLABuah[$key][$key1]['total_masak'] = 0;
                $mtPLABuah[$key][$key1]['total_perMasak'] = 0;
                $mtPLABuah[$key][$key1]['total_over'] = 0;
                $mtPLABuah[$key][$key1]['total_perOver'] = 0;
                $mtPLABuah[$key][$key1]['total_abnormal'] = 0;
                $mtPLABuah[$key][$key1]['perAbnormal'] = 0;
                $mtPLABuah[$key][$key1]['total_jjgKosong'] = 0;
                $mtPLABuah[$key][$key1]['total_perKosongjjg'] = 0;
                $mtPLABuah[$key][$key1]['total_vcut'] = 0;
                $mtPLABuah[$key][$key1]['perVcut'] = 0;

                $mtPLABuah[$key][$key1]['jum_kr'] = 0;
                $mtPLABuah[$key][$key1]['total_kr'] = 0;
                $mtPLABuah[$key][$key1]['persen_kr'] = 0;

                // skoring
                $mtPLABuah[$key][$key1]['skor_mentah'] = 0;
                $mtPLABuah[$key][$key1]['skor_masak'] = 0;
                $mtPLABuah[$key][$key1]['skor_over'] = 0;
                $mtPLABuah[$key][$key1]['skor_jjgKosong'] = 0;
                $mtPLABuah[$key][$key1]['skor_vcut'] = 0;
                $mtPLABuah[$key][$key1]['skor_abnormal'] = 0;;
                $mtPLABuah[$key][$key1]['skor_kr'] = 0;
                $mtPLABuah[$key][$key1]['skorWil'] = 0;
            }

            if ($sum_krEst != 0) {
                $total_krEst = round($sum_krEst / $jum_haEst, 2);
            } else {
                $total_krEst = 0;
            }
            // if ($sum_kr != 0) {
            //     $total_kr = round($sum_kr / $dataBLok, 2);
            // } else {
            //     $total_kr = 0;
            // }

            if ($sum_bmtEst != 0) {
                $PerMthEst = round(($sum_bmtEst / ($sum_SamplejjgEst - $sum_abnorEst)) * 100, 2);
            } else {
                $PerMthEst = 0;
            }

            if ($sum_bmkEst != 0) {
                $PerMskEst = round(($sum_bmkEst / ($sum_SamplejjgEst - $sum_abnorEst)) * 100, 2);
            } else {
                $PerMskEst = 0;
            }

            if ($sum_overEst != 0) {
                $PerOverEst = round(($sum_overEst / ($sum_SamplejjgEst - $sum_abnorEst)) * 100, 2);
            } else {
                $PerOverEst = 0;
            }
            if ($sum_kosongjjgEst != 0) {
                $PerkosongjjgEst = round(($sum_kosongjjgEst / ($sum_SamplejjgEst - $sum_abnorEst)) * 100, 2);
            } else {
                $PerkosongjjgEst = 0;
            }
            if ($sum_vcutEst != 0) {
                $PerVcutest = round(($sum_vcutEst / $sum_SamplejjgEst) * 100, 2);
            } else {
                $PerVcutest = 0;
            }
            if ($sum_abnorEst != 0) {
                $PerAbrest = round(($sum_abnorEst / $sum_SamplejjgEst) * 100, 2);
            } else {
                $PerAbrest = 0;
            }
            // $per_kr = round($sum_kr * 100);
            $per_krEst = round($total_krEst * 100, 2);

            $nonZeroValues = array_filter([$sum_SamplejjgEst, $sum_bmtEst, $sum_bmkEst, $sum_overEst, $sum_abnorEst, $sum_kosongjjgEst, $sum_vcutEst]);

            if (!empty($nonZeroValues)) {
                $mtPLABuah[$key]['check_data'] = 'ada';
                // $mtPLABuah[$key]['skor_masak'] = $skor_masak =  skor_buah_masak_mb($PerMskEst);
                // $mtPLABuah[$key]['skor_over'] = $skor_over =  skor_buah_over_mb($PerOverEst);
                // $mtPLABuah[$key]['skor_jjgKosong'] = $skor_jjgKosong =  skor_jangkos_mb($PerkosongjjgEst);
                // $mtPLABuah[$key]['skor_vcut'] = $skor_vcut =  skor_vcut_mb($PerVcutest);
                // $mtPLABuah[$key]['skor_kr'] = $skor_kr =  skor_abr_mb($per_krEst);
            } else {
                $mtPLABuah[$key]['check_data'] = 'kosong';
                // $mtPLABuah[$key]['skor_masak'] = $skor_masak = 0;
                // $mtPLABuah[$key]['skor_over'] = $skor_over = 0;
                // $mtPLABuah[$key]['skor_jjgKosong'] = $skor_jjgKosong = 0;
                // $mtPLABuah[$key]['skor_vcut'] = $skor_vcut =  0;
                // $mtPLABuah[$key]['skor_kr'] = $skor_kr = 0;
            }

            // $totalSkorEst = $skor_mentah + $skor_masak + $skor_over + $skor_jjgKosong + $skor_vcut + $skor_kr;


            $totalSkorEst =   skor_buah_mentah_mb($PerMthEst) + skor_buah_masak_mb($PerMskEst) + skor_buah_over_mb($PerOverEst) + skor_jangkos_mb($PerkosongjjgEst) + skor_vcut_mb($PerVcutest) + skor_abr_mb($per_krEst);
            $mtPLABuah[$key]['tph_baris_blok'] = $jum_haEst;
            $mtPLABuah[$key]['sampleJJG_total'] = $sum_SamplejjgEst;
            $mtPLABuah[$key]['total_mentah'] = $sum_bmtEst;
            $mtPLABuah[$key]['total_perMentah'] = $PerMthEst;
            $mtPLABuah[$key]['total_masak'] = $sum_bmkEst;
            $mtPLABuah[$key]['total_perMasak'] = $PerMskEst;
            $mtPLABuah[$key]['total_over'] = $sum_overEst;
            $mtPLABuah[$key]['total_perOver'] = $PerOverEst;
            $mtPLABuah[$key]['total_abnormal'] = $sum_abnorEst;
            $mtPLABuah[$key]['total_perabnormal'] = $PerAbrest;
            $mtPLABuah[$key]['total_jjgKosong'] = $sum_kosongjjgEst;
            $mtPLABuah[$key]['total_perKosongjjg'] = $PerkosongjjgEst;
            $mtPLABuah[$key]['total_vcut'] = $sum_vcutEst;
            $mtPLABuah[$key]['perVcut'] = $PerVcutest;
            $mtPLABuah[$key]['jum_kr'] = $sum_krEst;
            $mtPLABuah[$key]['kr_blok'] = $total_krEst;

            $mtPLABuah[$key]['persen_kr'] = $per_krEst;

            // skoring
            $mtPLABuah[$key]['skor_mentah'] =  skor_buah_mentah_mb($PerMthEst);
            $mtPLABuah[$key]['skor_masak'] = skor_buah_masak_mb($PerMskEst);
            $mtPLABuah[$key]['skor_over'] = skor_buah_over_mb($PerOverEst);;
            $mtPLABuah[$key]['skor_jjgKosong'] = skor_jangkos_mb($PerkosongjjgEst);
            $mtPLABuah[$key]['skor_vcut'] = skor_vcut_mb($PerVcutest);
            $mtPLABuah[$key]['skor_kr'] = skor_abr_mb($per_krEst);
            $mtPLABuah[$key]['skorPlasma'] = $totalSkorEst;

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
            $mtPLABuah[$key]['tph_baris_blok'] = 0;
            $mtPLABuah[$key]['sampleJJG_total'] = 0;
            $mtPLABuah[$key]['total_mentah'] = 0;
            $mtPLABuah[$key]['total_perMentah'] = 0;
            $mtPLABuah[$key]['total_masak'] = 0;
            $mtPLABuah[$key]['total_perMasak'] = 0;
            $mtPLABuah[$key]['total_over'] = 0;
            $mtPLABuah[$key]['total_perOver'] = 0;
            $mtPLABuah[$key]['total_abnormal'] = 0;
            $mtPLABuah[$key]['total_perabnormal'] = 0;
            $mtPLABuah[$key]['total_jjgKosong'] = 0;
            $mtPLABuah[$key]['total_perKosongjjg'] = 0;
            $mtPLABuah[$key]['total_vcut'] = 0;
            $mtPLABuah[$key]['perVcut'] = 0;
            $mtPLABuah[$key]['jum_kr'] = 0;
            $mtPLABuah[$key]['kr_blok'] = 0;
            $mtPLABuah[$key]['persen_kr'] = 0;

            // skoring
            $mtPLABuah[$key]['skor_mentah'] = 0;
            $mtPLABuah[$key]['skor_masak'] = 0;
            $mtPLABuah[$key]['skor_over'] = 0;
            $mtPLABuah[$key]['skor_jjgKosong'] = 0;
            $mtPLABuah[$key]['skor_vcut'] = 0;
            $mtPLABuah[$key]['skor_abnormal'] = 0;;
            $mtPLABuah[$key]['skor_kr'] = 0;
            $mtPLABuah[$key]['skorPlasma'] = 0;
        }

        //mutu ancak
        $mtPLAancak = array();
        foreach ($mtAncakPlasma as $key => $value) if (!empty($value)) {
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
            foreach ($value as $key1 => $value1) if (!empty($value1)) {
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
                foreach ($value1 as $key2 => $value2) if (is_array($value2)) {
                    if (!in_array($value2['estate'] . ' ' . $value2['afdeling'] . ' ' . $value2['blok'], $listBlokPerAfd)) {
                        $listBlokPerAfd[] = $value2['estate'] . ' ' . $value2['afdeling'] . ' ' . $value2['blok'];
                    }
                    $jum_ha = count($listBlokPerAfd);
                    $totalPokok += $value2["sample"];
                    $totalPanen +=  $value2["jjg"];
                    $totalP_panen += $value2["brtp"];
                    $totalK_panen += $value2["brtk"];
                    $totalPTgl_panen += $value2["brtgl"];

                    $totalbhts_panen += $value2["bhts"];
                    $totalbhtm1_panen += $value2["bhtm1"];
                    $totalbhtm2_panen += $value2["bhtm2"];
                    $totalbhtm3_oanen += $value2["bhtm3"];

                    $totalpelepah_s += $value2["ps"];
                }


                if ($totalPokok != 0) {
                    $akp = round(($totalPanen / $totalPokok) * 100, 1);
                } else {
                    $akp = 0;
                }


                $skor_bTinggal = $totalP_panen + $totalK_panen + $totalPTgl_panen;

                if ($totalPokok != 0) {
                    $brdPerjjg = round($skor_bTinggal / $totalPanen, 1);
                } else {
                    $brdPerjjg = 0;
                }

                $sumBH = $totalbhts_panen +  $totalbhtm1_panen +  $totalbhtm2_panen +  $totalbhtm3_oanen;
                if ($sumBH != 0) {
                    $sumPerBH = round($sumBH / ($totalPanen + $sumBH) * 100, 1);
                } else {
                    $sumPerBH = 0;
                }

                if ($totalpelepah_s != 0) {
                    $perPl = round(($totalpelepah_s / $totalPokok) * 100, 2);
                } else {
                    $perPl = 0;
                }

                $nonZeroValues = array_filter([$totalP_panen, $totalK_panen, $totalPTgl_panen, $totalbhts_panen, $totalbhtm1_panen, $totalbhtm2_panen, $totalbhtm3_oanen]);

                if (!empty($nonZeroValues)) {
                    $mtPLAancak[$key][$key1]['check_data'] = 'ada';
                    // $mtPLAancak[$key][$key1]['skor_brd'] = $skor_brd = skor_brd_ma($sumPerBH);
                    // $mtPLAancak[$key][$key1]['skor_ps'] = $skor_ps = skor_palepah_ma($perPl);
                } else {
                    $mtPLAancak[$key][$key1]['check_data'] = 'kosong';
                    // $mtPLAancak[$key][$key1]['skor_brd'] = $skor_brd = 0;
                    // $mtPLAancak[$key][$key1]['skor_ps'] = $skor_ps = 0;
                }

                // $ttlSkorMA = $skor_bh + $skor_brd + $skor_ps;
                $ttlSkorMA = skor_brd_ma($brdPerjjg) + skor_buah_Ma($sumPerBH) + skor_palepah_ma($perPl);

                $mtPLAancak[$key][$key1]['pokok_sample'] = $totalPokok;
                $mtPLAancak[$key][$key1]['ha_sample'] = $jum_ha;
                $mtPLAancak[$key][$key1]['jumlah_panen'] = $totalPanen;
                $mtPLAancak[$key][$key1]['akp_rl'] = $akp;

                $mtPLAancak[$key][$key1]['p'] = $totalP_panen;
                $mtPLAancak[$key][$key1]['k'] = $totalK_panen;
                $mtPLAancak[$key][$key1]['tgl'] = $totalPTgl_panen;

                $mtPLAancak[$key][$key1]['total_brd'] = $skor_bTinggal;
                $mtPLAancak[$key][$key1]['brd/jjg'] = $brdPerjjg;

                // data untuk buah tinggal
                $mtPLAancak[$key][$key1]['bhts_s'] = $totalbhts_panen;
                $mtPLAancak[$key][$key1]['bhtm1'] = $totalbhtm1_panen;
                $mtPLAancak[$key][$key1]['bhtm2'] = $totalbhtm2_panen;
                $mtPLAancak[$key][$key1]['bhtm3'] = $totalbhtm3_oanen;
                $mtPLAancak[$key][$key1]['buah/jjg'] = $sumPerBH;

                $mtPLAancak[$key][$key1]['jjgperBuah'] = number_format($sumPerBH, 2);
                // data untuk pelepah sengklek

                $mtPLAancak[$key][$key1]['palepah_pokok'] = $totalpelepah_s;
                // total skor akhir
                $mtPLAancak[$key][$key1]['skor_bh'] = skor_brd_ma($brdPerjjg);
                $mtPLAancak[$key][$key1]['skor_brd'] = skor_buah_Ma($sumPerBH);
                $mtPLAancak[$key][$key1]['skor_ps'] = skor_palepah_ma($perPl);
                $mtPLAancak[$key][$key1]['skorWil'] = $ttlSkorMA;

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
                $mtPLAancak[$key][$key1]['pokok_sample'] = 0;
                $mtPLAancak[$key][$key1]['ha_sample'] = 0;
                $mtPLAancak[$key][$key1]['jumlah_panen'] = 0;
                $mtPLAancak[$key][$key1]['akp_rl'] =  0;

                $mtPLAancak[$key][$key1]['p'] = 0;
                $mtPLAancak[$key][$key1]['k'] = 0;
                $mtPLAancak[$key][$key1]['tgl'] = 0;

                // $mtPLAancak[$key][$key1]['total_brd'] = $skor_bTinggal;
                $mtPLAancak[$key][$key1]['brd/jjg'] = 0;

                // data untuk buah tinggal
                $mtPLAancak[$key][$key1]['bhts_s'] = 0;
                $mtPLAancak[$key][$key1]['bhtm1'] = 0;
                $mtPLAancak[$key][$key1]['bhtm2'] = 0;
                $mtPLAancak[$key][$key1]['bhtm3'] = 0;

                // $mtPLAancak[$key][$key1]['jjgperBuah'] = number_format($sumPerBH, 2);
                // data untuk pelepah sengklek

                $mtPLAancak[$key][$key1]['palepah_pokok'] = 0;
                // total skor akhi0;

                $mtPLAancak[$key][$key1]['skor_bh'] = 0;
                $mtPLAancak[$key][$key1]['skor_brd'] = 0;
                $mtPLAancak[$key][$key1]['skor_ps'] = 0;
                $mtPLAancak[$key][$key1]['skorWil'] = 0;
            }
            $sumBHEst = $bhtsEST +  $bhtm1EST +  $bhtm2EST +  $bhtm3EST;
            $totalPKT = $p_panenEst + $k_panenEst + $brtgl_panenEst;
            // dd($sumBHEst);
            if ($pokok_panenEst != 0) {
                $akpEst = round(($janjang_panenEst / $pokok_panenEst) * 100, 2);
            } else {
                $akpEst = 0;
            }

            if ($pokok_panenEst != 0) {
                $brdPerjjgEst = round($totalPKT / $janjang_panenEst, 1);
            } else {
                $brdPerjjgEst = 0;
            }



            // dd($sumBHEst);
            if ($sumBHEst != 0) {
                $sumPerBHEst = round($sumBHEst / ($janjang_panenEst + $sumBHEst) * 100, 1);
            } else {
                $sumPerBHEst = 0;
            }

            if ($pokok_panenEst != 0) {
                $perPlEst = round(($pelepah_sEST / $pokok_panenEst) * 100, 1);
            } else {
                $perPlEst = 0;
            }

            $nonZeroValues = array_filter([$p_panenEst, $k_panenEst, $brtgl_panenEst, $bhtsEST, $bhtm1EST, $bhtm2EST, $bhtm3EST]);

            if (!empty($nonZeroValues)) {
                $mtPLAancak[$key]['check_data'] = 'ada';
                // $mtPLAancak[$key]['skor_brd'] = $skor_brd = skor_brd_ma($sumPerBHEst);
                // $mtPLAancak[$key]['skor_ps'] = $skor_ps = skor_palepah_ma($perPlEst);
            } else {
                $mtPLAancak[$key]['check_data'] = 'kosong';
                // $mtPLAancak[$key]['skor_brd'] = $skor_brd = 0;
                // $mtPLAancak[$key]['skor_ps'] = $skor_ps = 0;
            }

            // $totalSkorEst = $skor_bh + $skor_brd + $skor_ps;
            $totalSkorEst =  skor_brd_ma($brdPerjjgEst) + skor_buah_Ma($sumPerBHEst) + skor_palepah_ma($perPlEst);
            //PENAMPILAN UNTUK PERESTATE
            $mtPLAancak[$key]['pokok_sample'] = $pokok_panenEst;
            $mtPLAancak[$key]['ha_sample'] =  $jum_haEst;
            $mtPLAancak[$key]['jumlah_panen'] = $janjang_panenEst;
            $mtPLAancak[$key]['akp_rl'] =  $akpEst;

            $mtPLAancak[$key]['p'] = $p_panenEst;
            $mtPLAancak[$key]['k'] = $k_panenEst;
            $mtPLAancak[$key]['tgl'] = $brtgl_panenEst;

            // $mtPLAancak[$key]['total_brd'] = $skor_bTinggal;
            $mtPLAancak[$key]['brd/jjgest'] = $brdPerjjgEst;
            $mtPLAancak[$key]['buah/jjg'] = $sumPerBHEst;

            // data untuk buah tinggal
            $mtPLAancak[$key]['bhts_s'] = $bhtsEST;
            $mtPLAancak[$key]['bhtm1'] = $bhtm1EST;
            $mtPLAancak[$key]['bhtm2'] = $bhtm2EST;
            $mtPLAancak[$key]['bhtm3'] = $bhtm3EST;
            $mtPLAancak[$key]['palepah_pokok'] = $pelepah_sEST;
            $mtPLAancak[$key]['palepah_per'] = $perPlEst;
            // total skor akhir
            $mtPLAancak[$key]['skor_bh'] =  skor_brd_ma($brdPerjjgEst);
            $mtPLAancak[$key]['skor_brd'] = skor_buah_Ma($sumPerBHEst);
            $mtPLAancak[$key]['skor_ps'] = skor_palepah_ma($perPlEst);
            $mtPLAancak[$key]['skorPlasma'] = $totalSkorEst;
        } else {
            $mtPLAancak[$key]['pokok_sample'] = 0;
            $mtPLAancak[$key]['ha_sample'] =  0;
            $mtPLAancak[$key]['jumlah_panen'] = 0;
            $mtPLAancak[$key]['akp_rl'] =  0;

            $mtPLAancak[$key]['p'] = 0;
            $mtPLAancak[$key]['k'] = 0;
            $mtPLAancak[$key]['tgl'] = 0;

            // $mtPLAancak[$key]['total_brd'] = $skor_bTinggal;
            $mtPLAancak[$key]['brd/jjgest'] = 0;
            $mtPLAancak[$key]['buah/jjg'] = 0;
            // data untuk buah tinggal
            $mtPLAancak[$key]['bhts_s'] = 0;
            $mtPLAancak[$key]['bhtm1'] = 0;
            $mtPLAancak[$key]['bhtm2'] = 0;
            $mtPLAancak[$key]['bhtm3'] = 0;
            $mtPLAancak[$key]['palepah_pokok'] = 0;
            // total skor akhir
            $mtPLAancak[$key]['skor_bh'] =  0;
            $mtPLAancak[$key]['skor_brd'] = 0;
            $mtPLAancak[$key]['skor_ps'] = 0;
            $mtPLAancak[$key]['skorPlasma'] = 0;
        }
        // dd($mtPLAancak);if (is_array($buah1) && $key1 == $cak1 && $cak1 == $bh1)
        //         mtPLA
        // mtPLAancak
        // mtPLABuah
        // dd($mtPLA, $mtPLABuah, $mtPLAancak);
        // dd($mtPLAancak);
        //rekap toal skor plasma
        $rekapPlasma = array();
        foreach ($mtPLA as $key => $trans) {
            foreach ($trans as $key2 => $trans1) {
                foreach ($mtPLAancak as $cak => $ancak) {
                    foreach ($ancak as $cak2 => $ancak1) {
                        foreach ($mtPLABuah as $bh => $buah) {
                            foreach ($buah as $bh2 => $buah1) if (is_array($buah1) && $key2 == $cak2 && $cak2 == $bh2) {
                                if ($trans1['check_data'] == 'kosong' && $ancak1['check_data'] === 'kosong' && $buah1['check_data'] === 'kosong') {

                                    $rekapPlasma[$key][$key2]['data'] = 'kosong';
                                }
                                if ($trans1['check_data'] == 'kosong' && $ancak1['check_data'] === 'kosong' && $buah1['check_data'] === 'kosong') {

                                    $rekapPlasma[$key][$key2]['Wil'] = 0;
                                } else {
                                    $rekapPlasma[$key][$key2]['Wil'] = $trans1['skorWil'] + $ancak1['skorWil'] + $buah1['skorWil'];
                                }
                                if ($trans1['check_data'] == 'kosong' && $ancak1['check_data'] === 'kosong' && $buah1['check_data'] === 'kosong') {

                                    $rekapPlasma[$key]['Plasma'] = 0;
                                } else {
                                    $rekapPlasma[$key]['Plasma'] = $trans['skorPlasma'] + $ancak['skorPlasma'] + $buah['skorPlasma'];
                                }
                            }
                        }
                    }
                }
            }
        }


        // dd($mtPLA);

        //  buat ranking
        foreach ($rekapPlasma as $key1 => $estates)  if (is_array($estates)) {
            // $sortedData = array();
            $sortedDataEst = array();
            foreach ($estates as $estateName => $data) {
                // dd($data);
                if (is_array($data)) {
                    $sortedDataEst[] = array(
                        'key1' => $key1,
                        'estateName' => $estateName,
                        'data' => $data
                    );
                }
            }
            usort($sortedDataEst, function ($a, $b) {
                return $b['data']['Wil'] - $a['data']['Wil'];
            });
            $rank = 1;
            foreach ($sortedDataEst as $sortedest) {
                $rekapPlasma[$key1][$sortedest['estateName']]['rank'] = $rank;
                $rank++;
            }
            unset($sortedDataEst);
        }
        // dd($rekapPlasma);
        function sortAndAssignRanks($rekapPlasma)
        {
            foreach ($rekapPlasma as $key1 => $estates) {
                if (is_array($estates)) {
                    $sortedDataEst = array();
                    foreach ($estates as $estateName => $data) {
                        if (is_array($data) && isset($data['Wil'])) {
                            $sortedDataEst[] = array(
                                'key1' => $key1,
                                'estateName' => $estateName,
                                'data' => $data
                            );
                        }
                    }

                    usort($sortedDataEst, function ($a, $b) {
                        return $a['data']['rank'] - $b['data']['rank'];
                    });

                    $sortedRekapPlasma = array(); // Create a temporary array to store the sorted data
                    foreach ($sortedDataEst as $sortedest) {
                        $sortedRekapPlasma[$sortedest['estateName']] = $rekapPlasma[$key1][$sortedest['estateName']];
                    }

                    // Add the "Plasma" key back to the sorted array
                    if (isset($rekapPlasma[$key1]['Plasma'])) {
                        $sortedRekapPlasma['Plasma'] = $rekapPlasma[$key1]['Plasma'];
                    }

                    // Replace the original array with the sorted one
                    $rekapPlasma[$key1] = $sortedRekapPlasma;
                    unset($sortedDataEst);
                }
            }

            return $rekapPlasma;
        }

        // Call the function to sort the array and assign ranks
        $rekapPlasma = sortAndAssignRanks($rekapPlasma);



        // dd($rekapPlasma);
        $rankingPlasma = $rekapPlasma;

        //ubah format untuk mempermudahkan menampilkan di tabel
        $PlasmaEm = array();
        foreach ($rankingPlasma as $key => $value) if (is_array($value)) {
            foreach ($value as $key1 => $value1) if (is_array($value1)) {
                // dd($value1);
                $inc = 0;
                $est = $key;
                $skor = $value1['Wil'];
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
                $PlasmaEm[] = array(
                    'est' => $est,
                    'afd' => $EM,
                    'nama' => $nama,
                    'skor' => $skor,
                    'rank' => $rank

                );
                $inc++;
            }
        }

        $PlasmaEm = array_values($PlasmaEm);

        $PlsamaGMEM = array();
        $namaEM = '-';
        foreach ($rankingPlasma as $key => $value) {
            if (is_array($value)) {
                $inc = 0;
                $est = $key;
                $skor = $value['Plasma'];
                $EM = 'EM';
                // $GM = 'GM';
                // dd($value);
                foreach ($queryAsisten as $key4 => $value4) {

                    if (is_array($value4) && $value4['est'] == $est && $value4['afd'] == $EM) {
                        $namaEM = $value4['nama'];
                    }
                    // if (is_array($value4) && $value4['est'] == $est && $value4['afd'] == $GM) {
                    //     $namaGM = $value4['nama'];
                    // }
                }
                $inc++;
            }
        }

        $PlsamaGMEM[] = array(
            'est' => $est,
            'afd' => $EM,
            'namaEM' => $namaEM,
            'Skor' => $skor,

        );

        $PlsamaGMEM = array_values($PlsamaGMEM);

        $plasmaGM = array();
        $plasmaGM = array();
        $namaGM = '-';
        $GM = 'GM';
        $skor = 0;
        $est = '';

        foreach ($rankingPlasma as $key => $value) {
            if (is_array($value) && isset($value['Plasma'])) {
                $inc = 0;
                $est = $key;
                $skor = $value['Plasma'];

                foreach ($queryAsisten as $key4 => $value4) {
                    if (is_array($value4) && $value4['est'] == $est && $value4['afd'] == $GM) {
                        $namaGM = $value4['nama'];
                    }
                }
                $inc++;
            }
        }

        $plasmaGM[] = array(
            'est' => $est,
            'afd' => $GM,
            'namaEM' => $namaGM,
            'Skor' => $skor,
        );

        $plasmaGM = array_values($plasmaGM);



        $pt_muaSkor = [
            'Pt_mua' => $sumOfAllScores
        ];
        //

        // dd($pt_muaSkor);


        $ptMuachartBuah = $mtAncakMua['buah/jjgwil'];
        $ptMuachartBRD = $mtAncakMua['brd/jjgwil'];
        $arrChartbhMua = [
            'pt_muabuah' => $ptMuachartBuah
        ];
        $arrChartbhBRD = [
            'pt_muabrd' => $ptMuachartBRD
        ];


        $filteredBRD = [];

        foreach ($array as $key => $value) {
            if (!in_array($key, $keysToRemove)) {
                $filteredBRD[$key] = $value;
            }
        }



        // dd($arrBuahBTT);
        // dd($arrChartbhMua, $filteredBuah);
        $filteredBuah["pt_muabuah"] = $arrChartbhMua["pt_muabuah"];
        $filteredBRD["pt_muabrd"] = $arrChartbhBRD["pt_muabrd"];
        // dd($filteredBuah);

        $chartBuahTTPlas = array();
        foreach ($mtPLAancak as $key => $value) {


            $chartBuahTTPlas[$key] = $value['buah/jjg'];
        }
        $chartPlasBRD = array();
        foreach ($mtPLAancak as $key => $value) {


            $chartPlasBRD[$key] = $value['brd/jjgest'];
        }


        if ($RegData == 1 || $RegData == '1') {
            $insertAfter = "UPE";
        } else if ($RegData == 2 || $RegData == '2') {
            $insertAfter = "SPE";
        } else {
            $insertAfter = "PKE";
        }

        $result_brd = [];
        $added = false;
        foreach ($filteredBRD as $key => $value) {
            $result_brd[$key] = $value;
            if ($key === $insertAfter && !$added) {
                foreach ($chartPlasBRD as $k => $v) {
                    $result_brd[$k] = $v;
                }
                $added = true;
            }
        }
        $result_buah = [];
        $addeds = false;
        foreach ($filteredBuah as $key => $value) {
            $result_buah[$key] = $value;
            if ($key === $insertAfter && !$addeds) {
                foreach ($chartBuahTTPlas as $k => $v) {
                    $result_buah[$k] = $v;
                }
                $addeds = true;
            }
        }

        // grafik untuk mutu buah
        //  dd($mtBuahtab1Wil_reg);
        $chrtBuahMentah = array();
        foreach ($mtBuahtab1Wil_reg as $key => $value) {
            foreach ($value as $key2 => $value2) if (is_array($value2)) {

                $chrtBuahMentah[$key2] = $value2['total_perMentah'];
            }
        }
        //  untuk mengahous key LDE etc 
        $chrtBuahMentahv2 = [];
        foreach ($chrtBuahMentah as $key => $value) {
            if (!in_array($key, $keysToRemove)) {
                $chrtBuahMentahv2[$key] = $value;
            }
        }
        //   dd($chrtBuahMentahv2);
        $chrtBuahMsk = array();
        foreach ($mtBuahtab1Wil_reg as $key => $value) {
            foreach ($value as $key2 => $value2) if (is_array($value2)) {

                $chrtBuahMsk[$key2] = $value2['total_perMasak'];
            }
        }
        //  untuk mengahous key LDE etc 
        $chrtBuahMskv2 = [];
        foreach ($chrtBuahMsk as $key => $value) {
            if (!in_array($key, $keysToRemove)) {
                $chrtBuahMskv2[$key] = $value;
            }
        }
        $chrtBuahOver = array();
        foreach ($mtBuahtab1Wil_reg as $key => $value) {
            foreach ($value as $key2 => $value2) if (is_array($value2)) {

                $chrtBuahOver[$key2] = $value2['total_perOver'];
            }
        }
        //  untuk mengahous key LDE etc 
        $chrtBuahOverv2 = [];
        foreach ($chrtBuahOver as $key => $value) {
            if (!in_array($key, $keysToRemove)) {
                $chrtBuahOverv2[$key] = $value;
            }
        }
        $chrtBuahAbr = array();
        foreach ($mtBuahtab1Wil_reg as $key => $value) {
            foreach ($value as $key2 => $value2) if (is_array($value2)) {

                $chrtBuahAbr[$key2] = $value2['total_perabnormal'];
            }
        }
        //  untuk mengahous key LDE etc 
        $chrtBuahAbrv2 = [];
        foreach ($chrtBuahAbr as $key => $value) {
            if (!in_array($key, $keysToRemove)) {
                $chrtBuahAbrv2[$key] = $value;
            }
        }
        $chrtBuahKosng = array();
        foreach ($mtBuahtab1Wil_reg as $key => $value) {
            foreach ($value as $key2 => $value2) if (is_array($value2)) {

                $chrtBuahKosng[$key2] = $value2['total_perKosongjjg'];
            }
        }
        //  untuk mengahous key LDE etc 
        $chrtBuahKosongv2 = [];
        foreach ($chrtBuahKosng as $key => $value) {
            if (!in_array($key, $keysToRemove)) {
                $chrtBuahKosongv2[$key] = $value;
            }
        }
        $chrtBuahVcut = array();
        foreach ($mtBuahtab1Wil_reg as $key => $value) {
            foreach ($value as $key2 => $value2) if (is_array($value2)) {

                $chrtBuahVcut[$key2] = $value2['perVcut'];
            }
        }
        //  untuk mengahous key LDE etc 
        $chrtBuahVcutv2 = [];
        foreach ($chrtBuahVcut as $key => $value) {
            if (!in_array($key, $keysToRemove)) {
                $chrtBuahVcutv2[$key] = $value;
            }
        }



        // dd($mtAncakMua);
        $arrBuahMentah = [
            'pt_mua' => $mtBuahMua['total_perMentah']
        ];
        $arrBuahMasak = [
            'pt_mua' => $mtBuahMua['total_perMasak']
        ];
        $arrBuahMOver = [
            'pt_mua' => $mtBuahMua['total_perOver']
        ];
        $arrBuahAbnrm = [
            'pt_mua' => $mtBuahMua['total_perabnormal']
        ];
        $arrBuahKosong = [
            'pt_mua' => $mtBuahMua['total_perKosongjjg']
        ];
        $arrBuahVcut = [
            'pt_mua' => $mtBuahMua['per_vcut']
        ];

        // membuat pt muah satu array dan di bagian bawah
        $chrtBuahMentahv2["pt_mua"] = $arrBuahMentah["pt_mua"];
        $chrtBuahMskv2["pt_mua"] = $arrBuahMasak["pt_mua"];
        $chrtBuahOverv2["pt_mua"] = $arrBuahMOver["pt_mua"];
        $chrtBuahAbrv2["pt_mua"] = $arrBuahAbnrm["pt_mua"];
        $chrtBuahKosongv2["pt_mua"] = $arrBuahKosong["pt_mua"];
        $chrtBuahVcutv2["pt_mua"] = $arrBuahVcut["pt_mua"];





        $willBuah_Mentah = array();
        foreach ($mtBuahtab1Wil_reg as $key => $value) {
            $willBuah_Mentah[$key] = $value['total_perMentah'];
        }
        $willBuah_Masak = array();
        foreach ($mtBuahtab1Wil_reg as $key => $value) {
            $willBuah_Masak[$key] = $value['total_perMasak'];
        }
        $willBuah_Over = array();
        foreach ($mtBuahtab1Wil_reg as $key => $value) {
            $willBuah_Over[$key] = $value['total_perOver'];
        }

        $willBuah_Abr = array();
        foreach ($mtBuahtab1Wil_reg as $key => $value) {
            $willBuah_Abr[$key] = $value['total_perabnormal'];
        }
        $willBuah_Kosong = array();
        foreach ($mtBuahtab1Wil_reg as $key => $value) {
            $willBuah_Kosong[$key] = $value['total_perKosongjjg'];
        }
        $willBuah_Vcut = array();
        foreach ($mtBuahtab1Wil_reg as $key => $value) {
            $willBuah_Vcut[$key] = $value['per_vcut'];
        }


        // dd($willBuah_Vcut);
        $arrays = [
            &$chrTransbrdv2,
            &$chrTransbuahv2,
            &$chrtBuahMentahv2,
            &$chrtBuahMskv2,
            &$chrtBuahOverv2,
            &$chrtBuahAbrv2,
            &$chrtBuahKosongv2,
            &$chrtBuahVcutv2
        ];
        $insertAfterv2 = $RegData == '1' ? "UPE" : ($RegData == '2' ? "SPE" : "PKE");
        function moveElement(&$array, $key, $insertAfterv2)
        {
            if (!array_key_exists($key, $array) || !array_key_exists($insertAfterv2, $array)) {
                return false;
            }
            $newArray = [];
            foreach ($array as $k => $v) {
                if ($k === $key) continue;
                $newArray[$k] = $v;
                if ($k === $insertAfterv2) {
                    $newArray[$key] = $array[$key];
                }
            }
            $array = $newArray;
            return true;
        }

        //end grafik mutu buah

        // grafik untuk mutu transport

        $chrTransbrd = array();
        foreach ($mtTranstab1Wil_reg as $key => $value) {
            foreach ($value as $key2 => $value2) if (is_array($value2)) {

                $chrTransbrd[$key2] = $value2['total_brd/TPH'];
            }
        }
        $chrTransbrdv2 = [];
        foreach ($chrTransbrd as $key => $value) {
            if (!in_array($key, $keysToRemove)) {
                $chrTransbrdv2[$key] = $value;
            }
        }

        $chrTransbuah = array();
        foreach ($mtTranstab1Wil_reg as $key => $value) {
            foreach ($value as $key2 => $value2) if (is_array($value2)) {

                $chrTransbuah[$key2] = $value2['total_buahPerTPH'];
            }
        }
        $chrTransbuahv2 = [];
        foreach ($chrTransbuah as $key => $value) {
            if (!in_array($key, $keysToRemove)) {
                $chrTransbuahv2[$key] = $value;
            }
        }


        $arrTransMentah = [
            'pt_mua' => $mtTransMua['total_brd/TPH']
        ];
        $arrTransMasak = [
            'pt_mua' => $mtTransMua['total_buahPerTPH']
        ];

        $chrTransbrdv2["pt_mua"] = $arrTransMentah["pt_mua"];
        $chrTransbuahv2["pt_mua"] = $arrTransMasak["pt_mua"];





        // Arrays to be modified


        if (in_array($RegData, ['2', '3', 2, 3])) {
            foreach ($arrays as &$array) {
                if (array_key_exists('pt_mua', $array)) {
                    unset($array['pt_mua']);
                }
            }
        }

        foreach ($arrays as &$array) {
            $plasmaKeys = preg_grep('/^Plasma/', array_keys($array));
            foreach ($plasmaKeys as $plasmaKey) {
                moveElement($array, $plasmaKey, $insertAfterv2);
            }
        }

        // transport perwilayah 

        $WilTransBRD = array();
        foreach ($mtTranstab1Wil_reg as $key => $value) {
            $WilTransBRD[$key] = $value['total_brd/TPH'];
        }

        $WilTransBuah = array();
        foreach ($mtTranstab1Wil_reg as $key => $value) {
            $WilTransBuah[$key] = $value['total_buahPerTPH'];
        }

        if ($RegData != 1 && $RegData != '1') {
            unset($result_brd['pt_muabrd']);
            unset($result_buah['pt_muabuah']);
            unset($chrTransbrdv2['pt_mua']);
            unset($chrTransbuahv2['pt_mua']);
            unset($chrtBuahMentahv2['pt_mua']);
            unset($chrtBuahMskv2['pt_mua']);
            unset($chrtBuahOverv2['pt_mua']);
            unset($chrtBuahAbrv2['pt_mua']);
            unset($chrtBuahKosongv2['pt_mua']);
            unset($chrtBuahVcutv2['pt_mua']);
        }

        // $queryEsta = updateKeyRecursive2($queryEsta);
        // dd($mtTranstab1Wil_reg,$chrTransbuahv2);
        $arrView = array();
        // dd($FormatTable1);
        $arrView['chart_brd'] = $result_brd;
        $arrView['chart_buah'] = $result_buah;
        $arrView['chart_brdwil'] =  $chartPerwil;
        $arrView['chart_buahwil'] = $buahPerwil;
        $arrView['RekapRegTable'] =  $RekapRegTable;
        $arrView['GM_1'] =  $GmWil1;
        $arrView['GM_2'] =  $GmWil2;
        $arrView['GM_3'] =  $GmWil3;
        $arrView['asisten'] =  $queryAsisten;
        $arrView['data_tabelutama'] =  $FormatTable1;
        $arrView['data_tabelkedua'] =  $FormatTable2;
        $arrView['data_tabeketiga'] =  $FormatTable3;
        $arrView['data_Est1'] =  $FormatTabEst1;
        $arrView['data_Est2'] =  $FormatTabEst2;
        $arrView['data_Est3'] =  $FormatTabEst3;
        $arrView['data_GM'] =  $RHGM;
        $arrView['list_estate'] =  $queryEsta;
        $arrView['plasma'] =  $PlasmaEm;
        $arrView['plasmaEM'] =  $PlsamaGMEM;
        $arrView['plasmaGM'] =  $plasmaGM;
        $arrView['pt_mua'] =  $pt_muaSkor;
        $arrView['ptmuaBuah'] =  $ptMuachartBuah;
        $arrView['ptmuaBRD'] =  $ptMuachartBRD;

        // grafik mutu buah
        // dd($chrtBuahMentahv2);
        $arrView['mtbuah_mentah'] =  $chrtBuahMentahv2;
        $arrView['mtbuah_masak'] =  $chrtBuahMskv2;
        $arrView['mtbuah_over'] =  $chrtBuahOverv2;
        $arrView['mtbuah_abnr'] =  $chrtBuahAbrv2;
        $arrView['mtbuah_ksong'] =  $chrtBuahKosongv2;
        $arrView['mtbuah_vcut'] =  $chrtBuahVcutv2;

        $arrView['willBuah_Mentah'] =  $willBuah_Mentah;
        $arrView['willBuah_Masak'] =  $willBuah_Masak;
        $arrView['willBuah_Over'] =  $willBuah_Over;
        $arrView['willBuah_Abr'] =  $willBuah_Abr;
        $arrView['willBuah_Kosong'] =  $willBuah_Kosong;
        $arrView['willBuah_Vcut'] =  $willBuah_Vcut;
        // grafik mutu transport
        $arrView['mttrans_brd'] =  $chrTransbrdv2;
        $arrView['mttrans_buah'] =  $chrTransbuahv2;
        $arrView['mttrans_wilbrd'] =  $WilTransBRD;
        $arrView['mttrans_wilbuah'] =  $WilTransBuah;

        // dd($FinalTahun);
        echo json_encode($arrView); //di decode ke dalam bentuk json dalam vaiavel arrview yang dapat menampung banyak isi array
        exit();
    }


    public function pdfBA_excel(Request $request)
    {
        $est = $request->input('estBA_excel');
        $afd = $request->input('afdBA_excel');
        $date = $request->input('tglPDF_excel');
        $reg = $request->input('regExcel');

        $mutuAncak = DB::connection('mysql2')->table('mutu_ancak_new')
            ->select("mutu_ancak_new.*", DB::raw('DATE_FORMAT(mutu_ancak_new.datetime, "%M") as bulan'), DB::raw('DATE_FORMAT(mutu_ancak_new.datetime, "%Y") as tahun'))
            ->where('datetime', 'like', '%' . $date . '%')
            ->where('mutu_ancak_new.estate', $est)
            ->where('mutu_ancak_new.afdeling', $afd)
            ->get();

        $mutuAncak = $mutuAncak->groupBy(['estate', function ($item) {
            $blok = $item->blok;
            $dashIndex = strpos($blok, '-');
            if ($dashIndex !== false) {
                return substr($blok, 0, $dashIndex);
            }
            return $blok;
        }]);

        $mutuAncakResult = [];

        foreach ($mutuAncak as $estate => $blocks) {
            $mutuAncakResult[$estate] = [];
            foreach ($blocks as $block => $data) {
                $mutuAncakResult[$estate][$block] = [];
                foreach ($data as $item) {
                    $mutuAncakResult[$estate][$block][] = json_decode(json_encode($item), true);
                }
            }
        }

        $mutuBuahQuery = DB::connection('mysql2')->table('mutu_buah')
            ->select("mutu_buah.*", DB::raw('DATE_FORMAT(mutu_buah.datetime, "%M") as bulan'), DB::raw('DATE_FORMAT(mutu_buah.datetime, "%Y") as tahun'))
            ->where('datetime', 'like', '%' . $date . '%')
            ->where('mutu_buah.estate', $est)
            ->where('mutu_buah.afdeling', $afd)
            ->get();
        $mutuBuahQuery = $mutuBuahQuery->groupBy(['estate', function ($item) {
            $blok = $item->blok;
            $dashIndex = strpos($blok, '-');
            if ($dashIndex !== false) {
                return substr($blok, 0, $dashIndex);
            }
            return $blok;
        }]);
        $mutuBuahQueryResult = [];

        foreach ($mutuBuahQuery as $estate => $blocks) {
            $mutuBuahQueryResult[$estate] = [];
            foreach ($blocks as $block => $data) {
                $mutuBuahQueryResult[$estate][$block] = [];
                foreach ($data as $item) {
                    $mutuBuahQueryResult[$estate][$block][] = json_decode(json_encode($item), true);
                }
            }
        }

        $mutuTransport = DB::connection('mysql2')->table('mutu_transport')
            ->select("mutu_transport.*", DB::raw('DATE_FORMAT(mutu_transport.datetime, "%M") as bulan'), DB::raw('DATE_FORMAT(mutu_transport.datetime, "%Y") as tahun'))
            ->where('datetime', 'like', '%' . $date . '%')
            ->where('mutu_transport.estate', $est)
            ->where('mutu_transport.afdeling', $afd)
            ->get();

        $mutuTransport = $mutuTransport->groupBy(['estate', function ($item) {
            $blok = $item->blok;
            $dashIndex = strpos($blok, '-');
            if ($dashIndex !== false) {
                return substr($blok, 0, $dashIndex);
            }
            return $blok;
        }]);

        $mutuTransportResult = [];

        foreach ($mutuTransport as $estate => $blocks) {
            $mutuTransportResult[$estate] = [];
            foreach ($blocks as $block => $data) {
                $mutuTransportResult[$estate][$block] = [];
                foreach ($data as $item) {
                    $mutuTransportResult[$estate][$block][] = json_decode(json_encode($item), true);
                }
            }
        }


        // dd($mutuAncakResult ,$mutuTransportResult,$mutuBuahQueryResult);
        foreach ($mutuAncakResult as $key => $value) {
            $jml_pokok_sm_est = 0;
            $luas_ha_est = 0;
            $jml_jjg_panen_est = 0;
            $jml_brtp_est = 0;
            $jml_brtk_est = 0;
            $jml_brtgl_est = 0;
            $jml_bhts_est = 0;
            $jml_bhtm1_est = 0;
            $jml_bhtm2_est = 0;
            $jml_bhtm3_est = 0;
            $jml_ps_est = 0;
            foreach ($value as $key2 => $value2) {
                $listBlok = array();
                $sph = 0;
                $jml_pokok_sm = 0;
                $jml_jjg_panen = 0;
                $jml_brtp = 0;
                $jml_brtk = 0;
                $jml_brtgl = 0;
                $jml_bhts = 0;
                $jml_bhtm1 = 0;
                $jml_bhtm2 = 0;
                $jml_bhtm3 = 0;
                $jml_ps = 0;
                foreach ($value2 as $key3 => $value3) {
                    // dd($value3);
                    if (!in_array($value3['estate'] . ' ' . $value3['afdeling'] . ' ' . $value3['blok'], $listBlok)) {
                        if ($value3['sph'] != 0) {
                            $listBlok[] = $value3['estate'] . ' ' . $value3['afdeling'] . ' ' . $value3['blok'];
                            $sph += $value3['sph'];
                        }
                    }
                    $jml_blok = count($listBlok);
                    $jml_pokok_sm += $value3['sample'];
                    $jml_jjg_panen += $value3['jjg'];
                    $jml_brtp += $value3['brtp'];
                    $jml_brtk += $value3['brtk'];
                    $jml_brtgl += $value3['brtgl'];
                    $jml_bhts += $value3['bhts'];
                    $jml_bhtm1 += $value3['bhtm1'];
                    $jml_bhtm2 += $value3['bhtm2'];
                    $jml_bhtm3 += $value3['bhtm3'];
                    $jml_ps += $value3['ps'];
                }
                $jml_sph = $jml_blok == 0 ? $sph : ($sph / $jml_blok);
                $tot_brd = ($jml_brtp + $jml_brtk + $jml_brtgl);
                $tot_jjg = ($jml_bhts + $jml_bhtm1 + $jml_bhtm2 + $jml_bhtm3);
                $luas_ha = round(($jml_pokok_sm / $jml_sph), 2);

                $jml_pokok_sm_est += $jml_pokok_sm;
                $luas_ha_est += $luas_ha;
                $jml_jjg_panen_est += $jml_jjg_panen;
                $jml_brtp_est += $jml_brtp;
                $jml_brtk_est += $jml_brtk;
                $jml_brtgl_est += $jml_brtgl;
                $jml_bhts_est += $jml_bhts;
                $jml_bhtm1_est += $jml_bhtm1;
                $jml_bhtm2_est += $jml_bhtm2;
                $jml_bhtm3_est += $jml_bhtm3;
                $jml_ps_est += $jml_ps;

                if ($reg === '2') {
                    $status_panen = explode(",", $value3['status_panen']);
                    $dataSkor[$key][$key2]['status_panen'] = $status_panen[0];
                } else {
                    $dataSkor[$key][$key2]['status_panen'] = $value3['status_panen'];
                }
                $dataSkor[$key][$key2]['jml_pokok_sampel'] = $jml_pokok_sm;
                $dataSkor[$key][$key2]['luas_ha'] = $value3['luas_blok'];
                $dataSkor[$key][$key2]['jml_jjg_panen'] = $jml_jjg_panen;
                $dataSkor[$key][$key2]['akp_real'] = count_percent($jml_jjg_panen, $jml_pokok_sm);
                $dataSkor[$key][$key2]['p_ma'] = $jml_brtp;
                $dataSkor[$key][$key2]['k_ma'] = $jml_brtk;
                $dataSkor[$key][$key2]['gl_ma'] = $jml_brtgl;
                $dataSkor[$key][$key2]['total_brd_ma'] = $tot_brd;
                $dataSkor[$key][$key2]['pemanen'] = $value3['ancak_pemanen'];
                if ($jml_jjg_panen != 0) {
                    $dataSkor[$key][$key2]['btr_jjg_ma'] = round(($tot_brd / $jml_jjg_panen), 2);
                } else {
                    $dataSkor[$key][$key2]['btr_jjg_ma'] = 0;
                }
                $dataSkor[$key][$key2]['skor_brd'] = $jml_jjg_panen !== 0 ? skor_brd_ma(round(($tot_brd / $jml_jjg_panen), 2)) : 0;


                $dataSkor[$key][$key2]['bhts_ma'] = $jml_bhts;
                $dataSkor[$key][$key2]['bhtm1_ma'] = $jml_bhtm1;
                $dataSkor[$key][$key2]['bhtm2_ma'] = $jml_bhtm2;
                $dataSkor[$key][$key2]['bhtm3_ma'] = $jml_bhtm3;
                $dataSkor[$key][$key2]['tot_jjg_ma'] = $tot_jjg;
                if ($tot_jjg != 0) {
                    $dataSkor[$key][$key2]['jjg_tgl_ma'] = round(($tot_jjg / ($jml_jjg_panen + $tot_jjg)) * 100, 2);
                } else {
                    $dataSkor[$key][$key2]['jjg_tgl_ma'] = 0;
                }
                $dataSkor[$key][$key2]['skor_buah'] = ($jml_jjg_panen + $tot_jjg) !== 0 ? skor_buah_Ma(round($tot_jjg / ($jml_jjg_panen + $tot_jjg)) * 100, 2) : 0;


                $dataSkor[$key][$key2]['ps_ma'] = $jml_ps;
                $dataSkor[$key][$key2]['PerPSMA'] = count_percent($jml_ps, $jml_pokok_sm);
                $dataSkor[$key][$key2]['skor_palepah'] = skor_palepah_ma(count_percent($jml_ps, $jml_pokok_sm));
            }
            $tot_brd_est = ($jml_brtp_est + $jml_brtk_est + $jml_brtgl_est);
            $tot_jjg_est = ($jml_bhts_est + $jml_bhtm1_est + $jml_bhtm2_est + $jml_bhtm3_est);

            $dataSkor[$key]['tot_jml_pokok_ma'] = $jml_pokok_sm_est;
            $dataSkor[$key]['tot_luas_ha_ma'] = $luas_ha_est;
            $dataSkor[$key]['tot_jml_jjg_panen_ma'] = $jml_jjg_panen_est;
            $dataSkor[$key]['akp_real_est'] = count_percent($jml_jjg_panen_est, $jml_pokok_sm_est);
            $dataSkor[$key]['p_ma_est'] = $jml_brtp_est;
            $dataSkor[$key]['k_ma_est'] = $jml_brtk_est;
            $dataSkor[$key]['gl_ma_est'] = $jml_brtgl_est;
            $dataSkor[$key]['total_brd_ma_est'] = $tot_brd_est;
            $dataSkor[$key]['btr_jjg_ma_est'] = $jml_jjg_panen_est == 0 ? $tot_brd_est : round(($tot_brd_est / $jml_jjg_panen_est), 2);
            $dataSkor[$key]['bhts_ma_est'] = $jml_bhts_est;
            $dataSkor[$key]['bhtm1_ma_est'] = $jml_bhtm1_est;
            $dataSkor[$key]['bhtm2_ma_est'] = $jml_bhtm2_est;
            $dataSkor[$key]['bhtm3_ma_est'] = $jml_bhtm3_est;
            $dataSkor[$key]['tot_jjg_ma_est'] = $tot_jjg_est;
            $dataSkor[$key]['jjg_tgl_ma_est'] = ($jml_jjg_panen_est + $tot_jjg_est) == 0 ? $tot_jjg_est : round(($tot_jjg_est / ($jml_jjg_panen_est + $tot_jjg_est)) * 100, 2);
            $dataSkor[$key]['ps_ma_est'] = $jml_ps_est;
            $dataSkor[$key]['PerPSMA_est'] = count_percent($jml_ps_est, $jml_pokok_sm_est);
        }





        foreach ($mutuTransportResult as $key => $value) {
            $skor_butir = 0;
            $skor_restant = 0;
            $sum_tph_sample = 0;
            $sum_skor_bt = 0;
            $sum_jjg = 0;
            foreach ($value as $key2 => $value2) {
                $sum_bt = 0;
                $sum_Restan = 0;
                $tph_sample = 0;
                $listBlokPerAfd = array();
                foreach ($value2 as $key3 => $value3) {
                    // if (!in_array($value3['estate'] . ' ' . $value3['afdeling'] . ' ' . $value3['blok'], $listBlokPerAfd)) {
                    $listBlokPerAfd[] = $value3['estate'] . ' ' . $value3['afdeling'] . ' ' . $value3['blok'];
                    // }
                    $sum_Restan += $value3['rst'];
                    $tph_sample = count($listBlokPerAfd);
                    $sum_bt += $value3['bt'];
                }

                $sum_skor_bt += $sum_bt;
                $sum_jjg += $sum_Restan;

                if ($reg === '2' || $reg == 2 || $reg === '4' || $reg == 4) {
                    foreach ($dataSkor as $keys => $value) {
                        if ($keys == $key) {
                            $panen = 0;
                            $LuasKey = 0;
                            $status_panen = 0; // Initialize the status_panen variable

                            foreach ($value as $keys1 => $value1) {
                                if ($keys1 == $key2) {
                                    $status_panen = $value1['status_panen']; // Update the status_panen value
                                    $dataSkor_trans[$key][$key2]['status'] = $value1['status_panen'];
                                    $dataSkor_trans[$key][$key2]['luas_blok'] = $value1['luas_ha'];

                                    $panen = $value1['status_panen'];
                                    $LuasKey = $value1['luas_ha'];
                                }
                            }

                            // Calculate the new tph_sample value based on the status_panen value
                            if ($status_panen !== 0 && $status_panen <= 3) {
                                $tod = round($LuasKey * 1.3);
                                $dataSkor_trans[$key][$key2]['tph_sample'] = $tod;
                                $dataSkor_trans[$key][$key2]['skor'] = round($sum_bt / $tod, 2);
                                $dataSkor_trans[$key][$key2]['skor_restan'] = round($sum_Restan / $tod, 2);
                                $sum_tph_sample += $tod;
                            } else {
                                $dataSkor_trans[$key][$key2]['tph_sample'] = $tph_sample;
                                $dataSkor_trans[$key][$key2]['skor'] = round($sum_bt / $tph_sample, 2);
                                $dataSkor_trans[$key][$key2]['skor_restan'] = round($sum_Restan / $tph_sample, 2);
                                $sum_tph_sample += $tph_sample;
                            }
                        }
                    }
                }
                //  $sum_tph_sample += $tph_sample;
                $dataSkor_trans[$key][$key2]['bt_total'] = $sum_bt;
                $dataSkor_trans[$key][$key2]['restan_total'] = $sum_Restan;


                $dataSkor_trans[$key][$key2]['skor_bt'] = skor_brd_tinggal(($tph_sample != 0) ? round($sum_bt / $tph_sample, 2) : 0);
                $dataSkor_trans[$key][$key2]['skoring_restan'] = skor_buah_tinggal(($tph_sample != 0) ? round($sum_Restan / $tph_sample, 2) : 0);
            }
            $dataSkor_trans[$key]['bt_total'] = $sum_skor_bt;
            $dataSkor_trans[$key]['tph_sample_total'] = $sum_tph_sample;
            $dataSkor_trans[$key]['bt_tph_total'] = round(($sum_tph_sample != 0) ? ($sum_skor_bt / $sum_tph_sample) : 0, 2);
            $dataSkor_trans[$key]['jjg_total'] = $sum_jjg;
            $dataSkor_trans[$key]['jjg_tph_total'] = round(($sum_tph_sample != 0) ? ($sum_jjg / $sum_tph_sample) : 0, 2);
        }







        foreach ($mutuBuahQueryResult as $key => $value) {
            $sum_blok = 0;
            $sum_janjang = 0;
            $sum_jjg_mentah = 0;
            $sum_jjg_mentah2 = 0;
            $sum_jjg_over = 0;
            $sum_jjg_empty = 0;
            $sum_jjg_abnormal = 0;
            $sum_jjg_vcut = 0;
            $sum_jjg_als = 0;
            foreach ($value as $key2 => $value2) {
                $listBlokPerAfd = array();
                $janjang = 0;
                $Jjg_Mth = 0;
                $Jjg_Mth2 = 0;
                $Jjg_Over = 0;
                $Jjg_Empty = 0;
                $Jjg_Abr = 0;
                $Jjg_Vcut = 0;
                $Jjg_Als = 0;
                foreach ($value2 as $key3 => $value3) {
                    // if (!in_array($value3['estate'] . ' ' . $value3['afdeling'] . ' ' . $value3['blok'] . ' ' . $value3['tph_baris'], $listBlokPerAfd)) {
                    $listBlokPerAfd[] = $value3['estate'] . ' ' . $value3['afdeling'] . ' ' . $value3['blok'] . ' ' . $value3['tph_baris'];
                    // }
                    $dtBlok = count($listBlokPerAfd);
                    $janjang += $value3['jumlah_jjg'];
                    $Jjg_Mth += $value3['bmt'];
                    $Jjg_Mth2 += $value3['bmk'];
                    $Jjg_Over += $value3['overripe'];
                    $Jjg_Empty += $value3['empty_bunch'];
                    $Jjg_Abr += $value3['abnormal'];
                    $Jjg_Vcut += $value3['vcut'];
                    $Jjg_Als += $value3['alas_br'];
                }
                $jml_mth = ($Jjg_Mth + $Jjg_Mth2);
                $jml_mtg = $janjang - ($jml_mth + $Jjg_Over + $Jjg_Empty + $Jjg_Abr);
                $sum_blok += $dtBlok;
                $sum_janjang += $janjang;
                $sum_jjg_mentah += $Jjg_Mth;
                $sum_jjg_mentah2 += $Jjg_Mth2;
                $sum_jjg_over += $Jjg_Over;
                $sum_jjg_empty += $Jjg_Empty;
                $sum_jjg_abnormal += $Jjg_Abr;
                $sum_jjg_vcut += $Jjg_Vcut;
                $sum_jjg_als += $Jjg_Als;

                $dataSkorbuah[$key][$key2]['blok_mb'] = $dtBlok ?? 0;
                $dataSkorbuah[$key][$key2]['alas_mb'] = $Jjg_Als ?? 0;
                $dataSkorbuah[$key][$key2]['jml_janjang'] = $janjang ?? 0;
                $dataSkorbuah[$key][$key2]['jml_mentah'] = $jml_mth ?? 0;
                $dataSkorbuah[$key][$key2]['jml_masak'] = $jml_mtg ?? 0;
                $dataSkorbuah[$key][$key2]['jml_over'] = $Jjg_Over ?? 0;
                $dataSkorbuah[$key][$key2]['jml_empty'] = $Jjg_Empty ?? 0;
                $dataSkorbuah[$key][$key2]['jml_abnormal'] = $Jjg_Abr ?? 0;
                $dataSkorbuah[$key][$key2]['jml_vcut'] = $Jjg_Vcut ?? 0;

                $dataSkorbuah[$key][$key2]['jml_krg_brd'] = $dtBlok == 0 ? $Jjg_Als : round($Jjg_Als / $dtBlok, 2);
                $denom = ($janjang - $Jjg_Abr) != 0 ? ($janjang - $Jjg_Abr) : 1;

                $dataSkorbuah[$key][$key2]['PersenBuahMentah'] = round(($jml_mth / $denom) * 100, 2) ?? 0;
                $dataSkorbuah[$key][$key2]['PersenBuahMasak'] = round(($jml_mtg / $denom) * 100, 2) ?? 0;
                $dataSkorbuah[$key][$key2]['PersenBuahOver'] = round(($Jjg_Over / $denom) * 100, 2) ?? 0;
                $dataSkorbuah[$key][$key2]['PersenPerJanjang'] = round(($Jjg_Empty / $denom) * 100, 2) ?? 0;

                $dataSkorbuah[$key][$key2]['PersenVcut'] = count_percent($Jjg_Vcut, $janjang) ?? 0;
                $dataSkorbuah[$key][$key2]['PersenAbr'] = count_percent($Jjg_Abr, $janjang) ?? 0;
                $dataSkorbuah[$key][$key2]['PersenKrgBrd'] = count_percent($Jjg_Als, $dtBlok) ?? 0;

                $dataSkorbuah[$key][$key2]['skor_mentah'] = skor_buah_mentah_mb(round(($jml_mth / $denom) * 100, 2) ?? 0);
                $dataSkorbuah[$key][$key2]['skor_matang'] = skor_buah_masak_mb(round(($jml_mtg / $denom) * 100, 2) ?? 0);
                $dataSkorbuah[$key][$key2]['skor_over'] = skor_buah_over_mb(round(($Jjg_Over / $denom) * 100, 2) ?? 0);
                $dataSkorbuah[$key][$key2]['skor_kosong'] = skor_jangkos_mb(round(($Jjg_Empty / $denom) * 100, 2) ?? 0);
                $dataSkorbuah[$key][$key2]['skor_vcut'] = skor_vcut_mb(count_percent($Jjg_Vcut, $janjang) ?? 0);
                $dataSkorbuah[$key][$key2]['skor_karung'] = skor_abr_mb(count_percent($Jjg_Als, $dtBlok) ?? 0);
            }
            $jml_mth_est = ($sum_jjg_mentah + $sum_jjg_mentah2);
            $jml_mtg_est = $sum_janjang - ($jml_mth_est + $sum_jjg_over + $sum_jjg_empty + $sum_jjg_abnormal);

            $dataSkorbuah[$key]['tot_blok'] = $sum_blok;
            $dataSkorbuah[$key]['tot_alas'] = $sum_jjg_als;
            $dataSkorbuah[$key]['tot_jjg'] = $sum_janjang;
            $dataSkorbuah[$key]['tot_mentah'] = $jml_mth_est;
            $dataSkorbuah[$key]['tot_matang'] = $jml_mtg_est;
            $dataSkorbuah[$key]['tot_over'] = $sum_jjg_over;
            $dataSkorbuah[$key]['tot_empty'] = $sum_jjg_empty;
            $dataSkorbuah[$key]['tot_abr'] = $sum_jjg_abnormal;
            $dataSkorbuah[$key]['tot_vcut'] = $sum_jjg_vcut;
            $dataSkorbuah[$key]['tot_krg_brd'] = $sum_blok == 0 ? $sum_jjg_als : round($sum_jjg_als / $sum_blok, 2);
            $dataSkorbuah[$key]['tot_PersenBuahMentah'] = round(($jml_mth_est / ($sum_janjang - $sum_jjg_abnormal)) * 100, 2);
            $dataSkorbuah[$key]['tot_PersenBuahMasak'] = round(($jml_mtg_est / ($sum_janjang - $sum_jjg_abnormal)) * 100, 2);
            $dataSkorbuah[$key]['tot_PersenBuahOver'] = round(($sum_jjg_over / ($sum_janjang - $sum_jjg_abnormal)) * 100, 2);
            $dataSkorbuah[$key]['tot_PersenPerJanjang'] = round(($sum_jjg_empty / ($sum_janjang - $sum_jjg_abnormal)) * 100, 2);
            $dataSkorbuah[$key]['tot_PersenVcut'] = count_percent($sum_jjg_vcut, $sum_janjang);
            $dataSkorbuah[$key]['tot_PersenAbr'] = count_percent($sum_jjg_abnormal, $sum_janjang);
            $dataSkorbuah[$key]['tot_PersenKrgBrd'] = count_percent($sum_jjg_als, $sum_blok);
        }


        // dd($dataSkor_trans);
        $arrView = array();
        $arrView['mutuAncak'] =  $mutuAncak;
        $arrView['mutuAncak_total'] =  $dataSkor ?? array(); // Provide a default value (empty array) if $dataSkor is not set
        $arrView['mutuTransport_total'] =  $dataSkor_trans ?? array(); // Provide a default value (empty array) if $dataSkor_trans is not set
        $arrView['mutuBuah_total'] =  $dataSkorbuah ?? array(); // Provide a default value (empty array) if $dataSkorbuah is not set


        $arrView['est'] =  $est;
        $arrView['afd'] =  $afd;
        $arrView['tanggal'] =  $date;
        $arrView['reg'] =  $reg;

        // dd($ancak);
        return view('Qcinspeksi.inpeksiBA_excel')->with('data', $arrView);
    }

    public function getDataDay(Request $request)
    {
        $est = $request->input('est');
        $afd = $request->input('afd');
        $date = $request->input('Tanggal');

        $reg = $request->get('reg');

        $mutuAncak = DB::connection('mysql2')->table('mutu_ancak_new')
            ->select("mutu_ancak_new.*", DB::raw('DATE_FORMAT(mutu_ancak_new.datetime, "%M") as bulan'), DB::raw('DATE_FORMAT(mutu_ancak_new.datetime, "%Y") as tahun'))
            ->where('datetime', 'like', '%' . $date . '%')
            ->where('mutu_ancak_new.estate', $est)
            ->where('mutu_ancak_new.afdeling', $afd)

            ->get();
        $mutuAncak = $mutuAncak->groupBy(['blok']);
        $mutuAncak = json_decode($mutuAncak, true);


        $mutuBuahQuery = DB::connection('mysql2')->table('mutu_buah')
            ->select("mutu_buah.*", DB::raw('DATE_FORMAT(mutu_buah.datetime, "%M") as bulan'), DB::raw('DATE_FORMAT(mutu_buah.datetime, "%Y") as tahun'))
            ->where('datetime', 'like', '%' . $date . '%')
            ->where('mutu_buah.estate', $est)
            ->where('mutu_buah.afdeling', $afd)

            ->get();
        $mutuBuahQuery = $mutuBuahQuery->groupBy(['blok']);
        $mutuBuahQuery = json_decode($mutuBuahQuery, true);

        $mutuTransport = DB::connection('mysql2')->table('mutu_transport')
            ->select("mutu_transport.*", DB::raw('DATE_FORMAT(mutu_transport.datetime, "%M") as bulan'), DB::raw('DATE_FORMAT(mutu_transport.datetime, "%Y") as tahun'))
            ->where('datetime', 'like', '%' . $date . '%')
            ->where('mutu_transport.estate', $est)
            ->where('mutu_transport.afdeling', $afd)

            ->get();
        $mutuTransport = $mutuTransport->groupBy(['blok']);
        $mutuTransport = json_decode($mutuTransport, true);

        // dd($mutuAncak);

        $ancak = array();
        $sum = 0; // Initialize sum variable
        $count = 0; // Initialize count variable
        foreach ($mutuAncak as $key => $value) {
            $jumPokok = 0;
            $sph = 0;
            $jml_jjg_panen = 0;
            $jml_brtp = 0;
            $jml_brtk = 0;
            $jml_brtgl = 0;
            $jml_bhts = 0;
            $jml_bhtm1 = 0;
            $jml_bhtm2 = 0;
            $jml_bhtm3 = 0;
            $jml_ps = 0;
            $listBlok = array();
            $pk_kuning = 0;
            $pr_smak = 0;
            $unprun  = 0;
            $sp = 0;
            $over_prun = 0;
            $pokok_panen = 0;
            $firstEntry = $value[0];
            foreach ($value as $key1 => $value2) {
                $jumPokok += $value2['sample'];
                if (!in_array($value2['estate'] . ' ' . $value2['afdeling'] . ' ' . $value2['blok'], $listBlok)) {
                    if ($value2['sph'] != 0) {
                        $listBlok[] = $value2['estate'] . ' ' . $value2['afdeling'] . ' ' . $value2['blok'];
                        $sph += $value2['sph'];
                    }
                }
                $jml_blok = count($listBlok);

                $jml_jjg_panen += $value2['jjg'];
                $jml_brtp += $value2['brtp'];
                $jml_brtk += $value2['brtk'];
                $jml_brtgl += $value2['brtgl'];
                $jml_bhts += $value2['bhts'];
                $jml_bhtm1 += $value2['bhtm1'];
                $jml_bhtm2 += $value2['bhtm2'];
                $jml_bhtm3 += $value2['bhtm3'];
                $jml_ps += $value2['ps'];


                // untuk bagian food stacking
                $pk_kuning += $value2['pokok_kuning'];
                $pr_smak += $value2['piringan_semak'];
                $unprun += $value2['underpruning'];
                $over_prun += $value2['overpruning'];
                $sp += $value2['sp'];
                $pokok_panen += $value2['pokok_panen'];
            }
            $jml_sph = $jml_blok == 0 ? $sph : ($sph / $jml_blok);
            $tot_brd = ($jml_brtp + $jml_brtk + $jml_brtgl);
            $tot_jjg = ($jml_bhts + $jml_bhtm1 + $jml_bhtm2 + $jml_bhtm3);
            // $luas_ha = round(($jumPokok / $jml_sph), 2);
            $luas_ha = ($jml_sph != 0) ? round(($jumPokok / $jml_sph), 2) : 0;

            if ($firstEntry['luas_blok'] != 0) {
                $first = $firstEntry['luas_blok'];
            } else {
                $first = '-';
            }
            // $luasha = round($jumPokok / $sph, 2);

            $ancak[$key]['luas_blok'] = $first;
            $ancak[$key]['persenSamp'] = ($first != '-') ? round(($luas_ha / $first) * 100, 2) : '-';

            if ($reg === '2' || $reg == 2) {
                $status_panen = explode(",", $value2['status_panen']);
                $ancak[$key]['status_panen'] = $status_panen[0];
            } else {
                $ancak[$key]['status_panen'] = $value2['status_panen'];
            }
            $ancak[$key]['sph'] = $sph;
            $ancak[$key]['pokok_sample'] = $jumPokok;
            $ancak[$key]['pokok_panen'] = $pokok_panen;
            $ancak[$key]['luas_ha'] = $luas_ha;
            $ancak[$key]['jml_jjg_panen'] = $jml_jjg_panen;
            if ($reg == 2 || $reg == '2') {
                $ancak[$key]['akp_real'] = round((($jml_jjg_panen + $tot_jjg) / $jumPokok * 100), 2);
            } else {
                $ancak[$key]['akp_real'] = count_percent($jml_jjg_panen, $jumPokok);
            }

            $ancak[$key]['p_ma'] = $jml_brtp;
            $ancak[$key]['k_ma'] = $jml_brtk;
            $ancak[$key]['gl_ma'] = $jml_brtgl;
            $ancak[$key]['total_brd_ma'] = $tot_brd;
            if ($jml_jjg_panen != 0) {
                $ancak[$key]['btr_jjg_ma'] = round(($tot_brd / $jml_jjg_panen), 2);
            } else {
                $ancak[$key]['btr_jjg_ma'] = 0;
            }
            $ancak[$key]['skor_brd'] = skor_brd_ma(round(($tot_brd / $jml_jjg_panen), 2));
            $ancak[$key]['bhts_ma'] = $jml_bhts;
            $ancak[$key]['bhtm1_ma'] = $jml_bhtm1;
            $ancak[$key]['bhtm2_ma'] = $jml_bhtm2;
            $ancak[$key]['bhtm3_ma'] = $jml_bhtm3;
            $ancak[$key]['tot_jjg_ma'] = $tot_jjg;
            if ($tot_jjg != 0) {
                $ancak[$key]['jjg_tgl_ma'] = round(($tot_jjg / ($jml_jjg_panen + $tot_jjg)) * 100, 2);
            } else {
                $ancak[$key]['jjg_tgl_ma'] = 0;
            }
            $ancak[$key]['skor_buah'] = skor_buah_Ma(round(($tot_jjg / ($jml_jjg_panen + $tot_jjg)) * 100, 2));
            $ancak[$key]['ps_ma'] = $jml_ps;

            $ancak[$key]['PerPSMA'] = count_percent($jml_ps, $jumPokok);
            $ancak[$key]['skor_pale'] = skor_palepah_ma(count_percent($jml_ps, $jumPokok));
            $ancak[$key]['front'] = $sp;
            $ancak[$key]['pk_kuning'] = $pk_kuning;
            $ancak[$key]['und'] = $unprun;
            $ancak[$key]['overprn'] = $over_prun;
            $ancak[$key]['ancak_panen'] = $value2['ancak_pemanen'];
            $ancak[$key]['prsmk'] = $pr_smak;
            $ancak[$key]['frontstack'] = ($jumPokok != 0) ? round(($sp / $jumPokok) * 100, 2) : 0;
            $ancak[$key]['under'] = ($jumPokok != 0) ? round(($unprun / $jumPokok) * 100, 2) : 0;
            $ancak[$key]['overprun'] = ($jumPokok != 0) ? round(($over_prun / $jumPokok) * 100, 2) : 0;
            $ancak[$key]['piringansmk'] = ($jumPokok != 0) ? round(($pr_smak / $jumPokok) * 100, 2) : 0;


            if ($first != '-') {
                $sum += $first; // Add luas_blok to the sum
                $count++;
            }
        }
        $average = $count != 0 ? $sum / $count : 0;

        // dd($ancak);
        $avg = [];
        foreach ($ancak as $key) {
            $avg['average'] = $average;
        }
        // dd($ancak, $avg);
        $sph_values = [];
        foreach ($ancak as $key => $data) {
            $sph_values[] = $data['sph'];
        }

        // Calculate the sum of sph values
        $sum = array_sum($sph_values);

        if (count($sph_values) > 0) {
            $average = round($sum / count($sph_values), 0);
        } else {
            $average = 0; // or any default value you prefer
        }

        // dd($average, $ancak);

        $transport = array();

        foreach ($mutuTransport as $key => $value) {
            $sum_bt = 0;
            $sum_Restan = 0;
            $tph_sample = 0;
            $listBlokPerAfd = array();
            foreach ($value as $key2 => $value2) {
                // if (!in_array($value3['estate'] . ' ' . $value3['afdeling'] . ' ' . $value3['blok'], $listBlokPerAfd)) {
                $listBlokPerAfd[] = $value2['estate'] . ' ' . $value2['afdeling'] . ' ' . $value2['blok'];
                // }
                $sum_Restan += $value2['rst'];
                $tph_sample = count($listBlokPerAfd);
                $sum_bt += $value2['bt'];
            }
            $transport[$key]['reg'] = 'reg1/reg3';
            $transport[$key]['bt_total'] = $sum_bt;
            $transport[$key]['restan_total'] = $sum_Restan;
            $transport[$key]['tph_sample'] = $tph_sample;
            $transport[$key]['skor'] = ($tph_sample != 0) ? round($sum_bt / $tph_sample, 2) : 0;
            $transport[$key]['skor_restan'] = ($tph_sample != 0) ? round($sum_Restan / $tph_sample, 2) : 0;
        }


        if ($reg == 2 || $reg == '2') {

            // $ancak_status = $ancak[''];
            foreach ($mutuTransport as $key => $value) {
                $sum_bt = 0;
                $sum_Restan = 0;
                $tph_sample = 0;
                $listBlokPerAfd = array();
                foreach ($value as $key2 => $value2) {
                    // if (!in_array($value3['estate'] . ' ' . $value3['afdeling'] . ' ' . $value3['blok'], $listBlokPerAfd)) {
                    $listBlokPerAfd[] = $value2['estate'] . ' ' . $value2['afdeling'] . ' ' . $value2['blok'];
                    // }
                    $sum_Restan += $value2['rst'];
                    $tph_sample = count($listBlokPerAfd);
                    $sum_bt += $value2['bt'];
                    // dd($value2);
                }

                $panenKey = 0;

                if (isset($ancak[$key]['status_panen'])) {
                    $transport[$key]['status_panen'] = $ancak[$key]['status_panen'];
                    $panenKey = $ancak[$key]['status_panen'];
                    $transport[$key]['status_panentrans'] = $value2['status_panen'];
                    $transport[$key]['status_panenAncak'] = $ancak[$key]['status_panen'];
                }
                $LuasKey = 0;
                if (isset($ancak[$key]['luas_blok'])) {
                    $transport[$key]['luas_blok'] = $ancak[$key]['luas_blok'];
                    $LuasKey = $ancak[$key]['luas_blok'];
                }

                if (isset($panenKey) && $panenKey <= 3 && isset($ancak[$key]['luas_blok'])) {
                    $transport[$key]['tph_sample'] = round($LuasKey * 1.3);
                } else {
                    $transport[$key]['tph_sample'] = $tph_sample;
                }


                $transport[$key]['reg'] = $reg;
                $transport[$key]['status_panen'] = $value2['status_panen'];
                $transport[$key]['tph_sampleTrans'] = $tph_sample;
                $transport[$key]['estate'] = $value2['estate'];
                $transport[$key]['afdeling'] = $value2['afdeling'];
                $transport[$key]['bt_total'] = $sum_bt;
                $transport[$key]['restan_total'] = $sum_Restan;
                $transport[$key]['skor'] = ($tph_sample != 0) ? round($sum_bt / $tph_sample, 2) : 0;
                $transport[$key]['skor_restan'] = ($tph_sample != 0) ? round($sum_Restan / $tph_sample, 2) : 0;
            }
            // Add this code after your existing foreach loop


            $transReg2 = array();
            $tph_sample = 0;
            foreach ($transport as $key => $value) {
                # code...
                $tph_sample += $value['tph_sample'];
                // dd($value);
            }
            if (isset($value['estate'])) {
                if (!isset($transReg2[$value['estate']])) {
                    $transReg2[$value['estate']] = [];
                }

                if (!isset($transReg2[$value['estate']][$value['afdeling']])) {
                    $transReg2[$value['estate']][$value['afdeling']] = [];
                }

                $transReg2[$value['estate']][$value['afdeling']]['tph_sample'] = $tph_sample;
            }


            foreach ($ancak as $key => $value) {
                if (!array_key_exists($key, $transport)) {
                    $transport[$key]['status_panen'] = $value['status_panen'];
                    $transport[$key]['luas_blok'] = $value['luas_blok'];

                    if ($value['status_panen'] <= 3) {
                        $transport[$key]['tph_sample'] = round($value['luas_blok'] * 1.3, 2);
                    } else {
                        $transport[$key]['tph_sample'] = $value['status_panen'];
                    }
                    $transport[$key]['bt_total'] = 0;
                    $transport[$key]['restan_total'] = 0;
                    $transport[$key]['skor'] = 0;
                    $transport[$key]['skor_restan'] = 0;
                }
            }
        }

        // dd($transport);
        $newVariable = array();

        foreach ($transport as $key => $value) {
            if (isset($value['status_panentrans']) && isset($value['status_panenAncak'])) {
                $newVariable[$key] = $value;
                break;  // stop the loop after the first match
            }
        }

        // dd($newVariable, $transport);
        $mutuBuah = array();
        foreach ($mutuBuahQuery as $key => $value) {
            $listBlokPerAfd = array();
            $janjang = 0;
            $Jjg_Mth = 0;
            $Jjg_Mth2 = 0;
            $Jjg_Over = 0;
            $Jjg_Empty = 0;
            $Jjg_Abr = 0;
            $Jjg_Vcut = 0;
            $Jjg_Als = 0;
            $dtBlok = count($value);
            $count_alas_br_1 = 0;
            $count_alas_br_0 = 0;
            $vcutStack = 0;
            foreach ($value as $key2 => $value2) {

                if (!in_array($value2['blok'], $listBlokPerAfd)) {
                    $listBlokPerAfd[] = $value2['blok'];
                }
                $janjang += $value2['jumlah_jjg'];
                $Jjg_Mth += $value2['bmt'];
                $Jjg_Mth2 += $value2['bmk'];
                $Jjg_Over += $value2['overripe'];
                $Jjg_Empty += $value2['empty_bunch'];
                $Jjg_Abr += $value2['abnormal'];
                $Jjg_Vcut += $value2['vcut'];
                $Jjg_Als += $value2['alas_br'];

                if ($value2['alas_br'] == 1) {
                    $count_alas_br_1++;
                } elseif ($value2['alas_br'] == 0) {
                    $count_alas_br_0++;
                }
            }
            //untuk food stacking
            $vcutStack = $janjang - $Jjg_Vcut;

            $jml_mth = ($Jjg_Mth + $Jjg_Mth2);
            $jml_mtg = $janjang - ($jml_mth + $Jjg_Over + $Jjg_Empty + $Jjg_Abr);

            $mutuBuah[$key]['blok_mb'] = $dtBlok;
            $mutuBuah[$key]['status_panen'] = $value2['status_panen'];
            $mutuBuah[$key]['alas_mb'] = $Jjg_Als;
            $mutuBuah[$key]['bmt'] = $Jjg_Mth;
            $mutuBuah[$key]['bmk'] = $Jjg_Mth2;
            $mutuBuah[$key]['jml_janjang'] = $janjang;
            $mutuBuah[$key]['jml_mentah'] = $jml_mth;
            $mutuBuah[$key]['jml_masak'] = $jml_mtg;
            $mutuBuah[$key]['jml_over'] = $Jjg_Over;
            $mutuBuah[$key]['jml_empty'] = $Jjg_Empty;
            $mutuBuah[$key]['jml_abnormal'] = $Jjg_Abr;
            $mutuBuah[$key]['jml_vcut'] = $Jjg_Vcut;
            $mutuBuah[$key]['jml_krg_brd'] = $dtBlok == 0 ? $Jjg_Als : round($Jjg_Als / $dtBlok, 2);
            $denom = ($janjang - $Jjg_Abr) != 0 ? ($janjang - $Jjg_Abr) : 1;

            $mutuBuah[$key]['PersenBuahMentah'] = $denom != 0 ? round(($jml_mth / $denom) * 100, 2) : 0;
            $mutuBuah[$key]['PersenBuahMasak'] = $denom != 0 ? round(($jml_mtg / $denom) * 100, 2) : 0;
            $mutuBuah[$key]['PersenBuahOver'] = $denom != 0 ? round(($Jjg_Over / $denom) * 100, 2) : 0;
            $mutuBuah[$key]['PersenPerJanjang'] = $denom != 0 ? round(($Jjg_Empty / $denom) * 100, 2) : 0;
            $mutuBuah[$key]['PersenVcut'] = count_percent($Jjg_Vcut, $janjang);
            $mutuBuah[$key]['PersenAbr'] = count_percent($Jjg_Abr, $janjang);
            $mutuBuah[$key]['PersenKrgBrd'] = count_percent($count_alas_br_1, $dtBlok);
            $mutuBuah[$key]['count_alas_br_1'] = $count_alas_br_1;
            $mutuBuah[$key]['count_alas_br_0'] = $count_alas_br_0;
            $mutuBuah[$key]['vst'] = $vcutStack;
            $mutuBuah[$key]['vcutStack'] = $janjang != 0 ? round(($vcutStack / $janjang) * 100, 2) : 0;
        }

        // dd($ancak, $mutuBuah);

        $BuahStack = array();

        // Merge the keys from both arrays and create a unique set of keys
        $keys = array_unique(array_merge(array_keys($mutuBuah), array_keys($ancak)));

        foreach ($keys as $key) {
            $currentBuahStack = array();
            $currentBuahStack['vst'] = isset($mutuBuah[$key]['vst']) ? $mutuBuah[$key]['vst'] : 0;
            $currentBuahStack['jml_janjang'] = isset($mutuBuah[$key]['jml_janjang']) ? $mutuBuah[$key]['jml_janjang'] : 0;
            $currentBuahStack['bmt'] = isset($mutuBuah[$key]['bmt']) ? $mutuBuah[$key]['bmt'] : 0;
            $currentBuahStack['bmk'] = isset($mutuBuah[$key]['bmk']) ? $mutuBuah[$key]['bmk'] : 0;
            $currentBuahStack['jml_vcut'] = isset($mutuBuah[$key]['jml_vcut']) ? $mutuBuah[$key]['jml_vcut'] : 0;
            //ancak 
            $currentBuahStack['front'] = isset($ancak[$key]['front']) ? $ancak[$key]['front'] : 0;
            $currentBuahStack['pk_kuning'] = isset($ancak[$key]['pk_kuning']) ? $ancak[$key]['pk_kuning'] : 0;
            $currentBuahStack['pokok_panen'] = isset($ancak[$key]['pokok_panen']) ? $ancak[$key]['pokok_panen'] : 0;
            $currentBuahStack['und'] = isset($ancak[$key]['und']) ? $ancak[$key]['und'] : 0;
            $currentBuahStack['overprn'] = isset($ancak[$key]['overprn']) ? $ancak[$key]['overprn'] : 0;
            $currentBuahStack['prsmk'] = isset($ancak[$key]['prsmk']) ? $ancak[$key]['prsmk'] : 0;
            $currentBuahStack['pokok_sample'] = isset($ancak[$key]['pokok_sample']) ? $ancak[$key]['pokok_sample'] : 0;

            // Append the current iteration values to the main $BuahStack array with the $key
            $BuahStack[$key] = $currentBuahStack;
        }

        // dd($BuahStack);
        $CalculateStack = array();
        $vcut = 0;
        $jjg = 0;
        $front = 0;
        $und  = 0;
        $overprn = 0;
        $prsmk = 0;
        $pkok_sam = 0;
        $pkok_kuning = 0;
        $bmt = 0;
        $bmk = 0;
        $vcutt = 0;
        $pkok_panen = 0;
        foreach ($BuahStack as $key => $value) {
            $vcut += $value['vst'];
            $vcutt += $value['jml_vcut'];
            $bmt += $value['bmt'];
            $bmk += $value['bmk'];
            $jjg += $value['jml_janjang'];
            $front += $value['front'];
            $und  += $value['und'];
            $overprn += $value['overprn'];
            $prsmk += $value['prsmk'];
            $pkok_sam += $value['pokok_sample'];
            $pkok_panen += $value['pokok_panen'];
            $pkok_kuning += $value['pk_kuning'];
        }
        $vcutStacks  = $jjg - $vcutt;
        $CalculateStack['frontstack'] = $pkok_panen != 0 ? round(($front / $pkok_panen) * 100, 2) : 0;
        $CalculateStack['pokok_kuning'] = $pkok_sam != 0 ? round(($pkok_kuning / $pkok_sam) * 100, 2) : 0;
        $CalculateStack['piringansmk'] = $pkok_sam != 0 ? round(($prsmk / $pkok_sam) * 100, 2) : 0;
        $CalculateStack['under'] = $pkok_sam != 0 ? round(($und / $pkok_sam) * 100, 2) : 0;
        $CalculateStack['overprun'] = $pkok_sam != 0 ? round(($overprn / $pkok_sam) * 100, 2) : 0;
        $CalculateStack['mentah_tpBrd'] = $jjg != 0 ? round(($bmt / $jjg) * 100, 2) : 0;
        $CalculateStack['mentah_krngBRD'] = $jjg != 0 ? round(($bmk / $jjg) * 100, 2) : 0;
        $CalculateStack['vcutStack'] = $jjg != 0 ? round(($vcutStacks / $jjg) * 100, 2) : 0;


        $CalculateStack['vst'] = $vcut;
        $CalculateStack['vcutStacks'] = $vcutStacks;
        $CalculateStack['TidakVcut'] = $vcutt;
        $CalculateStack['jjg_buah'] = $jjg;
        $CalculateStack['bmk'] = $bmk;
        $CalculateStack['bmt'] = $bmt;
        $CalculateStack['pokok_sample'] = $jjg;
        // dd($transReg2,$transport);
        // dd($transport);
        // Session::put('transReg2', $transReg2);
        $result = array_merge_recursive($ancak, $transport, $mutuBuah);


        // untuk kemandoran 

        $ancakM = DB::connection('mysql2')->table('mutu_ancak_new')
            ->select("mutu_ancak_new.*", DB::raw('DATE_FORMAT(mutu_ancak_new.datetime, "%M") as bulan'), DB::raw('DATE_FORMAT(mutu_ancak_new.datetime, "%Y") as tahun'))
            ->where('datetime', 'like', '%' . $date . '%')
            ->where('mutu_ancak_new.estate', $est)
            ->where('mutu_ancak_new.afdeling', $afd)

            ->get();
        $ancakM = $ancakM->groupBy(['kemandoran']);
        $ancakM = json_decode($ancakM, true);
        // dd($ancak);


        $buahM = DB::connection('mysql2')->table('mutu_buah')
            ->select("mutu_buah.*", DB::raw('DATE_FORMAT(mutu_buah.datetime, "%M") as bulan'), DB::raw('DATE_FORMAT(mutu_buah.datetime, "%Y") as tahun'))
            ->where('datetime', 'like', '%' . $date . '%')
            ->where('mutu_buah.estate', $est)
            ->where('mutu_buah.afdeling', $afd)

            ->get();
        $buahM = $buahM->groupBy(['kemandoran']);
        $buahM = json_decode($buahM, true);

        $transM = DB::connection('mysql2')->table('mutu_transport')
            ->select("mutu_transport.*", DB::raw('DATE_FORMAT(mutu_transport.datetime, "%M") as bulan'), DB::raw('DATE_FORMAT(mutu_transport.datetime, "%Y") as tahun'))
            ->where('datetime', 'like', '%' . $date . '%')
            ->where('mutu_transport.estate', $est)
            ->where('mutu_transport.afdeling', $afd)

            ->get();
        $transM = $transM->groupBy(['kemandoran']);
        $transM = json_decode($transM, true);

        // untuk reg 2 

        $ancakM2 = DB::connection('mysql2')->table('mutu_ancak_new')
            ->select("mutu_ancak_new.*", DB::raw('DATE_FORMAT(mutu_ancak_new.datetime, "%M") as bulan'), DB::raw('DATE_FORMAT(mutu_ancak_new.datetime, "%Y") as tahun'))
            ->where('datetime', 'like', '%' . $date . '%')
            ->where('mutu_ancak_new.estate', $est)
            ->where('mutu_ancak_new.afdeling', $afd)

            ->get();
        $ancakM2 = $ancakM2->groupBy(['kemandoran', 'blok']);
        $ancakM2 = json_decode($ancakM2, true);

        $transM2 = DB::connection('mysql2')->table('mutu_transport')
            ->select("mutu_transport.*", DB::raw('DATE_FORMAT(mutu_transport.datetime, "%M") as bulan'), DB::raw('DATE_FORMAT(mutu_transport.datetime, "%Y") as tahun'))
            ->where('datetime', 'like', '%' . $date . '%')
            ->where('mutu_transport.estate', $est)
            ->where('mutu_transport.afdeling', $afd)

            ->get();
        $transM2 = $transM2->groupBy(['kemandoran', 'blok']);
        $transM2 = json_decode($transM2, true);

        // dd($ancakM2);
        // end reg 2 

        // dd($transM2);
        $ancakx = array();
        $countx  = 0;
        foreach ($ancakM as $key => $value) {
            $jumPokok = 0;
            $sph = 0;
            $jml_jjg_panen = 0;
            $jml_brtp = 0;
            $jml_brtk = 0;
            $jml_brtgl = 0;
            $jml_bhts = 0;
            $jml_bhtm1 = 0;
            $jml_bhtm2 = 0;
            $jml_bhtm3 = 0;
            $jml_ps = 0;
            $listBlok = array();
            $pk_kuning = 0;
            $pr_smak = 0;
            $unprun  = 0;
            $sp = 0;
            $over_prun = 0;
            $pokok_panen = 0;
            $firstEntry = $value[0];
            foreach ($value as $key1 => $value2) {
                $jumPokok += $value2['sample'];
                if (!in_array($value2['estate'] . ' ' . $value2['afdeling'] . ' ' . $value2['blok'], $listBlok)) {
                    if ($value2['sph'] != 0) {
                        $listBlok[] = $value2['estate'] . ' ' . $value2['afdeling'] . ' ' . $value2['blok'];
                        $sph += $value2['sph'];
                    }
                }
                $jml_blok = count($listBlok);

                $jml_jjg_panen += $value2['jjg'];
                $jml_brtp += $value2['brtp'];
                $jml_brtk += $value2['brtk'];
                $jml_brtgl += $value2['brtgl'];
                $jml_bhts += $value2['bhts'];
                $jml_bhtm1 += $value2['bhtm1'];
                $jml_bhtm2 += $value2['bhtm2'];
                $jml_bhtm3 += $value2['bhtm3'];
                $jml_ps += $value2['ps'];


                // untuk bagian food stacking
                $pk_kuning += $value2['pokok_kuning'];
                $pr_smak += $value2['piringan_semak'];
                $unprun += $value2['underpruning'];
                $over_prun += $value2['overpruning'];
                $sp += $value2['sp'];
                $pokok_panen += $value2['pokok_panen'];
            }
            $jml_sph = $jml_blok == 0 ? $sph : ($sph / $jml_blok);
            $tot_brd = ($jml_brtp + $jml_brtk + $jml_brtgl);
            $tot_jjg = ($jml_bhts + $jml_bhtm1 + $jml_bhtm2 + $jml_bhtm3);
            // $luas_ha = round(($jumPokok / $jml_sph), 2);
            $luas_ha = ($jml_sph != 0) ? round(($jumPokok / $jml_sph), 2) : 0;

            if ($firstEntry['luas_blok'] != 0) {
                $first = $firstEntry['luas_blok'];
            } else {
                $first = '-';
            }
            // $luasha = round($jumPokok / $sph, 2);
            $akp = round(($jml_jjg_panen / $jumPokok) * 100, 3);

            $ancakx[$key]['luas_blok'] = $first;
            $ancakx[$key]['akp'] = $first;
            $ancakx[$key]['persenSamp'] = ($first != '-') ? round(($luas_ha / $first) * 100, 2) : '-';

            if ($reg === '2' || $reg == 2) {
                $status_panen = explode(",", $value2['status_panen']);
                $ancakx[$key]['status_panen'] = $status_panen[0];
            } else {
                $ancakx[$key]['status_panen'] = $value2['status_panen'];
            }
            $ancakx[$key]['sph'] = $sph;
            $ancakx[$key]['pokok_sample'] = $jumPokok;
            $ancakx[$key]['pokok_panen'] = $pokok_panen;
            $ancakx[$key]['luas_ha'] = $luas_ha;
            $ancakx[$key]['jml_jjg_panen'] = $jml_jjg_panen;
            if ($reg == 2 || $reg == '2') {
                $ancakx[$key]['akp_real'] = round((($jml_jjg_panen + $tot_jjg) / $jumPokok * 100), 2);
            } else {
                $ancakx[$key]['akp_real'] = count_percent($jml_jjg_panen, $jumPokok);
            }

            $ancakx[$key]['p_ma'] = $jml_brtp;
            $ancakx[$key]['k_ma'] = $jml_brtk;
            $ancakx[$key]['gl_ma'] = $jml_brtgl;
            $ancakx[$key]['total_brd_ma'] = $tot_brd;
            if ($jml_jjg_panen != 0) {
                $ancakx[$key]['btr_jjg_ma'] = round(($tot_brd / $jml_jjg_panen), 3);
            } else {
                $ancakx[$key]['btr_jjg_ma'] = 0;
            }
            $ancakx[$key]['skor_brd'] = skor_brd_ma(round(($tot_brd / $jml_jjg_panen), 3));
            $ancakx[$key]['bhts_ma'] = $jml_bhts;
            $ancakx[$key]['bhtm1_ma'] = $jml_bhtm1;
            $ancakx[$key]['bhtm2_ma'] = $jml_bhtm2;
            $ancakx[$key]['bhtm3_ma'] = $jml_bhtm3;
            $ancakx[$key]['tot_jjg_ma'] = $tot_jjg;
            if ($tot_jjg != 0) {
                $ancakx[$key]['jjg_tgl_ma'] = round(($tot_jjg / ($jml_jjg_panen + $tot_jjg)) * 100, 3);
            } else {
                $ancakx[$key]['jjg_tgl_ma'] = 0;
            }
            $ancakx[$key]['skor_buah'] = skor_buah_Ma(round(($tot_jjg / ($jml_jjg_panen + $tot_jjg)) * 100, 3));
            $ancakx[$key]['ps_ma'] = $jml_ps;

            $ancakx[$key]['PerPSMA'] = count_percent($jml_ps, $jumPokok);
            $ancakx[$key]['skor_pale'] = skor_palepah_ma(count_percent($jml_ps, $jumPokok));
            $ancakx[$key]['front'] = $sp;
            $ancakx[$key]['pk_kuning'] = $pk_kuning;
            $ancakx[$key]['und'] = $unprun;
            $ancakx[$key]['overprn'] = $over_prun;
            $ancakx[$key]['ancak_panen'] = $value2['ancak_pemanen'];
            $ancakx[$key]['prsmk'] = $pr_smak;
            $ancakx[$key]['frontstack'] = ($jumPokok != 0) ? round(($sp / $jumPokok) * 100, 3) : 0;
            $ancakx[$key]['under'] = ($jumPokok != 0) ? round(($unprun / $jumPokok) * 100, 3) : 0;
            $ancakx[$key]['overprun'] = ($jumPokok != 0) ? round(($over_prun / $jumPokok) * 100, 3) : 0;
            $ancakx[$key]['piringansmk'] = ($jumPokok != 0) ? round(($pr_smak / $jumPokok) * 100, 3) : 0;
            $ancakx[$key]['totskor_ancak'] = skor_brd_ma(round(($tot_brd / $jml_jjg_panen), 3)) + skor_buah_Ma(round(($tot_jjg / ($jml_jjg_panen + $tot_jjg)) * 100, 3)) + skor_palepah_ma(count_percent($jml_ps, $jumPokok));


            if ($first != '-') {
                $sum += $first; // Add luas_blok to the sum
                $countx++;
            }
        }


        // dd($ancakM, $ancakx);
        $transportx = array();

        foreach ($transM as $key => $value) {
            $sum_bt = 0;
            $sum_Restan = 0;
            $tph_sample = 0;
            $listBlokPerAfd = array();
            foreach ($value as $key2 => $value2) {
                // if (!in_array($value3['estate'] . ' ' . $value3['afdeling'] . ' ' . $value3['blok'], $listBlokPerAfd)) {
                $listBlokPerAfd[] = $value2['estate'] . ' ' . $value2['afdeling'] . ' ' . $value2['blok'];
                // }
                $sum_Restan += $value2['rst'];
                $tph_sample = count($listBlokPerAfd);
                $sum_bt += $value2['bt'];
            }
            $brdtph = ($tph_sample != 0) ? round($sum_bt / $tph_sample, 2) : 0;
            $rsttph = ($tph_sample != 0) ? round($sum_Restan / $tph_sample, 2) : 0;

            $transportx[$key]['reg'] = 'reg1/reg3';
            $transportx[$key]['bt_total'] = $sum_bt;
            $transportx[$key]['restan_total'] = $sum_Restan;
            $transportx[$key]['tph_sample'] = $tph_sample;
            $transportx[$key]['bt_tph'] = $brdtph;
            $transportx[$key]['restan_tph'] = $rsttph;
            $transportx[$key]['skor_bt'] = skor_brd_tinggal($brdtph);
            $transportx[$key]['skor_restan'] = skor_buah_tinggal($rsttph);
            $transportx[$key]['tot_skortra'] = skor_buah_tinggal($rsttph) + skor_brd_tinggal($brdtph);
        }


        // if ($reg == 2 || $reg == '2') {

        //     // $ancak_status = $ancak[''];
        //     foreach ($transM as $key => $value) {
        //         $sum_bt = 0;
        //         $sum_Restan = 0;
        //         $tph_sample = 0;
        //         $listBlokPerAfd = array();
        //         foreach ($value as $key2 => $value2) {
        //             // if (!in_array($value3['estate'] . ' ' . $value3['afdeling'] . ' ' . $value3['blok'], $listBlokPerAfd)) {
        //             $listBlokPerAfd[] = $value2['estate'] . ' ' . $value2['afdeling'] . ' ' . $value2['blok'];
        //             // }
        //             $sum_Restan += $value2['rst'];
        //             $tph_sample = count($listBlokPerAfd);
        //             $sum_bt += $value2['bt'];
        //             // dd($value2);
        //         }

        //         $panenKey = 0;

        //         if (isset($ancak[$key]['status_panen'])) {
        //             $transportx[$key]['status_panen'] = $ancak[$key]['status_panen'];
        //             $panenKey = $ancak[$key]['status_panen'];
        //             $transportx[$key]['status_panentrans'] = $value2['status_panen'];
        //             $transportx[$key]['status_panenAncak'] = $ancak[$key]['status_panen'];
        //         }
        //         $LuasKey = 0;
        //         if (isset($ancak[$key]['luas_blok'])) {
        //             $transportx[$key]['luas_blok'] = $ancak[$key]['luas_blok'];
        //             $LuasKey = $ancak[$key]['luas_blok'];
        //         }

        //         if (isset($panenKey) && $panenKey <= 3 && isset($ancak[$key]['luas_blok'])) {
        //             $transportx[$key]['tph_sample'] = round($LuasKey * 1.3);
        //         } else {
        //             $transportx[$key]['tph_sample'] = $tph_sample;
        //         }


        //         $transportx[$key]['reg'] = $reg;
        //         $transportx[$key]['status_panen'] = $value2['status_panen'];
        //         $transportx[$key]['tph_sampleTrans'] = $tph_sample;
        //         $transportx[$key]['estate'] = $value2['estate'];
        //         $transportx[$key]['afdeling'] = $value2['afdeling'];
        //         $transportx[$key]['bt_total'] = $sum_bt;
        //         $transportx[$key]['restan_total'] = $sum_Restan;
        //         $transportx[$key]['skor'] = ($tph_sample != 0) ? round($sum_bt / $tph_sample, 2) : 0;
        //         $transportx[$key]['skor_restan'] = ($tph_sample != 0) ? round($sum_Restan / $tph_sample, 2) : 0;
        //     }
        //     // Add this code after your existing foreach loop


        //     $transReg2 = array();
        //     $tph_sample = 0;
        //     foreach ($transport as $key => $value) {
        //         # code...
        //         $tph_sample += $value['tph_sample'];
        //         // dd($value);
        //     }
        //     if (isset($value['estate'])) {
        //         if (!isset($transReg2[$value['estate']])) {
        //             $transReg2[$value['estate']] = [];
        //         }

        //         if (!isset($transReg2[$value['estate']][$value['afdeling']])) {
        //             $transReg2[$value['estate']][$value['afdeling']] = [];
        //         }

        //         $transReg2[$value['estate']][$value['afdeling']]['tph_sample'] = $tph_sample;
        //     }


        //     foreach ($ancak as $key => $value) {
        //         if (!array_key_exists($key, $transport)) {
        //             $transport[$key]['status_panen'] = $value['status_panen'];
        //             $transport[$key]['luas_blok'] = $value['luas_blok'];

        //             if ($value['status_panen'] <= 3) {
        //                 $transport[$key]['tph_sample'] = round($value['luas_blok'] * 1.3, 2);
        //             } else {
        //                 $transport[$key]['tph_sample'] = $value['status_panen'];
        //             }
        //             $transport[$key]['bt_total'] = 0;
        //             $transport[$key]['restan_total'] = 0;
        //             $transport[$key]['skor'] = 0;
        //             $transport[$key]['skor_restan'] = 0;
        //         }
        //     }
        // }

        $mutuBuahx = array();
        foreach ($buahM as $key => $value) {
            $listBlokPerAfd = array();
            $janjang = 0;
            $Jjg_Mth = 0;
            $Jjg_Mth2 = 0;
            $Jjg_Over = 0;
            $Jjg_Empty = 0;
            $Jjg_Abr = 0;
            $Jjg_Vcut = 0;
            $Jjg_Als = 0;
            $dtBlok = count($value);
            $count_alas_br_1 = 0;
            $count_alas_br_0 = 0;
            $vcutStack = 0;
            foreach ($value as $key2 => $value2) {

                if (!in_array($value2['blok'], $listBlokPerAfd)) {
                    $listBlokPerAfd[] = $value2['blok'];
                }
                $janjang += $value2['jumlah_jjg'];
                $Jjg_Mth += $value2['bmt'];
                $Jjg_Mth2 += $value2['bmk'];
                $Jjg_Over += $value2['overripe'];
                $Jjg_Empty += $value2['empty_bunch'];
                $Jjg_Abr += $value2['abnormal'];
                $Jjg_Vcut += $value2['vcut'];
                $Jjg_Als += $value2['alas_br'];

                if ($value2['alas_br'] == 1) {
                    $count_alas_br_1++;
                } elseif ($value2['alas_br'] == 0) {
                    $count_alas_br_0++;
                }
            }
            //untuk food stacking
            $vcutStack = $janjang - $Jjg_Vcut;

            $jml_mth = ($Jjg_Mth + $Jjg_Mth2);
            $jml_mtg = $janjang - ($jml_mth + $Jjg_Over + $Jjg_Empty + $Jjg_Abr);

            $mutuBuahx[$key]['blok_mb'] = $dtBlok;
            $mutuBuahx[$key]['status_panen'] = $value2['status_panen'];
            $mutuBuahx[$key]['alas_mb'] = $Jjg_Als;
            $mutuBuahx[$key]['bmt'] = $Jjg_Mth;
            $mutuBuahx[$key]['bmk'] = $Jjg_Mth2;
            $mutuBuahx[$key]['jml_janjang'] = $janjang;
            $mutuBuahx[$key]['jml_mentah'] = $jml_mth;
            $mutuBuahx[$key]['jml_masak'] = $jml_mtg;
            $mutuBuahx[$key]['jml_over'] = $Jjg_Over;
            $mutuBuahx[$key]['jml_empty'] = $Jjg_Empty;
            $mutuBuahx[$key]['jml_abnormal'] = $Jjg_Abr;
            $mutuBuahx[$key]['jml_vcut'] = $Jjg_Vcut;
            $mutuBuahx[$key]['jml_krg_brd'] = $dtBlok == 0 ? $Jjg_Als : round($Jjg_Als / $dtBlok, 2);
            $denom = ($janjang - $Jjg_Abr) != 0 ? ($janjang - $Jjg_Abr) : 1;

            $PersenBuahMentah = $denom != 0 ? round(($jml_mth / $denom) * 100, 2) : 0;
            $mutuBuahx[$key]['PersenBuahMentah'] = $PersenBuahMentah;
            $mutuBuahx[$key]['skorbh_mentah'] = skor_buah_mentah_mb($PersenBuahMentah);
            $PersenBuahMasak = $denom != 0 ? round(($jml_mtg / $denom) * 100, 2) : 0;
            $mutuBuahx[$key]['PersenBuahMasak'] = $PersenBuahMasak;
            $mutuBuahx[$key]['skorbh_masak'] = skor_buah_masak_mb($PersenBuahMasak);
            $PersenBuahOver = $denom != 0 ? round(($Jjg_Over / $denom) * 100, 2) : 0;
            $mutuBuahx[$key]['PersenBuahOver'] = $PersenBuahOver;
            $mutuBuahx[$key]['skorbh_over'] = skor_buah_over_mb($PersenBuahOver);
            $PersenPerempty =  $denom != 0 ? round(($Jjg_Empty / $denom) * 100, 2) : 0;
            $mutuBuahx[$key]['PersenPerempty'] = $PersenPerempty;
            $mutuBuahx[$key]['skorbh_empty'] = skor_jangkos_mb($PersenPerempty);
            $PersenVcut = count_percent($Jjg_Vcut, $janjang);
            $mutuBuahx[$key]['PersenVcut'] = $PersenVcut;
            $mutuBuahx[$key]['skorbh_vcut'] = skor_vcut_mb($PersenVcut);
            $mutuBuahx[$key]['PersenAbr'] = count_percent($Jjg_Abr, $janjang);
            $PersenKrgBrd =  count_percent($count_alas_br_1, $dtBlok);
            $mutuBuahx[$key]['PersenKrgBrd'] = $PersenKrgBrd;
            $mutuBuahx[$key]['skorkarung'] = skor_abr_mb($PersenKrgBrd);
            $mutuBuahx[$key]['count_alas_br_1'] = $count_alas_br_1;
            $mutuBuahx[$key]['count_alas_br_0'] = $count_alas_br_0;
            $mutuBuahx[$key]['vst'] = $vcutStack;
            $mutuBuahx[$key]['vcutStack'] = $janjang != 0 ? round(($vcutStack / $janjang) * 100, 2) : 0;
            $mutuBuahx[$key]['tot_skorbuah'] = skor_buah_mentah_mb($PersenBuahMentah) +
                skor_buah_masak_mb($PersenBuahMasak) +
                skor_buah_over_mb($PersenBuahOver) +
                skor_jangkos_mb($PersenPerempty) +
                skor_vcut_mb($PersenVcut) +
                skor_abr_mb($PersenKrgBrd);
        }
        $resultKemandoran = array_merge_recursive($ancakx, $transportx, $mutuBuahx);

        // dd($transportx);
        if ($reg != 2 || $reg != '2') {
            $tot_mntol = [];
            $totsample = 0;
            $totha = 0;
            $totpanen = 0;
            $p_ma = 0;
            $k_ma = 0;
            $gl_ma = 0;
            $bhts_ma = 0;
            $bhtm1_ma = 0;
            $bhtm2_ma = 0;
            $bhtm3_ma = 0;
            $ps_ma = 0;
            $bt_total = 0;
            $restan_total = 0;
            $blok_mb = 0;
            $alas_mb = 0;
            $jml_janjang = 0;
            $jml_mentah = 0;
            $jml_masak = 0;
            $jml_over = 0;
            $jml_empty = 0;
            $jml_mtgx = 0;
            $jml_vcut = 0;
            $tph_samplex = 0;
            $tph_samplebuah = 0;
            foreach ($resultKemandoran as $key => $value) {
                # code...
                // dd($value);
                $totsample += $value['pokok_sample'] ?? 0;
                $totha += $value['luas_ha'] ?? 0;
                $totpanen += $value['jml_jjg_panen'] ?? 0;

                $akp = round(($totpanen / $totsample) * 100, 3);

                $p_ma += $value['p_ma'] ?? 0;
                $k_ma += $value['k_ma'] ?? 0;
                $gl_ma += $value['gl_ma'] ?? 0;

                $tod_brd = $p_ma + $k_ma + $gl_ma;
                $brd_jgg = round(($tod_brd / $totpanen), 3);
                $skor_brd = skor_brd_ma($brd_jgg);

                $bhts_ma += $value['bhts_ma'] ?? 0;
                $bhtm1_ma += $value['bhtm1_ma'] ?? 0;
                $bhtm2_ma += $value['bhtm2_ma'] ?? 0;
                $bhtm3_ma += $value['bhtm3_ma'] ?? 0;

                $tod_buah = $bhts_ma + $bhtm1_ma + $bhtm2_ma + $bhtm3_ma;
                $buah_jjg = round(($tod_buah / ($totpanen + $tod_buah)) * 100, 3);
                $skor_buah = skor_buah_Ma($buah_jjg);

                $ps_ma += $value['ps_ma'] ?? 0;

                $persen_ps = round(($ps_ma / $totsample) * 100, 3);
                $skor_ps = skor_palepah_ma($persen_ps);

                $totskor_ancak = $skor_brd + $skor_buah + $skor_ps;

                // transport 

                $tph_samplex += $value['tph_sample'] ?? 0;
                $bt_total += $value['bt_total'] ?? 0;

                $bt_tph = ($tph_samplex != 0) ? round($bt_total / $tph_samplex, 3) : 0;
                $skor_bt = skor_brd_tinggal($bt_tph);
                $restan_total += $value['restan_total'] ?? 0;
                $restan_tph = ($tph_samplex != 0) ? round($restan_total / $tph_samplex, 3) : 0;
                $skor_restan = skor_buah_tinggal($restan_tph);
                $tot_skortra = $skor_bt + $skor_restan;

                // mutubuah 
                $tph_samplebuah += $value['blok_mb'] ?? 0;
                $jml_janjang += $value['jml_janjang'] ?? 0;
                $jml_mentah += $value['jml_mentah'] ?? 0;
                $jml_mtgx += $value['jml_masak'] ?? 0;
                $Jjg_Abr += $value['jml_abnormal'] ?? 0;
                $jml_empty += $value['jml_empty'] ?? 0;
                $jml_vcut += $value['jml_vcut'] ?? 0;
                $jml_over += $value['jml_over'] ?? 0;

                $alas_mb += $value['alas_mb'] ?? 0;

                $denom = ($jml_janjang - $Jjg_Abr) != 0 ? ($jml_janjang - $Jjg_Abr) : 1;

                $PersenBuahMentah = $denom != 0 ? round(($jml_mentah / $denom) * 100, 3) : 0;
                $skorbh_mentah = skor_buah_mentah_mb($PersenBuahMentah);
                $PersenBuahMasak = $denom != 0 ? round(($jml_mtgx / $denom) * 100, 3) : 0;
                $skorbh_masak = skor_buah_masak_mb($PersenBuahMasak);
                $PersenPerempty = $denom != 0 ? round(($jml_empty / $denom) * 100, 3) : 0;
                $skorbh_empty = skor_jangkos_mb($PersenPerempty);
                $PersenVcut = $jml_janjang != 0 ? round(($jml_vcut / $jml_janjang) * 100, 3) : 0;
                $skorbh_vcut = skor_vcut_mb($PersenVcut);
                $PersenAbr = count_percent($Jjg_Abr, $jml_janjang);
                $PersenKrgBrd = count_percent($alas_mb, $tph_samplebuah);
                $skorkarung = skor_abr_mb($PersenKrgBrd);
                $PersenBuahOver = $denom != 0 ? round(($jml_over / $denom) * 100, 3) : 0;
                $skorbh_over = skor_buah_over_mb($PersenBuahOver);

                $tot_skorbuah = $skorbh_mentah + $skorbh_masak + $skorbh_empty + $skorbh_vcut +  $skorkarung + $skorbh_over;

                $totalall = $tot_skorbuah + $totskor_ancak + $tot_skortra;
                $kat = sidak_akhir($totalall);
            }

            $tot_mntol[] = [
                'pokok_sample' => $totsample,
                'luas_ha' => $totha,
                'pokok_panen' => $totpanen,
                'akp' => $akp,
                'p_ma' => $p_ma,
                'k_ma' => $k_ma,
                'gl_ma' => $gl_ma,
                'total_brd_ma' => $tod_brd,
                'btr_jjg_ma' => $brd_jgg,
                'skor_brd' => $skor_brd,
                'bhts_ma' => $bhts_ma,
                'bhtm1_ma' => $bhtm1_ma,
                'bhtm2_ma' => $bhtm2_ma,
                'bhtm3_ma' => $bhtm3_ma,
                'tot_jjg_ma' => $tod_buah,
                'jjg_tgl_ma' => $buah_jjg,
                'skor_buah' => $skor_buah,
                'ps_ma' => $ps_ma,
                'PerPSMA' => $persen_ps,
                'skor_pale' => $skor_ps,
                'totskor_ancak' => $totskor_ancak,
                'tph_sample' => $tph_samplex,
                'bt_total' => $bt_total,
                'bt_tph' => $bt_tph,
                'skor_bt' => $skor_bt,
                'restan_total' => $restan_total,
                'restan_tph' => $restan_tph,
                'skor_restan' => $skor_restan,
                'tot_skortra' => $tot_skortra,
                'blok_mb' => $tph_samplebuah,
                'jml_janjang' => $jml_janjang,
                'jml_mentah' => $jml_mentah,
                'PersenBuahMentah' => $PersenBuahMentah,
                'skorbh_mentah' => $skorbh_mentah,
                'jml_masak' => $jml_mtgx,
                'PersenBuahMasak' => $PersenBuahMasak,
                'skorbh_masak' => $skorbh_masak,
                'jml_over' => $jml_over,
                'PersenBuahOver' => $PersenBuahOver,
                'skorbh_over' => $skorbh_over,
                'jml_empty' => $jml_empty,
                'PersenPerempty' => $PersenPerempty,
                'skorbh_empty' => $skorbh_empty,
                'jml_vcut' => $jml_vcut,
                'PersenVcut' => $PersenVcut,
                'skorbh_vcut' => $skorbh_vcut,
                'jml_abnormal' => $Jjg_Abr,
                'PersenAbr' => $PersenAbr,
                'alas_mb' => $alas_mb,
                'PersenKrgBrd' => $PersenKrgBrd,
                'skorkarung' => $skorkarung,
                'tot_skorbuah' => $tot_skorbuah,
                'totalall' => $totalall,
                'kategori' => $kat,
            ];
            $values = array_values($tot_mntol[0]);

            // Extracting keys of $tot_mntol[0]
            $keys = array_keys($tot_mntol[0]);


            $resultKemandoran['Total'] = array_combine($keys, $values);
        } else {
            $resultKemandoran[] = [];
        }




        // dd($tot_mntol, $resultKemandoran);


        // dd($resultKemandoran, $result);
        //     dd($transport);
        // dd($ancak,$transport,$mutuBuah);
        $arrView = array();
        $arrView['hitung'] =  $CalculateStack;
        $arrView['mutuAncak'] =  $ancak;
        $arrView['avg'] =  $avg;
        $arrView['sph_avg'] = $average;
        $arrView['mutuBuah'] =  $mutuBuah;
        $arrView['mutuTransport'] =  $transport;
        $arrView['est'] =  $est;
        $arrView['afd'] =  $afd;
        $arrView['reg'] =  $reg;
        $arrView['tanggal'] =  $date;
        $arrView['ancak_trans'] =  $newVariable;
        $arrView['data_chuack'] =  $result;
        $arrView['tabelmandor'] =  $resultKemandoran;



        echo json_encode($arrView);
        exit();
    }

    public function getimgqc(Request $request)
    {
        $date = $request->input('date');
        $reg = $request->input('reg');
        $title = $request->input('title');
        $href = $request->input('href');
        $tables = [];

        // Retrieve the base64 data from the request
        for ($i = 1; $i <= 4; $i++) {
            $tableData = $request->input("table$i");
            $tables["table$i"] = $tableData;
        }

        // Process the base64 data as needed

        // dd($title);

        // Return a response, for example, to indicate success
        return view('layoutimgqc', [
            'date' => $date,
            'reg' => $reg,
            'title' => $title,
            'href' => $href,
            'tables' => $tables,
        ]);
    }

    public function excelqcinspeksi($reg, $month)
    {
        $regional = $reg;
        $bulan = $month;

        // dd($bulan);
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
                    if ($est['est'] === 'LDE' || $est['est'] === 'SRE' || $est['est'] === 'SKE') {
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
                    if ($est['est'] === 'LDE' || $est['est'] === 'SRE' || $est['est'] === 'SKE') {
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
                    $nilai_input = 0;
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
                        $perPl = round(($totalpelepah_s / $totalPokok) * 100, 3);
                    } else {
                        $perPl = 0;
                    }





                    $nonZeroValues = array_filter([$totalP_panen, $totalK_panen, $totalPTgl_panen, $totalbhts_panen, $totalbhtm1_panen, $totalbhtm2_panen, $totalbhtm3_oanen]);

                    if (!empty($nonZeroValues)) {
                        $rekap[$key][$key1][$key2]['check_datacak'] = 'ada';
                        // $rekap[$key][$key1][$key2]['skor_brd'] = $skor_brd = skor_brd_ma($brdPerjjg);
                        // $rekap[$key][$key1][$key2]['skor_ps'] = $skor_ps = skor_palepah_ma($perPl);
                    } else {
                        $rekap[$key][$key1][$key2]['check_datacak'] = 'kosong';
                        // $rekap[$key][$key1][$key2]['skor_brd'] = $skor_brd = 0;
                        // $rekap[$key][$key1][$key2]['skor_ps'] = $skor_ps = 0;
                    }

                    // $ttlSkorMA = $skor_bh + $skor_brd + $skor_ps;
                    $ttlSkorMA =  skor_buah_Ma($sumPerBH) + skor_brd_ma($brdPerjjg) + skor_palepah_ma($perPl);

                    $rekap[$key][$key1][$key2]['pokok_samplecak'] = $totalPokok;
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
                    // $rekap[$key][$key1]['check_data'] = 'ada';
                    $check_data = 'ada';
                    // $rekap[$key][$key1]['skor_brd'] = $skor_brd = skor_brd_ma($brdPerjjgEst);
                    // $rekap[$key][$key1]['skor_ps'] = $skor_ps = skor_palepah_ma($perPlEst);
                } else {
                    // $rekap[$key][$key1]['check_data'] = 'kosong';
                    $check_data = 'kosong';
                    // $rekap[$key][$key1]['skor_brd'] = $skor_brd = 0;
                    // $rekap[$key][$key1]['skor_ps'] = $skor_ps = 0;
                }

                // $totalSkorEst = $skor_bh + $skor_brd + $skor_ps;

                $totalSkorEst =  skor_brd_ma($brdPerjjgEst) + skor_buah_Ma($sumPerBHEst) + skor_palepah_ma($perPlEst);
                //PENAMPILAN UNTUK PERESTATE
                $rekap[$key][$key1]['est']['estancak'] = [
                    'pokok_samplecak' => $pokok_panenEst,
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

                if ($key1 === 'LDE' || $key1 === 'SRE' || $key1 === 'SKE') {

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

            $rekap[$key]['wil']['wilancak'] = [
                'data' =>  $data,
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
                'jjgperBuahcak' =>  number_format($sumPerBH, 3),
                'palepah_pokokcak' =>  $pelepah_swil,
                'palepah_percak' =>  $perPiWil,
                'skor_bhcak' =>  skor_buah_Ma($sumPerBHWil),
                'skor_brdcak' =>  skor_brd_ma($brdPerwil),
                'skor_pscak' =>  skor_palepah_ma($perPiWil),
                'skor_akhircak' =>  $totalWil,
                'est' => $key,
                'afd' => 'wil',
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
                        // $rekap[$key][$key1][$key2]['skor_masak'] = $skor_masak =  skor_buah_masak_mb($PerMsk);
                        // $rekap[$key][$key1][$key2]['skor_over'] = $skor_over =  skor_buah_over_mb($PerOver);
                        // $rekap[$key][$key1][$key2]['skor_jjgKosong'] = $skor_jjgKosong =  skor_jangkos_mb($Perkosongjjg);
                        // $rekap[$key][$key1][$key2]['skor_vcut'] = $skor_vcut =  skor_vcut_mb($PerVcut);
                        // $rekap[$key][$key1][$key2]['skor_kr'] = $skor_kr =  skor_abr_mb($per_kr);
                    } else {
                        $rekap[$key][$key1][$key2]['check_databh'] = 'kosong';
                        // $rekap[$key][$key1][$key2]['skor_masak'] = $skor_masak = 0;
                        // $rekap[$key][$key1][$key2]['skor_over'] = $skor_over = 0;
                        // $rekap[$key][$key1][$key2]['skor_jjgKosong'] = $skor_jjgKosong = 0;
                        // $rekap[$key][$key1][$key2]['skor_vcut'] = $skor_vcut =  0;
                        // $rekap[$key][$key1][$key2]['skor_kr'] = $skor_kr = 0;
                    }

                    // $totalSkor = $skor_mentah + $skor_masak + $skor_over + $skor_jjgKosong + $skor_vcut + $skor_kr;


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
                // if ($sum_kr != 0) {
                //     $total_kr = round($sum_kr / $dataBLok, 3);
                // } else {
                //     $total_kr = 0;
                // }

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
                    if ($est['est'] === 'LDE' || $est['est'] === 'SRE' || $est['est'] === 'SKE') {
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
        // dd($rekap[3]);

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

        if ($regional == 1) {
            $muaarray = [
                'SRE' => $rekap[3]['SRE']['estate'] ?? [],
                'LDE' => $rekap[3]['LDE']['estate'] ?? [],
                'SKE' => $rekap[3]['SKE']['estate'] ?? [],
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


            $rekap[3]['MUA']['PT.MUA'] = $resultmua;


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
        // dd($rekap);

        return view('Pdf.qcinspeksiexcel', ['data' => $rekap, 'reg' => $regional, 'bulan' => $bulan, 'datareg' => $dataReg]);
    }

    public function verifdata(Request $request)
    {
        // Retrieve input values from the request
        $Tanggal = $request->input('Tanggal');
        $est = $request->input('est');
        $afd = $request->input('afd');
        $menu = $request->input('menu');

        // dd($afd);
        $status = DB::connection('mysql2')->table('verification')
            ->where('est', $est)
            ->where('afd', $afd)
            ->where('menu', $menu)
            ->where('datetime', 'LIKE', '%' . $Tanggal . '%')
            ->get();

        // dd($status);

        if ($status->isEmpty()) {
            return response()->json('not_approved_all');
        } else {
            $verifby_askep = $status[0]->verifby_askep;
            $verifby_manager = $status[0]->verifby_manager;
            $verifby_asisten = ($status[0]->verifby_asisten == 1) ?  true : false;

            $askep_manager = ($verifby_manager == 1 || $verifby_askep == 1) ? true : false;


            if ($askep_manager && $verifby_asisten) {
                return response()->json('all_approved');
            } elseif ($askep_manager && !$verifby_asisten) {
                return response()->json('asisten_not_approved');
            } elseif (!$askep_manager && $verifby_asisten) {
                return response()->json('askep_manager_not_approved');
            } elseif (!$askep_manager && !$verifby_asisten) {
                return response()->json('not_approved_all');
            } else {
                return response()->json('condition_not_met');
            }
        }
    }

    public function verifaction(Request $request)
    {
        // Retrieve input values from the request
        $Tanggal = $request->input('Tanggal');
        $est = $request->input('est');
        $afd = $request->input('afd');
        $menu = $request->input('menu');
        $jabatan = $request->input('jabatan');
        $nama = $request->input('nama');
        $action = $request->input('action');
        $tanggal_approve = $request->input('tanggal_approve');
        $departemen = $request->input('departemen');
        $lokasikerja = $request->input('lokasikerja');
        // dd($action, $nama, $jabatan);
        try {
            DB::beginTransaction();

            // Retrieve current verification status
            $currentStatus = DB::connection('mysql2')->table('verification')
                ->where([
                    'est' => $est,
                    'afd' => $afd,
                    'datetime' => $Tanggal,
                    'menu' => $menu,
                ])->first();

            $verifby_askep = $jabatan === 'Askep' ? 1 : 0;
            $verifby_manager = $jabatan === 'Manager' ? 1 : 0;
            $verifby_asisten = $jabatan === 'Asisten' ? 1 : 0;

            if ($currentStatus == null) {
                $data = [
                    'est' => $est,
                    'afd' => $afd,
                    'datetime' => $Tanggal,
                    'menu' => $menu,
                    'verifby_askep' => $verifby_askep,
                    'verifby_manager' => $verifby_manager,
                    'verifby_asisten' => $verifby_asisten,
                    'action' => $action,
                ];

                if ($jabatan === 'Askep') {
                    $data['detail_askep'] = $departemen;
                    $data['nama_askep'] = $nama;
                    $data['approve_askep'] = $tanggal_approve;
                    $data['lok_askep'] = $lokasikerja;
                } elseif ($jabatan === 'Asisten') {
                    $data['detail_asisten'] = $departemen;
                    $data['nama_asisten'] = $nama;
                    $data['approve_asisten'] = $tanggal_approve;
                    $data['lok_asisten'] = $lokasikerja;
                } else {
                    $data['detail_manager'] = $departemen;
                    $data['nama_maneger'] = $nama;
                    $data['approve_maneger'] = $tanggal_approve;
                    $data['lok_manager'] = $lokasikerja;
                }

                DB::connection('mysql2')->table('verification')->insert($data);


                DB::commit();
                return response()->json('success', 200);
            } else {
                if ($jabatan === 'Askep') {
                    DB::connection('mysql2')->table('verification')->where('id', $currentStatus->id)->update([
                        'verifby_askep' => $verifby_askep,
                        'detail_askep' => $departemen,
                        'nama_askep' => $nama,
                        'approve_askep' => $tanggal_approve,
                        'lok_askep' => $lokasikerja,
                    ]);
                } else if ($jabatan === 'Asisten') {
                    DB::connection('mysql2')->table('verification')->where('id', $currentStatus->id)->update([
                        'verifby_asisten' => $verifby_asisten,
                        'detail_asisten' => $departemen,
                        'nama_asisten' => $nama,
                        'approve_asisten' => $tanggal_approve,
                        'lok_asisten' => $lokasikerja,
                    ]);
                } else if ($jabatan === 'Manager') {
                    DB::connection('mysql2')->table('verification')->where('id', $currentStatus->id)->update([
                        'verifby_manager' => $verifby_manager,
                        'detail_manager' => $departemen,
                        'nama_maneger' => $nama,
                        'approve_maneger' => $tanggal_approve,
                        'lok_manager' => $lokasikerja,
                    ]);
                } else {
                    // Handle other cases or return an error response
                    DB::rollback();
                    return response()->json('error', 400);
                }

                DB::commit();
                return response()->json('success', 200);
            }
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json($th->getMessage(), 500);
        }
    }
}
