<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;

use Symfony\Component\Mime\Email;

use Illuminate\Http\Request;
use App\Mail\TestEmail;

class TestMailController extends Controller
{
    public function sendTestEmail()
    {
        $toEmail = 'msathish9000057@gmail.com'; // Replace with your test email
        $messageContent = '<h1>This is a test email sent from Laravel 11</h1>';

        try {
            // Send the email using the TestEmail Mailable
            Mail::to($toEmail)->send(new TestEmail($messageContent));

            return response()->json(['message' => 'Test email sent successfully. Check your inbox!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
