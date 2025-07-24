@extends('template.app')

@section('main-content')
<form class="kt-form" style="margin-bottom: 50px;">
    <div class="row">
        <div class="col-md-12">
            <!--begin::Portlet-->
            <div class="kt-portlet" style="margin-top: 25px;">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title" style="font-weight: bold; margin-left: 50px;">Confirm Submission</h3>
                    </div>
                </div>
                <div class="kt-portlet__body" style="margin-left: 50px;">
                    <div class="form-group">
                        <div class="kt-checkbox-list">
                            <label class="kt-checkbox">
                                <input type="checkbox" name="submit" style="font-weight: bold;">
                                <strong style="font-size: 20px">
                                    You are about to submit this application<br>
                                    Be sure that the inputs are correct. You <br>
                                    will not be able to edit this form again after<br>
                                    submission. Do you wish to proceed with the submission?
                                </strong>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__foot" style="margin-left: 50px;">
                    <div class="kt-form__actions">
                        <button type="button" class="btn btn-primary">Back</button>
                        <button type="button" class="btn btn-large btn-success" style="width: 150px; background-color: blue;" data-toggle="modal" data-target="#confirmSubmissionModal">Preview Inputs</button>
                        <button type="submit" class="btn btn-large btn-success" style="width: 150px;">Save & Complete</button>
                    </div>
                </div>
            </div>
            <!--end::Portlet-->
        </div>
    </div>
</form>

<div class="modal fade" id="confirmSubmissionModal" tabindex="-1" role="dialog" aria-labelledby="confirmSubmissionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmSubmissionModalLabel">You cannot make changes to your inputs</h5>
            </div>
            <div class="modal-body">
                <!-- Form for confirming submission -->

                <p>Do you want to proceed with the submission?</p>
                <form id="confirmSubmissionForm">
                    <div class="form-group">
                        <label for="application">Application</label>
                        <input type="text" class="form-control" id="" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="payment-status">Payment Status</label>
                        <input type="text" class="form-control" id="" placeholder="">
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" id="saveProprietor">Submit & Finish</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
