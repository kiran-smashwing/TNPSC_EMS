<!-- Additional Candidate Modal -->
<div class="modal fade modal-animate anim-blur" data-bs-backdrop="static" id="attendanceCandidateModal" tabindex="-1"
    aria-labelledby="attendanceCandidateModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <form id="attendanceCandidateModalForm" method="POST" action="{{ route('candidate.attendance.save') }}">
                @csrf
                <input type="hidden" name="exam_id" value="{{ $session->currentexam->exam_main_no }}">
                <input type="hidden" name="exam_sess_date" value="{{ $session->exam_sess_date }}">
                <input type="hidden" name="exam_sess_session" value="{{ $session->exam_sess_session }}">
                <input type="hidden" name="alloted_count"
                    value="{{ $session_confirmedhalls ? $session_confirmedhalls->alloted_count : 0 }}">


                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-primary" id="attendanceCandidateModal">
                        <i class="feather icon-user-plus me-2"></i>Candidates Allotted -
                        {{ $session_confirmedhalls ? $session_confirmedhalls->alloted_count : 0 }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="present" class="form-label">Present:</label>
                        <input type="number" class="form-control" name="present[]" id="present"
                            placeholder="Enter Present" required min="0"
                            max="{{ $session_confirmedhalls ? $session_confirmedhalls->alloted_count : 0 }}" oninput="validatePresent()">
                    </div>

                    <div class="mb-3">
                        <label for="absent" class="form-label">Absent:</label>
                        <input type="number" class="form-control" name="absent[]" id="absent"
                            placeholder="Calculated Absent" readonly>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="submitBtn" class="btn btn-success" disabled>
                        <i class="feather icon-save me-2"></i>Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            // Define the validatePresent function for the Add page
            function validatePresent() {
                const allotedCount = {{ $session_confirmedhalls ? $session_confirmedhalls->alloted_count : 0 }} ;
                const presentInput = document.getElementById('present');
                const absentInput = document.getElementById('absent');
                const submitButton = document.getElementById('submitBtn');

                let presentCount = parseInt(presentInput.value) || 0; // Default to 0 if empty
                let absentCount = allotedCount - presentCount;

                // Ensure Present does not exceed Allotted count
                if (presentCount > allotedCount) {
                    presentCount = allotedCount;
                    absentCount = 0;
                    presentInput.value = presentCount; // Update the Present field
                }

                // Update the Absent field
                absentInput.value = absentCount >= 0 ? absentCount : 0;

                // Optionally disable the submit button if the values are invalid
                if (presentCount < 0 || presentCount > allotedCount) {
                    submitButton.disabled = true;
                } else {
                    submitButton.disabled = false;
                }
            }

            // Trigger the validation when the modal is shown
            $('#attendanceCandidateModal').on('shown.bs.modal', function() {
                validatePresent(); // Ensure validation is triggered on modal open
            });

            // Trigger the validation when the present input is modified
            $('#present').on('input', function() {
                validatePresent(); // Recalculate when the Present input is changed
            });
        });
    </script>
@endpush
