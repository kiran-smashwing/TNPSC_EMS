<!-- Additional Candidate Modal -->
<div class="modal fade modal-animate anim-blur" data-bs-backdrop="static" id="additionalCandidateModal" tabindex="-1"
    aria-labelledby="additionalCandidateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <form id="additionalCandidateForm" method="POST" action="{{route('ci-candidates-log.savecandidates')}}">
                @csrf
                <input type="hidden" name="exam_id" value="{{ $session->currentexam->exam_main_no }}">
                <input type="hidden" name="exam_sess_date" value="{{ $session->exam_sess_date }}">
                <input type="hidden" name="exam_sess_session" value="{{ $session->exam_sess_session }}">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-primary" id="additionalCandidateModalLabel">
                        <i class="feather icon-user-plus me-2"></i>Add Additional Candidates
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="candidateRegNo" class="form-label">Registration Number:</label>
                        <input type="text" class="form-control" name="candidateRegNo[]" id="candidateRegNo" placeholder="Enter Registration Number" required>
                    </div>
                    <div class="mb-3">
                        <label for="candidateName" class="form-label">Name:</label>
                        <input type="text" class="form-control" name="candidateName[]" id="candidateName" placeholder="Enter Candidate Name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success"><i class="feather icon-save me-2"></i>Add Candidate</button>
                </div>
            </form>
            
        </div>
    </div>
</div>
