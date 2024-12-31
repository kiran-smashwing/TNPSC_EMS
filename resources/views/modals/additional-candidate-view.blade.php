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
                <!-- Static Table of Added Candidates View -->
                <div class="card-body">
                    {{-- <p class="fw-bold">Below is the list of added candidates:</p> --}}
                    <!-- Table of Added Candidates -->
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="bg-light">
                                <tr>
                                    <th>Reg No</th>
                                    <th>Name</th>
                                    {{-- <th>Session</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Loop through AN session candidates -->
                                @if (isset($candidate_logs_data['AN']) && count($candidate_logs_data['AN']) > 0)
                                    @foreach ($candidate_logs_data['AN'] as $log)
                                        <tr>
                                            <td>{{ $log['registration_number'] }}</td>
                                            <td>{{ $log['candidate_name'] }}</td>
                                            {{-- <td>AN</td> --}}
                                        </tr>
                                    @endforeach
                                @endif

                                <!-- Loop through FN session candidates -->
                                @if (isset($candidate_logs_data['FN']) && count($candidate_logs_data['FN']) > 0)
                                    @foreach ($candidate_logs_data['FN'] as $log)
                                        <tr>
                                            <td>{{ $log['registration_number'] }}</td>
                                            <td>{{ $log['candidate_name'] }}</td>
                                            {{-- <td>FN</td> --}}
                                        </tr>
                                    @endforeach
                                @endif

                                <!-- If no candidates for both sessions -->
                                @if (
                                    (empty($candidate_logs_data['AN']) || count($candidate_logs_data['AN']) === 0) &&
                                        (empty($candidate_logs_data['FN']) || count($candidate_logs_data['FN']) === 0))
                                    <tr>
                                        <td colspan="3" class="text-center">No candidates available for either
                                            session.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>


    </div>
</div>
