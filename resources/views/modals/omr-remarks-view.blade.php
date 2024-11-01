<!-- OMR Remarks View Modal -->
<div class="modal fade modal-animate" data-bs-backdrop="static" id="omrRemarksViewModal" tabindex="-1" aria-labelledby="omrRemarksViewModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title text-primary" id="omrRemarksViewModalLabel">
                    <i class="feather icon-eye me-2"></i>View OMR Remarks
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Remarks List Table -->
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="bg-light">
                            <tr>
                                <th>Registration Number</th>
                                <th>Remark</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Sample Row 1 -->
                            <tr>
                                <td>123456789</td>
                                <td>Used Non-Personalized OMR</td>
                            </tr>
                            <!-- Sample Row 2 -->
                            <tr>
                                <td>987654321</td>
                                <td>Returned Blank OMR Sheet</td>
                            </tr>
                            <!-- Sample Row 3 -->
                            <tr>
                                <td>112233445</td>
                                <td>Used Pencil in OMR Sheet</td>
                            </tr>
                            <!-- Add more rows as needed -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
