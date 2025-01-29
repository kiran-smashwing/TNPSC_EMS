<?php

namespace App\Http\Controllers;

use App\Models\DepartmentOfficial;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Services\AuditLogger;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all(); // Retrieve all roles from the database
        return view('masters.department.roles.index', compact('roles' ));
    }

    public function create()
    {
        $departmentOfficials = DepartmentOfficial::all();
        return view('masters.department.roles.create',compact('departmentOfficials'));
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'role_name' => 'required|string|max:255',
            'role_department' => 'required|string|max:255',
            'department_officer'=> 'required|numeric|exists:department_officer,dept_off_id',
        ]);
        try {
            // update role to department officer
            $departmentOfficial = DepartmentOfficial::findOrFail($request->department_officer);
            $departmentOfficial->dept_off_department = $request->role_department;
            $departmentOfficial->dept_off_role = $request->role_name;
            $departmentOfficial->save();
    
            // Log the role assigned action in the audit log with the role and department name.
            AuditLogger::log('Role Assigned', DepartmentOfficial::class, $departmentOfficial->dept_off_id, null, $departmentOfficial->toArray());

            // Redirect with success message
            return redirect()->route('role')->with('success', 'Role created successfully.');
        } catch (\Exception $e) {
            // Send the error message to the session and show it in the view
            return redirect()->back()->with('error', 'There was an issue creating the role: ' . $e->getMessage());
        }
    }



    public function edit($id)
    {
        $role = Role::findOrFail($id);
        return view('masters.department.roles.edit', compact('role'));
    }

    public function update(Request $request, $id)
    {
        try {
            // Find the role by ID (this will automatically return 404 if the role is not found)
            $role = Role::findOrFail($id);

            // Validate the incoming request data
            $request->validate([
                'role_department' => 'required|string|max:255',
                'role_name' => 'required|string|max:255',
            ]);

            // Update the role with new data
            $role->update([
                'role_department' => $request->role_department,
                'role_name' => $request->role_name,
            ]);

            // Log the update action in the audit log
            AuditLogger::log('Role Updated', Role::class, $role->role_id, null, $role->toArray());

            // Redirect with success message
            return redirect()->route('role')->with('success', 'Role updated successfully.');
        } catch (\Exception $e) {
            // Send the error message to the session and show it in the view
            return redirect()->back()->with('error', 'There was an issue updating the role: ' . $e->getMessage());
        }
    }





    // public function destroy(Designation $designation)
    // {
    //     $designation->delete();
    //     return redirect()->route('designations.index')->with('success', 'Designation deleted successfully.');
    // }
}
