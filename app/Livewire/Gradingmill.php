<?php

namespace App\Livewire;

use App\Exports\Gradingperhari;
use App\Models\Gradingmill as ModelsGradingmill;

use App\Models\Regional;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class Gradingmill extends Component
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

    public function mount()
    {
        // Load regions except id 5
        $this->regional_id = Regional::query()->where('id', '!=', 5)->get();
        $this->inputbulan = Carbon::now('Asia/Jakarta')->format('mm/dd/yyyy');
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

    // Method to handle 'Show' button click
    public function showResults()
    {
        $this->validate([
            'inputregional' => 'required',  // Ensure region is selected
            'mill_id' => 'required',        // Ensure mill is selected
            'inputbulan' => 'required|date', // Ensure bulan (date) is provided
        ]);
        $reg = $this->inputregional;
        $mill = $this->mill_id;
        $bulan = $this->inputbulan;


        // Debugging values
        // dd($reg, $mill);

        // Your logic to show results
        $result = rekap_estate_mill_perbulan_perhari($bulan, $reg, $mill);
        // dd($result);
        $this->resultdata = $result['result'];
        // dd($this)
        // dd($result);
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
        $mill_perhari = $this->mill_id;
        $data = rekap_estate_mill_perbulan_perhari($date, $reg, $mill_perhari);
        session()->flash('message', 'Excel Berhasil di proses...!');
        return Excel::download(new Gradingperhari($data), 'Excel Grading Regional-' . $date . '-' . 'Bulan-' . $reg . '.xlsx');
    }



    public function render()
    {
        return view('livewire.gradingmill');
    }
    public function formdata($estate, $afdeling)
    {
        $data = ModelsGradingmill::query()
            ->where('estate', $estate)
            ->where('afdeling', $afdeling)
            ->where('datetime', 'like', '%' . $this->inputbulan . '%')
            ->get();
        $this->modal_data = json_decode($data, true);
        // dd($this->modal_data);
        $this->dispatch('showModal');
    }

    public function editgradingmill($id)
    {

        if (can_edit()) {
            $gradingMill = ModelsGradingmill::find($id);

            // Check if the record exists
            if (!$gradingMill) {
                session()->flash('error', 'Grading mill record not found.');
                return;
            }

            try {
                // Debugging: Log the data being used to update
                $data = $this->modal_data[0];
                $data['update_by'] = auth()->user()->user_id;
                $data['update_date'] = now('Asia/Jakarta')->format('Y-m-d H:i:s');

                // dd($data);
                // Update the record with data from modal_data
                $gradingMill->update($data);

                // Flash a success message
                session()->flash('message', 'Grading mill record updated successfully.');

                // Optionally, close the modal or reset the form
                $this->dispatch('closeModal');
                $this->dispatch('refreshComponent');
            } catch (\Exception $e) {
                // Handle the exception
                // dd($e);
                session()->flash('error', 'An error occurred while updating the grading mill: ' . $e->getMessage());

                // Debugging: Log the exception

            }
        }
        session()->flash('error', 'You do not have permission to edit this grading mill.');
    }

    // Delete method: Delete item from the database
    public function delete($id)
    {
        if (can_edit()) {
            ModelsGradingmill::find($id)->delete();

            // Provide feedback to the user
            session()->flash('message', 'Item deleted successfully!');

            // Refresh the component
            $this->dispatch('refreshComponent');
        }
        session()->flash('error', 'You do not have permission to delete this grading mill.');
    }
}
