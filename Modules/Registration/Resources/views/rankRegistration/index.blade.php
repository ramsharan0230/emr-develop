@extends('frontend.layouts.master') @section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <form method="POST" enctype="multipart/form-data" id="registration-form">
                            @csrf

                            <!-- Adding hidden field because of Disabled in Address -->
                                <input type="hidden" name="country" value="" id="hidden_country">
                                <input type="hidden" name="province" value="" id="hidden_province">
                                <input type="hidden" name="district" value="" id="hidden_district">
                                <input type="hidden" name="municipality" value="" id="hidden_municipality">
                            <button type="button" class="accordion accordion-box">New Patient<i
                                    class="fa fa-down float-right"></i></button>

                            <div class="panel mt-3 mb-3" style="display: block;">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <div
                                                class="custom-control custom-radio custom-radio-color-checked custom-control-inline">
                                                <input type="radio" name="registration_type"
                                                       class="custom-control-input bg-primary radioType" value="regular"/>
                                                <label class="custom-control-label" for="">Regular</label>
                                            </div>
                                            <div
                                                class="custom-control custom-radio custom-radio-color-checked custom-control-inline">
                                                <input type="radio" name="registration_type"
                                                       class="custom-control-input bg-primary" value="family" id="familyRadio"/>
                                                <label class="custom-control-label" for="familyRadio">Family</label>
                                            </div>
                                            <div
                                                class="custom-control custom-radio custom-radio-color-checked custom-control-inline ">
                                                <input type="radio" name="registration_type"
                                                       class="custom-control-input bg-primary radioType" value="other" id="otherRadio"/>
                                                <label class="custom-control-label" for="otherRadio">Other </label>
                                            </div>
                                            <div
                                                class="custom-control custom-radio custom-radio-color-checked custom-control-inline ">
                                                <input type="radio" name="registration_type" id="opdradio"
                                                       class="custom-control-input bg-primary radioType" value="OPD" onclick="getDepartments('Consultation')"/>
                                                <label class="custom-control-label" for="opdradio">OPD</label>
                                            </div>
                                            <div
                                                class="custom-control custom-radio custom-radio-color-checked custom-control-inline ">
                                                <input type="radio" name="registration_type" id="ipradio"
                                                       class="custom-control-input bg-primary radioType"  value="IP" onclick="getDepartments('Patient Ward')"/>
                                                <label class="custom-control-label" for="ipradio">IPD</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-5">
                                        <div class="form-group">
                                            <label for="">Personal No.</label>
                                            <div class="form-row">
                                                <div class="col-sm-4">
                                                    <input type="text" value="{{ request('patient_no') }}"
                                                           name="patient_no" placeholder="Personal No." id="patient_no"
                                                           class="form-control">
                                                </div>
                                                <div class="col-sm-4">
                                                    <input type="text" value="{{ request('hospital_id') }}"
                                                           name="hospital_id" placeholder="Hospital No" id="hospital_id"
                                                           class="form-control">
                                                </div>

                                                <div class="col-sm-3">
                                                    <input type="text" value="{{ request('opd_no') }}"
                                                           name="opd_no" placeholder="OPD No" id="opd_no"
                                                           class="form-control" >
                                                </div>

                                                <div class="col-sm-1">
                                                    <button type="button" class="btn btn-sm-in btn-primary"
                                                            id="registration-refresh"><i class="ri-refresh-line"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="">Encounter Id</label>
                                            <input type="text" value="{{ request('encounter_id') }}" name="encounter_id"
                                                   readonly id="registration-encounter-id" placeholder="Encounter Id"
                                                   class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="">Patient Type</label>
                                            <select name="billing_mode" id="registration-billing-mode"
                                                    class="form-control">
                                                <option value="">--Select--</option>
                                                @foreach($billingModes as $billingMode)
                                                    <option value="{{ $billingMode }}">{{ $billingMode }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="">Specialization</label>
                                            <select name="department" id="registration-department" class="form-control">
                                                departments
                                                <option value="">--Select--</option>
                                                @foreach($departments as $department)
                                                    <option
                                                        value="{{ $department->flddept }}">{{ $department->flddept }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label>Unit:</label>
                                            <select name="registration-unit" id="registration-unit" class="form-control">
                                                <option value=""></option>
                                            </select>
{{--                                            <input type="text" name="registration-unit" class="form-control"--}}
{{--                                                   id="registration-unit" readonly>&nbsp;--}}
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label>Service:</label>
                                            <select name="service" id="registration_service" class="form-control">
                                                <option value=""></option>
                                            </select>
{{--                                            <input type="text" name="service"--}}
{{--                                                   value="{{ request('service') ? request('service') : '' }}"--}}
{{--                                                   class="form-control" id="registration_service" readonly/>--}}
                                        </div>
                                    </div>


                                    <div class="col-sm-3 mt-2">
                                        <div class="form-group">
                                            <label for="">Discount Scheme</label>
                                            <input type="hidden" name="flddiscper" id="registration-flddiscper">
                                            <input type="hidden" name="flddiscamt" id="registration-flddiscamt">
                                            <select name="discount_scheme" id="registration-discount-scheme"
                                                    class="form-control">
                                                <option value="">--Select--</option>
                                                @foreach($discounts as $discount)
                                                    <option value="{{ $discount->fldtype }}"
                                                            data-fldmode="{{ $discount->fldmode }}"
                                                            data-fldpercent="{{ $discount->fldpercent }}"
                                                            data-fldamount="{{ $discount->fldamount }}">{{ $discount->fldtype }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-lg-3 mt-2">
                                        <div class="form-group">
                                            <label for="">HealthInsurance Type</label>
                                            <select name="insurance_type" id="registration-insurance-type"
                                                    class="form-control" disabled>
                                                <option value="">--Select--</option>
                                                @foreach($insurances as $insurance)
                                                    <option
                                                        value="{{ $insurance->id }}">{{ $insurance->insurancetype }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 mt-2 insurance-toggle">
                                        <div class="form-group">
                                            <label for="">HealthInsurance No</label>
                                            <input type="text" value="{{ request('nhsi_id') }}" name="nhsi_id"
                                                   id="registration-nhsi-no" placeholder="NHSI No."
                                                   class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-3 mt-2 insurance-toggle">
                                        <div class="form-group">
                                            <label for="">Claim Code <span class="text-danger">*</span></label>
                                            <input type="text" value="{{ request('claim_code') }}" name="claim_code"
                                                   id="registration-claim-code" placeholder="Claim Code"
                                                   class="form-control">
                                            @if(isset($form_errors['claim_code']))
                                                <div class="text-danger">{{ $form_errors['claim_code'] }} </div>@endif
                                        </div>
                                    </div>


                                    <div class="col-sm-3 col-lg-2 mt-2">
                                        <div class="form-group">
                                            <label for="">Reg Amount</label>
                                            <input type="text" value="{{ request('amount') }}" name="amount"
                                                   id="registration-amount" readonly class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-2 pr-0">
                                        <div class="form-group mt-4">
                                            <label for="">Is Follow up</label>
                                            <input type="checkbox" name="is_follow_up" value="1"
                                                   id="registration-is-follow-up">
                                        </div>
                                    </div>
                                    <div class="col-sm-3 col-lg-2">
                                        <div class="form-group">
                                            <label for="">Followup Date</label>
                                            <input type="text" value="{{ request('followup_date') }}"
                                                   name="followup_date" id="registration-followup-date"
                                                   placeholder="Followup Date" class="form-control nepaliDatePicker">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">

                            </div>


                            <button type="button" class="accordion accordion-box">Personal Information<i
                                    class="fa fa-down float-right"></i></button>
                            <div class="panel mt-3 mb-3" style="display: block;">
                                <div class="form-row">
                                    <div class="col-sm-9">
                                        <div class="form-group form-row">
                                            <div class="col-lg-1 col-sm-2">
                                                <label for="">Title <span class="text-danger">*</span></label>
                                                <input type="text" value="{{ request('title') }}" name="title"
                                                       id="registration-title" placeholder="Title"
                                                       class="form-control">
                                                @if(isset($form_errors['title']))
                                                    <div class="text-danger">{{ $form_errors['title'] }} </div>@endif
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <label>Rank:</label>
                                                    <select name="registration_rank" id="registration-rank" class="form-control">
                                                        <option value=""></option>
                                                    </select>
{{--                                                    <input type="text" name="registration_rank" class="form-control"--}}
{{--                                                           id="registration-rank" readonly>&nbsp;--}}
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-sm-4">
                                                <label for="">First Name <span class="text-danger">*</span></label>
                                                <input type="text" value="{{ request('first_name') }}" name="first_name"
                                                       id="registration-first-name" placeholder="First Name"
                                                       class="form-control" readonly>
                                                @if(isset($form_errors['first_name']))
                                                    <div
                                                        class="text-danger">{{ $form_errors['first_name'] }} </div>@endif
                                            </div>
                                            <div class="col-lg-3 col-sm-3 ">
                                                <label for="">Middle Name</label>
                                                <input type="text" value="{{ request('middle_name') }}"
                                                       name="middle_name" id="registration-middle-name"
                                                       placeholder="Middle Name" class="form-control" readonly>
                                            </div>
                                            <div class="col-lg-3 col-sm-3">
                                                <label for="">Last Names </label>
                                                <div class=" er-input p-0">
                                                    <select name="last_name" id="registration-last-name"
                                                            class="form-control select2"
                                                            style="width: 100%;padding: .375rem .75rem;">
                                                        <option value="">--Select--</option>
                                                        @foreach($surnames as $surname)
                                                            <option value="{{ $surname->flditem }}"
                                                                    data-id="{{ $surname->fldid }}">{{ $surname->flditem }}</option>
                                                        @endforeach
                                                    </select>
                                                    <button type="button" class="ml-3 btn-sm-in btn btn-primary"
                                                            id="registration-add-surname"><i class="ri-add-fill"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="col-sm-3 col-lg-2 er-input mt-3">
                                                <label for="">Age</label>&nbsp;
                                                <input type="text" value="{{ request('year') }}" name="year"
                                                       id="registration-age" class="form-control" readonly>
                                                <label>Years</label>
                                            </div>
                                            <div class="col-sm-3 col-lg-2 er-input mt-3">
                                                <input type="text" value="{{ request('month') }}" name="month"
                                                       id="registration-month" class="form-control col-lg-6" readonly>&nbsp;
                                                <label>Months</label>
                                            </div>
                                            <div class="col-sm-3 col-lg-2 er-input mt-3">
                                                <input type="text" value="{{ request('day') }}" name="day"
                                                       id="registration-day" class="form-control col-lg-6" readonly>&nbsp;
                                                <label>Days</label>
                                            </div>

                                            <div class="col-sm-3 mt-2">
                                                <label for="">Date of Birth</label>
                                                <input type="text" value="{{ request('dob') }}" name="dob"
                                                       autocomplete="off" id="registration-dob" placeholder="Date"
                                                       class="form-control" readonly>
                                            </div>
                                            <div class="col-sm-3 mt-2">
                                                <label for="">Gender</label>
                                                <select name="gender" id="registration-gender" class="form-control"
                                                        readonly>
                                                    <option value="">--Select--</option>
                                                    @foreach($genders as $gender)
                                                        <option value="{{ $gender }}">{{ $gender }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-sm-5 mt-2">
                                                <label for="">Ethnic Group</label>
                                                <select name="ethnicgroup" id="registration-ethnic-group"
                                                        class="form-control select2">
                                                    <option value="">--Select--</option>

                                                    <option value="1 - Dalit">1 - Dalit</option>
                                                    <option value="2 - Janajati">2 - Janajati</option>
                                                    <option value="3 - Madhesi">3 - Madhesi</option>
                                                    <option value="4 - Muslim">4 - Muslim</option>
                                                    <option value="5 - Brahman/Chhetri">5 - Brahman/Chhetri</option>
                                                    <option value="6 - Others">6 - Others</option>


                                                </select>
                                            </div>


                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <img id="profile" class="img-info"
                                                 src="{{ asset('assets/images/dummy-img.jpg')}}" alt="your image"
                                                 style="width: 27%; margin-left: 32%;"/>
                                        </div>
                                        <div class="form-group text-right">
                                            <input type='file' name="image" class="col-9" onchange="readURL(this);"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="accordion accordion-box">Contact information<i
                                    class="fa fa-down float-right"></i></button>
                            <div class="panel mt-3 mb-3" style="display: block;">
                                <div class="panel mt-3 mb-3" style="display: block;">
                                    <div class="form-row">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="">Country</label>
                                                <select  id="registration-country"
                                                        class="form-control">
                                                    <option value="">--Select--</option>
                                                    @foreach($countries as $country)
                                                        <option value="{{ $country->fldname }}">{{ $country->fldname }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="">Province</label>
                                                <select  id="registration-province"
                                                        class="form-control">
                                                    <option value="">--Select--</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="">District</label>
                                                <select  id="registration-district"
                                                        class="form-control">
                                                    <option value="">--Select--</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="">Municipality</label>
                                                <select  id="registration-municipality"
                                                        class="form-control">
                                                    <option value="">--Select--</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="">Ward No.</label>
                                                <input type="text" value="{{ request('wardno') }}" name="wardno"
                                                       id="registration-wardno" placeholder="Ward No."
                                                       class="form-control" readonly>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="">Tole</label>
                                                <input type="text" value="{{ request('tole') }}" name="tole"
                                                       id="registration-tole" placeholder="Tole"
                                                       class="form-control" readonly>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="">Contact Number</label>
                                                <input type="text" value="{{ request('contact') }}" name="contact"
                                                       id="registration-contact-number" placeholder="Contact Number"
                                                       class="form-control" readonly>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="">Email</label>
                                                <input type="email" name="email" id="registration-email"
                                                       placeholder="Email" class="form-control" readonly>
                                                @if(isset($form_errors['email']))
                                                    <div class="text-danger">{{ $form_errors['email'] }} </div>@endif
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="">Guardian</label>
                                                <input type="text" value="{{ request('guardian') }}" name="guardian"
                                                       id="registration-guardian" placeholder="Guardian"
                                                       class="form-control" >
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="">Relation</label>
                                                <select name="relation" id="registration-relation"
                                                        class="form-control" >
                                                    <option value="">--Select--</option>
                                                    @foreach($relations as $relation)
                                                        <option
                                                            value="{{ $relation->flditem }}">{{ $relation->flditem }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="accordion accordion-box">Other information<i
                                        class="fa fa-down float-right"></i></button>
                                <div class="panel mt-3 mb-3" style="display: block;">
                                    <div class="form-row">
                                        <div class="col-sm-3 col-lg-3">
                                            <div class="form-group">
                                                <label for="">National Id</label>
                                                <input type="text" value="{{ request('national_id') }}"
                                                       name="national_id" id="registration-national-id"
                                                       placeholder="National Id" class="form-control" >
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-lg-3">
                                            <div class="form-group">
                                                <label for="">Citizenship No.</label>
                                                <input type="text" value="{{ request('citizenship_no') }}"
                                                       name="citizenship_no" id="registration-citizenship-no"
                                                       placeholder="Citizenship No." class="form-control" readonly>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-lg-3">
                                            <div class="form-group">
                                                <label for="">PAN Number</label>
                                                <input type="text" value="{{ request('pan_number') }}" name="pan_number"
                                                       id="registration-pan-number" placeholder="PAN Number"
                                                       class="form-control" >
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-lg-3">
                                            <div class="form-group">
                                                <label for="">Blood Group</label>
                                                <select name="blood_group" id="registration-blood-group"
                                                        class="form-control">
                                                    <option value="">--Select--</option>
                                                    @foreach($bloodGroups as $bloodGroup)
                                                        <option value="{{ $bloodGroup }}">{{ $bloodGroup }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="accordion accordion-box">Additional information<i
                                        class="fa fa-down float-right"></i></button>
                                <div class="panel mt-3 mb-3" style="display: block;">
                                    <div class="form-row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="">Consultant Name</label>
                                                <select name="consultant" id="registration-consultant"
                                                        class="form-control">
                                                    <option value="">--Select--</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="">Referal</label>
                                                <select class="form-control" name="referal"
                                                        id="registration-referal">
                                                    <option value="">-- Select --</option>
                                                    @foreach($referals as $referal)
                                                        <option
                                                            value="{{ $referal->flduserid }}">{{ $referal->fldusername }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="">Payable</label>
                                                <select class="form-control" name="payable"
                                                        id="registration-payable">
                                                    <option value="">-- Select --</option>
                                                    @foreach($payables as $payable)
                                                        <option
                                                            value="{{ $payable->flduserid }}">{{ $payable->fldusername }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="">Date</label>
                                                <input type="text" value="{{ request('date') ?: $todaydate }}"
                                                       name="date" id="registration-date" placeholder="Date"
                                                       class="form-control nepaliDatePicker">
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="">Last visit</label>
                                                <input type="text" value="{{ request('last_visit') }}" name="last_visit"
                                                       id="registration-last-visit" placeholder="last visit"
                                                       class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Datetime:</label>
                                                <input type="text" name="registration-date-time"
                                                       value="{{ request('registration-date-time') ? request('registration-date-time') : '' }}"
                                                       class="form-control nepaliDatePicker"
                                                       id="registration-date-time"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr/>
                                <div class="res-table">
                                    <div id="billing-body">
{{--                                        @if($html !="")--}}
{{--                                            {!! $html !!}--}}
{{--                                        @else--}}
                                            <table class="table table-striped table-bordered table-hover">
                                                <thead class="thead-light">
                                                <tr>
                                                    <th></th>
                                                    <th style="width: 60%;"></th>
                                                    <th class="text-center"></th>
                                                    <th class="text-center"></th>
                                                    <th class="text-center"></th>
                                                    <th class="text-center">Price</th>
                                                    <th class="text-center">Discount</th>
                                                    <th class="text-center">Amount</th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                                <thead class="thead-light">
                                                <tr>
                                                    <th>&nbsp;</th>
                                                    <th></th>
                                                    <th colspan="2" class="text-center" ></th>
                                                    <th colspan="2" class="text-center" id="price"></th>
                                                    <th class="text-center " id="discount"></th>
                                                    <th class="text-center" id="finalAmount">&nbsp;</th>
                                                </tr>
                                                </thead>

                                                <thead class="thead-light">
                                                <tr>
                                                    <th>&nbsp;</th>
                                                    <th></th>
                                                    <th colspan="2" class="text-center" ></th>
                                                    <th colspan="2" class="text-center">Remarks</th>
                                                    <th class="text-center" colspan="2">
                                                        <input class="form-control" type="text" name="price_remarks" id="price_remarks" placeholder="Remarks">
                                                    </th>
{{--                                                    <th class="text-center" id="finalAmount">&nbsp;</th>--}}
                                                </tr>
                                                </thead>
                                            </table>
{{--                                        @endif--}}
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center mt-2 pt-2 mb-4">
                                    <button class="btn btn-primary" id="saveBtn">Save</button>&nbsp;
                                    @if(Options::get('register_bill') == 'SaveAndBill')
                                        <input type="submit" class="btn btn-primary" name="bill" value="Save and bill" id="SaveandBillBtn">
                                    @endif

                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Registration Modal -->

    <div class="modal fade" id="registration-add-item-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="encounter_listLabel" style="text-align: center;">Variables</h5>
                    <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" id="registration-flditem-input-modal" class="form-control"
                               style="width: 100%;">
                    </div>
                    <div>
                        <button class="btn btn-primary" id="registration-add-btn-modal"><i class="ri-add-fill"></i>Add
                        </button>
                        <button class="btn btn-danger" style="float: right;" id="registration-delete-btn-modal"><i
                                class="ri-delete-bin-5-fill"></i>Delete
                        </button>
                    </div>
                    <br>
                    <div class="table-responsive table-sroll-lab">
                        <table id="registration-table-modal" class="table table-bordered table-hover"></table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary onclose" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

@endsection


@push('after-script')
    <script type="text/javascript">
        var addresses = JSON.parse('{!! $addresses !!}');
    </script>
    <script src="{{asset('js/rankRegistration_form.js')}}"></script>
@endpush
