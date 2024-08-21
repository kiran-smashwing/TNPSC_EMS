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
                            <a href="#"><img class="mb-3 login-img" src="../assets/images/login-logo.png"
                                    alt="img" /></a>
                            <h4 class="text-center f-w-600 mb-3">தமிழ்நாடு அரசுப் பணியாளர் தேர்வாணையம்</h4>
                            {{-- <h4 class="text-center f-w-600 mb-3">Tamil Nadu Public Service Commission</h4> --}}
                        </div>
                        <div class="saprator my-3">
                        </div>
                        <div class="d-flex justify-content-between align-items-end mb-4">
                            <h3 class="mb-0"><b>Forgot Password</b></h3>
                            <a href="{{ route('login') }}" class="link-primary">Back to Login</a>
                        </div>
                        <div class="mb-3">
                            {{-- <label class="form-label">Email Address</label> --}}
                            <input type="email" class="form-control" id="floatingInput" placeholder="Email Address" />
                        </div>
                        <p class="mt-4 text-sm text-muted">Do not forgot to check SPAM box.</p>
                        <div class="d-grid mt-3">
                            <button type="button" class="btn btn-primary">Send Password Reset Email</button>
                        </div>
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