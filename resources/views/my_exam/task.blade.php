@extends('layouts.app')
@section('title', $session->exam_main_name . ' - ' . $session->exam_main_notification)
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
                                @foreach ($auditDetails as $audit)
                                    @if ($audit->task_type == 'exam_metadata')
                                        <li class="task-list-item">
                                            <i class="feather icon-check f-w-600 task-icon bg-success"></i>
                                            <div class="card ticket-card open-ticket">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-sm-auto mb-3 mb-sm-0">
                                                            <div class="d-sm-inline-block d-flex align-items-center">
                                                                @php
                                                                    $user = App\Models\DepartmentOfficial::find(
                                                                        $audit->user_id,
                                                                    );
                                                                @endphp
                                                                <img loading="lazy" class="media-object wid-60 img-radius"
                                                                    src="{{ asset('storage/' . $user->profile_image) }}"
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
                                                                        class="badge bg-light-secondary ms-2">created</small>
                                                                </div>
                                                                <div class="help-sm-hidden">
                                                                    <ul class="list-unstyled mt-2 mb-0 text-muted">

                                                                        <li class="d-sm-inline-block d-block mt-1"><img
                                                                                src="../assets/images/user/avatar-5.jpg"
                                                                                alt=""
                                                                                class="wid-20 rounded me-2 img-fluid" />Done
                                                                            by
                                                                            <b>{{ json_decode($audit->metadata)->user_name }}
                                                                            </b>
                                                                        </li>
                                                                        <li class="d-sm-inline-block d-block mt-1"><i
                                                                                class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>
                                                                            {{ \Carbon\Carbon::parse($audit->created_at)->format('d-m-Y h:i A') }}
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                                <div class="h5 mt-3"><i
                                                                        class="material-icons-two-tone f-16 me-1">apartment</i>
                                                                    {{ $audit->department }}</div>

                                                            </div>
                                                            <div class="mt-2">
                                                                <a href="{{ route('current-exam.show', $session->exam_main_id) }}"
                                                                    class="me-2 btn btn-sm btn-light-primary"><i
                                                                        class="feather icon-eye mx-1"></i>View Exam</a>
                                                                @hasPermission('current-exam.edit')
                                                                    <a href="{{ route('current-exam.edit', $session->exam_main_id) }}"
                                                                        class="me-3 btn btn-sm btn-light-warning"><i
                                                                            class="feather icon-edit mx-1"></i>Edit Exam</a>
                                                                @endhasPermission
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endif
                                @endforeach

                                @if (session('auth_role') == 'headquarters' && $current_user->custom_role != 'VDS')
                                    @php
                                        $is_apd_upload = $expectedCandidatesUpload !== null;

                                        $metadata = null;
                                        if ($expectedCandidatesUpload !== null) {
                                            $metadata = is_string($expectedCandidatesUpload->metadata)
                                                ? json_decode($expectedCandidatesUpload->metadata)
                                                : (object) $expectedCandidatesUpload->metadata;
                                            $expectedCandidatesUpload = (object) $expectedCandidatesUpload;
                                        }

                                        $user = $is_apd_upload
                                            ? App\Models\DepartmentOfficial::find($expectedCandidatesUpload->user_id)
                                            : null;
                                        $profileImage =
                                            $user && !empty($user->profile_image)
                                                ? asset('storage/' . $user->profile_image)
                                                : asset('storage/assets/images/user/avatar-1.jpg');
                                        // Set dynamic badge text and color
                                        $uploadStatus = $is_apd_upload ? 'Uploaded' : 'Pending';
                                        $badgeClass = $is_apd_upload ? 'bg-light-secondary' : 'bg-danger';

                                    @endphp
                                    <li class="task-list-item">
                                        <i
                                            class="task-icon {{ $is_apd_upload ? 'feather icon-check f-w-600 bg-success' : 'bg-danger' }}"></i>
                                        <div class="card ticket-card open-ticket">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-sm-auto mb-3 mb-sm-0">
                                                        <div class="d-sm-inline-block d-flex align-items-center">
                                                            <img loading="lazy" class="media-object wid-60 img-radius"
                                                                src="{{ $profileImage }}"
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
                                                            <div class="h5 font-weight-bold">Tentative Candidates
                                                                CSV
                                                                <small
                                                                    class="badge {{ $badgeClass }} ms-2">{{ $uploadStatus }}</small>
                                                            </div>
                                                            <div class="help-sm-hidden">
                                                                <ul class="list-unstyled mt-2 mb-0 text-muted">

                                                                    <li class="d-sm-inline-block d-block mt-1"><img
                                                                            src="../assets/images/user/avatar-5.jpg"
                                                                            alt=""
                                                                            class="wid-20 rounded me-2 img-fluid" />Done
                                                                        by
                                                                        <b>{{ $is_apd_upload ? $metadata->user_name ?? '' : ' Unknown ' }}</b>
                                                                    </li>
                                                                    <li class="d-sm-inline-block d-block mt-1"><i
                                                                            class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>
                                                                        {{ $is_apd_upload ? \Carbon\Carbon::parse($expectedCandidatesUpload->updated_at)->format('d-m-Y h:i A') : ' ' }}
                                                                    </li>

                                                                </ul>
                                                            </div>
                                                            <div class="h5 mt-3"><i
                                                                    class="material-icons-two-tone f-16 me-1">apartment</i>
                                                                {{ $is_apd_upload ? $expectedCandidatesUpload->department : 'APD - Section Officer' }}
                                                            </div>
                                                        </div>
                                                        <div class="mt-2">
                                                            @hasPermission('upload-candidates-csv')
                                                                @if (isset($metadata->uploaded_csv_link))
                                                                    <a href="#" class="me-2 btn btn-sm btn-light-info"
                                                                        data-pc-animate="just-me" data-bs-toggle="modal"
                                                                        data-bs-target="#animateModal"><i
                                                                            class="feather icon-edit mx-1"></i>Edit
                                                                    </a>
                                                                @else
                                                                    <a href="#"
                                                                        class="me-2 btn btn-sm btn-light-primary m-2"
                                                                        data-pc-animate="just-me" data-bs-toggle="modal"
                                                                        data-bs-target="#animateModal"><i
                                                                            class="feather icon-upload mx-1 "></i>Upload
                                                                    </a>
                                                                @endif
                                                            @endhasPermission
                                                            @hasPermission('download-expected-candidates')
                                                                @if ($is_apd_upload)
                                                                    <a href="{{ $metadata->uploaded_csv_link }}"
                                                                        class="me-3 btn btn-sm btn-light-warning"><i
                                                                            class="feather icon-download mx-1"></i>Download
                                                                    </a>
                                                                @endif
                                                            @endhasPermission
                                                            @hasPermission('upload-candidates-csv')
                                                                @if (!empty($metadata->failed_csv_link))
                                                                    <a href="{{ $metadata->failed_csv_link }}"
                                                                        class="me-3 btn btn-sm btn-light-danger">
                                                                        <i class="feather icon-download mx-1"></i>Failed
                                                                        Records
                                                                    </a>
                                                                @endif
                                                            @endhasPermission
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    @php
                                        $is_id_updated_count = $candidatesCountIncrease !== null;

                                        $metadata = null;
                                        if ($candidatesCountIncrease !== null) {
                                            $metadata = is_string($candidatesCountIncrease->metadata)
                                                ? json_decode($candidatesCountIncrease->metadata)
                                                : (object) $candidatesCountIncrease->metadata;
                                            $candidatesCountIncrease = (object) $candidatesCountIncrease;
                                        }

                                        $user = $is_id_updated_count
                                            ? App\Models\DepartmentOfficial::find($candidatesCountIncrease->user_id)
                                            : null;
                                        $profileImage =
                                            $user && !empty($user->profile_image)
                                                ? asset('storage/' . $user->profile_image)
                                                : asset('storage/assets/images/user/avatar-6.jpg');
                                        // Set dynamic badge text and color
                                        $uploadStatus = $is_id_updated_count ? 'Updated' : 'Pending';
                                        $badgeClass = $is_id_updated_count ? 'bg-light-secondary' : 'bg-danger';

                                    @endphp
                                    <li class="task-list-item">
                                        <i
                                            class="task-icon {{ $is_id_updated_count ? 'feather icon-check f-w-600 bg-success' : 'bg-danger' }}"></i>
                                        <div class="card ticket-card open-ticket">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-sm-auto mb-3 mb-sm-0">
                                                        <div class="d-sm-inline-block d-flex align-items-center">
                                                            <img loading="lazy" class="media-object wid-60 img-radius"
                                                                src="{{ $profileImage }}"
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
                                                            <div class="h5 font-weight-bold">Increase Candidates
                                                                Count
                                                                <small
                                                                    class="badge {{ $badgeClass }} ms-2">{{ $uploadStatus }}</small>
                                                            </div>
                                                            <div class="help-sm-hidden">
                                                                <ul class="list-unstyled mt-2 mb-0 text-muted">

                                                                    <li class="d-sm-inline-block d-block mt-1"><img
                                                                            src="../assets/images/user/avatar-5.jpg"
                                                                            alt=""
                                                                            class="wid-20 rounded me-2 img-fluid" />Done
                                                                        by
                                                                        <b>{{ $is_id_updated_count ? $metadata->user_name ?? '' : ' Unknown ' }}</b>
                                                                    </li>
                                                                    <li class="d-sm-inline-block d-block mt-1"><i
                                                                            class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>
                                                                        {{ $is_id_updated_count ? \Carbon\Carbon::parse($candidatesCountIncrease->updated_at)->format('d-m-Y h:i A') : '' }}
                                                                    </li>

                                                                </ul>
                                                            </div>
                                                            <div class="h5 mt-3"><i
                                                                    class="material-icons-two-tone f-16 me-1">apartment</i>
                                                                {{ $is_id_updated_count ? $candidatesCountIncrease->department : 'ID - Section Officer' }}
                                                            </div>
                                                        </div>
                                                        <div class="mt-2">
                                                            @hasPermission('update-percentage')
                                                                <a href="#" data-pc-animate="just-me"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#increaseCandiadteModal"
                                                                    class="me-2 btn btn-sm btn-light-primary m-2"><i
                                                                        class="feather icon-chevrons-up mx-1"></i>Increase
                                                                    Count</a>
                                                            @endhasPermission
                                                            @hasPermission('download-candidates-count-updated')
                                                                @if ($is_id_updated_count)
                                                                    <a href="{{ route('id-candidates.download-updated-count-csv', $session->exam_main_no) }}"
                                                                        class="me-2 btn btn-sm btn-light-info m-2"><i
                                                                            class="feather icon-download mx-1"></i>Download
                                                                        CSV</a>
                                                                @endif
                                                            @endhasPermission
                                                            @hasPermission('update-percentage')
                                                                <a href="{{ route('id-candidates.intimateCollectorate', $session->exam_main_no) }}"
                                                                    class="me-3 btn btn-sm btn-light-warning m-2"><i
                                                                        class="feather icon-navigation mx-1"></i>Send
                                                                    Intimation</a>
                                                            @endhasPermission
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endif
                                @hasPermission('showVenueIntimationForm')
                                    @php
                                        $is_venue_consent_sent = $sendExamVenueConsent !== null;

                                        $metadata = null;
                                        if ($sendExamVenueConsent !== null) {
                                            $metadata = is_string($sendExamVenueConsent->metadata)
                                                ? json_decode($sendExamVenueConsent->metadata)
                                                : (object) $sendExamVenueConsent->metadata;
                                            $sendExamVenueConsent = (object) $sendExamVenueConsent;
                                        }
                                        $user = $is_venue_consent_sent
                                            ? App\Models\District::find($sendExamVenueConsent->user_id)
                                            : null;
                                        $profileImage =
                                            $user && !empty($user->profile_image)
                                                ? asset('storage/' . $user->profile_image)
                                                : asset('storage/assets/images/user/avatar-7.jpg');
                                        // Set dynamic badge text and color
                                        $uploadStatus = $is_venue_consent_sent ? 'Selected' : 'Pending';
                                        $badgeClass = $is_venue_consent_sent ? 'bg-light-secondary' : 'bg-danger';
                                    @endphp
                                    <li class="task-list-item">
                                        <i
                                            class="task-icon {{ $is_venue_consent_sent ? 'feather icon-check f-w-600 bg-success' : 'bg-danger' }}"></i>
                                        <div class="card ticket-card open-ticket">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-sm-auto mb-3 mb-sm-0">
                                                        <div class="d-sm-inline-block d-flex align-items-center">
                                                            <img loading="lazy" class="media-object wid-60 img-radius"
                                                                src="{{ $profileImage }}" alt="Generic placeholder image " />
                                                            <div class="ms-3 ms-sm-0 mb-3 mb-sm-0">
                                                                <ul
                                                                    class="text-sm-center list-unstyled mt-2 mb-0 d-inline-block">
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="popup-trigger">
                                                            <div class="h5 font-weight-bold">Select Venues
                                                                <small
                                                                    class="badge {{ $badgeClass }} ms-2">{{ $uploadStatus }}</small>
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
                                                                        <b>{{ $is_venue_consent_sent ? $metadata->user_name ?? '' : ' Unknown ' }}</b>
                                                                    </li>
                                                                    <li class="d-sm-inline-block d-block mt-1"><i
                                                                            class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>
                                                                        {{ $is_venue_consent_sent ? \Carbon\Carbon::parse($sendExamVenueConsent->updated_at)->format('d-m-Y h:i A') : ' ' }}
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            <div class="h5 mt-3"><i
                                                                    class="material-icons-two-tone f-16 me-1">apartment</i>
                                                                District Collectorate</div>
                                                        </div>
                                                        <div class="mt-2">
                                                            @hasPermission('showVenueIntimationForm')
                                                                <a target="_blank"
                                                                    href="{{ route('district-candidates.showVenueIntimationForm', $session->exam_main_no) }}"
                                                                    class="me-2 btn btn-sm btn-light-info"><i
                                                                        class="feather icon-check-circle mx-1"></i>Select
                                                                    Venues</a>
                                                                {{-- <a href="#" data-pc-animate="blur" data-bs-toggle="modal"
                                                                    data-bs-target="#sendConsentMailModel"
                                                                    class="me-3 btn btn-sm btn-light-warning"><i
                                                                        class="feather icon-navigation mx-1"></i>Send
                                                                    Intimation</a> --}}
                                                            @endhasPermission
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endhasPermission
                                @if (session('auth_role') == 'venue')
                                    @php

                                        $profileImage =
                                            $venueConsents && !empty($venueConsents->profile_image)
                                                ? asset('storage/' . $venueConsents->profile_image)
                                                : asset('storage/assets/images/user/venue.png');
                                        // Set dynamic badge text and color
                                        $uploadStatus = $venueConsents ? 'Updated' : 'Pending';
                                        $badgeClass =
                                            $venueConsents->consent_status == 'accepted'
                                                ? 'bg-light-secondary'
                                                : 'bg-danger';

                                    @endphp
                                    <li class="task-list-item">
                                        <i
                                            class="task-icon {{ $venueConsents->consent_status == 'accepted' ? 'feather icon-check f-w-600 bg-success' : 'bg-danger' }}"></i>
                                        <div class="card ticket-card open-ticket">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-sm-auto mb-3 mb-sm-0">
                                                        <div class="d-sm-inline-block d-flex align-items-center">
                                                            <img loading="lazy" class="media-object wid-60 img-radius"
                                                                src="{{ $profileImage }}"
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
                                                            <div class="h5 font-weight-bold">Give Consent & Assign CI<small
                                                                    class="badge {{ $badgeClass }} ms-2">{{ $venueConsents->consent_status ?? '' }}</small>
                                                            </div>
                                                            <div class="help-sm-hidden">
                                                                <ul class="list-unstyled mt-2 mb-0 text-muted">

                                                                    <li class="d-sm-inline-block d-block mt-1"><img
                                                                            src="../assets/images/user/avatar-5.jpg"
                                                                            alt=""
                                                                            class="wid-20 rounded me-2 img-fluid" />Done by
                                                                        <b>{{ $venueConsents->venueName ?? 'venue' }}</b>
                                                                    </li>
                                                                    <li class="d-sm-inline-block d-block mt-1"><i
                                                                            class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>
                                                                        {{ \Carbon\Carbon::parse($venueConsents->updated_at ?? '')->format('d-m-Y h:i A') }}
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            <div class="h5 mt-3"><i
                                                                    class="material-icons-two-tone f-16 me-1">apartment</i>
                                                                Venue</div>
                                                        </div>
                                                        <div class="mt-2">
                                                            @if (isset($venueConsents->consent_status))
                                                                <a href="{{ route('venues.show-venue-consent', $session->exam_main_no) }}"
                                                                    class="me-2 btn btn-sm btn-light-primary m-2"><i
                                                                        class="feather icon-eye mx-1"></i>View</a>
                                                            @endif
                                                            @if (isset($venueConsents->consent_status) && $venueConsents->consent_status == 'requested')
                                                                <a href="{{ route('venues.venue-consent', $session->exam_main_no) }}"
                                                                    class="me-2 btn btn-sm btn-light-info"><i
                                                                        class="feather icon-check-circle mx-1"></i>Add
                                                                    Hall</a>
                                                            @endif
                                                            @if (isset($venueConsents->consent_status) && $venueConsents->consent_status == 'accepted')
                                                                <a href="{{ route('venues.venue-consent', $session->exam_main_no) }}"
                                                                    class="me-3 btn btn-sm btn-light-warning"><i
                                                                        class="feather icon-edit mx-1"></i>Edit Hall</a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endif
                                @if (session('auth_role') == 'headquarters' && $current_user->custom_role != 'VDS')
                                    @php
                                        $is_halls_confirmed = $examVenueHallConfirmation !== null;

                                        $metadata = null;
                                        if ($examVenueHallConfirmation !== null) {
                                            $metadata = is_string($examVenueHallConfirmation->metadata)
                                                ? json_decode($examVenueHallConfirmation->metadata)
                                                : (object) $examVenueHallConfirmation->metadata;
                                            $examVenueHallConfirmation = (object) $examVenueHallConfirmation;
                                        }

                                        $user = $is_halls_confirmed
                                            ? App\Models\DepartmentOfficial::find($examVenueHallConfirmation->user_id)
                                            : null;
                                        $profileImage =
                                            $user && !empty($user->profile_image)
                                                ? asset('storage/' . $user->profile_image)
                                                : asset('storage/assets/images/user/avatar-9.jpg');
                                        // Set dynamic badge text and color
                                        $uploadStatus = $is_halls_confirmed ? 'Confirmed' : 'Pending';
                                        $badgeClass = $is_halls_confirmed ? 'bg-light-secondary' : 'bg-danger';

                                    @endphp
                                    <li class="task-list-item">
                                        <i
                                            class="task-icon {{ $is_halls_confirmed ? 'feather icon-check f-w-600 bg-success' : 'bg-danger' }}"></i>
                                        <div class="card ticket-card open-ticket">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-sm-auto mb-3 mb-sm-0">
                                                        <div class="d-sm-inline-block d-flex align-items-center">
                                                            <img loading="lazy" class="media-object wid-60 img-radius"
                                                                src="{{ $profileImage }}"
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
                                                            <div class="h5 font-weight-bold">Confirm Venues <small
                                                                    class="badge {{ $badgeClass }} ms-2">{{ $uploadStatus }}</small>
                                                            </div>
                                                            <div class="help-sm-hidden">
                                                                <ul class="list-unstyled mt-2 mb-0 text-muted">

                                                                    <li class="d-sm-inline-block d-block mt-1"><img
                                                                            src="../assets/images/user/avatar-5.jpg"
                                                                            alt=""
                                                                            class="wid-20 rounded me-2 img-fluid" />Done by
                                                                        <b>{{ $is_halls_confirmed ? $metadata->user_name ?? '' : ' Unknown ' }}</b>
                                                                    </li>
                                                                    <li class="d-sm-inline-block d-block mt-1"><i
                                                                            class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>
                                                                        {{ $is_halls_confirmed ? \Carbon\Carbon::parse($examVenueHallConfirmation->updated_at)->format('d-m-Y h:i A') : ' ' }}
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            <div class="h5 mt-3"><i
                                                                    class="material-icons-two-tone f-16 me-1">apartment</i>
                                                                ID
                                                                -Section Officer</div>
                                                        </div>
                                                        <div class="mt-2">
                                                            @hasPermission('confirmExamVenueHalls')
                                                                <a target="_blank"
                                                                    href="{{ route('id-candidates.show-venue-confirmation-form', $session->exam_main_no) }}"
                                                                    class="me-2 btn btn-sm btn-light-primary m-2"><i
                                                                        class="feather icon-eye mx-1"></i>Review Venues</a>
                                                            @endhasPermission
                                                            @hasPermission('downlaodConfirmedExamHalls')
                                                                @if ($is_halls_confirmed)
                                                                    <a href="{{ route('id-candidates.export-confirmed-halls', $session->exam_main_no) }}"
                                                                        class="me-2 btn btn-sm btn-light-info"><i
                                                                            class="feather icon-download mx-1"></i>Download
                                                                        CSV</a>
                                                                @endif
                                                            @endhasPermission
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    @php
                                        $is_finalize_halls_upload = $audit->task_type == 'apd_finalize_halls_upload';
                                    @endphp
                                    @php
                                        $is_finalize_halls_upload = $apdFinalizeHallsUpload !== null;

                                        $metadata = null;
                                        if ($apdFinalizeHallsUpload !== null) {
                                            $metadata = is_string($apdFinalizeHallsUpload->metadata)
                                                ? json_decode($apdFinalizeHallsUpload->metadata)
                                                : (object) $apdFinalizeHallsUpload->metadata;
                                            $apdFinalizeHallsUpload = (object) $apdFinalizeHallsUpload;
                                        }
                                        $user = $is_finalize_halls_upload
                                            ? App\Models\DepartmentOfficial::find($apdFinalizeHallsUpload->user_id)
                                            : null;
                                        $profileImage =
                                            $user && !empty($user->profile_image)
                                                ? asset('storage/' . $user->profile_image)
                                                : asset('storage/assets/images/user/avatar-1.jpg');
                                        // Set dynamic badge text and color
                                        $uploadStatus = $is_finalize_halls_upload ? 'Uploaded' : 'Pending';
                                        $badgeClass = $is_finalize_halls_upload ? 'bg-light-secondary' : 'bg-danger';

                                    @endphp
                                    <li class="task-list-item">
                                        <i
                                            class="task-icon {{ $is_finalize_halls_upload ? 'feather icon-check f-w-600 bg-success' : 'bg-danger' }}"></i>
                                        <div class="card ticket-card open-ticket">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-sm-auto mb-3 mb-sm-0">
                                                        <div class="d-sm-inline-block d-flex align-items-center">
                                                            <img loading="lazy" class="media-object wid-60 img-radius"
                                                                src="{{ $profileImage }}"
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
                                                            <div class="h5 font-weight-bold">Finalize Exam Halls
                                                                <small
                                                                    class="badge {{ $badgeClass }} ms-2">{{ $uploadStatus }}</small>
                                                            </div>
                                                            <div class="help-sm-hidden">
                                                                <ul class="list-unstyled mt-2 mb-0 text-muted">
                                                                    <li class="d-sm-inline-block d-block mt-1"><img
                                                                            src="../assets/images/user/avatar-5.jpg"
                                                                            alt=""
                                                                            class="wid-20 rounded me-2 img-fluid" />Done
                                                                        by
                                                                        <b>{{ $is_finalize_halls_upload ? $metadata->user_name ?? '' : ' Unknown ' }}</b>
                                                                    </li>
                                                                    <li class="d-sm-inline-block d-block mt-1"><i
                                                                            class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>
                                                                        {{ $is_finalize_halls_upload ? \Carbon\Carbon::parse($apdFinalizeHallsUpload->updated_at)->format('d-m-Y h:i A') : ' ' }}
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            <div class="h5 mt-3"><i
                                                                    class="material-icons-two-tone f-16 me-1">apartment</i>
                                                                {{ $is_finalize_halls_upload ? $apdFinalizeHallsUpload->department : 'APD - Section Officer' }}
                                                            </div>
                                                            <div class="mt-2">
                                                                @hasPermission('finalize-csv')
                                                                    @if (isset($metadata->uploaded_csv_link))
                                                                        <a href="#"
                                                                            class="me-2 btn btn-sm btn-light-info"
                                                                            data-pc-animate="just-me" data-bs-toggle="modal"
                                                                            data-bs-target="#apdFinalizeCandidate"><i
                                                                                class="feather icon-edit mx-1"></i>Edit
                                                                        </a>
                                                                    @else
                                                                        <a href="#"
                                                                            class="me-2 btn btn-sm btn-light-primary m-2"
                                                                            data-pc-animate="just-me" data-bs-toggle="modal"
                                                                            data-bs-target="#apdFinalizeCandidate"><i
                                                                                class="feather icon-upload mx-1 "></i>Upload
                                                                        </a>
                                                                    @endif
                                                                @endhasPermission
                                                                @hasPermission('download-finalized-halls-csv')
                                                                    @if ($is_finalize_halls_upload)
                                                                        <a href="{{ $metadata->uploaded_csv_link }}"
                                                                            class="me-3 btn btn-sm btn-light-warning"><i
                                                                                class="feather icon-download mx-1"></i>Download
                                                                        </a>
                                                                    @endif
                                                                @endhasPermission
                                                                @hasPermission('finalize-csv')
                                                                    @if (!empty($metadata->failed_csv_link))
                                                                        <a href="{{ $metadata->failed_csv_link }}"
                                                                            class="me-3 btn btn-sm btn-light-danger">
                                                                            <i class="feather icon-download mx-1"></i>Failed
                                                                            Records
                                                                        </a>
                                                                    @endif
                                                                @endhasPermission
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    </li>
                                    @php
                                        $is_qd_qr_upload = $examMaterialsUpdate !== null;
                                        $metadata = null;
                                        if ($examMaterialsUpdate !== null) {
                                            $metadata = is_string($examMaterialsUpdate->metadata)
                                                ? json_decode($examMaterialsUpdate->metadata)
                                                : (object) $examMaterialsUpdate->metadata;
                                            $examMaterialsUpdate = (object) $examMaterialsUpdate;
                                        }
                                        $user = $is_qd_qr_upload
                                            ? App\Models\DepartmentOfficial::find($examMaterialsUpdate->user_id)
                                            : null;
                                        $profileImage =
                                            $user && !empty($user->profile_image)
                                                ? asset('storage/' . $user->profile_image)
                                                : asset('storage/assets/images/user/avatar-3.jpg');
                                        // Set dynamic badge text and color
                                        $uploadStatus = $is_qd_qr_upload ? 'Uploaded' : 'Pending';
                                        $badgeClass = $is_qd_qr_upload ? 'bg-light-secondary' : 'bg-danger';
                                    @endphp
                                    <li class="task-list-item">
                                        <i
                                            class="task-icon {{ $is_qd_qr_upload ? 'feather icon-check f-w-600 bg-success' : 'bg-danger' }}"></i>
                                        <div class="card ticket-card open-ticket">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-sm-auto mb-3 mb-sm-0">
                                                        <div class="d-sm-inline-block d-flex align-items-center">
                                                            <img loading="lazy" class="media-object wid-60 img-radius"
                                                                src="{{ $profileImage }}"
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
                                                            <div class="h5 font-weight-bold">Update Exam Materials
                                                                Details
                                                                <small
                                                                    class="badge {{ $badgeClass }} ms-2">{{ $uploadStatus }}</small>
                                                            </div>
                                                            <div class="help-sm-hidden">
                                                                <ul class="list-unstyled mt-2 mb-0 text-muted">

                                                                    <li class="d-sm-inline-block d-block mt-1"><img
                                                                            src="../assets/images/user/avatar-4.jpg"
                                                                            alt=""
                                                                            class="wid-20 rounded me-2 img-fluid" /> Done
                                                                        by
                                                                        <b>{{ $is_qd_qr_upload ? $metadata->user_name ?? '' : ' Unknown ' }}</b>
                                                                    </li>
                                                                    <li class="d-sm-inline-block d-block mt-1"><i
                                                                            class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>
                                                                        {{ $is_qd_qr_upload ? \Carbon\Carbon::parse($examMaterialsUpdate->updated_at)->format('d-m-Y h:i A') : ' ' }}
                                                                    </li>

                                                                </ul>
                                                            </div>
                                                            <div class="h5 mt-3"><i
                                                                    class="material-icons-two-tone f-16 me-1">apartment</i>
                                                                {{ $is_qd_qr_upload ? $examMaterialsUpdate->department : 'QD - Section Officer' }}
                                                            </div>
                                                        </div>
                                                        <div class="mt-2">
                                                            @hasPermission('upload-exam-materials-csv')
                                                                <a href="{{ route('exam-materials.index', $session->exam_main_no) }}"
                                                                    class="me-2 btn btn-sm btn-light-primary m-2">
                                                                    <i class="feather icon-upload mx-1 "></i>Upload </a>
                                                            @endhasPermission
                                                            @hasPermission('download-exam-materials-uploaded')
                                                                @if ($is_qd_qr_upload)
                                                                    <a href="{{ $metadata->uploaded_csv_link }}"
                                                                        class="me-3 btn btn-sm btn-light-warning"><i
                                                                            class="feather icon-download mx-1"></i>Download
                                                                    </a>
                                                                @endif
                                                            @endhasPermission
                                                            @hasPermission('upload-exam-materials-csv')
                                                                @if (isset($metadata->failed_csv_link) &&
                                                                        file_exists(public_path(str_replace(url('/'), '', $metadata->failed_csv_link))))
                                                                    <a href="{{ $metadata->failed_csv_link }}"
                                                                        class="me-3 btn btn-sm btn-light-danger">
                                                                        <i class="feather icon-download mx-1"></i>Failed
                                                                        Records
                                                                    </a>
                                                                @endif
                                                            @endhasPermission
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    </li>
                                @endif
                                @hasPermission('receive-exam-materials-from-printer')
                                    @php
                                        $is_received_printer_to_district = $receiveMaterialsPrinterToDistrict !== null;

                                        $metadata = null;
                                        if ($receiveMaterialsPrinterToDistrict !== null) {
                                            $metadata = is_string($receiveMaterialsPrinterToDistrict->metadata)
                                                ? json_decode($receiveMaterialsPrinterToDistrict->metadata)
                                                : (object) $receiveMaterialsPrinterToDistrict->metadata;
                                            $receiveMaterialsPrinterToDistrict = (object) $receiveMaterialsPrinterToDistrict;
                                        }

                                        $user = $is_received_printer_to_district
                                            ? App\Models\TreasuryOfficer::find(
                                                $receiveMaterialsPrinterToDistrict->user_id,
                                            )
                                            : null;
                                        $profileImage =
                                            $user && !empty($user->profile_image)
                                                ? asset('storage/' . $user->profile_image)
                                                : asset('storage/assets/images/user/avatar-3.jpg');
                                        // Set dynamic badge text and color
                                        $uploadStatus = $is_received_printer_to_district ? 'Received' : 'Pending';
                                        $badgeClass = $is_received_printer_to_district
                                            ? 'bg-light-secondary'
                                            : 'bg-danger';
                                    @endphp
                                    <li class="task-list-item">
                                        <i
                                            class="task-icon {{ $is_received_printer_to_district ? 'feather icon-check f-w-600 bg-success' : 'bg-danger' }}"></i>
                                        <div class="card ticket-card open-ticket">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-sm-auto mb-3 mb-sm-0">
                                                        <div class="d-sm-inline-block d-flex align-items-center">
                                                            <img loading="lazy" class="media-object wid-60 img-radius"
                                                                src="{{ $profileImage }}" alt="Generic placeholder image " />
                                                            <div class="ms-3 ms-sm-0 mb-3 mb-sm-0">
                                                                <ul
                                                                    class="text-sm-center list-unstyled mt-2 mb-0 d-inline-block">
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="popup-trigger">
                                                            <div class="h5 font-weight-bold">Receive Exam Materials From
                                                                Printer
                                                                <small
                                                                    class="badge {{ $badgeClass }} ms-2">{{ $uploadStatus }}</small>
                                                            </div>
                                                            <div class="help-sm-hidden">
                                                                <ul class="list-unstyled mt-2 mb-0 text-muted">

                                                                    <li class="d-sm-inline-block d-block mt-1"><img
                                                                            src="../assets/images/user/avatar-4.jpg"
                                                                            alt=""
                                                                            class="wid-20 rounded me-2 img-fluid" />Done by
                                                                        <b>{{ $is_received_printer_to_district ? $metadata->user_name ?? '' : ' Unknown ' }}</b>
                                                                    </li>
                                                                    <li class="d-sm-inline-block d-block mt-1"><i
                                                                            class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>
                                                                        {{ $is_received_printer_to_district ? \Carbon\Carbon::parse($receiveMaterialsPrinterToDistrict->updated_at)->format('d-m-Y h:i A') : ' ' }}
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            <div class="h5 mt-3"><i
                                                                    class="material-icons-two-tone f-16 me-1">apartment</i>
                                                                District - Treasury</div>
                                                        </div>
                                                        <div class="mt-2">
                                                            <a href="{{ route('receive-exam-materials.printer-to-disitrict-treasury', $session->exam_main_no) }}"
                                                                class="me-2 btn btn-sm btn-light-info"><i
                                                                    class="feather icon-info mx-1"></i>Verify</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endhasPermission
                                @if (session('auth_role') == 'headquarters' && $current_user->custom_role != 'VDS')
                                    @php
                                        $is_received_printer_to_hq = $receiveMaterialsPrinterToHQ !== null;
                                        $metadata = null;
                                        if ($receiveMaterialsPrinterToHQ !== null) {
                                            $metadata = is_string($receiveMaterialsPrinterToHQ->metadata)
                                                ? json_decode($receiveMaterialsPrinterToHQ->metadata)
                                                : (object) $receiveMaterialsPrinterToHQ->metadata;
                                            $receiveMaterialsPrinterToHQ = (object) $receiveMaterialsPrinterToHQ;
                                        }
                                        $user = $is_received_printer_to_hq
                                            ? App\Models\TreasuryOfficer::find($receiveMaterialsPrinterToHQ->user_id)
                                            : null;
                                        $profileImage =
                                            $user && !empty($user->profile_image)
                                                ? asset('storage/' . $user->profile_image)
                                                : asset('storage/assets/images/user/avatar-3.jpg');
                                        // Set dynamic badge text and color
                                        $uploadStatus = $is_received_printer_to_hq ? 'Received' : 'Pending';
                                        $badgeClass = $is_received_printer_to_hq ? 'bg-light-secondary' : 'bg-danger';
                                    @endphp
                                    <li class="task-list-item">
                                        <i
                                            class="task-icon {{ $is_received_printer_to_hq ? 'feather icon-check f-w-600 bg-success' : 'bg-danger' }}"></i>
                                        <div class="card ticket-card open-ticket">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-sm-auto mb-3 mb-sm-0">
                                                        <div class="d-sm-inline-block d-flex align-items-center">
                                                            <img loading="lazy" class="media-object wid-60 img-radius"
                                                                src="{{ $profileImage }}"
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
                                                            <div class="h5 font-weight-bold">Receive Exam Materials From
                                                                Printer
                                                                <small
                                                                    class="badge {{ $badgeClass }} ms-2">{{ $uploadStatus }}</small>
                                                            </div>
                                                            <div class="help-sm-hidden">
                                                                <ul class="list-unstyled mt-2 mb-0 text-muted">
                                                                    <li class="d-sm-inline-block d-block mt-1"><img
                                                                            src="../assets/images/user/avatar-4.jpg"
                                                                            alt=""
                                                                            class="wid-20 rounded me-2 img-fluid" />Done by
                                                                        <b>{{ $is_received_printer_to_hq ? $metadata->user_name ?? '' : ' Unknown ' }}</b>
                                                                    </li>
                                                                    <li class="d-sm-inline-block d-block mt-1"><i
                                                                            class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>
                                                                        {{ $is_received_printer_to_hq ? \Carbon\Carbon::parse($receiveMaterialsPrinterToHQ->updated_at)->format('d-m-Y h:i A') : ' ' }}
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            <div class="h5 mt-3"><i
                                                                    class="material-icons-two-tone f-16 me-1">apartment</i>
                                                                {{ $is_received_printer_to_hq ? $receiveMaterialsPrinterToHQ->department : 'QD - Section Officer' }}
                                                            </div>
                                                        </div>
                                                        <div class="mt-2">
                                                            @hasPermission('receive-materials-printer-to-hq')
                                                                <a href="{{ route('receive-exam-materials.printer-to-hq-treasury', $session->exam_main_no) }}"
                                                                    class="me-2 btn btn-sm btn-light-info"><i
                                                                        class="feather icon-info mx-1"></i>Verify</a>
                                                            @endhasPermission
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endif
                                @hasPermission('download-meeting-qr')
                                    @php
                                        $is_meeting_qr_created = $meetingCodeGen !== null;
                                        $user = $is_meeting_qr_created
                                            ? App\Models\District::where(
                                                'district_code',
                                                $meetingCodeGen->district_code,
                                            )->first()
                                            : null;
                                        $profileImage =
                                            $user && !empty($user->profile_image)
                                                ? asset('storage/' . $user->profile_image)
                                                : asset('storage/assets/images/user/avatar-7.jpg');
                                        // Set dynamic badge text and color
                                        $uploadStatus = $is_meeting_qr_created ? 'Generated' : 'Pending';
                                        $badgeClass = $is_meeting_qr_created ? 'bg-light-secondary' : 'bg-danger';
                                    @endphp
                                    <li class="task-list-item">
                                        <i
                                            class="task-icon {{ $is_meeting_qr_created ? 'feather icon-check f-w-600 bg-success' : 'bg-danger' }}"></i>
                                        <div class="card ticket-card open-ticket">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-sm-auto mb-3 mb-sm-0">
                                                        <div class="d-sm-inline-block d-flex align-items-center">
                                                            <img loading="lazy" class="media-object wid-60 img-radius"
                                                                src="{{ $profileImage }}" alt="Generic placeholder image " />
                                                            <div class="ms-3 ms-sm-0 mb-3 mb-sm-0">
                                                                <ul
                                                                    class="text-sm-center list-unstyled mt-2 mb-0 d-inline-block">

                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="popup-trigger">
                                                            <div class="h5 font-weight-bold">Create CI Meeting
                                                                <small class="badge {{ $badgeClass }} ms-2">
                                                                    {{ $uploadStatus }}</small>
                                                            </div>
                                                            <div class="help-sm-hidden">
                                                                <ul class="list-unstyled mt-2 mb-0 text-muted">

                                                                    <li class="d-sm-inline-block d-block mt-1"><img
                                                                            src="../assets/images/user/avatar-5.jpg"
                                                                            alt=""
                                                                            class="wid-20 rounded me-2 img-fluid" />Done
                                                                        by
                                                                        <b>{{ $is_meeting_qr_created ? $user->district_name : 'District' }}</b>
                                                                    </li>
                                                                    <li class="d-sm-inline-block d-block mt-1"><i
                                                                            class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>
                                                                        {{ $is_meeting_qr_created ? \Carbon\Carbon::parse($meetingCodeGen->created_at)->format('d-m-Y h:i A') : ' ' }}
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            <div class="h5 mt-3"><i
                                                                    class="material-icons-two-tone f-16 me-1">apartment</i>
                                                                District Collectorate</div>
                                                        </div>
                                                        <div class="mt-2">
                                                            @hasPermission('create-ci-meetings')
                                                                <a href="#" data-pc-animate="just-me"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#ciMeetingCodeGenerateModal"
                                                                    class="me-2 btn btn-sm btn-light-primary"><i
                                                                        class="feather icon-grid mx-1"></i>Generate</a>
                                                            @endhasPermission
                                                            @hasPermission('download-meeting-qr')
                                                                <a href="{{ $meetingCodeGen ? route('district-candidates.generatePdf', ['qrCodeId' => $meetingCodeGen->id]) : '#' }}"
                                                                    class="me-2 btn btn-sm btn-light-info"><i
                                                                        class="feather icon-download mx-1"></i>Download</a>
                                                            @endhasPermission
                                                            @hasPermission('create-ci-meetings')
                                                                <a href="#" class="me-3 btn btn-sm btn-light-warning"><i
                                                                        class="feather icon-navigation mx-1"></i>Send
                                                                    Intimation</a>
                                                            @endhasPermission
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endhasPermission
                                @if (session('auth_role') == 'headquarters' && $current_user->custom_role != 'VDS')
                                    @php
                                        $is_qd_trunk_qr_upload = $examTrunkboxOTLData !== null;

                                        $metadata = null;
                                        if ($examTrunkboxOTLData !== null) {
                                            $metadata = is_string($examTrunkboxOTLData->metadata)
                                                ? json_decode($examTrunkboxOTLData->metadata)
                                                : (object) $examTrunkboxOTLData->metadata;
                                            $examTrunkboxOTLData = (object) $examTrunkboxOTLData;
                                        }
                                        $user = $is_qd_trunk_qr_upload
                                            ? App\Models\DepartmentOfficial::find($examTrunkboxOTLData->user_id)
                                            : null;
                                        $profileImage =
                                            $user && !empty($user->profile_image)
                                                ? asset('storage/' . $user->profile_image)
                                                : asset('storage/assets/images/user/avatar-7.jpg');
                                        // Set dynamic badge text and color
                                        $uploadStatus = $is_qd_trunk_qr_upload ? 'Uploaded' : 'Pending';
                                        $badgeClass = $is_qd_trunk_qr_upload ? 'bg-light-secondary' : 'bg-danger';
                                    @endphp
                                    <li class="task-list-item">
                                        <i
                                            class="task-icon {{ $is_qd_trunk_qr_upload ? 'feather icon-check f-w-600 bg-success' : 'bg-danger' }}"></i>
                                        <div class="card ticket-card open-ticket">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-sm-auto mb-3 mb-sm-0">
                                                        <div class="d-sm-inline-block d-flex align-items-center">
                                                            <img loading="lazy" class="media-object wid-60 img-radius"
                                                                src="{{ $profileImage }}"
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
                                                            <div class="h5 font-weight-bold">Update Trunk box and OTL
                                                                Details
                                                                <small
                                                                    class="badge {{ $badgeClass }} ms-2">{{ $uploadStatus }}</small>
                                                            </div>
                                                            <div class="help-sm-hidden">
                                                                <ul class="list-unstyled mt-2 mb-0 text-muted">

                                                                    <li class="d-sm-inline-block d-block mt-1"><img
                                                                            src="../assets/images/user/avatar-4.jpg"
                                                                            alt=""
                                                                            class="wid-20 rounded me-2 img-fluid" />Done by
                                                                        <b>{{ $is_qd_trunk_qr_upload ? $metadata->user_name ?? '' : ' Unknown ' }}</b>
                                                                    </li>
                                                                    <li class="d-sm-inline-block d-block mt-1"><i
                                                                            class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>
                                                                        {{ $is_qd_trunk_qr_upload ? \Carbon\Carbon::parse($examTrunkboxOTLData->updated_at)->format('d-m-Y h:i A') : ' ' }}
                                                                    </li>

                                                                </ul>
                                                            </div>
                                                            <div class="h5 mt-3"><i
                                                                    class="material-icons-two-tone f-16 me-1">apartment</i>
                                                                {{ $is_qd_trunk_qr_upload ? $examTrunkboxOTLData->department : 'QD - Section Officer' }}
                                                            </div>
                                                        </div>
                                                        <div class="mt-2">
                                                            @hasPermission('upload-trunk-box-otl-csv')
                                                                <a href="{{ route('exam-trunkbox-qr-otl-data.index', $session->exam_main_no) }}"
                                                                    class="me-2 btn btn-sm btn-light-primary m-2">
                                                                    <i class="feather icon-upload mx-1 "></i>Upload </a>
                                                            @endhasPermission
                                                            @hasPermission('download-trunk-box-otl-csv')
                                                                @if ($is_qd_trunk_qr_upload)
                                                                    <a href="{{ $metadata->uploaded_csv_link }}"
                                                                        class="me-3 btn btn-sm btn-light-warning"><i
                                                                            class="feather icon-download mx-1"></i>Download
                                                                    </a>
                                                                @endif
                                                            @endhasPermission
                                                            @hasPermission('upload-trunk-box-otl-csv')
                                                                @if (isset($metadata->failed_csv_link) &&
                                                                        file_exists(public_path(str_replace(url('/'), '', $metadata->failed_csv_link))))
                                                                    <a href="{{ $metadata->failed_csv_link }}"
                                                                        class="me-3 btn btn-sm btn-light-danger">
                                                                        <i class="feather icon-download mx-1"></i>Failed
                                                                        Records
                                                                    </a>
                                                                @endif
                                                            @endhasPermission
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endif
                                @hasPermission('create-exam-materails-route')
                                    @php
                                        $is_exam_routes_created = $examRoutesCreated !== null;

                                        $role = session('auth_role');
                                        if ($role == 'headquarters') {
                                            $user = $is_exam_routes_created
                                                ? App\Models\DepartmentOfficial::find($examRoutesCreated->user_id)
                                                : null;
                                        } else {
                                            $user = $is_exam_routes_created
                                                ? App\Models\District::where(
                                                    'district_code',
                                                    $examRoutesCreated->district_code,
                                                )->first()
                                                : null;
                                        }
                                        $profileImage =
                                            $user && !empty($user->profile_image)
                                                ? asset('storage/' . $user->profile_image)
                                                : asset('storage/assets/images/user/avatar-7.jpg');
                                        // Set dynamic badge text and color
                                        $uploadStatus = $is_exam_routes_created ? 'Created' : 'Pending';
                                        $badgeClass = $is_exam_routes_created ? 'bg-light-secondary' : 'bg-danger';
                                    @endphp
                                    <li class="task-list-item">
                                        <i
                                            class="task-icon {{ $is_exam_routes_created ? 'feather icon-check f-w-600 bg-success' : 'bg-danger' }}"></i>
                                        <div class="card ticket-card open-ticket">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-sm-auto mb-3 mb-sm-0">
                                                        <div class="d-sm-inline-block d-flex align-items-center">
                                                            <img loading="lazy" class="media-object wid-60 img-radius"
                                                                src="{{ $profileImage }}"
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
                                                            <div class="h5 font-weight-bold">Route Creation
                                                                <small class="badge {{ $badgeClass }} ms-2">
                                                                    {{ $uploadStatus }}</small>
                                                            </div>
                                                            <div class="help-sm-hidden">
                                                                <ul class="list-unstyled mt-2 mb-0 text-muted">
                                                                    <li class="d-sm-inline-block d-block mt-1"><img
                                                                            src="../assets/images/user/avatar-5.jpg"
                                                                            alt=""
                                                                            class="wid-20 rounded me-2 img-fluid" />Done
                                                                        by
                                                                        <b>
                                                                            @php
                                                                                $user = current_user();
                                                                            @endphp
                                                                            {{ $is_exam_routes_created
                                                                                ? ($role == 'headquarters'
                                                                                    ? $user->dept_off_name
                                                                                    : $user->district_name)
                                                                                : ($role == 'headquarters'
                                                                                    ? 'Unknown'
                                                                                    : 'District') }}
                                                                        </b>
                                                                    </li>
                                                                    <li class="d-sm-inline-block d-block mt-1"><i
                                                                            class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>
                                                                        {{ $is_exam_routes_created ? \Carbon\Carbon::parse($examRoutesCreated->updated_at)->format('d-m-Y h:i A') : ' ' }}
                                                                    </li>

                                                                </ul>
                                                            </div>
                                                            <div class="h5 mt-3"><i
                                                                    class="material-icons-two-tone f-16 me-1">apartment</i>
                                                                {{ $role == 'headquarters' ? 'ID - Section Officer' : 'District Collectorate' }}
                                                            </div>
                                                        </div>
                                                        <div class="mt-2">
                                                            @hasPermission('create-exam-materials-route')
                                                                <a href="{{ route('exam-materials-route.index', $session->exam_main_no) }}"
                                                                    class="me-2 btn btn-sm btn-light-info"><i
                                                                        class="feather icon-map mx-1"></i>Create Route</a>
                                                            @endhasPermission
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endhasPermission
                                @if ($session->exam_main_model == 'Major' && session('auth_role') == 'center')
                                    @php
                                        $is_received_district_to_center = $receiveMaterialsDistrictToCenter !== null;
                                        $metadata = null;
                                        if ($receiveMaterialsDistrictToCenter !== null) {
                                            $metadata = is_string($receiveMaterialsDistrictToCenter->metadata)
                                                ? json_decode($receiveMaterialsDistrictToCenter->metadata)
                                                : (object) $receiveMaterialsDistrictToCenter->metadata;
                                            $receiveMaterialsDistrictToCenter = (object) $receiveMaterialsDistrictToCenter;
                                        }
                                        $user = $is_received_district_to_center
                                            ? App\Models\Center::find($receiveMaterialsDistrictToCenter->user_id)
                                            : null;
                                        $profileImage =
                                            $user && !empty($user->profile_image)
                                                ? asset('storage/' . $user->profile_image)
                                                : asset('storage/assets/images/user/avatar-10.jpg');
                                        // Set dynamic badge text and color
                                        $uploadStatus = $is_received_district_to_center ? 'Received' : 'Pending';
                                        $badgeClass = $is_received_district_to_center
                                            ? 'bg-light-secondary'
                                            : 'bg-danger';
                                    @endphp
                                    <li class="task-list-item">
                                        <i
                                            class="task-icon {{ $is_received_district_to_center ? 'feather icon-check f-w-600 bg-success' : 'bg-danger' }}"></i>
                                        <div class="card ticket-card open-ticket">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-sm-auto mb-3 mb-sm-0">
                                                        <div class="d-sm-inline-block d-flex align-items-center">
                                                            <img loading="lazy" class="media-object wid-60 img-radius"
                                                                src="{{ $profileImage }}"
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
                                                            <div class="h5 font-weight-bold">Receive Materials From
                                                                District <small
                                                                    class="badge {{ $badgeClass }} ms-2">{{ $uploadStatus }}</small>
                                                            </div>
                                                            <div class="help-sm-hidden">
                                                                <ul class="list-unstyled mt-2 mb-0 text-muted">

                                                                    <li class="d-sm-inline-block d-block mt-1"><img
                                                                            src="../assets/images/user/avatar-5.jpg"
                                                                            alt=""
                                                                            class="wid-20 rounded me-2 img-fluid" />Done by
                                                                        <b>{{ $is_received_district_to_center ? $metadata->user_name ?? '' : ' Unknown ' }}</b>
                                                                    </li>
                                                                    <li class="d-sm-inline-block d-block mt-1"><i
                                                                            class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>
                                                                        {{ $is_received_district_to_center ? \Carbon\Carbon::parse($receiveMaterialsDistrictToCenter->updated_at)->format('d-m-Y h:i A') : ' ' }}
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            <div class="h5 mt-3"><i
                                                                    class="material-icons-two-tone f-16 me-1">apartment</i>
                                                                Center</div>
                                                        </div>
                                                        <div class="mt-2">
                                                            <a href="{{ route('receive-exam-materials.district-to-center', $session->exam_main_no) }}"
                                                                class="me-2 btn btn-sm btn-light-primary"><i
                                                                    class="feather icon-info mx-1"></i>Verify </a>
                                                            {{-- <a href="helpdesk-ticket-details.html"
                                                                class="me-2 btn btn-sm btn-light-info"><i
                                                                    class="feather icon-map mx-1"></i>View Route</a> --}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endif
                                @if ($session->exam_main_model == 'Major' && session('auth_role') == 'center')
                                    @php
                                        $is_received_mobileteam_to_center =
                                            $receiveMaterialsMobileteamToCenter !== null;
                                        $metadata = null;
                                        if ($receiveMaterialsMobileteamToCenter !== null) {
                                            $metadata = is_string($receiveMaterialsMobileteamToCenter->metadata)
                                                ? json_decode($receiveMaterialsMobileteamToCenter->metadata)
                                                : (object) $receiveMaterialsMobileteamToCenter->metadata;
                                            $receiveMaterialsMobileteamToCenter = (object) $receiveMaterialsMobileteamToCenter;
                                        }
                                        $user = $is_received_mobileteam_to_center
                                            ? App\Models\Center::find($receiveMaterialsMobileteamToCenter->user_id)
                                            : null;
                                        $profileImage =
                                            $user && !empty($user->profile_image)
                                                ? asset('storage/' . $user->profile_image)
                                                : asset('storage/assets/images/user/avatar-10.jpg');
                                        // Set dynamic badge text and color
                                        $uploadStatus = $is_received_mobileteam_to_center ? 'Received' : 'Pending';
                                        $badgeClass = $is_received_mobileteam_to_center
                                            ? 'bg-light-secondary'
                                            : 'bg-danger';
                                    @endphp
                                    <li class="task-list-item">
                                        <i
                                            class="task-icon {{ $is_received_mobileteam_to_center ? 'feather icon-check f-w-600 bg-success' : 'bg-danger' }}"></i>
                                        <div class="card ticket-card open-ticket">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-sm-auto mb-3 mb-sm-0">
                                                        <div class="d-sm-inline-block d-flex align-items-center">
                                                            <img loading="lazy" class="media-object wid-60 img-radius"
                                                                src="{{ $profileImage }}"
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
                                                                Team<small
                                                                    class="badge {{ $badgeClass }} ms-2">{{ $uploadStatus }}</small>
                                                            </div>
                                                            <div class="help-sm-hidden">
                                                                <ul class="list-unstyled mt-2 mb-0 text-muted">
                                                                    <li class="d-sm-inline-block d-block mt-1"><img
                                                                            src="../assets/images/user/avatar-5.jpg"
                                                                            alt=""
                                                                            class="wid-20 rounded me-2 img-fluid" />Done
                                                                        by
                                                                        <b>{{ $is_received_mobileteam_to_center ? $metadata->user_name ?? '' : ' Unknown ' }}</b>
                                                                    </li>
                                                                    <li class="d-sm-inline-block d-block mt-1"><i
                                                                            class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>
                                                                        {{ $is_received_mobileteam_to_center ? \Carbon\Carbon::parse($receiveMaterialsMobileteamToCenter->updated_at)->format('d-m-Y h:i A') : ' ' }}
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            <div class="h5 mt-3"><i
                                                                    class="material-icons-two-tone f-16 me-1">apartment</i>
                                                                Center</div>
                                                        </div>
                                                        <div class="mt-2">
                                                            <a href="{{ route('bundle-packaging.mobileteam-to-center', $session->exam_main_no) }}"
                                                                class="me-2 btn btn-sm btn-light-primary"><i
                                                                    class="feather icon-info mx-1"></i>Verify </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endif
                                @hasPermission('receive-bundle-from-mobile-team')
                                    @php
                                        $is_received_bundle_to_district = $receiveBundleToDistrict !== null;

                                        $metadata = null;
                                        if ($receiveBundleToDistrict !== null) {
                                            $metadata = is_string($receiveBundleToDistrict->metadata)
                                                ? json_decode($receiveBundleToDistrict->metadata)
                                                : (object) $receiveBundleToDistrict->metadata;
                                            $receiveBundleToDistrict = (object) $receiveBundleToDistrict;
                                        }

                                        $user = $is_received_bundle_to_district
                                            ? App\Models\TreasuryOfficer::find($receiveBundleToDistrict->user_id)
                                            : null;
                                        $profileImage =
                                            $user && !empty($user->profile_image)
                                                ? asset('storage/' . $user->profile_image)
                                                : asset('storage/assets/images/user/avatar-3.jpg');
                                        // Set dynamic badge text and color
                                        $uploadStatus = $is_received_bundle_to_district ? 'Received' : 'Pending';
                                        $badgeClass = $is_received_bundle_to_district
                                            ? 'bg-light-secondary'
                                            : 'bg-danger';
                                    @endphp
                                    <li class="task-list-item">
                                        <i
                                            class="task-icon {{ $is_received_bundle_to_district ? 'feather icon-check f-w-600 bg-success' : 'bg-danger' }}"></i>
                                        <div class="card ticket-card open-ticket">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-sm-auto mb-3 mb-sm-0">
                                                        <div class="d-sm-inline-block d-flex align-items-center">
                                                            <img loading="lazy" class="media-object wid-60 img-radius"
                                                                src="{{ $profileImage }}"
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
                                                            <div class="h5 font-weight-bold">Receive Materials From
                                                                {{ $session->exam_main_model == 'Major' ? 'Sub Treasury' : 'Mobile Team' }}
                                                                <small
                                                                    class="badge {{ $badgeClass }} ms-2">{{ $uploadStatus }}</small>
                                                            </div>
                                                            <div class="help-sm-hidden">
                                                                <ul class="list-unstyled mt-2 mb-0 text-muted">

                                                                    <li class="d-sm-inline-block d-block mt-1"><img
                                                                            src="../assets/images/user/avatar-5.jpg"
                                                                            alt=""
                                                                            class="wid-20 rounded me-2 img-fluid" />Done
                                                                        by
                                                                        <b>{{ $is_received_bundle_to_district ? $metadata->user_name ?? '' : ' Unknown ' }}</b>
                                                                    </li>
                                                                    <li class="d-sm-inline-block d-block mt-1"><i
                                                                            class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>
                                                                        {{ $is_received_bundle_to_district ? \Carbon\Carbon::parse($receiveBundleToDistrict->updated_at)->format('d-m-Y h:i A') : ' ' }}
                                                                    </li>
                                                                    {{-- <li class="d-sm-inline-block d-block mt-1"><i
                                                                        class="wid-20 material-icons-two-tone text-center f-14 me-2">chat</i>9
                                                                </li> --}}
                                                                </ul>
                                                            </div>
                                                            <div class="h5 mt-3"><i
                                                                    class="material-icons-two-tone f-16 me-1">apartment</i>
                                                                District - Treasury</div>
                                                        </div>
                                                        <div class="mt-2">
                                                            <a href="{{ route('bundle-packaging.mobileteam-to-district', $session->exam_main_no) }}"
                                                                class="me-2 btn btn-sm btn-light-primary"><i
                                                                    class="feather icon-info mx-1"></i>Verify </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endhasPermission
                                {{-- @if (session('auth_role') == 'headquarters' && $current_user->custom_role != 'VDS')
                                    @php
                                        $is_received_trunkbox_at_hq = $receiveTrunkboxToHQ !== null;

                                        $metadata = null;
                                        if ($receiveTrunkboxToHQ !== null) {
                                            $metadata = is_string($receiveTrunkboxToHQ->metadata)
                                                ? json_decode($receiveTrunkboxToHQ->metadata)
                                                : (object) $receiveTrunkboxToHQ->metadata;
                                            $receiveTrunkboxToHQ = (object) $receiveTrunkboxToHQ;
                                        }

                                        $user = $is_received_trunkbox_at_hq
                                            ? App\Models\DepartmentOfficial::find($receiveTrunkboxToHQ->user_id)
                                            : null;
                                        $profileImage =
                                            $user && !empty($user->profile_image)
                                                ? asset('storage/' . $user->profile_image)
                                                : asset('storage/assets/images/user/avatar-3.jpg');
                                        // Set dynamic badge text and color
                                        $uploadStatus = $is_received_trunkbox_at_hq ? 'Received' : 'Pending';
                                        $badgeClass = $is_received_trunkbox_at_hq ? 'bg-light-secondary' : 'bg-danger';
                                    @endphp
                                    <li class="task-list-item">
                                        <i
                                            class="task-icon {{ $is_received_trunkbox_at_hq ? 'feather icon-check f-w-600 bg-success' : 'bg-danger' }}"></i>
                                        <div class="card ticket-card open-ticket">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-sm-auto mb-3 mb-sm-0">
                                                        <div class="d-sm-inline-block d-flex align-items-center">
                                                            <img loading="lazy" class="media-object wid-60 img-radius"
                                                                src="{{ $profileImage }}"
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
                                                            <div class="h5 font-weight-bold">Receive All Materials from
                                                                Charted Vehicle <small
                                                                    class="badge {{ $badgeClass }} ms-2">{{ $uploadStatus }}</small>
                                                            </div>
                                                            <div class="help-sm-hidden">
                                                                <ul class="list-unstyled mt-2 mb-0 text-muted">
                                                                    <li class="d-sm-inline-block d-block mt-1"><img
                                                                            src="../assets/images/user/avatar-4.jpg"
                                                                            alt=""
                                                                            class="wid-20 rounded me-2 img-fluid" />Done
                                                                        by
                                                                        <b>{{ $is_received_trunkbox_at_hq ? $metadata->user_name ?? '' : ' Unknown ' }}</b>
                                                                    </li>
                                                                    <li class="d-sm-inline-block d-block mt-1"><i
                                                                            class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>
                                                                        {{ $is_received_trunkbox_at_hq ? \Carbon\Carbon::parse($receiveTrunkboxToHQ->updated_at)->format('d-m-Y h:i A') : ' ' }}
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            <div class="h5 mt-3"><i
                                                                    class="material-icons-two-tone f-16 me-1">apartment</i>
                                                                ED
                                                                - Officer</div>
                                                        </div>
                                                        <div class="mt-2">
                                                            @hasPermission('verify-bundle-recevied-at-hq')
                                                                <a href="{{ route('bundle-packaging.charted-vehicle-to-headquarters', $session->exam_main_no) }}"
                                                                    class="me-2 btn btn-sm btn-light-info"><i
                                                                        class="feather icon-info mx-1"></i>Verify</a>
                                                            @endhasPermission
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    @php
                                        $is_materials_handover_verified = $materialsHandoverVerification !== null;

                                        $metadata = null;
                                        if ($materialsHandoverVerification !== null) {
                                            $metadata = is_string($materialsHandoverVerification->metadata)
                                                ? json_decode($materialsHandoverVerification->metadata)
                                                : (object) $materialsHandoverVerification->metadata;
                                            $materialsHandoverVerification = (object) $materialsHandoverVerification;
                                        }

                                        $user = $is_materials_handover_verified
                                            ? App\Models\DepartmentOfficial::find(
                                                $materialsHandoverVerification->user_id,
                                            )
                                            : null;
                                        $profileImage =
                                            $user && !empty($user->profile_image)
                                                ? asset('storage/' . $user->profile_image)
                                                : asset('storage/assets/images/user/avatar-3.jpg');
                                        // Set dynamic badge text and color
                                        $uploadStatus = $is_materials_handover_verified ? 'Verified' : 'Pending';
                                        $badgeClass = $is_materials_handover_verified
                                            ? 'bg-light-secondary'
                                            : 'bg-danger';
                                    @endphp
                                    <li class="task-list-item">
                                        <i
                                            class="task-icon {{ $is_materials_handover_verified ? 'feather icon-check f-w-600 bg-success' : 'bg-danger' }}"></i>
                                        <div class="card ticket-card open-ticket">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-sm-auto mb-3 mb-sm-0">
                                                        <div class="d-sm-inline-block d-flex align-items-center">
                                                            <img loading="lazy" class="media-object wid-60 img-radius"
                                                                src="{{ $profileImage }}"
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
                                                            <div class="h5 font-weight-bold">Verify All Materials and
                                                                Memory
                                                                Cards Handovered <small
                                                                    class="badge {{ $badgeClass }} ms-2">{{ $uploadStatus }}</small>
                                                            </div>
                                                            <div class="help-sm-hidden">
                                                                <ul class="list-unstyled mt-2 mb-0 text-muted">

                                                                    <li class="d-sm-inline-block d-block mt-1"><img
                                                                            src="../assets/images/user/avatar-4.jpg"
                                                                            alt=""
                                                                            class="wid-20 rounded me-2 img-fluid" />Done
                                                                        by
                                                                        <b>{{ $is_materials_handover_verified ? $metadata->user_name ?? '' : ' Unknown ' }}</b>
                                                                    </li>
                                                                    <li class="d-sm-inline-block d-block mt-1"><i
                                                                            class="wid-20 material-icons-two-tone text-center f-14 me-2">calendar_today</i>
                                                                        {{ $is_materials_handover_verified ? \Carbon\Carbon::parse($materialsHandoverVerification->updated_at)->format('d-m-Y h:i A') : ' ' }}
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            <div class="h5 mt-3"><i
                                                                    class="material-icons-two-tone f-16 me-1">apartment</i>
                                                                VMD
                                                                - Admin Officer</div>
                                                        </div>
                                                        <div class="mt-2">
                                                            @hasPermission('verify-materials-handovered')
                                                                <a href="{{ route('bundle-packaging.charted-vehicle-to-headquarters', $session->exam_main_no) }}"
                                                                    class="me-2 btn btn-sm btn-light-info"><i
                                                                        class="feather icon-info mx-1"></i>Verify</a>
                                                            @endhasPermission
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endif --}}
                            </ul>
                            {{-- <div class="text-end">
                      <a href="#!" class="b-b-primary text-primary">View Friend List</a>
                    </div> --}}
                        </div>
                    </div>
                </div>
                @include('modals.apd-upload-excel')
                @include('modals.id-increase-candidate')
                @include('modals.apd-finalize-candidate')
                {{-- @include('modals.preliminary-checklist') --}}
                {{-- @include('modals.session-checklist') --}}
                {{-- @include('modals.invigilator-select') --}}
                @include('modals.ci-meetingcode-generate')
                {{-- @include('modals.invigilator-allotment') --}}
                {{-- @include('modals.qpbox-opentime') --}}
                {{-- @include('modals.verify-all-materials-handovered') --}}
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
