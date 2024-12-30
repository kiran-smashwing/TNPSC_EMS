<!-- Question Paper Box Scan Modal -->
<div class="modal fade modal-animate anim-blur" data-bs-backdrop="static" id="qpaperdistributiontime" tabindex="-1"
    aria-labelledby="qpaperdistributiontime" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <form id="qpTimeForm" method="POST" action="{{ route('qp-box-distribution.save-time') }}">
                @csrf
                <input type="hidden" name="exam_id" value="{{ $session->currentexam->exam_main_no }}">
                <input type="hidden" name="exam_sess_date" value="{{ $session->exam_sess_date }}">
                <input type="hidden" name="exam_sess_session" value="{{ $session->exam_sess_session }}">
                <div class="modal-header bg-success">
                    <h5 class="modal-title text-primary" id="qpaperdistributiontimeLabel">
                        <i class="feather icon-box me-2"></i>Q-paper distribution time
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <!-- Scanning Status Section -->
                        <div id="scanStatus">
                            <p class="fw-bold">Current time:</p>
                            <!-- Show Timestamp Immediately -->
                            <p id="scanTime" class="text-muted" style="font-size: 1.1rem;">Time: <span
                                    id="timeDisplayss">Not
                                    Set</span></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">
                        <i class="feather icon-save me-2"></i>Save Time Details
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <!-- JavaScript for Setting the Current Time and Saving Details -->
    <script>
        function setCurrentTimes() {
            const currentTime = new Date().toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit'
            });
            document.getElementById('timeDisplays').textContent = currentTime;
        }

        function saveScanDetails() {
            // Implement save logic here
            alert('Time details saved successfully!');
        }
    </script>
@endpush
