<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8"/>

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="_token" content="{{ csrf_token() }}">
    <meta name="base-url" content="{{ url('/') }}">

    <title>Cogent EMR</title>
    <!-- Favicon -->
    @include('frontend.layouts.root-color')
    <style>
        .profile-form a, .accordion-nav ul {
            background-color: #144069;
        }
    </style>
    <link rel="shortcut icon" href="{{ asset('new/images/favicon.ico') }}"/>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('new/css/bootstrap.min.css') }}"/>
    <!-- Fontawesomg css -->
    <link rel="stylesheet" href="{{ asset('fontawesome/css/all.css') }}"/>
    <!-- Typography CSS -->
    <link rel="stylesheet" href="{{ asset('new/css/typography.css') }}"/>
    <!-- Style CSS -->
    <link rel="stylesheet" href="{{ asset('new/css/style.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/remixicon.css') }}"/>
    <!-- custom css -->
    <link rel="stylesheet" href="{{ asset('new/css/custom.css') }}"/>
    <!-- design css -->
    <link rel="stylesheet" href="{{ asset('new/css/design.css') }}"/>
    <!-- neuro css -->
{{--    <link rel="stylesheet" href="{{ asset('new/css/.css') }}"/>--}}
<!-- Responsive CSS -->
    <link rel="stylesheet" href="{{ asset('new/css/theme-responsive.css') }}"/>
    <link rel="stylesheet" href="{{ asset('new/css/responsive.css') }}"/>
    <link rel="stylesheet" href="{{ mix('new/css/fixes.css') }}"/>

    <!-- Full calendar -->
    <link rel="stylesheet" href="{{ asset('new/fullcalendar/core/main.css') }}"/>
    <link rel="stylesheet" href="{{ asset('new/fullcalendar/daygrid/main.css') }}"/>
    <link rel="stylesheet" href="{{ asset('new/fullcalendar/timegrid/main.css') }}"/>
    <link rel="stylesheet" href="{{ asset('new/fullcalendar/list/main.css') }}"/>


    <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui-timepicker.css')}}">

    <link rel="stylesheet" href="{{asset('css/select2.min.css')}}"/>


    <!--Script for Neuro -->

    <link rel="stylesheet" type="text/css" href="{{ asset('DataTables/datatables.min.css') }}"/>

    {{--Neapli datepicker--}}
    @if(request()->segment(count(request()->segments())) == 'registrationform')
    <link rel="stylesheet" href="{{ asset('css/nepali.datepicker.v3.7.min.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('assets/css/nepali.datepicker.v2.2.min.css') }}">
    @endif


    {{-- jquery validation css --}}
    <link rel="stylesheet" href="{{ asset('assets/css/jquery-validation.css') }}">
    {{--Sweet alert 2 --}}
    <link rel="stylesheet" href="{{asset('assets/css/sweetalert2.css')}}"/>
    {{-- Header js --}}
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script> --}}
    <script src="{{asset('assets/js/jquery.min.js')}}"></script>
    <script src="{{asset('assets/js/jquery-ui.min.js')}}"></script>

    <script src="{{asset('assets/js/jquery-ui-timepicker.js')}}"></script>
    <script src="{{asset('js/select2.min.js')}}"></script>
    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
    @if(request()->segment(count(request()->segments())) == 'registrationform')
    <script src="{{ asset('js/nepali.datepicker.v3.7.min.js') }}"></script>
    @else
    <script src="{{ asset('assets/js/nepali.datepicker.v2.2.min.js') }}"></script>
    @endif

    <script src="{{asset('assets/js/jquery.dataTables.min.js')}}"></script>

    {{--Resizeable Table--}}
    {{-- <link href="https://unpkg.com/jquery-resizable-columns@0.2.3/dist/jquery.resizableColumns.css" rel="stylesheet">
    <link href="https://unpkg.com/bootstrap-table@1.19.1/dist/bootstrap-table.min.css" rel="stylesheet">

    <script src="https://unpkg.com/jquery-resizable-columns@0.2.3/dist/jquery.resizableColumns.min.js"></script>
    <script src="https://unpkg.com/bootstrap-table@1.19.1/dist/bootstrap-table.min.js"></script>
    <script src="https://unpkg.com/bootstrap-table@1.19.1/dist/extensions/resizable/bootstrap-table-resizable.min.js"></script> --}}

    <link href="{{ asset('assets/resizeable/jquery.resizableColumns.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/resizeable/bootstrap-table.min.css') }}" rel="stylesheet">

    <script src="{{asset('assets/resizeable/jquery.resizableColumns.min.js')}}"></script>
    <script src="{{asset('assets/resizeable/bootstrap-table.min.js')}}"></script>
    <script src="{{asset('assets/resizeable/bootstrap-table-resizable.min.js')}}"></script>
    {{-- sweet alert2 js--}}
    <script src="{{asset('assets/js/sweetalert2.js')}}"></script>

    <script type="text/javascript">
        function numberFormatDisplay(number) {
            const decimals = 2;
            const dec_point = '.';
            const thousands_sep = ',';
            // Strip all characters but numerical ones.
            number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
            var n = !isFinite(+number) ? 0 : +number,
                prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                s = '',
                toFixedFix = function(n, prec) {
                    var k = Math.pow(10, prec);
                    return '' + Math.round(n * k) / k;
                };
            // Fix for IE parseFloat(0.55).toFixed(0) = 0;
            s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
            if (s[0].length > 3) {
                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
            }
            if ((s[1] || '').length < prec) {
                s[1] = s[1] || '';
                s[1] += new Array(prec - s[1].length + 1).join('0');
            }
            return s.join(dec);
        }

        function numberFormat(number) {
            const decimals = 2;
            const dec_point = '.';
            const thousands_sep = ',';
            // Strip all characters but numerical ones.
            number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
            var n = !isFinite(+number) ? 0 : +number,
                prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                s = '',
                toFixedFix = function(n, prec) {
                    var k = Math.pow(10, prec);
                    return '' + Math.round(n * k) / k;
                };
            // Fix for IE parseFloat(0.55).toFixed(0) = 0;
            s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
            if (s[0].length > 3) {
                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
            }
            if ((s[1] || '').length < prec) {
                s[1] = s[1] || '';
                s[1] += new Array(prec - s[1].length + 1).join('0');
            }
            var numberwithoutcomma = s.join(dec);
            return  numberwithoutcomma.replaceAll(',', '');
        }
    </script>


    @if(isset($patient_status_disabled) && $patient_status_disabled == 1 )
        <style>
            .disableInsertUpdate {
                opacity: .4;
                cursor: default !important;
                pointer-events: none;
            }

            .img-icon {
                width: 14%;
                margin-right: 3%;
            }

            .btn {
                font-size: 11px !important;
                font-family: 'Poppins', sans-serif;
            }
        </style>
    @endif
    <style>
        .loader-ajax-start-stop {
            position: absolute;
            left: 45%;
            top: 35%;
        }

        .loader-ajax-start-stop-container {
            position: fixed;
            top: 0px;
            left: 0px;
            width: 100%;
            height: 100%;
            background: black;
            opacity: .5;
            z-index: 999999;
        }
    </style>
    @stack('after-styles')
</head>
<body class="loading">

<!-- loader Start -->
<div class="loader-block">
    <div class="loader-side-menu">
        <div class="loader-logo-block">
            <div class="loader-logo"></div>
            <div class="lodaer-logo-name"></div>
        </div>
        <div class="loader-menu1"></div>
        <div class="loader-menu2"></div>
        <div class="loader-menu3"></div>
        <div class="loader-menu1"></div>
        <div class="loader-menu2"></div>
        <div class="loader-menu3"></div>
        <div class="loader-menu1"></div>
        <div class="loader-menu2"></div>
        <div class="loader-menu1"></div>
        <div class="loader-menu2"></div>
        <div class="loader-menu3"></div>
        <div class="loader-menu2"></div>
        <div class="loader-menu3"></div>
    </div>
    <div class="loader-search-bar">
        <div class="loader-search-block"></div>
        <div class="loader-logo-block">
            <div class="loader-logo"></div>
            <div class="lodaer-logo-name"></div>
        </div>
    </div>
    <div class="loader-content-block">
        <div class="loader-content1"></div>
        <div class="loader-content4"></div>
        <div class="loader-content2"></div>
        <div class="loader-content3"></div>
    </div>
    <div class="loader-content-block">
        <div class="loader-content1"></div>
        <div class="loader-content3"></div>
        <div class="loader-content4"></div>
        <div class="loader-content2"></div>
    </div>
    <div class="loader-content-block">
        <div class="loader-content2"></div>
        <div class="loader-content3"></div>
        <div class="loader-content1"></div>
        <div class="loader-content4"></div>
    </div>
</div>
<!-- loader END -->

<!-- Wrapper Start -->
<div class="wrapper">
    <!-- Sidebar  -->
@include('frontend.layouts.sidebar')

<!-- Page Content  -->
    <div id="content-page" class="content-page">
        <!-- TOP Nav Bar -->
    @include('frontend.layouts.header')

    @yield('content')

    <!-- Footer -->
        @include('frontend.layouts.footer')

        @include('modal.patient-profile-modal')
        @include('modal.inventory-expiry-date')

        @include('modal.consult-complaints-general-modal')
        @include('modal.consult-group-general-modal')
        @include('outpatient::modal.laboratory-radiology-modal')
        @include('inpatient::layouts.modal.essense')
        @include('inpatient::layouts.modal.edit-stat')
        @include('outpatient::modal.pharmacy-modal')

        @include('modal.change-abnormal-status')

        @include('modal.general-modal')
        @include('modal.general-modal-sm')
        @include('modal.consultant-general-modal')
        @include('outpatient::modal.finish-boxLabel-modal')
        @include('outpatient::modal.patient-image-modal')
        @include('modal.bed-list')

        @include('inpatient::layouts.modal.confirm-box')

        <div class="modal" id="js-global-exam-observation-edit"></div>

        <input type="hidden" id="traigecolor" value="@if(isset($enpatient)) {{$enpatient->fldheight??''}} @endif">
    </div>
</div>
<div class="error-success">
    <div class="alert alert-danger" id="error-for-all-container" role="alert" style="position:fixed ;top:12%;right:2%; z-index:1054;">
        <div class="iq-alert-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="iq-alert-text">
            <div id="error-for-all"></div>
        </div>
    </div>

    <div class="alert alert-success" id="success-for-all-container" role="alert" style="position:fixed ;top:12%;right:2%;z-index:1054;">
        <div class="iq-alert-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="iq-alert-text">
            <div id="success-for-all"></div>
        </div>
    </div>

</div>

<div class="loader-ajax-start-stop-container">
    <div class="loader-ajax-start-stop">
        <img src="{{ asset('images/loader-rolling.svg') }}">
    </div>
</div>
<!-- Wrapper END -->
{{--old scripts--}}
@include('frontend.common.global-script')

{{--all js are combined and merged in one cogent-js.min.js--}}
<script src="{{ asset('js/cogent-js.min.js') }}"></script>
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
{{--<script src="{{ asset('new/js/popper.min.js') }}"></script>--}}
{{--<script src="{{ asset('new/js/bootstrap.min.js') }}"></script>--}}
<!-- Appear JavaScript -->
{{--<script src="{{ asset('new/js/jquery.appear.js') }}"></script>--}}
<!-- Wow JavaScript -->
{{--<script src="{{ asset('new/js/wow.min.js') }}"></script>--}}
<!-- Apexcharts JavaScript -->
{{--<script src="{{ asset('new/js/apexcharts.js') }}"></script>--}}
<!-- Magnific Popup JavaScript -->
{{--<script src="{{ asset('new/js/jquery.magnific-popup.min.js') }}"></script>--}}
<!-- Smooth Scrollbar JavaScript -->
{{--<script src="{{ asset('new/js/smooth-scrollbar.js') }}"></script>--}}
<!-- lottie JavaScript -->
{{--<script src="{{ asset('new/js/lottie.js') }}"></script>--}}

<!-- Custom JavaScript -->
<script src="{{ asset('new/js/custom.js') }}"></script>

<script type="text/javascript">
    $.ajaxSetup({
        headers:
            {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
    });

    var baseUrl = '{{ url('/') }}';
    $(document).ready(function () {
        $('.select2').select2();
        $('.nepaliDatePicker').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 10
        });
        $('.englishDatePicker').datepicker({
            dateFormat: "yy-mm-dd"
        });
    });

    function getCurrentTriageColor() {
        var traigecolor = $('#traigecolor').val();
        if (traigecolor !== "") {
            element = document.getElementById("traicolor");
            if (typeof (element) != 'undefined' && element != null)
                document.getElementById("traicolor").style.borderTop = "5px solid " + traigecolor;
        }

    }

    $(document).ready(function () {


        $(document).on("click", "label", function () {


            $(this).parent('div').find('input:checkbox').checked = true;


        });
        $(document).on("click", "label", function () {

            $(this).parent('div').find('input:radio').checked = true;


        });

        $(document).on("click", "td tr", function () {
            $(this).find('input:checkbox').checked = true;


        });


        $(document).on("click", "td tr", function () {
            $(this).find('input:checkbox').each(function () {
                if (this.checked) this.checked = false; // toggle the checkbox
                else this.checked = true;
                // this.checked = true;
            })

        });

        $(document).on("click", "label", function () {

            $(this).parent('div').find('input:radio').each(function () {
                if (this.checked) this.checked = false; // toggle the checkbox
                else this.checked = true;
                // this.checked = true;
            })

        });

        $(document).on("click", "label", function () {

            $(this).parent('div').find('input:checkbox').each(function () {
                if (this.checked) this.checked = false; // toggle the checkbox
                else this.checked = true;
                // this.checked = true;
            })

        });
    });

    /*ajax loader*/
    var $loadingContainer = $('.loader-ajax-start-stop-container').hide();

    $('#loader-ajax-start-stop').show();
    $loadingContainer.show();
    $(document).ready(function () {
        $('#loader-ajax-start-stop').hide();
        $loadingContainer.hide();
    })
if (!(window.location.href.indexOf("live-medicine-stock") > -1)) {
    $(document)
        .ajaxStart(function () {
            $('#loader-ajax-start-stop').show();
            $loadingContainer.show();
        })
        .ajaxStop(function () {
            $('#loader-ajax-start-stop').hide();
            $loadingContainer.hide();
        });

}

    // hide/show
    function myFunction() {
        var x = document.getElementById("myDIV");
        if (x.style.display === "none") {
            x.style.display = "block";
        } else {
            x.style.display = "none";
        }
    }
</script>

<script>

    function moveScroll(offset = 0) {
        if ($(".table-content").length > 0) {
            var scroll = $(window).scrollTop();
            var anchor_top = $(".table-content").offset().top;
            var anchor_bottom = $("#bottom_anchor").offset().top;
            if (scroll > anchor_top && scroll < anchor_bottom) {
                var clone_table = $("#clone");
                if (clone_table.length == 0) {
                    clone_table = $(".table-content").clone();
                    clone_table.attr('id', 'clone');
                    clone_table.css({
                        position: 'fixed',
                        'pointer-events': 'none',
                        top: '74px'
                    });
                    clone_table.width($(".table-content").width());
                    $(".table-container").append(clone_table);
                    $("#clone").css({visibility: 'hidden'});
                    $("#clone thead").css({visibility: 'visible', 'pointer-events': 'auto'});
                }
            } else {
                $("#clone").remove();
            }
        }
    }

    $(window).scroll(moveScroll);

    function disableButton() {
        $('.disable-on-first-click').disabled = true;

        setTimeout(function () {
            $('.disable-on-first-click').disabled = false;
        }, 1500);
    }
</script>
@if(Session::has('error_message'))
    <script>
        $(document).ready(function () {
            showAlert("{{ Session::get('error_message') }}", 'error');
        });
    </script>
@endif
@if(Session::has('success_message'))
    <script>
        $(document).ready(function () {
            showAlert("{{ Session::get('success_message') }}");
        });
    </script>
@endif
@if ($errors->any())
    <script>
        $(document).ready(function () {
            showAlert("{{ $errors->all()[0] }}", 'error');
        });
    </script>
@endif
@stack('after-script')

</body>
</html>
