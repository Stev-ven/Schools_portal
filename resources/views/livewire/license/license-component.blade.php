<div>
    {{-- The best athlete wants his opponent at his best. --}}


    <div class="row">
        <div class="col-xl-12 col-lg-12 order-lg-1 order-xl-1">

            <!--begin:: Widgets/Application Sales-->
            <div class="kt-portlet kt-portlet--height-fluid" style="margin-top: 15px;">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            License Schools / Campuses
                        </h3>
                    </div>

                </div>
                <div class="kt-portlet__body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="kt_widget11_tab1_content">

                            <!--begin::Widget 11-->
                            <div class="kt-widget11">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <td style=" width: 1%;">#</td>
                                                <td style=" width: 20%;">School Name</td>
                                                <td style=" width: 10%;">Nature</td>
                                                <td style=" width: 10%;">Application</td>
                                                <td style=" width: 10%;">License Expiry Date</td>
                                                <td style=" width: 10%;" class="">License Renewal</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($schools as $key => $school)
                                                <tr class="">
                                                <td>
                                                    <span class="text-muted">
                                                                {{$key +1 }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="kt-widget11__title">{{$school->school_name}}</span>

                                                </td>
                                                <td>
                                                    <a $wire:click="viewApplication({{$school->id}})" type="button" class="btn rounded btn-label-info btn-sm btn-bold"><i class="flaticon-eye" > </i> View Application</a>
                                                </td>

                                                 <td><span class="kt-badge kt-badge--warning kt-badge--inline">
                                                     @if(Empty($school->current_license_issued_expiry_date))
                                                     <i class='flaticon-warning'></i>
                                                      @else
                                                      <i class='flaticon-event-calendar-symbol mr-2'> </i>
                                                      @endif {{ $school->current_license_issued_expiry_date}}S
                                                    </span> </td>
                                                <td><span class="kt-badge kt-badge--info kt-badge--inline"><i class='flaticon-event-calendar-symbol'></span></td>

                                            </tr>
                                            @endforeach




                                        </tbody>
                                    </table>
                                </div>
                                <div class="kt-widget11__action kt-align-right">
                                    <button wire:click='licenseSchool' type="button" class="btn btn-label-success btn-sm btn-bold">

                                        <span wire:loading.remove wire:target="licenseSchool">License a school</span>
                        <span wire:loading wire:target="licenseSchool" class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                        <span wire:loading wire:target="licenseSchool"role="status">Loading...</span>


                                    </button>
                                </div>
                            </div>

                            <!--end::Widget 11-->
                        </div>

                    </div>
                </div>
            </div>

            <!--end:: Widgets/Application Sales-->
        </div>






    </div>
</div>
