@extends('layouts.app')

@section('title', 'Charted Vehicle Routes')

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
                    justify-content: center;
                }
            }
        </style>
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
                                <h5 class="mb-3 mb-sm-0">Vanduty Staff to Headquarters</h5>
                                <div>
                                    <a href="{{ route('charted-vehicle-routes.create') }}"
                                        class="btn btn-outline-success">Add Route</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body table-border-style">
                            <!-- Filter options -->
                            <form id="filterForm" class="mb-3" method="GET" action="#">
                                <div class="filter-item">
                                    <select class="form-select" id="centerCodeFilter" name="centerCode">
                                        <option value="">Select Center</option>

                                    </select>
                                </div>
                                <div class="filter-item">
                                    <select class="form-select" id="examDateFilter" name="examDate">
                                        <option value="">Select Exam Date</option>

                                    </select>
                                </div>
                                <div class="btn-container">
                                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                                </div>
                                <div class="btn-container">
                                    <button type="button" id="resetButton"
                                        class="btn btn-secondary d-flex align-items-center" onclick="">
                                        <i class="ti ti-refresh me-2"></i> Reset
                                    </button>
                                </div>
                            </form>

                            <table id="res-config" class="display table table-striped table-hover dt-responsive nowrap"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th>Route no</th>
                                        <th>Exam Notification</th>
                                        <th>Vehicle No</th>
                                        <th>OTL Locks</th>
                                        <th>GPS Locks</th>
                                        <th>District</th>
                                        {{-- <th>Mobile team staff</th>
                                        <th>Mobile team mobile no</th> --}}
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($routes as $route)
                                        <tr>
                                            <td>{{ $route->route_no }}</td>
                                            <td>{{ $route->exam_notifications }}</td>
                                            <td>{{ $route->charted_vehicle_no }}</td>
                                            <td>{{ is_array($route->otl_locks) ? implode(', ', $route->otl_locks) : $route->otl_locks }}
                                            </td>
                                            <td>{{ is_array($route->gps_locks) ? implode(', ', $route->gps_locks) : $route->gps_locks }}
                                            </td>
                                            <td> {{ $route->district_codes }}</td>
                                            <td>
                                                <a href="{{ route('charted-vehicle-routes.edit', $route['id']) }}"
                                                    class="avtar avtar-xs btn-light-success"><i
                                                        class="ti ti-edit f-20"></i></a>
                                                <a href="{{ route('viewTrunkboxes', $route['id']) }}"
                                                    class="avtar avtar-xs btn-light-success"><i
                                                        class="ti ti-checkbox  f-20"></i></a>
                                                <a href="#" class="avtar avtar-xs btn-light-success"
                                                    data-bs-toggle="modal" data-bs-target="#verifyAllMaterialsHandovered"
                                                    data-route-id="{{ $route['id'] }}" onclick="setVehicleId(this)">
                                                    <i class="ti ti-clipboard-check f-20"></i>
                                                </a>
                                                <a href="{{ route('bundle-packaging.report-handover-details', $route['id']) }}"
                                                    class="avtar avtar-xs btn-light-success"><i
                                                        class="ti ti-download f-20"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ basic-table ] end -->
        </div>
        <!-- [ Main Content ] end -->
        </div>
        @include('modals.verify-all-materials-handovered')

    </section>
    <!-- [ Main Content ] end -->
    @include('partials.footer')

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="{{ asset('storage/assets/js/plugins/sweetalert2.all.min.js') }}"></script>
        @include('partials.datatable-export-js')
    @endpush

    @include('partials.theme')



@endsection
