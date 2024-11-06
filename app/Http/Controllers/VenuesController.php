<?php
namespace App\Http\Controllers;
use App\Models\Venues;
use App\Models\Center;
use App\Models\District;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\AuditLogger;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Services\ImageCompressService;
class VenuesController extends Controller
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
        // Eager load the related district and center
        $venues = Venues::with(['district', 'center'])->get();
        return view('masters.venues.venue.index', compact('venues'));
    }
    public function create()
    {
        $districts = District::all(); // Retrieve all districts
        $centers = Center::all(); // Retrieve all centers
        return view('masters.venues.venue.create', compact('districts', 'centers'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'district_id' => 'required|exists:district,district_id',
            'center_id' => 'required|exists:centers,center_id',
            'venue_name' => 'required|string',
            'venue_code' => 'required|string',
            'venue_code_provider' => 'required|string', // Adjust max length as needed
            'venue_type' => 'required|string',
            'venue_category' => 'required|string',
            'venue_distance_railway' => 'required|string', // Can use numeric if it's a number
            'venue_treasury_office' => 'required|string', // Can use numeric if it's a number
            'venue_email' => 'nullable|email',
            'venue_phone' => 'required|string|max:20',
            'venue_alternative_phone' => 'nullable|string|max:20',
            'venue_website' => 'nullable|url',
            'venue_address' => 'required|string',
            'venue_password' => 'required|string|min:6',
            'venue_longitude' => 'required|numeric',
            'venue_latitude' => 'required|numeric',
            'cropped_image' => 'nullable|string',
            'venue_bank_name' => 'required|string|max:255',
            'venue_account_name' => 'required|string|max:255',
            'venue_account_number' => 'required|string|max:50',
            'venue_branch_name' => 'required|string|max:255',
            'venue_account_type' => 'required|string|max:50',
            'venue_ifsc' => 'required|string|max:11',
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
                $imageName = 'venue_' . time() . '.png';
                $imagePath = 'images/venue/' . $imageName;
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
                $validated['venue_image'] = $imagePath;
            }
            $validated['venue_password'] = Hash::make($validated['venue_password']);
            // Create the venue with bank details
            $venue = Venues::create([
                'venue_district_id' => $validated['district_id'],
                'venue_center_id' => $validated['center_id'],
                'venue_name' => $validated['venue_name'],
                'venue_code' => $validated['venue_code'],
                'venue_codeprovider' => $validated['venue_code_provider'],
                'venue_type' => $validated['venue_type'],
                'venue_category' => $validated['venue_category'],
                'venue_distance_railway' => $validated['venue_distance_railway'],
                'venue_treasury_office' => $validated['venue_treasury_office'],
                'venue_email' => $validated['venue_email'],
                'venue_phone' => $validated['venue_phone'],
                'venue_alternative_phone' => $validated['venue_alternative_phone'],
                'venue_website' => $validated['venue_website'],
                'venue_address' => $validated['venue_address'],
                'venue_longitude' => $validated['venue_longitude'],
                'venue_latitude' => $validated['venue_latitude'],
                'venue_password' => $validated['venue_password'],
                'venue_image' => $validated['venue_image'] ?? null,
                'venue_bank_name' => $validated['venue_bank_name'],
                'venue_account_name' => $validated['venue_account_name'],
                'venue_account_number' => $validated['venue_account_number'],
                'venue_branch_name' => $validated['venue_branch_name'],
                'venue_account_type' => $validated['venue_account_type'],
                'venue_ifsc' => $validated['venue_ifsc'],
            ]);
            // Log venue creation with new values
            AuditLogger::log('Venue Created', Venues::class, $venue->venue_id, null, $venue->toArray());
            return redirect()->route('venue')
                ->with('success', 'Venue created successfully');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error creating venue: ' . $e->getMessage());
        }
    }
    public function update(Request $request, $id)
    {
        $venue = Venues::findOrFail($id);
        // Validate the incoming request
        $validated = $request->validate([
            'district_id' => 'required|exists:district,district_id',
            'center_id' => 'required|exists:centers,center_id',
            'venue_name' => 'required|string',
            'venue_code' => 'required|string',
            'venue_code_provider' => 'required|string', // Adjust max length as needed
            'venue_type' => 'required|string',
            'venue_category' => 'required|string',
            'venue_distance_railway' => 'required|string', // Can use numeric if it's a number
            'venue_treasury_office' => 'required|string', // Can use numeric if it's a number
            'venue_email' => 'nullable|email',
            'venue_phone' => 'required|string|max:20',
            'venue_alternative_phone' => 'nullable|string|max:20',
            'venue_website' => 'nullable|url',
            'venue_address' => 'required|string',
            // 'venue_password' => 'required|string|min:6',
            'venue_longitude' => 'required|numeric',
            'venue_latitude' => 'required|numeric',
            'cropped_image' => 'nullable|string',
            'venue_bank_name' => 'required|string|max:255',
            'venue_account_name' => 'required|string|max:255',
            'venue_account_number' => 'required|string|max:50',
            'venue_branch_name' => 'required|string|max:255',
            'venue_account_type' => 'required|string|max:50',
            'venue_ifsc' => 'required|string|max:11',
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
                $imageName = 'venue_' . time() . '.jpg';
                $imagePath = 'images/venues/' . $imageName;
                // Save the image in public storage
                Storage::disk('public')->put($imagePath, $imageData);
                // Compress if image exceeds 200 KB
                $fullImagePath = storage_path('app/public/' . $imagePath);
                if (filesize($fullImagePath) > 200 * 1024) {
                    $this->imageService->saveAndCompressImage($imageData, $fullImagePath, 200);
                }
                // Delete the old image if it exists
                if ($venue->venue_image && Storage::disk('public')->exists($venue->venue_image)) {
                    Storage::disk('public')->delete($venue->venue_image);
                }
                // Set new image path to be saved in the database
                $newImagePath = $imagePath;
            }
            if ($request->filled('password')) {
                $validated['venue_password'] = Hash::make($validated['venue_password']);
            }
            // Prepare data for update, including the new image path if it exists
            $updateData = [
                'venue_district_id' => $validated['district_id'],
                'venue_center_id' => $validated['center_id'],
                'venue_name' => $validated['venue_name'],
                'venue_code' => $validated['venue_code'],
                'venue_codeprovider' => $validated['venue_code_provider'],
                'venue_type' => $validated['venue_type'],
                'venue_category' => $validated['venue_category'],
                'venue_distance_railway' => $validated['venue_distance_railway'],
                'venue_treasury_office' => $validated['venue_treasury_office'],
                'venue_email' => $validated['venue_email'],
                'venue_phone' => $validated['venue_phone'],
                'venue_alternative_phone' => $validated['venue_alternative_phone'],
                'venue_website' => $validated['venue_website'],
                'venue_address' => $validated['venue_address'],
                'venue_longitude' => $validated['venue_longitude'],
                'venue_latitude' => $validated['venue_latitude'],
                'venue_password' => $validated['venue_password'] ?? $venue->venue_password,
                'venue_bank_name' => $validated['venue_bank_name'],
                'venue_account_name' => $validated['venue_account_name'],
                'venue_account_number' => $validated['venue_account_number'],
                'venue_branch_name' => $validated['venue_branch_name'],
                'venue_account_type' => $validated['venue_account_type'],
                'venue_ifsc' => $validated['venue_ifsc'],
            ];
            // Add new image path to update data if present
            if ($newImagePath) {
                $updateData['venue_image'] = $newImagePath;
            }
            // Update the venue with the validated data
            $oldValues = $venue->getOriginal(); // Get old values before update
            $venue->update($updateData); // Update the venue
            // Get changed values for logging
            $changedValues = $venue->getChanges();
            $oldValues = array_intersect_key($oldValues, $changedValues);
            // Log venue update with old and new values
            AuditLogger::log('Venue Updated', Venues::class, $venue->id, $oldValues, $changedValues);
            // Redirect back with success message
            return redirect()->route('venue')
                ->with('success', 'Venue updated successfully');
        } catch (\Exception $e) {
            // Handle any errors
            return back()->withInput()
                ->with('error', 'Error updating venue: ' . $e->getMessage());
        }
    }
    public function edit($id)
    {
        $districts = District::all(); // Retrieve all districts
        $centers = Center::all(); // Retrieve all centers
        $venue = Venues::with(['district', 'center'])->findOrFail($id);
        return view('masters.venues.venue.edit', compact('venue', 'districts', 'centers'));
    }
    public function show($id)
    {
        // Fetch the venue first based on the ID
        $venue = Venues::findOrFail($id);
        // Fetch the district related to the venue, assuming there's a `district_id` column in the `venues` table
        $district = District::findOrFail($venue->venue_district_id);
        // Fetch the center related to the venue, assuming there's a `center_id` column in the `venues` table
        $center = Center::findOrFail($venue->venue_center_id);
        // Return the view with venue, district, and center data
        return view('masters.venues.venue.show', compact('venue', 'district', 'center'));
    }
}
