@extends('inpatient::pdf.layout.main')

@section('title')
OT Checklists
@endsection

@section('report_type')
OT Checklists
@endsection

@section('content')
<style>
    .checklist-container {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
    }

    .form-card {
        width: 33%;
        border: 1px solid #ccc;
        padding: 10px 20px;
        position: relative;
        padding-bottom:80px;
    }

    .content-body {
        border-collapse: collapse;
    }

    .content-body td,
    .content-body th {
        border: 1px solid #ddd;
    }

    .content-body {
        font-size: 12px;
    }

    .name {
        border-bottom: 1px solid #c0c0c0;
        width: 120px;
        display: inline-block;
    }

    .name-content {
        margin-top: 10px;
    }

    .sign {
        border-bottom: 1px solid #c0c0c0;
        width: 120px;
        height: 30px;
        display: inline-block;
    }

    .bottom-container{
        position:absolute;
        bottom:8px;
    }

    @media print {
        input[type=radio]:checked+label::after {
            visibility: hidden;
        }

        input[type=radio]:checked+label::before {
            top: 4px;
            border-width: 4px;
        }

        input[type=checkbox]:checked+label::after {
            visibility: hidden;
        }

        input[type=checkbox]:checked+label::before {
            top: 4px;
            border-width: 8px;
        }
    }
</style>

<div class="checklist-container">

    <div class="form-card text-left ">
        <div class="row">
            <div class="col-7">
                <h3 class="mb-4">Sign In Checklist:</h3>
            </div>

        </div>
        <form id="signinform">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" name="fldpatientconfirm" id="fldpatientconfirm" @if(isset($otchecklistdata)) @if($otchecklistdata->fldpatientconfirm == 1) checked @endif @endif value="1">
                            <label class="custom-control-label" for="customCheck1"><b>Patient has confirmed</b></label>
                            <ul class="ml-4">
                                <li>Identity</li>
                                <li>Site</li>
                                <li>Procedure</li>
                                <li>Consent</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" name="fldsitemarked" id="fldsitemarked" @if(isset($otchecklistdata)) @if($otchecklistdata->fldsitemarked == 1) checked @endif @endif value="1">
                            <label class="custom-control-label" for="customCheck2"><b>Site marked/Not applicable</b></label>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" name="fldanaesthesiachecked" id="fldanaesthesiachecked" @if(isset($otchecklistdata)) @if($otchecklistdata->fldanaesthesiachecked == 1) checked @endif @endif value="1">
                            <label class="custom-control-label" for="customCheck3"><b>Anesthesia safety check completed</b></label>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" name="fldoxyfunct" id="fldoxyfunct" @if(isset($otchecklistdata)) @if($otchecklistdata->fldoxyfunct == 1) checked @endif @endif value="1">
                            <label class="custom-control-label" for="customCheck4"><b>Pulse oximeter on paitent and functioning</b></label>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 mt-2">

                    <label class="ml-4">
                        <h3>Does patient have a :</h3>
                    </label>
                    <div class="form-group">
                        <label class="ml-4 mt-2"><b>Known Allergy?</b></label>
                        <div class="custom-control custom-radio">
                            <input type="radio" class="custom-control-input" name="fldhasallergy" value="0" @if(isset($otchecklistdata)) @if($otchecklistdata->fldhasallergy == 0) checked @endif @endif>
                            <label class="custom-control-label">No</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input type="radio" class="custom-control-input" name="fldhasallergy" value="1" @if(isset($otchecklistdata)) @if($otchecklistdata->fldhasallergy == 1) checked @endif @endif>
                            <label class="custom-control-label">Yes</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="ml-4 mt-2"><b>Difficult airway/aspiration risk?</b></label>
                        <div class="custom-control custom-radio">
                            <input type="radio" name="fldairwayrisk" class="custom-control-input" value="0" @if(isset($otchecklistdata)) @if($otchecklistdata->fldairwayrisk == 0) checked @endif @endif>
                            <label class="custom-control-label">No</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input type="radio" name="fldairwayrisk" class="custom-control-input" value="1" @if(isset($otchecklistdata)) @if($otchecklistdata->fldairwayrisk == 1) checked @endif @endif>
                            <label class="custom-control-label">Yes, and equipped/assistance available</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="ml-4 mt-2"><b>Risk of > 500Ml Blood Loss(7ML/KG in children)?</b></label>
                        <div class="custom-control custom-radio">
                            <input type="radio" name="fldbloodlossrisk" class="custom-control-input" value="0" @if(isset($otchecklistdata)) @if($otchecklistdata->fldbloodlossrisk == 0) checked @endif @endif>
                            <label class="custom-control-label">No</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input type="radio" name="fldbloodlossrisk" class="custom-control-input" value="1" @if(isset($otchecklistdata)) @if($otchecklistdata->fldbloodlossrisk == 1) checked @endif @endif>
                            <label class="custom-control-label">Yes,and adequate intravenous access and fluids planned.</label>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="bottom-container">
            <div class="name-content">Name : <span class="name"> @if(isset($otchecklistdata)) {{$otchecklistdata->fldsigninuser}} @endif </span></div>
            <div> Signature :
                <span class="sign">
                    @if(isset($otchecklistdata))
                        @if(isset($otchecklistdata->signinuser->signature_image))
                            <img class="" style="width: 90%;" src="data:image/jpg;base64,{{ $otchecklistdata->signinuser->signature_image }}" alt="">
                        @endif
                    @endif
                </span>
            </div>
        </div>
    </div>

    <div class="form-card text-left ">
        <div class="row">
            <div class="col-7">
                <h3 class="mb-4">Time Out Checklist</h3>
            </div>

        </div>
        <form id="timeoutform">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" name="fldconfirmteam" id="fldconfirmteam" value="1" @if(isset($otchecklistdata)) @if($otchecklistdata->fldconfirmteam == 1) checked @endif @endif>
                            <label class="custom-control-label" for="customCheck5"><b>Confirm all team members have introduced themeselves by name and role.</b>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" name="fldverbalconfirm" id="fldverbalconfirm" value="1" @if(isset($otchecklistdata)) @if($otchecklistdata->fldverbalconfirm == 1) checked @endif @endif>
                            <label class="custom-control-label" for="customCheck6"><b>Surgeon, Anesthesia Professional and nurse verbally confirm</b></label>
                            <ul class="ml-4">
                                <li>Patient</li>
                                <li>Site</li>
                                <li>Precedure</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="my-3 ml-4"><b>Anticipated Critical Risks</b></div>
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" name="fldsurgeonreview" id="fldsurgeonreview" value="1" @if(isset($otchecklistdata)) @if($otchecklistdata->fldsurgeonreview == 1) checked @endif @endif>
                            <label class="custom-control-label" for="customCheck7"><b>Surgeons Regviews:</b> What are the critical or unexpected steps,operative duration,anticipated blood loss?</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" name="fldanaesthesianreview" id="fldanaesthesianreview" value="1" @if(isset($otchecklistdata)) @if($otchecklistdata->fldanaesthesianreview == 1) checked @endif @endif>
                            <label class="custom-control-label" for="customCheck8"><b>Anesthesia Team Reviews:</b> Are there any patient-specific concerns?</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" name="fldnursingreview" id="fldnursingreview" value="1" @if(isset($otchecklistdata)) @if($otchecklistdata->fldnursingreview == 1) checked @endif @endif>
                            <label class="custom-control-label" for="customCheck9"><b>Nurshing Team Reviews:</b> Has sterility (including indicators results) been confirmed? Are there equipment issues or any concerns?</label>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label class="ml-4 mt-2"><b>Has antibiotic prophylaxis been given within the last 60 minutes?</b></label>
                        <div class="custom-control custom-radio">
                            <input type="radio" name="fldantibioticprophyloxis" class="custom-control-input" value="1" @if(isset($otchecklistdata)) @if($otchecklistdata->fldantibioticprophyloxis == 1) checked @endif @endif>
                            <label class="custom-control-label">Yes</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input type="radio" name="fldantibioticprophyloxis" class="custom-control-input" value="0" @if(isset($otchecklistdata)) @if($otchecklistdata->fldantibioticprophyloxis == 0) checked @endif @endif>
                            <label class="custom-control-label">Not applicable</label>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label class="ml-4 mt-2"><b>Is essential imaging displayed?</b></label>
                        <div class="custom-control custom-radio">
                            <input type="radio" name="fldimagingdisplay" class="custom-control-input" value="1" @if(isset($otchecklistdata)) @if($otchecklistdata->fldimagingdisplay == 1) checked @endif @endif>
                            <label class="custom-control-label">Yes</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input type="radio" name="fldimagingdisplay" class="custom-control-input" value="0" @if(isset($otchecklistdata)) @if($otchecklistdata->fldimagingdisplay == 0) checked @endif @endif>
                            <label class="custom-control-label">Not applicable</label>
                        </div>
                    </div>
                </div>

            </div>

        </form>

        <div class="bottom-container">
            <div class="name-content">Name : <span class="name"> @if(isset($otchecklistdata)) {{$otchecklistdata->fldtimeoutuser}} @endif </span></div>
            <div> Signature :
                <span class="sign">
                    @if(isset($otchecklistdata))
                        @if(isset($otchecklistdata->timeoutuser->signature_image))
                            <img class="" style="width: 90%;" src="data:image/jpg;base64,{{ $otchecklistdata->timeoutuser->signature_image }}" alt="">
                        @endif
                    @endif
                </span>
            </div>
        </div>
    </div>

    <div class="form-card text-left">
        <div class="row">
            <div class="col-7">
                <h3 class="mb-4">Sign Out Checklist:</h3>
            </div>

        </div>
        <form id="signoutform">
            <div class="my-3 ml-4">
                <h4>Nurse verbally confirms with the team:</h4>
            </div>
            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" name="fldprocrecord" id="fldprocrecord" value="1" @if(isset($otchecklistdata)) @if($otchecklistdata->fldprocrecord == 1) checked @endif @endif>
                    <label class="custom-control-label" for="customCheck10"><b>The name of the procedure recorded.</b></label>
                </div>
            </div>
            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" name="fldinstrucorrect" id="fldinstrucorrect" value="1" @if(isset($otchecklistdata)) @if($otchecklistdata->fldinstrucorrect == 1) checked @endif @endif>
                    <label class="custom-control-label" for="customCheck11"><b>That instrument,sponge and needle counts are correct</b> (Or not applicable)</label>
                </div>
            </div>
            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" name="fldspecimentlabelled" id="fldspecimentlabelled" value="1" @if(isset($otchecklistdata)) @if($otchecklistdata->fldspecimentlabelled == 1) checked @endif @endif>
                    <label class="custom-control-label" for="customCheck12"><b>How the speciment is labelled</b> (Including patient name)</label>
                </div>
            </div>
            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" name="fldequipprobaddressed" id="fldequipprobaddressed" value="1" @if(isset($otchecklistdata)) @if($otchecklistdata->fldequipprobaddressed == 1) checked @endif @endif>
                    <label class="custom-control-label" for="customCheck13"><b>Whether there are any equipment problmes to be addressed</b></label>
                </div>
            </div>
            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" name="fldkeyconcernreview" id="fldkeyconcernreview" value="1" @if(isset($otchecklistdata)) @if($otchecklistdata->fldkeyconcernreview == 1) checked @endif @endif>
                    <label class="custom-control-label" for="customCheck14"><b>Surgeons, Anesthesia professional and nurse review the key concerns for recovery and management of this patient</b></label>
                </div>
            </div>

        </form>

        <div class="bottom-container">
            <div class="name-content">Name : <span class="name"> @if(isset($otchecklistdata)) {{$otchecklistdata->fldsignoutuser}} @endif </span></div>
            <div> Signature :
                <span class="sign">
                    @if(isset($otchecklistdata))
                        @if(isset($otchecklistdata->signoutuser->signature_image))
                            <img class="" style="width: 90%;" src="data:image/jpg;base64,{{ $otchecklistdata->signoutuser->signature_image }}" alt="">
                        @endif
                    @endif
                </span>
            </div>
        </div>
    </div>

</div>

@endsection

@push('after-script')
<script src="{{asset('assets/js/jquery-3.4.1.min.js')}}"></script>
<script>
    $(document).ready(function() {
        $('#signinform').unbind("click");
        $('#signinform').css("pointer-events", "none");
        $('#timeoutform').unbind("click");
        $('#timeoutform').css("pointer-events", "none");
        $('#signoutform').unbind("click");
        $('#signoutform').css("pointer-events", "none");
    });
</script>
@endpush
