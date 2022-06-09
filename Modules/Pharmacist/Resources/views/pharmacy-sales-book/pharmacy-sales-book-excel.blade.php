
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
        <th colspan="8"><b>Pharmacy Sales Book Report</b></th>
    </tr>
    <tr><th></th></tr>
    <tr>
        @for($i=1;$i<6;$i++)
            <th></th>
        @endfor
        <th colspan="2"><b>From date:</b></th>
        <th colspan="2">{{ $from_date }} {{ "(".\App\Utils\Helpers::dateToNepali($from_date) .")" }}</th>
    </tr>
    <tr>
        @for($i=1;$i<6;$i++)
            <th></th>
        @endfor
        <th colspan="2"><b>To date:</b></th>
        <th colspan="2">{{ $to_date }} {{ "(". \App\Utils\Helpers::dateToNepali($to_date) .")" }}</th>
    </tr>
    <tr>
        @for($i=1;$i<6;$i++)
            <th></th>
        @endfor
        <th colspan="2"><b>Datetime:</b></th>
        <th colspan="2">{{ \Carbon\Carbon::now() }}</th>
    </tr>
    <tr><th></th></tr>
    <tr>
        <th>S.N</th>
{{--        <th>Code</th>--}}
        <th>Brand Name</th>
        <th>Generic Name</th>
        <th>Sales Qty</th>
        <th>Return Qty</th>
        <th>Value(SP)</th>
        <th>Value(CP)</th>
        <th>Discount</th>
        <th>Return(SP)</th>
        <th>Return(CP)</th>
        <th>Net Profit</th>
    </tr>
    </thead>
    <tbody>

    @php
        $returnsp =0;
        $returncp = 0;
        $valuesp =0;
        $valuecp =0;
        $netprofit =0;
    @endphp

    @forelse($pharmacy_sales as $pharmacy)
        <tr>

            <td>{{ $loop->iteration }}</td>

{{--            <td></td>--}}

            @if($pharmacy->flditemtype=='Medicines')
                <td>{{ (($pharmacy->brand) ? $pharmacy->brand->fldbrand :'' ) ?? null}}</td>
            @endif

            @if($pharmacy->flditemtype=='Surgicals')
                <td>{{ (($pharmacy->surgicalBrand) ? $pharmacy->surgicalBrand->fldbrand :'' ) ?? null}}</td>
            @endif

            @if($pharmacy->flditemtype=='Extra Items')
                <td>{{ (($pharmacy->extraBrand) ? $pharmacy->extraBrand->fldbrand :'' ) ?? null}}</td>
            @endif

            @if($pharmacy->flditemtype=='Medicines')
                <td>{{ (($pharmacy->brand) ? $pharmacy->brand->flddrug :'' ) ?? null}}</td>
            @endif

            @if($pharmacy->flditemtype=='Surgicals')
                <td>{{ (($pharmacy->surgicalBrand) ? $pharmacy->surgicalBrand->fldsurgid :'' ) ?? null}}</td>
            @endif

            @if($pharmacy->flditemtype=='Extra Items')
                <td>{{ (($pharmacy->extraBrand) ? $pharmacy->extraBrand->fldextraid :'' ) ?? null}}</td>
            @endif

            @php
                $returnsp = ( $pharmacy->flditemqty  * $pharmacy->fldditemamt );
                $returncp = $pharmacy->purchase ?  ( $pharmacy->flditemqty * $pharmacy->purchase->fldnetcost) : 0;
                $valuesp = $pharmacy->flditemrate;
                $valuecp = ($pharmacy->purchase ? $pharmacy->purchase->fldnetcost :0);
                $netprofit = (($valuesp -$valuecp) - ( $returnsp + $returncp));
            @endphp


            <td>{{ $pharmacy->flditemqty ??0 }}</td>
            <td> {{ $pharmacy->fldretqty ?? 0 }}</td>
            <td>{{ \App\Utils\Helpers::numberFormat($valuesp) ?? 0 }}</td>
            <td>{{ \App\Utils\Helpers::numberFormat($valuecp) ?? 0 }}</td>
            <td>{{ \App\Utils\Helpers::numberFormat($pharmacy->flddiscamt) ?? 0 }}</td>
            <td>{{ \App\Utils\Helpers::numberFormat($returnsp) ?? 0 }}</td>
            <td>{{ \App\Utils\Helpers::numberFormat($returncp) ?? 0 }}</td>
            <td>{{ \App\Utils\Helpers::numberFormat(abs($netprofit)) ?? 0 }}</td>

{{--            <td></td>--}}
        </tr>
    @empty
        <tr><td colspan="12" align="center">  No Data available </td></tr>
    @endforelse
    </tbody>
</table>

