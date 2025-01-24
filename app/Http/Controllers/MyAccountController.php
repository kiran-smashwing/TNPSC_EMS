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
            $centerCount = $district->centers()->count();  // Assuming 'centers' is a relationship in District model
            $venueCount = $district->venues()->count();    // Assuming 'venues' is a relationship in District model
            $staffCount = $district->treasuryOfficers()->count() + $district->mobileTeamStaffs()->count();
            return view('user.my-account', compact('district', 'role', 'centerCount', 'venueCount', 'staffCount'));
        } else if ($role == 'center') {
            $center = Center::findOrFail($userId); // Retrieves the center by its ID
            $districts = District::all(); // Fetch all districts
            $centerCount = $center->district->centers()->count();  // Assuming 'centers' is a relationship in District model
            $venueCount = $center->district->venues()->count();
            $staffCount = $center->district->treasuryOfficers()->count() + $center->district->mobileTeamStaffs()->count();
            return view('user.my-account', compact('role', 'center', 'districts', 'centerCount', 'venueCount', 'staffCount'));
        } elseif ($role == 'treasury') {
            $treasuryOfficer = TreasuryOfficer::findOrFail($userId);
            $centerCount = $treasuryOfficer->district->centers()->count();  // Assuming 'centers' is a relationship in District model
            $venueCount = $treasuryOfficer->district->venues()->count();
            $staffCount = $treasuryOfficer->district->treasuryOfficers()->count() + $treasuryOfficer->district->mobileTeamStaffs()->count();
            $districts = District::all(); // Fetch all districts
            return view('user.my-account', compact('role', 'treasuryOfficer', 'districts', 'centerCount', 'venueCount', 'staffCount'));
        } elseif ($role == 'mobile_team_staffs') {
            $mobileTeamStaff = MobileTeamStaffs::findOrFail($userId);
            $districts = District::all(); // Fetch all districts
            $centerCount = $mobileTeamStaff->district->centers()->count();  // Assuming 'centers' is a relationship in District model
            $venueCount = $mobileTeamStaff->district->venues()->count();
            $staffCount = $mobileTeamStaff->district->treasuryOfficers()->count() + $mobileTeamStaff->district->mobileTeamStaffs()->count();
            $team = MobileTeamStaffs::with('district')->findOrFail($userId);
            return view('user.my-account', compact('mobileTeamStaff', 'role', 'districts', 'team','centerCount', 'venueCount','staffCount'));
        } elseif ($role == 'venue') {
            $districts = District::all(); // Retrieve all districts
            $centers = Center::all(); // Retrieve all centers
            $venue = Venues::with(['district', 'center'])->findOrFail($userId);
            $centerCount = $venue->district->centers()->count();  // Assuming 'centers' is a relationship in District model
            $venueCount = $venue->district->venues()->count();
            $staffCount = $venue->district->treasuryOfficers()->count() + $venue->district->mobileTeamStaffs()->count();
            $ci_count = $venue->chiefinvigilator()->count();
            $invigilator_count = $venue->invigilator()->count();
            $cia_count = $venue->cia()->count();
            return view('user.my-account', compact('venue', 'districts', 'centers', 'role','centerCount', 'venueCount','staffCount','ci_count','invigilator_count','cia_count'));
        } elseif ($role == 'mobile_team_staffs') {
        } elseif ($role == 'headquarters') {
            $official = DepartmentOfficial::findOrFail($userId);
            $roles_role = Role::findOrFail($official->dept_off_role);
            $roles = Role::all();

            return view('user.my-account', compact('official', 'roles', 'roles_role', 'role'));
        } elseif ($role == 'ci') {
            $venues = Venues::all(); // Retrieve all venues
            $centers = Center::all(); // Retrieve all centers
            $districts = District::all(); // Retrieve all districts
            $chiefInvigilator = ChiefInvigilator::findOrFail($userId); // Retrieve the specific Chief Invigilator
            $centerCount = $chiefInvigilator->district->centers()->count();  // Assuming 'centers' is a relationship in District model
            $venueCount = $chiefInvigilator->district->venues()->count();
            $staffCount = $chiefInvigilator->district->treasuryOfficers()->count() + $chiefInvigilator->district->mobileTeamStaffs()->count();
            $ci_count = $chiefInvigilator->venue->chiefinvigilator()->count();
            $invigilator_count = $chiefInvigilator->venue->invigilator()->count();
            $cia_count = $chiefInvigilator->venue->cia()->count();

            return view('user.my-account', compact('chiefInvigilator', 'venues', 'centers', 'districts', 'role', 'centerCount', 'venueCount', 'staffCount', 'ci_count', 'invigilator_count', 'cia_count'));
        }






        // Pass user details and role to the view
        return view('user.my-account', compact('role'));
    }

    /**
     * Get user details based on role and user ID.
     */
}
