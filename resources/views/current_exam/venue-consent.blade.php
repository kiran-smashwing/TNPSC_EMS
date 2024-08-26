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

                                        <!-- Exam Name -->
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="exam_name">Exam Name</label>
                                                <input type="text" class="form-control" id="exam_name" name="exam_name"
                                                    value="Combined Civil Services Examination - II" disabled>
                                            </div>
                                        </div>

                                        <!-- Exam Name in Tamil -->
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="exam_name_tamil">Exam Name in Tamil</label>
                                                <input type="text" class="form-control" id="exam_name_tamil"
                                                    name="exam_name_tamil"
                                                    value="ஒருங்கிணைந்த சிவில் சர்வீசஸ் தேர்வு - II (குரூப் II மற்றும் IIA சேவைகள்)"
                                                    disabled>
                                            </div>
                                        </div>

                                        <!-- Exam Model -->
                                        <div class="col-sm-3">
                                            <div class="mb-3">
                                                <label class="form-label" for="exam_model">Exam Model</label>
                                                <select class="form-control" id="exam_model" name="exam_model" disabled>
                                                    <option selected>Major</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Exam Type -->
                                        <div class="col-sm-3">
                                            <div class="mb-3">
                                                <label class="form-label" for="exam_type">Type of Exam</label>
                                                <select class="form-control" id="exam_type" name="exam_type" disabled>
                                                    <option selected>Objective</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Exam Service -->
                                        <div class="col-sm-3">
                                            <div class="mb-3">
                                                <label class="form-label" for="exam_service">Exam Service</label>
                                                <select class="form-control" id="exam_service" name="exam_service" disabled>
                                                    <option selected>GROUP I SERVICES EXAMINATION</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-bordered" id="responsiveTable">
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
                                                    <tr>
                                                        <td data-label="Exam Date">
                                                            <div class="input-group date">
                                                                <input type="text" name="exam_date" class="form-control"
                                                                    value="05/20/2017" id="exam_date" disabled />
                                                                <span class="input-group-text">
                                                                    <i class="feather icon-calendar"></i>
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td data-label="Session">
                                                            <select name="subjects[0][session]" class="form-control"
                                                                disabled>
                                                                <option value="fn" selected>FN</option>
                                                            </select>
                                                        </td>
                                                        <td data-label="Time">
                                                            <select name="subjects[0][time]" class="form-control" disabled
                                                                required>
                                                                <option selected value="09:30AM">09:30AM</option>
                                                            </select>
                                                        </td>
                                                        <td data-label="Duration">
                                                            <select name="subjects[0][duration]" class="form-control"
                                                                disabled>
                                                                <option selected value="2.5hrs">2:30 Hrs</option>
                                                            </select>
                                                        </td>
                                                        <td data-label="Subject">
                                                            <input type="text" name="subjects[0][name]"
                                                                class="form-control" placeholder="Subject Name"
                                                                value="Maths" disabled />
                                                        </td>
                                                    </tr>
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
