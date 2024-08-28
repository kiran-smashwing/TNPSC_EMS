@extends('layouts.app')

@section('title', 'District Collectorates')
@push('styles')
    <link rel="stylesheet" href="{{ asset('storage/assets/css/plugins/croppr.min.css')}}" />
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
                                        <h5>District Collectorate - <span class="text-primary">Add</span></h5>
                                    </div>
                                    <div class="card-body">
                                        {{-- <form action="{{ route('collectorates.store') }}" method="POST" enctype="multipart/form-data"> --}}
                                        @csrf
                                        <div class="row">
                                            <div class="col-sm-6 text-center mb-3">
                                                <div class="user-upload wid-75"  data-pc-animate="just-me" data-bs-toggle="modal" data-bs-target="#cropperModal">
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
                                                    <label class="form-label">Name <span
                                                      class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="district_name"
                                                        name="district_name" placeholder="Chennai" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label ">Code<span
                                                      class="text-danger">*</span></label>
                                                    <input type="number"  class="form-control no-arrows" id="district_code"
                                                        name="district_code" placeholder="01" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Email<span
                                                      class="text-danger">*</span></label>
                                                    <input type="email" class="form-control" id="mail" name="mail"
                                                    placeholder="ceochn@***.in" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="phone">Phone<span
                                                      class="text-danger">*</span></label>
                                                    <input type="tel" class="form-control" id="phone" name="phone"
                                                    placeholder="9434***1212" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="alternate_phone">Alternate Phone</label>
                                                    <input type="tel" class="form-control" id="alternate_phone"
                                                        name="alternate_phone"  placeholder="O4448***762/9434***1212">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label"for="password">Password<span
                                                      class="text-danger">*</span></label>
                                                    <input type="password" class="form-control" id="password"
                                                        name="password" required  placeholder="******">
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
                                            <div class="col-sm-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Website<span
                                                      class="text-danger">*</span></label>
                                                    <input  type="url" class="form-control" id="website" name="website" placeholder="https://chennai.nic.in/">
                                                </div>
                                            </div>
                                              <div class="col-sm-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Address<span
                                                      class="text-danger">*</span></label>
                                                    <textarea class="form-control" id="address" name="address" required placeholder="Tamil Nadu Public Service Commission, TNPSC Road, Broadway, Chennai-600003."></textarea>
                                                </div>
                                            </div> 
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="longitude">longitude<span
                                                            class="text-danger">*</span></label>
                                                    <input type="number" step="any" class="form-control" id="longitude"
                                                        name="longitude" placeholder="11.2312312312312">
                                                </div>
                                            </div>
                                            <div class="col-sm-6 ">
                                                <div class="mb-3">
                                                    <label class="form-label"  for="latitude" >latitude<span
                                                            class="text-danger">*</span></label>
                                                    <input type="number" step="any" class="form-control"
                                                        id="latitude" name="latitude" placeholder="11.2312312312312">
                                                </div>
                                            </div>
                                            <div class="col-sm-6  d-inline-flex justify-content-center mb-3">
                                            <a href="#" class="btn btn-success d-inline-flex  justify-content-center"><i class="ti ti-current-location me-1"></i>Get Location Coordinates</a>
                                            </div>
                                             <div class="col-sm-6 d-inline-flex justify-content-center mb-3">
                                               <a href="https://www.google.com/maps" target="_blank" class="btn btn-info d-inline-flex  justify-content-center"><i class="ti ti-map-2 me-1"></i>Get Coordinates on G-Map</a>
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

    <script src="{{ asset('storage/assets/js/plugins/croppr.min.js')}}"></script>
    <script src="{{ asset('storage/assets/js/pages/page-croper.js')}}"></script>
 
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
    @include('partials.theme')

@endsection
