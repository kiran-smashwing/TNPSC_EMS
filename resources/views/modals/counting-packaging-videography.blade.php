<div class="modal fade modal-animate anim-blur" data-bs-backdrop="static" id="countingpackagingvideography" tabindex="-1"
    aria-labelledby="countingpackagingvideographyModelLabel" aria-hidden="true">
    <div class="modal-dialog  ">
        <form method="POST" action="{{ route('saveVideographyChecklist') }}">
            @csrf
            <input type="hidden" name="exam_id" value="{{ $session->currentexam->exam_main_no }}">
            <input type="hidden" name="exam_sess_date" value="{{ $session->exam_sess_date }}">
            <input type="hidden" name="exam_sess_session" value="{{ $session->exam_sess_session }}">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-primary" id="countingpackagingvideographyModelLabel">
                        <i class="feather icon-video me-2"></i>Videography
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                @php
                    // Decode the stored JSON response
                    $videographyAnswer = optional($videographyAnswer)->videography_answer;
                    $checklistData = $videographyAnswer['checklist'] ?? [];
                @endphp

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            @foreach ([1, 2] as $key)
                                @php
                                    $isChecked =
                                        isset($checklistData[$key]['value']) && $checklistData[$key]['value'] == '1';
                                    $remark = $checklistData[$key]['remark'] ?? '';
                                @endphp
                                <div class="form-check mb-2">
                                    <!-- Hidden input for unchecked value -->
                                    <input type="hidden" name="checklist[{{ $key }}]" value="0">
                                    <input class="form-check-input input-primary" type="checkbox"
                                        name="checklist[{{ $key }}]" id="customCheckc{{ $key }}"
                                        value="1" {{ $isChecked ? 'checked' : '' }}>
                                    <label class="form-check-label" for="customCheckc{{ $key }}">
                                        @if ($key == 1)
                                            Whether the entire counting and packing activities of all the Bundles A
                                            (Covers A1 & A2)
                                            & B (Covers B1, B2, B3, B4 & B5) have been completely videographed without
                                            any break?
                                        @else
                                            Whether the Videographer has videographed all the exam rooms during the time
                                            of
                                            examination covering the entrance and the blackboard in the classroom, where
                                            the
                                            Register Numbers and the seating arrangement are displayed?
                                        @endif
                                    </label>
                                    <div class="mt-3">
                                        <textarea name="remarks[{{ $key }}]" id="videographyRemarks{{ $key }}" class="form-control"
                                            placeholder="Enter Remarks" rows="4">{{ old("remarks[$key]", $remark) }}</textarea>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary d-flex align-items-center">
                        <i class="feather icon-save me-2"></i>Save Changes
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
