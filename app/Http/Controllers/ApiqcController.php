<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Afdeling;
use App\Models\Blok;
use App\Models\Curahhujanbot;
use App\Models\Estate;
use App\Models\Formijin;
use App\Models\Gradingmill;
use App\Models\Regional;
use App\Models\Wilayah;
use App\Models\historycron;
use App\Models\Ijinkebun;
use App\Models\Pengguna;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Barryvdh\DomPDF\Facade\Pdf;
use DateTime;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

require_once(app_path('helpers.php'));

class ApiqcController extends Controller
{
    use WithoutMiddleware;
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
            ->where('estate', '!=', 'NBE')
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
            ->whereIn('id_jabatan', ['3', '1', '5', '6', '9', '10', '11', '12', '17', '19', '20', '21', '25', '26', '27', '28', '29', '32', '18'])
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
            $user_id = $request->input('name');
            $no_hp = $request->input('no_hp');
            $whatsappNumber = strstr($no_hp, '@', true);
            if (strpos($whatsappNumber, '08') === 0) {
                $whatsappNumber = '62' . substr($whatsappNumber, 1);
            }


            // return response()->json(['error_validasi' => $no_hp], 200);

            $existingRequest = Formijin::where('user_id', $user_id)
                ->where('status_bot', '!=', '0$0$0')
                ->first();

            if ($existingRequest) {
                return response()->json([
                    'error_validasi' => 'Permintaan izin Anda saat ini masih menunggu persetujuan. Mohon tunggu hingga permintaan terakhir Anda pada tanggal: ' . $existingRequest['tanggal_keluar'] . ' disetujui oleh atasan.'
                ], 200);
            }

            $user_nomor_hp = Pengguna::where('user_id', $user_id)->pluck('no_hp')->first();

            // Standardize the user phone number from the database
            if (strpos($user_nomor_hp, '08') === 0) {
                $user_nomor_hp = '62' . substr($user_nomor_hp, 1);
            }

            // Standardize the extracted number for the case when it's already in 62 format
            if (strpos($user_nomor_hp, '62') === 0 && strpos($user_nomor_hp, '620') !== 0) {
                $user_nomor_hp = '62' . ltrim(substr($user_nomor_hp, 2), '0');
            }

            // Compare the standardized phone numbers
            if ($user_nomor_hp !== $whatsappNumber) {
                return response()->json([
                    'error_validasi' => 'Nomor Whatsapp yang anda daftarkan berbeda dengan di database! HARAP PILIH NAMA USER YANG SESUAI!'
                ], 200);
            }

            if ($user_nomor_hp == null) {
                return response()->json([
                    'error_validasi' => 'Nomor Hp Anda belum terdaftar di database. Silahkan Hubungi Admin Devisi anda untuk mendaftarkan nomor handphone'
                ], 200);
            }

            return response()->json(['success' => 'pass'], 200);
        } else {
            try {

                // Parse the input dates to Carbon instances
                $today = Carbon::now();
                $carbon_tanggal_keluar = Carbon::createFromFormat('d-m-Y', $request->input('pergi'));
                $carbon_tanggal_kembali = Carbon::createFromFormat('d-m-Y', $request->input('kembali'));
                $carbon_jam_keluar = Carbon::createFromFormat('H:i', $request->input('jam_keluar'));
                $carbot_jam_kembali = Carbon::createFromFormat('H:i', $request->input('jam_kembali'));
                // Check if tanggal_kembali is before tanggal_keluar
                if ($carbon_tanggal_keluar->lessThan($today)) {
                    return response()->json(['error_validasi' => 'Tanggal keluar tidak boleh di masa lalu'], 200);
                }

                // Check if tanggal_kembali is in the past
                if ($carbon_tanggal_kembali->lessThan($today)) {
                    return response()->json(['error_validasi' => 'Tanggal kembali tidak boleh di masa lalu'], 200);
                }

                // Check if tanggal_keluar and tanggal_kembali are the same
                // if ($carbon_tanggal_keluar->equalTo($carbon_tanggal_kembali)) {
                //     return response()->json(['error_validasi' => 'Tanggal keluar dan tanggal kembali tidak boleh sama'], 200);
                // }

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

                if ($request->input('plat_nomor') === 'skip') {
                    $plat_nomor = null;
                } else {
                    $plat_nomor = $request->input('plat_nomor');
                }

                // combine tanggal keluar with jam_keluar
                $tanggal_keluar = $carbon_tanggal_keluar->format('Y-m-d') . ' ' . $carbon_jam_keluar->format('H:i:s');
                $tanggal_kembali = $carbon_tanggal_kembali->format('Y-m-d') . ' ' . $carbot_jam_kembali->format('H:i:s');

                // // Format the dates to datetime format for storing in the database
                // $tanggal_keluar = $carbon_tanggal_keluar->format('Y-m-d H:i:s');
                // $tanggal_kembali = $carbon_tanggal_kembali->format('Y-m-d H:i:s');

                // return response()->json(['success' => $request->input('unit_kerja')], 200);
                // Prepare the data for insertion
                $data = [
                    'user_id' => $request->input('name'),
                    'tanggal_keluar' => $tanggal_keluar,
                    'tanggal_kembali' => $tanggal_kembali,
                    'lokasi_tujuan' => $request->input('tujuan'),
                    'plat_nomor' => $plat_nomor,
                    'kendaraan' => $request->input('kendaraan'),
                    'keperluan' => $request->input('keperluan'),
                    'atasan_1' => $request->input('atasan_satu'),
                    'atasan_2' => $request->input('atasan_dua'),
                    'created_at' => $today,
                    'updated_at' => $today,
                ];

                // Check if the user already has a pending request


                // Check if the atasan fields are the same
                // if ($request->input('atasan_satu') === $request->input('atasan_dua')) {
                //     return response()->json(['error_validasi' => 'Nama Atasan satu dan Atasan dua tidak boleh sama'], 200);
                // }

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



    public function get_data_mill()
    {
        $data = Gradingmill::query()->where('status_bot', 0)->get();

        $result = [];
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                // Remove square brackets and split the string into an array
                $cleaned_string = str_replace(['[', ']'], '', $value['foto_temuan']);
                $foto = explode(',', $cleaned_string);

                // Trim spaces from each element in the array
                $foto = array_map('trim', $foto);
                $jumlah_janjang_grading = $value['jjg_grading'];
                $jumlah_janjang_spb = $value['jjg_spb'];
                $kelas_a = $value['kelas_a'];
                $kelas_b = $value['kelas_b'];
                $kelas_c = $value['kelas_c'];
                $persentage_kelas_a = ($kelas_a / $jumlah_janjang_grading) * 100;
                $persentage_kelas_b = ($kelas_b / $jumlah_janjang_grading) * 100;
                $persentage_kelas_c = ($kelas_c / $jumlah_janjang_grading) * 100;
                $brondol_0 = $value['unripe_tanpa_brondol'];
                $brondol_less = $value['unripe_kurang_brondol'];
                $vcut = $value['vcut'];
                $not_vcut = $jumlah_janjang_grading - $vcut;
                $overripe = $value['overripe'];
                $empty_bunch = $value['empty'];
                $rotten_bunch = $value['rotten'];
                $abnormal = $value['abn_partheno'] + $value['abn_hard'] + $value['abn_sakit'] +  $value['abn_kastrasi'];

                $loose_fruit_kg = round(($value['loose_fruit'] / $value['tonase']) * 100, 2);
                $dirt_kg = round(($value['dirt']  / $value['tonase']) * 100, 2);
                $unripe = $brondol_0 + $brondol_less;
                $ripeness = $value['jjg_grading'] - ($value['overripe'] + $value['empty'] + $value['rotten'] + $abnormal + $unripe);

                // Calculate percentages
                $percentage_ripeness = ($ripeness / $jumlah_janjang_grading) * 100;
                $percentage_unripe = ($unripe / $jumlah_janjang_grading) * 100;
                $percentage_brondol_0 = ($brondol_0 / $jumlah_janjang_grading) * 100;
                $percentage_brondol_less = ($brondol_less / $jumlah_janjang_grading) * 100;
                $percentage_overripe = ($overripe / $jumlah_janjang_grading) * 100;
                $percentage_empty_bunch = ($empty_bunch / $jumlah_janjang_grading) * 100;
                $percentage_vcut = ($vcut / $jumlah_janjang_grading) * 100;
                $percentage_not_vcut = ($not_vcut / $jumlah_janjang_grading) * 100;
                $percentage_rotten_bunch = ($rotten_bunch / $jumlah_janjang_grading) * 100;
                $percentage_abnormal = ($abnormal / $jumlah_janjang_grading) * 100;
                $percentage_tangkai_panjang = ($value['tangkai_panjang'] / $jumlah_janjang_grading) * 100;
                // Calculate selisih janjang and percentage
                $jumlah_selisih_janjang = $jumlah_janjang_grading - $jumlah_janjang_spb;
                $percentage_selisih_janjang = ($jumlah_selisih_janjang / $jumlah_janjang_spb) * 100;
                $no_pemanen = json_decode($value['no_pemanen'], true);

                $tanpaBrondol = [];
                $datakurang_brondol = [];
                // dd($no_pemanen);
                foreach ($no_pemanen as $keys1 => $values1) {
                    $get_pemanen = isset($values1['a']) ? $values1['a'] : (isset($values1['noPemanen']) ? $values1['noPemanen'] : null);
                    $get_kurangBrondol = isset($values1['b']) ? $values1['b'] : (isset($values1['kurangBrondol']) ? $values1['kurangBrondol'] : 0);
                    $get_tanpaBrondol = isset($values1['c']) ? $values1['c'] : (isset($values1['tanpaBrondol']) ? $values1['tanpaBrondol'] : 0);

                    if ($get_kurangBrondol != 0) {
                        $datakurang_brondol['kurangBrondol_list'][] = [
                            'no_pemanen' => ($get_pemanen == 999) ? 'X' : $get_pemanen,
                            'kurangBrondol' => $get_kurangBrondol,
                        ];
                    }

                    if ($get_tanpaBrondol != 0) {
                        $tanpaBrondol['tanpaBrondol_list'][] = [
                            'no_pemanen' => ($get_pemanen == 999) ? 'X' : $get_pemanen,
                            'tanpaBrondol' => $get_tanpaBrondol,
                        ];
                    }
                }

                // dd($tanpaBrondol);

                if ($datakurang_brondol == []) {
                    $resultKurangBrondol = '-';
                } else {
                    $resultKurangBrondol = implode(',', array_map(function ($item) {
                        return $item['no_pemanen'] . '(' . $item['kurangBrondol'] . ')';
                    }, $datakurang_brondol['kurangBrondol_list']));
                }

                if ($tanpaBrondol == []) {
                    $resultTanpaBrondol = '-';
                } else {
                    $resultTanpaBrondol = implode(',', array_map(function ($item) {
                        return $item['no_pemanen'] . '(' . $item['tanpaBrondol'] . ')';
                    }, $tanpaBrondol['tanpaBrondol_list']));
                }

                Carbon::setLocale('id');

                // Original datetime string
                $datetime = $value['datetime'];

                // Create a Carbon instance
                $date = Carbon::parse($datetime);
                $getadds = explode(';', $value['app_version']);

                $appvers = $getadds[0] ?? '-';
                $os_version = $getadds[1] ?? '-';
                $phone_version = $getadds[2] ?? '-';
                // Format the date
                $dayOfWeek = $date->isoFormat('dddd'); // Get the day of the week in Indonesian
                $formattedDate = $dayOfWeek . ', ' . $date->format('d-m-Y');
                $list_blok = str_replace(['[', ']'], '', $value['blok']);

                // dd($value['blok']);
                $dataakhir = [
                    'id' => $value['id'],
                    'estate' => $value['estate'],
                    'afdeling' => $value['afdeling'],
                    'mill' => $value['mill'],
                    'Tanggal' => $formattedDate,
                    'list_blok' => $list_blok,
                    'waktu_grading' => $date->format('H:i:s'),
                    'jjg_grading' => $value['jjg_grading'],
                    'no_plat' => $value['no_plat'],
                    'supir' => $value['driver'],
                    'jjg_spb' => $value['jjg_spb'],
                    'tonase' => $value['tonase'],
                    'abn_partheno' => $value['abn_partheno'],
                    'abn_partheno_percen' => round(($value['abn_partheno'] / $jumlah_janjang_grading) * 100, 2),
                    'abn_hard' => $value['abn_hard'],
                    'abn_hard_percen' => round(($value['abn_hard'] / $jumlah_janjang_grading) * 100, 2),
                    'abn_sakit' =>  $value['abn_sakit'],
                    'abn_sakit_percen' => round(($value['abn_sakit'] / $jumlah_janjang_grading) * 100, 2),
                    'abn_kastrasi' =>  $value['abn_kastrasi'],
                    'abn_kastrasi_percen' => round(($value['abn_kastrasi'] / $jumlah_janjang_grading) * 100, 2),
                    'bjr' => round($value['tonase'] / $value['jjg_spb'], 2),
                    'jjg_selisih' => $jumlah_selisih_janjang,
                    'persentase_selisih' => round($percentage_selisih_janjang, 2),
                    'Ripeness' => $ripeness,
                    'percentase_ripenes' => round($percentage_ripeness, 2),
                    'Unripe' => $unripe,
                    'persenstase_unripe' => round($percentage_unripe, 2),
                    'nol_brondol' => $brondol_0,
                    'persentase_nol_brondol' => round($percentage_brondol_0, 2),
                    'kurang_brondol' => $brondol_less,
                    'persentase_brondol' => round($percentage_brondol_less, 2),
                    'nomor_pemanen' => 'a',
                    'unripe_tanda_x' => 'a',
                    'Overripe' => $overripe,
                    'persentase_overripe' => round($percentage_overripe, 2),
                    'empty_bunch' => $empty_bunch,
                    'persentase_empty_bunch' => round($percentage_empty_bunch, 2),
                    'rotten_bunch' => $rotten_bunch,
                    'persentase_rotten_bunce' => round($percentage_rotten_bunch, 2),
                    'Abnormal' => $abnormal,
                    'persentase_abnormal' =>    round($percentage_abnormal, 2),
                    'loose_fruit' => $value['loose_fruit'],
                    'persentase_lose_fruit' => $loose_fruit_kg,
                    'Dirt' => $value['dirt'],
                    'persentase' => $dirt_kg,
                    'foto' => $foto,
                    'stalk' =>    $value['tangkai_panjang'],
                    'persentase_stalk' => round($percentage_tangkai_panjang, 2),
                    'pemanen_list_tanpabrondol' => $tanpaBrondol,
                    'pemanen_list_kurangbrondol' => $datakurang_brondol,
                    'resultKurangBrondol' => $resultKurangBrondol,
                    'resultTanpaBrondol' => $resultTanpaBrondol,
                    'vcut' => $vcut,
                    'percentage_vcut' => round($percentage_vcut, 2),
                    'not_vcut' => $not_vcut,
                    'percentage_not_vcut' => round($percentage_not_vcut, 2),
                    'kelas_a' => $kelas_a,
                    'kelas_b' => $kelas_b,
                    'kelas_c' => $kelas_c,
                    'percentage_kelas_a' => round($persentage_kelas_a, 2),
                    'percentage_kelas_b' => round($persentage_kelas_b, 2),
                    'percentage_kelas_c' =>  round($persentage_kelas_c, 2),
                    'tanggal_titel' => $date->format('d M Y'),
                    'appvers' => $appvers,
                    'os_version' => $os_version,
                    'phone_version' => $phone_version,
                ];
                // dd($dataakhir);
                $pdf = pdf::loadView('Grading.pdfgrading_api', ['data' => $dataakhir]);

                // Set the paper size to A4 and orientation to portrait
                $pdf->setPaper('A4', 'portrait');

                // Generate a unique name for the PDF file
                $pdfName = 'document_' . time() . '.pdf';

                // return $pdf->stream($pdfName);
                Storage::disk('public')->put($pdfName, $pdf->output());



                // dd($tanpaBrondol);
                $result[] = [
                    'id' => $value['id'],
                    'estate' => $value['estate'],
                    'afdeling' => $value['afdeling'],
                    'mill' => $value['mill'],
                    'Tanggal' => $formattedDate,
                    'list_blok' => $list_blok,
                    'waktu_grading' => $date->format('d-m-Y'),
                    'jjg_grading' => $value['jjg_grading'],
                    'no_plat' => $value['no_plat'],
                    'supir' => '-',
                    'jjg_spb' => $value['jjg_spb'],
                    'tonase' => $value['tonase'],
                    'abn_partheno' => $value['abn_partheno'],
                    'abn_partheno_percen' => round(($value['abn_partheno'] / $jumlah_janjang_grading) * 100, 2),
                    'abn_hard' => $value['abn_hard'],
                    'abn_hard_percen' => round(($value['abn_hard'] / $jumlah_janjang_grading) * 100, 2),
                    'abn_sakit' =>  $value['abn_sakit'],
                    'abn_sakit_percen' => round(($value['abn_sakit'] / $jumlah_janjang_grading) * 100, 2),
                    'abn_kastrasi' =>  $value['abn_kastrasi'],
                    'abn_kastrasi_percen' => round(($value['abn_kastrasi'] / $jumlah_janjang_grading) * 100, 2),
                    'bjr' => round($value['jjg_spb'] / $value['tonase'], 2),
                    'jjg_selisih' => $jumlah_selisih_janjang,
                    'persentase_selisih' => round($percentage_selisih_janjang, 2),
                    'Ripeness' => $ripeness,
                    'percentase_ripenes' => round($percentage_ripeness, 2),
                    'Unripe' => $unripe,
                    'persenstase_unripe' => round($percentage_unripe, 2),
                    'nol_brondol' => $brondol_0,
                    'persentase_nol_brondol' => round($percentage_brondol_0, 2),
                    'kurang_brondol' => $brondol_less,
                    'persentase_brondol' => round($percentage_brondol_less, 2),
                    'nomor_pemanen' => 'a',
                    'unripe_tanda_x' => 'a',
                    'Overripe' => $overripe,
                    'persentase_overripe' => round($percentage_overripe, 2),
                    'empty_bunch' => $empty_bunch,
                    'persentase_empty_bunch' => round($percentage_empty_bunch, 2),
                    'rotten_bunch' => $rotten_bunch,
                    'persentase_rotten_bunce' => round($percentage_rotten_bunch, 2),
                    'Abnormal' => $abnormal,
                    'persentase_abnormal' =>    round($percentage_abnormal, 2),
                    'loose_fruit' => $value['loose_fruit'],
                    'persentase_lose_fruit' => $loose_fruit_kg,
                    'Dirt' => $value['dirt'],
                    'persentase' => $dirt_kg,
                    'foto' => $foto,
                    'stalk' =>    $value['tangkai_panjang'],
                    'persentase_stalk' => round($percentage_tangkai_panjang, 2),
                    'pemanen_list_tanpabrondol' => $tanpaBrondol,
                    'pemanen_list_kurangbrondol' => $datakurang_brondol,
                    'resultKurangBrondol' => $resultKurangBrondol,
                    'resultTanpaBrondol' => $resultTanpaBrondol,
                    'filename_pdf' => $pdfName,
                    'waktu_grading' => $date->format('H:i:s'),
                    'waktu_grading_judul' => $date->format('H:i'),
                    'tanggal_judul' => $date->format('dmY'),
                    'appvers' => $appvers,
                    'os_version' => $os_version,
                    'phone_version' => $phone_version,
                ];
            }

            return response()->json([
                'status' => '200',
                'data' => $result,
            ], 200);
        } else {
            return response()->json([
                'status' => 'kosong',
                'data' => 'kosong'
            ], 200);
        }

        // dd($result);
    }

    public function get_data_mill_update(Request $request): JsonResponse
    {
        // Get the single 'id' input
        $id = $request->input('id');

        // Check if the record exists
        $exists = Gradingmill::query()->where('id', $id)->exists();

        if ($exists) {
            // If the record exists, update it
            Gradingmill::where('id', $id)
                ->update([
                    'status_bot' => 1
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

    public function getnotif_suratijin()
    {
        $data = Formijin::where('status_send_notif', '!=', '1$1$1')->get();

        if ($data->isEmpty()) {
            return response()->json(['status' => 'kosong', 'data' => 'kosong'], 200);
        }

        $responseData = $this->processFormijinData($data);

        return response()->json(['status' => '200', 'data' => $responseData], 200);
    }

    private function processFormijinData($data)
    {
        $responseData = [];
        foreach ($data as $value) {
            $status = explode('$', $value['status_bot']);
            $statusSend = explode('$', $value['status_send_notif']);
            $case = $this->determineCase($status, $value['status']);

            $userData = $this->getUserData($value);

            if ($userData['user'] && $userData['user']->nama_lengkap != null) {
                $notificationData = $this->prepareNotificationData($value, $userData, $case, $statusSend);

                if ($notificationData) {
                    $responseData[] = $notificationData;
                    $this->updateStatusSendNotif($value['id'], $case, $userData);
                }
            }
        }

        return $responseData;
    }


    private function determineCase($status, $formStatus)
    {
        if ($status[0] != 1 && $formStatus !== '3') return 'Atasan_1_not_approved';
        if ($status[0] == 1 && $status[1] != 1 && $formStatus !== '3') return 'Atasan_2_not_approved';
        if ($status[0] == 1 && $status[1] == 1 && $status[2] != 1 && $formStatus !== '3') return 'User_not_sending';
        if ($formStatus == '3') return 'rejected';
        return '-';
    }

    private function getUserData($value)
    {
        return [
            'user' => Pengguna::where('user_id', $value['user_id'])->first(),
            'atasan1' => Pengguna::where('user_id', $value['atasan_1'])->first(),
            'atasan2' => Pengguna::where('user_id', $value['atasan_2'])->first(),
        ];
    }

    private function prepareNotificationData($value, $userData, $case, $statusSend)
    {
        $baseData = [
            'user_request' => $userData['user']->nama_lengkap,
            'tanggal_keluar' => Carbon::parse($value['tanggal_keluar'])->format('d-m-Y'),
            'tanggal_kembali' => Carbon::parse($value['tanggal_kembali'])->format('d-m-Y'),
            'jam_keluar' => Carbon::parse($value['tanggal_keluar'])->format('H:i:s'),
            'jam_kembali' => Carbon::parse($value['tanggal_kembali'])->format('H:i:s'),
            'keperluan' => $value['keperluan'],
            'lokasi_tujuan' => $value['lokasi_tujuan'],
        ];

        switch ($case) {
            case 'Atasan_1_not_approved':
                if ($statusSend[0] != 1) {
                    return array_merge($baseData, [
                        'id' => $value->id . '/1',
                        'status' => 'approved',
                        'atasan_nama' => $userData['atasan1']->nama_lengkap,
                        'no_hp' => formatPhoneNumber($userData['atasan1']->no_hp),
                    ]);
                }
                break;
            case 'Atasan_2_not_approved':
                if ($statusSend[1] != 1) {
                    return array_merge($baseData, [
                        'id' => $value->id . '/2',
                        'status' => 'approved',
                        'atasan_nama' => $userData['atasan2']->nama_lengkap,
                        'no_hp' => formatPhoneNumber($userData['atasan2']->no_hp),
                        'no_hp_user' => formatPhoneNumber($userData['user']->no_hp),
                    ]);
                }
                break;
            case 'User_not_sending':
                if ($statusSend[2] != 1) {
                    return array_merge($baseData, [
                        'id' => $value->id,
                        'id_atasan' => 3,
                        'status' => 'send_approved',
                        'no_hp' => formatPhoneNumber($userData['user']->no_hp),
                    ]);
                }
                break;
            case 'rejected':
                return array_merge($baseData, [
                    'id' => $value->id,
                    'id_atasan' => 4,
                    'status' => 'rejected',
                    'no_hp' => formatPhoneNumber($userData['user']->no_hp),
                    'alasan' => ($value['catatan'] == null) ? '-' : $value['catatan'],
                ]);
        }

        return null;
    }

    private function updateStatusSendNotif($id, $case, $userData)
    {
        // dd($userData);
        $atasan1 = formatPhoneNumber($userData['atasan1']->no_hp);
        $atasan2 = formatPhoneNumber($userData['atasan2']->no_hp);

        if ($atasan1 != null) {
            $atasan1_data = '1$0$0';
        } else {
            $atasan1_data = '0$0$0';
        }
        if ($atasan2 != null) {
            $atasan2_data = '1$1$0';
        } else {
            $atasan2_data = '1$0$0';
        }
        // dd($atasan1, $atasan2);


        $statusMap = [
            'Atasan_1_not_approved' => $atasan1_data,
            'Atasan_2_not_approved' => $atasan2_data,
            'User_not_sending' => '1$1$1',
            'rejected' => '1$1$1',
        ];

        if (isset($statusMap[$case])) {
            Formijin::where('id', $id)->update(['status_send_notif' => $statusMap[$case]]);
        }
    }

    public function getnotif_suratijin_approved(Request $request): JsonResponse
    {
        $id = $request->input('id_data');
        $id_atasan = $request->input('id_atasan');
        $answer = $request->input('answer');

        $formIjin = Formijin::find($id);

        if (!$formIjin) {
            return response()->json(['message' => 'No pending messages found.'], 404);
        }

        $status = explode('$', $formIjin->status_bot);
        $atasan1 = $status[0];
        $atasan2 = $status[1];
        $user = $status[2];

        switch ($id_atasan) {
            case '1':
                return $this->handleAtasan1Approval($formIjin, $atasan1, $atasan2, $user, $answer);
            case '2':
                return $this->handleAtasan2Approval($formIjin, $atasan1, $atasan2, $user, $answer);
            case '3':
                return $this->handleUserApproval($formIjin, $atasan1, $atasan2);
            default:
                return response()->json(['message' => 'error.'], 200);
        }
    }

    private function handleAtasan1Approval($formIjin, $atasan1, $atasan2, $user, $answer): JsonResponse
    {
        if ($atasan1 == 1 || $atasan1 === '1') {
            return response()->json(['error_validasi' => 'Anda sudah Approval data ini'], 200);
        }

        $newStatus = implode('$', [1, $atasan2, $user]);

        if ($answer === 'ya') {
            $formIjin->update(['status_bot' => $newStatus, 'status' => 2]);
        } else {
            $formIjin->update([
                'status_bot' => '1$0$0',
                'status' => 3,
                'catatan' => $answer !== 'tidak' ? $answer : null
            ]);
        }

        return response()->json(['success' => 'Status updated successfully.'], 200);
    }

    private function handleAtasan2Approval($formIjin, $atasan1, $atasan2, $user, $answer): JsonResponse
    {
        if ($atasan2 == 1 || $atasan2 === '1') {
            return response()->json(['error_validasi' => 'Anda sudah Approval data ini'], 200);
        }

        $newStatus = implode('$', [$atasan1, 1, $user]);

        if ($answer === 'ya') {
            $formIjin->update(['status_bot' => $newStatus, 'status' => 4]);
        } else {
            $formIjin->update([
                'status_bot' => '1$1$0',
                'status' => 3,
                'catatan' => $answer !== 'tidak' ? $answer : null
            ]);
        }

        return response()->json(['success' => 'Status updated successfully.'], 200);
    }

    private function handleUserApproval($formIjin, $atasan1, $atasan2): JsonResponse
    {
        $newStatus = implode('$', [$atasan1, $atasan2, 1]);
        $formIjin->update(['status_bot' => $newStatus]);
        return response()->json(['message' => 'Status updated successfully.'], 200);
    }


    public function inputiot_data(Request $request): JsonResponse
    {
        $estate = $request->input('estate');
        $afdeling = $request->input('afdeling');
        $afdeling_id = $request->input('afdeling_id');
        $estate_id = $request->input('estate_id');
        $curahHujan = $request->input('curahHujan');
        $type = $request->input('type');

        switch ($type) {
            case 'check_estate':
                $data = DB::connection('mysql2')->table('estate')
                    ->select('*', 'afdeling.*', 'afdeling.id as afdeling_id', 'estate.id as est_id')
                    ->join('afdeling', 'afdeling.estate', '=', 'estate.id')
                    ->where('est', '=', $estate)
                    ->get();
                $data = $data->groupBy(['est', 'nama']);

                // dd($data);
                $afd = [];
                foreach ($data as $key => $value) {
                    $afd = $value;
                }
                // dd($afd);
                if ($data->isNotEmpty()) {
                    return response()->json(['data' => $afd], 200);
                } else {
                    return response()->json(['error_validasi' => 'Est Tidak Tersedia ditemukan'], 404);
                }


                break;
            case 'input':
                try {
                    $dateTime = Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s');

                    $data = [
                        'Date' => $dateTime,
                        'Afd' => $request->input('afdeling'),
                        'Est' => $request->input('estate'),
                        'CH' => $request->input('curahHujan'),
                        'afd_id' => $request->input('afdeling_id'),
                        'est_id' => $request->input('estate_id'),
                    ];

                    DB::beginTransaction();

                    // Insert the new data
                    $newdata = Curahhujanbot::create($data);
                    $newdata->save();

                    DB::commit();
                    return response()->json(['success' => true], 200);
                } catch (\Throwable $th) {
                    DB::rollBack();
                    return response()->json(['error_validasi' => $th], 404);
                }

                break;
            default:
                return response()->json(['error_validasi' => 'tidak tau'], 404);
                break;
        }
    }

    public function getmsgsmartlabs()
    {
        $data = DB::connection('mysql5')
            ->table('send_msg')
            ->select('*')
            ->get();

        $data = json_decode($data, true);

        if (!empty($data)) {
            $filteredData = collect($data)->filter(function ($item) {
                return strpos($item['penerima'], '62') === 0;
            });

            if ($filteredData->isNotEmpty()) {
                return response()->json([
                    'status' => '200',
                    'data' => $filteredData->values(),
                ], 200);
            } else {
                return response()->json([
                    'status' => '200',
                    'data' => 'kosong',
                ], 200);
            }
        } else {
            return response()->json([
                'status' => '200',
                'data' => 'kosong',
            ], 200);
        }
    }

    public function deletemsgsmartlabs(Request $request)
    {
        $id = $request->input('id');

        if ($id != null) {
            $data = DB::connection('mysql5')
                ->table('send_msg')
                ->where('id', $id)
                ->delete();

            return response()->json([
                'status' => '200',
                'data' => 'deleted',
            ], 200);
        } else {
            return response()->json([
                'status' => '400',
                'error' => 'Invalid ID provided',
            ], 400);
        }
    }

    public function updatestatusbot(Request $request)
    {
        $request->validate([
            'pc_id' => 'required|string|max:50',
            'status' => 'required|string|max:10'
        ]);

        $pc_id = $request->input('pc_id');
        $status = $request->input('status');
        $time = Carbon::now('Asia/Jakarta');

        try {
            $record = DB::connection('mysql2')->table('pc_status')->where('pc_id', $pc_id)->first();

            if ($record) {
                // Record exists, update it
                DB::connection('mysql2')
                    ->table('pc_status')
                    ->where('pc_id', $pc_id)
                    ->update(['status' => $status, 'last_update' => $time]);
                return response()->json(['message' => 'Record updated successfully'], 200);
            } else {
                // Record doesn't exist, insert a new one
                DB::connection('mysql2')
                    ->table('pc_status')
                    ->insert(['pc_id' => $pc_id, 'status' => $status, 'last_update' => $time]);
                return response()->json(['message' => 'Record inserted successfully'], 201);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error updating record: ' . $e->getMessage()], 500);
        }
    }

    public function checkPcStatus()
    {
        // Set the threshold time to 10 minutes ago
        $threshold = Carbon::now('Asia/Jakarta')->subMinutes(10)->format('Y-m-d H:i:s');

        // Fetch PCs with last update time older than the threshold
        $pcs = DB::connection('mysql2')->table('pc_status')->where('last_update', '<', $threshold)->get();

        if ($pcs->isEmpty()) {
            return response()->json(['message' => 'All PCs are online']);
        }

        // Send notifications for PCs that are offline
        foreach ($pcs as $pc) {
            $this->sendTelegramNotification($pc->pc_id);
        }

        return response()->json(['message' => 'Notifications sent']);
    }


    protected function sendTelegramNotification($pc_id)
    {
        $botApiToken = '5428994980:AAFdHrlniyG8UqKZfXw-Cvz_MzjftW5KObY';
        $channelId = '-1001316663954';
        $text = "PC HO Tidak Online";
        $threadId = '16858';

        $response = Http::get("https://api.telegram.org/bot{$botApiToken}/sendMessage", [
            'chat_id' => $channelId,
            'message_thread_id' => $threadId,
            'text' => $text,
        ]);

        if ($response->failed()) {
            // Handle error if needed
            return false;
        }

        return $response->json();
    }


    public function getlistestate(): JsonResponse
    {
        $data = DB::connection('mysql2')->table('estate')
            ->select('*')
            ->where('estate.emp', '!=', '01')
            ->get();

        return response()->json(['data' => $data], 200);
    }
}
