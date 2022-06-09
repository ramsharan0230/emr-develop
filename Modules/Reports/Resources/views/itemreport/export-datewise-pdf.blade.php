@extends('inpatient::pdf.layout.main')

@section('title', 'ITEM DATEWISE REPORT')

@section('content')
    <table style="width: 100%;">
        <tbody>
        <tr>
            <td>
                <p>From Date: {{ $finalfrom}} {{ isset($finalfrom) ? "(" .\App\Utils\Helpers::dateToNepali($finalfrom) .")" :'' }}</p>
            </td>
            <td>
                <p>To Date: {{ $finalto }} {{ isset($finalto) ? "(" .\App\Utils\Helpers::dateToNepali($finalto) .")" :'' }}</p>
            </td>
            <td>
                <p>Category: {{ $category }}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p>Billing Mode: {{ $billingmode }}</p>
            </td>
            <td>
                <p>Comp: {{ $comp }}</p>
            </td>
            <td>
                <p>Particulars: {{ ($itemRadio == "all_items") ? "%" : $selectedItem }}</p>
            </td>
        </tr>
        </tbody>
    </table>
    <table style="width: 100%;"  class="content-body">
        <thead>
            <tr>
                <th>Date</th>
                @if($itemRadio == "packages")
                <th>Package Name</th>
                @else
                <th>Particulars</th>
                @endif
                <th>Rate</th>
                <th>Qty</th>
                <th>Disc</th>
                <th>Tax</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($datas as $data)
                <tr>
                    <td>{{($dateType == "invoice_date") ? \App\Utils\Helpers::dateToNepali($data->invoice_date) : \App\Utils\Helpers::dateToNepali($data->entry_date)}}</td>
                    @if($itemRadio == "packages")
                    <td>{{ $data->package_name ?? 'N/A' }}</td>
                    @else
                    <td>{{ $data->flditemname }}</td>
                    @endif
                    <td>{{ Helpers::numberFormat($data->rate)}}</td>
                    <td>{{$data->qnty}}</td>
                    <td>Rs. {{ Helpers::numberFormat($data->dsc)}}</td>
                    <td>Rs. {{ Helpers::numberFormat($data->tax)}}</td>
                    <td>Rs. {{ Helpers::numberFormat($data->totl+ $data->dsc)) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
