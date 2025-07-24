@extends('template.app')

@section('main-content')
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">
                Register a new school
            </h3>
            <span class="kt-subheader__separator kt-subheader__separator--v"></span>


        </div>
    </div>
</div>

<div class="kt-portlet__body">
    <div class="kt-portlet">

        <div class="kt-portlet__body">

            <livewire:license.register-school />

        </div>
    </div>


    <!--begin::Portlet-->


</div>
@endsection

