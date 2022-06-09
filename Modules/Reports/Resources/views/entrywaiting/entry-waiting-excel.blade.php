
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
    @for($i=1;$i<6;$i++)
        <th></th>
    @endfor
    <th colspan="2"><b>Type:</b></th>
    <th colspan="2">{{ ($type == 0) ? "Not Saved" : "Not Billed" }}</th>
</tr>
<tr>
    @for($i=1;$i<6;$i++)
        <th></th>
    @endfor
    <th colspan="2"><b>Comp:</b></th>
    <th colspan="2">{{ $comp }}</th>
</tr>
<tr>
    @for($i=1;$i<6;$i++)
        <th></th>
    @endfor
    <th colspan="2"><b>User:</b></th>
    <th colspan="2">{{ $user }}</th>
</tr>
<tr><th></th></tr>
<tr>
    <th>SN.</th>
    <td>EncID</td>
    <td>Category</td>
    <td>Particulars</td>
    <td>Rate</td>
    <td>Qty</td>
    <td>User</td>
    <td>Dept</td>
    <td>DateTime</td>
</tr>
</thead>
<tbody>
    {!! $html !!}
</tbody>
</table>
