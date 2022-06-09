@extends('inpatient::pdf.layout.main')

@section('title', 'VISITS REPORT')

@section('content')
    <style>
        h2 {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        #alignleft {
            text-align: left;
        }
        #totaltable {
            margin-top: 20px;
        }

        .heading {
            margin-bottom: 20px;
        }

        .heading tbody tr td:nth-child(2) {
            text-align: right;
        }
            
        #totaltable td {
            text-align: right;
            height: 20px;
            width: 130px;
        }
        
    </style>
    <h2>Visits Report</h2>
    <table style="width: 100%;" class="heading">
        <tbody>
        <tr>
            <td>
                From Date: {{ $finalfrom}} {{ isset($finalfrom) ? "(" .\App\Utils\Helpers::dateToNepali($finalfrom) .")" :'' }}
            </td>
            <td id="alignright">
                Category: {{ $category }}
            </td>        
        </tr>
        <tr>
            <td>
                To Date: {{ $finalto }} {{ isset($finalto) ? "(" .\App\Utils\Helpers::dateToNepali($finalto) .")" :'' }}
            </td>
            <td id="alignright">
                Billing Mode: {{ $billingmode }}                
            </td>
            
        </tr>
        <tr>
            <td>
                Particulars: {{ ($itemRadio == "select_item") ? $selectedItem : "%"}}
            </td>
            <td id="alignright">
                Comp: {{ $comp }}
            </td>
        </tr>
        <!-- <tr>
            <td>
                <p>From Date: {{ $finalfrom}} {{ isset($finalfrom) ? "(" .\App\Utils\Helpers::dateToNepali($finalfrom) .")" :'' }}</p>
            </td>
            <td>
                <p>To Date: {{ $finalto }} {{ isset($finalto) ? "(" .\App\Utils\Helpers::dateToNepali($finalto) .")" :'' }}</p>
            </td>
            <td>
                <p>Category: {{ $category }}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p>Billing Mode: {{ $billingmode }}</p>
            </td>
            <td>
                <p>Comp: {{ $comp }}</p>
            </td>
            <td>
                <p>Particulars: {{ ($itemRadio == "select_item") ? $selectedItem : "%"}}</p>
            </td>
        </tr> -->
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
                @if(isset($data->encounter->patientInfo))
                <tr>
                    <td>{{ $data->fldencounterval }}</td>
                    <td>{{(isset($data->encounter->patientInfo)) ? $data->encounter->patientInfo->getFldrankfullnameAttribute() : ""}}</td>
                    <td>{{(isset($data->encounter->patientInfo)) ? $data->encounter->patientInfo->fldptsex : ""}}</td>
                    <td>{{(isset($data->encounter->patientInfo)) ? $data->encounter->patientInfo->getFullAddress() : ""}}</td>
                </tr>
                @endif
            @endforeach
        </tbody>
    </table>
@endsection
