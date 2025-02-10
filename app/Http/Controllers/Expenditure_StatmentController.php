<?php

namespace App\Http\Controllers;

use App\Models\Currentexam;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\DB;
use App\Models\Center;
use App\Models\CIChecklistAnswer;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\District;

use Illuminate\Http\Request;

class Expenditure_StatmentController extends Controller
{
    public function index()
    {
        $districts = District::all(); // Fetch all districts
        // Fetch unique center values from the same table
        $centers = center::all(); // Fetch all venues
        return view('view_report.expenditure_report.index', compact('districts', 'centers')); // Path matches the file created
    }
    public function filterExpenditure(Request $request)
    {
        $query = Currentexam::with('examservice');

        // Apply filters if the input is provided
        if ($request->filled('notification_no')) {
            $query->where('exam_main_notification', $request->notification_no);
        }

        // Fetch the filtered results
        $exam_data = $query->get();

        // Ensure there's at least one record before accessing properties
        if ($exam_data->isNotEmpty()) {
            $exam_main_no = $exam_data->first()->exam_main_no;
        } else {
            return redirect()->back()->with([
                'error' => 'No exam data found.',
                'attendance_data' => [] // Pass empty array to avoid errors
            ]);
        }

        // ✅ Always define `$attendance_data` before using it
        $attendance_data = [];

        // Fetch candidate attendance and related data
        $candidate_attendance = CIChecklistAnswer::where('exam_id', $exam_main_no)
            ->with('ci.venue', 'center.district') // Include relationships
            ->get();

        if ($candidate_attendance->isNotEmpty()) {
            // Extract required fields
            $attendance_data = $candidate_attendance->map(function ($item) {
                // Decode utility_answer JSON safely
                $utility = $item->utility_answer;

                return [
                    'id' => $item->id,
                    'district' => optional($item->center->district)->district_name ?? 'N/A',
                    'center' => optional($item->center)->center_name ?? 'N/A',
                    'hall_code' => $item->hall_code ?? 'N/A',
                    'venue_name' => optional($item->ci->venue)->venue_name ?? 'N/A',
                    'amountReceived' => $utility['amountReceived'] ?? '0',
                    'totalAmountSpent' => $utility['totalAmountSpent'] ?? '0',
                    'balanceAmount' => $utility['balanceAmount'] ?? '0',
                ];
            })->toArray(); // Ensure it's an array
        }

        // ✅ Now `$attendance_data` is always defined (empty or filled)
        return view('view_report.expenditure_report.index', compact(
            'exam_data',
            'exam_main_no',
            'attendance_data' // This will never be undefined
        ));
    }

public function getDropdownData(Request $request)
    {
        $notificationNo = $request->query('notification_no');

        // Check if notification exists
        $examDetails = Currentexam::where('exam_main_notification', $notificationNo)->first();

        if (!$examDetails) {
            return response()->json(['error' => 'Invalid Notification No'], 404);
        }

        // Retrieve the role and guard from session
        $role = session('auth_role');
        $guard = $role ? Auth::guard($role) : null;

        // Get the user based on the guard
        $user = $guard ? $guard->user() : null;
        $districtCodeFromSession = $user ? $user->district_code : null; // Assuming district_code is in the user table
        $centerCodeFromSession = $user ? $user->center_code : null; // Assuming center_code is in the user table or retrieved through a relationship

        // Fetch districts - shown for headquarters and district roles
        $districts = [];
        if ($role === 'headquarters' || $role === 'district') {
            $districts = DB::table('district')
                ->select('district_code as id', 'district_name as name')
                ->get();
        }

        // Fetch centers
        $centers = [];
        if ($role === 'headquarters') {
            // Fetch all centers for headquarters
            $centers = DB::table('centers')
                ->select('center_code as id', 'center_name as name')
                ->get();
        } elseif ($role === 'district') {
            // Fetch centers for the specific district
            if ($districtCodeFromSession) {
                $centers = DB::table('centers')
                    ->where('center_district_id', $districtCodeFromSession)
                    ->select('center_code as id', 'center_name as name')
                    ->get();
            }
        }

        // Exam Dates and Sessions - applicable to all roles
        $examDates = DB::table('exam_session')
            ->where('exam_sess_mainid', $examDetails->exam_main_no)
            ->select('exam_sess_date')
            ->distinct()
            ->pluck('exam_sess_date');

        $sessions = DB::table('exam_session')
            ->where('exam_sess_mainid', $examDetails->exam_main_no)
            ->select('exam_sess_session')
            ->distinct()
            ->pluck('exam_sess_session');
        // Return data with logic applied based on roles
        return response()->json([
            'user' => $user,
            'districts' => $districts,
            'centers' => $centers,
            'examDates' => $examDates,
            'sessions' => $sessions,
            'centerCodeFromSession' => $centerCodeFromSession,
        ]);
    }
    public function generateexapenditureCertificate($examid)
    {
        $exam_data = Currentexam::with('examservice', 'examsession')
            ->where('exam_main_no', $examid)
            ->first();

        if (!$exam_data) {
            abort(404, 'Exam data not found.');
        }

        // Collect all exam dates
        $all_exam_dates = [];
        foreach ($exam_data->examsession as $session) {
            if (!empty($session->exam_sess_date)) {
                $dates = explode(',', $session->exam_sess_date);
                foreach ($dates as $date) {
                    $all_exam_dates[] = trim($date);
                }
            }
        }

        // Remove duplicate dates and format them
        $uniqueDates = array_unique($all_exam_dates);
        $formattedDates = array_map(fn($date) => \Carbon\Carbon::parse($date)->format('d-m-Y'), $uniqueDates);
        $formattedDatesString = implode(', ', $formattedDates);

        // Fetch utilization data
        $ci_amount = DB::table('ci_checklist_answers')->where('exam_id', $examid)->get();
        if ($ci_amount->isEmpty()) {
            abort(404, 'Utilization data not found.');
        }

        // Fetch candidate attendance with relationships
        $candidate_attendance = CIChecklistAnswer::where('exam_id', $examid)
            ->with(['ci', 'ci.venue', 'center', 'center.district'])
            ->get();

        if ($candidate_attendance->isEmpty()) {
            return back()->with('error', 'No candidate attendance found for this exam date.');
        }

        // Extract required details
        // Extract required details
        // Extract required details
        $ci_details = $candidate_attendance->pluck('ci')->unique();
        // dd($ci_details);
        $venue_details = $candidate_attendance->pluck('ci.venue')->unique();
        $center_details = $candidate_attendance->pluck('center')->unique();
        $district_details = $candidate_attendance->pluck('center.district')->unique();

        // Store extracted details in a single array
        $examDetails = [
            'center_name'    => optional($center_details->first())->center_name ?? 'N/A',
            'center_code'    => optional($center_details->first())->center_code ?? 'N/A',
            'district_name'  => optional($district_details->first())->district_name ?? 'N/A',
            'venue_name'     => optional($venue_details->first())->venue_name ?? 'N/A',
            'ci_name'        => optional($ci_details->first())->ci_name ?? 'N/A',
            'ci_designation' => optional($ci_details->first())->ci_designation ?? 'N/A',
            'ci_phone'       => optional($ci_details->first())->ci_phone ?? 'N/A',
        ];

        //    dd($examDetails);
        // Fetch hall code
        $hall_code = DB::table('exam_confirmed_halls')->where('exam_id', $examid)->pluck('hall_code')->first();

        // Extract utility answer details
        $utility_answer = json_decode($ci_amount->first()->utility_answer, true);
        $amount = $utility_answer['amountReceived'] ?? 'N/A';
        $amount_in_words = $this->convertNumberToWords($amount);

        // Generate HTML view
        $html = view('view_report.expenditure_report.expenditure-report', compact(
            'exam_data',
            'formattedDatesString',
            'utility_answer',
            'amount',
            'amount_in_words',
            'hall_code',

            'examDetails',
        ))->render();

        // Generate PDF using Browsershot
        $pdf = Browsershot::html($html)
            ->setOption('landscape', false)
            ->setOption('margin', [
                'top' => '10mm',
                'right' => '10mm',
                'bottom' => '10mm',
                'left' => '10mm'
            ])
            ->setOption('displayHeaderFooter', true)
            ->setOption('headerTemplate', '<div></div>')
            ->setOption('footerTemplate', '
            <div style="font-size:10px;width:100%;text-align:center;">
                Page <span class="pageNumber"></span> of <span class="totalPages"></span>
            </div>
            <div style="position: absolute; bottom: 5mm; right: 10px; font-size: 10px;">
                IP: ' . request()->ip() . ' | Timestamp: ' . now()->format('d-m-Y H:i:s') . '
            </div>')
            ->setOption('preferCSSPageSize', true)
            ->setOption('printBackground', true)
            ->scale(1)
            ->format('A4')
            ->pdf();

        // Define a unique filename for the report
        $filename = 'expenditure-report-' . time() . '.pdf';

        // Return the PDF response
        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }





    private function convertNumberToWords($number)
    {
        $hyphen      = '-';
        $conjunction = ' and ';
        $separator   = ', ';
        $negative    = 'negative ';
        $decimal     = ' point ';
        $dictionary  = array(
            0                   => 'zero',
            1                   => 'one',
            2                   => 'two',
            3                   => 'three',
            4                   => 'four',
            5                   => 'five',
            6                   => 'six',
            7                   => 'seven',
            8                   => 'eight',
            9                   => 'nine',
            10                  => 'ten',
            11                  => 'eleven',
            12                  => 'twelve',
            13                  => 'thirteen',
            14                  => 'fourteen',
            15                  => 'fifteen',
            16                  => 'sixteen',
            17                  => 'seventeen',
            18                  => 'eighteen',
            19                  => 'nineteen',
            20                  => 'twenty',
            30                  => 'thirty',
            40                  => 'forty',
            50                  => 'fifty',
            60                  => 'sixty',
            70                  => 'seventy',
            80                  => 'eighty',
            90                  => 'ninety',
            100                 => 'hundred',
            1000                => 'thousand',
            1000000             => 'million',
            1000000000          => 'billion',
            1000000000000       => 'trillion',
            1000000000000000    => 'quadrillion',
            1000000000000000000 => 'quintillion'
        );

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                'convertNumberToWords only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );
            return false;
        }

        if ($number < 0) {
            return $negative . $this->convertNumberToWords(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds  = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . $this->convertNumberToWords($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = $this->convertNumberToWords($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= $this->convertNumberToWords($remainder);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }

        return ucfirst($string);
    }
    // public function generateexpenditureReport()
    // {

    //     $data = [];
    //     $html = view('view_report.expenditure_report.expenditure-report')->render();
    //     // $html = view('PDF.Reports.ci-utility-certificate')->render();
    //     $pdf = Browsershot::html($html)
    //         ->setOption('landscape', false)
    //         ->setOption('margin', [
    //             'top' => '10mm',
    //             'right' => '10mm',
    //             'bottom' => '10mm',
    //             'left' => '10mm'
    //         ])
    //         ->setOption('displayHeaderFooter', true)
    //         ->setOption('headerTemplate', '<div></div>')
    //         ->setOption('footerTemplate', '
    //         <div style="font-size:10px;width:100%;text-align:center;">
    //             Page <span class="pageNumber"></span> of <span class="totalPages"></span>
    //         </div>
    //         <div style="position: absolute; bottom: 5mm; right: 10px; font-size: 10px;">
    //              IP: ' . $_SERVER['REMOTE_ADDR'] . ' | Timestamp: ' . date('d-m-Y H:i:s') . ' 
    //         </div>')
    //         ->setOption('preferCSSPageSize', true)
    //         ->setOption('printBackground', true)
    //         ->scale(1)
    //         ->format('A4')
    //         ->pdf();
    //     // Define a unique filename for the report
    //     $filename = 'expenditure_reprot' . time() . '.pdf';

    //     // Return the PDF as a response
    //     return response($pdf)
    //         ->header('Content-Type', 'application/pdf')
    //         ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    // }
}
