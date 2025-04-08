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
        // Get user details
        $role = session('auth_role');
        $user_details = $request->get('auth_user');
        $user_venue_code = $user_details->venue_code ?? null;

        // Start the query for Scribes with relationships
        $query = Scribe::with(['district', 'center', 'venue']);

        // If the user has a venue_code, show only their venue's data
        if (!empty($user_venue_code)) {
            $query->where('scribe_venue_id', $user_venue_code);
        }

        // Apply filter by district if selected (overrides auto venue filter)
        if ($request->filled('district')) {
            $query->where('scribe_district_id', $request->input('district'));
        }

        // Apply filter by center if selected
        if ($request->filled('center')) {
            $query->where('scribe_center_id', $request->input('center'));
        }

        // Apply filter by venue if selected
        if ($request->filled('venue')) {
            $query->where('scribe_venue_id', $request->input('venue'));
        }

        // Fetch filtered scribes
        $scribes = $query->orderBy('scribe_name')->get();

        // Fetch all districts (for dropdown)
        $districts = District::select('district_code', 'district_name')
            ->orderBy('district_name')
            ->get();
        // dd($districts);
        // Fetch all centers (for dropdown)
        $centers = Center::select('center_code', 'center_name', 'center_district_id')
            ->orderBy('center_name')
            ->get();

        // Fetch all venues (for dropdown)
        $venues = Venues::select('venue_code', 'venue_name', 'venue_center_id')
            ->orderBy('venue_name')
            ->get();

        // Return the view with the necessary data
        return view('masters.venues.scribe.index', compact('scribes', 'districts', 'centers', 'venues'));
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

            return view('masters.venues.scribe.create', compact('districts', 'centers', 'venues', 'user'));
        } else if ($role == 'ci') {
            if (!$user) {
                return redirect()->back()->withErrors(['error' => 'Unauthorized access.']);
            }
            $venues = Venues::where('venue_code', $user->ci_venue_id)->get();
            $centers = Center::where('center_code', $user->ci_center_id)->get();
            $districts = District::where('district_code', $user->ci_district_id)->get();
            return view('masters.venues.scribe.create', compact('districts', 'centers', 'venues', 'user'));
        }

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
            'district.numeric' => 'Please select a valid district',
            'center.required' => 'Please select a center',
            'center.numeric' => 'Please select a valid center',
            'venue.required' => 'Please select a venue',
            'venue.numeric' => 'Please select a valid venue',
        ];
        $validated = $request->validate([
            'district' => 'required|numeric', // Assuming you have a relation to district
            'center' => 'required|numeric',
            'venue' => 'required|numeric',
            'name' => 'required|string|max:255',
            // 'mail' => 'required|email|max:255|unique:scribe,scribe_email',
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
                'scribe_district_id' => $validated['district'],
                'scribe_center_id' => $validated['center'],
                'scribe_venue_id' => $validated['venue'],
                'scribe_name' => $validated['name'],
                // 'scribe_email' => $validated['mail'],
                'scribe_phone' => $validated['phone'],
                'scribe_designation' => $validated['designation'],
                'scribe_image' => $validated['image'] ?? null  // Save image if available
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
        $ids = decrypt($id);
        $scribe = Scribe::findOrFail($ids);

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
            'district.numeric' => 'Please select a valid district',
            'center.required' => 'Please select a center',
            'center.numeric' => 'Please select a valid center',
            'venue.required' => 'Please select a venue',
            'venue.numeric' => 'Please select a valid venue',
        ];
        $validated = $request->validate([
            'district' => 'required|numeric',
            'center' => 'required|numeric',
            'venue' => 'required|numeric',
            'name' => 'required|string|max:255',
            // 'mail' => 'required|email|max:255|unique:scribe,scribe_email,' . $id . ',scribe_id',
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
                // 'scribe_email' => $validated['mail'],
                'scribe_phone' => $validated['phone'],
                'scribe_designation' => $validated['designation'],
                'scribe_district_id' => $validated['district'],
                'scribe_center_id' => $validated['center'],
                'scribe_venue_id' => $validated['venue'],
            ];
            $role = session('auth_role');
            $user = current_user();
            if ($role == 'district') {
                // $updateData['scribe_district_id'] = $validated['district'];
                $updateData[ 'scribe_center_id'] = $validated['center'];
                $updateData['scribe_venue_id'] = $validated['venue'];
            }
            elseif ($user->role && $user->role->role_department == 'ID') {
                $updateData['scribe_district_id'] = $validated['district'];
                $updateData[ 'scribe_center_id'] = $validated['center'];
                $updateData['scribe_venue_id'] = $validated['venue'];
            }
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
        $ids = decrypt($id);
        $scribe = Scribe::with(['district', 'venue', 'center'])->findOrFail($ids);

        // Handle null district
        $centerCount = optional($scribe->district)->centers()->count() ?? 0;
        $venueCount = optional($scribe->district)->venues()->count() ?? 0;
        $staffCount = (optional($scribe->district)->treasuryOfficers()->count() ?? 0) +
            (optional($scribe->district)->mobileTeamStaffs()->count() ?? 0);

        // Handle null venue
        $ci_count = optional($scribe->venue)->chiefinvigilator()->count() ?? 0;
        $invigilator_count = optional($scribe->venue)->invigilator()->count() ?? 0;
        $cia_count = optional($scribe->venue)->cia()->count() ?? 0;
        // Return the view with the Scribe and related data
        return view('masters.venues.scribe.show', compact('scribe', 'centerCount', 'venueCount', 'staffCount', 'ci_count', 'invigilator_count', 'cia_count'));
    }

    public function toggleStatus($id)
    {
        try {
            $ids = decrypt($id);
            $scribe = Scribe::findOrFail($ids);

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
