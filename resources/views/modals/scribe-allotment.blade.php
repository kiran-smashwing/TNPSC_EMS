<!-- Scribe Attendance and Allotment Modal -->
<div class="modal fade modal-animate anim-blur" id="scribeAllotmentModal" tabindex="-1"
    aria-labelledby="scribeAllotmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-primary" id="scribeAllotmentModalLabel">
                    <i class="feather icon-users me-2"></i>Scribe Allotment
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form to Assign Scribes -->
                <form id="scribeAllotmentForm">
                    <div class="row">
                        <!-- Iterate through registration numbers and scribes -->
                        <div class="col-12">
                            <!-- Each Scribe Allocation Block -->
                            <div class="mb-4">
                                <h6 class="text-primary mb-2">Scribe Allocation</h6>
                                <!-- Card for each registration number -->
                                <div class="card mb-2">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4 mb-2">
                                                <label for="reg001" class="form-label">Reg No:</label>
                                                <input type="text" class="form-control" id="reg001" value="TNPSC001" readonly>
                                            </div>
                                            <div class="col-md-8 mb-2">
                                                <label for="scribe001" class="form-label">Scribe:</label>
                                                <input type="text" class="form-control" id="scribe001" value="Amit - 951234567" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Repeat similar card blocks for more registration numbers -->
                                <div class="card mb-2">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4 mb-2">
                                                <label for="reg002" class="form-label">Reg No:</label>
                                                <input type="text" class="form-control" id="reg002" value="TNPSC002" readonly>
                                            </div>
                                            <div class="col-md-8 mb-2">
                                                <label for="scribe002" class="form-label">Scribe:</label>
                                                <input type="text" class="form-control" id="scribe002" value="Bhavani - 951234567" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Add more cards as needed -->
                                <div class="card mb-2">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4 mb-2">
                                                <label for="reg003" class="form-label">Reg No:</label>
                                                <input type="text" class="form-control" id="reg003" value="TNPSC003" readonly>
                                            </div>
                                            <div class="col-md-8 mb-2">
                                                <label for="scribe003" class="form-label">Scribe:</label>
                                                <input type="text" class="form-control" id="scribe003" value="Chandran - 951234567" readonly>
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
