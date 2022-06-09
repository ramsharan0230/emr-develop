@extends('frontend.layouts.master')
<style>

.img-ms-form{
    max-width:80px;
    width:100%;
    height: auto ;
}

</style>
@section('content')
    <div class="container-fluid">
        {{-- inputs for discharge --}}
        <input type="hidden" id="fldencounterval" value="{{ $enpatient->fldencounterval??'' }}">
        {{-- end of inputs for discharge --}}
        @php
            $disableChangeDepartment = ($enpatient && $enpatient->currentDepartment && in_array($enpatient->currentDepartment->fldcateg, ["Consultation", "Emergency"])) && ($enpatient && \Carbon\Carbon::now()->diffInHours($enpatient->flddoa) < 24);
        @endphp

        <div class="row">
            <div class="col-sm-12">
                @include('frontend.common.alert_message')
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header row">
                        <div class="iq-header-title col-6">
                            <h4 class="card-title">
                                Credit Clearance
                            </h4>
                        </div>
                        <div class="col-6 d-flex justify-content-end">
                              <!-- <button class="btn btn-sm btn-primary" id="admission_requests" name="admission_requests">Admission Requests</button> -->
{{--                            <div class="dropdown">--}}
{{--                                <button class="btn btn-primary dropdown-toggle mr-2" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
{{--                                    Admission Requests <b>{{ isset($admissions_requests) ? '('.count($admissions_requests).')' : '' }}</b>--}}
{{--                                </button>--}}
{{--                                <div class="iq-sub-dropdown notification-dropdown dropdown-menu" aria-labelledby="dropdownMenuButton">--}}
{{--                                    <div class="iq-card-body p-0 ">--}}
{{--                                        <div class="bg-primary p-3  border-admit">--}}
{{--                                            <h5 class="mb-0 text-white">All Requests</h5>--}}
{{--                                        </div>--}}
{{--                                        <div class="notify-scroll">--}}
{{--                                            @if (isset($admissions_requests) && $admissions_requests)--}}
{{--                                                @foreach($admissions_requests as $admission)--}}
{{--                                                    <a href="{{ route('depositForm',['encounter_id' => $admission->fldencounterval ??''])}}" class="iq-sub-card">--}}
{{--                                                    <div class="media align-items-center">--}}
{{--                                                        <label for="">{{ $admission->message ?? '' }}</label>--}}
{{--                                                    </div></a>--}}
{{--                                                @endforeach--}}
{{--                                            @else--}}
{{--                                                <a href="#" class="iq-sub-card">--}}
{{--                                                    <div class="media align-items-center">--}}
{{--                                                        <label for="">No admission requests available</label>--}}
{{--                                                    </div>--}}
{{--                                                </a>--}}
{{--                                            @endif--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <button {{ $disableChangeDepartment ? '' : 'disabled' }} data-backdrop="static" data-target="#modal-change-dept" data-toggle="modal" class="btn btn-sm btn-primary mr-2" style="float: right;">Change Department</button>--}}
                            <div >
                                    @if(isset($enpatient) && !empty($enpatient) )
                                        @php
                                            $patientStatusBtnName = "Transfer";
                                            if($enpatient->fldadmission == "Admitted"){
                                                if(isset($enbed)){
                                                    if(isset($enbed->fldbed)){
                                                        $patientStatusBtnName = "Transfer";
                                                    }else{
                                                        $patientStatusBtnName = "Assign Bed";
                                                    }
                                                }else {
                                                    $patientStatusBtnName = "Assign Bed";
                                                }
                                            }else{
                                                $patientStatusBtnName = "Transfer";
                                            }
                                        @endphp
                                        <a id="patientActionButton" href="javascript:;" @if($enpatient->fldadmission == "Registered") disabled @endif data-toggle="modal" class="btn btn-primary btn-sm" data-target="#assign-bed-emergency">{{ $patientStatusBtnName }}</a>


                                    @endif
                                </div>
                        </div>
                      </div>
                    <div class="iq-card-body">
                        <form action="{{ route('deposit.credit') }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group form-row align-items-center er-input">
                                        <label for="" class="col-sm-4">Encounter Id:</label>
                                        <div class="col-sm-3">
                                            {{-- {{ isset($_GET['title'])?$_GET['title']:'' }} --}}
                                            @if(isset($_GET['encounter_id']))
                                                <input type="text" name="encounter_id" id="encounter_id" class="form-control" value="{{ isset($_GET['encounter_id'])?$_GET['encounter_id']:'' }}"/>
                                            @elseif(session()->has('changed_department_encounter_val'))
                                                <input type="text" name="encounter_id" id="encounter_id" class="form-control" value="{{ session()->get('changed_department_encounter_val') }}"/>
                                            @else
                                                <input type="text" name="encounter_id" id="encounter_id" class="form-control" value="{{ $enpatient->fldencounterval??'' }}"/>
                                            @endif
                                            <input type="hidden" id="patient_id" value="{{ $enpatient && $enpatient->patientInfo ? $enpatient->patientInfo->fldpatientval : '' }}"/>
                                        </div>
                                        <div class="col-sm-5">
                                            <button id="show-btn" type="submit" class="btn btn-primary"><i class="fa fa-play" aria-hidden="true"></i>&nbsp;Show</button>
                                            <a href="{{ route('depositForm.reset') }}" class="btn btn-danger"><i class="fa fa-times" aria-hidden="true"></i>&nbsp;Reset</a>
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center er-input">
                                        <label for="" class="col-sm-4">Full Name:</label>
                                        <div class="col-sm-8">
                                            <input type="text" readonly name="fullname" id="fullname" class="form-control" value="{{ $enpatient && $enpatient->patientInfo ? $enpatient->patientInfo->fldfullname :'' }}"/>
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center er-input">
                                        <label for="" class="col-sm-4">Billing mode:</label>
                                        <div class="col-sm-8">
                                            <input type="text" readonly name="billing_mode" id="billing_mode" class="form-control" value="{{ $enpatient  ? $enpatient->fldbillingmode :'' }}"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group form-row align-items-center er-input">
                                        <label for="" class="col-sm-5">Location:</label>
                                        <div class="col-sm-7">
                                            <input type="text" name="location" id="location" class="form-control" value="{{ $enpatient->fldcurrlocat??'' }}" readonly/>
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center er-input">
                                        <label for="" class="col-sm-5">Expense:</label>
                                        <div class="col-sm-7">
                                            <input type="text" name="expense" id="expense" class="form-control" value="{{ $enpatient && $enpatient->patBill? \App\Utils\Helpers::numberFormat(($enpatient->patBill->sum('fldditemamt'))):'' }}" readonly/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-5">Age/Sex:</label>
                                        <div class="col-sm-7">
                                            <input type="text" name="age/sex" id="age/sex" class="form-control" value="{{ $enpatient && $enpatient->patientInfo ? $enpatient->patientInfo->fldagestyle : ""}} {{ $enpatient && $enpatient->patientInfo?$enpatient->patientInfo->fldptsex:'' }}" readonly/>
                                            {{-- <input type="text" name="age/sex" id="age/sex" class="form-control" value="{{ $enpatient && $enpatient->patientInfo?\Carbon\Carbon::parse($enpatient->patientInfo->fldptbirday ? $enpatient->patientInfo->fldptbirday :null)->age:'' }} {{ $enpatient && $enpatient->patientInfo?$enpatient->patientInfo->fldptsex:'' }}" readonly/> --}}
                                        </div>
                                    </div>
                                    <div class="form-group form-row">
                                        <label for="" class="col-sm-5">Payment:</label>
                                        <div class="col-sm-7">
                                            <input type="text" name="payment" id="payment" class="form-control" value="{{ $enpatient && $enpatient->patBillDetails? \App\Utils\Helpers::numberFormat(($enpatient->patBillDetails->sum('fldreceivedamt'))):'' }}" readonly/>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <ul class="nav nav-tabs" id="myTab-1" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="credit-tab" data-toggle="tab" href="#credit" role="tab" aria-controls="credit" aria-selected="false">Credit Clearance</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent-2">
                            <div class="tab-pane fade active show" id="credit" role="tabpanel" aria-labelledby="credit-tab">
                                <div class="row">
                                    <div class="col-sm-5">
                                        <div class="form-group form-row align-items-center er-input">
                                            <label for="" class="col-sm-4">Prev Deposit:</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="js-deposit-form-prevDeposit-input" value="{{ \App\Utils\Helpers::numberFormat(($previousDeposit)) ?: 0 }}" readonly/>
                                            </div>
                                        </div>
                                        <div class="form-group form-row align-items-center er-input">
                                            <label for="" class="col-sm-4">Curr Deposit:</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="js-deposit-form-currDeposit-input" value="0.00" readonly/>
                                            </div>
                                        </div>
                                        {{-- <div class="form-group form-row align-items-center er-input">
                                            <button type="button" id="js-deposit-form-return-btn" class="btn btn-primary"><i class="fa fa-sync" aria-hidden="true"></i>&nbsp;Return Deposit</button>
                                        </div> --}}
                                    </div>

                                    <div class="col-sm-7 border">
                                        <form id="js-deposit-form" method="POST">
                                            <div class="form-group form-row align-items-center er-input mb-3">
                                                <label for="" class="col-sm-3">Deposit For:</label>
                                                <div class="col-sm-3">
                                                    <select class="form-control" name="deposit_for">
                                                        <option value="">--Select--</option>
                                                        @foreach ($deposit_for as $deposit)
                                                            <option value="{{ $deposit }}" @if($deposit == 'Credit Clearance')selected="selected" @endif>{{ $deposit }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <label for="" class="col-sm-3">Receive Amount:</label>
                                                <div class="col-sm-3">
                                                    <input type="number" max="1000000" class="form-control" name="received_amount"/>
{{--                                                    <small>Max amt. is 10 Lakh.</small>--}}
                                                </div>
                                            </div>
                                            <div style="visibility: hidden">
                                                @include('paymentgatewaysetting::payment-option')
                                            </div>

                                            <div class="form-group form-row er-input">
                                                <input type="hidden" name="convergent_payment_status" id="convergent_payment_status" value="{{Options::get('convergent_payment_status')}}">
                                                <input type="hidden" name="generate_qr" id="generate_qr" value="{{Options::get('generate_qr')}}">
                                                <input type="hidden" name="fonepaylog_id" value="no" class="js-fonepaylog-id-hidden" value="">
                                                <div class="col-sm-8">
                                                        <label><b> Payment Mode:</b></label>
                                                        <div class="bak-payment p-2">
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
                                                    </div>
                                                <div class="col-sm-4 text-right">
                                                    <button type="button" id="js-deposit-form-submit-button-credit-clerance" class="btn btn-info"><i class="fa fa-print" aria-hidden="true"></i>&nbsp;Print</button>
<!--                                                    <button type="button" class="btn btn-primary js-deposit-form-sticker-btn"><i class="fa fa-print" aria-hidden="true"></i>&nbsp;Sticker</button>-->
                                                    <button type="button" class="btn btn-danger" onclick="clearForms()"><i class="fa fa-times" aria-hidden="true"></i>&nbsp;Clear</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-sm-5">
                                        <div class="form-group form-row align-items-center er-input">
                                            <h5 class="col-6">Expenses</h5>
                                            <div class="col-sm-6 text-right">
                                                <button class="btn btn-primary" onclick="depositForm.getExpensesList()"><i class="fa fa-sync" aria-hidden="true"></i></button>
                                                <a href="{{ $enpatient?route('depositForm.expenses.pdf', $enpatient->fldencounterval):'' }}" class="btn btn-primary" target="_blank"><i class="fa fa-code" aria-hidden="true"></i></a>
                                            </div>
                                        </div>
                                        <div class="res-table table-sticky-th">
                                            <table class="table table-bordered table-hover table-striped table-sm">
                                                <thead class="thead-light">
                                                <tr>
                                                    <th></th>
                                                    <th>DateTime</th>
                                                    <th>Category</th>
                                                    <th>Particulars</th>
                                                    <th>Rate</th>
                                                    <th>Tax(%)</th>
                                                    <th>Disc(%)</th>
                                                    <th>QTY</th>
                                                    <th>Amount</th>
                                                    <th>Invoice</th>
                                                </tr>
                                                </thead>
                                                <tbody id="expenses-table">
                                                @if (isset($expenses) && $expenses)
                                                    @foreach ($expenses as $expens)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $expens->fldtime }}</td>
                                                            <td>{{ $expens->flditemtype }}</td>
                                                            <td>{{ $expens->flditemname }}</td>
                                                            <td>{{ \App\Utils\Helpers::numberFormat($expens->flditemrate) }}</td>
                                                            <td>{{ \App\Utils\Helpers::numberFormat($expens->fldtaxper) }}</td>
                                                            <td>{{ \App\Utils\Helpers::numberFormat($expens->flddiscper) }}</td>
                                                            <td>{{ $expens->flditemqty }}</td>
                                                            <td>{{ \App\Utils\Helpers::numberFormat($expens->fldditemamt) }}</td>
                                                            <td>{{ $expens->fldbillno }}</td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-sm-7">
                                        <div class="form-group form-row align-items-center er-input">
                                            <h5 class="col-6">Invoices</h5>
                                            <div class="col-sm-6 text-right">
                                                <button class="btn btn-primary" onclick="depositForm.getInvoiceList()"><i class="fa fa-sync" aria-hidden="true"></i></button>
                                                <a href="{{ $enpatient?route('depositForm.invoice.pdf', $enpatient->fldencounterval):'' }}" class="btn btn-primary" target="_blank"><i class="fa fa-code" aria-hidden="true"></i></a>
                                            </div>
                                        </div>
                                        <div class="res-table table-sticky-th">
                                            <table class="table table-bordered table-hover table-striped table-sm">
                                                <thead class="thead-light">
                                                <tr>
                                                    <th></th>
                                                    <th>DateTime</th>
                                                    <th>Invoice</th>
                                                    <th>ItemAMT</th>
                                                    <th>TaxAMT</th>
                                                    <th>DiscAMT</th>
                                                    <th>RecvAMT</th>
                                                    <th>CurDeposit</th>
                                                    <th>Type</th>
                                                </tr>
                                                </thead>
                                                <tbody id="invoice-table">
                                                @if (isset($invoices) && $invoices)
                                                    @foreach ($invoices as $invoice)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $invoice->fldtime }}</td>
                                                            <td>{{ $invoice->fldbillno }}</td>
                                                            <td>{{ \App\Utils\Helpers::numberFormat($invoice->flditemamt) }}</td>
                                                            <td>{{ \App\Utils\Helpers::numberFormat($invoice->fldtaxamt) }}</td>
                                                            <td>{{ \App\Utils\Helpers::numberFormat($invoice->flddiscountamt) }}</td>
                                                            <td>{{ \App\Utils\Helpers::numberFormat($invoice->fldreceivedamt) }}</td>
                                                            <td>{{ \App\Utils\Helpers::numberFormat($invoice->fldcurdeposit) }}</td>
                                                            <td>{{ $invoice->fldbilltype }}</td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group form-row align-items-center er-input">
                                            <label for="" class="col-sm-4">Cause of Admission:</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control" id="comment" name="comment" value="{{ $enpatient && $enpatient->patientInfo?$enpatient->patientInfo->fldcomment:'' }}"/>
                                            </div>
                                            <div class="col-sm-2">
                                                <button class="btn btn-primary" onclick="depositForm.saveComment();"><i class="fa fa-check" aria-hidden="true"></i></button>
                                            </div>
                                        </div>
                                        <div class="form-group form-row align-items-center er-input">
                                            <label for="" class="col-sm-4">IP No.:</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control" name="diary_number" id="diary_number" value="{{ $enpatient && $enpatient->patientInfo?$enpatient->patientInfo->fldadmitfile:'' }}"/>
                                            </div>
                                            <div class="col-sm-2">
                                                <button class="btn btn-primary" onclick="depositForm.saveDiaryNumber();"><i class="fa fa-check" aria-hidden="true"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group form-row align-items-center er-input">
                                            <label for="" class="col-sm-3">Consultant:</label>
                                            <div class="col-sm-7">
                                                <select name="consultant" id="consultant" class="form-control">
                                                    <option value="">--Select--</option>
                                                    @php
                                                        $consultantList = Helpers::getConsultantList();
                                                    @endphp
                                                    @if(count($consultantList))
                                                        @foreach($consultantList as $con)
                                                            @if($con->nmc)
                                                                <option data-nmc="{{ $con->nmc }}" value="{{ $con->username }}"  {{ $enpatient && $enpatient->consultant && $con->username ==$enpatient->consultant->flduserid ? 'selected':'' }} >{{ $con->fldtitlefullname }}</option>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group form-row align-items-center er-input">
                                            <label for="" class="col-sm-3">Guardian:</label>
                                            <div class="col-sm-3">
                                                <input type="text" id="js-depositform-guardian" class="form-control" value="{{ $enpatient && $enpatient->patientInfo ? $enpatient->patientInfo->fldptguardian : '' }}">
                                            </div>
                                            <label for="" class="col-sm-2">Relation:</label>
                                            <div class="col-sm-3">
                                                <select name="consultant" id="js-depositform-relation" class="form-control">
                                                    <option value="">--Select--</option>
                                                    @php
                                                        $relations = Helpers::getRelations();
                                                    @endphp
                                                    @if(count($relations))
                                                        @foreach($relations as $relation)
                                                            <option @if($enpatient && $enpatient->patientInfo && $enpatient->patientInfo->fldrelation == $relation->flditem) selected @endif value="{{ $relation->flditem }}">{{ $relation->flditem }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="col-sm-1">
                                                <button class="btn btn-primary" onclick="depositForm.saveGuardian();"><i class="fa fa-check" aria-hidden="true"></i></button>
                                            </div>
                                        </div>
                                        <div class="form-group form-row align-items-center er-input">
                                            <div class="col-sm-3">
                                                <div class="custom-control custom-checkbox custom-checkbox-color-check custom-control-inline">
                                                    <input type="checkbox" class="custom-control-input bg-primary" name="admitted" id="admitted" value="Admitted"/>
                                                    <label class="custom-control-label"> Admission</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-8">
                                                <button class="btn btn-primary" onclick="depositForm.saveConsultantAdmitted();"><i class="fa fa-check" aria-hidden="true"></i>&nbsp;OK</button>
                                                <button class="btn btn-primary"><i class="fa fa-edit" aria-hidden="true"></i>&nbsp;Status</button>
                                                <button type="button" class="btn btn-primary js-deposit-form-sticker-btn"><i class="fa fa-print" aria-hidden="true"></i>&nbsp;Sticker</button>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="res-table">
                                            <table class="table table-bordered table-hover table-striped">
                                                <thead class="thead-light">
                                                <tr>
                                                    <th></th>
                                                    <th>EncID</th>
                                                    <th>RegDate</th>
                                                    <th>Status</th>
                                                    <th>BillMode</th>
                                                    <th>Discount</th>
                                                    <th>DOA</th>
                                                    <th>RegDept</th>
                                                    <th>CurrDept</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @if (isset($admissions))
                                                    @forelse ($admissions as $admission)
                                                        @php
                                                            $allConsultant = $admission->allConsultant;
                                                        @endphp
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $admission->fldpatientval }}</td>
                                                            <td>{{ $admission->fldregdate }}</td>
                                                            <td>{{ $admission->fldadmission }}</td>
                                                            <td>{{ $admission->fldbillingmode }}</td>
                                                            <td>{{ $admission->flddisctype }}</td>
                                                            <td>{{ $admission->flddoa }}</td>
                                                            <td>{{ ($allConsultant->count() > 0) ? implode(', ', (array_column($allConsultant->toArray(), 'fldconsultname'))) : ''  }}</td>
                                                            <td>{{ $admission->fldcurrlocat }}</td>
                                                        </tr>
                                                    @empty

                                                    @endforelse
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="test" role="tabpanel" aria-labelledby="test-tab">
                                <div class="row">
                                    <div class="col-md-6">
                                        <ul class="list-group">
                                            <li class="list-group-item"><a class="iq-sub-card" href="javascript:void(0)" id="dischargeModal" data-toggle="modal" data-target="#confirm-box">Discharge</a></li>
                                            <li class="list-group-item"><a class="iq-sub-card" href="javascript:void(0)" id="markLamaModal" data-toggle="modal" data-target="#confirm-box">Mark LAMA</a></li>
                                            <li class="list-group-item"><a class="iq-sub-card" href="javascript:void(0)" id="markReferModal" data-toggle="modal" data-target="#confirm-box">Mark Refer</a></li>
                                            <li class="list-group-item"><a class="iq-sub-card" href="javascript:void(0)" id="markDeathModal" data-toggle="modal" data-target="#confirm-box">Mark Death</a></li>
                                            <li class="list-group-item"><a class="iq-sub-card" href="javascript:void(0)" id="absconderModal" data-toggle="modal" data-target="#confirm-box">Absconder</a></li>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Change Department Modal --}}
    <div class="modal fade" id="modal-change-dept" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="change-dept-form" method="POST" action="{{ route('depositForm.change-department') }}">
                @csrf
                <input type="hidden" name="encounter_val" value="{{ $enpatient->fldencounterval??'' }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Change Department</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="custom-control custom-radio custom-control-inline align-items-right">
                                    <input type="radio" id="IP-radio-old" name="department_category" value="IP" class="custom-control-input">
                                    <label class="custom-control-label" for="IP-radio-old"> IP Patient </label>
                                </div>
                                @if (($enpatient && $enpatient->currentDepartment && in_array($enpatient->currentDepartment->fldcateg, ["Consultation", "Emergency"])))
                                    <div class="custom-control custom-radio custom-control-inline align-items-right">
                                        <input type="radio" id="ER-radio-old" name="department_category" value="ER" class="custom-control-input">
                                        <label class="custom-control-label" for="ER-radio-old"> ER Patient </label>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div id="check-box-error"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </form>
        </div>
        {{-- End of change department modal --}}
    </div>
    @include('frontend.common.assign-bed')
@endsection
@push('after-script')
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/additional-methods.min.js"></script>
    <script src="{{ asset('js/deposit_form.js')}}"></script>
    <script>

        $(document).ready(function () {
            $("#consultant").select2();
        });
        function getRadioFunction(value){
            // alert(value);
            if (value == "Cash") {

                $('#cash_payment').addClass('checked-bak');
                $('#credit_payment').removeClass('checked-bak');
                $('#card_payment').removeClass('checked-bak');
                $('#fonepay_payment').removeClass('checked-bak');

            } else if (value == "Credit") {

                $('#cash_payment').removeClass('checked-bak');
                $('#credit_payment').addClass('checked-bak');
                $('#card_payment').removeClass('checked-bak');
                $('#fonepay_payment').removeClass('checked-bak');



            } else if (value == "Card") {

                $('#cash_payment').removeClass('checked-bak');
                $('#credit_payment').removeClass('checked-bak');
                $('#card_payment').addClass('checked-bak');
                $('#fonepay_payment').removeClass('checked-bak');

            } else if (value == "Fonepay") {

                $('#cash_payment').removeClass('checked-bak');
                $('#credit_payment').removeClass('checked-bak');
                $('#card_payment').removeClass('checked-bak');
                $('#fonepay_payment').addClass('checked-bak');
                var convergent = $('#convergent_payment_status').val();
                var encounter = $('#encounter_id').val();
                var generateQr = $('#generate_qr').val();
                if(encounter !='' && convergent !='' && convergent == 'active' && generateQr == 'yes'){
                    var totalamount = $('input[name="received_amount"]').val();
                    if(totalamount == '' || totalamount <= 0){
                        showAlert('Amount not available');
                        return false;
                    }
                    fonepayQrGenerate(encounter);
                }
            } else if (value == "Other") {

            }else{

                $('#cash_payment').addClass('checked-bak');
                $('#credit_payment').removeClass('checked-bak');
                $('#card_payment').removeClass('checked-bak');
                $('#fonepay_payment').removeClass('checked-bak');

            }
            // return false;
        }
        function fonepayQrGenerate(encounter) {
            let route = "{!! route('convergent.payments.creditClearance') !!}";
            $.ajax({
                url: route,
                type: "POST",
                data: { "total": $('input[name="received_amount"]').val(),encounter:$('#encounter_id').val(), "_token": "{{ csrf_token() }}" },
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
        function clearForms() {
            $('#js-deposit-form').trigger("reset");
        }

        $(function () {
            $('#payment_mode').val('Credit');
            $('#payment_mode option[value="Cash"]').remove();
            $('#payment_mode option[value="Other"]').remove();
            $('#payment_mode').trigger('change');

            $('#payment_mode').change(function () {
                if (this.value === 'Cheque') {
                    $("#cheque_number").show();
                    $('#bank_name').next(".select2-container").show();
                } else {
                    $("#cheque_number").hide();
                    $('#bank_name').next(".select2-container").hide();
                }
            });
            $("input[name='received_amount']").on('keyup', function (event) {
                let val = $(this).val();
                if (parseInt(val) > 1000000) {
                    $(this).val(1000000)
                }
            });

            let has_session_changed_dept_encounter = '{{ session()->pull("changed_department_encounter_val") }}';

            if (has_session_changed_dept_encounter != "") {
                $("#show-btn").click();
            }
            let dept_select = $("#select-change-department");

            $("#change-dept-form").validate({
                rules: {
                    department_category: { // <- NAME of every radio in the same group
                        required: true
                    }
                },
                errorPlacement: function (error, element) {
                    if (element.attr("name") == "department_category") {
                        $("#check-box-error").html(error);
                    } else {
                        // something else if it's not a checkbox
                    }
                }
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
