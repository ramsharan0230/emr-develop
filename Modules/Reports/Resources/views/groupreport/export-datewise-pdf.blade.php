@extends('inpatient::pdf.layout.main')

@section('title', 'DATEWISE GROUP REPORT')

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
                <th>Date</th>
                <th>Particulars</th>
                <th>Rate</th>
                <th>Qty</th>
                <th>Disc</th>
                <th>Tax</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($datas as $data)
                <tr colspan="7">
                    <td>{{($dateType == "invoice_date") ? $data->invoice_date : $data->entry_date}}</td>
                </tr>
                <tr>
                    <td>{{($dateType == "invoice_date") ? $data->invoice_date : $data->entry_date}}</td>
                    <td>{{($itemRadio == "select_item") ? $selectedItem : "%"}}</td>
                    <td>Rs. {{$data->rate}}</td>
                    <td>{{$data->qnty}}</td>
                    <td>Rs. {{$data->dsc}}</td>
                    <td>Rs. {{$data->tax}}</td>
                    <td>Rs. {{$data->totl}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
