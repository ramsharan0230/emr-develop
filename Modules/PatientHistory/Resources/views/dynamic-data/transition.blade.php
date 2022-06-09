@if($transitions)
    <table class="table">
        <thead>
        <tr>
            <th>Item</th>
            <th>Second Report</th>
            <th>First Time</th>
            <th>First User</th>
            <th>Second Time</th>
            <th>Second User</th>
            <th>Duration</th>
        </tr>
        </thead>
        <tbody>

        @foreach($transitions as $data)
            <tr>
                <td>{{ $data->flditem }}</td>
                <td>{{ $data->fldsecondreport }}</td>
                <td>{{ $data->fldfirsttime }}</td>
                <td>{{ $data->fldfirstuserid }}</td>
                <td>{{ $data->fldsecondtime }}</td>
                <td>{{ $data->fldseconduserid }}</td>
                <td>{{ $data->duration }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif
