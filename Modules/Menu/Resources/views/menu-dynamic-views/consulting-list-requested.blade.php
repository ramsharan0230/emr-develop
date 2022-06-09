@if(count($consult_list))
    @foreach($consult_list as $con)
        <tr>
            <td>{{ $con->fldconsulttime }}</td>
            <td>{{ $con->fldconsultname }}</td>
            <td>{{ $con->flduserid ? $con->flduserid : '' }}</td>
            <td>{{ $con->fldcomment }}</td>
            <td><a href="javascript:;" onclick="consultation.deleteConsultation('{{ $con->fldid }}')" class="text-danger" style="font-size: 18px;"><i class="ri-close-fill"></i></td>
        </tr>
    @endforeach
@endif
