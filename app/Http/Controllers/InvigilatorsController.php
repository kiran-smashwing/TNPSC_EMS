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

    public function index(Request $request)
    {
        // Get user details
        $role = session('auth_role');
        $user_details = $request->get('auth_user');
        $user_venue_code = $user_details->venue_code ?? null;
        // dd($user_details);
        // Start the query for Invigilators
        $query = Invigilator::query();

        // If the user has a district_code, show only their district's data
        if (!empty($user_venue_code)) {
            $query->where('invigilator_venue_id', $user_venue_code);
        }

        // Apply filter by district if selected (overrides auto district filter)
        if ($request->filled('district')) {
            $query->where('invigilator_district_id', $request->input('district'));
        }

        // Apply filter by center if selected
        if ($request->filled('center')) {
            $query->where('invigilator_center_id', $request->input('center'));
        }

        // Apply filter by venue if selected
        if ($request->filled('venue')) {
            $query->where('invigilator_venue_id', $request->input('venue'));
        }

        // Fetch filtered invigilators
        $invigilators = $query->orderBy('invigilator_name')->get();
        // dd($invigilators);

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
        return view('masters.venues.invigilator.index', compact('invigilators', 'districts', 'centers', 'venues'));
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

            return view('masters.venues.invigilator.create', compact('venues', 'centers', 'districts', 'user'));
        } else if ($role == 'ci') {
            if (!$user) {
                return redirect()->back()->withErrors(['error' => 'Unauthorized access.']);
            }
            $venues = Venues::where('venue_code', $user->ci_venue_id)->get();
            $centers = Center::where('center_code', $user->ci_center_id)->get();
            $districts = District::where('district_code', $user->ci_district_id)->get();
            return view('masters.venues.invigilator.create', compact('venues', 'centers', 'districts', 'user'));
        }
        // Fetch any necessary data to display in the form (e.g., venues, centers)
        $venues = Venues::all(); // Or use a filter if necessary
        $centers = Center::all(); // Same as above
        $districts = District::all(); // Same as above

        // Return the view with the data
        return view('masters.venues.invigilator.create', compact('venues', 'centers', 'districts', 'user'));
    }

    public function store(Request $request)
    {
        // Validate incoming request data
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
            // 'mail' => 'required|email|max:255|unique:invigilator,invigilator_email',
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
                // 'invigilator_email' => $validated['mail'],
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
        $ids = decrypt($id);
        // Fetch the specific invigilator by ID
        $invigilator = Invigilator::findOrFail($ids);
        // Fetch any necessary data to display in the form (e.g., venues, centers)
        $venues = Venues::all(); // Or use a filter if necessary
        $centers = Center::all(); // Same as above
        $districts = District::all(); // Same as above
        // Pass the invigilator data to the edit view
        return view('masters.venues.invigilator.edit', compact('venues', 'centers', 'districts', 'invigilator'));
    }

    public function update(Request $request, $id)
    {
        $user = current_user();
        $role = session('auth_role');
        $invigilator = null;
        if ($user->role && $user->role->role_department !== 'ID' && $role !== "district") {
            //myaccount
            $invigilator = Invigilator::findOrFail($user->invigilator_id);
        } elseif ($role === 'district') {
            //only distrrict
            $invigilator = Invigilator::where('invigilator_district_id', $user->district_code)
                ->where('invigilator_id', $id)
                ->firstOrFail();
        } elseif ($role === 'ci' || $role === 'venue') {
            //only CI
            $venue_code = $role == 'ci'? $user->ci_venue_id : $user->venue_code;
            $invigilator = Invigilator::where('invigilator_venue_id', $venue_code)
                ->where('invigilator_id', $id)
                ->firstOrFail();
        } else {
            //comman
            $invigilator = Invigilator::findOrFail($id);
        }
        // $invigilator = Invigilator::findOrFail($id);
        // Validate incoming request data
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
            // 'mail' => 'required|email|max:255|unique:invigilator,invigilator_email,' . $id . ',invigilator_id',
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

            $role = session('auth_role');
            $user = current_user();
            // Prepare data for update, including new image path if it exists
            $updateData = [
                // 'invigilator_district_id' => $validated['district'],
                // 'invigilator_center_id' => $validated['center'],
                // 'invigilator_venue_id' => $validated['venue'],
                'invigilator_name' => $validated['name'],
                // 'invigilator_email' => $validated['mail'],
                'invigilator_phone' => $validated['phone'],
                'invigilator_designation' => $validated['designation'],


            ];
            if ($role == 'district') {
                // $updateData['ci_district_id'] = $validated['district'];
                $updateData[ 'invigilator_center_id'] = $validated['center'];
                $updateData['invigilator_venue_id'] = $validated['venue'];
            }
            elseif ($user->role && $user->role->role_department == 'ID') {
                $updateData['invigilator_district_id'] = $validated['district'];
                $updateData[ 'invigilator_venue_id'] = $validated['center'];
                $updateData['invigilator_venue_id'] = $validated['venue'];
            }
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
            $ids = decrypt($id);
           $invigilator = Invigilator::findOrFail($ids);

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
        $ids = decrypt($id);
        
        // Fetch the invigilator with its related district, venue, and center using eager loading
        $invigilator = Invigilator::with(['district', 'venue', 'center'])->findOrFail($ids);

        // Handle null district
        $centerCount = optional($invigilator->district)->centers()->count() ?? 0;
        $venueCount = optional($invigilator->district)->venues()->count() ?? 0;
        $staffCount = (optional($invigilator->district)->treasuryOfficers()->count() ?? 0) +
            (optional($invigilator->district)->mobileTeamStaffs()->count() ?? 0);

        // Handle null venue
        $ci_count = optional($invigilator->venue)->chiefinvigilator()->count() ?? 0;
        $invigilator_count = optional($invigilator->venue)->invigilator()->count() ?? 0;
        $cia_count = optional($invigilator->venue)->cia()->count() ?? 0;


        // Return the view with the invigilator and its related data
        return view('masters.venues.invigilator.show', compact('invigilator', 'centerCount', 'venueCount', 'staffCount', 'ci_count', 'invigilator_count', 'cia_count'));
    }
}
