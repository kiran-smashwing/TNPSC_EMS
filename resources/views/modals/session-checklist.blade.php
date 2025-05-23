<div class="modal fade modal-animate anim-blur" data-bs-backdrop="static" id="sessionCheckListModel" tabindex="-1"
    aria-labelledby="sessionCheckListModelLabel" aria-hidden="true">
    <div class="modal-dialog  ">
        <div class="modal-content">
            <form method="POST" action="{{ route('ci-session-checklist.save') }}">
                @csrf
                <input type="hidden" name="exam_id" value="{{ $session->currentexam->exam_main_no }}">
                <input type="hidden" name="exam_sess_date" value="{{ $session->exam_sess_date }}">
                <input type="hidden" name="exam_sess_session" value="{{ $session->exam_sess_session }}">

                <div class="modal-header ">
                    <h5 class="modal-title text-primary" id="sessionCheckListModelLabel">
                        <i class="feather icon-map-pin me-2"></i>Session Checklist
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            @if ($type_sessions->isEmpty())
                                <p>No checklist items found.</p>
                            @else
                                @php
                                    // Decode the session_answer JSON string
                                    $sessionAnswer = $sessionAnswer
                                        ? $sessionAnswer->session_answer : null;

                                    // Initialize selectedIds to an empty array if sessionAnswer or checklist is null
                                    $selectedIds =
                                        $sessionAnswer && isset($sessionAnswer['checklist'])
                                            ? array_keys($sessionAnswer['checklist'])
                                            : [];
                                @endphp
                                @foreach ($type_sessions as $item)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input input-primary"
                                            name="checklist[{{ $item->ci_checklist_id }}]" type="checkbox"
                                            id="customCheck{{ $item->ci_checklist_id }}"
                                            data-checklist-id="{{ $item->ci_checklist_id }}"
                                            data-description="{{ $item->ci_checklist_description }}"
                                            {{ in_array($item->ci_checklist_id, $selectedIds) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="customCheck{{ $item->ci_checklist_id }}">
                                            {{ $item->ci_checklist_description }}
                                        </label>

                                        <!-- Placeholder for dynamic fields -->
                                        <div class="dynamic-fields" id="dynamicFields{{ $item->ci_checklist_id }}"
                                            style="display: none;">
                                            <!-- Inspection Staff Fields -->
                                            @if (strpos($item->ci_checklist_description, 'Inspection Staff') !== false)
                                                <div class="mt-3">
                                                    <div class="row g-2">
                                                        <div class="col-md-4">
                                                            <label
                                                                for="inspectionStaffName{{ $item->ci_checklist_id }}"
                                                                class="form-label">Name</label>
                                                            <input type="text"
                                                                id="inspectionStaffName{{ $item->ci_checklist_id }}"
                                                                name="inspectionStaff[{{ $item->ci_checklist_id }}][name]"
                                                                class="form-control" placeholder="Enter Name"
                                                                value="{{ $sessionAnswer['inspection_staff'][$item->ci_checklist_id]['name'] ?? '' }}">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label
                                                                for="inspectionStaffDesignation{{ $item->ci_checklist_id }}"
                                                                class="form-label">Designation</label>
                                                            <input type="text"
                                                                id="inspectionStaffDesignation{{ $item->ci_checklist_id }}"
                                                                name="inspectionStaff[{{ $item->ci_checklist_id }}][designation]"
                                                                class="form-control" placeholder="Enter Designation"
                                                                value="{{ $sessionAnswer['inspection_staff'][$item->ci_checklist_id]['designation'] ?? '' }}">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label
                                                                for="inspectionStaffDepartment{{ $item->ci_checklist_id }}"
                                                                class="form-label">Department</label>
                                                            <input type="text"
                                                                id="inspectionStaffDepartment{{ $item->ci_checklist_id }}"
                                                                name="inspectionStaff[{{ $item->ci_checklist_id }}][department]"
                                                                class="form-control" placeholder="Enter Department"
                                                                value="{{ $sessionAnswer['inspection_staff'][$item->ci_checklist_id]['department'] ?? '' }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary d-flex align-items-center"> <i
                                class="feather icon-save me-2"></i>Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(function() {
            // Function to toggle dynamic fields
            function toggleDynamicFields($checkbox) {
                const checklistId = $checkbox.data('checklist-id');
                const $dynamicFields = $('#dynamicFields' +
                    checklistId); // Removed the hyphen to match your HTML IDs

                if ($checkbox.is(':checked')) {
                    $dynamicFields.slideDown();
                } else {
                    $dynamicFields.slideUp();
                }
            }

            // Event listener for checkbox changes
            $(document).on('change', '.form-check-input',
                function() { // Changed selector to match your checkbox class
                    toggleDynamicFields($(this));
                });

            // Initialize fields on page load
            $('.form-check-input').each(function() {
                // Check if the checkbox was previously checked (from old input)
                if ($(this).is(':checked')) {
                    const checklistId = $(this).data('checklist-id');
                    $('#dynamicFields' + checklistId).show();
                }
            });

            // Optional: Handle modal show event to reinitialize fields
            $('#sessionCheckListModel').on('shown.bs.modal', function() {
                $('.form-check-input').each(function() {
                    if ($(this).is(':checked')) {
                        const checklistId = $(this).data('checklist-id');
                        $('#dynamicFields' + checklistId).show();
                    }
                });
            });
        });
    </script>
@endpush
