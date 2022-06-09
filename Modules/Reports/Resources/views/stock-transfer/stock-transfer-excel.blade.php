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
        <th colspan="8"><b>Stock Transfer Report</b></th>
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
        <th colspan="2">{{ \Carbon\Carbon::now() }}</th>
    </tr>
    <tr>
        @for($i=1;$i<6;$i++)
            <th></th>
        @endfor
        <th colspan="2"><b>Transfer No: </b></th>
        <th colspan="2">{{ (isset($references[0])) ? $references[0]->fldreference : '' }}</th>
    </tr>
    <tr><th></th></tr>
    <tr>
        <th>SN</th>
        <td>Generic</td>
        <td>Brand</td>
        <td>Unit</td>
        <td>Batch</td>
        <td>Expiry</td>
        <td>Qty</td>
        <td>Sellpr</td>
        <td>Total</td>
        <td>From</td>
        <td>To</td>
    </tr>
    </thead>
    <tbody>
    @if($references)
        @foreach($references as $reference)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ (($reference->fldstockid) ? $reference->fldstockid :'') ?? null }}</td>

                @if($reference->fldcategory=='Medicines')
                    <td>{{ (($reference->brand) ? $reference->brand->fldbrand :'' ) ?? null}}</td>
                    <td>{{ (($reference->brand) ? $reference->brand->fldvolunit :'' ) ?? null}}</td>
                @endif

                @if($reference->fldcategory=='Surgicals')
                    <td>{{ (($reference->surgicalBrand) ? $reference->surgicalBrand->fldbrand :'' ) ?? null}}</td>
                    <td>{{ (($reference->surgicalBrand) ? $reference->surgicalBrand->fldvolunit :'' ) ?? null}}</td>
                @endif

                @if($reference->fldcategory=='Extra Items')
                    <td>{{ (($reference->extraBrand) ? $reference->extraBrand->fldbrand :'' ) ?? null}}</td>
                    <td>{{ (($reference->extraBrand) ? $reference->extraBrand->fldvolunit :'' ) ?? null}}</td>
                @endif

                <td>{{ (($reference->entry) ? $reference->entry->fldbatch : '' ) ?? null }}</td>
                <td>{{ (($reference->entry) ? $reference->entry->fldexpiry : '' ) ?? null }}</td>
                <td>{{ $reference->fldqty ?? null }}</td>
                <td>{{ $reference->fldsellpr ?? null }}</td>
                @php
                    $amount = ($reference->fldqty) * ($reference->fldsellpr);
                    $sum = ($sum+$amount);
                @endphp
                <td>{{ $amount ? 'Rs.'.$amount : 'Rs.0' }}</td>
                <td>{{ $reference->fldfromcomp  ?? null}}</td>
                <td>{{ $reference->fldtocomp  ?? null}}</td>


                {{--                    <td>{{ ($reference->fldqty) ? 'Rs.'.$reference->fldcost :'Rs.0' }}</td>--}}

                {{--                    <td>{{ $amount ? 'Rs.'.$amount : 'Rs.0' }}</td>--}}
            </tr>
        @endforeach
    @endif
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;Total</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td></td>
        <td>&nbsp;</td>
        <td>&nbsp;{{ 'Rs.'.$sum  }}</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
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
