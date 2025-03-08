<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\AuditLogger;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use App\Models\District;
use App\Models\TreasuryOfficer;
use App\Models\Center;
use App\Models\MobileTeamStaffs;
use App\Models\Venues;
use App\Models\ChiefInvigilator;
use App\Models\DepartmentOfficial;

class ChangePasswordController extends Controller
{
    public function __construct()
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth.multi');
    }
  

    public function updatePassword(Request $request)
    {
        // Validate the input
        $request->validate([
            'old_password' => 'required|string',
            'new_password' => 'required|string|confirmed', // Use confirmed for password confirmation
            'role' => 'required|string',
            'user_id' => 'required|integer',
        ]);
    
        $role = $request->role;
        $userId = $request->user_id;
        $oldPassword = $request->old_password;
        $newPassword = $request->new_password;
    
        // Initialize user and password column
        $user = null;
        $passwordColumn = null;
    
        // Determine the model and password column based on the role
        switch ($role) {
            case 'district':
                $user = District::find($userId);
                $passwordColumn = 'district_password';
                break;
            case 'center':
                $user = Center::find($userId);
                $passwordColumn = 'center_password';
                break;
            case 'treasury':
                $user = TreasuryOfficer::find($userId);
                $passwordColumn = 'tre_off_password';
                break;
            case 'mobile_team_staffs':
                $user = MobileTeamStaffs::find($userId);
                $passwordColumn = 'mobile_password';
                break;
            case 'venue':
                $user = Venues::find($userId);
                $passwordColumn = 'venue_password';
                break;
            case 'headquarters':
                $user = DepartmentOfficial::find($userId);
                $passwordColumn = 'dept_off_password';
                break;
            case 'ci':
                $user = ChiefInvigilator::find($userId);
                $passwordColumn = 'ci_password';
                break;
            default:
                return redirect()->back()->with('error', 'Invalid role.');
        }
    
        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }
    
        // Check if the old password matches
        if (Hash::check($oldPassword, $user->{$passwordColumn})) {
            // Log the old data for auditing
            $oldData = [$passwordColumn => $user->{$passwordColumn}];
    
            // Update the password
            $user->{$passwordColumn} = Hash::make($newPassword);
            $user->save();
    
            // Log the new data for auditing
            $newData = [$passwordColumn => $user->{$passwordColumn}];
    
            // Record the action in the audit log
            AuditLogger::log(
                'Password Updated',
                get_class($user),  // Model class dynamically resolved
                $user->id,         // User ID
                $oldData,          // Old password data
                $newData           // New password data
            );
    
            return redirect()->back()->with('success', 'Password updated successfully.');
        } else {
            return redirect()->back()->with('error', 'Old password is incorrect.');
        }
    }
    public function showchangePassword()
    {
        return view('auth.change_password');
    }
}
