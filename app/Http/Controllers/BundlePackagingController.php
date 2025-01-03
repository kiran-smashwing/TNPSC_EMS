<?php

namespace App\Http\Controllers;

use App\Models\ExamMaterialsScan;
use App\Models\ExamMaterialsData;
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
    public function ciBundlepackagingView(Request $request, $examId, $exam_date,$exam_session)
    {
        $role = session('auth_role');
        $guard = $role ? Auth::guard($role) : null;
        $user = $guard ? $guard->user() : null;

        // Define the category mapping
        $categoryLabels = [
            'I1' => 'Bundle A1',
            'I2' => 'Bundle A2',
            'R1' => 'Bundle A',
            'I3' => 'Bundle B1',
            'I4' => 'Bundle B2',
            'I5' => 'Bundle B3',
            'I6' => 'Bundle B4',
            'I7' => 'Bundle B5',
            'R2' => 'Bundle B',
            'R3' => 'Bundle I',
            'R4' => 'Bundle II',
            'R5' => 'Bundle C',
        ];

        $query = $role == 'ci'
            ? ExamMaterialsData::where('exam_id', $examId)
                ->where('ci_id', $user->ci_id)
                ->whereIn('category', array_keys($categoryLabels))
                ->whereDate('exam_date', $exam_date)
                ->where('exam_session',$exam_session)
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

}
