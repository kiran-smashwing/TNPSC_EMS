@extends('layouts.app')

@section('title', 'Venues')

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
                                        <h5>Venue - <span class="text-primary">Add</span></h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-12 text-center mb-3">
                                                <div class="user-upload wid-75">
                                                    <img src="{{ asset('storage/assets/images/user/venue.png') }}" alt="img"
                                                        class="img-fluid">
                                                    <label for="uplfile" class="img-avtar-upload">
                                                        <i class="ti ti-camera f-24 mb-1"></i>
                                                        <span>Upload</span>
                                                    </label>
                                                    <input type="file" id="uplfile" class="d-none">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="district_id">District<span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-control" id="district_id" name="district_id"
                                                        required>
                                                        <option>Select District</option>
                                                        <option value="1010">Chennai</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="center_id">Center<span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-control" id="center_id" name="center_id" required>
                                                        <option>Select Center</option>
                                                        <option value="1010">Alandur</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="mb-3">
                                                    <label class="form-label " for="name">Venue Name<span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="name"
                                                        name="name" placeholder="Gov Hr Sec School" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label ">Venue Code<span
                                                            class="text-danger">*</span></label>
                                                    <input type="number" class="form-control no-arrows" id="venue_code"
                                                        name="venue_code" placeholder="0102" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="venue_code_provider">Venue Code
                                                        Provider<span class="text-danger">*</span></label>
                                                    <select class="form-control" id="venue_code_provider"
                                                        name="venue_code_provider" required>
                                                        <option>Select Venue Code Provider</option>
                                                        <option value="UDISE">UDISE</option>
                                                        <option value="1">Anna University</option>
                                                        <option value="2">Thriuvalluvar University</option>
                                                        <option value="2">Madras University</option>
                                                        <option value="2">Madurai Kamraj University</option>
                                                        <option value="2">Manonmaniam Sundaranar University</option>
                                                        <option value="2">Others</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Email<span
                                                            class="text-danger">*</span></label>
                                                    <input type="email" class="form-control" id="mail" name="mail"
                                                        placeholder="ceochn@***.in" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="phone">Phone<span
                                                            class="text-danger">*</span></label>
                                                    <input type="tel" class="form-control" id="phone" name="phone"
                                                        placeholder="9434***1212" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="alternate_phone">Alternate Phone</label>
                                                    <input type="tel" class="form-control" id="alternate_phone"
                                                        name="alternate_phone" placeholder="O4448***762/9434***1212">
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="type">Type<span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-control" id="type" name="type" required>
                                                        <option>School</option>
                                                        <option>College</option>
                                                        <option>Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="category">Category<span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-control" id="category" name="category" required>
                                                        <option>Government</option>
                                                        <option>Private</option>
                                                        <option>Aided</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Website</label>
                                                    <input type="url" class="form-control" id="website"
                                                        name="website" placeholder="https://chennai.nic.in/">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label"for="password">Password<span
                                                            class="text-danger">*</span></label>
                                                    <input type="password" class="form-control" id="password"
                                                        name="password" required placeholder="******">
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
                                                    <textarea class="form-control" id="address" name="address" required
                                                        placeholder="Tamil Nadu Public Service Commission, TNPSC Road, Broadway, Chennai-600003."></textarea>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="dt_railway">Distance from Railway<span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" step="any" class="form-control"
                                                        id="dt_railway" name="dt_railway" placeholder="8.2km">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="dt_treasury">Distance from
                                                        Treasury<span class="text-danger">*</span></label>
                                                    <input type="text" step="any" class="form-control"
                                                        id="dt_treasury" name="dt_treasury" placeholder="1.2km">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="longitude">longitude<span
                                                            class="text-danger">*</span></label>
                                                    <input type="number" step="any" class="form-control"
                                                        id="longitude" name="longitude" placeholder="11.2312312312312">
                                                </div>
                                            </div>
                                            <div class="col-sm-6 ">
                                                <div class="mb-3">
                                                    <label class="form-label" for="latitude">latitude<span
                                                            class="text-danger">*</span></label>
                                                    <input type="number" step="any" class="form-control"
                                                        id="latitude" name="latitude" placeholder="11.2312312312312">
                                                </div>
                                            </div>
                                            <div class="col-sm-6  d-inline-flex justify-content-center">
                                                <a href="#"
                                                    class="btn btn-success d-inline-flex  justify-content-center"><i
                                                        class="ti ti-current-location me-1"></i>Get Location
                                                    Coordinates</a>
                                            </div>
                                            <div class="col-sm-6 d-inline-flex justify-content-center">
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
                                                    <input type="text" step="any" class="form-control"
                                                        id="bank_ac_name" name="bank_ac_name"
                                                        placeholder="State Bank Of India">
                                                </div>
                                            </div>
                                            <div class="col-sm-6 ">
                                                <div class="mb-3">
                                                    <label class="form-label" for="bank_ac_name">Account Name</label>
                                                    <input type="text" step="any" class="form-control"
                                                        id="bank_ac_name" name="bank_ac_name"
                                                        placeholder="Gov Hr Sec School">
                                                </div>
                                            </div>
                                            <div class="col-sm-6 ">
                                                <div class="mb-3">
                                                    <label class="form-label" for="bank_ac_number">
                                                        Number</label>
                                                    <input type="text" step="any" class="form-control"
                                                        id="bank_ac_number" name="bank_ac_number"
                                                        placeholder="2312312312312">
                                                </div>
                                            </div>
                                            <div class="col-sm-6 ">
                                                <div class="mb-3">
                                                    <label class="form-label" for="bank_ac_branch"> Branch
                                                        Name</label>
                                                    <input type="text" step="any" class="form-control"
                                                        id="bank_ac_branch" name="bank_ac_branch" placeholder="chennai">
                                                </div>
                                            </div>
                                            <div class="col-sm-6 ">
                                                <div class="mb-3">
                                                    <label class="form-label" for="bank_ac_type"> Type</label>
                                                    <input type="text" step="any" class="form-control"
                                                        id="bank_ac_type" name="bank_ac_type" placeholder="current">
                                                </div>
                                            </div>
                                            <div class="col-sm-6 ">
                                                <div class="mb-3">
                                                    <label class="form-label" for="bank_ac_ifsc"> IFSC
                                                        Code</label>
                                                    <input type="text" step="any" class="form-control"
                                                        id="bank_ac_ifsc" name="bank_ac_ifsc" placeholder="SBI000123">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 text-end btn-page">
                                <div class="btn btn-outline-secondary">Cancel</div>
                                <div class="btn btn-primary">Create</div>
                            </div>
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
