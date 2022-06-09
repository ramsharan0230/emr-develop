<table>
    <thead>
    <tr>
        <th></th>
    </tr>
    <tr>
        @for($i=1;$i<6;$i++)
            <th></th>
        @endfor
        <th colspan="8">
            <b>{{ isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'' }}</b>
        </th>
    </tr>
    <tr>
        @for($i=1;$i<6;$i++)
            <th></th>
        @endfor
        <th colspan="8">
            <b>{{ isset(Options::get('siteconfig')['system_slogan'])?Options::get('siteconfig')['system_slogan']:'' }}</b>
        </th>
    </tr>

    <tr>
        <th>OFF Time Report</th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        

        <th style="text-align: right">Printed Time: {{ \Carbon\Carbon::now() }} </th>
    </tr>

    <tr>
        <th>{{isset($request) ? \App\Utils\Helpers::dateToNepali($request->eng_from_date) :''}} To {{isset($request) ? \App\Utils\Helpers::dateToNepali($request->eng_to_date) :''}}</th>
    </tr>
    <tr>
        <th>{{isset($request) ? $request->from_time : '' }} To {{ $request->to_time}}</th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        

        {{-- <th style="text-align: right">Printed Time: {{ \Carbon\Carbon::now() }} </th> --}}
        <th style="text-align: right">Printed By: {{$userid??''}}</th>
    </tr>
    <tr>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        
        
    </tr>
   
    <tr>
        <th></th>
    </tr>
    <tr>
        <th></th>
    </tr>
    <tr>
        <th>SN</th>
        <th>Date</th>
        <th>Time</th>
        <th>Invoice</th>
        <th>EnciD</th>
        <th>Name</th>
        <th>Particulars</th>
        <th>Rate</th>
        <th>Quantity</th>
        <th>Subtotal</th>

    </tr>
    </thead>
    <tbody>
        @if(isset($results) and count($results) > 0)
            @forelse ($results as $report )
            <tr>
                <td>{{ $loop->index+1 }}</td>
                {{-- <td> {{ \Carbon\Carbon::parse($report->time)->format('Y-m-d') }} </td>
                <td> {{ \Carbon\Carbon::parse($report->time)->format('h:i:s') }} </td> --}}
                <td>{{ \App\Utils\Helpers::dateEngToNepdash(\Carbon\Carbon::parse($report->fldtime)->format('Y-m-d'))->full_date }}</td>
                <td> {{ \Carbon\Carbon::parse($report->fldtime)->format('H:i:s') }} </td>
                <td> {{ $report->fldbillno }} </td>
                <td> {{ $report->fldencounterval }} </td>
                <td> {{ $report->flduserid }} </td>
                <td> {{ $report->flditemtype }} </td>
                <td> {{ $report->flditemrate }} </td>
                <td> {{ $report->flditemqty }} </td>
                <td> {{ $report->fldditemamt }} </td>
            </tr>
        @empty
            
        @endforelse
        @endif
    {{-- <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>Total Amount: {{ \App\Utils\Helpers::numberFormat($totalamount) }}</td>
        <td>TotalTaxAmt : {{ \App\Utils\Helpers::numberFormat($taxamount) }}</td>
        <td>TotalDiscAmt : {{ \App\Utils\Helpers::numberFormat($discAmt) }}</td>
        <td>TotalNetAmt : {{ \App\Utils\Helpers::numberFormat($nettot) }}</td>
        <td>TotalRecAmt : {{ \App\Utils\Helpers::numberFormat($recAmt) }}</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr> --}}
    </tbody>
</table>
