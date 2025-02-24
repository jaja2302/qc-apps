<?php
// Important functions

use App\Models\Asistenqc;
use App\Models\BlokMatch;
use App\Models\Departement;
use App\Models\DepartementUser;
use App\Models\Gradingmill;
use App\Models\Listmill;
use App\Models\mutu_ancak;
use App\Models\Pengguna;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;

use function App\Http\Controllers\grupwaktu;

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
        // Check if the input is a string and return it as is
        if (is_string($number)) {
            // dd($number);
            return $number;
        }
        // dd('oke');
        // Ensure the number is an integer
        $number = (int)$number;

        // dd($number);
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
        // dd($result);
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

        // Check if the number is less than 9 digits or more than 14 digits
        if (strlen($number) < 9 || strlen($number) > 14) {
            return null;
        }

        // Check for valid Indonesian prefixes
        $validPrefixes = ['0811', '0812', '0813', '0814', '0815', '0816', '0817', '0818', '0819', '0821', '0822', '0823', '0851', '0852', '0853', '0855', '0856', '0857', '0858', '0859', '0877', '0878', '0881', '0882', '0883', '0884', '0885', '0886', '0887', '0888', '0889'];

        if (in_array(substr($number, 0, 4), $validPrefixes)) {
            return '62' . substr($number, 1);
        } elseif (substr($number, 0, 2) === '62' && in_array('0' . substr($number, 2, 3), $validPrefixes)) {
            return $number;
        } else {
            // If it doesn't match Indonesian format, return null
            return null;
        }
    }
}
if (!function_exists('type_of_user')) {
    function type_of_user()
    {
        $user = Pengguna::where('user_id', auth()->user()->user_id)
            ->with('Departement', 'Jabatan')
            ->first();

        $departmentIds = $user->Departement->pluck('id')->toArray();

        if (in_array('43', $departmentIds)) {
            return count($departmentIds) > 1 ? 'QC_Mill' : 'QC';
        }
        if (in_array('268', $departmentIds)) {
            return 'QC_Mill';
        }

        return 'Not_QC';
    }
}
// Manager, Askep, and Asisten can edit with QC department
if (!function_exists('can_edit')) {
    function can_edit()
    {
        $user = Pengguna::where('user_id', auth()->user()->user_id)
            ->with('Departement', 'Jabatan')
            ->first();

        $allowedPositions = ['6', '7', '15', '5', '45', '47'];
        $allowedDepartments = GetDepartementFamily(43);
        // dd($allowedDepartments);
        $allowedOldPositions = ['Askep', 'Manager', 'Asisten', 'Askep/Asisten'];
        $allowedOldDepartments = ['QC', 'Quality Control'];

        // Check new department and position
        if ($user->id_jabatan !== null) {
            return in_array($user->id_jabatan, $allowedPositions) &&
                !empty(array_intersect($user->Departement->pluck('id')->toArray(), $allowedDepartments));
        }

        // Check old department and position
        return in_array($user->jabatan, $allowedOldPositions) &&
            in_array($user->departemen, $allowedOldDepartments);
    }
}

if (!function_exists('user_qc')) {
    function user_qc()
    {
        $userDepartments = auth()->user()->Departement->pluck('nama')->toArray();
        $allowedDepartments = ['QC', 'Quality Control', 'QC Mill', 'QC Reguler'];
        return !empty(array_intersect($userDepartments, $allowedDepartments));
    }
}

if (!function_exists('can_edit_mananger_askep')) {
    function can_edit_mananger_askep()
    {
        $user = auth()->user();
        $newJabatan = $user->id_jabatan;
        $oldJabatan = $user->jabatan;
        $allowedJabatan = ['6', '15', '5'];


        // Check new department and position
        if ($newJabatan !== null && in_array($newJabatan, $allowedJabatan)) {
            return true;
        }

        // Check old department and position
        $allowedOldJabatan = ['Askep', 'Manager', 'Askep/Asisten'];


        if ($newJabatan === null &&  in_array($oldJabatan, $allowedOldJabatan)) {
            return true;
        }

        return false;
    }
}
// cuma manager/askep/asisten/asisten afdeling  bisa edit tanpa depertement
if (!function_exists('can_edit_all_atasan')) {
    function can_edit_all_atasan()
    {
        $user = auth()->user();
        $newJabatan = $user->id_jabatan;
        $oldJabatan = $user->jabatan;
        $allowedJabatan = ['6', '15', '5', '3', '7'];


        // Check new department and position
        if ($newJabatan !== null && in_array($newJabatan, $allowedJabatan)) {
            return true;
        }

        // Check old department and position
        $allowedOldJabatan = ['Askep', 'Manager', 'Askep/Asisten', 'Asisten Afdeling'];


        if ($newJabatan === null &&  in_array($oldJabatan, $allowedOldJabatan)) {
            return true;
        }

        return false;
    }
}
if (!function_exists('can_edit_asisten')) {
    function can_edit_asisten()
    {
        $user = auth()->user();
        $newJabatan = $user->id_jabatan;
        $oldJabatan = $user->jabatan;
        $allowedJabatan = ['7', '3'];


        // Check new department and position
        if ($newJabatan !== null && in_array($newJabatan, $allowedJabatan)) {
            return true;
        }

        // Check old department and position
        $allowedOldJabatan = ['Asisten', 'Askep/Asisten', 'Asisten Afdeling'];


        if ($newJabatan === null &&  in_array($oldJabatan, $allowedOldJabatan)) {
            return true;
        }

        return false;
    }
}

if (!function_exists('can_edit_based_departement')) {
    function can_edit_based_departement($mill)
    {
        $user_departement = auth()->user()->Departement->pluck('nama')->toArray();
        // dd($user_departement, $mill);
        if (in_array($mill, $user_departement)) {
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

//helper pertahun reg->bulan->wil->estate->afdeling->value qc inspeksi
if (!function_exists('rekap_pertahun')) {
    function rekap_pertahun($year, $RegData)
    {

        // dd($year, $RegData);
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
                            $rekap[$key][$key1][$key2][$key3][$key4]['namaGM'] =   get_nama_asisten($key3, $key4);
                            $rekap[$key][$key1][$key2][$key3][$key4]['ha_samplecak'] = $jum_ha;
                            $rekap[$key][$key1][$key2][$key3][$key4]['jumlah_panencak'] = $totalPanen;
                            $rekap[$key][$key1][$key2][$key3][$key4]['akp_rlcak'] = $akp;
                            $rekap[$key][$key1][$key2][$key3][$key4]['pcak'] = $totalP_panen;
                            $rekap[$key][$key1][$key2][$key3][$key4]['kcak'] = $totalK_panen;
                            $rekap[$key][$key1][$key2][$key3][$key4]['tglcak'] = $totalPTgl_panen;
                            $rekap[$key][$key1][$key2][$key3][$key4]['total_brdcak'] = $skor_bTinggal;
                            $rekap[$key][$key1][$key2][$key3][$key4]['brd_jjgcak'] = $brdPerjjg;
                            // data untuk buah tinggal
                            $rekap[$key][$key1][$key2][$key3][$key4]['bhts_scak'] = $totalbhts_panen;
                            $rekap[$key][$key1][$key2][$key3][$key4]['bhtm1cak'] = $totalbhtm1_panen;
                            $rekap[$key][$key1][$key2][$key3][$key4]['bhtm2cak'] = $totalbhtm2_panen;
                            $rekap[$key][$key1][$key2][$key3][$key4]['bhtm3cak'] = $totalbhtm3_oanen;
                            $rekap[$key][$key1][$key2][$key3][$key4]['buah_jjgcak'] = $sumPerBH;
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
                            $rekap[$key][$key1][$key2][$key3][$key4]['namaGM'] = get_nama_asisten($key3, $key4);
                            $rekap[$key][$key1][$key2][$key3][$key4]['ha_samplecak'] = 0;
                            $rekap[$key][$key1][$key2][$key3][$key4]['jumlah_panencak'] = 0;
                            $rekap[$key][$key1][$key2][$key3][$key4]['akp_rlcak'] = 0;
                            $rekap[$key][$key1][$key2][$key3][$key4]['pcak'] = 0;
                            $rekap[$key][$key1][$key2][$key3][$key4]['kcak'] = 0;
                            $rekap[$key][$key1][$key2][$key3][$key4]['tglcak'] = 0;
                            $rekap[$key][$key1][$key2][$key3][$key4]['total_brdcak'] = 0;
                            $rekap[$key][$key1][$key2][$key3][$key4]['brd_jjgcak'] = 0;
                            // data untuk buah tinggal
                            $rekap[$key][$key1][$key2][$key3][$key4]['bhts_scak'] = 0;
                            $rekap[$key][$key1][$key2][$key3][$key4]['bhtm1cak'] = 0;
                            $rekap[$key][$key1][$key2][$key3][$key4]['bhtm2cak'] = 0;
                            $rekap[$key][$key1][$key2][$key3][$key4]['bhtm3cak'] = 0;
                            $rekap[$key][$key1][$key2][$key3][$key4]['buah_jjgcak'] = 0;
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
                        $namaGM =  get_nama_em($key3);
                        // dd($namaGM, $key1, $key2, $key3);
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
                            'brd_jjgcak' => $brdPerjjgEst,
                            'bhts_scak' => $bhtsEST,
                            'bhtm1cak' => $bhtm1EST,
                            'bhtm2cak' => $bhtm2EST,
                            'bhtm3cak' => $bhtm3EST,
                            'buah_jjgcak' => $sumPerBHEst,
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
                    // $namaGM = '-';
                    // $namewil = 'WIL-' . convertToRoman($key);
                    // foreach ($queryAsisten as $asisten) {

                    //     // dd($asisten);
                    //     if ($asisten['est'] == $namewil && $asisten['afd'] == 'GM') {
                    //         $namaGM = $asisten['nama'];
                    //         break;
                    //     }
                    // }
                    // dd($key, $key1, $key2);
                    $rekap[$key][$key1][$key2]['wil']['ancak'] = [
                        'bulan' => $key1,
                        'data' =>  $data,
                        'namewil' =>  'WIL-' . convertToRoman($key2),
                        'namaGM' =>  get_nama_gm($key2),
                        'pokok_samplecak' =>  $pokok_panenWil,
                        'ha_samplecak' =>   $jum_haWil,
                        'check_datacak' =>   $check_data,
                        'jumlah_panencak' =>  $janjang_panenWil,
                        'akp_rlcak' =>   $akpWil,
                        'pcak' =>  $p_panenWil,
                        'kcak' =>  $k_panenWil,
                        'tglcak' =>  $brtgl_panenWil,
                        'total_brdcak' =>  $totalPKTwil,
                        'brd_jjgcak' =>  $brdPerwil,
                        'buah_jjgwilcak' =>  $sumPerBHWil,
                        'bhts_scak' =>  $bhts_panenWil,
                        'bhtm1cak' =>  $bhtm1_panenWil,
                        'bhtm2cak' =>  $bhtm2_panenWil,
                        'bhtm3cak' =>  $bhtm3_oanenWil,
                        'total_buahcak' =>  $sumBHWil,
                        'buah_jjgcak' =>  $sumPerBHWil,
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
                    // dd($rekap);
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
                // $namaGM = '-';
                $namewil = 'REG-' . convertToRoman($key);
                // foreach ($queryAsisten as $asisten) {

                //     // dd($asisten);
                //     if ($asisten['est'] == $namewil && $asisten['afd'] == 'GM') {
                //         $namaGM = $asisten['nama'];
                //         break;
                //     }
                // }
                // dd($key, $key1);
                $rekap[$key][$key1]['reg']['ancak'] = [
                    'bulan' => $key1,
                    'data' =>  $data,
                    'namewil' =>  $namewil,
                    'namaGM' => get_nama_rh($key),
                    'pokok_samplecak' =>  $pokok_panenreg,
                    'ha_samplecak' =>   $jum_hareg,
                    'check_datacak' =>   $check_data,
                    'jumlah_panencak' =>  $janjang_panenreg,
                    'akp_rlcak' =>   $akpWil,
                    'pcak' =>  $p_panenWil,
                    'kcak' =>  $k_panenWil,
                    'tglcak' =>  $brtgl_panenWil,
                    'total_brdcak' =>  $totalPKTwil,
                    'brd_jjgcak' =>  $brdPerwil,
                    'buah_jjgwilcak' =>  $sumPerBHWil,
                    'bhts_scak' =>  $bhts_panenWil,
                    'bhtm1cak' =>  $bhtm1_panenWil,
                    'bhtm2cak' =>  $bhtm2_panenWil,
                    'bhtm3cak' =>  $bhtm3_oanenWil,
                    'total_buahcak' =>  $sumBHWil,
                    'buah_jjgcak' =>  $sumPerBHWil,
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

        // dd($rekap[1]['January']);

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

        // Helper function to check if key should be excluded
        $excludedKeys = ['wil', 'estate', 'ancak', 'buah', 'trans'];

        // Process nested data structure
        foreach ($rekap as $months) {
            foreach ($months as $month => $wilayahs) {
                foreach ($wilayahs as $wilayah => $estates) {
                    if (!in_array($wilayah, $excludedKeys)) {
                        foreach ($estates as $estate => $afdelings) {
                            if (!in_array($estate, $excludedKeys) && is_array($afdelings)) {
                                $skor_akhircak = 0;
                                $total_skorbh = 0;
                                $total_skortrans = 0;
                                foreach ($afdelings as $afdeling => $afdelingDetails) {
                                    if (!in_array($afdeling, $excludedKeys)) {
                                        // Get values with null coalescing
                                        $skor_akhircak = $afdelingDetails['skor_akhircak'] ?? 0;
                                        $total_skorbh = $afdelingDetails['TOTAL_SKORbh'] ?? 0;
                                        $total_skortrans = $afdelingDetails['totalSkortrans'] ?? 0;
                                        $check_databh = $afdelingDetails['check_databh'] ?? 'kosong';
                                        $check_datacak = $afdelingDetails['check_datacak'] ?? 'kosong';
                                        $check_datatrans = $afdelingDetails['check_datatrans'] ?? 'kosong';

                                        // Calculate total by adding the values
                                        $afdelingDetails['calculation_string'] = "Ancak : " . $skor_akhircak . " Buah : " . $total_skorbh . " Trans : " . $total_skortrans;

                                        // Check if all data is empty
                                        if ($check_databh == 'kosong' && $check_datacak == 'kosong' && $check_datatrans == 'kosong') {
                                            $afdelingDetails['calculation'] = '-';
                                        } else {
                                            $afdelingDetails['calculation'] = $skor_akhircak + $total_skorbh + $total_skortrans;
                                        }

                                        $resultafdeling[$estate][$afdeling][$month] = $afdelingDetails;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        // dd($resultafdeling);
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



        // dd($resultafdeling);
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
                    $sum_btEst += $value2['total_brdtrans'] ?? 0;
                    $sum_rstEst += $value2['total_buahtrans'] ?? 0;
                    $dataBLokEst += $value2['tph_sampleNew'] ?? 0;
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
                // foreach ($queryAsisten as $asisten) {

                //     // dd($asisten);
                //     if ($asisten['est'] == $key1 && $asisten['afd'] == 'EM') {
                //         $namaGM = $asisten['nama'];
                //         break;
                //     }
                // }
                $namaGM = get_nama_em($key1);
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
                    'brd_jjgcak' => $brdPerjjgEst,
                    'bhts_scak' => $bhtsEST,
                    'bhtm1cak' => $bhtm1EST,
                    'bhtm2cak' => $bhtm2EST,
                    'bhtm3cak' => $bhtm3EST,
                    'buah_jjgcak' => $sumPerBHEst,
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
        // dd($resultafdeling);
        return [
            'resultreg' => $resultreg,
            'resultwil' => $resultwil,
            'resultestate' => $resultestate,
            'resultafdeling' => $resultafdeling,
            'rekaptahunan_afdeling' => $rekaptahunan_afdeling,
        ];
    }
}

if (!function_exists('rekap_qcinspeks_perbulan')) {
    function rekap_qcinspeks_perbulan($regional, $bulan)
    {

        // dd($value2['datetime'], $endDate);
        $queryEste = DB::connection('mysql2')->table('estate')
            ->select('estate.*')
            ->where('estate.emp', '!=', 1)
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->where('wil.regional', $regional)
            ->get();
        $queryEste = json_decode($queryEste, true);

        // dd($queryEste);

        $muaest = DB::connection('mysql2')->table('estate')
            ->select('estate.*')
            ->where('estate.emp', '!=', 1)
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->where('wil.regional', $regional)
            ->get('est');
        $muaest = json_decode($muaest, true);

        // dd($muaest, $queryEste);

        $queryAfd = DB::connection('mysql2')->table('afdeling')
            ->select(
                'afdeling.id',
                'afdeling.nama',
                'estate.est'
            ) //buat mengambil data di estate db dan willayah db
            ->join('estate', 'estate.id', '=', 'afdeling.estate') //kemudian di join untuk mengambil est perwilayah
            ->get();
        $queryAfd = json_decode($queryAfd, true);


        $QueryMTancakWil = DB::connection('mysql2')->table('mutu_ancak_new')
            ->select("mutu_ancak_new.*", DB::raw('DATE_FORMAT(mutu_ancak_new.datetime, "%M") as bulan'), DB::raw('DATE_FORMAT(mutu_ancak_new.datetime, "%Y") as tahun'))
            // ->whereYear('datetime', '2023')
            // ->where('datetime', 'like', '%' . $getDate . '%')
            ->where('datetime', 'like', '%' . $bulan . '%')
            // ->whereYear('datetime', $year)
            ->orderBy('afdeling', 'asc')
            ->get();
        $QueryMTancakWil = $QueryMTancakWil->groupBy(['estate', 'afdeling']);
        $QueryMTancakWil = json_decode($QueryMTancakWil, true);

        $dataPerBulan = array();
        foreach ($QueryMTancakWil as $key => $value) {
            foreach ($value as $key2 => $value2) {
                foreach ($value2 as $key3 => $value3) {
                    $dataPerBulan[$key][$key2][$key3] = $value3;
                }
            }
        }

        // dd($QueryMTancakWil);
        // dd($QueryMTancakWil);

        $defaultNew = [];

        foreach ($queryEste as $est) {
            foreach ($queryAfd as $afd) {
                // dd($est);
                if ($est['est'] == $afd['est']) {
                    if ($est['est'] === 'LDE' || $est['est'] === 'SRE') {
                        $defaultNew[$est['est']][$afd['est']]['null'] = 0;
                    } else {
                        $defaultNew[$est['est']][$afd['nama']]['null'] = 0;
                    }
                }
            }
        }


        $defaultNewmua = array();
        foreach ($muaest as $est) {
            foreach ($queryAfd as $afd) {
                if ($est['est'] == $afd['est']) {
                    $defaultNewmua[$est['est']][$afd['est']]['null'] = 0;
                }
            }
        }

        // dd($defaultNewmua, $defaultNew);
        $mergedDatamua = array();
        foreach ($defaultNewmua as $estKey => $afdArray) {
            foreach ($afdArray as $afdKey => $afdValue) {
                if (array_key_exists($estKey, $dataPerBulan)) {
                    if (array_key_exists($afdKey, $dataPerBulan[$estKey])) {
                        if (!empty($dataPerBulan[$estKey][$afdKey])) {
                            $mergedDatamua[$estKey][$afdKey] = $dataPerBulan[$estKey][$afdKey];
                        } else {
                            $mergedDatamua[$estKey][$afdKey] = $afdValue;
                        }
                    } else {
                        $mergedDatamua[$estKey][$afdKey] = $afdValue;
                    }
                } else {
                    $mergedDatamua[$estKey][$afdKey] = $afdValue;
                }
            }
        }
        $mtancakWIltab1mua = array();
        foreach ($muaest as $key => $value) {
            foreach ($mergedDatamua as $key2 => $value2) {
                if ($value['est'] == $key2) {
                    $mtancakWIltab1mua[$value['wil']][$key2] = array_merge($mtancakWIltab1mua[$value['wil']][$key2] ?? [], $value2);
                }
            }
        }


        // dd($mtancakWIltab1mua, $mergedDatamua);
        $mergedData = array();
        foreach ($defaultNew as $estKey => $afdArray) {
            foreach ($afdArray as $afdKey => $afdValue) {
                if (array_key_exists($estKey, $dataPerBulan)) {
                    if (array_key_exists($afdKey, $dataPerBulan[$estKey])) {
                        if (!empty($dataPerBulan[$estKey][$afdKey])) {
                            $mergedData[$estKey][$afdKey] = $dataPerBulan[$estKey][$afdKey];
                        } else {
                            $mergedData[$estKey][$afdKey] = $afdValue;
                        }
                    } else {
                        $mergedData[$estKey][$afdKey] = $afdValue;
                    }
                } else {
                    $mergedData[$estKey][$afdKey] = $afdValue;
                }
            }
        }
        $mtancakWIltab1 = array();
        foreach ($queryEste as $key => $value) {
            foreach ($mergedData as $key2 => $value2) {
                if ($value['est'] == $key2) {
                    $mtancakWIltab1[$value['wil']][$key2] = array_merge($mtancakWIltab1[$value['wil']][$key2] ?? [], $value2);
                }
            }
        }


        // dd($mtancakWIltab1);

        $QueryMTbuahWil = DB::connection('mysql2')->table('mutu_buah')
            ->select(
                "mutu_buah.*",
                DB::raw('DATE_FORMAT(mutu_buah.datetime, "%M") as bulan'),
                DB::raw('DATE_FORMAT(mutu_buah.datetime, "%Y") as tahun')
            )
            ->where('datetime', 'like', '%' . $bulan . '%')
            ->orderBy('afdeling', 'asc')
            ->get();
        $QueryMTbuahWil = $QueryMTbuahWil->groupBy(['estate', 'afdeling']);
        $QueryMTbuahWil = json_decode($QueryMTbuahWil, true);

        // dd($QueryMTbuahWil);

        $dataMTBuah = array();
        foreach ($QueryMTbuahWil as $key => $value) {
            foreach ($value as $key2 => $value2) {
                foreach ($value2 as $key3 => $value3) {
                    $dataMTBuah[$key][$key2][$key3] = $value3;
                }
            }
        }

        $defaultMTbuah = array();
        foreach ($queryEste as $est) {
            foreach ($queryAfd as $afd) {
                if ($est['est'] == $afd['est']) {
                    if ($est['est'] === 'LDE' || $est['est'] === 'SRE') {
                        $defaultMTbuah[$est['est']][$afd['est']]['null'] = 0;
                    } else {
                        $defaultMTbuah[$est['est']][$afd['nama']]['null'] = 0;
                    }
                }
                // if ($est['est'] == $afd['est']) {
                //     $defaultMTbuah[$est['est']][$afd['nama']]['null'] = 0;
                // }
            }
        }



        // dd($mtBuahWIltab1mua);




        $mutuBuahMerge = array();
        foreach ($defaultMTbuah as $estKey => $afdArray) {
            foreach ($afdArray as $afdKey => $afdValue) {
                if (array_key_exists($estKey, $dataMTBuah)) {
                    if (array_key_exists($afdKey, $dataMTBuah[$estKey])) {
                        if (!empty($dataMTBuah[$estKey][$afdKey])) {
                            $mutuBuahMerge[$estKey][$afdKey] = $dataMTBuah[$estKey][$afdKey];
                        } else {
                            $mutuBuahMerge[$estKey][$afdKey] = $afdValue;
                        }
                    } else {
                        $mutuBuahMerge[$estKey][$afdKey] = $afdValue;
                    }
                } else {
                    $mutuBuahMerge[$estKey][$afdKey] = $afdValue;
                }
            }
        }
        $mtBuahWIltab1 = array();
        foreach ($queryEste as $key => $value) {
            foreach ($mutuBuahMerge as $key2 => $value2) {
                if ($value['est'] == $key2) {
                    $mtBuahWIltab1[$value['wil']][$key2] = array_merge($mtBuahWIltab1[$value['wil']][$key2] ?? [], $value2);
                }
            }
        }
        // dd($bulan);

        $QueryPengurangan = DB::connection('mysql2')->table('list_estate_nilai')
            ->select('*')
            ->where('date', $bulan)
            ->get();
        $QueryPengurangan = json_decode($QueryPengurangan, true);
        // dd($QueryPengurangan);

        // dd($mtancakWIltab1);
        $rekap = [];
        foreach ($mtancakWIltab1 as $key => $value) if (!empty($value)) {
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
            foreach ($value as $key1 => $value1) if (!empty($value2)) {
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
                foreach ($value1 as $key2 => $value2) if (!empty($value2)) {

                    $akp = 0;
                    $skor_bTinggal = 0;
                    $brdPerjjg = 0;

                    $ttlSkorMA = 0;
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
                    $listBlok = array();
                    $jml_blok = 0;
                    foreach ($value2 as $key3 => $value3) if (is_array($value3)) {
                        if (!in_array($value3['estate'] . ' ' . $value3['afdeling'] . ' ' . $value3['blok'], $listBlok)) {
                            if ($value3['sph'] != 0) {
                                $listBlok[] = $value3['estate'] . ' ' . $value3['afdeling'] . ' ' . $value3['blok'];
                                $sph += $value3['sph'];
                            }
                        }
                        $jml_blok = count($listBlok);
                        $totalPokok += $value3["sample"];
                        $totalPanen +=  $value3["jjg"];
                        $totalP_panen += $value3["brtp"];
                        $totalK_panen += $value3["brtk"];
                        $totalPTgl_panen += $value3["brtgl"];

                        $totalbhts_panen += $value3["bhts"];
                        $totalbhtm1_panen += $value3["bhtm1"];
                        $totalbhtm2_panen += $value3["bhtm2"];
                        $totalbhtm3_oanen += $value3["bhtm3"];

                        $totalpelepah_s += $value3["ps"];
                        $check_input = $value3["jenis_input"];
                        $nilai_input = $value3["skor_akhir"];
                    }
                    $jml_sph = $jml_blok == 0 ? $sph : ($sph / $jml_blok);
                    $luas_ha = ($jml_sph != 0) ? round(($totalPokok / $jml_sph), 2) : 0;

                    if ($totalPokok != 0) {
                        $akp = round(($totalPanen / $totalPokok) * 100, 1);
                    } else {
                        $akp = 0;
                    }


                    $skor_bTinggal = $totalP_panen + $totalK_panen + $totalPTgl_panen;

                    if ($totalPanen != 0) {
                        $brdPerjjg = round($skor_bTinggal / $totalPanen, 3);
                    } else {
                        $brdPerjjg = 0;
                    }

                    $sumBH = $totalbhts_panen +  $totalbhtm1_panen +  $totalbhtm2_panen +  $totalbhtm3_oanen;
                    if ($sumBH != 0) {
                        $sumPerBH = round($sumBH / ($totalPanen + $sumBH) * 100, 3);
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
                        $rekap[$key][$key1][$key2]['check_datacak'] = 'ada';
                        // $rekap[$key][$key1][$key2]['skor_brd'] = $skor_brd = skor_brd_ma($brdPerjjg);
                        // $rekap[$key][$key1][$key2]['skor_ps'] = $skor_ps = skor_palepah_ma($perPl);
                    } else {
                        $rekap[$key][$key1][$key2]['check_datacak'] = 'kosong';
                        // $rekap[$key][$key1][$key2]['skor_brd'] = $skor_brd = 0;
                        // $rekap[$key][$key1][$key2]['skor_ps'] = $skor_ps = 0;
                    }

                    // $ttlSkorMA = $skor_bh + $skor_brd + $skor_ps;
                    $ttlSkorMA =  skor_buah_Ma($sumPerBH) + skor_brd_ma($brdPerjjg) + skor_palepah_ma($perPl);
                    $rekap[$key][$key1][$key2]['afdeling_Data'] = '-----------------------';
                    $rekap[$key][$key1][$key2]['pokok_samplecak'] = $totalPokok;
                    $rekap[$key][$key1][$key2]['asisten'] = get_nama_asisten($key1, $key2);
                    $rekap[$key][$key1][$key2]['ha_samplecak'] = $luas_ha;
                    $rekap[$key][$key1][$key2]['jumlah_panencak'] = $totalPanen;
                    $rekap[$key][$key1][$key2]['akp_rlcak'] = $akp;
                    $rekap[$key][$key1][$key2]['pcak'] = $totalP_panen;
                    $rekap[$key][$key1][$key2]['kcak'] = $totalK_panen;
                    $rekap[$key][$key1][$key2]['tglcak'] = $totalPTgl_panen;
                    $rekap[$key][$key1][$key2]['total_brdcak'] = $skor_bTinggal;
                    $rekap[$key][$key1][$key2]['brd_jjgcak'] = $brdPerjjg;
                    // data untuk buah tinggal
                    $rekap[$key][$key1][$key2]['bhts_scak'] = $totalbhts_panen;
                    $rekap[$key][$key1][$key2]['bhtm1cak'] = $totalbhtm1_panen;
                    $rekap[$key][$key1][$key2]['bhtm2cak'] = $totalbhtm2_panen;
                    $rekap[$key][$key1][$key2]['bhtm3cak'] = $totalbhtm3_oanen;
                    $rekap[$key][$key1][$key2]['buah_jjgcak'] = $sumPerBH;
                    $rekap[$key][$key1][$key2]['total_buahcak'] = $sumBH;
                    $rekap[$key][$key1][$key2]['jjgperBuahcak'] = number_format($sumPerBH, 3);
                    // data untuk pelepah sengklek
                    $rekap[$key][$key1][$key2]['palepah_pokokcak'] = $totalpelepah_s;
                    $rekap[$key][$key1][$key2]['palepah_percak'] = $perPl;
                    $rekap[$key][$key1][$key2]['skor_bhcak'] = skor_buah_Ma($sumPerBH);
                    $rekap[$key][$key1][$key2]['skor_brdcak'] = skor_brd_ma($brdPerjjg);
                    $rekap[$key][$key1][$key2]['skor_pscak'] =  skor_palepah_ma($perPl);
                    // total skor akhir
                    $rekap[$key][$key1][$key2]['skor_akhircak'] = $ttlSkorMA;
                    $rekap[$key][$key1][$key2]['check_inputcak'] = $check_input;
                    $rekap[$key][$key1][$key2]['est'] = $key1;
                    $rekap[$key][$key1][$key2]['afd'] = $key2;
                    $rekap[$key][$key1][$key2]['mutuancak'] = '-----------------------------------';

                    $pokok_panenEst += $totalPokok;

                    $jum_haEst += $luas_ha;
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
                    $rekap[$key][$key1][$key2]['afdeling_Data'] = '-----------------------';
                    $rekap[$key][$key1][$key2]['pokok_samplecak'] = 0;
                    $rekap[$key][$key1][$key2]['ha_samplecak'] = 0;
                    $rekap[$key][$key1][$key2]['jumlah_panencak'] = 0;
                    $rekap[$key][$key1][$key2]['akp_rlcak'] =  0;
                    $rekap[$key][$key1][$key2]['est'] = $key1;
                    $rekap[$key][$key1][$key2]['afd'] = $key2;
                    $rekap[$key][$key1][$key2]['pcak'] = 0;
                    $rekap[$key][$key1][$key2]['kcak'] = 0;
                    $rekap[$key][$key1][$key2]['tglcak'] = 0;

                    // $rekap[$key][$key1][$key2]['total_brdcak'] = $skor_bTinggal;
                    $rekap[$key][$key1][$key2]['brd_jjgcak'] = 0;

                    // data untuk buah tinggal
                    $rekap[$key][$key1][$key2]['bhts_scak'] = 0;
                    $rekap[$key][$key1][$key2]['bhtm1cak'] = 0;
                    $rekap[$key][$key1][$key2]['bhtm2cak'] = 0;
                    $rekap[$key][$key1][$key2]['bhtm3cak'] = 0;
                    $rekap[$key][$key1][$key2]['total_buahcak'] = 0;

                    // $rekap[$key][$key1][$key2]['jjgperBuahcak'] = number_format($sumPerBH, 3);
                    // data untuk pelepah sengklek

                    $rekap[$key][$key1][$key2]['palepah_pokokcak'] = 0;
                    // total skor akhi0;

                    $rekap[$key][$key1][$key2]['skor_bhcak'] = 0;
                    $rekap[$key][$key1][$key2]['skor_brdcak'] = 0;
                    $rekap[$key][$key1][$key2]['skor_pscak'] = 0;
                    $rekap[$key][$key1][$key2]['skor_akhircak'] = 0;
                    $rekap[$key][$key1][$key2]['mutuancak'] = '-----------------------------------';
                }

                $sumBHEst = $bhtsEST +  $bhtm1EST +  $bhtm2EST +  $bhtm3EST;
                $totalPKT = $p_panenEst + $k_panenEst + $brtgl_panenEst;
                // dd($sumBHEst);
                if ($pokok_panenEst != 0) {
                    $akpEst = round(($janjang_panenEst / $pokok_panenEst) * 100, 3);
                } else {
                    $akpEst = 0;
                }

                if ($janjang_panenEst != 0) {
                    $brdPerjjgEst = round($totalPKT / $janjang_panenEst, 3);
                } else {
                    $brdPerjjgEst = 0;
                }



                // dd($sumBHEst);
                if ($sumBHEst != 0) {
                    $sumPerBHEst = round($sumBHEst / ($janjang_panenEst + $sumBHEst) * 100, 3);
                } else {
                    $sumPerBHEst = 0;
                }

                if ($pokok_panenEst != 0) {
                    $perPlEst = round(($pelepah_sEST / $pokok_panenEst) * 100, 3);
                } else {
                    $perPlEst = 0;
                }


                $nonZeroValues = array_filter([$p_panenEst, $k_panenEst, $brtgl_panenEst, $bhtsEST, $bhtm1EST, $bhtm2EST, $bhtm3EST]);

                if (!empty($nonZeroValues)) {
                    // $rekap[$key][$key1]['check_data'] = 'ada';
                    $check_data = 'ada';
                    // $rekap[$key][$key1]['skor_brd'] = $skor_brd = skor_brd_ma($brdPerjjgEst);
                    // $rekap[$key][$key1]['skor_ps'] = $skor_ps = skor_palepah_ma($perPlEst);
                } else {
                    // $rekap[$key][$key1]['check_data'] = 'kosong';
                    $check_data = 'kosong';
                    // $rekap[$key][$key1]['skor_brd'] = $skor_brd = 0;
                    // $rekap[$key][$key1]['skor_ps'] = $skor_ps = 0;
                }
                // dd($key1);
                $pengurangan = 0;
                $penambahan = 0;
                foreach ($QueryPengurangan as $num => $vals) {
                    if ($vals['est'] === $key1) {
                        if ($vals['tipe'] === 'minus') {
                            $pengurangan = $vals['nilai'];
                        } else {
                            $penambahan = $vals['nilai'];
                        }
                    }
                }


                // $totalSkorEst = $skor_bh + $skor_brd + $skor_ps;

                $totalSkorEst =  skor_brd_ma($brdPerjjgEst) + skor_buah_Ma($sumPerBHEst) + skor_palepah_ma($perPlEst);
                //PENAMPILAN UNTUK PERESTATE
                // dd($key1);
                $rekap[$key][$key1]['est']['estancak'] = [
                    'estate_Data' => '-----------------------',
                    'pokok_samplecak' => $pokok_panenEst,
                    'asisten' => get_nama_em($key1),
                    'ha_samplecak' =>  $jum_haEst,
                    'jumlah_panencak' => $janjang_panenEst,
                    'akp_rlcak' =>  $akpEst,
                    'pcak' => $p_panenEst,
                    'kcak' => $k_panenEst,
                    'tglcak' => $brtgl_panenEst,
                    'total_brdcak' => $skor_bTinggal,
                    'brd_jjgcak' => $brdPerjjgEst,
                    'bhts_scak' => $bhtsEST,
                    'bhtm1cak' => $bhtm1EST,
                    'bhtm2cak' => $bhtm2EST,
                    'bhtm3cak' => $bhtm3EST,
                    'buah_jjgcak' => $sumPerBHEst,
                    'total_buahcak' => $sumBHEst,
                    'palepah_pokokcak' => $pelepah_sEST,
                    'palepah_percak' => $perPlEst,
                    'skor_bhcak' => skor_buah_Ma($sumPerBHEst),
                    'skor_brdcak' => skor_brd_ma($brdPerjjgEst),
                    'skor_pscak' =>  skor_palepah_ma($perPlEst),
                    'skor_akhircak' =>  $totalSkorEst,
                    'check_datacak' => $check_data,
                    'est' => $key1,
                    'afd' => 'est',
                    'pengurangan' => $pengurangan,
                    'penambahan' => $penambahan,
                    'mutuancak' => '-----------------------------------'
                ];


                //perhitungn untuk perwilayah

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
            } else {
                $rekap[$key][$key1]['est']['estancak'] = [
                    'pokok_samplecak' => 0,
                    'ha_samplecak' => 0,
                    'jumlah_panencak' => 0,
                    'akp_rlcak' => 0,
                    'pcak' => 0,
                    'kcak' => 0,
                    'tglcak' => 0,
                    'total_brdcak' => 0,
                    'brd_jjgcak' => 0,
                    'bhts_scak' => 0,
                    'bhtm1cak' => 0,
                    'bhtm2cak' => 0,
                    'bhtm3cak' => 0,
                    'buah_jjgcak' => 0,
                    'total_buahcak' => 0,
                    'palepah_pokokcak' => 0,
                    'palepah_percak' => 0,
                    'skor_bhcak' => 0,
                    'skor_brdcak' => 0,
                    'skor_pscak' => 0,
                    'skor_akhircak' => 0,
                    'check_datacak' => 'kosong',
                    'est' => $key1,
                    'afd' => 'est',
                    'mutuancak' => '-----------------------------------'
                ];
            }
            $totalPKTwil = $p_panenWil + $k_panenWil + $brtgl_panenWil;
            $sumBHWil = $bhts_panenWil +  $bhtm1_panenWil +  $bhtm2_panenWil +  $bhtm3_oanenWil;
            $janjang_panenWil = $janjang_panenWilx;

            if ($janjang_panenWil == 0 || $pokok_panenWil == 0) {
                $akpWil = 0;
            } else {

                $akpWil = round(($janjang_panenWil / $pokok_panenWil) * 100, 3);
            }

            if ($totalPKTwil != 0) {
                $brdPerwil = round($totalPKTwil / $janjang_panenWil, 3);
            } else {
                $brdPerwil = 0;
            }

            // dd($sumBHEst);
            if ($sumBHWil != 0) {
                $sumPerBHWil = round($sumBHWil / ($janjang_panenWil + $sumBHWil) * 100, 3);
            } else {
                $sumPerBHWil = 0;
            }

            if ($pokok_panenWil != 0) {
                $perPiWil = round(($pelepah_swil / $pokok_panenWil) * 100, 3);
            } else {
                $perPiWil = 0;
            }

            $nonZeroValues = array_filter([$p_panenWil, $k_panenWil, $brtgl_panenWil, $bhts_panenWil, $bhtm1_panenWil, $bhtm2_panenWil, $bhtm3_oanenWil]);

            if (!empty($nonZeroValues)) {
                // $rekap[$key]['check_data'] = 'ada';
                $check_data = 'ada';
                // $rekap[$key]['skor_brd'] = $skor_brd = skor_brd_ma($brdPerwil);
                // $rekap[$key]['skor_ps'] = $skor_ps = skor_palepah_ma($perPiWil);
            } else {
                // $rekap[$key]['check_data'] = 'kosong';
                $check_data = 'kosong';
                // $rekap[$key]['skor_brd'] = $skor_brd = 0;
                // $rekap[$key]['skor_ps'] = $skor_ps = 0;
            }

            // $totalWil = $skor_bh + $skor_brd + $skor_ps;
            $totalWil = skor_brd_ma($brdPerwil) + skor_buah_Ma($sumPerBHWil) + skor_palepah_ma($perPiWil);


            if ($key == 10) {
                $news_key = 'Plasma';
            } else if ($key == 11) {
                $news_key = 'Inti';
            } else {
                $news_key =  convertToRoman($key);
            }

            $rekap[$key]['wil']['wilancak'] = [
                'wil_Data' => '-----------------------',
                'data' =>  $data,
                'pokok_samplecak' =>  $pokok_panenWil,
                'ha_samplecak' =>   $jum_haWil,
                'gm' =>  get_nama_gm($key),
                'check_datacak' =>   $check_data,
                'jumlah_panencak' =>  $janjang_panenWil,
                'akp_rlcak' =>   $akpWil,
                'pcak' =>  $p_panenWil,
                'kcak' =>  $k_panenWil,
                'tglcak' =>  $brtgl_panenWil,
                'total_brdcak' =>  $totalPKTwil,
                'brd_jjgcak' =>  $brdPerwil,
                'buah_jjgwilcak' =>  $sumPerBHWil,
                'bhts_scak' =>  $bhts_panenWil,
                'bhtm1cak' =>  $bhtm1_panenWil,
                'bhtm2cak' =>  $bhtm2_panenWil,
                'bhtm3cak' =>  $bhtm3_oanenWil,
                'total_buahcak' =>  $sumBHWil,
                'buah_jjgcak' =>  $sumPerBHWil,
                'jjgperBuahcak' =>  number_format($sumPerBH, 3),
                'palepah_pokokcak' =>  $pelepah_swil,
                'palepah_percak' =>  $perPiWil,
                'skor_bhcak' =>  skor_buah_Ma($sumPerBHWil),
                'skor_brdcak' =>  skor_brd_ma($brdPerwil),
                'skor_pscak' =>  skor_palepah_ma($perPiWil),
                'skor_akhircak' =>  $totalWil,
                'est' => $news_key,
                'afd' => 'WIL',
                'mutuancak' => '-----------------------------------'
            ];
        } else {
            if ($key == 10) {
                $news_key = 'Plasma';
            } else if ($key == 11) {
                $news_key = 'Inti';
            } else {
                $news_key =  convertToRoman($key);
            }

            $rekap[$key]['wil']['wilancak'] = [
                'wil_Data' => '-----------------------',
                'pokok_samplecak' => 0,
                'gm' =>  get_nama_gm($key),
                'ha_samplecak' => 0,
                'check_datacak' => 'kosong',
                'jumlah_panencak' => 0,
                'akp_rlcak' => 0,
                'pcak' => 0,
                'kcak' => 0,
                'tglcak' => 0,
                'total_brdcak' => 0,
                'brd_jjgcak' => 0,
                'buah_jjgwilcak' => 0,
                'bhts_scak' => 0,
                'bhtm1cak' => 0,
                'bhtm2cak' => 0,
                'bhtm3cak' => 0,
                'total_buahcak' => 0,
                'buah_jjgcak' => 0,
                'jjgperBuahcak' => 0,
                'palepah_pokokcak' => 0,
                'palepah_percak' => 0,
                'skor_bhcak' => 0,
                'skor_brdcak' => 0,
                'skor_pscak' => 0,
                'skor_akhircak' => 0,
                'est' => $news_key,
                'afd' => 'WIL',
                'mutuancak' => '-----------------------------------'
            ];
        }
        // dd($rekap);
        foreach ($mtBuahWIltab1 as $key => $value) if (is_array($value)) {
            $jum_haWil = 0;
            $sum_SamplejjgWil = 0;
            $sum_bmtWil = 0;
            $sum_bmkWil = 0;
            $sum_overWil = 0;
            $sum_abnorWil = 0;
            $sum_kosongjjgWil = 0;
            $sum_vcutWil = 0;
            $sum_krWil = 0;
            $no_Vcutwil = 0;

            foreach ($value as $key1 => $value1) if (is_array($value1)) {
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

                foreach ($value1 as $key2 => $value2) if (is_array($value2)) {
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
                    $jum_ha = 0;
                    $no_Vcut = 0;
                    $jml_mth = 0;
                    $jml_mtg = 0;
                    $dataBLok = 0;
                    $listBlokPerAfd = [];
                    $dtBlok = 0;
                    // $combination_counts = array();
                    foreach ($value2 as $key3 => $value3) if (is_array($value3)) {
                        $listBlokPerAfd[] = $value3['estate'] . ' ' . $value3['afdeling'] . ' ' . $value3['blok'] . ' ' . $value3['tph_baris'];
                        $dtBlok = count($listBlokPerAfd);

                        // $jum_ha = count($listBlokPerAfd);
                        $sum_bmt += $value3['bmt'];
                        $sum_bmk += $value3['bmk'];
                        $sum_over += $value3['overripe'];
                        $sum_kosongjjg += $value3['empty_bunch'];
                        $sum_vcut += $value3['vcut'];
                        $sum_kr += $value3['alas_br'];


                        $sum_Samplejjg += $value3['jumlah_jjg'];
                        $sum_abnor += $value3['abnormal'];
                    }

                    // $dataBLok = count($combination_counts);
                    $dataBLok = $dtBlok;
                    $jml_mth = ($sum_bmt + $sum_bmk);
                    $jml_mtg = $sum_Samplejjg - ($jml_mth + $sum_over + $sum_kosongjjg + $sum_abnor);

                    if ($sum_kr != 0) {
                        $total_kr = round($sum_kr / $dataBLok, 3);
                    } else {
                        $total_kr = 0;
                    }


                    $per_kr = round($total_kr * 100, 3);
                    if ($jml_mth != 0) {
                        $PerMth = round(($jml_mth / ($sum_Samplejjg - $sum_abnor)) * 100, 3);
                    } else {
                        $PerMth = 0;
                    }
                    if ($jml_mtg != 0) {
                        $PerMsk = round(($jml_mtg / ($sum_Samplejjg - $sum_abnor)) * 100, 3);
                    } else {
                        $PerMsk = 0;
                    }
                    if ($sum_over != 0) {
                        $PerOver = round(($sum_over / ($sum_Samplejjg - $sum_abnor)) * 100, 3);
                    } else {
                        $PerOver = 0;
                    }
                    if ($sum_kosongjjg != 0) {
                        $Perkosongjjg = round(($sum_kosongjjg / ($sum_Samplejjg - $sum_abnor)) * 100, 3);
                    } else {
                        $Perkosongjjg = 0;
                    }
                    if ($sum_vcut != 0) {
                        $PerVcut = round(($sum_vcut / $sum_Samplejjg) * 100, 3);
                    } else {
                        $PerVcut = 0;
                    }

                    if ($sum_abnor != 0) {
                        $PerAbr = round(($sum_abnor / $sum_Samplejjg) * 100, 3);
                    } else {
                        $PerAbr = 0;
                    }

                    $nonZeroValues = array_filter([$sum_Samplejjg, $jml_mth, $jml_mtg, $sum_over, $sum_abnor, $sum_kosongjjg, $sum_vcut, $dataBLok]);

                    if (!empty($nonZeroValues)) {
                        $rekap[$key][$key1][$key2]['check_databh'] = 'ada';
                        // $rekap[$key][$key1][$key2]['skor_masak'] = $skor_masak =  skor_buah_masak_mb($PerMsk);
                        // $rekap[$key][$key1][$key2]['skor_over'] = $skor_over =  skor_buah_over_mb($PerOver);
                        // $rekap[$key][$key1][$key2]['skor_jjgKosong'] = $skor_jjgKosong =  skor_jangkos_mb($Perkosongjjg);
                        // $rekap[$key][$key1][$key2]['skor_vcut'] = $skor_vcut =  skor_vcut_mb($PerVcut);
                        // $rekap[$key][$key1][$key2]['skor_kr'] = $skor_kr =  skor_abr_mb($per_kr);
                    } else {
                        $rekap[$key][$key1][$key2]['check_databh'] = 'kosong';
                        // $rekap[$key][$key1][$key2]['skor_masak'] = $skor_masak = 0;
                        // $rekap[$key][$key1][$key2]['skor_over'] = $skor_over = 0;
                        // $rekap[$key][$key1][$key2]['skor_jjgKosong'] = $skor_jjgKosong = 0;
                        // $rekap[$key][$key1][$key2]['skor_vcut'] = $skor_vcut =  0;
                        // $rekap[$key][$key1][$key2]['skor_kr'] = $skor_kr = 0;
                    }

                    // $totalSkor = $skor_mentah + $skor_masak + $skor_over + $skor_jjgKosong + $skor_vcut + $skor_kr;


                    $totalSkor =  skor_buah_mentah_mb($PerMth) + skor_buah_masak_mb($PerMsk) + skor_buah_over_mb($PerOver) + skor_vcut_mb($PerVcut) + skor_jangkos_mb($Perkosongjjg) + skor_abr_mb($per_kr);
                    $rekap[$key][$key1][$key2]['tph_baris_bloksbh'] = $dataBLok;
                    $rekap[$key][$key1][$key2]['sampleJJG_totalbh'] = $sum_Samplejjg;
                    $rekap[$key][$key1][$key2]['total_mentahbh'] = $jml_mth;
                    $rekap[$key][$key1][$key2]['total_perMentahbh'] = $PerMth;
                    $rekap[$key][$key1][$key2]['total_masakbh'] = $jml_mtg;
                    $rekap[$key][$key1][$key2]['total_perMasakbh'] = $PerMsk;
                    $rekap[$key][$key1][$key2]['total_overbh'] = $sum_over;
                    $rekap[$key][$key1][$key2]['total_perOverbh'] = $PerOver;
                    $rekap[$key][$key1][$key2]['total_abnormalbh'] = $sum_abnor;
                    $rekap[$key][$key1][$key2]['perAbnormalbh'] = $PerAbr;
                    $rekap[$key][$key1][$key2]['total_jjgKosongbh'] = $sum_kosongjjg;
                    $rekap[$key][$key1][$key2]['total_perKosongjjgbh'] = $Perkosongjjg;
                    $rekap[$key][$key1][$key2]['total_vcutbh'] = $sum_vcut;
                    $rekap[$key][$key1][$key2]['perVcutbh'] = $PerVcut;

                    $rekap[$key][$key1][$key2]['jum_krbh'] = $sum_kr;
                    $rekap[$key][$key1][$key2]['total_krbh'] = $total_kr;
                    $rekap[$key][$key1][$key2]['persen_krbh'] = $per_kr;

                    // skoring
                    $rekap[$key][$key1][$key2]['skor_mentahbh'] = skor_buah_mentah_mb($PerMth);
                    $rekap[$key][$key1][$key2]['skor_masakbh'] = skor_buah_masak_mb($PerMsk);
                    $rekap[$key][$key1][$key2]['skor_overbh'] = skor_buah_over_mb($PerOver);
                    $rekap[$key][$key1][$key2]['skor_jjgKosongbh'] = skor_jangkos_mb($Perkosongjjg);
                    $rekap[$key][$key1][$key2]['skor_vcutbh'] = skor_vcut_mb($PerVcut);
                    $rekap[$key][$key1][$key2]['skor_krbh'] = skor_abr_mb($per_kr);
                    $rekap[$key][$key1][$key2]['TOTAL_SKORbh'] = $totalSkor;
                    $rekap[$key][$key1][$key2]['mutubuah'] = '-----------------------------------------';

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
                    $rekap[$key][$key1][$key2]['tph_baris_bloksbh'] = 0;
                    $rekap[$key][$key1][$key2]['sampleJJG_totalbh'] = 0;
                    $rekap[$key][$key1][$key2]['total_mentahbh'] = 0;
                    $rekap[$key][$key1][$key2]['total_perMentahbh'] = 0;
                    $rekap[$key][$key1][$key2]['total_masakbh'] = 0;
                    $rekap[$key][$key1][$key2]['total_perMasakbh'] = 0;
                    $rekap[$key][$key1][$key2]['total_overbh'] = 0;
                    $rekap[$key][$key1][$key2]['total_perOverbh'] = 0;
                    $rekap[$key][$key1][$key2]['total_abnormalbh'] = 0;
                    $rekap[$key][$key1][$key2]['perAbnormalbh'] = 0;
                    $rekap[$key][$key1][$key2]['total_jjgKosongbh'] = 0;
                    $rekap[$key][$key1][$key2]['total_perKosongjjgbh'] = 0;
                    $rekap[$key][$key1][$key2]['total_vcutbh'] = 0;
                    $rekap[$key][$key1][$key2]['perVcutbh'] = 0;

                    $rekap[$key][$key1][$key2]['jum_krbh'] = 0;
                    $rekap[$key][$key1][$key2]['total_krbh'] = 0;
                    $rekap[$key][$key1][$key2]['persen_krbh'] = 0;

                    // skoring
                    $rekap[$key][$key1][$key2]['skor_mentahbh'] = 0;
                    $rekap[$key][$key1][$key2]['skor_masakbh'] = 0;
                    $rekap[$key][$key1][$key2]['skor_overbh'] = 0;
                    $rekap[$key][$key1][$key2]['skor_jjgKosongbh'] = 0;
                    $rekap[$key][$key1][$key2]['skor_vcutbh'] = 0;
                    $rekap[$key][$key1][$key2]['skor_abnormalbh'] = 0;;
                    $rekap[$key][$key1][$key2]['skor_krbh'] = 0;
                    $rekap[$key][$key1][$key2]['TOTAL_SKORbh'] = 0;
                    $rekap[$key][$key1][$key2]['mutubuah'] = '-----------------------------------------';
                }
                $no_VcutEst = $sum_SamplejjgEst - $sum_vcutEst;

                if ($sum_krEst != 0) {
                    $total_krEst = round($sum_krEst / $jum_haEst, 3);
                } else {
                    $total_krEst = 0;
                }
                // if ($sum_kr != 0) {
                //     $total_kr = round($sum_kr / $dataBLok, 3);
                // } else {
                //     $total_kr = 0;
                // }

                if ($sum_bmtEst != 0) {
                    $PerMthEst = round(($sum_bmtEst / ($sum_SamplejjgEst - $sum_abnorEst)) * 100, 3);
                } else {
                    $PerMthEst = 0;
                }

                if ($sum_bmkEst != 0) {
                    $PerMskEst = round(($sum_bmkEst / ($sum_SamplejjgEst - $sum_abnorEst)) * 100, 3);
                } else {
                    $PerMskEst = 0;
                }

                if ($sum_overEst != 0) {
                    $PerOverEst = round(($sum_overEst / ($sum_SamplejjgEst - $sum_abnorEst)) * 100, 3);
                } else {
                    $PerOverEst = 0;
                }
                if ($sum_kosongjjgEst != 0) {
                    $PerkosongjjgEst = round(($sum_kosongjjgEst / ($sum_SamplejjgEst - $sum_abnorEst)) * 100, 3);
                } else {
                    $PerkosongjjgEst = 0;
                }
                if ($sum_vcutEst != 0) {
                    $PerVcutest = round(($sum_vcutEst / $sum_SamplejjgEst) * 100, 3);
                } else {
                    $PerVcutest = 0;
                }
                if ($sum_abnorEst != 0) {
                    $PerAbrest = round(($sum_abnorEst / $sum_SamplejjgEst) * 100, 3);
                } else {
                    $PerAbrest = 0;
                }
                // $per_kr = round($sum_kr * 100);
                $per_krEst = round($total_krEst * 100, 3);


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


                $rekap[$key][$key1]['est']['estbuah'] = [
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


                //hitung perwilayah
                $jum_haWil += $jum_haEst;
                $sum_SamplejjgWil += $sum_SamplejjgEst;
                $sum_bmtWil += $sum_bmtEst;
                $sum_bmkWil += $sum_bmkEst;
                $sum_overWil += $sum_overEst;
                $sum_abnorWil += $sum_abnorEst;
                $sum_kosongjjgWil += $sum_kosongjjgEst;
                $sum_vcutWil += $sum_vcutEst;
                $sum_krWil += $sum_krEst;
            } else {
                $rekap[$key][$key1]['est']['estbuah'] = [
                    'check_databh' => 'kosong',
                    'tph_baris_bloksbh'  => 0,
                    'sampleJJG_totalbh'  => 0,
                    'total_mentahbh' => 0,
                    'total_perMentahbh'  => 0,
                    'total_masakbh' => 0,
                    'total_perMasakbh' => 0,
                    'total_overbh' => 0,
                    'total_perOverbh' => 0,
                    'total_abnormalbh' => 0,
                    'perAbnormalbh' => 0,
                    'total_jjgKosongbh'  => 0,
                    'total_perKosongjjgbh' => 0,
                    'total_vcutbh' => 0,
                    'perVcutbh' => 0,
                    'jum_krbh'  => 0,
                    'total_krbh'  => 0,
                    'persen_krbh'  => 0,
                    'skor_mentahbh' => 0,
                    'skor_masakbh'  => 0,
                    'skor_overbh' => 0,
                    'skor_jjgKosongbh' => 0,
                    'skor_vcutbh'  => 0,
                    'skor_krbh'  => 0,
                    'TOTAL_SKORbh'  => 0,
                    'mutubuah' => '------------------------------------------',
                ];
            }

            // if ($sum_kr != 0) {
            //     $total_kr = round($sum_kr / $dataBLok, 3);
            // } else {
            //     $total_kr = 0;
            // }



            if ($sum_krWil != 0) {
                $total_krWil = round($sum_krWil / $jum_haWil, 3);
            } else {
                $total_krWil = 0;
            }

            if ($sum_bmtWil != 0) {
                $PerMthWil = round(($sum_bmtWil / ($sum_SamplejjgWil - $sum_abnorWil)) * 100, 3);
            } else {
                $PerMthWil = 0;
            }


            if ($sum_bmkWil != 0) {
                $PerMskWil = round(($sum_bmkWil / ($sum_SamplejjgWil - $sum_abnorWil)) * 100, 3);
            } else {
                $PerMskWil = 0;
            }
            if ($sum_overWil != 0) {
                $PerOverWil = round(($sum_overWil / ($sum_SamplejjgWil - $sum_abnorWil)) * 100, 3);
            } else {
                $PerOverWil = 0;
            }
            if ($sum_kosongjjgWil != 0) {
                $PerkosongjjgWil = round(($sum_kosongjjgWil / ($sum_SamplejjgWil - $sum_abnorWil)) * 100, 3);
            } else {
                $PerkosongjjgWil = 0;
            }
            if ($sum_vcutWil != 0) {
                $PerVcutWil = round(($sum_vcutWil / $sum_SamplejjgWil) * 100, 3);
            } else {
                $PerVcutWil = 0;
            }
            if ($sum_abnorWil != 0) {
                $PerAbrWil = round(($sum_abnorWil / $sum_SamplejjgWil) * 100, 3);
            } else {
                $PerAbrWil = 0;
            }
            $per_krWil = round($total_krWil * 100, 3);

            $nonZeroValues = array_filter([$sum_SamplejjgWil, $sum_bmtWil, $sum_bmkWil, $sum_overWil, $sum_abnorWil, $sum_kosongjjgWil, $sum_vcutWil]);

            if (!empty($nonZeroValues)) {
                // $rekap[$key]['check_data'] = 'ada';
                $check_data = 'ada';
            } else {
                // $rekap[$key]['check_data'] = 'kosong';
                $check_data = 'kosong';
            }

            $totalSkorWil =  skor_buah_mentah_mb($PerMthWil) + skor_buah_masak_mb($PerMskWil) + skor_buah_over_mb($PerOverWil) + skor_jangkos_mb($PerkosongjjgWil) + skor_vcut_mb($PerVcutWil) + skor_abr_mb($per_krWil);

            $rekap[$key]['wil']['wilbuah']  = [
                'check_databh' => $check_data,
                'tph_baris_bloksbh' => $jum_haWil,
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
        } else {

            $rekap[$key]['wil']['wilbuah']  = [
                'check_databh' => 'kosong',
                'tph_baris_bloksbh' => 0,
                'sampleJJG_totalbh' => 0,
                'total_mentahbh' => 0,
                'total_perMentahbh' => 0,
                'total_masakbh' => 0,
                'total_perMasakbh' => 0,
                'total_overbh' => 0,
                'total_perOverbh' => 0,
                'total_abnormalbh' => 0,
                'perAbnormalbh' => 0,
                'total_jjgKosongbh' => 0,
                'total_perKosongjjgbh' => 0,
                'total_vcutbh' => 0,
                'perVcutbh' => 0,
                'jum_krbh' => 0,
                'total_krbh' => 0,
                'persen_krbh' => 0,
                // skoring
                'skor_mentahbh' => 0,
                'skor_masakbh' => 0,
                'skor_overbh' => 0,
                'skor_jjgKosongbh' => 0,
                'skor_vcutbh' => 0,
                'skor_krbh' => 0,
                'TOTAL_SKORbh' => 0,
                'mutubuah' => '------------------------------------------',
            ];
        }
        // dd($rekap[3]);

        $TranscakReg2 = DB::connection('mysql2')->table('mutu_transport')
            ->select(
                "mutu_transport.*",
                DB::raw('DATE_FORMAT(mutu_transport.datetime, "%Y-%m-%d") as date')
            )
            ->where('datetime', 'like', '%' . $bulan . '%')
            ->orderBy('datetime', 'DESC')
            ->orderBy(DB::raw('SECOND(datetime)'), 'DESC')
            ->get();
        $AncakCakReg2 = DB::connection('mysql2')->table('mutu_ancak_new')
            ->select(
                "mutu_ancak_new.*",
                DB::raw('DATE_FORMAT(mutu_ancak_new.datetime, "%Y-%m-%d") as date')
            )
            ->where('datetime', 'like', '%' . $bulan . '%')
            ->orderBy('datetime', 'DESC')
            ->orderBy(DB::raw('SECOND(datetime)'), 'DESC')
            ->get();

        $TranscakReg2 = $TranscakReg2->groupBy(['estate', 'afdeling', 'date', 'blok']);
        $AncakCakReg2 = $AncakCakReg2->groupBy(['estate', 'afdeling', 'date', 'blok']);

        // dd($TranscakReg2);

        // dd($TranscakReg2[1]);


        $DataTransGroupReg2 = json_decode($TranscakReg2, true);


        $groupedDataAcnakreg2 = json_decode($AncakCakReg2, true);
        // dd($groupedDataAcnakreg2);


        $dataMTTransRegs2 = array();
        foreach ($DataTransGroupReg2 as $key => $value) {
            foreach ($queryEste as $est => $estval)
                if ($estval['est'] === $key) {
                    foreach ($value as $key2 => $value2) {
                        foreach ($queryAfd as $afd => $afdval)
                            if ($afdval['est'] === $key && $afdval['nama'] === $key2) {
                                foreach ($value2 as $key3 => $value3) {

                                    foreach ($value3 as $key4 => $value4) {

                                        $dataMTTransRegs2[$afdval['est']][$afdval['nama']][$key3][$key4] = $value4;
                                    }
                                }
                            }
                    }
                }
        }

        // dd($dataMTTransRegs2, $dataMTTransRegs2);
        $dataAncaksRegs2 = array();
        foreach ($groupedDataAcnakreg2 as $key => $value) {
            foreach ($queryEste as $est => $estval)
                if ($estval['est'] === $key) {
                    foreach ($value as $key2 => $value2) {
                        foreach ($queryAfd as $afd => $afdval)
                            if ($afdval['est'] === $key && $afdval['nama'] === $key2) {
                                foreach ($value2 as $key3 => $value3) {
                                    foreach ($value3 as $key4 => $value4) {
                                        $dataAncaksRegs2[$afdval['est']][$afdval['nama']][$key3][$key4] = $value4;
                                    }
                                }
                            }
                    }
                }
        }
        // dd($dataMTTransRegs2);
        $ancakRegss2 = array();

        foreach ($dataAncaksRegs2 as $key => $value) {
            foreach ($value as $key1 => $value2) {
                foreach ($value2 as $key2 => $value3) {
                    $sum = 0; // Initialize sum variable
                    $count = 0; // Initialize count variable
                    foreach ($value3 as $key3 => $value4) {
                        $listBlok = array();
                        $firstEntry = $value4[0];
                        foreach ($value4 as $key4 => $value5) {
                            // dd($value5['sph']);
                            if (!in_array($value5['estate'] . ' ' . $value5['afdeling'] . ' ' . $value5['blok'], $listBlok)) {
                                if ($value5['sph'] != 0) {
                                    $listBlok[] = $value5['estate'] . ' ' . $value5['afdeling'] . ' ' . $value5['blok'];
                                }
                            }
                            $jml_blok = count($listBlok);

                            if ($firstEntry['luas_blok'] != 0) {
                                $first = $firstEntry['luas_blok'];
                            } else {
                                $first = '-';
                            }
                        }
                        if ($first != '-') {
                            $sum += $first;
                            $count++;
                        }
                        $ancakRegss2[$key][$key1][$key2][$key3]['luas_blok'] = $first;
                        if ($regional === '2') {
                            $status_panen = explode(",", $value5['status_panen']);
                            $ancakRegss2[$key][$key1][$key2][$key3]['status_panen'] = $status_panen[0];
                        } else {
                            $ancakRegss2[$key][$key1][$key2][$key3]['status_panen'] = $value5['status_panen'];
                        }
                    }
                }
            }
        }

        // dd($ancakRegss2);
        $transNewdata = array();
        foreach ($dataMTTransRegs2 as $key => $value) {
            foreach ($value as $key1 => $value1) {

                foreach ($value1 as $key2 => $value2) {

                    foreach ($value2 as $key3 => $value3) {
                        $sum_bt = 0;
                        $sum_Restan = 0;
                        $tph_sample = 0;
                        $listBlokPerAfd = array();
                        foreach ($value3 as $key4 => $value4) {
                            $listBlokPerAfd[] = $value4['estate'] . ' ' . $value4['afdeling'] . ' ' . $value4['blok'];
                            $sum_Restan += $value4['rst'];
                            $tph_sample = count($listBlokPerAfd);
                            $sum_bt += $value4['bt'];
                        }
                        $panenKey = 0;
                        $LuasKey = 0;
                        if (isset($ancakRegss2[$key][$key1][$key2][$key3]['status_panen'])) {
                            $transNewdata[$key][$key1][$key2][$key3]['status_panen'] = $ancakRegss2[$key][$key1][$key2][$key3]['status_panen'];
                            $panenKey = $ancakRegss2[$key][$key1][$key2][$key3]['status_panen'];
                        }
                        if (isset($ancakRegss2[$key][$key1][$key2][$key3]['luas_blok'])) {
                            $transNewdata[$key][$key1][$key2][$key3]['luas_blok'] = $ancakRegss2[$key][$key1][$key2][$key3]['luas_blok'];
                            $LuasKey = $ancakRegss2[$key][$key1][$key2][$key3]['luas_blok'];
                        }


                        if ($panenKey !== 0 && $panenKey <= 3) {
                            if (count($value4) == 1 && $value4[0]['blok'] == '0') {
                                $tph_sample = $value4[0]['tph_baris'];
                                $sum_bt = $value4[0]['bt'];
                            } else {
                                $transNewdata[$key][$key1][$key2][$key3]['tph_sample'] = round(floatval($LuasKey) * 1.3, 3);
                            }
                        } else {
                            $transNewdata[$key][$key1][$key2][$key3]['tph_sample'] = $tph_sample;
                        }



                        $transNewdata[$key][$key1][$key2][$key3]['estate'] = $value4['estate'];
                        $transNewdata[$key][$key1][$key2][$key3]['afdeling'] = $value4['afdeling'];
                        $transNewdata[$key][$key1][$key2][$key3]['estate'] = $value4['estate'];
                    }
                }
            }
        }

        foreach ($ancakRegss2 as $key => $value) {
            foreach ($value as $key1 => $value1) {

                foreach ($value1 as $key2 => $value2) {
                    $tph_tod = 0;
                    foreach ($value2 as $key3 => $value3) {
                        if (!isset($transNewdata[$key][$key1][$key2][$key3])) {
                            $transNewdata[$key][$key1][$key2][$key3] = $value3;

                            if ($value3['status_panen'] <= 3) {
                                $transNewdata[$key][$key1][$key2][$key3]['tph_sample'] = round(floatval($value3['luas_blok']) * 1.3, 3);
                            } else {
                                $transNewdata[$key][$key1][$key2][$key3]['tph_sample'] = 0;
                            }
                        }
                        // If 'tph_sample' key exists, add its value to $tph_tod
                        if (isset($value3['tph_sample'])) {
                            $tph_tod += $value3['tph_sample'];
                        }
                    }
                }
                // Store total_tph for each $key1 after iterating all $key2

            }
        }
        foreach ($transNewdata as $key => &$value) {
            foreach ($value as $key1 => &$value1) {
                $tph_sample_total = 0; // initialize the total
                foreach ($value1 as $key2 => $value2) {
                    if (is_array($value2)) {
                        foreach ($value2 as $key3 => $value3) {
                            if (isset($value3['tph_sample'])) {
                                $tph_sample_total += $value3['tph_sample'];
                            }
                        }
                    }
                }
                $value1['total_tph'] = $tph_sample_total;
            }
        }
        unset($value); // unset the reference
        unset($value1); // unset the reference
        // dd($transNewdata);
        // dd($ancakRegss2['SBE']['OE'], $dataMTTransRegs2['SBE']['OE'], $transNewdata['SBE']['OE']);
        $defaultMtTrans = array();
        foreach ($queryEste as $est) {
            // dd($est);
            foreach ($queryAfd as $afd) {
                // dd($afd);
                if ($est['est'] == $afd['est']) {
                    if ($est['est'] === 'LDE' || $est['est'] === 'SRE') {
                        $defaultMtTrans[$est['est']][$afd['est']]['null'] = 0;
                    } else {
                        $defaultMtTrans[$est['est']][$afd['nama']]['null'] = 0;
                    }
                }
                // if ($est['est'] == $afd['est']) {
                //     $defaultMtTrans[$est['est']][$afd['nama']]['null'] = 0;
                // }
            }
        }
        $QueryTransWil = DB::connection('mysql2')->table('mutu_transport')
            ->select(
                "mutu_transport.*",
                DB::raw('DATE_FORMAT(mutu_transport.datetime, "%M") as bulan'),
                DB::raw('DATE_FORMAT(mutu_transport.datetime, "%Y") as tahun')
            )
            ->where('datetime', 'like', '%' . $bulan . '%')
            // ->whereYear('datetime', $year)
            ->get();
        $QueryTransWil = $QueryTransWil->groupBy(['estate', 'afdeling']);
        $QueryTransWil = json_decode($QueryTransWil, true);


        // dd($QueryTransWil);
        $dataMTTrans = array();
        foreach ($QueryTransWil as $key => $value) {
            foreach ($value as $key2 => $value2) {
                foreach ($value2 as $key3 => $value3) {
                    $dataMTTrans[$key][$key2][$key3] = $value3;
                }
            }
        }
        $mutuAncakMerge = array();
        foreach ($defaultMtTrans as $estKey => $afdArray) {
            foreach ($afdArray as $afdKey => $afdValue) {
                if (array_key_exists($estKey, $dataMTTrans)) {
                    if (array_key_exists($afdKey, $dataMTTrans[$estKey])) {
                        if (!empty($dataMTTrans[$estKey][$afdKey])) {
                            $mutuAncakMerge[$estKey][$afdKey] = $dataMTTrans[$estKey][$afdKey];
                        } else {
                            $mutuAncakMerge[$estKey][$afdKey] = $afdValue;
                        }
                    } else {
                        $mutuAncakMerge[$estKey][$afdKey] = $afdValue;
                    }
                } else {
                    $mutuAncakMerge[$estKey][$afdKey] = $afdValue;
                }
            }
        }
        $mtTransWiltab1 = array();
        foreach ($queryEste as $key => $value) {
            foreach ($mutuAncakMerge as $key2 => $value2) {
                if ($value['est'] == $key2) {
                    $mtTransWiltab1[$value['wil']][$key2] = array_merge($mtTransWiltab1[$value['wil']][$key2] ?? [], $value2);
                }
            }
        }

        // dd($mtTranstab1Wilmua, $sidak_buah_mua);

        // dd($transNewdata);

        $mtTranstab1Wil = array();
        foreach ($mtTransWiltab1 as $key => $value) if (!empty($value)) {
            $dataBLokWil = 0;
            $sum_btWil = 0;
            $sum_rstWil = 0;
            foreach ($value as $key1 => $value1) if (!empty($value1)) {
                $dataBLokEst = 0;
                $sum_btEst = 0;
                $sum_rstEst = 0;
                foreach ($value1 as $key2 => $value2) if (!empty($value2)) {
                    $sum_bt = 0;
                    $sum_rst = 0;
                    $brdPertph = 0;
                    $buahPerTPH = 0;
                    $totalSkor = 0;
                    $dataBLok = 0;
                    $listBlokPerAfd = array();
                    foreach ($value2 as $key3 => $value3) if (is_array($value3)) {

                        // if (!in_array($value3['estate'] . ' ' . $value3['afdeling'] . ' ' . $value3['blok'] , $listBlokPerAfd)) {
                        $listBlokPerAfd[] = $value3['estate'] . ' ' . $value3['afdeling'] . ' ' . $value3['blok'];
                        // }
                        $dataBLok = count($listBlokPerAfd);
                        $sum_bt += $value3['bt'];
                        $sum_rst += $value3['rst'];
                    }
                    $tot_sample = 0;  // Define the variable outside of the foreach loop

                    foreach ($transNewdata as $keys => $trans) {
                        if ($keys == $key1) {
                            foreach ($trans as $keys2 => $trans2) {
                                if ($keys2 == $key2) {
                                    // $rekap[$key][$key1][$key2]['tph_sampleNew'] = $trans2['total_tph'];
                                    $tot_sample = $trans2['total_tph'];
                                }
                            }
                        }
                    }

                    if ($regional == '2' || $regional == 2) {
                        if ($dataBLok != 0) {
                            $brdPertph = round($sum_bt / $tot_sample, 3);
                        } else {
                            $brdPertph = 0;
                        }
                    } else {
                        if ($dataBLok != 0) {
                            $brdPertph = round($sum_bt / $dataBLok, 3);
                        } else {
                            $brdPertph = 0;
                        }
                    }

                    if ($regional == '2' || $regional == 2) {
                        if ($dataBLok != 0) {
                            $buahPerTPH = round($sum_rst / $tot_sample, 3);
                        } else {
                            $buahPerTPH = 0;
                        }
                    } else {
                        if ($dataBLok != 0) {
                            $buahPerTPH = round($sum_rst / $dataBLok, 3);
                        } else {
                            $buahPerTPH = 0;
                        }
                    }


                    $nonZeroValues = array_filter([$sum_bt, $sum_rst]);

                    if (!empty($nonZeroValues)) {
                        $rekap[$key][$key1][$key2]['check_datatrans'] = 'ada';
                    } else {
                        $rekap[$key][$key1][$key2]['check_datatrans'] = "kosong";
                    }
                    // dd($transNewdata);




                    $totalSkor =   skor_brd_tinggal($brdPertph) + skor_buah_tinggal($buahPerTPH);

                    if ($regional == '2' || $regional == 2) {
                        $rekap[$key][$key1][$key2]['tph_sampleNew'] = $tot_sample;
                    } else {
                        $rekap[$key][$key1][$key2]['tph_sampleNew'] = $dataBLok;
                    }

                    $rekap[$key][$key1][$key2]['total_brdtrans'] = $sum_bt;
                    $rekap[$key][$key1][$key2]['total_brdperTPHtrans'] = $brdPertph;
                    $rekap[$key][$key1][$key2]['total_buahtrans'] = $sum_rst;
                    $rekap[$key][$key1][$key2]['total_buahPerTPHtrans'] = $buahPerTPH;
                    $rekap[$key][$key1][$key2]['skor_brdPertphtrans'] = skor_brd_tinggal($brdPertph);
                    $rekap[$key][$key1][$key2]['skor_buahPerTPHtrans'] = skor_buah_tinggal($buahPerTPH);
                    $rekap[$key][$key1][$key2]['totalSkortrans'] = $totalSkor;
                    $rekap[$key][$key1][$key2]['mututrans'] = '-----------------------------------';

                    //PERHITUNGAN PERESTATE
                    if ($regional == '2' || $regional == 2) {
                        $dataBLokEst += $tot_sample;
                    } else {
                        $dataBLokEst += $dataBLok;
                    }

                    $sum_btEst += $sum_bt;
                    $sum_rstEst += $sum_rst;

                    if ($dataBLokEst != 0) {
                        $brdPertphEst = round($sum_btEst / $dataBLokEst, 3);
                    } else {
                        $brdPertphEst = 0;
                    }

                    if ($dataBLokEst != 0) {
                        $buahPerTPHEst = round($sum_rstEst / $dataBLokEst, 3);
                    } else {
                        $buahPerTPHEst = 0;
                    }

                    // dd($rekap);
                    $totalSkorEst = skor_brd_tinggal($brdPertphEst) + skor_buah_tinggal($buahPerTPHEst);
                } else {
                    $rekap[$key][$key1][$key2]['check_datatrans'] = 'kosong';
                    $rekap[$key][$key1][$key2]['tph_sampleNew'] = 0;
                    $rekap[$key][$key1][$key2]['tph_sampletrans'] = 0;
                    $rekap[$key][$key1][$key2]['total_brdtrans'] = 0;
                    $rekap[$key][$key1][$key2]['total_brdperTPHtrans'] = 0;
                    $rekap[$key][$key1][$key2]['total_buahtrans'] = 0;
                    $rekap[$key][$key1][$key2]['total_buahPerTPHtrans'] = 0;
                    $rekap[$key][$key1][$key2]['skor_brdPertphtrans'] = 0;
                    $rekap[$key][$key1][$key2]['skor_buahPerTPHtrans'] = 0;
                    $rekap[$key][$key1][$key2]['totalSkortrans'] = 0;
                    $rekap[$key][$key1][$key2]['mututrans'] = '-----------------------------------';
                }

                $nonZeroValues = array_filter([$sum_btEst, $sum_rstEst]);

                if (!empty($nonZeroValues)) {
                    $check_data = 'ada';
                    // $rekap[$key][$key1]['skor_buahPerTPH'] = $skor_buah =  skor_buah_tinggal($buahPerTPHEst);
                } else {
                    $check_data = 'kosong';
                    // $rekap[$key][$key1]['skor_buahPerTPH'] = $skor_buah = 0;
                }

                // $totalSkorEst = $skor_brd + $skor_buah ;


                $rekap[$key][$key1]['est']['esttrans'] = [
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


                //perhitungan per wil
                $dataBLokWil += $dataBLokEst;
                $sum_btWil += $sum_btEst;
                $sum_rstWil += $sum_rstEst;

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

                $totalSkorWil =   skor_brd_tinggal($brdPertphWil) + skor_buah_tinggal($buahPerTPHWil);
            } else {
                $rekap[$key][$key1]['est']['esttrans'] = [
                    'tph_sampleNew' => 0,
                    'total_brdtrans' => 0,
                    'check_datatrans' => 'kosong',
                    'total_brdperTPHtrans' => 0,
                    'total_buahtrans' => 0,
                    'total_buahPerTPHtrans' => 0,
                    'skor_brdPertphtrans' => 0,
                    'skor_buahPerTPHtrans' => 0,
                    'totalSkortrans' => 0,
                    'mututrans' => '-----------------------------------'
                ];
            }

            // dd($rekap);

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
            $rekap[$key]['wil']['wiltrans'] = [
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
        } else {
            $rekap[$key]['wil']['wiltrans'] = [
                'check_datatrans' => 'kosong',
                'tph_sampleNew' => 0,
                'total_brdtrans' => 0,
                'total_brdperTPHtrans' => 0,
                'total_buahtrans' => 0,
                'total_buahPerTPHtrans' => 0,
                'skor_brdPertphtrans' => 0,
                'skor_buahPerTPHtrans' => 0,
                'totalSkortrans' => 0,
                'mututrans' => '-----------------------------------'
            ];
        }


        foreach ($rekap as $key => $value) {
            foreach ($value as $key1 => $value1) {
                if (isset($value1["est"])) {
                    // Get the "est" array
                    $estArray = $value1["est"];

                    // Merge all arrays within "est"
                    $mergedEst = [];
                    foreach ($estArray as $subEst) {
                        $mergedEst = array_merge($mergedEst, $subEst);
                    }

                    // Unset the "est" key
                    unset($rekap[$key][$key1]["est"]);

                    // Replace the "est" key with the merged array
                    $rekap[$key][$key1]["estate"] = $mergedEst;
                }
            }
            if (isset($value["wil"])) {
                // Get the "est" array
                $estArray = $value["wil"];

                // Merge all arrays within "est"
                $mergedEst = [];
                foreach ($estArray as $subEst) {
                    $mergedEst = array_merge($mergedEst, $subEst);
                }

                // Unset the "est" key
                unset($rekap[$key]["wil"]);

                // Replace the "est" key with the merged array
                $rekap[$key]["wilayah"]['wil'] = $mergedEst;
            }
        }

        if ($regional == 1) {
            $muaarray = [
                'SRE' => $rekap[3]['SRE']['estate'] ?? [],
                'LDE' => $rekap[3]['LDE']['estate'] ?? [],
            ];


            $ha_samplecak = 0;
            $jumlah_panencak = 0;
            $pcak = 0;
            $kcak = 0;
            $tglcak = 0;
            $bhts_scak = 0;
            $bhtm1cak = 0;
            $bhtm2cak = 0;
            $bhtm3cak = 0;
            $palepah_pokokcak = 0;
            $pokok_samplecak = 0;
            $tph_sampleNew = 0;
            $total_brdtrans = 0;
            $total_buahtrans = 0;

            $tph_baris_bloksbh = 0;
            $sampleJJG_totalbh = 0;
            $total_mentahbh = 0;
            $total_overbh = 0;
            $total_abnormalbh = 0;
            $total_jjgKosongbh = 0;
            $total_vcutbh = 0;
            $jum_krbh = 0;
            foreach ($muaarray as $key => $value) {

                // ancak 
                $pokok_samplecak += $value['pokok_samplecak'];
                $ha_samplecak += $value['ha_samplecak'];
                $jumlah_panencak += $value['jumlah_panencak'];
                $pcak += $value['pcak'];
                $kcak += $value['kcak'];
                $tglcak += $value['tglcak'];
                $bhts_scak += $value['bhts_scak'];
                $bhtm1cak += $value['bhtm1cak'];
                $bhtm2cak += $value['bhtm2cak'];
                $bhtm3cak += $value['bhtm3cak'];
                $palepah_pokokcak += $value['palepah_pokokcak'];

                $tph_sampleNew += $value['tph_sampleNew'];
                $total_brdtrans += $value['total_brdtrans'];
                $total_buahtrans += $value['total_buahtrans'];

                $tph_baris_bloksbh += $value['tph_baris_bloksbh'];
                $sampleJJG_totalbh += $value['sampleJJG_totalbh'];
                $total_mentahbh += $value['total_mentahbh'];
                $total_overbh += $value['total_overbh'];
                $total_abnormalbh += $value['total_abnormalbh'];
                $total_jjgKosongbh += $value['total_jjgKosongbh'];
                $total_vcutbh += $value['total_vcutbh'];
                $jum_krbh += $value['jum_krbh'];
            }

            if ($ha_samplecak != 0) {
                $akp = round(($jumlah_panencak / $pokok_samplecak) * 100, 1);
                $datacak = 'ada';
            } else {
                $akp = 0;
                $datacak = 'kosong';
            }
            $skor_bTinggal = $pcak + $kcak + $tglcak;

            if ($jumlah_panencak != 0) {
                $brdPerjjg = round($skor_bTinggal / $jumlah_panencak, 3);
            } else {
                $brdPerjjg = 0;
            }
            $sumBH = $bhts_scak +  $bhtm1cak +  $bhtm2cak +  $bhtm3cak;
            if ($sumBH != 0) {
                $sumPerBH = round($sumBH / ($jumlah_panencak + $sumBH) * 100, 3);
            } else {
                $sumPerBH = 0;
            }
            if ($palepah_pokokcak != 0) {
                $perPl = ($palepah_pokokcak / $pokok_samplecak) * 100;
            } else {
                $perPl = 0;
            }

            if ($tph_sampleNew != 0) {
                $brdPertph = round($total_brdtrans / $tph_sampleNew, 3);
            } else {
                $brdPertph = 0;
            }
            if ($tph_sampleNew != 0) {
                $buahPerTPH = round($total_buahtrans / $tph_sampleNew, 3);
            } else {
                $buahPerTPH = 0;
            }


            $dataBLok = $tph_baris_bloksbh;
            $jml_mth = $total_mentahbh;
            $jml_mtg = $sampleJJG_totalbh - ($total_mentahbh + $total_overbh + $total_jjgKosongbh + $total_abnormalbh);

            if ($jum_krbh != 0) {
                $total_kr = round($jum_krbh / $dataBLok, 3);
            } else {
                $total_kr = 0;
            }


            $per_kr = round($total_kr * 100, 3);
            if ($jml_mth != 0) {
                $PerMth = round(($jml_mth / ($sampleJJG_totalbh - $total_abnormalbh)) * 100, 3);
            } else {
                $PerMth = 0;
            }
            if ($jml_mtg != 0) {
                $PerMsk = round(($jml_mtg / ($sampleJJG_totalbh - $total_abnormalbh)) * 100, 3);
            } else {
                $PerMsk = 0;
            }
            if ($total_overbh != 0) {
                $PerOver = round(($total_overbh / ($sampleJJG_totalbh - $total_abnormalbh)) * 100, 3);
            } else {
                $PerOver = 0;
            }
            if ($total_jjgKosongbh != 0) {
                $Perkosongjjg = round(($total_jjgKosongbh / ($sampleJJG_totalbh - $total_abnormalbh)) * 100, 3);
            } else {
                $Perkosongjjg = 0;
            }
            if ($total_vcutbh != 0) {
                $PerVcut = round(($total_vcutbh / $sampleJJG_totalbh) * 100, 3);
            } else {
                $PerVcut = 0;
            }

            if ($total_abnormalbh != 0) {
                $PerAbr = round(($total_abnormalbh / $sampleJJG_totalbh) * 100, 3);
            } else {
                $PerAbr = 0;
            }

            $totalSkorEsttrans = skor_brd_tinggal($brdPertph) + skor_buah_tinggal($buahPerTPH);
            $totalSkorEstancak =  skor_palepah_ma($perPl) + skor_buah_Ma($sumPerBH) + skor_brd_ma($brdPerjjg);
            $totalSkorBuah =  skor_buah_mentah_mb($PerMth) + skor_buah_masak_mb($PerMsk) + skor_buah_over_mb($PerOver) + skor_vcut_mb($PerVcut) + skor_jangkos_mb($Perkosongjjg) + skor_abr_mb($per_kr);

            $resultmua['pokok_samplecak'] = $pokok_samplecak;
            $resultmua['ha_samplecak'] = $ha_samplecak;
            $resultmua['jumlah_panencak'] = $jumlah_panencak;
            $resultmua['asisten'] = get_nama_em('PT.MUA');
            $resultmua['akp_rlcak'] = $akp;
            $resultmua['pcak'] = $pcak;
            $resultmua['kcak'] = $kcak;
            $resultmua['tglcak'] = $tglcak;
            $resultmua['total_brdcak'] = $skor_bTinggal;
            $resultmua['brd_jjgcak'] = $brdPerjjg;
            $resultmua['skor_brdcak'] = skor_brd_ma($brdPerjjg);
            $resultmua['bhts_scak'] = $bhts_scak;
            $resultmua['bhtm1cak'] = $bhtm1cak;
            $resultmua['bhtm2cak'] = $bhtm2cak;
            $resultmua['bhtm3cak'] = $bhtm3cak;
            $resultmua['buah_jjgcak'] = $sumPerBH;
            $resultmua['skor_bhcak'] = skor_buah_Ma($sumPerBH);
            $resultmua['palepah_pokokcak'] = $palepah_pokokcak;
            $resultmua['palepah_percak'] = $perPl;
            $resultmua['skor_pscak'] = skor_palepah_ma($perPl);
            $resultmua['skor_akhircak'] = $totalSkorEstancak;
            $resultmua['check_datacak'] = $datacak;
            $resultmua['est'] = 'PT.MUA';
            $resultmua['afd'] = 'est';
            $resultmua['mutuancak'] = '------------------------------------------------------';
            $resultmua['tph_sampleNew'] = $tph_sampleNew;
            $resultmua['total_brdtrans'] = $total_brdtrans;
            $resultmua['total_buahtrans'] = $total_buahtrans;
            $resultmua['total_brdperTPHtrans'] = $brdPertph;
            $resultmua['total_buahPerTPHtrans'] = $buahPerTPH;
            $resultmua['skor_brdPertphtrans'] = skor_brd_tinggal($brdPertph);
            $resultmua['skor_buahPerTPHtrans'] = skor_buah_tinggal($buahPerTPH);
            $resultmua['totalSkortrans'] = $totalSkorEsttrans;
            $resultmua['check_datatrans'] = 'ada';
            $resultmua['mututrans'] = '------------------------------------------------------';
            $resultmua['tph_baris_bloksbh'] = $tph_baris_bloksbh;
            $resultmua['sampleJJG_totalbh'] = $sampleJJG_totalbh;
            $resultmua['total_mentahbh'] = $total_mentahbh;
            $resultmua['total_perMentahbh'] = $PerMth;
            $resultmua['total_masakbh'] = $jml_mtg;
            $resultmua['total_perMasakbh'] = $PerMsk;
            $resultmua['total_overbh'] = $total_overbh;
            $resultmua['total_perOverbh'] = $PerOver;
            $resultmua['total_abnormalbh'] = $sum_abnor;
            $resultmua['perAbnormalbh'] = $PerAbr;
            $resultmua['total_jjgKosongbh'] = $sum_kosongjjg;
            $resultmua['total_perKosongjjgbh'] = $Perkosongjjg;
            $resultmua['total_vcutbh'] = $total_vcutbh;
            $resultmua['perVcutbh'] = $PerVcut;
            $resultmua['total_krbh'] = $jum_krbh;
            $resultmua['jum_krbh'] = $total_kr;
            $resultmua['persen_krbh'] = $per_kr;
            $resultmua['skor_mentahbh'] = skor_buah_mentah_mb($PerMth);
            $resultmua['skor_masakbh'] = skor_buah_masak_mb($PerMsk);
            $resultmua['skor_overbh'] = skor_buah_over_mb($PerOver);
            $resultmua['skor_jjgKosongbh'] = skor_jangkos_mb($Perkosongjjg);
            $resultmua['skor_vcutbh'] = skor_vcut_mb($PerVcut);
            $resultmua['skor_krbh'] = skor_abr_mb($per_kr);
            $resultmua['total_buahcak'] = $totalSkorBuah;
            $resultmua['TOTAL_SKORbh'] = $totalSkorBuah;
            $resultmua['check_databh'] = 'ada';

            // dd($resultmua);


            $rekap[3]['MUA']['estate'] = $resultmua;

            foreach ($rekap as $key => $value) {
                if ($key == 3) {
                    // Save the "wilayah" value if needed
                    $wilayahValue = $value["wilayah"];

                    // Unset the "wilayah" key
                    unset($rekap[$key]["wilayah"]);

                    // Assign "wilayah" at the end of the array
                    $rekap[$key]["wilayah"] = $wilayahValue;
                }
            }

            // dd($rekap);
        } else {
            $resultmua = [];
        }


        $getwilx = [];

        foreach ($rekap as $key => $value) {
            if (isset($value['wilayah'])) {
                $getwilx[$key] = $value['wilayah']['wil'];
            }
        }



        // Memisahkan array berdasarkan wilayah, estate, dan afdeling
        $array_wilayah = [];
        $array_estate = [];
        $array_afdeling = [];



        foreach ($rekap as $key => $value) {
            // Array wilayah
            if (isset($value['wilayah'])) {
                $array_wilayah[$key] = ['wilayah' => $value['wilayah']];
            }

            // Array estate dan afdeling
            foreach ($value as $estate_key => $estate_value) {
                if ($estate_key !== 'wilayah') {
                    // Array estate
                    if (isset($estate_value['estate'])) {
                        if (!in_array($estate_key, ['SRE', 'LDE'])) {
                            $array_estate[$key][$estate_key] = ['estate' => $estate_value['estate']];
                        }
                    }

                    // Array afdeling
                    $afdelings = array_filter(array_keys($estate_value), function ($k) {
                        return in_array($k, ['OA', 'OB', 'OC', 'OD', 'OE', 'OF', 'LDE', 'SRE', 'OG']);
                    });

                    if (!empty($afdelings)) {
                        if (!isset($array_afdeling[$key])) {
                            $array_afdeling[$key] = [];
                        }
                        $array_afdeling[$key][$estate_key] = array_intersect_key($estate_value, array_flip($afdelings));
                    }
                }
            }
        }

        // dd($array_wilayah);
        // dd([
        //     'rekap' => $rekap,
        //     'array_estate' => $array_estate,
        //     'array_afdeling' => $array_afdeling,
        //     'array_wilayah' => $array_wilayah
        // ]);

        $dataReg = array();

        $ha_samplecak = 0;
        $jumlah_panencak = 0;
        $pcak = 0;
        $kcak = 0;
        $tglcak = 0;
        $bhts_scak = 0;
        $bhtm1cak = 0;
        $bhtm2cak = 0;
        $bhtm3cak = 0;
        $palepah_pokokcak = 0;
        $pokok_samplecak = 0;
        $tph_sampleNew = 0;
        $total_brdtrans = 0;
        $total_buahtrans = 0;

        $tph_baris_bloksbh = 0;
        $sampleJJG_totalbh = 0;
        $total_mentahbh = 0;
        $total_overbh = 0;
        $total_abnormalbh = 0;
        $total_jjgKosongbh = 0;
        $total_vcutbh = 0;
        $jum_krbh = 0;
        foreach ($getwilx as $keyx => $value) {
            $pokok_samplecak += $value['pokok_samplecak'];
            $ha_samplecak += $value['ha_samplecak'];
            $jumlah_panencak += $value['jumlah_panencak'];
            $pcak += $value['pcak'];
            $kcak += $value['kcak'];
            $tglcak += $value['tglcak'];
            $bhts_scak += $value['bhts_scak'];
            $bhtm1cak += $value['bhtm1cak'];
            $bhtm2cak += $value['bhtm2cak'];
            $bhtm3cak += $value['bhtm3cak'];
            $palepah_pokokcak += $value['palepah_pokokcak'];

            $tph_sampleNew += $value['tph_sampleNew'];
            $total_brdtrans += $value['total_brdtrans'];
            $total_buahtrans += $value['total_buahtrans'];

            $tph_baris_bloksbh += $value['tph_baris_bloksbh'];
            $sampleJJG_totalbh += $value['sampleJJG_totalbh'];
            $total_mentahbh += $value['total_mentahbh'];
            $total_overbh += $value['total_overbh'];
            $total_abnormalbh += $value['total_abnormalbh'];
            $total_jjgKosongbh += $value['total_jjgKosongbh'];
            $total_vcutbh += $value['total_vcutbh'];
            $jum_krbh += $value['jum_krbh'];
        }
        if ($ha_samplecak != 0) {
            $akp = round(($jumlah_panencak / $pokok_samplecak) * 100, 1);
            $datacak = 'ada';
        } else {
            $akp = 0;
            $datacak = 'kosong';
        }
        $skor_bTinggal = $pcak + $kcak + $tglcak;

        if ($jumlah_panencak != 0) {
            $brdPerjjg = round($skor_bTinggal / $jumlah_panencak, 3);
        } else {
            $brdPerjjg = 0;
        }
        $sumBH = $bhts_scak +  $bhtm1cak +  $bhtm2cak +  $bhtm3cak;
        if ($sumBH != 0) {
            $sumPerBH = round($sumBH / ($jumlah_panencak + $sumBH) * 100, 3);
        } else {
            $sumPerBH = 0;
        }
        if ($palepah_pokokcak != 0) {
            $perPl = ($palepah_pokokcak / $pokok_samplecak) * 100;
        } else {
            $perPl = 0;
        }

        if ($tph_sampleNew != 0) {
            $brdPertph = round($total_brdtrans / $tph_sampleNew, 3);
        } else {
            $brdPertph = 0;
        }
        if ($tph_sampleNew != 0) {
            $buahPerTPH = round($total_buahtrans / $tph_sampleNew, 3);
        } else {
            $buahPerTPH = 0;
        }


        $dataBLok = $tph_baris_bloksbh;
        $jml_mth = $total_mentahbh;
        $jml_mtg = $sampleJJG_totalbh - ($total_mentahbh + $total_overbh + $total_jjgKosongbh + $total_abnormalbh);

        if ($jum_krbh != 0) {
            $total_kr = round($jum_krbh / $dataBLok, 3);
        } else {
            $total_kr = 0;
        }


        $per_kr = round($total_kr * 100, 3);
        if ($jml_mth != 0) {
            $PerMth = round(($jml_mth / ($sampleJJG_totalbh - $total_abnormalbh)) * 100, 3);
        } else {
            $PerMth = 0;
        }
        if ($jml_mtg != 0) {
            $PerMsk = round(($jml_mtg / ($sampleJJG_totalbh - $total_abnormalbh)) * 100, 3);
        } else {
            $PerMsk = 0;
        }
        if ($total_overbh != 0) {
            $PerOver = round(($total_overbh / ($sampleJJG_totalbh - $total_abnormalbh)) * 100, 3);
        } else {
            $PerOver = 0;
        }
        if ($total_jjgKosongbh != 0) {
            $Perkosongjjg = round(($total_jjgKosongbh / ($sampleJJG_totalbh - $total_abnormalbh)) * 100, 3);
        } else {
            $Perkosongjjg = 0;
        }
        if ($total_vcutbh != 0) {
            $PerVcut = round(($total_vcutbh / $sampleJJG_totalbh) * 100, 3);
        } else {
            $PerVcut = 0;
        }

        if ($total_abnormalbh != 0) {
            $PerAbr = round(($total_abnormalbh / $sampleJJG_totalbh) * 100, 3);
        } else {
            $PerAbr = 0;
        }

        $totalSkorEsttrans = skor_brd_tinggal($brdPertph) + skor_buah_tinggal($buahPerTPH);
        $totalSkorEstancak =  skor_palepah_ma($perPl) + skor_buah_Ma($sumPerBH) + skor_brd_ma($brdPerjjg);
        $totalSkorBuah =  skor_buah_mentah_mb($PerMth) + skor_buah_masak_mb($PerMsk) + skor_buah_over_mb($PerOver) + skor_vcut_mb($PerVcut) + skor_jangkos_mb($Perkosongjjg) + skor_abr_mb($per_kr);

        $dataReg['pokok_samplecak'] = $pokok_samplecak;
        $dataReg['ha_samplecak'] = $ha_samplecak;
        $dataReg['jumlah_panencak'] = $jumlah_panencak;
        $dataReg['akp_rlcak'] = $akp;
        $dataReg['pcak'] = $pcak;
        $dataReg['kcak'] = $kcak;
        $dataReg['tglcak'] = $tglcak;
        $dataReg['total_brdcak'] = $skor_bTinggal;
        $dataReg['brd_jjgcak'] = $brdPerjjg;
        $dataReg['skor_brdcak'] = skor_brd_ma($brdPerjjg);
        $dataReg['bhts_scak'] = $bhts_scak;
        $dataReg['bhtm1cak'] = $bhtm1cak;
        $dataReg['bhtm2cak'] = $bhtm2cak;
        $dataReg['bhtm3cak'] = $bhtm3cak;
        $dataReg['buah_jjgcak'] = $sumPerBH;
        $dataReg['skor_bhcak'] = skor_buah_Ma($sumPerBH);
        $dataReg['palepah_pokokcak'] = $palepah_pokokcak;
        $dataReg['palepah_percak'] = $perPl;
        $dataReg['skor_pscak'] = skor_palepah_ma($perPl);
        $dataReg['skor_akhircak'] = $totalSkorEstancak;
        $dataReg['check_datacak'] = $datacak;
        $dataReg['est'] = 'Regional';
        $dataReg['afd'] = $regional;
        $dataReg['mutuancak'] = '------------------------------------------------------';
        $dataReg['tph_sampleNew'] = $tph_sampleNew;
        $dataReg['total_brdtrans'] = $total_brdtrans;
        $dataReg['total_buahtrans'] = $total_buahtrans;
        $dataReg['total_brdperTPHtrans'] = $brdPertph;
        $dataReg['total_buahPerTPHtrans'] = $buahPerTPH;
        $dataReg['skor_brdPertphtrans'] = skor_brd_tinggal($brdPertph);
        $dataReg['skor_buahPerTPHtrans'] = skor_buah_tinggal($buahPerTPH);
        $dataReg['totalSkortrans'] = $totalSkorEsttrans;
        $dataReg['check_datatrans'] = 'ada';
        $dataReg['mututrans'] = '------------------------------------------------------';
        $dataReg['tph_baris_bloksbh'] = $tph_baris_bloksbh;
        $dataReg['sampleJJG_totalbh'] = $sampleJJG_totalbh;
        $dataReg['total_mentahbh'] = $total_mentahbh;
        $dataReg['total_perMentahbh'] = $PerMth;
        $dataReg['total_masakbh'] = $jml_mtg;
        $dataReg['total_perMasakbh'] = $PerMsk;
        $dataReg['total_overbh'] = $total_overbh;
        $dataReg['total_perOverbh'] = $PerOver;
        $dataReg['total_abnormalbh'] = $sum_abnor;
        $dataReg['perAbnormalbh'] = $PerAbr;
        $dataReg['total_jjgKosongbh'] = $sum_kosongjjg;
        $dataReg['total_perKosongjjgbh'] = $Perkosongjjg;
        $dataReg['total_vcutbh'] = $total_vcutbh;
        $dataReg['perVcutbh'] = $PerVcut;
        $dataReg['total_krbh'] = $jum_krbh;
        $dataReg['jum_krbh'] = $total_kr;
        $dataReg['persen_krbh'] = $per_kr;
        $dataReg['skor_mentahbh'] = skor_buah_mentah_mb($PerMth);
        $dataReg['skor_masakbh'] = skor_buah_masak_mb($PerMsk);
        $dataReg['skor_overbh'] = skor_buah_over_mb($PerOver);
        $dataReg['skor_jjgKosongbh'] = skor_jangkos_mb($Perkosongjjg);
        $dataReg['skor_vcutbh'] = skor_vcut_mb($PerVcut);
        $dataReg['skor_krbh'] = skor_abr_mb($per_kr);
        $dataReg['total_buahcak'] = $totalSkorBuah;
        $dataReg['TOTAL_SKORbh'] = $totalSkorBuah;
        $dataReg['check_databh'] = 'ada';
        $dataReg['nama_staff'] = get_nama_rh($regional);

        // dd($dataReg);
        return  [
            'data' => $rekap,
            'data_estate' => $array_estate,
            'data_afdeling' => $array_afdeling,
            'data_wilayah' => $array_wilayah,
            'reg' => $regional,
            'bulan' => $bulan,
            'datareg' => $dataReg
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
                    $sidak_buah[$key][$key1]['nama_asisten'] = get_nama_asisten($key, $key1);
                    // foreach ($queryAsisten as $ast => $asisten) {
                    //     if ($key === $asisten['est'] && $key1 === $asisten['afd']) {
                    //         $sidak_buah[$key][$key1]['nama_asisten'] = $asisten->user->nama;
                    //     }
                    // }
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
                    $sidak_buah[$key][$key1]['nama_asisten'] = get_nama_asisten($key, $key1);
                    // foreach ($queryAsisten as $ast => $asisten) {
                    //     if ($key === $asisten['est'] && $key1 === $asisten['afd']) {
                    //         $sidak_buah[$key][$key1]['nama_asisten'] = $asisten['nama'];
                    //     }
                    // }
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

            // $em = 'EM';

            // $nama_em = '';

            // // dd($key1);
            // foreach ($queryAsisten as $ast => $asisten) {
            //     if ($key1 === $asisten['est'] && $em === $asisten['afd']) {
            //         $nama_em = $asisten['nama'];
            //     }
            // }
            $nama_em = get_nama_em($key1);
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

            // $nama_em = '';
            $newkey = 'WIL-' . convertToRoman($key);
            // // dd($newkey);
            // foreach ($queryAsisten as $ast => $asisten) {
            //     if ($newkey === $asisten['est'] && $em === $asisten['afd']) {
            //         $nama_em = $asisten['nama'] ?? '-';
            //     }
            // }
            $nama_em =   get_nama_gm($newkey);
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

        // $nama_em = '';
        $newkey = 'REG-' . convertToRoman($regional);
        // dd($newkey);
        // foreach ($queryAsisten as $ast => $asisten) {
        //     if ($newkey === $asisten['est'] && $em === $asisten['afd']) {
        //         $nama_em = $asisten['nama'] ?? '-';
        //     }
        // }
        $nama_em = get_nama_rh($newkey);
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
                    // foreach ($queryAsisten as $keyx => $valuex) if ($valuex['est'] === $key1 && $valuex['afd'] === $key2) {
                    //     $data[$key][$key1][$key2]['nama'] = $valuex['nama'] ?? '-';
                    //     break;
                    // }
                    $data[$key][$key1][$key2]['nama'] = get_nama_asisten($key1, $key2);
                    // $data[$key][$key1][$key2]['nama'] = 'nama';
                    $data[$key][$key1][$key2]['total_score'] = $value2['All_skor'];
                    $data[$key][$key1][$key2]['est'] = $key1;
                    $data[$key][$key1][$key2]['afd'] = $key2;
                    $data[$key][$key1][$key2]['bgcolor'] = 'white';

                    // $totale = $totalest;
                }
                // $nama = '-';
                // foreach ($queryAsisten as $keyx => $valuex) if ($valuex['est'] === $key1 && $valuex['afd'] === 'EM') {
                //     $nama = $valuex['nama'] ?? '-';
                //     break;
                // }
                $nama = get_nama_em($key1);
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
                        $sidakbuahmuah[$key][$key1]['nama_asisten'] = get_nama_asisten($key, $key1);
                        // foreach ($queryAsisten as $ast => $asisten) {
                        //     if ($key === $asisten['est'] && $key1 === $asisten['afd']) {
                        //         $sidakbuahmuah[$key][$key1]['nama_asisten'] = $asisten['nama'];
                        //     }
                        // }
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
                        $sidakbuahmuah[$key][$key1]['nama_asisten'] = get_nama_asisten($key, $key1);
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

                // $em = 'EM';

                // $nama_em = '';

                // // dd($key1);
                // foreach ($queryAsisten as $ast => $asisten) {
                //     if ($key1 === $asisten['est'] && $em === $asisten['afd']) {
                //         $nama_em = $asisten['nama'];
                //     }
                // }
                $nama_em = get_nama_em($key1);
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

            // $em = 'EM';

            // $nama_em = '';

            // // dd($key1);
            // foreach ($queryAsisten as $ast => $asisten) {
            //     if ($key1 === $asisten['est'] && $em === $asisten['afd']) {
            //         $nama_em = $asisten['nama'];
            //     }
            // }
            $nama_em = get_nama_em($key1);
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

//helper maps
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
if (!function_exists('countNonZeroValues')) {
    function countNonZeroValues($array)
    {
        // Filter out elements equal to 0
        $filteredArray = array_filter($array, function ($value) {
            return $value !== 0;
        });

        // Count the remaining elements
        $count = count($filteredArray);

        return $count;
    }
}

if (!function_exists('get_nama_asisten')) {
    function get_nama_asisten($est, $afd)
    {
        if (in_array($est, ['LDE', 'SRE'])) {
            $new_afd = 'OA';
        } else {
            $new_afd = $afd;
        }

        // $query = DB::connection('mysql2')->table('asisten_qc')
        //     ->where('est', $est)
        //     ->where('afd', $new_afd)
        //     ->first();
        $query = Asistenqc::with('User')
            ->where('est', $est)
            ->where('afd', $new_afd)
            ->first();
        // dd($query[0]);
        // Check if the query result is not null
        if ($query !== null) {
            return $query->user->nama_lengkap ?? 'Vacant';
        } else {
            return '-';  // Return null or a default value if no record is found
        }
    }
}

if (!function_exists('get_nama_em')) {
    function get_nama_em($est)
    {
        if ($est === 'MUA') {
            $new_est = 'PT.MUA';
        } else {
            $new_est = $est;
        }
        $query = Asistenqc::with('User')
            ->where('est', $new_est)
            ->where('afd', 'EM')
            ->first();
        if ($query !== null) {
            return $query->user->nama_lengkap ?? 'Vacant';
        } else {
            return 'Nama tidak ditemukan';
        }
    }
}
if (!function_exists('get_nama_gm')) {
    function get_nama_gm($wil)
    {
        $int_num = intval($wil);
        if ($int_num == 0) {
            return 'Nama tidak ditemukan di database';
        }
        $new_will = 'WIL-' . convertToRoman($int_num);


        $query = Asistenqc::with('User')
            ->where('est', $new_will)
            ->where('afd', 'GM')
            ->first();
        if ($query !== null) {
            return $query->user->nama_lengkap ?? 'Vacant';
        } else {
            return 'Nama tidak ditemukan';
        }
    }
}
if (!function_exists('get_nama_rh')) {
    function get_nama_rh($rh)
    {
        $int_num = intval($rh);

        if ($int_num == 0) {
            return 'Nama tidak ditemukan di database';
        }
        $new_will = 'REG-' . convertToRoman($int_num);
        $query = Asistenqc::with('User')
            ->where('est', $new_will)
            ->where('afd', 'RH')
            ->first();

        // dd($query, $new_will);
        if ($query !== null) {
            return $query->user->nama_lengkap ?? 'Vacant';
        } else {
            return 'Nama tidak ditemukan';
        }
    }
}
//helper halaman sidaktph perbulan
if (!function_exists('get_sidaktph_perbulan')) {
    function get_sidaktph_perbulan($tanggal, $regional)
    {
        $newparamsdate = '2024-03-01';
        $tanggalDateTime = new DateTime($tanggal);
        // dd($tanggalDateTime);
        $newparamsdateDateTime = new DateTime($newparamsdate);
        // dd($newparamsdateDateTime);

        if ($tanggalDateTime >= $newparamsdateDateTime) {
            $dataparams = 'new';
        } else {
            $dataparams = 'old';
        }

        // dd($dataparams);
        $ancakFA = DB::connection('mysql2')
            ->table('sidak_tph')
            ->select(
                "sidak_tph.*",
                DB::raw('DATE_FORMAT(sidak_tph.datetime, "%Y-%m-%d") as tanggal'),
                DB::raw('DATE_FORMAT(sidak_tph.datetime, "%M") as bulan'),
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
            ->where('sidak_tph.datetime', 'like', '%' . $tanggal . '%')
            ->orderBy('status', 'asc')
            ->get();

        $ancakFA = $ancakFA->groupBy(['est', 'afd', 'statuspanen', 'tanggal', 'blok']);
        $ancakFA = json_decode($ancakFA, true);



        // dd($ancakFA);

        $dateString = $tanggal;
        $dateParts = date_parse($dateString);
        $year = $dateParts['year'];
        $month = $dateParts['month'];

        $year = $year; // Replace with the desired year
        $month = $month;   // Replace with the desired month (September in this example)

        if ($regional == 3) {

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
        } else {
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
        }


        // dd($weeks);

        $result = [];

        // Iterate through the original array
        foreach ($ancakFA as $mainKey => $mainValue) {
            $result[$mainKey] = [];

            foreach ($mainValue as $subKey => $subValue) {
                $result[$mainKey][$subKey] = [];

                foreach ($subValue as $dateKey => $dateValue) {
                    // Remove 'H+' prefix if it exists
                    $numericIndex = is_numeric($dateKey) ? $dateKey : (strpos($dateKey, 'H+') === 0 ? substr($dateKey, 2) : $dateKey);

                    if (!isset($result[$mainKey][$subKey][$numericIndex])) {
                        $result[$mainKey][$subKey][$numericIndex] = [];
                    }

                    foreach ($dateValue as $statusKey => $statusValue) {
                        // Handle 'H+' prefix in status
                        $statusIndex = is_numeric($statusKey) ? $statusKey : (strpos($statusKey, 'H+') === 0 ? substr($statusKey, 2) : $statusKey);

                        if (!isset($result[$mainKey][$subKey][$numericIndex][$statusIndex])) {
                            $result[$mainKey][$subKey][$numericIndex][$statusIndex] = [];
                        }

                        foreach ($statusValue as $blokKey => $blokValue) {
                            $result[$mainKey][$subKey][$numericIndex][$statusIndex][$blokKey] = $blokValue;
                        }
                    }
                }
            }
        }

        // result by statis week 
        $newResult = [];

        foreach ($result as $key => $value) {
            $newResult[$key] = [];

            foreach ($value as $estKey => $est) {
                $newResult[$key][$estKey] = [];

                foreach ($est as $statusKey => $status) {
                    $newResult[$key][$estKey][$statusKey] = [];

                    foreach ($weeks as $weekKey => $week) {
                        $newStatus = [];

                        foreach ($status as $date => $data) {
                            if (strtotime($date) >= strtotime($week["start"]) && strtotime($date) <= strtotime($week["end"])) {
                                $newStatus[$date] = $data;
                            }
                        }

                        if (!empty($newStatus)) {
                            $newResult[$key][$estKey][$statusKey]["week" . ($weekKey + 1)] = $newStatus;
                        }
                    }
                }
            }
        }

        // result by week status 
        $WeekStatus = [];

        foreach ($result as $key => $value) {
            $WeekStatus[$key] = [];

            foreach ($value as $estKey => $est) {
                $WeekStatus[$key][$estKey] = [];

                foreach ($weeks as $weekKey => $week) {
                    $WeekStatus[$key][$estKey]["week" . ($weekKey + 0)] = [];

                    foreach ($est as $statusKey => $status) {
                        $newStatus = [];

                        foreach ($status as $date => $data) {
                            if (strtotime($date) >= strtotime($week["start"]) && strtotime($date) <= strtotime($week["end"])) {
                                $newStatus[$date] = $data;
                            }
                        }

                        if (!empty($newStatus)) {
                            $WeekStatus[$key][$estKey]["week" . ($weekKey + 0)][$statusKey] = $newStatus;
                        }
                    }
                }
            }
        }

        // dd($WeekStatus);



        $qrafd = DB::connection('mysql2')->table('afdeling')
            ->select(
                'afdeling.id',
                'afdeling.nama',
                'estate.est'
            ) //buat mengambil data di estate db dan willayah db
            ->join('estate', 'estate.id', '=', 'afdeling.estate') //kemudian di join untuk mengambil est perwilayah
            ->get();
        $qrafd = json_decode($qrafd, true);
        $queryEstereg = DB::connection('mysql2')->table('estate')
            ->select('estate.*')
            // ->whereNotIn('estate.est', ['PLASMA', 'CWS1', 'SRS'])
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->where('estate.emp', '!=', 1)
            ->where('wil.regional', $regional)
            ->get();
        $queryEstereg = json_decode($queryEstereg, true);

        // dd($queryEstereg);
        $defaultNew = array();

        foreach ($queryEstereg as $est) {
            foreach ($qrafd as $afd) {
                if ($est['est'] == $afd['est']) {
                    $defaultNew[$est['est']][$afd['nama']] = 0;
                }
            }
        }



        foreach ($defaultNew as $key => $estValue) {
            foreach ($estValue as $monthKey => $monthValue) {
                foreach ($newResult as $dataKey => $dataValue) {

                    if ($dataKey == $key) {
                        foreach ($dataValue as $dataEstKey => $dataEstValue) {

                            if ($dataEstKey == $monthKey) {
                                $defaultNew[$key][$monthKey] = $dataEstValue;
                            }
                        }
                    }
                }
            }
        }

        // dd($queryEstereg, $qrafd);

        $defaultWeek = array();
        foreach ($queryEstereg as $est) {
            foreach ($qrafd as $afd) {
                if ($est['est'] == $afd['est']) {
                    if (in_array($est['est'], ['LDE', 'SRE'])) {
                        $defaultWeek[$est['est']][$afd['est']] = 0;
                    } else {
                        $defaultWeek[$est['est']][$afd['nama']] = 0;
                    }
                }
            }
        }

        // dd($defaultWeek);
        foreach ($defaultWeek as $key => $estValue) {
            foreach ($estValue as $monthKey => $monthValue) {
                foreach ($WeekStatus as $dataKey => $dataValue) {

                    if ($dataKey == $key) {
                        foreach ($dataValue as $dataEstKey => $dataEstValue) {

                            if ($dataEstKey == $monthKey) {
                                $defaultWeek[$key][$monthKey] = $dataEstValue;
                            }
                        }
                    }
                }
            }
        }


        $newDefaultWeek = [];

        foreach ($defaultWeek as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $key1 => $value1) {
                    if (is_array($value1)) {
                        foreach ($value1 as $subKey => $subValue) {
                            if (is_array($subValue)) {
                                // Check if both key 0 and key 1 exist
                                $hasKeyZero = isset($subValue[0]);
                                $hasKeyOne = isset($subValue[1]);

                                // Merge key 0 into key 1
                                if ($hasKeyZero && $hasKeyOne) {
                                    $subValue[1] = array_merge_recursive((array)$subValue[1], (array)$subValue[0]);
                                    unset($subValue[0]);
                                } elseif ($hasKeyZero && !$hasKeyOne) {
                                    // Create key 1 and merge key 0 into it
                                    $subValue[1] = $subValue[0];
                                    unset($subValue[0]);
                                }

                                // Check if keys 1 through 7 don't exist, add them with a default value of 0
                                for ($i = 1; $i <= 7; $i++) {
                                    if (!isset($subValue[$i])) {
                                        $subValue[$i] = 0;
                                    }
                                }

                                // Ensure key 8 exists, and if not, create it with a default value of an empty array
                                if (!isset($subValue[8])) {
                                    $subValue[8] = 0;
                                }

                                // Check if keys higher than 8 exist, merge them into index 8
                                for ($i = 9; $i <= 100; $i++) {
                                    if (isset($subValue[$i])) {
                                        $subValue[8] = array_merge_recursive((array)$subValue[8], (array)$subValue[$i]);
                                        unset($subValue[$i]);
                                    }
                                }
                            }
                            $newDefaultWeek[$key][$key1][$subKey] = $subValue;
                        }
                    } else {
                        // Check if $value1 is equal to 0 and add "week1" to "week5" keys
                        if ($value1 === 0) {
                            $newDefaultWeek[$key][$key1] = [];
                            for ($i = 1; $i <= 5; $i++) {
                                $weekKey = "week" . $i;
                                $newDefaultWeek[$key][$key1][$weekKey] = [];
                                for ($j = 1; $j <= 8; $j++) {
                                    $newDefaultWeek[$key][$key1][$weekKey][$j] = 0;
                                }
                            }
                        } else {
                            $newDefaultWeek[$key][$key1] = $value1;
                        }
                    }
                }
            } else {
                $newDefaultWeek[$key] = $value;
            }
        }
        // dd($newDefaultWeek['Plasma1']['WIL-III']);
        // dd($newDefaultWeek);

        function removeZeroFromDatetime2(&$array)
        {
            foreach ($array as $key => &$value) {
                if (is_array($value)) {
                    foreach ($value as $key1 => &$value2) {
                        if (is_array($value2)) {
                            foreach ($value2 as $key2 => &$value3) {
                                if (is_array($value3)) {
                                    foreach ($value3 as $key3 => &$value4) if (is_array($value4)) {
                                        foreach ($value4 as $key4 => $value5) {
                                            if ($key4 === 0 && $value5 === 0) {
                                                unset($value4[$key4]); // Unset the key 0 => 0 within the current nested array
                                            }
                                            removeZeroFromDatetime2($value4);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        removeZeroFromDatetime2($newDefaultWeek);

        function filterEmptyWeeks(&$array)
        {
            foreach ($array as $key => &$value) {
                if (is_array($value)) {
                    filterEmptyWeeks($value); // Recursively check nested arrays
                    if (empty($value) && $key !== 'week') {
                        unset($array[$key]);
                    }
                }
            }
        }

        // dd($defaultWeek);
        // Call the function on your array
        filterEmptyWeeks($defaultWeek);




        // dd($defaultWeek);
        $dividen = [];

        foreach ($defaultWeek as $key => $value) {
            foreach ($value as $key1 => $value1) if (is_array($value1)) {
                foreach ($value1 as $key2 => $value2) if (is_array($value2)) {

                    $dividenn = count($value1);
                }
                $dividen[$key][$key1]['dividen'] = $dividenn;
            } else {
                $dividen[$key][$key1]['dividen'] = 0;
            }
        }
        // dd($newDefaultWeek['BKE']['OA']);

        // dd($newDefaultWeek['Plasma1']['WIL-III']);
        $newSidak = array();
        $asisten_qc = DB::connection('mysql2')
            ->Table('asisten_qc')
            ->get();
        $asisten_qc = json_decode($asisten_qc, true);
        // dd($newDefaultWeek['SLE']['OA']);
        $devest = 0;
        foreach ($newDefaultWeek as $key => $value) {
            $dividen_afd = 0;
            $total_skoreest = 0;
            $tot_estAFd = 0;
            $new_dvdAfd = 0;
            $new_dvdAfdest = 0;
            $total_estkors = 0;
            $total_skoreafd = 0;
            $totskor_brd2 = 0;
            $totskor_janjang2 = 0;
            $tot_brd4 = 0;
            $tod_janjang4 = 0;

            $deviden = 0;
            $devest = count($value);
            // dd($devest);
            // dd($value);

            foreach ($value as $key1 => $value2)  if (is_array($value2)) {

                $tot_afdscore = 0;
                $totskor_brd1 = 0;
                $totskor_janjang1 = 0;
                $total_skoreest = 0;
                $newpembagi1 = 0;
                $tot_brd3 = 0;
                $tod_janjang3 = 0;
                $v2check4 = 0;
                foreach ($value2 as $key2 => $value3) {


                    $total_brondolan = 0;
                    $total_janjang = 0;
                    $tod_brd = 0;
                    $tod_jjg = 0;
                    $totskor_brd = 0;
                    $totskor_janjang = 0;
                    $tot_brdxm = 0;
                    $tod_janjangxm = 0;
                    $v2check3 = 0;

                    foreach ($value3 as $key3 => $value4) if (is_array($value4)) {
                        $tph1 = 0;
                        $jalan1 = 0;
                        $bin1 = 0;
                        $karung1 = 0;
                        $buah1 = 0;
                        $restan1 = 0;
                        $v2check2 = 0;

                        foreach ($value4 as $key4 => $value5) if (is_array($value5)) {
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
                                $sum_all_restan_unreported = 0;

                                foreach ($value6 as $key6 => $value7) {
                                    // dd($value7);
                                    // dd($value7);
                                    $sum_bt_tph += $value7['bt_tph'];
                                    $sum_bt_jalan += $value7['bt_jalan'];
                                    $sum_bt_bin += $value7['bt_bin'];
                                    $sum_jum_karung += $value7['jum_karung'];


                                    $sum_buah_tinggal += $value7['buah_tinggal'];
                                    $sum_restan_unreported += $value7['restan_unreported'];
                                }
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
                            }

                            $newSidak[$key][$key1][$key2][$key3][$key4]['tph'] = $tph;
                            $newSidak[$key][$key1][$key2][$key3][$key4]['jalan'] = $jalan;
                            $newSidak[$key][$key1][$key2][$key3][$key4]['bin'] = $bin;
                            $newSidak[$key][$key1][$key2][$key3][$key4]['karung'] = $karung;

                            $newSidak[$key][$key1][$key2][$key3][$key4]['buah'] = $buah;
                            $newSidak[$key][$key1][$key2][$key3][$key4]['restan'] = $restan;
                            $newSidak[$key][$key1][$key2][$key3][$key4]['v2check'] = $v2check;

                            $tph1 += $tph;
                            $jalan1 += $jalan;
                            $bin1 += $bin;
                            $karung1 += $karung;
                            $buah1 += $buah;
                            $restan1 += $restan;
                            $v2check2 += $v2check;
                        }
                        // dd($key3);
                        $status_panen = $key3;
                        // dd($tanggal);
                        if ($dataparams === 'new') {
                            [$panen_brd, $panen_jjg] = calculatePanennew($status_panen);
                        } else {
                            [$panen_brd, $panen_jjg] = calculatePanen($status_panen);
                        }



                        // untuk brondolan gabungan dari bt-tph,bt-jalan,bt-bin,jum-karung 
                        $total_brondolan =  round(($tph1 + $jalan1 + $bin1 + $karung1) * $panen_brd / 100, 1);
                        $total_janjang =  round(($buah1 + $restan1) * $panen_jjg / 100, 1);
                        $tod_brd = $tph1 + $jalan1 + $bin1 + $karung1;
                        $tod_jjg = $buah1 + $restan1;
                        $newSidak[$key][$key1][$key2][$key3]['tphx'] = $tph1;
                        $newSidak[$key][$key1][$key2][$key3]['jalan'] = $jalan1;
                        $newSidak[$key][$key1][$key2][$key3]['bin'] = $bin1;
                        $newSidak[$key][$key1][$key2][$key3]['karung'] = $karung1;
                        $newSidak[$key][$key1][$key2][$key3]['tot_brd'] = $tod_brd;

                        $newSidak[$key][$key1][$key2][$key3]['buah'] = $buah1;
                        $newSidak[$key][$key1][$key2][$key3]['restan'] = $restan1;
                        $newSidak[$key][$key1][$key2][$key3]['skor_brd'] = $total_brondolan;
                        $newSidak[$key][$key1][$key2][$key3]['skor_janjang'] = $total_janjang;
                        $newSidak[$key][$key1][$key2][$key3]['tod_jjg'] = $tod_jjg;
                        $newSidak[$key][$key1][$key2][$key3]['v2check2'] = $v2check2;

                        $totskor_brd += $total_brondolan;
                        $totskor_janjang += $total_janjang;
                        $tot_brdxm += $tod_brd;
                        $tod_janjangxm += $tod_jjg;
                        $v2check3 += $v2check2;
                    } else {
                        $newSidak[$key][$key1][$key2][$key3]['tphx'] = 0;
                        $newSidak[$key][$key1][$key2][$key3]['jalan'] = 0;
                        $newSidak[$key][$key1][$key2][$key3]['bin'] = 0;
                        $newSidak[$key][$key1][$key2][$key3]['karung'] = 0;

                        $newSidak[$key][$key1][$key2][$key3]['buah'] = 0;
                        $newSidak[$key][$key1][$key2][$key3]['restan'] = 0;

                        $newSidak[$key][$key1][$key2][$key3]['skor_brd'] = 0;
                        $newSidak[$key][$key1][$key2][$key3]['skor_janjang'] = 0;
                        $newSidak[$key][$key1][$key2][$key3]['tot_brd'] = 0;
                        $newSidak[$key][$key1][$key2][$key3]['tod_jjg'] = 0;
                        $newSidak[$key][$key1][$key2][$key3]['v2check2'] = 0;
                    }


                    $total_estkors = $totskor_brd + $totskor_janjang;
                    if ($total_estkors != 0) {

                        $checkscore = 100 - ($total_estkors);

                        if ($checkscore < 0) {
                            $newscore = 0;
                            $newSidak[$key][$key1][$key2]['mines'] = 'ada';
                        } else {
                            $newscore = $checkscore;
                            $newSidak[$key][$key1][$key2]['mines'] = 'tidak';
                        }

                        $newSidak[$key][$key1][$key2]['all_score'] = $newscore;
                        $newSidak[$key][$key1][$key2]['check_data'] = 'ada';

                        $total_skoreafd = $newscore;
                        $newpembagi = 1;
                    } else if ($v2check3 != 0) {
                        $checkscore = 100 - ($total_estkors);

                        if ($checkscore < 0) {
                            $newscore = 0;
                            $newSidak[$key][$key1][$key2]['mines'] = 'ada';
                        } else {
                            $newscore = $checkscore;
                            $newSidak[$key][$key1][$key2]['mines'] = 'tidak';
                        }
                        $newSidak[$key][$key1][$key2]['all_score'] = 100 - ($total_estkors);
                        $newSidak[$key][$key1][$key2]['check_data'] = 'ada';

                        $total_skoreafd = $newscore;

                        $newpembagi = 1;
                    } else {
                        $newSidak[$key][$key1][$key2]['all_score'] = 0;
                        $newSidak[$key][$key1][$key2]['check_data'] = 'null';
                        $total_skoreafd = 0;
                        $newpembagi = 0;
                    }
                    // $newSidak[$key][$key1][$key2]['all_score'] = 100 - ($total_estkors);
                    $newSidak[$key][$key1][$key2]['total_brd'] = $tot_brdxm;
                    $newSidak[$key][$key1][$key2]['total_brdSkor'] = $totskor_brd;
                    $newSidak[$key][$key1][$key2]['total_janjang'] = $tod_janjangxm;
                    $newSidak[$key][$key1][$key2]['total_janjangSkor'] = $totskor_janjang;
                    $newSidak[$key][$key1][$key2]['total_skor'] = $total_skoreafd;
                    $newSidak[$key][$key1][$key2]['janjang_brd'] = $totskor_brd + $totskor_janjang;
                    $newSidak[$key][$key1][$key2]['v2check3'] = $v2check3;
                    $newSidak[$key][$key1][$key2]['newpembagi'] = $newpembagi;

                    $totskor_brd1 += $totskor_brd;
                    $totskor_janjang1 += $totskor_janjang;
                    $total_skoreest += $total_skoreafd;
                    $newpembagi1 += $newpembagi;
                    $tot_brd3 += $tot_brdxm;
                    $tod_janjang3 += $tod_janjangxm;
                    $v2check4 += $v2check3;
                }



                // $namaGM = '-';
                // foreach ($asisten_qc as $asisten) {

                //     // dd($asisten);
                //     if ($asisten['est'] == $key && $asisten['afd'] == $key1) {
                //         $namaGM = $asisten['nama'];
                //         break;
                //     }
                // }
                $namaGM = get_nama_asisten($key, $key1);

                $deviden = count($value2);


                if ($newpembagi1 != 0) {
                    $tot_afdscore = round($total_skoreest / $newpembagi1, 1);
                } else {
                    $tot_afdscore = 0;  # code...
                }
                if ($newpembagi1 != 0) {
                    $estbagi = 1;
                } else {
                    $estbagi = 0;
                }
                if ($v2check4 == 0) {
                    $newdivsafd = 0;
                } else {
                    $newdivsafd = 1;
                }
                // $newSidak[$key][$key1]['deviden'] = $deviden;
                $newSidak[$key][$key1]['afdeling'] = [
                    'total_score' => $tot_afdscore,
                    'total_brd' => $totskor_brd1,
                    'total_janjang' => $totskor_janjang1,
                    'new_deviden' => 1,
                    'asisten' => $namaGM,
                    'total_skor' => $total_skoreest,
                    'est' => $key,
                    'afd' => $key1,
                    'devidenest' => $devest,
                    'newdivsafd' => $newdivsafd,
                ];


                $tot_estAFd += $tot_afdscore;
                $new_dvdAfdest += $estbagi;
                $totskor_brd2 += $totskor_brd1;
                $totskor_janjang2 += $totskor_janjang1;
                $tot_brd4 += $tot_brd3;
                $tod_janjang4 += $tod_janjang3;
            } else {
                // $namaGM = '-';
                // foreach ($asisten_qc as $asisten) {

                //     // dd($asisten);
                //     if ($asisten['est'] == $key && $asisten['afd'] == $key1) {
                //         $namaGM = $asisten['nama'];
                //         break;
                //     }
                // }
                $namaGM = get_nama_asisten($key, $key1);
                $newSidak[$key][$key1]['afdeling'] = [
                    'deviden' => 0,
                    'total_score' => 0,
                    'total_brd' => 0,
                    'new_deviden' => 0,
                    'total_janjang' => 0,
                    'asisten' => $namaGM,
                ];
            }
            if ($new_dvdAfdest != 0) {
                $total_skoreest = round($tot_estAFd / $new_dvdAfdest, 1);
            } else {
                $total_skoreest = 0;
            }

            // dd($value);

            // $namaGM = '-';
            // foreach ($asisten_qc as $asisten) {
            //     if ($asisten['est'] == $key && $asisten['afd'] == 'EM') {
            //         $namaGM = $asisten['nama'];
            //         break;
            //     }
            // }
            $namaGM = get_nama_em($key);
            if ($new_dvdAfdest != 0) {

                $devidens = 1;
            } else {

                $devidens = 0;
            }
            $newSidak[$key]['estate'] = [
                'total_skorest' => $tot_estAFd,
                'score_estate' => $total_skoreest,
                'asisten' => $namaGM,
                'estate' => $key,
                'afd' => 'GM',
                'dividen' => $new_dvdAfdest,
                'afdeling' => $devest,
                'deviden' => $devidens,
                'brd' => $tot_brd4,
                'buah' => $tod_janjang4,
            ];
        }

        // dd($newSidak);

        $week1 = []; // Initialize the new array
        foreach ($newSidak as $key => $value) {
            $estateValues = []; // Initialize an array to accumulate values for estate
            $est_brd = 0;
            $total_weeks = 0;
            $deviden = 0;
            $devnew = 0;
            $skor_akhir = 0;
            $nulldata = [];
            // dd($value);
            $afdcount = $value['estate']['afdeling'];
            $scoreest = 0;
            foreach ($value as $subKey => $subValue) {
                if (is_array($subValue) && isset($subValue['week1'])) {
                    $week1Data = $subValue['week1']; // Access "week1" data

                    // dd($week1Data);
                    foreach ($weeks as $keywk => $value) if ($keywk == 1) {
                        $start = $value['start'];
                        $end = $value['end'];
                    }
                    // week for afdeling 
                    // dd($week1Data);
                    if ($week1Data['all_score'] == 0 && $week1Data['v2check3'] != 0  && $week1Data['mines'] === 'ada') {
                        # code...
                        $total_score = 0;
                        $data = 'ada';
                    } else if ($week1Data['all_score'] == 0 && $week1Data['v2check3'] != 0 && $week1Data['mines'] === 'tidak') {
                        # code...
                        $total_score = 100;
                        $data = 'ada';
                    } else if ($week1Data['all_score'] == 0 && $week1Data['check_data'] == 'null') {
                        $total_score = 0;
                        $data = 'kosong';
                    } else {
                        $total_score =  round($week1Data['all_score'], 1);
                        $data = 'ada';
                    }

                    $week1Flat = [
                        'est' => $key,
                        'afd' => $subKey,
                        'total_score' => $total_score,
                        'kategori' => $week1Data['check_data'],
                        'inspek' => $data
                    ];

                    // Extract tphx values for keys 1 to 8 and flatten them
                    for ($i = 1; $i <= 8; $i++) {
                        $tphxKey = $i;
                        $tphxValue = $week1Data[$tphxKey]['tphx'];
                        $jalan = $week1Data[$tphxKey]['jalan'];
                        $bin = $week1Data[$tphxKey]['bin'];
                        $karung = $week1Data[$tphxKey]['karung'];
                        $buah = $week1Data[$tphxKey]['buah'];
                        $restan = $week1Data[$tphxKey]['restan'];
                        $skor_brd = $week1Data[$tphxKey]['skor_brd'];
                        $skor_janjang = $week1Data[$tphxKey]['skor_janjang'];
                        $tot_brd = $week1Data[$tphxKey]['tot_brd'];
                        $tod_jjg = $week1Data[$tphxKey]['tod_jjg'];

                        $week1Flat["tph$i"] = $tphxValue;
                        $week1Flat["jalan$i"] = $jalan;
                        $week1Flat["bin$i"] = $bin;
                        $week1Flat["karung$i"] = $karung;
                        $week1Flat["buah$i"] = $buah;
                        $week1Flat["restan$i"] = $restan;
                        $week1Flat["skor_brd$i"] = $skor_brd;
                        $week1Flat["skor_janjang$i"] = round($skor_janjang, 1);
                        $week1Flat["tot_brd$i"] = $tot_brd;
                        $week1Flat["tod_jjg$i"] = $tod_jjg;
                        if (!isset($estateValues["tph$i"])) {
                            $estateValues["tph$i"] = 0;
                            $estateValues["jalan$i"] = 0;
                            $estateValues["bin$i"] = 0;
                            $estateValues["karung$i"] = 0;
                            $estateValues["buah$i"] = 0;
                            $estateValues["restan$i"] = 0;
                            $estateValues["skor_brd$i"] = 0;
                            $estateValues["skor_janjang$i"] = 0;
                            $estateValues["tot_brd$i"] = 0;
                            $estateValues["tod_jjg$i"] = 0;
                        }

                        if ($dataparams === 'new') {
                            [$panen_brd, $panen_jjg] = calculatePanennew($i);
                        } else {
                            [$panen_brd, $panen_jjg] = calculatePanen($i);
                        }

                        // [$panen_brd, $panen_jjg] = calculatePanen($i);

                        $estateValues["tph$i"] += $tphxValue;
                        $estateValues["jalan$i"] += $jalan;
                        $estateValues["bin$i"] += $bin;
                        $estateValues["karung$i"] += $karung;
                        $estateValues["buah$i"] += $buah;
                        $estateValues["restan$i"] += $restan;
                        $estateValues["skor_brd$i"] += round(($tot_brd * $panen_brd) / 100, 1);
                        $estateValues["skor_janjang$i"] += round(($tod_jjg * $panen_jjg) / 100, 1);
                        $estateValues["tot_brd$i"] += $tot_brd;
                        $estateValues["tod_jjg$i"] += $tod_jjg;
                    }
                    $total_weeks += round($week1Data['all_score'], 2);
                    $deviden += $subValue['afdeling']['new_deviden'];
                    $devnew = $subValue['afdeling']['devidenest'];

                    // Add the flattened array to the result
                    $week1[] = $week1Flat;
                    $nulldata[] .= $week1Data['check_data'];
                    $v2check3 = $week1Data['v2check3'];
                    $scoreest += $total_score;
                }
            }


            $counts = array_count_values($nulldata);

            // Subtract the count of 'ada' from the total count
            $getnull = count($nulldata) - ($counts['ada'] ?? 0);

            // Set getnull to 0 if it's negative (in case 'ada' occurs more times than the array size)
            $getnull = max(0, $getnull);
            if ($devnew != 0 && $scoreest != 0) {
                // $skor_akhir = round($total_weeks / $deviden, 1);
                $skor_akhir = round($scoreest / $devnew, 2);
            } else {
                $skor_akhir = '-';
            }

            // week for estate 
            $weekestate = [
                'est' => $key,
                'afd' => 'EST',
                'kategori' => 'Test',
                'total_score' => $skor_akhir,
                'skore' => $scoreest,
                'pembagi' => $devnew,
                'start' => $start,
                'end' => $end,
                'reg' => $regional,

            ];
            $skor_brd = 0;
            $skor_janjang = 0;
            for ($i = 1; $i <= 8; $i++) {
                // Calculate the values for estate using $estateValues

                $skor_brd += round($estateValues["skor_brd$i"], 1);
                $skor_janjang += round($estateValues["skor_janjang$i"], 1);
                $weekestate["tph$i"] = $estateValues["tph$i"];
                $weekestate["jalan$i"] = $estateValues["jalan$i"];
                $weekestate["bin$i"] = $estateValues["bin$i"];
                $weekestate["karung$i"] = $estateValues["karung$i"];
                $weekestate["buah$i"] = $estateValues["buah$i"];
                $weekestate["restan$i"] = $estateValues["restan$i"];
                $weekestate["skor_brd$i"] = $estateValues["skor_brd$i"];
                $weekestate["skor_janjang$i"] = round($estateValues["skor_janjang$i"], 1);
                $weekestate["tot_brd$i"] = $estateValues["tot_brd$i"];
                $weekestate["tod_jjg$i"] = $estateValues["tod_jjg$i"];
                // $weekestate["total_score"] = round($skor_brd + $skor_janjang, 1);
            }


            $week1[] = $weekestate;
        }

        $week2 = []; // Initialize the new array
        foreach ($newSidak as $key => $value) {
            $estateValues = []; // Initialize an array to accumulate values for estate
            $est_brd = 0;
            $total_weeks = 0;
            $deviden = 0;
            $skor_akhir = 0;
            $devnew = 0;
            $nulldata = [];
            $afdcount = $value['estate']['afdeling'];
            $scoreest = 0;
            foreach ($value as $subKey => $subValue) {
                if (is_array($subValue) && isset($subValue['week2'])) {
                    $week1Data = $subValue['week2']; // Access "week1" data
                    foreach ($weeks as $keywk => $value) if ($keywk == 2) {
                        $start = $value['start'];
                        $end = $value['end'];
                    }

                    if ($week1Data['all_score'] == 0 && $week1Data['v2check3'] != 0  && $week1Data['mines'] === 'ada') {
                        # code...
                        $total_score = 0;
                        $data = 'ada';
                    } else if ($week1Data['all_score'] == 0 && $week1Data['v2check3'] != 0 && $week1Data['mines'] === 'tidak') {
                        # code...
                        $total_score = 100;
                        $data = 'ada';
                    } else if ($week1Data['all_score'] == 0 && $week1Data['check_data'] == 'null') {
                        $total_score = 0;
                        $data = 'kosong';
                    } else {
                        $total_score =  round($week1Data['all_score'], 1);
                        $data = 'ada';
                    }


                    $week1Flat = [
                        'est' => $key,
                        'afd' => $subKey,
                        'total_score' => $total_score,
                        'kategori' => $week1Data['check_data'],
                        'inspek' => $data
                    ];

                    // Extract tphx values for keys 1 to 8 and flatten them
                    for ($i = 1; $i <= 8; $i++) {
                        $tphxKey = $i;
                        $tphxValue = $week1Data[$tphxKey]['tphx'];
                        $jalan = $week1Data[$tphxKey]['jalan'];
                        $bin = $week1Data[$tphxKey]['bin'];
                        $karung = $week1Data[$tphxKey]['karung'];
                        $buah = $week1Data[$tphxKey]['buah'];
                        $restan = $week1Data[$tphxKey]['restan'];
                        $skor_brd = $week1Data[$tphxKey]['skor_brd'];
                        $skor_janjang = $week1Data[$tphxKey]['skor_janjang'];
                        $tot_brd = $week1Data[$tphxKey]['tot_brd'];
                        $tod_jjg = $week1Data[$tphxKey]['tod_jjg'];

                        $week1Flat["tph$i"] = $tphxValue;
                        $week1Flat["jalan$i"] = $jalan;
                        $week1Flat["bin$i"] = $bin;
                        $week1Flat["karung$i"] = $karung;
                        $week1Flat["buah$i"] = $buah;
                        $week1Flat["restan$i"] = $restan;
                        $week1Flat["skor_brd$i"] = $skor_brd;
                        $week1Flat["skor_janjang$i"] = round($skor_janjang, 1);
                        $week1Flat["tot_brd$i"] = $tot_brd;
                        $week1Flat["tod_jjg$i"] = $tod_jjg;
                        if (!isset($estateValues["tph$i"])) {
                            $estateValues["tph$i"] = 0;
                            $estateValues["jalan$i"] = 0;
                            $estateValues["bin$i"] = 0;
                            $estateValues["karung$i"] = 0;
                            $estateValues["buah$i"] = 0;
                            $estateValues["restan$i"] = 0;
                            $estateValues["skor_brd$i"] = 0;
                            $estateValues["skor_janjang$i"] = 0;
                            $estateValues["tot_brd$i"] = 0;
                            $estateValues["tod_jjg$i"] = 0;
                        }

                        if ($dataparams === 'new') {
                            [$panen_brd, $panen_jjg] = calculatePanennew($i);
                        } else {
                            [$panen_brd, $panen_jjg] = calculatePanen($i);
                        }

                        $estateValues["tph$i"] += $tphxValue;
                        $estateValues["jalan$i"] += $jalan;
                        $estateValues["bin$i"] += $bin;
                        $estateValues["karung$i"] += $karung;
                        $estateValues["buah$i"] += $buah;
                        $estateValues["restan$i"] += $restan;
                        $estateValues["skor_brd$i"] += round(($tot_brd * $panen_brd) / 100, 1);
                        $estateValues["skor_janjang$i"] += round(($tod_jjg * $panen_jjg) / 100, 1);
                        $estateValues["tot_brd$i"] += $tot_brd;
                        $estateValues["tod_jjg$i"] += $tod_jjg;
                    }
                    $total_weeks += round($week1Data['all_score'], 1);
                    $deviden += $subValue['afdeling']['new_deviden'];
                    $devnew = $subValue['afdeling']['devidenest'];
                    // Add the flattened array to the result
                    $week2[] = $week1Flat;
                    $nulldata[] .= $week1Data['check_data'];
                    $v2check3 = $week1Data['v2check3'];
                    $scoreest += $total_score;
                }
            }

            $counts = array_count_values($nulldata);

            // Subtract the count of 'ada' from the total count
            $getnull = count($nulldata) - ($counts['ada'] ?? 0);

            // Set getnull to 0 if it's negative (in case 'ada' occurs more times than the array size)
            $getnull = max(0, $getnull);
            if ($devnew != 0 && $scoreest != 0) {
                // $skor_akhir = round($total_weeks / $deviden, 1);
                $skor_akhir = round($scoreest / $devnew, 1);
            } else {
                $skor_akhir = '-';
            }


            // week for estate 
            $weekestate = [
                'est' => $key,
                'afd' => 'EST',
                'kategori' => 'Test',
                'total_score' => $skor_akhir,
                'start' => $start,
                'end' => $end,
                'reg' => $regional,
            ];
            $skor_brd = 0;
            $skor_janjang = 0;
            for ($i = 1; $i <= 8; $i++) {
                // Calculate the values for estate using $estateValues

                $skor_brd += round($estateValues["skor_brd$i"], 1);
                $skor_janjang += round($estateValues["skor_janjang$i"], 1);
                $weekestate["tph$i"] = $estateValues["tph$i"];
                $weekestate["jalan$i"] = $estateValues["jalan$i"];
                $weekestate["bin$i"] = $estateValues["bin$i"];
                $weekestate["karung$i"] = $estateValues["karung$i"];
                $weekestate["buah$i"] = $estateValues["buah$i"];
                $weekestate["restan$i"] = $estateValues["restan$i"];
                $weekestate["skor_brd$i"] = $estateValues["skor_brd$i"];
                $weekestate["skor_janjang$i"] = round($estateValues["skor_janjang$i"], 1);
                $weekestate["tot_brd$i"] = $estateValues["tot_brd$i"];
                $weekestate["tod_jjg$i"] = $estateValues["tod_jjg$i"];
                // $weekestate["total_score"] = round($skor_brd + $skor_janjang, 1);
            }


            $week2[] = $weekestate;
        }

        $week3 = []; // Initialize the new array
        foreach ($newSidak as $key => $value) {
            $estateValues = []; // Initialize an array to accumulate values for estate
            $est_brd = 0;
            $total_weeks = 0;
            $deviden = 0;
            $skor_akhir = 0;
            $devnew = 0;
            $nulldata = [];
            $afdcount = $value['estate']['afdeling'];
            $scoreest = 0;
            foreach ($value as $subKey => $subValue) {
                if (is_array($subValue) && isset($subValue['week3'])) {
                    $week1Data = $subValue['week3']; // Access "week1" data

                    // dd($week1Data);
                    foreach ($weeks as $keywk => $value) if ($keywk == 3) {
                        $start = $value['start'];
                        $end = $value['end'];
                    }
                    // week for afdeling 
                    if ($week1Data['all_score'] == 0 && $week1Data['v2check3'] != 0  && $week1Data['mines'] === 'ada') {
                        # code...
                        $total_score = 0;
                        $data = 'ada';
                    } else if ($week1Data['all_score'] == 0 && $week1Data['v2check3'] != 0 && $week1Data['mines'] === 'tidak') {
                        # code...
                        $total_score = 100;
                        $data = 'ada';
                    } else if ($week1Data['all_score'] == 0 && $week1Data['check_data'] == 'null') {
                        $total_score = 0;
                        $data = 'kosong';
                    } else {
                        $total_score =  round($week1Data['all_score'], 1);
                        $data = 'ada';
                    }


                    $week1Flat = [
                        'est' => $key,
                        'afd' => $subKey,
                        'total_score' => $total_score,
                        'kategori' => $week1Data['check_data'],
                        'inspek' => $data
                    ];
                    // Extract tphx values for keys 1 to 8 and flatten them
                    for ($i = 1; $i <= 8; $i++) {
                        $tphxKey = $i;
                        $tphxValue = $week1Data[$tphxKey]['tphx'];
                        $jalan = $week1Data[$tphxKey]['jalan'];
                        $bin = $week1Data[$tphxKey]['bin'];
                        $karung = $week1Data[$tphxKey]['karung'];
                        $buah = $week1Data[$tphxKey]['buah'];
                        $restan = $week1Data[$tphxKey]['restan'];
                        $skor_brd = $week1Data[$tphxKey]['skor_brd'];
                        $skor_janjang = $week1Data[$tphxKey]['skor_janjang'];
                        $tot_brd = $week1Data[$tphxKey]['tot_brd'];
                        $tod_jjg = $week1Data[$tphxKey]['tod_jjg'];

                        $week1Flat["tph$i"] = $tphxValue;
                        $week1Flat["jalan$i"] = $jalan;
                        $week1Flat["bin$i"] = $bin;
                        $week1Flat["karung$i"] = $karung;
                        $week1Flat["buah$i"] = $buah;
                        $week1Flat["restan$i"] = $restan;
                        $week1Flat["skor_brd$i"] = $skor_brd;
                        $week1Flat["skor_janjang$i"] = round($skor_janjang, 1);
                        $week1Flat["tot_brd$i"] = $tot_brd;
                        $week1Flat["tod_jjg$i"] = $tod_jjg;
                        if (!isset($estateValues["tph$i"])) {
                            $estateValues["tph$i"] = 0;
                            $estateValues["jalan$i"] = 0;
                            $estateValues["bin$i"] = 0;
                            $estateValues["karung$i"] = 0;
                            $estateValues["buah$i"] = 0;
                            $estateValues["restan$i"] = 0;
                            $estateValues["skor_brd$i"] = 0;
                            $estateValues["skor_janjang$i"] = 0;
                            $estateValues["tot_brd$i"] = 0;
                            $estateValues["tod_jjg$i"] = 0;
                        }


                        if ($dataparams === 'new') {
                            [$panen_brd, $panen_jjg] = calculatePanennew($i);
                        } else {
                            [$panen_brd, $panen_jjg] = calculatePanen($i);
                        }

                        $estateValues["tph$i"] += $tphxValue;
                        $estateValues["jalan$i"] += $jalan;
                        $estateValues["bin$i"] += $bin;
                        $estateValues["karung$i"] += $karung;
                        $estateValues["buah$i"] += $buah;
                        $estateValues["restan$i"] += $restan;
                        $estateValues["skor_brd$i"] += round(($tot_brd * $panen_brd) / 100, 1);
                        $estateValues["skor_janjang$i"] += round(($tod_jjg * $panen_jjg) / 100, 1);
                        $estateValues["tot_brd$i"] += $tot_brd;
                        $estateValues["tod_jjg$i"] += $tod_jjg;
                    }
                    $total_weeks += round($week1Data['all_score'], 1);
                    $deviden += $subValue['afdeling']['new_deviden'];
                    $devnew = $subValue['afdeling']['devidenest'];
                    // Add the flattened array to the result
                    $week3[] = $week1Flat;
                    $nulldata[] .= $week1Data['check_data'];
                    $v2check3 = $week1Data['v2check3'];
                    $scoreest += $total_score;
                }
            }
            $counts = array_count_values($nulldata);

            // Subtract the count of 'ada' from the total count
            $getnull = count($nulldata) - ($counts['ada'] ?? 0);

            // Set getnull to 0 if it's negative (in case 'ada' occurs more times than the array size)
            $getnull = max(0, $getnull);
            if ($devnew != 0 && $scoreest != 0) {
                // $skor_akhir = round($total_weeks / $deviden, 1);
                $skor_akhir = round($scoreest / $devnew, 1);
            } else {
                $skor_akhir = '-';
            }
            // week for estate 
            $weekestate = [
                'est' => $key,
                'afd' => 'EST',
                'kategori' => $getnull,
                'total_score' => $skor_akhir,
                'start' => $start,
                'end' => $end,
                'reg' => $regional,
            ];
            $skor_brd = 0;
            $skor_janjang = 0;
            for ($i = 1; $i <= 8; $i++) {
                // Calculate the values for estate using $estateValues

                $skor_brd += round($estateValues["skor_brd$i"], 1);
                $skor_janjang += round($estateValues["skor_janjang$i"], 1);
                $weekestate["tph$i"] = $estateValues["tph$i"];
                $weekestate["jalan$i"] = $estateValues["jalan$i"];
                $weekestate["bin$i"] = $estateValues["bin$i"];
                $weekestate["karung$i"] = $estateValues["karung$i"];
                $weekestate["buah$i"] = $estateValues["buah$i"];
                $weekestate["restan$i"] = $estateValues["restan$i"];
                $weekestate["skor_brd$i"] = $estateValues["skor_brd$i"];
                $weekestate["skor_janjang$i"] = round($estateValues["skor_janjang$i"], 1);
                $weekestate["tot_brd$i"] = $estateValues["tot_brd$i"];
                $weekestate["tod_jjg$i"] = $estateValues["tod_jjg$i"];
                // $weekestate["total_score"] = round($skor_brd + $skor_janjang, 1);
            }


            $week3[] = $weekestate;
        }

        // dd($week3[4]);
        $week4 = []; // Initialize the new array
        foreach ($newSidak as $key => $value) {
            $estateValues = []; // Initialize an array to accumulate values for estate
            $est_brd = 0;
            $total_weeks = 0;
            $deviden = 0;
            $devnew = 0;
            $skor_akhir = 0;
            $nulldata = [];
            $scoreest = 0;
            $afdcount = $value['estate']['afdeling'];
            foreach ($value as $subKey => $subValue) {
                if (is_array($subValue) && isset($subValue['week4'])) {
                    $week1Data = $subValue['week4']; // Access "week1" data
                    foreach ($weeks as $keywk => $value) if ($keywk == 4) {
                        $start = $value['start'];
                        $end = $value['end'];
                    }
                    // week for afdeling 
                    if ($week1Data['all_score'] == 0 && $week1Data['v2check3'] != 0  && $week1Data['mines'] === 'ada') {
                        # code...
                        $total_score = 0;
                        $data = 'ada';
                    } else if ($week1Data['all_score'] == 0 && $week1Data['v2check3'] != 0 && $week1Data['mines'] === 'tidak') {
                        # code...
                        $total_score = 100;
                        $data = 'ada';
                    } else if ($week1Data['all_score'] == 0 && $week1Data['check_data'] == 'null') {
                        $total_score = 0;
                        $data = 'kosong';
                    } else {
                        $total_score =  round($week1Data['all_score'], 1);
                        $data = 'ada';
                    }


                    $week1Flat = [
                        'est' => $key,
                        'afd' => $subKey,
                        'total_score' => $total_score,
                        'kategori' => $week1Data['check_data'],
                        'inspek' => $data
                    ];
                    // Extract tphx values for keys 1 to 8 and flatten them
                    for ($i = 1; $i <= 8; $i++) {
                        $tphxKey = $i;
                        $tphxValue = $week1Data[$tphxKey]['tphx'];
                        $jalan = $week1Data[$tphxKey]['jalan'];
                        $bin = $week1Data[$tphxKey]['bin'];
                        $karung = $week1Data[$tphxKey]['karung'];
                        $buah = $week1Data[$tphxKey]['buah'];
                        $restan = $week1Data[$tphxKey]['restan'];
                        $skor_brd = $week1Data[$tphxKey]['skor_brd'];
                        $skor_janjang = $week1Data[$tphxKey]['skor_janjang'];
                        $tot_brd = $week1Data[$tphxKey]['tot_brd'];
                        $tod_jjg = $week1Data[$tphxKey]['tod_jjg'];

                        $week1Flat["tph$i"] = $tphxValue;
                        $week1Flat["jalan$i"] = $jalan;
                        $week1Flat["bin$i"] = $bin;
                        $week1Flat["karung$i"] = $karung;
                        $week1Flat["buah$i"] = $buah;
                        $week1Flat["restan$i"] = $restan;
                        $week1Flat["skor_brd$i"] = $skor_brd;
                        $week1Flat["skor_janjang$i"] = round($skor_janjang, 1);
                        $week1Flat["tot_brd$i"] = $tot_brd;
                        $week1Flat["tod_jjg$i"] = $tod_jjg;
                        if (!isset($estateValues["tph$i"])) {
                            $estateValues["tph$i"] = 0;
                            $estateValues["jalan$i"] = 0;
                            $estateValues["bin$i"] = 0;
                            $estateValues["karung$i"] = 0;
                            $estateValues["buah$i"] = 0;
                            $estateValues["restan$i"] = 0;
                            $estateValues["skor_brd$i"] = 0;
                            $estateValues["skor_janjang$i"] = 0;
                            $estateValues["tot_brd$i"] = 0;
                            $estateValues["tod_jjg$i"] = 0;
                        }


                        if ($dataparams === 'new') {
                            [$panen_brd, $panen_jjg] = calculatePanennew($i);
                        } else {
                            [$panen_brd, $panen_jjg] = calculatePanen($i);
                        }

                        $estateValues["tph$i"] += $tphxValue;
                        $estateValues["jalan$i"] += $jalan;
                        $estateValues["bin$i"] += $bin;
                        $estateValues["karung$i"] += $karung;
                        $estateValues["buah$i"] += $buah;
                        $estateValues["restan$i"] += $restan;
                        $estateValues["skor_brd$i"] += round(($tot_brd * $panen_brd) / 100, 1);
                        $estateValues["skor_janjang$i"] += round(($tod_jjg * $panen_jjg) / 100, 1);
                        $estateValues["tot_brd$i"] += $tot_brd;
                        $estateValues["tod_jjg$i"] += $tod_jjg;
                    }
                    $total_weeks += round($week1Data['all_score'], 1);
                    $deviden += $subValue['afdeling']['new_deviden'];
                    $devnew = $subValue['afdeling']['devidenest'];
                    // Add the flattened array to the result
                    $week4[] = $week1Flat;
                    $nulldata[] .= $week1Data['check_data'];
                    $v2check3 = $week1Data['v2check3'];
                    $scoreest += $total_score;
                }
            }

            $counts = array_count_values($nulldata);

            // Subtract the count of 'ada' from the total count
            $getnull = count($nulldata) - ($counts['ada'] ?? 0);

            // Set getnull to 0 if it's negative (in case 'ada' occurs more times than the array size)
            $getnull = max(0, $getnull);
            if ($devnew != 0 && $scoreest != 0) {
                // $skor_akhir = round($total_weeks / $deviden, 1);
                $skor_akhir = round($scoreest / $devnew, 1);
            } else {
                $skor_akhir = '-';
            }


            // week for estate 
            $weekestate = [
                'est' => $key,
                'afd' => 'EST',
                'kategori' => 'Test',
                'total_score' => $skor_akhir,
                'start' => $start,
                'end' => $end,
                'reg' => $regional,
            ];
            $skor_brd = 0;
            $skor_janjang = 0;
            for ($i = 1; $i <= 8; $i++) {
                // Calculate the values for estate using $estateValues

                $skor_brd += round($estateValues["skor_brd$i"], 1);
                $skor_janjang += round($estateValues["skor_janjang$i"], 1);
                $weekestate["tph$i"] = $estateValues["tph$i"];
                $weekestate["jalan$i"] = $estateValues["jalan$i"];
                $weekestate["bin$i"] = $estateValues["bin$i"];
                $weekestate["karung$i"] = $estateValues["karung$i"];
                $weekestate["buah$i"] = $estateValues["buah$i"];
                $weekestate["restan$i"] = $estateValues["restan$i"];
                $weekestate["skor_brd$i"] = $estateValues["skor_brd$i"];
                $weekestate["skor_janjang$i"] = round($estateValues["skor_janjang$i"], 1);
                $weekestate["tot_brd$i"] = $estateValues["tot_brd$i"];
                $weekestate["tod_jjg$i"] = $estateValues["tod_jjg$i"];
                // $weekestate["total_score"] = round($skor_brd + $skor_janjang, 1);
            }


            $week4[] = $weekestate;
        }

        $week5 = []; // Initialize the new array
        foreach ($newSidak as $key => $value) {
            $estateValues = []; // Initialize an array to accumulate values for estate
            $est_brd = 0;
            $total_weeks = 0;
            $deviden = 0;
            $skor_akhir = 0;
            $devnew = 0;
            $nulldata = [];
            $afdcount = $value['estate']['afdeling'];
            $scoreest = 0;
            foreach ($value as $subKey => $subValue) {
                if (is_array($subValue) && isset($subValue['week5'])) {
                    $week1Data = $subValue['week5']; // Access "week1" data
                    foreach ($weeks as $keywk => $value) if ($keywk == 5) {
                        $start = $value['start'];
                        $end = $value['end'];
                    }
                    // week for afdeling 
                    if ($week1Data['all_score'] == 0 && $week1Data['v2check3'] != 0  && $week1Data['mines'] === 'ada') {
                        # code...
                        $total_score = 0;
                        $data = 'ada';
                    } else if ($week1Data['all_score'] == 0 && $week1Data['v2check3'] != 0 && $week1Data['mines'] === 'tidak') {
                        # code...
                        $total_score = 100;
                        $data = 'ada';
                    } else if ($week1Data['all_score'] == 0 && $week1Data['check_data'] == 'null') {
                        $total_score = 0;
                        $data = 'kosong';
                    } else {
                        $total_score =  round($week1Data['all_score'], 1);
                        $data = 'ada';
                    }

                    $week1Flat = [
                        'est' => $key,
                        'afd' => $subKey,
                        'total_score' => $total_score,
                        'kategori' => $week1Data['check_data'],
                        'inspek' => $data
                    ];

                    // dd($subValue);

                    // Extract tphx values for keys 1 to 8 and flatten them
                    for ($i = 1; $i <= 8; $i++) {
                        $tphxKey = $i;
                        $tphxValue = $week1Data[$tphxKey]['tphx'];
                        $jalan = $week1Data[$tphxKey]['jalan'];
                        $bin = $week1Data[$tphxKey]['bin'];
                        $karung = $week1Data[$tphxKey]['karung'];
                        $buah = $week1Data[$tphxKey]['buah'];
                        $restan = $week1Data[$tphxKey]['restan'];
                        $skor_brd = $week1Data[$tphxKey]['skor_brd'];
                        $skor_janjang = $week1Data[$tphxKey]['skor_janjang'];
                        $tot_brd = $week1Data[$tphxKey]['tot_brd'];
                        $tod_jjg = $week1Data[$tphxKey]['tod_jjg'];

                        $week1Flat["tph$i"] = $tphxValue;
                        $week1Flat["jalan$i"] = $jalan;
                        $week1Flat["bin$i"] = $bin;
                        $week1Flat["karung$i"] = $karung;
                        $week1Flat["buah$i"] = $buah;
                        $week1Flat["restan$i"] = $restan;
                        $week1Flat["skor_brd$i"] = $skor_brd;
                        $week1Flat["skor_janjang$i"] = round($skor_janjang, 1);
                        $week1Flat["tot_brd$i"] = $tot_brd;
                        $week1Flat["tod_jjg$i"] = $tod_jjg;
                        if (!isset($estateValues["tph$i"])) {
                            $estateValues["tph$i"] = 0;
                            $estateValues["jalan$i"] = 0;
                            $estateValues["bin$i"] = 0;
                            $estateValues["karung$i"] = 0;
                            $estateValues["buah$i"] = 0;
                            $estateValues["restan$i"] = 0;
                            $estateValues["skor_brd$i"] = 0;
                            $estateValues["skor_janjang$i"] = 0;
                            $estateValues["tot_brd$i"] = 0;
                            $estateValues["tod_jjg$i"] = 0;
                        }


                        if ($dataparams === 'new') {
                            [$panen_brd, $panen_jjg] = calculatePanennew($i);
                        } else {
                            [$panen_brd, $panen_jjg] = calculatePanen($i);
                        }

                        $estateValues["tph$i"] += $tphxValue;
                        $estateValues["jalan$i"] += $jalan;
                        $estateValues["bin$i"] += $bin;
                        $estateValues["karung$i"] += $karung;
                        $estateValues["buah$i"] += $buah;
                        $estateValues["restan$i"] += $restan;
                        $estateValues["skor_brd$i"] += round(($tot_brd * $panen_brd) / 100, 1);
                        $estateValues["skor_janjang$i"] += round(($tod_jjg * $panen_jjg) / 100, 1);
                        $estateValues["tot_brd$i"] += $tot_brd;
                        $estateValues["tod_jjg$i"] += $tod_jjg;
                    }
                    $total_weeks += round($week1Data['all_score'], 1);
                    $deviden += $subValue['afdeling']['new_deviden'];
                    $devnew = $subValue['afdeling']['devidenest'];
                    // Add the flattened array to the result
                    $week5[] = $week1Flat;
                    $nulldata[] .= $week1Data['check_data'];
                    $v2check3 = $week1Data['v2check3'];
                    $scoreest += $total_score;
                }
            }
            $counts = array_count_values($nulldata);

            // Subtract the count of 'ada' from the total count
            $getnull = count($nulldata) - ($counts['ada'] ?? 0);

            // Set getnull to 0 if it's negative (in case 'ada' occurs more times than the array size)
            $getnull = max(0, $getnull);
            if ($devnew != 0 && $scoreest != 0) {
                // $skor_akhir = round($total_weeks / $deviden, 1);
                $skor_akhir = round($scoreest / $devnew, 1);
            } else {
                $skor_akhir = '-';
            }
            // week for estate 
            $weekestate = [
                'est' => $key,
                'afd' => 'EST',
                'kategori' => 'Test',
                'total_score' => $skor_akhir,
                'start' => $start,
                'end' => $end,
                'reg' => $regional,
            ];
            $skor_brd = 0;
            $skor_janjang = 0;
            for ($i = 1; $i <= 8; $i++) {
                // Calculate the values for estate using $estateValues

                $skor_brd += round($estateValues["skor_brd$i"], 1);
                $skor_janjang += round($estateValues["skor_janjang$i"], 1);
                $weekestate["tph$i"] = $estateValues["tph$i"];
                $weekestate["jalan$i"] = $estateValues["jalan$i"];
                $weekestate["bin$i"] = $estateValues["bin$i"];
                $weekestate["karung$i"] = $estateValues["karung$i"];
                $weekestate["buah$i"] = $estateValues["buah$i"];
                $weekestate["restan$i"] = $estateValues["restan$i"];
                $weekestate["skor_brd$i"] = $estateValues["skor_brd$i"];
                $weekestate["skor_janjang$i"] = round($estateValues["skor_janjang$i"], 1);
                $weekestate["tot_brd$i"] = $estateValues["tot_brd$i"];
                $weekestate["tod_jjg$i"] = $estateValues["tod_jjg$i"];
                // $weekestate["total_score"] = round($skor_brd + $skor_janjang, 1);
            }


            $week5[] = $weekestate;
        }

        $estate = DB::connection('mysql2')->table('estate')
            ->select('*')
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->where('wil.regional', $regional)
            ->where('estate.emp', '!=', 1)
            ->get();
        $estate = $estate->groupBy(['wil', 'est']);
        $estate = json_decode($estate, true);


        //  memkelompokan setiap kategori estae /afdeling/wilayah 
        $rekap_perafdeling_groupwil = [];
        foreach ($newSidak as $key => $value) {
            unset($value['estate']);
            foreach ($value as $keys1 => $value2) {
                foreach ($estate as $key1 => $wil) {
                    foreach ($wil as $key2 => $est) {
                        if ($key2 === $key) {
                            $rekap_perafdeling_groupwil[$key1][$key][$keys1] = $value2['afdeling'];
                        }
                    }
                }
            }
        }
        $rekap_perestate_groupwill = [];
        foreach ($newSidak as $key => $value) {
            foreach ($estate as $key1 => $wil) {
                foreach ($wil as $key2 => $est) {
                    if ($key2 === $key) {
                        $rekap_perestate_groupwill[$key1][$key]['estate'] = $value['estate'];
                    }
                }
            }
        }
        $rekap_estate_ori = [];
        foreach ($newSidak as $key => $value) {
            foreach ($estate as $key1 => $wil) {
                foreach ($wil as $key2 => $est) {
                    if ($key2 === $key) {
                        $rekap_estate_ori[$key1][$key] = $value['estate'];
                    }
                }
            }
        }

        $rekap_perwill_groupwil = [];
        // dd($rekap_perafdeling_groupwil, $rekap_estate_ori);
        if ($regional === '3') {
            foreach ($rekap_perafdeling_groupwil as $key => $value) {
                $get_score2 = 0;
                $get_deviden2 = 0;
                $brd2 = 0;
                $buah2 = 0;
                foreach ($value as $key1 => $value1) {
                    $get_score = 0;
                    $get_deviden = 0;
                    $brd = 0;
                    $buah = 0;
                    $test1 = [];
                    foreach ($value1 as $key2 => $value2) {
                        $get_score += $value2['total_score'];
                        $get_deviden += $value2['newdivsafd'];
                        $brd += $value2['total_brd'];
                        $buah += $value2['total_janjang'];
                    }
                    $get_score2 += $get_score;
                    $get_deviden2 += $get_deviden;
                    $brd2 += $brd;
                    $buah2 += $buah;
                }
                $total_score = ($get_deviden2 != 0) ? $get_score2 / $get_deviden2 : 0;
                $deviden = ($get_deviden2 != 0) ? 1 : 0;
                $rekap_perwill_groupwil[$key]['deviden'] = $deviden;
                $rekap_perwill_groupwil[$key]['get_deviden2'] = $get_deviden2;
                $rekap_perwill_groupwil[$key]['brd'] = $brd2;
                $rekap_perwill_groupwil[$key]['buah'] = $buah2;
                $rekap_perwill_groupwil[$key]['skor'] = $total_score;
                $rekap_perwill_groupwil[$key]['wil'] = convertToRoman($key);
                $rekap_perwill_groupwil[$key]['gm'] = get_nama_gm($key);
                $rekap_perwill_groupwil[$key]['Status_Reg'] = 'Regional 3';
            }
        } else {
            foreach ($rekap_estate_ori as $key => $value) {
                $get_score = 0;
                $get_deviden = 0;
                $brd = 0;
                $buah = 0;
                foreach ($value as $key1 => $value1) {
                    $get_score += $value1['score_estate'];
                    $get_deviden += $value1['deviden'];
                    $brd += $value1['brd'];
                    $buah += $value1['buah'];
                    // $newdivsafd += $value1['newdivsafd'];
                }
                $total_score = ($get_deviden != 0) ? $get_score / $get_deviden : 0;
                $deviden = ($get_deviden != 0) ? 1 : 0;
                $rekap_perwill_groupwil[$key]['deviden'] = $deviden;
                $rekap_perwill_groupwil[$key]['brd'] = $brd;
                $rekap_perwill_groupwil[$key]['buah'] = $buah;
                $rekap_perwill_groupwil[$key]['skor'] = $total_score;
                $rekap_perwill_groupwil[$key]['wil'] = convertToRoman($key);
                $rekap_perwill_groupwil[$key]['gm'] = get_nama_gm($key);
                $rekap_perwill_groupwil[$key]['Status_Reg'] = 'Regional tidak 3';
            }
        }

        // dd($rekap_perwill_groupwil, $rekap_perafdeling_groupwil, $regional);
        $rekap_rh = [];
        $get_score_rh = 0;
        $get_deviden_rh = 0;
        foreach ($rekap_perwill_groupwil as $key => $value) {
            $get_score_rh += $value['skor'];
            $get_deviden_rh += $value['deviden'];
        }
        $total_score_rh = ($get_deviden_rh != 0) ? $get_score_rh / $get_deviden_rh : 0;
        $deviden_rh = ($get_deviden_rh != 0) ? 1 : 0;
        $rekap_rh['deviden'] = $deviden_rh;
        $rekap_rh['skor'] = $total_score_rh;
        $rekap_rh['wil'] = convertToRoman($regional);
        $rekap_rh['rh'] = get_nama_rh($regional);
        // dd($rekap_perestate_groupwill);
        // dd($rekap_perafdeling_groupwil, $rekap_perestate_groupwill);

        // untuk dapatkan mua regional 1 
        if ($regional == 1) {
            // untuk mua ============================= 
            $muaest = DB::connection('mysql2')->table('estate')
                ->select('estate.*')
                ->where('estate.emp', '!=', 1)
                ->join('wil', 'wil.id', '=', 'estate.wil')
                ->where('wil.regional', $regional)
                // ->where('estate.emp', '!=', 1)
                ->whereIn('estate.est', ['SRE', 'LDE'])
                ->get('est');
            $muaest = json_decode($muaest, true);
            $afdmua = DB::connection('mysql2')->table('afdeling')
                ->select(
                    'afdeling.id',
                    'afdeling.nama',
                    'estate.est'
                ) //buat mengambil data di estate db dan willayah db
                ->join('estate', 'estate.id', '=', 'afdeling.estate') //kemudian di join untuk mengambil est perwilayah
                ->get();
            $afdmua = json_decode($afdmua, true);
            $defaultweekmua = array();

            foreach ($muaest as $est) {
                foreach ($afdmua as $afd) {
                    if ($est['est'] == $afd['est']) {
                        $defaultweekmua[$est['est']][$afd['est']] = 0;
                    }
                }
            }

            // dd($defaultweekmua);
            foreach ($defaultweekmua as $key => $estValue) {
                foreach ($estValue as $monthKey => $monthValue) {
                    foreach ($WeekStatus as $dataKey => $dataValue) {

                        if ($dataKey == $key) {
                            foreach ($dataValue as $dataEstKey => $dataEstValue) {

                                if ($dataEstKey == $monthKey) {
                                    $defaultweekmua[$key][$monthKey] = $dataEstValue;
                                }
                            }
                        }
                    }
                }
            }
            $dividenmua = [];

            foreach ($defaultweekmua as $key => $value) {
                foreach ($value as $key1 => $value1) if (is_array($value1)) {
                    foreach ($value1 as $key2 => $value2) if (is_array($value2)) {

                        $dividenn = count($value1);
                    }
                    $dividenmua[$key][$key1]['dividen'] = $dividenn;
                } else {
                    $dividenmua[$key][$key1]['dividen'] = 0;
                }
            }

            $tot_estAFdx = 0;
            $new_dvdAfdx = 0;
            $new_dvdAfdesx = 0;
            $v2check5x = 0;
            $chartbrd4 = 0;
            $chartrst4 = 0;
            $newSidak_mua = array();

            foreach ($defaultweekmua as $key => $value) {
                $total_skoreest = 0;
                $tot_estAFd = 0;
                $new_dvdAfd = 0;
                $new_dvdAfdest = 0;
                $total_estkors = 0;
                $total_skoreafd = 0;
                $devest = count($value);
                // dd($devest);
                // dd($value);
                $v2check5 = 0;
                $newpembagi3 = 0;
                $chartbrd3 = 0;
                $chartrst3 = 0;
                foreach ($value as $key1 => $value2)  if (is_array($value2)) {

                    $tot_afdscore = 0;
                    $totskor_brd1 = 0;
                    $totskor_janjang1 = 0;
                    $total_skoreest = 0;
                    $newpembagi1 = 0;
                    $v2check4 = 0;
                    $chartbrd2 = 0;
                    $chartrst2 = 0;
                    foreach ($value2 as $key2 => $value3) {


                        $total_brondolan = 0;
                        $total_janjang = 0;
                        $tod_brd = 0;
                        $tod_jjg = 0;
                        $totskor_brd = 0;
                        $totskor_janjang = 0;
                        $tot_brdxm = 0;
                        $tod_janjangxm = 0;
                        $v2check3 = 0;
                        $chartbrd1 = 0;
                        $chartrst1 = 0;

                        foreach ($value3 as $key3 => $value4) if (is_array($value4)) {
                            $tph1 = 0;
                            $jalan1 = 0;
                            $bin1 = 0;
                            $karung1 = 0;
                            $buah1 = 0;
                            $restan1 = 0;
                            $v2check2 = 0;

                            foreach ($value4 as $key4 => $value5) if (is_array($value5)) {
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
                                    $sum_all_restan_unreported = 0;

                                    foreach ($value6 as $key6 => $value7) {
                                        // dd($value7);
                                        // dd($value7);
                                        $sum_bt_tph += $value7['bt_tph'];
                                        $sum_bt_jalan += $value7['bt_jalan'];
                                        $sum_bt_bin += $value7['bt_bin'];
                                        $sum_jum_karung += $value7['jum_karung'];


                                        $sum_buah_tinggal += $value7['buah_tinggal'];
                                        $sum_restan_unreported += $value7['restan_unreported'];
                                    }
                                    $newSidak_mua[$key][$key1][$key2][$key3][$key4][$key5]['tph'] = $sum_bt_tph;
                                    $newSidak_mua[$key][$key1][$key2][$key3][$key4][$key5]['jalan'] = $sum_bt_jalan;
                                    $newSidak_mua[$key][$key1][$key2][$key3][$key4][$key5]['bin'] = $sum_bt_bin;
                                    $newSidak_mua[$key][$key1][$key2][$key3][$key4][$key5]['karung'] = $sum_jum_karung;

                                    $newSidak_mua[$key][$key1][$key2][$key3][$key4][$key5]['buah'] = $sum_buah_tinggal;
                                    $newSidak_mua[$key][$key1][$key2][$key3][$key4][$key5]['restan'] = $sum_restan_unreported;


                                    $tph += $sum_bt_tph;
                                    $jalan += $sum_bt_jalan;
                                    $bin += $sum_bt_bin;
                                    $karung += $sum_jum_karung;
                                    $buah += $sum_buah_tinggal;
                                    $restan += $sum_restan_unreported;
                                }

                                $newSidak_mua[$key][$key1][$key2][$key3][$key4]['tph'] = $tph;
                                $newSidak_mua[$key][$key1][$key2][$key3][$key4]['jalan'] = $jalan;
                                $newSidak_mua[$key][$key1][$key2][$key3][$key4]['bin'] = $bin;
                                $newSidak_mua[$key][$key1][$key2][$key3][$key4]['karung'] = $karung;

                                $newSidak_mua[$key][$key1][$key2][$key3][$key4]['buah'] = $buah;
                                $newSidak_mua[$key][$key1][$key2][$key3][$key4]['restan'] = $restan;
                                $newSidak_mua[$key][$key1][$key2][$key3][$key4]['v2check'] = $v2check;

                                $tph1 += $tph;
                                $jalan1 += $jalan;
                                $bin1 += $bin;
                                $karung1 += $karung;
                                $buah1 += $buah;
                                $restan1 += $restan;
                                $v2check2 += $v2check;
                            }
                            // dd($key3);
                            $status_panen = $key3;

                            if ($dataparams === 'new') {
                                [$panen_brd, $panen_jjg] = calculatePanennew($status_panen);
                            } else {
                                [$panen_brd, $panen_jjg] = calculatePanen($status_panen);
                            }



                            // untuk brondolan gabungan dari bt-tph,bt-jalan,bt-bin,jum-karung 
                            $total_brondolan =  round(($tph1 + $jalan1 + $bin1 + $karung1) * $panen_brd / 100, 4);
                            $total_janjang =  round(($buah1 + $restan1) * $panen_jjg / 100, 4);
                            $chartbrd =  $tph1 + $jalan1 + $bin1 + $karung1;
                            $chartrst =  $buah1 + $restan1;
                            $tod_brd = $tph1 + $jalan1 + $bin1 + $karung1;
                            $tod_jjg = $buah1 + $restan1;
                            $newSidak_mua[$key][$key1][$key2][$key3]['tphx'] = $tph1;
                            $newSidak_mua[$key][$key1][$key2][$key3]['jalan'] = $jalan1;
                            $newSidak_mua[$key][$key1][$key2][$key3]['bin'] = $bin1;
                            $newSidak_mua[$key][$key1][$key2][$key3]['karung'] = $karung1;
                            $newSidak_mua[$key][$key1][$key2][$key3]['tot_brd'] = $tod_brd;

                            $newSidak_mua[$key][$key1][$key2][$key3]['buah'] = $buah1;
                            $newSidak_mua[$key][$key1][$key2][$key3]['restan'] = $restan1;
                            $newSidak_mua[$key][$key1][$key2][$key3]['skor_brd'] = $total_brondolan;
                            $newSidak_mua[$key][$key1][$key2][$key3]['skor_janjang'] = $total_janjang;
                            $newSidak_mua[$key][$key1][$key2][$key3]['tod_jjg'] = $tod_jjg;
                            $newSidak_mua[$key][$key1][$key2][$key3]['v2check2'] = $v2check2;

                            $totskor_brd += $total_brondolan;
                            $totskor_janjang += $total_janjang;
                            $tot_brdxm += $tod_brd;
                            $tod_janjangxm += $tod_jjg;
                            $v2check3 += $v2check2;
                            $chartbrd1 += $chartbrd;
                            $chartrst1 += $chartrst;
                        } else {
                            $newSidak_mua[$key][$key1][$key2][$key3]['tphx'] = 0;
                            $newSidak_mua[$key][$key1][$key2][$key3]['jalan'] = 0;
                            $newSidak_mua[$key][$key1][$key2][$key3]['bin'] = 0;
                            $newSidak_mua[$key][$key1][$key2][$key3]['karung'] = 0;

                            $newSidak_mua[$key][$key1][$key2][$key3]['buah'] = 0;
                            $newSidak_mua[$key][$key1][$key2][$key3]['restan'] = 0;

                            $newSidak_mua[$key][$key1][$key2][$key3]['skor_brd'] = 0;
                            $newSidak_mua[$key][$key1][$key2][$key3]['skor_janjang'] = 0;
                            $newSidak_mua[$key][$key1][$key2][$key3]['tot_brd'] = 0;
                            $newSidak_mua[$key][$key1][$key2][$key3]['tod_jjg'] = 0;
                            $newSidak_mua[$key][$key1][$key2][$key3]['v2check2'] = 0;
                        }


                        $total_estkors = $totskor_brd + $totskor_janjang;
                        if ($total_estkors != 0) {

                            $checkscore = 100 - ($total_estkors);

                            if ($checkscore < 0) {
                                $newscore = 0;
                                $newSidak_mua[$key][$key1][$key2]['mines'] = 'ada';
                            } else {
                                $newscore = $checkscore;
                                $newSidak_mua[$key][$key1][$key2]['mines'] = 'tidak';
                            }

                            $newSidak_mua[$key][$key1][$key2]['all_score'] = $newscore;
                            $newSidak_mua[$key][$key1][$key2]['check_data'] = 'ada';

                            $total_skoreafd = $newscore;
                            $newpembagi = 1;
                        } else if ($v2check3 != 0) {
                            $checkscore = 100 - ($total_estkors);

                            if ($checkscore < 0) {
                                $newscore = 0;
                                $newSidak_mua[$key][$key1][$key2]['mines'] = 'ada';
                            } else {
                                $newscore = $checkscore;
                                $newSidak_mua[$key][$key1][$key2]['mines'] = 'tidak';
                            }
                            $newSidak_mua[$key][$key1][$key2]['all_score'] = 100 - ($total_estkors);
                            $newSidak_mua[$key][$key1][$key2]['check_data'] = 'ada';

                            $total_skoreafd = $newscore;

                            $newpembagi = 1;
                        } else {
                            $newSidak_mua[$key][$key1][$key2]['all_score'] = 0;
                            $newSidak_mua[$key][$key1][$key2]['check_data'] = 'null';
                            $total_skoreafd = 0;
                            $newpembagi = 0;
                        }
                        // $newSidak_mua[$key][$key1][$key2]['all_score'] = 100 - ($total_estkors);
                        $newSidak_mua[$key][$key1][$key2]['total_brd'] = $tot_brdxm;
                        $newSidak_mua[$key][$key1][$key2]['total_brdSkor'] = $totskor_brd;
                        $newSidak_mua[$key][$key1][$key2]['total_janjang'] = $tod_janjangxm;
                        $newSidak_mua[$key][$key1][$key2]['total_janjangSkor'] = $totskor_janjang;
                        $newSidak_mua[$key][$key1][$key2]['total_skor'] = $total_skoreafd;
                        $newSidak_mua[$key][$key1][$key2]['janjang_brd'] = $totskor_brd + $totskor_janjang;
                        $newSidak_mua[$key][$key1][$key2]['v2check3'] = $v2check3;
                        $newSidak_mua[$key][$key1][$key2]['newpembagi'] = $newpembagi;

                        $totskor_brd1 += $totskor_brd;
                        $totskor_janjang1 += $totskor_janjang;
                        $total_skoreest += $total_skoreafd;
                        $newpembagi1 += $newpembagi;
                        $v2check4 += $v2check3;
                        $chartbrd2 += $chartbrd1;
                        $chartrst2 += $chartrst1;
                    }



                    // dd($deviden);


                    $namaGM = get_nama_asisten($key, $key1);
                    $deviden = count($value2);

                    $new_dvd = $dividen_x ?? 0;
                    $new_dvdest = $devidenEst_x ?? 0;


                    $total_estkors = $totskor_brd1 + $totskor_janjang1;
                    if ($total_estkors != 0) {

                        // $checkscore = 100 - ($total_estkors);
                        $checkscore = round($total_skoreest / $newpembagi1, 1);
                        if ($checkscore < 0) {
                            $newscore = 0;
                            $newSidak_mua[$key][$key1][$key2]['mines'] = 'ada';
                        } else {
                            $newscore = $checkscore;
                            $newSidak_mua[$key][$key1][$key2]['mines'] = 'tidak';
                        }

                        $newSidak_mua[$key][$key1]['all_score'] = $newscore;
                        $newSidak_mua[$key][$key1]['check_data'] = 'ada';

                        $total_skoreafd = $newscore;
                        $newpembagi2 = 1;
                    } else if ($v2check4 != 0) {
                        $checkscore = round($total_skoreest / $newpembagi1, 1);
                        if ($checkscore < 0) {
                            $newscore = 0;
                            $newSidak_mua[$key][$key1]['mines'] = 'ada';
                        } else {
                            $newscore = $checkscore;
                            $newSidak_mua[$key][$key1]['mines'] = 'tidak';
                        }
                        $newSidak_mua[$key][$key1]['all_score'] = 100 - ($total_estkors);
                        $newSidak_mua[$key][$key1]['check_data'] = 'ada';

                        $total_skoreafd = $newscore;

                        $newpembagi2 = 1;
                        $newSidak_mua[$key][$key1]['checkdata'] = 'ada';
                    } else {
                        $newSidak_mua[$key][$key1]['all_score'] = 0;
                        $newSidak_mua[$key][$key1]['check_data'] = 'null';
                        $total_skoreafd = 0;
                        $newpembagi2 = 0;
                        $newSidak_mua[$key][$key1]['checkdata'] = 'kosong';
                    }
                    $newSidak_mua[$key][$key1]['total_brd'] = $totskor_brd1;
                    $newSidak_mua[$key][$key1]['total_janjang'] = $totskor_janjang1;
                    $newSidak_mua[$key][$key1]['new_deviden'] = $new_dvd;
                    $newSidak_mua[$key][$key1]['asisten'] = $namaGM;
                    $newSidak_mua[$key][$key1]['total_skoreest'] = $total_skoreest;
                    if ($v2check4 == 0) {
                        $newSidak_mua[$key][$key1]['total_score'] = '-';
                    } else {
                        $newSidak_mua[$key][$key1]['total_score'] = $newscore;
                    }

                    $newSidak_mua[$key][$key1]['est'] = $key;
                    $newSidak_mua[$key][$key1]['afd'] = $key1;
                    $newSidak_mua[$key][$key1]['devidenest'] = $newpembagi1;
                    $newSidak_mua[$key][$key1]['v2check4'] = $v2check4;

                    $tot_estAFd += $newscore;
                    $new_dvdAfd += $new_dvd;
                    $new_dvdAfdest += $new_dvdest;
                    $v2check5 += $v2check4;
                    $newpembagi3 += $newpembagi2;
                    $chartbrd3 += $chartbrd2;
                    $chartrst3 += $chartrst2;
                } else {
                    $newSidak_mua[$key][$key1]['total_brd'] = 0;
                    $newSidak_mua[$key][$key1]['total_janjang'] = 0;
                    $newSidak_mua[$key][$key1]['new_deviden'] = 0;
                    $newSidak_mua[$key][$key1]['asisten'] = 0;
                    $newSidak_mua[$key][$key1]['total_skoreest'] = 0;
                    $newSidak_mua[$key][$key1]['total_score'] = '-';
                    $newSidak_mua[$key][$key1]['est'] = $key;
                    $newSidak_mua[$key][$key1]['checkdata'] = 'kosong';
                    $newSidak_mua[$key][$key1]['afd'] = $key1;
                    $newSidak_mua[$key][$key1]['devidenest'] = 0;
                    $newSidak_mua[$key][$key1]['v2check4'] = 0;
                }


                if ($v2check5 != 0) {
                    $total_skoreest = round($tot_estAFd / $newpembagi3, 1);
                    $newSidak_mua[$key]['checkdata'] = 'ada';
                } else if ($v2check5 != 0 && $tot_estAFd == 0) {
                    $total_skoreest = 100;
                    $newSidak_mua[$key]['checkdata'] = 'ada';
                } else {
                    $total_skoreest = 0;
                    $newSidak_mua[$key]['checkdata'] = 'kosong';
                }

                // dd($value);


                $namaGM = get_nama_em($key);
                if ($new_dvdAfd != 0) {
                    $newSidak_mua[$key]['deviden'] = 1;
                } else {
                    $newSidak_mua[$key]['deviden'] = 0;
                }

                $newSidak_mua[$key]['total_skorest'] = $tot_estAFd;
                $newSidak_mua[$key]['score_estate'] = $total_skoreest;
                $newSidak_mua[$key]['asisten'] = $namaGM;
                $newSidak_mua[$key]['estate'] = $key;
                $newSidak_mua[$key]['afd'] = 'GM';
                $newSidak_mua[$key]['afdeling'] = $newpembagi3;
                $newSidak_mua[$key]['v2check5'] = $v2check5;
                if ($v2check5 != 0) {
                    $devidenlast = 1;
                } else {
                    $devidenlast = 0;
                }
                $devmuxa[] = $devidenlast;

                $tot_estAFdx  += $tot_estAFd;
                $new_dvdAfdx  += $new_dvdAfd;
                $new_dvdAfdesx += $new_dvdAfdest;
                $v2check5x += $v2check5;
                $chartbrd4 += $chartbrd3;
                $chartrst4 += $chartrst3;
            }
            $devmuxax = array_sum($devmuxa);

            if ($v2check5x != 0) {
                $total_skoreestxyz = round($tot_estAFdx / $devmuxax, 1);
                $checkdata = 'ada';
            } else if ($v2check5x != 0 && $devmuxax != 0) {
                $total_skoreestxyz = 0;
                $checkdata = 'ada';
            } else {
                $total_skoreestxyz = '-';
                $checkdata = 'kosong';
            }

            // dd($value);


            $namaGMnewSidak_mua = get_nama_em('MUA');
            $newSidak_mua['PT.MUA'] = [
                'deviden' => $devmuxax,
                'checkdata' => $checkdata,
                'total_skorest' => $tot_estAFdx,
                'score_estate' => $total_skoreestxyz,
                'asisten' => $namaGMnewSidak_mua,
                'estate' => $key,
                'chartbrd' => $chartbrd4,
                'chartrst' => $chartrst4,
                'brd' => $chartbrd4,
                'buah' => $chartrst4,
                'afd' => $namaGMnewSidak_mua,
                'afdeling' => $devmuxax,
                'v2check6' => $v2check5,
            ];
            unset($rekap_perestate_groupwill[3]['SRE']);
            unset($rekap_perestate_groupwill[3]['LDE']);
            $rekap_perestate_groupwill[3]['PT.MUA']['estate'] = $newSidak_mua['PT.MUA'];
        } else {
            $newSidak_mua = [];
        }
        // dd($rekap_perestate_groupwill);
        // dd($rekap_perestate_groupwill, $rekap_perwill_groupwil);
        $datachart_est = [];
        foreach ($rekap_perestate_groupwill as $key => $value) {
            foreach ($value as $key1 => $value1) {
                foreach ($value1 as $key2 => $value2) {

                    $datachart_est[$key1] = $value2;
                }
            }
        }

        // dd($rekap_perwill_groupwil);
        return [
            'week1' => $week1,
            'week2' => $week2,
            'week3' => $week3,
            'week4' => $week4,
            'week5' => $week5,
            'rekap_perestate_groupwill' => $rekap_perestate_groupwill,
            'rekap_perafdeling_groupwil' => $rekap_perafdeling_groupwil,
            'rekap_perwill_groupwil' => $rekap_perwill_groupwil,
            'rekap_rh' => $rekap_rh,
            'datachart_est' => $datachart_est,
        ];
    }
}
//helper untuk sidaktph pertahun
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
                    // $ass = '-';
                    // foreach ($queryAsisten as $ast => $asisten) {
                    //     if ($key2 === $asisten['est'] && 'OA' === $asisten['afd']) {
                    //         $ass = $asisten['nama'];
                    //         break;
                    //     }
                    // }
                    $ass = get_nama_asisten($key2, 'OA');
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
                // $namasisten = ''; // Initialize $namasisten before the loop
                // foreach ($queryAsisten as $ast => $asisten) {
                //     if ('PT.MUA' === $asisten['est'] && 'EM' === $asisten['afd']) {
                //         $namasisten = $asisten['nama'];
                //         break;
                //     }
                // }
                $namasisten = get_nama_em('PT.MUA');
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

            // $nama_em = '';
            $newkey = 'WIL-' . convertToRoman($key);
            // // dd($newkey);
            // foreach ($queryAsisten as $ast => $asisten) {
            //     if ($newkey === $asisten['est'] && $em === $asisten['afd']) {
            //         $nama_em = $asisten['nama'] ?? '-';
            //     }
            // }
            $nama_em = get_nama_gm($newkey);
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

        // $nama_em = '';
        $newkey = 'REG-' . convertToRoman($regional);
        // // dd($newkey);
        // foreach ($queryAsisten as $ast => $asisten) {
        //     if ($newkey === $asisten['est'] && $em === $asisten['afd']) {
        //         $nama_em = $asisten['nama'] ?? '-';
        //     }
        // }
        $nama_em = get_nama_rh($newkey);
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
                    // foreach ($queryAsisten as $keyx => $valuex) if ($valuex['est'] === $key1 && $valuex['afd'] === $key2) {
                    //     $data[$key][$key1][$key2]['nama'] = $valuex['nama'] ?? '-';
                    //     break;
                    // }
                    $data[$key][$key1][$key2]['nama'] = get_nama_asisten($key1, $key2);
                    // $data[$key][$key1][$key2]['nama'] = 'nama';
                    $data[$key][$key1][$key2]['total_score'] = $value2['total_score'];
                    $data[$key][$key1][$key2]['est'] = $key1;
                    $data[$key][$key1][$key2]['afd'] = $key2;
                    $data[$key][$key1][$key2]['bgcolor'] = 'white';
                }
                // $nama = '-';
                // foreach ($queryAsisten as $keyx => $valuex) if ($valuex['est'] === $key1 && $valuex['afd'] === 'EM') {
                //     $nama = $valuex['nama'] ?? '-';
                //     break;
                // }
                $nama = get_nama_em($key1);
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

//helper halaman sidakmutubuah perbulan
if (!function_exists('rekap_sidakmutubuah_bulan')) {
    function rekap_sidakmutubuah_bulan($bulan, $regional)
    {
        $queryEste = DB::connection('mysql2')->table('estate')
            ->select('estate.*')
            ->where('estate.emp', '!=', 1)
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->where('wil.regional', $regional)
            ->where('estate.emp', '!=', 1)
            // ->whereNotIn('estate.est', ['SRE', 'LDE'])
            ->get();
        $queryEste = json_decode($queryEste, true);

        $queryAfd = DB::connection('mysql2')->table('afdeling')
            ->select(
                'afdeling.id',
                'afdeling.nama',
                'estate.est'
            ) //buat mengambil data di estate db dan willayah db
            ->join('estate', 'estate.id', '=', 'afdeling.estate') //kemudian di join untuk mengambil est perwilayah
            ->get();
        $queryAfd = json_decode($queryAfd, true);
        $muaest = DB::connection('mysql2')->table('estate')
            ->select('estate.*')
            ->where('estate.emp', '!=', 1)
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->where('wil.regional', $regional)
            // ->where('estate.emp', '!=', 1)
            ->whereIn('estate.est', ['SRE', 'LDE'])
            ->get('est');
        $muaest = json_decode($muaest, true);

        $queryMTbuah = DB::connection('mysql2')->table('sidak_mutu_buah')
            ->select(
                "sidak_mutu_buah.*",
                DB::raw('DATE_FORMAT(sidak_mutu_buah.datetime, "%M") as bulan'),
                DB::raw('DATE_FORMAT(sidak_mutu_buah.datetime, "%Y") as tahun')
            )
            ->where('sidak_mutu_buah.datetime', 'like', '%' . $bulan . '%')
            // ->whereNotIn('estate', ['Plasma1', 'Plasma2', 'Plasma3'])
            // ->whereBetween('sidak_mutu_buah.datetime', ['2023-04-03', '2023-04-09'])
            ->get();
        $queryMTbuah = $queryMTbuah->groupBy(['estate', 'afdeling']);
        $queryMTbuah = json_decode($queryMTbuah, true);

        $queryAsisten = DB::connection('mysql2')->table('asisten_qc')
            ->select('asisten_qc.*')
            ->get();
        $queryAsisten = json_decode($queryAsisten, true);
        $databulananBuah = array();
        foreach ($queryMTbuah as $key => $value) {
            foreach ($value as $key2 => $value2) {
                foreach ($value2 as $key3 => $value3) {

                    $databulananBuah[$key][$key2][$key3] = $value3;
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
        function dodo($values)
        {
            foreach ($values as $value) {
                if ($value > 0) {
                    return true;
                }
            }
            return false;
        }

        $estate = DB::connection('mysql2')->table('estate')
            ->select('*')
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->where('wil.regional', $regional)
            ->where('estate.emp', '!=', 1)
            ->get();
        $estate = $estate->groupBy(['wil', 'est']);
        $estate = json_decode($estate, true);


        $rekap = array();
        foreach ($defPerbulanWil as $key => $value) {
            foreach ($estate as $key1 => $wil) {
                foreach ($wil as $key2 => $est) {
                    if ($key2 === $key) {
                        $rekap[$key1][$key] = $value;
                    }
                }
            }
        }

        $sidak_mutubuah = [];
        foreach ($rekap as $key => $value) {
            $totalJJG2 = 0;
            $totaltnpBRD2 = 0;
            $totalkrgBRD2 = 0;
            $totalabr2 = 0;
            $totoverripe2 = 0;
            $totempty2 = 0;
            $totRD2 = 0;
            $totBlok2 = 0;
            $totKR2 = 0;
            $totVcut2 = 0;
            foreach ($value as $key1 => $value1) {
                $totalJJG = 0;
                $totaltnpBRD = 0;
                $totalkrgBRD = 0;
                $totalabr = 0;
                $totoverripe = 0;
                $totempty = 0;
                $totRD = 0;
                $totBlok = 0;
                $totKR = 0;
                $totVcut = 0;
                foreach ($value1 as $key2 => $value2) if (is_array($value2)) {
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
                    foreach ($value2 as $key3 => $value3) {
                        $combination = $value3['blok'] . ' ' . $value3['estate'] . ' ' . $value3['afdeling'] . ' ' . $value3['tph_baris'];
                        if (!isset($combination_counts[$combination])) {
                            $combination_counts[$combination] = 0;
                        }
                        $newblok = count($value2);
                        $jjg_sample += $value3['jumlah_jjg'];
                        $tnpBRD += $value3['bmt'];
                        $krgBRD += $value3['bmk'];
                        $abr += $value3['abnormal'];
                        $overripe += $value3['overripe'];
                        $empty += $value3['empty_bunch'];
                        $vcut += $value3['vcut'];
                        $rd += $value3['rd'];
                        $sum_kr += $value3['alas_br'];
                    }
                    $dataBLok = $newblok;
                    if ($sum_kr != 0) {
                        $total_kr = round($sum_kr / $dataBLok, 3);
                    } else {
                        $total_kr = 0;
                    }
                    $per_kr = round($total_kr * 100, 3);
                    $skor_total = round((($tnpBRD + $krgBRD) / ($jjg_sample - $abr)) * 100, 3);
                    $skor_jjgMSk = round(($jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr)) / ($jjg_sample - $abr) * 100, 3);
                    $skor_lewatMTng =  round(($overripe / ($jjg_sample - $abr)) * 100, 3);
                    $skor_jjgKosong =  round(($empty / ($jjg_sample - $abr)) * 100, 3);
                    $skor_vcut =   round(($vcut / $jjg_sample) * 100, 3);
                    $allSkor = sidak_brdTotal($skor_total) +  sidak_matangSKOR($skor_jjgMSk) +  sidak_lwtMatang($skor_lewatMTng) + sidak_jjgKosong($skor_jjgKosong) + sidak_tangkaiP($skor_vcut) + sidak_PengBRD($per_kr);
                    // dd($key1, $key2);
                    $sidak_mutubuah[$key][$key1][$key2]['reg'] = 'REG-I';
                    $sidak_mutubuah[$key][$key1][$key2]['pt'] = 'SSMS';
                    $sidak_mutubuah[$key][$key1][$key2]['Jumlah_janjang'] = $jjg_sample;
                    $sidak_mutubuah[$key][$key1][$key2]['blok'] = $dataBLok;
                    $sidak_mutubuah[$key][$key1][$key2]['est'] = $key;
                    $sidak_mutubuah[$key][$key1][$key2]['afd'] = $key1;
                    $sidak_mutubuah[$key][$key1][$key2]['nama_staff'] = get_nama_asisten($key1, $key2);
                    $sidak_mutubuah[$key][$key1][$key2]['tnp_brd'] = $tnpBRD;
                    $sidak_mutubuah[$key][$key1][$key2]['krg_brd'] = $krgBRD;
                    $sidak_mutubuah[$key][$key1][$key2]['persenTNP_brd'] = round(($tnpBRD / ($jjg_sample - $abr)) * 100, 3);
                    $sidak_mutubuah[$key][$key1][$key2]['persenKRG_brd'] = round(($krgBRD / ($jjg_sample - $abr)) * 100, 3);
                    $sidak_mutubuah[$key][$key1][$key2]['total_jjg'] = $tnpBRD + $krgBRD;
                    $sidak_mutubuah[$key][$key1][$key2]['persen_totalJjg'] = $skor_total;
                    $sidak_mutubuah[$key][$key1][$key2]['skor_total'] = sidak_brdTotal($skor_total);
                    $sidak_mutubuah[$key][$key1][$key2]['jjg_matang'] = $jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr);
                    $sidak_mutubuah[$key][$key1][$key2]['persen_jjgMtang'] = $skor_jjgMSk;
                    $sidak_mutubuah[$key][$key1][$key2]['skor_jjgMatang'] = sidak_matangSKOR($skor_jjgMSk);
                    $sidak_mutubuah[$key][$key1][$key2]['lewat_matang'] = $overripe;
                    $sidak_mutubuah[$key][$key1][$key2]['persen_lwtMtng'] =  $skor_lewatMTng;
                    $sidak_mutubuah[$key][$key1][$key2]['skor_lewatMTng'] = sidak_lwtMatang($skor_lewatMTng);
                    $sidak_mutubuah[$key][$key1][$key2]['janjang_kosong'] = $empty;
                    $sidak_mutubuah[$key][$key1][$key2]['persen_kosong'] = $skor_jjgKosong;
                    $sidak_mutubuah[$key][$key1][$key2]['skor_kosong'] = sidak_jjgKosong($skor_jjgKosong);
                    $sidak_mutubuah[$key][$key1][$key2]['vcut'] = $vcut;
                    $sidak_mutubuah[$key][$key1][$key2]['vcut_persen'] = $skor_vcut;
                    $sidak_mutubuah[$key][$key1][$key2]['vcut_skor'] = sidak_tangkaiP($skor_vcut);
                    $sidak_mutubuah[$key][$key1][$key2]['abnormal'] = $abr;
                    $sidak_mutubuah[$key][$key1][$key2]['abnormal_persen'] = round(($abr / $jjg_sample) * 100, 3);
                    $sidak_mutubuah[$key][$key1][$key2]['rat_dmg'] = $rd;
                    $sidak_mutubuah[$key][$key1][$key2]['rd_persen'] = round(($rd / $jjg_sample) * 100, 3);
                    $sidak_mutubuah[$key][$key1][$key2]['TPH'] = $total_kr;
                    $sidak_mutubuah[$key][$key1][$key2]['persen_krg'] = $per_kr;
                    $sidak_mutubuah[$key][$key1][$key2]['karung'] = $sum_kr;
                    $sidak_mutubuah[$key][$key1][$key2]['skor_kr'] = sidak_PengBRD($per_kr);
                    $sidak_mutubuah[$key][$key1][$key2]['All_skor'] = $allSkor;
                    $sidak_mutubuah[$key][$key1][$key2]['kategori'] = sidak_akhir($allSkor);
                    $sidak_mutubuah[$key][$key1][$key2]['newblok'] = $newblok;

                    $totalJJG += $jjg_sample;
                    $totaltnpBRD += $tnpBRD;
                    $totalkrgBRD += $krgBRD;
                    $totalabr += $abr;
                    $totoverripe += $overripe;
                    $totempty += $empty;
                    $totRD += $rd;
                    $totBlok += $dataBLok;
                    $totKR += $sum_kr;
                    $totVcut += $vcut;
                } else {
                    $sidak_mutubuah[$key][$key1][$key2]['reg'] = 'REG-I';
                    $sidak_mutubuah[$key][$key1][$key2]['pt'] = 'SSMS';
                    $sidak_mutubuah[$key][$key1][$key2]['Jumlah_janjang'] = 0;
                    $sidak_mutubuah[$key][$key1][$key2]['blok'] = 0;
                    $sidak_mutubuah[$key][$key1][$key2]['est'] = $key;
                    $sidak_mutubuah[$key][$key1][$key2]['afd'] = $key1;
                    $sidak_mutubuah[$key][$key1][$key2]['nama_staff'] = get_nama_asisten($key1, $key2);
                    $sidak_mutubuah[$key][$key1][$key2]['tnp_brd'] = 0;
                    $sidak_mutubuah[$key][$key1][$key2]['krg_brd'] = 0;
                    $sidak_mutubuah[$key][$key1][$key2]['persenTNP_brd'] = 0;
                    $sidak_mutubuah[$key][$key1][$key2]['persenKRG_brd'] = 0;
                    $sidak_mutubuah[$key][$key1][$key2]['total_jjg'] = 0;
                    $sidak_mutubuah[$key][$key1][$key2]['persen_totalJjg'] = 0;
                    $sidak_mutubuah[$key][$key1][$key2]['skor_total'] = 0;
                    $sidak_mutubuah[$key][$key1][$key2]['jjg_matang'] = 0;
                    $sidak_mutubuah[$key][$key1][$key2]['persen_jjgMtang'] = 0;
                    $sidak_mutubuah[$key][$key1][$key2]['skor_jjgMatang'] = 0;
                    $sidak_mutubuah[$key][$key1][$key2]['lewat_matang'] = 0;
                    $sidak_mutubuah[$key][$key1][$key2]['persen_lwtMtng'] =  0;
                    $sidak_mutubuah[$key][$key1][$key2]['skor_lewatMTng'] = 0;
                    $sidak_mutubuah[$key][$key1][$key2]['janjang_kosong'] = 0;
                    $sidak_mutubuah[$key][$key1][$key2]['persen_kosong'] = 0;
                    $sidak_mutubuah[$key][$key1][$key2]['skor_kosong'] = 0;
                    $sidak_mutubuah[$key][$key1][$key2]['vcut'] = 0;
                    $sidak_mutubuah[$key][$key1][$key2]['vcut_persen'] = 0;
                    $sidak_mutubuah[$key][$key1][$key2]['vcut_skor'] = 0;
                    $sidak_mutubuah[$key][$key1][$key2]['abnormal'] = 0;
                    $sidak_mutubuah[$key][$key1][$key2]['abnormal_persen'] = 0;
                    $sidak_mutubuah[$key][$key1][$key2]['rat_dmg'] = 0;
                    $sidak_mutubuah[$key][$key1][$key2]['rd_persen'] = 0;
                    $sidak_mutubuah[$key][$key1][$key2]['TPH'] = 0;
                    $sidak_mutubuah[$key][$key1][$key2]['persen_krg'] = 0;
                    $sidak_mutubuah[$key][$key1][$key2]['karung'] = 0;
                    $sidak_mutubuah[$key][$key1][$key2]['skor_kr'] = 0;
                    $sidak_mutubuah[$key][$key1][$key2]['All_skor'] = 0;
                    $sidak_mutubuah[$key][$key1][$key2]['kategori'] = 0;
                    $sidak_mutubuah[$key][$key1][$key2]['newblok'] = 0;
                }
                $temp_jggabr = $totalJJG - $totalabr;
                $TotPersenTNP = ($temp_jggabr != 0) ? round(($totaltnpBRD / ($temp_jggabr)) * 100, 3) : 0;
                $TotPersenKRG =  ($temp_jggabr != 0) ? round(($totalkrgBRD / ($temp_jggabr)) * 100, 3) : 0;
                $totJJG = $totaltnpBRD + $totalkrgBRD;
                $totPersenTOtaljjg = ($temp_jggabr != 0) ?  round((($totaltnpBRD + $totalkrgBRD) / ($temp_jggabr)) * 100, 3) : 0;
                $totJJG_matang = $totalJJG - ($totaltnpBRD + $totalkrgBRD + $totoverripe + $totempty + $totalabr);
                $totPer_jjgMtng =  ($temp_jggabr != 0) ? round($totJJG_matang / ($temp_jggabr) * 100, 3) : 0;
                $totPer_over =  ($temp_jggabr != 0) ? round(($totoverripe / ($temp_jggabr)) * 100, 3) : 0;
                $totPer_Empty =  ($temp_jggabr != 0) ? round(($totempty / ($temp_jggabr)) * 100, 3) : 0;
                $totPer_vcut = ($totalJJG != 0) ?  round(($totVcut / $totalJJG) * 100, 3) : 0;
                $totPer_abr =  ($totalJJG != 0) ? round(($totalabr / $totalJJG) * 100, 3) : 0;
                $totPer_rd = ($totalJJG != 0) ? round(($totRD / $totalJJG) * 100, 3) : 0;
                $tot_krS = ($totBlok != 0) ? round($totKR / $totBlok, 3) : 0;
                $totPer_kr = round($tot_krS * 100, 3);
                $totSkor_kr = ($totBlok != 0) ? sidak_PengBRD($totPer_kr) : 0;
                $totSkor_Vcut =   ($totBlok != 0) ? sidak_tangkaiP($totPer_vcut) : 0;
                $totSkor_Empty =  ($totBlok != 0) ? sidak_jjgKosong($totPer_Empty) : 0;
                $totSkor_Over =  ($totBlok != 0) ? sidak_lwtMatang($totPer_over) : 0;
                $totSkor_jjgMtng =  ($totBlok != 0) ? sidak_matangSKOR($totPer_jjgMtng) : 0;
                $totSkor_total =  ($totBlok != 0) ? sidak_brdTotal($totPersenTOtaljjg) : 0;
                $totalskor = $totSkor_kr + $totSkor_Vcut + $totSkor_Empty + $totSkor_Over + $totSkor_jjgMtng + $totSkor_total;
                $totKategor = sidak_akhir($totalskor);
                $sidak_mutubuah[$key][$key1]['ESTATE'] = [
                    'reg' => '',
                    'pt' => '',
                    'nama_staff' => get_nama_em($key1),
                    'Jumlah_janjang' => $totalJJG,
                    'est' => $key1,
                    'afd' => 'afdeling',
                    'karung' => $totKR,
                    'blok' => $totBlok,
                    'tnp_brd' => $totaltnpBRD,
                    'krg_brd' => $totalkrgBRD,
                    'persenTNP_brd' => $TotPersenTNP,
                    'persenKRG_brd' => $TotPersenKRG,
                    'total_jjg' => $totJJG,
                    'persen_totalJjg' => $totPersenTOtaljjg,
                    'skor_total' => $totSkor_total,
                    'jjg_matang' => $totJJG_matang,
                    'persen_jjgMtang' => $totPer_jjgMtng,
                    'skor_jjgMatang' => $totSkor_jjgMtng,
                    'lewat_matang' => $totoverripe,
                    'persen_lwtMtng' => $totPer_over,
                    'skor_lewatMTng' => $totSkor_Over,
                    'janjang_kosong' => $totempty,
                    'persen_kosong' => $totPer_Empty,
                    'skor_kosong' => $totSkor_Empty,
                    'vcut' => $totVcut,
                    'vcut_persen' => $totPer_vcut,
                    'vcut_skor' => $totSkor_Vcut,
                    'abnormal' => $totalabr,
                    'abnormal_persen' => $totPer_abr,
                    'rat_dmg' => $totRD,
                    'rd_persen' => $totPer_rd,
                    'TPH' => $tot_krS,
                    'persen_krg' => $totPer_kr,
                    'skor_kr' => $totSkor_kr,
                    'All_skor' => $totalskor,
                    'kategori' => $totKategor,
                ];

                $totalJJG2 += $totalJJG;
                $totaltnpBRD2 += $totaltnpBRD;
                $totalkrgBRD2 += $totalkrgBRD;
                $totalabr2 += $totalabr;
                $totoverripe2 += $totoverripe;
                $totempty2 += $totempty;
                $totRD2 += $totRD;
                $totBlok2 += $totBlok;
                $totKR2 += $totKR;
                $totVcut2 += $totVcut;
            }
            $temp_jggabr = $totalJJG2 - $totalabr2;
            $TotPersenTNP = ($temp_jggabr != 0) ? round(($totaltnpBRD2 / ($temp_jggabr)) * 100, 3) : 0;
            $TotPersenKRG =  ($temp_jggabr != 0) ? round(($totalkrgBRD2 / ($temp_jggabr)) * 100, 3) : 0;
            $totJJG = $totaltnpBRD2 + $totalkrgBRD2;
            $totPersenTOtaljjg = ($temp_jggabr != 0) ?  round((($totaltnpBRD2 + $totalkrgBRD2) / ($temp_jggabr)) * 100, 3) : 0;
            $totJJG_matang = $totalJJG2 - ($totaltnpBRD2 + $totalkrgBRD2 + $totoverripe2 + $totempty2 + $totalabr2);
            $totPer_jjgMtng =  ($temp_jggabr != 0) ? round($totJJG_matang / ($temp_jggabr) * 100, 3) : 0;
            $totPer_over =  ($temp_jggabr != 0) ? round(($totoverripe2 / ($temp_jggabr)) * 100, 3) : 0;
            $totPer_Empty =  ($temp_jggabr != 0) ? round(($totempty2 / ($temp_jggabr)) * 100, 3) : 0;
            $totPer_vcut = ($totalJJG2 != 0) ?  round(($totVcut2 / $totalJJG2) * 100, 3) : 0;
            $totPer_abr =  ($totalJJG2 != 0) ? round(($totalabr2 / $totalJJG2) * 100, 3) : 0;
            $totPer_rd = ($totalJJG2 != 0) ? round(($totRD2 / $totalJJG2) * 100, 3) : 0;
            $tot_krS = ($totBlok2 != 0) ? round($totKR2 / $totBlok2, 3) : 0;
            $totPer_kr = round($tot_krS * 100, 3);
            $totSkor_kr = ($totBlok2 != 0) ? sidak_PengBRD($totPer_kr) : 0;
            $totSkor_Vcut =   ($totBlok2 != 0) ? sidak_tangkaiP($totPer_vcut) : 0;
            $totSkor_Empty =  ($totBlok2 != 0) ? sidak_jjgKosong($totPer_Empty) : 0;
            $totSkor_Over =  ($totBlok2 != 0) ? sidak_lwtMatang($totPer_over) : 0;
            $totSkor_jjgMtng =  ($totBlok2 != 0) ? sidak_matangSKOR($totPer_jjgMtng) : 0;
            $totSkor_total =  ($totBlok2 != 0) ? sidak_brdTotal($totPersenTOtaljjg) : 0;
            $totalskor = $totSkor_kr + $totSkor_Vcut + $totSkor_Empty + $totSkor_Over + $totSkor_jjgMtng + $totSkor_total;
            $totKategor = sidak_akhir($totalskor);
            // dd($key);
            $sidak_mutubuah[$key]['WIL']['WIL-' . convertToRoman($key)] = [
                'reg' => '',
                'pt' => '',
                'nama_staff' => get_nama_gm($key),
                'Jumlah_janjang' => $totalJJG,
                'est' => $key1,
                'afd' => 'afdeling',
                'karung' => $totKR,
                'blok' => $totBlok,
                'tnp_brd' => $totaltnpBRD,
                'krg_brd' => $totalkrgBRD,
                'persenTNP_brd' => $TotPersenTNP,
                'persenKRG_brd' => $TotPersenKRG,
                'total_jjg' => $totJJG,
                'persen_totalJjg' => $totPersenTOtaljjg,
                'skor_total' => $totSkor_total,
                'jjg_matang' => $totJJG_matang,
                'persen_jjgMtang' => $totPer_jjgMtng,
                'skor_jjgMatang' => $totSkor_jjgMtng,
                'lewat_matang' => $totoverripe,
                'persen_lwtMtng' => $totPer_over,
                'skor_lewatMTng' => $totSkor_Over,
                'janjang_kosong' => $totempty,
                'persen_kosong' => $totPer_Empty,
                'skor_kosong' => $totSkor_Empty,
                'vcut' => $totVcut,
                'vcut_persen' => $totPer_vcut,
                'vcut_skor' => $totSkor_Vcut,
                'abnormal' => $totalabr,
                'abnormal_persen' => $totPer_abr,
                'rat_dmg' => $totRD,
                'rd_persen' => $totPer_rd,
                'TPH' => $tot_krS,
                'persen_krg' => $totPer_kr,
                'skor_kr' => $totSkor_kr,
                'All_skor' => $totalskor,
                'kategori' => $totKategor,
            ];
        }
        // dd($sidak_mutubuah);
        // $data_for_mua = $sidak_mutubuah;
        // dd($sidak_mutubuah);
        if ($regional == 1) {


            $defaultmua = array();

            foreach ($muaest as $key2 => $value2) {
                foreach ($queryAfd as $key3 => $value3) {
                    if ($value2['est'] == $value3['est']) {
                        $defaultmua[$value2['est']][$value3['est']] = 0;
                    }
                }
            }
            foreach ($defaultmua as $estateKey => $afdelingArray) {
                foreach ($afdelingArray as $afdelingKey => $afdelingValue) {
                    if (isset($databulananBuah[$estateKey]) && isset($databulananBuah[$estateKey][$afdelingKey])) {
                        $defaultmua[$estateKey][$afdelingKey] = $databulananBuah[$estateKey][$afdelingKey];
                    }
                }
            }

            $sidak_buah_mua = array();
            // dd($defaultmua);
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
            foreach ($defaultmua as $key => $value) {
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
                            // dd($key2);
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

                        // dd($key);

                        $sidak_buah_mua[$key][$key1]['Jumlah_janjang'] = $jjg_sample;
                        $sidak_buah_mua[$key][$key1]['newblok'] = $dataBLok;
                        $sidak_buah_mua[$key][$key1]['est'] = $key;
                        $sidak_buah_mua[$key][$key1]['afd'] = 'OA';
                        $sidak_buah_mua[$key][$key1]['nama_staff'] = get_nama_asisten($key, $key1);
                        $sidak_buah_mua[$key][$key1]['tnp_brd'] = $tnpBRD;
                        $sidak_buah_mua[$key][$key1]['krg_brd'] = $krgBRD;
                        $sidak_buah_mua[$key][$key1]['persenTNP_brd'] = round(($tnpBRD / ($jjg_sample - $abr)) * 100, 2);
                        $sidak_buah_mua[$key][$key1]['persenKRG_brd'] = round(($krgBRD / ($jjg_sample - $abr)) * 100, 2);
                        $sidak_buah_mua[$key][$key1]['total_jjg'] = $tnpBRD + $krgBRD;
                        $sidak_buah_mua[$key][$key1]['persen_totalJjg'] = $skor_total;
                        $sidak_buah_mua[$key][$key1]['skor_total'] = sidak_brdTotal($skor_total);
                        $sidak_buah_mua[$key][$key1]['jjg_matang'] = $jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty + $abr);
                        $sidak_buah_mua[$key][$key1]['persen_jjgMtang'] = $skor_jjgMSk;
                        $sidak_buah_mua[$key][$key1]['skor_jjgMatang'] = sidak_matangSKOR($skor_jjgMSk);
                        $sidak_buah_mua[$key][$key1]['lewat_matang'] = $overripe;
                        $sidak_buah_mua[$key][$key1]['persen_lwtMtng'] =  $skor_lewatMTng;
                        $sidak_buah_mua[$key][$key1]['skor_lewatMTng'] = sidak_lwtMatang($skor_lewatMTng);
                        $sidak_buah_mua[$key][$key1]['janjang_kosong'] = $empty;
                        $sidak_buah_mua[$key][$key1]['persen_kosong'] = $skor_jjgKosong;
                        $sidak_buah_mua[$key][$key1]['skor_kosong'] = sidak_jjgKosong($skor_jjgKosong);
                        $sidak_buah_mua[$key][$key1]['vcut'] = $vcut;
                        $sidak_buah_mua[$key][$key1]['karung'] = $sum_kr;
                        $sidak_buah_mua[$key][$key1]['vcut_persen'] = $skor_vcut;
                        $sidak_buah_mua[$key][$key1]['vcut_skor'] = sidak_tangkaiP($skor_vcut);
                        $sidak_buah_mua[$key][$key1]['abnormal'] = $abr;
                        $sidak_buah_mua[$key][$key1]['abnormal_persen'] = round(($abr / $jjg_sample) * 100, 2);
                        $sidak_buah_mua[$key][$key1]['rat_dmg'] = $rd;
                        $sidak_buah_mua[$key][$key1]['rd_persen'] = round(($rd / $jjg_sample) * 100, 2);
                        $sidak_buah_mua[$key][$key1]['TPH'] = $total_kr;
                        $sidak_buah_mua[$key][$key1]['persen_krg'] = $per_kr;
                        $sidak_buah_mua[$key][$key1]['karung'] = $sum_kr;
                        $sidak_buah_mua[$key][$key1]['skor_kr'] = sidak_PengBRD($per_kr);
                        $sidak_buah_mua[$key][$key1]['All_skor'] = $allSkor;
                        $sidak_buah_mua[$key][$key1]['csfxr'] = $csfxr;
                        $sidak_buah_mua[$key][$key1]['kategori'] = sidak_akhir($allSkor);


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
                        $sidak_buah_mua[$key][$key1]['Jumlah_janjang'] = 0;
                        $sidak_buah_mua[$key][$key1]['newblok'] = 0;
                        $sidak_buah_mua[$key][$key1]['est'] = $key;
                        $sidak_buah_mua[$key][$key1]['afd'] = 'OA';
                        $sidak_buah_mua[$key][$key1]['nama_staff'] = get_nama_asisten($key, $key1);
                        $sidak_buah_mua[$key][$key1]['tnp_brd'] = 0;
                        $sidak_buah_mua[$key][$key1]['krg_brd'] = 0;
                        $sidak_buah_mua[$key][$key1]['persenTNP_brd'] = 0;
                        $sidak_buah_mua[$key][$key1]['persenKRG_brd'] = 0;
                        $sidak_buah_mua[$key][$key1]['total_jjg'] = 0;
                        $sidak_buah_mua[$key][$key1]['persen_totalJjg'] = 0;
                        $sidak_buah_mua[$key][$key1]['skor_total'] = 0;
                        $sidak_buah_mua[$key][$key1]['jjg_matang'] = 0;
                        $sidak_buah_mua[$key][$key1]['persen_jjgMtang'] = 0;
                        $sidak_buah_mua[$key][$key1]['skor_jjgMatang'] = 0;
                        $sidak_buah_mua[$key][$key1]['lewat_matang'] = 0;
                        $sidak_buah_mua[$key][$key1]['persen_lwtMtng'] =  0;
                        $sidak_buah_mua[$key][$key1]['skor_lewatMTng'] = 0;
                        $sidak_buah_mua[$key][$key1]['janjang_kosong'] = 0;
                        $sidak_buah_mua[$key][$key1]['persen_kosong'] = 0;
                        $sidak_buah_mua[$key][$key1]['skor_kosong'] = 0;
                        $sidak_buah_mua[$key][$key1]['vcut'] = 0;
                        $sidak_buah_mua[$key][$key1]['karung'] = 0;
                        $sidak_buah_mua[$key][$key1]['vcut_persen'] = 0;
                        $sidak_buah_mua[$key][$key1]['vcut_skor'] = 0;
                        $sidak_buah_mua[$key][$key1]['abnormal'] = 0;
                        $sidak_buah_mua[$key][$key1]['abnormal_persen'] = 0;
                        $sidak_buah_mua[$key][$key1]['rat_dmg'] = 0;
                        $sidak_buah_mua[$key][$key1]['rd_persen'] = 0;
                        $sidak_buah_mua[$key][$key1]['TPH'] = 0;
                        $sidak_buah_mua[$key][$key1]['persen_krg'] = 0;
                        $sidak_buah_mua[$key][$key1]['karung'] = 0;
                        $sidak_buah_mua[$key][$key1]['skor_kr'] = 0;
                        $sidak_buah_mua[$key][$key1]['All_skor'] = 0;
                        $sidak_buah_mua[$key][$key1]['csfxr'] = 0;
                        $sidak_buah_mua[$key][$key1]['kategori'] = 0;
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

                // $nama_em = '';

                // // dd($key1);
                // foreach ($queryAsisten as $ast => $asisten) {
                //     if ($key1 === $asisten['est'] && $em === 'OA') {
                //         $nama_em = $asisten['nama'];
                //         break;
                //     }
                // }
                $nama_em = get_nama_asisten($key1, 'OA');

                $jjg_mth = $tnpBRDx + $krgBRDx + $overripex + $emptyx;

                $skor_jjgMTh = ($jjg_samplex - $abrx != 0) ? round($jjg_mth / ($jjg_samplex - $abrx) * 100, 2) : 0;


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

            // $nama_em = '';

            // // dd($key1);
            // foreach ($queryAsisten as $ast => $asisten) {
            //     if ('PT.MUA' === $asisten['est'] && $em === $asisten['afd']) {
            //         $nama_em = $asisten['nama'];
            //     }
            // }
            $nama_em = get_nama_em('PT.MUA');
            $jjg_mthxy = $tnpBRDxy + $krgBRDxy + $overripexy + $emptyxy;

            $skor_jjgMTh = ($jjg_samplexy - $abrxy != 0) ? round($jjg_mth / ($jjg_samplexy - $abrxy) * 100, 2) : 0;
            if ($csrmsy == 0) {
                $check_arr = 'kosong';
                $All_skor = '-';
            } else {
                $check_arr = 'ada';
                $All_skor = $allSkor;
            };



            $sidak_buah_mua['PT.MUA'] = [
                'reg' => 'WiL',
                'pt' => 'SSMS',
                'est' => 'PT.MUA',
                'afd' => 'OE',
                'background_color' => '#fffc04',
                'jjg_mantah' => $jjg_mthxy,
                'persen_jjgmentah' => $skor_jjgMTh,
                'check_arr' => $check_arr,
                'all_skor' => $All_skor,
                'Jumlah_janjang' => $jjg_samplexy,
                'csrms' => $csrmsy,
                'blok' => $dataBLokxy,
                'EM' => 'EM',
                'Nama_assist' => $nama_em,
                'nama_staff' => $nama_em,
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
                'karung_est' => $sum_krxy,
                'TPH' => $total_kr,
                'persen_krg' => $per_kr,
                'skor_kr' => sidak_PengBRD($per_kr),
                'kategori' => sidak_akhir($allSkor),
            ];


            $mua_afdeling = [];
            foreach ($sidak_buah_mua as $key => $value) {
                if ($key !== 'PT.MUA') {
                    unset($sidak_buah_mua[$key]);
                    $mua_afdeling[$key] = $value;
                }
            }
            $mua_estate = [];
            foreach ($sidak_buah_mua as $key => $value) {
                if ($key === 'PT.MUA') {
                    unset($sidak_buah_mua[$key]);
                    $mua_estate[$key] = $value;
                }
            }

            $sidak_mutubuah[3]['PT.MUA'] =  $mua_estate;
            // dd($sidak_mutubuah, $sidak_buah_mua);
            // $new_sidakBuah[] = $sidak_buah_mua;

            // dd($arrtest, $new_sidakBuah);

        }
        // dd($sidak_mutubuah);

        // perkelompokan data perwilayah/estate/reg 


        return [
            'data_tabel' => $sidak_mutubuah,
            // 'reg_data' => $regional_arrays,
        ];
    }
}
if (!function_exists('fetch_verified_match')) {
    function fetch_verified_match($estate, $afd, $blok_asli)
    {
        $match = BlokMatch::where('blok_asli', $blok_asli)
            ->where('est', $estate)
            ->where('afd', $afd)
            ->first();
        // dd($match->blok);
        return $match ? $match->blok : null;
    }
}
if (!function_exists('score_by_maps')) {
    function score_by_maps($est, $regData, $date)
    {
        $regData = explode(',', $regData);

        $reg = $regData[0];

        $regional = DB::connection('mysql2')->table('wil')
            ->select('*')
            ->join('estate', 'estate.wil', '=', 'wil.id')
            ->where('estate.wil', $reg)
            ->pluck('regional');
        $regional = $regional[0];
        // dd($regData, $regional);

        // dd($est, $date);
        $queryTrans = DB::connection('mysql2')->table("mutu_transport")
            // ->select("mutu_transport.*", "estate.wil")
            ->select("mutu_transport.*", "estate.wil", DB::raw('DATE_FORMAT(mutu_transport.datetime, "%Y-%m-%d") as date'))
            ->join('estate', 'estate.est', '=', 'mutu_transport.estate')
            ->where('mutu_transport.estate', $est)
            ->whereYear('mutu_transport.datetime', $date)
            ->get();

        if ($regional !== 3 && $regional !== 2) {
            $DataEstate = $queryTrans->groupBy(['blok']);
            // dd('aaa');
        } else {
            // dd('caca');
            $DataEstate = $queryTrans->groupBy(['blok', 'date']);
        }

        $DataEstate = json_decode($DataEstate, true);

        // dd($DataEstate, $regional);
        $queryAncak = DB::connection('mysql2')->table("mutu_ancak_new")
            ->select("mutu_ancak_new.*", "estate.wil", DB::raw('DATE_FORMAT(mutu_ancak_new.datetime, "%Y-%m-%d") as date'))
            ->join('estate', 'estate.est', '=', 'mutu_ancak_new.estate')
            ->where('mutu_ancak_new.estate', $est)
            ->whereYear('mutu_ancak_new.datetime', $date)
            ->orderBy('blok', 'desc')
            ->get();

        if ($regional !== 3 && $regional !== 2) {
            $DataMTAncak = $queryAncak->groupBy(['blok']);
        } else {
            $DataMTAncak = $queryAncak->groupBy(['blok', 'date']);
        }


        $DataMTAncak = json_decode($DataMTAncak, true);

        $queryAncak2 = DB::connection('mysql2')->table("mutu_ancak_new")
            ->select("mutu_ancak_new.*", "estate.wil", DB::raw('DATE_FORMAT(mutu_ancak_new.datetime, "%Y-%m-%d") as date'))
            ->join('estate', 'estate.est', '=', 'mutu_ancak_new.estate')
            ->where('mutu_ancak_new.estate', $est)
            ->whereYear('mutu_ancak_new.datetime', $date)
            ->orderBy('blok', 'desc')
            ->get();
        $queryAncak2 = $queryAncak2->groupBy(['blok']);
        $blokasli_ancak = json_decode($queryAncak2, true);

        $queryTrans2 = DB::connection('mysql2')->table("mutu_transport")
            // ->select("mutu_transport.*", "estate.wil")
            ->select("mutu_transport.*", "estate.wil", DB::raw('DATE_FORMAT(mutu_transport.datetime, "%Y-%m-%d") as date'))
            ->join('estate', 'estate.est', '=', 'mutu_transport.estate')
            ->where('mutu_transport.estate', $est)
            ->whereYear('mutu_transport.datetime', $date)
            ->get();
        $queryTrans2 = $queryTrans2->groupBy(['blok']);
        $blokasli_trans = json_decode($queryTrans2, true);


        function normalizeBlock($block)
        {
            // Remove "P-" prefix if present
            if (strpos($block, 'P-') === 0) {
                $block = substr($block, 2);
            }

            // Remove the first "0" after an initial alphabetic character
            if (preg_match('/^[A-Za-z]0/', $block)) {
                $block = substr($block, 0, 1) . substr($block, 2);
            }

            // Check for block identifiers like 'F006-CBI14'
            if (preg_match('/^[A-Z]+\d+-CBI\d+$/', $block)) {
                return substr($block, 0, strpos($block, '-CBI'));
            }
            // Check for block identifiers like 'O27012'
            elseif (preg_match('/^[A-Z]+\d+$/', $block)) {
                return substr($block, 0, -2);
            }
            // Return the processed block
            return $block;
        }

        // dd($DataEstate, $DataMTAncak);

        // Step 3: Normalize the block identifiers
        $datamutuancak = [];
        foreach ($DataMTAncak as $block => $data) {
            // Normalize the block identifier
            $normalizedBlock = normalizeBlock($block);

            // Initialize the array for this normalized block if not exists
            if (!isset($datamutuancak[$normalizedBlock])) {
                $datamutuancak[$normalizedBlock] = [];
            }

            // Merge the data
            $datamutuancak[$normalizedBlock] = array_merge($datamutuancak[$normalizedBlock], $data);
        }
        $datamututrans = [];
        foreach ($DataEstate as $block => $data) {
            // Normalize the block identifier
            $normalizedBlock = normalizeBlock($block);

            // Initialize the array for this normalized block if not exists
            if (!isset($datamututrans[$normalizedBlock])) {
                $datamututrans[$normalizedBlock] = [];
            }

            // Merge the data
            $datamututrans[$normalizedBlock] = array_merge($datamututrans[$normalizedBlock], $data);
        }



        if ($regional == 3 || $regional == 2) {
            $data_ancak_bydate = [];
            foreach ($blokasli_ancak as $block => $data) {
                // Normalize the block identifier
                $normalizedBlock = normalizeBlock($block);

                // Initialize the array for this normalized block if not exists
                if (!isset($data_ancak_bydate[$normalizedBlock])) {
                    $data_ancak_bydate[$normalizedBlock] = [];
                }

                // Merge the data
                $data_ancak_bydate[$normalizedBlock] = array_merge($data_ancak_bydate[$normalizedBlock], $data);
            }
            $data_trans_bydate = [];
            foreach ($blokasli_trans as $block => $data) {
                // Normalize the block identifier
                $normalizedBlock = normalizeBlock($block);

                // Initialize the array for this normalized block if not exists
                if (!isset($data_trans_bydate[$normalizedBlock])) {
                    $data_trans_bydate[$normalizedBlock] = [];
                }

                // Merge the data
                $data_trans_bydate[$normalizedBlock] = array_merge($data_trans_bydate[$normalizedBlock], $data);
            }
        } else {
            $data_ancak_bydate = $datamutuancak;
            $data_trans_bydate = $datamututrans;
        }


        // dd($datamutuancak, $datamututrans);
        if ($regional === 3) {
            foreach ($datamutuancak as $key => $dates) {
                if (isset($datamututrans[$key])) {
                    $mutuancakDates = array_keys($dates);
                    $mututransportDates = array_keys($datamututrans[$key]);

                    // Find common dates
                    $commonDates = array_intersect($mutuancakDates, $mututransportDates);

                    // Unset dates that are not common in datamutuancak
                    foreach ($mutuancakDates as $date) {
                        if (!in_array($date, $commonDates)) {
                            unset($datamutuancak[$key][$date]);
                        }
                    }

                    // Unset dates that are not common in datamututrans
                    foreach ($mututransportDates as $date) {
                        if (!in_array($date, $commonDates)) {
                            unset($datamututrans[$key][$date]);
                        }
                    }
                }
            }
            foreach ($datamutuancak as $key => $dates) {
                if (empty($dates)) {
                    unset($datamutuancak[$key]);
                }
            }

            // Remove keys with empty arrays from mututransport
            foreach ($datamututrans as $key => $dates) {
                if (empty($dates)) {
                    unset($datamututrans[$key]);
                }
            }

            function ungroupByDate($array)
            {
                $result = [];

                foreach ($array as $blok => $dates) {
                    foreach ($dates as $date => $values) {
                        foreach ($values as $value) {
                            $result[$blok][] = $value;
                        }
                    }
                }

                return $result;
            }

            $datamutuancak = ungroupByDate($datamutuancak);
            $datamututrans = ungroupByDate($datamututrans);
        } else if ($regional == 2) {
            function getNewestDate($array)
            {
                $newestDate = null;
                foreach ($array as $date => $values) {
                    $currentDate = Carbon::parse($date);
                    if ($newestDate === null || $currentDate->greaterThan($newestDate)) {
                        $newestDate = $currentDate;
                    }
                }
                return $newestDate;
            }

            function filterDates(&$array, $newestDate)
            {
                foreach ($array as $date => $values) {
                    $currentDate = Carbon::parse($date);
                    if (!$currentDate->isSameMonth($newestDate)) {
                        unset($array[$date]);
                    }
                }
            }

            foreach ($datamutuancak as $key => &$dates) {
                if (isset($datamututrans[$key])) {
                    $newestDateCak = getNewestDate($dates);
                    $newestDateTrans = getNewestDate($datamututrans[$key]);

                    if ($newestDateCak && (!$newestDateTrans || $newestDateCak->greaterThanOrEqualTo($newestDateTrans))) {
                        filterDates($dates, $newestDateCak);
                        if ($newestDateTrans) {
                            unset($datamututrans[$key][$newestDateTrans->toDateString()]);
                        }
                    } elseif ($newestDateTrans) {
                        filterDates($dates, $newestDateTrans);
                    }
                } else {
                    $newestDateCak = getNewestDate($dates);
                    filterDates($dates, $newestDateCak);
                }
            }

            foreach ($datamututrans as $key => &$dates) {
                if (isset($datamutuancak[$key])) {
                    $newestDateTrans = getNewestDate($dates);
                    $newestDateCak = getNewestDate($datamutuancak[$key]);

                    if ($newestDateTrans && (!$newestDateCak || $newestDateTrans->greaterThanOrEqualTo($newestDateCak))) {
                        filterDates($dates, $newestDateTrans);
                        if ($newestDateCak) {
                            unset($datamutuancak[$key][$newestDateCak->toDateString()]);
                        }
                    }
                } else {
                    $newestDateTrans = getNewestDate($dates);
                    filterDates($dates, $newestDateTrans);
                }
            }

            foreach ($datamutuancak as $key => $dates) {
                if (empty($dates)) {
                    unset($datamutuancak[$key]);
                }
            }

            foreach ($datamututrans as $key => $dates) {
                if (empty($dates)) {
                    unset($datamututrans[$key]);
                }
            }

            function ungroupByDate($array)
            {
                $result = [];

                foreach ($array as $blok => $dates) {
                    foreach ($dates as $date => $values) {
                        foreach ($values as $value) {
                            $result[$blok][] = $value;
                        }
                    }
                }

                return $result;
            }

            $datamutuancak = ungroupByDate($datamutuancak);
            $datamututrans = ungroupByDate($datamututrans);
        }

        $QueryEst = DB::connection('mysql2')
            ->table("estate")
            ->join('afdeling', 'afdeling.estate', 'estate.id')
            ->join('blok', 'blok.afdeling', '=', 'afdeling.id')
            ->select('blok.id', 'blok.nama', DB::raw('afdeling.nama as `afdeling`'), 'blok.lon', 'blok.lat')
            ->where('est', $est)
            // ->orderBy('lat', 'desc')
            ->get();


        $queryBlok = json_decode($QueryEst, true);
        $bloks_afd = array_reduce($queryBlok, function ($carry, $item) {
            $carry[$item['afdeling']][$item['nama']][] = $item;
            return $carry;
        }, []);

        $plotBlokAlls = [];
        foreach ($bloks_afd as $key => $coord) {
            foreach ($coord as $key2 => $value) {
                foreach ($value as $key3 => $value1) {
                    $plotBlokAlls[$key][] = [$value1['lat'], $value1['lon']];
                }
            }
        }
        // dd($plotBlokAlls);

        $dataAfdeling = $QueryEst->groupBy('afdeling', 'nama');
        $dataAfdeling = json_decode($dataAfdeling, true);
        $coordinates = [];

        foreach ($dataAfdeling as $key => $afdelingItems) {
            $coords = [];
            foreach ($afdelingItems as $item) {
                $coords[] = [
                    'lat' => $item['lat'],
                    'lon' => $item['lon'],
                ];
            }
            $coordinates[$key] = $coords;
        }




        // dd($datamutuancak['G19'], $datamututrans);

        $dataSkor = array();
        // dd($regional);
        if ($regional !== 2) {
            foreach ($datamututrans as $key => $value) {
                $sum_bt = 0;
                $sum_Restan = 0;
                $tph_sample = 0;
                $listBlokPerAfd = array();
                foreach ($value as $key2 => $value2) {
                    $listBlokPerAfd[] = $value2['estate'] . ' ' . $value2['afdeling'] . ' ' . $value2['blok'];
                    $tph_sample = count($listBlokPerAfd);
                    // $tph_sample = count($listBlokPerAfd);
                    $sum_Restan += $value2['rst'];
                    $sum_bt += $value2['bt'];
                }
                $skorTrans = skor_brd_tinggal(round($sum_bt / $tph_sample, 2)) + skor_buah_tinggal(round($sum_Restan / $tph_sample, 2));
                $dataSkor[$key][0]['skorTrans'] = $skorTrans;
                $dataSkor[$key][0]['tph_sample'] = $tph_sample;
                $dataSkor[$key][0]['sum_Restan'] = $sum_Restan;
                $dataSkor[$key][0]['afdeling'] = $value2['afdeling'];
                $dataSkor[$key][0]['sum_bt'] = $sum_bt;
                $dataSkor[$key][0]['latin'] = $value2['lat'] . ',' . $value2['lon'];
                $dataSkor[$key][0]['check_datatrans'] = 'ada';
            }
            foreach ($datamutuancak as $key => $value) {
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
                foreach ($value as $key2 => $value2) {
                    if (!in_array($value2['estate'] . ' ' . $value2['afdeling'] . ' ' . $value2['blok'], $listBlokPerAfd)) {
                        if ($value2['sph'] != 0) {
                            $listBlokPerAfd[] = $value2['estate'] . ' ' . $value2['afdeling'] . ' ' . $value2['blok'];
                        }
                    }

                    $jml_blok = count($listBlokPerAfd);
                    $totalPokok += $value2['sample'];
                    $totalPanen +=  $value2['jjg'];
                    $totalP_panen += $value2['brtp'];
                    $totalK_panen += $value2['brtk'];
                    $totalPTgl_panen += $value2['brtgl'];

                    $totalbhts_panen += $value2['bhts'];
                    $totalbhtm1_panen += $value2['bhtm1'];
                    $totalbhtm2_panen += $value2['bhtm2'];
                    $totalbhtm3_oanen += $value2['bhtm3'];
                    $check_input = $value2['jenis_input'];
                    $nilai_input = $value2['skor_akhir'];
                    $totalpelepah_s += $value2['ps'];
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



                // $ttlSkorMA = $skor_bh + $skor_brd + $skor_ps;
                $ttlSkorMA =  skor_buah_Ma($sumPerBH) + skor_brd_ma($brdPerjjg) + skor_palepah_ma($perPl);


                $dataSkor[$key][0]['Ancak'] = '=======================================================';
                $dataSkor[$key][0]['skorAncak'] = $ttlSkorMA;
                $dataSkor[$key][0]['afdeling'] = $value2['afdeling'];
                $dataSkor[$key][0]['tot_brd'] = $brdPerjjg;
                $dataSkor[$key][0]['total_brd'] = $skor_bTinggal;
                $dataSkor[$key][0]['sumBH'] = $sumBH;
                $dataSkor[$key][0]['totalP_panen'] = $totalP_panen;
                $dataSkor[$key][0]['totalK_panen'] = $totalK_panen;
                $dataSkor[$key][0]['totalPTgl_panen'] = $totalPTgl_panen;
                $dataSkor[$key][0]['totalPanen'] = $totalPanen;
                $dataSkor[$key][0]['latin2'] = $value2['lat_awal'] . ',' . $value2['lon_awal'];
                if (!empty($nonZeroValues)) {
                    $dataSkor[$key][0]['check_datacak'] = 'ada';
                } else {
                    $dataSkor[$key][0]['check_datacak'] = 'kosong';
                }
            }

            // dd($dataSkor['R009']);
            // dd($dataSkor['D13']);

        } else {
            // $new_transdata = getLatestEntries($datamututrans);
            // $new_ancakdata = getLatestEntries($datamutuancak);
            $new_transdata = $datamututrans;
            $new_ancakdata = $datamutuancak;

            // dd($new_ancakdata['G19'], $new_transdata['G19']);
            $ancakRegss2 = array();
            $sum = 0; // Initialize sum variable
            $count = 0; // Initialize count variable
            // dd($new_ancakdata['F19'], $new_transdata);
            foreach ($new_ancakdata as $key => $value) {
                $listBlok = array();
                $firstEntry = $value[0];
                foreach ($value as $key1 => $value2) {
                    // dd($value2);
                    if (!in_array($value2['estate'] . ' ' . $value2['afdeling'] . ' ' . $value2['blok'], $listBlok)) {
                        if ($value2['sph'] != 0) {
                            $listBlok[] = $value2['estate'] . ' ' . $value2['afdeling'] . ' ' . $value2['blok'];
                        }
                    }
                    $jml_blok = count($listBlok);

                    if ($firstEntry['luas_blok'] != 0) {
                        $first = $firstEntry['luas_blok'];
                    } else {
                        $first = '-';
                    }
                }
                if ($first != '-') {
                    $sum += $first;
                    $count++;
                }
                // $transNewdata[$key]['latin'] = $value['lat_awal'] . ',' . $value['lon_awal'];
                $ancakRegss2[$key]['luas_blok'] = $first;
                $ancakRegss2[$key]['estate'] = $value2['estate'];
                $ancakRegss2[$key]['afdeling'] = $value2['afdeling'];
                $ancakRegss2[$key]['lat_awal'] = $value2['lat_awal'];
                $ancakRegss2[$key]['lon_awal'] = $value2['lon_awal'];
                if ($regional == '2') {
                    $status_panen = explode(",", $value2['status_panen']);
                    $ancakRegss2[$key]['status_panen'] = $status_panen[0];
                } else {
                    $ancakRegss2[$key]['status_panen'] = $value2['status_panen'];
                }
            }
            // dd($ancakRegss2);
            // dd($regional == '2');
            $transNewdata = array();
            foreach ($new_transdata as $key => $value) {
                $sum_bt = 0;
                $sum_Restan = 0;
                $tph_sample = 0;
                $listBlokPerAfd = array();
                foreach ($value as $key1 => $value1) {
                    $listBlokPerAfd[] = $value1['estate'] . ' ' . $value1['afdeling'] . ' ' . $value1['blok'];
                    $sum_Restan += $value1['rst'];
                    $tph_sample = count($listBlokPerAfd);
                    $sum_bt += $value1['bt'];
                }
                $panenKey = 0;
                $LuasKey = 0;
                if (isset($ancakRegss2[$key]['status_panen'])) {
                    $transNewdata[$key]['status_panen'] = $ancakRegss2[$key]['status_panen'];
                    $panenKey = $ancakRegss2[$key]['status_panen'];
                }
                if (isset($ancakRegss2[$key]['luas_blok'])) {
                    $transNewdata[$key]['luas_blok'] = $ancakRegss2[$key]['luas_blok'];
                    $LuasKey = $ancakRegss2[$key]['luas_blok'];
                }


                if ($panenKey !== 0 && $panenKey <= 3) {
                    if (count($value1) == 1 && $value1[0]['blok'] == '0') {
                        $tph_sample = $value1[0]['tph_baris'];
                        $sum_bt = $value1[0]['bt'];
                    } else {
                        $transNewdata[$key]['tph_sample'] = round(floatval($LuasKey) * 1.3, 3);
                    }
                } else {
                    $transNewdata[$key]['tph_sample'] = $tph_sample;
                }



                $transNewdata[$key]['estate'] = $value1['estate'];
                $transNewdata[$key]['afdeling'] = $value1['afdeling'];
                $transNewdata[$key]['estate'] = $value1['estate'];
                $transNewdata[$key]['date'] = $value1['date'];
                $transNewdata[$key]['bt'] = $sum_bt;
                $transNewdata[$key]['rst'] = $sum_Restan;
                $transNewdata[$key]['latin'] = $value1['lat'] . ',' . $value1['lon'];
            }

            // dd($transNewdata);
            // dd($ancakRegss2);
            $tph_tod = 0;
            foreach ($ancakRegss2 as $key => $value) {
                if (!isset($transNewdata[$key])) {
                    $transNewdata[$key] = $value;
                    // dd($value);
                    if ($value['status_panen'] <= 3) {
                        $transNewdata[$key]['tph_sample'] = round(floatval($value['luas_blok']) * 1.3, 3);
                    } else {
                        $transNewdata[$key]['tph_sample'] = 0;
                    }
                    $transNewdata[$key]['rst'] = 0;
                    $transNewdata[$key]['bt'] = 0;
                    $transNewdata[$key]['estate'] = $value['estate'];
                    $transNewdata[$key]['afdeling'] = $value['afdeling'];
                    $transNewdata[$key]['latin'] = $value['lat_awal'] . ',' . $value['lon_awal'];
                }
                // // If 'tph_sample' key exists, add its value to $tph_tod
                if (isset($value['tph_sample'])) {
                    $tph_tod += $value['tph_sample'];
                }
            }

            // dd($transNewdata);
            $sum_bt = 0;
            $sum_Restan = 0;
            $tph_sample = 0;
            foreach ($transNewdata as $key => $value) {
                // dd($value);
                $tph_sample = $value['tph_sample'];
                $sum_Restan = $value['rst'];
                $sum_bt = $value['bt'];
                if ($tph_sample > 0) {
                    $skorTrans = skor_brd_tinggal(round($sum_bt / $tph_sample, 2)) + skor_buah_tinggal(round($sum_Restan / $tph_sample, 2));
                } else {
                    $skorTrans = 0;
                }

                $dataSkor[$key][0]['skorTrans'] = $skorTrans;
                $dataSkor[$key][0]['afdeling'] = $value['afdeling'];
                $dataSkor[$key][0]['tph_sample'] = $tph_sample;
                $dataSkor[$key][0]['sum_Restan'] = $sum_Restan;
                $dataSkor[$key][0]['sum_bt'] = $sum_bt;
                $dataSkor[$key][0]['latin'] = $value['latin'];
            }


            // dd($dataSkor);
            foreach ($new_ancakdata as $key => $value) {
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
                foreach ($value as $key2 => $value2) {
                    if (!in_array($value2['estate'] . ' ' . $value2['afdeling'] . ' ' . $value2['blok'], $listBlokPerAfd)) {
                        if ($value2['sph'] != 0) {
                            $listBlokPerAfd[] = $value2['estate'] . ' ' . $value2['afdeling'] . ' ' . $value2['blok'];
                        }
                    }

                    $jml_blok = count($listBlokPerAfd);
                    $totalPokok += $value2['sample'];
                    $totalPanen +=  $value2['jjg'];
                    $totalP_panen += $value2['brtp'];
                    $totalK_panen += $value2['brtk'];
                    $totalPTgl_panen += $value2['brtgl'];

                    $totalbhts_panen += $value2['bhts'];
                    $totalbhtm1_panen += $value2['bhtm1'];
                    $totalbhtm2_panen += $value2['bhtm2'];
                    $totalbhtm3_oanen += $value2['bhtm3'];
                    $check_input = $value2['jenis_input'];
                    $nilai_input = $value2['skor_akhir'];
                    $totalpelepah_s += $value2['ps'];
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
                    $dataSkor[$key][0]['check_datacak'] = 'ada';
                } else {
                    $dataSkor[$key][0]['check_datacak'] = 'kosong';
                }

                // $ttlSkorMA = $skor_bh + $skor_brd + $skor_ps;
                $ttlSkorMA =  skor_buah_Ma($sumPerBH) + skor_brd_ma($brdPerjjg) + skor_palepah_ma($perPl);


                $dataSkor[$key][0]['Ancak'] = '=======================================================';
                $dataSkor[$key][0]['skorAncak'] = $ttlSkorMA;
                $dataSkor[$key][0]['afdeling'] = $value2['afdeling'];
                $dataSkor[$key][0]['tot_brd'] = $brdPerjjg;
                $dataSkor[$key][0]['total_brd'] = $skor_bTinggal;
                $dataSkor[$key][0]['sumBH'] = $sumBH;
                $dataSkor[$key][0]['totalP_panen'] = $totalP_panen;
                $dataSkor[$key][0]['totalK_panen'] = $totalK_panen;
                $dataSkor[$key][0]['totalPTgl_panen'] = $totalPTgl_panen;
                $dataSkor[$key][0]['totalPanen'] = $totalPanen;
                $dataSkor[$key][0]['latin2'] = $value2['lat_awal'] . ',' . $value2['lon_awal'];
            }
        }

        // dd($dataSkor);
        $dataSkorResult = array();
        foreach ($dataSkor as $key => $value) {
            foreach ($value as $key1 => $value1) {
                // dd($value1);


                $skorTrans = check_array('skorTrans', $value1);
                $skorAncak = check_array('skorAncak', $value1);
                if ($regional == 3) {

                    if ($skorTrans != 0 && $skorAncak != 0) {
                        $skorAkhir = (int) round((($skorTrans + $skorAncak) * 100) / 65 - 1);
                    } elseif ($skorTrans != 0) {
                        $skorAkhir = 0;
                    } elseif ($skorAncak != 0) {
                        $skorAkhir = 0;
                    } else {
                        $skorAkhir = 0;
                    }
                } else if ($regional == 2) {
                    if ($skorTrans != 0 && $skorAncak != 0) {
                        $total = (int) round((($skorTrans + $skorAncak) * 100) / 65);
                        $skorAkhir = ($total != 100) ? $total : $total - 1;
                    } elseif ($skorTrans != 0) {
                        $total = (int) round(($skorTrans  * 100) / 65);
                        $skorAkhir = ($total != 100) ? $total : $total - 1;
                    } elseif ($skorAncak != 0) {
                        $total = (int) round(($skorAncak  * 100) / 65);
                        $skorAkhir = ($total != 100) ? $total : $total - 1;
                    } else {
                        $skorAkhir = 0;
                    }
                } else {
                    if ($skorTrans != 0 && $skorAncak != 0) {
                        $skorAkhir = (int) round((($skorTrans + $skorAncak) * 100) / 65 - 1);
                    } elseif ($skorTrans != 0) {
                        $skorAkhir = (int)round(($skorTrans * 100) / 65 - 1);
                    } elseif ($skorAncak != 0) {
                        $skorAkhir = (int) round(($skorAncak * 100) / 65 - 1);
                    } else {
                        $skorAkhir = 0;
                    }
                }



                if ($skorTrans == 0 && $skorAncak == 0) {
                    $check = 'empty';
                } else {
                    $check = 'data';
                }

                if ($check == 'data') {
                    $skor_kategori_akhir_est = skor_kategori_akhir($skorAkhir);
                } else {
                    $skor_kategori_akhir_est = 'xxx';
                }



                $dataSkorResult[$key]['estate'] = $est;
                $dataSkorResult[$key]['afdeling'] = $value1['afdeling'];
                $dataSkorResult[$key]['skorTrans'] = $skorTrans;
                $dataSkorResult[$key]['skorAncak'] = $skorAncak;
                $dataSkorResult[$key]['blok'] = $key;
                $dataSkorResult[$key]['text'] = $skor_kategori_akhir_est[1];
                $dataSkorResult[$key]['skorAkhir'] = $skorAkhir;
                $dataSkorResult[$key]['check_data'] = $check;
                $dataSkorResult[$key]['latin'] = $value1['latin'] ?? $value1['latin2'];
            }
        }
        // dd($dataSkorResult);



        $estateQuery = DB::connection('mysql2')->table('estate')
            ->select('*')
            ->join('afdeling', 'afdeling.estate', '=', 'estate.id')
            ->where('estate.est', $est)
            ->get();
        $estateQuery = json_decode($estateQuery, true);

        $listIdAfd = array();
        foreach ($estateQuery as $key => $value) {
            $listIdAfd[] = $value['id'];
        }


        $blokEstate = DB::connection('mysql2')->table('blok')
            ->select(DB::raw('DISTINCT nama, MIN(id) as id, afdeling'))
            ->whereIn('afdeling', $listIdAfd)
            ->groupBy('nama', 'afdeling')
            ->get();
        $blokEstate = json_decode($blokEstate, true);

        $blokEstateFix = array();
        foreach ($blokEstate as $key => $value) {
            $blokEstateFix[$value['afdeling']][] = $value['nama'];
        }

        // dd($blokEstateFix);
        $qrAfd = DB::connection('mysql2')->table('afdeling')
            ->select('*')
            ->get();
        $qrAfd = json_decode($qrAfd, true);

        $blokEstNewFix = array();
        foreach ($blokEstateFix as $key => $value) {
            foreach ($qrAfd as $key1 => $value1) {
                if ($value1['id'] == $key) {
                    $afdelingNama = $value1['nama'];
                }
            }
            $blokEstNewFix[$afdelingNama] = $value;
        }

        $queryBlok = DB::connection('mysql2')->table('blok')
            ->select('*')
            ->whereIn('afdeling', $listIdAfd)
            ->get();
        $queryBlok = json_decode($queryBlok, true);

        $blokLatLnEw = array();
        $inc = 0;
        foreach ($blokEstNewFix as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $latln = '';
                $latln2 = '';
                foreach ($queryBlok as $key3 => $value4) {
                    if ($value4['nama'] == $value1) {
                        $latln .= $value4['lat'] . ',' . $value4['lon'] . '$';
                        $latln2 .= '[' . $value4['lon'] . ',' . $value4['lat'] . '],';
                    }
                }

                $blokLatLnEw[$value1]['afd'] = $key;
                $blokLatLnEw[$value1]['blok'] = $value1;
                $blokLatLnEw[$value1]['latln'] = rtrim($latln, '$');
                $blokLatLnEw[$value1]['latinnew'] = rtrim($latln2, ',');
                $inc++;
            }
        }
        // dd($blokLatLnEw);
        $blokLatLn = [];
        // dd($dataSkorResult);

        foreach ($dataSkorResult as $markerKey => $marker) {
            // dd($marker);
            $found = false; // Flag to check if the marker is found in any polygon
            $blok_asli = $marker['blok'];

            foreach ($blokLatLnEw as $key => $value) {
                if (isPointInPolygon($marker['latin'], $value['latln'])) {
                    $found = true;
                    $blokLatLn[$value['blok']][] = [
                        'blok' => $value['blok'],
                        'blok_asli' => $blok_asli,
                        'estate' => $marker['estate'],
                        'latln' => $value['latinnew'],
                        'nilai' => $marker['skorAkhir'],
                        'afdeling' => $value['afd'],
                        'kategori' => $marker['text'],
                    ];
                    // No break here, so we continue checking for more matches
                }
            }

            if (!$found) {
                $blokLatLn[$value['blok']][] = [
                    'blok' => $marker['blok'],
                    'blok_asli' => $marker['blok'],
                    'estate' => $marker['estate'],
                    'latln' => 'no_coordinates',
                    'nilai' => $marker['skorAkhir'],
                    'afdeling' => $marker['afdeling'],
                    'kategori' => $marker['text'],
                ];
            }
        }

        $double_blok = [];

        foreach ($blokLatLn as $key => $value) {
            $val = count($value);
            if ($val > 1) {
                unset($blokLatLn[$key]);
                $double_blok[$key] = $value;
            }
        }

        // dd($blokLatLn, $double_blok);

        $not_match = [];

        foreach ($double_blok as $key => $values) {
            $maxSimilarity = 0;
            $bestMatchIndex = null;

            // First pass: Find the best match index
            foreach ($values as $index => $data) {
                $similarity = 0;
                similar_text($key, $data['blok_asli'], $similarity);

                if ($similarity > $maxSimilarity) {
                    $maxSimilarity = $similarity;
                    $bestMatchIndex = $index;
                }
            }

            // Second pass: Unset non-matching elements
            foreach ($values as $index => $data) {
                if ($index !== $bestMatchIndex) {
                    $not_match[$data['blok_asli']] = $data;
                    unset($double_blok[$key][$index]);
                }
            }

            // If there are no matches found, remove the entire key
            if ($bestMatchIndex === null) {
                $not_match[$key] = $values;
                unset($double_blok[$key]);
            }
        }
        // dd($blokLatLnEw);

        $combine_latin_double = array_merge($blokLatLn, $double_blok);

        $collectBlok = [];
        foreach ($combine_latin_double as $key => $value) {
            foreach ($value as $key1 => $value1) {
                if (similar_text($value1['blok_asli'], $value1['blok']) <= 2) {
                    unset($combine_latin_double[$key]);
                    unset($value1['latln']);
                    $collectBlok[$value1['blok']] = $value;
                }
            }
        }
        // dd($combine_latin_double);

        foreach ($collectBlok as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $found = false;

                // First, check for an exact match
                foreach ($blokLatLnEw as $value2) {
                    if ($value1['blok_asli'] === $value2['blok']) {
                        $collectBlok[$key][$key1]['type'] = 'keysama';
                        $collectBlok[$key][$key1]['latln'] = $value2['latinnew'];
                        $collectBlok[$key][$key1]['blok'] = $value2['blok'];
                        $collectBlok[$key][$key1]['similar_blok'] = $value2['blok'];
                        $found = true;
                        break; // Exit the inner loop once a match is found
                    }
                }
                if (!$found) {
                    $verified_blok = fetch_verified_match($value1['estate'], $value1['afdeling'], $value1['blok_asli']);

                    if ($verified_blok !== null) {
                        foreach ($blokLatLnEw as $value4) {
                            if ($verified_blok === $value4['blok']) {
                                $collectBlok[$key][$key1]['type'] = 'databasekeymatch';
                                $collectBlok[$key][$key1]['latln'] = $value4['latinnew'];
                                $collectBlok[$key][$key1]['blok'] = $value4['blok'];
                                $collectBlok[$key][$key1]['similar_blok'] = $value4['blok'];
                                $found = true;
                                break; // Exit the inner loop once a match is found
                            }
                        }
                    }
                }
                // If no exact match is found, check for the most similar block using Levenshtein distance
                if (!$found) {
                    $lowestDistance = PHP_INT_MAX;
                    $mostSimilarBlok = null;
                    foreach ($blokLatLnEw as $value3) {
                        $distance = levenshtein($value1['blok_asli'], $value3['blok']);
                        if ($distance < $lowestDistance) {
                            $lowestDistance = $distance;
                            $mostSimilarBlok = $value3;
                        }
                    }

                    // If a similar block is found, update the array with its latln
                    if ($mostSimilarBlok && $lowestDistance < PHP_INT_MAX) {
                        $collectBlok[$key][$key1]['type'] = 'similar';
                        $collectBlok[$key][$key1]['latln'] = $mostSimilarBlok['latinnew'];
                        $collectBlok[$key][$key1]['blok'] = $mostSimilarBlok['blok'];
                        $collectBlok[$key][$key1]['similar_blok'] = $mostSimilarBlok['blok'];
                    } else {
                        $collectBlok[$key][$key1]['latln'] = 'kosong';
                        $collectBlok[$key][$key1]['similar_blok'] = 'kosong';
                    }
                }
            }
        }
        // dd($collectBlok, $blokLatLnEw);

        $new_blok = [];
        foreach ($collectBlok as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $new_blok[$value1['similar_blok']][$key1] = $value1;
            }
        }
        // dd($new_blok);
        $table_newblok['collect'] = $collectBlok;
        // dd($table_newblok);
        $combinedArray = array_merge($combine_latin_double, $new_blok);
        // dd($not_match);
        foreach ($not_match as $key => $value) {
            $found = false;

            // First, check for an exact match
            foreach ($blokLatLnEw as $value2) {
                if ($value['blok_asli'] === $value2['blok']) {
                    $not_match[$key]['type'] = 'keysama';
                    $not_match[$key]['latln'] = $value2['latinnew'];
                    $not_match[$key]['blok'] = $value2['blok'];
                    $not_match[$key]['similar_blok'] = $value2['blok'];
                    $found = true;
                    break; // Exit the inner loop once a match is found
                }
            }

            // If no exact match is found, check the database for a verified match
            if (!$found) {
                $verified_blok = fetch_verified_match($value['estate'], $value['afdeling'], $value['blok_asli']);

                if ($verified_blok !== null) {
                    foreach ($blokLatLnEw as $value3) {
                        if ($verified_blok === $value3['blok']) {
                            $not_match[$key]['type'] = 'databasekeymatch';
                            $not_match[$key]['latln'] = $value3['latinnew'];
                            $not_match[$key]['blok'] = $value3['blok'];
                            $not_match[$key]['similar_blok'] = $value3['blok'];
                            $found = true;
                            break; // Exit the inner loop once a match is found
                        }
                    }
                }
            }

            // If no verified match is found, check for the most similar block using similar_text
            if (!$found) {
                $lowestDistance = PHP_INT_MAX;
                $mostSimilarBlok = null;
                foreach ($blokLatLnEw as $value3) {
                    $distance = levenshtein($value['blok_asli'], $value3['blok']);
                    if ($distance < $lowestDistance) {
                        $lowestDistance = $distance;
                        $mostSimilarBlok = $value3;
                    }
                }

                // If a similar block is found, update the array with its latln
                if ($mostSimilarBlok && $lowestDistance < PHP_INT_MAX) {
                    $not_match[$key]['type'] = 'similar';
                    $not_match[$key]['latln'] = $mostSimilarBlok['latinnew'];
                    $not_match[$key]['blok'] = $mostSimilarBlok['blok'];
                    $not_match[$key]['similar_blok'] = $mostSimilarBlok['blok'];
                } else {
                    $not_match[$key]['latln'] = 'kosong';
                    $not_match[$key]['similar_blok'] = 'kosong';
                }
            }
        }

        $table_newblok['not_match'] = $not_match;
        // dd($table_newblok);
        // dd($not_match, $collectBlok);
        $new_blok_not_match = [];
        foreach ($not_match as $key => $value) {
            $new_blok_not_match[$value['similar_blok']][] = $value;
        }


        $last_latls = array_merge($combinedArray, $new_blok_not_match);
        $not_include_key = [];
        foreach ($blokLatLnEw as $key => $value) {
            if (!isset($last_latls[$key])) {
                unset($value['latln']);
                // dd($value);
                $not_include_key[$key][] = [
                    "blok" => $key,
                    "blok_asli" => $key,
                    "estate" => '-',
                    'latln' => $value['latinnew'],
                    'nilai' => 0,
                    'afdeling' => '-',
                    'kategori' => '-',
                    'similar_blok' => '-',
                ];
            }
        }
        $finalLatln = array_merge($last_latls, $not_include_key);

        foreach ($finalLatln as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $finalLatln[$key] = $value1;
            }
        }

        $fix_no_coordintes = [];
        foreach ($finalLatln as $key => $value) {
            if ($value['latln'] === 'no_coordinates') {
                unset($finalLatln[$key]);
                $fix_no_coordintes[$key] = $value;
            }
        }
        // dd($finalLatln, $fix_no_coordintes);
        foreach ($fix_no_coordintes as $key => $value) {
            $found = false;

            // First, check for an exact match
            foreach ($blokLatLnEw as $value2) {
                if ($value['blok_asli'] === $value2['blok']) {
                    $fix_no_coordintes[$key]['type'] = 'keysama';
                    $fix_no_coordintes[$key]['latln'] = $value2['latinnew'];
                    $fix_no_coordintes[$key]['blok'] = $value2['blok'];
                    $fix_no_coordintes[$key]['similar_blok'] = $value2['blok'];
                    $found = true;
                    break; // Exit the inner loop once a match is found
                }
            }
            if (!$found) {
                $verified_blok = fetch_verified_match($value['estate'], $value['afdeling'], $value['blok_asli']);

                if ($verified_blok !== null) {
                    foreach ($blokLatLnEw as $value4) {
                        if ($verified_blok === $value4['blok']) {
                            $fix_no_coordintes[$key]['type'] = 'databasekeymatch';
                            $fix_no_coordintes[$key]['latln'] = $value4['latinnew'];
                            $fix_no_coordintes[$key]['blok'] = $value4['blok'];
                            $fix_no_coordintes[$key]['similar_blok'] = $value4['blok'];
                            $found = true;
                            break; // Exit the inner loop once a match is found
                        }
                    }
                }
            }
            // If no exact match is found, check for the most similar block using Levenshtein distance
            if (!$found) {
                $lowestDistance = PHP_INT_MAX;
                $mostSimilarBlok = null;
                foreach ($blokLatLnEw as $value3) {
                    $distance = levenshtein($value['blok_asli'], $value3['blok']);
                    if ($distance < $lowestDistance) {
                        $lowestDistance = $distance;
                        $mostSimilarBlok = $value3;
                    }
                }

                // If a similar block is found, update the array with its latln
                if ($mostSimilarBlok && $lowestDistance < PHP_INT_MAX) {
                    $fix_no_coordintes[$key]['type'] = 'similar';
                    $fix_no_coordintes[$key]['latln'] = $mostSimilarBlok['latinnew'];
                    $fix_no_coordintes[$key]['blok'] = $mostSimilarBlok['blok'];
                    $fix_no_coordintes[$key]['similar_blok'] = $mostSimilarBlok['blok'];
                } else {
                    $fix_no_coordintes[$key]['latln'] = 'kosong';
                    $fix_no_coordintes[$key]['similar_blok'] = 'kosong';
                }
            }
        }
        $table_newblok['fix_no_coordintes'] = $fix_no_coordintes;
        // dd($fix_no_coordintes);
        $new_blok2 = [];
        foreach ($fix_no_coordintes as $key => $value) {
            $new_blok2[$value['similar_blok']] = $value;
        }

        foreach ($finalLatln as $key => $value) {
            if (isset($new_blok2[$key])) {
                unset($finalLatln[$key]);
            }
        }

        $finalLatln = array_merge($finalLatln, $new_blok2);
        foreach ($blokLatLnEw as $key => $value) {
            if (isset($fix_no_coordintes[$key])) {
                // unset($fix_no_coordintes[$value]);
                // dd($value);
                $fix_no_coordintes[$key] = [
                    "blok" => $key,
                    "blok_asli" => $key,
                    "estate" => '-',
                    'latln' => $value['latinnew'],
                    'nilai' => 0,
                    'afdeling' => '-',
                    'kategori' => '-',
                    'similar_blok' => '-',
                ];
            }
        }

        // dd($fix_no_coordintes);
        // dd($finalLatln, $fix_no_coordintes, $new_blok2);
        $finalLatln = array_merge($finalLatln, $new_blok2, $fix_no_coordintes);
        // dd($finalLatln);
        // dd($finalLatln, $fix_no_coordintes);
        // dd($finalLatln, $not_include_key);


        $dataLegend = array();
        $excellent = array();
        $good = array();
        $satis = array();
        $fair = array();
        $poor = array();
        $empty = array();
        $dataLegend = array();
        foreach ($finalLatln as $key => $value) {
            $skor = $value['nilai'];
            $data = $value['kategori'];
            if ($data == 'EXCELLENT') {
                $excellent[] = $value['nilai'];
            } else if ($data == 'GOOD') {
                $good[] = $value['nilai'];
            } else if ($data == 'SATISFACTORY') {
                $satis[] = $value['nilai'];
            } else if ($data == 'FAIR') {
                $fair[] = $value['nilai'];
            } else if ($data == 'POOR') {
                $poor[] = $value['nilai'];
            } else if ($data == 'x') {
                $empty[] = $value['nilai'];
            }
        }

        $tot_exc = count($excellent);
        $tot_good = count($good);
        $tot_satis = count($satis);
        $tot_fair = count($fair);
        $tot_poor = count($poor);
        $tot_empty = count($empty);

        $totalSkor = $tot_exc + $tot_good + $tot_satis + $tot_fair + $tot_poor + $tot_empty;

        $dataLegend['excellent'] = $tot_exc;
        $dataLegend['good'] = $tot_good;
        $dataLegend['satis'] = $tot_satis;
        $dataLegend['fair'] = $tot_fair;
        $dataLegend['poor'] = $tot_poor;
        $dataLegend['empty'] = $tot_empty;
        $dataLegend['total'] = $totalSkor;
        $dataLegend['perExc'] = count_percent($tot_exc, $totalSkor);
        $dataLegend['perGood'] = count_percent($tot_good, $totalSkor);
        $dataLegend['perSatis'] = count_percent($tot_satis, $totalSkor);
        $dataLegend['perFair'] = count_percent($tot_fair, $totalSkor);
        $dataLegend['perPoor'] = count_percent($tot_poor, $totalSkor);
        $dataLegend['perEmpty'] = count_percent($tot_empty, $totalSkor);

        $highestValue = null;
        $estatesWithHighestNilai = [];
        $bloksWithHighestNilai = [];

        foreach ($finalLatln as $value) {
            $nilai = $value['nilai'];
            $estate = $value['estate'];
            $blok = $value['blok'];

            if ($highestValue === null || $nilai > $highestValue) {
                $highestValue = $nilai;
                $estatesWithHighestNilai = [$estate];
                $bloksWithHighestNilai = [$blok];
            } elseif ($nilai === $highestValue) {
                $estatesWithHighestNilai[] = $estate;
                $bloksWithHighestNilai[] = $blok;
            }
        }

        $resultsHIgh = [
            'estate' => $estatesWithHighestNilai,
            'blok' => $bloksWithHighestNilai,
            'nilai' => $highestValue,
        ];

        $lowestValue = null;
        $estatesWithLowestNilai = [];
        $bloksWithLowestNilai = [];

        foreach ($finalLatln as $value) {
            $nilai = $value['nilai'];
            $estate = $value['estate'];
            $blok = $value['blok'];

            if ($lowestValue === null && $nilai !== 0) {
                $lowestValue = $nilai;
                $estatesWithLowestNilai = [$estate];
                $bloksWithLowestNilai = [$blok];
            } elseif ($nilai < $lowestValue && $nilai !== 0) {
                $lowestValue = $nilai;
                $estatesWithLowestNilai = [$estate];
                $bloksWithLowestNilai = [$blok];
            } elseif ($nilai === $lowestValue && $nilai !== 0) {
                $estatesWithLowestNilai[] = $estate;
                $bloksWithLowestNilai[] = $blok;
            }
        }

        $resultsLow = [
            'estate' => $estatesWithLowestNilai,
            'blok' => $bloksWithLowestNilai,
            'nilai' => $lowestValue,
        ];
        // dd($data_ancak_bydate);
        return [
            'blok' => $finalLatln,
            'legend' => $dataLegend,
            'lowest' => $resultsLow,
            'highest' => $resultsHIgh,
            'afdeling' => $plotBlokAlls,
            'table_newblok' => $table_newblok,
            'master_blok' => $blokLatLnEw,
            'data_ancak_bydate' => $data_ancak_bydate,
            'data_trans_bydate' => $data_trans_bydate,
        ];
    }
}


// function grading mill
if (!function_exists('getdatamill')) {
    function getdatamill($bulan, $reg, $type)
    {
        $get_bulan = $bulan;
        $get_regional = (int)$reg;
        $get_type = $type;
        // dd($get_type);
        // if ($get_type === 'perbulan') {
        //     $data = DB::connection('mysql2')->table('grading_mill')
        //         ->select('grading_mill.*', 'grading_mill.id as id_data')
        //         ->join('estate', 'estate.est', '=', 'grading_mill.estate')
        //         ->join('wil', 'wil.id', '=', 'estate.wil')
        //         ->where('estate.emp', '!=', 1)
        //         ->where('wil.regional', $get_regional)
        //         ->where('grading_mill.datetime', 'like', '%' . $get_bulan . '%')
        //         ->orderBy('grading_mill.datetime', 'asc')
        //         ->orderBy('estate.est', 'asc')
        //         ->orderBy('grading_mill.afdeling', 'asc')
        //         ->get();
        //     // $data = $data->groupBy(['estate']);
        //     $data = $data->groupBy(['estate']);
        //     $data = json_decode($data, true);

        //     // dd($data);
        //     // save_json($data['SCM']);
        //     // dd($data['SCM']);
        //     // dd($data, $get_regional, $get_bulan);

        //     $wil = DB::connection('mysql2')->table('estate')
        //         ->select('estate.*', 'wil.nama as namawil')
        //         ->join('wil', 'wil.id', '=', 'estate.wil')
        //         ->where('wil.regional', $get_regional)
        //         ->whereNotIn('estate.est', ['CWS1', 'CWS2', 'CWS3'])
        //         ->where('estate.emp', '!=', 1)
        //         ->orderBy('estate.wil', 'asc')
        //         ->where('estate.est', '!=', 'PLASMA')
        //         ->get();
        //     $wil = $wil->groupBy(['namawil']);
        //     $wil = json_decode($wil, true);

        //     $mil = DB::connection('mysql2')->table('list_mill')
        //         ->select('list_mill.*')
        //         ->where('reg', $get_regional)
        //         ->get();
        //     $mil = $mil->groupBy(['nama_mill']);
        //     $mil = json_decode($mil, true);
        //     // dd($mil);
        //     $data_wil = [];
        //     foreach ($data as $key => $value) {
        //         foreach ($value as $key => $value1) {
        //             foreach ($wil as $keywil => $wilval) {
        //                 foreach ($wilval as $keywil1 => $wilval1) {
        //                     if ($value1['estate'] === $wilval1['est']) {
        //                         $data_wil[$keywil][] = $value1;
        //                     }
        //                 }
        //             }
        //         }
        //     }
        //     $data_mill = [];
        //     foreach ($data as $key => $value) {
        //         foreach ($value as $key => $value1) {
        //             foreach ($mil as $keywil => $wilval) {
        //                 foreach ($wilval as $keywil1 => $wilval1) {
        //                     if ($value1['mill'] === $wilval1['mill']) {
        //                         $data_mill[$keywil][] = $value1;
        //                     }
        //                 }
        //             }
        //         }
        //     }
        //     // dd($data_mill);
        //     // dd($data, $mil, $data_mill);
        //     // dd($wil, $data_wil);
        //     $result = [];
        //     if (!empty($data)) {
        //         foreach ($data as $keys => $values) {
        //             $tonase = 0;
        //             $jumlah_janjang_grading = 0;
        //             $jumlah_janjang_spb = 0;
        //             $brondol_0 = 0;
        //             $brondol_less = 0;
        //             $overripe = 0;
        //             $empty_bunch = 0;
        //             $rotten_bunch = 0;
        //             $abn_partheno = 0;
        //             $abn_hard = 0;
        //             $abn_sakit = 0;
        //             $abn_kastrasi = 0;
        //             $longstalk = 0;
        //             $vcut = 0;
        //             $dirt = 0;
        //             $loose_fruit = 0;
        //             $kelas_a = 0;
        //             $kelas_b = 0;
        //             $kelas_c = 0;
        //             foreach ($values as $key => $value) {
        //                 $tonase += $value['tonase'];
        //                 $jumlah_janjang_grading += $value['jjg_grading'];
        //                 $jumlah_janjang_spb += $value['jjg_spb'];


        //                 $brondol_0 += is_numeric($value['unripe_tanpa_brondol']) ? $value['unripe_tanpa_brondol'] : 0;
        //                 $brondol_less += is_numeric($value['unripe_kurang_brondol']) ? $value['unripe_kurang_brondol'] : 0;

        //                 $overripe += $value['overripe'];
        //                 $empty_bunch += $value['empty'];
        //                 $rotten_bunch += $value['rotten'];

        //                 $abn_partheno += $value['abn_partheno'];
        //                 $abn_hard += $value['abn_hard'];
        //                 $abn_sakit += $value['abn_sakit'];
        //                 $abn_kastrasi += $value['abn_kastrasi'];
        //                 $longstalk += $value['tangkai_panjang'];
        //                 $vcut += $value['vcut'];
        //                 $dirt += $value['dirt'];
        //                 $loose_fruit += $value['loose_fruit'];
        //                 $kelas_a += $value['kelas_a'];
        //                 $kelas_b += $value['kelas_b'];
        //                 $kelas_c += $value['kelas_c'];
        //             }

        //             $array_perdate = [
        //                 'estate' => $keys,
        //                 'afdeling' => $key,
        //                 'jjg_grading' => $jumlah_janjang_grading,
        //                 'jjg_spb' => $jumlah_janjang_spb,
        //                 'brondol_0' => $brondol_0,
        //                 'brondol_less' => $brondol_less,
        //                 'overripe' => $overripe,
        //                 'empty' => $empty_bunch,
        //                 'rotten' => $rotten_bunch,
        //                 'tangkai_panjang' => $longstalk,
        //                 'vcuts' => $vcut,
        //                 'tonase' => $tonase,
        //                 'dirt' => $dirt,
        //                 'loose_fruit' => $loose_fruit,
        //                 'abn_partheno' => $abn_partheno,
        //                 'abn_hard' => $abn_hard,
        //                 'abn_sakit' => $abn_sakit,
        //                 'abn_kastrasi' => $abn_kastrasi,
        //                 'kelas_a' => $kelas_a,
        //                 'kelas_b' => $kelas_b,
        //                 'kelas_c' => $kelas_c,
        //             ];

        //             // dd($array_perdate);
        //             $date_arr = formula_grading($array_perdate);

        //             $result[$keys]['regional'] = [
        //                 'tonase' => $date_arr['tonase'],
        //                 'jumlah_janjang_grading' => $date_arr['jjg_grading'],
        //                 'jumlah_janjang_spb' => $date_arr['jjg_spb'],
        //                 'ripeness' => $date_arr['Ripeness'],
        //                 'percentage_ripeness' => $date_arr['percentase_ripenes'],
        //                 'unripe' => $date_arr['Unripe'],
        //                 'percentage_unripe' => $date_arr['persenstase_unripe'],
        //                 'overripe' => $date_arr['overripe'],
        //                 'percentage_overripe' => $date_arr['persentase_overripe'],
        //                 'empty_bunch' => $date_arr['empty'],
        //                 'percentage_empty_bunch' => $date_arr['persentase_empty_bunch'],
        //                 'rotten_bunch' => $date_arr['rotten'],
        //                 'percentage_rotten_bunch' => $date_arr['persentase_rotten_bunce'],
        //                 'abnormal' => $date_arr['Abnormal'],
        //                 'percentage_abnormal' => $date_arr['persentase_abnormal'],
        //                 'longstalk' => $date_arr['tangkai_panjang'],
        //                 'percentage_longstalk' => $date_arr['persentase_stalk'],
        //                 'vcut' => $date_arr['vcuts'],
        //                 'percentage_vcut' => $date_arr['persentase_vcut'],
        //                 'dirt_kg' => $date_arr['dirt'],
        //                 'percentage_dirt' => $date_arr['persentase'],
        //                 'loose_fruit_kg' => $date_arr['loose_fruit'],
        //                 'percentage_loose_fruit' => $date_arr['persentase_lose_fruit'],
        //                 'kelas_a' => $date_arr['kelas_a'],
        //                 'kelas_b' => $date_arr['kelas_b'],
        //                 'kelas_c' => $date_arr['kelas_c'],
        //                 'percentage_kelas_a' => $date_arr['persentase_kelas_a'],
        //                 'percentage_kelas_b' => $date_arr['persentase_kelas_b'],
        //                 'percentage_kelas_c' => $date_arr['persentase_kelas_c'],

        //             ];
        //         }
        //     }
        //     $result_wil = [];
        //     if (!empty($data_wil)) {
        //         foreach ($data_wil as $keys => $values) {
        //             $tonase = 0;
        //             $jumlah_janjang_grading = 0;
        //             $jumlah_janjang_spb = 0;
        //             $brondol_0 = 0;
        //             $brondol_less = 0;
        //             $overripe = 0;
        //             $empty_bunch = 0;
        //             $rotten_bunch = 0;
        //             $abn_partheno = 0;
        //             $abn_hard = 0;
        //             $abn_sakit = 0;
        //             $abn_kastrasi = 0;
        //             $longstalk = 0;
        //             $vcut = 0;
        //             $dirt = 0;
        //             $loose_fruit = 0;
        //             $kelas_a = 0;
        //             $kelas_b = 0;
        //             $kelas_c = 0;
        //             foreach ($values as $key => $value) {
        //                 $tonase += $value['tonase'];
        //                 $jumlah_janjang_grading += $value['jjg_grading'];
        //                 $jumlah_janjang_spb += $value['jjg_spb'];



        //                 $brondol_0 += is_numeric($value['unripe_tanpa_brondol']) ? $value['unripe_tanpa_brondol'] : 0;
        //                 $brondol_less += is_numeric($value['unripe_kurang_brondol']) ? $value['unripe_kurang_brondol'] : 0;

        //                 // $brondol_0 += $value['unripe_tanpa_brondol'];
        //                 // $brondol_less += $value['unripe_kurang_brondol'];

        //                 $overripe += $value['overripe'];
        //                 $empty_bunch += $value['empty'];
        //                 $rotten_bunch += $value['rotten'];

        //                 $abn_partheno += $value['abn_partheno'];
        //                 $abn_hard += $value['abn_hard'];
        //                 $abn_sakit += $value['abn_sakit'];
        //                 $abn_kastrasi += $value['abn_kastrasi'];
        //                 $longstalk += $value['tangkai_panjang'];
        //                 $vcut += $value['vcut'];
        //                 $dirt += $value['dirt'];
        //                 $loose_fruit += $value['loose_fruit'];
        //                 $kelas_a += $value['kelas_a'];
        //                 $kelas_b += $value['kelas_b'];
        //                 $kelas_c += $value['kelas_c'];
        //             }

        //             $array_perdate = [
        //                 'estate' => $keys,
        //                 'afdeling' => $key,
        //                 'jjg_grading' => $jumlah_janjang_grading,
        //                 'jjg_spb' => $jumlah_janjang_spb,
        //                 'brondol_0' => $brondol_0,
        //                 'brondol_less' => $brondol_less,
        //                 'overripe' => $overripe,
        //                 'empty' => $empty_bunch,
        //                 'rotten' => $rotten_bunch,
        //                 'tangkai_panjang' => $longstalk,
        //                 'vcuts' => $vcut,
        //                 'tonase' => $tonase,
        //                 'dirt' => $dirt,
        //                 'loose_fruit' => $loose_fruit,
        //                 'abn_partheno' => $abn_partheno,
        //                 'abn_hard' => $abn_hard,
        //                 'abn_sakit' => $abn_sakit,
        //                 'abn_kastrasi' => $abn_kastrasi,
        //                 'kelas_a' => $kelas_a,
        //                 'kelas_b' => $kelas_b,
        //                 'kelas_c' => $kelas_c,
        //             ];

        //             // dd($array_perdate);
        //             $date_arr = formula_grading($array_perdate);

        //             $result_wil[$keys]['wil'] = [
        //                 'tonase' => $date_arr['tonase'] ?? 0,
        //                 'jumlah_janjang_grading' => $date_arr['jjg_grading'] ?? 0,
        //                 'jumlah_janjang_spb' => $date_arr['jjg_spb'] ?? 0,
        //                 'ripeness' => $date_arr['Ripeness'] ?? 0,
        //                 'percentage_ripeness' => $date_arr['percentase_ripenes'] ?? 0,
        //                 'unripe' => $date_arr['Unripe'] ?? 0,
        //                 'percentage_unripe' => $date_arr['persenstase_unripe'] ?? 0,
        //                 'overripe' => $date_arr['overripe'] ?? 0,
        //                 'percentage_overripe' => $date_arr['persentase_overripe'] ?? 0,
        //                 'empty_bunch' => $date_arr['empty'] ?? 0,
        //                 'percentage_empty_bunch' => $date_arr['persentase_empty_bunch'] ?? 0,
        //                 'rotten_bunch' => $date_arr['rotten'] ?? 0,
        //                 'percentage_rotten_bunch' => $date_arr['persentase_rotten_bunce'] ?? 0,
        //                 'abnormal' => $date_arr['abnormal'] ?? 0,
        //                 'percentage_abnormal' => $date_arr['persentase_abnormal'] ?? 0,
        //                 'longstalk' => $date_arr['longstalk'] ?? 0,
        //                 'percentage_longstalk' => $date_arr['persentase_stalk'] ?? 0,
        //                 'vcut' => $date_arr['vcuts'] ?? 0,
        //                 'percentage_vcut' => $date_arr['persentase_vcut'] ?? 0,
        //                 'dirt_kg' => $date_arr['dirt'] ?? 0,
        //                 'percentage_dirt' => $date_arr['persentase'] ?? 0,
        //                 'loose_fruit_kg' => $date_arr['loose_fruit'] ?? 0,
        //                 'percentage_loose_fruit' => $date_arr['persentase_lose_fruit'] ?? 0,
        //                 'kelas_a' => $date_arr['kelas_a'] ?? 0,
        //                 'kelas_b' => $date_arr['kelas_b'] ?? 0,
        //                 'kelas_c' => $date_arr['kelas_c'] ?? 0,
        //                 'percentage_kelas_a' => $date_arr['persentase_kelas_a'] ?? 0,
        //                 'percentage_kelas_b' => $date_arr['persentase_kelas_b'] ?? 0,
        //                 'percentage_kelas_c' => $date_arr['persentase_kelas_c'] ?? 0,

        //             ];
        //         }
        //     }
        //     $result_mill = [];
        //     if (!empty($data_mill)) {
        //         foreach ($data_mill as $keys => $values) {
        //             $tonase = 0;
        //             $jumlah_janjang_grading = 0;
        //             $jumlah_janjang_spb = 0;
        //             $brondol_0 = 0;
        //             $brondol_less = 0;
        //             $overripe = 0;
        //             $empty_bunch = 0;
        //             $rotten_bunch = 0;
        //             $abn_partheno = 0;
        //             $abn_hard = 0;
        //             $abn_sakit = 0;
        //             $abn_kastrasi = 0;
        //             $longstalk = 0;
        //             $vcut = 0;
        //             $dirt = 0;
        //             $loose_fruit = 0;
        //             $kelas_a = 0;
        //             $kelas_b = 0;
        //             $kelas_c = 0;
        //             foreach ($values as $key => $value) {
        //                 $tonase += $value['tonase'];
        //                 $jumlah_janjang_grading += $value['jjg_grading'];
        //                 $jumlah_janjang_spb += $value['jjg_spb'];


        //                 // $brondol_0 += $value['unripe_tanpa_brondol'];
        //                 // $brondol_less += $value['unripe_kurang_brondol'];


        //                 $brondol_0 += is_numeric($value['unripe_tanpa_brondol']) ? $value['unripe_tanpa_brondol'] : 0;
        //                 $brondol_less += is_numeric($value['unripe_kurang_brondol']) ? $value['unripe_kurang_brondol'] : 0;


        //                 $overripe += $value['overripe'];
        //                 $empty_bunch += $value['empty'];
        //                 $rotten_bunch += $value['rotten'];

        //                 $abn_partheno += $value['abn_partheno'];
        //                 $abn_hard += $value['abn_hard'];
        //                 $abn_sakit += $value['abn_sakit'];
        //                 $abn_kastrasi += $value['abn_kastrasi'];
        //                 $longstalk += $value['tangkai_panjang'];
        //                 $vcut += $value['vcut'];
        //                 $dirt += $value['dirt'];
        //                 $loose_fruit += $value['loose_fruit'];
        //                 $kelas_a += $value['kelas_a'];
        //                 $kelas_b += $value['kelas_b'];
        //                 $kelas_c += $value['kelas_c'];
        //             }

        //             $array_perdate = [
        //                 'estate' => $keys,
        //                 'afdeling' => $key,
        //                 'jjg_grading' => $jumlah_janjang_grading,
        //                 'jjg_spb' => $jumlah_janjang_spb,
        //                 'brondol_0' => $brondol_0,
        //                 'brondol_less' => $brondol_less,
        //                 'overripe' => $overripe,
        //                 'empty' => $empty_bunch,
        //                 'rotten' => $rotten_bunch,
        //                 'tangkai_panjang' => $longstalk,
        //                 'vcuts' => $vcut,
        //                 'tonase' => $tonase,
        //                 'dirt' => $dirt,
        //                 'loose_fruit' => $loose_fruit,
        //                 'abn_partheno' => $abn_partheno,
        //                 'abn_hard' => $abn_hard,
        //                 'abn_sakit' => $abn_sakit,
        //                 'abn_kastrasi' => $abn_kastrasi,
        //                 'kelas_a' => $kelas_a,
        //                 'kelas_b' => $kelas_b,
        //                 'kelas_c' => $kelas_c,
        //             ];

        //             // dd($array_perdate);
        //             $date_arr = formula_grading($array_perdate);
        //             $result_mill[$keys]['mil'] = [
        //                 'tonase' => $date_arr['tonase'],
        //                 'jumlah_janjang_grading' => $date_arr['jjg_grading'],
        //                 'jumlah_janjang_spb' => $date_arr['jjg_spb'],
        //                 'ripeness' => $date_arr['Ripeness'],
        //                 'percentage_ripeness' => $date_arr['percentase_ripenes'],
        //                 'unripe' => $date_arr['Unripe'],
        //                 'percentage_unripe' => $date_arr['persenstase_unripe'],
        //                 'overripe' => $date_arr['overripe'],
        //                 'percentage_overripe' => $date_arr['persentase_overripe'],
        //                 'empty_bunch' => $date_arr['empty'],
        //                 'percentage_empty_bunch' => $date_arr['persentase_empty_bunch'],
        //                 'rotten_bunch' => $date_arr['rotten'],
        //                 'percentage_rotten_bunch' => $date_arr['persentase_rotten_bunce'],
        //                 'abnormal' => $date_arr['Abnormal'],
        //                 'percentage_abnormal' => $date_arr['persentase_abnormal'],
        //                 'longstalk' => $date_arr['tangkai_panjang'],
        //                 'percentage_longstalk' => $date_arr['persentase_stalk'],
        //                 'vcut' => $date_arr['vcuts'],
        //                 'percentage_vcut' => $date_arr['persentase_vcut'],
        //                 'dirt_kg' => $date_arr['dirt'],
        //                 'percentage_dirt' => $date_arr['persentase'],
        //                 'loose_fruit_kg' => $date_arr['loose_fruit'],
        //                 'percentage_loose_fruit' => $date_arr['persentase_lose_fruit'],
        //                 'kelas_a' => $date_arr['kelas_a'],
        //                 'kelas_b' => $date_arr['kelas_b'],
        //                 'kelas_c' => $date_arr['kelas_c'],
        //                 'percentage_kelas_a' => $date_arr['persentase_kelas_a'],
        //                 'percentage_kelas_b' => $date_arr['persentase_kelas_b'],
        //                 'percentage_kelas_c' => $date_arr['persentase_kelas_c'],

        //             ];
        //         }
        //     }
        //     // dd($result_mill);
        //     $arr = array();
        //     $arr['data_regional'] = $result;
        //     $arr['data_wil'] = $result_wil;
        //     $arr['data_mill'] = $result_mill;

        //     return $arr;
        //     // dd($result, $data);
        //     // echo json_encode($arr); //di decode ke dalam bentuk json dalam vaiavel arrview yang dapat menampung banyak isi array
        //     // exit();
        // } elseif ($get_type === 'perhari') {

        //     $data = DB::connection('mysql2')->table('grading_mill')
        //         ->join('estate', 'estate.est', '=', 'grading_mill.estate')
        //         ->join('wil', 'wil.id', '=', 'estate.wil')
        //         ->where('estate.emp', '!=', 1)
        //         ->where('wil.regional', $get_regional)
        //         ->where('grading_mill.datetime', 'like', '%' . $get_bulan . '%')
        //         ->orderBy('estate.est', 'asc')
        //         ->orderBy('grading_mill.afdeling', 'asc')
        //         ->get();
        //     $data = json_decode($data, true);

        //     $result = [];
        //     if (!empty($data)) {
        //         foreach ($data as $key => $value) {
        //             // Remove square brackets and split the string into an array
        //             $cleaned_string = str_replace(['[', ']'], '', $value['foto_temuan']);
        //             $foto = explode(',', $cleaned_string);

        //             // Trim spaces from each element in the array
        //             $foto = array_map('trim', $foto);

        //             $array_perdate = [
        //                 'estate' => $value['estate'],
        //                 'afdeling' => $value['afdeling'],
        //                 'jjg_grading' => $value['jjg_grading'],
        //                 'jjg_spb' => $value['jjg_spb'],
        //                 'brondol_0' => $value['unripe_tanpa_brondol'],
        //                 'brondol_less' => $value['unripe_kurang_brondol'],
        //                 'overripe' => $value['overripe'],
        //                 'empty' => $value['empty'],
        //                 'rotten' => $value['rotten'],
        //                 'tangkai_panjang' => $value['tangkai_panjang'],
        //                 'vcuts' => $value['vcut'],
        //                 'tonase' => $value['tonase'],
        //                 'dirt' => $value['dirt'],
        //                 'loose_fruit' => $value['loose_fruit'],
        //                 'abn_partheno' => $value['abn_partheno'],
        //                 'abn_hard' => $value['abn_hard'],
        //                 'abn_sakit' => $value['abn_sakit'],
        //                 'abn_kastrasi' => $value['abn_kastrasi'],
        //                 'kelas_a' => $value['kelas_a'],
        //                 'kelas_b' => $value['kelas_b'],
        //                 'kelas_c' => $value['kelas_c'],
        //             ];

        //             // dd($array_perdate);
        //             $date_arr = formula_grading($array_perdate);

        //             $no_pemanen = json_decode($value['no_pemanen'], true);
        //             $tanpaBrondol = [];
        //             $datakurang_brondol = [];
        //             // dd($no_pemanen);
        //             foreach ($no_pemanen as $keys1 => $values1) {
        //                 $get_pemanen = isset($values1['a']) ? $values1['a'] : (isset($values1['noPemanen']) ? $values1['noPemanen'] : null);
        //                 $get_kurangBrondol = isset($values1['b']) ? $values1['b'] : (isset($values1['kurangBrondol']) ? $values1['kurangBrondol'] : 0);
        //                 $get_tanpaBrondol = isset($values1['c']) ? $values1['c'] : (isset($values1['tanpaBrondol']) ? $values1['tanpaBrondol'] : 0);
        //                 if ($get_kurangBrondol != 0) {
        //                     $datakurang_brondol['kurangBrondol_list'][] = [
        //                         'no_pemanen' => ($get_pemanen == 999) ? 'x' : $get_pemanen,
        //                         'kurangBrondol' => $get_kurangBrondol,
        //                     ];
        //                 }
        //                 if ($get_kurangBrondol != 0) {
        //                     $tanpaBrondol['tanpaBrondol_list'][] = [
        //                         'no_pemanen' => ($get_pemanen == 999) ? 'x' : $get_pemanen,
        //                         'tanpaBrondol' => $get_tanpaBrondol,
        //                     ];
        //                 }
        //             }

        //             // dd($datakurang_brondol, $tanpaBrondol);



        //             // Output results

        //             $result[] = [
        //                 'id' => $value['id'],
        //                 'estate' => $value['estate'],
        //                 'afdeling' => $value['afdeling'],
        //                 'jjg_grading' => $value['jjg_grading'],
        //                 'no_plat' => $value['no_plat'],
        //                 'jjg_spb' => $value['jjg_spb'],
        //                 'datetime' => $value['datetime'],
        //                 'tonase' => $value['tonase'],
        //                 // 'bjr' => ($value['jjg_spb'] / $value['tonase']) * 100,
        //                 'bjr' => $value['tonase'] > 0 ? round(($value['tonase'] / $value['jjg_grading']) * 100, 2) : 0,
        //                 'jjg_selisih' => $date_arr['jjg_selisih'],
        //                 'persentase_selisih' => round($date_arr['percentage_selisih_janjang']),
        //                 'Ripeness' => $date_arr['ripeness'],
        //                 'percentase_ripenes' => round($date_arr['percentage_ripeness'], 2),
        //                 'Unripe' => $date_arr['unripe'],
        //                 'persenstase_unripe' => round($date_arr['percentage_unripe'], 2),
        //                 'nol_brondol' => $date_arr['brondol_0'],
        //                 'persentase_nol_brondol' => round($date_arr['percentage_brondol_0'], 2),
        //                 'kurang_brondol' => $date_arr['brondol_less'],
        //                 'persentase_brondol' => round($date_arr['percentage_brondol_less'], 2),
        //                 'nomor_pemanen' => 'a',
        //                 'unripe_tanda_x' => 'a',
        //                 'Overripe' => $date_arr['overripe'],
        //                 'persentase_overripe' => round($date_arr['percentage_overripe'], 2),
        //                 'empty_bunch' => $value['empty'],
        //                 'persentase_empty_bunch' => round($date_arr['percentage_empty_bunch'], 2),
        //                 'rotten_bunch' => $value['rotten'],
        //                 'persentase_rotten_bunce' => round($date_arr['percentage_rotten_bunch'], 2),
        //                 'Abnormal' => $date_arr['abnormal'],
        //                 'persentase_abnormal' =>    round($date_arr['percentage_abnormal'], 2),
        //                 'stalk' =>    $date_arr['tangkai_panjang'],
        //                 'persentase_stalk' => round($date_arr['percentage_tangkai_panjang'], 2),
        //                 'vcut' =>    $value['vcut'],
        //                 'persentase_vcut' => round($date_arr['percentage_vcuts'], 2),
        //                 'loose_fruit' => $value['loose_fruit'],
        //                 'persentase_lose_fruit' => $date_arr['persentase_loose_fruit'],
        //                 'Dirt' => $value['dirt'],
        //                 'persentase' => $date_arr['persentase'],
        //                 'foto' => $foto,
        //                 'pemanen_list_tanpabrondol' => $tanpaBrondol,
        //                 'pemanen_list_kurangbrondol' => $datakurang_brondol,
        //                 'kelas_a' => $value['kelas_a'],
        //                 'persentase_kelas_a' => round($date_arr['percentage_kelas_a'], 2),
        //                 'kelas_b' => $value['kelas_b'],
        //                 'persentase_kelas_b' => round($date_arr['percentage_kelas_b'], 2),
        //                 'kelas_c' => $value['kelas_c'],
        //                 'persentase_kelas_c' => round($date_arr['percentage_kelas_c'], 2),
        //             ];
        //         }
        //         // dd($data, $result);
        //         // $result now contains the processed data


        //     }
        //     // dd($result);
        //     $arr = array();
        //     $arr['data_perhari'] = $result;
        //     // dd($result, $data);
        //     return $arr;
        // } else {
        //     // dd($data);
        //     $calculationService = new \App\Services\CalculationGrading();

        //     $type = 'perhari';


        //     $result = $calculationService->getGradingData($bulan, $reg, $type);
        //     // dd($result);
        //     $arr = array();
        //     $arr['data_pperafd'] = $result;

        //     return $arr;
        //     // // dd($result, $data);
        //     // echo json_encode($arr);
        //     // exit();
        // }

        $calculationService = new \App\Services\CalculationGrading();

        $type = 'perhari';


        $result = $calculationService->getGradingData($bulan, $reg, $type);
        return $result;
    }
}

if (!function_exists('getdatamildetail')) {
    function getdatamildetail($estate, $afd, $date)
    {
        $estate = $estate;
        $afdeling = $afd;
        $date = $date;
        $calculationService = new \App\Services\CalculationGrading();

        $type = 'perhari';


        $result = $calculationService->getGradingData($date, null, $type, null, $estate, $afdeling);
        $arr = array();
        $arr['data_perhari'] = $result;
        return $arr;
    }
}


if (!function_exists('getdatamildetailpertanggal')) {
    function getdatamildetailpertanggal($bulan, $reg)
    {

        // dd($bulan, $reg);
        $data = Gradingmill::query()
            ->with('Listmill')
            ->selectRaw('*, DATE(datetime) as tanggal')
            ->where('datetime', 'like', '%' . $bulan . '%')
            ->whereHas('Listmill', function ($query) use ($reg) {
                $query->where('reg', $reg);
            })
            ->orderBy('datetime', 'asc')
            ->orderBy('estate', 'asc')
            ->orderBy('afdeling', 'asc')
            ->get()
            ->groupBy(['tanggal', 'estate', 'afdeling']);

        $data = json_decode($data, true);
        if ($data) {
            ksort($data); // Sort by date keys
        }
        // dd($data);

        // Assuming your original array is stored in $data
        $result = [];
        if (!empty($data)) {
            foreach ($data as $date => $estates) {
                $estates_result = [];
                $jjg_grading_perdate = 0;
                $jjg_spb_perdate = 0;
                $unripe_tanpa_brondol_perdate = 0;
                $unripe_kurang_brondol_perdate = 0;
                $overripe_perdate = 0;
                $empty_perdate = 0;
                $rotten_perdate = 0;
                $tangkai_panjang_perdate = 0;
                $vcut_perdate = 0;
                $kelas_a_perdate = 0;
                $kelas_b_perdate = 0;
                $kelas_c_perdate = 0;
                $tonase_perdate = 0;
                $dirt_perdate = 0;
                $loose_fruit_perdate = 0;
                $abn_partheno_perdate = 0;
                $abn_hard_perdate = 0;
                $abn_sakit_perdate = 0;
                $abn_kastrasi_perdate = 0;
                foreach ($estates as $estate => $blocks) {
                    $blocks_result = [];
                    $jjg_grading_estate = 0;
                    $jjg_spb_estate = 0;
                    $unripe_tanpa_brondol_estate = 0;
                    $unripe_kurang_brondol_estate = 0;
                    $overripe_estate = 0;
                    $empty_estate = 0;
                    $rotten_estate = 0;
                    $tangkai_panjang_estate = 0;
                    $vcut_estate = 0;
                    $kelas_a_estate = 0;
                    $kelas_b_estate = 0;
                    $kelas_c_estate = 0;
                    $tonase_estate = 0;
                    $dirt_estate = 0;
                    $loose_fruit_estate = 0;
                    $abn_partheno_estate = 0;
                    $abn_hard_estate = 0;
                    $abn_sakit_estate = 0;
                    $abn_kastrasi_estate = 0;
                    foreach ($blocks as $block => $items) {
                        // Create the new array you want to add
                        $jjg_grading = 0;
                        $jjg_spb = 0;
                        $unripe_tanpa_brondol = 0;
                        $unripe_kurang_brondol = 0;
                        $overripe = 0;
                        $empty = 0;
                        $rotten = 0;
                        $tangkai_panjang = 0;
                        $vcut = 0;
                        $kelas_a = 0;
                        $kelas_b = 0;
                        $kelas_c = 0;
                        $abn_partheno = 0;
                        $abn_hard = 0;
                        $abn_sakit = 0;
                        $abn_kastrasi = 0;
                        $tonase = 0;
                        $dirt = 0;
                        $loose_fruit = 0;
                        $brondol_0 = 0;
                        $brondol_less = 0;
                        foreach ($items as $key => $value) {
                            $jjg_grading += $value['jjg_grading'];
                            $jjg_spb += $value['jjg_spb'];

                            $unripe_tanpa_brondol += is_numeric($value['unripe_tanpa_brondol']) ? $value['unripe_tanpa_brondol'] : 0;
                            $unripe_kurang_brondol += is_numeric($value['unripe_kurang_brondol']) ? $value['unripe_kurang_brondol'] : 0;


                            // $unripe_tanpa_brondol += $value['unripe_tanpa_brondol'];
                            // $unripe_kurang_brondol += $value['unripe_kurang_brondol'];
                            $overripe += $value['overripe'];
                            $empty += $value['empty'];
                            $rotten += $value['rotten'];
                            $tangkai_panjang += $value['tangkai_panjang'];
                            $vcut += $value['vcut'];
                            $kelas_a += $value['kelas_a'];
                            $kelas_b += $value['kelas_b'];
                            $kelas_c += $value['kelas_c'];
                            $abn_partheno += $value['abn_partheno'];
                            $abn_hard += $value['abn_hard'];
                            $abn_sakit += $value['abn_sakit'];
                            $abn_kastrasi += $value['abn_kastrasi'];
                            $tonase += $value['tonase'];
                            $dirt += $value['dirt'];
                            $loose_fruit += $value['loose_fruit'];
                        }
                        $array = [
                            'estate' => $estate,
                            'afdeling' => $block,
                            'jjg_grading' => $jjg_grading,
                            'jjg_spb' => $jjg_spb,
                            'brondol_0' => $unripe_tanpa_brondol,
                            'brondol_less' => $unripe_kurang_brondol,
                            'overripe' => $overripe,
                            'empty' => $empty,
                            'rotten' => $rotten,
                            'tangkai_panjang' => $tangkai_panjang,
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
                        ];

                        $afdeling_arr = formula_grading($array);
                        // Add the new array to the existing items
                        $items_copy = $items;
                        $items_copy['total_blok'] = $afdeling_arr;
                        $blocks_result[$block] = $items_copy;

                        $jjg_grading_estate += $jjg_grading;
                        $jjg_spb_estate += $jjg_spb;
                        $unripe_tanpa_brondol_estate += $unripe_tanpa_brondol;
                        $unripe_kurang_brondol_estate += $unripe_kurang_brondol;
                        $overripe_estate += $overripe;
                        $empty_estate += $empty;
                        $rotten_estate += $rotten;
                        $tangkai_panjang_estate += $tangkai_panjang;
                        $vcut_estate += $vcut;
                        $kelas_a_estate += $kelas_a;
                        $kelas_b_estate += $kelas_b;
                        $kelas_c_estate += $kelas_c;
                        $tonase_estate += $tonase;
                        $dirt_estate += $dirt;
                        $loose_fruit_estate += $loose_fruit;
                        $abn_partheno_estate += $abn_partheno;
                        $abn_hard_estate += $abn_hard;
                        $abn_sakit_estate += $abn_sakit;
                        $abn_kastrasi_estate += $abn_kastrasi;
                    }

                    $array_estate = [
                        'estate' => $estate,
                        'afdeling' => 'est',
                        'jjg_grading' => $jjg_grading_estate,
                        'jjg_spb' => $jjg_spb_estate,
                        'brondol_0' => $unripe_tanpa_brondol_estate,
                        'brondol_less' => $unripe_kurang_brondol_estate,
                        'overripe' => $overripe_estate,
                        'empty' => $empty_estate,
                        'rotten' => $rotten_estate,
                        'tangkai_panjang' => $tangkai_panjang_estate,
                        'vcuts' => $vcut_estate,
                        'tonase' => $tonase_estate,
                        'dirt' => $dirt_estate,
                        'loose_fruit' => $loose_fruit_estate,
                        'abn_partheno' => $abn_partheno_estate,
                        'abn_hard' => $abn_hard_estate,
                        'abn_sakit' => $abn_sakit_estate,
                        'abn_kastrasi' => $abn_kastrasi_estate,
                        'kelas_a' => $kelas_a_estate,
                        'kelas_b' => $kelas_b_estate,
                        'kelas_c' => $kelas_c_estate,
                    ];

                    // dd($array_estate);
                    $estate_arr = formula_grading($array_estate);
                    $blocks_result['total_estate'] = $estate_arr;
                    $estates_result[$estate] = $blocks_result;

                    $jjg_grading_perdate += $jjg_grading_estate;
                    $jjg_spb_perdate += $jjg_spb_estate;
                    $unripe_tanpa_brondol_perdate += $unripe_tanpa_brondol_estate;
                    $unripe_kurang_brondol_perdate += $unripe_kurang_brondol_estate;
                    $overripe_perdate += $overripe_estate;
                    $empty_perdate += $empty_estate;
                    $rotten_perdate += $rotten_estate;
                    $tangkai_panjang_perdate += $tangkai_panjang_estate;
                    $vcut_perdate += $vcut_estate;
                    $kelas_a_perdate += $kelas_a_estate;
                    $kelas_b_perdate += $kelas_b_estate;
                    $kelas_c_perdate += $kelas_c_estate;
                    $tonase_perdate += $tonase_estate;
                    $dirt_perdate += $dirt_estate;
                    $loose_fruit_perdate += $loose_fruit_estate;
                    $abn_partheno_perdate += $abn_partheno_estate;
                    $abn_hard_perdate += $abn_hard_estate;
                    $abn_sakit_perdate += $abn_sakit_estate;
                    $abn_kastrasi_perdate += $abn_kastrasi_estate;
                }
                $array_perdate = [
                    'estate' => $estate,
                    'afdeling' => 'est',
                    'jjg_grading' => $jjg_grading_perdate,
                    'jjg_spb' => $jjg_spb_perdate,
                    'brondol_0' => $unripe_tanpa_brondol_perdate,
                    'brondol_less' => $unripe_kurang_brondol_perdate,
                    'overripe' => $overripe_perdate,
                    'empty' => $empty_perdate,
                    'rotten' => $rotten_perdate,
                    'tangkai_panjang' => $tangkai_panjang_perdate,
                    'vcuts' => $vcut_perdate,
                    'tonase' => $tonase_perdate,
                    'dirt' => $dirt_perdate,
                    'loose_fruit' => $loose_fruit_perdate,
                    'abn_partheno' => $abn_partheno_perdate,
                    'abn_hard' => $abn_hard_perdate,
                    'abn_sakit' => $abn_sakit_perdate,
                    'abn_kastrasi' => $abn_kastrasi_perdate,
                    'kelas_a' => $kelas_a_perdate,
                    'kelas_b' => $kelas_b_perdate,
                    'kelas_c' => $kelas_c_perdate,
                ];

                // dd($array_perdate);
                $date_arr = formula_grading($array_perdate);
                $estates_result['total_date'] = $date_arr;
                $result[$date] = $estates_result;
            }
            $data = $result;
        }

        // dd($data);
        $flattenedData = [];

        foreach ($data as $date => $estates) {
            // dd($estates);
            foreach ($estates as $estate => $afdelings) {
                if ($estate !== 'total_date') {
                    foreach ($afdelings as $afdeling => $data) {
                        if ($afdeling !== 'total_estate') {
                            foreach ($data as $key => $value) {
                                if ($key !== 'total_blok') {
                                    $loose_fruit_kg = round(($value['loose_fruit'] / $value['tonase']) * 100, 2);
                                    $dirt_kg = round(($value['dirt'] / $value['tonase']) * 100, 2);
                                    $abnormal = $value['abn_partheno'] + $value['abn_hard'] + $value['abn_sakit'] + $value['abn_kastrasi'];
                                    // dd($value);
                                    $unripe = intval($value['unripe_tanpa_brondol']) + intval($value['unripe_kurang_brondol']);
                                    $ripeness = $value['jjg_grading'] - ($value['overripe'] + $value['empty'] + $value['rotten'] + $abnormal + $unripe);

                                    // Calculate percentages
                                    $percentage_ripeness = ($ripeness / $value['jjg_grading']) * 100;
                                    $percentage_unripe = ($unripe / $value['jjg_grading']) * 100;
                                    // $percentage_brondol_0 = ($value['unripe_tanpa_brondol'] / $value['jjg_grading']) * 100;
                                    // $percentage_brondol_less = ($value['unripe_kurang_brondol'] / $value['jjg_grading']) * 100;
                                    $percentage_overripe = ($value['overripe'] / $value['jjg_grading']) * 100;
                                    $percentage_empty_bunch = ($value['empty'] / $value['jjg_grading']) * 100;
                                    $percentage_rotten_bunch = ($value['rotten'] / $value['jjg_grading']) * 100;
                                    $percentage_abnormal = ($abnormal / $value['jjg_grading']) * 100;
                                    $percentage_tangkai_panjang = ($value['tangkai_panjang'] / $value['jjg_grading']) * 100;
                                    $percentage_vcuts = ($value['vcut'] / $value['jjg_grading']) * 100;
                                    $percentage_kelas_a = ($value['kelas_a'] / $value['jjg_grading']) * 100;
                                    $percentage_kelas_b = ($value['kelas_b'] / $value['jjg_grading']) * 100;
                                    $percentage_kelas_c = ($value['kelas_c'] / $value['jjg_grading']) * 100;

                                    $jumlah_selisih_janjang = $value['jjg_grading'] - $value['jjg_spb'];
                                    $percentage_selisih_janjang = ($jumlah_selisih_janjang / $value['jjg_spb']) * 100;
                                    // $bjr = round(($value['jjg_spb'] / $value['tonase']) * 100, 2);
                                    $bjr = $value['tonase'] > 0 ? round(($value['tonase'] / $value['jjg_grading']) * 100, 2) : 0;
                                    $jam = date('H:i', strtotime($value['datetime']));
                                    // Regular row
                                    $flattenedData[] = [
                                        'type' => 'data',
                                        'date' => $date,
                                        'estate' => $estate,
                                        'afdeling' => $value['afdeling'],
                                        'mill' => $value['mill'],
                                        'datetime' => $jam,
                                        'no_plat' => $value['no_plat'],
                                        'driver' => $value['driver'],
                                        'blok' => $value['blok'],
                                        'jjg_spb' => $value['jjg_spb'],
                                        'jjg_grading' => $value['jjg_grading'],
                                        'tonase' => $value['tonase'],
                                        'bjr' => $bjr,
                                        'ripeness' => $ripeness,
                                        'percentage_ripeness' => $percentage_ripeness,
                                        'unripe' => $unripe,
                                        'percentage_unripe' => $percentage_unripe,
                                        'overripe' => $value['overripe'],
                                        'percentage_overripe' => $percentage_overripe,
                                        'empty' => $value['empty'],
                                        'percentage_empty_bunch' => $percentage_empty_bunch,
                                        'rotten' => $value['rotten'],
                                        'percentage_rotten_bunch' => $percentage_rotten_bunch,
                                        'abnormal' => $abnormal,
                                        'percentage_abnormal' => $percentage_abnormal,
                                        'tangkai_panjang' => $value['tangkai_panjang'],
                                        'percentage_tangkai_panjang' => $percentage_tangkai_panjang,
                                        'vcuts' => $value['vcut'],
                                        'percentage_vcut' => $percentage_vcuts,
                                        'loose_fruit' => $value['loose_fruit'],
                                        'percentage_loose_fruit_kg' => $loose_fruit_kg,
                                        'dirt' => $value['dirt'],
                                        'percentage_dirt_kg' => $dirt_kg,
                                        'kelas_c' => $value['kelas_c'],
                                        'percentage_kelas_c' => $percentage_kelas_c,
                                        'kelas_b' => $value['kelas_b'],
                                        'percentage_kelas_b' => $percentage_kelas_b,
                                        'kelas_a' => $value['kelas_a'],
                                        'percentage_kelas_a' => $percentage_kelas_a,
                                    ];
                                } else {
                                    // Afdeling total row
                                    $flattenedData[] = [
                                        'type' => 'afdeling_total',
                                        'date' => $date,
                                        'estate' => $estate,
                                        'afdeling' => $value['afdeling'],
                                        'mill' => '-',
                                        'datetime' => '-',
                                        'no_plat' => '-',
                                        'driver' => '-',
                                        'blok' => '-',
                                        'jjg_spb' => $value['jjg_spb'],
                                        'jjg_grading' => $value['jjg_grading'],
                                        'tonase' => $value['tonase'],
                                        'bjr' => $value['bjr'],
                                        'ripeness' => $value['Ripeness'],
                                        'percentage_ripeness' => $value['percentase_ripenes'],
                                        'unripe' => $value['Unripe'],
                                        'percentage_unripe' => $value['persenstase_unripe'],
                                        'overripe' => $value['overripe'],
                                        'percentage_overripe' => $value['persentase_overripe'],
                                        'empty' => $value['empty'],
                                        'percentage_empty_bunch' => $value['persentase_empty_bunch'],
                                        'rotten' => $value['rotten'],
                                        'percentage_rotten_bunch' => $value['persentase_rotten_bunce'],
                                        'abnormal' => $value['Abnormal'],
                                        'percentage_abnormal' => $value['persentase_abnormal'],
                                        'tangkai_panjang' => $value['tangkai_panjang'],
                                        'percentage_tangkai_panjang' => $value['persentase_stalk'],
                                        'vcuts' => $value['vcuts'],
                                        'percentage_vcut' => $value['persentase_vcut'],
                                        'loose_fruit' => $value['loose_fruit'],
                                        'percentage_loose_fruit_kg' => $value['persentase_lose_fruit'],
                                        'dirt' => $value['dirt'],
                                        'percentage_dirt_kg' => $value['persentase'],
                                        'kelas_c' => $value['kelas_c'],
                                        'percentage_kelas_c' => $value['persentase_kelas_c'],
                                        'kelas_b' => $value['kelas_b'],
                                        'percentage_kelas_b' => $value['persentase_kelas_b'],
                                        'kelas_a' => $value['kelas_a'],
                                        'percentage_kelas_a' => $value['persentase_kelas_a'],
                                    ];
                                }
                            }
                        } else {
                            // Estate total row
                            $flattenedData[] = [
                                'type' => 'estate_total',
                                'date' => $date,
                                'estate' => $estate,
                                'afdeling' => '-',
                                'mill' => '-',
                                'datetime' => '-',
                                'no_plat' => '-',
                                'driver' => '-',
                                'blok' => '-',
                                'jjg_spb' => $data['jjg_spb'],
                                'jjg_grading' => $data['jjg_grading'],
                                'tonase' => $data['tonase'],
                                'bjr' => $data['bjr'],
                                'ripeness' => $data['Ripeness'],
                                'percentage_ripeness' => $data['percentase_ripenes'],
                                'unripe' => $data['Unripe'],
                                'percentage_unripe' => $data['persenstase_unripe'],
                                'overripe' => $data['overripe'],
                                'percentage_overripe' => $data['persentase_overripe'],
                                'empty' => $data['empty'],
                                'percentage_empty_bunch' => $data['persentase_empty_bunch'],
                                'rotten' => $data['rotten'],
                                'percentage_rotten_bunch' => $data['persentase_rotten_bunce'],
                                'abnormal' => $data['Abnormal'],
                                'percentage_abnormal' => $data['persentase_abnormal'],
                                'tangkai_panjang' => $data['tangkai_panjang'],
                                'percentage_tangkai_panjang' => $data['persentase_stalk'],
                                'vcuts' => $data['vcuts'],
                                'percentage_vcut' => $data['persentase_vcut'],
                                'loose_fruit' => $data['loose_fruit'],
                                'percentage_loose_fruit_kg' => $data['persentase_lose_fruit'],
                                'dirt' => $data['dirt'],
                                'percentage_dirt_kg' => $data['persentase'],
                                'kelas_c' => $data['kelas_c'],
                                'percentage_kelas_c' => $data['persentase_kelas_c'],
                                'kelas_b' => $data['kelas_b'],
                                'percentage_kelas_b' => $data['persentase_kelas_b'],
                                'kelas_a' => $data['kelas_a'],
                                'percentage_kelas_a' => $data['persentase_kelas_a'],
                            ];
                        }
                    }
                } else {
                    // Date total row
                    $flattenedData[] = [
                        'type' => 'date_total',
                        'date' => $date,
                        'estate' => '-',
                        'afdeling' => '-',
                        'mill' => '-',
                        'datetime' => '-',
                        'no_plat' => '-',
                        'driver' => '-',
                        'blok' => '-',
                        'jjg_spb' => $afdelings['jjg_spb'],
                        'jjg_grading' => $afdelings['jjg_grading'],
                        'tonase' => $afdelings['tonase'],
                        'bjr' => $afdelings['bjr'],
                        'ripeness' => $afdelings['Ripeness'],
                        'percentage_ripeness' => $afdelings['percentase_ripenes'],
                        'unripe' => $afdelings['Unripe'],
                        'percentage_unripe' => $afdelings['persenstase_unripe'],
                        'overripe' => $afdelings['overripe'],
                        'percentage_overripe' => $afdelings['persentase_overripe'],
                        'empty' => $afdelings['empty'],
                        'percentage_empty_bunch' => $afdelings['persentase_empty_bunch'],
                        'rotten' => $afdelings['rotten'],
                        'percentage_rotten_bunch' => $afdelings['persentase_rotten_bunce'],
                        'abnormal' => $afdelings['Abnormal'],
                        'percentage_abnormal' => $afdelings['persentase_abnormal'],
                        'tangkai_panjang' => $afdelings['tangkai_panjang'],
                        'percentage_tangkai_panjang' => $afdelings['persentase_stalk'],
                        'vcuts' => $afdelings['vcuts'],
                        'percentage_vcut' => $afdelings['persentase_vcut'],
                        'loose_fruit' => $afdelings['loose_fruit'],
                        'percentage_loose_fruit_kg' => $afdelings['persentase_lose_fruit'],
                        'dirt' => $afdelings['dirt'],
                        'percentage_dirt_kg' => $afdelings['persentase'],
                        'kelas_c' => $afdelings['kelas_c'],
                        'percentage_kelas_c' => $afdelings['persentase_kelas_c'],
                        'kelas_b' => $afdelings['kelas_b'],
                        'percentage_kelas_b' => $afdelings['persentase_kelas_b'],
                        'kelas_a' => $afdelings['kelas_a'],
                        'percentage_kelas_a' => $afdelings['persentase_kelas_a'],
                    ];
                }
            }
        }
        // save_json($flattenedData);
        return $flattenedData;
    }
}

if (!function_exists('rekap_estate_mill_perbulan_perhari')) {
    function rekap_estate_mill_perbulan_perhari($bulan, $reg, $mill, $type = null)
    {
        $calculationService = new \App\Services\CalculationGrading();

        // $type = 'perbulan';


        $result = $calculationService->getGradingData($bulan, $reg, $type, $mill);

        if ($type == 'perbulan') {
            return [
                'status' => 'success',
                'result' => $result,
            ];
        }

        // dd($result);
        $Total_est = [];
        foreach ($result as $key => $value) {
            $Total_est[$key] = $value['Total'];
        }
        $Total_est = $calculationService->getValueData($Total_est);
        $data_arr_level_3 = $calculationService->formula_grading($Total_est);
        $Total_est = $calculationService->formatResult($data_arr_level_3);

        // dd($Total_est);

        // dd($final);
        // dd($result, $final);
        $pt_name = Listmill::where('id', $mill)->first();
        $tanggal = Carbon::parse($bulan)->format('l, d F Y');

        return [
            'status' => 'success',
            'result' => $result,
            'final' => $Total_est,
            'pt_name' => $pt_name->nama_pt,
            'mill_name' => $pt_name->nama_mill,
            'tanggal' => $tanggal,
        ];

        // return $result;
    }
}

if (!function_exists('cetakPDFFI')) {
    function cetakPDFFI($id, $est, $tgl)
    {
        if ($est == 'Pla') {
            $est = 'Plasma1';
        }
        $date = Carbon::parse($tgl)->format('F Y');



        // buat baru  

        // dd($est);

        $getwill = DB::connection('mysql2')->table('estate')
            ->select('estate.*')
            ->where('est', $est)
            ->get();
        // dd($getwill);

        $mtTrans = DB::connection('mysql2')->table('mutu_transport')
            ->selectRaw("mutu_transport.*, DATE_FORMAT(datetime, '%Y-%m-%d') AS formatted_date")
            ->where('estate', $est)
            ->where('datetime', 'like', '%' . $tgl . '%')
            ->where('foto_temuan', '!=', ' ')
            ->orderBy('formatted_date', 'asc')
            ->orderBy('estate', 'asc')
            ->orderBy('afdeling', 'asc')
            ->orderBy('blok', 'asc')

            ->get();
        // dd($mtTrans);

        $mtTrans = $mtTrans->groupBy(['formatted_date', 'afdeling', 'blok']);
        $mtTrans = json_decode($mtTrans, true);


        $mtbuah = DB::connection('mysql2')->table('mutu_buah')
            ->selectRaw("mutu_buah.*, DATE_FORMAT(datetime, '%Y-%m-%d') AS formatted_date")
            ->where('estate', $est)
            ->where('datetime', 'like', '%' . $tgl . '%')
            ->where('foto_temuan', '!=', ' ')
            ->orderBy('formatted_date', 'asc')
            ->orderBy('estate', 'asc')
            ->orderBy('afdeling', 'asc')
            ->orderBy('blok', 'asc')

            ->get();

        $mtbuah = $mtbuah->groupBy(['formatted_date', 'afdeling', 'blok']);
        $mtbuah = json_decode($mtbuah, true);



        $mtancak = DB::connection('mysql2')->table('follow_up_ma')
            ->selectRaw("follow_up_ma.*, DATE_FORMAT(waktu_temuan, '%Y-%m-%d') AS formatted_date")
            ->where('estate', $est)
            ->where('waktu_temuan', 'like', '%' . $tgl . '%')
            ->orderBy('formatted_date', 'asc')
            ->orderBy('estate', 'asc')
            ->orderBy('afdeling', 'asc')
            ->orderBy('blok', 'asc')
            ->get();

        $mtancak = $mtancak->groupBy(['formatted_date', 'afdeling', 'blok']);
        $mtancak = json_decode($mtancak, true);

        // dd($mtbuah, $mtancak, $mtTrans);

        // $inc = 1;
        $allDates = [];

        foreach ($mtTrans as $key => $value) {
            $allDates[$key] = ['dates' => $key];
        }

        foreach ($mtancak as $key => $value) {
            $allDates[$key] = ['dates' => $key];
        }

        foreach ($mtbuah as $key => $value) {
            $allDates[$key] = ['dates' => $key];
        }

        // To remove duplicate dates and reindex the array keys
        $uniqueDates = array_values(array_unique(array_column($allDates, 'dates')));


        usort($uniqueDates, function ($a, $b) {
            return strtotime($a) - strtotime($b);
        });
        // dd($uniqueDates[0]);



        // dd($uniqueDates);
        // dd($getwill[0]->wil);
        if ($getwill[0]->wil == 7 || $getwill[0]->wil == 8) {
            # code...
            $start_date = Carbon::createFromDate($uniqueDates[0]); // Replace this with your dynamic date
            $current_date = $start_date->copy();

            $month = $start_date->month;
            $week_number = 1;

            $getdate = [];

            while ($current_date->month == $month) {
                $getdate[$current_date->format('Y-m-d')] = $week_number;

                $current_date->addDay();

                // Increment week number after every 7 days
                if ($current_date->diffInDays($start_date) % 7 === 0) {
                    $week_number++;
                }
            }
        } else {
            $inc = 1;
            foreach ($uniqueDates as $key => $value) {
                # code...
                $getdate[$value] = $inc++;
            }
        }

        // dd($getdate);

        $newtrans = array();

        foreach ($mtTrans as $date => $items) {
            foreach ($items as $category => $categoryItems) {
                foreach ($categoryItems as $itemKey => $itemData) {
                    foreach ($itemData as $key => $value) {

                        $newkeyafd = $value['estate'] . ' ' . $value['afdeling'] . ' ' . $value['blok'];

                        // Create the 'visit' array based on the date
                        foreach ($getdate as $key2 => $value2) {
                            if ($key2 == $date) {
                                $value['visit'] = $value2;
                            }
                        }

                        $newtrans[$value['estate'] . ' ' . $value['afdeling']][$newkeyafd]['mutu_transport'][] = $value;
                    }
                }
            }
        }

        $newBuah = array();

        foreach ($mtbuah as $date => $items) {
            foreach ($items as $category => $categoryItems) {
                foreach ($categoryItems as $itemKey => $itemData) {
                    foreach ($itemData as $key => $value) {

                        $newkeyafd = $value['estate'] . ' ' . $value['afdeling'] . ' ' . $value['blok'];

                        // Create the 'visit' array based on the date
                        foreach ($getdate as $key2 => $value2) {
                            if ($key2 == $date) {
                                $value['visit'] = $value2;
                            }
                        }

                        $newBuah[$value['estate'] . ' ' . $value['afdeling']][$newkeyafd]['mutu_buah'][] = $value;
                    }
                }
            }
        }

        $newAncak = array();

        foreach ($mtancak as $date => $items) {
            foreach ($items as $category => $categoryItems) {
                foreach ($categoryItems as $itemKey => $itemData) {
                    foreach ($itemData as $key => $value) {

                        $newkeyafd = $value['estate'] . ' ' . $value['afdeling'] . ' ' . $value['blok'];

                        // Create the 'visit' array based on the date
                        foreach ($getdate as $key2 => $value2) {
                            if ($key2 == $date) {
                                $value['visit'] = $value2;
                            }
                        }

                        $newAncak[$value['estate'] . ' ' . $value['afdeling']][$newkeyafd]['mutu_ancak'][] = $value;
                    }
                }
            }
        }


        // Merge arrays into one
        $mergedArrays = array_merge_recursive($newtrans, $newAncak, $newBuah);

        // Iterate through the merged array and fill missing keys with empty arrays
        foreach ($mergedArrays as $estate => &$estateData) {
            foreach ($estateData as $category => &$categoryData) {
                // Ensure all necessary keys exist, else set them as empty arrays
                $categoryData += [
                    'mutu_transport' => [],
                    'mutu_ancak' => [],
                    'mutu_buah' => [],
                ];
            }
        }

        // Unset references
        unset($estateData, $categoryData);
        // dd($mergedArrays);

        return $mergedArrays;
    }
}


if (!function_exists('formatangka')) {
    function formatangka($value)
    {
        return number_format($value, 0, ',', '.');
    }
}

if (!function_exists('roundangka_decimal')) {
    function roundangka_decimal($value)
    {
        return round($value, 2);
    }
}

if (!function_exists('formula_grading')) {
    function formula_grading($array)
    {

        $loose_fruit_kg = $array['tonase'] > 0 ? round(($array['loose_fruit'] / $array['tonase']) * 100, 2) : 0;
        $dirt_kg = $array['tonase'] > 0 ? round(($array['dirt'] / $array['tonase']) * 100, 2) : 0;
        $abnormal = $array['abn_partheno'] + $array['abn_hard'] + $array['abn_sakit'] + $array['abn_kastrasi'];
        $unripe = $array['brondol_0'] + $array['brondol_less'];
        $ripeness = $array['jjg_grading'] - ($array['overripe'] + $array['empty'] + $array['rotten'] + $abnormal + $unripe);

        // Calculate percentages
        $percentage_ripeness = $array['jjg_grading'] > 0 ? ($ripeness / $array['jjg_grading']) * 100 : 0;
        $percentage_unripe = $array['jjg_grading'] > 0 ? ($unripe / $array['jjg_grading']) * 100 : 0;
        $percentage_brondol_0 = $array['jjg_grading'] > 0 ? ($array['brondol_0'] / $array['jjg_grading']) * 100 : 0;
        $percentage_brondol_less = $array['jjg_grading'] > 0 ? ($array['brondol_less'] / $array['jjg_grading']) * 100 : 0;
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
            'no_plat' => 'null',
            'abn_partheno' => $array['abn_partheno'],
            'abn_hard' => $array['abn_hard'],
            'abn_sakit' => $array['abn_sakit'],
            'abn_kastrasi' => $array['abn_kastrasi'],
            'jjg_spb' => $array['jjg_spb'],
            'datetime' => 'null',
            'tonase' => $array['tonase'],
            // 'bjr' => $array['tonase'] > 0 ? round(($array['jjg_spb'] / $array['tonase']) * 100, 2) : 0,
            'bjr' => $array['jjg_grading'] > 0 ? round(($array['tonase'] / $array['jjg_grading']) * 100, 2) : 0,
            'jjg_selisih' => $jumlah_selisih_janjang,
            'persentase_selisih' => round($percentage_selisih_janjang),
            'Ripeness' => $ripeness,
            'percentase_ripenes' => round($percentage_ripeness, 2),
            'Unripe' => $unripe,
            'persenstase_unripe' => round($percentage_unripe, 2),
            'brondol_0' => $array['brondol_0'],
            'persentase_nol_brondol' => round($percentage_brondol_0, 2),
            'brondol_less' => $array['brondol_less'],
            'persentase_brondol' => round($percentage_brondol_less, 2),
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
        ];
    }
}

if (!function_exists('save_json')) {
    function save_json($data)
    {
        $jsonData = json_encode($data, JSON_PRETTY_PRINT);
        $filename = storage_path('app/grading_data.json');
        file_put_contents($filename, $jsonData);;
    }
}
if (!function_exists('getRekapPerbulanSidaktph')) {
    function getRekapPerbulanSidaktph($date, $collect_estate, $newparamsdate)
    {
        $date = Carbon::parse($date)->format('Y-m');

        $ancakFA = DB::connection('mysql2')
            ->table('sidak_tph')
            ->whereIn('sidak_tph.est', $collect_estate)
            ->select(
                "sidak_tph.*",
                DB::raw('DATE_FORMAT(sidak_tph.datetime, "%Y-%m-%d") as tanggal'),
                DB::raw('DATE_FORMAT(sidak_tph.datetime, "%M") as bulan'),
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
            ->where('sidak_tph.datetime', 'like', '%' . $date . '%')
            ->orderBy('est', 'asc')
            ->orderBy('afd', 'asc')
            ->get();

        $ancakFA = $ancakFA->groupBy(['est', 'afd', 'statuspanen', 'tanggal']);
        $ancakFA = json_decode($ancakFA, true);

        // dd($ancakFA, $collect_estate, $date);

        // dd($ancakFA['SPE']);
        // Start of Selection
        $default_h_panen = [];
        for ($i = 1; $i <= 8; $i++) {
            $default_h_panen[(string)$i] = [
                "est" => "-",
                "afd" => "-",
                "total_score" => "-",
                "kategori" => "-",
                "inspek" => "-",
                "tph" => "-",
                "jalan" => "-",
                "bin" => "-",
                "karung" => "-",
                "buah" => "-",
                "restan" => "-",
                "skor_brd" => "-",
                "skor_janjang" => "-",
                "tot_brd" => "-",
                "tod_jjg" => "-",
            ];
        }
        // dd($default_h_panen);

        $result = [];
        foreach ($ancakFA as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $sum_bt_tph_3 = 0;
                $sum_bt_jalan_3 = 0;
                $sum_bt_bin_3 = 0;
                $sum_jum_karung_3 = 0;
                $sum_buah_tinggal_3 = 0;
                $sum_restan_unreported_3 = 0;
                $tod_brd_sum = 0;
                $tod_jjg_sum = 0;
                foreach ($value1 as $key2 => $value2) {
                    $sum_bt_tph_2 = 0;
                    $sum_bt_jalan_2 = 0;
                    $sum_bt_bin_2 = 0;
                    $sum_jum_karung_2 = 0;
                    $sum_buah_tinggal_2 = 0;
                    $sum_restan_unreported_2 = 0;
                    foreach ($value2 as $key3 => $value3) {
                        $sum_bt_tph = 0;
                        $sum_bt_jalan = 0;
                        $sum_bt_bin = 0;
                        $sum_jum_karung = 0;
                        $sum_buah_tinggal = 0;
                        $sum_restan_unreported = 0;
                        foreach ($value3 as $key4 => $value4) {
                            // dd($key3);
                            $sum_bt_tph += $value4['bt_tph'];
                            $sum_bt_jalan += $value4['bt_jalan'];
                            $sum_bt_bin += $value4['bt_bin'];
                            $sum_jum_karung += $value4['jum_karung'];
                            $sum_buah_tinggal += $value4['buah_tinggal'];
                            $sum_restan_unreported += $value4['restan_unreported'];
                        }

                        // $result[$key][$key1][$key2][$key3] = [
                        //     'tphx' => $sum_bt_tph,
                        //     'jalan' => $sum_bt_jalan,
                        //     'bin' => $sum_bt_bin,
                        //     'karung' => $sum_jum_karung,
                        // ];
                        $sum_bt_tph_2 += $sum_bt_tph;
                        $sum_bt_jalan_2 += $sum_bt_jalan;
                        $sum_bt_bin_2 += $sum_bt_bin;
                        $sum_jum_karung_2 += $sum_jum_karung;
                        $sum_buah_tinggal_2 += $sum_buah_tinggal;
                        $sum_restan_unreported_2 += $sum_restan_unreported;
                    }
                    $status_panen = $key2;

                    if ($newparamsdate === 'new') {
                        [$panen_brd, $panen_jjg] = calculatePanennew($status_panen);
                    } else {
                        [$panen_brd, $panen_jjg] = calculatePanen($status_panen);
                    }


                    $total_brondolan =  round(($sum_bt_tph_2 + $sum_bt_jalan_2 + $sum_bt_bin_2 + $sum_jum_karung_2) * $panen_brd / 100, 1);
                    $total_janjang =  round(($sum_buah_tinggal_2 + $sum_restan_unreported_2) * $panen_jjg / 100, 1);
                    $tod_brd = $sum_bt_tph_2 + $sum_bt_jalan_2 + $sum_bt_bin_2 + $sum_jum_karung_2;
                    $tod_jjg = $sum_buah_tinggal_2 + $sum_restan_unreported_2;
                    $result[$key][$key1][$key2] = [
                        "est" => $key,
                        "afd" => $key1,
                        "total_score" => $tod_brd + $tod_jjg,
                        "kategori" => "-",
                        "inspek" => "-",
                        "tph" => $sum_bt_tph_2,
                        "jalan" => $sum_bt_jalan_2,
                        "bin" => $sum_bt_bin_2,
                        "karung" => $sum_jum_karung_2,
                        "buah" => $sum_buah_tinggal_2,
                        "restan" => $sum_restan_unreported_2,
                        "skor_brd" => $total_brondolan,
                        "skor_janjang" => $total_janjang,
                        "tot_brd" => $tod_brd,
                        "tod_jjg" => $tod_jjg,
                    ];

                    $sum_bt_tph_3 += $sum_bt_tph_2;
                    $sum_bt_jalan_3 += $sum_bt_jalan_2;
                    $sum_bt_bin_3 += $sum_bt_bin_2;
                    $sum_jum_karung_3 += $sum_jum_karung_2;
                    $sum_buah_tinggal_3 += $sum_buah_tinggal_2;
                    $sum_restan_unreported_3 += $sum_restan_unreported_2;
                    $tod_brd_sum += $total_brondolan;
                    $tod_jjg_sum += $total_janjang;
                }

                $total_estkors = $tod_brd_sum + $tod_jjg_sum;
                $checkscore = 100 - ($total_estkors);
                if ($checkscore < 0) {
                    $all_score = 0;
                } else {
                    $all_score = $checkscore;
                }
                $result[$key][$key1]['Totalafd'] = [
                    "est" => $key,
                    "afd" => $key1,
                    "total_score" => $all_score,
                    "kategori" => "-",
                    "inspek" => "-",
                    "tot_brd" => $tod_brd_sum,
                    "tod_jjg" => $tod_jjg_sum,
                ];
            }
        }

        // dd($result);
        // // Iterate through estates
        foreach ($result as $estate => $afdeling_data) {
            // Iterate through afdelings
            foreach ($afdeling_data as $afd => $status_data) {
                // Create a new array with default values
                $merged_status = $default_h_panen;

                // Merge existing data with defaults
                foreach ($status_data as $status => $data) {
                    $merged_status[$status] = $data;
                }

                // Replace original data with merged data
                $result[$estate][$afd] = $merged_status;
            }
        }
        $flattened = [];
        $index = 0;

        // dd($result);
        foreach ($result as $est => $afdArray) {
            foreach ($afdArray as $afd => $weekData) {
                // dd($weekData);
                $newRow = [
                    'est' => $est,
                    'afd' => $afd,
                    'total_score' => $weekData['Totalafd']['total_score'],
                    'kategori' => $weekData['Totalafd']['kategori'],
                    'inspek' => $weekData['Totalafd']['inspek'],
                ];

                // Loop through weeks 1-8 to add numbered fields
                for ($week = 1; $week <= 8; $week++) {
                    $newRow["tph$week"] = $weekData[$week]['tph'];
                    $newRow["jalan$week"] = $weekData[$week]['jalan'];
                    $newRow["bin$week"] = $weekData[$week]['bin'];
                    $newRow["karung$week"] = $weekData[$week]['karung'];
                    $newRow["buah$week"] = $weekData[$week]['buah'];
                    $newRow["restan$week"] = $weekData[$week]['restan'];
                    $newRow["skor_brd$week"] = $weekData[$week]['skor_brd'];
                    $newRow["skor_janjang$week"] = $weekData[$week]['skor_janjang'];
                    $newRow["tot_brd$week"] = $weekData[$week]['tot_brd'];
                    $newRow["tod_jjg$week"] = $weekData[$week]['tod_jjg'];
                }

                $flattened[$index] = $newRow;
                $index++;
            }
        }
        // dd($flattened[37]);
        return $flattened;
    }
}


if (!function_exists('GetDepartementFamily')) {
    function GetDepartementFamily($parentId)
    {
        $allDepartments = Departement::all();

        // Function to get all child departments of a given parent department
        $getChildDepartments = function ($parentId, $departments) use (&$getChildDepartments) {
            $childDepartments = [];
            foreach ($departments as $department) {
                if ($department->id_parent == $parentId) {
                    $childDepartments[] = $department->id;
                    // Recursively get child departments
                    $childDepartments = array_merge($childDepartments, $getChildDepartments($department->id, $departments));
                }
            }
            $childDepartments[] = $parentId;
            return $childDepartments;
        };
        return $getChildDepartments($parentId, $allDepartments);
    }
}


if (!function_exists('check_edit_permittion')) {
    function check_edit_permittion($establishment)
    {
        $user = auth()->user();
        $jsonDepartments = $user->json_deps;
        $userDepartments = $user->Departement;


        // dd($GetDepartementFamily);
        // dd($userDepartments, $jsonDepartments);

        // if (!$jsonDepartments && !$userDepartments) {
        //     return false;
        // }

        // dd($userDepartments);
        if (!$userDepartments) {
            // dd('no user departments');
            return false;
        }

        // $departmentNames = [];

        // if ($jsonDepartments) {
        //     $departments = json_decode($jsonDepartments, true);
        //     $departmentIds = collectDepartmentIds($departments);

        //     $departmentNames = Departement::whereIn('id', $departmentIds)
        //         ->pluck('nama', 'id')
        //         ->toArray();
        // }

        if ($userDepartments) {
            $GetDepartementFamily = GetDepartementFamily($userDepartments[0]->id_parent);
            $departmentNames = Departement::whereIn('id', $GetDepartementFamily)
                ->pluck('nama', 'id')
                ->toArray();

            // dd($departmentNames);
        }

        return in_array($establishment, $departmentNames);
    }
}

if (!function_exists('collectDepartmentIds')) {
    function collectDepartmentIds($departments)
    {
        $ids = [];
        foreach ($departments as $department) {
            foreach ($department as $key => $value) {
                if ($value === null) {
                    continue;
                }

                if (
                    str_starts_with($key, 'departement') ||
                    str_starts_with($key, 'departementsub')
                ) {
                    $ids[] = $value;
                }
            }
        }
        return $ids;
    }
}



if (!function_exists('getUserAbsensQC')) {
    function getUserAbsensQC($lokasi)
    {
        $deparetementFamily = GetDepartementFamily(43);
        // dd($deparetementFamily);

        $depart = DepartementUser::whereIn('department_id', $deparetementFamily)
            ->with('users', 'departement')
            ->get();

        // dd($depart->pluck('user_id'));

        if ($depart->isEmpty()) {

            return [];
        }

        $users = $depart->flatMap(function ($item) use ($lokasi) {
            return $item->users->whereIn('id_jabatan', [1, 8])->whereIn('lokasi_kerja', $lokasi);
            // return $item->users->whereIn('id_jabatan', [1, 8])
            //     ->where('lokasi_kerja', $lokasi);
        });
        // dd($users, $lokasi);
        // dd($users, $lokasi, $depart);
        return collect($users);
        // dd($users);
    }
}
