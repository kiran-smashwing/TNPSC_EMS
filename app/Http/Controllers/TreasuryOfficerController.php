<?php

namespace App\Http\Controllers;

use App\Models\TreasuryOfficer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Services\AuditLogger;
use Illuminate\Support\Facades\Validator;
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

    public function index()
    {
        $treasuryOfficers = TreasuryOfficer::all();
        return view('masters.district.treasury_Officers.index', compact('treasuryOfficers'));
    }

    public function create()
    {
        return view('masters.district.treasury_Officers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tre_off_district_id' => 'required|string|max:50',
            'tre_off_name' => 'required|string|max:255',
            'tre_off_designation' => 'required|string|max:255',
            'tre_off_phone' => 'required|string',
            'tre_off_email' => 'required|email|unique:treasury_officer,tre_off_email',
            'tre_off_employeeid' => 'required|string|unique:treasury_officer,tre_off_employeeid',
            'tre_off_password' => 'required|string|min:6',
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
                    // Use the ImageService to save and compress the image
                    $this->imageService->saveAndCompressImage($imageData, $fullImagePath, 200);  // 200 KB max size
                }

                $validated['image'] = $imagePath;

            }
            // Hash the password
            $validated['tre_off_password'] = Hash::make($validated['tre_off_password']);

            // Create the treasury officer record
            $treasuryOfficer = TreasuryOfficer::create([
                'tre_off_district_id' => $validated['tre_off_district_id'],
                'tre_off_name' => $validated['tre_off_name'],
                'tre_off_designation' => $validated['tre_off_designation'],
                'tre_off_phone' => $validated['tre_off_phone'],
                'tre_off_email' => $validated['tre_off_email'],
                'tre_off_employeeid' => $validated['tre_off_employeeid'],
                'tre_off_password' => $validated['tre_off_password'],
                'tre_off_image' => $validated['image'] ?? null
            ]);

            // Log the creation
            AuditLogger::log('Treasury Officer Created', TreasuryOfficer::class, $treasuryOfficer->tre_off_id, null, $treasuryOfficer->toArray());

            return redirect()->route('treasury-officers.index')->with('success', 'Treasury Officer created successfully');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error creating treasury officer: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $treasuryOfficer = TreasuryOfficer::findOrFail($id);
        return view('masters.district.treasury_Officers.edit', compact('treasuryOfficer'));
    }

    public function update(Request $request, $id)
    {
        $treasuryOfficer = TreasuryOfficer::findOrFail($id);

        $validated = $request->validate([
            'tre_off_district_id' => 'required|string|max:50',
            'tre_off_name' => 'required|string|max:255',
            'tre_off_designation' => 'required|string|max:255',
            'tre_off_phone' => 'required|string',
            'tre_off_email' => 'required|email|unique:treasury_officer,tre_off_email,' . $id . ',tre_off_id',
            'tre_off_employeeid' => 'required|string|unique:treasury_officer,tre_off_employeeid,' . $id . ',tre_off_id',
            'tre_off_password' => 'nullable|string|min:6',
        ]);

        try {
            // Only update password if provided
            if ($request->filled('tre_off_password')) {
                $validated['tre_off_password'] = Hash::make($validated['tre_off_password']);
            }

            // Store old values for logging
            $oldValues = $treasuryOfficer->getOriginal();
            $treasuryOfficer->update($validated);

            // Log the update
            AuditLogger::log('Treasury Officer Updated', TreasuryOfficer::class, $treasuryOfficer->tre_off_id, $oldValues, $treasuryOfficer->getChanges());

            return redirect()->route('treasury_officer.index')->with('success', 'Treasury Officer updated successfully');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error updating treasury officer: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $treasuryOfficer = TreasuryOfficer::findOrFail($id);

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
