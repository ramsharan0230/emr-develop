@if(count($patBillingPunched))
    @foreach($patBillingPunched as $bill)
        <tr>
            <td>
                <input type="checkbox" name="laboratory-request-check[]" value="{{ $bill->fldid }}">
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
                <a href="javascript:;" onclick="insertUpdateRequestLab.deleteRequestedData('{{ $bill->fldid }}')">
                    <i class="fa fa-trash text-danger"></i>
                </a>
            </td>
        </tr>
    @endforeach
@endif
