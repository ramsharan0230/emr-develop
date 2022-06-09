@extends('inpatient::pdf.layout.main')

@section('title')
BLADDER IRRIGATION
@endsection

@section('report_type')
    BLADDER IRRIGATION
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

    @php
        $date = '';
        $time ='';
        $balance = '';
    @endphp

    <table class="table content-body">
    <thead>
        <tr>
            <th>Date</th>
            <th>Item Name</th>
            <th>Time</th>
            <th>Fluid In (ML)</th>
            <th>Time</th>
            <th>Fluid Out(ML)</th>
            <th>Balance</th>
            <th>Output</th>
        </tr>
    </thead>
    <tbody>
        @forelse($intakes as $data)
            <tr>
                @php
                $date = \Carbon\Carbon::parse($data->fldtime)->format('Y-m-d');
                $time = \Carbon\Carbon::parse($data->fldtime)->format('H:i:s');
                $balance = $data->examgeneral ? ($data->examgeneral->fldreportquanti - $data->fldvalue) :'';
                @endphp
                <td align="center">{{ $data->fldtime ? \App\Utils\Helpers::dateEngToNepdash($date)->full_date  : ''}}</td>
                <td align="center">{{ $data->getName ? $data->getName->flditem   : ''}}</td>
                <td align="center">{{ $time }}</td>
                <td align="center">{{ $data->fldvalue }}</td>
                <td align="center">{{ $time }}</td>
                <td align="center">{{ $data->examgeneral ? $data->examgeneral->fldreportquanti :''  }}</td>
                <td align="center">{{ $balance }}</td>
                <td align="center">{{ $data->duration }}</td>
            </tr>
        @empty
            <tr>
                <td>No data available</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection
