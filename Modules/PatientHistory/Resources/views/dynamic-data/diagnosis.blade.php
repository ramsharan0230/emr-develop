@if($diagnosis_data)
    <table class="table">
        <tbody>

            @foreach($diagnosis_data as $key => $dates)
                <tr>
                    <td colspan="4" style="text-align: center;"><strong>{{ $key }}</strong></td>
                </tr>
                @foreach($dates as $data)
                    <tr>
                        <td>{{ $data->fldchild }}</td>
                    </tr>
                @endforeach
            @endforeach

        </tbody>
    </table>
@endif
