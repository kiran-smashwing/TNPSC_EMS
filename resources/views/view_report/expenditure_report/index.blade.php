@extends('layouts.app')

@section('title', 'Expenditure Statement')

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
                                <h2 class="mb-0">Expenditure Statement</h2>
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
                            <h5>Filter Expenditure Statement</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('filter.expenditure') }}" method="GET">
                                @csrf
                                <div class="row">
                                    <!-- Notification No -->
                                    <div class="col-md-4 mb-3">
                                        <label for="notification_no" class="form-label">Notification No</label>
                                        <input type="text" name="notification_no" id="notification_no"
                                            class="form-control" placeholder="Enter Notification No"
                                            value="{{ request('notification_no') }}" />
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="row">
                                    <div class="col-md-12 text-end">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                        <a href="{{ route('expenditure-statment.report') }}"
                                            class="btn btn-secondary">Reset</a>

                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
                <!-- [ Filter Form ] end -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Expenditure Statement</h5>
                        </div>
                        <div class="card-body">
                            <table id="res-config" class="display table table-striped table-hover dt-responsive nowrap"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>District</th>
                                        <th>Center</th>
                                        <th>Hall Code</th>
                                        <th>Venue Name</th>
                                        <th>Amount Received (Rs.)</th>
                                        <th>Amount Spent (Rs.)</th>
                                        <th>Balance Returned (Rs.)</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($attendance_data) && is_array($attendance_data))
                                        @forelse ($attendance_data as $index => $data)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $data['district'] ?? 'N/A' }}</td>
                                                <td>{{ $data['center'] ?? 'N/A' }}</td>
                                                <td>{{ $data['hall_code'] ?? 'N/A' }}</td>
                                                <td>{{ $data['venue_name'] ?? 'N/A' }}</td>
                                                <td>{{ $data['amountReceived'] ?? '0' }}</td>
                                                <td>{{ $data['totalAmountSpent'] ?? '0' }}</td>
                                                <td>{{ $data['balanceAmount'] ?? '0' }}</td>
                                                <td>
                                                    <a href="{{ route('download.expenditure.report', ['examid' => $exam_main_no]) }}"
                                                        class="me-2 btn btn-sm btn-light-success">
                                                        <i class="feather icon-download mx-1"></i> View
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center">No records found</td>
                                            </tr>
                                        @endforelse
                                    @endif
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
            $(document).ready(function() {
                var table; // Declare the DataTable variable

                // Handle form submission and initialize/reload DataTable
                $('#filterForm').submit(function(e) {
                    e.preventDefault();
                    var notification_no = $('#notification_no').val().trim();

                    if (!notification_no) {
                        alert("Please enter a Notification No.");
                        return;
                    }

                    // Check if DataTable is already initialized
                    if ($.fn.DataTable.isDataTable("#res-config")) {
                        table.destroy(); // Destroy the previous instance before reinitializing
                    }

                    // Initialize DataTable with new notification number
                    table = $('#res-config').DataTable({
                        processing: true,
                        serverSide: false, // Set to true if handling large datasets
                        destroy: true,
                        ajax: {
                            url: "{{ route('filter.expenditure') }}",
                            type: 'GET',
                            data: function(d) {
                                d.notification_no = notification_no; // Pass entered notification_no
                            },
                            dataSrc: function(json) {
                                console.log("Filtered Exam Data:", json.data);
                                return json.data || []; // Ensure data is always an array
                            },
                            error: function(xhr, error, thrown) {
                                console.error("DataTable AJAX Error:", error);
                            }
                        },
                        columns: [{
                                data: 'id',
                                name: 'id'
                            },
                            {
                                data: 'district',
                                name: 'district'
                            },
                            {
                                data: 'center',
                                name: 'center'
                            },
                            {
                                data: 'hall_code',
                                name: 'hall_code'
                            },
                            {
                                data: 'venue_name',
                                name: 'venue_name'
                            },
                            {
                                data: 'amount_received',
                                name: 'amount_received'
                            },
                            {
                                data: 'amount_spent',
                                name: 'amount_spent'
                            },
                            {
                                data: 'balance_returned',
                                name: 'balance_returned'
                            },
                            {
                                data: null,
                                render: function(data, type, row) {
                                    return `<a href="/expenditure/${row.id}" class="btn btn-primary btn-sm">View</a>`;
                                }
                            }
                        ]
                    });
                });
            });
        </script>




        {{-- <script>
            document.getElementById('notification_no').addEventListener('blur', function() {
                const notificationNo = this.value.trim();

                if (notificationNo) {
                    fetch(`{{ route('attendance.dropdown') }}?notification_no=${notificationNo}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Invalid response from the server');
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log(data.user); // Debugging: Log user info
                            console.log(data.districts); // Debugging: Log user info
                            console.log(data.centers); // Debugging: Log user info
                            console.log(data.examDates); // Debugging: Log user info
                            console.log(data.sessions); // Debugging: Log user info
                            console.log(data.centerCodeFromSession); // Debugging: Log user info

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
                                    examDateSelect.appendChild(option);
                                });
                            }

                            // Populate sessions
                            const sessionSelect = document.getElementById('session');
                            sessionSelect.innerHTML = '<option value="" selected>Select Session</option>';
                            if (data.sessions && data.sessions.length > 0) {
                                data.sessions.forEach(session => {
                                    const option = document.createElement('option');
                                    option.value = session;
                                    option.textContent = session;
                                    sessionSelect.appendChild(option);
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching dropdown data:', error);
                            // alert('Failed to fetch data. Please try again later.');
                        });
                } else {
                    // alert('Please enter a valid notification number.');
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
            document.getElementById('category').addEventListener('change', function () {
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
        </script> --}}
        {{-- <script>
            document.getElementById('notification_no').addEventListener('blur', function() {
                const notificationNo = this.value.trim();
    
                if (notificationNo) {
                    fetch(`{{ route('attendance.dropdown') }}?notification_no=${notificationNo}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Invalid response from the server');
                            }
                            return response.json();
                        })
                        .then(data => {
                            // Populate exam dates
                            const examDateSelect = document.getElementById('exam_date');
                            examDateSelect.innerHTML = '<option value="" selected>Select Exam Date</option>';
                            if (data.examDates && data.examDates.length > 0) {
                                data.examDates.forEach(date => {
                                    const option = document.createElement('option');
                                    option.value = date;
                                    option.textContent = date;
                                    examDateSelect.appendChild(option);
                                });
                            }
    
                            // Populate sessions
                            const sessionSelect = document.getElementById('session');
                            sessionSelect.innerHTML = '<option value="" selected>Select Session</option>';
                            if (data.sessions && data.sessions.length > 0) {
                                data.sessions.forEach(session => {
                                    const option = document.createElement('option');
                                    option.value = session;
                                    option.textContent = session;
                                    sessionSelect.appendChild(option);
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching dropdown data:', error);
                        });
                }
            });
        </script> --}}
    @endpush
    @include('partials.theme')

@endsection
