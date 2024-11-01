<!-- CI Assistant Attendance and Allotment Modal -->
<div class="modal fade modal-animate anim-blur" data-bs-backdrop="static" id="ciAssistantAllotmentModal" tabindex="-1"
    aria-labelledby="ciAssistantAllotmentModelLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-primary" id="ciAssistantAllotmentModelLabel">
                    <i class="feather icon-users me-2"></i>CI Assistant Allotment
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form to Assign CI Assistants -->
                <form id="ciAssistantAllotmentForm">
                    <div class="row">
                        <!-- Iterate through halls and CI Assistants -->
                        <div class="col-12">
                            <!-- Each Hall Allocation Block -->
                            <div class="mb-4">
                                <h6 class="text-primary mb-2">CI Assistants</h6>
                                <!-- Card for each hall -->
                                <div class="card mb-2">
                                    <div class="card-body">
                                        <div class="row">
                                           
                                            <div class="col-md-12 mb-2">
                                                <label for="ciAssistant001" class="form-label">CI Assistant:</label>
                                                <input type="text" class="form-control" id="ciAssistant001" value="Arun - 951234567" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Repeat similar card blocks for more halls -->
                                <div class="card mb-2">
                                    <div class="card-body">
                                        <div class="row">
                                        
                                            <div class="col-md-12 mb-2">
                                                <label for="ciAssistant002" class="form-label">CI Assistant:</label>
                                                <input type="text" class="form-control" id="ciAssistant002" value="Bala - 951234567" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Add more cards as needed -->
                            

                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary d-flex align-items-center"
                    onclick="saveCIAssistantDetails()">
                    <i class="feather icon-save me-2"></i>Save Changes
                </button>
            </div>
        </div>
    </div>
</div>
