@extends('inpatient::pdf.layout.main')

@section('title')
Discharge Bills
@endsection

@section('content')

    <style>
        .text-left{
            text-align: left !important;
        }

        p{
            margin: 0;
        }
    </style>
    <p style="text-align: center"><strong>Total Indoor Treatment Charges (Discharge Bills)</strong></p>
    <div style="width: 100%;">
        <div style="width: 50%;float: left;">
            <p><b>Date: {{ \Carbon\Carbon::parse($finalfrom)->format('Y-m-d') }} to {{ \Carbon\Carbon::parse($finalto)->format('Y-m-d') }}</b> </p>
        </div>
        <div style="width: 50%;float: right;text-align: right;">
            <p><b>Printed Date: {{ \Carbon\Carbon::now()->format('Y-m-d') }}</b> </p>
            <p><b>Printed By: {{Helpers::getCurrentUserName()}}</b> </p>
        </div>
    </div>
        <table class="table content-body">
            <thead>
            <tr>
                <th>SN.</th>
                <th>Encounter ID</th>
                <th class="text-left">Patient Name</th>
                <th class="text-left">Deposit Receipt No.</th>
                <th class="text-left">Invoice No.</th>
                <th class="text-left">Deposit Refund No.</th>
                <th>Deposit Amount</th>
                <th>Total Net Bill Amount</th>
                <th>Amount Received After Deposit Adjustment</th>
                <th>Discount</th>
                <th>Amount Refund After Deducting Deposit</th>
                <th>Remaining Refund</th>
                <th>Admitted Date</th>
                <th>Discharge Date</th>
                <th>Remarks</th>
            </tr>
            </thead>
            <tbody>
            {!! $html_pdf !!}
            </tbody>
        </table>
@endsection
