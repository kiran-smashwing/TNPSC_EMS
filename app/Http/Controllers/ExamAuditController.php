<?php

namespace App\Http\Controllers;

use App\Services\ExamAuditService;
use Illuminate\Http\Request;

class ExamAuditController extends Controller
{
    protected $auditService;

    public function __construct(ExamAuditService $auditService)
    {
        $this->auditService = $auditService;
    }

    public function timeline(Request $request, $examId)
    {
        $timeline = $this->auditService->getExamTimeline($examId);
        return view('exams.audit-timeline', compact('timeline'));
    }
}
