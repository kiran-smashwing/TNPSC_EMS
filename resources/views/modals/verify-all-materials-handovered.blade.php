<div class="modal fade modal-animate anim-blur" data-bs-backdrop="static" id="verifyAllMaterialsHandovered" tabindex="-1"
    aria-labelledby="verifyAllMaterialsHandoveredModelLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary" id="verifyAllMaterialsHandoveredModelLabel">
                    <i class="feather icon-check-circle me-2"></i> Verify Materials Handover
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Checklist items -->
                        <div class="form-check mb-2">
                            <input class="form-check-input input-primary" type="checkbox" id="memoryCardCheck">
                            <label class="form-check-label" for="memoryCardCheck">
                                Has the memory card been handed over from the vehicle?
                            </label>
                           
                        </div>

                        <div class="form-check mb-2">
                            <input class="form-check-input input-primary" type="checkbox" id="cameraCheck">
                            <label class="form-check-label" for="cameraCheck">
                                Has the camera been handed over from the vehicle?
                            </label>
                          
                        </div>

                        <div class="form-check mb-2">
                            <input class="form-check-input input-primary" type="checkbox" id="confidentialMaterialCheck">
                            <label class="form-check-label" for="confidentialMaterialCheck">
                                Has the confidential material been offloaded from the vehicle?
                            </label>
                         
                        </div>

                        <div class="form-check mb-2">
                            <input class="form-check-input input-primary" type="checkbox" id="gpsLockCheck">
                            <label class="form-check-label" for="gpsLockCheck">
                                Has the GPS lock been handed over from the vehicle?
                            </label>
                        
                        </div>

                        <!-- Final remarks -->
                        <div class="mt-3">
                            <label for="remarks" class="form-label fw-bold">Remarks</label>
                            <textarea id="remarks" class="form-control" placeholder="Enter any additional remarks" rows="4"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary d-flex align-items-center" onclick="saveHandoverDetails()">
                    <i class="feather icon-save me-2"></i> Save Changes
                </button>
            </div>
        </div>
    </div>
</div>
