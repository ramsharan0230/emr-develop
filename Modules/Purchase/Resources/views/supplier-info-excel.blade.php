<table>
    <thead>
        <tr><th></th></tr>
        <tr>
            @for($i=1;$i<3;$i++)
            <th></th>
            @endfor
            <th colspan="5"><b>{{ isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'' }}</b></th>
        </tr>
        <tr>
            @for($i=1;$i<3;$i++)
            <th></th>
            @endfor
            <th colspan="5"><b>{{ isset(Options::get('siteconfig')['system_slogan'])?Options::get('siteconfig')['system_slogan']:'' }}</b></th>
        </tr>
        <tr>
            @for($i=1;$i<3;$i++)
            <th></th>
            @endfor
            <th colspan="5"><b>Supplier Information</b></th>
        </tr>
        <tr><th></th></tr>
        <tr><th></th></tr>
        <tr><th></th></tr>
        <tr>
            <th></th>
            <th>Supplier</th>
            <th>Address</th>
            <th>Status</th>
            <th>Paid</th>
            <th>To Pay</th>
            <th>NET</th>
        </tr>
    </thead>
    <tbody>
        @php $total_balance = 0; @endphp
        @foreach($get_supplier_info as $key=>$supplier_info)
            <tr>
                <td>{{ ++$key }}</td>
                <td>{{ $supplier_info->fldsuppname }}</td>
                <td>{{ $supplier_info->fldsuppaddress }}</td>
                <td>{{ $supplier_info->fldactive }}</td>
                <td>{{  \App\Utils\Helpers::numberFormat(($supplier_info->fldpaiddebit)) }}</td>
                <td>{{  \App\Utils\Helpers::numberFormat(($supplier_info->fldleftcredit)) }}</td>
                @php $tot = $supplier_info->fldleftcredit - $supplier_info->fldpaiddebit @endphp
                <td>{{   \App\Utils\Helpers::numberFormat(($tot)) }}</td>
                @php $total_balance += $tot @endphp
            </tr>
        @endforeach
        <tr>
            <td></td>
            <td>All</td>
            <td></td>
            <td></td>
            <td>{{  \App\Utils\Helpers::numberFormat(($total_debit_sum)) }}</td>
            <td>{{  \App\Utils\Helpers::numberFormat(($total_credit_sum)) }}</td>
            <td>{{  \App\Utils\Helpers::numberFormat(($total_balance)) }}</td>
        </tr>
    </tbody>
</table>
