<div>
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">School Details</h3>
        </div>
    </div>
<form class="row" wire:submit.prevent="editSchoolDetails" action="#!" style="margin-top: 20px;">
    <div class="col-md-6">
        <div class="form-group">
            <label class="form-control-label">School Name</label>
            <input type="text" class="form-control" wire:model="school_name">
            <p class="small text-muted">School name cannot be changed</p>
            @error('school_name') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="form-control-label">Suburb</label>
            <input type="text" class="form-control" wire:model="suburb">
            @error('suburb') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="form-control-label">Postal Address</label>
            <input type="text" class="form-control" wire:model="postal_address">
            @error('postal_address') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="form-control-label">Street Name</label>
            <input type="text" class="form-control" wire:model="streetname">
            @error('streetname') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="form-control-label">Landmark</label>
            <input type="text" class="form-control" wire:model="landmark">
            @error('landmark') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
    </div>
    <div class="kt-portlet__foot">

        <div class="kt-form__actions kt-align-right">
            <button type="button" class="btn btn-outline-dark btn-label-dark" wire:click="back">Back</button>
            <span>&nbsp;</span>
            <button type="submit" class="btn btn-outline-success btn-label-success">Submit & Continue</button>
        </div>

    </div>
</form>
</div>
@script

    <script>
        $wire.on('notify', (items) => {
            Swal.fire({
                title: "Details have been updated successfully!",
                text: "Proceed to proprietor details",
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

