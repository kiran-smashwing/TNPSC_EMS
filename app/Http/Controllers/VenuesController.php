<?php

namespace App\Http\Controllers;

use App\Mail\UserAccountCreationMail;
use App\Mail\UserEmailVerificationMail;
use App\Models\Venues;
use App\Models\Center;
use App\Models\District;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\AuditLogger;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Services\ImageCompressService;
use Str;

class VenuesController extends Controller
{
    protected $imageService;
    public function __construct(ImageCompressService $imageService)
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth.multi')->except('verifyEmail');
        $this->imageService = $imageService;
    }

    public function index(Request $request)
    { 
        $user = current_user();
        $role = session('auth_role');
        if ($role== "district") {
            $centers = Center::select(['center_code', 'center_name', 'center_district_id'])
            ->where('center_district_id',$user->district_code)
            ->get();
        } else{
          $centers = Center::select(['center_code', 'center_name', 'center_district_id'])->get();
        }
        // Load necessary columns for dropdowns
        $districts = District::select(['district_code', 'district_name'])->get();
        

        return view('masters.venues.venue.index', compact('districts', 'centers'));
    }


    public function getVenuesJson(Request $request)
    {
        $user = current_user();
        $role = session('auth_role');
        $query = Venues::query()
            ->select([
                'venue_id',
                'venue_image',
                'venue_name',
                'venue_district_id',
                'venue_center_id',
                'venue_email',
                'venue_phone',
                'venue_email_status',
                'venue_status'
            ])
            ->with([
                'district:district_code,district_name',
                'center:center_code,center_name'
            ]);

        // Apply filters
        if ($role== "district") {
            $query->where('venue_district_id', $user->district_code);
        }
        else if ($request->filled('district')) {
            $query->where('venue_district_id', $request->input('district'));
        }

        if ($request->filled('center')) {
            $query->where('venue_center_id', $request->input('center'));
        }
        if ($request->has('search') && !empty($request->input('search.value'))) {
            $searchValue = strtolower($request->input('search.value'));
            $query->where(function ($q) use ($searchValue) {
                $q->whereRaw('LOWER(venue_name) LIKE ?', ["%{$searchValue}%"])
                    ->orWhereRaw('LOWER(venue_email) LIKE ?', ["%{$searchValue}%"])
                    ->orWhereRaw('LOWER(venue_phone) LIKE ?', ["%{$searchValue}%"])
                    ->orWhereHas('district', function ($query) use ($searchValue) {
                        $query->whereRaw('LOWER(district_name) LIKE ?', ["%{$searchValue}%"]);
                    })
                    ->orWhereHas('center', function ($query) use ($searchValue) {
                        $query->whereRaw('LOWER(center_name) LIKE ?', ["%{$searchValue}%"]);
                    });
            });
        }

        // Get total and filtered counts
        $totalRecords = Venues::count();
        $filteredRecords = $query->count();

        // Apply pagination
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $query->skip($start)->take($length);

        // Apply ordering
        $order = $request->input('order.0');
        if ($order) {
            $columnIndex = $order['column'];
            $columnDir = $order['dir'];
            $columns = $request->input('columns');
            $columnName = $columns[$columnIndex]['name'];

            if (in_array($columnName, ['district.district_name', 'center.center_name'])) {
                // Handle relationship sorting
                $relation = explode('.', $columnName)[0];
                $field = explode('.', $columnName)[1];
                $query->join($relation, "venues.venue_{$relation}_id", '=', "{$relation}.{$relation}_code")
                    ->orderBy($columnName, $columnDir);
            } else {
                $query->orderBy($columnName, $columnDir);
            }
        }

        // Get results
        $venues = $query->get();

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $venues
        ]);
    }

    public function create(Request $request)
    {
        $role = session('auth_role');
        $user = $request->get('auth_user');
        if ($role == 'district') {
            // Ensure $user exists before accessing properties
            if (!$user) {
                return redirect()->back()->withErrors(['error' => 'Unauthorized access.']);
            }
            $districts = District::where('district_code', $user->district_code)->get();// Retrieve all districts
            // dd($districts);
            $centers = Center::all(); // Retrieve all centers
            return view('masters.venues.venue.create', compact('districts', 'centers'));
        }
        $districts = District::all(); // Retrieve all districts
        $centers = Center::all(); // Retrieve all centers
        return view('masters.venues.venue.create', compact('districts', 'centers'));
    }


    public function store(Request $request)
    {
        $messages = [
            'district.required' => 'Please select a district',
            'district.numeric' => 'Please select a valid district',
            'center.required' => 'Please select a center',
            'center.numeric' => 'Please select a valid center',
        ];

        $validated = $request->validate([
            'district' => 'required|numeric',
            'center' => 'required|numeric',
            'venue_name' => 'required|string',
            'venue_code' => 'required|string|unique:venue,venue_code',
            'venue_code_provider' => 'required|string',
            'type' => 'required|string',
            'category' => 'required|string',
            'distance_from_railway' => 'required|string',
            'distance_from_treasury' => 'required|string',
            'email' => 'required|email|unique:venue,venue_email',
            'phone' => 'required|string|max:20',
            'alternative_phone' => 'nullable|string|max:20',
            'website' => 'nullable|url',
            'address' => 'required|string',
            'landmark' => 'required|string',
            'pin_code' => 'required|string',
            'password' => 'required|string|min:6',
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric',
            'bank_name' => 'string|max:255',
            'account_name' => 'string|max:255',
            'account_number' => 'string|max:50',
            'branch_name' => 'string|max:255',
            'account_type' => 'string|max:50',
            'ifsc' => 'string|max:11',
            'cropped_image' => 'nullable|string',
        ], $messages);

        try {
            // Handling image upload
            if (!empty($validated['cropped_image'])) {
                $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $validated['cropped_image']);
                $imageData = base64_decode($imageData);
                if ($imageData === false) {
                    throw new \Exception('Base64 decode failed.');
                }
                $imageName = $validated['venue_code'] . time() . '.png';
                $imagePath = 'images/venue/' . $imageName;
                $stored = Storage::disk('public')->put($imagePath, $imageData);
                if (!$stored) {
                    throw new \Exception('Failed to save image to storage.');
                }
                $fullImagePath = storage_path('app/public/' . $imagePath);
                if (filesize($fullImagePath) > 200 * 1024) {
                    $this->imageService->saveAndCompressImage($imageData, $fullImagePath, 200);
                }
                $validated['venue_image'] = $imagePath;
            }

            // Hashing the password
            $validated['venue_password'] = Hash::make($validated['password']);
            $validated['verification_token'] = Str::random(64);

            // Creating the venue
            $venue = Venues::create([
                'venue_district_id' => $validated['district'],
                'venue_center_id' => $validated['center'],
                'venue_name' => $validated['venue_name'],
                'venue_code' => $validated['venue_code'],
                'venue_codeprovider' => $validated['venue_code_provider'],
                'venue_type' => $validated['type'],
                'venue_category' => $validated['category'],
                'venue_distance_railway' => $validated['distance_from_railway'],
                'venue_treasury_office' => $validated['distance_from_treasury'],
                'venue_email' => $validated['email'],
                'venue_phone' => $validated['phone'],
                'venue_alternative_phone' => $validated['alternative_phone'],
                'venue_website' => $validated['website'],
                'venue_address' => $validated['address'],
                'venue_pincode' => $validated['pin_code'],
                'venue_landmark' => $validated['landmark'],
                'venue_longitude' => $validated['longitude'],
                'venue_latitude' => $validated['latitude'],
                'venue_password' => $validated['venue_password'],
                'venue_image' => $validated['venue_image'] ?? null,
                'venue_bank_name' => $validated['bank_name'],
                'venue_account_name' => $validated['account_name'],
                'venue_account_number' => $validated['account_number'],
                'venue_branch_name' => $validated['branch_name'],
                'venue_account_type' => $validated['account_type'],
                'venue_ifsc' => $validated['ifsc'],
                'verification_token' => $validated['verification_token'], // Verification token
            ]);

            // Sending email to the venue after creation
            Mail::to($venue->venue_email)->send(new UserAccountCreationMail($venue->venue_name, $venue->venue_email, $validated['password'])); // Use the common mailable

            $verificationLink = route('venues.verifyEmail', ['token' => urlencode($venue->verification_token)]);

            if ($verificationLink) {
                Mail::to($venue->venue_email)->send(new UserEmailVerificationMail($venue->venue_name, $venue->venue_email, $verificationLink)); // Use the common mailable
            } else {
                throw new \Exception('Failed to generate verification link.');
            }
            // Log the venue creation
            AuditLogger::log('Venue Created', Venues::class, $venue->venue_id, null, $venue->toArray());

            return redirect()->route('venues.index')
                ->with('success', 'Venue created successfully and notification email sent.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error creating venue: ' . $e->getMessage());
        }
    }
    public function verifyEmail($token)
    {

        $decodedToken = urldecode($token);

        $venue = Venues::where('verification_token', $decodedToken)->first();

        if (!$venue) {
            return redirect()->route('login')->with('status', 'Invalid verification link.');
        }

        $venue->update([
            'venue_email_status' => true, // Updating the correct column for email verification
            'verification_token' => null, // Clear the token after verification
        ]);

        
        return redirect()->route('login')->with('status', 'Email verified successfully.');
    }
    public function update(Request $request, $id)
    {
        // dd($request->all());
        $venue = Venues::findOrFail($id);
        // Validate the incoming request
        $messages = [
            'district.required' => 'Please select a district',
            'district.numeric' => 'Please select a valid district',
            'center.required' => 'Please select a center',
            'center.numeric' => 'Please select a valid center',
            'venue_type.required' => 'Please select a venue type',
            'venue_category.required' => 'Please select a venue category',
        ];
        $validated = $request->validate([
            'district' => 'required|numeric',
            'center' => 'required|numeric',
            'venue_name' => 'required|string',
            'venue_code' => 'required|string|unique:venue,venue_code,' . $id . ',venue_id',
            'venue_code_provider' => 'required|string', // Adjust max length as needed
            'type' => 'required|string',
            'category' => 'required|string',
            'distance_from_railway' => 'required|string',
            'distance_from_treasury' => 'required|string',
            'email' => 'required|email|unique:venue,venue_email,' . $id . ',venue_id',
            'phone' => 'required|string|max:20',
            'alternative_phone' => 'nullable|string|max:20',
            'website' => 'nullable|url',
            'address' => 'required|string',
            'landmark' => 'required|string',
            'pin_code' => 'required|string',
            'password' => 'nullable|string|min:6',
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric',
            'cropped_image' => 'nullable|string',
            'bank_name' => 'string|max:255',
            'account_name' => 'string|max:255',
            'account_number' => 'string|max:50',
            'branch_name' => 'string|max:255',
            'account_type' => 'string|max:50',
            'ifsc' => 'string|max:11',
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
                // Create a unique filename
                $imageName = $validated['venue_code'] . time() . '.png';
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
                $validated['venue_password'] = Hash::make($validated['password']);
            }
            // Prepare data for update, including the new image path if it exists
            $updateData = [
                'venue_district_id' => $validated['district'],
                'venue_center_id' => $validated['center'],
                'venue_name' => $validated['venue_name'],
                'venue_code' => $validated['venue_code'],
                'venue_codeprovider' => $validated['venue_code_provider'],
                'venue_type' => $validated['type'],
                'venue_category' => $validated['category'],
                'venue_distance_railway' => $validated['distance_from_railway'],
                'venue_treasury_office' => $validated['distance_from_treasury'],
                'venue_email' => $validated['email'],
                'venue_phone' => $validated['phone'],
                'venue_alternative_phone' => $validated['alternative_phone'],
                'venue_website' => $validated['website'],
                'venue_address' => $validated['address'],
                'venue_pincode' => $validated['pin_code'],
                'venue_landmark' => $validated['landmark'],
                'venue_longitude' => $validated['longitude'],
                'venue_latitude' => $validated['latitude'],
                'venue_password' => $validated['venue_password'] ?? $venue->venue_password,
                'venue_bank_name' => $validated['bank_name'],
                'venue_account_name' => $validated['account_name'],
                'venue_account_number' => $validated['account_number'],
                'venue_branch_name' => $validated['branch_name'],
                'venue_account_type' => $validated['account_type'],
                'venue_ifsc' => $validated['ifsc'],
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
            AuditLogger::log('Venue Updated', Venues::class, $venue->venue_id, $oldValues, $changedValues);
            // Redirect back with success message
            if (url()->previous() === route('venues.edit', $id)) {
                return redirect()->route('venues.index')
                    ->with('success', 'Venue updated successfully');
            } else {
                return redirect()->back()->with('success', 'Venue updated successfully');
            }

        } catch (\Exception $e) {
            // Handle any errors
            return back()->withInput()
                ->with('error', 'Error updating venue: ' . $e->getMessage());
        }
    }
    public function edit(Request $request, $id)
    {
        $role = session('auth_role');
        $user = $request->get('auth_user');
        if ($role == 'district') {
            // Ensure $user exists before accessing properties
            if (!$user) {
                return redirect()->back()->withErrors(['error' => 'Unauthorized access.']);
            }
            $districts = District::where('district_code', $user->district_code)->get();// Retrieve all districts
             $venue = Venues::with(['district', 'center'])->findOrFail($id);
            $centers = Center::all(); // Retrieve all centers
            return view('masters.venues.venue.edit', compact('venue','districts', 'centers','user'));
        }
        $districts = District::all(); // Retrieve all districts
        $centers = Center::all(); // Retrieve all centers
        $venue = Venues::with(['district', 'center'])->findOrFail($id);
        return view('masters.venues.venue.edit', compact('venue', 'districts', 'centers'));
    }
    public function toggleStatus($id)
    {
        try {
            // Find the venue by its ID
            $venue = Venues::findOrFail($id);

            // Get current status before update
            $oldStatus = $venue->venue_status;

            // Toggle the status
            $venue->venue_status = !$venue->venue_status;
            $venue->save();

            // Log the status change (if logging is needed)
            AuditLogger::log(
                'Venue Status Changed',
                Venues::class, // Use the Venue model
                $venue->id,   // ID of the venue
                ['status' => $oldStatus],
                ['status' => $venue->venue_status]
            );

            return response()->json([
                'success' => true,
                'status' => $venue->venue_status,
                'message' => 'Venue status updated successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update Venue status',
                'details' => $e->getMessage(),  // Optional, useful for debugging
            ], 500);
        }
    }

    public function show($id)
    {
        // Fetch the venue with its related district and center in one query using eager loading
        $venue = Venues::with(['district', 'center'])->findOrFail($id);
        $centerCount = $venue->district->centers()->count();  // Assuming 'centers' is a relationship in District model
        $venueCount = $venue->district->venues()->count();
        $staffCount = $venue->district->treasuryOfficers()->count() + $venue->district->mobileTeamStaffs()->count();
        $ci_count = $venue->chiefinvigilator()->count();
        $invigilator_count = $venue->invigilator()->count();
        $cia_count = $venue->cia()->count();
        // dd($ci);

        // Return the view with venue, district, and center data
        return view('masters.venues.venue.show', compact('venue', 'centerCount', 'venueCount', 'staffCount', 'ci_count', 'invigilator_count', 'cia_count'));
    }
}
