@extends('layouts.app')

@section('title', 'Current Exam')
@push('styles')
    <link rel="stylesheet" href="{{ asset('storage/assets/css/plugins/datepicker-bs5.min.css') }}" />
    <style>
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
            /* For Firefox */
        }

        @media screen and (max-width: 600px) {
            #subjectsTable thead {
                display: none;
            }

            #subjectsTable,
            #subjectsTable tbody,
            #subjectsTable tr,
            #subjectsTable td {
                display: block;
                width: 100%;
            }

            #subjectsTable tr {
                margin-bottom: 15px;
            }

            #subjectsTable td {
                text-align: right;
                padding-left: 50%;
                position: relative;
            }

            #subjectsTable td::before {
                content: attr(data-label);
                position: absolute;
                left: 6px;
                width: 45%;
                padding-right: 10px;
                white-space: nowrap;
                text-align: left;
                font-weight: bold;
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
                                    <h5>Current Exam - <span class="text-primary">Add</span></h5>
                                </div>
                                <div class="card-body">
                                    <form id="examForm" action="{{ route('current-exam.store') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="exam_main_no">Exam ID <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="exam_main_no"
                                                        name="exam_main_no" readonly required value="{{ date('YmdHis') }}">
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="exam_main_type">Type of Exam<span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-control" id="exam_main_type" name="exam_main_type"
                                                        required>
                                                        <option disabled selected>Select Exam Type</option>
                                                        <option value="Objective">Objective</option>
                                                        <option value="Descriptive">Descriptive</option>
                                                        <option value="CBT">CBT</option>
                                                        <option value="Objective+Descriptive">Objective + Descriptive
                                                        </option>
                                                    </select>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="exam_main_model">Exam Model<span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-control" id="exam_main_model" name="exam_main_model"
                                                        required>
                                                        <option disabled selected>Select Exam Model</option>
                                                        <option value="Major">Major</option>
                                                        <option value="Minor">Minor</option>
                                                    </select>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="exam_main_tiers">Exam Tiers<span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-control" data-trigger id="choices-single-groups"
                                                        name="exam_main_tiers" required>
                                                        <option disabled selected>Select Exam Tiers</option>
                                                        <optgroup label="Single Tier">
                                                            <option value="1-Single Tier">Single</option>
                                                        </optgroup>
                                                        <optgroup label="Multi Tiers">
                                                            <option value="2-Preliminary"> Preliminary</option>
                                                            <option value="2-Mains"> Mains</option>
                                                        </optgroup>
                                                    </select>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="exam_main_service">Exam Service
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <select class="form-control" id="exam_main_service"
                                                        name="exam_main_service" required>
                                                        <option disabled selected>Select Exam Service</option>
                                                        @foreach ($examServices as $service)
                                                            <option value="{{ $service->examservice_id }}">
                                                                {{ $service->examservice_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="exam_main_notification">Notification no
                                                        <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="exam_main_notification"
                                                        name="exam_main_notification" required placeholder="08/2024">
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="mb-3 ">
                                                    <label class="form-label" for="exam_main_notifdate">Notification Date
                                                        <span class="text-danger">*</span></label>
                                                    <div class="input-group date">
                                                        <input type="text" name="exam_main_notifdate"
                                                            class="form-control" value="05/20/2017"
                                                            id="exam_main_notifdate" />
                                                        <span class="input-group-text">
                                                            <i class="feather icon-calendar"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="exam_main_name">Exam Name <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="exam_main_name"
                                                        name="exam_main_name" required
                                                        placeholder="Combined Civil Services Examination - II">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="exam_main_nametamil">Exam Name in Tamil
                                                        <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="exam_main_nametamil"
                                                        name="exam_main_nametamil" required
                                                        placeholder="ஒருங்கிணைந்த சிவில் சர்வீசஸ் தேர்வு - II (குரூப் II மற்றும் IIA சேவைகள்)">
                                                </div>
                                            </div>
                                            <!-- <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="exam_main_postname">Post Name <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="exam_main_postname"
                                                        name="exam_main_postname" required
                                                        placeholder="Group II and IIA Services">
                                                </div>
                                            </div> -->
                                            <div class="col-sm-3">
                                                <div class="mb-3 ">
                                                    <label class="form-label" for="exam_main_lastdate">Last Date For Apply
                                                        <span class="text-danger">*</span></label>
                                                    <div class="input-group date">
                                                        <input type="text" name="exam_main_lastdate"
                                                            class="form-control" value="05/20/2017"
                                                            id="exam_main_lastdate" />
                                                        <span class="input-group-text">
                                                            <i class="feather icon-calendar"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="mb-3 ">
                                                    <label class="form-label" for="exam_main_startdate">Exam Start Date
                                                        <span class="text-danger">*</span></label>
                                                    <div class="input-group date">
                                                        <input type="text" name="exam_main_startdate"
                                                            class="form-control" value="05/20/2017"
                                                            id="exam_main_startdate" />
                                                        <span class="input-group-text">
                                                            <i class="feather icon-calendar"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="mb-3">
                                                    <label class="form-label"
                                                        for="exam_main_candidates_for_hall">Candidates For Each Hall <span
                                                            class="text-danger">*</span></label>
                                                    <input type="number" class="form-control"
                                                        id="exam_main_candidates_for_hall"
                                                        name="exam_main_candidates_for_hall" required placeholder="200"
                                                        min="1"
                                                        oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/^0+/, '');"
                                                        style="padding-right: 0;">
                                                </div>
                                            </div>

                                        </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 mt-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Exam Subjects, Date, and Session</h5>
                                </div>
                                <div class="table-responsive" style="overflow-x: visible;">
                                    <table class="table table-bordered" id="subjectsTable">
                                        <thead>
                                            <tr>
                                                <th>Exam Date</th>
                                                <th>Session</th>
                                                <th>Time</th>
                                                <th>Duration</th>
                                                <th>Subject</th>
                                                <th>Type</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td data-label="Exam Date">
                                                    <div class="input-group date">
                                                        <input type="text" name="subjects[0][date]"
                                                            class="form-control" value="05/20/2017" id="exam_date"
                                                            required />
                                                        <span class="input-group-text">
                                                            <i class="feather icon-calendar"></i>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td data-label="Session">
                                                    <select name="subjects[0][session]" class="form-control" required>
                                                        <option disabled selected>Select Session</option>
                                                        <option value="FN">FN</option>
                                                        <option value="AN">AN</option>
                                                    </select>
                                                </td>
                                                <td data-label="Time">
                                                    <select name="subjects[0][time]" class="form-control" required>
                                                        <option disabled selected>Select Time</option>
                                                        <option value="09:30AM">09:30AM</option>
                                                        <option value="10:00AM">10:00AM</option>
                                                        <option value="10:30AM">10:30AM</option>
                                                        <option value="11:00AM">11:00AM</option>
                                                        <option value="11:30AM">11:30AM</option>
                                                        <option value="02:00PM">02:00PM</option>
                                                        <option value="02:30PM">02:30PM</option>
                                                        <option value="03:00PM">03:00PM</option>
                                                        <option value="03:30PM">03:30PM</option>
                                                    </select>
                                                </td>
                                                <td data-label="Duration">
                                                    <select name="subjects[0][duration]" class="form-control" required>
                                                        <option disabled selected>Select Duration</option>
                                                        <option value="2hrs">2 Hrs</option>
                                                        <option value="2.5hrs">2:30 Hrs</option>
                                                        <option value="3hrs">3 Hrs</option>
                                                    </select>
                                                </td>
                                                <td data-label="Subject">
                                                    <input type="text" name="subjects[0][name]" class="form-control"
                                                        placeholder="Subject Name" required />
                                                </td>
                                                <td>
                                                    <select name="subjects[0][type]" class="form-control" required>
                                                        <option disabled selected>Select Type</option>
                                                        <option value="Objective">Objective</option>
                                                        <option value="Descriptive">Descriptive</option>
                                                        <option value="CBT">CBT</option>
                                                    </select>
                                                </td>
                                                <td data-label="Action">
                                                    <button type="button" class="btn btn-success add-row">+</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 text-end btn-page">
                            <a href="{{ route('current-exam.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create</button>

                        </div>
                    </div>
                    </form>
                </div>


            </div>
        </div>
        <!-- [ Main Content ] end -->
    </div>

    @include('partials.footer')

    @push('scripts')
        <script src="{{ asset('storage/assets/js/plugins/choices.min.js') }}"></script>
        <script src="{{ asset('storage/assets/js/plugins/sweetalert2.all.min.js') }}"></script>
        <script src="{{ asset('storage/assets/js/plugins/datepicker-full.min.js') }}"></script>
        <script>
            function initializeDatepicker(element) {
                const datepicker = new Datepicker(element, {
                    buttonClass: 'btn',
                    todayBtn: true,
                    clearBtn: true,
                    format: 'dd-mm-yyyy',
                    autohide: true,
                });
                // Set default date to today
                datepicker.setDate(new Date());
            }

            document.addEventListener('DOMContentLoaded', function() {
                let rowIndex = 1;

                // Initialize datepickers for existing elements
                document.querySelectorAll('.date input').forEach(initializeDatepicker);

                // Add a new row
                document.querySelector('#subjectsTable').addEventListener('click', function(e) {
                    if (e.target.classList.contains('add-row')) {
                        e.preventDefault();

                        const newRow = `
                <tr>
                    <td data-label="Exam Date">
                        <div class="input-group date">
                            <input type="text" name="subjects[${rowIndex}][date]" class="form-control datepicker-input" value="05/20/2017" required />
                            <span class="input-group-text">
                                <i class="feather icon-calendar"></i>
                            </span>
                        </div>
                    </td>
                    <td data-label="Session">
                        <select name="subjects[${rowIndex}][session]" class="form-control" required>
                            <option disabled selected>Select Session</option>
                            <option value="FN">FN</option>
                            <option value="AN">AN</option>
                        </select>
                    </td>
                    <td data-label="Time">
                        <select name="subjects[${rowIndex}][time]" class="form-control" required>
                            <option disabled selected>Select Time</option>
                            <option value="09:30AM">09:30AM</option>
                            <option value="10:00AM">10:00AM</option>
                            <option value="10:30AM">10:30AM</option>
                            <option value="11:00AM">11:00AM</option>
                            <option value="11:30AM">11:30AM</option>
                            <option value="02:00PM">02:00PM</option>
                            <option value="02:30PM">02:30PM</option>
                            <option value="03:00PM">03:00PM</option>
                            <option value="03:30PM">03:30PM</option>
                        </select>
                    </td>
                    <td data-label="Duration">
                        <select name="subjects[${rowIndex}][duration]" class="form-control" required>
                            <option disabled selected>Select Duration</option>
                            <option value="2hrs">2 Hrs</option>
                            <option value="2.5hrs">2:30 Hrs</option>
                            <option value="3hrs">3 Hrs</option>
                        </select>
                    </td>
                    <td data-label="Subject">
                        <input type="text" name="subjects[${rowIndex}][name]" class="form-control" placeholder="Subject Name" required />
                    </td>
                    <td>
                        <select name="subjects[${rowIndex}][type]" class="form-control" required>
                            <option disabled selected>Select Type</option>
                            <option value="Objective">Objective</option>
                            <option value="Descriptive">Descriptive</option>
                            <option value="CBT">CBT</option>
                        </select>
                    </td>
                    <td data-label="Action">
                        <button type="button" class="btn btn-danger remove-row">-</button>
                    </td>
                </tr>
            `;

                        document.querySelector('#subjectsTable tbody').insertAdjacentHTML('beforeend', newRow);

                        // Initialize datepicker for the new row
                        const newRowDateInput = document.querySelector(
                            `#subjectsTable tbody tr:last-child .datepicker-input`
                        );
                        initializeDatepicker(newRowDateInput);

                        rowIndex++;
                    }
                });

                // Remove a row
                document.querySelector('#subjectsTable').addEventListener('click', function(e) {
                    if (e.target.classList.contains('remove-row')) {
                        e.preventDefault();
                        e.target.closest('tr').remove();
                    }
                });

                // Before form submission, ensure all rows are captured
                document.querySelector('#examForm').addEventListener('submit', function(e) {
                    // Remove any existing subjects inputs
                    const existingSubjectsInputs = this.querySelectorAll('input[name^="subjects["]');
                    existingSubjectsInputs.forEach(input => input.remove());

                    // Collect all rows and their data
                    const rows = document.querySelectorAll('#subjectsTable tbody tr');
                    const subjects = [];

                    rows.forEach((row, index) => {
                        const subject = {
                            date: row.querySelector(`input[name^="subjects["][name$="[date]"]`)
                                .value,
                            session: row.querySelector(
                                `select[name^="subjects["][name$="[session]"]`).value,
                            time: row.querySelector(`select[name^="subjects["][name$="[time]"]`)
                                .value,
                            duration: row.querySelector(
                                `select[name^="subjects["][name$="[duration]"]`).value,
                            name: row.querySelector(`input[name^="subjects["][name$="[name]"]`)
                                .value,
                            type: row.querySelector(`select[name^="subjects["][name$="[type]"]`)
                                .value
                        };

                        // Create hidden inputs for each subject
                        const dateInput = document.createElement('input');
                        dateInput.type = 'hidden';
                        dateInput.name = `subjects[${index}][date]`;
                        dateInput.value = subject.date;
                        this.appendChild(dateInput);

                        const sessionInput = document.createElement('input');
                        sessionInput.type = 'hidden';
                        sessionInput.name = `subjects[${index}][session]`;
                        sessionInput.value = subject.session;
                        this.appendChild(sessionInput);

                        const timeInput = document.createElement('input');
                        timeInput.type = 'hidden';
                        timeInput.name = `subjects[${index}][time]`;
                        timeInput.value = subject.time;
                        this.appendChild(timeInput);

                        const durationInput = document.createElement('input');
                        durationInput.type = 'hidden';
                        durationInput.name = `subjects[${index}][duration]`;
                        durationInput.value = subject.duration;
                        this.appendChild(durationInput);

                        const nameInput = document.createElement('input');
                        nameInput.type = 'hidden';
                        nameInput.name = `subjects[${index}][name]`;
                        nameInput.value = subject.name;
                        this.appendChild(nameInput);

                        const typeInput = document.createElement('input');
                        typeInput.type = 'hidden';
                        typeInput.name = `subjects[${index}][type]`;
                        typeInput.value = subject.type;
                        this.appendChild(typeInput);

                        subjects.push(subject);
                    });

                    // // Create a hidden input to send all subjects as JSON (optional)
                    // const hiddenInput = document.createElement('input');
                    // hiddenInput.type = 'hidden';
                    // hiddenInput.name = 'subjects_json';
                    // hiddenInput.value = JSON.stringify(subjects);
                    // this.appendChild(hiddenInput);
                });
            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const examTiersSelect = document.getElementById('choices-single-groups');

                examTiersSelect.addEventListener('change', function() {
                    if (this.value === '2-Mains') {
                        Swal.fire({
                            title: 'Input Preliminary Exam Notification Number',
                            input: 'text',
                            inputPlaceholder: '12/2024'
                        }).then((result) => {
                            if (result.value) {
                                const notificationNumber = result.value;
                                // Fetch exam details using the entered notification number
                                fetch(
                                        `{{ url('/current-exam/fetch-exam-details') }}`, {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                            },
                                            body: JSON.stringify({
                                                notificationNumber: notificationNumber
                                            })
                                        })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.error) {
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Error',
                                                text: 'No preliminary exam found for the provided notification number.'
                                            });
                                        } else {
                                            // Populate the form with fetched exam details
                                            document.getElementById('exam_main_type').value = data
                                                .exam_main_type;
                                            document.getElementById('exam_main_model').value = data
                                                .exam_main_model;
                                            document.getElementById('exam_main_service').value =
                                                data.exam_main_service;
                                            document.getElementById('exam_main_notification')
                                                .value = data.exam_main_notification;
                                            document.getElementById('exam_main_notifdate').value =
                                                data.exam_main_notifdate;
                                            document.getElementById('exam_main_name').value = data
                                                .exam_main_name;
                                            document.getElementById('exam_main_nametamil').value =
                                                data.exam_main_nametamil;
                                            document.getElementById('exam_main_postname').value =
                                                data.exam_main_postname;
                                            document.getElementById('exam_main_lastdate').value =
                                                data.exam_main_lastdate;
                                            document.getElementById('exam_main_startdate').value =
                                                data.exam_main_startdate;
                                            // all readonly fields
                                            document.getElementById('exam_main_service').disabled =
                                                true;
                                            document.getElementById('exam_main_notification')
                                                .disabled = true;
                                            document.getElementById('exam_main_notifdate')
                                                .disabled = true;
                                            document.getElementById('exam_main_name').disabled =
                                                true;
                                            document.getElementById('exam_main_nametamil')
                                                .disabled = true;
                                            document.getElementById('exam_main_postname').disabled =
                                                true;
                                            document.getElementById('exam_main_lastdate').disabled =
                                                true;

                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error fetching exam details:', error);
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: 'An error occurred while fetching exam details.'
                                        });
                                    });
                            }
                        });
                    }
                });
            });
        </script>
    @endpush
    @include('partials.theme')

@endsection
