@extends('layouts.app')

@section('title', 'Invigilator')
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
                        <form action="{{ route('invigilators.update', $invigilator->invigilator_id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
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
                                        <h5>Invigilator - <span class="text-primary">Edit</span></h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-12 text-center mb-3">
                                                <div class="user-upload wid-75" data-pc-animate="just-me"
                                                    data-bs-toggle="modal" data-bs-target="#cropperModal">
                                                    <img src="{{ $invigilator->invigilator_image
                                                        ? asset('storage/' . $invigilator->invigilator_image)
                                                        : asset('storage/assets/images/user/avatar-4.jpg') }}"
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
                                                        id="district" name="district" required {{ session('auth_role') == 'venue' ? 'disabled' : '' }}>
                                                        <option value="">Select District</option>
                                                        @foreach ($districts as $district)
                                                            <option value="{{ $district->district_code }}"
                                                                {{ old('district', $invigilator->invigilator_district_id) == $district->district_code ? 'selected' : '' }}>
                                                                {{ $district->district_name }}
                                                            </option>
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
                                                        id="center" name="center" required {{ session('auth_role') == 'venue' ? 'disabled' : '' }}>
                                                        <option value="">Select Center</option>
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
                                                        id="venue" name="venue" required {{ session('auth_role') == 'venue' ? 'disabled' : '' }}>
                                                        <option value="">Select Venue</option>
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
                                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" value="{{ old('name', $invigilator->invigilator_name) }}" name="name"
                                                        placeholder="Malarvizhi" required>
                                                    @error('name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Email<span
                                                            class="text-danger">*</span></label>
                                                    <input type="email" class="form-control @error('mail') is-invalid @enderror" id="mail" value="{{ old('mail', $invigilator->invigilator_email) }}" name="mail"
                                                        placeholder="malarvizhi@***.in" required>
                                                    @error('mail')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="phone">Phone<span
                                                            class="text-danger">*</span></label>
                                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" value="{{ old('phone', $invigilator->invigilator_phone) }}" name="phone"
                                                        placeholder="9434***1212" required>
                                                    @error('phone')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="designation">Designation <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" class="form-control @error('designation') is-invalid @enderror" id="designation"
                                                        name="designation" value="{{ old('designation', $invigilator->invigilator_designation) }}" placeholder="Asst Professor" required>
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
                                <a href="{{ route('invigilators.index') }}" class="btn btn-outline-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Update</button>
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
    <script src="{{ asset('storage/assets/js/plugins/croppr.min.js')}}"></script>
    <script src="{{ asset('storage/assets/js/pages/page-croper.js')}}"></script>
   
    <script>
        // Full list of centers
        const allCenters = @json($centers);

        // District dropdown change event
        $('#district').on('change', function() {

            const selectedDistrictCode = $(this).val();
            const centerDropdown = $('#center');

            // Clear previous options
            centerDropdown.empty();
            centerDropdown.append('<option value="">Select Center </option>');

            // Filter centers based on selected district
            const filteredCenters = allCenters.filter(center =>
                center.center_district_id == selectedDistrictCode
            );            

            // Populate centers
            filteredCenters.forEach(center => {
                const selected = "{{ old('center', $invigilator->invigilator_center_id) }}" == center.center_code ? 'selected' : '';
                centerDropdown.append(
                    `<option value="${center.center_code}" ${selected}>
                        ${center.center_name}
                    </option>`
                );
            });
        });

        // Trigger change event on page load to handle old/existing selections
        $(document).ready(function() {
                const oldDistrict = "{{ old('district', $invigilator->invigilator_district_id) }}";
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
                const selected = "{{ old('venue', $invigilator->invigilator_venue_id) }}" == venue.venue_code ? 'selected' : '';

                venueDropdown.append(
                    `<option value="${venue.venue_code}" ${selected}>
                        ${venue.venue_name}
                    </option>`
                );
            });
        });

        // Trigger change event on page load to handle old/existing selections
        $(document).ready(function() {
            const oldCenter= "{{ old('center', $invigilator->invigilator_center_id ?? '') }}";
            if (oldCenter) {
                $('#center').val(oldCenter).trigger('change');
            }
        });
    </script>
    @endpush

    @include('partials.theme')

@endsection
