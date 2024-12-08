<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Snappy PDF / Image Configuration
    |--------------------------------------------------------------------------
    |
    | This option contains settings for PDF generation.
    |
    | Enabled:
    |    
    |    Whether to load PDF / Image generation.
    |
    | Binary:
    |    
    |    The file path of the wkhtmltopdf / wkhtmltoimage executable.
    |
    | Timeout:
    |    
    |    The amount of time to wait (in seconds) before PDF / Image generation is stopped.
    |    Setting this to false disables the timeout (unlimited processing time).
    |
    | Options:
    |
    |    The wkhtmltopdf command options. These are passed directly to wkhtmltopdf.
    |    See https://wkhtmltopdf.org/usage/wkhtmltopdf.txt for all options.
    |
    | Env:
    |
    |    The environment variables to set while running the wkhtmltopdf process.
    |
    */
    'pdf' => [
        'enabled' => true,
        'binary'  => env('WKHTML_PDF_BINARY', '"C:/Program Files/wkhtmltopdf/bin/wkhtmltopdf.exe"'), // Path to wkhtmltopdf
        'timeout' => false,
        'options' => [
            'page-size'     => 'A4',             // Set page size to A4
            'orientation'   => 'portrait',       // Set orientation to portrait
            // 'margin-top'    => '10mm',           // Set top margin
            // 'margin-bottom' => '10mm',           // Set bottom margin
            // 'margin-left'   => '10mm',           // Set left margin
            // 'margin-right'  => '10mm',           // Set right margin
            'encoding'      => 'UTF-8', // Ensure UTF-8 encoding
        ],
        'env'     => [],
    ],

    'image' => [
        'enabled' => true,
        'binary'  => env('WKHTML_IMG_BINARY', '"C:/Program Files/wkhtmltopdf/bin/wkhtmltoimage.exe"'), // Path to wkhtmltoimage
        'timeout' => false,
        'options' => [
            'page-size'     => 'A4',             // Set page size to A4 for images as well
            'orientation'   => 'portrait',       // Set orientation to portrait for images
        ],
        'env'     => [],
    ],




];
