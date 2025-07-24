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
                                <h5 class="mb-3 mb-sm-0">Chief Invigilator View List</h5>
                                {{-- <div>
                                    <a href="{{ route('chief-invigilators.create') }}" class="btn btn-outline-success">Add
                                        Chief Invigilator</a>
                                </div> --}}
                            </div>
                        </div>

                        <div class="card-body table-border-style">
                            <!-- Filter options -->

                            @hasPermission('ci-filter')
                                <div class="d-flex flex-wrap justify-content-between align-items-end mb-3 gap-3">

                                    {{-- Filter Form --}}
                                    <form id="filterForm" class="d-flex flex-wrap gap-2">
                                        <div class="filter-item">
                                            <input type="text" class="form-control" id="exam_id" name="exam_id"
                                                placeholder="Exam Notif. No." required>
                                        </div>

                                        @hasPermission('ci-district-filter')
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
                                        @endhasPermission

                                        <div class="filter-item">
                                            <select class="form-select" id="centerFilter" name="center">
                                                <option value="">Select Center</option>
                                            </select>
                                        </div>

                                        <div class="filter-item">
                                            <select class="form-select" id="venueFilter" name="venue">
                                                <option value="">Select Venue</option>
                                            </select>
                                        </div>

                                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                                        <a href="{{ url()->current() }}" class="btn btn-secondary">Reset</a>
                                    </form>

                                    {{-- Export Form aligned right --}}
                                    <form id="exportForm" method="GET" action="{{ route('chief-invigilators.export') }}">
                                        @csrf
                                        <input type="hidden" name="exam_id" id="export_exam_id">
                                        <input type="hidden" name="district" id="export_district">
                                        <input type="hidden" name="center" id="export_center">
                                        <input type="hidden" name="venue" id="export_venue">
                                        <button type="submit" class="btn btn-success">Export Excel</button>
                                    </form>

                                </div>
                            @endhasPermission
                            <table id="centersTable" class="display table table-striped table-hover"
                                style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Center Code</th>
                                        <th>Hall Code</th>
                                        <th>Hall Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
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
            // Check if jQuery is available
            if (typeof jQuery === 'undefined') {
                console.error('jQuery is not loaded. Please include it in your project.');
            }

            // Full list of centers
            const allCenters = @json($centers);
            const userRole = "{{ $role }}"; // Get the logged-in user's role
            const userDistrict =
                "{{ $user->district_code ?? '' }}"; // Get the logged-in user's district (if applicable)
            console.log(userDistrict);
            $(document).ready(function() {
                const districtFilter = $('#districtFilter');
                const centerFilter = $('#centerFilter');

                // Function to filter centers based on district
                function filterCenters(districtCode) {
                    centerFilter.empty();
                    centerFilter.append('<option value="">Select Center</option>');

                    const filteredCenters = allCenters.filter(center => center.center_district_id == districtCode);
                    filteredCenters.forEach(center => {
                        const selected = "{{ request('center') }}" == center.center_code ? 'selected' : '';
                        centerFilter.append(
                            `<option value="${center.center_code}" ${selected}>
                        ${center.center_name}
                    </option>`
                        );
                    });
                }

                // Role-based filtering
                if (userRole === 'district' && userDistrict) {
                    // District user - Auto-select district & disable dropdown
                    districtFilter.val(userDistrict).prop('disabled', true);
                    filterCenters(userDistrict);
                } else {
                    // Department officer - Allow changing district
                    districtFilter.on('change', function() {
                        filterCenters($(this).val());
                    });

                    // Handle existing selections on page load
                    const oldDistrict = "{{ request('district') }}";
                    if (oldDistrict) {
                        districtFilter.val(oldDistrict).trigger('change');
                    }
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
                    const selected = "{{ request('venue') }}" == venue.venue_id ? 'selected' : '';
                    venueDropdown.append(
                        `<option value="${venue.venue_id}" ${selected}>
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
            let table;

            function initializeDataTable() {
                // Destroy existing table before reinitializing
                if ($.fn.DataTable.isDataTable('#centersTable')) {
                    $('#centersTable').DataTable().clear().destroy();
                }

                table = $('#centersTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{{ route('chief-invigilators.ci-view-page-json') }}',
                        type: 'GET',
                        data: function(d) {
                            d.exam_id = $('#exam_id').val();
                            d.district = $('#districtFilter').val();
                            d.center = $('#centerFilter').val();
                            d.venue = $('#venueFilter').val();
                        },
                        error: function(xhr) {
                            console.error("❌ AJAX Error:", xhr.responseText);
                        }
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'ci_center_id',
                            name: 'ci_center_id'
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
                            data: 'ci_email',
                            name: 'ci_email'
                        },
                        {
                            data: 'ci_phone',
                            name: 'ci_phone'
                        }
                    ],
                    responsive: true,
                    language: {
                        emptyTable: "No data available for the selected filters"
                    },
                    initComplete: function() {
                        $('#centersTable')
                            .removeClass()
                            .addClass('display table table-striped table-hover dt-responsive nowrap');
                    }
                });
            }

            function updateExportForm() {
                $('#export_exam_id').val($('#exam_id').val());
                $('#export_district').val($('#districtFilter').val());
                $('#export_center').val($('#centerFilter').val());
                $('#export_venue').val($('#venueFilter').val());
            }

            $(document).ready(function() {
                $('#filterForm').on('submit', function(e) {
                    e.preventDefault();
                    initializeDataTable();
                    updateExportForm(); // updates export form
                });

                $('.btn-secondary').on('click', function() {
                    $('#exam_id, #districtFilter, #centerFilter, #venueFilter').val('');
                    if ($.fn.DataTable.isDataTable('#res-config')) {
                        table.clear().draw();
                    }
                    updateExportForm(); // clear export form
                });

                $('#exam_id, #districtFilter, #centerFilter, #venueFilter').on('change', updateExportForm);

                // Add this export AJAX submit handler here:
                $('#exportForm').on('submit', function(e) {
                    e.preventDefault();

                    $.ajax({
                        url: $(this).attr('action'),
                        method: 'POST',
                        data: $(this).serialize(),
                        xhrFields: {
                            responseType: 'blob' // Important: to handle file blob
                        },
                        success: function(data, status, xhr) {
                            let filename = xhr.getResponseHeader('Content-Disposition')
                                ?.split('filename=')[1]
                                ?.replace(/"/g, '') || 'export.xlsx';
                            let blob = new Blob([data], {
                                type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                            });
                            let link = document.createElement('a');
                            link.href = window.URL.createObjectURL(blob);
                            link.download = filename;
                            document.body.appendChild(link);
                            link.click();
                            link.remove();
                        },
                        error: function(xhr) {
                            alert('Export failed or unauthorized. Check console.');
                            console.error(xhr);
                        }
                    });
                });
            });
        </script>
    @endpush

    @include('partials.theme')



@endsection
