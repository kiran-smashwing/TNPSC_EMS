<?php

namespace App\Http\Controllers;

use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Services\AuditLogger;
use Illuminate\Support\Facades\Storage;
use App\Services\ImageCompressService;

class DistrictController extends Controller
{
    protected $imageService;
    public function __construct(ImageCompressService $imageService)
    {

        $this->middleware('auth:district');
        $this->imageService = $imageService;

    }

   
    public function index()
    {
        $districts = District::all();
        return view('masters.district.collectorate.index', compact('districts'));
    }

    public function create()
    {
        return view('masters.district.collectorate.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'district_name' => 'required|string|max:255',
            'district_code' => 'required|numeric|unique:district',
            'mail' => 'required|email',
            'phone' => 'required|string',
            'alternate_phone' => 'nullable|string',
            'password' => 'required|string|min:6',
            'website' => 'required|url',
            'address' => 'required|string',
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric',
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
                $imageName = 'district_' . time() . '.png';
                $imagePath = 'images/districts/' . $imageName;

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

            // Hash password
            $validated['district_password'] = Hash::make($validated['password']);
            // Map the fields to match your database columns
            $district = District::create([
                'district_name' => $validated['district_name'],
                'district_code' => $validated['district_code'],
                'district_email' => $validated['mail'],
                'district_phone' => $validated['phone'],
                'district_alternate_phone' => $validated['alternate_phone'],
                'district_password' => $validated['district_password'],
                'district_website' => $validated['website'],
                'district_address' => $validated['address'],
                'district_longitude' => $validated['longitude'],
                'district_latitude' => $validated['latitude'],
                'district_image' => $validated['image'] ?? null
            ]);

            // Log district creation with new values
            AuditLogger::log('District Created', District::class, $district->district_id, null, $district->toArray());


            return redirect()->route('district.index')
                ->with('success', 'District created successfully');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error creating district: ' . $e->getMessage());
        }
    }

    
    public function edit($id)
    {
        $district = District::findOrFail($id);
        return view('masters.district.collectorate.edit', compact('district'));
    }

    public function update(Request $request, $id)
    {
        $district = District::findOrFail($id);

        $validated = $request->validate([
            'district_name' => 'required|string|max:255',
            'district_code' => 'required|numeric|unique:district,district_code,' . $id . ',district_id',
            'mail' => 'required|email',
            'phone' => 'required|string',
            'alternate_phone' => 'nullable|string',
            'password' => 'nullable|string|min:6',
            'website' => 'required|url',
            'address' => 'required|string',
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric',
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
                $imageName = 'district_' . time() . '.jpg';
                $imagePath = 'images/districts/' . $imageName;

                // Save the image in public storage
                Storage::disk('public')->put($imagePath, $imageData);

                // Compress if image exceeds 200 KB
                $fullImagePath = storage_path('app/public/' . $imagePath);
                if (filesize($fullImagePath) > 200 * 1024) {
                    $this->imageService->saveAndCompressImage($imageData, $fullImagePath, 200);
                }
                //  Delete the old image if it exists
                if ($district->district_image && Storage::disk('public')->exists($district->district_image)) {
                    Storage::disk('public')->delete($district->district_image);
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
                'district_name' => $validated['district_name'],
                'district_code' => $validated['district_code'],
                'district_email' => $validated['mail'],
                'district_phone' => $validated['phone'],
                'district_alternate_phone' => $validated['alternate_phone'],
                'district_website' => $validated['website'],
                'district_address' => $validated['address'],
                'district_longitude' => $validated['longitude'],
                'district_latitude' => $validated['latitude'],
                'district_password' => $validated['district_password'] ?? $district->district_password
            ];

            // Add new image path to update data if present
            if ($newImagePath) {
                $updateData['district_image'] = $newImagePath;
            }

            // Get old values and update the district
            $oldValues = $district->getOriginal();
            $district->update($updateData);

            // Get changed values for logging
            $changedValues = $district->getChanges();
            $oldValues = array_intersect_key($oldValues, $changedValues);

            // Log district update with old and new values
            AuditLogger::log('District Updated', District::class, $district->district_id, $oldValues, $changedValues);

            return redirect()->route('district.index')
                ->with('success', 'District updated successfully');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error updating district: ' . $e->getMessage());
        }
    }


    public function show($id)
    {
        $district = District::findOrFail($id);

        // Log view action
        AuditLogger::log('District Viewed', District::class, $district->district_id);

        return view('masters.district.collectorate.show', compact('district'));
    }

    public function destroy($id)
    {
        $district = District::findOrFail($id);

        // Log district deletion
        AuditLogger::log('District Deleted', District::class, $district->district_id);

        $district->delete();

        return redirect()->route('district.index')
            ->with('success', 'District deleted successfully');
    }

    public function logout(Request $request)
    {
        $district_id = session('district_id');

        // Log logout
        if ($district_id) {
            AuditLogger::log('District Logout', District::class, $district_id);
        }

        $request->session()->forget('district_id');

        return redirect()->route('district.login');
    }
    // In app/Http/Controllers/DistrictController.php:
    public function toggleStatus($id)
    {
        try {
            $district = District::findOrFail($id);

            // Get current status before update
            $oldStatus = $district->district_status;

            // Toggle the status
            $district->district_status = !$district->district_status;
            $district->save();

            // Log the status change
            AuditLogger::log(
                'District Status Changed',
                District::class,
                $district->district_id,
                ['status' => $oldStatus],
                ['status' => $district->district_status]
            );

            return response()->json([
                'success' => true,
                'status' => $district->district_status,
                'message' => 'District status updated successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update district status',
                'details' => $e->getMessage(),  // Optional
            ], 500);
        }
    }
}