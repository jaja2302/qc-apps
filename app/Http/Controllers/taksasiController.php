<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Collection;
use Carbon\Carbon;


require_once(app_path('helpers.php'));

class taksasiController extends Controller
{
    public function dashboard($query)
    {
        $decodedDataAfd = json_decode($query, true);
        $first_array = json_decode($query, true);

        dd($decodedDataAfd);
        $dataAfd = $first_array['dataAfd'];
        $testing = $first_array['dataAfd'];

        $est = $decodedDataAfd['est'];
        $datetime = $decodedDataAfd['datetime'];
        $wilayah = $decodedDataAfd['wilayah'];
        $modifiedVariable = str_replace('+', ' ', $est);
        $modWIl = str_replace('+', ' ', $wilayah);


        $originalDate = Carbon::parse($datetime);
        $modifiedDate = $originalDate->addDay(); // This adds one day
        $modifiedDateString = $modifiedDate->format("Y-m-d");
        // unset($decodedDataAfd["OC"]);
        foreach ($testing as $key => $value) {
            // Check if the key has only one index and the value is an array with one element containing only "-"
            if (count($value) === 1 && is_array($value[0]) && count(array_filter($value[0], function ($item) {
                return $item === "-";
            })) === count($value[0])) {
                // Remove the key
                unset($testing[$key]);
            }
        }
        // dd($decodedDataAfd, $first_array);
        // dd($dataAfd);
        // dd($dataAfd, $modifiedDateString, $datetime);

        return view('Taksasi.taksasi')->with(['collection' => $dataAfd, 'total' => $testing, 'est' => $modifiedVariable, 'awal' => $datetime, 'akhir' => $modifiedDateString, 'wil' => $modWIl]);
    }
}
