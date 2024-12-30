<!-- View Multiple Replacement Details Modal -->
<div class="modal fade modal-animate" data-bs-backdrop="static" id="viewReplacementModal" tabindex="-1"
    aria-labelledby="viewReplacementModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title text-primary" id="viewReplacementModalLabel">
                    <i class="feather icon-eye me-2"></i>View Replacement Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Dynamic Replacement Details -->
                @if (!empty($cipaperreplacement_data))
                    @foreach (['FN', 'AN'] as $sessionType)
                        @php
                            // Filter replacements by session type
                            $filteredReplacements = collect($cipaperreplacement_data)->where(
                                'exam_session',
                                $sessionType,
                            );
                        @endphp

                        @if ($filteredReplacements->isNotEmpty())
                            <h5 class="text-muted">Session: {{ $sessionType }}</h5>
                            <hr>

                            @foreach ($filteredReplacements as $replacement)
                                <div class="candidate-details mb-4">
                                    <h6 class="text-muted">Registration Number:</h6>
                                    <p class="fw-bold">{{ $replacement->registration_number ?? 'N/A' }}</p>

                                    <h6 class="text-muted">Replacement Type:</h6>
                                    <p class="fw-bold">{{ $replacement->replacement_type ?? 'N/A' }}</p>

                                    @if (!empty($replacement->old_paper_number))
                                        <h6 class="text-muted">Old Paper Number:</h6>
                                        <p class="fw-bold">{{ $replacement->old_paper_number }}</p>
                                    @endif

                                    <h6 class="text-muted">New Paper Number:</h6>
                                    <p class="fw-bold">{{ $replacement->new_paper_number ?? 'N/A' }}</p>

                                    <h6 class="text-muted">Reason for Replacement:</h6>
                                    <p class="fw-bold">{{ $replacement->replacement_reason ?? 'N/A' }}</p>
                                </div>
                                <!-- Divider -->
                                <hr>
                            @endforeach
                        {{-- @else
                            <p class="text-muted">No replacements found for the {{ $sessionType }} session.</p> --}}
                        @endif
                    @endforeach
                @else
                    <p class="text-muted">No replacement data available.</p>
                @endif

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" data-bs-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
