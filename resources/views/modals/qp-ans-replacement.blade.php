<!-- Replacement of Question/Answer Paper Modal -->
<div class="modal fade modal-animate" data-bs-backdrop="static" id="paperReplacementModal" tabindex="-1" aria-labelledby="paperReplacementModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title text-primary" id="paperReplacementModalLabel">
                    <i class="feather icon-repeat me-2"></i>Replacement of Question/Answer Paper
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Replacement Form -->
                <form id="paperReplacementForm">
                    <div class="mb-3">
                        <label for="regNo" class="form-label">Registration Number</label>
                        <input type="text" class="form-control" id="regNo" placeholder="Enter Registration Number">
                    </div>
                    
                    <!-- Replacement Type Selection -->
                    <div class="mb-3">
                        <label for="replacementType" class="form-label">Replacement Type</label>
                        <select class="form-select" id="replacementType">
                            <option value="damaged">Damaged</option>
                            <option value="shortage">Shortage</option>
                        </select>
                    </div>

                    <!-- Damaged Paper Details (Shown only if "damaged" is selected) -->
                    <div id="damagedDetails" class="mb-3" >
                        <div class="row">
                            <div class="col-md-6">
                                <label for="oldPaperNo" class="form-label">Old Paper Number</label>
                                <input type="text" class="form-control" id="oldPaperNo" placeholder="Enter Old Paper Number">
                            </div>
                            <div class="col-md-6">
                                <label for="newPaperNoDamaged" class="form-label">New Paper Number</label>
                                <input type="text" class="form-control" id="newPaperNoDamaged" placeholder="Enter New Paper Number">
                            </div>
                        </div>
                    </div>

                    <!-- Shortage Paper Details (Shown only if "shortage" is selected) -->
                    <div id="shortageDetails" class="mb-3" style="display: none;">
                        <label for="newPaperNoShortage" class="form-label">New Paper Number</label>
                        <input type="text" class="form-control" id="newPaperNoShortage" placeholder="Enter New Paper Number">
                    </div>

                    <!-- Common Description for Replacement Reason -->
                    <div class="mb-3">
                        <label for="replacementReason" class="form-label">Reason for Replacement</label>
                        <textarea class="form-control" id="replacementReason" rows="3" placeholder="Enter Replacement Reason"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveReplacementDetails()">
                    <i class="feather icon-save me-2"></i>Save Replacement Details
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Show/hide relevant sections based on selected replacement type
    document.getElementById('replacementType').addEventListener('change', function() {
        const type = this.value;
        document.getElementById('damagedDetails').style.display = (type === 'damaged') ? 'block' : 'none';
        document.getElementById('shortageDetails').style.display = (type === 'shortage') ? 'block' : 'none';
    });
</script>
