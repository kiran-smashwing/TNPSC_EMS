@extends('layouts.app')

@section('title', 'Chief Invigilator')

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
            <!-- Modal end -->

            <!-- [ Main Content ] start -->
            <div class="row">
                <div class="col-sm-12"></div>
                <div class="tab-content">
                    <div>
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
                                        <h5>Chief Invigilator - <span class="text-primary">Edit</span></h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="{{ route('chief-invigilator.update', $chiefInvigilator->ci_id) }}"
                                            method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')

                                            <div class="row">
                                                <div class="col-sm-12 text-center mb-3">
                                                    <div class="user-upload wid-75" data-pc-animate="just-me"
                                                        data-bs-toggle="modal" data-bs-target="#cropperModal">
                                                        <img src="{{ $chiefInvigilator->ci_image
                                                            ? asset('storage/' . $chiefInvigilator->ci_image)
                                                            : asset('storage/assets/images/user/avatar-4.jpg') }}"
                                                            id="previewImage" alt="Cropped Preview"
                                                            style="max-width: 100%; height: auto; object-fit: cover;">
                                                        <input type="hidden" name="cropped_image" id="cropped_image">
                                                        <label for="imageUpload" class="img-avtar-upload"></label>
                                                    </div>
                                                </div>


                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="ci_district_id">District<span
                                                                class="text-danger">*</span></label>
                                                        <select class="form-control" id="ci_district_id"
                                                            name="ci_district_id" required>
                                                            <option value="">Select District</option>
                                                            @foreach ($districts as $district)
                                                                <option value="{{ $district->district_id }}"
                                                                    {{ old('ci_district_id', $chiefInvigilator->ci_district_id) == $district->district_id ? 'selected' : '' }}>
                                                                    {{ $district->district_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="ci_center_id">Center<span
                                                                class="text-danger">*</span></label>
                                                        <select class="form-control" id="ci_center_id" name="ci_center_id"
                                                            required>
                                                            <option value="">Select Center</option>
                                                            @foreach ($centers as $center)
                                                                <option value="{{ $center->center_id }}"
                                                                    {{ old('ci_center_id', $chiefInvigilator->ci_center_id) == $center->center_id ? 'selected' : '' }}>
                                                                    {{ $center->center_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="ci_venue_id">Venue<span
                                                                class="text-danger">*</span></label>
                                                        <select class="form-control" id="ci_venue_id" name="ci_venue_id"
                                                            required>
                                                            <option value="">Select Venue</option>
                                                            @foreach ($venues as $venue)
                                                                <option value="{{ $venue->venue_id }}"
                                                                    {{ old('ci_venue_id', $chiefInvigilator->ci_venue_id) == $venue->venue_id ? 'selected' : '' }}>
                                                                    {{ $venue->venue_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Name <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="ci_name"
                                                            name="ci_name"
                                                            value="{{ old('ci_name', $chiefInvigilator->ci_name) }}"
                                                            required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="designation">Designation <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="ci_designation"
                                                            name="ci_designation"
                                                            value="{{ old('ci_designation', $chiefInvigilator->ci_designation) }}"
                                                            required>
                                                    </div>
                                                </div>

                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Email <span
                                                                class="text-danger">*</span></label>
                                                        <input type="email" class="form-control" id="ci_email"
                                                            name="ci_email"
                                                            value="{{ old('email', $chiefInvigilator->ci_email) }}"
                                                            required>
                                                    </div>
                                                </div>

                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="phone">Phone <span
                                                                class="text-danger">*</span></label>
                                                        <input type="tel" class="form-control" id="ci_phone"
                                                            name="ci_phone"
                                                            value="{{ old('ci_phone', $chiefInvigilator->ci_phone) }}"
                                                            required>
                                                    </div>
                                                </div>

                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="password">Password <span
                                                                class="text-danger">*</span></label>
                                                        <input type="password" class="form-control" id="ci_password"
                                                            name="password" placeholder="Leave blank to keep current">
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 text-end btn-page">
                                <a href="{{ route('chief-invigilator') }}" class="btn btn-outline-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>
    <!-- [ Main Content ] end -->
    </div>
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
