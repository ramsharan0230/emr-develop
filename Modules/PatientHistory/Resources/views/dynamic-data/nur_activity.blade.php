@if($nur_activity)
    <table class="table">
    <thead>
    <tr>
        <th>SNo</th>
        <th>Particulars</th>
        <th>Regimen</th>
        <th>Today</th>
    </tr>
    </thead>
    <tbody>

        @foreach($nur_activity as $key=> $data)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ $data->flditem }}</td>
                <td>{{ $data->flddose }} Grams</td>
                <td>&nbsp;</td>
            </tr>
        @endforeach

    </tbody>
</table>
@endif
