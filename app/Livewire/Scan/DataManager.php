<?php

namespace App\Livewire\Scan;

use App\Models\HistoryDelete;
use Livewire\Component;
use App\Models\mutu_ancak;
use App\Models\mutu_buah;
use App\Models\mutu_transport;
use App\Models\QcGudang;
use App\Models\SidakMutuBuah;
use App\Models\SidakTph;
use Carbon\Carbon;

class DataManager extends Component
{
    public $isScanning = false;
    public $scanType = 'today';
    public $startDate;
    public $endDate;
    public $duplicateData = [];
    public $indicationData = [];
    public $detailRecord = null;
    public $detailRecords = null;

    public function mount()
    {
        if (!user_qc()) {
            return redirect()->intended(route('dashboard_inspeksi'));
        }

        $this->startDate = Carbon::now()->format('Y-m-d');
        $this->endDate = Carbon::now()->format('Y-m-d');
    }

    public function scanDuplicates()
    {
        $this->isScanning = true;
        $this->duplicateData = [];
        $this->indicationData = [];

        try {
            // Get date range based on scan type
            $dateRange = $this->getDateRange();

            // Scan Mutu Ancak
            $ancakData = mutu_ancak::whereBetween('datetime', $dateRange)->get();
            $this->findDuplicates($ancakData, 'Mutu Ancak');

            // dd($ancakData);
            // Scan Mutu Buah
            $buahData = mutu_buah::whereBetween('datetime', $dateRange)->get();
            $this->findDuplicates($buahData, 'Mutu Buah');

            // Scan Mutu Transport
            $transportData = mutu_transport::whereBetween('datetime', $dateRange)->get();
            $this->findDuplicates($transportData, 'Mutu Transport');

            // Scan Sidak TPH
            $sidaktphData = SidakTph::whereBetween('datetime', $dateRange)->get();
            $this->findDuplicates($sidaktphData, 'Sidak TPH');
            // Scan Sidak mutu buah
            $sidakmutubuahData = SidakMutuBuah::whereBetween('datetime', $dateRange)->get();
            $this->findDuplicates($sidakmutubuahData, 'Sidak Mutubuah');
            // Scan Sidak mutu buah
            $qcgudangData = QcGudang::whereBetween('tanggal', $dateRange)->get();
            $this->findDuplicates($qcgudangData, 'QC Gudang');

            $this->isScanning = false;

            if (empty($this->duplicateData) && empty($this->indicationData)) {
                session()->flash('message', 'Pemindaian selesai! Tidak ditemukan data duplikat.');
                session()->flash('type', 'success');
            } else {
                session()->flash('message', 'Ditemukan beberapa data duplikat! Silakan periksa hasilnya.');
                session()->flash('type', 'warning');
            }
        } catch (\Exception $e) {
            $this->isScanning = false;
            session()->flash('message', 'Gagal melakukan pemindaian: ' . $e->getMessage());
            session()->flash('type', 'error');
        }
    }

    private function getModelByType($type)
    {
        return match ($type) {
            'Mutu Ancak' => mutu_ancak::class,
            'Mutu Buah' => mutu_buah::class,
            'Mutu Transport' => mutu_transport::class,
            'Sidak TPH' => SidakTph::class,
            'Sidak Mutubuah' => SidakMutuBuah::class,
            'QC Gudang' => QcGudang::class,
            default => throw new \Exception('Tipe data tidak valid'),
        };
    }

    private function getDateRange()
    {
        if ($this->scanType === 'today') {
            $start = Carbon::today()->startOfDay();
            $end = Carbon::today()->endOfDay();
        } else {
            $start = Carbon::parse($this->startDate)->startOfDay();
            $end = Carbon::parse($this->endDate)->endOfDay();
        }

        return [$start, $end];
    }

    private function findDuplicates($data, $type)
    {
        // For exact duplicates (all columns)
        $exactDuplicates = $data->groupBy(function ($item) {
            $values = $item->toArray();
            // Remove id and timestamps from comparison
            unset($values['id'], $values['created_at'], $values['updated_at']);
            return json_encode($values);
        })->filter(function ($group) {
            return $group->count() > 1;
        });

        // For indication duplicates (excluding datetime, lat, lon, and specific columns)
        $indicationDuplicates = $data->groupBy(function ($item) {
            $values = $item->toArray();
            // Daftar kolom yang akan dihapus jika ada
            $columnsToUnset = ['id', 'datetime', 'lat', 'lon'];

            // Hapus kolom hanya jika mereka ada
            foreach ($columnsToUnset as $column) {
                if (array_key_exists($column, $values)) {
                    unset($values[$column]);
                }
            }
            return json_encode($values);
        })->filter(function ($group) {
            return $group->count() > 1;
        });

        if ($exactDuplicates->count() > 0) {
            $this->duplicateData[$type] = $exactDuplicates->values()->toArray();
        }

        if ($indicationDuplicates->count() > 0) {
            $this->indicationData[$type] = $indicationDuplicates->values()->toArray();
        }
    }

    public function deleteAllDuplicates($type, $ids)
    {
        if (can_edit()) {
            try {
                $model = $this->getModelByType($type);

                // Ambil semua data
                $allData = $model::whereIn('id', $ids)->get();

                // Simpan satu data (data pertama) dan hapus sisanya
                $keepData = $allData->first();
                $dataToDelete = $allData->slice(1);

                // Simpan ke history_delete untuk data yang akan dihapus
                foreach ($dataToDelete as $data) {
                    HistoryDelete::create([
                        'tabel' => $type,
                        'data' => json_encode($data->toArray()),
                        'delete_by' => auth()->id(),
                        'delete_date' => now()
                    ]);
                }

                // Hapus data duplikat (kecuali data pertama)
                $model::whereIn('id', $dataToDelete->pluck('id'))->delete();

                session()->flash('message', count($dataToDelete) . ' data duplikat berhasil dihapus dari ' . $type . ', menyisakan 1 data asli!');
                session()->flash('type', 'success');

                // Refresh scan
                $this->scanDuplicates();
            } catch (\Exception $e) {
                session()->flash('message', 'Gagal menghapus data: ' . $e->getMessage());
                session()->flash('type', 'error');
            }
        }
    }

    public function deleteSingleRecord($type, $id)
    {
        if (can_edit()) {
            try {
                $model = $this->getModelByType($type);
                // Ambil data sebelum dihapus
                $dataToDelete = $model::find($id)->toArray();

                // Simpan ke history_delete
                HistoryDelete::create([
                    'tabel' => $type,
                    'data' => json_encode($dataToDelete),
                    'delete_by' => auth()->id(),
                    'delete_date' => now()
                ]);

                // Hapus data
                $model::find($id)->delete();

                session()->flash('message', '1 data dari ' . $type . ' berhasil dihapus dan disimpan ke history!');
                session()->flash('type', 'success');

                // Refresh scan
                $this->scanDuplicates();
            } catch (\Exception $e) {
                session()->flash('message', 'Gagal menghapus data: ' . $e->getMessage());
                session()->flash('type', 'error');
            }
        }
    }

    public function showDetail($type, $id)
    {
        $model = $this->getModelByType($type);
        $this->detailRecord = $model::find($id)->toArray();
    }

    public function showGroupDetail($type, $ids)
    {
        $model = $this->getModelByType($type);
        $this->detailRecords = $model::whereIn('id', $ids)->get()->toArray();
    }

    public function closeDetail()
    {
        $this->detailRecord = null;
        $this->detailRecords = null;
    }

    public function render()
    {
        return view('livewire.scan.data-manager')
            ->layout('components.layout.app');
    }
}
