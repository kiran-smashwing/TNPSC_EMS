<?php

namespace App\Http\Controllers;

use App\Models\Scribe;
use App\Models\Center;
use App\Models\District;
use App\Models\Venues;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Services\AuditLogger;
use Illuminate\Support\Facades\Storage;
use App\Services\ImageCompressService;
use App\Http\Controllers\Controller;

class ScribeController extends Controller
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
        // Fetch all scribes with related district, center, and venue (optional eager loading)
        $scribes = Scribe::with('venue')->get();

        // Pass the scribes to the view
        return view('masters.venues.scribe.index', compact('scribes'));
    }
    public function create()
    {
        // Fetch any necessary data to display in the form (e.g., venues, centers)
        $venues = Venues::all(); // Or use a filter if necessary
        $centers = Center::all(); // Same as above
        $districts = District::all(); // Same as above
        return view('masters.venues.scribe.create', compact('districts', 'centers', 'venues'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'district_id'   => 'required|string|max:50',
            'center_id'     => 'required|string|max:50',
            'venue_id'      => 'required|string|max:50',
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|max:255',
            'phone'          => 'required|string|max:15',
            'designation'    => 'required|string|max:255',
            'cropped_image'  => 'nullable|string'  // For image (if needed)
        ]);

        try {
            // If cropped_image exists, process it
            if (!empty($validated['cropped_image'])) {
                // Remove the data URL prefix if present
                $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $validated['cropped_image']);
                $imageData = base64_decode($imageData);

                if ($imageData === false) {
                    throw new \Exception('Base64 decode failed.');
                }

                // Create a unique image name
                $imageName = 'scribe_' . time() . '.png';
                $imagePath = 'images/scribes/' . $imageName;

                // Store the image
                $stored = Storage::disk('public')->put($imagePath, $imageData);

                if (!$stored) {
                    throw new \Exception('Failed to save image to storage.');
                }

                // Optionally, compress the image if necessary (if it exceeds a size)
                $fullImagePath = storage_path('app/public/' . $imagePath);
                if (filesize($fullImagePath) > 200 * 1024) {  // 200 KB max size
                    // Compress the image (you can add your custom compression logic)
                    $this->imageService->saveAndCompressImage($imageData, $fullImagePath, 200);
                }

                $validated['image'] = $imagePath;
            }

            // Create a new Scribe record
            $scribe = Scribe::create([
                'scribe_district_id'   => $validated['district_id'],
                'scribe_center_id'     => $validated['center_id'],
                'scribe_venue_id'      => $validated['venue_id'],
                'scribe_name'          => $validated['name'],
                'scribe_email'         => $validated['email'],
                'scribe_phone'         => $validated['phone'],
                'scribe_designation'   => $validated['designation'],
                'scribe_image'         => $validated['image'] ?? null  // Save image if available
            ]);

            // Log scribe creation
            AuditLogger::log('Scribe Created', Scribe::class, $scribe->scribe_id, null, $scribe->toArray());

            // Redirect to the scribe list or wherever necessary
            return redirect()->route('scribe')->with('success', 'Scribe created successfully.');
        } catch (\Exception $e) {
            // If there's an error, return back with an error message
            return back()->withInput()->with('error', 'Error creating scribe: ' . $e->getMessage());
        }
    }


    public function edit($id)
    {
        // Fetch the specific scribe record by its ID
        $scribe = Scribe::findOrFail($id);

        // Fetch the necessary data for dropdowns
        $venues = Venues::all();
        $centers = Center::all();
        $districts = District::all();

        // Return the view with the scribe data and the options for districts, centers, and venues
        return view('masters.venues.scribe.edit', compact('scribe', 'districts', 'centers', 'venues'));
    }

    public function update(Request $request, $id)
    {
        // Find the scribe record by ID
        $scribe = Scribe::findOrFail($id);

        // Validation rules for the scribe data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'mail' => 'required|email|unique:scribe,scribe_email,' . $id . ',scribe_id',
            'phone' => 'required|string',
            'designation' => 'nullable|string',
            'district_id' => 'required|exists:district,district_id',
            'center_id' => 'required|exists:centers,center_id',
            'venue_id' => 'required|exists:venue,venue_id',
            'cropped_image' => 'nullable|string', // Base64 encoded image string
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

                // Create a unique filename for the image
                $imageName = 'scribe_' . time() . '.jpg';
                $imagePath = 'images/scribes/' . $imageName;

                // Save the image in public storage
                Storage::disk('public')->put($imagePath, $imageData);

                // Compress if image exceeds 200 KB (if imageService exists)
                $fullImagePath = storage_path('app/public/' . $imagePath);
                if (filesize($fullImagePath) > 200 * 1024) {
                    // Assuming you have the ImageService class set up
                    $this->imageService->saveAndCompressImage($imageData, $fullImagePath, 200);
                }

                // Delete the old image if it exists
                if ($scribe->scribe_image && Storage::disk('public')->exists($scribe->scribe_image)) {
                    Storage::disk('public')->delete($scribe->scribe_image);
                }

                // Set new image path to be saved in the database
                $newImagePath = $imagePath;
            }

            // Prepare data for updating the scribe record
            $updateData = [
                'scribe_name' => $validated['name'],
                'scribe_email' => $validated['mail'],
                'scribe_phone' => $validated['phone'],
                'scribe_designation' => $validated['designation'],
                'scribe_district_id' => $validated['district_id'],
                'scribe_center_id' => $validated['center_id'],
                'scribe_venue_id' => $validated['venue_id'],
            ];

            // Add the new image path if it's provided
            if ($newImagePath) {
                $updateData['scribe_image'] = $newImagePath;
            }

            // Update the scribe record with the validated data
            $scribe->update($updateData);
            AuditLogger::log('Scribe Created', Scribe::class, $scribe->scribe_id, null, $scribe->toArray());

            // Redirect to the scribe show page (adjust route as necessary)
            return redirect()->route('scribe', $scribe->scribe_id)
                ->with('success', 'Scribe updated successfully');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error updating scribe: ' . $e->getMessage());
        }
    }

    public function toggleStatus($id)
    {
        try {
            // Find the scribe by ID
            $scribe = Scribe::findOrFail($id);

            // Get the current status before updating
            $oldStatus = $scribe->scribe_status;

            // Toggle the status
            $scribe->scribe_status = !$scribe->scribe_status;
            $scribe->save();

            return response()->json([
                'success' => true,
                'status' => $scribe->scribe_status,
                'message' => 'Scribe status updated successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update scribe status',
                'details' => $e->getMessage(), // Optional for debugging
            ], 500);
        }
    }



    public function show($id)
    {
        // Retrieve the Scribe record by ID, or fail if not found
        $scribe = Scribe::findOrFail($id);

        // Fetch related District, Venue, and Center records based on Scribe's foreign keys
        $district = District::findOrFail($scribe->scribe_district_id);
        $venue = Venues::findOrFail($scribe->scribe_venue_id);
        $center = Center::findOrFail($scribe->scribe_center_id);

        // Return the view with the Scribe and related data
        return view('masters.venues.scribe.show', compact('scribe', 'district', 'venue', 'center'));
    }
}
