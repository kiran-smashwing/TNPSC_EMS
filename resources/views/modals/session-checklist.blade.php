<div class="modal fade modal-animate anim-blur" id="sessionCheckListModel" tabindex="-1"
    aria-labelledby="sessionCheckListModelLabel" aria-hidden="true">
    <div class="modal-dialog  ">
        <div class="modal-content">
            <div class="modal-header ">
                <h5 class="modal-title text-primary" id="sessionCheckListModelLabel">
                    <i class="feather icon-map-pin me-2"></i>Session Checklist 
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
                                    for="customCheckc1"> Whether instructions were given to the invigilator</label></div>
                            <div class="form-check mb-2"><input class="form-check-input input-primary" type="checkbox"
                                    id="customCheckc1" checked="checked"> <label class="form-check-label"
                                    for="customCheckc1">Adequate seating arrangements are available.</label></div>
                            <div class="form-check mb-2"><input class="form-check-input input-primary" type="checkbox"
                                    id="customCheckc1" checked="checked"> <label class="form-check-label"
                                    for="customCheckc1">     Whether Inspection Staff present
                                    <div class="mt-3">
                                        <div class="row g-2">
                                            <div class="col-md-4">
                                                <label for="inspectionStaffName" class="form-label">Name</label>
                                                <input type="text" id="inspectionStaffName" class="form-control" placeholder="Enter Name">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="inspectionStaffDesignation" class="form-label">Designation</label>
                                                <input type="text" id="inspectionStaffDesignation" class="form-control" placeholder="Enter Designation">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="inspectionStaffDepartment" class="form-label">Department</label>
                                                <input type="text" id="inspectionStaffDepartment" class="form-control" placeholder="Enter Department">
                                            </div>
                                        </div>
                                    </div></label></div>
                            <div class="form-check mb-2"><input class="form-check-input input-primary" type="checkbox"
                                    id="customCheckc1" checked="checked"> <label class="form-check-label"
                                    for="customCheckc1">Drinking water and toilet facilities are available</label></div>
                            <div class="form-check mb-2"><input class="form-check-input input-primary" type="checkbox"
                                    id="customCheckc1" checked="checked"> <label class="form-check-label"
                                    for="customCheckc1">Adequate security arrangements are available</label></div>
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

