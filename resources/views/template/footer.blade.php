
    <div class="kt-footer  kt-grid__item kt-grid kt-grid--desktop kt-grid--ver-desktop" id="kt_footer">
        <div class="kt-container  kt-container--fluid ">
            <div class="kt-footer__copyright">
                {{Carbon\Carbon::now()->year}}&nbsp;&copy;&nbsp;<a href="http://keenthemes.com/metronic" target="_blank" class="kt-link">NaSIA</a>
            </div>
            <div class="kt-footer__menu">
                <a href="#" target="_blank" class="kt-footer__menu-link kt-link">About</a>
                <a href="#" target="_blank" class="kt-footer__menu-link kt-link">SLIMS</a>
                <a href="#" target="_blank" class="kt-footer__menu-link kt-link">Contact</a>
            </div>
        </div>
    </div>
</div>


    <!-- end::Global Config -->
	{{-- <livewire:scripts /> --}}

@livewireScripts

    <!--begin:: Global Mandatory Vendors -->
	{{-- <script src="{{asset('assets/vendors/general/jquery/dist/jquery.js')}}" type="text/javascript"></script> --}}
	<script src="{{asset('assets/js/demo1/my.scripts.js')}}" type="text/javascript"></script>

    {{-- <script src="{{asset('assets/vendors/general/jquery/dist/jquery.js')}}" type="text/javascript"></script> --}}
    <script src="{{asset('assets/vendors/general/popper.js/dist/umd/popper.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/general/bootstrap/dist/js/bootstrap.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/general/js-cookie/src/js.cookie.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/general/moment/min/moment.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/general/tooltip.js/dist/umd/tooltip.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/general/perfect-scrollbar/dist/perfect-scrollbar.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/general/sticky-js/dist/sticky.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/general/wnumb/wNumb.js')}}" type="text/javascript"></script>

    <!--end:: Global Mandatory Vendors -->

    <!--begin:: Global Optional Vendors -->
    <script src="{{asset('assets/vendors/general/jquery-form/dist/jquery.form.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/general/block-ui/jquery.blockUI.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/custom/js/vendors/bootstrap-datepicker.init.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/general/bootstrap-datetime-picker/js/bootstrap-datetimepicker.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/general/bootstrap-timepicker/js/bootstrap-timepicker.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/custom/js/vendors/bootstrap-timepicker.init.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/general/bootstrap-daterangepicker/daterangepicker.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/general/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/general/bootstrap-maxlength/src/bootstrap-maxlength.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/custom/vendors/bootstrap-multiselectsplitter/bootstrap-multiselectsplitter.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/general/bootstrap-select/dist/js/bootstrap-select.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/general/bootstrap-switch/dist/js/bootstrap-switch.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/custom/js/vendors/bootstrap-switch.init.js')}}" type="text/javascript"></script>
    {{-- <script src="{{asset('assets/vendors/general/select2/dist/js/select2.full.js')}}" type="text/javascript"></script> --}}
    <script src="{{asset('assets/vendors/general/ion-rangeslider/js/ion.rangeSlider.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/general/typeahead.js/dist/typeahead.bundle.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/general/handlebars/dist/handlebars.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/general/inputmask/dist/jquery.inputmask.bundle.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/general/inputmask/dist/inputmask/inputmask.date.extensions.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/general/inputmask/dist/inputmask/inputmask.numeric.extensions.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/general/nouislider/distribute/nouislider.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/general/owl.carousel/dist/owl.carousel.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/general/autosize/dist/autosize.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/general/clipboard/dist/clipboard.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/general/dropzone/dist/dropzone.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/general/summernote/dist/summernote.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/general/markdown/lib/markdown.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/general/bootstrap-markdown/js/bootstrap-markdown.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/custom/js/vendors/bootstrap-markdown.init.js')}}" type="text/javascript"></script>
    {{-- <script src="{{asset('assets/vendors/general/bootstrap-notify/bootstrap-notify.min.js')}}" type="text/javascript"></script> --}}
    {{-- <script src="{{asset('assets/vendors/custom/js/vendors/bootstrap-notify.init.js')}}" type="text/javascript"></script> --}}
    <script src="{{asset('assets/vendors/general/jquery-validation/dist/jquery.validate.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/general/jquery-validation/dist/additional-methods.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/custom/js/vendors/jquery-validation.init.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/general/toastr/build/toastr.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/general/raphael/raphael.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/general/morris.js/morris.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/general/chart.js/dist/Chart.bundle.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/custom/vendors/bootstrap-session-timeout/dist/bootstrap-session-timeout.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/custom/vendors/jquery-idletimer/idle-timer.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/general/waypoints/lib/jquery.waypoints.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/general/counterup/jquery.counterup.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/general/es6-promise-polyfill/promise.min.js')}}" type="text/javascript"></script>
    {{-- <script src="{{asset('assets/vendors/general/sweetalert2/dist/sweetalert2.min.js')}}" type="text/javascript"></script> --}}
    {{-- <script src="{{asset('assets/vendors/custom/js/vendors/sweetalert2.init.js')}}" type="text/javascript"></script> --}}
    <script src="{{asset('assets/vendors/general/jquery.repeater/src/lib.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/general/jquery.repeater/src/jquery.input.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/general/jquery.repeater/src/repeater.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/general/dompurify/dist/purify.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/js/demo1/scripts.bundle.js')}}" type="text/javascript"></script>
    {{-- <script src="{{asset('assets/vendors/custom/fullcalendar/fullcalendar.bundle.js')}}" type="text/javascript"></script> --}}

    {{-- <script src="{{asset('assets/vendors/custom/gmaps/gmaps.js')}}" type="text/javascript"></script> --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
		{{-- <script src="./assets/js/demo1/scripts.bundle.js" type="text/javascript"></script> --}}
	{{-- <script src="{{asset('assets/js/demo1/scripts.bundle.js')}}" type="text/javascript"></script> --}}


@stack('scripts')
</body>

<!-- end::Body -->

</html>
