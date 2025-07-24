<div>
    <form wire:submit.prevent='login' action="#!">
        <p class="text-center h3 " style="font-family:serif;">Login</p>
        @if (session('message'))
            @if (session('message_type') == 'success')
                <div class="alert alert-success">
                  {{ session('message') }}
                </div>
            @else
                <div class="alert alert-danger">
                    {{ session('message') }}
                </div>
            @endif
        @endif
        <div class="row gy-3 overflow-hidden">
            <div class="col-12">
                <div class="form-floating mb-3">
                    <input wire:model='email' type="email" class="form-control" name="email" id="email" placeholder="name@example.com">
                    <label for="email" class="form-label">Email</label>
                </div>
                <p class="text-danger">@error('email'){{ $message }}@enderror</p>
            </div>
            <div class="col-12">
                <div class="form-floating mb-3">
                    <input wire:model='password' type="password" class="form-control" name="password" id="password" value="" placeholder="Password">
                    <label for="password" class="form-label">Password</label>
                </div>
                <p class="text-danger">@error('password'){{ $message }}@enderror </p>

            </div>

            <div class="col-12">
                <p>Don't have an account? <a href="{{ route('signup')}}" style="color: rgb(60, 60, 136); text-decoration: none; font-size: 14px;">Create an account</a>
                </p>
                <div class="d-grid">
                    <button wire.loading.remove class="btn btn-dark btn-lg" type="submit"> <span wire:loading.remove>Log in</span>
                        <span wire:loading class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                        <span wire:loading role="status">Loading...</span>
                    </button>
                </div>
                <a href="{{ route('forgotpassword')}}" style="color: rgb(60, 60, 136); text-decoration: none; float: right; margin: 10px; font-size: 14px;">Forgot Password?</a>
            </div>
        </div>
    </form>
</div>
