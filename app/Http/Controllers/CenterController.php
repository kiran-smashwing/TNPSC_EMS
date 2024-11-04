<?php

namespace App\Http\Controllers;

use App\Models\Center;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CenterController extends Controller
{
    public function __construct()
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth');
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
        return view('masters.district.centers.create' , compact('districts'));
    }

    

    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'center_district_id' => 'required|integer',
            'center_name' => 'required|string|max:255',
            'district_code' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        dd($request->all());
        // Store image if it was uploaded
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('centers', 'public');
        }

        // Create a new Center entry
        Center::create([
            'center_district_id' => $request->district_id,
            'center_name' => $request->center_name,
            'district_code' => $request->district_code,
            'image_path' => $imagePath,
        ]);

        // Redirect with success message
        return redirect()->route('center.index')->with('success', 'Center added successfully.');
    }
    


    public function show(Center $center)
    {
        return view('masters.district.centers.show', compact('center'));
    }

    public function edit(Center $center)
    {
        $districts = District::all();
        return view('masters.district.centers.edit', compact('center', 'districts'));
    }

    public function update(Request $request, Center $center)
    {
        $validated = $request->validate([
            'image' => 'nullable|image|max:2048',
            'center_name' => 'required|unique:centers,center_name,' . $center->id . '|max:100', // Updated table name
            'center_code' => 'required|unique:centers,center_code,' . $center->id . '|max:20', // Updated table name
            'district_id' => 'required|exists:collectorate,id',
            'status' => 'required|in:Active,Inactive',
        ]);
    
        if ($request->hasFile('image')) {
            if ($center->image) {
                Storage::disk('public')->delete($center->image);
            }
            $imagePath = $request->file('image')->store('center_images', 'public');
            $validated['image'] = $imagePath;
        }
    
        $validated['updated_by'] = auth()->user()->name;
    
        $center->update($validated);
    
        return redirect()->route('centers.index')->with('success', 'Center updated successfully.');
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