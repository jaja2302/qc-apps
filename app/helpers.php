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
