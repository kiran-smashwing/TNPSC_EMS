<?php

namespace App\Http\Controllers;

use App\Models\ChartedVehicleRoute;
use App\Models\ExamMaterialsScan;
use App\Models\ExamMaterialsData;
use App\Models\Currentexam;
use App\Models\ExamSession;
use App\Models\ExamTrunkBoxOTLData;
use App\Models\ExamTrunkBoxScan;
use App\Services\ExamAuditService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Spatie\Browsershot\Browsershot;

class BundlePackagingController extends Controller
{
    public function __construct()
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth.multi');
    }
    public function ciBundlepackagingView(Request $request, $examId, $exam_date, $exam_session)
    {
        $role = session('auth_role');
        $guard = $role ? Auth::guard($role) : null;
        $user = $guard ? $guard->user() : null;

        // Define the category mapping
        $exam_session = ExamSession::where('exam_sess_mainid', $examId)
            ->where('exam_sess_date', $exam_date)
            ->where('exam_sess_session', $exam_session)
            ->first();
        if ($exam_session->exam_sess_type == 'Descriptive') {
            $categoryLabels = [
                'R1' => 'Bundle IA',
                'R2' => 'Bundle IB',
                'R3' => 'Bundle II',
                'R4' => 'Bundle III',
                'R5' => 'Bundle IV',
                'R6' => 'Cover C',
            ];
        } else {
            $categoryLabels = [
                'R3' => 'Bundle I',
                'R4' => 'Bundle II',
                'R5' => 'Bundle C',
            ];
        }
        $query = $role == 'ci'
            ? ExamMaterialsData::where('exam_id', $examId)
                ->where('ci_id', $user->ci_id)
                ->whereIn('category', array_keys($categoryLabels))
                ->whereDate('exam_date', $exam_date)
                ->where('exam_session', $exam_session->exam_sess_session)
            : ExamMaterialsData::where('exam_id', $examId)
                ->whereIn('category', array_keys($categoryLabels));
        
        $examMaterials = $query->with(relations: ['examMaterialsScan'])->get();
        // Add label mapping to the data
        $examMaterials->each(function ($material) use ($categoryLabels) {
            $material->bundle_label = $categoryLabels[$material->category] ?? 'Unknown Bundle';
        });
        // dd($examMaterials);
        return view('my_exam.BundlePackaging.ci-bundle-packaging', compact('examMaterials', 'examId', 'exam_date'));
    }
    public function CItoMobileTeam(Request $request, $examId, $examDate)
    {
        $role = session('auth_role');
        $guard = $role ? Auth::guard($role) : null;
        $user = $guard ? $guard->user() : null;
        $examDate = Carbon::parse($examDate)->format('Y-m-d');

        // Define the category mapping
        $categoryLabels = [
            'R3' => 'Bundle I',
            'R4' => 'Bundle II',
            'R5' => 'Bundle C',
        ];
        $query = '';
        if ($role == 'mobile_team_staffs') {
            $query = ExamMaterialsData::where('exam_id', $examId)
                ->where('mobile_team_id', $user->mobile_id)
                ->whereDate('exam_date', $examDate)
                ->whereIn('category', array_keys($categoryLabels));
        } elseif ($role == 'headquarters' && $user->role->role_name == 'Van Duty Staff') {
            $query = ExamMaterialsData::where('exam_id', $examId)
                ->where('district_code', '01')
                ->where('mobile_team_id', $user->dept_off_id)
                ->whereDate('exam_date', $examDate)
                ->whereIn('category', array_keys($categoryLabels));
        } else {
            $query = ExamMaterialsData::where('exam_id', $examId)
                ->whereIn('category', array_keys($categoryLabels));
        }
        // Apply filters 
        if ($request->has('centerCode') && !empty($request->centerCode)) {
            $query->where('center_code', $request->centerCode);
        }
        if ($request->has('examSession') && !empty($request->examSession)) {
            $query->where('exam_session', $request->examSession);
        }
        $examMaterials = $query
            ->with([
                'center',
                'examMaterialsScan'
            ])
            ->get();


        //total number of exam materials found for this user
        $totalExamMaterials = $examMaterials->count();
        //total number of exam materials scanned by the user
        $totalScanned = $examMaterials->filter(function ($examMaterial) {
            return $examMaterial->examMaterialsScan &&
                $examMaterial->examMaterialsScan->mobile_team_scanned_at;
        })->count();
        //get center code for the user 
        $centers = ExamMaterialsData::where('exam_id', $examId)
            ->where('district_code', $role == 'headquarters' ? '01' : $user->district_code)
            ->join('centers', 'exam_materials_data.center_code', '=', 'centers.center_code')
            ->groupBy('centers.center_code', 'centers.center_name')
            ->select('centers.center_name', 'centers.center_code')
            ->get();
        // Add label mapping to the data
        $examMaterials->each(function ($material) use ($categoryLabels) {
            $material->bundle_label = $categoryLabels[$material->category] ?? 'Unknown Bundle';
        });
        return view('my_exam.BundlePackaging.ci-to-mobileteam-bundle', compact('examMaterials', 'examId', 'examDate', 'totalExamMaterials', 'totalScanned', 'centers'));
    }
    public function MobileTeamtoDistrict(Request $request, $examId)
    {
        $role = session('auth_role');
        $guard = $role ? Auth::guard($role) : null;
        $user = $guard ? $guard->user() : null;
        // $examDate = Carbon::parse($examDate)->format('Y-m-d');

        // Define the category mapping
        $categoryLabels = [
            'R3' => 'Bundle I',
            'R4' => 'Bundle II',
            'R5' => 'Bundle C',
        ];
        $query = $role == 'district'
            ? ExamMaterialsData::where('exam_id', $examId)
                ->where('district_code', $user->district_code)
                ->whereIn('category', array_keys($categoryLabels))
            : ExamMaterialsData::where('exam_id', $examId)
                ->whereIn('category', array_keys($categoryLabels));

        // Apply filters 
        if ($request->has('centerCode') && !empty($request->centerCode)) {
            $query->where('center_code', $request->centerCode);
        }
        if ($request->has('examDate') && !empty($request->examDate)) {
            $query->whereDate('exam_date', $request->examDate);
        }
        $examMaterials = $query
            ->with([
                'center',
                'examMaterialsScan'
            ])
            ->get();

        //total number of exam materials found for this user
        $totalExamMaterials = $examMaterials->count();
        //total number of exam materials scanned by the user
        $totalScanned = $examMaterials->filter(function ($examMaterial) {
            return $examMaterial->examMaterialsScan &&
                $examMaterial->examMaterialsScan->district_scanned_at;
        })->count();
        //get center code for the user 
        $centers = ExamMaterialsData::where('exam_id', $examId)
            ->where('district_code', $user->district_code)
            ->join('centers', 'exam_materials_data.center_code', '=', 'centers.center_code')
            ->groupBy('centers.center_code', 'centers.center_name')
            ->select('centers.center_name', 'centers.center_code')
            ->get();
        // Get current exam session details
        $session = Currentexam::with('examsession')->where('exam_main_no', $examId)->first();
        $examDates = $session->examsession->groupBy(function ($item) {
            return Carbon::parse($item->exam_sess_date)->format('d-m-Y');
        })->keys();
        // Add label mapping to the data
        $examMaterials->each(function ($material) use ($categoryLabels) {
            $material->bundle_label = $categoryLabels[$material->category] ?? 'Unknown Bundle';
        });
        return view('my_exam.BundlePackaging.mobileteam-to-disitrict-bundle', compact('examMaterials', 'examId', 'totalExamMaterials', 'totalScanned', 'centers', 'examDates'));
    }
    public function scanDistrictExamMaterials($examId, Request $request)
    {
        // Validate request
        $request->validate([
            'qr_code' => 'required|string',
        ]);

        // Get authenticated user
        $role = session('auth_role');
        $guard = $role ? Auth::guard($role) : null;
        $user = $guard ? $guard->user() : null;

        // Check authorization
        if ($role !== 'district' || !$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found or not authorized'
            ], 403); // 403 is for authorization errors
        }

        // Find exam materials
        $examMaterials = ExamMaterialsData::where([
            'exam_id' => $examId,
            'district_code' => $user->district_code,
            'qr_code' => $request->qr_code
        ])->first();

        if (!$examMaterials) {
            $examMaterials = ExamMaterialsData::where([
                'exam_id' => $examId,
                'qr_code' => $request->qr_code
            ])
                ->with('center')
                ->with('district')
                ->first();
            $msg = "This Qr Code belongs to the following District : " . $examMaterials->district->district_name . " , Center : " . $examMaterials->center->center_name . " , Hall Code: " . $examMaterials->hall_code;
            return response()->json([
                'status' => 'error',
                'message' => $msg
            ], 404);
        }
        //find trunk box for this exm materials
        $trunkBox = ExamTrunkBoxOTLData::where([
            'exam_id' => $examId,
            'district_code' => $user->district_code,
            'center_code' => $examMaterials->center_code,
            'hall_code' => $examMaterials->hall_code,
        ])->first();

        // Check if already scanned
        if (
            ExamMaterialsScan::where([
                'exam_material_id' => $examMaterials->id,
            ])->exists()
        ) {
            return response()->json([
                'status' => 'error',
                'message' => 'QR code has already been scanned, Place this bundle in this trunk box: ' . $trunkBox->trunkbox_qr_code . '',
            ], 409);
        }

        // Create scan record
        ExamMaterialsScan::create([
            'exam_material_id' => $examMaterials->id,
            'district_scanned_at' => now()
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'QR code scanned successfully, Place this bundle in this trunk box:' . $trunkBox->trunkbox_qr_code . '',
        ], 200);
    }
    public function MobileTeamtoCenter(Request $request, $examId)
    {
        $role = session('auth_role');
        $guard = $role ? Auth::guard($role) : null;
        $user = $guard ? $guard->user() : null;
        // $examDate = Carbon::parse($examDate)->format('Y-m-d');

        // Define the category mapping
        $categoryLabels = [
            'R3' => 'Bundle I',
            'R4' => 'Bundle II',
            'R5' => 'Bundle C',
        ];
        $query = $role == 'district'
            ? ExamMaterialsData::where('exam_id', $examId)
                ->where('center_code', $user->center_code)
                ->whereIn('category', array_keys($categoryLabels))
            : ExamMaterialsData::where('exam_id', $examId)
                ->whereIn('category', array_keys($categoryLabels));

        // Apply filters 
        if ($request->has('examDate') && !empty($request->examDate)) {
            $query->whereDate('exam_date', $request->examDate);
        }
        $examMaterials = $query
            ->with([
                'center',
                'examMaterialsScan'
            ])
            ->get();

        //total number of exam materials found for this user
        $totalExamMaterials = $examMaterials->count();
        //total number of exam materials scanned by the user
        $totalScanned = $examMaterials->filter(function ($examMaterial) {
            return $examMaterial->examMaterialsScan &&
                $examMaterial->examMaterialsScan->center_scanned_at;
        })->count();

        // Get current exam session details
        $session = Currentexam::with('examsession')->where('exam_main_no', $examId)->first();
        $examDates = $session->examsession->groupBy(function ($item) {
            return Carbon::parse($item->exam_sess_date)->format('d-m-Y');
        })->keys();
        // Add label mapping to the data
        $examMaterials->each(function ($material) use ($categoryLabels) {
            $material->bundle_label = $categoryLabels[$material->category] ?? 'Unknown Bundle';
        });
        return view('my_exam.BundlePackaging.mobileteam-to-center-bundle', compact('examMaterials', 'examId', 'totalExamMaterials', 'totalScanned', 'examDates'));
    }
    public function chartedVehicletoHeadquarters(Request $request, $examId)
    {
        $user = $request->get('auth_user');

        $routes = ChartedVehicleRoute::whereJsonContains('exam_id', $examId)->get();
        // Fetching exam notifications 
        foreach ($routes as $route) {
            $examIds = $route->exam_id; // Assuming this is how you fetch the exam IDs array 
            $exams = Currentexam::whereIn('exam_main_no', $examIds)->get();
            $route->exam_notifications = $exams->pluck('exam_main_notification')->implode(', ');
        }
        // Fetching district codes 
        foreach ($routes as $route) {
            $districtCodes = $route->escortstaffs->pluck('district_code')->unique()->toArray();
            $route->district_codes = implode(', ', $districtCodes);
        }
        return view('my_exam.BundlePackaging.vds-to-hq-bundle', compact('routes'));
    }
    public function scanHQExamMaterials(Request $request)
    {
        // Validate request
        $request->validate([
            'qr_code' => 'required|string',
            'exam_id' => 'required|string',
        ]);

        // Decode exam_id from JSON string
        $decodedExamIds = json_decode(htmlspecialchars_decode($request->exam_id), true);

        // Ensure the decoded value is an array
        if (!is_array($decodedExamIds)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid exam_id format'
            ], 400);
        }

        // Find exam materials matching qr_code and exam_id values
        $examMaterials = ExamTrunkBoxOTLData::where('trunkbox_qr_code', $request->qr_code)
            ->whereIn('exam_id', $decodedExamIds)
            ->first();

        if (!$examMaterials) {
            return response()->json([
                'status' => 'error',
                'message' => 'QR code not found for the provided exam IDs.'
            ], 404);
        }

        // Get the next trunk box in reverse order (descending load_order)
        $previousTrunkBox = ExamTrunkBoxOTLData::where('exam_id', $examMaterials->exam_id)
            ->where('district_code', $examMaterials->district_code)
            ->where('load_order', '>', $examMaterials->load_order) // Reverse order scanning
            ->orderBy('load_order') // Ascending order for reverse processing
            ->first();

        // Check if the previous trunk box was scanned
        if ($previousTrunkBox && !ExamTrunkBoxScan::where('exam_trunkbox_id', $previousTrunkBox->id)->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Please scan trunk boxes in the correct order. The next trunk box has not been scanned yet.',
            ], 400);
        }

        // Check if the current trunk box is already scanned
        $existingScan = ExamTrunkBoxScan::where('exam_trunkbox_id', $examMaterials->id)->first();

        if ($existingScan) {
            // Update the existing record with new HQ scan timestamp
            $existingScan->update(['hq_scanned_at' => now()]);
        } else {
            // Create a new scan record
            ExamTrunkBoxScan::create([
                'exam_trunkbox_id' => $examMaterials->id,
                'hq_scanned_at' => now()
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'QR code scanned successfully.',
        ], 200);
    }
    public function saveHandoverDetails(Request $request)
    {
        // Validate request data
        $request->validate([
            'vehicle_id' => 'required|exists:charted_vehicle_routes,id',
            'memory_card_handovered' => 'sometimes|in:on',
            'camera_handovered' => 'sometimes|in:on',
            'confidential_material_offloaded' => 'sometimes|in:on',
            'gps_lock_handovered' => 'sometimes|in:on',
            'final_remarks' => 'nullable|string|max:500',
        ]);

        try {
            // Find the vehicle record
            $vehicle = ChartedVehicleRoute::findOrFail($request->vehicle_id);

            // Prepare data, setting unchecked checkboxes to 'off'
            $handoverDetails = [
                'memory_card_handovered' => $request->has('memory_card_handovered') ? true : false,
                'camera_handovered' => $request->has('camera_handovered') ? true : false,
                'confidential_material_offloaded' => $request->has('confidential_material_offloaded') ? true : false,
                'gps_lock_handovered' => $request->has('gps_lock_handovered') ? true : false,
                'final_remarks' => $request->final_remarks ?? '',
            ];

            // Save data in JSON format
            $vehicle->handover_verification_details = json_encode($handoverDetails);
            $vehicle->save();

            return back()->with('success', 'Handover details saved successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to save handover details. Please try again.');
        }
    }
    public function reportHandoverDetails(Request $request, $id)
    {
        $vehicles = ChartedVehicleRoute::where('id', $id)->with(['escortstaffs.district'])->first();
        $exams = Currentexam::whereIn('exam_main_no', $vehicles->exam_id)->get();
        // dd($vehicles->escortstaffs);
        // return view('PDF.BundlePackaging.handover-verification',compact('vehicles','exams'));
        $html = view('PDF.BundlePackaging.handover-verification', compact('vehicles', 'exams'))->render();
        // $html = view('PDF.Reports.ci-utility-certificate')->render();
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
                 IP: ' . $_SERVER['REMOTE_ADDR'] . ' | Timestamp: ' . date('d-m-Y H:i:s') . ' 
            </div>')
            ->setOption('preferCSSPageSize', true)
            ->setOption('printBackground', true)
            ->scale(1)
            ->format('A4')
            ->pdf();
        // Define a unique filename for the report
        $filename = $vehicles->route_no . '_verify_materials_handover_reprot' . time() . '.pdf';

        // Return the PDF as a response
        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }

}
