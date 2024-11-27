@extends('layouts.app')

@section('title', 'Venues')
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
                <div class="col-sm-12">
                    <!-- <div class="card">
                                          <div class="card-body py-0">
                                             Your content here
                                          </div> -->
                </div>
                <div class="tab-content">
                    <div>
                        <form action="{{ route('venues.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
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
                                            <h5>Venue - <span class="text-primary">Add</span></h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-12 text-center mb-3">
                                                    <div class="user-upload wid-75" data-pc-animate="just-me"
                                                        data-bs-toggle="modal" data-bs-target="#cropperModal">
                                                        <img src="{{ asset('storage/assets/images/user/venue.png') }}"
                                                            id="previewImage" alt="Cropped Preview"
                                                            style="max-width: 100%; height: auto; object-fit: cover;">
                                                        <input type="hidden" name="cropped_image" id="cropped_image">
                                                        <label for="imageUpload" class="img-avtar-upload"></label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="district">District<span class="text-danger">*</span></label>
                                                        <select class="form-control @error('district') is-invalid @enderror" id="district" name="district" required>
                                                            <option value="">Select District</option>
                                                            @foreach ($districts as $district)
                                                                <option value="{{ $district->district_code }}">{{ $district->district_name }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('district')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="center">Center<span
                                                                class="text-danger">*</span></label>
                                                        <select class="form-control @error('center') is-invalid @enderror"
                                                            id="center" name="center" required>
                                                            <option value="">Select Center</option>
                                                            @foreach ($centers as $center)
                                                                <option value="{{ $center->center_code }}">
                                                                    {{ $center->center_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('center')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>


                                                <div class="col-sm-12">
                                                    <div class="mb-3">
                                                        <label class="form-label " for="name">Venue Name<span
                                                                class="text-danger">*</span></label>
                                                        <input type="text"
                                                            class="form-control @error('venue_name') is-invalid @enderror"
                                                            id="venue_name" name="venue_name"
                                                            placeholder="Gov Hr Sec School" required>
                                                        @error('venue_name')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label ">Venue Code<span
                                                                class="text-danger">*</span></label>
                                                        <input type="number"
                                                            class="form-control no-arrows @error('venue_code') is-invalid @enderror"
                                                            id="venue_code" name="venue_code" placeholder="448966" required>
                                                        @error('venue_code')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="venue_code_provider">Venue Code
                                                            Provider<span class="text-danger">*</span></label>
                                                        <select
                                                            class="form-control @error('venue_code_provider') is-invalid @enderror"
                                                            id="venue_code_provider" name="venue_code_provider" required>
                                                            <option>Select Venue Code Provider</option>
                                                            <option value="UDISE">UDISE</option>
                                                            <option value="Anna University">Anna University</option>
                                                            <option value="Thriuvalluvar University">Thriuvalluvar
                                                                University
                                                            </option>
                                                            <option value="Madras University">Madras University</option>
                                                            <option value="Madurai Kamraj University">Madurai Kamraj
                                                                University
                                                            </option>
                                                            <option value="Manonmaniam Sundaranar University">Manonmaniam
                                                                Sundaranar University</option>
                                                            <option value="Others">Others</option>
                                                        </select>
                                                        @error('venue_code_provider')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Email<span
                                                                class="text-danger">*</span></label>
                                                        <input type="email"
                                                            class="form-control @error('email') is-invalid @enderror"
                                                            id="email" name="email" placeholder="ceochn@***.in"
                                                            required>
                                                        @error('email')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="phone">Phone<span
                                                                class="text-danger">*</span></label>
                                                        <input type="tel"
                                                            class="form-control @error('phone') is-invalid @enderror"
                                                            id="phone" name="phone" placeholder="9434***1212"
                                                            required>
                                                        @error('phone')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="alternate_phone">Alternate
                                                            Phone</label>
                                                        <input type="tel"
                                                            class="form-control @error('alternative_phone') is-invalid @enderror"
                                                            id="alternative_phone" name="alternative_phone"
                                                            placeholder="O4448***762/9434***1212">
                                                        @error('alternative_phone')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="type">Type<span
                                                                class="text-danger">*</span></label>
                                                        <select class="form-control @error('type') is-invalid @enderror"
                                                            id="type" name="type" required>
                                                            <option value="">Select Type</option>
                                                            <option value="School">School</option>
                                                            <option value="College">College</option>
                                                            <option value="Other">Other</option>
                                                        </select>
                                                        @error('type')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="category">Category<span
                                                                class="text-danger">*</span></label>
                                                        <select
                                                            class="form-control @error('category') is-invalid @enderror"
                                                            id="category" name="category" required>
                                                            <option value="">Select Category</option>
                                                            <option value="Government">Government</option>
                                                            <option value="Private">Private</option>
                                                            <option value="Aided">Aided</option>
                                                        </select>
                                                        @error('category')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Website</label>
                                                        <input type="url"
                                                            class="form-control @error('website') is-invalid @enderror"
                                                            id="website" name="website"
                                                            placeholder="https://chennai.nic.in/">
                                                        @error('website')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label"for="password">Password<span
                                                                class="text-danger">*</span></label>
                                                        <input type="password"
                                                            class="form-control @error('password') is-invalid @enderror"
                                                            id="password" name="password" required placeholder="******">
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
                                                        <label class="form-label">Address<span
                                                                class="text-danger">*</span></label>
                                                        <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" required
                                                            placeholder="Tamil Nadu Public Service Commission, TNPSC Road, Broadway, Chennai-600003."></textarea>
                                                        @error('address')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="distance_from_railway">Distance
                                                            from Railway<span class="text-danger">*</span></label>
                                                        <input type="text" step="any"
                                                            class="form-control @error('distance_from_railway') is-invalid @enderror"
                                                            id="distance_from_railway" name="distance_from_railway"
                                                            placeholder="8.2km">
                                                        @error('distance_from_railway')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="distance_from_treasury">Distance
                                                            from
                                                            Treasury<span class="text-danger">*</span></label>
                                                        <input type="text" step="any"
                                                            class="form-control @error('distance_from_treasury') is-invalid @enderror"
                                                            id="distance_from_treasury" name="distance_from_treasury"
                                                            placeholder="1.2km">
                                                        @error('distance_from_treasury')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="longitude">longitude<span
                                                                class="text-danger">*</span></label>
                                                        <input type="number" step="any"
                                                            class="form-control @error('longitude') is-invalid @enderror"
                                                            id="longitude" name="longitude"
                                                            placeholder="11.2312312312312">
                                                        @error('longitude')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 ">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="latitude">latitude<span
                                                                class="text-danger">*</span></label>
                                                        <input type="number" step="any"
                                                            class="form-control @error('latitude') is-invalid @enderror"
                                                            id="latitude" name="latitude"
                                                            placeholder="11.2312312312312">
                                                        @error('latitude')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
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
                                                        <label class="form-label" for="bank_ac_name">Bank Name</label>
                                                        <input type="text" step="any"
                                                            class="form-control @error('bank_name') is-invalid @enderror"
                                                            id="bank_name" name="bank_name"
                                                            placeholder="State Bank Of India">
                                                        @error('bank_name')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 ">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="bank_ac_name">Account Name</label>
                                                        <input type="text" step="any"
                                                            class="form-control @error('account_name') is-invalid @enderror"
                                                            id="account_name" name="account_name"
                                                            placeholder="Gov Hr Sec School">
                                                        @error('account_name')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 ">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="bank_ac_number">
                                                            Number</label>
                                                        <input type="text" step="any"
                                                            class="form-control @error('account_number') is-invalid @enderror"
                                                            id="account_number" name="account_number"
                                                            placeholder="2312312312312">
                                                        @error('account_number')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 ">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="bank_ac_branch"> Branch
                                                            Name</label>
                                                        <input type="text" step="any"
                                                            class="form-control @error('branch_name') is-invalid @enderror"
                                                            id="branch_name" name="branch_name" placeholder="chennai">
                                                        @error('branch_name')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 ">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="bank_ac_type"> Type</label>
                                                        <input type="text" step="any"
                                                            class="form-control @error('account_type') is-invalid @enderror"
                                                            id="account_type" name="account_type" placeholder="current">
                                                        @error('account_type')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 ">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="bank_ac_ifsc"> IFSC
                                                            Code</label>
                                                        <input type="text" step="any"
                                                            class="form-control @error('ifsc') is-invalid @enderror"
                                                            id="ifsc" name="ifsc" placeholder="SBI000123">
                                                        @error('ifsc')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 text-end btn-page">
                                    <a href="{{ route('venues.index') }}" class="btn btn-outline-secondary">Cancel</a>
                                    <button type="submit" class="btn btn-primary">Create</button>
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

    <script src="{{ asset('storage/assets/js/plugins/croppr.min.js') }}"></script>
    <script src="{{ asset('storage/assets/js/pages/page-croper.js') }}"></script>
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
