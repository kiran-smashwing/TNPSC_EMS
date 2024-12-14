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

    @include('modals.cropper')


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
                                        <i class="ti ti-user me-2"></i>My Account
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="profile-tab-2" data-bs-toggle="tab" href="#profile-2"
                                        role="tab" aria-selected="true">
                                        <i class="ti ti-file-text me-2"></i>My Account Details
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
                            @if ($role == 'headquarters')
                                @include('partials.my_account_view.department_account_view')
                            @elseif($role == 'venue')
                                @include('partials.my_account_view.venue_account_view')
                            @elseif($role == 'center')
                                @include('partials.my_account_view.center_account_view')
                            @elseif($role == 'treasury')
                                @include('partials.my_account_view.treasury_account_view')
                            @elseif($role == 'ci')
                                @include('partials.my_account_view.ci_account_view')
                            @elseif($role == 'district')
                                @include('partials.my_account_view.district_account_view')
                                @elseif($role == 'mobile_team_staffs')
                                @include('partials.my_account_view.mobile_account_view')

                            @endif
                        </div>
                        <!-- Modal -->
                        {{-- <div class="modal fade" id="cropperModal" tabindex="-1" aria-labelledby="cropperModalLabel"
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
                        </div> --}}
                        <div class="tab-pane" id="profile-2" role="tabpanel" aria-labelledby="profile-tab-2">
                            @if ($role == 'headquarters')
                                @include('partials.my_account_edit.department_account')
                            @elseif($role == 'venue')
                                @include('partials.my_account_edit.venue_account')
                            @elseif($role == 'center')
                                @include('partials.my_account_edit.center_account')
                            @elseif($role == 'treasury')
                                @include('partials.my_account_edit.treasury_account')
                            @elseif($role == 'ci')
                                @include('partials.my_account_edit.ci_account')
                            @elseif($role == 'district')
                                @include('partials.my_account_edit.district_account')
                            @elseif($role == 'mobile_team_staffs')
                                @include('partials.my_account_edit.mobile_account')
                            
                            @endif

                        </div>
                        {{-- <div class="tab-pane" id="profile-3" role="tabpanel" aria-labelledby="profile-tab-3">
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
                        </div> --}}
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
            document.querySelector('.btn-success').addEventListener('click', function(e) {
                e.preventDefault();
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(position => {
                        document.getElementById('latitude').value = position.coords.latitude;
                        document.getElementById('longitude').value = position.coords.longitude;
                    });
                }
            });
        </script>
        @if($role == 'ci')
        <script>
            // Full list of centers
            const allCenters = @json($centers);

            // District dropdown change event
            $('#district').on('change', function() {
                const selectedDistrictCode = $(this).val();
                const centerDropdown = $('#center');

                // Clear previous options
                centerDropdown.empty();
                centerDropdown.append('<option value="">Select Center Name</option>');

                // Filter centers based on selected district
                const filteredCenters = allCenters.filter(center =>
                    center.center_district_id == selectedDistrictCode
                );

                // Populate centers
                filteredCenters.forEach(center => {
                    const selected = "{{ old('center', $chiefInvigilator->ci_center_id) }}" == center
                        .center_code ? 'selected' : '';
                    centerDropdown.append(
                        `<option value="${center.center_code}" ${selected}>
                        ${center.center_name}
                    </option>`
                    );
                });
            });

            // Trigger change event on page load to handle old/existing selections
            $(document).ready(function() {
                const oldDistrict = "{{ old('district', $chiefInvigilator->ci_district_id) }}";
                if (oldDistrict) {
                    $('#district').val(oldDistrict).trigger('change');
                }
            });
        </script>
        <script>
            // Full list of venues
            const allVenues = @json($venues);

            // Center dropdown change event
            $('#center').on('change', function() {
                const selectedCenterCode = $(this).val();
                const venueDropdown = $('#venue');

                // Clear previous options
                venueDropdown.empty();
                venueDropdown.append('<option value="">Select Venue Name</option>');

                // Filter venues based on selected center
                const filteredVenues = allVenues.filter(venue =>
                    venue.venue_center_id == selectedCenterCode
                );
                // Populate venues
                filteredVenues.forEach(venue => {
                    const selected = "{{ old('venue', $chiefInvigilator->ci_venue_id) }}" == venue.venue_code ?
                        'selected' : '';
                    venueDropdown.append(
                        `<option value="${venue.venue_code}" ${selected}>
                        ${venue.venue_name}
                    </option>`
                    );
                });
            });

            // Trigger change event on page load to handle old/existing selections
            $(document).ready(function() {
                const oldCenter = "{{ old('center', $chiefInvigilator->ci_center_id ?? '') }}";
                if (oldCenter) {
                    $('#center').val(oldCenter).trigger('change');
                }
            });
        </script>
        @endif
        @endpush
        @include('partials.theme')

    @endsection
