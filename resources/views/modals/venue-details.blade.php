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

                <form id="venueDetailsForm" method="POST" action="" autocomplete="off">
                    @csrf
                    <div class="row g-3">
                        <!-- Venue Name -->
                        <div class="col-md-6">
                            <label for="venue_name" class="form-label">Venue Name *</label>
                            <textarea class="form-control" name="venue_name" id="venue_name" rows="2" required>{{ $user->venue_name }}</textarea>
                        </div>

                        <!-- Address Line 1 -->
                        <div class="col-md-6">
                            <label for="address_1" class="form-label">Address Line 1 *</label>
                            <textarea class="form-control" name="address_1" id="address_1" rows="2" required>{{ $user->venue_address }}</textarea>
                        </div>

                        <!-- Address Line 2 -->
                        <div class="col-md-6">
                            <label for="address_2" class="form-label">Address Line 2</label>
                            <textarea class="form-control" name="address_2" id="address_2" rows="2"></textarea>
                        </div>

                        <!-- Pincode -->
                        <div class="col-md-3">
                            <label for="pincode" class="form-label">Pincode *</label>
                            <input type="text" class="form-control" name="pincode" id="pincode"
                                value="{{ $user->venue_pincode }}" required>
                        </div>

                        <!-- Landmark -->
                        <div class="col-md-3">
                            <label for="landmark" class="form-label">Landmark *</label>
                            <input type="text" class="form-control" name="landmark" id="landmark" required>
                        </div>

                        <!-- Taluk Name -->
                        <div class="col-md-4">
                            <label for="grade" class="form-label">Taluk Name *</label>
                            <input type="text" class="form-control" name="grade" id="grade" required>
                        </div>

                        <!-- District -->
                        <div class="col-md-4">
                            <label for="district" class="form-label">District *</label>
                            <input type="text" class="form-control" name="district" id="district" required>
                        </div>

                        <!-- Email -->
                        <div class="col-md-4">
                            <label for="cmail" class="form-label">Email-ID (School/College) *</label>
                            <input type="email" class="form-control" name="cmail" id="cmail"
                                value="{{ $user->venue_email }}" required>
                        </div>

                        <!-- Contact No -->
                        <div class="col-md-4">
                            <label for="phone" class="form-label">Contact No (School/College) *</label>
                            <input type="tel" class="form-control" name="phone" id="phone"
                                value="{{ $user->venue_phone }}" required>
                        </div>

                        <!-- HM/Principal's Contact No -->
                        <div class="col-md-4">
                            <label for="CI_mobile" class="form-label">HM/Principal's Contact No *</label>
                            <input type="tel" class="form-control" name="CI_mobile" id="CI_mobile"
                                value="{{ $user->venue_alternative_phone }}" required>
                        </div>

                        <!-- Type -->
                        <div class="col-md-4">
                            <label for="type" class="form-label">Type *</label>
                            <select class="form-select" name="type" id="type" required>
                                <option value="" disabled {{ empty($user->venue_category) ? 'selected' : '' }}>
                                    ---select---</option>
                                <option value="GOVERNMENT" @selected($user->venue_category === 'GOVERNMENT')>Government</option>
                                <option value="AIDED" @selected($user->venue_category === 'AIDED')>Aided</option>
                                <option value="PRIVATE" @selected($user->venue_category === 'PRIVATE')>Private</option>
                            </select>
                        </div>

                        <!-- Distance from Bus Stand/Railway Station -->
                        <div class="col-md-4">
                            <label for="disbus" class="form-label">Distance from Bus Stand/Railway Station (KM)
                                *</label>
                            <input type="number" step="0.01" class="form-control" name="disbus" id="disbus"
                             value="{{$user->venue_distance_railway}}"    required>
                        </div>

                        <!-- Distance from Treasury -->
                        <div class="col-md-4">
                            <label for="dis_tressary" class="form-label">Distance from Treasury (KM) *</label>
                            <input type="number" step="0.01" class="form-control" name="dis_tressary"
                            value="{{$user->venue_treasury_office}}"     id="dis_tressary" required>
                        </div>

                        <!-- Latitude -->
                        <div class="col-md-4">
                            <label for="latitute" class="form-label">Latitude *</label>
                            <input type="number" step="0.000001" class="form-control" name="latitute"
                            value="{{$user->venue_latitude}}"      id="latitute" required>
                        </div>

                        <!-- Longitude -->
                        <div class="col-md-4">
                            <label for="langtitute" class="form-label">Longitude *</label>
                            <input type="number" step="0.000001" class="form-control" name="langtitute"
                            value="{{$user->venue_longitude}}"     id="langtitute" required>
                        </div>

                        <!-- Google Maps Link -->
                        <div class="col-md-4 d-flex align-items-end">
                            <a href="https://www.google.com/maps" class="btn btn-outline-primary" target="_blank">
                                <i class="feather icon-map me-2"></i>Open Google Maps
                            </a>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary d-flex align-items-center"
                    onclick="saveVenueDetails()">
                    <i class="feather icon-save me-2"></i>Save Changes
                </button>
            </div>
        </div>
    </div>
</div>
