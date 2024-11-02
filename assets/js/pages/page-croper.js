"use strict";
(function () {
    document.addEventListener("DOMContentLoaded", function () {
        let croppr = null;
        const cropprElement = document.getElementById("croppr");
        const imageUpload = document.getElementById("imageUpload");
        const saveCroppedBtn = document.getElementById("saveCroppedImage");
        console.log("saveCroppedBtn:", saveCroppedBtn);
        const previewImage = document.getElementById("previewImage");
        const hiddenCroppedInput = document.getElementById("cropped_image");

        // Initialize Croppr with default image
        function initializeCroppr(src) {
            if (croppr) {
                croppr.destroy();
            }
            croppr = new Croppr(cropprElement, {
                aspectRatio: 1,
                startSize: [80, 80, "%"],
                onCropEnd: updatePreview,
            });
        }

        // Update Preview Image and Hidden Input
        function updatePreview() {
            if (!croppr) {
                console.error("Croppr not initialized.");
                return;
            }

            const cropData = croppr.getValue();
            const canvas = document.createElement("canvas");
            canvas.width = cropData.width;
            canvas.height = cropData.height;
            const ctx = canvas.getContext("2d");
            const img = new Image();
            img.src = cropprElement.src;

            img.onload = function () {
                ctx.drawImage(
                    img,
                    cropData.x,
                    cropData.y,
                    cropData.width,
                    cropData.height,
                    0,
                    0,
                    cropData.width,
                    cropData.height
                );
                const croppedImageDataURL = canvas.toDataURL("image/png");

                if (previewImage) {
                    previewImage.src = croppedImageDataURL;
                    previewImage.style.display = "block";
                }

                if (hiddenCroppedInput) {
                    hiddenCroppedInput.value = croppedImageDataURL;
                }
            };

            img.onerror = function () {
                console.error("Failed to load image for cropping.");
            };
        }

        // Handle Image Upload
        imageUpload.addEventListener("change", function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (event) {
                    cropprElement.src = event.target.result;
                    initializeCroppr(event.target.result);
                };
                reader.readAsDataURL(file);
            }
        });

        // Handle Save Cropped Image
        saveCroppedBtn.addEventListener("click", function () {
            if (!croppr) {
                console.error("Croppr not initialized.");
                return;
            }
            updatePreview();
            // Close the modal after saving
            const cropperModal = new bootstrap.Modal(
                document.getElementById("cropperModal")
            );
            cropperModal.hide();
        });

        // Initialize Croppr on default image load
        if (cropprElement && cropprElement.src) {
            initializeCroppr(cropprElement.src);
        } else {
            console.error("Croppr element or source not found.");
        }

        // Aspect Ratio
        var ratioCheckbox = document.getElementById("cb-ratio");
        var ratioInput = document.getElementById("input-ratio");

        ratioCheckbox.addEventListener("change", function (event) {
            if (!event.target.checked) {
                croppr.options.aspectRatio = null;
                ratioInput.disabled = true;
                ratioInput.classList.remove("is-danger");
                croppr.reset();
                return;
            }

            ratioInput.disabled = false;
            var value = ratioInput.value;
            if (!isNumber(value)) {
                if (value !== "") {
                    ratioInput.classList.add("is-danger");
                }
                return;
            } else {
                ratioInput.classList.remove("is-danger");
            }
            croppr.options.aspectRatio = Number(value);

            croppr.reset();
        });

        ratioInput.addEventListener("input", function (event) {
            if (!ratioCheckbox.checked) {
                return;
            }
            var value = ratioInput.value;
            if (!isNumber(value)) {
                ratioInput.classList.add("is-danger");
                return;
            } else {
                ratioInput.classList.remove("is-danger");
                value = Number(value);
                croppr.options.aspectRatio = value;
                croppr.reset();
            }
        });

        // Maximum size
        var maxCheckbox = document.getElementById("max-checkbox");
        var maxInputs = [
            document.getElementById("max-input-width"),
            document.getElementById("max-input-height"),
            document.getElementById("max-input-unit"),
        ];

        maxCheckbox.addEventListener("change", function (event) {
            if (!event.target.checked) {
                croppr.options.maxSize = {
                    width: null,
                    height: null,
                };
                maxInputs.map(function (el) {
                    el.disabled = true;
                    el.classList.remove("is-danger");
                });
                croppr.reset();
                return;
            } else {
                maxInputs.map(function (el) {
                    el.disabled = false;
                });
            }

            var values = maxInputs.map(parseElementValues);
            croppr.options.maxSize = {
                width: Number(values[0]),
                height: Number(values[1]),
                unit: values[2],
            };
            croppr.reset();
        });

        maxInputs.map(function (el) {
            el.addEventListener(
                "input",
                handleChange(croppr, "maxSize", maxInputs)
            );
        });

        // Minimum size
        var minCheckbox = document.getElementById("min-checkbox");
        var minInputs = [
            document.getElementById("min-input-width"),
            document.getElementById("min-input-height"),
            document.getElementById("min-input-unit"),
        ];

        minCheckbox.addEventListener("change", function (event) {
            if (!event.target.checked) {
                croppr.options.minSize = {
                    width: null,
                    height: null,
                };
                minInputs.map(function (el) {
                    el.disabled = true;
                    el.classList.remove("is-danger");
                });
                croppr.reset();
                return;
            } else {
                minInputs.map(function (el) {
                    el.disabled = false;
                });
            }

            var values = minInputs.map(parseElementValues);
            croppr.options.minSize = {
                width: Number(values[0]),
                height: Number(values[1]),
                unit: values[2],
            };
            croppr.reset();
        });

        minInputs.map(function (el) {
            el.addEventListener(
                "input",
                handleChange(croppr, "minSize", minInputs)
            );
        });

        var value = croppr.getValue();
        updateValue(value.x, value.y, value.width, value.height);
    });

    /** Functions */
    function updateValue(x, y, w, h) {
        document.getElementById("valX").innerHTML =
            '<strong class="font-weight-bold">x : </strong>&nbsp;' + x;
        document.getElementById("valY").innerHTML =
            '<strong class="font-weight-bold">y : </strong>&nbsp;' + y;
        document.getElementById("valW").innerHTML =
            '<strong class="font-weight-bold">width : </strong>&nbsp;' + w;
        document.getElementById("valH").innerHTML =
            '<strong class="font-weight-bold">height : </strong>&nbsp;' + h;
    }

    // check number
    function isNumber(value) {
        if (isNaN(parseInt(value))) {
            return false;
        }
        if (value === "") {
            return false;
        }
        return true;
    }

    // parce value
    function parseElementValues(element) {
        var value = element.value;
        if (element.tagName !== "SELECT") {
            if (!isNumber(value)) {
                if (value !== "") {
                    element.classList.add("is-danger");
                }
                return null;
            } else {
                element.classList.remove("is-danger");
                return value;
            }
        } else {
            return value;
        }
    }
    //  change event
    function handleChange(croppr, option, elements) {
        return function () {
            var values = elements.map(parseElementValues);
            croppr.options[option] = {
                width: Number(values[0]),
                height: Number(values[1]),
                unit: values[2],

                // Convert to pixels
            };
            if (values[2] === "%") {
                croppr.options.convertToPixels(croppr.cropperEl);
            }

            croppr.reset();
        };
    }
})();
