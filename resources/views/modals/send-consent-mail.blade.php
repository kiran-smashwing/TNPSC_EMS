<div class="modal fade modal-animate anim-blur" id="sendConsentMailModel" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title"><i class="feather icon-mail me-2"></i>Send Consent Mail to Venues</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info" role="alert">
                    <i class="feather icon-info me-2"></i>
                    <strong>Important:</strong> Please review the selected venues before sending consent emails.
                </div>
                
                <div class="card mb-4">
                    <div class="card-header sticky-top bg-light">
                        <h6 class="mb-0"><strong>Selected Venues</strong></h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Venue Name</th>
                                    <th class="text-end">Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Venue A</td>
                                    <td class="text-end"><span class="badge bg-primary rounded-pill">1</span></td>
                                </tr>
                                <tr>
                                    <td>Venue B</td>
                                    <td class="text-end"><span class="badge bg-primary rounded-pill">2</span></td>
                                </tr>
                                <tr>
                                    <td>Venue C</td>
                                    <td class="text-end"><span class="badge bg-primary rounded-pill">3</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer text-end">
                        <a href="#" class="btn btn-sm btn-light-primary">
                            <i class="feather icon-eye me-1"></i>View All Venues
                        </a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><strong>Notes</strong></h6>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><i class="bi bi-check-circle text-success me-2"></i>Ensure all venue information is up-to-date.</li>
                        <li class="list-group-item"><i class="bi bi-check-circle text-success me-2"></i>Verify that no venue is duplicated.</li>
                        <li class="list-group-item"><i class="bi bi-check-circle text-success me-2"></i>Check that all contact details are accurate.</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="feather icon-x me-2"></i>Cancel
                </button>
                <button type="button" class="btn btn-primary" id="sendConsentMailBtn">
                    <i class="feather icon-navigation me-2"></i>Send Intimation
                </button>
            </div>
        </div>
    </div>
</div>