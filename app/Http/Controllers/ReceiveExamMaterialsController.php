<?php

namespace App\Http\Controllers;

use App\Models\ExamMaterialsScan;
use App\Models\ExamMaterialsData;
use App\Services\ExamAuditService;
use App\Models\ExamSession;
use App\Models\Currentexam;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ReceiveExamMaterialsController extends Controller
{

    protected $auditService;

    public function __construct(ExamAuditService $auditService)
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth.multi');
        $this->auditService = $auditService;
    }

    public function printerToDistrictTreasury(Request $request, $examId)
    {
        $role = session('auth_role');
        $guard = $role ? Auth::guard($role) : null;
        $user = $guard ? $guard->user() : null;

        $query = $role == 'treasury'
            ? ExamMaterialsData::where('exam_id', $examId)
                ->where('district_code', $user->tre_off_district_id)
                ->whereIn('category', ['D1', 'D2'])
            : ExamMaterialsData::where('exam_id', $examId)
                ->where('district_code', $user->district_code)
                ->whereIn('category', ['D1', 'D2']);
        // Apply filters 
        if ($request->has('centerCode') && !empty($request->centerCode)) {
            $query->where('center_code', $request->centerCode);
        }
        if ($request->has('examDate') && !empty($request->examDate)) {
            $query->whereDate('exam_date', $request->examDate);
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
            return $examMaterial->examMaterialsScan;
        })->count();
        //get center code for the user 
        $centers = ExamMaterialsData::where('exam_id', $examId)
            ->where('district_code', $user->district_code)
            ->join('centers', 'exam_materials_data.center_code', '=', 'centers.center_code')
            ->groupBy('centers.center_code', 'centers.center_name')
            ->select('centers.center_name', 'centers.center_code')
            ->get();

        //get center name for 

        return view('my_exam.ExamMaterialsData.printer-to-disitrict-materials', compact('examMaterials', 'examId', 'totalExamMaterials', 'totalScanned', 'centers'));
    }

    public function scanDistrictExamMaterials($examId, Request $request)
    {
        // Validate request
        $request->validate([
            'qr_codes' => 'required|array',
            'qr_codes.*' => 'required|string',
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
        $results = [];        // To hold the result for each QR code
        $successfulScans = []; // To hold details of successfully scanned codes
        foreach ($request->qr_codes as $qr_code) {
            // Trim whitespace and skip if empty
            $qr_code = trim($qr_code);
            if (empty($qr_code)) {
                continue;
            }
            // Find exam materials
            $examMaterials = ExamMaterialsData::where([
                'exam_id' => $examId,
                'district_code' => $user->tre_off_district_id,
                'qr_code' => $qr_code
            ])->first();

            if (!$examMaterials) {
                $examMaterials = ExamMaterialsData::where([
                    'exam_id' => $examId,
                    'qr_code' => $qr_code
                ])
                    ->with('center')
                    ->with('district')
                    ->first();
                if ($examMaterials) {
                    $msg = "This QR Code belongs to District: "
                        . $examMaterials->district->district_name
                        . ", Center: "
                        . $examMaterials->center->center_name
                        . ", Hall Code: "
                        . $examMaterials->hall_code;
                    $results[] = [
                        'qr_code' => $qr_code,
                        'status' => 'error',
                        'message' => $msg,
                    ];
                    continue;
                } else {
                    // Not found at all
                    $results[] = [
                        'qr_code' => $qr_code,
                        'status' => 'error',
                        'message' => "Exam material not found for QR code: $qr_code",
                    ];
                    continue;
                }
            }

            // Check if already scanned
            if (
                ExamMaterialsScan::where([
                    'exam_material_id' => $examMaterials->id,
                ])->exists()
            ) {
                $results[] = [
                    'qr_code' => $qr_code,
                    'status' => 'error',
                    'message' => 'QR code has already been scanned',
                ];
                continue;
            }

            // Create scan record
            ExamMaterialsScan::create([
                'exam_material_id' => $examMaterials->id,
                'district_scanned_at' => now()
            ]);
            // Prepare details for audit logging
            $examMaterialDetails = [
                'qr_code' => $qr_code,
                'district' => $examMaterials->district->district_name,
                'center' => $examMaterials->center->center_name,
                'hall_code' => $examMaterials->hall_code,
                'scan_time' => now()->toDateTimeString(),
            ];

            // Record a successful scan result
            $results[] = [
                'qr_code' => $qr_code,
                'status' => 'success',
                'message' => 'QR code scanned successfully',
            ];

            $successfulScans[] = $examMaterialDetails;
        }
        if (count($successfulScans) > 0) {

            // Audit Logging
            $currentUser = current_user();
            $userName = $currentUser ? $currentUser->display_name : 'Unknown';
            $metadata = [
                'user_name' => $userName,
                'district_code' => $currentUser->tre_off_district_id,
            ];

            // Check existing log
            $existingLog = $this->auditService->findLog([
                'exam_id' => $examId,
                'task_type' => 'receive_materials_printer_to_district_treasury',
                'action_type' => 'qr_scan',
                'user_id' => $user->tre_off_id,
            ]);

            if ($existingLog) {
                // Append new scans to existing scanned codes
                $existingScans = $existingLog->after_state['scanned_codes'] ?? [];
                $updatedScans = array_merge($existingScans, $successfulScans);
                $totalScans = ($existingLog->after_state['total_scanned'] ?? 0) + count($successfulScans);

                $this->auditService->updateLog(
                    logId: $existingLog->id,
                    metadata: $metadata,
                    afterState: [
                        'scanned_codes' => $updatedScans,
                        'total_scanned' => $totalScans
                    ],
                    description: "Bulk scanned QR codes (Total scanned: $totalScans)"
                );
            } else {
                // Create new log for first scan
                $this->auditService->log(
                    examId: $examId,
                    actionType: 'qr_scan',
                    taskType: 'receive_materials_printer_to_district_treasury',
                    beforeState: null,
                    afterState: [
                        'scanned_codes' => $successfulScans,
                        'total_scanned' => count($successfulScans)
                    ],
                    description: "Initial bulk QR code scan",
                    metadata: $metadata
                );
            }
        }
        return response()->json([
            'status' => 'success',
            'results' => $results,
        ], 200);
    }
    /*
     * HeadQuarters receive exam materials from the printer to all centers in chennai district instead of disitrict collectorate directly.
     */
    public function printerToHQTreasury(Request $request, $examId)
    {
        $role = session('auth_role');
        $guard = $role ? Auth::guard($role) : null;
        $user = $guard ? $guard->user() : null;

        $query = $role == 'headquarters' && $user->role->role_department == 'QD'
            ? ExamMaterialsData::where('exam_id', $examId)
                ->where('district_code', '01')               //Only for Chennai District
                ->whereIn('category', ['D1', 'D2'])
            : ExamMaterialsData::where('exam_id', $examId);
        // Apply filters 
        if ($request->has('centerCode') && !empty($request->centerCode)) {
            $query->where('center_code', $request->centerCode);
        }
        if ($request->has('examDate') && !empty($request->examDate)) {
            $query->whereDate('exam_date', $request->examDate);
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
            return $examMaterial->examMaterialsScan;
        })->count();
        //get center code for the user 
        $centers = ExamMaterialsData::where('exam_id', $examId)
            ->where('district_code', '01')
            ->join('centers', 'exam_materials_data.center_code', '=', 'centers.center_code')
            ->groupBy('centers.center_code', 'centers.center_name')
            ->select('centers.center_name', 'centers.center_code')
            ->get();
        // Get current exam session details
        $session = Currentexam::with('examsession')->where('exam_main_no', $examId)->first();
        $examDates = $session->examsession->groupBy(function ($item) {
            return Carbon::parse($item->exam_sess_date)->format('d-m-Y');
        })->keys();
        // dd($examDates);
        //get center name for 

        return view('my_exam.ExamMaterialsData.printer-to-hq-materials', compact('examMaterials', 'examId', 'totalExamMaterials', 'totalScanned', 'examDates', 'centers'));
    }
    public function scanHQExamMaterials($examId, Request $request)
    {
        // Validate request
        $request->validate([
            'qr_codes' => 'required|array',
            'qr_codes.*' => 'required|string',
        ]);

        // Get authenticated user
        $role = session('auth_role');
        $guard = $role ? Auth::guard($role) : null;
        $user = $guard ? $guard->user() : null;

        // Check authorization
        if (
            $role !== 'headquarters' &&
            (!isset($user->role) || $user->role->role_department !== 'QD') ||
            !$user
        ) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found or not authorized'
            ], 403); // 403 is for authorization errors
        }
        $results = [];        // To hold the result for each QR code
        $successfulScans = []; // To hold details of successfully scanned codes
        foreach ($request->qr_codes as $qr_code) {
            // Trim whitespace and skip if empty
            $qr_code = trim($qr_code);
            if (empty($qr_code)) {
                continue;
            }
            // Find exam materials
            $examMaterials = ExamMaterialsData::where([
                'exam_id' => $examId,
                'district_code' => '01', //Only for Chennai District
                'qr_code' => $qr_code
            ])->first();

            if (!$examMaterials) {
                $examMaterials = ExamMaterialsData::where([
                    'exam_id' => $examId,
                    'qr_code' => $qr_code
                ])
                    ->with('center')
                    ->with('district')
                    ->first();
                if ($examMaterials) {
                    $msg = "This QR Code belongs to District: "
                        . $examMaterials->district->district_name
                        . ", Center: "
                        . $examMaterials->center->center_name
                        . ", Hall Code: "
                        . $examMaterials->hall_code;
                    $results[] = [
                        'qr_code' => $qr_code,
                        'status' => 'error',
                        'message' => $msg,
                    ];
                    continue;
                } else {
                    // Not found at all
                    $results[] = [
                        'qr_code' => $qr_code,
                        'status' => 'error',
                        'message' => "Exam material not found for QR code: $qr_code",
                    ];
                    continue;
                }
            }

            // Check if already scanned
            if (
                ExamMaterialsScan::where([
                    'exam_material_id' => $examMaterials->id,
                ])->exists()
            ) {
                $results[] = [
                    'qr_code' => $qr_code,
                    'status' => 'error',
                    'message' => 'QR code has already been scanned',
                ];
                continue;
            }

            // Create scan record in district scanned at because QD Receives Materials for Chennai District Instead of chennai district collectrate.  
            ExamMaterialsScan::create([
                'exam_material_id' => $examMaterials->id,
                'district_scanned_at' => now()
            ]);
            // Prepare details for audit logging
            $examMaterialDetails = [
                'qr_code' => $qr_code,
                'district' => $examMaterials->district->district_name,
                'center' => $examMaterials->center->center_name,
                'hall_code' => $examMaterials->hall_code,
                'scan_time' => now()->toDateTimeString(),
            ];

            // Record a successful scan result
            $results[] = [
                'qr_code' => $qr_code,
                'status' => 'success',
                'message' => 'QR code scanned successfully',
            ];

            $successfulScans[] = $examMaterialDetails;
        }
        if (count($successfulScans) > 0) {
            // Audit Logging
            $currentUser = current_user();
            $userName = $currentUser ? $currentUser->display_name : 'Unknown';
            $metadata = [
                'user_name' => $userName,
                'district_code' => '01',
            ];

            // Check existing log
            $existingLog = $this->auditService->findLog([
                'exam_id' => $examId,
                'task_type' => 'receive_materials_printer_to_hq',
                'action_type' => 'qr_scan',
            ]);

            if ($existingLog) {
                // Update existing log
                $existingScans = $existingLog->after_state['scanned_codes'] ?? [];
                $updatedScans = array_merge($existingScans, $successfulScans);
                $totalScans = ($existingLog->after_state['total_scanned'] ?? 0) + count($successfulScans);


                $this->auditService->updateLog(
                    logId: $existingLog->id,
                    metadata: $metadata,
                    afterState: [
                        'scanned_codes' => $updatedScans,
                        'total_scanned' => $totalScans
                    ],
                    description: "Bulk scanned QR codes (Total scanned: $totalScans)"
                );
            } else {
                // Create new log for first scan
                $this->auditService->log(
                    examId: $examId,
                    actionType: 'qr_scan',
                    taskType: 'receive_materials_printer_to_hq',
                    beforeState: null,
                    afterState: [
                        'scanned_codes' => $successfulScans,
                        'total_scanned' => count($successfulScans)
                    ],
                    description: "Initial QR code scan",
                    metadata: $metadata
                );
            }
        }
        return response()->json([
            'status' => 'success',
            'results' => $results,
        ], 200);
    }

    public function districtTreasuryToCenter(Request $request, $examId)
    {
        $role = session('auth_role');
        $guard = $role ? Auth::guard($role) : null;
        $user = $guard ? $guard->user() : null;

        $query = $role == 'center'
            ? ExamMaterialsData::where('exam_id', $examId)
                ->where('center_code', $user->center_code)
                ->whereIn('category', ['D1', 'D2'])
            : ExamMaterialsData::where('exam_id', $examId);
        // Apply filters 
        if ($request->has('examDate') && !empty($request->examDate)) {
            $query->whereDate('exam_date', $request->examDate);
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
                $examMaterial->examMaterialsScan->center_scanned_at;
        })->count();

        //get center name for 

        return view('my_exam.ExamMaterialsData.district-to-center-materials', compact('examMaterials', 'examId', 'totalExamMaterials', 'totalScanned'));
    }
    public function scanCenterExamMaterials($examId, Request $request)
    {
        // Validate request
        $request->validate([
            'qr_codes' => 'required|array',
            'qr_codes.*' => 'required|string',
        ]);

        // Get authenticated user
        $role = session('auth_role');
        $guard = $role ? Auth::guard($role) : null;
        $user = $guard ? $guard->user() : null;

        // Check authorization
        if ($role !== 'center' || !$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found or not authorized'
            ], 403); // 403 is for authorization errors
        }
        $results = [];        // To hold the result for each QR code
        $successfulScans = []; // To hold details of successfully scanned codes
        foreach ($request->qr_codes as $qr_code) {
            // Trim whitespace and skip if empty
            $qr_code = trim($qr_code);
            if (empty($qr_code)) {
                continue;
            }
            // Find exam materials
            $examMaterials = ExamMaterialsData::where([
                'exam_id' => $examId,
                'center_code' => $user->center_code,
                'qr_code' => $qr_code
            ])->first();

            if (!$examMaterials) {
                $examMaterials = ExamMaterialsData::where([
                    'exam_id' => $examId,
                    'qr_code' => $qr_code
                ])
                    ->with('center')
                    ->with('district')
                    ->first();
                if ($examMaterials) {
                    $msg = "This QR Code belongs to District: "
                        . $examMaterials->district->district_name
                        . ", Center: "
                        . $examMaterials->center->center_name
                        . ", Hall Code: "
                        . $examMaterials->hall_code;
                    $results[] = [
                        'qr_code' => $qr_code,
                        'status' => 'error',
                        'message' => $msg,
                    ];
                    continue;
                } else {
                    // Not found at all
                    $results[] = [
                        'qr_code' => $qr_code,
                        'status' => 'error',
                        'message' => "Exam material not found for QR code: $qr_code",
                    ];
                    continue;
                }
            }
            // Check if already scanned (i.e. center_scanned_at is set)
            if (
                ExamMaterialsScan::where('exam_material_id', $examMaterials->id)
                    ->whereNotNull('center_scanned_at')->exists()
            ) {
                $message = 'QR code has already been scanned';
                $results[] = [
                    'qr_code' => $qr_code,
                    'status' => 'error',
                    'message' => $message,
                ];
                continue;
            }
            // Check if there is an existing scan record
            $existingScan = ExamMaterialsScan::where('exam_material_id', $examMaterials->id)->first();

            if ($existingScan && !$existingScan->center_scanned_at) {
                // Update the record if not yet scanned
                $existingScan->update([
                    'center_scanned_at' => now()
                ]);
            } else {
                // Otherwise create a new scan record
                ExamMaterialsScan::create([
                    'exam_material_id' => $examMaterials->id,
                    'center_scanned_at' => now()
                ]);
            }
            // Prepare details for audit logging
            $examMaterialDetails = [
                'qr_code' => $qr_code,
                'district' => $examMaterials->district->district_name,
                'center' => $examMaterials->center->center_name,
                'hall_code' => $examMaterials->hall_code,
                'scan_time' => now()->toDateTimeString(),
            ];
            // Record a successful scan result
            $results[] = [
                'qr_code' => $qr_code,
                'status' => 'success',
                'message' => 'QR code scanned successfully',
            ];

            $successfulScans[] = $examMaterialDetails;
        }
        if (count($successfulScans) > 0) {
            // Audit Logging
            $currentUser = current_user();
            $userName = $currentUser ? $currentUser->display_name : 'Unknown';
            $metadata = [
                'user_name' => $userName,
                'district_code' => $currentUser->center_district_id,
            ];

            $tasktype = in_array($examMaterials->category, ['D1', 'D2'])
                ? 'receive_materials_disitrct_to_center'
                : 'receive_bundle_to_center';

            // Check existing log
            $existingLog = $this->auditService->findLog([
                'exam_id' => $examId,
                'task_type' => $tasktype,
                'action_type' => 'qr_scan',
                'user_id' => $user->center_id,
            ]);

            if ($existingLog) {
                // Update existing log
                $existingScans = $existingLog->after_state['scanned_codes'] ?? [];
                $updatedScans = array_merge($existingScans, $successfulScans);
                $totalScans = ($existingLog->after_state['total_scanned'] ?? 0) + count($successfulScans);


                $this->auditService->updateLog(
                    logId: $existingLog->id,
                    metadata: $metadata,
                    afterState: [
                        'scanned_codes' => $updatedScans,
                        'total_scanned' => $totalScans
                    ],
                    description: "Bulk scanned QR codes (Total scanned: $totalScans)"
                );
            } else {
                // Create new log for first scan
                $this->auditService->log(
                    examId: $examId,
                    actionType: 'qr_scan',
                    taskType: $tasktype,
                    beforeState: null,
                    afterState: [
                        'scanned_codes' => $successfulScans,
                        'total_scanned' => count($successfulScans)
                    ],
                    description: "Initial bulk QR code scan",
                    metadata: $metadata
                );
            }
        }
        return response()->json([
            'status' => 'success',
            'results' => $results,
        ], 200);
    }

    public function subTreasuryToMobileTeam(Request $request, $examId, $examDate)
    {
        $role = session('auth_role');
        $guard = $role ? Auth::guard($role) : null;
        $user = $guard ? $guard->user() : null;
        $examDate = Carbon::parse($examDate)->format('Y-m-d');

        $query = $role == 'mobile_team_staffs'
            ? ExamMaterialsData::where('exam_id', $examId)
                ->where('mobile_team_id', $user->mobile_id)
                ->whereDate('exam_date', $examDate)
                ->whereIn('category', ['D1', 'D2'])
            : ExamMaterialsData::where('exam_id', $examId);
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
            ->where('district_code', $user->mobile_district_id)
            ->join('centers', 'exam_materials_data.center_code', '=', 'centers.center_code')
            ->groupBy('centers.center_code', 'centers.center_name')
            ->select('centers.center_name', 'centers.center_code')
            ->get();

        //get center name for 

        return view('my_exam.ExamMaterialsData.center-to-mobileteam-materials', compact('examMaterials', 'examId', 'examDate', 'totalExamMaterials', 'totalScanned', 'centers'));
    }
    public function scanMobileTeamExamMaterials($examId, Request $request)
    {
        // Validate request
        $request->validate([
            'qr_codes' => 'required|array',
            'qr_codes.*' => 'required|string',
        ]);

        // Get authenticated user
        $role = session('auth_role');
        $guard = $role ? Auth::guard($role) : null;
        $user = $guard ? $guard->user() : null;

        // Check authorization
        if ($role !== 'mobile_team_staffs' || !$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found or not authorized'
            ], 403); // 403 is for authorization errors
        }
        $results = [];       // To hold result for each QR code
        $auditByDate = [];   // To accumulate audit info keyed by exam_date
        foreach ($request->qr_codes as $qr_code) {
            // Trim whitespace and skip if empty
            $qr_code = trim($qr_code);
            if (empty($qr_code)) {
                continue;
            }
            // Find exam materials
            $examMaterials = ExamMaterialsData::where([
                'exam_id' => $examId,
                'mobile_team_id' => $user->mobile_id,
                'qr_code' => $qr_code
            ])->first();

            if (!$examMaterials) {
                $examMaterials = ExamMaterialsData::where([
                    'exam_id' => $examId,
                    'qr_code' => $qr_code
                ])
                    ->with('center')
                    ->with('district')
                    ->first();
                if ($examMaterials) {
                    $msg = "This QR Code belongs to District: "
                        . $examMaterials->district->district_name
                        . ", Center: "
                        . $examMaterials->center->center_name
                        . ", Hall Code: "
                        . $examMaterials->hall_code;
                    $results[] = [
                        'qr_code' => $qr_code,
                        'status' => 'error',
                        'message' => $msg,
                    ];
                    continue;
                } else {
                    // Not found at all
                    $results[] = [
                        'qr_code' => $qr_code,
                        'status' => 'error',
                        'message' => "Exam material not found for QR code: $qr_code",
                    ];
                    continue;
                }
            }
            // Check if the exam material has already been scanned (mobile_team_scanned_at is not null)
            if (
                ExamMaterialsScan::where('exam_material_id', $examMaterials->id)
                    ->whereNotNull('mobile_team_scanned_at')->exists()
            ) {
                $results[] = [
                    'qr_code' => $qr_code,
                    'status' => 'error',
                    'message' => 'QR code has already been scanned'
                ];
                continue;
            }
            // Update existing scan record if available; otherwise, create a new record
            $existingScan = ExamMaterialsScan::where('exam_material_id', $examMaterials->id)->first();
            if ($existingScan && !$existingScan->mobile_team_scanned_at) {
                $existingScan->update([
                    'mobile_team_scanned_at' => now()
                ]);
            } else {
                ExamMaterialsScan::create([
                    'exam_material_id' => $examMaterials->id,
                    'mobile_team_scanned_at' => now()
                ]);
            }
            // Prepare details for last scanned material
            $lastScannedMaterial = [
                'district' => $examMaterials->district->district_name ?? 'Unknown',
                'center' => $examMaterials->center->center_name ?? 'Unknown',
                'hall_code' => $examMaterials->hall_code ?? 'Unknown',
                'scan_timestamp' => now()->toDateTimeString()
            ];
            // Prepare metadata for audit logging (using current scan's QR code)
            $metadata = [
                'user_name' => $user->display_name ?? 'Unknown',
                'mobile_team_id' => $user->mobile_id ?? null,
                'qr_code' => $qr_code,
                'scan_time' => now()->toDateTimeString()
            ];

            // Determine task type based on exam material category
            $tasktype = in_array($examMaterials->category, ['D1', 'D2']) ? 'receive_materials_to_mobileteam_staff' :
            'receive_bundle_to_mobileteam_staff';

            // Get the exam date as string (used as key in audit log)
            $scanDate = strval($examMaterials->exam_date);

            // Accumulate audit data: for each date, update last scanned material and increment count
            if (!isset($auditByDate[$scanDate])) {
                $auditByDate[$scanDate] = [
                    'last_scanned_material' => $lastScannedMaterial,
                    'total_scans' => 1
                ];
            } else {
                $auditByDate[$scanDate]['last_scanned_material'] = $lastScannedMaterial;
                $auditByDate[$scanDate]['total_scans'] += 1;
            }

            // Record successful scan result
            $results[] = [
                'qr_code' => $qr_code,
                'status' => 'success',
                'message' => 'QR code scanned successfully'
            ];
        } // end foreach
        // Update the audit log if there were any successful scans
        if (!empty($auditByDate)) {
            // Fetch existing audit log for this exam, task type, and van duty staff
            $existingLog = $this->auditService->findLog([
                'exam_id' => $examId,
                'task_type' => $tasktype,
                'user_id' => $user->mobile_id,
            ]);

            if ($existingLog) {
                $afterState = is_array($existingLog->after_state)
                    ? $existingLog->after_state
                    : json_decode($existingLog->after_state, true);
                $scanHistory = isset($afterState['scans_by_date']) && is_array($afterState['scans_by_date'])
                    ? $afterState['scans_by_date']
                    : [];

                // Merge the new audit data with existing history
                foreach ($auditByDate as $dateKey => $newData) {
                    if (isset($scanHistory[$dateKey])) {
                        $scanHistory[$dateKey]['total_scans'] += $newData['total_scans'];
                        $scanHistory[$dateKey]['last_scanned_material'] = $newData['last_scanned_material'];
                    } else {
                        $scanHistory[$dateKey] = $newData;
                    }
                }
                $updatedAfterState = ['scans_by_date' => $scanHistory];

                // Update audit log entry
                $this->auditService->updateLog(
                    logId: $existingLog->id,
                    metadata: $metadata, // using metadata from the last processed scan
                    afterState: $updatedAfterState,
                    description: "Updated scan details for dates: " . implode(", ", array_keys($auditByDate))
                );
            } else {
                // Create a new audit log entry if none exists
                $this->auditService->log(
                    examId: $examId,
                    actionType: 'qr_scan',
                    taskType: $tasktype,
                    beforeState: null,
                    afterState: [
                        'scans_by_date' => $auditByDate
                    ],
                    description: "Initial scan recorded for dates: " . implode(", ", array_keys($auditByDate)),
                    metadata: $metadata
                );
            }
        }

        return response()->json([
            'status' => 'success',
            'results' => $results
        ], 200);
    }

    /*
     *  Vanduty Staffreceive exam materials from the HeadQuarters to all centers in chennai district instead of mobile team.
     */
    public function headQuartersToVanduty(Request $request, $examId, $examDate)
    {
        $role = session('auth_role');
        $guard = $role ? Auth::guard($role) : null;
        $user = $guard ? $guard->user() : null;
        $examDate = Carbon::parse($examDate)->format('Y-m-d');

        $query = $role == 'headquarters' && $user->custom_role == 'VDS'
            ? ExamMaterialsData::where('exam_id', $examId)
                ->where('district_code', '01')
                ->where('mobile_team_id', $user->dept_off_id)
                ->whereDate('exam_date', $examDate)
                ->whereIn('category', ['D1', 'D2'])
            : ExamMaterialsData::where('exam_id', $examId);
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
            ->where('district_code', '01')
            ->join('centers', 'exam_materials_data.center_code', '=', 'centers.center_code')
            ->groupBy('centers.center_code', 'centers.center_name')
            ->select('centers.center_name', 'centers.center_code')
            ->get();

        //get center name for 

        return view('my_exam.ExamMaterialsData.hq-to-vandutystaff-materials', compact('examMaterials', 'examId', 'examDate', 'totalExamMaterials', 'totalScanned', 'centers'));
    }
    public function scanVandutystaffExamMaterials($examId, Request $request)
    {
        // Validate request
        $request->validate([
            'qr_codes' => 'required|array',
            'qr_codes.*' => 'required|string',
        ]);

        // Get authenticated user
        $role = session('auth_role');
        $guard = $role ? Auth::guard($role) : null;
        $user = $guard ? $guard->user() : null;

        // Check authorization
        if ($role !== 'headquarters' && $user->custom_role !== 'VDS' || !$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found or not authorized'
            ], 403); // 403 is for authorization errors
        }
        $results = [];       // To hold result for each QR code
        $auditByDate = [];   // To accumulate audit info keyed by exam_date
        // Process each QR code in the request
        foreach ($request->qr_codes as $qr_code) {
            $qr_code = trim($qr_code);
            if (empty($qr_code)) {
                continue; // Skip empty/whitespace-only codes
            }
            // Find exam materials
            $examMaterials = ExamMaterialsData::where([
                'exam_id' => $examId,
                'mobile_team_id' => $user->dept_off_id,
                'qr_code' => $qr_code
            ])->first();

            // If not found, try a broader lookup with relations
            if (!$examMaterials) {
                $examMaterials = ExamMaterialsData::where([
                    'exam_id' => $examId,
                    'qr_code' => $qr_code
                ])
                    ->with('center')
                    ->with('district')
                    ->first();

                if ($examMaterials) {
                    $msg = "This QR Code belongs to District: "
                        . $examMaterials->district->district_name
                        . ", Center: "
                        . $examMaterials->center->center_name
                        . ", Hall Code: "
                        . $examMaterials->hall_code;
                    $results[] = [
                        'qr_code' => $qr_code,
                        'status' => 'error',
                        'message' => $msg
                    ];
                    continue;
                } else {
                    $results[] = [
                        'qr_code' => $qr_code,
                        'status' => 'error',
                        'message' => "Exam material not found for QR code: $qr_code"
                    ];
                    continue;
                }
            }
            // Check if the exam material has already been scanned (mobile_team_scanned_at is not null)
            if (
                ExamMaterialsScan::where('exam_material_id', $examMaterials->id)
                    ->whereNotNull('mobile_team_scanned_at')->exists()
            ) {
                $results[] = [
                    'qr_code' => $qr_code,
                    'status' => 'error',
                    'message' => 'QR code has already been scanned'
                ];
                continue;
            }
            // Update existing scan record if available; otherwise, create a new record
            $existingScan = ExamMaterialsScan::where('exam_material_id', $examMaterials->id)->first();
            if ($existingScan && !$existingScan->mobile_team_scanned_at) {
                $existingScan->update([
                    'mobile_team_scanned_at' => now()
                ]);
            } else {
                ExamMaterialsScan::create([
                    'exam_material_id' => $examMaterials->id,
                    'mobile_team_scanned_at' => now()
                ]);
            }

            // Prepare details for last scanned material
            $lastScannedMaterial = [
                'district' => $examMaterials->district->district_name ?? 'Unknown',
                'center' => $examMaterials->center->center_name ?? 'Unknown',
                'hall_code' => $examMaterials->hall_code ?? 'Unknown',
                'scan_timestamp' => now()->toDateTimeString()
            ];

            // Prepare metadata for audit logging (using current scan's QR code)
            $metadata = [
                'user_name' => $user->display_name ?? 'Unknown',
                'van_duty_staff_id' => $user->dept_off_id ?? null,
                'qr_code' => $qr_code,
                'scan_time' => now()->toDateTimeString()
            ];

            // Determine task type based on exam material category
            $tasktype = in_array($examMaterials->category, ['D1', 'D2'])
                ? 'receive_materials_to_vanduty_staff'
                : 'receive_bundle_to_vanduty_staff';

            // Get the exam date as string (used as key in audit log)
            $scanDate = strval($examMaterials->exam_date);

            // Accumulate audit data: for each date, update last scanned material and increment count
            if (!isset($auditByDate[$scanDate])) {
                $auditByDate[$scanDate] = [
                    'last_scanned_material' => $lastScannedMaterial,
                    'total_scans' => 1
                ];
            } else {
                $auditByDate[$scanDate]['last_scanned_material'] = $lastScannedMaterial;
                $auditByDate[$scanDate]['total_scans'] += 1;
            }

            // Record successful scan result
            $results[] = [
                'qr_code' => $qr_code,
                'status' => 'success',
                'message' => 'QR code scanned successfully'
            ];
        } // end foreach
        // Update the audit log if there were any successful scans
        if (!empty($auditByDate)) {
            // Fetch existing audit log for this exam, task type, and van duty staff
            $existingLog = $this->auditService->findLog([
                'exam_id' => $examId,
                'task_type' => $tasktype,
                'user_id' => $user->dept_off_id,
            ]);

            if ($existingLog) {
                $afterState = is_array($existingLog->after_state)
                    ? $existingLog->after_state
                    : json_decode($existingLog->after_state, true);
                $scanHistory = isset($afterState['scans_by_date']) && is_array($afterState['scans_by_date'])
                    ? $afterState['scans_by_date']
                    : [];

                // Merge the new audit data with existing history
                foreach ($auditByDate as $dateKey => $newData) {
                    if (isset($scanHistory[$dateKey])) {
                        $scanHistory[$dateKey]['total_scans'] += $newData['total_scans'];
                        $scanHistory[$dateKey]['last_scanned_material'] = $newData['last_scanned_material'];
                    } else {
                        $scanHistory[$dateKey] = $newData;
                    }
                }
                $updatedAfterState = ['scans_by_date' => $scanHistory];

                // Update audit log entry
                $this->auditService->updateLog(
                    logId: $existingLog->id,
                    metadata: $metadata, // using metadata from the last processed scan
                    afterState: $updatedAfterState,
                    description: "Updated scan details for dates: " . implode(", ", array_keys($auditByDate))
                );
            } else {
                // Create a new audit log entry if none exists
                $this->auditService->log(
                    examId: $examId,
                    actionType: 'qr_scan',
                    taskType: $tasktype,
                    beforeState: null,
                    afterState: [
                        'scans_by_date' => $auditByDate
                    ],
                    description: "Initial scan recorded for dates: " . implode(", ", array_keys($auditByDate)),
                    metadata: $metadata
                );
            }
        }

        return response()->json([
            'status' => 'success',
            'results' => $results
        ], 200);
    }
    public function ciReceiveMaterialsFromMobileTeam(Request $request, $examId, $exam_date, $exam_session)
    {
        $role = session('auth_role');
        $guard = $role ? Auth::guard($role) : null;
        $user = $guard ? $guard->user() : null;

        // Query based on the role
        $query = $role == 'ci'
            ? ExamMaterialsData::where('exam_id', $examId)
                ->where('ci_id', $user->ci_id)
                ->whereIn('category', ['D1', 'D2'])
                ->whereDate('exam_date', $exam_date)
            : ExamMaterialsData::where('exam_id', $examId)
                ->whereDate('exam_date', $exam_date);

        // Filter by session (FN/AN)
        // if ($exam_session == 'FN' || $exam_session == 'AN') {
        //     $query->where('exam_session', $exam_session);
        // }

        // Retrieve exam materials
        $examMaterials = $query
            ->with(['examMaterialsScan'])
            ->get()
            ->groupBy('exam_session');

        // Retrieve exam type
        $exam_type = ExamSession::where([
            'exam_sess_mainid' => $examId,
            'exam_sess_date' => $exam_date,
            'exam_sess_session' => $exam_session,
        ])->first();

        // Debug output (optional)
        // dd($exam_type);

        // Return the view with data
        return view('my_exam.ExamMaterialsData.mobileTeam-to-ci-materials', compact('exam_type', 'examMaterials', 'examId', 'exam_date'));
    }


    public function scanCIExamMaterials($examId, Request $request)
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
        if ($role !== 'ci' || !$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found or not authorized'
            ], 403); // 403 is for authorization errors
        }

        // Find exam materials
        $examMaterials = ExamMaterialsData::where([
            'exam_id' => $examId,
            'ci_id' => $user->ci_id,
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
        // Check if already scanned with a valid timestamp
        $existingScan = ExamMaterialsScan::where([
            'exam_material_id' => $examMaterials->id,
        ])->first();

        // Check if already scanned
        if (
            ExamMaterialsScan::where([
                'exam_material_id' => $examMaterials->id,
            ])->whereNotNull('ci_scanned_at')->exists()
        ) {
            return response()->json([
                'status' => 'error',
                'message' => 'QR code has already been scanned'
            ], 409);
        }

        // Update the existing record if center_scanned_at is null
        if ($existingScan && !$existingScan->ci_scanned_at) {
            $existingScan->update([
                'ci_scanned_at' => now()
            ]);
        } else {
            // Create a new record if no existing scan record is found
            ExamMaterialsScan::create([
                'exam_material_id' => $examMaterials->id,
                'ci_scanned_at' => now()
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'QR code scanned successfully'
        ], 200);
    }
}
