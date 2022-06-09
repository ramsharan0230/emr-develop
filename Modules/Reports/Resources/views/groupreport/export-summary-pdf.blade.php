@extends('inpatient::pdf.layout.main')

@section('title', 'GROUP SUMMARY REPORT')

@section('content')
    @if(isset($certificate))
        <h4 style="text-align: center;">{{ucfirst($certificate)}} REPORT</h4>
    @endif
    <table style="width: 100%;">
        <tbody>
        <tr>
            <td style="width: 200px;">
                <p>From Date: {{ $finalfrom}}</p>
                <p>To Date: {{ $finalto }}</p>
                <p>Billing Mode: {{ $billingmode }}</p>
                <p>Comp: {{ $comp }}</p>
                <p>Group: {{ ($itemRadio == "select_item") ? $selectedItem : "%"}}</p>
            </td>
        </tbody>
    </table>
    <table style="width: 100%;"  class="content-body">
        <thead>
            <tr>
                <th>Particulars</th>
                <th>Rate</th>
                <th>Qty</th>
                <th>Disc</th>
                <th>Tax</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalRate = 0;
                $totalQty = 0;
                $totalDisc = 0;
                $totalTax = 0;
                $grandTotal = 0;
            @endphp
            @foreach ($datas as $data)
                <tr>
                    <td>{{$data->flditemname}}</td>
                    <td>Rs. {{$data->rate}}</td>
                    <td>{{$data->qnty}}</td>
                    <td>Rs. {{$data->dsc}}</td>
                    <td>Rs. {{$data->tax}}</td>
                    <td>Rs. {{$data->tot}}</td>
                </tr>
                @php
                    $totalRate += $data->rate;
                    $totalQty += $data->qnty;
                    $totalDisc += $data->dsc;
                    $totalTax += $data->tax;
                    $grandTotal += $data->tot;
                @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td>Total</td>
                <td>Rs. {{$totalRate}}</td>
                <td>{{$totalQty}}</td>
                <td>Rs. {{$totalDisc}}</td>
                <td>Rs. {{$totalTax}}</td>
                <td>Rs. {{$grandTotal}}</td>
            </tr>
        </tfoot>
    </table>
@endsection
