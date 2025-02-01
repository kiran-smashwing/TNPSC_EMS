<?php

namespace App\Http\Controllers;

use App\Models\ExamMaterialsScan;
use App\Models\ExamMaterialsData;
use App\Services\ExamAuditService;
use App\Models\ExamSession;
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

        // Check if already scanned
        if (
            ExamMaterialsScan::where([
                'exam_material_id' => $examMaterials->id,
            ])->exists()
        ) {
            return response()->json([
                'status' => 'error',
                'message' => 'QR code has already been scanned'
            ], 409);
        }

        // Create scan record
        ExamMaterialsScan::create([
            'exam_material_id' => $examMaterials->id,
            'district_scanned_at' => now()
        ]);
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
            'task_type' => 'receive_materials_printer_to_disitrct_treasury',
            'action_type' => 'qr_scan',
            'user_id' => $user->tre_off_id
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
                taskType: 'receive_materials_printer_to_disitrct_treasury',
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
            'message' => 'QR code scanned successfully'
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

        //get center name for 

        return view('my_exam.ExamMaterialsData.printer-to-hq-materials', compact('examMaterials', 'examId', 'totalExamMaterials', 'totalScanned', 'centers'));
    }

    public function scanHQExamMaterials($examId, Request $request)
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
        // Find exam materials
        $examMaterials = ExamMaterialsData::where([
            'exam_id' => $examId,
            'district_code' => '01', //Only for Chennai District
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

        // Check if already scanned
        if (
            ExamMaterialsScan::where([
                'exam_material_id' => $examMaterials->id,
            ])->exists()
        ) {
            return response()->json([
                'status' => 'error',
                'message' => 'QR code has already been scanned'
            ], 409);
        }

        // Create scan record in district scanned at because QD Receives Materials for Chennai District Instead of chennai district collectrate.  
        ExamMaterialsScan::create([
            'exam_material_id' => $examMaterials->id,
            'district_scanned_at' => now()
        ]);
        // Audit Logging
        $currentUser = current_user();
        $userName = $currentUser ? $currentUser->display_name : 'Unknown';
        $metadata = ['user_name' => $userName];

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
            'task_type' => 'receive_materials_printer_to_hq',
            'action_type' => 'qr_scan',
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
                taskType: 'receive_materials_printer_to_hq',
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
            'message' => 'QR code scanned successfully'
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
            'qr_code' => 'required|string',
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

        // Find exam materials
        $examMaterials = ExamMaterialsData::where([
            'exam_id' => $examId,
            'center_code' => $user->center_code,
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
            ])->whereNotNull('center_scanned_at')->exists()
        ) {
            return response()->json([
                'status' => 'error',
                'message' => 'QR code has already been scanned'
            ], 409);
        }

        // Update the existing record if center_scanned_at is null
        if ($existingScan && !$existingScan->center_scanned_at) {
            $existingScan->update([
                'center_scanned_at' => now()
            ]);
        } else {
            // Create a new record if no existing scan record is found
            ExamMaterialsScan::create([
                'exam_material_id' => $examMaterials->id,
                'center_scanned_at' => now()
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'QR code scanned successfully'
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
            'qr_code' => 'required|string',
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

        // Find exam materials
        $examMaterials = ExamMaterialsData::where([
            'exam_id' => $examId,
            'mobile_team_id' => $user->mobile_id,
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
            ])->whereNotNull('mobile_team_scanned_at')->exists()
        ) {
            return response()->json([
                'status' => 'error',
                'message' => 'QR code has already been scanned'
            ], 409);
        }

        // Update the existing record if mobile_team_scanned_at is null
        if ($existingScan && !$existingScan->mobile_team_scanned_at) {
            $existingScan->update([
                'mobile_team_scanned_at' => now()
            ]);
        } else {
            // Create a new record if no existing scan record is found
            ExamMaterialsScan::create([
                'exam_material_id' => $examMaterials->id,
                'mobile_team_scanned_at' => now()
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'QR code scanned successfully'
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

        $query = $role == 'headquarters' && $user->role->role_name == 'Van Duty Staff'
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
            'qr_code' => 'required|string',
        ]);

        // Get authenticated user
        $role = session('auth_role');
        $guard = $role ? Auth::guard($role) : null;
        $user = $guard ? $guard->user() : null;

        // Check authorization
        if ($role !== 'headquarters' && $user->role->role_name !== 'Van Duty Staff' || !$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found or not authorized'
            ], 403); // 403 is for authorization errors
        }

        // Find exam materials
        $examMaterials = ExamMaterialsData::where([
            'exam_id' => $examId,
            'mobile_team_id' => $user->dept_off_id,
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
            ])->whereNotNull('mobile_team_scanned_at')->exists()
        ) {
            return response()->json([
                'status' => 'error',
                'message' => 'QR code has already been scanned'
            ], 409);
        }

        // Update the existing record if mobile_team_scanned_at is null
        if ($existingScan && !$existingScan->mobile_team_scanned_at) {
            $existingScan->update([
                'mobile_team_scanned_at' => now()
            ]);
        } else {
            // Create a new record if no existing scan record is found
            ExamMaterialsScan::create([
                'exam_material_id' => $examMaterials->id,
                'mobile_team_scanned_at' => now()
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'QR code scanned successfully'
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
        if ($exam_session == 'FN' || $exam_session == 'AN') {
            $query->where('exam_session', $exam_session);
        }

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
