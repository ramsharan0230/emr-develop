@extends('inpatient::pdf.layout.main')

@section('title')
Medicine Expiry Report
@endsection
@include('pdf-header-footer.hospital-header')
<h4 style="text-align: center;margin-top:2px;">Medicine Expiry Report</h4>
@section('content')
@php $grandtotal = 0; @endphp
    <ul>
{{--        <li>Medicine Expiry Report</li>--}}
    </ul>
    <table class="table content-body">
        <thead>
            <tr>
                <th></th>
                <th width="500px">Stock Id</th>
                <th>Supplier</th>
                <th>Purchase Number</th>
                <th>Batch</th>
                <th>Expiry</th>
                <th>Quantity</th>
                <th>Rate</th>
                <th>Total</th>
                <th>Category</th>
            </tr>
        </thead>
        <tbody>
            @if ($medicines)
                @foreach ($medicines as $medicine)
                @php
                    $total = $medicine->fldqty*$medicine->fldsellpr;
                    $grandtotal += $total;
                @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $medicine->fldstockid }}</td>
                        @if(isset($medicine->hasTransfer))
                        @php $suppliername = Helpers::getSuppName($medicine->hasTransfer->fldoldstockno);
                        @endphp
                        @else
                        @php $suppliername = Helpers::getSuppName($medicine->fldstockno);
                        @endphp
                        @endif
                        <td>{{ isset($suppliername) ? $suppliername->fldsuppname : '' }}</td>
                        <td>{{ isset($suppliername) ? $suppliername->fldreference : '' }}</td>
                        <td>{{ $medicine->fldbatch }}</td>
                        <td>{{ explode(' ', $medicine->fldexpiry)[0] }}</td>
                        <td>{{ $medicine->fldqty }}</td>
                        <td>{{ $medicine->fldsellpr }}</td>
                        <td>{{ $total }}</td>
                        <td>{{ $medicine->fldcategory }}</td>
                    </tr>
                @endforeach
            @endif
            <tr>
                <td colspan="7">Total</td>
                <td colspan="2">{{ $grandtotal }}</td>
            </tr>
        </tbody>
    </table>
@endsection
