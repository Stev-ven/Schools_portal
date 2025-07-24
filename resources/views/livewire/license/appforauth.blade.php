
    <div class="row">
        <div class="kt-subheader   kt-grid__item" id="kt_subheader">
            <div class="kt-container  kt-container--fluid ">
                <div class="kt-subheader__main">
                    <h3 class="kt-subheader__title">
                        Applications for authorization to open a new school
                    </h3>
                    <span class="kt-subheader__separator kt-subheader__separator--v"></span>

                </div>

            </div>

        </div>

            <!--begin::Portlet-->

                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h1 class="kt-portlet__head-title">School information</h1>
                    </div>
                </div>

                <div class="kt-portlet__body">
                    <form class="kt-form" wire:submit.prevent="editAfa" action="#!">
                    <div class="row md-12">
                    <div class="form-group col-md-6">
                        <label style="font-size: 15px">School Name</label>
                        <input type="text" class="form-control" wire:model="school_name" placeholder="Enter School Name" readonly>
                        <p class="text-muted" style="font-size: 12px">School name cannot be changed</p>
                       <p class="text-danger">@error('school_name'){{ $message }}@enderror</p>
                    </div>
                    <div class="form-group col-md-6">
                        <label style="font-size: 15px">GPS Address</label>
                        <input type="text" class="form-control"  placeholder="Enter GPS Address" wire:model="gps_address">
                       <p class="text-danger">@error('gps_address'){{ $message }}@enderror</p>
                    </div>
                    </div>
                    <div class="row md-12">
                    <div class="form-group col-md-6">
                        <label style="font-size: 15px">City/Town</label>
                        <input type="text" class="form-control" wire:model="city_or_town">
                        <p class="text-danger">@error('city_or_town'){{ $message }}@enderror</p>
                     </div>
                     <div class="form-group col-md-6">
                        <label for="region" style="font-size: 15px">Region</label>
                        <select class="form-select select-pt-pb" wire:model.live="selectedRegion" name="selectedRegion">
                            <option value="">Select Region</option>
                            @foreach($regions as $region)
                            <option value="{{ $region->code}}">{{ $region->name}}</option>
                            @endforeach
                        </select>
                        <p class="text-danger">@error('selectedRegion'){{ $message }}@enderror</p>
                    </div>
                    </div>
                    <div class="row md-12">
                        {{-- district --}}
                        <div class="form-group col-md-6">
                            <label style="font-size: 15px">Location of school (Address and landmark)</label>
                            <textarea class="form-control" name="location_of_school" placeholder="Enter location details" rows="4" wire:model.live="location_of_school"></textarea>
                            <p class="text-danger">@error('location_of_school'){{ $message }}@enderror</p>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="district" style="font-size: 15px">District</label>
                            <select class="form-select select-pt-pb" wire:model.live="selectedDistrict">
                                <option value="">Select District</option>
                                @if(!empty($districts))
                                    @foreach ($districts as $district)
                                        <option value="{{ $district->name }}">{{ $district->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <p class="text-danger">@error('selectedDistrict'){{ $message }}@enderror</p>
                        </div>

                    <div class="row md-12">
                        <div class="form-group col-md-6">
                            <label for="title" style="font-size: 15px">Person Title</label>
                            <select class="form-select select-pt-pb" wire:model="person_title">
                                <option value="">Select Title</option>
                                <option value="Prof.">Prof.</option>
                                <option value="Dr.">Dr.</option>
                                <option value="Eng.">Eng.</option>
                                <option value="Mr.">Mr.</option>
                                <option value="Mrs.">Mrs.</option>
                                <option value="Ms.">Ms.</option>
                                <option value="Miss">Miss</option>
                                <option value="Lawyer">Lawyer</option>
                                <option value="other">Other</option>
                            </select>
                           <p class="text-danger">@error('person_title'){{ $message }}@enderror</p>
                        </div>
                        <div class="form-group col-md-6">
                            <label style="font-size: 15px">Name of Proprietor</label>
                            <input type="text" class="form-control"  placeholder="Enter proprietor name" wire:model="name_of_proprietor">
                            <p class="text-danger">@error('name_of_proprietor'){{ $message }}@enderror</p>
                        </div>
                    </div>
                    <div class="row md-12">
                        <div class="form-group col-md-6">
                            <label style="font-size: 15px">Phone Number</label>
                            <input type="text" class="form-control" placeholder="Enter phone number" wire:model="phone_number">
                            <p class="text-danger">@error('phone_number'){{ $message }}@enderror</p>
                        </div>
                        <div class="form-group col-md-6">
                            <label style="font-size: 15px">Email Address</label>
                            <input type="email" class="form-control" placeholder="Enter email" wire:model="email">
                            <p class="text-danger">@error('email'){{ $message }}@enderror</p>
                        </div>
                    </div>
                    <div class="row md-12">
                        <div class="form-group col-md-6">
                            <label style="font-size: 15px">School's Website Address</label>
                            <input type="text" class="form-control" placeholder="Enter website" wire:model="website_address">
                            <p class="text-danger">@error('website_address'){{ $message }}@enderror</p>
                        </div>
                    </div>
                    <div class="kt-portlet__foot ">
                        <div class="kt-form__actions kt-align-right">
                            <button type="button" class="btn btn-outline-dark btn-label-dark" wire:click="back">Back</button>
                            <span>&nbsp;</span>
                            <button type="submit" class="btn btn-outline-success btn-label-success">Save & Continue</button>
                        </div>
                    </div>
                 </div>
                </form>
                </div>
            {{-- </div> --}}
        {{-- </div> --}}
    </div>

    @script
    <script>
        $wire.on('notify', (items) => {
            Swal.fire({
                title: "Application details have been updated successfully.",
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
    <script>
        toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "timeOut": "3000",
        "extendedTimeOut": "1000"
    };
    @if (session('success'))
        toastr.success("{{ session('success') }}");
    @endif

    @if (session('error'))
        toastr.error("{{ session('error') }}");
    @endif

    @if (session('info'))
        toastr.info("{{ session('info') }}");
    @endif

    @if (session('warning'))
        toastr.warning("{{ session('warning') }}");
    @endif
</script>
@endscript

