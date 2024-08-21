@extends('layouts.app')

@section('title', 'District Collectorates')

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
                                <span class="badge bg-primary">Email</span>
                              </div>
                              <div class="text-center mt-3">
                                <div class="chat-avtar d-inline-flex mx-auto">
                                  <img class="rounded-circle img-fluid wid-70" src="../assets/images/user/avatar-5.jpg" alt="User image" />
                                </div>
                                <h5 class="mb-0">Chennai</h5>
                                <p class="text-muted text-sm">Project Manager</p>
                                <hr class="my-3 border border-secondary-subtle" />
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
                                {{-- <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3">
                                  <i class="ti ti-phone me-2"></i>
                                  <p class="mb-0">(+91) O4448***762</p>
                                </div> --}}
                                <div class="d-inline-flex align-items-center w-100 mb-3">
                                  <i class="ti ti-map-pin me-2"></i>
                                  <p class="mb-0">Tamil Nadu Public Service Commission, TNPSC Road, Broadway, Chennai-600003.</p>
                                </div>
                                <div class="d-inline-flex align-items-center justify-content-start w-100">
                                  <i class="ti ti-link me-2"></i>
                                  <a href="#" class="link-primary">
                                    <p class="mb-0">https://chennai.nic.in/</p>
                                  </a>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="card">
                            <div class="card-header">
                              <h5>Skills</h5>
                            </div>
                            <div class="card-body">
                              <div class="row align-items-center mb-3">
                                <div class="col-sm-6 mb-2 mb-sm-0">
                                  <p class="mb-0">Junior</p>
                                </div>
                                <div class="col-sm-6">
                                  <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 me-3">
                                      <div class="progress progress-primary" style="height: 6px">
                                        <div class="progress-bar" style="width: 30%"></div>
                                      </div>
                                    </div>
                                    <div class="flex-shrink-0">
                                      <p class="mb-0 text-muted">30%</p>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="row align-items-center mb-3">
                                <div class="col-sm-6 mb-2 mb-sm-0">
                                  <p class="mb-0">UX Researcher</p>
                                </div>
                                <div class="col-sm-6">
                                  <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 me-3">
                                      <div class="progress progress-primary" style="height: 6px">
                                        <div class="progress-bar" style="width: 80%"></div>
                                      </div>
                                    </div>
                                    <div class="flex-shrink-0">
                                      <p class="mb-0 text-muted">80%</p>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="row align-items-center mb-3">
                                <div class="col-sm-6 mb-2 mb-sm-0">
                                  <p class="mb-0">Wordpress</p>
                                </div>
                                <div class="col-sm-6">
                                  <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 me-3">
                                      <div class="progress progress-primary" style="height: 6px">
                                        <div class="progress-bar" style="width: 90%"></div>
                                      </div>
                                    </div>
                                    <div class="flex-shrink-0">
                                      <p class="mb-0 text-muted">90%</p>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="row align-items-center mb-3">
                                <div class="col-sm-6 mb-2 mb-sm-0">
                                  <p class="mb-0">HTML</p>
                                </div>
                                <div class="col-sm-6">
                                  <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 me-3">
                                      <div class="progress progress-primary" style="height: 6px">
                                        <div class="progress-bar" style="width: 30%"></div>
                                      </div>
                                    </div>
                                    <div class="flex-shrink-0">
                                      <p class="mb-0 text-muted">30%</p>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="row align-items-center mb-3">
                                <div class="col-sm-6 mb-2 mb-sm-0">
                                  <p class="mb-0">Graphic Design</p>
                                </div>
                                <div class="col-sm-6">
                                  <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 me-3">
                                      <div class="progress progress-primary" style="height: 6px">
                                        <div class="progress-bar" style="width: 95%"></div>
                                      </div>
                                    </div>
                                    <div class="flex-shrink-0">
                                      <p class="mb-0 text-muted">95%</p>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="row align-items-center">
                                <div class="col-sm-6 mb-2 mb-sm-0">
                                  <p class="mb-0">Code Style</p>
                                </div>
                                <div class="col-sm-6">
                                  <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 me-3">
                                      <div class="progress progress-primary" style="height: 6px">
                                        <div class="progress-bar" style="width: 75%"></div>
                                      </div>
                                    </div>
                                    <div class="flex-shrink-0">
                                      <p class="mb-0 text-muted">75%</p>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-8 col-xxl-9">
                          <div class="card">
                            <div class="card-header">
                              <h5>About me</h5>
                            </div>
                            <div class="card-body">
                              <p class="mb-0"
                                >Hello, I’m Anshan Handgun Creative Graphic Designer & User Experience Designer based in Website, I create digital
                                Products a more Beautiful and usable place. Morbid accusant ipsum. Nam nec tellus at.</p
                              >
                            </div>
                          </div>
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
                          <div class="card">
                            <div class="card-header">
                              <h5>Education</h5>
                            </div>
                            <div class="card-body">
                              <ul class="list-group list-group-flush">
                                <li class="list-group-item px-0 pt-0">
                                  <div class="row">
                                    <div class="col-md-6">
                                      <p class="mb-1 text-muted">Master Degree (Year)</p>
                                      <p class="mb-0">2014-2017</p>
                                    </div>
                                    <div class="col-md-6">
                                      <p class="mb-1 text-muted">Institute</p>
                                      <p class="mb-0">-</p>
                                    </div>
                                  </div>
                                </li>
                                <li class="list-group-item px-0">
                                  <div class="row">
                                    <div class="col-md-6">
                                      <p class="mb-1 text-muted">Bachelor (Year)</p>
                                      <p class="mb-0">2011-2013</p>
                                    </div>
                                    <div class="col-md-6">
                                      <p class="mb-1 text-muted">Institute</p>
                                      <p class="mb-0">Imperial College London</p>
                                    </div>
                                  </div>
                                </li>
                                <li class="list-group-item px-0 pb-0">
                                  <div class="row">
                                    <div class="col-md-6">
                                      <p class="mb-1 text-muted">School (Year)</p>
                                      <p class="mb-0">2009-2011</p>
                                    </div>
                                    <div class="col-md-6">
                                      <p class="mb-1 text-muted">Institute</p>
                                      <p class="mb-0">School of London, England</p>
                                    </div>
                                  </div>
                                </li>
                              </ul>
                            </div>
                          </div>
                          <div class="card">
                            <div class="card-header">
                              <h5>Employment</h5>
                            </div>
                            <div class="card-body">
                              <ul class="list-group list-group-flush">
                                <li class="list-group-item px-0 pt-0">
                                  <div class="row">
                                    <div class="col-md-6">
                                      <p class="mb-1 text-muted">Senior</p>
                                      <p class="mb-0">Senior UI/UX designer (Year)</p>
                                    </div>
                                    <div class="col-md-6">
                                      <p class="mb-1 text-muted">Job Responsibility</p>
                                      <p class="mb-0"
                                        >Perform task related to project manager with the 100+ team under my observation. Team management is key
                                        role in this company.</p
                                      >
                                    </div>
                                  </div>
                                </li>
                                <li class="list-group-item px-0">
                                  <div class="row">
                                    <div class="col-md-6">
                                      <p class="mb-1 text-muted">Trainee cum Project Manager (Year)</p>
                                      <p class="mb-0">2017-2019</p>
                                    </div>
                                    <div class="col-md-6">
                                      <p class="mb-1 text-muted">Job Responsibility</p>
                                      <p class="mb-0">Team management is key role in this company.</p>
                                    </div>
                                  </div>
                                </li>
                                <li class="list-group-item px-0 pb-0">
                                  <div class="row">
                                    <div class="col-md-6">
                                      <p class="mb-1 text-muted">School (Year)</p>
                                      <p class="mb-0">2009-2011</p>
                                    </div>
                                    <div class="col-md-6">
                                      <p class="mb-1 text-muted">Institute</p>
                                      <p class="mb-0">School of London, England</p>
                                    </div>
                                  </div>
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
