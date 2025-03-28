@extends('layouts.app')

@section('title', 'Edit Treasury Officer')

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
                <div class="col-sm-12">
                </div>
                <div class="tab-content">
                    <form action="{{ route('treasury-officers.update', $treasuryOfficer->tre_off_id) }}" method="POST"
                        enctype="multipart/form-data">
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
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5>Treasury Officer - <span class="text-primary">Edit</span></h5>
                                            </div>
                                            <div class="card-body">

                                                @csrf
                                                @method('PUT')
                                                <div class="row">
                                                    <div class="col-sm-6 text-center mb-3">
                                                        <div class="user-upload wid-75" data-pc-animate="just-me"
                                                            data-bs-toggle="modal" data-bs-target="#cropperModal">
                                                            <img src="{{ $treasuryOfficer->tre_off_image
                                                                ? asset('storage/' . $treasuryOfficer->tre_off_image)
                                                                : asset('storage/assets/images/user/avatar-4.jpg') }}"
                                                                id="previewImage" alt="Cropped Preview"
                                                                style="max-width: 100%; height: auto; object-fit: cover;">
                                                            <input type="hidden" name="cropped_image" id="cropped_image">
                                                            <label for="imageUpload" class="img-avtar-upload"></label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="district">District <span
                                                                    class="text-danger">*</span></label>
                                                            <select
                                                                class="form-control @error('district') is-invalid @enderror"
                                                                id="district" name="district" required {{ session('auth_role') == 'district' ? 'disabled' : '' }}>
                                                                <option value="">Select District</option>
                                                                @foreach ($districts as $district)
                                                                    <option value="{{ $district->district_code }}"
                                                                        {{ $district->district_code == $treasuryOfficer->tre_off_district_id ? 'selected' : '' }}>
                                                                        {{ $district->district_name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @if (session('auth_role') == 'district')
                                                            <input type="hidden" name="district"
                                                                value="{{ $treasuryOfficer->tre_off_district_id ?? '' }}">
                                                        @endif
                                                            @error('district')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="name">Name <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text"
                                                                class="form-control @error('name') is-invalid @enderror"
                                                                id="name" name="name"
                                                                value="{{ $treasuryOfficer->tre_off_name }}" required>
                                                            @error('name')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="employeeid">Employee ID <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text"
                                                                class="form-control @error('employeeid') is-invalid @enderror"
                                                                id="employeeid" name="employeeid"
                                                                value="{{ $treasuryOfficer->tre_off_employeeid }}"
                                                                required>
                                                            @error('employeeid')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="designation">Designation <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text"
                                                                class="form-control @error('designation') is-invalid @enderror"
                                                                id="designation" name="designation"
                                                                value="{{ $treasuryOfficer->tre_off_designation }}"
                                                                required>
                                                            @error('designation')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="email">Email<span
                                                                    class="text-danger">*</span></label>
                                                            <input type="email"
                                                                class="form-control @error('email') is-invalid @enderror"
                                                                id="email" name="email"
                                                                value="{{ $treasuryOfficer->tre_off_email }}" required>
                                                            @error('email')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="phone">Phone<span
                                                                    class="text-danger">*</span></label>
                                                            <input type="tel"
                                                                class="form-control @error('phone') is-invalid @enderror"
                                                                id="phone" name="phone"
                                                                value="{{ $treasuryOfficer->tre_off_phone }}" required>
                                                            @error('phone')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="password">Password<small>(leave
                                                                    blank to keep
                                                                    current)</small></label>
                                                            <input type="password"
                                                                class="form-control @error('password') is-invalid @enderror"
                                                                id="password" name="password" placeholder="******">
                                                            @error('password')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 text-end btn-page">
                                                    <a href="{{ route('treasury-officers.index') }}"
                                                        class="btn btn-outline-secondary">Cancel</a>
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
