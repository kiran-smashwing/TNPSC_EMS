<!-- Invigilator Attendance and Allotment Modal -->
<div class="modal fade modal-animate anim-blur" data-bs-backdrop="static" id="invigilatorSelectModal" tabindex="-1"
    aria-labelledby="invigilatorSelectModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <form id="invigilator-form" action="{{ route('save-invigilator.details') }}" method="POST">
                @csrf
                <input type="hidden" name="exam_id" value="{{ $session->currentexam->exam_main_no }}">
                <input type="hidden" name="exam_sess_date" value="{{ $session->exam_sess_date }}">
                <input type="hidden" name="exam_sess_session" value="{{ $session->exam_sess_session }}">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-primary" id="invigilatorSelectModalLabel">
                        <i class="feather icon-users me-2"></i>Select Invigilator - Required
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
                                        <option value="{{ $invigilatorItem->invigilator_id }}">
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
            // Get the allotted count element and validate it exists
            const allotedCountElement = document.getElementById('alloted-count-display');
            if (!allotedCountElement) {
                console.error('Alloted count element not found.');
                return;
            }

            // Parse and validate the allotted count
            const allotedCount = parseInt(allotedCountElement.textContent.trim());
            if (isNaN(allotedCount)) {
                console.error('Alloted count is not a valid number.');
                return;
            }

            console.log(`Allotted Count: ${allotedCount}`);

            // Get the dropdown element
            const dropdown = document.getElementById('choices-multiple-default');
            if (!dropdown) {
                console.error('Dropdown element not found.');
                return;
            }

            // Make sure Choices.js is loaded
            if (typeof Choices === 'undefined') {
                console.error('Choices.js library is not loaded.');
                return;
            }

            try {
                // Initialize Choices.js with configuration
                const multipleChoices = new Choices(dropdown, {
                    placeholder: true,
                    placeholderValue: 'Pick Invigilator',
                    maxItemCount: allotedCount,
                    removeItemButton: true,
                    searchEnabled: true,
                    silent: false
                });

                // Function to toggle the dropdown interface
                const toggleDropdownInterface = () => {
                    const selectedCount = multipleChoices.getValue().length;
                    const dropdownInput = document.querySelector('.choices__input--cloned');
                    const dropdownList = document.querySelector('.choices__list--dropdown');

                    if (selectedCount >= allotedCount) {
                        // Hide the dropdown interface when limit is reached
                        if (dropdownInput) dropdownInput.style.display = 'none';
                        if (dropdownList) dropdownList.style.display = 'none';
                    } else {
                        // Show the dropdown interface when under limit
                        if (dropdownInput) dropdownInput.style.display = '';
                        if (dropdownList) dropdownList.style.display = '';
                    }
                };

                // Add change event listener to handle selection count
                multipleChoices.passedElement.element.addEventListener('change', function() {
                    const selectedCount = multipleChoices.getValue().length;
                    console.log(`Selected Count: ${selectedCount}`);

                    // Toggle dropdown interface visibility
                    toggleDropdownInterface();

                    if (selectedCount >= allotedCount) {
                        // alert(`Maximum ${allotedCount} selections allowed`);

                        // If user tries to add more than allowed, remove the last selection
                        const currentSelections = multipleChoices.getValue();
                        if (currentSelections.length > allotedCount) {
                            // Remove the last added item
                            multipleChoices.removeActiveItems();
                            // Restore previous valid selections
                            const validSelections = currentSelections.slice(0, allotedCount);
                            validSelections.forEach(item => {
                                multipleChoices.setChoiceByValue(item.value);
                            });
                        }
                    }
                });

                // Listen for removal of items
                multipleChoices.passedElement.element.addEventListener('removeItem', function() {
                    // Show the dropdown interface when an item is removed
                    toggleDropdownInterface();
                });

                // Initialize interface visibility
                toggleDropdownInterface();

            } catch (error) {
                console.error('Error initializing Choices:', error);
            }
        });
    </script>
@endpush
