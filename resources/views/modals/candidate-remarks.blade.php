<!-- Candidate Remarks Modal -->
<div class="modal fade modal-animate" id="candidateRemarksModal" tabindex="-1" aria-labelledby="candidateRemarksModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title text-primary" id="candidateRemarksModalLabel">
                    <i class="feather icon-edit me-2"></i>Candidate Remarks
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form for Remarks -->
                <form id="candidateRemarksForm">
                    <!-- Registration Number -->
                    <div class="mb-3">
                        <label for="regNo" class="form-label">Registration Number:</label>
                        <input type="text" class="form-control" id="regNo" placeholder="Enter Registration Number">
                    </div>

                    <!-- Remarks Options -->
                    <div class="mb-3">
                        <label for="remarks" class="form-label">Remarks:</label>
                        <select class="form-select" id="remarks">
                            <option selected disabled>Select a remark</option>
                            <option value="malpractice">Indulged in Malpractice</option>
                            <option value="wrongly_seated">Wrongly Seated</option>
                            <option value="used_other_omr">Used OMR of Another Candidate</option>
                            <option value="left_exam">Left Exam During Examination</option>
                            <option value="used_scribe">Used Scribe Assistance</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" onclick="submitRemarks()">
                    <i class="feather icon-save me-2"></i>Save Remarks
                </button>
            </div>
        </div>
    </div>
</div>