<?php

namespace App\Http\Controllers;

use App\Models\Estate;
use App\Models\Gradingmill;
use App\Models\Regional;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
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
        $type = 'perbulan';
        $this->getdatamill($bulan, $reg, $type);
    }
    public function gradingrekapmill(Request $request)
    {
        $reg = $request->input('reg');
        $bulan = $request->input('bulan');
        $type = 'perbulan';
        $this->getdatamill($bulan, $reg, $type);
    }
    public function getrekapperhari(Request $request)
    {
        $reg = $request->input('reg');
        $bulan = $request->input('bulan');
        $type = 'perhari';
        $this->getdatamill($bulan, $reg, $type);
    }

    public function getrekapperafdeling(Request $request)
    {
        $reg = $request->input('reg');
        $bulan = $request->input('bulan');
        $type = 'perafdeling';
        $this->getdatamill($bulan, $reg, $type);
    }
    private function getdatamill($bulan, $reg, $type)
    {
        $get_bulan = $bulan;
        $get_regional = $reg;
        $get_type = $type;
        // dd($get_type);
        if ($get_type === 'perbulan') {
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

            $wil = DB::connection('mysql2')->table('estate')
                ->select('estate.*', 'wil.nama as namawil')
                ->join('wil', 'wil.id', '=', 'estate.wil')
                ->where('wil.regional', $get_regional)
                ->whereNotIn('estate.est', ['CWS1', 'CWS2', 'CWS3'])
                ->where('estate.emp', '!=', 1)
                ->orderBy('estate.wil', 'asc')
                ->where('estate.est', '!=', 'PLASMA')
                ->get();
            $wil = $wil->groupBy(['namawil']);
            $wil = json_decode($wil, true);

            $mil = DB::connection('mysql2')->table('list_mill')
                ->select('list_mill.*')
                ->where('reg', $get_regional)
                ->get();
            $mil = $mil->groupBy(['nama_mill']);
            $mil = json_decode($mil, true);
            // dd($mil);
            $data_wil = [];
            foreach ($data as $key => $value) {
                foreach ($value as $key => $value1) {
                    foreach ($wil as $keywil => $wilval) {
                        foreach ($wilval as $keywil1 => $wilval1) {
                            if ($value1['estate'] === $wilval1['est']) {
                                $data_wil[$keywil][] = $value1;
                            }
                        }
                    }
                }
            }
            $data_mill = [];
            foreach ($data as $key => $value) {
                foreach ($value as $key => $value1) {
                    foreach ($mil as $keywil => $wilval) {
                        foreach ($wilval as $keywil1 => $wilval1) {
                            if ($value1['mill'] === $wilval1['mill']) {
                                $data_mill[$keywil][] = $value1;
                            }
                        }
                    }
                }
            }
            // dd($mil, $data_mill);
            // dd($wil, $data_wil);
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
                    $kelas_a = 0;
                    $kelas_b = 0;
                    $kelas_c = 0;
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
                        $kelas_a += $value['kelas_a'];
                        $kelas_b += $value['kelas_b'];
                        $kelas_c += $value['kelas_c'];
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
                    $persentage_kelas_a = ($kelas_a / $jumlah_janjang_grading) * 100;
                    $persentage_kelas_b = ($kelas_b / $jumlah_janjang_grading) * 100;
                    $persentage_kelas_c = ($kelas_c / $jumlah_janjang_grading) * 100;
                    // Assume loose fruit and dirt percentages are given as a part of total weight

                    // Calculate selisih janjang and percentage
                    $jumlah_selisih_janjang = $jumlah_janjang_grading - $jumlah_janjang_spb;
                    $percentage_selisih_janjang = ($jumlah_selisih_janjang / $jumlah_janjang_spb) * 100;
                    $result[$keys]['regional'] = [
                        'tonase' => $tonase,
                        'jumlah_janjang_grading' => $jumlah_janjang_grading,
                        'jumlah_janjang_spb' => $jumlah_janjang_spb,
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
                        'kelas_a' => $kelas_a,
                        'kelas_b' => $kelas_b,
                        'kelas_c' => $kelas_c,
                        'percentage_kelas_a' => $persentage_kelas_a,
                        'percentage_kelas_b' => $persentage_kelas_b,
                        'percentage_kelas_c' => $persentage_kelas_c,

                    ];
                }
            }
            $result_wil = [];
            if (!empty($data_wil)) {
                foreach ($data_wil as $keys => $values) {
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
                    $kelas_a = 0;
                    $kelas_b = 0;
                    $kelas_c = 0;
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
                        $kelas_a += $value['kelas_a'];
                        $kelas_b += $value['kelas_b'];
                        $kelas_c += $value['kelas_c'];
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
                    $persentage_kelas_a = ($kelas_a / $jumlah_janjang_grading) * 100;
                    $persentage_kelas_b = ($kelas_b / $jumlah_janjang_grading) * 100;
                    $persentage_kelas_c = ($kelas_c / $jumlah_janjang_grading) * 100;
                    // Assume loose fruit and dirt percentages are given as a part of total weight

                    // Calculate selisih janjang and percentage
                    $jumlah_selisih_janjang = $jumlah_janjang_grading - $jumlah_janjang_spb;
                    $percentage_selisih_janjang = ($jumlah_selisih_janjang / $jumlah_janjang_spb) * 100;
                    $result_wil[$keys]['wil'] = [
                        'tonase' => $tonase,
                        'jumlah_janjang_grading' => $jumlah_janjang_grading,
                        'jumlah_janjang_spb' => $jumlah_janjang_spb,
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
                        'kelas_a' => $kelas_a,
                        'kelas_b' => $kelas_b,
                        'kelas_c' => $kelas_c,
                        'percentage_kelas_a' => $persentage_kelas_a,
                        'percentage_kelas_b' => $persentage_kelas_b,
                        'percentage_kelas_c' => $persentage_kelas_c,

                    ];
                }
            }
            $result_mill = [];
            if (!empty($data_mill)) {
                foreach ($data_mill as $keys => $values) {
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
                    $kelas_a = 0;
                    $kelas_b = 0;
                    $kelas_c = 0;
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
                        $kelas_a += $value['kelas_a'];
                        $kelas_b += $value['kelas_b'];
                        $kelas_c += $value['kelas_c'];
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
                    $persentage_kelas_a = ($kelas_a / $jumlah_janjang_grading) * 100;
                    $persentage_kelas_b = ($kelas_b / $jumlah_janjang_grading) * 100;
                    $persentage_kelas_c = ($kelas_c / $jumlah_janjang_grading) * 100;
                    // Assume loose fruit and dirt percentages are given as a part of total weight

                    // Calculate selisih janjang and percentage
                    $jumlah_selisih_janjang = $jumlah_janjang_grading - $jumlah_janjang_spb;
                    $percentage_selisih_janjang = ($jumlah_selisih_janjang / $jumlah_janjang_spb) * 100;
                    $result_mill[$keys]['mil'] = [
                        'tonase' => $tonase,
                        'jumlah_janjang_grading' => $jumlah_janjang_grading,
                        'jumlah_janjang_spb' => $jumlah_janjang_spb,
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
                        'kelas_a' => $kelas_a,
                        'kelas_b' => $kelas_b,
                        'kelas_c' => $kelas_c,
                        'percentage_kelas_a' => $persentage_kelas_a,
                        'percentage_kelas_b' => $persentage_kelas_b,
                        'percentage_kelas_c' => $persentage_kelas_c,

                    ];
                }
            }
            // dd($result_wil);
            $arr = array();
            $arr['data_regional'] = $result;
            $arr['data_wil'] = $result_wil;
            $arr['data_mill'] = $result_mill;
            // dd($result, $data);
            echo json_encode($arr); //di decode ke dalam bentuk json dalam vaiavel arrview yang dapat menampung banyak isi array
            exit();
        } elseif ($get_type === 'perhari') {

            $data = DB::connection('mysql2')->table('grading_mill')
                ->join('estate', 'estate.est', '=', 'grading_mill.estate')
                ->join('wil', 'wil.id', '=', 'estate.wil')
                ->where('estate.emp', '!=', 1)
                ->where('wil.regional', $get_regional)
                ->where('grading_mill.datetime', 'like', '%' . $get_bulan . '%')
                ->orderBy('estate.est', 'asc')
                ->orderBy('grading_mill.afdeling', 'asc')
                ->get();
            $data = json_decode($data, true);

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


                    $brondol_0 = $value['unripe_tanpa_brondol'];
                    $brondol_less = $value['unripe_kurang_brondol'];

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
                    // Rotten bunch and abnormal are missing, set to zero
                    $percentage_rotten_bunch = ($rotten_bunch / $jumlah_janjang_grading) * 100;
                    $percentage_abnormal = ($abnormal / $jumlah_janjang_grading) * 100;
                    // Assume loose fruit and dirt percentages are given as a part of total weight

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
                                'no_pemanen' => ($get_pemanen == 999) ? 'x' : $get_pemanen,
                                'kurangBrondol' => $get_kurangBrondol,
                            ];
                        }
                        if ($get_kurangBrondol != 0) {
                            $tanpaBrondol['tanpaBrondol_list'][] = [
                                'no_pemanen' => ($get_pemanen == 999) ? 'x' : $get_pemanen,
                                'tanpaBrondol' => $get_tanpaBrondol,
                            ];
                        }
                    }

                    // dd($datakurang_brondol, $tanpaBrondol);



                    // Output results

                    $result[] = [
                        'id' => $value['id'],
                        'estate' => $value['estate'],
                        'afdeling' => $value['afdeling'],
                        'jjg_grading' => $value['jjg_grading'],
                        'no_plat' => $value['no_plat'],
                        'jjg_spb' => $value['jjg_spb'],
                        'datetime' => $value['datetime'],
                        'tonase' => $value['tonase'],
                        'bjr' => '-',
                        'jjg_selisih' => $jumlah_selisih_janjang,
                        'persentase_selisih' => round($percentage_selisih_janjang),
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
                        'stalk' =>    '-',
                        'persentase_stalk' =>    '-',
                        'vcut' =>    '-',
                        'persentase_vcut' =>    '-',
                        'loose_fruit' => $value['loose_fruit'],
                        'persentase_lose_fruit' => $loose_fruit_kg,
                        'Dirt' => $value['dirt'],
                        'persentase' => $dirt_kg,
                        'foto' => $foto,
                        'pemanen_list_tanpabrondol' => $tanpaBrondol,
                        'pemanen_list_kurangbrondol' => $datakurang_brondol,
                        'kelas_a' => '-',
                        'persentase_kelas_a' => '-',
                        'kelas_b' => '-',
                        'persentase_kelas_b' => '-',
                        'kelas_c' => '-',
                        'persentase_kelas_c' => '-',
                    ];
                }
                // dd($data, $result);
                // $result now contains the processed data


            }
            // dd($result);
            $arr = array();
            $arr['data_perhari'] = $result;
            // dd($result, $data);
            echo json_encode($arr); //di decode ke dalam bentuk json dalam vaiavel arrview yang dapat menampung banyak isi array
            exit();
        } else {
            $data = DB::connection('mysql2')->table('grading_mill')
                ->join('estate', 'estate.est', '=', 'grading_mill.estate')
                ->join('wil', 'wil.id', '=', 'estate.wil')
                ->where('estate.emp', '!=', 1)
                ->where('wil.regional', $get_regional)
                ->where('grading_mill.datetime', 'like', '%' . $get_bulan . '%')
                ->orderBy('estate.est', 'asc')
                ->orderBy('grading_mill.afdeling', 'asc')
                ->get();
            $data = $data->groupBy(['estate', 'afdeling']);
            $data = json_decode($data, true);
            // dd($data, $get_bulan);
            $result = [];
            if (!empty($data)) {
                foreach ($data as $keys => $values) {
                    foreach ($values as $key => $value) {
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
                        $kelas_a = 0;
                        $kelas_b = 0;
                        $kelas_c = 0;
                        foreach ($value as $key1 => $value1) {
                            $tonase += $value1['tonase'];
                            $jumlah_janjang_grading += $value1['jjg_grading'];
                            $jumlah_janjang_spb += $value1['jjg_spb'];


                            $brondol_0 += $value1['unripe_tanpa_brondol'];
                            $brondol_less += $value1['unripe_kurang_brondol'];

                            $overripe += $value1['overripe'];
                            $empty_bunch += $value1['empty'];
                            $rotten_bunch += $value1['rotten'];

                            $abn_partheno += $value1['abn_partheno'];
                            $abn_hard += $value1['abn_hard'];
                            $abn_sakit += $value1['abn_sakit'];
                            $abn_kastrasi += $value1['abn_kastrasi'];
                            $longstalk += $value1['tangkai_panjang'];
                            $vcut += $value1['vcut'];
                            $dirt += $value1['dirt'];
                            $loose_fruit += $value1['loose_fruit'];
                            $kelas_a += $value1['kelas_a'];
                            $kelas_b += $value1['kelas_b'];
                            $kelas_c += $value1['kelas_c'];
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
                        $persentage_kelas_a = ($kelas_a / $jumlah_janjang_grading) * 100;
                        $persentage_kelas_b = ($kelas_b / $jumlah_janjang_grading) * 100;
                        $persentage_kelas_c = ($kelas_c / $jumlah_janjang_grading) * 100;
                        // Assume loose fruit and dirt percentages are given as a part of total weight

                        // Calculate selisih janjang and percentage
                        $jumlah_selisih_janjang = $jumlah_janjang_grading - $jumlah_janjang_spb;
                        $percentage_selisih_janjang = ($jumlah_selisih_janjang / $jumlah_janjang_spb) * 100;
                        $result[$keys][$key] = [
                            'tonase' => $tonase,
                            'jumlah_janjang_grading' => $jumlah_janjang_grading,
                            'jumlah_janjang_spb' => $jumlah_janjang_spb,
                            'bjr' => '-',
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
                            'kelas_a' => $kelas_a,
                            'kelas_b' => $kelas_b,
                            'kelas_c' => $kelas_c,
                            'percentage_kelas_a' => $persentage_kelas_a,
                            'percentage_kelas_b' => $persentage_kelas_b,
                            'percentage_kelas_c' => $persentage_kelas_c,

                        ];
                    }
                }
            }

            $arr = array();
            $arr['data_pperafd'] = $result;
            // dd($result, $data);
            echo json_encode($arr); //di decode ke dalam bentuk json dalam vaiavel arrview yang dapat menampung banyak isi array
            exit();
        }
    }
}
