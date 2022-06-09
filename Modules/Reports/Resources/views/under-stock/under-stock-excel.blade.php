@php
    $sum = 0;
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
        <th colspan="8"><b>Under Stock Report</b></th>
    </tr>
    <tr><th></th></tr>
{{--    <tr>--}}
{{--        @for($i=1;$i<6;$i++)--}}
{{--            <th></th>--}}
{{--        @endfor--}}
{{--        <th colspan="2"><b>From date:</b></th>--}}
{{--        <th colspan="2">{{ $from_date }}</th>--}}
{{--    </tr>--}}
{{--    <tr>--}}
{{--        @for($i=1;$i<6;$i++)--}}
{{--            <th></th>--}}
{{--        @endfor--}}
{{--        <th colspan="2"><b>To date:</b></th>--}}
{{--        <th colspan="2">{{ $to_date }}</th>--}}
{{--    </tr>--}}
{{--    <tr>--}}
{{--        @for($i=1;$i<6;$i++)--}}
{{--            <th></th>--}}
{{--        @endfor--}}
{{--        <th colspan="2"><b>Supplier/Department:</b></th>--}}
{{--        <th colspan="2">{{ (isset($orders[0])) ? $orders[0]->fldsuppname : '' }}</th>--}}
{{--    </tr>--}}
    <tr>
        @for($i=1;$i<6;$i++)
            <th></th>
        @endfor
        <th colspan="2"><b>Datetime:</b></th>
        <th colspan="2">{{ now() }}</th>
    </tr>
{{--    <tr>--}}
{{--        @for($i=1;$i<6;$i++)--}}
{{--            <th></th>--}}
{{--        @endfor--}}
{{--        <th colspan="2"><b>Reference No: </b></th>--}}
{{--        <th colspan="2">{{ (isset($references[0])) ? $references[0]->fldnewreference : '' }}</th>--}}
{{--    </tr>--}}
    <tr><th></th></tr>
    <tr>
        <th>Particulars</th>
        <th>Manufacturer</th>
        <th>Standard</th>
        <th>MinQty</th>
        <th>CurrentQty</th>
        <th>Comment</th>
        {{--                                        <th>Category</th>--}}
    </tr>
    </thead>
    <tbody>
    @if(isset($meds))
        <tr>
            <td align="center" colspan="8"><b>Medicine</b></td>
        </tr>
        @forelse( $meds as $med)
            <tr>
                <td> {{ $med->fldbrandid }}</td>
                <td> {{ $med->fldmanufacturer}}</td>
                <td> {{ $med->fldstandard}}</td>
                <td> {{ $med->fldminqty}}</td>
                <td> {{ $med->fldleadtime}}</td>
            </tr>
        @empty

        @endforelse
    @endif

    @if(isset($surgeries))
        <tr>
            <td align="center" colspan="8"><b>Surgical</b></td>
        </tr>
        @forelse( $surgeries as $surg)
            <tr>
                <td> {{ $surg->fldbrandid }}</td>
                <td> {{ $surg->fldmanufacturer}}</td>
                <td> {{ $surg->fldstandard}}</td>
                <td> {{ $surg->fldminqty}}</td>
                <td> {{ $surg->fldleadtime}}</td>
            </tr>
        @empty

        @endforelse
    @endif

    @if(isset($extras))
        <tr>
            <td align="center" colspan="8"><b>Extras</b></td>
        </tr>
        @forelse( $extras as $ext)
            <tr>
                <td> {{ $ext->fldbrandid }}</td>
                <td> {{ $ext->fldmanufacturer}}</td>
                <td> {{ $ext->fldstandard}}</td>
                <td> {{ $ext->fldminqty}}</td>
                <td> {{ $ext->fldleadtime}}</td>
            </tr>
        @empty

        @endforelse
    @endif




{{--    @if($html)--}}
{{--        {!! $html !!}--}}
{{--    @endif--}}
    </tbody>
</table>

{{--<div style="width: 100%;">--}}
{{--    <div style="width: 50%;float: left;">--}}
{{--        <p>IN WORDS: {{ \App\Utils\Helpers::numberToNepaliWords($totalamount) }}</p>--}}
{{--    </div>--}}
{{--    <div style="width: 50%;float: left;">--}}
{{--        --}}{{--            <p>TOTATAMT: {{ $totalamount }}</p>--}}
{{--    </div>--}}
{{--</div>--}}
