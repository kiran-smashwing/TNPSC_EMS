<!-- Invigilator Attendance and Allotment Modal -->
<div class="modal fade modal-animate anim-blur" id="invigilatorSelectModal" tabindex="-1"
    aria-labelledby="invigilatorSelectModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-primary" id="invigilatorSelectModalLabel">
                    <i class="feather icon-users me-2"></i>Select Invigilator - Required <span
                        class="text-warning">15</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="row">
                    <!-- Invigilator Details Section -->
                    <div class="mb-4">
                        <div class="col-lg-12 col-md-11 col-sm-12">
                            <!-- Multi-select dropdown with search functionality -->
                            <select class="form-control" name="choices-multiple-default" id="choices-multiple-default"
                                multiple>
                                <option value="">Select Invigilator</option>
                                <option value="1">Arun - 9323248024</option>
                                <option value="2">Bala - 9323248024</option>
                                <option value="3">Chitra - 9323248024</option>
                                <option value="4">Dinesh - 9323248024</option>
                                <option value="5">Eswari - 9323248024</option>
                                <option value="6">Fathima - 9323248024</option>
                                <option value="7">Ganesh - 9323248024</option>
                                <option value="8">Hari - 9323248024</option>
                                <option value="9">Ishwarya - 9323248024</option>
                                <option value="10">Jeeva - 9323248020</option>
                                <option value="11">Karthik - 9323248021</option>
                                <option value="12">Latha - 9323248022</option>
                                <option value="13">Mani - 9323248023</option>
                                <option value="14">Nandhini - 9323248014</option>
                                <option value="15">Oviya - 9323248015</option>                               
                            </select>
                        </div>
                    </div>

                </div>
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

