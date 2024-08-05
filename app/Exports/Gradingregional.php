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
    protected $tipe;

    use Exportable;
    public function __construct(array $arrView, string $tipe)
    {
        $this->data = $arrView;
        $this->tipe = $tipe;
        // dd($tipe);
    }
    public function view(): View
    {
        return view('Grading.Exportexcel', ['data' => $this->data, 'type' => $this->tipe]);
    }

    public function sheets(): array
    {
        $sheets = [];
        $title = 'Rekap Regional';
        foreach ($this->data as $week => $data) {
            $sheets[$week] = new Gradingregional($data, $this->tipe);
        }

        return $sheets;
    }
}
