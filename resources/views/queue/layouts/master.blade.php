<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Page title -->
    <title>{{ config('constants.hospital_name') }}</title>

    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
    <!--<link rel="shortcut icon" type="image/ico" href="favicon.ico" />-->

    <!-- Vendor styles -->

    <link rel="stylesheet" href="{{ asset('queue/vendor/fontawesome/css/font-awesome') }}.css" />
    <link rel="stylesheet" href="{{ asset('queue/vendor/metisMenu/dist/metisMenu.') }}css" />
    <link rel="stylesheet" href="{{ asset('queue/vendor/animate.css/animate') }}.css" />
    <link rel="stylesheet" href="{{ asset('queue/vendor/bootstrap/dist/css/bootstrap.') }}css" />

    <!-- App styles -->
    <link rel="stylesheet" href="fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css" />
    <link rel="stylesheet" href="fonts/pe-icon-7-stroke/css/helper.css" />
    <link rel="stylesheet" href="{{ asset('queue/styles/style.css') }}">
    <script src="{{asset('assets/js/jquery-3.4.1.min.js')}}"></script>
</head>

<body class="fixed-navbar sidebar-scroll hide-sidebar">

    <style>
        .skin-option {
            position: fixed;
            text-align: center;
            right: -1px;
            padding: 10px;
            top: 80px;
            width: 150px;
            height: 133px;
            text-transform: uppercase;
            background-color: #ffffff;
            box-shadow: 0 1px 10px rgba(0, 0, 0, 0.05), 0 1px 4px rgba(0, 0, 0, .1);
            border-radius: 4px 0 0 4px;
            z-index: 100;
        }

        #logo {
            width: auto;
        }

        .active_tr {
            background-color: #28a745;

        }

        .navbar-nav>li>a {
            font-weight: 600;
            padding: 15px 20px;
            font-size: 13px;
            text-transform: uppercase;
            color: black !important;
        }

        .content {
            padding: 10px 20px 0px 20px;
            min-width: 320px;
            font-size: 20px;
        }

        td {
            color: #000000;
            padding: 5px !important;

        }

        th {
            padding: 5px !important;

        }

        .active_tr td {
            font-weight: bold;
            color: #fff;
        }

        .btn_right {
            text-align: right;
        }

        #logo {
            float: inherit;
            height: auto;
        }

        #logo.light-version span {

            text-transform: uppercase;
        }
    </style>


    <!-- Header -->
    <div id="header">
        <div style="width:60px; float:left;">
            <img src="{{ asset('uploads/config/'.Options::get('brand_image')) }}" width="100%" />
        </div>

        <nav role="navigation">


            <div class="navbar-right">
                <ul class="nav navbar-nav no-borders">
                    <li class="dropdown">
                        <a class="" href="{{ route('queue.new.consultants') }}">
                            {{ config('constants.first_menu') }}
                        </a>
                    </li>
                    <li class="dropdown">
                        <a class="" href="{{ route('queue.new.pharmacy') }}">
                            {{ config('constants.second_menu') }}
                        </a>
                    </li>

                    <li class="dropdown">
                        <a class="" href="{{ route('queue.new.laboratory') }}">
                            {{ config('constants.third_menu') }}
                        </a>
                    </li>

                    <li class="dropdown">
                        <a class="" href="{{ route('queue.new.radiology') }}">
                            {{ config('constants.forth_menu') }}
                        </a>
                    </li>

                    <li>
                        <a href="javascript:;" id="right-sidebar" class="right-sidebar-toggle">
                            <i class="fa fa-filter"></i> Filter
                        </a>
                    </li>

                </ul>
            </div>
        </nav>

    </div>

    @yield('content')

    <!-- Vendor scripts -->

    <script src="{{asset('assets/js/jquery-ui.min.js')}}"></script>
    <script src="{{ asset('queue/vendor/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('queue/vendor/slimScroll/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ asset('queue/vendor/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('queue/vendor/jquery-flot/jquery.flot.js') }}"></script>
    <script src="{{ asset('queue/vendor/jquery-flot/jquery.flot.resize.js') }}"></script>
    <script src="{{ asset('queue/vendor/jquery-flot/jquery.flot.pie.js') }}"></script>
    <script src="{{ asset('queue/vendor/flot.curvedlines/curvedLines.js') }}"></script>
    <script src="{{ asset('queue/vendor/jquery.flot.spline/index.js') }}"></script>
    <script src="{{ asset('queue/vendor/metisMenu/dist/metisMenu.min.js') }}"></script>
    <script src="{{ asset('queue/vendor/iCheck/icheck.min.js') }}"></script>
    <script src="{{ asset('queue/vendor/peity/jquery.peity.min.js') }}"></script>
    <script src="{{ asset('queue/vendor/sparkline/index.js') }}"></script>

    <!-- App scripts -->
    <script src="scripts/homer.js"></script>
    <script src="scripts/charts.js"></script>

    <script>
        $(function() {

            /**
             * Flot charts data and options
             */
            var data1 = [
                [0, 55],
                [1, 48],
                [2, 40],
                [3, 36],
                [4, 40],
                [5, 60],
                [6, 50],
                [7, 51]
            ];
            var data2 = [
                [0, 56],
                [1, 49],
                [2, 41],
                [3, 38],
                [4, 46],
                [5, 67],
                [6, 57],
                [7, 59]
            ];

            var chartUsersOptions = {
                series: {
                    splines: {
                        show: true,
                        tension: 0.4,
                        lineWidth: 1,
                        fill: 0.4
                    },
                },
                grid: {
                    tickColor: "#f0f0f0",
                    borderWidth: 1,
                    borderColor: 'f0f0f0',
                    color: '#6a6c6f'
                },
                colors: ["#62cb31", "#efefef"],
            };

            $.plot($("#flot-line-chart"), [data1, data2], chartUsersOptions);

            /**
             * Flot charts 2 data and options
             */
            var chartIncomeData = [{
                label: "line",
                data: [
                    [1, 10],
                    [2, 26],
                    [3, 16],
                    [4, 36],
                    [5, 32],
                    [6, 51]
                ]
            }];

            var chartIncomeOptions = {
                series: {
                    lines: {
                        show: true,
                        lineWidth: 0,
                        fill: true,
                        fillColor: "#64cc34"

                    }
                },
                colors: ["#62cb31"],
                grid: {
                    show: false
                },
                legend: {
                    show: false
                }
            };

            $.plot($("#flot-income-chart"), chartIncomeData, chartIncomeOptions);


        });
    </script>

</body>

</html>