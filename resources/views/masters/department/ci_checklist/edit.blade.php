@extends('layouts.app')
@section('title', ' Edit CI Checklist')

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
                                    <h5>CI Checklist - <span class="text-primary">Edit</span></h5>
                                </div>
                                <div class="card-body">
                                    {{-- <form action="{{ route('collectorates.store') }}" method="POST" enctype="multipart/form-data"> --}}
                                    @csrf
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="type">Type<span class="text-danger">*</span></label>
                                                <select class="form-control" id="type" name="type" required>
                                                    <option>Select Type</option>
                                                    <option value="1">Preliminary</option>
                                                    <option value="1">Session</option>
                                                    <option value="1">Self Declaration</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="mb-3">
                                                <label class="form-label" for="description">Description<span
                                                        class="text-danger">*</span></label>
                                                <textarea class="form-control" id="description" name="description" required
                                                    placeholder="Whether Registartion Number are alloted in W shape as per the room sketch"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                   
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

    @include('partials.theme')

@endsection
