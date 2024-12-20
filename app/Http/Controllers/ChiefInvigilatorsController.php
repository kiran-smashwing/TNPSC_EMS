<?php

namespace App\Http\Controllers;

use App\Models\ChiefInvigilator;
use App\Models\Center;
use App\Models\District;
use App\Models\Venues;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Services\AuditLogger;
use Illuminate\Support\Facades\Storage;
use App\Services\ImageCompressService;

class ChiefInvigilatorsController extends Controller
{
    protected $imageService;

    public function __construct(ImageCompressService $imageService)
    {
        $this->middleware('auth.multi');
        $this->imageService = $imageService;
    }


    public function index(Request $request)
    {
        // Start the query for ChiefInvigilator
        $query = ChiefInvigilator::query();

        // Filter by district if selected
        if ($request->filled('district')) {
            $query->where('ci_district_id', $request->input('district'));
        }

        // Filter by center if selected
        if ($request->filled('center')) {
            $query->where('ci_center_id', $request->input('center'));
        }

        // Filter by venue if selected
        if ($request->filled('venue')) {
            $query->where('ci_venue_id', $request->input('venue'));
        }

        // Fetch the filtered data with pagination
        $chiefInvigilator = $query->orderBy('ci_name')->get();
        // dd($chiefInvigilator);

        // Fetch unique district values from the same table
        $districts = District::all(); // Fetch all districts


        // Fetch unique centers values from the same table
        $centers = center::all();  // Fetch all centers

        // Fetch unique venues values from the same table
        $venues = venues::all();  // Fetch all venues


        // Return the view with data
        return view('masters.venues.chief_invigilator.index', compact('chiefInvigilator', 'districts', 'centers', 'venues'));
    }





    public function create()
    {
        $venues = Venues::all(); // Retrieve all venues
        $centers = Center::all(); // Retrieve all centers
        $districts = District::all(); // Retrieve all districts

        return view('masters.venues.chief_invigilator.create', compact('venues', 'centers', 'districts'));
    }

    public function store(Request $request)
    {
        // Custom validation messages
        $messages = [
            'district.required' => 'Please select a district',
            'district.integer' => 'Please select a valid district',
            'center.required' => 'Please select a center',
            'center.integer' => 'Please select a valid center',
            'venue.required' => 'Please select a venue',
            'venue.integer' => 'Please select a valid venue',
        ];

        // Validate incoming request data
        $validated = $request->validate([
            'district' => 'required|string|max:50',
            'center' => 'required|string|max:50',
            'venue' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:cheif_invigilator,ci_email',
            'employee_id' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'alternative_phone' => 'nullable|string|max:15',
            'designation' => 'required|string|max:100',
            'cropped_image' => 'nullable|string',
            'password' => 'required|string|min:6',
        ], $messages);

        try {
            // Process and store the image if provided
            if (!empty($validated['cropped_image'])) {
                $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $validated['cropped_image']);
                $imageData = base64_decode($imageData);

                if ($imageData === false) {
                    throw new \Exception('Base64 decode failed.');
                }

                // Generate a unique image name
                $imageName = $validated['email'] . time() . '.png';
                $imagePath = 'images/chiefinvigilator/' . $imageName;

                // Store the image in public storage
                $stored = Storage::disk('public')->put($imagePath, $imageData);

                if (!$stored) {
                    throw new \Exception('Failed to save image to storage.');
                }

                // Compress the image if its size exceeds 200 KB
                $fullImagePath = storage_path('app/public/' . $imagePath);
                if (filesize($fullImagePath) > 200 * 1024) {
                    // Compress the image
                    $this->imageService->saveAndCompressImage($imageData, $fullImagePath, 200);
                }

                $validated['image'] = $imagePath;
            }

            // Hash the password before storing
            $validated['ci_password'] = Hash::make($validated['password']);

            // Generate a verification token
            $verificationToken = Str::random(64); // Create a random string for the token

            // Create the Chief Invigilator record
            $chiefInvigilator = ChiefInvigilator::create([
                'ci_district_id' => $validated['district'],
                'ci_center_id' => $validated['center'],
                'ci_venue_id' => $validated['venue'],
                'ci_name' => $validated['name'],
                'ci_designation' => $validated['designation'],
                'ci_phone' => $validated['phone'],
                'ci_alternative_phone' => $validated['alternative_phone'],
                'ci_id' => $validated['employee_id'],
                'ci_email' => $validated['email'],
                'ci_password' => $validated['ci_password'], // Hash the password
                'ci_image' => $validated['image'] ?? null, // Store image if provided
                'verification_token' => $verificationToken, // Store the verification token
            ]);

            // Send welcome email to Chief Invigilator
            $emailData = [
                'name' => $chiefInvigilator->ci_name,
                'email' => $chiefInvigilator->ci_email,
                'password' => $validated['password'], // Send plain password for first login
            ];

            Mail::send('email.chief_invigilator_created', $emailData, function ($message) use ($emailData) {
                $message->to($emailData['email'])
                    ->subject('Welcome to the Chief Invigilator Role');
            });

            // Send email verification email
            $verificationLink = route('chief-invigilator.verifyEmail', ['token' => urlencode($verificationToken)]);
            $verificationData = [
                'name' => $chiefInvigilator->ci_name,
                'email' => $chiefInvigilator->ci_email,
                'verification_link' => $verificationLink,
            ];

            Mail::send('email.chief_invigilator_verification', $verificationData, function ($message) use ($verificationData) {
                $message->to($verificationData['email'])
                    ->subject('Verify Your Email Address');
            });

            // Log the creation for auditing purposes
            AuditLogger::log('Chief Invigilator Created', ChiefInvigilator::class, $chiefInvigilator->ci_id, null, $chiefInvigilator->toArray());

            // Redirect back with a success message
            return redirect()->route('chief-invigilators.index')
                ->with('success', 'Chief Invigilator created successfully. A verification email has been sent.');
        } catch (\Exception $e) {
            // Handle any errors and return with the error message
            return back()->withInput()
                ->with('error', 'Error creating Chief Invigilator: ' . $e->getMessage());
        }
    }


    public function verifyEmail($token)
    {
        Log::info('Verification token received: ' . $token);

        // Decode the token received in the URL
        $decodedToken = urldecode($token);

        // Look for a Chief Invigilator record matching the verification token
        $chiefInvigilator = ChiefInvigilator::where('verification_token', $decodedToken)->first();

        // Check if the Chief Invigilator exists with the provided token
        if (!$chiefInvigilator) {
            Log::error('Verification failed: Token not found in database. Token: ' . $decodedToken);
            return redirect()->route('chief-invigilators.index')->with('error', 'Invalid verification link.');
        }

        // Update the Chief Invigilator record to mark email as verified and clear the verification token
        $chiefInvigilator->update([
            'ci_email_status' => true, // Set email status to true (verified)
            'verification_token' => null, // Clear the verification token after successful verification
        ]);

        Log::info('Email verified successfully for Chief Invigilator ID: ' . $chiefInvigilator->ci_id);

        // Redirect with a success message
        return redirect()->route('chief-invigilators.index')->with('success', 'Email verified successfully.');
    }

    public function edit($id)
    {
        $venues = Venues::all(); // Retrieve all venues
        $centers = Center::all(); // Retrieve all centers
        $districts = District::all(); // Retrieve all districts
        $chiefInvigilator = ChiefInvigilator::findOrFail($id); // Retrieve the specific Chief Invigilator

        return view('masters.venues.chief_invigilator.edit', compact('chiefInvigilator', 'venues', 'centers', 'districts'));
    }



    public function update(Request $request, $id)
    {
        $chiefInvigilator = ChiefInvigilator::findOrFail($id);
        $messages = [
            'district.required' => 'Please select a district',
            'district.integer' => 'Please select a valid district',
            'center.required' => 'Please select a center',
            'center.integer' => 'Please select a valid center',
            'venue.required' => 'Please select a venue',
            'venue.integer' => 'Please select a valid venue',
        ];
        $validated = $request->validate([
            'district' => 'required|string|max:50',
            'center' => 'required|string|max:50',
            'venue' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:cheif_invigilator,ci_email,' . $chiefInvigilator->ci_id . ',ci_id',
            'employee_id' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'alternative_phone' => 'nullable|string|max:15',
            'designation' => 'required|string|max:100',
            'password' => 'nullable|string|min:6',
            'cropped_image' => 'nullable|string',
        ], $messages);

        try {
            $newImagePath = null;

            // Handle the image if provided
            if (!empty($validated['cropped_image'])) {
                // Remove the data URL prefix if present
                $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $validated['cropped_image']);
                $imageData = base64_decode($imageData);

                if ($imageData === false) {
                    throw new \Exception('Base64 decode failed.');
                }

                // Create a unique image name
                $imageName = $validated['email'] . time() . '.png';
                $imagePath = 'images/chiefinvigilator/' . $imageName;

                // Store the image
                Storage::disk('public')->put($imagePath, $imageData);

                // Compress the image if it exceeds 200 KB
                $fullImagePath = storage_path('app/public/' . $imagePath);
                if (filesize($fullImagePath) > 200 * 1024) {
                    $this->imageService->saveAndCompressImage($imageData, $fullImagePath, 200); // 200 KB max size
                }

                //  Delete the old image if it exists
                if ($chiefInvigilator->ci_image && Storage::disk('public')->exists($chiefInvigilator->ci_image)) {
                    Storage::disk('public')->delete($chiefInvigilator->ci_image);
                }
                // Set new image path to be saved in the database
                $newImagePath = $imagePath;
            }
            // Only update password if provided
            if ($request->filled('password')) {
                $validated['ci_password'] = Hash::make($validated['password']);
            }
            // Update the Chief Invigilator
            // Prepare data for update, including the new image path if it exists
            $updateData = [
                'ci_district_id' => $validated['district'],
                'ci_center_id' => $validated['center'],
                'ci_venue_id' => $validated['venue'],
                'ci_name' => $validated['name'],
                'ci_designation' => $validated['designation'],
                'ci_phone' => $validated['phone'],
                'ci_alternative_phone' => $validated['alternative_phone'],
                'ci_id' => $validated['employee_id'],
                'ci_email' => $validated['email'],
                'ci_password' => $validated['ci_password'] ?? $chiefInvigilator->ci_password, // Use the old password if not updated
            ];
            // Add new image path to update data if present
            if ($newImagePath) {
                $updateData['ci_image'] = $newImagePath;
            }

            // Get old values and update the district
            $oldValues = $chiefInvigilator->getOriginal();
            $chiefInvigilator->update($updateData);

            // Get changed values for logging
            $changedValues = $chiefInvigilator->getChanges();
            $oldValues = array_intersect_key($oldValues, $changedValues);
            // Log district update with old and new values
            AuditLogger::log('Chief Invigilator Updated', ChiefInvigilator::class, $chiefInvigilator->ci_id, $oldValues, $changedValues);
                if (url()->previous() === route('chief-invigilators.edit', $id)) {
                    return redirect()->route('chief-invigilators.index')
                        ->with('success', 'Chief Invigilator updated successfully');
                } else {
                    return redirect()->back()->with('success', 'Chief Invigilator updated successfully');
                }
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error updating Chief Invigilator: ' . $e->getMessage());
        }
    }
    public function show($id)
    {
        $chiefInvigilator = ChiefInvigilator::with(['district', 'venue', 'center'])->findOrFail($id);

        return view('masters.venues.chief_invigilator.show', compact('chiefInvigilator'));
    }

    public function destroy($id)
    {
        $chiefInvigilator = ChiefInvigilator::findOrFail($id);

        AuditLogger::log('Chief Invigilator Deleted', ChiefInvigilator::class, $chiefInvigilator->ci_id);

        $chiefInvigilator->delete();

        return redirect()->route('chief-invigilators.index')
            ->with('success', 'Chief Invigilator deleted successfully');
    }
    public function toggleStatus($id)
    {
        try {
            $chiefInvigilator = ChiefInvigilator::findOrFail($id);

            // Get current status before update
            $oldStatus = $chiefInvigilator->ci_status;

            // Toggle the status
            $chiefInvigilator->ci_status = !$chiefInvigilator->ci_status;
            $chiefInvigilator->save();

            // Log the status change
            AuditLogger::log(
                'ChiefInvigilator Status Changed',
                ChiefInvigilator::class,
                $chiefInvigilator->ci_id,
                ['status' => $oldStatus],
                ['status' => $chiefInvigilator->ci_status]
            );

            return response()->json([
                'success' => true,
                'status' => $chiefInvigilator->ci_status,
                'message' => 'ChiefInvigilator status updated successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update chiefinvigilator status',
                'details' => $e->getMessage(),  // Optional
            ], 500);
        }
    }
}
