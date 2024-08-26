<!-- Scribe Attendance and Allotment Modal -->
<div class="modal fade modal-animate anim-blur" id="scribeSelectModal" tabindex="-1"
    aria-labelledby="scribeSelectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-primary" id="scribeSelectModalLabel">
                    <i class="feather icon-users me-2"></i>Select Scribe - Required <span
                        class="text-warning">5</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Scribe Details Section -->
                    <div class="mb-4">
                        <div class="col-lg-12 col-md-11 col-sm-12">
                            <!-- Multi-select dropdown with search functionality -->
                            <select class="form-control" name="choices-multiple-default" id="choices-multiple-default"
                                multiple>
                                <option value="">Select Scribe</option>
                                <option value="1">Amit - 9323248024</option>
                                <option value="2">Bhavani - 9323248024</option>
                                <option value="3">Chandran - 9323248024</option>
                                <option value="4">Divya - 9323248024</option>
                                <option value="5">Elango - 9323248024</option>
                                <option value="6">Farida - 9323248024</option>
                                <option value="7">Gokul - 9323248024</option>
                                <option value="8">Harini - 9323248024</option>
                                <option value="9">Ishaan - 9323248024</option>
                                <option value="10">Jaya - 9323248020</option>
                                <option value="11">Kiran - 9323248021</option>
                                <option value="12">Lalitha - 9323248022</option>
                                <option value="13">Manish - 9323248023</option>
                                <option value="14">Nisha - 9323248014</option>
                                <option value="15">Ravi - 9323248015</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary d-flex align-items-center"
                    onclick="saveScribeDetails()">
                    <i class="feather icon-save me-2"></i>Save Changes
                </button>
            </div>
        </div>
    </div>
</div>
