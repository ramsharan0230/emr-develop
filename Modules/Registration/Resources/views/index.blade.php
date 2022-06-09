@extends('frontend.layouts.master')
<style type="text/css">
    .nhsi-patient .title {
        width: 146px;
        display: inline-block;
    }

    .nhsi-patient #insurance-no {
        letter-spacing: 4px;
    }

    .img-ms-form {
        max-width: 80px;
        width: 100%;
        height: auto;
    }



    .hi-card {
        background: #f8f8f8;
        margin: 10px 20px 0;
        padding: 10px 10px;
        border-radius: 8px;
    }

    .hi-card p {
        margin-bottom: 0;
    }

    .hi-card p span {
        font-weight: bold;
    }

    .hi-card p .title {
        width: 155px;
        display: inline-block;
        font-weight: normal;
    }

    .hi-card ul {
        list-style: none;
    }

    .img-left {
        width: 120px;
    }

    .img-right {
        width: 100px;
    }

    .hi-card #insurance-no {}

   /* Confirm Box  */

    .confirm-box {
        font-family: "Poppins", sans-serif;
    }
    .ui-dialog .ui-dialog-buttonpane button {
        font-size: 12px;
        border: unset;
        color: white;
        padding: 0.175rem 0.55rem;
        border-radius: 6px;
    }
    .ui-dialog-buttonset button:nth-child(1) {
        background: linear-gradient( to right, rgba(120, 125, 118, 1) 0%, rgba(171, 171, 171, 1) 100%);
    }

    .ui-dialog-buttonset button:nth-child(2) {
        background: linear-gradient( to right, var(--gradient-color-one) 0%, var(--gradient-color-two) 100%);
    }

    /* Close Button of Confirm Box */
    .ui-dialog-titlebar-close {
        border: unset;
        background: unset;
    }
    .ui-dialog-titlebar-close:before {
        font-family: 'Font Awesome 5 Free';
        content: "\f00d";
        font-weight: 900;
    }


    /* Consultations */

    .consultation {
        padding: 10px;
        width: 100%;
        border: 1px solid #c3c3c3;
    }

    .c-row {
        display: flex;
        flex-direction: row;
        align-items: center;
        flex-wrap: wrap;
    }

    .c-row div {
        margin-right: 5px;
        width: 100%;
    }

    .c-row div:nth-child(1) {
        flex: 0 0 48%;
        max-width: 48%;

    }

    .c-row div:nth-child(2) {
        flex: 0 0 38%;
        max-width: 38%;
    }

    .c-row div:nth-child(3) {
        flex: 0 0 8%;
        max-width: 8%;
    }

    .credit-box{
        border:1px solid #d1b6b6;
        background-color: #e7e0e0;
        padding: 0 10px;
        border-radius: 4px;
        color: red;
        font-size: 16px;
        font-weight: 600;
        text-align: center;
}

    .amt{
        font-size: 13px;
        font-weight:400;
    }

    /* .creditAmount {
        font-size: 28px;
        font-weight: 500;
    } */

    .blink_me {
        display: inline;
        animation: blinker 2s linear infinite;
    }

    @keyframes blinker {
        50% {
            opacity: 0;
        }
    }

 /*   .credit-box {
      -webkit-animation: glow 1s ease-in-out infinite alternate;
      -moz-animation: glow 1s ease-in-out infinite alternate;
      animation: glow 1s ease-in-out infinite alternate;
    }*/

/*@-webkit-keyframes glow {
  from {
    text-shadow: 0 0 10px #fff, 0 0 20px #fff, 0 0 30px #e60073, 0 0 40px #e60073, 0 0 50px #e60073, 0 0 60px #e60073, 0 0 70px #e60073;
  }
  to {
    text-shadow: 0 0 20px #fff, 0 0 30px #ff4da6, 0 0 40px #ff4da6, 0 0 50px #ff4da6, 0 0 60px #ff4da6, 0 0 70px #ff4da6, 0 0 80px #ff4da6;
  }
}*/

</style>
@section('content')
    <template id="js-multi-consultation-tr-template">
        <div class="c-row">
            <div class="d-flex flex-column">
                <label class="specs_label">Specialization <span class="text-danger">*</span>:</label>
                <select name="department[]" class="form-control select2 js-registration-department" required>
                    <option value="">--Select--</option>
                    @foreach ($departments as $department)
                        <option
                            value="{{ $department->flddept }}">
                            {{ $department->flddept }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="d-flex flex-column">
                <span class="specs_label-consult">Consultant Name @if(Options::get('consultation_required') == 'Yes') <span class="text-danger">*</span> @endif:</span>
                <input type="hidden" name="consultantid[]" class="js-registration-consultantid">
                <select name="consultant[]" class="form-control js-registration-consultant select2" @if(Options::get('consultation_required') == 'Yes')required @endif>
                    <option value="">--Select--</option>
                </select>
            </div>
            <div class="d-flex justify-content-center mr-2">
                <button type="button" class="btn btn-danger btn-sm-in mt-4 js-multi-consultation-remove-btn">
                    <i class="fa fa-times"></i>
                </button>
            </div>
        </div>
    </template>

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <div class="nav-box">
                            <ul class="nav nav-tabs" id="myTab-1" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="new-patient-tab" data-toggle="tab" href="#new-patient"
                                        role="tab" aria-controls="new-patient" aria-selected="true"><span
                                            id="js-new-patient-span">New</span> Patient</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="old-patient-tab" data-toggle="tab" href="#old-patient"
                                        role="tab" aria-controls="old-patient" aria-selected="false">Old Patient</a>
                                </li>
                            </ul>
                            <div class="today-date">
                                Date : {{ date('Y-m-d') }}
                            </div>
                            <input type="hidden" name="convergent_payment_status" id="convergent_payment_status"
                                value="{{ Options::get('convergent_payment_status') }}">
                            <input type="hidden" name="generate_qr" id="generate_qr"
                                value="{{ Options::get('generate_qr') }}">
                        </div>
                        <div class="tab-content" id="myTabContent-2">


                            <!-- New Patient tabs content -->
                            <div class="tab-pane fade show active" id="new-patient" role="tabpanel"
                                aria-labelledby="new-patient">
                                <form method="POST" enctype="multipart/form-data" id="regsitrationForm">
                                    @csrf
                                    <input type="hidden" class="js-registration-regtype-hidden" name="fldregtype"
                                        value="New Registration">
                                    <input type="hidden" class="js-fonepaylog-id-hidden" name="fonepaylog_id" value="">
                                    <div class="panel mt-1 mb-1" style="display: block;">
                                        @if (Options::get('reg_seperate_num') == 'Yes')
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div
                                                        class="custom-control custom-radio custom-control-inline align-items-right">
                                                        <input type="radio" id="OPD-radio" name="department_seperate_num"
                                                            onclick="getDepartments('Consultation')" value="OPD"
                                                            class="custom-control-input" checked>
                                                        <label class="custom-control-label" for="OPD-radio"> OPD
                                                            Patient </label>
                                                    </div>
                                                    <div
                                                        class="custom-control custom-radio custom-control-inline align-items-right">
                                                        <input type="radio" id="IP-radio" name="department_seperate_num"
                                                            onclick="getDepartments('Patient Ward')" value="IP"
                                                            class="custom-control-input">
                                                        <label class="custom-control-label" for="IP-radio"> IP
                                                            Patient </label>
                                                    </div>
                                                    <div
                                                        class="custom-control custom-radio custom-control-inline align-items-right">
                                                        <input type="radio" id="ER-radio" name="department_seperate_num"
                                                            onclick="getDepartments('Emergency')" value="ER"
                                                            class="custom-control-input">
                                                        <label class="custom-control-label" for="ER-radio"> ER
                                                            Patient </label>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        @if (Options::get('is_army_police') == 'Yes')
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div
                                                        class="custom-control custom-radio custom-control-inline align-items-right">
                                                        <input type="radio" id="regular-radio" name="registration_type"
                                                            value="regular" class="custom-control-input">
                                                        <label class="custom-control-label" for="regular-radio">
                                                            Regular </label>
                                                    </div>
                                                    <div
                                                        class="custom-control custom-radio custom-control-inline align-items-right">
                                                        <input type="radio" id="family-radio" name="registration_type"
                                                            value="family" class="custom-control-input">
                                                        <label class="custom-control-label" for="family-radio">
                                                            Family </label>
                                                    </div>
                                                    <div
                                                        class="custom-control custom-radio custom-control-inline align-items-right">
                                                        <input type="radio" id="other-radio" name="registration_type"
                                                            value="other" class="custom-control-input" checked>
                                                        <label class="custom-control-label" for="other-radio">
                                                            Other </label>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-row flex-column">
                                                    {{-- <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="">Booking No.</label>
                                                            <div class="form-row">
                                                                <div class="col-sm-6">
                                                                    <input type="text" value="{{ request('booking_id') }}" name="booking_id" placeholder="Booking Id" class="form-control js-registration-booking-id">
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <button type="button" class="btn btn-md btn-primary js-registration-refresh"><i class="fa fa-search">&nbsp;Search</i></button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div> --}}
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="">Old PatientId</label>
                                                            <input type="text" value="{{ request('booking_id') }}"
                                                                name="fldoldpatientid" placeholder="Old PatientId"
                                                                class="form-control js-registration-oldpatientid-id">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <div class="consultation">
                                                                <div class="d-flex flex-row justify-content-between border-bottom pb-1">
                                                                    <span class="text">Consultation</span>
                                                                    <button type="button" class="btn btn-primary btn-sm-in js-multi-consultation-add-btn">
                                                                        ADD
                                                                    </button>
                                                                </div>
                                                                <div class="c-body js-multi-consultation-tbody">
                                                                    <div class="c-row">
                                                                        <div class="d-flex flex-column">
                                                                            <label class="specs_label">Specialization <span class="text-danger">*</span>:</label>
                                                                            <select name="department[]" class="form-control select2 js-registration-department" required>
                                                                                <option value="">--Select--</option>
                                                                                @foreach ($departments as $department)
                                                                                    <option
                                                                                        value="{{ $department->flddept }}">
                                                                                        {{ $department->flddept }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="d-flex flex-column">
                                                                            <span class="specs_label-consult">Consultant Name @if(Options::get('consultation_required') == 'Yes')<span class="text-danger">*</span>@endif:</span>
                                                                            <input type="hidden" name="consultantid[]" class="js-registration-consultantid">
                                                                            <select name="consultant[]" class="form-control js-registration-consultant select2" @if(Options::get('consultation_required') == 'Yes') required @endif>
                                                                                <option value="">--Select--</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="d-flex justify-content-center mr-2">
                                                                            <button type="button" class="btn btn-danger btn-sm-in mt-4 js-multi-consultation-remove-btn">
                                                                                <i class="fa fa-times"></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- <table class="table border mt-2">
                                                                <thead>
                                                                    <tr>
                                                                        <td colspan="2">Consultation</td>
                                                                        <td>
                                                                            <button type="button" class="btn btn-primary btn-sm-in js-multi-consultation-add-btn">
                                                                                ADD
                                                                            </button>
                                                                        </td>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="js-multi-consultation-tbody">
                                                                    <tr>
                                                                        <td class="col-sm-5"><span class="specs_label">Specialization</span>
                                                                        <span class="text-danger">*</span>:
                                                                            <select name="department[]" class="form-control select2 js-registration-department" required>
                                                                                <option value="">--Select--</option>
                                                                                @foreach ($departments as $department)
                                                                                    <option
                                                                                        value="{{ $department->flddept }}">
                                                                                        {{ $department->flddept }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </td>
                                                                        <td class="col-sm-5">Consultant Name<span class="text-danger consultant-span">*</span>:
                                                                            <input type="hidden" name="consultantid[]" class="js-registration-consultantid">
                                                                            <select name="consultant[]" class="form-control js-registration-consultant select2" required>
                                                                                <option value="">--Select--</option>
                                                                            </select>
                                                                        </td>
                                                                        <td class="col-sm-2">
                                                                            <button type="button" class="btn btn-danger btn-sm-in mt-4 js-multi-consultation-remove-btn">
                                                                                <i class="fa fa-times"></i>
                                                                            </button>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table> -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="">Patient Type</label>
                                                            <span class="text-danger">*</span>
                                                            <select id="select-patient-type" name="billing_mode" class="form-control js-registration-billing-mode select2" required>
                                                                <option value="">--Select--</option>
                                                                @foreach ($billingModes as $billingMode)
                                                                    <option value="{{ $billingMode }}"
                                                                        {{ strtoupper($billingMode) == 'GENERAL' ? 'selected' : '' }}>
                                                                        {{ $billingMode }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @if (isset($form_errors['billing_mode']))
                                                                <div class="text-danger">
                                                                    {{ $form_errors['billing_mode'] }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div id="block-ssf-number" style="position: relative; display:none; margin-bottom: 20px;">
                                                            <input id="input-ssf-number" type="text" name="ssf_number" class="form-control" placeholder="SSF Number" />
                                                            <div id="ssf-number-spinner" class="spinner-border"
                                                                style="top: 7px; position: absolute; right: 6px; width: 15px; color: #ababab; height: 15px; display:none;"
                                                                role="status">
                                                                <span class="visually-hidden"></span>
                                                            </div>
                                                            <a href="javascript:;" id="refresh-ssf"
                                                                class="btn btn-primary btn-sm-in"><i
                                                                    class="fa fa-redo-alt"></i></a>
                                                            <div class="form-group">
                                                                <label for="">Allowed Money</label>
                                                                (Rs.) <input id="ssf-allowed-money" disabled type="text"
                                                                    class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="">Discount Scheme</label><span
                                                                class="text-danger">*</span>
                                                            <input type="hidden" name="flddiscper"
                                                                class="js-registration-flddiscper">
                                                            <input type="hidden" name="flddiscamt"
                                                                class="js-registration-flddiscamt">
                                                            <select name="discount_scheme"
                                                                class="form-control js-registration-discount-scheme select2"
                                                                required>
                                                                <option value="">--Select--</option>
                                                                @foreach ($discounts as $discount)
                                                                    <option
                                                                        {{ strtoupper($discount->fldtype) == 'GENERAL' ? 'selected' : '' }}
                                                                        value="{{ $discount->fldtype }}"
                                                                        data-fldmode="{{ $discount->fldmode }}"
                                                                        data-fldpercent="{{ $discount->fldpercent }}"
                                                                        data-fldamount="{{ $discount->fldamount }}">
                                                                        {{ $discount->fldtype }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 insurance-block" style="display: none;">
                                                        <div class="form-group">
                                                            <label for="">Health Insurance Type</label>
                                                            <select name="insurance_type"
                                                                class="form-control js-registration-insurance-type">
                                                                <option value="">--Select--</option>
                                                                @foreach ($insurances as $insurance)
                                                                    <option value="{{ $insurance->id }}">
                                                                        {{ $insurance->insurancetype }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 health-insurance-block" style="display: none;">
                                                        <div class="form-group">
                                                            <label for="">HealthInsurance No</label>
                                                            <input type="text" value="{{ request('nhsi_id') }}"
                                                                name="nhsi_id" placeholder="NHSI No."
                                                                class="form-control js-registration-nhsi-no">

                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 health-insurance-block" style="display: none;">
                                                        <div class="form-group">
                                                            <label for="">HI Amount</label>
                                                            <input type="text" value="" name="nhsi_amount"
                                                                placeholder="HI Amount"
                                                                class="form-control js-registration-hi-amount" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 health-insurance-block" style="display: none;">
                                                        <div class="form-group">
                                                            <label for="">Claim Code <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" value="{{ request('claim_code') }}"
                                                                name="claim_code" placeholder="Claim Code"
                                                                class="form-control js-registration-claim-code">
                                                            @if (isset($form_errors['claim_code']))
                                                                <div class="text-danger">
                                                                    {{ $form_errors['claim_code'] }} </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="">Referal</label>
                                                            <input type="text" class="form-control js-registration-referal"
                                                                name="referal">
                                                            {{-- <select class="form-control js-registration-referal select2" name="referal">
                                                                <option value="">-- Select --</option>
                                                                @foreach ($referals as $referal)
                                                                <option value="{{ $referal->flduserid }}">{{ $referal->fldusername }}</option>
                                                                @endforeach
                                                            </select> --}}
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="">Reg Amount</label>
                                                            <input type="text" value="{{ request('amount') }}"
                                                                name="amount" disabled
                                                                class=" form-control js-registration-amount">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="">Specialization</label><span class="text-danger">*</span>
                                                    <select name="department" class="form-control select2 js-registration-department" required>
                                                        departments
                                                        <option value="">--Select--</option>
                                                        @foreach ($departments as $department)
                                                <option value="{{ $department->flddept }}">{{ $department->flddept }}</option>
                                                        @endforeach
                                                </select>
                                                @if (isset($form_errors['department']))
                                                <div class="text-danger">{{ $form_errors['department'] }} </div>
                                                @endif
                                                </div>
                                            </div>
                                            <div class="col-sm-4 col-lg-3">
                                                <div class="form-group">
                                                    <label for="">Consultant Name</label>
                                                    <select name="consultant" class="form-control js-registration-consultant select2">
                                                        <option value="">--Select--</option>
                                                    </select>
                                                </div>
                                            </div> --}}

                                        </div>
                                    </div>
                                    <button type="button" class="accordion accordion-box p-2">Personal Information<i
                                            class="fa fa-caret-down float-right"></i></button>
                                    <div class="panel mt-1 mb-1" style="display: block;">
                                        <div class="form-row">
                                            <div class="col-sm-9">
                                                <div class="form-group form-row">
                                                    <div class="col-lg-2 col-sm-2">
                                                        <div class="form-group">
                                                            <label for="">Title <span
                                                                    class="text-danger">*</span></label>
                                                            <select name="title"
                                                                class="form-control js-registration-title select2"
                                                                oninvalid="openPersonalInfoAccordian()" required>
                                                                <option value="">Select</option>
                                                                <option value="Mr."
                                                                    @if (request('title') == 'Mr.') selected @endif>
                                                                    Mr.
                                                                </option>
                                                                <option value="Mrs."
                                                                    @if (request('title') == 'Mrs.') selected @endif>
                                                                    Mrs.
                                                                </option>
                                                                <option value="Ms."
                                                                    @if (request('title') == 'Ms.') selected @endif>Ms
                                                                </option>

                                                                <option value="other">Other</option>

                                                            </select>
                                                            @if (isset($form_errors['title']))
                                                                <div class="text-danger">{{ $form_errors['title'] }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="other_salutation" style="display: none;">
                                                            <div class="form-group">
                                                                <label for="">If other Title</label>
                                                                <input type="text" autocomplete="off" value=""
                                                                    name="other_title" placeholder="Title"
                                                                    class="form-control other_title">
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="col-lg-3 col-sm-4">
                                                        <div class="form-group">
                                                            <label for="">First Name <span
                                                                    class="text-danger">*</span></label>
                                                            <input id="input-first-name" type="text" autocomplete="off"
                                                                value="{{ request('first_name') }}" name="first_name"
                                                                placeholder="First Name"
                                                                class="form-control js-registration-first-name"
                                                                pattern="[a-zA-Z ]+" oninvalid="invalidAlphabets(this)"
                                                                oninput="setCustomValidity('')" required>
                                                            @if (isset($form_errors['first_name']))
                                                                <div class="text-danger">
                                                                    {{ $form_errors['first_name'] }} </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-sm-3 ">
                                                        <div class="form-group">
                                                            <label for="">Middle Name</label>
                                                            <input id="input-middle-name" type="text" autocomplete="off"
                                                                value="{{ request('middle_name') }}" name="middle_name"
                                                                placeholder="Middle Name"
                                                                class="form-control js-registration-middle-name"
                                                                pattern="[a-zA-Z ]+" oninvalid="invalidAlphabets(this)"
                                                                oninput="setCustomValidity('')">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-sm-3">
                                                        <div class="form-group">
                                                            <label for="">Last Name </label><span
                                                                class="text-danger">*</span>
                                                            <div class=" er-input p-0">
                                                                <input id="js-registration-last-name-new" type="text"
                                                                    autocomplete="off" value="{{ request('last_name') }}"
                                                                    name="last_name" placeholder="Last Name"
                                                                    class="form-control js-registration-last-name"
                                                                    pattern="[a-zA-Z ]+" oninvalid="invalidAlphabets(this)"
                                                                    oninput="setCustomValidity('')">
                                                            </div>
                                                            @if (isset($form_errors['last_name']))
                                                                <div class="text-danger">
                                                                    {{ $form_errors['last_name'] }} </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-8" id="hidedhow-register">
                                                        <div class="form-row mt-3">
                                                            <label for="">Age</label><span
                                                                class="text-danger"></span>&nbsp;&nbsp;&nbsp;
                                                            <input min="0" type="number" autocomplete="off"
                                                                value="{{ request('year') }}" name="year"
                                                                class="js-registration-age form-control col-lg-1"
                                                                oninvalid="openPersonalInfoAccordian()">
                                                            &nbsp;&nbsp

                                                            <label>Years</label>&nbsp;&nbsp;&nbsp;
                                                            <input min="0" type="number" autocomplete="off"
                                                                value="{{ request('month') }}" name="month"
                                                                class="js-registration-month form-control col-lg-1">
                                                            &nbsp;&nbsp;

                                                            <label>Months</label>&nbsp;&nbsp;&nbsp;
                                                            <input min="0" type="number" autocomplete="off"
                                                                value="{{ request('day') }}" name="day"
                                                                class="js-registration-day form-control col-lg-1">
                                                            &nbsp;&nbsp;

                                                            <label>Days</label>&nbsp;&nbsp;&nbsp;
                                                            <input min="0" type="number" autocomplete="off" value=""
                                                                name="day" id=""
                                                                class="js-registration-hours form-control col-lg-1">
                                                            &nbsp;&nbsp;

                                                            <label>Hours</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4" id="js-registration-dob-div">
                                                    <div class="form-group">
                                                            <label for="">Date of Birth</label><span
                                                                class="text-danger">*</span>
                                                            <div class="form-check form-check-inline" >
                                                                <input class="form-check-input date-label" type="radio"
                                                                    name="dateOptions" id="adDate"  value="ad" checked>
                                                                <label class="form-check-label ad-date-label"
                                                                    for="adDate" >AD</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input date-label" type="radio"
                                                                    name="dateOptions" id="bsDate" value="bs">
                                                                <label class="form-check-label bs-date-label"
                                                                    for="bsDate" >BS</label>
                                                            </div>
                                                            <div style="position: relative;">
                                                            <input type="text" value="{{ request('dob') }}" name="dob"
                                                                autocomplete="off" id="js-registration-dob-new"
                                                                placeholder="Date"
                                                                class="form-control js-registration-dob english-dob"
                                                                oninvalid="openPersonalInfoAccordian()" >
                                                            <input type="text" class="form-control nepali-dob"
                                                                name="nep_date" id="nep_date" autocomplete="off"
                                                                style="position: absolute; top: 0;"
                                                                placeholder="Date" />
                                                            </div>
                                                            
                                                            <input type="hidden" name="eng_from_date" id="eng_from_date">
                                                            <input type="hidden" name="date_hour" id="date_hour" value="0">
                                                            <input type="hidden" name="nep_from_date" id="nep_from_date">
                                                            @if (isset($form_errors['dob']))
                                                                <div class="text-danger">{{ $form_errors['dob'] }}
                                                                </div>
                                                            @endif


                                                        </div>

                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label for="">Gender</label><span
                                                                class="text-danger">*</span>
                                                            <select name="gender"
                                                                class="form-control js-registration-gender select2"
                                                                oninvalid="openPersonalInfoAccordian()" required>
                                                                <option value="">--Select--</option>
                                                                @foreach ($genders as $gender)
                                                                    <option
                                                                        {{ request('gender') == $gender ? 'selected' : '' }}
                                                                        value="{{ $gender }}">{{ $gender }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @if (isset($form_errors['gender']))
                                                                <div class="text-danger">{{ $form_errors['gender'] }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-5">
                                                        <div class="form-group">
                                                            <label for="">Ethnic Group</label>
                                                            <select name="ethnicgroup"
                                                                class="form-control select2 js-registration-ethnic-group">
                                                                <option value="">--Select--</option>
                                                                <option
                                                                    {{ request('ethnicgroup') == '1 - Dalit' ? 'selected' : '' }}
                                                                    value="1 - Dalit">
                                                                    1 - Dalit
                                                                </option>
                                                                <option
                                                                    {{ request('ethnicgroup') == '2 - Janajati' ? 'selected' : '' }}
                                                                    value="2 - Janajati">
                                                                    2 - Janajati
                                                                </option>
                                                                <option
                                                                    {{ request('ethnicgroup') == '3 - Madhesi' ? 'selected' : '' }}
                                                                    value="3 - Madhesi">
                                                                    3 - Madhesi
                                                                </option>
                                                                <option
                                                                    {{ request('ethnicgroup') == '4 - Muslim' ? 'selected' : '' }}
                                                                    value="4 - Muslim">
                                                                    4 - Muslim
                                                                </option>
                                                                <option
                                                                    {{ request('ethnicgroup') == '5 - Brahman/Chhetri' ? 'selected' : '' }}
                                                                    value="5 - Brahman/Chhetri">
                                                                    5 - Brahman/Chhetri
                                                                </option>
                                                                <option
                                                                    {{ request('ethnicgroup') == '6 - Others' ? 'selected' : '' }}
                                                                    value="6 - Others">
                                                                    6 - Others
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-3 col-lg-4">
                                                        <div class="form-group">
                                                            <label for="">Blood Group</label>
                                                            <select name="blood_group"
                                                                class="form-control js-registration-blood-group select2">
                                                                <option value="">--Select--</option>
                                                                @foreach ($bloodGroups as $bloodGroup)
                                                                    <option
                                                                        {{ request('blood_group') == $bloodGroup ? 'selected' : '' }}
                                                                        value="{{ $bloodGroup }}">{{ $bloodGroup }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <img class="img-info profile"
                                                        src="{{ asset('assets/images/dummy-img.jpg') }}" alt="your image"
                                                        style="width: 33%; margin-left: 32%;" />
                                                </div>
                                                <div class="form-group text-right">
                                                    <input type='file' name="image" class="ml-3"
                                                        onchange="readURL(this);" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="accordion accordion-box p-2 contactInfoBtn">Contact
                                        information<i class="fa fa-caret-down float-right"></i></button>
                                    <div class="panel mt-1 mb-1 contactInfoPanel" style="display: block;">
                                        <div class="form-row">
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="">Country</label><span class="text-danger">*</span>
                                                    <select name="country"
                                                        class="form-control select2 js-registration-country"
                                                        oninvalid="openContactInfoAccordian()" required>
                                                        <option value="default">--Select--</option>
                                                        @foreach ($countries as $country)
                                                            <option value="{{ $country->fldname }}">{{ $country->fldname }}
                                                            </option>
                                                        @endforeach
                                                        <option value="Other">Other</option>
                                                    </select>
                                                    @if (isset($form_errors['country']))
                                                        <div class="text-danger">{{ $form_errors['country'] }} </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="">Province</label>
                                                    <select name="province" id="js-new-province"
                                                        class="form-control select2 js-registration-province"
                                                        oninvalid="openContactInfoAccordian()">
                                                        <option value="default">--Select--</option>
                                                    </select>
                                                    @if (isset($form_errors['province']))
                                                        <div class="text-danger">{{ $form_errors['province'] }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="">District</label><span class="text-danger">*</span>
                                                    <select name="district"
                                                        class="form-control select2 js-registration-district"
                                                        oninvalid="openContactInfoAccordian()" required>
                                                        <option value="default">--Select--</option>
                                                    </select>
                                                    @if (isset($form_errors['district']))
                                                        <div class="text-danger">{{ $form_errors['district'] }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="">Municipality</label>
                                                    <select name="municipality"
                                                        class="form-control select2 js-registration-municipality"
                                                        oninvalid="openContactInfoAccordian()">
                                                        <option value="default">--Select--</option>
                                                    </select>
                                                    @if (isset($form_errors['municipality']))
                                                        <div class="text-danger">{{ $form_errors['municipality'] }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="">Ward No.</label>
                                                    <input type="number" autocomplete="off"
                                                        value="{{ request('wardno') }}" name="wardno"
                                                        placeholder="Ward No." class="form-control js-registration-wardno">
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="">Tole</label>
                                                    <input type="text" autocomplete="off" value="{{ request('tole') }}"
                                                        name="tole" placeholder="Tole"
                                                        class="form-control js-registration-tole">
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="">Contact Number<span
                                                            class="text-danger">*</span></label>
                                                    <input type="tel" autocomplete="off" value="{{ request('contact') }}"
                                                        name="contact" placeholder="Contact Number"
                                                        class="form-control js-registration-contact-number"
                                                        pattern="^\d{10}$"
                                                        oninvalid="setCustomValidity('Contact number must be atleast 10 digits')"
                                                        required oninput="setCustomValidity('')">
                                                    @if (isset($form_errors['contact']))
                                                        <div class="text-danger">{{ $form_errors['contact'] }} </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-sm-3 js-registration-phone-number-div" style="display: none;">
                                                <div class="form-group">
                                                    <label for="">Phone Number</label>
                                                    <input type="tel" autocomplete="off" value="{{ request('phone') }}"
                                                        name="phone" placeholder="Phone Number"
                                                        class="form-control js-registration-phone-number">
                                                    @if (isset($form_errors['phone']))
                                                        <div class="text-danger">{{ $form_errors['phone'] }} </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="">Email</label>
                                                    <input type="email" autocomplete="off" value="{{ request('email') }}"
                                                        name="email" placeholder="Email"
                                                        class="form-control js-registration-email">
                                                    @if (isset($form_errors['email']))
                                                        <div class="text-danger">{{ $form_errors['email'] }} </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="">Guardian</label>
                                                    <input type="text" autocomplete="off"
                                                        value="{{ request('guardian') }}" name="guardian"
                                                        placeholder="Guardian"
                                                        class="form-control js-registration-guardian">
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="">Relation</label>
                                                    <select name="relation"
                                                        class="form-control select2 js-registration-relation">
                                                        <option value="">--Select--</option>
                                                        @foreach ($relations as $relation)
                                                            <option
                                                                {{ request('relation') == $relation->flditem ? 'selected' : '' }}
                                                                value="{{ $relation->flditem }}">
                                                                {{ $relation->flditem }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="">Marital Status</label>
                                                    <select name="marital_status"
                                                        class="js-registration-marital-status form-control select2">
                                                        <option value="">--Select--</option>
                                                        @foreach ($maritalStatus as $marital)
                                                            <option
                                                                {{ request('marital_status') == $marital ? 'selected' : '' }}
                                                                value="{{ $marital }}">{{ $marital }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="">Religion</label>
                                                    <input type="text" autocomplete="off"
                                                        value="{{ request('religion') }}" name="religion"
                                                        placeholder="Religion" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="accordion accordion-box p-2">Other information<i
                                            class="fa fa-caret-down float-right"></i></button>
                                    <div class="panel mt-1 mb-1">
                                        <div class="form-row">
                                            <div class="col-sm-4 col-lg-4">
                                                <div class="form-group">
                                                    <label for="">National Id</label>
                                                    <input type="text" autocomplete="off"
                                                        value="{{ request('national_id') }}" name="national_id"
                                                        placeholder="National Id"
                                                        class="form-control js-registration-national-id">
                                                </div>
                                            </div>
                                            <div class="col-sm-4 col-lg-4">
                                                <div class="form-group">
                                                    <label for="">Citizenship No.</label>
                                                    <input type="number" autocomplete="off"
                                                        value="{{ request('citizenship_no') }}" name="citizenship_no"
                                                        placeholder="Citizenship No."
                                                        class="form-control js-registration-citizenship-no">
                                                </div>
                                            </div>
                                            <div class="col-sm-4 col-lg-4">
                                                <div class="form-group">
                                                    <label for="">PAN Number</label>
                                                    <input type="text" autocomplete="off"
                                                        value="{{ request('pan_number') }}" name="pan_number"
                                                        placeholder="PAN Number"
                                                        class="form-control js-registration-pan-number">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="button" class="accordion accordion-box p-2">Payment Details<i
                                            class="fa fa-caret-down float-right"></i></button>
                                    <div class="panel mt-1 mb-1 panel-payment">
                                        <div class="res-table">
                                            <div id="billing-body">
                                                <table class="table table-striped table-bordered table-hover">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th></th>
                                                            <th style="width: 60%;">Particulars</th>
                                                            <th class="text-center">Price</th>
                                                            <th class="text-center">Discount</th>
                                                            <th class="text-center">Amount</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="js-registration-billing-tbody"></tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td></td>
                                                            <td class="text-center"></td>
                                                            <td class="text-center">Remarks</td>
                                                            <td class="text-center" colspan="2">
                                                                <input class="form-control price_remarks" type="text"
                                                                    name="price_remarks" placeholder="Remarks">
                                                            </td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="form-horizontal">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <label class="col-12">Payment Mode</label>

                                                    <div class="bak-payment p-2">
                                                        <div class="form-row">
                                                            <div class="col-sm-3  pay-rad checked-bak" id="cash_payment"
                                                                onclick="getRadioFunction('Cash')">
                                                                <div class="custom-control custom-radio custom-control-inline"
                                                                    onclick="getRadioFunction('Cash')">
                                                                    <input type="radio" id="newCash" name="payment_mode"
                                                                        class="custom-control-input payment_mode"
                                                                        value="Cash" checked>
                                                                    <label class="custom-control-label" for="newCash"
                                                                        onclick="getRadioFunction('Cash')"> Cash</label>
                                                                </div>
                                                                <div class="img-ms-form"
                                                                    onclick="getRadioFunction('Cash')">
                                                                    <img src="{{ asset('new/images/cash-2.png') }}"
                                                                        class="img-ms-form" alt="">
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-3 pay-rad" id="credit_payment"
                                                                onclick="getRadioFunction('Credit')">
                                                                <div class="custom-control custom-radio custom-control-inline"
                                                                    onclick="getRadioFunction('Credit')">
                                                                    <input type="radio" id="newCredit" name="payment_mode"
                                                                        class="custom-control-input payment_mode"
                                                                        value="Credit">
                                                                    <label class="custom-control-label" for="newCredit"
                                                                        onclick="getRadioFunction('Credit')"> Credit
                                                                    </label>
                                                                </div>
                                                                <div class="img-ms-form"
                                                                    onclick="getRadioFunction('Credit')">
                                                                    <img src="{{ asset('new/images/credit-3.png') }}"
                                                                        class="img-ms-form" alt="">
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-3 pay-rad" id="card_payment"
                                                                onclick="getRadioFunction('Card')">
                                                                <div class="custom-control custom-radio custom-control-inline"
                                                                    onclick="getRadioFunction('Card')">
                                                                    <input type="radio" id="newCard" name="payment_mode"
                                                                        class="custom-control-input payment_mode"
                                                                        value="Card">
                                                                    <label class="custom-control-label " for="newCard"
                                                                        onclick="getRadioFunction('Card')"> Card </label>
                                                                </div>
                                                                <div class="mt-2 img-ms-form"
                                                                    onclick="getRadioFunction('Card')">
                                                                    <img src="{{ asset('new/images/swipe2.png') }}"
                                                                        class="img-ms-form" alt="">
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-3 pay-rad" id="fonepay_payment"
                                                                onclick="getRadioFunction('Fonepay')">
                                                                <div class="custom-control custom-radio custom-control-inline"
                                                                    onclick="getRadioFunction('Fonepay')">
                                                                    <input type="radio" id="newFonepay" name="payment_mode"
                                                                        class="custom-control-input payment_mode"
                                                                        value="Fonepay"
                                                                        onclick="getRadioFunction('Fonepay')">
                                                                    <label class="custom-control-label" for="newFonepay"
                                                                        onclick="getRadioFunction('Fonepay')">Fonepay
                                                                    </label>
                                                                </div>
                                                                <div class="img-ms-form"
                                                                    onclick="getRadioFunction('Fonepay')">
                                                                    <img src="{{ asset('new/images/fonepay_logo.png') }}"
                                                                        class="ml-4" class="img-ms-form"
                                                                        alt="">
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- <div class="form-group form-row align-items-center">
                                                                        <label class="col-sm-3">Payment Mode:</label>
                                                                        <div class="col-sm-5">
                                                                            <select name="payment_mode"
                                                                                    class="form-control payment_mode">
                                                                                <option selected value="Cash">Cash</option>
                                                                                <option value="Credit">Credit</option>
                                                                                {{-- <option value="Credit">Credit</option>
                                                                <option value="Cheque">Cheque</option>
                                                                <option value="Fonepay">Fonepay</option>
                                                                <option value="Others">Others</option> --}}
                                                                            </select>
                                                                        </div>
                                                                    </div> -->
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group form-row align-items-center expected_date">
                                                        <label class="col-sm-5">Expected Payment Date</label>
                                                        <div class="col-sm-7">
                                                            <div class="input-group">
                                                                <input type="text" name="expected_payment_date_nepali"
                                                                    class="form-control  expected_payment_date_nepali"
                                                                    id="expected_payment_date_nepali-new">
                                                                <input type="hidden" name="expected_payment_date"
                                                                    class="form-control expected_payment_date">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- if cash --}}
                                        <div class="form-horizontal border-bottom pt-3">
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <input type="text" name="cheque_number" placeholder="Cheque Number"
                                                            class="form-control cheque_number">
                                                        <input type="text" name="other_reason" placeholder="Reason"
                                                            class="form-control other_reason">
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <select name="bank_name" class="form-control bank-name">
                                                            <option value="">Select Bank</option>
                                                            @if (count($banks))
                                                                @forelse($banks as $bank)
                                                                    <option value="{{ $bank->fldbankname }}">
                                                                        {{ $bank->fldbankname }}</option>
                                                                @empty
                                                                @endforelse
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <input type="text" name="office_name" placeholder="Office Name"
                                                            class="form-control office_name">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- end if cash --}}
                                        <div class="d-flex mt-2 pt-2 mb-4" style="float: right;">
                                            @if (Options::get('register_bill') != 'SaveAndBill')
                                                <button class="btn btn-primary js-registrationform-submit-btn">Save</button>&nbsp;
                                            @endif

                                            @if (Options::get('register_bill') == 'SaveAndBill')
                                                <input type="submit" class="btn btn-primary submit js-registrationform-submit-btn" id="new-patient-submit"
                                                    name="bill" value="Save and bill">
                                            @endif

                                            <!-- modal -->
                                            {{-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".modal-ssh">SSF Modal</button>&nbsp; --}}
                                            <div class="modal fade modal-ssh" tabindex="-1" role="dialog"
                                                aria-hidden="true" style="display: none;">
                                                <div class="modal-dialog ">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Modal</h5>
                                                            <button type="button" class="close"
                                                                data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="div-ssh">
                                                                <div class="row">
                                                                    <div class="col-sm-3">
                                                                        <img src="{{ asset('new/images/logo-health.jpg') }}"
                                                                            alt="">
                                                                    </div>
                                                                    <div class="col-sm-9 mt-3">
                                                                        <h4 class="ml-3">Government Of Nepal</h4>
                                                                        <h4 class="ml-3">Social Security Fund
                                                                        </h4>
                                                                    </div>
                                                                </div>
                                                                <div class="row mt-3">
                                                                    <div class="col-sm-12">
                                                                        <div class="ssf-header">
                                                                            <p class="mt-1">Social Security
                                                                                Identity
                                                                                Card</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class=col-sm-12>
                                                                        <table class="ssf-card">
                                                                            <tr>
                                                                                <td>SSID:</td>
                                                                                <td>Gender:</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Name:</td>
                                                                                <td>ID:</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Address:</td>
                                                                                <td>Contact No.:</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Nationality:</td>
                                                                                <td>Blood Grp:</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Issuing officer:</td>
                                                                            </tr>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                                <div class="text-center mt-5 mb-3">
                                                                    <label>Please if found return to the nearet police
                                                                        office </label>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Close
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg">HI modal</button> --}}
                                            <div class="modal fade hi-modal-lg" tabindex="-1" role="dialog"
                                                aria-hidden="true" style="display: none;">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Health Insurance Demo-Card</h5>
                                                            <button type="button" class="close"
                                                                data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="div-Hi">
                                                                <div class="row">
                                                                    <div class="col-sm-3 text-center">
                                                                        <img src="{{ asset('new/images/gov.png') }}"
                                                                            alt="" class="img-left">
                                                                    </div>
                                                                    <div class="col-sm-6 mt-4">
                                                                        <h4 class=" text-center">Nepal Government</h4>
                                                                        <h4 class=" text-center">Health Insurance Board
                                                                        </h4>
                                                                        <h5 class=" text-center mt-2">Insurance Card</h5>
                                                                    </div>
                                                                    <div class="col-sm-3 text-center">
                                                                        <img src="{{ asset('new/images/shs.png') }}"
                                                                            alt="" class="img-right">
                                                                    </div>
                                                                    {{-- <div class="col-sm-12 text-right">
                                                                        <h5 class="nhsi-patient">सदस्यता नं. :<span id="insurance-no"></span></h5>
                                                                    </div> --}}
                                                                </div>
                                                                <div class="row">
                                                                    <div class="hi-card">
                                                                        <h5 class="nhsi-patient"><span
                                                                                class="title">Insurance
                                                                                No.:</span>&nbsp; <span
                                                                                id="insurance-no"></span></h5>
                                                                        <ul>
                                                                            <li>
                                                                                <p><span class="title">Name
                                                                                        :</span><span
                                                                                        id="fname"></span>&nbsp; <span
                                                                                        id="lname"></span></p>
                                                                            </li>
                                                                            <li>
                                                                                <p><span class="title">DOB
                                                                                        :</span><span id="dob"></span></p>
                                                                            </li>
                                                                            <li>
                                                                                <p><span class="title">Gender
                                                                                        :</span><span id="gender"></span>
                                                                                </p>
                                                                            </li>
                                                                            <li>
                                                                                <p><span class="title">Balance
                                                                                        Money :</span><span
                                                                                        id="allmoney"></span></p>
                                                                            </li>
                                                                            <!-- <li>
                                                                                           <p><span class="title">Used Money :</span><span id="usedmoney"></span></p>
                                                                                       </li> -->
                                                                            <li>
                                                                                <p><span class="title">Expiry
                                                                                        :</span><span id="expiry"></span>
                                                                                </p>
                                                                            </li>
                                                                        </ul>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>



                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary verify"
                                                                data-dismiss="modal">Use Details
                                                            </button>

                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Close
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- old patient tabs content -->
                            <div class="tab-pane fade" id="old-patient" role="tabpanel" aria-labelledby="old-patient">
                                <form method="POST" enctype="multipart/form-data" id="oldRegistrationForm">
                                    @csrf
                                    <input type="hidden" class="js-registration-regtype-hidden" name="fldregtype"
                                        value="Other Registration">
                                    <input type="hidden" class="js-fonepaylog-id-hidden" name="fonepaylog_id" value="">
                                    <div class="panel mt-1 mb-1" style="display: block;">
                                        @if (Options::get('reg_seperate_num') == 'Yes')
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div
                                                        class="custom-control custom-radio custom-control-inline align-items-right">
                                                        <input type="radio" id="OPD-radio-old"
                                                            name="department_seperate_num"
                                                            onclick="getDepartments('Consultation')" value="OP"
                                                            class="custom-control-input" checked>
                                                        <label class="custom-control-label" for="OPD-radio-old"> OPD
                                                            Patient </label>
                                                    </div>
                                                    <div
                                                        class="custom-control custom-radio custom-control-inline align-items-right">
                                                        <input type="radio" id="IP-radio-old" name="department_seperate_num"
                                                            onclick="getDepartments('Patient Ward')" value="IP"
                                                            class="custom-control-input">
                                                        <label class="custom-control-label" for="IP-radio-old"> IP
                                                            Patient </label>
                                                    </div>
                                                    <div
                                                        class="custom-control custom-radio custom-control-inline align-items-right">
                                                        <input type="radio" id="ER-radio-old" name="department_seperate_num"
                                                            onclick="getDepartments('Emergency')" value="ER"
                                                            class="custom-control-input">
                                                        <label class="custom-control-label" for="ER-radio-old"> ER
                                                            Patient </label>
                                                    </div>
                                                </div>

                                                <div class="col-sm-6">
                                                    <div class="d-flex flex-row justify-content-end">
                                                        <div id="creditPart" class="mr-3" style="display: none;">
                                                            <div class="form-group">
                                                                <div class="credit-box">
                                                                    <span class="amt"> Credit Amount:</span>
                                                                    <div class="blink_me">
                                                                        <span class="creditAmount"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mr-2">
                                                            Last Visit:<b id="js-registration-lastvisit-b"> -</b>
                                                        </div>
                                                        <div>
                                                            Encounter ID : <b id="js-registration-encounterid-b">-</b>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        @if (Options::get('is_army_police') == 'Yes')
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div
                                                        class="custom-control custom-radio custom-control-inline align-items-right">
                                                        <input type="radio" id="regular-radio-old" name="registration_type"
                                                            value="regular" class="custom-control-input">
                                                        <label class="custom-control-label" for="regular-radio-old">
                                                            Regular </label>
                                                    </div>
                                                    <div
                                                        class="custom-control custom-radio custom-control-inline align-items-right">
                                                        <input type="radio" id="family-radio-old" name="registration_type"
                                                            value="family" class="custom-control-input">
                                                        <label class="custom-control-label" for="family-radio-old">
                                                            Family </label>
                                                    </div>
                                                    <div
                                                        class="custom-control custom-radio custom-control-inline align-items-right">
                                                        <input type="radio" id="other-radio-old" name="registration_type"
                                                            value="other" class="custom-control-input" checked>
                                                        <label class="custom-control-label" for="other-radio-old">
                                                            Other </label>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-row flex-column">
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label for="">Patient No.</label>
                                                            <div class="form-row">
                                                                <div class="col-sm-5">
                                                                    <input type="text"
                                                                        value="{{ request('patient_no') }}"
                                                                        name="patient_no" placeholder="Patient No."
                                                                        id="js-registration-patient-no"
                                                                        class="form-control js-registration-patient-no">
                                                                </div>
                                                                <div class="col-sm-5">
                                                                    <input type="text"
                                                                        value="{{ request('booking_id') }}"
                                                                        name="booking_id" placeholder="Booking Id"
                                                                        class="form-control js-registration-booking-id">
                                                                </div>
                                                                <div class="col-sm-1">
                                                                    <button type="button" class="btn btn-md btn-primary"
                                                                        id="js-registration-refresh"><i
                                                                            class="fa fa-search"></i></button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <div class="consultation">
                                                                <div class="d-flex flex-row justify-content-between border-bottom pb-1">
                                                                    <span class="text">Consultation</span>
                                                                    <button type="button" class="btn btn-primary btn-sm-in js-multi-consultation-add-btn">
                                                                        ADD
                                                                    </button>
                                                                </div>
                                                                <div class="c-body js-multi-consultation-tbody">
                                                                    <div class="c-row">
                                                                        <div class="d-flex flex-column">
                                                                            <span class="specs_label">Specialization<span class="text-danger">*</span> :</span>
                                                                            <select name="department[]" class="form-control select2 js-registration-department" required>
                                                                                <option value="">--Select--</option>
                                                                                @foreach ($departments as $department)
                                                                                    <option
                                                                                        value="{{ $department->flddept }}">
                                                                                        {{ $department->flddept }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="d-flex flex-column">
                                                                            <span class="specs_label-consult">Consultant Name @if(Options::get('consultation_required') == 'Yes')<span class="text-danger">*</span>@endif :</span>
                                                                            <input type="hidden" name="consultantid[]" class="js-registration-consultantid">
                                                                            <select name="consultant[]" class="form-control js-registration-consultant select2" @if(Options::get('consultation_required') == 'Yes') required @endif>
                                                                                <option value="">--Select--</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="d-flex justify-content-center mr-2">
                                                                            <button type="button" class="btn btn-danger btn-sm-in mt-4 js-multi-consultation-remove-btn">
                                                                                <i class="fa fa-times"></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- <table class="table border mt-2">
                                                                <thead>
                                                                    <tr>
                                                                        <td colspan="2">Consultations</td>
                                                                        <td>
                                                                            <button type="button" class="btn btn-primary btn-sm-in js-multi-consultation-add-btn">
                                                                                ADD
                                                                            </button>
                                                                        </td>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="js-multi-consultation-tbody">
                                                                    <tr>
                                                                        <td>
                                                                            <span class="specs_label">Specialization</span>
                                                                            <span class="text-danger">*</span>:
                                                                            <select name="department[]" class="form-control select2 js-registration-department" required>
                                                                                <option value="">--Select--</option>
                                                                                @foreach ($departments as $department)
                                                                                    <option
                                                                                        value="{{ $department->flddept }}">
                                                                                        {{ $department->flddept }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </td>
                                                                        <td>Consultant Name
                                                                            <span class="text-danger consultant-span">*</span>:
                                                                            <input type="hidden" name="consultantid[]" class="js-registration-consultantid">
                                                                            <select name="consultant[]" class="form-control js-registration-consultant select2" required>
                                                                                <option value="">--Select--</option>
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <button type="button" class="btn btn-danger btn-sm-in mt-4 js-multi-consultation-remove-btn">
                                                                                <i class="fa fa-times"></i>
                                                                            </button>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table> -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="">Patient Type</label><span
                                                                class="text-danger">*</span>
                                                            <select id="old-select-patient-type" name="billing_mode"
                                                                class="form-control js-registration-billing-mode select2"
                                                                id="js-registration-billing-mode-old" required>
                                                                <option value="">--Select--</option>
                                                                @foreach ($billingModes as $billingMode)
                                                                    <option
                                                                        {{ $billingMode == 'General' ? 'selected' : '' }}
                                                                        value="{{ $billingMode }}">{{ $billingMode }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @if (isset($form_errors['billing_mode']))
                                                                <div class="text-danger">
                                                                    {{ $form_errors['billing_mode'] }} </div>
                                                            @endif
                                                        </div>
                                                        <div id="old-block-ssf-number"
                                                            style="position: relative; display:none; margin-bottom: 20px;">
                                                            <input id="old-input-ssf-number" type="text" readonly
                                                                name="ssf_number" class="form-control"
                                                                placeholder="SSF Number" />
                                                            <div id="old-ssf-number-spinner" class="spinner-border"
                                                                style="top: 7px; position: absolute; right: 6px; width: 15px; color: #ababab; height: 15px; display:none;"
                                                                role="status">
                                                                <span class="visually-hidden"></span>
                                                            </div>
                                                            {{-- <a href="javascript:;" id="old-refresh-ssf" class="btn btn-primary btn-sm-in"><i class="fa fa-redo-alt"></i></a> --}}
                                                            <div class="form-group">
                                                                <label for="">Allowed Money</label>
                                                                (Rs.) <input id="old-ssf-allowed-money" disabled type="text"
                                                                    class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="">Discount Scheme</label><span
                                                                class="text-danger">*</span>
                                                            <input type="hidden" name="flddiscper"
                                                                class="js-registration-flddiscper">
                                                            <input type="hidden" name="flddiscamt"
                                                                class="js-registration-flddiscamt">
                                                            <select id="select-old-patient-discount-scheme"
                                                                name="discount_scheme"
                                                                class="form-control js-registration-discount-scheme select2"
                                                                required>
                                                                <option value="">--Select--</option>
                                                                @foreach ($discounts as $discount)
                                                                    <option value="{{ $discount->fldtype }}"
                                                                        {{ $discount->fldtype == 'General' ? 'selected' : '' }}
                                                                        data-fldmode="{{ $discount->fldmode }}"
                                                                        data-fldpercent="{{ $discount->fldpercent }}"
                                                                        data-fldamount="{{ $discount->fldamount }}">
                                                                        {{ $discount->fldtype }}</option>
                                                                @endforeach
                                                            </select>
                                                            @if (isset($form_errors['discount_scheme']))
                                                                <div class="text-danger">
                                                                    {{ $form_errors['discount_scheme'] }} </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 old-insurance-block" style="display: none;">
                                                        <div class="form-group">
                                                            <label for="">Health Insurance Type</label>
                                                            <select name="insurance_type"
                                                                class="form-control js-registration-insurance-type">
                                                                <option value="">--Select--</option>
                                                                @foreach ($insurances as $insurance)
                                                                    <option value="{{ $insurance->id }}">
                                                                        {{ $insurance->insurancetype }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    {{-- <div class="col-sm-6 old-health-insurance-block old-insurance-toggle" --}}
                                                    <div class="col-sm-6 old-health-insurance-block old-insurance-toggle"
                                                        style="display: none;">
                                                        <div class="form-group">
                                                            <label for="">Health Insurance No.</label>
                                                            <input type="text" value="{{ request('nhsi_id') }}"
                                                                name="nhsi_id" placeholder="NHSI No."
                                                                class="form-control js-registration-nhsi-no">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 old-health-insurance-block" style="display: none;">
                                                        <div class="form-group">
                                                            <label for="">HI Amount</label>
                                                            <input type="text" id="oldhiamount" name="nhsi_amount"
                                                                placeholder="HI Amount"
                                                                class="form-control js-registration-old-hi-amount" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 old-health-insurance-block"
                                                        style="display: none;">
                                                        <div class="form-group">
                                                            <label for="">Claim Code <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" value="{{ request('claim_code') }}"
                                                                name="claim_code" placeholder="Claim Code"
                                                                class="form-control js-registration-claim-code">
                                                            @if (isset($form_errors['claim_code']))
                                                                <div class="text-danger">
                                                                    {{ $form_errors['claim_code'] }} </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    {{-- <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="">HealthInsuranceProvider Type</label>
                                                            <select name="insurance_type" class="form-control js-registration-insurance-type">
                                                                <option value="">--Select--</option>
                                                                @foreach ($insurances as $insurance)
                                                                <option value="{{ $insurance->id }}">{{ $insurance->insurancetype }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 insurance-toggle">
                                                        <div class="form-group">
                                                            <label for="">HealthInsuranceProvider No</label>
                                                            <input type="text" value="{{ request('nhsi_id') }}" name="nhsi_id" placeholder="NHSI No." class="form-control js-registration-nhsi-no">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 insurance-toggle">
                                                        <div class="form-group">
                                                            <label for="">Claim Code <span class="text-danger">*</span></label>
                                                            <input type="text" value="{{ request('claim_code') }}" name="claim_code" placeholder="Claim Code" class="form-control js-registration-claim-code">
                                                            @if (isset($form_errors['claim_code'])) <div class="text-danger">{{ $form_errors['claim_code'] }} </div>@endif
                                                        </div>
                                                    </div> --}}

                                                    <div class="col-sm-6 col-lg-6">
                                                        <div class="form-group">
                                                            <label for="">Reg Amount</label>
                                                            <input type="text" value="{{ request('amount') }}"
                                                                name="amount" disabled
                                                                class="js-registration-amount form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group" onclick="toggleFollowupdateRequired()">
                                                            <input type="checkbox" name="is_follow_up" value="1"
                                                                id="js-registration-is-follow-up">&nbsp;
                                                            <label for="">Is Follow up</label>
                                                            <input type="text" value="{{ request('followup_date') }}"
                                                                name="followup_date" id="js-registration-followup-date"
                                                                placeholder="Followup Date"
                                                                class="form-control nepaliDatePicker" autocomplete="off">
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-6">
                                                        <div class="form-group">

                                                            <label for="">Referal</label>
                                                            <input type="text" class="form-control js-registration-referal"
                                                                name="referal">

                                                            {{-- <select class="form-control js-registration-referal select2" name="referal">
                                                                <option value="">-- Select --</option>
                                                                @foreach ($referals as $referal)
                                                                <option value="{{ $referal->flduserid }}">{{ $referal->fldusername }}</option>
                                                                @endforeach
                                                            </select> --}}
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6" id="creditPart" style="display: none;">
                                                        <div class="form-group">
                                                            <div class="credit-box">
                                                                <span class="amt"> Credit Amount:</span>
                                                                <div class="blink_me">
                                                                    <span class="creditAmount"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="">Specialization</label><span class="text-danger">*</span>
                                                    <select name="department" class="form-control js-registration-department select2" required>
                                                        departments
                                                        <option value="">--Select--</option>
                                                        @foreach ($departments as $department)
                                                <option value="{{ $department->flddept }}">{{ $department->flddept }}</option>
                                                        @endforeach
                                                </select>
                                                @if (isset($form_errors['department']))
                                                <div class="text-danger">{{ $form_errors['department'] }} </div>
                                                @endif
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label for="">Consultant Name</label>
                                                    <select name="consultant" class="form-control js-registration-consultant select2">
                                                        <option value="">--Select--</option>
                                                    </select>
                                                </div>
                                            </div> --}}
                                        </div>
                                    </div>
                                    <button type="button" class="accordion accordion-box p-2">Personal Information<i
                                            class="fa fa-caret-down float-right"></i></button>
                                    <div class="panel mt-1 mb-1" style="display: block;">
                                        <div class="form-row">
                                            <div class="col-sm-9">
                                                <div class="form-group form-row">
                                                    <div class="col-lg-2 col-sm-2">
                                                        <div class="form-group">
                                                            <label for="">Title <span
                                                                    class="text-danger">*</span></label>
                                                            <select name="title"
                                                                class="js-registration-title form-control select2"
                                                                oninvalid="openPersonalInfoAccordian()" readonly>
                                                                <option value="">Select</option>
                                                                <option value="Mr."
                                                                    @if (request('title') == 'Mr.') selected @endif>
                                                                    Mr.
                                                                </option>
                                                                <option value="Mrs."
                                                                    @if (request('title') == 'Mrs.') selected @endif>
                                                                    Mrs.
                                                                </option>
                                                                <option value="Ms."
                                                                    @if (request('title') == 'Ms.') selected @endif>Ms
                                                                </option>
                                                                <option value="other">Other</option>
                                                            </select>
                                                            @if (isset($form_errors['title']))
                                                                <div class="text-danger">{{ $form_errors['title'] }}
                                                                </div>
                                                            @endif
                                                            <div class="other_salutation" style="display: none;">
                                                                <label for="">If other Title</label>
                                                                <input type="text" value="" name="other_title"
                                                                    placeholder="Title" class="form-control other_title"
                                                                    readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-sm-4">
                                                        <div class="form-group">
                                                            <label for="">First Name <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" value="{{ request('first_name') }}"
                                                                name="first_name" placeholder="First Name"
                                                                class="form-control js-registration-first-name"
                                                                pattern="[a-zA-Z ]+" oninvalid="invalidAlphabets(this)"
                                                                oninput="setCustomValidity('')" required readonly>
                                                            @if (isset($form_errors['first_name']))
                                                                <div class="text-danger">
                                                                    {{ $form_errors['first_name'] }} </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-sm-3 ">
                                                        <label for="">Middle Name</label>
                                                        <input readonly type="text" value="{{ request('middle_name') }}"
                                                            name="middle_name" placeholder="Middle Name"
                                                            class="form-control js-registration-middle-name"
                                                            pattern="[a-zA-Z ]+" oninvalid="invalidAlphabets(this)"
                                                            oninput="setCustomValidity('')">
                                                    </div>
                                                    <div class="col-lg-4 col-sm-3">
                                                        <div class="form-group">
                                                            <label for="">Last Name </label><span
                                                                class="text-danger">*</span>
                                                            <div class=" er-input p-0">
                                                                <input id="js-registration-last-name-old" type="text"
                                                                    autocomplete="off"
                                                                    value="{{ request('last_name') }}" name="last_name"
                                                                    placeholder="Last Name"
                                                                    class="form-control js-registration-last-name"
                                                                    pattern="[a-zA-Z ]+" oninvalid="invalidAlphabets(this)"
                                                                    oninput="setCustomValidity('')">
                                                            </div>
                                                            @if (isset($form_errors['last_name']))
                                                                <div class="text-danger">
                                                                    {{ $form_errors['last_name'] }} </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-8 hidedhow-register">
                                                        <div class="form-row mt-3">
                                                            <label for="">Age</label><span
                                                                class="text-danger"></span>&nbsp;&nbsp;&nbsp;
                                                            <input readonly type="number" min="0"
                                                                value="{{ request('year') }}" name="year"
                                                                class="js-registration-age form-control col-lg-1"
                                                                oninvalid="openPersonalInfoAccordian()">
                                                            &nbsp;&nbsp

                                                            <label>Years</label>&nbsp;&nbsp;&nbsp;
                                                            <input readonly type="number" min="0"
                                                                value="{{ request('month') }}" name="month"
                                                                class="js-registration-month form-control col-lg-1"
                                                                readonly>
                                                            &nbsp;&nbsp;

                                                            <label>Months</label>&nbsp;&nbsp;&nbsp;
                                                            <input readonly type="number" min="0"
                                                                value="{{ request('day') }}" name="day"
                                                                class="js-registration-day form-control col-lg-1" readonly>
                                                            &nbsp;&nbsp;
                                                            <label>Days</label>&nbsp;&nbsp;&nbsp;
                                                            <input readonly type="number" min="0" value="" name="day"
                                                                class="js-registration-hours form-control col-lg-1">
                                                            &nbsp;&nbsp;
                                                            <label>Hours</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4 js-registration-dob-div">
                                                        <div class="form-group">
                                                            <label for="">Date of Birth</label><span
                                                                class="text-danger">*</span>
                                                            <input type="text" value="{{ request('dob') }}" name="dob"
                                                                autocomplete="off" placeholder="Date"
                                                                class="form-control js-registration-dob"
                                                                id="js-registration-dob-old"
                                                                oninvalid="openPersonalInfoAccordian()" readonly required>
                                                            @if (isset($form_errors['dob']))
                                                                <div class="text-danger">{{ $form_errors['dob'] }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label for="">Gender</label><span
                                                                class="text-danger">*</span>
                                                            <select name="gender"
                                                                class="form-control js-registration-gender select2"
                                                                oninvalid="openPersonalInfoAccordian()" readonly required>
                                                                <option value="">--Select--</option>
                                                                @foreach ($genders as $gender)
                                                                    <option value="{{ $gender }}">
                                                                        {{ $gender }}</option>
                                                                @endforeach
                                                            </select>
                                                            @if (isset($form_errors['gender']))
                                                                <div class="text-danger">
                                                                    {{ $form_errors['gender'] }} </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-5">
                                                        <div class="form-group">
                                                            <label for="">Ethnic Group</label>
                                                            <select name="ethnicgroup" id="js-registration-ethnic-old"
                                                                class="form-control js-registration-ethnic-group" readonly>
                                                                <option value="">--Select--</option>
                                                                <option value="1 - Dalit">1 - Dalit</option>
                                                                <option value="2 - Janajati">2 - Janajati</option>
                                                                <option value="3 - Madhesi">3 - Madhesi</option>
                                                                <option value="4 - Muslim">4 - Muslim</option>
                                                                <option value="5 - Brahman/Chhetri">5 -
                                                                    Brahman/Chhetri
                                                                </option>
                                                                <option value="6 - Others">6 - Others</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-3 col-lg-4">
                                                        <div class="form-group">
                                                            <label for="">Blood Group</label>
                                                            <select name="blood_group"
                                                                class="form-control js-registration-blood-group select2"
                                                                readonly>
                                                                <option value="">--Select--</option>
                                                                @foreach ($bloodGroups as $bloodGroup)
                                                                    <option value="{{ $bloodGroup }}">
                                                                        {{ $bloodGroup }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <img class="img-info profile"
                                                        src="{{ asset('assets/images/dummy-img.jpg') }}"
                                                        alt="your image" style="width: 33%; margin-left: 32%;" />
                                                </div>
                                                <div class="form-group text-right">
                                                    <input type='file' name="image" class="ml-3"
                                                        onchange="readURL(this);" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="accordion accordion-box p-2 contactInfoBtn">Contact
                                        information<i class="fa fa-caret-down float-right"></i></button>
                                    <div class="panel mt-1 mb-1 contactInfoPanel">
                                        <div class="form-row">
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="">Country</label><span class="text-danger">*</span>
                                                    <select name="country"
                                                        class="form-control select2 js-registration-country"
                                                        oninvalid="openContactInfoAccordian()" readonly>
                                                        <option value="">--Select--</option>
                                                        @foreach ($countries as $country)
                                                            <option value="{{ $country->fldname }}">{{ $country->fldname }}
                                                            </option>
                                                        @endforeach
                                                        <option value="Other">Other</option>
                                                    </select>
                                                    @if (isset($form_errors['country']))
                                                        <div class="text-danger">{{ $form_errors['country'] }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="">Province</label>
                                                    <select name="province" id="js-old-province"
                                                        class="form-control select2 js-registration-province"
                                                        oninvalid="openContactInfoAccordian()">
                                                        <option value="">--Select--</option>
                                                    </select>
                                                    @if (isset($form_errors['province']))
                                                        <div class="text-danger">{{ $form_errors['province'] }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="">District</label><span class="text-danger">*</span>
                                                    <select name="district"
                                                        class="form-control select2 js-registration-district"
                                                        oninvalid="openContactInfoAccordian()" readonly required>
                                                        <option value="">--Select--</option>
                                                    </select>
                                                    @if (isset($form_errors['district']))
                                                        <div class="text-danger">{{ $form_errors['district'] }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="">Municipality</label>
                                                    <select name="municipality"
                                                        class="form-control select2 js-registration-municipality"
                                                        oninvalid="openContactInfoAccordian()" readonly>
                                                        <option value="">--Select--</option>
                                                    </select>
                                                    @if (isset($form_errors['municipality']))
                                                        <div class="text-danger">{{ $form_errors['municipality'] }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="">Ward No.</label>
                                                    <input type="text" value="{{ request('wardno') }}" name="wardno"
                                                        placeholder="Ward No." class="form-control js-registration-wardno"
                                                        readonly>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="">Tole</label>
                                                    <input type="text" value="{{ request('tole') }}" name="tole"
                                                        placeholder="Tole" class="form-control js-registration-tole"
                                                        readonly>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="">Contact Number</label>
                                                    <input type="tel" value="{{ request('contact') }}" name="contact"
                                                        placeholder="Contact Number"
                                                        class="form-control js-registration-contact-number"
                                                        pattern="^\d{10}$"
                                                        oninvalid="setCustomValidity('Contact number must be atleast 10 digits')"
                                                        oninput="setCustomValidity('')" readonly>
                                                    @if (isset($form_errors['contact-number']))
                                                        <div class="text-danger">
                                                            {{ $form_errors['contact-number'] }} </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-sm-3 js-registration-phone-number-div" style="display: none;">
                                                <div class="form-group">
                                                    <label for="">Phone Number</label>
                                                    <input type="tel" autocomplete="off" value="{{ request('phone') }}"
                                                        name="phone" placeholder="Phone Number"
                                                        class="form-control js-registration-phone-number">
                                                    @if (isset($form_errors['phone']))
                                                        <div class="text-danger">{{ $form_errors['phone'] }} </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="">Email</label>
                                                    <input type="email" name="email" placeholder="Email"
                                                        class="form-control js-registration-email" readonly>
                                                    @if (isset($form_errors['email']))
                                                        <div class="text-danger">{{ $form_errors['email'] }} </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="">Guardian</label>
                                                    <input type="text" value="{{ request('guardian') }}" name="guardian"
                                                        placeholder="Guardian"
                                                        class="form-control js-registration-guardian" readonly>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="">Relation</label>
                                                    <select name="relation"
                                                        class="form-control select2 js-registration-relation" readonly>
                                                        <option value="">--Select--</option>
                                                        @foreach ($relations as $relation)
                                                            <option value="{{ $relation->flditem }}">
                                                                {{ $relation->flditem }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="">Marital Status</label>
                                                    <select name="marital_status"
                                                        class="js-registration-marital-status form-control select2"
                                                        readonly>
                                                        <option value="">--Select--</option>
                                                        @foreach ($maritalStatus as $marital)
                                                            <option value="{{ $marital }}">{{ $marital }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="">Religion</label>
                                                    <input type="text" name="religion" placeholder="Religion"
                                                        class="form-control" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="accordion accordion-box p-2">Other information<i
                                            class="fa fa-caret-down float-right"></i></button>
                                    <div class="panel mt-1 mb-1">
                                        <div class="form-row">
                                            <div class="col-sm-4 col-lg-4">
                                                <div class="form-group">
                                                    <label for="">National Id</label>
                                                    <input readonly type="text" value="{{ request('national_id') }}"
                                                        name="national_id" placeholder="National Id"
                                                        class="form-control js-registration-national-id">
                                                </div>
                                            </div>
                                            <div class="col-sm-4 col-lg-4">
                                                <div class="form-group">
                                                    <label for="">Citizenship No.</label>
                                                    <input readonly type="text" value="{{ request('citizenship_no') }}"
                                                        name="citizenship_no" placeholder="Citizenship No."
                                                        class="form-control js-registration-citizenship-no">
                                                </div>
                                            </div>
                                            <div class="col-sm-4 col-lg-4">
                                                <div class="form-group">
                                                    <label for="">PAN Number</label>
                                                    <input readonly type="text" value="{{ request('pan_number') }}"
                                                        name="pan_number" placeholder="PAN Number"
                                                        class="form-control js-registration-pan-number">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="accordion accordion-box p-2">Payment Details<i
                                            class="fa fa-caret-down float-right"></i></button>
                                    <div class="panel mt-1 mb-1 panel-payment">
                                        <div class="res-table">
                                            <div id="billing-body">
                                                <table class="table table-striped table-bordered table-hover">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th></th>
                                                            <th style="width: 60%;">Particulars</th>
                                                            <th class="text-center">Price</th>
                                                            <th class="text-center">Discount</th>
                                                            <th class="text-center">Amount</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="js-registration-billing-tbody"></tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td></td>
                                                            <td class="text-center"></td>
                                                            <td class="text-center">Remarks</td>
                                                            <td class="text-center" colspan="2">
                                                                <input class="form-control price_remarks" type="text"
                                                                    name="price_remarks" placeholder="Remarks">
                                                            </td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="form-horizontal">
                                            <div class="row">
                                                <div class="col-sm-6">

                                                    <label class="col-12">Payment Mode</label>

                                                    <div class="bak-payment p-2">
                                                        <div class="form-row">
                                                            <div class="col-sm-3  pay-rad checked-bak" id="cash_payment"
                                                                onclick="getRadioFunction('Cash')">
                                                                <div class="custom-control custom-radio custom-control-inline"
                                                                    onclick="getRadioFunction('Cash')">
                                                                    <input type="radio" id="cash" name="payment_mode"
                                                                        class="custom-control-input payment_mode"
                                                                        value="Cash" checked>
                                                                    <label class="custom-control-label" for="cash"
                                                                        onclick="getRadioFunction('Cash')"> Cash</label>
                                                                </div>
                                                                <div class="img-ms-form"
                                                                    onclick="getRadioFunction('Cash')">
                                                                    <img src="{{ asset('new/images/cash-2.png') }}"
                                                                        class="img-ms-form" alt="">
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-3 pay-rad" id="credit_payment"
                                                                onclick="getRadioFunction('Credit')">
                                                                <div class="custom-control custom-radio custom-control-inline"
                                                                    onclick="getRadioFunction('Credit')">
                                                                    <input type="radio" id="credit" name="payment_mode"
                                                                        class="custom-control-input payment_mode"
                                                                        value="Credit">
                                                                    <label class="custom-control-label" for="credit"
                                                                        onclick="getRadioFunction('Credit')"> Credit
                                                                    </label>
                                                                </div>
                                                                <div class="img-ms-form"
                                                                    onclick="getRadioFunction('Credit')">
                                                                    <img src="{{ asset('new/images/credit-3.png') }}"
                                                                        class="img-ms-form" alt="">
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-3 pay-rad" id="card_payment"
                                                                onclick="getRadioFunction('Card')">
                                                                <div class="custom-control custom-radio custom-control-inline"
                                                                    onclick="getRadioFunction('Card')">
                                                                    <input type="radio" id="card" name="payment_mode"
                                                                        class="custom-control-input payment_mode"
                                                                        value="Card">
                                                                    <label class="custom-control-label " for="card"
                                                                        onclick="getRadioFunction('Card')"> Card </label>
                                                                </div>
                                                                <div class="mt-2 img-ms-form"
                                                                    onclick="getRadioFunction('Card')">
                                                                    <img src="{{ asset('new/images/swipe2.png') }}"
                                                                        class="img-ms-form" alt="">
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-3 pay-rad" id="fonepay_payment"
                                                                onclick="getRadioFunction('Fonepay')">
                                                                <div class="custom-control custom-radio custom-control-inline"
                                                                    onclick="getRadioFunction('Fonepay')">
                                                                    <input type="radio" id="fonepay" name="payment_mode"
                                                                        class="custom-control-input payment_mode"
                                                                        value="Fonepay"
                                                                        onclick="getRadioFunction('Fonepay')">
                                                                    <label class="custom-control-label" for="fonepay"
                                                                        onclick="getRadioFunction('Fonepay')">Fonepay
                                                                    </label>
                                                                </div>
                                                                <div class="img-ms-form"
                                                                    onclick="getRadioFunction('Fonepay')">
                                                                    <img src="{{ asset('new/images/fonepay_logo.png') }}"
                                                                        class="ml-4" class="img-ms-form"
                                                                        alt="">
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- <div class="form-group form-row align-items-center">
                                                                        <label class="col-sm-3">Payment Mode:</label>
                                                                        <div class="col-sm-5">
                                                                            <select name="payment_mode"
                                                                                    class="form-control payment_mode">
                                                                                <option selected value="Cash">Cash</option>
                                                                                {{-- <option value="Credit">Credit</option>
                                                                <option value="Cheque">Cheque</option>
                                                                <option value="Fonepay">Fonepay</option>
                                                                <option value="Others">Others</option> --}}
                                                                            </select>
                                                                        </div>
                                                                    </div> -->
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group form-row align-items-center expected_date">
                                                        <label class="col-sm-5">Expected Payment Date</label>
                                                        <div class="col-sm-7">
                                                            <div class="input-group">
                                                                <input type="text" name="expected_payment_date_nepali"
                                                                    class="form-control expected_payment_date_nepali"
                                                                    id="expected_payment_date_nepali-old">
                                                                <input type="hidden" name="expected_payment_date"
                                                                    class="form-control expected_payment_date">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- if cash --}}
                                        <div class="form-horizontal border-bottom pt-3">
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <input type="text" name="cheque_number" placeholder="Cheque Number"
                                                            class="form-control cheque_number">
                                                        <input type="text" name="other_reason" placeholder="Reason"
                                                            class="form-control other_reason">
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <select name="bank_name" class="form-control bank-name">
                                                            <option value="">Select Bank</option>
                                                            @if (count($banks))
                                                                @forelse($banks as $bank)
                                                                    <option value="{{ $bank->fldbankname }}">
                                                                        {{ $bank->fldbankname }}</option>
                                                                @empty
                                                                @endforelse
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <input type="text" name="office_name" placeholder="Office Name"
                                                            class="form-control office_name">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- end if cash --}}
                                        <div class="d-flex mt-2 pt-2 mb-4" style="float: right;">
                                            @if (Options::get('register_bill') != 'SaveAndBill')
                                                <button class="btn btn-primary js-registrationform-submit-btn">Save
                                                </button>&nbsp;
                                            @endif

                                            @if (Options::get('register_bill') == 'SaveAndBill')
                                                <input type="submit" class="btn btn-primary js-registrationform-submit-btn"
                                                    name="bill" value="Save and bill">
                                            @endif
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="js-registration-add-item-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="encounter_listLabel" style="text-align: center;">Variables</h5>
                    <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">Ãƒâ€”</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" id="js-registration-flditem-input-modal" class="form-control"
                            style="width: 100%;">
                    </div>
                    <div>
                        <button class="btn btn-primary" id="js-registration-add-btn-modal"><i
                                class="ri-add-fill"></i>Add
                        </button>
                        <button class="btn btn-danger" style="float: right;" id="js-registration-delete-btn-modal"><i
                                class="ri-delete-bin-5-fill"></i>Delete
                        </button>
                    </div>
                    <br>
                    <div class="table-responsive table-sroll-lab">
                        <table id="js-registration-table-modal" class="table table-bordered table-hover"></table>
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

    <script>
        (function blink() {
            $('.blink_me').fadeOut(500).fadeIn(500, blink);
        })();




        $(document).ready(function () {
            var activeForm = $('div.tab-pane.fade.active.show');
            $(activeForm).find(".js-registration-country").val('NEPAL');
            $("#regsitrationForm,#oldRegistrationForm").submit(function () {
                $('.bak-payment').hide();
                $(".js-registrationform-submit-btn").attr("disabled", true);
                return true;
            });

});
    </script>
    <script src="{{ asset('js/number-format.js') }}"></script>
    <script src="{{ asset('js/search-ajax.js') }}"></script>
    <script src="{{ asset('js/registration_form.js') }}"></script>
    <script src="{{ asset('js/registrationform_form.js') }}"></script>
    <script type="text/javascript">
        
        
        function hideAll() {
            $('.office-name').hide();
            $('.bank-name').next(".select2-container").hide();
            $('.expected_date').hide();
            $('.cheque_number').hide();
            $('.office_name').hide();
            $('.other_reason').hide();
        }

        function getRadioFunction(value) {
            var activeForm = $('div.tab-pane.fade.active.show');
            if (value == "Cash") {
                hideAll();
                $(activeForm).find('#cash_payment').addClass('checked-bak');
                $(activeForm).find('#credit_payment').removeClass('checked-bak');
                $(activeForm).find('#card_payment').removeClass('checked-bak');
                $(activeForm).find('#fonepay_payment').removeClass('checked-bak');
                $(activeForm).find('.payment-save-done').show();
            } else if (value == "Credit") {
                hideAll();
                $(activeForm).find('#cash_payment').removeClass('checked-bak');
                $(activeForm).find('#credit_payment').addClass('checked-bak');
                $(activeForm).find('#card_payment').removeClass('checked-bak');
                $(activeForm).find('#fonepay_payment').removeClass('checked-bak');
                $(activeForm).find('.expected_date').show();
                $(activeForm).find('.payment-save-done').show();

            } else if (value == "Card") {
                hideAll();
                $(activeForm).find('#cash_payment').removeClass('checked-bak');
                $(activeForm).find('#credit_payment').removeClass('checked-bak');
                $(activeForm).find('#card_payment').addClass('checked-bak');
                $(activeForm).find('#fonepay_payment').removeClass('checked-bak');
                $(activeForm).find('.payment-save-done').show();
            } else if (value == "Fonepay") {
                hideAll();
                $(activeForm).find('#cash_payment').removeClass('checked-bak');
                $(activeForm).find('#credit_payment').removeClass('checked-bak');
                $(activeForm).find('#card_payment').removeClass('checked-bak');
                $(activeForm).find('#fonepay_payment').addClass('checked-bak');

                $(activeForm).find('.payment-save-done').show();
                var convergent = $('#convergent_payment_status').val();
                var encounter = $('#fldencounterval').val();
                var generateQr = $('#generate_qr').val();
                var patientType = $(activeForm).find('.js-registration-billing-mode').val();
                var discountScheme = $(activeForm).find('.js-registration-discount-scheme').val();
                var title = $(activeForm).find('.js-registration-title').val();
                var firstname = $(activeForm).find('.js-registration-first-name').val();
                var lastname = $(activeForm).find('.js-registration-last-name').val();
                var dob = $(activeForm).find('.js-registration-dob').val();
                var gender = $(activeForm).find('.js-registration-gender').val();
                var country = $(activeForm).find('.js-registration-country').val();
                var province = $(activeForm).find('.js-registration-province').val();
                var district = $(activeForm).find('.js-registration-district').val();
                var municipality = $(activeForm).find('.js-registration-municipality').val();
                var contactnumber = $(activeForm).find('.js-registration-contact-number').val();
                if (patientType == '') {
                    showAlert('Choose Patient Type.');
                    return false;
                }

                if (discountScheme == '') {
                    showAlert('Choose Discount Scheme.');
                    return false;
                }

                if (title == '') {
                    showAlert('Choose Title.');
                    return false;
                }

                if (firstname == '') {
                    showAlert('First Name Required.');
                    return false;
                }

                if (lastname == '') {
                    showAlert('Last Name Required.');
                    return false;
                }

                if (dob == '') {
                    showAlert('Date Of Birth Required.');
                    return false;
                }

                if (gender == '') {
                    showAlert('Select Gender.');
                    return false;
                }

                if (gender == '') {
                    showAlert('Select Gender.');
                    return false;
                }

                if (country == '') {
                    showAlert('Select Country.');
                    return false;
                }

                if (province == '') {
                    showAlert('Choose Province.');
                    return false;
                }

                if (district == '') {
                    showAlert('Choose Disctrict.');
                    return false;
                }

                if (municipality == '') {
                    showAlert('Choose Municipality.');
                    return false;
                }

                if (contactnumber == '') {

                    showAlert('Contact Number is Required.');
                    return false;
                }

                if (contactnumber.length != 10) {
                    showAlert('Contact number must be of 10 digits.');
                    return false;
                }
                if (convergent != '' && convergent == 'active' && generateQr == 'yes') {
                    var totalamount = $(activeForm).find('.js-registration-amount').val();
                    if (totalamount == '') {
                        showAlert('Amount not available');
                        return false;
                    }
                    fonepayQrGenerate();
                }
            } else if (value == "Other") {
                hideAll();
                $('#other_reason').show();
                $(activeForm).find('.payment-save-done').show();
            } else {
                hideAll();
                $(activeForm).find('#cash_payment').addClass('checked-bak');
                $(activeForm).find('#credit_payment').removeClass('checked-bak');
                $(activeForm).find('#card_payment').removeClass('checked-bak');
                $(activeForm).find('#fonepay_payment').removeClass('checked-bak');

            }
            // return false;
        }

        function fonepayQrGenerate() {
            var activeForm = $('div.tab-pane.fade.active.show');
            let route = "{!! route('convergent.payments.registration') !!}";
            $.ajax({
                url: route,
                type: "POST",
                data: {
                    'firstname': $(activeForm).find('.js-registration-first-name').val(),
                    'middlename': $(activeForm).find('.js-registration-middle-name').val(),
                    'lastname': $(activeForm).find('.js-registration-last-name').val(),
                    'total': $(activeForm).find('.js-registration-amount').val(),
                    '_token': '{{ csrf_token() }}'
                },
                success: function(data) {
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

        var addresses = JSON.parse('{!! $addresses !!}');
        var initdistricts = JSON.parse('{!! $districts !!}');
        $(function() {

            // search ssf number
            $("#input-ssf-number").on('keyup', function() {
                $("#ssf-allowed-money").val('');
            });

            $("#refresh-ssf").on('click', function() {
                $("#input-ssf-number").trigger('keyup');
            });

            $("#input-ssf-number").searchAjax({
                url: '/ssf/patient-detail',
                urlParam: true,
                global: false,
                param: ["id"],
                spinner: "#ssf-number-spinner",
                method: 'get',
                paginate: false,
                onResult: function(res) {
                    let input = $("#input-ssf-number");
                    if (res.data != "") {
                        populatePersonalInfo(res.data);
                        input.css({
                            borderColor: 'green'
                        });

                        // check for eligibility
                        let eligibility = checkEligibility(input.val()).then(function(res) {
                            $("#ssf-allowed-money").val(formatMoney(res.data.finance
                                .allowedMoney));
                        });
                    } else {
                        resetPersonalInfo();
                        if (input.val() != "") {
                            input.css({
                                borderColor: 'red'
                            });
                            return;
                        }
                        input.css({
                            borderColor: 'black'
                        });
                    }
                },
                onError: function(xhr) {
                    if (xhr.status == 500) {
                        showAlert('Server Error. Please try again later.', '');
                    }
                }
            });

            async function checkEligibility(patientId) {
                let route = "{!! route('ssf.check-eligibility', ':PATIENT_ID') !!}";
                route = route.replace(':PATIENT_ID', patientId);
                return await $.ajax({
                    url: route,
                    type: 'GET',
                    dataType: 'JSON',
                    async: true
                });
            }

            function resetPersonalInfo() {
                $("#input-first-name").val('');
                $("#js-registration-last-name-new").select2().select2('val', $(
                    '#js-registration-last-name-new option:eq(1)').val());
                $("#js-registration-dob-new").val('').trigger('change');
                $(".js-registration-gender").val('');
            }

            function populatePersonalInfo(info) {
                $("#input-first-name").val(info.firstname);
                var newOption = new Option(info.lastname, info.lastname, true, true);
                $('#js-registration-last-name-new').append(newOption).trigger('change');
                $("#js-registration-dob-new").val(info.nepali_dob);
                $(".js-registration-gender").val(info.gender);

                var detail = getAgeDetail(info.english_dob);
                $('.js-registration-age').val(detail.age);
                $('.js-registration-month').val(detail.month);
                $('.js-registration-day').val(detail.day);
            }

            // old patient type
            $('#old-select-patient-type').on('change', function(event) {
                event.preventDefault();
                let value = $(this).val();
                $("#old-block-ssf-number").hide();
                if (value.toLowerCase() == 'ssf') {
                    $("#old-block-ssf-number").show();
                }

                if (value.toLowerCase() == 'ssf' || value.toLowerCase() == 'health insurance' || value.toLowerCase() == 'healthinsurance' || value.toLowerCase() == 'hi' ) {
                    $(".old-insurance-block").show();
                    if (value.toLowerCase() == 'health insurance' || value.toLowerCase() == 'healthinsurance' || value.toLowerCase() == 'hi' ) {
                        $(".old-health-insurance-block").show();
                    } else {
                        $(".old-health-insurance-block").hide();
                    }
                } else {
                    $(".old-insurance-block").hide();
                    $(".old-health-insurance-block").hide();
                }
            });

            // new patient type select
            $('#select-patient-type').on('change', function(event) {
                event.preventDefault();
                let value = $(this).val();
                $("#block-ssf-number").hide();
                if (value.toLowerCase() == 'ssf') {
                    $("#block-ssf-number").show();
                }

                if (value.toLowerCase() == 'ssf' || value.toLowerCase() == 'health insurance' || value.toLowerCase() == 'healthinsurance' || value.toLowerCase() == 'hi'  )  {
                    $(".insurance-block").show();
                    if (value.toLowerCase() == 'health insurance' || value.toLowerCase() == 'healthinsurance' || value.toLowerCase() == 'hi') {
                        $(".health-insurance-block").show();
                    } else {
                        $(".health-insurance-block").hide();
                    }
                } else {
                    $(".insurance-block").hide();
                    $(".health-insurance-block").hide();
                }
            });

            $('input[type="text"],input[type="number"],input[type="tel"],input[type="email"]').keydown(function(
                event) {
                if (event.which == 13)
                    event.preventDefault();
            });
        });
        var oldProvince = "{{ request('province') }}" || null;
        var olddistrict = "{{ request('district') }}" || null;
        var oldmunicipality = "{{ request('municipality') }}" || null;
        $(document).ready(function() {
            @if (Session::has('reg_patient_id'))
                var patientId = "{{ Session::get('reg_patient_id') }}";
                var encounterId = "{{ Session::get('reg_encounter_id') }}";
                @if (Options::get('issue_ticket') == 'Yes')
                    @if (Options::get('issue_ticket_number') !== null)
                        @if (Options::get('issue_ticket_number') == 2)
                            window.open(baseUrl + '/registrationform/printnextticket/' + patientId + '?fldencounterval=' +
                            encounterId, '_blank');
                        @else
                            window.open(baseUrl + '/registrationform/printticket/' + patientId + '?fldencounterval=' + encounterId,
                            '_blank');
                        @endif
                    @else
                        window.open(baseUrl + '/registrationform/printticket/' + patientId + '?fldencounterval=' + encounterId,
                        '_blank');
                    @endif
                @endif
            @endif
            @if (Options::get('register_bill') == 'SaveAndBill')
                @if (Session::has('billno'))
                    var billno = "{{ Session::get('billno') }}";
                    var encounterId = "{{ Session::get('reg_encounter_id') }}";
                    window.open(baseUrl + '/billing/service/billing-display-invoice?encounter_id=' + encounterId +
                    '&invoice_number=' + billno, '_blank');
                @endif
            @endif

            $(document).on('select2:select', '.select2', function() {
                $(this).focus();
            });
        });

        // $(document).on('focusout', '.ndp-nepali-calendar', function() {
        //     setTimeout(() => {
        //         setSelectedDay($(this).val());
        //     }, 200);
        // });


        $('#ER-radio').click(function() {
            $('#regsitrationForm').find('.consultant-span').hide();
            $('#regsitrationForm').find('.js-registration-consultant').prop('required', false);
        });

        $('#ER-radio-old').click(function() {
            $('#oldRegistrationForm').find('.consultant-span').hide();
            $('#oldRegistrationForm').find('.js-registration-consultant').prop('required', false);
        });

        $('.img-ms-form').click(function() {
            $(this).parent().find('input[type=radio]').prop('checked', true);
        });
        $('.pay-rad').click(function() {
            $(this).closest('.checked-bak').find('input[type=radio]').prop('checked', true);
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#adDate').prop('checked', true);
            $('.nepali-dob').css('visibility', 'hidden');
            $('#nep_from_date').removeAttr('value')
            $('#eng_from_date').removeAttr('value');
            $('.img-ms-form').click(function() {
                $(this).parent().find('input[type=radio]').prop('checked', true);
            });
            $('.pay-rad').click(function() {
                $(this).closest('.checked-bak').find('input[type=radio]').prop('checked', true);
            });
            $('#expected_payment_date_nepali-new').nepaliDatePicker({
                ndpMonth: true,
                ndpYear: true,
                onChange: function() {
                    var nep_date = NepaliFunctions.ConvertToDateObject($('#expected_payment_date_nepali-new').val(),'YYYY-MM-DD');
                    console.log(nep_date);
                    var nepali_date = NepaliFunctions.BS2AD(nep_date);
                    $('.expected_payment_date').val(NepaliFunctions.ConvertDateFormat(nepali_date));
                }
            });
            $('#expected_payment_date_nepali-old').nepaliDatePicker({
                ndpMonth: true,
                ndpYear: true,
                onChange: function() {
                    var nep_date = NepaliFunctions.ConvertToDateObject($('#expected_payment_date_nepali-old').val(),'YYYY-MM-DD');
                    console.log(nep_date);
                    var nepali_date = NepaliFunctions.BS2AD(nep_date);
                    $('.expected_payment_date').val(NepaliFunctions.ConvertDateFormat(nepali_date));
                }
            });

            $('#nep_date').nepaliDatePicker({
                ndpMonth: true,
                ndpYear: true,
                onChange: function() {
                    var nep_date = NepaliFunctions.ConvertToDateObject($('#nep_date').val(),'YYYY-MM-DD');
                    console.log(nep_date);
                    var nepali_date = NepaliFunctions.BS2AD(nep_date);
                    $('#eng_from_date').val(NepaliFunctions.ConvertDateFormat(nepali_date));
                    $('#js-registration-dob-new').val(NepaliFunctions.ConvertDateFormat(nepali_date));
                    var activeForm = $('div.tab-pane.fade.active.show');
                    var detail = getAgeDetail($('#eng_from_date').val());
                    $(activeForm).find('.js-registration-age').val(detail.age);
                    $(activeForm).find('.js-registration-month').val(detail.month);
                    $(activeForm).find('.js-registration-day').val(detail.day);
                    $(activeForm).find('.js-registration-hours').val(0);
                    $('#nep_from_date').val($('#nep_date').val());
                }
            });
            $(".date-label").click(function() {
                if (this.value == 'ad') {
                    console.log('date',this.value);
                    $('.english-dob').css('visibility', 'visible');
                    $('.nepali-dob').css('visibility', 'hidden');
                    if ($('#eng_from_date').val() != '') {
                        $('#js-registration-dob-new').val($('#eng_from_date').val());
                    }
                } else {
                    console.log(this.value);
                    $('.english-dob').css('visibility', 'hidden');
                    $('.nepali-dob').css('visibility', 'visible');
                    if ($('#nep_from_date').val() != '') {
                        $('#nep_date').val($('#nep_from_date').val());
                    }
                }
            });
        });
        $('.js-registration-country').on('change',function(){
            var activeForm = $('div.tab-pane.fade.active.show');
            var country = $(this).val();
            // alert(country);
            if(country !='NEPAL' || country == ''){
                
                $(activeForm).find('.js-registration-province').prop('disabled',true);
                $(activeForm).find('.js-registration-province').removeAttr('required');

                $(activeForm).find('.js-registration-district').prop('disabled',true);
                $(activeForm).find('.js-registration-district').removeAttr('required');

                $(activeForm).find('.js-registration-municipality').prop('disabled',true);
                $(activeForm).find('.js-registration-municipality').removeAttr('required');
            }else{
                $(activeForm).find('.js-registration-province').prop('disabled',false);
                $(activeForm).find('.js-registration-province').attr('required');

                $(activeForm).find('.js-registration-district').prop('disabled',false);
                $(activeForm).find('.js-registration-district').attr('required');

                $(activeForm).find('.js-registration-municipality').prop('disabled',false);
                $(activeForm).find('.js-registration-municipality').attr('required');
                
                getProvinces(country, null);
            }   
        })

        // $('#regsitrationForm').submit(function(e) {
        //     e.preventDefault();
        //     const $this = $(this);
        //     const oldPatientId = $('.js-registration-oldpatientid-id').val();
        //     if (oldPatientId != '') {
        //         $this.unbind('submit').submit()
        //     }
        //     const route = "{!! route('previous.registration') !!}";
        //     var activeForm = $('div.tab-pane.fade.active.show');
        //     $.ajax({
        //         url: route,
        //         type: "POST",
        //         data: {
        //             '_token': '{{ csrf_token() }}',
        //             'firstname': $(activeForm).find('.js-registration-first-name').val(),
        //             'middlename': $(activeForm).find('.js-registration-middle-name').val(),
        //             'lastname': $(activeForm).find('.js-registration-last-name').val(),
        //             'dob': $(activeForm).find('.js-registration-dob').val(),
        //             'contact': $(activeForm).find('.js-registration-contact-number').val()
        //         },
        //         success: function(data) {
        //             if (data && data.length && Object.prototype.toString.call(data) ===
        //                 '[object Array]') {
        //                 let message =
        //                     `This ${$(activeForm).find('.js-registration-contact-number').val()} phone number is already allocated to patient No:- ${data.join(',')} \n Do you want to generate new patient No. ?`;
        //                 ConfirmDialog(message, $this);
        //             } else {
        //                 $this.unbind('submit').submit()
        //             }
        //         }
        //     });
        // });

        function ConfirmDialog(message, $this) {
            $('<div class="confirm-box"></div>').appendTo('body')
                .html('<div><h6>' + message + '?</h6></div>')
                .dialog({
                    modal: true,
                    title: 'Patient Already Exist',
                    zIndex: 10000,
                    autoOpen: true,
                    width: '500',
                    resizable: false,
                    dialogClass: 'no-close success-dialog',
                    buttons: {
                        No: function() {
                            $(this).dialog("close");
                        },
                        Yes: function() {
                            $(this).dialog("close");
                            $this.unbind('submit').submit()
                        }
                    },
                    close: function(event, ui) {
                        $(this).remove();
                    }
                });
        };
    </script>
@endpush
