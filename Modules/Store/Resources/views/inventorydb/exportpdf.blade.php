@extends('inpatient::pdf.layout.main')
<style>
    .text-left{
        text-align:left;
    }
    .table, th, td {
        font-size:14px;
    }
</style>

@section('content')
    <ul>
        <li>{{ $category }}</li>
        <li>Total Quantity</li>
    </ul>

    <table class="table table-sm table-bordered"  style="border:1px solid #808080;">
        <thead  style="border:1px solid #808080;">
            <tr> 
                <th class="tittle-th text-left">&nbsp;S/N</th>
                <th class="tittle-th text-left">Generic Name</th>
                <th class="tittle-th text-left">Batch</th>
                <th class="tittle-th text-left">Brand Name</th>
                <th class="tittle-th text-left">Supplier</th>
                <th class="tittle-th text-left">Type</th>
                <th class="tittle-th text-left">QTY</th>
                <th class="tittle-th text-left" style="width:17%">Expiry Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inventories as $inventory)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $inventory->fldstockid }}</td>
                <td>{{ $inventory->fldbatch }}</td>
                <td>{{ $inventory->fldbrand }}</td>
                <td>{{ $inventory->fldsuppname }}</td>
                <td>{{ $inventory->fldcategory }}</td>
                <td>{{ $inventory->fldqty }}</td>
                <td>{{ date('Y-m-d', strtotime($inventory->fldexpiry)) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@stop
