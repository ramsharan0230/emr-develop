@extends('inpatient::pdf.layout.main')

@section('title', 'BILL STATUS')

@section('content')
    <style>

        h2 {
            display:flex;
            justify-content: center;
        }
        .header {
            margin-bottom: 20px;
        }
        .header tbody tr td:nth-child(2) {
            text-align: right;
        }
        #alignleft {
            text-align: left;
        }
    </style>
     @include('frontend.common.account-header')
    <h2>Bill Status</h2>
    <table style="width: 100%;" class="header">
        <tbody>
        <tr>
            <td>
                From Date: 2021-12-6 (2078-08-20)
            </td>
            <td>
                Category: %
            </td>
        </tr>
        <tr>
            <td>
                To Date: 2021-12-8   (2078-08-22)
            </td>
            <td>
                Comp: %
            </td>
        </tr>
        </tbody>
    </table>
    <table style="width: 100%;"  class="content-body">
        <thead>
            <tr>
                <th></th>
                <th>Encounter</th>
                <th>Patient Name</th>
                <th>Particulars</th>
                <th>Rate</th>
                <th>Qty</th>
                <th>Tax %</th>
                <th>Disc %</th>
                <th>Total</th>
                <th>Entry Date</th>
                <th>Invoice</th>
                <th>Status</th>

            </tr>
        </thead>
        <tbody>
            {!! $html !!}
        </tbody>
    </table>
@endsection
