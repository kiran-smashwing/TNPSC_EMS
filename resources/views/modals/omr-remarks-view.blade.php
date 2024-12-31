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
                            @if(isset($candidate_orm_remarks_data['AN']))
                                @foreach($candidate_orm_remarks_data['AN'] as $remark)
                                    <tr>
                                        <td>{{ $remark['registration_number'] }}</td>
                                        <td>{{ $remark['remark'] }}</td>
                                    </tr>
                                @endforeach
                            @endif
            
                            @if(isset($candidate_orm_remarks_data['FN']))
                                @foreach($candidate_orm_remarks_data['FN'] as $remark)
                                    <tr>
                                        <td>{{ $remark['registration_number'] }}</td>
                                        <td>{{ $remark['remark'] }}</td>
                                    </tr>
                                @endforeach
                            @endif
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
