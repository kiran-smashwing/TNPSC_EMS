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
                                <h5 class="mb-3 mb-sm-0">Invigilator list</h5>
                                <div>
                                    <a href="{{ route('invigilator.create') }}" class="btn btn-outline-success">Add
                                        Invigilator</a>
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


                            <table id="res-config" class="display table table-striped table-hover dt-responsive nowrap"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Venue Name</th>
                                        <th>E-mail</th>
                                        <th>Phone</th>
                                        <th>Email-Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invigilators as $invigilator)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <img src="{{ asset('storage/' . $invigilator->invigilator_image) }}"
                                                            alt="user image" class="img-radius wid-40">
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $invigilator->invigilator_name }}</td>
                                            <td>{{ $invigilator->invigilator_designation }}</td>
                                            <td>{{ $invigilator->invigilator_email }}</td>
                                            <td>{{ $invigilator->invigilator_phone }}</td>
                                            <td class="text-center">
                                                @if ($invigilator->invigilator_email_status)
                                                    <i class="ti ti-circle-check text-success f-18"></i>
                                                @else
                                                    <i class="ti ti-alert-circle text-danger f-18"></i>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('invigilator.show', ['id' => $invigilator->invigilator_id]) }}"
                                                    class="avtar avtar-xs btn-light-success">
                                                    <i class="ti ti-eye f-20"></i>
                                                </a>
                                                <a href="{{ route('invigilator.edit', ['id' => $invigilator->invigilator_id]) }}"
                                                    class="avtar avtar-xs btn-light-success">
                                                    <i class="ti ti-edit f-20"></i>
                                                </a>
                                                <a href="#"
                                                    class="avtar avtar-xs status-toggle {{ $invigilator->invigilator_status ? 'btn-light-success' : 'btn-light-danger' }}"
                                                    data-invigilator-id="{{ $invigilator->invigilator_id }}"
                                                    title="Change Status (Active or Inactive)">
                                                    <i
                                                        class="ti ti-toggle-{{ $invigilator->invigilator_status ? 'right' : 'left' }} f-20"></i>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleButtons = document.querySelectorAll('.status-toggle');
    
            toggleButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
    
                    // Disable the button during processing
                    this.classList.add('disabled');
    
                    // Get the invigilator ID from the data attribute
                    const invigilatorId = this.dataset.invigilatorId;
    
                    // Send the request to toggle the invigilator status
                    fetch(`{{ url('/') }}/invigilator/${invigilatorId}/toggle-status`, {
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
                                // Toggle button classes
                                this.classList.toggle('btn-light-success');
                                this.classList.toggle('btn-light-danger');
    
                                // Toggle icon
                                const icon = this.querySelector('i');
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
                                    data.message || 'Invigilator status updated successfully',
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
                            this.classList.remove('disabled');
                        });
                });
            });
        });
    </script>
    
    @include('partials.footer')

    @push('scripts')
        @include('partials.datatable-export-js')
    @endpush

    @include('partials.theme')



@endsection
