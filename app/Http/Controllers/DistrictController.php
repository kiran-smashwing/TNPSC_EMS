<?php

namespace App\Http\Controllers;

use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Services\AuditLogger;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DistrictController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['login', 'showLoginForm']);
    }

    public function showLoginForm()
    {
        return view('district.login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'district_email' => 'required|email',
            'district_password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $district = District::where('district_email', $request->district_email)->first();

        if (!$district || !Hash::check($request->district_password, $district->district_password)) {
            return back()->withErrors([
                'district_email' => 'Invalid credentials',
            ])->withInput();
        }

        // Log successful login
        AuditLogger::log('District Login', District::class, $district->district_id);

        // Store district data in session
        session(['district_id' => $district->district_id]);

        return redirect()->route('district.dashboard');
    }

    public function index()
    {
        $districts = District::all();
        return view('masters.district.collectorate.index', compact('districts'));
    }

    public function create()
    {
        return view('masters.district.collectorate.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'district_name' => 'required|string|max:255',
            'district_code' => 'required|numeric|unique:district',
            'mail' => 'required|email',
            'phone' => 'required|string',
            'alternate_phone' => 'nullable|string',
            'password' => 'required|string|min:6',
            'website' => 'required|url',
            'address' => 'required|string',
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            // Handle image upload if present
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('districts', 'public');
                $validated['image'] = $imagePath;
            }

            // Hash password
            $validated['district_password'] = Hash::make($validated['password']);

            // Map the fields to match your database columns
            $district = District::create([
                'district_name' => $validated['district_name'],
                'district_code' => $validated['district_code'],
                'district_email' => $validated['mail'],
                'district_phone' => $validated['phone'],
                'district_alternate_phone' => $validated['alternate_phone'],
                'district_password' => $validated['district_password'],
                'district_website' => $validated['website'],
                'district_address' => $validated['address'],
                'district_longitude' => $validated['longitude'],
                'district_latitude' => $validated['latitude'],
                'district_image' => $validated['image'] ?? null
            ]);

            // Log district creation with new values
            AuditLogger::log('District Created', District::class, $district->district_id, null, $district->toArray());


            return redirect()->route('district.index')
                ->with('success', 'District created successfully');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error creating district: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $district = District::findOrFail($id);
        return view('masters.district.collectorate.edit', compact('district'));
    }

    public function update(Request $request, $id)
    {
        $district = District::findOrFail($id);

        $validated = $request->validate([
            'district_name' => 'required|string|max:255',
            'district_code' => 'required|numeric|unique:district,district_code,' . $id . ',district_id',
            'mail' => 'required|email',
            'phone' => 'required|string',
            'alternate_phone' => 'nullable|string',
            'password' => 'nullable|string|min:6',
            'website' => 'required|url',
            'address' => 'required|string',
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric',
        ]);

        try {
            // Handle image if updated
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($district->district_image) {
                    Storage::disk('public')->delete($district->district_image);
                }
                $validated['district_image'] = $request->file('image')->store('districts', 'public');
            }

            // Only update password if provided
            if ($request->filled('password')) {
                $validated['district_password'] = Hash::make($validated['password']);
            }
            // Get the old values before updating
            $oldValues = $district->getOriginal();
            // Map validated data to district columns
            $district->update([
                'district_name' => $validated['district_name'],
                'district_code' => $validated['district_code'],
                'district_email' => $validated['mail'],
                'district_phone' => $validated['phone'],
                'district_alternate_phone' => $validated['alternate_phone'],
                'district_website' => $validated['website'],
                'district_address' => $validated['address'],
                'district_longitude' => $validated['longitude'],
                'district_latitude' => $validated['latitude'],
                'district_password' => $validated['district_password'] ?? $district->district_password,
                'district_image' => $validated['district_image'] ?? $district->district_image,
            ]);
            // Get the changed values
            $changedValues = $district->getChanges();

            // Filter old values to only include fields that have changed
            $oldValues = array_intersect_key($oldValues, $changedValues);

            // Log district update with old and new values
            AuditLogger::log('District Updated', District::class, $district->district_id, $oldValues, $changedValues);

            return redirect()->route('district.index')
                ->with('success', 'District updated successfully');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error updating district: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $district = District::findOrFail($id);

        // Log view action
        AuditLogger::log('District Viewed', District::class, $district->district_id);

        return view('masters.district.collectorate.show', compact('district'));
    }

    public function destroy($id)
    {
        $district = District::findOrFail($id);

        // Log district deletion
        AuditLogger::log('District Deleted', District::class, $district->district_id);

        $district->delete();

        return redirect()->route('district.index')
            ->with('success', 'District deleted successfully');
    }

    public function logout(Request $request)
    {
        $district_id = session('district_id');

        // Log logout
        if ($district_id) {
            AuditLogger::log('District Logout', District::class, $district_id);
        }

        $request->session()->forget('district_id');

        return redirect()->route('district.login');
    }
}