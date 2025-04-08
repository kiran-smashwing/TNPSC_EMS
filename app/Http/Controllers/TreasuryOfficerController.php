<?php

namespace App\Http\Controllers;

use App\Mail\UserAccountCreationMail;
use App\Mail\UserEmailVerificationMail;
use App\Models\TreasuryOfficer;
use App\Models\Center;
use Illuminate\Support\Facades\DB;
use App\Models\Venues;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Services\AuditLogger;
use App\Models\District;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Services\ImageCompressService;

class TreasuryOfficerController extends Controller
{
    protected $imageService;

    public function __construct(ImageCompressService $imageService)
    {
        $this->middleware('auth.multi')->except('verifyEmail');
        $this->imageService = $imageService;
    }

    public function index(Request $request)
{
    $role = session('auth_role');
    $user_details = $request->get('auth_user');
    $user_district_code = $user_details->district_code ?? null;

    $query = TreasuryOfficer::with('district');

    if (!empty($user_district_code)) {
        $query->where('tre_off_district_id', $user_district_code);
    }

    if ($request->filled('district')) {
        $query->where('tre_off_district_id', $request->district);
    }

    $treasuryOfficers = $query->orderBy('tre_off_name')->get();
    $districts = District::all();

    return view('masters.district.treasury_officers.index', compact('treasuryOfficers', 'districts'));
}

    
    

    private function getFilteredData(Request $request, $role)
    {
        $user_details = $request->get('auth_user');

        $centersQuery = TreasuryOfficer::query()
            ->select([
                'mobile_team.mobile_id',
                'mobile_team.mobile_district_id',
                'mobile_team.mobile_name',
                'mobile_team.mobile_designation',
                'mobile_team.mobile_phone',
                'mobile_team.mobile_email',
                'mobile_team.mobile_employeeid',
                'centers.center_code', // include this if needed for ordering/display
            ])
            ->join('district', 'mobile_team.mobile_district_id', '=', 'district.district_code')
            ->join('centers', 'centers.center_district_id', '=', 'mobile_team.mobile_district_id') // required join
            ->with([
                'district' => function ($query) {
                    $query->select('district_id', 'district_code', 'district_name');
                }
            ]);

        // Apply role-based filtering
        if ($role === 'district') {
            $districtCode = $user_details->district_code ?? null;
            if ($districtCode) {
                $centersQuery->where('mobile_team.mobile_district_id', $districtCode);
            }
        }

        // Apply additional filters from request
        if ($request->filled('district')) {
            $centersQuery->where('mobile_team.mobile_district_id', $request->district);
        }

        if ($request->filled('centerCode')) {
            $centersQuery->where('centers.center_code', $request->centerCode);
        }

        // Get centers
        $centers = $centersQuery->orderBy('centers.center_code')->get();

        // Get districts
        $districts = District::select('district_code', 'district_name')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('mobile_team')
                    ->whereColumn('mobile_team.mobile_district_id', 'district.district_code');
            })
            ->orderBy('district_name')
            ->get();

        // Get center codes
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
            return view('masters.district.treasury_officers.create', compact('districts', 'user'));
        }

        // Default case: Fetch all districts and centers
        $districts = District::all();
        return view('masters.district.treasury_officers.create', compact('districts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'district' => 'required|numeric',
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'email' => 'required|email|unique:treasury_officer,tre_off_email',
            'employeeid' => 'required|string|unique:treasury_officer,tre_off_employeeid',
            'password' => 'required|string|min:6',
            'cropped_image' => 'nullable|string',
        ]);

        try {
            // Handle cropped image processing
            if (!empty($validated['cropped_image'])) {
                $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $validated['cropped_image']);
                $imageData = base64_decode($imageData);

                if ($imageData === false) {
                    throw new \Exception('Base64 decode failed.');
                }

                $imageName = 'tre_off_' . time() . '.png';
                $imagePath = 'images/treasury/' . $imageName;

                $stored = Storage::disk('public')->put($imagePath, $imageData);

                if (!$stored) {
                    throw new \Exception('Failed to save image to storage.');
                }

                $fullImagePath = storage_path('app/public/' . $imagePath);
                if (filesize($fullImagePath) > 200 * 1024) {
                    $this->imageService->saveAndCompressImage($imageData, $fullImagePath, 200);
                }

                $validated['image'] = $imagePath;
            }

            // Hash the password
            $validated['password'] = Hash::make($validated['password']);

            // Generate email verification token
            $verificationToken = Str::random(64);

            // Create the Treasury Officer record
            $treasuryOfficer = TreasuryOfficer::create([
                'tre_off_district_id' => $validated['district'],
                'tre_off_name' => $validated['name'],
                'tre_off_designation' => $validated['designation'],
                'tre_off_phone' => $validated['phone'],
                'tre_off_email' => $validated['email'],
                'tre_off_employeeid' => $validated['employeeid'],
                'tre_off_password' => $validated['password'],
                'tre_off_image' => $validated['image'] ?? null,
                'verification_token' => $verificationToken, // Save the token for email verification
            ]);


            Mail::to($treasuryOfficer->tre_off_email)->send(new UserAccountCreationMail($treasuryOfficer->tre_off_name, $treasuryOfficer->tre_off_email, $validated['password'])); // Use the common mailable

            // Send email verification link
            $verificationLink = route('treasury-officer.verifyEmail', ['token' => urlencode($verificationToken)]);

            if ($verificationLink) {
                Mail::to($treasuryOfficer->tre_off_email)->send(new UserEmailVerificationMail($treasuryOfficer->tre_off_name, $treasuryOfficer->tre_off_email, $verificationLink)); // Use the common mailable
            } else {
                throw new \Exception('Failed to generate verification link.');
            }
            // Log the creation
            AuditLogger::log('Treasury Officer Created', TreasuryOfficer::class, $treasuryOfficer->tre_off_id, null, $treasuryOfficer->toArray());

            return redirect()->route('treasury-officers.index')
                ->with('success', 'Treasury Officer created successfully. Welcome email and verification link sent.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error creating treasury officer: ' . $e->getMessage());
        }
    }

    public function verifyEmail($token)
    {

        $decodedToken = urldecode($token);

        $treasuryOfficer = TreasuryOfficer::where('verification_token', $decodedToken)->first();

        if (!$treasuryOfficer) {
            return redirect()->route('login')->with('status', 'Invalid verification link.');
        }

        $treasuryOfficer->update([
            'tre_off_email_status' => true, // Mark email as verified
            'verification_token' => null, // Clear verification token
        ]);

        return redirect()->route('login')->with('status', 'Email verified successfully.');
    }


    public function edit($id)
    {
        $ids = decrypt($id);
        $treasuryOfficer = TreasuryOfficer::findOrFail($ids);
        $districts = District::all(); // Fetch all districts
        return view('masters.district.treasury_officers.edit', compact('treasuryOfficer', 'districts'));
    }

    public function update(Request $request, $id)
    {
        $treasuryOfficer = TreasuryOfficer::findOrFail($id);

        $validated = $request->validate([
            'district' => 'required|numeric',
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'email' => 'required|email|unique:treasury_officer,tre_off_email,' . $id . ',tre_off_id',
            'employeeid' => 'required|string|unique:treasury_officer,tre_off_employeeid,' . $id . ',tre_off_id',
            'password' => 'nullable|string|min:6',
            'cropped_image' => 'nullable|string'
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
                $imageName = $validated['name'] . time() . '.png';
                $imagePath = 'images/treasury/' . $imageName;

                // Save the image in public storage
                Storage::disk('public')->put($imagePath, $imageData);

                // Compress if image exceeds 200 KB
                $fullImagePath = storage_path('app/public/' . $imagePath);
                if (filesize($fullImagePath) > 200 * 1024) {
                    $this->imageService->saveAndCompressImage($imageData, $fullImagePath, 200);
                }
                //  Delete the old image if it exists
                if ($treasuryOfficer->tre_off_image && Storage::disk('public')->exists($treasuryOfficer->tre_off_image)) {
                    Storage::disk('public')->delete($treasuryOfficer->tre_off_image);
                }

                // Set new image path to be saved in the database
                $newImagePath = $imagePath;
            }

            // Only update password if provided
            if ($request->filled('password')) {
                $validated['password'] = Hash::make($validated['password']);
            }
            $role = session('auth_role');
            $user = current_user();
            // Prepare data for update, including the new image path if it exists
            $updateData = [
                'tre_off_district_id' => $validated['district'],
                'tre_off_name' => $validated['name'],
                'tre_off_designation' => $validated['designation'],
                'tre_off_phone' => $validated['phone'],
                'tre_off_email' => $validated['email'],
                'tre_off_employeeid' => $validated['employeeid'],
                'tre_off_password' => $validated['password'] ?? $treasuryOfficer->tre_off_password
            ];
            if ($user->role && $user->role->role_department == 'ID') {
                $updateData['tre_off_district_id'] = $validated['district'];
            }

            // Add new image path to update data if present
            if ($newImagePath) {
                $updateData['tre_off_image'] = $newImagePath;
            }
            // Store old values for logging
            $oldValues = $treasuryOfficer->getOriginal();
            $treasuryOfficer->update($updateData);
            // Get changed values for logging
            $changedValues = $treasuryOfficer->getChanges();
            $oldValues = array_intersect_key($oldValues, $changedValues);
            // Log the update
            AuditLogger::log('Treasury Officer Updated', TreasuryOfficer::class, $treasuryOfficer->tre_off_id, $oldValues, $changedValues);
            if (url()->previous() === route('treasury-officers.edit', $id)) {
                return redirect()->route('treasury-officers.index')
                    ->with('success', 'Treasury Officer updated successfully');
            } else {
                return redirect()->back()->with('success', 'Treasury Officer updated successfully');
            }
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error updating treasury officer: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $ids = decrypt($id);
        // Find the Treasury Officer by ID and load the related district
        $treasuryOfficer = TreasuryOfficer::with('district')->findOrFail($ids);

        // Count of centers, venues, and members related to the district
        $centerCount = $treasuryOfficer->district->centers()->count();  // Assuming 'centers' is a relationship in District model
        $venueCount = $treasuryOfficer->district->venues()->count();
        $staffCount = $treasuryOfficer->district->treasuryOfficers()->count() + $treasuryOfficer->district->mobileTeamStaffs()->count();


        // Log view action
        AuditLogger::log('Treasury Officer Viewed', TreasuryOfficer::class, $treasuryOfficer->tre_off_id);

        // Pass the counts to the view
        return view('masters.district.treasury_officers.show', compact('treasuryOfficer', 'centerCount', 'venueCount', 'staffCount'));
    }


    public function destroy($id)
    {
        $treasuryOfficer = TreasuryOfficer::findOrFail($id);

        // Log deletion
        AuditLogger::log('Treasury Officer Deleted', TreasuryOfficer::class, $treasuryOfficer->tre_off_id);

        $treasuryOfficer->delete();

        return redirect()->route('treasury_officer.index')->with('success', 'Treasury Officer deleted successfully');
    }

    public function logout(Request $request)
    {
        $tre_off_id = session('tre_off_id');

        // Log logout
        if ($tre_off_id) {
            AuditLogger::log('Treasury Officer Logout', TreasuryOfficer::class, $tre_off_id);
        }

        $request->session()->forget('tre_off_id');

        return redirect()->route('treasury_officer.login');
    }

    public function toggleStatus($id)
    {
        try {
            $ids = decrypt($id);
            $treasuryOfficer = TreasuryOfficer::findOrFail($ids);

            // Get current status before update
            $oldStatus = $treasuryOfficer->tre_off_status;

            // Toggle the status
            $treasuryOfficer->tre_off_status = !$treasuryOfficer->tre_off_status;
            $treasuryOfficer->save();

            // Log the status change
            AuditLogger::log(
                'Treasury Officer Status Changed',
                TreasuryOfficer::class,
                $treasuryOfficer->tre_off_id,
                ['status' => $oldStatus],
                ['status' => $treasuryOfficer->tre_off_status]
            );

            return response()->json([
                'success' => true,
                'status' => $treasuryOfficer->tre_off_status,
                'message' => 'Treasury Officer status updated successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update treasury officer status',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
}
