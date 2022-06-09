@extends('inpatient::pdf.layout.main')

@section('title', 'PATIENT REPORT')

@section('content')
    @if(isset($certificate))
        <h4 style="text-align: center;">{{ucfirst($certificate)}} REPORT</h4>
    @endif
    <table style="width: 100%;">
        <tbody>
        <tr>
            <td style="width: 200px;">
                <p>From Date: {{ $finalfrom}}</p>
                <p>To Date: {{ $finalto }}</p>
                <p>Billing Mode: {{ $billingmode }}</p>
                <p>Comp: {{ $comp }}</p>
            </td>
        </tbody>
    </table>
    <table style="width: 100%;"  class="content-body">
        <thead>
            <tr>
                <th>Particular</th>
                <th>Disc</th>
                <th>Tax</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($datas as $data)
                <tr>
                    <td colspan="4" style="text-align: center;"><b>{{(isset($data->encounter->patientInfo)) ? $data->encounter->patientInfo->getFldrankfullnameAttribute() : ""}} ( {{ $data->fldencounterval }} ) </b></td>
                </tr>
                <tr>
                    <td><b>***</b></td>
                    <td><b>Rs. {{ $data->dsc }}</b></td>
                    <td><b>Rs. {{ $data->tax }}</b></td>
                    <td><b>Rs. {{ $data->tot }}</b></td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
