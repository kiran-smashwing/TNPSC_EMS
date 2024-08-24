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
                                                <h5 class="">Thiruvannamalai<span
                                                        class="me-2 btn btn-sm bg-light-primary ">10/12</span> </h5>
                                                {{-- <div class="form-search">
                          <i class="ti ti-search"></i>
                          <input type="search" class="form-control" placeholder="Search Followers" />
                        </div> --}}
                                            </div>
                                            <div class="scroll-block">
                                                <div class="card-body p-0">
                                                    <div class="list-group list-group-flush">
                                                        <a href="#"
                                                            class="list-group-item list-group-item-action p-3">
                                                            <div class="d-flex align-items-center">
                                                                
                                                                <div class="flex-grow-1 mx-2">
                                                                    <h6 class="mb-0">Thiruvannamalai 
                                                                    </h6>
                                                                    <span class="text-sm text-muted">Required Halls - 100
                                                                    </span>
                                                                </div>
                                                                <div class="chat-avtar text-md text-warning f-w-400 fs-5">
                                                                    2201
                                                                </div>
                                                            </div>
                                                        </a>
                                                        <a href="#"
                                                            class="list-group-item list-group-item-action p-3">
                                                            <div class="d-flex align-items-center">
                                                               
                                                                <div class="flex-grow-1 mx-2">
                                                                    <h6 class="mb-0">Arni</h6>
                                                                    <span class="text-sm text-muted">19-07-2024 12:00 PM
                                                                        {{-- <span class="float-end">
                                        <span class="chat-badge-status bg-success text-white"><i class="ti ti-check"></i></span> </span
                                    ></span> --}}
                                                                </div>
                                                                <div class="chat-avtar text-md text-warning f-w-400 fs-5">
                                                                    2201
                                                                </div>
                                                            </div>
                                                        </a>
                                                        <a href="#"
                                                            class="list-group-item list-group-item-action p-3">
                                                            <div class="d-flex align-items-center">
                                                             
                                                                <div class="flex-grow-1 mx-2">
                                                                    <h6 class="mb-0">Chengam</h6>
                                                                    <span class="text-sm text-muted">19-07-2024 12:00 PM
                                                                        {{-- <span class="float-end">
                                        <span class="chat-badge-status bg-success text-white"><i class="ti ti-check"></i></span> </span
                                    ></span> --}}
                                                                </div>
                                                                <div class="chat-avtar text-md text-warning f-w-400 fs-5">
                                                                    2201
                                                                </div>
                                                            </div>
                                                        </a>
                                                        <a href="#"
                                                            class="list-group-item list-group-item-action p-3">
                                                            <div class="d-flex align-items-center">
                                                               
                                                                <div class="flex-grow-1 mx-2">
                                                                    <h6 class="mb-0">Chetpet</h6>
                                                                    <span class="text-sm text-muted">19-07-2024 12:00 PM
                                                                        {{-- <span class="float-end">
                                        <span class="chat-badge-status bg-success text-white"><i class="ti ti-check"></i></span> </span
                                    ></span> --}}
                                                                </div>
                                                                <div class="chat-avtar text-md text-warning f-w-400 fs-5">
                                                                    2201
                                                                </div>
                                                            </div>
                                                        </a>
                                                        <a href="#"
                                                            class="list-group-item list-group-item-action p-3">
                                                            <div class="d-flex align-items-center">
                                                               
                                                                <div class="flex-grow-1 mx-2">
                                                                    <h6 class="mb-0">Cheyyar</h6>
                                                                    <span class="text-sm text-muted">19-07-2024 12:00 PM
                                                                        {{-- <span class="float-end">
                                        <span class="chat-badge-status bg-success text-white"><i class="ti ti-check"></i></span> </span
                                    ></span> --}}
                                                                </div>
                                                                <div class="chat-avtar text-md text-warning f-w-400 fs-5">
                                                                    2201
                                                                </div>
                                                            </div>
                                                        </a>
                                                        <a href="#"
                                                            class="list-group-item list-group-item-action p-3">
                                                            <div class="d-flex align-items-center">
                                                               
                                                                <div class="flex-grow-1 mx-2">
                                                                    <h6 class="mb-0">Jamunamarathur</h6>
                                                                    <span class="text-sm text-muted">19-07-2024 12:00 PM
                                                                        {{-- <span class="float-end">
                                        <span class="chat-badge-status bg-success text-white"><i class="ti ti-check"></i></span> </span
                                    ></span> --}}
                                                                </div>
                                                                <div class="chat-avtar text-md text-warning f-w-400 fs-5">
                                                                    2201
                                                                </div>
                                                            </div>
                                                        </a>
                                                        <a href="#"
                                                            class="list-group-item list-group-item-action p-3">
                                                            <div class="d-flex align-items-center">
                                                               
                                                                <div class="flex-grow-1 mx-2">
                                                                    <h6 class="mb-0">Kalasapakkam</h6>
                                                                    <span class="text-sm text-muted">19-07-2024 12:00 PM
                                                                        {{-- <span class="float-end">
                                        <span class="chat-badge-status bg-success text-white"><i class="ti ti-check"></i></span> </span
                                    ></span> --}}
                                                                </div>
                                                                <div class="chat-avtar text-md text-warning f-w-400 fs-5">
                                                                    2201
                                                                </div>
                                                            </div>
                                                        </a>
                                                        <a href="#"
                                                            class="list-group-item list-group-item-action p-3">
                                                            <div class="d-flex align-items-center">
                                                               
                                                                <div class="flex-grow-1 mx-2">
                                                                    <h6 class="mb-0">Kilpennathur</h6>
                                                                    <span class="text-sm text-muted">19-07-2024 12:00 PM
                                                                        {{-- <span class="float-end">
                                        <span class="chat-badge-status bg-success text-white"><i class="ti ti-check"></i></span> </span
                                    ></span> --}}
                                                                </div>
                                                                <div class="chat-avtar text-md text-warning f-w-400 fs-5">
                                                                    2201
                                                                </div>
                                                            </div>
                                                        </a>
                                                        <a href="#"
                                                            class="list-group-item list-group-item-action p-3">
                                                            <div class="d-flex align-items-center">
                                                               
                                                                <div class="flex-grow-1 mx-2">
                                                                    <h6 class="mb-0">Polur</h6>
                                                                    <span class="text-sm text-muted">19-07-2024 12:00 PM
                                                                        {{-- <span class="float-end">
                                        <span class="chat-badge-status bg-success text-white"><i class="ti ti-check"></i></span> </span
                                    ></span> --}}
                                                                </div>
                                                                <div class="chat-avtar text-md text-warning f-w-400 fs-5">
                                                                    2201
                                                                </div>
                                                            </div>
                                                        </a>
                                                        <a href="#"
                                                            class="list-group-item list-group-item-action p-3">
                                                            <div class="d-flex align-items-center">
                                                               
                                                                <div class="flex-grow-1 mx-2">
                                                                    <h6 class="mb-0">Thandramet</h6>
                                                                    <span class="text-sm text-muted">19-07-2024 12:00 PM
                                                                        {{-- <span class="float-end">
                                        <span class="chat-badge-status bg-success text-white"><i class="ti ti-check"></i></span> </span
                                    ></span> --}}
                                                                </div>
                                                                <div class="chat-avtar text-md text-warning f-w-400 fs-5">
                                                                    2201
                                                                </div>
                                                            </div>
                                                        </a>
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
                                                <a href="#" class="badge bg-dark ">Requested 150</a>
                                            </li>
                                            <li class="list-inline-item">
                                                <a href="#" class="badge bg-success">Accepted 110</a>
                                            </li>
                                            <li class="list-inline-item"><a href="#"
                                                    class="badge bg-info">Selected 40 / 100</a></li>
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
                                                        </tr>

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
                                <button class="btn-close" data-bs-dismiss="offcanvas"
                                    data-bs-target="#offcanvas_User_info" aria-label="Close"></button>
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
        <!-- [Page Specific JS] end -->
    @endpush
    @include('partials.theme')

@endsection
