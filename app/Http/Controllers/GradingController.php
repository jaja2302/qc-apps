<?php

namespace App\Http\Controllers;

use App\Models\Estate;
use App\Models\Gradingmill;
use App\Models\Regional;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GradingController extends Controller
{

    public function index(Request $request)
    {
        $regionalId = $request->input('regional_id');
        $reg = Regional::query()->where('id', '!=', 5)->get();
        $estates = collect(); // Initialize an empty collection

        if ($regionalId) {
            $wilayahs = Wilayah::where('regional', $regionalId)
                ->with(['estate' => function ($query) {
                    $query->where('emp', '!=', 1);
                }])
                ->get();

            foreach ($wilayahs as $wilayah) {
                foreach ($wilayah->estate as $estate) {
                    $estates->push($estate);
                }
            }
        }
        return view('Grading.index', [
            'regional' => $reg,
            'estates' => $estates,
            'selectedRegionalId' => $regionalId,
        ]);
    }

    public function rekapregional(Request $request)
    {
        $reg = $request->input('reg');
        $bulan = $request->input('bulan');
        // $estate = $request->input('estate');

        // if ($estate == null) {
        //     return response()->json(['error' => ['estate tidak boleh kosong']], 200);
        // }

        $this->getdatamill($bulan, $reg);
        // dd($reg, $bulan, $estate);
    }

    private function getdatamill($bulan, $reg)
    {
        $get_bulan = $bulan;
        $get_regional = $reg;
        $data = DB::connection('mysql2')->table('grading_mill')
            ->join('estate', 'estate.est', '=', 'grading_mill.estate')
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->where('estate.emp', '!=', 1)
            ->where('wil.regional', $get_regional)
            ->where('grading_mill.datetime', 'like', '%' . $get_bulan . '%')
            ->orderBy('estate.est', 'asc')
            ->orderBy('grading_mill.afdeling', 'asc')
            ->get();
        $data = $data->groupBy(['estate']);
        $data = json_decode($data, true);

        // dd($data);
        $result = [];
        if (!empty($data)) {
            foreach ($data as $keys => $values) {
                $tonase = 0;
                $jumlah_janjang_grading = 0;
                $jumlah_janjang_spb = 0;
                $brondol_0 = 0;
                $brondol_less = 0;
                $overripe = 0;
                $empty_bunch = 0;
                $rotten_bunch = 0;
                $abn_partheno = 0;
                $abn_hard = 0;
                $abn_sakit = 0;
                $abn_kastrasi = 0;
                $longstalk = 0;
                $vcut = 0;
                $dirt = 0;
                $loose_fruit = 0;
                foreach ($values as $key => $value) {
                    $tonase += $value['tonase'];
                    $jumlah_janjang_grading += $value['jjg_grading'];
                    $jumlah_janjang_spb += $value['jjg_spb'];


                    $brondol_0 += $value['unripe_tanpa_brondol'];
                    $brondol_less += $value['unripe_kurang_brondol'];

                    $overripe += $value['overripe'];
                    $empty_bunch += $value['empty'];
                    $rotten_bunch += $value['rotten'];

                    $abn_partheno += $value['abn_partheno'];
                    $abn_hard += $value['abn_hard'];
                    $abn_sakit += $value['abn_sakit'];
                    $abn_kastrasi += $value['abn_kastrasi'];
                    $longstalk += $value['tangkai_panjang'];
                    $vcut += $value['vcut'];
                    $dirt += $value['dirt'];
                    $loose_fruit += $value['loose_fruit'];
                }
                $abnormal = $abn_partheno + $abn_hard + $abn_sakit +  $abn_kastrasi;
                $unripe = $brondol_0 + $brondol_less;
                $ripeness = $jumlah_janjang_grading - ($overripe + $empty_bunch + $rotten_bunch + $abnormal + $unripe);


                $loose_fruit_kg = ($loose_fruit / $tonase) * 100;
                $dirt_kg = ($dirt  / $tonase) * 100;

                // Calculate percentages
                $percentage_ripeness = ($ripeness / $jumlah_janjang_grading) * 100;
                $percentage_unripe = ($unripe / $jumlah_janjang_grading) * 100;
                $percentage_brondol_0 = ($brondol_0 / $jumlah_janjang_grading) * 100;
                $percentage_brondol_less = ($brondol_less / $jumlah_janjang_grading) * 100;
                $percentage_overripe = ($overripe / $jumlah_janjang_grading) * 100;
                $percentage_empty_bunch = ($empty_bunch / $jumlah_janjang_grading) * 100;
                // Rotten bunch and abnormal are missing, set to zero
                $percentage_rotten_bunch = ($rotten_bunch / $jumlah_janjang_grading) * 100;
                $percentage_abnormal = ($abnormal / $jumlah_janjang_grading) * 100;
                $percentage_longstalk = ($longstalk / $jumlah_janjang_grading) * 100;
                $percentage_vcut = ($vcut / $jumlah_janjang_grading) * 100;
                // Assume loose fruit and dirt percentages are given as a part of total weight

                // Calculate selisih janjang and percentage
                $jumlah_selisih_janjang = $jumlah_janjang_grading - $jumlah_janjang_spb;
                $percentage_selisih_janjang = ($jumlah_selisih_janjang / $jumlah_janjang_spb) * 100;
                $result[$keys][$key] = [
                    'tonase' => $tonase,
                    'jumlah_janjang_grading' => $jumlah_janjang_grading,
                    'jumlah_janjang_spb' => $jumlah_janjang_spb,
                    'brondol_0' => $brondol_0,
                    'brondol_less' => $brondol_less,


                    'ripeness' => $ripeness,
                    'percentage_ripeness' => $percentage_ripeness,
                    'unripe' => $unripe,
                    'percentage_unripe' => $percentage_unripe,
                    'overripe' => $overripe,
                    'percentage_overripe' => $percentage_overripe,
                    'empty_bunch' => $empty_bunch,
                    'percentage_empty_bunch' => $percentage_empty_bunch,
                    'rotten_bunch' => $rotten_bunch,
                    'percentage_rotten_bunch' => $percentage_rotten_bunch,
                    'abnormal' => $abnormal,
                    'percentage_abnormal' => $percentage_abnormal,
                    'longstalk' => $longstalk,
                    'percentage_longstalk' => $percentage_longstalk,
                    'vcut' => $vcut,
                    'percentage_vcut' => $percentage_vcut,
                    'dirt_kg' => $dirt,
                    'percentage_dirt' => $dirt_kg,
                    'loose_fruit_kg' => $loose_fruit,
                    'percentage_loose_fruit' => $loose_fruit_kg,

                ];
            }
        } else {
        }

        dd($result, $data);
        echo json_encode($result); //di decode ke dalam bentuk json dalam vaiavel arrview yang dapat menampung banyak isi array
        exit();
    }
}
