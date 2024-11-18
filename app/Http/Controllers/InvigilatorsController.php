<?php

namespace App\Http\Controllers;

use App\Models\Invigilator;
use App\Models\Center;
use App\Models\District;
use App\Models\Venues;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Services\AuditLogger;
use Illuminate\Support\Facades\Storage;
use App\Services\ImageCompressService;
use App\Http\Controllers\Controller;

class InvigilatorsController extends Controller
{
    protected $imageService;
    public function __construct(ImageCompressService $imageService)
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth.multi');
        $this->imageService = $imageService;
    }

    public function index()
    {
        $invigilators = Invigilator::all();
        return view('masters.venues.invigilator.index', compact('invigilators'));
    }

    public function create()
    {
        // Fetch any necessary data to display in the form (e.g., venues, centers)
        $venues = Venues::all(); // Or use a filter if necessary
        $centers = Center::all(); // Same as above
        $districts = District::all(); // Same as above

        // Return the view with the data
        return view('masters.venues.invigilator.create', compact('venues', 'centers', 'districts'));
    }

    public function store(Request $request)
    {
        // Validate incoming request data
        $messages = [
            'district.required' => 'Please select a district',
            'district.integer' => 'Please select a valid district',
            'center.required' => 'Please select a center',
            'center.integer' => 'Please select a valid center',
            'venue.required' => 'Please select a venue',
            'venue.integer' => 'Please select a valid venue',
        ];
        $validated = $request->validate([
            'district' => 'required|integer', // Assuming you have a relation to district
            'center' => 'required|integer',
            'venue' => 'required|integer',
            'name' => 'required|string|max:255',
            'mail' => 'required|email|max:255|unique:invigilator,invigilator_email',
            'phone' => 'required|string|max:15',
            'designation' => 'required|string|max:255',
            'cropped_image' => 'nullable|string', // Base64 image input
        ], $messages);

        try {
            // Process the cropped image if it's provided
            if (!empty($validated['cropped_image'])) {
                // Remove the data URL prefix if present
                $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $validated['cropped_image']);
                $imageData = base64_decode($imageData);

                if ($imageData === false) {
                    throw new \Exception('Base64 decode failed.');
                }

                // Create a unique image name
                $imageName = $validated['name'] . time() . '.png';
                $imagePath = 'images/invigilators/' . $imageName;

                // Store the image in the public storage
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

                $validated['image'] = $imagePath; // Assign the image path
            }

            // Create the invigilator with validated data
            $invigilator = Invigilator::create([
                'invigilator_district_id' => $validated['district'],
                'invigilator_center_id' => $validated['center'],
                'invigilator_venue_id' => $validated['venue'],
                'invigilator_name' => $validated['name'],
                'invigilator_email' => $validated['mail'],
                'invigilator_phone' => $validated['phone'],
                'invigilator_designation' => $validated['designation'],
                'invigilator_image' => $validated['image'] ?? null, // Store image path if exists
            ]);

            // Log invigilator creation with new values
            AuditLogger::log('Invigilator Created', Invigilator::class, $invigilator->invigilator_id, null, $invigilator->toArray());

            // Redirect with success message
            return redirect()->route('invigilators.index')
                ->with('success', 'Invigilator created successfully');
        } catch (\Exception $e) {
            // Handle exceptions and return error message
            return back()->withInput()
                ->with('error', 'Error creating invigilator: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        // Fetch the specific invigilator by ID
        $invigilator = Invigilator::findOrFail($id);
        // Fetch any necessary data to display in the form (e.g., venues, centers)
        $venues = Venues::all(); // Or use a filter if necessary
        $centers = Center::all(); // Same as above
        $districts = District::all(); // Same as above
        // Pass the invigilator data to the edit view
        return view('masters.venues.invigilator.edit', compact('venues', 'centers', 'districts', 'invigilator'));
    }

    public function update(Request $request, $id)
    {
        $invigilator = Invigilator::findOrFail($id);
        // Validate incoming request data
        $messages = [
            'district.required' => 'Please select a district',
            'district.integer' => 'Please select a valid district',
            'center.required' => 'Please select a center',
            'center.integer' => 'Please select a valid center',
            'venue.required' => 'Please select a venue',
            'venue.integer' => 'Please select a valid venue',
        ];
        $validated = $request->validate([
            'district' => 'required|integer',
            'center' => 'required|integer',
            'venue' => 'required|integer',
            'name' => 'required|string|max:255',
            'mail' => 'required|email|max:255|unique:invigilator,invigilator_email,' . $id . ',invigilator_id',
            'phone' => 'required|string|max:15',
            'designation' => 'required|string|max:255',
            'cropped_image' => 'nullable|string' // Base64 image input
        ], $messages);

        try {
            $newImagePath = null;

            // Process the cropped image if it's provided
            if (!empty($validated['cropped_image'])) {
                $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $validated['cropped_image']);
                $imageData = base64_decode($imageData);

                if ($imageData === false) {
                    throw new \Exception('Base64 decode failed.');
                }

                // Create a unique filename
                $imageName = $validated['name'] . time() . '.png';
                $imagePath = 'images/invigilators/' . $imageName;

                // Save the image in public storage
                Storage::disk('public')->put($imagePath, $imageData);

                // Compress if image exceeds 200 KB
                $fullImagePath = storage_path('app/public/' . $imagePath);
                if (filesize($fullImagePath) > 200 * 1024) {
                    $this->imageService->saveAndCompressImage($imageData, $fullImagePath, 200);
                }

                // Delete old image if exists
                if ($invigilator->invigilator_image && Storage::disk('public')->exists($invigilator->invigilator_image)) {
                    Storage::disk('public')->delete($invigilator->invigilator_image);
                }

                // Set new image path to be saved in the database
                $newImagePath = $imagePath;
            }


            // Prepare data for update, including new image path if it exists
            $updateData = [
                'invigilator_district_id' => $validated['district'],
                'invigilator_center_id' => $validated['center'],
                'invigilator_venue_id' => $validated['venue'],
                'invigilator_name' => $validated['name'],
                'invigilator_email' => $validated['mail'],
                'invigilator_phone' => $validated['phone'],
                'invigilator_designation' => $validated['designation'],


            ];

            // Add new image path to update data if present
            if ($newImagePath) {
                $updateData['invigilator_image'] = $newImagePath;
            }

            // Get old values and update the invigilator
            $oldValues = $invigilator->getOriginal();
            $invigilator->update($updateData);

            // Get changed values for logging
            $changedValues = $invigilator->getChanges();
            $oldValues = array_intersect_key($oldValues, $changedValues);

            // Log invigilator update with old and new values
            AuditLogger::log('Invigilator Updated', Invigilator::class, $invigilator->invigilator_id, $oldValues, $changedValues);

            return redirect()->route('invigilators.index')
                ->with('success', 'Invigilator updated successfully');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error updating invigilator: ' . $e->getMessage());
        }
    }

    public function toggleStatus($id)
    {
        try {
            $invigilator = Invigilator::findOrFail($id);

            // Get current status before update
            $oldStatus = $invigilator->invigilator_status;

            // Toggle the status
            $invigilator->invigilator_status = !$invigilator->invigilator_status;
            $invigilator->save();

            // Log the status change
            AuditLogger::log(
                'Invigilator Status Changed',
                Invigilator::class,
                $invigilator->invigilator_id,
                ['status' => $oldStatus],
                ['status' => $invigilator->invigilator_status]
            );

            return response()->json([
                'success' => true,
                'status' => $invigilator->invigilator_status,
                'message' => 'Invigilator status updated successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update invigilator status',
                'details' => $e->getMessage(),  // Optional
            ], 500);
        }
    }
   


    public function show($id)
    {
        $invigilator = Invigilator::findOrFail($id);
        $district = District::findOrFail($invigilator->invigilator_district_id);
        $venue = Venues::findOrFail($invigilator->invigilator_venue_id);
        $center = Center::findOrFail($invigilator->invigilator_center_id);

        return view('masters.venues.invigilator.show', compact('invigilator', 'district', 'venue', 'center'));
    }
}
