<div>
    <div class="kt-subheader   kt-grid__item" id="kt_subheader">
        <div class="kt-container  kt-container--fluid ">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">
                    Notice of Intent to Open a New School
                </h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
            </div>
            <div class="kt-subheader__main">
                <button class="btn btn-outline-success" wire:click="nextSection">
                    <span wire:loading.remove wire:target="nextSection">Next section</span>
                    <span wire:loading wire:target="nextSection" class="spinner-border spinner-border-sm"
                        aria-hidden="true"></span>
                    <span wire:loading wire:target="nextSection" role="status">Loading...</span>
                </button>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
            </div>
        </div>
    </div>


    <form class="kt-form" action="#!" wire:submit.prevent="saveDocuments">
        <div class="row">
            <div class="col-md-12">
                <!--begin::Portlet-->
                <div class="kt-portlet" style="margin-top: 25px;">
                    <div class="kt-portlet__body">
                        <div class="kt-portlet__head">
                            <div class="kt-portlet__head-label">
                                <h3 class="kt-portlet__head-title" style="font-weight: bold">Supporting documents</h3>
                            </div>
                        </div>
                        <div class="alert alert-secondary" role="alert">
                            File upload size should not exceed 5MB. Only jpg, jpeg, pdf files are allowed.
                        </div>
                        <div class="kt-portlet__body">

                            <form action="#!" wire:key="form-land-yes" wire:submit.prevent="saveLandLordDocument"
                                class="kt-form" style="width: 80%; margin: 0 auto">
                                <div class="kt-portlet__head">
                                    <div class="kt-portlet__head-label">
                                        <h1 class="kt-portlet__head-title">Mandatory Documents</h1>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group">
                                        <label style="font-size: 16px">Governance Structure with Mission & Vision Statement</label>
                                        <x-file-upload label="Upload file" model="vision_and_mission_statement_document"
                                            :certificatePreviewUrl="$visionAndMissionCertificatePreviewUrl" />

                                        <p class="text-danger" style="font-size: 12px;">
                                            @error('vision_and_mission_statement_document')
                                                {{ $message }}
                                            @enderror
                                        </p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group">
                                        <label style="font-size: 16px">Staff records and evidence of social security and National Insurance Trust
                                            registration</label>
                                        <x-file-upload label="Upload file" model="social_security_document"
                                            :certificatePreviewUrl="$socialSecurityDocumentPreviewUrl" />

                                        <p class="text-danger" style="font-size: 12px;">
                                            @error('social_security_document')
                                                {{ $message }}
                                            @enderror
                                        </p>

                                    </div>

                                </div>

                                <div class="row">
                                    <div class="form-group">
                                        <label style="font-size: 16px">Scanned copy of National Identity card of Head Teacher(Passport, Ghana card,
                                            Drivers license, Voters ID)</label>
                                        <x-file-upload label="Upload file" model="scanned_national_id_card_document" :certificatePreviewUrl="$scannedNationalIdCardPreviewUrl"/>

                                        <p class="text-danger" style="font-size: 12px;">
                                            @error('scanned_national_id_card_document')
                                                {{ $message }}
                                            @enderror
                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label style="font-size: 16px">School professional Development and School/staff Development Plan</label>
                                        <x-file-upload label="Upload file" model="staff_development_plan_document"
                                            :certificatePreviewUrl="$staffDevelopmentPlanPreviewUrl" />

                                        <p class="text-danger" style="font-size: 12px;">
                                            @error('staff_development_plan_document')
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
                                    <div class="form-group">
                                        <label style="font-size: 16px">Policies (Health and safety, child protection
                                                & safeguarding policy)</label>
                                        <x-file-upload label="Upload file" model="policies_document"
                                            :certificatePreviewUrl="$policiesDocumentPreviewUrl" />

                                        <p class="text-danger" style="font-size: 12px;">
                                            @error('police_clearance_report')
                                                {{ $message }}
                                            @enderror
                                        </p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group">
                                        <label style="font-size: 16px">Grievance Procedure Document</label>
                                        <x-file-upload label="Upload file" model="grievance_procedure_document"
                                            :certificatePreviewUrl="$grievanceProcedurePreviewUrl" />

                                        <p class="text-danger" style="font-size: 12px;">
                                            @error('fire_safety_certificate_document')
                                                {{ $message }}
                                            @enderror
                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label style="font-size: 16px">Fire Safety Certificate</label>
                                        <x-file-upload label="Upload file" model="fire_safety_certificate_document"
                                            :certificatePreviewUrl="$fireSafetyCertificatePreviewUrl" />

                                        <p class="text-danger" style="font-size: 12px;">
                                            @error('fire_safety_certificate_document')
                                                {{ $message }}
                                            @enderror
                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label style="font-size: 16px">School curriculum and language policy</label>
                                        <x-file-upload label="Upload file" model="curriculum_and_language_policy_document"
                                            :certificatePreviewUrl="$curriculumAndLanguagePolicyPreviewUrl" />

                                        <p class="text-danger" style="font-size: 12px;">
                                            @error('curriculum_and_language_policy_document')
                                                {{ $message }}
                                            @enderror
                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label style="font-size: 16px">Inclusive Education Policy</label>
                                        <x-file-upload label="Upload file" model="inclusive_education_policy_document"
                                            :certificatePreviewUrl="$inclusiveEducationPolicyPreviewUrl" />

                                        <p class="text-danger" style="font-size: 12px;">
                                            @error('inclusive_education_policy_document')
                                                {{ $message }}
                                            @enderror
                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label style="font-size: 16px">Policy clearance report of proprietor & headteacher <br> / principal
                                            or vice principal(s) (not more than 6 months old)</label>
                                        <x-file-upload label="Upload file" model="police_clearance_report"
                                            :certificatePreviewUrl="$policeClearanceReportPreviewUrl" />

                                        <p class="text-danger" style="font-size: 12px;">
                                            @error('police_clearance_report')
                                                {{ $message }}
                                            @enderror
                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label style="font-size: 16px">Waste Management Plan</label>
                                        <x-file-upload label="Upload file" model="waste_management_plan_document"
                                            :certificatePreviewUrl="$wasteManagementPlanPreviewUrl" />

                                        <p class="text-danger" style="font-size: 12px;">
                                            @error('waste_management_plan_document')
                                                {{ $message }}
                                            @enderror
                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label style="font-size: 16px">Business continuity plan and source of funding for the next 5 years</label>
                                        <x-file-upload label="Upload file" model="business_continuity_plan_document"
                                            :certificatePreviewUrl="$businessContinuityPlanPreviewUrl" />

                                        <p class="text-danger" style="font-size: 12px;">
                                            @error('business_continuity_plan_document')
                                                {{ $message }}
                                            @enderror
                                        </p>
                                    </div>
                                </div>

                                <div class="kt-portlet__foot">
                                    <div class="kt-form__actions kt-align-right">
                                        <!-- Back Button -->
                                        <button type="button" class="btn btn-outline-dark" wire:click="back"
                                            wire:loading.attr="disabled">
                                            <span wire:loading.remove wire:target="back">Back</span>
                                            <span wire:loading wire:target="back">
                                                <i class="fas fa-spinner fa-spin"></i> Loading...
                                            </span>
                                        </button>
                                        <span>&nbsp;</span>

                                        <!-- Upload Documents Button -->
                                        <button type="button" class="btn btn-outline-success"
                                            wire:click="saveDocuments" wire:loading.attr="disabled">
                                            <span wire:loading.remove wire:target="saveDocuments">Save
                                                documents</span>
                                            <span wire:loading wire:target="saveDocuments">
                                                <i class="fas fa-spinner fa-spin"></i> Saving...
                                            </span>
                                        </button>
                                        <span>&nbsp;</span>


                                    </div>
                                </div>
                            </form>

                        </div>

                    </div>
                </div>
                <!--end::Portlet-->
            </div>
        </div>
    </form>
</div>
@script
    <script>
        $wire.on('notify', (items) => {
            Swal.fire({
                title: "Details have been updated successfully",
                text: "Proceed to Next section",
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
