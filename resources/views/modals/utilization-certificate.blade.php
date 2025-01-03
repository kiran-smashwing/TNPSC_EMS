<!-- Utilization Certificate Input Modal -->
<div class="modal fade modal-animate" data-bs-backdrop="static" id="utilizationCertificateModal" tabindex="-1"
    aria-labelledby="utilizationCertificateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-primary" id="utilizationCertificateModalLabel">
                    <i class="feather icon-file-text me-2"></i>Utilization Certificate Entry
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form to Enter Utilization Details -->
                <form id="utilizationCertificateForm" action="{{ route('saveUtilizationCertificate') }}" method="POST">
                    <input type="hidden" name="exam_id" value="{{ $session->exam_main_no }}">

                    @csrf <!-- Add CSRF token for security -->

                    <!-- CI.I Amount Input -->
                    <div class="mb-3">
                        <label for="ciAmount" class="form-label">Cheif Invigilator, Invigilator, Assisting
                            staff:</label>
                        <input type="number" class="form-control calculate-spent" name="ciAmount" id="ciAmount"
                            placeholder="Enter CI.I amount" value="0" required>
                    </div>

                    <!-- Assistant Staff Amount Input -->
                    <div class="mb-3">
                        <label for="assistantStaffAmount" class="form-label">Sweeper, Sanitary Worker, Waterman:</label>
                        <input type="number" class="form-control calculate-spent" name="assistantStaffAmount"
                            id="assistantStaffAmount" value="0" placeholder="Enter Assistant Staff amount"
                            required>
                    </div>

                    <!-- Police Amount Input -->
                    <div class="mb-3">
                        <label for="policeAmount" class="form-label">Police Person:</label>
                        <input type="number" class="form-control calculate-spent" value="0" name="policeAmount"
                            id="policeAmount" placeholder="Enter Police amount" required>
                    </div>

                    <!-- Scribe Amount Input -->
                    <div class="mb-3">
                        <label for="scribeAmount" class="form-label">Scribe(s) (if any):</label>
                        <input type="number" class="form-control calculate-spent" name="scribeAmount" value="0"
                            id="scribeAmount" placeholder="Enter Scribe amount">
                    </div>

                    <!-- Inspection Staff Amount Input -->
                    <div class="mb-3">
                        <label for="inspectionStaffAmount" class="form-label">Inspection Staff deputed by DRO/District
                            Collector:</label>
                        <input type="number" class="form-control calculate-spent" name="inspectionStaffAmount"
                            id="inspectionStaffAmount" value="0" placeholder="Enter Inspection Staff amount"
                            required>
                    </div>

                    <!-- Stationery Amount Input -->
                    <div class="mb-3">
                        <label for="stationeryAmount" class="form-label">Stationery:</label>
                        <input type="number" class="form-control calculate-spent" name="stationeryAmount"
                            id="stationeryAmount" value="0" placeholder="Enter Stationery amount" required>
                    </div>

                    <!-- Hall Rent Amount Input -->
                    <div class="mb-3">
                        <label for="hallRentAmount" class="form-label">Hall Rent:</label>
                        <input type="number" class="form-control calculate-spent" name="hallRentAmount"
                            id="hallRentAmount" value="0" placeholder="Enter Hall Rent amount" required>
                    </div>

                    <!-- Total Amount Spent Input -->
                    <div class="mb-3">
                        <label for="totalAmountSpent" class="form-label">Total Amount Spent:</label>
                        <input type="number" class="form-control" name="totalAmountSpent" id="totalAmountSpent"
                            placeholder="Total amount spent" value="0" readonly>
                    </div>

                    <!-- Amount Received Input -->
                    <div class="mb-3">
                        <label for="amountReceived" class="form-label">Total Amount Received:</label>
                        <input type="number" class="form-control" name="amountReceived" id="amountReceived"
                            value="{{ old('amountReceived', $firstReceivedAmount) }}" readonly>
                    </div>

                    <!-- Balance Amount Input -->
                    <div class="mb-3">
                        <label for="balanceAmount" class="form-label">Balance Amount:</label>
                        <input type="number" class="form-control" name="balanceAmount" id="balanceAmount"
                            placeholder="Balance amount" value="0" readonly>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary d-flex align-items-center">
                    <i class="feather icon-save me-2"></i>Save Changes
                </button>
            </div>
            </form>
        </div>
    </div>
</div>
@push('scripts')
    <!-- JavaScript for Calculation -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calculateFields = document.querySelectorAll('.calculate-spent');
            const totalAmountSpent = document.getElementById('totalAmountSpent');
            const amountReceived = document.getElementById('amountReceived');
            const balanceAmount = document.getElementById('balanceAmount');

            // Function to make amountReceived readonly if it already has a value
            function checkAmountReceived() {
                if (parseFloat(amountReceived.value) > 0) {
                    amountReceived.setAttribute('readonly', 'true');
                } else {
                    amountReceived.removeAttribute('readonly');
                }
            }

            function updateAmounts() {
                let totalSpent = 0;

                // Calculate total spent from all input fields
                calculateFields.forEach(field => {
                    const value = parseFloat(field.value) || 0;
                    totalSpent += value;
                });

                // Update total amount spent
                totalAmountSpent.value = totalSpent.toFixed(2); // Format to 2 decimal places if needed

                // Get the amount received
                const received = parseFloat(amountReceived.value) || 0;

                // Validate and calculate balance
                if (totalSpent > received) {
                    alert('The total amount spent cannot exceed the total amount received!');
                    balanceAmount.value = '0.00'; // Reset balance to 0
                    totalAmountSpent.value = received.toFixed(2); // Limit total spent to received amount
                } else {
                    balanceAmount.value = (received - totalSpent).toFixed(2);
                }
            }

            // Add event listeners to all calculate fields
            calculateFields.forEach(field => {
                field.addEventListener('input', updateAmounts);
            });

            // Ensure "Total Amount Received" has a default or server-side value
            if (!amountReceived.value || isNaN(parseFloat(amountReceived.value))) {
                amountReceived.value = "0.00"; // Default to 0.00 if no value is present
            }

            // Check if amountReceived should be readonly based on its value
            checkAmountReceived();

            // Initial calculation on page load
            updateAmounts();
        });
    </script>
@endpush
