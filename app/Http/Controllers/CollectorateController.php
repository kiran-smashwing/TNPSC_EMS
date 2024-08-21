<?php

namespace App\Http\Controllers;

use App\Models\Collectorate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class CollectorateController extends Controller
{
    public function index()
    {
        return view('masters.district.collectorate.index');
    }

    public function create()
    {
        return view('masters.district.collectorate.create');
    }
    public function edit()
    {
        return view('masters.district.collectorate.edit');
    }
    public function show()
    {
        return view('masters.district.collectorate.show');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'image' => 'nullable|image|max:2048',
            'district_name' => 'required|unique:collectorate,district_name|max:100',
            'district_code' => 'required|unique:collectorate,district_code|max:20',
            'address' => 'required',
            'mail' => 'required|email|unique:collectorate,mail|max:100',
            'website' => 'nullable|url|max:100',
            'mail_verify_status' => 'boolean',
            'phone' => 'required|unique:collectorate,phone|max:20',
            'alternate_phone' => 'nullable|max:20',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'password' => 'required|min:8',
            'status' => 'required|in:Active,Inactive',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('collectorate_images', 'public');
            $validated['image'] = $imagePath;
        }

        $validated['password'] = Hash::make($validated['password']);
        $validated['created_by'] = auth()->user()->name;
        $validated['updated_by'] = auth()->user()->name;

        Collectorate::create($validated);

        return redirect()->route('collectorates.index')->with('success', 'Collectorate created successfully.');
    }

    // public function show(Collectorate $collectorate)
    // {
    //     return view('masters.district.collectorate.show', compact('collectorate'));
    // }

    // public function edit(Collectorate $collectorate)
    // {
    //     return view('masters.district.collectorate.edit', compact('collectorate'));
    // }

    public function update(Request $request, Collectorate $collectorate)
    {
        $validated = $request->validate([
            'image' => 'nullable|image|max:2048',
            'district_name' => 'required|unique:collectorate,district_name,' . $collectorate->id . '|max:100',
            'district_code' => 'required|unique:collectorate,district_code,' . $collectorate->id . '|max:20',
            'address' => 'required',
            'mail' => 'required|email|unique:collectorate,mail,' . $collectorate->id . '|max:100',
            'website' => 'nullable|url|max:100',
            'mail_verify_status' => 'boolean',
            'phone' => 'required|unique:collectorate,phone,' . $collectorate->id . '|max:20',
            'alternate_phone' => 'nullable|max:20',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'password' => 'nullable|min:8',
            'status' => 'required|in:Active,Inactive',
        ]);

        if ($request->hasFile('image')) {
            if ($collectorate->image) {
                Storage::disk('public')->delete($collectorate->image);
            }
            $imagePath = $request->file('image')->store('collectorate_images', 'public');
            $validated['image'] = $imagePath;
        }

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['updated_by'] = auth()->user()->name;

        $collectorate->update($validated);

        return redirect()->route('collectorates.index')->with('success', 'Collectorate updated successfully.');
    }

    public function destroy(Collectorate $collectorate)
    {
        if ($collectorate->image) {
            Storage::disk('public')->delete($collectorate->image);
        }
        $collectorate->delete();
        return redirect()->route('collectorates.index')->with('success', 'Collectorate deleted successfully.');
    }
}