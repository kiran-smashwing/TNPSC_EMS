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
    protected $auditService;

    public function __construct(ExamAuditService $auditService)
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth.multi');
        $this->auditService = $auditService;
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
        $categoryArray = ['R1', 'R2', 'R3', 'R4', 'R5', 'R6'];
        // Fetch exam materials based on examId and examDate
        $query = ExamMaterialsData::where('exam_id', $examId)
            ->whereDate('exam_date', $examDate)
            ->whereIn('category', $categoryArray);

        if ($role == 'mobile_team_staffs') {
            $query->where('mobile_team_id', $user->mobile_id);
        } elseif ($role == 'headquarters' && $user->custom_role == 'VDS') {
            $query->where('district_code', '01')
                ->where('mobile_team_id', $user->dept_off_id);
        } else {
            $query = ExamMaterialsData::where('exam_id', $examId);
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
        // Assign bundle labels to each exam material based on its exam session and date
        $examMaterials->each(function ($material) {
            $examSessionData = ExamSession::where('exam_sess_mainid', $material->exam_id)
                ->whereRaw("TO_DATE(exam_sess_date, 'DD-MM-YYYY') = TO_DATE(?, 'YYYY-MM-DD')", [$material->exam_date])
                ->where('exam_sess_session', $material->exam_session)
                ->first();

            // Check if session data exists for the material
            if ($examSessionData) {
                // Define the category labels based on the session type
                if ($examSessionData->exam_sess_type == 'Descriptive') {
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

                // Apply the label from the category mapping for each material
                $material->bundle_label = $categoryLabels[$material->category] ?? $material->category;  // If no match, use the material's category directly
            } else {
                // If no session data found, use the material's category directly
                $material->bundle_label = $material->category;
            }
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
        $categoryArray = ['R1', 'R2', 'R3', 'R4', 'R5', 'R6'];
        // Fetch exam materials based on examId and examDate
        $query = ExamMaterialsData::where('exam_id', $examId)
            ->whereIn('category', $categoryArray);

        $role == 'treasury'
            ? $query->where('district_code', $user->tre_off_district_id)
            : $query->where('district_code', $user->district_code);

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
        // Assign bundle labels to each exam material based on its exam session and date
        $examMaterials->each(function ($material) {
            $examSessionData = ExamSession::where('exam_sess_mainid', $material->exam_id)
                ->whereRaw("TO_DATE(exam_sess_date, 'DD-MM-YYYY') = TO_DATE(?, 'YYYY-MM-DD')", [$material->exam_date])
                ->where('exam_sess_session', $material->exam_session)
                ->first();

            // Check if session data exists for the material
            if ($examSessionData) {
                // Define the category labels based on the session type
                if ($examSessionData->exam_sess_type == 'Descriptive') {
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

                // Apply the label from the category mapping for each material
                $material->bundle_label = $categoryLabels[$material->category] ?? $material->category;  // If no match, use the material's category directly
            } else {
                // If no session data found, use the material's category directly
                $material->bundle_label = $material->category;
            }
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
        if ($role !== 'treasury' || !$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found or not authorized'
            ], 403); // 403 is for authorization errors
        }

        // Find exam materials
        $examMaterials = ExamMaterialsData::where([
            'exam_id' => $examId,
            'district_code' => $user->tre_off_district_id,
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
        $existingScan = ExamMaterialsScan::where([
            'exam_material_id' => $examMaterials->id,
        ])->first();

        // Check if already scanned
        // Check if already scanned
        if (
            ExamMaterialsScan::where(['exam_material_id' => $examMaterials->id])
                ->whereNotNull('district_scanned_at')->exists()
        ) {
            $message = 'QR code has already been scanned';
            if (!is_null($trunkBox)) {
                $message .= ', Place this bundle in this trunk box: ' . $trunkBox->trunkbox_qr_code;
            }
            return response()->json([
                'status' => 'error',
                'message' => $message,
            ], 409);
        }


        // Update the existing record if district_scanned_at is null
        if ($existingScan && !$existingScan->district_scanned_at) {
            $existingScan->update([
                'district_scanned_at' => now()
            ]);
        } else {
            // Create a new record if no existing scan record is found
            ExamMaterialsScan::create([
                'exam_material_id' => $examMaterials->id,
                'district_scanned_at' => now()
            ]);
        }
        // Audit Logging
        $currentUser = current_user();
        $userName = $currentUser ? $currentUser->display_name : 'Unknown';
        $metadata = [
            'user_name' => $userName,
            'district_code' => $currentUser->tre_off_district_id,
        ];

        $examMaterialDetails = [
            'qr_code' => $request->qr_code,
            'district' => $examMaterials->district->district_name,
            'center' => $examMaterials->center->center_name,
            'hall_code' => $examMaterials->hall_code,
            'scan_time' => now()->toDateTimeString()
        ];

        // Check existing log
        $existingLog = $this->auditService->findLog([
            'exam_id' => $examId,
            'task_type' => 'receive_bundle_to_disitrct_treasury',
            'action_type' => 'qr_scan',
            'user_id' => $user->tre_off_id,
        ]);

        if ($existingLog) {
            // Update existing log
            $existingScans = $existingLog->after_state['scanned_codes'] ?? [];
            $firstScan = $existingScans[0] ?? null; // Keep the first scan

            // Update with first and current scan only
            $updatedScans = [
                $firstScan,
                $examMaterialDetails // Current scan becomes the last scan
            ];

            $totalScans = ($existingLog->after_state['total_scanned'] ?? 0) + 1;

            $this->auditService->updateLog(
                logId: $existingLog->id,
                metadata: $metadata,
                afterState: [
                    'scanned_codes' => $updatedScans,
                    'total_scanned' => $totalScans
                ],
                description: "Scanned QR code: {$request->qr_code} (Total scanned: $totalScans)"
            );
        } else {
            // Create new log for first scan
            $this->auditService->log(
                examId: $examId,
                actionType: 'qr_scan',
                taskType: 'receive_bundle_to_disitrct_treasury',
                beforeState: null,
                afterState: [
                    'scanned_codes' => [$examMaterialDetails],
                    'total_scanned' => 1
                ],
                description: "Initial QR code scan: {$request->qr_code}",
                metadata: $metadata
            );
        }
        return response()->json([
            'status' => 'success',
            'message' => 'QR code scanned successfully' .
                (!is_null($trunkBox) ? ', Place this bundle in this trunk box: ' . $trunkBox->trunkbox_qr_code : ''),
        ], 200);

    }
    public function MobileTeamtoCenter(Request $request, $examId)
    {
        $role = session('auth_role');
        $guard = $role ? Auth::guard($role) : null;
        $user = $guard ? $guard->user() : null;
        // $examDate = Carbon::parse($examDate)->format('Y-m-d');


        // Define the category mapping       
        $categoryArray = ['R1', 'R2', 'R3', 'R4', 'R5', 'R6'];
        // Fetch exam materials based on examId and examDate
        $query = ExamMaterialsData::where('exam_id', $examId)
            ->whereIn('category', $categoryArray);

        $role == 'center'
            ? $query->where('center_code', $user->center_code)
            : '';

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
        // Assign bundle labels to each exam material based on its exam session and date
        $examMaterials->each(function ($material) {
            $examSessionData = ExamSession::where('exam_sess_mainid', $material->exam_id)
                ->whereRaw("TO_DATE(exam_sess_date, 'DD-MM-YYYY') = TO_DATE(?, 'YYYY-MM-DD')", [$material->exam_date])
                ->where('exam_sess_session', $material->exam_session)
                ->first();

            // Check if session data exists for the material
            if ($examSessionData) {
                // Define the category labels based on the session type
                if ($examSessionData->exam_sess_type == 'Descriptive') {
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

                // Apply the label from the category mapping for each material
                $material->bundle_label = $categoryLabels[$material->category] ?? $material->category;  // If no match, use the material's category directly
            } else {
                // If no session data found, use the material's category directly
                $material->bundle_label = $material->category;
            }
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
        // Audit Logging
        $user = $request->get('auth_user');

        $userName = $user->display_name ?? 'Unknown';
        $metadata = [
            'user_name' => $userName,
            // Include additional metadata if needed (e.g., HQ-specific fields)
        ];

        // Prepare trunk box details for the audit log
        $trunkBoxDetails = [
            'trunkbox_qr_code' => $examMaterials->trunkbox_qr_code,
            'district' => $examMaterials->district->district_name, // Adjust based on your relationships
            'center' => $examMaterials->center->center_name,
            'hall_code' => $examMaterials->hall_code,
            'scan_time' => now()->toDateTimeString()
        ];

        // Check existing log for the same exam, task type, action type, and user
        $existingLog = $this->auditService->findLog([
            'exam_id' => $examMaterials->exam_id,
            'task_type' => 'receive_trunkbox_at_hq', // Adjust task type as needed
            'action_type' => 'qr_scan',
            'user_id' => $user->dept_off_id, // Adjust user identifier as per your user model
        ]);

        if ($existingLog) {
            // Update existing log
            $existingScans = $existingLog->after_state['scanned_trunkboxes'] ?? [];
            $firstScan = $existingScans[0] ?? null; // Keep the first scan

            $updatedScans = [
                $firstScan,
                $trunkBoxDetails // Add current scan as the latest entry
            ];

            $totalScans = ($existingLog->after_state['total_scanned'] ?? 0) + 1;

            $this->auditService->updateLog(
                logId: $existingLog->id,
                metadata: $metadata,
                afterState: [
                    'scanned_trunkboxes' => $updatedScans,
                    'total_scanned' => $totalScans
                ],
                description: "Scanned trunkbox QR code: {$examMaterials->trunkbox_qr_code} (Total scanned: $totalScans)"
            );
        } else {
            // Create new log for first scan
            $this->auditService->log(
                examId: $examMaterials->exam_id,
                actionType: 'qr_scan',
                taskType: 'receive_trunkbox_at_hq', // Adjust task type as needed
                beforeState: null,
                afterState: [
                    'scanned_trunkboxes' => [$trunkBoxDetails],
                    'total_scanned' => 1
                ],
                description: "Initial trunkbox QR code scan: {$examMaterials->trunkbox_qr_code}",
                metadata: $metadata
            );
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
            $vehicle = ChartedVehicleRoute::where('id', $request->vehicle_id)->first();
            // Capture original state before changes
            $beforeState = $vehicle->handover_verification_details
                ? json_decode($vehicle->handover_verification_details, true)
                : [];
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
            // Prepare audit log metadata
            $user = current_user();
            $metadata = [
                'user_name' => $user->display_name ?? 'Unknown',
                'vehicle_id' => $vehicle->id,
                'route' => $vehicle->route_name,
                'driver' => $vehicle->driver_name,
            ];

            // Normalize the exam_id field into an array
            $examIds = $vehicle->exam_id;
            if (!is_array($examIds)) {
                $examIds = [$examIds];
            }

            $processLogForExamId = function ($examId) use ($beforeState, $handoverDetails, $metadata, $examIds) {
                // Look for an existing audit log entry for this exam ID and task
                $existingLog = $this->auditService->findLog([
                    'exam_id' => $examId,
                    'task_type' => 'materials_handover_verification',
                ]);

                if ($existingLog) {
                    // Update existing log: Preserve initial_state and update last_state.
                    $initialState = $existingLog->after_state['initial_state'] ?? $beforeState;
                    $totalUpdates = ($existingLog->after_state['total_updates'] ?? 0) + 1;

                    $this->auditService->updateLog(
                        logId: $existingLog->id,
                        metadata: $metadata,
                        afterState: [
                            'initial_state' => $initialState,
                            'last_state' => $handoverDetails,
                            'total_updates' => $totalUpdates,
                        ],
                        description: "Handover details updated for vehicle {$metadata['vehicle_id']} on exam {$examId} (Total updates: {$totalUpdates})"
                    );
                } else {
                    // Create new log entry for this exam ID only if more than one exam id is saved.
                    // (If only one exam ID exists, we do not want to create a separate log entry.)
                    if (count($examIds) > 1) {
                        $this->auditService->log(
                            examId: $examId,
                            actionType: 'update',
                            taskType: 'materials_handover_verification',
                            beforeState: null,
                            afterState: [
                                'initial_state' => $handoverDetails,
                                'last_state' => $handoverDetails,
                                'total_updates' => 1,
                            ],
                            description: "Initial handover details saved for vehicle {$metadata['vehicle_id']} on exam {$examId}",
                            metadata: $metadata
                        );
                    }
                    // For a single exam ID, if no log exists, we simply do not create one.
                }
            };

            // Process audit logging:
            // If there is more than one exam ID, create/update logs for each exam ID.
            if (count($examIds) > 1) {
                foreach ($examIds as $examId) {
                    $processLogForExamId($examId);
                }
            } else {
                // If there is only one exam ID, update existing log if it exists,
                // but do not create a new log entry if none exists.
                $examId = $examIds[0];
                $existingLog = $this->auditService->findLog([
                    'exam_id' => $examId,
                    'task_type' => 'materials_handover_verification',
                ]);
                if ($existingLog) {
                    // Update the log for the single exam ID.
                    $initialState = $existingLog->after_state['initial_state'] ?? $beforeState;
                    $totalUpdates = ($existingLog->after_state['total_updates'] ?? 0) + 1;
                    $this->auditService->updateLog(
                        logId: $existingLog->id,
                        metadata: $metadata,
                        afterState: [
                            'initial_state' => $initialState,
                            'last_state' => $handoverDetails,
                            'total_updates' => $totalUpdates,
                        ],
                        description: "Handover details updated for vehicle {$metadata['vehicle_id']} on exam {$examId} (Total updates: {$totalUpdates})"
                    );
                }
                // If there is no log for the single exam id, we simply do not create one.
            }

            return back()->with('success', 'Handover details saved successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to save handover details. Please try again. ' . $e);
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
