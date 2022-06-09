@extends('inpatient::pdf.layout.main')

@section('title', 'GROUP REPORT')

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
                <th>Encounter</th>
                <th>Patient Name</th>
                <th>Particulars</th>
                <th>Rate</th>
                <th>Qty</th>
                <th>Disc</th>
                <th>Tax</th>
                <th>Total</th>
                <th>Entry Date</th>
                <th>Invoice</th>
                <th>Payable</th>
                <th>Referral</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($datas as $data)
                <tr>
                    <td>{{$data->fldencounterval}}</td>
                    <td>{{(isset($data->encounter->patientInfo)) ? $data->encounter->patientInfo->getFldrankfullnameAttribute() : ""}}</td>
                    <td>{{$data->flditemname}}</td>
                    <td>{{$data->flditemrate}}</td>
                    <td>{{$data->flditemqty}}</td>
                    <td>Rs. {{$data->flddiscamt}}</td>
                    <td>Rs. {{$data->fldtaxamt}}</td>
                    <td>Rs. {{$data->tot}}</td>
                    <td>{{$data->entrytime}}</td>
                    <td>{{$data->fldbillno}}</td>
                    <td>{{$data->fldpayto}}</td>
                    <td>{{$data->fldrefer}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
