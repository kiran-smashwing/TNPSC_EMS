<?php

namespace App\Http\Controllers;

use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Http\Request;
use Spatie\Browsershot\Browsershot;

class CIConsolidateController extends Controller
{
    /**
     * Generate a PDF Report.
     */
    public function generateReport()
{
    // Prepare the data for the report
    $data = [
        // Add any data you want to pass to the view
    ];

    // Get the HTML content for the report
    $html = view('pdf.ci_consolidate', $data)->render();

    // Generate the PDF using Browsershot
    $pdf = Browsershot::html($html)
        ->setOption('landscape', false) // Set to portrait orientation
        ->setOption('margin', [
            'top' => '10mm',
            'right' => '10mm',
            'bottom' => '10mm',
            'left' => '10mm'
        ])
        ->setOption('displayHeaderFooter', false)
        ->setOption('preferCSSPageSize', true)
        ->setOption('printBackground', true)
        ->scale(1)
        ->format('A4')
        ->pdf();

    // Define a unique filename for the report
    $filename = 'consolidated-report-' . time() . '.pdf';

    // Return the PDF as a response
    return response($pdf)
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
}

}
