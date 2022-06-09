<head>
    <title>Top 10 Performing Doctors</title>
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
                    <td style="width: 10%;"><img src="{{ asset('uploads/config/'.Options::get('brand_image')) }}" alt="" width="100" height="100"/></td>
                    <td style="width:80%;">
                        <h3 style="text-align: center;margin-bottom:8px;">{{ isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'' }}</h3>
                        <h4 style="text-align: center;margin-top:4px;margin-bottom:0;">{{ isset(Options::get('siteconfig')['system_slogan'])?Options::get('siteconfig')['system_slogan']:'' }}</h4>
                        <h4 style="text-align: center;margin-top:4px;">{{ isset(Options::get('siteconfig')['system_address'])?Options::get('siteconfig')['system_address']:'' }}</h4>
                            <h4 style="text-align: center;"> Top 10 Performing Doctors({{$encountertype}})</h4>
                    </td>
                    <td style="width: 10%;"></td>
                </tr>
            </table>
    </div>

    <table style="width:100%">
        <tr>
            <td>
                Date:{{isset($_GET['from_date']) ?$_GET['from_date']: '' }}  TO
                {{isset($_GET['to_date']) ? $_GET['to_date']: '' }}</td>
            <td style="text-align: right">Printed By: {{$userid??''}}</td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align: right">Printed Time: {{ \Carbon\Carbon::now() }}</td>
        </tr>
    </table>

    <div class="table-responsive res-table" style="max-height: none">
        <table class="table content-body">
            <thead>
            <tr>
                <th style="text-align: left;">SN</th>
                <th style="text-align: left;">Doctor Name</th>
                <th style="text-align: left;">Revenue generated</th>
            </tr>
            </thead>
            <tbody>
            @if(!$top_ten_doc->isEmpty())
            <?php
                $count = 1;
            ?>
            @foreach ($top_ten_doc as $list)
            <tr>
                <td>{{$count++}}</td>
                <td>{{$list->fullnames}} </td>
                <td>{{ \App\Utils\Helpers::numberFormat($list->total_amount)}} </td>
            </tr>
            @endforeach
            @endif
            </tbody>
        </table>
    </div>
</div>
