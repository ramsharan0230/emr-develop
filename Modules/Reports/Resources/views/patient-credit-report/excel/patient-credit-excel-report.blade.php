<table class="table" id="table">
    <thead>
        <tr>
            <th>SN</th>
            <th>Date</th>
            <th>Days</th>
            <th>Patient ID/Encounter ID</th>
            <th>Patient Details</th>
            @if($amount_type==0)
            <th>Credit Amount</th>
            @elseif($amount_type==1)
            <th>Deposite Amount</th>
            @else
            <th>Credit Amount</th>
            @endif
        </tr>
    </thead>
    <tbody id="billing_result">
        <?php 
            $count = 1; 
        ?>
        @if(!$patient_credit_report->isEmpty())
            @foreach ($patient_credit_report as $pcr)
            @php
                $days=new \Carbon\Carbon($pcr->fldtime);
                $days_exceed=\Carbon\Carbon::now()->diffInDays($days);
            @endphp
                <tr>
                    <td class="green_day">{{$count++}}</td>
                    <td class="green_day">{{$pcr->fldtime ?? ''}}</td>
                    <td class="green_day">{{$days_exceed ?? ''}}</td>
                    <td class="green_day">{{$pcr->fldpatientval ?? ''}}/{{$pcr->fldencounterval ?? ''}}</td>
                    <td class="green_day">
                        {{strtoupper($pcr->fldptnamefir) ?? ''}} {{strtoupper($pcr->fldptnamelast) ?? ''}} <br>
                        {{Carbon\Carbon::parse($pcr->fldptbirday)->age ?? ''}}/{{$pcr->fldptsex ?? ''}} Y <br> 
                        {{$pcr->fldptcontact ?? ''}}
                    </td>
                    <td class="green_day">{{$pcr->fldcurdeposit ?? ''}}</td>
                </tr>
            @endforeach
        @endif                        
    </tbody>
</table>     