@extends('layouts.app')

@section('title', 'Current Exam')

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
    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pc-content">

            <!-- [ Main Content ] start -->
            <div class="row">
                <!-- [ sample-page ] start -->
                <div class="col-sm-12">
                    <div class="chat-wrapper">
                        <div class="offcanvas-xxl offcanvas-start chat-offcanvas" tabindex="-1" id="offcanvas_User_list">
                            <div class="offcanvas-header">
                                <button class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#offcanvas_User_list"
                                    aria-label="Close"></button>
                            </div>
                            <div class="offcanvas-body p-0">
                                <div id="chat-user_list" class="show collapse collapse-horizontal">
                                    <div class="chat-user_list">
                                        <div class="card overflow-hidden">
                                            <div class="card-header">

                                                <h5 class="">{{ $user->district_name }}<span
                                                        class="me-2 btn btn-sm bg-light-primary ">{{ $totalCentersFromProjection }}/{{ $totalCenters }}</span>
                                                </h5>
                                            </div>
                                            <div class="scroll-block">
                                                <div class="card-body p-0">
                                                    <div class="list-group list-group-flush">
                                                        @foreach ($examCenters as $center)
                                                            <a href="#"
                                                                class="list-group-item list-group-item-action p-3 center-list-item"
                                                                data-center-code="{{ $center->center_code }}">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="flex-grow-1 mx-2">
                                                                        <h6 class="mb-0 center-name">
                                                                            {{ $center->details->center_name }}</h6>
                                                                        <span class="text-sm text-muted required-halls">
                                                                            Required Halls -
                                                                            {{ ceil($center->total_accommodation / 200) }}
                                                                        </span>
                                                                    </div>
                                                                    <div
                                                                        class="chat-avtar text-md text-warning f-w-400 fs-5">
                                                                        {{ $center->center_code }}
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        @endforeach

                                                        {{-- <a href="#"
                                                            class="list-group-item list-group-item-action p-3">
                                                            <div class="d-flex align-items-center">

                                                                <div class="flex-grow-1 mx-2">
                                                                    <h6 class="mb-0">Arni</h6>
                                                                    <span class="text-sm text-muted">19-07-2024 12:00 PM
                                                                     
                                                                </div>
                                                                <div class="chat-avtar text-md text-warning f-w-400 fs-5">
                                                                    2201
                                                                </div>
                                                            </div>
                                                        </a> --}}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body p-0">
                                                <div class="list-group list-group-flush">
                                                    <a href="#"
                                                        class="list-group-item list-group-item-action d-flex align-items-center gap-2 bg-light-success">
                                                        <i class="ti ti-mail-forward " style="font-size: 19px;"></i>
                                                        <span>Send Mail </span>
                                                    </a>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="chat-content">
                            <div class="card mb-0">
                                <div class="card-header p-3">
                                    <div class="d-flex align-items-center">
                                        <ul class="list-inline me-auto mb-0">
                                            <li class="list-inline-item align-bottom">
                                                <a href="#" class="d-xxl-none avtar avtar-s btn-link-secondary"
                                                    data-bs-toggle="offcanvas" data-bs-target="#offcanvas_User_list">
                                                    <i class="ti ti-menu-2 f-18"></i>
                                                </a>
                                                <a href="#"
                                                    class="d-none d-xxl-inline-flex avtar avtar-s btn-link-secondary"
                                                    data-bs-toggle="collapse" data-bs-target="#chat-user_list">
                                                    <i class="ti ti-menu-2 f-18"></i>
                                                </a>
                                            </li>

                                        </ul>

                                        <ul class="list-inline ms-auto me-auto mb-0 fs-5 ">
                                            <li class="list-inline-item">
                                                <a href="#" class="badge bg-dark ">Requested
                                                    {{ ceil($center->total_accommodation / 200) }}</a>
                                            </li>
                                            <li class="list-inline-item">
                                                <a href="#" class="badge bg-success">Accepted 110</a>
                                            </li>
                                            <li class="list-inline-item"><a href="#" class="badge bg-info"> Selected
                                                    <span id="selected-venues">0</span> / <span
                                                        id="total-venues">0</span></a></li>
                                        </ul>
                                        <ul class="list-inline ms-auto mb-0">
                                            <li class="list-inline-item"><a href="#"
                                                    class="btn btn-outline-success">Save</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="scroll-block chat-message">
                                    <div class="card-body">
                                        <div class="row">
                                            <!-- Table for Venue Allocation with Editable Columns -->
                                            <div class="mb-4">
                                                <table class="table table-bordered" id="responsiveTable">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Venue</th>
                                                            <th>Contact</th>
                                                            <th>Halls </th>
                                                            <th>Status </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                        @foreach ($allvenues as $centerCode => $venues)
                                                            @foreach ($venues as $venue)
                                                                <tr class="venue-row {{ $venue->consent_status == 'denied' ? 'table-danger' : '' }}"
                                                                    data-center-code="{{ $centerCode }}">
                                                                    <td>
                                                                        <input
                                                                            class="form-check-input input-success venue-checkbox"
                                                                            data-venue-code="{{ $venue->venue_id }}"
                                                                            type="checkbox"
                                                                            @if ($venue->consent_status !== 'not_requested') checked disabled @endif>
                                                                    </td>
                                                                    <td data-venue-name="{{ $venue->venue_name }}">
                                                                        <b>{{ $venue->venue_name }}</b> <br>
                                                                        {{ $venue->venue_address }}
                                                                    </td>
                                                                    <td>
                                                                        {{ $venue->venue_email }} <br>
                                                                        {{ $venue->venue_phone }}
                                                                    </td>
                                                                    <td>
                                                                        <select class="form-select" name="allocationCount"
                                                                            @if ($venue->halls_count !== 0) disabled @endif>
                                                                            <option value="">No of Halls</option>
                                                                            @foreach (range(1, 5) as $hallCount)
                                                                                <option value="{{ $hallCount * $candidatesCountForEachHall }}"
                                                                                    @if ($venue->halls_count == $hallCount * $candidatesCountForEachHall ) selected @endif>
                                                                                    {{ $hallCount }} -
                                                                                    {{ $hallCount * $candidatesCountForEachHall }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        @if ($venue->consent_status === 'not_requested')
                                                                            <span
                                                                                class="badge bg-light-warning">Waiting</span>
                                                                        @elseif ($venue->consent_status === 'requested')
                                                                            <span class="badge bg-light-info">Email
                                                                                Sent</span>
                                                                        @elseif ($venue->consent_status === 'accepted')
                                                                            <span
                                                                                class="badge bg-light-success">Accepted</span>
                                                                        @elseif ($venue->consent_status === 'denied')
                                                                            <span
                                                                                class="badge bg-light-danger">Denied</span>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @endforeach
                                                        {{-- <tr>
                                                            <td>
                                                                <input class="form-check-input input-success"
                                                                    type="checkbox" id="customCheckc3" checked="checked">
                                                            </td>
                                                            <!-- Editable Cell for Venue Name -->
                                                            <td>
                                                                <b>Gov Hr Sec School</b> <br> 2nd Cross Street
                                                                Thiruvannamalai
                                                            </td>

                                                            <!-- Editable Cell for Email -->
                                                            <td>
                                                                thvgovschool@mail.in <br> 9234567890

                                                            </td>

                                                            <!-- Editable Cell for Phone -->

                                                            <!-- Dropdown for Allocation Count -->
                                                            <td>
                                                                <select class="form-select" name="allocationCount">
                                                                    <option value="">No of Halls</option>
                                                                    <option value="200">1 - 200</option>
                                                                    <option value="400">2 - 400</option>
                                                                    <option value="600">3 - 600</option>
                                                                    <option value="600">4 - 800</option>
                                                                    <option value="600">5 - 1000</option>
                                                                    <!-- Add more options as needed -->
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-light-warning">Waiting</span>
                                                            </td>
                                                        </tr>
                                                        <tr class="table-danger">
                                                            <td>
                                                                <input class="form-check-input input-success"
                                                                    type="checkbox" id="customCheckc3" disabled>
                                                            </td>
                                                            <!-- Editable Cell for Venue Name -->
                                                            <td>
                                                                <b>Gov Hr Sec School</b> <br> 2nd Cross Street
                                                                Thiruvannamalai
                                                            </td>

                                                            <!-- Editable Cell for Email -->
                                                            <td>
                                                                thvgovschool@mail.in <br> 9234567890

                                                            </td>

                                                            <!-- Editable Cell for Phone -->

                                                            <!-- Dropdown for Allocation Count -->
                                                            <td>
                                                                <select class="form-select" name="allocationCount" disabled>
                                                                    <option value="">No of Halls</option>
                                                                    <option value="200">1 - 200</option>
                                                                    <option value="400">2 - 400</option>
                                                                    <option value="600">3 - 600</option>
                                                                    <option value="600">4 - 800</option>
                                                                    <option value="600">5 - 1000</option>
                                                                    <!-- Add more options as needed -->
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-light-danger">Denied</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <input class="form-check-input input-success"
                                                                    type="checkbox" id="customCheckc3" checked="checked">
                                                            </td>
                                                            <!-- Editable Cell for Venue Name -->
                                                            <td>
                                                                <b>Gov Hr Sec School</b> <br> 2nd Cross Street
                                                                Thiruvannamalai
                                                            </td>

                                                            <!-- Editable Cell for Email -->
                                                            <td>
                                                                thvgovschool@mail.in <br> 9234567890

                                                            </td>

                                                            <!-- Editable Cell for Phone -->

                                                            <!-- Dropdown for Allocation Count -->
                                                            <td>
                                                                <select class="form-select" name="allocationCount">
                                                                    <option value="">No of Halls</option>
                                                                    <option value="200">1 - 200</option>
                                                                    <option value="400">2 - 400</option>
                                                                    <option value="600">3 - 600</option>
                                                                    <option value="600">4 - 800</option>
                                                                    <option value="600">5 - 1000</option>
                                                                    <!-- Add more options as needed -->
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-light-success">Accepted</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <input class="form-check-input input-success"
                                                                    type="checkbox" id="customCheckc3" checked="checked">
                                                            </td>
                                                            <!-- Editable Cell for Venue Name -->
                                                            <td>
                                                                <b>Gov Hr Sec School</b> <br> 2nd Cross Street
                                                                Thiruvannamalai
                                                            </td>

                                                            <!-- Editable Cell for Email -->
                                                            <td>
                                                                thvgovschool@mail.in <br> 9234567890

                                                            </td>

                                                            <!-- Editable Cell for Phone -->

                                                            <!-- Dropdown for Allocation Count -->
                                                            <td>
                                                                <select class="form-select" name="allocationCount">
                                                                    <option value="">No of Halls</option>
                                                                    <option value="200">1 - 200</option>
                                                                    <option value="400">2 - 400</option>
                                                                    <option value="600">3 - 600</option>
                                                                    <option value="600">4 - 800</option>
                                                                    <option value="600">5 - 1000</option>
                                                                    <!-- Add more options as needed -->
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-light-success">Accepted</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <input class="form-check-input input-success"
                                                                    type="checkbox" id="customCheckc3" checked="checked">
                                                            </td>
                                                            <!-- Editable Cell for Venue Name -->
                                                            <td>
                                                                <b>Gov Hr Sec School</b> <br> 2nd Cross Street
                                                                Thiruvannamalai
                                                            </td>

                                                            <!-- Editable Cell for Email -->
                                                            <td>
                                                                thvgovschool@mail.in <br> 9234567890

                                                            </td>

                                                            <!-- Editable Cell for Phone -->

                                                            <!-- Dropdown for Allocation Count -->
                                                            <td>
                                                                <select class="form-select" name="allocationCount">
                                                                    <option value="">No of Halls</option>
                                                                    <option value="200">1 - 200</option>
                                                                    <option value="400">2 - 400</option>
                                                                    <option value="600">3 - 600</option>
                                                                    <option value="600">4 - 800</option>
                                                                    <option value="600">5 - 1000</option>
                                                                    <!-- Add more options as needed -->
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-light-danger">Denied</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <input class="form-check-input input-success"
                                                                    type="checkbox" id="customCheckc3" checked="checked">
                                                            </td>
                                                            <!-- Editable Cell for Venue Name -->
                                                            <td>
                                                                <b>Gov Hr Sec School</b> <br> 2nd Cross Street
                                                                Thiruvannamalai
                                                            </td>

                                                            <!-- Editable Cell for Email -->
                                                            <td>
                                                                thvgovschool@mail.in <br> 9234567890

                                                            </td>

                                                            <!-- Editable Cell for Phone -->

                                                            <!-- Dropdown for Allocation Count -->
                                                            <td>
                                                                <select class="form-select" name="allocationCount">
                                                                    <option value="">No of Halls</option>
                                                                    <option value="200">1 - 200</option>
                                                                    <option value="400">2 - 400</option>
                                                                    <option value="600">3 - 600</option>
                                                                    <option value="600">4 - 800</option>
                                                                    <option value="600">5 - 1000</option>
                                                                    <!-- Add more options as needed -->
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-light-danger">Denied</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <input class="form-check-input input-success"
                                                                    type="checkbox" id="customCheckc3" checked="checked">
                                                            </td>
                                                            <!-- Editable Cell for Venue Name -->
                                                            <td>
                                                                <b>Gov Hr Sec School</b> <br> 2nd Cross Street
                                                                Thiruvannamalai
                                                            </td>

                                                            <!-- Editable Cell for Email -->
                                                            <td>
                                                                thvgovschool@mail.in <br> 9234567890

                                                            </td>

                                                            <!-- Editable Cell for Phone -->

                                                            <!-- Dropdown for Allocation Count -->
                                                            <td>
                                                                <select class="form-select" name="allocationCount">
                                                                    <option value="">No of Halls</option>
                                                                    <option value="200">1 - 200</option>
                                                                    <option value="400">2 - 400</option>
                                                                    <option value="600">3 - 600</option>
                                                                    <option value="600">4 - 800</option>
                                                                    <option value="600">5 - 1000</option>
                                                                    <!-- Add more options as needed -->
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-light-danger">Denied</span>
                                                            </td>
                                                        </tr> --}}
                                                        <!-- You can add more rows as needed -->
                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="offcanvas-xxl offcanvas-end chat-offcanvas" tabindex="-1" id="offcanvas_User_info">
                            <div class="offcanvas-header">
                                <button class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#offcanvas_User_info"
                                    aria-label="Close"></button>
                            </div>
                            <div class="offcanvas-body p-0">
                                <div id="chat-user_info" class="collapse collapse-horizontal">
                                    <div class="chat-user_info">
                                        <div class="card">
                                            <div class="text-center card-body position-relative pb-0">
                                                <h5 class="text-start">Profile View</h5>
                                                <div class="position-absolute end-0 top-0 p-3 d-none d-xxl-inline-flex">
                                                    <a href="#"
                                                        class="avtar avtar-xs btn-link-danger btn-pc-default"
                                                        data-bs-toggle="collapse" data-bs-target="#chat-user_info">
                                                        <i class="ti ti-x f-16"></i>
                                                    </a>
                                                </div>
                                                <div class="chat-avtar d-inline-flex mx-auto">
                                                    <img class="rounded-circle img-fluid wid-100"
                                                        src="../assets/images/user/avatar-5.jpg" alt="User image" />
                                                </div>
                                                <h5 class="mb-0">Alene</h5>
                                                <p class="text-muted text-sm">Sr. Customer Manager</p>
                                                <div class="d-flex align-items-center justify-content-center mb-4">
                                                    <i class="chat-badge bg-success me-2"></i>
                                                    <span class="badge bg-light-success">Available</span>
                                                </div>
                                                <ul class="list-inline ms-auto mb-0">
                                                    <li class="list-inline-item">
                                                        <a href="#" class="avtar avtar-s btn-link-secondary">
                                                            <i class="ti ti-phone-call f-18"></i>
                                                        </a>
                                                    </li>
                                                    <li class="list-inline-item">
                                                        <a href="#" class="avtar avtar-s btn-link-secondary">
                                                            <i class="ti ti-message-circle f-18"></i>
                                                        </a>
                                                    </li>
                                                    <li class="list-inline-item">
                                                        <a href="#" class="avtar avtar-s btn-link-secondary">
                                                            <i class="ti ti-video f-18"></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="scroll-block">
                                                <div class="card-body">
                                                    <div class="row mb-3">
                                                        <div class="col-6">
                                                            <div class="p-3 rounded bg-light-primary">
                                                                <p class="mb-1">All File</p>
                                                                <div class="d-flex align-items-center">
                                                                    <i class="ti ti-folder f-22 text-primary"></i>
                                                                    <h4 class="mb-0 ms-2">231</h4>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="p-3 rounded bg-light-secondary">
                                                                <p class="mb-1">All Link</p>
                                                                <div class="d-flex align-items-center">
                                                                    <i class="ti ti-link f-22 text-secondary"></i>
                                                                    <h4 class="mb-0 ms-2">231</h4>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="form-check form-switch d-flex align-items-center justify-content-between p-0">
                                                        <label class="form-check-label h5 mb-0"
                                                            for="customSwitchemlnot1">Notification</label>
                                                        <input class="form-check-input h5 m-0 position-relative"
                                                            type="checkbox" id="customSwitchemlnot1" checked="" />
                                                    </div>
                                                    <hr class="my-3 border border-secondary-subtle" />
                                                    <a class="btn border-0 p-0 text-start w-100" data-bs-toggle="collapse"
                                                        href="#filtercollapse1">
                                                        <div class="float-end"><i class="ti ti-chevron-down"></i></div>
                                                        <h5 class="mb-0">Information</h5>
                                                    </a>
                                                    <div class="collapse show" id="filtercollapse1">
                                                        <div class="py-3">
                                                            <div
                                                                class="d-flex align-items-center justify-content-between mb-2">
                                                                <p class="mb-0">Address</p>
                                                                <p class="mb-0 text-muted">Port Narcos</p>
                                                            </div>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between mb-2">
                                                                <p class="mb-0">Email</p>
                                                                <p class="mb-0 text-muted">alene@company.com</p>
                                                            </div>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between mb-2">
                                                                <p class="mb-0">Phone</p>
                                                                <p class="mb-0 text-muted">380-293-0177</p>
                                                            </div>
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <p class="mb-0">Last visited</p>
                                                                <p class="mb-0 text-muted">2 hours</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr class="my-3 border border-secondary-subtle" />
                                                    <a class="btn border-0 p-0 text-start w-100" data-bs-toggle="collapse"
                                                        href="#filtercollapse2">
                                                        <div class="float-end"><i class="ti ti-chevron-down"></i></div>
                                                        <h5 class="mb-0">File type</h5>
                                                    </a>
                                                    <div class="collapse show" id="filtercollapse2">
                                                        <div class="py-3">
                                                            <div class="d-flex align-items-center mb-2">
                                                                <a href="#" class="avtar avtar-s btn-light-success">
                                                                    <i class="ti ti-file-text f-20"></i>
                                                                </a>
                                                                <div class="flex-grow-1 ms-3">
                                                                    <h6 class="mb-0">Document</h6>
                                                                    <span class="text-muted text-sm">123 files,
                                                                        193MB</span>
                                                                </div>
                                                                <a href="#"
                                                                    class="avtar avtar-xs btn-link-secondary">
                                                                    <i class="ti ti-chevron-right f-16"></i>
                                                                </a>
                                                            </div>
                                                            <div class="d-flex align-items-center mb-2">
                                                                <a href="#" class="avtar avtar-s btn-light-warning">
                                                                    <i class="ti ti-photo f-20"></i>
                                                                </a>
                                                                <div class="flex-grow-1 ms-3">
                                                                    <h6 class="mb-0">Photos</h6>
                                                                    <span class="text-muted text-sm">53 files, 321MB</span>
                                                                </div>
                                                                <a href="#"
                                                                    class="avtar avtar-xs btn-link-secondary">
                                                                    <i class="ti ti-chevron-right f-16"></i>
                                                                </a>
                                                            </div>
                                                            <div class="d-flex align-items-center mb-2">
                                                                <a href="#" class="avtar avtar-s btn-light-primary">
                                                                    <i class="ti ti-id f-20"></i>
                                                                </a>
                                                                <div class="flex-grow-1 ms-3">
                                                                    <h6 class="mb-0">Other</h6>
                                                                    <span class="text-muted text-sm">49 files, 193MB</span>
                                                                </div>
                                                                <a href="#"
                                                                    class="avtar avtar-xs btn-link-secondary">
                                                                    <i class="ti ti-chevron-right f-16"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
        <script src="{{ asset('storage//assets/js/plugins/sweetalert2.all.min.js') }}"></script>
        <script>
            $(document).ready(function() {
                // Select/Deselect all venues
                $('#select-all-venues').change(function() {
                    $('.venue-checkbox:visible').prop('checked', $(this).prop('checked'));
                    updateSelectedVenuesCount();
                });
            });
        </script>
        <script>
            $(document).ready(function() {
                // Initially hide all venue rows
                $('.venue-row').hide();

                // Handle center selection
                $('.center-list-item').click(function() {
                    // Get the center code
                    var centerCode = $(this).data('center-code');
                    // Remove active class from all center list items
                    $('.center-list-item').removeClass('list-group-item');

                    // Add active class to the clicked center list item
                    $(this).addClass('list-group-item-success');
                    // Hide all venue rows first
                    $('.venue-row').hide();

                    // Show venues for selected center
                    var $centerVenues = $('.venue-row[data-center-code="' + centerCode + '"]');
                    $centerVenues.show();

                    // Update header information
                    updateCenterInfo($(this), $centerVenues);
                });

                // Handle venue checkbox selection
                $(document).on('change', '.venue-checkbox', function() {
                    updateSelectedVenuesCount();
                });

                function updateCenterInfo(centerElement, $centerVenues) {
                    // Update center name and other details
                    var centerName = centerElement.find('.center-name').text();
                    var centerCode = centerElement.data('center-code');
                    var requiredHalls = centerElement.find('.required-halls').text();
                    var totalVenues = $centerVenues.length;

                    $('#selected-center-name').text(centerName);
                    $('#selected-center-code').text(centerCode);
                    $('#required-halls').text(requiredHalls);
                    $('#total-venues').text(totalVenues);

                    // Reset and update selected venues count
                    updateSelectedVenuesCount();
                }

                function updateSelectedVenuesCount() {
                    var $visibleVenues = $('.venue-row:visible');
                    var selectedVenues = $visibleVenues.find('.venue-checkbox:checked').length;
                    var totalVenues = $visibleVenues.length;

                    $('#selected-venues').text(selectedVenues);
                    $('#total-venues').text(totalVenues);
                }
            });
        </script>
        <!-- [Page Specific JS] start -->
        <script>
            // scroll-block
            var tc = document.querySelectorAll('.scroll-block');
            for (var t = 0; t < tc.length; t++) {
                new SimpleBar(tc[t]);
            }
            setTimeout(function() {
                var element = document.querySelector('.chat-content .scroll-block .simplebar-content-wrapper');
                var elementheight = document.querySelector('.chat-content .scroll-block .simplebar-content');
                var off = elementheight.getBoundingClientRect();
                var h = off.height;
                element.scrollTop += h;
            }, 100);
        </script>
        <script>
            $(document).ready(function() {
                // Send Mail Button Click Handler
                $('a.bg-light-success[href="#"]').click(function(e) {
                    e.preventDefault();

                    // Get the current selected center code
                    var centerCode = $('.center-list-item.list-group-item-success').data('center-code');

                    // Collect selected venues
                    var selectedVenues = [];
                    $('.venue-row:visible .venue-checkbox:checked').not(':disabled').each(function() {
                        var venueId = $(this).data('venue-code');
                        var hallCount = $(this).closest('tr').find('select[name="allocationCount"]')
                            .val();
                        var venueName = $(this).closest('tr').find('td[data-venue-name]').data(
                            'venue-name'); // Get venue name

                        selectedVenues.push({
                            venue_id: venueId,
                            venue_name: venueName,
                            halls_count: hallCount
                        });
                    });

                    // Validate selection
                    if (selectedVenues.length === 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'No Venues Selected',
                            text: 'Please select at least one venue',
                        });
                        return;
                    }
                    // Validate halls not selected
                    var venuesWithoutHalls = selectedVenues.filter(function(venue) {
                        return venue.halls_count === 0 || venue.halls_count === null || venue
                            .halls_count === undefined || venue.halls_count === '';
                    });

                    if (venuesWithoutHalls.length > 0) {
                        var venueNames = venuesWithoutHalls.map(function(venue) {
                            return venue.venue_name;
                        }).join(' || ');

                        Swal.fire({
                            icon: 'warning',
                            title: 'Halls Not Selected',
                            html: `Please select at least one hall for the following venues:<br><strong>${venueNames}</strong>`,
                        });
                        return;
                    }

                    // Send AJAX request
                    $.ajax({
                        url: '{{ route('district-candidates.processVenueConsentEmail') }}', // Create this route in web.php
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            center_code: centerCode,
                            exam_id: '{{ $examId }}', // Assuming you pass the current exam ID
                            venues: selectedVenues
                        },
                        beforeSend: function() {
                            // Show the loader before the request starts
                            const loader = document.getElementById('loader');
                            if (loader) {
                                loader.style.removeProperty('display');
                            }
                        },
                        success: function(response) {
                            // Hide the loader when the request is complete
                            const loader = document.getElementById('loader');
                            if (loader) {
                                loader.style.display = 'none';
                            }
                            // Handle success
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Consent emails sent successfully',
                            }).then(() => {
                                // Reload the page after the user acknowledges the success alert
                                location.reload();
                            });

                            // Optionally update UI to reflect sent status
                            selectedVenues.forEach(function(venue) {
                                $(`input[data-venue-code="${venue.venue_id}"]`)
                                    .closest('tr')
                                    .find('.badge')
                                    .removeClass('bg-light-warning')
                                    .addClass('bg-light-info')
                                    .text('Email Sent');
                            });
                        },
                        error: function(xhr) {
                            // Handle error
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Failed to send consent emails',
                            });
                            console.error(xhr.responseText);
                        }
                    });
                });
            });
        </script>

        <!-- [Page Specific JS] end -->
    @endpush
    @include('partials.theme')

@endsection
