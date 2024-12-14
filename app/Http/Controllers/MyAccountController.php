<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Center;
use App\Models\TreasuryOfficer;
use App\Models\MobileTeamStaffs;
use App\Models\Venues;
use App\Models\DepartmentOfficial;
use App\Models\ChiefInvigilator;

class MyAccountController extends Controller
{
    public function __construct()
    {
        // Apply the auth middleware to the entire controller
        $this->middleware('auth.multi');
    }

    public function index()
    {
        // Get the role and user ID from the session
        $role = session('auth_role'); // Role stored in session as 'auth_role'
        $userId = session('auth_id'); // User ID stored in session as 'auth_id'
        if ($role == 'district') {
            $district = District::findOrFail($userId);
            return view('user.my-account', compact('district','role'));
        }
        else if ($role == 'center') {
            $center = Center::findOrFail($userId); // Retrieves the center by its ID
            $districts = District::all(); // Fetch all districts
            return view('user.my-account', compact('role','center','districts'));

        }
        elseif ($role == 'treasury') {
            $treasuryOfficer = TreasuryOfficer::findOrFail($userId);
            $districts = District::all(); // Fetch all districts
            return view('user.my-account', compact('role','treasuryOfficer','districts'));
        }
        elseif ($role =='mobile_team_staffs') {
            $mobileTeamStaff = MobileTeamStaffs::findOrFail($userId);
            $districts = District::all(); // Fetch all districts
            $team = MobileTeamStaffs::with('district')->findOrFail($userId);
            return view('user.my-account', compact('mobileTeamStaff','role', 'districts','team'));
        }
        elseif ($role == 'venue') {
            $districts = District::all(); // Retrieve all districts
            $centers = Center::all(); // Retrieve all centers
            $venue = Venues::with(['district', 'center'])->findOrFail($userId);
            return view('user.my-account', compact('venue', 'districts', 'centers','role'));
        }
        elseif ($role == 'mobile_team_staffs'){  

         }
        elseif ($role == 'headquarters') {
            $official = DepartmentOfficial::findOrFail($userId);
            $roles_role = Role::findOrFail($official->dept_off_role);
            $roles = Role::all();
    
            return view('user.my-account', compact('official', 'roles','roles_role','role'));
        }
        elseif ($role == 'ci') {
            $venues = Venues::all(); // Retrieve all venues
            $centers = Center::all(); // Retrieve all centers
            $districts = District::all(); // Retrieve all districts
            $chiefInvigilator = ChiefInvigilator::findOrFail($userId); // Retrieve the specific Chief Invigilator
    
            return view('user.my-account', compact('chiefInvigilator', 'venues', 'centers', 'districts','role')); 
        }

      
        

       

        // Pass user details and role to the view
        return view('user.my-account', compact('role'));
    }

    /**
     * Get user details based on role and user ID.
     */
    
}
