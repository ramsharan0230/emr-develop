<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body>
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
        margin: 0;
        padding: 0;
    }

    .bar-code {
        width: 200px;
        height: auto;
        margin-top: 5px;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
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
        text-align: left;
        padding-right: 4px;
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
        text-align: right;;
        list-style: none;

    }

    ul li span:first-child {
        text-align: left;
    }

    ul li span:nth-child(2) {
        text-align: right;
        width: 150px;
        display: inline-block;
    }

    .table thead {
        background-color: #fff;
    }
</style>
<div class="a4">

    <div class="main-body">

        <div class="pdf-container" style="margin: 0 auto; width: 95%;">

            <div style="width: 100%;"></div>
            <table style="width: 60%;float:left;">
                <tbody>
                <tr>
                    <td>From Date: {{ $fromDate }} {{ isset($fromDate) ? "(". \App\Utils\Helpers::dateToNepali($fromDate). ")" :'' }}</td>
                    <td>To Date: {{ $toDate }}  {{ isset($toDate) ? "(". \App\Utils\Helpers::dateToNepali($toDate). ")" :'' }}</td>
                </tr>

                </tbody>
            </table>
            <div style="clear: both"></div>
        </div>

        <div class="pdf-container" style="margin: 0 auto; width: 95%;">
            <div class="table-dental2" style="margin-top: 16px;">
                <table class="table content-body">
                    <thead>
                    <tr>
                        <th>S.N</th>
                        <th>Entry Date</th>
                        <th>Encounter</th>
                        <th>Invoice</th>
                        <th>Patient Name</th>
                        <th>Particulars</th>
                        <th>Rate</th>
                        <th>Qty</th>
                        <th>Disc(Rs.)</th>
                        <th>Tax(Rs.)</th>
                        <th>Item Amt(Rs.)</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $total_qty = 0;
                        $total_rate = 0;
                        $total_dsc = 0;
                        $total_tax = 0;
                        $total_totl = 0;
                        $total_particulars = 0;
                        // $total_prev_amt = 0;
                        // $total_remain_amt = 0;
                        $total_receive_amt = 0;
                    @endphp
                    @foreach ($tpBilling as $data)
                        @php
                            $total_qty += $data->flditemqty;
                            $total_rate += $data->flditemrate;
                            $total_dsc += $data->flddiscamt;
                            $total_tax += $data->fldtaxamt;
                            $total_totl += $data->fldditemamt;
                            $total_particulars += 1;
                        @endphp
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{ isset($data->fldordtime) ? \App\Utils\Helpers::dateToNepali($data->fldordtime) :'' }}</td>
                            <td>{{$data->fldencounterval}}</td>
                            <td>{{$data->fldtempbillno}}</td>
                            <td>{{(isset($data->encounter->patientInfo)) ? $data->encounter->patientInfo->getFldrankfullnameAttribute() : ""}}</td>
                            <td>{{$data->flditemname}}</td>
                            <td>{{  \App\Utils\Helpers::numberFormat($data->flditemrate) }}</td>
                            <td>{{$data->flditemqty}}</td>
                            <td>{{  \App\Utils\Helpers::numberFormat($data->flddiscamt) }}</td>
                            <td>{{  \App\Utils\Helpers::numberFormat($data->fldtaxamt) }}</td>
                            <td>{{  \App\Utils\Helpers::numberFormat($data->fldditemamt) }}</td>
                        </tr>
                        {{-- @endif --}}
                    @endforeach
                    </tbody>
                </table>
                <div style="width: 50%;float: left;">
                    <p>Total Quantity: {{ $total_qty }}</p>
                    <p>Total Discount: Rs. {{  \App\Utils\Helpers::numberFormat($total_dsc) }}</p>
                    <p>Total Tax: Rs. {{  \App\Utils\Helpers::numberFormat($total_tax) }}</p>
                    <p>Total Amount: Rs. {{  \App\Utils\Helpers::numberFormat($total_totl) }}</p>
                </div>

            </div>
        </div>
    </div>
</div>


</body>
<script src="{{asset('assets/js/jquery-3.4.1.min.js')}}"></script>
<script>
    $(document).ready(function () {
        setTimeout(function () {
            window.print();
        }, 3000);
    });
</script>
</html>
