@extends('layouts.app')

@section('title', 'Exam Service')

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
            <div class="row">
                <div class="col-sm-12">
                    <!-- <div class="card">
              <div class="card-body py-0">
                 Your content here
              </div> -->
                </div>
                <div class="tab-content">
                    <div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>Exam Service - <span class="text-primary">Edit</span></h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="{{ route('examservice.update', $examService->examservice_id) }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="row">


                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="name">Name <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="name"
                                                            name="examservice_name"
                                                            placeholder="GROUP I SERVICES EXAMINATION"
                                                            value="{{ old('examservice_name', $examService->examservice_name) }}"
                                                            required>
                                                    </div>
                                                </div>


                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="code">Code <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="code"
                                                            name="examservice_code" placeholder="001"
                                                            value="{{ old('examservice_code', $examService->examservice_code) }}"
                                                            required>
                                                    </div>
                                                </div>


                                            </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 text-end btn-page">
                                <a href="{{ route('exam-service') }}" class="btn btn-outline-secondary">Cancel</a>
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
    </div>

    @include('partials.footer')

    @push('scripts')
        @include('partials.datatable-export-js')
    @endpush

    @include('partials.theme')

@endsection
