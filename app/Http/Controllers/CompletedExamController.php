<?php

namespace App\Http\Controllers;
use App\Models\Currentexam;
use App\Http\Controllers\Controller;
use App\Models\ExamCandidatesProjection;
use App\Models\ExamConfirmedHalls;
use App\Models\ExamMaterialRoutes;
use App\Models\ExamVenueConsent;
use Carbon\Carbon;


class CompletedExamController extends Controller
{
    public function __construct()
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth.multi');
    }

    public function index()
    {
        $user = current_user();
        $role = session('auth_role');
        $examIds = null; // Initialize as null so we know if a filter should be applied

        switch ($role) {
            case 'district':
            case 'treasury':
                $examIds = ExamCandidatesProjection::where('district_code', $user->district_code)
                    ->pluck('exam_id')
                    ->unique()
                    ->values();
                break;
            case 'center':
                $examIds = ExamCandidatesProjection::where('center_code', $user->center_code)
                    ->pluck('exam_id')
                    ->unique()
                    ->values();
                break;
            case 'venue':
                $examIds = ExamVenueConsent::where('venue_id', $user->venue_id)
                    ->pluck('exam_id')
                    ->unique()
                    ->values();
                break;
            case 'mobile_team_staffs':
                $examIds = ExamMaterialRoutes::where('mobile_team_staff', $user->mobile_id)
                    ->pluck('exam_id')
                    ->unique()
                    ->values();
                break;
            case 'ci':
                $examIds = ExamConfirmedHalls::where('ci_id', $user->ci_id)
                    ->where('is_apd_uploaded', true)
                    ->where('alloted_count', '>', 0)
                    ->pluck('exam_id')
                    ->unique()
                    ->values();
                break;
            case 'headquarters':
                if ($user->custom_role == 'VDS') {
                    $examIds = ExamMaterialRoutes::where('mobile_team_staff', $user->dept_off_id)
                        ->pluck('exam_id')
                        ->unique()
                        ->values();
                }
                break;
            default:
        }

        $examQuery = Currentexam::withCount('examsession')
            ->orderBy('exam_main_createdat', 'desc');

        // If a role-specific exam ID list is set, filter by it
        if (!is_null($examIds)) {
            $examQuery->whereIn('exam_main_no', $examIds);
        }
        // Get current date
        $today = now();

        // Filter out completed exams (where last_exam_date + 2 days is less than or equal to today)
        $examQuery->whereRaw("CAST(exam_main_lastdate AS DATE) + INTERVAL '2 days' < ?", [$today]);
        $exams = $examQuery->get();

        return view('current_exam.index', compact('exams'));
    }
}