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
                                    <form action="{{ route('ci-checklist.update', $ciChecklist->ci_checklist_id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT') <!-- This ensures the form sends a PUT request -->
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="type">Type <span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-control" id="ci_checklist_type" name="ci_checklist_type" required>
                                                        <option value="">Select Type</option>
                                                        <option value="Preliminary"
                                                            {{ old('ci_checklist_type', $ciChecklist->ci_checklist_type) == 'Preliminary' ? 'selected' : '' }}>
                                                            Preliminary</option>
                                                        <option value="Session"
                                                            {{ old('ci_checklist_type', $ciChecklist->ci_checklist_type) == 'Session' ? 'selected' : '' }}>
                                                            Session</option>
                                                        <option value="Self Declaration"
                                                            {{ old('ci_checklist_type', $ciChecklist->ci_checklist_type) == 'Self Declaration' ? 'selected' : '' }}>
                                                            Self Declaration</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-12">
                                                <div class="mb-3">
                                                    <label class="form-label" for="description">Description<span
                                                            class="text-danger">*</span></label>
                                                    <textarea class="form-control" id="ci_checklist_description" name="ci_checklist_description" required
                                                        placeholder="Whether Registartion Number are alloted in W shape as per the room sketch">{{ old('ci_checklist_description', $ciChecklist->ci_checklist_description) }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 text-end btn-page">
                            <a href="{{ route('ci-checklist') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update</button>
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
