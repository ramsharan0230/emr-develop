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
            {{--<td>
                <a href="javascript:;" onclick="insertUpdateRequestLab.deleteRequestedData('{{ $bill->fldid }}')">
                    <i class="fa fa-trash text-danger"></i>
                </a>
            </td>--}}
        </tr>
    @endforeach
@endif
