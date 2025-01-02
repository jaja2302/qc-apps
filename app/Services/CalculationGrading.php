<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Mockery\Matcher\Type;
use App\Models\Gradingmill;
// Carbon
class CalculationGrading
{
    /**
     * Get base query for grading mill data
     */
    private function getBaseQuery($regional, $bulan, $mill, $estate, $afdeling)
    {
        // dd($regional, $bulan, $mill);
        if ($mill) {
            return Gradingmill::where('datetime', 'like', '%' . $bulan . '%')
                ->whereHas('Listmill', function ($query) use ($mill) {
                    $query->where('id', $mill);
                })
                ->with('Listmill')
                ->orderBy('afdeling', 'asc');
        }
        if ($estate && $afdeling) {
            return DB::connection('mysql2')->table('grading_mill')
                ->select('*')
                ->where('estate', $estate)
                ->where('afdeling', $afdeling)
                ->where('datetime', 'LIKE', '%' . $bulan . '%');
        }
        return DB::connection('mysql2')->table('grading_mill')
            ->select('grading_mill.*', 'grading_mill.id as id_data')
            ->join('estate', 'estate.est', '=', 'grading_mill.estate')
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->where('estate.emp', '!=', 1)
            ->where('wil.regional', $regional)
            ->where('grading_mill.datetime', 'like', '%' . $bulan . '%')
            ->orderBy('estate.est', 'asc')
            ->orderBy('grading_mill.afdeling', 'asc');
    }

    private function getMill($regional)
    {
        $wil = DB::connection('mysql2')->table('estate')
            ->select('estate.*', 'wil.nama as namawil')
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->where('wil.regional', $regional)
            ->whereNotIn('estate.est', ['CWS1', 'CWS2', 'CWS3'])
            ->where('estate.emp', '!=', 1)
            ->orderBy('estate.wil', 'asc')
            ->where('estate.est', '!=', 'PLASMA')
            ->get();
        $wil = $wil->groupBy(['namawil']);
        $wil = json_decode($wil, true);

        $mil = DB::connection('mysql2')->table('list_mill')
            ->select('list_mill.*')
            ->where('reg', $regional)
            ->get();
        $mil = $mil->groupBy(['nama_mill']);
        $mil = json_decode($mil, true);
        return [
            'wil' => $wil,
            'mil' => $mil
        ];
    }
    /**
     * Calculate basic metrics for grading
     */

    /**
     * Get grading data based on type (perbulan/perhari/perafdeling)
     */
    public function getGradingData($bulan, $regional, $type, $mill = null, $estate = null, $afdeling = null)
    {
        // dd($mill);
        $query = $this->getBaseQuery($regional, $bulan, $mill, $estate, $afdeling);

        return $this->CalculationGrading($query, $bulan, $regional, $type, $mill, $estate, $afdeling);
    }

    private function CalculationGrading($query, $bulan, $regional, $type, $mill, $estate, $afdeling)
    {
        $data = collect($query->get());
        // dd($data);
        switch ($type) {
            case 'perbulan':
                $data = $data->groupBy(['estate']);
                $data = json_decode($data, true);
                $query_mill_wil = $this->getMill($regional);

                $data_wil = $this->groupBy($data, $query_mill_wil['wil'], 'wil');
                $data_mill = $this->groupBy($data, $query_mill_wil['mil'], 'mil');
                // dd($data);

                $result = $this->processGradingData($data, $type);
                $result_wil = $this->processGradingData($data_wil, $type);
                $result_mill = $this->processGradingData($data_mill, $type);
                // dd($data_mill);
                return [
                    "data_regional" => $result,
                    "data_wil" => $result_wil,
                    "data_mill" => $result_mill
                ];
            default:
                if ($estate && $afdeling) {
                    $data = json_decode($data, true);
                } else {
                    $data = $data->groupBy(['estate', 'afdeling']);
                    $data = json_decode($data, true);
                }

                // dd($data);,
                return $this->processGradingData($data, $type, $estate, $afdeling);
        }
        return $data;
    }

    private function groupBy($data, $query_mill_wil, $key_group)
    {
        $data_group = [];
        $compareKey = $key_group === 'wil' ? 'estate' : 'mill';
        $matchKey = $key_group === 'wil' ? 'est' : 'mill';

        foreach ($data as $items) {
            foreach ($items as $item) {
                foreach ($query_mill_wil as $groupKey => $groupValues) {
                    foreach ($groupValues as $value) {
                        if ($item[$compareKey] === $value[$matchKey]) {
                            $data_group[$groupKey][] = $item;
                        }
                    }
                }
            }
        }

        return $data_group;
    }

    private function processGradingData($data, $type, $estate = null, $afdeling = null)
    {
        // dd($data, $type);
        $result = [];
        foreach ($data as $keys => $values) {

            if ($type !== 'perbulan') {
                // dd($values, 'no perbulan');
                $data_2 = [];

                if ($estate && $afdeling) {
                    // dd($estate, $afdeling);
                    // dd($result, '1');
                    $data_level_1 = $this->getValueData($values, $estate, $afdeling);
                    // dd($data_level_1);
                    $data_arr_level_1 = $this->formula_grading($data_level_1);
                    $result[$keys] = $this->formatResult($data_arr_level_1);
                } else {
                    // dd($result, '2');
                    foreach ($values as $key => $value) {
                        foreach ($value as $key2 => $value2) {
                            $data_level_0 = $this->getValueData($value2, $keys, $key);
                            // dd($data_level_1);
                            $data_arr_level_0 = $this->formula_grading($data_level_0);
                            // dd($data_arr_level_1);
                            $result[$keys][$key]['data'][] = $this->formatResult($data_arr_level_0);
                        }
                        $data_level_1 = $this->getValueData($value);
                        // dd($data_level_1);
                        $data_arr_level_1 = $this->formula_grading($data_level_1);
                        $result[$keys][$key]['afdeling'] = $this->formatResult($data_arr_level_1);
                        $data_2[] = $this->formatResult($data_arr_level_1);
                    }

                    // Calculate total units for data_level_2
                    $total_units = 0;
                    foreach ($values as $value) {
                        $total_units += count($value);
                    }

                    $data_level_2 = $this->getValueData($data_2);
                    $data_arr_level_2 = $this->formula_grading($data_level_2);
                    // Override the unit count in the total
                    $data_arr_level_2['unit'] = $total_units;
                    $result[$keys]['Total'] = $this->formatResult($data_arr_level_2);
                }
            } else {
                // dd($values, 'perbulan');
                // // For estate totals, sum up all units from afdelings
                // $total_units = 0;
                // foreach ($values as $value) {
                //     $total_units += count($value); // Count arrays in each afdeling
                // }
                // dd($values, 'perbulan');
                $data_level_3 = $this->getValueData($values);
                // dd($data_level_3);
                $data_arr_level_3 = $this->formula_grading($data_level_3);
                $result[$keys]['data'] = $this->formatResult($data_arr_level_3);
                // Override the unit count for totals
                // $result[$keys]['data']['unit'] = $total_units;
            }
        }
        // dd($result);
        return $result;
    }


    public function getValueData($value, $estate = null, $afdeling = null)
    {
        // dd($value, $estate, $afdeling);

        if ($estate && $afdeling) {
            // dd($value, 'value');
            $cleaned_string = str_replace(['[', ']'], '', $value['foto_temuan']);
            $foto = explode(',', $cleaned_string);

            // Trim spaces from each element in the array
            $foto = array_map('trim', $foto);

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
            return [
                'estate' => $value['estate'],
                'afdeling' => $value['afdeling'],
                'jjg_grading' => $value['jjg_grading'],
                'jjg_spb' => $value['jjg_spb'],
                'unripe_tanpa_brondol' => $value['unripe_tanpa_brondol'],
                'unripe_kurang_brondol' => $value['unripe_kurang_brondol'],
                'overripe' => $value['overripe'],
                'empty' => $value['empty'],
                'rotten' => $value['rotten'],
                'tangkai_panjang' => $value['tangkai_panjang'],
                'vcuts' => $value['vcut'],
                'tonase' => $value['tonase'],
                'dirt' => $value['dirt'],
                'loose_fruit' => $value['loose_fruit'],
                'abn_partheno' => $value['abn_partheno'],
                'abn_hard' => $value['abn_hard'],
                'abn_sakit' => $value['abn_sakit'],
                'abn_kastrasi' => $value['abn_kastrasi'],
                'kelas_a' => $value['kelas_a'],
                'kelas_b' => $value['kelas_b'],
                'kelas_c' => $value['kelas_c'],
                'datetime' =>  Carbon::parse($value['datetime'])->format('H:i'),
                'no_plat' => $value['no_plat'],
                'unit' => 1,
                'getunit' => [0],
                'foto' => $foto,
                'pemanen_list_tanpabrondol' => $tanpaBrondol,
                'pemanen_list_kurangbrondol' => $datakurang_brondol,
                'status_bot' => $value['status_bot'],
                'id' => $value['id'],
            ];
        } else {
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
            $unit = count($value);
            $estate = '-';
            $afdeling = '=';
            $no_plat = '-';
            $getunit = [];
            $inc = 0;
            $datetime = '-';
            foreach ($value as $key2 => $value1) {
                $tonase += $value1['tonase'];
                $jumlah_janjang_grading += $value1['jjg_grading'];
                $jumlah_janjang_spb += $value1['jjg_spb'];
                $brondol_0 += is_numeric($value1['unripe_tanpa_brondol']) ? $value1['unripe_tanpa_brondol'] : 0;
                $brondol_less += is_numeric($value1['unripe_kurang_brondol']) ? $value1['unripe_kurang_brondol'] : 0;
                $overripe += $value1['overripe'];
                $empty_bunch += $value1['empty'] ?? $value1['empty_bunch'];
                $rotten_bunch += $value1['rotten'] ?? $value1['rotten_bunch'];

                $abn_partheno += $value1['abn_partheno'];
                $abn_hard += $value1['abn_hard'];
                $abn_sakit += $value1['abn_sakit'];
                $abn_kastrasi += $value1['abn_kastrasi'];
                $longstalk += $value1['tangkai_panjang'] ?? $value1['longstalk'];
                $vcut += $value1['vcut'];
                $dirt += $value1['dirt'];
                $loose_fruit += $value1['loose_fruit'];
                $kelas_a += $value1['kelas_a'];
                $kelas_b += $value1['kelas_b'];
                $kelas_c += $value1['kelas_c'];
                $no_plat = $value1['no_plat'];
                $datetime = Carbon::parse($value1['datetime'])->format('H:i');
                $estate = $value1['estate'];
                $afdeling = $value1['afdeling'];
                $getunit[] = $inc++;
            }
            // dd('value1');
            return [
                'estate' => $estate,
                'afdeling' => $afdeling,
                'jjg_grading' => $jumlah_janjang_grading,
                'jjg_spb' => $jumlah_janjang_spb,
                'unripe_tanpa_brondol' => $brondol_0,
                'unripe_kurang_brondol' => $brondol_less,
                'overripe' => $overripe,
                'empty' => $empty_bunch,
                'rotten' => $rotten_bunch,
                'tangkai_panjang' => $longstalk,
                'vcuts' => $vcut,
                'tonase' => $tonase,
                'dirt' => $dirt,
                'loose_fruit' => $loose_fruit,
                'abn_partheno' => $abn_partheno,
                'abn_hard' => $abn_hard,
                'abn_sakit' => $abn_sakit,
                'abn_kastrasi' => $abn_kastrasi,
                'kelas_a' => $kelas_a,
                'kelas_b' => $kelas_b,
                'kelas_c' => $kelas_c,
                'datetime' => $datetime,
                'no_plat' => $no_plat,
                'unit' => $unit,
                'getunit' => $getunit,
            ];
        }
    }


    public function formula_grading($array)
    {
        // dd($array);
        $loose_fruit_kg = $array['tonase'] > 0 ? round(($array['loose_fruit'] / $array['tonase']) * 100, 2) : 0;
        $dirt_kg = $array['tonase'] > 0 ? round(($array['dirt'] / $array['tonase']) * 100, 2) : 0;
        $abnormal = $array['abn_partheno'] + $array['abn_hard'] + $array['abn_sakit'] + $array['abn_kastrasi'];
        $unripe = $array['unripe_tanpa_brondol'] + $array['unripe_kurang_brondol'];
        $ripeness = $array['jjg_grading'] - ($array['overripe'] + $array['empty'] + $array['rotten'] + $abnormal + $unripe);

        // Calculate percentages
        $percentage_ripeness = $array['jjg_grading'] > 0 ? ($ripeness / $array['jjg_grading']) * 100 : 0;
        $percentage_unripe = $array['jjg_grading'] > 0 ? ($unripe / $array['jjg_grading']) * 100 : 0;
        $percentage_brondol_0 = $array['jjg_grading'] > 0 ? ($array['unripe_tanpa_brondol'] / $array['jjg_grading']) * 100 : 0;
        $percentage_brondol_less = $array['jjg_grading'] > 0 ? ($array['unripe_kurang_brondol'] / $array['jjg_grading']) * 100 : 0;
        $percentage_overripe = $array['jjg_grading'] > 0 ? ($array['overripe'] / $array['jjg_grading']) * 100 : 0;
        $percentage_empty_bunch = $array['jjg_grading'] > 0 ? ($array['empty'] / $array['jjg_grading']) * 100 : 0;
        $percentage_rotten_bunch = $array['jjg_grading'] > 0 ? ($array['rotten'] / $array['jjg_grading']) * 100 : 0;
        $percentage_abnormal = $array['jjg_grading'] > 0 ? ($abnormal / $array['jjg_grading']) * 100 : 0;
        $percentage_tangkai_panjang = $array['jjg_grading'] > 0 ? ($array['tangkai_panjang'] / $array['jjg_grading']) * 100 : 0;
        $percentage_vcuts = $array['jjg_grading'] > 0 ? ($array['vcuts'] / $array['jjg_grading']) * 100 : 0;
        $percentage_kelas_a = $array['jjg_grading'] > 0 ? ($array['kelas_a'] / $array['jjg_grading']) * 100 : 0;
        $percentage_kelas_b = $array['jjg_grading'] > 0 ? ($array['kelas_b'] / $array['jjg_grading']) * 100 : 0;
        $percentage_kelas_c = $array['jjg_grading'] > 0 ? ($array['kelas_c'] / $array['jjg_grading']) * 100 : 0;

        // Calculate selisih janjang and percentage
        $jumlah_selisih_janjang = $array['jjg_grading'] - $array['jjg_spb'];
        $percentage_selisih_janjang = $array['jjg_spb'] > 0 ? ($jumlah_selisih_janjang / $array['jjg_spb']) * 100 : 0;

        return [
            'estate' => $array['estate'],
            'afdeling' => $array['afdeling'],
            'jjg_grading' => $array['jjg_grading'],
            'jjg_spb' => $array['jjg_spb'],
            'no_plat' => $array['no_plat'],
            'abn_partheno' => $array['abn_partheno'],
            'abn_hard' => $array['abn_hard'],
            'abn_sakit' => $array['abn_sakit'],
            'abn_kastrasi' => $array['abn_kastrasi'],
            'unit' => $array['unit'],
            'tonase' => $array['tonase'],
            'datetime' => $array['datetime'] ?? '-',
            'bjr' => $array['jjg_grading'] > 0 ? round(($array['tonase'] / $array['jjg_grading']), 2) : 0,
            // 'bjr' => $array['jjg_grading'],
            'jjg_selisih' => $jumlah_selisih_janjang,
            'persentase_selisih' => round($percentage_selisih_janjang),
            'Ripeness' => $ripeness,
            'percentase_ripenes' => round($percentage_ripeness, 2),
            'Unripe' => $unripe,
            'persenstase_unripe' => round($percentage_unripe, 2),
            'unripe_tanpa_brondol' => $array['unripe_tanpa_brondol'],
            'persentase_unripe_tanpa_brondol' => round($percentage_brondol_0, 2),
            'unripe_kurang_brondol' => $array['unripe_kurang_brondol'],
            'persentase_unripe_kurang_brondol' => round($percentage_brondol_less, 2),
            'overripe' => $array['overripe'],
            'persentase_overripe' => round($percentage_overripe, 2),
            'empty' => $array['empty'],
            'persentase_empty_bunch' => round($percentage_empty_bunch, 2),
            'rotten' => $array['rotten'],
            'persentase_rotten_bunce' => round($percentage_rotten_bunch, 2),
            'Abnormal' => $abnormal,
            'persentase_abnormal' => round($percentage_abnormal, 2),
            'tangkai_panjang' => $array['tangkai_panjang'],
            'persentase_stalk' => round($percentage_tangkai_panjang, 2),
            'vcuts' => $array['vcuts'],
            'persentase_vcut' => round($percentage_vcuts, 2),
            'loose_fruit' => $array['loose_fruit'],
            'persentase_lose_fruit' => $loose_fruit_kg,
            'dirt' => $array['dirt'],
            'persentase' => $dirt_kg,
            'kelas_a' => $array['kelas_a'],
            'persentase_kelas_a' => round($percentage_kelas_a, 2),
            'kelas_b' => $array['kelas_b'],
            'persentase_kelas_b' => round($percentage_kelas_b, 2),
            'kelas_c' => $array['kelas_c'],
            'persentase_kelas_c' => round($percentage_kelas_c, 2),
            'getunit' => $array['getunit'],
            'foto' => $array['foto'] ?? [],
            'pemanen_list_tanpabrondol' => $array['pemanen_list_tanpabrondol'] ?? [],
            'pemanen_list_kurangbrondol' => $array['pemanen_list_kurangbrondol'] ?? [],
            'status_bot' => $array['status_bot'] ?? 0,
            'id' => $array['id'] ?? 0,
        ];
    }

    public function formatResult($array)
    {
        return [
            'estate' => $array['estate'],
            'afdeling' => $array['afdeling'],
            'datetime' => $array['datetime'] ?? '-',
            'tonase' => $array['tonase'],
            'jjg_grading' => $array['jjg_grading'],
            'jjg_spb' => $array['jjg_spb'],
            'bjr' => $array['bjr'],
            'ripeness' => $array['Ripeness'],
            'percentage_ripeness' => $array['percentase_ripenes'],
            'unripe' => $array['Unripe'],
            'percentage_unripe' => $array['persenstase_unripe'],
            'overripe' => $array['overripe'],
            'percentage_overripe' => $array['persentase_overripe'],
            'empty_bunch' => $array['empty'],
            'percentage_empty_bunch' => $array['persentase_empty_bunch'],
            'rotten_bunch' => $array['rotten'],
            'percentage_rotten_bunch' => $array['persentase_rotten_bunce'],
            'abnormal' => $array['Abnormal'],
            'percentage_abnormal' => $array['persentase_abnormal'],
            'abn_partheno' => $array['abn_partheno'],
            'abn_hard' => $array['abn_hard'],
            'abn_sakit' => $array['abn_sakit'],
            'abn_kastrasi' => $array['abn_kastrasi'],
            'longstalk' => $array['tangkai_panjang'],
            'percentage_longstalk' => $array['persentase_stalk'],
            'vcut' => $array['vcuts'],
            'percentage_vcut' => $array['persentase_vcut'],
            'dirt' => $array['dirt'],
            'percentage_dirt' => $array['persentase'],
            'loose_fruit' => $array['loose_fruit'],
            'percentage_loose_fruit' => $array['persentase_lose_fruit'],
            'kelas_a' => $array['kelas_a'],
            'kelas_b' => $array['kelas_b'],
            'kelas_c' => $array['kelas_c'],
            'percentage_kelas_a' => $array['persentase_kelas_a'],
            'percentage_kelas_b' => $array['persentase_kelas_b'],
            'percentage_kelas_c' => $array['persentase_kelas_c'],
            'no_plat' => $array['no_plat'],
            'unit' => $array['unit'],
            'unripe_tanpa_brondol' => $array['unripe_tanpa_brondol'],
            'persentase_unripe_tanpa_brondol' => $array['persentase_unripe_tanpa_brondol'],
            'unripe_kurang_brondol' => $array['unripe_kurang_brondol'],
            'persentase_unripe_kurang_brondol' => $array['persentase_unripe_kurang_brondol'],
            'getunit' => $array['getunit'],
            'foto' => $array['foto'] ?? [],
            'pemanen_list_tanpabrondol' => $array['pemanen_list_tanpabrondol'] ?? [],
            'pemanen_list_kurangbrondol' => $array['pemanen_list_kurangbrondol'] ?? [],
            'status_bot' => $array['status_bot'] ?? 0,
            'id' => $array['id'] ?? 0,
        ];
    }
}
