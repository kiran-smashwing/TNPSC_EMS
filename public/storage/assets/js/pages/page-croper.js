"use strict";
(function () {
    document.addEventListener("DOMContentLoaded", function () {
        let croppr = null;
        const cropprElement = document.getElementById("croppr");
        const imageUpload = document.getElementById("imageUpload");
        const previewImage = document.getElementById("previewImage");
        const hiddenCroppedInput = document.getElementById("cropped_image");
        const cropperModal = document.getElementById("cropperModal");

        function initializeCroppr() {
            if (croppr) {
                croppr.destroy();
            }
            croppr = new Croppr(cropprElement, {
                aspectRatio: 1,
                startSize: [80, 80, "%"],
                onCropEnd: updatePreview,
            });
        }

        cropperModal.addEventListener("shown.bs.modal", function () {
            initializeCroppr();

            const saveCroppedBtn = document.getElementById("saveCroppedImage");
            if (saveCroppedBtn) {
                // Remove existing listener to prevent multiple attachments
                saveCroppedBtn.removeEventListener(
                    "click",
                    handleSaveCroppedImage
                );
                saveCroppedBtn.addEventListener(
                    "click",
                    handleSaveCroppedImage
                );
            } else {
                console.error("Save button not found");
            }
        });

        function handleSaveCroppedImage() {
            console.log("Save button clicked");
            if (!croppr) {
                console.error("Croppr not initialized.");
                return;
            }
            updatePreview();
            const modalInstance = bootstrap.Modal.getInstance(cropperModal);
            modalInstance.hide();
        }

        imageUpload.addEventListener("change", function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (event) {
                    cropprElement.src = event.target.result;
                    initializeCroppr();
                };
                reader.readAsDataURL(file);
            }
        });

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

                // Find all preview images on the page
                const previewImages =
                    document.querySelectorAll("#previewImage");
                previewImages.forEach((previewImage) => {
                    if (previewImage) {
                        previewImage.src = croppedImageDataURL;
                        previewImage.style.display = "block";
                    }
                });

                // Find all hidden input fields for cropped image
                const hiddenInputs =
                    document.querySelectorAll("#cropped_image");
                hiddenInputs.forEach((hiddenInput) => {
                    if (hiddenInput) {
                        hiddenInput.value = croppedImageDataURL;
                    }
                });
            };

            img.onerror = function () {
                console.error("Failed to load image for cropping.");
            };
        }
    });

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
