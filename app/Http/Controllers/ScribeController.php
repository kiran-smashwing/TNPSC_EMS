<?php

namespace App\Http\Controllers;

use App\Models\Scribe;
use App\Models\Center;
use App\Models\District;
use App\Models\Venues;
use Illuminate\Http\Request;
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

    public function index(Request $request)
    {
        // Start query for Scribe with related District, Center, and Venue
        $query = Scribe::with(['district', 'center', 'venue']);

        // Apply filters based on the request
        if ($request->filled('district')) {
            $query->where('scribe_district_id', $request->input('district'));
        }

        if ($request->filled('center')) {
            $query->where('scribe_center_id', $request->input('center'));
        }

        if ($request->filled('venue')) {
            $query->where('scribe_venue_id', $request->input('venue'));
        }

        // Fetch filtered scribes
        $scribes = $query->paginate(10);

        // Fetch distinct districts, centers, and venues only present in the Scribe table
        $districts = District::whereIn('district_id', function ($query) {
            $query->selectRaw('CAST(scribe_district_id AS INTEGER)')->from('scribe');
        })->get(['district_id', 'district_name']);

        $centers = Center::whereIn('center_id', function ($query) {
            $query->selectRaw('CAST(scribe_center_id AS INTEGER)')->from('scribe');
        })->get(['center_id', 'center_name']);

        $venues = Venues::whereIn('venue_id', function ($query) {
            $query->selectRaw('CAST(scribe_venue_id AS INTEGER)')->from('scribe');
        })->get(['venue_id', 'venue_name']);

        // Return view with the filtered scribes and filter data
        return view('masters.venues.scribe.index', compact('scribes', 'districts', 'centers', 'venues'));
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
            'mail' => 'required|email|max:255|unique:scribe,scribe_email',
            'phone' => 'required|string|max:15',
            'designation' => 'required|string|max:255',
            'cropped_image' => 'nullable|string', // Base64 image input
        ], $messages);

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
                $imageName = $validated['name'] . time() . '.png';
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
                'scribe_district_id'   => $validated['district'],
                'scribe_center_id'     => $validated['center'],
                'scribe_venue_id'      => $validated['venue'],
                'scribe_name'          => $validated['name'],
                'scribe_email'         => $validated['mail'],
                'scribe_phone'         => $validated['phone'],
                'scribe_designation'   => $validated['designation'],
                'scribe_image'         => $validated['image'] ?? null  // Save image if available
            ]);

            // Log scribe creation
            AuditLogger::log('Scribe Created', Scribe::class, $scribe->scribe_id, null, $scribe->toArray());

            // Redirect to the scribe list or wherever necessary
            return redirect()->route('scribes.index')->with('success', 'Scribe created successfully.');
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
            'mail' => 'required|email|max:255|unique:scribe,scribe_email,' . $id . ',scribe_id',
            'phone' => 'required|string|max:15',
            'designation' => 'required|string|max:255',
            'cropped_image' => 'nullable|string' // Base64 image input
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

                // Create a unique filename for the image
                $imageName = $validated['name'] . time() . '.png';
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
                'scribe_district_id' => $validated['district'],
                'scribe_center_id' => $validated['center'],
                'scribe_venue_id' => $validated['venue'],
            ];

            // Add the new image path if it's provided
            if ($newImagePath) {
                $updateData['scribe_image'] = $newImagePath;
            }

            // Update the scribe record with the validated data
            $scribe->update($updateData);
            AuditLogger::log('Scribe Created', Scribe::class, $scribe->scribe_id, null, $scribe->toArray());

            // Redirect to the scribe show page (adjust route as necessary)
            return redirect()->route('scribes.index', $scribe->scribe_id)
                ->with('success', 'Scribe updated successfully');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error updating scribe: ' . $e->getMessage());
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

    public function toggleStatus($id)
    {
        try {
            $scribe = Scribe::findOrFail($id);

            // Get current status before update
            $oldStatus = $scribe->scribe_status;

            // Toggle the status
            $scribe->scribe_status = !$scribe->scribe_status;
            $scribe->save();

            // Log the status change
            AuditLogger::log(
                'Scribe Status Changed',
                Scribe::class,
                $scribe->scribe_id,
                ['status' => $oldStatus],
                ['status' => $scribe->scribe_status]
            );

            return response()->json([
                'success' => true,
                'status' => $scribe->scribe_status,
                'message' => 'Scribe status updated successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update scribe status',
                'details' => $e->getMessage(),  // Optional
            ], 500);
        }
    }
}
