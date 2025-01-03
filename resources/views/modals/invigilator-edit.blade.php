<!-- Invigilator Attendance and Allotment Modal -->
<div class="modal fade modal-animate anim-blur" data-bs-backdrop="static" id="invigilatorEditModal" tabindex="-1"
    aria-labelledby="invigilatorSelectModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <form id="invigilator-form" action="{{ route('update-invigilator.details', [$session->currentexam->exam_main_no, $session->exam_sess_date, $ci_id]) }}" method="POST">
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
                                <select class="form-control" id="choices-multiple-default" name="invigilators[]"
                                    multiple>
                                    <option value="">Select Invigilator</option>
                                    @foreach ($invigilator as $invigilatorItem)
                                        <option value="{{ $invigilatorItem->invigilator_id }}"
                                            @if (in_array($invigilatorItem->invigilator_id, old('invigilators', $invigilators_type))) selected @endif>
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
                console.error('Alloted count element not found.');
                return;
            }

            // Get the allotted count value
            const allotedCount = parseInt(allotedCountElement.textContent.trim());
            if (isNaN(allotedCount)) {
                console.error('Alloted count is not a valid number.');
                return;
            }

            console.log(`Allotted Count: ${allotedCount}`);

            const dropdown = document.getElementById('choices-multiple-default');
            if (!dropdown) {
                console.error('Dropdown element not found.');
                return;
            }

            // Initialize Choices.js
            const multipleChoices = new Choices(dropdown, {
                placeholder: true,
                placeholderValue: 'Pick Invigilator',
                maxItemCount: allotedCount, // Limit number of selected items
                removeItemButton: true, // Enable remove item button
            });

            // Function to handle selection count
            window.checkSelection = function() {
                const selectedCount = multipleChoices.getValue(true).length;
                console.log(`Selected Count: ${selectedCount}`);

                // Disable dropdown if the selected count matches the allotted count
                if (selectedCount >= allotedCount) {
                    dropdown.disabled = true;
                    console.log('Dropdown disabled');
                } else {
                    dropdown.disabled = false;
                    console.log('Dropdown enabled');
                }
            };

            // Initial check in case of pre-selected options
            checkSelection();
        });
    </script>
@endpush