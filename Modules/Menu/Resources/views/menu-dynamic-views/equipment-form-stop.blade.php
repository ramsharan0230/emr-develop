@php
    $count = 1;
@endphp
@if(count($equipmentCleared))
    @foreach($equipmentCleared as $con)
        <tr>
            <td>{{ $con->flditem }}</td>
            <td>{{ $con->fldfirsttime }}</td>
            <td>{{ $con->fldsecondtime }}</td>
        </tr>
    @endforeach
@endif
