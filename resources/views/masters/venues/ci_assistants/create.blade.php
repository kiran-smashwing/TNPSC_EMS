@extends('layouts.app')

@section('title', 'CI Assistants')
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
                <div class="col-sm-12">

                </div>
                <div class="tab-content">
                    <div>
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
                            <form action="{{ route('ci-assistant.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="col-lg-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Cheif Invigilator Assistant - <span class="text-primary">Add</span></h5>
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
                                                        <label class="form-label" for="district">District<span
                                                                class="text-danger">*</span></label>
                                                        <select class="form-control @error('district') is-invalid @enderror"
                                                            id="district" name="district" required>
                                                            <option value="">Select District Name</option>
                                                            @foreach ($districts as $district)
                                                                <option value="{{ $district->district_code }}">
                                                                    {{ $district->district_name }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('district')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <!-- Center Dropdown -->
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="center">Center<span
                                                                class="text-danger">*</span></label>
                                                        <select class="form-control @error('center') is-invalid @enderror"
                                                            id="center" name="center" required>
                                                            <option value="">Select Center Name</option>
                                                            <!-- Centers will be dynamically populated -->
                                                        </select>
                                                        @error('center')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <!-- Venue Dropdown -->
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="venue">Venue<span
                                                                class="text-danger">*</span></label>
                                                        <select class="form-control @error('venue') is-invalid @enderror"
                                                            id="venue" name="venue" required>
                                                            <option value="">Select Venue Name</option>
                                                            <!-- Venues will be dynamically populated -->
                                                        </select>
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
                                                            id="name" name="name" placeholder="Malarvizhi" required>
                                                        @error('name')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Email<span
                                                                class="text-danger">*</span></label>
                                                        <input type="email"
                                                            class="form-control @error('email') is-invalid @enderror"
                                                            id="email" name="email" placeholder="malarvizhi@***.in"
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
                                                            id="phone" name="phone" placeholder="9434***1212"
                                                            required>
                                                        @error('phone')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="designation">Designation <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text"
                                                            class="form-control @error('designation') is-invalid @enderror"
                                                            id="designation" name="designation"
                                                            placeholder="Asst Professor" required>
                                                        @error('designation')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 text-end btn-page">
                                    <a href="{{ route('ci-assistant') }}" class="btn btn-outline-secondary">Cancel</a>
                                    <button type="submit" class="btn btn-primary">Create</button>
                                </div>
                            </form>
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
        <script src="{{ asset('storage/assets/js/plugins/croppr.min.js') }}"></script>
        <script src="{{ asset('storage/assets/js/pages/page-croper.js') }}"></script>
        <script>
            document.getElementById('triggerModal').addEventListener('click', function() {
                var modal = new bootstrap.Modal(document.getElementById('cropperModal'));
                modal.show();
            });
        </script>
        <script>
            $(document).ready(function() {
                // Full list of centers and venues
                const allCenters = @json($centers);
                const allVenues = @json($venues);

                // Function to populate centers based on selected district
                function populateCenters(districtId, selectedCenter = '') {
                    const centerDropdown = $('#center');
                    centerDropdown.empty().append('<option value="">Select Center Name</option>');

                    if (!districtId) return; // Ensure valid district selection

                    const filteredCenters = allCenters.filter(center => center.center_district_id == districtId);

                    filteredCenters.forEach(center => {
                        const isSelected =
                            ("{{ old('center') }}" == center.center_code || selectedCenter == center
                                .center_code) ?
                            'selected' : '';
                        centerDropdown.append(
                            `<option value="${center.center_code}" ${isSelected}>
                            ${center.center_name}
                        </option>`
                        );
                    });

                    if (selectedCenter) {
                        centerDropdown.val(selectedCenter).trigger('change');
                    }
                }

                // Function to populate venues based on selected center
                function populateVenues(centerId, selectedVenue = '') {
                    const venueDropdown = $('#venue');
                    venueDropdown.empty().append('<option value="">Select Venue Name</option>');

                    if (!centerId) return; // Ensure valid center selection

                    const filteredVenues = allVenues.filter(venue => venue.venue_center_id == centerId);

                    filteredVenues.forEach(venue => {
                        const isSelected =
                            ("{{ old('venue') }}" == venue.venue_code || selectedVenue == venue.venue_code) ?
                            'selected' : '';
                        venueDropdown.append(
                            `<option value="${venue.venue_code}" ${isSelected}>
                            ${venue.venue_name}
                        </option>`
                        );
                    });

                    if (selectedVenue) {
                        venueDropdown.val(selectedVenue);
                    }
                }

                // District dropdown change event
                $('#district').on('change', function() {
                    const selectedDistrict = $(this).val();
                    populateCenters(selectedDistrict);
                });

                // Center dropdown change event
                $('#center').on('change', function() {
                    const selectedCenter = $(this).val();
                    populateVenues(selectedCenter);
                });

                // Load old/existing selections on page load
                const oldDistrict = "{{ old('district', $user->venue_district_id ?? '') }}";
                const oldCenter = "{{ old('center', $user->venue_center_id ?? '') }}";
                const oldVenue = "{{ old('venue', $user->venue_code ?? '') }}";

                if (oldDistrict) {
                    $('#district').val(oldDistrict);
                    populateCenters(oldDistrict, oldCenter);
                }

                // Ensure the venue loads only after centers are populated
                setTimeout(() => {
                    if (oldCenter) {
                        populateVenues(oldCenter, oldVenue);
                    }
                }, 300);
            });
        </script>
    @endpush

    @include('partials.theme')

@endsection
