<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Spatie\Browsershot\Browsershot;

class IdToCollectorateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $subject;

    public function __construct($data, $subject)
    {
        $this->data = $data;
        $this->subject = $subject;
    }

    public function build()
    {
        // Generate the PDF from the view
        $pdf = $this->generatePdf();
        return $this->subject($this->subject)
            ->view('email.id_to_collectorate')
            ->with('data', $this->data)
            ->attachData($pdf->output(), 'TNPSC_Intimation.pdf', [ // Attach the PDF data
                'mime' => 'application/pdf',
            ]);
        ;
    }

    /**
     * Generates the PDF from the view.
     *
     */
    protected function generatePdf()
    {
        // Load the view and pass the data
        $html = view('email.id_to_collectorate', ['data' => $this->data])->render();

        // Generate the PDF
        $pdf = Browsershot::html($html)
            ->setOption('landscape', false)
            ->setOption('margin', [
                'top' => '10mm',
                'right' => '10mm',
                'bottom' => '10mm',
                'left' => '10mm'
            ])
            ->setOption('preferCSSPageSize', true)
            ->setOption('printBackground', true)
            ->scale(1)
            ->format('Letter')
            ->pdf();

        // Define a unique filename for the report
        $filename = 'TNPSC_Intimation' . time() . '.pdf';

        // Return the PDF as a response
        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');

    }
}