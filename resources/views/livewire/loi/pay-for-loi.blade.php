
<div class="row">
    <div class="kt-subheader   kt-grid__item" id="kt_subheader">
        <div class="kt-container  kt-container--fluid ">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">
                    Letter of Introduction
                </h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
            </div>

        </div>

    </div>

        <!--begin::Portlet-->
        <div class="kt-portlet">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h1 class="kt-portlet__head-title">Pay for application</h1>
                </div>
            </div>
        </div>
        <div class="kt-portlet__body" style="border: 2px solid #ffffff; border-radius: 10px; padding: 20px; max-width: 600px; margin: 0 auto; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);">
            <form class="kt-form" wire:submit.prevent="makePayment" action="#!">
                <div class="row md-12">
                    <div class="form-group">
                        <h2 style="font-family: 'Overpass', sans-serif;">
                            You need to make payment of GHC __ to access
                            the application forms
                        </h2>
                    </div>

                <div class="kt-portlet__foot">
                    <div class="kt-form__actions">
                        <button type="button" class="btn btn-outline-dark btn-label-dark" wire:click="back">Go Back</button>
                        <button type="button" class="btn btn-outline-success btn-label-success" data-toggle="modal" data-target="#makepaymentModal">Make Payment</button>
                    </div>
                </div>
                </div>
            </form>
        </div>
        <div class="modal fade" id="makepaymentModal" tabindex="-1" role="dialog" aria-labelledby="makepaymentModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="makepaymentModalLabel">Confirm Payment</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="kt-form" wire:submit.prevent="makePayment" action="#!">
                            <div class="row md-12">
                                <div class="form-group">
                                    <label for="application">Payment type</label>
                                    <input type="text" class="form-control" readonly placeholder="Notice of intent">
                                </div>
                                <div class="form-group">
                                    <label for="application">Amount (GHC)</label>
                                    <input type="text" class="form-control" readonly placeholder="__">
                                </div>
                            </div>
                            <div class="kt-portlet__foot">
                                <div class="kt-form__actions">
                                    <button type="button" class="btn btn-outline-dark btn-label-dark" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-outline-success btn-label-success" wire:click="makePayment">
                                        <span wire:loading.remove wire:target="makePayment">Make Payment</span>
                                        <span wire:loading wire:target="makePayment" class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                                        <div wire:loading>Loading...</div>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

{{-- </div> --}}
 {{-- </div> --}}
</div>

@script
    <script>
        $wire.on('notify', (items) => {
            Swal.fire({
                // title: "Are you sure?",
                text: "Click proceed to continue",
                icon: "warning",
                // showCancelButton: true,
                confirmButtonColor: "#3085d6",
                // cancelButtonColor: "#d33",
                confirmButtonText: "Yes! Proceed",
            }).then((result) => {
                if (result.isConfirmed) {
                    // Swal.fire({
                    //     title: "Deleted!",
                    //     text: "Your file has been deleted.",
                    //     icon: "success"
                    // });

                    $wire.dispatch('redirect');
                }
            });
        })
    </script>
@endscript



