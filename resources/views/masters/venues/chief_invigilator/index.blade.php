@extends('layouts.app')

@section('title', 'Chief Invigilator')

@section('content')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('storage/assets/css/plugins/dataTables.bootstrap5.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('storage/assets/css/plugins/buttons.bootstrap5.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('storage/assets/css/plugins/responsive.bootstrap5.min.css') }}" />


        <style>
            /* Container and row adjustments */
            .dataTables_wrapper .container-fluid {
                padding: 0;
            }

            .dataTables_wrapper .row {
                display: flex;
                flex-wrap: wrap;
            }

            /* Ensure full width on small screens */
            .dataTables_wrapper .col-sm-12 {
                flex: 0 0 100%;
            }

            /* Adjust columns for medium and larger screens */
            .dataTables_wrapper .col-md-6 {
                flex: 0 0 50%;
            }

            /* Align buttons and controls */
            .dataTables_wrapper .d-flex {
                display: flex;
                align-items: center;
                justify-content: flex-end;
            }

            /* Adjust for specific DataTables controls */
            .dataTables_wrapper .dt-buttons {
                margin: 0;
                padding: 0;
            }

            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_filter {
                margin: 0;
                padding: 0;
                display: flex;
                align-items: center;
            }

            .dataTables_wrapper .dataTables_filter input {
                width: auto;
                /* Adjust width as needed */
            }

            /* Responsive adjustments for small screens */
            @media (max-width: 768px) {
                .dataTables_wrapper .col-md-6 {
                    flex: 0 0 100%;
                    margin-bottom: 1rem;
                }

                [data-pc-direction="ltr"] .flex-wrap {
                    flex-wrap: nowrap !important;
                }

                div.dt-container div.dt-length label {
                    display: none;
                }

                .dataTables_wrapper .d-flex {
                    justify-content: space-between;
                }

                .dataTables_wrapper .dataTables_length,
                .dataTables_wrapper .dataTables_filter {
                    flex-direction: column;
                    align-items: flex-start;
                }

            }

            @media (max-width: 421px) {
                div.dt-container div.dt-search {
                    margin-bottom: 18px;
                }
            }

            /* Flexbox container for the form */
            #filterForm {
                display: flex;
                flex-wrap: wrap;
                gap: 1rem;
                /* Adds space between items */
                align-items: center;
            }

            /* Flexbox item for filters */
            .filter-item {
                flex: 1 1 200px;
                /* Adjusts basis to a minimum width, grows and shrinks as needed */
            }

            /* Align button to the end */
            .btn-container {
                flex: 1 1 200px;
                /* Ensures button is on the same row */
                display: flex;
                justify-content: flex-end;
                /* Aligns the button to the right */
            }

            @media (max-width: 421px) {
                .btn-container {
                    /* justify-content: center; */
                }
            }

            .status-toggle {
                transition: all 0.3s ease;
            }

            .status-toggle.disabled {
                pointer-events: none;
                opacity: 0.6;
            }
        </style>
        <!-- Add these to your layout -->
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
                            <!-- <div class="page-header-title">
                                                                                                          <h2 class="mb-0"></h2>
                                                                                                        </div> -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->


            <!-- [ Main Content ] start -->

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
                <!-- [ basic-table ] start -->
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-sm-flex align-items-center justify-content-between">
                                <h5 class="mb-3 mb-sm-0">Chief Invigilator List</h5>
                                <div>
                                    <a href="{{ route('chief-invigilators.create') }}" class="btn btn-outline-success">Add
                                        Chief Invigilator</a>
                                </div>
                            </div>
                        </div>

                        <div class="card-body table-border-style">
                            <!-- Filter options -->
                            @if (session('auth_role') == 'headquarters')
                                <form id="filterForm" class="mb-3">
                                    {{-- District Filter --}}
                                    <div class="filter-item">
                                        <select class="form-select" id="districtFilter" name="district">
                                            <option value="">Select District</option>
                                            @foreach ($districts as $district)
                                                <option value="{{ $district->district_code }}"
                                                    {{ request('district') == $district->district_code ? 'selected' : '' }}>
                                                    {{ $district->district_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Center Filter --}}
                                    <div class="filter-item">
                                        <select class="form-select" id="centerFilter" name="center">
                                            <option value="">Select Center</option>
                                            {{-- @foreach ($centers as $center)
                                            <option value="{{ $center->ci_center_code }}"
                                                {{ request('center') == $center->center_code ? 'selected' : '' }}>
                                                {{ $center->center_name }}
                                            </option>
                                        @endforeach --}}
                                        </select>
                                    </div>

                                    {{-- Venue Filter --}}

                                    <div class="filter-item">
                                        <select class="form-select" id="venueFilter" name="venue">
                                            <option value="">Select Venue</option>
                                            {{-- @foreach ($venues as $venue)
                                            <option value="{{ $venue->ci_venue_code }}"
                                                {{ request('venue') == $venue->venue_code ? 'selected' : '' }}>
                                                {{ $venue->venue_name }}
                                            </option>
                                        @endforeach --}}
                                        </select>
                                    </div>


                                    <div class="btn-container">
                                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                                    </div>
                                    <a href="{{ url()->current() }}" class="btn btn-secondary"><i
                                            class="ti ti-refresh me-2"></i>Reset</a>
                                </form>
                            @endif


                            <table id="centersTable" class="display table table-striped table-hover dt-responsive nowrap"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>E-mail</th>
                                        <th>Phone</th>
                                        <th>E-mail Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ basic-table ] end -->
        </div>
        <!-- [ Main Content ] end -->
        </div>

    </section>
    <!-- [ Main Content ] end -->
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    @include('partials.footer')

    @push('scripts')
        @include('partials.datatable-export-js')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Use event delegation for dynamically loaded or paginated buttons
                document.body.addEventListener('click', function(e) {
                    // Find the closest toggle button if the click event occurs within it
                    const button = e.target.closest('.status-toggle');
                    if (!button) return; // Exit if no toggle button is found

                    e.preventDefault(); // Prevent default behavior of the link

                    // Disable the button during processing
                    button.classList.add('disabled');

                    // Get the Chief Invigilator ID from the data attribute
                    const chiefInvigilatorId = button.dataset.ciId;

                    // Send the request to toggle the status
                    fetch(`{{ url('/') }}/chief-invigilators/${chiefInvigilatorId}/toggle-status`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Toggle button classes to reflect the new status
                                button.classList.toggle('btn-light-success');
                                button.classList.toggle('btn-light-danger');

                                // Toggle the icon
                                const icon = button.querySelector('i');
                                if (icon.classList.contains('ti-toggle-right')) {
                                    icon.classList.remove('ti-toggle-right');
                                    icon.classList.add('ti-toggle-left');
                                } else {
                                    icon.classList.remove('ti-toggle-left');
                                    icon.classList.add('ti-toggle-right');
                                }

                                // Show success notification
                                showNotification(
                                    'Status Updated',
                                    data.message || 'Chief Invigilator status updated successfully',
                                    'success'
                                );
                            } else {
                                // Show error notification
                                showNotification(
                                    'Update Failed',
                                    data.message || 'Failed to update status',
                                    'error'
                                );
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showNotification(
                                'Error',
                                'An error occurred while updating status',
                                'error'
                            );
                        })
                        .finally(() => {
                            // Re-enable the button
                            button.classList.remove('disabled');
                        });
                });
            });
        </script>

        <script>
            // Check if jQuery is available
            if (typeof jQuery === 'undefined') {
                console.error('jQuery is not loaded. Please include it in your project.');
            }
            // Full list of centers
            const allCenters = @json($centers);
            // console.log(@json($districts));
            // District filter change event
            $('#districtFilter').on('change', function() {
                const selectedDistrictCode = $(this).val();
                // alert(selectedDistrictCode);
                const centerDropdown = $('#centerFilter'); // Corrected to #centerFilter

                // Clear previous options
                centerDropdown.empty();
                centerDropdown.append('<option value="">Select Center</option>');

                // Filter centers based on selected district
                const filteredCenters = allCenters.filter(center =>
                    center.center_district_id == selectedDistrictCode
                );

                // Populate centers
                filteredCenters.forEach(center => {
                    const selected = "{{ request('center') }}" == center.center_code ? 'selected' : '';
                    centerDropdown.append(
                        `<option value="${center.center_code}" ${selected}>
                    ${center.center_name}
                </option>`
                    );
                });
            });

            // Trigger change event on page load to handle old/existing selections
            $(document).ready(function() {
                const oldDistrict = "{{ request('district') }}";
                if (oldDistrict) {
                    $('#districtFilter').val(oldDistrict).trigger('change');
                }
            });
        </script>

        <script>
            // Full list of venues
            const allVenues = @json($venues);

            // Center filter change event
            $('#centerFilter').on('change', function() {
                const selectedCenterCode = $(this).val();
                const venueDropdown = $('#venueFilter'); // Corrected to #venueFilter

                // Clear previous options
                venueDropdown.empty();
                venueDropdown.append('<option value="">Select Venue</option>');

                // Filter venues based on selected center
                const filteredVenues = allVenues.filter(venue =>
                    venue.venue_center_id == selectedCenterCode
                );

                // Populate venues
                filteredVenues.forEach(venue => {
                    const selected = "{{ request('venue') }}" == venue.venue_code ? 'selected' : '';
                    venueDropdown.append(
                        `<option value="${venue.venue_code}" ${selected}>
                    ${venue.venue_name}
                </option>`
                    );
                });
            });

            // Trigger change event on page load to handle old/existing selections
            $(document).ready(function() {
                const oldCenter = "{{ request('center') }}";
                if (oldCenter) {
                    $('#centerFilter').val(oldCenter).trigger('change');
                }
            });
        </script>
        <script>
            $(document).ready(function() {
                let table = $('#centersTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{{ route('chief-invigilators.json') }}',
                        data: function(d) {
                            d.district = $('#districtFilter').val();
                            d.center = $('#centerFilter').val();
                            d.venue = $('#venueFilter').val();
                        },
                        complete: function(response) {
                            if (response.responseJSON.data.length === 0) {
                                // Hide the processing indicator
                                $('#centersTable_processing').hide();

                                // Append a custom message directly to the table body
                                $('#centersTable tbody').html(
                                    '<tr><td colspan="7" class="text-center">No chief invigilators found.</td></tr>'
                                    );
                            }
                        }
                    },
                    columns: [{
                            data: null,
                            name: 'index',
                            render: function(data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                        },
                        {
                            data: 'ci_image',
                            name: 'ci_image',
                            render: function(data, type, row) {
                                if (data) {
                                    return `<div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <img src="{{ asset('storage/') }}/${data}" alt="ci image" class="img-radius wid-40"/>
                            </div>
                        </div>`;
                                }
                                return `<div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <img src="{{ asset('storage/assets/images/user/avatar-4.jpg') }}" alt="default image" class="img-radius wid-40"/>
                        </div>
                    </div>`;
                            }
                        },
                        {
                            data: 'ci_name',
                            name: 'ci_name',
                            render: function(data) {
                                return `<div class="flex-grow-1 ms-3">
                        <h6 class="mb-0">${data}</h6>
                    </div>`;
                            }
                        },
                        {
                            data: 'ci_email',
                            name: 'ci_email'
                        },
                        {
                            data: 'ci_phone',
                            name: 'ci_phone'
                        },
                        {
                            data: 'ci_email_status',
                            name: 'ci_email_status',
                            render: function(data) {
                                return data ?
                                    '<i class="ti ti-circle-check text-success f-18"></i>' :
                                    '<i class="ti ti-alert-circle text-danger f-18"></i>';
                            }
                        },
                        {
                            data: null, // Allows access to all fields in the row
                            render: function(data, type, row) {
                                return `
                                <a href="{{ route('chief-invigilators.show', ':id') }}" class="avtar avtar-xs btn-light-success">
                                    <i class="ti ti-eye f-20"></i>
                                </a>
                                <a href="{{ route('chief-invigilators.edit', ':id') }}" class="avtar avtar-xs btn-light-success">
                                    <i class="ti ti-edit f-20"></i>
                                </a>
                                <a href="#"
                                    class="avtar avtar-xs status-toggle ${row.ci_status ? 'btn-light-success' : 'btn-light-danger'}"
                                    data-ci-id="${row.ci_id}" title="Change Status (Active or Inactive)">
                                    <i class="ti ti-toggle-${row.ci_status ? 'right' : 'left'} f-20"></i>
                                </a>
                                `.replace(/:id/g, row.ci_id);
                            }
                        }

                    ],
                    order: [
                        [2, 'asc']
                    ],
                    responsive: true,
                    searching: true,
                    search: {
                        return: true
                    },


                });

                // Apply filters
                $('#filterForm').on('submit', function(e) {
                    e.preventDefault();
                    table.draw();
                });

                // Reset filters
                $('.btn-secondary').on('click', function() {
                    $('#districtFilter').val('');
                    $('#centerFilter').val('');
                    $('#venueFilter').val('');
                    table.search('').draw();
                });
            });
        </script>
    @endpush

    @include('partials.theme')



@endsection
