<div class="modal fade modal-animate anim-blur" data-bs-backdrop="static" id="consolidatecertificate" tabindex="-1"
    aria-labelledby="consolidatecertificateModelLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header bg-warning">
                <h5 class="modal-title text-primary" id="consolidatecertificateModelLabel">
                    <i class="feather icon-file-text me-2"></i>Consolidate Certificate
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="consolidatecertificateForm" action="{{ route('saveConsolidateCertificate') }}" method="POST">
                    @csrf
                    <!-- Hidden inputs to send exam session details -->
                    <input type="hidden" name="exam_id" value="{{ $session->currentexam->exam_main_no }}">
                    <input type="hidden" name="exam_sess_date" value="{{ $session->exam_sess_date }}">
                    <input type="hidden" name="exam_sess_session" value="{{ $session->exam_sess_session }}">

                    <div class="row">
                        <div class="col-md-12">
                            @foreach ($consolidate_data as $checklist)
                                <div class="form-check mb-2">
                                    <!-- Hidden input to send 0 when unchecked -->
                                    <input type="hidden" name="checklists[{{ $checklist->ci_checklist_id }}]"
                                        value="0">

                                    <!-- Checkbox input to send 1 when checked -->
                                    <input class="form-check-input input-primary" type="checkbox"
                                        name="checklists[{{ $checklist->ci_checklist_id }}]"
                                        id="customCheck{{ $loop->index }}" value="1"
                                        {{ in_array($checklist->ci_checklist_id, old('checklists', [])) ? 'checked' : '' }}>

                                    <label class="form-check-label" for="customCheck{{ $loop->index }}">
                                        {{ $checklist->ci_checklist_description }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary d-flex align-items-center">
                            <i class="feather icon-save me-2"></i>Save Changes
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
