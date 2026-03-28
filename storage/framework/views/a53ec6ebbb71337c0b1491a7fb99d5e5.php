<!-- Image Cropping Modal Partial -->
<div id="cropper-modal" class="fixed inset-0 z-[60] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog"
    aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background backdrop -->
        <div class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" aria-hidden="true"
            onclick="closeCropperModal()"></div>

        <!-- Modal panel -->
        <div
            class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white dark:bg-slate-900 rounded-2xl shadow-2xl sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-slate-200 dark:border-white/10">
            <div class="px-6 py-4 border-b border-slate-100 dark:border-white/5 flex items-center justify-between">
                <h3 class="text-lg font-bold text-slate-800 dark:text-white" id="modal-title">
                    Adjust Your Profile Picture
                </h3>
                <button type="button" onclick="closeCropperModal()"
                    class="text-slate-400 hover:text-slate-500 dark:hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <div class="p-6">
                <!-- Cropping Area -->
                <div class="relative w-full overflow-hidden bg-slate-100 dark:bg-slate-800 rounded-xl mb-6 flex items-center justify-center"
                    style="height: 55vh; max-height: 500px;">
                    <img id="cropper-image" src="" alt="To be cropped" class="max-w-full max-h-full block">
                </div>

                <!-- Controls -->
                <div class="flex flex-wrap items-center justify-center gap-3 mb-6">
                    <button type="button" onclick="cropper.zoom(0.1)"
                        class="p-2.5 bg-slate-100 dark:bg-white/5 rounded-xl hover:bg-slate-200 dark:hover:bg-white/10 text-slate-700 dark:text-white transition-all group"
                        title="Zoom In">
                        <svg class="w-5 h-5 group-active:scale-90 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                        </svg>
                    </button>
                    <button type="button" onclick="cropper.zoom(-0.1)"
                        class="p-2.5 bg-slate-100 dark:bg-white/5 rounded-xl hover:bg-slate-200 dark:hover:bg-white/10 text-slate-700 dark:text-white transition-all group"
                        title="Zoom Out">
                        <svg class="w-5 h-5 group-active:scale-90 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM13 10H7"></path>
                        </svg>
                    </button>
                    <div class="w-px h-6 bg-slate-200 dark:bg-white/10 mx-1"></div>
                    <button type="button" onclick="cropper.rotate(-90)"
                        class="p-2.5 bg-slate-100 dark:bg-white/5 rounded-xl hover:bg-slate-200 dark:hover:bg-white/10 text-slate-700 dark:text-white transition-all group"
                        title="Rotate Left">
                        <svg class="w-5 h-5 group-active:scale-90 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </button>
                    <button type="button" onclick="cropper.rotate(90)"
                        class="p-2.5 bg-slate-100 dark:bg-white/5 rounded-xl hover:bg-slate-200 dark:hover:bg-white/10 text-slate-700 dark:text-white transition-all group"
                        title="Rotate Right">
                        <svg class="w-5 h-5 group-active:scale-90 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </button>
                    <div class="w-px h-6 bg-slate-200 dark:bg-white/10 mx-1"></div>
                    <button type="button" onclick="cropper.reset()"
                        class="p-2.5 bg-slate-100 dark:bg-white/5 rounded-xl hover:bg-slate-200 dark:hover:bg-white/10 text-slate-700 dark:text-white transition-all group"
                        title="Reset">
                        <svg class="w-5 h-5 group-active:rotate-180 transition-transform duration-500" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                            </path>
                        </svg>
                    </button>
                    <div class="w-px h-6 bg-slate-200 dark:bg-white/10 mx-1"></div>
                    <button type="button" id="formalize-btn" onclick="formalizeImage()"
                        class="px-4 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-emerald-500/20 transition-all flex items-center space-x-2 animate-pulse"
                        title="AI Background Removal">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        <span>Passport Mode</span>
                    </button>
                </div>
            </div>

            <div
                class="px-6 py-4 bg-slate-50 dark:bg-white/5 flex flex-col-reverse sm:flex-row sm:justify-end gap-3 rounded-b-2xl">
                <button type="button" onclick="closeCropperModal()"
                    class="w-full sm:w-auto px-6 py-2.5 text-sm font-bold text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white transition-all bg-white dark:bg-transparent border border-slate-200 dark:border-white/10 rounded-xl">
                    Cancel
                </button>
                <button type="button" id="apply-crop"
                    class="w-full sm:w-auto px-8 py-2.5 text-sm font-bold text-white bg-accent hover:bg-accent/90 rounded-xl shadow-lg shadow-accent/20 transition-all">
                    Apply Crop
                </button>
            </div>
        </div>
    </div>
</div>



<script>
    let cropper;
    let currentInput;
    let currentPreview;
    let isApplyingCrop = false;


    function initCropper(inputElement, previewElement) {
        // If we are currently applying a crop, don't re-initialize the modal
        if (isApplyingCrop) return;

        currentInput = inputElement;
        currentPreview = previewElement;

        const file = inputElement.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (e) {
            const image = document.getElementById('cropper-image');

            // Destroy existing cropper if any
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }

            // Set up the load listener BEFORE setting the source to avoid race conditions
            image.onload = function () {
                document.getElementById('cropper-modal').classList.remove('hidden');

                // Use a small delay to ensure modal is visible for Cropper.js calculations
                setTimeout(() => {
                    if (cropper) {
                        cropper.destroy();
                        cropper = null;
                    }

                    cropper = new Cropper(image, {
                        aspectRatio: 1,
                        viewMode: 1,
                        autoCropArea: 1,
                        dragMode: 'move',
                        responsive: true,
                        restore: false,
                        guides: true,
                        center: true,
                        highlight: false,
                        cropBoxMovable: true,
                        cropBoxResizable: true,
                        toggleDragModeOnDblclick: false,
                        ready: function () {
                            console.log('Cropper ready');
                        }
                    });
                }, 50);

                image.onload = null; // Clean up the listener
            };

            // Also handle load errors
            image.onerror = function () {
                console.error('Failed to load image for cropping');
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load the image. Please try another file.',
                    ...getSwalConfig()
                });
                image.onerror = null;
            };

            image.src = e.target.result;
        };

        reader.onerror = function () {
            console.error('FileReader error');
            reader.onerror = null;
        };

        reader.readAsDataURL(file);
    }

    function closeCropperModal() {
        document.getElementById('cropper-modal').classList.add('hidden');
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        // Don't reset isApplyingCrop here, let the caller handle it 
        // to prevent race conditions during state transitions
    }

    document.getElementById('apply-crop').addEventListener('click', function () {
        if (!cropper) return;

        const applyBtn = this;
        const originalText = applyBtn.innerHTML;
        applyBtn.disabled = true;
        applyBtn.innerHTML = '<span class="flex items-center gap-2 justify-center"><svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Generating...</span>';

        const canvas = cropper.getCroppedCanvas({
            width: 800, // Higher resolution for better quality
            height: 800,
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high',
        });

        if (!canvas) {
            console.error('Canvas could not be generated');
            applyBtn.disabled = false;
            applyBtn.innerHTML = originalText;
            return;
        }

        canvas.toBlob(function (blob) {
            if (!blob) {
                console.error('Blob could not be generated');
                applyBtn.disabled = false;
                applyBtn.innerHTML = originalText;
                return;
            }

            // 1. Set the flag to prevent re-opening
            isApplyingCrop = true;

            // 2. Update Preview
            if (currentPreview) {
                const url = URL.createObjectURL(blob);
                currentPreview.src = url;
                currentPreview.classList.remove('hidden');

                const initialsDiv = currentPreview.parentElement.querySelector('.initials-placeholder');
                if (initialsDiv) initialsDiv.classList.add('hidden');
            }

            // 3. Update File Input
            try {
                const fileName = (currentInput.files && currentInput.files[0]) ? currentInput.files[0].name : 'profile_picture.jpg';
                const croppedFile = new File([blob], fileName, { type: 'image/jpeg' });
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(croppedFile);
                currentInput.files = dataTransfer.files;

                // 4. Close Modal
                closeCropperModal();

                // 5. Trigger Change Event (Caught by isApplyingCrop)
                const event = new Event('change', { bubbles: true });
                currentInput.dispatchEvent(event);
            } catch (err) {
                console.error('Error updating file input:', err);
            }

            // 6. Final cleanup after events have finished processing
            setTimeout(() => {
                isApplyingCrop = false;
                applyBtn.disabled = false;
                applyBtn.innerHTML = originalText;
            }, 500);

        }, 'image/jpeg', 0.85); // 0.85 quality is a good balance
    });
    function handlePreviewClick(inputId, previewId) {
        const input = document.getElementById(inputId);
        const preview = document.getElementById(previewId);

        // If a file is already selected client-side, re-open the cropper
        if (input && input.files && input.files[0]) {
            initCropper(input, preview);
        } else if (preview && preview.src && !preview.src.endsWith('#')) {
            // If it's an existing image (either from server or previously cropped)
            const image = document.getElementById('cropper-image');

            // Set up the load listener
            image.onload = function () {
                document.getElementById('cropper-modal').classList.remove('hidden');
                setTimeout(() => {
                    if (cropper) cropper.destroy();
                    cropper = new Cropper(image, {
                        aspectRatio: 1,
                        viewMode: 1,
                        autoCropArea: 1,
                        dragMode: 'move',
                        responsive: true,
                        restore: false,
                        guides: true,
                        center: true,
                        highlight: false,
                        cropBoxMovable: true,
                        cropBoxResizable: true,
                        toggleDragModeOnDblclick: false,
                    });
                }, 50);
                image.onload = null;
            };

            // Trigger load from current preview source
            image.src = preview.src;
            currentInput = input;
            currentPreview = preview;
        } else {
            // Otherwise, trigger the file browser
            if (input) input.click();
        }
    }

    async function formalizeImage() {
        if (!cropper) return;

        const formalBtn = document.getElementById('formalize-btn');
        const originalContent = formalBtn.innerHTML;

        try {
            formalBtn.disabled = true;
            formalBtn.innerHTML = `
                <div class="flex items-center space-x-2">
                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="ml-2">AI Working...</span>
                </div>
            `;

            if (typeof SelfieSegmentation === 'undefined') {
                throw new Error('AI library failed to load. Please check your internet or refresh the page.');
            }

            const canvas = cropper.getCroppedCanvas();
            const selfieSegmentation = new SelfieSegmentation({
                locateFile: (file) => `https://cdn.jsdelivr.net/npm/@mediapipe/selfie_segmentation/${file}`
            });

            selfieSegmentation.setOptions({
                modelSelection: 1, // High quality person segmentation
                selfieMode: false,
            });

            const resultPromise = new Promise((resolve) => {
                selfieSegmentation.onResults((results) => {
                    const finalCanvas = document.createElement('canvas');
                    finalCanvas.width = results.image.width;
                    finalCanvas.height = results.image.height;
                    const ctx = finalCanvas.getContext('2d');

                    // 1. Create the person layer with transparent background
                    const personCanvas = document.createElement('canvas');
                    personCanvas.width = finalCanvas.width;
                    personCanvas.height = finalCanvas.height;
                    const pctx = personCanvas.getContext('2d');

                    pctx.drawImage(results.segmentationMask, 0, 0, personCanvas.width, personCanvas.height);
                    pctx.globalCompositeOperation = 'source-in'; // Mask the image
                    pctx.drawImage(results.image, 0, 0);

                    // 2. Composite onto the blue background
                    ctx.fillStyle = '#3568b2'; // Pro Passport Blue
                    ctx.fillRect(0, 0, finalCanvas.width, finalCanvas.height);
                    ctx.drawImage(personCanvas, 0, 0);

                    resolve(finalCanvas.toDataURL('image/jpeg', 0.95));
                });
            });

            await selfieSegmentation.send({ image: canvas });
            const finalDataUrl = await resultPromise;

            cropper.replace(finalDataUrl);
            await selfieSegmentation.close();

            Swal.fire({
                icon: 'success',
                title: 'Done!',
                text: 'Background removed successfully.',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000
            });

        } catch (error) {
            console.error('AI Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Smooth Workflow Interrupted',
                text: error.message || 'The AI encountered a temporary issue. Please try again.',
                confirmButtonColor: '#3C50E0'
            });
        } finally {
            formalBtn.disabled = false;
            formalBtn.innerHTML = originalContent;
        }
    }
</script>

<style>
    /* Ensure cropper aspect ratio is maintained and responsive */
    .cropper-container {
        width: 100% !important;
    }

    .cropper-view-box,
    .cropper-face {
        border-radius: 12px;
    }
</style><?php /**PATH C:\xampp\htdocs\HF\resources\views/layouts/partials/image_cropper.blade.php ENDPATH**/ ?>