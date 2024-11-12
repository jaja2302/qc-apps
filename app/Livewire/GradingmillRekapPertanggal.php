<?php

namespace App\Livewire;

use App\Exports\Gradingregional;
use Livewire\Component;

use App\Models\Regional;
use Carbon\Carbon;
use App\Models\Gradingmill as ModelsGradingmill;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class GradingmillRekapPertanggal extends Component
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
    public function mount()
    {
        // Load regions except id 5he
        $this->regional_id = Regional::query()->where('id', '!=', 5)->get();
        $this->inputbulan = Carbon::now('Asia/Jakarta')->format('Y-m');

        // dd($this->inputbulan);
    }
    public function render()
    {
        return view('livewire.gradingmill-rekap-pertanggal');
    }

    public function showResults()
    {
        // dd('showResults');
        $this->validate([
            'inputregional' => 'required',  // Ensure region is selected     // Ensure mill is selected
            'inputbulan' => 'required|date', // Ensure bulan (date) is provided
        ]);
        $reg = $this->inputregional;
        $bulan = $this->inputbulan;
        $result = getdatamildetailpertanggal($bulan, $reg);
        $this->resultdata = $result;
    }
}
