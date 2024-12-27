@extends('layouts.app')

@section('title', 'District Collectorate')

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

            </div>
            <div class="row">
                <!-- [ basic-table ] start -->
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-sm-flex align-items-center justify-content-between">
                                <h5 class="mb-3 mb-sm-0">Route View</h5>
                                <div>
                                    <a href="{{ route('exam-materials-route.create', $examId) }}"
                                        class="btn btn-outline-success">Add Route</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body table-border-style">
                            <!-- Filter options -->
                            <!-- Filter options -->
                            <form id="filterForm" class="mb-3" method="GET"
                                action="#">
                                <div class="filter-item">
                                    <select class="form-select" id="centerCodeFilter" name="centerCode">
                                        <option value="">Select Center</option>
                                        @foreach ($centers as $center)
                                            <option value="{{ $center->center_code }}"
                                                {{ request('centerCode') == $center->center_code ? 'selected' : '' }}>
                                                {{ $center->center_code }} - {{ $center->center_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="filter-item">
                                    <select class="form-select" id="examDateFilter" name="examDate">
                                        <option value="">Select Exam Date</option>
                                        @foreach ($examDates as $examDate)
                                            <option value="{{ $examDate }}"
                                                {{ request('examDate') == $examDate ? 'selected' : '' }}>
                                                {{ Carbon\Carbon::parse($examDate)->format('d-m-Y') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="btn-container">
                                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                                </div>
                                <div class="btn-container">
                                    <button type="button" id="resetButton"
                                        class="btn btn-secondary d-flex align-items-center"
                                        onclick="">
                                        <i class="ti ti-refresh me-2"></i> Reset
                                    </button>
                                </div>
                                <div class="btn-container">
                                    <a href="#" class="btn btn-light-primary d-flex align-items-center"
                                        data-pc-animate="just-me" data-bs-toggle="modal" data-bs-target="#qrCodeModal">
                                        <i class="feather icon-aperture mx-1"></i>Scan
                                    </a>
                                </div>
                            </form>


                            <table id="res-config" class="display table table-striped table-hover dt-responsive nowrap"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th>route no</th>
                                        <th>Center code</th>
                                        <th>Exam Date</th>
                                        <th>Halls</th>
                                        <th>Driver name</th>
                                        <th>Driver licenese no</th>
                                        <th>Driver mobile no</th>
                                        <th>Vehicle no</th>
                                        <th>Mobile team staff</th>
                                        <th>Mobile team mobile no</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($groupedRoutes as $route)
                                        <tr>
                                            <td>{{ $route['route_no'] }}</td>
                                            <td>{{ $route['center_code'] }}</td>
                                            <td>{{ Carbon\Carbon::parse($route['exam_date'])->format('d-m-Y') }}</td>
                                            <td>{{ $route['halls'] }}</td>
                                            <td>{{ $route['driver_name'] }}</td>
                                            <td>{{ $route['driver_license'] }}</td>
                                            <td>{{ $route['driver_phone'] }}</td>
                                            <td>{{ $route['vehicle_no'] }}</td>
                                            <td>{{ $route['mobileteam']->mobile_name ?? '' }}</td>
                                            <td>{{ $route['mobileteam']->mobile_phone ?? '' }}</td>
                                            <td>
                                                <a href="#" class="avtar avtar-xs btn-light-success"><i
                                                        class="ti ti-edit f-20"></i></a>
                                                <a href="#" class="avtar avtar-xs btn-light-success"
                                                    title="Change Status (Active or Inactive)" onclick="toggleIcon(this)">
                                                    <i class="ti ti-toggle-left f-20"></i>
                                                </a>
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
    </section>
    <!-- [ Main Content ] end -->
    @include('partials.footer')

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        @include('partials.datatable-export-js')
    @endpush

    @include('partials.theme')



@endsection
