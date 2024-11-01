<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QrCodeController extends Controller
{
    public function index()
    {
        return view('qr-code-reader');
    }

    public function process(Request $request)
    {
        $qrCode = $request->input('qr_code');
        // Process the QR code data here
        return response()->json(['success' => true, 'data' => $qrCode]);
    }
}