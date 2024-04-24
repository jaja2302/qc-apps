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

class ExportSidaktph implements FromView, WithEvents, WithMultipleSheets
{


    protected $dataPerWeek;

    use Exportable;

    public function __construct(array $dataPerWeek)
    {
        $this->dataPerWeek = $dataPerWeek;
    }
    public function view(): View
    {


        // dd($this->dataPerWeek);

        return view('sidaktph.sidaktphexcel', ['data' => [$this->dataPerWeek]]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $styleHeader = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => 'thin',
                            'color' => ['rgb' => '808080']
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                ];
                $styleHeader2 = [
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                ];

                $event->sheet->getStyle('C3:CD3')->applyFromArray($styleHeader2);
                $event->sheet->getStyle('CF')->applyFromArray($styleHeader2);

                $event->sheet->getDelegate()->freezePane('B2');
            },
        ];
    }

    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->dataPerWeek as $week => $data) {
            $sheets[$week] = new ExportSidaktph($data);
        }

        return $sheets;
    }
}
