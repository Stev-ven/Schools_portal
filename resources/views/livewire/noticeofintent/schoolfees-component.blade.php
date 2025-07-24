<div>
    <div class="kt-subheader   kt-grid__item" id="kt_subheader">
        <div class="kt-container  kt-container--fluid ">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">
                    Notice of Intent to Open a New School
                </h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
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
        </div>
    </div>
    <div class="kt-portlet" style="margin-top: 25px;">
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-label">
                <h3 class="kt-portlet__head-title">School Fees Structure</h3>
            </div>
        </div>

    </div>

    <form class="kt-form" action="#!" wire:submit.prevent="sendFeesDetails">
        <div class="row">
            <div class="col-md-12">
                <!--begin::Portlet-->
                <div class="kt-portlet" style="margin-top: 25px; display: flex; justify-content: center; align-items: center;">
                    <div class="kt-portlet__body" style="display: flex; flex-direction: column; align-items: center; width: 100%;">

                        <!-- Table -->
                        <div class="form-group" style="width: 100%;">
                            <label style="font-weight: bold; margin-left: 10%;">Tuition fees</label>
                            <div class="table-responsive">
                                <table class="table table-striped" style="width: 80%; margin: auto;">
                                    <thead>
                                        <tr>
                                            <th>Department</th>
                                            <th>Level/Grade</th>
                                            <th>Term 1<br>/semester(GHc)</th>
                                            <th>Term 2<br>/semester(GHc)</th>
                                            <th>Term 3<br>/semester(GHc)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>NaCCA/GES</td>
                                            <td>Primary School</td>
                                            <td>
                                                <input type="number" class="form-control" wire:model.live="tuition_fee_primary_first_term">
                                                @error($tuition_fee_primary_first_term) <span class="text-danger">{{ $message }}</span> @enderror
                                            </td>
                                            <td>
                                                <input type="number" class="form-control" wire:model.live="tuition_fee_primary_second_term">
                                                @error($tuition_fee_primary_second_term) <span class="text-danger">{{ $message }}</span> @enderror
                                            </td>
                                            <td>
                                                <input type="number" class="form-control" wire:model.live="tuition_fee_primary_third_term">
                                                @error($tuition_fee_primary_third_term) <span class="text-danger">{{ $message }}</span> @enderror
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>NaCCA/GES</td>
                                            <td>Junior High School</td>
                                            <td>
                                                <input type="number" class="form-control" wire:model.live="tuition_fee_junior_high_first_term">
                                                @error($tuition_fee_junior_high_first_term) <span class="text-danger">{{ $message }}</span> @enderror
                                            </td>
                                            <td>
                                                <input type="number" class="form-control" wire:model.live="tuition_fee_junior_high_second_term">
                                                @error($tuition_fee_junior_high_second_term) <span class="text-danger">{{ $message }}</span> @enderror
                                            </td>
                                            <td>
                                                <input type="number" class="form-control" wire:model.live="tuition_fee_junior_high_third_term">
                                                @error($tuition_fee_junior_high_third_term) <span class="text-danger">{{ $message }}</span> @enderror
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>NaCCA/GES</td>
                                            <td>Senior High School</td>
                                            <td>
                                                <input type="number" class="form-control"  wire:model.live="tuition_fee_senior_high_first_term">
                                                @error($tuition_fee_senior_high_first_term) <span class="text-danger">{{ $message }}</span> @enderror
                                            </td>
                                            <td>
                                                <input type="number" class="form-control" wire:model.live="tuition_fee_senior_high_second_term">
                                                @error($tuition_fee_senior_high_second_term) <span class="text-danger">{{ $message }}</span> @enderror
                                            </td>
                                            <td>
                                                <input type="number" class="form-control" wire:model.live="tuition_fee_senior_high_third_term">
                                                @error($tuition_fee_senior_high_third_term) <span class="text-danger">{{ $message }}</span> @enderror
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>


                            <label style="font-weight: bold; margin-left: 10%; margin-top: 50px">Additional Fees</label>
                                <div class="table-responsive">
                                    <table class="table  center-text" style="width: 80%; margin: auto;">
                                        <thead>
                                            <tr>
                                                <th>Fees Description</th>
                                                <th>Amount(GHc)</th>
                                                <th>Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Admission fee(new learners only)</td>
                                                <td><input type="number" class="form-control" wire:model.live="additional_fees_admission_fee_amount"></td>
                                                <td><input type="text" class="form-control" wire:model.live="additional_fees_admission_fee_remarks"></td>
                                            </tr>
                                            <tr>
                                                <td>Maintenance fee(payable once on admission)</td>
                                                <td><input type="number" class="form-control" wire:model.live="additional_fees_maintenance_fee_amount"></td>
                                                <td><input type="text" class="form-control" wire:model.live="additional_fees_maintenance_fee_remarks"></td>
                                            </tr>
                                            <tr>
                                                <td>Boarding fee(optional)</td>
                                                <td><input type="number" class="form-control" wire:model.live="additional_fees_boarding_fees_amount"></td>
                                                <td><input type="text" class="form-control" wire:model.live="additional_fees_boarding_fees_remarks"></td>
                                            </tr>
                                            <tr>
                                                <td>Extracurricular</td>
                                                <td><input type="number" class="form-control" wire:model.live="additional_fees_extracurricular_amount"></td>
                                                <td><input type="text" class="form-control" wire:model.live="additional_fees_extracurricular_remarks"></td>
                                            </tr>
                                            <tr>
                                                <td>Lunch charges</td>
                                                <td><input type="number" class="form-control" wire:model.live="launch_charges"></td>
                                                <td><input type="text" class="form-control" wire:model.live="launch_charges_remarks"></td>
                                            </tr>
                                            <tr>
                                                <td>Course books charges</td>
                                                <td><input type="number" class="form-control" wire:model.live="course_books_charges"></td>
                                                <td><input type="text" class="form-control" wire:model.live="course_books_charges_remarks"></td>
                                            </tr>
                                            <tr>
                                                <td>Other facility charges</td>
                                                <td><input type="number" class="form-control" wire:model.live="other_facility_charges"></td>
                                                <td><input type="text" class="form-control" wire:model.live="other_facility_charges_remarks"></td>
                                            </tr>
                                            <tr>
                                                <td>Technical</td>
                                                <td><input type="text" class="form-control" name=""></td>
                                                <td><input type="text" class="form-control" name=""></td>
                                            </tr>
                                            <tr>
                                                <td>Others(specify)<br>
                                                    <input type="text" name="" id="" class="form-control" wire:model.live="additional_fees_others_specified_value">
                                                </td>
                                                <td><input type="number" class="form-control" wire:model.live="additional_fees_others_amount"></td>
                                                <td><input type="text" class="form-control" name="" wire:model.live="additional_fees_others_remarks"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="kt-portlet__foot" style="margin-left: 100px;">
                                    <div class="kt-form__actions" style="margin-left: 65%;">
                                        <button type="button" class="btn btn-outline-dark" wire:click="back">Back</button>
                                        <button type="submit" class="btn btn-outline-success">Save & continue</button>
                                    </div>
                                </div>

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

