@extends('layouts.app')

@section('title', 'View District Collectorates')

@section('content')


    <div class="page-loader">
        <div class="bar"></div>
    </div>



    @include('partials.sidebar')



    @include('partials.header')


    <div class="pc-container">
        <div class="pc-content">

            <div class="row">
                <div class="tab-content">
                    <div class="row">
                        <div class="col-lg-4 col-xxl-3">
                            <div class="card">
                                <div class="card-body position-relative">
                                    <div class="position-absolute end-0 top-0 p-3">
                                        <span class="d-flex align-items-center">
                                            <span class="me-2">E-mail</span>
                                            <!-- Check the district_email_status -->
                                            @if ($center->district->district_email_status) <!-- Assuming $district contains the row data -->
                                                <i class="ph-duotone ph-circle-wavy-check text-success"></i>
                                            @else
                                                <i class="ti ti-alert-circle text-danger f-18"></i>
                                            @endif
                                        </span>
                                    </div>
                                    

                                    <div class="text-center mt-3">
                                        <div class="chat-avtar d-inline-flex mx-auto">
                                            <img alt="User image" src="{{ $center->district->district_image
                                            ? asset('storage/' . $center->district->district_image)
                                            : asset('storage/assets/images/user/collectorate.png') }}"
                                            id="previewImage" alt="Cropped Preview"
                                            class="rounded-circle img-fluid wid-70">
                                        </div>
                                        <h5 class="mb-0">{{ $center->district->district_name }} - {{ $center->district->district_code }}
                                        </h5>
                                        <p class="text-muted text-sm">District Collectorate</p>
                                        <hr class="my-3 border border-secondary-subtle">
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
                                                <h5 class="mb-0">45</h5>
                                                <small class="text-muted">Members</small>
                                            </div>
                                        </div>
                                        <hr class="my-3 border border-secondary-subtle">
                                        <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3">
                                            <i class="ti ti-mail me-2"></i>
                                            <p class="mb-0">{{ $center->district->district_email }}</p>
                                        </div>
                                        <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3">
                                            <i class="ti ti-phone me-2"></i>
                                            <p class="mb-0">{{ $center->district->district_phone }}</p>
                                        </div>
                                        <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3">
                                            <i class="ti ti-phone-plus me-2"></i>
                                            <p class="mb-0">{{ $center->district->district_alternate_phone }}</p>
                                        </div>
                                        <div
                                            class="d-inline-flex align-items-center justify-content-start w-100 mb-3 text-start">
                                            <i class="ti ti-map-pin me-2"></i>
                                            <p class="mb-0">{{ $center->district->district_address }}</p>
                                        </div>
                                        <div class="d-inline-flex align-items-center justify-content-start w-100">
                                            <i class="ti ti-link me-2"></i>
                                            <a class="link-primary" href="#">
                                                <p class="mb-0">{{ $center->district->district_website }}</p>
                                            </a>
                                        </div>
                                        <div class="d-inline-flex align-items-center justify-content-center mt-2 w-100">
                                            <a class="btn btn-success d-inline-flex  justify-content-center" href="#"
                                                onclick="openMap({{ $center->district->district_latitude }}, {{ $center->district->district_longitude }})">
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
                                            @if ($center->center_email_status) <!-- Assuming $district contains the row data -->
                                                <i class="ph-duotone ph-circle-wavy-check text-success"></i>
                                            @else
                                                <i class="ti ti-alert-circle text-danger f-18"></i>
                                            @endif
                                        </span>
                                    </div>
                                    

                                    <div class="text-center mt-3">
                                        <div class="chat-avtar d-inline-flex mx-auto">
                                            <img alt="User image" src="{{ $center->center_image
                                            ? asset('storage/' . $center->center_image)
                                            : asset('storage/assets/images/user/collectorate.png') }}"
                                            id="previewImage" alt="Cropped Preview"
                                            class="rounded-circle img-fluid wid-70">
                                        </div>
                                        <h5 class="mb-0">{{ $center->center_name }} - {{ $center->center_code }}
                                        </h5>
                                        <p class="text-muted text-sm">Center</p>
                                        <hr class="my-3 border border-secondary-subtle">
                                        <div class="row g-3">
                                            <div class="col-4">
                                                <h5 class="mb-0">86</h5>
                                                <small class="text-muted">Centers</small>
                                            </div>
                                            <div class="col-4 border border-top-0 border-bottom-0">
                                                <h5 class="mb-0">40</h5>
                                                <small class="text-muted">Venues</small>
                                            </div>
                                            <div class="col-4">
                                                <h5 class="mb-0">45</h5>
                                                <small class="text-muted">Members</small>
                                            </div>
                                        </div>
                                        <hr class="my-3 border border-secondary-subtle">
                                        <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3">
                                            <i class="ti ti-mail me-2"></i>
                                            <p class="mb-0">{{ $center->center_email }}</p>
                                        </div>
                                        <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3">
                                            <i class="ti ti-phone me-2"></i>
                                            <p class="mb-0">{{ $center->center_phone }}</p>
                                        </div>
                                        <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3">
                                            <i class="ti ti-phone-plus me-2"></i>
                                            <p class="mb-0">{{ $center->center_alternate_phone }}</p>
                                        </div>
                                        <div
                                            class="d-inline-flex align-items-center justify-content-start w-100 mb-3 text-start">
                                            <i class="ti ti-map-pin me-2"></i>
                                            <p class="mb-0">{{ $center->center_address }}</p>
                                        </div>
                                      
                                        <div class="d-inline-flex align-items-center justify-content-center mt-2 w-100">
                                            <a class="btn btn-success d-inline-flex  justify-content-center" href="#"
                                                onclick="openMap({{ $center->center_latitude }}, {{ $center->center_longitude }})">
                                                <i class="ti ti-map-2 me-1"></i>View Location
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-xxl-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Demo Videos</h5>
                                </div>
                                <div class="card-body pc-component">
                                    <div data-bs-ride="carousel" class="carousel slide carousel-fade"
                                        id="carouselExampleFade">
                                        <div class="carousel-inner">
                                            <div class="carousel-item active">
                                                <video controls="" class="img-fluid d-block w-100">
                                                    <source type="video/mp4" src="../assets/videos/video-1.mp4">
                                                    Your browser does not support the video tag.
                                                </video>
                                            </div>
                                            {{-- <div class="carousel-item">
                                            <video controls="" class="img-fluid d-block w-100">
                                                <source type="video/mp4" src="../assets/videos/video-2.mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        </div> --}}
                                            {{-- <div class="carousel-item">
                                            <video controls="" class="img-fluid d-block w-100">
                                                <source type="video/mp4" src="../assets/videos/video-3.mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        </div> --}}
                                        </div>
                                        {{-- <a data-bs-slide="prev" role="button" href="#carouselExampleFade" class="carousel-control-prev">
                                        <span aria-hidden="true" class="carousel-control-prev-icon"></span>
                                        <span class="sr-only">Previous</span>
                                    </a> --}}
                                        {{-- <a data-bs-slide="next" role="button" href="#carouselExampleFade" class="carousel-control-next">
                                        <span aria-hidden="true" class="carousel-control-next-icon"></span>
                                        <span class="sr-only">Next</span>
                                    </a> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @include('partials.footer')

    @include('partials.theme')
   
@endsection
