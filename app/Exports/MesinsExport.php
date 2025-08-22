<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class MesinsExport implements
    FromCollection,
    WithHeadings,
    WithMapping, 
    WithStyles,
    WithCustomStartCell,
    WithTitle,
    WithEvents,
    WithDrawings
{
    protected $data;
    protected $line_name;
    protected $proses_name;
    protected $cetakanKe;
    protected $printedFromUrl;
    private $rowNumber = 0;

    public function __construct($data, $line_name, $proses_name, $cetakanKe, $printedFromUrl)
    {
        $this->data = $data;
        $this->line_name = $line_name;
        $this->proses_name = $proses_name;
        $this->cetakanKe = $cetakanKe;
        $this->printedFromUrl = $printedFromUrl;
    }

    //logo
    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo');
        $drawing->setPath(public_path('assets/logo/Logo-Kalbe-&-BSB_Original.png'));
        $drawing->setCoordinates('F1');
        $drawing->setHeight(85); 
        $drawing->setOffsetX(-75); 
        $drawing->setOffsetY(10);
        return $drawing;
    }

    public function title(): string
    {
        return 'Data Machine';
    }

    public function collection()
    {
        return $this->data;
    }

    public function startCell(): string
    {
        return 'A6';
    }

    public function headings(): array
    {
        return [
            'No',
            'Line',
            'Proses',
            'Kode Mesin',
            'Nama Mesin',
            'Kapasitas',
            'Speed',
            'Jumlah Operator',
            'Keterangan',
        ];
    }

    public function map($mesin): array
    {
        $this->rowNumber++;
        return [
            $this->rowNumber, 
            $mesin->line->name,
            $mesin->proses->pluck('name')->implode(', '),
            $mesin->kodeMesin,
            $mesin->name,
            $mesin->kapasitas . ' ' . $mesin->satuanKapasitas,
            $mesin->speed . ' ' . $mesin->satuanSpeed,
            $mesin->jumlahOperator,
            $mesin->keterangan ?? 'NA',
            '',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:J2');
        
        $sheet->getStyle('A1:J2')
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);

        $sheet->getRowDimension(1)->setRowHeight(30);
        $sheet->getRowDimension(2)->setRowHeight(30);
        $sheet->getRowDimension(6)->setRowHeight(25);

        $sheet->setCellValue('A3', 'Laporan Data Mesin');
        $sheet->mergeCells('A3:J3');
        $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(20);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A4', 'Line: ' . $this->line_name . ' | Proses: ' . $this->proses_name); 
        $sheet->mergeCells('A4:J4'); 
        $sheet->getStyle('A4')->getFont()->setBold(true)->setSize(15);
        $sheet->getStyle('A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('A6:J6')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFD9D9D9');
        $sheet->getStyle('A6:J6')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A6:J6')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('A6:J6')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension(4)->setRowHeight(20);
        $sheet->mergeCells('I6:J6'); 

        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(10);
        $sheet->getColumnDimension('C')->setWidth(30);
        $sheet->getColumnDimension('D')->setWidth(25);
        $sheet->getColumnDimension('E')->setWidth(30);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(20);
        $sheet->getColumnDimension('H')->setWidth(20);
        $sheet->getColumnDimension('I')->setWidth(40); 

        $lastRow = $this->data->count() + 6; 
        $dataRange = 'A7:J' . $lastRow;

        $sheet->getStyle($dataRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle($dataRange)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)->setHorizontal(Alignment::HORIZONTAL_CENTER);

        for ($row = 7; $row <= $lastRow; $row++) {
            $sheet->mergeCells('I' . $row . ':J' . $row);
        }

    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $style = '&I&11'; 

                $left_footer = sprintf(
                    "Dicetak dari halaman %s\nCetakan ke-%d pada %s oleh %s",
                    $this->printedFromUrl,
                    $this->cetakanKe,
                    date('d M Y H:i:s'), 
                    auth()->user() ? auth()->user()->email : 'System'
                );

                $right_footer = 'Halaman &P dari &N';

                $full_footer = $style . '&L' . $left_footer . '&R' . $right_footer;

                $event->sheet->getHeaderFooter()->setOddFooter($full_footer);
                $event->sheet->getHeaderFooter()->setEvenFooter($full_footer);
            },
        ];
    }
}