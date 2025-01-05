<!-- Candidate Remarks Modal -->
<div class="modal fade modal-animate" data-bs-backdrop="static" id="candidateRemarksModal" tabindex="-1"
    aria-labelledby="candidateRemarksModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="paperReplacementForm" action="{{ route('ci-candidates-remark.saveremarks') }}" method="POST">
                @csrf
                <input type="hidden" name="exam_id" value="{{ $session->currentexam->exam_main_no }}">
                <input type="hidden" name="exam_sess_date" value="{{ $session->exam_sess_date }}">
                <input type="hidden" name="exam_sess_session" value="{{ $session->exam_sess_session }}">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title text-primary" id="candidateRemarksModalLabel">
                        <i class="feather icon-edit me-2"></i>Candidate Remarks
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form for Remarks -->
                    <!-- Registration Number -->
                    <div class="mb-3">
                        <label for="regNo" class="form-label">Registration Number:</label>
                        <input type="text" class="form-control" id="regNo" name="candidateRegNo[]"
                            placeholder="Enter Registration Number">
                    </div>

                    <!-- Remarks Options -->
                    <div class="mb-3">
                        <label for="remarks" class="form-label">Remarks:</label>
                        <select class="form-select" name="candidateRemarks[]" id="remarks">
                            <option selected disabled>Select a remark</option>
                            <option value="Indulged in Malpractice">Indulged in Malpractice</option>
                            <option value="Wrongly Seated">Wrongly Seated</option>
                            <option value="Used OMR of Another Candidate">Used OMR of Another Candidate</option>
                            <option value="Left Exam During Examination">Left Exam During Examination</option>
                        </select>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="feather icon-save me-2"></i>Save Remarks
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
