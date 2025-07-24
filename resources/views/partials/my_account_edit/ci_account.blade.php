<form action="{{ route('chief-invigilators.update', $chiefInvigilator->ci_id) }}" method="POST"
    enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5>Chief Invigilator - <span class="text-primary">Edit</span></h5>
                </div>
                <div class="card-body">


                    <div class="row">
                        <div class="col-sm-12 text-center mb-3">
                            <div class="user-upload wid-75" data-pc-animate="just-me" data-bs-toggle="modal"
                                data-bs-target="#cropperModal">
                                <img src="{{ $chiefInvigilator->ci_image
                                    ? asset('storage/' . $chiefInvigilator->ci_image)
                                    : asset('storage/assets/images/user/avatar-4.jpg') }}"
                                    id="previewImage" alt="Cropped Preview"
                                    style="max-width: 100%; height: auto; object-fit: cover;">
                                <input type="hidden" name="cropped_image" id="cropped_image">
                                <label for="imageUpload" class="img-avtar-upload"></label>
                            </div>
                        </div>
                        {{-- <!-- District Dropdown -->
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label" for="district">District<span
                                        class="text-danger">*</span></label>
                                <select class="form-control @error('district') is-invalid @enderror"
                                    id="district" name="district" required>
                                    <option value="">Select District</option>
                                    @foreach ($districts as $district)
                                        <option value="{{ $district->district_code }}"
                                            {{ old('district', $chiefInvigilator->ci_district_id) == $district->district_code ? 'selected' : '' }}>
                                            {{ $district->district_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('district')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- Center Dropdown -->
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label" for="center">Center<span
                                        class="text-danger">*</span></label>
                                <select class="form-control @error('center') is-invalid @enderror"
                                    id="center" name="center" required>
                                    <option value="">Select Center</option>
                                <!-- Centers will be dynamically populated -->    
                                </select>
                                @error('center')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- Venue Dropdown -->
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label" for="venue">Venue<span
                                        class="text-danger">*</span></label>
                                <select class="form-control @error('venue') is-invalid @enderror"
                                    id="venue" name="venue" required>
                                    <option value="">Select Venue</option>
                                <!-- Venues will be dynamically populated -->   
                                </select>
                                @error('venue')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div> --}}

                        <!-- District Readonly Input -->
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label" for="district">District <span
                                        class="text-danger">*</span></label>
                                <!-- Display selected district name -->
                                <input type="text" class="form-control @error('district') is-invalid @enderror"
                                    id="district_display" name="district_display"
                                    value="{{ $districts->firstWhere('district_code', $chiefInvigilator->ci_district_id)->district_name ?? 'Select District' }}"
                                    readonly>
                                <!-- Hidden input for district code -->
                                <input type="hidden" id="district" name="district"
                                    value="{{ $chiefInvigilator->ci_district_id }}">
                                @error('district')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Center Readonly Input -->
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label" for="center">Center <span
                                        class="text-danger">*</span></label>
                                <!-- Display selected center name -->
                                <input type="text" class="form-control @error('center') is-invalid @enderror"
                                    id="center_display" name="center_display"
                                    value="{{ $centers->firstWhere('center_code', $chiefInvigilator->ci_center_id)->center_name ?? 'Select Center' }}"
                                    readonly>
                                <!-- Hidden input for center code -->
                                <input type="hidden" id="center" name="center"
                                    value="{{ $chiefInvigilator->ci_center_id }}">
                                @error('center')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Venue Readonly Input -->
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label" for="venue">Venue <span
                                        class="text-danger">*</span></label>
                                <!-- Display selected venue name -->
                                <input type="text" class="form-control @error('venue') is-invalid @enderror"
                                    id="venue_display" name="venue_display"
                                    value="{{ $venues->firstWhere('venue_id', $chiefInvigilator->ci_venue_id)->venue_name ?? 'Select Venue' }}"
                                    readonly>
                                <!-- Hidden input for venue code -->
                                <input type="hidden" id="venue" name="venue"
                                    value="{{ $chiefInvigilator->ci_venue_id }}">
                                @error('venue')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>



                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name', $chiefInvigilator->ci_name) }}"
                                    required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label" for="employee_id">Employee ID <span
                                        class="text-danger">*</span></label>
                                <input type="text"
                                    class="form-control @error('employee_id') is-invalid @enderror"
                                    id="employee_id" name="employee_id"
                                    value="{{ old('employee_id', $chiefInvigilator->ci_employee_id) }}"
                                    required>
                                @error('employee_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label" for="designation">Designation <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('designation') is-invalid @enderror"
                                    id="designation" name="designation"
                                    value="{{ old('designation', $chiefInvigilator->ci_designation) }}" required>
                                @error('designation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email"
                                    value="{{ old('email', $chiefInvigilator->ci_email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label" for="phone">Phone <span
                                        class="text-danger">*</span></label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                    id="phone" name="phone"
                                    value="{{ old('phone', $chiefInvigilator->ci_phone) }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label" for="alternative_phone">Alternative
                                    Phone
                                    <span class="text-danger">*</span></label>
                                <input type="tel"
                                    class="form-control @error('alternative_phone') is-invalid @enderror"
                                    id="alternative_phone" name="alternative_phone"
                                    value="{{ old('alternative_phone', $chiefInvigilator->ci_alternative_phone) }}"
                                    required>
                                @error('alternative_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div> --}}

                        {{-- <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label" for="password">Password <span
                                        class="text-danger">*</span></label>
                                <input type="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password"
                                    placeholder="Leave blank to keep current">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 text-end btn-page">
            <a href="{{ route('chief-invigilators.index') }}" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </div>
</form>
