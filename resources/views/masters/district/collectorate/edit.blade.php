@extends('layouts.app')

@section('title', 'District Collectorates')
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
            @include('modals.cropper')
            <div class="row">
                <div class="tab-content">
                    <div>
                        <form action="{{ route('district.update', $district->district_id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
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
                                <div class="col-lg-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>District Collectorate - <span class="text-primary">Edit</span></h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-6 text-center mb-3">
                                                    <div class="user-upload wid-75" data-pc-animate="just-me"
                                                        data-bs-toggle="modal" data-bs-target="#cropperModal">
                                                        <img src="{{ $district->district_image
                                                            ? asset('storage/' . $district->district_image)
                                                            : asset('storage/assets/images/user/collectorate.png') }}"
                                                            id="previewImage" alt="Cropped Preview"
                                                            style="max-width: 100%; height: auto; object-fit: cover;">
                                                        <input type="hidden" name="cropped_image" id="cropped_image">
                                                        <label for="imageUpload" class="img-avtar-upload"></label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Name <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text"
                                                            class="form-control @error('district_name') is-invalid @enderror"
                                                            id="district_name" name="district_name"
                                                            value="{{ old('district_name', $district->district_name) }}"
                                                            required>
                                                        @error('district_name')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Code<span
                                                                class="text-danger">*</span></label>
                                                        <input type="number"
                                                            class="form-control no-arrows @error('district_code') is-invalid @enderror"
                                                            id="district_code" name="district_code"
                                                            value="{{ old('district_code', $district->district_code) }}"
                                                            required>
                                                        @error('district_code')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Email<span
                                                                class="text-danger">*</span></label>
                                                        <input type="email"
                                                            class="form-control @error('mail') is-invalid @enderror"
                                                            id="mail" name="mail"
                                                            value="{{ old('mail', $district->district_email) }}" required>
                                                        @error('mail')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Phone<span
                                                                class="text-danger">*</span></label>
                                                        <input type="tel"
                                                            class="form-control @error('phone') is-invalid @enderror"
                                                            id="phone" name="phone"
                                                            value="{{ old('phone', $district->district_phone) }}" required>
                                                        @error('phone')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Alternate Phone</label>
                                                        <input type="tel"
                                                            class="form-control @error('alternate_phone') is-invalid @enderror"
                                                            id="alternate_phone" name="alternate_phone"
                                                            value="{{ old('alternate_phone', $district->district_alternate_phone) }}">
                                                        @error('alternate_phone')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Password <small>(leave blank to keep
                                                                current)</small></label>
                                                        <input type="password"
                                                            class="form-control @error('password') is-invalid @enderror"
                                                            id="password" name="password">
                                                        @error('password')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
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
                                                        <input type="url"
                                                            class="form-control @error('website') is-invalid @enderror"
                                                            id="website" name="website"
                                                            value="{{ old('website', $district->district_website) }}"
                                                            required>
                                                        @error('website')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="mb-3">
                                                        <label class="form-label">Address<span
                                                                class="text-danger">*</span></label>
                                                        <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" required>{{ old('address', $district->district_address) }}</textarea>
                                                        @error('address')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Longitude<span
                                                                class="text-danger">*</span></label>
                                                        <input type="number" step="any"
                                                            class="form-control @error('longitude') is-invalid @enderror"
                                                            id="longitude" name="longitude"
                                                            value="{{ old('longitude', $district->district_longitude) }}"
                                                            required>
                                                        @error('longitude')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Latitude<span
                                                                class="text-danger">*</span></label>
                                                        <input type="number" step="any"
                                                            class="form-control @error('latitude') is-invalid @enderror"
                                                            id="latitude" name="latitude"
                                                            value="{{ old('latitude', $district->district_latitude) }}"
                                                            required>
                                                        @error('latitude')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <!-- Location buttons remain same -->
                                                <div class="col-sm-6 d-inline-flex justify-content-center mb-3">
                                                    <a href="#"
                                                        class="btn btn-success d-inline-flex justify-content-center">
                                                        <i class="ti ti-current-location me-1"></i>Get Location Coordinates
                                                    </a>
                                                </div>
                                                <div class="col-sm-6 d-inline-flex justify-content-center mb-3">
                                                    <a href="https://www.google.com/maps" target="_blank"
                                                        class="btn btn-info d-inline-flex justify-content-center">
                                                        <i class="ti ti-map-2 me-1"></i>Get Coordinates on G-Map
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 text-end btn-page">
                                    <a href="{{ route('district.index') }}" class="btn btn-outline-secondary">Cancel</a>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')

    @push('scripts')
        <script src="{{ asset('storage/assets/js/plugins/croppr.min.js') }}"></script>
        <script src="{{ asset('storage/assets/js/pages/page-croper.js') }}"></script>
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
            document.getElementById('triggerModal').addEventListener('click', function() {
                var modal = new bootstrap.Modal(document.getElementById('cropperModal'));
                modal.show();
            });
        </script>
    @endpush

    @include('partials.theme')

@endsection
