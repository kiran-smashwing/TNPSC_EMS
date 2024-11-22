<?php

namespace App\Http\Controllers;

use App\Models\Center;
use App\Models\District;
use Illuminate\Http\Request;
use App\Services\ImageCompressService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Services\AuditLogger;
use Illuminate\Support\Facades\DB;

class CenterController extends Controller
{
    protected $imageService;
    public function __construct(ImageCompressService $imageService)
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth.multi');
        $this->imageService = $imageService;
    }
    public function index(Request $request)
    {
        // Get the filtered centers and options in a single method
        $data = $this->getFilteredData($request);
        
        return view('masters.district.centers.index', $data);
    }

    private function getFilteredData(Request $request)
    {
        // Build the centers query with eager loading
        $centersQuery = Center::query()
            ->select([
                'centers.center_id',
                'centers.center_district_id',
                'centers.center_name',
                'centers.center_code',
                'centers.center_email',
                'centers.center_phone',
                'centers.center_image',
                'centers.center_email_status',
                'centers.center_status'
            ])
            ->join('district', 'centers.center_district_id', '=', 'district.district_code')
            ->with(['district' => function ($query) {
                $query->select('district_id', 'district_code', 'district_name');
            }]);

        // Apply filters
        if ($request->filled('district')) {
            $centersQuery->where('centers.center_district_id', $request->district);
        }

        if ($request->filled('centerCode')) {
            $centersQuery->where('centers.center_code', $request->centerCode);
        }

        // Get centers
        $centers = $centersQuery->orderBy('centers.center_name')->get();

        // Get districts and center codes efficiently
        $districts = District::select('district_code', 'district_name')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('centers')
                    ->whereColumn('centers.center_district_id', 'district.district_code');
            })
            ->orderBy('district_name')
            ->get();

        $centerCodes = Center::select('center_code')
            ->whereNotNull('center_code')
            ->distinct()
            ->orderBy('center_code')
            ->pluck('center_code');

        return compact('centers', 'districts', 'centerCodes');
    }




    public function create()
    {
        $districts = District::all();
        return view('masters.district.centers.create', compact('districts'));
    }

    public function store(Request $request)
    {
        // Validate the incoming request
        $messages = [
            'district.required' => 'Please select a district',
            'district.integer' => 'Please select a valid district',
        ];
        $validated = $request->validate([
            'district' => 'required|integer',
            'center_name' => 'required|string|max:255',
            'center_code' => 'required|numeric|unique:centers',
            'email' => 'required|email|unique:centers,center_email',
            'phone' => 'required|string|max:15',
            'alternate_phone' => 'nullable|string|max:15',
            'password' => 'required|string|min:6',
            'address' => 'required|string',
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric',
            'cropped_image' => 'nullable|string',
        ], $messages);
        try {
            if (!empty($validated['cropped_image'])) {
                // Remove the data URL prefix if present
                $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $validated['cropped_image']);
                $imageData = base64_decode($imageData);

                if ($imageData === false) {
                    throw new \Exception('Base64 decode failed.');
                }

                // Create a unique image name
                $imageName = $validated['center_code'] . time() . '.png';
                $imagePath = 'images/center/' . $imageName;

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
            $validated['center_password'] = Hash::make($validated['password']);

            // Create a new center record
            $center = Center::create([
                'center_district_id' => $validated['district'],
                'center_name' => $validated['center_name'],
                'center_code' => $validated['center_code'],
                'center_email' => $validated['email'],
                'center_phone' => $validated['phone'],
                'center_alternate_phone' => $validated['alternate_phone'] ?? null,
                'center_password' => $validated['center_password'],
                'center_address' => $validated['address'],
                'center_longitude' => $validated['longitude'],
                'center_latitude' => $validated['latitude'],
                'center_image' => $validated['image'] ?? null
            ]);
            // Log district creation with new values
            AuditLogger::log('Center Created', Center::class, $center->district_id, null, $center->toArray());


            // Redirect with success message
            return redirect()->route('centers.index')->with('success', 'Center added successfully.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error creating center: ' . $e->getMessage());
        }
    }



    public function edit($center_id)
    {
        $center = Center::findOrFail($center_id); // Retrieves the center by its ID
        $districts = District::all(); // Fetch all districts
        return view('masters.district.centers.edit', compact('center', 'districts'));
    }

    public function update(Request $request, $id)
    {
        $center = Center::findOrFail($id);
        $messages = [
            'district.required' => 'Please select a district',
            'district.integer' => 'Please select a valid district',
        ];
        $validated = $request->validate([
            'district' => 'required|integer',
            'center_name' => 'required|string|max:255',
            'center_code' => 'required|numeric|unique:centers,center_code,' . $id . ',center_id',
            'email' => 'required|email|unique:centers,center_email,' . $id . ',center_id',
            'phone' => 'required|string|max:15',
            'alternate_phone' => 'nullable|string|max:15',
            'password' => 'nullable|string|min:6',
            'address' => 'required|string',
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric',
            'cropped_image' => 'nullable|string'
        ], $messages);

        try {
            $newImagePath = null;

            if (!empty($validated['cropped_image'])) {
                $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $validated['cropped_image']);
                $imageData = base64_decode($imageData);

                if ($imageData === false) {
                    throw new \Exception('Base64 decode failed.');
                }

                $imageName = $validated['center_code'] . time() . '.png';
                $imagePath = 'images/center/' . $imageName;
                // Save the image in public storage
                Storage::disk('public')->put($imagePath, $imageData);
                // Compress if image exceeds 200 KB
                $fullImagePath = storage_path('app/public/' . $imagePath);
                if (filesize($fullImagePath) > 200 * 1024) {
                    $this->imageService->saveAndCompressImage($imageData, $fullImagePath, 200);
                }
                //  Delete the old image if it exists
                if ($center->center_image && Storage::disk('public')->exists($center->center_image)) {
                    Storage::disk('public')->delete($center->center_image);
                }
                // Set new image path to be saved in the database
                $newImagePath = $imagePath;
            }
            // Only update password if provided
            if ($request->filled('password')) {
                $validated['center_password'] = Hash::make($validated['password']);
            }
            $updateData = [
                'center_district_id' => $validated['district'],
                'center_name' => $validated['center_name'],
                'center_code' => $validated['center_code'],
                'center_email' => $validated['email'],
                'center_phone' => $validated['phone'],
                'center_alternate_phone' => $validated['alternate_phone'] ?? null,
                'center_password' => $validated['center_password'] ?? $center->center_password,
                'center_address' => $validated['address'],
                'center_longitude' => $validated['longitude'],
                'center_latitude' => $validated['latitude'],
            ];

            // Add new image path to update data if present
            if ($newImagePath) {
                $updateData['center_image'] = $newImagePath;
            }

            // Get old values and update the district
            $oldValues = $center->getOriginal();
            $center->update($updateData);

            // Get changed values for logging
            $changedValues = $center->getChanges();
            $oldValues = array_intersect_key($oldValues, $changedValues);

            // Log district update with old and new values
            AuditLogger::log('Center Updated', Center::class, $center->center_id, $oldValues, $changedValues);
            return redirect()->route('centers.index')
                ->with('success', 'Center updated successfully');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error updating center: ' . $e->getMessage());
        }
    }


    public function show($id)
    {
        $center = Center::with(relations: 'district')->findOrFail($id);

        // Log view action
        AuditLogger::log('Center Viewed', Center::class, $center->center_id);

        return view('masters.district.centers.show', compact('center'));
    }

    public function destroy(Center $center)
    {
        if ($center->image) {
            Storage::disk('public')->delete($center->image);
        }
        $center->delete();
        return redirect()->route('centers.index')->with('success', 'Center deleted successfully.');
    }
    public function toggleStatus($id)
    {
        try {
            $center = Center::findOrFail($id);

            // Get current status before update
            $oldStatus = $center->center_status;

            // Toggle the status
            $center->center_status = !$center->center_status;
            $center->save();

            // Log the status change
            AuditLogger::log(
                'Center Status Changed',
                District::class,
                $center->center_id,
                ['status' => $oldStatus],
                ['status' => $center->center_status]
            );

            return response()->json([
                'success' => true,
                'status' => $center->center_status,
                'message' => 'Center status updated successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update Center status',
                'details' => $e->getMessage(),  // Optional
            ], 500);
        }
    }
}
