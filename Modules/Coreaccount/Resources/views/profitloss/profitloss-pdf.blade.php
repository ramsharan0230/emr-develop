@extends('inpatient::pdf.layout.main')

@section('title', 'Profit and Loss')

@section('content')

<main>
    @include('frontend.common.account-header')

    <div style="width: 51%; float: left;">
        <p><b>From Date:</b> {{ isset($from_date) ? $from_date :'' }} {{ isset($eng_from_date) ? "(" .$eng_from_date .")" :'' }}</p>
        <p><b>To Date:</b> {{ isset($to_date) ? $to_date :'' }} {{ isset($eng_to_date) ? "(" .$eng_to_date .")" :'' }}</p>
    </div>
    <div style="width: 44%; float:left;">
        <h3>Profit and Loss</h3>
    </div>
    <table style="width: 100%;" border="1px" class="content-body">
        <thead>
        <tr>
            <th class="text-center">S/N</th>
            <th class="text-center"  colspan="2">Particulars</th>
            <th class="text-center">For The period</th>
            <th class="text-center">Year To Date</th>
        </tr>
        <tr>
            <th class="text-center" ></th>
            <th class="text-center" >Group</th>
            <th class="text-center">Subgroup</th>
            <th class="text-center">Amount</th>
            <th class="text-center">Amount</th>
        </tr>
        </thead>
        <tbody>
        {!! $html !!}
        </tbody>
    </table>
    @php
        $signatures = Helpers::getSignature('bedoccupancy');
    @endphp
    @include('frontend.common.footer-signature-pdf')
</main>
@endsection

