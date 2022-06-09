
<table>
    <thead>
    <tr><th></th></tr>
    <tr>
        @for($i=1;$i<4;$i++)
            <th></th>
        @endfor
        <th colspan="8"><b>{{ isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'' }}</b></th>
    </tr>
    <tr>
        @for($i=1;$i<4;$i++)
            <th></th>
        @endfor
        <th colspan="8"><b>{{ isset(Options::get('siteconfig')['system_slogan'])?Options::get('siteconfig')['system_slogan']:'' }}</b></th>
    </tr>
    <tr>
        @for($i=1;$i<4;$i++)
            <th></th>
        @endfor
        <th colspan="8"><b>{{ isset(Options::get('siteconfig')['system_address'])?Options::get('siteconfig')['system_address']:'' }}</b></th>
    </tr>
    <tr>
        @for($i=1;$i<4;$i++)
            <th></th>
        @endfor
        <th colspan="8"><b>Contact No: {{ Options::get('system_telephone_no') ? Options::get('system_telephone_no'):'' }}</b></th>
    </tr>

    <tr>
        @for($i=1;$i<4;$i++)
            <th></th>
        @endfor
        <th colspan="8"><b>Purchase Entry Supply Report</b></th>
    </tr>
    <tr><th></th></tr>
    <tr>
        @for($i=1;$i<6;$i++)
            <th></th>
        @endfor
        <th colspan="2"><b>From date:</b></th>
        <th colspan="2">{{ $from_date }}</th>
    </tr>
    <tr>
        @for($i=1;$i<6;$i++)
            <th></th>
        @endfor
        <th colspan="2"><b>To date:</b></th>
        <th colspan="2">{{ $to_date }}</th>
    </tr>
    <tr>
        @for($i=1;$i<6;$i++)
            <th></th>
        @endfor
        <th colspan="2"><b>Datetime:</b></th>
        <th colspan="2"></th>
    </tr>
    <tr><th></th></tr>
    <tr>
        <th>SNo.</th>
        <th>Supplier Name</th>
        <th>Bill No.</th>
        <th>PAN/ VAT No.</th>
        <th>Taxable Amount</th>
        <th>VAT</th>
        <th>Total Amount</th>
        <th>General Voucher</th>
        <th>Remarks</th>
      
    </tr>
    </thead>
    <tbody>
    @if($entries)
        @php
            $i = 1;
        @endphp
        @foreach ($entries as $entry)
            @foreach ($entry->purchase as $purchase)
                <tr>
                    <td>{{ $i }}</td>
                    <td>{{ $purchase->fldsuppname }}</td>
                    <td>{{ $purchase->fldbillno ?? null }}</td>
                    <td></td>
                    <td>{{ $entry->sumdebit }}</td>
                    <td>{{ $entry->sumcredit }}</td>
                    <td>{{ $entry->sumtax }}</td>
                    <td>{{ $entry->sumdis }}</td>
                    <td>{{ $purchase->fldpurdate ?? null }}</td>
                    <td>{{ $purchase->fldreference ?? null }}</td>
                    <td>{{ $purchase->fldtotalvat ?? null }}</td>
                   
                </tr>
                @php
                    ++$i;
                @endphp
            @endforeach
        @endforeach
    @endif
    </tbody>
</table>
