@extends('layouts.app')

@section('title', 'Support')

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
                <!-- [ basic-table ] start -->
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-sm-flex align-items-center justify-content-between">
                                <h5 class="mb-3 mb-sm-0">Support</h5>
                            </div>
                        </div>
                        <div class="card-body table-border-style">
                         
                            <table class="display table table-striped table-hover dt-responsive nowrap"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Department Name & Section</th>
                                        <th>Contact Number(s)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                    <td>1</td>
                                    <td>Infrastructure Department - ID (Technical Support for TNPSC EMS App)</td>
                                    <td>044-25300385, 303, 302, 440</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Application Processing Department - APD</td>
                                    <td>044-25300318, 439</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Rules Notification Department - RND-TECH</td>
                                    <td>044-25300312</td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Evaluation Department - ED</td>
                                    <td>8072356547</td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>Confidential Department - QD</td>
                                    <td>044-25300353</td>
                                </tr>
                                <tr>
                                    <td>6</td>
                                    <td>Vehicles Machineries Department - VMD</td>
                                    <td>044-25300444</td>
                                </tr>
                                <tr>
                                    <td>7</td>
                                    <td>Verification Stationary Department - VSD</td>
                                    <td>044-25300326</td>
                                </tr>
                                <tr>
                                    <td>8</td>
                                    <td>Monitoring Coordination Department - MCD</td>
                                    <td>044-25300305</td>
                                </tr>
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
        @include('partials.datatable-export-js')
    @endpush

    @include('partials.theme')



@endsection
