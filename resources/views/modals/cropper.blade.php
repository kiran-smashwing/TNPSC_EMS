<div class="modal fade" id="cropperModal" tabindex="-1" aria-labelledby="cropperModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="cropperModalLabel">Image Cropper</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"
                aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="col-sm-12">
                <!-- Image cropper plugin start -->

                <div class="container">
                    <div class="row">
                        <div class="col-md-7 mb-3 mb-md-0">
                            <div class="cropper">
                                <img src="{{ asset('storage/assets/images/light-box/l1.jpg')}}" alt="image"
                                    id="croppr" />
                            </div>
                            <div class="input-group mb-3">
                                <label class="input-group-text" for="imageUpload">Upload
                                    Image</label>
                                <input type="file" class="form-control" id="imageUpload"
                                    accept="image/*">
                            </div>


                        </div>
                        <div class="col-md-5">
                            <div class="rounded bg-light px-4 py-3 mb-3">
                                <h5>Selection value</h5>
                                <div class="row">
                                    <div class="col-6">
                                        <p id="valX"><strong>x: </strong>&nbsp;500</p>
                                        <p class="mb-1" id="valY"><strong>y:
                                            </strong>&nbsp;500</p>
                                    </div>
                                    <div class="col-6">
                                        <p id="valW"><strong>width: </strong>&nbsp;500</p>
                                        <p class="mb-1" id="valH"><strong>height:
                                            </strong>&nbsp;500</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col">
                                    <h6>Aspect Ratio</h6>
                                </div>
                                <div class="col-auto">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            value="" id="cb-ratio" />
                                        <label class="form-check-label" for="cb-ratio"> Enable
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text">A</span>
                                <input type="text" class="form-control" id="input-ratio"
                                    value="1.0" disabled="disabled" />
                            </div>
                            <div class="row mb-1">
                                <div class="col">
                                    <h6>Maximum size</h6>
                                </div>
                                <div class="col-auto">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            value="" id="max-checkbox" />
                                        <label class="form-check-label" for="max-checkbox">
                                            Enable </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-1 g-sm-3 mb-4">
                                <div class="col-4">
                                    <div class="input-group">
                                        <span class="input-group-text">W</span>
                                        <input type="text" class="form-control"
                                            id="max-input-width" value="150"
                                            disabled="disabled" />
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="input-group">
                                        <span class="input-group-text">H</span>
                                        <input type="text" class="form-control"
                                            id="max-input-height" value="150"
                                            disabled="disabled" />
                                    </div>
                                </div>
                                <div class="col-4">
                                    <select id="max-input-unit" disabled="disabled"
                                        class="form-control">
                                        <option>px</option>
                                        <option value="%">%</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-1">
                                <div class="col">
                                    <h6>Minimum size</h6>
                                </div>
                                <div class="col-auto">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            value="" id="min-checkbox" />
                                        <label class="form-check-label" for="min-checkbox">
                                            Enable </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-1 g-sm-3">
                                <div class="col-4">
                                    <div class="input-group">
                                        <span class="input-group-text">W</span>
                                        <input type="text" class="form-control"
                                            id="min-input-width" value="150"
                                            disabled="disabled" />
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="input-group">
                                        <span class="input-group-text">H</span>
                                        <input type="text" class="form-control"
                                            id="min-input-height" value="150"
                                            disabled="disabled" />
                                    </div>
                                </div>
                                <div class="col-4">
                                    <select id="min-input-unit" disabled="disabled"
                                        class="form-control">
                                        <option> px </option>
                                        <option value="%"> % </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Image cropper plugin end -->
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary"
                data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="saveCroppedImage">Save
                changes</button>
        </div>
    </div>
</div>
</div>