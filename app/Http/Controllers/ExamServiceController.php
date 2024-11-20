<?php

namespace App\Http\Controllers;

use App\Models\ExamService;
use App\Services\AuditLogger;
use Illuminate\Http\Request;

class ExamServiceController extends Controller
{
    public function __construct()
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth.multi');
    }
    public function index()
    {
        // Fetch all exam services from the database
        $examServices = ExamService::all();
        // Return the view with the exam services data
        return view('masters.department.exam_service.index', compact('examServices'));
    }
    public function create()
    {
        return view('masters.department.exam_service.create');
    }
    public function store(Request $request)
    {
        // Validation for the request data
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'code'    => 'required|string|max:50',
        ]);
        try {
            // Create a new exam service record
            $examService = ExamService::create([
                'examservice_name'    => $validated['name'],
                'examservice_code'    => $validated['code'],
            ]);
            // Log the creation of the exam service
            AuditLogger::log('Exam Service Created', ExamService::class, $examService->examservice_id, null, $examService->toArray());
            // Redirect to the exam service list or wherever necessary
            return redirect()->route('exam-service')->with('success', 'Exam service created successfully.');
        } catch (\Exception $e) {
            // If there's an error, return back with an error message
            return back()->withInput()->with('error', 'Error creating exam service: ' . $e->getMessage());
        }
    }
    public function edit($id)
    {
        // Fetch the exam service by ID
        $examService = ExamService::findOrFail($id);

        // Return the edit view with the exam service data
        return view('masters.department.exam_service.edit', compact('examService'));
    }

    public function update(Request $request, $id)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'examservice_name' => 'required|string|max:255',
            'examservice_code' => 'required|string|max:50',
        ]);

        try {
            // Find the ExamService by ID
            $examService = ExamService::findOrFail($id);

            // Update the exam service fields
            $examService->update([
                'examservice_name' => $validated['examservice_name'],
                'examservice_code' => $validated['examservice_code'],
            ]);

            // Log the update (optional)
            AuditLogger::log('Exam Service Updated', ExamService::class, $examService->examservice_id, null, $examService->toArray());

            // Redirect to the index page with a success message
            return redirect()->route('exam-service')
                ->with('success', 'Exam Service updated successfully.');
        } catch (\Exception $e) {
            // Return back with the error message if the update fails
            return back()->withInput()->with('error', 'Error updating exam service: ' . $e->getMessage());
        }
    }
    public function toggleExamServiceStatus($id)
    {
        try {
            // Find the ExamService by ID
            $examService = ExamService::findOrFail($id);

            // Get the previous status for logging
            $previousStatus = $examService->examservice_status;

            // Temporarily disable timestamp updating for this operation
            $examService->timestamps = false;

            // Toggle the status (examservice_status column is being toggled)
            $examService->examservice_status = !$examService->examservice_status;
            $examService->save();

            // Restore timestamp behavior for subsequent operations
            $examService->timestamps = true;

            // Log the status change in the audit log
            AuditLogger::log(
                'Exam Service Status Toggled',
                ExamService::class,
                $examService->examservice_id,
                null,
                [
                    'previous_status' => $previousStatus,
                    'new_status' => $examService->examservice_status
                ]
            );

            // Return a JSON response with the updated status
            return response()->json([
                'success' => true,
                'status' => $examService->examservice_status,  // Corrected to examservice_status
                'message' => 'Exam Service status updated successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status',
                'details' => $e->getMessage(), // Optional for debugging
            ], 500);
        }
    }
}
