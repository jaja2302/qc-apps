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
    protected $result;
    protected $final;
    protected $tanggal;
    protected $mill_name;
    protected $pt_name;

    use Exportable;
    public function __construct(array $arrView)
    {
        //         array:6 [▼ // resources\views/Grading/Exportexcel_perhari.blade.php
        //   "status" => "success"
        //   "result" => array:2 [▶]
        //   "final" => array:49 [▶]
        //   "pt_name" => "PT.KSA"
        //   "mill_name" => "PKS Natai Baru"
        //   "tanggal" => "Tuesday, 17 December 2024"
        // ]
        $this->result = $arrView['result'];
        $this->final = $arrView['final'];
        $this->tanggal = $arrView['tanggal'];
        $this->mill_name = $arrView['mill_name'];
        $this->pt_name = $arrView['pt_name'];
        // dd($this->data);
    }
    public function view(): View
    {

        return view('Grading.Exportexcel_perhari', [
            'result' => $this->result,
            'final' => $this->final,
            'tanggal' => $this->tanggal,
            'mill_name' => $this->mill_name,
            'pt_name' => $this->pt_name,
        ]);
    }
}
