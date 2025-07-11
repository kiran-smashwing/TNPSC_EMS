@extends('layouts.app')

@section('title', 'ROUTE - Create')
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
                <form action="{{ route('charted-vehicle-routes.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
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
                                        <h5>Route - <span class="text-primary">Add</span></h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Route no <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text"
                                                        class="form-control  @error('route_no') is-invalid @enderror"
                                                        id="route_no" value="{{ old('route_no') }}" name="route_no"
                                                        placeholder="001" required>
                                                    @error('route_no')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="exam_id">Exam<span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-control @error('exam_id') is-invalid @enderror"
                                                        id="exam_id" name="exam_id[]" multiple required>
                                                        <option value="">Select Exam</option>
                                                        @foreach ($exams as $exam)
                                                            <option value="{{ $exam->exam_main_no }}">
                                                                {{ $exam->exam_main_notification }}
                                                                {{ $exam->exam_main_name }}</option>
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
                                                            class="text-danger"></span></label>
                                                    <input type="text"
                                                        class="form-control @error('driver_name') is-invalid @enderror"
                                                        id="driver_name" name="driver_name" placeholder="vijay"
                                                        value="{{ old('driver_name') }}">
                                                    @error('driver_name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Charted Vehicle Driver License No<span
                                                            class="text-danger"></span></label>
                                                    <input type="text"
                                                        class="form-control  @error('driver_licence_no') is-invalid @enderror"
                                                        id="driver_licence_no" name="driver_licence_no"
                                                        placeholder="DLR0101223" value="{{ old('driver_licence_no') }}">
                                                    @error('driver_licence_no')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="phone">Charted Vehicle Driver
                                                        Phone<span class="text-danger"></span></label>
                                                    <input type="tel"
                                                        class="form-control @error('phone') is-invalid @enderror "
                                                        id="phone" name="phone" value="{{ old('phone') }}"
                                                        placeholder="9434***1212">
                                                    @error('phone')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="vehicle_no">Charted Vehicle No <span
                                                            class="text-danger"></span></label>
                                                    <input type="text"
                                                        class="form-control @error('vehicle_no') is-invalid @enderror"
                                                        id="vehicle_no" name="vehicle_no" placeholder="TN20AA2024"
                                                        value="{{ old('vehicle_no') }}" maxlength="10">

                                                    @error('vehicle_no')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="otl_locks">OTL Locks <span
                                                            class="text-danger"></span></label>
                                                    <input type="text" class="form-control" id="otl_locks"
                                                        name="otl_locks[]" placeholder="OTL Locks">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="gps_lock">GPS Lock <span
                                                            class="text-danger"></span></label>
                                                    <input type="text" class="form-control" id="gps_lock"
                                                        name="gps_lock[]" placeholder="GPS Lock">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="police_constable">Police Constable
                                                        Name<span class="text-danger"></span></label>
                                                    <input type="text"
                                                        class="form-control @error('police_constable') is-invalid @enderror"
                                                        id="police_constable" name="police_constable" placeholder="vijay"
                                                        value="{{ old('police_constable') }}" >
                                                    @error('police_constable')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="police_constable_phone">Police
                                                        Constable Phone<span class="text-danger"></span></label>
                                                    <input type="tel"
                                                        class="form-control @error('police_constable_phone') is-invalid @enderror"
                                                        id="police_constable_phone" name="police_constable_phone"
                                                        value="{{ old('police_constable_phone') }}"
                                                        placeholder="9434***1212" >
                                                    @error('police_constable_phone')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="police_constable_ifhrms_no">Police
                                                        Constable
                                                        IFHRMS No<span class="text-danger"></span></label>
                                                    <input type="text"
                                                        class="form-control @error('police_constable_ifhrms_no') is-invalid @enderror"
                                                        id="police_constable_ifhrms_no" name="police_constable_ifhrms_no"
                                                        placeholder="35123469851"
                                                        value="{{ old('police_constable_ifhrms_no') }}" >
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
                                                    <label class="form-label" for="escort_vehicle_no">Escort Vehicle
                                                        No<span class="text-danger"></span></label>
                                                    <input type="text"
                                                        class="form-control @error('escort_vehicle_no') is-invalid @enderror"
                                                        id="escort_vehicle_no" name="escort_vehicle_no"
                                                        placeholder="TN01AA2345" maxlength="10"
                                                        value="{{ old('escort_vehicle_no') }}">
                                                    @error('escort_vehicle_no')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="escort_driver_name">Escort Driver
                                                        Name<span class="text-danger"></span></label>
                                                    <input type="text"
                                                        class="form-control @error('escort_driver_name') is-invalid @enderror"
                                                        id="escort_driver_name" name="escort_driver_name"
                                                        placeholder="vijay" value="{{ old('escort_driver_name') }}">
                                                    @error('escort_driver_name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="escort_driver_licence_no">Escort Driver
                                                        License No<span class="text-danger"></span></label>
                                                    <input type="text"
                                                        class="form-control @error('escort_driver_licence_no') is-invalid @enderror"
                                                        id="escort_driver_licence_no" name="escort_driver_licence_no"
                                                        placeholder="DLR0101223"
                                                        value="{{ old('escort_driver_licence_no') }}" >
                                                    @error('escort_driver_licence_no')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="escort_driver_phone">Escort Driver
                                                        Phone<span class="text-danger"></span></label>
                                                    <input type="tel"
                                                        class="form-control @error('escort_driver_phone') is-invalid @enderror"
                                                        id="escort_driver_phone" name="escort_driver_phone"
                                                        value="{{ old('escort_driver_phone') }}"
                                                        placeholder="9434***1212">
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
                                        <h5>District Source For Downward Journey</h5>
                                        <button type="button" class="btn btn-success add-card">Add District</button>
                                    </div>
                                    <div class="card-body">
                                        <div id="escortstaffsContainer">
                                            <!-- Default Card -->
                                            <div class="card mb-3" id="escortstaff-card-0">
                                                <div class="card-header">
                                                    <h5 class="card-title">Escort Staff #1</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">District <span
                                                                        class="text-danger">*</span></label>
                                                                <select name="escortstaffs[0][district]"
                                                                    class="form-control district-select" required>
                                                                    <option disabled selected>Select District</option>

                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">TNPSC Staff <span
                                                                        class="text-danger"></span></label>
                                                                <select name="escortstaffs[0][tnpsc_staff]"
                                                                    class="form-control" >
                                                                    <option disabled selected>Select TNPSC Staff</option>
                                                                    @foreach ($tnpscStaffs as $tnpscStaff)
                                                                        <option value="{{ $tnpscStaff->dept_off_id }}">
                                                                            {{ $tnpscStaff->dept_off_name }} -
                                                                            {{ $tnpscStaff->role->role_department ?? 'N/A' }}
                                                                            {{ $tnpscStaff->role->role_name ?? 'N/A' }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">SI Name <span
                                                                        class="text-danger"></span></label>
                                                                <input type="text" name="escortstaffs[0][si_name]"
                                                                    class="form-control" placeholder="SI Name" />
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">SI Phone <span
                                                                        class="text-danger"></span></label>
                                                                <input type="text" name="escortstaffs[0][si_phone]"
                                                                    class="form-control" placeholder="SI Phone" />
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">SI IFHRMS No <span
                                                                        class="text-danger"></span></label>
                                                                <input type="text" name="escortstaffs[0][si_ifhrms_no]"
                                                                    class="form-control" placeholder="SI IFHRMS No" />
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Revenue Staff Name <span
                                                                        class="text-danger"></span></label>
                                                                <input type="text"
                                                                    name="escortstaffs[0][revenue_staff_name]"
                                                                    class="form-control" placeholder="Revenue Staff Name"
                                                                     />
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Revenue Staff Phone <span
                                                                        class="text-danger"></span></label>
                                                                <input type="text"
                                                                    name="escortstaffs[0][revenue_phone]"
                                                                    class="form-control" placeholder="Revenue Staff Phone"
                                                                     />
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Revenue Staff IFHRMS No <span
                                                                        class="text-danger"></span></label>
                                                                <input type="text"
                                                                    name="escortstaffs[0][revenue_ifhrms_no]"
                                                                    class="form-control"
                                                                    placeholder="Revenue Staff IFHRMS No"  />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 text-end btn-page">
                                <a href="" class="btn btn-outline-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Create</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- [ Main Content ] end -->
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
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let cardIndex = 1; // Start from 1 because there is already one default card
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
                        // Convert object to array if needed
                        districts = Array.isArray(data) ? data : Object.values(data);
                        updateDistrictDropdowns();
                    })

                    .catch(error => console.error('Error fetching districts:', error));
            }
            // Function to update district dropdowns for all cards
            function updateDistrictDropdowns() {
                document.querySelectorAll('.district-select').forEach(select => {
                    const selectedValue = select.value; // Preserve selected value
                    select.innerHTML = '<option disabled selected>Select District</option>';
                    districts.forEach(district => {
                        const option = document.createElement('option');
                        option.value = district.district_code;
                        option.textContent = district.district_name;
                        select.appendChild(option);
                    });
                    if (selectedValue) {
                        select.value = selectedValue; // Reapply selected value if it exists
                    }
                });
            }
            // Fetch districts when an exam is selected
            document.getElementById('exam_id').addEventListener('change', function() {
                let selectedExams = Array.from(this.selectedOptions).map(option => option.value);
                if (selectedExams.length > 0) {
                    fetchDistricts(selectedExams);
                } else {
                    districts = [];
                    updateDistrictDropdowns();
                }
            });
            var tnpscStaffs = @json($tnpscStaffs);

            document.querySelector('.add-card').addEventListener('click', function(e) {
                e.preventDefault();
                const tnpscOptions = tnpscStaffs.map(staff =>
                    `<option value="${staff.dept_off_id}">${staff.dept_off_name} - ${staff.role?.role_department || 'N/A'} ${staff.role?.role_name || 'N/A'}</option>`
                ).join('');
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
                                <select name="escortstaffs[${cardIndex}][district]" class="form-control  district-select" required>
                                    <option disabled selected>Select District</option>
                                   
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">TNPSC Staff <span class="text-danger">*</span></label>
                                <select name="escortstaffs[${cardIndex}][tnpsc_staff]" class="form-control" >
                                    <option disabled selected>Select TNPSC Staff</option>
                                        ${tnpscOptions}  <!-- Insert TNPSC staff dynamically -->
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">SI Name <span class="text-danger">*</span></label>
                                <input type="text" name="escortstaffs[${cardIndex}][si_name]" class="form-control" placeholder="SI Name"  />
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">SI Phone <span class="text-danger">*</span></label>
                                <input type="text" name="escortstaffs[${cardIndex}][si_phone]" class="form-control" placeholder="SI Phone"  />
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">SI IFHRMS No <span class="text-danger">*</span></label>
                                <input type="text" name="escortstaffs[${cardIndex}][si_ifhrms_no]" class="form-control" placeholder="SI IFHRMS No"  />
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">Revenue Staff Name <span class="text-danger">*</span></label>
                                <input type="text" name="escortstaffs[${cardIndex}][revenue_staff_name]" class="form-control" placeholder="Revenue Staff Name"  />
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">Revenue Staff Phone <span class="text-danger">*</span></label>
                                <input type="text" name="escortstaffs[${cardIndex}][revenue_phone]" class="form-control" placeholder="Revenue Staff Phone"  />
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">Revenue Staff IFHRMS No <span class="text-danger">*</span></label>
                                <input type="text" name="escortstaffs[${cardIndex}][revenue_ifhrms_no]" class="form-control" placeholder="Revenue Staff IFHRMS No"  />
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

            // Remove a card
            document.getElementById('escortstaffsContainer').addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-card')) {
                    e.preventDefault();
                    const cardId = e.target.getAttribute('data-card-id');
                    document.getElementById(`escortstaff-card-${cardId}`).remove();
                    cardIndex--;
                }
            });
        });
    </script>
    @include('partials.theme')

@endsection
