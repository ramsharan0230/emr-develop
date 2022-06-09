@extends('inpatient::pdf.layout.main')

@section('title')
ABC Analysis Item Class Report
@endsection

@section('content')
    <div style="width: 100%;">
        <div style="width: 50%;float: left;">
            <p>DATE: {{$from_date}} </p>
        </div>
    </div>
    <table class="table content-body">
        <thead>
            <tr>
                <th>S.No.</th>
                <th>Generic Name</th>
                <th>Brand Name</th>
                <th>Category</th>
                <th>Batch</th>
                {{-- <th>Stock</th> --}}
                <th>Class</th>
                <th>Sold Qty</th>
                <th>Total Amt</th>
            </tr>
        </thead>
        <tbody>
            {!! $html !!}
        </tbody>
    </table>
@endsection
