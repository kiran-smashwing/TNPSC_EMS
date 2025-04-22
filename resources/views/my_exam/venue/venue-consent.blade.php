@extends('layouts.app')

@section('title', 'Venue Consent')
@push('styles')
    <link rel="stylesheet" href="{{ asset('storage/assets/css/plugins/datepicker-bs5.min.css') }}" />
    <style>
        @media screen and (max-width: 600px) {
            #responsiveTable thead {
                display: none;
            }

            #responsiveTable,
            #responsiveTable tbody,
            #responsiveTable tr,
            #responsiveTable td {
                display: block;
                width: 100%;
            }

            #responsiveTable tr {
                margin-bottom: 15px;
            }

            #responsiveTable td {
                text-align: right;
                /* padding-left: 50%; */
                position: relative;
            }

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
    <!-- [ Header Topbar ] end -->

    <div class="pc-container">
        <div class="pc-content">
            <!-- Modal start-->
            @include('modals.cropper')
            <!-- Modal start-->
            <!-- [ Main Content ] start -->
            <div class="row">
                <div class="tab-content">
                    <div class="row">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Exam Details</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">

                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="exam_main_name">Exam Name</label>
                                                <input type="text" class="form-control" id="exam_main_name"
                                                    name="exam_main_name" disabled value="{{ $exam->exam_main_name }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="exam_main_nametamil">Exam Name in
                                                    Tamil</label>
                                                <input type="text" class="form-control" id="exam_main_nametamil"
                                                    name="exam_main_nametamil" disabled
                                                    value="{{ $exam->exam_main_nametamil }}">
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="mb-3">
                                                <label class="form-label" for="exam_main_model">Exam Model</label>
                                                <input type="text" class="form-control" id="exam_main_model"
                                                    name="exam_main_model" disabled value="{{ $exam->exam_main_model }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="mb-3">
                                                <label class="form-label" for="exam_main_type">Type of Exam</label>
                                                <input type="text" class="form-control" id="exam_main_type"
                                                    name="exam_main_type" disabled value="{{ $exam->exam_main_type }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="exam_main_service">Exam Service</label>
                                                <input type="text" class="form-control" id="exam_main_service"
                                                    name="exam_main_service" disabled
                                                    value="{{ $exam->examservice->examservice_name }}">
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="table-responsive" style="overflow-x: visible;">
                                            <table class="table table-bordered" id="subjectsTable">
                                                <thead>
                                                    <tr>
                                                        <th>Exam Date</th>
                                                        <th>Session</th>
                                                        <th>Time</th>
                                                        <th>Duration</th>
                                                        <th>Subject</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($exam->examsession as $index => $exam_session)
                                                        <tr>
                                                            <td data-label="Exam Date">
                                                                <input type="text"
                                                                    name="subjects[{{ $index }}][date]"
                                                                    class="form-control" id="exam_date"
                                                                    value="{{ $exam_session->exam_sess_date }}" disabled />
                                                            </td>
                                                            <td data-label="Session">
                                                                <input type="text"
                                                                    name="subjects[{{ $index }}][session]"
                                                                    class="form-control"
                                                                    value="{{ $exam_session->exam_sess_session }}"
                                                                    disabled />
                                                            </td>
                                                            <td data-label="Time">
                                                                <input type="text"
                                                                    name="subjects[{{ $index }}][time]"
                                                                    class="form-control"
                                                                    value="{{ $exam_session->exam_sess_time }}" disabled />
                                                            </td>
                                                            <td data-label="Duration">
                                                                <input type="text"
                                                                    name="subjects[{{ $index }}][duration]"
                                                                    class="form-control"
                                                                    value="{{ $exam_session->exam_sess_duration }}"
                                                                    disabled />
                                                            </td>
                                                            <td data-label="Subject">
                                                                <input type="text"
                                                                    name="subjects[{{ $index }}][name]"
                                                                    class="form-control"
                                                                    value="{{ $exam_session->exam_sess_subject }}"
                                                                    disabled />
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- Consent Section -->
                        <div class="col-lg-12">
                            <div class="card mt-4 shadow-sm">
                                <div class="card-header bg-primary">
                                    <h5 class="mb-0 text-white"><i class="feather icon-check-circle me-2"></i>Examination
                                        Venue Consent Form</h5>
                                </div>
                                <div class="card-body">
                                    <form id="venueConsentForm"
                                        action="{{ route('venues.submit-venue-consent', $exam->exam_main_no) }}"
                                        method="POST">
                                        @csrf
                                        <div class="mb-4">
                                            <h6 class="text-muted">Dear Venue Administrator,</h6>
                                            <p>We kindly request your consent to utilize your esteemed venue for our
                                                upcoming examination. The examination details are provided above. Your
                                                cooperation is crucial for the successful conduct of this event.</p>
                                        </div>

                                        <div class="mb-4">
                                            <label class="form-label fw-bold">Please indicate your decision:</label>
                                            <div class="d-flex flex-column flex-md-row gap-3">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="consent"
                                                        id="consent_accept" value="accept"
                                                        {{ $venueConsents->consent_status == 'accepted' ? 'checked' : '' }}
                                                        {{ $venueConsents->consent_status == 'denied' ? 'disabled' : '' }}
                                                        required>
                                                    <label class="form-check-label" for="consent_accept">
                                                        <span class="badge bg-success me-2"><i
                                                                class="feather icon-check"></i></span>I agree to host the
                                                        examination
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="consent"
                                                        id="consent_decline" value="decline"
                                                        {{ $venueConsents->consent_status == 'denied' ? 'checked' : '' }}
                                                        {{ $venueConsents->consent_status == 'accepted' ? 'disabled' : '' }}
                                                        required>
                                                    <label class="form-check-label" for="consent_decline">
                                                        <span class="badge bg-danger me-2"><i
                                                                class="feather icon-x"></i></span>I am unable to host the
                                                        examination
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Additional fields to show when "accept" is selected -->
                                        <div id="additionalFields" class="d-none">
                                            <!-- Input for total number of candidates -->
                                            <div class="mb-4 row">
                                                <div class="col-sm-4">
                                                    <label for="allottedCandidates" class="form-label fw-bold">Allotted
                                                        Candidates:</label>
                                                    <input type="text" class="form-control" id="allottedCandidates"
                                                        value="{{ $venueConsents->expected_candidates_count ?? 0 }}"
                                                        disabled>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label for="canidatesPerHall" class="form-label fw-bold">Total
                                                        Candidates (Max per Hall):</label>
                                                    <input type="text" class="form-control" id="canidatesPerHall"
                                                        value="{{ $exam->exam_main_candidates_for_hall ?? 0 }}" disabled>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label for="venueCapacity" class="form-label fw-bold">Venue Capacity
                                                        (Max Candidates):</label>
                                                    <input type="number" class="form-control" id="venueCapacity"
                                                        name="venueCapacity" placeholder="Enter venue capacity"
                                                        value="{{ $venueConsents->venue_max_capacity ?? '' }}"        
                                                        {{ isset($venueConsents->venue_max_capacity) && $venueConsents->venue_max_capacity !== '' ? 'disabled' : '' }} 
                                                        required>
                                                </div>
                                            </div>

                                            <!-- Table for CI dropdown and candidates allocation -->
                                            <div class="mb-4">
                                                <label class="form-label fw-bold">Chief Invigilator and Candidate
                                                    Allocation:</label>

                                                @php
                                                    $sessionDates = $exam->examsession
                                                        ->pluck('exam_sess_date')
                                                        ->unique();
                                                    $totalCandidates =
                                                        empty($venueConsents->venue_max_capacity) ||
                                                        !is_numeric($venueConsents->venue_max_capacity) ||
                                                        (int) $venueConsents->venue_max_capacity <= 0
                                                            ? $venueConsents->expected_candidates_count
                                                            : (int) $venueConsents->venue_max_capacity;
                                                    $candidatesPerHall = $exam->exam_main_candidates_for_hall;
                                                    $ciCount = ceil($totalCandidates / $candidatesPerHall);

                                                    // Group assigned CIs by exam date
                                                    $assignedCIsByDate = [];
                                                    foreach ($venueConsents->assignedCIs as $assignedCI) {
                                                        $examDate = \Carbon\Carbon::parse(
                                                            $assignedCI->exam_date,
                                                        )->format('d-m-Y');
                                                        $assignedCIsByDate[$examDate][] = $assignedCI->ci_id;
                                                    }
                                                @endphp

                                                <table class="table table-bordered" id="responsiveTable">
                                                    <thead>
                                                        <tr>
                                                            <th>Exam Date</th>
                                                            <th>Chief Invigilator (CI)</th>
                                                            <th>Designation</th>
                                                            <th>E-mail</th>
                                                            <th>Phone Number</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $overallIndex = 0;
                                                        @endphp
                                                        @foreach ($sessionDates as $sessionDate)
                                                            @php
                                                                $formattedDate = \Carbon\Carbon::parse(
                                                                    $sessionDate,
                                                                )->format('d-m-Y');
                                                                $savedCIIdsForDate =
                                                                    $assignedCIsByDate[$formattedDate] ?? [];
                                                            @endphp

                                                            @for ($i = 0; $i < $ciCount; $i++)
                                                                <tr>
                                                                    <td>
                                                                        <input type="date" class="form-control"
                                                                            name="examDate[]"
                                                                            value="{{ \Carbon\Carbon::parse($sessionDate)->format('Y-m-d') }}"
                                                                            disabled>
                                                                    </td>
                                                                    <td>
                                                                        <select class="form-select" name="ciName[]">
                                                                            <option value="">Select Chief Invigilator
                                                                            </option>
                                                                            @foreach ($chiefInvigilators as $ci)
                                                                                @php
                                                                                    $isSelected =
                                                                                        isset($savedCIIdsForDate[$i]) &&
                                                                                        $savedCIIdsForDate[$i] ==
                                                                                            $ci->ci_id;
                                                                                @endphp
                                                                                <option value="{{ $ci->ci_id }}"
                                                                                    {{ $isSelected ? 'selected' : '' }}>
                                                                                    {{ $ci->ci_name }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" class="form-control"
                                                                            name="ciDesignation[]"
                                                                            placeholder="Designation" disabled>
                                                                    </td>
                                                                    <td>
                                                                        <input type="email" class="form-control"
                                                                            name="ciEmail[]" placeholder="Email" disabled>
                                                                    </td>
                                                                    <td>
                                                                        <input type="tel" class="form-control"
                                                                            name="ciPhone[]" placeholder="Phone Number"
                                                                            disabled>
                                                                    </td>
                                                                </tr>
                                                                @php
                                                                    $overallIndex++;
                                                                @endphp
                                                            @endfor
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        @if (Route::is('venues.venue-consent'))
                                            <div class="d-flex justify-content-end mt-4">
                                                <button type="submit"
                                                    class="btn btn-primary px-4 d-flex align-items-center">
                                                    <i class="feather icon-send me-2"></i>Submit Consent
                                                </button>
                                            </div>
                                        @endif
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('modals.venue-details')
        </div>
        <!-- [ Main Content ] end -->
    </div>

    @include('partials.footer')

    @push('scripts')
        <!-- Include jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="{{ asset('storage//assets/js/plugins/sweetalert2.all.min.js') }}"></script>

        <script>
            $(document).ready(function() {
                // When the radio button with id 'consent_accept' is checked
                $('#consent_accept').on('change', function() {
                    if ($(this).is(':checked')) {
                        // Open the modal
                        $('#venueDetailsModal').modal('show');
                    }
                });
            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const consentAcceptRadio = document.getElementById('consent_accept');
                const consentDeclineRadio = document.getElementById('consent_decline');
                const additionalFields = document.getElementById('additionalFields');

                // Function to show/hide additional fields
                function toggleAdditionalFields() {
                    if (consentAcceptRadio.checked) {
                        additionalFields.classList.remove('d-none');
                    } else {
                        additionalFields.classList.add('d-none');
                    }
                }

                // Attach event listeners to the radio buttons
                consentAcceptRadio.addEventListener('change', toggleAdditionalFields);
                consentDeclineRadio.addEventListener('change', toggleAdditionalFields);

                // Initial check on page load
                toggleAdditionalFields();
            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const venueCapacityInput = document.getElementById('venueCapacity');
                const ciTableBody = document.querySelector('#responsiveTable tbody');
                const sessionDates = @json($sessionDates);
                const chiefInvigilators = @json($chiefInvigilators);
                const exam_candidate_count = {{ $exam->exam_main_candidates_for_hall }};

                // Function to generate a single CI table row
                function createCIRow(examDate, savedCIIdsForDate, index) {
                    const row = document.createElement('tr');

                    // Exam Date
                    const dateCell = document.createElement('td');
                    const dateInput = document.createElement('input');
                    dateInput.type = 'date';
                    dateInput.className = 'form-control';
                    dateInput.name = 'examDate[]';
                    dateInput.value = examDate;
                    dateInput.disabled = true;
                    dateCell.appendChild(dateInput);
                    row.appendChild(dateCell);

                    // Chief Invigilator Dropdown
                    const ciCell = document.createElement('td');
                    const ciSelect = document.createElement('select');
                    ciSelect.className = 'form-select';
                    ciSelect.name = 'ciName[]';

                    // Add default option
                    const defaultOption = document.createElement('option');
                    defaultOption.value = '';
                    defaultOption.textContent = 'Select Chief Invigilator';
                    ciSelect.appendChild(defaultOption);

                    // Add CI options
                    chiefInvigilators.forEach(ci => {
                        const option = document.createElement('option');
                        option.value = ci.ci_id;
                        option.textContent = ci.ci_name;
                        if (savedCIIdsForDate && savedCIIdsForDate[index] === ci.ci_id) {
                            option.selected = true;
                        }
                        ciSelect.appendChild(option);
                    });

                    ciCell.appendChild(ciSelect);
                    row.appendChild(ciCell);

                    // Designation, Email, Phone
                    ['Designation', 'Email', 'Phone'].forEach((field, idx) => {
                        const cell = document.createElement('td');
                        const input = document.createElement('input');
                        input.type = idx === 1 ? 'email' : 'text';
                        input.className = 'form-control';
                        input.name = `ci${field}[]`;
                        input.placeholder = field;
                        input.disabled = true;
                        cell.appendChild(input);
                        row.appendChild(cell);
                    });

                    return row;
                }

                // Function to update CI table based on venue capacity
                function updateCITable(venueCapacity) {
                    if (!venueCapacity || venueCapacity <= 0) {
                        ciTableBody.innerHTML = '';
                        return;
                    }

                    // Calculate number of halls needed
                    const hallCount = Math.ceil(venueCapacity / exam_candidate_count);
                    ciTableBody.innerHTML = ''; // Clear existing rows

                    sessionDates.forEach(sessionDate => {
                        // Convert dd-mm-yyyy to yyyy-mm-dd for compatibility with JavaScript Date
                        const [day, month, year] = sessionDate.split('-');
                        const formattedDate = `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`;

                        // Retrieve saved CI IDs for the formatted date
                        const savedCIIdsForDate = @json($assignedCIsByDate)[formattedDate] || [];

                        // Generate rows for the calculated hall count
                        for (let i = 0; i < hallCount; i++) {
                            const row = createCIRow(formattedDate, savedCIIdsForDate, i);
                            ciTableBody.appendChild(row);
                        }
                    });
                }

                // Event listener for venue capacity input
                venueCapacityInput.addEventListener('input', function() {
                    const capacity = parseInt(this.value) || 0;
                    updateCITable(capacity);
                });

                // Initial table generation if venue capacity exists
                if (venueCapacityInput.value) {
                    // updateCITable(parseInt(venueCapacityInput.value));
                }

                // Event listener for CI dropdowns to prevent duplicate selection
                $(document).on('change', 'select[name="ciName[]"]', function() {
                    const currentRow = $(this).closest('tr');
                    const currentDate = currentRow.find('input[name="examDate[]"]').val();
                    const selectedCIId = $(this).val();

                    if (selectedCIId === '') {
                        return; // Skip validation if no CI is selected
                    }

                    // Check all other rows for the same date
                    let duplicateFound = false;
                    $('select[name="ciName[]"]').each(function() {
                        const row = $(this).closest('tr');
                        const rowDate = row.find('input[name="examDate[]"]').val();
                        const rowCIId = $(this).val();

                        if (rowDate === currentDate && rowCIId === selectedCIId && row[0] !==
                            currentRow[0]) {
                            duplicateFound = true;
                            return false; // Exit the loop early
                        }
                    });

                    if (duplicateFound) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Duplicate CI Selection',
                            text: 'This Chief Invigilator is already assigned to another hall on the same date.',
                            confirmButtonText: 'OK'
                        });
                        $(this).val(''); // Reset the dropdown
                        $(this).focus(); // Focus back on the dropdown
                    } else {
                        // Update CI details
                        updateCIDetails(this);
                    }
                });
            });
        </script>
        <script>
            // Store Chief Invigilators data as a JavaScript object for easy lookup
            const chiefInvigilators = @json($chiefInvigilators);

            // Function to update row details when CI is selected
            function updateCIDetails(selectElement) {
                // Find the parent row
                const row = $(selectElement).closest('tr');

                // Get selected CI ID
                const selectedCIId = $(selectElement).val();

                // Find the matching CI in the array
                const selectedCI = chiefInvigilators.find(ci => ci.ci_id == selectedCIId);

                if (selectedCI) {
                    // Update designation input
                    row.find('input[name="ciDesignation[]"]').val(selectedCI.ci_designation || '');

                    // Update email input
                    row.find('input[name="ciEmail[]"]').val(selectedCI.ci_email || '');

                    // Update phone input
                    row.find('input[name="ciPhone[]"]').val(selectedCI.ci_phone || '');
                } else {
                    // Clear fields if no CI selected
                    row.find('input[name="ciDesignation[]"]').val('');
                    row.find('input[name="ciEmail[]"]').val('');
                    row.find('input[name="ciPhone[]"]').val('');
                }
            }

            // Attach event listener to CI dropdowns
            $(document).on('change', 'select[name="ciName[]"] option:selected', function() {
                updateCIDetails(this);
            });

            // Trigger details update for pre-selected CIs on page load
            $(document).ready(function() {
                $('select[name="ciName[]"] option:selected').each(function() {
                    if ($(this).val()) {
                        updateCIDetails($(this).closest('select'));
                    }
                });
            });
        </script>
        <script>
            $(document).ready(function() {
                $('#venueConsentForm').on('submit', function(e) {
                    e.preventDefault();

                    // Check which consent option is selected
                    const consentValue = $('input[name="consent"]:checked').val();

                    // Prepare form data
                    const formData = new FormData(this);


                    if (consentValue === 'accept') {
                        // Check if venue capacity is entered
                        const venueCapacity = $('#venueCapacity').val();
                        if (!venueCapacity || venueCapacity <= 0) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Venue Capacity Required',
                                text: 'Please enter the venue capacity.',
                                confirmButtonText: 'OK'
                            });
                            return;
                        }

                        // Check if all CI dropdowns are selected
                        let allCIsSelected = true;
                        $('select[name="ciName[]"]').each(function() {
                            if ($(this).val() === '') {
                                allCIsSelected = false;
                                return false;
                            }
                        });

                        if (!allCIsSelected) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Incomplete Selection',
                                text: 'Please select a Chief Invigilator for each row.',
                                confirmButtonText: 'OK'
                            });
                            return;
                        }

                        const ciExamData = [];

                        // Collect Chief Invigilator IDs and their respective exam dates
                        $('input[name="examDate[]"]').each(function(index) {
                            const examDate = $(this).val();
                            const ciId = $(`select[name="ciName[]"]`).eq(index).val();

                            if (ciId) {
                                ciExamData.push({
                                    id: index,
                                    exam_date: examDate,
                                    ci_id: ciId
                                });
                            }
                        });

                        // Append additional data
                        formData.append('ciExamData', JSON.stringify(ciExamData));
                        formData.append('venueCapacity', $('#venueCapacity').val());
                    }

                    // Send AJAX request
                    $.ajax({
                        url: $(this).attr('action'),
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        beforeSend: function() {
                            const loader = document.getElementById('loader');
                            if (loader) loader.style.removeProperty('display');
                        },
                        success: function(response) {
                            const loader = document.getElementById('loader');
                            if (loader) loader.style.display = 'none';
                            // Handle successful submission
                            Swal.fire({
                                icon: 'success',
                                title: 'Consent Submitted',
                                text: response.message ||
                                    'Your consent has been recorded successfully.',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                // Optionally redirect or refresh
                                window.location.href =
                                    "{{ route('my-exam.examTask', ['examid' => $exam->exam_main_no]) }}";
                            });
                        },
                        error: function(xhr) {
                            const loader = document.getElementById('loader');
                            if (loader) loader.style.display = 'none';

                            // Handle errors
                            Swal.fire({
                                icon: 'error',
                                title: 'Submission Failed',
                                text: xhr.responseJSON?.message ||
                                    'There was an error submitting your consent.',
                                confirmButtonText: 'Try Again'
                            });
                        }
                    });
                });

                // Existing radio button toggle logic
                const consentAcceptRadio = document.getElementById('consent_accept');
                const consentDeclineRadio = document.getElementById('consent_decline');
                const additionalFields = document.getElementById('additionalFields');

                function toggleAdditionalFields() {
                    if (consentAcceptRadio.checked) {
                        additionalFields.classList.remove('d-none');
                    } else {
                        additionalFields.classList.add('d-none');
                    }
                }

                consentAcceptRadio.addEventListener('change', toggleAdditionalFields);
                consentDeclineRadio.addEventListener('change', toggleAdditionalFields);

                // Initial check on page load
                toggleAdditionalFields();
            });
        </script>
    @endpush
    @include('partials.theme')

@endsection
