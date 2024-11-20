<?php

namespace App\Http\Controllers;

use App\Models\CIAssistant;
use App\Models\Center;
use App\Models\District;
use App\Models\Venues;
use Illuminate\Http\Request;
use App\Services\AuditLogger;
use Illuminate\Support\Facades\Storage;
use App\Services\ImageCompressService;
use App\Http\Controllers\Controller;

class CIAssistantsController extends Controller
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
        // Fetch all CI Assistants with related district, center, and venue using eager loading
        $ciAssistants = CIAssistant::with('district', 'center', 'venue')->get();

        // Pass the CI Assistants to the view
        return view('masters.venues.ci_assistants.index', compact('ciAssistants'));
    }


    public function create()
    {
        // Fetch necessary data for CI Assistants form (venues, centers, districts)
        $venues = Venues::all(); // Retrieves all venues
        $centers = Center::all(); // Retrieves all centers
        $districts = District::all(); // Retrieves all districts

        return view('masters.venues.ci_assistants.create', compact('districts', 'centers', 'venues'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'district_id'   => 'required|string|max:50',
            'center_id'     => 'required|string|max:50',
            'venue_id'      => 'required|string|max:50',
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|max:255',
            'phone'         => 'required|string|max:15',
            'designation'   => 'required|string|max:255',
            'cropped_image' => 'nullable|string'  // For image (if needed)
        ]);

        try {
            $newImagePath = null;
            if (!empty($validated['cropped_image'])) {
                $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $validated['cropped_image']);
                $imageData = base64_decode($imageData);
                if ($imageData === false) throw new \Exception('Base64 decode failed.');

                $imageName = 'cia_' . time() . '.png';
                $imagePath = 'images/cias/' . $imageName;
                Storage::disk('public')->put($imagePath, $imageData);

                $fullImagePath = storage_path('app/public/' . $imagePath);
                if (filesize($fullImagePath) > 200 * 1024) {
                    $this->imageService->saveAndCompressImage($imageData, $fullImagePath, 200);
                }
                $newImagePath = $imagePath;
            }

            // Create a new CheifInvigilatorAssistant record
            $cia = CIAssistant::create([
                'cia_district_id' => $validated['district_id'],
                'cia_center_id'   => $validated['center_id'],
                'cia_venue_id'    => $validated['venue_id'],
                'cia_name'        => $validated['name'],
                'cia_email'       => $validated['email'],
                'cia_phone'       => $validated['phone'],
                'cia_designation' => $validated['designation'],
                'cia_image'       => $newImagePath
            ]);

            AuditLogger::log('Cheif Invigilator Assistant Created', CIAssistant::class, $cia->cia_id, null, $cia->toArray());

            return redirect()->route('ci-assistant')->with('success', 'Assistant created successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error creating assistant: ' . $e->getMessage());
        }
    }



    public function edit($id)
    {
        // Fetch the specific CI Assistant record by its ID
        $ciAssistant = CIAssistant::findOrFail($id);

        // Fetch data for dropdowns
        $venues = Venues::all();
        $centers = Center::all();
        $districts = District::all();

        // Return the view with the CI Assistant data and dropdown options
        return view('masters.venues.ci_assistants.edit', compact('ciAssistant', 'districts', 'centers', 'venues'));
    }

    public function update(Request $request, $id)
    {
        // Find the CI Assistant record by ID
        $ciAssistant = CIAssistant::findOrFail($id);

        // Validation rules for CI Assistant data
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:cheif_invigilator_assistant,cia_email,' . $id . ',cia_id',
            'phone'        => 'required|string|max:15',
            'designation'  => 'nullable|string|max:255',
            'district_id'  => 'required|exists:district,district_id',
            'center_id'    => 'required|exists:centers,center_id',
            'venue_id'     => 'required|exists:venue,venue_id',
            'cropped_image' => 'nullable|string', // Base64 encoded image string
        ]);

        try {
            $newImagePath = null;

            // Process the image if provided
            if (!empty($validated['cropped_image'])) {
                // Decode the base64 image string
                $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $validated['cropped_image']);
                $imageData = base64_decode($imageData);

                if ($imageData === false) {
                    throw new \Exception('Base64 decode failed.');
                }

                // Create a unique filename for the image
                $imageName = 'ci_assistant_' . time() . '.jpg';
                $imagePath = 'images/ci_assistants/' . $imageName;

                // Save the image in public storage
                Storage::disk('public')->put($imagePath, $imageData);

                // Compress if image exceeds 200 KB
                $fullImagePath = storage_path('app/public/' . $imagePath);
                if (filesize($fullImagePath) > 200 * 1024) {
                    // Assuming you have the ImageService class set up
                    $this->imageService->saveAndCompressImage($imageData, $fullImagePath, 200);
                }

                // Delete the old image if it exists
                if ($ciAssistant->cia_image && Storage::disk('public')->exists($ciAssistant->cia_image)) {
                    Storage::disk('public')->delete($ciAssistant->cia_image);
                }

                // Set new image path to be saved in the database
                $newImagePath = $imagePath;
            }

            // Prepare data for updating the CI Assistant record
            $updateData = [
                'cia_name'        => $validated['name'],
                'cia_email'       => $validated['email'],
                'cia_phone'       => $validated['phone'],
                'cia_designation' => $validated['designation'],
                'cia_district_id' => $validated['district_id'],
                'cia_center_id'   => $validated['center_id'],
                'cia_venue_id'    => $validated['venue_id'],
            ];

            // Add the new image path if it's provided
            if ($newImagePath) {
                $updateData['cia_image'] = $newImagePath;
            }

            // Update the CI Assistant record with the validated data
            $ciAssistant->update($updateData);
            AuditLogger::log('CI Assistant Created', CIAssistant::class, $ciAssistant->cia_id, null, $ciAssistant->toArray());

            // Redirect to the CI Assistant show page
            return redirect()->route('ci-assistant', $ciAssistant->cia_id)
                ->with('success', 'CI Assistant updated successfully');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error updating CI Assistant: ' . $e->getMessage());
        }
    }

    public function toggleStatus($id)
    {
        try {
            // Find the CI Assistant by ID
            $ciAssistant = CIAssistant::findOrFail($id);

            // Get the current status before updating
            $oldStatus = $ciAssistant->cia_status;

            // Toggle the status
            $ciAssistant->cia_status = !$ciAssistant->cia_status;
            $ciAssistant->save();

            return response()->json([
                'success' => true,
                'status' => $ciAssistant->cia_status,
                'message' => 'CI Assistant status updated successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update CI Assistant status',
                'details' => $e->getMessage(), // Optional for debugging
            ], 500);
        }
    }


    public function show($id)
    {
        // Retrieve the CI Assistant record by ID, or fail if not found
        $ciAssistant = CIAssistant::findOrFail($id);

        // Fetch related District, Venue, and Center records based on the CI Assistant's foreign keys
        $district = District::findOrFail($ciAssistant->cia_district_id);
        $venue = Venues::findOrFail($ciAssistant->cia_venue_id);
        $center = Center::findOrFail($ciAssistant->cia_center_id);

        // Return the view with the CI Assistant and related data
        return view('masters.venues.ci_assistants.show', compact('ciAssistant', 'district', 'venue', 'center'));
    }
}
