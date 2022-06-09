@extends('inpatient::pdf.layout.main')

@section('title', 'ITEM DETAIL REPORT')

@section('content')
    <style>
        h2 {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        #alignleft {
            text-align: left;
        }
        #totaltable {
            margin-top: 20px;
        }

        .heading {
            margin-bottom: 20px;
        }

        .heading tbody tr td:nth-child(2) {
            text-align: right;
        }

        #totaltable td {
            text-align: right;
            height: 20px;
            width: 130px;
        }

    </style>
    <h2>Item Detail Report</h2>
    <table style="width: 100%;" class="heading">
        <tbody>
        <tr>
            <td>
                From Date: {{ $finalfrom}} {{ isset($finalfrom) ? "(" .\App\Utils\Helpers::dateToNepali($finalfrom) .")" :'' }}
            </td>
            <td id="alignright">
                Category: {{ $category }}
            </td>
        </tr>
        <tr>
            <td>
                To Date: {{ $finalto }} {{ isset($finalto) ? "(" .\App\Utils\Helpers::dateToNepali($finalto) .")" :'' }}
            </td>
            <td id="alignright">
                Billing Mode: {{ $billingmode }}
            </td>
        </tr>
        <tr>
            <td>
                Particulars: {{ ($itemRadio == "select_item") ? $selectedItem : "%"}}
            </td>
            <td id="alignright">
                Comp: {{ $comp }}
            </td>
        </tr>
        </tbody>
    </table>
    <table style="width: 100%;"  class="content-body">
        <thead>
            <tr>
                <th>Date</th>
                <th>EncID</th>
                <th>Patient Name</th>
                <th>Particulars</th>
                <th>Rate</th>
                <th>Qty</th>
                <th>Disc</th>
                <th>Tax</th>
                <th>Total Amount</th>
                <th>Receive Amount</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total_qty = 0;
                $total_rate = 0;
                $total_dsc = 0;
                $total_tax = 0;
                $total_totl = 0;
                $total_received = 0;
                $total_particulars = 0;
            @endphp
            @foreach ($datas as $itemtype => $data)
                @php
                    $total_particulars += count($data);
                @endphp
                <tr>
                    <td colspan="9" style="text-align: center;"><b>{{ $itemtype }}<b></td>
                </tr>
                @foreach($data as $d)
                    @php
                        $total_qty += $d->qnty;
                        $total_rate += $d->rate;
                        $total_dsc += $d->dsc;
                        $total_tax += $d->tax;
                        $total_totl += ($d->totl + $d->dsc);
                        $total_received += $d->totl;
                    @endphp
                    <tr>
                        <td>{{($dateType == "invoice_date") ? \App\Utils\Helpers::dateToNepali($d->invoicetime) : \App\Utils\Helpers::dateToNepali($d->entrytime) }}</td>
                        <td>{{$d->fldencounterval}}</td>
                        <td>{{(isset($d->encounter->patientInfo)) ? $d->encounter->patientInfo->getFldrankfullnameAttribute() : ""}}</td>
                        <td>{{$d->flditemname}}</td>
                        <td>{{  \App\Utils\Helpers::numberFormat(($d->rate))}}</td>
                        <td>{{$d->qnty}}</td>
                        <td>Rs. {{ Helpers::numberFormat($d->dsc)}}</td>
                        <td>Rs. {{ Helpers::numberFormat($d->tax)}}</td>
                        <td>Rs. {{ Helpers::numberFormat($d->totl + $d->dsc)}}</td>
                        <td>Rs. {{ Helpers::numberFormat($d->totl)}}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
        {{-- <tfoot>
            <tr>
                <td>Total</td>
                <td>{{ ($total_particulars > 0) ? $total_rate/$total_particulars : "0" }}</td>
                <td>{{ $total_qty }}</td>
                <td>Rs. {{ Helpers::numberFormat($total_dsc) }}</td>
                <td>Rs. {{ Helpers::numberFormat($total_tax) }}</td>
                <td>Rs. {{ Helpers::numberFormat($total_totl) }}</td>
            </tr>
        </tfoot> --}}
    </table>
    <div style="float: right;">
        <table id="totaltable">
            <tr>
                <td>Total Quantity</td>
                <td>{{ $total_qty }}</td>
            </tr>
            <tr>
                <td>Total Discount</td>
                <td>Rs. {{ Helpers::numberFormat($total_dsc))}}</td>
            </tr>
            <tr>
                <td>Total Tax</td>
                <td>Rs. {{ Helpers::numberFormat($total_tax)}}</td>
            </tr>
            <tr>
                <td>Total Amount</td>
                <td>Rs. {{ Helpers::numberFormat($total_totl)}}</td>
            </tr>
            <tr>
                <td>Total Received</td>
                <td>Rs. {{ Helpers::numberFormat($total_received)}}</td>
            </tr>
        </table>
    </div>
@endsection
