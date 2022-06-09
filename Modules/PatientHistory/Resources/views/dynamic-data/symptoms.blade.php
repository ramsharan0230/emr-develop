@if($symtoms)
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Time</th>
            <th>Symptom</th>
            <th>Description</th>
            <th>Comment</th>
        </tr>
        </thead>
        <tbody>

        @foreach($symtoms as $key => $dates)
            <tr>
                <td colspan="4" style="text-align: center;"><strong>{{ $key }}</strong></td>
            </tr>
            @foreach($dates as $data)
                <tr>
                    <td>{{ $data->time }}</td>
                    <td>{{ $data->flditem }}</td>
                    <td>{{ $data->fldreportquali }}</td>
                    <td>{{ $data->flddetail }}</td>
                </tr>
            @endforeach
        @endforeach

        </tbody>
    </table>
@endif
