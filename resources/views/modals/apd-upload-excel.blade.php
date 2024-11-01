<div class="modal fade modal-animate" data-bs-backdrop="static" id="animateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
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
                        <input type="file" class="form-control" id="inputGroupFile01">
                    </div>
                </div>
           
                <!-- Information Section -->
                <div class="mb-3">
                    <p class="mb-0"><b>1. Download Sample CSV:</b>Before you proceed with uploading your data, download the sample CSV file provided. This file serves as a template, showing the required structure and format.</p>
                    <a href="#" class="mb-2">Download Sample CSV </a>
                    <p class="mb-0">
                        <b>2. Supported File Format:</b> Only.csv files are supported for upload. Ensure your file is saved with a .csv extension.
                       </p>
                       <p class="mb-0">
                    <b> 3. Column Names:</b> Do not alter the column names in the header row. They should match exactly with those in the sample file.
                       </p>
                          <p class="mb-0">
                        <b> 4. Special Characters:</b> Avoid using special characters (like commas, semicolons) within the data fields as they may cause parsing errors.
                            </p>
                            <p class="mb-0">
                            <b>  5. Empty Fields:</b> If a column is not applicable, ensure it is left blank but do not remove the column itself.
                               </p>
                               <p class="mb-0">
                            <b>6. Duplicate Entries:</b> Check for and remove any duplicate rows to ensure data integrity.
                               </p>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary shadow-2">Save changes</button>
            </div>
        </div>
    </div>
</div>
{{-- 1. Download Sample CSV: 
Before you proceed with uploading your data, download the sample CSV file provided. This file serves as a template, showing the required structure and format.
Download Sample CSV (Link to the sample file)
2. Supported File Format: Only .csv files are supported for upload. Ensure your file is saved with a .csv extension.
3. Column Names: Do not alter the column names in the header row. They should match exactly with those in the sample file.
4. Special Characters: Avoid using special characters (like commas, semicolons) within the data fields as they may cause parsing errors.
5. Empty Fields: If a column is not applicable, ensure it is left blank but do not remove the column itself.
6. Duplicate Entries: Check for and remove any duplicate rows to ensure data integrity. --}}