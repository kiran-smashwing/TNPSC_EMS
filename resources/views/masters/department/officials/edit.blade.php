@extends('layouts.app')

@section('title', 'Department Officials')

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
                <div class="col-sm-12">

                </div>

                <div class="tab-content">
                    <form action="{{ route('department-officials.update', $official->dept_off_id) }}" method="POST"
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
                                        <h5>Department Officer - <span class="text-primary">Edit</span></h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-6 text-center mb-3">
                                                <div class="user-upload wid-75" data-pc-animate="just-me"
                                                    data-bs-toggle="modal" data-bs-target="#cropperModal">
                                                    <img src="{{ $official->dept_off_image
                                                        ? asset('storage/' . $official->dept_off_image)
                                                        : asset('storage/assets/images/user/avatar-4.jpg') }}"
                                                        id="previewImage" alt="Cropped Preview"
                                                        style="max-width: 100%; height: auto; object-fit: cover;">
                                                    <input type="hidden" name="cropped_image" id="cropped_image">
                                                    <label for="imageUpload" class="img-avtar-upload"></label>
                                                </div>
                                            </div>
                                            {{-- <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="role">Role <span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-control @error('role') is-invalid @enderror" id="role" name="role" required>
                                                        <option>Select Role</option>
                                                        @foreach ($roles as $role)
                                                            <option value="{{ $role->role_id }}"
                                                                {{ $official->dept_off_role == $role->role_id ? 'selected' : '' }}>
                                                                {{ $role->role_department }} - {{ $role->role_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('role')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div> --}}

                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="name">Name <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                                                        value="{{ old('name', $official->dept_off_name) }}"
                                                        placeholder="Nanmaran" required>
                                                    @error('name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="employee_id">Employee ID <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" class="form-control @error('employee_id') is-invalid @enderror"
                                                        value="{{ old('employee_id', $official->dept_off_emp_id) }}"
                                                        id="employee_id" name="employee_id" placeholder="EMP1234" required>
                                                    @error('employee_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="designation">Designation
                                                        <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control @error('designation') is-invalid @enderror"
                                                        value="{{ old('designation', $official->dept_off_designation) }}"
                                                        id="designation" name="designation" placeholder="Thasildar"
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
                                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                        value="{{ old('email', $official->dept_off_email) }}"
                                                        id="email" name="email" placeholder="ceochn@***.in"
                                                        required>
                                                    @error('email')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="phone">Phone<span
                                                            class="text-danger">*</span></label>
                                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                                        value="{{ old('phone', $official->dept_off_phone) }}"
                                                        id="phone" name="phone" placeholder="9434***1212" required>
                                                    @error('phone')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="password">Password <small>(leave blank to keep
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

                            <div class="col-12 text-end btn-page">
                                <a href="{{ route('department-officials.index') }}" class="btn btn-outline-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Update</button>

                            </div>
                        </div>
                    </form>
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
