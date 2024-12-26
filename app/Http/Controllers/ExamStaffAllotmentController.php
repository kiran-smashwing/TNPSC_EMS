<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class ExamStaffAllotmentController extends Controller
{
    public function __construct()
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth.multi');
    }
    public function saveinvigilatoreDetails(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'exam_id' => 'required', // Validate exam_id exists in the exams table
            'exam_sess_date' => 'required|date', // Validate exam_sess_date is a valid date
            'invigilators' => 'required|array', // Ensure invigilators array is not empty
            // 'invigilators.*' => 'integer|exists:invigilators,invigilator_id', // Validate each invigilator ID exists in the invigilators table
        ]);
    
        // Find the existing record in the exam_invigilators table or create a new one
        $examInvigilatorRecord = DB::table('ci_staff_allocation')
            ->where('exam_id', $validated['exam_id'])
            ->where('exam_date', $validated['exam_sess_date'])
            ->first();
    
        // If the record doesn't exist, create it, otherwise update it
        // dd($examInvigilatorRecord);
        if (!$examInvigilatorRecord) {
            // Insert new record if it doesn't exist
            foreach ($validated['invigilators'] as $invigilatorId) {
                DB::table('exam_invigilators')->insert([
                    'exam_id' => $validated['exam_id'],
                    'exam_sess_date' => $validated['exam_sess_date'],
                    'invigilator_id' => $invigilatorId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } else {
            // If record exists, update the invigilators
            DB::table('ci_staff_allocation')
                ->where('exam_id', $validated['exam_id'])
                ->where('exam_date', $validated['exam_sess_date'])
                ->delete(); // Remove existing invigilators for this session
    
            // Insert new invigilators for this session
            foreach ($validated['invigilators'] as $invigilatorId) {
                DB::table('ci_staff_allocation')->insert([
                    'exam_id' => $validated['exam_id'],
                    'exam_date' => $validated['exam_sess_date'],
                    'invigilator_id' => $invigilatorId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    
        // Return back to the previous page with a success message
        return redirect()->back()->with('success', 'Invigilators saved successfully!');
    }
    
    
    
    
}
