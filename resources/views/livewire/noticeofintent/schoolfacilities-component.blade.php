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

    <form class="kt-form" action="#!" wire:submit.prevent="sendFacilityDetails">
        <div class="row">
            <div class="col-md-6">
                <!--begin::Portlet-->
                <div class="kt-portlet" style="margin-top: 25px;">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">School Facilities</h3>
                        </div>
                    </div>
                    <div class="kt-portlet__body">

                        <div class="form-group">
                            <label for="type-of-school" style="">Does the school share the premises with other
                                businesses / activities?</label>
                            <div class="kt-radio-list">
                                <label class="kt-radio" style="display: inline-block;">
                                    <input type="radio" name="share_premises" value="yes"
                               wire:model.live="share_premises" {{ ($results['result']['share_premises'] ?? null) === 'yes' ? 'checked' : '' }}> Yes

                                    <span></span>
                                </label>
                                <label class="kt-radio" style="display: inline-block; margin-left: 10px;">
                                    <input type="radio" name="share_premises" value="no"
                                        wire:model.live="share_premises"> No
                                    <span></span>
                                </label><br>
                                @error('share_premises')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror



                                @if ($share_premises === 'yes')
                                    <label>Indicate whom the premises is shared with </label>
                                    <input class="form-control" type="text" name="share_premises_with"
                                        wire:model.live="share_premises_with"><br>
                                @endif
                                @error('share_premises_with')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror

                            </div>
                        </div>
                        <div class="form-group">
                            <label for="type-of-school" style="">Does the school share the building with other
                                businesses / activities?</label>
                            <div class="kt-radio-list">
                                <label class="kt-radio" style="display: inline-block;">
                                    <input type="radio" name="share_building" value="yes"
                                        wire:model.live="share_building">Yes
                                    <span></span>
                                </label>
                                <label class="kt-radio" style="display: inline-block; margin-left: 10px;">
                                    <input type="radio" name="share_building" value="no"
                                        wire:model.live="share_building">No
                                    <span></span>

                                </label><br>
                                @error('share_building')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror


                                @if ($share_building === 'yes')
                                    <label>Indicate whom the building is shared with </label>
                                    <input class="form-control" type="text" name="share_building_with"
                                        wire:model.live="share_building_with"><br>
                                @endif
                                @error('share_building_with')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror

                            </div>
                        </div>

                        <div class="form-group">
                            <label>Number of classrooms</label>
                            <input type="text" class="form-control" name="number_of_classrooms"
                                wire:model.live="number_of_classrooms">
                            @error('number_of_classrooms')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror

                        </div>
                        <div class="form-group">
                            <label>Projected enrolment</label>
                            <input type="text" class="form-control" name="projected_enrolment"
                                placeholder="Enter the projected enrolment" wire:model.live="project_enrollment">
                        </div>
                    </div>
                </div>
                <!--end::Portlet-->
            </div>

            <div class="col-md-6">
                <!--begin::Portlet-->
                <div class="kt-portlet" style="margin-top: 25px;">
                    <div class="kt-portlet__body">
                        <div class="form-group">
                            <label>Number of washrooms</label>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Male</th>
                                        <th>Female</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="font-weight: 500;">Staff</td>
                                        <td>
                                            <div class="form-group">
                                                <input id="number_of_washrooms_staff_male" type="number"
                                                    class="form-control bg-light"
                                                    wire:model.live="number_of_washrooms_staff_male">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input id="number_of_washrooms_staff_female" type="number"
                                                    class="form-control bg-light"
                                                    wire:model.live="number_of_washrooms_staff_female">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <p class="bg-light" style="display: flex; align-items: center; justify-content: center;">
                                                    @if($number_of_washrooms_staff_total == null)
                                                        0
                                                    @else
                                                        {{ $number_of_washrooms_staff_total }}
                                                    @endif
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: 500;">Learners</td>
                                        <td>
                                            <div class="form-group">
                                                <input type="number" class="form-control bg-light"
                                                    wire:model.live="number_of_washrooms_students_male">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="number" class="form-control bg-light"
                                                    wire:model.live="number_of_washrooms_students_female">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                               <p class=" bg-light" style="display: flex; align-items: center; justify-content: center">
                                                @if($number_of_washrooms_students_total == null)
                                                    0
                                                @else
                                                    {{ $number_of_washrooms_students_total }}
                                                @endif
                                                <p>

                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="form-group">
                            <label>Number of toilet seats</label>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Male</th>
                                        <th>Female</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="font-weight: 500;">Staff</td>
                                        <td>
                                            <div class="form-group">
                                                <input type="number" class="form-control bg-light"
                                                    wire:model.live="number_of_toilets_staff_male">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="number" class="form-control bg-light"
                                                    wire:model.live="number_of_toilets_staff_female">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                               <p class=" bg-light" style="display: flex; align-items: center; justify-content: center"> {{ $this->number_of_toilets_staff_total }}<p>

                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: 500;">Learners</td>
                                        <td>
                                            <div class="form-group">
                                                <input type="number" class="form-control  bg-light"
                                                    wire:model.live="number_of_toilets_students_male">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="number" class="form-control bg-light"
                                                    wire:model.live="number_of_toilets_students_female">
                                            </div>
                                        </td>
                                        <td>
                                            <p class=" bg-light" style="display: flex; align-items: center; justify-content: center">
                                                @if($number_of_toilets_students_total == null)
                                                    0
                                                @else
                                                    {{ $number_of_toilets_students_total }}
                                                @endif
                                            <p>

                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="form-group">
                            <label>Number of urinals</label>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Male</th>
                                        <th>Female</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="font-weight: 500;">Staff</td>
                                        <td>
                                            <div class="form-group">
                                                <input type="number" class="form-control bg-light"
                                                    wire:model.live="number_of_urinals_staff_male">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="number" class="form-control bg-light"
                                                    wire:model.live="number_of_urinals_staff_female">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                               <p class=" bg-light" style="display: flex; align-items: center; justify-content: center">
                                                    @if($number_of_urinals_staff_total == null)
                                                        0
                                                    @else
                                                        {{ $number_of_urinals_staff_total }}
                                                    @endif
                                                <p>

                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: 500;">Learners</td>
                                        <td>
                                            <div class="form-group">
                                                <input type="number" class="form-control bg-ligh"
                                                    wire:model.live="number_of_urinals_students_male">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="number" class="form-control bg-light"
                                                    wire:model.live="number_of_urinals_students_female">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                               <p class=" bg-light" style="display: flex; align-items: center; justify-content: center">
                                                @if($number_of_urinals_students_total == null)
                                                    0
                                                @else
                                                    {{ $number_of_urinals_students_total }}
                                                @endif
                                                <p>

                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!--end::Portlet-->
                <div class="kt-portlet__foot kt-align-right">
                    <div class="kt-form__actions">
                        <button type="button" class="btn btn-outline-dark" wire:click="back">Back</button>
                        <button type="submit" class="btn btn-outline-success">Save &
                            continue</button>
                    </div>
                </div>
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
