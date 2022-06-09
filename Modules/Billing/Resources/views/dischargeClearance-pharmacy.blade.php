@extends('frontend.layouts.master') @section('content')
<style>

.img-ms-form{
    max-width:80px;
    width:100%;
    height: auto ;
}

</style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block ">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">
                                Deposit Clearance
                            </h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <form name="discharge-clearance" method="POST" action="{{route('billing.dischargeClearance.pharmacy')}}">
                            @csrf
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group form-row">
                                        <label class="col-sm-5">Ip No:</label>
                                        <div class="col-sm-7">
                                            <input type="text" name="encounter_id" id="encounter_id" class="form-control" value="{{ (isset($enpatient) && $enpatient) ? $enpatient->fldencounterval : '' }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-9">
                                    <div class="form-group form-row">
                                        <button type="submit" class="btn btn-primary btn-action"><i class="fa fa-"></i>&nbsp;Detail</button>&nbsp;
                                        <!-- <a href="#" type="button" class="btn btn-primary btn-action"><i class="fa fa-sync"></i>&nbsp;Bed & Scheme Exchange</a>&nbsp; -->
                                        <a href="{{ route('depositForm') }}" type="button" class="btn btn-primary btn-action"><i class="fa fa-"></i>&nbsp;Deposit</a>&nbsp;
                                        @if(isset($enpatient) && $enpatient->patientInfo)
                                            @if(Options::get('convergent_payment_status') && Options::get('convergent_payment_status') == 'active' )
                                                <a href="{{ route('convergent.payments', $enpatient->fldencounterval) }}" class="btn btn-primary float-right fonepay-button-save" style="display: none;">Fonepay</a>
                                            @endif
                                        @endif
                                        <a href="{{ route('billing.dischargeClearance') }}" class="btn btn-danger btn-action"><i class="fa fa-sync"></i>&nbsp;Reset</a>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group form-row">
                                        <label class="col-sm-5">Hospital No:</label>
                                        <div class="col-sm-7">
                                            <input type="text" name="patientid" value="<?php if (isset($enpatient) && $enpatient->patientInfo) {
                                                echo $enpatient->patientInfo->fldpatientval;
                                            } ?>" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group form-row">
                                        <label class="col-sm-3">Name:</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="patient_name" value="<?php if (isset($enpatient) && $enpatient->patientInfo) {
                                                echo $enpatient->patientInfo->fullname;
                                            } ?>" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <?php
                                if (isset($enpatient) && $enpatient->patientInfo && $enpatient->fldadmission != 'Registered') {
                                    if(!empty($enpatient->flddod)){
                                        $dod = $enpatient->flddod;
                                        $doa =  $enpatient->flddoa;
                                        $dateworkdoa = \Carbon\Carbon::createFromDate(date('Y-m-d', strtotime($doa)). '00:00:00');
                                        $dateworkdod = \Carbon\Carbon::createFromDate($dod);

                                        $testdate = $dateworkdoa->diffInDays($dod);
                                    }else{
                                        $date = $enpatient->flddoa;
                                        $doa =  $enpatient->flddoa;
                                        $datework = \Carbon\Carbon::createFromDate(date('Y-m-d', strtotime($date)). '00:00:00');
                                        $now = \Carbon\Carbon::now();
                                        $testdate = $datework->diffInDays($now);
                                    }

                                } else {
                                    $doa = 0;
                                    $testdate = 0;
                                }
                                ?>

                                <div class="col-sm-3">
                                    <div class="form-group form-row">
                                        <label class="col-sm-7">Total No of Days:</label>
                                        <div class="col-sm-5">
                                            <input type="text" name="" value="{{ $testdate != 0 ? $testdate + 1 : 0}}" class="form-control">
                                        </div>
                                    </div>
                                </div>


                                <div class="col-sm-3">
                                    <div class="form-group form-row">
                                        <label class="col-sm-6">Admission Date:</label>
                                        <div class="col-sm-6">
                                            <input type="text" name="admission_date" value="{{$doa}}" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group form-row">
                                        <label class="col-sm-6">Discharge Date:</label>
                                        <div class="col-sm-6">
                                            <input type="text" name="discharge_date" value="<?php echo date('Y-m-d'); ?>" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group form-row">
                                        <label class="col-sm-12">Referral Doctor: {{ isset($referable_doctor) ?  $referable_doctor :'' }}</label>

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-7">
                @if(isset($eachpatbilling) && $eachpatbilling)
                    @php
                        $category = [];

                    @endphp
                    @foreach($eachpatbilling as $billing)
                        <div class="iq-card iq-card-block ">
                            <div class="iq-card-header d-flex justify-content-between">
                                <div class="iq-header-title">
                                    <h4 class="card-title">

                                        {{$billing['category']}}
                                    </h4>
                                </div>
                            </div>

                            <div class="iq-card-body">
                                <div class="table-responsive res-table">
                                    <table class="table table-striped table-hover table-bordered">
                                        <thead class="thead-light">
                                        <tr>
                                            <th>Particulars</th>
                                            <th>Rate</th>
                                            <th>Qty</th>
                                            <th>Total</th>
                                            <th>Tax</th>
                                            <th>Discount</th>
                                            <th>TP Bill</th>
                                        </tr>
                                        </thead>
                                        <tbody class="item-list">
                                        @if($billing['details'])
                                            @php
                                                $total = 0;
                                            @endphp
                                            @foreach($billing['details'] as $bill_detail)
                                                <tr data-tax="{{$bill_detail->fldtaxamt}}">
                                                    <td>{{$bill_detail->flditemname}}</td>
                                                    <td>{{\App\Utils\Helpers::numberFormat($bill_detail->flditemrate)}}</td>
                                                    <td>{{$bill_detail->flditemqty}}</td>
                                                    <td>{{\App\Utils\Helpers::numberFormat(($bill_detail->flditemrate * $bill_detail->flditemqty))}}</td>
                                                    @php
                                                        $total += $bill_detail->flditemrate * $bill_detail->flditemqty;
                                                    @endphp
                                                    <td>{{\App\Utils\Helpers::numberFormat($bill_detail->fldtaxamt)}}</td>
                                                    <td>{{\App\Utils\Helpers::numberFormat($bill_detail->flddiscamt)}}</td>
                                                    <td>{{$bill_detail->fldtempbillno}}</td>

{{--                                                    <td>{!! Helpers::getNameByUsername($bill_detail->fldpayto)!!}</td>--}}


                                                </tr>
                                            @endforeach

                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                        @php

                            $category[$billing['category']] = $total;

                        @endphp
                    @endforeach
                @endif
                @php
                    $subtotalfirst =0;
                @endphp
                @if(isset($eachpatbilling) && $eachpatbilling)

                    @foreach($eachpatbilling as $billing)

                        @php
                            $subtotalfirst +=$category[$billing['category']];
                        @endphp
                    @endforeach
                @endif

                @php
                    $grandTotalFirst = $subtotalfirst - $discount + $tax;
                @endphp

            </div>
            <div class="col-sm-5">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title float-left">
                            <h4 class="card-title">
                                Account
                            </h4>

                        </div>
                        <a href="javascript:;" type="button" class="btn btn-primary btn-action payment-refund float-right" id="refundbtn"><i class="fa fa-check"></i>&nbsp;Refund</a>
                    </div>

                    <div class="iq-card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-bordered">
                                <thead class="thead-light">
                                <tr>
                                    <th class="text-center">Particulars</th>
                                    <th class="text-center">Amount</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(isset($eachpatbilling) && $eachpatbilling)
                                    @php

                                        $subtotal =0;
                                    @endphp
                                    @foreach($eachpatbilling as $billing)
                                        <tr>
                                            <td class="text-center"> {{$billing['category']}}</td>
                                            <td class="text-center"><input type="text" name="category_total" class="form-control" value="{{\App\Utils\Helpers::numberFormat($category[$billing['category']])}}"></td>
                                        </tr>
                                        @php
                                            $subtotal +=$category[$billing['category']];
                                        @endphp
                                    @endforeach
                                @endif

                                @php
                                    $grandTotal = $subtotal - $discount + $tax;
                                @endphp

                                <tr>
                                    <th class="text-bold text-center"> Sub Total</th>
                                    <th class="" id="subtotal">@if(isset($total)) {{ \App\Utils\Helpers::numberFormat($subtotal) }} @endif</th>
                                </tr>
                                <input type="hidden"  class="subtotal" id="" value="@if(isset($total)) {{ $subtotal }} @endif">
                                <tr>
                                    <th class="text-bold text-center"> Discount Total</th>
                                    <th class="" id="displayDiscountTotal">@if(isset($discount)) {{ \App\Utils\Helpers::numberFormat($discount) }} @endif

                                    </th>
                                    <input type="hidden" class="form-control" id="totaldiscount" value="{{ $discount }}">
                                </tr>
                                <tr>
                                    <th class="text-bold text-center"> Total</th>
                                    <th class="" id="totalAmt">@if(isset($total)) {{ \App\Utils\Helpers::numberFormat(($subtotal - $discount)) }} @endif</th>
                                </tr>
                                <tr>
                                    <td class="text-center">Tax</td>
                                    <td class="text-center"><input type="text" class="form-control" id="tax" value="{{ \App\Utils\Helpers::numberFormat($tax) }}" readonly></td>
                                </tr>

                                <tr>
                                    <th class="text-bold text-center"> Net Total</th>
                                    <th class="" id="nettotal">@if(isset($grandTotal)) {{ \App\Utils\Helpers::numberFormat($grandTotal) }} @endif</th>
                                </tr>
                                <input type="hidden"  class="nettotal" id="" value="@if(isset($grandTotal)) {{ $grandTotal }} @endif">


                                <tr>
                                    <th class="text-bold text-center"> Total Deposit</th>
                                    <th class="" id="curdeposit">@if(isset($previousDeposit)){{ \App\Utils\Helpers::numberFormat($previousDeposit) }} @endif</th>
                                </tr>
                                <?php if (isset($total)) {
                                    $tobepaidhospital = 0;
                                    $tobepaidpatient = 0;
                                    $recieved_amount = 0;
                                    $cur_deposit = 0;
                                    if (isset($remaining_deposit)) {
                                        $tobepaid = $remaining_deposit->fldcurdeposit - $grandTotal;
                                    } else {
                                        $tobepaid = -$grandTotal;
                                    }

                                    if (isset($previousDeposit)) {
                                        $deposit = $previousDeposit;


                                        if ($tobepaid < 0) {
                                            $tobepaidpatient = abs($tobepaid);
                                            $recieved_amount = abs($tobepaid);
                                        } else {
                                            $tobepaidhospital = $tobepaid;
                                            $recieved_amount = 0;
                                        }

                                        $cur_deposit = $recieved_amount + $tobepaidhospital - $tobepaidpatient;
                                    }
                                } ?>

                                <tr>
                                    <td class="text-center">Remaining Deposit</td>
                                    <td class="text-center"><input type="text" class="form-control" id="remaining_deposit" value="@if(isset($remaining_deposit)) {{\App\Utils\Helpers::numberFormat($remaining_deposit->fldcurdeposit)}}  @endif" readonly></td>
                                </tr>
                                <tr>
                                    <td class="text-center">To be Paid</td>
                                    <td class="text-center"><input type="text" class="form-control" id="tobepaid" value="@if(isset($tobepaid)) {{\App\Utils\Helpers::numberFormat($tobepaid)}}  @endif" readonly></td>
                                </tr>
                                <tr>
                                    <td class="text-center">To Refund</td>
                                    <td class="text-center"><input type="text" class="form-control" id="tobepaidbyhospital" value="{{\App\Utils\Helpers::numberFormat($tobepaidhospital)}}" readonly></td>
                                </tr>
                                <tr>
                                    <td class="text-center">Discount Amount</td>
                                    <td class="text-center">
                                        <select name="discount_type_change" id="discount_type_change" class="form-control">
                                            <option value="">Select Type</option>
                                            <option value="no_discount">No Discount</option>
                                            <option value="fixed">Fixed</option>
                                            <option value="percentage">Percentage</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center">Discount Amount</td>
                                    <td class="text-center"><input type="text" class="form-control" id="discountAmount" value="" ></td>
                                </tr>
                                <tr>
                                    <td class="text-center">Discount Percentage</td>
                                    <td class="text-center">
                                        <input type="text" class="form-control" id="discountPercentange" value="" readonly>
                                        <input type="hidden" name="" class="form-control" id="discountPercent">
                                    </td>
                                </tr>


                                <tr>
                                    <td class="text-center">To be Paid by Patient</td>
                                    <td class="text-center"><input type="text" class="form-control" id="tobepaidbypatient" value="{{\App\Utils\Helpers::numberFormat($tobepaidpatient)}}" readonly></td>
                                </tr>
                                <tr>
                                    <td class="text-center">Received Amount</td>
                                    <td class="text-center"><input type="text" class="form-control" id="received_amount" value="{{\App\Utils\Helpers::numberFormat($recieved_amount)}}"></td>
                                </tr>

                                <tr>
                                    <td class="text-center">CUR Deposit</td>
                                    <td class="text-center"><input type="text" class="form-control" id="cur_deposit" value="{{\App\Utils\Helpers::numberFormat($cur_deposit)}}"></td>
                                </tr>


                                <tr id="payment_date" style="display: none;">
                                    <td class="text-center">Expected Payment Date</td>
                                    <td class="text-center">
                                        <div class="input-group">
                                            <input type="date" name="expected_payment_date" id="expected_payment_date" class="form-control" value="<?php echo date('m-d-Y'); ?>">
                                            {{--<div class="input-group-append">
                                                <div class="input-group-text"><i class="ri-calendar-2-fill"></i></div>
                                            </div>--}}
                                        </div>
                                    </td>

                                </tr>
                                <tr id="bankname" style="display: none;">
                                    <td class="text-center" colspan="2">
                                        <div class="form-group form-row">
                                            <div class="col-sm-6">
                                                <input type="text" name="cheque_number" id="cheque_number" placeholder="Cheque Number" class="form-control">

                                            </div>
                                            <div class="col-sm-6">
                                                <select name="bank_name" id="bank-name" class="form-control">
                                                    <option value="">Select Bank</option>
                                                    @if(isset($banks))
                                                        @forelse($banks as $bank)
                                                            <option value="{{ $bank->fldbankname }}">{{ $bank->fldbankname }}</option>
                                                        @empty

                                                        @endforelse

                                                    @endif
                                                </select>
                                                <!-- <input type="text" name="office_name" id="office_name" placeholder="Office Name" class="form-control mt-2"> -->
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr id="otherreason" style="display: none;">
                                    <td class="text-center" colspan="2">
                                        <div class="form-group form-row">
                                            <div class="col-sm-6" id="otherreason">
                                                <input type="text" name="other_reason" id="other_reason" placeholder="Other Reason" class="form-control">

                                            </div>

                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-center">Remarks</td>
                                    <td class="text-center"><textarea id="discharge_remark" class="form-control"></textarea></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        @if(isset($previousDeposit) and ($grandTotalFirst < $previousDeposit))
                        <input type="hidden" name="mode" class="mode" value="0">

                        @else
                            <label><b> Payment Mode:</b></label>
                            <div class="bak-payment p-2">
                                <input type="hidden" name="convergent_payment_status" id="convergent_payment_status" value="{{Options::get('convergent_payment_status')}}">
                                <input type="hidden" name="generate_qr" id="generate_qr" value="{{Options::get('generate_qr')}}">
                                <input type="hidden" name="fonepaylog_id" value="no" class="js-fonepaylog-id-hidden" value="">
                                <div class="form-row">
                                    <div class="col-sm-3  pay-rad" id="cash_payment" onclick="getRadioFunction('Cash')">
                                        <div class="custom-control custom-radio custom-control-inline" >
                                            <input type="radio" id="" name="payment_mode" class="custom-control-input payment_mode" value="Cash" checked>
                                            <label class="custom-control-label" for=""> Cash</label>
                                        </div>
                                         <div class="img-ms-form">
                                            <img src="{{ asset('new/images/cash-2.png')}}"   class="img-ms-form" alt="">
                                        </div>
                                    </div>
                                    <div class="col-sm-3 pay-rad" id="credit_payment" onclick="getRadioFunction('Credit')">
                                        <div class="custom-control custom-radio custom-control-inline" >
                                            <input type="radio" id="" name="payment_mode" class="custom-control-input payment_mode" value="Credit">
                                            <label class="custom-control-label" for=""> Credit </label>
                                        </div>
                                        <div class="img-ms-form">
                                            <img src="{{ asset('new/images/credit-3.png')}}"   class="img-ms-form" alt="">
                                        </div>
                                    </div>
                                    <div class="col-sm-3 pay-rad" id="card_payment" onclick="getRadioFunction('Card')">
                                        <div class="custom-control custom-radio custom-control-inline" >
                                            <input type="radio" id="" name="payment_mode" class="custom-control-input payment_mode" value="Card">
                                            <label class="custom-control-label " for=""> Card </label>
                                        </div>
                                        <div class="mt-2 img-ms-form">
                                            <img src="{{ asset('new/images/swipe2.png')}}"   class="img-ms-form" alt="">
                                        </div>
                                    </div>
                                    <div class="col-sm-3 pay-rad" id="fonepay_payment" onclick="getRadioFunction('Fonepay')">
                                        <div class="custom-control custom-radio custom-control-inline" >
                                            <input type="radio" id="" name="payment_mode" class="custom-control-input payment_mode" value="Fonepay">
                                            <label class="custom-control-label" for="">Fonepay </label>
                                        </div>
                                        <div class="img-ms-form">
                                            <img src="{{ asset('new/images/fonepay_logo.png')}}" class="ml-4"   class="img-ms-form" alt="">
                                        </div>

                                    </div>
                                </div>
                            </div>
                        <input type="hidden" name="mode" class="mode" value="1">
                        @endif
                        <div class=" form-group form-row">
                            <div class="col-sm-12 text-right mb-2">
                                <input type="radio" name="print_preview" value="detailed" checked> Detailed Invoice
                                <input type="radio" name="print_preview" value="summary"> Summary Invoice
                            </div>
                            <div class="col-sm-12 text-right">
                                <a href="javascript:;" type="button" class="btn btn-primary btn-action payment-save-done" id="payment-save-done"><i class="fa fa-check"></i>&nbsp;Save</a>&nbsp;

                                <a href="javascript:;" class="btn btn-primary btn-action" onclick="previewDischargeClearancePharmacy()"><i class="fa fa-eye"></i>&nbsp;Preview</a>

                                <a href="javascript:;" class="btn btn-primary btn-action" onclick="previewDischargeClearancePharmacy()"><i class="fa fa-print"></i>&nbsp;Print</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
@push('after-script')

    <script>
        function getRadioFunction(value){
                if (value == "Cash") {

                    $('#cash_payment').addClass('checked-bak');
                    $('#credit_payment').removeClass('checked-bak');
                    $('#card_payment').removeClass('checked-bak');
                    $('#fonepay_payment').removeClass('checked-bak');

                    $('.payment-save-done').show();
                    $('.fonepay-button-save').hide();
                    $('#payment_date').hide();
                    $('#otherreason').hide();
                    $('#bankname').hide();
                    net = parseFloat(numberFormat($('#nettotal').text()));
                    currentdeposit = parseFloat(numberFormat($('#curdeposit').text()));
                    if(currentdeposit > net){

                    }else{
                        $('#received_amount').val(numberFormatDisplay(net-currentdeposit));
                        $('#tobepaidbyhospital').val(numberFormatDisplay(0));
                        $('#tobepaidbypatient').val(numberFormatDisplay(net-currentdeposit));
                        $('#cur_deposit').val(numberFormatDisplay(0));
                    }


                } else if (value == "Credit") {

                    $('#cash_payment').removeClass('checked-bak');
                    $('#credit_payment').addClass('checked-bak');
                    $('#card_payment').removeClass('checked-bak');
                    $('#fonepay_payment').removeClass('checked-bak');
                    $('#payment_date').show();
                    $('.payment-save-done').show();
                    $('.fonepay-button-save').hide();
                    $('#otherreason').hide();
                    $('#payment_date').show();
                    $('#bankname').hide();
                    net = parseFloat(numberFormat($('#nettotal').text()));
                    currentdeposit = parseFloat(numberFormat($('#curdeposit').text()));
                    if(currentdeposit > net){

                    }else{
                        $('#received_amount').val(numberFormatDisplay(0));
                        $('#tobepaidbyhospital').val(numberFormatDisplay(0));
                        $('#tobepaidbypatient').val(numberFormatDisplay(net-currentdeposit));
                        $('#cur_deposit').val(numberFormatDisplay(currentdeposit-net));
                    }


                } else if (value == "Card") {

                    $('#cash_payment').removeClass('checked-bak');
                    $('#credit_payment').removeClass('checked-bak');
                    $('#card_payment').addClass('checked-bak');
                    $('#fonepay_payment').removeClass('checked-bak');

                    $('.payment-save-done').show();
                    $('.fonepay-button-save').hide();
                    $('#payment_date').hide();
                    $('#otherreason').hide();
                    $('#bankname').hide();
                    net = parseFloat(numberFormat($('#nettotal').text()));
                    currentdeposit = parseFloat(numberFormat($('#curdeposit').text()));
                    if(currentdeposit > net){

                    }else{
                        $('#received_amount').val(numberFormatDisplay(net-currentdeposit));
                        $('#tobepaidbyhospital').val(numberFormatDisplay(0));
                        $('#tobepaidbypatient').val(numberFormatDisplay(net-currentdeposit));
                        $('#cur_deposit').val(numberFormatDisplay(0));
                    }

                } else if (value == "Fonepay") {

                    $('#cash_payment').removeClass('checked-bak');
                    $('#credit_payment').removeClass('checked-bak');
                    $('#card_payment').removeClass('checked-bak');
                    $('#fonepay_payment').addClass('checked-bak');

                    $('#payment_date').hide();
                    $('#bankname').hide();
                    $('#otherreason').hide();
                    $('.fonepay-button-save').hide();
                    $('.payment-save-done').show();

                    net = parseFloat(numberFormat($('#nettotal').text()));
                    currentdeposit = parseFloat(numberFormat($('#curdeposit').text()));
                    if(currentdeposit > net){

                    }else{
                        $('#received_amount').val(numberFormatDisplay(net-currentdeposit));
                        $('#tobepaidbyhospital').val(numberFormatDisplay(0));
                        $('#tobepaidbypatient').val(numberFormatDisplay(net-currentdeposit));
                        $('#cur_deposit').val(numberFormatDisplay(0));
                    }

                    var convergent = $('#convergent_payment_status').val();
                    var encounter = $('#encounter_id').val();
                    var generateQr = $('#generate_qr').val();
                    if(encounter !='' && convergent !='' && convergent == 'active' && generateQr == 'yes'){
                        var totalamount = parseFloat(numberFormat($('#received_amount').val()));
                        if(totalamount == '' || totalamount <= 0){
                            showAlert('Amount not available');
                            return false;
                        }
                        fonepayQrGenerate(encounter);
                    }

                }else if (value == "Other") {

                    $('#other_reason').show();

                }else{
                    $('#cash_payment').addClass('checked-bak');
                    $('#credit_payment').removeClass('checked-bak');
                    $('#card_payment').removeClass('checked-bak');
                    $('#fonepay_payment').removeClass('checked-bak');
                    $('#payment_date').hide();
                    $('#otherreason').hide();
                    $('#bankname').hide();
                }
                // return false;
            }


            function fonepayQrGenerate(encounter) {
                let route = "{!! route('convergent.payments.depositClearance') !!}";
                $.ajax({
                    url: route,
                    type: "POST",
                    data: { "total": $('#received_amount').val(),encounter:$('#encounter_id').val(), "_token": "{{ csrf_token() }}" },
                    success: function (data) {
                        if (data.success === true) {
                            $('.file-modal-title').empty().text('Scan To Pay');
                            $('.file-form-data').html(data.html);
                            $('.modal-footer #savebutton').hide();
                            $('#file-modal').modal('show');
                            $(".modal-dialog").removeClass("modal-lg");
                            $(".modal-dialog").addClass("modal-sm");
                        } else {
                            showAlert(data.message, 'error');
                        }
                    }
                });
            }
        $(document).on('change', '#discount_type_change', function () {
            var discount_type = $(this).val() || '';
            if (discount_type == 'no_discount') {
                $('#discountAmount').val('0');
                $('#discountPercentange').val('0');

                $('#discountAmount').attr('readonly', true);
                $('#discountPercentange').attr('readonly', true);
                $('#discountPercentange').trigger("keyup");
                $('#totaldiscount').trigger("keyup");
                $('#discountAmount').trigger("focusout");
            } else if  (discount_type == 'fixed') {
                $('#discountAmount').attr('readonly', false);
                $('#discountPercentange').attr('readonly', true);
                $('#discountPercentange').trigger("keyup");
                $('#totaldiscount').trigger("keyup");
                $('#discountAmount').trigger("focusout");
            } else if  (discount_type == 'percentage') {
                $('#discountAmount').attr('readonly', true);
                $('#discountPercentange').attr('readonly', false);
                $('#discountPercentange').trigger("keyup");
                $('#totaldiscount').trigger("keyup");
                $('#discountAmount').trigger("focusout");
            }
        });


        $(document).on('focusout', '#discountAmount', function () {
            // alert('sdfsdf');
            var subtotal = parseFloat(numberFormat($('.subtotal').val())) || 0;
            var discount = parseFloat(numberFormat($('#discountAmount').val())) || 0;
            var percentage = (discount*100)/subtotal;
            $('#displayDiscountTotal').html(numberFormatDisplay(discount));
            $('#discountPercentange').val(percentage.toFixed(8));
            $('#discountPercent').val(percentage.toFixed(8));
            $('#totaldiscount').val(numberFormatDisplay(discount));
            totalAmt = parseFloat(subtotal) - parseFloat(discount);
            $('#totalAmt').text(numberFormatDisplay(totalAmt));
             $('#discountPercentange').trigger("keyup");
            $('#totaldiscount').trigger("keyup");












        });



        $(function () {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-Token": $('meta[name="_token"]').attr("content")
                }
            });


            $('#received_amount').on('keyup', function () {
                var recieved_amount = parseFloat(numberFormat($('#received_amount').val()));
                recieved_amount = isNaN(recieved_amount) ? 0 : recieved_amount;
                var tobepaidhospital = parseFloat(numberFormat($('#tobepaidbyhospital').val()));
                tobepaidhospital = isNaN(tobepaidhospital) ? 0 : tobepaidhospital;

                var tobepaidpatient = parseFloat(numberFormat($('#tobepaidbypatient').val()));
                tobepaidpatient = isNaN(tobepaidpatient) ? 0 : tobepaidpatient;

                var cur_deposit = parseFloat(recieved_amount) + parseFloat(tobepaidhospital) - parseFloat(tobepaidpatient);
                $('#cur_deposit').val(numberFormatDisplay(cur_deposit));
                //alert(cur_deposit);
            });
            $('#discountPercentange').keyup(function () {
                // alert('jeyed');
                var discount = parseFloat(numberFormat($(this).val())) || 0;
                if (isNaN(discount)) {
                    showAlert('Enter valid number.', 'fail');

                } else if (discount > 100) {
                    showAlert('Discount cannot be greater than 100 %', 'fail');
                    $('#discountAmount').val('');
                    $('#discountPercentange').val('');
                } else {
                    // alert('sdfsdf');
                    var subtotal = 0;
                    var totalvat = 0;
                    var discountamount = 0;
                    var finalnet = 0;
                    $.each($('.item-list tr'), function(i, trElem) {
                        var trSubTotal = parseFloat(numberFormat($(trElem).find('td:nth-child(4)').text().trim()));



                        trSubTotal = parseFloat(numberFormat(isNaN(trSubTotal) ? 0 : trSubTotal));
                        var trdiscount = parseFloat(numberFormat((trSubTotal * discount)/100));


                        var existingtax = parseFloat(numberFormat($(trElem).data('tax')) || 0);
                        var taxpercentage = ((parseFloat(existingtax)*100)/trSubTotal);
                        // alert(taxpercentage);
                        var tax = ((trSubTotal-trdiscount) * taxpercentage)/100;
                        // alert(tax);

                        var nettotal = parseFloat(numberFormat(trSubTotal))-parseFloat(numberFormat(trdiscount))+parseFloat(numberFormat(tax));
                        nettotal = parseFloat(numberFormat(nettotal));

                        $(trElem).find('td:nth-child(6)').text(numberFormatDisplay(trdiscount));
                        $(trElem).find('td:nth-child(5)').text(numberFormatDisplay(tax));
                        // $(trElem).find('td:nth-child(4)').text(nettotal);


                        subtotal += parseFloat(numberFormat(trSubTotal));
                        totalvat += parseFloat(numberFormat(tax));
                        discountamount += parseFloat(numberFormat(trdiscount));
                        finalnet += parseFloat(numberFormat(nettotal));
                    });
                    $('#tax').val(numberFormatDisplay(numberFormatDisplay(totalvat)));
                }
            });

            $(document).on('focusout', '#discountPercentange', function () {
                var discount = parseFloat(numberFormat($(this).val())) || 0;
                var totamt = parseFloat(numberFormat($('.subtotal').val()));
                var discountamt = (discount*totamt)/100;
                var calculateddiscountamount = parseFloat(numberFormat(discountamt));
                // alert(calculateddiscountamount);
                $('#discountAmount').val(numberFormatDisplay(calculateddiscountamount));
                $('#discountAmount').trigger("focusout");
            });

            $('#totaldiscount').on('keyup', function () {
                var totalAmt = parseFloat(numberFormat($('#totalAmt').text()));
                var nettotal = parseFloat(numberFormat($('.nettotal').val()));
                var curdeposit = parseFloat(numberFormat($('#curdeposit').text()));
                var dis = parseFloat(numberFormat($('#totaldiscount').val()));
                var deposit = parseFloat(numberFormat($('#curdeposit').text()));
                 var tax = parseFloat(numberFormat($('#tax').val()));
                $('#nettotal').text(numberFormatDisplay(parseFloat(totalAmt) + parseFloat(tax)));

                var total = parseFloat(numberFormat($('#totalAmt').text()));
                var remaining_deposit = parseFloat(numberFormat($('#remaining_deposit').val()));
                var previousDeposit = curdeposit;
                var grandTotal = parseFloat(numberFormat($('#nettotal').text()));
                var tobepaidbyhospital = parseFloat(numberFormat($('#tobepaidbyhospital').val()));



                                    if (remaining_deposit > 0) {
                                        var tobepaid = parseFloat(deposit) - parseFloat(grandTotal);
                                    } else {
                                        var tobepaid = -grandTotal;
                                    }



                                        if (tobepaid < 0) {
                                            var tobepaidpatient = Math.abs(tobepaid);
                                            var recieved_amount = Math.abs(tobepaid);
                                            var tobepaidhospital = 0;

                                        } else {
                                            var tobepaidhospital = tobepaid;
                                            var recieved_amount = 0;
                                            var tobepaidpatient = 0;

                                        }

                                        var cur_deposit = parseFloat(recieved_amount) + parseFloat(tobepaidhospital) - parseFloat(tobepaidpatient);

                                        $('#remaining_deposit').val(numberFormatDisplay(remaining_deposit));
                                $('#tobepaid').val(numberFormatDisplay(tobepaid));
                                $('#tobepaidbyhospital').val(numberFormatDisplay(tobepaidhospital));
                                $('#tobepaidbypatient').val(numberFormatDisplay(tobepaidpatient));
                                $('#received_amount').val(numberFormatDisplay(recieved_amount));
                                $('#cur_deposit').val(numberFormatDisplay(cur_deposit));



            });
            function getRadioFunction(value){
                if (value == "Cash") {

                    $('#cash_payment').addClass('checked-bak');
                    $('#credit_payment').removeClass('checked-bak');
                    $('#card_payment').removeClass('checked-bak');
                    $('#fonepay_payment').removeClass('checked-bak');

                    $('.payment-save-done').show();
                    $('.fonepay-button-save').hide();
                    $('#payment_date').hide();
                    $('#otherreason').hide();
                    $('#bankname').hide();

                    net = parseFloat(numberFormat($('#nettotal').text()));
                    $('#received_amount').val(numberFormatDisplay(net));
                    $('#tobepaidbyhospital').val(numberFormatDisplay(0));
                    $('#tobepaidbypatient').val(numberFormatDisplay(net));

                } else if (value == "Credit") {

                    $('#cash_payment').removeClass('checked-bak');
                    $('#credit_payment').addClass('checked-bak');
                    $('#card_payment').removeClass('checked-bak');
                    $('#fonepay_payment').removeClass('checked-bak');
                    $('#payment_date').show();
                    $('.payment-save-done').show();
                    $('.fonepay-button-save').hide();
                    $('#otherreason').hide();
                    $('#payment_date').show();
                    $('#bankname').hide();
                    net = parseFloat(numberFormat($('#nettotal').text()));
                    cur = parseFloat(numberFormat($('#nettotal').text()));

                    $('#received_amount').val('');


                } else if (value == "Card") {

                    $('#cash_payment').removeClass('checked-bak');
                    $('#credit_payment').removeClass('checked-bak');
                    $('#card_payment').addClass('checked-bak');
                    $('#fonepay_payment').removeClass('checked-bak');

                    $('.payment-save-done').show();
                    $('.fonepay-button-save').hide();
                    $('#payment_date').hide();
                    $('#otherreason').hide();
                    $('#bankname').hide();
                    net = parseFloat(numberFormat($('#nettotal').text()));
                    $('#received_amount').val(numberFormatDisplay(net));
                    $('#tobepaidbyhospital').val(numberFormatDisplay(0));
                    $('#tobepaidbypatient').val(numberFormatDisplay(net));

                } else if (value == "Fonepay") {

                    $('#cash_payment').removeClass('checked-bak');
                    $('#credit_payment').removeClass('checked-bak');
                    $('#card_payment').removeClass('checked-bak');
                    $('#fonepay_payment').addClass('checked-bak');

                    $('#payment_date').hide();
                    $('#bankname').hide();
                    $('#otherreason').hide();
                    $('.fonepay-button-save').hide();
                    $('.payment-save-done').show();
                    net = parseFloat(numberFormat($('#nettotal').text()));
                    $('#received_amount').val(numberFormatDisplay(net));
                    $('#tobepaidbyhospital').val(numberFormatDisplay(0));
                    $('#tobepaidbypatient').val(numberFormatDisplay(net));

                } else if (value == "Other") {

                    $('#other_reason').show();

                }else{
                    $('#cash_payment').addClass('checked-bak');
                    $('#credit_payment').removeClass('checked-bak');
                    $('#card_payment').removeClass('checked-bak');
                    $('#fonepay_payment').removeClass('checked-bak');
                    $('#payment_date').hide();
                    $('#otherreason').hide();
                    $('#bankname').hide();
                }
                // return false;
            }
            $('#payment_mode').on('change', function () {
                if (this.value === "Cash") {
                    $('.payment-save-done').show();
                    $('.fonepay-button-save').hide();
                    $('#payment_date').hide();
                    $('#otherreason').hide();
                    $('#bankname').hide();
                    net = parseFloat(numberFormat($('#nettotal').text()));
                    $('#received_amount').val(numberFormatDisplay(net));
                    $('#tobepaidbyhospital').val(numberFormatDisplay(0));
                    $('#tobepaidbypatient').val(numberFormatDisplay(net));

                } else if (this.value === "Credit") {
                    $('.payment-save-done').show();
                    $('.fonepay-button-save').hide();
                    $('#otherreason').hide();
                    $('#payment_date').show();
                    $('#bankname').hide();
                    net = parseFloat(numberFormat($('#nettotal').text()));
                    cur = parseFloat(numberFormat($('#nettotal').text()));

                    $('#received_amount').val('');

                } else if (this.value === "Cheque") {
                    $('.payment-save-done').show();
                    $('.fonepay-button-save').hide();
                    $('#payment_date').hide();
                    $('#bankname').show();
                    $('#otherreason').hide();
                    net = parseFloat(numberFormat($('#nettotal').text()));
                    $('#received_amount').val(numberFormatDisplay(net));
                    $('#tobepaidbyhospital').val(numberFormatDisplay(0));
                    $('#tobepaidbypatient').val(numberFormatDisplay(net));
                } else if (this.value === "Fonepay") {
                    // fonepay-button-save
                    $('#payment_date').hide();
                    $('#bankname').hide();
                    $('#otherreason').hide();
                    $('.fonepay-button-save').show();
                    $('.payment-save-done').hide();
                    net = parseFloat(numberFormat($('#nettotal').text()));
                    $('#received_amount').val(numberFormatDisplay(net));
                    $('#tobepaidbyhospital').val(numberFormatDisplay(0));
                    $('#tobepaidbypatient').val(numberFormatDisplay(net));
                } else if (this.value === "Other") {
                    $('.payment-save-done').show();
                    $('.fonepay-button-save').hide();
                    $('#otherreason').show();
                    $('#payment_date').hide();
                    $('#bankname').hide();
                    net = parseFloat(numberFormat($('#nettotal').text()));
                    $('#received_amount').val(numberFormatDisplay(net));
                    $('#tobepaidbyhospital').val(numberFormatDisplay(0));
                    $('#tobepaidbypatient').val(numberFormatDisplay(net));
                }
            });

            $('.payment-save-done').on('click', function () {
                if (confirm('Are you sure you want to save ?')) {
                    var curdeposit = parseFloat(numberFormat($('#curdeposit').text()));
                var tobepaidbyhospital = parseFloat(numberFormat($('#tobepaidbyhospital').val()));
                var tobepaidbypatient = parseFloat(numberFormat($('#tobepaidbypatient').val()));
                var encounter_id = $('#encounter_id').val();
                var totaldiscount = parseFloat(numberFormat($('#totaldiscount').val()));
                var nettotal = parseFloat(numberFormat($("#nettotal").text()));
                var tax = parseFloat(numberFormat($("#tax").val()));
                var tobepaid = parseFloat(numberFormat($('#tobepaid').val()));
                var payment_mode = $("input[name='payment_mode']:checked").val();
                var pay_id = $('#pay_id').val();
                var discharge_remark = $('#discharge_remark').val();
                var received_amount = parseFloat(numberFormat($("#received_amount").val()));
                var print_preview = $('input[name=print_preview]:checked').val();
                var expected_payment_date = $("#expected_payment_date").val();
                var recieved_amount = parseFloat(numberFormat($('#recieved_amount').val()));
                var tobepaidhospital = parseFloat(numberFormat($('#tobepaidbyhospital').val()));
                var tobepaidpatient = parseFloat(numberFormat($('#tobepaidbypatient').val()));
                var cur_deposit = parseFloat(numberFormat($('#cur_deposit').val()));
                var discountpercentage = $('#discountPercent').val();
                var mode = $('.mode').val();
                var fonepaylog_id = $('.js-fonepaylog-id-hidden').val();
                if(mode == 1){
                    if (payment_mode == '' || payment_mode == undefined) {
                        showAlert('Please choose payment mode', 'fail');
                        return false;
                    }
                }

                // alert(discountpercentage)
                $.ajax({
                    url: "{{ route('billing.finalPaymentDischarge.pharmacy') }}",
                    type: "POST",
                    data: {
                        curdeposit: curdeposit,
                        tobepaidbyhospital: tobepaidbyhospital,
                        tobepaidbypatient: tobepaidbypatient,
                        encounter_id: encounter_id,
                        totaldiscount: totaldiscount,
                        nettotal: nettotal,
                        tax: tax,
                        tobepaid: tobepaid,
                        payment_mode: payment_mode,
                        pay_id: pay_id,
                        discharge_remark: discharge_remark,
                        received_amount: received_amount,
                        print_preview: print_preview,
                        expected_payment_date: expected_payment_date,
                        recieved_amount: recieved_amount,
                        tobepaidhospital: tobepaidhospital,
                        tobepaidpatient: tobepaidpatient,
                        cur_deposit: cur_deposit,
                        discountpercentage: discountpercentage,
                        discountAmount:totaldiscount,
                        fonepaylog_id: fonepaylog_id,
                    },
                    success: function (data) {
                        location.reload(true);

                    }
                });
                }

            });

            $('.payment-refund').on('click', function () {
                var encounter_id = $('#encounter_id').val();
                var cur_deposit = parseFloat(numberFormat($('#cur_deposit').val()));
                var print_preview = $('input[name=print_preview]:checked').val();
                if (cur_deposit > 0) {
                    if (confirm('Do you want to refund deposit?') == true) {
                        $.ajax({
                            url: "{{ route('billing.finalPaymentDischargeRefundDepositPharmacy') }}",
                            type: "POST",
                            data: {

                                encounter_id: encounter_id,
                                cur_deposit: cur_deposit,
                                print_preview: print_preview
                            },
                            success: function (data) {


                                var params = {


                                    fldbillno: data.invoice_number,

                                    };
                                    var queryString = $.param(params);
                                    window.open("{{ route('depositForm.printBill') }}?" + queryString, '_blank');
                                    location.reload(true);

                            }
                        });

                    } else {
                        location.reload(true);
                    }
                } else {
                    location.reload(true);
                }
            });

        });


        function printDischargeClearance() {
            var params = {
                encounter_id: $('#encounter_id').val(),
                print_preview: $('input[name=print_preview]:checked').val(),
                billtype: 'discharge'
            };
            var queryString = $.param(params);
            window.open("{{ route('discharge.clearance.print.pharmacy') }}?" + queryString, '_blank');
        }

        function previewDischargeClearancePharmacy() {
            var params = {
                encounter_id: $('#encounter_id').val(),
                print_preview: $('input[name=print_preview]:checked').val(),
                billtype: 'discharge'
            };
            var queryString = $.param(params);
            window.open("{{ route('discharge-pharmacy.preview.invoice') }}?" + queryString, '_blank');
        }


    $('.img-ms-form').click(function() {

    $(this).parent().find('input[type=radio]').prop('checked', true);

    });
    $('.pay-rad').click(function() {

    $(this).closest('.checked-bak').find('input[type=radio]').prop('checked', true);

    });
    </script>

    @if(Session::get('display_generated_invoice'))
        <script>
            var params = {

                fldencounterval: "{{Session::get('billing_encounter_id')}}",
                receive_amt: "{{Session::get('receive_amtphar_bill')}}",
                printIds: "{{Session::get('billing_printids')}}",
                billNumberGeneratedString: "{{Session::get('invoice_number')}}",
                payment_mode: "{{Session::get('payment_mode')}}",
                billtype: 'discharge'
            };
            var queryString = $.param(params);
            window.open("{{ route('printPharmacy') }}?" + queryString, '_blank');
        </script>
    @endif
@endpush
