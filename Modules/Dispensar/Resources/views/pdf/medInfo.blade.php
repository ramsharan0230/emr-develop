@extends('inpatient::pdf.layout.main')

@section('title')
DISPERNSER
@endsection

@section('report_type')
{{ $reporttype }}
@endsection

@section('content')
    @if($type == 'Review')
        <table class="table content-body">
            <thead>
                <tr>
                    <th>Medicine</th>
                    <th>Observation</th>
                    <th>Recommendation</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tableData as $record)
                    <tr>
                        <td>{{ $record->fldtime }}</td>
                        <td>{{ $record->flditemname }}</td>
                        <td>{{ $record->fldbatch }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <ul>
            <li>{{ $data->flditem }}</li>
            <li>{{ $data->fldroute }} X {{ $data->flddose }} X {{ $data->fldfreq }} X {{ $data->flddays }}</li>
        </ul>

        <table class="table content-body">
            <thead>
                <tr>
                    <th>Drug Information</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tableData as $record)
                    <tr>
                        <td>{{ $record->fldmedinfo }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
