<!-- CI Assistant Attendance and Allotment Modal -->
<div class="modal fade modal-animate anim-blur" data-bs-backdrop="static" id="ciAssistantSelectModal" tabindex="-1"
    aria-labelledby="ciAssistantSelectModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <!-- Form Blade Template -->
            <form
                action="{{ route('update.ci-assistant-details', [$session->currentexam->exam_main_no, $session->exam_sess_date, $ci_id]) }}"
                method="POST">
                @csrf
                @method('PUT')

                <input type="hidden" name="exam_id" value="{{ $session->currentexam->exam_main_no }}">
                <input type="hidden" name="exam_sess_date" value="{{ $session->exam_sess_date }}">
                <input type="hidden" name="exam_sess_session" value="{{ $session->exam_sess_session }}">

                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-primary" id="ciAssistantSelectModalLabel">
                        <i class="feather icon-users me-2"></i>Select CI Assistant - Required <span
                            class="text-warning">2</span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    @php
                        // Decode the selected_assistants JSON string
                        $selectedAssistant = $selectedAssistant
                            ? json_decode($selectedAssistant->selected_assistants, true)
                            : null;

                        // Initialize selectedIds to an empty array if selectedAssistant or selected_assistants is null
                        $selectedIds =
                            $selectedAssistant && isset($selectedAssistant['assistant_ids'])
                                ? $selectedAssistant['assistant_ids']
                                : [];
                    @endphp
                    <div class="row">
                        <div class="mb-4">
                            <div class="col-lg-12 col-md-11 col-sm-12">
                                <!-- Changed the select name to match the validation -->
                                <select class="form-control @error('assistants') is-invalid @enderror"
                                    name="assistants[]" id="ciAssistantSelect" multiple required>
                                    <option value="">Select CI Assistant</option>
                                    @foreach ($ci_assistant as $assistant)
                                        <option value="{{ $assistant->cia_id }}"
                                            {{ in_array($assistant->cia_id, $selectedIds) ? 'selected' : '' }}>
                                            {{ $assistant->cia_name }} - {{ $assistant->cia_phone }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('assistants')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
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
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropdown = document.getElementById('ciAssistantSelect');

            if (!dropdown) {
                console.error('Dropdown element not found.');
                return;
            }

            // Initialize Choices.js
            const multipleChoices = new Choices(dropdown, {
                placeholder: true,
                placeholderValue: 'Select CI Assistant',
                maxItemCount: 2,
                removeItemButton: true,
                searchEnabled: true,
                searchPlaceholderValue: 'Search for assistant...',
                noResultsText: 'No assistants found',
                itemSelectText: 'Press to select',
                maxItemText: (maxItemCount) => {
                    return `Only ${maxItemCount} assistants can be selected`;
                }
            });

            // Function to handle selection state
            const updateSelectionState = () => {
                const selectedCount = multipleChoices.getValue().length;

                // Disable choices when 2 items are selected
                if (selectedCount >= 2) {
                    dropdown.disabled = true;
                    multipleChoices.enable();
                } else {
                    dropdown.disabled = false;
                    multipleChoices.enable();
                }
            };

            // Add event listeners
            dropdown.addEventListener('change', updateSelectionState);
            dropdown.addEventListener('choice', updateSelectionState);

            // Initial state check
            updateSelectionState();
        });
    </script>
@endpush
