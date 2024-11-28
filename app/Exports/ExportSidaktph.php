<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\WithEvents;
use Nette\Utils\DateTime;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportSidaktph implements WithMultipleSheets
{


    protected $dataPerWeek;

    use Exportable;

    public function __construct(array $dataPerWeek)
    {
        $this->dataPerWeek = $dataPerWeek;
    }


    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->dataPerWeek as $sheetType => $data) {
            $sheets[] = new class($sheetType, $data) implements FromView, WithTitle, ShouldAutoSize, WithEvents {
                private $sheetType;
                private $data;

                public function __construct($sheetType, $data)
                {
                    $this->sheetType = $sheetType;
                    $this->data = $data;
                }

                public function registerEvents(): array
                {
                    return [
                        AfterSheet::class => function (AfterSheet $event) {
                            // Apply borders to the entire data range
                            $lastRow = $event->sheet->getHighestRow();
                            $lastColumn = $event->sheet->getHighestColumn();

                            $event->sheet->getStyle('A1:' . $lastColumn . $lastRow)->applyFromArray([
                                'borders' => [
                                    'allBorders' => [
                                        'borderStyle' => 'thin',
                                        'color' => ['rgb' => '000000']
                                    ],
                                ],
                            ]);

                            // Center align headers
                            $event->sheet->getStyle('A1:' . $lastColumn . '3')->applyFromArray([
                                'alignment' => [
                                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                                    'vertical' => Alignment::VERTICAL_CENTER,
                                    'wrapText' => true,
                                ],
                            ]);

                            // Freeze panes - both vertical (column B) and horizontal (row 4)
                            $event->sheet->getDelegate()->freezePane('C4');
                        },
                    ];
                }

                public function view(): View
                {
                    // dd($this->data);
                    return view('sidaktph.sidaktphexcel', [
                        'data' => $this->data
                    ]);
                }

                public function title(): string
                {
                    return $this->sheetType;
                }
            };
        }
        // dd($sheets);
        return $sheets;
    }
}
