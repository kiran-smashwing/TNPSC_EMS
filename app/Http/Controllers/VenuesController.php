<?php

namespace App\Http\Controllers;

use App\Models\Venues;
use App\Models\Center;
use App\Models\District;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\AuditLogger;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Services\ImageCompressService;

class VenuesController extends Controller
{
    protected $imageService;
    public function __construct(ImageCompressService $imageService)
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth');
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
            'venue_name' => 'required|string|max:255',
            'venue_code' => 'required|string|max:50',
            'venue_email' => 'nullable|email',
            'venue_phone' => 'required|string|max:20',
            'venue_alternative_phone' => 'nullable|string|max:20',
            'venue_website' => 'nullable|url',
            'venue_address' => 'required|string',
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

                $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $validated['cropped_image']);
                $imageData = base64_decode($imageData);

                if ($imageData === false) {
                    throw new \Exception('Base64 decode failed.');
                }

                $imageName = 'venue_' . time() . '.png';
                $imagePath = 'images/venues/' . $imageName;

                $stored = Storage::disk('public')->put($imagePath, $imageData);

                if (!$stored) {
                    throw new \Exception('Failed to save image to storage.');
                }

                $fullImagePath = storage_path('app/public/' . $imagePath);
                if (filesize($fullImagePath) > 200 * 1024) {
                    $this->imageService->saveAndCompressImage($imageData, $fullImagePath, 200); // 200 KB max size
                }

                $validated['venue_image'] = $imagePath; // Add the image path to the validated array
            }

            // Create the venue with bank details
            $venue = Venues::create([
                'venue_district_id' => $validated['district_id'],
                'venue_center_id' => $validated['center_id'],
                'venue_name' => $validated['venue_name'],
                'venue_code' => $validated['venue_code'],
                'venue_email' => $validated['venue_email'],
                'venue_phone' => $validated['venue_phone'],
                'venue_alternative_phone' => $validated['venue_alternative_phone'],
                'venue_website' => $validated['venue_website'],
                'venue_address' => $validated['venue_address'],
                'venue_longitude' => $validated['venue_longitude'],
                'venue_latitude' => $validated['venue_latitude'],
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


    public function edit($id)
    {
        $venue = Venues::with(['district', 'center'])->findOrFail($id);
        return view('masters.venues.venue.edit', compact('venue'));
    }
    public function show()
    {

        return view('masters.venues.venue.show');
    }
}
