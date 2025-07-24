@props([
    'label',
    'model',
    'accept' => 'application/pdf,image/jpg,image/jpeg,image/png',
    'certificatePreviewUrl' => null,
])
@if ($certificatePreviewUrl ?? false)
    @php
        $extension = pathinfo($certificatePreviewUrl, PATHINFO_EXTENSION);
        $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']);
    @endphp
    @if ($isImage)
        <div class="mt-2 text-center">
            <a href="{{ $certificatePreviewUrl }}" target="_blank" class="btn btn-sm btn-outline-primary">
                View Existing File
            </a>
        </div>
    @else
        <div class="mt-2 text-center">
            <a href="{{ $certificatePreviewUrl }}" target="_blank" class="btn btn-sm btn-outline-primary">
                View Existing File
            </a>
        </div>
    @endif
@endif
<div class="container mt-4">
    <div class="card border-secondary mb-3 text-center bg-light"
        style="height: 70px; position: relative; overflow: hidden; border-radius: 15px;">
        <img id="js-preview-image-{{ $model }}" src="" alt=""
            class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover d-none" />

        <div id="js-fallback-icon-{{ $model }}" class="d-flex align-items-center justify-content-center h-100">
            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor"
                class="bi bi-cloud-arrow-up" viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                    d="M7.646 5.146a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1-.708.708L8.5 6.707V10.5a.5.5 0 0 1-1 0V6.707L6.354 7.854a.5.5 0 1 1-.708-.708z" />
                <path d="M4.406 3.342A5.53 5.53 0 0 1 8 2c2.69 0 4.923 2 5.166 4.579C14.758 6.804 16 8.137 16 9.773
                    16 11.569 14.502 13 12.687 13H3.781C1.708 13 0 11.366 0 9.318c0-1.763
                    1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383m.653.757c-.757.653-1.153
                    1.44-1.153 2.056v.448l-.445.049C2.064 6.805 1 7.952 1 9.318 1 10.785 2.23 12 3.781
                    12h8.906C13.98 12 15 10.988 15 9.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188
                    4.825 10.328 3 8 3a4.53 4.53 0 0 0-2.941 1.1z" />
            </svg>
        </div>


    </div>


   

    <div class="text-center cursor-pointer">
        <label class="btn btn-outline-dark btn-label-dark cursor-pointer">
            <!-- This span shows only when NOT loading -->
            <span wire:loading.remove wire:target="{{ $model }}">{{ $label }}</span>

            <!-- The file input -->
            <input type="file" id="js-profile-pic-{{ $model }}" wire:model.live="{{ $model }}"
                wire:loading.attr="disabled" wire:target="{{ $model }}" class="d-none cursor-pointer"
                accept="{{ $accept }}">

            <!-- This spinner shows only when loading -->
            <span wire:loading wire:target="{{ $model }}" class="spinner-border spinner-border-sm"
                aria-hidden="true"></span>
            <div wire:loading wire:target="{{ $model }}">Uploading...</div>
        </label>
    </div>




    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('js-profile-pic-{{ $model }}');
            const fallbackIcon = document.getElementById('js-fallback-icon-{{ $model }}');

            // Create a text element for the file name
            const fileNameText = document.createElement('p');
            fileNameText.className = "text-muted mt-2 file-name-text";
            fallbackIcon.appendChild(fileNameText);

            if (input) {
                input.addEventListener('change', function() {
                    const file = this.files[0];
                    const iconSvg = fallbackIcon.querySelector('svg');

                    if (file) {
                        // Show the file name
                        fileNameText.textContent = file.name;

                        // Hide the SVG icon but keep the container visible
                        if (iconSvg) iconSvg.classList.add('d-none');
                    } else {
                        // No file selected: clear the name and show the icon again
                        fileNameText.textContent = '';
                        if (iconSvg) iconSvg.classList.remove('d-none');
                    }
                });
            }
        });
    </script>


</div>
