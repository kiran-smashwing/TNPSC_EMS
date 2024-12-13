<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\AuditLogger;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\District;
use App\Models\TreasuryOfficer;
use App\Models\Center;
use App\Models\MobileTeamStaffs;
use App\Models\Venues;
use App\Models\ChiefInvigilator;
use App\Models\DepartmentOfficial;


class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            '_token' => 'required|string',
            'role' => 'required|string',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string',
            'remember' => 'sometimes|string',
        ]);

        $email = $validated['email'];
        $password = $validated['password'];
        $remember = isset($validated['remember']) && $validated['remember'] === 'on';
        $role = $validated['role'];

        $throttleKey = strtolower($role) . '|' . $email;

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'email' => __('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ]),
            ])->withInput($request->only('email'));
        }

        // Log out of all guards first
        Auth::guard('district')->logout();
        Auth::guard('treasury')->logout();
        // Auth::guard('center')->logout();
        // Auth::guard('treasury')->logout();
        Auth::guard('mobile_team_staffs')->logout();
        // Auth::guard('venue')->logout();
        // Auth::guard('headquarters')->logout();
        // Auth::guard('ci')->logout();

        // Clear all existing sessions
        $request->session()->flush();
        $request->session()->regenerate();

        $success = false;
        $user = null;
        $userId = null;

        switch ($role) {
            case 'district':
                $success = Auth::guard('district')->attempt([
                    'district_email' => $email,
                    'password' => $password
                ], $remember);
                if ($success) {
                    $user = Auth::guard('district')->user();
                    $userId = $user->district_id; // Using district_id instead of id
                }
                break;
            case 'center':
                $success = Auth::guard('center')->attempt([
                    'center_email' => $email,
                    'password' => $password
                ], $remember);
                if ($success) {
                    $user = Auth::guard('center')->user();
                    $userId = $user->center_id; // Adjust this based on your center table's ID column
                }
                break;

            case 'treasury':
                $success = Auth::guard('treasury')->attempt([
                    'tre_off_email' => $email,
                    'password' => $password
                ], $remember);
                if ($success) {
                    $user = Auth::guard('treasury')->user();
                    $userId = $user->tre_off_id; // Adjust this based on your treasury table's ID column
                }
                break;

            case 'mobile_team_staffs':
                $success = Auth::guard('mobile_team_staffs')->attempt([
                    'mobile_email' => $email,
                    'password' => $password
                ], $remember);
                if ($success) {
                    $user = Auth::guard('mobile_team_staffs')->user();
                    $userId = $user->mobile_id; // Adjust this based on your mobile team table's ID column
                }
                break;
            case 'venue':
                $success = Auth::guard('venue')->attempt([
                    'venue_email' => $email,
                    'password' => $password
                ], $remember);
                if ($success) {
                    $user = Auth::guard('venue')->user();
                    $userId = $user->venue_id; // Adjust this based on your treasury table's ID column
                }
                break;
            case 'headquarters':
                $success = Auth::guard('headquarters')->attempt([
                    'dept_off_email' => $email,
                    'password' => $password
                ], $remember);
                if ($success) {
                    $user = Auth::guard('headquarters')->user();
                    $userId = $user->dept_off_id; // Adjust this based on your treasury table's ID column
                }
                break;
            case 'ci':
                $success = Auth::guard('ci')->attempt([
                    'ci_email' => $email,
                    'password' => $password
                ], $remember);
                if ($success) {
                    $user = Auth::guard('ci')->user();
                    $userId = $user->ci_id; // Adjust this based on your treasury table's ID column
                }
                break;
        }

        if ($success && $user) {
            RateLimiter::clear($throttleKey);

            // Store essential session data with the correct ID
            // Get name and image based on the role
            $name = null;
            $profileImage = 'default.png';

            switch ($role) {
                case 'district':
                    $district = District::find($userId);
                    $name = $district->district_name;
                    $display_role = "District Collectorates";
                    $profileImage = $district->district_image;
                    $email = $district->district_email;  // Add email field
                    break;
            
                case 'center':
                    $center = Center::find($userId);
                    $name = $center->center_name;
                    $display_role = "Centers/Sub Treasuries";
                    $profileImage = $center->center_image ;
                    $email = $center->center_email;  // Add email field
                    break;
            
                case 'treasury':
                    $treasuryOfficer = TreasuryOfficer::find($userId);
                    $name = $treasuryOfficer->tre_off_name;
                    $display_role = "District Treasuries";
                    $profileImage = $treasuryOfficer->tre_off_image;
                    $email = $treasuryOfficer->tre_off_email;  // Add email field
                    break;
            
                case 'mobile_team_staffs':
                    $mobileTeamStaffs = MobileTeamStaffs::find($userId);
                    $name = $mobileTeamStaffs->mobile_name;
                    $display_role = "Mobile Teams";
                    $profileImage = $mobileTeamStaffs->mobile_image ;
                    $email = $mobileTeamStaffs->mobile_email;  // Add email field
                    break;
            
                case 'venue':
                    $venue = Venues::find($userId);
                    $name = $venue->venue_name;
                    $display_role = "Venues";
                    $profileImage = $venue->venue_image ;
                    $email = $venue->venue_email ;  // Add email field
                    break;
            
                case 'headquarters':
                    $departmentOfficial = DepartmentOfficial::find($userId);
                    $name = $departmentOfficial->dept_off_name;
                    $display_role = "Department Officials";
                    $profileImage = $departmentOfficial->dept_off_image;
                    $email = $departmentOfficial->dept_off_email ;  // Add email field
                    break;
            
                case 'ci':
                    $chiefInvigilator = ChiefInvigilator::find($userId);
                    $name = $chiefInvigilator->ci_name;
                    $display_role = "Chief Invigilators";
                    $profileImage = $chiefInvigilator->ci_image;
                    $email = $chiefInvigilator->ci_email;  // Add email field
                    break;
            }
            

            // Store essential session data with the correct ID
            session([
                'auth_role' => $role,
                'athu_display_role' => $display_role,
                'auth_id' => $userId,
                'auth_name' => $name,
                'auth_email' => $email,
                'auth_image' => $profileImage,
            ]);
            // Debugging: dump and die the session values
            // dd([
            //     'auth_role' => session('auth_role'),
            //     'auth_id' => session('auth_id'),
            //     'auth_name' => session('auth_name'),
            //     'auth_email' => session('auth_email'),
            //     'auth_image' => session('auth_image'),
            // ]);

            if ($remember) {
                session()->put('auth.password_confirmed_at', time());
                session()->put('ip_address', $request->ip());
                session()->put('user_agent', $request->userAgent());
            }

            // Log the audit with the correct ID
            AuditLogger::log(
                'User Login',
                get_class($user),
                $user->getKey(),
                null,
                [
                    'email' => $email,
                    'role' => $role,
                    'ip' => request()->ip()
                ]
            );

            return redirect()->intended('/dashboard');
        }

        RateLimiter::hit($throttleKey);


        return back()->withErrors([
            'email' => __('auth.failed'),
        ])->withInput($request->only('email'));
    }


    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255', // Added last name validation
            'email' => 'required|string|email|max:255|unique:users',
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/[a-zA-Z]/',  // At least one letter
                'regex:/[0-9]/',     // At least one number
                'regex:/[@$!%*?&#]/' // At least one symbol
            ], // Ensure password is alphanumeric and at least 8 characters long
            'termsAndConditions' => 'accepted' // Validate checkbox acceptance
        ], [
            'password.regex' => 'Your Password must include at least one letter, number, and special character.',
            'termsAndConditions.accepted' => 'You must agree to the terms and conditions.',
            'email.unique' => 'Thsi email is already associated with an existing account.',
        ]);

        // Create the new user
        $user = User::create([
            'name' => $request->fname . ' ' . $request->lname, // Include last name in user creation
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect('/dashboard');
    }
    private function getRoleFromGuard()
    {
        $guards = config('auth.guards');
        foreach ($guards as $guard => $config) {
            if (Auth::guard($guard)->check()) {
                return $guard;
            }
        }
        throw new \Exception('Unable to determine role from guard.');
    }

    private function getModelByRole($role)
    {
        switch ($role) {
            case 'district':
                return District::class;
            case 'center':
                return Center::class;
            case 'venue':
                return District::class;
            case 'treasury':
                return TreasuryOfficer::class;
            case 'mobile_team_staffs':
                return MobileTeamStaffs::class;
            case 'headquarters':
                return DepartmentOfficialsController::class;
            case 'ci':
                return ChiefInvigilatorsController::class;
                // Add other cases as needed
            default:
                throw new \Exception('Invalid role');
        }
    }
    public function logout(Request $request)
    {
        $role = $this->getRoleFromGuard();
        $model = $this->getModelByRole($role);

        AuditLogger::log('User Logged Out', $model, Auth::id());
        Auth::guard($role)->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }


    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }


    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'role' => 'required|string',
            'email' => 'required|email',
        ]);

        // Map roles to their corresponding Eloquent models, password column names, and email column names
        $roleModelMap = [
            'headquarters' => [
                'model' => DepartmentOfficial::class,
                'password_column' => 'dept_off_password',
                'email_column' => 'dept_off_email',
                'name_column' => 'dept_off_name'
            ],
            'district' => [
                'model' => District::class,
                'password_column' => 'district_password',
                'email_column' => 'district_email',
                'name_column' => 'district_name'
            ],
            'center' => [
                'model' => Center::class,
                'password_column' => 'center_password',
                'email_column' => 'center_email',
                'name_column' => 'center_name'
            ],
            'treasury' => [
                'model' => TreasuryOfficer::class,
                'password_column' => 'tre_off_password',
                'email_column' => 'tre_off_email',
                'name_column' => 'tre_off_name'
            ],
            'mobile_team_staffs' => [
                'model' => MobileTeamStaffs::class,
                'password_column' => 'mobile_password',
                'email_column' => 'mobile_email',
                'name_column' => 'mobile_name'
            ],
            'venue' => [
                'model' => Venues::class,
                'password_column' => 'venue_password',
                'email_column' => 'venue_email',
                'name_column' => 'venue_name'
            ],
            'ci' => [
                'model' => ChiefInvigilator::class,
                'password_column' => 'ci_password',
                'email_column' => 'ci_email',
                'name_column' => 'ci_name'
            ],
        ];

        $role = $request->role;
        $email = $request->email;

        // Check if the role exists in the mapping
        if (!array_key_exists($role, $roleModelMap)) {
            return back()->withErrors(['role' => 'Invalid role selected.']);
        }

        try {
            // Dynamically get the model, password column name, and email column name
            $roleData = $roleModelMap[$role];
            $model = $roleData['model'];
            $passwordColumn = $roleData['password_column'];
            $emailColumn = $roleData['email_column'];
            $nameColumn = $roleData['name_column'];

            // Check if the email exists in the corresponding model
            $user = $model::where($emailColumn, $email)->first();

            if (!$user) {
                return back()->withErrors([
                    'email' => 'No user found with the provided email and role.',
                ]);
            }

            // Generate a new password and hash it before saving
            $newPassword = Str::random(12);
            $hashedPassword = Hash::make($newPassword);

            // Update the relevant user model with the new password
            $model::where($emailColumn, $email)->update([$passwordColumn => $hashedPassword]);

            // Send the new password via email
            Mail::send('email.password_reset', [
                'newPassword' => $newPassword,
                'email' => $email,
                'name' => $user->$nameColumn,
            ], function ($message) use ($email) {
                $message->to($email)
                    ->subject('Your New Password');
            });

            Log::info('Password reset email sent', [
                'email' => $email,
                'role' => $role,
            ]);

            return redirect()->route('password.check-email')->with('email', $email);
        } catch (\Exception $e) {
            Log::error('Error sending password reset email', [
                'email' => $email,
                'role' => $role,
                'error' => $e->getMessage(),
            ]);

            return back()->withErrors([
                'email' => 'An error occurred while sending the password reset email. Please try again later. ' . $e->getMessage(),
            ]);
        }
    }

    public function showCheckEmail()
    {
        if (!session('email')) {
            return redirect()->route('login');
        }

        return view('auth.check-email', ['email' => session('email')]);
    }
    public function showResetPassword(Request $request)
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => [
                'required',
                'confirmed',
                'password' => [
                    'required',
                    'confirmed',
                    'min:8',
                    'regex:/[a-zA-Z]/',
                    'regex:/[0-9]/',
                    'regex:/[@$!%*?&#]/'
                ],
            ],
        ]);

        Log::info('Validation passed');

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
                Log::info('Password reset for user', ['user_id' => $user->id]);
            }
        );

        Log::info('Password reset status', ['status' => $status]);

        if ($status === Password::PASSWORD_RESET) {
            AuditLogger::log('Password Reset', User::class, Auth::id());
            return redirect()->route('login')->with('status', __('Your password has been reset successfully.'));
        }
        // Check the status and handle errors
        if ($status === Password::INVALID_TOKEN) {
            // Handle invalid token case
            return back()->withErrors(['password' => __('This password reset link is invalid.')]);
        } elseif ($status === Password::INVALID_USER) {
            // Handle invalid email case
            return back()->withErrors(['password' => __('We cannot find a user with that email address.')]);
        } elseif ($status === Password::PASSWORD_RESET) {
            // Handle password reset case
            return back()->withErrors(['password' => __('Your password has already been reset. Please try again.')]);
        }

        return back()->withErrors(['password' => [__($status)]]);
    }
}
