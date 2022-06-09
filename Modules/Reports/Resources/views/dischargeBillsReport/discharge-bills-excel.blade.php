<style>
    .res-table td ul{
        list-style: none;

    }

</style>
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
    <tr><th></th></tr>
    <tr>
        @for($i=1;$i<6;$i++)
            <th></th>
        @endfor
        <th colspan="2"><b>Comp:</b></th>
        <th colspan="2">{{ $comp }}</th>
    </tr>
    <tr>
        @for($i=1;$i<6;$i++)
            <th></th>
        @endfor
        <th colspan="2"><b>From Date:</b></th>
        <th colspan="2">{{ \Carbon\Carbon::parse($finalfrom)->format('Y-m-d') }}</th>
    </tr>
    <tr>
        @for($i=1;$i<6;$i++)
            <th></th>
        @endfor
        <th colspan="2"><b>To Date:</b></th>
        <th colspan="2">{{ \Carbon\Carbon::parse($finalto)->format('Y-m-d') }}</th>
    </tr>
    <tr>
        @for($i=1;$i<6;$i++)
            <th></th>
        @endfor
        <th colspan="2"><b>Service Type:</b></th>
        <th colspan="2">{{ $serviceType }}</th>
    </tr>
    <tr><th></th></tr>
    <tr>
        <th>SN.</th>
        <th>Encounter ID</th>
        <th>Patient Name</th>
        <th>Deposit Receipt No.</th>
        <th>Invoice No.</th>
        <th>Deposit Refund No.</th>
        <th>Deposit Amount</th>
        <th>Total Net Bill Amount</th>
        <th>Amount Received After Deposit Adjustment</th>
        <th>Discount</th>
        <th>Amount Refund After Deducting Deposit</th>
        <th>Remaining Refund</th>
        <th>Admitted Date</th>
        <th>Discharge Date</th>
        <th>Remarks</th>
    </tr>
    </thead>
    <tbody>
        {!! $html !!}
    </tbody>
    </table>
