@extends('inpatient::pdf.layout.main')

@section('title')
IPD Input Food
@endsection

@section('title')
INPUT FOOD
@endsection

@section('content')
    <style>
        .content-body {
            border-collapse: collapse;
        }
        .content-body td, .content-body th{
            border: 1px solid #ddd;
        }
        .content-body {
            font-size: 12px;
        }
    </style>
    <table class="table content-body">
    <thead>
        <tr>
            <th>Time</th>
            <th>Particulars</th>
            <th>Input (gm)</th>
            <th>Fluid (gm)</th>
            <th>Energy (kCal)</th>
            <th>Protein (gm)</th>
            <th>Carbohydrate (gm)</th>
            <th>Lipid (gm)</th>
            <th>Minerals (gm)</th>
            <th>Fiber (gm)</th>
            <th>Calcium (mg)</th>
            <th>Phosphorus (mg)</th>
            <th>Iron (mg)</th>
            <th>Carotene (mcg)</th>
            <th>Thiamine (mg)</th>
            <th>Riboflavin (mg)</th>
            <th>Niacin (mg) </th>
            <th>Pyridoxine (mg)</th>
            <th>Free Folic (mcg)</th>
            <th>Total Folic (mcg)</th>
            <th>Vitamin C (mg)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($foods as $key => $dates)
            <tr>
                <td colspan="21" style="text-align: center;"><strong>{{ $key }}</strong></td>
            </tr>
            @foreach($dates as $data)
            <tr>
                <td>{{ $data->time }}</td>
                <td>{{ $data->flditem }}</td>
                <td>{{ $data->fldreportquanti }}</td>
                <td>{{ $data->fldfluid }}</td>
                <td>{{ $data->fldenergy }}</td>
                <td>{{ $data->fldprotein }}</td>
                <td>{{ $data->fldsugar }}</td>
                <td>{{ $data->fldlipid }}</td>
                <td>{{ $data->fldmineral }}</td>
                <td>{{ $data->fldfibre }}</td>
                <td>{{ $data->fldcalcium }}</td>
                <td>{{ $data->fldphosphorous }}</td>
                <td>{{ $data->fldiron }}</td>
                <td>{{ $data->fldcarotene }}</td>
                <td>{{ $data->fldthiamine }}</td>
                <td>{{ $data->fldriboflavin }}</td>
                <td>{{ $data->fldniacin }}</td>
                <td>{{ $data->fldpyridoxine }}</td>
                <td>{{ $data->fldfreefolic }}</td>
                <td>{{ $data->fldtotalfolic }}</td>
                <td>{{ $data->fldvitaminc }}</td>
            </tr>
            @endforeach
        @endforeach
    </tbody>
</table>
@endsection
