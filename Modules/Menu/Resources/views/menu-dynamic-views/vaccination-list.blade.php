@if(count($vaccDosing))
    @foreach($vaccDosing as $dosList)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $dosList->fldtime }}</td>
            <td>{{ $dosList->flditem }}</td>
            <td>{{ $dosList->fldtype }}</td>
            <td>{{ $dosList->fldvalue }}</td>
            <td>{{ $dosList->fldunit }}</td>
            <td><a href="javascript:;" class="btn btn-default" onclick="updateVaccineDosing({{$dosList->fldid}})"><img src="{{ asset('assets/images/edit.png') }}" alt="Update" style="width: 15px;"></a></td>
        </tr>
    @endforeach
@endif
