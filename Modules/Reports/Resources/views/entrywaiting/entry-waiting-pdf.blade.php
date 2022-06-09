@extends('inpatient::pdf.layout.main')

@section('title')
MEDICAL REPORT
@endsection

@section('content')
    <p style="margin-left: 210px; text-align: center"><strong>ENTRY WAITING REPORT</strong></p>
    <div style="width: 100%;">
        <div style="width: 50%;float: left;">
            <p><b>Type: {{ ($type == 0) ? "Not Saved" : "Not Billed" }}</b> </p>
            <p><b>Comp: {{ $comp }}</b> </p>
            <p><b>User: {{ $user }}</b> </p>
        </div>
    </div>
    <table class="table content-body">
        <thead>
            <tr>
                <th>SN.</th>
                <td>EncID</td>
                <td>Category</td>
                <td>Particulars</td>
                <td>Rate</td>
                <td>Qty</td>
                <td>User</td>
                <td>Dept</td>
                <td>DateTime</td>
            </tr>
        </thead>
        <tbody>
            {!! $html !!}
        </tbody>
    </table>
@endsection
