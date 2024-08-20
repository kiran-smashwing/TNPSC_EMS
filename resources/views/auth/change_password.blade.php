@extends('layouts.app')

@section('title', 'Cheif Invigilator')

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
    <div class="tab-pane" id="profile-4" role="tabpanel" aria-labelledby="profile-tab-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Change Password</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="mb-3"><label class="form-label">Old Password</label> <input type="password" class="form-control"></div>
                                            <div class="mb-3"><label class="form-label">New Password</label> <input type="password" class="form-control"></div>
                                            <div class="mb-3"><label class="form-label">Confirm Password</label> <input type="password" class="form-control"></div>
                                        </div>
                                        <div class="col-sm-6">
                                            <h5>New password must contain:</h5>
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item"><i class="ti ti-circle-check text-success f-16 me-2"></i> At least 8 characters</li>
                                                <li class="list-group-item"><i class="ti ti-circle-check text-success f-16 me-2"></i> At least 1 lower letter (a-z)</li>
                                                <li class="list-group-item"><i class="ti ti-circle-check text-success f-16 me-2"></i> At least 1 uppercase letter(A-Z)</li>
                                                <li class="list-group-item"><i class="ti ti-circle-check text-success f-16 me-2"></i> At least 1 number (0-9)</li>
                                                <li class="list-group-item"><i class="ti ti-circle-check text-success f-16 me-2"></i> At least 1 special characters</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-end btn-page">
                                    <div class="btn btn-outline-secondary">Cancel</div>
                                    <div class="btn btn-primary">Update</div>
                                </div>
                            </div>
                        </div>
    <!-- [ Main Content ] end -->
  </div>
</div>

@include('partials.footer')

@push('scripts')
 <script src="../assets/js/plugins/croppr.min.js"></script>
    <script src="../assets/js/pages/page-croper.js"></script>
    <script>
  document.getElementById('triggerModal').addEventListener('click', function() {
    var modal = new bootstrap.Modal(document.getElementById('cropperModal'));
    modal.show();
  });
</script>

@endpush
    @include('partials.theme')

@endsection
