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
use App\Models\Gradingmill;
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

            // Scan Grading mill
            $gradingData = Gradingmill::whereBetween('datetime', $dateRange)->get();
            $this->findDuplicates($gradingData, 'Grading Mill');

            if (empty($this->duplicateData) && empty($this->indicationData)) {
                session()->flash('message', 'Pemindaian selesai! Tidak ditemukan data duplikat.');
                session()->flash('type', 'success');
            } else {
                session()->flash('message', 'Ditemukan beberapa data duplikat! Silakan periksa hasilnya.');
                session()->flash('type', 'warning');
            }
        } catch (\Exception $e) {
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
            'Grading Mill' => Gradingmill::class,
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
            $columnsToUnset = ['id', 'datetime', 'lat', 'lon', 'no_pemanen', 'unripe_tanpa_brondol', 'unripe_kurang_brondol', 'foto_temuan'];

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
        if (!can_edit()) {
            return;
        }

        try {
            $model = $this->getModelByType($type);
            // Cek dulu apakah semua data masih ada
            $existingIds = $model::whereIn('id', $ids)->pluck('id')->toArray();
            $missingIds = array_diff($ids, $existingIds);

            if (!empty($missingIds)) {
                session()->flash('message', 'Beberapa data sudah terhapus oleh pengguna lain. Halaman akan diperbarui.');
                session()->flash('type', 'warning');
                $this->scanDuplicates(); // Refresh data
                return;
            }

            $allData = $model::whereIn('id', $ids)->select('id')->get();
            $dataToDelete = $allData->slice(1);

            if ($dataToDelete->isEmpty()) {
                session()->flash('message', 'Tidak ada data yang perlu dihapus!');
                session()->flash('type', 'warning');
                return;
            }

            // Batch insert untuk history delete
            $historyData = [];
            foreach ($dataToDelete as $data) {
                $historyData[] = [
                    'tabel' => $type,
                    'data' => json_encode(['id' => $data->id]),
                    'delete_by' => auth()->id(),
                    'delete_date' => now()
                ];
            }
            HistoryDelete::insert($historyData);

            // Hapus data dalam satu query dengan pengecekan ulang
            $deletedCount = $model::whereIn('id', $dataToDelete->pluck('id'))->delete();

            if ($deletedCount < count($dataToDelete)) {
                session()->flash('message', 'Beberapa data tidak dapat dihapus karena sudah diubah. Halaman akan diperbarui.');
                session()->flash('type', 'warning');
                $this->scanDuplicates();
                return;
            }

            session()->flash('message', $deletedCount . ' data duplikat berhasil dihapus dari ' . $type . ', menyisakan 1 data asli!');
            session()->flash('type', 'success');

            $this->updateDuplicateData($type, $ids);
        } catch (\Exception $e) {
            session()->flash('message', 'Gagal menghapus data: ' . $e->getMessage());
            session()->flash('type', 'error');
        }
    }

    public function deleteSingleRecord($type, $id)
    {
        if (!can_edit()) {
            return;
        }

        try {
            $model = $this->getModelByType($type);

            // Cek apakah data masih ada dengan lock
            $record = $model::select('id')->where('id', $id)->lockForUpdate()->first();

            if (!$record) {
                session()->flash('message', 'Data sudah terhapus oleh pengguna lain!');
                session()->flash('type', 'warning');
                $this->updateDuplicateData($type, [$id]);
                return;
            }

            HistoryDelete::create([
                'tabel' => $type,
                'data' => json_encode(['id' => $record->id]),
                'delete_by' => auth()->id(),
                'delete_date' => now()
            ]);

            // Coba hapus dengan pengecekan ulang
            $deleted = $model::where('id', $id)->delete();

            if (!$deleted) {
                session()->flash('message', 'Data tidak dapat dihapus karena sudah diubah oleh pengguna lain!');
                session()->flash('type', 'warning');
                $this->scanDuplicates();
                return;
            }

            session()->flash('message', '1 data dari ' . $type . ' berhasil dihapus dan disimpan ke history!');
            session()->flash('type', 'success');

            $this->updateDuplicateData($type, [$id]);
        } catch (\Exception $e) {
            session()->flash('message', 'Gagal menghapus data: ' . $e->getMessage());
            session()->flash('type', 'error');
        }
    }

    // Tambahkan method baru untuk update data secara lokal
    private function updateDuplicateData($type, $deletedIds)
    {
        // Update duplicateData
        if (isset($this->duplicateData[$type])) {
            foreach ($this->duplicateData[$type] as $groupIndex => $group) {
                // Filter out deleted records
                $updatedGroup = array_filter($group, function ($record) use ($deletedIds) {
                    return !in_array($record['id'], $deletedIds);
                });

                if (count($updatedGroup) <= 1) {
                    // Remove group if only 1 or no records left
                    unset($this->duplicateData[$type][$groupIndex]);
                } else {
                    $this->duplicateData[$type][$groupIndex] = array_values($updatedGroup);
                }
            }

            if (empty($this->duplicateData[$type])) {
                unset($this->duplicateData[$type]);
            }
        }

        // Update indicationData
        if (isset($this->indicationData[$type])) {
            foreach ($this->indicationData[$type] as $groupIndex => $group) {
                // Filter out deleted records
                $updatedGroup = array_filter($group, function ($record) use ($deletedIds) {
                    return !in_array($record['id'], $deletedIds);
                });

                if (count($updatedGroup) <= 1) {
                    // Remove group if only 1 or no records left
                    unset($this->indicationData[$type][$groupIndex]);
                } else {
                    $this->indicationData[$type][$groupIndex] = array_values($updatedGroup);
                }
            }

            if (empty($this->indicationData[$type])) {
                unset($this->indicationData[$type]);
            }
        }
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
