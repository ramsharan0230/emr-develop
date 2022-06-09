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
        <th>Encounter</th>
        <th>Patient Name</th>
        <th>Particulars</th>
        <th>Rate</th>
        <th>Qty</th>
        <th>Disc</th>
        <th>Tax</th>
        <th>Total</th>
        <th>Entry Date</th>
        <th>Invoice</th>
        <th>Payable</th>
        <th>Referral</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($datas as $data)
        <tr>
            <td>{{$data->fldencounterval}}</td>
            <td>{{(isset($data->encounter->patientInfo)) ? $data->encounter->patientInfo->getFldrankfullnameAttribute() : ""}}</td>
            <td>{{$data->flditemname}}</td>
            <td>{{$data->flditemrate}}</td>
            <td>{{$data->flditemqty}}</td>
            <td>Rs. {{$data->flddiscamt}}</td>
            <td>Rs. {{$data->fldtaxamt}}</td>
            <td>{{$data->tot}}</td>
            <td>{{$data->entrytime}}</td>
            <td>{{$data->fldbillno}}</td>
            <td>{{$data->fldpayto}}</td>
            <td>{{$data->fldrefer}}</td>
        </tr>
    @endforeach
    <tr>
        <td colspan="7" style="text-align: right"><b>Total</b></td>
        <td><b>{{ $datas->sum('tot') }}</b></td>
    </tr>
    </tbody>
</table>
