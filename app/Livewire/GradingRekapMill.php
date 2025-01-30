<?php

namespace App\Livewire;

use App\Exports\GradingRekapPerbulan;
use App\Models\Listmill;
use App\Models\Regional;
use Carbon\Carbon;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;


class GradingRekapMill extends Component
{

    public $listmill;
    public $regional_id;
    public $inputbulan;
    public $mill_id;
    public $mills = [];
    public $resultdata = [];
    public $resultdate = [];
    public $inputregional;
    public $resulttotal = [];
    public function mount()
    {

        $this->listmill = Listmill::all()->pluck('mill', 'mill');
        // dd($this->listmill);
        $this->regional_id = Regional::query()->where('id', '!=', 5)->get();
        $this->inputbulan = Carbon::now('Asia/Jakarta')->format('Y-m');
    }
    // Method triggered on region change
    public function getdatamill($regionalId)
    {
        $data = Regional::where('id', $regionalId)->with('Mill')->first();

        // Populate mills if the regional has any
        $this->mills = $data ? $data->Mill : [];

        // Reset the mill_id to force user selection
        $this->mill_id = null;
    }

    public function showResults()
    {

        $reg = $this->inputregional;
        $mill = $this->mill_id;
        $bulan = $this->inputbulan;
        // dd($bulan);

        // Debugging values
        // dd($reg, $mill);

        // dd($reg, $mill, $bulan);

        // Your logic to show results
        $result = rekap_estate_mill_perbulan_perhari($bulan, $reg, $mill, 'perbulan');
        // dd($result);
        // dd($result['result']);
        $this->resultdata = $result['result']['data_regional'];
        $this->resulttotal = $result['result']['data_mill'];
        // dd($this->resulttotal);
        // dd($this->resultdata);
        // $this->resultdate = $result['final'];
    }

    public function exportData()
    {
        session()->flash('message', 'Mohon menunggu excel sedang di proses...!');

        // Validate the input fields
        $this->validate([
            'inputregional' => 'required',
            'inputbulan' => 'required',
            'mill_id' => 'required',
        ]);
        $date = $this->inputbulan;
        $reg = $this->inputregional;
        $mill_perbulan = $this->mill_id;
        $data = rekap_estate_mill_perbulan_perhari($date, $reg, $mill_perbulan, 'perbulan');
        $resultdata = $data['result']['data_regional'];
        $resulttotal = $data['result']['data_mill'];


        session()->flash('message', 'Excel Berhasil di proses...!');
        return Excel::download(new GradingRekapPerbulan($resultdata, $resulttotal), 'Excel Grading Rekap Mill-' . $date . '-' . 'Bulan-' . $reg . '.xlsx');
    }


    public function render()
    {
        return view('livewire.grading-rekap-mill');
    }
}
