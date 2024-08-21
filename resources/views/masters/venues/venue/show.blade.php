@extends('layouts.app')

@section('title', 'View Venues')

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
                                        <span class="d-flex align-items-center">
                                            <!-- Email Address -->
                                            <span class="me-2">E-mail</span>
                                            <!-- Verified Icon -->
                                            <i class="ph-duotone ph-circle-wavy-check text-success"></i>
                                            <!-- Bootstrap Icon -->
                                        </span>
                                    </div>

                                    <div class="text-center mt-3">
                                        <div class="chat-avtar d-inline-flex mx-auto">
                                            <img class="rounded-circle img-fluid wid-70"
                                                src="{{ asset('storage/assets/images/user/venue.png') }}"
                                                alt="User image" />
                                        </div>
                                        <h5 class="mb-0">0102 - Gov Hr Sec School</h5>
                                        <p class="text-muted text-sm">Alandur-Chennai</p>
                                        <hr class="my-3 border border-secondary-subtle" />
                                        <div class="row g-3">
                                            <div class="col-4">
                                                <h5 class="mb-0">5</h5>
                                                <small class="text-muted">Exam</small>
                                            </div>
                                            <div class="col-4 border border-top-0 border-bottom-0">
                                                <h5 class="mb-0">3</h5>
                                                <small class="text-muted">CI</small>
                                            </div>
                                            <div class="col-4">
                                                <h5 class="mb-0">100</h5>
                                                <small class="text-muted">Count</small>
                                            </div>
                                        </div>
                                        <hr class="my-3 border border-secondary-subtle" />
                                        <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3">
                                            <i class="ti ti-mail me-2"></i>
                                            <p class="mb-0">ceochn@***.in</p>
                                        </div>
                                        <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3">
                                            <i class="ti ti-phone me-2"></i>
                                            <p class="mb-0">(+91) 9434***1212</p>
                                        </div>
                                        <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3">
                                            <i class="ti ti-phone-plus me-2"></i>
                                            <p class="mb-0">04434***1212</p>
                                        </div>
                                        <div
                                            class="d-inline-flex align-items-center justify-content-start w-100 mb-3 text-start">
                                            <i class="ti ti-map-pin me-2"></i>
                                            <p class="mb-0">Tamil Nadu Public Service Commission, TNPSC Road, Broadway,
                                                Chennai-600003.</p>
                                        </div>
                                        <div class="d-inline-flex align-items-center justify-content-start w-100">
                                            <i class="ti ti-link me-2"></i>
                                            <a href="#" class="link-primary">
                                                <p class="mb-0">https://chennai.nic.in/</p>
                                            </a>
                                        </div>
                                        <div class="d-inline-flex align-items-center justify-content-center mt-2 w-100">
                                            <a href="#"
                                                class="btn btn-success d-inline-flex  justify-content-center"><i
                                                    class="ti ti-current-location me-1"></i>Get Location</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8 col-xxl-9">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Details</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item px-0 pt-0">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p class="mb-1 text-muted">Venue Code Provider</p>
                                                    <p class="mb-0">Madras University</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p class="mb-1 text-muted">Category</p>
                                                    <p class="mb-0">Government</p>
                                                </div>

                                            </div>
                                        </li>
                                        <li class="list-group-item px-0">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p class="mb-1 text-muted">Type</p>
                                                    <p class="mb-0">School</p>
                                                </div>

                                                <div class="col-md-6">
                                                    <p class="mb-1 text-muted">Distance from Railway</p>
                                                    <p class="mb-0">8.2km</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item px-0">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p class="mb-1 text-muted">Distance from Treasury</p>
                                                    <p class="mb-0">1.2km</p>
                                                </div>
                                                {{-- <div class="col-md-6">
                                                    <p class="mb-1 text-muted">Zip Code</p>
                                                    <p class="mb-0">956 754</p>
                                                </div> --}}
                                            </div>
                                        </li>
                                        {{-- <li class="list-group-item px-0 pb-0">
                                            <p class="mb-1 text-muted">Address</p>
                                            <p class="mb-0">Street 110-B Kalians Bag, Dewan, M.P. New York</p>
                                        </li> --}}
                                    </ul>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h5>Bank Account Details</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item px-0 pt-0">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p class="mb-1 text-muted">Bank Name</p>
                                                    <p class="mb-0">State Bank Of India</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p class="mb-1 text-muted">Account Name</p>
                                                    <p class="mb-0">Gov Hr Sec School</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item px-0">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p class="mb-1 text-muted">Number</p>
                                                    <p class="mb-0">2312312312312</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p class="mb-1 text-muted">Branch Name</p>
                                                    <p class="mb-0">chennai</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item px-0 pb-0">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p class="mb-1 text-muted">Type</p>
                                                    <p class="mb-0">current</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p class="mb-1 text-muted">IFSC Code</p>
                                                    <p class="mb-0">SBI000123</p>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- [ Main Content ] end -->
    </div>

    @include('partials.footer')

    @include('partials.theme')

@endsection
