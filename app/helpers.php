<?php
// Important functions

use App\Models\mutu_ancak;
use Illuminate\Support\Facades\DB;

if (!function_exists('count_percent')) {
    function count_percent($skor1, $skor2)
    {
        return $skor2 == 0 ? $skor1 : round(($skor1 / $skor2) * 100, 3);
    }
}

if (!function_exists('skor_brd_tinggal')) {
    function skor_brd_tinggal($skor)
    {
        if ($skor <= 3) {
            return 10;
        } else if ($skor >= 3 && $skor <= 5) {
            return 8;
        } else if ($skor >= 5 && $skor <= 7) {
            return 6;
        } else if ($skor >= 7 && $skor <= 9) {
            return 4;
        } else if ($skor >= 9 && $skor <= 11) {
            return 2;
        } else if ($skor >= 11) {
            return 0;
        }
    }
}

if (!function_exists('skor_buah_tinggal')) {
    function skor_buah_tinggal($skor)
    {
        if ($skor <= 0) {
            return 10;
        } else if ($skor > 0 && $skor <= 0.5) {
            return 8;
        } else if ($skor >= 0.5 && $skor <= 1) {
            return 6;
        } else if ($skor >= 1.0 && $skor <= 1.5) {
            return 4;
        } else if ($skor >= 1.5 && $skor <= 2.0) {
            return 2;
        } else if ($skor >= 2.0 && $skor <= 2.5) {
            return 0;
        } else if ($skor >= 2.5 && $skor <= 3.0) {
            return -2;
        } else if ($skor >= 3.0 && $skor <= 3.5) {
            return -4;
        } else if ($skor >= 3.5 && $skor <= 4.0) {
            return -6;
        } else if ($skor >= 4.0) {
            return -8;
        }
    }
}

//mutubuah

if (!function_exists('skor_buah_mentah_mb')) {
    function skor_buah_mentah_mb($skor)
    {
        if ($skor <= 1) {
            return 10;
        } else if ($skor >= 1.0 && $skor <= 2.0) {
            return 8;
        } else if ($skor >= 2.0 && $skor <= 3.0) {
            return 6;
        } else if ($skor >= 3.0 && $skor <= 4.0) {
            return 4;
        } else if ($skor >= 4.0 && $skor <= 5.0) {
            return 2;
        } else if ($skor >= 5.0) {
            return 0;
        }
    }
}

if (!function_exists('skor_buah_masak_mb')) {
    function skor_buah_masak_mb($skor)
    {
        if ($skor <= 75.0) {
            return 0;
        } else if ($skor >= 75.0 && $skor <= 80.0) {
            return 1;
        } else if ($skor >= 80.0 && $skor <= 85.0) {
            return 2;
        } else if ($skor >= 85.0 && $skor <= 90.0) {
            return 3;
        } else if ($skor >= 90.0 && $skor <= 95.0) {
            return 4;
        } else if ($skor >= 95.0) {
            return 5;
        }
    }
}

if (!function_exists('skor_buah_over_mb')) {
    function skor_buah_over_mb($skor)
    {
        if ($skor <= 2.0) {
            return 5;
        } else if ($skor >= 2.0 && $skor <= 4.0) {
            return 4;
        } else if ($skor >= 4.0 && $skor <= 6.0) {
            return 3;
        } else if ($skor >= 6.0 && $skor <= 8.0) {
            return 2;
        } else if ($skor >= 8.0 && $skor <= 10.0) {
            return 1;
        } else if ($skor >= 10.0) {
            return 0;
        }
    }
}

if (!function_exists('skor_jangkos_mb')) {
    function skor_jangkos_mb($skor)
    {
        if ($skor <= 1.0) {
            return 5;
        } else if ($skor >= 1.0 && $skor <= 2.0) {
            return 4;
        } else if ($skor >= 2.0 && $skor <= 3.0) {
            return 3;
        } else if ($skor >= 3.0 && $skor <= 4.0) {
            return 2;
        } else if ($skor >= 4.0 && $skor <= 5.0) {
            return 1;
        } else if ($skor >= 5.0) {
            return 0;
        }
    }
}

if (!function_exists('skor_abr_mb')) {
    function skor_abr_mb($skor)
    {
        if ($skor >= 100) {
            return 5;
        } else if ($skor >= 90 && $skor <= 100) {
            return 4;
        } else if ($skor >= 80 && $skor <= 90) {
            return 3;
        } else if ($skor >= 70 && $skor <= 80) {
            return 2;
        } else if ($skor >= 60 && $skor <= 70) {
            return 1;
        } else if ($skor <= 60) {
            return 0;
        }
    }
}

if (!function_exists('skor_brd_ma')) {
    function skor_brd_ma($skor)
    {
        if ($skor <= 1.0) {
            return 20;
        } else if ($skor >= 1 && $skor <= 1.5) {
            return 16;
        } else if ($skor >= 1.5 && $skor <= 2.0) {
            return 12;
        } else if ($skor >= 2.0 && $skor <= 2.5) {
            return 8;
        } else if ($skor >= 2.5 && $skor <= 3.0) {
            return 4;
        } else if ($skor >= 3.0 && $skor <= 3.5) {
            return 0;
        } else if ($skor >= 3.5 && $skor <= 4.0) {
            return -4;
        } else if ($skor >= 4.0 && $skor <= 4.5) {
            return -8;
        } else if ($skor >=  4.5 && $skor <= 5.0) {
            return -12;
        } else if ($skor >=  5.0) {
            return -16;
        }
    }
}

if (!function_exists('skor_buah_Ma')) {
    function skor_buah_Ma($skor)
    {
        if ($skor <=  0.0) {
            return 20;
        } else if ($skor >=  0.0 && $skor <= 1.0) {
            return 18;
        } else if ($skor >= 1 && $skor <= 1.5) {
            return 16;
        } else if ($skor >= 1.5 && $skor <= 2.0) {
            return 12;
        } else if ($skor >= 2.0 && $skor <= 2.5) {
            return 8;
        } else if ($skor >= 2.5 && $skor <= 3.0) {
            return 4;
        } else if ($skor >= 3.0 && $skor <= 3.5) {
            return 0;
        } else if ($skor >= 3.5 && $skor <= 4.0) {
            return -4;
        } else if ($skor >= 4.0 && $skor <= 4.5) {
            return -8;
        } else if ($skor >= 4.5 && $skor <= 5.0) {
            return -12;
        } else if ($skor >= 5.0) {
            return -16;
        }
    }
}

if (!function_exists('skor_palepah_ma')) {
    function skor_palepah_ma($skor)
    {
        if ($skor <=  0.5) {
            return  5;
        } else if ($skor >=  0.5 && $skor <= 1.0) {
            return  4;
        } else if ($skor >= 1.0 && $skor <= 1.5) {
            return  3;
        } else if ($skor >= 1.5 && $skor <= 2.0) {
            return  2;
        } else if ($skor >= 2.0 && $skor <= 2.5) {
            return  1;
        } else if ($skor >= 2.5) {
            return  0;
        }
    }
}

if (!function_exists('skor_kategori_akhir')) {
    function skor_kategori_akhir($skor)
    {
        if ($skor >= 95.0 && $skor <= 100.0) {
            $color = "#4874c4";
            $text = "EXCELLENT";
            return array($color, $text);
        } else if ($skor >= 85.0 && $skor < 95.0) {
            $color = "#00ff2e";
            $text = "GOOD";
            return array($color, $text);
        } else if ($skor >= 75.0 && $skor < 85.0) {
            $color = "yellow";
            $text = "SATISFACTORY";
            return array($color, $text);
        } else if ($skor >= 65.0 && $skor < 75.0) {
            $color = "orange";
            $text = "FAIR";
            return array($color, $text);
        } else if ($skor < 65.0) {
            $color = "red";
            $text = "POOR";
            return array($color, $text);
        }
    }
}

if (!function_exists('check_array')) {
    function check_array($key, $value)
    {
        if (array_key_exists($key, $value)) {
            return $value[$key];
        } else {
            return 0;
        }
    }
}


//mutubuah
if (!function_exists('skor_buah_mentah_mb')) {
    function skor_buah_mentah_mb($skor)
    {
        if ($skor <= 1.0) {
            return 10;
        } else if ($skor >= 1.0 && $skor <= 2.0) {
            return 8;
        } else if ($skor >= 2.0 && $skor <= 3.0) {
            return 6;
        } else if ($skor >= 3.0 && $skor <= 4.0) {
            return 4;
        } else if ($skor >= 4.0 && $skor <= 5.0) {
            return 2;
        } else if ($skor >= 5.0) {
            return 0;
        }
    }
}

if (!function_exists('skor_buah_masak_mb')) {
    function skor_buah_masak_mb($skor)
    {
        if ($skor <= 75.0) {
            return 0;
        } else if ($skor >= 75.0 && $skor <= 80.0) {
            return 1;
        } else if ($skor >= 80.0 && $skor <= 85.0) {
            return 2;
        } else if ($skor >= 85.0 && $skor <= 90.0) {
            return 3;
        } else if ($skor >= 90.0 && $skor <= 95.0) {
            return 4;
        } else if ($skor >= 95.0) {
            return 5;
        }
    }
}

if (!function_exists('skor_buah_over_mb')) {
    function skor_buah_over_mb($skor)
    {
        if ($skor <= 2.0) {
            return 5;
        } else if ($skor >= 2.0 && $skor <= 4.0) {
            return 4;
        } else if ($skor >= 4.0 && $skor <= 6.0) {
            return 3;
        } else if ($skor >= 6.0 && $skor <= 8.0) {
            return 2;
        } else if ($skor >= 8.0 && $skor <= 10.0) {
            return 1;
        } else if ($skor >= 10.0) {
            return 0;
        }
    }
}

if (!function_exists('skor_vcut_mb')) {
    function skor_vcut_mb($skor)
    {

        if ($skor <= 2.0) {
            return  5;
        } else if ($skor >= 2.0 && $skor <= 4.0) {
            return  4;
        } else if ($skor >= 4.0 && $skor <= 6.0) {
            return  3;
        } else if ($skor >= 6.0 && $skor <= 8.0) {
            return  2;
        } else if ($skor >= 8.0 && $skor <= 10.0) {
            return  1;
        } else if ($skor >= 10.0) {
            return  0;
        }
    }
}

if (!function_exists('skor_jangkos_mb')) {
    function skor_jangkos_mb($skor)
    {
        if ($skor <= 1.0) {
            return 5;
        } else if ($skor >= 1.0 && $skor <= 2.0) {
            return 4;
        } else if ($skor >= 2.0 && $skor <= 3.0) {
            return 3;
        } else if ($skor >= 3.0 && $skor <= 4.0) {
            return 2;
        } else if ($skor >= 4.0 && $skor <= 5.0) {
            return 1;
        } else if ($skor >= 5.0) {
            return 0;
        }
    }
}

if (!function_exists('skor_abr_mb')) {
    function skor_abr_mb($skor)
    {
        if ($skor >= 100) {
            return 5;
        } else if ($skor >= 90 && $skor <= 100) {
            return 4;
        } else if ($skor >= 80 && $skor <= 90) {
            return 3;
        } else if ($skor >= 70 && $skor <= 80) {
            return 2;
        } else if ($skor >= 60 && $skor <= 70) {
            return 1;
        } else if ($skor <= 60) {
            return 0;
        }
    }
}

//buat sidak tph

if (!function_exists('skorBRDsidak')) {
    function skorBRDsidak($skor)
    {
        if ($skor <= 0.0) {
            return 30;
        } else if ($skor >= 0.0 && $skor <= 18.0) {
            return 28;
        } else if ($skor >= 18.0 && $skor <= 30) {
            return 26;
        } else if ($skor >= 30.0 && $skor <= 42.0) {
            return 22;
        } else if ($skor >= 42.0 && $skor <= 54.0) {
            return 18;
        } else if ($skor >= 54.0 && $skor <= 66.0) {
            return 14;
        } else if ($skor >= 66.0 && $skor <= 78.0) {
            return 10;
        } else if ($skor >= 78.0 && $skor <= 90.0) {
            return 6;
        } else if ($skor >= 90.0 && $skor <= 96.0) {
            return 2;
        } else if ($skor >= 96.0) {
            return 0;
        }
    }
}

if (!function_exists('skorKRsidak')) {
    function skorKRsidak($skor)
    {
        if ($skor <= 0) {
            return 20;
        } else if ($skor >= 0.0 && $skor <= 1.0) {
            return 17;
        } else if ($skor >= 1.0 && $skor <= 2.0) {
            return 14;
        } else if ($skor >= 2.0 && $skor <= 3.0) {
            return 11;
        } else if ($skor >= 3.0 && $skor <= 4.0) {
            return 8;
        } else if ($skor >= 4.0 && $skor <= 5.0) {
            return 5;
        } else if ($skor >= 5.0 && $skor <= 6.0) {
            return 2;
        } else if ($skor >= 6.0) {
            return 0;
        }
    }
}

if (!function_exists('skorBHsidak')) {
    function skorBHsidak($skor)
    {
        if ($skor <= 0) {
            return 20;
        } else if ($skor >= 0.0 && $skor <= 3.0) {
            return 17;
        } else if ($skor >= 3.0 && $skor <= 6.0) {
            return 14;
        } else if ($skor >= 6.0 && $skor <= 9.0) {
            return 11;
        } else if ($skor >= 9.0 && $skor <= 12.0) {
            return 8;
        } else if ($skor >= 12.0 && $skor <= 15.0) {
            return 5;
        } else if ($skor >= 15.0 && $skor <= 18.0) {
            return 2;
        } else if ($skor >= 18.0) {
            return 0;
        }
    }
}

if (!function_exists('skorRSsidak')) {
    function skorRSsidak($skor)
    {
        if ($skor <= 0.0) {
            return 30;
        } else if ($skor >= 0.0 && $skor <= 3.0) {
            return 26;
        } else if ($skor >= 3.0 && $skor <= 6.0) {
            return 22;
        } else if ($skor >= 6.0 && $skor <= 9.0) {
            return 18;
        } else if ($skor >= 9.0 && $skor <= 12.0) {
            return 14;
        } else if ($skor >= 12.0 && $skor <= 15.0) {
            return 10;
        } else if ($skor >= 15.0 && $skor <= 18.0) {
            return 6;
        } else if ($skor >= 18.0 && $skor <= 21.0) {
            return 2;
        } else if ($skor >= 21.0) {
            return 0;
        }
    }
}

if (!function_exists('skor_bt_tph')) {
    function skor_bt_tph($skor)
    {
        if ($skor == 0) {
            return 30;
        } elseif ($skor > 0 && $skor <= 18) {
            return 28;
        } elseif ($skor > 18 && $skor <= 30) {
            return 26;
        } elseif ($skor > 30 && $skor <= 42) {
            return 22;
        } elseif ($skor > 42 && $skor <= 54) {
            return 18;
        } elseif ($skor > 54 && $skor <= 66) {
            return 14;
        } elseif ($skor > 66 && $skor <= 78) {
            return 10;
        } elseif ($skor > 78 && $skor <= 90) {
            return 6;
        } elseif ($skor > 90 && $skor <= 96) {
            return 2;
        } else {
            return 0;
        }
    }
}

if (!function_exists('skor_krg_tph')) {
    function skor_krg_tph($skor)
    {
        if ($skor <= 0) {
            return 20;
        } elseif ($skor > 0 && $skor <= 1) {
            return 17;
        } elseif ($skor > 1 && $skor <= 2) {
            return 14;
        } elseif ($skor > 2 && $skor <= 3) {
            return 11;
        } elseif ($skor > 3 && $skor <= 4) {
            return 8;
        } elseif ($skor > 4 && $skor <= 5) {
            return 5;
        } elseif ($skor > 5 && $skor <= 6) {
            return 2;
        } else {
            return 0;
        }
    }
}

if (!function_exists('skor_buah_tph')) {
    function skor_buah_tph($skor)
    {
        if ($skor <= 0) {
            return 20;
        } elseif ($skor > 0 && $skor <= 3) {
            return 17;
        } elseif ($skor > 3 && $skor <= 6) {
            return 14;
        } elseif ($skor > 6 && $skor <= 9) {
            return 11;
        } elseif ($skor > 9 && $skor <= 12) {
            return 8;
        } elseif ($skor > 12 && $skor <= 15) {
            return 5;
        } elseif ($skor > 15 && $skor <= 18) {
            return 2;
        } else {
            return 0;
        }
    }
}

if (!function_exists('skor_rst_tph')) {
    function skor_rst_tph($skor)
    {
        if ($skor <= 0) {
            return 30;
        } elseif ($skor > 0 && $skor <= 3) {
            return 26;
        } elseif ($skor > 3 && $skor <= 6) {
            return 22;
        } elseif ($skor > 6 && $skor <= 9) {
            return 18;
        } elseif ($skor > 9 && $skor <= 12) {
            return 14;
        } elseif ($skor > 12 && $skor <= 15) {
            return 10;
        } elseif ($skor > 15 && $skor <= 18) {
            return 6;
        } elseif ($skor > 18 && $skor <= 21) {
            return 2;
        } else {
            return 0;
        }
    }
}

// untuk sidak_mutu_buah 

if (!function_exists('sidak_brdTotal')) {
    function sidak_brdTotal($skor)
    {
        if ($skor >= 0 && $skor <= 1) {
            return  30;
        } elseif ($skor > 1 && $skor <= 2) {
            return  26;
        } elseif ($skor > 2 && $skor <= 3) {
            return  22;
        } elseif ($skor > 3 && $skor <= 4) {
            return  18;
        } elseif ($skor > 4 && $skor <= 5) {
            return  14;
        } elseif ($skor > 5 && $skor <= 6) {
            return  10;
        } elseif ($skor > 6 && $skor <= 7) {
            return  6;
        } elseif ($skor > 7 && $skor <= 8) {
            return  2;
        } else {
            return  0;
        }
    }
}
if (!function_exists('sidak_matangSKOR')) {
    function sidak_matangSKOR($skor)
    {
        if ($skor >= 95 && $skor <= 100) {
            return  25;
        } elseif ($skor > 90 && $skor <= 95) {
            return  20;
        } elseif ($skor > 85 && $skor <= 90) {
            return  15;
        } elseif ($skor > 80 && $skor <= 85) {
            return  10;
        } elseif ($skor > 75 && $skor <= 80) {
            return  5;
        } else {
            return  0;
        }
    }
}
if (!function_exists('sidak_lwtMatang')) {
    function sidak_lwtMatang($skor)
    {
        if ($skor >= 0 && $skor <= 2) {
            return  15;
        } elseif ($skor > 2 && $skor <= 4) {
            return  12;
        } elseif ($skor > 4 && $skor <= 6) {
            return  9;
        } elseif ($skor > 6 && $skor <= 8) {
            return  6;
        } elseif ($skor > 8 && $skor <= 10) {
            return  3;
        } else {
            return  0;
        }
    }
}
if (!function_exists('sidak_jjgKosong')) {
    function sidak_jjgKosong($skor)
    {

        if ($skor >= 0 && $skor <= 1) {
            return  15;
        } elseif ($skor > 1 && $skor <= 2) {
            return  12;
        } elseif ($skor > 2 && $skor <= 3) {
            return  9;
        } elseif ($skor > 3 && $skor <= 4) {
            return  6;
        } elseif ($skor > 4 && $skor <= 5) {
            return  3;
        } else {
            return  0;
        }
    }
}
if (!function_exists('sidak_tangkaiP')) {
    function sidak_tangkaiP($skor)
    {
        if ($skor >= 0 && $skor <= 2) {
            return  10;
        } elseif ($skor > 2 && $skor <= 4) {
            return  8;
        } elseif ($skor > 4 && $skor <= 6) {
            return  6;
        } elseif ($skor > 6 && $skor <= 8) {
            return  4;
        } elseif ($skor > 8 && $skor <= 10) {
            return  2;
        } else {
            return  0;
        }
    }
}

if (!function_exists('sidak_PengBRD')) {
    function sidak_PengBRD($skor)
    {

        if ($skor >= 100) {
            return  5;
        } elseif ($skor >= 90 && $skor < 100) {
            return  4;
        } elseif ($skor >= 80 && $skor < 90) {
            return  3;
        } elseif ($skor >= 70 && $skor < 80) {
            return  2;
        } elseif ($skor >= 60 && $skor < 70) {
            return  1;
        } else {
            return  0;
        }
    }
}
if (!function_exists('sidak_akhir')) {
    function sidak_akhir($skor)
    {
        if ($skor >= 95) {
            return  "EXCELLENT";
        } elseif ($skor >= 85) {
            return  "GOOD";
        } elseif ($skor >= 75) {
            return  "SATISFACTORY";
        } elseif ($skor >= 65) {
            return  "FAIR";
        } else {
            return  "POOR";
        }
    }
}


if (!function_exists('updateKeyRecursive')) {

    function updateKeyRecursive(&$array, $oldKey, $newKey)
    {
        foreach ($array as $key => &$value) {
            if ($key === $oldKey) {
                $array[$newKey] = $value;
                unset($array[$oldKey]);
            }
            if (is_array($value)) {
                updateKeyRecursive($value, $oldKey, $newKey);
            }
        }
    }
}

// helpers.php

if (!function_exists('updateKeyRecursive2')) {
    function updateKeyRecursive2($array)
    {
        return array_map(function ($value) {
            return ($value === "KTE4") ? "KTE" : $value;
        }, $array);
    }
}

if (!function_exists('updateKeyRecursive3')) {
    function updateKeyRecursive3(&$array, $oldKey, $newKey)
    {
        if (array_key_exists($oldKey, $array)) {
            $array[$newKey] = $array[$oldKey];
            unset($array[$oldKey]);
        }
    }
}
if (!function_exists('changeKey')) {
    function changeKey(&$array, $oldKey, $newKey)
    {
        if (array_key_exists($oldKey, $array)) {
            $array[$newKey] = $array[$oldKey];
            unset($array[$oldKey]);
        }
    }
}
if (!function_exists('changeKTE4ToKTE')) {
    function changeKTE4ToKTE($array)
    {
        foreach ($array as &$subArray) {
            foreach ($subArray as &$item) {
                if (isset($item['est']) && $item['est'] === 'KTE4') {
                    $item['est'] = 'KTE';
                    if (isset($item['est_afd'])) {
                        $item['est_afd'] = str_replace('KTE4_', 'KTE_', $item['est_afd']);
                    }
                }
            }
        }

        return $array;
    }
}
if (!function_exists('BpthKTE')) {
    function BpthKTE($array)
    {
        foreach ($array as &$subArray) {
            foreach ($subArray as &$item) {
                if (isset($item['est']) && $item['est'] === 'KTE4') {
                    $item['est'] = 'KTE';
                }
            }
        }

        return $array;
    }
}
if (!function_exists('calculateValue')) {
    function calculateValue($sum, $total)
    {
        if ($total != 0) {
            return round($sum / $total, 2);
        } else {
            return 0;
        }
    }
}

if (!function_exists('calculatePanen')) {
    function calculatePanen($status_panen)
    {
        $panen_brd = 0; // Initialize panen_brd
        $panen_jjg = 0; // Initialize panen_jjg

        if ($status_panen == 0) {
            $panen_brd = 0;
            $panen_jjg = 0;
        } else if ($status_panen == 1) {
            $panen_brd = 1;
            $panen_jjg = 5;
        } else if ($status_panen == 2) {
            $panen_brd = 1.5;
            $panen_jjg = 7;
        } else if ($status_panen == 3) {
            $panen_brd = 2;
            $panen_jjg = 9;
        } else if ($status_panen == 4) {
            $panen_brd = 3;
            $panen_jjg = 11;
        } else if ($status_panen == 5) {
            $panen_brd = 4;
            $panen_jjg = 14;
        } else if ($status_panen == 6) {
            $panen_brd = 5;
            $panen_jjg = 17;
        } else if ($status_panen == 7) {
            $panen_brd = 6;
            $panen_jjg = 20;
        } else if ($status_panen >= 7) {
            $panen_brd = 8;
            $panen_jjg = 25;
        }

        return [$panen_brd, $panen_jjg];
    }
}

if (!function_exists('calculatePanennew')) {
    function calculatePanennew($status_panen)
    {
        $panen_brd = 0; // Initialize panen_brd
        $panen_jjg = 0; // Initialize panen_jjg

        if ($status_panen == 0) {
            $panen_brd = 0;
            $panen_jjg = 0;
        } else if ($status_panen == 1) {
            $panen_brd = 1.00;
            $panen_jjg = 50.00;
        } else if ($status_panen == 2) {
            $panen_brd = 1.25;
            $panen_jjg = 75.0;
        } else if ($status_panen == 3) {
            $panen_brd = 1.50;
            $panen_jjg = 100.0;
        } else if ($status_panen == 4) {
            $panen_brd = 2.00;
            $panen_jjg = 150.0;
        } else if ($status_panen == 5) {
            $panen_brd = 2.50;
            $panen_jjg = 200.0;
        } else if ($status_panen == 6) {
            $panen_brd = 3.00;
            $panen_jjg = 250.0;
        } else if ($status_panen == 7) {
            $panen_brd = 3.50;
            $panen_jjg = 300.0;
        } else if ($status_panen >= 7) {
            $panen_brd = 4.50;
            $panen_jjg = 400.0;
        }

        return [$panen_brd, $panen_jjg];
    }
}



if (!function_exists('list_wil')) {
    function list_wil($collection)
    {
        $collection->transform(function ($innerCollection) {
            $innerCollection->transform(function ($item) {
                if (isset($item->est) && $item->est === 'KTE4') {
                    $item->est = 'KTE';
                }
                return $item;
            });

            return $innerCollection;
        });

        return $collection;
    }
}
if (!function_exists('convertToRoman')) {
    function convertToRoman($number)
    {
        $result = '';

        $numerals = array(
            'M'  => 1000,
            'CM' => 900,
            'D'  => 500,
            'CD' => 400,
            'C'  => 100,
            'XC' => 90,
            'L'  => 50,
            'XL' => 40,
            'X'  => 10,
            'IX' => 9,
            'V'  => 5,
            'IV' => 4,
            'I'  => 1
        );

        foreach ($numerals as $numeral => $value) {
            while ($number >= $value) {
                $result .= $numeral;
                $number -= $value;
            }
        }

        return $result;
    }
}

if (!function_exists('detectDuplicates')) {
    function detectDuplicates($data, $columns)
    {
        $temp = [];
        $result = [];

        foreach ($data as $item) {
            $identifier = '';
            foreach ($columns as $column) {
                $identifier .= $item->{$column};
            }

            if (isset($temp[$identifier])) {
                $result[] = $item->id;
            } else {
                $temp[$identifier] = true;
            }
        }

        return $result;
    }
}

if (!function_exists('sendwhatsapp')) {
    function sendwhatsapp($dataarr)
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
            CURLOPT_POSTFIELDS => array('message' => $dataarr, 'id_group' => '120363205553012899@g.us'),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
    }
}

if (!function_exists('formatPhoneNumber')) {
    function formatPhoneNumber($number)
    {
        // Remove any non-numeric characters from the input number
        $number = preg_replace('/\D/', '', $number);

        // Check if the number starts with '0'
        if (strpos($number, '0') === 0) {
            // Replace '0' with '62'
            return '62' . substr($number, 1);
        } else if (strpos($number, '8') === 0) {
            // Replace '0' with '62'
            return '62' . $number;
        } else {
            // If it doesn't start with '0', return as is
            return $number;
        }
    }
}

if (!function_exists('can_edit')) {
    function can_edit()
    {
        $user = auth()->user();
        $newJabatan = $user->id_jabatan;
        $oldJabatan = $user->jabatan;
        $newDepartemen = $user->id_departement;
        $oldDepartemen = $user->departemen;

        $allowedJabatan = ['6', '7', '15', '5'];
        $allowedDepartments = ['43'];

        // Check new department and position
        if ($newJabatan !== null && in_array($newDepartemen, $allowedDepartments) && in_array($newJabatan, $allowedJabatan)) {
            return true;
        }

        // Check old department and position
        $allowedOldJabatan = ['Askep', 'Manager', 'Asisten', 'Askep/Asisten'];
        $allowedOldDepartments = ['QC', 'Quality Control'];

        if ($newJabatan === null && in_array($oldDepartemen, $allowedOldDepartments) && in_array($oldJabatan, $allowedOldJabatan)) {
            return true;
        }

        return false;
    }
}


if (!function_exists('fetchDataByMonthAndGroupInChunks')) {
    function fetchDataByMonthAndGroupInChunks($table, $RegData, $year, $month)
    {
        $queryResult = [];

        DB::connection('mysql2')->table($table)
            ->select("{$table}.*", 'estate.*', DB::raw('DATE_FORMAT(' . $table . '.datetime, "%M") as bulan'), DB::raw('DATE_FORMAT(' . $table . '.datetime, "%Y") as tahun'), DB::raw('DATE_FORMAT(' . $table . '.datetime, "%Y-%m-%d") as date'))
            ->join('estate', 'estate.est', '=', "{$table}.estate")
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->where('datetime', 'like', $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '%')
            ->where('wil.regional', $RegData)
            ->where('estate.emp', '!=', 1)
            ->orderBy('estate', 'DESC')
            ->orderBy('afdeling', 'DESC')
            ->orderBy('blok', 'DESC')
            ->orderBy('datetime', 'DESC')
            ->chunk(1000, function ($data) use (&$queryResult) {
                $groupedData = $data->groupBy(['estate', 'afdeling', 'bulan']);
                foreach ($groupedData as $estate => $afdelings) {
                    foreach ($afdelings as $afdeling => $bulanData) {
                        foreach ($bulanData as $bulan => $records) {
                            if (!isset($queryResult[$estate])) {
                                $queryResult[$estate] = [];
                            }
                            if (!isset($queryResult[$estate][$afdeling])) {
                                $queryResult[$estate][$afdeling] = [];
                            }
                            if (!isset($queryResult[$estate][$afdeling][$bulan])) {
                                $queryResult[$estate][$afdeling][$bulan] = [];
                            }
                            $queryResult[$estate][$afdeling][$bulan] = array_merge($queryResult[$estate][$afdeling][$bulan], $records->toArray());
                        }
                    }
                }
            });

        return $queryResult;
    }
}

if (!function_exists('fetchDataAndGroupByYearInChunks')) {
    function fetchDataAndGroupByYearInChunks($table, $RegData, $year)
    {
        $finalResult = [];

        for ($month = 1; $month <= 12; $month++) {
            $monthlyData = fetchDataByMonthAndGroupInChunks($table, $RegData, $year, $month);
            foreach ($monthlyData as $estate => $afdelings) {
                if (!isset($finalResult[$estate])) {
                    $finalResult[$estate] = [];
                }
                foreach ($afdelings as $afdeling => $bulanData) {
                    if (!isset($finalResult[$estate][$afdeling])) {
                        $finalResult[$estate][$afdeling] = [];
                    }
                    foreach ($bulanData as $bulan => $data) {
                        if (!isset($finalResult[$estate][$afdeling][$bulan])) {
                            $finalResult[$estate][$afdeling][$bulan] = [];
                        }
                        $finalResult[$estate][$afdeling][$bulan] = array_merge($finalResult[$estate][$afdeling][$bulan], $data);
                    }
                }
            }
        }

        return $finalResult;
    }
}
if (!function_exists('getnilaitphreg2_perblok_satu_dimensi')) {
    function getnilaitphreg2_perblok_satu_dimensi($mutuAncak, $mutuTransport, $reg)
    {
        // berlaku hanya untuk array perblok satu demensi 

        // Summarize the mutuAncak array
        $ancakRegss2 = [];
        foreach ($mutuAncak as $key => $value) {
            $listBlok = [];
            $firstEntry = $value[0];
            $first = ($firstEntry['luas_blok'] != 0) ? $firstEntry['luas_blok'] : '-';

            foreach ($value as $entry) {
                if (!in_array($entry['estate'] . ' ' . $entry['afdeling'] . ' ' . $entry['blok'], $listBlok)) {
                    if ($entry['sph'] != 0) {
                        $listBlok[] = $entry['estate'] . ' ' . $entry['afdeling'] . ' ' . $entry['blok'];
                    }
                }
            }
            $ancakRegss2[$key]['luas_blok'] = $first;

            $ancakRegss2[$key]['status_panen'] = explode(",", $firstEntry['status_panen'])[0];
        }

        // Process the mutuTransport array
        $transNewdata = [];
        foreach ($mutuTransport as $key => $value) {
            $sum_bt = 0;
            $sum_Restan = 0;
            $tph_sample = 0;
            $listBlokPerAfd = [];

            foreach ($value as $entry) {
                $listBlokPerAfd[] = $entry['estate'] . ' ' . $entry['afdeling'] . ' ' . $entry['blok'];
                $sum_Restan += $entry['rst'];
                $sum_bt += $entry['bt'];
            }

            $tph_sample = count($listBlokPerAfd);
            $panenKey = $ancakRegss2[$key]['status_panen'] ?? 0;
            $LuasKey = $ancakRegss2[$key]['luas_blok'] ?? 0;

            $transNewdata[$key]['status_panen'] = $panenKey;
            $transNewdata[$key]['luas_blok'] = $LuasKey;
            $transNewdata[$key]['sum_Restan'] = $sum_Restan;
            $transNewdata[$key]['sum_bt'] = $sum_bt;

            if ($reg == 2) {

                if ($panenKey !== 0 && $panenKey <= 3) {
                    if (is_array($value) && count($value) == 1 && $value[0]['blok'] == '0') {
                        $tph_sample = $value[0]['tph_baris'];
                        $sum_bt = $value[0]['bt'];
                    } else {
                        $transNewdata[$key]['tph_sample'] = round(floatval($LuasKey) * 1.3, 3);
                    }
                } else {
                    $transNewdata[$key]['tph_sample'] = $tph_sample;
                }
            } else {
                $transNewdata[$key]['tph_sample'] = $tph_sample;
            }


            $transNewdata[$key]['afdeling'] = $value[0]['afdeling'];
            $transNewdata[$key]['estate'] = $value[0]['estate'];
        }

        // Add data from ancakRegss2 if not present in transNewdata
        $tph_tod = 0;
        foreach ($ancakRegss2 as $key => $value) {
            if (!isset($transNewdata[$key])) {
                $transNewdata[$key] = $value;

                if ($value['status_panen'] <= 3) {
                    $transNewdata[$key]['tph_sample'] = round(floatval($value['luas_blok']) * 1.3, 3);
                } else {
                    $transNewdata[$key]['tph_sample'] = 0;
                }

                // Set default values for sum_Restan and sum_bt if not present
                $transNewdata[$key]['sum_Restan'] = 0;
                $transNewdata[$key]['sum_bt'] = 0;
            }

            if (isset($value['tph_sample'])) {
                $tph_tod += $value['tph_sample'];
            }
        }

        // Calculate the total tph_sample
        $tph_sample_total = 0;
        foreach ($transNewdata as $key => $value) {
            if (isset($value['tph_sample'])) {
                $tph_sample_total += $value['tph_sample'];
            }
        }

        // return [$transNewdata, $tph_sample_total];
        return $transNewdata;
    }
}


// helper perblok perhari 

if (!function_exists('rekap_blok')) {
    function rekap_blok($est, $afd, $date, $reg)
    {

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
        $rekap = [];

        $transnewdata = getnilaitphreg2_perblok_satu_dimensi($mutuAncak, $mutuTransport, $reg);


        foreach ($mutuAncak as $key => $value) {
            $akp = 0;
            $skor_bTinggal = 0;
            $brdPerjjg = 0;

            $ttlSkorMA = 0;
            $ttlSkorMAess = 0;
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
            $sph = 0;
            $check_input = 'kosong';
            foreach ($value as $key1 => $value1) {
                if (!in_array($value1['estate'] . ' ' . $value1['afdeling'] . ' ' . $value1['blok'], $listBlokPerAfd)) {
                    $listBlokPerAfd[] = $value1['estate'] . ' ' . $value1['afdeling'] . ' ' . $value1['blok'];
                    if ($value1['sph'] != 0) {
                        $sph += $value1['sph'];
                    }
                }
                $jum_ha = count($listBlokPerAfd);

                $totalPokok += $value1['sample'];
                $totalPanen +=  $value1['jjg'];
                $totalP_panen += $value1['brtp'];
                $totalK_panen += $value1['brtk'];
                $totalPTgl_panen += $value1['brtgl'];

                $totalbhts_panen += $value1['bhts'];
                $totalbhtm1_panen += $value1['bhtm1'];
                $totalbhtm2_panen += $value1['bhtm2'];
                $totalbhtm3_oanen += $value1['bhtm3'];

                $totalpelepah_s += $value1['ps'];
                $check_input = $value1['jenis_input'];
                $nilai_input = $value1['skor_akhir'];
                $status_panen = explode(',', $value1['status_panen']);
                $status_panen = $status_panen[0];
            }
            $jml_sph = $jum_ha == 0 ? $sph : ($sph / $jum_ha);
            $luas_ha = ($jml_sph != 0) ? round(($totalPokok / $jml_sph), 2) : 0;

            if ($totalPokok != 0) {
                $akp = $totalPanen / $totalPokok * 100;
            } else {
                $akp = 0;
            }


            $skor_bTinggal = $totalP_panen + $totalK_panen + $totalPTgl_panen;

            if ($totalPanen != 0) {
                $brdPerjjg = $skor_bTinggal / $totalPanen;
            } else {
                $brdPerjjg = 0;
            }

            $sumBH = $totalbhts_panen +  $totalbhtm1_panen +  $totalbhtm2_panen +  $totalbhtm3_oanen;
            if ($sumBH != 0) {
                $sumPerBH = $sumBH / ($totalPanen + $sumBH) * 100;
            } else {
                $sumPerBH = 0;
            }

            if ($totalpelepah_s != 0) {
                $perPl = ($totalpelepah_s / $totalPokok) * 100;
            } else {
                $perPl = 0;
            }
            $nonZeroValues = array_filter([$totalP_panen, $totalK_panen, $totalPTgl_panen, $totalbhts_panen, $totalbhtm1_panen, $totalbhtm2_panen, $totalbhtm3_oanen]);

            if (!empty($nonZeroValues)) {
                $rekap[$key]['check_datacak'] = 'ada';
            } else {
                $rekap[$key]['check_datacak'] = 'kosong';
            }

            // $ttlSkorMA = $skor_bh + $skor_brd + $skor_ps;
            $ttlSkorMA =  skor_buah_Ma($sumPerBH) + skor_brd_ma($brdPerjjg) + skor_palepah_ma($perPl);

            // $namaGM = '-';
            // foreach ($queryAsisten as $asisten) {

            //     // dd($asisten);
            //     if ($asisten['est'] == $key1 && $asisten['afd'] == $key2) {
            //         $namaGM = $asisten['nama'];
            //         break;
            //     }
            // }
            $rekap[$key]['pokok_samplecak'] = $totalPokok;
            $rekap[$key]['bulan'] = $key1;
            $rekap[$key]['luas_ha'] = $luas_ha;
            $rekap[$key]['namaGM'] = '-';
            $rekap[$key]['ha_samplecak'] = $jum_ha;
            $rekap[$key]['jumlah_panencak'] = $totalPanen;
            $rekap[$key]['status_panen'] = $status_panen;
            $rekap[$key]['akp_rlcak'] = $akp;
            $rekap[$key]['pcak'] = $totalP_panen;
            $rekap[$key]['kcak'] = $totalK_panen;
            $rekap[$key]['tglcak'] = $totalPTgl_panen;
            $rekap[$key]['total_brdcak'] = $skor_bTinggal;
            $rekap[$key]['brdperjjgcak'] = $brdPerjjg;
            // data untuk buah tinggal
            $rekap[$key]['bhts_scak'] = $totalbhts_panen;
            $rekap[$key]['bhtm1cak'] = $totalbhtm1_panen;
            $rekap[$key]['bhtm2cak'] = $totalbhtm2_panen;
            $rekap[$key]['bhtm3cak'] = $totalbhtm3_oanen;
            $rekap[$key]['buahperjjgcak'] = $sumPerBH;
            $rekap[$key]['total_buahcak'] = $sumBH;
            $rekap[$key]['jjgperBuahcak'] = number_format($sumPerBH, 2);
            // data untuk pelepah sengklek
            $rekap[$key]['palepah_pokokcak'] = $totalpelepah_s;
            $rekap[$key]['palepah_percak'] = $perPl;
            $rekap[$key]['skor_bhcak'] = skor_buah_Ma($sumPerBH);
            $rekap[$key]['skor_brdcak'] = skor_brd_ma($brdPerjjg);
            $rekap[$key]['skor_pscak'] =  skor_palepah_ma($perPl);
            // total skor akhir
            $rekap[$key]['skor_akhircak'] = $ttlSkorMA;
            $rekap[$key]['check_inputcak'] = $check_input;
            $rekap[$key]['blok'] = $key1;
            $rekap[$key]['mutuancak'] = '-----------------------------------';
        }
        // dd($transnewdata);
        foreach ($transnewdata as $key => $value) {
            $dataBLok = $value['tph_sample'];
            $status_panen = $value['status_panen'];

            $brdPertph = ($dataBLok != 0) ? $value['sum_bt'] / $dataBLok : 0;
            $buahPerTPH = ($dataBLok != 0) ? $value['sum_Restan'] / $dataBLok : 0;

            $nonZeroValues = array_filter([$value['sum_bt'], $value['sum_Restan']]);
            $rekap[$key]['check_datatrans'] = !empty($nonZeroValues) ? 'ada' : 'kosong';
            $totalSkor = skor_brd_tinggal($brdPertph) + skor_buah_tinggal($buahPerTPH);
            $rekap[$key]['tph_sampleNew'] = $dataBLok;
            $rekap[$key]['status_panen'] = $status_panen;
            $rekap[$key]['total_brdtrans'] = $value['sum_bt'];
            $rekap[$key]['total_brdperTPHtrans'] = $brdPertph;
            $rekap[$key]['total_buahtrans'] = $value['sum_Restan'];
            $rekap[$key]['total_buahPerTPHtrans'] = $buahPerTPH;
            $rekap[$key]['skor_brdPertphtrans'] = skor_brd_tinggal($brdPertph);
            $rekap[$key]['skor_buahPerTPHtrans'] = skor_buah_tinggal($buahPerTPH);
            $rekap[$key]['totalSkortrans'] = $totalSkor;
            $rekap[$key]['mututrans'] = '-----------------------------------';
        }

        foreach ($rekap as $key => $value) {
            $skorTrans = $value['totalSkortrans'] ?? 0;
            $skorAncak = $value['skor_akhircak'] ?? 0;

            if ($skorTrans != 0 || $skorAncak != 0) {
                $skorTotal = $skorTrans + $skorAncak;
                $skorAkhir = (int) round(($skorTotal * 100) / 65);

                if ($skorAkhir == 100) {
                    $skorAkhir -= 1;
                }
            } else {
                $skorAkhir = 0;
            }

            if ($reg != 1) {
                $skorAkhir -= 1;
            }

            $ktg = skor_kategori_akhir($skorAkhir);
            // dd($ktg);
            $rekap[$key]['skorAkhir'] = $skorAkhir;
            $rekap[$key]['kategori'] = $ktg[1];
            $rekap[$key]['totaleakhir'] = '-----------------------------------';
        }

        // dd($rekap);

        return $rekap;
    }
}

//helper pertahun reg->bulan->wil->estate->afdeling->value
if (!function_exists('rekap_pertahun')) {
    function rekap_pertahun($year, $RegData)
    {
        $queryAsisten = DB::connection('mysql2')->table('asisten_qc')->get();
        $queryAsisten = json_decode($queryAsisten, true);

        $querySidak = DB::connection('mysql2')->table('mutu_transport')
            ->select("mutu_transport.*")
            ->get();
        $DataEstate = $querySidak->groupBy(['estate', 'afdeling']);
        $DataEstate = json_decode($DataEstate, true);

        $queryAfd = DB::connection('mysql2')->table('afdeling')
            ->select(
                '*',
                'afdeling.nama as afdnama',
                'estate.est',
                'wil.regional'
            )
            ->join('estate', 'estate.id', '=', 'afdeling.estate')
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->where('wil.regional', $RegData)
            ->where('estate.emp', '!=', 1)
            ->get();
        $queryAfd = $queryAfd->groupBy(['regional', 'wil', 'est', 'afdnama']);
        $queryAfd = json_decode($queryAfd, true);
        $querytahun = fetchDataAndGroupByYearInChunks('mutu_ancak_new', $RegData, $year);
        $queryMTbuah = fetchDataAndGroupByYearInChunks('mutu_buah', $RegData, $year);
        $queryMTtrans = fetchDataAndGroupByYearInChunks('mutu_transport', $RegData, $year);

        // dd($queryMTtrans);
        $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $defaultAncak = [];

        foreach ($queryAfd as $afdKey => $afdValue) {
            foreach ($afdValue as $blockKey => $blockValue) {
                foreach ($blockValue as $yearKey => $yearValue) {
                    foreach ($yearValue as $monthKey => $monthValue) {
                        foreach ($querytahun as $queryYearKey => $queryYearValue) {
                            if ($yearKey === $queryYearKey) {
                                foreach ($queryYearValue as $dataKey => $dataValue) {
                                    if ($dataKey === $monthKey) {
                                        foreach ($months as $month) {
                                            $defaultAncak[$afdKey][$month][$blockKey][$yearKey][$monthKey] = $dataValue[$month] ?? 0;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        // dd($defaultAncak);

        $defaultbuah = [];

        foreach ($queryAfd as $afdKey => $afdValue) {
            foreach ($afdValue as $blockKey => $blockValue) {
                foreach ($blockValue as $yearKey => $yearValue) {
                    foreach ($yearValue as $monthKey => $monthValue) {
                        foreach ($queryMTbuah as $queryYearKey => $queryYearValue) {
                            if ($yearKey === $queryYearKey) {
                                foreach ($queryYearValue as $dataKey => $dataValue) {
                                    if ($dataKey === $monthKey) {
                                        foreach ($months as $month) {
                                            $defaultbuah[$afdKey][$month][$blockKey][$yearKey][$monthKey] = $dataValue[$month] ?? 0;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }


        $defaulttrans = [];
        foreach ($queryAfd as $afdKey => $afdValue) {
            foreach ($afdValue as $blockKey => $blockValue) {
                foreach ($blockValue as $yearKey => $yearValue) {
                    foreach ($yearValue as $monthKey => $monthValue) {
                        foreach ($queryMTtrans as $queryYearKey => $queryYearValue) {
                            if ($yearKey === $queryYearKey) {
                                foreach ($queryYearValue as $dataKey => $dataValue) {
                                    if ($dataKey === $monthKey) {
                                        foreach ($months as $month) {
                                            $defaulttrans[$afdKey][$month][$blockKey][$yearKey][$monthKey] = $dataValue[$month] ?? 0;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        function groupByBlok($array)
        {
            $result = [];

            foreach ($array as $regional => $months) {
                foreach ($months as $month => $estates) {
                    foreach ($estates as $estate => $afdelings) {
                        foreach ($afdelings as $afdeling => $entries) {
                            foreach ($entries as $afds => $entry) {
                                if (is_array($entry)) {
                                    foreach ($entry as $bloks) {
                                        // Extract the blok from each entry
                                        $blok = $bloks->blok;
                                        $date = $bloks->date;
                                        // dd($bloks);
                                        // Initialize the blok array if it doesn't exist
                                        if (!isset($result[$regional][$month][$estate][$afdeling][$afds][$date][$blok])) {
                                            $result[$regional][$month][$estate][$afdeling][$afds][$date][$blok] = [];
                                        }

                                        // Add the entry to the respective blok
                                        $result[$regional][$month][$estate][$afdeling][$afds][$date][$blok][] = $bloks;
                                    }
                                }
                            }
                        }
                    }
                }
            }

            return $result;
        }

        $groupblokAncak = groupByBlok($defaultAncak);
        // dd($groupblokAncak[2]['January'][4]['MRE']);
        $groupblokTrans = groupByBlok($defaulttrans);
        // dd($groupblokAncak[2]['July'][4]);

        // dd($defaultAncak[2]['January'][4]['MRE']);
        // Process groupblokAncak to fill ancakRegss2
        $ancakRegss2 = array();
        foreach ($groupblokAncak as $key => $value) {
            foreach ($value as $key1 => $value2) {
                foreach ($value2 as $key2 => $value3) {
                    foreach ($value3 as $key3 => $value4) {
                        foreach ($value4 as $key4 => $value5) {
                            foreach ($value5 as $key5 => $value6) {
                                $sum = 0;
                                $count = 0;
                                foreach ($value6 as $key6 => $value7) {
                                    $listBlok = array();
                                    $firstEntry = $value7[0];
                                    foreach ($value7 as $key7 => $value9) {
                                        if (!in_array($value9->estate . ' ' . $value9->afdeling . ' ' . $value9->blok, $listBlok)) {
                                            if ($value9->sph != 0) {
                                                $listBlok[] = $value9->estate . ' ' . $value9->afdeling . ' ' . $value9->blok;
                                            }
                                        }
                                        $jml_blok = count($listBlok);

                                        if ($firstEntry->luas_blok != 0) {
                                            $first = $firstEntry->luas_blok;
                                        } else {
                                            $first = '-';
                                        }
                                    }
                                    if ($first != '-') {
                                        $sum += $first;
                                        $count++;
                                    }
                                    $ancakRegss2[$key][$key1][$key2][$key3][$key4][$key5][$key6]['luas_blok'] = $first;
                                    if ($RegData === '2') {
                                        $status_panen = explode(",", $value9->status_panen);
                                        $ancakRegss2[$key][$key1][$key2][$key3][$key4][$key5][$key6]['status_panen'] = $status_panen[0];
                                    } else {
                                        $ancakRegss2[$key][$key1][$key2][$key3][$key4][$key5][$key6]['status_panen'] = $value9->status_panen;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        // dd($ancakRegss2);
        // Process groupblokTrans to fill transNewdata
        $transNewdata = array();
        foreach ($groupblokTrans as $key => $value) {
            foreach ($value as $key1 => $value1) {
                foreach ($value1 as $key2 => $value2) {
                    foreach ($value2 as $key3 => $value3) {
                        foreach ($value3 as $key4 => $value4) {
                            foreach ($value4 as $key5 => $value5) {
                                foreach ($value5 as $key6 => $value6) {
                                    $sum_bt = 0;
                                    $sum_Restan = 0;
                                    $tph_sample = 0;
                                    $listBlokPerAfd = array();
                                    foreach ($value6 as $key7 => $value7) {
                                        $listBlokPerAfd[] = $value7->estate . ' ' . $value7->afdeling . ' ' . $value7->blok;
                                        $sum_Restan += $value7->rst;
                                        $tph_sample = count($listBlokPerAfd);
                                        $sum_bt += $value7->bt;
                                    }
                                    $panenKey = 0;
                                    $LuasKey = 0;
                                    if (isset($ancakRegss2[$key][$key1][$key2][$key3][$key4][$key5][$key6]['status_panen'])) {
                                        $transNewdata[$key][$key1][$key2][$key3][$key4][$key5][$key6]['status_panen'] = $ancakRegss2[$key][$key1][$key2][$key3][$key4][$key5][$key6]['status_panen'];
                                        $panenKey = $ancakRegss2[$key][$key1][$key2][$key3][$key4][$key5][$key6]['status_panen'];
                                    }
                                    if (isset($ancakRegss2[$key][$key1][$key2][$key3][$key4][$key5][$key6]['luas_blok'])) {
                                        $transNewdata[$key][$key1][$key2][$key3][$key4][$key5][$key6]['luas_blok'] = $ancakRegss2[$key][$key1][$key2][$key3][$key4][$key5][$key6]['luas_blok'];
                                        $LuasKey = $ancakRegss2[$key][$key1][$key2][$key3][$key4][$key5][$key6]['luas_blok'];
                                    }

                                    if ($panenKey !== 0 && $panenKey <= 3) {
                                        if (is_array($value7) && count($value7) == 1 && $value7[0]->blok == '0') {
                                            $tph_sample = $value7[0]->tph_baris;
                                            $sum_bt = $value7[0]->bt;
                                        } else {
                                            $transNewdata[$key][$key1][$key2][$key3][$key4][$key5][$key6]['tph_sample'] = round(floatval($LuasKey) * 1.3, 3);
                                        }
                                    } else {
                                        $transNewdata[$key][$key1][$key2][$key3][$key4][$key5][$key6]['tph_sample'] = $tph_sample;
                                    }


                                    $transNewdata[$key][$key1][$key2][$key3][$key4][$key5][$key6]['afdeling'] = $value7->afdeling;
                                    $transNewdata[$key][$key1][$key2][$key3][$key4][$key5][$key6]['estate'] = $value7->estate;
                                }
                            }
                        }
                    }
                }
            }
        }


        // dd($transNewdata);
        // dd($transNewdata[2]['July']);
        foreach ($ancakRegss2 as $key => $value) {
            foreach ($value as $key1 => $value1) {
                foreach ($value1 as $key2 => $value2) {
                    foreach ($value2 as $key3 => $value3) {

                        foreach ($value3 as $key4 => $value4) {
                            $tph_tod = 0;
                            foreach ($value4 as $key5 => $value5) {
                                foreach ($value5 as $key6 => $value6) {
                                    if (!isset($transNewdata[$key][$key1][$key2][$key3][$key4][$key5][$key6])) {
                                        $transNewdata[$key][$key1][$key2][$key3][$key4][$key5][$key6] = $value6;

                                        if ($value6['status_panen'] <= 3) {
                                            $transNewdata[$key][$key1][$key2][$key3][$key4][$key5][$key6]['tph_sample'] = round(floatval($value6['luas_blok']) * 1.3, 3);
                                        } else {
                                            $transNewdata[$key][$key1][$key2][$key3][$key4][$key5][$key6]['tph_sample'] = 0;
                                        }
                                    }
                                    // If 'tph_sample' key exists, add its value to $tph_tod
                                    if (isset($value6['tph_sample'])) {
                                        $tph_tod += $value6['tph_sample'];
                                    }
                                }
                            }
                        }
                    }
                }
                // Store total_tph for each $key1 after iterating all $key2

            }
        }
        foreach ($transNewdata as $key => $value) {
            foreach ($value as $key1 => $value1) {
                foreach ($value1 as $key2 => $value2) {
                    foreach ($value2 as $key3 => $value3) {
                        foreach ($value3 as $key4 => $value4) {
                            $tph_sample_total = 0; // initialize the total
                            foreach ($value4 as $key5 => $value5) {
                                foreach ($value5 as $key6 => $value6) {
                                    if (isset($value6['tph_sample'])) {
                                        $tph_sample_total += $value6['tph_sample'];
                                    }
                                }
                            }
                            $transNewdata[$key][$key1][$key2][$key3][$key4]['total_tph'] = $tph_sample_total;
                        }
                    }
                }
            }
        }
        // dd($transNewdata[2]['January'][4]['MRE']);
        // dd($transNewdata[2]['July'][4]);

        $rekap = [];
        foreach ($defaultAncak as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $pokok_panenreg = 0;
                $jum_hareg = 0;
                $janjang_panenreg = 0;
                $p_panenreg = 0;
                $k_panenreg = 0;
                $brtgl_panenreg = 0;
                $bhts_panenreg = 0;
                $bhtm1_panenreg = 0;
                $bhtm2_panenreg = 0;
                $bhtm3_oanenreg = 0;
                $pelepah_sreg = 0;
                foreach ($value1 as $key2 => $value2) {
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
                    foreach ($value2 as $key3 => $value3) {
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
                        foreach ($value3 as $key4 => $value4) if (is_array($value4)) {
                            $akp = 0;
                            $skor_bTinggal = 0;
                            $brdPerjjg = 0;

                            $ttlSkorMA = 0;
                            $ttlSkorMAess = 0;
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
                            foreach ($value4 as $key5 => $value5) {
                                if (!in_array($value5->estate . ' ' . $value5->afdeling . ' ' . $value5->blok, $listBlokPerAfd)) {
                                    $listBlokPerAfd[] = $value5->estate . ' ' . $value5->afdeling . ' ' . $value5->blok;
                                }
                                $jum_ha = count($listBlokPerAfd);

                                $totalPokok += $value5->sample;
                                $totalPanen +=  $value5->jjg;
                                $totalP_panen += $value5->brtp;
                                $totalK_panen += $value5->brtk;
                                $totalPTgl_panen += $value5->brtgl;

                                $totalbhts_panen += $value5->bhts;
                                $totalbhtm1_panen += $value5->bhtm1;
                                $totalbhtm2_panen += $value5->bhtm2;
                                $totalbhtm3_oanen += $value5->bhtm3;

                                $totalpelepah_s += $value5->ps;
                                $check_input = $value5->jenis_input;
                                $nilai_input = $value5->skor_akhir;
                            }
                            if ($totalPokok != 0) {
                                $akp = $totalPanen / $totalPokok * 100;
                            } else {
                                $akp = 0;
                            }


                            $skor_bTinggal = $totalP_panen + $totalK_panen + $totalPTgl_panen;

                            if ($totalPanen != 0) {
                                $brdPerjjg = $skor_bTinggal / $totalPanen;
                            } else {
                                $brdPerjjg = 0;
                            }

                            $sumBH = $totalbhts_panen +  $totalbhtm1_panen +  $totalbhtm2_panen +  $totalbhtm3_oanen;
                            if ($sumBH != 0) {
                                $sumPerBH = $sumBH / ($totalPanen + $sumBH) * 100;
                            } else {
                                $sumPerBH = 0;
                            }

                            if ($totalpelepah_s != 0) {
                                $perPl = ($totalpelepah_s / $totalPokok) * 100;
                            } else {
                                $perPl = 0;
                            }
                            $nonZeroValues = array_filter([$totalP_panen, $totalK_panen, $totalPTgl_panen, $totalbhts_panen, $totalbhtm1_panen, $totalbhtm2_panen, $totalbhtm3_oanen]);

                            if (!empty($nonZeroValues)) {
                                $rekap[$key][$key1][$key2][$key3][$key4]['check_datacak'] = 'ada';
                            } else {
                                $rekap[$key][$key1][$key2][$key3][$key4]['check_datacak'] = 'kosong';
                            }

                            // $ttlSkorMA = $skor_bh + $skor_brd + $skor_ps;
                            $ttlSkorMA =  skor_buah_Ma($sumPerBH) + skor_brd_ma($brdPerjjg) + skor_palepah_ma($perPl);

                            // $namaGM = '-';
                            // foreach ($queryAsisten as $asisten) {

                            //     // dd($asisten);
                            //     if ($asisten['est'] == $key1 && $asisten['afd'] == $key2) {
                            //         $namaGM = $asisten['nama'];
                            //         break;
                            //     }
                            // }
                            $rekap[$key][$key1][$key2][$key3][$key4]['pokok_samplecak'] = $totalPokok;
                            $rekap[$key][$key1][$key2][$key3][$key4]['bulan'] = $key1;
                            $rekap[$key][$key1][$key2][$key3][$key4]['namaGM'] = '-';
                            $rekap[$key][$key1][$key2][$key3][$key4]['ha_samplecak'] = $jum_ha;
                            $rekap[$key][$key1][$key2][$key3][$key4]['jumlah_panencak'] = $totalPanen;
                            $rekap[$key][$key1][$key2][$key3][$key4]['akp_rlcak'] = $akp;
                            $rekap[$key][$key1][$key2][$key3][$key4]['pcak'] = $totalP_panen;
                            $rekap[$key][$key1][$key2][$key3][$key4]['kcak'] = $totalK_panen;
                            $rekap[$key][$key1][$key2][$key3][$key4]['tglcak'] = $totalPTgl_panen;
                            $rekap[$key][$key1][$key2][$key3][$key4]['total_brdcak'] = $skor_bTinggal;
                            $rekap[$key][$key1][$key2][$key3][$key4]['brd/jjgcak'] = $brdPerjjg;
                            // data untuk buah tinggal
                            $rekap[$key][$key1][$key2][$key3][$key4]['bhts_scak'] = $totalbhts_panen;
                            $rekap[$key][$key1][$key2][$key3][$key4]['bhtm1cak'] = $totalbhtm1_panen;
                            $rekap[$key][$key1][$key2][$key3][$key4]['bhtm2cak'] = $totalbhtm2_panen;
                            $rekap[$key][$key1][$key2][$key3][$key4]['bhtm3cak'] = $totalbhtm3_oanen;
                            $rekap[$key][$key1][$key2][$key3][$key4]['buah/jjgcak'] = $sumPerBH;
                            $rekap[$key][$key1][$key2][$key3][$key4]['total_buahcak'] = $sumBH;
                            $rekap[$key][$key1][$key2][$key3][$key4]['jjgperBuahcak'] = number_format($sumPerBH, 3);
                            // data untuk pelepah sengklek
                            $rekap[$key][$key1][$key2][$key3][$key4]['palepah_pokokcak'] = $totalpelepah_s;
                            $rekap[$key][$key1][$key2][$key3][$key4]['palepah_percak'] = $perPl;
                            $rekap[$key][$key1][$key2][$key3][$key4]['skor_bhcak'] = skor_buah_Ma($sumPerBH);
                            $rekap[$key][$key1][$key2][$key3][$key4]['skor_brdcak'] = skor_brd_ma($brdPerjjg);
                            $rekap[$key][$key1][$key2][$key3][$key4]['skor_pscak'] =  skor_palepah_ma($perPl);
                            // total skor akhir
                            $rekap[$key][$key1][$key2][$key3][$key4]['skor_akhircak'] = $ttlSkorMA;
                            $rekap[$key][$key1][$key2][$key3][$key4]['check_inputcak'] = $check_input;
                            $rekap[$key][$key1][$key2][$key3][$key4]['est'] = $key3;
                            $rekap[$key][$key1][$key2][$key3][$key4]['afd'] = $key4;
                            $rekap[$key][$key1][$key2][$key3][$key4]['mutuancak'] = '-----------------------------------';

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
                            $ttlSkorMAess =  skor_buah_Ma(0) + skor_brd_ma(0) + skor_palepah_ma(0);

                            $rekap[$key][$key1][$key2][$key3][$key4]['check_datacak'] = 'kosong';
                            $rekap[$key][$key1][$key2][$key3][$key4]['bulan'] = $key1;
                            $rekap[$key][$key1][$key2][$key3][$key4]['pokok_samplecak'] = 0;
                            $rekap[$key][$key1][$key2][$key3][$key4]['namaGM'] = '-';
                            $rekap[$key][$key1][$key2][$key3][$key4]['ha_samplecak'] = 0;
                            $rekap[$key][$key1][$key2][$key3][$key4]['jumlah_panencak'] = 0;
                            $rekap[$key][$key1][$key2][$key3][$key4]['akp_rlcak'] = 0;
                            $rekap[$key][$key1][$key2][$key3][$key4]['pcak'] = 0;
                            $rekap[$key][$key1][$key2][$key3][$key4]['kcak'] = 0;
                            $rekap[$key][$key1][$key2][$key3][$key4]['tglcak'] = 0;
                            $rekap[$key][$key1][$key2][$key3][$key4]['total_brdcak'] = 0;
                            $rekap[$key][$key1][$key2][$key3][$key4]['brd/jjgcak'] = 0;
                            // data untuk buah tinggal
                            $rekap[$key][$key1][$key2][$key3][$key4]['bhts_scak'] = 0;
                            $rekap[$key][$key1][$key2][$key3][$key4]['bhtm1cak'] = 0;
                            $rekap[$key][$key1][$key2][$key3][$key4]['bhtm2cak'] = 0;
                            $rekap[$key][$key1][$key2][$key3][$key4]['bhtm3cak'] = 0;
                            $rekap[$key][$key1][$key2][$key3][$key4]['buah/jjgcak'] = 0;
                            $rekap[$key][$key1][$key2][$key3][$key4]['total_buahcak'] = 0;
                            $rekap[$key][$key1][$key2][$key3][$key4]['jjgperBuahcak'] = 0;
                            // data untuk pelepah sengklek
                            $rekap[$key][$key1][$key2][$key3][$key4]['palepah_pokokcak'] = 0;
                            $rekap[$key][$key1][$key2][$key3][$key4]['palepah_percak'] = 0;
                            $rekap[$key][$key1][$key2][$key3][$key4]['skor_bhcak'] = 0;
                            $rekap[$key][$key1][$key2][$key3][$key4]['skor_brdcak'] = 0;
                            $rekap[$key][$key1][$key2][$key3][$key4]['skor_pscak'] = 0;
                            // total skor akhir
                            $rekap[$key][$key1][$key2][$key3][$key4]['skor_akhircak'] = $ttlSkorMAess;
                            $rekap[$key][$key1][$key2][$key3][$key4]['check_inputcak'] = 0;
                            $rekap[$key][$key1][$key2][$key3][$key4]['est'] = $key3;
                            $rekap[$key][$key1][$key2][$key3][$key4]['afd'] = $key4;
                            $rekap[$key][$key1][$key2][$key3][$key4]['mutuancak'] = '-----------------------------------';
                        }

                        $sumBHEst = $bhtsEST +  $bhtm1EST +  $bhtm2EST +  $bhtm3EST;
                        $totalPKT = $p_panenEst + $k_panenEst + $brtgl_panenEst;
                        // dd($sumBHEst);
                        if ($pokok_panenEst != 0) {
                            $akpEst = $janjang_panenEst / $pokok_panenEst * 100;
                        } else {
                            $akpEst = 0;
                        }

                        if ($janjang_panenEst != 0) {
                            $brdPerjjgEst = $totalPKT / $janjang_panenEst;
                        } else {
                            $brdPerjjgEst = 0;
                        }



                        // dd($sumBHEst);
                        if ($sumBHEst != 0) {
                            $sumPerBHEst = $sumBHEst / ($janjang_panenEst + $sumBHEst) * 100;
                        } else {
                            $sumPerBHEst = 0;
                        }

                        if ($pokok_panenEst != 0) {
                            $perPlEst = ($pelepah_sEST / $pokok_panenEst) * 100;
                        } else {
                            $perPlEst = 0;
                        }


                        $nonZeroValues = array_filter([$p_panenEst, $k_panenEst, $brtgl_panenEst, $bhtsEST, $bhtm1EST, $bhtm2EST, $bhtm3EST]);

                        if (!empty($nonZeroValues)) {
                            $check_data = 'ada';
                        } else {
                            $check_data = 'kosong';
                        }

                        // $totalSkorEst = $skor_bh + $skor_brd + $skor_ps;

                        $totalSkorEst =  skor_brd_ma($brdPerjjgEst) + skor_buah_Ma($sumPerBHEst) + skor_palepah_ma($perPlEst);
                        //PENAMPILAN UNTUK PERESTATE
                        $namaGM = '-';
                        foreach ($queryAsisten as $asisten) {

                            // dd($asisten);
                            if ($asisten['est'] == $key1 && $asisten['afd'] == 'EM') {
                                $namaGM = $asisten['nama'];
                                break;
                            }
                        }
                        // $rekap[$key][$key1][$key2]['pokok_samplecak'] = $totalPokok;
                        $rekap[$key][$key1][$key2][$key3]['estate']['ancak'] = [
                            'bulan' => $key1,
                            'pokok_samplecak' => $totalPKT,
                            'namaGM' => $namaGM,
                            'ha_samplecak' =>  $jum_haEst,
                            'jumlah_panencak' => $janjang_panenEst,
                            'akp_rlcak' =>  $akpEst,
                            'pcak' => $p_panenEst,
                            'kcak' => $k_panenEst,
                            'tglcak' => $brtgl_panenEst,
                            'total_brdcak' => $sumBHEst,
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
                            'est' => $key3,
                            'afd' => 'est',
                            'mutuancak' => '-----------------------------------'
                        ];


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

                        if ($key1 === 'LDE' || $key1 === 'SRE') {

                            $data[] = $janjang_panenEst;
                        }
                    }
                    $totalPKTwil = $p_panenWil + $k_panenWil + $brtgl_panenWil;
                    $sumBHWil = $bhts_panenWil +  $bhtm1_panenWil +  $bhtm2_panenWil +  $bhtm3_oanenWil;
                    $janjang_panenWil = $janjang_panenWilx;

                    if ($janjang_panenWil == 0 || $pokok_panenWil == 0) {
                        $akpWil = 0;
                    } else {

                        $akpWil = ($janjang_panenWil / $pokok_panenWil) * 100;
                    }

                    if ($totalPKTwil != 0) {
                        $brdPerwil = $totalPKTwil / $janjang_panenWil;
                    } else {
                        $brdPerwil = 0;
                    }

                    // dd($sumBHEst);
                    if ($sumBHWil != 0) {
                        $sumPerBHWil = $sumBHWil / ($janjang_panenWil + $sumBHWil) * 100;
                    } else {
                        $sumPerBHWil = 0;
                    }

                    if ($pokok_panenWil != 0) {
                        $perPiWil = ($pelepah_swil / $pokok_panenWil) * 100;
                    } else {
                        $perPiWil = 0;
                    }

                    $nonZeroValues = array_filter([$p_panenWil, $k_panenWil, $brtgl_panenWil, $bhts_panenWil, $bhtm1_panenWil, $bhtm2_panenWil, $bhtm3_oanenWil]);

                    if (!empty($nonZeroValues)) {
                        $check_data = 'ada';
                    } else {
                        $check_data = 'kosong';
                    }
                    $totalWil = skor_brd_ma($brdPerwil) + skor_buah_Ma($sumPerBHWil) + skor_palepah_ma($perPiWil);
                    $namaGM = '-';
                    $namewil = 'WIL-' . convertToRoman($key);
                    foreach ($queryAsisten as $asisten) {

                        // dd($asisten);
                        if ($asisten['est'] == $namewil && $asisten['afd'] == 'GM') {
                            $namaGM = $asisten['nama'];
                            break;
                        }
                    }
                    $rekap[$key][$key1][$key2]['wil']['ancak'] = [
                        'bulan' => $key1,
                        'data' =>  $data,
                        'namewil' =>  $namewil,
                        'namaGM' =>  $namaGM,
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
                        'jjgperBuahcak' =>  number_format($sumPerBHWil, 3),
                        'palepah_pokokcak' =>  $pelepah_swil,
                        'palepah_percak' =>  $perPiWil,
                        'skor_bhcak' =>  skor_buah_Ma($sumPerBHWil),
                        'skor_brdcak' =>  skor_brd_ma($brdPerwil),
                        'skor_pscak' =>  skor_palepah_ma($perPiWil),
                        'skor_akhircak' =>  $totalWil,
                        'afd' => convertToRoman($key2),
                        'est' => 'WIL',
                        'mutuancak' => '-----------------------------------'
                    ];

                    $pokok_panenreg += $pokok_panenWil;
                    $jum_hareg += $jum_haWil;
                    $janjang_panenreg += $janjang_panenWilx;
                    $p_panenreg += $p_panenWil;
                    $k_panenreg += $k_panenWil;
                    $brtgl_panenreg += $brtgl_panenWil;
                    // bagian buah tinggal
                    $bhts_panenreg += $bhts_panenWil;
                    $bhtm1_panenreg += $bhtm1_panenWil;
                    $bhtm2_panenreg += $bhtm2_panenWil;
                    $bhtm3_oanenreg += $bhtm3_oanenWil;
                    $pelepah_sreg += $pelepah_swil;
                }
                $totalPKTreg = $p_panenreg + $k_panenreg + $brtgl_panenreg;
                $sumBHreg = $bhts_panenreg +  $bhtm1_panenreg +  $bhtm2_panenreg +  $bhtm3_oanenreg;

                if ($janjang_panenreg == 0 || $pokok_panenreg == 0) {
                    $akpWil = 0;
                } else {

                    $akpWil = ($janjang_panenreg / $pokok_panenreg) * 100;
                }

                if ($totalPKTreg != 0) {
                    $brdPerwil = $totalPKTreg / $janjang_panenreg;
                } else {
                    $brdPerwil = 0;
                }

                // dd($sumBHEst);
                if ($sumBHreg != 0) {
                    $sumPerBHWil = $sumBHreg / ($janjang_panenreg + $sumBHreg) * 100;
                } else {
                    $sumPerBHWil = 0;
                }

                if ($pokok_panenreg != 0) {
                    $perPiWil = ($pelepah_sreg / $pokok_panenreg) * 100;
                } else {
                    $perPiWil = 0;
                }

                $nonZeroValues = array_filter([$p_panenreg, $k_panenreg, $brtgl_panenreg, $bhts_panenreg, $bhtm1_panenreg, $bhtm2_panenreg, $bhtm3_oanenreg]);

                if (!empty($nonZeroValues)) {
                    $check_data = 'ada';
                } else {
                    $check_data = 'kosong';
                }
                $totalWil = skor_brd_ma($brdPerwil) + skor_buah_Ma($sumPerBHWil) + skor_palepah_ma($perPiWil);
                $namaGM = '-';
                $namewil = 'WIL-' . convertToRoman($key);
                foreach ($queryAsisten as $asisten) {

                    // dd($asisten);
                    if ($asisten['est'] == $namewil && $asisten['afd'] == 'GM') {
                        $namaGM = $asisten['nama'];
                        break;
                    }
                }
                $rekap[$key][$key1]['reg']['ancak'] = [
                    'bulan' => $key1,
                    'data' =>  $data,
                    'namewil' =>  $namewil,
                    'namaGM' =>  $namaGM,
                    'pokok_samplecak' =>  $pokok_panenreg,
                    'ha_samplecak' =>   $jum_hareg,
                    'check_datacak' =>   $check_data,
                    'jumlah_panencak' =>  $janjang_panenreg,
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
                    'jjgperBuahcak' =>  number_format($sumPerBHWil, 3),
                    'palepah_pokokcak' =>  $pelepah_swil,
                    'palepah_percak' =>  $perPiWil,
                    'skor_bhcak' =>  skor_buah_Ma($sumPerBHWil),
                    'skor_brdcak' =>  skor_brd_ma($brdPerwil),
                    'skor_pscak' =>  skor_palepah_ma($perPiWil),
                    'skor_akhircak' =>  $totalWil,
                    'afd' => convertToRoman($key),
                    'est' => 'Reg',
                    'mutuancak' => '-----------------------------------'
                ];
            }
        }

        // dd($rekap);

        foreach ($defaultbuah as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $jum_haregg = 0;
                $sum_Samplejjgreg = 0;
                $sum_bmtreg = 0;
                $sum_bmkreg = 0;
                $sum_overreg = 0;
                $sum_abnorreg = 0;
                $sum_kosongjjgreg = 0;
                $sum_vcutreg = 0;
                $sum_krreg = 0;
                foreach ($value1 as $key2 => $value2) {
                    $jum_haWilbuah = 0;
                    $sum_SamplejjgWil = 0;
                    $sum_bmtWil = 0;
                    $sum_bmkWil = 0;
                    $sum_overWil = 0;
                    $sum_abnorWil = 0;
                    $sum_kosongjjgWil = 0;
                    $sum_vcutWil = 0;
                    $sum_krWil = 0;

                    $no_Vcutwil = 0;
                    foreach ($value2 as $key3 => $value3) {
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
                        foreach ($value3 as $key4 => $value4) if (is_array($value4)) {
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
                            $totalSkorses = 0;
                            $jum_ha = 0;
                            $no_Vcut = 0;
                            $jml_mth = 0;
                            $jml_mtg = 0;
                            $dataBLok = 0;
                            $listBlokPerAfd = [];
                            $dtBlok = 0;
                            foreach ($value4 as $key5 => $value5) {
                                $listBlokPerAfd[] = $value5->estate . ' ' . $value5->afdeling . ' ' . $value5->blok . ' ' . $value5->tph_baris;
                                $dtBlok = count($listBlokPerAfd);

                                // $jum_ha = count($listBlokPerAfd);
                                $sum_bmt += $value5->bmt;
                                $sum_bmk += $value5->bmk;
                                $sum_over += $value5->overripe;
                                $sum_kosongjjg += $value5->empty_bunch;
                                $sum_vcut += $value5->vcut;
                                $sum_kr += $value5->alas_br;


                                $sum_Samplejjg += $value5->jumlah_jjg;
                                $sum_abnor += $value5->abnormal;
                            }
                            $dataBLok = $dtBlok;
                            $jml_mth = ($sum_bmt + $sum_bmk);
                            $jml_mtg = $sum_Samplejjg - ($jml_mth + $sum_over + $sum_kosongjjg + $sum_abnor);

                            if ($sum_kr != 0) {
                                $total_kr = $sum_kr / $dataBLok;
                            } else {
                                $total_kr = 0;
                            }


                            $per_kr = $total_kr * 100;
                            if ($jml_mth != 0) {
                                $PerMth = ($jml_mth / ($sum_Samplejjg - $sum_abnor)) * 100;
                            } else {
                                $PerMth = 0;
                            }
                            if ($jml_mtg != 0) {
                                $PerMsk = ($jml_mtg / ($sum_Samplejjg - $sum_abnor)) * 100;
                            } else {
                                $PerMsk = 0;
                            }
                            if ($sum_over != 0) {
                                $PerOver = ($sum_over / ($sum_Samplejjg - $sum_abnor)) * 100;
                            } else {
                                $PerOver = 0;
                            }
                            if ($sum_kosongjjg != 0) {
                                $Perkosongjjg = ($sum_kosongjjg / ($sum_Samplejjg - $sum_abnor)) * 100;
                            } else {
                                $Perkosongjjg = 0;
                            }
                            if ($sum_vcut != 0) {
                                $PerVcut = ($sum_vcut / $sum_Samplejjg) * 100;
                            } else {
                                $PerVcut = 0;
                            }

                            if ($sum_abnor != 0) {
                                $PerAbr = ($sum_abnor / $sum_Samplejjg) * 100;
                            } else {
                                $PerAbr = 0;
                            }

                            $nonZeroValues = array_filter([$sum_Samplejjg, $jml_mth, $jml_mtg, $sum_over, $sum_abnor, $sum_kosongjjg, $sum_vcut, $dataBLok]);

                            if (!empty($nonZeroValues)) {
                                $rekap[$key][$key1][$key2][$key3][$key4]['check_databh'] = 'ada';
                            } else {
                                $rekap[$key][$key1][$key2][$key3][$key4]['check_databh'] = 'kosong';
                            }
                            $totalSkor =  skor_buah_mentah_mb($PerMth) + skor_buah_masak_mb($PerMsk) + skor_buah_over_mb($PerOver) + skor_vcut_mb($PerVcut) + skor_jangkos_mb($Perkosongjjg) + skor_abr_mb($per_kr);
                            $rekap[$key][$key1][$key2][$key3][$key4]['tph_baris_bloksbh'] = $dataBLok;
                            $rekap[$key][$key1][$key2][$key3][$key4]['sampleJJG_totalbh'] = $sum_Samplejjg;
                            $rekap[$key][$key1][$key2][$key3][$key4]['total_mentahbh'] = $jml_mth;
                            $rekap[$key][$key1][$key2][$key3][$key4]['total_perMentahbh'] = $PerMth;
                            $rekap[$key][$key1][$key2][$key3][$key4]['total_masakbh'] = $jml_mtg;
                            $rekap[$key][$key1][$key2][$key3][$key4]['total_perMasakbh'] = $PerMsk;
                            $rekap[$key][$key1][$key2][$key3][$key4]['total_overbh'] = $sum_over;
                            $rekap[$key][$key1][$key2][$key3][$key4]['total_perOverbh'] = $PerOver;
                            $rekap[$key][$key1][$key2][$key3][$key4]['total_abnormalbh'] = $sum_abnor;
                            $rekap[$key][$key1][$key2][$key3][$key4]['perAbnormalbh'] = $PerAbr;
                            $rekap[$key][$key1][$key2][$key3][$key4]['total_jjgKosongbh'] = $sum_kosongjjg;
                            $rekap[$key][$key1][$key2][$key3][$key4]['total_perKosongjjgbh'] = $Perkosongjjg;
                            $rekap[$key][$key1][$key2][$key3][$key4]['total_vcutbh'] = $sum_vcut;
                            $rekap[$key][$key1][$key2][$key3][$key4]['perVcutbh'] = $PerVcut;

                            $rekap[$key][$key1][$key2][$key3][$key4]['jum_krbh'] = $sum_kr;
                            $rekap[$key][$key1][$key2][$key3][$key4]['total_krbh'] = $total_kr;
                            $rekap[$key][$key1][$key2][$key3][$key4]['persen_krbh'] = $per_kr;

                            // skoring
                            $rekap[$key][$key1][$key2][$key3][$key4]['skor_mentahbh'] = skor_buah_mentah_mb($PerMth);
                            $rekap[$key][$key1][$key2][$key3][$key4]['skor_masakbh'] = skor_buah_masak_mb($PerMsk);
                            $rekap[$key][$key1][$key2][$key3][$key4]['skor_overbh'] = skor_buah_over_mb($PerOver);
                            $rekap[$key][$key1][$key2][$key3][$key4]['skor_jjgKosongbh'] = skor_jangkos_mb($Perkosongjjg);
                            $rekap[$key][$key1][$key2][$key3][$key4]['skor_vcutbh'] = skor_vcut_mb($PerVcut);
                            $rekap[$key][$key1][$key2][$key3][$key4]['skor_krbh'] = skor_abr_mb($per_kr);
                            $rekap[$key][$key1][$key2][$key3][$key4]['TOTAL_SKORbh'] = $totalSkor;
                            $rekap[$key][$key1][$key2][$key3][$key4]['mutubuah'] = '-----------------------------------------';

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
                            $totalSkorses =  skor_buah_mentah_mb(0) + skor_buah_masak_mb(0) + skor_buah_over_mb(0) + skor_vcut_mb(0) + skor_jangkos_mb(0) + skor_abr_mb(0);
                            $rekap[$key][$key1][$key2][$key3][$key4]['tph_baris_bloksbh'] = 0;
                            $rekap[$key][$key1][$key2][$key3][$key4]['check_databh'] = 'kosong';
                            $rekap[$key][$key1][$key2][$key3][$key4]['sampleJJG_totalbh'] = 0;
                            $rekap[$key][$key1][$key2][$key3][$key4]['total_mentahbh'] = 0;
                            $rekap[$key][$key1][$key2][$key3][$key4]['total_perMentahbh'] = 0;
                            $rekap[$key][$key1][$key2][$key3][$key4]['total_masakbh'] = 0;
                            $rekap[$key][$key1][$key2][$key3][$key4]['total_perMasakbh'] = 0;
                            $rekap[$key][$key1][$key2][$key3][$key4]['total_overbh'] = 0;
                            $rekap[$key][$key1][$key2][$key3][$key4]['total_perOverbh'] = 0;
                            $rekap[$key][$key1][$key2][$key3][$key4]['total_abnormalbh'] = 0;
                            $rekap[$key][$key1][$key2][$key3][$key4]['perAbnormalbh'] = 0;
                            $rekap[$key][$key1][$key2][$key3][$key4]['total_jjgKosongbh'] = 0;
                            $rekap[$key][$key1][$key2][$key3][$key4]['total_perKosongjjgbh'] = 0;
                            $rekap[$key][$key1][$key2][$key3][$key4]['total_vcutbh'] = 0;
                            $rekap[$key][$key1][$key2][$key3][$key4]['perVcutbh'] = 0;

                            $rekap[$key][$key1][$key2][$key3][$key4]['jum_krbh'] = 0;
                            $rekap[$key][$key1][$key2][$key3][$key4]['total_krbh'] = 0;
                            $rekap[$key][$key1][$key2][$key3][$key4]['persen_krbh'] = 0;

                            // skoring
                            $rekap[$key][$key1][$key2][$key3][$key4]['skor_mentahbh'] = 0;
                            $rekap[$key][$key1][$key2][$key3][$key4]['skor_masakbh'] = 0;
                            $rekap[$key][$key1][$key2][$key3][$key4]['skor_overbh'] = 0;
                            $rekap[$key][$key1][$key2][$key3][$key4]['skor_jjgKosongbh'] = 0;
                            $rekap[$key][$key1][$key2][$key3][$key4]['skor_vcutbh'] = 0;
                            $rekap[$key][$key1][$key2][$key3][$key4]['skor_abnormalbh'] = 0;;
                            $rekap[$key][$key1][$key2][$key3][$key4]['skor_krbh'] = 0;
                            $rekap[$key][$key1][$key2][$key3][$key4]['TOTAL_SKORbh'] = $totalSkorses;
                            $rekap[$key][$key1][$key2][$key3][$key4]['mutubuah'] = '-----------------------------------------';
                        }

                        $no_VcutEst = $sum_SamplejjgEst - $sum_vcutEst;

                        if ($sum_krEst != 0) {
                            $total_krEst = $sum_krEst / $jum_haEst;
                        } else {
                            $total_krEst = 0;
                        }

                        if ($sum_bmtEst != 0) {
                            $PerMthEst = ($sum_bmtEst / ($sum_SamplejjgEst - $sum_abnorEst)) * 100;
                        } else {
                            $PerMthEst = 0;
                        }

                        if ($sum_bmkEst != 0) {
                            $PerMskEst = ($sum_bmkEst / ($sum_SamplejjgEst - $sum_abnorEst)) * 100;
                        } else {
                            $PerMskEst = 0;
                        }

                        if ($sum_overEst != 0) {
                            $PerOverEst = ($sum_overEst / ($sum_SamplejjgEst - $sum_abnorEst)) * 100;
                        } else {
                            $PerOverEst = 0;
                        }
                        if ($sum_kosongjjgEst != 0) {
                            $PerkosongjjgEst = ($sum_kosongjjgEst / ($sum_SamplejjgEst - $sum_abnorEst)) * 100;
                        } else {
                            $PerkosongjjgEst = 0;
                        }
                        if ($sum_vcutEst != 0) {
                            $PerVcutest = ($sum_vcutEst / $sum_SamplejjgEst) * 100;
                        } else {
                            $PerVcutest = 0;
                        }
                        if ($sum_abnorEst != 0) {
                            $PerAbrest = ($sum_abnorEst / $sum_SamplejjgEst) * 100;
                        } else {
                            $PerAbrest = 0;
                        }
                        // $per_kr = round($sum_kr * 100);
                        $per_krEst = $total_krEst * 100;


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

                        $rekap[$key][$key1][$key2][$key3]['estate']['buah'] = [
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

                        $jum_haWilbuah += $jum_haEst;
                        $sum_SamplejjgWil += $sum_SamplejjgEst;
                        $sum_bmtWil += $sum_bmtEst;
                        $sum_bmkWil += $sum_bmkEst;
                        $sum_overWil += $sum_overEst;
                        $sum_abnorWil += $sum_abnorEst;
                        $sum_kosongjjgWil += $sum_kosongjjgEst;
                        $sum_vcutWil += $sum_vcutEst;
                        $sum_krWil += $sum_krEst;
                    }
                    if ($sum_krWil != 0) {
                        $total_krWil = $sum_krWil / $jum_haWilbuah;
                    } else {
                        $total_krWil = 0;
                    }

                    if ($sum_bmtWil != 0) {
                        $PerMthWil = ($sum_bmtWil / ($sum_SamplejjgWil - $sum_abnorWil)) * 100;
                    } else {
                        $PerMthWil = 0;
                    }


                    if ($sum_bmkWil != 0) {
                        $PerMskWil = $sum_bmkWil / ($sum_SamplejjgWil - $sum_abnorWil) * 100;
                    } else {
                        $PerMskWil = 0;
                    }
                    if ($sum_overWil != 0) {
                        $PerOverWil = ($sum_overWil / ($sum_SamplejjgWil - $sum_abnorWil)) * 100;
                    } else {
                        $PerOverWil = 0;
                    }
                    if ($sum_kosongjjgWil != 0) {
                        $PerkosongjjgWil = ($sum_kosongjjgWil / ($sum_SamplejjgWil - $sum_abnorWil)) * 100;
                    } else {
                        $PerkosongjjgWil = 0;
                    }
                    if ($sum_vcutWil != 0) {
                        $PerVcutWil = ($sum_vcutWil / $sum_SamplejjgWil) * 100;
                    } else {
                        $PerVcutWil = 0;
                    }
                    if ($sum_abnorWil != 0) {
                        $PerAbrWil = ($sum_abnorWil / $sum_SamplejjgWil) * 100;
                    } else {
                        $PerAbrWil = 0;
                    }
                    $per_krWil = $total_krWil * 100;

                    $nonZeroValues = array_filter([$sum_SamplejjgWil, $sum_bmtWil, $sum_bmkWil, $sum_overWil, $sum_abnorWil, $sum_kosongjjgWil, $sum_vcutWil]);

                    if (!empty($nonZeroValues)) {
                        // $rekap[$key]['check_data'] = 'ada';
                        $check_data = 'ada';
                    } else {
                        // $rekap[$key]['check_data'] = 'kosong';
                        $check_data = 'kosong';
                    }

                    $totalSkorWil =  skor_buah_mentah_mb($PerMthWil) + skor_buah_masak_mb($PerMskWil) + skor_buah_over_mb($PerOverWil) + skor_jangkos_mb($PerkosongjjgWil) + skor_vcut_mb($PerVcutWil) + skor_abr_mb($per_krWil);

                    $rekap[$key][$key1][$key2]['wil']['buah']  = [
                        'check_databh' => $check_data,
                        'tph_baris_bloksbh' => $jum_haWilbuah,
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

                    $jum_haregg += $jum_haWilbuah;
                    $sum_Samplejjgreg += $sum_SamplejjgWil;
                    $sum_bmtreg += $sum_bmtWil;
                    $sum_bmkreg += $sum_bmkWil;
                    $sum_overreg += $sum_overWil;
                    $sum_abnorreg += $sum_abnorWil;
                    $sum_kosongjjgreg += $sum_kosongjjgWil;
                    $sum_vcutreg += $sum_vcutWil;
                    $sum_krreg += $sum_krWil;
                }

                if ($sum_krreg != 0) {
                    $total_krWil = $sum_krreg / $jum_haregg;
                } else {
                    $total_krWil = 0;
                }

                if ($sum_bmtreg != 0) {
                    $PerMthWil = ($sum_bmtreg / ($sum_Samplejjgreg - $sum_abnorreg)) * 100;
                } else {
                    $PerMthWil = 0;
                }


                if ($sum_bmkreg != 0) {
                    $PerMskWil = ($sum_bmkreg / ($sum_Samplejjgreg - $sum_abnorreg)) * 100;
                } else {
                    $PerMskWil = 0;
                }
                if ($sum_overreg != 0) {
                    $PerOverWil = ($sum_overreg / ($sum_Samplejjgreg - $sum_abnorreg)) * 100;
                } else {
                    $PerOverWil = 0;
                }
                if ($sum_kosongjjgreg != 0) {
                    $PerkosongjjgWil = ($sum_kosongjjgreg / ($sum_Samplejjgreg - $sum_abnorreg)) * 100;
                } else {
                    $PerkosongjjgWil = 0;
                }
                if ($sum_vcutreg != 0) {
                    $PerVcutWil = ($sum_vcutreg / $sum_Samplejjgreg) * 100;
                } else {
                    $PerVcutWil = 0;
                }
                if ($sum_abnorreg != 0) {
                    $PerAbrWil = ($sum_abnorreg / $sum_Samplejjgreg) * 100;
                } else {
                    $PerAbrWil = 0;
                }
                $per_krWil = $total_krWil * 100;

                $nonZeroValues = array_filter([$sum_Samplejjgreg, $sum_bmtreg, $sum_bmkreg, $sum_overWil, $sum_abnorreg, $sum_kosongjjgreg, $sum_vcutreg]);

                if (!empty($nonZeroValues)) {
                    // $rekap[$key]['check_data'] = 'ada';
                    $check_data = 'ada';
                } else {
                    // $rekap[$key]['check_data'] = 'kosong';
                    $check_data = 'kosong';
                }

                $totalSkorWil =  skor_buah_mentah_mb($PerMthWil) + skor_buah_masak_mb($PerMskWil) + skor_buah_over_mb($PerOverWil) + skor_jangkos_mb($PerkosongjjgWil) + skor_vcut_mb($PerVcutWil) + skor_abr_mb($per_krWil);

                $rekap[$key][$key1]['reg']['buah']  = [
                    'check_databh' => $check_data,
                    'tph_baris_bloksbh' => $jum_haWilbuah,
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
                    'skor_mentahbh' => skor_buah_mentah_mb($PerMthWil),
                    'skor_masakbh' => skor_buah_masak_mb($PerMskWil),
                    'skor_overbh' => skor_buah_over_mb($PerOverWil),
                    'skor_jjgKosongbh' => skor_jangkos_mb($PerkosongjjgWil),
                    'skor_vcutbh' => skor_vcut_mb($PerVcutWil),
                    'skor_krbh' => skor_abr_mb($per_krWil),
                    'TOTAL_SKORbh' => $totalSkorWil,
                    'mutubuah' => '------------------------------------------',
                ];
            }
        }

        // dd($defaulttrans);
        foreach ($defaulttrans as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $dataBLokreg = 0;
                $sum_btreg = 0;
                $sum_rstreg = 0;
                foreach ($value1 as $key2 => $value2) {
                    $dataBLokWil = 0;
                    $sum_btWil = 0;
                    $sum_rstWil = 0;
                    // Initialize variables outside the loop to accumulate values across iterations
                    $testsampel = [];
                    // $sampelblok = [];

                    foreach ($value2 as $key3 => $value3) {
                        $dataBLokEst = 0;
                        $sum_btEst = 0;
                        $sum_rstEst = 0;

                        foreach ($value3 as $key4 => $value4) {
                            $sum_bt = 0;
                            $sum_rst = 0;
                            $brdPertph = 0;
                            $buahPerTPH = 0;
                            $totalSkor = 0;
                            $dataBLok = 0;
                            $totalSkores = 0;

                            $tot_sample = 0; // Initialize outside the inner loops
                            $listBlokPerAfd = [];
                            if (is_array($value4)) {
                                foreach ($value4 as $key5 => $value5) {
                                    $listBlokPerAfd[] = $value5->estate . ' ' . $value5->afdeling . ' ' . $value5->blok;
                                    $dataBLok = count($listBlokPerAfd);
                                    $sum_bt += $value5->bt;
                                    $sum_rst += $value5->rst;
                                }

                                foreach ($transNewdata as $keys => $trans) {
                                    if ($keys == $key) {
                                        foreach ($trans as $keys1 => $trans1) {
                                            if ($keys1 == $key1) {
                                                foreach ($trans1 as $keys2 => $trans2) {
                                                    if ($keys2 == $key2) {
                                                        foreach ($trans2 as $keys3 => $trans3) {
                                                            if ($keys3 == $key3) {
                                                                foreach ($trans3 as $keys4 => $trans4) {
                                                                    if ($keys4 == $key4) {
                                                                        $tot_sample = $trans4['total_tph'];
                                                                        $testsampel[] = $trans4['total_tph'];
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }

                                if ($RegData == '2' || $RegData == 2) {
                                    $brdPertph = ($dataBLok != 0) ? $sum_bt / $tot_sample : 0;
                                    $buahPerTPH = ($dataBLok != 0) ? $sum_rst / $tot_sample : 0;
                                } else {
                                    $brdPertph = ($dataBLok != 0) ? $sum_bt / $dataBLok : 0;
                                    $buahPerTPH = ($dataBLok != 0) ? $sum_rst / $dataBLok : 0;
                                }

                                $nonZeroValues = array_filter([$sum_bt, $sum_rst]);
                                $rekap[$key][$key1][$key2][$key3][$key4]['check_datatrans'] = !empty($nonZeroValues) ? 'ada' : 'kosong';
                                $totalSkor = skor_brd_tinggal($brdPertph) + skor_buah_tinggal($buahPerTPH);
                                $rekap[$key][$key1][$key2][$key3][$key4]['tph_sampleNew'] = ($RegData == '2' || $RegData == 2) ? $tot_sample : $dataBLok;
                                $rekap[$key][$key1][$key2][$key3][$key4]['total_brdtrans'] = $sum_bt;
                                $rekap[$key][$key1][$key2][$key3][$key4]['dataBLok'] = $dataBLok;
                                $rekap[$key][$key1][$key2][$key3][$key4]['total_brdperTPHtrans'] = $brdPertph;
                                $rekap[$key][$key1][$key2][$key3][$key4]['total_buahtrans'] = $sum_rst;
                                $rekap[$key][$key1][$key2][$key3][$key4]['total_buahPerTPHtrans'] = $buahPerTPH;
                                $rekap[$key][$key1][$key2][$key3][$key4]['skor_brdPertphtrans'] = skor_brd_tinggal($brdPertph);
                                $rekap[$key][$key1][$key2][$key3][$key4]['skor_buahPerTPHtrans'] = skor_buah_tinggal($buahPerTPH);
                                $rekap[$key][$key1][$key2][$key3][$key4]['totalSkortrans'] = $totalSkor;
                                $rekap[$key][$key1][$key2][$key3][$key4]['mututrans'] = '-----------------------------------';

                                $sum_btEst += $sum_bt;
                                $sum_rstEst += $sum_rst;
                                $dataBLokEst += $dataBLok;
                            } else {
                                $totalSkores = skor_brd_tinggal(0) + skor_buah_tinggal(0);

                                foreach ($transNewdata as $keys => $trans) {
                                    if ($keys == $key) {
                                        foreach ($trans as $keys1 => $trans1) {
                                            if ($keys1 == $key1) {
                                                foreach ($trans1 as $keys2 => $trans2) {
                                                    if ($keys2 == $key2) {
                                                        foreach ($trans2 as $keys3 => $trans3) {
                                                            if ($keys3 == $key3) {
                                                                foreach ($trans3 as $keys4 => $trans4) {
                                                                    if ($keys4 == $key4) {
                                                                        $tot_sample = $trans4['total_tph'];
                                                                        $testsampel[] = $trans4['total_tph'];
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }

                                $rekap[$key][$key1][$key2][$key3][$key4]['check_datatrans'] = 'kosong';
                                $rekap[$key][$key1][$key2][$key3][$key4]['tph_sampleNew'] = ($RegData == '2' || $RegData == 2) ? $tot_sample : $dataBLok;
                                $rekap[$key][$key1][$key2][$key3][$key4]['tph_sampletrans'] = 0;
                                $rekap[$key][$key1][$key2][$key3][$key4]['total_brdtrans'] = 0;
                                $rekap[$key][$key1][$key2][$key3][$key4]['total_brdperTPHtrans'] = 0;
                                $rekap[$key][$key1][$key2][$key3][$key4]['total_buahtrans'] = 0;
                                $rekap[$key][$key1][$key2][$key3][$key4]['total_buahPerTPHtrans'] = 0;
                                $rekap[$key][$key1][$key2][$key3][$key4]['skor_brdPertphtrans'] = 0;
                                $rekap[$key][$key1][$key2][$key3][$key4]['skor_buahPerTPHtrans'] = 0;
                                $rekap[$key][$key1][$key2][$key3][$key4]['totalSkortrans'] = $totalSkores;
                                $rekap[$key][$key1][$key2][$key3][$key4]['mututrans'] = '-----------------------------------';
                            }
                        }

                        if ($RegData == '2' || $RegData == 2) {
                            $dataBLokEst = array_sum($testsampel);
                        }

                        $brdPertphEst = ($dataBLokEst != 0) ? round($sum_btEst / $dataBLokEst, 3) : 0;
                        $buahPerTPHEst = ($dataBLokEst != 0) ? round($sum_rstEst / $dataBLokEst, 3) : 0;
                        $totalSkorEst = skor_brd_tinggal($brdPertphEst) + skor_buah_tinggal($buahPerTPHEst);
                        $nonZeroValues = array_filter([$sum_btEst, $sum_rstEst]);

                        if (!empty($nonZeroValues)) {
                            $check_data = 'ada';
                        } else {
                            $check_data = 'kosong';
                        }

                        $rekap[$key][$key1][$key2][$key3]['estate']['trans'] = [
                            // 'tph_sampleNew' => $dataBLokEst,
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

                        $dataBLokWil += $dataBLokEst;
                        $sum_btWil += $sum_btEst;
                        $sum_rstWil += $sum_rstEst;
                    }

                    // dd($testsampel);

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
                    $totalSkorWil =   skor_brd_tinggal($brdPertphWil) + skor_buah_tinggal($buahPerTPHWil);

                    $rekap[$key][$key1][$key2]['wil']['trans'] = [
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


                    $dataBLokreg += $dataBLokWil;
                    $sum_btreg += $sum_btWil;
                    $sum_rstreg += $sum_rstWil;
                }
                if ($dataBLokreg != 0) {
                    $brdPertphWil = round($sum_btreg / $dataBLokreg, 3);
                } else {
                    $brdPertphWil = 0;
                }
                if ($dataBLokreg != 0) {
                    $buahPerTPHWil = round($sum_rstreg / $dataBLokreg, 3);
                } else {
                    $buahPerTPHWil = 0;
                }
                $nonZeroValues = array_filter([$sum_btreg, $sum_rstreg]);


                if (!empty($nonZeroValues)) {
                    $check_data = 'ada';
                    // $rekap[$key]['skor_brd'] = $skor_brd = skor_brd_ma($brdPerwil);
                    // $rekap[$key]['skor_ps'] = $skor_ps = skor_palepah_ma($perPiWil);
                } else {
                    $check_data = 'kosong';
                    // $rekap[$key]['skor_brd'] = $skor_brd = 0;
                    // $rekap[$key]['skor_ps'] = $skor_ps = 0;
                }
                $totalSkorWil =   skor_brd_tinggal($brdPertphWil) + skor_buah_tinggal($buahPerTPHWil);

                $rekap[$key][$key1]['reg']['trans'] = [
                    'check_datatrans' => $check_data,
                    'tph_sampleNew' => $dataBLokreg,
                    'total_brdtrans' => $sum_btreg,
                    'total_brdperTPHtrans' => $brdPertphWil,
                    'total_buahtrans' => $sum_rstreg,
                    'total_buahPerTPHtrans' => $buahPerTPHWil,
                    'skor_brdPertphtrans' =>   skor_brd_tinggal($brdPertphWil),
                    'skor_buahPerTPHtrans' => skor_buah_tinggal($buahPerTPHWil),
                    'totalSkortrans' => $totalSkorWil,
                    'mututrans' => '-----------------------------------'
                ];
            }
        }
        // dd($rekap[2]['July'][6]);

        $resultafdeling = [];

        foreach ($rekap as $region => $months) {
            foreach ($months as $month => $wilayahs) {
                foreach ($wilayahs as $wilayah => $estates) {
                    if ($wilayah !== "wil") {
                        foreach ($estates as $estate => $afdelings) {
                            if ($estate !== "estate" && is_array($afdelings)) {
                                foreach ($afdelings as $afdeling => $afdelingDetails) {
                                    if ($afdeling !== "estate") {
                                        $resultafdeling[$estate][$afdeling][$month] = $afdelingDetails;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }


        $resultestate = [];

        foreach ($rekap as $region => $months) {
            foreach ($months as $month => $wilayahs) {
                foreach ($wilayahs as $wilayah => $estates) {
                    foreach ($estates as $estate => $afdelings) {
                        // dd($estate);

                        foreach ($afdelings as $afdeling => $afdelingDetails) {
                            if ($afdeling === "estate" && is_array($afdelingDetails)) {
                                if ($afdeling === "estate") {
                                    $resultestate[$estate][$month][$afdeling] = $afdelingDetails;
                                }
                            }
                        }
                    }
                }
            }
        }


        $resultwil = [];

        foreach ($rekap as $region => $months) {
            foreach ($months as $month => $wilayahs) {
                foreach ($wilayahs as $wilayah => $estates) {
                    foreach ($estates as $estate => $afdelings) {
                        if ($estate === "wil" && is_array($afdelings)) {
                            if ($estate === "wil") {
                                $resultwil[$wilayah][$month][$estate] = $afdelings;
                            }
                        }
                    }
                }
            }
        }
        $resultreg = [];

        foreach ($rekap as $region => $months) {
            foreach ($months as $month => $wilayahs) {
                if (isset($wilayahs['reg']) && is_array($wilayahs['reg'])) {
                    $resultreg[$month] = $wilayahs['reg'];
                }
            }
        }


        // dd($rekap);

        // dd($resultestate);
        // Unset the unwanted keys
        unset($resultafdeling['ancak']);
        unset($resultafdeling['buah']);
        unset($resultafdeling['trans']);
        unset($resultafdeling['wil']);

        $rekaptahunan_afdeling = [];
        foreach ($resultafdeling as $key => $value) {
            foreach ($value as $key1 => $value1) {
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
                $jum_haEstcak = 0;
                $dataBLokEst = 0;
                $sum_btEst = 0;
                $sum_rstEst = 0;
                foreach ($value1 as $key2 => $value2) {
                    // mutu ancak 
                    $pokok_panenEst += $value2['pokok_samplecak'];
                    $jum_haEstcak += $value2['ha_samplecak'];
                    $janjang_panenEst += $value2['jumlah_panencak'];
                    $p_panenEst += $value2['pcak'];
                    $k_panenEst += $value2['kcak'];
                    $brtgl_panenEst += $value2['tglcak'];
                    $bhtsEST   += $value2['bhts_scak'];
                    $bhtm1EST += $value2['bhtm1cak'];
                    $bhtm2EST   += $value2['bhtm2cak'];
                    $bhtm3EST   += $value2['bhtm3cak'];
                    $pelepah_sEST += $value2['palepah_pokokcak'];
                    // mutubuah 
                    $jum_haEst += $value2['tph_baris_bloksbh'];
                    $sum_SamplejjgEst += $value2['sampleJJG_totalbh'];
                    $sum_bmtEst += $value2['total_mentahbh'];
                    $sum_bmkEst += $value2['total_masakbh'];
                    $sum_overEst += $value2['total_overbh'];
                    $sum_abnorEst += $value2['total_abnormalbh'];
                    $sum_kosongjjgEst += $value2['total_jjgKosongbh'];
                    $sum_vcutEst += $value2['total_vcutbh'];
                    $sum_krEst += $value2['jum_krbh'];
                    // mututransport 
                    $sum_btEst += $value2['total_brdtrans'];
                    $sum_rstEst += $value2['total_buahtrans'];
                    $dataBLokEst += $value2['tph_sampleNew'];
                }

                $sumBHEst = $bhtsEST +  $bhtm1EST +  $bhtm2EST +  $bhtm3EST;
                $totalPKT = $p_panenEst + $k_panenEst + $brtgl_panenEst;
                // dd($sumBHEst);
                if ($pokok_panenEst != 0) {
                    $akpEst = $janjang_panenEst / $pokok_panenEst * 100;
                } else {
                    $akpEst = 0;
                }

                if ($janjang_panenEst != 0) {
                    $brdPerjjgEst = $totalPKT / $janjang_panenEst;
                } else {
                    $brdPerjjgEst = 0;
                }



                // dd($sumBHEst);
                if ($sumBHEst != 0) {
                    $sumPerBHEst = $sumBHEst / ($janjang_panenEst + $sumBHEst) * 100;
                } else {
                    $sumPerBHEst = 0;
                }

                if ($pokok_panenEst != 0) {
                    $perPlEst = ($pelepah_sEST / $pokok_panenEst) * 100;
                } else {
                    $perPlEst = 0;
                }


                $nonZeroValues = array_filter([$p_panenEst, $k_panenEst, $brtgl_panenEst, $bhtsEST, $bhtm1EST, $bhtm2EST, $bhtm3EST]);

                if (!empty($nonZeroValues)) {
                    $check_datacak = 'ada';
                } else {
                    $check_datacak = 'kosong';
                }

                // $totalSkorEst = $skor_bh + $skor_brd + $skor_ps;

                $totalSkorEst =  skor_brd_ma($brdPerjjgEst) + skor_buah_Ma($sumPerBHEst) + skor_palepah_ma($perPlEst);
                //PENAMPILAN UNTUK PERESTATE
                $namaGM = '-';
                foreach ($queryAsisten as $asisten) {

                    // dd($asisten);
                    if ($asisten['est'] == $key1 && $asisten['afd'] == 'EM') {
                        $namaGM = $asisten['nama'];
                        break;
                    }
                }

                // sidak mutu buah 
                if ($sum_krEst != 0) {
                    $total_krEst = $sum_krEst / $jum_haEst;
                } else {
                    $total_krEst = 0;
                }

                if ($sum_bmtEst != 0) {
                    $PerMthEst = ($sum_bmtEst / ($sum_SamplejjgEst - $sum_abnorEst)) * 100;
                } else {
                    $PerMthEst = 0;
                }

                if ($sum_bmkEst != 0) {
                    $PerMskEst = ($sum_bmkEst / ($sum_SamplejjgEst - $sum_abnorEst)) * 100;
                } else {
                    $PerMskEst = 0;
                }

                if ($sum_overEst != 0) {
                    $PerOverEst = ($sum_overEst / ($sum_SamplejjgEst - $sum_abnorEst)) * 100;
                } else {
                    $PerOverEst = 0;
                }
                if ($sum_kosongjjgEst != 0) {
                    $PerkosongjjgEst = ($sum_kosongjjgEst / ($sum_SamplejjgEst - $sum_abnorEst)) * 100;
                } else {
                    $PerkosongjjgEst = 0;
                }
                if ($sum_vcutEst != 0) {
                    $PerVcutest = ($sum_vcutEst / $sum_SamplejjgEst) * 100;
                } else {
                    $PerVcutest = 0;
                }
                if ($sum_abnorEst != 0) {
                    $PerAbrest = ($sum_abnorEst / $sum_SamplejjgEst) * 100;
                } else {
                    $PerAbrest = 0;
                }
                // $per_kr = round($sum_kr * 100);
                $per_krEst = $total_krEst * 100;


                $nonZeroValues = array_filter([$sum_SamplejjgEst, $sum_bmtEst, $sum_bmkEst, $sum_overEst, $sum_abnorEst, $sum_kosongjjgEst, $sum_vcutEst]);

                if (!empty($nonZeroValues)) {
                    $check_databuah = 'ada';
                } else {
                    $check_databuah = 'kosong';
                }

                // $totalSkorEst = $skor_mentah + $skor_masak + $skor_over + $skor_jjgKosong + $skor_vcut + $skor_kr;

                $totalSkorEstbuah =   skor_buah_mentah_mb($PerMthEst) + skor_buah_masak_mb($PerMskEst) + skor_buah_over_mb($PerOverEst) + skor_jangkos_mb($PerkosongjjgEst) + skor_vcut_mb($PerVcutest) + skor_abr_mb($per_krEst);

                // mutu transport 
                $brdPertphEst = ($dataBLokEst != 0) ? round($sum_btEst / $dataBLokEst, 3) : 0;
                $buahPerTPHEst = ($dataBLokEst != 0) ? round($sum_rstEst / $dataBLokEst, 3) : 0;
                $totalSkorEsttrans = skor_brd_tinggal($brdPertphEst) + skor_buah_tinggal($buahPerTPHEst);
                $nonZeroValuesss = array_filter([$sum_btEst, $sum_rstEst]);

                if (!empty($nonZeroValuesss)) {
                    $check_datatrams = 'ada';
                } else {
                    $check_datatrams = 'kosong';
                }



                // $rekap[$key][$key1][$key2]['pokok_samplecak'] = $totalPokok;
                $rekaptahunan_afdeling[$key][$key1]['Tahun'] = [
                    'bulan' => $key1,
                    'pokok_samplecak' => $totalPKT,
                    'namaGM' => $namaGM,
                    'ha_samplecak' =>  $jum_haEstcak,
                    'jumlah_panencak' => $janjang_panenEst,
                    'akp_rlcak' =>  $akpEst,
                    'pcak' => $p_panenEst,
                    'kcak' => $k_panenEst,
                    'tglcak' => $brtgl_panenEst,
                    'total_brdcak' => $sumBHEst,
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
                    'check_datacak' => $check_datacak,
                    'est' => $key,
                    'afd' => $key1,
                    'mutuancak' => '-----------------------------------',
                    'check_databh' => $check_databuah,
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
                    'TOTAL_SKORbh' => $totalSkorEstbuah,
                    'mutubuah' => '------------------------------------------',
                    'tph_sampleNew' => $dataBLokEst,
                    'total_brdtrans' => $sum_btEst,
                    'check_datatrans' => $check_datatrams,
                    'total_brdperTPHtrans' => $brdPertphEst,
                    'total_buahtrans' => $sum_rstEst,
                    'total_buahPerTPHtrans' => $buahPerTPHEst,
                    'skor_brdPertphtrans' => skor_brd_tinggal($brdPertphEst),
                    'skor_buahPerTPHtrans' => skor_buah_tinggal($buahPerTPHEst),
                    'totalSkortrans' => $totalSkorEsttrans,
                    'mututrans' => '-----------------------------------',
                    'skor_akhir' => $totalSkorEstbuah + $totalSkorEsttrans + $totalSkorEst
                ];
            }
        }
        // dd($rekaptahunan_afdeling);
        return [
            'resultreg' => $resultreg,
            'resultwil' => $resultwil,
            'resultestate' => $resultestate,
            'resultafdeling' => $resultafdeling,
            'rekaptahunan_afdeling' => $rekaptahunan_afdeling,
        ];
    }
}

//helper halaman sidak tph pertahun
if (!function_exists('rekap_pertahun_sidaktph')) {
    function rekap_pertahun_sidaktph($regional, $tahun, $start_date, $end_date)
    {


        $queryAsisten = DB::connection('mysql2')->table('asisten_qc')
            ->select('asisten_qc.*')
            ->get();
        $queryAsisten = json_decode($queryAsisten, true);
        // dd($value2['datetime'], $endDate);

        $defafdmua = DB::connection('mysql2')->table('afdeling')
            ->select('afdeling.*', 'estate.*', 'afdeling.nama as afdnama')
            ->join('estate', 'estate.id', '=', 'afdeling.estate')
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->whereIn('estate.est', ['SRE', 'LDE'])
            ->get();
        $defafdmua = $defafdmua->groupBy(['wil', 'est', 'est']);
        $defafdmua = json_decode($defafdmua, true);

        $defafd = DB::connection('mysql2')->table('afdeling')
            ->select('afdeling.*', 'estate.*', 'afdeling.nama as afdnama')
            ->join('estate', 'estate.id', '=', 'afdeling.estate')
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->where('wil.regional', $regional)
            ->where('estate.emp', '!=', 1)
            ->whereNotIn('estate.est', ['SRE', 'LDE'])
            ->get();
        $defafd = $defafd->groupBy(['wil', 'est', 'afdnama']);
        $defafd = json_decode($defafd, true);

        $datatph = [];

        // dd($defafd);

        $chunkSize = 1000;
        if ($tahun != null) {
            DB::connection('mysql2')->table('sidak_tph')
                ->select(
                    "sidak_tph.*",
                    DB::raw('DATE_FORMAT(sidak_tph.datetime, "%M") as bulan'),
                    DB::raw('DATE_FORMAT(sidak_tph.datetime, "%Y") as tahun'),
                    DB::raw('DATE_FORMAT(sidak_tph.datetime, "%Y-%m-%d") as tanggal'),
                    DB::raw("
            CASE 
            WHEN status = '' THEN 1
            WHEN status = '0' THEN 1
            WHEN LOCATE('>H+', status) > 0 THEN '8'
            WHEN LOCATE('H+', status) > 0 THEN 
                CASE 
                    WHEN SUBSTRING_INDEX(SUBSTRING_INDEX(status, 'H+', -1), ' ', 1) > 8 THEN '8'
                    ELSE SUBSTRING_INDEX(SUBSTRING_INDEX(status, 'H+', -1), ' ', 1)
                END
            WHEN status REGEXP '^[0-9]+$' AND status > 8 THEN '8'
            WHEN LENGTH(status) > 1 AND status NOT LIKE '%H+%' AND status NOT LIKE '%>H+%' AND LOCATE(',', status) > 0 THEN SUBSTRING_INDEX(status, ',', 1)
            ELSE status
        END AS statuspanen")
                )

                ->join('estate', 'estate.est', '=', 'sidak_tph.est')
                ->join('wil', 'wil.id', '=', 'estate.wil')
                ->where('wil.regional', $regional)
                ->where('estate.emp', '!=', 1)
                // ->whereBetween(DB::raw('DATE_FORMAT(datetime, "%Y-%m")'), ["$start_date", "$end_date"])
                ->whereYear('datetime', $tahun)
                ->orderBy('afd', 'asc')
                ->orderBy('datetime', 'asc')
                ->chunk($chunkSize, function ($results) use (&$datatph) {
                    foreach ($results as $result) {
                        // Grouping logic here, if needed
                        $datatph[] = $result;
                        // Adjust this according to your grouping requirements
                    }
                });


            $datatph = collect($datatph)->groupBy(['est', 'afd', 'bulan', 'tanggal', 'statuspanen', 'blok']);
            $ancakFA = json_decode($datatph, true);
            $year = $tahun;
        } else {
            DB::connection('mysql2')->table('sidak_tph')
                ->select(
                    "sidak_tph.*",
                    DB::raw('DATE_FORMAT(sidak_tph.datetime, "%M") as bulan'),
                    DB::raw('DATE_FORMAT(sidak_tph.datetime, "%Y") as tahun'),
                    DB::raw('DATE_FORMAT(sidak_tph.datetime, "%Y-%m-%d") as tanggal'),
                    DB::raw("
            CASE 
            WHEN status = '' THEN 1
            WHEN status = '0' THEN 1
            WHEN LOCATE('>H+', status) > 0 THEN '8'
            WHEN LOCATE('H+', status) > 0 THEN 
                CASE 
                    WHEN SUBSTRING_INDEX(SUBSTRING_INDEX(status, 'H+', -1), ' ', 1) > 8 THEN '8'
                    ELSE SUBSTRING_INDEX(SUBSTRING_INDEX(status, 'H+', -1), ' ', 1)
                END
            WHEN status REGEXP '^[0-9]+$' AND status > 8 THEN '8'
            WHEN LENGTH(status) > 1 AND status NOT LIKE '%H+%' AND status NOT LIKE '%>H+%' AND LOCATE(',', status) > 0 THEN SUBSTRING_INDEX(status, ',', 1)
            ELSE status
        END AS statuspanen")
                )

                ->join('estate', 'estate.est', '=', 'sidak_tph.est')
                ->join('wil', 'wil.id', '=', 'estate.wil')
                ->where('wil.regional', $regional)
                ->where('estate.emp', '!=', 1)
                ->whereBetween(DB::raw('DATE_FORMAT(datetime, "%Y-%m")'), ["$start_date", "$end_date"])
                // ->whereYear('datetime', $tahun)
                ->orderBy('afd', 'asc')
                ->orderBy('datetime', 'asc')
                ->chunk($chunkSize, function ($results) use (&$datatph) {
                    foreach ($results as $result) {
                        // Grouping logic here, if needed
                        $datatph[] = $result;
                        // Adjust this according to your grouping requirements
                    }
                });


            $datatph = collect($datatph)->groupBy(['est', 'afd', 'bulan', 'tanggal', 'statuspanen', 'blok']);
            $ancakFA = json_decode($datatph, true);
            $year = date('Y', strtotime($start_date));
        }



        // dd($ancakFA, $start_date, $end_date);


        // dd($year);

        if ($regional == 3) {
            $months = [];

            for ($month = 1; $month <= 12; $month++) {
                $weeks = [];
                $firstDayOfMonth = strtotime("$year-$month-01");
                $lastDayOfMonth = strtotime(date('Y-m-t', $firstDayOfMonth));

                $weekNumber = 1;

                // Find the first Saturday of the month or the last Saturday of the previous month
                $firstSaturday = strtotime("last Saturday", $firstDayOfMonth);

                // Set the start date to the first Saturday
                $startDate = $firstSaturday;

                while ($startDate <= $lastDayOfMonth) {
                    $endDate = strtotime("next Friday", $startDate);
                    if ($endDate > $lastDayOfMonth) {
                        $endDate = $lastDayOfMonth;
                    }

                    $weeks[$weekNumber] = [
                        'start' => date('Y-m-d', $startDate),
                        'end' => date('Y-m-d', $endDate),
                    ];

                    // Update start date to the next Saturday
                    $startDate = strtotime("next Saturday", $endDate);

                    $weekNumber++;
                }

                $monthName = date('F', mktime(0, 0, 0, $month, 1));
                $months[$monthName] = $weeks;
            }

            $weeksdata = $months;
        } else {
            $months = [];
            for ($month = 1; $month <= 12; $month++) {
                $weeks = [];
                $firstDayOfMonth = strtotime("$year-$month-01");
                $lastDayOfMonth = strtotime(date('Y-m-t', $firstDayOfMonth));

                $weekNumber = 1;
                $startDate = $firstDayOfMonth;

                while ($startDate <= $lastDayOfMonth) {
                    $endDate = strtotime("next Sunday", $startDate);
                    if ($endDate > $lastDayOfMonth) {
                        $endDate = $lastDayOfMonth;
                    }

                    $weeks[$weekNumber] = [
                        'start' => date('Y-m-d', $startDate),
                        'end' => date('Y-m-d', $endDate),
                    ];

                    $nextMonday = strtotime("next Monday", $endDate);

                    // Check if the next Monday is still within the current month.
                    if (date('m', $nextMonday) == $month) {
                        $startDate = $nextMonday;
                    } else {
                        // If the next Monday is in the next month, break the loop.
                        break;
                    }

                    $weekNumber++;
                }

                $monthName = date('F', mktime(0, 0, 0, $month, 1));
                $months[$monthName] = $weeks;
            }

            $weeksdata = $months;
        }

        $result = [];

        foreach ($ancakFA as $category => $subCategories) {
            foreach ($subCategories as $subCategory => $monthlyData) {
                foreach ($monthlyData as $month => $dailyData) {
                    foreach ($dailyData as $date => $data) {

                        foreach ($weeksdata[$month] as $weekNumber => $week) {
                            if (strtotime($date) >= strtotime($week['start']) && strtotime($date) <= strtotime($week['end'])) {
                                // Create a new entry for the week if not exists
                                // dd('sex');
                                if (!isset($result[$category][$subCategory][$month]['week' . $weekNumber])) {
                                    $result[$category][$subCategory][$month]['week' . $weekNumber] = [];
                                }
                                // Assign data to the corresponding week
                                $result[$category][$subCategory][$month]['week' . $weekNumber] = $data;
                            }
                        }
                    }
                }
            }
        }

        $newSidak = array();

        // dd($result);

        foreach ($result as $key => $value1) {
            $v2check6 = 0;
            $tot_estAFd6 = 0;
            $totskor_brd6 = 0;
            $totskor_janjang6 = 0;
            foreach ($value1 as $key1 => $value2) {
                $total_skoreest = 0;
                $tot_estAFd = 0;
                $totskor_brd2 = 0;
                $totskor_janjang2 = 0;
                $total_estkors = 0;
                $total_skoreafd = 0;

                $deviden = 0;

                $v2check5 = 0;
                $tot_afdscoremonth = 0;
                $devest = count($value1);
                foreach ($value2 as $key2 => $value3) {
                    $tot_afdscore = 0;
                    $totskor_brd1 = 0;
                    $totskor_janjang1 = 0;
                    $total_skoreest = 0;
                    $v2check4 = 0;
                    $devidenmonth = count($value2);
                    foreach ($value3 as $key3 => $value4) {
                        $total_brondolan = 0;
                        $total_janjang = 0;
                        $tod_brd = 0;
                        $tod_jjg = 0;
                        $totskor_brd = 0;
                        $totskor_janjang = 0;
                        $tot_brdxm = 0;
                        $tod_janjangxm = 0;
                        $v2check3 = 0;
                        // dd($key2);
                        $deviden = count($value3);
                        foreach ($value4 as $key4 => $value5) {
                            $tph = 0;
                            $jalan = 0;
                            $bin = 0;
                            $karung = 0;
                            $buah = 0;
                            $restan = 0;
                            $v2check = count($value5);
                            foreach ($value5 as $key5 => $value6) {
                                $sum_bt_tph = 0;
                                $sum_bt_jalan = 0;
                                $sum_bt_bin = 0;
                                $sum_jum_karung = 0;
                                $sum_buah_tinggal = 0;
                                $sum_restan_unreported = 0;
                                $dataparams = '-';
                                foreach ($value6 as $key6 => $value7) {
                                    $sum_bt_tph += $value7['bt_tph'];
                                    $sum_bt_jalan += $value7['bt_jalan'];
                                    $sum_bt_bin += $value7['bt_bin'];
                                    $sum_jum_karung += $value7['jum_karung'];

                                    // dd($value7);
                                    $sum_buah_tinggal += $value7['buah_tinggal'];
                                    $sum_restan_unreported += $value7['restan_unreported'];

                                    $newparamsdate = '2024-03-01';

                                    $tanggalDateTime = new DateTime($value7['tanggal']);
                                    // dd($tanggalDateTime);
                                    $newparamsdateDateTime = new DateTime($newparamsdate);
                                    // dd($newparamsdateDateTime);

                                    if ($tanggalDateTime >= $newparamsdateDateTime) {
                                        $dataparams = 'new';
                                    } else {
                                        $dataparams = 'old';
                                    }
                                } # code... dd($value3);

                                $newSidak[$key][$key1][$key2][$key3][$key4][$key5]['tph'] = $sum_bt_tph;
                                $newSidak[$key][$key1][$key2][$key3][$key4][$key5]['jalan'] = $sum_bt_jalan;
                                $newSidak[$key][$key1][$key2][$key3][$key4][$key5]['bin'] = $sum_bt_bin;
                                $newSidak[$key][$key1][$key2][$key3][$key4][$key5]['karung'] = $sum_jum_karung;
                                $newSidak[$key][$key1][$key2][$key3][$key4][$key5]['buah'] = $sum_buah_tinggal;
                                $newSidak[$key][$key1][$key2][$key3][$key4][$key5]['restan'] = $sum_restan_unreported;

                                $tph += $sum_bt_tph;
                                $jalan += $sum_bt_jalan;
                                $bin += $sum_bt_bin;
                                $karung += $sum_jum_karung;
                                $buah += $sum_buah_tinggal;
                                $restan += $sum_restan_unreported;
                            } # code... dd($value3);


                            $status_panen = $key4;
                            if ($dataparams === 'new') {
                                [$panen_brd, $panen_jjg] = calculatePanennew($status_panen);
                            } else {
                                [$panen_brd, $panen_jjg] = calculatePanen($status_panen);
                            }

                            // [$panen_brd, $panen_jjg] = calculatePanen($status_panen);

                            $total_brondolan =  round(($tph + $jalan + $bin + $karung) * $panen_brd / 100, 1);
                            $total_janjang =  round(($buah + $restan) * $panen_jjg / 100, 1);
                            $tod_brd = $tph + $jalan + $bin + $karung;
                            $tod_jjg = $buah + $restan;
                            $newSidak[$key][$key1][$key2][$key3][$key4]['tph'] = $tph;
                            $newSidak[$key][$key1][$key2][$key3][$key4]['jalan'] = $jalan;
                            $newSidak[$key][$key1][$key2][$key3][$key4]['bin'] = $bin;
                            $newSidak[$key][$key1][$key2][$key3][$key4]['karung'] = $karung;
                            $newSidak[$key][$key1][$key2][$key3][$key4]['tot_brd'] = $tod_brd;

                            $newSidak[$key][$key1][$key2][$key3][$key4]['buah'] = $buah;
                            $newSidak[$key][$key1][$key2][$key3][$key4]['restan'] = $restan;
                            $newSidak[$key][$key1][$key2][$key3][$key4]['skor_brd'] = $total_brondolan;
                            $newSidak[$key][$key1][$key2][$key3][$key4]['skor_janjang'] = $total_janjang;
                            $newSidak[$key][$key1][$key2][$key3][$key4]['tod_jjg'] = $tod_jjg;
                            $newSidak[$key][$key1][$key2][$key3][$key4]['v2check2'] = $v2check;
                            $newSidak[$key][$key1][$key2][$key3][$key4]['dataparams'] = $dataparams;


                            $totskor_brd += $total_brondolan;
                            $totskor_janjang += $total_janjang;
                            $tot_brdxm += $tod_brd;
                            $tod_janjangxm += $tod_jjg;
                            $v2check3 += $v2check;
                        } # code... dd($value3);
                        $total_estkors = $totskor_brd + $totskor_janjang;
                        if ($total_estkors != 0) {
                            $newSidak[$key][$key1][$key2][$key3]['all_score'] = 100 - ($total_estkors);
                            $newSidak[$key][$key1][$key2][$key3]['check_data'] = 'ada';

                            $total_skoreafd = 100 - ($total_estkors);
                        } else if ($v2check3 != 0) {
                            $newSidak[$key][$key1][$key2]['all_score'] = 100 - ($total_estkors);
                            $newSidak[$key][$key1][$key2]['check_data'] = 'ada';

                            $total_skoreafd = 100 - ($total_estkors);
                        } else {
                            $newSidak[$key][$key1][$key2][$key3]['all_score'] = 0;
                            $newSidak[$key][$key1][$key2][$key3]['check_data'] = 'null';
                            $total_skoreafd = 0;
                        }
                        // $newSidak[$key][$key1][$key2]['all_score'] = 100 - ($total_estkors);
                        $newSidak[$key][$key1][$key2][$key3]['total_brd'] = $tot_brdxm;
                        $newSidak[$key][$key1][$key2][$key3]['total_brdSkor'] = $totskor_brd;
                        $newSidak[$key][$key1][$key2][$key3]['total_janjang'] = $tod_janjangxm;
                        $newSidak[$key][$key1][$key2][$key3]['total_janjangSkor'] = $totskor_janjang;
                        $newSidak[$key][$key1][$key2][$key3]['total_skor'] = $total_skoreafd;
                        $newSidak[$key][$key1][$key2][$key3]['janjang_brd'] = $totskor_brd + $totskor_janjang;
                        $newSidak[$key][$key1][$key2][$key3]['v2check3'] = $v2check3;

                        $totskor_brd1 += $totskor_brd;
                        $totskor_janjang1 += $totskor_janjang;
                        $total_skoreest += $total_skoreafd;
                        $v2check4 += $v2check3;

                        // dd($key3);
                    } # code...


                    if ($v2check4 != 0 && $total_skoreest == 0) {
                        $tot_afdscore = 100;
                    } else if ($deviden != 0) {
                        $tot_afdscore = round($total_skoreest / $deviden, 2);
                    } else if ($deviden == 0 && $v2check4 == 0) {
                        $tot_afdscore = 0;
                    }

                    // $newSidak[$key][$key1]['deviden'] = $deviden;

                    $newSidak[$key][$key1][$key2]['total_brd'] = $totskor_brd1;
                    $newSidak[$key][$key1][$key2]['total_janjang'] = $totskor_janjang1;
                    if ($v2check4 == 0) {
                        $newSidak[$key][$key1][$key2]['total_score'] = '-';
                    } else {
                        $newSidak[$key][$key1][$key2]['total_score'] = $tot_afdscore;
                    }


                    $newSidak[$key][$key1][$key2]['deviden'] = $deviden;
                    $newSidak[$key][$key1][$key2]['v2check4'] = $v2check4;

                    $tot_estAFd += $tot_afdscore;
                    $totskor_brd2 += $totskor_brd1;
                    $totskor_janjang2 += $totskor_janjang1;
                    // $new_dvdAfd += $new_dvd;
                    // $new_dvdAfdest += $new_dvdest;
                    $v2check5 += $v2check4;
                } # code...


                if ($v2check5 != 0 && $tot_estAFd == 0) {
                    $tot_afdscoremonth = 100;
                } else if ($devidenmonth != 0) {
                    $score =  round($tot_estAFd / $devidenmonth, 2);
                    if ($score < 0) {
                        $tot_afdscoremonth = 0;
                    } else {
                        $tot_afdscoremonth = $score;
                    }
                } else if ($devidenmonth == 0 && $v2check5 == 0) {
                    $tot_afdscoremonth = 0;
                }

                $newSidak[$key][$key1]['deviden'] = $devidenmonth;
                $newSidak[$key][$key1]['deviden'] = ($v2check5 > 0) ? 1 : 0;
                if ($v2check4 == 0) {
                    $newSidak[$key][$key1]['total_score'] = 0;
                } else {
                    $newSidak[$key][$key1]['total_score'] = $tot_afdscoremonth;
                }
                $newSidak[$key][$key1]['total_brd'] = $totskor_brd2;
                $newSidak[$key][$key1]['total_janjang'] = $totskor_janjang2;
                $newSidak[$key][$key1]['est'] = $key;
                $newSidak[$key][$key1]['afd'] = $key1;
                $newSidak[$key][$key1]['v2check5'] = $v2check5;

                $v2check6 += $v2check5;
                $tot_estAFd6 += $tot_afdscoremonth;
                $totskor_brd6 += $totskor_brd2;
                $totskor_janjang6 += $totskor_janjang2;
            }
            if ($v2check6 != 0 && $tot_estAFd6 == 0) {
                $todest = 100;
            } else if ($devest != 0) {
                // $todest = $tot_estAFd6 . '/' . $devest;
                $todest = round($tot_estAFd6 / $devest, 2);
            } else if ($devest == 0 && $v2check6 == 0) {
                $todest = 0;
            }
            $newSidak[$key]['deviden'] = ($v2check6 > 0) ? 1 : 0;
            if ($v2check4 == 0) {
                $newSidak[$key]['total_score'] = 0;
            } else {
                $newSidak[$key]['total_score'] = $todest;
            }
            $newSidak[$key]['total_brd'] = $totskor_brd6;
            $newSidak[$key]['total_janjang'] = $totskor_janjang6;
            $newSidak[$key]['est'] = $key;
            $newSidak[$key]['afd'] = $key1;
            $newSidak[$key]['tot_estAFd6'] = $tot_estAFd6;
            $newSidak[$key]['devest'] = $devest;
            $newSidak[$key]['v2check6'] = $v2check6;
        }
        // dd($newSidak);
        // dd($);

        // dd($newSidak['SJE']['OL']);
        $newsidakend = [];
        foreach ($defafd as $key => $value) {
            foreach ($value as $key2 => $value2) {
                $divest = 0;
                $scoreest = 0;
                $totbrd = 0;
                $totjjg = 0;
                $v2check5 = 0;
                foreach ($value2 as $key3 => $value3) {
                    $datas = [];
                    foreach ($newSidak as $keysidak => $valsidak) {
                        if ($key2 == $keysidak) {


                            foreach ($valsidak as $keysidak1 => $valsidak1) {
                                if ($keysidak1 == $key3) {
                                    // Key exists, assign values
                                    $deviden = $valsidak1['deviden'];
                                    $totalscore = $valsidak1['total_score'];
                                    $totalbrd = $valsidak1['total_brd'];
                                    $total_janjang = $valsidak1['total_janjang'];
                                    $v2check4 = $valsidak1['v2check5'];

                                    $newsidakend[$key][$key2][$key3]['deviden'] = $deviden;
                                    $newsidakend[$key][$key2][$key3]['total_score'] = $totalscore;
                                    $newsidakend[$key][$key2][$key3]['total_brd'] = $totalbrd;
                                    $newsidakend[$key][$key2][$key3]['total_janjang'] = $total_janjang;
                                    $newsidakend[$key][$key2][$key3]['v2check4'] = $v2check4;


                                    $scoreest += $totalscore;
                                    $totbrd += $totalbrd;
                                    $totjjg += $total_janjang;
                                    $v2check5 += $v2check4;
                                    $divest += $deviden;

                                    if ($v2check4 != 0) {
                                        $newdiff = 1;
                                    } else {
                                        $newdiff = 0;
                                    }
                                }
                            }

                            // $datas[] = $newdiff;
                        }
                    }

                    // dd($divest);
                    // If key not found, set default values
                    if (!isset($newsidakend[$key][$key2][$key3])) {
                        $newsidakend[$key][$key2][$key3]['deviden'] = 0;
                        $newsidakend[$key][$key2][$key3]['total_score'] = 0;
                        $newsidakend[$key][$key2][$key3]['total_brd'] = 0;
                        $newsidakend[$key][$key2][$key3]['total_janjang'] = 0;
                        $newsidakend[$key][$key2][$key3]['v2check4'] = 0;
                    }
                }
                // Assign calculated values outside the innermost loop
                $newsidakend[$key][$key2]['deviden'] = $divest;
                $newsidakend[$key][$key2]['v2check5'] = $v2check5;
                $newsidakend[$key][$key2]['score_estate'] = ($divest !== 0) ? round($scoreest / $divest, 2) : 0;
                $newsidakend[$key][$key2]['totbrd'] = $totbrd;
                $newsidakend[$key][$key2]['totjjg'] = $totjjg;
                $newsidakend[$key][$key2]['scoreest'] = $scoreest;
                $newsidakend[$key][$key2]['divest'] = $divest;
            }
        }

        // dd($newsidakend);
        if ($regional == 1) {
            $newsidakendmua = [];

            // dd($defafdmua);
            foreach ($defafdmua as $key => $value) {
                $divest1 = 0;
                $scoreest1 = 0;
                $totbrd1 = 0;
                $totjjg1 = 0;
                $v2check51 = 0;
                foreach ($value as $key2 => $value2) {
                    $divest = 0;
                    $scoreest = 0;
                    $totbrd = 0;
                    $totjjg = 0;
                    $v2check5 = 0;
                    foreach ($value2 as $key3 => $value3) {
                        foreach ($newSidak as $keysidak => $valsidak) {
                            if ($key2 == $keysidak) {
                                foreach ($valsidak as $keysidak1 => $valsidak1) {
                                    if ($keysidak1 == $key3) {
                                        // Key exists, assign values
                                        $deviden = $valsidak1['deviden'];
                                        $totalscore = $valsidak1['total_score'];
                                        $totalbrd = $valsidak1['total_brd'];
                                        $total_janjang = $valsidak1['total_janjang'];
                                        $v2check4 = $valsidak1['v2check5'];

                                        $newsidakendmua[$key][$key2][$key3]['deviden'] = $deviden;
                                        $newsidakendmua[$key][$key2][$key3]['total_score'] = $totalscore;
                                        $newsidakendmua[$key][$key2][$key3]['total_brd'] = $totalbrd;
                                        $newsidakendmua[$key][$key2][$key3]['total_janjang'] = $total_janjang;
                                        $newsidakendmua[$key][$key2][$key3]['v2check4'] = $v2check4;

                                        $divest += $deviden;
                                        $scoreest += $totalscore;
                                        $totbrd += $totalbrd;
                                        $totjjg += $total_janjang;
                                        $v2check5 += $v2check4;
                                    }
                                }
                            }
                        }
                        // If key not found, set default values
                        if (!isset($newsidakendmua[$key][$key2][$key3])) {
                            $newsidakendmua[$key][$key2][$key3]['deviden'] = 0;
                            $newsidakendmua[$key][$key2][$key3]['total_score'] = 0;
                            $newsidakendmua[$key][$key2][$key3]['total_brd'] = 0;
                            $newsidakendmua[$key][$key2][$key3]['total_janjang'] = 0;
                            $newsidakendmua[$key][$key2][$key3]['v2check4'] = 0;
                        }
                    }
                    if ($v2check5 != 0) {
                        $data = 'ada';
                        $estatescorx = ($divest !== 0) ? round($scoreest / $divest, 2) : 0;
                        $newskaxa = ($divest !== 0) ? round($scoreest / $divest, 2) : 0;
                    } else {
                        $data = 'kosong';
                        $estatescorx = 0;
                        $newskaxa = '-';
                    }
                    $ass = '-';
                    foreach ($queryAsisten as $ast => $asisten) {
                        if ($key2 === $asisten['est'] && 'OA' === $asisten['afd']) {
                            $ass = $asisten['nama'];
                            break;
                        }
                    }
                    // Assign calculated values outside the innermost loop
                    $newsidakendmua[$key][$key2]['deviden'] = $divest;
                    $newsidakendmua[$key][$key2]['v2check5'] = $v2check5;
                    $newsidakendmua[$key][$key2]['checkdata'] = $data;
                    $newsidakendmua[$key][$key2]['Nama_assist'] = $ass;
                    $newsidakendmua[$key][$key2]['score_estate'] = $newskaxa;
                    $newsidakendmua[$key][$key2]['totbrd'] = $totbrd;
                    $newsidakendmua[$key][$key2]['totjjg'] = $totjjg;


                    $divest1 +=  $divest;
                    $scoreest1 += $estatescorx;
                    $totbrd1 += $totbrd;
                    $totjjg1 +=  $totjjg;
                    $v2check51 += $v2check5;
                }
                if ($v2check51 != 0) {
                    $data = 'ada';
                    $skor = round($scoreest1 / $divest1, 2);
                } else {
                    $data = 'kosong';
                    $skor = '-';
                }
                $namasisten = ''; // Initialize $namasisten before the loop
                foreach ($queryAsisten as $ast => $asisten) {
                    if ('PT.MUA' === $asisten['est'] && 'EM' === $asisten['afd']) {
                        $namasisten = $asisten['nama'];
                        break;
                    }
                }
                // Now $namasisten is defined even if the loop doesn't run

                $newsidakendmua[$key]['PT.MUA'] = [
                    'deviden' => $divest1,
                    'v2check5' => $v2check51,
                    'checkdata' => $data,
                    'score_estate' => $skor,
                    'Nama_assist' => $namasisten

                ];
            }
            // dd($newsidakendmua);
            foreach ($newsidakendmua as $key => $value) {
                # code...
                $newsidakendmua = $value;
            }
            $newsidakend[3]['PT.MUA'] =  $newsidakendmua['PT.MUA'];
        } else {
            $newsidakendmua = [];
        }
        // unutk mua ========================================


        // dd($newsidakend, $newsidakendmua);


        // end mua 



        $divestrh = [];
        $skorrh = 0;
        foreach ($newsidakend as $key => $value) {
            $vhcek = 0;
            $skor = 0;
            $totalvhcek = 0;
            $divest = []; // Initialize $divest as an empty array for each iteration

            foreach ($value as $key1 => $value1) {
                $vhcek = $value1['v2check5'];
                $totalvhcek += $value1['v2check5'];
                $skor += $value1['score_estate'];

                if ($vhcek != 0) {
                    $div = 1;
                } else {
                    $div = 0;
                }

                $divest[] = $div;
            }
            $em = 'GM';

            $nama_em = '';
            $newkey = 'WIL-' . convertToRoman($key);
            // dd($newkey);
            foreach ($queryAsisten as $ast => $asisten) {
                if ($newkey === $asisten['est'] && $em === $asisten['afd']) {
                    $nama_em = $asisten['nama'] ?? '-';
                }
            }
            $dividen = array_sum($divest);

            $total = $dividen != 0 ? round($skor / $dividen, 2) : 0;


            $newsidakend[$key]['check'] = $totalvhcek;
            $newsidakend[$key]['div'] = $dividen;
            $newsidakend[$key]['skor'] = $total;
            $newsidakend[$key]['nama'] = $nama_em;
            $newsidakend[$key]['newkey'] = $newkey;


            $skorrh += $total;
            $checkrh = $totalvhcek;


            if ($checkrh != 0) {
                $divrh = 1;
            } else {
                $divrh = 0;
            }

            $divestrh[] = $divrh;
        }
        $em = 'RH';

        $nama_em = '';
        $newkey = 'REG-' . convertToRoman($regional);
        // dd($newkey);
        foreach ($queryAsisten as $ast => $asisten) {
            if ($newkey === $asisten['est'] && $em === $asisten['afd']) {
                $nama_em = $asisten['nama'] ?? '-';
            }
        }
        $dividenrh = array_sum($divestrh);

        $totalrh = round($skorrh / $dividenrh, 2);

        $newsidakend['totalskor'] = $skorrh;
        $newsidakend['div'] = $dividenrh;
        $newsidakend['skor'] = $totalrh;
        $newsidakend['nama'] = $nama_em;
        $newsidakend['newkey'] = $newkey;

        // dd($newsidakend);
        $data = [];
        foreach ($newsidakend as $key => $value) if (is_array($value)) {
            foreach ($value as $key1 => $value1) if (is_array($value1)) {
                foreach ($value1 as $key2 => $value2) if (is_array($value2)) {
                    foreach ($queryAsisten as $keyx => $valuex) if ($valuex['est'] === $key1 && $valuex['afd'] === $key2) {
                        $data[$key][$key1][$key2]['nama'] = $valuex['nama'] ?? '-';
                        break;
                    }
                    // $data[$key][$key1][$key2]['nama'] = 'nama';
                    $data[$key][$key1][$key2]['total_score'] = $value2['total_score'];
                    $data[$key][$key1][$key2]['est'] = $key1;
                    $data[$key][$key1][$key2]['afd'] = $key2;
                    $data[$key][$key1][$key2]['bgcolor'] = 'white';
                }
                $nama = '-';
                foreach ($queryAsisten as $keyx => $valuex) if ($valuex['est'] === $key1 && $valuex['afd'] === 'EM') {
                    $nama = $valuex['nama'] ?? '-';
                    break;
                }
                $estate = [
                    'total_score' => $value1['score_estate'],
                    'est' => $key1,
                    'afd' => '-',
                    'nama' => $nama,
                    'bgcolor' => '#a0978d'
                ];

                $data[$key][$key1]['est'] = $estate;
            }

            $data[$key]['A']['EST']  = [
                'total_score' => $value['skor'],
                'est' => $value['newkey'],
                'afd' => '-',
                'nama' => $value['nama'],
                'bgcolor' => '#FFF176'
            ];
        }
        // dd($data);
        $rhdata =  [
            'total_score' => $newsidakend['skor'] ?? 0,
            'est' => $newsidakend['newkey'],
            'afd' => '-',
            'nama' => $newsidakend['nama'],
            'bgcolor' => '#FFF176'
        ];
        unset($data[3]['PT.MUA']);

        return [
            'newsidakend' => $data,
            'rhdata' => $rhdata,
            'rekapmua' => $newsidakendmua,
        ];
    }
}

//helper halaman sidak mutu buah pertahun
if (!function_exists('rekap_pertahun_mutubuah')) {
    function rekap_pertahun_mutubuah($regional, $tahun)
    {
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
            ->where('estate.emp', '!=', 1)
            ->whereNotIn('estate.est', ['SRE', 'LDE'])
            ->get();
        $queryEste = json_decode($queryEste, true);

        $defafd = DB::connection('mysql2')->table('afdeling')
            ->select('afdeling.*', 'estate.*', 'afdeling.nama as afdnama')
            ->join('estate', 'estate.id', '=', 'afdeling.estate')
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->where('wil.regional', $regional)
            ->where('estate.emp', '!=', 1)
            ->get();
        $defafd = $defafd->groupBy(['wil', 'est', 'afdnama']);
        $defafd = json_decode($defafd, true);
        $muaest = DB::connection('mysql2')->table('estate')
            ->select('estate.*')
            ->where('estate.emp', '!=', 1)
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->where('wil.regional', $regional)
            // ->where('estate.emp', '!=', 1)
            ->whereIn('estate.est', ['SRE', 'LDE'])
            ->get('est');
        $muaest = json_decode($muaest, true);


        // dd($defafd);

        $estev2 = DB::connection('mysql2')->table('estate')
            ->select('estate.*')
            ->where('estate.emp', '!=', 1)
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->where('wil.regional', $regional)
            ->where('estate.emp', '!=', 1)
            ->whereNotIn('estate.est', ['SRE', 'LDE'])
            ->pluck('est');
        $estev2 = json_decode($estev2, true);

        $queryAfd = DB::connection('mysql2')->table('afdeling')
            ->select(
                'afdeling.id',
                'afdeling.nama',
                'estate.est'
            ) //buat mengambil data di estate db dan willayah db
            ->join('estate', 'estate.id', '=', 'afdeling.estate') //kemudian di join untuk mengambil est perwilayah
            ->get();
        $queryAfd = json_decode($queryAfd, true);


        $data = [];
        $chunkSize = 1000;

        DB::connection('mysql2')->table('sidak_mutu_buah')
            ->select(
                "sidak_mutu_buah.*",
                DB::raw('DATE_FORMAT(sidak_mutu_buah.datetime, "%M") as bulan'),
                DB::raw('DATE_FORMAT(sidak_mutu_buah.datetime, "%Y") as tahun')
            )
            ->join('estate', 'estate.est', '=', 'sidak_mutu_buah.estate')
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->where('wil.regional', $regional)
            ->where('estate.emp', '!=', 1)
            ->whereYear('datetime', $tahun)
            ->orderBy('datetime', 'asc')
            ->chunk($chunkSize, function ($results) use (&$data) {
                foreach ($results as $result) {
                    // Grouping logic here, if needed
                    $data[] = $result;
                    // Adjust this according to your grouping requirements
                }
            });

        $data = collect($data)->groupBy(['estate', 'afdeling']);
        $queryMTbuah = json_decode($data, true);


        // dd($queryMTbuah);
        $databulananBuah = array();
        foreach ($queryMTbuah as $key => $value) {
            foreach ($value as $key2 => $value2) {
                foreach ($value2 as $keytph => $value3) {

                    $databulananBuah[$key][$key2][$keytph] = $value3;
                }
            }
        }

        $defPerbulanWil = array();

        foreach ($queryEste as $key2 => $value2) {
            foreach ($queryAfd as $key3 => $value3) {
                if ($value2['est'] == $value3['est']) {
                    $defPerbulanWil[$value2['est']][$value3['nama']] = 0;
                }
            }
        }



        foreach ($defPerbulanWil as $estateKey => $afdelingArray) {
            foreach ($afdelingArray as $afdelingKey => $afdelingValue) {
                if (isset($databulananBuah[$estateKey]) && isset($databulananBuah[$estateKey][$afdelingKey])) {
                    $defPerbulanWil[$estateKey][$afdelingKey] = $databulananBuah[$estateKey][$afdelingKey];
                }
            }
        }

        $sidak_buah = array();
        // dd($defPerbulanWil);

        foreach ($defPerbulanWil as $key => $value) {
            $jjg_samplex = 0;
            $tnpBRDx = 0;
            $krgBRDx = 0;
            $abrx = 0;
            $overripex = 0;
            $emptyx = 0;
            $vcutx = 0;
            $rdx = 0;
            $dataBLokx = 0;
            $sum_krx = 0;
            $csrms = 0;
            foreach ($value as $key1 => $value1) {
                if (is_array($value1)) {
                    $jjg_sample = 0;
                    $tnpBRD = 0;
                    $krgBRD = 0;
                    $abr = 0;
                    $skor_total = 0;
                    $overripe = 0;
                    $empty = 0;
                    $vcut = 0;
                    $rd = 0;
                    $sum_kr = 0;
                    $allSkor = 0;
                    $combination_counts = array();
                    $newblok = 0;
                    $csfxr = count($value1);
                    foreach ($value1 as $key2 => $value2) {
                        $combination = $value2['blok'] . ' ' . $value2['estate'] . ' ' . $value2['afdeling'] . ' ' . $value2['tph_baris'];
                        if (!isset($combination_counts[$combination])) {
                            $combination_counts[$combination] = 0;
                        }
                        $newblok = count($value1);
                        $jjg_sample += $value2['jumlah_jjg'];
                        $tnpBRD += $value2['bmt'];
                        $krgBRD += $value2['bmk'];
                        $abr += $value2['abnormal'];
                        $overripe += $value2['overripe'];
                        $empty += $value2['empty_bunch'];
                        $vcut += $value2['vcut'];
                        $rd += $value2['rd'];
                        $sum_kr += $value2['alas_br'];
                    }
                    // $dataBLok = count($combination_counts);
                    $dataBLok = $newblok;
                    if ($sum_kr != 0) {
                        $total_kr = round($sum_kr / $dataBLok, 2);
                    } else {
                        $total_kr = 0;
                    }
                    $per_kr = round($total_kr * 100, 2);
                    $skor_total = round((($tnpBRD + $krgBRD) / ($jjg_sample - $abr)) * 100, 2);
                    $skor_jjgMSk = round(($jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr)) / ($jjg_sample - $abr) * 100, 2);
                    $skor_lewatMTng =  round(($overripe / ($jjg_sample - $abr)) * 100, 2);
                    $skor_jjgKosong =  round(($empty / ($jjg_sample - $abr)) * 100, 2);
                    $skor_vcut =   round(($vcut / $jjg_sample) * 100, 2);
                    $allSkor = sidak_brdTotal($skor_total) +  sidak_matangSKOR($skor_jjgMSk) +  sidak_lwtMatang($skor_lewatMTng) + sidak_jjgKosong($skor_jjgKosong) + sidak_tangkaiP($skor_vcut) + sidak_PengBRD($per_kr);

                    $sidak_buah[$key][$key1]['Jumlah_janjang'] = $jjg_sample;
                    $sidak_buah[$key][$key1]['blok'] = $dataBLok;
                    $sidak_buah[$key][$key1]['est'] = $key;
                    $sidak_buah[$key][$key1]['afd'] = $key1;
                    $sidak_buah[$key][$key1]['nama_staff'] = '-';
                    $sidak_buah[$key][$key1]['tnp_brd'] = $tnpBRD;
                    $sidak_buah[$key][$key1]['krg_brd'] = $krgBRD;
                    $sidak_buah[$key][$key1]['persenTNP_brd'] = round(($tnpBRD / ($jjg_sample - $abr)) * 100, 2);
                    $sidak_buah[$key][$key1]['persenKRG_brd'] = round(($krgBRD / ($jjg_sample - $abr)) * 100, 2);
                    $sidak_buah[$key][$key1]['total_jjg'] = $tnpBRD + $krgBRD;
                    $sidak_buah[$key][$key1]['persen_totalJjg'] = $skor_total;
                    $sidak_buah[$key][$key1]['skor_total'] = sidak_brdTotal($skor_total);
                    $sidak_buah[$key][$key1]['jjg_matang'] = $jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr);
                    $sidak_buah[$key][$key1]['persen_jjgMtang'] = $skor_jjgMSk;
                    $sidak_buah[$key][$key1]['skor_jjgMatang'] = sidak_matangSKOR($skor_jjgMSk);
                    $sidak_buah[$key][$key1]['lewat_matang'] = $overripe;
                    $sidak_buah[$key][$key1]['persen_lwtMtng'] =  $skor_lewatMTng;
                    $sidak_buah[$key][$key1]['skor_lewatMTng'] = sidak_lwtMatang($skor_lewatMTng);
                    $sidak_buah[$key][$key1]['janjang_kosong'] = $empty;
                    $sidak_buah[$key][$key1]['persen_kosong'] = $skor_jjgKosong;
                    $sidak_buah[$key][$key1]['skor_kosong'] = sidak_jjgKosong($skor_jjgKosong);
                    $sidak_buah[$key][$key1]['vcut'] = $vcut;
                    $sidak_buah[$key][$key1]['karung'] = $sum_kr;
                    $sidak_buah[$key][$key1]['vcut_persen'] = $skor_vcut;
                    $sidak_buah[$key][$key1]['vcut_skor'] = sidak_tangkaiP($skor_vcut);
                    $sidak_buah[$key][$key1]['abnormal'] = $abr;
                    $sidak_buah[$key][$key1]['abnormal_persen'] = round(($abr / $jjg_sample) * 100, 2);
                    $sidak_buah[$key][$key1]['rat_dmg'] = $rd;
                    $sidak_buah[$key][$key1]['rd_persen'] = round(($rd / $jjg_sample) * 100, 2);
                    $sidak_buah[$key][$key1]['TPH'] = $total_kr;
                    $sidak_buah[$key][$key1]['persen_krg'] = $per_kr;
                    $sidak_buah[$key][$key1]['skor_kr'] = sidak_PengBRD($per_kr);
                    $sidak_buah[$key][$key1]['All_skor'] = $allSkor;
                    $sidak_buah[$key][$key1]['csfxr'] = $csfxr;
                    $sidak_buah[$key][$key1]['kategori'] = sidak_akhir($allSkor);

                    foreach ($queryAsisten as $ast => $asisten) {
                        if ($key === $asisten['est'] && $key1 === $asisten['afd']) {
                            $sidak_buah[$key][$key1]['nama_asisten'] = $asisten['nama'];
                        }
                    }
                    $jjg_samplex += $jjg_sample;
                    $tnpBRDx += $tnpBRD;
                    $krgBRDx += $krgBRD;
                    $abrx += $abr;
                    $overripex += $overripe;
                    $emptyx += $empty;
                    $vcutx += $vcut;

                    $rdx += $rd;

                    $dataBLokx += $newblok;
                    $sum_krx += $sum_kr;
                    $csrms += $csfxr;
                } else {

                    $sidak_buah[$key][$key1]['Jumlah_janjang'] = 0;
                    $sidak_buah[$key][$key1]['blok'] = 0;
                    $sidak_buah[$key][$key1]['est'] = $key;
                    $sidak_buah[$key][$key1]['afd'] = $key1;
                    $sidak_buah[$key][$key1]['nama_staff'] = '-';
                    $sidak_buah[$key][$key1]['tnp_brd'] = 0;
                    $sidak_buah[$key][$key1]['krg_brd'] = 0;
                    $sidak_buah[$key][$key1]['persenTNP_brd'] = 0;
                    $sidak_buah[$key][$key1]['persenKRG_brd'] = 0;
                    $sidak_buah[$key][$key1]['total_jjg'] = 0;
                    $sidak_buah[$key][$key1]['persen_totalJjg'] = 0;
                    $sidak_buah[$key][$key1]['skor_total'] = 0;
                    $sidak_buah[$key][$key1]['jjg_matang'] = 0;
                    $sidak_buah[$key][$key1]['persen_jjgMtang'] = 0;
                    $sidak_buah[$key][$key1]['skor_jjgMatang'] = 0;
                    $sidak_buah[$key][$key1]['lewat_matang'] = 0;
                    $sidak_buah[$key][$key1]['persen_lwtMtng'] =  0;
                    $sidak_buah[$key][$key1]['skor_lewatMTng'] = 0;
                    $sidak_buah[$key][$key1]['janjang_kosong'] = 0;
                    $sidak_buah[$key][$key1]['persen_kosong'] = 0;
                    $sidak_buah[$key][$key1]['skor_kosong'] = 0;
                    $sidak_buah[$key][$key1]['vcut'] = 0;
                    $sidak_buah[$key][$key1]['karung'] = 0;
                    $sidak_buah[$key][$key1]['vcut_persen'] = 0;
                    $sidak_buah[$key][$key1]['vcut_skor'] = 0;
                    $sidak_buah[$key][$key1]['abnormal'] = 0;
                    $sidak_buah[$key][$key1]['abnormal_persen'] = 0;
                    $sidak_buah[$key][$key1]['rat_dmg'] = 0;
                    $sidak_buah[$key][$key1]['rd_persen'] = 0;
                    $sidak_buah[$key][$key1]['TPH'] = 0;
                    $sidak_buah[$key][$key1]['persen_krg'] = 0;
                    $sidak_buah[$key][$key1]['skor_kr'] = 0;
                    $sidak_buah[$key][$key1]['All_skor'] = 0;
                    $sidak_buah[$key][$key1]['kategori'] = 0;
                    $sidak_buah[$key][$key1]['csfxr'] = 0;
                    foreach ($queryAsisten as $ast => $asisten) {
                        if ($key === $asisten['est'] && $key1 === $asisten['afd']) {
                            $sidak_buah[$key][$key1]['nama_asisten'] = $asisten['nama'];
                        }
                    }
                }
            }
            if ($sum_krx != 0) {
                $total_kr = round($sum_krx / $dataBLokx, 2);
            } else {
                $total_kr = 0;
            }
            $per_kr = round($total_kr * 100, 2);
            $skor_total = round(($jjg_samplex - $abrx != 0 ? (($tnpBRDx + $krgBRDx) / ($jjg_samplex - $abrx)) * 100 : 0), 2);

            $skor_jjgMSk = round(($jjg_samplex - $abrx != 0 ? (($jjg_samplex - ($tnpBRDx + $krgBRDx + $overripex + $emptyx + $abrx)) / ($jjg_samplex - $abrx)) * 100 : 0), 2);

            $skor_lewatMTng = round(($jjg_samplex - $abrx != 0 ? ($overripex / ($jjg_samplex - $abrx)) * 100 : 0), 2);

            $skor_jjgKosong = round(($jjg_samplex - $abrx != 0 ? ($emptyx / ($jjg_samplex - $abrx)) * 100 : 0), 2);

            $skor_vcut = round(($jjg_samplex != 0 ? ($vcutx / $jjg_samplex) * 100 : 0), 2);

            $allSkor = sidak_brdTotal($skor_total) +  sidak_matangSKOR($skor_jjgMSk) +  sidak_lwtMatang($skor_lewatMTng) + sidak_jjgKosong($skor_jjgKosong) + sidak_tangkaiP($skor_vcut) + sidak_PengBRD($per_kr);

            $em = 'EM';

            $nama_em = '';

            // dd($key1);
            foreach ($queryAsisten as $ast => $asisten) {
                if ($key1 === $asisten['est'] && $em === $asisten['afd']) {
                    $nama_em = $asisten['nama'];
                }
            }
            $jjg_mth = $tnpBRDx + $krgBRDx + $overripex + $emptyx;

            $skor_jjgMTh = ($jjg_samplex - $abrx != 0) ? round($jjg_mth / ($jjg_samplex - $abrx) * 100, 2) : 0;

            $sidak_buah[$key]['jjg_mantah'] = $jjg_mth;
            $sidak_buah[$key]['persen_jjgmentah'] = $skor_jjgMTh;

            if ($jjg_samplex == 0 && $tnpBRDx == 0 &&   $krgBRDx == 0 && $abrx == 0 && $overripex == 0 && $emptyx == 0 &&  $vcutx == 0 &&  $rdx == 0 && $sum_krx == 0) {
                $sidak_buah[$key]['check_arr'] = 'kosong';
                $sidak_buah[$key]['All_skor'] = 0;
            } else {
                $sidak_buah[$key]['check_arr'] = 'ada';
                $sidak_buah[$key]['All_skor'] = $allSkor;
            }

            $sidak_buah[$key]['Jumlah_janjang'] = $jjg_samplex;
            $sidak_buah[$key]['csrms'] = $csrms;
            $sidak_buah[$key]['blok'] = $dataBLokx;
            $sidak_buah[$key]['EM'] = 'EM';
            $sidak_buah[$key]['Nama_assist'] = $nama_em;
            $sidak_buah[$key]['nama_staff'] = '-';
            $sidak_buah[$key]['tnp_brd'] = $tnpBRDx;
            $sidak_buah[$key]['krg_brd'] = $krgBRDx;
            $sidak_buah[$key]['persenTNP_brd'] = round(($jjg_samplex - $abrx != 0 ? ($tnpBRDx / ($jjg_samplex - $abrx)) * 100 : 0), 2);
            $sidak_buah[$key]['persenKRG_brd'] = round(($jjg_samplex - $abrx != 0 ? ($krgBRDx / ($jjg_samplex - $abrx)) * 100 : 0), 2);
            $sidak_buah[$key]['abnormal_persen'] = round(($jjg_samplex != 0 ? ($abrx / $jjg_samplex) * 100 : 0), 2);
            $sidak_buah[$key]['rd_persen'] = round(($jjg_samplex != 0 ? ($rdx / $jjg_samplex) * 100 : 0), 2);


            $sidak_buah[$key]['total_jjg'] = $tnpBRDx + $krgBRDx;
            $sidak_buah[$key]['persen_totalJjg'] = $skor_total;
            $sidak_buah[$key]['skor_total'] = sidak_brdTotal($skor_total);
            $sidak_buah[$key]['jjg_matang'] = $jjg_samplex - ($tnpBRDx + $krgBRDx + $overripex + $emptyx + $abrx);
            $sidak_buah[$key]['persen_jjgMtang'] = $skor_jjgMSk;
            $sidak_buah[$key]['skor_jjgMatang'] = sidak_matangSKOR($skor_jjgMSk);
            $sidak_buah[$key]['lewat_matang'] = $overripex;
            $sidak_buah[$key]['persen_lwtMtng'] =  $skor_lewatMTng;
            $sidak_buah[$key]['skor_lewatMTng'] = sidak_lwtMatang($skor_lewatMTng);
            $sidak_buah[$key]['janjang_kosong'] = $emptyx;
            $sidak_buah[$key]['persen_kosong'] = $skor_jjgKosong;
            $sidak_buah[$key]['skor_kosong'] = sidak_jjgKosong($skor_jjgKosong);
            $sidak_buah[$key]['vcut'] = $vcutx;
            $sidak_buah[$key]['vcut_persen'] = $skor_vcut;
            $sidak_buah[$key]['vcut_skor'] = sidak_tangkaiP($skor_vcut);
            $sidak_buah[$key]['abnormal'] = $abrx;

            $sidak_buah[$key]['rat_dmg'] = $rdx;

            $sidak_buah[$key]['karung'] = $sum_krx;
            $sidak_buah[$key]['TPH'] = $total_kr;
            $sidak_buah[$key]['persen_krg'] = $per_kr;
            $sidak_buah[$key]['skor_kr'] = sidak_PengBRD($per_kr);
            // $sidak_buah[$key]['All_skor'] = $allSkor;
            $sidak_buah[$key]['kategori'] = sidak_akhir($allSkor);
        }


        // dd($sidak_buah);

        $mutu_buah = array();
        foreach ($queryEste as $key => $value) {
            foreach ($sidak_buah as $key2 => $value2) {
                if ($value['est'] == $key2) {
                    $mutu_buah[$value['wil']][$key2] = array_merge($mutu_buah[$value['wil']][$key2] ?? [], $value2);
                }
            }
        }


        $jjg_samplexy = 0;
        $tnpBRDxy = 0;
        $krgBRDxy = 0;
        $abrxy = 0;
        $overripexy = 0;
        $emptyxy = 0;
        $vcutxy = 0;
        $rdxy = 0;
        $dataBLokxy = 0;
        $sum_krxy = 0;
        foreach ($mutu_buah as $key => $value) {
            $jjg_samplex = 0;
            $tnpBRDx = 0;
            $krgBRDx = 0;
            $jjg_matang = 0;
            $overripex = 0;
            $emptyx = 0;
            $vcutreg = 0;
            $abrx = 0;
            $rat_dmg = 0;
            $dataBLokx = 0;
            $sum_krx = 0;
            foreach ($value as $key1 => $value1) {
                // dd($value1);/
                $jjg_samplex += $value1['Jumlah_janjang'];
                $tnpBRDx += $value1['tnp_brd'];
                $krgBRDx += $value1['krg_brd'];
                $jjg_matang += $value1['jjg_matang'];
                $overripex += $value1['lewat_matang'];
                $emptyx += $value1['janjang_kosong'];
                $vcutreg += $value1['vcut'];
                $abrx += $value1['abnormal'];
                $rat_dmg += $value1['rat_dmg'];
                $dataBLokx += $value1['blok'];
                $sum_krx += $value1['karung'];
            }

            if ($sum_krx != 0) {
                $total_kr = round($sum_krx / $dataBLokx, 3);
            } else {
                $total_kr = 0;
            }
            $per_kr = round($total_kr * 100, 3);
            $skor_total = round(($jjg_samplex - $abrx != 0 ? (($tnpBRDx + $krgBRDx) / ($jjg_samplex - $abrx)) * 100 : 0), 3);

            $skor_jjgMSk = round(($jjg_samplex - $abrx != 0 ? (($jjg_samplex - ($tnpBRDx + $krgBRDx + $overripex + $emptyx + $abrx)) / ($jjg_samplex - $abrx)) * 100 : 0), 3);

            $skor_lewatMTng = round(($jjg_samplex - $abrx != 0 ? ($overripex / ($jjg_samplex - $abrx)) * 100 : 0), 3);

            $skor_jjgKosong = round(($jjg_samplex - $abrx != 0 ? ($emptyx / ($jjg_samplex - $abrx)) * 100 : 0), 3);

            $skor_vcut = round(($jjg_samplex != 0 ? ($vcutreg / $jjg_samplex) * 100 : 0), 3);

            $allSkor = sidak_brdTotal($skor_total) +  sidak_matangSKOR($skor_jjgMSk) +  sidak_lwtMatang($skor_lewatMTng) + sidak_jjgKosong($skor_jjgKosong) + sidak_tangkaiP($skor_vcut) + sidak_PengBRD($per_kr);

            $em = 'GM';

            $nama_em = '';
            $newkey = 'WIL-' . convertToRoman($key);
            // dd($newkey);
            foreach ($queryAsisten as $ast => $asisten) {
                if ($newkey === $asisten['est'] && $em === $asisten['afd']) {
                    $nama_em = $asisten['nama'] ?? '-';
                }
            }
            $jjg_mth = $tnpBRDx + $krgBRDx + $overripex + $emptyx;

            $skor_jjgMTh = ($jjg_samplex - $abrx != 0) ? round($jjg_mth / ($jjg_samplex - $abrx) * 100, 3) : 0;

            $mutu_buah[$key]['jjg_mantah'] = $jjg_mth;
            $mutu_buah[$key]['persen_jjgmentah'] = $skor_jjgMTh;

            if ($jjg_samplex == 0 && $tnpBRDx == 0 &&   $krgBRDx == 0 && $abrx == 0 && $overripex == 0 && $emptyx == 0 &&  $vcutx == 0 &&  $rdx == 0 && $sum_krx == 0) {
                $mutu_buah[$key]['check_arr'] = 'kosong';
                $mutu_buah[$key]['All_skor'] = 0;
            } else {
                $mutu_buah[$key]['check_arr'] = 'ada';
                $mutu_buah[$key]['All_skor'] = $allSkor;
            }

            $mutu_buah[$key]['Jumlah_janjang'] = $jjg_samplex;
            $mutu_buah[$key]['csrms'] = $csrms;
            $mutu_buah[$key]['blok'] = $dataBLokx;
            $mutu_buah[$key]['newkey'] = $newkey;
            $mutu_buah[$key]['EM'] = 'EM';
            $mutu_buah[$key]['Nama_assist'] = $nama_em;
            $mutu_buah[$key]['nama_staff'] = '-';
            $mutu_buah[$key]['tnp_brd'] = $tnpBRDx;
            $mutu_buah[$key]['krg_brd'] = $krgBRDx;
            $mutu_buah[$key]['persenTNP_brd'] = round(($jjg_samplex - $abrx != 0 ? ($tnpBRDx / ($jjg_samplex - $abrx)) * 100 : 0), 3);
            $mutu_buah[$key]['persenKRG_brd'] = round(($jjg_samplex - $abrx != 0 ? ($krgBRDx / ($jjg_samplex - $abrx)) * 100 : 0), 3);
            $mutu_buah[$key]['abnormal_persen'] = round(($jjg_samplex != 0 ? ($abrx / $jjg_samplex) * 100 : 0), 3);
            $mutu_buah[$key]['rd_persen'] = round(($jjg_samplex != 0 ? ($rat_dmg / $jjg_samplex) * 100 : 0), 3);


            $mutu_buah[$key]['total_jjg'] = $tnpBRDx + $krgBRDx;
            $mutu_buah[$key]['persen_totalJjg'] = $skor_total;
            $mutu_buah[$key]['skor_total'] = sidak_brdTotal($skor_total);
            $mutu_buah[$key]['jjg_matang'] = $jjg_samplex - ($tnpBRDx + $krgBRDx + $overripex + $emptyx + $abrx);
            $mutu_buah[$key]['persen_jjgMtang'] = $skor_jjgMSk;
            $mutu_buah[$key]['skor_jjgMatang'] = sidak_matangSKOR($skor_jjgMSk);
            $mutu_buah[$key]['lewat_matang'] = $overripex;
            $mutu_buah[$key]['persen_lwtMtng'] =  $skor_lewatMTng;
            $mutu_buah[$key]['skor_lewatMTng'] = sidak_lwtMatang($skor_lewatMTng);
            $mutu_buah[$key]['janjang_kosong'] = $emptyx;
            $mutu_buah[$key]['persen_kosong'] = $skor_jjgKosong;
            $mutu_buah[$key]['skor_kosong'] = sidak_jjgKosong($skor_jjgKosong);
            $mutu_buah[$key]['vcut'] = $vcutx;
            $mutu_buah[$key]['vcut_persen'] = $skor_vcut;
            $mutu_buah[$key]['vcut_skor'] = sidak_tangkaiP($skor_vcut);
            $mutu_buah[$key]['abnormal'] = $abrx;

            $mutu_buah[$key]['rat_dmg'] = $rat_dmg;

            $mutu_buah[$key]['karung'] = $sum_krx;
            $mutu_buah[$key]['TPH'] = $total_kr;
            $mutu_buah[$key]['persen_krg'] = $per_kr;
            $mutu_buah[$key]['skor_kr'] = sidak_PengBRD($per_kr);
            // $mutu_buah[$key]['All_skor'] = $allSkor;
            $mutu_buah[$key]['kategori'] = sidak_akhir($allSkor);

            $jjg_samplexy += $jjg_samplex;
            $tnpBRDxy += $tnpBRDx;
            $krgBRDxy += $krgBRDx;
            $abrxy += $abrx;
            $overripexy += $overripex;
            $emptyxy += $emptyx;
            $vcutxy += $vcutreg;

            $rdxy += $rat_dmg;

            $dataBLokxy += $dataBLokx;
            $sum_krxy += $sum_krx;
            // $csrmsy += $csfxr;
        }
        if ($sum_krx != 0) {
            $total_kr = round($sum_krxy / $dataBLokxy, 3);
        } else {
            $total_kr = 0;
        }
        $per_kr = round($total_kr * 100, 3);
        $skor_total = round(($jjg_samplexy - $abrxy != 0 ? (($tnpBRDxy + $krgBRDxy) / ($jjg_samplexy - $abrxy)) * 100 : 0), 3);

        $skor_jjgMSk = round(($jjg_samplexy - $abrxy != 0 ? (($jjg_samplexy - ($tnpBRDxy + $krgBRDxy + $overripexy + $emptyxy + $abrxy)) / ($jjg_samplexy - $abrxy)) * 100 : 0), 3);

        $skor_lewatMTng = round(($jjg_samplexy - $abrxy != 0 ? ($overripexy / ($jjg_samplexy - $abrxy)) * 100 : 0), 3);

        $skor_jjgKosong = round(($jjg_samplexy - $abrxy != 0 ? ($emptyx / ($jjg_samplexy - $abrxy)) * 100 : 0), 3);

        $skor_vcut = round(($jjg_samplexy != 0 ? ($vcutxy / $jjg_samplexy) * 100 : 0), 3);

        $allSkor = sidak_brdTotal($skor_total) +  sidak_matangSKOR($skor_jjgMSk) +  sidak_lwtMatang($skor_lewatMTng) + sidak_jjgKosong($skor_jjgKosong) + sidak_tangkaiP($skor_vcut) + sidak_PengBRD($per_kr);

        $em = 'RH';

        $nama_em = '';
        $newkey = 'REG-' . convertToRoman($regional);
        // dd($newkey);
        foreach ($queryAsisten as $ast => $asisten) {
            if ($newkey === $asisten['est'] && $em === $asisten['afd']) {
                $nama_em = $asisten['nama'] ?? '-';
            }
        }
        $jjg_mth = $tnpBRDxy + $krgBRDxy + $overripexy + $emptyx;

        $skor_jjgMTh = ($jjg_samplexy - $abrxy != 0) ? round($jjg_mth / ($jjg_samplexy - $abrxy) * 100, 3) : 0;

        $mutu_buah['jjg_mantah'] = $jjg_mth;
        $mutu_buah['persen_jjgmentah'] = $skor_jjgMTh;

        if ($jjg_samplexy == 0 && $tnpBRDxy == 0 &&   $krgBRDxy == 0 && $abrxy == 0 && $overripexy == 0 && $emptyx == 0 &&  $vcutx == 0 &&  $rdx == 0 && $sum_krx == 0) {
            $mutu_buah['check_arr'] = 'kosong';
            $mutu_buah['All_skor'] = 0;
        } else {
            $mutu_buah['check_arr'] = 'ada';
            $mutu_buah['All_skor'] = $allSkor;
        }
        $mutu_buah['Jumlah_janjang'] = $jjg_samplexy;
        $mutu_buah['csrms'] = $csrms;
        $mutu_buah['blok'] = $dataBLokx;
        $mutu_buah['newkey'] = $newkey;
        $mutu_buah['EM'] = 'EM';
        $mutu_buah['Nama_assist'] = $nama_em;
        $mutu_buah['nama_staff'] = '-';
        $mutu_buah['tnp_brd'] = $tnpBRDxy;
        $mutu_buah['krg_brd'] = $krgBRDxy;
        $mutu_buah['persenTNP_brd'] = round(($jjg_samplexy - $abrxy != 0 ? ($tnpBRDxy / ($jjg_samplexy - $abrxy)) * 100 : 0), 3);
        $mutu_buah['persenKRG_brd'] = round(($jjg_samplexy - $abrxy != 0 ? ($krgBRDxy / ($jjg_samplexy - $abrxy)) * 100 : 0), 3);
        $mutu_buah['abnormal_persen'] = round(($jjg_samplexy != 0 ? ($abrxy / $jjg_samplexy) * 100 : 0), 3);
        $mutu_buah['rd_persen'] = round(($jjg_samplexy != 0 ? ($rdxy / $jjg_samplexy) * 100 : 0), 3);
        $mutu_buah['total_jjg'] = $tnpBRDxy + $krgBRDxy;
        $mutu_buah['persen_totalJjg'] = $skor_total;
        $mutu_buah['skor_total'] = sidak_brdTotal($skor_total);
        $mutu_buah['jjg_matang'] = $jjg_samplexy - ($tnpBRDxy + $krgBRDxy + $overripexy + $emptyx + $abrxy);
        $mutu_buah['persen_jjgMtang'] = $skor_jjgMSk;
        $mutu_buah['skor_jjgMatang'] = sidak_matangSKOR($skor_jjgMSk);
        $mutu_buah['lewat_matang'] = $overripexy;
        $mutu_buah['persen_lwtMtng'] =  $skor_lewatMTng;
        $mutu_buah['skor_lewatMTng'] = sidak_lwtMatang($skor_lewatMTng);
        $mutu_buah['janjang_kosong'] = $emptyxy;
        $mutu_buah['persen_kosong'] = $skor_jjgKosong;
        $mutu_buah['skor_kosong'] = sidak_jjgKosong($skor_jjgKosong);
        $mutu_buah['vcut'] = $vcutxy;
        $mutu_buah['vcut_persen'] = $skor_vcut;
        $mutu_buah['vcut_skor'] = sidak_tangkaiP($skor_vcut);
        $mutu_buah['abnormal'] = $abrxy;
        $mutu_buah['rat_dmg'] = $rdxy;
        $mutu_buah['karung'] = $sum_krxy;
        $mutu_buah['TPH'] = $total_kr;
        $mutu_buah['persen_krg'] = $per_kr;
        $mutu_buah['skor_kr'] = sidak_PengBRD($per_kr);
        $mutu_buah['kategori'] = sidak_akhir($allSkor);


        // dd($mutu_buah);


        $data = [];
        foreach ($mutu_buah as $key => $value)  if (is_array($value)) {
            foreach ($value as $key1 => $value1) if (is_array($value1)) {
                // dd($value1);
                foreach ($value1 as $key2 => $value2) if (is_array($value2)) {
                    foreach ($queryAsisten as $keyx => $valuex) if ($valuex['est'] === $key1 && $valuex['afd'] === $key2) {
                        $data[$key][$key1][$key2]['nama'] = $valuex['nama'] ?? '-';
                        break;
                    }
                    // $data[$key][$key1][$key2]['nama'] = 'nama';
                    $data[$key][$key1][$key2]['total_score'] = $value2['All_skor'];
                    $data[$key][$key1][$key2]['est'] = $key1;
                    $data[$key][$key1][$key2]['afd'] = $key2;
                    $data[$key][$key1][$key2]['bgcolor'] = 'white';

                    // $totale = $totalest;
                }
                $nama = '-';
                foreach ($queryAsisten as $keyx => $valuex) if ($valuex['est'] === $key1 && $valuex['afd'] === 'EM') {
                    $nama = $valuex['nama'] ?? '-';
                    break;
                }
                $estate = [
                    'total_score' => $value1['All_skor'],
                    'est' => $key1,
                    'afd' => '-',
                    'nama' => $nama,
                    'bgcolor' => '#a0978d'
                ];

                $data[$key][$key1]['est'] = $estate;
            }
            // dd($value);
            $data[$key]['A']['EST']  = [
                'total_score' => $value['All_skor'],
                'est' => $value['newkey'],
                'afd' => '-',
                'nama' => $value['Nama_assist'],
                'bgcolor' => '#FFF176'
            ];
        }
        // dd($data);
        // dd($data, $mutu_buah);

        $rhdata =  [
            'total_score' => $mutu_buah['All_skor'] ?? 0,
            'est' => $mutu_buah['newkey'],
            'afd' => '-',
            'nama' => $mutu_buah['Nama_assist'],
            'bgcolor' => '#FFF176'
        ];

        if ($regional == 1) {
            $defPerbulanWilmua = array();

            foreach ($muaest as $key2 => $value2) {
                foreach ($queryAfd as $key3 => $value3) {
                    if ($value2['est'] == $value3['est']) {
                        $defPerbulanWilmua[$value2['est']][$value3['est']] = 0;
                    }
                }
            }
            foreach ($defPerbulanWilmua as $estateKey => $afdelingArray) {
                foreach ($afdelingArray as $afdelingKey => $afdelingValue) {
                    if (isset($databulananBuah[$estateKey]) && isset($databulananBuah[$estateKey][$afdelingKey])) {
                        $defPerbulanWilmua[$estateKey][$afdelingKey] = $databulananBuah[$estateKey][$afdelingKey];
                    }
                }
            }

            $sidakbuahmuah = array();
            // dd($defPerbulanWil);
            $jjg_samplexy = 0;
            $tnpBRDxy = 0;
            $krgBRDxy = 0;
            $abrxy = 0;
            $overripexy = 0;
            $emptyxy = 0;
            $vcutxy = 0;
            $rdxy = 0;
            $dataBLokxy = 0;
            $sum_krxy = 0;
            $csrmsy = 0;
            foreach ($defPerbulanWilmua as $key => $value) {
                $jjg_samplex = 0;
                $tnpBRDx = 0;
                $krgBRDx = 0;
                $abrx = 0;
                $overripex = 0;
                $emptyx = 0;
                $vcutx = 0;
                $rdx = 0;
                $dataBLokx = 0;
                $sum_krx = 0;
                $csrms = 0;
                foreach ($value as $key1 => $value1) {
                    if (is_array($value1)) {
                        $jjg_sample = 0;
                        $tnpBRD = 0;
                        $krgBRD = 0;
                        $abr = 0;
                        $skor_total = 0;
                        $overripe = 0;
                        $empty = 0;
                        $vcut = 0;
                        $rd = 0;
                        $sum_kr = 0;
                        $allSkor = 0;
                        $combination_counts = array();
                        $newblok = 0;
                        $csfxr = count($value1);
                        foreach ($value1 as $key2 => $value2) {
                            $combination = $value2['blok'] . ' ' . $value2['estate'] . ' ' . $value2['afdeling'] . ' ' . $value2['tph_baris'];
                            if (!isset($combination_counts[$combination])) {
                                $combination_counts[$combination] = 0;
                            }
                            $newblok = count($value1);
                            $jjg_sample += $value2['jumlah_jjg'];
                            $tnpBRD += $value2['bmt'];
                            $krgBRD += $value2['bmk'];
                            $abr += $value2['abnormal'];
                            $overripe += $value2['overripe'];
                            $empty += $value2['empty_bunch'];
                            $vcut += $value2['vcut'];
                            $rd += $value2['rd'];
                            $sum_kr += $value2['alas_br'];
                        }
                        // $dataBLok = count($combination_counts);
                        $dataBLok = $newblok;
                        if ($sum_kr != 0) {
                            $total_kr = round($sum_kr / $dataBLok, 2);
                        } else {
                            $total_kr = 0;
                        }
                        $per_kr = round($total_kr * 100, 2);
                        $skor_total = round((($tnpBRD + $krgBRD) / ($jjg_sample - $abr)) * 100, 2);
                        $skor_jjgMSk = round(($jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr)) / ($jjg_sample - $abr) * 100, 2);
                        $skor_lewatMTng =  round(($overripe / ($jjg_sample - $abr)) * 100, 2);
                        $skor_jjgKosong =  round(($empty / ($jjg_sample - $abr)) * 100, 2);
                        $skor_vcut =   round(($vcut / $jjg_sample) * 100, 2);
                        $allSkor = sidak_brdTotal($skor_total) +  sidak_matangSKOR($skor_jjgMSk) +  sidak_lwtMatang($skor_lewatMTng) + sidak_jjgKosong($skor_jjgKosong) + sidak_tangkaiP($skor_vcut) + sidak_PengBRD($per_kr);

                        $sidakbuahmuah[$key][$key1]['Jumlah_janjang'] = $jjg_sample;
                        $sidakbuahmuah[$key][$key1]['blok'] = $dataBLok;
                        $sidakbuahmuah[$key][$key1]['est'] = $key;
                        $sidakbuahmuah[$key][$key1]['afd'] = $key1;
                        $sidakbuahmuah[$key][$key1]['nama_staff'] = '-';
                        $sidakbuahmuah[$key][$key1]['tnp_brd'] = $tnpBRD;
                        $sidakbuahmuah[$key][$key1]['krg_brd'] = $krgBRD;
                        $sidakbuahmuah[$key][$key1]['persenTNP_brd'] = round(($tnpBRD / ($jjg_sample - $abr)) * 100, 2);
                        $sidakbuahmuah[$key][$key1]['persenKRG_brd'] = round(($krgBRD / ($jjg_sample - $abr)) * 100, 2);
                        $sidakbuahmuah[$key][$key1]['total_jjg'] = $tnpBRD + $krgBRD;
                        $sidakbuahmuah[$key][$key1]['persen_totalJjg'] = $skor_total;
                        $sidakbuahmuah[$key][$key1]['skor_total'] = sidak_brdTotal($skor_total);
                        $sidakbuahmuah[$key][$key1]['jjg_matang'] = $jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr);
                        $sidakbuahmuah[$key][$key1]['persen_jjgMtang'] = $skor_jjgMSk;
                        $sidakbuahmuah[$key][$key1]['skor_jjgMatang'] = sidak_matangSKOR($skor_jjgMSk);
                        $sidakbuahmuah[$key][$key1]['lewat_matang'] = $overripe;
                        $sidakbuahmuah[$key][$key1]['persen_lwtMtng'] =  $skor_lewatMTng;
                        $sidakbuahmuah[$key][$key1]['skor_lewatMTng'] = sidak_lwtMatang($skor_lewatMTng);
                        $sidakbuahmuah[$key][$key1]['janjang_kosong'] = $empty;
                        $sidakbuahmuah[$key][$key1]['persen_kosong'] = $skor_jjgKosong;
                        $sidakbuahmuah[$key][$key1]['skor_kosong'] = sidak_jjgKosong($skor_jjgKosong);
                        $sidakbuahmuah[$key][$key1]['vcut'] = $vcut;
                        $sidakbuahmuah[$key][$key1]['karung'] = $sum_kr;
                        $sidakbuahmuah[$key][$key1]['vcut_persen'] = $skor_vcut;
                        $sidakbuahmuah[$key][$key1]['vcut_skor'] = sidak_tangkaiP($skor_vcut);
                        $sidakbuahmuah[$key][$key1]['abnormal'] = $abr;
                        $sidakbuahmuah[$key][$key1]['abnormal_persen'] = round(($abr / $jjg_sample) * 100, 2);
                        $sidakbuahmuah[$key][$key1]['rat_dmg'] = $rd;
                        $sidakbuahmuah[$key][$key1]['rd_persen'] = round(($rd / $jjg_sample) * 100, 2);
                        $sidakbuahmuah[$key][$key1]['TPH'] = $total_kr;
                        $sidakbuahmuah[$key][$key1]['persen_krg'] = $per_kr;
                        $sidakbuahmuah[$key][$key1]['skor_kr'] = sidak_PengBRD($per_kr);
                        $sidakbuahmuah[$key][$key1]['All_skor'] = $allSkor;
                        $sidakbuahmuah[$key][$key1]['csfxr'] = $csfxr;
                        $sidakbuahmuah[$key][$key1]['kategori'] = sidak_akhir($allSkor);

                        foreach ($queryAsisten as $ast => $asisten) {
                            if ($key === $asisten['est'] && $key1 === $asisten['afd']) {
                                $sidakbuahmuah[$key][$key1]['nama_asisten'] = $asisten['nama'];
                            }
                        }
                        $jjg_samplex += $jjg_sample;
                        $tnpBRDx += $tnpBRD;
                        $krgBRDx += $krgBRD;
                        $abrx += $abr;
                        $overripex += $overripe;
                        $emptyx += $empty;
                        $vcutx += $vcut;

                        $rdx += $rd;

                        $dataBLokx += $newblok;
                        $sum_krx += $sum_kr;
                        $csrms += $csfxr;
                    } else {

                        $sidakbuahmuah[$key][$key1]['Jumlah_janjang'] = 0;
                        $sidakbuahmuah[$key][$key1]['blok'] = 0;
                        $sidakbuahmuah[$key][$key1]['est'] = $key;
                        $sidakbuahmuah[$key][$key1]['afd'] = $key1;
                        $sidakbuahmuah[$key][$key1]['nama_staff'] = '-';
                        $sidakbuahmuah[$key][$key1]['tnp_brd'] = 0;
                        $sidakbuahmuah[$key][$key1]['krg_brd'] = 0;
                        $sidakbuahmuah[$key][$key1]['persenTNP_brd'] = 0;
                        $sidakbuahmuah[$key][$key1]['persenKRG_brd'] = 0;
                        $sidakbuahmuah[$key][$key1]['total_jjg'] = 0;
                        $sidakbuahmuah[$key][$key1]['persen_totalJjg'] = 0;
                        $sidakbuahmuah[$key][$key1]['skor_total'] = 0;
                        $sidakbuahmuah[$key][$key1]['jjg_matang'] = 0;
                        $sidakbuahmuah[$key][$key1]['persen_jjgMtang'] = 0;
                        $sidakbuahmuah[$key][$key1]['skor_jjgMatang'] = 0;
                        $sidakbuahmuah[$key][$key1]['lewat_matang'] = 0;
                        $sidakbuahmuah[$key][$key1]['persen_lwtMtng'] =  0;
                        $sidakbuahmuah[$key][$key1]['skor_lewatMTng'] = 0;
                        $sidakbuahmuah[$key][$key1]['janjang_kosong'] = 0;
                        $sidakbuahmuah[$key][$key1]['persen_kosong'] = 0;
                        $sidakbuahmuah[$key][$key1]['skor_kosong'] = 0;
                        $sidakbuahmuah[$key][$key1]['vcut'] = 0;
                        $sidakbuahmuah[$key][$key1]['karung'] = 0;
                        $sidakbuahmuah[$key][$key1]['vcut_persen'] = 0;
                        $sidakbuahmuah[$key][$key1]['vcut_skor'] = 0;
                        $sidakbuahmuah[$key][$key1]['abnormal'] = 0;
                        $sidakbuahmuah[$key][$key1]['abnormal_persen'] = 0;
                        $sidakbuahmuah[$key][$key1]['rat_dmg'] = 0;
                        $sidakbuahmuah[$key][$key1]['rd_persen'] = 0;
                        $sidakbuahmuah[$key][$key1]['TPH'] = 0;
                        $sidakbuahmuah[$key][$key1]['persen_krg'] = 0;
                        $sidakbuahmuah[$key][$key1]['skor_kr'] = 0;
                        $sidakbuahmuah[$key][$key1]['All_skor'] = 0;
                        $sidakbuahmuah[$key][$key1]['kategori'] = 0;
                        $sidakbuahmuah[$key][$key1]['csfxr'] = 0;
                        foreach ($queryAsisten as $ast => $asisten) {
                            if ($key === $asisten['est'] && $key1 === $asisten['afd']) {
                                $sidakbuahmuah[$key][$key1]['nama_asisten'] = $asisten['nama'];
                            }
                        }
                    }
                }
                if ($sum_krx != 0) {
                    $total_kr = round($sum_krx / $dataBLokx, 2);
                } else {
                    $total_kr = 0;
                }
                $per_kr = round($total_kr * 100, 2);
                $skor_total = round(($jjg_samplex - $abrx != 0 ? (($tnpBRDx + $krgBRDx) / ($jjg_samplex - $abrx)) * 100 : 0), 2);

                $skor_jjgMSk = round(($jjg_samplex - $abrx != 0 ? (($jjg_samplex - ($tnpBRDx + $krgBRDx + $overripex + $emptyx + $abrx)) / ($jjg_samplex - $abrx)) * 100 : 0), 2);

                $skor_lewatMTng = round(($jjg_samplex - $abrx != 0 ? ($overripex / ($jjg_samplex - $abrx)) * 100 : 0), 2);

                $skor_jjgKosong = round(($jjg_samplex - $abrx != 0 ? ($emptyx / ($jjg_samplex - $abrx)) * 100 : 0), 2);

                $skor_vcut = round(($jjg_samplex != 0 ? ($vcutx / $jjg_samplex) * 100 : 0), 2);

                $allSkor = sidak_brdTotal($skor_total) +  sidak_matangSKOR($skor_jjgMSk) +  sidak_lwtMatang($skor_lewatMTng) + sidak_jjgKosong($skor_jjgKosong) + sidak_tangkaiP($skor_vcut) + sidak_PengBRD($per_kr);

                $em = 'EM';

                $nama_em = '';

                // dd($key1);
                foreach ($queryAsisten as $ast => $asisten) {
                    if ($key1 === $asisten['est'] && $em === $asisten['afd']) {
                        $nama_em = $asisten['nama'];
                    }
                }
                $jjg_mth = $tnpBRDx + $krgBRDx + $overripex + $emptyx;

                $skor_jjgMTh = ($jjg_samplex - $abrx != 0) ? round($jjg_mth / ($jjg_samplex - $abrx) * 100, 2) : 0;

                $sidakbuahmuah[$key]['jjg_mantah'] = $jjg_mth;
                $sidakbuahmuah[$key]['persen_jjgmentah'] = $skor_jjgMTh;

                if ($csrms == 0) {
                    $sidakbuahmuah[$key]['check_arr'] = 'kosong';
                    $sidakbuahmuah[$key]['All_skor'] = '-';
                } else {
                    $sidakbuahmuah[$key]['check_arr'] = 'ada';
                    $sidakbuahmuah[$key]['All_skor'] = $allSkor;
                }

                $sidakbuahmuah[$key]['Jumlah_janjang'] = $jjg_samplex;
                $sidakbuahmuah[$key]['csrms'] = $csrms;
                $sidakbuahmuah[$key]['blok'] = $dataBLokx;
                $sidakbuahmuah[$key]['EM'] = 'EM';
                $sidakbuahmuah[$key]['Nama_assist'] = $nama_em;
                $sidakbuahmuah[$key]['nama_staff'] = '-';
                $sidakbuahmuah[$key]['tnp_brd'] = $tnpBRDx;
                $sidakbuahmuah[$key]['krg_brd'] = $krgBRDx;
                $sidakbuahmuah[$key]['persenTNP_brd'] = round(($jjg_samplex - $abrx != 0 ? ($tnpBRDx / ($jjg_samplex - $abrx)) * 100 : 0), 2);
                $sidakbuahmuah[$key]['persenKRG_brd'] = round(($jjg_samplex - $abrx != 0 ? ($krgBRDx / ($jjg_samplex - $abrx)) * 100 : 0), 2);
                $sidakbuahmuah[$key]['abnormal_persen'] = round(($jjg_samplex != 0 ? ($abrx / $jjg_samplex) * 100 : 0), 2);
                $sidakbuahmuah[$key]['rd_persen'] = round(($jjg_samplex != 0 ? ($rdx / $jjg_samplex) * 100 : 0), 2);


                $sidakbuahmuah[$key]['total_jjg'] = $tnpBRDx + $krgBRDx;
                $sidakbuahmuah[$key]['persen_totalJjg'] = $skor_total;
                $sidakbuahmuah[$key]['skor_total'] = sidak_brdTotal($skor_total);
                $sidakbuahmuah[$key]['jjg_matang'] = $jjg_samplex - ($tnpBRDx + $krgBRDx + $overripex + $emptyx + $abrx);
                $sidakbuahmuah[$key]['persen_jjgMtang'] = $skor_jjgMSk;
                $sidakbuahmuah[$key]['skor_jjgMatang'] = sidak_matangSKOR($skor_jjgMSk);
                $sidakbuahmuah[$key]['lewat_matang'] = $overripex;
                $sidakbuahmuah[$key]['persen_lwtMtng'] =  $skor_lewatMTng;
                $sidakbuahmuah[$key]['skor_lewatMTng'] = sidak_lwtMatang($skor_lewatMTng);
                $sidakbuahmuah[$key]['janjang_kosong'] = $emptyx;
                $sidakbuahmuah[$key]['persen_kosong'] = $skor_jjgKosong;
                $sidakbuahmuah[$key]['skor_kosong'] = sidak_jjgKosong($skor_jjgKosong);
                $sidakbuahmuah[$key]['vcut'] = $vcutx;
                $sidakbuahmuah[$key]['vcut_persen'] = $skor_vcut;
                $sidakbuahmuah[$key]['vcut_skor'] = sidak_tangkaiP($skor_vcut);
                $sidakbuahmuah[$key]['abnormal'] = $abrx;

                $sidakbuahmuah[$key]['rat_dmg'] = $rdx;

                $sidakbuahmuah[$key]['karung'] = $sum_krx;
                $sidakbuahmuah[$key]['TPH'] = $total_kr;
                $sidakbuahmuah[$key]['persen_krg'] = $per_kr;
                $sidakbuahmuah[$key]['skor_kr'] = sidak_PengBRD($per_kr);
                // $sidakbuahmuah[$key]['All_skor'] = $allSkor;
                $sidakbuahmuah[$key]['kategori'] = sidak_akhir($allSkor);


                $jjg_samplexy += $jjg_samplex;
                $tnpBRDxy += $tnpBRDx;
                $krgBRDxy += $krgBRDx;
                $abrxy += $abrx;
                $overripexy += $overripex;
                $emptyxy += $emptyx;
                $vcutxy += $vcutx;
                $rdxy += $rdx;
                $dataBLokxy += $dataBLokx;
                $sum_krxy += $sum_krx;
                $csrmsy += $csrms;
            }

            if ($sum_krxy != 0) {
                $total_kr = round($sum_krxy / $dataBLokxy, 2);
            } else {
                $total_kr = 0;
            }
            $per_kr = round($total_kr * 100, 2);
            $skor_total = round(($jjg_samplexy - $abrxy != 0 ? (($tnpBRDxy + $krgBRDxy) / ($jjg_samplexy - $abrxy)) * 100 : 0), 2);

            $skor_jjgMSk = round(($jjg_samplexy - $abrxy != 0 ? (($jjg_samplexy - ($tnpBRDxy + $krgBRDxy + $overripexy + $emptyxy + $abrxy)) / ($jjg_samplexy - $abrxy)) * 100 : 0), 2);

            $skor_lewatMTng = round(($jjg_samplexy - $abrxy != 0 ? ($overripexy / ($jjg_samplexy - $abrxy)) * 100 : 0), 2);

            $skor_jjgKosong = round(($jjg_samplexy - $abrxy != 0 ? ($emptyxy / ($jjg_samplexy - $abrxy)) * 100 : 0), 2);

            $skor_vcut = round(($jjg_samplexy != 0 ? ($vcutxy / $jjg_samplexy) * 100 : 0), 2);

            $allSkor = sidak_brdTotal($skor_total) +  sidak_matangSKOR($skor_jjgMSk) +  sidak_lwtMatang($skor_lewatMTng) + sidak_jjgKosong($skor_jjgKosong) + sidak_tangkaiP($skor_vcut) + sidak_PengBRD($per_kr);

            $em = 'EM';

            $nama_em = '';

            // dd($key1);
            foreach ($queryAsisten as $ast => $asisten) {
                if ($key1 === $asisten['est'] && $em === $asisten['afd']) {
                    $nama_em = $asisten['nama'];
                }
            }
            $jjg_mthxy = $tnpBRDxy + $krgBRDxy + $overripexy + $emptyxy;

            $skor_jjgMTh = ($jjg_samplexy - $abrxy != 0) ? round($jjg_mth / ($jjg_samplexy - $abrxy) * 100, 2) : 0;
            if ($csrmsy == 0) {
                $check_arr = 'kosong';
                $All_skor = '-';
            } else {
                $check_arr = 'ada';
                $All_skor = $allSkor;
            };
            $sidakbuahmuah['PT.MUA'] = [
                'jjg_mantah' => $jjg_mthxy,
                'persen_jjgmentah' => $skor_jjgMTh,
                'check_arr' => $check_arr,
                'All_skor' => $All_skor,
                'Jumlah_janjang' => $jjg_samplexy,
                'csrms' => $csrmsy,
                'blok' => $dataBLokxy,
                'EM' => 'EM',
                'Nama_assist' => $nama_em,
                'nama_staff' => '-',
                'tnp_brd' => $tnpBRDxy,
                'krg_brd' => $krgBRDxy,
                'persenTNP_brd' => round(($jjg_samplexy - $abrxy != 0 ? ($tnpBRDxy / ($jjg_samplexy - $abrxy)) * 100 : 0), 2),
                'persenKRG_brd' => round(($jjg_samplexy - $abrxy != 0 ? ($krgBRDxy / ($jjg_samplexy - $abrxy)) * 100 : 0), 2),
                'abnormal_persen' => round(($jjg_samplexy != 0 ? ($abrxy / $jjg_samplexy) * 100 : 0), 2),
                'rd_persen' => round(($jjg_samplexy != 0 ? ($rdxy / $jjg_samplexy) * 100 : 0), 2),
                'total_jjg' => $tnpBRDxy + $krgBRDxy,
                'persen_totalJjg' => $skor_total,
                'skor_total' => sidak_brdTotal($skor_total),
                'jjg_matang' => $jjg_samplexy - ($tnpBRDxy + $krgBRDxy + $overripexy + $emptyxy + $abrxy),
                'persen_jjgMtang' => $skor_jjgMSk,
                'skor_jjgMatang' => sidak_matangSKOR($skor_jjgMSk),
                'lewat_matang' => $overripexy,
                'persen_lwtMtng' =>  $skor_lewatMTng,
                'skor_lewatMTng' => sidak_lwtMatang($skor_lewatMTng),
                'janjang_kosong' => $emptyxy,
                'persen_kosong' => $skor_jjgKosong,
                'skor_kosong' => sidak_jjgKosong($skor_jjgKosong),
                'vcut' => $vcutxy,
                'vcut_persen' => $skor_vcut,
                'vcut_skor' => sidak_tangkaiP($skor_vcut),
                'abnormal' => $abrxy,
                'rat_dmg' => $rdxy,
                'karung' => $sum_krxy,
                'TPH' => $total_kr,
                'persen_krg' => $per_kr,
                'skor_kr' => sidak_PengBRD($per_kr),
                'kategori' => sidak_akhir($allSkor),
            ];
        } else {
            $sidakbuahmuah = [];
        }

        return [
            'listregion' =>  $estev2,
            'mutu_buah' => $data,
            'rekapmua' => $sidakbuahmuah,
            'rhdata' => $rhdata,
        ];
    }
}

//helper halaman sidak mutu buah pertahun
if (!function_exists('isPointInPolygon')) {
    function isPointInPolygon($point, $polygon)
    {
        $splPoint = explode(',', $point);
        $y = (float)$splPoint[0];
        $x = (float)$splPoint[1];

        $vertices = array_map(function ($vertex) {
            $coords = explode(',', $vertex);
            return [(float)$coords[1], (float)$coords[0]];
        }, explode('$', $polygon));

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
}
