@if(count($dosedata))
    @foreach($dosedata as $dose)
        <tr>
            <td>
                {{ $loop->iteration }}
            </td>
            <td>
                <a href="javascript:;" onclick="pharmacyPopup.changeDate('{{ $dose->fldid }}')">
                    {{ $dose->fldstarttime }}
                </a>
            </td>
            <td>
                {{ $dose->fldroute }}
            </td>
            <td>
                {{ $dose->flditem }}
            </td>
            <td>
                <a href="javascript:;" onclick="pharmacyPopup.changeDose('{{ $dose->fldid }}')">
                    {{ $dose->flddose }}
                </a>
            </td>
            <td>
                <a href="javascript:;" onclick="pharmacyPopup.changeFrequency('{{ $dose->fldid }}')">
                    {{ $dose->fldfreq }}
                </a>
            </td>
            <td>
                <a href="javascript:;" onclick="pharmacyPopup.changeDay('{{ $dose->fldid }}')">
                    {{ $dose->flddays }}
                </a>
            </td>
            <td>
                <a href="javascript:;" onclick="pharmacyPopup.changeQuantity('{{ $dose->fldid }}')">
                    {{ $dose->fldqtydisp }}
                </a>
            </td>
            <td>
                <textarea name="commentPharmacy" class="commentPharmacy" cols="20" rows="2" onblur="pharmacyPopup.comment({{ $dose->fldid }});" id="comment-fldid-{{ $dose->fldid }}">{{ $dose->fldcomment }}</textarea>
            </td>
            <td>
                <a href="javascript:void(0)" onclick="pharmacyPopup.deleteNewOrder('{{ $dose->fldid }}')"><i class="fa fa-trash text-danger"></i></a>

            </td>
        </tr>
    @endforeach
@endif
