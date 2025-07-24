@extends('layouts.app')

@section('title', 'Delivery Report')

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
                                <h2 class="mb-0">Delivery Report</h2>
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
                            <h5>Filter Delivery Report</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('delivery-report.report.generate') }}" target="_blank" method="GET"
                                id="filterForm">
                                @csrf
                                <div class="row">
                                    <!-- Notification No -->
                                    <div class="col-md-4 mb-3">
                                        <label for="notification_no" class="form-label">Notification No</label>
                                        <input type="text" name="notification_no" id="notification_no"
                                            class="form-control" placeholder="Enter Notification No" />
                                    </div>
                                    <!-- Exam Date -->
                                    <div class="col-md-4 mb-3">
                                        <label for="exam_date" class="form-label">Exam Date</label>
                                        <select name="exam_date" id="exam_date" class="form-control">
                                            <option value="" selected>Select Exam Date</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="category" class="form-label">Category</label>
                                        <select name="category" id="category" class="form-control">
                                            <option value="" selected>Select</option>
                                            <option value="all">All</option>
                                            <option value="district">District</option>
                                            <option value="center">Center</option>
                                        </select>
                                    </div>

                                    <!-- District Dropdown -->
                                    <div class="col-md-4 mb-3" id="district-container" style="display: none;">
                                        <label for="district" class="form-label">District</label>
                                        <select name="district" id="district" class="form-control">
                                            <option value="" selected>Select District</option>
                                        </select>
                                    </div>

                                    <!-- Center Dropdown -->
                                    <div class="col-md-4 mb-3" id="center-container" style="display: none;">
                                        <label for="center" class="form-label">Center</label>
                                        <select name="center" id="center" class="form-control">
                                            <option value="" selected>Select Center</option>
                                        </select>
                                    </div>



                                </div>

                                <!-- Submit Button -->
                                <div class="row">
                                    <div class="col-md-12 text-end">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- [ Filter Form ] end -->
            </div>
        </div>
    </section>
    <!-- [ Main Content ] end -->

    @include('partials.footer')

    @push('scripts')
        @include('partials.datatable-export-js')
        <script>
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
                                    examDateSelect.appendChild(option);
                                });
                            }

                            // Populate sessions
                            // const sessionSelect = document.getElementById('session');
                            // sessionSelect.innerHTML = '<option value="" selected>Select Session</option>';
                            // if (data.sessions && data.sessions.length > 0) {
                            //     data.sessions.forEach(session => {
                            //         const option = document.createElement('option');
                            //         option.value = session;
                            //         option.textContent = session;
                            //         sessionSelect.appendChild(option);
                            //     });
                            // }
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
