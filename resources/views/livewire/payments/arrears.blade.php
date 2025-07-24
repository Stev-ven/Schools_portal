<div>
    <div class="kt-subheader   kt-grid__item" id="kt_subheader">
        <div class="kt-container  kt-container--fluid ">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">
                    Arrears History
                </h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
            </div>
            <div class="kt-subheader__toolbar">
            </div>
        </div>
    </div>
    <div class="col-md-12" style="margin-top: 25px;">
        <!--begin::Portlet-->
        <div class="kt-portlet">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title" style="font-weight: bold;">All Arrears</h3>
                </div>
            </div>
            <!-- table -->
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Arrears ID</th>
                        <th>Numbeer of Years</th>
                        <th>Amount Due</th>
                        <th>Payment Status</th>
                        <th>Date Paid</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Remis Int.school</td>
                        <td>Application for authorization</td>
                        <td>123456789</td>
                        <td>
                            {{-- <button class="btn btn-danger" disabled>
                            Not paid
                        </button> --}}
                            <span
                                class="badge badge-danger">
                                Not paid
                            </span>
                        </td>
                        <td>
                            ----
                        </td>
                        <td>
                            <button class="btn btn-outling-success btn-label-success">
                                Download
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Kwame Lal school</td>
                        <td>Notice of intent</td>
                        <td>
                            123456789
                        </td>
                        <td>
                            <span class="badge badge-success">
                                paid
                            </span>

                        </td>
                        <td>
                           ----
                        </td>
                        <td>
                            <button class="btn btn-outling-success btn-label-success">
                                Download
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
