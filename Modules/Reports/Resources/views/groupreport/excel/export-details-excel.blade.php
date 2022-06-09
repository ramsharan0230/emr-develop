<table>
    <thead>
    <tr><th></th></tr>
    <tr>
        @for($i=1;$i<3;$i++)
            <th></th>
        @endfor
        <th colspan="5" style="text-align: center"><b>{{ isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'' }}</b></th>
    </tr>
    <tr>
        @for($i=1;$i<3;$i++)
            <th></th>
        @endfor
        <th colspan="5" style="text-align: center"><b>{{ isset(Options::get('siteconfig')['system_slogan'])?Options::get('siteconfig')['system_slogan']:'' }}</b></th>
    </tr>
    <tr><th></th></tr>
    <tr>
        @for($i=1;$i<3;$i++)
            <th></th>
        @endfor
        <th colspan="5" style="text-align: center"><b>{{ $selectedItem }}</b></th>
        <th colspan="5"></th>
    </tr>
    <tr>
        @for($i=1;$i<3;$i++)
            <th></th>
        @endfor
        <th colspan="5" style="text-align: center"><b>{{$finalfrom}} {{ isset($finalfrom) ? "(". \App\Utils\Helpers::dateNepToEng($finalfrom)->full_date .")" : ''}} TO
                {{$finalto}} {{ isset($finalto) ? "(" .\App\Utils\Helpers::dateNepToEng($finalto)->full_date . ")":'' }}</b></th>
    </tr>


    <tr>
        @for($i=1;$i<3;$i++)
            <th></th>
        @endfor
        <th colspan="5" style="text-align: center"><b>Datetime: {{ \Carbon\Carbon::now() }}</b></th>
        <th style="text-align: center"></th>
    </tr>

    <tr><th></th></tr>
    <tr>
        <th>Date</th>
        <th>EncID</th>
        <th>Patient Name</th>
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
        @foreach ($datas as $itemtype => $data)
            @php
                $total_particulars += count($data);
            @endphp
            <tr>
                <td colspan="9" style="text-align: center;"><b>{{ htmlspecialchars($itemtype) }}</b></td>
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
                    <td>{{($dateType == "invoice_date") ? $d->invoicetime : $d->entrytime}}</td>
                    <td>{{$d->fldencounterval}}</td>
                    <td>{{(isset($d->encounter->patientInfo)) ? $d->encounter->patientInfo->getFldrankfullnameAttribute() : ""}}</td>
                    <td>{{htmlspecialchars($d->flditemname)}}</td>
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
