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
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;

class Gradingpertanggal implements FromView, WithEvents
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
        $this->tipe = 'pertanggal';
    }
    public function view(): View
    {
        // dd($this->data);
        return view('Grading.Exportexcel_pertanggal', ['data' => $this->data, 'type' => $this->tipe]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Get the sheet
                $sheet = $event->sheet;

                // Get the highest row and column
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();

                // Apply center alignment, text wrapping, and borders to all cells
                $sheet->getDelegate()->getStyle('A1:' . $highestColumn . $highestRow)
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER)
                    ->setWrapText(true);

                $sheet->getDelegate()->getStyle('A1:' . $highestColumn . $highestRow)
                    ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

                // Freeze panes
                // Freeze the first 4 columns (A to D - Tanggal, Estate, Afdeling, Mill)
                // and first 3 rows (header rows)
                $sheet->getDelegate()->freezePane('E4');
            }
        ];
    }
}
