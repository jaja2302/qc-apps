<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\WithEvents;

class Exportqcinspeksiba implements FromView, WithEvents
{

    protected $data;


    public function __construct(array $arrView)
    {
        $this->data = $arrView;
    }
    public function view(): View
    {

        // dd($this->data);
        return view('Qcinspeksi.inpeksiBA_excel', ['data' => $this->data]);
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

                $event->sheet->getStyle('C3:BA3')->applyFromArray($styleHeader);
                $event->sheet->getStyle('A1:B1')->applyFromArray($styleHeader);
                $event->sheet->getStyle('AE2:AG2')->applyFromArray($styleHeader);
                $event->sheet->getStyle('BB2')->applyFromArray($styleHeader);
                $event->sheet->getStyle('BC1:BD1')->applyFromArray($styleHeader);
                $event->sheet->getStyle('W2:X2')->applyFromArray($styleHeader);
                $event->sheet->getStyle('BD')->applyFromArray($styleHeader2);
            },
        ];
    }
}
