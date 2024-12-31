<!-- OMR Remarks Input Modal -->
<div class="modal fade modal-animate" data-bs-backdrop="static" id="omrRemarksInputModal" tabindex="-1"
    aria-labelledby="omrRemarksInputModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title text-primary" id="omrRemarksInputModalLabel">
                    <i class="feather icon-edit me-2"></i>OMR Remarks Entry
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form to Enter OMR Remarks -->
                <form id="omrRemarksForm" method="POST" action="{{ route('ci-candidates-omrremarks.saveomrremarks') }}">
                    <input type="hidden" name="exam_id" value="{{ $session->currentexam->exam_main_no }}">
                    <input type="hidden" name="exam_sess_date" value="{{ $session->exam_sess_date }}">
                    <input type="hidden" name="exam_sess_session" value="{{ $session->exam_sess_session }}">
                    @csrf
                    <div class="mb-3">
                        <label for="regNo" class="form-label">Registration Number:</label>
                        <input type="text" class="form-control" id="regNo" name="candidateRegNo[]"
                            placeholder="Enter Registration Number">
                    </div>
                    <div class="mb-3">
                        <label for="omrRemark" class="form-label">Remark:</label>
                        <select class="form-select" id="omrRemark" name="candidateRemarks[]">
                            <option value="">Select Remark</option>
                            <option value="Used Non-Personalized OMR">Used Non-Personalized OMR</option>
                            <option value="Returned Blank OMR Sheet">Returned Blank OMR Sheet</option>
                            <option value="Used Pencil in OMR Sheet">Used Pencil in OMR Sheet</option>
                            <option value="Used Other Than Black Ballpoint Pen">Used Other Than Black Ballpoint Pen</option>
                        </select>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Save Remark</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
