<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class Gradingperhari implements FromView, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $data;
    protected $tipe;

    use Exportable;
    public function __construct(array $arrView)
    {
        $this->data = $arrView;
        // dd($this->data);
    }
    public function view(): View
    {
        return view('Grading.Exportexcel_perhari', ['data' => $this->data]);
    }
}
