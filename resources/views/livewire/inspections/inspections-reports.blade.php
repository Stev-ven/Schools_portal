{{-- <form class="kt-form" style="width: 90%; height: 85%; align-items: center; margin: 50px auto;">
    <div class="row">
        <div class="col-md-12">

            <div class="kt-portlet" style="margin-top: 25px;">
                <div class="kt-portlet__head" style="display: flex; justify-content: space-between; align-items: center;">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title" style="font-weight: bold;">INSPECTION REPORTS</h3>
                    </div>
                    <div class="kt-portlet__head-right" style="width: 300px;">

                        <select id="multiselect" multiple class="form-control" style="width:80%;"  onfocus="hidePlaceholder()" onblur="showPlaceholder()">
                            <option value="" disabled selected hidden>Select options...</option>
                            <option value="option1">Option 1</option>
                            <option value="option2">Option 2</option>
                            <option value="option3">Option 3</option>
                            <option value="option4">Option 4</option>
                        </select>
                    </div>
                </div>
                <p style="margin-top: 10px; font-size: 14px; margin-left: 20px; font-weight: bold;">PAGE 0 OF 0</p>
                <p style="font-size: 14px; margin-left: 20px; margin-top: 0; font-weight: bold;">Total reports 0</p>
                <div class="kt-portlet__body">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title" style="font-weight: bold; font-size: 15px; margin-left: 20px;">Facilities checklist</h3>
                    </div>
                    <table class="table table-striped table-bordered kt-table" style="width: 100%; text-align: center;">
                        <thead>
                            <tr>
                                <th>Report file Name</th>
                                <th>Type</th>
                                <th>Date & time received</th>
                                <th>Date & time updated</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>----</td>
                                <td>----</td>
                                <td>----</td>
                                <td>----</td>
                                <td>----</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="kt-portlet__foot" style="margin-left: 100px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</form> --}}


<div>
    <div class="kt-subheader   kt-grid__item" id="kt_subheader">
        <div class="kt-container  kt-container--fluid ">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">
                    Inspection Reports
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
                <h3 class="kt-portlet__head-title" style="font-weight: bold;">All Inspection Reports</h3>
            </div>
            <div class="filter-input mt-3">
                <input type="number" class="form-control" placeholder="Filter by (All)" style="width: 300px; height: 40px; margin-left: 50px;">
            </div>
        </div>
        <!-- table -->
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Report File Name</th>
                    <th>Type</th>
                    <th>Date &  Time Received</th>
                    <th>Date & Time Updated</th>
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
                        <span class="badge badge-danger">
                            Not paid
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-success btn-label-success">
                            Make payment
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
                            Paid
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-outline-success btn-label-success" disabled>
                            Download receipt
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
</div>

