@extends('layouts.app')

@section('title', 'ROUTE - Create')
@push('styles')
    <link rel="stylesheet" href="{{ asset('storage/assets/css/plugins/croppr.min.css') }}" />
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
    <!-- [ Header Topbar ] end -->

    <div class="pc-container">
        <div class="pc-content">
            <!-- Modal start-->
            @include('modals.cropper')
            <!-- Modal start-->
            <!-- [ Main Content ] start -->
            <div class="row">

                <div class="tab-content">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Route - <span class="text-primary">Add</span></h5>
                                </div>
                                <div class="card-body">
                                    {{-- <form action="{{ route('collectorates.store') }}" method="POST" enctype="multipart/form-data"> --}}
                                    @csrf
                                    <div class="row">
                                        <div class="col-sm-6 text-center mb-3">
                                            <div class="user-upload wid-75" data-pc-animate="just-me" data-bs-toggle="modal"
                                                data-bs-target="#cropperModal">
                                                <img src="{{ asset('storage/assets/images/user/collectorate.png') }}"
                                                    alt="img" class="img-fluid">
                                                <label for="image" class="img-avtar-upload">
                                                    <i class="ti ti-camera f-24 mb-1"></i>
                                                    <span>Upload</span>
                                                </label>
                                                {{-- <input type="file" id="image" name="image" class="d-none"> --}}
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Route no <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="route_no" name="route_no"
                                                    placeholder="001" required>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Driver Name<span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="driver_name"
                                                    name="driver_name" placeholder="vijay" required>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Driver Licenese No<span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="driver_licence_no"
                                                    name="driver_licence_no" placeholder="DLR0101223" required>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="phone">Driver Phone<span
                                                        class="text-danger">*</span></label>
                                                <input type="tel" class="form-control" id="phone" name="phone"
                                                    placeholder="9434***1212" required>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="vehicle_no">Vehicle No<span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="vehicle_no" name="vehicle_no"
                                                    placeholder="TN 01 2345" required>
                                            </div>
                                        </div>
                                        {{-- <div class="col-sm-6">
                                              <div class="mb-3">
                                                  <label class="form-label" for="status">Status</label>
                                                  <select class="form-control" id="status" name="status" required>
                                                    <option value="Active">Active</option>
                                                    <option value="Inactive">Inactive</option>
                                                  </select>
                                              </div>
                                          </div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="mobile_staff">Mobile Staff<span
                                                        class="text-danger">*</span></label>
                                                <select class="form-control" name="mobile_staff" required data-trigger
                                                    id="choices-single-default">
                                                    <option value="1">John Doe</option>
                                                    <option value="2">Jane Smith</option>
                                                    <option value="3">Michael Johnson</option>
                                                    <option value="4">Emily Davis</option>
                                                </select>

                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="center">Center <span
                                                        class="text-danger">*</span></label>
                                                <select class="form-control" name="center" id="center-select" multiple>
                                                    <option value="1"  >Chennai</option>
                                                    <option value="2" >Arcot</option>
                                                    <option value="3">Vellore</option>
                                                    <option value="4">Tirupattur</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="halls">Halls<span
                                                        class="text-danger">*</span></label>
                                                <select class="form-control" name="halls" id="halls-select" multiple>
                                                    <optgroup label="Chennai">
                                                        <option value="1" >Hall 1</option>
                                                        <option value="2">Hall 2</option>
                                                        <option value="3" >Hall 3</option>
                                                        <option value="4">Hall 4</option>
                                                    </optgroup>
                                                    <optgroup label="Arcot">
                                                        <option value="5">Hall 5</option>
                                                        <option value="6" >Hall 6</option>
                                                        <option value="7" >Hall 7</option>
                                                        <option value="8">Hall 8</option>
                                                    </optgroup>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 text-end btn-page">
                            <div class="btn btn-outline-secondary">Cancel</div>
                            <div class="btn btn-primary">Create</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ Main Content ] end -->
    </div>

    @include('partials.footer')

    <script src="{{ asset('storage/assets/js/plugins/croppr.min.js') }}"></script>
    <script src="{{ asset('storage/assets/js/pages/page-croper.js') }}"></script>
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
            // Initialize Choices for the Center select
            var centerSelect = new Choices('#center-select', {
                removeItemButton: true,
                placeholderValue: 'Select Center',
                searchPlaceholderValue: 'Search Centers'
            });

            // Initialize Choices for the Halls select
            var hallsSelect = new Choices('#halls-select', {
                removeItemButton: true,
                placeholderValue: 'Select Halls',
                searchPlaceholderValue: 'Search Halls'
            });
        });
    </script>
    @include('partials.theme')

@endsection
