<?php

namespace App\Http\Controllers;

use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Http\Request;

class CIConsolidateController extends Controller
{
    /**
     * Generate a PDF Report.
     */
    public function generateReport()
    {
        // Get the HTML content for the report
        $html = view('pdf.ci_consolidate')->render();

        // Generate the PDF from HTML using Snappy PDF
        return SnappyPdf::loadHTML($html)
            ->setOption('no-images', false)           // Ensure images are included
            ->setOption('lowquality', false)           // Optional: Reduce PDF file size
            ->setOption('orientation', 'portrait')    // Set page orientation to portrait
            ->setOption('page-size', 'A4')            // Set page size to A4
            // ->setOption('margin-top', '10mm')         // Optional: Add top margin
            // ->setOption('margin-bottom', '10mm')      // Optional: Add bottom margin
            // ->setOption('margin-left', '10mm')        // Optional: Add left margin
            // ->setOption('margin-right', '10mm')       // Optional: Add right margin
            ->setOption('no-background', false)       // Optional: Include background images if necessary
            ->setOption('disable-javascript', true)   // Disable JavaScript execution
            ->setOption('load-error-handling', 'ignore') // Ignore loading errors
            ->stream('consolidated_report.pdf');      // Stream the PDF to the browser
        $pdf->setOption('watermark', url('storage/assets/images/login-logo.png'))
            ->setOption('watermark-opacity', 0.1)
            ->setOption('watermark-size', '50%');  // Adjust size as needed
    }
}
