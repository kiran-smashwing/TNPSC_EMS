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
                                                    class="ti ti-map-2 me-1"></i>View Location</a>
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
                                    {{-- <div class="row align-items-center mb-3">
                                        <div class="col-sm-6 mb-2 mb-sm-0">
                                            <p class="mb-0"><strong>Venue Code Provider</strong> : <span class="m-l-15 religion">Madras University</span></p>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1 me-3">
                                                    <p class="align-items-center text-muted"></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}
                                    <div class="row align-items-center mb-3">
                                        <div class="col-sm-6 mb-2 mb-sm-0">
                                            <p class="mb-0">Venue Code Provider</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1 me-3">
                                                    <p class="mb-0 text-muted">Madras University</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row align-items-center mb-3">
                                        <div class="col-sm-6 mb-2 mb-sm-0">
                                            <p class="mb-0">Category</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1 me-3">
                                                    <p class="mb-0 text-muted">Government</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row align-items-center mb-3">
                                        <div class="col-sm-6 mb-2 mb-sm-0">
                                            <p class="mb-0">Type</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1 me-3">
                                                    <p class="mb-0 text-muted">School</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row align-items-center mb-3">
                                        <div class="col-sm-6 mb-2 mb-sm-0">
                                            <p class="mb-0">Distance from Railway</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1 me-3">
                                                    <p class="mb-0 text-muted">8.2km</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row align-items-center mb-3">
                                        <div class="col-sm-6 mb-2 mb-sm-0">
                                            <p class="mb-0">Distance from Treasury</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1 me-3">
                                                    <p class="mb-0 text-muted">1.2km</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row align-items-center mb-3">
                                        <div class="col-sm-6 mb-2 mb-sm-0">
                                            <p class="mb-0">Bank Name</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1 me-3">
                                                    <p class="mb-0 text-muted">State Bank Of India</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row align-items-center mb-3">
                                        <div class="col-sm-6 mb-2 mb-sm-0">
                                            <p class="mb-0">Account Name</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1 me-3">
                                                    <p class="mb-0 text-muted">Gov Hr Sec School</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row align-items-center mb-3">
                                        <div class="col-sm-6 mb-2 mb-sm-0">
                                            <p class="mb-0">Number</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1 me-3">
                                                    <p class="mb-0 text-muted">2312312312312</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row align-items-center mb-3">
                                        <div class="col-sm-6 mb-2 mb-sm-0">
                                            <p class="mb-0">Branch Name</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1 me-3">
                                                    <p class="mb-0 text-muted">chennai</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row align-items-center mb-3">
                                        <div class="col-sm-6 mb-2 mb-sm-0">
                                            <p class="mb-0">Type</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1 me-3">
                                                    <p class="mb-0 text-muted">current</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row align-items-center mb-3">
                                        <div class="col-sm-6 mb-2 mb-sm-0">
                                            <p class="mb-0">IFSC Code</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1 me-3">
                                                    <p class="mb-0 text-muted">SBI000123</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
