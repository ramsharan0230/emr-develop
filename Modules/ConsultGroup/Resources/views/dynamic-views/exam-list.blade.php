@if(count($triage))
    @foreach($triage as $t)
        <tr>
            <td>{{ $t->fldcategory }}</td>
            <td></td>
            <td>{{ $t->flddiagnounit }}</td>
            <td>{{ $t->flddiagnounit }}</td>
            <td>{{ $t->fldbaserate }}</td>
            <td><a href="javascript:;" onclick="triageExam.deleteexam('{{ $t->flid }}')"><i class="fa fa-trash text-danger"></i></a></td>
        </tr>
    @endforeach
@endif
