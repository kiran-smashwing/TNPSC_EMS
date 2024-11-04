<?php

namespace App\Http\Controllers;

use App\Models\MobileTeamStaffs;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Services\ImageCompressService;
use App\Services\AuditLogger;

class MobileTeamStaffsController extends Controller
{
    protected $imageService;
    public function __construct(ImageCompressService $imageService)
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth');
        $this->imageService = $imageService;
    }

    public function index()
    {
    $mobileTeams = MobileTeamStaffs::with('district')->paginate(10); // Get all records
    return view('masters.district.mobile_team_staffs.index', compact('mobileTeams'));
    }

    public function create()
    {
        $districts = District::all();
        return view('masters.district.mobile_team_staffs.create', compact('districts'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'district_id' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'employee_id' => 'required|string|max:255|unique:mobile_team,mobile_employeeid',
            'designation' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'mail' => 'required|email|unique:mobile_team,mobile_email',
            'password' => 'required|string|min:6',
            'cropped_image' => 'nullable|string'
        ]);

        try {
            if (!empty($validated['cropped_image'])) {
                // Remove the data URL prefix if present
                $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $validated['cropped_image']);
                $imageData = base64_decode($imageData);

                if ($imageData === false) {
                    throw new \Exception('Base64 decode failed.');
                }

                // Create a unique image name
                $imageName = 'mobile_team_' . time() . '.png';
                $imagePath = 'images/mobile_team/' . $imageName;

                // Store the image
                $stored = Storage::disk('public')->put($imagePath, $imageData);

                if (!$stored) {
                    throw new \Exception('Failed to save image to storage.');
                }


                // Compress the image if it exceeds 200 KB
                $fullImagePath = storage_path('app/public/' . $imagePath);
                if (filesize($fullImagePath) > 200 * 1024) {
                    // Use the ImageService to save and compress the image
                    $this->imageService->saveAndCompressImage($imageData, $fullImagePath, 200);  // 200 KB max size
                }

                $validated['image'] = $imagePath;

            }

            // Hash the password
            $validated['mobile_password'] = Hash::make($validated['password']);

            // Create a new Mobile Team member
             $mobileTeamStaff= MobileTeamStaffs::create([
                'mobile_district_id' => $validated['district_id'],
                'mobile_name' => $validated['name'],
                'mobile_designation' => $validated['designation'],
                'mobile_phone' => $validated['phone'],
                'mobile_email' => $validated['mail'],
                'mobile_employeeid' => $validated['employee_id'],
                'mobile_password' => $validated['mobile_password'],
                'mobile_image' => $validated['image'] ?? null,
                
            ]);
            AuditLogger::log('Mobile Team Staff Created', MobileTeamStaffs::class, $mobileTeamStaff->id, null, $mobileTeamStaff->toArray());

            return redirect()->route('mobile-team')
                ->with('success', 'Mobile team member created successfully');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error creating mobile team member: ' . $e->getMessage());
        }
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

    // Validate the request data
    $validated = $request->validate([
        'district_id' => 'required|exists:districts,district_id',
        'name' => 'required|string|max:255',
        'employee_id' => 'required|string|max:255|unique:mobile_team_staffs,employee_id,' . $id,
        'designation' => 'required|string|max:255',
        'mail' => 'required|email|max:255',
        'phone' => 'required|string|max:15',
        'password' => 'nullable|string|min:6',
        'cropped_image' => 'nullable|string' // Base64 encoded string
    ]);

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
            $imageName = 'mobile_team_staff_' . time() . '.jpg';
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
            if ($staffMember->image && Storage::disk('public')->exists($staffMember->image)) {
                Storage::disk('public')->delete($staffMember->image);
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
            'mobile_district_id' => $validated['district_id'],
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
        AuditLogger::log('Mobile Team Staff Updated', MobileTeamStaffs::class, $staffMember->id, $oldValues, $changedValues);

        return redirect()->route('mobile-team')
            ->with('success', 'Mobile team staff updated successfully.');

    } catch (\Exception $e) {
        return back()->withInput()
            ->with('error', 'Error updating mobile team staff: ' . $e->getMessage());
    }
}
    public function show()
    {

        return view('masters.district.mobile_team_staffs.show');
    }
}
