<!DOCTYPE html>
<html lang="en">

<head>
    <title>Cogent Health Form</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/all.css') }}">

    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <script src="{{ asset('js/jquery.slim.js') }}" ></script>
    <script src="{{ asset('js/jquery-ui.js') }}"></script>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('js/typehead.js') }}"></script>
    <script src="{{ asset('NepaliDatepicker/nepali.datepicker.v2.2.min.js') }}"></script>
    <link href="{{ asset('NepaliDatepicker/nepali.datepicker.v2.2.min.css') }}" rel="stylesheet">


    <link rel="stylesheet" type="text/css" href="{{ asset('DataTables/datatables.min.css') }}"/>
    <script type="text/javascript" src="{{  asset('DataTables/datatables.min.js') }}"></script>


    <style>
        * {
            outline: unset;
        }
        button:focus {
            box-shadow: unset !important;
        }
        .container {
            max-width: 1200px;
        }
        .body_bg{
            overflow-x: hidden;
        }
        .body_bg,
        .menu_nav {
            background: #efebe7;
        }
        .menu_nav {
            border-bottom: 1px solid #e7e2dd;
        }
        .menu_nav li a {
            font-weight: 500;
            color: #000;
        }
        .form_title {
            font-size: 18px;
            color: #212529;
        }
        .row_bg {
            background-color: #e9e5e1;
            border: 1px solid #c9c6c4;
            padding: 10px;
            border-radius: 3px;
        }
        .photo_size {
            width: 30px;
        }
        .ip_relative {
            position: relative;
        }
        .img_in {
            position: absolute;
            right: 6px;
        }
        .cg_table tbody {
            background-color: white;
        }
        .check_btn {
            background: #f3f2f0 !important;
            border: 1px solid #c9c6c4 !important;
        }
        .fas_icon .fas {
            color: #6fdf6f;
        }
        .btn_button {
            background: #f3f2f0 !important;
            border: 1px solid #c9c6c4 !important;
            padding: 6px;
        }
        .nav-tabs li a {
            color: #000000;
        }
        .mr-sm-3,
        .mx-sm-3 {
            margin-right: 5px !important;
        }
        .alignCenter {
            align-self: center;
        }
        .btn_center {
            text-align: center;
        }
        .anatomy_section {
            position: relative;
            height: 474px;
            text-align: center;
        }
        .body_anatomy {
            height: 100%;
        }
        .circle_one {
            /*border: 3px solid #ffffff;*/
            height: 40px;
            width: 22px;
            position: absolute;
            padding: 5px;
            right: 172px;
            top: 25px;
            cursor: pointer;
        }
        .circle_two {
            /*border: 3px solid #ffffff;*/
            height: 75px;
            width: 15px;
            position: absolute;
            padding: 5px;
            right: 175px;
            top: 50px;
            cursor: pointer;
        }
        .circle_three {
            /*border: 3px solid #ffffff;*/
            height: 40px;
            width: 15px;
            position: absolute;
            padding: 5px;
            right: 175px;
            top: 125px;
            cursor: pointer;
        }
        .circle_four {
            /*border: 3px solid #ffffff;*/
            height: 42px;
            width: 23px;
            position: absolute;
            padding: 5px;
            right: 170px;
            top: 165px;
            cursor: pointer;
        }
        /*.circle_one {*/
        /*    !*border: 3px solid #ffffff;*!*/
        /*    height: 47px;*/
        /*    width: 22px;*/
        /*    position: absolute;*/
        /*    padding: 5px;*/
        /*    right: 167px;*/
        /*    top: 68px;*/
        /*    cursor: pointer;*/
        /*}*/
        /*.circle_two {*/
        /*    !*border: 3px solid #ffffff;*!*/
        /*    height: 75px;*/
        /*    width: 15px;*/
        /*    position: absolute;*/
        /*    padding: 5px;*/
        /*    right: 171px;*/
        /*    top: 118px;*/
        /*    cursor: pointer;*/
        /*}*/
        /*.circle_three {*/
        /*    !*border: 3px solid #ffffff;*!*/
        /*    height: 40px;*/
        /*    width: 15px;*/
        /*    position: absolute;*/
        /*    padding: 5px;*/
        /*    right: 170px;*/
        /*    top: 204px;*/
        /*    cursor: pointer;*/
        /*}*/
        /*.circle_four {*/
        /*    !*border: 3px solid #ffffff;*!*/
        /*    height: 42px;*/
        /*    width: 23px;*/
        /*    position: absolute;*/
        /*    padding: 5px;*/
        /*    right: 166px;*/
        /*    top: 246px;*/
        /*    cursor: pointer;*/
        /*}*/
        .circle_five {
            /*border: 3px solid #ffffff;*/
            height: 96px;
            width: 40px;
            position: absolute;
            padding: 5px;
            left: 106px;
            top: 136px;
            cursor: pointer;
        }
        .circle_six {
            /*border: 3px solid #ffffff;*/
            height: 90px;
            width: 44px;
            position: absolute;
            padding: 5px;
            right: 89px;
            top: 141px;
            cursor: pointer;
        }
        .circle_seven {
            /*border: 3px solid #ffffff;*/
            height: 102px;
            width: 42px;
            position: absolute;
            padding: 5px;
            left: 149px;
            top: 348px;
            cursor: pointer;
        }
        .circle_eight {
            /*border: 3px solid #ffffff;*/
            height: 103px;
            width: 42px;
            position: absolute;
            padding: 5px;
            right: 134px;
            top: 348px;
            cursor: pointer;
        }
        .l_r {
            display: inline-block;
            background-color: #e9e5e1;
            border: 1px solid #c9c6c4;
            padding: 5px 15px;
            border-radius: 3px;
            font-size: 1.5rem;
        }
        .fa-play {
            color: #6fdf6f;
        }
        .fa-stop {
            color: #dc3545;
        }
        .notes {
            background-color: #e9e5e1;
            border: 1px solid #c9c6c4;
            padding: 10px;
            border-radius: 3px;
        }
        .drugs_table.table-bordered th,
        .drugs_table td {
            border: 1px solid #c9c6c4;
        }
        .drugs_table {
            background-color: #e9e5e1;
        }

        .ui-autocomplete { max-height: 200px; overflow-y: auto; overflow-x: hidden; padding-right: 20px;}

        .diagnosis_table_body{
            /*display:inline-block;*/
            overflow:auto;
            height:200px;
            width:100%;
        }
        .drug_table_body{
            display:inline-block;
            overflow:auto;
            height:350px;
            width:100%;
        }
        .drug_table_body tr {
            cursor: pointer;
        }
        .sub_diagnosis_table_body{
            display:block;
            overflow:auto;
            height:200px;
            width:100%;
        }

        .drug-table-modal td, .drug-table-modal th {
            padding: 5px 13px 5px 9px;
            vertical-align: middle !important;
            border-top: 1px solid #dee2e6;
            font-size: 14px;
        }

        a { color: inherit; }
        #diagnosis_table tr {
            cursor: pointer;
        }
        .sub_diagnosis_table_body tr {
            cursor: pointer;
        }

        .table-diagnosis td, .table-diagnosis th {
            padding: 0.3rem !important;
        }

        .btnDiagnosisName, .btnDiagnosisName:hover{
            text-decoration: none;
            color: #212529;
        }

        .diagnosisSelected {
            background: #e0dbdb;
        }

        .list-group-item {
            font-size: 14px;
            padding: 3px 30px 3px 7px !important;
            border: none !important;
        }

        li.list-group-item a {
            text-decoration: none;
        }
        ul {
            list-style-type: none;
        }

        .drugs_table_list {
            background-color: #ffffff;
        }

        .drugs_table_list td, .drugs_table_list th {
            vertical-align: middle !important;
            padding: 3px 0 3px 20px;
        }

        .drugs_table_list thead tr {
            background: #28a745;
            color: #ffffff;
            padding: 8px 0 0 0;
        }

        .drugs_table_list th{
            padding: 5px 0 5px 20px;
        }

        .list-group-item {
            padding: 0px 30px 0px 7px !important;
        }

        table.dataTable tbody th, table.dataTable tbody td {
            font-size: 15px;
        }

        div#table-diagnosis_filter {
            float: left;
            margin-bottom: 13px;
        }

        .table-intake thead tr {
            background: #28a745;
            color: #ffffff;
            padding: 8px 0 0 0;
        }

        .table-intake,.table-intake-particulars {
            background-color: #ffffff;
        }

        .table-intake td, .table-intake-particulars td {
            white-space: nowrap;
        }

        .table-intake td, .table-intake th {
            padding: 0.3rem !important;
        }

        .table-intake tr, .table-intake tr {
            vertical-align:center !important;
        }

        .table td, .table th {
            vertical-align: middle !important;
        }

        .table-intake-particulars thead tr {
            background: #28a745;
            color: #ffffff;
            padding: 8px 0 0 0;
        }

        .table-intake-particulars th, .table-intake-particulars td ,.table-intake th, .table-intake td  {
            padding: 0.15rem 0.5rem 0.15rem 0.5rem;
            font-size: 14px;
        }

        .table-intake-particulars, .table-intake{
            max-height: 171px;
        }

        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }

    </style>
    <script>
        $(document).ready(function () {
            $('[data-tooltip="tooltip"]').tooltip();

            $("#report_date").datepicker({
                //changeYear: true,
                changeMonth: true,
                dateFormat: "yy-mm-dd",
                autoclose: true,
            });


        });
        $(document).ready(function() {
            $('body').tooltip({
                selector: "[data-tooltip=tooltip]",
                container: "body"
            });
        });
    </script>
</head>

<body class="body_bg">

    @include('neuro::common.header')

    @yield('content')

</body>

</html>
