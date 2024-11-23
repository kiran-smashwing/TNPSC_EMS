<?php

namespace App\Http\Controllers;

use App\Models\Currentexam;
use App\Models\ExamSession;


class MyExamController extends Controller
{
    public function __construct()
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth.multi');
    }

    public function index()
    {
        $exams = Currentexam::with('examsession')->get(); // Fetch all exams with their related exam sessions
        return view('my_exam.index', compact('exams')); // Pass the exam to
    }
    public function task($examId, $sessionId)
    {
        $session = ExamSession::with([
            'currentexam.examservice' // Load the exam and its related service
        ])->find($sessionId);

        if (!$session) {
            abort(404, 'Session not found');
        }
        
        return view('my_exam.task', compact('session'));
    }


}
