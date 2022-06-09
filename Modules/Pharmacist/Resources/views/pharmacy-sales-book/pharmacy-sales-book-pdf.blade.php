@extends('inpatient::pdf.layout.main')

@section('title')
    Pharmacy Sales Book Report
@endsection
@include('pdf-header-footer.hospital-header')
<h4 style="text-align: center;margin-top:2px;">Pharmacy Sales Book Report</h4>
@section('content')
    <div style="width: 100%;">
        <div style="width: 50%;float: left;">
            <p>Datetime: {{ \Carbon\Carbon::now() }}</p>
            <p>From: {{ \App\Utils\Helpers::dateToNepali($from_date) }} To  {{ \App\Utils\Helpers::dateToNepali($to_date) }}
        </div>
    </div>
    <table class="table content-body">
        <thead>
        <tr>
            <th>S.N</th>
{{--            <th>Code</th>--}}
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

{{--                <td></td>--}}

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
                    $returncp = $pharmacy->purchase ? ( $pharmacy->flditemqty * $pharmacy->purchase->fldnetcost) : 0;
                    $valuesp = $pharmacy->flditemrate;
                    $valuecp = ($pharmacy->purchase ? $pharmacy->purchase->fldnetcost : 0);
                    $netprofit = (($valuesp -$valuecp) - ( $returnsp + $returncp));
                @endphp


                <td>{{ $pharmacy->flditemqty ??'' }}</td>
                <td> {{ $pharmacy->fldretqty ?? '' }}</td>
                <td>{{   \App\Utils\Helpers::numberFormat($valuesp) ?? 0 }}</td>
                <td>{{   \App\Utils\Helpers::numberFormat($valuecp) ?? 0 }}</td>
                <td>{{   \App\Utils\Helpers::numberFormat($pharmacy->flddiscamt) ?? 0 }}</td>
                <td>{{   \App\Utils\Helpers::numberFormat($returnsp) ?? 0 }}</td>
                <td>{{   \App\Utils\Helpers::numberFormat($returncp) ?? 0 }}</td>
                <td>{{   \App\Utils\Helpers::numberFormat(abs($netprofit)) ?? 0}}</td>
            </tr>
        @empty
            <tr><td colspan="12" align="center">  No Data available </td></tr>
        @endforelse
        </tbody>
    </table>
@endsection
