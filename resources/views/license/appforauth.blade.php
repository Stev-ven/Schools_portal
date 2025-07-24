@extends('template.app')

@section('main-content')

<div class="kt-portlet__body">
    <div class="kt-portlet">

        <div class="kt-portlet__body">

            <livewire:license.appforauth :results="$results" />


        </div>
    </div>


    <!--begin::Portlet-->


</div>
<script>
    toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "timeOut": "3000",
    "extendedTimeOut": "1000"
};
    @if (session('success'))
        toastr.success("{{ session('success') }}");
    @endif

    @if (session('error'))
        toastr.error("{{ session('error') }}");
    @endif

    @if (session('info'))
        toastr.info("{{ session('info') }}");
    @endif

    @if (session('warning'))
        toastr.warning("{{ session('warning') }}");
    @endif
</script>


@endsection
