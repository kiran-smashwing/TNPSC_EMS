<!-- Candidate Remarks Modal -->
<div class="modal fade modal-animate" data-bs-backdrop="static" id="candidateRemarkseditModal" tabindex="-1"
    aria-labelledby="candidateRemarkseditModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editCandidateRemarksForm" action="{{route('ci-candidates-remark.updateremarks')}}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="exam_id" value="{{ $session->currentexam->exam_main_no }}">
                <input type="hidden" name="exam_sess_date" value="{{ $session->exam_sess_date }}">
                <input type="hidden" name="exam_sess_session" value="{{ $session->exam_sess_session }}">

                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-primary">
                        <i class="feather icon-edit me-2"></i>Edit Candidate Remarks
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    @if (
                        (isset($candidate_remarks_data['FN']) && !empty($candidate_remarks_data['FN'])) ||
                            (isset($candidate_remarks_data['AN']) && !empty($candidate_remarks_data['AN'])))
                        @foreach (['FN', 'AN'] as $key)
                            @if (isset($candidate_remarks_data[$key]) && !empty($candidate_remarks_data[$key]))
                                @foreach ($candidate_remarks_data[$key] as $index => $remark)
                                    <div class="mb-3">
                                        <!-- Registration Number -->
                                        <label for="regNo_{{ $key }}_{{ $index }}"
                                            class="form-label">Registration Number:</label>
                                        <input type="text" class="form-control"
                                            id="regNo_{{ $key }}_{{ $index }}"
                                            name="candidateRegNo[]"
                                            value="{{ $remark['registration_number'] ?? 'N/A' }}" readonly>
                                    </div>

                                    <!-- Remarks -->
                                    <div class="mb-3">
                                        <label for="remarks_{{ $key }}_{{ $index }}"
                                            class="form-label">Remarks:</label>
                                        <select class="form-select"
                                            id="remarks_{{ $key }}_{{ $index }}"
                                            name="candidateRemarks[]">
                                            <option disabled>Select a remark</option>
                                            <option value="Indulged in Malpractice"
                                                {{ isset($remark['remark']) && $remark['remark'] == 'Indulged in Malpractice' ? 'selected' : '' }}>
                                                Indulged in Malpractice</option>
                                            <option value="Wrongly Seated"
                                                {{ isset($remark['remark']) && $remark['remark'] == 'Wrongly Seated' ? 'selected' : '' }}>
                                                Wrongly Seated</option>
                                            <option value="Used OMR of Another Candidate"
                                                {{ isset($remark['remark']) && $remark['remark'] == 'Used OMR of Another Candidate' ? 'selected' : '' }}>
                                                Used OMR of Another Candidate</option>
                                            <option value="Left Exam During Examination"
                                                {{ isset($remark['remark']) && $remark['remark'] == 'Left Exam During Examination' ? 'selected' : '' }}>
                                                Left Exam During Examination</option>
                                            <option value="Used Scribe Assistance"
                                                {{ isset($remark['remark']) && $remark['remark'] == 'Used Scribe Assistance' ? 'selected' : '' }}>
                                                Used Scribe Assistance</option>
                                        </select>
                                    </div>

                                    <hr>
                                @endforeach
                            @endif
                        @endforeach
                    @else
                        <p class="text-danger">No candidate remarks available for editing.</p>
                    @endif
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="feather icon-save me-2"></i>Update Remarks
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
