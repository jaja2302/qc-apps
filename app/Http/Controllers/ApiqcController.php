<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Afdeling;
use App\Models\Blok;
use App\Models\Estate;
use App\Models\Regional;
use App\Models\Wilayah;
use App\Models\historycron;
use Carbon\Carbon;

class ApiqcController extends Controller
{
    public function getHistoryedit(Request $request)
    {
        // Get the ID parameter from the request
        $requestedId = (int) $request->input('id');

        // Get the latest ID from the database
        $latestId = DB::connection('mysql2')
            ->table('history_edit')
            ->max('id');

        // Check if the requested ID matches the latest ID
        if ($requestedId == $latestId) {
            // If they match, return an empty response
            return response()->json([]);
        }

        // Query to retrieve new data since the requested ID
        $query = DB::connection('mysql2')
            ->table('history_edit')
            ->select('id', 'nama_user', 'tanggal', 'menu')
            ->where('id', '>', $requestedId)
            ->get();

        // Convert the query result to an array
        $result = $query->toArray();

        // Return the result as JSON response
        return response()->json($result);
    }

    public function plotmaps(Request $request)
    {
        $estate_input = $request->input('estate');

        $tgl = $request->input('date');
        // $estate_input = 'SPE';

        //     $tgl = '2024-03-13';    

        // $test = [
        //     'est' => $request->input('estate'),
        //     'tgl' => $request->input('date')
        // ];

        // return response()->json($test);

        function isPointInPolygon($point, $polygon)
        {

            $x = $point[0];
            $y = $point[1];

            // dd($polygon);
            $vertices = array_map(function ($vertex) {
                return explode(',', $vertex);
            }, explode('$', $polygon));

            // dd($vertices);

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


        $queryData = DB::connection('mysql2')->table('taksasi')
            ->select('taksasi.*')
            ->whereDate('taksasi.waktu_upload', $tgl)
            ->where('lokasi_kerja', $estate_input)
            ->orderBy('taksasi.waktu_upload', 'desc')
            ->get();

        $markers = [];

        foreach ($queryData as $row) {
            $lat = floatval($row->lat_awal);
            $lon = floatval($row->lon_awal);
            $markers[] = [$lat, $lon];
        }
        $markers = array_values($markers);


        $estateQuery = Estate::with("afdeling")->where('est', $estate_input)->get();

        // dd($estateQuery);

        $polygons = array();
        $listBlok = [];
        foreach ($estateQuery as $key => $value) {
            foreach ($value->afdeling as $key2 => $data) {
                foreach ($data as $value2) {
                    // dd($value2);
                    $data2 = Afdeling::with("blok")->find($data->id)->blok;
                    foreach ($data2 as $value2) {
                        $nama = $value2->nama;
                        $latln = $value2->lat . ',' . $value2->lon;

                        if (!isset($polygons[$nama])) {
                            $polygons[$nama] = $latln;
                            $listBlok[] = $nama;
                        } else {
                            $polygons[$nama] .= '$' . $latln;
                        }
                    }
                }
            }
        }

        $polygons = array_values($polygons);

        // dd($polygons);




        $finalResultBlok = [];

        // dd($polygons, $markers);
        foreach ($polygons as $key => $polygon) {
            foreach ($markers as $index => $marker) {
                // dd($marker, $polygon);
                if (isPointInPolygon($marker, $polygon)) {

                    $finalResultBlok[] = $listBlok[$key];
                }
            }
        }
        $finalResultBlok = array_unique($finalResultBlok);

        // dd($finalResultBlok);
        // // //get lat lang dan key $result_blok atau semua list_blok

        $blokLatLn = array();
        $inc = 0;
        foreach ($finalResultBlok as $key => $value) {


            $query = DB::connection('mysql2')->table('blok')
                ->select('blok.*', 'estate.est', 'afdeling.nama as nama_afdeling')
                ->join('afdeling', 'blok.afdeling', '=', 'afdeling.id')
                ->join('estate', 'afdeling.estate', '=', 'estate.id')
                ->where('estate.est', $estate_input)
                ->where('blok.nama', $value)
                ->get();

            $latln = '';

            foreach ($query as $key2 => $data) {
                $latln .= '[' . $data->lon . ',' . $data->lat . '],';
                $estate = DB::connection('mysql2')->table('estate')
                    ->select('estate.*')
                    ->where('estate.est', $estate_input)
                    ->first();

                $nama_estate = $estate->nama;


                // dd($latln);
                $blokLatLn[$inc]['blok'] = $data->nama;
                $blokLatLn[$inc]['estate'] = $nama_estate;
                $blokLatLn[$inc]['afdeling'] = $data->nama_afdeling;
                $blokLatLn[$inc]['latln'] = rtrim($latln, ',');
            }
            $inc++;
        }

        // $test = [
        //     'est' => $estate_input,
        //     'tgl' =>  $tgl
        // ];

        // return response()->json($test);
        // return response()->json($blokLatLn);


        return view('Api/generatemaps', [
            'blokLatLn' => $blokLatLn,
            // 'list_wilayah' => $queryWill,
            // 'optYear' => $optYear,
            // 'list_month' => $listMonth,
            // 'option_reg' => $optionREg,
            // 'check' => $check,
            // 'idduplicate' => $records,
            // 'check_data' => $getdata,
        ]);
    }

    public function testapi()
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://dawhatsappservices.srs-ssms.com/send-group-message',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array('message' => 'testkirimapi', 'id_group' => '120363205553012899@g.us'),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
    }

    public function getdatacron()
    {

        $getdata = DB::connection('mysql2')->table('crontab')
            ->select('*')
            ->get();

        return response()->json($getdata);
    }

    public function recordcronjob(Request $request)
    {
        $estate = $request->input('est');
        $datetime = $request->input('datetime');
        $id = $request->input('id');

        if (!$estate || !$datetime) {
            return response()->json(['error' => 'Invalid data provided.'], 400);
        }

        try {
            DB::beginTransaction();

            $newdata = Historycron::find($id);
            $newdata->last_update = $datetime;
            $newdata->save();

            DB::commit();

            return response()->json(['success' => true], 200);
        } catch (\Throwable $th) {
            DB::rollBack();

            if ($th instanceof \Illuminate\Database\QueryException) {
                return response()->json(['error' => 'Database error.'], 500);
            }

            return response()->json(['error' => 'An error occurred while saving data. Please try again.'], 500);
        }
    }

    // public function checkcronjob()
    // {
    //     $time = Carbon::now('Asia/Jakarta');
    //     $hours = $time->format('H:i:s');
    //     $ymd = $time->format('Y-m-d H:i:s');

    //     $startTime = Carbon::createFromTime(6, 0, 0)->format('H:i:s');
    //     $yearmonth = $time->format('Y-m-d') . ' ' . $startTime;

    //     // Query to get data between 06:00:00 and the current time
    //     $getdatacron = DB::connection('mysql2')->table('crontab')
    //         ->select('*')
    //         ->whereBetween('datetime', [$startTime, $hours])
    //         ->get();

    //     $gethistorycron = DB::connection('mysql2')->table('cron_history')
    //         ->select('*')
    //         ->whereBetween('datetime', [$yearmonth, $ymd])
    //         ->get();

    //     $cronfail = [];
    //     foreach ($getdatacron as $value) {
    //         $foundInHistory = false;
    //         foreach ($gethistorycron as $value1) {
    //             if ($value->estate == $value1->estate) {
    //                 $foundInHistory = true;
    //                 break; // Exit the inner loop if found in history
    //             }
    //         }
    //         if (!$foundInHistory) {
    //             $cronfail[] = $value; // Add to cronfail if not found in history
    //         }
    //     }

    //     return response()->json([
    //         'time' => $ymd,
    //         'hours' => $hours,
    //         'cronfail' => $cronfail,
    //         'crondata' => $getdatacron,
    //         'cronhistory' => $gethistorycron,
    //     ], 200);
    // }

    public function checkcronjob()
    {
        $time = Carbon::now('Asia/Jakarta');
        $hours = $time->format('H:i:s');
        $datetimeNow = Carbon::now();
        $startTime = Carbon::createFromTime(6, 0, 0)->format('H:i:s');
        // Query to get data between 06:00:00 and the current time
        $getdatacron = DB::connection('mysql2')->table('crontab')
            ->select('*')
            ->whereBetween('triger_time', [$startTime, $hours])
            ->get();


        $filteredData = collect([]);

        foreach ($getdatacron as $item) {
            if ($item->last_update === null) {
                $filteredData->push($item); // Include items with null last_update
            } else {
                // Convert last_update to Carbon instance for comparison
                $lastUpdate = Carbon::parse($item->last_update);

                // Check if last_update is not today or is less than the trigger time today
                if (!$lastUpdate->isToday() || $lastUpdate->lt($datetimeNow->startOfDay()->addHours($item->triger_time))) {
                    $filteredData->push($item);
                }
            }
        }



        return response()->json([
            'cronfail' => $filteredData,
            'crondata' => $getdatacron,
        ], 200);
    }
}
