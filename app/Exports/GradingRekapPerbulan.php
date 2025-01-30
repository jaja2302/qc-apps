<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class GradingRekapPerbulan implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $resultdata;
    protected $resulttotal;

    public function __construct(array $resultdata, array $resulttotal)
    {
        $this->resultdata = $resultdata;
        $this->resulttotal = $resulttotal;
    }
    public function view(): View
    {
        return view('Grading.ExportRekapMillExcel', [
            'resultdata' => $this->resultdata,
            'resulttotal' => $this->resulttotal,
        ]);
    }
}
