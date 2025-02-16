<!-- OMR Remarks View Modal -->
<div class="modal fade modal-animate" data-bs-backdrop="static" id="omrRemarksViewModal" tabindex="-1"
    aria-labelledby="omrRemarksViewModalLabel" aria-hidden="true">
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
                        @php
                            // Decode the stored JSON response
                            $omrRemarksData = optional($omrRemarks)->omr_remarks;
                            $remarksList = $omrRemarksData['remarks'] ?? []; // Extract remarks array
                        @endphp
                        <tbody>
                            @foreach ($remarksList as $remark)
                                <tr>
                                    <td>{{ $remark['reg_no'] }}</td>
                                    <td>{{ $remark['remark'] }}</td>
                                </tr>
                            @endforeach
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
