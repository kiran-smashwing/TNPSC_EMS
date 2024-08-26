@extends('layouts.app')

@section('title', 'Department Officials')

@push('styles')
    <link rel="stylesheet" href="{{ asset('storage/assets/css/plugins/croppr.min.css')}}" />
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
      <div class="col-sm-12">
        </div>
        <div class="tab-content">
          <div>
            <div class="row">
              <div class="col-lg-6">
                <div class="card">
                  <div class="card-header">
                    <h5>Department Officer - <span class="text-primary">Add</span></h5>
                  </div> 
                  <div class="card-body">
                    <div class="row">
                      <div class="col-sm-6 text-center mb-3">
                        <div class="user-upload wid-75" id="triggerModal">
                            <img src="{{ asset('storage/assets/images/user/avatar-4.jpg') }}"
                                alt="img" class="img-fluid">
                            <label for="image" class="img-avtar-upload">
                                <i class="ti ti-camera f-24 mb-1"></i>
                                <span>Upload</span>
                            </label>
                            {{-- <input type="file" id="image" name="image" class="d-none"> --}}
                        </div>
                    </div>
                      <div class="col-sm-6">
                        <div class="mb-3">
                            <label class="form-label" for="role_id">Role <span class="text-danger">*</span></label>
                            <select class="form-control" id="role_id" name="role_id" required>
                                <option>Select Role</option>
                                <option value="1">APD - role</option>
                                <option value="1">ID - role</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="mb-3">
                          <label class="form-label">Name <span class="text-danger">*</span></label>
                          <input type="text" class="form-control" id="name"
                              name="name" placeholder="Nanmaran" required>
                      </div>
                  </div>
                    <div class="col-sm-6">
                      <div class="mb-3">
                          <label class="form-label" for="employee_id">Employee ID <span class="text-danger">*</span></label>
                          <input type="text" class="form-control" id="employee_id"
                              name="employee_id" placeholder="EMP1234" required>
                      </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="mb-3">
                        <label class="form-label" for="designation">Designation <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="designation"
                            name="designation" placeholder="Thasildar" required>
                    </div>
                </div>
                  <div class="col-sm-6">
                    <div class="mb-3">
                        <label class="form-label">Email<span
                          class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="mail" name="mail"
                        placeholder="ceochn@***.in" required>
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
                      <label class="form-label"for="password">Password<span
                        class="text-danger">*</span></label>
                      <input type="password" class="form-control" id="password"
                          name="password" required  placeholder="******">
                  </div>
              </div>
                    </div>
                  </div>
                </div>
              </div>
              {{-- <div class="col-lg-6">
                <div class="card">
                  <!-- <div class="card-header">
                    <h5>Contact Information</h5>
                  </div> -->
                  <div class="card-body">
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="mb-3">
                          <label class="form-label">E-mail</label>
                          <input type="text" class="form-control" value="Testing@gmail.com">
                        </div>
                      </div>
                    <!-- <div class="col-sm-12">
                        <div class="mb-3">
                          <label class="form-label">Address</label>
                          <textarea class="form-control">3379 Monroe Avenue, Vellore, TamilNude</textarea>
                        </div>
                      </div> -->
                     
                      <div class="col-sm-6">
                        <div class="mb-3">
                          <label class="form-label">Confrim password<span class="text-danger">*</span></label>
                          <input type="text" class="form-control" value="**********">
                        </div>
                      </div>
                      
                     
                    </div>
                  </div>
                </div>
              </div> --}}
              <div class="col-12 text-end btn-page">
                <div class="btn btn-outline-secondary">Cancel</div>
                <div class="btn btn-primary">Create</div>
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
<script src="{{ asset('storage/assets/js/plugins/croppr.min.js')}}"></script>
        <script src="{{ asset('storage/assets/js/pages/page-croper.js')}}"></script>
        <script>
            document.getElementById('triggerModal').addEventListener('click', function() {
                var modal = new bootstrap.Modal(document.getElementById('cropperModal'));
                modal.show();
            });
        </script>
@endpush

@include('partials.theme')

@endsection
