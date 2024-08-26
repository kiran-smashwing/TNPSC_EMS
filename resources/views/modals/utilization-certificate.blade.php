<!-- Utilization Certificate Input Modal -->
<div class="modal fade modal-animate" id="utilizationCertificateModal" tabindex="-1" aria-labelledby="utilizationCertificateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="utilizationCertificateModalLabel">
                    <i class="feather icon-file-text me-2"></i>Utilization Certificate Entry
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form to Enter Utilization Details -->
                <form id="utilizationCertificateForm">
                    <!-- CI.I Amount Input -->
                    <div class="mb-3">
                        <label for="ciAmount" class="form-label">Cheif Invigilator,Invigilator,Assisting staff:</label>
                        <input type="number" class="form-control" id="ciAmount" placeholder="Enter CI.I amount">
                    </div>

                    <!-- Assistant Staff Amount Input -->
                    <div class="mb-3">
                        <label for="assistantStaffAmount" class="form-label">Sweeper,Sanitary Worker,Waterman:</label>
                        <input type="number" class="form-control" id="assistantStaffAmount" placeholder="Enter Assistant Staff amount">
                    </div>

                    <!-- Police Amount Input -->
                    <div class="mb-3">
                        <label for="policeAmount" class="form-label">Police Person :</label>
                        <input type="number" class="form-control" id="policeAmount" placeholder="Enter Police amount">
                    </div>

                    <!-- Scribe Amount Input -->
                    <div class="mb-3">
                        <label for="scribeAmount" class="form-label">Scribe(s) (if any):</label>
                        <input type="number" class="form-control" id="scribeAmount" placeholder="Enter Scribe amount">
                    </div>

                    <!-- Inspection Staff Amount Input -->
                    <div class="mb-3">
                        <label for="inspectionStaffAmount" class="form-label">Inspection Staff deputed by DRO/District Collector:</label>
                        <input type="number" class="form-control" id="inspectionStaffAmount" placeholder="Enter Inspection Staff amount">
                    </div>

                    <!-- Stationery Amount Input -->
                    <div class="mb-3">
                        <label for="stationeryAmount" class="form-label">Stationery:</label>
                        <input type="number" class="form-control" id="stationeryAmount" placeholder="Enter Stationery amount">
                    </div>

                    <!-- Hall Rent Amount Input -->
                    <div class="mb-3">
                        <label for="hallRentAmount" class="form-label">Hall Rent:</label>
                        <input type="number" class="form-control" id="hallRentAmount" placeholder="Enter Hall Rent amount">
                    </div>

                    <!-- Total Amount Spent Input -->
                    <div class="mb-3">
                        <label for="totalAmountSpent" class="form-label">Total Amount Spent:</label>
                        <input type="number" class="form-control" id="totalAmountSpent" placeholder="Enter Total amount spent">
                    </div>

                    <!-- Amount Received Input -->
                    <div class="mb-3">
                        <label for="amountReceived" class="form-label">Total Amount Received:</label>
                        <input type="number" class="form-control" id="amountReceived" placeholder="Enter Amount received">
                    </div>

                    <!-- Balance Amount Input -->
                    <div class="mb-3">
                        <label for="balanceAmount" class="form-label">Balance Amount:</label>
                        <input type="number" class="form-control" id="balanceAmount" placeholder="Enter Balance amount">
                    </div>
                </form>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary d-flex align-items-center" onclick="saveVenueDetails()">
                    <i class="feather icon-save me-2"></i>Save Changes
                </button>
            </div>
        </div>
    </div>
</div>
