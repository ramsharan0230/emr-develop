@if(count($patBillingCancelled))
    @foreach($patBillingCancelled as $bill)
        <tr>
            <td>
                <input type="checkbox" name="radiology-request-check[]" value="{{ $bill->fldid }}">
            </td>
            <td>
                <input type="hidden" name="fldid-request[]" value="{{ $bill->fldid }}">
                {{ $bill->fldordtime }}
            </td>
            <td>
                {{ $bill->flditemname }}
            </td>
            <td>
                <input type="hidden" name="status-request[]" value="{{ $bill->fldstatus }}">
                {{ $bill->fldstatus }}
            </td>
            <td>
                <textarea name="commentRadio" class="commentRadio" cols="20" rows="3" onblur="insertUpdateRequestRadio.comment({{ $bill->fldid }});" id="comment-fldid-{{ $bill->fldid }}">{{ $bill->fldreason }}</textarea>
            </td>
            <td>
                {{ $bill->fldtarget }}
            </td>
            <td>
                <a href="javascript:;" onclick="insertUpdateRequestRadio.deleteRequestedData('{{ $bill->fldid }}')">
                    <i class="fa fa-trash text-danger"></i>
                </a>
            </td>
        </tr>
    @endforeach
@endif
