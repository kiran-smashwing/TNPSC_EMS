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
            <div class="auth-form" style="background: #266936 ">
                <div class="card my-5">
                    <div class="card-body">
                        <div class="text-center">
                            <a href="#"><img class="mb-3 login-img" src="{{ asset('storage/assets/images/login-logo.png')}}"
                                    alt="img" /></a>
                            <h4 class="text-center f-w-600 mb-3">தமிழ்நாடு அரசுப் பணியாளர் தேர்வாணையம்</h4>
                            {{-- <h4 class="text-center f-w-600 mb-3">Tamil Nadu Public Service Commission</h4> --}}
                        </div>
                        <div class="saprator my-3">
                        </div>
                        <div class="mb-4">
                            <h3 class="mb-2"><b>Hi, Check Your Mail</b></h3>
                            <p class="text-muted">We have sent a password recover instructions to your email.</p>
                        </div>
                        <div class="d-grid mt-3">
                            <a href="{{ route('login') }}" type="button" class="btn btn-primary">Login</a>
                        </div>
                       
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
