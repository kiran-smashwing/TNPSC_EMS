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
                                    <h5><span class="text-primary">08/2024</span> - Combined Civil Services Examination - II
                                        (Group II and IIA Services) - <span class="text-warning"> 20-06-2024 </span> </h5>
                                    <div class="btn-group btn-group-sm help-filter" role="group"
                                        aria-label="button groups sm">
                                    </div>
                                </nav>
                            </div>
                        </div>

                        <div class="card-body">
                            <ul class="list-unstyled task-list">
                                <li class="task-list-item">
                                    <i class="feather icon-check f-w-600 task-icon bg-success"></i>
                                    <div class="card ticket-card open-ticket">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-auto mb-3 mb-sm-0">
                                                    <div class="d-sm-inline-block d-flex align-items-center">
                                                        <img class="media-object wid-60 img-radius"
                                                            src="{{ asset('storage/assets/images/user/avatar-1.jpg') }}"
                                                            alt="Generic placeholder image " />
                                                        <div class="ms-3 ms-sm-0 mb-3 mb-sm-0">
                                                            <ul
                                                                class="text-sm-center list-unstyled mt-2 mb-0 d-inline-block">
                                                                {{-- <li class="list-unstyled-item"><a href="#" class="link-secondary">1 session</a></li> --}}
                                                                {{-- <li class="list-unstyled-item"
                                                    ><a href="#" class="link-danger"><i class="fas fa-heart"></i> 3</a></li
                                                  > --}}
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="popup-trigger">
                                                        <div class="h5 font-weight-bold">Exam Meta Data <small
                                                                class="badge bg-light-secondary ms-2">created</small></div>
                                                        <div class="help-sm-hidden">
                                                            <ul class="list-unstyled mt-2 mb-0 text-muted">
                                                                {{-- <li class="d-sm-inline-block d-block mt-1"
                                                    ><img src="../assets/images/admin/p1.jpg" alt="" class="wid-20 rounded me-2 img-fluid" /></li
                                                  > --}}
                                                                <li class="d-sm-inline-block d-block mt-1"><img
                                                                        src="../assets/images/user/avatar-5.jpg"
                                                                        alt=""
                                                                        class="wid-20 rounded me-2 img-fluid" />Done by
                                                                    <b>Elanchezhiyan</b>
                                                                </li>
                                                                <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>22-07-2024
                                                                    04:45 PM</li>
                                                                {{-- <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">chat</i>9
                                                                </li> --}}
                                                            </ul>
                                                        </div>
                                                        <div class="h5 mt-3"><i
                                                                class="material-icons-two-tone f-16 me-1">apartment</i> RND
                                                            - Section Officer</div>

                                                    </div>
                                                    <div class="mt-2">
                                                        <a href="#" class="me-2 btn btn-sm btn-light-primary"><i
                                                                class="feather icon-eye mx-1"></i>View Exam</a>
                                                        <a href="{{ route('current-exam.edit') }}"
                                                            class="me-3 btn btn-sm btn-light-warning"><i
                                                                class="feather icon-edit mx-1"></i>Edit Exam</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="task-list-item">
                                    <i class="task-icon bg-warning"></i>
                                    <div class="card ticket-card open-ticket">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-auto mb-3 mb-sm-0">
                                                    <div class="d-sm-inline-block d-flex align-items-center">
                                                        <img class="media-object wid-60 img-radius"
                                                            src="{{ asset('storage/assets/images/user/avatar-7.jpg') }}"
                                                            alt="Generic placeholder image " />
                                                        <div class="ms-3 ms-sm-0 mb-3 mb-sm-0">
                                                            <ul
                                                                class="text-sm-center list-unstyled mt-2 mb-0 d-inline-block">
                                                                {{-- <li class="list-unstyled-item"><a href="#"
                                                                        class="link-secondary">1 Ticket</a></li>
                                                                <li class="list-unstyled-item"><a href="#"
                                                                        class="link-danger"><i class="fas fa-heart"></i>
                                                                        3</a></li> --}}
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="popup-trigger">
                                                        <div class="h5 font-weight-bold">Select Venues<small
                                                                class="badge bg-light-secondary ms-2">selected</small>
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
                                                                    <b>Ariyalur</b>
                                                                </li>
                                                                <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>
                                                                    24-07-2024 01:23 PM</li>
                                                                {{-- <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">chat</i>9
                                                                </li> --}}
                                                            </ul>
                                                        </div>
                                                        <div class="h5 mt-3"><i
                                                                class="material-icons-two-tone f-16 me-1">apartment</i>
                                                            District Collectorate</div>
                                                    </div>
                                                    <div class="mt-2">
                                                        <a href="#" class="me-2 btn btn-sm btn-light-primary"><i
                                                                class="feather icon-eye mx-1"></i>View</a>
                                                        <a target="_blank" href="{{ route('current-exam.intimateVenue') }}" class="me-2 btn btn-sm btn-light-info"><i
                                                                class="feather icon-check-circle mx-1"></i>Select
                                                            Venues</a>
                                                        <a href="#" data-pc-animate="blur" data-bs-toggle="modal"
                                                        data-bs-target="#sendConsentMailModel" class="me-3 btn btn-sm btn-light-warning"><i
                                                                class="feather icon-navigation mx-1"></i>Send
                                                            Intimation</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="task-list-item">
                                    <i class="task-icon bg-primary"></i>
                                    <div class="card ticket-card open-ticket">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-auto mb-3 mb-sm-0">
                                                    <div class="d-sm-inline-block d-flex align-items-center">
                                                        <img class="media-object wid-60 img-radius"
                                                            src="{{ asset('storage/assets/images/user/avatar-3.jpg') }}"
                                                            alt="Generic placeholder image " />
                                                        <div class="ms-3 ms-sm-0 mb-3 mb-sm-0">
                                                            <ul
                                                                class="text-sm-center list-unstyled mt-2 mb-0 d-inline-block">
                                                                {{-- <li class="list-unstyled-item"><a href="#"
                                                                        class="link-secondary">1 Ticket</a></li>
                                                                <li class="list-unstyled-item"><a href="#"
                                                                        class="link-danger"><i class="fas fa-heart"></i>
                                                                        3</a></li> --}}
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="popup-trigger">
                                                        <div class="h5 font-weight-bold">Receive Exam Materials<small
                                                                class="badge bg-light-secondary ms-2">Received</small>
                                                        </div>
                                                        <div class="help-sm-hidden">
                                                            <ul class="list-unstyled mt-2 mb-0 text-muted">
                                                                {{-- <li class="d-sm-inline-block d-block mt-1"
                                                    ><img src="../assets/images/admin/p1.jpg" alt="" class="wid-20 rounded me-2 img-fluid" /></li
                                                  > --}}
                                                                <li class="d-sm-inline-block d-block mt-1"><img
                                                                        src="../assets/images/user/avatar-4.jpg"
                                                                        alt=""
                                                                        class="wid-20 rounded me-2 img-fluid" />Done by
                                                                    <b>Anbezhili</b>
                                                                </li>
                                                                <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>25-07-2024
                                                                    10:05 AM</li>
                                                                {{-- <li class="d-sm-inline-block d-block mt-1"
                                                    ><i class="wid-20 material-icons-two-tone text-center f-14 me-2">chat</i>9
                                                  </li> --}}
                                                            </ul>
                                                        </div>
                                                        <div class="h5 mt-3"><i
                                                                class="material-icons-two-tone f-16 me-1">apartment</i> HQ
                                                            - Treasury Officer</div>

                                                    </div>
                                                    <div class="mt-2">
                                                        <a href="#"
                                                            class="me-2 btn btn-sm btn-light-primary "><i
                                                                class="feather icon-eye mx-1 "></i>View </a>
                                                        <a href="#" data-pc-animate="just-me" data-bs-toggle="modal"
                                                        data-bs-target="#printerToTreasuryModal" class="me-2 btn btn-sm btn-light-info"><i
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
                                                            src="{{ asset('storage/assets/images/user/avatar-7.jpg') }}"
                                                            alt="Generic placeholder image " />
                                                        <div class="ms-3 ms-sm-0 mb-3 mb-sm-0">
                                                            <ul
                                                                class="text-sm-center list-unstyled mt-2 mb-0 d-inline-block">
                                                                {{-- <li class="list-unstyled-item"><a href="#"
                                                                        class="link-secondary">1 Ticket</a></li>
                                                                <li class="list-unstyled-item"><a href="#"
                                                                        class="link-danger"><i class="fas fa-heart"></i>
                                                                        3</a></li> --}}
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="popup-trigger">
                                                        <div class="h5 font-weight-bold">Create CI Meeting<small
                                                                class="badge bg-light-secondary ms-2">generated</small>
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
                                                                    <b>Ariyalur</b>
                                                                </li>
                                                                <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>
                                                                    25-07-2024 04:30 PM</li>
                                                                {{-- <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">chat</i>9
                                                                </li> --}}
                                                            </ul>
                                                        </div>
                                                        <div class="h5 mt-3"><i
                                                                class="material-icons-two-tone f-16 me-1">apartment</i>
                                                            District Collectorate</div>
                                                    </div>
                                                    <div class="mt-2">
                                                        <a href="#"  data-pc-animate="just-me" data-bs-toggle="modal"
                                                        data-bs-target="#ciMeetingCodeGenerateModal" class="me-2 btn btn-sm btn-light-primary"><i
                                                                class="feather icon-grid mx-1"></i>Generate</a>
                                                        <a href="helpdesk-ticket-details.html"
                                                            class="me-2 btn btn-sm btn-light-info"><i
                                                                class="feather icon-download mx-1"></i>Download</a>
                                                        <a href="#" class="me-3 btn btn-sm btn-light-warning"><i
                                                                class="feather icon-navigation mx-1"></i>Send
                                                            Intimation</a>
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
                                                                        class="link-secondary">1 Ticket</a></li>
                                                                <li class="list-unstyled-item"><a href="#"
                                                                        class="link-danger"><i class="fas fa-heart"></i>
                                                                        3</a></li> --}}
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="popup-trigger">
                                                        <div class="h5 font-weight-bold">Attendence - CI Meeting<small
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
                                                                    26-07-2024 09:30 AM</li>
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
                                                        <a href="#" class="me-2 btn btn-sm btn-light-primary"><i
                                                                class="feather icon-aperture mx-1"></i>Scan</a>
                                                        <a href="helpdesk-ticket-details.html"
                                                            class="me-2 btn btn-sm btn-light-info"><i
                                                                class="feather icon-list mx-1"></i>Adequacy Check</a>
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
                                                            src="{{ asset('storage/assets/images/user/avatar-7.jpg') }}"
                                                            alt="Generic placeholder image " />
                                                        <div class="ms-3 ms-sm-0 mb-3 mb-sm-0">
                                                            <ul
                                                                class="text-sm-center list-unstyled mt-2 mb-0 d-inline-block">
                                                                {{-- <li class="list-unstyled-item"><a href="#"
                                                                        class="link-secondary">1 Ticket</a></li>
                                                                <li class="list-unstyled-item"><a href="#"
                                                                        class="link-danger"><i class="fas fa-heart"></i>
                                                                        3</a></li> --}}
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="popup-trigger">
                                                        <div class="h5 font-weight-bold">Route Creation<small
                                                                class="badge bg-light-secondary ms-2">created</small>
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
                                                                    <b>Ariyalur</b>
                                                                </li>
                                                                <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>
                                                                    26-07-2024 01:12 PM</li>
                                                                {{-- <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">chat</i>9
                                                                </li> --}}
                                                            </ul>
                                                        </div>
                                                        <div class="h5 mt-3"><i
                                                                class="material-icons-two-tone f-16 me-1">apartment</i>
                                                            District Collectorate</div>
                                                    </div>
                                                    <div class="mt-2">
                                                        <a href="#" class="me-2 btn btn-sm btn-light-primary"><i
                                                                class="feather icon-eye mx-1"></i>view</a>
                                                        <a href="helpdesk-ticket-details.html"
                                                            class="me-2 btn btn-sm btn-light-info"><i
                                                                class="feather icon-map mx-1"></i>Create Route</a>

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
                                                            src="{{ asset('storage/assets/images/user/avatar-1.jpg') }}"
                                                            alt="Generic placeholder image " />
                                                        <div class="ms-3 ms-sm-0 mb-3 mb-sm-0">
                                                            <ul
                                                                class="text-sm-center list-unstyled mt-2 mb-0 d-inline-block">
                                                                {{-- <li class="list-unstyled-item"><a href="#"
                                                                        class="link-secondary">1 Ticket</a></li>
                                                                <li class="list-unstyled-item"><a href="#"
                                                                        class="link-danger"><i class="fas fa-heart"></i>
                                                                        3</a></li> --}}
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="popup-trigger">
                                                        <div class="h5 font-weight-bold">Receive Materials From
                                                            Treasury<small
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
                                                                    <b>Prabakaran</b>
                                                                </li>
                                                                <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>
                                                                    27-07-2024 02:32 PM</li>
                                                                {{-- <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">chat</i>9
                                                                </li> --}}
                                                            </ul>
                                                        </div>
                                                        <div class="h5 mt-3"><i
                                                                class="material-icons-two-tone f-16 me-1">apartment</i>
                                                            DC - Sub Treasury</div>
                                                    </div>
                                                    <div class="mt-2">
                                                        <a href="#" class="me-2 btn btn-sm btn-light-primary"><i
                                                                class="feather icon-info mx-1"></i>Verify </a>
                                                        <a href="helpdesk-ticket-details.html"
                                                            class="me-2 btn btn-sm btn-light-info"><i
                                                                class="feather icon-map mx-1"></i>View Route</a>

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
                                                            src="{{ asset('storage/assets/images/user/avatar-10.jpg') }}"
                                                            alt="Generic placeholder image " />
                                                        <div class="ms-3 ms-sm-0 mb-3 mb-sm-0">
                                                            <ul
                                                                class="text-sm-center list-unstyled mt-2 mb-0 d-inline-block">
                                                                {{-- <li class="list-unstyled-item"><a href="#"
                                                                        class="link-secondary">1 Ticket</a></li>
                                                                <li class="list-unstyled-item"><a href="#"
                                                                        class="link-danger"><i class="fas fa-heart"></i>
                                                                        3</a></li> --}}
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="popup-trigger">
                                                        <div class="h5 font-weight-bold">Receive Materials From Sub
                                                            Treasury<small
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
                                                                    <b>Iniya</b>
                                                                </li>
                                                                <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>
                                                                    27-07-2024 02:32 PM</li>
                                                                {{-- <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">chat</i>9
                                                                </li> --}}
                                                            </ul>
                                                        </div>
                                                        <div class="h5 mt-3"><i
                                                                class="material-icons-two-tone f-16 me-1">apartment</i>
                                                            DC - Mobile Team</div>
                                                    </div>
                                                    <div class="mt-2">
                                                        <a href="{{route('current-exam.treasury-mobTeam')}}" class="me-2 btn btn-sm btn-light-primary"><i
                                                                class="feather icon-info mx-1"></i>Verify </a>
                                                        <a href="helpdesk-ticket-details.html"
                                                            class="me-2 btn btn-sm btn-light-info"><i
                                                                class="feather icon-map mx-1"></i>View Route</a>

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
                                                        <a target="_blank" href="{{route('current-exam.mobTeam-ci')}}" class="me-2 btn btn-sm btn-light-primary"><i
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
                                                        <div class="h5 font-weight-bold">Preliminary Check<small
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
                                                        <a href="#"  data-bs-toggle="modal" data-bs-target="#preliminaryCheckListModel" data-pc-animate="just-me" class="me-2 btn btn-sm btn-light-primary"><i
                                                                class="feather icon-info mx-1" ></i>Verify</a>
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
                                                        <div class="h5 font-weight-bold">FN - Session<small
                                                                class="badge bg-light-secondary ms-2">completed</small>
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
                                                        <a href="#" class="me-2 btn btn-sm btn-light-primary"><i
                                                                class="feather icon-edit mx-1"></i>Update Exam
                                                            Activites</a>
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
                                                        <a href="#" data-pc-animate="just-me"  data-bs-toggle="modal" data-bs-target="#sessionCheckListModel" class="me-2 btn btn-sm btn-light-primary"><i
                                                                class="feather icon-info mx-1" ></i>Verify</a>
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
                                                        {{-- <a href="#"  data-bs-toggle="modal" data-bs-target="#invigilatorAllotmentModel" class="me-2 btn btn-sm btn-light-primary"><i
                                                                class="feather icon-eye mx-1"></i>View</a> --}}
                                                        <a href="#"  data-bs-toggle="modal" data-bs-target="#invigilatorSelectModal" class="me-2 btn btn-sm btn-light-info"><i
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
                                                        <a href="#" class="me-2 btn btn-sm btn-light-primary"><i
                                                                class="feather icon-eye mx-1"></i>View</a>
                                                        <a href="#" class="me-2 btn btn-sm btn-light-info"><i
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
                                                        <a href="#" class="me-2 btn btn-sm btn-light-primary"><i
                                                                class="feather icon-eye mx-1"></i>View</a>
                                                        <a href="#" class="me-2 btn btn-sm btn-light-info"><i
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
                                                        <a href="#" class="me-2 btn btn-sm btn-light-primary"><i
                                                                class="feather icon-eye mx-1"></i>View</a>
                                                        <a href="#"  data-bs-toggle="modal" data-bs-target="#questionPaperScanModal" class="me-2 btn btn-sm btn-light-info"><i
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
                                                        <a href="#" class="me-2 btn btn-sm btn-light-primary"><i
                                                                class="feather icon-eye mx-1"></i>View</a>
                                                        <a href="#" class="me-2 btn btn-sm btn-light-info"><i
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
                                                        <div class="h5 font-weight-bold">Candidiate Subject Change<small
                                                                class="badge bg-light-secondary ms-2">changed</small>
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
                                                        <a href="#" class="me-2 btn btn-sm btn-light-primary"><i
                                                                class="feather icon-eye mx-1"></i>View</a>
                                                        <a href="#" class="me-2 btn btn-sm btn-light-info"><i
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
                                                        <a href="#" class="me-2 btn btn-sm btn-light-primary"><i
                                                                class="feather icon-eye mx-1"></i>View</a>
                                                        <a href="#" data-bs-toggle="modal" data-bs-target="#distributionTimeModal" class="me-2 btn btn-sm btn-light-info"><i
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
                                                        <a href="#" class="me-2 btn btn-sm btn-light-primary"><i
                                                                class="feather icon-eye mx-1"></i>View</a>
                                                        <a href="#" class="me-2 btn btn-sm btn-light-info"><i
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
                                                        <a href="#" class="me-2 btn btn-sm btn-light-primary"><i
                                                                class="feather icon-eye mx-1"></i>View</a>
                                                        <a href="#" class="me-2 btn btn-sm btn-light-info"><i
                                                                class="feather icon-plus mx-1"></i>Add</a>
                                                        <a href="#" class="me-2 btn btn-sm btn-light-warning"><i
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
                                                        <div class="h5 font-weight-bold">Counting / Packaging Videography
                                                            <small
                                                                class="badge bg-light-secondary ms-2">videographed</small>
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
                                                        <a href="#" class="me-2 btn btn-sm btn-light-primary"><i
                                                                class="feather icon-eye mx-1"></i>View</a>
                                                        <a href="#" class="me-2 btn btn-sm btn-light-info"><i
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
                                                        <div class="h5 font-weight-bold">Exam Rooms / Seat Arrangment
                                                            Videography <small
                                                                class="badge bg-light-secondary ms-2">videographed</small>
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
                                                        <a href="#" class="me-2 btn btn-sm btn-light-primary"><i
                                                                class="feather icon-eye mx-1"></i>View</a>
                                                        <a href="#" class="me-2 btn btn-sm btn-light-info"><i
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
                                                        <a href="#" class="me-2 btn btn-sm btn-light-primary"><i
                                                                class="feather icon-eye mx-1"></i>View</a>
                                                        <a href="#" class="me-2 btn btn-sm btn-light-info"><i
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
                                                        <a href="#" class="me-2 btn btn-sm btn-light-primary"><i
                                                                class="feather icon-eye mx-1"></i>View</a>
                                                        <a href="#" class="me-2 btn btn-sm btn-light-info"><i
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
                                                        <a href="#" class="me-2 btn btn-sm btn-light-primary"><i
                                                                class="feather icon-plus mx-1"></i>Add</a>
                                                        <a href="#" class="me-2 btn btn-sm btn-light-info"><i
                                                                class="feather icon-download mx-1"></i>Download</a>
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
                                                        <div class="h5 font-weight-bold">Utility Certificate <small
                                                                class="badge bg-light-secondary ms-2">completed</small>
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
                                                        <a href="#" class="me-2 btn btn-sm btn-light-primary"><i
                                                                class="feather icon-plus mx-1"></i>Add</a>
                                                        <a href="#" class="me-2 btn btn-sm btn-light-info"><i
                                                                class="feather icon-download mx-1"></i>Download</a>
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
                                                            src="{{ asset('storage/assets/images/user/avatar-10.jpg') }}"
                                                            alt="Generic placeholder image " />
                                                        <div class="ms-3 ms-sm-0 mb-3 mb-sm-0">
                                                            <ul
                                                                class="text-sm-center list-unstyled mt-2 mb-0 d-inline-block">
                                                                {{-- <li class="list-unstyled-item"><a href="#"
                                                                        class="link-secondary">1 Ticket</a></li>
                                                                <li class="list-unstyled-item"><a href="#"
                                                                        class="link-danger"><i class="fas fa-heart"></i>
                                                                        3</a></li> --}}
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="popup-trigger">
                                                        <div class="h5 font-weight-bold">Receive Materials From Cheif
                                                            Invigilator<small
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
                                                                    <b>Iniya</b>
                                                                </li>
                                                                <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>
                                                                    27-07-2024 02:32 PM</li>
                                                                {{-- <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">chat</i>9
                                                                </li> --}}
                                                            </ul>
                                                        </div>
                                                        <div class="h5 mt-3"><i
                                                                class="material-icons-two-tone f-16 me-1">apartment</i>
                                                            DC - Mobile Team</div>
                                                    </div>
                                                    <div class="mt-2">
                                                        <a href="#" class="me-2 btn btn-sm btn-light-primary"><i
                                                                class="feather icon-info mx-1"></i>Verify </a>
                                                        <a href="helpdesk-ticket-details.html"
                                                            class="me-2 btn btn-sm btn-light-info"><i
                                                                class="feather icon-map mx-1"></i>View Route</a>

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
                                                            src="{{ asset('storage/assets/images/user/avatar-1.jpg') }}"
                                                            alt="Generic placeholder image " />
                                                        <div class="ms-3 ms-sm-0 mb-3 mb-sm-0">
                                                            <ul
                                                                class="text-sm-center list-unstyled mt-2 mb-0 d-inline-block">
                                                                {{-- <li class="list-unstyled-item"><a href="#"
                                                                        class="link-secondary">1 Ticket</a></li>
                                                                <li class="list-unstyled-item"><a href="#"
                                                                        class="link-danger"><i class="fas fa-heart"></i>
                                                                        3</a></li> --}}
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="popup-trigger">
                                                        <div class="h5 font-weight-bold">Receive Materials From Cheif
                                                            Invigilator<small
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
                                                                    <b>Iniya</b>
                                                                </li>
                                                                <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>
                                                                    27-07-2024 02:32 PM</li>
                                                                {{-- <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">chat</i>9
                                                                </li> --}}
                                                            </ul>
                                                        </div>
                                                        <div class="h5 mt-3"><i
                                                                class="material-icons-two-tone f-16 me-1">apartment</i>
                                                            HQ - Van Duty Staff</div>
                                                    </div>
                                                    <div class="mt-2">
                                                        <a href="#" class="me-2 btn btn-sm btn-light-primary"><i
                                                                class="feather icon-info mx-1"></i>Verify </a>
                                                        <a href="helpdesk-ticket-details.html"
                                                            class="me-2 btn btn-sm btn-light-info"><i
                                                                class="feather icon-map mx-1"></i>View Route</a>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="task-list-item">
                                    <i class="task-icon bg-primary"></i>
                                    <div class="card ticket-card open-ticket">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-auto mb-3 mb-sm-0">
                                                    <div class="d-sm-inline-block d-flex align-items-center">
                                                        <img class="media-object wid-60 img-radius"
                                                            src="{{ asset('storage/assets/images/user/avatar-3.jpg') }}"
                                                            alt="Generic placeholder image " />
                                                        <div class="ms-3 ms-sm-0 mb-3 mb-sm-0">
                                                            <ul
                                                                class="text-sm-center list-unstyled mt-2 mb-0 d-inline-block">
                                                                {{-- <li class="list-unstyled-item"><a href="#"
                                                                        class="link-secondary">1 Ticket</a></li>
                                                                <li class="list-unstyled-item"><a href="#"
                                                                        class="link-danger"><i class="fas fa-heart"></i>
                                                                        3</a></li> --}}
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="popup-trigger">
                                                        <div class="h5 font-weight-bold">Receive Exam Materials From
                                                            VDS<small
                                                                class="badge bg-light-secondary ms-2">Received</small>
                                                        </div>
                                                        <div class="help-sm-hidden">
                                                            <ul class="list-unstyled mt-2 mb-0 text-muted">
                                                                {{-- <li class="d-sm-inline-block d-block mt-1"
                                                    ><img src="../assets/images/admin/p1.jpg" alt="" class="wid-20 rounded me-2 img-fluid" /></li
                                                  > --}}
                                                                <li class="d-sm-inline-block d-block mt-1"><img
                                                                        src="../assets/images/user/avatar-4.jpg"
                                                                        alt=""
                                                                        class="wid-20 rounded me-2 img-fluid" />Done by
                                                                    <b>Anbezhili</b>
                                                                </li>
                                                                <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>25-07-2024
                                                                    10:05 AM</li>
                                                                {{-- <li class="d-sm-inline-block d-block mt-1"
                                                    ><i class="wid-20 material-icons-two-tone text-center f-14 me-2">chat</i>9
                                                  </li> --}}
                                                            </ul>
                                                        </div>
                                                        <div class="h5 mt-3"><i
                                                                class="material-icons-two-tone f-16 me-1">apartment</i> HQ
                                                            - Treasury Officer</div>

                                                    </div>
                                                    <div class="mt-2">
                                                        <a href="helpdesk-ticket-details.html"
                                                            class="me-2 btn btn-sm btn-light-primary "
                                                            data-pc-animate="just-me" data-bs-toggle="modal"
                                                            data-bs-target="#animateModal"><i
                                                                class="feather icon-eye mx-1 "></i>View </a>
                                                        <a href="#" class="me-2 btn btn-sm btn-light-info"><i
                                                                class="feather icon-info mx-1"></i>Verify</a>
                                                        <a href="#" class="me-3 btn btn-sm btn-light-warning"><i
                                                                class="feather icon-edit mx-1"></i>Edit </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="task-list-item">
                                    <i class="task-icon bg-primary"></i>
                                    <div class="card ticket-card open-ticket">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-auto mb-3 mb-sm-0">
                                                    <div class="d-sm-inline-block d-flex align-items-center">
                                                        <img class="media-object wid-60 img-radius"
                                                            src="{{ asset('storage/assets/images/user/avatar-3.jpg') }}"
                                                            alt="Generic placeholder image " />
                                                        <div class="ms-3 ms-sm-0 mb-3 mb-sm-0">
                                                            <ul
                                                                class="text-sm-center list-unstyled mt-2 mb-0 d-inline-block">
                                                                {{-- <li class="list-unstyled-item"><a href="#"
                                                                        class="link-secondary">1 Ticket</a></li>
                                                                <li class="list-unstyled-item"><a href="#"
                                                                        class="link-danger"><i class="fas fa-heart"></i>
                                                                        3</a></li> --}}
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="popup-trigger">
                                                        <div class="h5 font-weight-bold">Scan Trunk Box<small
                                                                class="badge bg-light-secondary ms-2">Scanned</small>
                                                        </div>
                                                        <div class="help-sm-hidden">
                                                            <ul class="list-unstyled mt-2 mb-0 text-muted">
                                                                {{-- <li class="d-sm-inline-block d-block mt-1"
                                                    ><img src="../assets/images/admin/p1.jpg" alt="" class="wid-20 rounded me-2 img-fluid" /></li
                                                  > --}}
                                                                <li class="d-sm-inline-block d-block mt-1"><img
                                                                        src="../assets/images/user/avatar-4.jpg"
                                                                        alt=""
                                                                        class="wid-20 rounded me-2 img-fluid" />Done by
                                                                    <b>Anbezhili</b>
                                                                </li>
                                                                <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>25-07-2024
                                                                    10:05 AM</li>
                                                                {{-- <li class="d-sm-inline-block d-block mt-1"
                                                    ><i class="wid-20 material-icons-two-tone text-center f-14 me-2">chat</i>9
                                                  </li> --}}
                                                            </ul>
                                                        </div>
                                                        <div class="h5 mt-3"><i
                                                                class="material-icons-two-tone f-16 me-1">apartment</i> HQ
                                                            - Admin Officer</div>

                                                    </div>
                                                    <div class="mt-2">
                                                        <a href="helpdesk-ticket-details.html"
                                                            class="me-2 btn btn-sm btn-light-primary "
                                                            data-pc-animate="just-me" data-bs-toggle="modal"
                                                            data-bs-target="#animateModal"><i
                                                                class="feather icon-eye mx-1 "></i>View </a>
                                                        <a href="#" class="me-3 btn btn-sm btn-light-info"><i
                                                                class="feather icon-aperture mx-1"></i>Scan</a>
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
                @include('modals.printer-to-treasury')
                @include('modals.ci-meetingcode-generate')
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>
    @include('partials.footer')

    @push('scripts')
        <script src="{{ asset('storage/assets/js/plugins/prism.js') }}"></script>
        <script src="{{ asset('storage/assets/js/plugins/quill.min.js') }}"></script>
        <!-- [Page Specific JS] start -->
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
        
        <!-- [Page Specific JS] end -->
    @endpush

    @include('partials.theme')

@endsection
