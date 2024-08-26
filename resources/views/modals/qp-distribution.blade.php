<!-- Question Paper Distribution Time Modal -->
<div class="modal fade modal-animate anim-blur" id="distributionTimeModal" tabindex="-1" aria-labelledby="distributionTimeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title text-white" id="distributionTimeModalLabel">
                    <i class="feather icon-clock me-2"></i>Question Paper Distribution Time
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="distributionTimeForm">
                    <div class="mb-3">
                        <label for="distributionTime" class="form-label">Distribution Time</label>
                        <input type="time" class="form-control" id="distributionTime" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-warning" onclick="saveDistributionTime()">
                    <i class="feather icon-save me-2"></i>Save Time
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<!-- Responsive Styles for Mobile -->
<style>
   
    .modal-body {
        padding: 1rem; /* Slightly increase padding for better readability */
    }
    #distributionTime {
        font-size: 1rem; /* Ensure the time input is easy to read */
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
<!-- JavaScript for Saving Distribution Time -->
<script>
    function saveDistributionTime() {
        const distributionTime = document.getElementById('distributionTime').value;
        if (distributionTime) {
            // Implement save logic here
            alert('Distribution time saved successfully: ' + distributionTime);
        } else {
            alert('Please enter a valid time.');
        }
    }
</script>
@endpush
