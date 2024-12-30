<!-- Scribe Attendance and Allotment Modal -->
<div class="modal fade modal-animate anim-blur" data-bs-backdrop="static" id="scribeSelectModal" tabindex="-1"
    aria-labelledby="scribeSelectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('update.scribe.details', [$session->currentexam->exam_main_no, $session->exam_sess_date, $ci_id]) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="exam_id" value="{{ $session->currentexam->exam_main_no }}">
                <input type="hidden" name="exam_sess_date" value="{{ $session->exam_sess_date }}">
                <input type="hidden" name="exam_sess_session" value="{{ $session->exam_sess_session }}">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-primary" id="scribeSelectModalLabel">
                    <i class="feather icon-users me-2"></i>Select Scribe <span class="text-warning"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Scribe Details Section -->
                    <div class="mb-4">
                        <div class="col-lg-12 col-md-11 col-sm-12">
                            <!-- Text input for Registration Number -->
                            <input type="text" id="reg_no" name="reg_no[]" class="form-control"
                                placeholder="Enter Reg No">
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="col-lg-12 col-md-11 col-sm-12">
                            <!-- Searchable Single-select Dropdown -->
                            <select class="form-control select2" name="scribes[]" id="single-option-dropdown">
                                {{-- <option value="">Select Scribe</option> --}}
                                @foreach ($scribe as $scribe)
                                    <!-- Ensure variable name consistency -->
                                    <option value="{{ $scribe->scribe_id }}">
                                        {{ $scribe->scribe_name }} - {{ $scribe->scribe_phone }}
                                    </option>
                                @endforeach
                            </select>
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
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <script>
        // Initialize Choices.js for the single dropdown
        new Choices('#single-option-dropdown', {
            placeholderValue: 'Select Scribe', // Placeholder text
            searchPlaceholderValue: 'Search Scribe...', // Search input placeholder
            // maxItemCount: 1, // Ensure single selection
            removeItemButton: true, // Allow clearing the selection
            allowHTML: true, // Allow HTML in options if needed
        });
    </script>
@endpush
