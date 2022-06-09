<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8"/>

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="_token" content="{{ csrf_token() }}">
    <title>Cogent EMR</title>
    <!-- Favicon --> 
    @include('frontend.layouts.root-color')

    <style>
      

        .profile-form a, .accordion-nav ul  {
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
    <link rel="stylesheet" href="{{ mix('new/css/fixes.css') }}" />

    <!-- Full calendar -->
    <link rel="stylesheet" href="{{ asset('new/fullcalendar/core/main.css') }}"/>
    <link rel="stylesheet" href="{{ asset('new/fullcalendar/daygrid/main.css') }}"/>
    <link rel="stylesheet" href="{{ asset('new/fullcalendar/timegrid/main.css') }}"/>
    <link rel="stylesheet" href="{{ asset('new/fullcalendar/list/main.css') }}"/>


    <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui-timepicker.css')}}">
    <script src="{{asset('assets/js/jquery-3.4.1.min.js')}}"></script>
    <script src="{{asset('assets/js/jquery-ui.min.js')}}"></script>
    <script src="{{asset('assets/js/jquery-ui-timepicker.js')}}"></script>

    <link rel="stylesheet" href="{{asset('css/select2.min.css')}}"/>
    <script src="{{asset('js/select2.min.js')}}"></script>
<!-- <script src="{{asset('../vendor/unisharp/laravel-ckeditor/ckeditor.js')}}"></script> -->

    <!--Script for Neuro -->
    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('DataTables/datatables.min.css') }}"/>
<!-- <script type="text/javascript" src="{{  asset('DataTables/datatables.min.js') }}"></script> -->

    {{--Neapli datepicker--}}
    <link rel="stylesheet" href="{{ asset('assets/css/nepali.datepicker.v2.2.min.css') }}">
    <script src="{{ asset('assets/js/nepali.datepicker.v2.2.min.js') }}"></script>
    <script src="{{asset('assets/js/jquery.dataTables.min.js')}}"></script>

    {{-- jquery validation css --}}
    <link rel="stylesheet" href="{{ asset('assets/css/jquery-validation.css') }}">

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

        @include('modal.bed-list')


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

{{--all js are compined and merged in one cogent-js.min.js--}}

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
@stack('after-script')

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

    $(document)
        .ajaxStart(function () {
            $('#loader-ajax-start-stop').show();
            $loadingContainer.show();
        })
        .ajaxStop(function () {
            $('#loader-ajax-start-stop').hide();
            $loadingContainer.hide();
        });

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
        if ($(".").length > 0) {
            var scroll = $(window).scrollTop();
            var anchor_top = $(".").offset().top;
            var anchor_bottom = $("#bottom_anchor").offset().top;
            if (scroll > anchor_top && scroll < anchor_bottom) {
                var clone_table = $("#clone");
                if (clone_table.length == 0) {
                    clone_table = $(".").clone();
                    clone_table.attr('id', 'clone');
                    clone_table.css({
                        position: 'fixed',
                        'pointer-events': 'none',
                        top: '74px'
                    });
                    clone_table.width($(".").width());
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
</body>
</html>
