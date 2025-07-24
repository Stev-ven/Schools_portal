<div>
    <div class="kt-subheader   kt-grid__item" id="kt_subheader">
        <div class="kt-container  kt-container--fluid ">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">
                    Expression of Interest to Establish a New School
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
    <div class="kt-portlet" style="margin-top: 25px;">
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-label">
                <h3 class="kt-portlet__head-title">Proprietor Information</h3>
            </div>
        </div>
        <div class="kt-portlet__body">
            <div class="form-group row">
                <div class="form-group col-md-6">
                    <label>School Ownership</label>
                    <div class="kt-radio-inline" required>
                        <label class="kt-radio">
                            <input type="radio" name="proprietor_info_type" value="organisation"
                                wire:model.live="proprietor_info_type"> Organisation
                            <span></span>
                        </label>
                        <label class="kt-radio">
                            <input type="radio" name="proprietor_info_type" value="individuals"
                                wire:model.live="proprietor_info_type"> Individual(s)
                            <span></span>
                        </label>

                    </div>
                    <p class="text-danger">
                        @error('proprietor_info_type')
                            {{ $message }}
                        @enderror
                    </p>
                    {{-- <span class="form-text text-muted">Some help text goes here</span> --}}
                </div>
            </div>
            @if ($proprietor_info_type == 'individuals')
                <div class="row">
                    <div class=" col-md-6 kt-portlet__foot">
                        <div class="kt-form__actions">
                            <button class="btn btn-outline-info btn-label-info" type="button" data-toggle="modal"
                                data-target="#addIndividualsModal">Add Proprietor</button>
                            <p class="text-danger">Add details of at least one proprietor</p>
                        </div>
                    </div>
                </div>
            @elseif($proprietor_info_type == 'organisation')
                <div class="row">
                    <div class=" col-md-6 kt-portlet__foot">
                        <div class="kt-form__actions">
                            <button class="btn btn-outline-info btn-label-info" type="button" data-toggle="modal"
                                data-target="#addOrganisationModal">Update Organisation</button>
                            <p class="text-danger">Add details of organisation</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="kt-portlet__foot">
                    <div class="kt-form__actions">
                        <button class="btn btn-outline-info btn-label-info" type="buton" data-toggle="modal"
                            data-target="#addOrganisationsModal" disabled>Add Proprietor</button>
                        <p class="text-danger">Add details of at least one proprietor</p>
                    </div>
                </div>
            @endif

            @if (!empty($results['proprietors']))
                <div class="kt-widget11">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">Proprietors</h3>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Name</th>
                                    <th>Gender</th>
                                    <th>Email</th>
                                    <th>Contact</th>
                                    <th>Occupation</th>
                                    <th>Qualification</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($proprietors as $proprietor)
                                    <tr>
                                        <td class="pl-2">{{ ucfirst($proprietor['person_title']) }}</td>
                                        <td class="pl-2">{{ ucfirst($proprietor['name']) }}</td>
                                        <td class="pl-2">{{ ucfirst($proprietor['gender']) }}</td>
                                        <td class="pl-2">{{ $proprietor['email_address'] }}</td>
                                        <td class="pl-2">{{ $proprietor['telephone_number'] }}</td>
                                        <td class="pl-2">{{ ucfirst($proprietor['occupation']) }}</td>
                                        <td class="pl-2">{{ ucfirst($proprietor['qualification']) }}</td>
                                        <td class="pl-2">
                                            <button type="button" class="btn btn-outline-warning btn-label-warning"
                                                wire:click="editProprietor({{ json_encode($proprietor) }})"
                                                data-toggle="modal" data-target="#updateProprietorModal">Edit</button>

                                            <button type="button" class="btn btn-outline-danger btn-label-danger"
                                                wire:click="removeProprietor({{ json_encode($proprietor) }})">
                                                Remove
                                            </button>


                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
            @if (!empty($results['organization']))
                <div class="kt-widget11">
                    <div class="table-responsive">
                        <div class="kt-portlet__head">
                            <div class="kt-portlet__head-label">
                                <h3 class="kt-portlet__head-title">Organisation</h3>
                            </div>
                        </div>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Name of Organisation</th>
                                    <th>Organisation Email Address</th>
                                    <th>Organisation Contact</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="pl-2">{{ ucfirst($results['organization']['organization_name']) }}
                                    </td>
                                    <td class="pl-2">{{ $results['organization']['organization_email'] }}</td>
                                    <td class="pl-2">{{ $results['organization']['organization_contact'] }}</td>
                                    <td class="pl-2">

                                        <button type="button" class="btn btn-outline-danger btn-label-danger"
                                            wire:click="removeOrganisation({{ json_encode($results['organization']) }})"
                                            data-toggle="modal" data-target="#deleteOrganisationModal">Remove</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

        </div>
    </div>

    {{-- add individual proprietors to empty proprietor array --}}
    <div wire:ignore.self class="modal fade" id="addIndividualsModal" tabindex="-1"
        aria-labelledby="individualsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="individualsModalLabel">Add Proprietor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="#!" wire:submit.prevent="addIndividualProprietor">
                        <div class="form-group">
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


                        <div class="form-group">
                            <label class="form-control-label">Name</label>
                            <input type="text" class="form-control" wire:model="person_name">
                            @error('person_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                        <div class="form-group">
                            <label class="form-control-label">Email Address</label>
                            <input type="text" class="form-control" wire:model="person_email">
                            @error('person_email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                        <div class="form-group">
                            <label class="form-control-label">Gender</label>
                            <select name="gender" id="" class="form-control" wire:model="person_gender">
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                            @error('person_gender')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                        <div class="form-group">
                            <label class="form-control-label">Telephone Number</label>
                            <input type="text" class="form-control" wire:model="person_telephone">
                            @error('person_telephone')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                        <div class="form-group">
                            <label class="form-control-label">Occupation</label>
                            <input type="text" class="form-control" wire:model="person_occupation">
                            @error('person_occupation')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                        <div class="form-group">
                            <label class="form-control-label">Qualification</label>
                            <input type="text" class="form-control" wire:model="person_qualification">
                            @error('person_qualification')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="kt-portlet__foot">

                            <div class="kt-form__actions kt-align-right">
                                <button type="button" class="btn btn-outline-dark btn-label-dark"
                                    data-dismiss="modal">Close</button>
                                <span>&nbsp;</span>
                                <button type="submit" class="btn btn-outline-success btn-label-success">Save &
                                    Continue</button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
        @script
            <script>
                $wire.on('notifyindpropadded', (items) => {
                    Swal.fire({
                        title: "Proprietor added successfully!",
                        // text: "Proceed to proprietor details",
                        icon: "success",
                        showCancelButton: false,
                        confirmButtonColor: "#3085d6",
                        // cancelButtonColor: "#d33",
                        // confirmButtonText: "Yes! Proceed",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $wire.dispatch('redirect');
                        }
                    });
                })
            </script>
        @endscript
    </div>

    {{-- add organisation to empty proprietor array --}}
    <div wire:ignore.self class="modal fade" id="addOrganisationModal" tabindex="-1"
        aria-labelledby="organisationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editDetailsModalLabel">Organisation Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="#!" wire:submit.prevent="addOrganisation">

                        <div class="form-group">
                            <label class="form-control-label">Name of Organisation</label>
                            <input type="text" class="form-control" wire:model.="organisation_name">
                            @error('organisation_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                        <div class="form-group">
                            <label class="form-control-label">Organisation Email Address</label>
                            <input type="text" class="form-control" wire:model="organisation_email">
                            @error('organisation_email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                        <div class="form-group">
                            <label class="form-control-label">Telephone Number</label>
                            <input type="text" class="form-control" wire:model="organisation_telephone">
                            @error('organisation_telephone')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="modal-header">
                            <h5 class="modal-title" id="editDetailsModalLabel">Proprietor Details</h5>
                        </div>
                        <div class="row" style="margin-top: 20px;">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Title</label>
                                    <select name="title" id="" class="form-control"
                                        wire:model="proprietor_title">
                                        <option value="">Select a Title</option>
                                        <option value="prof.">Prof.</option>
                                        <option value="dr.">Dr.</option>
                                        <option value="eng.">Eng.</option>
                                        <option value="mr.">Mr.</option>
                                        <option value="mrs.">Mrs.</option>
                                        <option value="ms.">Ms.</option>
                                        <option value="miss">Miss</option>
                                        <option value="lawyer">Lawyer</option>
                                        <option value="other">Other</option>
                                    </select>
                                    @error('proprietor_title')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">FullName</label>
                                    <input type="text" class="form-control" wire:model="proprietor_name">
                                    @error('proprietor_name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Email Address</label>
                                    <input type="text" class="form-control" wire:model="proprietor_email">
                                    @error('proprietor_email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Gender</label>
                                    <select name="gender" id="" class="form-control"
                                        wire:model="proprietor_gender">
                                        <option value="">Select Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                    @error('proprietor_gender')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Telephone Number</label>
                                    <input type="text" class="form-control" wire:model="proprietor_telephone">
                                    @error('person_telephone')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Occupation</label>
                                    <input type="text" class="form-control" wire:model="proprietor_occupation">
                                    @error('proprietor_occupation')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Qualification</label>
                                    <input type="text" class="form-control" wire:model="proprietor_qualification">
                                    @error('proprietorr_qualification')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="kt-portlet__foot">

                            <div class="kt-form__actions kt-align-right">
                                <button type="button" class="btn btn-btn-outline-dark btn-label-dark"
                                    data-dismiss="modal">Close</button>
                                <span>&nbsp;</span>
                                <button type="submit" class="btn btn-outline-success btn-label-success">Save &
                                    Continue</button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
        @script
            <script>
                $wire.on('notifyorgupdate', (items) => {
                    Swal.fire({
                        title: "Details have been updated successfully!",
                        // text: "Proceed to proprietor details",
                        icon: "success",
                        showCancelButton: false,
                        confirmButtonColor: "#3085d6",
                        // cancelButtonColor: "#d33",
                        // confirmButtonText: "Yes! Proceed",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $wire.dispatch('redirect');
                        }
                    });
                })
            </script>
        @endscript
    </div>

    {{-- Edit organisation Modal End --}}
    <div wire:ignore.self class="modal fade" id="updateOrganisationModal" tabindex="-1"
        aria-labelledby="organisationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editDetailsModalLabel">Organisation Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="#!" wire:submit.prevent="addOrganisation">

                        <div class="form-group">
                            <label class="form-control-label">Name of Organisation</label>
                            <input type="text" class="form-control" wire:model="organisation_name">
                            @error('organisation_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                        <div class="form-group">
                            <label class="form-control-label">Organisation Email Address</label>
                            <input type="text" class="form-control" wire:model="organisation_email">
                            @error('organisation_email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                        <div class="form-group">
                            <label class="form-control-label">Telephone Number</label>
                            <input type="text" class="form-control" wire:model="organisation_telephone">
                            @error('organisation_telephone')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="modal-header">
                            <h5 class="modal-title" id="editDetailsModalLabel">Proprietor Details</h5>
                        </div>
                        <div class="row" style="margin-top: 20px;">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Title</label>
                                    <select name="title" id="" class="form-control"
                                        wire:model="proprietor_title">
                                        <option value="">Select a Title</option>
                                        <option value="prof.">Prof.</option>
                                        <option value="dr.">Dr.</option>
                                        <option value="eng.">Eng.</option>
                                        <option value="mr.">Mr.</option>
                                        <option value="mrs.">Mrs.</option>
                                        <option value="ms.">Ms.</option>
                                        <option value="miss">Miss</option>
                                        <option value="lawyer">Lawyer</option>
                                        <option value="other">Other</option>
                                    </select>
                                    @error('proprietor_title')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">FullName</label>
                                    <input type="text" class="form-control" wire:model="proprietor_name">
                                    @error('proprietor_name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Email Address</label>
                                    <input type="text" class="form-control" wire:model="proprietor_email">
                                    @error('proprietor_email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Gender</label>
                                    <select name="gender" id="" class="form-control"
                                        wire:model="proprietor_gender">
                                        <option value="">Select Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                    @error('proprietor_gender')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Telephone Number</label>
                                    <input type="text" class="form-control" wire:model="proprietor_telephone">
                                    @error('person_telephone')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Occupation</label>
                                    <input type="text" class="form-control" wire:model="proprietor_occupation">
                                    @error('proprietor_occupation')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Qualification</label>
                                    <input type="text" class="form-control" wire:model="proprietor_qualification">
                                    @error('proprietorr_qualification')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="kt-portlet__foot">

                            <div class="kt-form__actions kt-align-right">
                                <button type="button" class="btn btn-btn-outline-dark btn-label-dark"
                                    data-dismiss="modal">Close</button>
                                <span>&nbsp;</span>
                                <button type="submit" class="btn btn-outline-success btn-label-success">Save &
                                    Continue</button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
        @script
            <script>
                $wire.on('notifyorgupdate', (items) => {
                    Swal.fire({
                        title: "Details have been updated successfully!",
                        // text: "Proceed to proprietor details",
                        icon: "success",
                        showCancelButton: false,
                        confirmButtonColor: "#3085d6",
                        // cancelButtonColor: "#d33",
                        // confirmButtonText: "Yes! Proceed",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $wire.dispatch('redirect');
                        }
                    });
                })
            </script>
        @endscript
    </div>

    {{-- Edit Proprietor Modal Begin --}}
    <div wire:ignore.self class="modal fade" id="updateProprietorModal" tabindex="-1" role="dialog"
        aria-labelledby="editProprietorModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            @if ($selected_proprietor)
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateProprietorModalLabel">Edit Proprietor</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <form wire:submit.prevent="updateProprietor">
                            <div class="form-group">
                                <label for="proprietorGender">Title</label>
                                <select class="form-control" id="proprietorTitle"
                                    wire:model="selected_proprietor.title">
                                    @foreach ($proprietor_titles as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('selected_proprietor.title')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="proprietorName">Name</label>
                                <input type="text" class="form-control" id="proprietorName"
                                    wire:model="selected_proprietor.name">
                                @error('selected_proprietor.name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="proprietorEmail">Email</label>
                                <input type="email" class="form-control" id="proprietorEmail"
                                    wire:model="selected_proprietor.email_address">
                                @error('selected_proprietor.email_address')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="proprietorTelephone">Telephone</label>
                                <input type="text" class="form-control" id="proprietorTelephone"
                                    wire:model="selected_proprietor.telephone_number">
                                @error('selected_proprietor.telephone_number')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="proprietorGender">Gender</label>
                                <select class="form-control" id="proprietorGender"
                                    wire:model="selected_proprietor.gender">
                                    @foreach ($proprietor_genders as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('selected_proprietor.gender')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="proprietorOccupation">Occupation</label>
                                <input type="text" class="form-control" id="proprietorOccupation"
                                    wire:model="selected_proprietor.occupation">
                                @error('selected_proprietor.occupation')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="proprietorQualification">Qualification</label>
                                <input type="text" class="form-control" id="proprietorQualification"
                                    wire:model="selected_proprietor.qualification">
                                @error('selected_proprietor.qualification')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-btn-outline-dark btn-label-dark"
                                    data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-outline-success btn-label-success">Save
                                    Changes</button>
                            </div>
                        </form>

                    </div>

                </div>
            @endif
        </div>
        @script
            <script>
                $wire.on('notifyupdate', (items) => {
                    Swal.fire({
                        title: "Details have been updated successfully!",
                        // text: "Proceed to proprietor details",
                        icon: "success",
                        showCancelButton: false,
                        confirmButtonColor: "#3085d6",
                        // cancelButtonColor: "#d33",
                        // confirmButtonText: "Yes! Proceed",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $wire.dispatch('redirect');
                        }
                    });
                })
            </script>
        @endscript
    </div>

    {{-- Delete Proprietor Modal Begin --}}

    <div wire:ignore.self class="modal fade" id="show-delete-proprietor-modal" tabindex="-1" role="dialog"
        aria-labelledby="deleteProprietorModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteProprietorModalLabel">Delete Proprietor</h5>
                </div>
                <div class="modal-body">
                    @if ($selected_proprietor)

                        <form wire:submit.prevent="deleteProprietory">
                            <div class="form-group">
                                <label for="proprietorGender">Title</label>
                                <select class="form-control" id="proprietorTitle"
                                    wire:model="selected_proprietor.title" readonly>
                                    @foreach ($proprietor_titles as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="proprietorName">Name</label>
                                <input type="text" class="form-control" id="proprietorName"
                                    wire:model="selected_proprietor.name" readonly>
                            </div>

                            <div class="form-group">
                                <label for="proprietorName">Email</label>
                                <input type="text" class="form-control" id="proprietorName"
                                    wire:model="selected_proprietor.email_address">
                            </div>
                            <div class="form-group">
                                <label for="proprietorName">Telephone</label>
                                <input type="text" class="form-control" id="proprietorName"
                                    wire:model="selected_proprietor.telephone_number" readonly>
                            </div>
                            <div class="form-group">
                                <label for="proprietorName">Gender</label>
                                <select name="proprietorGender" wire:model="selected_proprietor.gender"
                                    class="form-control">
                                    @foreach ($proprietor_genders as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="proprietorName">Occupation</label>
                                <input type="text" class="form-control" id="proprietorName"
                                    wire:model="selected_proprietor.occupation" readonly>
                            </div>
                            <div class="form-group">
                                <label for="proprietorName">Qualification</label>
                                <input type="text" class="form-control" id="proprietorName"
                                    wire:model="selected_proprietor.qualification" readonly>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-dark btn-label-dark"
                                    wire:click="closeDeleteProprietorModal">Cancel</button>
                                {{-- <button type="submit" class="btn btn-danger">Confirm Delete</button> --}}
                                <button type="submit" class="btn btn-outline-danger btn-label-danger"
                                    wire:loading.attr="disabled">
                                    Confirm Delete
                                </button>
                            </div>
                        </form>
                    @endif
                </div>

            </div>
        </div>

        @script
            <script>
                $wire.on('notifydelete', (items) => {
                    Swal.fire({
                        title: "Proprietor Deleted",
                        // text: "Proceed to proprietor details",
                        icon: "success",
                        showCancelButton: false,
                        confirmButtonColor: "#3085d6",
                        // cancelButtonColor: "#d33",
                        // confirmButtonText: "Yes! Proceed",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $wire.dispatch('redirect');
                        }
                    });
                })
            </script>
        @endscript

    </div>

    {{-- Delete Proprietor Modal End --}}

    {{-- delete organisation modal begin --}}
    <div wire:ignore.self class="modal fade" id="deleteOrganisationModal" tabindex="-1" role="dialog"
        aria-labelledby="deleteOrganisationModalLabel" aria-hidden="true">

        <div class="modal-dialog" role="document">
            @if ($selected_organisation)
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteOrganisationModalLabel">Delete Organisation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <form wire:submit.prevent="deleteOrganisation">
                            <div class="form-group">
                                <label for="organisationName">Organisation Name</label>
                                <input type="text" class="form-control" id="organisationName"
                                    wire:model="selected_organisation.organization_name" readonly>
                            </div>
                            <div class="form-group">
                                <label for="organisationEmail">Organisation Email Address</label>
                                <input type="text" class="form-control" id="organisationEmail"
                                    wire:model="selected_organisation.organization_email" readonly>
                            </div>
                            <div class="form-group">
                                <label for="organisationContact">Organisation Contact</label>
                                <input type="text" class="form-control" id="organisationContact"
                                    wire:model="selected_organisation.organization_contact">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-dark btn-label-dark"
                                    data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-outline-danger btn-label-danger">Confirm
                                    Delete</button>
                            </div>
                        </form>

                    </div>

                </div>
            @endif
        </div>

    </div>
    {{-- delete organisation modal end --}}
</div>
@script
    <script>
        //close add-proprietor-modal
        Livewire.on('close-add-individual-modal', () => {
            $('#addIndividualsModal').modal('hide');
        });

        window.addEventListener('show-delete-proprietor-modal', () => {
            $('#show-delete-proprietor-modal').modal('show');
        });

        // Close the modal
        window.addEventListener('close-delete-proprietor-modal', () => {
            $('#show-delete-proprietor-modal').modal('hide');
        });
    </script>
@endscript
