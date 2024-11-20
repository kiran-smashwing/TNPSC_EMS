<?php

namespace App\Http\Controllers;

use App\Models\DepartmentOfficial;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\AuditLogger;
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
        // Validate the incoming request
        $validated = $request->validate([
            'dept_off_name' => 'required|string|max:255',
            'dept_off_designation' => 'nullable|string|max:255',
            'dept_off_phone' => 'nullable|string|max:15',
            'dept_off_role' => 'required|string|max:255',
            'dept_off_emp_id' => 'required|string|max:255',
            'dept_off_email' => 'required|email',
            'dept_off_password' => 'required|string|min:8', // Assuming there's a password confirmation field in the form
            'cropped_image' => 'nullable|string', // If you are dealing with a base64 image
        ]);

        try {
            // Handle image upload (if any)
            $imagePath = null;
            if (!empty($validated['cropped_image'])) {
                // Remove the data URL prefix if present
                $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $validated['cropped_image']);
                $imageData = base64_decode($imageData);

                if ($imageData === false) {
                    throw new \Exception('Base64 decode failed.');
                }

                // Create a unique image name
                $imageName = 'dept_official_' . time() . '.png';
                $imagePath = 'images/dept_officials/' . $imageName;

                // Store the image
                $stored = Storage::disk('public')->put($imagePath, $imageData);

                if (!$stored) {
                    throw new \Exception('Failed to save image to storage.');
                }

                // Compress the image if it exceeds 200 KB
                $fullImagePath = storage_path('app/public/' . $imagePath);
                if (filesize($fullImagePath) > 200 * 1024) {
                    // Assuming you have an image service for compressing the image
                    $this->imageService->saveAndCompressImage($imageData, $fullImagePath, 200);  // 200 KB max size
                }
            }

            // Create a new department official record
            $official = DepartmentOfficial::create([
                'dept_off_name' => $validated['dept_off_name'],
                'dept_off_designation' => $validated['dept_off_designation'],
                'dept_off_phone' => $validated['dept_off_phone'],
                'dept_off_role' => $validated['dept_off_role'],
                'dept_off_emp_id' => $validated['dept_off_emp_id'],
                'dept_off_email' => $validated['dept_off_email'],
                'dept_off_password' => Hash::make($validated['dept_off_password']), // Hashing the password before storing
                'dept_off_createdat' => now(), // Set the current timestamp
                'dept_off_image' => $imagePath, // If no image was uploaded, this will be null
            ]);

            // Log the creation action in the audit log
            AuditLogger::log('Department Official Created', DepartmentOfficial::class, $official->dept_off_emp_id, null, $official->toArray());

            // Redirect with success message
            return redirect()->route('department')->with('success', 'Department official added successfully.');
        } catch (\Exception $e) {
            // Handle any errors during the process
            return redirect()->back()->with('error', 'There was an issue creating the department official: ' . $e->getMessage());
        }
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

        // Validate the incoming request
        $validated = $request->validate([
            'dept_off_name' => 'required|string|max:255',
            'dept_off_emp_id' => 'required|string|max:20',
            'dept_off_designation' => 'nullable|string|max:100',
            'dept_off_role' => 'required|integer',
            'dept_off_phone' => 'nullable|string|max:20',
            'dept_off_email' => 'required|email|max:255',
            'cropped_image' => 'nullable|string', // Base64 encoded string for image
        ]);

        try {
            $newImagePath = null;

            if (!empty($validated['cropped_image'])) {
                $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $validated['cropped_image']);
                $imageData = base64_decode($imageData);

                if ($imageData === false) {
                    throw new \Exception('Base64 decode failed.');
                }

                $imageName = 'dept_official_' . time() . '.jpg';
                $imagePath = 'images/dept_official/' . $imageName;

                Storage::disk('public')->put($imagePath, $imageData);

                // Remove the old image if it exists
                if ($official->dept_off_image && Storage::disk('public')->exists($official->dept_off_image)) {
                    Storage::disk('public')->delete($official->dept_off_image);
                }

                $newImagePath = $imagePath;
            }

            // Prepare data for updating
            $updateData = [
                'dept_off_name' => $validated['dept_off_name'],
                'dept_off_emp_id' => $validated['dept_off_emp_id'],
                'dept_off_designation' => $validated['dept_off_designation'],
                'dept_off_role' => $validated['dept_off_role'],
                'dept_off_phone' => $validated['dept_off_phone'],
                'dept_off_email' => $validated['dept_off_email'],
            ];

            if ($newImagePath) {
                $updateData['dept_off_image'] = $newImagePath;
            }

            // Update the DepartmentOfficial with the new data
            $official->update($updateData);
            // Log the update action in the audit log
            AuditLogger::log('Department Official Updated', DepartmentOfficial::class, $official->dept_off_emp_id, null, $official->toArray());

            // Redirect with success message
            return redirect()->route('department')
                ->with('success', 'Department official updated successfully.');
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
        $roles = Role::findOrFail($official->dept_off_role);

        // Pass the department official data to the view
        return view('masters.department.officials.show', compact('official', 'roles'));
    }
}
