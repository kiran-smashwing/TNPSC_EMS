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
                    <h5 class="mb-0">{{ $official->dept_off_name }}</h5>
                    <p class="text-muted text-sm">{{ $official->custom_role ? $official->custom_role :  $roles_role->role_department .' - '. $roles_role->role_name }}</p>
                    {{-- <hr class="my-3 border border-secondary-subtle" /> --}}
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
                        <i class="ti ti-man me-2"></i>
                        <p class="mb-0">{{ $official->dept_off_designation }}</p>
                    </div>
                    <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3">
                        <i class="ti ti-mail me-2"></i>
                        <p class="mb-0">{{ $official->dept_off_email }}</p>
                    </div>
                    <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3">
                        <i class="ti ti-phone me-2"></i>
                        <p class="mb-0">{{ $official->dept_off_phone }}</p>
                    </div>
                    <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3">
                        <i class="ti ti-barcode me-2"></i>
                        <p class="mb-0">{{ $official->dept_off_emp_id }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="col-lg-8 col-xxl-9">
        <div class="card">
            <div class="card-header">
                <h5>Demo Videos</h5>
            </div>
            <div class="card-body pc-component">
                <div id="carouselExampleFade" class="carousel slide carousel-fade"
                    data-bs-ride="carousel">
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