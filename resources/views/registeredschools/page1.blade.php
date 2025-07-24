<<<<<<< HEAD

@extends('template.app')

@section('main-content')
    <div class="row">
        <div class="col-md-12">
            <!--begin::Portlet-->
            <div class="kt-portlet">
                <div class="kt-portlet__head" style="margin-top: 25px;">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Registered Schools > Kwame Lal > Applications
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    {{-- table --}}
                    <table class="table table-striped  table-responsive">
                        <thead >
                            <tr >
                                <th>Application type</th>
                                <th>Application status</th>
                                <th>Payment status</th>
                                <th>Action</th>
                                <th>Date & time paid</th>
                                <th>Date & time added</th>
                                <th>More option</th>

                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Application for authorization</td>
                                <td>
                                    <button class="btn btn-danger">
                                        Pending
                                    </button>
                                </td>
                                <td>
                                    <button class="btn btn-success">
                                        Paid
                                    </button>
                                </td>
                                <td>
                                    <button class="btn btn-warning">
                                        Edit
                                    </button>
                                </td>
                                <td>--</td>
                                <td>--</td>
                                <td>
                                    <button class="btn btn-info">
                                        More info
                                    </button>
                                </td>

                            </tr>
                            <tr>
                                <td>Notice of intent</td>
                                <td>
                                    <button class="btn btn-danger">
                                        Pending
                                    </button>
                                </td>
                                <td>
                                    <button class="btn btn-success">
                                        Paid
                                    </button>
                                </td>
                                <td>
                                    <button class="btn btn-warning">
                                        Edit
                                    </button>
                                </td>
                                <td>--</td>
                                <td>--</td>
                                <td>
                                    <button class="btn btn-info">
                                        More info
                                    </button>
                                </td>

                            </tr>


                        </tbody>
                    </table>
                </div>
            </div>
            <!--end::Portlet-->
        </div>
    </div>
@endsection
=======
>>>>>>> stev_branch
