@extends('frontend.layouts.master')

@push('after-styles')
    <style type="text/css">

        #divtoprint { display: none; }

        @media print
        {
            #divtoprint { display: block; }
            @page {
                margin: 24mm 0 11mm;
            }

            body {
                margin: 0 auto;
                padding: 10px 10px 5px;
                font-size: 13px;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            }

            .bill-title {
                position: absolute;
                width: 100%;
                text-align: center;
                margin-bottom: 2px;
                margin-top: 3px;
            }


            .a4 {
                width: auto;
                margin: 0 auto;
            }

            .footer {
                /* position: absolute; */
                width: 100%;
                text-align: center;
                margin: 0;
                padding: 0;
            }

            .bar-code {
                width: 200px;
                height: auto;
                margin-top: 5px;
            }

            .table {
                width: 100%;
                border-collapse: collapse;
            }

            .content-body {
                border-collapse: collapse;
            }

            .content-body table {
                page-break-inside: auto
            }

            .content-body tr {
                page-break-inside: avoid;
                page-break-after: auto
            }

            .content-body td:nth-child(1),
            .content-body th:nth-child(1),
            .content-body td:nth-child(2),
            .content-body th:nth-child(2),
            .content-body th:nth-child(3),
            .content-body td:nth-child(3),
            .content-body th:nth-child(4),
            .content-body td:nth-child(4) {
                text-align: left;

            }


            .content-body td,
            .content-body th {
                border: 1px solid #ddd;
                font-size: 13px;
                text-align: right;
                padding-right: 4px;
            }

            h2,
            h4 {
                line-height: 0.5rem;
            }

            ul {
                float: right;
                padding: 0;
                margin: 0;
            }

            ul li {
                text-align: right;;
                list-style: none;

            }

            ul li span:first-child {
                text-align: left;
            }

            ul li span:nth-child(2) {
                text-align: right;
                width: 150px;
                display: inline-block;
            }

            .table thead {
                background-color: #fff;
            }
        }
    </style>
    <style>
/*
    .total-detail th:nth-child(1){
        text-align:left
    } */
    .swal-ok-btn {
        background-color: #144069;
    }
        .switch-button {
            background: rgba(7, 7, 7, 0.56);
            border-radius: 30px;
            overflow: hidden;
            width: 240px;
            text-align: center;
            font-size: 18px;
            letter-spacing: 1px;
            color: #155fff;
            position: relative;
            padding-right: 120px;
        }

        .switch-button:before {
            content: "Popup";
            position: absolute;
            top: 0;
            bottom: 0;
            right: 0;
            width: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 3;
            pointer-events: none;
        }

        .switch-button-checkbox {
            cursor: pointer;
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            z-index: 2;
        }

        .switch-button-checkbox:checked + .switch-button-label:before {
            transform: translateX(120px);
            transition: transform 300ms linear;
        }

        .switch-button-checkbox + .switch-button-label {
            position: relative;
            padding: 15px 0;
            display: block;
            user-select: none;
            pointer-events: none;
        }

        .switch-button-checkbox + .switch-button-label:before {
            content: "";
            background: #ffffff;
            height: 20%;
            width: 20%;
            position: absolute;
            left: 0;
            top: 0;
            border-radius: 30px;
            transform: translateX(0);
            transition: transform 300ms;
        }

        .switch-button-checkbox + .switch-button-label .switch-button-label-span {
            position: relative;
        }

        .total-detail tr th:nth-child(1) {
            width: 50%;
            text-align: right;
        }

        .total-detail tr th:nth-child(2) {
            width: 50%;
        }

        .img-ms-form{
            max-width:80px;
            width:100%;
            height: auto ;
        }
    </style>
@endpush
@section('content')
    @php
        $segment = Request::segment(1);
        $packageBillingMode = false;
        if (isset($serviceDataPackage) && $serviceDataPackage != null){
            $packageBillingMode = true;
        }
    @endphp

    @if(isset($patient_status_disabled) && $patient_status_disabled == 1 )
        @php
            $disableClass = 'disableInsertUpdate';
        @endphp
    @else
        @php
            $disableClass = '';
        @endphp
    @endif
    @php
        $segment = Request::segment(1);
        if($segment == 'admin'){
        $segment2 = Request::segment(2);
        $segment3 = Request::segment(3);
        if(!empty($segment3))
        $route = 'admin/'.$segment2 . '/'.$segment3;
        else
        $route = 'admin/'.$segment2;

        }else{
        $route = $segment;
        }

        /** check if patient is in consult*/
        $patientDepartment = (isset($enpatient) && $enpatient->currentDepartment) ? $enpatient->currentDepartment->fldcateg : '';

        if ($patientDepartment == 'Consultation')
        $patientDepartment = false;
        else
        $patientDepartment = true;
    @endphp

    <div class="container-fluid">
        <div class="row">
            @include('billing::common.patient-profile')
        </div>
    </div>
    <div class="container-fluid">
        <div class="">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <ul class="nav nav-tabs" id="myTab-two" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="cashier-tab-grid" data-toggle="tab" href="#cashier-tab" role="tab" aria-controls="cashier" aria-selected="true">Cashier</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tp-tab-grid" data-toggle="tab" href="#tp-tab" role="tab" aria-controls="tp" aria-selected="false">TP Bill Detail</a>
                        </li>

                    </ul>
                    <div class="tab-content" id="myTabContent-1">
                        <div class="tab-pane fade show active" id="cashier-tab" role="tabpanel" aria-labelledby="cashier-tab-grid">
                            <div class="col-sm-12">
                                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                                    <div class="iq-card-body">

                                        <form action="{{route('billing.final.save.payment')}}" method="post" id="cashier-form">
                                            @csrf
                                            <input type="hidden" name="billing_mode" id="billing_mode" value="no" class="billing_mode">
                                            <input type="hidden" name="discount_mode" id="discount_mode" value="no" class="discount_mode">
                                            <input type="hidden" name="fonepaylog_id" value="no" class="js-fonepaylog-id-hidden" value="">

                                            <input type="hidden" name="is_credit_checked" id="is_credit_checked" value="no" class="is_credit_checked">
                                            <input type="hidden" id="user_billing_mode" class="user_billing_mode" value="@if(isset($enpatient) && isset($enpatient->fldbillingmode) ) {{$enpatient->fldbillingmode}} @endif" disabled>
                                            <input type="hidden" name="__encounter_id" value="{{ isset($enpatient)?$enpatient->fldencounterval:'' }}" class="__encounter_id">
                                            <input type="hidden" name="__patient_id" class="__patient_id" value="{{ isset($enpatient)?$enpatient->fldpatientval:'' }}">
                                            @if(isset($enpatient) && (strtolower($enpatient->fldbillingmode) == 'health insurance' || strtolower($enpatient->fldbillingmode) == 'healthinsurance' || strtolower($enpatient->fldbillingmode) == 'hi'))
                                                @php $encallowedamt = \App\PatInsuranceDetails::where('fldencounterval',$enpatient->fldencounterval)->first();  @endphp
                                                @php $encchargedamt = \App\PatBilling::where('fldencounterval',$enpatient->fldencounterval)->where('fldsave','1')->sum('fldditemamt'); @endphp
                                                <input type="hidden" id="allowedamt" class="allowedamt" name="allowedamt" value="{{ isset($encallowedamt)?\App\Utils\Helpers::numberFormat($encallowedamt->fldallowedamt,'insert'):'' }}">
                                                 <input type="hidden" id="chargedamt" class="chargedamt" name="chargedamt" value="{{ isset($encchargedamt)?\App\Utils\Helpers::numberFormat($encchargedamt,'insert'):'' }}">
                                                {{-- <input type="hidden" id="allowedamt" name="allowedamt" value="{{ \App\Utils\Helpers::numberFormat($encallowedamt->fldallowedamt,'insert') }}"> --}}
                                            @endif

                                            <div class="form-horizontal border-bottom">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <div class="form-group form-row align-items-center">
                                                            <label class="col-sm-5">Transaction Date</label>
                                                            <div class="col-sm-7">
                                                                <div class="input-group">
                                                                    <input type="date" name="transaction_payment_date" id="transaction_payment_date" class="form-control">
                                                                    {{--<div class="input-group-append">
                                                                                    <div class="input-group-text"><i class="ri-calendar-2-fill"></i></div>
                                                                                </div>--}}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-4">
                                                        <div class="form-group form-row align-items-center">
                                                            <label class="col-sm-4 text-right">Pan Number</label>
                                                            <div class="col-sm-8">
                                                                <div class="input-group">
                                                                    <input type="number" name="pan_number" id="pan_number" class="form-control" value="{{ isset($enpatient) && $enpatient->patientInfo ?$enpatient->patientInfo->fldpannumber:'' }}" placeholder="Pan Number">
                                                                    <!-- <button type="button" id="save_pan_number" class="btn btn-primary btn-action ml-2">Save</button> -->
                                                                    <div class="input-group-append">
                                                                        <button type="button" id="save_pan_number" class="btn btn-primary">Save</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group form-row align-items-center">
                                                            <label class="col-sm-4">Referral Doctor</label>
                                                            <div class="col-sm-6">
                                                                    <select id="js-referal-doctor-id" class="form-control select2" name="referal_username">
                                                                        <option value="">--Select--</option>
                                                                        @if (isset($refer_by) && $refer_by)
                                                                            @foreach ($refer_by as $refer)
                                                                                <option data-userid="{{ $refer->id }}" {{ (isset($referralDoctorSelected) && $referralDoctorSelected == $refer->username) ? 'selected' : '' }} value="{{ $refer->username }}">{{ $refer->fldtitlefullname }}</option>
                                                                            @endforeach
                                                                        @endif
                                                                    </select>
                                                                </div>
                                                                <div class="col-sm-1">

                                                                    <button class="btn btn-primary" type="button" id="js-referral-doctor-change-btn">
                                                                        <i class="fa fa-check"></i>
                                                                    </button>

                                                                </div>

                                                        </div>
                                                    </div>
                                                    <div class="col-sm-5">
                                                        <div class="form-group form-row align-items-center" id="expected_date">
                                                            <label class="col-sm-5">Expected Payment Date</label>
                                                            <div class="col-sm-7">
                                                                <div class="input-group">
                                                                    <input type="date" name="expected_payment_date" id="expected_payment_date" placeholder="DD/MM/YYY" class="form-control">
                                                                    {{--<div class="input-group-append">
                                                                                    <div class="input-group-text"><i class="ri-calendar-2-fill"></i></div>
                                                                                </div>--}}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @if(Options::get('display_billing_toggle') == 1)
                                                <div class="iq-card-body">
                                                    <div class="form-horizontal border-bottom">
                                                        <div class="row">
                                                            <div class="col-sm-2">
                                                    <span>
                                                        <input type="checkbox" id="billing-selection-type-change">
                                                        Billing Toggle
                                                    </span>
                                                            </div>

                                                            <div class="col-sm-5 service-package-radio billing-dropdown">
                                                                <input type="radio" id="Service" name="servicetype" class="servicetype" url="{{route('billing.get.items.by.service.or.inventory.select')}}" value="service"> <label for="Service">Service</label>
                                                                <input type="radio" id="Package" name="servicetype" class="servicetype" url="{{route('billing.get.items.by.service.or.inventory.select')}}" value="package"> <label for="Package">Package</label>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            @endif


                                            <div class="from-horizontal border-bottom mb-2">
                                                <div class="row">

                                                    @if(isset($serviceDataPackage) and !empty($serviceDataPackage) and (strtoupper(substr($enpatient->fldencounterval, 0,2)) != "IP") )
                                                        <div class="col-sm-5" style="display: none;">
                                                    @else
                                                        <div class="col-sm-5" style="display: block;">
                                                    @endif

                                                        <div class="row">
                                                            <div class="col-lg-12 mb-2">

                                                                <div id="js-service-select">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4 mb-2">
                                                                <button type="button" id="save-button-pakage-service" class="btn btn-primary" onclick="saveServiceByRadio()">Add</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-7">
                                                        <div class="form-group form-row float-right">
                                                            <div class="custom-control custom-radio custom-control-inline d-none">
                                                                <input type="radio" name="item_type" class="custom-control-input item_type" value="service" checked>
                                                                <label class="custom-control-label">Service Item</label>
                                                            </div>
                                                            <div class="billing-popup">
                                                                <button class="btn btn-primary btn-action" type="button" onclick="getSoldItem()">
                                                                    Service Billing <i class="ri-add-line"></i></button>
                                                                <button class="btn btn-primary btn-action" type="button" id="package-billing-button">Package Billing <i class="ri-add-line"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{--<div class="d-flex justify-content-center w-100 pb-3">
                                                                    <button class="btn btn-primary btn-action mb-2" type="button" onclick="getSoldItem()">Add <i class="ri-add-line"></i></button>
                                                                </div>--}}
                                                </div>
                                                <div class="res-table">
                                                    <div id="billing-body">
                                                        @php
                                                            $subTotal = $subtotal ? $subtotal:0;
                                                            $totalAfterDiscount = $subTotal - $discount;
                                                            $totalAfterTax = $subTotal - $discount + $tax;
                                                        @endphp
                                                        @if($html !="")
                                                            {!! $html !!}
                                                        @else
                                                            <table class="table table-striped table-bordered table-hover">
                                                                <thead class="thead-light">
                                                                <tr>
                                                                    <th>S.N.</th>
                                                                    <th style="width: 60%;">Items</th>
                                                                    <th class="text-center">Qty</th>
                                                                    <th class="text-center">Rate</th>
                                                                    <th class="text-center">Dis%</th>
                                                                    <th class="text-center">Tax%</th>
                                                                    <th class="text-center">Total Amount</th>
                                                                    <th class="text-center">Action</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <tr>
                                                                    <td colspan="8">No Items Added</td>
                                                                </tr>
                                                                </tbody>
                                                                <tfoot class="thead-light d-none">
                                                                <tr>
                                                                    <th>&nbsp;</th>
                                                                    <th>Total</th>
                                                                    <th colspan="2" class="text-right"></th>
                                                                    <th colspan="2" class="text-right"></th>
                                                                    <th class="text-right table-bill-total">{{\App\Utils\Helpers::numberFormat($totalAfterTax)}}</th>
                                                                    <th colspan="2">&nbsp;</th>
                                                                </tr>
                                                                </tfoot>
                                                            </table>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6">

                                                        <p class="mb-2">
                                                            {!! isset($previousDeposit) ? "<strong>Deposit</strong>: ".\App\Utils\Helpers::numberFormat($previousDeposit) :'' !!}
                                                        </p>
                                                        @if(isset($totalAmountReceivedByEncounter) && $totalAmountReceivedByEncounter)
                                                            <p>
                                                                <strong>Previous Received Amount</strong> Rs. {{ \App\Utils\Helpers::numberFormat($totalAmountReceivedByEncounter)??'' }} <a href="{{ route('previous.received.amount', $enpatient->fldencounterval) }}" class="btn btn-primary" target="_blank">Previous</a>
                                                            </p>
                                                        @endif


                                                        @if(isset($totalDepositAmountReceived) && $totalDepositAmountReceived)
                                                            <p><strong>Deposit Amount</strong> Rs.<span class="depAmount">{{ \App\Utils\Helpers::numberFormat($totalDepositAmountReceived)??'' }}
                                                                                                            </span></p>
                                                        @else
                                                            <p><strong>Deposit Amount</strong> Rs.<span class="depAmount">0.00 /-</span></p>
                                                        @endif
                                                        @if(isset($totalTPAmountReceived) && $totalTPAmountReceived)
                                                            <p><strong>TP Bill Amount</strong> Rs. <span class="tpAmount">{{ \App\Utils\Helpers::numberFormat($totalTPAmountReceived)??'' }}</span>
                                                                <a href="{{ route('previous.tp.amount', $enpatient->fldencounterval) }}" class="btn btn-primary" target="_blank">Previous</a>
                                                            </p>
                                                        @else
                                                            <p><strong>TP Bill Amount</strong> Rs. <span class="tpAmount">0.00/-</span></p>
                                                        @endif

                                                        @if(isset($remaining_deposit) && $remaining_deposit)
                                                            <p><strong>Remaining Amount</strong> Rs. <span class="remainingAmount">{{ \App\Utils\Helpers::numberFormat($remaining_deposit)??'' }}</span>

                                                            </p>
                                                        @else
                                                            <p><strong>Remaining Amount</strong> Rs. <span class="remainingAmount">0.00/-</span></p>
                                                        @endif
                                                        {{--
                                                        @if(isset($enpatient) && (strtoupper(substr($enpatient->fldencounterval, 0,2)) != "IP") && !$packageBillingMode)--}}

                                                        {{--
                                                        @endif
                                                        --}}
                                                        {{--                                        @include('billing::common.payment-modes')--}}
                                                        {{--@if((isset($enpatient) && strtoupper(substr($enpatient->fldencounterval, 0,2)) !== "IP"))--}}
                                                        <div class="form-group form-row">
                                                            <label class="col-12">Payment Mode</label>
                                                            <div class="col-sm-12">
                                                                <div class="bak-payment p-2">
                                                                    <div class="form-row">
                                                                        <div class="col-sm-3  pay-rad" id="cash_payment" onclick="getRadioFunction('Cash')">
                                                                            <div class="custom-control custom-radio custom-control-inline" onclick="getRadioFunction('Cash')">
                                                                                <input type="radio" id="" name="payment_mode" class="custom-control-input payment_mode" value="Cash" checked>
                                                                                <label class="custom-control-label" for="" onclick="getRadioFunction('Cash')"> Cash</label>
                                                                            </div>
                                                                             <div class="img-ms-form" onclick="getRadioFunction('Cash')">
                                                                                <img src="{{ asset('new/images/cash-2.png')}}"   class="img-ms-form" alt="">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-3 pay-rad" id="credit_payment" onclick="getRadioFunction('Credit')">
                                                                            <div class="custom-control custom-radio custom-control-inline" onclick="getRadioFunction('Credit')">
                                                                                <input type="radio" id="" name="payment_mode" class="custom-control-input payment_mode" value="Credit">
                                                                                <label class="custom-control-label" for="" onclick="getRadioFunction('Credit')"> Credit </label>
                                                                            </div>
                                                                             <div class="img-ms-form" onclick="getRadioFunction('Credit')">
                                                                                <img src="{{ asset('new/images/credit-3.png')}}"   class="img-ms-form" alt="">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-3 pay-rad" id="card_payment" onclick="getRadioFunction('Card')">
                                                                            <div class="custom-control custom-radio custom-control-inline" onclick="getRadioFunction('Card')">
                                                                                <input type="radio" id="" name="payment_mode" class="custom-control-input payment_mode" value="Card">
                                                                                <label class="custom-control-label " for="" onclick="getRadioFunction('Card')"> Card </label>
                                                                            </div>
                                                                            <div class="mt-2 img-ms-form" onclick="getRadioFunction('Card')">
                                                                                <img src="{{ asset('new/images/swipe2.png')}}"   class="img-ms-form" alt="">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-3 pay-rad" id="fonepay_payment" onclick="getRadioFunction('Fonepay')">
                                                                            <div class="custom-control custom-radio custom-control-inline" onclick="getRadioFunction('Fonepay')">
                                                                                <input type="radio" id="" name="payment_mode" class="custom-control-input payment_mode" value="Fonepay" onclick="getRadioFunction('Fonepay')">
                                                                                <label class="custom-control-label" for="" onclick="getRadioFunction('Fonepay')">Fonepay </label>
                                                                            </div>
                                                                            <div class="img-ms-form" onclick="getRadioFunction('Fonepay')">
                                                                                <img src="{{ asset('new/images/fonepay_logo.png')}}" class="ml-4"   class="img-ms-form" alt="">
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- <div class="col-4">
                                                                <select name="payment_mode" id="payment_mode" class="form-control">
                                                                    <option value="Cash">Cash</option>
                                                                    {{--@if(isset($enpatient) && $patientDepartment )
                                                                        <option value="Credit" {{ (strtoupper(substr($enpatient->fldencounterval, 0,2)) === "IP") ? "selected" : '' }}>Credit</option>
                                                                    @endif--}}
                                                                    <option value="Credit">Credit</option>
                                                                    <option value="Cheque">Cheque</option>
                                                                    {{-- <option value="Fonepay">Fonepay</option>--}}
                                                                    <option value="Other">Other</option>
                                                                </select>
                                                            </div> -->
                                                        </div>
                                                        {{--@endif--}}

                                                        {{--if cash--}}
                                                        <div class="form-horizontal">

                                                            <div class="row">
                                                                <div class="form-group col-sm-6">
                                                                    <input type="text" name="cheque_number" id="cheque_number" placeholder="Cheque Number" class="form-control">
                                                                </div>

                                                                <div class="form-group col-sm-6">
                                                                    <select name="bank_name" id="bank-name" class="form-control">
                                                                        <option value="">Select Bank</option>
                                                                        @if(count($banks))
                                                                            @forelse($banks as $bank)
                                                                                <option value="{{ $bank->fldbankname }}">{{ $bank->fldbankname }}</option>
                                                                            @empty

                                                                            @endforelse

                                                                        @endif
                                                                    </select>

                                                                </div>

                                                                <div class="form-group col-sm-12">
                                                                    <input type="text" name="other_reason" id="other_reason" placeholder="Reason" class="form-control">
                                                                </div>


                                                                <div class="form-group col-sm-12">
                                                                    <input type="text" name="office_name" id="office_name" placeholder="Office Name" class="form-control">
                                                                </div>


                                                            </div>


                                                        </div>
                                                        {{--end if cash--}}

                                                        <div class="form-group">
                                                            <label> Remarks:</label>
                                                            <textarea name="remarks" class="form-control" id="remarks" cols="40" rows="3"></textarea>

                                                        </div>

                                                    </div>
                                                    <div class="col-sm-6">

                                                        <table class="table table-borderless total-detail">
                                                            <tbody>
                                                            <tr>
                                                                <th>SubTotal:</th>
                                                                <th class="">
                                                                    <input type="text" id="sub-total-data" class="form-control text-right" value="{{ \App\Utils\Helpers::numberFormat($subtotal)  }}" readonly>
                                                                </th>
                                                            </tr>
                                                            <!--                                            <tr>
                                                                <th class="text-right">Discount:</th>
                                                                <th><input type="text" name="" placeholder="0.00" class="form-control ml-auto text-right" style="width: 100px;" {{ $discount != 0?"readonly":"" }}>
                                                                </th>
                                                            </tr>-->
                                                            <tr>

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
                                                                    <input type="text" id="discount-amount" class="form-control text-right" value="{{ \App\Utils\Helpers::numberFormat($discount) }}">
                                                                </th>
                                                            </tr>
                                                            <tr id="discount-percent-container">
                                                                <th>Discount Percent:</th>
                                                                <th>
                                                                    <div class="input-group">
                                                                        <input type="text" id="discount-percent" class="form-control text-right" value="">
                                                                        <div class="input-group-append">
                                                                            <span class="input-group-text" id="basic-addon2">%</span>
                                                                        </div>
                                                                    </div>
                                                                </th>
                                                            </tr>

                                                            <tr>
                                                                <th>Tax:</th>
                                                                <th class="">
                                                                    <input type="text" id="tax-total-data" class="form-control text-right" value="{{ \App\Utils\Helpers::numberFormat($tax) }}" readonly>
                                                                </th>
                                                            </tr>
                                                            <tr>
                                                                <th>Total:</th>
                                                                <th>
                                                                    <input type="text" id="grand-total-data" class="form-control text-right" value=" {{\App\Utils\Helpers::numberFormat($totalAfterTax)  }}" readonly>

                                                                </th>
                                                            </tr>

                                                            <tr>
                                                                <th>Receive:</th>
                                                                <th class="">
                                                                    <input type="text" name="received_amount" id="received-amount" class="form-control text-right" value="0.00" readonly>
                                                                </th>
                                                            </tr>
                                                            {{--we might need it in futute--}}
                                                            {{--
                                                            <tr>
                                                                <th>Tender:</th>
                                                                <th>
                                                                    <input type="text" id="tender-amount" class="form-control text-right" value="0">
                                                                </th>
                                                            </tr>
                                                            <tr>
                                                                <th>Return:</th>
                                                                <th>
                                                                    <input type="text" id="return-amount" class="form-control text-right" value="0" readonly>
                                                                </th>
                                                            </tr>
                                                            --}}

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="">
                                                <div class="pt-2 mr-2 float-right fonepay-button-save">
                                                    @if(isset($enpatient))
                                                        @if(Options::get('convergent_payment_status') && Options::get('convergent_payment_status') == 'active' )
                                                            @php
                                                                $convergentpumentstatus = 'active';
                                                            @endphp
                                                            {{--<a href="{{ route('convergent.payments', $enpatient->fldencounterval) }}" class="btn btn-primary btn-action float-right">Fonepay</a>--}}
                                                        @else
                                                            @php
                                                                $convergentpumentstatus = '';
                                                            @endphp
                                                        @endif
                                                    @else

                                                            @php
                                                                $convergentpumentstatus = '';
                                                            @endphp

                                                    @endif
                                                    <input type="hidden" name="convergent_payment_status" id="convergent_payment_status" value="{{$convergentpumentstatus}}">
                                                    <input type="hidden" name="generate_qr" id="generate_qr" value="{{Options::get('generate_qr')}}">
                                                </div>

                                                <div class="mt-3 mb-2 float-right payment-save-done">
                                                    {{--@if((isset($enpatient) && strtoupper(substr($enpatient->fldencounterval, 0,2)) !== "IP"))--}}
                                                        <button type="button" id="js-billing-save-btn" class="btn btn-primary btn-action float-right">Payment Done/Save</button>
                                                    {{--@endif--}}

                                                   {{-- @if(isset($enpatient) && (strtoupper(substr($enpatient->fldencounterval, 0,2)) === "ER" || strtoupper(substr($enpatient->fldencounterval, 0,2)) === "IP"))--}}
                                                        <button type="button" class="mr-2 btn btn-primary btn-action float-right" onclick="saveTpBill()" id="js-billing-save-tp-btn" style="display: none;">TP Bill</button>
                                                    {{--@endif--}}
                                                </div>
                                            </div>

                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade " id="tp-tab" role="tabpanel" aria-labelledby="tp-tab-grid">
                             <div class="col-sm-12">
                                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                                    <div class="iq-card-body">
                                        <div class="from-horizontal border-bottom mb-2">
                                            <div class="row">
                                                <div class="res-table">
                                                    <div id="billing-tp-body">
                                                        @php
                                                            $subTotal = $subtotal ? $subtotal:0;
                                                            $totalAfterDiscount = $subTotal - $discount;
                                                            $totalAfterTax = $subTotal - $discount + $tax;
                                                        @endphp
                                                        @if($tphtml !="")
                                                            {!! $tphtml !!}
                                                        @else
                                                            <table class="table table-striped table-bordered table-hover">
                                                                <thead class="thead-light">
                                                                <tr>
                                                                    <th>S.N.</th>
                                                                    <th style="width: 60%;">Items</th>
                                                                    <th class="text-center">Qty</th>
                                                                    <th class="text-center">Rate</th>
                                                                    <th class="text-center">Dis%</th>
                                                                    <th class="text-center">Tax%</th>
                                                                    <th class="text-center">Total Amount</th>
                                                                    <th class="text-center">Action</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <tr>
                                                                    <td colspan="8">No Items Added</td>
                                                                </tr>
                                                                </tbody>
                                                                <tfoot class="thead-light d-none">
                                                                <tr>
                                                                    <th>&nbsp;</th>
                                                                    <th>Total</th>
                                                                    <th colspan="2" class="text-right"></th>
                                                                    <th colspan="2" class="text-right"></th>
                                                                    <th class="text-right table-bill-total">{{\App\Utils\Helpers::numberFormat($totalAfterTax)}}</th>
                                                                    <th colspan="2">&nbsp;</th>
                                                                </tr>
                                                                </tfoot>
                                                            </table>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">

                                                    <p class="mb-2">
                                                        {!! isset($previousDeposit)?"<strong>Deposit</strong>: ".\App\Utils\Helpers::numberFormat($previousDeposit) :'' !!}
                                                    </p>
                                                    @if(isset($totalAmountReceivedByEncounter) && $totalAmountReceivedByEncounter)
                                                        <p>
                                                            <strong>Previous Received Amount</strong> Rs. {{ \App\Utils\Helpers::numberFormat($totalAmountReceivedByEncounter)??'' }} <a href="{{ route('previous.received.amount', $enpatient->fldencounterval) }}" class="btn btn-primary" target="_blank">Previous</a>
                                                        </p>
                                                    @endif


                                                    @if(isset($totalDepositAmountReceived) && $totalDepositAmountReceived)
                                                        <p><strong>Deposit Amount</strong> Rs.<span class="depAmount">{{ \App\Utils\Helpers::numberFormat($totalDepositAmountReceived)??'' }}
                                                                                                        </span></p>
                                                    @else
                                                        <p><strong>Deposit Amount</strong> Rs.<span class="depAmount">0.00 /-</span></p>
                                                    @endif
                                                    @if(isset($totalTPAmountReceived) && $totalTPAmountReceived)
                                                        <p><strong>TP Bill Amount</strong> Rs. <span class="tpAmount">{{ \App\Utils\Helpers::numberFormat($totalTPAmountReceived)??'' }}</span>
                                                            <a href="{{ route('previous.tp.amount', $enpatient->fldencounterval) }}" class="btn btn-primary" target="_blank">Previous</a>
                                                        </p>
                                                    @else
                                                        <p><strong>TP Bill Amount</strong> Rs. <span class="tpAmount">0.00 /-</span></p>
                                                    @endif

                                                    @if(isset($remaining_deposit) && $remaining_deposit)
                                                        <p><strong>Remaining Amount</strong> Rs. <span class="remainingAmount">{{ \App\Utils\Helpers::numberFormat($remaining_deposit) ??'' }}</span>

                                                        </p>
                                                    @else
                                                        <p><strong>Remaining Amount</strong> Rs. <span class="remainingAmount">0.00 /-</span></p>
                                                    @endif

                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @include('billing::modal.encounter-list')
    @include('billing::modal.group-add')
    @include('billing::modal.dr-share')
    @include('billing::modal.package-item-list')
    @include('billing::modal.department-doctor')
    @include('frontend.common.create-new-patient')
@endsection

@push('after-script')
<div id="printCashier" style="display: none;"></div>
<div id="divtoprint" style="display: none;"></div>
    <script src="{{ asset('assets/plugins/jquery-validate/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery-validate/additional-methods.min.js') }}"></script>
    <script>
        function getRadioFunction(value){

            if (value == "Cash") {
                hideAll();
                $('#cash_payment').addClass('checked-bak');
                $('#credit_payment').removeClass('checked-bak');
                $('#card_payment').removeClass('checked-bak');
                $('#fonepay_payment').removeClass('checked-bak');
                 $('.payment-save-done').show();
               $("input[name=payment_mode][value=Cash]").prop('checked', true);

               $('#received-amount').val($('#grand-total-data').val());
               $('#js-billing-save-btn').show();
               $('#js-billing-save-tp-btn').hide();
            } else if (value == "Credit") {
                hideAll();
                $('#cash_payment').removeClass('checked-bak');
                $('#credit_payment').addClass('checked-bak');
                $('#card_payment').removeClass('checked-bak');
                $('#fonepay_payment').removeClass('checked-bak');
                $('#expected_date').show();
                $('.payment-save-done').show();
                $('#received-amount').empty().val(numberFormatDisplay(0));
                $("input[name=payment_mode][value=Credit]").prop('checked', true);

                var encounter = $('.__encounter_id').val();
                if(encounter.includes('IP') || encounter.includes('ER')){
                    $('#js-billing-save-btn').hide();
                    $('#js-billing-save-tp-btn').show();
                }else{
                    $('#js-billing-save-btn').show();
                    $('#js-billing-save-tp-btn').hide();
                }

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
                $("input[name=payment_mode][value=Card]").prop('checked', true);
                $('#received-amount').val($('#grand-total-data').val());
                $('#js-billing-save-btn').show();
                $('#js-billing-save-tp-btn').hide();
            } else if (value == "Fonepay") {
                hideAll();
                $('#cash_payment').removeClass('checked-bak');
                $('#credit_payment').removeClass('checked-bak');
                $('#card_payment').removeClass('checked-bak');
                $('#fonepay_payment').addClass('checked-bak');
                // $('.fonepay-button-save').show();
                $('.payment-save-done').show();
                $("input[name=payment_mode][value=Fonepay]").prop('checked', true);
                $('#received-amount').val($('#grand-total-data').val());
                $('#js-billing-save-btn').show();
                $('#js-billing-save-tp-btn').hide();
                var convergent = $('#convergent_payment_status').val();
                var encounter = $('.__encounter_id').val();
                var generateQr = $('#generate_qr').val();
                if(encounter !='' && convergent !='' && convergent == 'active' && generateQr == 'yes'){
                    var totalamount = parseFloat(numberFormatDisplay($('#grand-total-data').val()));

                    if(totalamount == '' || totalamount <= 0){
                        showAlert('Amount not available');
                        return false;
                    }
                    if ($(".share_check").length) {
                        var share_check_enable = 1;
                      } else {
                        var share_check_enable = 0;
                      }

                    if(share_check_enable == 1){
                        var payabledoc = $('#payable-doctors').text();
                        if(payabledoc == ''){
                            showAlert('Doctor share not given');
                            return false;
                        }
                    }
                    fonepayQrGenerate(encounter);
                }

            } else if (value == "Other") {
                hideAll();
                $('#other_reason').show();
                $('.payment-save-done').show();
            }else{
                hideAll();
                $('#cash_payment').addClass('checked-bak');
                $('#credit_payment').removeClass('checked-bak');
                $('#card_payment').removeClass('checked-bak');
                $('#fonepay_payment').removeClass('checked-bak');

            }
            // return false;
        }
        var addresses = JSON.parse('{!! \App\Utils\Helpers::getAllAddress() !!}');
        $(function () {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-Token": $('meta[name="_token"]').attr("content")
                }
            });

            $(".billing-dropdown").hide();

            $(document).on('change', '#billing-selection-type-change', function () {
                if (this.checked) {
                    $("#Service").prop("checked", true).trigger("click")
                    $('.billing-popup').hide();
                    $(".billing-dropdown").show();
                } else {
                    $('.billing-popup').show();
                    $(".billing-dropdown").hide();
                    $("#js-service-select").hide();
                    $("#save-button-pakage-service").hide();
                }
            });

            $('.servicetype').on('click', function () {
                var service = $(this).val();
                var url = $(this).attr("url");
                var billingmode = $("#billingmode").val();
                $.ajax({
                    url: url,
                    type: "POST",
                    dataType: "json",
                    data: {
                        item_type: service,
                        billingMode: billingmode
                    },
                    success: function (data1) {
                        // console.log(data1);
                        $("#js-service-select").show();
                        $("#save-button-pakage-service").hide();
                        $("#js-service-select").empty();
                        $("#js-service-select").html(data1.html);
                        $(".select2-dynamic").select2();

                    }
                });
            });


            $('#package-billing-button').on('click', function () {
                if (checkPatient()) {
                    $("#discountMode").val($("#discount-scheme-change").val());
                    $('#addGroup').modal('show');
                    $('.modal-footer').show();
                }
                return false;
            });


            // Doctor share system.
            const DOC_SHARE_MODAL = $("#doctor-share-modal");
            const DOC_SHARE_FORM = $("#doctor-share-form");
            let select_boxes = []; //dynamically generate select boxes for type.

            function validateDoctorShareForm() {
                DOC_SHARE_FORM.validate({
                    submitHandler: function (form) {
                        let submit_btn = $(form).find('button[type="submit"]');
                        submit_btn.html('Saving...');
                        submit_btn.prop('disabled', true);
                        let valid = true;
                        // for select box validation
                        // $.each(select_boxes, function(i, v) {
                        //     let select_box = $("select[name='" + v + "']");
                        //     if(select_box.val().length > 0) {
                        //         valid = true;
                        //         return;
                        //     }
                        // });
                        if (valid) {
                            form.submit();
                        } else {
                            alert('Please select at least one field.');
                        }
                    }
                });
            }

            validateDoctorShareForm();

            $(document).on('click', '#js-referral-doctor-change-btn', function (event) {
                var pat_billing_ids = [];
                $.each($('#billing-body tr td a.doctor-share'), function (i, elem) {
                    pat_billing_ids.push($(elem).data('id'));
                });


                if (pat_billing_ids.length > 0) {
                    $.ajax({
                        url: "{{ route("billing.updateDoctorShareReferral") }}",
                        type: "POST",
                        data: {
                            pat_billing_ids: pat_billing_ids,
                            user_id: $('#js-referal-doctor-id option:selected').data('userid'),
                            username: $('#js-referal-doctor-id option:selected').val(),
                        },
                        dataType: "json",
                        success: function (response) {
                            var status = response.status ? 'success' : 'fail';
                            showAlert(response.message, status);
                        }
                    });
                } else {
                    showAlert('User share not found!', 'error')
                }
            });

            $(document).on('click', '#js-dr-share-submit-btn', function (event) {
                $.ajax({
                    url: "{{ route("billing.doctor-share") }}",
                    type: "POST",
                    data: $('#doctor-share-form').serialize(),
                    success: function (response) {
                        var status = response.status ? 'success' : 'fail';
                        showAlert(response.message, status);

                        // $('#payable-doctors').text(response.doctors);
                        // $('.doctor-share').data('user-ids',response.existingdoctors); //setter
                        $("#billing-body").empty().append(response.view.html);
                        $('#billing-tp-body').empty().append(response.view.tphtml)
                        $('#doctor-share-modal').modal('hide');
                    }
                });
            });

            $(document).on('click', '.doctor-share', function (event) {
                let e = $(this);
                let id = e.data('id');
                let itemname = e.data('itemname');
                let old_ids = e.data('user-ids');
                let types = e.data('type');
                let category_block = "";
                // create type block for modal.
                select_boxes = [];

                if (types.length == 1 && types.includes("referable"))
                    return false;

                $.each(types, function (i, type) {
                    if (type !== 'referable' || type !=='OPD Consultation') {
                        category_block += '<div class="form-group row mb-2 align-items-center">\
                                    <label for="" class="control-label col-sm-12 col-lg-12 mb-0" style="text-transform:capitalize;"><strong>' + type + '</strong></label>\
                                    <div class="col-lg-12 col-sm-12">\
                                        <input type="hidden" class="form-control" name="share_category[' + i + '][type]" value="' + type + '">\
                                    </div>\
                                </div>\
                                <div class="form-group row mb-2 align-items-center">\
                                    <div class="col-lg-12 col-sm-12">\
                                        <select class="form-control modal-select2" data-type="' + type + '" multiple id="select-doctors-' + type + '" name="share_category[' + i + '][doctor_ids][]">\
                                        </select>\
                                    </div>\
                                </div><hr/>';
                        let name = 'share_category' + '[' + i + '][doctor_ids][]';

                        // name of select box list for later iteration.
                        select_boxes.push(name);
                    }
                });

                // prepare the modal.
                $("#doc-share-category-block").html(category_block);
                $(".modal-select2").select2();
                $("#doc-modal-title").html(itemname);
                $("input[name='pat_billing_id']").val(id);
                $("input[name='encounter_id']").val($('#encounter_id').val());
                // validate form.
                $.each(select_boxes, function (j, k) {
                    let select_box = $("select[name='" + k + "']");
                    let type = select_box.data('type');
                    if (type == "OT Dr. Group") {
                        let item_types = getOTGroupList().then(function (res) {

                            // loop through doctor list.
                            let options = "";
                            $.each(res.data, function (i, v) {
                                let selected = "";
                                $.each(old_ids, function (c, t) {
                                    if (t.ot_group_sub_category_id == v.id && t.type == type) {
                                        selected = 'selected';
                                        return;
                                    }
                                });
                                options += '<option value="' + v.id + '" ' + selected + '>' + v.name + '</option>';
                            });

                            // populate options to selectbox.
                            select_box.html(options);
                        });
                    } else {
                        // get doctor list.
                        // id id pat_billing_id
                        let item_types = getDoctorList(id, type).then(function (res) {

                            // loop through doctor list.
                            let options = "";
                            $.each(res, function (i, v) {
                                let selected = "";
                                $.each(old_ids, function (c, t) {
                                    if (t.user_id == v.flduserid && t.type == type) {
                                        selected = 'selected';
                                        return;
                                    }
                                });
                                options += '<option value="' + v.flduserid + '" ' + selected + '>' + v.user.fldfullname + '</option>';
                            });

                            // populate options to selectbox.
                            select_box.html(options);
                        });
                    }
                });
                // show modal.
                $("#doctor-share-form .modal-footer").css("display", "block");
                DOC_SHARE_MODAL.modal('show');
            });

            async function getDoctorList(patId, type) {
                let route = "{!! route('billing.doctor-list', ['billingId' => ':PATBILLING_ID', 'category' => ':CATEGORY']) !!}";
                route = route.replace(':PATBILLING_ID', patId);
                route = route.replace(':CATEGORY', type);
                return await $.ajax({
                    url: route,
                    type: 'GET',
                    dataType: 'JSON',
                    async: true
                });
            }

            // getOTGroupList
            async function getOTGroupList() {
                let route = "{!! route('usershare.ot-group-sub-categories') !!}";
                return await $.ajax({
                    url: route,
                    type: 'GET',
                    dataType: 'JSON',
                    async: true
                });
            }

            // End of doctor share system.

            getPatientProfileColor();
            $('.fonepay-button-save').hide();

        });

        $(function ($) {
            hideAll();
            setTimeout(function () {
                $("#bank-name").select2();
                $('#bank-name').next(".select2-container").hide();
            }, 1500);

            if ($("input[name='payment_mode']:checked").val() === "Credit") {
                hideAll();
                $('#expected_date').show();
                $('.payment-save-done').show();
            }
            function getRadioFunction(value){

                if (value == "Cash") {
                    hideAll();
                    $('#cash_payment').addClass('checked-bak');
                    $('#credit_payment').removeClass('checked-bak');
                    $('#card_payment').removeClass('checked-bak');
                    $('#fonepay_payment').removeClass('checked-bak');
                     $('.payment-save-done').show();
                   $("input[name=payment_mode][value=Cash]").prop('checked', true);

                   $('#received-amount').val($('#grand-total-data').val());

                } else if (value == "Credit") {
                    hideAll();
                    $('#cash_payment').removeClass('checked-bak');
                    $('#credit_payment').addClass('checked-bak');
                    $('#card_payment').removeClass('checked-bak');
                    $('#fonepay_payment').removeClass('checked-bak');
                    $('#expected_date').show();
                    $('.payment-save-done').show();
                    $('#received-amount').empty().val(numberFormatDisplay(0));
                    $("input[name=payment_mode][value=Credit]").prop('checked', true);

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
                    $("input[name=payment_mode][value=Card]").prop('checked', true);
                    $('#received-amount').val($('#grand-total-data').val());
                } else if (value == "Fonepay") {
                    hideAll();
                    $('#cash_payment').removeClass('checked-bak');
                    $('#credit_payment').removeClass('checked-bak');
                    $('#card_payment').removeClass('checked-bak');
                    $('#fonepay_payment').addClass('checked-bak');
                    // $('.fonepay-button-save').show();
                    $('.payment-save-done').show();
                    $("input[name=payment_mode][value=Fonepay]").prop('checked', true);
                    $('#received-amount').val($('#grand-total-data').val());
                } else if (value == "Other") {
                    hideAll();
                    $('#other_reason').show();
                    $('.payment-save-done').show();
                }else{
                    hideAll();
                    $('#cash_payment').addClass('checked-bak');
                    $('#credit_payment').removeClass('checked-bak');
                    $('#card_payment').removeClass('checked-bak');
                    $('#fonepay_payment').removeClass('checked-bak');

                }
                // return false;
            }
            /*On click payment modes*/
            $('#payment_mode').on('change', function () {
                $('#received-amount').empty().val($("#grand-total-data").val());
                if (this.value === "Cash") {
                    hideAll();
                    $('.payment-save-done').show();

                } else if (this.value === "Credit") {
                    hideAll();
                    $('#expected_date').show();
                    $('.payment-save-done').show();
                    $('#received-amount').empty().val(numberFormatDisplay(0));
                } else if (this.value === "Cheque") {
                    hideAll();
                    $('#cheque_number').show();
                    // $("#payment_mode_party").show();
                    /*$("#agent_list").show();*/
                    $('#bank-name').next(".select2-container").show();
                    $('.payment-save-done').show();
                } else if (this.value === "Fonepay") {
                    hideAll();
                    // fonepay-button-save
                    $('.fonepay-button-save').show();
                    $('.payment-save-done').hide();
                } else if (this.value === "Other") {
                    hideAll();
                    $('#other_reason').show();
                    $('.payment-save-done').show();
                }
            });




            $("#patient_req").click(function () {
                var patient_id = $("#patient_id_submit").val();
                var url = $(this).attr("url");
                if (patient_id == '' || patient_id == 0) {
                    alert('Enter patient id');
                } else {
                    $.ajax({
                        url: url,
                        type: "POST",
                        dataType: "json",
                        data: {
                            patient_id: patient_id
                        },
                        success: function (data) {
                            console.log(data);
                            if ($.isEmptyObject(data.error)) {
                                $("#ajax_response_encounter_list").empty();
                                $("#ajax_response_encounter_list").html(data.success.options);
                                $("#encounter_list").modal("show");
                            } else {
                                showAlert("Something went wrong!!");
                            }
                        }
                    });
                }
            });
            $("#patient_id_submit").on('keyup', function (e) {
                if (e.keyCode === 13) {

                    var patient_id = $("#patient_id_submit").val();
                    var url = $('#patient_req').attr("url");
                    if (patient_id == '' || patient_id == 0) {
                        alert('Enter patient id');
                    } else {
                        $.ajax({
                            url: url,
                            type: "POST",
                            dataType: "json",
                            data: {
                                patient_id: patient_id
                            },
                            success: function (data) {

                                if ($.isEmptyObject(data.error)) {
                                    $("#ajax_response_encounter_list").empty();
                                    $("#ajax_response_encounter_list").html(data.success.options);
                                    $("#encounter_list").modal("show");
                                } else {
                                    showAlert("Something went wrong!!");
                                }
                            }
                        });
                    }
                }
            });

            document.getElementById('transaction_payment_date').valueAsDate = new Date();

            document.getElementById('expected_payment_date').valueAsDate = new Date();
            document.getElementById('transaction_payment_date').max = new Date().toISOString().split("T")[0];
            document.getElementById('expected_payment_date').min = new Date().toISOString().split("T")[0];

            $('#save_pan_number').on('click', function () {
                if (!checkPatient()) {
                    return false;
                }
                var patientId = $("#__patient_id").val();
                $.ajax({
                    url: "{{ route('save.pan.number.cashier.form') }}",
                    type: "POST",
                    data: {
                        pan_number: $('#pan_number').val(),
                        patientId: patientId
                    },
                    success: function (data) {
                        showAlert(data.message);
                    },
                    error: function (data) {
                        showAlert("Invalid pan number.", 'error');
                    }
                });
            });

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
            var selectOption = $('<option>', {
                val: '',
                text: '--Select--'
            });

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

                        return $('<option>', {
                            val: d.fldprovince,
                            text: d.fldprovince,
                            selected: (d.fldprovince == provinceId)
                        });
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
                        }, {}
                    );
                    var elems = $.map(districts, function (d) {
                        return $('<option>', {
                            val: d.flddistrict,
                            text: d.flddistrict,
                            selected: (d.flddistrict == districtId)
                        });
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
                        return $('<option>', {
                            val: d,
                            text: d,
                            selected: (d == municipalityId)
                        });
                    });

                    $('#' + municipalityVdcSelector).empty().append(selectOption.clone()).append(elems);
                }
            }

        });


        $(document).on('click','.department-doctor-share',function(event){
            let e = $(this);
            let id = e.data('id');
            $.ajax({
                    url: "{{ route('billing.get.opd.data') }}",
                    type: "POST",
                    data: {
                        pat_billing_id : id,
                    },
                    success: function (data) {
                        console.log(data);
                        $('#department-doctor-modal').modal('show');
                        $('#billing-department').html(data.message.depthtml);
                        $('#billing-doctors').html(data.message.dochtml)
                        $('#pat_billing_id').val(id)
                        $('#docdep-modal-title').html(data.message.itemname);
                    }
                });
        })
        /*function receiveInputWithCash() {
            var paymentmode = ($('#payment_mode').val() || '').toLowerCase();
            if (paymentmode == 'cash') {
                $('#received-amount').val($('#grand-total-data').val());
            }
        }*/

        $(document).ready(function () {
            /*receiveInputWithCash();
            $('#payment_mode').on('change', function () {
                receiveInputWithCash();
            });*/

            $('#js-billing-save-btn').on('click', function () {

                var allowedamt = parseFloat(numberFormat($('#allowedamt').val()));
                var chargedamt = parseFloat(numberFormat($('#chargedamt').val()));
                var billingmode = $('#billingmode').val();

                //alert(allowedamt);

                let submitForm = true;
                var subtotal = parseFloat(numberFormat($('#sub-total-data').val()));

                var disamount = parseFloat(numberFormat($('#discount-amount').val()));
                var paymode = $("input[name='payment_mode']:checked").val();
                // alert(paymode);
                if (paymode == '' || paymode == undefined) {
                    showAlert('Please choose payment mode', 'fail');
                    return false;
                }
                if(disamount > subtotal){
                    showAlert("Discount amount greater than total.", 'error');
                    return false;
                }
                $(".quantity-change").each(function () {
                    var val = parseInt($(this).val());
                    if (val == 0) {
                        submitForm = false;
                    }
                });
                if (submitForm == false) {
                    showAlert("Quantity must be greater than 0.", 'error');
                    return false;
                }
                $(this).prop('disabled', true);
                $('#billing_mode').val($('#billingmode').val());
                $('#discount_mode').val($('#discount-scheme-change').val());

                var total = parseFloat(chargedamt) + parseFloat(numberFormat(subtotal));

                



                if(billingmode.toLowerCase() == 'healthinsurance' || billingmode.toLowerCase() == 'hi' || billingmode.toLowerCase() == 'health insurance'){
                    if( total < allowedamt ){
                        $('#cashier-form').submit();
                    }else{

                        Swal.fire({
                            title: 'Allowed Amount (Rs.' + allowedamt + ') is greater that Billed amount (Rs.' + total + ') for HI Patient. Billing Has Been Cancelled.',
                            showDenyButton: true,
                            //showCancelButton: true,
                            denyButtonText: `Cancel`,
                            allowOutsideClick: false,
                            confirmButtonText: 'Ok',
                            }).then((result) => {
                            /* Read more about isConfirmed, isDenied below */
                            if (result.isConfirmed) {
                                //$('#cashier-form').submit();
                                $(this).prop('disabled', false);
                            } else if (result.isDenied) {
                                 $(this).prop('disabled', false);
                                Swal.fire('Billing Cancelled.', '', 'info')
                            }
                            })

                        //if(confirm('Allowed Amount (Rs.' + allowedamt + ') is greater that Billed amount (Rs.' + total + ') for HI Patient! Do you really want to continue?')) {
                           // $('#cashier-form').submit();
                       // }else{
                         //   $(this).prop('disabled', false);
                        //}

                    }
                }else{
                    $('#cashier-form').submit();
                }
                

            });
        });

        function hideAll() {
            // $('#payment_mode_party').hide();
            $('#office-name').hide();
            $('#bank-name').next(".select2-container").hide();
            /*$('#agent_list').hide();*/
            $('#expected_date').hide();
            $('#cheque_number').hide();
            $('#office_name').hide();
            $('#other_reason').hide();
            $('#save-button-pakage-service').hide();
            $('.fonepay-button-save').hide();
        }

        function getSoldItem() {
            if (!checkPatient()) {
                return false;
            }
            item_type = $("input[name='item_type']:checked").val();
            billingMode = $("#billingmode").val();

            $.ajax({
                url: "{{ route('billing.get.items.by.service.or.inventory') }}",
                type: "POST",
                data: {
                    item_type: item_type,
                    billingMode: billingMode
                },
                success: function (data) {
                    $('.file-modal-title').empty().text(item_type);
                    $('.file-form-data').empty().append(data);
                    $('.modal-dialog').addClass('modal-lg');
                    $('.modal-footer').hide();
                    // console.log(data);
                    $('#file-modal').modal('show');
                    $('#billing_type_payment').val($("#discount-scheme-change").val());
                }
            });
        }

        function saveServiceCosting() {
            if (!checkPatient()) {
                return false;
            }

            // if (document.getElementById('show-temporary-items') && document.getElementById('show-temporary-items').checked) {
            //     serializedData = $("#pharmacy-form").serialize() + '&temp_checked=yes&discountMode=' + $("#discount-scheme-change").val() + '&billingMode=' + $("#billingmode").val();
            // } else {
            //     serializedData = $("#pharmacy-form").serialize() + '&temp_checked=no&discountMode=' + $("#discount-scheme-change").val() + '&billingMode=' + $("#billingmode").val();
            // }
            serializedData = $("#pharmacy-form").serialize() + '&temp_checked=no&discountMode=' + $("#discount-scheme-change").val() + '&billingMode=' + $("#billingmode").val();

            $.ajax({
                url: "{{ route('billing.save.items.by.service') }}",
                type: "POST",
                data: serializedData,
                success: function (data) {
                    // console.log(data);
                    if (data.status === true) {

                        $("#billing-body").empty().append(data.message.tableData);
                        $("#sub-total-data").empty().val(numberFormatDisplay((parseFloat(data.message.total) + parseFloat(data.message.discount)) - parseFloat(data.message.tax)));
                        $("#discount-amount").val(numberFormatDisplay((data.message.discount)));
                        $("#table-bill-total").empty().val(numberFormatDisplay((data.message.total)));
                        $("#tax-total-data").empty().val(numberFormatDisplay((data.message.tax)));
                        $("#grand-total-data").empty().val(numberFormatDisplay((data.message.total)));

                        let encounter_for_received_amount = $("#encounter_id").val();

                        // if (encounter_for_received_amount.substring(0, 2) === "IP" || encounter_for_received_amount.substring(0, 2) === "ER") {
                        //     $("#received-amount").val(0);
                        // } else {
                            $("#received-amount").val(numberFormatDisplay((data.message.total)));
                        // }


                        // $("#discount-scheme-change").prop('disabled', true);
                        $('#file-modal').modal('hide');
                        showAlert('Added successfully.');
                    }
                }
            });
        }

        $('#discount-scheme-change').on('change', function(){
            var discountMode = $(this).val();
            var encounter = $('#encounter_id').val();
            if(encounter == ''){
                showAlert('Encounter not available');
                return false;
            }
            $.ajax({
                url: "{{ route('billing.change.discountMode.service') }}",
                type: "POST",
                data: {
                    discount: discountMode,
                    encounterId: encounter,
                    temp_checked: 'no'
                },
                success: function (data) {
                    if (data.status === true) {
                        var bulksubtotal = (parseFloat(data.message.total) + parseFloat(data.message.discount)) - parseFloat(data.message.tax);
                        $("#billing-body").empty().append(data.message.tableData);
                        $("#sub-total-data").empty().val(numberFormatDisplay((bulksubtotal)));
                        $("#discount-amount").val(numberFormatDisplay((data.message.discount)));
                        $("#table-bill-total").empty().val(numberFormatDisplay((data.message.total)));
                        $("#tax-total-data").empty().val(numberFormatDisplay((data.message.tax)));
                        $("#grand-total-data").empty().val(numberFormatDisplay((data.message.total)));
                        $('.depAmount').text(numberFormatDisplay(data.message.depositAmount));
                        $('.tpAmount').text(numberFormatDisplay(data.message.tpAmount));
                        $('.remainingAmount').text(numberFormatDisplay(data.message.remainingAmount));
                        let encounter_for_received_amount = $("#encounter_id").val();
                        var paymode = $("input[name='payment_mode']:checked").val();

                        if (paymode == 'Credit' || paymode == undefined) {
                            $("#received-amount").val(numberFormatDisplay(0));
                        }else{
                            // if (encounter_for_received_amount.substring(0, 2) === "IP" || encounter_for_received_amount.substring(0, 2) === "ER") {
                        //     $("#received-amount").val(0);
                        // } else {
                            $("#received-amount").val(numberFormatDisplay((data.message.total)));
                        // }
                        }

                        showAlert('Updated successfully.');
                    }
                }
            });
        })

        $(document).on('focusout', '#discount-amount', function () {
            var subtotal = parseFloat(numberFormat($('#sub-total-data').val()));

                var disamount = parseFloat(numberFormat($('#discount-amount').val()));
                var alloweddiscount = parseFloat(numberFormat($('#allowed_discount_amount').val()));
                if(disamount > alloweddiscount){
                    $('#discount-amount').val('');
                    showAlert("Discount amount greater than "+alloweddiscount+".", 'error');
                    return false;
                }
                if(disamount > subtotal){
                    $('#discount-amount').val('');
                    showAlert("Discount amount greater than total.", 'error');
                    return false;
                }
            var fldids = [];
            $.each($('.discount-change'), function (i, elem) {
                fldids.push($(elem).attr('fldid'));
            });
            var discount = $(this).val().replace(/[^0-9.]/g, '');
            $(this).val(numberFormatDisplay(discount));
            var subtotal = parseFloat(numberFormat($('#sub-total-data').val()));

            var new_discount = (discount) * 100 / alloweddiscount;

            $.ajax({
                url: "{{ route('billing.change.discountBulk.service') }}",
                type: "POST",
                data: {
                    fldids: fldids,
                    new_discount: new_discount,
                    temp_checked: 'no'
                },
                success: function (data) {
                    if (data.status === true) {
                        var bulksubtotal = (parseFloat(data.message.total) + parseFloat(data.message.discount)) - parseFloat(data.message.tax);
                        $("#billing-body").empty().append(data.message.tableData);
                        $("#sub-total-data").empty().val(numberFormatDisplay((bulksubtotal)));
                        $("#discount-amount").val(numberFormatDisplay((data.message.discount)));
                        $("#table-bill-total").empty().val(numberFormatDisplay((data.message.total)));
                        $("#tax-total-data").empty().val(numberFormatDisplay((data.message.tax)));
                        $("#grand-total-data").empty().val(numberFormatDisplay((data.message.total)));
                        $('.depAmount').text(numberFormatDisplay(data.message.depositAmount));
                        $('.tpAmount').text(numberFormatDisplay(data.message.tpAmount));
                        $('.remainingAmount').text(numberFormatDisplay(data.message.remainingAmount));
                        let encounter_for_received_amount = $("#encounter_id").val();
                        var paymode = $("input[name='payment_mode']:checked").val();

                        if (paymode == 'Credit' || paymode == undefined) {
                            $("#received-amount").val(numberFormatDisplay(0));
                        }else{
                            // if (encounter_for_received_amount.substring(0, 2) === "IP" || encounter_for_received_amount.substring(0, 2) === "ER") {
                        //     $("#received-amount").val(0);
                        // } else {
                            $("#received-amount").val(numberFormatDisplay((data.message.total)));
                        // }
                        }



                        // $("#discount-scheme-change").prop('disabled', true);
                        $('#file-modal').modal('hide');
                        showAlert('Added successfully.');
                    }
                }
            });
        });

        $(document).on('focusout', '#discount-percent', function () {
            var discount = $(this).val().replace(/[^0-9.]/g, '');
            $(this).val(numberFormatDisplay(discount));

            var alloweddiscount = parseFloat(numberFormat($('#allowed_discount_amount').val()));
            var disamount = parseFloat(numberFormat((discount/100)*alloweddiscount));
            if(disamount > alloweddiscount || disamount == 0){
                $('#discount-amount').val('');
                showAlert("Discount amount cannot be greater than or equal to "+alloweddiscount+".", 'error');
                return false;
            }
            if (discount > 100 || discount < 0) {
                $('#discount-percent').val('');
                showAlert('Discount percent cannot be greater than 100 or negative.', 'error');
                return false;
            }
            var fldids = [];
            $.each($('.discount-change'), function (i, elem) {
                fldids.push($(elem).attr('fldid'));
            });


            var subtotal = parseFloat(numberFormat($('#sub-total-data').text().trim()) || 0);
            var new_discount = parseFloat(numberFormat((discount * 100) / subtotal));



            $.ajax({
                url: "{{ route('billing.change.discount.percent.bulk.service') }}",
                type: "POST",
                data: {
                    fldids: fldids,
                    new_discount: discount,
                    temp_checked: 'no'
                },
                success: function (data) {
                    if (data.status === true) {
                        $("#billing-body").empty().append(data.message.tableData);
                        $("#sub-total-data").empty().val(numberFormatDisplay(parseFloat(data.message.total) + parseFloat(data.message.discount)));
                        $("#discount-amount").val(numberFormatDisplay((data.message.discount)));
                        if(data.message.discount_percent == 100){
                            $("#table-bill-total").empty().val(numberFormatDisplay(0));
                            $("#tax-total-data").empty().val(numberFormatDisplay(0));
                            $("#grand-total-data").empty().val(numberFormatDisplay(0));
                        }else{
                            $("#table-bill-total").empty().val(numberFormatDisplay((data.message.total)));
                            $("#tax-total-data").empty().val(numberFormatDisplay((data.message.tax)));
                            $("#grand-total-data").empty().val(numberFormatDisplay((data.message.total)));
                        }


                        let encounter_for_received_amount = $("#encounter_id").val();
                        var paymode = $("input[name='payment_mode']:checked").val();

                            if (paymode == 'Credit' || paymode == undefined) {
                                 $("#received-amount").val(numberFormatDisplay(0));
                            }else{
                                 // if (encounter_for_received_amount.substring(0, 2) === "IP" || encounter_for_received_amount.substring(0, 2) === "ER") {
                            //     $("#received-amount").val(0);
                            // } else {
                                if(data.message.discount_percent == 100){
                                    $("#received-amount").val(numberFormatDisplay(0));
                                }else{
                                    $("#received-amount").val(numberFormatDisplay((data.message.total)));
                                }

                            // }
                            }



                        // $("#discount-scheme-change").prop('disabled', true);
                        $('#file-modal').modal('hide');
                        showAlert('Added successfully.');
                    }
                }
            });
        });
        $(document).on('change', '.service-pack', function () {
            saveServiceByRadio();
        });

        function saveServiceByRadio() {

            if (!checkPatient()) {
                showAlert('Select encounter.', 'error');
                return false;
            }

            discountMode = $("#discount-scheme-change").val();
            billingMode = $("#billingmode").val();
            encounter_id_payment = $(".__encounter_id").val();
            serviceItem = $('.service-pack option:selected').val();
            serviceItemText = $('.service-pack option:selected').text();
            serviceType = serviceItemText.split('||');
            var inc = 0;
            $('#billing-body tr').each(function(i, el){
                var value1 = $(el).children().eq(1).text();

                if(value1 === serviceType[0].trim()){
                    inc = (inc)+1;
                }

            })
            if(inc > 0){
                if (!confirm(serviceItem + ' has already been added. Do you want to continue'))
                    return false;
            }



            /*if (document.getElementById('show-temporary-items') && document.getElementById('show-temporary-items').checked) {
                temp_checked = 'yes';
            } else {
                temp_checked = 'no';
            }*/
            temp_checked = 'no';
            if ($("input[name='servicetype']:checked").val() === "service") {
                $.ajax({
                    url: "{{ route('billing.save.items.by.service') }}",
                    type: "POST",
                    data: {
                        discountMode: discountMode,
                        billingMode: billingMode,
                        encounter_id_payment: encounter_id_payment,
                        serviceItem: serviceItem,
                        temp_checked: 'no',
                    },
                    success: function (data) {
                        // console.log(data);
                        if (data.status === true) {
                            $("#billing-body").empty().append(data.message.tableData);
                            $("#sub-total-data").empty().val(numberFormatDisplay((parseFloat(data.message.total) + parseFloat(data.message.discount)) - parseFloat(data.message.tax)));
                            $("#discount-amount").val(numberFormatDisplay((data.message.discount)));
                            $("#table-bill-total").empty().val(numberFormatDisplay((data.message.total)));
                            $("#tax-total-data").empty().val(numberFormatDisplay((data.message.tax)));
                            $("#grand-total-data").empty().val(numberFormatDisplay((data.message.total)));

                            let encounter_for_received_amount = $("#encounter_id").val();

                            var paymode = $("input[name='payment_mode']:checked").val();

                            if (paymode == 'Credit' || paymode == undefined) {
                                $("#received-amount").val(numberFormatDisplay(0));
                            }else{
                                // if (encounter_for_received_amount.substring(0, 2) === "IP" || encounter_for_received_amount.substring(0, 2) === "ER") {
                            //     $("#received-amount").val(0);
                            // } else {
                                $("#received-amount").empty().val(numberFormatDisplay((data.message.total)));
                            // }
                            }



                            // $("#discount-scheme-change").prop('disabled', true);
                            $('#file-modal').modal('hide');
                            // $('.service-pack option:eq(0)').prop('selected',true);
                            // $(".service-pack").val('').trigger('change')
                            // $('.service-pack').select2('open');
                            if(data.message.opd_consultation == '1'){
                                $('#department-doctor-modal').modal('show');
                                $('#billing-department').html(data.message.departmenthtml);
                                $('#pat_billing_id').val(data.message.pat_billing_id)
                                $('#docdep-modal-title').html(data.message.itemname);
                            }else if(data.message.opd_consultation == '2'){
                                $("#billing-body").find("tr").eq(1).find('.doctor-share').trigger('click');
                            }

                            // $('#billItemType').val(serviceType[1]);
                            // $('#billingMode').val(billingMode);
                            // if(data.message.taxper){
                            //     $('#billTax').val(data.message.taxper);
                            // }
                            // $('#billItemname').val(serviceItem);
                            showAlert('Added successfully.');

                            // setTimeout(() => {
                            //     receiveInputWithCash();
                            // }, 200);
                        }
                    }
                });
            }

            if ($("input[name='servicetype']:checked").val() === "package") {
                $.ajax({
                    url: "{{ route('billing.show.add.group') }}",
                    type: "POST",
                    data: {
                        discountMode: discountMode,
                        __billing_mode: billingMode,
                        __encounter_id: encounter_id_payment,
                        groupTest: $('.group-id-for-ajax').val(),
                        temp_checked: temp_checked,
                        serviceItem: $('.service-pack option:selected').val()
                    },
                    success: function (data) {
                        // console.log(data);
                        window.location.href = window.location.href;
                    }
                });
            }

        }

        function checkPatient() {
            var patient_id = $("#encounter_id").val();
            if (patient_id === '' || patient_id === 0 || patient_id === undefined) {
                showAlert('Enter patient id', ' ');
                return false;
            }
            return true;
        }

        function showTemporaryBill() {
            if (!checkPatient()) {
                return false;
            }

            if (document.getElementById('show-temporary-items') && document.getElementById('show-temporary-items').checked) {
                $("#is_credit_checked").val('yes');
                $.ajax({
                    url: "{{ route('billing.display.temporary.data') }}",
                    type: "POST",
                    data: {
                        encounter_id: $('#encounter_id').val(),
                        show_temporary: 'yes'
                    },
                    success: function (data) {
                        // console.log(data);
                        if (data.status === true) {
                            $("#billing-body").empty().append(data.message.tableData);
                            $("#sub-total-data").empty().val(numberFormatDisplay((parseFloat(data.message.total) + parseFloat(data.message.discount)) - parseFloat(data.message.tax)));
                            $("#discount-amount").val(numberFormatDisplay((data.message.discount)));
                            $("#table-bill-total").empty().val(numberFormatDisplay((data.message.total)));
                            $("#tax-total-data").empty().val(numberFormatDisplay((data.message.tax)));
                            $("#grand-total-data").empty().val(numberFormatDisplay((data.message.total)));

                            let encounter_for_received_amount = $("#encounter_id").val();

                            // if (encounter_for_received_amount.substring(0, 2) === "IP" || encounter_for_received_amount.substring(0, 2) === "ER") {
                            //     $("#received-amount").val(0);
                            // } else {
                                $("#received-amount").val(numberFormatDisplay((data.message.total)));
                            // }


                            $('#file-modal').modal('hide');
                        }
                    }
                });
            } else {
                $("#is_credit_checked").val('no');
                $.ajax({
                    url: "{{ route('billing.display.temporary.data') }}",
                    type: "POST",
                    data: {
                        encounter_id: $('#encounter_id').val(),
                        show_temporary: 'no'
                    },
                    success: function (data) {
                        // console.log(data);
                        if (data.status === true) {
                            $("#billing-body").empty().append(data.message.tableData);
                            $("#sub-total-data").empty().val(numberFormatDisplay(parseFloat(data.message.total) + parseFloat(data.message.discount) - parseFloat(data.message.tax)));
                            $("#discount-amount").val(numberFormatDisplay((data.message.discount)));
                            $("#table-bill-total").empty().val(numberFormatDisplay((data.message.total)));
                            $("#tax-total-data").empty().val(numberFormatDisplay((data.message.tax)));
                            $("#grand-total-data").empty().val(numberFormatDisplay((data.message.total)));

                            let encounter_for_received_amount = $("#encounter_id").val();

                            // if (encounter_for_received_amount.substring(0, 2) === "IP" || encounter_for_received_amount.substring(0, 2) === "ER") {
                            //     $("#received-amount").val(0);
                            // } else {
                                $("#received-amount").val(numberFormatDisplay((data.message.total)));
                            // }


                            $('#file-modal').modal('hide');
                        }
                    }
                });
            }
        }


        $(window).on('load', function () {
            var billingmode = $('#billingmode').val();

            if(billingmode.toLowerCase() == 'health insurance' || billingmode.toLowerCase() == 'hi' || billingmode.toLowerCase() == 'healthinsurance' ){
                $('#card_payment').hide();
                $('#fonepay_payment').hide();
            }

            
        });

        $(window).ready(function () {
            $("#js-registration-last-name").select2();

            $("#tender-amount").on('blur', function () {
                grandTotal = numberFormat($("#grand-total-data").val());
                tender =  numberFormat($("#tender-amount").val());
                returnValue = parseFloat(tender) - parseFloat(grandTotal);
                $("#return-amount").val(numberFormatDisplay(returnValue));
            });


            $('#billing-selection-type-change').prop('checked', true);

            $('.billing-popup').hide();
            $(".billing-dropdown").show();

        });

        $(document).on('keyup', '.select2-search__field', function (e) {
            var id = $(this).closest('.select2-dropdown').find('.select2-results ul').attr('id');
            var flditem = $(this).val() || '';
            if ((id == "select2-js-registration-last-name-results" || id == "select2-js-registration-last-name-old-results") && e.keyCode === 13 && flditem != '') {
                var data = {
                    flditem: flditem,
                };
                $.ajax({
                    url: baseUrl + '/registrationform/addSurname',
                    type: "POST",
                    data: data,
                    dataType: "json",
                    success: function (response) {
                        if (response.status) {
                            var activeForm = $('#new-user-add-form');
                            var val = response.data;
                            var newOption = new Option(val.flditem, val.flditem, true, true);
                            $(activeForm).find('.js-registration-last-name').append(newOption).trigger('change');
                            $(activeForm).find('.js-registration-last-name').val(val.flditem).trigger('change');
                            $(activeForm).find('.js-registration-last-name').select2("close");
                        }
                        showAlert(response.message);
                    }
                });
            }
        });

        /**tp bill submit*/
        function saveTpBill() {
            let submitFormTP = true;
            var subtotal = $('#sub-total-data').val();

                var disamount = parseFloat(numberFormat($('#discount-amount').val()));
                var referaldoctor = $('#js-referal-doctor-id option:selected').val();
                if(referaldoctor == '' || referaldoctor == undefined){
                    showAlert('Please select referal doctor name.');
                    return false;
                }
               var paymode = $("input[name='payment_mode']:checked").val();

                if (paymode == '' || paymode == undefined) {
                    showAlert('Please choose payment mode', 'fail');
                    return false;
                }
                if(disamount > subtotal){
                    showAlert("Discount amount greater than total.", 'error');
                    return false;
                }
            $(".quantity-change").each(function () {
                var val = parseInt($(this).val());
                if (val == 0) {
                    submitFormTP = false;
                }
            });

            if (submitFormTP == false) {
                showAlert("Quantity must be greater than 0.", 'error');
                return false;
            }
            if ($(".share_check").length) {
                var share_check_enable = 1;
              } else {
                var share_check_enable = 0;
              }

            if(share_check_enable == 1){
                var payabledoc = $('#payable-doctors').text();
                if(payabledoc == ''){
                    showAlert('Doctor share not given');
                    return false;
                }
            }
            $.ajax({
                url: "{{ route('billing.tp.bill') }}",
                type: "POST",
                data: $('#cashier-form').serialize(),
                success: function (data) {
                    // console.log(data);
                    if (data.status === true) {
                        $("#billing-body").empty().append(data.message.tableData);
                        $("#sub-total-data").empty().val(numberFormatDisplay((parseFloat(data.message.total) + parseFloat(data.message.discount)) - parseFloat(data.message.tax)));
                        $("#discount-amount").val(numberFormatDisplay((data.message.discount)));
                        $("#table-bill-total").empty().val(numberFormatDisplay((data.message.total)));
                        $("#tax-total-data").empty().val(numberFormatDisplay((data.message.tax)));
                        $("#grand-total-data").empty().val(numberFormatDisplay((data.message.total)));

                        let encounter_for_received_amount = $("#encounter_id").val();

                        if (encounter_for_received_amount.substring(0, 2) === "IP" || encounter_for_received_amount.substring(0, 2) === "ER") {
                            $("#received-amount").val(numberFormatDisplay(0));
                        } else {
                            $("#received-amount").val(numberFormatDisplay(data.message.total));
                        }

                        $('#divtoprint').html(data.message.invoice_html);

                        // $("#discount-scheme-change").prop('disabled', true);
                        // $('#file-modal').modal('hide');

                        /**display invoice for TP*/
                        var params = {
                            encounter_id: $(".__encounter_id").val(),
                            tp_bill_no: data.message.tp_bill_no
                        };
                        var queryString = $.param(params);
                        window.location.href = window.location.href;
                        window.open("{{ route('tp.billing.display.invoice') }}?" + queryString, '_blank');

                        showAlert('Added successfully.');
                        // printInvoice();
                    } else {
                        showAlert(data.message, 'error');
                    }
                }
            });
        }
        function printInvoice() {
            const printContents = document.getElementById('divtoprint').innerHTML;
            const originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
        /**discount type*/
        $(window).ready(function () {
            $('#discount-amount').prop('readonly', true);
            $("#discount-percent-container").hide();
            $("#discount_type_change").on('change', function () {
                if ($(this).val() === "fixed") {
                    $('#discount-amount').prop('readonly', false);
                    $("#discount-percent").val('');
                    $("#discount-percent-container").hide();

                }
                if ($(this).val() === "percentage") {
                    $('#discount-amount').prop('readonly', true);
                    $("#discount-percent-container").show();
                    $('#discount-amount').val('');
                }
                if ($(this).val() === "no_discount") {
                    $('#discount-amount').prop('readonly', true);
                    $("#discount-percent-container").hide();
                    $('#discount-amount').val('');
                }
            });

            let encounter_for_received_amount = $("#encounter_id").val();

            // if (encounter_for_received_amount.substring(0, 2) === "IP" || encounter_for_received_amount.substring(0, 2) === "ER") {
            //     $("#received-amount").val(0);
            // } else {
                $("#received-amount").val(numberFormatDisplay($("#grand-total-data").val()));
            // }

        })
    </script>
    <script>

    $('.img-ms-form').click(function() {

    $(this).parent().find('input[type=radio]').prop('checked', true);

    });
    $('.pay-rad').click(function() {

    $(this).closest('.checked-bak').find('input[type=radio]').prop('checked', true);

    });
    function fonepayQrGenerate(encounter,share_check_enable) {

        let route = "{!! route('convergent.payments', ['encounter' => ':ENCOUNTER']) !!}";
        route = route.replace(':ENCOUNTER', encounter);
        $.ajax({
            url: route,
            type: "POST",
            success: function (data) {

                console.log(data);
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
    @if(Session::get('display_generated_invoice'))
        <script>
            var params = {
                encounter_id: "{{Session::get('last_encounter_id')}}",
                invoice_number: "{{Session::get('invoice_number')}}"
            };
            var queryString = $.param(params);


            window.open("{{ route('cashier.form.display.invoice') }}?" + queryString, '_blank');
        </script>
    @endif

    {{--disable service radion if there is already package items in grid--}}
    @if(isset($serviceDataPackage) && $serviceDataPackage != null and (strtoupper(substr($enpatient->fldencounterval, 0,2)) != "IP"))
        <script>
            $(window).ready(function () {
                $("#Package").prop("checked", true).trigger("click");
                $("#Service").attr("disabled", true);
            });
        </script>
    @else
        <script>
            $(window).ready(function () {
                $("#Service").prop("checked", true).trigger("click");
            });
        </script>
    @endif


@endpush