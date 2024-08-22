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
                                                        <a href="helpdesk-ticket-details.html"
                                                            class="me-2 btn btn-sm btn-light-primary"><i
                                                                class="feather icon-eye mx-1"></i>View Exam</a>
                                                        <a href="#" class="me-3 btn btn-sm btn-light-warning"><i
                                                                class="feather icon-edit mx-1"></i>Edit Exam</a>
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
                                                            src="{{ asset('storage/assets/images/user/avatar-6.jpg') }}"
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
                                                        <div class="h5 font-weight-bold">Tentative Candidates CSV <small
                                                                class="badge bg-light-secondary ms-2">uploaded</small></div>
                                                        <div class="help-sm-hidden">
                                                            <ul class="list-unstyled mt-2 mb-0 text-muted">
                                                                {{-- <li class="d-sm-inline-block d-block mt-1"
                                                    ><img src="../assets/images/admin/p1.jpg" alt="" class="wid-20 rounded me-2 img-fluid" /></li
                                                  > --}}
                                                                <li class="d-sm-inline-block d-block mt-1"><img
                                                                        src="../assets/images/user/avatar-5.jpg"
                                                                        alt=""
                                                                        class="wid-20 rounded me-2 img-fluid" />Done by
                                                                    <b>Poonkuzhali</b>
                                                                </li>
                                                                <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>23-07-2024
                                                                    09:55 AM</li>
                                                                {{-- <li class="d-sm-inline-block d-block mt-1"
                                                    ><i class="wid-20 material-icons-two-tone text-center f-14 me-2">chat</i>9
                                                  </li> --}}
                                                            </ul>
                                                        </div>
                                                        <div class="h5 mt-3"><i
                                                                class="material-icons-two-tone f-16 me-1">apartment</i> APD
                                                            - Section Officer</div>

                                                    </div>
                                                    <div class="mt-2">
                                                        <a href="helpdesk-ticket-details.html"
                                                            class="me-2 btn btn-sm btn-light-primary mb-2" data-pc-animate="just-me" data-bs-toggle="modal" data-bs-target="#animateModal"><i
                                                                class="feather icon-upload mx-1 "></i>Upload </a>
                                                        <a href="#" class="me-3 btn btn-sm btn-light-warning"><i
                                                                class="feather icon-edit mx-1"></i>Edit </a>
                                                        <a href="#" class="me-3 btn btn-sm btn-light-info"><i
                                                                class="feather icon-download mx-1"></i>Download </a>
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
                                                            src="{{ asset('storage/assets/images/user/avatar-9.jpg') }}"
                                                            alt="Generic placeholder image " />
                                                        <div class="ms-3 ms-sm-0 mb-3 mb-sm-0">
                                                            <ul
                                                                class="text-sm-center list-unstyled mt-2 mb-0 d-inline-block">
                                                                <li class="list-unstyled-item"><a href="#"
                                                                        class="link-secondary">1 Ticket</a></li>
                                                                <li class="list-unstyled-item"><a href="#"
                                                                        class="link-danger"><i class="fas fa-heart"></i>
                                                                        3</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="popup-trigger">
                                                        <div class="h5 font-weight-bold">John lui <small
                                                                class="badge bg-light-secondary ms-2">Replied</small></div>
                                                        <div class="help-sm-hidden">
                                                            <ul class="list-unstyled mt-2 mb-0 text-muted">
                                                                <li class="d-sm-inline-block d-block mt-1"><img
                                                                        src="../assets/images/admin/p1.jpg" alt=""
                                                                        class="wid-20 rounded me-2 img-fluid" />Piaf able
                                                                </li>
                                                                <li class="d-sm-inline-block d-block mt-1"><img
                                                                        src="../assets/images/user/avatar-5.jpg"
                                                                        alt=""
                                                                        class="wid-20 rounded me-2 img-fluid" />Assigned to
                                                                    <b>Robert alia</b>
                                                                </li>
                                                                <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>Updated
                                                                    22 hours ago</li>
                                                                <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">chat</i>9
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="h5 mt-3"><i
                                                                class="material-icons-two-tone f-16 me-1">lock</i> Theme
                                                            customisation issue</div>
                                                        <div class="help-md-hidden">
                                                            <div class="bg-body mb-3 p-3">
                                                                <h6><img src="../assets/images/user/avatar-5.jpg"
                                                                        alt=""
                                                                        class="wid-20 avatar me-2 rounded" />Last comment
                                                                    from
                                                                    <a href="#" class="link-secondary">Robert
                                                                        alia:</a>
                                                                </h6>
                                                                <p class="mb-0"><b>hello John lui</b>,<br />
                                                                    you need to create <b>"toolbar-options" div only</b>
                                                                    once in a page&nbsp;in your code,<br />
                                                                    this div fill found every "td" tag in your page,<br />
                                                                    just remove those things and also in option button add
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mt-2">
                                                        <a href="helpdesk-ticket-details.html"
                                                            class="me-2 btn btn-sm btn-light-primary"><i
                                                                class="feather icon-eye mx-1"></i>View Ticket</a>
                                                        <a href="#" class="me-3 btn btn-sm btn-light-danger"><i
                                                                class="feather icon-trash-2 mx-1"></i>Delete</a>
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
                                                                <li class="list-unstyled-item"><a href="#"
                                                                        class="link-secondary">1 Ticket</a></li>
                                                                <li class="list-unstyled-item"><a href="#"
                                                                        class="link-danger"><i class="fas fa-heart"></i>
                                                                        3</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="popup-trigger">
                                                        <div class="h5 font-weight-bold">John lui <small
                                                                class="badge bg-light-secondary ms-2">Replied</small></div>
                                                        <div class="help-sm-hidden">
                                                            <ul class="list-unstyled mt-2 mb-0 text-muted">
                                                                <li class="d-sm-inline-block d-block mt-1"><img
                                                                        src="../assets/images/admin/p1.jpg" alt=""
                                                                        class="wid-20 rounded me-2 img-fluid" />Piaf able
                                                                </li>
                                                                <li class="d-sm-inline-block d-block mt-1"><img
                                                                        src="../assets/images/user/avatar-5.jpg"
                                                                        alt=""
                                                                        class="wid-20 rounded me-2 img-fluid" />Assigned to
                                                                    <b>Robert alia</b>
                                                                </li>
                                                                <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>Updated
                                                                    22 hours ago</li>
                                                                <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">chat</i>9
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="h5 mt-3"><i
                                                                class="material-icons-two-tone f-16 me-1">lock</i> Theme
                                                            customisation issue</div>
                                                        <div class="help-md-hidden">
                                                            <div class="bg-body mb-3 p-3">
                                                                <h6><img src="../assets/images/user/avatar-5.jpg"
                                                                        alt=""
                                                                        class="wid-20 avatar me-2 rounded" />Last comment
                                                                    from
                                                                    <a href="#" class="link-secondary">Robert
                                                                        alia:</a>
                                                                </h6>
                                                                <p class="mb-0"><b>hello John lui</b>,<br />
                                                                    you need to create <b>"toolbar-options" div only</b>
                                                                    once in a page&nbsp;in your code,<br />
                                                                    this div fill found every "td" tag in your page,<br />
                                                                    just remove those things and also in option button add
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mt-2">
                                                        <a href="helpdesk-ticket-details.html"
                                                            class="me-2 btn btn-sm btn-light-primary"><i
                                                                class="feather icon-eye mx-1"></i>View Ticket</a>
                                                        <a href="#" class="me-3 btn btn-sm btn-light-danger"><i
                                                                class="feather icon-trash-2 mx-1"></i>Delete</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="p-b-15 m-b-10 task-list-item">
                                    <i class="task-icon bg-success"></i>
                                    <div class="card ticket-card open-ticket">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-auto mb-3 mb-sm-0">
                                                    <div class="d-sm-inline-block d-flex align-items-center">
                                                        <img class="media-object wid-60 img-radius"
                                                            src="{{ asset('storage/assets/images/user/avatar-2.jpg') }}"
                                                            alt="Generic placeholder image " />
                                                        <div class="ms-3 ms-sm-0 mb-3 mb-sm-0">
                                                            <ul
                                                                class="text-sm-center list-unstyled mt-2 mb-0 d-inline-block">
                                                                <li class="list-unstyled-item"><a href="#"
                                                                        class="link-secondary">1 Ticket</a></li>
                                                                <li class="list-unstyled-item"><a href="#"
                                                                        class="link-danger"><i class="fas fa-heart"></i>
                                                                        3</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="popup-trigger">
                                                        <div class="h5 font-weight-bold">John lui <small
                                                                class="badge bg-light-secondary ms-2">Replied</small></div>
                                                        <div class="help-sm-hidden">
                                                            <ul class="list-unstyled mt-2 mb-0 text-muted">
                                                                <li class="d-sm-inline-block d-block mt-1"><img
                                                                        src="../assets/images/admin/p1.jpg" alt=""
                                                                        class="wid-20 rounded me-2 img-fluid" />Piaf able
                                                                </li>
                                                                <li class="d-sm-inline-block d-block mt-1"><img
                                                                        src="../assets/images/user/avatar-5.jpg"
                                                                        alt=""
                                                                        class="wid-20 rounded me-2 img-fluid" />Assigned to
                                                                    <b>Robert alia</b>
                                                                </li>
                                                                <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>Updated
                                                                    22 hours ago</li>
                                                                <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">chat</i>9
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="h5 mt-3"><i
                                                                class="material-icons-two-tone f-16 me-1">lock</i> Theme
                                                            customisation issue</div>
                                                        <div class="help-md-hidden">
                                                            <div class="bg-body mb-3 p-3">
                                                                <h6><img src="../assets/images/user/avatar-5.jpg"
                                                                        alt=""
                                                                        class="wid-20 avatar me-2 rounded" />Last comment
                                                                    from
                                                                    <a href="#" class="link-secondary">Robert
                                                                        alia:</a>
                                                                </h6>
                                                                <p class="mb-0"><b>hello John lui</b>,<br />
                                                                    you need to create <b>"toolbar-options" div only</b>
                                                                    once in a page&nbsp;in your code,<br />
                                                                    this div fill found every "td" tag in your page,<br />
                                                                    just remove those things and also in option button add
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mt-2">
                                                        <a href="helpdesk-ticket-details.html"
                                                            class="me-2 btn btn-sm btn-light-primary"><i
                                                                class="feather icon-eye mx-1"></i>View Ticket</a>
                                                        <a href="#" class="me-3 btn btn-sm btn-light-danger"><i
                                                                class="feather icon-trash-2 mx-1"></i>Delete</a>
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
            </div>
            @include('modals.apd-upload-excel')
            <!-- [ Main Content ] end -->
        </div>
    </div>
    @include('partials.footer')

    @push('scripts')
        <script src="{{ asset('storage/assets/js/plugins/prism.js') }}"></script>
        <script src="{{ asset('storage/assets/js/plugins/quill.min.js') }}"></script>
          <!-- [Page Specific JS] start -->
    <script>
      var animateModal = document.getElementById('animateModal');
      animateModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var recipient = button.getAttribute('data-pc-animate');
        var modalTitle = animateModal.querySelector('.modal-title');
        // modalTitle.textContent = 'Animate Modal : ' + recipient;
        animateModal.classList.add('anim-' + recipient);
        if (recipient == 'let-me-in' || recipient == 'make-way' || recipient == 'slip-from-top') {
          document.body.classList.add('anim-' + recipient);
        }
      });
      animateModal.addEventListener('hidden.bs.modal', function (event) {
        removeClassByPrefix(animateModal, 'anim-');
        removeClassByPrefix(document.body, 'anim-');
      });

      function removeClassByPrefix(node, prefix) {
        for (let i = 0; i < node.classList.length; i++) {
          let value = node.classList[i];
          if (value.startsWith(prefix)) {
            node.classList.remove(value);
          }
        }
      }
    </script>
    <!-- [Page Specific JS] end -->
    @endpush

    @include('partials.theme')

@endsection
