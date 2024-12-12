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

        // Debugging: Check if role and userId are correctly retrieved
        if (!$role || !$userId) {
            return redirect()->route('login')->with('error', 'Session expired or invalid.');
        }

        $userDetails = $this->getUserDetailsByRole($role, $userId);
        $roles = Role::all();
        $districts = District::all();
        $centers = Center::all(); // Retrieve all centers

        // Handle if user details are not found
        if (!$userDetails) {
            return redirect()->back()->with('error', 'User details not found.');
        }

        // Pass user details and role to the view
        return view('user.my-account', compact('userDetails', 'roles', 'role' ,'districts', 'centers'));
    }

    /**
     * Get user details based on role and user ID.
     */
    private function getUserDetailsByRole($role, $userId)
    {
        switch ($role) {
            case 'district':
                return $this->getDistrictDetails($userId);
            case 'center':
                return $this->getCenterDetails($userId);
            case 'treasury':
                return $this->getTreasuryDetails($userId);
            case 'mobile_team_staffs':
                return $this->getMobileTeamDetails($userId);
            case 'venue':
                return $this->getVenueDetails($userId);
            case 'headquarters':
                return $this->getHeadquartersDetails($userId);
            case 'ci':
                return $this->getCIDetails($userId);
            default:
                return null;
        }
    }

    /**
     * Role-specific methods to fetch user details.
     */

    private function getDistrictDetails($userId)
    {
        $district = District::find($userId);
        if (!$district) {
            return null;
        }

        return [
            'name' => $district->district_name,
            'email' => $district->district_email,
            'phone' => $district->district_phone,
            'designation' => 'District Officer',
            'profile_picture' => $district->profile_picture,
            'address' => $district->address,
        ];
    }

    private function getCenterDetails($userId)
    {
        $center = Center::find($userId);
        if (!$center) {
            return null;
        }

        return [
            'name' => $center->center_name,
            'email' => $center->center_email,
            'phone' => $center->center_phone,
            'designation' => 'Center Officer',
            'profile_picture' => $center->avatar,
            'address' => $center->location,
        ];
    }

    private function getTreasuryDetails($userId)
    {
        $treasury = TreasuryOfficer::find($userId); // Changed model
        if (!$treasury) {
            return null;
        }

        return [
            'name' => $treasury->officer_name, // Assuming field names in TreasuryOfficer
            'email' => $treasury->officer_email,
            'phone' => $treasury->officer_phone,
            'designation' => 'Treasury Officer',
            'profile_picture' => $treasury->profile_picture,
            'address' => $treasury->address,
        ];
    }

    private function getMobileTeamDetails($userId)
    {
        $mobileTeam = MobileTeamStaffs::find($userId); // Changed model
        if (!$mobileTeam) {
            return null;
        }

        return [
            'name' => $mobileTeam->staff_name, // Assuming field names in MobileTeamStaffs
            'email' => $mobileTeam->staff_email,
            'phone' => $mobileTeam->staff_phone,
            'designation' => 'Mobile Team Staff',
            'profile_picture' => $mobileTeam->profile_picture,
            'address' => $mobileTeam->location,
        ];
    }

    private function getVenueDetails($userId)
    {
        $venue = Venues::find($userId); // Changed model
        if (!$venue) {
            return null;
        }
    // dd($venue->venue_district_id); //
        // Retrieve District Details
        $district = District::find($venue->venue_district_id);
        $districtDetails = $district ? [
            'district_name' => $district->district_name,
            'district_code' => $district->district_code,
        ] : [
            'district_name' => null,
            'district_code' => null,
        ];
    
        // Retrieve Center Details
        // dd($venue->venue_center_id);
        $center = Center::find($venue->venue_center_id);
        $centerDetails = $center ? [
            'center_name' => $center->center_name,
            'center_code' => $center->center_code,
        ] : [
            'center_name' => null,
            'center_code' => null,
        ];
    //  dd($centerDetails);
        return [
            'name' => $venue->venue_name,
            'district_id' => $venue->venue_district_id,
            'district_name' => $districtDetails['district_name'],
            'district_code' => $districtDetails['district_code'],
            'center_id' => $venue->venue_center_id,
            'center_name' => $centerDetails['center_name'],
            'center_code' => $centerDetails['center_code'],
            'code' => $venue->venue_code,
            'email' => $venue->venue_emai,
            'code_provider' => $venue->venue_codeprovider,
            'phone' => $venue->venue_phone,
            'alternative_phone' => $venue->venue_alternative_phone,
            'type' => $venue->venue_type,
            'category' => $venue->venue_category,
            'website' => $venue->venue_website,
            'bank_name' => $venue->venue_bank_name,
            'account_name' => $venue->venue_account_name,
            'account_number' => $venue->venue_account_number,
            'branch_name' => $venue->venue_branch_name,
            'account_type' => $venue->venue_account_type,
            'ifsc' => $venue->venue_ifsc,
            'profile_picture' => $venue->venue_image 
                ? asset('storage/' . $venue->venue_image) 
                : asset('storage/assets/images/user/avatar-1.jpg'),
            'address' => $venue->venue_address,
        ];
    }
    

    private function getHeadquartersDetails($userId)
    {
        // Fetch DepartmentOfficial based on user ID
        $hq = DepartmentOfficial::find($userId);
        if (!$hq) {
            return null;
        }
        // Fetch the Role based on the role_id in DepartmentOfficial
        $role = Role::find($hq->dept_off_role); // Assuming 'role_id' is a field in 'DepartmentOfficial'


        // If the role exists, fetch both the role_name and role_department, otherwise return 'N/A'
        $roleName = $role ? $role->role_name : 'N/A';
        $roleDepartment = $role ? $role->role_department : 'N/A';

        // Return user details with role information
        return [
            'name' => $hq->dept_off_name, // Name from DepartmentOfficial
            'role_name' => $roleName, // Role name from Role model
            'role_department' => $roleDepartment, // Role department from Role model
            'role' => $hq->dept_off_role,
            'email' => $hq->dept_off_email,
            'phone' => $hq->dept_off_phone,
            'designation' => $hq->dept_off_designation,
            'profile_picture' => $hq->dept_off_image ? asset('storage/' . $hq->dept_off_image) : asset('storage/assets/images/user/avatar-1.jpg'),
            'address' => $hq->dept_off_emp_id, // Assuming 'emp_id' is the address field
        ];
    }


    private function getCIDetails($userId)
    {
        $ci = ChiefInvigilator::find($userId); // Changed model
        if (!$ci) {
            return null;
        }

        return [
            'name' => $ci->ci_name, // Assuming field names in ChiefInvigilator
            'email' => $ci->ci_email,
            'phone' => $ci->ci_phone,
            'designation' => 'Chief Invigilator',
            'profile_picture' => $ci->profile_picture,
            'address' => $ci->address,
        ];
    }
}
