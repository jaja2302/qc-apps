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
use Maatwebsite\Excel\Concerns\WithTitle;

class Gradingregional implements WithMultipleSheets
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
        // dd($this->data);
    }


    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->data as $region => $data) {
            $sheets[] = new class($region, $data, $this->tipe) implements FromView, WithTitle {
                private $region;
                private $data;
                private $tipe;

                public function __construct($region, $data, $tipe)
                {
                    $this->region = $region;
                    $this->data = $data;
                    $this->tipe = $tipe;
                }

                public function view(): View
                {
                    // dd($this->region);
                    return view('Grading.Exportexcel', [
                        'data' => $this->data,
                        'type' => $this->tipe
                    ]);
                }

                public function title(): string
                {
                    return $this->region;
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

        return $sheets;
    }
}
