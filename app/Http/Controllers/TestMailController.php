<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;

use Symfony\Component\Mime\Email;
use Illuminate\Support\Facades\DB;
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


    public function updateConsentStatusOnly()
    {
        try {
            DB::beginTransaction();

            // Update matching records only
            DB::table('exam_venue_consent')
                ->where([
                    ['venue_id', '=', '1658'],
                    ['exam_id', '=', '20250508154900'],
                    ['center_code', '=', '3004'],
                    ['district_code', '=', 30],
                    ['consent_status', '=', 'saved'],
                    ['email_sent_status', '=', 'false'],
                    ['expected_candidates_count', '=', 600],
                ])
                ->whereNull('venue_max_capacity')
                ->update([
                    'consent_status' => 'requested',
                    'email_sent_status' => 'true'
                ]);

            DB::commit();

            return response()->json(['message' => 'Consent status updated successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function removeDuplicateVenue()
    {
        try {
            DB::beginTransaction();

            // Remove duplicates for venue_id = '1660'
            DB::statement("
            DELETE FROM public.exam_venue_consent a
            USING public.exam_venue_consent b
            WHERE a.ctid > b.ctid
              AND a.venue_id = b.venue_id
              AND a.exam_id = b.exam_id
              AND a.center_code = b.center_code
              AND a.district_code = b.district_code
              AND a.consent_status = b.consent_status
              AND a.email_sent_status = b.email_sent_status
              AND a.expected_candidates_count = b.expected_candidates_count
              AND (a.venue_max_capacity IS NULL AND b.venue_max_capacity IS NULL)
              AND a.venue_id = '1658'
              AND b.venue_id = '1658'
        ");

            DB::commit();

            return response()->json(['message' => 'Duplicate entries for venue_id 1658 removed successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function removeDuplicateVenue_with_Centercode()
    {
        try {
            DB::beginTransaction();

            // Remove duplicates for venue_id = '1660' and center_code = '3001'
            DB::statement("
            DELETE FROM public.exam_venue_consent a
            USING public.exam_venue_consent b
            WHERE a.ctid > b.ctid
              AND a.venue_id = b.venue_id
              AND a.center_code = b.center_code
              AND a.venue_id = '1660'
              AND a.center_code = '3001'
        ");

            DB::commit();

            return response()->json(['message' => 'Duplicate rows for venue_id 1660 and center_code 3001 removed successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
