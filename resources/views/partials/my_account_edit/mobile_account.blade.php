<form action="{{ route('mobile-team-staffs.update', $mobileTeamStaff->mobile_id) }}" method="POST"
    enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5>Mobile Team - <span class="text-primary">Edit</span></h5>
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-sm-6 text-center mb-3">
                            <div class="user-upload wid-75" data-pc-animate="just-me"
                                data-bs-toggle="modal" data-bs-target="#cropperModal">
                                <img src="{{ $mobileTeamStaff->mobile_image
                                    ? asset('storage/' . $mobileTeamStaff->mobile_image)
                                    : asset('storage/assets/images/user/avatar-4.jpg') }}"
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
                                    id="district" name="district" readonly>
                                    <option>Select District</option>
                                    @foreach ($districts as $district)
                                        <option value="{{ $district->district_code }}"
                                            {{ $district->district_code == $mobileTeamStaff->mobile_district_id ? 'selected' : '' }}>
                                            {{ $district->district_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('district')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div> --}}
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label" for="district">District </label>
                                <input type="text" 
                                       class="form-control @error('district') is-invalid @enderror" 
                                       id="district" 
                                       name="district_display" 
                                       value="{{ $districts->firstWhere('district_code', $mobileTeamStaff->mobile_district_id)->district_name ?? 'Select District' }}" 
                                       readonly>
                                <input type="hidden" 
                                       name="district" 
                                       value="{{ $mobileTeamStaff->mobile_district_id }}">
                                @error('district')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">Name <span
                                        class="text-danger">*</span></label>
                                <input type="text"
                                    class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name"
                                    value="{{ $mobileTeamStaff->mobile_name }}" required>
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
                                    value="{{ $mobileTeamStaff->mobile_employeeid }}" required>
                                @error('employee_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label" for="designation">Designation <span
                                        class="text-danger">*</span></label>
                                <input type="text"
                                    class="form-control @error('designation') is-invalid @enderror"
                                    id="designation" name="designation"
                                    value="{{ $mobileTeamStaff->mobile_designation }}" required>
                                @error('designation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">Email<span
                                        class="text-danger">*</span></label>
                                <input type="email"
                                    class="form-control @error('mail') is-invalid @enderror"
                                    id="mail" name="mail"
                                    value="{{ $mobileTeamStaff->mobile_email }}" required>
                                @error('mail')
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
                                    id="phone" name="phone"
                                    value="{{ $mobileTeamStaff->mobile_phone }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        {{-- <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label" for="password">Password <small>(Leave
                                        blank
                                        to keep current)</small></label>
                                <input type="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" placeholder="******">
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
            <a href="{{ route('mobile-team-staffs.index') }}"
                class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </div>
</form>