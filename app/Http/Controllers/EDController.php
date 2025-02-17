<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Browsershot\Browsershot;

class EDController extends Controller
{
    public function generateEDReport()
    {
        // $exam_id = ;
        // $exam_date= ;
        $data = [];  // You can load your ED report data here

        // Render the view for the ED report
        $html = view('PDF.ED.st_receiving_team')->render(); // Assuming you have an `ed_report.blade.php` view in the 'pdf' folder

        // Create the PDF with the necessary options
        $pdf = Browsershot::html($html)
            ->setOption('landscape', true) // Set landscape orientation
            ->setOption('margin', [
                'top' => '10mm',
                'right' => '10mm',
                'bottom' => '10mm',
                'left' => '10mm'
            ])
            ->setOption('displayHeaderFooter', true)
            ->setOption('headerTemplate', '<div></div>')
            ->setOption('footerTemplate', '
        <div style="font-size:10px;width:100%;text-align:center;">
            Page <span class="pageNumber"></span> of <span class="totalPages"></span>
        </div>
        <div style="position: absolute; bottom: 5mm; right: 10px; font-size: 10px;">
            IP: ' . $_SERVER['REMOTE_ADDR'] . ' | Timestamp: ' . date('d-m-Y H:i:s') . ' 
        </div>')
            ->setOption('preferCSSPageSize', true)
            ->setOption('printBackground', true)
            ->scale(1)
            ->format('A4')  // Page size
            ->pdf(); // Generate PDF

        // Define a unique filename for the ED report
        $filename = 'ed_statment_receiving_report_' . time() . '.pdf';

        // Return the PDF as a response to be viewed inline
        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }
}
