<!-- View Multiple Replacement Details Modal -->
<div class="modal fade modal-animate" data-bs-backdrop="static" id="printerToTreasuryModal" tabindex="-1" aria-labelledby="printerToTreasuryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title text-primary" id="printerToTreasuryModalLabel">
                    <i class="feather icon-eye me-2"></i>Printer to District Treasury 
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form to Add Candidates -->
                <form id="additionalCandidateForm">
                    <div class="mb-3">
                        <label for="noofqpbox" class="form-label">No of Question Paper Boxes:</label>
                        <input type="text" class="form-control" id="noofqpbox" placeholder="250">
                    </div>
                    <div class="mb-3">
                        <label for="noofomrpackets" class="form-label">No of OMR Packets:</label>
                        <input type="text" class="form-control" id="noofomrpackets" placeholder="50">
                    </div>
                </form>
               
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveReplacementDetails()">
                    <i class="feather icon-save me-2"></i>Save 
                </button>            </div>
        </div>
    </div>
</div>
