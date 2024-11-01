<div class="modal fade modal-animate anim-blur" data-bs-backdrop="static" id="meetingPreliminaryCheckListModel"
    tabindex="-1" aria-labelledby="meetingPreliminaryCheckListModelLabel" aria-hidden="true">
    <div class="modal-dialog  ">
        <div class="modal-content">
            <div class="modal-header ">
                <h5 class="modal-title text-primary" id="meetingPreliminaryCheckListModelLabel">
                    <i class="feather icon-map-pin me-2"></i>Preliminary Checklist
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="row g4">
                        <div class="col md-6">
                            <div class="form-check mb-2"><input class="form-check-input input-primary" type="checkbox"
                                    id="customCheckc1" checked="checked"> <label class="form-check-label"
                                    for="customCheckc1">Received Appointment Letter</label></div>
                            <div class="form-check mb-2"><input class="form-check-input input-primary" type="checkbox"
                                    id="customCheckc1" checked="checked"> <label class="form-check-label"
                                    for="customCheckc1">Received Packet contaning attendance sheet
                                    -cum-hall-sketch</label></div>
                                    <div class="mb-2">
                                        <label for="customInputc3" class="form-label">Received Amount</label>
                                        <input type="text" class="form-control input-primary" id="customInputc3">
                                    </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary d-flex align-items-center" onclick="saveVenueDetails()">
                    <i class="feather icon-save me-2"></i>Save Changes
                </button>
            </div>
        </div>
    </div>
</div>
