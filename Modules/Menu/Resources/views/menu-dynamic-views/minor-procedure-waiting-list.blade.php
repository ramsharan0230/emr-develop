{{--minor-procedure-waiting-list--}}
@if(count($patGeneralWaitingCleared))
    @foreach($patGeneralWaitingCleared as $con)
        <tr>
            <td>{{ $con->fldtime }}</td>
            <td>{{ $con->flditem }}</td>
            <td><a href="javascript:;" onclick="minorProcedure.deleteminorProcedure('{{ $con->fldid }}')"><img src="{{ asset('images/cancel.png') }}" alt="Delete" style="width: 16px;"></a></td>
        </tr>
    @endforeach
@endif
