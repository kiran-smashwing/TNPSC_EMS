@extends('layouts.app')

@section('title', 'View Department Officials')

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
                                                src="{{ asset('storage/assets/images/user/avatar-4.jpg') }}"
                                                alt="User image" />
                                        </div>
                                        <h5 class="mb-0">Nanmaran</h5>
                                        <p class="text-muted text-sm">Thasildar</p>
                                        <hr class="my-3 border border-secondary-subtle" />
                                        <div class="row g-3">
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
                                        </div>
                                        <hr class="my-3 border border-secondary-subtle" />
                                        <div
                                            class="d-inline-flex align-items-center justify-content-start w-100 mb-3 text-start">
                                            <i class="ti ti-man me-2"></i>
                                            <p class="mb-0">APD - role</p>
                                        </div>
                                        <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3">
                                            <i class="ti ti-mail me-2"></i>
                                            <p class="mb-0">ceochn@***.in</p>
                                        </div>
                                        <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3">
                                            <i class="ti ti-phone me-2"></i>
                                            <p class="mb-0">(+91) 9434***1212</p>
                                        </div>
                                        <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3">
                                            <i class="ti ti-barcode me-2"></i>
                                            <p class="mb-0">EMP1234</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8 col-xxl-9">
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
                                            {{-- <div class="carousel-item">
                                            <video class="img-fluid d-block w-100" controls>
                                                <source src="../assets/videos/video-2.mp4" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        </div> --}}
                                            {{-- <div class="carousel-item">
                                            <video class="img-fluid d-block w-100" controls>
                                                <source src="../assets/videos/video-3.mp4" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        </div> --}}
                                        </div>
                                        {{-- <a class="carousel-control-prev" href="#carouselExampleFade" role="button" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Previous</span>
                                    </a> --}}
                                        {{-- <a class="carousel-control-next" href="#carouselExampleFade" role="button" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Next</span>
                                    </a> --}}
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
