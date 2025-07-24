<div>
    <div class="kt-subheader   kt-grid__item" id="kt_subheader">
        <div class="kt-container  kt-container--fluid ">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">
                    Notice of Intent to Open a New School
                </h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>


            </div>
            <div class="kt-subheader__toolbar">



            </div>
        </div>
    </div>
    <div class="kt-portlet" style="margin-top: 25px;">
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-label">
                <h3 class="kt-portlet__head-title">School Leadership Information</h3>
            </div>
            <button class="btn btn-outline-info" type="button" data-bs-toggle="modal"
                data-bs-target="#addLeaderModal" style="height: 35px; margin-top: auto; margin-bottom: auto;">
                Add A New Leader
            </button>
        </div>

    </div>

    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="addLeaderModal" tabindex="-1" aria-labelledby="addLeaderModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addLeaderModalLabel">Add A New Leader</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="#!" wire:submit.prevent="addNewLeader">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="form-control-label">Title</label>
                                <select name="title" id="" class="form-control" wire:model="person_title">
                                    <option value="">Select a Title</option>
                                    <option value="prof.">Prof.</option>
                                    <option value="dr.">Dr.</option>
                                    <option value="eng.">Eng.</option>
                                    <option value="mr.">Mr.</option>
                                    <option value="mrs.">Mrs.</option>
                                    <option value="ms.">Ms.</option>
                                    <option value="miss">Miss</option>
                                    <option value="lawyer">Lawyer</option>
                                    <option value="other">Prof.</option>
                                </select>
                                @error('person_title')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label class="form-control-label">Role</label>
                                <input type="text" class="form-control" wire:model="role">
                                @error('role')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label class="form-control-label">Full Name</label>
                                <input type="text" class="form-control" wire:model="full_name">
                                @error('full_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label class="form-control-label">Permanent Residential Address</label>
                                <textarea name="" class="form-control" wire:model="permanent_residential_address" cols="10" rows="3"></textarea>
                                @error('permanent_residential_address')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label class="form-control-label">National Teaching Council (NTC) licensure
                                    number</label>
                                <input type="text" class="form-control" wire:model="ntc_license_number">
                                @error('ntc_license_number')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group">
                                <label class="form-control-label">Gender</label>
                                <select name="gender" class="form-control" wire:model="gender">
                                    <option value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                                @error('gender')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Email Address</label>
                                    <input type="text" class="form-control" wire:model="email_address">
                                    @error('email_address')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Telephone Number</label>
                                    <input type="text" class="form-control" wire:model="telephone_number">
                                    @error('telephone_number')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label class="form-control-label">Highest Academic Qualification</label>
                                <input type="text" class="form-control"
                                    wire:model="highest_academic_qualification">
                                @error('highest_academic_qualification')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label class="form-control-label">Highest Professional Qualification</label>
                                <input type="text" class="form-control"
                                    wire:model="highest_professional_qualification">
                                @error('highest_professional_qualification')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="kt-portlet__foot">

                            <div class="kt-form__actions kt-align-right">
                                {{-- <button type="button" class="btn btn-secondary" data-dismiss="addLeaderModal">Close</button> --}}
                                <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal"
                                    aria-label="Close">Close</button>
                                <span>&nbsp;</span>
                                <button type="submit" class="btn btn-outline-success">Save & Continue</button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Main content -->
    <div>
        <div class="row">
            <div class="col-xl-12 col-lg-12 order-lg-1 order-xl-1">
                <!--begin:: Widgets/Application Sales-->
                <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-portlet__body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="kt_widget11_tab1_content">
                                <!--begin::Widget 11-->
                                <div class="kt-widget11">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr style="font-weight: bold; font-size: 14px">
                                                    <td style="">#</td>
                                                    <td style="">Title</td>
                                                    <td style="">Name</td>
                                                    <td style="">Role</td>
                                                    <td style="">Telephone</td>
                                                    <td style="">Email</td>
                                                    <td style="">Academic Qualification</td>
                                                    <td style="">Professional Qualification</td>
                                                    <td style="">Action</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {{-- @dd($results); --}}
                                                @if ($results['result'] == [])
                                                    <tr>
                                                        <td colspan="5" class="text-center"
                                                            style="font-size: 16px">You have no registered Leaders</td>
                                                    </tr>
                                                @else
                                                    @foreach ($results['result'] as $index => $leader)
                                                        <tr>
                                                            <td style="text-align: center">
                                                                <span class="text-muted">
                                                                    {{ $loop->iteration }}
                                                                </span>
                                                            </td>
                                                            <td>{{ $results['result'][$index]['person_title'] }}</td>
                                                            <td>{{ $results['result'][$index]['full_name'] }}</td>
                                                            <td>{{ $leader['role'] }}</td>
                                                            <td>{{ $leader['telephone_number'] }}</td>
                                                            {{-- <td>{{ $leader['permanent_residential_address'] }}</td> --}}
                                                            <td>{{ $leader['email_address'] }}</td>
                                                            {{-- <td>{{ $leader['ntc_license_number'] }}</td> --}}
                                                            <td>{{ $leader['highest_academic_qualification'] }}</td>
                                                            <td>{{ $leader['highest_professional_qualification'] }}</td>
                                                            <td>
                                                                <button type="button" class="btn btn-outline-warning"
                                                                    wire:click="editLeader({{ json_encode($leader) }})"
                                                                    data-toggle="modal"
                                                                    data-target="#updateLeaderModal">Edit</button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif

                                            </tbody>
                                        </table>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="kt-portlet__foot">

            <div class="kt-form__actions kt-align-right">
                <button type="button" class="btn btn-outline-dark" wire:click="back">Back</button>
                <span>&nbsp;</span>
                <button type="button" class="btn btn-outline-success" wire:click="nextSection">Next
                    Section</button>
            </div>

        </div>
    </div>


    {{-- edit modal --}}
    @if($selected_leader)
    <div wire:ignore.self class="modal fade" id="updateLeaderModal" tabindex="-1" role="dialog"
        aria-labelledby="editLeaderModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateLeaderModalLabel">Edit Leader info</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                        {{-- @dd($selected_leader) --}}
                        <form wire:submit.prevent="updateLeader">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="proprietorGender">Title</label>
                                    <select class="form-control" id="personTitle"
                                        wire:model="selected_leader.person_title">
                                        @foreach ($leader_titles as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="proprietorGender">Gender</label>
                                    <select class="form-control" id="proprietorGender"
                                        wire:model="selected_leader.gender">
                                        @foreach ($leader_genders as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('selected_leader.gender')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="proprietorName">Role</label>
                                <input type="text" class="form-control" id="proprietorName"
                                    wire:model="selected_leader.role">
                                @error('selected_leader.role')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="proprietorName">Full Name</label>
                                <input type="text" class="form-control" id="proprietorName"
                                    wire:model="selected_leader.full_name">
                                @error('selected_leader.fullname')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-control-label">Permanent Residential Address</label>
                                <textarea name="" class="form-control" wire:model="selected_leader.permanent_residential_address" cols="10" rows="3"></textarea>
                                @error('selected_leader.permanent_residential_address')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>


                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="proprietorTelephone">NTC License Number</label>
                                    <input type="text" class="form-control" id="proprietorTelephone"
                                        wire:model="selected_leader.ntc_license_number">
                                    @error('selected_leader.ntc_license_number')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="proprietorOccupation">Telephone Number</label>
                                    <input type="text" class="form-control" id="proprietorOccupation"
                                        wire:model="selected_leader.telephone_number">
                                    @error('selected_leader.telephone_number')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>


                            <div class="form-group">
                                <label for="proprietorOccupation">Email Address</label>
                                <input type="email" class="form-control" id="proprietorOccupation"
                                    wire:model="selected_leader.email_address">
                                @error('selected_leader.email_address')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>


                            <div class="form-group">
                                <label for="proprietorQualification">Academic Qualification</label>
                                <input type="text" class="form-control" id="proprietorQualification"
                                    wire:model="selected_leader.highest_academic_qualification">
                                @error('selected_leader.highest_academic_qualification')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="proprietorQualification">Professional Qualification</label>
                                <input type="text" class="form-control" id="proprietorQualification"
                                    wire:model="selected_leader.highest_professional_qualification">
                                @error('selected_leader.highest_professional_qualification')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-outline-success">Save Changes</button>
                            </div>
                        </form>
                    @endif
                </div>

            </div>
        </div>

    </div>




</div>
