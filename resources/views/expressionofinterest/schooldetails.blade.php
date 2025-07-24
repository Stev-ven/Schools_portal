@extends('template.app')

@section('main-content')

<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">
                Expression of Interest to Open a New School
            </h3>
            <span class="kt-subheader__separator kt-subheader__separator--v"></span>
        </div>
    </div>
</div>
<div class="kt-portlet__body">
    <div class="kt-portlet">
        {{-- @dd($results['data']['school_details'][0]['school_name']) --}}
        <div class="kt-portlet__body">

            <livewire:expressionofinterest.schooldetails-component :results="$results"/>


        </div>
    </div>


    <!--begin::Portlet-->


</div>
 {{-- @dd($results); --}}


@endsection
