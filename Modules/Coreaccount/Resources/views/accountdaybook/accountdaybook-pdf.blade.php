@extends('inpatient::pdf.layout.main')

@section('title', 'Account Summary')

@section('content')

<main>
    @include('frontend.common.account-header')
    <table style="width: 100%">
        <tr>
            <td><b>From Date:</b> {{ isset($from_date) ? $from_date :'' }}
            {{ isset($from_date) ? "(" . \App\Utils\Helpers::dateNepToEng($from_date)->full_date .")" :'' }} </td>
            <td style="text-align: right;"><b>Printed At:</b> {{ date('Y-m-d H:i:s') }}</td>
        </tr>
        <tr>
            <td><b>To Date:</b> {{ isset($to_date) ? $to_date :'' }}
            {{ isset($to_date) ? "(" . \App\Utils\Helpers::dateNepToEng($to_date)->full_date .")" :'' }} </td>
            <td style="text-align: right;"><b>Printed At:</b> {{ \App\Utils\Helpers::getNameByUsername(\Auth::guard('admin_frontend')->user()->flduserid) }}</td>
        </tr>
    </table>

    <div style="width: 100%; display: flex; justify-content: center;">
        <h3>Account Day Book</h3>
    </div>

    <table style="width: 100%;"  class="content-body">
        <thead>
        <tr>
            <th class="text-center" >S/N</th>
            <th>TranDateBS</th>
            <th>TranDateAD</th>
            <th class="text-center" >Voucher No</th>
            <th class="text-center" >Voucher Type</th>
            <th class="text-center" >Voucher Date</th>
            <th class="text-center" >Amount</th>
            <th class="text-center" >User</th>
        </tr>
        </thead>
        <tbody>
        {!! $html !!}
        </tbody>
    </table>
    @php
        $signatures = Helpers::getSignature('bedoccupancy');
    @endphp
    @include('frontend.common.footer-signature-pdf')
</main>
@endsection

