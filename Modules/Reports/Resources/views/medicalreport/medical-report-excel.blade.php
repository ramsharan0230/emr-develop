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
            <th colspan="2"><b>Category:</b></th>
            <th colspan="2">{{ $category }}</th>
        </tr>
        <tr>
            @for($i=1;$i<6;$i++)
            <th></th>
            @endfor
            <th colspan="2"><b>From:</b></th>
            <th colspan="2">{{ $finalfrom }}</th>
        </tr>
        <tr>
            @for($i=1;$i<6;$i++)
            <th></th>
            @endfor
            <th colspan="2"><b>To:</b></th>
            <th colspan="2">{{ $finalto }}</th>
        </tr>
        <tr>
            @for($i=1;$i<6;$i++)
            <th></th>
            @endfor
            <th colspan="2"><b>Gender:</b></th>
            <th colspan="2">{{ $gender }}</th>
        </tr>
        <tr><th></th></tr>
        <tr>
            <th>Index</th>
            <td>Date</td>
            <td>EncID</td>
            <td>Name</td>
            <td>Age</td>
            <td>Gender</td>
            <td>DOReg</td>
            <td>Patient No</td>
            <td>Observation</td>
        </tr>
    </thead>
    <tbody>
        {!! $html !!}
    </tbody>
</table>