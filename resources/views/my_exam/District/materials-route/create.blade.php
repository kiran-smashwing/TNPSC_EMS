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
                    <input type="hidden" name="exam_id" value="{{ $examId }}">
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
                                                    <label class="form-label" for="exam-date">Exam Date<span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-control" name="exam-date" required data-trigger
                                                        id="choices-single-default">
                                                        <option value="" selected disabled>Select Exam Date
                                                        </option>
                                                        @foreach ($examDates as $examDate)
                                                            <option value="{{ $examDate }}">
                                                                {{ $examDate }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="mobile_staff">Mobile Staff<span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-control" name="mobile_staff" required data-trigger
                                                        id="choices-single-default">
                                                        <option value="" selected disabled>Select Mobile Team Staff
                                                        </option>
                                                        @foreach ($mobileTeam as $mobile_staff)
                                                            <option value="{{ $mobile_staff->mobile_id }}">
                                                                {{ $mobile_staff->mobile_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="center">Center <span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-control" id="center-select" name="center-select[]"
                                                        multiple>
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
                                                    <select class="form-control" id="halls-select" name="halls[]"
                                                        multiple></select>
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
            // Store halls data grouped by center code
            const hallsByCenter = {};
            @foreach ($halls as $hall)
                if (!hallsByCenter['{{ $hall->center_code }}']) {
                    hallsByCenter['{{ $hall->center_code }}'] = {
                        centerName: '{{ $hall->center_name }}',
                        halls: []
                    };
                }
                hallsByCenter['{{ $hall->center_code }}'].halls.push({
                    code: '{{ $hall->hall_code }}',
                    centerName: '{{ $hall->center_name }}'
                });
            @endforeach

            const centerSelect = new Choices('#center-select', {
                removeItemButton: true,
                placeholderValue: 'Select Center',
                multiple: true
            });

            const hallsSelect = new Choices('#halls-select', {
                removeItemButton: true,
                placeholderValue: 'Select Halls',
                multiple: true,
                shouldSort: false
            });

            document.getElementById('center-select').addEventListener('change', function(e) {
                // Get all selected centers
                const selectedCenters = centerSelect.getValue().map(choice => choice.value);

                // Create grouped choices structure
                let groupedChoices = [];
                selectedCenters.forEach(centerCode => {
                    const centerData = hallsByCenter[centerCode];
                    if (centerData) {
                        groupedChoices.push({
                            label: `${centerCode} - ${centerData.centerName}`,
                            id: centerCode,
                            choices: centerData.halls.map(hall => ({
                                value: `${centerCode}:${hall.code}`, // Combine center code and hall code
                                label: centerCode + ' - ' + hall.code,
                                selected: false
                            }))
                        });
                    }
                });

                // Update halls dropdown with grouped choices
                hallsSelect.clearStore();
                hallsSelect.setChoices(groupedChoices, 'value', 'label', true);
            });
        });
    </script>
    @include('partials.theme')

@endsection
