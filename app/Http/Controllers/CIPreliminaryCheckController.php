<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\CIChecklist;

class CIPreliminaryCheckController extends Controller
{
    public function __construct()
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth.multi');
    }

    /**
     * Retrieve all data from the CIChecklist table.
     *
     * @return \Illuminate\Http\Response
     */

     public function saveChecklist(Request $request)
     {
         // Validate the incoming data
         $validated = $request->validate([
             'checklist' => 'required|array', // Ensure 'checklist' is an array
             'exam_id' => 'required|exists:exam_confirmed_halls,exam_id', // Ensure the 'exam_id' exists in the 'exam_confirmed_halls' table
         ]);
         
         // Retrieve the exam details from the 'exam_confirmed_halls' table based on the 'exam_id'
         $examDetails = DB::table('exam_confirmed_halls')
             ->where('exam_id', $validated['exam_id'])
             ->first(); // Use `first()` to get a single record
         
         // If no exam details are found, you can handle it accordingly (e.g., abort or redirect with an error message)
         if (!$examDetails) {
             return back()->withErrors(['exam_id' => 'Exam not found in confirmed halls.']);
         }

        
         $role = session('auth_role');
        $guard = $role ? Auth::guard($role) : null;
        $user = $guard ? $guard->user() : null;
        $ci_id=$user->ci_id;
        dd($examDetails,$validated,$ci_id);
     
         // You can now work with $examDetails, for example:
         // dd($examDetails); // Uncomment this to inspect the fetched data
     
         // Save each selected checklist item to the database
        //  foreach ($validated['checklist'] as $checklistId) {
        //      DB::table('ci_checklist_submissions')->insert([
        //          'ci_checklist_id' => $checklistId,
        //          'exam_id' => $validated['exam_id'], // Include exam_id in your submission record
        //          'submitted_at' => now(),
        //      ]);
        //  }
     
         // Redirect back with a success message
         return redirect()->back()->with('success', 'Checklist saved successfully!');
     }
     
}
