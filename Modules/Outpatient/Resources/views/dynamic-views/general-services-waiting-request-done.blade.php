@if(count($patBillingDone))
    @foreach($patBillingDone as $bill)
        <tr>
            <td>
                {{ $loop->iteration }}
            </td>
            <td>
                {{ $bill->fldordtime }}
            </td>
            <td>
                {{ $bill->flditemname }}
            </td>
            <td>
                {{ $bill->fldrefer }}
            </td>
        </tr>
    @endforeach
@endif
