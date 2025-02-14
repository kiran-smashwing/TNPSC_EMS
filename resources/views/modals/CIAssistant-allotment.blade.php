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
                                {{-- <h6 class="text-primary mb-2">CI Assistants</h6> --}}
                                @php
                                    // Decode the session_answer JSON string for selected assistants
                                    $selectedAssistant = $selectedAssistant
                                        ? json_decode($selectedAssistant->selected_assistants, true)
                                        : null;

                                    // Initialize assistantAssignments to an empty array if selectedAssistant or assistant_ids is null
                                    $assistantAssignments =
                                        $selectedAssistant && isset($selectedAssistant['assistant_ids'])
                                            ? $selectedAssistant['assistant_ids']
                                            : [];

                                    // Fetch assistant details from the database
                                    $assistants = \App\Models\CIAssistant::whereIn('cia_id', $assistantAssignments)
                                        ->get()
                                        ->keyBy('cia_id');
                                @endphp

                                @if (!empty($assistantAssignments))
                                    @foreach ($assistantAssignments as $index => $assistantId)
                                        @php
                                            $assistant = $assistants->get($assistantId);
                                        @endphp
                                        <div class="row">
                                            <div class="col-md-12 mb-2">
                                                <label for="ciAssistant{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}"
                                                    class="form-label">
                                                </label>
                                                <input type="text" class="form-control"
                                                    id="ciAssistant{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}"
                                                    value="{{ $assistant ? $assistant->cia_name . ' - ' . $assistant->cia_phone : 'Unknown - Unknown' }}"
                                                    readonly>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="card">
                                        <div class="card-body">
                                            <p class="text-muted">No Assistants assigned for this venue.</p>
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
