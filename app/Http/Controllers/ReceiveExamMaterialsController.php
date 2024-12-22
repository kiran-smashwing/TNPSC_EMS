<?php

namespace App\Http\Controllers;

use App\Models\ExamMaterialsData;
use App\Services\ExamAuditService;
use Illuminate\Support\Facades\Auth;

class ReceiveExamMaterialsController extends Controller
{

    protected $auditService;

    public function __construct(ExamAuditService $auditService)
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth.multi');
        $this->auditService = $auditService;
    }

    public function printerToDistrictTreasury($examId)
    {
        $role = session('auth_role');
        $guard = $role ? Auth::guard($role) : null;
        $user = $guard ? $guard->user() : null;

        $query = $role == 'district'
            ? ExamMaterialsData::where('exam_id', $examId)
                ->where('district_code', $user->district_code)
                ->whereIn('category', ['D1', 'D2'])
            : ExamMaterialsData::where('exam_id', $examId);

        $examMaterials = $query
            ->with('center')
            ->get()
            ->groupBy(['center.center_code', 'hall_code', 'exam_date', 'exam_session'])
            ->map(function ($centerGroup) {
                return $centerGroup->map(function ($hallGroup) {
                    return $hallGroup->map(function ($dateGroup) {
                        return $dateGroup->map(function ($sessionGroup) {
                            return [
                                'center' => $sessionGroup->first()->center,
                                'hall_code' => $sessionGroup->first()->hall_code,
                                'exam_date' => $sessionGroup->first()->exam_date,
                                'exam_session' => $sessionGroup->first()->exam_session,
                                'd1_count' => $sessionGroup->where('category', 'D1')->count(),
                                'd2_count' => $sessionGroup->where('category', 'D2')->count(),
                                'total_count' => $sessionGroup->count(),
                                'created_at' => $sessionGroup->first()->created_at,
                            ];
                        });
                    });
                });
            });

        return view('my_exam.ExamMaterialsData.printer-to-disitrict-materials', compact('examMaterials'));
    }

}
