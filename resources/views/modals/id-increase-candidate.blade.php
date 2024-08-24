<div class="modal fade modal-animate" id="increaseCandiadteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Increase Candidate Count</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
            </div>
            <div class="modal-body">
                <!-- File Upload Section -->
                <!-- Switch with a hidden percentage input -->
                <div class="d-flex align-items-center mb-2">
                    <div class="col-sm-12" id="percentageInput">
                        <div class="mb-3 mt-3">
                            {{-- <label class="form-label" for="percentage">Percentage<span
                                    class="text-danger">*</span></label> --}}
                            <select class="form-control " name="percentage" required>
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
                            </select>
                        </div>
                    </div>

                    {{-- <div class="form-check form-switch custom-switch-v1">
                        <input type="checkbox" class="form-check-input input-success" id="customswitchv2-3">
                        <label class="form-check-label" for="customswitchv2-3">Auto</label>
                    </div> --}}
                    <!-- Percentage input (initially hidden) -->
                    {{-- <div class="col-sm-3"  id="percentageInput" style="display: none">
                        <div class="mb-3 mt-3">
                        
                            <select class="form-control form-control-sm ms-2" name="percentage" required>
                                <option disabled selected>Percentage</option>
                                <option value="5">5%</option>
                                <option value="10">10%</option>
                                <option value="15">15%</option>
                                <option value="20">20%</option>
                                <option value="25">25%</option>
                                <option value="30">30%</option>
                                <option value="40">40%</option>
                                <option value="50">50%</option>
                            </select>
                            </select>
                        </div>
                    </div>
                   --}}
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
                        <b>5. </b>Verify that the new count reflects the intended increase in download CSV .
                    </p>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary shadow-2">Save changes</button>
            </div>
        </div>
    </div>
</div>
