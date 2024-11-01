<!-- Invigilator Attendance and Allotment Modal -->
<div class="modal fade modal-animate anim-blur" data-bs-backdrop="static" id="invigilatorAllotmentModel" tabindex="-1"
    aria-labelledby="invigilatorAllotmentModelLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-primary" id="invigilatorAllotmentModelLabel">
                    <i class="feather icon-users me-2"></i>Invigilator Allotment
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form to Assign Invigilators -->
                <form id="invigilatorAllotmentForm">
                    <div class="row">
                        <!-- Iterate through halls and invigilators -->
                        <div class="col-12">
                            <!-- Each Hall Allocation Block -->
                            <div class="mb-4">
                                <h6 class="text-primary mb-2">Hall Allocation</h6>
                                <!-- Card for each hall -->
                                <div class="card mb-2">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4 mb-2">
                                                <label for="hall001" class="form-label">Hall No:</label>
                                                <input type="text" class="form-control" id="hall001" value="001" readonly>
                                            </div>
                                            <div class="col-md-8 mb-2">
                                                <label for="invigilator001" class="form-label">Invigilator:</label>
                                                <input type="text" class="form-control" id="invigilator001" value="Arun - 951234567" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Repeat similar card blocks for more halls -->
                                <div class="card mb-2">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4 mb-2">
                                                <label for="hall002" class="form-label">Hall No:</label>
                                                <input type="text" class="form-control" id="hall002" value="002" readonly>
                                            </div>
                                            <div class="col-md-8 mb-2">
                                                <label for="invigilator002" class="form-label">Invigilator:</label>
                                                <input type="text" class="form-control" id="invigilator002" value="Bala - 951234567" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Add more cards as needed -->
                                <div class="card mb-2">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4 mb-2">
                                                <label for="hall003" class="form-label">Hall No:</label>
                                                <input type="text" class="form-control" id="hall003" value="003" readonly>
                                            </div>
                                            <div class="col-md-8 mb-2">
                                                <label for="invigilator003" class="form-label">Invigilator:</label>
                                                <input type="text" class="form-control" id="invigilator003" value="Chitra - 951234567" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary d-flex align-items-center"
                    onclick="saveInvigilatorDetails()">
                    <i class="feather icon-save me-2"></i>Save Changes
                </button>
            </div>
        </div>
    </div>
</div>
