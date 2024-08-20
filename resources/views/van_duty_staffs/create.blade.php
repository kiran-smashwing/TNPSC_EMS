@extends('layouts.app')

@section('title', 'Van Duty Staffs')

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
                    <h5>Van Duty Staffs Add</h5>
                  </div> 
                  <div class="card-body">
                    <div class="row">
                      <div class="col-sm-12 text-center mb-3">
                        <div class="user-upload wid-75">
                          <img src="../assets/images/user/avatar-4.jpg" alt="img" class="img-fluid">
                          <label for="uplfile" class="img-avtar-upload">
                            <i class="ti ti-camera f-24 mb-1"></i>
                            <span>Upload</span>
                          </label>
                          <input type="file" id="uplfile" class="d-none">
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="mb-3">
                          <label class="form-label">Name</label>
                          <input type="text" class="form-control" value="Anshan">
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="mb-3">
                          <label class="form-label">Employee ID</label>
                          <input type="text" class="form-control" value="12205940">
                        </div>
                      </div>
                      <!-- <div class="col-sm-6">
                        <div class="mb-3">
                          <label class="form-label">Role</label>
                          <input type="text" class="form-control" value="Testing@gmail.com">
                        </div>
                      </div> -->
                      <div class="col-sm-6">
                        <div class="mb-3">
                          <label class="form-label">Role</label>
                          <select class="form-control">
                            <option>CI</option>
                            <option>ID</option>
                            <option>CEN</option>
                            <!-- <option selected="selected">4 year</option>
                            <option>5 year</option> -->
                          </select>
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="mb-3">
                          <label class="form-label">District Name</label>
                          <input type="text" class="form-control" value="Vellore">
                        </div>
                      </div>
                      <!-- <div class="col-sm-6">
                        <div class="mb-3">
                          <label class="form-label">E-mail</label>
                          <input type="text" class="form-control" value="+91 95675497832">
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="mb-3">
                          <label class="form-label">Password</label>
                          <input type="text" class="form-control" value="***********">
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="mb-3">
                          <label class="form-label">Confirm password</label>
                          <input type="text" class="form-control" value="*********">
                        </div>
                      </div> -->
                      <!-- <div class="col-sm-12">
                        <div class="mb-3">
                          <label class="form-label">Bio</label>
                          <textarea class="form-control">
Hello, I’m Anshan Handgun Creative Graphic Designer & User Experience Designer based in Website, I create digital Products a more Beautiful and usable place. Morbid accusant ipsum. Nam nec tellus at.
                          </textarea>
                        </div>
                      </div> -->
                      <!-- <div class="col-sm-12">
                        <div class="mb-3">
                          <label class="form-label">Experience</label>
                          <select class="form-control">
                            <option>Startup</option>
                            <option>2 year</option>
                            <option>3 year</option>
                            <option selected="selected">4 year</option>
                            <option>5 year</option>
                          </select>
                        </div>
                      </div> -->
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
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
                          <label class="form-label">Password</label>
                          <input type="text" class="form-control" value="***********">
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="mb-3">
                          <label class="form-label">Confrim password<span class="text-danger">*</span></label>
                          <input type="text" class="form-control" value="**********">
                        </div>
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
