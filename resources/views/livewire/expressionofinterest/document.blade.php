<div class="kt-portlet__body" style="">
    <div class="kt-subheader   kt-grid__item" id="kt_subheader">
        <div class="kt-container  kt-container--fluid ">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">
                    Expression Of Interest To Establish A New School
                </h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
            </div>
            <div class="kt-subheader__main">
                <button class="btn btn-outline-success btn-label-success" wire:click="nextSection">
                    <span wire:loading.remove wire:target="nextSection">Next section</span>
                    <span wire:loading wire:target="nextSection" class="spinner-border spinner-border-sm"
                        aria-hidden="true"></span>
                    <span wire:loading wire:target="nextSection" role="status">Loading...</span>
                </button>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
            </div>
        </div>
    </div>
    <div class="kt-portlet" style="">
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-label">
                <h3 class="kt-portlet__head-title">Legal Registration<small class="form-text text-muted">(PDF, JPG only,
                        max. 5MB per upload).</small></h3>
            </div>
        </div>



        {{-- <form class="kt-form" style="width: 100%; margin: 0 auto"> --}}
        <div class="form-group row" style="width: 80%; margin: 0 auto">
            <div class="col-md-6">
                <label class=" col-form-label">Is the land owned by the proprietor?</label>
                <div class="col-9">
                    <div class="kt-radio-inline">
                        <label class="kt-radio">
                            <input type="radio" name="radio1" class="kt-radio" value="yes"
                                wire:model.live="land_owned_by_proprietor">Yes
                            <span></span>
                        </label>
                        <label class="kt-radio">
                            <input type="radio" name="radio1" value="no" class="kt-radio"
                                wire:model.live="land_owned_by_proprietor">No
                            <span></span>
                        </label>
                    </div>
                </div>
                @error('land_owned_by_proprietor')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-md-6">

                <label>When does the school intend to commence operation?</label>
                <input type="date" class="form-control" style="width: 80%;" wire:model="commence_date">
                <small class="form-text text-muted">Please enter when you intern to start operation</small>
                @error('commence_date')
                    <span class="text-danger">{{ $message }}</span>
                @enderror

            </div>


        </div>

        {{-- @if ($land_owned_by_proprietor == 'yes') --}}
        <div class="{{ $land_owned_by_proprietor === 'no' ? 'd-none' : '' }}">
            <form action="#!" wire:key="form-land-yes" wire:submit.prevent="saveLandLordDocument" class="kt-form"
                style="width: 80%; margin: 0 auto">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h1 class="kt-portlet__head-title">Mandatory Documents</h1>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group" wire:ignore style="font-size: 25px">
                        <label style="font-size: 16px">Business Operating Permit</label>
                        <x-file-upload label="Upload Document" model="business_operating_permit" :certificatePreviewUrl="$businessPermitPreviewUrl" />

                        <p class="text-danger" style="font-size: 12px;">
                            @error('business_operating_permit')
                                {{ $message }}
                            @enderror
                        </p>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group" wire:ignore style="font-size: 25px">
                        <label style="font-size: 16px">Approved Building Plan</label>
                        <x-file-upload label="Upload Document" model="approved_building_plan" :certificatePreviewUrl="$approvedBuildingPlanPreviewUrl" />

                        <p class="text-danger" style="font-size: 12px;">
                            @error('approved_building_plan')
                                {{ $message }}
                            @enderror
                        </p>

                    </div>

                </div>

                <div class="row">
                    <div class="form-group" wire:ignore style="font-size: 25px">
                        <label style="font-size: 16px">National ID Card</label>
                        <x-file-upload label="Upload Document" model="national_id_card" :certificatePreviewUrl="$nationalIdPreviewUrl" />

                        <p class="text-danger" style="font-size: 12px;">
                            @error('national_id_card')
                                {{ $message }}
                            @enderror
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group" wire:ignore style="font-size: 25px">
                        <label style="font-size: 16px">Local Authority Certificate</label>
                        <x-file-upload label="Upload Document" model="local_authority_certificate_document"
                            :certificatePreviewUrl="$localAuthorityCertificatePreviewUrl" />

                        <p class="text-danger" style="font-size: 12px;">
                            @error('local_authority_certificate_document')
                                {{ $message }}
                            @enderror
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group" wire:ignore style="font-size: 25px">
                        <label style="font-size: 16px">Land Documents with Proprietor Name</label>
                        <x-file-upload label="Upload Document" model="land_documents_with_prop_name_document"
                            :certificatePreviewUrl="$landDocumentsWithProprietorNamePreviewUrl" />

                        <p class="text-danger" style="font-size: 12px;">
                            @error('land_documents_with_prop_name_document')
                                {{ $message }}
                            @enderror
                        </p>
                    </div>
                </div>

                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h1 class="kt-portlet__head-title">Optional Documents</h1>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group" wire:ignore style="font-size: 25px">
                        <label style="font-size: 16px">Police Clearance Report</label>
                        <x-file-upload label="Upload Document" model="police_clearance_report" :certificatePreviewUrl="$policeClearanceReportPreviewUrl" />

                        <p class="text-danger" style="font-size: 12px;">
                            @error('police_clearance_report')
                                {{ $message }}
                            @enderror
                        </p>
                    </div>
                </div>

                <div class="row">

                    <div class="form-group">
                        <input type="checkbox" class="kt-checkbox-large" wire:model.live="declarationChecked">
                        I declare that the information
                        provided in this application and the attachment
                        therein, <br>are true to the best of my knowledge.
                        Further, I acknowledge that the submission of false<br>
                        information shall render this application void.<br>
                        @error('declaration')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="kt-portlet__foot">
                    <div class="kt-form__actions kt-align-right">
                        <!-- Back Button -->
                        <button type="button" class="btn btn-outline-dark btn-label-dark" wire:click="back"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="back">Back</span>
                            <span wire:loading wire:target="back">
                                <i class="fas fa-spinner fa-spin"></i> Loading...
                            </span>
                        </button>
                        <span>&nbsp;</span>

                        <!-- Upload Documents Button -->
                        <button type="button" class="btn btn-outline-success btn-label-success"
                            wire:click="saveLandLordDocument" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="saveLandLordDocument">Save Documents</span>
                            <span wire:loading wire:target="saveLandLordDocument" class="spinner-border spinner-border-sm"
                                aria-hidden="true"></span>
                            <div wire:loading wire:target="saveLandLordDocument">Saving...</div>
                        </button>
                        <span>&nbsp;</span>


                    </div>
                </div>
            </form>
        </div>

        {{-- @endif --}}

        {{-- @if ($land_owned_by_proprietor == 'no') --}}
        <div class="{{ $land_owned_by_proprietor === 'yes' ? 'd-none' : '' }}">
            <form action="#!" wire:key="form-land-no" wire:submit.prevent="saveLandLordDocument" class="kt-form"
                style="width: 80%; margin: 0 auto">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h1 class="kt-portlet__head-title">Mandatory Documents</h1>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group" wire:ignore style="font-size: 25px">
                        <label style="font-size: 16px">Business Operating Permit</label>
                        <x-file-upload label="Upload Document" model="business_operating_permit_no"
                            :certificatePreviewUrl="$businessPermitPreviewUrl" />

                        <p class="text-danger" style="font-size: 12px;">
                            @error('business_operating_permit_no')
                                {{ $message }}
                            @enderror
                        </p>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group" wire:ignore style="font-size: 25px">
                        <label style="font-size: 16px">Tenancy Agreement for at least 10 years</label>
                        <x-file-upload label="Upload Document" model="tenancy_agreement_no" :certificatePreviewUrl="$tenancyAgreementPreviewUrl" />

                        <p class="text-danger" style="font-size: 12px;">
                            @error('tenancy_agreement_no')
                                {{ $message }}
                            @enderror
                        </p>

                    </div>

                </div>

                <div class="row">
                    <div class="form-group" wire:ignore style="font-size: 25px">
                        <label style="font-size: 16px">National ID Card</label>

                        <x-file-upload label="Upload Document" model="national_id_card_no" :certificatePreviewUrl="$nationalIdPreviewUrl" />

                        <p class="text-danger" style="font-size: 12px;">
                            @error('national_id_card_no')
                                {{ $message }}
                            @enderror
                        </p>
                    </div>
                </div>

                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h1 class="kt-portlet__head-title">Optional Documents</h1>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group" wire:ignore style="font-size: 25px;">
                        <label style="font-size: 16px">Police Clearance Report</label>
                        <x-file-upload label="Upload Document" model="police_clearance_report_no" :certificatePreviewUrl="$policeClearanceReportPreviewUrl" />

                        <p class="text-danger" style="font-size: 12px;">
                            @error('police_clearance_report_no')
                                {{ $message }}
                            @enderror
                        </p>
                    </div>
                </div>

                <div class="row">

                    <div class="form-group">
                        <input type="checkbox" class="kt-checkbox-large" wire:model.live="declarationChecked">
                        I declare that the information
                        provided in this application and the attachment
                        therein, <br>are true to the best of my knowledge.
                        Further, I acknowledge that the submission of false<br>
                        information shall render this application void.<br>
                        @error('declaration')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="kt-portlet__foot">
                    <div class="kt-form__actions kt-align-right">
                        <!-- Back Button -->
                        <button type="button" class="btn btn-outline-dark btn-label-dark" wire:click="back"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="back">Back</span>
                            <span wire:loading wire:target="back">
                                <i class="fas fa-spinner fa-spin"></i> Loading...
                            </span>
                        </button>
                        <span>&nbsp;</span>

                        <!-- Upload Documents Button -->
                        <button type="button" class="btn btn-outline-success btn-label-success"
                            wire:click="saveLandLordDocument" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="saveLandLordDocument">Save Documents</span>
                            <span wire:loading wire:target="saveLandLordDocument"
                                class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                            <div wire:loading wire:target="saveLandLordDocument">Saving...</div>

                            </span>
                        </button>
                        <span>&nbsp;</span>
                    </div>
                </div>
            </form>
        </div>

        {{-- @endif --}}
    </div>
</div>



</div>
</div>




@script
    <script>
        $wire.on('notify', (items) => {
            Swal.fire({
                title: "File has been successfully uploaded!",
                text: "Proceed to add school details",
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
