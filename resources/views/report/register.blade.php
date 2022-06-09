@extends('frontend.layouts.master') @section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <form method="POST" enctype="multipart/form-data">
                            @csrf
                            <button type="button" class="accordion accordion-box">New Patient<i class="fa fa-down float-right"></i></button>
                            <div class="panel mt-3 mb-3" style="display: block;">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="">Patient No.</label>
                                            <div class="form-row">
                                                <div class="col-sm-5">
                                                    <input type="text" value="{{ request('patient_no') }}" name="patient_no" placeholder="Patient No." id="js-registration-patient-no" class="form-control">
                                                </div>
                                                <div class="col-sm-5">
                                                    <input type="text" value="{{ request('booking_id') }}" name="booking_id" placeholder="Booking Id" id="js-registration-booking-id" class="form-control">
                                                </div>
                                                <div class="col-sm-1">
                                                    <button type="button" class="btn btn-sm-in btn-primary" id="js-registration-refresh"><i class="ri-refresh-line"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="">Encounter Id</label>
                                            <input type="text" value="{{ request('encounter_id') }}" name="encounter_id" readonly id="js-registration-encounter-id" placeholder="Encounter Id" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="">Patient Type</label>
                                            <select name="billing_mode" id="js-registration-billing-mode" class="form-control">
                                                <option value="">--Select--</option>
{{--                                                @foreach($billingModes as $billingMode)--}}
{{--                                                    <option value="{{ $billingMode }}">{{ $billingMode }}</option>--}}
{{--                                                @endforeach--}}
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-3 mt-2">
                                        <div class="form-group">
                                            <label for="">Discount Scheme</label>
                                            <input type="hidden" name="flddiscper" id="js-registration-flddiscper">
                                            <input type="hidden" name="flddiscamt" id="js-registration-flddiscamt">
                                            <select name="discount_scheme" id="js-registration-discount-scheme" class="form-control">
                                                <option value="">--Select--</option>
{{--                                                @foreach($discounts as $discount)--}}
{{--                                                    <option value="{{ $discount->fldtype }}" data-fldmode="{{ $discount->fldmode }}" data-fldpercent="{{ $discount->fldpercent }}" data-fldamount="{{ $discount->fldamount }}">{{ $discount->fldtype }}</option>--}}
{{--                                                @endforeach--}}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-lg-3 mt-2">
                                        <div class="form-group">
                                            <label for="">HealthInsurance Type</label>
                                            <select name="insurance_type" id="js-registration-insurance-type" class="form-control">
                                                <option value="">--Select--</option>
{{--                                                @foreach($insurances as $insurance)--}}
{{--                                                    <option value="{{ $insurance->id }}">{{ $insurance->insurancetype }}</option>--}}
{{--                                                @endforeach--}}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 mt-2 insurance-toggle">
                                        <div class="form-group">
                                            <label for="">HealthInsurance No</label>
                                            <input type="text" value="{{ request('nhsi_id') }}" name="nhsi_id" id="js-registration-nhsi-no" placeholder="NHSI No." class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-3 mt-2 insurance-toggle">
                                        <div class="form-group">
                                            <label for="">Claim Code</label>
                                            <input type="text" value="{{ request('claim_code') }}" name="claim_code" id="js-registration-claim-code" placeholder="Claim Code" class="form-control">
                                            @if(isset($form_errors['claim_code'])) <div class="text-danger">{{ $form_errors['claim_code'] }} </div>@endif
                                        </div>
                                    </div>



                                    <div class="col-sm-3 col-lg-2 mt-2">
                                        <div class="form-group">
                                            <label for="">Reg Amount</label>
                                            <input type="text" value="{{ request('amount') }}" name="amount" id="js-registration-amount" readonly class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-2 pr-0">
                                        <div class="form-group mt-4">
                                            <label for="">Is Follow up</label>
                                            <input type="checkbox" name="is_follow_up" value="1" id="js-registration-is-follow-up">
                                        </div>
                                    </div>
                                    <div class="col-sm-3 col-lg-2">
                                        <div class="form-group">
                                            <label for="">Followup Date</label>
                                            <input type="text" value="{{ request('followup_date') }}" name="followup_date" id="js-registration-followup-date" placeholder="Followup Date" class="form-control nepaliDatePicker">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="iq-card iq-card-block">
                                    <div class="iq-card-body">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox custom-checkbox-color-checked custom-control-inline">
                                                <input type="checkbox" id="" name="" class="custom-control-input bg-primary" />
                                                <label class="custom-control-label" for="">Regular</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-radio-color-checked custom-control-inline">
                                                <input type="radio" id="" name="" class="custom-control-input bg-primary" />
                                                <label class="custom-control-label" for="">Family</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-radio-color-checked custom-control-inline">
                                                <input type="radio" id="" name="" class="custom-control-input bg-primary" />
                                                <label class="custom-control-label" for="">Other </label>
                                            </div>
                                            <div class="custom-control custom-radio custom-radio-color-checked custom-control-inline">
                                                <input type="radio" id="" name="" class="custom-control-input bg-primary" />
                                                <label class="custom-control-label" for="">OPD</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-radio-color-checked custom-control-inline">
                                                <input type="radio" id="" name="" class="custom-control-input bg-primary" />
                                                <label class="custom-control-label" for="">IPD</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group form-row">
                                        <div class="col-sm-3">
                                            <label>Personal No.</label>
                                        </div>
                                        <div class="col-sm-7">
                                            <input type="text" name="" value="" class="form-control" />
                                        </div>
                                        <div class="col-sm-1 col-lg-1">
                                            <button type="button" class="btn btn-primary btn-sm"><i class="fa fa-question" aria-hidden="true"></i></button>
                                        </div>
                                        <div class="col-sm-1 col-lg-1">
                                            <div class="custom-control custom-checkbox custom-control-inline">
                                                <input type="checkbox" class="custom-control-input" />
                                                <label class="custom-control-label" for=""></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group form-row">
                                        <div class="col-sm-3">
                                            <label>Hospital No.</label>
                                        </div>
                                        <div class="col-sm-5">
                                            <input type="text" name="" value="" class="form-control" />
                                        </div>
                                        <div class="col-sm-1 col-lg-1">
                                            <button type="button" class="btn btn-primary btn-sm"><i class="fa fa-question" aria-hidden="true"></i></button>
                                        </div>
                                        <div class="col-sm-3 col-lg-3">
                                            <div class="custom-control custom-checkbox custom-control-inline">
                                                <input type="checkbox" class="custom-control-input" />
                                                <label class="custom-control-label" for="">Follow Up</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group form-row">
                                        <label class="col-sm-4 col-lg-3">Rank:</label>
                                        <div class="col-sm-7 col-lg-8">
                                            <select name="" class="form-control">
                                                <option value=""></option>
                                                <option value=""></option>
                                            </select>
                                        </div>
                                        <div class="col-sm-1 col-lg-1">
                                            <button type="button" class="btn btn-primary btn-sm"><i class="fa fa-plus" aria-hidden="true"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group form-row">
                                        <label class="col-sm-4 col-lg-3">Unit:</label>
                                        <div class="col-sm-7 col-lg-8">
                                            <select name="" class="form-control">
                                                <option value=""></option>
                                                <option value=""></option>
                                            </select>
                                        </div>
                                        <div class="col-sm-1 col-lg-1">
                                            <button type="button" class="btn btn-primary btn-sm"><i class="fa fa-plus" aria-hidden="true"></i></button>
                                        </div>
                                    </div>
                                </div>







                                <div class="col-sm-6">
                                    <div class="form-group form-row">
                                        <div class="col-sm-3">
                                            <label>Service:</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" name="" value="" class="form-control" />
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group form-row">
                                        <div class="col-sm-3">
                                            <label>Discount:</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" name="" value="" class="form-control" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group form-row">
                                        <div class="col-sm-3">
                                            <label>Datetime:</label>
                                        </div>
                                        <div class="col-sm-1">
                                            <div class="custom-control custom-checkbox custom-control-inline">
                                                <input type="checkbox" class="custom-control-input" />
                                                <label class="custom-control-label" for=""></label>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="date" name="" value="" class="form-control" />
                                        </div>
                                        <div class="col-sm-1 col-lg-1">
                                            <button class="btn btn-primary btn-sm"><i class="fa fa-calendar" aria-hidden="true"></i></button>
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="time" name="" value="" class="form-control" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group form-row">
                                        <div class="col-sm-4">
                                            <input type="date" name="" value="" class="form-control" />
                                        </div>
                                        <div class="col-sm-1">
                                            <div class="custom-control custom-checkbox custom-control-inline">
                                                <input type="checkbox" class="custom-control-input" />
                                                <label class="custom-control-label" for=""></label>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <label>Max Allowed</label>
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="number" name="" value="" placeholder="0" class="form-control" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group form-row">
                                        <div class="col-sm-3">
                                            <label>Booked Waiting</label>
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="number" name="" value="" placeholder="0" class="form-control" />
                                        </div>
                                        <div class="col-sm-3">
                                            <label>Valid Registered</label>
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="number" name="" value="" placeholder="0" class="form-control" />
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <button type="button" class="accordion accordion-box">Personal Information<i class="fa fa-down float-right"></i></button>
                            <div class="panel mt-3 mb-3" style="display: block;">
                                <div class="form-row">
                                    <div class="col-sm-8">
                                        <div class="form-group form-row">
                                            <div class="col-lg-1 col-sm-2">
                                                <label for="">Title <span class="text-danger">*</span></label>
                                                <input type="text" value="{{ request('title') }}" name="title" id="js-registration-title" placeholder="Title" class="form-control">
                                                @if(isset($form_errors['title'])) <div class="text-danger">{{ $form_errors['title'] }} </div>@endif
                                            </div>
                                            <div class="col-lg-4 col-sm-4">
                                                <label for="">First Name <span class="text-danger">*</span></label>
                                                <input type="text" value="{{ request('first_name') }}" name="first_name" id="js-registration-first-name" placeholder="First Name" class="form-control">
                                                @if(isset($form_errors['first_name'])) <div class="text-danger">{{ $form_errors['first_name'] }} </div>@endif
                                            </div>
                                            <div class="col-lg-3 col-sm-3 ">
                                                <label for="">Middle Name</label>
                                                <input type="text" value="{{ request('middle_name') }}" name="middle_name" id="js-registration-middle-name" placeholder="Middle Name" class="form-control">
                                            </div>
                                            <div class="col-lg-4 col-sm-3">
                                                <label for="">Last Name </label>
                                                <div class=" er-input p-0">
                                                    <select name="last_name" id="js-registration-last-name" class="form-control select2" style="width: 100%;padding: .375rem .75rem;">
                                                        <option value="">--Select--</option>
{{--                                                        @foreach($surnames as $surname)--}}
{{--                                                            <option value="{{ $surname->flditem }}" data-id="{{ $surname->fldid }}">{{ $surname->flditem }}</option>--}}
{{--                                                        @endforeach--}}
                                                    </select>
                                                    <button type="button" class="ml-3 btn-sm-in btn btn-primary" id="js-registration-add-surname"><i class="ri-add-fill"></i></button>
                                                </div>
                                            </div>
                                            <div class="col-sm-3 col-lg-2 er-input mt-3">
                                                <label for="">Age</label>&nbsp;
                                                <input type="text" value="{{ request('year') }}" name="year" id="js-registration-age" class="form-control">
                                                <label>Years</label>
                                            </div>
                                            <div class="col-sm-3 col-lg-2 er-input mt-3">
                                                <input type="text" value="{{ request('month') }}" name="month" id="js-registration-month"  class="form-control col-lg-4">
                                                <label>Months</label>
                                            </div>
                                            <div class="col-sm-3 col-lg-2 er-input mt-3">
                                                <input type="text" value="{{ request('day') }}" name="day" id="js-registration-day"  class="form-control col-lg-4">
                                                <label>Days</label>
                                            </div>

                                            <div class="col-sm-4 mt-2">
                                                <label for="">Date of Birth</label>
                                                <input type="text" value="{{ request('dob') }}" name="dob" autocomplete="off" id="js-registration-dob" placeholder="Date" class="form-control">
                                            </div>
                                            <div class="col-sm-3 mt-2">
                                                <label for="">Gender</label>
                                                <select name="gender" id="js-registration-gender" class="form-control">
                                                    <option value="">--Select--</option>
{{--                                                    @foreach($genders as $gender)--}}
{{--                                                        <option value="{{ $gender }}">{{ $gender }}</option>--}}
{{--                                                    @endforeach--}}
                                                </select>
                                            </div>
                                            <div class="col-sm-5 mt-2">
                                                <label for="">Ethnic Group</label>
                                                <select name="ethnicgroup" id="js-registration-ethnic-group" class="form-control select2">
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
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <img id="profile" class="img-info" src="{{ asset('assets/images/dummy-img.jpg')}}" alt="your image" style="width: 27%; margin-left: 32%;" />
                                        </div>
                                        <div class="form-group text-right">
                                            <input type='file' name="image" class="col-9" onchange="readURL(this);" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="accordion accordion-box">Contact information<i class="fa fa-down float-right"></i></button>
                            <div class="panel mt-3 mb-3" style="display: block;">
                            <div class="panel mt-3 mb-3" style="display: block;">
                                <div class="form-row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="">Country</label>
                                            <select name="country" id="js-registration-country" class="form-control">
                                                <option value="">--Select--</option>
{{--                                                @foreach($countries as $country)--}}
{{--                                                    <option value="{{ $country }}">{{ $country }}</option>--}}
{{--                                                @endforeach--}}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="">Province</label>
                                            <select name="province" id="js-registration-province" class="form-control">
                                                <option value="">--Select--</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="">District</label>
                                            <select name="district" id="js-registration-district" class="form-control">
                                                <option value="">--Select--</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="">Municipality</label>
                                            <select name="municipality" id="js-registration-municipality" class="form-control">
                                                <option value="">--Select--</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="">Ward No.</label>
                                            <input type="text" value="{{ request('wardno') }}" name="wardno" id="js-registration-wardno" placeholder="Ward No." class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="">Tole</label>
                                            <input type="text" value="{{ request('tole') }}" name="tole" id="js-registration-tole" placeholder="Tole" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="">Contact Number</label>
                                            <input type="text" value="{{ request('contact') }}" name="contact" id="js-registration-contact-number" placeholder="Contact Number" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="">Email</label>
                                            <input type="email" name="email" id="js-registration-email" placeholder="Email" class="form-control">
                                            @if(isset($form_errors['email'])) <div class="text-danger">{{ $form_errors['email'] }} </div>@endif
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="">Guardian</label>
                                            <input type="text" value="{{ request('guardian') }}" name="guardian" id="js-registration-guardian" placeholder="Guardian" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="">Relation</label>
                                            <select name="relation" id="js-registration-relation" class="form-control">
                                                <option value="">--Select--</option>
{{--                                                @foreach($relations as $relation)--}}
{{--                                                    <option value="{{ $relation->flditem }}">{{ $relation->flditem }}</option>--}}
{{--                                                @endforeach--}}
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="accordion accordion-box">Other information<i class="fa fa-down float-right"></i></button>
                            <div class="panel mt-3 mb-3" style="display: block;">
                                <div class="form-row">
                                    <div class="col-sm-3 col-lg-3">
                                        <div class="form-group">
                                            <label for="">National Id</label>
                                            <input type="text" value="{{ request('national_id') }}" name="national_id" id="js-registration-national-id" placeholder="National Id" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-3 col-lg-3">
                                        <div class="form-group">
                                            <label for="">Citizenship No.</label>
                                            <input type="text" value="{{ request('citizenship_no') }}" name="citizenship_no" id="js-registration-citizenship-no" placeholder="Citizenship No." class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-3 col-lg-3">
                                        <div class="form-group">
                                            <label for="">PAN Number</label>
                                            <input type="text" value="{{ request('pan_number') }}" name="pan_number" id="js-registration-pan-number" placeholder="PAN Number" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-3 col-lg-3">
                                        <div class="form-group">
                                            <label for="">Blood Group</label>
                                            <select name="blood_group" id="js-registration-blood-group" class="form-control">
                                                <option value="">--Select--</option>
{{--                                                @foreach($bloodGroups as $bloodGroup)--}}
{{--                                                    <option value="{{ $bloodGroup }}">{{ $bloodGroup }}</option>--}}
{{--                                                @endforeach--}}
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="accordion accordion-box">Additional information<i class="fa fa-down float-right"></i></button>
                            <div class="panel mt-3 mb-3" style="display: block;">
                                <div class="form-row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="">Consultant Name</label>
                                            <select name="consultant" id="js-registration-consultant" class="form-control">
                                                <option value="">--Select--</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="">Referal</label>
                                            <select class="form-control" name="referal" id="js-registration-referal">
                                                <option value="">-- Select --</option>
{{--                                                @foreach($referals as $referal)--}}
{{--                                                    <option value="{{ $referal->flduserid }}">{{ $referal->fldusername }}</option>--}}
{{--                                                @endforeach--}}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="">Payable</label>
                                            <select class="form-control" name="payable" id="js-registration-payable">
                                                <option value="">-- Select --</option>
{{--                                                @foreach($payables as $payable)--}}
{{--                                                    <option value="{{ $payable->flduserid }}">{{ $payable->fldusername }}</option>--}}
{{--                                                @endforeach--}}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="">Date</label>
                                            <input type="text" value="{{ request('date') ?: '$todaydate' }}" name="date" id="js-registration-date" placeholder="Date" class="form-control nepaliDatePicker">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="">Last visit</label>
                                            <input type="text" value="{{ request('last_visit') }}" name="last_visit" id="js-registration-last-visit" placeholder="last visit" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center mt-2 pt-2 mb-4">
                                <button class="btn btn-primary">Save</button>&nbsp;
                                <input type="submit" class="btn btn-primary" name="bill" value="Save and bill">
                                <!-- <button class="btn btn-primary">update</button> -->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>




@endsection
