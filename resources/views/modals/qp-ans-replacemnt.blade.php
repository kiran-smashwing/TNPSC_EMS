<!-- Replacement of Question Paper or Answer Sheet Modal -->
<div class="modal fade modal-animate anim-blur" id="replacementModal" tabindex="-1" aria-labelledby="replacementModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white" id="replacementModalLabel">
                    <i class="feather icon-refresh-cw me-2"></i>Replacement of Question Paper or Answer Sheet
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="replacementForm">
                    <div class="mb-3">
                        <label for="registrationNumber" class="form-label">Registration Number</label>
                        <input type="text" class="form-control" id="registrationNumber" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="replacementType" class="form-label">Type of Replacement</label>
                        <select class="form-select" id="replacementType" required>
                            <option value="">Select Replacement Type</option>
                            <option value="damage">Damage</option>
                            <option value="shortage">Shortage</option>
                        </select>
                    </div>

                    <div id="damageFields" class="d-none">
                        <div class="mb-3">
                            <label for="oldNumber" class="form-label">Old Number</label>
                            <input type="text" class="form-control" id="oldNumber">
                        </div>
                        <div class="mb-3">
                            <label for="newNumber" class="form-label">New Number</label>
                            <input type="text" class="form-control" id="newNumber" required>
                        </div>
                        <div class="mb-3">
                            <label for="damagePhoto" class="form-label">Photo of Damage</label>
                            <input type="file" class="form-control" id="damagePhoto" accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label for="damageDescription" class="form-label">Description of Damage</label>
                            <textarea class="form-control" id="damageDescription" rows="3"></textarea>
                        </div>
                    </div>

                    <div id="shortageFields" class="d-none">
                        <div class="mb-3">
                            <label for="newNumberShortage" class="form-label">New Number</label>
                            <input type="text" class="form-control" id="newNumberShortage" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" onclick="saveReplacementDetails()">
                    <i class="feather icon-save me-2"></i>Save Replacement
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<!-- Responsive Styles for Mobile -->
<style>
    .modal-lg {
        max-width: 95%; /* Ensure modals take up most of the mobile screen width */
    }
    .modal-body {
        padding: 1rem; /* Slightly increase padding for better readability */
    }
    #replacementForm .form-control,
    #replacementForm .form-select {
        font-size: 1rem; /* Ensure input fields are easy to read */
    }
    /* Adjustments for smaller screens */
    @media (max-width: 576px) {
        .modal-header h5 {
            font-size: 1.1rem; /* Reduce title size slightly */
        }
        .btn-lg {
            font-size: 1rem;
            padding: 0.75rem 1rem;
        }
    }
</style>
@endpush

@push('scripts')
<!-- JavaScript for Handling Replacement Form -->
<script>
    document.getElementById('replacementType').addEventListener('change', function() {
        var damageFields = document.getElementById('damageFields');
        var shortageFields = document.getElementById('shortageFields');
        
        if (this.value === 'damage') {
            damageFields.classList.remove('d-none');
            shortageFields.classList.add('d-none');
        } else if (this.value === 'shortage') {
            damageFields.classList.add('d-none');
            shortageFields.classList.remove('d-none');
        } else {
            damageFields.classList.add('d-none');
            shortageFields.classList.add('d-none');
        }
    });

    function saveReplacementDetails() {
        const registrationNumber = document.getElementById('registrationNumber').value;
        const replacementType = document.getElementById('replacementType').value;
        let newNumber = '';
        let oldNumber = '';
        let damagePhoto = '';
        let damageDescription = '';
        
        if (replacementType === 'damage') {
            oldNumber = document.getElementById('oldNumber').value;
            newNumber = document.getElementById('newNumber').value;
            damagePhoto = document.getElementById('damagePhoto').files[0];
            damageDescription = document.getElementById('damageDescription').value;
        } else if (replacementType === 'shortage') {
            newNumber = document.getElementById('newNumberShortage').value;
        }

        // Implement save logic here
        if (registrationNumber && newNumber) {
            alert('Replacement details saved successfully:\n' +
                  'Registration Number: ' + registrationNumber + '\n' +
                  'Replacement Type: ' + replacementType + '\n' +
                  'Old Number: ' + oldNumber + '\n' +
                  'New Number: ' + newNumber + '\n' +
                  'Damage Photo: ' + (damagePhoto ? damagePhoto.name : 'N/A') + '\n' +
                  'Damage Description: ' + damageDescription);
        } else {
            alert('Please fill in all required fields.');
        }
    }
</script>
@endpush
