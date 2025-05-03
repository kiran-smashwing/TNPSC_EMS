@extends('layouts.app')

@section('title', 'Venues')

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
                <!-- [ basic-table ] start -->
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
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-sm-flex align-items-center justify-content-between">
                                <h5 class="mb-3 mb-sm-0">Venues list</h5>
                                <div>
                                    <a href="{{ route('venues.create') }}" class="btn btn-outline-success">Add Venues</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body table-border-style">
                            <form id="filterForm" class="mb-3">
                                @hasPermission('district-filter')
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
                                @endhasPermission
                                <div class="filter-item">
                                    <select class="form-select" id="centerFilter" name="center">
                                        <option value="">Select Center name</option>
                                        @foreach ($centers as $center)
                                            <option value="{{ $center->center_code }}"
                                                {{ request('center') == $center->center_code ? 'selected' : '' }}>
                                                {{ $center->center_name }}
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

                            <table id="venuesTable" class="display table table-striped table-hover dt-responsive nowrap"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>District</th>
                                        <th>Center</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Email Status</th>
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


    @include('partials.footer')

    @push('scripts')
        @include('partials.datatable-export-js')
        <script src="{{ asset('storage/assets/js/plugins/sweetalert2.all.min.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const tableContainer = document.querySelector('body'); // Parent element of all toggles

                tableContainer.addEventListener('click', function(e) {
                    const button = e.target.closest(
                        '.status-toggle'); // Check if the clicked element is the toggle button

                    if (button) {
                        e.preventDefault(); // Prevent default behavior

                        // Disable the button during processing
                        button.classList.add('disabled');

                        // Get the venue ID
                        const venueId = button.dataset.venueId;

                        fetch(`{{ url('/') }}/venues/${venueId}/toggle-status`, {
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
                                    button.classList.toggle('btn-light-success');
                                    button.classList.toggle('btn-light-danger');

                                    // Toggle icon
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
                                        data.message || 'Venue status updated successfully',
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
                    }
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
            // console.log("All Centers:", allCenters);
            // console.log(@json($districts));
            // District filter change event
            $('#districtFilter').on('change', function() {
                const selectedDistrictCode = $(this).val();
                // alert(selectedDistrictCode);
                const centerDropdown = $('#centerFilter'); // Corrected to #centerFilter
                // console.log("Selected District Code:", selectedDistrictCode, "Type:", typeof selectedDistrictCode);

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
            $(document).ready(function() {
                let table = $('#venuesTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{{ route('venues.json') }}',
                        data: function(d) {
                            d.district = $('#districtFilter').val();
                            d.center = $('#centerFilter').val();
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
                            data: 'venue_image',
                            name: 'venue_image',
                            render: function(data, type, row) {
                                if (data) {
                                    return `<img src="{{ asset('storage/') }}/${data}" alt="image" class="img-radius wid-40"/>`;
                                }
                                return `<img src="{{ asset('storage/assets/images/user/venue.png') }}" alt="default image" class="img-radius wid-40"/>`;
                            }
                        },
                        {
                            data: 'venue_name',
                            name: 'venue_name'
                        },
                        {
                            data: 'district.district_name',
                            name: 'district.district_name',
                            defaultContent: 'N/A'
                        },
                        {
                            data: 'center.center_name',
                            name: 'center.center_name',
                            defaultContent: 'N/A'
                        },
                        {
                            data: 'venue_email',
                            name: 'venue_email'
                        },
                        {
                            data: 'venue_phone',
                            name: 'venue_phone'
                        },
                        {
                            data: 'venue_email_status',
                            name: 'venue_email_status',
                            render: function(data) {
                                return data ?
                                    '<i class="ti ti-circle-check text-success f-18"></i>' :
                                    '<i class="ti ti-alert-circle text-danger f-18"></i>';
                            }
                        },
                        {
                            data: null, // Access multiple fields
                            render: function(data) {
                                return `
        <a href="{{ route('venues.show', ':id') }}" class="avtar avtar-xs btn-light-success">
            <i class="ti ti-eye f-20"></i>
        </a>
        <a href="{{ route('venues.edit', ':id') }}" class="avtar avtar-xs btn-light-success">
            <i class="ti ti-edit f-20"></i>
        </a>
        <a href="#"
            class="avtar avtar-xs status-toggle ${data.venue_status ? 'btn-light-success' : 'btn-light-danger'}"
            data-venue-id="${data.venue_id}"
            title="Change Status (Active or Inactive)">
            <i class="ti ti-toggle-${data.venue_status ? 'right' : 'left'} f-20"></i>
        </a>
            <a href="#"
               class="avtar avtar-xs btn-light-danger delete-venue"
               data-venue-id="${data.venue_id}">
               <i class="ti ti-trash f-20"></i>
            </a>

        `.replace(/:id/g, data.venue_id);
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
                    }
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
                    $('#searchBox').val('');
                    table.search('').draw();
                });
            });
        </script>
        <script>
            $(document).on('click', '.delete-venue', function(e) {
                e.preventDefault();
                const venueId = $(this).data('venue-id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This action cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e3342f',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Use a form submission approach to handle the DELETE request properly
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/venues/${venueId}`;
                        form.style.display = 'none';

                        // Add CSRF token
                        const csrfField = document.createElement('input');
                        csrfField.type = 'hidden';
                        csrfField.name = '_token';
                        csrfField.value = '{{ csrf_token() }}';
                        form.appendChild(csrfField);

                        // Add method spoofing field for DELETE
                        const methodField = document.createElement('input');
                        methodField.type = 'hidden';
                        methodField.name = '_method';
                        methodField.value = 'DELETE';
                        form.appendChild(methodField);

                        // Add to document and submit
                        document.body.appendChild(form);

                        const deleteUrl = "{{ route('venues.destroy', ':id') }}".replace(':id', venueId);

                        fetch(deleteUrl, {
                                method: 'post',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json'
                                },
                                credentials: 'same-origin'
                            })
                            .then(response => {
                                // First convert to JSON regardless of status
                                return response.json().then(data => {
                                    // If response is not OK, throw error with message from server
                                    if (!response.ok) {
                                        throw new Error(data.message ||
                                            'Error occurred while deleting');
                                    }
                                    return data;
                                });
                            })
                            .then(data => {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: data.message || 'Venue deleted successfully.',
                                    icon: 'success',
                                    confirmButtonColor: '#3085d6'
                                }).then(() => {
                                    // Make sure to refresh the table after the alert is closed
                                    $('#venuesTable').DataTable().ajax.reload();
                                });
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire({
                                    title: 'Error!',
                                    text: error.message ||
                                        'Failed to delete venue. Please try again.',
                                    icon: 'error',
                                    confirmButtonColor: '#3085d6'
                                });
                            });
                    }
                });
            });
        </script>
    @endpush

    @include('partials.theme')



@endsection
