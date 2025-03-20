<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Center;
use App\Models\MobileTeamStaffs;
use App\Models\TreasuryOfficer;
use App\Models\Venues;
use App\Models\ChiefInvigilator;
use App\Models\ExamService;
use App\Models\Currentexam;
use Carbon\Carbon; // Import Carbon for date comparison
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __construct()
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth.multi');
    }

    public function index()
    {
        $districtCount = District::count(); // Count of all districts
        $treasuryofficerCount = TreasuryOfficer::count(); // Count of all Treasury Officers
        $centerCount = Center::count(); // Count of all centers
        $mobileTeamStaffCount = MobileTeamStaffs::count(); // Count of all Mobile Team Staffs
        $venueCount = Venues::count(); // Count of all venues
        $chiefInvigilatorCount = ChiefInvigilator::count(); // Count of all Chief Invigilators
        $examServiceCount = ExamService::count(); // Count of all Exam Services
        $currentExamCount = Currentexam::count(); // Count of all Current Exams
        $majorExamCount = Currentexam::where('exam_main_model', 'Major')->count();
        $minorExamCount = Currentexam::where('exam_main_model', 'Minor')->count();
        $today = Carbon::today()->format('Y-m-d'); // Ensure today's date is in 'YYYY-MM-DD' format

        // Convert text to date for PostgreSQL comparison
        $currentsExamCount = Currentexam::where(function ($query) use ($today) {
            $query->whereExists(function ($subQuery) use ($today) {
                $subQuery->selectRaw("MAX(TO_DATE(exam_sess_date, 'DD-MM-YYYY'))")
                    ->from('exam_session')
                    ->whereColumn('exam_session.exam_sess_mainid', 'exam_main.exam_main_no') // Use correct table name
                    ->havingRaw("MAX(TO_DATE(exam_sess_date, 'DD-MM-YYYY')) + INTERVAL '2 days' >= TO_DATE(?, 'YYYY-MM-DD')", [$today]);
            })->orWhereDoesntHave('examsession');
        })->count();

        $completedExamCount = Currentexam::where(function ($query) use ($today) {
            $query->whereExists(function ($subQuery) use ($today) {
                $subQuery->selectRaw("MAX(TO_DATE(exam_sess_date, 'DD-MM-YYYY'))")
                    ->from('exam_session')
                    ->whereColumn('exam_session.exam_sess_mainid', 'exam_main.exam_main_no') // Ensure correct table reference
                    ->havingRaw("MAX(TO_DATE(exam_sess_date, 'DD-MM-YYYY')) + INTERVAL '2 days' < TO_DATE(?, 'YYYY-MM-DD')", [$today]);
            })->orWhereDoesntHave('examsession');
        })->count();

        // Count Exams by Type
        $descriptiveExamCount = Currentexam::where('exam_main_type', 'Descriptive')->count();
        $objectiveExamCount = Currentexam::where('exam_main_type', 'Objective')->count();
        $objDescExamCount = Currentexam::where('exam_main_type', 'Objective+Descriptive')->count();
        $cbtExamCount = Currentexam::where('exam_main_type', 'CBT')->count();

        // dd($completedExamCount); // Debugging output
        return view('dashboard.index', compact(
            'districtCount',
            'treasuryofficerCount',
            'centerCount',
            'mobileTeamStaffCount',
            'venueCount',
            'chiefInvigilatorCount',
            'examServiceCount',
            'currentExamCount',
            'majorExamCount',
            'minorExamCount',
            'currentsExamCount',
            'completedExamCount',
            'descriptiveExamCount',
            'objectiveExamCount',
            'objDescExamCount',
            'cbtExamCount',
        ));
    }
}
