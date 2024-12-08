@extends('layouts.app')

@section('title', 'ID Confirm Venues')

@section('content')
    @push('styles')
        <!-- data tables css -->
        <link rel="stylesheet" href="{{ asset('storage/assets/css/plugins/dataTables.bootstrap5.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('storage/assets/css/plugins/rowReorder.bootstrap5.min.css') }}" />
        <style>
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

            .switch-lg {
                font-size: 2em;
            }

            .form-check-label {
                font-size: 1.2rem !important;
                /* Keep the label text size normal */
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
                                <h5 class="mb-3 mb-sm-0">Review Confirmed Venues</h5>

                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Filter options -->
                            <form id="filterForm" class="mb-3" method="GET">
                                @csrf
                                <input type="hidden" name="exam_id" value="{{ $exam->exam_main_no }}">

                                <div class="filter-item">
                                    <select class="form-select" id="districtFilter" name="district">
                                        <option value="">Select District</option>
                                        @foreach ($districts as $district)
                                            <option value="{{ $district->district_code }}"
                                                {{ $selectedDistrict == $district->district_code ? 'selected' : '' }}>
                                                {{ $district->district_code }} - {{ $district->district_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="filter-item">
                                    <select class="form-select" id="centerCodeFilter" name="center_code">
                                        <option value="">Select Center Code</option>
                                        <!-- Centers will be dynamically populated -->
                                    </select>
                                </div>
                                <div class="filter-item">
                                    <div class="form-check form-switch custom-switch-v2 mb-1 mt-2 switch-lg">
                                        <input name="confirmed_only" type="checkbox"
                                            class="form-check-input input-light-success" id="customswitchlightv1-3"
                                            {{ $confirmedOnly == 'on' ? 'checked' : '' }}>
                                        <label class="form-check-label " for="customswitchlightv1-3"> Confirmed Only</label>
                                    </div>
                                </div>
                                <div class="btn-container">
                                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                                </div>

                            </form>

                            <div class="dt-responsive table-responsive">
                                <table id="reorder-events" class="table table-striped table-bordered nowrap">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>#</th>
                                            <th>VENUE NAME</th>
                                            <th>VENUE CODE</th>
                                            <th>E- MAIL</th>
                                            <th>PHONE</th>
                                            <th>HALL COUNT</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($confirmedVenues as $key => $confirmedVenue)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>
                                                    <input class="form-check-input input-success venue-checkbox"
                                                        type="checkbox" name="venue_checkbox[]"
                                                        value="{{ $confirmedVenue->id }}">
                                                </td>
                                                <td>{{ $confirmedVenue->venues->venue_name }}</td>
                                                <td>{{ $confirmedVenue->venues->venue_code }}</td>
                                                <td>{{ $confirmedVenue->venues->venue_email }}</td>
                                                <td>{{ $confirmedVenue->venues->venue_phone }}</td>
                                                <td>{{ $confirmedVenue->expected_candidates_count / $exam->exam_main_candidates_for_hall }}
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>S.NO</th>
                                            <th>#</th>
                                            <th>VENUE NAME</th>
                                            <th>VENUE CODE</th>
                                            <th>E - MAIL</th>
                                            <th>PHONE</th>
                                            <th>HALL COUNT</th>
                                        </tr>
                                    </tfoot>
                                </table>
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <form id="venueConfirmationForm"
                                            action="{{ route('id-candidates.save-venue-confirmation', $exam->exam_main_no) }}"
                                            method="POST">
                                            @csrf
                                            <input type="hidden" name="exam_id" value="{{ $exam->exam_main_no }}">
                                            <input type="hidden" id="selectedVenuesOrder" name="selected_venues"
                                                value="">
                                            <button type="submit" class="btn btn-success save-venue-confirmation" disabled>
                                                Save Venue Confirmation
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
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
        <!-- datatable Js -->
        <script src="{{ asset('storage/assets/js/plugins/dataTables.min.js') }}"></script>
        <script src="{{ asset('storage/assets/js/plugins/dataTables.bootstrap5.min.js') }}"></script>
        <script src="{{ asset('storage/assets/js/plugins/dataTables.rowReorder.min.js') }}"></script>
        <script>
            // [ Reorder Events ]
            var rowevents = $('#reorder-events').DataTable({
                rowReorder: true,
                rowReorder: {
                    selector: 'td:not(:first-child):not(:nth-child(2))' // Allow checkbox in second column to be clicked
                },
                columnDefs: [{
                    targets: 0, // Checkbox column
                    orderable: true, // Disable ordering for this column
                    className: 'reorder-disabled', // Prevent drag behavior
                    searchable: false, // Disable search for this column
                    visible: true, // Make the column visible
                }],
            });

            rowevents.on('row-reorder', function(e, diff, edit) {
                var result = 'Reorder started on row: ' + edit.triggerRow.data()[1] + '<br>';

                for (var i = 0, ien = diff.length; i < ien; i++) {
                    var rowData = rowevents.row(diff[i].node).data();

                    result += rowData[1] + ' updated to be in position ' + diff[i].newData + ' (was ' + diff[i]
                        .oldData + ')<br>';
                }

                $('#result').html('Event result:<br>' + result);
            });
        </script>
        <script>
            // Full list of centers
            const allCenters = @json($centers);

            // District dropdown change event
            $('#districtFilter').on('change', function() {
                const selectedDistrictCode = $(this).val();
                const centerDropdown = $('#centerCodeFilter');

                // Clear previous options
                centerDropdown.empty();
                centerDropdown.append('<option value="">Select Center Name</option>');

                // Filter centers based on selected district
                const filteredCenters = allCenters.filter(center =>
                    center.district_code == selectedDistrictCode
                );

                // Populate centers
                filteredCenters.forEach(center => {
                    const selected = "{{ $selectedCenter }}" == center.center_code ? 'selected' : '';
                    centerDropdown.append(
                        `<option value="${center.center_code}" ${selected}>
                          ${center.center_code} -  ${center.center_name}
                        </option>`
                    );
                });
            });

            // Trigger change event on page load to handle old/existing selections
            $(document).ready(function() {
                const oldDistrict = "{{ $selectedDistrict }}";
                if (oldDistrict) {
                    $('#districtFilter').val(oldDistrict).trigger('change');
                }
            });
        </script>
        <script>
            $(document).ready(function() {
                const saveButton = $('.save-venue-confirmation');
                const venueCheckboxes = $('.venue-checkbox');
                const selectedVenuesOrderInput = $('#selectedVenuesOrder');

                // Enable/disable save button based on checkbox selection
                venueCheckboxes.on('change', function() {
                    const checkedBoxes = venueCheckboxes.filter(':checked');
                    saveButton.prop('disabled', checkedBoxes.length === 0);

                    // Collect selected venue IDs in order
                    if (checkedBoxes.length > 0) {
                        const selectedVenueIds = checkedBoxes.map(function() {
                            return $(this).val();
                        }).get();

                        selectedVenuesOrderInput.val(JSON.stringify(selectedVenueIds));
                    } else {
                        selectedVenuesOrderInput.val('');
                    }
                });

                // Form submission handling
                $('#venueConfirmationForm').on('submit', function(e) {
                    const checkedBoxes = venueCheckboxes.filter(':checked');

                    if (checkedBoxes.length === 0) {
                        e.preventDefault();
                        alert('Please select at least one venue.');
                    }
                });
            });
        </script>
    @endpush

    @include('partials.theme')



@endsection
