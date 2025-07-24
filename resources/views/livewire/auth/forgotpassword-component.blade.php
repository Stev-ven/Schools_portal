<div>
    <form wire:submit.prevent='resetpassword' action="#!">
        <p class="text-center h3 " style="font-family:serif;">Reset your password</p>
        <div class="row gy-3 overflow-hidden">
            <div class="col-12">
                <div class="form-floating mb-3">
                    <input wire:model='email' type="email" class="form-control" name="email" id="email" placeholder="name@example.com">
                    <label for="email" class="form-label">Enter your email for password reset link</label>
                </div>
                <p class="text-danger">@error('email'){{ $message }}@enderror</p>
            </div>
            <div class="col-12" style="margin-bottom: 10px;">
                <div class="d-grid">
                    <button wire.loading.remove class="btn btn-dark btn-lg" type="submit"> <span wire:loading.remove>Submit</span>
                        <span wire:loading class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                        <span wire:loading role="status">Loading...</span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@script
    <script>

          $wire.on('notify', (message ) => {
            $('.show-alert').show();

            swal.fire(message.icon , message.message, message.color);

           {{-- setTimeout(function(){
                $(".show-alert").fadeOut(800);

            }, 4000); --}}
        });
    </script>
@endscript
