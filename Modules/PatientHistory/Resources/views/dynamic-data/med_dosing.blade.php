@if($medDosing)
    <table class="table">
    <thead>
    <tr>
        <th>Time</th>
        <th>Medicine</th>
        <th>Regimen</th>
        <th>Dose</th>
    </tr>
    </thead>
    <tbody>

        @foreach($medDosing as $key => $dates)
            <tr>
                <td colspan="4" style="text-align: center;"><strong>{{ $key }}</strong></td>
            </tr>
            @foreach($dates as $data)
                <tr>
                    <td>{{ $data->time }}</td>
                    <td>{{ $data->flditem }}</td>
                    <td>{{ $data->fldroute }} X {{ $data->fldfreq }} X 1</td>
                    <td>{{ $data->fldvalue }} {{ $data->fldunit }}</td>
                </tr>
            @endforeach
        @endforeach

    </tbody>
</table>
@endif
