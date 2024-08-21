<?php

namespace App\Http\Controllers;

use App\Models\TreasuryOfficer;
use App\Models\Collectorate;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TreasuryOfficersController extends Controller
{
    public function __construct()
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth');
    }

    public function index(Request $request)
    {

        $query = TreasuryOfficer::query();

    // Apply filters if they are set
    if ($request->has('role') && !empty($request->role)) {
        $query->where('role', $request->role);
    }
    if ($request->has('district') && !empty($request->district)) {
        $query->where('district', $request->district);
    }
    if ($request->has('centerCode') && !empty($request->centerCode)) {
        $query->where('center_code', $request->centerCode);
    }
        $treasuryOfficers = TreasuryOfficer::all();
        return view('masters.district.treasury_officers.index', compact('treasuryOfficers'));
    }

   

    public function create()
    {
        $districts = Collectorate::all();
        return view('masters.district.treasury_officers.create', compact('districts'));
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'image' => 'required|image|max:2048',
            'name' => 'required|string|max:100',
            'employee_id' => 'required|string|max:20',
            'role' => 'required|string|max:50',
            'district_name' => 'required|exists:collectorate,district_name',
            'mail' => 'required|email|unique:treasury_officer,mail',
            'phone' => 'required|string|max:20|unique:treasury_officer,phone',
            'password' => 'required|string|min:6',
            'status' => 'required|in:Active,Inactive',
        ]);

        // $validatedData['image'] = file_get_contents($request->file('image')->store('center_images', 'public'));
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('center_images', 'public');
            $validatedData['image'] = $imagePath;
        }
        $validatedData['password'] = bcrypt($validatedData['password']);
        $validatedData['created_by'] = auth()->user()->name;

        TreasuryOfficer::create($validatedData);

        return redirect()->route('treasury_officers.index')->with('success', 'Treasury Officer created successfully.');
    }

    

    public function edit()
    {
        $districts = Collectorate::all();
        return view('masters.district.treasury_officers.edit', compact('treasuryOfficer', 'districts'));
    }
    public function update(Request $request, TreasuryOfficer $treasuryOfficer)
    {
        $validatedData = $request->validate([
            'image' => 'nullable|image|max:2048',
            'name' => 'required|string|max:100',
            'employee_id' => 'required|string|max:20',
            'role' => 'required|string|max:50',
            'district_name' => 'required|exists:collectorate,district_name',
            'mail' => 'required|email|unique:treasury_officer,mail,' . $treasuryOfficer->id,
            'phone' => 'required|string|max:20|unique:treasury_officer,phone,' . $treasuryOfficer->id,
            'status' => 'required|in:Active,Inactive',
        ]);

        if ($request->hasFile('image')) {
            $validatedData['image'] = file_get_contents($request->file('image')->getRealPath());
        }

        $validatedData['updated_by'] = auth()->user()->name;

        $treasuryOfficer->update($validatedData);

        return redirect()->route('treasury_officers.index')->with('success', 'Treasury Officer updated successfully.');
    }
    
    public function destroy(TreasuryOfficer $treasuryOfficer)
    {
        $treasuryOfficer->delete();
        return redirect()->route('treasury-officers.index')->with('success', 'Treasury Officer deleted successfully.');
    }
    
}