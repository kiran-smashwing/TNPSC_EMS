@extends('layouts.app')

@section('title', 'ROUTE - Create')
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
                <form action="{{ route('exam-materials-route.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="tab-content">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>Route - <span class="text-primary">Add</span></h5>
                                    </div>
                                    <div class="card-body">

                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Route no <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="route_no"
                                                        name="route_no" placeholder="001" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Driver Name<span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="driver_name"
                                                        name="driver_name" placeholder="vijay" required>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Driver Licenese No<span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="driver_licence_no"
                                                        name="driver_licence_no" placeholder="DLR0101223" required>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="phone">Driver Phone<span
                                                            class="text-danger">*</span></label>
                                                    <input type="tel" class="form-control" id="phone" name="phone"
                                                        placeholder="9434***1212" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="vehicle_no">Vehicle No<span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="vehicle_no"
                                                        name="vehicle_no" placeholder="TN 01 2345" required>
                                                </div>
                                            </div>
                                            {{-- <div class="col-sm-6">
                                              <div class="mb-3">
                                                  <label class="form-label" for="status">Status</label>
                                                  <select class="form-control" id="status" name="status" required>
                                                    <option value="Active">Active</option>
                                                    <option value="Inactive">Inactive</option>
                                                  </select>
                                              </div>
                                          </div> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="mobile_staff">Mobile Staff<span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-control" name="mobile_staff" required data-trigger
                                                        id="choices-single-default">
                                                        <option value="1">John Doe</option>
                                                        <option value="2">Jane Smith</option>
                                                        <option value="3">Michael Johnson</option>
                                                        <option value="4">Emily Davis</option>
                                                    </select>

                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="center">Center <span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-control" id="center-select" name="center-select[]" multiple>
                                                        <option value="">Select Center</option>
                                                        @foreach ($centers as $center)
                                                            <option value="{{ $center->center_code }}"
                                                                {{ request('centerCode') == $center->center_code ? 'selected' : '' }}>
                                                                {{ $center->center_code }} - {{ $center->center_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="halls">Halls<span
                                                            class="text-danger">*</span></label>
                                                            <select class="form-control" id="halls-select" name="halls[]" multiple></select>

                                                    {{-- <select class="form-control" name="halls[]" id="halls-select" multiple>
                                                    <optgroup label="Chennai">
                                                        <option value="1" >Hall 1</option>
                                                        <option value="2">Hall 2</option>
                                                        <option value="3" >Hall 3</option>
                                                        <option value="4">Hall 4</option>
                                                    </optgroup>
                                                    <optgroup label="Arcot">
                                                        <option value="5">Hall 5</option>
                                                        <option value="6" >Hall 6</option>
                                                        <option value="7" >Hall 7</option>
                                                        <option value="8">Hall 8</option>
                                                    </optgroup>
                                                </select> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 text-end btn-page">
                                <a href="{{ route('exam-materials-route.index', $examId) }}"
                                    class="btn btn-outline-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Create</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- [ Main Content ] end -->
    </div>

    @include('partials.footer')

    <script src="{{ asset('storage/assets/js/plugins/choices.min.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Choices for the Center select
            var centerSelect = new Choices('#center-select', {
                removeItemButton: true,
                placeholderValue: 'Select Center',
                searchPlaceholderValue: 'Search Centers'
            });

            // // Initialize Choices for the Halls select
            // var hallsSelect = new Choices('#halls-select', {
            //     removeItemButton: true,
            //     placeholderValue: 'Select Halls',
            //     searchPlaceholderValue: 'Search Halls'
            // });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Store halls data grouped by center code
            const hallsByCenter = {};
            @foreach ($halls as $hall)
                if (!hallsByCenter['{{ $hall->center_code }}']) {
                    hallsByCenter['{{ $hall->center_code }}'] = [];
                }
                hallsByCenter['{{ $hall->center_code }}'].push({
                    code: '{{ $hall->hall_code }}',
                    centerName: '{{ $hall->center_name }}'
                });
            @endforeach

            const centerSelect = new Choices('#center-select', {
                removeItemButton: true,
                placeholderValue: 'Select Center'
            });

            const hallsSelect = new Choices('#halls-select', {
                removeItemButton: true,
                placeholderValue: 'Select Halls',
                multiple: true
            });

            document.getElementById('center-select').addEventListener('change', function(e) {
                const selectedCenter = e.target.value;
                const halls = hallsByCenter[selectedCenter] || [];

                const hallOptions = halls.map(hall => ({
                    value: hall.code,
                    label: `${hall.code}`,
                    selected: false
                }));

                hallsSelect.setChoices(hallOptions, 'value', 'label', true);
            });
        });
    </script>
    @include('partials.theme')

@endsection
