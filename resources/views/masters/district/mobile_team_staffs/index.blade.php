@extends('layouts.app')

@section('title', 'Mobile Team Staffs')

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
                                <h5 class="mb-3 mb-sm-0">Mobile Team Staffs list</h5>
                                <div>
                                    <a href="{{ route('mobile-team-staffs.create') }}" class="btn btn-outline-success">Add
                                        Mobile
                                        Team Staffs</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body table-border-style">
                            <!-- Filter options -->
                            @hasPermission('mobile-team-staffs-filter')
                                <form id="filterForm" method="GET" action="{{ route('mobile-team-staffs.index') }}"
                                    class="mb-3">
                                    <div class="filter-item">
                                        <select class="form-select" id="districtFilter" name="district">
                                            <option value="">Select District Name</option>
                                            @foreach ($districts as $district)
                                                <option value="{{ $district->district_code }}"
                                                    {{ request('district') == $district->district_code ? 'selected' : '' }}>
                                                    {{ $district->district_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="btn-container">
                                        <button type="submit" class="btn btn-primary">Apply Filters</button>

                                    </div>
                                    <a href="{{ url()->current() }}" class="btn btn-secondary"><i
                                            class="ti ti-refresh me-2"></i>Reset</a>
                                </form>
                            @endhasPermission

                            <table id="res-config" class="display table table-striped table-hover dt-responsive nowrap"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>District</th>
                                        <th>E-mail</th>
                                        <th>Phone</th>
                                        <th>E-mail Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($mobileTeams as $key => $team)
                                        <tr>
                                            <td>{{ $key + 1 }}</td> <!-- Displaying index -->
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        @if ($team->mobile_image)
                                                            <img src="{{ asset('storage/' . $team->mobile_image) }}"
                                                                alt="mombile team image" class="img-radius wid-40">
                                                        @else
                                                            <img src="{{ asset('storage/assets/images/user/avatar-4.jpg') }}"
                                                                alt="default image" class="img-radius wid-40">
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="mb-0">{{ $team->mobile_name }}</h6>
                                                </div>
                                            </td>
                                            <td>{{ $team->district->district_name }}</td>
                                            <!-- Displaying district name -->
                                            <td>{{ $team->mobile_email }}</td>
                                            <td>{{ $team->mobile_phone }}</td>
                                            <td class="text-center">
                                                @if ($team->mobile_email_status)
                                                    <i class="ti ti-circle-check text-success f-18"></i>
                                                @else
                                                    <i class="ti ti-alert-circle text-danger f-18"></i>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('mobile-team-staffs.show', ['id' => encrypt($team->mobile_id)]) }}"
                                                    class="avtar avtar-xs btn-light-success">
                                                    <i class="ti ti-eye f-20"></i>
                                                </a>

                                                <a href="{{ route('mobile-team-staffs.edit', encrypt($team->mobile_id)) }}"
                                                    class="avtar avtar-xs btn-light-success">
                                                    <i class="ti ti-edit f-20"></i>
                                                </a>

                                                <a href="#"
                                                    class="avtar avtar-xs status-toggle {{ $team->mobile_status ? 'btn-light-success' : 'btn-light-danger' }}"
                                                    data-mobile-id="{{ encrypt($team->mobile_id) }}"
                                                    title="Change Status (Active or Inactive)">
                                                    <i
                                                        class="ti ti-toggle-{{ $team->mobile_status ? 'right' : 'left' }} f-20"></i>
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
        @include('partials.datatable-export-js')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Use event delegation to handle dynamically rendered buttons
                document.body.addEventListener('click', function(e) {
                    // Find the closest toggle button if the clicked element is within it
                    const button = e.target.closest('.status-toggle');
                    if (!button) return; // Exit if the click was not on a toggle button

                    e.preventDefault(); // Prevent default behavior

                    // Disable the button during processing
                    button.classList.add('disabled');

                    // Get the mobile team staff ID from the data attribute
                    const mobileId = button.dataset.mobileId;

                    // Send the request to toggle the status
                    fetch(`{{ url('/') }}/mobile-team-staffs/${mobileId}/toggle-status`, {
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
                                    data.message || 'Mobile team staff status updated successfully',
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
    @endpush

    @include('partials.theme')



@endsection
