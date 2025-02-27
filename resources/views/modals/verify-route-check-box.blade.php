<div class="modal fade modal-animate anim-blur" data-bs-backdrop="static" id="verifyroutecheckbox" tabindex="-1"
    aria-labelledby="verifyAllMaterialsHandoveredModelLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('charted.vehicle.verification') }}" method="POST">
                @csrf
                <input type="hidden" name="vehicle_id_cv" value="" id="vehicle_id_cv">
                <div class="modal-header">
                    <h5 class="modal-title text-primary" id="verifyAllMaterialsHandoveredModelLabel">
                        <i class="feather icon-check-circle me-2"></i> Charted Vehicle Verification
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <!-- Checklist items -->
                            <div class="form-check mb-2">
                                <input class="form-check-input input-primary" type="checkbox" id="GPSlockintact"
                                    name="GPS_lock_intact">
                                <label class="form-check-label" for="GPSlockintact">Whether the GPS lock is intact?</label>
                            </div>

                            <div class="form-check mb-2">
                                <input class="form-check-input input-primary" type="checkbox" id="oneGPSlockintact"
                                    name="one_GPS_lock_intact">
                                <label class="form-check-label" for="oneGPSlockintact">Whether the seal on the Single One Time Lock is intact?</label>
                            </div>

                            <div class="form-check mb-2">
                                <input class="form-check-input input-primary" type="checkbox"
                                    id="OTLintacl" name="OTL_intacl">
                                <label class="form-check-label" for="OTLintacl">Whether the OTL is intact?</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input input-primary" type="checkbox"
                                    id="predeterminedorder" name="pre_determined_order">
                                <label class="form-check-label" for="predeterminedorder">Whether the trunk boxes have been arranged in the pre-determined order?</label>
                            </div>

                            <div class="form-check mb-2">
                                <input class="form-check-input input-primary" type="checkbox" id="onetimelocktrunkbox"
                                    name="one_time_lock_trunk_box">
                                <label class="form-check-label" for="onetimelocktrunkbox">Whether the One Time Locks of the trunk boxes are intact?</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input input-primary" type="checkbox" id="numberonetimelocktrunkbox"
                                    name="number_one_lock_trunk_box">
                                <label class="form-check-label" for="numberonetimelocktrunkbox">Whether the numbers found on the One Time Locks have been verified with that available in the app?</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input input-primary" type="checkbox" id="verifiedavailabeapp"
                                    name="verified_availabe_app">
                                <label class="form-check-label" for="verifiedavailabeapp">Whether the numbers found on the One Time Locks have been verified with that available in the app?</label>
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
    function setVehicleIds(link) {
        var routeId = link.getAttribute('data-route-id');
        console.log(routeId);
        document.getElementById('vehicle_id_cv').value = routeId;
    }
</script>
