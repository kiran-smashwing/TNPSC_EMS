@extends('layouts.app')
@section('title', 'Chief Invigilator - ' . $session->exam_main_name . ' - ' . $session->exam_main_notification)
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
                                    <h5><span class="text-primary">{{ $session->exam_main_notification }}</span>
                                        - {{ $session->exam_main_name }} - {{ $session->exam_main_postname }}
                                        - <span class="text-warning"> {{ $session->exam_main_startdate }} </span>
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
                                                        <a href="{{ route('current-exam.ciReceiveMaterials') }}"
                                                            class="me-2 btn btn-sm btn-light-primary"><i
                                                                class="feather icon-aperture mx-1"></i>Scan</a>
                                                        <a href="#" data-pc-animate="just-me" data-bs-toggle="modal"
                                                            data-bs-target="#meetingPreliminaryCheckListModel"
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
                                                        <a href="#" data-bs-toggle="modal"
                                                            data-bs-target="#preliminaryCheckListModel"
                                                            data-pc-animate="just-me"
                                                            class="me-2 btn btn-sm btn-light-primary"><i
                                                                class="feather icon-info mx-1"></i>Verify</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @foreach ($groupedSessions as $date => $sessions)
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

                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="popup-trigger">
                                                            <div class="h5 font-weight-bold">{{ $date }}<small
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
                                                            @php
                                                                $fnSession = $sessions->first(function ($session) {
                                                                    return strtoupper($session->exam_sess_session) ===
                                                                        'FN';
                                                                });

                                                                $anSession = $sessions->first(function ($session) {
                                                                    return strtoupper($session->exam_sess_session) ===
                                                                        'AN';
                                                                });
                                                            @endphp
                                                            @if ($fnSession)
                                                                <a href="{{ route('my-exam.ciExamActivity', ['examid' => $fnSession->exam_sess_mainid, 'session' => $fnSession->exam_sess_session]) }}"
                                                                    class="me-2 btn btn-sm btn-light-primary"><i
                                                                        class="feather icon-disc mx-1"></i>FN - Session</a>
                                                            @endif
                                                            @if ($anSession)
                                                                <a href="{{ route('my-exam.ciExamActivity', ['examid' => $anSession->exam_sess_mainid, 'session' => $anSession->exam_sess_session]) }}"
                                                                    class="me-2 btn btn-sm btn-light-info"><i
                                                                        class="feather icon-disc mx-1"></i>AN - Session</a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
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
                                                               
                                                                <li class="d-sm-inline-block d-block mt-1"><img
                                                                        src="../assets/images/user/avatar-5.jpg"
                                                                        alt=""
                                                                        class="wid-20 rounded me-2 img-fluid" />Done by
                                                                    <b>Chezhiyan</b>
                                                                </li>
                                                                <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>
                                                                    28-07-2024 09:30 AM</li>
                                                              
                                                            </ul>
                                                        </div>
                                                        <div class="h5 mt-3"><i
                                                                class="material-icons-two-tone f-16 me-1">apartment</i>
                                                            Chief Invigilator</div>
                                                    </div>
                                                    <div class="mt-2">
                                                        <a href="{{ route('current-exam.examActivityTask') }}"
                                                            class="me-2 btn btn-sm btn-light-primary"><i
                                                                class="feather icon-edit mx-1"></i>Update Exam
                                                            Activites</a>
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
                                                        <a href="#" data-pc-animate="just-me"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#utilizationCertificateModal"
                                                            class="me-2 btn btn-sm btn-light-primary"><i
                                                                class="feather icon-info mx-1"></i>Self declaration</a>
                                                        <a href="{{ route('download.utilireport', ['examid' => $anSession->exam_sess_mainid]) }}" class="me-2 btn btn-sm btn-light-info"><i
                                                                class="feather icon-download mx-1"></i>Download</a>
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
                {{-- @include('modals.apd-upload-excel') --}}
                {{-- @include('modals.id-increase-candidate') --}}
                @include('modals.apd-finalize-candidate')
                @include('modals.preliminary-checklist')
                @include('modals.utilization-certificate')
                @include('modals.meeting-preliminary-checklist')
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
