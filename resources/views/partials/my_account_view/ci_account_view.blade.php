<div class="row">
    <div class="col-lg-4 col-xxl-4">
        <div class="card">
            <div class="card-body position-relative">
                <div class="position-absolute end-0 top-0 p-3">
                    <span class="d-flex align-items-center">
                        <span class="me-2">E-mail</span>
                        <!-- Check the district_email_status -->
                        @if ($chiefInvigilator->district->district_email_status)
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
                            src="{{ $chiefInvigilator->district->district_image
                                ? asset('storage/' . $chiefInvigilator->district->district_image)
                                : asset('storage/assets/images/user/collectorate.png') }}"
                            id="previewImage" alt="Cropped Preview" class="rounded-circle img-fluid wid-70">
                    </div>
                    <h5 class="mb-0">{{ $chiefInvigilator->district->district_name }} -
                        {{ $chiefInvigilator->district->district_code }}
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
                        <p class="mb-0">{{ $chiefInvigilator->district->district_email }}</p>
                    </div>
                    <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3">
                        <i class="ti ti-phone me-2"></i>
                        <p class="mb-0">{{ $chiefInvigilator->district->district_phone }}</p>
                    </div>
                    <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3">
                        <i class="ti ti-phone-plus me-2"></i>
                        <p class="mb-0">{{ $chiefInvigilator->district->district_alternate_phone }}</p>
                    </div>
                    <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3 text-start">
                        <i class="ti ti-map-pin me-2"></i>
                        <p class="mb-0">{{ $chiefInvigilator->district->district_address }}</p>
                    </div>
                    <div class="d-inline-flex align-items-center justify-content-start w-100">
                        <i class="ti ti-link me-2"></i>
                        <a class="link-primary" href="#">
                            <p class="mb-0">{{ $chiefInvigilator->district->district_website }}</p>
                        </a>
                    </div>
                    <div class="d-inline-flex align-items-center justify-content-center mt-2 w-100">
                        <a class="btn btn-success d-inline-flex  justify-content-center" href="#"
                            onclick="openMap({{ $chiefInvigilator->district->district_latitude }}, {{ $chiefInvigilator->district->district_longitude }})">
                            <i class="ti ti-map-2 me-1"></i>View Location
                        </a>
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
                        <span class="d-flex align-items-center">
                            <span class="me-2">E-mail</span>
                            <!-- Check the district_email_status -->
                            @if ($chiefInvigilator->venue->venue_email_status)
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
                            src="{{ $chiefInvigilator->venue->venue_image
                                ? asset('storage/' . $chiefInvigilator->venue->venue_image)
                                : asset('storage/assets/images/user/venue.png') }}"
                            alt="Venue image" />
                    </div>
                    <h5 class="mb-0">{{ $chiefInvigilator->venue->venue_code }} -
                        {{ $chiefInvigilator->venue->venue_name }}</h5>
                    <p class="text-muted text-sm">Venues</p>
                    <hr class="my-3 border border-secondary-subtle" />
                    <div class="row g-3">
                        <div class="col-4">
                            <h5 class="mb-0">{{ $ci_count }}</h5>
                            <small class="text-muted">Cheif Invigilators</small>
                        </div>
                        <div class="col-4 border border-top-0 border-bottom-0">
                            <h5 class="mb-0">{{ $invigilator_count }}</h5>
                            <small class="text-muted">Invigilators</small>
                        </div>
                        <div class="col-4">
                            <h5 class="mb-0">{{ $cia_count }}</h5>
                            <small class="text-muted">CI Assistants</small>
                        </div>
                    </div>
                    <hr class="my-3 border border-secondary-subtle" />
                    <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3">
                        <i class="ti ti-mail me-2"></i>
                        <p class="mb-0">{{ $chiefInvigilator->venue->venue_email }}</p>
                    </div>
                    <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3">
                        <i class="ti ti-phone me-2"></i>
                        <p class="mb-0">{{ $chiefInvigilator->venue->venue_phone }}</p>
                    </div>
                    <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3">
                        <i class="ti ti-phone-plus me-2"></i>
                        <p class="mb-0">{{ $chiefInvigilator->venue->venue_alternative_phone }}</p>
                    </div>
                    <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3 text-start">
                        <i class="ti ti-map-pin me-2"></i>
                        <p class="mb-0">{{ $chiefInvigilator->venue->venue_address }}</p>
                    </div>
                    <div class="d-inline-flex align-items-center justify-content-start w-100">
                        <i class="ti ti-link me-2"></i>
                        <a href="#" class="link-primary">
                            <p class="mb-0">{{ $chiefInvigilator->venue->venue_website }}</p>
                        </a>
                    </div>
                    <div class="d-inline-flex align-items-center justify-content-center mt-2 w-100">
                        <a href="#"
                            onclick="openMap({{ $chiefInvigilator->venue->venue_latitude }}, {{ $chiefInvigilator->venue->venue_longitude }})"
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
                        @if ($chiefInvigilator->ci_email_status)
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
                            src="{{ $chiefInvigilator->ci_image
                                ? asset('storage/' . $chiefInvigilator->ci_image)
                                : asset('storage/assets/images/user/avatar-4.jpg') }}"
                            alt="Venue image" />
                    </div>
                    <h5 class="mb-0">{{ $chiefInvigilator->ci_name }}</h5>
                    <p class="text-muted text-sm">{{ $chiefInvigilator->ci_designation }}</p>
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
                    <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3 text-start">
                        <i class="ti ti-map-pin me-2"></i>
                        <p class="mb-0">{{ $chiefInvigilator->center->center_code }} -
                            {{ $chiefInvigilator->center->center_name }}</p>
                    </div>
                    <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3">
                        <i class="ti ti-mail me-2"></i>
                        <p class="mb-0">{{ $chiefInvigilator->ci_email }}</p>
                    </div>
                    <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3">
                        <i class="ti ti-phone me-2"></i>
                        <p class="mb-0">{{ $chiefInvigilator->ci_phone }}</p>
                    </div>
                    {{-- <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3">
                        <i class="ti ti-phone-plus me-2"></i>
                        <p class="mb-0">{{ $chiefInvigilator->ci_alternative_phone }}</p>
                    </div> --}}
                    <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3">
                        <i class="ti ti-building me-2"></i>
                        <p class="mb-0">{{ $chiefInvigilator->venue->venue_name }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="col-lg-8 col-xxl-12">
        <div class="card">
            <div class="card-header">
                <h5>User Guide Video - Chief Invigilator</h5>
            </div>
            <div class="card-body pc-component">
                <div id="carouselExampleFade" class="carousel slide carousel-fade" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active text-center p-4">
                            <p>
                                User Guide Document - 
                                <a href="#" target="_blank">Click Here</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div> --}}
</div>
