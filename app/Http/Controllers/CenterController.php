<?php

namespace App\Http\Controllers;

use App\Models\Center;
use App\Models\Collectorate;
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
        $districts = Collectorate::all();
        return view('masters.district.centers.create', compact('districts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'image' => 'nullable|image|max:2048',
            'center_name' => 'required|unique:center,center_name|max:100',
            'center_code' => 'required|unique:center,center_code|max:20',
            'district_id' => 'required|exists:collectorate,id',
            'status' => 'required|in:Active,Inactive',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('center_images', 'public');
            $validated['image'] = $imagePath;
        }

        $validated['created_by'] = auth()->user()->name;
        $validated['updated_by'] = auth()->user()->name;

        Center::create($validated);

        return redirect()->route('centers.index')->with('success', 'Center created successfully.');
    }

    public function show(Center $center)
    {
        return view('masters.district.centers.show', compact('center'));
    }

    public function edit(Center $center)
    {
        $districts = Collectorate::all();
        return view('masters.district.centers.edit', compact('center', 'districts'));
    }

    public function update(Request $request, Center $center)
    {
        $validated = $request->validate([
            'image' => 'nullable|image|max:2048',
            'center_name' => 'required|unique:center,center_name,' . $center->id . '|max:100',
            'center_code' => 'required|unique:center,center_code,' . $center->id . '|max:20',
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