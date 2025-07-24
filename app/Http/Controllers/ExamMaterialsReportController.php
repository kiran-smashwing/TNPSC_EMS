<?php

namespace App\Http\Controllers;

use App\Models\ExamConfirmedHalls;
use App\Models\ExamMaterialsData;
use Illuminate\Http\Request;
use App\Models\Currentexam;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\DB;
use App\Models\Center;
use Illuminate\Support\Facades\Auth;
use App\Models\District;

class ExamMaterialsReportController extends Controller
{
    public function __construct()
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth.multi');
    }

    public function index()
    {
        $districts = District::all(); // Fetch all districts
        // Fetch unique center values from the same table
        $centers = center::all(); // Fetch all venues
        return view('view_report.exam_materials_report.delivery_report', compact('districts', 'centers')); // Path matches the file created
    }
    public function generatecategorysender(Request $request)
    {
        $category = $request->query('category'); // category can be district, center, or overall

        switch ($category) {
            case 'district':
                return $this->generateDistrictWiseDeliveryReport($request);
                break;

            case 'center':
                return $this->generateCenterWiseDeliveryReport($request);
                break;

            case 'all':
                return $this->generateDeliveryReport($request);
                break;

            default:
                return back()->with('error', 'Invalid category specified.');
        }
    }
    public function collectionReport()
    {
        $districts = District::all(); // Fetch all districts
        // Fetch unique center values from the same table
        $centers = center::all(); // Fetch all venues
        return view('view_report.exam_materials_report.collection_report', compact('districts', 'centers')); // Path matches the file created
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
    public function generateDeliveryReport(Request $request)
    {
        $notificationNo = $request->query('notification_no');
        $examDate = $request->query('exam_date');

        // Fetch the exam details
        $exam_data = Currentexam::with('examservice')
            ->where('exam_main_notification', $notificationNo)
            ->first();

        if (!$exam_data) {
            return back()->with('error', 'Exam data not found.');
        }

        $examId = $exam_data->exam_main_no;

        // Fetch exam materials data with related scans
        $examMaterials = ExamMaterialsData::where('exam_id', $examId)
            ->where('exam_date', $examDate)
            ->whereIn('category', ['D1', 'D2'])
            ->with(['examMaterialsScan', 'center', 'district', 'ci', 'venue'])
            ->get()
            ->sortBy('center_code')
            ->sortBy('hall_code');
        // dd($examMaterials);
        if ($examMaterials->isEmpty()) {
            return back()->with('error', 'No exam materials data found for the given exam ID.');
        }

        // Prepare the session data for the report
        $session_data = [];
        foreach ($examMaterials as $material) {
            $scan = $material->examMaterialsScan;

            // Create a session entry
            $sessionEntry = [
                'district_code' => $material->district_code,
                'district_name' => $material->district->district_name,
                'center_name' => $material->center->center_name,
                'center_code' => $material->center_code,
                'hall_code' => $material->hall_code,
                'venue_name' => $material->venue->venue_name,
                'exam_date' => $material->exam_date,
                'exam_session' => $material->exam_session,
                'qr_code' => $material->qr_code,
                'category' => $material->category,
                'district_scanned_at' => $scan->district_scanned_at ?? false
                    ? \Carbon\Carbon::parse($scan->district_scanned_at)->format('d-m-Y - h:i:s A')
                    : 'N/A',
                'center_scanned_at' => $scan->center_scanned_at ?? false
                    ? \Carbon\Carbon::parse($scan->center_scanned_at)->format('d-m-Y - h:i:s A')
                    : 'N/A',
                'mobile_team_scanned_at' => $scan->mobile_team_scanned_at ?? false
                    ? \Carbon\Carbon::parse($scan->mobile_team_scanned_at)->format('d-m-Y - h:i:s A')
                    : 'N/A',
                'ci_scanned_at' => $scan->ci_scanned_at ?? false
                    ? \Carbon\Carbon::parse($scan->ci_scanned_at)->format('d-m-Y - h:i:s A')
                    : 'N/A',
            ];

            $session_data[] = $sessionEntry;
        }


        // Group session data by district
        $grouped_data = collect($session_data)->groupBy('district_name')->map(function ($items) {
            // Calculate totals and percentages for the district
            $totalMaterials = $items->count();
            $districtScannedCount = $items->filter(function ($item) {
                return $item['district_scanned_at'] !== 'N/A';
            })->count();
            $centerScannedCount = $items->filter(function ($item) {
                return $item['center_scanned_at'] !== 'N/A';
            })->count();
            $mobileTeamScannedCount = $items->filter(function ($item) {
                return $item['mobile_team_scanned_at'] !== 'N/A';
            })->count();
            $ciScannedCount = $items->filter(function ($item) {
                return $item['ci_scanned_at'] !== 'N/A';
            })->count();

            $districtPercentage = $totalMaterials > 0
                ? number_format(($districtScannedCount / $totalMaterials) * 100, 2) . '%'
                : '0%';
            $centerPercentage = $totalMaterials > 0
                ? number_format(($centerScannedCount / $totalMaterials) * 100, 2) . '%'
                : '0%';
            $mobileTeamPercentage = $totalMaterials > 0
                ? number_format(($mobileTeamScannedCount / $totalMaterials) * 100, 2) . '%'
                : '0%';

            $ciPercentage = $totalMaterials > 0
                ? number_format(($ciScannedCount / $totalMaterials) * 100, 2) . '%'
                : '0%';

            return [
                'materials' => $items,
                'totals' => [
                    'total_materials' => $totalMaterials,
                    'district_scanned_count' => $districtScannedCount,
                    'district_percentage' => $districtPercentage,
                    'center_scanned_count' => $centerScannedCount,
                    'center_percentage' => $centerPercentage,
                    'mobile_team_scanned_count' => $mobileTeamScannedCount,
                    'mobile_team_percentage' => $mobileTeamPercentage,
                    'ci_scanned_count' => $ciScannedCount,
                    'ci_percentage' => $ciPercentage,
                ],
            ];
        });
        // Calculate overall district summary
        $overallMaterials = 0;
        $overallDistrictScanned = 0;
        $overallCenterScanned = 0;
        $overallMobileTeamScanned = 0;
        $overallCiScanned = 0;

        foreach ($grouped_data as $districtSummary) {
            $totals = $districtSummary['totals'];
            $overallMaterials += $totals['total_materials'];
            $overallDistrictScanned += $totals['district_scanned_count'];
            $overallCenterScanned += $totals['center_scanned_count'];
            $overallMobileTeamScanned += $totals['mobile_team_scanned_count'];
            $overallCiScanned += $totals['ci_scanned_count'];
        }

        $overallSummary = [
            'total_materials' => $overallMaterials,
            'district_scanned_count' => $overallDistrictScanned,
            'district_percentage' => $overallMaterials > 0 ? number_format(($overallDistrictScanned / $overallMaterials) * 100, 2) . '%' : '0%',
            'center_scanned_count' => $overallCenterScanned,
            'center_percentage' => $overallMaterials > 0 ? number_format(($overallCenterScanned / $overallMaterials) * 100, 2) . '%' : '0%',
            'mobile_team_scanned_count' => $overallMobileTeamScanned,
            'mobile_team_percentage' => $overallMaterials > 0 ? number_format(($overallMobileTeamScanned / $overallMaterials) * 100, 2) . '%' : '0%',
            'ci_scanned_count' => $overallCiScanned,
            'ci_percentage' => $overallMaterials > 0 ? number_format(($overallCiScanned / $overallMaterials) * 100, 2) . '%' : '0%',
        ];


        // Prepare final data for Blade
        $data = [
            'notification_no' => $notificationNo,
            'exam_date' => $examDate,
            'exam_data' => $exam_data,
            'exam_id' => $examId,
            'grouped_data' => $grouped_data,
            'grand_total' => $overallSummary,
        ];

        // Render the Blade template
        $html = view('view_report.exam_materials_report.delivery_report_pdf', $data)->render();

        // Generate PDF using Browsershot
        $pdf = Browsershot::html($html)
            ->setOption('landscape', true)
            ->setOption('margin', [
                'top' => '10mm',
                'right' => '10mm',
                'bottom' => '10mm',
                'left' => '10mm',
            ])
            ->setOption('displayHeaderFooter', true)
            ->setOption('headerTemplate', '<div></div>')
            ->setOption('footerTemplate', '
            <div style="font-size:10px;width:100%;text-align:center;">
                Page <span class="pageNumber"></span> of <span class="totalPages"></span>
            </div>
            <div style="position: absolute; bottom: 5mm; right: 10px; font-size: 10px;">
                IP: ' . $_SERVER['REMOTE_ADDR'] . ' | Timestamp: ' . date('d-m-Y H:i:s') . '
            </div>')
            ->setOption('preferCSSPageSize', true)
            ->setOption('printBackground', true)
            ->scale(1)
            ->format('A4')
            ->pdf();

        // Define a unique filename for the report
        $filename = 'delivery_report_' . $examId . '_' . time() . '.pdf';

        // Return the PDF as a response
        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }
    public function generateDistrictWiseDeliveryReport(Request $request)
    {
        $notificationNo = $request->query('notification_no');
        $examDate = $request->query('exam_date');
        $districtCode = $request->query('district'); // Optional: specific district

        // Fetch the exam details
        $exam_data = Currentexam::with('examservice')
            ->where('exam_main_notification', $notificationNo)
            ->first();

        if (!$exam_data) {
            return back()->with('error', 'Exam data not found.');
        }

        $examId = $exam_data->exam_main_no;

        // Build query for exam materials
        $query = ExamMaterialsData::where('exam_id', $examId)
            ->where('exam_date', $examDate)
            ->whereIn('category', ['D1', 'D2'])
            ->with(['examMaterialsScan', 'center', 'district', 'ci', 'venue']);

        // Filter by specific district if provided
        if ($districtCode) {
            $query->where('district_code', $districtCode);
        }

        $examMaterials = $query->get()
            ->sortBy('center_code')
            ->sortBy('hall_code');

        if ($examMaterials->isEmpty()) {
            return back()->with('error', 'No exam materials data found for the given criteria.');
        }

        // Prepare the session data for the report
        $session_data = [];
        foreach ($examMaterials as $material) {
            $scan = $material->examMaterialsScan;

            // Create a session entry
            $sessionEntry = [
                'district_code' => $material->district_code,
                'district_name' => $material->district->district_name,
                'center_name' => $material->center->center_name,
                'center_code' => $material->center_code,
                'hall_code' => $material->hall_code,
                'venue_name' => $material->venue->venue_name,
                'exam_date' => $material->exam_date,
                'exam_session' => $material->exam_session,
                'qr_code' => $material->qr_code,
                'category' => $material->category,
                'district_scanned_at' => $scan->district_scanned_at ?? false
                    ? \Carbon\Carbon::parse($scan->district_scanned_at)->format('d-m-Y - h:i:s A')
                    : 'N/A',
                'center_scanned_at' => $scan->center_scanned_at ?? false
                    ? \Carbon\Carbon::parse($scan->center_scanned_at)->format('d-m-Y - h:i:s A')
                    : 'N/A',
                'mobile_team_scanned_at' => $scan->mobile_team_scanned_at ?? false
                    ? \Carbon\Carbon::parse($scan->mobile_team_scanned_at)->format('d-m-Y - h:i:s A')
                    : 'N/A',
                'ci_scanned_at' => $scan->ci_scanned_at ?? false
                    ? \Carbon\Carbon::parse($scan->ci_scanned_at)->format('d-m-Y - h:i:s A')
                    : 'N/A',
            ];

            $session_data[] = $sessionEntry;
        }

        // Group session data by district
        $grouped_data = collect($session_data)->groupBy('district_name')->map(function ($items) {
            // Calculate totals and percentages for the district
            $totalMaterials = $items->count();
            $districtScannedCount = $items->filter(function ($item) {
                return $item['district_scanned_at'] !== 'N/A';
            })->count();
            $centerScannedCount = $items->filter(function ($item) {
                return $item['center_scanned_at'] !== 'N/A';
            })->count();
            $mobileTeamScannedCount = $items->filter(function ($item) {
                return $item['mobile_team_scanned_at'] !== 'N/A';
            })->count();
            $ciScannedCount = $items->filter(function ($item) {
                return $item['ci_scanned_at'] !== 'N/A';
            })->count();

            $districtPercentage = $totalMaterials > 0
                ? number_format(($districtScannedCount / $totalMaterials) * 100, 2) . '%'
                : '0%';
            $centerPercentage = $totalMaterials > 0
                ? number_format(($centerScannedCount / $totalMaterials) * 100, 2) . '%'
                : '0%';
            $mobileTeamPercentage = $totalMaterials > 0
                ? number_format(($mobileTeamScannedCount / $totalMaterials) * 100, 2) . '%'
                : '0%';
            $ciPercentage = $totalMaterials > 0
                ? number_format(($ciScannedCount / $totalMaterials) * 100, 2) . '%'
                : '0%';

            return [
                'materials' => $items,
                'totals' => [
                    'total_materials' => $totalMaterials,
                    'district_scanned_count' => $districtScannedCount,
                    'district_percentage' => $districtPercentage,
                    'center_scanned_count' => $centerScannedCount,
                    'center_percentage' => $centerPercentage,
                    'mobile_team_scanned_count' => $mobileTeamScannedCount,
                    'mobile_team_percentage' => $mobileTeamPercentage,
                    'ci_scanned_count' => $ciScannedCount,
                    'ci_percentage' => $ciPercentage,
                ],
            ];
        });

        // Calculate overall district summary
        $overallMaterials = 0;
        $overallDistrictScanned = 0;
        $overallCenterScanned = 0;
        $overallMobileTeamScanned = 0;
        $overallCiScanned = 0;

        foreach ($grouped_data as $districtSummary) {
            $totals = $districtSummary['totals'];
            $overallMaterials += $totals['total_materials'];
            $overallDistrictScanned += $totals['district_scanned_count'];
            $overallCenterScanned += $totals['center_scanned_count'];
            $overallMobileTeamScanned += $totals['mobile_team_scanned_count'];
            $overallCiScanned += $totals['ci_scanned_count'];
        }

        $overallSummary = [
            'total_materials' => $overallMaterials,
            'district_scanned_count' => $overallDistrictScanned,
            'district_percentage' => $overallMaterials > 0 ? number_format(($overallDistrictScanned / $overallMaterials) * 100, 2) . '%' : '0%',
            'center_scanned_count' => $overallCenterScanned,
            'center_percentage' => $overallMaterials > 0 ? number_format(($overallCenterScanned / $overallMaterials) * 100, 2) . '%' : '0%',
            'mobile_team_scanned_count' => $overallMobileTeamScanned,
            'mobile_team_percentage' => $overallMaterials > 0 ? number_format(($overallMobileTeamScanned / $overallMaterials) * 100, 2) . '%' : '0%',
            'ci_scanned_count' => $overallCiScanned,
            'ci_percentage' => $overallMaterials > 0 ? number_format(($overallCiScanned / $overallMaterials) * 100, 2) . '%' : '0%',
        ];

        // Prepare final data for Blade
        $data = [
            'notification_no' => $notificationNo,
            'exam_date' => $examDate,
            'exam_data' => $exam_data,
            'exam_id' => $examId,
            'grouped_data' => $grouped_data,
            'grand_total' => $overallSummary,
            'district_code' => $districtCode,
            'is_district_specific' => !is_null($districtCode),
        ];

        // Render the Blade template
        $html = view('view_report.exam_materials_report.delivery_report_district_pdf', $data)->render();

        // Generate PDF using Browsershot
        $pdf = Browsershot::html($html)
            ->setOption('landscape', true)
            ->setOption('margin', [
                'top' => '10mm',
                'right' => '10mm',
                'bottom' => '10mm',
                'left' => '10mm',
            ])
            ->setOption('displayHeaderFooter', true)
            ->setOption('headerTemplate', '<div></div>')
            ->setOption('footerTemplate', '
        <div style="font-size:10px;width:100%;text-align:center;">
            Page <span class="pageNumber"></span> of <span class="totalPages"></span>
        </div>
        <div style="position: absolute; bottom: 5mm; right: 10px; font-size: 10px;">
            ' . ($districtCode ? 'District: ' . $districtCode . ' | ' : '') . 'IP: ' . $_SERVER['REMOTE_ADDR'] . ' | Timestamp: ' . date('d-m-Y H:i:s') . '
        </div>')
            ->setOption('preferCSSPageSize', true)
            ->setOption('printBackground', true)
            ->scale(1)
            ->format('A4')
            ->pdf();

        // Define a unique filename for the report
        $filename = 'delivery_report_' . ($districtCode ? $districtCode . '_' : '') . $examId . '_' . time() . '.pdf';

        // Return the PDF as a response
        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }
    public function generateCenterWiseDeliveryReport(Request $request)
    {
        $notificationNo = $request->query('notification_no');
        $examDate = $request->query('exam_date');
        $districtCode = $request->query('district');
        $centerCode = $request->query('center');

        // Fetch the exam details
        $exam_data = Currentexam::with('examservice')
            ->where('exam_main_notification', $notificationNo)
            ->first();

        if (!$exam_data) {
            return back()->with('error', 'Exam data not found.');
        }

        $examId = $exam_data->exam_main_no;

        // Build query for exam materials
        $query = ExamMaterialsData::where('exam_id', $examId)
            ->where('exam_date', $examDate)
            ->whereIn('category', ['D1', 'D2'])
            ->with(['examMaterialsScan', 'center', 'district', 'ci', 'venue']);

        if ($districtCode) {
            $query->where('district_code', $districtCode);
        }

        if ($centerCode) {
            $query->where('center_code', $centerCode);
        }

        $examMaterials = $query->get()
            ->sortBy('center_code')
            ->sortBy('hall_code');

        if ($examMaterials->isEmpty()) {
            return back()->with('error', 'No exam materials data found for the given criteria.');
        }

        // Prepare the session data for the report
        $session_data = [];
        foreach ($examMaterials as $material) {
            $scan = $material->examMaterialsScan ?? (object)[]; // Handle null scan

            $sessionEntry = [
                'district_code' => $material->district_code ?? '',
                'district_name' => $material->district->district_name ?? '',
                'center_name' => $material->center->center_name ?? '',
                'center_code' => $material->center_code ?? '',
                'hall_code' => $material->hall_code ?? '',
                'venue_name' => $material->venue->venue_name ?? '',
                'exam_date' => $material->exam_date ?? '',
                'exam_session' => $material->exam_session ?? '',
                'qr_code' => $material->qr_code ?? '',
                'category' => $material->category ?? '',
                'district_scanned_at' => isset($scan->district_scanned_at) && $scan->district_scanned_at
                    ? \Carbon\Carbon::parse($scan->district_scanned_at)->format('d-m-Y - h:i:s A')
                    : 'N/A',
                'center_scanned_at' => isset($scan->center_scanned_at) && $scan->center_scanned_at
                    ? \Carbon\Carbon::parse($scan->center_scanned_at)->format('d-m-Y - h:i:s A')
                    : 'N/A',
                'mobile_team_scanned_at' => isset($scan->mobile_team_scanned_at) && $scan->mobile_team_scanned_at
                    ? \Carbon\Carbon::parse($scan->mobile_team_scanned_at)->format('d-m-Y - h:i:s A')
                    : 'N/A',
                'ci_scanned_at' => isset($scan->ci_scanned_at) && $scan->ci_scanned_at
                    ? \Carbon\Carbon::parse($scan->ci_scanned_at)->format('d-m-Y - h:i:s A')
                    : 'N/A',
            ];

            $session_data[] = $sessionEntry;
        }

        // Group session data by district and then by center with proper initialization
        $grouped_data = collect($session_data)
            ->groupBy('district_name')
            ->map(function ($districtItems, $districtName) {
                $centerGroups = $districtItems->groupBy('center_name')->map(function ($centerItems, $centerName) {
                    // Initialize materials array if not set
                    $materials = $centerItems ?: collect();

                    $totalMaterials = $materials->count();

                    $counts = [
                        'district' => $materials->where('district_scanned_at', '!=', 'N/A')->count(),
                        'center' => $materials->where('center_scanned_at', '!=', 'N/A')->count(),
                        'mobile_team' => $materials->where('mobile_team_scanned_at', '!=', 'N/A')->count(),
                        'ci' => $materials->where('ci_scanned_at', '!=', 'N/A')->count(),
                    ];

                    $percentages = array_map(function ($count) use ($totalMaterials) {
                        return $totalMaterials > 0
                            ? number_format(($count / $totalMaterials) * 100, 2) . '%'
                            : '0%';
                    }, $counts);

                    return [
                        'center_name' => $centerName,
                        'center_code' => $centerItems->first()['center_code'] ?? '',
                        'materials' => $materials,
                        'totals' => [
                            'total_materials' => $totalMaterials,
                            'district_scanned_count' => $counts['district'],
                            'district_percentage' => $percentages['district'],
                            'center_scanned_count' => $counts['center'],
                            'center_percentage' => $percentages['center'],
                            'mobile_team_scanned_count' => $counts['mobile_team'],
                            'mobile_team_percentage' => $percentages['mobile_team'],
                            'ci_scanned_count' => $counts['ci'],
                            'ci_percentage' => $percentages['ci'],
                        ],
                    ];
                });
                

                // Calculate district totals
                $totals = [
                    'total_materials' => $centerGroups->sum('totals.total_materials'),
                    'district_scanned_count' => $centerGroups->sum('totals.district_scanned_count'),
                    'center_scanned_count' => $centerGroups->sum('totals.center_scanned_count'),
                    'mobile_team_scanned_count' => $centerGroups->sum('totals.mobile_team_scanned_count'),
                    'ci_scanned_count' => $centerGroups->sum('totals.ci_scanned_count'),
                ];

                $percentages = array_map(function ($count) use ($totals) {
                    return $totals['total_materials'] > 0
                        ? number_format(($count / $totals['total_materials']) * 100, 2) . '%'
                        : '0%';
                }, [
                    'district' => $totals['district_scanned_count'],
                    'center' => $totals['center_scanned_count'],
                    'mobile_team' => $totals['mobile_team_scanned_count'],
                    'ci' => $totals['ci_scanned_count'],
                ]);

                return [
                    'district_name' => $districtName,
                    'district_code' => $districtItems->first()['district_code'] ?? '',
                    'centers' => $centerGroups,
                    'district_totals' => [
                        'total_materials' => $totals['total_materials'],
                        'district_scanned_count' => $totals['district_scanned_count'],
                        'district_percentage' => $percentages['district'],
                        'center_scanned_count' => $totals['center_scanned_count'],
                        'center_percentage' => $percentages['center'],
                        'mobile_team_scanned_count' => $totals['mobile_team_scanned_count'],
                        'mobile_team_percentage' => $percentages['mobile_team'],
                        'ci_scanned_count' => $totals['ci_scanned_count'],
                        'ci_percentage' => $percentages['ci'],
                    ],
                ];
            });

        // Filter grouped data if district or center specified
        if ($districtCode) {
            $districtName = $examMaterials->first()->district->district_name ?? '';
            $grouped_data = $grouped_data->filter(fn($item) => $item['district_name'] === $districtName);

            if ($centerCode) {
                $centerName = $examMaterials->first()->center->center_name ?? '';
                $grouped_data = $grouped_data->map(function ($district) use ($centerName) {
                    $district['centers'] = $district['centers']->filter(fn($center) => $center['center_name'] === $centerName);

                    // Recalculate district totals
                    $totals = [
                        'total_materials' => $district['centers']->sum('totals.total_materials'),
                        'district_scanned_count' => $district['centers']->sum('totals.district_scanned_count'),
                        'center_scanned_count' => $district['centers']->sum('totals.center_scanned_count'),
                        'mobile_team_scanned_count' => $district['centers']->sum('totals.mobile_team_scanned_count'),
                        'ci_scanned_count' => $district['centers']->sum('totals.ci_scanned_count'),
                    ];

                    $percentages = array_map(function ($count) use ($totals) {
                        return $totals['total_materials'] > 0
                            ? number_format(($count / $totals['total_materials']) * 100, 2) . '%'
                            : '0%';
                    }, [
                        'district' => $totals['district_scanned_count'],
                        'center' => $totals['center_scanned_count'],
                        'mobile_team' => $totals['mobile_team_scanned_count'],
                        'ci' => $totals['ci_scanned_count'],
                    ]);

                    $district['district_totals'] = [
                        'total_materials' => $totals['total_materials'],
                        'district_scanned_count' => $totals['district_scanned_count'],
                        'district_percentage' => $percentages['district'],
                        'center_scanned_count' => $totals['center_scanned_count'],
                        'center_percentage' => $percentages['center'],
                        'mobile_team_scanned_count' => $totals['mobile_team_scanned_count'],
                        'mobile_team_percentage' => $percentages['mobile_team'],
                        'ci_scanned_count' => $totals['ci_scanned_count'],
                        'ci_percentage' => $percentages['ci'],
                    ];

                    return $district;
                });
            }
        }

        // Calculate overall summary
        $overall = [
            'total_materials' => $grouped_data->sum('district_totals.total_materials'),
            'district_scanned_count' => $grouped_data->sum('district_totals.district_scanned_count'),
            'center_scanned_count' => $grouped_data->sum('district_totals.center_scanned_count'),
            'mobile_team_scanned_count' => $grouped_data->sum('district_totals.mobile_team_scanned_count'),
            'ci_scanned_count' => $grouped_data->sum('district_totals.ci_scanned_count'),
        ];

        $overallPercentages = array_map(function ($count) use ($overall) {
            return $overall['total_materials'] > 0
                ? number_format(($count / $overall['total_materials']) * 100, 2) . '%'
                : '0%';
        }, [
            'district' => $overall['district_scanned_count'],
            'center' => $overall['center_scanned_count'],
            'mobile_team' => $overall['mobile_team_scanned_count'],
            'ci' => $overall['ci_scanned_count'],
        ]);

        $overallSummary = [
            'total_materials' => $overall['total_materials'],
            'district_scanned_count' => $overall['district_scanned_count'],
            'district_percentage' => $overallPercentages['district'],
            'center_scanned_count' => $overall['center_scanned_count'],
            'center_percentage' => $overallPercentages['center'],
            'mobile_team_scanned_count' => $overall['mobile_team_scanned_count'],
            'mobile_team_percentage' => $overallPercentages['mobile_team'],
            'ci_scanned_count' => $overall['ci_scanned_count'],
            'ci_percentage' => $overallPercentages['ci'],
        ];

        // Prepare final data
        $data = [
            'notification_no' => $notificationNo,
            'exam_date' => $examDate,
            'exam_data' => $exam_data,
            'exam_id' => $examId,
            'grouped_data' => $grouped_data,
            'grand_total' => $overallSummary,
            'district_code' => $districtCode,
            'center_code' => $centerCode,
            'center_name' => $centerCode ? $examMaterials->first()->center->center_name : null,
            'is_district_specific' => !is_null($districtCode),
            'is_center_specific' => !is_null($centerCode),
        ];

        // Generate PDF
        $html = view('view_report.exam_materials_report.delivery_report_center_pdf', $data)->render();

        $pdf = Browsershot::html($html)
            ->setOption('landscape', true)
            ->setOption('margin', ['top' => '10mm', 'right' => '10mm', 'bottom' => '10mm', 'left' => '10mm'])
            ->setOption('displayHeaderFooter', true)
            ->setOption('headerTemplate', '<div></div>')
            ->setOption('footerTemplate', '
            <div style="font-size:10px;width:100%;text-align:center;">
                Page <span class="pageNumber"></span> of <span class="totalPages"></span>
            </div>
            <div style="position: absolute; bottom: 5mm; right: 10px; font-size: 10px;">
                ' . ($districtCode ? 'District: ' . $districtCode . ' | ' : '') .
                ($centerCode ? 'Center: ' . $centerCode . ' | ' : '') .
                'IP: ' . ($_SERVER['REMOTE_ADDR'] ?? '') . ' | Timestamp: ' . date('d-m-Y H:i:s') . '
            </div>')
            ->setOption('preferCSSPageSize', true)
            ->setOption('printBackground', true)
            ->scale(1)
            ->format('A4')
            ->pdf();

        $filename = 'delivery_report_' .
            ($districtCode ? $districtCode . '_' : '') .
            ($centerCode ? $centerCode . '_' : '') .
            $examId . '_' . time() . '.pdf';

        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }
    public function generateCollectionReport(Request $request)
    {
        $notificationNo = $request->query('notification_no');
        $examDate = $request->query('exam_date');

        // Fetch the exam details
        $exam_data = Currentexam::with('examservice')
            ->where('exam_main_notification', $notificationNo)
            ->first();

        if (!$exam_data) {
            return back()->with('error', 'Exam data not found.');
        }

        $examId = $exam_data->exam_main_no;

        // Fetch exam materials data with related scans
        $examMaterials = ExamMaterialsData::where('exam_id', $examId)
            ->where('exam_date', $examDate)
            ->whereNotIn('category', ['D1', 'D2'])
            ->with(['examMaterialsScan', 'center', 'district', 'ci', 'venue'])
            ->get()
            ->sortBy('center_code')
            ->sortBy('hall_code');

        if ($examMaterials->isEmpty()) {
            return back()->with('error', 'No exam materials data found for the given exam ID.');
        }

        // Prepare the session data for the report
        $session_data = [];
        foreach ($examMaterials as $material) {
            $scan = $material->examMaterialsScan;

            // Create a session entry
            $sessionEntry = [
                'district_code' => $material->district_code,
                'district_name' => $material->district->district_name,
                'center_name' => $material->center->center_name,
                'center_code' => $material->center_code,
                'hall_code' => $material->hall_code,
                'venue_name' => $material->venue->venue_name,
                'exam_date' => $material->exam_date,
                'exam_session' => $material->exam_session,
                'qr_code' => $material->qr_code,
                'category' => $material->category,
                'district_scanned_at' => $scan->district_scanned_at ?? false
                    ? \Carbon\Carbon::parse($scan->district_scanned_at)->format('d-m-Y - h:i:s A')
                    : 'N/A',
                'center_scanned_at' => $scan->center_scanned_at ?? false
                    ? \Carbon\Carbon::parse($scan->center_scanned_at)->format('d-m-Y - h:i:s A')
                    : 'N/A',
                'mobile_team_scanned_at' => $scan->mobile_team_scanned_at ?? false
                    ? \Carbon\Carbon::parse($scan->mobile_team_scanned_at)->format('d-m-Y - h:i:s A')
                    : 'N/A',
                'ci_scanned_at' => $scan->ci_scanned_at ?? false
                    ? \Carbon\Carbon::parse($scan->ci_scanned_at)->format('d-m-Y - h:i:s A')
                    : 'N/A',
            ];

            $session_data[] = $sessionEntry;
        }


        // Group session data by district
        $grouped_data = collect($session_data)->groupBy('district_name')->map(function ($items) {
            // Calculate totals and percentages for the district
            $totalMaterials = $items->count();
            $districtScannedCount = $items->filter(function ($item) {
                return $item['district_scanned_at'] !== 'N/A';
            })->count();
            $centerScannedCount = $items->filter(function ($item) {
                return $item['center_scanned_at'] !== 'N/A';
            })->count();
            $mobileTeamScannedCount = $items->filter(function ($item) {
                return $item['mobile_team_scanned_at'] !== 'N/A';
            })->count();
            $ciScannedCount = $items->filter(function ($item) {
                return $item['ci_scanned_at'] !== 'N/A';
            })->count();

            $districtPercentage = $totalMaterials > 0
                ? number_format(($districtScannedCount / $totalMaterials) * 100, 2) . '%'
                : '0%';
            $centerPercentage = $totalMaterials > 0
                ? number_format(($centerScannedCount / $totalMaterials) * 100, 2) . '%'
                : '0%';
            $mobileTeamPercentage = $totalMaterials > 0
                ? number_format(($mobileTeamScannedCount / $totalMaterials) * 100, 2) . '%'
                : '0%';

            $ciPercentage = $totalMaterials > 0
                ? number_format(($ciScannedCount / $totalMaterials) * 100, 2) . '%'
                : '0%';

            return [
                'materials' => $items,
                'totals' => [
                    'total_materials' => $totalMaterials,
                    'district_scanned_count' => $districtScannedCount,
                    'district_percentage' => $districtPercentage,
                    'center_scanned_count' => $centerScannedCount,
                    'center_percentage' => $centerPercentage,
                    'mobile_team_scanned_count' => $mobileTeamScannedCount,
                    'mobile_team_percentage' => $mobileTeamPercentage,
                    'ci_scanned_count' => $ciScannedCount,
                    'ci_percentage' => $ciPercentage,
                ],
            ];
        });
        // Calculate overall district summary
        $overallMaterials = 0;
        $overallDistrictScanned = 0;
        $overallCenterScanned = 0;
        $overallMobileTeamScanned = 0;
        $overallCiScanned = 0;

        foreach ($grouped_data as $districtSummary) {
            $totals = $districtSummary['totals'];
            $overallMaterials += $totals['total_materials'];
            $overallDistrictScanned += $totals['district_scanned_count'];
            $overallCenterScanned += $totals['center_scanned_count'];
            $overallMobileTeamScanned += $totals['mobile_team_scanned_count'];
            $overallCiScanned += $totals['ci_scanned_count'];
        }

        $overallSummary = [
            'total_materials' => $overallMaterials,
            'district_scanned_count' => $overallDistrictScanned,
            'district_percentage' => $overallMaterials > 0 ? number_format(($overallDistrictScanned / $overallMaterials) * 100, 2) . '%' : '0%',
            'center_scanned_count' => $overallCenterScanned,
            'center_percentage' => $overallMaterials > 0 ? number_format(($overallCenterScanned / $overallMaterials) * 100, 2) . '%' : '0%',
            'mobile_team_scanned_count' => $overallMobileTeamScanned,
            'mobile_team_percentage' => $overallMaterials > 0 ? number_format(($overallMobileTeamScanned / $overallMaterials) * 100, 2) . '%' : '0%',
            'ci_scanned_count' => $overallCiScanned,
            'ci_percentage' => $overallMaterials > 0 ? number_format(($overallCiScanned / $overallMaterials) * 100, 2) . '%' : '0%',
        ];


        // Prepare final data for Blade
        $data = [
            'notification_no' => $notificationNo,
            'exam_date' => $examDate,
            'exam_data' => $exam_data,
            'exam_id' => $examId,
            'grouped_data' => $grouped_data,
            'grand_total' => $overallSummary,
        ];

        // Render the Blade template
        $html = view('view_report.exam_materials_report.collection_report_pdf', $data)->render();

        // Generate PDF using Browsershot
        $pdf = Browsershot::html($html)
            ->setOption('landscape', true)
            ->setOption('margin', [
                'top' => '10mm',
                'right' => '10mm',
                'bottom' => '10mm',
                'left' => '10mm',
            ])
            ->setOption('displayHeaderFooter', true)
            ->setOption('headerTemplate', '<div></div>')
            ->setOption('footerTemplate', '
            <div style="font-size:10px;width:100%;text-align:center;">
                Page <span class="pageNumber"></span> of <span class="totalPages"></span>
            </div>
            <div style="position: absolute; bottom: 5mm; right: 10px; font-size: 10px;">
                IP: ' . $_SERVER['REMOTE_ADDR'] . ' | Timestamp: ' . date('d-m-Y H:i:s') . '
            </div>')
            ->setOption('preferCSSPageSize', true)
            ->setOption('printBackground', true)
            ->scale(1)
            ->format('A4')
            ->pdf();

        // Define a unique filename for the report
        $filename = 'collection_report_' . $examId . '_' . time() . '.pdf';

        // Return the PDF as a response
        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }
}
