@extends('template.authapp')

@section('main-content')

<div class="kt-portlet__body">
    {{-- <form class="kt-form"> --}}
        <div class="row">
            <div class="col-md-6" style="background-color: #fff; margin-bottom: 30px;" >
                <div class="kt-portlet__body">
                    <img  class="img-fluid rounded mb-4" loading="lazy" src="{{ asset('assets/media/icons/nasia-logo-emblem.png') }}" width="60" height="30" alt="Nasia Logo" style="margin: 20px;">
                </div>
                <div class="kt-portlet__body" style="margin: 0 20%;">
                    <p class="text-center fs-4 custom-font-family" style="color: #000; margin 0 50px;">School Licensing and Inspection Management System (SLIMS) Portal</p>
                    <livewire:auth.forgotpassword-component />
                </div>


            </div>
            <div class="col-md-6 p-0">
                <div id="carouselExample" class="carousel slide">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img style="min-height:100vh; object-fit: cover; width:100%;" src="{{ asset('assets/media/bg/slims.jpeg') }}" class="d-block rounded w-100" alt="...">
                        </div>
                        <div class="carousel-item">
                            <img src="..." class="d-block w-100" alt="...">
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


{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="description" content="Login page example">
    <script src="{./assets/vendors/general/jquery/dist/jquery.js?" type="text/javascript"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
    <script src="./assets/js/webfont-loader.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Overpass:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <title>Reset Password</title>

    <style>
        #container {
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }


        .custom-font-family {
            font-family: "Overpass", sans-serif;
            font-optical-sizing: auto;
            font-weight: 600;
            font-style: normal;
        }



    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

	<livewire:styles>
	<livewire:scripts>

	 <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  	<x-livewire-alert::scripts />
</head>
<body class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--fixed kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading">

    <!-- begin:: Page -->
    <!-- Login 9 - Bootstrap Brain Component -->
    <section class=" " style=" height: 100vh;">
        <div class=" ">
            <div class="row" style="height: 100vh;">
                <div style="background-image: linear-gradient(to right, rgba(0, 128, 0, 1), rgba(115, 179, 130, 0.979));" class="col-4 py-2 px-5">
                    <div class=" pt-5 border-0 rounded-4">

                        <div class="row">
                            <div class="col-12">

                                <div class="mb-2">

                                    <img style="width:15%;" class="img-fluid rounded mb-4" loading="lazy" src="{{asset('assets/Screenshots/nasia-logo.webp')}}" width="245" height="80" alt="Nasia Logo">
                                    <p class='text-center fs-4 custom-font-family' style='color: #fff'>School Licensing and Inspection
                                        Management System (SLIMS) Portal</p>

                                </div>
                            </div> --}}

                            {{-- <form action="#!">
                                <p class="text-center" style="color: rgb(60, 60, 136)">Login</p>
                                <div class="row gy-3 overflow-hidden">
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <input type="email" class="form-control" name="email" id="email" placeholder="name@example.com" required>
                                            <label for="email" class="form-label">Email</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <input type="password" class="form-control" name="password" id="password" value="" placeholder="Password" required>
                                            <label for="password" class="form-label">Password</label>
                                        </div>
                                    </div>

                                    <div class="col-12 text-center">
                                        <p style="color:rgb(94, 94, 104)">Don't have an account? <a href="#!" style="color: rgb(60, 60, 136); text-decoration: none">Sign up</a></p>
                                        <div class="d-grid">
                                            <button class="btn btn-dark btn-lg text-center" style="width: 300px; height: 65px; background-color: #87aa73a1; margin: 0 auto; border-radius: 30px;" type="submit">Log in now</button>
                                        </div>
                                    </div>
                                </div>
                            </form> --}}
							{{-- <livewire:auth.forgotpassword-component />

                        </div>
                    </div>
                </div>
                <div class="col-8  p-0">

                    <div id="container" style="background-color: rgba(90, 90, 221, 0.329)">

                        <div class="w-100 ">
                            <div id="carouselExample" class="carousel slide">
                                <div class="carousel-inner">
                                    <div class="carousel-item active">
                                        <img style="min-height:100vh;  object-fit: cover; width:100%;"  src="{{asset('assets/media/bg/truck.jpg')}}" class="d-block rounded w-100" alt="...">
                                    </div>
                                    <div class="carousel-item">
                                        <img src="..." class="d-block w-100" alt="...">
                                    </div>
                                    <div class="carousel-item">
                                        <img src="..." class="d-block w-100" alt="...">
                                    </div>
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>

                        </div>
                    </div>


                </div>
            </div>
        </div>
    </section> --}}



    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script> --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</body> --}}
<!-- end::Body -->
{{-- </html> --}}
