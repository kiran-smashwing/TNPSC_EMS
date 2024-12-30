<!-- Replacement of Question/Answer Paper Modal -->
<div class="modal fade modal-animate" data-bs-backdrop="static" id="editReplacementModal" tabindex="-1"
    aria-labelledby="editReplacementModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title text-primary" id="editReplacementModal">
                    <i class="feather icon-repeat me-2"></i>Replacement of Question/Answer Paper
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Replacement Form -->
                <form id="paperReplacementForm" action="#" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="exam_id" value="{{ $session->currentexam->exam_main_no }}">
                    <input type="hidden" name="exam_sess_date" value="{{ $session->exam_sess_date }}">
                    <input type="hidden" name="exam_sess_session" value="{{ $session->exam_sess_session }}">

                    <div class="mb-3">
                        <label for="regNo" class="form-label">Registration Number</label>
                        <input type="text" class="form-control" name="registration_number" id="regNo"
                            placeholder="Enter Registration Number" required>
                    </div>

                    <!-- Replacement Type Selection -->
                    <div class="mb-3">
                        <label for="replacementType" class="form-label">Replacement Type</label>
                        <select class="form-select" name="replacement_type" id="replacementType" required>
                            <option value="damaged">Damaged</option>
                            <option value="shortage">Shortage</option>
                        </select>
                    </div>

                    <!-- Damaged Paper Details -->
                    <div id="damagedDetails" class="mb-3" style="display: none;">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="oldPaperNo" class="form-label">Old Paper Number</label>
                                <input type="text" class="form-control" name="old_paper_number" id="oldPaperNo"
                                    placeholder="Enter Old Paper Number">
                            </div>
                            <div class="col-md-6">
                                <label for="newPaperNoDamaged" class="form-label">New Paper Number</label>
                                <input type="text" class="form-control" name="new_paper_number_damaged"
                                    id="newPaperNoDamaged" placeholder="Enter New Paper Number">
                            </div>
                        </div>
                        <div class="mt-3">
                            <input type="hidden" name="cropped_image" id="croppedImageInput">
                            <label for="photoUpload" class="form-label">Upload Photo of Damaged Paper</label>
                            <input type="file" class="form-control" name="photoUpload" id="photoUpload"
                                accept="image/*">
                        </div>
                    </div>

                    <!-- Shortage Paper Details -->
                    <div id="shortageDetails" class="mb-3" style="display: none;">
                        <label for="newPaperNoShortage" class="form-label">New Paper Number</label>
                        <input type="text" class="form-control" name="new_paper_number_shortage"
                            id="newPaperNoShortage" placeholder="Enter New Paper Number">
                    </div>

                    <!-- Common Description for Replacement Reason -->
                    <div class="mb-3">
                        <label for="replacementReason" class="form-label">Reason for Replacement</label>
                        <textarea class="form-control" name="replacement_reason" id="replacementReason" rows="3"
                            placeholder="Enter Replacement Reason" required></textarea>
                    </div>

                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="feather icon-save me-2"></i>Save Replacement Details
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get reference to elements
            const replacementType = document.getElementById('replacementType');
            const damagedDetails = document.getElementById('damagedDetails');
            const shortageDetails = document.getElementById('shortageDetails');

            // Get current replacement type from the server (use old value if set)
            const currentReplacementType = '{{ old('replacement_type', $session->replacement_type) }}';

            // Log the currentReplacementType to help debug
            console.log('Current Replacement Type:', currentReplacementType);

            // Check the existing replacement type (this is important for editing)
            if (currentReplacementType === 'damaged') {
                console.log('Displaying Damaged Details'); // Debug message
                damagedDetails.style.display = 'block';
                shortageDetails.style.display = 'none';
            } else if (currentReplacementType === 'shortage') {
                console.log('Displaying Shortage Details'); // Debug message
                damagedDetails.style.display = 'none';
                shortageDetails.style.display = 'block';
            } else {
                console.log('No matching replacement type, defaulting to damaged'); // Debug message
                damagedDetails.style.display = 'none';
                shortageDetails.style.display = 'none';
            }

            // Add the event listener for replacement type change to show or hide the respective details
            replacementType.addEventListener('change', function() {
                console.log('Replacement Type Changed:', this.value); // Debug message
                if (this.value === 'damaged') {
                    console.log('Displaying Damaged Details'); // Debug message
                    damagedDetails.style.display = 'block';
                    shortageDetails.style.display = 'none';
                } else if (this.value === 'shortage') {
                    console.log('Displaying Shortage Details'); // Debug message
                    damagedDetails.style.display = 'none';
                    shortageDetails.style.display = 'block';
                }
            });

            // Trigger the change event on modal open to ensure the correct section is visible
            replacementType.dispatchEvent(new Event('change'));
        });
    </script>

    <script>
        // Handle photo upload and convert it to a base64 string
        document.getElementById('photoUpload').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                console.log('File selected:', file.name); // Debug message
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Replace the input value with the Base64 string
                    document.querySelector('input[name="cropped_image"]').value = e.target.result;
                    console.log('Base64 string for the file:', e.target.result); // Debug message
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
@endpush
