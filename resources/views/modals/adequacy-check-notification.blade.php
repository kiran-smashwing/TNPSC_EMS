<div class="modal fade modal-animate anim-blur" data-bs-backdrop="static" id="adequacyCheckNotificationModal"
    tabindex="-1" aria-labelledby="adequacyCheckNotificationModalLabel" aria-hidden="true">
    <div class="modal-dialog  ">
        <div class="modal-content">
            <form method="POST" action="{{route('alert-notification.adequacy-check')}}">
                @csrf
                <input type="hidden" name="exam_id" value="{{ $session->currentexam->exam_main_no }}">
                <input type="hidden" name="exam_sess_date" value="{{ $session->exam_sess_date }}">
                <input type="hidden" name="exam_sess_session" value="{{ $session->exam_sess_session }}">

                <div class="modal-header ">
                    <h5 class="modal-title text-primary" id="adequacyCheckNotificationModalLabel">
                        <i class="feather icon-map-pin me-2"></i>Emergency Alarm Notification
                    </h5>

                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="adequacyCheckType" class="form-label">Adequacy Check Type</label>
                                <select class="form-select" name="adequacy_check_type" id="adequacyCheckType" required>
                                    <option value="count_mismatch_reported">Count mismatch reported?</option>
                                    <option value="discrepancy_reported">Any discrepancy reported?</option>
                                    <option value="seal_damage_reported">Damage of seal/tampered?</option>
                                </select>
                            </div>
                            <div class="mt-3">
                                <label for="adequacyCheckRemarks" class="form-label fw-bold">Remarks</label>
                                <textarea id="adequacyCheckRemarks" name="adequacy_check_remarks" class="form-control" placeholder="Enter any additional remarks" rows="4"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary d-flex align-items-center"> <i
                                class="feather icon-save me-2"></i>Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
