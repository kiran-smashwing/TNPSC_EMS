<!-- Invigilator Attendance and Allotment Modal -->
<div class="modal fade modal-animate anim-blur" data-bs-backdrop="static" id="invigilatorEditModal" tabindex="-1"
    aria-labelledby="invigilatorSelectModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <form id="invigilator-form"
                action="{{ route('update-invigilator.details', [$session->currentexam->exam_main_no, $session->exam_sess_date, $ci_id]) }}"
                method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="exam_id" value="{{ $session->currentexam->exam_main_no }}">
                <input type="hidden" name="exam_sess_date" value="{{ $session->exam_sess_date }}">
                <input type="hidden" name="exam_sess_session" value="{{ $session->exam_sess_session }}">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-primary" id="invigilatorSelectModalLabel">
                        <i class="feather icon-users me-2"></i>Edit Invigilator - Required
                        <span class="text-warning" id="alloted-count-display">{{ $alloted_count }}</span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-4">
                            <div class="col-lg-12 col-md-11 col-sm-12">
                                {{-- Flatten the invigilators_type array --}}
                                @php
                                    $flattenedInvigilators = collect($invigilators_type)
                                        ->pluck('invigilators')
                                        ->toArray();
                                @endphp

                                <select class="form-control" id="choices-multiple-default" name="invigilators[]"
                                    multiple>
                                    <option value="">Select Invigilator</option>
                                    @foreach ($invigilator as $invigilatorItem)
                                        <option value="{{ $invigilatorItem->invigilator_id }}"
                                            @if (in_array($invigilatorItem->invigilator_id, old('invigilators', $flattenedInvigilators))) selected @endif>
                                            {{ $invigilatorItem->invigilator_name }} -
                                            {{ $invigilatorItem->invigilator_phone }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>



                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary d-flex align-items-center" id="save-changes-button">
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
            const allotedCountElement = document.getElementById('alloted-count-display');
            if (!allotedCountElement) {
                console.error('Allotted count element not found.');
                return;
            }

            // Get the allotted count value
            const allotedCount = parseInt(allotedCountElement.textContent.trim(), 10);
            if (isNaN(allotedCount)) {
                console.error('Allotted count is not a valid number.');
                return;
            }

            console.log(`Allotted Count: ${allotedCount}`);

            const dropdown = document.getElementById('choices-multiple-default');
            if (!dropdown) {
                console.error('Dropdown element not found.');
                return;
            }

            // Identify pre-selected options
            const preSelectedOptions = Array.from(dropdown.options).filter(option => option.selected && option
                .value !== "");
            const initialSelectedCount = preSelectedOptions.length;

            console.log(`Initial Selected Count: ${initialSelectedCount}`);

            // Initialize Choices.js
            const multipleChoices = new Choices(dropdown, {
                placeholder: true,
                placeholderValue: 'Pick Invigilator',
                maxItemCount: allotedCount, // Limit number of selected items
                removeItemButton: true, // Enable remove item button
            });

            // Disable dropdown if pre-selected options already match allotted count
            dropdown.disabled = initialSelectedCount >= allotedCount;

            // Function to handle selection count dynamically
            const checkSelection = () => {
                const selectedCount = multipleChoices.getValue(true).length;
                console.log(`Selected Count: ${selectedCount}`);

                // Enable/disable dropdown based on selected count
                dropdown.disabled = selectedCount >= allotedCount;
            };

            // Attach change event listener to monitor selections
            dropdown.addEventListener('change', checkSelection);

            // Initial check to account for pre-selected options
            checkSelection();
        });
    </script>
@endpush
