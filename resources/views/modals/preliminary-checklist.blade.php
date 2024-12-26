<div class="modal fade modal-animate anim-blur" data-bs-backdrop="static" id="preliminaryCheckListModel" tabindex="-1"
    aria-labelledby="venueDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog  ">
        <div class="modal-content">
            <form action="{{ route('ci-checklist.save') }}" method="POST">
                @csrf <!-- CSRF token for Laravel form security -->
                <input type="hidden" name="exam_id" value="{{ $session->exam_main_no }}">
                <div class="modal-header ">
                    <h5 class="modal-title text-primary" id="venueDetailsModalLabel">
                        <i class="feather icon-map-pin me-2"></i>Preliminary Checklist
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            @if ($preliminary->isEmpty())
                                <p>No checklist items found.</p>
                            @else
                                @foreach ($preliminary as $item)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input input-primary"
                                            name="checklist[{{ $item->ci_checklist_id }}]" type="checkbox"
                                            id="customCheck{{ $item->ci_checklist_id }}" checked>
                                        <label class="form-check-label" for="customCheck{{ $item->ci_checklist_id }}">
                                            {{ $item->ci_checklist_description }}
                                        </label>
                                    </div>
                                @endforeach
                            @endif
                        </div>
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
