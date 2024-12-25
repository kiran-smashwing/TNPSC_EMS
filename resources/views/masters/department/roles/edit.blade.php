@extends('layouts.app')
@section('title', ' Edit Role')

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
            <!-- [ Main Content ] start -->
            <div class="row">

                <div class="tab-content">
                    <form action="{{ route('roles.update', $role->role_id) }}" method="POST" enctype="multipart/form-data">
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
                                        <h5>Role - <span class="text-primary">Edit</span></h5>
                                    </div>
                                    <div class="card-body">

                                        <div class="row">

                                            <div class="col-sm-12">
                                                <div class="mb-3">
                                                    <label class="form-label" for="role_department">Department<span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-control @error('role_department') is-invalid @enderror" id="role_department" name="role_department"
                                                        required>
                                                        <option value="">Select Department</option>
                                                        <option value="APD"
                                                            {{ $role->role_department == 'APD' ? 'selected' : '' }}>
                                                            Application
                                                            Processing Department - APD</option>
                                                        <option value="ID"
                                                            {{ $role->role_department == 'ID' ? 'selected' : '' }}>
                                                            Infrastructure Department - ID</option>
                                                        <option value="RND"
                                                            {{ $role->role_department == 'RND' ? 'selected' : '' }}>Rules
                                                            Notification Department - RND</option>
                                                        <option value="ED"
                                                            {{ $role->role_department == 'ED' ? 'selected' : '' }}>
                                                            Evaluation
                                                            Department - ED</option>
                                                        <option value="QD"
                                                            {{ $role->role_department == 'QD' ? 'selected' : '' }}>
                                                            Confidential
                                                            Department - QD</option>
                                                        <option value="VMD"
                                                            {{ $role->role_department == 'VMD' ? 'selected' : '' }}>Vehicles
                                                            Machineries Department - VMD</option>
                                                        <option value="VSD"
                                                            {{ $role->role_department == 'VSD' ? 'selected' : '' }}>
                                                            Verification
                                                            Stationary Department - VSD</option>
                                                        <option value="MCD"
                                                            {{ $role->role_department == 'MCD' ? 'selected' : '' }}>
                                                            Monitoring
                                                            Coordination Department - MCD</option>
                                                    </select>
                                                    @error('role_department')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="mb-3">
                                                    <label class="form-label" for="role_name">Role<span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" class="form-control @error('role_name') is-invalid @enderror" id="role_name"
                                                        name="role_name" placeholder="Enter role"
                                                        value="{{ old('role_name', $role->role_name) }}" required>
                                                    @error('role_name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 text-end btn-page">
                                <a href="{{ route('role') }}" class="btn btn-outline-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- [ Main Content ] end -->
    </div>

    <!-- [ Main Content ] end -->
    @include('partials.footer')

    @include('partials.theme')

@endsection
