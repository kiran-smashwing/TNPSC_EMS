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
use Illuminate\Support\Facades\Log;

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
            'email' => 'required|string|email|max:255',
            'password' => 'required|string',
            'remember' => 'sometimes|string',
        ]);

        $email = $validated['email'];
        $password = $validated['password'];
        $remember = isset($validated['remember']) && $validated['remember'] === 'on';

        $throttleKey = Str::lower($request->input('email')) . '|' . $request->ip();

        // Check if email exists in the database
        if (!User::where('email', $email)->exists()) {
            return back()->withErrors([
                'email' => 'The email address you entered is not registered with us.',
            ])->withInput($request->only('email'));
        }

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return back()->withErrors([
                'email' => __('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ]),
            ])->withInput($request->only('email'))
                ->with('seconds', $seconds); // Pass seconds to the view
        }

        if (Auth::attempt(['email' => $email, 'password' => $password], $remember)) {
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();
            if ($remember) {
                // If "Remember Me" is checked, set a long-lived session
                $rememberDuration = 60 * 24; //1 days
                config(['session.lifetime' => $rememberDuration]);
                session()->put('auth.password_confirmed_at', time());
                session()->put('ip_address', $request->ip());
                session()->put('user_agent', $request->userAgent());
            }
            AuditLogger::log('User Logged In', User::class, Auth::id());
            return redirect()->intended('dashboard');
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

    public function logout(Request $request)
    {
        AuditLogger::log('User Logged Out', User::class, Auth::id());
        Auth::guard('web')->logout();

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
            'email' => 'required|email',
        ]);

        try {
            $status = Password::sendResetLink(
                $request->only('email')
            );

            Log::info('Password reset attempt', ['email' => $request->email, 'status' => $status]);

            if ($status === Password::RESET_LINK_SENT) {
                return redirect()->route('password.check-email')->with('email', $request->email);
            }

            return back()->withErrors(['email' => __($status)]);
        } catch (\Exception $e) {
            Log::error('Error sending password reset email', ['email' => $request->email, 'error' => $e->getMessage()]);
            return back()->withErrors(['email' => 'An error occurred while sending the password reset email. Please try again later.']);
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