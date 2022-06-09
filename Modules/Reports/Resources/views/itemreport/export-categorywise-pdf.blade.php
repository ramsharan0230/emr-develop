@extends('inpatient::pdf.layout.main')

@section('title', 'ITEM CATEGORY WISE REPORT')

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
    <h2>Item Category Wise Report</h2>
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
                <th>Category</th>
                <th>Disc</th>
                <th>Tax</th>
                <th>Total Amount</th>
                <th>Receive Amount</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total_dsc = 0;
                $total_tax = 0;
                $total_totl = 0;
                $total_received = 0;
            @endphp
            @foreach ($datas as $data)
                @php
                    $total_dsc += $data->dsc;
                    $total_tax += $data->tax;
                    $total_totl += ($data->totl + $data->dsc);
                    $total_received += $data->totl;
                @endphp
                <tr>
                    <td>{{$data->flditemtype}}</td>
                    <td>Rs. {{ Helpers::numberFormat($data->dsc)}}</td>
                    <td>Rs. {{ Helpers::numberFormat($data->tax) }}</td>
                    <td>Rs. {{ Helpers::numberFormat($data->totl + $data->dsc) }}</td>
                    <td>Rs. {{ Helpers::numberFormat($data->totl) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div style="float: right;">
        <table id="totaltable">
            <tr>
                <td>Total Discount</td>
                <td>{{ Helpers::numberFormat($total_dsc) }}</td>
            </tr>
            <tr>
                <td>Total Tax</td>
                <td>Rs. {{ Helpers::numberFormat($total_tax) }}</td>
            </tr>
            <tr>
                <td>Total Amount</td>
                <td>Rs. {{ Helpers::numberFormat($total_totl) }}</td>
            </tr>
            <tr>
                <td>Total Received</td>
                <td>Rs. {{ Helpers::numberFormat($total_received) }}</td>
            </tr>
        </table>
    </div>
@endsection
