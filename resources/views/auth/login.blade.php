@extends('layouts.app')
@section('title', ' Login')

@section('content')
    <!-- [ Pre-loader ] start -->
    <div class="page-loader">
        <div class="bar"></div>
    </div>
    <!-- [ Pre-loader ] End -->
    <div class="auth-main">
        <div class="auth-wrapper ">
            <div class="auth-form" style="background: #266936 ">
                <div class="card my-5">
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-info alert-dismissible fade show" role="alert">{{ session('status') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        <div class="text-center">
                            <a href="#"><img class="mb-3 login-img"
                                    src="{{ asset('storage/assets/images/login-logo.png') }}" alt="img" /></a>
                            <h4 class="text-center f-w-600 mb-3">தமிழ்நாடு அரசுப் பணியாளர் தேர்வாணையம்</h4>
                            {{-- <h4 class="text-center f-w-600 mb-3">Tamil Nadu Public Service Commission</h4> --}}
                        </div>
                        <div class="saprator my-3">
                        </div>
                        {{-- <h4 class="text-center f-w-500 mb-3">Login with your email</h4> --}}
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-3 d-flex align-items-center">
                                {{-- <h6 class="f-w-500 mb-0 me-2">Role:</h6> --}}
                                <select name="role" class="form-select @error('role') is-invalid @enderror"
                                    aria-label="Select Role" required>
                                    <option selected disabled>Select Role</option>
                                    <option value="headquarters" {{ old('role') == 'headquarters' ? 'selected' : '' }}>
                                        Department Officials</option>
                                    <option value="district" {{ old('role') == 'district' ? 'selected' : '' }}>District
                                        Collectorates</option>
                                    <option value="center" {{ old('role') == 'center' ? 'selected' : '' }}>Centers/Sub
                                        Treasuries</option>
                                    <option value="treasury" {{ old('role') == 'treasury' ? 'selected' : '' }}>District
                                        Treasuries</option>
                                    <option value="mobile_team_staffs"
                                        {{ old('role') == 'mobile_team_staffs' ? 'selected' : '' }}>Mobile Teams</option>
                                    <option value="venue" {{ old('role') == 'venue' ? 'selected' : '' }}>
                                        Venues(Schools/Colleges)</option>
                                    <option value="ci" {{ old('role') == 'ci' ? 'selected' : '' }}>Chief Invigilators
                                    </option>
                                </select>
                                @error('role')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror" id="floatingInput"
                                    placeholder="Email Address" value="{{ old('email') }}" required autocomplete="email"
                                    autofocus />
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <input type="password" name="password"
                                    class="form-control @error('email') is-invalid @enderror" id="floatingInput1"
                                    placeholder="Password" required autocomplete="current-password" />
                            </div>
                            <div class="d-flex mt-1 justify-content-between align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input input-primary" type="checkbox" name="remember"
                                        id="remember" {{ old('remember') ? 'checked' : '' }} />
                                    <label class="form-check-label text-muted" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                                @if (Route::has('forgot-password'))
                                    <h6 class="text-secondary f-w-400 mb-0">
                                        <a href="{{ route('forgot-password') }}">
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                    </h6>
                                @endif
                            </div>
                            <div class="alert alert-warning mt-3" id="rateLimitMessage" style="display: none;">
                                Too many login attempts. Please try again in <span id="countdown"></span> seconds.
                            </div>
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary" id="loginButton">
                                    {{ __('Login') }}
                                </button>
                            </div>
                            <div class="auth-footer mt-4">
                                <p class="m-0 w-100 text-center">By signing in, you confirm to have read TNPSC EMS <a
                                        href="#">Privacy Policy</a> and agree to the
                                    <a href="#">Terms of Service</a>.
                                </p>
                            </div>
                        </form>

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const loginButton = document.getElementById('loginButton');
                                const rateLimitMessage = document.getElementById('rateLimitMessage');
                                const countdownElement = document.getElementById('countdown');

                                // Retrieve the remaining seconds from the server (passed via session)
                                let seconds = {{ session('throttle', 0) }};

                                if (seconds > 0) {
                                    // Show the rate limit message and disable the login button
                                    rateLimitMessage.style.display = 'block';
                                    loginButton.disabled = true;

                                    // Start the countdown timer
                                    const countdownInterval = setInterval(() => {
                                        if (seconds > 0) {
                                            // Update the countdown display
                                            countdownElement.textContent = seconds;
                                            seconds--;
                                        } else {
                                            // When the countdown ends, enable the login button and hide the message
                                            clearInterval(countdownInterval);
                                            loginButton.disabled = false;
                                            rateLimitMessage.style.display = 'none';
                                        }
                                    }, 1000); // Update every second
                                }
                                const emailInput = document.getElementById('floatingInput');
                                const roleSelect = document.querySelector('select[name="role"]');

                                // Disable form submission if no role is selected
                                function updateButtonState() {
                                    loginButton.disabled = !roleSelect.value || !emailInput.value;
                                }

                                roleSelect.addEventListener('change', updateButtonState);
                                emailInput.addEventListener('input', updateButtonState);

                                // Initial state
                                updateButtonState();

                                // Add basic input validation
                                emailInput.addEventListener('input', function() {
                                    const email = this.value;
                                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                                    if (!emailRegex.test(email)) {
                                        this.setCustomValidity('Please enter a valid email address');
                                    } else {
                                        this.setCustomValidity('');
                                    }
                                });
                            });
                        </script>

                    </div>
                </div>
            </div>
            <div class=" auth-footer mt-2 mb-2" style="backgroud:#fff">
                <p class="m-0 w-100 text-center">Copyright © {{ date('Y') }} <a
                        href="https://www.tnpsc.gov.in/">TNPSC</a>.
                    Developed By <a href="https://www.smashwing.com/">Smashwing Technologies Pvt Ltd.</a> All rights
                    reserved.
                </p>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
 

    @include('partials.theme')

@endsection
