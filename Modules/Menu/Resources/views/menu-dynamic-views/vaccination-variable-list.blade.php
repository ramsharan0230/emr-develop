@if(count($vaccineList))
    @foreach($vaccineList as $list)
        <tr>
            <td>{{ $list->flditem }}</td>
            <td><a href="javascript:;" onclick="VaccinationVariables.deleteVariable('{{ $list->fldid }}')"><img src="{{ asset('assets/images/cancel.png') }}" alt=""></a></td>
        </tr>
    @endforeach
@endif
