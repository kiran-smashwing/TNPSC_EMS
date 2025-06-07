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
                justify-content: space-between;
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

            .table-responsive {
                overflow-y: auto;
                overflow-x: auto;
                max-width: 100%;
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

                <div class="row">
                    <!-- [ basic-table ] start -->
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-sm-flex align-items-center justify-content-between">
                                    <h5 class="mb-3 mb-sm-0">Review Confirmed Venues</h5>
                                    <div>
                                        <!-- District Excel Button -->
                                        <a href="#" class="me-2 btn btn-sm btn-light-primary"
                                            onclick="validateFilters('district')">
                                            <i class="feather icon-download mx-1"></i>District Excel
                                        </a>

                                        <!-- Center Excel Button -->
                                        <a href="#" class="me-2 btn btn-sm btn-light-primary"
                                            onclick="validateFilters('center')">
                                            <i class="feather icon-download mx-1"></i>Center Excel
                                        </a>
                                    </div>
                                    <div>
                                        <h5 class="mb-3 mb-sm-0">Required : {{ $confirmedVenuesCapacity }} /
                                            {{ $accommodation_required ?? 0 }}</h5>
                                            
                                      <h5 class="mb-3 mb-sm-0" id="totalCandidatesCount">Selected Count: 0</h5>
                                    </div>
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
                                    <div class="filter-item" style="max-width: 130px;">
                                        <select class="form-select" id="examDateFilter" name="exam_date"
                                            class="form-select">
                                            @foreach ($examDates as $examDate)
                                                <option value="{{ $examDate }}"
                                                    {{ request('exam_date') == $examDate ? 'selected' : '' }}>
                                                    {{ Carbon\Carbon::parse($examDate)->format('d-m-Y') }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="filter-item">
                                        <div class="form-check form-switch custom-switch-v2 mb-1 mt-2 switch-lg">
                                            <input name="confirmed_only" type="checkbox"
                                                class="form-check-input input-light-success" id="customswitchlightv1-3"
                                                {{ $confirmedOnly == 'on' ? 'checked' : '' }}>
                                            <label class="form-check-label " for="customswitchlightv1-3"> Confirmed
                                                Only</label>
                                        </div>
                                    </div>
                                    <div class="btn-container">
                                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                                        <button type="button" id="resetButton"
                                            class="btn btn-secondary d-flex align-items-center"
                                            onclick="window.location.href='{{ route('id-candidates.show-venue-confirmation-form', $exam->exam_main_no) }}'">
                                            <i class="ti ti-refresh me-2"></i> Reset
                                        </button>
                                    </div>
                                </form>

                                <div class="dt-responsive table-responsive">
                                    <table id="reorder-events" class="table table-striped table-bordered nowrap">
                                        <thead>
                                            <tr>
                                                <th>S.No</th>
                                                <th>#</th>
                                                <th>Actions</th>
                                                <th>VENUE NAME</th>
                                                <th>VENUE CODE</th>
                                                <th>E-MAIL</th>
                                                <th>PHONE</th>
                                                <th>ADDRESS</th>
                                                <th>EXAM DATE</th>
                                                <th>CI NAME</th>
                                                <th>CI EMAIL</th>
                                                <th>CI PHONE</th>
                                                <th>CANDIDATES COUNT</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($venuesWithCIs as $key => $item)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>
                                                        <input class="form-check-input input-success venue-checkbox"
                                                            type="checkbox" name="venue_checkbox[]"
                                                            {{ $item['ci']->is_confirmed ? 'checked' : '' }}
                                                            data-ci-id="{{ $item['ci']['ci_id'] ?? '' }}"
                                                            data-exam-date="{{ $item['ci']['exam_date'] ?? '' }}"
                                                            value="{{ $item['venue']->venue_id }}">
                                                    </td>
                                                    <td><a href="{{ route('id-candidates.edit-venue-consent', ['examId' => $exam->exam_main_no, 'venueId' => $item['venue']->venue_id]) }}"
                                                        target="_blank"  class="me-3 btn btn-sm btn-light-warning">
                                                         <i class="feather icon-edit mx-1"></i>
                                                      </a>                                                     
                                                     </td>
                                                    <td>{{ $item['venue']->venues->venue_name ?? 'N/A' }}</td>
                                                    <td>{{ $item['venue']->venues->venue_code ?? 'N/A' }}</td>
                                                    <td>{{ $item['venue']->venues->venue_email ?? 'N/A' }}</td>
                                                    <td>{{ $item['venue']->venues->venue_phone ?? 'N/A' }}</td>
                                                    <td>{{ $item['venue']->venues->venue_address ?? 'N/A' }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($item['ci']->exam_date)->format('d-m-Y') ?? 'No Date' }}
                                                    </td>
                                                    <td>{{ $item['ci']->chiefInvigilator->ci_name ?? 'No CI Assigned' }}
                                                    </td>
                                                    <td>{{ $item['ci']->chiefInvigilator->ci_email ?? 'N/A' }}</td>
                                                    <td>{{ $item['ci']->chiefInvigilator->ci_phone ?? 'N/A' }}</td>
                                                    <td contenteditable="true" class="editable-candidates">
                                                        {{ $item['candidates_count'] ?? 0 }}</td>
                                                   
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>S.No</th>
                                                <th>#</th>
                                                <th>Actions</th>
                                                <th>VENUE NAME</th>
                                                <th>VENUE CODE</th>
                                                <th>E-MAIL</th>
                                                <th>PHONE</th>
                                                <th>ADDRESS</th>
                                                <th>EXAM DATE</th>
                                                <th>CI NAME</th>
                                                <th>CI EMAIL</th>
                                                <th>CI PHONE</th>
                                                <th>CANDIDATES COUNT</th>
                                            </tr>
                                        </tfoot>
                                    </table>

                                </div>
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <form id="venueConfirmationForm"
                                            action="{{ route('id-candidates.save-venue-confirmation', $exam->exam_main_no) }}"
                                            method="POST">
                                            @csrf
                                            <input type="hidden" name="exam_id" value="{{ $exam->exam_main_no }}">
                                            <input type="hidden" id="selectedVenuesOrder" name="selected_venues"
                                                value="">
                                            <input type="hidden" name="center_code" value="{{ $selectedCenter }}">
                                            <input type="hidden" id="examDateInput" name="exam_date"
                                                value="{{ request('exam_date') }}">
                                            <!-- Add Confirm All Dates Checkbox -->
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div
                                                    class="form-check form-switch custom-switch-v2 d-flex align-items-center mb-0">
                                                    <!-- Checkbox -->
                                                    <input name="confirm_all_dates" type="checkbox"
                                                        class="form-check-input input-light-success" id="confirmAllDates"
                                                        checked>
                                                    <!-- Label -->
                                                    <label class="form-label ms-2 mb-0" style="margin-top: 2px"
                                                        for="confirmAllDates">Confirm All Dates</label>
                                                </div>
                                                <!-- Save Confirmation Button -->
                                                <button type="submit" class="btn btn-success save-venue-confirmation">
                                                    Save Confirmation
                                                </button>
                                            </div>


                                        </form>
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
        <script src="{{ asset('storage//assets/js/plugins/sweetalert2.all.min.js') }}"></script>

        <script>
            // [ Reorder Events ]
            var rowevents = $('#reorder-events').DataTable({
                rowReorder: true,
                rowReorder: {
                    selector: 'td:not(:first-child):not(:nth-child(2)):not(:nth-child(3))' // Allow checkbox in second column to be clicked
                },
                columnDefs: [{
                        targets: 0, // Checkbox column
                        orderable: true, // Disable ordering for this column
                        className: 'reorder-disabled', // Prevent drag behavior
                        searchable: false, // Disable search for this column
                        visible: true, // Make the column visible
                    },
                    {
                        targets: 1, // Candidates Count column (0-based index)
                        orderable: false, // Disable ordering for this column
                        searchable: false, // Disable search for this column
                        className: 'editable-cell', // Add a class for styling/editing
                    }
                ],
                paging: false, // Disable pagination
                language: {
                    emptyTable: "Please select a district and center, or no venues have been confirmed yet.", // Custom message
                }
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
            // Form submission handling
            $('#venueConfirmationForm').on('submit', function(e) {
                const venueList = [];
                const checkedBoxes = $('.venue-checkbox');

                checkedBoxes.each(function(index) {
                    const venueId = $(this).val();
                    const isChecked = $(this).is(':checked');
                    const ciId = $(this).data('ci-id');
                    const exam_date = $(this).data('exam-date');
                    const candidatesCount = $(this).closest('tr').find('td:last-child').text();
                    venueList.push({
                        venue_id: venueId,
                        order: index,
                        checked: isChecked,
                        ci_id: ciId,
                        exam_date: exam_date,
                        candidates_count: candidatesCount
                    });
                });

                $('#selectedVenuesOrder').val(JSON.stringify(venueList));
            });
        </script>
        <script>
            $('#venueConfirmationForm').ajaxForm({
                beforeSubmit: function() {
                    // Show the loader before the request starts
                    const loader = document.getElementById('loader');
                    if (loader) {
                        loader.style.removeProperty('display');
                    }
                },
                success: function(data) {
                    const loader = document.getElementById('loader');
                    if (loader) {
                        loader.style.display = 'none';
                    }
                },
                error: function(xhr, status, error) {
                    const loader = document.getElementById('loader');
                    if (loader) {
                        loader.style.display = 'none';
                    }
                }
            });
        </script>
        <script>
            function validateFilters(type) {
                // Get the selected district and center
                const selectedDistrict = document.getElementById('districtFilter').value;
                const selectedCenter = document.getElementById('centerCodeFilter').value;

                // Check if both district and center are selected
                if (!selectedDistrict || !selectedCenter) {
                    Swal.fire({
                        title: 'Filters Not Applied!',
                        text: 'Please select both a district and a center, then apply filters to download the Excel file.',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                // Construct the URL based on the type (district or center)
                const examId = "{{ $exam->exam_main_no }}";
                let url = '';
                if (type === 'district') {
                    url =
                        `{{ route('id-candidates.export-confirmed-halls', ['examId' => $exam->exam_main_no]) }}?district_code=${selectedDistrict}`;
                } else if (type === 'center') {
                    url =
                        `{{ route('id-candidates.export-confirmed-halls', ['examId' => $exam->exam_main_no]) }}?center_code=${selectedCenter}`;
                }

                // Redirect to the constructed URL
                window.location.href = url;
            }
        </script>
        <script>
            // Function to calculate the total candidates count for checked rows
            function updateTotalCandidatesCount() {
                let totalCandidates = 0;
        
                // Loop through all checked checkboxes
                $('.venue-checkbox:checked').each(function () {
                    const candidatesCount = parseInt($(this).closest('tr').find('.editable-candidates').text()) || 0;
                    totalCandidates += candidatesCount;
                });
        
                // Update the total candidates count in the UI
                $('#totalCandidatesCount').text(`Selected Count : ${totalCandidates}`);
            }
        
            // Attach an event listener to checkboxes to trigger the update
            $(document).on('change', '.venue-checkbox', function () {
                updateTotalCandidatesCount();
            });
        
            // Initialize the total candidates count on page load
            $(document).ready(function () {
                updateTotalCandidatesCount();
            });
        </script>
    @endpush

    @include('partials.theme')



@endsection
