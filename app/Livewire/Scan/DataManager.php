<?php

namespace App\Livewire\Scan;

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
        $this->startDate = Carbon::now()->format('Y-m-d');
        $this->endDate = Carbon::now()->format('Y-m-d');
    }

    public function scanDuplicates()
    {
        $this->isScanning = true;
        $this->duplicateData = [];
        $this->indicationData = [];

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
            $this->dispatch('showAlert', [
                'type' => 'success',
                'message' => 'Scan completed! No duplicate data found.'
            ]);
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
        try {
            $model = $this->getModelByType($type);
            $model::whereIn('id', $ids)->delete();

            $this->dispatch('showAlert', [
                'type' => 'success',
                'message' => 'Data duplikat berhasil dihapus!'
            ]);

            // Refresh scan
            $this->scanDuplicates();
        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'type' => 'error',
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ]);
        }
    }

    public function deleteSingleRecord($type, $id)
    {
        try {
            $model = $this->getModelByType($type);
            $model::find($id)->delete();

            $this->dispatch('showAlert', [
                'type' => 'success',
                'message' => 'Data berhasil dihapus!'
            ]);

            // Refresh scan
            $this->scanDuplicates();
        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'type' => 'error',
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ]);
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
