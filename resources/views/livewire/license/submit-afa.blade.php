<div class="row">
    <div class="kt-subheader   kt-grid__item" id="kt_subheader">
        <div class="kt-container  kt-container--fluid ">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">
                    Application For Authorization To Establish A New School
                </h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
            </div>

        </div>

    </div>
    <div class="kt-portlet">
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-label">
                <h1 class="kt-portlet__head-title">Submit Application</h1>
            </div>
        </div>
    </div>
    <div class="kt-portlet__body" style="border: 2px solid #ffffff; border-radius: 10px; padding: 20px; max-width: 600px; margin: 0 auto; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);">
        <form class="kt-form" action="#!">
            <div class="row md-12">
                <div class="form-group">
                    <h2 style="font-family: 'Overpass', sans-serif;">
                        You are about to submit this application, be sure that all your inputs
                    are correct. You will not be able to edit this form again after submission.
                    Do you want to proceed with the submission?</h2>
            </div>

            <div class="kt-portlet__foot">
                <div class="kt-form__actions kt-align-right">
                    <button type="button" class="btn btn-outline-dark" wire:click="back">Back</button>
                    {{-- <button type="button" class="btn btn-outline-dark">Preview Inputs</button> --}}
                    <button type="button" class="btn btn-outline-success" data-toggle="modal" data-target="#submitEoiModal">Submit & Complete</button>
                </div>
            </div>
        </form>
    </div>
    <div wire:ignore.self class="modal fade" id="submitEoiModal" tabindex="-1" role="dialog" aria-labelledby="subbmitEoiModalLabel" aria-hidden="true">
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
                            <input type="text" class="form-control" readonly placeholder="Application For Authorization">
                        </div>
                        <div class="form-group">
                            <label for="payment-status">Payment Status</label>
                            <input type="text" class="form-control" readonly placeholder="PAID">
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-outline-success" wire:click="submitAfa" >submit & Finish</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>




