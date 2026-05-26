<?php
namespace App\Service;

use App\Entity\Registration;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Dompdf\Dompdf;
use Dompdf\Options;
use Twig\Environment;

class PdfTicketService
{
    public function __construct(private Environment $twig) {}

    public function generateTicketPdf(Registration $registration): string
    {
        $qrCode = new QrCode($registration->getTicketNumber());
        $writer = new PngWriter();
        $result = $writer->write($qrCode);
        $qrBase64 = base64_encode($result->getString());

        $html = $this->twig->render('pdf/ticket.html.twig', [
            'registration' => $registration,
            'qrBase64' => $qrBase64,
        ]);

        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output();
    }
}