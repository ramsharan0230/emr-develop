@push('after-styles')
    <style>
        @media print {
            input[type=radio]:checked + label::after {
                visibility: hidden;
            }
            input[type=radio]:checked + label::before {
                top: 4px;
                border-width: 4px;
            }

            input[type=checkbox]:checked + label::after {
                visibility: hidden;
            }
            input[type=checkbox]:checked + label::before {
                top: 4px;
                border-width: 8px;
            }
        }


        .tab-list__link{
            width: 100% !important;
            /* background-color: #eff7f8;
            color: #089bab;
            display: block;
            padding: 15px;
            margin: 0 10px;
            border-radius: 25px;
            -webkit-border-radius: 25px;
            text-decoration: none; */

        }

        .tab-list__link .step{
            background:none !important;
        }

        .nav-pills{ margin: 0 0px 0px; overflow: hidden; color: #777D74; }
        .tab-list__item.active { color: var(--main-bg-color); border:1px solid #cfcfcf;  background: var(--main-bg-color);}
        .nav-pills li { list-style-type: none; font-size: 18px; width: 33%; float: left; text-align: left; position: relative; font-weight: 400 }
        .nav-pills li i { display: inline-block; text-align: center; height: 50px; width: 50px; line-height: 50px; font-size: 20px; border-radius: 50%; margin: 0 15px 0 0; color: #ffffff; background: var(--main-bg-color); }
        .nav-pills li a.active { color: #fff; background: var(--main-bg-color); }
        .nav-pills li a.done { color: #fff; background:#089bab;}
        .nav-pills li.active a { color: #fff; background: var(--main-bg-color); }
        .nav-pills li.active.done a { background: #27b345; }
        .nav-pills li.active.done i { color: #27b345; }
        .nav-pills li#confirm.active a { background: #27b345; }
        .nav-pills li#confirm.active i { color: #27b345; }
        .nav-pills li a { background: #eff7f8; color: var(--main-bg-color); display: block; padding: 15px; margin: 0 10px; border-radius: 25px; -webkit-border-radius: 25px; text-decoration: none;font-size:20px;font-weight: 500;}

        .nav-pills li.active i { background-color: #fff; color: var(--main-bg-color); }
        .fit-image { width: 100%; object-fit: cover }
        .nav-pills li a.active .step i{ color: var(--main-bg-color); ; background:#fff; }

        b {
            font-weight: 600;
        }

        .tab-list__link {
            font-weight: 700;
            font-size: 15px;
            color: #fff;
            display: inline-block;
            -webkit-border-radius: 22.5px;
            -moz-border-radius: 22.5px;
            border-radius: 22.5px;
            background: #999;
            width: 162px;
            text-align: left;
        }
        .tab-list__link .step {
            display: inline-block;
            height: 45px;
            width: 45px;
            line-height: 45px;
            text-align: center;
            -webkit-border-radius: 50%;
            -moz-border-radius: 50%;
            border-radius: 50%;
            background: #666;
            font-size: 18px;
            margin-right: 10px;
        }
        .tab-list {
            list-style: none;
            text-align: center;
            padding: 5px 0 10px;
            border-bottom: 1px solid #e5e5e5;
        }
        .tab-list__item {
            display: inline-block;
            -webkit-transition: all .4s ease;
            -o-transition: all .4s ease;
            -moz-transition: all .4s ease;
            transition: all .4s ease;
            padding: 0 13px;
        }
        .tab-list .active .tab-list__link {
            background: #3155cc;
        }
        .tab-list .active .tab-list__link .step {
            background: #3d6aff;
        }
        .tab-list__link .desc {
            text-transform: capitalize;
            display: inline-block;
        }
    </style>
@endpush
<div class="tab-panel collapse" id="otchecklist" role="tabpanel" aria-labelledby="otchecklist-tab" data-parent="#accordion">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <!--start form wizard-->
                        <div class="container text-center">
                            <ul class="tab-list  nav nav-pills ">
                                @if (\App\Utils\Permission::checkPermissionFrontendAdmin( 'signin-otchecklists' ))
                                    <li class="tab-list__item">
                                        <a class="tab-list__link @if(isset($activeOtChecklistTab)) @if($activeOtChecklistTab == "signin") active @endif @endif  @if(isset($otchecklistdata)) @if($otchecklistdata->fldsignincomp == 1) done @endif @else active @endif" href="#signin" data-toggle="tab" aria-expanded="true" id="signin_tab">
                                            <span class="step"><i class="ri-login-box-line"></i></span>
                                            <span class="desc">Sign In</span>
                                        </a>
                                    </li>
                                @endif

                                @if (\App\Utils\Permission::checkPermissionFrontendAdmin( 'timeout-otchecklists' ))
                                    <li class="tab-list__item">
                                        <a class="tab-list__link @if(isset($activeOtChecklistTab)) @if($activeOtChecklistTab == "timeout") active @endif @endif @if(isset($otchecklistdata)) @if($otchecklistdata->fldtimeoutcomp == 1) done @endif @endif" href="#timeout" data-toggle="tab" aria-expanded="false" id="timeout_tab" data-signincomp="@if(isset($otchecklistdata)) @if($otchecklistdata->fldsignincomp == 1) 1 @else 0 @endif @else 0 @endif">
                                            <span class="step"><i class="ri-close-circle-fill"></i></span>
                                            <span class="desc">Time Out</span>
                                        </a>
                                    </li>
                                @endif

                                @if (\App\Utils\Permission::checkPermissionFrontendAdmin( 'signout-otchecklists' ))
                                    <li class="tab-list__item">
                                        <a class="tab-list__link  @if(isset($activeOtChecklistTab)) @if($activeOtChecklistTab == "signout") active @endif @endif @if(isset($otchecklistdata)) @if($otchecklistdata->fldsignoutcomp == 1) done @endif @endif" href="#signout" data-toggle="tab" aria-expanded="false" id="signout_tab" data-signincomp="@if(isset($otchecklistdata)) @if($otchecklistdata->fldsignincomp == 1) 1 @else 0 @endif @else 0 @endif" data-timeoutcomp="@if(isset($otchecklistdata)) @if($otchecklistdata->fldtimeoutcomp == 1) 1 @else 0 @endif @else 0 @endif">
                                            <span class="step"><i class="ri-logout-box-fill"></i></span>
                                            <span class="desc">Sign Out</span>
                                        </a>
                                    </li>
                                @endif
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                @if (\App\Utils\Permission::checkPermissionFrontendAdmin( 'signin-otchecklists' ))
                                    <div id="signin" class="tab-pane  @if(isset($activeOtChecklistTab)) @if($activeOtChecklistTab == "signin") show active @else fade @endif @else show active @endif"><br>
                                        <div class="form-card text-left">
                                            <div class="row">
                                                <div class="col-7">
                                                    <h3 class="mb-4">Sign In Checklist:</h3>
                                                </div>
                                                <div class="col-5">
                                                    <h2 class="steps">Step 1 - 3</h2>
                                                </div>
                                            </div>
                                            <form id="signinform">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input" name="fldpatientconfirm"  id="fldpatientconfirm" @if(isset($otchecklistdata)) @if($otchecklistdata->fldpatientconfirm == 1) checked @endif @endif value="1">
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
                                                                <label class="custom-control-label" for="customCheck4"><b>Pulse oximeter on patent and functioning</b></label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12 mt-2">

                                                        <label class="ml-4"><h5>Does patient have A :</h5></label>
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
                                        </div>
                                        <button type="button" name="next" class="btn btn-primary next btn-xl btn-action float-right" value="Next" id="submitSignin" data-signincomp="@if(isset($otchecklistdata)) @if($otchecklistdata->fldsignincomp == 1) 1 @else 0 @endif @endif">Next</button>
                                    </div>
                                @endif
                                @if (\App\Utils\Permission::checkPermissionFrontendAdmin( 'timeout-otchecklists' ))
                                    <div id="timeout" class="tab-pane @if(isset($activeOtChecklistTab)) @if($activeOtChecklistTab == "timeout") show active @else fade @endif @endif"><br>
                                        <div class="form-card text-left ">
                                            <div class="row">
                                                <div class="col-7">
                                                    <h3 class="mb-4">Time Out Checklist</h3>
                                                </div>
                                                <div class="col-5">
                                                    <h2 class="steps">Step 2 - 3</h2>
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
                                                                <label class="custom-control-label" for="customCheck7"><b>Surgeons Reviews:</b> What are the critical or unexpected steps,operative duration,anticipated blood loss?</label>
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
                                                                <label class="custom-control-label" for="customCheck9"><b>Nursing Team Reviews:</b> Has sterility (including indicators results) been confirmed? Are there equipment issues or any concerns?</label>
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
                                        </div>
                                        <button type="button" name="next" class="btn btn-primary next action-button btn-action float-right" value="Next" id="submitTimeout" data-timeoutcomp="@if(isset($otchecklistdata)) @if($otchecklistdata->fldtimeoutcomp == 1) 1 @else 0 @endif @endif">Next</button>
                                        <button type="button" name="previous" class="btn btn-dark previous action-button-previous btn-action float-right mr-3" value="Previous" id="gotosignin">Previous</button>
                                    </div>
                                @endif
                                @if (\App\Utils\Permission::checkPermissionFrontendAdmin( 'signout-otchecklists' ))
                                    <div id="signout" class="tab-pane @if(isset($activeOtChecklistTab)) @if($activeOtChecklistTab == "signout") show active @else fade @endif @endif"><br>
                                        <div class="form-card text-left">
                                            <div class="row">
                                                <div class="col-7">
                                                    <h3 class="mb-4">Sign Out Checklist:</h3>
                                                </div>
                                                <div class="col-5">
                                                    <h2 class="steps">Step 3 - 3</h2>
                                                </div>
                                            </div>
                                            <form id="signoutform">
                                                <div class="my-3 ml-4"><h4>Nurse verbally confirms with the team:</h4></div>
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
                                                        <label class="custom-control-label" for="customCheck12"><b>How the specimen is labelled</b> (Including patient name)</label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" name="fldequipprobaddressed" id="fldequipprobaddressed" value="1" @if(isset($otchecklistdata)) @if($otchecklistdata->fldequipprobaddressed == 1) checked @endif @endif>
                                                        <label class="custom-control-label" for="customCheck13"><b>Whether there are any equipment problems to be addressed</b></label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" name="fldkeyconcernreview" id="fldkeyconcernreview" value="1" @if(isset($otchecklistdata)) @if($otchecklistdata->fldkeyconcernreview == 1) checked @endif @endif>
                                                        <label class="custom-control-label" for="customCheck14"><b>Surgeons, Anesthesia professional and nurse review the key concerns for recovery and management of this patient</b></label>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        @if(isset($otchecklistcomp))
                                            @if($otchecklistcomp == 0)
                                                <button type="button" name="next" class="btn btn-primary next action-button float-right" value="Submit" id="submitSignout" data-signoutcomp="@if(isset($otchecklistdata)) @if($otchecklistdata->fldsignoutcomp == 1) 1 @else 0 @endif @endif">Submit</button>
                                            @endif
                                        @endif
                                        <button type="button" name="previous" class="btn btn-dark previous action-button-previous float-right mr-3" value="Previous" id="gototimeout">Previous</button>
                                    </div>
                                @endif
                            </div>

                        </div>
                        <!--end wizard-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('after-script')
    <script>
        $(document).ready(function(){
            @if(isset($otchecklistdata))
            @if($otchecklistdata->fldsignincomp == 1)
            $('#signinform').unbind("click");
            $('#signinform').css("pointer-events","none");
            @endif
            @if($otchecklistdata->fldtimeoutcomp == 1)
            $('#timeoutform').unbind("click");
            $('#timeoutform').css("pointer-events","none");
            @endif
            @if($otchecklistdata->fldsignoutcomp == 1)
            $('#signoutform').unbind("click");
            $('#signoutform').css("pointer-events","none");
            @endif
            @endif
        });
        $(document).on('click','#submitSignin',function(){
            var encounter_id = $('#encounter_id').val();
            if(encounter_id == '0'){
                alert('Choose Encounter');
                return false;
            }
            if($(this).attr('data-signincomp') != 1){
                var formData = new FormData($('#signinform')[0]);
                formData.append("encounter_id", encounter_id);
                var object = {};
                formData.forEach(function(value, key){
                    object[key] = value;
                });
                if(Object.keys(object).length != 8){
                    alert("Please fill all the data!");
                    return false;
                }

                $.ajax({
                    url:"{{ route('inpatient.saveOtSignin') }}",
                    method:"POST",
                    data: formData,
                    contentType: false,
                    cache:false,
                    processData: false,
                    dataType:"json",
                    success:function(data){
                        if(data.status){
                            showAlert(data.message);
                            $('#submitSignin').attr('data-signincomp',1);
                            $('#timeout_tab').attr('data-signincomp',1);
                            $('#signout_tab').attr('data-signincomp',1);
                            $('#signin_tab').addClass('done');
                            $('#signinform').unbind("click");
                            $('#signinform').css("pointer-events","none");
                        }else{
                            showAlert("Something went wrong!!", 'Error');
                            return false;
                        }
                    }
                });
            }
            @if (\App\Utils\Permission::checkPermissionFrontendAdmin( 'timeout-otchecklists' ))
            toggleActiveTabs("timeout_tab");
            @else
            showAlert("No Timeout Permission Available!!", 'Error');
            @endif
        });

        $(document).on('click','#submitTimeout',function(){
            var encounter_id = $('#encounter_id').val();
            if(encounter_id == '0'){
                alert('Choose Encounter');
                return false;
            }
            if($(this).attr('data-timeoutcomp') != 1){
                var formData = new FormData($('#timeoutform')[0]);
                formData.append("encounter_id", encounter_id);
                var object = {};
                formData.forEach(function(value, key){
                    object[key] = value;
                });
                if(Object.keys(object).length != 8){
                    alert("Please fill all the data!");
                    return false;
                }

                $.ajax({
                    url:"{{ route('inpatient.saveOtTimeout') }}",
                    method:"POST",
                    data: formData,
                    contentType: false,
                    cache:false,
                    processData: false,
                    dataType:"json",
                    success:function(data){
                        if(data.status){
                            showAlert(data.message);
                            $('#submitTimeout').attr('data-timeoutcomp',1);
                            $('#signout_tab').attr('data-timeoutcomp',1);
                            $('#timeout_tab').addClass('done');
                            $('#timeoutform').unbind("click");
                            $('#timeoutform').css("pointer-events","none");
                        }else{
                            showAlert("Something went wrong!!", 'Error');
                            return false;
                        }
                    }
                });
            }
            @if (\App\Utils\Permission::checkPermissionFrontendAdmin( 'signout-otchecklists' ))
            toggleActiveTabs("signout_tab");
            @else
            showAlert("No Signout Permission Available!!", 'Error');
            @endif
        });

        $(document).on('click','#submitSignout',function(){
            var encounter_id = $('#encounter_id').val();
            if(encounter_id == '0'){
                alert('Choose Encounter');
                return false;
            }
            if($(this).attr('data-signoutcomp') != 1){
                var formData = new FormData($('#signoutform')[0]);
                formData.append("encounter_id", encounter_id);
                var object = {};
                formData.forEach(function(value, key){
                    object[key] = value;
                });
                if(Object.keys(object).length != 6){
                    alert("Please fill all the data!");
                    return false;
                }

                $.ajax({
                    url:"{{ route('inpatient.saveOtSignout') }}",
                    method:"POST",
                    data: formData,
                    contentType: false,
                    cache:false,
                    processData: false,
                    dataType:"json",
                    success:function(data){
                        if(data.status){
                            showAlert(data.message);
                            $('#submitSignout').attr('data-signoutcomp',1);
                            $('#signout_tab').addClass('done');
                            $('#submitSignout').hide();
                            $('#signoutform').unbind("click");
                            $('#signoutform').css("pointer-events","none");
                        }else{
                            showAlert("Something went wrong!!", 'Error');
                            return false;
                        }
                    }
                });
            }
        });

        $(document).on('click','#gotosignin',function(){
            toggleActiveTabs("signin_tab");
        });

        $(document).on('click','#gototimeout',function(){
            toggleActiveTabs("timeout_tab");
        });

        function toggleActiveTabs(tabName){
            var navItems = ["signin_tab","timeout_tab","signout_tab"];
            var tabPanes = ["signin","timeout","signout"];
            var navTabs = {"signin_tab": "signin", "timeout_tab": "timeout", "signout_tab": "signout"};
            $.each(navItems, function( index, value ) {
                if($("#"+value).hasClass("active")){
                    $("#"+value).removeClass("active");
                }
            });
            $.each(tabPanes, function( index, value ) {
                if($("#"+value).hasClass("active")){
                    $("#"+value).removeClass("active");
                }
                if($("#"+value).hasClass("show")){
                    $("#"+value).removeClass("show");
                }
            });
            $('#'+tabName).addClass("active");
            $('#'+navTabs[tabName]).addClass("show");
            $('#'+navTabs[tabName]).addClass("active");
        }

        $(document).on('click','#timeout_tab',function(e){
            if($(this).attr('data-signincomp') == 0){
                showAlert("Fill sign up first!","error");
                $(this).removeAttr("data-toggle");
                toggleActiveTabs("signin_tab");
            }else{
                $(this).attr("data-toggle","tab");
                $(this)[0].click();
            }
        });

        $(document).on('click','#signout_tab',function(e){
            var current = $(this);
            if(current.attr('data-signincomp') == 0){
                showAlert("Fill sign up first!","error");
                $(this).removeAttr("data-toggle");
                toggleActiveTabs("signin_tab");
                return false;
            }else{
                if(current.attr('data-timeoutcomp') == 0){
                    showAlert("Fill time out first!","error");
                    $(this).removeAttr("data-toggle");
                    toggleActiveTabs("timeout_tab");
                    return false;
                }
            }
            current.attr("data-toggle","tab");
            current[0].click();
        });

    </script>
@endpush
