<!-- Scribe Attendance and Allotment Modal -->
<div class="modal fade modal-animate anim-blur" data-bs-backdrop="static" id="scribeAllotmentModal" tabindex="-1"
    aria-labelledby="scribeAllotmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-primary" id="scribeAllotmentModalLabel">
                    <i class="feather icon-users me-2"></i>Scribe Allotment
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form to Assign Scribes -->

                <div class="row">
                    <!-- Iterate through registration numbers and scribes -->
                    <div class="col-12">
                        <!-- Each Scribe Allocation Block -->
                        <div class="mb-4">
                            <h6 class="text-primary mb-2">Scribe Allocation</h6>

                            <!-- Loop through the scribes data -->
                            @if (!empty($scribes_type) && count($scribes_type) > 0)
                                @foreach ($scribes_type as $index => $scribeData)
                                    <div class="card mb-2">
                                        <div class="card-body">
                                            <div class="row">
                                                <!-- Registration Number Field -->
                                                <div class="col-md-4 mb-2">
                                                    <label for="reg{{ $index + 1 }}" class="form-label">Reg
                                                        No:</label>
                                                    <input type="text" class="form-control"
                                                        id="reg{{ $index + 1 }}"
                                                        value="{{ is_array($scribeData['reg_no']) ? implode(', ', $scribeData['reg_no']) : $scribeData['reg_no'] ?? 'Not Available' }}"
                                                        readonly>
                                                </div>
                                                <!-- Scribe Name Field -->
                                                <!-- Scribe Name and Phone Fields -->
                                                <div class="col-md-8 mb-2">
                                                    <label for="scribe{{ $index + 1 }}"
                                                        class="form-label">Scribe:</label>
                                                    <input type="text" class="form-control"
                                                        id="scribe{{ $index + 1 }}"
                                                        value="{{ $scribeData['scribe_name'] }} - {{ $scribeData['scribe_phone'] }}"
                                                        readonly>
                                                </div>



                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="card">
                                    <div class="card-body">
                                        <p class="text-muted">No Scribe assigned.</p>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>


                </div>
            </div>
            {{-- <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary d-flex align-items-center"
                    onclick="saveInvigilatorDetails()">
                    <i class="feather icon-save me-2"></i>Save Changes
                </button>
            </div> --}}
        </div>
    </div>
</div>
