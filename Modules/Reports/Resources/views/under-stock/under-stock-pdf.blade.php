@extends('inpatient::pdf.layout.main')

@section('title')
    Under Stock Report
@endsection
@include('pdf-header-footer.hospital-header')
<h4 style="text-align: center;margin-top:2px;">Under Stock Report</h4>
@section('content')
{{--    <p align="center" style="margin-left: 210px;"><strong> Under Stock Report</strong></p>--}}
    @php
        $sum = 0;
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
            <p>Transfer No: {{ (isset($references[0])) ? $references[0]->fldreference : '' }}
            </p>
        </div>
    </div>
    <table class="table content-body">
        <thead>
        <tr>
            <th>Particulars</th>
            <th>Manufacturer</th>
            <th>Standard</th>
            <th>MinQty</th>
            <th>CurrentQty</th>
            <th>Comment</th>
            {{--                                        <th>Category</th>--}}
        </tr>
        </thead>
        <tbody>
        @if($html)
         {!! $html !!}
        @endif
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
