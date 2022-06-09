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
            <th colspan="2"><b>Analysis Type:</b></th>
            <th colspan="2">{{ $analysis_type }}</th>
        </tr>
        <tr><th></th></tr>
        <tr>
            <th>S.No.</th>
            <th>Generic Name</th>
            <th>Brand Name</th>
            <th>Category</th>
            <th>Sold Qty</th>
            <th>Unit Price</th>
            @if($analysis_type == "quantity")
            <th>Moving Type</th>
            @else
            <th>Value Type</th>
            @endif
            <th>Total Amt</th>
        </tr>
    </thead>
    <tbody>
        {!!$html!!}
    </tbody>
</table>
