
@php
$totalamount = 0;
@endphp
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
<tr><th></th></tr>
<tr>
    @for($i=1;$i<4;$i++)
        <th></th>
    @endfor
    <th><b>From date:</b></th>
    <th colspan="2">{{ $from_date }} {{ isset($from_date) ? "(". \App\Utils\Helpers::dateToNepali($from_date) .")" :'' }}</th>
</tr>
<tr>
    @for($i=1;$i<4;$i++)
        <th></th>
    @endfor
    <th><b>To date:</b></th>
    <th colspan="2">{{ $to_date }} {{ isset($to_date) ? "(". \App\Utils\Helpers::dateToNepali($to_date)  .")" :'' }}</th>
</tr>
<tr>
    @for($i=1;$i<4;$i++)
        <th></th>
    @endfor
    <th><b>Printed At:</b>{{ date('Y-m-d H:i:s') }}</th>
    
</tr>
<tr>
    @for($i=1;$i<4;$i++)
        <th></th>
    @endfor
    <th><b>Printed By: </b> {{\App\Utils\Helpers::getNameByUsername(\Auth::guard('admin_frontend')->user()->flduserid)}}</th>
    
</tr>
<tr><th></th></tr>
<tr>
    <th>S/N</th>
    <th>Tran Date</th>
    <th>Description</th>
    <th>Voucher Code</th>
    <th>Voucher No</th>
    <th>Debit</th>
    <th>Credit</th>
    <th>Balance</th>
    <th>ChequeNo</th>
    <th>Remarks</th>
</tr>
</thead>
<tbody>
{!! $html !!}
</tbody>
</table>
