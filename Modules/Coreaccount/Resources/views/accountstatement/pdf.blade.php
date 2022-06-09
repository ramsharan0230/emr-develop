@extends('inpatient::pdf.layout.main')

@section('title', 'Account Statement')

@section('content')
    @include('frontend.common.account-header')
    <style>
        @page {
            margin: 20px;
        }
    </style>

    <table style="width: 100%">
        <tr>
            <td><b>From Date:</b> {{ date('Y-m-d', strtotime($from_date))}} {{ isset($from_date) ? "(". \App\Utils\Helpers::dateEngToNepdash(\Carbon\Carbon::parse($from_date)->format('Y-m-d'))->full_date .")" : ''}} </td>
            <td style="text-align: right;"><b>Printed At:</b> {{ date('Y-m-d H:i:s') }}</td>
        </tr>
        <tr>
            <td><b>To Date:</b> {{ date('Y-m-d', strtotime($to_date)) }} {{ isset($to_date) ? "(" .\App\Utils\Helpers::dateEngToNepdash(\Carbon\Carbon::parse($to_date)->format('Y-m-d'))->full_date . ")":'' }} </td>
            <td style="text-align: right;"><b>Printed By:</b> {{ \App\Utils\Helpers::getNameByUsername(\Auth::guard('admin_frontend')->user()->flduserid) }}</td>
        </tr>
    </table>

    <div style="width: 100%; display: flex; justify-content: center;">
        <h3>Account Statement</h3>
    </div>
    
    <table style="width: 100%;"  class="content-body">
        <thead>
            <tr>
                <th>S/N</th>
{{--                <th>Branch</th>--}}
                <th>TranDateBS</th>
                <th>TranDateAD</th>
                <th>Description</th>
                <th>Voucher Code</th>
                <th>Voucher No</th>
                <th>Debit</th>
                <th>Credit</th>
                <th>Balance</th>
                <th>ChequeNo</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            {!! $html !!}
        </tbody>
    </table>
    
@endsection
