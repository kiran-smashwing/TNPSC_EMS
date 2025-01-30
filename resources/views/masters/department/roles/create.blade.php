@extends('layouts.app')
@section('title', ' Add Role')
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Style the Select2 container */
        .select2-container .select2-selection--single {
            height: 45px !important;
            /* Adjust height */
            border: 1px solid #bec8d0 !important;
            /* Blue border */
            border-radius: 8px !important;
            padding: 10px !important;
            font-size: 16px;
            background-color: #fff !important;
            display: flex !important;
            align-items: center !important;
            transition: all 0.3s ease-in-out;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: var(--bs-body-color) !important;
        }

        [data-pc-theme=dark] .select2-dropdown {
            background-color: #263240 !important;
            border-color: #303f50 !important;
        }

        [data-pc-theme=dark].select2-container--default .select2-results__option[aria-selected=true] {
            background-color: #263240 !important;
            border-color: #303f50 !important;
        }

        [data-pc-theme=dark] .select2-container .select2-selection--single {
            background-color: #263240 !important;
            border-color: #303f50 !important;
        }

        /* Focus state */
        .select2-container--default .select2-selection--single:focus,
        .select2-container--default .select2-selection--single:hover {
            border-color: #21b789 !important;
            /* Green border on hover/focus */
            box-shadow: 0 0 5px rgba(33, 183, 137, 0.5) !important;
        }

        /* Style the placeholder text */
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            /* Dark text */
            font-size: 16px !important;
            padding-left: 10px !important;
        }

        /* Dropdown arrow icon */
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 50% !important;
            transform: translateY(-50%) !important;
            right: 10px !important;
        }

        /* Style the dropdown menu */
        .select2-dropdown {
            border: 1px solid #bec8d0 !important;
            /* Border color */
            border-radius: 8px !important;
        }

        /* Style the options inside dropdown */
        .select2-container--default .select2-results__option {
            padding: 10px !important;
            font-size: 16px !important;
            transition: background 0.3s;
        }

        /* Hover effect on dropdown options */
        .select2-container--default .select2-results__option:hover {
            background-color: #21b789 !important;
            color: white !important;
        }

        /* Remove X (clear button) */
        .select2-selection__clear {
            display: none !important;
        }
    </style>
@endpush
@section('content')

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

    <div class="pc-container">
        <div class="pc-content">
            <!-- [ Main Content ] start -->
            <div class="row">

                <div class="tab-content">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Role - <span class="text-primary">Add</span></h5>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('role.store') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">

                                            <div class="col-sm-12">
                                                <div class="mb-3">
                                                    <label class="form-label" for="role_department">Department<span
                                                            class="text-danger">*</span></label>
                                                    <select
                                                        class="form-control @error('role_department') is-invalid @enderror"
                                                        id="role_department" name="role_department" required>
                                                        <option selected disabled>Select Department</option>
                                                        <option value="APD">Application Processing Department - APD
                                                        </option>
                                                        <option value="ID">Infrastructure Department - ID</option>
                                                        <option value="RND">Rules Notification Department - RND</option>
                                                        <option value="ED">Evaluation Department - ED</option>
                                                        <option value="QD">Confidential Department - QD</option>
                                                        <option value="VMD">Vehicles Machineries Department - VMD
                                                        </option>
                                                        <option value="VSD">Verification Stationary Department - VSD
                                                        </option>
                                                        <option value="MCD">Monitoring Coordination Department - MCD
                                                        </option>
                                                    </select>
                                                    @error('role_department')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="mb-3">
                                                    <label class="form-label" for="role_name">Role<span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-control @error('role_name') is-invalid @enderror"
                                                        id="role_name" name="role_name" required>
                                                        <option selected disabled>Select Role</option>
                                                        <option value="SO">Section Officer</option>
                                                        <option value="US">Under Seceratory</option>
                                                        <option value="JS">Joint Seceratory</option>
                                                        <option value="DS">Deputy Seceratory</option>
                                                        <option value="SEC">Seceratory</option>
                                                        <option value="COE">Controller of Examination</option>
                                                    </select>
                                                    @error('role_name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="mb-3">
                                                    <label class="form-label" for="department_officer">Department officer
                                                        <span class="text-danger">*</span></label>
                                                    <select class="form-control select2" id="department_officer"
                                                        name="department_officer" required>
                                                        <option disabled selected>Select Department Officer</option>
                                                        @foreach ($departmentOfficials as $departmentOfficial)
                                                            <option value="{{ $departmentOfficial->dept_off_id }}">
                                                                {{ $departmentOfficial->dept_off_name }} -
                                                                {{ $departmentOfficial->dept_off_email }}
                                                            </option>
                                                        @endforeach
                                                    </select>


                                                    @error('department_officer')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-12 text-end btn-page">
                                                <a href="{{ route('role') }}" class="btn btn-outline-secondary">Cancel</a>
                                                <button type="submit" class="btn btn-primary">Create</button>

                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- [ Main Content ] end -->
                    </div>

                    <!-- [ Main Content ] end -->
                    @include('partials.footer')
                    @push('scripts')
                        <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
                        <script>
                            $(document).ready(function() {
                                $('#department_officer').select2({
                                    placeholder: "Select Department Officer",
                                    allowClear: true,
                                    width: '100%'
                                });
                            });
                        </script>
                    @endpush
                    @include('partials.theme')

                @endsection
