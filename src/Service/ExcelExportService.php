<?php
namespace App\Service;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ExcelExportService
{
    public function exportRegistrations(array $registrations, string $summitName): string
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Registrations');

        $headers = ['#', 'Ticket No.', 'First Name', 'Last Name', 'Email', 'Company', 'Job Title', 'Phone', 'Meal', 'Parking', 'Accommodation', 'Status', 'Registered At'];
        foreach ($headers as $i => $header) {
            $col = chr(65 + $i);
            $sheet->setCellValue($col . '1', $header);
            $sheet->getStyle($col . '1')->getFont()->setBold(true);
            $sheet->getStyle($col . '1')->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB('1C3D6E');
            $sheet->getStyle($col . '1')->getFont()->getColor()->setRGB('FFFFFF');
        }

        foreach ($registrations as $i => $reg) {
            $row = $i + 2;
            $sheet->setCellValue('A' . $row, $i + 1);
            $sheet->setCellValue('B' . $row, $reg->getTicketNumber());
            $sheet->setCellValue('C' . $row, $reg->getFirstName());
            $sheet->setCellValue('D' . $row, $reg->getLastName());
            $sheet->setCellValue('E' . $row, $reg->getEmail());
            $sheet->setCellValue('F' . $row, $reg->getCompany());
            $sheet->setCellValue('G' . $row, $reg->getJobTitle());
            $sheet->setCellValue('H' . $row, $reg->getPhone());
            $sheet->setCellValue('I' . $row, $reg->getMealPreference());
            $sheet->setCellValue('J' . $row, $reg->isNeedsParking() ? 'Yes' : 'No');
            $sheet->setCellValue('K' . $row, $reg->isNeedsAccommodation() ? 'Yes' : 'No');
            $sheet->setCellValue('L' . $row, $reg->getStatus());
            $sheet->setCellValue('M' . $row, $reg->getRegisteredAt()->format('d.m.Y H:i'));
        }

        foreach (range('A', 'M') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $tmpFile = tempnam(sys_get_temp_dir(), 'excel_');
        $writer->save($tmpFile);
        return $tmpFile;
    }
}