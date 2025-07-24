<form action="{{ route('venues.update', $venue->venue_id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5>Venue - <span class="text-primary">Edit</span></h5>
                </div>
                <div class="card-body">
                    <div class="row">

                        <div class="col-sm-12 text-center mb-3">
                            <div class="user-upload wid-75" data-pc-animate="just-me" data-bs-toggle="modal"
                                data-bs-target="#cropperModal">
                                <img src="{{ $venue->venue_image
                                    ? asset('storage/' . $venue->venue_image)
                                    : asset('storage/assets/images/user/venue.png') }}"
                                    id="previewImage" alt="Cropped Preview"
                                    style="max-width: 100%; height: auto; object-fit: cover;">
                                <input type="hidden" name="cropped_image" id="cropped_image">
                                <label for="imageUpload" class="img-avtar-upload"></label>
                            </div>
                        </div>
                        {{-- <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label" for="district">District <span
                                        class="text-danger">*</span></label>
                                <select class="form-control @error('district') is-invalid @enderror"
                                    id="district" name="district" required>
                                    <option>Select District</option>
                                    @foreach ($districts as $district)
                                        <option value="{{ $district->district_code }}"
                                            {{ $district->district_code == $venue->venue_district_id ? 'selected' : '' }}>
                                            {{ $district->district_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('district')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label" for="center">Center <span
                                        class="text-danger">*</span></label>
                                <select class="form-control @error('center') is-invalid @enderror"
                                    id="center" name="center" required>
                                    <option>Select Centers</option>
                                    @foreach ($centers as $center)
                                        <option value="{{ $center->center_code }}"
                                            {{ $center->center_code == $venue->venue_center_id ? 'selected' : '' }}>
                                            {{ $center->center_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('center')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div> --}}
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label" for="district">District <span
                                        class="text-danger">*</span></label>
                                <!-- Readonly input to display selected district name -->
                                <input type="text" class="form-control @error('district') is-invalid @enderror"
                                    id="district_display" name="district_display"
                                    value="{{ $districts->firstWhere('district_code', $venue->venue_district_id)->district_name ?? 'Select District' }}"
                                    readonly>
                                <!-- Hidden input to submit the selected district code -->
                                <input type="hidden" id="district" name="district"
                                    value="{{ $venue->venue_district_id }}">
                                @error('district')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label" for="center">Center <span
                                        class="text-danger">*</span></label>
                                <!-- Readonly input to display selected center name -->
                                <input type="text" class="form-control @error('center') is-invalid @enderror"
                                    id="center_display" name="center_display"
                                    value="{{ $centers->firstWhere('center_code', $venue->venue_center_id)->center_name ?? 'Select Center' }}"
                                    readonly>
                                <!-- Hidden input to submit the selected center code -->
                                <input type="hidden" id="center" name="center"
                                    value="{{ $venue->venue_center_id }}">
                                @error('center')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="mb-3">
                                <label class="form-label" for="name">Venue Name<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('venue_name') is-invalid @enderror"
                                    id="venue_name" name="venue_name" value="{{ old('name', $venue->venue_name) }}"
                                    placeholder="Gov Hr Sec School" required>
                                @error('venue_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">Venue Code<span class="text-danger">*</span></label>
                                <input type="number"
                                    class="form-control @error('venue_code') is-invalid @enderror no-arrows"
                                    id="venue_code" name="venue_code" value="{{ old('name', $venue->venue_code) }}"
                                    placeholder="448966" required>
                                @error('venue_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label" for="venue_code_provider">Venue Code
                                    Provider<span class="text-danger">*</span></label>
                                <select class="form-control @error('venue_code_provider') is-invalid @enderror"
                                    id="venue_code_provider" name="venue_code_provider" required>
                                    <option>Select Venue Code Provider</option>
                                    <option value="UDISE"
                                        {{ old('code_provider', $venue->venue_codeprovider) == 'UDISE' ? 'selected' : '' }}>
                                        UDISE</option>
                                    <option value="1"
                                        {{ old('code_provider', $venue->venue_codeprovider) == '1' ? 'selected' : '' }}>
                                        Anna University</option>
                                    <option value="2"
                                        {{ old('code_provider', $venue->venue_codeprovider) == '2' ? 'selected' : '' }}>
                                        Thriuvalluvar University</option>
                                    <option value="3"
                                        {{ old('code_provider', $venue->venue_codeprovider) == '3' ? 'selected' : '' }}>
                                        Madras University</option>
                                    <option value="4"
                                        {{ old('code_provider', $venue->venue_codeprovider) == '4' ? 'selected' : '' }}>
                                        Madurai Kamraj University</option>
                                    <option value="5"
                                        {{ old('code_provider', $venue->venue_codeprovider) == '5' ? 'selected' : '' }}>
                                        Manonmaniam Sundaranar University</option>
                                    <option value="6"
                                        {{ old('code_provider', $venue->venue_codeprovider) == '6' ? 'selected' : '' }}>
                                        Others</option>
                                </select>
                                @error('venue_code_provider')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">Email<span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" value="{{ old('name', $venue->venue_email) }}" name="email"
                                    placeholder="ceochn@***.in" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label" for="phone">Phone<span
                                        class="text-danger">*</span></label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                    id="phone" value="{{ old('name', $venue->venue_phone) }}" name="phone"
                                    placeholder="9434***1212" required>
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
                                    class="form-control @error('alternate_phone') is-invalid @enderror"
                                    value="{{ old('name', $venue->venue_alternative_phone) }}" id="alternative_phone"
                                    name="alternative_phone" placeholder="O4448***762/9434***1212">
                                @error('alternate_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label" for="type">Type<span
                                        class="text-danger">*</span></label>
                                <select class="form-control @error('type') is-invalid @enderror" id="type"
                                    name="type" required>
                                    <option value="School"
                                        {{ old('type', $venue->venue_type) == 'School' ? 'selected' : '' }}>
                                        School</option>
                                    <option value="College"
                                        {{ old('type', $venue->venue_type) == 'College' ? 'selected' : '' }}>
                                        College</option>
                                    <option value="Other"
                                        {{ old('type', $venue->venue_type) == 'Other' ? 'selected' : '' }}>
                                        Other</option>
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
                                <select class="form-control @error('category') is-invalid @enderror" id="category"
                                    name="category" required>
                                    <option value="Government"
                                        {{ old('category', $venue->venue_category) == 'Government' ? 'selected' : '' }}>
                                        Government</option>
                                    <option value="Private"
                                        {{ old('category', $venue->venue_category) == 'Private' ? 'selected' : '' }}>
                                        Private</option>
                                    <option value="Aided"
                                        {{ old('category', $venue->venue_category) == 'Aided' ? 'selected' : '' }}>
                                        Aided</option>
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">Website</label>
                                <input type="url" class="form-control @error('website') is-invalid @enderror"
                                    id="website" name="website" value="{{ old('name', $venue->venue_website) }}"
                                    placeholder="https://chennai.nic.in/">
                                @error('website')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label ">Pin Code<span class="text-danger">*</span></label>
                                <input type="number" value="{{ old('pin_code', $venue->venue_pincode) }}"
                                    class="form-control no-arrows @error('pin_code') is-invalid @enderror"
                                    id="pin_code" name="pin_code" placeholder="600001" required>
                                @error('pin_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        {{-- <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">Password <small>(leave blank to keep
                                        current)</small></label>
                                <input type="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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

                        <div class="col-sm-12">
                            <div class="mb-3">
                                <label class="form-label" for="address">Address<span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" required
                                    placeholder="Tamil Nadu Public Service Commission, TNPSC Road, Broadway, Chennai-600003.">{{ old('address', $venue->venue_address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="mb-3">
                                <label class="form-label" for="address2">Address 2<span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control @error('address2') is-invalid @enderror" id="address2" name="address2" required
                                    placeholder="Chennai TK & DT">{{ old('address2', $venue->venue_address_2) }}</textarea>
                                @error('address2')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="mb-3">
                                <label class="form-label">Landmark <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('landmark') is-invalid @enderror" id="landmark" name="landmark" required
                                    placeholder="NEAR TO NEW BUS STAND">{{ old('landmark', $venue->venue_landmark) }}</textarea>
                                @error('landmark')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label" for="distance_from_treasury">Distance
                                    from
                                    Collectorate<span class="text-danger">*</span></label>
                                <input type="text" step="any"
                                    class="form-control @error('distance_from_treasury') is-invalid @enderror"
                                    value="{{ old('distance_from_treasury', $venue->venue_treasury_office) }}"
                                    id="distance_from_treasury" name="distance_from_treasury" placeholder="1.2km">
                                @error('distance_from_treasury')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label" for="dt_railway">Distance from
                                    Railway Station / Bus Stand<span class="text-danger">*</span></label>
                                <input type="text" step="any"
                                    class="form-control @error('distance_from_railway') is-invalid @enderror"
                                    id="distance_from_railway"
                                    value="{{ old('distance_from_railway', $venue->venue_distance_railway) }}"
                                    name="distance_from_railway" placeholder="8.2km">
                                @error('distance_from_railway')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6 ">
                            <div class="mb-3">
                                <label class="form-label" for="latitude">Latitude<span
                                        class="text-danger">*</span></label>
                                <input type="number" step="any"
                                    class="form-control @error('latitude') is-invalid @enderror" id="latitude"
                                    name="latitude" value="{{ old('latitude', $venue->venue_latitude) }}"
                                    placeholder="11.2312312312312">
                                @error('latitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label" for="longitude">Longitude<span
                                        class="text-danger">*</span></label>
                                <input type="number" step="any"
                                    class="form-control @error('longitude') is-invalid @enderror" id="longitude"
                                    value="{{ old('longitude', $venue->venue_longitude) }}" name="longitude"
                                    placeholder="11.2312312312312">
                                @error('longitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6  d-inline-flex justify-content-center mb-3">
                            <a href="#" class="btn btn-success d-inline-flex  justify-content-center"><i
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
                                <label class="form-label" for="bank_name">Bank Name</label>
                                <input type="text" step="any"
                                    class="form-control @error('bank_name') is-invalid @enderror" id="bank_name"
                                    value="{{ old('bank_name', $venue->venue_bank_name) }}" name="bank_name"
                                    placeholder="State Bank Of India">
                                @error('bank_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6 ">
                            <div class="mb-3">
                                <label class="form-label" for="account_name">Account Name</label>
                                <input type="text" step="any"
                                    class="form-control @error('account_name') is-invalid @enderror"
                                    id="account_name" value="{{ old('account_name', $venue->venue_account_name) }}"
                                    name="account_name" placeholder="Gov Hr Sec School">
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
                                    id="account_number"
                                    value="{{ old('account_number', $venue->venue_account_number) }}"
                                    name="account_number" placeholder="2312312312312">
                                @error('account_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6 ">
                            <div class="mb-3">
                                <label class="form-label" for="branch_name"> Branch
                                    Name</label>
                                <input type="text" step="any"
                                    class="form-control @error('branch_name') is-invalid @enderror" id="branch_name"
                                    name="branch_name" value="{{ old('branch_name', $venue->venue_branch_name) }}"
                                    placeholder="chennai">
                                @error('branch_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6 ">
                            <div class="mb-3">
                                <label class="form-label" for="account_type"> Type</label>
                                <input type="text" step="any"
                                    class="form-control @error('account_type') is-invalid @enderror"
                                    id="account_type" name="account_type"
                                    value="{{ old('account_type', $venue->venue_account_type) }}"
                                    placeholder="current">
                                @error('account_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6 ">
                            <div class="mb-3">
                                <label class="form-label" for="ifsc"> IFSC
                                    Code</label>
                                <input type="text" step="any"
                                    class="form-control @error('ifsc') is-invalid @enderror" id="ifsc"
                                    value="{{ old('ifsc', $venue->venue_ifsc) }}" name="ifsc"
                                    placeholder="SBI000123">
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
            <a href="{{ route('scribes.index') }}" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </div>
</form>
