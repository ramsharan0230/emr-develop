@extends('inpatient::pdf.layout.main')

@section('title')
    Stock Return Report
@endsection
@include('pdf-header-footer.hospital-header')
<h4 style="text-align: center;margin-top:2px;">Stock Return Report</h4>
@section('content')
{{--    <p align="center" style="margin-left: 210px;"><strong> Stock Return Report</strong></p>--}}
    @php
        $totalamount = 0;
    @endphp
    <div style="width: 100%;">
{{--        <div style="width: 50%;float: left;">--}}
{{--            <p>Supplier/Department: {{ (isset($orders[0])) ? $orders[0]->fldsuppname : '' }}</p>--}}
{{--        </div>--}}
        <div style="width: 50%;float: left;">
            <p>Datetime: {{ \Carbon\Carbon::now() }}
{{--                {{ (isset($orders[0])) ? $orders[0]->fldorddate : '' }}--}}
            </p>
        </div>
        <div style="width: 50%;float: left;">
            <p>Reference No: {{ (isset($references[0])) ? $references[0]->fldnewreference : '' }}
            </p>
        </div>
    </div>
    <table class="table content-body">
        <thead>
        <tr>
            <th>SN</th>
            <td>Location</td>
            <td>Reference</td>
            <td>Supplier</td>
            <td>Particulars</td>
            <td>Batch</td>
            <td>Expiry</td>
            <td>Qty</td>
            <td>Cost</td>
            <td>Amount</td>
        </tr>
        </thead>
        <tbody>
        @if($references)
            @foreach($references as $reference)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $reference->fldcomp }}</td>
                    <td>{{ $reference->fldreference }}</td>
                    <td>{{ $reference->fldsuppname }}</td>
                    <td>{{ $reference->fldstockid }}</td>
                    <td>{{ (($reference->entry->fldbatch) ? $reference->entry->fldbatch : '' ) }}</td>
                    <td>{{ (($reference->entry->fldexpiry) ? $reference->entry->fldexpiry : '' ) }}</td>
                    <td>{{ $reference->fldqty }}</td>
                    <td>{{ ($reference->fldcost) ? 'Rs.'.$reference->fldcost :'Rs.0' }}</td>
                    @php
                    $amount = ($reference->fldqty) * ($reference->fldcost);
                    @endphp
                    <td>{{ $amount ? 'Rs.'.$amount : 'Rs.0' }}</td>
                </tr>
            @endforeach
        @endif
{{--        <tr>--}}
{{--            <td>&nbsp;</td>--}}
{{--            <td>&nbsp;</td>--}}
{{--            <td>&nbsp;</td>--}}
{{--            <td>&nbsp;</td>--}}
{{--            <td>&nbsp;</td>--}}
{{--            <td>&nbsp;</td>--}}
{{--            <td>{{ $totalamount }}</td>--}}
{{--            <td>&nbsp;</td>--}}
{{--        </tr>--}}
        </tbody>
    </table>
{{--    <div style="width: 100%;">--}}
{{--        <div style="width: 50%;float: left;">--}}
{{--            <p>IN WORDS: {{ \App\Utils\Helpers::numberToNepaliWords($totalamount) }}</p>--}}
{{--        </div>--}}
{{--        <div style="width: 50%;float: left;">--}}
{{--            --}}{{--            <p>TOTATAMT: {{ $totalamount }}</p>--}}
{{--        </div>--}}
{{--    </div>--}}
@endsection
