<?php

namespace App\Livewire;

use App\Exports\Gradingperhari;
use App\Models\Gradingmill as ModelsGradingmill;
use App\Models\Listmill;
use App\Models\Regional;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\DeletedGradingmill;

class Gradingmill extends Component
{
    public $inputbulan;
    public $regional_id;
    public $inputregional;
    public $regions;
    public $mill_id;
    public $mills = [];
    public $resultdata = [];
    public $resultdate = [];
    public $listmill = [];
    public $modal_data = [];
    public $itemId;
    public $showDeleteModal = false;
    public $deleteId = null;



    public function mount()
    {

        $this->listmill = Listmill::all()->pluck('mill', 'mill');
        // dd($this->listmill);
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
        // dd($result['result']);
        $this->resultdata = $result['result'];
        $this->resultdate = $result['final'];
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

    public function rules()
    {
        return [
            'modal_data.*.jjg_spb' => 'required|numeric|min:0',
            'modal_data.*.jjg_grading' => 'required|numeric|min:0',
            'modal_data.*.overripe' => 'required|numeric|min:0',
            'modal_data.*.empty' => 'required|numeric|min:0',
            'modal_data.*.rotten' => 'required|numeric|min:0',
            'modal_data.*.abn_partheno' => 'required|numeric|min:0',
            'modal_data.*.abn_hard' => 'required|numeric|min:0',
            'modal_data.*.abn_sakit' => 'required|numeric|min:0',
            'modal_data.*.abn_kastrasi' => 'required|numeric|min:0',
            'modal_data.*.tangkai_panjang' => 'required|numeric|min:0',
            'modal_data.*.vcut' => 'required|numeric|min:0',
            'modal_data.*.dirt' => 'required|numeric|min:0',
            'modal_data.*.karung' => 'required|numeric|min:0',
            'modal_data.*.loose_fruit' => 'required|numeric|min:0',
            'modal_data.*.tonase' => 'required|numeric|min:0',
            'modal_data.*.kelas_c' => 'required|numeric|min:0',
            'modal_data.*.kelas_b' => 'required|numeric|min:0',
            'modal_data.*.kelas_a' => 'required|numeric|min:0',
            'modal_data.*.unripe_tanpa_brondol' => 'required|numeric|min:0',
            'modal_data.*.unripe_kurang_brondol' => 'required|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [
            'modal_data.*.*.required' => 'Field tidak boleh kosong (minimal 0)',
            'modal_data.*.*.numeric' => 'Field harus berupa angka',
            'modal_data.*.*.min' => 'Field minimal bernilai 0',
        ];
    }

    public function editgradingmill($id)
    {
        if (can_edit()) {
            $this->validate();
            $data = $this->modal_data[$id];
            $check_status_departement = can_edit_based_departement($data['mill']);

            if ($check_status_departement) {
                try {
                    $data['mill'] = str_replace(' ', '', $data['mill']);

                    $gradingMill = ModelsGradingmill::find($data['id']);
                    if (!$gradingMill) {
                        $this->addError('modal_error', 'Data grading mill tidak ditemukan.');
                        return;
                    }

                    $data['datetime'] = Carbon::parse($data['datetime'])->format('Y-m-d H:i:s');
                    $data['update_by'] = auth()->user()->user_id;
                    $data['status_bot'] = 0;
                    $data['update_date'] = now('Asia/Jakarta')->format('Y-m-d H:i:s');

                    $gradingMill->update($data);
                    $this->dispatch('closeModal');
                    $this->dispatch('refreshComponent');
                } catch (\Illuminate\Validation\ValidationException $e) {
                    // Keep modal open and show validation errors
                    $this->dispatch('showModal');
                    throw $e;
                } catch (\Exception $e) {
                    $this->addError('modal_error', 'Terjadi kesalahan saat mengupdate grading mill: ' . $e->getMessage());
                    $this->dispatch('showModal');
                }
            } else {
                $this->addError('modal_error', 'Anda tidak memiliki izin untuk mengedit grading mill ini.');
                $this->dispatch('showModal');
            }
        } else {
            $this->addError('modal_error', 'Anda tidak memiliki izin untuk mengedit grading mill ini.');
            $this->dispatch('showModal');
        }
    }
    public function confirmDelete($id)
    {
        // dd($id);
        $this->deleteId = $id;
        $this->showDeleteModal = true;
        $this->dispatch('showDeleteModal', $id);
    }
    public function cancelDelete()
    {
        $this->deleteId = null;
        $this->showDeleteModal = false;
        $this->dispatch('hideDeleteModal');
    }
    // Delete method: Delete item from the database
    public function delete($id)
    {
        // dd($id);
        if (can_edit()) {

            try {
                $data =  ModelsGradingmill::find($id);
                if (!$data) {
                    $this->addError('modal_error', 'Data tidak ditemukan.');
                    return;
                }

                $check_status_departement = can_edit_based_departement($data['mill']);
                if ($check_status_departement) {
                    // dd($data->toArray());
                    DeletedGradingmill::create([
                        'estate' => $data['estate'],
                        'afdeling' => $data['afdeling'],
                        'deleted_data' => json_encode($data->toArray()),
                        'deleted_by' => auth()->user()->user_id,
                        'deleted_at' => now(),
                    ]);


                    $data->delete();
                    session()->flash('message', 'Item deleted successfully!');
                    $this->dispatch('refreshComponent');
                } else {
                    $this->addError('modal_error', 'Anda tidak memiliki izin untuk menghapus grading mill ini.');
                    $this->dispatch('showModal');
                }
            } catch (\Exception $e) {
                $this->addError('modal_error', 'Terjadi kesalahan saat menghapus grading mill: ' . $e->getMessage());
                $this->dispatch('showModal');
            }
        } else {
            session()->flash('error', 'Anda tidak memiliki izin untuk menghapus grading mill ini.');
        }
    }
}
