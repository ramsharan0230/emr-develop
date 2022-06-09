<table>
    <thead>
        <tr><th></th></tr>
        <tr>
            @for($i=1;$i<3;$i++)
            <th></th>
            @endfor
            <th colspan="8"><b>{{ isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'' }}</b></th>
        </tr>
        <tr>
            @for($i=1;$i<3;$i++)
            <th></th>
            @endfor
            <th colspan="8"><b>{{ isset(Options::get('siteconfig')['system_slogan'])?Options::get('siteconfig')['system_slogan']:'' }}</b></th>
        </tr>
        <tr><th></th></tr>
        <tr><th></th></tr>
    </thead>
</table>
<table style="width: 100%;border-collapse: collapse;border: 1px solid black;">
    {{-- <thead> --}}
        {{-- <tr>
            <td style="font-weight: 600;" colspan="13">Cash Refund Bill</td>
        </tr> --}}
        <tr>
            <td></td>
            <td style="border: 1px solid black;">DiscLabel</td>
            <td style="border: 1px solid black;">DiscMode</td>
            <td style="border: 1px solid black;">BillingMode</td>
            <td style="border: 1px solid black;">StartDate</td>
            {{-- <td style="border: 1px solid black;">DiscATM</td> --}}
            <td style="border: 1px solid black;">DiscATM/Year</td>
            <td style="border: 1px solid black;">CreditAmt</td>
            <td style="border: 1px solid black;">Created By</td>
            <td style="border: 1px solid black;">Updated By</td>
        </tr>
    {{-- </thead> --}}
    <tbody>
        @forelse($discountData as $dis)
            <tr >

                <td>{{ $loop->iteration }}</td>
                <td>{{ $dis->fldtype }}</td>
                <td>{{ $dis->fldmode }}</td>
                <td>{{ $dis->fldbillingmode }}</td>
                <td>{{ date('Y-m-d', strtotime($dis->fldyear)) }}</td>
                <td>{{ $dis->fldamount }}</td>
                <td>{{ $dis->fldcredit }}</td>
                {{-- <td></td> --}}
                <td>{{ $dis->flduserid }}</td>
                <td>{{ !is_null($dis->cogentUser) ? $dis->cogentUser->firstname : null }}</td>
            </tr>
        @empty

        @endforelse
    </tbody>
</table>
