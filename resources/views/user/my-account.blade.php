@extends('layouts.app')
@section('title', ' My Account')
@push('styles')
    <link rel="stylesheet" href="{{ asset('storage/assets/css/plugins/croppr.min.css') }}" />
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

    @include('modals.cropper')

    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            {{-- <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="../dashboard/index.html">Home</a></li>
                                <li class="breadcrumb-item"><a href="javascript: void(0)">Users</a></li>
                                <li class="breadcrumb-item" aria-current="page">Account Profile</li>
                            </ul> --}}
                        </div>
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">My Account</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            <!-- [ Main Content ] start -->
            <div class="row">
                <!-- [ sample-page ] start -->
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body py-0">
                            <ul class="nav nav-tabs profile-tabs" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="profile-tab-1" data-bs-toggle="tab" href="#profile-1"
                                        role="tab" aria-selected="true">
                                        <i class="ti ti-user me-2"></i>My Account
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="profile-tab-2" data-bs-toggle="tab" href="#profile-2"
                                        role="tab" aria-selected="true">
                                        <i class="ti ti-file-text me-2"></i>Edit My Account 
                                    </a>
                                </li>
                                {{-- <li class="nav-item">
                                    <a class="nav-link" id="profile-tab-3" data-bs-toggle="tab" href="#profile-3"
                                        role="tab" aria-selected="true">
                                        <i class="ti ti-id me-2"></i>My Account
                                    </a>
                                </li> --}}
                                <li class="nav-item">
                                    <a class="nav-link" id="profile-tab-4" data-bs-toggle="tab" href="#profile-4"
                                        role="tab" aria-selected="true">
                                        <i class="ti ti-lock me-2"></i>Change Password
                                    </a>
                                </li>
                                {{-- <li class="nav-item">
                                    <a class="nav-link" id="profile-tab-5" data-bs-toggle="tab" href="#profile-5"
                                        role="tab" aria-selected="true">
                                        <i class="ti ti-users me-2"></i>Role
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="profile-tab-6" data-bs-toggle="tab" href="#profile-6"
                                        role="tab" aria-selected="true">
                                        <i class="ti ti-settings me-2"></i>Settings
                                    </a>
                                </li> --}}
                            </ul>
                        </div>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane show active" id="profile-1" role="tabpanel" aria-labelledby="profile-tab-1">
                            @if ($role == 'headquarters')
                                @include('partials.my_account_view.department_account_view')
                            @elseif($role == 'venue')
                                @include('partials.my_account_view.venue_account_view')
                            @elseif($role == 'center')
                                @include('partials.my_account_view.center_account_view')
                            @elseif($role == 'treasury')
                                @include('partials.my_account_view.treasury_account_view')
                            @elseif($role == 'ci')
                                @include('partials.my_account_view.ci_account_view')
                            @elseif($role == 'district')
                                @include('partials.my_account_view.district_account_view')
                            @elseif($role == 'mobile_team_staffs')
                                @include('partials.my_account_view.mobile_account_view')
                            @endif
                        </div>
                        <!-- Modal -->
                        <div class="tab-pane" id="profile-2" role="tabpanel" aria-labelledby="profile-tab-2">
                            @if ($role == 'headquarters')
                                @include('partials.my_account_edit.department_account')
                            @elseif($role == 'venue')
                                @include('partials.my_account_edit.venue_account')
                            @elseif($role == 'center')
                                @include('partials.my_account_edit.center_account')
                            @elseif($role == 'treasury')
                                @include('partials.my_account_edit.treasury_account')
                            @elseif($role == 'ci')
                                @include('partials.my_account_edit.ci_account')
                            @elseif($role == 'district')
                                @include('partials.my_account_edit.district_account')
                            @elseif($role == 'mobile_team_staffs')
                                @include('partials.my_account_edit.mobile_account')
                            @endif

                        </div>
                        <div class="tab-pane" id="profile-4" role="tabpanel" aria-labelledby="profile-tab-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Change Password</h5>
                                </div>
                                <div class="card-body">
                                    @if (session('success'))
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            {{ session('success') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                        </div>
                                    @endif
                                    @if (session('error'))
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            {{ session('error') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                        </div>
                                    @endif
                                    <form action="{{ route('password.update') }}" method="POST" id="password-form">
                                        @csrf
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Old Password</label>
                                                        <input type="password" name="old_password" class="form-control"
                                                            required />
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">New Password</label>
                                                        <input type="password" name="new_password" class="form-control"
                                                            required />
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Confirm Password</label>
                                                        <input type="password" name="new_password_confirmation"
                                                            class="form-control" required />
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <h5>New password must contain:</h5>
                                                    <ul class="list-group list-group-flush">
                                                        <li class="list-group-item">
                                                            <i class="ti ti-circle-check text-success f-16 me-2"></i> At
                                                            least 8 characters
                                                        </li>
                                                        <li class="list-group-item">
                                                            <i class="ti ti-circle-check text-success f-16 me-2"></i> At
                                                            least 1 lower letter (a-z)
                                                        </li>
                                                        <li class="list-group-item">
                                                            <i class="ti ti-circle-check text-success f-16 me-2"></i> At
                                                            least 1 uppercase letter (A-Z)
                                                        </li>
                                                        <li class="list-group-item">
                                                            <i class="ti ti-circle-check text-success f-16 me-2"></i> At
                                                            least 1 number (0-9)
                                                        </li>
                                                        <li class="list-group-item">
                                                            <i class="ti ti-circle-check text-success f-16 me-2"></i> At
                                                            least 1 special character
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Hidden fields for role and user ID -->
                                        <input type="hidden" name="role" value="{{ session('auth_role') }}">
                                        <input type="hidden" name="user_id" value="{{ session('auth_id') }}">

                                        <div class="card-footer text-end btn-page">
                                            <button type="button" class="btn btn-outline-secondary"
                                                onclick="window.location.href='{{ url()->previous() }}'">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Update Password</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- [ sample-page ] end -->
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>
    <!-- [ Main Content ] end -->
    @include('partials.footer')

    @push('scripts')
        <script src="{{ asset('storage/assets/js/plugins/croppr.min.js') }}"></script>
        <script src="{{ asset('storage/assets/js/pages/page-croper.js') }}"></script>

        <script>
            document.querySelector('.btn-success').addEventListener('click', function(e) {
                e.preventDefault();
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(position => {
                        document.getElementById('latitude').value = position.coords.latitude;
                        document.getElementById('longitude').value = position.coords.longitude;
                    });
                }
            });
        </script>
        @if ($role == 'ci')
            <script>
                // Full list of centers
                const allCenters = @json($centers);

                // District dropdown change event
                $('#district').on('change', function() {
                    const selectedDistrictCode = $(this).val();
                    const centerDropdown = $('#center');

                    // Clear previous options
                    centerDropdown.empty();
                    centerDropdown.append('<option value="">Select Center Name</option>');

                    // Filter centers based on selected district
                    const filteredCenters = allCenters.filter(center =>
                        center.center_district_id == selectedDistrictCode
                    );

                    // Populate centers
                    filteredCenters.forEach(center => {
                        const selected = "{{ old('center', $chiefInvigilator->ci_center_id) }}" == center
                            .center_code ? 'selected' : '';
                        centerDropdown.append(
                            `<option value="${center.center_code}" ${selected}>
                        ${center.center_name}
                    </option>`
                        );
                    });
                });

                // Trigger change event on page load to handle old/existing selections
                $(document).ready(function() {
                    const oldDistrict = "{{ old('district', $chiefInvigilator->ci_district_id) }}";
                    if (oldDistrict) {
                        $('#district').val(oldDistrict).trigger('change');
                    }
                });
            </script>
            <script>
                // Full list of venues
                const allVenues = @json($venues);

                // Center dropdown change event
                $('#center').on('change', function() {
                    const selectedCenterCode = $(this).val();
                    const venueDropdown = $('#venue');

                    // Clear previous options
                    venueDropdown.empty();
                    venueDropdown.append('<option value="">Select Venue Name</option>');

                    // Filter venues based on selected center
                    const filteredVenues = allVenues.filter(venue =>
                        venue.venue_center_id == selectedCenterCode
                    );
                    // Populate venues
                    filteredVenues.forEach(venue => {
                        const selected = "{{ old('venue', $chiefInvigilator->ci_venue_id) }}" == venue.venue_code ?
                            'selected' : '';
                        venueDropdown.append(
                            `<option value="${venue.venue_code}" ${selected}>
                        ${venue.venue_name}
                    </option>`
                        );
                    });
                });

                // Trigger change event on page load to handle old/existing selections
                $(document).ready(function() {
                    const oldCenter = "{{ old('center', $chiefInvigilator->ci_center_id ?? '') }}";
                    if (oldCenter) {
                        $('#center').val(oldCenter).trigger('change');
                    }
                });
            </script>
        @endif
    @endpush
    @include('partials.theme')

@endsection
