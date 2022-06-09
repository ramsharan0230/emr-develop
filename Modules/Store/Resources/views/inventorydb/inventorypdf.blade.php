@extends('inpatient::pdf.layout.main')

@section('content')
<ul>
    <li>{{ $category }}</li>
    <li>{{ date('Y-m-d H:i') }}</li>
</ul>

<table class="table table-sm table-bordered"  style="border:1px solid #808080;">
    <thead style="border:1px solid #808080;">
        <tr>
            <th class="tittle-th">&nbsp;</th>
            <th class="tittle-th">Particulars</th>
            <th class="tittle-th">Batch</th>
            <th class="tittle-th" width="90px;">Expiry</th>
            <th class="tittle-th">Order</th>
            <th class="tittle-th">QTY</th>
            <th class="tittle-th" style="text-align:right;">Sell</th>
            <th class="tittle-th" style="text-align:right;">Amt</th>
        </tr>
    </thead>
    <tbody>
        @php
            $grandtot = 0;
        @endphp
        @foreach($inventories as $compid => $all_inv)
            @php
                $totamt = 0;
            @endphp
            <tr>
                <td colspan="8" style="text-align: center;padding:20px 0;"><b>{{Helpers::getDepartmentFromCompID($compid)}}</b></td>
            </tr>
            @foreach($all_inv as $inventory)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $inventory->fldstockid }}</td>
                    <td>{{ $inventory->fldbatch }}</td>
                    <td width="90px;">{{ $inventory->fldexpiry ? explode(' ', $inventory->fldexpiry)[0] : '' }}</td>
                    <td style="text-align:center;">{{ $inventory->fldsav }}</td>
                    <td style="text-align:center;">{{ $inventory->fldqty }}</td>
                    <td style="text-align:right;">{{ $inventory->fldsellpr }}</td>
                    <td style="text-align:right;">{{ $inventory->fldqty*$inventory->fldsellpr }}</td>
                </tr>
                @php
                    $totamt += ($inventory->fldqty * $inventory->fldsellpr);
                @endphp
            @endforeach
            <tr style="background-color:#dedede;">
                <td colspan="5">Total</td>
                <td colspan="3" style="text-align:right;">{{$totamt}}</td>
            </tr>
            @php
                $grandtot += $totamt;
            @endphp
        @endforeach
        <tr style="background-color:#dedede;border-top:1px solid #808080;">
            <td colspan="5"><strong>Grand Total<strong></td>
            <td colspan="3" style="text-align:right;padding:4px 0;"><strong>{{$grandtot}}</strong></td>
        </tr>
    </tbody>
</table>
@stop
