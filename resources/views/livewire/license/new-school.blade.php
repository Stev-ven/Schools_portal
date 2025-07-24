<div>
    {{-- Because she competes with no one, no one can compete with her. --}}
    <div class="row">
        <div class="col-xl-12 col-lg-12 order-lg-1 order-xl-1">
            <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
                <div class="row">
                    <div class="col-lg-12">

                        <!--begin::Portlet-->
                        <div class="kt-portlet">
                            <div class="kt-portlet__head">
                                <div class="kt-portlet__head-label">
                                    <h3 class="kt-portlet__head-title">
                                        Application
                                    </h3>
                                </div>
                            </div>

                            <!--begin::Form-->
                            <form wire:submit.prevent="submit" class="kt-form kt-form--label-right">
                                <div class="kt-portlet__body">
                                    <div class="form-group row">
                                        <div class="col-lg-6">
                                            <label>School name</label>
                                            <input wire:model='school_name' type="text" class="form-control">
                                            <span class="form-text text-danger">
                                                School name must be same as appears
                                                on Registrar General's Certificate
                                            </span>
                                            @error('school_name')<span class="form-text text-danger">{{$message}}</span>@enderror
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="">Type of School You Operate</label>
                                            <select wire:model='school_type' name="" wire:model='' class="form-control" id="">
                                                <option value="">Select</option>
                                                <option value="Private International Curricular School">
                                                    Private – International Curricular School (E.g., IB, CAIE, etc)
                                                </option>
                                                <option value='Private National Curriculum'> Private – National Curriculum (GES/NaCCA Curriculum) </option>
                                                <option value='Public National Curriculum'> Public – National Curriculum (GES/NaCCA Curriculum) </option>
                                                <option value='Specialized'> Specialized (E.g. Music, Media, Aviation, Fashion, Training School, etc)</option>
                                            </select>
                                            @error('school_type')<span class="form-text text-danger">{{$message}}</span>@enderror

                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-lg-6">
                                            <label>Region *</label>
                                            <div class="kt-input-icon">
                                                <select wire:model.live='selectedRegion' class="form-control " id="">
                                                    <option value="">Select</option>
                                                    @foreach($regions as $key => $value)
                                                    <option value="{{$value->name}}"> {{$value->name}}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                @error('selectedRegion')<span class="form-text text-danger">{{$message}}</span>@enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <label class="">District *</label>
                                            <div class="kt-input-icon">

                                                <select wire:model='district' name="" class="form-control" id="">
                                                    <option value="">Select</option>
                                                    @foreach($all_districts as $key => $value)
                                                    <option value="{{$value->name}}">{{$value->name}}</option>
                                                    @endforeach
                                                </select>
                                                @error('district')<span class="form-text text-danger">{{$message}}</span>@enderror

                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <div class="col-lg-6">
                                            <label>
                                                Is your school registered as a business with the Register's General's Dept.
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="kt-input-icon">
                                                <select wire:model='is_registered' name="" class="form-control" id="">
                                                    <option value="">Select</option>
                                                    <option value="yes">Yes</option>
                                                    <option value="no">No</option>
                                                </select>
                                            </div>
                                            @error('is_registered')<span class="form-text text-danger">{{$message}}</span>@enderror

                                        </div>

                                        <div class="col-lg-6">
                                            <label>Was your school established before August 26, 2020 ? <span class="text-danger">*</span> </label>
                                            <div class="kt-input-icon">
                                                <select wire:model='is_established' name="" class="form-control" id="">
                                                    <option value="">Select</option>
                                                    <option value="yes">Yes</option>
                                                    <option value="0">No</option>
                                                </select>
                                                @error('is_established')<span class="form-text text-danger">{{$message}}</span>@enderror
                                            </div>

                                        </div>
                                    </div>

                                </div>
                                <div class="kt-portlet__foot">
                                    <div class="kt-form__actions">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <button type="submit" class="btn btn-primary">
                                                    <span wire:loading.remove wire:target="submit">Submit</span>
                                                    <span wire:loading wire:target="submit" class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                                                    <span wire:loading wire:target="submit" role="status">Loading...</span>
                                                </button>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </form>

                            <!--end::Form-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@script

<script>
    $wire.on('success', (items) => {
        swal.fire(items.title, items.msg, items.type);
    })

</script>

@endscript
