<!-- Date and Time Selection with QR Code Generation Modal -->
<div class="modal fade modal-animate" id="ciMeetingCodeGenerateModal" tabindex="-1" aria-labelledby="ciMeetingCodeGenerateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-primary" id="ciMeetingCodeGenerateModalLabel">
                    <i class="feather icon-calendar me-2"></i>Generate CI Meeting QR Code
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form to Select Date and Time -->
                <form id="qrCodeForm">
                    <!-- Date Input -->
                    <div class="mb-3">
                        <label for="selectDate" class="form-label">Select Date:</label>
                        <input type="date" class="form-control" id="selectDate">
                    </div>

                    <!-- Time Input -->
                    <div class="mb-3">
                        <label for="selectTime" class="form-label">Select Time:</label>
                        <input type="time" class="form-control" id="selectTime">
                    </div>

                    <!-- QR Code Preview (Static) -->
                    <div class="text-center my-4">
                        <p class="fw-bold">Generated QR Code:</p>
                        <div id="qrCodePreview" style="border: 1px dashed #ced4da; padding: 20px;">
                            <img src="https://via.placeholder.com/150" alt="QR Code Placeholder">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-end">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success">
                    <i class="feather icon-download me-2"></i>Download QR Code
                </button>
            </div>
        </div>
    </div>
</div>
