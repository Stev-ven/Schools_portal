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
    <form class="kt-form" action="#!" wire:submit.prevent="sendFacilityChecklist">
        <div class="row">
            <div class="col-md-12">
                <!--begin::Portlet-->


                <div class="kt-portlet__body">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title"
                            style="font-weight: bold; font-size: 15px; margin-left: 10%;">Facilities checklist</h3>
                    </div>
                    <table class="table table-striped table-bordered center-text" style="width: 80%; margin: auto; ">
                        <thead>
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th style="width: 25%;">Facility</th>
                                <th style="width: 10%;">Yes</th>
                                <th style="width: 10%;">No</th>
                                <th style="width: 50%;">Comments</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @dd($results); --}}
                            <tr>
                                <td>1</td>
                                <td>Administration Block</td>
                                <td>
                                    <input class="kt-radio" type="radio" value="yes"
                                        wire:model.live="administration_block">
                                </td>
                                <td>
                                    <input class="kt-radio" type="radio" value="no"
                                        wire:model.live="administration_block">
                                </td>
                                <td>
                                    <input class="form-control" type="text"
                                        wire:model.live="administration_block_comment">
                                </td>

                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Assembly Hall</td>
                                <td>
                                    <input class="kt-radio" type="radio" name="assembly-hall" value="yes"
                                        wire:model.live="assembly_hall">
                                </td>
                                <td>
                                    <input class="kt-radio" type="radio" name="assembly-hall" value="no"
                                        wire:model.live="assembly_hall">
                                </td>
                                <td>
                                    <input type="text" class="form-control" wire:model.live="assembly_hall_comment">
                                </td>

                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Staff Common Room</td>
                                <td>
                                    <input class="kt-radio" type="radio" name="staff-common-room" value="yes"
                                        wire:model.live="staff_common_room">
                                </td>
                                <td>
                                    <input class="kt-radio" type="radio" name="staff-common-room" value="no"
                                        wire:model.live="staff_common_room">
                                </td>
                                <td>
                                    <input type="text" name="comment" class="form-control"
                                        wire:model.live="staff_common_room_comment">
                                </td>

                            </tr>
                            <tr>
                                <td>4</td>
                                <td>School Library</td>
                                <td>
                                    <input class="kt-radio" type="radio" name="school-library" value="yes"
                                        wire:model.live="school_library">
                                </td>
                                <td>
                                    <input class="kt-radio" type="radio" name="school-library" value="no"
                                        wire:model.live="school_library">
                                </td>
                                <td>
                                    <input type="text" name="comment" class="form-control"
                                        wire:model.live="school_library_comment">
                                </td>

                            </tr>
                            <tr>
                                <td>5</td>
                                <td>Science Laboratory</td>
                                <td>
                                    <input class="kt-radio" type="radio" name="science-laboratory" value="yes"
                                        wire:model.live="science_laboratory">
                                </td>
                                <td>
                                    <input class="kt-radio" type="radio" name="science-laboratory" value="no"
                                        wire:model.live="science_laboratory">
                                </td>
                                <td>
                                    <input type="text" name="comment" class="form-control"
                                        wire:model.live="science_laboratory_comment">
                                </td>

                            </tr>
                            <tr>
                                <td>6</td>
                                <td>ICT Laboratory</td>
                                <td>
                                    <input class="kt-radio" type="radio" name="ict-laboratory" value="yes"
                                        wire:model.live="ict_laboratory">
                                </td>
                                <td>
                                    <input class="kt-radio" type="radio" name="ict-laboratory" value="no"
                                        wire:model.live="ict_laboratory">
                                </td>
                                <td>
                                    <input type="text" name="comment" class="form-control"
                                        wire:model.live="ict_laboratory_comment">
                                </td>

                            </tr>
                            <tr>
                                <td>7</td>
                                <td>Workshop/Studio</td>
                                <td>
                                    <input class="kt-radio" type="radio" name="workshop" value="yes"
                                        wire:model.live="workshop">
                                </td>
                                <td>
                                    <input class="kt-radio" type="radio" name="workshop" value="no"
                                        wire:model.live="workshop">
                                </td>
                                <td>
                                    <input type="text" name="workshop_comment" class="form-control"
                                        wire:model.live="workshop_comment">
                                </td>

                            </tr>
                            <tr>
                                <td>8</td>
                                <td>Kitchen/Canteen/Dining Hall</td>
                                <td>
                                    <input class="kt-radio" type="radio" name="dining" value="yes"
                                        wire:model.live="dining">
                                </td>
                                <td>
                                    <input class="kt-radio" type="radio" name="dining" value="no"
                                        wire:model.live="dining">
                                </td>
                                <td>
                                    <input type="text" class="form-control" wire:model.live="dining_comment">
                                </td>

                            </tr>
                            <tr>
                                <td>9</td>
                                <td>Music Room</td>
                                <td>
                                    <input class="kt-radio" type="radio" name="music_room" value="yes"
                                        wire:model.live="music_room">
                                </td>
                                <td>
                                    <input class="kt-radio" type="radio" name="music_room" value="no"
                                        wire:model.live="music_room">
                                </td>
                                <td>
                                    <input type="text" name="music_room_comment" class="form-control"
                                        wire:model.live="music_room_comment">
                                </td>

                            </tr>
                            <tr>
                                <td>10</td>
                                <td>Guidance & Counseling Office</td>
                                <td>
                                    <input class="kt-radio" type="radio" name="guidance_counselling_office"
                                        value="yes" wire:model.live="guidance_counselling_office">
                                </td>
                                <td>
                                    <input class="kt-radio" type="radio" name="guidance_counselling_office"
                                        value="no" wire:model.live="guidance_counselling_office">
                                </td>
                                <td>
                                    <input type="text" class="form-control"
                                        wire:model.live="guidance_counselling_office_comment">
                                </td>

                            </tr>
                            <tr>
                                <td>11</td>
                                <td>Sick Bay/Infirmary</td>
                                <td>
                                    <input class="kt-radio" type="radio" name="infirmary" value="yes"
                                        wire:model.live="infirmary">
                                </td>
                                <td>
                                    <input class="kt-radio" type="radio" name="infirmary" value="no"
                                        wire:model.live="infirmary">
                                </td>
                                <td>
                                    <input type="text" class="form-control" wire:model.live="infirmary_comment">
                                </td>

                            </tr>
                            <tr>
                                <td>12</td>
                                <td>Sporting And Recreational Facilities</td>
                                <td>
                                    <input class="kt-radio" type="radio" name="recreational_facilities"
                                        value="yes" wire:model.live="recreational_facilities">
                                </td>
                                <td>
                                    <input class="kt-radio" type="radio" name="recreational_facilities"
                                        value="no" wire:model.live="recreational_facilities">
                                </td>
                                <td>
                                    <input type="text" class="form-control" style="width: 100%; height: 40px;"
                                        wire:model.live="recreational_facilities_comment">
                                </td>

                            </tr>
                            <tr>
                                <td>13</td>
                                <td>School Boundaries</td>
                                <td>
                                    <input class="kt-radio" type="radio" name="boundaries" value="yes"
                                        wire:model.live="boundaries">
                                </td>
                                <td>
                                    <input class="kt-radio" type="radio" name="boundaries" value="no"
                                        wire:model.live="boundaries">
                                </td>
                                <td>
                                    <input type="text" class="form-control" style="width: 100%; height: 40px;"
                                        wire:model.live="boundaries_comment">
                                </td>

                            </tr>
                            <tr>
                                <td>14</td>
                                <td>Security Post</td>
                                <td>
                                    <input class="kt-radio" type="radio" name="security_post" value="yes"
                                        wire:model.live="security_post">
                                </td>
                                <td>
                                    <input class="kt-radio" type="radio" name="security_post" value="no"
                                        wire:model.live="security_post">
                                </td>
                                <td>
                                    <input type="text" class="form-control" style="width: 100%; height: 40px;"
                                        wire:model.live="security_post_comment">
                                </td>

                            </tr>
                            <tr>
                                <td>15</td>
                                <td>School Dormitories (for boarding schools only)</td>
                                <td>
                                    <input class="kt-radio" type="radio" name="school-dormitories" value="yes"
                                        wire:model.live="school_dormitories">
                                </td>
                                <td>
                                    <input class="kt-radio" type="radio" name="school-dormitories" value="no"
                                        wire:model.live="school_dormitories">
                                </td>
                                <td>
                                    <input type="text" class="form-control" style="width: 100%; height: 40px;"
                                        wire:model.live="school_dormitories_comment">
                                </td>

                            </tr>
                            <tr>
                                <td>16</td>
                                <td>Staff Residence (for boarding schools only)</td>
                                <td>
                                    <input class="kt-radio" type="radio" name="staff-residence" value="yes"
                                        wire:model.live="staff_residence">
                                </td>
                                <td>
                                    <input class="kt-radio" type="radio" name="staff-residence" value="no"
                                        wire:model.live="staff_residence"> 
                                </td>
                                <td>
                                    <input type="text" name="comment" class="form-control"
                                        style="width: 100%; height: 40px;" wire:model.live="staff_residence_comment">
                                </td>

                            </tr>
                            <tr>
                                <td>17</td>
                                <td>School Transportation</td>
                                <td>
                                    <input class="kt-radio" type="radio" name="school-transportation"
                                        value="yes" wire:model.live="school_transportation">
                                </td>
                                <td>
                                    <input class="kt-radio" type="radio" name="school-transportation"
                                        value="no" wire:model.live="school_transportation">
                                </td>
                                <td>
                                    <input type="text" name="comment" style="width: 100%; height: 40px;"
                                        class="form-control" wire:model.live="school_transportation_comment">
                                </td>

                            </tr>
                            <tr>
                                <td>18</td>
                                <td>
                                    Other (specify)
                                    <input type="text" name="other_specified_value" class="form-control"
                                        wire:model.live="other_specified_value">
                                </td>

                                <td>
                                    <input class="kt-radio" type="radio" name="other" value="yes"
                                        wire:model.live="other">
                                </td>

                                <td>
                                    <input class="kt-radio" type="radio" name="other" value="no"
                                        wire:model.live="other">
                                </td>

                                <td>
                                    <input type="text" name="other_comment" style="width: 100%; height: 40px;"
                                        class="form-control" wire:model.live="other_comment">
                                </td>

                            </tr>
                        </tbody>
                    </table>

                    @if ($errors->any())
                        <div style="width: 40%; margin: 0 auto">
                            <span class="text-danger">All fields are required.</span>

                        </div>
                    @endif


                </div>
                <div class="kt-portlet__foot" style="margin-top: 20px; margin-right: 100px;">

                        <div class="kt-form__actions kt-align-right">
                            <button type="button" class="btn btn-outline-dark"
                                wire:click="back">Back</button>
                            <span>&nbsp;</span>
                            <button type="submit" class="btn btn-outline-success">Submit & Continue</button>
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
