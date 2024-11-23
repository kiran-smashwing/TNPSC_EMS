<?php

namespace App\Http\Controllers;

use App\Models\ChiefInvigilator;
use App\Models\Center;
use App\Models\District;
use App\Models\Venues;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
        $chiefInvigilator = $query->paginate(10);

        // Fetch unique districts with ID and code
        $districts = ChiefInvigilator::select('ci_district_id')
            ->distinct()
            ->get();

        // Fetch unique centers with ID and code
        $centers = ChiefInvigilator::select('ci_center_id')
            ->distinct()
            ->get();

        // Fetch unique venues with ID and code
        $venues = ChiefInvigilator::select('ci_venue_id')
            ->distinct()
            ->get();

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
            'email' => 'required|email|unique:cheif_invigilator,ci_email',
            'employee_id' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'alternative_phone' => 'nullable|string|max:15',
            'designation' => 'required|string|max:100',
            'cropped_image' => 'nullable|string',
            'password' => 'required|string|min:6',
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
                $imageName = $validated['email'] . time() . '.png';
                $imagePath = 'images/chiefinvigilator/' . $imageName;

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
            $validated['ci_password'] = Hash::make($validated['password']);
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
                'ci_password' => $validated['ci_password'], // Hashing the password before storing
                'ci_image' => $validated['image'] ?? null, // Handle image path, set to null if not provided
            ]);


            AuditLogger::log('Chief Invigilator Created', ChiefInvigilator::class, $chiefInvigilator->ci_id, null, $chiefInvigilator->toArray());

            return redirect()->route('chief-invigilators.index')
                ->with('success', 'Chief Invigilator created successfully');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error creating Chief Invigilator: ' . $e->getMessage());
        }
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

            return redirect()->route('chief-invigilators.index')
                ->with('success', 'Chief Invigilator updated successfully');
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
