@extends('layouts.app')

@section('title', 'Current Exam')
@push('styles')
    <link rel="stylesheet" href="{{ asset('storage/assets/css/plugins/datepicker-bs5.min.css') }}" />
    <style>
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
                                    <h5>Current Exam - <span class="text-primary">Details</span></h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="mb-3">
                                                <label class="form-label" for="exam_main_no">Exam ID</label>
                                                <input type="text" class="form-control" id="exam_main_no"
                                                    name="exam_main_no" disabled value="{{ $exam->exam_main_no }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="mb-3">
                                                <label class="form-label" for="exam_main_type">Type of Exam</label>
                                                <input type="text" class="form-control" id="exam_main_type"
                                                    name="exam_main_type" disabled value="{{ $exam->exam_main_type }}">
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
                                                <label class="form-label" for="exam_main_tiers">Exam Tiers</label>
                                                <input type="text" class="form-control" id="exam_main_tiers"
                                                    name="exam_main_tiers" disabled value="{{ $exam->exam_main_tiers }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="exam_main_service">Exam Service</label>
                                                <input type="text" class="form-control" id="exam_main_service"
                                                    name="exam_main_service" disabled
                                                    value="{{ $exam->exam_main_service }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="mb-3">
                                                <label class="form-label" for="exam_main_notification">Notification
                                                    no</label>
                                                <input type="text" class="form-control" id="exam_main_notification"
                                                    name="exam_main_notification" disabled
                                                    value="{{ $exam->exam_main_notification }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="mb-3">
                                                <label class="form-label" for="exam_main_notifdate">Notification
                                                    Date</label>
                                                <input type="text" class="form-control" id="exam_main_notifdate"
                                                    name="exam_main_notifdate" disabled
                                                    value="{{ $exam->exam_main_notifdate }}">
                                            </div>
                                        </div>
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
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="exam_main_postname">Post Name</label>
                                                <input type="text" class="form-control" id="exam_main_postname"
                                                    name="exam_main_postname" disabled
                                                    value="{{ $exam->exam_main_postname }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="mb-3">
                                                <label class="form-label" for="exam_main_lastdate">Last Date For
                                                    Apply</label>
                                                <input type="text" class="form-control" id="exam_main_lastdate"
                                                    name="exam_main_lastdate" disabled
                                                    value="{{ $exam->exam_main_lastdate }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="mb-3">
                                                <label class="form-label" for="exam_main_startdate">Exam Start
                                                    Date</label>
                                                <input type="text" class="form-control" id="exam_main_startdate"
                                                    name="exam_main_startdate" disabled
                                                    value="{{ $exam->exam_main_startdate }}">
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
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($exam->examsession as $index => $exam_session)
                                                <tr>
                                                    <td data-label="Exam Date">
                                                        <input type="text" name="subjects[{{ $index }}][date]"
                                                            class="form-control" id="exam_date"
                                                            value="{{ $exam_session->exam_sess_date }}" disabled />
                                                    </td>
                                                    <td data-label="Session">
                                                        <input type="text"
                                                            name="subjects[{{ $index }}][session]"
                                                            class="form-control"
                                                            value="{{ $exam_session->exam_sess_session }}" disabled />
                                                    </td>
                                                    <td data-label="Time">
                                                        <input type="text" name="subjects[{{ $index }}][time]"
                                                            class="form-control"
                                                            value="{{ $exam_session->exam_sess_time }}" disabled />
                                                    </td>
                                                    <td data-label="Duration">
                                                        <input type="text"
                                                            name="subjects[{{ $index }}][duration]"
                                                            class="form-control"
                                                            value="{{ $exam_session->exam_sess_duration }}" disabled />
                                                    </td>
                                                    <td data-label="Subject">
                                                        <input type="text" name="subjects[{{ $index }}][name]"
                                                            class="form-control"
                                                            value="{{ $exam_session->exam_sess_subject }}" disabled />
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 text-end btn-page">
                            <a href="{{ url()->previous() ?: route('current-exam.index') }}"
                                class="btn btn-outline-secondary">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ Main Content ] end -->
    </div>

    @include('partials.footer')
    @include('partials.theme')

@endsection
