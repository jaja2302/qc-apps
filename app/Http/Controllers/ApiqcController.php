<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Afdeling;
use App\Models\Blok;
use App\Models\Estate;
use App\Models\Formijin;
use App\Models\Regional;
use App\Models\Wilayah;
use App\Models\historycron;
use App\Models\Ijinkebun;
use App\Models\Pengguna;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

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


    public function checkcronjob()
    {
        $time = Carbon::now('Asia/Jakarta');
        $hours = $time->format('H:i:s');
        $datetimeNow = Carbon::now();
        $startTime = Carbon::createFromTime(6, 0, 0)->format('H:i:s');
        // Query to get data between 06:00:00 and the current time
        $getdatacron = DB::connection('mysql2')->table('crontab')
            ->select('*')
            ->where('status', 1)
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
    public function sendwamaintence(): JsonResponse
    {
        $check = DB::table('message_info')
            ->select('*')
            ->where('aplikasi', 'Maintence')
            ->where('status_sending', 0)
            ->get();

        $responseArray = [];

        foreach ($check as $value) {
            $data = json_decode($value->data, true); // Decode JSON to array

            $responseArray[] = [
                'id' => [$value->id],
                'data' => [$data],
                'message' => 'Data retrieved successfully.'
            ];
        }

        return response()->json($responseArray, 200);
    }



    public function changestatusmaintence(Request $request): JsonResponse
    {
        // Check if the record exists
        $exists = DB::table('message_info')
            ->where('id', $request->input('id'))
            ->exists();

        if ($exists) {
            // If the record exists, update it
            DB::table('message_info')
                ->where('id', $request->input('id'))
                ->update([
                    'status_sending' => 1
                ]);

            // Add the code to send WhatsApp message here if needed

            return response()->json([
                'message' => 'Status updated successfully.'
            ], 200); // 200 OK
        } else {
            // If the record does not exist, return a message
            return response()->json([
                'message' => 'No pending messages found.'
            ], 404); // 404 Not Found
        }
    }


    public function getnamaatasan(Request $request): JsonResponse
    {
        // Retrieve the 'nama' input from the request
        $nama = $request->input('nama');

        // Validate the 'nama' input
        if (is_null($nama) || trim($nama) === '') {
            return response()->json(['message' => 'Please input a nama'], 400);
        }

        // Use the 'like' operator correctly in your query
        $data = Pengguna::where('nama_lengkap', 'like', '%' . $nama . '%')
            ->whereIn('jabatan', ['Manager', 'General Manager', 'Asisten', 'Askep', 'Regional Head'])
            ->get()
            ->toArray();

        $result = array();
        foreach ($data as $key => $value) {
            // Assuming you want to use $key as the ID since user_id is not available in the pluck result
            $result[$key] = [
                'id' => $value['user_id'], // Use $key as the ID
                'nama' => $value['nama_lengkap'],
                'departemen' => $value['departemen'],
            ];
        }

        // Check if any data is found
        if (!empty($result)) {
            return response()->json(['data' => $result], 200);
        } else {
            return response()->json(['message' => 'Nama Atasan tidak ditemukan'], 404);
        }
    }

    public function getuserinfo(Request $request): JsonResponse
    {
        // Retrieve the 'nama' input from the request
        $nama = $request->input('nama');


        // Validate the 'nama' input
        if (is_null($nama) || trim($nama) === '') {
            return response()->json(['message' => 'Please input a nama'], 400);
        }

        // Use the 'like' operator correctly in your query
        $data = Pengguna::where('nama_lengkap', 'like', '%' . $nama . '%')
            ->get()
            ->toArray();

        $result = array();
        foreach ($data as $key => $value) {
            // Assuming you want to use $key as the ID since user_id is not available in the pluck result
            $result[$key] = [
                'id' => $value['user_id'], // Use $key as the ID
                'nama' => $value['nama_lengkap'],
                'departemen' => $value['departemen'],
            ];
        }

        // Check if any data is found
        if (!empty($result)) {
            return response()->json(['data' => $result], 200);
        } else {
            return response()->json(['message' => 'Nama User tidak ditemukan'], 404);
        }
    }
    public function get_unit_bagian(): JsonResponse
    {
        $data = Ijinkebun::query()->get();
        $result = [];
        foreach ($data as $key => $value) {
            $result[] = [
                'id' => $value['id'],
                'nama_unit' => $value['nama'],
            ];
        }
        // dd($data);
        if (!empty($result)) {
            return response()->json(['data' => $result], 200);
        } else {
            return response()->json(['message' => 'Unit Tidak Tersedia ditemukan'], 404);
        }
    }

    public function form_data_ijin(Request $request)
    {
        if ($request->input('type') === 'check_user') {
            $existingRequest = Formijin::where('user_id', $request->input('name'))->where('status', 'waiting approval')->first();

            if ($existingRequest) {
                return response()->json(['error_validasi' => 'Permintaan izin Anda saat ini masih menunggu persetujuan. Mohon tunggu hingga permintaan terakhir Anda pada tanggal: ' . $existingRequest['tanggal_keluar'] . ' disetujui oleh atasan.'], 200);
            } else {
                return response()->json(['succes' => 'pass'], 200);
            }
        } else {
            try {

                // Parse the input dates to Carbon instances
                $today = Carbon::now();
                $carbon_tanggal_keluar = Carbon::createFromFormat('d-m-Y', $request->input('pergi'));
                $carbon_tanggal_kembali = Carbon::createFromFormat('d-m-Y', $request->input('kembali'));

                // Check if tanggal_kembali is before tanggal_keluar
                if ($carbon_tanggal_keluar->lessThan($today)) {
                    return response()->json(['error_validasi' => 'Tanggal keluar tidak boleh di masa lalu'], 200);
                }

                // Check if tanggal_kembali is in the past
                if ($carbon_tanggal_kembali->lessThan($today)) {
                    return response()->json(['error_validasi' => 'Tanggal kembali tidak boleh di masa lalu'], 200);
                }

                // Check if tanggal_keluar and tanggal_kembali are the same
                if ($carbon_tanggal_keluar->equalTo($carbon_tanggal_kembali)) {
                    return response()->json(['error_validasi' => 'Tanggal keluar dan tanggal kembali tidak boleh sama'], 200);
                }

                // Check if tanggal_kembali is before tanggal_keluar
                if ($carbon_tanggal_kembali->lessThan($carbon_tanggal_keluar)) {
                    return response()->json(['error_validasi' => 'Tanggal kembali tidak bisa sebelum tanggal keluar'], 200);
                }

                // Check if tanggal_keluar is more than 7 days from today
                if ($carbon_tanggal_keluar->greaterThan($today->copy()->addDays(7))) {
                    return response()->json(['error_validasi' => 'Tanggal keluar harus dalam 1 minggu dari sekarang'], 200);
                }

                // Check if tanggal_kembali is more than 30 days after tanggal_keluar
                if ($carbon_tanggal_kembali->greaterThan($carbon_tanggal_keluar->copy()->addDays(30))) {
                    return response()->json(['error_validasi' => 'Tanggal kembali tidak boleh lebih dari 30 hari setelah tanggal keluar'], 200);
                }
                // Format the dates to datetime format for storing in the database
                $tanggal_keluar = $carbon_tanggal_keluar->format('Y-m-d H:i:s');
                $tanggal_kembali = $carbon_tanggal_kembali->format('Y-m-d H:i:s');


                // Prepare the data for insertion
                $data = [
                    'user_id' => $request->input('name'),
                    'unit_id' => $request->input('unit_kerja'),
                    'tanggal_keluar' => $tanggal_keluar,
                    'tanggal_kembali' => $tanggal_kembali,
                    'lokasi_tujuan' => $request->input('tujuan'),
                    'keperluan' => $request->input('keperluan'),
                    'atasan_1' => $request->input('atasan_satu'),
                    'atasan_2' => $request->input('atasan_dua'),
                    'no_hp' => $request->input('no_hp'),
                ];

                // Check if the user already has a pending request


                // Check if the atasan fields are the same
                if ($request->input('atasan_satu') === $request->input('atasan_dua')) {
                    return response()->json(['error_validasi' => 'Nama Atasan satu dan Atasan dua tidak boleh sama'], 200);
                }

                DB::beginTransaction();

                // Insert the new data
                $newdata = Formijin::create($data);
                $newdata->save();

                DB::commit();

                return response()->json(['success' => true], 200);
            } catch (\Throwable $th) {
                DB::rollBack();

                if ($th instanceof \Illuminate\Database\QueryException) {
                    return response()->json(['error' => $th->getMessage()], 500);
                }

                return response()->json(['error' => $th], 500);
            }
        }
    }
}
