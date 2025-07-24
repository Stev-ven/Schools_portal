<form class="kt-form" wire:submit.prevent="registerSchool" action="#!">
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
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h1 class="kt-portlet__head-title">School Details</h1>
        </div>
    </div>

    <div class="row" style="margin-top: 20px;">
        <div class="col-md-6">
            <div class="form-group">
                <label style="">School Name</label>
                <input type="text" class="form-control" placeholder="Enter School Name" wire:model="school_name">
                <p class="text-danger"> @error('school_name') {{ $message }}@enderror</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="region">Select Region</label>
                <select class="form-control" wire:model.live="selectedRegion">
                    <option value="">Select Region</option>
                    @foreach ($regions as $region)
                        <!-- Bind the region code as the value -->
                        <option value="{{ $region->code }}">{{ $region->name }}</option>
                    @endforeach
                </select>
                <p class="text-danger">@error('selectedRegion'){{ $message }}@enderror</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <lable style="font-size: 15px">Is your school registered with the Registrar General Department?</label>
                    <div class="kt-radio-inline">
                        <label class="kt-radio">
                            <input type="radio" value="yes" name="register" checked="checked"
                                wire:model="registered_with_registrar_general_department"> Yes
                            <span></span>
                        </label>
                        <label class="kt-radio">
                            <input type="radio" value="no" name="register"
                                wire:model="registered_with_registrar_general_department"> No
                            <span></span>
                        </label>
                        <p class="text-danger">@error('registered_with_registrar_general_department'){{ $message }}@enderror</p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="district">Select District</label>
                    <select class="form-control select-pt-pb" wire:model="selectedDistrict">
                        <option value="">Select District</option>
                        @if(!empty($districts))
                            @foreach ($districts as $district)
                                <option value="{{ $district->name }}">{{ $district->name }}</option>
                            @endforeach
                        @endif
                    </select>
                    <p class="text-danger">@error('selectedDistrict'){{ $message }}@enderror</p>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-md-6" style="">
                <div class="form-group">
                    <label style="font-size: 15px">Was your school established before August 26, 2020?</label>
                    <div class="kt-radio-inline">
                        <label class="kt-radio">
                            <input type="radio" value="yes" name="established"
                                wire:model="school_established_before_august_26_2020"> Yes
                            <span></span>
                        </label>
                        <label class="kt-radio">
                            <input type="radio" value="no" name="established"
                                wire:model="school_established_before_august_26_2020"> No
                            <span></span>
                        </label>
                        <p class="text-danger">
                            @error('school_established_before_august_26_2020')
                                {{ $message }}
                            @enderror
                        </p>
                    </div>
                </div>
            </div>


            <div class="col-md-6">
                <div class="form-group">
                    <label for="type-of-school" style="font-size: 15px">Type of school</label>
                    <select class="form-select select-pt-pb" wire:model="type_of_school">
                        <option value="">Select Type of School</option>
                        <option value="specialized">Specialized</option>
                        <option value="private national curriculum">Private National Curriculum</option>
                        <option value="public national curriculum">Public National Curriculum</option>
                        <option value="private international curricular school">Private International Curricular School</option>
                    </select>
                    <p class="text-danger">@error('type_of_school'){{ $message }}@enderror</p>
                </div>
            </div>
        </div>
    </div>
    <div class="kt-portlet__foot">
        <div class="kt-form__actions kt-align-right">
            <button type="button" class="btn btn-outline-dark btn-label-dark" wire:click="back">Back</button>
            <span>&nbsp;</span>
            <button type="submit" class="btn btn-outline-success btn-label-success">Submit</button>
        </div>
    </div>
</form>

@script
    <script>
        $wire.on('notify', (items) => {
            Swal.fire({
                title: "Applications have been successfully generated!",
                text: "Proceed to applications",
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
