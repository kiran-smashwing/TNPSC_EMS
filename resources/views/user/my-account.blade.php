@extends('layouts.app')
@section('title', ' My Account')
@push('styles')
    <link rel="stylesheet" href="../assets/css/plugins/croppr.min.css" />
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
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            {{-- <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="../dashboard/index.html">Home</a></li>
                                <li class="breadcrumb-item"><a href="javascript: void(0)">Users</a></li>
                                <li class="breadcrumb-item" aria-current="page">Account Profile</li>
                            </ul> --}}
                        </div>
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">My Account</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            <!-- [ Main Content ] start -->
            <div class="row">
                <!-- [ sample-page ] start -->
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body py-0">
                            <ul class="nav nav-tabs profile-tabs" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="profile-tab-1" data-bs-toggle="tab" href="#profile-1"
                                        role="tab" aria-selected="true">
                                        <i class="ti ti-user me-2"></i>Profile
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="profile-tab-2" data-bs-toggle="tab" href="#profile-2"
                                        role="tab" aria-selected="true">
                                        <i class="ti ti-file-text me-2"></i>Personal Details
                                    </a>
                                </li>
                                {{-- <li class="nav-item">
                                    <a class="nav-link" id="profile-tab-3" data-bs-toggle="tab" href="#profile-3"
                                        role="tab" aria-selected="true">
                                        <i class="ti ti-id me-2"></i>My Account
                                    </a>
                                </li> --}}
                                <li class="nav-item">
                                    <a class="nav-link" id="profile-tab-4" data-bs-toggle="tab" href="#profile-4"
                                        role="tab" aria-selected="true">
                                        <i class="ti ti-lock me-2"></i>Change Password
                                    </a>
                                </li>
                                {{-- <li class="nav-item">
                                    <a class="nav-link" id="profile-tab-5" data-bs-toggle="tab" href="#profile-5"
                                        role="tab" aria-selected="true">
                                        <i class="ti ti-users me-2"></i>Role
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="profile-tab-6" data-bs-toggle="tab" href="#profile-6"
                                        role="tab" aria-selected="true">
                                        <i class="ti ti-settings me-2"></i>Settings
                                    </a>
                                </li> --}}
                            </ul>
                        </div>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane show active" id="profile-1" role="tabpanel" aria-labelledby="profile-tab-1">
                            <div class="row">
                                <div class="col-lg-4 col-xxl-3">
                                    <div class="card">
                                        <div class="card-body position-relative">
                                            <div class="position-absolute end-0 top-0 p-3">
                                                {{-- <span class="badge bg-primary"></span> --}}
                                            </div>
                                            <div class="text-center mt-3">
                                                <div class="chat-avtar d-inline-flex mx-auto">
                                                    <img class="rounded-circle img-fluid wid-70"
                                                        src="{{ $userDetails['profile_picture'] }}" alt="User image" />
                                                </div>

                                                <h5 class="mb-0">{{ $userDetails['name'] }}</h5>
                                                <p class="text-muted text-sm">
                                                    {{ strtoupper(str_replace('_', ' ', session('athu_display_role'))) }}
                                                </p>
                                                <hr class="my-3 border border-secondary-subtle" />
                                                {{-- <div class="row g-3">
                                                    <div class="col-4">
                                                        <h5 class="mb-0">86</h5>
                                                        <small class="text-muted">Post</small>
                                                    </div>
                                                    <div class="col-4 border border-top-0 border-bottom-0">
                                                        <h5 class="mb-0">40</h5>
                                                        <small class="text-muted">Project</small>
                                                    </div>
                                                    <div class="col-4">
                                                        <h5 class="mb-0">4.5K</h5>
                                                        <small class="text-muted">Members</small>
                                                    </div>
                                                </div> --}}
                                                <hr class="my-3 border border-secondary-subtle" />
                                                @if ($role == 'headquarters')
                                                    <div
                                                        class="d-inline-flex align-items-center justify-content-start w-100 mb-3">
                                                        <i class="ti ti-man me-2"></i>
                                                        <p class="mb-0">
                                                            {{ $userDetails['role_department'] }}-{{ $userDetails['role_name'] }}
                                                        </p>
                                                    </div>
                                                @endif
                                                <div
                                                    class="d-inline-flex align-items-center justify-content-start w-100 mb-3">
                                                    <i class="ti ti-mail me-2"></i>
                                                    <p class="mb-0">{{ $userDetails['email'] }}</p>
                                                </div>
                                                <div
                                                    class="d-inline-flex align-items-center justify-content-start w-100 mb-3">
                                                    <i class="ti ti-phone me-2"></i>
                                                    <p class="mb-0">{{ $userDetails['phone'] }}</p>
                                                </div>
                                                @if ($role == 'district' || $role == 'center' || $role == 'venue' || $role == 'ci')
                                                    <div
                                                        class="d-inline-flex align-items-center justify-content-start w-100 mb-3">
                                                        <i class="ti ti-map-pin me-2"></i>
                                                        <p class="mb-0">{{ $userDetails['address'] }}</p>
                                                    </div>
                                                @endif
                                                @if ($role == 'headquarters')
                                                    <div
                                                        class="d-inline-flex align-items-center justify-content-start w-100 mb-3">
                                                        <i class="ti ti-barcode me-2"></i>
                                                        <p class="mb-0">{{ $userDetails['address'] }}</p>
                                                    </div>
                                                @endif
                                                @if ($role == 'venue')
                                                    <div
                                                        class="d-inline-flex align-items-center justify-content-start w-100">
                                                        <i class="ti ti-link me-2"></i>
                                                        <a href="#" class="link-primary">
                                                            <p class="mb-0">{{ $userDetails['website'] }}</p>
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        {{-- <div class="card-header">
                                            <h5>Skills</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row align-items-center mb-3">
                                                <div class="col-sm-6 mb-2 mb-sm-0">
                                                    <p class="mb-0">Junior</p>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-grow-1 me-3">
                                                            <div class="progress progress-primary" style="height: 6px">
                                                                <div class="progress-bar" style="width: 30%"></div>
                                                            </div>
                                                        </div>
                                                        <div class="flex-shrink-0">
                                                            <p class="mb-0 text-muted">30%</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row align-items-center mb-3">
                                                <div class="col-sm-6 mb-2 mb-sm-0">
                                                    <p class="mb-0">UX Researcher</p>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-grow-1 me-3">
                                                            <div class="progress progress-primary" style="height: 6px">
                                                                <div class="progress-bar" style="width: 80%"></div>
                                                            </div>
                                                        </div>
                                                        <div class="flex-shrink-0">
                                                            <p class="mb-0 text-muted">80%</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row align-items-center mb-3">
                                                <div class="col-sm-6 mb-2 mb-sm-0">
                                                    <p class="mb-0">Wordpress</p>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-grow-1 me-3">
                                                            <div class="progress progress-primary" style="height: 6px">
                                                                <div class="progress-bar" style="width: 90%"></div>
                                                            </div>
                                                        </div>
                                                        <div class="flex-shrink-0">
                                                            <p class="mb-0 text-muted">90%</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row align-items-center mb-3">
                                                <div class="col-sm-6 mb-2 mb-sm-0">
                                                    <p class="mb-0">HTML</p>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-grow-1 me-3">
                                                            <div class="progress progress-primary" style="height: 6px">
                                                                <div class="progress-bar" style="width: 30%"></div>
                                                            </div>
                                                        </div>
                                                        <div class="flex-shrink-0">
                                                            <p class="mb-0 text-muted">30%</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row align-items-center mb-3">
                                                <div class="col-sm-6 mb-2 mb-sm-0">
                                                    <p class="mb-0">Graphic Design</p>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-grow-1 me-3">
                                                            <div class="progress progress-primary" style="height: 6px">
                                                                <div class="progress-bar" style="width: 95%"></div>
                                                            </div>
                                                        </div>
                                                        <div class="flex-shrink-0">
                                                            <p class="mb-0 text-muted">95%</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row align-items-center">
                                                <div class="col-sm-6 mb-2 mb-sm-0">
                                                    <p class="mb-0">Code Style</p>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-grow-1 me-3">
                                                            <div class="progress progress-primary" style="height: 6px">
                                                                <div class="progress-bar" style="width: 75%"></div>
                                                            </div>
                                                        </div>
                                                        <div class="flex-shrink-0">
                                                            <p class="mb-0 text-muted">75%</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> --}}
                                    </div>
                                </div>
                                <div class="col-lg-8 col-xxl-9">
                                    {{-- <div class="card">
                                        <div class="card-header">
                                            <h5>About me</h5>
                                        </div>
                                        <div class="card-body">
                                            <p class="mb-0">Hello, I’m Anshan Handgun Creative Graphic Designer & User
                                                Experience Designer based in Website, I create digital
                                                Products a more Beautiful and usable place. Morbid accusant ipsum. Nam nec
                                                tellus at.</p>
                                        </div>
                                    </div> --}}
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Personal Details</h5>
                                        </div>
                                        <div class="card-body">
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item px-0 pt-0">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <p class="mb-1 text-muted">Name</p>
                                                            <p class="mb-0">{{ $userDetails['name'] }}</p>
                                                        </div>
                                                        @if ($role == 'headquarters')
                                                            <div class="col-md-6">
                                                                <p class="mb-1 text-muted">Role</p>
                                                                <p class="mb-0">
                                                                    {{ $userDetails['role_department'] }}-{{ $userDetails['role_name'] }}
                                                                </p>
                                                            </div>
                                                        @endif
                                                        @if ($role == 'venue')
                                                            <div class="col-md-6">
                                                                <p class="mb-1 text-muted">District</p>
                                                                <p class="mb-0">
                                                                    {{ $userDetails['district_name'] }}
                                                                </p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </li>
                                                <li class="list-group-item px-0">
                                                    <div class="row">
                                                        @if ($role == 'venue')
                                                            <div class="col-md-6">
                                                                <p class="mb-1 text-muted">Center</p>
                                                                <p class="mb-0">{{ $userDetails['center_name'] }}</p>
                                                            </div>
                                                        @endif
                                                        <div class="col-md-6">
                                                            <p class="mb-1 text-muted">Email</p>
                                                            <p class="mb-0">{{ $userDetails['email'] }}</p>
                                                        </div>

                                                        @if ($role == 'headquarters')
                                                            <div class="col-md-6">
                                                                <p class="mb-1 text-muted">Designation</p>
                                                                <p class="mb-0">{{ $userDetails['designation'] }}</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </li>
                                                <li class="list-group-item px-0">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <p class="mb-1 text-muted">Phone</p>
                                                            <p class="mb-0">{{ $userDetails['phone'] }}</p>
                                                        </div>
                                                        @if ($role == 'venue')
                                                            <div class="col-md-6">
                                                                <p class="mb-1 text-muted">Alternative Phone</p>
                                                                <p class="mb-0">{{ $userDetails['alternative_phone'] }}
                                                                </p>
                                                            </div>
                                                        @endif
                                                        {{-- <div class="col-md-6">
                                                            <p class="mb-1 text-muted">Zip Code</p>
                                                            <p class="mb-0">956 754</p>
                                                        </div> --}}
                                                    </div>
                                                </li>
                                                @if ($role == 'venue')
                                                    <li class="list-group-item px-0">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <p class="mb-1 text-muted">Type</p>
                                                                <p class="mb-0">{{ $userDetails['type'] }}</p>
                                                            </div>
                                                            @if ($role == 'venue')
                                                                <div class="col-md-6">
                                                                    <p class="mb-1 text-muted">Category</p>
                                                                    <p class="mb-0">{{ $userDetails['category'] }}</p>
                                                                </div>
                                                            @endif
                                                            {{-- <div class="col-md-6">
                                                            <p class="mb-1 text-muted">Zip Code</p>
                                                            <p class="mb-0">956 754</p>
                                                        </div> --}}
                                                        </div>
                                                    </li>
                                                @endif
                                                @if ($role == 'venue')
                                                    <li class="list-group-item px-0">
                                                        <div class="row">
                                                            @php
                                                            $typeMapping = [
                                                                '1' => 'Anna University',
                                                                '2' => 'Thriuvalluvar University',
                                                                '3' => 'Madras University',
                                                                '4' => 'Madurai Kamraj University',
                                                                '5' => 'Manonmaniam Sundaranar University',
                                                                '6' => 'Others',
                                                                'UDISE' => 'UDISE',
                                                            ];

                                                            $typeLabel =
                                                                $typeMapping[$userDetails['code_provider'] ?? ''] ??
                                                                'Not Available';
                                                        @endphp
                                                            <div class="col-md-6">
                                                                <p class="mb-1 text-muted">Venue Code Provider</p>
                                                                <p class="mb-0">{{ $typeLabel }}</p>
                                                            </div>
                                                            {{-- <div class="col-md-6">
                                                            <p class="mb-1 text-muted">Zip Code</p>
                                                            <p class="mb-0">956 754</p>
                                                        </div> --}}
                                                        </div>
                                                    </li>
                                                @endif
                                                {{-- <li class="list-group-item px-0 pb-0">
                                                    <p class="mb-1 text-muted">Address</p>
                                                    <p class="mb-0">Street 110-B Kalians Bag, Dewan, M.P. New York</p>
                                                </li> --}}
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="card">
                                        {{-- <div class="card-header">
                                            <h5>Education</h5>
                                        </div>
                                        <div class="card-body">
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item px-0 pt-0">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <p class="mb-1 text-muted">Master Degree (Year)</p>
                                                            <p class="mb-0">2014-2017</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p class="mb-1 text-muted">Institute</p>
                                                            <p class="mb-0">-</p>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="list-group-item px-0">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <p class="mb-1 text-muted">Bachelor (Year)</p>
                                                            <p class="mb-0">2011-2013</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p class="mb-1 text-muted">Institute</p>
                                                            <p class="mb-0">Imperial College London</p>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="list-group-item px-0 pb-0">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <p class="mb-1 text-muted">School (Year)</p>
                                                            <p class="mb-0">2009-2011</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p class="mb-1 text-muted">Institute</p>
                                                            <p class="mb-0">School of London, England</p>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div> --}}
                                    </div>
                                    @if ($role == 'venue')
                                        <div class="card">
                                            <div class="card-header">
                                                <h5>Bank Details</h5>
                                            </div>
                                            <div class="card-body">
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item px-0 pt-0">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <p class="mb-1 text-muted">Bank Name</p>
                                                                <p class="mb-0">{{ $userDetails['bank_name'] }}</p>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <p class="mb-1 text-muted">Account Name</p>
                                                                <p class="mb-0">{{ $userDetails['account_name'] }}</p>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li class="list-group-item px-0">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <p class="mb-1 text-muted">Account Number
                                                                </p>
                                                                <p class="mb-0">{{ $userDetails['account_number'] }}</p>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <p class="mb-1 text-muted">Branch Name</p>
                                                                <p class="mb-0">{{ $userDetails['branch_name'] }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li class="list-group-item px-0 pb-0">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <p class="mb-1 text-muted">Account Type</p>
                                                                <p class="mb-0">{{ $userDetails['account_type'] }}</p>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <p class="mb-1 text-muted">IFSC Code</p>
                                                                <p class="mb-0">{{ $userDetails['ifsc'] }}</p>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <!-- Modal -->
                        <div class="modal fade" id="cropperModal" tabindex="-1" aria-labelledby="cropperModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content" style="width:850px">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="cropperModalLabel">Image Cropper</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="col-sm-12">
                                            <!-- Image cropper plugin start -->

                                            <div class="container">
                                                <div class="row">
                                                    <div class="col-md-7 mb-3 mb-md-0">
                                                        <div class="cropper">
                                                            <img src="../assets/images/light-box/l1.jpg" alt="image"
                                                                id="croppr" />
                                                        </div>
                                                        <div class="input-group mb-3">
                                                            <label class="input-group-text" for="imageUpload">Upload
                                                                Image</label>
                                                            <input type="file" class="form-control" id="imageUpload"
                                                                accept="image/*">
                                                        </div>


                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="rounded bg-light px-4 py-3 mb-3">
                                                            <h5>Selection value</h5>
                                                            <div class="row">
                                                                <div class="col-6">
                                                                    <p id="valX"><strong>x: </strong>&nbsp;500</p>
                                                                    <p class="mb-1" id="valY"><strong>y:
                                                                        </strong>&nbsp;500</p>
                                                                </div>
                                                                <div class="col-6">
                                                                    <p id="valW"><strong>width: </strong>&nbsp;500</p>
                                                                    <p class="mb-1" id="valH"><strong>height:
                                                                        </strong>&nbsp;500</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-1">
                                                            <div class="col">
                                                                <h6>Aspect Ratio</h6>
                                                            </div>
                                                            <div class="col-auto">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="" id="cb-ratio" />
                                                                    <label class="form-check-label" for="cb-ratio"> Enable
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="input-group mb-3">
                                                            <span class="input-group-text">A</span>
                                                            <input type="text" class="form-control" id="input-ratio"
                                                                value="1.0" disabled="disabled" />
                                                        </div>
                                                        <div class="row mb-1">
                                                            <div class="col">
                                                                <h6>Maximum size</h6>
                                                            </div>
                                                            <div class="col-auto">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="" id="max-checkbox" />
                                                                    <label class="form-check-label" for="max-checkbox">
                                                                        Enable </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row g-1 g-sm-3 mb-4">
                                                            <div class="col-4">
                                                                <div class="input-group">
                                                                    <span class="input-group-text">W</span>
                                                                    <input type="text" class="form-control"
                                                                        id="max-input-width" value="150"
                                                                        disabled="disabled" />
                                                                </div>
                                                            </div>
                                                            <div class="col-4">
                                                                <div class="input-group">
                                                                    <span class="input-group-text">H</span>
                                                                    <input type="text" class="form-control"
                                                                        id="max-input-height" value="150"
                                                                        disabled="disabled" />
                                                                </div>
                                                            </div>
                                                            <div class="col-4">
                                                                <select id="max-input-unit" disabled="disabled"
                                                                    class="form-control">
                                                                    <option>px</option>
                                                                    <option value="%">%</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="row mb-1">
                                                            <div class="col">
                                                                <h6>Minimum size</h6>
                                                            </div>
                                                            <div class="col-auto">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="" id="min-checkbox" />
                                                                    <label class="form-check-label" for="min-checkbox">
                                                                        Enable </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row g-1 g-sm-3">
                                                            <div class="col-4">
                                                                <div class="input-group">
                                                                    <span class="input-group-text">W</span>
                                                                    <input type="text" class="form-control"
                                                                        id="min-input-width" value="150"
                                                                        disabled="disabled" />
                                                                </div>
                                                            </div>
                                                            <div class="col-4">
                                                                <div class="input-group">
                                                                    <span class="input-group-text">H</span>
                                                                    <input type="text" class="form-control"
                                                                        id="min-input-height" value="150"
                                                                        disabled="disabled" />
                                                                </div>
                                                            </div>
                                                            <div class="col-4">
                                                                <select id="min-input-unit" disabled="disabled"
                                                                    class="form-control">
                                                                    <option> px </option>
                                                                    <option value="%"> % </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Image cropper plugin end -->
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary" id="saveCroppedImage">Save
                                            changes</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="profile-2" role="tabpanel" aria-labelledby="profile-tab-2">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Personal Information</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-12 text-center mb-3">
                                                    <div class="user-upload wid-75" data-pc-animate="just-me"
                                                        data-bs-toggle="modal" data-bs-target="#cropperModal">
                                                        <img src="{{ $userDetails['profile_picture'] }}"
                                                            id="previewImage" alt="Cropped Preview"
                                                            style="max-width: 100%; height: auto; object-fit: cover;">
                                                        <input type="hidden" name="cropped_image" id="cropped_image">
                                                        <label for="imageUpload" class="img-avtar-upload"></label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Name<span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" class="form-control"
                                                            value="{{ $userDetails['name'] ?? '' }}" name="name"
                                                            required />
                                                    </div>
                                                </div>
                                                @if ($role == 'headquarters')
                                                    <div class="col-sm-6">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="role">Role </label>
                                                            <select
                                                                class="form-control @error('role') is-invalid @enderror"
                                                                id="role" name="role" disabled>
                                                                <option>Select Role</option>
                                                                @foreach ($roles as $roleOption)
                                                                    <option value="{{ $roleOption->role_id }}"
                                                                        {{ isset($userDetails) && $userDetails['role'] == $roleOption->role_id ? 'selected' : '' }}>
                                                                        {{ $roleOption->role_department }} -
                                                                        {{ $roleOption->role_name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('role')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Employee ID</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $userDetails['address'] }}" />
                                                        </div>
                                                    </div>
                                                @endif
                                                @if ($role == 'venue')
                                                    <div class="col-sm-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">District</label>
                                                            <select class="form-control" name="district_id" disabled>
                                                                <option value="">Select District</option>
                                                                @foreach ($districts as $district)
                                                                    <option value="{{ $district->district_code }}"
                                                                        {{ old('district_code', $userDetails['district'] ?? '') == $district->id ? 'selected' : '' }}>
                                                                        {{ $district->district_name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Center</label>
                                                            <select class="form-control" name="center_id" disabled>
                                                                <option value="">Select Center</option>
                                                                @foreach ($centers as $center)
                                                                    <option value="{{ $center->center_code }}"
                                                                        {{ old('center_code', $userDetails['center'] ?? '') == $center->id ? 'selected' : '' }}>
                                                                        {{ $center->center_name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                @endif

                                                {{-- <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Email</label>
                                                        <input type="text" class="form-control"
                                                            value="{{ $userDetails['email'] }}" />
                                                    </div>
                                                </div> --}}
                                                @if ($role == 'venue')
                                                    <div class="col-sm-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Venue code</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $userDetails['code'] }}" />
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="mb-3">
                                                            <label class="form-label">Address</label>
                                                            <textarea class="form-control">{{ $userDetails['address'] }}</textarea>
                                                        </div>

                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="venue_code_provider">Venue Code
                                                                Provider
                                                                <span class="text-danger">*</span>
                                                            </label>
                                                            <select
                                                                class="form-control @error('venue_code_provider') is-invalid @enderror"
                                                                id="venue_code_provider" name="venue_code_provider"
                                                                required>
                                                                <option>Select Venue Code Provider</option>
                                                                <option value="UDISE"
                                                                    {{ old('venue_code_provider', $userDetails['code_provider'] ?? '') == 'UDISE' ? 'selected' : '' }}>
                                                                    UDISE
                                                                </option>
                                                                <option value="1"
                                                                    {{ old('venue_code_provider', $userDetails['code_provider'] ?? '') == '1' ? 'selected' : '' }}>
                                                                    Anna University
                                                                </option>
                                                                <option value="2"
                                                                    {{ old('venue_code_provider', $userDetails['code_provider'] ?? '') == '2' ? 'selected' : '' }}>
                                                                    Thriuvalluvar University
                                                                </option>
                                                                <option value="3"
                                                                    {{ old('venue_code_provider', $userDetails['code_provider'] ?? '') == '3' ? 'selected' : '' }}>
                                                                    Madras University
                                                                </option>
                                                                <option value="4"
                                                                    {{ old('venue_code_provider', $userDetails['code_provider'] ?? '') == '4' ? 'selected' : '' }}>
                                                                    Madurai Kamraj University
                                                                </option>
                                                                <option value="5"
                                                                    {{ old('venue_code_provider', $userDetails['code_provider'] ?? '') == '5' ? 'selected' : '' }}>
                                                                    Manonmaniam Sundaranar University
                                                                </option>
                                                                <option value="6"
                                                                    {{ old('venue_code_provider', $userDetails['code_provider'] ?? '') == '6' ? 'selected' : '' }}>
                                                                    Others
                                                                </option>
                                                            </select>
                                                            @error('venue_code_provider')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    {{-- <div class="card">
                      <div class="card-header">
                        <h5>Social Network</h5>
                      </div>
                      <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                          <div class="flex-grow-1 me-3">
                            <div class="d-flex align-items-center">
                              <div class="flex-shrink-0">
                                <div class="avtar avtar-xs btn-light-twitter">
                                  <i class="fab fa-twitter f-16"></i>
                                </div>
                              </div>
                              <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">Twitter</h6>
                              </div>
                            </div>
                          </div>
                          <div class="flex-shrink-0">
                            <button class="btn btn-link-primary">Connect</button>
                          </div>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                          <div class="flex-grow-1 me-3">
                            <div class="d-flex align-items-center">
                              <div class="flex-shrink-0">
                                <div class="avtar avtar-xs btn-light-facebook">
                                  <i class="fab fa-facebook-f f-16"></i>
                                </div>
                              </div>
                              <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">Facebook <small class="text-muted f-w-400">/Anshan Handgun</small></h6>
                              </div>
                            </div>
                          </div>
                          <div class="flex-shrink-0">
                            <button class="btn btn-link-danger">Remove</button>
                          </div>
                        </div>
                        <div class="d-flex align-items-center">
                          <div class="flex-grow-1 me-3">
                            <div class="d-flex align-items-center">
                              <div class="flex-shrink-0">
                                <div class="avtar avtar-xs btn-light-linkedin">
                                  <i class="fab fa-linkedin-in f-16"></i>
                                </div>
                              </div>
                              <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">Linkedin</h6>
                              </div>
                            </div>
                          </div>
                          <div class="flex-shrink-0">
                            <button class="btn btn-link-primary">Connect</button>
                          </div>
                        </div>
                      </div>
                    </div> --}}
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Contact Information</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                {{-- <div class="col-sm-6">
                            <div class="mb-3">
                              <label class="form-label">Contact Phone</label>
                              <input type="text" class="form-control" value="(+99) 9999 999 999" />
                            </div>
                          </div> --}}
                                                <div class="col-sm-12">
                                                    <div class="mb-3">
                                                        <label class="form-label">Email</label>
                                                        <input type="text" class="form-control"
                                                            value="{{ $userDetails['email'] }}" />
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    @if ($role == 'headquarters')
                                                        <div class="mb-3">
                                                            <label class="form-label">Designation</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $userDetails['designation'] }}" />
                                                        </div>
                                                    @endif
                                                </div>
                                                @if ($role == 'venue')
                                                    <div class="col-sm-12">
                                                        <div class="mb-3">
                                                            <label class="form-label">Address</label>
                                                            <textarea class="form-control">3379  Monroe Avenue, Fort Myers, Florida(33912)</textarea>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 text-end btn-page">
                                    <div class="btn btn-outline-secondary">Cancel</div>
                                    <div class="btn btn-primary">Update Profile</div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="profile-3" role="tabpanel" aria-labelledby="profile-tab-3">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>General Settings</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Username <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" class="form-control"
                                                            value="Ashoka_Tano_16" />
                                                        <small class="form-text text-muted">Your Profile URL:
                                                            https://pc.com/Ashoka_Tano_16</small>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Account Email <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" class="form-control"
                                                            value="demo@sample.com" />
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Language</label>
                                                        <select class="form-control">
                                                            <option>Washington</option>
                                                            <option>India</option>
                                                            <option>Africa</option>
                                                            <option>New York</option>
                                                            <option>Malaysia</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Sign in Using</label>
                                                        <select class="form-control">
                                                            <option>Password</option>
                                                            <option>Face Recognition</option>
                                                            <option>Thumb Impression</option>
                                                            <option>Key</option>
                                                            <option>Pin</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Advance Settings</h5>
                                        </div>
                                        <div class="card-body">
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item px-0 pt-0">
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <div>
                                                            <p class="mb-1">Secure Browsing</p>
                                                            <p class="text-muted text-sm mb-0">Browsing Securely ( https )
                                                                when it's necessary</p>
                                                        </div>
                                                        <div class="form-check form-switch p-0">
                                                            <input class="form-check-input h4 position-relative m-0"
                                                                type="checkbox" role="switch" checked="" />
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="list-group-item px-0">
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <div>
                                                            <p class="mb-1">Login Notifications</p>
                                                            <p class="text-muted text-sm mb-0">Notify when login attempted
                                                                from other place</p>
                                                        </div>
                                                        <div class="form-check form-switch p-0">
                                                            <input class="form-check-input h4 position-relative m-0"
                                                                type="checkbox" role="switch" checked="" />
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="list-group-item px-0 pb-0">
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <div>
                                                            <p class="mb-1">Login Approvals</p>
                                                            <p class="text-muted text-sm mb-0">Approvals is not required
                                                                when login from unrecognized devices.</p>
                                                        </div>
                                                        <div class="form-check form-switch p-0">
                                                            <input class="form-check-input h4 position-relative m-0"
                                                                type="checkbox" role="switch" checked="" />
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Recognized Devices</h5>
                                        </div>
                                        <div class="card-body">
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item px-0 pt-0">
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <div class="me-2">
                                                            <p class="mb-2">Celt Desktop</p>
                                                            <p class="mb-0 text-muted">4351 Deans Lane</p>
                                                        </div>
                                                        <div class="">
                                                            <div class="text-success d-inline-block me-2">
                                                                <i class="fas fa-circle f-10 me-2"></i>
                                                                Current Active
                                                            </div>
                                                            <a href="#!" class="text-danger"><i
                                                                    class="feather icon-x-circle"></i></a>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="list-group-item px-0">
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <div class="me-2">
                                                            <p class="mb-2">Imco Tablet</p>
                                                            <p class="mb-0 text-muted">4185 Michigan Avenue</p>
                                                        </div>
                                                        <div class="">
                                                            <div class="text-muted d-inline-block me-2">
                                                                <i class="fas fa-circle f-10 me-2"></i>
                                                                5 days ago
                                                            </div>
                                                            <a href="#!" class="text-danger"><i
                                                                    class="feather icon-x-circle"></i></a>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="list-group-item px-0 pb-0">
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <div class="me-2">
                                                            <p class="mb-2">Albs Mobile</p>
                                                            <p class="mb-0 text-muted">3462 Fairfax Drive</p>
                                                        </div>
                                                        <div class="">
                                                            <div class="text-muted d-inline-block me-2">
                                                                <i class="fas fa-circle f-10 me-2"></i>
                                                                1 month ago
                                                            </div>
                                                            <a href="#!" class="text-danger"><i
                                                                    class="feather icon-x-circle"></i></a>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Active Sessions</h5>
                                        </div>
                                        <div class="card-body">
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item px-0 pt-0">
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <div class="me-2">
                                                            <p class="mb-2">Celt Desktop</p>
                                                            <p class="mb-0 text-muted">4351 Deans Lane</p>
                                                        </div>
                                                        <button class="btn btn-link-danger">Logout</button>
                                                    </div>
                                                </li>
                                                <li class="list-group-item px-0 pb-0">
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <div class="me-2">
                                                            <p class="mb-2">Moon Tablet</p>
                                                            <p class="mb-0 text-muted">4185 Michigan Avenue</p>
                                                        </div>
                                                        <button class="btn btn-link-danger">Logout</button>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 text-end">
                                    <button class="btn btn-outline-dark ms-2">Clear</button>
                                    <button class="btn btn-primary">Update Profile</button>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="profile-4" role="tabpanel" aria-labelledby="profile-tab-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Change Password</h5>
                                </div>
                                <div class="card-body">
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
                                    <form action="{{ route('password.update') }}" method="POST" id="password-form">
                                        @csrf
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Old Password</label>
                                                        <input type="password" name="old_password" class="form-control"
                                                            required />
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">New Password</label>
                                                        <input type="password" name="new_password" class="form-control"
                                                            required />
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Confirm Password</label>
                                                        <input type="password" name="new_password_confirmation"
                                                            class="form-control" required />
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <h5>New password must contain:</h5>
                                                    <ul class="list-group list-group-flush">
                                                        <li class="list-group-item">
                                                            <i class="ti ti-circle-check text-success f-16 me-2"></i> At
                                                            least 8 characters
                                                        </li>
                                                        <li class="list-group-item">
                                                            <i class="ti ti-circle-check text-success f-16 me-2"></i> At
                                                            least 1 lower letter (a-z)
                                                        </li>
                                                        <li class="list-group-item">
                                                            <i class="ti ti-circle-check text-success f-16 me-2"></i> At
                                                            least 1 uppercase letter (A-Z)
                                                        </li>
                                                        <li class="list-group-item">
                                                            <i class="ti ti-circle-check text-success f-16 me-2"></i> At
                                                            least 1 number (0-9)
                                                        </li>
                                                        <li class="list-group-item">
                                                            <i class="ti ti-circle-check text-success f-16 me-2"></i> At
                                                            least 1 special character
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Hidden fields for role and user ID -->
                                        <input type="hidden" name="role" value="{{ session('auth_role') }}">
                                        <input type="hidden" name="user_id" value="{{ session('auth_id') }}">

                                        <div class="card-footer text-end btn-page">
                                            <button type="button" class="btn btn-outline-secondary"
                                                onclick="window.location.href='{{ url()->previous() }}'">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Update Profile</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        {{-- <div class="tab-pane" id="profile-5" role="tabpanel" aria-labelledby="profile-tab-5">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Invite Team Members</h5>
                                </div>
                                <div class="card-body">
                                    <h4>5/10 <small>members available in your plan.</small></h4>
                                    <hr class="my-3" />
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="mb-3">
                                                <label class="form-label">Email Address</label>
                                                <div class="row">
                                                    <div class="col">
                                                        <input type="email" class="form-control" />
                                                    </div>
                                                    <div class="col-auto">
                                                        <button class="btn btn-primary">Send</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body table-card">
                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead>
                                                <tr>
                                                    <th>MEMBER</th>
                                                    <th>ROLE</th>
                                                    <th class="text-end">STATUS</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <div class="row">
                                                            <div class="col-auto pe-0">
                                                                <img src="../assets/images/user/avatar-1.jpg"
                                                                    alt="user-image" class="wid-40 rounded-circle" />
                                                            </div>
                                                            <div class="col">
                                                                <h5 class="mb-0">Addie Bass</h5>
                                                                <p class="text-muted f-12 mb-0">mareva@gmail.com</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><span class="badge bg-primary">Owner</span></td>
                                                    <td class="text-end"><span class="badge bg-success">Joined</span></td>
                                                    <td class="text-end"><a href="#"
                                                            class="avtar avtar-s btn-link-secondary"><i
                                                                class="ti ti-dots f-18"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="row">
                                                            <div class="col-auto pe-0">
                                                                <img src="../assets/images/user/avatar-4.jpg"
                                                                    alt="user-image" class="wid-40 rounded-circle" />
                                                            </div>
                                                            <div class="col">
                                                                <h5 class="mb-0">Agnes McGee</h5>
                                                                <p class="text-muted f-12 mb-0">heba@gmail.com</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><span class="badge bg-light-info">Manager</span></td>
                                                    <td class="text-end"><a href="#"
                                                            class="btn btn-link-danger">Resend</a> <span
                                                            class="badge bg-light-success">Invited</span></td>
                                                    <td class="text-end"><a href="#"
                                                            class="avtar avtar-s btn-link-secondary"><i
                                                                class="ti ti-dots f-18"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="row">
                                                            <div class="col-auto pe-0">
                                                                <img src="../assets/images/user/avatar-5.jpg"
                                                                    alt="user-image" class="wid-40 rounded-circle" />
                                                            </div>
                                                            <div class="col">
                                                                <h5 class="mb-0">Agnes McGee</h5>
                                                                <p class="text-muted f-12 mb-0">heba@gmail.com</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><span class="badge bg-light-warning">Staff</span></td>
                                                    <td class="text-end"><span class="badge bg-success">Joined</span></td>
                                                    <td class="text-end"><a href="#"
                                                            class="avtar avtar-s btn-link-secondary"><i
                                                                class="ti ti-dots f-18"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="row">
                                                            <div class="col-auto pe-0">
                                                                <img src="../assets/images/user/avatar-1.jpg"
                                                                    alt="user-image" class="wid-40 rounded-circle" />
                                                            </div>
                                                            <div class="col">
                                                                <h5 class="mb-0">Addie Bass</h5>
                                                                <p class="text-muted f-12 mb-0">mareva@gmail.com</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><span class="badge bg-primary">Owner</span></td>
                                                    <td class="text-end"><span class="badge bg-success">Joined</span></td>
                                                    <td class="text-end"><a href="#"
                                                            class="avtar avtar-s btn-link-secondary"><i
                                                                class="ti ti-dots f-18"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="row">
                                                            <div class="col-auto pe-0">
                                                                <img src="../assets/images/user/avatar-4.jpg"
                                                                    alt="user-image" class="wid-40 rounded-circle" />
                                                            </div>
                                                            <div class="col">
                                                                <h5 class="mb-0">Agnes McGee</h5>
                                                                <p class="text-muted f-12 mb-0">heba@gmail.com</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><span class="badge bg-light-info">Manager</span></td>
                                                    <td class="text-end"><a href="#"
                                                            class="btn btn-link-danger">Resend</a> <span
                                                            class="badge bg-light-success">Invited</span></td>
                                                    <td class="text-end"><a href="#"
                                                            class="avtar avtar-s btn-link-secondary"><i
                                                                class="ti ti-dots f-18"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="row">
                                                            <div class="col-auto pe-0">
                                                                <img src="../assets/images/user/avatar-5.jpg"
                                                                    alt="user-image" class="wid-40 rounded-circle" />
                                                            </div>
                                                            <div class="col">
                                                                <h5 class="mb-0">Agnes McGee</h5>
                                                                <p class="text-muted f-12 mb-0">heba@gmail.com</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><span class="badge bg-light-warning">Staff</span></td>
                                                    <td class="text-end"><span class="badge bg-success">Joined</span></td>
                                                    <td class="text-end"><a href="#"
                                                            class="avtar avtar-s btn-link-secondary"><i
                                                                class="ti ti-dots f-18"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="row">
                                                            <div class="col-auto pe-0">
                                                                <img src="../assets/images/user/avatar-1.jpg"
                                                                    alt="user-image" class="wid-40 rounded-circle" />
                                                            </div>
                                                            <div class="col">
                                                                <h5 class="mb-0">Addie Bass</h5>
                                                                <p class="text-muted f-12 mb-0">mareva@gmail.com</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><span class="badge bg-primary">Owner</span></td>
                                                    <td class="text-end"><span class="badge bg-success">Joined</span></td>
                                                    <td class="text-end"><a href="#"
                                                            class="avtar avtar-s btn-link-secondary"><i
                                                                class="ti ti-dots f-18"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="row">
                                                            <div class="col-auto pe-0">
                                                                <img src="../assets/images/user/avatar-4.jpg"
                                                                    alt="user-image" class="wid-40 rounded-circle" />
                                                            </div>
                                                            <div class="col">
                                                                <h5 class="mb-0">Agnes McGee</h5>
                                                                <p class="text-muted f-12 mb-0">heba@gmail.com</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><span class="badge bg-light-info">Manager</span></td>
                                                    <td class="text-end"><a href="#"
                                                            class="btn btn-link-danger">Resend</a> <span
                                                            class="badge bg-light-success">Invited</span></td>
                                                    <td class="text-end"><a href="#"
                                                            class="avtar avtar-s btn-link-secondary"><i
                                                                class="ti ti-dots f-18"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="row">
                                                            <div class="col-auto pe-0">
                                                                <img src="../assets/images/user/avatar-5.jpg"
                                                                    alt="user-image" class="wid-40 rounded-circle" />
                                                            </div>
                                                            <div class="col">
                                                                <h5 class="mb-0">Agnes McGee</h5>
                                                                <p class="text-muted f-12 mb-0">heba@gmail.com</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><span class="badge bg-light-warning">Staff</span></td>
                                                    <td class="text-end"><span class="badge bg-success">Joined</span></td>
                                                    <td class="text-end"><a href="#"
                                                            class="avtar avtar-s btn-link-secondary"><i
                                                                class="ti ti-dots f-18"></i></a></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="card-footer text-end btn-page">
                                    <div class="btn btn-link-danger">Cancel</div>
                                    <div class="btn btn-primary">Update Profile</div>
                                </div>
                            </div>
                        </div> --}}
                        {{-- <div class="tab-pane" id="profile-6" role="tabpanel" aria-labelledby="profile-tab-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Email Settings</h5>
                                        </div>
                                        <div class="card-body">
                                            <h6 class="mb-4">Setup Email Notification</h6>
                                            <div class="d-flex align-items-center justify-content-between mb-1">
                                                <div>
                                                    <p class="text-muted mb-0">Email Notification</p>
                                                </div>
                                                <div class="form-check form-switch p-0">
                                                    <input class="m-0 form-check-input h5 position-relative"
                                                        type="checkbox" role="switch" checked="" />
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between mb-1">
                                                <div>
                                                    <p class="text-muted mb-0">Send Copy To Personal Email</p>
                                                </div>
                                                <div class="form-check form-switch p-0">
                                                    <input class="m-0 form-check-input h5 position-relative"
                                                        type="checkbox" role="switch" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Updates from System Notification</h5>
                                        </div>
                                        <div class="card-body">
                                            <h6 class="mb-4">Email you with?</h6>
                                            <div class="d-flex align-items-center justify-content-between mb-1">
                                                <div>
                                                    <p class="text-muted mb-0">News about PCT-themes products and feature
                                                        updates</p>
                                                </div>
                                                <div class="form-check p-0">
                                                    <input class="m-0 form-check-input h5 position-relative"
                                                        type="checkbox" role="switch" checked="" />
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between mb-1">
                                                <div>
                                                    <p class="text-muted mb-0">Tips on getting more out of PCT-themes</p>
                                                </div>
                                                <div class="form-check p-0">
                                                    <input class="m-0 form-check-input h5 position-relative"
                                                        type="checkbox" role="switch" checked="" />
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between mb-1">
                                                <div>
                                                    <p class="text-muted mb-0">Things you missed since you last logged into
                                                        PCT-themes</p>
                                                </div>
                                                <div class="form-check p-0">
                                                    <input class="m-0 form-check-input h5 position-relative"
                                                        type="checkbox" role="switch" />
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between mb-1">
                                                <div>
                                                    <p class="text-muted mb-0">News about products and other services</p>
                                                </div>
                                                <div class="form-check p-0">
                                                    <input class="m-0 form-check-input h5 position-relative"
                                                        type="checkbox" role="switch" />
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between mb-1">
                                                <div>
                                                    <p class="text-muted mb-0">Tips and Document business products</p>
                                                </div>
                                                <div class="form-check p-0">
                                                    <input class="m-0 form-check-input h5 position-relative"
                                                        type="checkbox" role="switch" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Activity Related Emails</h5>
                                        </div>
                                        <div class="card-body">
                                            <h6 class="mb-4">When to email?</h6>
                                            <div class="d-flex align-items-center justify-content-between mb-1">
                                                <div>
                                                    <p class="text-muted mb-0">Have new notifications</p>
                                                </div>
                                                <div class="form-check form-switch p-0">
                                                    <input class="m-0 form-check-input h5 position-relative"
                                                        type="checkbox" role="switch" checked="" />
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between mb-1">
                                                <div>
                                                    <p class="text-muted mb-0">You're sent a direct message</p>
                                                </div>
                                                <div class="form-check form-switch p-0">
                                                    <input class="m-0 form-check-input h5 position-relative"
                                                        type="checkbox" role="switch" />
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between mb-1">
                                                <div>
                                                    <p class="text-muted mb-0">Someone adds you as a connection</p>
                                                </div>
                                                <div class="form-check form-switch p-0">
                                                    <input class="m-0 form-check-input h5 position-relative"
                                                        type="checkbox" role="switch" checked="" />
                                                </div>
                                            </div>
                                            <hr class="my-4 border border-secondary-subtle" />
                                            <h6 class="mb-4">When to escalate emails?</h6>
                                            <div class="d-flex align-items-center justify-content-between mb-1">
                                                <div>
                                                    <p class="text-muted mb-0">Upon new order</p>
                                                </div>
                                                <div class="form-check form-switch p-0">
                                                    <input class="m-0 form-check-input h5 position-relative"
                                                        type="checkbox" role="switch" checked="" />
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between mb-1">
                                                <div>
                                                    <p class="text-muted mb-0">New membership approval</p>
                                                </div>
                                                <div class="form-check form-switch p-0">
                                                    <input class="m-0 form-check-input h5 position-relative"
                                                        type="checkbox" role="switch" />
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between mb-1">
                                                <div>
                                                    <p class="text-muted mb-0">Member registration</p>
                                                </div>
                                                <div class="form-check form-switch p-0">
                                                    <input class="m-0 form-check-input h5 position-relative"
                                                        type="checkbox" role="switch" checked="" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 text-end btn-page">
                                    <div class="btn btn-outline-secondary">Cancel</div>
                                    <div class="btn btn-primary">Update Profile</div>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
                <!-- [ sample-page ] end -->
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>
    <!-- [ Main Content ] end -->
    @include('partials.footer')

    @push('scripts')
        <script src="{{ asset('storage/assets/js/plugins/croppr.min.js') }}"></script>
        <script src="{{ asset('storage/assets/js/pages/page-croper.js') }}"></script>
        <script>
            document.getElementById('triggerModal').addEventListener('click', function() {
                var modal = new bootstrap.Modal(document.getElementById('cropperModal'));
                modal.show();
            });
        </script>
        <script>
        @endpush
        @include('partials.theme')

    @endsection
