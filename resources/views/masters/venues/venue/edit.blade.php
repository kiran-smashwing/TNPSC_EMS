@extends('layouts.app')

@section('title', 'Venues')
@push('styles')
    <link rel="stylesheet" href="{{ asset('storage/assets/css/plugins/croppr.min.css')}}" />
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
                <div class="col-sm-12">
                    <!-- <div class="card">
                          <div class="card-body py-0">
                             Your content here
                          </div> -->
                </div>
                <div class="tab-content">
                    <div>
                        <form action="{{ route('venue.update', $venue->venue_id) }}" method="POST" enctype="multipart/form-data">
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
                                        <h5>Venue - <span class="text-primary">Edit</span></h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            
                                            <div class="col-sm-12 text-center mb-3">
                                                <div class="user-upload wid-75" data-pc-animate="just-me"
                                                    data-bs-toggle="modal" data-bs-target="#cropperModal">
                                                    <img src="{{ $venue->venue_image
                                                        ? asset('storage/' . $venue->venue_image)
                                                        : asset('storage/assets/images/user/collectorate.png') }}"
                                                        id="previewImage" alt="Cropped Preview"
                                                        style="max-width: 100%; height: auto; object-fit: cover;">
                                                    <input type="hidden" name="cropped_image" id="cropped_image">
                                                    <label for="imageUpload" class="img-avtar-upload"></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="district_id">District <span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-control" id="district_id" name="district_id" required>
                                                        <option>Select District</option>
                                                        @foreach ($districts as $district)
                                                            <option value="{{ $district->district_id }}"
                                                                {{ $district->district_id == $venue->venue_district_id ? 'selected' : '' }}>
                                                                {{ $district->district_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('district_id')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="center_id">Center <span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-control" id="center_id" name="center_id" required>
                                                        <option>Select Centers</option>
                                                        @foreach ($centers as $center)
                                                            <option value="{{ $center->center_id }}"
                                                                {{ $center->center_id == $venue->venue_center_id ? 'selected' : '' }}>
                                                                {{ $center->center_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('center_id')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="mb-3">
                                                    <label class="form-label" for="name">Venue Name<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="venue_name" name="venue_name" 
                                                           value="{{ old('name', $venue->venue_name) }}" 
                                                           placeholder="Gov Hr Sec School" required>
                                                </div>
                                            </div>
                                            
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label ">Venue Code<span
                                                            class="text-danger">*</span></label>
                                                    <input type="number" class="form-control no-arrows" id="venue_code"
                                                        name="venue_code" value="{{ old('name', $venue->venue_code) }}" placeholder="448966" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="venue_code_provider">Venue Code Provider<span class="text-danger">*</span></label>
                                                    <select class="form-control" id="venue_code_provider" name="venue_code_provider" required>
                                                        <option>Select Venue Code Provider</option>
                                                        <option value="UDISE" {{ old('venue_code_provider', $venue->venue_codeprovider) == 'UDISE' ? 'selected' : '' }}>UDISE</option>
                                                        <option value="1" {{ old('venue_code_provider', $venue->venue_codeprovider) == '1' ? 'selected' : '' }}>Anna University</option>
                                                        <option value="2" {{ old('venue_code_provider', $venue->venue_codeprovider) == '2' ? 'selected' : '' }}>Thriuvalluvar University</option>
                                                        <option value="3" {{ old('venue_code_provider', $venue->venue_codeprovider) == '3' ? 'selected' : '' }}>Madras University</option>
                                                        <option value="4" {{ old('venue_code_provider', $venue->venue_codeprovider) == '4' ? 'selected' : '' }}>Madurai Kamraj University</option>
                                                        <option value="5" {{ old('venue_code_provider', $venue->venue_codeprovider) == '5' ? 'selected' : '' }}>Manonmaniam Sundaranar University</option>
                                                        <option value="6" {{ old('venue_code_provider', $venue->venue_codeprovider) == '6' ? 'selected' : '' }}>Others</option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Email<span
                                                            class="text-danger">*</span></label>
                                                    <input type="email" class="form-control" id="venue_email" value="{{ old('name', $venue->venue_email) }}" name="venue_email"
                                                        placeholder="ceochn@***.in" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="phone">Phone<span
                                                            class="text-danger">*</span></label>
                                                    <input type="tel" class="form-control" id="venue_phone" value="{{ old('name', $venue->venue_phone) }}" name="venue_phone"
                                                        placeholder="9434***1212" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="alternate_phone">Alternate Phone</label>
                                                    <input type="tel" class="form-control" value="{{ old('name', $venue->venue_alternative_phone) }}" id="venue_alternative_phone"
                                                        name="venue_alternative_phone" placeholder="O4448***762/9434***1212">
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="venue_type">Type<span class="text-danger">*</span></label>
                                                    <select class="form-control" id="venue_type" name="venue_type" required>
                                                        <option value="School" {{ old('venue_type', $venue->venue_type) == 'School' ? 'selected' : '' }}>School</option>
                                                        <option value="College" {{ old('venue_type', $venue->venue_type) == 'College' ? 'selected' : '' }}>College</option>
                                                        <option value="Other" {{ old('venue_type', $venue->venue_type) == 'Other' ? 'selected' : '' }}>Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="venue_category">Category<span class="text-danger">*</span></label>
                                                    <select class="form-control" id="venue_category" name="venue_category" required>
                                                        <option value="Government" {{ old('venue_category', $venue->venue_category) == 'Government' ? 'selected' : '' }}>Government</option>
                                                        <option value="Private" {{ old('venue_category', $venue->venue_category) == 'Private' ? 'selected' : '' }}>Private</option>
                                                        <option value="Aided" {{ old('venue_category', $venue->venue_category) == 'Aided' ? 'selected' : '' }}>Aided</option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Website</label>
                                                    <input type="url" class="form-control" id="venue_website"
                                                        name="venue_website" value="{{ old('name', $venue->venue_website) }}" placeholder="https://chennai.nic.in/">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Password <small>(leave blank to keep
                                                            current)</small></label>
                                                    <input type="password"
                                                        class="form-control @error('venue_password') is-invalid @enderror"
                                                        id="venue_password" name="venue_password">
                                                    @error('password')
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

                                            <div class="col-sm-12">
                                                <div class="mb-3">
                                                    <label class="form-label" for="venue_address">Address<span class="text-danger">*</span></label>
                                                    <textarea class="form-control" id="venue_address" name="venue_address" required
                                                        placeholder="Tamil Nadu Public Service Commission, TNPSC Road, Broadway, Chennai-600003.">{{ old('venue_address', $venue->venue_address) }}</textarea>
                                                </div>
                                            </div>
                                            
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="dt_railway">Distance from Railway<span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" step="any" class="form-control"
                                                        id="venue_distance_railway" value="{{ old('venue_distance_railway', $venue->venue_distance_railway) }}" name="venue_distance_railway" placeholder="8.2km">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="venue_treasury_office">Distance from
                                                        Treasury<span class="text-danger">*</span></label>
                                                    <input type="text" step="any" class="form-control" value="{{ old('venue_treasury_office', $venue->venue_treasury_office) }}" 
                                                        id="venue_treasury_office" name="venue_treasury_office" placeholder="1.2km">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="longitude">longitude<span
                                                            class="text-danger">*</span></label>
                                                    <input type="number" step="any" class="form-control"
                                                        id="longitude" value="{{ old('venue_longitude', $venue->venue_longitude) }}" name="venue_longitude" placeholder="11.2312312312312">
                                                </div>
                                            </div>
                                            <div class="col-sm-6 ">
                                                <div class="mb-3">
                                                    <label class="form-label" for="latitude">latitude<span
                                                            class="text-danger">*</span></label>
                                                    <input type="number" step="any" class="form-control"
                                                        id="latitude" name="venue_latitude" value="{{ old('venue_latitude', $venue->venue_latitude) }}"  placeholder="11.2312312312312">
                                                </div>
                                            </div>
                                            <div class="col-sm-6  d-inline-flex justify-content-center mb-3">
                                                <a href="#"
                                                    class="btn btn-success d-inline-flex  justify-content-center"><i
                                                        class="ti ti-current-location me-1"></i>Get Location
                                                    Coordinates</a>
                                            </div>
                                            <div class="col-sm-6 d-inline-flex justify-content-center mb-3">
                                                <a href="https://www.google.com/maps" target="_blank"
                                                    class="btn btn-info d-inline-flex  justify-content-center"><i
                                                        class="ti ti-map-2 me-1"></i>Get Coordinates on G-Map</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <h5>Bank Account Details</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-6 ">
                                                <div class="mb-3">
                                                    <label class="form-label" for="venue_bank_name">Bank Name</label>
                                                    <input type="text" step="any" class="form-control"
                                                        id="venue_bank_name" value="{{ old('venue_bank_name', $venue->venue_bank_name) }}"  name="venue_bank_name"
                                                        placeholder="State Bank Of India">
                                                </div>
                                            </div>
                                            <div class="col-sm-6 ">
                                                <div class="mb-3">
                                                    <label class="form-label" for="venue_account_name">Account Name</label>
                                                    <input type="text" step="any" class="form-control"
                                                        id="venue_account_name" value="{{ old('venue_account_name', $venue->venue_account_name) }}"  name="venue_account_name"
                                                        placeholder="Gov Hr Sec School">
                                                </div>
                                            </div>
                                            <div class="col-sm-6 ">
                                                <div class="mb-3">
                                                    <label class="form-label" for="bank_ac_number">
                                                        Number</label>
                                                    <input type="text" step="any" class="form-control"
                                                        id="venue_account_number" value="{{ old('venue_account_number', $venue->venue_account_number) }}"  name="venue_account_number"
                                                        placeholder="2312312312312">
                                                </div>
                                            </div>
                                            <div class="col-sm-6 ">
                                                <div class="mb-3">
                                                    <label class="form-label" for="venue_branch_name"> Branch
                                                        Name</label>
                                                    <input type="text" step="any" class="form-control"
                                                        id="venue_branch_name" name="venue_branch_name" value="{{ old('venue_branch_name', $venue->venue_branch_name) }}"  placeholder="chennai">
                                                </div>
                                            </div>
                                            <div class="col-sm-6 ">
                                                <div class="mb-3">
                                                    <label class="form-label" for="venue_account_type"> Type</label>
                                                    <input type="text" step="any" class="form-control"
                                                        id="venue_account_type" name="venue_account_type" value="{{ old('venue_account_type', $venue->venue_account_type) }}"  placeholder="current">
                                                </div>
                                            </div>
                                            <div class="col-sm-6 ">
                                                <div class="mb-3">
                                                    <label class="form-label" for="venue_ifsc"> IFSC
                                                        Code</label>
                                                    <input type="text" step="any" class="form-control"
                                                        id="venue_ifsc" value="{{ old('venue_ifsc', $venue->venue_ifsc) }}"  name="venue_ifsc" placeholder="SBI000123">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 text-end btn-page">
                                <a href="{{ route('scribe') }}" class="btn btn-outline-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                         </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ Main Content ] end -->
    </div>
    </div>

    @include('partials.footer')

    <script src="{{ asset('storage/assets/js/plugins/croppr.min.js')}}"></script>
    <script src="{{ asset('storage/assets/js/pages/page-croper.js')}}"></script>
    <script>
        document.getElementById('triggerModal').addEventListener('click', function() {
            var modal = new bootstrap.Modal(document.getElementById('cropperModal'));
            modal.show();
        });
    </script>
     <script>
        document.querySelector('.btn-success').addEventListener('click', function(e) {
            e.preventDefault();
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(position => {
                    document.getElementById('latitude').value = position.coords.latitude;
                    document.getElementById('longitude').value = position.coords.longitude;
                });
            }
        });
    </script>

    @include('partials.theme')

@endsection
