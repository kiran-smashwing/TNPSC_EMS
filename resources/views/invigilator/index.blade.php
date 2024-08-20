@extends('layouts.app')

@section('title', 'Invigilator')

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
    }

    /* Responsive adjustments for small screens */
    @media (max-width: 768px) {
        .dataTables_wrapper .col-md-6 {
            flex: 0 0 100%;
            margin-bottom: 1rem;
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
        align-items: center;
    }

    /* Flexbox item for filters */
    .filter-item {
        flex: 1 1 200px;
    }

    /* Align button to the end */
    .btn-container {
        flex: 1 1 200px;
        display: flex;
        justify-content: flex-end;
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
<!-- [ Header Topbar ] end -->

<!-- [ Main Content ] start -->
<div class="pc-container">
    <div class="pc-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <!-- Breadcrumb Title or other content can go here -->
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->

        <!-- [ Main Content ] start -->
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-sm-flex align-items-center justify-content-between">
                            <h5>Invigilator List</h5>
                            <div>
                                <a href="{{ route('invigilator.create') }}" class="btn btn-outline-success">Add Invigilator</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
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
                        <!-- Filter Form End -->
                        <!-- Data Table -->
                        <table id="testing" class="display table table-striped table-hover dt-responsive nowrap" width="100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Venue Name</th>
                                    <th>E-mail</th>
                                    <th>Phone</th>
                                    <!-- <th>status</th> -->
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <img src="../assets/images/user/avatar-1.jpg" alt="user image" class="img-radius wid-40">
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="mb-0">Testing</h6>
                                        </div>
                                    </td>
                                    <td>Tesitng</td>
                                    <td>example@gmail.com</td>
                                    <td>+91-9094500072</td>
                                    <!-- <td class="text-success">Active</td> -->
                                    <td>
                                        <a href="#" class="avtar avtar-xs  btn-light-success"><i class="ti ti-eye f-20"></i></a>
                                        <a href="{{ route('invigilator.edit') }}" class="avtar avtar-xs  btn-light-success"><i class="ti ti-edit f-20"></i></a>
                                        <a href="#" class="avtar avtar-xs  btn-light-success" title="Change Status (Active or Inactive)">
                                            <i class="ti ti-toggle-left f-20"></i> <!-- Toggle icon for 'Active' -->
                                        </a>
                                        <!-- <a href="#" class="avtar avtar-xs btn-link-secondary"><i class="ti ti-trash f-20"></i></a> -->
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')

    @push('scripts')
    @include('partials.datatable-export-js')
    @endpush

    @include('partials.theme')



    @endsection