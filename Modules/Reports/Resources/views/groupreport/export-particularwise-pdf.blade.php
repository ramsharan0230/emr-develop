@extends('inpatient::pdf.layout.main')

@section('title', 'GROUP ITEM REPORT')

@section('content')
    @if(isset($certificate))
        <h4 style="text-align: center;">{{ucfirst($certificate)}} REPORT</h4>
    @endif
    <table style="width: 100%;">
        <tbody>
        <tr>
            <td style="width: 200px;">
                <p>From Date: {{ $finalfrom}}</p>
                <p>To Date: {{ $finalto }}</p>
                <p>Billing Mode: {{ $billingmode }}</p>
                <p>Comp: {{ $comp }}</p>
            </td>
        </tbody>
    </table>
    <table style="width: 100%;"  class="content-body">
        <thead>
            <tr>
                <th>Particulars</th>
                <th>Rate</th>
                <th>Qty</th>
                <th>Disc</th>
                <th>Tax</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total_qty = 0;
                $total_rate = 0;
                $total_dsc = 0;
                $total_tax = 0;
                $total_totl = 0;
                $total_particulars = 0;
            @endphp
            @foreach ($datas as $fldgroup => $data)
                @php
                    $total_particulars += count($data);
                @endphp
                <tr>
                    <td colspan="6" style="text-align: center;"><b>{{ $fldgroup }}<b></td>
                </tr>
                @foreach($data as $d)
                    @php
                        $total_qty += $d->qnty;
                        $total_rate += $d->rate;
                        $total_dsc += $d->dsc;
                        $total_tax += $d->tax;
                        $total_totl += $d->totl;
                    @endphp
                    <tr>
                        <td>{{$d->flditemname}}</td>
                        <td>{{$d->rate}}</td>
                        <td>{{$d->qnty}}</td>
                        <td>Rs. {{$d->dsc}}</td>
                        <td>Rs. {{$d->tax}}</td>
                        <td>Rs. {{$d->totl}}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td>Total</td>
                <td>{{ ($total_particulars > 0) ? $total_rate/$total_particulars : "0" }}</td>
                <td>{{ $total_qty }}</td>
                <td>Rs. {{ $total_dsc }}</td>
                <td>Rs. {{ $total_tax }}</td>
                <td>Rs. {{ $total_totl }}</td>
            </tr>
        </tfoot>
    </table>
@endsection
