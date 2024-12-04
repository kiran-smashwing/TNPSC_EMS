<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Center;
use App\Models\District;
use App\Models\Currentexam;
use Illuminate\Support\Facades\Auth;
use App\Services\ExamAuditService;
use App\Models\ChiefInvigilator;
use App\Models\ExamVenueConsent;
class VenueConsentController extends Controller
{
    protected $auditService;

    public function __construct(ExamAuditService $auditService)
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth.multi');
        $this->auditService = $auditService;
    }

    public function showVenueConsentForm($examId)
    {
        $role = session('auth_role');
        $guard = $role ? Auth::guard($role) : null;
        $user = $guard ? $guard->user() : null;
        // Fetch unique district values from the same table
        $districts = District::all(); // Fetch all districts
        // Fetch unique center values from the same table
        $centers = center::all(); // Fetch all venues
        // Retrieve exam and its sessions
        $exam = Currentexam::where('exam_main_no', $examId)->with(relations: ['examsession', 'examservice'])->first();
        //exam consent details 
        $venueConsents = \DB::table('exam_venue_consent')
            ->where('exam_id', $examId)
            ->where('venue_id', $user->venue_id)
            ->first();
        //get ChiefInvigilator with venue id
        $chiefInvigilators = ChiefInvigilator::where('ci_venue_id', $user->venue_code)->get();
        // Pass the exams to the index view
        return view('my_exam.venue.venue-consent', compact('exam', 'user', 'districts', 'centers', 'venueConsents', 'chiefInvigilators'));
    }

    public function submitVenueConsentForm(Request $request, $examId)
    {
        $request->validate([
            'consent' => 'required|in:accept,decline',
            'ciName' => 'nullable|array'
        ]);
        try {
            $role = session('auth_role');
            $guard = $role ? Auth::guard($role) : null;
            $user = $guard ? $guard->user() : null;

            $examVenueConsent = ExamVenueConsent::where('exam_id', $examId)
                ->where('venue_id', $user->venue_id)
                ->first();
            if ($examVenueConsent) {
                // Update existing exam venue consent
                $examVenueConsent->consent_status = $request->consent == 'accept' ? 'accepted' : 'denied';
                $examVenueConsent->chief_invigilator_ids = $request->consent == 'accept' ? $request->ciName : null;
                $examVenueConsent->save();
            }
            // Return a success response
            return response()->json([
                'message' => 'Your consent has been recorded successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'There was an error submitting your consent.'
            ], 422);
        }
    }
}