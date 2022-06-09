@if($progress)
    <table class="table">
        <thead>
        <tr>
            <th>Time</th>
            <th>Problem</th>
            <th>On Examination</th>
            <th>Treatment</th>
            <th>I/O Assessment</th>
            <th>Impression</th>
            <th>Nurse Plan</th>
        </tr>
        </thead>
        <tbody>

        @foreach($progress as $key => $dates)
            <tr>
                <td colspan="7" style="text-align: center;"><strong>{{ $key }}</strong></td>
            </tr>
            @foreach($dates as $data)
                <tr>
                    <td>{{ $data->time }}</td>
                    <td>{!! $data->fldsubjective !!}</td>
                    <td>
                        @foreach($data->exams as $exam)
                            {{ $exam->fldhead }}: {{ $exam->fldrepquali }} <br>
                        @endforeach
                    </td>
                    <td>{!! $data->fldobjective   !!}</td>
                    <td>{!! $data->fldassess !!}</td>
                    <td>{!! $data->fldproblem !!}</td>
                    <td>{!! $data->fldplan !!}</td>
                </tr>
            @endforeach
        @endforeach

        </tbody>
    </table>
@endif
