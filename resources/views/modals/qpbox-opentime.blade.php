<!-- Question Paper Box Scan Modal -->
<div class="modal fade modal-animate anim-blur" data-bs-backdrop="static" id="qpboxOpenTimeModal" tabindex="-1" aria-labelledby="qpboxOpenTimeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title text-primary" id="qpboxOpenTimeModalLabel">
                    <i class="feather icon-box me-2"></i>Question Paper Box Scan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <!-- Scanning Status Section -->
                    <div id="scanStatus">
                        <p class="fw-bold">Set the current time:</p>
                        <!-- Show Timestamp Immediately -->
                        <p id="scanTime" class="text-muted" style="font-size: 1.1rem;">Current time: <span id="timeDisplay">Not Set</span></p>
                    </div>
                    <!-- Set Time Button -->
                    <button type="button" class="btn btn-lg btn-primary my-3" id="setTimeButton" onclick="setCurrentTime()">
                        <i class="feather icon-clock me-2"></i>Set Current Time
                    </button>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" onclick="saveScanDetails()">
                    <i class="feather icon-save me-2"></i>Save Time Details
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- JavaScript for Setting the Current Time and Saving Details -->
<script>
    function setCurrentTime() {
        const currentTime = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        document.getElementById('timeDisplay').textContent = currentTime;
    }

    function saveScanDetails() {
        // Implement save logic here
        alert('Time details saved successfully!');
    }
</script>
@endpush
