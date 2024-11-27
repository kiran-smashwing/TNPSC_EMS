@extends('layouts.app')
@section('title', ' Forgot Password')

@section('content')
    <!-- [ Pre-loader ] start -->
    <div class="page-loader">
        <div class="bar"></div>
    </div>
    <!-- [ Pre-loader ] End -->

    <div class="auth-main">
        <div class="auth-wrapper">
            <div class="auth-form" style="background: #266936 ">
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
                        <div class="d-flex justify-content-between align-items-end mb-4">
                            <h3 class="mb-0"><b>Forgot Password</b></h3>
                            <a href="{{ route('login') }}" class="link-primary">Back to Login</a>
                        </div>
                        <form method="POST" action="{{ route('send-reset-link-email') }}">
                            @csrf
                            <div class="mb-3 d-flex align-items-center">
                                {{-- <h6 class="f-w-500 mb-0 me-2">Role:</h6> --}}
                                <select name="role" class="form-select @error('role') is-invalid @enderror"
                                    aria-label="Select Role" required>
                                    <option selected disabled>Select Role</option>
                                    <option value="headquarters" {{ old('role') == 'headquarters' ? 'selected' : '' }}>
                                        Headquarters Officers</option>
                                    <option value="district" {{ old('role') == 'district' ? 'selected' : '' }}>District
                                        Collectorates</option>
                                    <option value="center" {{ old('role') == 'center' ? 'selected' : '' }}>Centers</option>
                                    <option value="treasury" {{ old('role') == 'treasury' ? 'selected' : '' }}>Sub-Treasury
                                    </option>
                                    <option value="mobile_team_staffs"
                                        {{ old('role') == 'mobile_team_staffs' ? 'selected' : '' }}>Mobile Team</option>
                                    <option value="venue" {{ old('role') == 'venue' ? 'selected' : '' }}>Venues</option>
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
                                    placeholder="Email Address" value="{{ old('email') }}" />
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <p class="mt-4 text-sm text-muted">Do not forget to check SPAM box.</p>
                            <div class="d-grid mt-3">
                                <button type="submit" class="btn btn-primary">Send Password Reset Email</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class=" auth-footer mt-2 mb-2" style="backgroud:#fff">
                <p class="m-0 w-100 text-center">Copyright © {{ date('Y') }} <a
                        href="https://www.tnpsc.gov.in/">TNPSC</a>. Developed By <a
                        href="https://www.smashwing.com/">Smashwing Technologies Pvt Ltd.</a> All rights reserved.
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
