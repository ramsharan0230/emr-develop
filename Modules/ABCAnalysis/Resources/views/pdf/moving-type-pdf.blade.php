@extends('inpatient::pdf.layout.main')

@section('title')
ABC Analysis Moving Type Report
@endsection

@section('content')
    <div style="width: 100%;">
        <div style="width: 50%;float: left;">
            <p>SUMMARY: {{$from_date}} TO {{$to_date}}</p>
        </div>
        <div style="width: 50%;float: left;">
            <p>ANALYSIS TYPE: {{ucfirst($analysis_type)}}</p>
        </div>
    </div>
    <table class="table content-body">
        <thead>
            <tr>
                <th>S.No.</th>
                <th>Generic Name</th>
                <th>Brand Name</th>
                <th>Category</th>
                <th>Sold Qty</th>
                <th>Unit Price</th>
                @if($analysis_type == "quantity")
                <th>Moving Type</th>
                @else
                <th>Value Type</th>
                @endif
                <th>Total Amt</th>
            </tr>
        </thead>
        <tbody>
            {!! $html !!}
        </tbody>
    </table>
@endsection
