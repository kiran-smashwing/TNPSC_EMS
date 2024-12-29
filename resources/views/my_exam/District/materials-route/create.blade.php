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
                                        <h5>Route - <span class="text-primary">Add</span></h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Route no <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text"
                                                        class="form-control  @error('route_no') is-invalid @enderror"
                                                        id="route_no" value="{{ old('route_no') }}" name="route_no"
                                                        placeholder="001" required>
                                                    @error('route_no')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Driver Name<span
                                                            class="text-danger">*</span></label>
                                                    <input type="text"
                                                        class="form-control @error('driver_name') is-invalid @enderror"
                                                        id="driver_name" name="driver_name" placeholder="vijay"
                                                        value="{{ old('driver_name') }}" required>
                                                    @error('driver_name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Driver Licenese No<span
                                                            class="text-danger">*</span></label>
                                                    <input type="text"
                                                        class="form-control  @error('driver_licence_no') is-invalid @enderror"
                                                        id="driver_licence_no" name="driver_licence_no"
                                                        placeholder="DLR0101223" value="{{ old('driver_licence_no') }}"
                                                        required>
                                                    @error('driver_licence_no')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="phone">Driver Phone<span
                                                            class="text-danger">*</span></label>
                                                    <input type="tel"
                                                        class="form-control @error('phone') is-invalid @enderror "
                                                        id="phone" name="phone" value="{{ old('phone') }}"
                                                        placeholder="9434***1212" required>
                                                    @error('phone')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="vehicle_no">Vehicle No<span
                                                            class="text-danger">*</span></label>
                                                    <input type="text"
                                                        class="form-control @error('vehicle_no') is-invalid @enderror"
                                                        id="vehicle_no" name="vehicle_no" placeholder="TN 01 2345"
                                                        value="{{ old('vehicle_no') }}" required>
                                                    @error('vehicle_no')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
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
                                                    <label class="form-label" for="exam_date">Exam Date<span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-control @error('exam_date') is-invalid @enderror"
                                                        name="exam_date" required data-trigger
                                                        id="choices-single-default">
                                                        <option value="" selected disabled>Select Exam Date
                                                        </option>
                                                        @foreach ($examDates as $examDate)
                                                            <option value="{{ $examDate }}"
                                                                @if (old('exam_date') == $examDate) selected @endif>
                                                                {{ $examDate }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('exam_date')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="mobile_staff">Mobile Staff<span
                                                            class="text-danger">*</span></label>
                                                    <select
                                                        class="form-control @error('mobile_staff') is-invalid @enderror"
                                                        name="mobile_staff" required data-trigger
                                                        id="choices-single-default">
                                                        <option value="" selected disabled>Select Mobile Team Staff
                                                        </option>
                                                        @foreach ($mobileTeam as $mobile_staff)
                                                            <option value="{{ $mobile_staff->mobile_id }}"
                                                                @if (old('mobile_staff') == $mobile_staff->mobile_id) selected @endif>
                                                                {{ $mobile_staff->mobile_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('mobile_staff')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="centers">Center <span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-control @error('centers') is-invalid @enderror" id="centers"
                                                        name="centers[]" multiple required>
                                                        <option value="">Select Center</option>
                                                        @foreach ($centers as $center)
                                                            <option value="{{ $center->center_code }}"
                                                                @if (old('centers') == $center->center_code) selected @endif>
                                                                {{ $center->center_code }} - {{ $center->center_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('centers')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="halls">Halls<span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-control @error('halls') is-invalid @enderror" id="halls-select" name="halls[]"
                                                        multiple required></select>
                                                    @error('halls')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
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

            const centerSelect = new Choices('#centers', {
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

            document.getElementById('centers').addEventListener('change', function(e) {
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
