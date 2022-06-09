@extends('inpatient::pdf.layout.main')

@section('title')
Demand order
@endsection
@include('pdf-header-footer.hospital-header')
<h4 style="text-align: center;margin-top:2px;">Demand Order Report</h4>
@section('content')
{{--    <p align="center" style="margin-left: 210px;"><strong>Demand Order Report</strong></p>--}}
	@php
	    $totalamount = 0;
    @endphp
{{--    {{ dd($demands) }}--}}
    <div style="width: 100%;">
{{--        <div style="width: 50%;float: left;">--}}
{{--            <p>Supplier/Department: {{ (isset($demands[0])) ? $demands[0]->fldsuppname : '' }}</p>--}}
{{--        </div>--}}
        <div style="width: 50%;float: left;">
            <p>Datetime: {{ (isset($demands[0])) ? $demands[0]->fldtime_order : '' }}</p>
        </div>
    </div>
    <table class="table content-body">
        <thead>
            <tr>
                <th>SN</th>
            	<td>Datetime</td>
            	<td>Supplier/Department</td>
            	<td>Particular</td>
            	<td>Demand No</td>
            	<td>Quantity</td>
            	<td>Rate</td>
            	<td>Amount</td>
            	<td>User</td>
            </tr>
        </thead>
        <tbody>
           @if($demands)
               @foreach($demands as $datum)
                   @php
                       $totalamount += $datum->fldtotal;
                   @endphp
                   <tr data-fldid="{{ $datum->fldid }}">
                       <td>{{ $loop->iteration }}</td>
                       <td>{{ $datum->fldtime_order }}</td>
                       <td>{{ $datum->fldsuppname }}</td>
                       <td>{{ $datum->fldstockid }}</td>
                       <td>{{ $datum->fldquotationno }}</td>
                       <td>{{ $datum->fldquantity }}</td>
                       <td>{{ $datum->fldrate }}</td>
                       <td>{{ $datum->fldtotal }}</td>
                       <td>{{ $datum->flduserid_order }}</td>
                   </tr>
               @endforeach
               @endif
{{--            <tr>--}}
{{--            	<td>&nbsp;</td>--}}
{{--            	<td>&nbsp;</td>--}}
{{--            	<td>&nbsp;</td>--}}
{{--            	<td>&nbsp;</td>--}}
{{--            	<td>&nbsp;</td>--}}
{{--            	<td>&nbsp;</td>--}}
{{--            	<td>{{ $totalamount }}</td>--}}
{{--            	<td>&nbsp;</td>--}}
{{--            </tr>--}}
        </tbody>
    </table>
{{--    <div style="width: 100%;">--}}
{{--        <div style="width: 50%;float: left;">--}}
{{--            <p>IN WORDS: {{ \App\Utils\Helpers::numberToNepaliWords($totalamount) }}</p>--}}
{{--        </div>--}}
{{--        <div style="width: 50%;float: left;">--}}
{{--            <p>TOTATAMT: {{ $totalamount }}</p>--}}
{{--        </div>--}}
{{--    </div>--}}
@endsection
