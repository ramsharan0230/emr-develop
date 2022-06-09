@if(count($patBillingDone))
    @foreach($patBillingDone as $bill)
        <tr>
            <td>
                {{ $bill->fldordtime }}
            </td>
            <td>
                {{ $bill->flditemname }}
            </td>
            <td>
                {{ $bill->fldstatus }}
            </td>
            <td>
                {{ $bill->fldtarget }}
            </td>
        </tr>
    @endforeach
@endif
