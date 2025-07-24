
@extends('template.app')
@section('main-content')
    <div class="row">
        <div class="col-md-12">
            <!--begin::Portlet-->
            <div class="kt-portlet">
                <div class="kt-portlet__head" style="margin-top: 25px;">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Registered School
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>School Name</th>
                                <th>Nature</th>
                                <th>Action</th>
                                <th>License Expiry Date</th>
                                <th>License Renewal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td style="margin: 0;">Kwame Lal school</td>
                                <td>
                                    Main campuse
                                </td>
                                <td>
                                    <button class="btn btn-info">
                                        View Application
                                    </button>
                                </td>
                                <td>

                                        -
                                    
                                </td>
                                <td>
                                    -
                                </td>
                            </tr>


                            <!-- Repeat the above tr tags for the remaining rows -->
                        </tbody>
                    </table>
                </div>
            </div>
            <!--end::Portlet-->
        </div>
    </div>
@endsection
