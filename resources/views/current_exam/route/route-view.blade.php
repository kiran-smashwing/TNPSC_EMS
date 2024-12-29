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
                                <a href="{{route('current-exam.routeCreate')}}" class="btn btn-outline-success">Add Route</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body table-border-style">
                        <!-- Filter options -->
                        <form id="filterForm" class="mb-3">
                            <div class="filter-item">
                                <select class="form-select" id="roleFilter" name="role">
                                    <option value="">Select Role</option>
                                    <option value="AD">AD</option>
                                    <option value="Manager">Manager</option>
                                    <option value="Staff">Staff</option>
                                </select>
                            </div>
                            <div class="filter-item">
                                <select class="form-select" id="districtFilter" name="district">
                                    <option value="">Select District</option>
                                    <option value="Vellore">Vellore</option>
                                    <option value="Chennai">Chennai</option>
                                    <option value="Coimbatore">Coimbatore</option>
                                </select>
                            </div>
                            <div class="filter-item">
                                <select class="form-select" id="centerCodeFilter" name="centerCode">
                                    <option value="">Select Center Code</option>
                                    <option value="00101">00101</option>
                                    <option value="00102">00102</option>
                                    <option value="00103">00103</option>
                                </select>
                            </div>
                            <div class="btn-container">
                                <button type="submit" class="btn btn-primary">Apply Filters</button>
                            </div>
                        </form>


                        <table id="res-config" class="display table table-striped table-hover dt-responsive nowrap" width="100%">
                            <thead>
                                <tr>
                                    <th>route no</th>
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
                                <tr>
                                    <td>001</td>
                                    <td>
                                     Govt High School - 001 (Vellore), VIT College - 002 (Vellore), Girls High School - 003 (Vellore)
                                    </td>
                                    <td>
                                      Rajan
                                    </td>
                                    <td>100100101</td>
                                    <td>9094500072</td>
                                    <td>TN01AS2345</td>
                                    <td>Arun - Designation</td>
                                    <td>9094500072</td>
                                    <td>
                                        <a href="#" class="avtar avtar-xs  btn-light-success"><i class="ti ti-eye f-20"></i></a>
                                        <a href="{{ route('current-exam.routeEdit') }}" class="avtar avtar-xs  btn-light-success"><i class="ti ti-edit f-20"></i></a>
                                        <a href="#" class="avtar avtar-xs  btn-light-success" title="Change Status (Active or Inactive)" onclick="toggleIcon(this)">
                                            <i class="ti ti-toggle-left f-20"></i> <!-- Toggle icon for 'Active' -->
                                        </a>
                                        <!-- <a href="#" class="avtar avtar-xs btn-link-secondary"><i class="ti ti-trash f-20"></i></a> -->
                                    </td>
                                    <script>
                                        function toggleIcon(element) {
                                            // Get the icon element inside the anchor tag
                                            const icon = element.querySelector('i');
                                    
                                            // Check the current class and toggle it
                                            if (icon.classList.contains('ti-toggle-left')) {
                                                icon.classList.remove('ti-toggle-left');
                                                icon.classList.add('ti-toggle-right');
                                            } else {
                                                icon.classList.remove('ti-toggle-right');
                                                icon.classList.add('ti-toggle-left');
                                            }
                                        }
                                    </script>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@include('partials.datatable-export-js')
@endpush

@include('partials.theme')



@endsection