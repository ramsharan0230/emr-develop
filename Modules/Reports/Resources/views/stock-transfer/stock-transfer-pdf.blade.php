@extends('inpatient::pdf.layout.main')

@section('title')
    Stock Transfer Report
@endsection

<header>
    <table style="width: 100%;">
        <tbody>
        <tr>
            <td style="width: 20%;"><img src="{{ asset('uploads/config/'.Options::get('brand_image')) }}" alt="" width="100" height="100"/></td>
            <td style="width:70%;">
                <h3 style="text-align: center;margin-bottom:8px;">{{ isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'' }}</h3>
                <h4 style="text-align: center;margin-top:2px;margin-bottom:0;">{{ isset(Options::get('siteconfig')['system_slogan'])?Options::get('siteconfig')['system_slogan']:'' }}</h4>
                <h4 style="text-align: center;margin-top:2px;">{{ isset(Options::get('siteconfig')['system_address'])?Options::get('siteconfig')['system_address']:'' }}</h4>
                <h4 style="text-align: center;margin-top:2px;"> Contact No: {{ Options::get('system_telephone_no') ? Options::get('system_telephone_no'):'' }}</h4>
                <h4 style="text-align: center;margin-top:2px;">Stock Transfer Report</h4>
                {{--                @if(isset($certificate))--}}
                {{--                    <h4 style="text-align: center;">{{ucfirst($certificate)}} REPORT</h4>--}}
                {{--                @endif--}}
            </td>
            <td></td>
        </tr>
        </tbody>
    </table>
</header>

@section('content')
{{--    <p align="center" style="margin-left: 210px;"><strong>  Stock Transfer Report</strong></p>--}}
    @php
        $sum = 0;
    @endphp
    <div style="width: 100%;">
        <div style="width: 50%;float: left;">
            <p>Datetime: {{ \Carbon\Carbon::now() }}
            </p>
        </div>
        <div style="width: 50%;float: left;">
            <p>Transfer No: {{ (isset($references[0])) ? $references[0]->fldreference : '' }}
            </p>
        </div>
    </div>
    <table class="table content-body">
        <thead>
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
                    <td>{{ (($reference->entry) ? $reference->entry->fldexpiry : '' )  ?? null}}</td>
                    <td>{{ $reference->fldqty  ?? null}}</td>
                    <td>{{ $reference->fldsellpr  ?? null}}</td>
                    @php
                        $amount = ($reference->fldqty) * ($reference->fldsellpr);
                        $sum = ($sum+$amount);
                    @endphp
                    <td>{{ $amount ? 'Rs.'.$amount : 'Rs.0' }}</td>
                    <td>{{ $reference->fldfromcomp ?? null }}</td>
                    <td>{{ $reference->fldtocomp  ?? null}}</td>


                    {{--                    <td>{{ ($reference->fldqty) ? 'Rs.'.$reference->fldcost :'Rs.0' }}</td>--}}

                    {{--                    <td>{{ $amount ? 'Rs.'.$amount : 'Rs.0' }}</td>--}}
                </tr>
            @endforeach
        @endif
        @if($sum)
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
            @endif
        </tbody>
    </table>
    {{--    <div style="width: 100%;">--}}
    {{--        <div style="width: 50%;float: left;">--}}
    {{--            <p>IN WORDS: {{ \App\Utils\Helpers::numberToNepaliWords($totalamount) }}</p>--}}
    {{--        </div>--}}
    {{--        <div style="width: 50%;float: left;">--}}
    {{--            --}}{{--            <p>TOTATAMT: {{ $totalamount }}</p>--}}
    {{--        </div>--}}
    {{--    </div>--}}
@endsection
