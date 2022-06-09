<!DOCTYPE html>
<html>

<head>
    <title>@yield('title')</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @page {
            margin: 24mm 0 11mm;
        }

        body {
            margin: 0 auto;
            padding: 10px 10px 5px;
            font-size: 13px;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        }

        .bill-title {
            position: absolute;
            width: 100%;
            text-align: center;
            margin-bottom: 2px;
            margin-top: 3px;
        }


        .a4 {
            width: auto;
            margin: 0 auto;
        }

        .footer {
            /* position: absolute; */
            width: 100%;
            text-align: center;
            margin:0;
            padding:0;
        }

        .bar-code {
            width: 200px;
            height: auto;
            margin-top:5px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .pdf-container{
        margin: 0 auto;
        width: 95%;
        }

        .content-body {
            border-collapse: collapse;
        }

        .content-body table {
            page-break-inside: auto
        }

        .content-body tr {
            page-break-inside: avoid;
            page-break-after: auto
        }

        .content-body td:nth-child(1),
        .content-body th:nth-child(1),
        .content-body td:nth-child(2),
        .content-body th:nth-child(2),
        .content-body td:nth-child(3),
        .content-body th:nth-child(3),
        .content-body td:nth-child(4),
        .content-body th:nth-child(4) {
            text-align: left;

        }


        .content-body td,
        .content-body th {
            border: 1px solid #ddd;
            font-size: 13px;
            text-align: right;
            padding-right:4px;
        }



        h2,
        h4 {
            line-height: 0.5rem;
        }

        ul {
            float: right;
            padding: 0;
            margin: 0;
        }

        ul li {
            text-align: right;
            ;
            list-style: none;

        }

        ul li span:first-child {
            text-align: left;
        }

        ul li span:nth-child(2) {
            text-align: right;
            width: 120px;
            display: inline-block;
        }

        .left-align{
            text-align: left !important;
        }

        .right-align{
            text-align: right !important;
        }
    </style>
    @yield('styles')
</head>

<body>


@include('pdf-header-footer.header-footer')
<main>

    @if(isset($patientinfo))
    <table style="width: 100%;">
        <tbody>
        <tr>
            <td style="width:40%">
                <p>Name: {{ $patientinfo->patientInfo->fldrankfullname }} ({{$patientinfo->fldpatientval}})</p>
                <p>Age/Sex: {{ $patientinfo->patientInfo->fldagestyle }}yrs/{{ $patientinfo->patientInfo->fldptsex ?? "" }}</p>
                <p>Address: {{ $patientinfo->patientInfo->fldptaddvill ?? "" . ', ' . $patientinfo->patientInfo->fldptadddist ?? "" }}</p>
                <p>REPORT: @yield('report_type')</p>
            </td>
            <td style="width: calc(60% - 120px);">

                <p>DOReg: {{ $patientinfo->fldregdate ? \Carbon\Carbon::parse(  $patientinfo->fldregdate)->format('d/m/Y'):'' }}</p>
                <p>Phone: {{ $patientinfo->patientInfo->fldptcontact ?? "" }}</p>
            </td>
            <td style="width: 120px;text-align:left;">{!! Helpers::generateQrCode($patientinfo->fldpatientval)!!}</td>
        </tr>
        </tbody>
    </table>
    @endif

    @yield('content')

    @php
        if(Request::is('admin/laboratory/*'))
            $signatures = Helpers::getSignature('laboratory');
        elseif(Request::is('eye/*'))
            $signatures = Helpers::getSignature('eye');
        elseif(Request::is('delivery/*'))
            $signatures = Helpers::getSignature('delivery');
        elseif(Request::is('radiology/*'))
            $signatures = Helpers::getSignature('radiology');
        elseif(Request::is('inpatient/*'))
            $signatures = Helpers::getSignature('ipd');
    @endphp
    @include('frontend.common.footer-signature-pdf')
</main>
@stack('after-script')
</body>

</html>
