@extends('inpatient::pdf.layout.main')

@section('title', 'ITEM DATES REPORT')

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
    <h2>Item Dates Report</h2>
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
                <th>Particular</th>
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
            @endphp
            @foreach ($datas as $itemtype => $data)
                <tr>
                    <td colspan="9" style="text-align: center;"><b>{{ $itemtype }}<b></td>
                </tr>
                @foreach($data as $date=>$d)
                    <td colspan="9"><b>{{ isset($date) ? \App\Utils\Helpers::dateToNepali($date) :'' }}<b></td>
                    @foreach($d as $item)
                        @php
                            $total_qty += $item->qnty;
                            $total_rate += $item->rate;
                            $total_dsc += $item->dsc;
                            $total_tax += $item->tax;
                            $total_totl += ($item->totl + $item->dsc);
                            $total_received += $item->totl;
                        @endphp
                        <tr>
                            {{-- <td>{{($dateType == "invoice_date") ? $d->invoice_date : $d->entry_date}}</td> --}}
                            <td>{{$item->flditemname}}</td>
                            <td>{{ Helpers::numberFormat($item->rate) }}</td>
                            <td>{{$item->qnty}}</td>
                            <td>Rs. {{ Helpers::numberFormat($item->dsc) }}</td>
                            <td>Rs. {{ Helpers::numberFormat($item->tax) }}</td>
                            <td>Rs. {{ Helpers::numberFormat($item->totl + $item->dsc) }}</td>
                            <td>Rs. {{ Helpers::numberFormat)($item->totl)) }}</td>
                        </tr>
                    @endforeach
                @endforeach
            @endforeach
        </tbody>
        {{-- <tfoot>
            <tr>
                <td>Total</td>
                <td>{{ ($total_particulars > 0) ? $total_rate/$total_particulars : "0" }}</td>
                <td>{{ Helpers::numberFormat($total_qty) }}</td>
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
                <td>{{ Helpers::numberFormat( $total_qty)  }}</td>
            </tr>
            <tr>
                <td>Total Discount</td>
                <td>Rs. {{  Helpers::numberFormat($total_dsc ) }}</td>
            </tr>
            <tr>
                <td>Total Tax</td>
                <td>Rs. {{ Helpers::numberFormat($total_tax) }}</td>
            </tr>
            <tr>
                <td>Total Amount</td>
                <td>Rs. {{  Helpers::numberFormat($total_totl)  }}</td>
            </tr>
            <tr>
                <td>Total Received</td>
                <td>Rs. {{ Helpers::numberFormat( $total_received)  }}</td>
            </tr>
        </table>
    </div>

@endsection
