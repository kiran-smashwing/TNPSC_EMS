<?php

namespace App\Http\Controllers;

use App\Mail\UserAccountCreationMail;
use App\Mail\UserEmailVerificationMail;
use App\Models\DepartmentOfficial;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Services\AuditLogger;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Services\ImageCompressService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class DepartmentOfficialsController extends Controller
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
        // Fetch all department officials
        $departmentOfficials = DepartmentOfficial::all();

        // Pass the data to the index view
        return view('masters.department.officials.index', compact('departmentOfficials'));
    }


    public function create()
    {
        $roles = Role::all(); // Fetch all districts
        return view('masters.department.officials.create', compact('roles'));
    }



    public function store(Request $request)
    {
        // Custom error messages for validation
        $messages = [
            'role.required' => 'Please select a role.',
            'role.integer' => 'Please select a valid role.',
        ];

        // Validate the incoming request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:15',
            // 'role' => 'required|integer',
            'employee_id' => 'required|string|max:255',
            'email' => 'required|email|unique:department_officer,dept_off_email',
            'password' => 'required|string|min:6',
            'cropped_image' => 'nullable|string',
        ], $messages);

        try {
            // Handle image upload
            if (!empty($validated['cropped_image'])) {
                $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $validated['cropped_image']);
                $imageData = base64_decode($imageData);

                if ($imageData === false) {
                    throw new \Exception('Base64 decode failed.');
                }

                $imageName = $validated['email'] . time() . '.png';
                $imagePath = 'images/dept_officials/' . $imageName;

                // Store the image
                $stored = Storage::disk('public')->put($imagePath, $imageData);
                if (!$stored) {
                    throw new \Exception('Failed to save image to storage.');
                }

                $validated['image'] = $imagePath;
            }

            // Hash the password and generate verification token
            $validated['dept_off_password'] = Hash::make($validated['password']);
            $validated['verification_token'] = Str::random(64);

            // Create the department officer record
            $official = DepartmentOfficial::create([
                'dept_off_name' => $validated['name'],
                'dept_off_designation' => $validated['designation'],
                'dept_off_phone' => $validated['phone'],
                'dept_off_emp_id' => $validated['employee_id'],
                'dept_off_email' => $validated['email'],
                'dept_off_password' => $validated['dept_off_password'], // Hashed password
                'dept_off_image' => $validated['image'] ?? null,
                'verification_token' => $validated['verification_token'], // Verification token
            ]);

            // Send welcome email
          
            Mail::to($official->dept_off_email)->send(new UserAccountCreationMail($official->dept_off_name, $official->dept_off_email, $validated['password'])); // Use the common mailable

            $verificationLink = route('department-official.verifyEmail', ['token' => urlencode($official->verification_token)]);

            if ($verificationLink) {
                Mail::to($official->dept_off_email)->send(new UserEmailVerificationMail( $official->dept_off_name,  $official->dept_off_email, $verificationLink)); // Use the common mailable
            }
            else{
                throw new \Exception('Failed to generate verification link.');
            }
            // Log the creation action
            AuditLogger::log('Department Official Created', DepartmentOfficial::class, $official->dept_off_emp_id, null, $official->toArray());

            // Redirect with success message
            return redirect()->route('department-officials.index')
                ->with('success', 'Department official added successfully. Email verification link sent.');
        } catch (\Exception $e) {
            // Handle any errors during the process
            return redirect()->back()
                ->withInput()
                ->with('error', 'There was an issue creating the department official: ' . $e->getMessage());
        }
    }


    public function verifyEmail($token)
    {
        Log::info('Verification token received: ' . $token);

        $decodedToken = urldecode($token);

        $official = DepartmentOfficial::where('verification_token', $decodedToken)->first();

        if (!$official) {
            Log::error('Verification failed: Token not found. Token: ' . $decodedToken);
            return redirect()->route('department-officials.index')->with('error', 'Invalid verification link.');
        }

        $official->update([
            'dept_off_email_status' => true, // Updating the correct column for email verification
            'verification_token' => null, // Clear the token after verification
        ]);

        Log::info('Email verified successfully for department officer ID: ' . $official->dept_off_emp_id);

        return redirect()->route('department-officials.index')->with('success', 'Email verified successfully.');
    }


    public function edit($id)
    {
        $official = DepartmentOfficial::findOrFail($id);
        $roles = Role::all();

        return view('masters.department.officials.edit', compact('official', 'roles'));
    }

    public function update(Request $request, $id)
    {
        // Find the DepartmentOfficial by ID
        $official = DepartmentOfficial::findOrFail($id);
        $messages = [
            'role.required' => 'Please select a role',
            'role.integer' => 'Please select a valid role',
        ];
        // Validate the incoming request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'employee_id' => 'required|string|max:20',
            'designation' => 'nullable|string|max:100',
            // 'role' => 'required|integer',
            'phone' => 'nullable|string|max:20',
            'email' => 'required|email|max:255|unique:department_officer,dept_off_email,' . $id . ',dept_off_id',
            'cropped_image' => 'nullable|string', // Base64 encoded string for image
            'password' => 'nullable|string|min:6',
        ], $messages);

        try {
            $newImagePath = null;

            if (!empty($validated['cropped_image'])) {
                $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $validated['cropped_image']);
                $imageData = base64_decode($imageData);

                if ($imageData === false) {
                    throw new \Exception('Base64 decode failed.');
                }

                $imageName = $validated['email'] . time() . '.png';
                $imagePath = 'images/dept_official/' . $imageName;

                Storage::disk('public')->put($imagePath, $imageData);

                // Remove the old image if it exists
                if ($official->dept_off_image && Storage::disk('public')->exists($official->dept_off_image)) {
                    Storage::disk('public')->delete($official->dept_off_image);
                }

                $newImagePath = $imagePath;
            }
            // Only update password if provided
            if ($request->filled('password')) {
                $validated['dept_off_password'] = Hash::make($validated['password']);
            }

            // Prepare data for updating
            $updateData = [
                'dept_off_name' => $validated['name'],
                'dept_off_emp_id' => $validated['employee_id'],
                'dept_off_designation' => $validated['designation'],
                // 'dept_off_role' => $validated['role'],
                'dept_off_phone' => $validated['phone'],
                'dept_off_email' => $validated['email'],
                'dept_off_password' => $validated['dept_off_password'] ?? $official->dept_off_password
            ];

            if ($newImagePath) {
                $updateData['dept_off_image'] = $newImagePath;
            }
            // Get old values and update the DepartmentOfficial
            $oldValues = $official->getOriginal();
            $official->update($updateData);

            // Get changed values for logging
            $changedValues = $official->getChanges();
            $oldValues = array_intersect_key($oldValues, $changedValues);

            // Log district update with old and new values
            AuditLogger::log('Department Official Updated', DepartmentOfficial::class, $official->dept_off_emp_id, $oldValues, $changedValues);

            // Redirect with success message
            if (url()->previous() === route('department-officials.edit', $id)) {
                return redirect()->route('department-officials.index')
                    ->with('success', 'Department official updated successfully.');
            } else {
                return redirect()->back()->with('success', 'Department official updated successfully.');
            }
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error updating department official: ' . $e->getMessage());
        }
    }

    public function toggleStatus($id)
    {
        try {
            // Find the department official by ID
            $departmentOfficial = DepartmentOfficial::findOrFail($id);

            // Get the current status before updating
            $oldStatus = $departmentOfficial->dept_off_status;

            // Toggle the status
            $departmentOfficial->dept_off_status = !$departmentOfficial->dept_off_status;
            $departmentOfficial->save();

            return response()->json([
                'success' => true,
                'status' => $departmentOfficial->dept_off_status,
                'message' => 'Department Official status updated successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status',
                'details' => $e->getMessage(), // Optional for debugging
            ], 500);
        }
    }


    public function show($id)
    {
        // Find the department official by their ID
        $official = DepartmentOfficial::findOrFail($id);

        // Check if dept_off_role is null
        $roles = $official->dept_off_role ? Role::findOrFail($official->dept_off_role) : null;

        // Pass the department official data to the view
        return view('masters.department.officials.show', compact('official', 'roles'));
    }

}
