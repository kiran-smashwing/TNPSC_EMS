@extends('layouts.app')

@section('title', 'View CI Assistants')

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
                        <div class="col-lg-4 col-xxl-4">
                            <div class="card">
                                <div class="card-body position-relative">
                                    <div class="position-absolute end-0 top-0 p-3">
                                        <span class="d-flex align-items-center">
                                            <span class="me-2">E-mail</span>
                                            <!-- Check the district_email_status -->
                                            @if ($ciAssistant->district->district_email_status)
                                                <!-- Assuming $district contains the row data -->
                                                <i class="ph-duotone ph-circle-wavy-check text-success"></i>
                                            @else
                                                <i class="ti ti-alert-circle text-danger f-18"></i>
                                            @endif
                                        </span>
                                    </div>

                                    <div class="text-center mt-3">
                                        <div class="chat-avtar d-inline-flex mx-auto">
                                            <img class="rounded-circle img-fluid wid-70"
                                                src="{{ $ciAssistant->district->district_image
                                                    ? asset('storage/' . $ciAssistant->district->district_image)
                                                    : asset('storage/assets/images/user/collectorate.png') }}"
                                                alt="District image" />
                                        </div>
                                        <h5 class="mb-0">{{ $ciAssistant->district->district_code }} -
                                            {{ $ciAssistant->district->district_name }}</h5>
                                        <p class="text-muted text-sm">District Collectorate</p>
                                        <hr class="my-3 border border-secondary-subtle" />
                                        <div class="row g-3">
                                            <div class="col-4">
                                                <h5 class="mb-0">{{$centerCount}}</h5>
                                                <small class="text-muted">Centers</small>
                                            </div>
                                            <div class="col-4 border border-top-0 border-bottom-0">
                                                <h5 class="mb-0">{{$venueCount}}</h5>
                                                <small class="text-muted">Venues</small>
                                            </div>
                                            <div class="col-4">
                                                <h5 class="mb-0">{{$staffCount}}</h5>
                                                <small class="text-muted">Officers</small>
                                            </div>
                                        </div>
                                        <hr class="my-3 border border-secondary-subtle" />
                                        <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3">
                                            <i class="ti ti-mail me-2"></i>
                                            <p class="mb-0">{{ $ciAssistant->district->district_email }}</p>
                                        </div>
                                        <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3">
                                            <i class="ti ti-phone me-2"></i>
                                            <p class="mb-0">{{ $ciAssistant->district->district_phone }}</p>
                                        </div>
                                        <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3">
                                            <i class="ti ti-phone-plus me-2"></i>
                                            <p class="mb-0">{{ $ciAssistant->district->district_alternate_phone }}</p>
                                        </div>
                                        <div
                                            class="d-inline-flex align-items-center justify-content-start w-100 mb-3 text-start">
                                            <i class="ti ti-map-pin me-2"></i>
                                            <p class="mb-0">{{ $ciAssistant->district->district_address }}</p>
                                        </div>
                                        <div class="d-inline-flex align-items-center justify-content-start w-100">
                                            <i class="ti ti-link me-2"></i>
                                            <a href="#" class="link-primary">
                                                <p class="mb-0">{{ $ciAssistant->district->district_website }}</p>
                                            </a>
                                        </div>
                                        <div class="d-inline-flex align-items-center justify-content-center mt-2 w-100">
                                            <a href="#"
                                                onclick="openMap({{ $ciAssistant->district->district_latitude }}, {{ $ciAssistant->district->district_longitude }})"
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
                                            <span class="me-2">E-mail</span>
                                            <!-- Check the district_email_status -->
                                            @if ($ciAssistant->venue->venue_email_status) <!-- Assuming $district contains the row data -->
                                                <i class="ph-duotone ph-circle-wavy-check text-success"></i>
                                            @else
                                                <i class="ti ti-alert-circle text-danger f-18"></i>
                                            @endif
                                        </span>
                                    </div>

                                    <div class="text-center mt-4">
                                        <div class="chat-avtar d-inline-flex mx-auto">
                                            <img class="rounded-circle img-fluid wid-70"
                                                src="{{ $ciAssistant->venue->venue_image
                                                    ? asset('storage/' . $ciAssistant->venue->venue_image)
                                                    : asset('storage/assets/images/user/collectorate.png') }}"
                                                alt="Venue image" />
                                        </div>
                                        <h5 class="mb-0">{{ $ciAssistant->venue->venue_code }} -
                                            {{ $ciAssistant->venue->venue_name }}</h5>
                                        <p class="text-muted text-sm">Venues</p>
                                        <hr class="my-3 border border-secondary-subtle" />
                                        <div class="row g-3">
                                            <div class="col-4">
                                                <h5 class="mb-0">{{$ci_count}}</h5>
                                                <small class="text-muted">Cheif Invigilators</small>
                                            </div>
                                            <div class="col-4 border border-top-0 border-bottom-0">
                                                <h5 class="mb-0">{{$invigilator_count}}</h5>
                                                <small class="text-muted">Invigilators</small>
                                            </div>
                                            <div class="col-4">
                                                <h5 class="mb-0">{{$cia_count}}</h5>
                                                <small class="text-muted">CI Assistants</small>
                                            </div>
                                        </div>
                                        <hr class="my-3 border border-secondary-subtle" />
                                        <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3">
                                            <i class="ti ti-mail me-2"></i>
                                            <p class="mb-0">{{ $ciAssistant->venue->venue_email }}</p>
                                        </div>
                                        <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3">
                                            <i class="ti ti-phone me-2"></i>
                                            <p class="mb-0">{{ $ciAssistant->venue->venue_phone }}</p>
                                        </div>
                                        <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3">
                                            <i class="ti ti-phone-plus me-2"></i>
                                            <p class="mb-0">{{ $ciAssistant->venue->venue_alternative_phone }}</p>
                                        </div>
                                        <div
                                            class="d-inline-flex align-items-center justify-content-start w-100 mb-3 text-start">
                                            <i class="ti ti-map-pin me-2"></i>
                                            <p class="mb-0">{{ $ciAssistant->venue->venue_address }}</p>
                                        </div>
                                        <div class="d-inline-flex align-items-center justify-content-center mt-2 w-100">
                                            <a href="#"
                                                onclick="openMap({{ $ciAssistant->venue->venue_latitude }}, {{ $ciAssistant->venue->venue_longitude }})"
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
                                        {{-- <span class="d-flex align-items-center">
                                            <span class="me-2">E-mail</span>
                                            <!-- Check the district_email_status -->
                                            @if ($ciAssistant->cia_email_status) <!-- Assuming $district contains the row data -->
                                                <i class="ph-duotone ph-circle-wavy-check text-success"></i>
                                            @else
                                                <i class="ti ti-alert-circle text-danger f-18"></i>
                                            @endif
                                        </span> --}}
                                    </div>

                                    <div class="text-center mt-3">
                                        <div class="chat-avtar d-inline-flex mx-auto">
                                            <img class="rounded-circle img-fluid wid-70"
                                                src="{{ $ciAssistant->cia_image
                                                    ? asset('storage/' . $ciAssistant->cia_image)
                                                    : asset('storage/assets/images/user/avatar-4.jpg') }}"
                                                alt="Venue image" />
                                        </div>
                                        <h5 class="mb-0">{{ $ciAssistant->cia_name }}</h5>
                                        <p class="text-muted text-sm">{{ $ciAssistant->cia_designation }}</p>
                                        <hr class="my-3 border border-secondary-subtle" />
                                        {{-- <div class="row g-3">
                                            <div class="col-4">
                                                <h5 class="mb-0">4</h5>
                                                <small class="text-muted">Exams</small>
                                            </div>
                                            <div class="col-4 border border-top-0 border-bottom-0">
                                                <h5 class="mb-0">4</h5>
                                                <small class="text-muted">Invigilator</small>
                                            </div>
                                            <div class="col-4">
                                                <h5 class="mb-0">10</h5>
                                                <small class="text-muted">Members</small>
                                            </div>
                                        </div> --}}
                                        <hr class="my-3 border border-secondary-subtle" />
                                        <div
                                            class="d-inline-flex align-items-center justify-content-start w-100 mb-3 text-start">
                                            <i class="ti ti-map-pin me-2"></i>
                                            <p class="mb-0">{{ $ciAssistant->center->center_code }} -
                                                {{ $ciAssistant->center->center_name }}</p>
                                        </div>
                                        {{-- <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3">
                                            <i class="ti ti-mail me-2"></i>
                                            <p class="mb-0">{{ $ciAssistant->cia_email }}</p>
                                        </div> --}}
                                        <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3">
                                            <i class="ti ti-phone me-2"></i>
                                            <p class="mb-0">{{ $ciAssistant->cia_phone }}</p>
                                        </div>
                                        <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3">
                                            <i class="ti ti-building me-2"></i>
                                            <p class="mb-0">{{ $ciAssistant->venue->venue_name }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8 col-xxl-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>User Guide Video - CI Assistants</h5>
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
