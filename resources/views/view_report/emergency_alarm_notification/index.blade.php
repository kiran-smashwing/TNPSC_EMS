@extends('layouts.app')

@section('title', 'Emergency Alarm Notifications')

@section('content')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('storage/assets/css/plugins/dataTables.bootstrap5.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('storage/assets/css/plugins/buttons.bootstrap5.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('storage/assets/css/plugins/responsive.bootstrap5.min.css') }}" />
        <!-- Add your existing styles here -->
    @endpush

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

    <!-- [ Main Content ] start -->
    <section class="pc-container">
        <div class="pc-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">Emergency Alarm Notifications</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            <div class="row">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- [ Filter Form ] start -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Filter Emergency Alarm Notifications</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('emergency-alarm-notification.report') }}" method="GET">
                                <div class="row">
                                    <!-- Notification No -->
                                    <div class="col-md-4 mb-3">
                                        <label for="notification_no" class="form-label">Notification No</label>
                                        <input type="text" value="{{ $filters['notification_no'] }}"
                                            name="notification_no" id="notification_no" class="form-control"
                                            placeholder="Enter Notification No" />
                                    </div>

                                    <!-- Exam Date -->
                                    <div class="col-md-4 mb-3">
                                        <label for="exam_date" class="form-label">Exam Date</label>
                                        <select name="exam_date" id="exam_date" class="form-control">
                                            <option value="">Select Exam Date</option>
                                            @if ($filters['exam_date'])
                                                <option value="{{ $filters['exam_date'] }}" selected>
                                                    {{ $filters['exam_date'] }}
                                                </option>
                                            @endif
                                        </select>
                                    </div>

                                    <!-- Session -->
                                    <div class="col-md-4 mb-3">
                                        <label for="session" class="form-label">Session</label>
                                        <select name="session" id="session" class="form-control">
                                            <option value="">Select Session</option>
                                            @if ($filters['session'])
                                                <option value="{{ $filters['session'] }}" selected>
                                                    {{ $filters['session'] }}
                                                </option>
                                            @endif
                                        </select>
                                    </div>

                                    <!-- Category -->
                                    <div class="col-md-4 mb-3">
                                        <label for="category" class="form-label">Category</label>
                                        <select name="category" id="category" class="form-control">
                                            <option value="">Select</option>
                                            <option value="all" {{ $filters['category'] == 'all' ? 'selected' : '' }}>
                                                All</option>
                                            <option value="district"
                                                {{ $filters['category'] == 'district' ? 'selected' : '' }}>District
                                            </option>
                                            <option value="center"
                                                {{ $filters['category'] == 'center' ? 'selected' : '' }}>Center</option>
                                        </select>
                                    </div>

                                    <!-- District -->
                                    <div class="col-md-4 mb-3" id="district-container"
                                        style="display: {{ in_array(request('category'), ['district', 'center']) ? 'block' : 'none' }}">
                                        <label for="district" class="form-label">District</label>
                                        <select name="district" id="district" class="form-control">
                                            <option value="">Select District</option>
                                            @foreach ($districts as $district)
                                                <option value="{{ $district->id }}"
                                                    {{ $filters['district'] == $district->id ? 'selected' : '' }}>
                                                    {{ $district->district_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Center -->
                                    <div class="col-md-4 mb-3" id="center-container"
                                        style="display: {{ request('category') == 'center' ? 'block' : 'none' }}">
                                        <label for="center" class="form-label">Center</label>
                                        <select name="center" id="center" class="form-control">
                                            <option value="">Select Center</option>
                                            @foreach ($centers as $center)
                                                <option value="{{ $center->center_code }}"
                                                    {{ $filters['center'] == $center->center_code ? 'selected' : '' }}>
                                                    {{ $center->center_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="row">
                                    <div class="col-md-12 text-end">
                                        <button type="submit" class="btn btn-primary">Filter</button>
                                        <a href="{{ route('emergency-alarm-notification.report') }}"
                                            class="btn btn-secondary">Reset</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- [ Filter Form ] end -->
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body table-border-style">
                            <table id="res-config" class="display table table-striped table-hover dt-responsive nowrap"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>District</th>
                                        <th>Center</th>
                                        <th>Venue</th>
                                        <th>Hall No</th>
                                        <th>Alert Type</th>
                                        <th>Details</th>
                                        <th>Remarks</th>
                                        <th>CI Info</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $alertTypeTitles = [
                                            'count_mismatch_reported' => 'Count Mismatch Reported',
                                            'discrepancy_reported' => 'Discrepancy Reported',
                                            'seal_damage_reported' => 'Damage of Seal/Tampered',
                                            'malpractice' => 'Malpractice Reported',
                                            'attendance_sheets_missing' => 'Attendance Sheets Missing',
                                            'questions_not_printed_in_order' => 'Questions Not Printed in Order',
                                            'omr_answer_sheet_missing' => 'OMR/Answer Sheet Missing',
                                            'others' => 'Other Issues',
                                        ];

                                        // Assuming $emergencyAlerts is an array of alerts

                                    @endphp
                                    @foreach ($emergencyAlerts as $key => $alarm)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $alarm->district->district_name }}</td>
                                            <td>{{ $alarm->center->center_name }}</td>
                                            <td>{{ $alarm->ci->venue->venue_name }}</td>
                                            <td>{{ $alarm->hall_code }}</td>
                                            <td>{{ $alarm->alert_type }}</td>
                                            <td>{{ $alertTypeTitles[$alarm->details] ?? 'Unknown Alert Type' }}</td>
                                            <!-- Updated line -->
                                            <td>{{ $alarm->remarks }}</td>
                                            <td>{{ $alarm->ci->ci_name }} || {{ $alarm->ci->ci_phone }} ||
                                                {{ $alarm->ci->ci_alternative_phone }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- [ Main Content ] end -->

    @include('partials.footer')

    @push('scripts')
        @include('partials.datatable-export-js')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Listen for changes to notification number input
                const notificationInput = document.getElementById('notification_no');
                notificationInput.addEventListener('change', function() {
                    if (this.value) {
                        updateDropdowns(this.value);
                    }
                });

                // Initial load if notification number exists
                const initialNotification = document.getElementById('notification_no').value;
                if (initialNotification) {
                    updateDropdowns(initialNotification);
                }

                function updateDropdowns(notificationNo) {
                    fetch(`{{ route('attendance.dropdown') }}?notification_no=${notificationNo}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Invalid response from the server');
                            }
                            return response.json();
                        })
                        .then(data => {
                            // console.log(data.user); 
                            //console.log(data.districts);
                            // console.log(data.centers); 
                            // console.log(data.examDates); 
                            //console.log(data.sessions); 
                            //console.log(data.centerCodeFromSession);

                            // Populate districts
                            const districtSelect = document.getElementById('district');
                            districtSelect.innerHTML = '<option value="" selected>Select District</option>';
                            if (data.districts && data.districts.length > 0) {
                                data.districts.forEach(district => {
                                    const option = document.createElement('option');
                                    option.value = district.id;
                                    option.textContent = district.name;
                                    districtSelect.appendChild(option);
                                });
                            }

                            // Populate centers
                            const centerSelect = document.getElementById('center');
                            centerSelect.innerHTML = '<option value="" selected>Select Center</option>';
                            if (data.centers && data.centers.length > 0) {
                                data.centers.forEach(center => {
                                    const option = document.createElement('option');
                                    option.value = center.id;
                                    option.textContent = center.name;
                                    centerSelect.appendChild(option);
                                });
                            }

                            // Populate exam dates
                            const examDateSelect = document.getElementById('exam_date');
                            examDateSelect.innerHTML = '<option value="" selected>Select Exam Date</option>';
                            if (data.examDates && data.examDates.length > 0) {
                                data.examDates.forEach(date => {
                                    const option = document.createElement('option');
                                    option.value = date;
                                    option.textContent = date;
                                    // Check if the date matches the selected session date
                                    if (date === "{{ $session->exam_sess_date }}") {
                                        option.selected = true;
                                    }
                                    examDateSelect.appendChild(option);
                                });
                            }

                            // Populate sessions dynamically
                            const sessionSelect = document.getElementById('session');
                            sessionSelect.innerHTML = '<option value="" selected>Select Session</option>';

                            if (data.sessions && data.sessions.length > 0) {
                                let fnSession = null;
                                let anSession = null;

                                // Add options dynamically and check for FN and AN
                                data.sessions.forEach(session => {
                                    const option = document.createElement('option');
                                    option.value = session;
                                    option.textContent = session;
                                    sessionSelect.appendChild(option);

                                    if (session.includes('FN')) fnSession = session;
                                    if (session.includes('AN')) anSession = session;
                                });

                                // Get the selected session from the request
                                const selectedSession = "{{ request('session') }}";

                                // If a selected session is provided in the request, override the default selection
                                if (selectedSession) {
                                    sessionSelect.value = selectedSession;
                                } else {
                                    // If no selected session is provided, apply the time-based selection logic
                                    if (data.sessions.length === 2 && fnSession && anSession) {
                                        const currentHour = new Date().getHours();
                                        sessionSelect.value = currentHour < 12 ? fnSession : anSession;
                                    } else if (data.sessions.length === 1) {
                                        // If only one session exists, select it
                                        sessionSelect.value = data.sessions[0];
                                    }
                                }
                            }

                        })
                        .catch(error => {
                            console.error('Error fetching dropdown data:', error);
                            // alert('Failed to fetch data. Please try again later.');
                        });
                }
            });
        </script>

        <script>
            // Full list of centers
            const allCenters = @json($centers);

            // District dropdown change event
            $('#district').on('change', function() {
                const selectedDistrictCode = $(this).val();
                const centerDropdown = $('#center');

                // Clear previous options
                centerDropdown.empty();
                centerDropdown.append('<option value="">Select Center</option>');

                // Filter centers based on selected district
                const filteredCenters = allCenters.filter(center =>
                    center.center_district_id == selectedDistrictCode
                );

                // Populate centers
                filteredCenters.forEach(center => {
                    centerDropdown.append(
                        `<option value="${center.center_code}">
                            ${center.center_name}
                        </option>`
                    );
                });
            });

            // Trigger change event on page load to handle old/existing selections
            $(document).ready(function() {
                const oldDistrict = "{{ request('district') }}";
                if (oldDistrict) {
                    $('#district').val(oldDistrict).trigger('change');
                }
            });
        </script>
        <script>
            document.getElementById('category').addEventListener('change', function() {
                let category = this.value;
                let districtContainer = document.getElementById('district-container');
                let centerContainer = document.getElementById('center-container');

                if (category === "all" || category === "") {
                    districtContainer.style.display = "none";
                    centerContainer.style.display = "none";
                } else if (category === "district") {
                    districtContainer.style.display = "block";
                    centerContainer.style.display = "none";
                } else if (category === "center") {
                    districtContainer.style.display = "block";
                    centerContainer.style.display = "block";
                }
            });
        </script>
    @endpush
    @include('partials.theme')

@endsection
