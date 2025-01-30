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
        $roles = Role::with('department_officer')->get(); // Retrieve all roles from the database
        return view('masters.department.roles.index', compact('roles'));
    }

    public function create()
    {
        $departmentOfficials = DepartmentOfficial::all();
        return view('masters.department.roles.create', compact('departmentOfficials'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'role_name' => 'required|string|max:255',
            'role_department' => 'required|string|max:255',
            'department_officer' => 'required|numeric|exists:department_officer,dept_off_id',
        ]);

        try {
            // Check if role with the same department exists
            $existingRole = Role::where('role_department', $request->role_department)
                ->where('role_name', $request->role_name)
                ->first();

            if ($existingRole) {
                $roleId = $existingRole->role_id;
            } else {
                // Create new role if it doesn't exist
                $newRole = Role::create([
                    'role_name' => $request->role_name,
                    'role_department' => $request->role_department,
                    'role_createdat' => now(),
                ]);
                $roleId = $newRole->role_id;

                // Log the creation of new role
                AuditLogger::log('Role Created', Role::class, $roleId, null, $newRole->toArray());
            }

            // Update department officer with the role
            $departmentOfficial = DepartmentOfficial::findOrFail($request->department_officer);
            $departmentOfficial->dept_off_role = $roleId;
            $departmentOfficial->save();

            // Log the role assignment
            AuditLogger::log('Role Assigned', DepartmentOfficial::class, $departmentOfficial->dept_off_id, null, $departmentOfficial->toArray());

            return redirect()->route('role')->with('success', 'Role created and assigned successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'There was an issue creating/assigning the role: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $departmentOfficials = DepartmentOfficial::all();
        return view('masters.department.roles.edit', compact('role', 'departmentOfficials'));
    }

    public function update(Request $request, $id)
    {
        try {
            // Validate the incoming request data
            $request->validate([
                'role_department' => 'required|string|max:255',
                'role_name' => 'required|string|max:255',
                'department_officer' => 'required|numeric|exists:department_officer,dept_off_id',
            ]);

            // Find the current department officer with this role_id
            $currentOfficer = DepartmentOfficial::where('dept_off_role', $id)->first();
            if ($currentOfficer) {
                // Remove role assignment from current officer
                $currentOfficer->dept_off_role = null;
                $currentOfficer->save();
                // Log the role removal
                AuditLogger::log('Role Removed', DepartmentOfficial::class, $currentOfficer->dept_off_id, null, $currentOfficer->toArray());
            }

            // Assign role to new officer
            $newOfficer = DepartmentOfficial::findOrFail($request->department_officer);
            $newOfficer->dept_off_role = $id;
            $newOfficer->save();

            // Log the role reassignment
            AuditLogger::log('Role Reassigned', DepartmentOfficial::class, $newOfficer->dept_off_id, null, $newOfficer->toArray());

            return redirect()->route('role')->with('success', 'Role reassigned successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'There was an issue reassigning the role: ' . $e->getMessage());
        }
    }




    // public function destroy(Designation $designation)
    // {
    //     $designation->delete();
    //     return redirect()->route('designations.index')->with('success', 'Designation deleted successfully.');
    // }
}
