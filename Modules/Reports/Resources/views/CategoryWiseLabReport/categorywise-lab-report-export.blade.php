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
            <th colspan="8"><b>Category Wise Lab Report</b></th>
        </tr>
        <tr><th></th></tr>
        <tr>
            @for($i=1;$i<10;$i++)
            <th></th>
            @endfor
            <th colspan="2"><b>From date:</b></th>
            <th colspan="2">{{ $fromDate }}</th>
        </tr>
        <tr>
            @for($i=1;$i<10;$i++)
            <th></th>
            @endfor
            <th colspan="2"><b>To date:</b></th>
            <th colspan="2">{{ $toDate }}</th>
        </tr>
        <tr><th></th></tr>
        {!! $htmlHead !!}
    </thead>
    <tbody>
        {!! $htmlBody !!}
    </tbody>
</table>
