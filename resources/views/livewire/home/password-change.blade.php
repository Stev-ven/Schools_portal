<div>
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        <div class="row mb-5">
            <div class="small-screen-size" style="width: 60%; margin: 0 auto">

                <!--begin::Portlet-->
                <div class="kt-portlet " style="margin: 0 auto">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Password Change
                            </h3>
                        </div>
                    </div>

                    <!--begin::Form-->
                    <form wire:submit.prevent='changePassword' class="kt-form">
                        <div class="kt-portlet__body">
                            <div class="form-group">
                                <label>Current Password</label>
                                <input wire:model='current_password' type="password" class="form-control" placeholder="Current password">
                                @error('current_password')<span class="form-text text-danger"> {{$message}} </span> @enderror
                            </div>
                            <div class="form-group">
                                <label>New Password</label>
                                <input wire:model='password' type="password" class="form-control" placeholder="New password">
                                @error('password')<span class="form-text text-danger"> {{$message}} </span> @enderror

                            </div>

                            <div class="form-group">
                                <label>Password confirmation</label>
                                <input wire:model='password_confirmation' type="password" class="form-control" placeholder="Password confirmation">
                                @error('password_confirmation')<span class="form-text text-danger"> {{$message}} </span> @enderror
                            </div>

                        </div>
                        <div class="kt-portlet__foot">
                            <div class="kt-form__actions kt-align-right">
                                <button type="submit" class="btn btn-outline-success btn-label-success"><span wire:loading.remove>Update Password</span>
                                    <span wire:loading class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                                    <span wire:loading role="status">Loading...</span>
                                </button>
                                {{-- <button type="reset" class="btn btn-secondary">Cancel</button> --}}
                            </div>
                        </div>
                    </form>

                    <!--end::Form-->
                </div>

                <!--end::Portlet-->


                <!--end::Portlet-->
            </div>
        </div>
    </div>

    @script

    <script>
        $wire.on('notify', (items) => {
            Swal.fire({
                title: "Details have been updated successfully!",
                text: "Proceed to upload documents",
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


