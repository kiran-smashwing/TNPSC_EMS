@extends('layouts.app')
@section('title', ' Page Not Found')

@section('content')  <!-- [ Pre-loader ] start -->
    <div class="page-loader">
      <div class="bar"></div>
    </div>
    <!-- [ Pre-loader ] End -->

    <!-- [ Main Content ] start -->
    <div class="maintenance-block">
      <div class="container">
        <div class="row">
          <div class="col-sm-12">
            <div class="card error-card">
              <div class="card-body">
                <div class="error-image-block">
                  <img class="img-fluid"src="{{asset('storage//assets/images/pages/img-error-404.svg')}}" alt="img" />
                </div>
                <div class="text-center">
                  <h1 class="mt-5"><b>Page Not Found</b></h1>
                  <p class="mt-2 mb-4 text-muted"
                    >The page you are looking was moved, removed,<br />
                    renamed, or might never exist!</p
                  >
                  <a href="{{route("dashboard")}}" class="btn btn-primary mb-3">Go to home</a>
                </div>
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