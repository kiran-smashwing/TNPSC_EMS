@extends('layouts.app')
@section('title', ' Dashboard')
@push('styles')
    <link href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/styles/monokai-sublime.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('storage/assets/css/plugins/quill.core.css') }}" />
    <link rel="stylesheet" href="{{ asset('storage/assets/css/plugins/quill.snow.css') }}" />
    <link rel="stylesheet" href="{{ asset('storage/assets/css/plugins/prism-coy.css') }}" />
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
                                    <h5><span
                                            class="text-primary">{{ $session->currentexam->exam_main_notification }}</span>
                                        - {{ $session->currentexam->exam_main_name }} -
                                        {{ $session->currentexam->exam_main_postname }}
                                        - <span class="text-warning"> {{ $session->currentexam->exam_main_startdate }}
                                        </span>
                                    </h5>
                                    <div class="btn-group btn-group-sm help-filter" role="group"
                                        aria-label="button groups sm">
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
                                <li class="task-list-item">
                                    <i class="task-icon bg-danger"></i>
                                    <div class="card ticket-card open-ticket">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-auto mb-3 mb-sm-0">
                                                    <div class="d-sm-inline-block d-flex align-items-center">
                                                        <img class="media-object wid-60 img-radius"
                                                            src="{{ asset('storage/assets/images/user/avatar-8.jpg') }}"
                                                            alt="Generic placeholder image " />
                                                        <div class="ms-3 ms-sm-0 mb-3 mb-sm-0">
                                                            <ul
                                                                class="text-sm-center list-unstyled mt-2 mb-0 d-inline-block">
                                                                <li class="list-unstyled-item"><a href="#"
                                                                        class="link-secondary">4 - QP </a></li>
                                                                <li class="list-unstyled-item"><a href="#"
                                                                        class="link-secondary">1 - OMR </a></li>
                                                                {{-- <li class="list-unstyled-item"><a href="#"
                                                                        class="link-danger"><i class="fas fa-heart"></i>
                                                                        3</a></li> --}}
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="popup-trigger">
                                                        <div class="h5 font-weight-bold">Receive Materials From Mobile
                                                            Team<small
                                                                class="badge bg-light-secondary ms-2">received</small>
                                                        </div>
                                                        <div class="help-sm-hidden">
                                                            <ul class="list-unstyled mt-2 mb-0 text-muted">
                                                                {{-- <li class="d-sm-inline-block d-block mt-1"><img
                                                                        src="../assets/images/admin/p1.jpg" alt=""
                                                                        class="wid-20 rounded me-2 img-fluid" />Piaf able
                                                                </li> --}}
                                                                <li class="d-sm-inline-block d-block mt-1"><img
                                                                        src="../assets/images/user/avatar-5.jpg"
                                                                        alt=""
                                                                        class="wid-20 rounded me-2 img-fluid" />Done by
                                                                    <b>Chezhiyan</b>
                                                                </li>
                                                                <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>
                                                                    28-07-2024 09:30 AM</li>
                                                                {{-- <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">chat</i>9
                                                                </li> --}}
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
                                                            ]) }}"
                                                            class="me-2 btn btn-sm btn-light-primary"><i
                                                                class="feather icon-aperture mx-1"></i>Scan</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="task-list-item">
                                    <i class="task-icon bg-danger"></i>
                                    <div class="card ticket-card open-ticket">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-auto mb-3 mb-sm-0">
                                                    <div class="d-sm-inline-block d-flex align-items-center">
                                                        <img class="media-object wid-60 img-radius"
                                                            src="{{ asset('storage/assets/images/user/avatar-8.jpg') }}"
                                                            alt="Generic placeholder image " />
                                                        <div class="ms-3 ms-sm-0 mb-3 mb-sm-0">
                                                            <ul
                                                                class="text-sm-center list-unstyled mt-2 mb-0 d-inline-block">

                                                                {{-- <li class="list-unstyled-item"><a href="#"
                                                                        class="link-danger"><i class="fas fa-heart"></i>
                                                                        3</a></li> --}}
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="popup-trigger">
                                                        <div class="h5 font-weight-bold">Session Check<small
                                                                class="badge bg-light-secondary ms-2">checked</small>
                                                        </div>
                                                        <div class="help-sm-hidden">
                                                            <ul class="list-unstyled mt-2 mb-0 text-muted">
                                                                {{-- <li class="d-sm-inline-block d-block mt-1"><img
                                                                        src="../assets/images/admin/p1.jpg" alt=""
                                                                        class="wid-20 rounded me-2 img-fluid" />Piaf able
                                                                </li> --}}
                                                                <li class="d-sm-inline-block d-block mt-1"><img
                                                                        src="../assets/images/user/avatar-5.jpg"
                                                                        alt=""
                                                                        class="wid-20 rounded me-2 img-fluid" />Done by
                                                                    <b>Chezhiyan</b>
                                                                </li>
                                                                <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>
                                                                    28-07-2024 09:30 AM</li>
                                                                {{-- <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">chat</i>9
                                                                </li> --}}
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
                                <li class="task-list-item">
                                    <i class="task-icon bg-danger"></i>
                                    <div class="card ticket-card open-ticket">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-auto mb-3 mb-sm-0">
                                                    <div class="d-sm-inline-block d-flex align-items-center">
                                                        <img class="media-object wid-60 img-radius"
                                                            src="{{ asset('storage/assets/images/user/avatar-8.jpg') }}"
                                                            alt="Generic placeholder image " />
                                                        <div class="ms-3 ms-sm-0 mb-3 mb-sm-0">
                                                            <ul
                                                                class="text-sm-center list-unstyled mt-2 mb-0 d-inline-block">

                                                                {{-- <li class="list-unstyled-item"><a href="#"
                                                                        class="link-danger"><i class="fas fa-heart"></i>
                                                                        3</a></li> --}}
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="popup-trigger">
                                                        <div class="h5 font-weight-bold">Invigilator Attendence /
                                                            Allotment<small
                                                                class="badge bg-light-secondary ms-2">allotted</small>
                                                        </div>
                                                        <div class="help-sm-hidden">
                                                            <ul class="list-unstyled mt-2 mb-0 text-muted">
                                                                {{-- <li class="d-sm-inline-block d-block mt-1"><img
                                                                        src="../assets/images/admin/p1.jpg" alt=""
                                                                        class="wid-20 rounded me-2 img-fluid" />Piaf able
                                                                </li> --}}
                                                                <li class="d-sm-inline-block d-block mt-1"><img
                                                                        src="../assets/images/user/avatar-5.jpg"
                                                                        alt=""
                                                                        class="wid-20 rounded me-2 img-fluid" />Done by
                                                                    <b>Chezhiyan</b>
                                                                </li>
                                                                <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>
                                                                    28-07-2024 09:30 AM</li>
                                                                {{-- <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">chat</i>9
                                                                </li> --}}
                                                            </ul>
                                                        </div>
                                                        <div class="h5 mt-3"><i
                                                                class="material-icons-two-tone f-16 me-1">apartment</i>
                                                            Chief Invigilator</div>
                                                    </div>
                                                    <div class="mt-2">
                                                        <a href="#" id="viewBtn" data-pc-animate="just-me"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#invigilatorAllotmentModel"
                                                            class="me-2 btn btn-sm btn-light-primary"><i
                                                                class="feather icon-eye mx-1"></i>View</a>
                                                        @if ($invigilators_type)
                                                            <a href="#"data-pc-animate="just-me"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#invigilatorEditModal"
                                                                class="btn btn-sm btn-light-info"><i
                                                                    class="ti ti-edit f-20"></i>Edit</a>
                                                        @else
                                                            <a href="#" data-pc-animate="just-me"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#invigilatorSelectModal"
                                                                class="me-2 btn btn-sm btn-light-info"><i
                                                                    class="feather icon-plus mx-1"></i>Select</a>
                                                        @endif

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="task-list-item">
                                    <i class="task-icon bg-danger"></i>
                                    <div class="card ticket-card open-ticket">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-auto mb-3 mb-sm-0">
                                                    <div class="d-sm-inline-block d-flex align-items-center">
                                                        <img class="media-object wid-60 img-radius"
                                                            src="{{ asset('storage/assets/images/user/avatar-8.jpg') }}"
                                                            alt="Generic placeholder image " />
                                                        <div class="ms-3 ms-sm-0 mb-3 mb-sm-0">
                                                            <ul
                                                                class="text-sm-center list-unstyled mt-2 mb-0 d-inline-block">

                                                                {{-- <li class="list-unstyled-item"><a href="#"
                                                                        class="link-danger"><i class="fas fa-heart"></i>
                                                                        3</a></li> --}}
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="popup-trigger">
                                                        <div class="h5 font-weight-bold">Scribe Attendence <small
                                                                class="badge bg-light-secondary ms-2">attended</small>
                                                        </div>
                                                        <div class="help-sm-hidden">
                                                            <ul class="list-unstyled mt-2 mb-0 text-muted">
                                                                {{-- <li class="d-sm-inline-block d-block mt-1"><img
                                                                        src="../assets/images/admin/p1.jpg" alt=""
                                                                        class="wid-20 rounded me-2 img-fluid" />Piaf able
                                                                </li> --}}
                                                                <li class="d-sm-inline-block d-block mt-1"><img
                                                                        src="../assets/images/user/avatar-5.jpg"
                                                                        alt=""
                                                                        class="wid-20 rounded me-2 img-fluid" />Done by
                                                                    <b>Chezhiyan</b>
                                                                </li>
                                                                <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>
                                                                    28-07-2024 09:30 AM</li>
                                                                {{-- <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">chat</i>9
                                                                </li> --}}
                                                            </ul>
                                                        </div>
                                                        <div class="h5 mt-3"><i
                                                                class="material-icons-two-tone f-16 me-1">apartment</i>
                                                            Chief Invigilator</div>
                                                    </div>
                                                    <div class="mt-2">
                                                        <a href="#" data-pc-animate="just-me"
                                                            data-bs-toggle="modal" data-bs-target="#scribeAllotmentModal"
                                                            class="me-2 btn btn-sm btn-light-primary"><i
                                                                class="feather icon-eye mx-1"></i>View</a>
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
                                <li class="task-list-item">
                                    <i class="task-icon bg-danger"></i>
                                    <div class="card ticket-card open-ticket">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-auto mb-3 mb-sm-0">
                                                    <div class="d-sm-inline-block d-flex align-items-center">
                                                        <img class="media-object wid-60 img-radius"
                                                            src="{{ asset('storage/assets/images/user/avatar-8.jpg') }}"
                                                            alt="Generic placeholder image " />
                                                        <div class="ms-3 ms-sm-0 mb-3 mb-sm-0">
                                                            <ul
                                                                class="text-sm-center list-unstyled mt-2 mb-0 d-inline-block">

                                                                {{-- <li class="list-unstyled-item"><a href="#"
                                                                        class="link-danger"><i class="fas fa-heart"></i>
                                                                        3</a></li> --}}
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="popup-trigger">
                                                        <div class="h5 font-weight-bold">CI Assistants Attendence <small
                                                                class="badge bg-light-secondary ms-2">attended</small>
                                                        </div>
                                                        <div class="help-sm-hidden">
                                                            <ul class="list-unstyled mt-2 mb-0 text-muted">
                                                                {{-- <li class="d-sm-inline-block d-block mt-1"><img
                                                                        src="../assets/images/admin/p1.jpg" alt=""
                                                                        class="wid-20 rounded me-2 img-fluid" />Piaf able
                                                                </li> --}}
                                                                <li class="d-sm-inline-block d-block mt-1"><img
                                                                        src="../assets/images/user/avatar-5.jpg"
                                                                        alt=""
                                                                        class="wid-20 rounded me-2 img-fluid" />Done by
                                                                    <b>Chezhiyan</b>
                                                                </li>
                                                                <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>
                                                                    28-07-2024 09:30 AM</li>
                                                                {{-- <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">chat</i>9
                                                                </li> --}}
                                                            </ul>
                                                        </div>
                                                        <div class="h5 mt-3"><i
                                                                class="material-icons-two-tone f-16 me-1">apartment</i>
                                                            Chief Invigilator</div>
                                                    </div>
                                                    <div class="mt-2">
                                                        <a href="#" data-pc-animate="just-me"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#ciAssistantAllotmentModal"
                                                            class="me-2 btn btn-sm btn-light-primary"><i
                                                                class="feather icon-eye mx-1"></i>View</a>
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
                                <li class="task-list-item">
                                    <i class="task-icon bg-danger"></i>
                                    <div class="card ticket-card open-ticket">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-auto mb-3 mb-sm-0">
                                                    <div class="d-sm-inline-block d-flex align-items-center">
                                                        <img class="media-object wid-60 img-radius"
                                                            src="{{ asset('storage/assets/images/user/avatar-8.jpg') }}"
                                                            alt="Generic placeholder image " />
                                                        <div class="ms-3 ms-sm-0 mb-3 mb-sm-0">
                                                            <ul
                                                                class="text-sm-center list-unstyled mt-2 mb-0 d-inline-block">

                                                                {{-- <li class="list-unstyled-item"><a href="#"
                                                                        class="link-danger"><i class="fas fa-heart"></i>
                                                                        3</a></li> --}}
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="popup-trigger">
                                                        <div class="h5 font-weight-bold">QP Box Open Time <small
                                                                class="badge bg-light-secondary ms-2">scanned</small>
                                                        </div>
                                                        <div class="help-sm-hidden">
                                                            <ul class="list-unstyled mt-2 mb-0 text-muted">
                                                                {{-- <li class="d-sm-inline-block d-block mt-1"><img
                                                                        src="../assets/images/admin/p1.jpg" alt=""
                                                                        class="wid-20 rounded me-2 img-fluid" />Piaf able
                                                                </li> --}}
                                                                <li class="d-sm-inline-block d-block mt-1"><img
                                                                        src="../assets/images/user/avatar-5.jpg"
                                                                        alt=""
                                                                        class="wid-20 rounded me-2 img-fluid" />Done by
                                                                    <b>Chezhiyan</b>
                                                                </li>
                                                                <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>
                                                                    28-07-2024 09:30 AM</li>
                                                                {{-- <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">chat</i>9
                                                                </li> --}}
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
                                <li class="task-list-item">
                                    <i class="task-icon bg-danger"></i>
                                    <div class="card ticket-card open-ticket">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-auto mb-3 mb-sm-0">
                                                    <div class="d-sm-inline-block d-flex align-items-center">
                                                        <img class="media-object wid-60 img-radius"
                                                            src="{{ asset('storage/assets/images/user/avatar-8.jpg') }}"
                                                            alt="Generic placeholder image " />
                                                        <div class="ms-3 ms-sm-0 mb-3 mb-sm-0">
                                                            <ul
                                                                class="text-sm-center list-unstyled mt-2 mb-0 d-inline-block">

                                                                {{-- <li class="list-unstyled-item"><a href="#"
                                                                        class="link-danger"><i class="fas fa-heart"></i>
                                                                        3</a></li> --}}
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="popup-trigger">
                                                        <div class="h5 font-weight-bold">Candidiate Attendance<small
                                                                class="badge bg-light-secondary ms-2">added</small>
                                                        </div>
                                                        <div class="help-sm-hidden">
                                                            <ul class="list-unstyled mt-2 mb-0 text-muted">
                                                                {{-- <li class="d-sm-inline-block d-block mt-1"><img
                                                                        src="../assets/images/admin/p1.jpg" alt=""
                                                                        class="wid-20 rounded me-2 img-fluid" />Piaf able
                                                                </li> --}}
                                                                <li class="d-sm-inline-block d-block mt-1"><img
                                                                        src="../assets/images/user/avatar-5.jpg"
                                                                        alt=""
                                                                        class="wid-20 rounded me-2 img-fluid" />Done by
                                                                    <b>Chezhiyan</b>
                                                                </li>
                                                                <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>
                                                                    28-07-2024 09:30 AM</li>
                                                                {{-- <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">chat</i>9
                                                                </li> --}}
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
                        <li class="task-list-item">
                            <i class="task-icon bg-danger"></i>
                            <div class="card ticket-card open-ticket">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-auto mb-3 mb-sm-0">
                                            <div class="d-sm-inline-block d-flex align-items-center">
                                                <img class="media-object wid-60 img-radius"
                                                    src="{{ asset('storage/assets/images/user/avatar-8.jpg') }}"
                                                    alt="Generic placeholder image " />
                                                <div class="ms-3 ms-sm-0 mb-3 mb-sm-0">
                                                    <ul class="text-sm-center list-unstyled mt-2 mb-0 d-inline-block">

                                                        {{-- <li class="list-unstyled-item"><a href="#"
                                                                        class="link-danger"><i class="fas fa-heart"></i>
                                                                        3</a></li> --}}
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="popup-trigger">
                                                <div class="h5 font-weight-bold">Additional Candidiate<small
                                                        class="badge bg-light-secondary ms-2">added</small>
                                                </div>
                                                <div class="help-sm-hidden">
                                                    <ul class="list-unstyled mt-2 mb-0 text-muted">
                                                        {{-- <li class="d-sm-inline-block d-block mt-1"><img
                                                                        src="../assets/images/admin/p1.jpg" alt=""
                                                                        class="wid-20 rounded me-2 img-fluid" />Piaf able
                                                                </li> --}}
                                                        <li class="d-sm-inline-block d-block mt-1"><img
                                                                src="../assets/images/user/avatar-5.jpg" alt=""
                                                                class="wid-20 rounded me-2 img-fluid" />Done by
                                                            <b>Chezhiyan</b>
                                                        </li>
                                                        <li class="d-sm-inline-block d-block mt-1"><i
                                                                class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>
                                                            28-07-2024 09:30 AM</li>
                                                        {{-- <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">chat</i>9
                                                                </li> --}}
                                                    </ul>
                                                </div>
                                                <div class="h5 mt-3"><i
                                                        class="material-icons-two-tone f-16 me-1">apartment</i>
                                                    Chief Invigilator</div>
                                            </div>
                                            <div class="mt-2">
                                                <a href="#" data-pc-animate="just-me" data-bs-toggle="modal"
                                                    data-bs-target="#additionalCandidateViewModal"
                                                    class="me-2 btn btn-sm btn-light-primary"><i
                                                        class="feather icon-eye mx-1"></i>View</a>
                                                <a href="#" data-pc-animate="just-me" data-bs-toggle="modal"
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
                                                            src="{{ asset('storage/assets/images/user/avatar-8.jpg') }}"
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
                        <li class="task-list-item">
                            <i class="task-icon bg-danger"></i>
                            <div class="card ticket-card open-ticket">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-auto mb-3 mb-sm-0">
                                            <div class="d-sm-inline-block d-flex align-items-center">
                                                <img class="media-object wid-60 img-radius"
                                                    src="{{ asset('storage/assets/images/user/avatar-8.jpg') }}"
                                                    alt="Generic placeholder image " />
                                                <div class="ms-3 ms-sm-0 mb-3 mb-sm-0">
                                                    <ul class="text-sm-center list-unstyled mt-2 mb-0 d-inline-block">

                                                        {{-- <li class="list-unstyled-item"><a href="#"
                                                                        class="link-danger"><i class="fas fa-heart"></i>
                                                                        3</a></li> --}}
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="popup-trigger">
                                                <div class="h5 font-weight-bold">Q-paper distribution time <small
                                                        class="badge bg-light-secondary ms-2">added</small>
                                                </div>
                                                <div class="help-sm-hidden">
                                                    <ul class="list-unstyled mt-2 mb-0 text-muted">
                                                        {{-- <li class="d-sm-inline-block d-block mt-1"><img
                                                                        src="../assets/images/admin/p1.jpg" alt=""
                                                                        class="wid-20 rounded me-2 img-fluid" />Piaf able
                                                                </li> --}}
                                                        <li class="d-sm-inline-block d-block mt-1"><img
                                                                src="../assets/images/user/avatar-5.jpg" alt=""
                                                                class="wid-20 rounded me-2 img-fluid" />Done by
                                                            <b>Chezhiyan</b>
                                                        </li>
                                                        <li class="d-sm-inline-block d-block mt-1"><i
                                                                class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>
                                                            28-07-2024 09:30 AM</li>
                                                        {{-- <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">chat</i>9
                                                                </li> --}}
                                                    </ul>
                                                </div>
                                                <div class="h5 mt-3"><i
                                                        class="material-icons-two-tone f-16 me-1">apartment</i>
                                                    Chief Invigilator</div>
                                            </div>
                                            <div class="mt-2">
                                                {{-- <a href="#" class="me-2 btn btn-sm btn-light-primary"><i
                                                                class="feather icon-eye mx-1"></i>View</a> --}}
                                                <a href="#" data-pc-animate="just-me" data-bs-toggle="modal"
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
                        <li class="task-list-item">
                            <i class="task-icon bg-danger"></i>
                            <div class="card ticket-card open-ticket">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-auto mb-3 mb-sm-0">
                                            <div class="d-sm-inline-block d-flex align-items-center">
                                                <img class="media-object wid-60 img-radius"
                                                    src="{{ asset('storage/assets/images/user/avatar-8.jpg') }}"
                                                    alt="Generic placeholder image " />
                                                <div class="ms-3 ms-sm-0 mb-3 mb-sm-0">
                                                    <ul class="text-sm-center list-unstyled mt-2 mb-0 d-inline-block">

                                                        {{-- <li class="list-unstyled-item"><a href="#"
                                                                        class="link-danger"><i class="fas fa-heart"></i>
                                                                        3</a></li> --}}
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="popup-trigger">
                                                <div class="h5 font-weight-bold">Replacemnt of Q-paper<small
                                                        class="badge bg-light-secondary ms-2">replaced</small>
                                                </div>
                                                <div class="help-sm-hidden">
                                                    <ul class="list-unstyled mt-2 mb-0 text-muted">
                                                        {{-- <li class="d-sm-inline-block d-block mt-1"><img
                                                                        src="../assets/images/admin/p1.jpg" alt=""
                                                                        class="wid-20 rounded me-2 img-fluid" />Piaf able
                                                                </li> --}}
                                                        <li class="d-sm-inline-block d-block mt-1"><img
                                                                src="../assets/images/user/avatar-5.jpg" alt=""
                                                                class="wid-20 rounded me-2 img-fluid" />Done by
                                                            <b>Chezhiyan</b>
                                                        </li>
                                                        <li class="d-sm-inline-block d-block mt-1"><i
                                                                class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>
                                                            28-07-2024 09:30 AM</li>
                                                        {{-- <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">chat</i>9
                                                                </li> --}}
                                                    </ul>
                                                </div>
                                                <div class="h5 mt-3"><i
                                                        class="material-icons-two-tone f-16 me-1">apartment</i>
                                                    Chief Invigilator</div>
                                            </div>
                                            <div class="mt-2">
                                                <a href="#" data-pc-animate="just-me" data-bs-toggle="modal"
                                                    data-bs-target="#viewReplacementModal"
                                                    class="me-2 btn btn-sm btn-light-primary"><i
                                                        class="feather icon-eye mx-1"></i>View</a>
                                                <a href="#" data-pc-animate="just-me" data-bs-toggle="modal"
                                                    data-bs-target="#paperReplacementModal"
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
                        <li class="task-list-item">
                            <i class="task-icon bg-danger"></i>
                            <div class="card ticket-card open-ticket">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-auto mb-3 mb-sm-0">
                                            <div class="d-sm-inline-block d-flex align-items-center">
                                                <img class="media-object wid-60 img-radius"
                                                    src="{{ asset('storage/assets/images/user/avatar-8.jpg') }}"
                                                    alt="Generic placeholder image " />
                                                <div class="ms-3 ms-sm-0 mb-3 mb-sm-0">
                                                    <ul class="text-sm-center list-unstyled mt-2 mb-0 d-inline-block">

                                                        {{-- <li class="list-unstyled-item"><a href="#"
                                                                        class="link-danger"><i class="fas fa-heart"></i>
                                                                        3</a></li> --}}
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="popup-trigger">
                                                <div class="h5 font-weight-bold">Remarks of Candidiate <small
                                                        class="badge bg-light-secondary ms-2">remarked</small>
                                                </div>
                                                <div class="help-sm-hidden">
                                                    <ul class="list-unstyled mt-2 mb-0 text-muted">
                                                        {{-- <li class="d-sm-inline-block d-block mt-1"><img
                                                                        src="../assets/images/admin/p1.jpg" alt=""
                                                                        class="wid-20 rounded me-2 img-fluid" />Piaf able
                                                                </li> --}}
                                                        <li class="d-sm-inline-block d-block mt-1"><img
                                                                src="../assets/images/user/avatar-5.jpg" alt=""
                                                                class="wid-20 rounded me-2 img-fluid" />Done by
                                                            <b>Chezhiyan</b>
                                                        </li>
                                                        <li class="d-sm-inline-block d-block mt-1"><i
                                                                class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>
                                                            28-07-2024 09:30 AM</li>
                                                        {{-- <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">chat</i>9
                                                                </li> --}}
                                                    </ul>
                                                </div>
                                                <div class="h5 mt-3"><i
                                                        class="material-icons-two-tone f-16 me-1">apartment</i>
                                                    Chief Invigilator</div>
                                            </div>
                                            <div class="mt-2">
                                                <a href="#" data-pc-animate="just-me" data-bs-toggle="modal"
                                                    data-bs-target="#candidateRemarksViewModal"
                                                    class="me-2 btn btn-sm btn-light-primary"><i
                                                        class="feather icon-eye mx-1"></i>View</a>
                                                <a href="#" data-pc-animate="just-me" data-bs-toggle="modal"
                                                    data-bs-target="#candidateRemarksModal"
                                                    class="me-2 btn btn-sm btn-light-info"><i
                                                        class="feather icon-plus mx-1"></i>Add</a>
                                                <a href="#" data-pc-animate="just-me" data-bs-toggle="modal"
                                                    data-bs-target="#candidateRemarkseditModal"
                                                    class="me-2 btn btn-sm btn-light-warning"><i
                                                        class="feather icon-edit mx-1"></i>Edit</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="task-list-item">
                            <i class="task-icon bg-danger"></i>
                            <div class="card ticket-card open-ticket">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-auto mb-3 mb-sm-0">
                                            <div class="d-sm-inline-block d-flex align-items-center">
                                                <img class="media-object wid-60 img-radius"
                                                    src="{{ asset('storage/assets/images/user/avatar-8.jpg') }}"
                                                    alt="Generic placeholder image " />
                                                <div class="ms-3 ms-sm-0 mb-3 mb-sm-0">
                                                    <ul class="text-sm-center list-unstyled mt-2 mb-0 d-inline-block">

                                                        {{-- <li class="list-unstyled-item"><a href="#"
                                                                        class="link-danger"><i class="fas fa-heart"></i>
                                                                        3</a></li> --}}
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="popup-trigger">
                                                <div class="h5 font-weight-bold">Counting / Packaging Videography
                                                    <small class="badge bg-light-secondary ms-2">videographed</small>
                                                </div>
                                                <div class="help-sm-hidden">
                                                    <ul class="list-unstyled mt-2 mb-0 text-muted">
                                                        {{-- <li class="d-sm-inline-block d-block mt-1"><img
                                                                        src="../assets/images/admin/p1.jpg" alt=""
                                                                        class="wid-20 rounded me-2 img-fluid" />Piaf able
                                                                </li> --}}
                                                        <li class="d-sm-inline-block d-block mt-1"><img
                                                                src="../assets/images/user/avatar-5.jpg" alt=""
                                                                class="wid-20 rounded me-2 img-fluid" />Done by
                                                            <b>Chezhiyan</b>
                                                        </li>
                                                        <li class="d-sm-inline-block d-block mt-1"><i
                                                                class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>
                                                            28-07-2024 09:30 AM</li>
                                                        {{-- <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">chat</i>9
                                                                </li> --}}
                                                    </ul>
                                                </div>
                                                <div class="h5 mt-3"><i
                                                        class="material-icons-two-tone f-16 me-1">apartment</i>
                                                    Chief Invigilator</div>
                                            </div>
                                            <div class="mt-2">
                                                <a href="#" data-pc-animate="just-me" data-bs-toggle="modal"
                                                    data-bs-target="#countingpackagingvideographyview"
                                                    class="me-2 btn btn-sm btn-light-primary"><i
                                                        class="feather icon-eye mx-1"></i>View</a>
                                                <a href="#" data-pc-animate="just-me" data-bs-toggle="modal"
                                                    data-bs-target="#countingpackagingvideography"
                                                    class="me-2 btn btn-sm btn-light-info"><i
                                                        class="feather icon-info mx-1"></i>verify</a>
                                                {{-- <a href="#" class="me-2 btn btn-sm btn-light-info"><i
                                                                class="feather icon-info mx-1"></i>Verify</a> --}}
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
                                                            src="{{ asset('storage/assets/images/user/avatar-8.jpg') }}"
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
                        <li class="task-list-item">
                            <i class="task-icon bg-danger"></i>
                            <div class="card ticket-card open-ticket">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-auto mb-3 mb-sm-0">
                                            <div class="d-sm-inline-block d-flex align-items-center">
                                                <img class="media-object wid-60 img-radius"
                                                    src="{{ asset('storage/assets/images/user/avatar-8.jpg') }}"
                                                    alt="Generic placeholder image " />
                                                <div class="ms-3 ms-sm-0 mb-3 mb-sm-0">
                                                    <ul class="text-sm-center list-unstyled mt-2 mb-0 d-inline-block">

                                                        {{-- <li class="list-unstyled-item"><a href="#"
                                                                        class="link-danger"><i class="fas fa-heart"></i>
                                                                        3</a></li> --}}
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="popup-trigger">
                                                <div class="h5 font-weight-bold">OMR Remarks<small
                                                        class="badge bg-light-secondary ms-2">remarked</small>
                                                </div>
                                                <div class="help-sm-hidden">
                                                    <ul class="list-unstyled mt-2 mb-0 text-muted">
                                                        {{-- <li class="d-sm-inline-block d-block mt-1"><img
                                                                        src="../assets/images/admin/p1.jpg" alt=""
                                                                        class="wid-20 rounded me-2 img-fluid" />Piaf able
                                                                </li> --}}
                                                        <li class="d-sm-inline-block d-block mt-1"><img
                                                                src="../assets/images/user/avatar-5.jpg" alt=""
                                                                class="wid-20 rounded me-2 img-fluid" />Done by
                                                            <b>Chezhiyan</b>
                                                        </li>
                                                        <li class="d-sm-inline-block d-block mt-1"><i
                                                                class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>
                                                            28-07-2024 09:30 AM</li>
                                                        {{-- <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">chat</i>9
                                                                </li> --}}
                                                    </ul>
                                                </div>
                                                <div class="h5 mt-3"><i
                                                        class="material-icons-two-tone f-16 me-1">apartment</i>
                                                    Chief Invigilator</div>
                                            </div>
                                            <div class="mt-2">
                                                <a href="#" data-pc-animate="just-me" data-bs-toggle="modal"
                                                    data-bs-target="#omrRemarksViewModal"
                                                    class="me-2 btn btn-sm btn-light-primary"><i
                                                        class="feather icon-eye mx-1"></i>View</a>
                                                <a href="#" data-pc-animate="just-me" data-bs-toggle="modal"
                                                    data-bs-target="#omrRemarksInputModal"
                                                    class="me-2 btn btn-sm btn-light-info"><i
                                                        class="feather icon-plus mx-1"></i>Add</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="task-list-item">
                            <i class="task-icon bg-danger"></i>
                            <div class="card ticket-card open-ticket">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-auto mb-3 mb-sm-0">
                                            <div class="d-sm-inline-block d-flex align-items-center">
                                                <img class="media-object wid-60 img-radius"
                                                    src="{{ asset('storage/assets/images/user/avatar-8.jpg') }}"
                                                    alt="Generic placeholder image " />
                                                <div class="ms-3 ms-sm-0 mb-3 mb-sm-0">
                                                    <ul class="text-sm-center list-unstyled mt-2 mb-0 d-inline-block">

                                                        {{-- <li class="list-unstyled-item"><a href="#"
                                                                        class="link-danger"><i class="fas fa-heart"></i>
                                                                        3</a></li> --}}
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="popup-trigger">
                                                <div class="h5 font-weight-bold">Scan Bundle Packaging <small
                                                        class="badge bg-light-secondary ms-2">scanned</small>
                                                </div>
                                                <div class="help-sm-hidden">
                                                    <ul class="list-unstyled mt-2 mb-0 text-muted">
                                                        {{-- <li class="d-sm-inline-block d-block mt-1"><img
                                                                        src="../assets/images/admin/p1.jpg" alt=""
                                                                        class="wid-20 rounded me-2 img-fluid" />Piaf able
                                                                </li> --}}
                                                        <li class="d-sm-inline-block d-block mt-1"><img
                                                                src="../assets/images/user/avatar-5.jpg" alt=""
                                                                class="wid-20 rounded me-2 img-fluid" />Done by
                                                            <b>Chezhiyan</b>
                                                        </li>
                                                        <li class="d-sm-inline-block d-block mt-1"><i
                                                                class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>
                                                            28-07-2024 09:30 AM</li>
                                                        {{-- <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">chat</i>9
                                                                </li> --}}
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
                        <li class="task-list-item">
                            <i class="task-icon bg-danger"></i>
                            <div class="card ticket-card open-ticket">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-auto mb-3 mb-sm-0">
                                            <div class="d-sm-inline-block d-flex align-items-center">
                                                <img class="media-object wid-60 img-radius"
                                                    src="{{ asset('storage/assets/images/user/avatar-8.jpg') }}"
                                                    alt="Generic placeholder image " />
                                                <div class="ms-3 ms-sm-0 mb-3 mb-sm-0">
                                                    <ul class="text-sm-center list-unstyled mt-2 mb-0 d-inline-block">

                                                        {{-- <li class="list-unstyled-item"><a href="#"
                                                                        class="link-danger"><i class="fas fa-heart"></i>
                                                                        3</a></li> --}}
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="popup-trigger">
                                                <div class="h5 font-weight-bold">Consolidate Certificate <small
                                                        class="badge bg-light-secondary ms-2">completed</small>
                                                </div>
                                                <div class="help-sm-hidden">
                                                    <ul class="list-unstyled mt-2 mb-0 text-muted">
                                                        {{-- <li class="d-sm-inline-block d-block mt-1"><img
                                                                        src="../assets/images/admin/p1.jpg" alt=""
                                                                        class="wid-20 rounded me-2 img-fluid" />Piaf able
                                                                </li> --}}
                                                        <li class="d-sm-inline-block d-block mt-1"><img
                                                                src="../assets/images/user/avatar-5.jpg" alt=""
                                                                class="wid-20 rounded me-2 img-fluid" />Done by
                                                            <b>Chezhiyan</b>
                                                        </li>
                                                        <li class="d-sm-inline-block d-block mt-1"><i
                                                                class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>
                                                            28-07-2024 09:30 AM</li>
                                                        {{-- <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">chat</i>9
                                                                </li> --}}
                                                    </ul>
                                                </div>
                                                <div class="h5 mt-3"><i
                                                        class="material-icons-two-tone f-16 me-1">apartment</i>
                                                    Chief Invigilator</div>
                                            </div>
                                            <div class="mt-2">
                                                <a href="#" data-pc-animate="just-me" data-bs-toggle="modal"
                                                    data-bs-target="#consolidatecertificate"
                                                    class="me-2 btn btn-sm btn-light-primary"><i
                                                        class="feather icon-info mx-1"></i>verify</a>
                                                <a href="{{ route('download.report', ['examId' => $session->currentexam->exam_main_no, 'exam_date' => $session->exam_sess_date, 'exam_session' => $session->exam_sess_session]) }}"
                                                    class="me-2 btn btn-sm btn-light-info">
                                                    <i class="feather icon-download mx-1"></i>Download
                                                </a>

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
            @include('modals.invigilator-edit')
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
            @include('modals.counting-packaging-videography-view')
            @include('modals.consolidate-certificate')
            @include('modals.qp-ans-replacement')
            @include('modals.qp-ans-replacement-view')
            @include('modals.qp-ans-replacement-edit')
            @include('modals.candidate-remarks')
            @include('modals.candidate-remarks-view')
            @include('modals.candidate-remarks-edit')
            @include('modals.omr-remarks')
            @include('modals.omr-remarks-view')
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
