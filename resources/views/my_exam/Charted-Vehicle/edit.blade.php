@extends('layouts.app')

@section('title', 'ROUTE - Edit')
@push('styles')
    <link rel="stylesheet" href="{{ asset('storage/assets/css/plugins/croppr.min.css') }}" />
    <style>
        .choices__list--dropdown .choices__item--selectable {
            padding-right: 0px;
        }
    </style>
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
                <form action="{{ route('charted-vehicle-routes.update', $route->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="exam_id" value="">
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
                                    @php
                                        use App\Services\AuthorizationService;
                                        $authService = app(AuthorizationService::class);
                                        $role = session('auth_role');
                                        $canEdit = $authService->hasPermission($role, 'create-charted-vehicle-route');

                                    @endphp
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Route no <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text"
                                                        class="form-control @error('route_no') is-invalid @enderror"
                                                        id="route_no" value="{{ old('route_no', $route->route_no) }}"
                                                        name="route_no" placeholder="001" required>
                                                    @error('route_no')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="exam_id">Exam<span
                                                            class="text-danger">*</span></label>
                                                    <select  {{$canEdit == false ? 'disabled' : ''}} class="form-control @error('exam_id') is-invalid @enderror"
                                                        id="exam_id" name="exam_id[]" multiple required>
                                                        <option value="">Select Exam</option>
                                                        @foreach ($exams as $exam)
                                                            <option value="{{ $exam->exam_main_no }}"
                                                                {{ in_array($exam->exam_main_no, old('exam_id', $route->exam_id ?? [])) ? 'selected' : '' }}>
                                                                {{ $exam->exam_main_notification }}
                                                                {{ $exam->exam_main_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('exam_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Charted Vehicle Driver Name<span
                                                            class="text-danger">*</span></label>
                                                    <input type="text"
                                                        class="form-control @error('driver_name') is-invalid @enderror"
                                                        id="driver_name" name="driver_name" placeholder="vijay"
                                                        value="{{ old('driver_name', $route->driver_details['name'] ?? '') }}"
                                                        required>
                                                    @error('driver_name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Charted Vehicle Driver Licenese No<span
                                                            class="text-danger">*</span></label>
                                                    <input type="text"
                                                        class="form-control @error('driver_licence_no') is-invalid @enderror"
                                                        id="driver_licence_no" name="driver_licence_no"
                                                        placeholder="DLR0101223"
                                                        value="{{ old('driver_licence_no', $route->driver_details['licence_no'] ?? '') }}"
                                                        required>
                                                    @error('driver_licence_no')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Charted Vehicle Driver Phone<span
                                                            class="text-danger">*</span></label>
                                                    <input type="tel"
                                                        class="form-control @error('phone') is-invalid @enderror"
                                                        id="phone" name="phone"
                                                        value="{{ old('phone', $route->driver_details['phone'] ?? '') }}"
                                                        placeholder="9434***1212" required>
                                                    @error('phone')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Charted Vehicle No<span
                                                            class="text-danger">*</span></label>
                                                    <input type="text"
                                                        class="form-control @error('vehicle_no') is-invalid @enderror"
                                                        id="vehicle_no" name="vehicle_no" placeholder="TN 01 2345"
                                                        value="{{ old('vehicle_no', $route->charted_vehicle_no ?? '') }}"
                                                        required>
                                                    @error('vehicle_no')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">OTL Locks <span
                                                            class="text-danger">*</span></label>
                                                    <input  {{$canEdit == false ? 'disabled' : ''}} type="text" class="form-control" id="otl_locks"
                                                        name="otl_locks[]" placeholder="OTL Locks"
                                                        value="{{ old('otl_locks', implode(',', $route->otl_locks ?? [])) }}">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">GPS Lock <span
                                                            class="text-danger">*</span></label>
                                                    <input  {{$canEdit == false ? 'disabled' : ''}} type="text" class="form-control" id="gps_lock"
                                                        name="gps_locks[]" placeholder="GPS Lock"
                                                        value="{{ old('gps_locks', implode(',', $route->gps_locks ?? [])) }}">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Police Constable Name<span
                                                            class="text-danger">*</span></label>
                                                    <input type="text"
                                                        class="form-control @error('police_constable') is-invalid @enderror"
                                                        id="police_constable" name="police_constable" placeholder="vijay"
                                                        value="{{ old('police_constable', $route->pc_details['name'] ?? '') }}"
                                                        required>
                                                    @error('police_constable')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Police Constable Phone<span
                                                            class="text-danger">*</span></label>
                                                    <input type="tel"
                                                        class="form-control @error('police_constable_phone') is-invalid @enderror"
                                                        id="police_constable_phone" name="police_constable_phone"
                                                        value="{{ old('police_constable_phone', $route->pc_details['phone'] ?? '') }}"
                                                        placeholder="9434***1212" required>
                                                    @error('police_constable_phone')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Police Constable IFHRMS No<span
                                                            class="text-danger">*</span></label>
                                                    <input type="text"
                                                        class="form-control @error('police_constable_ifhrms_no') is-invalid @enderror"
                                                        id="police_constable_ifhrms_no" name="police_constable_ifhrms_no"
                                                        placeholder="35123469851"
                                                        value="{{ old('police_constable_ifhrms_no', $route->pc_details['ifhrms_no'] ?? '') }}"
                                                        required>
                                                    @error('police_constable_ifhrms_no')
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
                                    <div class="card-header">
                                        <h5>Escort Vehicle Details</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Escort Vehicle No<span
                                                            class="text-danger">*</span></label>
                                                    <input type="text"
                                                        class="form-control @error('escort_vehicle_no') is-invalid @enderror"
                                                        id="escort_vehicle_no" name="escort_vehicle_no"
                                                        placeholder="TN 01 2345"
                                                        value="{{ old('escort_vehicle_no', $route->escort_vehicle_details['vehicle_no'] ?? '') }}"
                                                        required>
                                                    @error('escort_vehicle_no')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Escort Driver Name<span
                                                            class="text-danger">*</span></label>
                                                    <input type="text"
                                                        class="form-control @error('escort_driver_name') is-invalid @enderror"
                                                        id="escort_driver_name" name="escort_driver_name"
                                                        placeholder="vijay"
                                                        value="{{ old('escort_driver_name', $route->escort_vehicle_details['driver_name'] ?? '') }}"
                                                        required>
                                                    @error('escort_driver_name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Escort Driver Licenese No<span
                                                            class="text-danger">*</span></label>
                                                    <input type="text"
                                                        class="form-control @error('escort_driver_licence_no') is-invalid @enderror"
                                                        id="escort_driver_licence_no" name="escort_driver_licence_no"
                                                        placeholder="DLR0101223"
                                                        value="{{ old('escort_driver_licence_no', $route->escort_vehicle_details['driver_licence_no'] ?? '') }}"
                                                        required>
                                                    @error('escort_driver_licence_no')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Escort Driver Phone<span
                                                            class="text-danger">*</span></label>
                                                    <input type="tel"
                                                        class="form-control @error('escort_driver_phone') is-invalid @enderror"
                                                        id="escort_driver_phone" name="escort_driver_phone"
                                                        value="{{ old('escort_driver_phone', $route->escort_vehicle_details['driver_phone'] ?? '') }}"
                                                        placeholder="9434***1212" required>
                                                    @error('escort_driver_phone')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 mt-4">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5>Escort Duty Staff for Each District</h5>
                                        @hasPermission('create-escort-staff')
                                        <button type="button" class="btn btn-success add-card">Add Staff</button>
                                        @endhasPermission
                                    </div>
                                    <div class="card-body">
                                        <div id="escortstaffsContainer">
                                            @foreach ($route->escortstaffs as $index => $escortStaff)
                                                <div class="card mb-3" id="escortstaff-card-{{ $index }}">
                                                    <div
                                                        class="card-header d-flex justify-content-between align-items-center">
                                                        <h5 class="card-title">Escort Staff #{{ $index + 1 }}</h5>
                                                        @if ($index > 0)
                                                            <!-- Only show remove button for dynamically added cards -->
                                                            <button type="button" class="btn btn-danger remove-card"
                                                                data-card-id="{{ $index }}">Remove</button>
                                                        @endif
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <div class="mb-3"> <label class="form-label">District
                                                                        <span class="text-danger">*</span></label> <select
                                                                        name="escortstaffs[{{ $index }}][district]"
                                                                        class="form-control district-dropdown" required>
                                                                        <!-- Options will be populated dynamically -->
                                                                    </select> </div>
                                                            </div>
                                                           <div class="col-sm-6">
    <div class="mb-3">
        <label class="form-label">TNPSC Staff <span class="text-danger">*</span></label>
        <select {{ $canEdit == false ? 'disabled' : '' }} name="escortstaffs[{{ $index }}][tnpsc_staff]" class="form-control" required>
            <option disabled>Select TNPSC Staff</option>
            @foreach ($tnpscStaffs as $tnpscStaff)
                <option value="{{ $tnpscStaff->dept_off_id }}"
                    {{ $tnpscStaff->dept_off_id == ($escortStaff->tnpsc_staff_id ?? '') ? 'selected' : '' }}>
                    {{ $tnpscStaff->dept_off_name }}
                    @if (!empty($tnpscStaff->role))
                        - {{ $tnpscStaff->role->role_department }} {{ $tnpscStaff->role->role_name }}
                    @endif
                </option>
            @endforeach
        </select>
    </div>
</div>

                                                            <div class="col-sm-6">
                                                                <div class="mb-3"> <label class="form-label">SI Name
                                                                        <span class="text-danger">*</span></label> <input
                                                                        type="text"
                                                                        name="escortstaffs[{{ $index }}][si_name]"
                                                                        class="form-control"
                                                                        value="{{ $escortStaff->si_details['name'] }}"
                                                                        required /> </div>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <div class="mb-3"> <label class="form-label">SI Phone
                                                                        <span class="text-danger">*</span></label> <input
                                                                        type="text"
                                                                        name="escortstaffs[{{ $index }}][si_phone]"
                                                                        class="form-control"
                                                                        value="{{ $escortStaff->si_details['phone'] }}"
                                                                        required /> </div>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <div class="mb-3"> <label class="form-label">SI IFHRMS
                                                                        No <span class="text-danger">*</span></label>
                                                                    <input type="text"
                                                                        name="escortstaffs[{{ $index }}][si_ifhrms_no]"
                                                                        class="form-control"
                                                                        value="{{ $escortStaff->si_details['ifhrms_no'] }}"
                                                                        required />
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <div class="mb-3"> <label class="form-label">Revenue
                                                                        Staff Name <span
                                                                            class="text-danger">*</span></label> <input
                                                                        type="text"
                                                                        name="escortstaffs[{{ $index }}][revenue_staff_name]"
                                                                        class="form-control"
                                                                        value="{{ $escortStaff->revenue_staff_details['name'] }}"
                                                                        required /> </div>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <div class="mb-3"> <label class="form-label">Revenue
                                                                        Staff Phone <span
                                                                            class="text-danger">*</span></label> <input
                                                                        type="text"
                                                                        name="escortstaffs[{{ $index }}][revenue_phone]"
                                                                        class="form-control"
                                                                        value="{{ $escortStaff->revenue_staff_details['phone'] }}"
                                                                        required /> </div>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <div class="mb-3"> <label class="form-label">Revenue
                                                                        Staff IFHRMS No <span
                                                                            class="text-danger">*</span></label> <input
                                                                        type="text"
                                                                        name="escortstaffs[{{ $index }}][revenue_ifhrms_no]"
                                                                        class="form-control"
                                                                        value="{{ $escortStaff->revenue_staff_details['ifhrms_no'] }}"
                                                                        required /> </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 text-end btn-page">
                                <a href="{{ route('charted-vehicle-routes.index') }}"
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

    <script src="{{ asset('storage/assets/js/plugins/choices.min.js') }}"></script>

    <script>
        const centerSelect = new Choices('#exam_id', {
            removeItemButton: true,
            placeholderValue: 'Select Exams',
            multiple: true,
            itemSelectText: ''
        });

        var textRemove = new Choices(document.getElementById('otl_locks'), {
            delimiter: ',',
            editItems: true,
            maxItemCount: 5,
            removeItemButton: true,
            placeholderValue: 'Add OTL Locks',
        });
        var textRemoveGps = new Choices(document.getElementById('gps_lock'), {
            delimiter: ',',
            editItems: true,
            maxItemCount: 2,
            removeItemButton: true,
            placeholderValue: 'Add GPS Lock',
        });

        document.addEventListener('DOMContentLoaded', function() {
            let cardIndex = {{ $route->escortstaffs->count() }}; // Start from the number of existing escortstaffs
            let districts = []; // Global variable to store fetched districts

            function fetchDistricts(examIds) {
                fetch('{{ route('charted-vehicle-routes.get-districts-for-exam') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            exam_ids: examIds
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        districts = data;
                        updateDistrictDropdowns();
                    })
                    .catch(error => console.error('Error fetching districts:', error));
            }

            // Function to update district dropdowns for all cards 
            function updateDistrictDropdowns() {
                document.querySelectorAll('.district-dropdown').forEach(select => {
                    let selectedDistrict = select.getAttribute('data-selected-district');
                    select.innerHTML = '<option disabled>Select District</option>';
                    districts.forEach(district => {
                        const option = document.createElement('option');
                        option.value = district.district_code;
                        option.textContent = district.district_name;
                        if (district.district_code == selectedDistrict) {
                            option.selected = true;
                        }
                        select.appendChild(option);
                    });
                });
            }
            // Initial fetching of districts based on the current exams 
            let selectedExams = Array.from(document.getElementById('exam_id').selectedOptions).map(option => option
                .value);
            if (selectedExams.length > 0) {
                fetchDistricts(selectedExams);
            }

            document.getElementById('exam_id').addEventListener('change', function() {
                let selectedExams = Array.from(this.selectedOptions).map(option => option.value);
                if (selectedExams.length > 0) {
                    fetchDistricts(selectedExams);
                } else {
                    districts = [];
                    updateDistrictDropdowns();
                }
            });

            // Add a new card
            document.querySelector('.add-card').addEventListener('click', function(e) {
                e.preventDefault();

                const newCard = `
            <div class="card mb-3" id="escortstaff-card-${cardIndex}">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Escort Staff #${cardIndex + 1}</h5>
                    <button type="button" class="btn btn-danger remove-card" data-card-id="${cardIndex}">Remove</button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">District <span class="text-danger">*</span></label>
                                <select name="escortstaffs[${cardIndex}][district]" class="form-control district-dropdown" required>
                                    <option disabled selected>Select District</option>
                                    <!-- Options will be populated dynamically -->
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">TNPSC Staff <span class="text-danger">*</span></label>
                                <select name="escortstaffs[${cardIndex}][tnpsc_staff]" class="form-control" required>
                                    <option disabled selected>Select TNPSC Staff</option>
                                    <option value="Staff1">Staff1</option>
                                    <option value="Staff2">Staff2</option>
                                    <option value="Staff3">Staff3</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">SI Name <span class="text-danger">*</span></label>
                                <input type="text" name="escortstaffs[${cardIndex}][si_name]" class="form-control" placeholder="SI Name" required />
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">SI Phone <span class="text-danger">*</span></label>
                                <input type="text" name="escortstaffs[${cardIndex}][si_phone]" class="form-control" placeholder="SI Phone" required />
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">SI IFHRMS No <span class="text-danger">*</span></label>
                                <input type="text" name="escortstaffs[${cardIndex}][si_ifhrms_no]" class="form-control" placeholder="SI IFHRMS No" required />
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">Revenue Staff Name <span class="text-danger">*</span></label>
                                <input type="text" name="escortstaffs[${cardIndex}][revenue_staff_name]" class="form-control" placeholder="Revenue Staff Name" required />
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">Revenue Staff Phone <span class="text-danger">*</span></label>
                                <input type="text" name="escortstaffs[${cardIndex}][revenue_phone]" class="form-control" placeholder="Revenue Staff Phone" required />
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">Revenue Staff IFHRMS No <span class="text-danger">*</span></label>
                                <input type="text" name="escortstaffs[${cardIndex}][revenue_ifhrms_no]" class="form-control" placeholder="Revenue Staff IFHRMS No" required />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

                document.getElementById('escortstaffsContainer').insertAdjacentHTML('beforeend', newCard);
                updateDistrictDropdowns(); // Update the district dropdown for the new card
                cardIndex++;
            });

            document.getElementById('escortstaffsContainer').addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-card')) {
                    e.preventDefault();
                    const cardId = e.target.getAttribute('data-card-id');
                    document.getElementById(`escortstaff-card-${cardId}`).remove();
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const canEdit = {{ $canEdit ? 'true' : 'false' }};
            if (!canEdit) {
                // Get all inputs outside of escortstaffsContainer
                const inputs = document.querySelectorAll(
                    'input:not(#escortstaffsContainer input), select:not(#escortstaffsContainer select)');

                inputs.forEach(input => {
                    input.setAttribute('readonly', true);
                    if (input.tagName === 'SELECT') {
                        input.setAttribute('disabled', true);
                    }
                });
            }
        });
    </script>
    @include('partials.theme')
@endsection

