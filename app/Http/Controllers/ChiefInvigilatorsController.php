<?php

namespace App\Http\Controllers;

use App\Models\ChiefInvigilator;
use App\Models\Center;
use App\Models\District;
use App\Models\Venues;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Services\AuditLogger;
use Illuminate\Support\Facades\Storage;
use App\Services\ImageCompressService;

class ChiefInvigilatorsController extends Controller
{
    protected $imageService;

    public function __construct(ImageCompressService $imageService)
    {
        $this->middleware('auth.multi');
        $this->imageService = $imageService;
    }


    public function index()
    {
        $chiefInvigilator = ChiefInvigilator::all();
        return view('masters.venues.chief_invigilator.index', compact('chiefInvigilator'));
    }

    public function create()
    {
        $venues = Venues::all(); // Retrieve all venues
        $centers = Center::all(); // Retrieve all centers
        $districts = District::all(); // Retrieve all districts

        return view('masters.venues.chief_invigilator.create', compact('venues', 'centers', 'districts'));
    }


    public function show()
    {
        return view('masters.venues.chief_invigilator.show');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ci_district_id' => 'required|string|max:50',
            'ci_center_id' => 'required|string|max:50',
            'ci_venue_id' => 'required|string|max:50',
            'ci_name' => 'required|string|max:255',
            'ci_email' => 'required|email|unique:cheif_invigilator',
            'ci_employee_id' => 'required|string|max:255',
            'ci_phone' => 'required|string|max:15',
            'ci_alternative_phone' => 'nullable|string|max:15',
            'ci_designation' => 'required|string|max:100',
            'cropped_image' => 'nullable|string',
            'ci_password' => 'required|string|min:6',
        ]);

        // $validated['ci_password'] = Hash::make($validated['ci_password']);

        try {
            if (!empty($validated['cropped_image'])) {
                // Remove the data URL prefix if present
                $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $validated['cropped_image']);
                $imageData = base64_decode($imageData);

                if ($imageData === false) {
                    throw new \Exception('Base64 decode failed.');
                }

                // Create a unique image name
                $imageName = 'tre_off_' . time() . '.png';
                $imagePath = 'images/chiefinvigilator/' . $imageName;

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

                $validated['image'] = $imagePath;
            }
            $chiefInvigilator = ChiefInvigilator::create([
                'ci_district_id' => $validated['ci_district_id'],
                'ci_center_id' => $validated['ci_center_id'],
                'ci_venue_id' => $validated['ci_venue_id'],
                'ci_name' => $validated['ci_name'],
                'ci_designation' => $validated['ci_designation'],
                'ci_phone' => $validated['ci_phone'],
                'ci_id' => $validated['ci_employee_id'],
                'ci_email' => $validated['ci_email'],
                'ci_password' => Hash::make($validated['ci_password']), // Hashing the password before storing
                'ci_image' => $validated['image'] ?? null, // Handle image path, set to null if not provided
            ]);


            AuditLogger::log('Chief Invigilator Created', ChiefInvigilator::class, $chiefInvigilator->ci_id);

            return redirect()->route('chief-invigilator')
                ->with('success', 'Chief Invigilator created successfully');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error creating Chief Invigilator: ' . $e->getMessage());
        }
    }

    public function edit($id)
{
    $venues = Venues::all(); // Retrieve all venues
    $centers = Center::all(); // Retrieve all centers
    $districts = District::all(); // Retrieve all districts
    $chiefInvigilator = ChiefInvigilator::findOrFail($id); // Retrieve the specific Chief Invigilator

    return view('masters.venues.chief_invigilator.edit', compact('chiefInvigilator', 'venues', 'centers', 'districts'));
}



public function update(Request $request, $id)
{
    $chiefInvigilator = ChiefInvigilator::findOrFail($id);

    $validated = $request->validate([
        'ci_district_id' => 'required|string|max:50',
        'ci_center_id' => 'required|string|max:50',
        'ci_venue_id' => 'required|string|max:50',
        'ci_name' => 'required|string|max:255',
        'ci_email' => 'required|email', // Unique except for the current record
        'ci_phone' => 'required|string|max:15',
        'ci_alternative_phone' => 'nullable|string|max:15',
        'ci_designation' => 'required|string|max:100',
        'ci_password' => 'nullable|string|min:6',
        'cropped_image' => 'nullable|string',
    ]);

    try {
        // Hash the password if provided
        if ($request->filled('ci_password')) {
            $validated['ci_password'] = Hash::make($validated['ci_password']);
        }

        // Handle the image if provided
        if (!empty($validated['cropped_image'])) {
            // Remove the data URL prefix if present
            $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $validated['cropped_image']);
            $imageData = base64_decode($imageData);

            if ($imageData === false) {
                throw new \Exception('Base64 decode failed.');
            }

            // Create a unique image name
            $imageName = 'tre_off_' . time() . '.png';
            $imagePath = 'images/chiefinvigilator/' . $imageName;

            // Store the image
            $stored = Storage::disk('public')->put($imagePath, $imageData);

            if (!$stored) {
                throw new \Exception('Failed to save image to storage.');
            }

            // Compress the image if it exceeds 200 KB
            $fullImagePath = storage_path('app/public/' . $imagePath);
            if (filesize($fullImagePath) > 200 * 1024) {
                $this->imageService->saveAndCompressImage($imageData, $fullImagePath, 200); // 200 KB max size
            }

            // Save the image path
            $validated['ci_image'] = $imagePath;

            // Optionally delete the old image
            if ($chiefInvigilator->ci_image) {
                Storage::disk('public')->delete($chiefInvigilator->ci_image);
            }
        }

        $oldValues = $chiefInvigilator->getOriginal(); // Store original values for logging

        // Update the Chief Invigilator
        $chiefInvigilator->update($validated);

        // Log the changes
        AuditLogger::log(
            'Chief Invigilator Updated',
            ChiefInvigilator::class,
            $chiefInvigilator->ci_id,
            $oldValues,
            $chiefInvigilator->getChanges()
        );

        return redirect()->route('chief-invigilator')
            ->with('success', 'Chief Invigilator updated successfully');
    } catch (\Exception $e) {
        return back()->withInput()
            ->with('error', 'Error updating Chief Invigilator: ' . $e->getMessage());
    }
}


    public function destroy($id)
    {
        $chiefInvigilator = ChiefInvigilator::findOrFail($id);

        AuditLogger::log('Chief Invigilator Deleted', ChiefInvigilator::class, $chiefInvigilator->ci_id);

        $chiefInvigilator->delete();

        return redirect()->route('chief-invigilator')
            ->with('success', 'Chief Invigilator deleted successfully');
    }

    public function logout(Request $request)
    {
        $ci_id = session('ci_id');

        if ($ci_id) {
            AuditLogger::log('Chief Invigilator Logout', ChiefInvigilator::class, $ci_id);
        }

        $request->session()->forget('ci_id');

        return redirect()->route('chief-invigilator');
    }
}
