@extends('frontend.layouts.master')
<style>

.img-ms-form{
    max-width:80px;
    width:100%;
    height: auto ;
}

.total-detail th:nth-child(1){
    text-align:right;
}

</style>

@php
    $subtotal = 0;
    $totalDiscount = 0;
    $totalVat = 0;
    $nettotal = 0;
    $department = '';
@endphp
@if(isset($enpatient) and !empty($enpatient))
    @php
        $patientDepartmentbed = \App\DepartmentBed::select('fldbed','flddept')->where('fldencounterval',$enpatient->fldencounterval)->first();
    @endphp
    @if(isset($patientDepartmentbed) and !empty($patientDepartmentbed))
        @php
            $patientDepartment = \App\Department::select('fldcateg')->where('flddept',$patientDepartmentbed->flddept)->first();
        @endphp
    @else
        @php
            $patientDepartment = \App\Department::select('fldcateg')->where('flddept',$enpatient->fldcurrlocat)->first();
        @endphp
    @endif

    @php
        $billingmode = $enpatient->fldbillingmode;
    @endphp

    @if(isset($patientDepartment) and !empty($patientDepartment))
        @php
            $department = $patientDepartment->fldcateg;
        @endphp
    @else
        @php
            $department = '';
        @endphp
    @endif

@endif

@php
    if ($department == 'Consultation')
    $department = 'Outpatient';
    elseif($department == 'Patient Ward')
    $department = 'Inpatient';
    elseif($department == 'Emergency')
    $department = 'Emergency';
    else
    $department = '';




    $segment = Request::segment(1);
    if(isset($patient_status_disabled) && $patient_status_disabled == 1 )
    $disableClass = 'disableInsertUpdate';
    else
    $disableClass = '';

    if($segment == 'admin'){
    $segment2 = Request::segment(2);
    $segment3 = Request::segment(3);
    if(!empty($segment3))
    $route = 'admin/'.$segment2 . '/'.$segment3;
    else
    $route = 'admin/'.$segment2;
    } else
    $route = $segment;
@endphp
@if(isset($enpatient))
    @php
        $result = substr($enpatient->fldencounterval, 0, 2);
        if ($result == 'OP')
        $department = 'Outpatient';
        elseif($result == 'IP')
        $department = 'Inpatient';
        elseif($result == 'ER')
        $department = 'Emergency';
        else
        $department = '';
   @endphp
@else
    @php
     $department = '';
    @endphp
@endif

@section('content')

    <div class="container-fluid mb-0">
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-block">
                <button type="button" class="close text-black-50 float-right" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
            </div>
        @endif

        @if ($message = Session::get('error'))
            <div class="alert alert-danger alert-block">
                <button type="button" class="close text-black-50 float-right" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
            </div>
        @endif
        <div class="row">
            @include('billing::common.patient-profile')
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h3 class="card-title">
                                Dispensing Details
                            </h3>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <div class="row">

                            <div class="col-sm-4 col-lg-4">
                             @if(isset($enpatient) && (strtolower($enpatient->fldbillingmode) == 'health insurance' || strtolower($enpatient->fldbillingmode) == 'healthinsurance' || strtolower($enpatient->fldbillingmode) == 'hi'))
                                @php $encallowedamt = \App\PatInsuranceDetails::where('fldencounterval',$enpatient->fldencounterval)->first();  @endphp
                                @php $encchargedamt = \App\PatBilling::where('fldencounterval',$enpatient->fldencounterval)->where('fldsave','1')->sum('fldditemamt'); @endphp
                                <input type="hidden" id="allowedamt" class="allowedamt" name="allowedamt" value="{{ isset($encallowedamt)?\App\Utils\Helpers::numberFormat($encallowedamt->fldallowedamt,'insert'):'' }}">
                                    <input type="hidden" id="chargedamt" class="chargedamt" name="chargedamt" value="{{ isset($encchargedamt)?\App\Utils\Helpers::numberFormat($encchargedamt,'insert'):'' }}">
                                {{-- <input type="hidden" id="allowedamt" name="allowedamt" value="{{ \App\Utils\Helpers::numberFormat($encallowedamt->fldallowedamt,'insert') }}"> --}}
                            @endif
                            <input type="hidden" name="js-dispensing-billingmode-select" id="js-dispensing-billingmode-select" value="{{(isset($enpatient) && $enpatient->fldbillingmode !='') ? $enpatient->fldbillingmode : ''}}">
                                {{-- <div class="custom-control custom-radio custom-control-inline" onclick="getPatientMedicine()"> --}}
                                 <div class="custom-control custom-radio custom-control-inline" onclick="getMedicineList()">
                                    {{-- <input type="radio" name="radio1" value="ordered" id="ordered-radio" class="custom-control-input" checked> --}}
                                    <input type="radio" name="radio1" value="ordered" id="ordered-radio" class="custom-control-input" @if(isset($enpatient) && (strtolower($enpatient->fldbillingmode) != 'health insurance' || strtolower($enpatient->fldbillingmode) != 'healthinsurance' || strtolower($enpatient->fldbillingmode) != 'hi')) checked @else checked @endif>
                                    <label for="ordered-radio" class="custom-control-label"> New </label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline" onclick="getDispensedPatientMedicine()">
                                    <input type="radio" name="radio1" value="dispensed" id="dispensed-radio" class="custom-control-input">
                                    <label for="dispensed-radio" class="custom-control-label"> Dispensed </label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline" onclick="getMedicineList()">
                                    <input type="radio" name="radio1" value="hibill" id="hibill-radio" class="custom-control-input" @if(isset($enpatient) && (strtolower($enpatient->fldbillingmode) == 'health insurance' || strtolower($enpatient->fldbillingmode) == 'healthinsurance' || strtolower($enpatient->fldbillingmode) == 'hi')) checked @endif>
                                    <label for="hi-radio" class="custom-control-label"> HI Bill </label>
                                </div>
                                <div class="custom-control custom-checkbox custom-control-inline" onclick="getTPBillList()">
                                    <input type="checkbox" name="tpbill" value="tpbill" id="tpbill" class="custom-control-input">
                                    <label for="" class="custom-control-label"> TP Bill </label>
                                </div>

                                {{-- <input type="hidden" name="js-dispensing-billingmode-select" id="js-dispensing-billingmode-select" value="{{(isset($enpatient) && $enpatient->fldbillingmode !='') ? $enpatient->fldbillingmode : ''}}"> --}}

                            </div>
                            <div class="col-lg-5">

                                <div id="js-dispensing-module-div">
                                    <div class="custom-control custom-radio custom-control-inline js-dispensing-department-checkbox">
                                        <input type="radio" name="radio" value="OP" class="custom-control-input" readonly {{ $department == 'Outpatient' || $department == '' ? 'checked' : '' }}>
                                        <label class="custom-control-label"> Outpatient </label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline js-dispensing-department-checkbox">
                                        <input type="radio" name="radio" value="IP" class="custom-control-input" readonly {{ $department == 'Inpatient' ? 'checked' : '' }}>
                                        <label class="custom-control-label"> Inpatient </label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline js-dispensing-department-checkbox">
                                        <input type="radio" name="radio" value="ER" class="custom-control-input" readonly {{ $department == 'Emergency' ? 'checked' : '' }}>
                                        <label class="custom-control-label"> Emergency </label>
                                    </div>

                                </div>
                            </div>


                            <div class="col-lg-3">
                                Last Date:
                            </div>
                        </div>


                        <div class="form-group form-row mt-3">


                            <div class="col-lg-5">
                                @if (Options::get('medicine_by_category') != 'No')
                                    <div class="custom-control custom-radio custom-control-inline" onclick="getMedicineList()">
                                        <input type="radio" name="medcategory" value="Medicines" class="custom-control-input" checked id="js-dispensing-medicines-radio">
                                        <label for="js-dispensing-medicines-radio" class="custom-control-label"> Medicines </label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline" onclick="getMedicineList()">
                                        <input type="radio" name="medcategory" value="Surgicals" class="custom-control-input" id="js-dispensing-surgicals-radio">
                                        <label for="js-dispensing-surgicals-radio" class="custom-control-label"> Surgicals </label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline" onclick="getMedicineList()">
                                        <input type="radio" name="medcategory" value="Extra Items" class="custom-control-input" id="js-dispensing-extraitems-radio">
                                        <label for="js-dispensing-extraitems-radio" class="custom-control-label"> Extra Items </label>
                                    </div>
                                @endif
                            </div>


                            <div class="col-lg-6">

                                <div class="custom-control custom-radio custom-control-inline" onclick="getMedicineList()">
                                    <input type="radio" name="orderBy" value="generic" class="custom-control-input" checked>
                                    <label class="custom-control-label"> Generic </label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline" onclick="getMedicineList()">
                                    <input type="radio" name="orderBy" value="brand" class="custom-control-input" >
                                    <label class="custom-control-label"> Brand </label>
                                </div>
                            </div>
                        </div>


                        <div class="form-group form-row">
                            <div class="col-sm-5">
                                <label>Particulars</label>
                                <input type="hidden" id="js-dispensing-consultant-hidden-input">
                                <input type="hidden" id="js-dispensing-free-consultant-hidden-input">
                                <input type="hidden" id="js-dispensing-free-consultant-nmc-hidden-input">
                                <select class="form-control select2" id="js-dispensing-medicine-input">
                                    <option value="">--Select--</option>
                                    @foreach($medicines as $medicine)
                                        @php
                                            $medname = (isset($medicine->fldstockid) and $medicine->fldstockid !='') ? $medicine->fldstockid : $medicine->fldbrand;
                                        @endphp
                                        <option value="{{ $medicine->fldstockid }}" data-route="{{ $medicine->fldroute }}" data-flditemtype="{{ $medicine->fldcategory }}" data-fldnarcotic="{{ $medicine->fldnarcotic }}" data-fldpackvol="{{ $medicine->fldpackvol }}" data-fldvolunit="{{ $medicine->fldvolunit }}" data-fldstockno="{{ $medicine->fldstockno }}"
                                                fldqty="{{ $medicine->fldqty }}">{{ $medicine->fldroute }} | {{ $medname }} | {{ $medicine->fldbatch }} | {{ explode(' ', $medicine->fldexpiry)[0] }} | QTY {{ $medicine->fldqty }} | Rs. {{ $medicine->fldsellpr }}</option>
                                        }
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-1">

                                <label>DoseUnit</label>
                                <input type="text" class="form-control" id="js-dispensing-doseunit-input"/>

                            </div>


                            <!-- <div class="col-sm-1">
                                <label>Qty:</label>
                                <input type="text" class="form-control" placeholder="0" />
                            </div> -->

                            <div class="col-sm-6">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label>Frequency</label>
                                        <select class="form-control" id="js-dispensing-frequency-select" {{ (Options::get('dispensing_freq_dose') == 'Auto') ? 'disabled' : '' }}>
                                            <option value="">--Select--</option>
                                            @foreach($frequencies as $frequency)
                                                <option value="{{ $frequency }}" {{ (Options::get('dispensing_freq_dose') == 'Auto' && $frequency == '1') ? 'selected' : '' }}>{{ $frequency }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <label>Duration</label>
                                        <input type="text" class="form-control" placeholder="0" id="js-dispensing-duration-input" {!! Options::get('dispensing_freq_dose')=='Auto' ? "disabled value='1'" : '' !!} />
                                    </div>

                                    <div class="col-sm-2">
                                        <label>Qty</label>
                                        <input type="number" class="form-control" id="js-dispensing-quantity-input" min="0" oninput="validity.valid||(value=value.replace(/\D+/g, ''))"/>
                                        <input type="hidden" class="form-control" id="js-dispensing-flditemtype-input" />
                                    </div>

                                    <div class="col-sm-2">
                                        <label class=""> Amt:</label>
                                        <input type="text" class="form-control" placeholder="0" id="js-dispensing-amt-input" readonly=""/>
                                    </div>

                                    <div class="col-sm-1">
                                        <button class="btn btn-primary" id="js-dispensing-add-btn"><i class="fa fa-plus" aria-hidden="true"></i></button>
                                        <input type="hidden" name="surgicaldiscount" id="surgicaldiscount" value="{{ (isset($surgicaldiscount) and $surgicaldiscount!='') ? $surgicaldiscount : ''}}">
                                        <input type="hidden" name="medicinediscount" id="medicinediscount" value="{{ (isset($meddiscount) and $meddiscount!='') ? $meddiscount : ''}}">
                                        <input type="hidden" name="extradiscount" id="extradiscount" value="{{ (isset($extradiscount) and $extradiscount !='') ? $extradiscount : ''}}">
                                        <input type="hidden" name="convergent_payment_status" id="convergent_payment_status" value="{{Options::get('convergent_payment_status')}}">
                                        <input type="hidden" name="generate_qr" id="generate_qr" value="{{Options::get('generate_qr')}}">
                                    </div>
                                </div>
                            </div>

                        </div>



                    </div>


                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <div class="res-table">
                            <table class="table table-bordered table-hover table-striped" id="dispensing_medicine_list">
                                <thead class="thead-light">
                                <tr>
                                    <th>&nbsp;</th>
                                    <th>Route</th>
                                    <th>Particulars</th>
                                    <th>Expiry</th>
                                    <th>Dose</th>
                                    <th>Freq</th>
                                    <th>Day</th>

                                    <th>QTY</th>
                                    <th>Rate</th>
                                    <th>User</th>
                                    <th>SubTotal</th>
                                    <th>VAT</th>
                                    <th>Discount</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                                </thead>

                                <tbody id="js-dispensing-medicine-tbody">
                                @if(isset($allMedicines))
                                    @foreach($allMedicines as $medicine)
                                        @if(isset($medicine->medicineBySetting) and !is_null($medicine->medicineBySetting))
                                            @php
                                                  if(strtolower($billingmode) == 'health insurance' or strtolower($billingmode) == 'healthinsurance' or strtolower($billingmode) == 'hi'){
                                                    $ftotal = ($medicine->fldqtydisp)*(($medicine->medicineByStockRate) ? $medicine->medicineByStockRate->fldrate : '0');
                                                }else{
                                                    $ftotal = ($medicine->fldqtydisp)*(($medicine->medicineBySetting) ? $medicine->medicineBySetting->fldsellpr : '0');
                                                }
                                                //$ftotal = ($medicine->fldqtydisp)*(($medicine->medicineBySetting) ? $medicine->medicineBySetting->fldsellpr : '0');
                                            @endphp

                                            @if($medicine->flditemtype == 'Surgicals')
                                                @php
                                                    $damount = $medicine->flddiscper;
                                                    $fldtotal = ($ftotal)-(($medicine->flddiscper/100)*$ftotal);
                                                @endphp
                                            @elseif($medicine->flditemtype == 'Medicines')
                                                @php
                                                    $damount = $medicine->flddiscper;
                                                    $fldtotal = ($ftotal)-(($medicine->flddiscper/100)*$ftotal);
                                                @endphp
                                            @elseif($medicine->flditemtype == 'Extra Items')
                                                @php
                                                    $damount = $medicine->flddiscper;
                                                    $fldtotal = ($ftotal)-(($medicine->flddiscper/100)*$ftotal);
                                                @endphp
                                            @else
                                                @php
                                                    $damount = 0;
                                                    $fldtotal = $ftotal;
                                                @endphp
                                            @endif

                                            @php
                                                $subtotal += $ftotal;
                                                $nettotal += $fldtotal;
                                                $totalDiscount += (($damount/100)*$ftotal);
                                                $totalVat += $medicine->fldtaxamt;
                                            @endphp
                                            <tr data-fldid="{{ $medicine->fldid }}" data-taxpercentage="{{ $medicine->fldtaxper }}" data-stocknumber="{{ $medicine->medicineBySetting->fldstockno }}">
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $medicine->fldroute }}</td>
                                                <td>{{ $medicine->flditem }}</td>
                                                <td>{{ ($medicine->medicineBySetting) ? $medicine->medicineBySetting->fldexpirydateonly : '' }}</td>
                                                <td>{{ $medicine->flddose }}</td>
                                                <td>{{ $medicine->fldfreq }}</td>
                                                <td>{{ $medicine->flddays }}</td>

                                                <td>{{ $medicine->fldqtydisp }}</td>
                                                <td>{{ ($medicine->medicineBySetting) ? \App\Utils\Helpers::numberFormat($medicine->medicineBySetting->fldsellpr) : '0' }}</td>
                                                <td>{{ $medicine->flduserid_order }}</td>
                                                <td>{{ \App\Utils\Helpers::numberFormat($ftotal) }}</td>
                                                <td>{{ \App\Utils\Helpers::numberFormat($medicine->fldtaxamt) }}</td>
                                                <td>{{\App\Utils\Helpers::numberFormat(($medicine->flddiscper/100)*$ftotal)}}</td>
                                                <td>{{ \App\Utils\Helpers::numberFormat(($ftotal-$medicine->flddiscamt+$medicine->fldtaxamt)) }}</td>
                                                <td>
                                                    <a href="javascript:void(0);" class="btn btn-primary" onclick="editMedicine({{$medicine->fldid}},{{$medicine->medicineBySetting->fldstockno}})"><i class="fa fa-edit"></i></a>
                                                    <a href="javascript:void(0);" class="btn btn-outline-primary  js-dispensing-alternate-button"><i class="fa fa-reply"></i></a>
                                                    <a href="javascript:void(0);" class="btn btn-danger delete"><i class="fa fa-trash"></i></a>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <div class="form-group form-row">

                            <div class="col-sm-6">
                                @if(isset($totalDepositAmountReceived) && $totalDepositAmountReceived)
                                    <p><strong>Deposit Amount</strong> Rs.<span class="depAmount">{{ \App\Utils\Helpers::numberFormat($totalDepositAmountReceived??'') }}
                                                                                    </span></p>
                                @else
                                    <p><strong>Deposit Amount</strong> Rs.0.00 /-</p>
                                @endif
                                @if(isset($totalTPAmountReceived) && $totalTPAmountReceived)
                                    <p><strong>TP Bill Amount</strong> Rs. <span class="tpAmount">{{ \App\Utils\Helpers::numberFormat($totalTPAmountReceived??'') }}</span>
                                        <a href="{{ route('previous.tp.amount', $enpatient->fldencounterval) }}" class="btn btn-primary" target="_blank">Previous</a>
                                    </p>
                                @else
                                    <p><strong>TP Bill Amount</strong> Rs. 0.00/-</p>
                                @endif

                                @if(isset($remaining_deposit) && $remaining_deposit)
                                    <p><strong>Remaining Amount</strong> Rs. <span class="remainingAmount">{{ \App\Utils\Helpers::numberFormat($remaining_deposit??'') }}</span>

                                    </p>
                                @else
                                    <p><strong>Remaining Amount</strong> Rs. 0.00/-</p>
                                @endif
                                <label><b> Payment Mode:</b></label>
                                <div class="bak-payment p-2">
                                    <div class="form-row">
                                        <div class="col-sm-3  pay-rad" id="cash_payment" onclick="getRadioFunction('Cash')">
                                            <div class="custom-control custom-radio custom-control-inline" >
                                                <input type="radio" id="defaultforcash" name="payment_mode" class="custom-control-input payment_mode" value="Cash" checked>
                                                <label class="custom-control-label" for="defaultforcash"> Cash</label>
                                            </div>
                                             <div class="img-ms-form">
                                                <img src="{{ asset('new/images/cash-2.png')}}"   class="img-ms-form" alt="">
                                            </div>
                                        </div>
                                        <div class="col-sm-3 pay-rad" id="credit_payment" onclick="getRadioFunction('Credit')">
                                            <div class="custom-control custom-radio custom-control-inline" >
                                                <input type="radio" id="defaultforcredit" name="payment_mode" class="custom-control-input payment_mode" value="Credit">
                                                <label class="custom-control-label" for="defaultforcredit"> Credit </label>
                                            </div>
                                             <div class="img-ms-form">
                                                <img src="{{ asset('new/images/credit-3.png')}}"   class="img-ms-form" alt="">
                                            </div>
                                        </div>
                                        <div class="col-sm-3 pay-rad" id="card_payment" onclick="getRadioFunction('Card')">
                                            <div class="custom-control custom-radio custom-control-inline" >
                                                <input type="radio" id="defaultforcard" name="payment_mode" class="custom-control-input payment_mode" value="Card">
                                                <label class="custom-control-label " for="defaultforcard"> Card </label>
                                            </div>
                                            <div class="mt-2 img-ms-form">
                                                <img src="{{ asset('new/images/swipe2.png')}}"   class="img-ms-form" alt="">
                                            </div>
                                        </div>
                                        <div class="col-sm-3 pay-rad" id="fonepay_payment" onclick="getRadioFunction('Fonepay')">
                                            <div class="custom-control custom-radio custom-control-inline" >
                                                <input type="radio" id="defaultforfonepay" name="payment_mode" class="custom-control-input payment_mode" value="Fonepay">
                                                <label class="custom-control-label" for="defaultforfonepay">Fonepay </label>
                                            </div>
                                            <div class="img-ms-form">
                                                <img src="{{ asset('new/images/fonepay_logo.png')}}" class="ml-4"   class="img-ms-form" alt="">
                                            </div>

                                        </div>


                                    </div>
                                </div>
                                <!-- <select name="payment_mode" id="payment_mode" class="form-control">
                                    <option value="">Select Payment Mode</option>

                                    <option value="Cash" selected="" >Cash</option>
                                    <option value="Credit">Credit</option>
                                    <option value="Cheque">Cheque</option>
                                    <option value="Other">Other</option>

                                </select> -->
                                <div class="form-group" id="other_reason">
                                    <label class="col-sm-5">Other Reason</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="other_reason" placeholder="Reason" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label><b>Remarks</b></label>
                                    <textarea name="" id="js-dispensing-remarks-textarea" class="form-control" rows="6"></textarea>
                                </div>
                                <div class="form-group" id="expected_date">
                                    <label class="col-sm-5">Expected Payment Date</label>
                                    <div class="col-sm-7">
                                        <div class="input-group">
                                            <input type="date" name="expected_payment_date" id="expected_payment_date" placeholder="DD/MM/YYY" class="form-control" value="{{ date('Y-m-d') }}">
                                            {{--<div class="input-group-append">
                                                    <div class="input-group-text"><i class="ri-calendar-2-fill"></i></div>
                                                </div>--}}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" id="cheque_number">
                                    <label class="col-sm-5">Cheque Nmber</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="cheque_number" id="cheque_number_input" placeholder="Cheque Number" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group" id="bankname">
                                    <label class="col-sm-5">Bank</label>
                                    <div class="col-sm-7">
                                        <select name="bank_name" id="bank-name" class="form-control">
                                            <option value="">Select Bank</option>
                                            @if(isset($banks) and count($banks) > 0)
                                                @forelse($banks as $bank)
                                                    <option value="{{ $bank->fldbankname }}">{{ $bank->fldbankname }}</option>
                                                @empty

                                                @endforelse

                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group" id="office_name">
                                    <label class="col-sm-5">Office Name</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="office_name" placeholder="Office Name" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <table class="table table-borderless total-detail">
                                    <tbody>
                                    <tr>
                                        <th>SubTotal:</th>
                                        <th class="">
                                            <input type="text" id="js-dispensing-subtotal-input" class="form-control text-right" value="{{ \App\Utils\Helpers::numberFormat($subtotal) }}" readonly>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>Discount Type:</th>
                                        <th class="text-right" id="discount-total">
                                            <select name="discount_type_change" id="discount_type_change" class="form-control">
                                                <option value="">Select Type</option>
                                                <option value="no_discount">No Discount</option>
                                                <option value="fixed">Fixed</option>
                                                <option value="percentage">Percentage</option>
                                            </select>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>Discount Amount:</th>
                                        <th class="" id="discount-total">
                                            <input type="text" id="js-dispensing-discounttotal-input" readonly class="form-control text-right" value="{{ \App\Utils\Helpers::numberFormat($totalDiscount)}}" placeholder="0.00">
                                        </th>
                                    </tr>
                                    @php
                                        $discountpercent = 0;
                                    @endphp
                                    @if(isset($subtotal) and $subtotal !=0)
                                        @php
                                            $discountpercent = (($totalDiscount*100)/$subtotal);
                                        @endphp
                                    @endif
                                    <tr id="discount-percent-container">
                                        <th>Discount Percent:</th>
                                        <th>
                                            <div class="input-group">
                                                <input type="text" id="js-dispensing-discount-input" class="form-control text-right" value="{{ \App\Utils\Helpers::numberFormat($discountpercent)}}">
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="basic-addon2">%</span>
                                                </div>
                                            </div>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>Tax:</th>
                                        <th class="">
                                            <input type="text" id="js-dispensing-totalvat-input" class="form-control text-right" value="{{ \App\Utils\Helpers::numberFormat($totalVat) }}" readonly>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>Total:</th>
                                        <th>
                                            <input type="text" id="js-dispensing-nettotal-input" class="form-control text-right" value="{{\App\Utils\Helpers::numberFormat($nettotal+$totalVat) }}" readonly>

                                        </th>
                                    </tr>
                                    <tr>
                                        <th>Tender:</th>
                                        <th>
                                            <input type="text" id="tender-amount" class="form-control text-right" value="0.00">
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>Return:</th>
                                        <th>
                                            <input type="text" id="return-amount" class="form-control text-right" value="0.00" readonly>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-right">
                                            @if(isset($enpatient))
                                                @if(Options::get('convergent_payment_status') && Options::get('convergent_payment_status') == 'active' )
                                                    <a href="{{ route('convergent.payments', $enpatient->fldencounterval) }}" class="btn btn-primary float-right fonepay-button-save">Fonepay</a>
                                                @endif
                                            @endif
                                            <input type="hidden" name="fonepaylog_id" value="no" class="js-fonepaylog-id-hidden" value="">
                                            @if($department == 'Inpatient')
                                                <button class="btn btn-primary btn-action" id="js-dispensing-tp-bill-btn" >
                                                    <i class="fa fa-print" aria-hidden="true"></i>&nbsp;TP Bill
                                                </button>
                                                <button class="btn btn-primary btn-action" id="js-dispensing-print-btn" >
                                                    <i class="fa fa-print" aria-hidden="true"></i>&nbsp;Print
                                                </button>
                                            @else
                                                <button class="btn btn-primary btn-action" id="js-dispensing-print-btn">
                                                    <i class="fa fa-print" aria-hidden="true"></i>&nbsp;Print
                                                </button>
                                            @endif
                                            <button class="btn btn-outline-primary btn-action" id="js-dispensing-clear-btn">
                                                <i class="fa fa-times" aria-hidden="true"></i>&nbsp;Clear
                                            </button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="modal fade show" id="js-dispensing-medicine-modal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="text-align: center;">Select Particulars</h5>
                    <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div>
                        <input type="text" class="form-control" id="js-dispensing-flditem-input-modal" style="width: 80%;float: left;">
                        <button style="float: left;" class="btn btn-sm-in btn-primary" type="button" id="js-dispensing-add-btn-modal">
                            <i class="fa fa-plus"></i>&nbsp; Save
                        </button>
                    </div>
                    <div style="overflow-y: auto;max-height: 400px;width: 100%;">
                        <table class="table table-bordered table-hover table-striped">
                            <thead>
                            <tr>
                                <th>Particulars</th>
                                <th>&nbsp;</th>
                                <th>Rate</th>
                                <th>QTY</th>
                                <th>Expiry</th>
                            </tr>
                            </thead>
                            <tbody id="js-dispensing-table-modal"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade show" id="js-dispensing-info-modal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="text-align: center;" id="js-dispensing-modal-title-modal"></h5>
                    <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" id="js-dispensing-modal-body-modal"></div>
            </div>
        </div>
    </div>

    <div class="modal fade show" id="js-dispensing-online-request-modal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="text-align: center;">Online Request</h5>
                    <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="js-dispensing-or-form-modal">
                        <div class="row">
                            <div class="col-sm-3">
                                <label>Department</label>
                                <select class="form-control" id="js-dispensing-or-compid-modal" name="compid">
                                    <option value="">%</option>
                                    @foreach($computers as $computer)
                                        <option value="{{ $computer }}">{{ $computer }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <label>From Date</label>
                                <input type="text" class="form-control nepaliDatePicker" id="js-dispensing-or-fromdate-modal" value="{{ $today }}" name="fromdate">
                            </div>
                            <div class="col-sm-2">
                                <label>To Date</label>
                                <input type="text" class="form-control nepaliDatePicker" id="js-dispensing-or-todate-modal" value="{{ $today }}" name="todate">
                            </div>
                            <div class="col-sm-2">
                                <label>EncounterId</label>
                                <input type="text" class="form-control" id="js-dispensing-or-encid-modal" name="encid">
                            </div>
                            <div class="col-sm-3">
                                <label>Name</label>
                                <input type="text" class="form-control" id="js-dispensing-or-name-modal" name="name">
                            </div>
                            <div class="col-sm-2">
                                <button style="float: left;" class="btn btn-sm-in btn-primary" type="button" id="js-dispensing-or-refresh-modal">
                                    <i class="fa fa-sync"></i>&nbsp; Refresh
                                </button>
                            </div>
                        </div>
                    </form>
                    <div style="overflow-y: auto;max-height: 400px;width: 100%;">
                        <table class="table table-bordered table-hover table-striped">
                            <thead>
                            <tr>
                                <th>&nbsp;</th>
                                <th>EncounterId</th>
                                <th>Name</th>
                                <th>Location</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody id="js-dispensing-or-table-modal"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('frontend.common.create-new-patient')

    @include('dispensar::modal.update-medicine-detail')

    @include('dispensar::layouts.consultant')
    @include('dispensar::modal.dispensed-medicine-modal')
    @include('dispensar::modal.tpbill-modal')
    @include('dispensar::modal.update-tp-item')
@endsection

@push('after-script')
    <script src="{{asset('js/dispensing_form.js')}}"></script>
    @if($department == 'Inpatient')
        <script type="text/javascript">
            $(document).ready(function(){
                hideAll();
                setTimeout(function () {
                    $("#bank-name").select2();
                    $('#bank-name').next(".select2-container").hide();
                }, 1500);
                /*On click payment modes*/

                $('input[type=radio][name=payment_mode]').change(function() {

                   if (this.value === "Cash") {
                        hideAll();
                        $('#cash_payment').addClass('checked-bak');
                        $('#credit_payment').removeClass('checked-bak');
                        $('#card_payment').removeClass('checked-bak');
                        $('#fonepay_payment').removeClass('checked-bak');
                        $('#js-dispensing-tp-bill-btn').hide();
                        $('.payment-save-done').show();
                        $('#js-dispensing-print-btn').show();
                    } else if (this.value === "Credit") {
                        hideAll();
                        $('#cash_payment').removeClass('checked-bak');
                        $('#credit_payment').addClass('checked-bak');
                        $('#card_payment').removeClass('checked-bak');
                        $('#fonepay_payment').removeClass('checked-bak');
                        $('#expected_date').show();
                        $('.payment-save-done').show();
                        $('#js-dispensing-tp-bill-btn').show();
                        $('#js-dispensing-print-btn').hide();


                    } else if (this.value === "Card") {
                        hideAll();
                        $('#cash_payment').removeClass('checked-bak');
                        $('#credit_payment').removeClass('checked-bak');
                        $('#card_payment').addClass('checked-bak');
                        $('#fonepay_payment').removeClass('checked-bak');
                        // $('#cheque_number').show();
                        // $("#payment_mode_party").show();
                        /*$("#agent_list").show();*/
                        // $('#bankname').show();
                        // $('#bank-name').next(".select2-container").show();
                        $('.payment-save-done').show();
                        $('#js-dispensing-print-btn').show();
                    } else if (this.value === "Fonepay") {
                        hideAll();
                        $('#cash_payment').removeClass('checked-bak');
                        $('#credit_payment').removeClass('checked-bak');
                        $('#card_payment').removeClass('checked-bak');
                        $('#fonepay_payment').addClass('checked-bak');
                        // fonepay-button-save
                        // $('.fonepay-button-save').show();
                        $('#js-dispensing-print-btn').show();
                        var convergent = $('#convergent_payment_status').val();
                        var encounter = $('#fldencounterval').val();
                        var generateQr = $('#generate_qr').val();
                        if(encounter !='' && convergent !='' && convergent == 'active' && generateQr == 'yes'){
                            var totalamount = $('#js-dispensing-nettotal-input').val();
                            if(totalamount == '' || totalamount <= 0){
                                showAlert('Amount not available');
                                return false;
                            }
                            fonepayQrGenerate(encounter);
                        }
                    } else if (this.value === "Other") {
                        hideAll();
                        $('#other_reason').show();
                        $('.payment-save-done').show();
                        $('#js-dispensing-print-btn').show();
                    }else{
                        hideAll();
                        $('#cash_payment').addClass('checked-bak');
                        $('#credit_payment').removeClass('checked-bak');
                        $('#card_payment').removeClass('checked-bak');
                        $('#fonepay_payment').removeClass('checked-bak');
                    }

                });

                function hideAll() {
                    // $('#payment_mode_party').hide();
                    $('#office-name').hide();
                    $('#bank-name').next(".select2-container").hide();
                    $('#bankname').hide();
                    /*$('#agent_list').hide();*/
                    $('#expected_date').hide();
                    $('#cheque_number').hide();
                    $('#office_name').hide();
                    $('#other_reason').hide();
                    $('.fonepay-button-save').hide();
                    $('#js-dispensing-tp-bill-btn').hide();

                }
            })
            function getRadioFunction(value){
                if (value == "Cash") {
                    hideAll();
                    $('#cash_payment').addClass('checked-bak');
                    $('#credit_payment').removeClass('checked-bak');
                    $('#card_payment').removeClass('checked-bak');
                    $('#fonepay_payment').removeClass('checked-bak');

                    $('#js-dispensing-tp-bill-btn').hide();
                    $('.payment-save-done').show();
                    // $('#js-dispensing-tp-bill-btn').hide();
                    $('#js-dispensing-print-btn').show();
                } else if (value == "Credit") {
                    hideAll();
                    $('#cash_payment').removeClass('checked-bak');
                    $('#credit_payment').addClass('checked-bak');
                    $('#card_payment').removeClass('checked-bak');
                    $('#fonepay_payment').removeClass('checked-bak');
                    $('#expected_date').show();
                    $('.payment-save-done').show();
                    $('#js-dispensing-tp-bill-btn').show();
                    $('#js-dispensing-print-btn').hide();


                } else if (value == "Card") {
                    hideAll();
                    $('#cash_payment').removeClass('checked-bak');
                    $('#credit_payment').removeClass('checked-bak');
                    $('#card_payment').addClass('checked-bak');
                    $('#fonepay_payment').removeClass('checked-bak');
                    // $('#cheque_number').show();
                    // $("#payment_mode_party").show();
                    /*$("#agent_list").show();*/
                    // $('#bankname').show();
                    // $('#bank-name').next(".select2-container").show();
                    $('.payment-save-done').show();
                    $('#js-dispensing-tp-bill-btn').hide();
                    $('#js-dispensing-print-btn').show();
                } else if (value == "Fonepay") {
                    hideAll();
                    $('#cash_payment').removeClass('checked-bak');
                    $('#credit_payment').removeClass('checked-bak');
                    $('#card_payment').removeClass('checked-bak');
                    $('#fonepay_payment').addClass('checked-bak');
                    // fonepay-button-save
                    $('.fonepay-button-save').hide();
                    $('#js-dispensing-tp-bill-btn').hide();
                    $('#js-dispensing-print-btn').show();
                    var convergent = $('#convergent_payment_status').val();
                    var encounter = $('#fldencounterval').val();
                    var generateQr = $('#generate_qr').val();
                    if(encounter !='' && convergent !='' && convergent == 'active' && generateQr == 'yes'){
                        var totalamount = $('#js-dispensing-nettotal-input').val();
                        if(totalamount == '' || totalamount <= 0){
                            showAlert('Amount not available');
                            return false;
                        }
                        fonepayQrGenerate(encounter);
                    }
                } else if (value == "Other") {
                    hideAll();
                    $('#other_reason').show();
                    $('.payment-save-done').show();
                    $('#js-dispensing-print-btn').show();
                    $('#js-dispensing-print-btn').hide();
                }else{
                    hideAll();
                    $('#cash_payment').addClass('checked-bak');
                    $('#credit_payment').removeClass('checked-bak');
                    $('#card_payment').removeClass('checked-bak');
                    $('#fonepay_payment').removeClass('checked-bak');
                    $('#js-dispensing-tp-bill-btn').hide();
                    $('#js-dispensing-print-btn').show();
                }
                // return false;
            }

            function hideAll() {
                // $('#payment_mode_party').hide();
                $('#office-name').hide();
                $('#bank-name').next(".select2-container").hide();
                $('#bankname').hide();
                /*$('#agent_list').hide();*/
                $('#expected_date').hide();
                $('#cheque_number').hide();
                $('#office_name').hide();
                $('#other_reason').hide();
                $('.fonepay-button-save').hide();
                $('#js-dispensing-tp-bill-btn').hide();

            }
        </script>
    @else
        <script type="text/javascript">
            $(document).ready(function(){
                hideAll();
                setTimeout(function () {
                    $("#bank-name").select2();
                    $('#bank-name').next(".select2-container").hide();
                }, 1500);
                /*On click payment modes*/

                $('#payment_mode').on('change', function () {
                    if (this.value === "Cash") {
                        hideAll();

                        $('#js-dispensing-tp-bill-btn').hide();
                        $('.payment-save-done').show();
                        $('#js-dispensing-print-btn').show();
                    } else if (this.value === "Credit") {
                        hideAll();
                        $('#expected_date').show();
                        $('.payment-save-done').show();
                        $('#js-dispensing-print-btn').show();


                    } else if (this.value === "Cheque") {
                        hideAll();
                        $('#cheque_number').show();
                        // $("#payment_mode_party").show();
                        /*$("#agent_list").show();*/
                        $('#bankname').show();
                        $('#bank-name').next(".select2-container").show();
                        $('.payment-save-done').show();
                        $('#js-dispensing-print-btn').show();
                    } else if (this.value === "Fonepay") {
                        hideAll();
                        // fonepay-button-save
                        $('.fonepay-button-save').show();
                        $('#js-dispensing-print-btn').hide();
                    } else if (this.value === "Other") {
                        hideAll();
                        $('#other_reason').show();
                        $('.payment-save-done').show();
                        $('#js-dispensing-print-btn').show();
                    }
                });

                function hideAll() {
                    // $('#payment_mode_party').hide();
                    $('#office-name').hide();
                    $('#bank-name').next(".select2-container").hide();
                    $('#bankname').hide();
                    /*$('#agent_list').hide();*/
                    $('#expected_date').hide();
                    $('#cheque_number').hide();
                    $('#office_name').hide();
                    $('#other_reason').hide();
                    $('.fonepay-button-save').hide();

                }
            })
            function getRadioFunction(value){
                if (value == "Cash") {
                    hideAll();
                    $('#cash_payment').addClass('checked-bak');
                    $('#credit_payment').removeClass('checked-bak');
                    $('#card_payment').removeClass('checked-bak');
                    $('#fonepay_payment').removeClass('checked-bak');
                    $('#js-dispensing-tp-bill-btn').hide();
                    $('.payment-save-done').show();
                    $('#js-dispensing-print-btn').show();
                } else if (value == "Credit") {
                    hideAll();
                    $('#cash_payment').removeClass('checked-bak');
                    $('#credit_payment').addClass('checked-bak');
                    $('#card_payment').removeClass('checked-bak');
                    $('#fonepay_payment').removeClass('checked-bak');
                    $('#expected_date').show();
                    $('.payment-save-done').show();
                    $('#js-dispensing-print-btn').show();


                } else if (value == "Card") {
                    hideAll();
                    $('#cash_payment').removeClass('checked-bak');
                    $('#credit_payment').removeClass('checked-bak');
                    $('#card_payment').addClass('checked-bak');
                    $('#fonepay_payment').removeClass('checked-bak');
                    // $('#cheque_number').show();
                    // $("#payment_mode_party").show();
                    /*$("#agent_list").show();*/
                    // $('#bankname').show();
                    // $('#bank-name').next(".select2-container").show();
                    $('.payment-save-done').show();
                    $('#js-dispensing-print-btn').show();
                } else if (value == "Fonepay") {
                    hideAll();
                    $('#cash_payment').removeClass('checked-bak');
                    $('#credit_payment').removeClass('checked-bak');
                    $('#card_payment').removeClass('checked-bak');
                    $('#fonepay_payment').addClass('checked-bak');
                    // fonepay-button-save
                    $('.fonepay-button-save').hide();
                    $('#js-dispensing-print-btn').show();
                    var convergent = $('#convergent_payment_status').val();
                    var encounter = $('#fldencounterval').val();
                    var generateQr = $('#generate_qr').val();

                    if(encounter !='' && convergent !='' && convergent == 'active' && generateQr == 'yes'){
                        var totalamount = $('#js-dispensing-nettotal-input').val();
                        if(totalamount == '' || totalamount <= 0){
                            showAlert('Amount not available');
                            return false;
                        }
                        fonepayQrGenerate(encounter);
                    }
                } else if (value == "Other") {
                    hideAll();
                    $('#other_reason').show();
                    $('.payment-save-done').show();
                    $('#js-dispensing-print-btn').show();
                }else{
                    hideAll();
                    $('#cash_payment').addClass('checked-bak');
                    $('#credit_payment').removeClass('checked-bak');
                    $('#card_payment').removeClass('checked-bak');
                    $('#fonepay_payment').removeClass('checked-bak');
                    $('#js-dispensing-print-btn').show();
                }
                // return false;
            }
            function hideAll() {
                    // $('#payment_mode_party').hide();
                    $('#office-name').hide();
                    $('#bank-name').next(".select2-container").hide();
                    $('#bankname').hide();
                    /*$('#agent_list').hide();*/
                    $('#expected_date').hide();
                    $('#cheque_number').hide();
                    $('#office_name').hide();
                    $('#other_reason').hide();
                    $('.fonepay-button-save').hide();

                }
        </script>
    @endif
    <script type="text/javascript">
        function fonepayQrGenerate(encounter) {
                let route = "{!! route('convergent.payments.dispensing') !!}";
                $.ajax({
                    url: route,
                    type: "POST",
                    data: { "total": $('#js-dispensing-nettotal-input').val(),encounter:$('#fldencounterval').val(), "_token": "{{ csrf_token() }}" },
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
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#js-dispensing-quantity-input').keyup(function () {
            if (!this.value.match(/^([0-9]{0,3})$/)) {
                this.value = this.value.replace(/[^0-9]/g, '').substring(0,2);
            }
        });

         $(window).on('load', function () {
            var billingmode = $('#billingmode').val();

            if(billingmode.toLowerCase() == 'health insurance' || billingmode.toLowerCase() == 'hi' || billingmode.toLowerCase() == 'healthinsurance' ){
                $('#card_payment').hide();
                $('#fonepay_payment').hide();
            }

        });



            // var redirect_to_dispensing =true;
            // // console.log($('#new-user-add-form'))
            $("#new-user-add-form input[name=form_to_redirect]").val('dispensing');

            var billingmode = $('#js-dispensing-billingmode-select').val();
            //alert(billingmode);
            if(billingmode.toLowerCase() == 'health insurance' || billingmode.toLowerCase() == 'healthinsurance' || billingmode.toLowerCase() == 'hi'){
                // $('#ordered-radio').click(function(e) {
                  //  e.preventDefault();
                //});

                getMedicineList();

                $('#ordered-radio').attr('disabled','disabled')
                $("#ordered-radio").unbind('click').click( function(){

                    return false;

                });
                //$('#ordered-radio').each(function (){
                //    this.style.pointerEvents = 'none';
                //});

            }

            /*$(document).on('blur', '#js-registration-dob', function (e) {
                var dob = $(this).val().split('-');
                if (dob[1] != undefined && dob[2] != undefined && dob[0] != undefined) {
                    dob = dob[1] + '/' + dob[2] + '/' + dob[0];
                    dob = (new NepaliDateConverter()).bs2ad(dob);
                    var detail = getAgeDetail(dob);

                    $('.js-registration-age').val(detail.age);
                    $('.js-registration-month').val(detail.month);
                    $('.js-registration-day').val(detail.day);
                }
            });*/

            /*$('#js-registration-dob').nepaliDatePicker({
                npdMonth: true,
                npdYear: true,
                npdYearCount: 80,
                disableDaysAfter: 1,
                onChange: function () {
                    var dob = $('#js-registration-dob').val().split('-');
                    dob = dob[1] + '/' + dob[2] + '/' + dob[0];
                    dob = (new NepaliDateConverter()).bs2ad(dob);
                    var detail = getAgeDetail(dob);

                    $('.js-registration-age').val(detail.age);
                    $('.js-registration-month').val(detail.month);
                    $('.js-registration-day').val(detail.day);
                }
            });*/




            var addresses = JSON.parse('{!! \App\Utils\Helpers::getAllAddress() !!}');

            $('#js-registration-country').change(function () {
                getProvinces($(this).val(), null);
            });

            $('#js-registration-province').change(function () {
                getDistrict($(this).val(), null);
            });

            $('#js-registration-district').change(function () {
                getMunicipality($(this).val(), null);
            });

            var provinceSelector = 'js-registration-province';
            var districtSelector = 'js-registration-district';
            var municipalityVdcSelector = 'js-registration-municipality';
            var selectOption = $('<option>', {val: '', text: '--Select--'});

            var districts = null;
            var municipalities = null;

            function getProvinces(id, provinceId) {
                // var activeForm = $('div.tab-pane.fade.active.show');
                $('#' + provinceSelector).empty().append(selectOption.clone());
                $('#' + districtSelector).empty().append(selectOption.clone());
                $('#' + municipalityVdcSelector).empty().append(selectOption.clone());

                if (id == 'Other') {
                    $('#' + provinceSelector).removeAttr('required');
                    $('#' + provinceSelector).closest('div.form-group').find('span.text-danger').text('');
                    $('#' + districtSelector).removeAttr('required');
                    $('#' + districtSelector).closest('div.form-group').find('span.text-danger').text('');
                    $('#' + municipalityVdcSelector).removeAttr('required');
                    $('#' + municipalityVdcSelector).closest('div.form-group').find('span.text-danger').text('');
                    return false;
                } else {
                    $('#' + provinceSelector).attr('required', true);
                    $('#' + provinceSelector).closest('div.form-group').find('span.text-danger').text('*');
                    $('#' + districtSelector).attr('required', true);
                    $('#' + districtSelector).closest('div.form-group').find('span.text-danger').text('*');
                    $('#' + municipalityVdcSelector).attr('required', true);
                    $('#' + municipalityVdcSelector).closest('div.form-group').find('span.text-danger').text('*');
                }

                if (id === "" || id === null) {
                } else {
                    var elems = $.map(addresses, function (d) {
                        if (d.fldprovince == provinceId)
                            districts = d.districts;

                        return $('<option>', {val: d.fldprovince, text: d.fldprovince, selected: (d.fldprovince == provinceId)});
                    });

                    $('#' + provinceSelector).empty().append(selectOption.clone()).append(elems);
                    $('#' + districtSelector).empty().append(selectOption.clone());
                    $('#' + municipalityVdcSelector).empty().append(selectOption.clone());
                }
            }

            function getDistrict(id, districtId) {
                // var activeForm = $('div.tab-pane.fade.active.show');
                if (id === "" || id === null) {
                    $('#' + districtSelector).empty().append(selectOption.clone());
                    $('#' + municipalityVdcSelector).empty().append(selectOption.clone());
                } else {
                    $.map(addresses, function (d) {
                        if (d.fldprovince == id) {
                            districts = d.districts;
                            return false;
                        }
                    });
                    districts = Object.keys(districts).sort().reduce(
                        (obj, key) => {
                            obj[key] = districts[key];
                            return obj;
                        },
                        {}
                    );
                    var elems = $.map(districts, function (d) {
                        return $('<option>', {val: d.flddistrict, text: d.flddistrict, selected: (d.flddistrict == districtId)});
                    });

                    $('#' + districtSelector).empty().append(selectOption.clone()).append(elems);
                    $('#' + municipalityVdcSelector).empty().append(selectOption.clone());
                }
            }

            function getMunicipality(id, municipalityId) {
                // var activeForm = $('div.tab-pane.fade.active.show');
                if (id === "" || id === null) {
                    $('#' + municipalityVdcSelector).empty().append(selectOption.clone());
                } else {
                    $.map(districts, function (d) {
                        if (d.flddistrict == id) {
                            municipalities = d.municipalities;
                            return false;
                        }
                    });

                    municipalities = municipalities.sort();
                    var elems = $.map(municipalities, function (d) {
                        return $('<option>', {val: d, text: d, selected: (d == municipalityId)});
                    });

                    $('#' + municipalityVdcSelector).empty().append(selectOption.clone()).append(elems);
                }
            }
        });

        $(window).ready(function () {
            $('.js-registration-age,.js-registration-month,.js-registration-day').keyup(function (e) {
                var activeForm = $('#new-user-add-form');
                // this.value = this.value.replace(/[^0-9]/g,'');
                // var age = this.value;

                var age = $(activeForm).find('.js-registration-age').val().replace(/[^0-9]/g, '');
                var month = $(activeForm).find('.js-registration-month').val().replace(/[^0-9]/g, '');
                var day = $(activeForm).find('.js-registration-day').val().replace(/[^0-9]/g, '');

                var totalDays = (Number(age) * 364) + (Number(month) * 30) + Number(day);
                var priorDate = new Date().setDate((new Date()).getDate() - totalDays);
                priorDate = new Date(priorDate);

                var dd = priorDate.getDate();
                var mm = priorDate.getMonth() + 1;
                var yyyy = priorDate.getFullYear();
                if (dd < 10)
                    dd = '0' + dd;
                if (mm < 10)
                    mm = '0' + mm;

                var dob = (new NepaliDateConverter()).ad2bs(mm + '/' + dd + '/' + yyyy);
                $(activeForm).find('.js-registration-dob').val(dob);
            });
        })
    </script>
    <script>
        $(window).ready(function () {
            $("#tender-amount").on('blur', function () {
                grandTotal = $("#js-dispensing-nettotal-input").val();
                tender = $("#tender-amount").val();
                returnValue = parseFloat(tender) - parseFloat(grandTotal);
                $("#return-amount").val(numberFormat(returnValue));
            });
        });
    </script>
    <script>

    $('.img-ms-form').click(function() {

    $(this).parent().find('input[type=radio]').prop('checked', true);

    });
    $('.pay-rad').click(function() {

    $(this).closest('.checked-bak').find('input[type=radio]').prop('checked', true);

    });
    </script>
@endpush
