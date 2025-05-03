@extends('layouts.app')

@section('title', 'Chief Invigilator')

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
    <!-- [ Header Topbar ] end -->

    <div class="pc-container">
        <div class="pc-content">
            <!-- Modal start-->
            @include('modals.cropper')
            <!-- Modal start-->
            <!-- [ Main Content ] start -->
            <div class="row">

                <div class="tab-content">
                    <form action="{{ route('chief-invigilators.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
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

                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>Chief Invigilator - <span class="text-primary">Add</span></h5>
                                    </div>
                                    <div class="card-body">

                                        <div class="row">
                                            <div class="col-sm-12 text-center mb-3">
                                                <div class="user-upload wid-75" data-pc-animate="just-me"
                                                    data-bs-toggle="modal" data-bs-target="#cropperModal">
                                                    <img src="{{ asset('storage/assets/images/user/avatar-4.jpg') }}"
                                                        id="previewImage" alt="Cropped Preview"
                                                        style="max-width: 100%; height: auto; object-fit: cover;">
                                                    <input type="hidden" name="cropped_image" id="cropped_image">
                                                    <label for="imageUpload" class="img-avtar-upload"></label>
                                                </div>
                                            </div>
                                            <!-- District Dropdown -->
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="district">District <span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-control @error('district') is-invalid @enderror"
                                                        id="district" name="district" required
                                                        {{ session('auth_role') == 'venue' ? 'disabled' : '' }}>
                                                        <option value="">Select District Name</option>
                                                        @foreach ($districts as $district)
                                                            <option value="{{ $district->district_code }}"
                                                                {{ isset($user) && $user->venue_district_id == $district->district_code ? 'selected' : '' }}
                                                                {{ isset($user) && $user->district_code == $district->district_code ? 'selected' : '' }}
                                                                {{ old('district') == $district->district_code ? 'selected' : '' }}>
                                                                {{ $district->district_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @if (session('auth_role') == 'venue')
                                                        <input type="hidden" name="district"
                                                            value="{{ $user->venue_district_id }}">
                                                    @elseif (session('auth_role') == 'district')
                                                    <input type="hidden" name="district"
                                                    value="{{ $user->district_code }}">
                                                    @endif
                                                    @error('district')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <!-- Center Dropdown -->
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="center">Center <span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-control @error('center') is-invalid @enderror"
                                                        id="center" name="center" required
                                                        {{ session('auth_role') == 'venue' ? 'disabled' : '' }}>
                                                        <option value="">Select Center Name</option>
                                                        <!-- Centers will be dynamically populated -->
                                                    </select>
                                                    @if (session('auth_role') == 'venue')
                                                        <input type="hidden" name="center"
                                                            value="{{ $user->venue_center_id }}">
                                                    @endif
                                                    @error('center')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Venue Dropdown -->
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="venue">Venue <span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-control @error('venue') is-invalid @enderror"
                                                        id="venue" name="venue" required
                                                        {{ session('auth_role') == 'venue' ? 'disabled' : '' }}>
                                                        <option value="">Select Venue Name</option>
                                                        <!-- Venues will be dynamically populated -->
                                                    </select>
                                                    @if (session('auth_role') == 'venue')
                                                        <input type="hidden" name="venue" value="{{ $user->venue_code }}">
                                                    @endif
                                                    @error('venue')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="name">Name <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text"
                                                        class="form-control @error('name') is-invalid @enderror"
                                                        id="name" name="name" placeholder="John Doe" required>
                                                    @error('name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="employee_id">Employee ID<span
                                                            class="text-danger">*</span></label>
                                                    <input type="text"
                                                        class="form-control @error('employee_id') is-invalid @enderror"
                                                        id="employee_id" name="employee_id" placeholder="EMP001"
                                                        required>
                                                    @error('employee_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="email">Email<span
                                                            class="text-danger">*</span></label>
                                                    <input type="email"
                                                        class="form-control @error('email') is-invalid @enderror"
                                                        id="email" name="email" placeholder="example@domain.com"
                                                        required>
                                                    @error('email')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="phone">Phone<span
                                                            class="text-danger">*</span></label>
                                                    <input type="tel"
                                                        class="form-control @error('phone') is-invalid @enderror"
                                                        id="phone" name="phone" placeholder="1234567890" required>
                                                    @error('phone')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            {{-- <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="alternative_phone">Alternative
                                                        Phone</label>
                                                    <input type="tel"
                                                        class="form-control @error('alternative_phone') is-invalid @enderror"
                                                        id="alternative_phone" name="alternative_phone"
                                                        placeholder="1234567890">
                                                    @error('alternative_phone')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div> --}}
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="designation">Designation<span
                                                            class="text-danger">*</span></label>
                                                    <input type="text"
                                                        class="form-control @error('designation') is-invalid @enderror"
                                                        id="designation" name="designation" placeholder="Professor"
                                                        required>
                                                    @error('designation')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="password">Password <span
                                                            class="text-danger">*</span></label>
                                                    <input type="password"
                                                        class="form-control @error('password') is-invalid @enderror"
                                                        id="password" name="password" placeholder="Enter your password"
                                                        required>
                                                    @error('password')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 text-end">
                            <a href="{{ route('chief-invigilators.index') }}"
                                class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>
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

    @push('scripts')
        <script src="{{ asset('storage/assets/js/plugins/croppr.min.js') }}"></script>
        <script src="{{ asset('storage/assets/js/pages/page-croper.js') }}"></script>
        <script>
            $(document).ready(function() {
                // Full list of centers from the server
                const allCenters = @json($centers);
                const userRole = "{{ session('auth_role') }}"; // Logged-in user's role
                const userDistrict = "{{ $user->district_code ?? '' }}"; // Logged-in user's district
                const userCenter = "{{ $user->venue_center_id ?? '' }}"; // Logged-in user's center
                console.log(allCenters, userRole, userDistrict)
        
                const districtDropdown = $('#district');
                const centerDropdown = $('#center');
        
                function filterCenters(selectedDistrictCode) {
                    // Clear previous options
                    centerDropdown.empty().append('<option value="">Select Center Name</option>');
        
                    if (selectedDistrictCode) {
                        // Filter centers based on selected district
                        const filteredCenters = allCenters.filter(center =>
                            center.center_district_id == selectedDistrictCode
                        );
        
                        // Populate centers
                        filteredCenters.forEach(center => {
                            const isSelected = center.center_code == "{{ old('center', $user->venue_center_id) }}" ? 'selected' : '';
                            centerDropdown.append(
                                `<option value="${center.center_code}" ${isSelected}>${center.center_name}</option>`
                            );
                        });
        
                        // Ensure the correct center is selected
                        const oldCenter = "{{ old('center', $user->venue_center_id) }}";
                        if (oldCenter) {
                            centerDropdown.val(oldCenter);
                        }
                    }
                }
        
                // Handle role-based district selection
                if (userRole === 'district' && userDistrict) {
                    // District user - Auto-select district & disable dropdown
                    districtDropdown.val(userDistrict).prop('disabled', true);
                    filterCenters(userDistrict);
                } 
                else if (userRole === 'venue' && userDistrict && userCenter) {
                    // Venue user - Pre-select district and center
                    districtDropdown.val(userDistrict);
                    filterCenters(userDistrict);
                    centerDropdown.val(userCenter);
                } 
                else {
                    // Other users - Allow district selection
                    districtDropdown.on('change', function() {
                        filterCenters($(this).val());
                    });
        
                    // Handle old selections on page load
                    const oldDistrict = "{{ old('district', request('district')) }}";
                    if (oldDistrict) {
                        districtDropdown.val(oldDistrict).trigger('change');
                    }
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
                    const selected = "{{ old('venue') }}" == venue.venue_code ? 'selected' : '';
                    const selectedVenue = "{{ $user->venue_code }}" == venue.venue_code ? 'selected' : '';
                    venueDropdown.append(
                        `<option value="${venue.venue_code}" ${selected} ${selectedVenue}>
                            ${venue.venue_name}
                        </option>`
                    );
                });
            });

            // Trigger change event on page load to handle old/existing selections
            $(document).ready(function() {
                const oldCenter = "{{ old('center', $user->venue_center_id ?? '') }}";
                if (oldCenter) {
                    $('#center').val(oldCenter).trigger('change');
                }
            });
        </script>
    @endpush
    @include('partials.theme')

@endsection
