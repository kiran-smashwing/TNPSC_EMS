<?php

namespace App\Http\Controllers;

use App\Models\VenueAssignedCI;
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
            'ciExamData' => 'required_if:consent,accept|json',
        ]);
        try {
            $role = session('auth_role');
            $guard = $role ? Auth::guard($role) : null;
            $user = $guard ? $guard->user() : null;

            // Retrieve the venue's existing consent for the exam
            $examVenueConsent = ExamVenueConsent::where('exam_id', $examId)
                ->where('venue_id', $user->venue_id)
                ->first();

            if (!$examVenueConsent) {
                return response()->json([
                    'message' => 'Venue consent not found.'
                ], 404);
            }
            // Update existing exam venue consent
            $examVenueConsent->consent_status = $request->consent == 'accept' ? 'accepted' : 'denied';
            $examVenueConsent->save();
            // If consent is accepted, process and save ciExamData
            if ($request->consent == 'accept') {
                $ciExamData = json_decode($request->ciExamData, true);

                if (!is_array($ciExamData)) {
                    return response()->json([
                        'message' => 'Invalid format for Chief Invigilator data.'
                    ], 422);
                }
                    // Map and save the data
                    $mappedData = [];

                // Delete previous CI assignments for this exam and venue
                VenueAssignedCI::where('venue_consent_id', $examVenueConsent->id)->delete();

                // Insert new CI records with venue_consent_id, exam_date, and ci_id
                foreach ($ciExamData as $data) {
                    if (isset($data['exam_date'], $data['ci_id'])) {
                        VenueAssignedCI::create([
                            'venue_consent_id' => $examVenueConsent->id,
                            'exam_date' => $data['exam_date'],
                            'ci_id' => $data['ci_id'],
                        ]);
                        $mappedData[] = [
                            'id' => $data['id'],
                            'exam_date' => $data['exam_date'],
                            'ci_id' => $data['ci_id']
                        ];
                    }
                }
                // Save the mapped data as JSON
                $examVenueConsent->chief_invigilator_data = $mappedData;
            } else {
                // If consent is declined, remove any previously assigned CIs
                $examVenueConsent->chief_invigilator_data = null;
                VenueAssignedCI::where('venue_consent_id', $examVenueConsent->id)->delete();
            }
            $examVenueConsent->save();
            // Return a success response
            return response()->json([
                'message' => 'Your consent has been recorded successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'There was an error submitting your consent.' . $e->getMessage()
            ], 422);
        }
    }
}