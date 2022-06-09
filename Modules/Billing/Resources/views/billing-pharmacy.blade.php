@extends('frontend.layouts.master')
@section('content')
    @php
        $segment = Request::segment(1);

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
    @endphp
    <div class="container-fluid">
        <div class="row">
            @include('billing::common.patient-profile')
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Sales Mode Outstanding</h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <input type="hidden" id="user_billing_mode" value="@if(isset($enpatient) && isset($enpatient->fldbillingmode) ) {{$enpatient->fldbillingmode}} @endif" disabled>
                        <div class="form-horizontal border-bottom">
                            <div class="row">
                                <div class="col-sm-7">
                                    {{--<div class="form-group form-row">
                                        <label class="col-sm-3">Load From</label>
                                        <div class="col-sm-9">
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input">
                                                <label class="custom-control-label">Sale Order</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input">
                                                <label class="custom-control-label">Sale Challan</label>
                                            </div>
                                        </div>
                                    </div>--}}
                                </div>
                                <div class="col-sm-5">
                                    <div class="form-group form-row">
                                        <lable class="col-sm-3">Warehouse</lable>
                                        <div class="col-sm-9">
                                            <select class="form-control">
                                                <option value="0">---select---</option>
                                                <option value="1">Main Store</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-horizontal border-bottom pt-3">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-3">Tax Type</label>
                                        <div class="col-sm-9">
                                            <select class="form-control">
                                                <option value="0">---select---</option>
                                                <option value="1">Exclusive</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                {{--<div class="col-sm-4">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-2">Bill No.</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="" id="" placeholder="Bill No" class="form-control">
                                        </div>
                                    </div>
                                </div>--}}
                                <div class="col-sm-4">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-5">Transaction Date</label>
                                        <div class="col-sm-7">
                                            <div class="input-group">
                                                <input type="text" name="" id="" placeholder="DD/MM/YYY" class="form-control">
                                                <div class="input-group-append">
                                                    <div class="input-group-text"><i class="ri-calendar-2-fill"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-3">Payment Mode</label>
                                        <div class="col-sm-9">
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" name="payment_mode" value="cash" class="custom-control-input" checked>
                                                <label class="custom-control-label" id="payment_mode_cash">Cash</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" name="payment_mode" value="credit" class="custom-control-input">
                                                <label class="custom-control-label" id="payment_mode_credit">Credit</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" name="payment_mode" value="cheque" class="custom-control-input">
                                                <label class="custom-control-label" id="payment_mode_cheque">Cheque</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" name="payment_mode" value="other" class="custom-control-input">
                                                <label class="custom-control-label" id="payment_mode_other">Other</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group form-row align-items-center" id="expected_date">
                                        <label class="col-sm-5">Expected Payment Date</label>
                                        <div class="col-sm-7">
                                            <div class="input-group">
                                                <input type="text" name="" id="" placeholder="DD/MM/YYY" class="form-control">
                                                <div class="input-group-append">
                                                    <div class="input-group-text"><i class="ri-calendar-2-fill"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{--if cash--}}
                        <div class="form-horizontal border-bottom pt-3">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group form-row align-items-center" id="payment_mode_party">
                                        <label class="col-sm-3">Sent To</label>
                                        <div class="col-sm-9">
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" name="sent_to" value="customer" class="custom-control-input" checked>
                                                <label class="custom-control-label" id="payment_customer">Customer</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" name="sent_to" value="office" class="custom-control-input">
                                                <label class="custom-control-label" id="payment_office">Office</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <input type="text" name="office_name" id="office_name" placeholder="Office Name" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <input type="text" name="cheque_number" id="cheque_number" placeholder="Cheque Number" class="form-control">
                                        <input type="text" name="other_reason" id="other_reason" placeholder="Reason" class="form-control">
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <select name="office_name" id="bank-name" class="form-control">
                                            <option value="" disabled></option>
                                            <option value="Nepal Bank Ltd. (NBL)">Nepal Bank Ltd. (NBL)</option>
                                            <option value="Rastriya Banijya Bank Ltd. (RBB)">Rastriya Banijya Bank Ltd. (RBB)</option>
                                            <option value="Nabil Bank Ltd. (NABIL)">Nabil Bank Ltd. (NABIL)</option>
                                            <option value="Nepal Investment Bank Ltd. (NIBL)">Nepal Investment Bank Ltd. (NIBL)</option>
                                            <option value="Standard Chartered Bank Nepal Ltd. (SCBNL)">Standard Chartered Bank Nepal Ltd. (SCBNL)</option>
                                            <option value="Himalayan Bank Ltd. (HBL)">Himalayan Bank Ltd. (HBL)</option>
                                            <option value="Nepal SBI Bank Ltd. (NSBI)">Nepal SBI Bank Ltd. (NSBI)</option>
                                            <option value="Nepal Bangaladesh Bank Ltd. (NBB)">Nepal Bangaladesh Bank Ltd. (NBB)</option>
                                            <option value="Everest Bank Ltd. (EBL)">Everest Bank Ltd. (EBL)</option>
                                            <option value="Bank of Kathmandu Lumbini Ltd. (BOK)">Bank of Kathmandu Lumbini Ltd. (BOK)</option>
                                            <option value="Nepal Credit and Commerce Bank Ltd. (NCC)">Nepal Credit and Commerce Bank Ltd. (NCC)</option>
                                            <option value="NIC ASIA Bank Ltd. (NIC)">NIC ASIA Bank Ltd. (NIC)</option>
                                            <option value="Machhapuchhre Bank Ltd. (MBL)">Machhapuchhre Bank Ltd. (MBL)</option>
                                            <option value="Kumari Bank Ltd. (Kumari)">Kumari Bank Ltd. (Kumari)</option>
                                            <option value="Laxmi Bank Ltd. (Laxmi)">Laxmi Bank Ltd. (Laxmi)</option>
                                            <option value="Siddhartha Bank Ltd. (SBL)">Siddhartha Bank Ltd. (SBL)</option>
                                            <option value="Agriculture Development Bank Ltd. (ADBNL)">Agriculture Development Bank Ltd. (ADBNL)</option>
                                            <option value="Global IME Bank Ltd. (Global)">Global IME Bank Ltd. (Global)</option>
                                            <option value="Citizens Bank International Ltd. (Citizens)">Citizens Bank International Ltd. (Citizens)</option>
                                            <option value="Prime Commercial Bank Ltd. (Prime)">Prime Commercial Bank Ltd. (Prime)</option>
                                            <option value="Sunrise Bank Ltd. (Sunrise)">Sunrise Bank Ltd. (Sunrise)</option>
                                            <option value="NMB Bank Ltd. (NMB)">NMB Bank Ltd. (NMB)</option>
                                            <option value="Prabhu Bank Ltd. (PRABHU)">Prabhu Bank Ltd. (PRABHU)</option>
                                            <option value="Mega Bank Nepal Ltd. (Mega)">Mega Bank Nepal Ltd. (Mega)</option>
                                            <option value="Civil Bank Ltd. (Civil)">Civil Bank Ltd. (Civil)</option>
                                            <option value="Century Commercial Bank Ltd. (Century)">Century Commercial Bank Ltd. (Century)</option>
                                            <option value="Sanima Bank Ltd. (Sanima)">Sanima Bank Ltd. (Sanima)</option>
                                            <option value="Narayani Development Bank Ltd.">Narayani Development Bank Ltd.</option>
                                            <option value="Sahayogi Vikas Bank Ltd. Sahayogi">Sahayogi Vikas Bank Ltd. Sahayogi</option>
                                            <option value="Karnali Development Bank Ltd.">Karnali Development Bank Ltd.</option>
                                            <option value="Excel Development Bank Ltd">Excel Development Bank Ltd</option>
                                            <option value="Miteri Development Bank Ltd.">Miteri Development Bank Ltd.</option>
                                            <option value="Muktinath Bikas Bank Ltd.">Muktinath Bikas Bank Ltd.</option>
                                            <option value="Corporate Development Bank Ltd.">Corporate Development Bank Ltd.</option>
                                            <option value="Kanchan Development Bank Ltd.">Kanchan Development Bank Ltd.</option>
                                            <option value="Sindhu Bikas Bank Ltd.">Sindhu Bikas Bank Ltd.</option>
                                            <option value="Sahara Bikas Bank Ltd.">Sahara Bikas Bank Ltd.</option>
                                            <option value="Salapa Bikash Bank Ltd.">Salapa Bikash Bank Ltd.</option>
                                            <option value="Green Development Bank Ltd.">Green Development Bank Ltd.</option>
                                            <option value="Sangrila Development Bank Ltd.">Sangrila Development Bank Ltd.</option>
                                            <option value="Deva Development Bank Ltd.">Deva Development Bank Ltd.</option>
                                            <option value="Kailash Bikash Bank Ltd.">Kailash Bikash Bank Ltd.</option>
                                            <option value="Shine Resunga Development Bank Ltd.">Shine Resunga Development Bank Ltd.</option>
                                            <option value="Jyoti Bikas Bank Ltd.">Jyoti Bikas Bank Ltd.</option>
                                            <option value="Garima Bikas Bank Ltd.">Garima Bikas Bank Ltd.</option>
                                            <option value="Mahalaxmi Bikas Bank Ltd.">Mahalaxmi Bikas Bank Ltd.</option>
                                            <option value="Gandaki Bikas Bank Ltd.">Gandaki Bikas Bank Ltd.</option>
                                            <option value="Lumbini Bikas Bank Ltd.">Lumbini Bikas Bank Ltd.</option>
                                            <option value="Kamana Sewa Bikas Bank Ltd.">Kamana Sewa Bikas Bank Ltd.</option>
                                            <option value="Saptakoshi Development Bank Ltd.">Saptakoshi Development Bank Ltd.</option>
                                            <option value="Tinau Mission Bikas Bank Ltd.">Tinau Mission Bikas Bank Ltd.</option>
                                            <option value="Nepal Finance Ltd.">Nepal Finance Ltd.</option>
                                            <option value="Nepal Share Markets and Finance Ltd.">Nepal Share Markets and Finance Ltd.</option>
                                            <option value="Gurkhas Finance Ltd.">Gurkhas Finance Ltd.</option>
                                            <option value="Goodwill Finance Ltd.">Goodwill Finance Ltd.</option>
                                            <option value="Shree Investment & Finance Co. Ltd.">Shree Investment & Finance Co. Ltd.</option>
                                            <option value="Lalitpur Finance Co. Ltd.">Lalitpur Finance Co. Ltd.</option>
                                            <option value="United Finance Co. Ltd. United">United Finance Co. Ltd. United</option>
                                            <option value="Best Finance Ltd.">Best Finance Ltd.</option>
                                            <option value="Progressive Finance Co. Ltd.">Progressive Finance Co. Ltd.</option>
                                            <option value="Janaki Finance Co. Ltd.">Janaki Finance Co. Ltd.</option>
                                            <option value="Pokhara Finance Ltd.">Pokhara Finance Ltd.</option>
                                            <option value="Central Finance Ltd.">Central Finance Ltd.</option>
                                            <option value="Multipurpose Finance Co. Ltd">Multipurpose Finance Co. Ltd</option>
                                            <option value="Shrijana Finance Ltd.">Shrijana Finance Ltd.</option>
                                            <option value="Samriddhi Finance Co. Ltd.">Samriddhi Finance Co. Ltd.</option>
                                            <option value="Capital Merchant Banking & Finance Co. Ltd.">Capital Merchant Banking & Finance Co. Ltd.</option>
                                            <option value="Crystal Finance Ltd.">Crystal Finance Ltd.</option>
                                            <option value="Guheshwori Merchant Banking & Finance Ltd.">Guheshwori Merchant Banking & Finance Ltd.</option>
                                            <option value="ICFC Finance Ltd.">ICFC Finance Ltd.</option>
                                            <option value="City Express Finance Company Ltd.">City Express Finance Company Ltd.</option>
                                            <option value="Manjushree Financial Institution Ltd.">Manjushree Financial Institution Ltd.</option>
                                            <option value="Reliance Finance Ltd.">Reliance Finance Ltd.</option>
                                            <option value="Nirdhan Utthan Laghubitta Bittiya Sanstha Limited">Nirdhan Utthan Laghubitta Bittiya Sanstha Limited</option>
                                            <option value="RMDC Laghubitta Bittiya Sanstha Limited">RMDC Laghubitta Bittiya Sanstha Limited</option>
                                            <option value="Deprosc Laghubitta Bittiya Sanstha Limited">Deprosc Laghubitta Bittiya Sanstha Limited</option>
                                            <option value="Chhimek Laghubitta Bittiya Sanstha Limited">Chhimek Laghubitta Bittiya Sanstha Limited</option>
                                            <option value="Swabalamban Laghubitta Bittiya Sanstha Limited">Swabalamban Laghubitta Bittiya Sanstha Limited</option>
                                            <option value="Sana Kisan Bikas Laghubitta Bittiya Sanstha Limited">Sana Kisan Bikas Laghubitta Bittiya Sanstha Limited</option>
                                            <option value="Nerude Laghubitta Bittiya Sanstha Limited">Nerude Laghubitta Bittiya Sanstha Limited</option>
                                            <option value="Naya Nepal Laghubitta Bittiya Sanstha Limited">Naya Nepal Laghubitta Bittiya Sanstha Limited</option>
                                            <option value="Mithila Laghubitta Bittiya Sanstha Ltd.">Mithila Laghubitta Bittiya Sanstha Ltd.</option>
                                            <option value="Summit Laghubitta Bittiya Sanstha Ltd.">Summit Laghubitta Bittiya Sanstha Ltd.</option>
                                            <option value="Sworojagar Laghu Bitta Bika Bank Limited">Sworojagar Laghu Bitta Bika Bank Limited</option>
                                            <option value="First Microfinance Laghubitta Bittiya Sanstha Limited">First Microfinance Laghubitta Bittiya Sanstha Limited</option>
                                            <option value="Nagbeli Laghubitta Bittiya Sanstha Limited">Nagbeli Laghubitta Bittiya Sanstha Limited</option>
                                            <option value="Kalika Microcredit Development Bank Limited">Kalika Microcredit Development Bank Limited</option>
                                            <option value="Mirmire Microfinance Development Bank Limited">Mirmire Microfinance Development Bank Limited</option>
                                            <option value="Janautthan SamudayikMicrofinance Dev. Bank Limited">Janautthan SamudayikMicrofinance Dev. Bank Limited</option>
                                            <option value="Womi Microfinance Bittiya Sanstha Ltd.">Womi Microfinance Bittiya Sanstha Ltd.</option>
                                            <option value="Laxmi Microfinance Bittiya Sanstha Ltd.">Laxmi Microfinance Bittiya Sanstha Ltd.</option>
                                            <option value="Civil laghubitta Bittiya Sanstha">Civil laghubitta Bittiya Sanstha</option>
                                            <option value="Mahila Sahayatra Microfinance Bittiya Sanstha Ltd.">Mahila Sahayatra Microfinance Bittiya Sanstha Ltd.</option>
                                            <option value="Vijaya Laghubitta Bittiya Sanstha Ltd.">Vijaya Laghubitta Bittiya Sanstha Ltd.</option>
                                            <option value="Kisan Microfinance Bittiya Sanstha Ltd.">Kisan Microfinance Bittiya Sanstha Ltd.</option>
                                            <option value="NMB Microfinance Bittiya Sanstha Ltd">NMB Microfinance Bittiya Sanstha Ltd</option>
                                            <option value="FORWARD Community Microfinance Bittiya Sanstha Ltd.">FORWARD Community Microfinance Bittiya Sanstha Ltd.</option>
                                            <option value="Global IME Laghubitta Bittiya Sanstha Limited">Global IME Laghubitta Bittiya Sanstha Limited</option>
                                            <option value="Mahuli Samudyik Laghubitta Bittiya Sanstha Ltd.">Mahuli Samudyik Laghubitta Bittiya Sanstha Ltd.</option>
                                            <option value="Suryodaya Laghubitta Bitiya Sanstha Ltd.">Suryodaya Laghubitta Bitiya Sanstha Ltd.</option>
                                            <option value="Mero Microfinance Bittiya Sanatha Ltd.">Mero Microfinance Bittiya Sanatha Ltd.</option>
                                            <option value="Samata Microfinance Bittiya Sanatha Ltd.">Samata Microfinance Bittiya Sanatha Ltd.</option>
                                            <option value="RSDC Laghubitta Bitiya Sanstha Ltd.">RSDC Laghubitta Bitiya Sanstha Ltd.</option>
                                            <option value="Samudayik Laghubitta Bitiya Sanstha Ltd.">Samudayik Laghubitta Bitiya Sanstha Ltd.</option>
                                            <option value="National Microfinance Bittiya Sanstha Ltd.">National Microfinance Bittiya Sanstha Ltd.</option>
                                            <option value="Nepal Sewa Laghubitta Bitiya Sanstha Ltd.Â¤">Nepal Sewa Laghubitta Bitiya Sanstha Ltd.Â¤</option>
                                            <option value="Unnati Microfinance Bittiya Sanstha Ltd.">Unnati Microfinance Bittiya Sanstha Ltd.</option>
                                            <option value="Swadeshi Lagubitta Bittiya Sanstha Ltd.">Swadeshi Lagubitta Bittiya Sanstha Ltd.</option>
                                            <option value="NADEP Laghubitta Bittiya Sanstha Limited">NADEP Laghubitta Bittiya Sanstha Limited</option>
                                            <option value="Support Microfinance Bittiya Sanstha imited">Support Microfinance Bittiya Sanstha imited</option>
                                            <option value="Arambha Microfinance Bittiya Sanstha Limited">Arambha Microfinance Bittiya Sanstha Limited</option>
                                            <option value="Janasewi Laghubitta Bittiya Sanstha Limited">Janasewi Laghubitta Bittiya Sanstha Limited</option>
                                            <option value="Chautari Laghubitta Bittiya Sanstha Limited">Chautari Laghubitta Bittiya Sanstha Limited</option>
                                            <option value="Ghodighoda Laghubitta Bittiya Sanstha Limited">Ghodighoda Laghubitta Bittiya Sanstha Limited</option>
                                            <option value="Asha Laghubitta Bittiya Sanstha Ltd">Asha Laghubitta Bittiya Sanstha Ltd</option>
                                            <option value="Nepal Agro Microfinance Bittiya Sastha Ltd">Nepal Agro Microfinance Bittiya Sastha Ltd</option>
                                            <option value="Gurans Laghubitta Bittiya Sanstha Ltd">Gurans Laghubitta Bittiya Sanstha Ltd</option>
                                            <option value="Ganapati Microfinance Bittiya Sastha Ltd">Ganapati Microfinance Bittiya Sastha Ltd</option>
                                            <option value="Infinity Microfinance Bittiya Sanstha Ltd">Infinity Microfinance Bittiya Sanstha Ltd</option>
                                            <option value="Adhikhola Laghubitta Bittiya Sanstha Ltd.">Adhikhola Laghubitta Bittiya Sanstha Ltd.</option>
                                            <option value="SwabhimanMicrofinance Bittiya Sanstha Ltd.">SwabhimanMicrofinance Bittiya Sanstha Ltd.</option>
                                            <option value="Sparsha Laghubitta Bittiya Sanstha Ltd.">Sparsha Laghubitta Bittiya Sanstha Ltd.</option>
                                            <option value="Sabaiko Laghubitta Bittiya Sanstha Ltd">Sabaiko Laghubitta Bittiya Sanstha Ltd</option>
                                            <option value="Sadhana Laghubitta Bittiya Sanstha Ltd.">Sadhana Laghubitta Bittiya Sanstha Ltd.</option>
                                            <option value="NIC Asia Laghubitta Bittiya Sanstha Ltd.">NIC Asia Laghubitta Bittiya Sanstha Ltd.</option>
                                            <option value="Sarathi Laghubitta Bittiya Sanstha Ltd.">Sarathi Laghubitta Bittiya Sanstha Ltd.</option>
                                            <option value="Trilok Laghubitta Bittiya Sanstha Ltd.">Trilok Laghubitta Bittiya Sanstha Ltd.</option>
                                            <option value="Manakamana Laghubitta Bittiya Sanstha Ltd.">Manakamana Laghubitta Bittiya Sanstha Ltd.</option>
                                            <option value="Sahakarya Laghubitta Bittiya Sanstha Ltd.">Sahakarya Laghubitta Bittiya Sanstha Ltd.</option>
                                            <option value="Sajeelo Laghu Bitta Bittiya Sanstha Ltd.">Sajeelo Laghu Bitta Bittiya Sanstha Ltd.</option>
                                            <option value="Buddha Jyoti Laghubitta Bittiya Sanstha Ltd.">Buddha Jyoti Laghubitta Bittiya Sanstha Ltd.</option>
                                            <option value="Samaj Laghubitta Bittiya Sanstha Ltd.">Samaj Laghubitta Bittiya Sanstha Ltd.</option>
                                            <option value="Divya Laghubitta Bittiya Sanstha Limited">Divya Laghubitta Bittiya Sanstha Limited</option>
                                            <option value="Cweda Laghubitta Bittiya Sanstha Limited">Cweda Laghubitta Bittiya Sanstha Limited</option>
                                            <option value="Grameen Swayamsewak Laghubitta Bittiya Sanstha Limited">Grameen Swayamsewak Laghubitta Bittiya Sanstha Limited</option>
                                            <option value="Mahila Laghubitta Bittiya Sanstha Limited">Mahila Laghubitta Bittiya Sanstha Limited</option>
                                            <option value="Adarsha Laghubitta Bittiya Sanstha Limited">Adarsha Laghubitta Bittiya Sanstha Limited</option>
                                            <option value="Unique Nepal Lagubitta Bittiya Sanstha Ltd.">Unique Nepal Lagubitta Bittiya Sanstha Ltd.</option>
                                            <option value="Manushi Laghubitta Bittiya Sanstha Limited">Manushi Laghubitta Bittiya Sanstha Limited</option>
                                            <option value="Smart Laghubitta Bittiya Sanstha Limited">Smart Laghubitta Bittiya Sanstha Limited</option>
                                            <option value="Jalpa Laghubitta Bittiya Sanstha Limited">Jalpa Laghubitta Bittiya Sanstha Limited</option>
                                            <option value="Mahila Samudayik Laghubitta Bittiya Sanstha Limited">Mahila Samudayik Laghubitta Bittiya Sanstha Limited</option>
                                            <option value="Rastra Utthan Laghubitta Bittiya Sanstha Limited">Rastra Utthan Laghubitta Bittiya Sanstha Limited</option>
                                            <option value="Solve Laghubitta Bittiya Sanstha Limited">Solve Laghubitta Bittiya Sanstha Limited</option>
                                            <option value="WEAN Laghubitta Bittiya Sanstha Limited">WEAN Laghubitta Bittiya Sanstha Limited</option>
                                            <option value="Upakar Laghubitta Bittiya Sanstha Limited">Upakar Laghubitta Bittiya Sanstha Limited</option>
                                            <option value="Dhaulagiri Laghubitta Bittiya Sanstha Limited">Dhaulagiri Laghubitta Bittiya Sanstha Limited</option>
                                            <option value="CYC Nepal Laghubitta Bittiya Sanstha Limited">CYC Nepal Laghubitta Bittiya Sanstha Limited</option>
                                            <option value="NESDO Samriddha Laghubitta Bittiya Sanstha Limited">NESDO Samriddha Laghubitta Bittiya Sanstha Limited</option>
                                            <option value="Swastik Laghubitta Bittiya Sanstha Limited">Swastik Laghubitta Bittiya Sanstha Limited</option>
                                            <option value="Garibi Nyunikaran Laghubitta Bittiya Sanstha Limited">Garibi Nyunikaran Laghubitta Bittiya Sanstha Limited</option>
                                            <option value="Shrijanshil Laghubitta Bittiya Sanstha Limited">Shrijanshil Laghubitta Bittiya Sanstha Limited</option>
                                            <option value="NRN Laghubitta Bittiya Sanstha Limited">NRN Laghubitta Bittiya Sanstha Limited</option>
                                            <option value="Jiban Bikash Laghubitta Bittiya Sanstha Limited">Jiban Bikash Laghubitta Bittiya Sanstha Limited</option>
                                            <option value="Gharelu Laghubitta Bittiya Sanstha Limited">Gharelu Laghubitta Bittiya Sanstha Limited</option>
                                            <option value="Janakpur Laghubitta Bittiya Sanstha Limited">Janakpur Laghubitta Bittiya Sanstha Limited</option>
                                            <option value="BPW Laghubitta Bittiya Sanstha Limited">BPW Laghubitta Bittiya Sanstha Limited</option>
                                            <option value="Aatmanirbhar Laghubitta Bittiya Sanstha Limited">Aatmanirbhar Laghubitta Bittiya Sanstha Limited</option>
                                            <option value="Shaligram Laghubitta Bittiya Sanstha Limited">Shaligram Laghubitta Bittiya Sanstha Limited</option>
                                            <option value="Super Laghubitta Bittiya Sanstha Limited">Super Laghubitta Bittiya Sanstha Limited</option>
                                            <option value="Abhiyan Laghubitta Bittiya Sanstha Limited">Abhiyan Laghubitta Bittiya Sanstha Limited</option>
                                            <option value="Deurali Laghubitta Bittiya Sanstha Limited">Deurali Laghubitta Bittiya Sanstha Limited</option>
                                            <option value="Nawa Kiran Laghubitta Bittiya Sanstha Limited">Nawa Kiran Laghubitta Bittiya Sanstha Limited</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <select name="" id="agent_list" class="form-control" id="agent-name">
                                            <option value="0">---select---</option>
                                        </select>
                                    </div>
                                </div>
                                {{--                                <div class="text-dark">+</div>--}}
                            </div>
                        </div>
                        {{--end if cash--}}

                        <div class="from-horizontal border-bottom pt-3">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                            <div class="custom-control custom-radio custom-control-inline d-none">
                                                <input type="radio" name="item_type" class="custom-control-input item_type" value="pharmacy" checked>
                                                <label class="custom-control-label">Pharmacy Item</label>
                                            </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <button class="btn btn-primary" type="button" onclick="getSoldItem()">Add <i class="ri-add-line"></i></button>
                                        </div>
                                    </div>
                                </div>
                                {{--<div class="d-flex justify-content-center w-100 pb-3">
                                    <button class="btn btn-primary" type="button" onclick="getSoldItem()">Add <i class="ri-add-line"></i></button>
                                </div>--}}
                            </div>
                            <div class="res-table">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead class="thead-light">
                                    <tr>
                                        <th>S.N.</th>
                                        <th style="width: 60%;">Items</th>
                                        <th>Qty</th>
                                        <th>Rate</th>
                                        <th>Total Amount</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody id="billing-body">
                                    @if($html !="")
                                        {!! $html !!}
                                    @else
                                        <tr>
                                            <td colspan="6">No Items Added</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                    <thead class="thead-light">
                                    <tr>
                                        <th>&nbsp;</th>
                                        <th>Total</th>
                                        <th colspan="2" class="text-right"></th>
                                        <th class="text-right">{{ $total }}</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-sm-4 offset-sm-8">
                                    <table class="table table-borderless">
                                        <tbody>
                                        <tr>
                                            <td class="text-right">SubTotal</td>
                                            <td class="text-right">0.00</td>
                                        </tr>
                                        <tr>
                                            <td class="text-right">Discount</td>
                                            <td><input type="text" name="" id="" value="0.00" class="form-control ml-auto text-right" style="width: 100px;"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-right">Discount Amount</td>
                                            <td class="text-right">0.00</td>
                                        </tr>
                                        <tr>
                                            <td class="text-right">Total</td>
                                            <td class="text-right">0.00</td>
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
    </div>
    <div class="modal fade" id="encounter_list" tabindex="-1" role="dialog" aria-labelledby="encounter_listLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="post" id="encountercall" action="{{$route != 'admin/billing'}}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="encounter_listLabel" style="text-align: center;">Choose Encounter ID</h5>
                        <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="ajax_response_encounter_list">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary onclose" data-dismiss="modal">Close</button>
                        <button type="submit" name="submit" id="submitencounter_list" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('after-script')
    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-Token": $('meta[name="_token"]').attr("content")
                }
            });
            var fldencounterval = $("#fldencounterval").val();
            getPatientProfileColor();
        });

        jQuery(function ($) {
            hideAll();
            setTimeout(function () {
                $("#bank-name").select2();
                $('#bank-name').next(".select2-container").hide();
            }, 1500);
            /*On click payment modes*/
            $(document).on('click', '#payment_customer', function (event) {
                $('#office_name').hide();
            });
            $(document).on('click', '#payment_office', function (event) {
                // hideAll();
                $('#office_name').show();
            });
            $(document).on('click', '#payment_mode_credit', function (event) {
                hideAll();
                $('#expected_date').show();
            });
            $(document).on('click', '#payment_mode_cheque', function (event) {
                hideAll();
                $('#cheque_number').show();
                $("#payment_mode_party").show();
                $("#agent_list").show();
                $('#bank-name').next(".select2-container").show();
            });
            $(document).on('click', '#payment_mode_other', function (event) {
                hideAll();
                $('#other_reason').show();
            });
            $(document).on('click', '#payment_mode_cash', function (event) {
                hideAll();
                $("#payment_mode_party").show();
                $("#agent_list").show();
            });
            /* End On click payment modes*/

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
        })

        var getPatientProfileColor = function (encounterId) {
            if (encounterId !== undefined || encounterId !== '')
                encounterId = globalEncounter;

            if (encounterId !== undefined || encounterId !== '') {
                $.ajax({
                    url: baseUrl + '/inpatient/prog/getColor',
                    type: "GET",
                    data: {encounterId: encounterId},
                    success: function (data) {
                        element = document.getElementById("traicolor");
                        if (typeof (element) != 'undefined' && element != null) {
                            $(".traicolor").css("border:4px solid " + data);
                        } else {
                            $(".traicolor").css("border:4px solid " + data);
                        }
                    }
                });
            }
        }

        function hideAll() {
            $('#payment_mode_party').hide();
            $('#office-name').hide();
            $('#bank-name').next(".select2-container").hide();
            $('#agent_list').hide();
            $('#expected_date').hide();
            $('#cheque_number').hide();
            $('#office_name').hide();
            $('#other_reason').hide();
        }

        function getSoldItem() {
            item_type = $("input[name='item_type']:checked").val();
            $.ajax({
                url: "{{ route('billing.get.items.by.service.or.inventory') }}",
                type: "POST",
                data: {
                    item_type: $("input[name='item_type']:checked").val()
                },
                success: function (data) {
                    $('.file-modal-title').empty().text(item_type);
                    $('.file-form-data').empty().append(data);
                    $('.modal-dialog').addClass('modal-lg');
                    $('.modal-footer').hide();
                    // console.log(data);
                    $('#file-modal').modal('show');
                }
            });
        }

    </script>
@endpush
