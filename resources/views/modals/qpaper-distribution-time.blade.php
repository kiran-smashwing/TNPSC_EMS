<!-- Question Paper Box Scan Modal -->
<div class="modal fade modal-animate anim-blur" id="qpaperdistributiontime" tabindex="-1" aria-labelledby="qpaperdistributiontime" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title text-primary" id="qpaperdistributiontimeLabel">
                    <i class="feather icon-box me-2"></i>Q-paper distribution time
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <div class="col-lg-12"><input type="time" class="form-control" name=" time" id="time" required=""> 
                    </div>
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
