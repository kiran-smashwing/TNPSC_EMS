<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Currentexam;
use Illuminate\Support\Facades\DB;
use Spatie\Browsershot\Browsershot;
// use NumberFormatter;

class UtilityController extends Controller
{
    public function __construct()
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth.multi');
    }
    public function generateUtilizationCertificate($examid)
    {
        // Get the role and user from the session
        $role = session('auth_role');
        $guard = $role ? Auth::guard($role) : null;
        $user = $guard ? $guard->user() : null;

        if (!$user) {
            abort(403, 'Unauthorized action.');
        }

        $exam_data = Currentexam::with('examservice')
            ->where('exam_main_no', $examid)
            ->first();

        if (!$exam_data) {
            abort(404, 'Exam data not found.');
        }

        // Initialize an array to hold all exam dates
        $all_exam_dates = [];

        // Collect all exam dates from examsession
        foreach ($exam_data->examsession as $session) {
            if (!empty($session->exam_sess_date)) {
                $dates = explode(',', $session->exam_sess_date);
                foreach ($dates as $date) {
                    $all_exam_dates[] = trim($date);
                }
            }
        }

        // Remove duplicate dates and format the unique dates
        $uniqueDates = array_unique($all_exam_dates);
        $formattedDates = array_map(function ($date) {
            return \Carbon\Carbon::parse($date)->format('d-m-Y');
        }, $uniqueDates);

        $formattedDatesString = implode(', ', $formattedDates);

        $ci_amount = DB::table('ci_checklist_answers')
            ->where('exam_id', $examid)
            ->where('ci_id', $user->ci_id)
            ->get();
        $hall_code = DB::table('exam_confirmed_halls')
            ->where('exam_id', $examid)
            ->where('district_code', $user->ci_district_id)
            ->where('center_code', $user->ci_center_id)
            ->where('venue_code', $user->ci_venue_id)
            ->where('ci_id', $user->ci_id)
            ->pluck('hall_code')
            ->first();
    //    dd($hall_code);
        if ($ci_amount->isEmpty()) {
            abort(404, 'CI data not found.');
        }

        // Use the utility_answer data directly
        $utility_answer = json_decode($ci_amount->first()->utility_answer, true);
        $amount = $utility_answer['amountReceived'] ?? 'N/A';
        $amount_in_words = $this->convertNumberToWords($amount);

        // Pass the collection to the Blade template
        // return view('PDF.Reports.ci-utility-certificate', compact('exam_data', 'formattedDatesString', 'user', 'utility_answer', 'amount', 'amount_in_words'));
        $html = view('PDF.Reports.ci-utility-certificate', compact('exam_data', 'formattedDatesString', 'user', 'utility_answer', 'amount', 'amount_in_words','hall_code'))->render();

        // Render the Blade view with the exam data
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
        $filename = 'utilization-report-' . time() . '.pdf';

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
}
