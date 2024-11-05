@extends('layouts.app')

@section('title', 'Mobile Team Staffs')
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
            <!-- [ Main Content ] start -->
            <!-- Modal start-->
            @include('modals.cropper')
            <!-- Modal start-->
            <div class="row">

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
                                        <h5>Mobile Team - <span class="text-primary">Edit</span></h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="{{ route('mobile-team.update', $mobileTeamStaff->mobile_id) }}"  method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="row">
                                                <div class="col-sm-6 text-center mb-3">
                                                    <div class="user-upload wid-75" data-pc-animate="just-me"
                                                        data-bs-toggle="modal" data-bs-target="#cropperModal">
                                                        <img src="{{ $mobileTeamStaff->mobile_image
                                                            ? asset('storage/' . $mobileTeamStaff->mobile_image)
                                                            : asset('storage/assets/images/user/avatar-4.jpg') }}"
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
                                                        <select class="form-control" id="district_id" name="district_id"
                                                            required>
                                                            <option>Select District</option>
                                                            @foreach ($districts as $district)
                                                                <option value="{{ $district->district_id }}"
                                                                    {{ $district->district_id == $mobileTeamStaff->mobile_district_id ? 'selected' : '' }}>
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
                                                        <label class="form-label">Name <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="name"
                                                            name="name" value="{{ $mobileTeamStaff->mobile_name }}"
                                                            required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="employee_id">Employee ID <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="employee_id"
                                                            name="employee_id"
                                                            value="{{ $mobileTeamStaff->mobile_employeeid }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="designation">Designation <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="designation"
                                                            name="designation"
                                                            value="{{ $mobileTeamStaff->mobile_designation }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Email<span
                                                                class="text-danger">*</span></label>
                                                        <input type="email" class="form-control" id="mail"
                                                            name="mail" value="{{ $mobileTeamStaff->mobile_email }}"
                                                            required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="phone">Phone<span
                                                                class="text-danger">*</span></label>
                                                        <input type="tel" class="form-control" id="phone"
                                                            name="phone" value="{{ $mobileTeamStaff->mobile_phone }}"
                                                            required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="password">Password <small>(Leave
                                                                blank
                                                                to keep current)</small></label>
                                                        <input type="password" class="form-control" id="password"
                                                            name="password" placeholder="******">
                                                    </div>
                                                </div>
                                            </div>
                                         
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 text-end btn-page">
                              <a href="{{ route('mobile-team') }}" class="btn btn-outline-secondary">Cancel</a>
                              <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
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
