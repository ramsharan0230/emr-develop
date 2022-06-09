<table style="width: 100%" class="table expandable-table custom-table table-bordered table-striped mt-c-15" id="myTableResponse" data-show-columns="true" data-search="false" data-show-toggle="true" data-pagination="false" data-resizable="true">
    <thead class="thead-light">
        <tr>
            <th>SN</th>
            <th>Bill no.</th>
            <th>Bill Type</th>
            <th>Bill Date/Time</th>
            <th>Encounter no.</th>
            <th>Patient Detail</th>
            <th>Billing Mode/Discount Mode</th>
            <th>Prev. Dept.</th>
            <th>Item Amount</th>
            <th>Discount Amt</th>
            <th>Received Amt</th>
            <th>Curr Deposit</th>
            <th>Payment Mode</th>
            <th>Doctor Detail</th>
        </tr>
    </thead>
    <tbody>
        @if (isset($results) and count($results) > 0)

        @forelse ($results as $k => $r)
        @php
        $enpatient = App\Encounter::where('fldencounterval', $r->fldencounterval)->with('patientInfo')->first();
        @endphp


        @if(!is_null($enpatient) && !is_null($enpatient->patientInfo) )

        @php
        $datetime = explode(' ', $r->fldtime);
        // $enpatient = App\Encounter::where('fldencounterval', $r->fldencounterval)->with('patientInfo')->first();
        // dd($r->encounter);
        // $oldOrNew = App\Encounter::where('fldencounterval', $r->fldencounterval)->first()->fldvisit;
        if(!is_null($enpatient))
        {
        $carbonRegTime = \Carbon\Carbon::parse($enpatient->patientInfo->fldtime);
        $carbonBirthday = \Carbon\Carbon::parse($enpatient->patientInfo->fldptbirday);
        $fullname = (isset($enpatient->patientInfo) and !empty($enpatient->patientInfo)) ? $enpatient->patientInfo->fldfullname : '';
        }

        $sn = $k + 1;
        @endphp
        @php
        $billtype = '';

        @endphp
        @if(!is_null($enpatient->patientInfo))
        <tr data-billno="{{ $r->fldbillno  }}" class="billInfo bill-list">
            <td> {{ $sn }} </td>
            <td> {{ $r->fldbillno }} </td>
            @if( substr($r->fldbillno , 0,3) == 'DEP' && $r->fldcurdeposit > 0)
            <td> Deposit Billing</td>
            @elseif( substr($r->fldbillno , 0,3) == 'CRE' && $r->fldcurdeposit < 0)
             <td> Credit Billing </td>
            @elseif( substr($r->fldbillno , 0,3) == 'RET')
            <td>    Return Billing  </td>
            @elseif($r->fldpayitemname == 'Discharge Clearence')
            <td> Discharge Clearance Billing                </td>
            @elseif($r->fldpayitemname == 'Pharmacy Deposit Refund' || $r->fldpayitemname == 'Deposit Refund')
            <td> Refund Billing </td>
            @elseif( (substr($r->fldbillno , 0,3) == 'CAS' || substr($r->fldbillno , 0,3) == 'REG') && ($r->flditemtype != 'Surgicals' || $r->flditemtype != 'Medicines' || $r->flditemtype != 'Extra Items'))
            <td> Service Billing </td>
            @elseif(isset($r->patBill) && count($r->patBill) && ($r->patBill[0]->flditemtype=="Medicines" || $r->patBill[0]->flditemtype=="Surgicals" || $r->patBill[0]->flditemtype=="Extra Items"))
                <td> Pharmacy Billing </td>
                @else
            <td></td>
            @endif
                <td>
                    {{ $carbonRegTime->format('M d Y') }} <br>
                    <i class="fa fa-clock" aria-hidden="true"></i>
                    {{ $carbonRegTime->format('g:i A') }}
                </td>
                <td> {{ $r->fldencounterval }}</td>
                <td>
                    @if ($r->fldpayitemname === "Discharge Clearence")
                    <a data-fldencounterval="{{ $r->fldencounterval }}" data-billno="{{$r->fldbillno}}" onclick="userDetail.displayDischageClearancBilleModal(this)">
                        {{ $fullname }} &nbsp;

                        {{ !is_null($r->encounter) ? $r->encounter->fldvisit : null }} &nbsp;

                        {{ $carbonRegTime->diffInYears($carbonBirthday) }}Y/{{ ($enpatient->patientInfo->fldptsex == 'Male') ? 'M' : 'F' }} <br>
                        <i class="fa fa-phone" aria-hidden="true"></i> {{$enpatient->fldptcontact }}
                    </a>
                    @else
                    @php
                    $parameter = "$r->fldbillno";
                    @endphp
                    <a data-billno="{{$r->fldbillno}}">
                        {{ $fullname }} &nbsp;
                        {{ $carbonRegTime->diffInYears($carbonBirthday) }}Y/{{ ($enpatient->patientInfo->fldptsex == 'Male') ? 'M' : 'F' }} <br>
                        <i class="fa fa-phone" aria-hidden="true"></i> {{$enpatient->fldptcontact}}
                    </a>
                    @endif
                </td>

                <td>
                    BM : {{ (Helpers::getBillingModeByBillno($r->fldbillno) !== '') ? Helpers::getBillingModeByBillno($r->fldbillno) : '' }} <br>
                    DM: {{ $r->flddiscountgroup }}
                </td>
                <td>
                @php
                    $prevdeposit = \App\Utils\Helpers::numberFormat($r->fldprevdeposit);
                    if (str_contains($prevdeposit, '-')) {
                            echo str_replace('-','(',$prevdeposit).")";
                        }else{
                            echo $prevdeposit;
                        }
                    @endphp
                </td>
                <td>
                @php
                    $item_amount = \App\Utils\Helpers::numberFormat($r->flditemamt);
                    if (str_contains($item_amount, '-')) {
                            echo str_replace('-','(',$item_amount).")";
                        }else{
                            echo $item_amount;
                        }
                    @endphp
                </td>
                <td>
                @php
                    $discount_amount = \App\Utils\Helpers::numberFormat($r->flddiscountamt);
                    if (str_contains($discount_amount, '-')) {
                            echo str_replace('-','(',$discount_amount).")";
                        }else{
                            echo $discount_amount;
                        }
                    @endphp
                </td>
                <td>
                @php
                    $received_amount = \App\Utils\Helpers::numberFormat($r->fldreceivedamt);
                    if (str_contains($received_amount, '-')) {
                            echo str_replace('-','(',$received_amount).")";
                        }else{
                            echo $received_amount;
                        }
                    @endphp
                </td>
                <td>
                    @php
                    $current_deposit = \App\Utils\Helpers::numberFormat($r->fldcurdeposit);
                    if (str_contains($current_deposit, '-')) {
                            echo str_replace('-','(',$current_deposit).")";
                        }else{
                            echo $current_deposit;
                        }
                    @endphp
                </td>
                <td> {{ $r->payment_mode  }} </td>
                <td> {{ ($r->patbill->isNotEmpty() && $r->patbill->first()->pat_billing_shares->isNotEmpty()  )  ?  $r->patbill->first()->pat_billing_shares->first()->user->fldfullname : null }}</td>
        </tr>
        @endif

        @endif
        @empty
        @endforelse
        @endif
    </tbody>
</table>