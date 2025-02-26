@extends('layouts.app')
@section('title', ' Dashboard')
@push('styles')
    <link href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/styles/monokai-sublime.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('storage/assets/css/plugins/quill.core.css') }}" />
    <link rel="stylesheet" href="{{ asset('storage/assets/css/plugins/quill.snow.css') }}" />
    <link rel="stylesheet" href="{{ asset('storage/assets/css/plugins/prism-coy.css') }}" />
    <link rel="stylesheet" href="../assets/fonts/material.css" />

    <style>
        /* Add a higher specificity to prevent the styles from applying inside the inner card */
        .list-unstyled-item {
            margin-bottom: 0px !important;
            padding: 0 !important;
            position: static !important;
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

    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ Main Content ] start -->
            <div class="row">
                <div class="col-xl-12 col-md-12 help-main md-view">

                    <div class=" task-card">
                        <div class="card">
                            <div class="card-body">
                                <nav class="navbar justify-content-between p-0 align-items-center">
                                    <h5><span class="text-primary">{{ $session->currentexam->exam_main_notification }}</span>
                                        - {{ $session->currentexam->exam_main_name }} -
                                        {{ $session->currentexam->examservice->examservice_name }} 
                                        - <span class="text-warning"> {{ $session->currentexam->exam_main_startdate }}</span>
                                    </h5>
                                    {{-- <h5 class="mb-0 d-flex align-items-center">
                                        <span
                                            class="text-primary">{{ $session->currentexam->exam_main_notification }}</span>
                                        - {{ $session->currentexam->exam_main_name }} -
                                        {{ $session->currentexam->examservice->examservice_name }} -
                                        <span
                                            class="text-warning">&nbsp;{{ $session->currentexam->exam_main_startdate }}</span>
                                    </h5> --}}
                                    <div class="btn-group btn-group-sm help-filter" role="group"
                                        aria-label="button groups sm">
                                        <!-- Add your buttons here if needed -->
                                    </div>
                                    {{-- <div class="d-flex align-items-center">

                                        <a href="#" title="Adequacy Check Notification" data-pc-animate="just-me"
                                            data-bs-toggle="modal" data-bs-target="#adequacyCheckNotificationModal"
                                            class="me-2 btn btn-sm btn-light-danger d-flex align-items-center">
                                            <i class="material-icons-two-tone"
                                                style="font-size: 22px">assignment_turned_in</i>
                                        </a>
                                        <a href="#" title="Emergency Alarm Notification" data-pc-animate="just-me"
                                            data-bs-toggle="modal" data-bs-target="#emergencyAlarmNotificationModal"
                                            class="me-2 btn btn-sm btn-light-danger d-flex align-items-center">
                                            <i class="material-icons-two-tone" style="font-size: 22px">add_alert</i>
                                        </a>
                                    </div> --}}

                                </nav>

                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <nav class="navbar justify-content-between p-0 align-items-center">
                                    <div class="d-flex">
                                        <a href="#" title="Adequacy Check Notification" data-pc-animate="just-me"
                                            data-bs-toggle="modal" data-bs-target="#adequacyCheckNotificationModal"
                                            class="me-2 btn btn-sm btn-light-danger">
                                            <i style="font-size: 22px"></i>Exam Materials Discrepancy
                                        </a>
                                        <a href="#" title="Emergency Alarm Notification" data-pc-animate="just-me"
                                            data-bs-toggle="modal" data-bs-target="#emergencyAlarmNotificationModal"
                                            class="me-2 btn btn-sm btn-light-danger">
                                            <i style="font-size: 22px"></i>Emergency Alarm Notifications
                                        </a>
                                    </div>
                                </nav>
                            </div>
                        </div>

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                @if (session('failed_csv_path'))
                                    <br>
                                    <a href="{{ asset('storage/' . session('failed_csv_path')) }}"
                                        class="btn btn-link">Download Failed Rows</a>
                                @endif
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

                        <div class="card-body">
                            <ul class="list-unstyled task-list">
                                @php
                                    $is_materials_received = false;

                                    if (!empty($lastScannedMaterial)) {
                                        $is_materials_received = !is_null($lastScannedMaterial['last_scanned_at']);
                                    }

                                    // Set dynamic badge text and color
                                    $taskStatus = $is_materials_received ? 'Received' : 'Pending';
                                    $badgeClass = $is_materials_received ? 'bg-light-secondary' : 'bg-danger';
                                @endphp
                                <li class="task-list-item">
                                    <i
                                        class="task-icon {{ $is_materials_received ? 'feather icon-check f-w-600 bg-success' : 'bg-danger' }}"></i>
                                    <div class="card ticket-card open-ticket">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-auto mb-3 mb-sm-0">
                                                    <div class="d-sm-inline-block d-flex align-items-center">
                                                        <img class="media-object wid-60 img-radius"
                                                            src="{{ asset('storage/' . current_user()->profile_image) }}"
                                                            alt="Generic placeholder image " />
                                                        <div class="ms-3 ms-sm-0 mb-3 mb-sm-0">
                                                            <ul
                                                                class="text-sm-center list-unstyled mt-2 mb-0 d-inline-block">

                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="popup-trigger">
                                                        <div class="h5 font-weight-bold">Receive Materials From Mobile
                                                            Team <small
                                                                class="badge {{ $badgeClass }} ms-2">{{ $taskStatus }}</small>
                                                        </div>
                                                        <div class="help-sm-hidden">
                                                            <ul class="list-unstyled mt-2 mb-0 text-muted">
                                                                <li class="d-sm-inline-block d-block mt-1"><img
                                                                        src="../assets/images/user/avatar-5.jpg"
                                                                        alt=""
                                                                        class="wid-20 rounded me-2 img-fluid" />Done by
                                                                    <b>{{ $is_materials_received ? Str::limit(current_user()->display_name, 15, '...') : 'Unknown' }}</b>
                                                                </li>
                                                                <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>
                                                                    {{ $is_materials_received ? \Carbon\Carbon::parse($lastScannedMaterial->last_scanned_at)->format('d-m-Y h:i A') : '' }}
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="h5 mt-3"><i
                                                                class="material-icons-two-tone f-16 me-1">apartment</i>
                                                            Chief Invigilator</div>
                                                    </div>
                                                    <div class="mt-2">
                                                        <a target="_blank"
                                                            href="{{ route('receive-exam-materials.mobileTeam-to-ci-materials', [
                                                                'examId' => $session->currentexam->exam_main_no,
                                                                'exam_date' => $session->exam_sess_date,
                                                                'exam_session' => $session->exam_sess_session,
                                                            ]) }}"
                                                            class="me-2 btn btn-sm btn-light-primary"><i
                                                                class="feather icon-aperture mx-1"></i>Scan</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @php
                                    $is_session_answered =
                                        $sessionAnswer !== null && !empty((array) $sessionAnswer->session_answer);

                                    // Set dynamic badge text and color
                                    $taskStatus = $is_session_answered ? 'Verified' : 'Pending';
                                    $badgeClass = $is_session_answered ? 'bg-light-secondary' : 'bg-danger';
                                @endphp
                                <li class="task-list-item">
                                    <i
                                        class="task-icon {{ $is_session_answered ? 'feather icon-check f-w-600 bg-success' : 'bg-danger' }}"></i>
                                    <div class="card ticket-card open-ticket">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-auto mb-3 mb-sm-0">
                                                    <div class="d-sm-inline-block d-flex align-items-center">
                                                        <img class="media-object wid-60 img-radius"
                                                            src="{{ asset('storage/' . current_user()->profile_image) }}"
                                                            alt="Generic placeholder image " />
                                                        <div class="ms-3 ms-sm-0 mb-3 mb-sm-0">
                                                            <ul
                                                                class="text-sm-center list-unstyled mt-2 mb-0 d-inline-block">
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="popup-trigger">
                                                        <div class="h5 font-weight-bold">Session Check <small
                                                                class="badge {{ $badgeClass }} ms-2">{{ $taskStatus }}</small>
                                                        </div>
                                                        <div class="help-sm-hidden">
                                                            <ul class="list-unstyled mt-2 mb-0 text-muted">

                                                                <li class="d-sm-inline-block d-block mt-1"><img
                                                                        src="../assets/images/user/avatar-5.jpg"
                                                                        alt=""
                                                                        class="wid-20 rounded me-2 img-fluid" />Done by
                                                                    <b>{{ $is_session_answered ? Str::limit(current_user()->display_name, 15, '...') : 'Unknown' }}</b>
                                                                </li>
                                                                <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>
                                                                    {{ $is_session_answered ? \Carbon\Carbon::parse($sessionAnswer->session_answer['timestamp'])->format('d-m-Y h:i A') : '' }}
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="h5 mt-3"><i
                                                                class="material-icons-two-tone f-16 me-1">apartment</i>
                                                            Chief Invigilator</div>
                                                    </div>
                                                    <div class="mt-2">
                                                        <a href="#" data-pc-animate="just-me"
                                                            data-bs-toggle="modal" data-bs-target="#sessionCheckListModel"
                                                            class="me-2 btn btn-sm btn-light-primary"><i
                                                                class="feather icon-info mx-1"></i>Verify</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @php

                                    $is_invigilators_selected =
                                        $selectedInvigilator !== null &&
                                        !empty((array) $selectedInvigilator->selected_invigilators);
                                    // Set dynamic badge text and color
                                    $taskStatus = $is_invigilators_selected ? 'Selected' : 'Pending';
                                    $badgeClass = $is_invigilators_selected ? 'bg-light-secondary' : 'bg-danger';
                                @endphp
                                <li class="task-list-item">
                                    <i
                                        class="task-icon {{ $is_invigilators_selected ? 'feather icon-check f-w-600 bg-success' : 'bg-danger' }}"></i>
                                    <div class="card ticket-card open-ticket">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-auto mb-3 mb-sm-0">
                                                    <div class="d-sm-inline-block d-flex align-items-center">
                                                        <img class="media-object wid-60 img-radius"
                                                            src="{{ asset('storage/' . current_user()->profile_image) }}"
                                                            alt="Generic placeholder image " />
                                                        <div class="ms-3 ms-sm-0 mb-3 mb-sm-0">
                                                            <ul
                                                                class="text-sm-center list-unstyled mt-2 mb-0 d-inline-block">
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="popup-trigger">
                                                        <div class="h5 font-weight-bold">Invigilator Attendence /
                                                            Allotment<small
                                                                class="badge {{ $badgeClass }} ms-2">{{ $taskStatus }}</small>
                                                        </div>
                                                        <div class="help-sm-hidden">
                                                            <ul class="list-unstyled mt-2 mb-0 text-muted">
                                                                <li class="d-sm-inline-block d-block mt-1"><img
                                                                        src="../assets/images/user/avatar-5.jpg"
                                                                        alt=""
                                                                        class="wid-20 rounded me-2 img-fluid" />Done by
                                                                    <b>{{ $is_invigilators_selected ? Str::limit(current_user()->display_name, 15, '...') : 'Unknown' }}</b>
                                                                </li>
                                                                <li class="d-sm-inline-block d-block mt-1">
                                                                    <i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>
                                                                    {{ $is_invigilators_selected ? \Carbon\Carbon::parse(time: json_decode($selectedInvigilator->selected_invigilators, true)['timestamp'])->format('d-m-Y h:i A') : '' }}
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="h5 mt-3"><i
                                                                class="material-icons-two-tone f-16 me-1">apartment</i>
                                                            Chief Invigilator</div>
                                                    </div>
                                                    <div class="mt-2">
                                                        @if ($is_invigilators_selected)
                                                            <a href="#" id="viewBtn" data-pc-animate="just-me"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#invigilatorAllotmentModel"
                                                                class="me-2 btn btn-sm btn-light-primary"><i
                                                                    class="feather icon-eye mx-1"></i>View</a>
                                                        @endif
                                                        <a href="#" data-pc-animate="just-me"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#invigilatorSelectModal"
                                                            class="me-2 btn btn-sm btn-light-info"><i
                                                                class="feather icon-plus mx-1"></i>Select</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @php
                                    $is_scribe_selected =
                                        $selectedScribe !== null && !empty((array) $selectedScribe->selected_scribes);

                                    // Set dynamic badge text and color
                                    $taskStatus = $is_scribe_selected ? 'Assigned' : 'Pending';
                                    $badgeClass = $is_scribe_selected ? 'bg-light-secondary' : 'bg-danger';
                                @endphp
                                <li class="task-list-item">
                                    <i
                                        class="task-icon {{ $is_scribe_selected ? 'feather icon-check f-w-600 bg-success' : 'bg-danger' }}"></i>
                                    <div class="card ticket-card open-ticket">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-auto mb-3 mb-sm-0">
                                                    <div class="d-sm-inline-block d-flex align-items-center">
                                                        <img class="media-object wid-60 img-radius"
                                                            src="{{ asset('storage/' . current_user()->profile_image) }}"
                                                            alt="Generic placeholder image " />
                                                        <div class="ms-3 ms-sm-0 mb-3 mb-sm-0">
                                                            <ul
                                                                class="text-sm-center list-unstyled mt-2 mb-0 d-inline-block">
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="popup-trigger">
                                                        <div class="h5 font-weight-bold">Scribe Attendence<small
                                                                class="badge {{ $badgeClass }} ms-2">{{ $taskStatus }}</small>
                                                        </div>
                                                        <div class="help-sm-hidden">
                                                            <ul class="list-unstyled mt-2 mb-0 text-muted">
                                                                <li class="d-sm-inline-block d-block mt-1"><img
                                                                        src="../assets/images/user/avatar-5.jpg"
                                                                        alt=""
                                                                        class="wid-20 rounded me-2 img-fluid" />Done by
                                                                    <b>{{ $is_scribe_selected ? Str::limit(current_user()->display_name, 15, '...') : 'Unknown' }}</b>
                                                                </li>
                                                                <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>
                                                                    {{ $is_scribe_selected ? \Carbon\Carbon::parse(time: json_decode($selectedScribe->selected_scribes, true)['last_updated'])->format('d-m-Y h:i A') : '' }}
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="h5 mt-3"><i
                                                                class="material-icons-two-tone f-16 me-1">apartment</i>
                                                            Chief Invigilator</div>
                                                    </div>
                                                    <div class="mt-2">
                                                        @if ($is_scribe_selected)
                                                            <a href="#" data-pc-animate="just-me"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#scribeAllotmentModal"
                                                                class="me-2 btn btn-sm btn-light-primary"><i
                                                                    class="feather icon-eye mx-1"></i>View</a>
                                                        @endif
                                                        <a href="#" data-pc-animate="just-me"
                                                            data-bs-toggle="modal" data-bs-target="#scribeSelectModal"
                                                            class="me-2 btn btn-sm btn-light-info"><i
                                                                class="feather icon-plus mx-1"></i>Add</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @php
                                    $is_assistant_selected =
                                        $selectedAssistant !== null &&
                                        !empty((array) $selectedAssistant->selected_assistants);

                                    // Set dynamic badge text and color
                                    $taskStatus = $is_assistant_selected ? 'Assigned' : 'Pending';
                                    $badgeClass = $is_assistant_selected ? 'bg-light-secondary' : 'bg-danger';
                                @endphp
                                <li class="task-list-item">
                                    <i
                                        class="task-icon {{ $is_assistant_selected ? 'feather icon-check f-w-600 bg-success' : 'bg-danger' }}"></i>
                                    <div class="card ticket-card open-ticket">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-auto mb-3 mb-sm-0">
                                                    <div class="d-sm-inline-block d-flex align-items-center">
                                                        <img class="media-object wid-60 img-radius"
                                                            src="{{ asset('storage/' . current_user()->profile_image) }}"
                                                            alt="Generic placeholder image " />
                                                        <div class="ms-3 ms-sm-0 mb-3 mb-sm-0">
                                                            <ul
                                                                class="text-sm-center list-unstyled mt-2 mb-0 d-inline-block">
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="popup-trigger">
                                                        <div class="h5 font-weight-bold">CI Assistants Attendence <small
                                                                class="badge {{ $badgeClass }} ms-2">{{ $taskStatus }}</small>
                                                        </div>
                                                        <div class="help-sm-hidden">
                                                            <ul class="list-unstyled mt-2 mb-0 text-muted">
                                                                <li class="d-sm-inline-block d-block mt-1"><img
                                                                        src="../assets/images/user/avatar-5.jpg"
                                                                        alt=""
                                                                        class="wid-20 rounded me-2 img-fluid" />Done by
                                                                    <b>{{ $is_assistant_selected ? Str::limit(current_user()->display_name, 15, '...') : 'Unknown' }}</b>
                                                                </li>
                                                                <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>
                                                                    {{ $is_assistant_selected ? \Carbon\Carbon::parse(time: json_decode($selectedAssistant->selected_assistants, true)['timestamp'])->format('d-m-Y h:i A') : '' }}
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="h5 mt-3"><i
                                                                class="material-icons-two-tone f-16 me-1">apartment</i>
                                                            Chief Invigilator</div>
                                                    </div>
                                                    <div class="mt-2">
                                                        @if ($is_assistant_selected)
                                                            <a href="#" data-pc-animate="just-me"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#ciAssistantAllotmentModal"
                                                                class="me-2 btn btn-sm btn-light-primary"><i
                                                                    class="feather icon-eye mx-1"></i>View</a>
                                                        @endif
                                                        <a href="#" data-pc-animate="just-me"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#ciAssistantSelectModal"
                                                            class="me-2 btn btn-sm btn-light-info"><i
                                                                class="feather icon-plus mx-1"></i>Add</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @php
                                    // Decode the JSON string into a PHP associative array
                                    $timingLog = $qpboxTimeLog ? $qpboxTimeLog['qp_timing_log'] : null;

                                    // Check if 'qp_box_open_time' exists and is not empty
                                    $isBoxOpenTimeSet =
                                        isset($timingLog['qp_box_open_time']) && !empty($timingLog['qp_box_open_time']);

                                    // Set a status or badge class based on whether the time is set
                                    $taskStatus = $isBoxOpenTimeSet ? 'Time Set' : 'Pending';
                                    $badgeClass = $isBoxOpenTimeSet ? 'bg-light-secondary' : 'bg-danger';
                                @endphp


                                <li class="task-list-item">
                                    <i
                                        class="task-icon {{ $isBoxOpenTimeSet ? 'feather icon-check f-w-600 bg-success' : 'bg-danger' }}"></i>
                                    <div class="card ticket-card open-ticket">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-auto mb-3 mb-sm-0">
                                                    <div class="d-sm-inline-block d-flex align-items-center">
                                                        <img class="media-object wid-60 img-radius"
                                                            src="{{ asset('storage/' . current_user()->profile_image) }}"
                                                            alt="Generic placeholder image " />
                                                        <div class="ms-3 ms-sm-0 mb-3 mb-sm-0">
                                                            <ul
                                                                class="text-sm-center list-unstyled mt-2 mb-0 d-inline-block">
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="popup-trigger">
                                                        <div class="h5 font-weight-bold">QP Box Open Time <small
                                                                class="badge {{ $badgeClass }} ms-2">{{ $taskStatus }}</small>
                                                        </div>
                                                        <div class="help-sm-hidden">
                                                            <ul class="list-unstyled mt-2 mb-0 text-muted">
                                                                <li class="d-sm-inline-block d-block mt-1"><img
                                                                        src="../assets/images/user/avatar-5.jpg"
                                                                        alt=""
                                                                        class="wid-20 rounded me-2 img-fluid" />Done by
                                                                    <b>{{ $isBoxOpenTimeSet ? Str::limit(current_user()->display_name, 15, '...') : 'Unknown' }}</b>
                                                                </li>
                                                                <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>
                                                                    {{ $isBoxOpenTimeSet ? \Carbon\Carbon::parse(time: $timingLog['qp_box_open_time'])->format('d-m-Y h:i A') : '' }}
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="h5 mt-3"><i
                                                                class="material-icons-two-tone f-16 me-1">apartment</i>
                                                            Chief Invigilator</div>
                                                    </div>
                                                    <div class="mt-2">
                                                        <a href="#" data-pc-animate="just-me"
                                                            data-bs-toggle="modal" data-bs-target="#qpboxOpenTimeModal"
                                                            class="me-2 btn btn-sm btn-light-primary"><i
                                                                class="feather icon-clock mx-1"></i>Set Current Time</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @php
                                    $attendanceData = $candidateAttendance
                                        ? $candidateAttendance->candidate_attendance
                                        : null;

                                    // Check if 'present' exists and is not empty
                                    $isPresentSet =
                                        isset($attendanceData['present']) && !empty($attendanceData['present']);
                                    // Set a dynamic status and badge class based on whether 'present' data is available
                                    $taskStatus = $isPresentSet ? 'Updated' : 'Pending';
                                    $badgeClass = $isPresentSet ? 'bg-light-secondary' : 'bg-danger';
                                @endphp
                                <li class="task-list-item">
                                    <i
                                        class="task-icon {{ $isPresentSet ? 'feather icon-check f-w-600 bg-success' : 'bg-danger' }}"></i>
                                    <div class="card ticket-card open-ticket">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-auto mb-3 mb-sm-0">
                                                    <div class="d-sm-inline-block d-flex align-items-center">
                                                        <img class="media-object wid-60 img-radius"
                                                            src="{{ asset('storage/' . current_user()->profile_image) }}"
                                                            alt="Generic placeholder image " />
                                                        <div class="ms-3 ms-sm-0 mb-3 mb-sm-0">
                                                            <ul
                                                                class="text-sm-center list-unstyled mt-2 mb-0 d-inline-block">
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="popup-trigger">
                                                        <div class="h5 font-weight-bold">Candidiate Attendance <small
                                                                class="badge {{ $badgeClass }} ms-2">{{ $taskStatus }}</small>
                                                        </div>
                                                        <div class="help-sm-hidden">
                                                            <ul class="list-unstyled mt-2 mb-0 text-muted">
                                                                <li class="d-sm-inline-block d-block mt-1"><img
                                                                        src="../assets/images/user/avatar-5.jpg"
                                                                        alt=""
                                                                        class="wid-20 rounded me-2 img-fluid" />Done by
                                                                    <b>{{ $isPresentSet ? Str::limit(current_user()->display_name, 15, '...') : 'Unknown' }}</b>
                                                                </li>
                                                                <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>
                                                                    {{ $isPresentSet ? \Carbon\Carbon::parse(time: $attendanceData['timestamp'])->format('d-m-Y h:i A') : '' }}
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="h5 mt-3"><i
                                                                class="material-icons-two-tone f-16 me-1">apartment</i>
                                                            Chief Invigilator</div>
                                                    </div>

                                                    <div class="mt-2">
                                                        {{-- @if ($candidate_attendance_data !== null)
                                                                <!-- Show Edit Button if candidate attendance data exists -->
                                                                <a href="#" data-pc-animate="just-me"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#attendanceCandidateeditModal"
                                                                    class="me-2 btn btn-sm btn-light-warning">
                                                                    <i class="feather icon-edit mx-1"></i>Edit
                                                                </a>
                                                            @else --}}
                                                        <!-- Show Add Button if no candidate attendance data exists -->
                                                        <a href="#" data-pc-animate="just-me"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#attendanceCandidateModal"
                                                            class="me-2 btn btn-sm btn-light-info">
                                                            <i class="feather icon-plus mx-1"></i>Add
                                                        </a>
                                                        {{-- @endif --}}

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @php
                                    $is_candidates_added =
                                        $additionalCandidates !== null &&
                                        !empty((array) $additionalCandidates->additional_candidates);

                                    // Set dynamic badge text and color
                                    $taskStatus = $is_candidates_added ? 'Added' : 'Pending';
                                    $badgeClass = $is_candidates_added ? 'bg-light-secondary' : 'bg-danger';
                                @endphp
                                <li class="task-list-item">
                                    <i
                                        class="task-icon {{ $is_candidates_added ? 'feather icon-check f-w-600 bg-success' : 'bg-danger' }}"></i>
                                    <div class="card ticket-card open-ticket">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-auto mb-3 mb-sm-0">
                                                    <div class="d-sm-inline-block d-flex align-items-center">
                                                        <img class="media-object wid-60 img-radius"
                                                            src="{{ asset('storage/' . current_user()->profile_image) }}"
                                                            alt="Generic placeholder image " />
                                                        <div class="ms-3 ms-sm-0 mb-3 mb-sm-0">
                                                            <ul
                                                                class="text-sm-center list-unstyled mt-2 mb-0 d-inline-block">
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="popup-trigger">
                                                        <div class="h5 font-weight-bold">Additional Candidiate<small
                                                                class="badge {{ $badgeClass }} ms-2">{{ $taskStatus }}</small>
                                                        </div>
                                                        <div class="help-sm-hidden">
                                                            <ul class="list-unstyled mt-2 mb-0 text-muted">
                                                                <li class="d-sm-inline-block d-block mt-1"><img
                                                                        src="../assets/images/user/avatar-5.jpg"
                                                                        alt=""
                                                                        class="wid-20 rounded me-2 img-fluid" />Done by
                                                                    <b>{{ $is_candidates_added ? Str::limit(current_user()->display_name, 15, '...') : 'Unknown' }}</b>
                                                                </li>
                                                                <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>
                                                                    {{ $is_candidates_added ? \Carbon\Carbon::parse(time: json_decode($additionalCandidates->additional_candidates, true)['timestamp'])->format('d-m-Y h:i A') : '' }}
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="h5 mt-3"><i
                                                                class="material-icons-two-tone f-16 me-1">apartment</i>
                                                            Chief Invigilator</div>
                                                    </div>
                                                    <div class="mt-2">
                                                        <a href="#" data-pc-animate="just-me"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#additionalCandidateViewModal"
                                                            class="me-2 btn btn-sm btn-light-primary"><i
                                                                class="feather icon-eye mx-1"></i>View</a>
                                                        <a href="#" data-pc-animate="just-me"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#additionalCandidateModal"
                                                            class="me-2 btn btn-sm btn-light-info"><i
                                                                class="feather icon-plus mx-1"></i>Add</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                {{-- <li class="task-list-item">
                                    <i class="task-icon bg-danger"></i>
                                    <div class="card ticket-card open-ticket">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-auto mb-3 mb-sm-0">
                                                    <div class="d-sm-inline-block d-flex align-items-center">
                                                        <img class="media-object wid-60 img-radius"
                                                            src="{{ asset('storage/' . current_user()->profile_image) }}"
                                                            alt="Generic placeholder image " />
                                                        <div class="ms-3 ms-sm-0 mb-3 mb-sm-0">
                                                            <ul
                                                                class="text-sm-center list-unstyled mt-2 mb-0 d-inline-block">

                                                                {{-- <li class="list-unstyled-item"><a href="#"
                                                                        class="link-danger"><i class="fas fa-heart"></i>
                                                                        3</a></li> -
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="popup-trigger">
                                                        <div class="h5 font-weight-bold">Candidiate Subject Change<small
                                                                class="badge bg-light-secondary ms-2">changed</small>
                                                        </div>
                                                        <div class="help-sm-hidden">
                                                            <ul class="list-unstyled mt-2 mb-0 text-muted">
                                                                {{-- <li class="d-sm-inline-block d-block mt-1"><img
                                                                        src="../assets/images/admin/p1.jpg" alt=""
                                                                        class="wid-20 rounded me-2 img-fluid" />Piaf able
                                                                </li> --}}
                                {{-- <li class="d-sm-inline-block d-block mt-1"><img
                                                                        src="../assets/images/user/avatar-5.jpg"
                                                                        alt=""
                                                                        class="wid-20 rounded me-2 img-fluid" />Done by
                                                                    <b>Chezhiyan</b>
                                                                </li>
                                                                <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>
                                                                    28-07-2024 09:30 AM</li> --}}
                                {{-- <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">chat</i>9
                                                                </li> 
                                                            </ul>
                                                        </div>
                                                        <div class="h5 mt-3"><i
                                                                class="material-icons-two-tone f-16 me-1">apartment</i>
                                                            Chief Invigilator</div>
                                                    </div>
                                                    <div class="mt-2">
                                                        <a href="#" class="me-2 btn btn-sm btn-light-primary"><i
                                                                class="feather icon-eye mx-1"></i>View</a>
                                                        <a href="#" class="me-2 btn btn-sm btn-light-info"><i
                                                                class="feather icon-plus mx-1"></i>Add</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li> --}}
                                @php
                                    // Decode the JSON string into a PHP associative array
                                    $timingLog = $qpboxTimeLog ? $qpboxTimeLog['qp_timing_log'] : null;

                                    // Check if 'isBoxDistributionTimeSet' exists and is not empty
                                    $isBoxDistributionTimeSet =
                                        isset($timingLog['qp_box_distribution_time']) &&
                                        !empty($timingLog['qp_box_distribution_time']);

                                    // Optional: Set a status or badge class based on whether the time is set
                                    $taskStatus = $isBoxDistributionTimeSet ? 'Time Set' : 'Pending';
                                    $badgeClass = $isBoxDistributionTimeSet ? 'bg-light-secondary' : 'bg-danger';
                                @endphp
                                <li class="task-list-item">
                                    <i
                                        class="task-icon {{ $isBoxDistributionTimeSet ? 'feather icon-check f-w-600 bg-success' : 'bg-danger' }}"></i>
                                    <div class="card ticket-card open-ticket">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-auto mb-3 mb-sm-0">
                                                    <div class="d-sm-inline-block d-flex align-items-center">
                                                        <img class="media-object wid-60 img-radius"
                                                            src="{{ asset('storage/' . current_user()->profile_image) }}"
                                                            alt="Generic placeholder image " />
                                                        <div class="ms-3 ms-sm-0 mb-3 mb-sm-0">
                                                            <ul
                                                                class="text-sm-center list-unstyled mt-2 mb-0 d-inline-block">
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="popup-trigger">
                                                        <div class="h5 font-weight-bold">Q-paper distribution time <small
                                                                class="badge {{ $badgeClass }} ms-2">{{ $taskStatus }}</small>
                                                        </div>
                                                        <div class="help-sm-hidden">
                                                            <ul class="list-unstyled mt-2 mb-0 text-muted">
                                                                <li class="d-sm-inline-block d-block mt-1"><img
                                                                        src="../assets/images/user/avatar-5.jpg"
                                                                        alt=""
                                                                        class="wid-20 rounded me-2 img-fluid" />Done by
                                                                    <b>{{ $isBoxDistributionTimeSet ? Str::limit(current_user()->display_name, 15, '...') : 'Unknown' }}</b>
                                                                </li>
                                                                <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>
                                                                    {{ $isBoxDistributionTimeSet ? \Carbon\Carbon::parse(time: $timingLog['qp_box_distribution_time'])->format('d-m-Y h:i A') : '' }}
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="h5 mt-3"><i
                                                                class="material-icons-two-tone f-16 me-1">apartment</i>
                                                            Chief Invigilator</div>
                                                    </div>
                                                    <div class="mt-2">
                                                        {{-- <a href="#" class="me-2 btn btn-sm btn-light-primary"><i
                                                                class="feather icon-eye mx-1"></i>View</a> --}}
                                                        <a href="#" data-pc-animate="just-me"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#qpaperdistributiontime"
                                                            class="me-2 btn btn-sm btn-light-info"><i
                                                                class="feather icon-plus mx-1"></i>Set Current Time</a>
                                                        {{-- <a href="#" class="me-2 btn btn-sm btn-light-info"><i
                                                                class="feather icon-plus mx-1"></i>Add</a> --}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @php
                                    // Determine if any replacement record exists
                                    $hasReplacement = $paperReplacements->isNotEmpty();
                                    // Since the collection is ordered descending, the first record is the latest
                                    $latestReplacement = $hasReplacement ? $paperReplacements->first() : null;
                                    // Set the status and badge based on the record's existence
$replacementStatus = $hasReplacement ? 'Replaced' : 'Pending';
$replacementBadgeClass = $hasReplacement ? 'bg-light-secondary' : 'bg-danger';

// Format the updated_at timestamp if a replacement exists
$replacementTime = $hasReplacement
    ? \Carbon\Carbon::parse($latestReplacement->updated_at)->format('d-m-Y h:i A')
    : '';
                                @endphp
                                <li class="task-list-item">
                                    <i
                                        class="task-icon {{ $hasReplacement ? 'feather icon-check f-w-600 bg-success' : 'bg-danger' }}"></i>
                                    <div class="card ticket-card open-ticket">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-auto mb-3 mb-sm-0">
                                                    <div class="d-sm-inline-block d-flex align-items-center">
                                                        <img class="media-object wid-60 img-radius"
                                                            src="{{ asset('storage/' . current_user()->profile_image) }}"
                                                            alt="Generic placeholder image " />
                                                        <div class="ms-3 ms-sm-0 mb-3 mb-sm-0">
                                                            <ul
                                                                class="text-sm-center list-unstyled mt-2 mb-0 d-inline-block">
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="popup-trigger">
                                                        <div class="h5 font-weight-bold">Replacemnt of Q-paper or OMR Sheet
                                                            <small class="badge {{ $replacementBadgeClass }} ms-2">
                                                                {{ $replacementStatus }}
                                                            </small>
                                                        </div>
                                                        <div class="help-sm-hidden">
                                                            <ul class="list-unstyled mt-2 mb-0 text-muted">

                                                                <li class="d-sm-inline-block d-block mt-1"><img
                                                                        src="../assets/images/user/avatar-5.jpg"
                                                                        alt=""
                                                                        class="wid-20 rounded me-2 img-fluid" />Done by
                                                                    <b>
                                                                        {{ $hasReplacement ? Str::limit(current_user()->display_name, 15, '...') : 'Unknown' }}
                                                                    </b>
                                                                </li>
                                                                <li class="d-sm-inline-block d-block mt-1">
                                                                    <i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">
                                                                        calendar_today
                                                                    </i>
                                                                    {{ $replacementTime }}
                                                                </li>

                                                            </ul>
                                                        </div>
                                                        <div class="h5 mt-3"><i
                                                                class="material-icons-two-tone f-16 me-1">apartment</i>
                                                            Chief Invigilator</div>
                                                    </div>
                                                    <div class="mt-2">
                                                        @if ($hasReplacement)
                                                            <a href="#" data-pc-animate="just-me"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#viewReplacementModal"
                                                                class="me-2 btn btn-sm btn-light-primary"><i
                                                                    class="feather icon-eye mx-1"></i>View</a>
                                                        @endif
                                                        <a href="#" data-pc-animate="just-me"
                                                            data-bs-toggle="modal" data-bs-target="#paperReplacementModal"
                                                            class="me-2 btn btn-sm btn-light-info"><i
                                                                class="feather icon-plus mx-1"></i>Add</a>
                                                        {{-- <a href="#" data-pc-animate="just-me"
                                                            data-bs-toggle="modal" data-bs-target="#editReplacementModal"
                                                            class="me-2 btn btn-sm btn-light-warning"><i
                                                                class="feather icon-edit mx-1"></i>Edit</a> --}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @php
                                    $is_remark_added =
                                        $candidateRemarks !== null &&
                                        !empty((array) $candidateRemarks->candidate_remarks);

                                    // Set dynamic badge text and color
                                    $taskStatus = $is_remark_added ? 'Added' : 'Pending';
                                    $badgeClass = $is_remark_added ? 'bg-light-secondary' : 'bg-danger';
                                @endphp
                                <li class="task-list-item">
                                    <i
                                        class="task-icon {{ $is_remark_added ? 'feather icon-check f-w-600 bg-success' : 'bg-danger' }}"></i>
                                    <div class="card ticket-card open-ticket">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-auto mb-3 mb-sm-0">
                                                    <div class="d-sm-inline-block d-flex align-items-center">
                                                        <img class="media-object wid-60 img-radius"
                                                            src="{{ asset('storage/' . current_user()->profile_image) }}"
                                                            alt="Generic placeholder image " />
                                                        <div class="ms-3 ms-sm-0 mb-3 mb-sm-0">
                                                            <ul
                                                                class="text-sm-center list-unstyled mt-2 mb-0 d-inline-block">
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="popup-trigger">
                                                        <div class="h5 font-weight-bold">Candidiate of Remarks  <small
                                                                class="badge {{ $badgeClass }} ms-2">{{ $taskStatus }}</small>
                                                        </div>
                                                        <div class="help-sm-hidden">
                                                            <ul class="list-unstyled mt-2 mb-0 text-muted">
                                                                <li class="d-sm-inline-block d-block mt-1"><img
                                                                        src="../assets/images/user/avatar-5.jpg"
                                                                        alt=""
                                                                        class="wid-20 rounded me-2 img-fluid" />Done by
                                                                    <b>{{ $is_remark_added ? Str::limit(current_user()->display_name, 15, '...') : 'Unknown' }}</b>
                                                                </li>
                                                                <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>
                                                                    {{ $is_remark_added ? \Carbon\Carbon::parse(time: $candidateRemarks->candidate_remarks['timestamp'])->format('d-m-Y h:i A') : '' }}
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="h5 mt-3"><i
                                                                class="material-icons-two-tone f-16 me-1">apartment</i>
                                                            Chief Invigilator</div>
                                                    </div>
                                                    <div class="mt-2">
                                                        @if ($is_remark_added)
                                                            <a href="#" data-pc-animate="just-me"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#candidateRemarksViewModal"
                                                                class="me-2 btn btn-sm btn-light-primary"><i
                                                                    class="feather icon-eye mx-1"></i>View</a>
                                                        @endif
                                                        <a href="#" data-pc-animate="just-me"
                                                            data-bs-toggle="modal" data-bs-target="#candidateRemarksModal"
                                                            class="me-2 btn btn-sm btn-light-info"><i
                                                                class="feather icon-plus mx-1"></i>Add</a>
                                                        @if ($is_remark_added)
                                                            <a href="#" data-pc-animate="just-me"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#candidateRemarkseditModal"
                                                                class="me-2 btn btn-sm btn-light-warning"><i
                                                                    class="feather icon-edit mx-1"></i>Edit</a>
                                                        @endif

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @php
                                    $is_videography_answered =
                                        $videographyAnswer !== null &&
                                        !empty((array) $videographyAnswer->videography_answer);
                                    // Set dynamic badge text and color
                                    $taskStatus = $is_videography_answered ? 'Verified' : 'Pending';
                                    $badgeClass = $is_videography_answered ? 'bg-light-secondary' : 'bg-danger';
                                @endphp
                                <li class="task-list-item">
                                    <i
                                        class="task-icon {{ $is_videography_answered ? 'feather icon-check f-w-600 bg-success' : 'bg-danger' }}"></i>
                                    <div class="card ticket-card open-ticket">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-auto mb-3 mb-sm-0">
                                                    <div class="d-sm-inline-block d-flex align-items-center">
                                                        <img class="media-object wid-60 img-radius"
                                                            src="{{ asset('storage/' . current_user()->profile_image) }}"
                                                            alt="Generic placeholder image " />
                                                        <div class="ms-3 ms-sm-0 mb-3 mb-sm-0">
                                                            <ul
                                                                class="text-sm-center list-unstyled mt-2 mb-0 d-inline-block">
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="popup-trigger">
                                                        <div class="h5 font-weight-bold">Counting / Packaging
                                                            Videography
                                                            <small
                                                                class="badge {{ $badgeClass }} ms-2">{{ $taskStatus }}</small>
                                                        </div>
                                                        <div class="help-sm-hidden">
                                                            <ul class="list-unstyled mt-2 mb-0 text-muted">
                                                                <li class="d-sm-inline-block d-block mt-1"><img
                                                                        src="../assets/images/user/avatar-5.jpg"
                                                                        alt=""
                                                                        class="wid-20 rounded me-2 img-fluid" />Done by
                                                                    <b>{{ $is_videography_answered ? Str::limit(current_user()->display_name, 15, '...') : 'Unknown' }}</b>
                                                                </li>
                                                                <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>
                                                                    {{ $is_videography_answered ? \Carbon\Carbon::parse($videographyAnswer->videography_answer['timestamp'])->format('d-m-Y h:i A') : '' }}
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="h5 mt-3"><i
                                                                class="material-icons-two-tone f-16 me-1">apartment</i>
                                                            Chief Invigilator</div>
                                                    </div>
                                                    <div class="mt-2">
                                                        <a href="#" data-pc-animate="just-me"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#countingpackagingvideography"
                                                            class="me-2 btn btn-sm btn-light-primary"><i
                                                                class="feather icon-info mx-1"></i>verify</a>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                {{-- <li class="task-list-item">
                                    <i class="task-icon bg-danger"></i>
                                    <div class="card ticket-card open-ticket">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-auto mb-3 mb-sm-0">
                                                    <div class="d-sm-inline-block d-flex align-items-center">
                                                        <img class="media-object wid-60 img-radius"
                                                            src="{{ asset('storage/' . current_user()->profile_image) }}"
                                                            alt="Generic placeholder image " />
                                                        <div class="ms-3 ms-sm-0 mb-3 mb-sm-0">
                                                            <ul
                                                                class="text-sm-center list-unstyled mt-2 mb-0 d-inline-block">

                                                                 <li class="list-unstyled-item"><a href="#"
                                                                        class="link-danger"><i class="fas fa-heart"></i>
                                                                        3</a></li> 
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="popup-trigger">
                                                        <div class="h5 font-weight-bold">Exam Rooms / Seat Arrangment
                                                            Videography <small
                                                                class="badge bg-light-secondary ms-2">videographed</small>
                                                        </div>
                                                        <div class="help-sm-hidden">
                                                            <ul class="list-unstyled mt-2 mb-0 text-muted">
                                                                 <li class="d-sm-inline-block d-block mt-1"><img
                                                                        src="../assets/images/admin/p1.jpg" alt=""
                                                                        class="wid-20 rounded me-2 img-fluid" />Piaf able
                                                                </li> -
                                                                <li class="d-sm-inline-block d-block mt-1"><img
                                                                        src="../assets/images/user/avatar-5.jpg"
                                                                        alt=""
                                                                        class="wid-20 rounded me-2 img-fluid" />Done by
                                                                    <b>Chezhiyan</b>
                                                                </li>
                                                                <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>
                                                                    28-07-2024 09:30 AM</li>
                                                                <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">chat</i>9
                                                                </li> 
                                                            </ul>
                                                        </div>
                                                        <div class="h5 mt-3"><i
                                                                class="material-icons-two-tone f-16 me-1">apartment</i>
                                                            Chief Invigilator</div>
                                                    </div>
                                                    <div class="mt-2">
                                                        <a href="#" class="me-2 btn btn-sm btn-light-primary"><i
                                                                class="feather icon-eye mx-1"></i>View</a>
                                                        <a href="#" class="me-2 btn btn-sm btn-light-info"><i
                                                                class="feather icon-info mx-1"></i>Verify</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li> --}}
                                @php
                                    $is_omr_remark_added =
                                        $omrRemarks !== null && !empty((array) $omrRemarks->omr_remarks);

                                    // Set dynamic badge text and color
                                    $taskStatus = $is_omr_remark_added ? 'Added' : 'Pending';
                                    $badgeClass = $is_omr_remark_added ? 'bg-light-secondary' : 'bg-danger';
                                @endphp
                                <li class="task-list-item">
                                    <i
                                        class="task-icon {{ $is_omr_remark_added ? 'feather icon-check f-w-600 bg-success' : 'bg-danger' }}"></i>
                                    <div class="card ticket-card open-ticket">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-auto mb-3 mb-sm-0">
                                                    <div class="d-sm-inline-block d-flex align-items-center">
                                                        <img class="media-object wid-60 img-radius"
                                                            src="{{ asset('storage/' . current_user()->profile_image) }}"
                                                            alt="Generic placeholder image " />
                                                        <div class="ms-3 ms-sm-0 mb-3 mb-sm-0">
                                                            <ul
                                                                class="text-sm-center list-unstyled mt-2 mb-0 d-inline-block">
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="popup-trigger">
                                                        <div class="h5 font-weight-bold">OMR Remarks <small
                                                                class="badge {{ $badgeClass }} ms-2">{{ $taskStatus }}</small>
                                                        </div>
                                                        <div class="help-sm-hidden">
                                                            <ul class="list-unstyled mt-2 mb-0 text-muted">
                                                                <li class="d-sm-inline-block d-block mt-1"><img
                                                                        src="../assets/images/user/avatar-5.jpg"
                                                                        alt=""
                                                                        class="wid-20 rounded me-2 img-fluid" />Done by
                                                                    <b>{{ $is_omr_remark_added ? Str::limit(current_user()->display_name, 15, '...') : 'Unknown' }}</b>
                                                                </li>
                                                                <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>
                                                                    {{ $is_omr_remark_added ? \Carbon\Carbon::parse(time: $omrRemarks->omr_remarks['timestamp'])->format('d-m-Y h:i A') : '' }}
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="h5 mt-3"><i
                                                                class="material-icons-two-tone f-16 me-1">apartment</i>
                                                            Chief Invigilator</div>
                                                    </div>
                                                    <div class="mt-2">
                                                        @if ($is_omr_remark_added)
                                                            <a href="#" data-pc-animate="just-me"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#omrRemarksViewModal"
                                                                class="me-2 btn btn-sm btn-light-primary"><i
                                                                    class="feather icon-eye mx-1"></i>View</a>
                                                        @endif
                                                        <a href="#" data-pc-animate="just-me"
                                                            data-bs-toggle="modal" data-bs-target="#omrRemarksInputModal"
                                                            class="me-2 btn btn-sm btn-light-info"><i
                                                                class="feather icon-plus mx-1"></i>Add</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @php
                                    $is_bundle_packed = $lastScannedBundle !== null;

                                    // Set dynamic badge text and color
                                    $taskStatus = $is_bundle_packed ? 'Packed' : 'Pending';
                                    $badgeClass = $is_bundle_packed ? 'bg-light-secondary' : 'bg-danger';
                                @endphp
                                <li class="task-list-item">
                                    <i
                                        class="task-icon {{ $is_bundle_packed ? 'feather icon-check f-w-600 bg-success' : 'bg-danger' }}"></i>
                                    <div class="card ticket-card open-ticket">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-auto mb-3 mb-sm-0">
                                                    <div class="d-sm-inline-block d-flex align-items-center">
                                                        <img class="media-object wid-60 img-radius"
                                                            src="{{ asset('storage/' . current_user()->profile_image) }}"
                                                            alt="Generic placeholder image " />
                                                        <div class="ms-3 ms-sm-0 mb-3 mb-sm-0">
                                                            <ul
                                                                class="text-sm-center list-unstyled mt-2 mb-0 d-inline-block">
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="popup-trigger">
                                                        <div class="h5 font-weight-bold">Scan Bundle Packaging <small
                                                                class="badge {{ $badgeClass }} ms-2">{{ $taskStatus }}</small>
                                                        </div>
                                                        <div class="help-sm-hidden">
                                                            <ul class="list-unstyled mt-2 mb-0 text-muted">
                                                                <li class="d-sm-inline-block d-block mt-1"><img
                                                                        src="../assets/images/user/avatar-5.jpg"
                                                                        alt=""
                                                                        class="wid-20 rounded me-2 img-fluid" />Done by
                                                                    <b>{{ $is_bundle_packed ? Str::limit(current_user()->display_name, 15, '...') : 'Unknown' }}</b>
                                                                </li>
                                                                <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>
                                                                    {{ $is_bundle_packed ? \Carbon\Carbon::parse($lastScannedBundle->last_scanned_at)->format('d-m-Y h:i A') : '' }}
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="h5 mt-3"><i
                                                                class="material-icons-two-tone f-16 me-1">apartment</i>
                                                            Chief Invigilator</div>
                                                    </div>
                                                    <div class="mt-2">
                                                        {{-- <a href="#" class="me-2 btn btn-sm btn-light-primary"><i
                                                                class="feather icon-eye mx-1"></i>View</a> --}}
                                                        <a href="{{ route('ci.bundlepackaging.view', [
                                                            'examId' => $session->currentexam->exam_main_no,
                                                            'exam_date' => $session->exam_sess_date,
                                                            'exam_session' => $session->exam_sess_session,
                                                        ]) }}"
                                                            class="me-2 btn btn-sm btn-light-primary">
                                                            <i class="feather icon-aperture mx-1"></i>Scan
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @php
                                    $is_consolidate_answered =
                                        $consolidateAnswer !== null &&
                                        !empty((array) $consolidateAnswer->consolidate_answer);
                                    // Set dynamic badge text and color
                                    $taskStatus = $is_consolidate_answered ? 'Verified' : 'Pending';
                                    $badgeClass = $is_consolidate_answered ? 'bg-light-secondary' : 'bg-danger';
                                @endphp
                                <li class="task-list-item">
                                    <i
                                        class="task-icon {{ $is_consolidate_answered ? 'feather icon-check f-w-600 bg-success' : 'bg-danger' }}"></i>
                                    <div class="card ticket-card open-ticket">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-auto mb-3 mb-sm-0">
                                                    <div class="d-sm-inline-block d-flex align-items-center">
                                                        <img class="media-object wid-60 img-radius"
                                                            src="{{ asset('storage/' . current_user()->profile_image) }}"
                                                            alt="Generic placeholder image " />
                                                        <div class="ms-3 ms-sm-0 mb-3 mb-sm-0">
                                                            <ul
                                                                class="text-sm-center list-unstyled mt-2 mb-0 d-inline-block">
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="popup-trigger">
                                                        <div class="h5 font-weight-bold">Consolidate Certificate <small
                                                                class="badge {{ $badgeClass }} ms-2">
                                                                {{ $taskStatus }}
                                                            </small>
                                                            <div class="help-sm-hidden">
                                                                <ul class="list-unstyled mt-2 mb-0 text-muted">
                                                                    <li class="d-sm-inline-block d-block mt-1"><img
                                                                            src="../assets/images/user/avatar-5.jpg"
                                                                            alt=""
                                                                            class="wid-20 rounded me-2 img-fluid" />Done by
                                                                        <b>{{ $is_consolidate_answered ? Str::limit(current_user()->display_name, 15, '...') : 'Unknown' }}</b>
                                                                    </li>
                                                                    <li class="d-sm-inline-block d-block mt-1"><i
                                                                            class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>
                                                                        {{ $is_consolidate_answered ? \Carbon\Carbon::parse($consolidateAnswer->consolidate_answer['timestamp'])->format('d-m-Y h:i A') : '' }}
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            <div class="h5 mt-3"><i
                                                                    class="material-icons-two-tone f-16 me-1">apartment</i>
                                                                Chief Invigilator</div>
                                                        </div>
                                                        <div class="mt-2">
                                                            <a href="#" data-pc-animate="just-me"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#consolidatecertificate"
                                                                class="me-2 btn btn-sm btn-light-primary"><i
                                                                    class="feather icon-info mx-1"></i>verify</a>
                                                            @if ($is_consolidate_answered)
                                                                <a href="{{ route('download.report', ['examId' => $session->currentexam->exam_main_no, 'exam_date' => $session->exam_sess_date, 'exam_session' => $session->exam_sess_session]) }}"
                                                                    class="me-2 btn btn-sm btn-light-info">
                                                                    <i class="feather icon-download mx-1"></i>Download
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                </li>
                            </ul>
                            {{-- <div class="text-end">
                      <a href="#!" class="b-b-primary text-primary">View Friend List</a>
                    </div> --}}
                        </div>
                    </div>
                </div>
                {{-- @include('modals.preliminary-checklist') --}}
                @include('modals.session-checklist')
                @include('modals.invigilator-select')
                @include('modals.invigilator-allotment')
                @include('modals.scribe-select')
                @include('modals.scribe-allotment')
                @include('modals.CIAssistant-allotment')
                @include('modals.CIAssistant-select')
                @include('modals.qpbox-opentime')
                @include('modals.additional-candidate')
                @include('modals.additional-candidate-view')
                @include('modals.attendance-candidate')
                @include('modals.attendance-candidate-edit')
                @include('modals.qpaper-distribution-time')
                @include('modals.counting-packaging-videography')
                @include('modals.consolidate-certificate')
                @include('modals.qp-ans-replacement')
                @include('modals.qp-ans-replacement-view')
                @include('modals.qp-ans-replacement-edit')
                @include('modals.candidate-remarks')
                @include('modals.candidate-remarks-view')
                @include('modals.candidate-remarks-edit')
                @include('modals.omr-remarks')
                @include('modals.omr-remarks-view')
                @include('modals.emergency-alarm-notification')
                @include('modals.adequacy-check-notification')
                {{-- @include('modals.utilization-certificate') --}}
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>
    @include('partials.footer')

    @push('scripts')
        <!-- [Page Specific JS] start -->
        <script src="{{ asset('storage/assets/js/plugins/prism.js') }}"></script>
        <script src="{{ asset('storage/assets/js/plugins/quill.min.js') }}"></script>
        <script src="{{ asset('storage/assets/js/plugins/choices.min.js') }}"></script>
        <script>
            // Listen for the 'show.bs.modal' event on any modal
            document.addEventListener('show.bs.modal', function(event) {
                var modal = event.target; // Get the modal being triggered
                var button = event.relatedTarget; // Button that triggered the modal
                var recipient = button.getAttribute('data-pc-animate'); // Get data attribute for animation type

                // Update the modal title and apply animation class
                var modalTitle = modal.querySelector('.modal-title');
                // modalTitle.textContent = 'Animate Modal: ' + recipient;
                modal.classList.add('anim-' + recipient);

                // Optionally, apply animation to the body for specific cases
                if (recipient == 'let-me-in' || recipient == 'make-way' || recipient == 'slip-from-top') {
                    document.body.classList.add('anim-' + recipient);
                }
            });

            // Listen for the 'hidden.bs.modal' event on any modal
            document.addEventListener('hidden.bs.modal', function(event) {
                var modal = event.target; // Get the modal being hidden
                removeClassByPrefix(modal, 'anim-');
                removeClassByPrefix(document.body, 'anim-');
            });

            // Helper function to remove classes by prefix
            function removeClassByPrefix(node, prefix) {
                var classesToRemove = Array.from(node.classList).filter(function(c) {
                    return c.startsWith(prefix);
                });
                classesToRemove.forEach(function(c) {
                    node.classList.remove(c);
                });
            }
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Select all multi-select dropdowns
                const multiSelects = document.querySelectorAll('select[multiple]');

                // Initialize Choices.js for each dropdown
                multiSelects.forEach(function(select) {
                    new Choices(select, {
                        removeItemButton: true, // Show remove button for each selected item
                        searchEnabled: true, // Enable search functionality
                        placeholder: true, // Show placeholder when no options are selected
                        placeholderValue: 'Select options', // Placeholder text
                        itemSelectText: 'Press to select', // Text shown when selecting items
                        delimiter: ',', // Delimiter for selected items in the input value
                        shouldSort: false // Disable sorting of options
                    });
                });
            });
        </script>

        <script>
            $('#qpboxOpenTimeModal').on('shown.bs.modal', function() {
                // Get the current time
                const currentTime = new Date().toLocaleTimeString(); // Format as HH:MM:SS

                // Update the time display inside the modal
                document.getElementById('timeDisplay').textContent = currentTime;
            });
            $('#qpaperdistributiontime').on('shown.bs.modal', function() {
                // Get the current time
                const currentTime = new Date().toLocaleTimeString(); // Format as HH:MM:SS

                // Update the time display inside the modal
                document.getElementById('timeDisplayss').textContent = currentTime;
            });
        </script>


        <!-- [Page Specific JS] end -->
    @endpush

    @include('partials.theme')

@endsection
