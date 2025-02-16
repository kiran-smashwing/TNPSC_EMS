<?php

namespace App\Http\Controllers;
use App\Models\Currentexam;
use App\Http\Controllers\Controller;
use Carbon\Carbon;


class CompletedExamController extends Controller
{
    public function __construct()
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth.multi');
    }

    public function index()
    {
        // Get today's date in 'DD-MM-YYYY' format (to match database format)
        $today = Carbon::today()->format('d-m-Y');

        // Fetch only the current exams that have not yet ended (last date >= today)
        $exams = Currentexam::withCount('examsession')
        ->whereRaw("TO_DATE(exam_main_lastdate, 'DD-MM-YYYY') < TO_DATE(?, 'DD-MM-YYYY')", [$today])
        ->orderBy('exam_main_createdat', 'desc')
        ->get();
        // dd($exams);
        return view('completed_exam.index', compact('exams'));
    }
    public function task()
    {
        return view('completed_exam.task');
    }
    public function edit()
    {
        return view('completed_exam.edit');
    }
}