<?php

namespace App\Http\Controllers;

use App\Models\BlokMatch;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Exists;

class Matchingblok extends Controller
{
    public function dashboard()
    {
        $table = BlokMatch::all(); // Fetch all records from BlokMatch

        return view('Matchblok.index', ['table' => $table]);
    }
    public function tabledata(Request $request)
    {
        $est = $request->get('est');
        $regData = $request->get('regData');
        $date = $request->get('date');

        // dd($date);
        $result = score_by_maps($est, $regData, $date);
        $table = $result['table_newblok'];
        $master_blok = $result['master_blok'];
        $data = array();
        $data['table'] = $table;
        $data['master_blok'] = $master_blok;

        return response()->json($data);
    }

    public function addmatchblok(Request $request)
    {
        $items = $request->input('items');

        try {
            foreach ($items as $item) {
                $exist = BlokMatch::where('est', $item['estate'])
                    ->where('afd', $item['afdeling'])
                    ->where('blok', $item['blok'])
                    ->exists();

                if ($exist) {
                    return response()->json(['success' => false, 'message' => 'Item already exists: ' . json_encode($item)]);
                } else {
                    $data = new BlokMatch;
                    $data->est = $item['estate'];
                    $data->afd = $item['afdeling'];
                    $data->blok_asli = $item['blokasli'];
                    $data->blok = $item['blok'];
                    $data->save();
                }
            }
            return response()->json(['success' => true, 'message' => 'Items successfully saved']);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => 'Failed to save items', 'error' => $th->getMessage()]);
        }
    }
}
