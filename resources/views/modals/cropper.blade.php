<div class="modal fade" id="cropperModal" tabindex="-1" aria-labelledby="cropperModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cropperModalLabel">Image Cropper</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="img-container">
                    <img id="croppr" 
                         src="{{ asset('storage/assets/images/light-box/l1.jpg') }}" 
                         alt="Picture" 
                         style="max-width: 100%; display: block;">
                </div>
                <div class="input-group mt-3">
                    <label class="input-group-text" for="imageUpload">Upload</label>
                    <input type="file" class="form-control" id="imageUpload" accept="image/*">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveCroppedImage">Save changes</button>
            </div>
        </div>
    </div>
</div>