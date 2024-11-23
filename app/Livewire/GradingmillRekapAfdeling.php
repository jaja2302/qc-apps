<?php

namespace App\Livewire;

use App\Exports\Gradingregional;
use Livewire\Component;

use App\Models\Regional;
use Carbon\Carbon;
use App\Models\Gradingmill as ModelsGradingmill;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class GradingmillRekapAfdeling extends Component
{
    public $inputbulan;
    public $regional_id;
    public $inputregional;
    public $regions;
    public $mill_id;
    public $mills = [];
    public $resultdata = [];
    public $modal_data = [];
    public $itemId;
    public $selectedDate = null;
    public $availableDates = [];
    public $selectedEstate;
    public $selectedAfdeling;
    public $isDownloading = false;
    public $isDownloadingImage = [];
    public $selectedId;
    public $isresendingWhatsapp = [];
    public $lastResendTime = [];

    protected $listeners = ['openModal'];

    public function mount()
    {
        // Load regions except id 5
        $this->regional_id = Regional::query()->where('id', '!=', 5)->get();
        $this->inputbulan = Carbon::now('Asia/Jakarta')->format('Y-m');

        // dd($this->inputbulan);
    }
    public function showResults()
    {
        $this->validate([
            'inputregional' => 'required',  // Ensure region is selected     // Ensure mill is selected
            'inputbulan' => 'required|date', // Ensure bulan (date) is provided
        ]);
        $reg = $this->inputregional;
        $bulan = $this->inputbulan;



        $type = 'perafdeling';
        $result = getdatamill($bulan, $reg, $type);

        // dd($result);
        $this->resultdata = $result;
        // dd($this)
        // dd($result);
    }

    public function openModal($estate, $afdeling)
    {
        $this->selectedEstate = $estate;
        $this->selectedAfdeling = $afdeling;

        // Get available dates for the selected estate and afdeling
        $this->availableDates = ModelsGradingmill::query()
            ->selectRaw('DATE(datetime) as date')
            ->where('estate', $this->selectedEstate)
            ->where('afdeling', $this->selectedAfdeling)
            ->where('datetime', 'like', '%' . $this->inputbulan . '%')
            ->distinct()
            ->get()
            ->toArray();

        $this->dispatch('show-modal');
    }

    // This will automatically run when selectedDate changes
    public function updatedSelectedDate($value)
    {
        if (!$value) {
            $this->modal_data = [];
            return;
        }

        // $estate = $request->input('estate');
        // // $afd = $request->input('afd');
        // // $date = $request->input('date');
        $data = getdatamildetail($this->selectedEstate, $this->selectedAfdeling, $value);
        $this->modal_data = $data['data_perhari'];
        // dd($this->modal_data);
        // $this->modal_data = ModelsGradingmill::query()
        //     ->where('estate', $this->selectedEstate)
        //     ->where('afdeling', $this->selectedAfdeling)
        //     ->whereDate('datetime', $value)
        //     ->get()
        //     ->toArray();
    }

    public function render()
    {
        return view('livewire.gradingmill-rekap-afdeling')->with([
            'modal_data' => $this->modal_data
        ]);
    }

    // resending bot whatsapp
    public function resendWhatsapp($id)
    {
        $this->isresendingWhatsapp[$id] = true;
        $data = ModelsGradingmill::find($id);

        if (!$data) {
            $this->isresendingWhatsapp[$id] = false;
            session()->flash('error', 'Data tidak ditemukan');
            return;
        }

        // Check if last resend time exists and if 10 minutes have passed
        $lastResend = $data->last_resend_time;
        $now = now();

        if ($lastResend && $now->diffInMinutes($lastResend) < 10) {
            $minutesLeft = 10 - $now->diffInMinutes($lastResend);
            $this->isresendingWhatsapp[$id] = false;
            session()->flash('error', "Mohon tunggu {$minutesLeft} menit sebelum mengirim ulang.");
            return;
        }

        try {
            // Your resending logic here
            $data->status_bot = 0;
            $data->last_resend_time = $now;
            $data->save();

            session()->flash('success', 'Bot WhatsApp berhasil dikirim ulang');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal mengirim ulang bot WhatsApp');
            \Log::error('WhatsApp resend failed: ' . $e->getMessage());
        }

        $this->isresendingWhatsapp[$id] = false;
    }

    public function confirmResend($id)
    {
        $this->selectedId = $id;
        $this->dispatch('confirm-resend');
    }

    public function performResend()
    {
        $this->resendWhatsapp($this->selectedId);
    }

    protected function getListeners()
    {
        return array_merge(parent::getListeners(), [
            'perform-resend' => 'performResend',
            'openModal' => 'openModal'
        ]);
    }

    // download image
    public function downloadImage($id)
    {
        $this->isDownloadingImage[$id] = true;
        $successCount = 0;
        $errorCount = 0;

        try {
            $data = ModelsGradingmill::find($id);
            if (!$data) {
                $this->isDownloadingImage[$id] = false;
                session()->flash('error', 'Record not found');
                return;
            }

            $baseLink = 'https://mobilepro.srs-ssms.com/storage/app/public/qc/grading_mill/';
            $foto = array_map('trim', explode(',', trim($data['foto_temuan'], '[]')));

            // Filter out empty entries
            $foto = array_filter($foto, fn($item) => !empty(trim($item)));

            if (empty($foto)) {
                $this->isDownloadingImage[$id] = false;
                session()->flash('error', 'No images found for this record');
                return;
            }

            // Ensure storage directory exists
            $tempDir = storage_path('app/public/temp');
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            // Create a zip archive
            $zip = new \ZipArchive();
            $zipName = 'grading_images_' . time() . '.zip';
            $zipPath = $tempDir . '/' . $zipName;

            if ($zip->open($zipPath, \ZipArchive::CREATE) === TRUE) {
                $client = new \GuzzleHttp\Client([
                    'verify' => false,
                    'timeout' => 10, // 10 seconds timeout
                    'connect_timeout' => 5
                ]);

                foreach ($foto as $image) {
                    $imageUrl = $baseLink . trim($image);
                    $fileName = basename(trim($image));

                    try {
                        // Download image content
                        $response = $client->get($imageUrl);

                        if ($response->getStatusCode() === 200) {
                            $content = $response->getBody();
                            $contentLength = $content->getSize();

                            // Skip if file size is 0
                            if ($contentLength === 0) {
                                \Log::warning("Image skipped (empty file): {$imageUrl}");
                                $errorCount++;
                                continue;
                            }

                            // Add file to zip
                            $zip->addFromString($fileName, $content);
                            $successCount++;
                        }
                    } catch (\GuzzleHttp\Exception\RequestException $e) {
                        // Handle connection timeout or 404 errors
                        \Log::warning("Image download failed: {$imageUrl} - " . $e->getMessage());
                        $errorCount++;
                        continue;
                    } catch (\Exception $e) {
                        // Handle other errors
                        \Log::error("Unexpected error: {$imageUrl} - " . $e->getMessage());
                        $errorCount++;
                        continue;
                    }
                }

                $zip->close();

                $this->isDownloadingImage[$id] = false;

                // Only return zip if at least one image was successfully downloaded
                if ($successCount > 0) {
                    $message = "Downloaded {$successCount} image" . ($successCount !== 1 ? 's' : '');
                    if ($errorCount > 0) {
                        $message .= " ({$errorCount} failed)";
                    }
                    session()->flash('success', $message);

                    return response()->download($zipPath)->deleteFileAfterSend(true);
                } else {
                    // Clean up empty zip file
                    if (file_exists($zipPath)) {
                        unlink($zipPath);
                    }
                    session()->flash('error', 'No images could be downloaded');
                    return;
                }
            }

            $this->isDownloadingImage[$id] = false;
            session()->flash('error', 'Failed to create zip archive');
        } catch (\Exception $e) {
            \Log::error('Download process failed: ' . $e->getMessage());
            $this->isDownloadingImage[$id] = false;
            session()->flash('error', 'Download process failed');
        }
    }
    public function downloadPDF($id)
    {
        $this->isDownloading = true;

        $data = ModelsGradingmill::find($id);
        if (!$data) {
            $this->isDownloading = false;
            return;
        }

        $foto = array_map('trim', explode(',', trim($data['foto_temuan'], '[]')));
        // dd($foto);
        // Calculate ratios
        $jumlah_janjang_grading = $data['jjg_grading'];
        $jjg_ratio = 100 / $jumlah_janjang_grading;
        $tonase_ratio = 100 / $data['tonase'];
        $brondol_0 = $data['unripe_tanpa_brondol'];
        $brondol_less = $data['unripe_kurang_brondol'];
        $vcut = $data['vcut'];
        $not_vcut = $jumlah_janjang_grading - $vcut;
        $overripe = $data['overripe'];
        $empty_bunch = $data['empty'];
        $rotten_bunch = $data['rotten'];
        // Calculate bunch metrics
        $abnormal = $data['abn_partheno'] + $data['abn_hard'] +
            $data['abn_sakit'] + $data['abn_kastrasi'];

        $unripe = $data['unripe_tanpa_brondol'] + $data['unripe_kurang_brondol'];
        $ripeness = $jumlah_janjang_grading - ($data['overripe'] + $data['empty'] +
            $data['rotten'] + $abnormal + $unripe);

        // Calculate percentages
        $percentage_ripeness = $ripeness * $jjg_ratio;
        $percentage_unripe = $unripe * $jjg_ratio;
        $percentage_overripe = $data['overripe'] * $jjg_ratio;
        $percentage_empty_bunch = $data['empty'] * $jjg_ratio;
        $percentage_rotten_bunch = $data['rotten'] * $jjg_ratio;
        $percentage_abnormal = $abnormal * $jjg_ratio;
        $percentage_tangkai_panjang = $data['tangkai_panjang'] * $jjg_ratio;

        // Calculate weight-based metrics
        $loose_fruit_kg = $data['loose_fruit'] * $tonase_ratio;
        $dirt_kg = $data['dirt'] * $tonase_ratio;

        // Process bunch difference
        $jumlah_selisih_janjang = $data['jjg_spb'] - $jumlah_janjang_grading;
        $percentage_selisih_janjang = ($jumlah_selisih_janjang / $data['jjg_spb']) * 100;
        $percentage_ripeness = ($ripeness / $jumlah_janjang_grading) * 100;
        $percentage_unripe = ($unripe / $jumlah_janjang_grading) * 100;
        $percentage_brondol_0 = ($brondol_0 / $jumlah_janjang_grading) * 100;
        $percentage_brondol_less = ($brondol_less / $jumlah_janjang_grading) * 100;
        $percentage_overripe = ($overripe / $jumlah_janjang_grading) * 100;
        $percentage_empty_bunch = ($empty_bunch / $jumlah_janjang_grading) * 100;
        $percentage_vcut = ($vcut / $jumlah_janjang_grading) * 100;
        $percentage_not_vcut = ($not_vcut / $jumlah_janjang_grading) * 100;
        $percentage_rotten_bunch = ($rotten_bunch / $jumlah_janjang_grading) * 100;
        $percentage_abnormal = ($abnormal / $jumlah_janjang_grading) * 100;
        $percentage_tangkai_panjang = ($data['tangkai_panjang'] / $jumlah_janjang_grading) * 100;
        // Process pemanen data
        $no_pemanen = json_decode($data['no_pemanen'], true) ?? [];
        [$resultKurangBrondol, $resultTanpaBrondol] = $this->processPemanenData($no_pemanen);

        // Format date
        $date = Carbon::parse($data['datetime'])->locale('id');
        $formattedDate = $date->isoFormat('dddd') . ', ' . $date->format('d-m-Y');

        // Process app version
        list($appvers, $os_version, $phone_version) = explode(';', $data['app_version']) + ['-', '-', '-'];

        // Calculate class percentages
        $total_kelas = $data['kelas_a'] + $data['kelas_b'] + $data['kelas_c'];
        $persentage_kelas_a = $total_kelas > 0 ? ($data['kelas_a'] / $total_kelas) * 100 : 0;
        $persentage_kelas_b = $total_kelas > 0 ? ($data['kelas_b'] / $total_kelas) * 100 : 0;
        $persentage_kelas_c = $total_kelas > 0 ? ($data['kelas_c'] / $total_kelas) * 100 : 0;


        $list_blok = str_replace(['[', ']'], '', $data['blok']);
        $tanpaBrondol = [];
        $datakurang_brondol = [];
        // dd($no_pemanen);
        foreach ($no_pemanen as $keys1 => $values1) {
            $get_pemanen = isset($values1['a']) ? $values1['a'] : (isset($values1['noPemanen']) ? $values1['noPemanen'] : null);
            $get_kurangBrondol = isset($values1['b']) ? $values1['b'] : (isset($values1['kurangBrondol']) ? $values1['kurangBrondol'] : 0);
            $get_tanpaBrondol = isset($values1['c']) ? $values1['c'] : (isset($values1['tanpaBrondol']) ? $values1['tanpaBrondol'] : 0);

            if ($get_kurangBrondol != 0) {
                $datakurang_brondol['kurangBrondol_list'][] = [
                    'no_pemanen' => ($get_pemanen == 999) ? 'X' : $get_pemanen,
                    'kurangBrondol' => $get_kurangBrondol,
                ];
            }

            if ($get_tanpaBrondol != 0) {
                $tanpaBrondol['tanpaBrondol_list'][] = [
                    'no_pemanen' => ($get_pemanen == 999) ? 'X' : $get_pemanen,
                    'tanpaBrondol' => $get_tanpaBrondol,
                ];
            }
        }

        // dd($tanpaBrondol);

        if ($datakurang_brondol == []) {
            $resultKurangBrondol = '-';
        } else {
            $resultKurangBrondol = implode(',', array_map(function ($item) {
                return $item['no_pemanen'] . '(' . $item['kurangBrondol'] . ')';
            }, $datakurang_brondol['kurangBrondol_list']));
        }

        if ($tanpaBrondol == []) {
            $resultTanpaBrondol = '-';
        } else {
            $resultTanpaBrondol = implode(',', array_map(function ($item) {
                return $item['no_pemanen'] . '(' . $item['tanpaBrondol'] . ')';
            }, $tanpaBrondol['tanpaBrondol_list']));
        }
        $kelas_a = $data['kelas_a'];
        $kelas_b = $data['kelas_b'];
        $kelas_c = $data['kelas_c'];
        // Generate PDF and collage
        $dataakhir = [
            'id' => $data['id'],
            'estate' => $data['estate'],
            'afdeling' => $data['afdeling'],
            'mill' => $data['mill'],
            'Tanggal' => $formattedDate,
            'list_blok' => $list_blok,
            'waktu_grading' => $date->format('H:i:s'),
            'jjg_grading' => $data['jjg_grading'],
            'no_plat' => $data['no_plat'],
            'supir' => $data['driver'],
            'jjg_spb' => $data['jjg_spb'],
            'tonase' => $data['tonase'],
            'abn_partheno' => $data['abn_partheno'],
            'abn_partheno_percen' => round(($data['abn_partheno'] / $jumlah_janjang_grading) * 100, 2),
            'abn_hard' => $data['abn_hard'],
            'abn_hard_percen' => round(($data['abn_hard'] / $jumlah_janjang_grading) * 100, 2),
            'abn_sakit' =>  $data['abn_sakit'],
            'abn_sakit_percen' => round(($data['abn_sakit'] / $jumlah_janjang_grading) * 100, 2),
            'abn_kastrasi' =>  $data['abn_kastrasi'],
            'abn_kastrasi_percen' => round(($data['abn_kastrasi'] / $jumlah_janjang_grading) * 100, 2),
            'bjr' => round($data['tonase'] / $data['jjg_spb'], 2),
            'jjg_selisih' => $jumlah_selisih_janjang,
            'persentase_selisih' => round($percentage_selisih_janjang, 2),
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
            'loose_fruit' => $data['loose_fruit'],
            'persentase_lose_fruit' => round($loose_fruit_kg, 2),
            'Dirt' => $data['dirt'],
            'persentase' => round($dirt_kg, 2),
            'foto' => $foto,
            'stalk' =>    $data['tangkai_panjang'],
            'persentase_stalk' => round($percentage_tangkai_panjang, 2),
            'pemanen_list_tanpabrondol' => $tanpaBrondol,
            'pemanen_list_kurangbrondol' => $datakurang_brondol,
            'resultKurangBrondol' => $resultKurangBrondol,
            'resultTanpaBrondol' => $resultTanpaBrondol,
            'vcut' => $vcut,
            'percentage_vcut' => round($percentage_vcut, 2),
            'not_vcut' => $not_vcut,
            'percentage_not_vcut' => round($percentage_not_vcut, 2),
            'kelas_a' => $kelas_a,
            'kelas_b' => $kelas_b,
            'kelas_c' => $kelas_c,
            'percentage_kelas_a' => round($persentage_kelas_a, 2),
            'percentage_kelas_b' => round($persentage_kelas_b, 2),
            'percentage_kelas_c' =>  round($persentage_kelas_c, 2),
            'tanggal_titel' => $date->format('d M Y'),
            'appvers' => $appvers,
            'os_version' => $os_version,
            'phone_version' => $phone_version,
        ];

        // Generate PDF

        // Generate unique names for each record
        $pdfName = 'grading_mill_' . $data['id'] . '_' . time() . '.pdf';

        $pdf = Pdf::loadView('Grading.Exportgradingmill', [
            'data' => $dataakhir
        ]);

        $this->isDownloading = false;

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $pdfName);
    }
    private function processPemanenData($no_pemanen)
    {
        $kurangBrondol = [];
        $tanpaBrondol = [];

        foreach ($no_pemanen as $item) {
            if (isset($item['type'])) {
                if ($item['type'] === 'kurang_brondol') {
                    $kurangBrondol[] = $item['value'];
                } elseif ($item['type'] === 'tanpa_brondol') {
                    $tanpaBrondol[] = $item['value'];
                }
            }
        }

        return [
            implode(', ', array_unique($kurangBrondol)),
            implode(', ', array_unique($tanpaBrondol))
        ];
    }

    public function exportData()
    {
        session()->flash('message', 'Mohon menunggu, excel sedang diproses...');

        // Validate the input fields
        $this->validate([
            'inputregional' => 'required',
            'inputbulan' => 'required',
        ]);
        // $date = $this->inputbulan;
        // $reg = $this->inputregional;
        // $mill_perhari = $this->mill_id;
        // $data = rekap_estate_mill_perbulan_perhari($date, $reg, $mill_perhari);
        // session()->flash('message', 'Excel Berhasil di proses...!');
        // return Excel::download(new Gradingperhari($data), 'Excel Grading Regional-' . $date . '-' . 'Bulan-' . $reg . '.xlsx');

        $type = 'perafdeling';
        $result = getdatamill($this->inputbulan, $this->inputregional, $type);
        // dd($data);
        return Excel::download(new Gradingregional($result, $type), 'Excel Grading Regional-' . $this->inputbulan . '-' . 'Bulan-' . $this->inputregional . '.xlsx');
    }
}
