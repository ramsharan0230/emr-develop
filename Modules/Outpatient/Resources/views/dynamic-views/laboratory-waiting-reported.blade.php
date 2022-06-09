@if(count($reportedData))
    @foreach($reportedData as $bill)
        <tr>
            <td>
                {{ $bill->fldsampletype }}
            </td>
            <td>
                {{ $bill->fldmethod }}
            </td>
            <td>
                <i @if($bill->fldabnormal == 0 ) style="color:green" @elseif($bill->fldabnormal == 1) style="color:red" @endif class="fas fa-square"></i>
            </td>
            <td>
                @if($bill->subTest)
                    @foreach($bill->subTest as $sub)
                        {{ $sub->fldsubtest.': '.$sub->fldreport }}
                    @endforeach
                @else
                    {{ $bill->fldreportquanti }}
                @endif
            </td>
            <td>
                {{ $bill->fldstatus }}
            </td>
            <td>
                {{ $bill->fldtime_sample }}
            </td>
            <td>
                {{ $bill->fldtime_report }}
            </td>
        </tr>
    @endforeach
@endif
