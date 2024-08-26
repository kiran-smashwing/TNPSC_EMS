<!-- Additional Candidate Modal -->
<div class="modal fade modal-animate anim-blur" id="additionalCandidateModal" tabindex="-1" aria-labelledby="additionalCandidateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="additionalCandidateModalLabel">
                    <i class="feather icon-user-plus me-2"></i>Add Additional Candidates
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form to Add Candidates -->
                <form id="additionalCandidateForm">
                    <div class="mb-3">
                        <label for="candidateRegNo" class="form-label">Registration Number:</label>
                        <input type="text" class="form-control" id="candidateRegNo" placeholder="Enter Registration Number">
                    </div>
                    <div class="mb-3">
                        <label for="candidateName" class="form-label">Name:</label>
                        <input type="text" class="form-control" id="candidateName" placeholder="Enter Candidate Name">
                    </div>
                </form>
               
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" onclick="saveCandidateDetails()">
                    <i class="feather icon-save me-2"></i>Add Candidate
                </button>
            </div>
        </div>
    </div>
</div>
