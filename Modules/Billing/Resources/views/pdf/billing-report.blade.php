{{-- <!DOCTYPE html>
<html>
<head>
    <title>Billing Report</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style type="text/css">
        .content-body tr td {
            padding: 5px;
        }

        p {
            margin: 4px 0;
        }

        .content-body {
            border-collapse: collapse;
        }

        .content-body td, .content-body th {
            border: 1px solid #ddd;
        }

        .content-body {
            font-size: 12px;
        }
    </style>

</head>
<body>
@include('pdf-header-footer.header-footer')
<main>

    <ul>
        <li>Billing Report </li>
        <li> {{ isset($from_date) ?  \App\Utils\Helpers::dateToNepali($from_date) :'' }} To  {{ isset($to_date) ?  \App\Utils\Helpers::dateToNepali($to_date) :'' }}</li>
    </ul> --}}<head>
    <title>Billing Report</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @media print {
            .page {
                margin: 5px;
            }           
        }
        .table {
            border-collapse: collapse;
            width: 100%;      
        }
        .table td, .table th {
            border: 1px solid #a79c9c;
            padding: 4px;
        }
        .text-center{
            text-align: center;
        }
        .text-left{
            text-align: left;
        }
        p, h3 {
            margin-bottom: 0; margin-top: 2px;
        }
        main{
            width: 90%;
            margin: 0 auto;;
        }
        .content-body table { page-break-inside:auto; }
        .content-body tr    { page-break-inside:avoid; page-break-after:auto }
        .border-none{
            border: none;
        }
        span{
            margin-top: 10px;
        }
    </style>
</head>

<div class="page">
    <div class="row">
            <table style="width: 100%;" >
                <tr>
                    <th colspan="7" style="text-align:center;">{{ isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'' }}</th>
                </tr>
                <tr>
                    <th colspan="7" style="text-align:center;">{{ isset(Options::get('siteconfig')['system_address'])?Options::get('siteconfig')['system_address']:'' }}</th>
                </tr>

                <tr>
                    <th colspan="7" style="text-align:center;">Billing  Report</th>
                </tr>
                <tr>
                    <th colspan ="3" style="text-align: left;">Date: {{ $data['date']?$data['date']['from']:'' }} To {{ $data['date']?$data['date']['to']:'' }}</th>
                    <th colspan ="1" style="text-align: left;"></th>
                    <th colspan ="3" style="text-align: right;">Printed By: {{\App\Utils\Helpers::getNameByUsername(\Auth::guard('admin_frontend')->user()->flduserid)}}<br>
                    Printed Time: {{ $data['date']?$data['date']['now']:'' }}
                </th>

                </tr>
            </table>
    </div>
    <div class="table-responsive res-table" style="max-height: none">

    {!! $data['html'] !!}
    {!! $data['sumhtml'] !!}
    @php
        $signatures = Helpers::getSignature('billing-report');
    @endphp
    @include('frontend.common.footer-signature-pdf')
    </div>
</div>
</main>
</body>
</html>
