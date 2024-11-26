<?php

namespace App\Http\Controllers;

use App\Models\Currentexam;
use App\Models\ExamSession;
use Illuminate\Support\Facades\DB;


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
        // Fetch the audit details for the exam
        $auditDetails = DB::table('exam_audit_logs')
            ->where('exam_id', $examId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('my_exam.task', compact('session','auditDetails'));
    }

}
