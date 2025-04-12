<?php

namespace App\Http\Controllers;

use App\Mail\UserEmailVerificationMail;
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
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Mail;
use InvalidArgumentException;
use Exception;
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
        $this->middleware('guest')->except('logout', 'resendVerificationEmail');
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

        // Create a unique throttle key based on role, email, and IP
        $throttleKey = strtolower($email) . '|' . $request->ip();

        // Check for rate limiting
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            // Log the rate limit hit
            Log::warning('Rate limit hit for login attempt', [
                'email' => $email,
                'role' => $role,
                'ip' => $request->ip(),
                'seconds_remaining' => $seconds
            ]);

            // Determine if the request expects a JSON response (e.g., API call) or a web redirect
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => __('auth.throttle', [
                        'seconds' => $seconds,
                        'minutes' => ceil($seconds / 60),
                    ]),
                    'throttle' => $seconds,
                ], 429); // Too Many Requests
            }

            // For web requests, return a redirect with errors as before
            return back()
                ->withErrors([
                    'email' => __('auth.throttle', [
                        'seconds' => $seconds,
                        'minutes' => ceil($seconds / 60),
                    ])
                ])
                ->with('throttle', $seconds)
                ->withInput($request->only('email', 'role'));
        }

        // Increment the rate limiter counter
        RateLimiter::hit($throttleKey);

        // Clear all existing sessions
        $request->session()->flush();
        $request->session()->regenerate();

        $success = false;
        $user = null;
        $userId = null;

        // Add a small delay to prevent brute force attacks
        usleep(rand(100000, 500000)); // Random delay between 100ms and 500ms

        switch ($role) {
            case 'district':
                $success = Auth::guard('district')->attempt([
                    'district_email' => $email,
                    'password' => $password
                ], $remember);
                if ($success) {
                    $user = Auth::guard('district')->user();
                    $userId = $user->district_id;
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
                    // Check verification status
                    if (!$user->venue_email_status) {
                        session(['show_verification_alert' => true]);
                    }
                }
                break;
            case 'headquarters':
                $users = DepartmentOfficial::where('dept_off_email', $email)->get();
                // Filter users who have the role 'headquarters'
                // Filter users who have at least one valid role
                $filteredUsers = $users->filter(function ($user) {
                    return (!empty($user->dept_off_role) || !empty($user->custom_role));
                });

                if ($filteredUsers->isEmpty()) {
                    return redirect()->back()->withErrors([
                        'email' => 'You have not been assigned to any department or role. Please contact your administrator.',
                    ])->withInput($request->except('password'));
                }

                $success = '';
                if ($filteredUsers->count() === 1) {
                    $user = $filteredUsers->first();
                    // Securely verify the password

                    if (Hash::check($password, $user->dept_off_password)) {
                        $success = Auth::guard('headquarters')->loginUsingId($user->dept_off_id, $remember);
                        $userId = $user->dept_off_id;
                    } else {
                        return redirect()->back()->withErrors([
                            'password' => 'Incorrect password. Please try again or reset it using "Forgot Password".',
                        ]);
                    }
                } else {
                    return redirect()->back()->withErrors([
                        'email' => 'Multiple accounts found with valid roles. Please contact support.',
                    ]);
                }
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
            // Clear rate limiter on successful login
            RateLimiter::clear($throttleKey);

            // Store essential session data
            $display_role = $this->getDisplayRole($role);
            // Store the device fingerprint in the session
            // Generate and store the initial device fingerprint
            $deviceFingerprint = $_COOKIE['device_fingerprint'] ?? null;
            if (!$deviceFingerprint) {
                return redirect()->route('login')
                    ->with('error', 'Security verification failed. Please try again.')
                    ->withInput($request->except('password'));
            }
            session([
                'auth_role' => $role,
                'athu_display_role' => $display_role,
                'auth_id' => $userId,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'device_fingerprint' => $deviceFingerprint
            ]);

            if ($remember) {
                session()->put('auth.password_confirmed_at', time());
            }

            // Log successful login
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

            return redirect()->intended('/myaccount');
        }

        // Log failed login attempt
        Log::warning('Failed login attempt', [
            'email' => $email,
            'role' => $role,
            'ip' => $request->ip()
        ]);

        return back()
            ->withErrors(['email' => __('auth.failed')])
            ->withInput($request->only('email', 'role'));
    }

    private function getDisplayRole(string $role): string
    {
        return match ($role) {
            'district' => "District Collectorates",
            'center' => "Centers/Sub Treasuries",
            'treasury' => "District Treasuries",
            'mobile_team_staffs' => "Mobile Teams",
            'venue' => "Venues",
            'headquarters' => "Department Officials",
            'ci' => "Chief Invigilators",
            default => ucfirst($role)
        };
    }

    public function showAdminLogin()
    {
        return view('auth.admin-login');
    }

    public function Adminlogin(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            '_token' => 'required|string',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string',
            'remember' => 'sometimes|string',
        ]);

        $email = $validated['email'];
        $password = $validated['password'];
        $remember = isset($validated['remember']) && $validated['remember'] === 'on';

        // Throttle key for rate limiting, specifically for 'sw-admin'
        $throttleKey = 'sw-admin|' . $email;

        // Check for throttling (rate limiting)
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'email' => __('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ]),
            ])->withInput($request->only('email'));
        }


        // Authenticate the 'sw-admin' user
        $success = Auth::guard('sw-admin')->attempt([
            'email' => $email,
            'password' => $password
        ], $remember);

        if ($success) {
            // Retrieve the user
            $user = Auth::guard('sw-admin')->user();
            $userId = $user->id;

            // Clear throttling attempts
            RateLimiter::clear($throttleKey);

            // Get user details
            $name = $user->name;
            $displayRole = "SW Admin";
            $profileImage = 'default.png';
            $email = $user->email;

            // Store session data
            session([
                'auth_role' => 'sw-admin',  // No hardcoded role name used, just the context
                'athu_display_role' => $displayRole,
                'auth_id' => $userId,
                'auth_name' => $name,
                'auth_email' => $email,
                'auth_image' => $profileImage,
            ]);

            // If the user chose 'remember me', store additional session data
            if ($remember) {
                session()->put('auth.password_confirmed_at', time());
                session()->put('ip_address', $request->ip());
                session()->put('user_agent', $request->userAgent());
            }

            // Log the user login action
            AuditLogger::log(
                'User Login',
                get_class($user),
                $user->getKey(),
                null,
                [
                    'email' => $email,
                    'role' => 'sw-admin', // Same as the session role
                    'ip' => request()->ip()
                ]
            );

            // Redirect to the intended page or dashboard
            return redirect()->intended('/dashboard');
        }

        // If authentication failed, increment throttle attempts
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
    private array $guardMap = [
        'district' => [
            'model' => District::class,
            'id_field' => 'district_id'
        ],
        'center' => [
            'model' => Center::class,
            'id_field' => 'center_id'
        ],
        'venue' => [
            'model' => Venues::class,
            'id_field' => 'venue_id'
        ],
        'treasury' => [
            'model' => TreasuryOfficer::class,
            'id_field' => 'tre_off_id'
        ],
        'mobile_team_staffs' => [
            'model' => MobileTeamStaffs::class,
            'id_field' => 'mobile_id'
        ],
        'headquarters' => [
            'model' => DepartmentOfficial::class,
            'id_field' => 'dept_off_id'
        ],
        'ci' => [
            'model' => ChiefInvigilator::class,
            'id_field' => 'ci_id'
        ],
        'sw-admin' => [
            'model' => User::class,
            'id_field' => 'id'
        ]
    ];

    private function getRoleFromGuard(): string
    {
        $currentRole = session('auth_role');

        // Verify the session role matches an active guard
        if ($currentRole && Auth::guard($currentRole)->check()) {
            return $currentRole;
        }

        // Fallback to checking all guards
        foreach (array_keys($this->guardMap) as $guard) {
            if (Auth::guard($guard)->check()) {
                return $guard;
            }
        }

        throw new AuthenticationException('No authenticated user found.');
    }

    private function getModelInfo(string $role): array
    {
        if (!isset($this->guardMap[$role])) {
            Log::error('Invalid role attempted during logout', [
                'role' => $role,
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            throw new InvalidArgumentException('Invalid role specified');
        }

        return $this->guardMap[$role];
    }

    public function logout(Request $request)
    {
        try {
            // Get current user info before logout
            $role = $this->getRoleFromGuard();
            $modelInfo = $this->getModelInfo($role);
            $guard = Auth::guard($role);
            $user = $guard->user();

            if ($user) {
                $userId = $user->{$modelInfo['id_field']};

                // Log the logout event
                AuditLogger::log(
                    'User Logged Out',
                    $modelInfo['model'],
                    $userId,
                    null,
                    [
                        'session_id' => session()->getId()
                    ]
                );

                // Logout of specific guard
                $guard->logout();
            }

            // Logout of all guards for extra security
            foreach (array_keys($this->guardMap) as $guardName) {
                Auth::guard($guardName)->logout();
            }

            // Clear and invalidate session
            session()->flush();
            session()->invalidate();
            session()->regenerateToken();

            return redirect('/')
                ->with('status', 'You have been successfully logged out');

        } catch (Exception $e) {
            Log::error('Logout error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip' => $request->ip()
            ]);

            // Still perform session cleanup even if there's an error
            session()->flush();
            session()->invalidate();
            session()->regenerateToken();

            return redirect('/')
                ->with('error', 'Logged out due to security concern');
        }
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

    public function resendVerificationEmail()
    {
        $role = session('auth_role');

        $user = current_user();
        $verification_token = Str::random(64);

        $venue = Venues::where('venue_id', $user->venue_id)->first();
        if ($venue) {
            $venue->verification_token = $verification_token;
            $venue->save();
            $verificationLink = route('venues.verifyEmail', ['token' => urlencode($venue->verification_token)]);

            if ($verificationLink) {
                Mail::to($venue->venue_email)->send(new UserEmailVerificationMail($venue->venue_name, $venue->venue_email, $verificationLink)); // Use the common mailable
            } else {
                throw new Exception('Failed to generate verification link.');
            }
            return redirect()->route('venues.verifyEmail', ['token' => urlencode($verification_token)]);
        } else {
            throw new Exception('Venue not found.');
        }
    }
}
