<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\ExamConfirmedHalls;
use App\Models\CIPaperReplacements;
use Illuminate\Support\Facades\Storage;
use App\Services\ImageCompressService;
use App\Models\ExamSession;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class CIPaperReplacementsController extends Controller
{
    protected $imageService;
    public function __construct(ImageCompressService $imageService)
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth.multi');
        $this->imageService = $imageService;
    }
    public function saveReplacementDetails(Request $request)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'exam_id' => 'required|numeric',
            'exam_sess_date' => 'required|date',
            'exam_sess_session' => 'required|string',
            'registration_number' => 'required|string|max:255',
            // 'replacement_type' => 'required|in:damaged,shortage',
            'replacement_type_paper' => 'required',
            'old_paper_number' => 'nullable|string|max:255', // Only for damaged
            'new_paper_number_damaged' => 'nullable|string|max:255', // Damaged type
            'new_paper_number_shortage' => 'nullable|string|max:255', // Shortage type
            'replacement_reason' => 'required|string|max:500',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // File upload
            'cropped_image' => 'nullable|string', // Base64 string
        ]);
        //   dd($validated);
        // Determine the new paper number based on replacement type
        $newPaperNumber = $request->replacement_type === 'damaged'
            ? $validated['new_paper_number_damaged']
            : $validated['new_paper_number_shortage'];

        // Retrieve user and exam hall information
        $exam_date = $validated['exam_sess_date'];
        $sessions = $validated['exam_sess_session'];

        // dd($exam_date);
         $examId =$validated['exam_id'];
        $role = session('auth_role');
        $guard = $role ? Auth::guard($role) : null;
        $user = $guard ? $guard->user() : null;
        $ci_id = $user ? $user->ci_id : null;
        $session = ExamSession::with('currentexam')
            ->where('exam_sess_mainid', $examId)
            ->where('exam_sess_session', $sessions)->first();
            // dd($session);
        $examConfirmedHall = ExamConfirmedHalls::where('exam_id', $validated['exam_id'])
            ->where('exam_date', $exam_date)
            ->where('exam_session', $sessions)
            ->where('ci_id', $ci_id)
            ->first();

        if (!$examConfirmedHall) {
            return redirect()->back()->with('error', 'No matching record found.');
        }

        $centerCode = $examConfirmedHall->center_code;
        $hallCode = $examConfirmedHall->hall_code;
        // dd($examConfirmedHall);
        // Handle photo upload
        $photoPath = null;

        // If traditional file upload is present
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('public/replacements');
        }

        // Handle Base64 cropped image
        if (!empty($validated['cropped_image'])) {
            try {
                $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $validated['cropped_image']);
                $imageData = base64_decode($imageData);

                if ($imageData === false) {
                    throw new \Exception('Base64 decode failed.');
                }

                // Generate unique image name
                $imageName = 'replacement_' . time() . '.png';
                $imagePath = 'images/replacements/' . $imageName;

                // Store the image
                Storage::disk('public')->put($imagePath, $imageData);

                // Optionally compress the image if needed
                $fullImagePath = storage_path('app/public/' . $imagePath);
                if (filesize($fullImagePath) > 200 * 1024) { // If size > 200KB
                    $this->imageService->saveAndCompressImage($imageData, $fullImagePath, 200);
                }

                // Use Base64 image path if processed successfully
                $photoPath = $imagePath;
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Failed to process cropped image. ' . $e->getMessage());
            }
        }

        // Save data to the database
        $exam_type = $session->exam_sess_type;

        if ($exam_type == 'Objective') {
            $replacement_type = "OMR Sheet";
            // dd($replacement_type);
        } else {
            $replacement_type = "Question Cum Answer Booklet";
            // dd($replacement_type);
        }
        
        try {
            CIPaperReplacements::create([
                'exam_id' => $validated['exam_id'],
                'exam_date' => $validated['exam_sess_date'],
                'exam_session' => $validated['exam_sess_session'],
                'registration_number' => $validated['registration_number'],
                'replacement_type' => $replacement_type,
                'replacement_type_paper' => $validated['replacement_type_paper'],
                'old_paper_number' => $validated['old_paper_number'], // Only for damaged
                'new_paper_number' => $newPaperNumber, // Dynamic based on type
                'replacement_reason' => $validated['replacement_reason'],
                'ci_id' => $ci_id,
                'center_code' => $centerCode,
                'hall_code' => $hallCode,
                'replacement_photo' => $photoPath, // Either traditional upload or Base64 image
            ]);

            return redirect()->back()->with('success', 'Replacement details saved successfully!');
        } catch (\Exception $e) {
            Log::error('Error saving replacement details: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while saving the details. Please try again.');
        }
    }

}
