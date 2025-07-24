<div style="display: flex; justify-content: center; align-items: center;"
    class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid text-center">
    <div class="kt-subheader   kt-grid__item" id="kt_subheader">
        <div class="kt-container  kt-container--fluid ">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">
                    Applications for authorization to open a new school
                </h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
            </div>
            <div class="kt-subheader__toolbar">
                <button type="button" class="btn btn-outline-success btn-label-success" wire:click="nextSection">
                    <span wire:loading.remove wire:target="nextSection">Next Section</span>
                    <span wire:loading wire:target="nextSection" class="spinner-border spinner-border-sm"
                        aria-hidden="true"></span>
                    <div wire:loading wire:target="nextSection">Loading...</div>
                </button>
            </div>
        </div>
    </div>

    <div class="kt-portlet">
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-label">
                <h3 class="kt-portlet__head-title" style="font-weight: bold">Legal Registration</h3>
            </div>
        </div>
        {{-- <livewire:license.afadocument-component :application_id="$application_id" /> --}}
        <form class="kt-form" wire:submit.prevent="save" action="#!">
            <div class="kt-portlet__body"
                style="display: flex; justify-content: center; align-items: center; font-size: 20px;">
                <div class="form-group">
                    <div class="kt-portlet__head-label">
                        <p>Registrar's General Certificate</p>
                    </div>
                </div>



                <div class="row">
                    <div class="form-group" wire:ignore>
                        <label style="font-size: 20px">Certificate of Incorporation / Certificate to Commence
                            Business</label>
                        <x-file-upload label="Upload Document" model="certificate_of_incorporation" :certificatePreviewUrl="$certificatePreviewUrl" />



                        {{-- <Fieldset:disabled></Fieldset:disabled> --}}
                        <p class="text-danger" style="font-size: 12px;">
                            @error('certificate_of_incorporation')
                                {{ $message }}
                            @enderror
                        </p>
                        <small class="form-text text-muted">Please upload certificate (PDF, JPG files, max.
                            5MB).</small>
                    </div>

                </div>
                {{-- </div> --}}

                <div class="form-group">
                    <div class="kt-checkbox-list">
                        <label class="kt-checkbox kt-checkbox--success">
                            <input type="checkbox" wire:model="agreement" name="agreement"> I agree that the information
                            provided in this
                            application and the attachments therein,<br> are true to the best of my knowledge.
                            Further, I acknowledge that the submission <br>of false information shall render this
                            application
                            void.
                            <span></span>
                        </label>
                        <p class="text-danger" style="font-size: 12px;">
                            @error('agreement')
                                {{ $message }}
                            @enderror
                        </p>
                    </div>
                </div>
                <div class="kt-portlet__foot ">
                    <div class="kt-form__actions kt-align-right">
                        <button type="button" class="btn btn-outline-dark btn-label-dark" wire:click="back">Back</button>
                        <button type="submit" class="btn btn-outline-success btn-label-success">
                            <span wire:loading.remove wire:target="save">Save Document</span>
                            <span wire:loading wire:target="save" class="spinner-border spinner-border-sm"
                                aria-hidden="true"></span>
                            <div wire:loading wire:target="save">Saving...</div>
                        </button>
                    </div>
                </div>
        </form>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('js-profile-pic');
        const preview = document.getElementById('js-preview-image');
        const fallbackIcon = document.getElementById('js-fallback-icon');
        const fallbackText = document.getElementById('js-fallback-text');

        input.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                    fallbackIcon.classList.add('d-none');
                    if (fallbackText) fallbackText.classList.add('d-none');
                };
                reader.readAsDataURL(file);
            } else {
                preview.classList.add('d-none');
                fallbackIcon.classList.remove('d-none');
                if (fallbackText) fallbackText.classList.remove('d-none');
            }
        });
    });
</script>





@script
    <script>
        $wire.on('notify', (items) => {
            Swal.fire({
                title: "Application documents uploaded successfully.",
                text: "Proceed to submit application?",
                icon: "success",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes! Proceed",
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.dispatch('redirect');
                }
            });
        })
    </script>
@endscript
