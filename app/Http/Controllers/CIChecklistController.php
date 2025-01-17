<?php

namespace App\Http\Controllers;

use App\Models\CIChecklist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Services\AuditLogger;

class CIChecklistController extends Controller
{
    public function __construct()
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth.multi');
    }
    public function index()
    {
        // Retrieve all CiChecklist records without pagination
        $ciChecklists = CiChecklist::all();

        // Pass the data to the view
        return view('masters.department.ci_checklist.index', compact('ciChecklists'));
    }



    public function create()
    {
        return view('masters.department.ci_checklist.create');
    }

    public function store(Request $request)
    {
        // Validate incoming request data
        $validated = $request->validate([
            // 'ci_checklist_examid' => 'required|string|max:50',  // Assuming examid is a string, adjust accordingly
            'ci_checklist_type'   => 'required|string|max:255', // Checklist type, e.g., "document", "process", etc.
            'ci_checklist_description' => 'nullable|string',    // Description field, not mandatory
        ]);

        try {
            // Create a new CI Checklist record in the database
            $ciChecklist = CIChecklist::create([
                // 'ci_checklist_examid'        => $validated['ci_checklist_examid'],
                'ci_checklist_type'          => $validated['ci_checklist_type'],
                'ci_checklist_description'   => $validated['ci_checklist_description'] ?? null,  // Handle nullable field
            ]);

            // Log the creation of the new CI Checklist using the AuditLogger
            AuditLogger::log('CI Checklist Created', CIChecklist::class, $ciChecklist->ci_checklist_id, null, $ciChecklist->toArray());

            // Redirect or return a success response
            return redirect()->route('ci-checklist')->with('success', 'CI Checklist created successfully.');
        } catch (\Exception $e) {
            // In case of error, return back with an error message
            return back()->withInput()->with('error', 'Error creating CI Checklist: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        // Retrieve the CiChecklist record by its ID
        $ciChecklist = CiChecklist::findOrFail($id);

        // Return the edit view with the CiChecklist data
        return view('masters.department.ci_checklist.edit', compact('ciChecklist'));
    }

    public function update(Request $request, $id)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'ci_checklist_type'        => 'required|string|max:255',
            'ci_checklist_description' => 'required|string|max:1000',
        ]);

        try {
            // Find the CiChecklist by ID
            $ciChecklist = CiChecklist::findOrFail($id);

            // Update the CiChecklist record with the validated data
            $ciChecklist->update([
                'ci_checklist_type'        => $validated['ci_checklist_type'],
                'ci_checklist_description' => $validated['ci_checklist_description'],
            ]);

            // Log the update action
            AuditLogger::log('CiChecklist Updated', CiChecklist::class, $ciChecklist->ci_checklist_id, null, $ciChecklist->toArray());

            // Redirect to the index page with a success message
            return redirect()->route('ci-checklist')->with('success', 'CiChecklist updated successfully.');
        } catch (\Exception $e) {
            // If there's an error, return back with an error message
            return back()->withInput()->with('error', 'Error updating CiChecklist: ' . $e->getMessage());
        }
    }

    public function toggleCiChecklistStatus($id)
    {
        try {
            // Find the CiChecklist by ID
            $ciChecklist = CiChecklist::findOrFail($id);

            // Get the previous status for logging (assuming `ci_checklist_status` is the column you are toggling)
            $previousStatus = $ciChecklist->ci_checklist_status;

            // Temporarily disable automatic timestamp updates
            $ciChecklist->timestamps = false;

            // Toggle the status (e.g., 1 = Active, 0 = Inactive)
            $ciChecklist->ci_checklist_status = !$ciChecklist->ci_checklist_status;

            // Save the updated status without updating `updated_at`
            $ciChecklist->save();

            // Re-enable the timestamp updates
            $ciChecklist->timestamps = true;

            // Log the status change in the audit log
            AuditLogger::log(
                'CiChecklist Status Toggled',
                CiChecklist::class,
                $ciChecklist->id,
                null,
                [
                    'previous_status' => $previousStatus,
                    'new_status' => $ciChecklist->ci_checklist_status
                ]
            );

            // Return a JSON response with the updated status
            return response()->json([
                'success' => true,
                'status' => $ciChecklist->ci_checklist_status,
                'message' => 'CiChecklist status updated successfully',
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
