<?php

namespace App\Http\Controllers;

use App\Models\ExamMaterialsData;
use App\Models\MobileTeamStaffs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class ExamMaterialsRouteController extends Controller
{
    public function index($examId)
    {
        return view('my_exam.District.materials-route.index', compact('examId'));
    }
    public function createRoute($examId)
    {
        // Get authenticated user
        $role = session('auth_role');
        $guard = $role ? Auth::guard($role) : null;
        $user = $guard ? $guard->user() : null;
        $district_code = $user->district_code;
        $mobileTeam = MobileTeamStaffs::where('mobile_district_id', $district_code)->get();
        //get center code for the user 
        $centers = ExamMaterialsData::where('exam_id', $examId)
            ->where('district_code', $user->district_code)
            ->join('centers', 'exam_materials_data.center_code', '=', 'centers.center_code')
            ->groupBy('centers.center_code', 'centers.center_name')
            ->select('centers.center_name', 'centers.center_code')
            ->get();
        // get hall from exam_materials_data by each groupped centercode 
        // Get all hall codes grouped by center code within the user's district
        $halls = ExamMaterialsData::where('exam_id', $examId)
            ->where('district_code', $user->district_code)
            ->join('centers', 'exam_materials_data.center_code', '=', 'centers.center_code')
            ->groupBy('exam_materials_data.center_code', 'centers.center_name', 'exam_materials_data.hall_code',)
            ->select(
                'centers.center_name',
                'exam_materials_data.center_code',
                'exam_materials_data.hall_code'
            )
            ->orderBy('exam_materials_data.center_code') // Optional: Order by center code
            ->get();
        return view('my_exam.District.materials-route.create', compact('examId', 'mobileTeam', 'centers', 'halls'));
    }
    public function editRoute()
    {
        return view('my_exam.District.materials-route.edit');
    }
    public function storeRoute(Request $request)
    {
        dd($request->all());
    }

}