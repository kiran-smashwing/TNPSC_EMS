@extends('layouts.app')
@section('title', ' Edit Centers')
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


    <!-- [ Main Content ] start -->

    <div class="pc-container">
        <div class="pc-content">
             <!-- Modal start-->
             @include('modals.cropper')
                  <!-- Modal end-->
            <!-- [ Main Content ] start -->
            <div class="row">

                <div class="tab-content">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Center - <span class="text-primary">Edit</span></h5>
                                </div>
                                <div class="card-body">
                                    {{-- <form action="{{ route('collectorates.store') }}" method="POST" enctype="multipart/form-data"> --}}
                                    @csrf
                                    <div class="row">
                                        <div class="col-sm-6 text-center mb-3">
                                            <div class="user-upload wid-75" id="triggerModal">
                                                <img src="{{ asset('storage/assets/images/user/center.png') }}"
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
                                                <label class="form-label" for="district_id">District</label>
                                                <select class="form-control" id="district_id" name="district_id" required>
                                                    <option>Select District</option>
                                                    <option value="1010">Chennai</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="center_name"
                                                    name="center_name" placeholder="Alandur" required>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label ">Code<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control no-arrows" id="district_code"
                                                    name="district_code" placeholder="0102" required>
                                            </div>
                                        </div>   
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="designation">Designation <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="designation"
                                                    name="designation" placeholder="Thasildar" required>
                                            </div>
                                        </div>         
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="col-lg-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="mb-3">
                                                <label class="form-label">Website<span class="text-danger">*</span></label>
                                                <input type="url" class="form-control" id="website" name="website"
                                                    placeholder="https://chennai.nic.in/">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="mb-3">
                                                <label class="form-label">Address<span
                                                        class="text-danger">*</span></label>
                                                <textarea class="form-control" id="address" name="address" required
                                                    placeholder="Tamil Nadu Public Service Commission, TNPSC Road, Broadway, Chennai-600003."></textarea>
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
                                                <label class="form-label" for="latitude">latitude<span
                                                        class="text-danger">*</span></label>
                                                <input type="number" step="any" class="form-control" id="latitude"
                                                    name="latitude" placeholder="11.2312312312312">
                                            </div>
                                        </div>
                                        <div class="col-sm-6  d-inline-flex justify-content-center">
                                            <a href="#"
                                                class="btn btn-success d-inline-flex  justify-content-center"><i
                                                    class="ti ti-current-location me-1"></i>Get Location Coordinates</a>
                                        </div>
                                        <div class="col-sm-6 d-inline-flex justify-content-center">
                                            <a href="https://www.google.com/maps" target="_blank"
                                                class="btn btn-info d-inline-flex  justify-content-center"><i
                                                    class="ti ti-map-2 me-1"></i>Get Coordinates on G-Map</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                        <div class="col-12 text-end btn-page">
                            <div class="btn btn-outline-secondary">Cancel</div>
                            <div class="btn btn-primary">Update</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ Main Content ] end -->
    </div>

    <!-- [ Main Content ] end -->
    @include('partials.footer')
    @push('scripts')
    <script src="{{ asset('storage/assets/js/plugins/croppr.min.js')}}"></script>
    <script src="{{ asset('storage/assets/js/pages/page-croper.js')}}"></script>
    <script>
        document.getElementById('triggerModal').addEventListener('click', function() {
            var modal = new bootstrap.Modal(document.getElementById('cropperModal'));
            modal.show();
        });
    </script>
    @endpush
    @include('partials.theme')

@endsection
