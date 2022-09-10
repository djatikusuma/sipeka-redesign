<?php

namespace App\Http\Controllers;

use App\Models\TrxEvent;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function print_certificate($id)
    {
        //Get Data Event
        $event = TrxEvent::with('presences')
                ->where('id', $id)
                ->orderBy('created_at', 'desc')
                ->first();

        if(!is_null($event->presences) && count($event->presences) > 0){
            $pdf = new \setasign\Fpdi\Fpdi('L');
            try {
                if(!is_null($event->file_certificate)) {
                    $pdf->setSourceFile(storage_path("app/public/user_certificate/{$event->file_certificate}"));
                }else {
                    $pdf->setSourceFile(storage_path("app/public/certificate_tmp/template_sertifikat.pdf"));
                }
            } catch (\setasign\Fpdi\PdfParser\PdfParserException $e) {
            }

            try {
                $template = $pdf->importPage(1);
            } catch (\setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException $e) {
            } catch (\setasign\Fpdi\PdfParser\Filter\FilterException $e) {
            } catch (\setasign\Fpdi\PdfParser\Type\PdfTypeException $e) {
            } catch (\setasign\Fpdi\PdfParser\PdfParserException $e) {
            } catch (\setasign\Fpdi\PdfReader\PdfReaderException $e) {
            }


            foreach($event->presences as $presence){
                $data = json_decode($presence->form_json);

                $pdf->addPage();

                $pdf->useTemplate($template, 0, 0, 297, 210);

                $pdf->SetAutoPageBreak(false);
                $pdf->SetFont('helvetica', 'B', 25);

                //set coordinat text
                $pdf->SetXY(0, 56);
                $pdf->Cell(0, 10, $data->nama_lengkap, 0, 0, 'C');
            }

            $title = "Sertifikat {$event->topic}";
            $pdf->setTitle($title);
            $pdf->Output('I', "{$title}.pdf");
        }else {
            return redirect()->route('event.index')->with('error', 'Tidak ada peserta.');
        }
    }

    public function show_certificate($id)
    {
        //Get Data Event
        $event = TrxEvent::with('presences')
                ->where('id', $id)
                ->orderBy('created_at', 'desc')
                ->first();

        $pdf = new \setasign\Fpdi\Fpdi('L');
        try {
            if(!is_null($event->file_certificate)) {
                $pdf->setSourceFile(storage_path("app/public/user_certificate/{$event->file_certificate}"));
            }else {
                $pdf->setSourceFile(storage_path("app/public/certificate_tmp/template_sertifikat.pdf"));
            }
        } catch (\setasign\Fpdi\PdfParser\PdfParserException $e) {
        }

        try {
            $template = $pdf->importPage(1);
        } catch (\setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException $e) {
        } catch (\setasign\Fpdi\PdfParser\Filter\FilterException $e) {
        } catch (\setasign\Fpdi\PdfParser\Type\PdfTypeException $e) {
        } catch (\setasign\Fpdi\PdfParser\PdfParserException $e) {
        } catch (\setasign\Fpdi\PdfReader\PdfReaderException $e) {
        }

        $pdf->addPage();

        $pdf->useTemplate($template, 0, 0, 297, 210);

        $pdf->SetAutoPageBreak(false);
        $pdf->SetFont('helvetica', 'B', 25);

        //set coordinat text
        $pdf->SetXY(0, 56);
        $pdf->Cell(0, 10, "Contoh Nama Peserta", 0, 0, 'C');

        $title = "Sertifikat {$event->topic}";
        $pdf->setTitle($title);
        $pdf->Output('I', "{$title}.pdf");
    }
}
