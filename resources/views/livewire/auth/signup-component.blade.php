<div class="kt-portlet__body">

    <div class="row">
        <div class="col-md-12 faded-bg">
            <div class="kt-portlet__body">
                <img class="img-fluid rounded mb-4" loading="lazy"
                    src="{{ asset('assets/media/icons/nasia-logo-emblem.png') }}" width="60" height="30"
                    alt="Nasia Logo" style="margin: 20px;">
            </div>
            <div class="kt-portlet__body" style="margin: 0 10%;">
                <p class="text-center fs-2 custom-font-family" style="color: #000; margin 0 50px;">School Licensing and
                    Inspection Management System (SLIMS) Portal</p>
                {{-- <livewire:auth.signup-component /> --}}

                <div>
                    <form wire:submit.prevent='signup' action="#!">

                        <p class="text-center h3 " style="font-family:serif;">Register</p>

                        <div class="row gy-3 overflow-hidden">
                            <div class="col-12 col-md-6">
                                <label for="title" style="font-weight: bold;">Title</label>
                                <div class="form-floating mb-3">
                                    <select wire:model='title' class="form-control" name="title">
                                        <option value="">--Select title--</option>
                                        @foreach ($titles as $values => $label)
                                            <option value="{{ $values }}"> {{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <p class="text-danger">
                                    @error('title')
                                        {{ $message }}
                                    @enderror
                                </p>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="proprietor-first-name" style="font-weight: bold;">Proprietor First
                                    Name</label>
                                <div class="form-floating mb-3">
                                    <input wire:model='proprietorfirstname' type="text" class="form-control"
                                        name="proprietor-first-name" id="proprietor-first-name" value=""
                                        placeholder="Proprietor First Name">
                                </div>
                                <p class="text-danger">
                                    @error('proprietorfirstname')
                                        {{ $message }}
                                    @enderror
                                </p>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="proprietor-last-name" style="font-weight: bold;">Proprietor Last
                                    Name</label>
                                <div class="form-floating mb-3">
                                    <input wire:model='proprietorlastname' type="text" class="form-control"
                                        name="proprietor-last-name" id="proprietor-last-name" value=""
                                        placeholder="Proprietor Last Name">
                                </div>
                                <p class="text-danger">
                                    @error('proprietorlastname')
                                        {{ $message }}
                                    @enderror
                                </p>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="proprietor-other-name" style="font-weight: bold;">Proprietor Other
                                    Name(Optional)</label>
                                <div class="form-floating mb-3">
                                    <input wire:model='proprietorothername' type="text" class="form-control"
                                        name="proprietor-other-name" id="proprietor-other-name" value=""
                                        placeholder="Proprietor Other Name">
                                </div>
                                <p class="text-danger">
                                    @error('proprietorothername')
                                        {{ $message }}
                                    @enderror
                                </p>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="gender" style="font-weight: bold;">Gender</label>
                                <div class="form-floating mb-3">
                                    <select wire:model='gender' class="form-control" name="gender">
                                        <option value="">--Select Gender--</option>
                                        @foreach ($genders as $values => $label)
                                            <option value="{{ $values }}"> {{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <p class="text-danger">
                                    @error('gender')
                                        {{ $message }}
                                    @enderror
                                </p>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="phone-number" style="font-weight: bold;">Phone Number</label>
                                <div class="form-floating mb-3 d-flex">
                                    <select wire:model="countryCode" class="form-select" id="country-code"
                                        style="max-width: 120px;">
                                        <option value="+1">+1 (US)</option>
                                        <option value="+233">+233 (GH)</option>
                                        <option value="+91">+91 (IN)</option>
                                        <!-- Add more country codes as needed -->
                                        <p class="text-danger">
                                            @error('countryCode')
                                                {{ $message }}
                                            @enderror
                                        </p>

                                    </select>
                                    <span>&nbsp;</span>
                                    <input wire:model="phonenumber" type="text" class="form-control"
                                        id="phone-number" placeholder="Phone Number">
                                </div>
                                <p class="text-danger">
                                    @error('phonenumber')
                                        {{ $message }}
                                    @enderror
                                </p>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="email" style="font-weight: bold;">Email Address</label>
                                <div class="form-floating mb-3">
                                    <input wire:model='email' type="email" class="form-control" name="email"
                                        id="email-address" value="" placeholder="Email Address">
                                </div>
                                <p class="text-danger">
                                    @error('email')
                                        {{ $message }}
                                    @enderror
                                </p>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="password" style="font-weight: bold;">Password</label>
                                <div class="form-floating mb-3">
                                    <input wire:model='password' type="password" class="form-control"
                                        name="password" id="password" value="" placeholder="Password">
                                </div>
                                <p class="text-danger">
                                    @error('password')
                                        {{ $message }}
                                    @enderror
                                </p>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="repeat-password" style="font-weight: bold;">Repeat Password</label>
                                <div class="form-floating mb-3">
                                    <input wire:model='repeatpassword' type="password" class="form-control"
                                        name="repeatpassword" id="repeat-password" value=""
                                        placeholder="Repeat Password">
                                </div>
                                <p class="text-danger">
                                    @error('repeatpassword')
                                        {{ $message }}
                                    @enderror
                                </p>
                            </div>
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


                        </div>
                        {{-- <div class="d-grid col-6 col-md-6 mb-3 text-right">

                                    <div class="d-grid text-right">
                                        <a href="{{ route('login') }}"
                                        style="color: rgb(60, 60, 136); text-decoration: none; float: right; margin: 10px; font-size: 14px;">Login
                                        instead
                                    </a>
                                        <button wire.loading.remove class="btn btn-dark btn-lg" type="submit"> <span
                                                wire:loading.remove>Sign Up</span>
                                            <span wire:loading class="spinner-border spinner-border-sm"
                                                aria-hidden="true"></span>
                                            <span wire:loading role="status">Loading...</span>
                                        </button>
                                    </div>

                            </div> --}}
                        <div class="row">
    <div class="col-12 d-flex justify-content-center">
        <div class="col-12 col-md-6 mb-3">
            <div class="d-grid gap-2">
                <a href="{{ route('login') }}"
                   style="color: rgb(60, 60, 136); text-decoration: none; font-size: 14px; text-align: right;">
                    Login instead
                </a>

                <button wire:loading.remove class="btn btn-dark btn-lg" type="submit">
                    <span wire:loading.remove>Sign Up</span>
                    <span wire:loading class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                    <span wire:loading role="status">Loading...</span>
                </button>
            </div>
        </div>
    </div>
</div>



                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@push('styles')
    <style>
        .faded-bg {
            position: relative;
            background-image: url('{{ asset('assets/media/bg/slims.jpeg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-color: #fff;
            /* margin-bottom: 30px; */
            overflow: hidden;
        }

        .faded-bg::before {
            content: "";
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background-color: #fff;
            opacity: 0.9;
            /* Adjust fade strength */
            z-index: 1;
        }

        .faded-bg>* {
            position: relative;
            z-index: 2;
        }
    </style>
@endpush
