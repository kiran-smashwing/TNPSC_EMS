<div class="modal fade modal-animate anim-blur" data-bs-backdrop="static" id="venueDetailsModal" tabindex="-1"
    aria-labelledby="venueDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary ">
                <h5 class="modal-title text-white" id="venueDetailsModalLabel">
                    <i class="feather icon-map-pin me-2"></i>Venue Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning" role="alert">
                    <i class="feather icon-alert-triangle me-2"></i>
                    Please check the address details carefully and make appropriate corrections. All fields marked with
                    an asterisk (*) are required.
                </div>
                <form action="{{ route('venues.update', $user->venue_id) }}" id="venueDetailsForm" method="POST"
                    enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label" for="district">District <span
                                        class="text-danger">*</span></label>
                                <select class="form-control @error('district') is-invalid @enderror" id="district"
                                    name="district" required disabled>
                                    <option>Select District</option>
                                    @foreach ($districts as $district)
                                        <option value="{{ $district->district_code }}"
                                            {{ $district->district_code == $user->venue_district_id ? 'selected' : '' }}>
                                            {{ $district->district_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <!-- Hidden Input to Submit the Selected District -->
                                <input type="hidden" name="district" value="{{ $user->venue_district_id }}">

                                @error('district')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label" for="center">Center <span
                                        class="text-danger">*</span></label>
                                <select class="form-control @error('center') is-invalid @enderror" id="center"
                                    name="center" required disabled>
                                    <option>Select Centers</option>
                                    @foreach ($centers as $center)
                                        <option value="{{ $center->center_code }}"
                                            {{ $center->center_code == $user->venue_center_id ? 'selected' : '' }}>
                                            {{ $center->center_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <!-- Hidden Input to Submit the Selected center -->
                                <input type="hidden" name="center" value="{{ $user->venue_center_id }}">

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
                                    id="venue_name" name="venue_name" value="{{ old('name', $user->venue_name) }}"
                                    placeholder="Gov Hr Sec School" required>
                                @error('venue_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="mb-3">
                                <label class="form-label" for="address">Address<span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" required
                                    placeholder="Tamil Nadu Public Service Commission, TNPSC Road, Broadway, Chennai-600003.">{{ old('address', $user->venue_address) }}</textarea>
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
                                    placeholder="Chennai TK & DT">{{ old('address2', $user->venue_address_2) }}</textarea>
                                @error('address2')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="mb-3">
                                <label class="form-label">Landmark <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('landmark') is-invalid @enderror" id="landmark" name="landmark" required
                                    placeholder="NEAR TO NEW BUS STAND">{{ old('landmark', $user->venue_landmark) }}</textarea>
                                @error('landmark')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="mb-3">
                                <label class="form-label ">Pin Code<span class="text-danger">*</span></label>
                                <input type="number" value="{{ old('pin_code', $user->venue_pincode) }}"
                                    class="form-control no-arrows @error('pin_code') is-invalid @enderror"
                                    id="pin_code" name="pin_code" placeholder="600001" required>
                                @error('pin_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="mb-3">
                                <label class="form-label">Venue Code<span class="text-danger">*</span></label>
                                <input type="number"
                                    class="form-control @error('venue_code') is-invalid @enderror no-arrows"
                                    id="venue_code" name="venue_code" value="{{ old('name', $user->venue_code) }}"
                                    placeholder="448966" required>
                                @error('venue_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="mb-3">
                                <label class="form-label" for="venue_code_provider">Venue Code Provider<span
                                        class="text-danger">*</span></label>
                                <select class="form-control @error('venue_code_provider') is-invalid @enderror"
                                    id="venue_code_provider" name="venue_code_provider" required>
                                    <option>Select Venue Code Provider</option>
                                    <option value="UDISE"
                                        {{ old('code_provider', $user->venue_codeprovider) == 'UDISE' ? 'selected' : '' }}>
                                        UDISE</option>
                                    <option value="1"
                                        {{ old('code_provider', $user->venue_codeprovider) == '1' ? 'selected' : '' }}>
                                        Anna University</option>
                                    <option value="2"
                                        {{ old('code_provider', $user->venue_codeprovider) == '2' ? 'selected' : '' }}>
                                        Thriuvalluvar University</option>
                                    <option value="3"
                                        {{ old('code_provider', $user->venue_codeprovider) == '3' ? 'selected' : '' }}>
                                        Madras University</option>
                                    <option value="4"
                                        {{ old('code_provider', $user->venue_codeprovider) == '4' ? 'selected' : '' }}>
                                        Madurai Kamraj University</option>
                                    <option value="5"
                                        {{ old('code_provider', $user->venue_codeprovider) == '5' ? 'selected' : '' }}>
                                        Manonmaniam Sundaranar University</option>
                                    <option value="6"
                                        {{ old('code_provider', $user->venue_codeprovider) == '6' ? 'selected' : '' }}>
                                        Others</option>
                                </select>
                                @error('venue_code_provider')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="mb-3">
                                <label class="form-label">Email<span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" value="{{ old('name', $user->venue_email) }}" name="email"
                                    placeholder="ceochn@***.in" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="mb-3">
                                <label class="form-label" for="phone">Phone<span
                                        class="text-danger">*</span></label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                    id="phone" value="{{ old('name', $user->venue_phone) }}" name="phone"
                                    placeholder="9434***1212" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="mb-3">
                                <label class="form-label" for="alternate_phone">Alternate Phone</label>
                                <input type="tel"
                                    class="form-control @error('alternate_phone') is-invalid @enderror"
                                    value="{{ old('name', $user->venue_alternative_phone) }}" id="alternative_phone"
                                    name="alternative_phone" placeholder="O4448***762/9434***1212">
                                @error('alternate_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="mb-3">
                                <label class="form-label" for="type">Type<span
                                        class="text-danger">*</span></label>
                                <select class="form-control @error('type') is-invalid @enderror" id="type"
                                    name="type" required>
                                    <option value="School"
                                        {{ old('type', $user->venue_type) == 'School' ? 'selected' : '' }}>School
                                    </option>
                                    <option value="College"
                                        {{ old('type', $user->venue_type) == 'College' ? 'selected' : '' }}>College
                                    </option>
                                    <option value="Other"
                                        {{ old('type', $user->venue_type) == 'Other' ? 'selected' : '' }}>Other
                                    </option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="mb-3">
                                <label class="form-label" for="category">Category<span
                                        class="text-danger">*</span></label>
                                <select class="form-control @error('category') is-invalid @enderror" id="category"
                                    name="category" required>
                                    <option value="Government"
                                        {{ old('category', $user->venue_category) == 'Government' ? 'selected' : '' }}>
                                        Government</option>
                                    <option value="Private"
                                        {{ old('category', $user->venue_category) == 'Private' ? 'selected' : '' }}>
                                        Private</option>
                                    <option value="Aided"
                                        {{ old('category', $user->venue_category) == 'Aided' ? 'selected' : '' }}>Aided
                                    </option>
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="mb-3">
                                <label class="form-label">Website</label>
                                <input type="url" class="form-control @error('website') is-invalid @enderror"
                                    id="website" name="website" value="{{ old('name', $user->venue_website) }}"
                                    placeholder="https://chennai.nic.in/">
                                @error('website')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="mb-3">
                                <label class="form-label" for="distance_from_treasury">Distance from
                                    Collectorate<span class="text-danger">*</span></label>
                                <input type="text" step="any"
                                    class="form-control @error('distance_from_treasury') is-invalid @enderror"
                                    value="{{ old('distance_from_treasury', $user->venue_treasury_office) }}"
                                    id="distance_from_treasury" name="distance_from_treasury" placeholder="1.2km">
                                @error('distance_from_treasury')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="mb-3">
                                <label class="form-label" for="dt_railway">Distance from Railway Station / Bus
                                    Stand<span class="text-danger">*</span></label>
                                <input type="text" step="any"
                                    class="form-control @error('distance_from_railway') is-invalid @enderror"
                                    id="distance_from_railway"
                                    value="{{ old('distance_from_railway', $user->venue_distance_railway) }}"
                                    name="distance_from_railway" placeholder="8.2km">
                                @error('distance_from_railway')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-4 ">
                            <div class="mb-3">
                                <label class="form-label" for="latitude">Latitude<span
                                        class="text-danger">*</span></label>
                                <input type="number" step="any"
                                    class="form-control @error('latitude') is-invalid @enderror" id="latitude"
                                    name="latitude" value="{{ old('latitude', $user->venue_latitude) }}"
                                    placeholder="11.2312312312312">
                                @error('latitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="mb-3">
                                <label class="form-label" for="longitude">Longitude<span
                                        class="text-danger">*</span></label>
                                <input type="number" step="any"
                                    class="form-control @error('longitude') is-invalid @enderror" id="longitude"
                                    value="{{ old('longitude', $user->venue_longitude) }}" name="longitude"
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
                        <hr>
                        <div class="col-sm-4 ">
                            <div class="mb-3">
                                <label class="form-label" for="bank_name">Bank Name</label>
                                <input type="text" step="any"
                                    class="form-control @error('bank_name') is-invalid @enderror" id="bank_name"
                                    value="{{ old('bank_name', $user->venue_bank_name) }}" name="bank_name"
                                    placeholder="State Bank Of India">
                                @error('bank_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-4 ">
                            <div class="mb-3">
                                <label class="form-label" for="account_name">Account Name</label>
                                <input type="text" step="any"
                                    class="form-control @error('account_name') is-invalid @enderror"
                                    id="account_name" value="{{ old('account_name', $user->venue_account_name) }}"
                                    name="account_name" placeholder="Gov Hr Sec School">
                                @error('account_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-4 ">
                            <div class="mb-3">
                                <label class="form-label" for="bank_ac_number">
                                    Number</label>
                                <input type="text" step="any"
                                    class="form-control @error('account_number') is-invalid @enderror"
                                    id="account_number"
                                    value="{{ old('account_number', $user->venue_account_number) }}"
                                    name="account_number" placeholder="2312312312312">
                                @error('account_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-4 ">
                            <div class="mb-3">
                                <label class="form-label" for="branch_name"> Branch
                                    Name</label>
                                <input type="text" step="any"
                                    class="form-control @error('branch_name') is-invalid @enderror" id="branch_name"
                                    name="branch_name" value="{{ old('branch_name', $user->venue_branch_name) }}"
                                    placeholder="chennai">
                                @error('branch_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-4 ">
                            <div class="mb-3">
                                <label class="form-label" for="account_type"> Type</label>
                                <input type="text" step="any"
                                    class="form-control @error('account_type') is-invalid @enderror"
                                    id="account_type" name="account_type"
                                    value="{{ old('account_type', $user->venue_account_type) }}"
                                    placeholder="current">
                                @error('account_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="mb-3">
                                <label class="form-label" for="ifsc"> IFSC
                                    Code</label>
                                <input type="text" step="any"
                                    class="form-control @error('ifsc') is-invalid @enderror" id="ifsc"
                                    value="{{ old('ifsc', $user->venue_ifsc) }}" name="ifsc"
                                    placeholder="SBI000123">
                                @error('ifsc')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" id="save-button" class="btn btn-primary d-flex align-items-center">
                    <i class="feather icon-save me-2"></i>Update
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById('save-button').addEventListener('click', function() {
        document.getElementById('venueDetailsForm').submit();
    });
</script>
