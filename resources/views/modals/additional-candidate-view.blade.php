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
                        @php
                            // Decode the JSON string into an associative array
                            $additionalData = json_decode($additionalCandidates->additional_candidates, true);

                            // Get the candidates array if it exists, otherwise use an empty array
                            $candidates = isset($additionalData['candidates']) ? $additionalData['candidates'] : [];
                        @endphp
                        <table class="table table-bordered">
                            <thead class="bg-light">
                                <tr>
                                    <th>Reg No</th>
                                    <th>Name</th>
                                    {{-- <th>Session</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($candidates as $candidate)
                                    <tr>
                                        <td>{{ $candidate['registration_number'] }}</td>
                                        <td>{{ $candidate['candidate_name'] }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2">No candidates found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>


    </div>
</div>
