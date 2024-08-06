<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Illuminate\Support\Facades\Http;

class Findingissue implements FromView, ShouldAutoSize, WithEvents, WithDrawings, WithColumnWidths
{
    protected $data;
    protected $downloadedImages = [];

    public function __construct(array $arrView)
    {
        $this->data = $this->flattenArray($arrView['newResult']);
        // dd($this->data);
        $this->downloadImages($this->data);
        // dd($this->data, $arrView['newResult']);
    }

    public function view(): View
    {
        return view('Qcinspeksi.Findingexcel', ['data' => $this->data]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $data = $this->data;
                $data = count($data);
                // dd($data);
                for ($i = 4; $i <= $data + 3; $i++) {
                    $event->sheet->getDelegate()->getRowDimension($i)->setRowHeight(114);
                }
            },
        ];
    }

    public function columnWidths(): array
    {
        return [
            'F' => 38,
            'G' => 38,
            'H' => 38,
            'I' => 38,
            'J' => 45,
        ];
    }

    public function drawings()
    {
        $drawings = [];

        foreach ($this->data as $index => $row) {
            $rowIndex = $index + 4;

            // Mutu Ancak
            if ($row['category'] == 'mutu_ancak') {
                $this->addDrawingsFromRow($drawings, $row, 'foto_temuan1', 'Foto Temuan 1', 'F', $rowIndex);
                $this->addDrawingsFromRow($drawings, $row, 'foto_temuan2', 'Foto Temuan 2', 'G', $rowIndex);
                $this->addDrawingsFromRow($drawings, $row, 'foto_fu1', 'Foto Follow Up 1', 'H', $rowIndex);
                $this->addDrawingsFromRow($drawings, $row, 'foto_fu2', 'Foto Follow Up 2', 'J', $rowIndex);
            }

            // Mutu Transport
            if ($row['category'] == 'mutu_transport') {
                $this->addDrawingsFromRow($drawings, $row, 'foto_temuan', 'Foto Temuan Transport', 'F', $rowIndex, true);
                $this->addDrawingsFromRow($drawings, $row, 'foto_fu', 'Foto Follow Up Transport', 'H', $rowIndex, true);
            }

            // Mutu Buah
            if ($row['category'] == 'mutu_buah') {
                $this->addDrawingsFromRow($drawings, $row, 'foto_temuan', 'Foto Temuan Buah', 'F', $rowIndex, true);
            }
        }
        // dd($drawings);
        return $drawings;
    }

    private function addDrawingsFromRow(&$drawings, $row, $field, $name, $startColumn, $rowIndex, $splitImages = false)
    {
        if (!empty($row[$field])) {
            $paths = $splitImages ? explode(';', $row[$field]) : [$row[$field]];
            $column = $startColumn;
            foreach ($paths as $path) {
                $path = trim($path); // Remove any surrounding whitespace
                if (!empty($path)) {
                    $drawing = new Drawing();
                    $drawing->setName($name);
                    $drawing->setDescription($name);
                    $drawing->setPath(public_path('qc/inspeksi_ma/' . $path));
                    $drawing->setHeight(150);
                    $drawing->setCoordinates($column . $rowIndex);
                    $drawings[] = $drawing;
                    $column++; // Move to the next column for the next image
                    if ($column == 'I') {
                        $column = 'J'; // Skip column 'I'
                    }
                }
            }
        }
    }


    private function flattenArray($array)
    {
        $flattenedArray = [];

        foreach ($array as $key => $value) {
            foreach ($value as $blok => $blokData) {
                foreach ($blokData as $type => $entries) {
                    foreach ($entries as $entry) {
                        // Add the category to the entry
                        $entry['category'] = $type;
                        $flattenedArray[] = $entry;
                    }
                }
            }
        }

        return $flattenedArray;
    }

    private function downloadImages(&$data)
    {
        foreach ($data as &$row) {
            // mutu_ancak images
            if (!empty($row['foto_temuan1'])) {
                $imageNames = explode(';', $row['foto_temuan1']);
                $localPaths = [];
                foreach ($imageNames as $imageName) {
                    $url = $this->getImageUrl($row['category'], trim($imageName));
                    $localPath = $this->getLocalPath(trim($imageName));
                    $this->downloadImage($url, $localPath);
                    // $localPaths[] = $localPath;
                    if (file_exists($localPath)) {
                        $localPaths[] = $localPath;
                    }
                }
                $row['foto_temuan1_local'] = implode(';', $localPaths);
            }
            if (!empty($row['foto_temuan2'])) {
                $imageNames = explode(';', $row['foto_temuan2']);
                $localPaths = [];
                foreach ($imageNames as $imageName) {
                    $url = $this->getImageUrl($row['category'], trim($imageName));
                    $localPath = $this->getLocalPath(trim($imageName));
                    $this->downloadImage($url, $localPath);
                    if (file_exists($localPath)) {
                        $localPaths[] = $localPath;
                    }
                }
                $row['foto_temuan2_local'] = implode(';', $localPaths);
            }
            if (!empty($row['foto_fu1'])) {
                $imageNames = explode(';', $row['foto_fu1']);
                $localPaths = [];
                foreach ($imageNames as $imageName) {
                    $url = $this->getImageUrl($row['category'], trim($imageName));
                    $localPath = $this->getLocalPath(trim($imageName));
                    $this->downloadImage($url, $localPath);
                    if (file_exists($localPath)) {
                        $localPaths[] = $localPath;
                    }
                }
                $row['foto_fu1_local'] = implode(';', $localPaths);
            }
            if (!empty($row['foto_fu2'])) {
                $imageNames = explode(';', $row['foto_fu2']);
                $localPaths = [];
                foreach ($imageNames as $imageName) {
                    $url = $this->getImageUrl($row['category'], trim($imageName));
                    $localPath = $this->getLocalPath(trim($imageName));
                    $this->downloadImage($url, $localPath);
                    if (file_exists($localPath)) {
                        $localPaths[] = $localPath;
                    }
                }
                $row['foto_fu2_local'] = implode(';', $localPaths);
            }

            // mutu_transport images
            if (!empty($row['foto_temuan'])) {
                $imageNames = explode(';', $row['foto_temuan']);
                $localPaths = [];
                foreach ($imageNames as $imageName) {
                    $url = $this->getImageUrl($row['category'], trim($imageName));
                    $localPath = $this->getLocalPath(trim($imageName));
                    $this->downloadImage($url, $localPath);
                    if (file_exists($localPath)) {
                        $localPaths[] = $localPath;
                    }
                }
                $row['foto_temuan_local'] = implode(';', $localPaths);
            }
            if (!empty($row['foto_fu'])) {
                $imageNames = explode(';', $row['foto_fu']);
                $localPaths = [];
                foreach ($imageNames as $imageName) {
                    $url = $this->getImageUrl($row['category'], trim($imageName));
                    $localPath = $this->getLocalPath(trim($imageName));
                    $this->downloadImage($url, $localPath);
                    if (file_exists($localPath)) {
                        $localPaths[] = $localPath;
                    }
                }
                $row['foto_fu_local'] = implode(';', $localPaths);
            }

            // mutu_buah images
            if (!empty($row['foto_temuan'])) {
                $imageNames = explode(';', $row['foto_temuan']);
                $localPaths = [];
                foreach ($imageNames as $imageName) {
                    $url = $this->getImageUrl($row['category'], trim($imageName));
                    $localPath = $this->getLocalPath(trim($imageName));
                    $this->downloadImage($url, $localPath);
                    $localPaths[] = $localPath;
                }
                $row['foto_temuan_local'] = implode(';', $localPaths);
            }
        }
    }

    private function getImageUrl($category, $imageName)
    {
        $baseUrl = 'https://mobilepro.srs-ssms.com/storage/app/public/qc/';

        switch ($category) {
            case 'mutu_ancak':
                return $baseUrl . 'inspeksi_ma/' . $imageName;
            case 'mutu_transport':
                return $baseUrl . 'inspeksi_mt/' . $imageName;
            case 'mutu_buah':
                return $baseUrl . 'inspeksi_mb/' . $imageName;
            default:
                return $baseUrl . 'inspeksi_ma/' . $imageName; // Default to 'mutu_ancak'
        }
    }

    private function getLocalPath($imageName)
    {
        $publicPath = public_path('qc/inspeksi_ma/');
        if (!is_dir($publicPath)) {
            mkdir($publicPath, 0777, true);
        }
        return $publicPath . $imageName;
    }

    private function downloadImage($url, $path)
    {
        try {
            $response = Http::get($url);
            if ($response->status() == 200) {
                file_put_contents($path, $response->body());
                $this->downloadedImages[] = $path;
            }
        } catch (\Exception $e) {
            // Handle the exception if needed
        }
    }

    public function __destruct()
    {
        // Delete downloaded images
        foreach ($this->downloadedImages as $image) {
            if (file_exists($image)) {
                unlink($image);
            }
        }
    }
}
