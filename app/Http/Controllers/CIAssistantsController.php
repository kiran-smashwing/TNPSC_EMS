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

    public function index(Request $request)
    {
        // Start the query for CIAssistants
        $query = CIAssistant::query();

        // Apply filter by district if selected
        if ($request->filled('district')) {
            $query->where('cia_district_id', $request->input('district'));
        }

        // Apply filter by center if selected
        if ($request->filled('center')) {
            $query->where('cia_center_id', $request->input('center'));
        }

        // Apply filter by venue if selected
        if ($request->filled('venue')) {
            $query->where('cia_venue_id', $request->input('venue'));
        }

        // Fetch filtered CIAssistants with pagination
        $ciAssistants = $query->get();

        // Fetch unique district values from the same table
        $districts = District::all(); // Fetch all districts


        // Fetch unique centers values from the same table
        $centers = center::all();  // Fetch all centers

        // Fetch unique venues values from the same table
        $venues = venues::all();  // Fetch all venues

        // Return the view with the necessary data
        return view('masters.venues.ci_assistants.index', compact('ciAssistants', 'districts', 'centers', 'venues'));
    }




    public function create(Request $request)
    {
        $role = session('auth_role');
        $user = $request->get('auth_user');

        if ($role == 'venue') {
            // Ensure $user exists before accessing properties
            if (!$user) {
                return redirect()->back()->withErrors(['error' => 'Unauthorized access.']);
            }

            $venues = Venues::where('venue_id', $user->venue_id)->get();
            $centers = Center::where('center_code', $user->venue_center_id)->get();
            $districts = District::where('district_code', $user->venue_district_id)->get();

            return view('masters.venues.ci_assistants.create', data: compact('districts', 'centers', 'venues'));
        } else if ($role == 'ci') {
            if (!$user) {
                return redirect()->back()->withErrors(['error' => 'Unauthorized access.']);
            }
            $venues = Venues::where('venue_code', $user->ci_venue_id)->get();
            $centers = Center::where('center_code', $user->ci_center_id)->get();
            $districts = District::where('district_code', $user->ci_district_id)->get();
            return view('masters.venues.ci_assistants.create', data: compact('districts', 'centers', 'venues'));
        }
        // Fetch necessary data for CI Assistants form (venues, centers, districts)
        $venues = Venues::all(); // Retrieves all venues
        $centers = Center::all(); // Retrieves all centers
        $districts = District::all(); // Retrieves all districts

        return view('masters.venues.ci_assistants.create', data: compact('districts', 'centers', 'venues'));
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
            'district' => 'required|numeric', // Assuming you have a relation to district
            'center' => 'required|numeric',
            'venue' => 'required|numeric',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:cheif_invigilator_assistant,cia_email',
            'phone' => 'required|string|max:15',
            'designation' => 'required|string|max:255',
            'cropped_image' => 'nullable|string'  // For image (if needed)
        ], $messages);

        try {
            if (!empty($validated['cropped_image'])) {
                $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $validated['cropped_image']);
                $imageData = base64_decode($imageData);
                if ($imageData === false) {
                    throw new \Exception('Base64 decode failed.');
                }
                // Create a unique image name
                $imageName = $validated['name'] . time() . '.png';
                $imagePath = 'images/cias/' . $imageName;
                // Store the image
                Storage::disk('public')->put($imagePath, $imageData);

                // Optionally, compress the image if necessary (if it exceeds a size)
                $fullImagePath = storage_path('app/public/' . $imagePath);
                if (filesize($fullImagePath) > 200 * 1024) {
                    $this->imageService->saveAndCompressImage($imageData, $fullImagePath, 200);
                }
                $validated['image'] = $imagePath;
            }

            // Create a new CheifInvigilatorAssistant record
            $cia = CIAssistant::create([
                'cia_district_id' => $validated['district'],
                'cia_center_id' => $validated['center'],
                'cia_venue_id' => $validated['venue'],
                'cia_name' => $validated['name'],
                'cia_email' => $validated['email'],
                'cia_phone' => $validated['phone'],
                'cia_designation' => $validated['designation'],
                'cia_image' => $validated['image'] ?? null  // Save image if available
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
        $messages = [
            'district.required' => 'Please select a district',
            'district.numeric' => 'Please select a valid district',
            'center.required' => 'Please select a center',
            'center.numeric' => 'Please select a valid center',
            'venue.required' => 'Please select a venue',
            'venue.numeric' => 'Please select a valid venue',
        ];
        // Validation rules for CI Assistant data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:cheif_invigilator_assistant,cia_email,' . $id . ',cia_id',
            'phone' => 'required|string|max:15',
            'designation' => 'nullable|string|max:255',
            'district' => 'required|numeric',
            'center' => 'required|numeric',
            'venue' => 'required|numeric',
            'cropped_image' => 'nullable|string', // Base64 encoded image string
        ], $messages);

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
                $imageName = $validated['name'] . time() . '.png';
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
                'cia_name' => $validated['name'],
                'cia_email' => $validated['email'],
                'cia_phone' => $validated['phone'],
                'cia_designation' => $validated['designation'],
                'cia_district_id' => $validated['district'],
                'cia_center_id' => $validated['center'],
                'cia_venue_id' => $validated['venue'],
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
            // Log the status change
            AuditLogger::log(
                'Scribe Status Changed',
                CIAssistant::class,
                $ciAssistant->cia_id,
                ['status' => $oldStatus],
                ['status' => $ciAssistant->cia_status]
            );

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
        $ciAssistant = CIAssistant::with(['district', 'venue', 'center'])->findOrFail($id);
        $centerCount = $ciAssistant->district->centers()->count();  // Assuming 'centers' is a relationship in District model
        $venueCount = $ciAssistant->district->venues()->count();
        $staffCount = $ciAssistant->district->treasuryOfficers()->count() + $ciAssistant->district->mobileTeamStaffs()->count();
        $ci_count = $ciAssistant->venue->chiefinvigilator()->count();
        $invigilator_count = $ciAssistant->venue->invigilator()->count();
        $cia_count = $ciAssistant->venue->cia()->count();
        // Return the view with the CI Assistant and related data
        return view('masters.venues.ci_assistants.show', compact('ciAssistant', 'centerCount', 'venueCount', 'staffCount', 'ci_count', 'invigilator_count', 'cia_count'));
    }
}
