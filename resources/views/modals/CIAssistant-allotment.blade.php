<!-- CI Assistant Attendance and Allotment Modal -->
<div class="modal fade modal-animate anim-blur" data-bs-backdrop="static" id="ciAssistantAllotmentModal" tabindex="-1"
    aria-labelledby="ciAssistantAllotmentModelLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-primary" id="ciAssistantAllotmentModelLabel">
                    <i class="feather icon-users me-2"></i>CI Assistant Allotment
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form to Assign CI Assistants -->
                <form id="ciAssistantAllotmentForm">
                    <div class="row">
                        <!-- Iterate through halls and CI Assistants -->
                        <div class="col-12">
                            <!-- Each Hall Allocation Block -->
                            <div class="mb-4">
                                <h6 class="text-primary mb-2">CI Assistants</h6>

                                @if (!empty($assistants_type) && count($assistants_type) > 0)
                                    @foreach ($assistants_type as $index => $assistant)
                                        <div class="card mb-2">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-12 mb-2">
                                                        <label
                                                            for="ciAssistant{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}"
                                                            class="form-label">
                                                            CI Assistant:
                                                        </label>
                                                        <input type="text" class="form-control"
                                                            id="ciAssistant{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}"
                                                            value="{{ $assistant['assistant_name'] ?? 'Unknown' }} - {{ $assistant['assistant_phone'] ?? 'Unknown' }}"
                                                            readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="card">
                                        <div class="card-body">
                                            <p class="text-muted">No CI Assistants assigned for this venue.</p>
                                        </div>
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>
                </form>
            </div>
            {{-- <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary d-flex align-items-center"
                    onclick="saveCIAssistantDetails()">
                    <i class="feather icon-save me-2"></i>Save Changes
                </button>
            </div> --}}
        </div>
    </div>
</div>
