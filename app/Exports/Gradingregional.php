<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Exportable;

class Gradingregional implements FromView, WithMultipleSheets
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $data;

    use Exportable;
    public function __construct(array $arrView)
    {
        $this->data = $arrView;
    }
    public function view(): View
    {

        // dd($this->data);
        return view('Grading.Exportexcel', ['data' => $this->data, 'type' => 'rekap_regional']);
    }
    public function sheets(): array
    {
        $sheets = [];
        $title = 'Rekap Regional';
        foreach ($this->data as $week => $data) {
            $sheets[$week] = new Gradingregional($data);
        }

        return $sheets;
    }
}
