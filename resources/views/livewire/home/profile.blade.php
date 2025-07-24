<div>
     <div class="kt-subheader   kt-grid__item" id="kt_subheader">
        <div class="kt-container  kt-container--fluid ">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">
                    Profile Settings
                </h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>


            </div>
        </div>
    </div>
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        <div class="row">
            <div class="col-lg-12">

                <!--begin::Portlet-->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                
                            </h3>
                        </div>
                    </div>

                    <!--begin::Form-->
                    <form wire:submit.prevent="updateProfile" class="kt-form kt-form--label-right">
                        <div class="kt-portlet__body">
                            <div class="form-group row">


                                <div class="col-lg-6">
                                    <label for="title">Title</label>
                                    <div class="form-control">
                                        <select wire:model='person_title' class="form-control" name="title">
                                         <option value="">--Select title--</option>
                                         @foreach ($person_titles as $values =>$label)
                                         <option value="{{$values}}"> {{$label}}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    <p class="text-danger">@error('person_title'){{ $message }}@enderror</p>

                                </div>
                                <div class="col-lg-6">
                                    <label for="gender">Gender</label>
                                    <div class="form-control">
                                        <select wire:model='person_gender' class="form-control" name="gender">
                                         <option value="">--Select Gender--</option>
                                         @foreach ($person_genders as $values =>$label)
                                         <option value="{{$values}}"> {{$label}}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    <p class="text-danger">@error('person_gender'){{ $message }}@enderror</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label>First Name:</label>
                                    <input wire:model='first_name' type="text" class="form-control">
                                    {{-- <span class="form-text text-muted">Enter your first name</span> --}}
                                    @error('first_name') <span class="form-text text-danger">{{$message}} @enderror
                                </div>


                                <div class="col-lg-6">
                                    <label>Last Name:</label>
                                    <input wire:model='last_name' type="text" class="form-control">
                                    {{-- <span class="form-text text-muted">Enter your Last name</span> --}}
                                    @error('last_name') <span class="form-text text-danger">{{$message}} @enderror

                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label>Other Names:</label>
                                    <input wire:model='other_names' type="text" class="form-control">
                                    {{-- <span class="form-text text-muted">Enter your other names</span> --}}
                                    @error('other_names') <span class="form-text text-danger">{{$message}} @enderror

                                </div>

                                <div class="col-lg-6">

                                        <label for="phone-number">Mobile Number</label>
                                        <div class="form-floating mb-3 d-flex">
                                            <select wire:model="mobile_number_country_number" class="form-select" id="country-code" style="max-width: 120px;">
                                                <option value="+1">+1 (US)</option>
                                                <option value="+233">+233 (GH)</option>
                                                <option value="+91">+91 (IN)</option>
                                                <p class="text-danger">@error('mobile_number_country_number'){{ $message }}@enderror</p>

                                            </select>
                                            <span>&nbsp;</span>
                                            <input wire:model="mobile_number" type="text" class="form-control" id="phone-number" placeholder="Phone Number">
                                        </div>
                                        <p class="text-danger">@error('mobile_number'){{ $message }}@enderror</p>

                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label class="">Email Address:</label>
                                    <input wire:model='email' type="email"  class="form-control" placeholder="Enter Email number" readonly>
                                    <span class="form-text text-danger ">Email address cannot be updated</span>
                                    @error('email') <span class="form-text text-danger">{{$message}} @enderror

                                </div>

                            </div>

                        </div>
                        <div class="kt-portlet__foot">
                            <div class="kt-form__actions">
                                <div class="row">
                                    <div class="col-lg-6">
                                        {{-- <button type="reset" class="btn btn-primary">Save</button> --}}
                                        {{-- <button type="reset" class="btn btn-secondary">Cancel</button> --}}
                                    </div>
                                    <div class="col-lg-6 kt-align-right">
                                        <button type="submit" class="btn btn-outline-success btn-label-success"> <span wire:loading.remove>Save changes</span>
                                            <span wire:loading class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                                            <span wire:loading role="status">Loading...</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!--end::Form-->
                </div>

                <!--end::Portlet-->


            </div>
        </div>
    </div>
</div>
