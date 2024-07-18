<?php


namespace App\Http\Controllers\incpectcomponent;

use Illuminate\Http\Request;


use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use DateTime;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;

require_once(app_path('helpers.php'));

use Illuminate\Support\Facades\Storage;


class makemapsController extends Controller
{

    public function plotBlok(Request $request)
    {
        $est = $request->get('est');
        $regData = $request->get('regData');
        $date = $request->get('date');

        $result = score_by_maps($est, $regData, $date);
        // dd($plotBlokAll);
        echo json_encode($result);
    }

    public function getMapsdetail(Request $request)
    {
        $est = $request->input('est');
        $afd = $request->input('afd');
        $date = $request->input('Tanggal');

        // dd($est, $afd, $date);
        $queryPlotEst = DB::connection('mysql2')->table('estate_plot')
            ->select("estate_plot.*")
            ->where('est', $est)
            ->get();
        // $queryPlotEst = $queryPlotEst->groupBy(['estate', 'afdeling']);
        $queryPlotEst = json_decode($queryPlotEst, true);

        // dd($queryPlotEst);

        $convertedCoords = [];
        foreach ($queryPlotEst as $coord) {
            $convertedCoords[] = [$coord['lat'], $coord['lon']];
        }

        $afd = $request->input('afd');
        $queryAfd = DB::connection('mysql2')->table('afdeling')
            ->select(
                'afdeling.id',
                'afdeling.nama',
                'estate.est'
            ) //buat mengambil data di estate db dan willayah db
            ->join('estate', 'estate.id', '=', 'afdeling.estate') //kemudian di join untuk mengambil est perwilayah
            ->where('est', '=', $est)
            ->where('afdeling.nama', '=', $afd)
            ->get();
        $queryAfd = json_decode($queryAfd, true);

        $id = $queryAfd[0]['id'];

        $queryBlokMA = DB::connection('mysql2')->table('mutu_ancak_new')
            ->select('mutu_ancak_new.*', 'mutu_ancak_new.blok as nama_blok')
            ->whereDate('mutu_ancak_new.datetime', $date)
            ->where('estate', $est)
            ->where('afdeling', $afd)
            ->orderBy('mutu_ancak_new.datetime', 'desc')
            ->groupBy('nama_blok')
            ->pluck('blok');
        $queryBlokMA = json_decode($queryBlokMA, true);


        $queryBlokMB = DB::connection('mysql2')->table('mutu_buah')
            ->select('mutu_buah.*', 'mutu_buah.blok as nama_blok')
            ->whereDate('mutu_buah.datetime', $date)
            ->where('estate', $est)
            ->where('afdeling', $afd)
            ->orderBy('mutu_buah.datetime', 'desc')
            ->groupBy('nama_blok')
            ->pluck('blok');

        $queryBlokMB = json_decode($queryBlokMB, true);
        $queryBlokMT = DB::connection('mysql2')->table('mutu_transport')
            ->select('mutu_transport.*', 'mutu_transport.blok as nama_blok')
            ->whereDate('mutu_transport.datetime', $date)
            ->where('estate', $est)
            ->where('afdeling', $afd)
            ->orderBy('mutu_transport.datetime', 'desc')
            ->groupBy('nama_blok')
            ->pluck('blok');

        $queryBlokMT = json_decode($queryBlokMT, true);

        //merge all blok untuk mendapatkan semua blok ma mb mt visit hari tersebut
        $allBlokSidakRaw = array_merge($queryBlokMA, $queryBlokMB, $queryBlokMT);

        //array unique mengambil eliminasi blok yg sama
        $allBlokSidakRaw = array_unique($allBlokSidakRaw);

        //sisipkan 0 setelah digit pertama


        $blokSidak = array();
        foreach ($allBlokSidakRaw as $value) {
            $length = strlen($value);
            $blokSidak[] = substr($value, 0, $length - 3);
            $modifiedStr2  = substr($value, 0, $length - 2);
            $blokSidak[] = substr($value, 0, $length - 2);
            $blokSidak[] =  substr_replace($modifiedStr2, "0", 1, 0);
        }

        // dd($allBlokRaw, $allBlok);
        $blokSidakResult = DB::connection('mysql2')
            ->table('blok')
            ->select('blok.nama as nama_blok_visit')
            ->where('afdeling', $id)
            ->whereIn('nama', $blokSidak)
            ->groupBy('nama_blok_visit')
            ->pluck('nama_blok_visit');

        $queryBlok = DB::connection('mysql2')->table('blok')
            ->select("blok.*")
            ->where('afdeling', '=', $id)
            ->get();
        $queryBlok = json_decode($queryBlok, true);

        $bloks_afd = array_reduce($queryBlok, function ($carry, $item) {
            $carry[$item['nama']][] = $item;
            return $carry;
        }, []);

        $bloks_afds = [];
        foreach ($bloks_afd as $blok => $coords) {
            foreach ($coords as $coord) {
                $bloks_afds[] = [
                    'blok' => $blok,
                    'lat' => $coord['lat'],
                    'lon' => $coord['lon'],
                ];
            }
        }

        $plotBlokAll = [];
        foreach ($bloks_afd as $key => $coord) {
            foreach ($coord as $key2 => $value) {
                $plotBlokAll[$key][] = [$value['lat'], $value['lon']];
            }
        }

        // Sort the coordinates in ascending order based on the first value


        //  dd($plotBlokAll);

        $queryTrans = DB::connection('mysql2')->table("mutu_transport")
            ->select("mutu_transport.*", "estate.wil")
            ->join('estate', 'estate.est', '=', 'mutu_transport.estate')
            ->where('mutu_transport.estate', $est)
            ->where('mutu_transport.afdeling', $afd)
            ->where('datetime', 'like', '%' . $date . '%')
            ->where('mutu_transport.afdeling', '!=', 'Pla')
            ->get();
        $queryTrans = json_decode($queryTrans, true);

        // dd($queryTrans);

        $queryBuah = DB::connection('mysql2')->table("mutu_buah")
            ->select("mutu_buah.*", "estate.wil")
            ->join('estate', 'estate.est', '=', 'mutu_buah.estate')
            ->where('mutu_buah.estate', $est)
            ->where('mutu_buah.afdeling', $afd)
            ->where('datetime', 'like', '%' . $date . '%')
            ->where('mutu_buah.afdeling', '!=', 'Pla')
            ->get();
        $queryBuah = json_decode($queryBuah, true);

        $groupedTrans = array_reduce($queryTrans, function ($carry, $item) {
            $carry[$item['blok']][] = $item;
            return $carry;
        }, []);

        $groupedBuah = array_reduce($queryBuah, function ($carry, $item) {
            $carry[$item['blok']][] = $item;
            return $carry;
        }, []);

        // dd($groupedBuah);
        $buah_plot = [];

        foreach ($groupedBuah as $blok => $coords) {
            foreach ($coords as $coord) {
                $datetime = $coord['datetime'];
                $time = date('H:i:s', strtotime($datetime));

                $maps = $coord['app_version'];
                if (strpos($maps, ';GA') !== false) {
                    $maps = 'GPS Akurat';
                } elseif (strpos($maps, ';GL') !== false) {
                    $maps = 'GPS Liar';
                }

                $buah_plot[$blok][] = [
                    'blok' => $blok,
                    'lat' => $coord['lat'],
                    'lon' => $coord['lon'],
                    'foto_temuan' => $coord['foto_temuan'],
                    'komentar' => $coord['komentar'],
                    'tph_baris' => $coord['tph_baris'],
                    'status_panen' => $coord['status_panen'],
                    'jumlah_jjg' => $coord['jumlah_jjg'],
                    'bmt' => $coord['bmt'],
                    'bmk' => $coord['bmk'],
                    'overripe' => $coord['overripe'],
                    'empty_bunch' => $coord['empty_bunch'],
                    'abnormal' => $coord['abnormal'],
                    'vcut' => $coord['vcut'],
                    'alas_br' => $coord['alas_br'],
                    'maps' => $maps,
                    'time' => $time,
                ];
            }
        }




        // dd($buah_plot);
        $trans_plot = [];
        foreach ($groupedTrans as $blok => $coords) {
            foreach ($coords as $coord) {
                $datetime = $coord['datetime'];
                $time = date('H:i:s', strtotime($datetime));
                $maps = $coord['app_version'];
                if (strpos($maps, ';GA') !== false) {
                    $maps = 'GPS Akurat';
                } elseif (strpos($maps, ';GL') !== false) {
                    $maps = 'GPS Liar';
                }

                $trans_plot[$blok][] = [
                    'blok' => $blok,
                    'lat' => $coord['lat'],
                    'lon' => $coord['lon'],
                    'foto_temuan' => $coord['foto_temuan'],
                    'foto_fu' => $coord['foto_fu'],
                    'komentar' => $coord['komentar'],
                    'status_panen' => $coord['status_panen'],
                    'luas_blok' => $coord['luas_blok'],
                    'bt' => $coord['bt'],
                    'Rst' => $coord['rst'],
                    'maps' => $maps,
                    'time' => $time,
                ];
            }
        }



        $queryancak = DB::connection('mysql2')->table("mutu_ancak_new")
            ->select("mutu_ancak_new.*", "estate.wil")
            ->join('estate', 'estate.est', '=', 'mutu_ancak_new.estate')
            ->where('mutu_ancak_new.estate', $est)
            ->where('mutu_ancak_new.afdeling', $afd)
            ->where('datetime', 'like', '%' . $date . '%')
            ->where('mutu_ancak_new.afdeling', '!=', 'Pla')
            ->get();
        $queryancak = json_decode($queryancak, true);

        $groupedAncak = array_reduce($queryancak, function ($carry, $item) {
            $carry[$item['blok']][] = $item;
            return $carry;
        }, []);

        $plotLine = array();
        foreach ($queryancak as $key => $value) {
            $plotLine[] =  '[' . $value['lat_awal'] . ',' . $value['lon_awal'] . '],[' . $value['lat_akhir'] . ',' . $value['lon_akhir'] . ']';
        }

        // dd($plotLine);   
        $queryancakFL = DB::connection('mysql2')->table("follow_up_ma")
            ->select("follow_up_ma.*", "estate.wil")
            ->join('estate', 'estate.est', '=', 'follow_up_ma.estate')
            ->where('follow_up_ma.estate', $est)
            ->where('follow_up_ma.afdeling', $afd)
            ->where('waktu_temuan', 'like', '%' . $date . '%')
            ->where('follow_up_ma.afdeling', '!=', 'Pla')
            ->get();
        $queryancakFL = json_decode($queryancakFL, true);

        $groupedAncakFL = array_reduce($queryancakFL, function ($carry, $item) {
            $carry[$item['blok']][] = $item;
            return $carry;
        }, []);
        // dd($groupedAncak['T01505'],$groupedAncakFL['T01505']);
        // dd($ancak_plot);
        $ancak_fa = [];
        foreach ($groupedAncakFL as $blok => $coords) {
            foreach ($coords as $coord) {
                // dd($coord);
                $datetime = $coord['waktu_temuan'];
                $time = date('H:i:s', strtotime($datetime));
                $ancak_fa[] = [
                    'blok' => $blok,
                    'estate' => $coord['estate'],
                    'afdeling' => $coord['afdeling'],
                    'br1' => $coord['br1'],
                    'br2' => $coord['br2'],
                    'jalur_masuk' => $coord['jalur_masuk'],
                    'foto_temuan1' => $coord['foto_temuan1'],
                    'foto_temuan2' => $coord['foto_temuan2'],
                    'foto_fu1' => $coord['foto_fu1'],
                    'foto_fu2' => $coord['foto_fu2'],
                    'komentar' => $coord['komentar'],
                    'lat' => $coord['lat'],
                    'lon' => $coord['lon'],
                    'time' => $time,
                ];
            }
        }

        $groupedArray = [];

        foreach ($ancak_fa as $item) {
            $blok = $item['blok'];
            if (!isset($groupedArray[$blok])) {
                $groupedArray[$blok] = [];
            }
            $groupedArray[$blok][] = $item;
        }
        // dd($groupedArray,$ancak_fa);


        $ancak_plot = [];

        foreach ($groupedAncak as $blok => $coords) {
            foreach ($coords as $coord) {
                $matchingAncakFa = [];

                foreach ($ancak_fa as $key => $value) {
                    if ($coord['blok'] == $value['blok'] && $coord['br1'] == $value['br1'] && $coord['br2'] == $value['br2'] && $coord['jalur_masuk'] == $value['jalur_masuk']) {
                        $matchingAncakFa[] = $value;
                    }
                }
                // $maps = $coord['app_version'];
                // if (strpos($maps, ';GA') !== false) {
                //     $maps = 'GPS Akurat';
                // } elseif (strpos($maps, ';GL') !== false) {
                //     $maps = 'GPS Liar';
                // }

                $vers = $coord['app_version'];
                $parts = explode(';', $vers);

                $defaultparts = '{"awal":"GO","akhir":"GO"}';
                $version = $parts[3] ?? $defaultparts;

                if (strpos($version, 'awal')) {
                    if (strpos($version, 'awal":"GL') !== false && strpos($version, 'akhir":"GA') !== false) {
                        $maps = 'GPS Awal Liar : GPS Akhir Akurat';
                    } else  if (strpos($version, 'awal":"GL') !== false && strpos($version, 'akhir":"GL') !== false) {
                        $maps = 'GPS Awal Liar : GPS Akhir Liar';
                    } else  if (strpos($version, 'awal":"GA') !== false && strpos($version, 'akhir":"GL"') !== false) {
                        $maps = 'GPS Awal Akurat : GPS Akhir Liar';
                    } else  if (strpos($version, 'awal":"GA') !== false && strpos($version, 'akhir":"GA"') !== false) {
                        $maps = 'GPS Awal Akurat : GPS Akhir Akurat';
                    } else if (strpos($version, 'awal":"GA') !== false && strpos($version, 'akhir":"G') !== false) {
                        $maps = 'GPS Awal Akurat : GPS Akhir Uknown';
                    } else if (strpos($version, 'awal":"GL') !== false && strpos($version, 'akhir":"G') !== false) {
                        $maps = 'GPS Awal Akurat : GPS Akhir Uknown';
                    } else if (strpos($version, 'awal":"GO') !== false && strpos($version, 'akhir":"GO') !== false) {
                        $maps = 'GPS Awal Uknown : GPS Akhir Uknown';
                    } else {
                        $maps = 'GPS Uknown';
                    }
                } else {
                    if (strpos($coord['app_version'], ';GA') !== false) {
                        $maps = 'GPS Akurat';
                    } elseif (strpos($coord['app_version'], ';GL') !== false) {
                        $maps = 'GPS Liar';
                    } else {
                        $maps = 'GPS Awal Uknown : GPS Akhir Uknown';
                    }
                }
                $ancak_fa_item = [
                    'blok' => $blok,
                    'estate' => $coord['estate'],
                    'afdeling' => $coord['afdeling'],
                    'br1' => $coord['br1'],
                    'br2' => $coord['br2'],
                    'jalur_masuk' => $coord['jalur_masuk'],
                    'ket' => 'Lokasi akhir',
                    'lat_' => $coord['lat_akhir'],
                    'lon_' => $coord['lon_akhir'],
                    'luas_blok' => $coord['luas_blok'],
                    'sph' => $coord['sph'],
                    'sample' => $coord['sample'],
                    'pokok_kuning' => $coord['pokok_kuning'],
                    'piringan_semak' => $coord['piringan_semak'],
                    'underpruning' => $coord['underpruning'],
                    'overpruning' => $coord['overpruning'],
                    'jjg' => $coord['jjg'],
                    'brtp' => $coord['brtp'],
                    'brtk' => $coord['brtk'],
                    'brtgl' => $coord['brtgl'],
                    'bhts' => $coord['bhts'],
                    'bhtm1' => $coord['bhtm1'],
                    'bhtm2' => $coord['bhtm2'],
                    'bhtm3' => $coord['bhtm3'],
                    'maps' => $maps,
                    'ps' => $coord['ps'],
                    'sp' => $coord['sp'],
                    'time' => date('H:i:s', strtotime($coord['datetime'])),
                ];

                if (!empty($matchingAncakFa)) {
                    $foto_temuan1 = [];
                    $foto_temuan2 = [];
                    $foto_fu1 = [];
                    $foto_fu2 = [];
                    $lat_ancak_fa = [];
                    $lon_ancak_fa = [];
                    $komentar = [];

                    foreach ($matchingAncakFa as $match) {
                        $foto_temuan1[] = 'foto_temuan1:' . (!empty($match['foto_temuan1']) ? $match['foto_temuan1'] : '') . ',foto_temuan2:' . (!empty($match['foto_temuan2']) ? $match['foto_temuan2'] : ' ') . ',foto_fu1:' . (!empty($match['foto_fu1']) ? $match['foto_fu1'] : ' ') . ',foto_fu2:' . (!empty($match['foto_fu2']) ? $match['foto_fu2'] : ' ') . ',lat:' . $match['lat'] . ',lon:' . $match['lon'] . ',komentar:' . $match['komentar'];

                        $foto_fu1[] = 'foto_fu1:' . (!empty($match['foto_fu1']) ? $match['foto_fu1'] : '') . ',foto_fu2:' . (!empty($match['foto_fu2']) ? $match['foto_fu2'] : ' ') . ',lat:' . $match['lat'] . ',lon:' . $match['lon'];
                    }


                    $ancak_fa_item['foto_temuan'] = $foto_temuan1;

                    // $ancak_fa_item['foto_fu'] = $foto_fu1;

                }



                $ancak_plot[] = $ancak_fa_item;
            }
        }

        // dd($plotLine);



        // dd($trans_plot,$buah_plot,$ancak_plot); 

        return response()->json([
            'plot_line' => $plotLine,
            'coords' => $convertedCoords,
            'plot_blok_all' => $plotBlokAll,
            'trans_plot' => $trans_plot,
            'buah_plot' => $buah_plot,
            'ancak_plot' => $ancak_plot,
            'blok_sidak' => $blokSidakResult
        ]);
    }




    public function downloadMaptahun(Request $request)
    {
        $imgData = $request->input('imgData');
        $estData = $request->input('estData');
        $regData = $request->input('regData');
        $decodedImage = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imgData));

        // dd($estData);
        // Generate a unique filename for the image, e.g., using a timestamp
        $filename = 'img_map_' . time() . '.png';

        // Define the public directory path
        $publicMapPath = public_path('img_map');
        $publicResultPath = public_path('img_result');

        // Check if the "img_map" directory exists, if not, create it
        if (!file_exists($publicMapPath)) {
            mkdir($publicMapPath, 0755, true);
        }

        // Save the image to the "img_map" directory
        file_put_contents($publicMapPath . '/' . $filename, $decodedImage);

        $files = File::files($publicMapPath);

        foreach ($files as $file) {
            $imageInfo = getimagesize($file);

            if ($imageInfo !== false) {
                list($width, $height, $type) = $imageInfo;

                $originalImage = imagecreatefromstring(file_get_contents($file));

                $minX = $width;
                $minY = $height;
                $maxX = 0;
                $maxY = 0;

                for ($x = 0; $x < $width; $x++) {
                    for ($y = 0; $y < $height; $y++) {
                        $pixelColor = imagecolorat($originalImage, $x, $y);
                        $color = imagecolorsforindex($originalImage, $pixelColor);
                        if ($color['red'] !== 0 || $color['green'] !== 0 || $color['blue'] !== 0) {
                            $minX = min($minX, $x);
                            $minY = min($minY, $y);
                            $maxX = max($maxX, $x);
                            $maxY = max($maxY, $y);
                        }
                    }
                }
                $cropWidth = $maxX - $minX + 1;
                $cropHeight = $maxY - $minY + 1;
                $croppedImage = imagecrop($originalImage, ['x' => $minX, 'y' => $minY, 'width' => $cropWidth, 'height' => $cropHeight]);

                $outputFile = $publicResultPath . '/' . basename($file);
                imagejpeg($croppedImage, $outputFile);

                imagedestroy($originalImage);
                imagedestroy($croppedImage);

                // Delete the original image from "img_map"
                unlink($file);
            }
        }


        // Provide the public URL for the saved image in "img_result"
        $publicUrl = asset('img_result/' . $filename);

        return response()->json(['message' => 'Image saved successfully', 'filename' => $filename, 'est' => $estData]);
    }




    public function pdfPage($filename, $est)
    {
        $url = $filename;
        $estData = $est;

        $pdf = PDF::loadView('pdfimgInspeksi', compact('url', 'estData'));

        $customPaper = array(360, 360, 360, 360);
        $pdf->set_paper('A2', 'landscape');

        $filename = 'Data Map Inspeksi per Blok' . '.pdf';

        return $pdf->stream($filename);
    }
}
