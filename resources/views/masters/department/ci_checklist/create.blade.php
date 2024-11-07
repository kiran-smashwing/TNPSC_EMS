@extends('layouts.app')
@section('title', ' Add CI Checklist')

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
                        @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
    
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
    
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>CI Checklist - <span class="text-primary">Add</span></h5>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('ci-checklist.store') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="type">Type<span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-control" id="ci_checklist_type"
                                                        name="ci_checklist_type" required>
                                                        <option>Select Type</option>
                                                        <option value="Preliminary">Preliminary</option>
                                                        <option value="Session">Session</option>
                                                        <option value="Self Declaration">Self Declaration</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="mb-3">
                                                    <label class="form-label"
                                                        for="ci_checklist_description">Description<span
                                                            class="text-danger">*</span></label>
                                                    <textarea class="form-control" id="ci_checklist_description" name="ci_checklist_description" required
                                                        placeholder="Whether Registartion Number are alloted in W shape as per the room sketch"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 text-end btn-page">
                            <a href="{{ route('ci-checklist') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                        </form>
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
