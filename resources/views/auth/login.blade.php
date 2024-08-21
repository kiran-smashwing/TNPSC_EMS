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
                        <div class="text-center">
                            <a href="#"><img class="mb-3 login-img" src="../assets/images/login-logo.png"
                                    alt="img" /></a>
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
                                <select class="form-select" aria-label="Select Role">
                                    <option selected disabled>Select Role</option>
                                    <option value="1">Headquarters Officers</option>
                                    <option value="2">District Collectorates</option>
                                    <option value="3">Sub-Treasury</option>
                                    <option value="4">Mobile Team</option>
                                    <option value="5">Venues </option>
                                    <option value="6">Cheif Invigilators </option>
                                </select>
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
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary" id="loginButton">
                                    {{ __('Login') }}
                                </button>
                            </div>
                            <div class="alert alert-warning mt-3" id="rateLimitMessage" style="display: none;">
                                Too many login attempts. Please try again in <span id="countdown"></span> seconds.
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

                                let seconds = {{ session('seconds', 0) }};

                                if (seconds > 0) {
                                    localStorage.setItem('rateLimitSeconds', seconds);
                                } else {
                                    const storedSeconds = localStorage.getItem('rateLimitSeconds');
                                    if (storedSeconds) {
                                        seconds = parseInt(storedSeconds, 10);
                                    }
                                }

                                if (seconds > 0) {
                                    rateLimitMessage.style.display = 'block';
                                    loginButton.disabled = true;

                                    const countdownInterval = setInterval(() => {
                                        if (seconds > 0) {
                                            seconds--;
                                            countdownElement.textContent = seconds;
                                            localStorage.setItem('rateLimitSeconds', seconds);
                                        } else {
                                            clearInterval(countdownInterval);
                                            localStorage.removeItem('rateLimitSeconds');
                                            loginButton.disabled = false;
                                            rateLimitMessage.style.display = 'none';
                                        }
                                    }, 1000);
                                }
                            });
                        </script>

                    </div>
                </div>
            </div>
             <div class=" auth-footer mt-2 mb-2" style="backgroud:#fff">
                <p class="m-0 w-100 text-center">Copyright © {{ date('Y') }} <a
                        href="https://www.tnpsc.gov.in/">TNPSC</a>. Developed By <a href="https://www.smashwing.com/">Smashwing Technologies Pvt Ltd.</a> All rights reserved.
                </p>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
    <!-- Required Js -->
    <script src="../assets/js/plugins/popper.min.js"></script>
    <script src="../assets/js/plugins/simplebar.min.js"></script>
    <script src="../assets/js/plugins/bootstrap.min.js"></script>
    <script src="../assets/js/fonts/custom-font.js"></script>
    <script src="../assets/js/pcoded.js"></script>
    <script src="../assets/js/plugins/feather.min.js"></script>

    @include('partials.theme')

@endsection
