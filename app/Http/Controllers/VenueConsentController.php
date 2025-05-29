<?php

namespace App\Http\Controllers;

use App\Models\ExamConfirmedHalls;
use App\Models\VenueAssignedCI;
use Illuminate\Http\Request;
use App\Models\Center;
use App\Models\District;
use App\Models\Currentexam;
use Illuminate\Support\Carbon;
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
        $venueConsents = ExamVenueConsent::where('exam_id', $examId)
            ->where('venue_id', $user->venue_id)
            ->with('assignedCIs')
            ->first();
        //get ChiefInvigilator with venue id
        $chiefInvigilators = ChiefInvigilator::where('ci_venue_id', $user->venue_id)->where('ci_status', true)->get();
        // Pass the exams to the index view
        return view('my_exam.venue.venue-consent', compact('exam', 'user', 'districts', 'centers', 'venueConsents', 'chiefInvigilators'));
    }

    public function submitVenueConsentForm(Request $request, $examId)
    {
        // dd($request->all());
        $request->validate([
            'consent' => 'required|in:accept,decline',
            'ciExamData' => 'required_if:consent,accept|json',
            'venueCapacity' => 'required_if:consent,accept|numeric|min:1',
        ]);
        try {
            $role = session('auth_role');
            $guard = $role ? Auth::guard($role) : null;
            $user = $guard ? $guard->user() : null;
            // Get exam details for validation
            $exam = Currentexam::where('exam_main_no', $examId)
                ->with(['examsession'])
                ->first();
            $maxCandidatesPerHall = $exam->exam_main_candidates_for_hall;
            $examSessionDates = $exam->examsession->pluck('exam_sess_date')
                ->map(fn($d) => Carbon::parse($d)->format('Y-m-d'))
                ->unique()
                ->values()
                ->toArray();
            // Validate CI data if consent accepted
            if ($request->consent === 'accept') {
                $ciExamData = json_decode($request->ciExamData, true);

                if (!is_array($ciExamData)) {
                    return response()->json([
                        'message' => 'Invalid format for Chief Invigilator data.'
                    ], 422);
                }
                $venueCapacity = (int) $request->venueCapacity;
                $dateTotals = [];

                foreach ($ciExamData as $entry) {
                    // Validate required fields
                    if (!isset($entry['exam_date'], $entry['ci_id'], $entry['candidate_count'])) {
                        return response()->json([
                            'message' => 'Missing required fields in CI data'
                        ], 422);
                    }

                    // Validate candidate count
                    $count = (int) $entry['candidate_count'];
                    if ($count < 1 || $count > $maxCandidatesPerHall) {
                        return response()->json([
                            'message' => "Invalid candidate count for CI {$entry['ci_id']}"
                        ], 422);
                    }

                    // Validate exam date
                    if (!in_array($entry['exam_date'], $examSessionDates)) {
                        return response()->json([
                            'message' => "Invalid exam date: {$entry['exam_date']}"
                        ], 422);
                    }

                    // Track date totals
                    $dateTotals[$entry['exam_date']] = ($dateTotals[$entry['exam_date']] ?? 0) + $count;
                }

                // Validate venue capacity per date
                foreach ($dateTotals as $date => $total) {
                    if ($total > $venueCapacity) {
                        return response()->json([
                            'message' => "Total candidates for $date exceeds venue capacity"
                        ], 422);
                    }
                }
            }

            // Retrieve the venue's existing consent for the exam
            $examVenueConsent = ExamVenueConsent::where('exam_id', $examId)
                ->where('venue_id', $user->venue_id)
                ->first();

            if (!$examVenueConsent) {
                return response()->json([
                    'message' => 'Venue consent not found.'
                ], 404);
            }

            // Check if the venue is already confirmed
            if ($examVenueConsent->consent_status === 'accepted') {
                // Check if the exam is already confirmed
                $isConfirmed = ExamConfirmedHalls::where('exam_id', $examId)
                    ->where('venue_code', $user->venue_id)
                    ->exists();
                if ($isConfirmed) {
                    // Return a response indicating that the venue is already confirmed
                    return response()->json([
                        'message' => 'Your venue is currently in the process of being confirmed for this exam. No further updates are allowed at this stage.'
                    ], 422);
                }
            }

            // Update existing exam venue consent
            $examVenueConsent->consent_status = $request->consent == 'accept' ? 'accepted' : 'denied';

            // Store venue capacity if consent is accepted
            if ($request->consent == 'accept') {
                // Prevent update if capacity already exists
                if (is_null($examVenueConsent->venue_max_capacity)) {
                    $examVenueConsent->venue_max_capacity = $request->venueCapacity;
                }
            } else {
                $examVenueConsent->venue_max_capacity = null;
            }

            $examVenueConsent->save();

            // If consent is accepted, process and save ciExamData
            if ($request->consent == 'accept') {


                // Delete previous CI assignments for this exam and venue
                VenueAssignedCI::where('venue_consent_id', $examVenueConsent->id)->delete();

                // Insert new CI records with venue_consent_id, exam_date, and ci_id
                foreach ($ciExamData as $data) {
                    if (isset($data['exam_date'], $data['ci_id'])) {
                        VenueAssignedCI::create([
                            'venue_consent_id' => $examVenueConsent->id,
                            'exam_date' => $data['exam_date'],
                            'ci_id' => $data['ci_id'],
                            'candidate_count' => $data['candidate_count'],
                        ]);
                    }
                }
            } else {
                // If consent is declined, remove any previously assigned CIs
                VenueAssignedCI::where('venue_consent_id', $examVenueConsent->id)->delete();
            }

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