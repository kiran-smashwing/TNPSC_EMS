<?php

namespace App\Http\Controllers;

use App\Models\MobileTeamStaffs;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Services\ImageCompressService;
use App\Services\AuditLogger;

class MobileTeamStaffsController extends Controller
{
    protected $imageService;
    public function __construct(ImageCompressService $imageService)
    {
        //apply the auth middleware to the entire controller
        $this->middleware(middleware: 'auth.multi');
        $this->imageService = $imageService;
    }

    public function index(Request $request)
    {
        // Start the query for MobileTeamStaffs with the district relationship
        $query = MobileTeamStaffs::with('district');

        // Filter by district if a district is selected
        if ($request->filled('district')) {
            $query->where('mobile_district_id', $request->input('district')); // Adjust the column as per your table schema
        }

        // Fetch filtered MobileTeamStaffs with pagination
        $mobileTeams = $query->orderBy('mobile_name')->get();

        // Fetch unique district values from the same table
        $districts = District::all(); // Fetch all districts

        return view('masters.district.mobile_team_staffs.index', compact('mobileTeams', 'districts'));
    }


    public function create()
    {
        $districts = District::all();
        return view('masters.district.mobile_team_staffs.create', compact('districts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'district' => 'required|numeric',
            'name' => 'required|string|max:255',
            'employee_id' => 'required|string|max:255|unique:mobile_team,mobile_employeeid',
            'designation' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'email' => 'required|email|unique:mobile_team,mobile_email',
            'password' => 'required|string|min:6',
            'cropped_image' => 'nullable|string',
        ]);

        try {
            // Process the cropped image if present
            if (!empty($validated['cropped_image'])) {
                $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $validated['cropped_image']);
                $imageData = base64_decode($imageData);

                if ($imageData === false) {
                    throw new \Exception('Base64 decode failed.');
                }

                $imageName = $validated['employee_id'] . time() . '.png';
                $imagePath = 'images/mobile_team/' . $imageName;

                $stored = Storage::disk('public')->put($imagePath, $imageData);

                if (!$stored) {
                    throw new \Exception('Failed to save image to storage.');
                }

                $validated['image'] = $imagePath;
            }

            // Hash the password and generate a verification token
            $validated['mobile_password'] = Hash::make($validated['password']);
            $validated['verification_token'] = Str::random(64);

            // Create the mobile team record
            $mobileTeamMember = MobileTeamStaffs::create([
                'mobile_district_id' => $validated['district'],
                'mobile_name' => $validated['name'],
                'mobile_employeeid' => $validated['employee_id'],
                'mobile_designation' => $validated['designation'],
                'mobile_phone' => $validated['phone'],
                'mobile_email' => $validated['email'],
                'mobile_password' => $validated['mobile_password'],
                'mobile_image' => $validated['image'] ?? null,
                'verification_token' => $validated['verification_token'],
            ]);

            // Log the creation with the audit logger
            AuditLogger::log('Mobile Team Member Created', MobileTeamStaffs::class, $mobileTeamMember->mobile_id, null, $mobileTeamMember->toArray());

            // Send the welcome email
            Mail::send('email.mobile_team_created', [
                'name' => $mobileTeamMember->mobile_name,
                'email' => $mobileTeamMember->mobile_email,
                'password' => $request->password, // Plain password for first login
            ], function ($message) use ($mobileTeamMember) {
                $message->to($mobileTeamMember->mobile_email)
                    ->subject('Welcome to the Mobile Team');
            });

            // Send the email verification link
            Mail::send('email.mobile_verification', [
                'name' => $mobileTeamMember->mobile_name,
                'email' => $mobileTeamMember->mobile_email,
                'verification_link' => route('mobile_team.verifyEmail', ['token' => urlencode($mobileTeamMember->verification_token)]),
            ], function ($message) use ($mobileTeamMember) {
                $message->to($mobileTeamMember->mobile_email)
                    ->subject('Verify Your Email Address');
            });

            // Redirect with success message
            return redirect()->route('mobile-team-staffs.index')
                ->with('success', 'Mobile team member created successfully. Email verification link has been sent.');
        } catch (\Exception $e) {
            // Handle exceptions and show an error message
            return back()->withInput()
                ->with('error', 'Error creating mobile team member: ' . $e->getMessage());
        }
    }

    public function verifyEmail($token)
    {
        // Log the received verification token
        Log::info('Mobile team email verification token received: ' . $token);

        // Decode the token if URL encoded
        $decodedToken = urldecode($token);

        // Search for the mobile team member with the given token
        $mobileTeamMember = MobileTeamStaffs::where('verification_token', $decodedToken)->first();

        if (!$mobileTeamMember) {
            // Log an error if the token is invalid
            Log::error('Mobile team email verification failed: Token not found. Token: ' . $decodedToken);

            // Redirect to the mobile team index page with an error message
            return redirect()->route('mobile-team-staffs.index')
                ->with('error', 'Invalid verification link. Please contact support if this issue persists.');
        }

        // Update the email verification status and clear the token
        $mobileTeamMember->update([
            'mobile_email_status' => true, // Using the correct column for email verification status
            'verification_token' => null,   // Clear the token after verification
        ]);

        // Log the successful verification
        Log::info('Mobile team email verified successfully for ID: ' . $mobileTeamMember->mobile_employeeid);

        // Redirect to the mobile team index page with a success message
        return redirect()->route('mobile-team-staffs.index')
            ->with('success', 'Email verified successfully. Welcome to the Mobile Team!');
    }



    public function edit($mobile_id)
    {

        $mobileTeamStaff = MobileTeamStaffs::findOrFail($mobile_id);
        $districts = District::all(); // Fetch all districts
        return view('masters.district.mobile_team_staffs.edit', compact('mobileTeamStaff', 'districts'));
    }
    public function update(Request $request, $id)
    {
        // Find the mobile team staff member by ID
        $staffMember = MobileTeamStaffs::findOrFail($id);
        $messages = [
            'district.required' => 'Please select a district',
            'district.integer' => 'Please select a valid district',
        ];
        // Validate the request data
        $validated = $request->validate([
            'district' => 'required|numeric',
            'name' => 'required|string|max:255',
            'employee_id' => 'required|string|max:255|unique:mobile_team,mobile_employeeid,' . $id . ',mobile_id',
            'designation' => 'required|string|max:255',
            'mail' => 'required|email|unique:mobile_team,mobile_email,' . $id . ',mobile_id',
            'phone' => 'required|string|max:15',
            'password' => 'nullable|string|min:6',
            'cropped_image' => 'nullable|string' // Base64 encoded string
        ], $messages);

        try {
            $newImagePath = null;

            // Process the image if provided
            if (!empty($validated['cropped_image'])) {
                $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $validated['cropped_image']);
                $imageData = base64_decode($imageData);

                if ($imageData === false) {
                    throw new \Exception('Base64 decode failed.');
                }

                // Create a unique filename
                $imageName = $validated['name'] . time() . '.png';
                $imagePath = 'images/mobile_team_staffs/' . $imageName;

                // Save the image in public storage
                Storage::disk('public')->put($imagePath, $imageData);

                // Optionally compress the image if it exceeds a certain size
                $fullImagePath = storage_path('app/public/' . $imagePath);
                if (filesize($fullImagePath) > 200 * 1024) {
                    // Assuming you have an image service for compression
                    $this->imageService->saveAndCompressImage($imageData, $fullImagePath, 200);
                }

                // Delete the old image if it exists
                if ($staffMember->mobile_image && Storage::disk('public')->exists($staffMember->mobile_image)) {
                    Storage::disk('public')->delete($staffMember->mobile_image);
                }

                // Set new image path to be saved in the database
                $newImagePath = $imagePath;
            }

            // Only update password if provided
            if ($request->filled('password')) {
                $validated['district_password'] = Hash::make($validated['password']);
            }

            // Prepare data for update, including the new image path if it exists
            $updateData = [
                'mobile_district_id' => $validated['district'],
                'mobile_name' => $validated['name'],
                'mobile_employeeid' => $validated['employee_id'],
                'mobile_designation' => $validated['designation'],
                'mobile_email' => $validated['mail'],
                'mobile_phone' => $validated['phone'],
                'mobile_password' => $validated['district_password'] ?? $staffMember->mobile_password
            ];

            // Add new image path to update data if present
            if ($newImagePath) {
                $updateData['mobile_image'] = $newImagePath;
            }

            // Get old values and update the staff member
            $oldValues = $staffMember->getOriginal();
            $staffMember->update($updateData);

            // Get changed values for logging
            $changedValues = $staffMember->getChanges();
            $oldValues = array_intersect_key($oldValues, $changedValues);

            // Log staff member update with old and new values (assuming you have a logging mechanism)
            AuditLogger::log('Mobile Team Staff Updated', MobileTeamStaffs::class, $staffMember->mobile_id, $oldValues, $changedValues);
                if (url()->previous() === route('mobile-team-staffs.edit', $id)) {
                    return redirect()->route('mobile-team-staffs.index')
                        ->with('success', 'Mobile team staff updated successfully.');
                } else {
                    return redirect()->back()->with('success', 'Mobile team staff updated successfully.');
                }
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error updating mobile team staff: ' . $e->getMessage());
        }
    }
    public function show($id)
    {

        $team = MobileTeamStaffs::with('district')->findOrFail($id); // Ensure MobileTeam is the correct model
        // dd($team);
        return view('masters.district.mobile_team_staffs.show', compact('team'));
    }
    public function toggleStatus($id)
    {
        try {
            $staffMember = MobileTeamStaffs::findOrFail($id);

            // Get current status before update
            $oldStatus = $staffMember->mobile_status;

            // Toggle the status
            $staffMember->mobile_status = !$staffMember->mobile_status;
            $staffMember->save();

            // Log the status change
            AuditLogger::log(
                'Mobile Staff Status Changed',
                District::class,
                $staffMember->mobile_id,
                ['status' => $oldStatus],
                ['status' => $staffMember->mobile_status]
            );

            return response()->json([
                'success' => true,
                'status' => $staffMember->mobile_status,
                'message' => 'Mobile Staff status updated successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update mobile staff status',
                'details' => $e->getMessage(),  // Optional
            ], 500);
        }
    }
}
