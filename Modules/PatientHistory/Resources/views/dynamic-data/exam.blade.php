@if($exams)
    <table class="table table-bordered">
    <thead>
    <tr>
        <th>Time</th>
        <th>Department</th>
        <th>Category</th>
        <th>Examination</th>
        <th>Observation</th>
        <th>Comment</th>
    </tr>
    </thead>
    <tbody>

        @foreach($exams as $key => $dates)
            <tr>
                <td colspan="6" style="text-align: center;"><strong>{{ $key }}</strong></td>
            </tr>
            @foreach($dates as $data)
                <tr>
                    <td>{{ $data->time }}</td>
                    <td>Empty</td>
                    <td>{{ $data->fldinput }}</td>
                    <td>{{ $data->fldhead }}</td>
                    <td>
                        @if($data->fldoption === 'Clinical Scale')
                            {{ $data->fldrepquanti }}
                        @elseif($data->fldoption === 'Left and Right')
                            @php
                                $d = json_decode($data->fldrepquali);
                            @endphp
                            <table class="table">
                                <tr>
                                    <th>Left</th>
                                    <th>Right</th>
                                </tr>
                                <tr>
                                    <td>{{ isset($d->LEFT) ? $d->LEFT : '-' }}</td>
                                    <td>{{ isset($d->RIGHT) ? $d->RIGHT : '-' }}</td>
                                </tr>

                            </table>
                        @else
                            {{ $data->fldrepquali }}
                        @endif
                    </td>
                    <td>&nbsp;</td>
                </tr>
            @endforeach
        @endforeach

    </tbody>
</table>
@endif
