<head>
    <title>Off Time Report</title>
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
                            <h4 style="text-align: center;">Off Time Report</h4>
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
            <td>Time : {{ $_GET['from_time'] }} to 
            {{ $_GET['to_time']}}</td>
            <td style="text-align: right">Printed Time: {{ \Carbon\Carbon::now() }}</td>
        </tr>
    </table>

    <div class="table-responsive res-table" style="max-height: none">
        <table class="table content-body">
            <thead>
                <tr>
                    <th>SN</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Invoice</th>
                    <th>EnciD</th>
                    <th>Name</th>
                    <th>Particulars</th>
                    <th>Rate</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @if(!$results->isEmpty())
                    @php
                        $count=1;
                    @endphp
                         @forelse ($results as $report )
                         <tr>
                             <td>{{ $loop->index+1 }}</td>
                             {{-- <td> {{ \Carbon\Carbon::parse($report->time)->format('Y-m-d') }} </td>
                             <td> {{ \Carbon\Carbon::parse($report->time)->format('h:i:s') }} </td> --}}
                             <td>{{ \App\Utils\Helpers::dateEngToNepdash(\Carbon\Carbon::parse($report->fldtime)->format('Y-m-d'))->full_date }}</td>
                            <td> {{ \Carbon\Carbon::parse($report->fldtime)->format('H:i:s') }} </td>
                             <td> {{ $report->fldbillno }} </td>
                             <td> {{ $report->fldencounterval }} </td>
                             <td> {{ $report->flduserid }} </td>
                             <td> {{ $report->flditemtype }} </td>
                             <td> {{ $report->flditemrate }} </td>
                             <td> {{ $report->flditemqty }} </td>
                             <td> {{ $report->fldditemamt }} </td>
                         </tr>
                     @empty
                         
                     @endforelse
                @endif
                <tr>
                    {{-- @dd($summary->sum('itemamt')) --}}
                    <td colspan="2">Subtotal: Rs. {{ $summary->sum('itemamt')  }} </td>
                    <td colspan="3">Tax: Rs. {{ $summary->sum('itetaxamtmamt')  }}</td>
                    <td colspan="2">Discount: Rs. {{ $summary->sum('dscamt')  }}</td>
                    <td colspan="3">Total Amount: Rs. {{  $summary->sum('recvamt') }}</td>
                </tr>
            </tbody>
        </table>
        <div class="table">
            <table style="width: 100%" class="content-body" id="sum">
                {{-- <div id="sum"> --}}
                    {{-- @if($summary->isNotEmpty())
                        <tr>
                          
                            <td>Subtotal: Rs. {{ $summary->sum('itemamt')  }} </td>
                            <td>Tax: Rs. {{ $summary->sum('itetaxamtmamt')  }}</td>
                            <td>Discount: Rs. {{ $summary->sum('dscamt')  }}</td>
                            <td>Total Amount: Rs. {{  $summary->sum('recvamt') }}</td>
                        </tr>
                    @endif --}}
            </table>
        </div>
    </div>
</div>
