<?php

namespace App\Http\Controllers;

use App\Models\TreasuryOfficer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Services\AuditLogger;
use App\Models\District;
use Illuminate\Support\Facades\Storage;
use App\Services\ImageCompressService;

class TreasuryOfficerController extends Controller
{
    protected $imageService;

    public function __construct(ImageCompressService $imageService)
    {
        // $this->middleware('auth:treasury_officers');
        $this->imageService = $imageService;
    }

    public function index(Request $request)
    {
        // Start the query for Treasury Officers with the district relationship
        $query = TreasuryOfficer::with('district');
    
        // Filter by district if a district is selected
        if ($request->filled('district')) {
            // Apply filter using the correct column `tre_off_district_id`
            $query->where('tre_off_district_id', $request->input('district'));
        }
    
        // Fetch filtered Treasury Officers
        $treasuryOfficers = $query->paginate(10);
    
        $districts = TreasuryOfficer::select('tre_off_district_id')
            ->distinct()
            ->get();
    
        return view('masters.district.treasury_Officers.index', compact('treasuryOfficers', 'districts'));
    }
    

    public function create()
    {
        $districts = District::all(); // Fetch all districts
        return view('masters.district.treasury_Officers.create', compact('districts'));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'district' => 'required|string|max:50',
        'name' => 'required|string|max:255',
        'designation' => 'required|string|max:255',
        'phone' => 'required|string|max:15',
        'email' => 'required|email|unique:treasury_officer,tre_off_email',
        'employeeid' => 'required|string|unique:treasury_officer,tre_off_employeeid',
        'password' => 'required|string|min:6',
        'cropped_image' => 'nullable|string',
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
            $imageName = 'tre_off_' . time() . '.png';
            $imagePath = 'images/treasury/' . $imageName;

            // Store the image
            $stored = Storage::disk('public')->put($imagePath, $imageData);

            if (!$stored) {
                throw new \Exception('Failed to save image to storage.');
            }

            // Compress the image if it exceeds 200 KB
            $fullImagePath = storage_path('app/public/' . $imagePath);
            if (filesize($fullImagePath) > 200 * 1024) {
                $this->imageService->saveAndCompressImage($imageData, $fullImagePath, 200); // 200 KB max size
            }

            $validated['image'] = $imagePath;
        }

        // Hash the password
        $plainPassword = $validated['password']; // Store the plain password for email
        $validated['password'] = Hash::make($validated['password']);

        // Create the treasury officer record
        $treasuryOfficer = TreasuryOfficer::create([
            'tre_off_district_id' => $validated['district'],
            'tre_off_name' => $validated['name'],
            'tre_off_designation' => $validated['designation'],
            'tre_off_phone' => $validated['phone'],
            'tre_off_email' => $validated['email'],
            'tre_off_employeeid' => $validated['employeeid'],
            'tre_off_password' => $validated['password'],
            'tre_off_image' => $validated['image'] ?? null,
        ]);

        // Send email notification
        $emailData = [
            'name' => $treasuryOfficer->tre_off_name,
            'email' => $treasuryOfficer->tre_off_email,
            'password' => $plainPassword, // Send the plain password
        ];

        Mail::send('email.treasury_officer_created', $emailData, function ($message) use ($emailData) {
            $message->to($emailData['email'])
                ->subject('Welcome to Treasury Management Platform');
        });

        // Log the creation
        AuditLogger::log('Treasury Officer Created', TreasuryOfficer::class, $treasuryOfficer->tre_off_id, null, $treasuryOfficer->toArray());

        return redirect()->route('treasury-officers.index')->with('success', 'Treasury Officer created successfully and notification email sent.');
    } catch (\Exception $e) {
        return back()->withInput()->with('error', 'Error creating treasury officer: ' . $e->getMessage());
    }
}


    public function edit($id)
    {
        $treasuryOfficer = TreasuryOfficer::findOrFail($id);
        $districts = District::all(); // Fetch all districts
        return view('masters.district.treasury_Officers.edit', compact('treasuryOfficer', 'districts'));
    }

    public function update(Request $request, $id)
    {
        $treasuryOfficer = TreasuryOfficer::findOrFail($id);

        $validated = $request->validate([
            'district' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'email' => 'required|email|unique:treasury_officer,tre_off_email,' . $id . ',tre_off_id',
            'employeeid' => 'required|string|unique:treasury_officer,tre_off_employeeid,' . $id . ',tre_off_id',
            'password' => 'nullable|string|min:6',
            'cropped_image' => 'nullable|string'
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
                $imageName = $validated['name'] . time() . '.png';
                $imagePath = 'images/treasury/' . $imageName;

                // Save the image in public storage
                Storage::disk('public')->put($imagePath, $imageData);

                // Compress if image exceeds 200 KB
                $fullImagePath = storage_path('app/public/' . $imagePath);
                if (filesize($fullImagePath) > 200 * 1024) {
                    $this->imageService->saveAndCompressImage($imageData, $fullImagePath, 200);
                }
                //  Delete the old image if it exists
                if ($treasuryOfficer->tre_off_image && Storage::disk('public')->exists($treasuryOfficer->tre_off_image)) {
                    Storage::disk('public')->delete($treasuryOfficer->tre_off_image);
                }

                // Set new image path to be saved in the database
                $newImagePath = $imagePath;
            }

            // Only update password if provided
            if ($request->filled('password')) {
                $validated['password'] = Hash::make($validated['password']);
            }
            // Prepare data for update, including the new image path if it exists
            $updateData = [
                'tre_off_district_id' => $validated['district'],
                'tre_off_name' => $validated['name'],
                'tre_off_designation' => $validated['designation'],
                'tre_off_phone' => $validated['phone'],
                'tre_off_email' => $validated['email'],
                'tre_off_employeeid' => $validated['employeeid'],
                'tre_off_password' => $validated['password'] ?? $treasuryOfficer->tre_off_password
            ];

            // Add new image path to update data if present
            if ($newImagePath) {
                $updateData['tre_off_image'] = $newImagePath;
            }
            // Store old values for logging
            $oldValues = $treasuryOfficer->getOriginal();
            $treasuryOfficer->update($updateData);
            // Get changed values for logging
            $changedValues = $treasuryOfficer->getChanges();
            $oldValues = array_intersect_key($oldValues, $changedValues);
            // Log the update
            AuditLogger::log('Treasury Officer Updated', TreasuryOfficer::class, $treasuryOfficer->tre_off_id, $oldValues, $changedValues);

            return redirect()->route('treasury-officers.index')->with('success', 'Treasury Officer updated successfully');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error updating treasury officer: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $treasuryOfficer = TreasuryOfficer::with(relations: 'district')->findOrFail($id);

        // Log view action
        AuditLogger::log('Treasury Officer Viewed', TreasuryOfficer::class, $treasuryOfficer->tre_off_id);

        return view('masters.district.treasury_Officers.show', compact('treasuryOfficer'));
    }

    public function destroy($id)
    {
        $treasuryOfficer = TreasuryOfficer::findOrFail($id);

        // Log deletion
        AuditLogger::log('Treasury Officer Deleted', TreasuryOfficer::class, $treasuryOfficer->tre_off_id);

        $treasuryOfficer->delete();

        return redirect()->route('treasury_officer.index')->with('success', 'Treasury Officer deleted successfully');
    }

    public function logout(Request $request)
    {
        $tre_off_id = session('tre_off_id');

        // Log logout
        if ($tre_off_id) {
            AuditLogger::log('Treasury Officer Logout', TreasuryOfficer::class, $tre_off_id);
        }

        $request->session()->forget('tre_off_id');

        return redirect()->route('treasury_officer.login');
    }

    public function toggleStatus($id)
    {
        try {
            $treasuryOfficer = TreasuryOfficer::findOrFail($id);

            // Get current status before update
            $oldStatus = $treasuryOfficer->tre_off_status;

            // Toggle the status
            $treasuryOfficer->tre_off_status = !$treasuryOfficer->tre_off_status;
            $treasuryOfficer->save();

            // Log the status change
            AuditLogger::log(
                'Treasury Officer Status Changed',
                TreasuryOfficer::class,
                $treasuryOfficer->tre_off_id,
                ['status' => $oldStatus],
                ['status' => $treasuryOfficer->tre_off_status]
            );

            return response()->json([
                'success' => true,
                'status' => $treasuryOfficer->tre_off_status,
                'message' => 'Treasury Officer status updated successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update treasury officer status',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
}
