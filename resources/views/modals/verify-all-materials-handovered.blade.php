<div class="modal fade modal-animate anim-blur" data-bs-backdrop="static" id="verifyAllMaterialsHandovered" tabindex="-1"
    aria-labelledby="verifyAllMaterialsHandoveredModelLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('bundle-packaging.save-handover-details') }}" method="POST">
                @csrf
                <input type="hidden" name="vehicle_id" value="" id="vehicle_id">
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
                                <input class="form-check-input input-primary" type="checkbox" id="memoryCardCheck"
                                    name="memory_card_handovered">
                                <label class="form-check-label" for="memoryCardCheck">Has the memory card been handed
                                    over from the vehicle?</label>
                            </div>

                            <div class="form-check mb-2">
                                <input class="form-check-input input-primary" type="checkbox" id="cameraCheck"
                                    name="camera_handovered">
                                <label class="form-check-label" for="cameraCheck">Has the camera been handed over from
                                    the vehicle?</label>
                            </div>

                            <div class="form-check mb-2">
                                <input class="form-check-input input-primary" type="checkbox"
                                    id="confidentialMaterialCheck" name="confidential_material_offloaded">
                                <label class="form-check-label" for="confidentialMaterialCheck">Has the confidential
                                    material been offloaded from the vehicle?</label>
                            </div>

                            <div class="form-check mb-2">
                                <input class="form-check-input input-primary" type="checkbox" id="gpsLockCheck"
                                    name="gps_lock_handovered">
                                <label class="form-check-label" for="gpsLockCheck">Has the GPS lock been handed over
                                    from the vehicle?</label>
                            </div>

                            <!-- Final remarks -->
                            <div class="mt-3">
                                <label for="remarks" class="form-label fw-bold">Remarks</label>
                                <textarea id="remarks" name="final_remarks" class="form-control" placeholder="Enter any additional remarks"
                                    rows="4"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary d-flex align-items-center">
                        <i class="feather icon-save me-2"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    function setVehicleId(link) {
        var routeId = link.getAttribute('data-route-id');
        document.getElementById('vehicle_id').value = routeId;
    }
</script>
