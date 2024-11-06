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
                                        <span class="d-flex align-items-center"></span>
                                    </div>

                                    <div class="text-center mt-3">
                                        <div class="chat-avtar d-inline-flex mx-auto">
                                            <img class="rounded-circle img-fluid wid-70"
                                                src="{{ $district->district_image
                                                    ? asset('storage/' . $district->district_image)
                                                    : asset('storage/assets/images/user/collectorate.png') }}"
                                                alt="District image" />
                                        </div>
                                        <h5 class="mb-0">{{ $district->district_code }} - {{ $district->district_name }}
                                        </h5>
                                        <p class="text-muted text-sm">{{ $district->district_type }}</p>
                                        <hr class="my-3 border border-secondary-subtle" />
                                        <div class="row g-3">
                                            <div class="col-4">
                                                <h5 class="mb-0">{{ $district->centers_count }}</h5>
                                                <small class="text-muted">Centers</small>
                                            </div>
                                            <div class="col-4 border border-top-0 border-bottom-0">
                                                <h5 class="mb-0">{{ $district->venues_count }}</h5>
                                                <small class="text-muted">Venues</small>
                                            </div>
                                            <div class="col-4">
                                                <h5 class="mb-0">{{ $district->members_count }}</h5>
                                                <small class="text-muted">Members</small>
                                            </div>
                                        </div>
                                        <hr class="my-3 border border-secondary-subtle" />
                                        <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3">
                                            <i class="ti ti-mail me-2"></i>
                                            <p class="mb-0">{{ $district->district_email }}</p>
                                        </div>
                                        <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3">
                                            <i class="ti ti-phone me-2"></i>
                                            <p class="mb-0">{{ $district->district_phone }}</p>
                                        </div>
                                        <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3">
                                            <i class="ti ti-phone-plus me-2"></i>
                                            <p class="mb-0">{{ $district->district_alternate_phone }}</p>
                                        </div>
                                        <div
                                            class="d-inline-flex align-items-center justify-content-start w-100 mb-3 text-start">
                                            <i class="ti ti-map-pin me-2"></i>
                                            <p class="mb-0">{{ $district->district_address }}</p>
                                        </div>
                                        <div class="d-inline-flex align-items-center justify-content-start w-100">
                                            <i class="ti ti-link me-2"></i>
                                            <a href="#" class="link-primary">
                                                <p class="mb-0">{{ $district->district_website }}</p>
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
                        <div class="col-lg-4 col-xxl-4">
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
                                                src="{{ $venue->venue_image
                                                    ? asset('storage/' . $venue->venue_image)
                                                    : asset('storage/assets/images/user/collectorate.png') }}"
                                                alt="Venue image" />
                                        </div>
                                        <h5 class="mb-0">{{ $venue->venue_code }} - {{ $venue->venue_name }}</h5>
                                        <p class="text-muted text-sm">Venues</p>
                                        <hr class="my-3 border border-secondary-subtle" />
                                        <div class="row g-3">
                                            <div class="col-4">
                                                <h5 class="mb-0">2</h5>
                                                <small class="text-muted">Cheif Invigilators</small>
                                            </div>
                                            <div class="col-4 border border-top-0 border-bottom-0">
                                                <h5 class="mb-0">4</h5>
                                                <small class="text-muted">Invigilators</small>
                                            </div>
                                            <div class="col-4">
                                                <h5 class="mb-0">3</h5>
                                                <small class="text-muted">CI Assistants</small>
                                            </div>
                                        </div>
                                        <hr class="my-3 border border-secondary-subtle" />
                                        <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3">
                                            <i class="ti ti-mail me-2"></i>
                                            <p class="mb-0">{{ $venue->venue_email }}</p>
                                        </div>
                                        <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3">
                                            <i class="ti ti-phone me-2"></i>
                                            <p class="mb-0">{{ $venue->venue_phone }}</p>
                                        </div>
                                        <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3">
                                            <i class="ti ti-phone-plus me-2"></i>
                                            <p class="mb-0">{{ $venue->venue_alternative_phone }}</p>
                                        </div>
                                        <div
                                            class="d-inline-flex align-items-center justify-content-start w-100 mb-3 text-start">
                                            <i class="ti ti-map-pin me-2"></i>
                                            <p class="mb-0">{{ $venue->venue_address }}</p>
                                        </div>
                                        <div class="d-inline-flex align-items-center justify-content-start w-100">
                                            <i class="ti ti-link me-2"></i>
                                            <a href="#" class="link-primary">
                                                <p class="mb-0">{{ $venue->venue_website }}</p>
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

                        <div class="col-lg-8 col-xxl-5">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Details</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row align-items-center mb-3">
                                        <div class="col-sm-6 mb-2 mb-sm-0">
                                            <p class="mb-0">Center Name</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1 me-3">
                                                    <p class="mb-0 text-muted"> {{ $center->center_code }} - {{ $center->center_name }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row align-items-center mb-3">
                                        <div class="col-sm-6 mb-2 mb-sm-0">
                                            <p class="mb-0">Venue Code Provider</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1 me-3">
                                                    <p class="mb-0 text-muted">{{ $venue->venue_codeprovider }}</p>
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
                                                    <p class="mb-0 text-muted">{{ $venue->venue_category }}</p>
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
                                                    <p class="mb-0 text-muted">{{ $venue->venue_type }}</p>
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
                                                    <p class="mb-0 text-muted">{{ $venue->venue_distance_railway }}</p>
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
                                                    <p class="mb-0 text-muted">{{ $venue->venue_treasury_office }}</p>
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
                                                    <p class="mb-0 text-muted">{{ $venue->venue_bank_name }}</p>
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
                                                    <p class="mb-0 text-muted">{{ $venue->venue_account_name }}</p>
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
                                                    <p class="mb-0 text-muted">{{ $venue->venue_account_number }}</p>
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
                                                    <p class="mb-0 text-muted">{{ $venue->venue_branch_name }}</p>
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
                                                    <p class="mb-0 text-muted">{{ $venue->venue_account_type }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row align-items-center mb-3">
                                        <div class="col-sm-6 mb-2 mb-sm-0">
                                            <p class="mb-0">IFSC Code</p>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1 me-3">
                                                    <p class="mb-0 text-muted">{{ $venue->venue_ifsc }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8 col-xxl-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Demo Videos</h5>
                                </div>
                                <div class="card-body pc-component">
                                    <div id="carouselExampleFade" class="carousel slide carousel-fade"
                                        data-bs-ride="carousel">
                                        <div class="carousel-inner">
                                            <div class="carousel-item active">
                                                <video class="img-fluid d-block w-100" controls>
                                                    <source src="../assets/videos/video-1.mp4" type="video/mp4">
                                                    Your browser does not support the video tag.
                                                </video>
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
    </div>
    @include('partials.footer')

    @include('partials.theme')

@endsection
