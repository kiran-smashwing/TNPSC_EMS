@extends('layouts.app')

@section('title', 'View Treasury Officers')

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
                                            <span class="me-2">E-mail</span>
                                            <!-- Check if district exists before accessing district_email_status -->
                                            @if ($treasuryOfficer->district)
                                                @if ($treasuryOfficer->district->district_email_status)
                                                    <i class="ph-duotone ph-circle-wavy-check text-success"></i>
                                                @else
                                                    <i class="ti ti-alert-circle text-danger f-18"></i>
                                                @endif
                                            @else
                                            <i class="ti ti-alert-circle text-danger f-18"></i>
                                            @endif
                                        </span>

                                    </div>


                                    <div class="text-center mt-3">
                                        <div class="chat-avtar d-inline-flex mx-auto">
                                            <img alt="User image"
                                                src="{{ $treasuryOfficer->district && $treasuryOfficer->district->district_image
                                                    ? asset('storage/' . $treasuryOfficer->district->district_image)
                                                    : asset('storage/assets/images/user/collectorate.png') }}"
                                                id="previewImage" class="rounded-circle img-fluid wid-70">

                                        </div>
                                        <h5 class="mb-0">
                                            {{ $treasuryOfficer->district
                                                ? $treasuryOfficer->district->district_name . ' - ' . $treasuryOfficer->district->district_code
                                                : 'N/A' }}
                                        </h5>
                                        <p class="text-muted text-sm">District Collectorate</p>
                                        <hr class="my-3 border border-secondary-subtle" />
                                        <div class="row g-3">
                                            <div class="col-4">
                                                <h5 class="mb-0">{{ $centerCount }}</h5>
                                                <small class="text-muted">Centers</small>
                                            </div>
                                            <div class="col-4 border border-top-0 border-bottom-0">
                                                <h5 class="mb-0">{{ $venueCount }}</h5>
                                                <small class="text-muted">Venues</small>
                                            </div>
                                            <div class="col-4">
                                                <h5 class="mb-0">{{ $staffCount }}</h5>
                                                <small class="text-muted">Members</small>
                                            </div>
                                        </div>
                                        <hr class="my-3 border border-secondary-subtle">
                                        <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3">
                                            <i class="ti ti-mail me-2"></i>
                                            <p class="mb-0">
                                                {{ $treasuryOfficer->district ? $treasuryOfficer->district->district_email : 'N/A' }}
                                            </p>
                                        </div>
                                        <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3">
                                            <i class="ti ti-phone me-2"></i>
                                            <p class="mb-0">
                                                {{ $treasuryOfficer->district ? $treasuryOfficer->district->district_phone : 'N/A' }}
                                            </p>
                                        </div>
                                        <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3">
                                            <i class="ti ti-phone-plus me-2"></i>
                                            <p class="mb-0">
                                                {{ $treasuryOfficer->district ? $treasuryOfficer->district->district_alternate_phone : 'N/A' }}
                                            </p>
                                        </div>
                                        <div
                                            class="d-inline-flex align-items-center justify-content-start w-100 mb-3 text-start">
                                            <i class="ti ti-map-pin me-2"></i>
                                            <p class="mb-0">
                                                {{ $treasuryOfficer->district ? $treasuryOfficer->district->district_address : 'N/A' }}
                                            </p>
                                        </div>
                                        <div class="d-inline-flex align-items-center justify-content-start w-100">
                                            <i class="ti ti-link me-2"></i>
                                            <a class="link-primary" href="#">
                                                <p class="mb-0">
                                                    {{ $treasuryOfficer->district ? $treasuryOfficer->district->district_website : 'N/A' }}
                                                </p>
                                            </a>
                                        </div>
                                        <div class="d-inline-flex align-items-center justify-content-center mt-2 w-100">
                                            <a class="btn btn-success d-inline-flex  justify-content-center" href="#"
                                                onclick="openMap({{ $treasuryOfficer->district
                                                    ? $treasuryOfficer->district->district_latitude . ', ' . $treasuryOfficer->district->district_longitude
                                                    : 'N/A' }}
                    )">
                                                <i class="ti ti-map-2 me-1"></i>View Location
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-xxl-3">
                            <div class="card">
                                <div class="card-body position-relative">
                                    <div class="position-absolute end-0 top-0 p-3">
                                        <span class="d-flex align-items-center">
                                            <span class="me-2">E-mail</span>
                                            <!-- Check the district_email_status -->
                                            @if ($treasuryOfficer->tre_off_email_status)
                                                <!-- Assuming $district contains the row data -->
                                                <i class="ph-duotone ph-circle-wavy-check text-success"></i>
                                            @else
                                                <i class="ti ti-alert-circle text-danger f-18"></i>
                                            @endif
                                        </span>
                                    </div>


                                    <div class="text-center mt-3">
                                        <div class="chat-avtar d-inline-flex mx-auto">
                                            <img alt="User image"
                                                src="{{ $treasuryOfficer->tre_off_image
                                                    ? asset('storage/' . $treasuryOfficer->tre_off_image)
                                                    : asset('storage/assets/images/user/avatar-4.jpg') }}"
                                                id="previewImage" alt="Cropped Preview"
                                                class="rounded-circle img-fluid wid-70">

                                        </div>
                                        <h5 class="mb-0">{{ $treasuryOfficer->tre_off_name }}</h5>
                                        <p class="text-muted text-sm">{{ $treasuryOfficer->tre_off_designation }}</p>
                                        <hr class="my-3 border border-secondary-subtle" />
                                        {{-- <div class="row g-3">
                                  <div class="col-4">
                                    <h5 class="mb-0">8</h5>
                                    <small class="text-muted">Exams</small>
                                  </div>
                                  <div class="col-4 border border-top-0 border-bottom-0">
                                    <h5 class="mb-0">40</h5>
                                    <small class="text-muted">Venues</small>
                                  </div>
                                  <div class="col-4">
                                    <h5 class="mb-0">45</h5>
                                    <small class="text-muted">Members</small>
                                  </div>
                                </div> --}}
                                        <hr class="my-3 border border-secondary-subtle" />
                                        <div
                                            class="d-inline-flex align-items-center justify-content-start w-100 mb-3 text-start">
                                            <i class="ti ti-map-pin me-2"></i>
                                            <p class="mb-0">
                                                {{ $treasuryOfficer->district
                                                    ? $treasuryOfficer->district->district_code . ' - ' . $treasuryOfficer->district->district_name
                                                    : 'N/A' }}
                                            </p>
                                        </div>
                                        <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3">
                                            <i class="ti ti-mail me-2"></i>
                                            <p class="mb-0">{{ $treasuryOfficer->tre_off_email }}</p>
                                        </div>
                                        <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3">
                                            <i class="ti ti-phone me-2"></i>
                                            <p class="mb-0">{{ $treasuryOfficer->tre_off_phone }}</p>
                                        </div>
                                        <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3">
                                            <i class="ti ti-barcode me-2"></i>
                                            <p class="mb-0">{{ $treasuryOfficer->tre_off_employeeid }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- <div class="col-lg-8 col-xxl-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>User Guide Video - Treasury Officer</h5>
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
                        </div> -->
                    </div>
                </div>
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>

    @include('partials.footer')

    @include('partials.theme')

@endsection
