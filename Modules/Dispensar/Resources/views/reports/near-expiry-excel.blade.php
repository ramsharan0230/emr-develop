@php $grandtotal = 0; @endphp
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
            <th colspan="8"><b>Medicine Near Expiry Report</b></th>
        </tr>
        <tr><th></th></tr>
        <tr>
            <th></th>
            <th>Stock Id</th>
            <th>Supplier</th>
            <th>Purchase Number</th>
            <th>Batch</th>
            <th>Expiry</th>
            <th>Quantity</th>
            <th>Rate</th>
            <th>Total</th>
            <th>Category</th>
        </tr>
    </thead>
    <tbody>
        @if ($medicines)
            @foreach ($medicines as $medicine)
                @php
                    $total = $medicine->fldqty*$medicine->fldsellpr;
                    $grandtotal += $total;
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $medicine->fldstockid }}</td>
                    @if(isset($medicine->hasTransfer))
                    @php $suppliername = Helpers::getSuppName($medicine->hasTransfer->fldoldstockno);
                    @endphp
                    @else
                    @php $suppliername = Helpers::getSuppName($medicine->fldstockno);
                    @endphp
                    @endif
                    <td>{{ isset($suppliername) ? $suppliername->fldsuppname : '' }}</td>
                    <td>{{ isset($suppliername) ? $suppliername->fldreference : '' }}</td>
                    <td>{{ $medicine->fldbatch }}</td>
                    <td>{{ explode(' ', $medicine->fldexpiry)[0] }}</td>
                    <td>{{ $medicine->fldqty }}</td>
                    <td>{{ $medicine->fldsellpr }}</td>
                    <td>{{ $total }}</td>
                    <td>{{ $medicine->fldcategory }}</td>
                </tr>
            @endforeach
        @endif
        <tr>
            <td colspan="6">Total</td>
            <td colspan="2">{{ $grandtotal }}</td>
        </tr>
    </tbody>
</table>
