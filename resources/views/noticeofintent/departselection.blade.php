@extends('template.app')

@section('main-content')

<form class="kt-form">
    <div class="row">
        <div class="col-md-12" >
            <!--begin::Portlet-->
            <div class="kt-portlet" style="margin-top: 25px;">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title" style="font-weight: bold; margin-left: 50px;">>Notice of Intent Department selection</h3>
                    </div>
                </div>
                <div class="kt-portlet__body" style="margin-left: 50px;">
                    <div class="form-group">
                        <div class="kt-checkbox-list">
                            <label for="curriculum">Select Curriculum</label>
                            <label class="kt-checkbox">
                                <input type="checkbox" name="agree"> National curriculum (NaCCA/GES)
                                <span></span>
                            </label>
                        </div>
                    </di>
                    </div>
                    <div class="form-group">
                        <label>Total Enrolments</label>
                        <input type="text" class="form-control" name="total_enrolment" placeholder="Enter Total Enrolment" style="width: 60%;">
                    </div>
                    <div class="form-group">
                        <label>Enrolment Range</label>
                        <input type="text" class="form-control" name="Enrolment_range" placeholder="Enter Enrolment Range" style="width: 60%;">
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <button type="submit" class="btn btn-info">Back</button>
                            <button type="submit" class="btn btn-success">Save & continue</button>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Portlet-->
        </div>
    </div>
</form>




@endsection
