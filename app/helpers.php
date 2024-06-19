<?php
// Important functions

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
