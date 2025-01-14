<?php

namespace App\Http\Controllers;

use App\Models\ExamMaterialsScan;
use App\Models\ExamMaterialsData;
use App\Models\Currentexam;
use App\Models\ExamTrunkBoxOTLData;
use App\Services\ExamAuditService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

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
        $categoryLabels = [
            'R3' => 'Bundle I',
            'R4' => 'Bundle II',
            'R5' => 'Bundle C',
        ];

        $query = $role == 'ci'
            ? ExamMaterialsData::where('exam_id', $examId)
                ->where('ci_id', $user->ci_id)
                ->whereIn('category', array_keys($categoryLabels))
                ->whereDate('exam_date', $exam_date)
                ->where('exam_session', $exam_session)
            : ExamMaterialsData::where('exam_id', $examId)
                ->whereIn('category', array_keys($categoryLabels));

        $examMaterials = $query->with(['examMaterialsScan'])->get();

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
                'message' => 'QR code has already been scanned, Place this bundle in this trunk box: '.$trunkBox->trunkbox_qr_code .'',
            ], 409);
        }

        // Create scan record
        ExamMaterialsScan::create([
            'exam_material_id' => $examMaterials->id,
            'district_scanned_at' => now()
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'QR code scanned successfully, Place this bundle in this trunk box:'.$trunkBox->trunkbox_qr_code .'',
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
}
