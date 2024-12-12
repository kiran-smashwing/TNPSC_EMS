<div class="modal fade modal-animate" data-bs-backdrop="static" id="animateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('apd-candidates.upload-candidates-csv') }}" method="POST" enctype="multipart/form-data">
                @csrf
               
                <input type="hidden" name="exam_id" value="{{ $session->exam_main_no }}">
                <div class="modal-header">
                    <h5 class="modal-title">Tentative Candidates</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                </div>
                <div class="modal-body">
                    <!-- File Upload Section -->
                    <div class="mb-4">
                        <label class="form-label" for="inputGroupFile01"><b>Upload File</b></label>
                        <div class="input-group">
                            <label class="input-group-text" for="inputGroupFile01">Upload</label>
                            <input name="csv_file" type="file" class="form-control" id="inputGroupFile01" accept=".csv">
                        </div>
                    </div>

                    <!-- Information Section -->
                    <div class="mb-3">
                        <p class="mb-0"><b>1. Download Sample CSV:</b>Before you proceed with uploading your data,
                            download the sample CSV file provided. This file serves as a template, showing the required
                            structure and format.</p>
                        <a href="{{ route('apd-candidates.download-sample-csv') }}" class="mb-2">Download Sample CSV
                        </a>
                        <p class="mb-0">
                            <b>2. Supported File Format:</b> Only.csv files are supported for upload. Ensure your file
                            is
                            saved with a .csv extension.
                        </p>
                        <p class="mb-0">
                            <b> 3. Column Names:</b> Do not alter the column names in the header row. They should match
                            exactly with those in the sample file.
                        </p>
                        <p class="mb-0">
                            <b> 4. Special Characters:</b> Avoid using special characters (like commas, semicolons)
                            within
                            the data fields as they may cause parsing errors.
                        </p>
                        <p class="mb-0">
                            <b> 5. Empty Fields:</b> If a column is not applicable, ensure it is left blank but do not
                            remove the column itself.
                        </p>
                        <p class="mb-0">
                            <b>6. Duplicate Entries:</b> Check for and remove any duplicate rows to ensure data
                            integrity.
                        </p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary shadow-2">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
