<!-- Edit Candidate Modal -->
<div class="modal fade modal-animate anim-blur" data-bs-backdrop="static" id="attendanceCandidateeditModal" tabindex="-1"
    aria-labelledby="attendanceCandidateeditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <form id="attendanceCandidateEditForm" method="POST" action="#">
                @csrf
                <input type="hidden" name="exam_id" value="{{ $session->currentexam->exam_main_no }}">
                <input type="hidden" name="exam_sess_date" value="{{ $session->exam_sess_date }}">
                <input type="hidden" name="exam_sess_session" value="{{ $session->exam_sess_session }}">
                <input type="hidden" name="alloted_count" value="{{ $session_confirmedhalls->alloted_count }}">

                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-primary" id="attendanceCandidateeditModalLabel">
                        <i class="feather icon-user-plus me-2"></i>Edit Candidates Allotted -  {{ $session_confirmedhalls->alloted_count }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="presentEdit" class="form-label">Present:</label>
                        <input type="number" class="form-control" name="present[]" id="presentEdit"
                            placeholder="Enter Present" required min="0"
                            max="{{ $session_confirmedhalls->alloted_count }}" oninput="validateEditPresent()"
                            value="{{ $candidate_attendance_data['present'] ?? '' }}">
                    </div>

                    <div class="mb-3">
                        <label for="absentEdit" class="form-label">Absent:</label>
                        <input type="number" class="form-control" name="absent[]" id="absentEdit" placeholder="Absent"
                            readonly value="{{ $candidate_attendance_data['absent'] ?? '' }}">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="submitEditBtn" class="btn btn-success"><i
                            class="feather icon-save me-2"></i>Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            // Edit Page Specific Logic
            function validateEditPresent() {
                const allotedCount = {{ $session_confirmedhalls->alloted_count }};
                const presentInput = $('#presentEdit');
                const absentInput = $('#absentEdit');
                let presentCount = parseInt(presentInput.val()) || 0;

                // Ensure Present does not exceed Allotted count
                if (presentCount > allotedCount) {
                    presentCount = allotedCount;
                }

                // Calculate Absent count
                const absentCount = allotedCount - presentCount;

                presentInput.val(presentCount);
                absentInput.val(absentCount >= 0 ? absentCount : 0);
            }

            // Trigger the validation when the modal is shown
            $('#attendanceCandidateeditModal').on('shown.bs.modal', function() {
                validateEditPresent(); // Validate when Edit Modal is shown
            });

            // Trigger validation on input change
            $('#presentEdit').on('input', function() {
                validateEditPresent();
            });
        });
    </script>
@endpush
