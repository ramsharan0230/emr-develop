@if($notes)
    <table class="table">
    <thead>
    <tr>
        <th>Time</th>
        <th>Category</th>
        <th>Note</th>
        <th>Impression</th>
    </tr>
    </thead>
    <tbody>

        @foreach($notes as $key => $dates)
            <tr>
                <td colspan="4" style="text-align: center;"><strong>{{ $key }}</strong></td>
            </tr>
            @foreach($dates as $data)
                <tr>
                    <td>{{ $data->time }}</td>
                    <td>{{ $data->flditem }}</td>
                    <td>{!! $data->flddetail !!}</td>
                    <td>{{ $data->fldreportquali }}</td>
                </tr>
            @endforeach
        @endforeach

    </tbody>
</table>
@endif
