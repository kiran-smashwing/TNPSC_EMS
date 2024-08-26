<!-- OMR Remarks Input Modal -->
<div class="modal fade modal-animate" id="omrRemarksInputModal" tabindex="-1" aria-labelledby="omrRemarksInputModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title text-primary" id="omrRemarksInputModalLabel">
                    <i class="feather icon-edit me-2"></i>OMR Remarks Entry
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form to Enter OMR Remarks -->
                <form id="omrRemarksForm">
                    <div class="mb-3">
                        <label for="regNo" class="form-label">Registration Number:</label>
                        <input type="text" class="form-control" id="regNo" placeholder="Enter Registration Number">
                    </div>
                    <div class="mb-3">
                        <label for="omrRemark" class="form-label">Remark:</label>
                        <select class="form-select" id="omrRemark">
                            <option value="">Select Remark</option>
                            <option value="non-personalized-omr">Used Non-Personalized OMR</option>
                            <option value="blank-omr">Returned Blank OMR Sheet</option>
                            <option value="used-pencil">Used Pencil in OMR Sheet</option>
                            <option value="wrong-pen">Used Other Than Black Ballpoint Pen</option>
                        </select>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-primary" onclick="saveOMRRemark()">Save Remark</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
