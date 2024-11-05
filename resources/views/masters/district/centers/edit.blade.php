@extends('layouts.app')
@section('title', 'Edit Center')
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
                                    <form action="{{ route('center.update', $center->center_id) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="row">

                                            <div class="col-sm-6 text-center mb-3">
                                                <div class="user-upload wid-75" data-pc-animate="just-me"
                                                    data-bs-toggle="modal" data-bs-target="#cropperModal">
                                                    <img src="{{ $center->center_image
                                                        ? asset('storage/' . $center->center_image)
                                                        : asset('storage/assets/images/user/center.png') }}"
                                                        id="previewImage" alt="Cropped Preview"
                                                        style="max-width: 100%; height: auto; object-fit: cover;">
                                                    <input type="hidden" name="cropped_image" id="cropped_image">
                                                    <label for="imageUpload" class="img-avtar-upload"></label>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="district_id">District <span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-control" id="district_id" name="district_id" required>
                                                        <option>Select District</option>
                                                        @foreach ($districts as $district)
                                                            <option value="{{ $district->district_id }}"
                                                                {{ $district->district_id == $center->center_district_id ? 'selected' : '' }}>
                                                                {{ $district->district_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('district_id')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Name <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="center_name"
                                                        name="center_name" value="{{ old('center_name', $center->center_name) }}"
                                                        placeholder="Alandur" required>
                                                    @error('center_name')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Code <span class="text-danger">*</span></label>
                                                    <input type="number" class="form-control no-arrows" id="center_code"
                                                        name="center_code" value="{{ old('center_code', $center->center_code) }}"
                                                        placeholder="0102" required>
                                                    @error('center_code')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-12 text-end btn-page">
                                            <a href="{{ route('center') }}" class="btn btn-outline-secondary">Cancel</a>
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ Main Content ] end -->
    </div>

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
    @endpush
    @include('partials.theme')

@endsection
