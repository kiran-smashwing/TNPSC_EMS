@extends('layouts.app')

@section('title', 'Current Exam')
@push('styles')
    <link rel="stylesheet" href="{{ asset('storage/assets/css/plugins/datepicker-bs5.min.css') }}" />
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
                                    <h5>Current Exam - <span class="text-primary">Edit</span></h5>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('current-exam.update', $exam->exam_main_id) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        @csrf
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="exam_main_no">Exam ID <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="exam_main_no" name="exam_main_no"
                                                        readonly required value="{{ $exam->exam_main_no }}">
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="exam_main_type">Type of Exam <span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-control @error('exam_main_type') is-invalid @enderror"
                                                        id="exam_main_type" name="exam_main_type" required>
                                                        <option disabled>Select Exam Type</option>
                                                        <option value="Objective"
                                                            {{ old('exam_main_type', $exam->exam_main_type) == 'Objective' ? 'selected' : '' }}>
                                                            Objective</option>
                                                        <option value="Descriptive"
                                                            {{ old('exam_main_type', $exam->exam_main_type) == 'Descriptive' ? 'selected' : '' }}>
                                                            Descriptive</option>
                                                        <option value="CBT"
                                                            {{ old('exam_main_type', $exam->exam_main_type) == 'CBT' ? 'selected' : '' }}>
                                                            CBT</option>
                                                        <option value="Objective+Descriptive"
                                                            {{ old('exam_main_type', $exam->exam_main_type) == 'Objective+Descriptive' ? 'selected' : '' }}>
                                                            Objective + Descriptive</option>
                                                    </select>
                                                    @error('exam_main_type')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="exam_main_model">Exam Model <span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-control @error('exam_main_model') is-invalid @enderror"
                                                        id="exam_main_model" name="exam_main_model" required>
                                                        <option disabled>Select Exam Model</option>
                                                        <option value="Major"
                                                            {{ old('exam_main_model', $exam->exam_main_model) == 'Major' ? 'selected' : '' }}>
                                                            Major</option>
                                                        <option value="Minor"
                                                            {{ old('exam_main_model', $exam->exam_main_model) == 'Minor' ? 'selected' : '' }}>
                                                            Minor</option>
                                                    </select>
                                                    @error('exam_main_model')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="exam_main_tiers">Exam Tiers <span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-control @error('exam_main_tiers') is-invalid @enderror"
                                                        id="exam_main_tiers" name="exam_main_tiers" required>
                                                        <option disabled>Select Exam Tiers</option>
                                                        <option value="1"
                                                            {{ old('exam_main_tiers', $exam->exam_main_tiers) == '1' ? 'selected' : '' }}>
                                                            1 - (Single Tier)</option>
                                                        <option value="2"
                                                            {{ old('exam_main_tiers', $exam->exam_main_tiers) == '2' ? 'selected' : '' }}>
                                                            2 - (Multi Tiers)</option>
                                                    </select>
                                                    @error('exam_tiers')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="exam_main_service">Exam Service <span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-control @error('exam_main_service') is-invalid @enderror"
                                                        id="exam_main_service" name="exam_main_service" required>
                                                        <option disabled>Select Exam Service</option>
                                                        <option value="001"
                                                            {{ old('exam_main_service', $exam->exam_main_service) == '001' ? 'selected' : '' }}>
                                                            GROUP I SERVICES EXAMINATION</option>
                                                        <option value="002"
                                                            {{ old('exam_main_service', $exam->exam_main_service) == '002' ? 'selected' : '' }}>
                                                            GROUP I-A SERVICES EXAMINATION</option>
                                                    </select>
                                                    @error('exam_main_service')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="exam_main_notification">Notification no
                                                        <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $exam->exam_main_notification }}"
                                                        id="exam_main_notification" name="exam_main_notification" required
                                                        placeholder="08/2024">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3 ">
                                                    <label class="form-label" for="exam_main_notifdate">Notification Date
                                                        <span class="text-danger">*</span></label>
                                                    <div class="input-group date">
                                                        <input type="text" name="exam_main_notifdate"
                                                            class="form-control" value="{{ $exam->exam_main_notifdate }}"
                                                            id="exam_main_notifdate" />
                                                        <span class="input-group-text">
                                                            <i class="feather icon-calendar"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="exam_name">Exam Name <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text"
                                                        class="form-control @error('exam_name') is-invalid @enderror"
                                                        id="exam_name" name="exam_main_name" required
                                                        value="{{ old('exam_main_name', $exam->exam_main_name) }}"
                                                        placeholder="Combined Civil Services Examination - II">
                                                    @error('exam_main_name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="exam_main_nametamil">Exam Name in Tamil
                                                        <span class="text-danger">*</span></label>
                                                    <input type="text"
                                                        class="form-control @error('exam_main_nametamil') is-invalid @enderror"
                                                        id="exam_main_nametamil" name="exam_main_nametamil" required
                                                        value="{{ old('exam_main_nametamil', $exam->exam_main_nametamil) }}"
                                                        placeholder="ஒருங்கிணைந்த சிவில் சர்வீசஸ் தேர்வு - II (குரூப் II மற்றும் IIA சேவைகள்)">
                                                    @error('exam_main_nametamil')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="exam_main_postname">Post Name <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text"
                                                        class="form-control @error('exam_main_postname') is-invalid @enderror"
                                                        id="exam_main_postname" name="exam_main_postname" required
                                                        value="{{ old('exam_main_postname', $exam->exam_main_postname) }}"
                                                        placeholder="Group II and IIA Services">
                                                    @error('exam_main_postname')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="exam_main_lastdate">Last Date For Apply
                                                        <span class="text-danger">*</span></label>
                                                    <div class="input-group date">
                                                        <input type="text" name="exam_main_lastdate"
                                                            class="form-control @error('exam_main_lastdate') is-invalid @enderror"
                                                            value="{{ old('exam_main_lastdate', \Carbon\Carbon::parse($exam->exam_main_lastdate)->format('m/d/Y')) }}"
                                                            id="exam_main_lastdate" required />
                                                        <span class="input-group-text">
                                                            <i class="feather icon-calendar"></i>
                                                        </span>
                                                    </div>
                                                    @error('exam_main_lastdate')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="exam_main_startdate">Exam Start Date <span
                                                            class="text-danger">*</span></label>
                                                    <div class="input-group date">
                                                        <input type="text" name="exam_main_startdate"
                                                            class="form-control @error('exam_main_startdate') is-invalid @enderror"
                                                            value="{{ old('exam_main_startdate', \Carbon\Carbon::parse($exam->exam_main_startdate)->format('m/d/Y')) }}"
                                                            id="exam_main_startdate" required />
                                                        <span class="input-group-text">
                                                            <i class="feather icon-calendar"></i>
                                                        </span>
                                                    </div>
                                                    @error('exam_main_startdate')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="exam_main_notifdate">Date of Exam <span
                                                            class="text-danger">*</span></label>
                                                    <div class="input-group date">
                                                        <input type="text" name="exam_main_notifdate"
                                                            class="form-control @error('exam_main_notifdate') is-invalid @enderror"
                                                            value="{{ old('exam_main_notifdate', \Carbon\Carbon::parse($exam->exam_main_notifdate)->format('m/d/Y')) }}"
                                                            id="exam_main_notifdate" required />
                                                        <span class="input-group-text">
                                                            <i class="feather icon-calendar"></i>
                                                        </span>
                                                    </div>
                                                    @error('exam_main_notifdate')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                        </div>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="col-lg-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="mb-3">
                                                <label class="form-label">Website<span
                                                        class="text-danger">*</span></label>
                                                <input type="url" class="form-control" id="website" name="website"
                                                    placeholder="https://chennai.nic.in/">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="mb-3">
                                                <label class="form-label">Address<span
                                                        class="text-danger">*</span></label>
                                                <textarea class="form-control" id="address" name="address" required
                                                    placeholder="Tamil Nadu Public Service Commission, TNPSC Road, Broadway, Chennai-600003."></textarea>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="longitude">longitude<span
                                                        class="text-danger">*</span></label>
                                                <input type="number" step="any" class="form-control" id="longitude"
                                                    name="longitude" placeholder="11.2312312312312">
                                            </div>
                                        </div>
                                        <div class="col-sm-6 ">
                                            <div class="mb-3">
                                                <label class="form-label" for="latitude">latitude<span
                                                        class="text-danger">*</span></label>
                                                <input type="number" step="any" class="form-control" id="latitude"
                                                    name="latitude" placeholder="11.2312312312312">
                                            </div>
                                        </div>
                                        <div class="col-sm-6  d-inline-flex justify-content-center mb-3">
                                            <a href="#"
                                                class="btn btn-success d-inline-flex  justify-content-center"><i
                                                    class="ti ti-current-location me-1"></i>Get Location Coordinates</a>
                                        </div>
                                        <div class="col-sm-6 d-inline-flex justify-content-center mb-3">
                                            <a href="https://www.google.com/maps" target="_blank"
                                                class="btn btn-info d-inline-flex  justify-content-center"><i
                                                    class="ti ti-map-2 me-1"></i>Get Coordinates on G-Map</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                        <div class="col-12 text-end btn-page">
                            <a href="{{ route('current-exam') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ Main Content ] end -->
    </div>

    @include('partials.footer')

    @push('scripts')
        <script src="{{ asset('storage/assets/js/plugins/datepicker-full.min.js') }}"></script>
        <script>
            (function() {
                const d_week = new Datepicker(document.querySelector('#notif_date'), {
                    buttonClass: 'btn',
                    todayBtn: true,
                    clearBtn: true,
                    format: 'dd-mm-yyyy'
                });
                const d_week1 = new Datepicker(document.querySelector('#last_date_apply'), {
                    buttonClass: 'btn',
                    todayBtn: true,
                    clearBtn: true,
                    format: 'dd-mm-yyyy'
                });
                const d_week2 = new Datepicker(document.querySelector('#exam_start_date'), {
                    buttonClass: 'btn',
                    todayBtn: true,
                    clearBtn: true,
                    format: 'dd-mm-yyyy'
                });
                const d_week3 = new Datepicker(document.querySelector('#date_of_exam'), {
                    buttonClass: 'btn',
                    todayBtn: true,
                    clearBtn: true,
                    format: 'dd-mm-yyyy'
                });
            })();
        </script>
    @endpush
    @include('partials.theme')

@endsection
