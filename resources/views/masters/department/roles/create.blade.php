@extends('layouts.app')
@section('title', ' Add Role')

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
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Role - <span class="text-primary">Add</span></h5>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('role.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">

                                        <div class="col-sm-12">
                                            <div class="mb-3">
                                                <label class="form-label" for="role_department">Department<span
                                                        class="text-danger">*</span></label>
                                                <select class="form-control @error('role_department') is-invalid @enderror" id="role_department" name="role_department"
                                                    required>
                                                    <option>Select Department</option>
                                                    <option value="APD">Application Processing Department - APD</option>
                                                    <option value="ID">Infrastructure Department - ID</option>
                                                    <option value="RND">Rules Notification Department - RND</option>
                                                    <option value="ED">Evaluation Department - ED</option>
                                                    <option value="QD">Confidential Department - QD</option>
                                                    <option value="VMD">Vehicles Machineries Department - VMD</option>
                                                    <option value="VSD">Verification Stationary Department - VSD</option>
                                                    <option value="MCD">Monitoring Coordination Department - MCD</option>
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
                                                <input type="text" class="form-control @error('role_name') is-invalid @enderror" id="role_name" name="role_name"
                                                    placeholder="Enter role" required>
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
                            <button type="submit" class="btn btn-primary">Create</button>

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
