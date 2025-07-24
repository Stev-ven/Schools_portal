@extends('template.authapp')

@section('main-content')
<div class="kt-portlet__body">
    {{-- <form class="kt-form"> --}}
        <div class="row" style="100%; margin: auto;">
            <div class="col-md-6 kt-portlet__body" style="background-color: #fff; margin-bottom: 30px;" >
                <div class="kt-portlet__body">
                    <img  class="img-fluid rounded mb-4" loading="lazy" src="{{ asset('assets/media/icons/nasia-logo-emblem.png') }}" width="60" height="30" alt="Nasia Logo" style="margin: 20px;">
                </div>
                <div class="kt-portlet__body" style="margin: 0 20%;">
                    <p class="text-center fs-4 custom-font-family" style="color: #000; margin 0 50px;">School Licensing and Inspection Management System (SLIMS) Portal</p>
                    <livewire:auth.login-component />
                </div>

            </div>
            <div class="col-md-6 carousel-container">
                <div id="carouselExample" class="carousel slide">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img style="min-height:100vh;" src="{{ asset('assets/media/bg/slims.jpeg') }}" class="d-block rounded w-100" alt="...">
                        </div>
                        <div class="carousel-item">
                            <img src="{{ asset('assets/media/bg/slims.jpeg') }}" class="d-block w-100" alt="...">
                        </div>
                        <div class="carousel-item">
                            <img src="..." class="d-block w-100" alt="...">
                        </div>
                    </div>
                    {{-- <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button> --}}
                </div>
            </div>
        </div>
    {{-- </form> --}}
</div>
@endsection

