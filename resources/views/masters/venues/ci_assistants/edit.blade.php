@extends('layouts.app')

@section('title', 'CI Assistants')

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
    <!-- [ breadcrumb ] start -->
    <!-- <div class="page-header">
      <div class="page-block">
        <div class="row align-items-center">
          <div class="col-md-12">
            <div class="page-header-title">
              <h2 class="mb-0">District Collectorates Add</h2>
            </div>
          </div>
        </div>
      </div>
    </div> -->
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class="row">
      <div class="col-sm-12">
        <!-- <div class="card">
          <div class="card-body py-0">
             Your content here 
          </div> -->
        </div>
        <div class="tab-content">
          <div>
              <div class="row">
                  <div class="col-lg-6">
                      <div class="card">
                          <div class="card-header">
                              <h5>Cheif Invigilator Assistant - <span class="text-primary">Edit</span></h5>
                          </div>
                          <div class="card-body">
                              <div class="row">
                                  <div class="col-sm-6 text-center mb-3">
                                      <div class="user-upload wid-75">
                                          <img src="../assets/images/user/avatar-4.jpg" alt="img"
                                              class="img-fluid">
                                          <label for="uplfile" class="img-avtar-upload">
                                              <i class="ti ti-camera f-24 mb-1"></i>
                                              <span>Upload</span>
                                          </label>
                                          <input type="file" id="uplfile" class="d-none">
                                      </div>
                                  </div>
                                  <div class="col-sm-6">
                                      <div class="mb-3">
                                          <label class="form-label" for="district_id">District<span
                                                  class="text-danger">*</span></label>
                                          <select class="form-control" id="district_id" name="district_id"
                                              required>
                                              <option>Select District</option>
                                              <option value="1010">Chennai</option>
                                          </select>
                                      </div>
                                  </div>
                                  <div class="col-sm-6">
                                      <div class="mb-3">
                                          <label class="form-label" for="center_id">Center<span
                                                  class="text-danger">*</span></label>
                                          <select class="form-control" id="center_id" name="center_id" required>
                                              <option>Select Center</option>
                                              <option value="1010">Alandur</option>
                                          </select>
                                      </div>
                                  </div>
                                  <div class="col-sm-6">
                                      <div class="mb-3">
                                          <label class="form-label" for="venue_id">Venue<span
                                                  class="text-danger">*</span></label>
                                          <select class="form-control" id="venue_id" name="venue_id" required>
                                              <option>Select Venue</option>
                                              <option value="1010">Gov Hr Sec School</option>
                                          </select>
                                      </div>
                                  </div>
                                  <div class="col-sm-6">
                                      <div class="mb-3">
                                          <label class="form-label" for="name">Name <span
                                                  class="text-danger">*</span></label>
                                          <input type="text" class="form-control" id="name" name="name"
                                              placeholder="Malarvizhi" required>
                                      </div>
                                  </div>
                                  <div class="col-sm-6">
                                      <div class="mb-3">
                                          <label class="form-label">Email<span
                                                  class="text-danger">*</span></label>
                                          <input type="email" class="form-control" id="mail" name="mail"
                                              placeholder="malarvizhi@***.in" required>
                                      </div>
                                  </div>
                                  <div class="col-sm-6">
                                      <div class="mb-3">
                                          <label class="form-label" for="phone">Phone<span
                                                  class="text-danger">*</span></label>
                                          <input type="tel" class="form-control" id="phone" name="phone"
                                              placeholder="9434***1212" required>
                                      </div>
                                  </div>
                                  <div class="col-sm-6">
                                      <div class="mb-3">
                                          <label class="form-label" for="designation">Designation <span
                                                  class="text-danger">*</span></label>
                                          <input type="text" class="form-control" id="designation"
                                              name="designation" placeholder="Asst Professor" required>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
                  <div class="col-12 text-end btn-page">
                      <div class="btn btn-outline-secondary">Cancel</div>
                      <div class="btn btn-primary">update</div>
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

@push('scripts')
@include('partials.datatable-export-js')
@endpush

@include('partials.theme')

@endsection
