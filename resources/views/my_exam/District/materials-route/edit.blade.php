@extends('layouts.app')

@section('title', 'ROUTE - Edit')
@push('styles')
    <link rel="stylesheet" href="{{ asset('storage/assets/css/plugins/croppr.min.css') }}" />
@endpush

@section('content')
    <!-- [ Pre-loader ] start -->
    <div class="page-loader">
        <div class="bar"></div>
    </div>
    <!-- [ Pre-loader ] End -->

    @include('partials.sidebar')
    @include('partials.header')

    <div class="pc-container">
        <div class="pc-content">
            <div class="row">
                <form action="{{ route('exam-materials-route.update', $routes->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="tab-content">
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
                                        <h5>Route - <span class="text-primary">Edit</span></h5>
                                    </div>
                                    <div class="card-body">

                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Route no <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text"
                                                        class="form-control @error('route_no') is-invalid @enderror"
                                                        id="route_no" name="route_no"
                                                        value="{{ old('route_no', $routes->route_no) }}" required>
                                                    @error('route_no')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Driver Name<span
                                                            class="text-danger"></span></label>
                                                    <input type="text"
                                                        class="form-control @error('driver_name') is-invalid @enderror"
                                                        id="driver_name" name="driver_name"
                                                        value="{{ old('driver_name', $routes->driver_name) }}">
                                                    @error('driver_name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Driver License No<span
                                                            class="text-danger"></span></label>
                                                    <input type="text"
                                                        class="form-control @error('driver_licence_no') is-invalid @enderror"
                                                        id="driver_licence_no" name="driver_licence_no"
                                                        value="{{ old('driver_licence_no', $routes->driver_license) }}"
                                                        >
                                                    @error('driver_licence_no')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Driver Phone<span
                                                            class="text-danger"></span></label>
                                                    <input type="tel"
                                                        class="form-control @error('driver_phone') is-invalid @enderror"
                                                        id="phone" name="driver_phone"
                                                        value="{{ old('driver_phone', $routes->driver_phone) }}">
                                                    @error('driver_phone')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Vehicle No<span
                                                            class="text-danger"></span></label>
                                                    <input type="text"
                                                        class="form-control @error('vehicle_no') is-invalid @enderror"
                                                        id="vehicle_no" name="vehicle_no"
                                                        value="{{ old('vehicle_no', $routes->vehicle_no) }}">
                                                    @error('vehicle_no')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
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
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="exam_date">Exam Date<span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-control @error('exam_date') is-invalid @enderror"
                                                        name="exam_date" required data-trigger id="choices-single-default">
                                                        <option value="" selected disabled>Select Exam Date</option>
                                                        @foreach ($examDates as $examDate)
                                                            <option value="{{ $examDate }}"
                                                                @if (Carbon\Carbon::parse($routes->exam_date)->format('d-m-Y') == $examDate) selected @endif>
                                                                {{ $examDate }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('exam_date')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label
                                                        class="form-label">{{ session('auth_role') == 'district' && $user->district_code != '01' ? 'Mobile Team Staff' : 'Van Duty Staff' }}<span
                                                            class="text-danger">*</span></label>
                                                    <select
                                                        class="form-control @error('mobile_staff') is-invalid @enderror"
                                                        name="mobile_staff" required data-trigger
                                                        id="choices-single-default">
                                                        @foreach ($mobileTeam as $staff)
                                                            @if (session('auth_role') == 'district' && $user->district_code != '01')
                                                                <option value="{{ $staff->mobile_id }}"
                                                                    {{ $routes->mobile_team_staff == $staff->id ? 'selected' : '' }}>
                                                                    {{ $staff->mobile_name }}
                                                                </option>
                                                            @else
                                                                <option value="{{ $staff->dept_off_id }}"
                                                                    {{ $routes->mobile_team_staff == $staff->dept_off_id ? 'selected' : '' }}>
                                                                    {{ $staff->dept_off_name }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                    @error('mobile_staff')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Center<span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-control" name="center_code[]" id="center_code"
                                                        multiple>
                                                        @foreach ($centers as $center)
                                                            <option value="{{ $center->center_code }}"
                                                                {{ in_array($center->center_code, json_decode($routes->center_code, true)) ? 'selected' : '' }}>
                                                                {{ $center->center_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Halls<span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-control" name="halls[]" id="hall_code" multiple>
                                                        <!-- Halls will be populated dynamically -->
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 text-end btn-page">
                                <a href="{{ route('exam-materials-route.index', $routes->exam_id) }}"
                                    class="btn btn-outline-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('partials.footer')
    @include('partials.theme')

    <script src="{{ asset('storage/assets/js/plugins/croppr.min.js') }}"></script>
    <script src="{{ asset('storage/assets/js/pages/page-croper.js') }}"></script>
    <script src="{{ asset('storage/assets/js/plugins/choices.min.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize hall data
            const hallsByCenter = @json($halls->groupBy('center_code'));

            // Get the currently selected halls from the route
            const selectedHalls = @json(is_array($routes->hall_code) ? $routes->hall_code : json_decode($routes->hall_code, true));

            // Ensure selectedHalls is an array of hall codes
            const selectedHallsArray = [];
            for (const center in selectedHalls) {
                selectedHallsArray.push(...selectedHalls[center]);
            }

            // Initialize select elements with Choices.js
            const centerSelect = new Choices('#center_code', {
                searchEnabled: true,
                removeItemButton: true,
                placeholder: true,
                multiple: true,
                placeholderValue: 'Select Center'
            });

            const hallSelect = new Choices('#hall_code', {
                removeItemButton: true,
                searchEnabled: true,
                placeholder: true,
                placeholderValue: 'Select Halls',
                multiple: true
            });

            // Function to update halls based on selected centers
            function updateHalls(centerCodes) {
                let halls = [];

                centerCodes.forEach(centerCode => {
                    if (hallsByCenter[centerCode]) {
                        halls = halls.concat(hallsByCenter[centerCode]);
                    }
                });

                // Clear existing options
                hallSelect.clearStore();

                // Add new options
                const choices = halls.map(hall => ({
                    value: hall.center_code+ ':' +hall.hall_code,
                    label: hall.center_code+ ' - ' +hall.hall_code,
                    selected: selectedHallsArray.includes(hall.hall_code)
                }));
                hallSelect.setChoices(choices, 'value', 'label', true);
            }

            // Initial load of halls based on pre-selected centers
            const initialCenters = Array.from(document.getElementById('center_code').selectedOptions).map(option =>
                option.value);
            if (initialCenters.length) {
                updateHalls(initialCenters);
            }

            // Update halls when center selection changes
            document.getElementById('center_code').addEventListener('change', function(e) {
                const selectedCenters = Array.from(e.target.selectedOptions).map(option => option.value);
                updateHalls(selectedCenters);
            });
        });
    </script>
@endsection
