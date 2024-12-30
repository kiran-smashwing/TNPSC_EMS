<!-- Candidate Remarks View Modal -->
<div class="modal fade modal-animate" data-bs-backdrop="static" id="candidateRemarksViewModal" tabindex="-1"
    aria-labelledby="candidateRemarksViewModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title text-primary" id="candidateRemarksViewModalLabel">
                    <i class="feather icon-eye me-2"></i>View Candidate Remarks
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
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($candidate_remarks_data) && !empty($candidate_remarks_data))
                                @foreach ($candidate_remarks_data as $session => $remarks)
                                    @foreach ($remarks as $remark)
                                        <tr>
                                            <td>{{ $remark['registration_number'] ?? 'N/A' }}</td>
                                            <td>{{ $remark['remark'] ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="2" class="text-center">No remarks available</td>
                                </tr>
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
