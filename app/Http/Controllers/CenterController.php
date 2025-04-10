<?php

namespace App\Http\Controllers;

use App\Mail\UserAccountCreationMail;
use App\Mail\UserEmailVerificationMail;
use App\Models\Center;
use App\Models\Venues;
use App\Models\TreasuryOfficer;
use App\Models\MobileTeamStaffs;
use App\Models\District;
use Illuminate\Http\Request;
use App\Services\ImageCompressService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Services\AuditLogger;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CenterController extends Controller
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
        // Get the authenticated user's role from the session
        $role = session('auth_role');
        // Get the filtered centers and options
        $data = $this->getFilteredData($request, $role);

        return view('masters.district.centers.index', $data);
    }
    private function getFilteredData(Request $request, $role)
    {
        // dd();
        // Build the centers query with eager loading
        $user_details = $request->get('auth_user');
        // $user_district_code  = $user_details->district_code;
        // dd($user_district_code);
        $centersQuery = Center::query()
            ->select([
                'centers.center_id',
                'centers.center_district_id',
                'centers.center_name',
                'centers.center_code',
                'centers.center_email',
                'centers.center_phone',
                'centers.center_image',
                'centers.center_email_status',
                'centers.center_status'
            ])
            ->join('district', 'centers.center_district_id', '=', 'district.district_code')
            ->with([
                'district' => function ($query) {
                    $query->select('district_id', 'district_code', 'district_name');
                }
            ]);

        // Apply role-based filtering
        if ($role == 'district') {
            // Get district code from session or request
            $districtCode = $user_details->district_code; // Assuming district code is stored in session
            // dd($districtCode);
            if ($districtCode) {
                $centersQuery->where('centers.center_district_id', $districtCode);
            }
        } elseif ($role == 'id') {
            // Show all centers (no filtering applied)
        }

        // Apply additional filters from request
        if ($request->filled('district')) {
            $centersQuery->where('centers.center_district_id', $request->district);
        }

        if ($request->filled('centerCode')) {
            $centersQuery->where('centers.center_code', $request->centerCode);
        }

        // Get centers
        $centers = $centersQuery->orderBy('centers.center_code')->get();

        // Get districts and center codes efficiently
        $districts = District::select('district_code', 'district_name')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('centers')
                    ->whereColumn('centers.center_district_id', 'district.district_code');
            })
            ->orderBy('district_name')
            ->get();

        $centerCodes = Center::select('center_code', 'center_name', 'center_district_id')
            ->whereNotNull('center_code')
            ->distinct()
            ->orderBy('center_name')
            ->get();

        return compact('centers', 'districts', 'centerCodes');
    }





    public function create(Request $request)
    {
        $role = session('auth_role');
        $user = $request->get('auth_user');
        // $user_district_code = $user->district_code;
        // dd($user_district_code);
        // dd($user);
        if ($role == 'district') {
            if (!$user) {
                return redirect()->back()->withErrors(['error' => 'Unauthorized access.']);
            }

            $districts = District::where('district_code', $user->district_code)->get();
            return view('masters.district.centers.create', compact('districts', 'user'));
        }

        // Default case: Fetch all districts and centers
        $districts = District::all();
        return view('masters.district.centers.create', compact('districts'));
    }



    public function store(Request $request)
    {
        $validated = $request->validate([
            'center_name' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'center_code' => 'required|numeric|unique:centers',
            'email' => 'required|email|unique:centers,center_email',
            'phone' => 'required|string|max:15',
            'alternate_phone' => 'nullable|string|max:15',
            'password' => 'required|string|min:6',
            'address' => 'required|string',
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric',
            'cropped_image' => 'nullable|string'
        ]);
    //    dd($validated);
        try {
            // Process the cropped image if present
            if (!empty($validated['cropped_image'])) {
                $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $validated['cropped_image']);
                $imageData = base64_decode($imageData);

                if ($imageData === false) {
                    throw new \Exception('Base64 decode failed.');
                }

                $imageName = $validated['center_code'] . time() . '.png';
                $imagePath = 'images/centers/' . $imageName;

                $stored = Storage::disk('public')->put($imagePath, $imageData);

                if (!$stored) {
                    throw new \Exception('Failed to save image to storage.');
                }

                $validated['image'] = $imagePath;
            }

            // Hash the password and generate a verification token
            $validated['center_password'] = Hash::make($validated['password']);
            $validated['verification_token'] = Str::random(64);

            // Create the center record
            $center = Center::create([
                'center_district_id' => $validated['district'],
                'center_name' => $validated['center_name'],
                'center_code' => $validated['center_code'],
                'center_email' => $validated['email'],
                'center_phone' => $validated['phone'],
                'center_alternate_phone' => $validated['alternate_phone'],
                'center_password' => $validated['center_password'],
                'center_address' => $validated['address'],
                'center_longitude' => $validated['longitude'],
                'center_latitude' => $validated['latitude'],
                'center_image' => $validated['image'] ?? null,
                'verification_token' => $validated['verification_token'],
            ]);

            // Log the creation with the audit logger
            AuditLogger::log('Center Created', Center::class, $center->id, null, $center->toArray());

            // Send the welcome email
            // Mail::send('email.center_created', [
            //     'name' => $center->center_name,
            //     'email' => $center->center_email,
            //     'password' => $request->password, // Plain password for first login
            // ], function ($message) use ($center) {
            //     $message->to($center->center_email)
            //         ->subject('Welcome to Our Platform');
            // });
            Mail::to($center->center_email)->send(new UserAccountCreationMail($center->center_name, $center->center_email, $validated['password'])); // Use the common mailable


            $verificationLink = route('center.verifyEmail', ['token' => urlencode($center->verification_token)]);

            if ($verificationLink) {
                Mail::to($center->center_email)->send(new UserEmailVerificationMail($center->center_name, $center->center_email, $verificationLink)); // Use the common mailable
            } else {
                throw new \Exception('Failed to generate verification link.');
            }
            // Redirect with success message
            return redirect()->route('centers.index')
                ->with('success', 'Center created successfully. Email verification link has been sent.');
        } catch (\Exception $e) {
            // Handle exceptions and show an error message
            return back()->withInput()
                ->with('error', 'Error creating center: ' . $e->getMessage());
        }
    }

    public function verifyEmail($token)
    {
        $decodedToken = urldecode($token);

        $center = Center::where('verification_token', $decodedToken)->first();

        if (!$center) {
            return redirect()->route('login')->with('status', 'Invalid verification link.');
        }

        $center->update([
            'center_email_status' => true,
            'verification_token' => null,
        ]);

        return redirect()->route('login')->with('status', 'Email verified successfully.');
    }

    public function edit($center_id)
    {
        $ids = decrypt($center_id);
        $center = Center::findOrFail($ids); // Retrieves the center by its ID
        $districts = District::all(); // Fetch all districts
        return view('masters.district.centers.edit', compact('center', 'districts'));
    }

    public function update(Request $request, $id)
    {

         $user = current_user();
        $role = session('auth_role');
        $center = null;
        if ($user->role && $user->role->role_department !== 'ID' && $role !== "district") {
            //myaccount
            $center = Center::findOrFail($user->center_id);
        } elseif ($role === 'district') {
            //only distrrict
            $center = Center::where('center_district_id', $user->district_code)
                ->where('center_id', $id)
                ->firstOrFail();
        } else {
            //comman
            $center = Center::findOrFail($id);
        }
        $center = Center::findOrFail($id);
        $messages = [
            'district.required' => 'Please select a district',
            'district.integer' => 'Please select a valid district',
        ];
        $validated = $request->validate([
            'district' => 'required|string|max:255',
            'center_name' => 'required|string|max:255',
            'center_code' => 'required|numeric|unique:centers,center_code,' . $id . ',center_id',
            'email' => 'required|email|unique:centers,center_email,' . $id . ',center_id',
            'phone' => 'required|string|max:15',
            'alternate_phone' => 'nullable|string|max:15',
            'password' => 'nullable|string|min:6',
            'address' => 'required|string',
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric',
            'cropped_image' => 'nullable|string'
        ], $messages);

        try {
            $newImagePath = null;

            if (!empty($validated['cropped_image'])) {
                $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $validated['cropped_image']);
                $imageData = base64_decode($imageData);

                if ($imageData === false) {
                    throw new \Exception('Base64 decode failed.');
                }

                $imageName = $validated['center_code'] . time() . '.png';
                $imagePath = 'images/center/' . $imageName;
                // Save the image in public storage
                Storage::disk('public')->put($imagePath, $imageData);
                // Compress if image exceeds 200 KB
                $fullImagePath = storage_path('app/public/' . $imagePath);
                if (filesize($fullImagePath) > 200 * 1024) {
                    $this->imageService->saveAndCompressImage($imageData, $fullImagePath, 200);
                }
                //  Delete the old image if it exists
                if ($center->center_image && Storage::disk('public')->exists($center->center_image)) {
                    Storage::disk('public')->delete($center->center_image);
                }
                // Set new image path to be saved in the database
                $newImagePath = $imagePath;
            }
            // Only update password if provided
            if ($request->filled('password')) {
                $validated['center_password'] = Hash::make($validated['password']);
            }
            $role = session('auth_role');
            $user = current_user();
            $updateData = [
                'center_district_id' => $validated['district'],
                'center_name' => $validated['center_name'],
                'center_code' => $validated['center_code'],
                'center_email' => $validated['email'],
                'center_phone' => $validated['phone'],
                'center_alternate_phone' => $validated['alternate_phone'] ?? null,
                'center_password' => $validated['center_password'] ?? $center->center_password,
                'center_address' => $validated['address'],
                'center_longitude' => $validated['longitude'],
                'center_latitude' => $validated['latitude'],
            ];
            if ($user->role && $user->role->role_department == 'ID') {
                $updateData['center_district_id'] = $validated['district'];
            }
            // Add new image path to update data if present
            if ($newImagePath) {
                $updateData['center_image'] = $newImagePath;
            }

            // Get old values and update the district
            $oldValues = $center->getOriginal();
            $center->update($updateData);

            // Get changed values for logging
            $changedValues = $center->getChanges();
            $oldValues = array_intersect_key($oldValues, $changedValues);

            // Log district update with old and new values
            AuditLogger::log('Center Updated', Center::class, $center->center_id, $oldValues, $changedValues);
            if (url()->previous() === route('centers.edit', $id)) {
                return redirect()->route('centers.index')
                    ->with('success', 'Center updated successfully');
            } else {
                return redirect()->back()->with('success', 'Center updated successfully');
            }
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error updating center: ' . $e->getMessage());
        }
    }


    public function show($id)
    {
        $ids = decrypt($id);
        // Find the center by ID and load the related district
        $center = Center::with('district')->findOrFail($ids);

        $centerCount = $center->district->centers()->count();  // Assuming 'centers' is a relationship in District model
        $venueCount = $center->district->venues()->count();
        $staffCount = $center->district->treasuryOfficers()->count() + $center->district->mobileTeamStaffs()->count();
        // dd( $center->treasuryOfficers);
        // Log view action
        AuditLogger::log('Center Viewed', Center::class, $center->center_id);
        // Pass the counts to the view
        return view('masters.district.centers.show', compact('center', 'centerCount', 'venueCount', 'staffCount'));
    }

    public function destroy(Center $center)
    {
        if ($center->image) {
            Storage::disk('public')->delete($center->image);
        }
        $center->delete();
        return redirect()->route('centers.index')->with('success', 'Center deleted successfully.');
    }
    public function toggleStatus($id)
    {
        try {
            $ids = decrypt($id);
            $center = Center::findOrFail($ids);

            // Get current status before update
            $oldStatus = $center->center_status;

            // Toggle the status
            $center->center_status = !$center->center_status;
            $center->save();

            // Log the status change
            AuditLogger::log(
                'Center Status Changed',
                District::class,
                $center->center_id,
                ['status' => $oldStatus],
                ['status' => $center->center_status]
            );

            return response()->json([
                'success' => true,
                'status' => $center->center_status,
                'message' => 'Center status updated successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update Center status',
                'details' => $e->getMessage(),  // Optional
            ], 500);
        }
    }
}
