@extends('layouts.app')

@section('title', 'Current Exam')
@push('styles')
    <link rel="stylesheet" href="{{ asset('storage/assets/css/plugins/datepicker-bs5.min.css')}}" />
@endpush

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
             <!-- Modal start-->
             @include('modals.cropper')
                  <!-- Modal start-->
            <!-- [ Main Content ] start -->
            <div class="row">
               
                <div class="tab-content">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>Current Exam - <span class="text-primary">Edit</span></h5>
                                    </div>
                                    <div class="card-body">
                                        {{-- <form action="{{ route('collectorates.store') }}" method="POST" enctype="multipart/form-data"> --}}
                                        @csrf
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="exam_id">Exam ID <span
                                                      class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="exam_id"
                                                        name="exam_id" readonly required value="20240719165037">
                                                </div>
                                            </div>
                                            
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="exam_type">Type of Exam<span class="text-danger">*</span></label>
                                                    <select class="form-control" id="exam_type" name="exam_type" required>
                                                        <option disabled selected>Select Exam Type</option>
                                                            <option value="Objective">Objective</option>
                                                            <option value="Descriptive">Descriptive</option>
                                                            <option value="CBT">CBT</option>
                                                            <option value="Objective+Descriptive" >Objective + Descriptive </option>
                                                        </select>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="exam_model">Exam Model<span class="text-danger">*</span></label>
                                                    <select class="form-control" id="exam_model" name="exam_model" required>
                                                        <option disabled selected>Select Exam Model</option>
                                                            <option value="Major">Major</option>
                                                            <option value="Minor">Minor</option>
                                                        </select>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="exam_tiers">Exam Tiers<span class="text-danger">*</span></label>
                                                    <select class="form-control" id="exam_tiers" name="exam_tiers" required>
                                                        <option disabled selected>Select Exam Tiers</option>
                                                            <option value="1">1 - (Single Tier)</option>
                                                            <option value="2" >2 - (Multi Tiers)</option>
                                                        </select>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="exam_service">Exam Service<span class="text-danger">*</span></label>
                                                    <select class="form-control" id="exam_service" name="exam_service" required>
                                                        <option disabled selected>Select Exam Service</option>
                                                            <option value="001">GROUP I SERVICES EXAMINATION</option>
                                                            <option value="002" >GROUP I-A SERVICES EXAMINATION</option>
                                                        </select>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="notif_no">Notification no                                                        <span
                                                      class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="notif_no"
                                                        name="notif_no"  required placeholder="08/2024">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3 ">
                                                    <label class="form-label" for="notif_date">Notification Date                                                        <span
                                                        class="text-danger">*</span></label>
                                                    <div class="input-group date">
                                                        <input type="text" name="notif_date"  class="form-control" value="05/20/2017" id="notif_date" />
                                                        <span class="input-group-text">
                                                          <i class="feather icon-calendar"></i>
                                                        </span>
                                                      </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="exam_name">Exam Name                                                       <span
                                                      class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="exam_name"
                                                        name="exam_name"  required placeholder="Combined Civil Services Examination - II">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="exam_name_tamil">Exam Name in Tamil                                                      <span
                                                      class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="exam_name_tamil"
                                                        name="exam_name_tamil"  required placeholder="ஒருங்கிணைந்த சிவில் சர்வீசஸ் தேர்வு - II (குரூப் II மற்றும் IIA சேவைகள்)">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="post_name">Post Name                                                     <span
                                                      class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="post_name"
                                                        name="post_name"  required placeholder="Group II and IIA Services">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3 ">
                                                    <label class="form-label" for="last_date_apply">Last Date For Apply                                                       <span
                                                        class="text-danger">*</span></label>
                                                    <div class="input-group date">
                                                        <input type="text" name="last_date_apply"  class="form-control" value="05/20/2017" id="last_date_apply" />
                                                        <span class="input-group-text">
                                                          <i class="feather icon-calendar"></i>
                                                        </span>
                                                      </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3 ">
                                                    <label class="form-label" for="exam_start_date">Exam Start Date                                                       <span
                                                        class="text-danger">*</span></label>
                                                    <div class="input-group date">
                                                        <input type="text" name="exam_start_date"  class="form-control" value="05/20/2017" id="exam_start_date" />
                                                        <span class="input-group-text">
                                                          <i class="feather icon-calendar"></i>
                                                        </span>
                                                      </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3 ">
                                                    <label class="form-label" for="date_of_exam">Date of Exam                                                       <span
                                                        class="text-danger">*</span></label>
                                                    <div class="input-group date">
                                                        <input type="text" name="date_of_exam"  class="form-control" value="05/20/2017" id="date_of_exam" />
                                                        <span class="input-group-text">
                                                          <i class="feather icon-calendar"></i>
                                                        </span>
                                                      </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Website<span
                                                      class="text-danger">*</span></label>
                                                    <input  type="url" class="form-control" id="website" name="website" placeholder="https://chennai.nic.in/">
                                                </div>
                                            </div>
                                              <div class="col-sm-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Address<span
                                                      class="text-danger">*</span></label>
                                                    <textarea class="form-control" id="address" name="address" required placeholder="Tamil Nadu Public Service Commission, TNPSC Road, Broadway, Chennai-600003."></textarea>
                                                </div>
                                            </div> 
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="longitude">longitude<span
                                                            class="text-danger">*</span></label>
                                                    <input type="number" step="any" class="form-control" id="longitude"
                                                        name="longitude" placeholder="11.2312312312312">
                                                </div>
                                            </div>
                                            <div class="col-sm-6 ">
                                                <div class="mb-3">
                                                    <label class="form-label"  for="latitude" >latitude<span
                                                            class="text-danger">*</span></label>
                                                    <input type="number" step="any" class="form-control"
                                                        id="latitude" name="latitude" placeholder="11.2312312312312">
                                                </div>
                                            </div>
                                            <div class="col-sm-6  d-inline-flex justify-content-center mb-3">
                                            <a href="#" class="btn btn-success d-inline-flex  justify-content-center"><i class="ti ti-current-location me-1"></i>Get Location Coordinates</a>
                                            </div>
                                             <div class="col-sm-6 d-inline-flex justify-content-center mb-3">
                                            <a href="https://www.google.com/maps" target="_blank" class="btn btn-info d-inline-flex  justify-content-center"><i class="ti ti-map-2 me-1"></i>Get Coordinates on G-Map</a>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 text-end btn-page">
                                <div class="btn btn-outline-secondary">Cancel</div>
                                <div class="btn btn-primary">Update</div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
        <!-- [ Main Content ] end -->
    </div>

    @include('partials.footer')

    @push('scripts')
      <script src="{{ asset('storage/assets/js/plugins/datepicker-full.min.js')}}"></script>
      <script>
       (function () {
        const d_week = new Datepicker(document.querySelector('#notif_date'), {
          buttonClass: 'btn',
          todayBtn: true,
          clearBtn: true,
           format: 'dd-mm-yyyy'
        });
        const d_week1 = new Datepicker(document.querySelector('#last_date_apply'), {
          buttonClass: 'btn',
          todayBtn: true,
          clearBtn: true,
           format: 'dd-mm-yyyy'
        });
        const d_week2 = new Datepicker(document.querySelector('#exam_start_date'), {
          buttonClass: 'btn',
          todayBtn: true,
          clearBtn: true,
           format: 'dd-mm-yyyy'
        });
        const d_week3 = new Datepicker(document.querySelector('#date_of_exam'), {
          buttonClass: 'btn',
          todayBtn: true,
          clearBtn: true,
           format: 'dd-mm-yyyy'
        });
      })();
        </script>
      @endpush
    @include('partials.theme')

@endsection
