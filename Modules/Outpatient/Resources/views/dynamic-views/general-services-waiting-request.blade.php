@if(count($patBillingPunched))
    @foreach($patBillingPunched as $bill)
        <tr>
            <td>
                <input type="checkbox" name="services-request-check[]" value="{{ $bill->fldid }}">
            </td>
            <td>
                <input type="hidden" name="fldid-request[]" value="{{ $bill->fldid }}">
                {{ $bill->fldordtime }}
            </td>
            <td>
                {{ $bill->flditemname }}
            </td>
            <td>
                <input type="hidden" name="flditemno-request[]" value="{{ $bill->flditemno }}">
                <input type="number" class="service_quantity" name="service_quantity[]" min="1" value="{{ isset($bill->flditemqty) ? $bill->flditemqty : 1 }}">
            </td>
            <td class="flditemrate" data-rate="{{ $bill->flditemrate }}" data-currency="{{ $bill->fldcurrency }}">
                {{ $bill->fldcurrency }} {{ $bill->flditemrate }}
            </td>
            <td class="fldditemamt" data-amount="{{ $bill->flditemrate }}" data-currency="{{ $bill->fldcurrency }}">
                {{ $bill->fldcurrency }} {{ $bill->fldditemamt }}
            </td>
            <td>
                <input type="hidden" name="status-request[]" value="{{ $bill->fldstatus }}">
                {{ $bill->fldstatus }}
            </td>
            <td>
                <a href="javascript:;" onclick="insertUpdateRequestServices.deleteRequestedData('{{ $bill->fldid }}')">
                    <i class="fa fa-trash text-danger"></i>
                </a>
            </td>
        </tr>
    @endforeach
@endif
