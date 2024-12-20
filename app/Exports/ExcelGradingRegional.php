<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;


class ExcelGradingRegional implements WithMultipleSheets
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
        // dd($this->data, 'adios');
    }


    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->data as $title => $data) {
            $sheets[] = new class($title, $data) implements FromView, WithTitle {
                private $title;
                private $data;

                public function __construct($title, $data)
                {
                    $this->title = $title;
                    $this->data = $data;
                }

                public function view(): View
                {
                    // dd($this->data);
                    return view('Grading.ExportRegionalExcel', [
                        'data' => $this->data,
                    ]);
                }

                public function title(): string
                {
                    return $this->title;
                }

                public function registerEvents(): array
                {
                    return [
                        AfterSheet::class => function (AfterSheet $event) {
                            // Freeze pane after the 3rd row and 2nd column
                            $event->sheet->freezePane('C4');

                            // This will freeze:
                            // - First 3 rows (your complete header)
                            // - First 2 columns (A and B)
                            // So when scrolling, everything above and to the left of cell C4 will remain fixed
                        },
                    ];
                }
            };
        }
        // dd($sheets);
        return $sheets;
    }
}
