<!-- Additional Candidate Modal -->
<div class="modal fade modal-animate anim-blur" data-bs-backdrop="static" id="additionalCandidateViewModal" tabindex="-1"
    aria-labelledby="additionalCandidateViewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-primary" id="additionalCandidateViewModalLabel">
                    <i class="feather icon-user-plus me-2"></i>List of Added Candidates
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Static List of Added Candidates View -->
                <div class="card-body">
                    <p class="fw-bold">Below is the list of added candidates:</p>
                    <!-- List of Added Candidates -->
                    <ul class="list-group">
                        <!-- Loop through AN session candidates -->
                        @if (isset($candidate_logs_data['AN']) && count($candidate_logs_data['AN']) > 0)
                            @foreach ($candidate_logs_data['AN'] as $log)
                                <li class="list-group-item">
                                    <strong>Reg No:</strong> {{ $log['registration_number'] }} -
                                    <strong>Name:</strong> {{ $log['candidate_name'] }}
                                </li>
                            @endforeach
                        @endif

                        <!-- Loop through FN session candidates -->
                        @if (isset($candidate_logs_data['FN']) && count($candidate_logs_data['FN']) > 0)
                            @foreach ($candidate_logs_data['FN'] as $log)
                                <li class="list-group-item">
                                    <strong>Reg No:</strong> {{ $log['registration_number'] }} -
                                    <strong>Name:</strong> {{ $log['candidate_name'] }}
                                </li>
                            @endforeach
                        @endif

                        <!-- If no candidates for both sessions -->
                        @if (empty($candidate_logs_data['AN']) && empty($candidate_logs_data['FN']))
                            <li class="list-group-item">No candidates available for either session.</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>


    </div>
</div>
