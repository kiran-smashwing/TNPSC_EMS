<?php

namespace App\Http\Controllers;

use App\Services\ExamAuditService;
use App\Models\ExamVenueConsent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\VenueConsentMail;
use Illuminate\Http\Request;
use App\Models\District;
use App\Models\Venues;

class DistrictCandidatesController extends Controller
{
    protected $auditService;

    public function __construct(ExamAuditService $auditService)
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth.multi');
        $this->auditService = $auditService;
    }
    public function showVenueIntimationForm($examId)
    {
        $role = session('auth_role');
        $guard = $role ? Auth::guard($role) : null;
        $user = $guard ? $guard->user() : null;

        $examCenters = \DB::table('exam_candidates_projection')
            ->select('center_code', \DB::raw('SUM(accommodation_required) as total_accommodation'), \DB::raw('COUNT(*) as candidate_count'))
            ->where('exam_id', $examId)
            ->where('district_code', $user->district_code)
            ->groupBy('center_code')
            ->get();
        $examCenters->each(function ($center) {
            $center->details = \DB::table('centers')
                ->where('center_code', $center->center_code)
                ->first();
        });
        $allvenues = [];
        foreach ($examCenters as $center) {
            $centerVenues = \DB::table('venue')
                ->where('venue_center_id', $center->center_code)
                ->get();
            $allvenues[$center->center_code] = $centerVenues;
        }

        $venueConsents = \DB::table('exam_venue_consent')
            ->where('exam_id', $examId)
            ->where('district_code', $user->district_code)
            ->get()
            ->keyBy('venue_id');

        foreach ($allvenues as $centerCode => $venues) {
            foreach ($venues as $venue) {
                $venue->halls_count = $venueConsents->has($venue->venue_id) ? $venueConsents->get($venue->venue_id)->expected_candidates_count / 200 : 0;
                $venue->consent_status = $venueConsents->has($venue->venue_id) ? $venueConsents->get($venue->venue_id)->consent_status : 'not_requested';
            }
        }
        $totalCenters = \DB::table('centers')
            ->where('center_district_id', $user->district_code)
            ->count();
        $totalCentersFromProjection = \DB::table('exam_candidates_projection')
            ->where('exam_id', $examId)
            ->where('district_code', $user->district_code)
            ->distinct('center_code')
            ->count('center_code');
        return view('my_exam.District.venue-intimation', compact('examId', 'examCenters', 'user', 'totalCenters', 'totalCentersFromProjection', 'allvenues'));
    }
    public function processVenueConsentEmail(Request $request)
    {
        // Validate the request
        $request->validate([
            'center_code' => 'required',
            'exam_id' => 'required',
            'venues' => 'required|array'
        ]);
        $role = session('auth_role');
        $guard = $role ? Auth::guard($role) : null;
        $user = $guard ? $guard->user() : null;

        // Get the district code (you might need to derive this from the center code)
        $districtCode = $user->district_code;

        // Process each selected venue
        foreach ($request->venues as $venue) {
            // Create or update exam venue consent record
            ExamVenueConsent::updateOrCreate(
                [
                    'exam_id' => $request->exam_id,
                    'venue_id' => $venue['venue_id'],
                    'center_code' => $request->center_code,
                    'district_code' => $districtCode
                ],
                [
                    'consent_status' => 'requested', // Initial status
                    'email_sent_status' => true,
                    'expected_candidates_count' => $venue['halls_count'] * 200 // Assuming 200 candidates per hall
                ]
            );
            // Get the current exam details
            $currentExam = \DB::table('exam_main')->where('exam_main_no', $request->exam_id)->first();

            // Send actual email to venue
            $this->sendVenueConsentEmail($venue['venue_id'], $currentExam);

        }
        // Log the action using the AuditService
        $currentUser = current_user();
        $userName = $currentUser ? $currentUser->display_name : 'Unknown';

        $metadata = [
            'user_name' => $userName,
        ];

        // Check if a log already exists for this exam and task type
        $existingLog = $this->auditService->findLog([
            'exam_id' => $request->exam_id,
            'task_type' => 'exam_venue_consent',
            'action_type' => 'email_sent',
        ]);

        if ($existingLog) {
            // Retrieve existing venues from the previous afterState
            $existingVenues = $existingLog->after_state['venues'] ?? [];

            // Merge existing venues with new venues and remove duplicates
            $mergedVenues = collect(array_merge($existingVenues,$request->venues))
                ->unique('venue_id')
                ->values()
                ->all();
            // Update the existing log
            $this->auditService->updateLog(
                logId: $existingLog->id,
                metadata: $metadata,
                afterState: [
                    'venues' => $mergedVenues,
                    'email_sent_status' => true,
                    'total_venues_count' => count($mergedVenues)
                ],
                description: 'Sent consent email to ' . count($request->venues) . ' venues (Total: ' . count($mergedVenues) . ' venues)'
            );
        }
        // Create a new log
        else {
            $this->auditService->log(
                examId: $request->exam_id,
                actionType: 'email_sent',
                taskType: 'exam_venue_consent',
                beforeState: null,
                afterState: [
                    'venues' => $request->venues,
                    'email_sent_status' => true,
                    'total_venues_count' => count($request->venues)
                ],
                description: 'Sent consent email to ' . count($request->venues) . ' venues',
                metadata: $metadata
            );
        }

        return response()->json([
            'message' => 'Consent requests sent successfully',
            'venues' => $request->venues
        ]);
    }

    // Optional email sending method
    protected function sendVenueConsentEmail($venueId, $examId)
    {
        // Fetch venue details
        $venue = Venues::findOrFail($venueId);

        // Prepare and send email
        Mail::to("kiran@smashwing.com")->send(new VenueConsentMail($venue, $examId));
    }
}