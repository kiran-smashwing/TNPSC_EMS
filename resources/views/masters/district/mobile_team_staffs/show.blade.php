@extends('layouts.app')

@section('title', 'View Mobile Team')

@section('content')

    <!-- [ Pre-loader ] start -->
    <div class="page-loader">
        <div class="bar"></div>
    </div>
    <!-- [ Pre-loader ] End -->

    <!-- [ Sidebar Menu ] start -->
    @include('partials.sidebar')
    <!-- [ Sidebar Menu ] end -->

    <!-- [ Header Topbar ] start -->
    @include('partials.header')
    <!-- [ Header Topbar ] end -->

    <div class="pc-container">
        <div class="pc-content">
            <!-- [ Main Content ] start -->
            <div class="row">
                <div class="tab-content">
                    <div class="row">
                        <div class="col-lg-4 col-xxl-3">
                            <div class="card">
                                <div class="card-body position-relative">
                                    <div class="position-absolute end-0 top-0 p-3">
                                        <span class="d-flex align-items-center"></span>
                                    </div>

                                    <div class="text-center mt-3">
                                        <div class="chat-avtar d-inline-flex mx-auto">
                                            <img class="rounded-circle img-fluid wid-70"
                                                src="{{ asset('storage/' . ($team->mobile_image ?? 'assets/images/user/default.png')) }}"
                                                alt="User image" />
                                        </div>
                                        <h5 class="mb-0">{{ $team->mobile_name }}</h5>
                                        <p class="text-muted text-sm">{{ $team->mobile_designation }}</p>
                                        <hr class="my-3 border border-secondary-subtle" />
                                        <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3">
                                            <i class="ti ti-mail me-2"></i>
                                            <p class="mb-0">{{ $team->mobile_email }}</p>
                                        </div>
                                        <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3">
                                            <i class="ti ti-phone me-2"></i>
                                            <p class="mb-0">{{ $team->mobile_phone }}</p>
                                        </div>
                                        <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3">
                                            <i class="ti ti-map-pin me-2"></i>
                                            <p class="mb-0">{{ $team->address }}</p>
                                        </div>
                                        <div class="d-inline-flex align-items-center justify-content-center mt-2 w-100">
                                            <a href="#"
                                                class="btn btn-success d-inline-flex justify-content-center">
                                                <i class="ti ti-map-2 me-1"></i>View Location
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional team details or sections -->
                    </div>
                </div>
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>

    @include('partials.footer')
    @include('partials.theme')

@endsection
