<?php

namespace App\Http\Controllers;

use App\Models\ExamMaterialsScan;
use App\Models\ExamMaterialsData;
use App\Services\ExamAuditService;
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

        $query = $role == 'district'
            ? ExamMaterialsData::where('exam_id', $examId)
                ->where('district_code', $user->district_code)
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
            return response()->json([
                'status' => 'error',
                'message' => 'QR code not found'
            ], 404);
        }

        // Check if already scanned
        if (
            ExamMaterialsScan::where([
                'exam_material_id' => $examMaterials->id,
            ])->exists()
        ) {
            return response()->json([
                'error' => 'QR code has already been scanned'
            ], 409);
        }

        // Create scan record
        ExamMaterialsScan::create([
            'exam_material_id' => $examMaterials->id,
            'district_scanned_at' => now()
        ]);

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
            return response()->json([
                'status' => 'error',
                'message' => 'QR code not found'
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

        return view('my_exam.ExamMaterialsData.center-to-mobileteam-materials', compact('examMaterials', 'examId', 'examDate', 'totalExamMaterials', 'totalScanned','centers'));
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
            return response()->json([
                'status' => 'error',
                'message' => 'QR code not found'
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

}
