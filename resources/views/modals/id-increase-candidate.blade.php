<div class="modal fade modal-animate" data-bs-backdrop="static" id="increaseCandiadteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <form action="{{ route('id-candidates.update-percentage') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="exam_id" value="{{ $session->exam_main_no }}">
                <div class="modal-header">
                    <h5 class="modal-title">Increase Candidate Count</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                </div>
                <div class="modal-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="col-sm-12" id="percentageInput">
                            <div class="mb-3 mt-3">
                                <select class="form-control" name="increment_percentage" required>
                                    <option disabled selected>Select Percentage</option>
                                    <option value="5">5%</option>
                                    <option value="10">10%</option>
                                    <option value="15">15%</option>
                                    <option value="20">20%</option>
                                    <option value="25">25%</option>
                                    <option value="30">30%</option>
                                    <option value="40">40%</option>
                                    <option value="50">50%</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- Information Section -->
                    <div class="mb-3">
                        <p class="mb-0"><b>1. </b>Click on the dropdown to reveal the available percentage options (e.g.,
                            5%, 10%, 15%, etc.)</p>
                        <p class="mb-0">
                            <b>2. </b>Select a percentage that reflects the necessary adjustment based on your
                            requirements.
                        </p>
                        <p class="mb-0">
                            <b>3. </b>After selecting the percentage, click the “Save” button to execute the increase in
                            candidate count.
                        </p>
                        <p class="mb-0">
                            <b>4. </b>The system will automatically calculate the new candidate count by increasing the
                            current count by the selected percentage
                        </p>
                        <p class="mb-0">
                            <b>5. </b>Verify that the new count reflects the intended increase in download CSV.
                        </p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary shadow-2">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>