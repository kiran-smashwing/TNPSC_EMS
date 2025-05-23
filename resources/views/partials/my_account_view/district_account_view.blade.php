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
                                @if ($district->district_email_status)
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
                                    src="{{ $district->district_image
                                        ? asset('storage/' . $district->district_image)
                                        : asset('storage/assets/images/user/collectorate.png') }}"
                                    id="previewImage" alt="Cropped Preview" class="rounded-circle img-fluid wid-70">
                            </div>
                            <h5 class="mb-0">{{ $district->district_name }} - {{ $district->district_code }}
                            </h5>
                            <p class="text-muted text-sm">District Collectorate</p>
                            <hr class="my-3 border border-secondary-subtle">
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
                                    <small class="text-muted">Officers</small>
                                </div>
                            </div>
                            <hr class="my-3 border border-secondary-subtle">
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
                            <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3 text-start">
                                <i class="ti ti-map-pin me-2"></i>
                                <p class="mb-0">{{ $district->district_address }}</p>
                            </div>
                            <div class="d-inline-flex align-items-center justify-content-start w-100">
                                <i class="ti ti-link me-2"></i>
                                <a class="link-primary" href="#">
                                    <p class="mb-0">{{ $district->district_website }}</p>
                                </a>
                            </div>
                            <div class="d-inline-flex align-items-center justify-content-center mt-2 w-100">
                                <a class="btn btn-success d-inline-flex  justify-content-center" href="#"
                                    onclick="openMap({{ $district->district_latitude }}, {{ $district->district_longitude }})">
                                    <i class="ti ti-map-2 me-1"></i>View Location
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="col-lg-8 col-xxl-9">
                <div class="card">
                    {{-- <div class="card-header">
                        <h5>User Guide Video - District Collectorate</h5>
                    </div>
                    <div class="card-body pc-component">
                        <div data-bs-ride="carousel" class="carousel slide carousel-fade" id="carouselExampleFade">
                            <div class="carousel-inner">
                                <div class="carousel-item active text-center p-4">
                                    <p>
                                        User Guide Document - 
                                        <a href="{{ asset('storage/user_guide/TNPSC EMS-DIstrict Module.pdf') }}" target="_blank">Click Here</a>
                                    </p>
                                </div>
                             </div>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
</div>
