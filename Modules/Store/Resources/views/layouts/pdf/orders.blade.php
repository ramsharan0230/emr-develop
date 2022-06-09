@extends('inpatient::pdf.layout.main')

@section('title')
Orders
@endsection

@section('content')
    @php $totalamount = 0; @endphp
    <table class="table content-body">
        <thead>
            <tr>
                <th>SN</th>
                <th>Item Code</th>
                <th>Particulars</th>
                <td>Tender Rate</td>
                <td>Offered Brand/Company</td>
                <td>Qty</td>
                <td>Amount</td>
                <td>Current Stock</td>
                <td>Avg Consumption for 3 months</td>
                <td>Previous Ordered Quantity</td>
                <td>Remarks</td>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            @php $totalamount += $order->fldamt; @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $order->fldid }}</td>
                <td>{{ $order->flditemname }}</td>
                <td>&nbsp;</td>
                <td>{{ $order->fldreference }}</td>
                <td>{{ $order->fldqty }}</td>
                <td>{{ $order->fldamt }}</td>
                <td>{{ $order->Fldstock }}</td>
                <td>&nbsp;</td>
                <td>{{ $order->purchase->fldtotalqty }}</td>
                <td>&nbsp;</td>
            </tr>
            @endforeach
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{ $totalamount }}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
@endsection
