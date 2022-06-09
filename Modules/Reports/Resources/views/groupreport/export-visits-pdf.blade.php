@extends('inpatient::pdf.layout.main')

@section('title', 'VISIT REPORT')

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
                <p>Group: {{ ($itemRadio == "select_item") ? $selectedItem : "%"}}</p>
            </td>
        </tbody>
    </table>
    <table style="width: 100%;"  class="content-body">
        <thead>
            <tr>
                <th>EncId</th>
                <th>Name</th>
                <th>Gender</th>
                <th>Address</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($datas as $data)
                <tr>
                    <td>{{ $data->fldencounterval }}</td>
                    <td>{{(isset($data->encounter->patientInfo)) ? $data->encounter->patientInfo->getFldrankfullnameAttribute() : ""}}</td>
                    <td>{{(isset($data->encounter->patientInfo)) ? $data->encounter->patientInfo->fldptsex : ""}}</td>
                    <td>{{(isset($data->encounter->patientInfo)) ? $data->encounter->patientInfo->getFullAddress() : ""}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
