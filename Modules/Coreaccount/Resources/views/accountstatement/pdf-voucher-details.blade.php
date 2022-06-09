<!DOCTYPE html>
<!-- saved from url=(0040)file:///C:/Users/DELL/Downloads/pdf.html -->
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<!-- <title>Invoice for {{ $patbillingDetails->fldbillno??"" }}</title> -->
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
    .content-body th:nth-child(4),
    .content-body td:nth-child(5),
    .content-body th:nth-child(5) {
        text-align: left;
    }


    .content-body td,
    .content-body th {
        border: 1px solid #ddd;
        font-size: 13px;
        text-align: right;
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
    @include('frontend.common.account-header')
    <div style="text-align: center">
        <h3>{{ $voucherDatas[0]->VoucherCode }} Voucher</h3>
    </div>
    <div class="main-body">
        <div class="pdf-container" style="margin: 0 auto; width: 95%;">

            <div style="width: 100%;"></div>
            <table style="width: 60%; float: left;">
                <tbody>
                <tr>
                    <td>Voucher No. : <b>{{$voucher_no}}</b></td>
                </tr>
                <tr>
                    <td>Date: <b>{{$date}}</b></td>
                </tr>
                </tbody>
            </table>
            <div style="clear: both"></div>
        </div>
        <div class="form-group">
            <div class="pdf-container" style="margin: 0 auto; width: 95%;">
                <div class="table-dental2" style="margin-top: 16px;">
                    <table class="table content-body">
                        <thead class="thead-light">
                        <tr>
                            <th class="text-center">S/N</th>
                            <th class="text-center">Acc No.</th>
                            <th class="text-center">Account Head</th>
                            <th class="text-center">Description</th>
                            <th class="text-center">Dr Amount</th>
                            <th class="text-center">Cr Amount</th>
                        </tr>
                        </thead>
                        <tbody id="voucher-details">
                        @php
                            $drAmount =0;
                            $crAmount =0;
                        @endphp
                        @foreach ($voucherDatas as $key=>$voucherData)
                            <tr>
                                <td>{{++$key}}</td>
                                <td>{{$voucherData->AccountNo}}</td>
                                <td>{{$voucherData->accountLedger ? $voucherData->accountLedger->AccountName : ''}}</td>
                                <td>{{$voucherData->Narration}}</td>
                                @if ($voucherData->TranAmount > 0)
                                    @php
                                        $drAmount += $voucherData->TranAmount;
                                    @endphp
                                    <td>{{abs(($voucherData->TranAmount))}}</td>
                                    <td></td>
                                @else
                                    @php
                                        $crAmount += $voucherData->TranAmount;
                                    @endphp
                                    <td></td>
                                    <td>{{\App\Utils\Helpers::numberFormat(abs($voucherData->TranAmount))}}</td>
                                @endif
                            </tr>
                        @endforeach
                        @if($voucherDatas)
                            <tr>
                                <th colspan="4">Total</th>
                                <th>{{ \App\Utils\Helpers::numberFormat($drAmount) }}</th>
                                <th>{{ \App\Utils\Helpers::numberFormat(abs($crAmount)) }}</th>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
                <div class="row">
                   <p>
                       <b>Particulars:</b> {{ $voucherDatas[0]->Remarks }}
                   </p>
                    <p>
                        <b>In Words: Rs.</b> {{ ucwords(\App\Utils\Helpers::numberToNepaliWords($drAmount)) }}
                    </p>
                </div>
            </div>
        </div>
        <section>
            <div>
                <div style="width: 30%; margin-left: 2rem; float: left">
                    <p>{{$voucherDatas[0]->CreatedBy}}</p>
                <p style="margin-top: 5px">_________________________</p>
                <p>Entered By : </p>
            </div>
            <div style="width: 30%; margin-left: 2rem; float: left">
                <p>{{\Auth::guard('admin_frontend')->user()->username}}</p>
                <p style="margin-top: 5px">_________________________</p>
                <p>Generated By : </p>
            </div>
                <div style="width: 30%; margin-left: 2rem; float: left">
                    <p style="margin-top: 40px">_________________________</p>
                    <p>Approved By : </p>
                </div>
            </div>
            <div style="clear: both"></div>
        </section>
    </div>
</div>
</body>
</html>
