<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExcelGradingMill implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('Grading.ExportRegionalExcel', [
            'data' => $this->data,
        ]);
    }
}
