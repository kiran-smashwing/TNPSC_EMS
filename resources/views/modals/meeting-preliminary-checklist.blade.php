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
            <form id="preliminary-checklist-form" method="POST" action="{{ route('ci-meetings.updateAdequacyCheck') }}">
                @csrf
                    <input type="hidden" name="exam_id" value="{{ $session->exam_main_no }}">
                <div class="modal-body">
                    <div class="row">
                        <div class="row g4">
                            <div class="col md-6">
                                <div class="form-check mb-2">
                                    <input class="form-check-input input-primary" type="checkbox"
                                        id="received_appointment_letter" name="received_appointment_letter"
                                        checked="checked">
                                    <label class="form-check-label" for="received_appointment_letter">Received
                                        Appointment Letter</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input input-primary" type="checkbox" id="received_packet"
                                        name="received_packet" checked="checked">
                                    <label class="form-check-label" for="received_packet">Received Packet containing
                                        attendance sheet
                                        -cum-hall-sketch</label>
                                </div>
                                <div class="mb-2">
                                    <label for="received_amount" class="form-label">Received Amount</label>
                                    <input type="text" class="form-control input-primary" id="received_amount"
                                        name="received_amount">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary d-flex align-items-center">
                        <i class="feather icon-save me-2"></i>Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
