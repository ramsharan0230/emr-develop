@if($radiologies)
    <table class="table">
        <thead>
        <tr>
            <th>Date</th>
            <th>Examination</th>
            <th>Evaluation</th>
            <th>Observation</th>
            <th>Comment</th>
        </tr>
        </thead>
        <tbody>

        @foreach($radiologies as $data)
            <tr>
                <td>{{ $data->fldtime_report }}</td>
                <td>{{ $data->fldtestid }}</td>
                <td>{{ $data->fldtest_type }}</td>
                <td>{{ $data->fldmethod }}</td>
                <td>&nbsp;</td>
            </tr>
        @endforeach

        </tbody>
    </table>
@endif
