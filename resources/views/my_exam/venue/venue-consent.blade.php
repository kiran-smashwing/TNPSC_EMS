@extends('layouts.app')

@section('title', 'Current Exam')
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
                                    <form action="" method="POST">
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
                                                        id="consent_accept" value="accept" required>
                                                    <label class="form-check-label" for="consent_accept">
                                                        <span class="badge bg-success me-2"><i
                                                                class="feather icon-check"></i></span>I agree to host the
                                                        examination
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="consent"
                                                        id="consent_decline" value="decline" required>
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
                                            <div class="mb-4 row ">
                                                <label for="totalCandidates" class="form-label fw-bold">Total no of
                                                    Halls:</label>
                                                <div class="col-sm-3 col-md-3">
                                                    <select class="form-select" name="allocationCount">
                                                        <option value="">No of Halls</option>
                                                        <option value="200">1 - 200</option>
                                                        <option selected value="400">2 - 400</option>
                                                        <option value="600">3 - 600</option>
                                                        <option value="600">4 - 800</option>
                                                        <option value="600">5 - 1000</option>
                                                        <!-- Add more options as needed -->
                                                    </select>

                                                </div>
                                            </div>


                                            <!-- Table for CI dropdown and candidates allocation -->
                                            <div class="mb-4">
                                                <label class="form-label fw-bold">Chief Invigilator and Candidate
                                                    Allocation:</label>
                                                <table class="table table-bordered" id="responsiveTable">
                                                    <thead>
                                                        <tr>
                                                            <th>Chief Invigilator (CI)</th>
                                                            <th>Designation</th>
                                                            <th>E-mail</th>
                                                            <th>Phone Number</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <select class="form-select" name="ciName">
                                                                    <option value="">Select Chief Invigilator
                                                                    </option>
                                                                    <option value="ci1">CI 1</option>
                                                                    <option value="ci2">CI 2</option>
                                                                    <option value="ci3">CI 3</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                Professor
                                                            </td>
                                                            <td>
                                                                Jhondoe@gmail.com
                                                            </td>
                                                            <td>
                                                                9123456780
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <select class="form-select" name="ciName">
                                                                    <option value="">Select Chief Invigilator
                                                                    </option>
                                                                    <option value="ci1">CI 1</option>
                                                                    <option value="ci2">CI 2</option>
                                                                    <option value="ci3">CI 3</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                Professor
                                                            </td>
                                                            <td>
                                                                Jhondoe@gmail.com
                                                            </td>
                                                            <td>
                                                                9123456780
                                                            </td>
                                                        </tr>
                                                        <!-- You can add more rows as needed -->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-end mt-4">
                                            <button type="submit" class="btn btn-primary px-4 d-flex align-items-center">
                                                <i class="feather icon-send me-2"></i>Submit Consent
                                            </button>
                                        </div>
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
    @endpush
    @include('partials.theme')

@endsection
