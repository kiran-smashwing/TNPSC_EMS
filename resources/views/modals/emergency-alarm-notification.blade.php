<div class="modal fade modal-animate anim-blur" data-bs-backdrop="static" id="emergencyAlarmNotificationModal"
    tabindex="-1" aria-labelledby="emergencyAlarmNotificationModalLabel" aria-hidden="true">
    <div class="modal-dialog  ">
        <div class="modal-content">
            <form method="POST" action="{{route('alert-notification.emergency-alert')}}">
                @csrf
                <input type="hidden" name="exam_id" value="{{ $session->currentexam->exam_main_no }}">
                <input type="hidden" name="exam_sess_date" value="{{ $session->exam_sess_date }}">
                <input type="hidden" name="exam_sess_session" value="{{ $session->exam_sess_session }}">
                
                <div class="modal-header ">
                    <h5 class="modal-title text-primary" id="emergencyAlarmNotificationModalLabel">
                        <i class="feather icon-map-pin me-2"></i>Emergency Alarm Notification
                    </h5>

                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="emergencyAlertType" class="form-label">Emergency Alert Type</label>
                                <select class="form-select" name="emergency_alert_type" id="emergencyAlertType" required>
                                    <option value="malpractice">Any malpractice reported?</option>
                                    <option value="attendance_sheets_missing">Attendance sheets missing?</option>
                                    <option value="questions_not_printed_in_order">Questions not printed in order?</option>
                                    <option value="omr_answer_sheet_missing">OMR/Answer sheet missing?</option>
                                    <option value="others">Others?</option>
                                </select>
                            </div>
                            <div class="mt-3">
                                <label for="emergencyAlertRemarks" class="form-label fw-bold">Remarks</label>
                                <textarea id="emergencyAlertRemarks" name="emergency_alert_remarks" class="form-control" placeholder="Enter any additional remarks" rows="4"></textarea>
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
