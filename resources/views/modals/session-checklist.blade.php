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
                                @foreach ($type_sessions as $item)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input input-primary"
                                            name="checklist[{{ $item->ci_checklist_id }}]" type="checkbox"
                                            id="customCheck{{ $item->ci_checklist_id }}"
                                            data-checklist-id="{{ $item->ci_checklist_id }}"
                                            data-description="{{ $item->ci_checklist_description }}"
                                            {{ old('checklist.' . $item->ci_checklist_id) ? 'checked' : '' }}>
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
                                                                class="form-control" placeholder="Enter Name">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label
                                                                for="inspectionStaffDesignation{{ $item->ci_checklist_id }}"
                                                                class="form-label">Designation</label>
                                                            <input type="text"
                                                                id="inspectionStaffDesignation{{ $item->ci_checklist_id }}"
                                                                name="inspectionStaff[{{ $item->ci_checklist_id }}][designation]"
                                                                class="form-control" placeholder="Enter Designation">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label
                                                                for="inspectionStaffDepartment{{ $item->ci_checklist_id }}"
                                                                class="form-label">Department</label>
                                                            <input type="text"
                                                                id="inspectionStaffDepartment{{ $item->ci_checklist_id }}"
                                                                name="inspectionStaff[{{ $item->ci_checklist_id }}][department]"
                                                                class="form-control" placeholder="Enter Department">
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            <!-- Police Constable Fields -->
                                            @if (strpos($item->ci_checklist_description, 'police') !== false)
                                                <div class="mt-3">
                                                    <div class="row g-2">
                                                        <div class="col-md-12">
                                                            <label for="pcCount{{ $item->ci_checklist_id }}"
                                                                class="form-label">PC Count</label>
                                                            <input type="text"
                                                                id="pcCount{{ $item->ci_checklist_id }}"
                                                                name="pcCount[{{ $item->ci_checklist_id }}]"
                                                                class="form-control" placeholder="Enter PC Count">
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
        <script>
            $(document).ready(function() {
                // Event listener for checkbox changes
                $('.checklist-checkbox').on('change', function() {
                    const checklistId = $(this).data('checklist-id');
                    const isChecked = $(this).prop('checked');
                    const dynamicFields = $('#dynamicFields' + checklistId);

                    // Show/hide the dynamic fields based on checkbox state
                    if (isChecked) {
                        dynamicFields.show();
                    } else {
                        dynamicFields.hide();
                    }
                });

                // Initially check the state of the checkboxes and show relevant fields
                $('.checklist-checkbox').each(function() {
                    const checklistId = $(this).data('checklist-id');
                    const isChecked = $(this).prop('checked');
                    const dynamicFields = $('#dynamicFields' + checklistId);

                    // If checked, show the dynamic fields
                    if (isChecked) {
                        dynamicFields.show();
                    } else {
                        dynamicFields.hide();
                    }
                });
            });
        </script>
    @endpush
