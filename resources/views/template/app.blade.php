@include('template.header')

<!-- begin:: Page -->

<!-- begin:: Header Mobile -->
<div id="kt_header_mobile" class="kt-header-mobile  kt-header-mobile--fixed ">
    <div class="kt-header-mobile__logo">
        <a href="#">
            <img alt="Logo" style="width:15%" src="{{asset('assets/media/icons/nasia-logo-emblem.png')}}" />
        </a>
    </div>
    <div class="kt-header-mobile__toolbar">
        <button class="kt-header-mobile__toggler kt-header-mobile__toggler--left" id="kt_aside_mobile_toggler"><span></span></button>
        {{-- <button class="kt-header-mobile__toggler" id="kt_header_mobile_toggler"><span></span></button> --}}
        <button class="kt-header-mobile__topbar-toggler" id="kt_header_mobile_topbar_toggler"><i class="flaticon-more"></i></button>
    </div>
</div>
<!-- end:: Header Mobile -->

<div class="kt-grid kt-grid--hor kt-grid--root">
    <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page">

        <!-- begin:: Aside -->
        <button class="kt-aside-close " id="kt_aside_close_btn"><i class="la la-close"></i></button>
		@include('template.aside')
        <!-- end:: Aside -->

        <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">

            <!-- begin:: Header -->
            <div id="kt_header" class="kt-header kt-grid__item kt-header--fixed">

                <!-- begin:: Header Menu -->
                <div class="kt-header-menu-wrapper" id="kt_header_menu_wrapper"></div>
                <!-- end:: Header Menu -->

                <!-- begin:: Header Topbar -->
                <div class="kt-header__topbar">
                    {{-- <div class="container" style="width: 60%; margin: 0 auto; display: flex; justify-content: center; align-items: center;">
                        <h1>Schools</h1>
                    </div> --}}
                    <!--begin: User Bar -->
                    <div class="kt-header__topbar-item kt-header__topbar-item--user">
                        <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="0px,0px">
                            <div class="kt-header__topbar-user">
                                <span class="kt-header__topbar-welcome kt-hidden-mobile">Hi,</span>
                                <span class="kt-header__topbar-username kt-hidden-mobile">{{session()->get('api_response')['first_name']}}</span><img src="{{session()->get('api_response')['profile_photo']}}" alt="">
                                <img class="kt-hidden" alt="Pic" src="{{session()->get('api_response')['profile_photo']}}" />
                                {{-- <span class="kt-badge kt-badge--username kt-badge--unified-success kt-badge--lg kt-badge--rounded kt-badge--bold">{{substr(session()->get('api_response')['first_name'], 0, 1). '.' . substr(session()->get('api_response')['last_name'], 0, 1)}}</span> --}}
                            </div>
                        </div>
                        <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround dropdown-menu-xl">
                            <!--begin: Head -->
                            <div class="kt-user-card kt-user-card--skin-dark kt-notification-item-padding-x" style="background-image: url(./assets/media/bg/bg-8.jpg)">
                                <div class="kt-user-card__avatar">
                                    <img class="kt-hidden" alt="Pic" src="./assets/media/users/300_25.jpg" />
                                </div>
                                <div class="kt-user-card__name text-dark">
                                    {{ session()->get('api_response')['first_name'] . ' ' . session()->get('api_response')['last_name'] }}
                                    <span class="kt-badge kt-badge--username kt-badge--unified-success kt-badge--lg kt-badge--rounded kt-badge--bold"><img style="width:70%;" src="{{asset('assets/media/icons/nasia-logo-emblem.png')}}" alt="icon"></span>
                                </div>
                            </div>
                            <!--end: Head -->

                            <!--begin: Navigation -->
                            <div class="kt-notification">
                                <a href="{{route('profile')}}" class="kt-notification__item" style="text-decoration: none">
                                    <div class="kt-notification__item-icon">
                                        <i class="flaticon2-calendar-3 kt-font-success"></i>
                                    </div>
                                    <div class="kt-notification__item-details">
                                        <div class="kt-notification__item-title kt-font-bold">My Profile</div>
                                        <div class="kt-notification__item-time">Account settings and more</div>
                                    </div>
                                </a>

                                <a href="{{route('password-change')}}" class="kt-notification__item" style="text-decoration: none">
                                    <div class="kt-notification__item-icon">
                                        <i class="flaticon2-lock kt-font-success"></i>
                                    </div>
                                    <div class="kt-notification__item-details">
                                        <div class="kt-notification__item-title kt-font-bold">Change Password</div>
                                        <div class="kt-notification__item-time">Update your password</div>
                                    </div>
                                </a>

                                <div class="kt-notification__custom kt-space-between">
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                    <a href="{{route('logout')}}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-label btn-label-brand btn-sm btn-bold">Sign Out</a>
                                </div>
                            </div>
                            <!--end: Navigation -->
                        </div>
                    </div>
                    <!--end: User Bar -->
                </div>
                <!-- end:: Header Topbar -->
            </div>
            <!-- end:: Header -->

            <div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content" style="flex-grow: 1; display: flex; flex-direction: column;">
                <!-- begin:: Content -->
                <div class="kt-container kt-container--fluid kt-grid__item kt-grid__item--fluid" style="flex-grow: 1;">
                    @yield('main-content')
                </div>
                <!-- end:: Content -->
            </div>

            @include('template.footer')

        </div>
    </div>
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

