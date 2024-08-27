@extends('layouts.app')
@section('title', ' Forgot Password')

@section('content')
    <!-- [ Pre-loader ] start -->
    <div class="page-loader">
        <div class="bar"></div>
    </div>
    <!-- [ Pre-loader ] End -->

    <div class="auth-main">
        <div class="auth-wrapper v1">
            <div class="auth-form" style="background: #266936">
                <div class="card my-5">
                    <div class="card-body">
                        <div class="text-center">
                            <a href="#"><img class="mb-3 login-img"
                                    src="{{ asset('storage/assets/images/login-logo.png') }}" alt="img" /></a>
                            <h4 class="text-center f-w-600 mb-3">தமிழ்நாடு அரசுப் பணியாளர் தேர்வாணையம்</h4>
                            {{-- <h4 class="text-center f-w-600 mb-3">Tamil Nadu Public Service Commission</h4> --}}
                        </div>
                        <div class="saprator my-3">
                        </div>
                        <div class="mb-4">
                            <h3 class="mb-2"><b>Reset Password</b></h3>
                            <p class="text-muted">Please choose your new password</p>
                        </div>
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $request->route('token') }}">
                            <input type="hidden" name="email" value="{{ $request->email }}">
                            <!-- Password -->
                            <div class="mb-3">
                                <input type="password" name="password" class="form-control" placeholder="Password" />
                                @error('password')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    Your password must be at least 8 characters long and include:
                                    <ul>
                                        <li>At least one letter (a-z, A-Z)</li>
                                        <li>At least one number (0-9)</li>
                                        <li>At least one symbol (e.g., @, $, !, %, *, ?, &, #)</li>
                                    </ul>
                                </small>
                            </div>

                            <!-- Confirm Password -->
                            <div class="mb-3">
                                <input type="password" name="password_confirmation" class="form-control"
                                    placeholder="Confirm Password" required />
                            </div>
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary">Reset Password</button>
                            </div>
                        </form>
                    </div>
                </div>
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
