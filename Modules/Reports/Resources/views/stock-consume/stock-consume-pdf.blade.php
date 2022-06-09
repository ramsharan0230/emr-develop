@extends('inpatient::pdf.layout.main')

@section('title')
    Stock Consume Report
@endsection
@include('pdf-header-footer.hospital-header')
<h4 style="text-align: center;margin-top:2px;">Stock Consume Report</h4>
@section('content')
{{--    <p align="center" style="margin-left: 210px;"><strong> Stock Consume Report</strong></p>--}}
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
            <p>Reference No: {{ (isset($references[0])) ? $references[0]->fldreference : '' }}
            </p>
        </div>
    </div>
    <table class="table content-body">
        <thead>
        <tr>
            <th>SN</th>
            <td>ID</td>
            <td>Date</td>
            <td>Target</td>
            <td>Category</td>
            <td>Particulars</td>
            <td>Batch</td>
            <td>Expiry</td>
            <td>Qty</td>
        </tr>
        </thead>
        <tbody>
        @if($references)
            @foreach($references as $reference)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $reference->fldid }}</td>
                    <td>{{ $reference->fldbulktime }}</td>
                    <td>{{ $reference->fldtarget }}</td>
                    <td>{{ $reference->fldcategory }}</td>
                    <td>{{ $reference->fldstockid }}</td>
                    <td>{{ (($reference->stock->fldbatch) ? $reference->stock->fldbatch : '' ) }}</td>
                    <td>{{ (($reference->stock->fldexpiry) ? $reference->stock->fldexpiry : '' ) }}</td>
                    <td>{{ $reference->fldqtydisp }}</td>
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
