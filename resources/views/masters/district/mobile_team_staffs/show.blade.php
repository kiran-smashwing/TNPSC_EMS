@extends('layouts.app')

@section('title', 'View District Collectorates')

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
                                    <i class="ph-duotone ph-circle-wavy-check text-success"></i> <!-- Bootstrap Icon -->
                                </span>
                            </div>
                            
                              <div class="text-center mt-3">
                                <div class="chat-avtar d-inline-flex mx-auto">
                                  <img class="rounded-circle img-fluid wid-70" src="{{ asset('storage/assets/images/user/avatar-4.jpg') }}" alt="User image" />
                                </div>
                                <h5 class="mb-0">Nanmaran</h5>
                                <p class="text-muted text-sm">Thasildar</p>
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
                                <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3 text-start">
                                    <i class="ti ti-map-pin me-2"></i>
                                    <p class="mb-0">01 - Chennai</p>
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
                              <h5>Personal Details</h5>
                            </div>
                            <div class="card-body">
                              <ul class="list-group list-group-flush">
                                <li class="list-group-item px-0 pt-0">
                                  <div class="row">
                                    <div class="col-md-6">
                                      <p class="mb-1 text-muted">Full Name</p>
                                      <p class="mb-0">Anshan Handgun</p>
                                    </div>
                                    <div class="col-md-6">
                                      <p class="mb-1 text-muted">Father Name</p>
                                      <p class="mb-0">Mr. Deepen Handgun</p>
                                    </div>
                                  </div>
                                </li>
                                <li class="list-group-item px-0">
                                  <div class="row">
                                    <div class="col-md-6">
                                      <p class="mb-1 text-muted">Phone</p>
                                      <p class="mb-0">(+1-876) 8654 239 581</p>
                                    </div>
                                    <div class="col-md-6">
                                      <p class="mb-1 text-muted">Country</p>
                                      <p class="mb-0">New York</p>
                                    </div>
                                  </div>
                                </li>
                                <li class="list-group-item px-0">
                                  <div class="row">
                                    <div class="col-md-6">
                                      <p class="mb-1 text-muted">Email</p>
                                      <p class="mb-0">anshan.dh81@gmail.com</p>
                                    </div>
                                    <div class="col-md-6">
                                      <p class="mb-1 text-muted">Zip Code</p>
                                      <p class="mb-0">956 754</p>
                                    </div>
                                  </div>
                                </li>
                                <li class="list-group-item px-0 pb-0">
                                  <p class="mb-1 text-muted">Address</p>
                                  <p class="mb-0">Street 110-B Kalians Bag, Dewan, M.P. New York</p>
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
