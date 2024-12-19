<div class="modal fade modal-animate" data-bs-backdrop="static" id="examMaterialsUploadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('exam-materials.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="exam_id" value="{{ $examId }}">
                <div class="modal-header">
                    <h5 class="modal-title">Upload Exam Materials Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                </div>
                <div class="modal-body">
                    <!-- File Upload Section -->
                    <div class="mb-4">
                        <label class="form-label" for="inputGroupFile01"><b>Upload File</b></label>
                        <div class="input-group">
                            <label class="input-group-text" for="inputGroupFile01">Upload</label>
                            <input name="csv_file" type="file" class="form-control" id="inputGroupFile01"
                                accept=".csv" required>
                        </div>
                    </div>
                    <!-- Information Section -->
                    <div class="mb-3">
                        <p class="mb-0"><b>1. Download Sample CSV:</b> Use the sample CSV file as a template for proper data structure.</p>
                        <a href="{{ route('exam-materials.download-sample-csv') }}" class="mb-2">Download Sample CSV</a>
                        <p class="mb-0"><b>2. Supported File Format:</b> Only .csv files are supported for upload. Ensure your file is saved with this extension.</p>
                        <p class="mb-0"><b>3. Column Names:</b> Do not alter the column names in the header row. They must match exactly with those in the sample file.</p>
                        <p class="mb-0"><b>4. Special Characters:</b> Avoid using special characters within the data fields to prevent parsing errors.</p>
                        <p class="mb-0"><b>5. Empty Fields:</b> If a column is not applicable, leave it blank but retain the column.</p>
                        <p class="mb-0"><b>6. Duplicate Entries:</b> Ensure there are no duplicate rows for data integrity.</p>
                        <p class="mb-0"><b>7. Required Fields:</b> Ensure the `qr_code` column is populated as it is mandatory.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary shadow-2">Upload Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
