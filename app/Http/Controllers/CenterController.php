<?php

namespace App\Http\Controllers;

use App\Models\Center;
use App\Models\District;
use Illuminate\Http\Request;
use App\Services\ImageCompressService;
use Illuminate\Support\Facades\Storage;

class CenterController extends Controller
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
        // Fetch centers with the district relationship, paginate the result
        $centers = Center::with('district')->paginate(10);

        // Return the view with the centers data
        return view('masters.district.centers.index', compact('centers'));
    }

    public function create()
    {
        $districts = District::all();
        //  dd($districts);
        return view('masters.district.centers.create', compact('districts'));
    }



    public function store(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'district_id' => 'required|integer',
            'center_name' => 'required|string|max:255',
            'district_code' => 'required|integer',
            'cropped_image' => 'nullable|string',
        ]);

        if (!empty($validated['cropped_image'])) {
            // Remove the data URL prefix if present
            $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $validated['cropped_image']);
            $imageData = base64_decode($imageData);

            if ($imageData === false) {
                throw new \Exception('Base64 decode failed.');
            }

            // Create a unique image name
            $imageName = 'center_' . time() . '.png';
            $imagePath = 'images/center/' . $imageName;

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
        // Create a new center record
        Center::create([
            'center_district_id' => $validated['district_id'],
            'center_name' => $validated['center_name'],
            'center_code' => $validated['district_code'],
            'center_image' => $imagePath, // This will be null if no image was uploaded
        ]);

        // Redirect with success message
        return redirect()->route('center')->with('success', 'Center added successfully.');
    }






    public function show(Center $center)
    {
        return view('masters.district.centers.show', compact('center'));
    }

    public function edit($center_id)
    {
        $center = Center::findOrFail($center_id); // Retrieves the center by its ID
        $districts = District::all(); // Fetch all districts
        return view('masters.district.centers.edit', compact('center', 'districts'));
    }

    public function update(Request $request, $id)
    {
        $center = Center::findOrFail($id);
    
        $validated = $request->validate([
            'center_name' => 'required|string|max:255|unique:centers,center_name,' . $id . ',center_id',
            'center_code' => 'required|string|max:20|unique:centers,center_code,' . $id . ',center_id',
            'district_id' => 'required|integer',
            'cropped_image' => 'nullable|string', // Base64 encoded string
        ]);
    
        try {
            $newImagePath = null;
    
            if (!empty($validated['cropped_image'])) {
                $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $validated['cropped_image']);
                $imageData = base64_decode($imageData);
    
                if ($imageData === false) {
                    throw new \Exception('Base64 decode failed.');
                }
    
                $imageName = 'center_' . time() . '.jpg';
                $imagePath = 'images/centers/' . $imageName;
    
                Storage::disk('public')->put($imagePath, $imageData);
    
                if ($center->center_image && Storage::disk('public')->exists($center->center_image)) {
                    Storage::disk('public')->delete($center->center_image);
                }
    
                $newImagePath = $imagePath;
            }
    
            $updateData = [
                'center_name' => $validated['center_name'],
                'center_code' => $validated['center_code'],
                'center_district_id' => $validated['district_id'],
            ];
    
            if ($newImagePath) {
                $updateData['center_image'] = $newImagePath;
            }
    
            $center->update($updateData);
    
            return redirect()->route('center')
                ->with('success', 'Center updated successfully');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error updating center: ' . $e->getMessage());
        }
    }
    




    public function destroy(Center $center)
    {
        if ($center->image) {
            Storage::disk('public')->delete($center->image);
        }
        $center->delete();
        return redirect()->route('centers.index')->with('success', 'Center deleted successfully.');
    }
}
