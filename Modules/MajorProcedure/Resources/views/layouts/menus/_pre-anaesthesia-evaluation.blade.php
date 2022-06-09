<style>
    @media print {
        input[type=radio]:checked + label::after {
            visibility: hidden;
        }
        input[type=radio]:checked + label::before {
            top: 4px;
            border-width: 4px;
        }
    }

</style>
<div class="tab-pane fade collapse" id="pre-anaesthesia-evaluation" role="tabpanel" aria-labelledby="pre-anaesthesia-evaluation-tab" data-parent="#accordion">

    <form id="anaethestic_form">
        <div class="iq-card-body p-4">
            <div class="form-card text-left">
                <div class="row">
                    <div class="col-12">
                        <h3 class="mb-4">Pre- Anaesthetic Evaluation Checklist:</h3>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="mt-2"><b>1) Have you ever had anaesthetic in the past ?</b></label>
                        </div>

                        <div class="row ml-2">
                            <div class="col-md-4 form-group">
                                <label>Year</label>
                                <input type="text" placeholder="calendar" class="form-control ndp-nepali-calendar" name="past_anaesthetic_date" id="past_anaesthetic_date" value="" onfocus="showNdpCalendarBox('past_anaesthetic_date')" >
                            </div>

                            <div class="col-md-4 form-group">
                                <label>Surgical Procedure</label>
                                <input type="text" placeholder="" class="form-control" name="surgerical_procedure" id="surgerical_procedure" value=" {{ isset($preanaethestic) ? $preanaethestic->surgerical_procedure :'' }}">
                            </div>

                            <div class="col-md-4 form-group">
                                <label>Hospital Name </label>
                                <input type="text" placeholder="" class="form-control" name="hospital_name" value="{{ isset($preanaethestic) ? $preanaethestic->hospital_name :'' }}" id="hospital_name">
                            </div>

                        </div>


                        <div class="form-group ml-4">
                            <label class="mt-2"><b>Types of Anesthesia?</b></label>
                            <div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="anesthesia_type" class="custom-control-input" id="ga" value="ga" @if(isset($preanaethestic)) @if($preanaethestic->anesthesia_type == 'ga') checked @endif @endif>
                                    <label class="custom-control-label" for="customCheck2">GA</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="anesthesia_type" class="custom-control-input" id="ra" value="ra" @if(isset($preanaethestic)) @if($preanaethestic->anesthesia_type == 'ra') checked @endif @endif>
                                    <label class="custom-control-label" for="customCheck2">RA</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="anesthesia_type" class="custom-control-input" id="local" value="local" @if(isset($preanaethestic)) @if($preanaethestic->anesthesia_type == 'local') checked @endif @endif>
                                    <label class="custom-control-label" for="customCheck2">Local anaesthesia</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="mt-2"><b>2. (a) Have your any member of your family had reaction to an anaesthetic?</b></label>
                            <div class="ml-4">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="reaction" class="custom-control-input" id="reaction_no" value="0"  @if(isset($preanaethestic)) @if($preanaethestic->reaction == 0) checked @endif @endif>
                                    <label class="custom-control-label" for="customCheck2">No</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="reaction" class="custom-control-input" id="reaction_yes" value="1"  @if(isset($preanaethestic)) @if($preanaethestic->reaction == 1) checked @endif @endif>
                                    <label class="custom-control-label" for="customCheck2">Yes</label>
                                </div>

                            </div>
                        </div>

                        <div class="form-group ml-3">
                            <label class="mt-2"><b>(b) Has any member of your family had reaction an anaesthestic?</b></label>
                            <div class="ml-2">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="has_family_reaction" class="custom-control-input" id="has_family_reaction_no" value="0" @if(isset($preanaethestic)) @if($preanaethestic->has_family_reaction == 0) checked @endif @endif>
                                    <label class="custom-control-label" for="customCheck2">No</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="has_family_reaction" class="custom-control-input" id="has_family_reaction_yes" value="1" @if(isset($preanaethestic)) @if($preanaethestic->has_family_reaction == 1) checked @endif @endif>
                                    <label class="custom-control-label" for="customCheck2">Yes</label>
                                </div>

                            </div>
                        </div>

                        <!-- Diseas-->
                        {{--                    <div class="form-group ">--}}
                        {{--                        <label class="mt-2"><b>4) Have you ever had following disease ?</b></label>--}}
                        {{--                        <div class="ml-4">--}}
                        {{--                            <div class="custom-control custom-radio custom-control-inline">--}}
                        {{--                                <input type="radio" name="diseas" class="custom-control-input" id="hear_attack" value="hear_attack" @if(isset($preanaethestic)) @if($preanaethestic->have_cough == 0) checked @endif @endif>--}}
                        {{--                                <label class="custom-control-label" for="customCheck2">Heart attack</label>--}}
                        {{--                            </div>--}}
                        {{--                            <div class="custom-control custom-radio custom-control-inline">--}}
                        {{--                                <input type="radio" name="diseas" class="custom-control-input" id="hear_disease" value="hear_disease" @if(isset($preanaethestic)) @if($preanaethestic->have_cough == 1) checked @endif @endif>--}}
                        {{--                                <label class="custom-control-label" for="customCheck2">Heart disease</label>--}}
                        {{--                            </div>--}}
                        {{--                            <div class="custom-control custom-radio custom-control-inline">--}}
                        {{--                                <input type="radio" name="diseas" class="custom-control-input" id="rhuematic_fever" value="rhuematic_fever" @if(isset($preanaethestic)) @if($preanaethestic->have_cough == 1) checked @endif @endif>--}}
                        {{--                                <label class="custom-control-label" for="customCheck2">Rheumatic Fever</label>--}}
                        {{--                            </div>--}}
                        {{--                            <div class="custom-control custom-radio custom-control-inline">--}}
                        {{--                                <input type="radio" name="diseas" class="custom-control-input" id="high_bp" value="high_bp" @if(isset($preanaethestic)) @if($preanaethestic->have_cough == 1) checked @endif @endif>--}}
                        {{--                                <label class="custom-control-label" for="customCheck2">High Blood Pressure</label>--}}
                        {{--                            </div>--}}
                        {{--                            <div class="custom-control custom-radio custom-control-inline">--}}
                        {{--                                <input type="radio" name="diseas" class="custom-control-input" id="asthma" value="asthma" @if(isset($preanaethestic)) @if($preanaethestic->have_cough == 1) checked @endif @endif>--}}
                        {{--                                <label class="custom-control-label" for="customCheck2">Asthma</label>--}}
                        {{--                            </div>--}}
                        {{--                            <div class="custom-control custom-radio custom-control-inline">--}}
                        {{--                                <input type="radio" name="diseas" class="custom-control-input" id="chronic_bronchitis" value="chronic_bronchitis" @if(isset($preanaethestic)) @if($preanaethestic->have_cough == 1) checked @endif @endif>--}}
                        {{--                                <label class="custom-control-label" for="customCheck2">Chronic Bronchitis</label>--}}
                        {{--                            </div>--}}
                        {{--                            <div class="custom-control custom-radio custom-control-inline">--}}
                        {{--                                <input type="radio" name="diseas" class="custom-control-input" id="tiberculosis" value="tiberculosis" @if(isset($preanaethestic)) @if($preanaethestic->have_cough == 1) checked @endif @endif>--}}
                        {{--                                <label class="custom-control-label" for="customCheck2">Tibeculosis</label>--}}
                        {{--                            </div>--}}
                        {{--                            <div class="custom-control custom-radio custom-control-inline">--}}
                        {{--                                <input type="radio" name="diseas" class="custom-control-input" id="arthritis" value="arthritis" @if(isset($preanaethestic)) @if($preanaethestic->have_cough == 1) checked @endif @endif>--}}
                        {{--                                <label class="custom-control-label" for="customCheck2">Arthritis</label>--}}
                        {{--                            </div>--}}
                        {{--                            <div class="custom-control custom-radio custom-control-inline">--}}
                        {{--                                <input type="radio" name="diseas" class="custom-control-input" id="jaundices" value="jaundices" @if(isset($preanaethestic)) @if($preanaethestic->have_cough == 1) checked @endif @endif>--}}
                        {{--                                <label class="custom-control-label" for="customCheck2">Jaundicises</label>--}}
                        {{--                            </div>--}}
                        {{--                            <div class="custom-control custom-radio custom-control-inline">--}}
                        {{--                                <input type="radio" name="diseas" class="custom-control-input" id="diabetes" value="diabetes" @if(isset($preanaethestic)) @if($preanaethestic->have_cough == 1) checked @endif @endif>--}}
                        {{--                                <label class="custom-control-label" for="customCheck2">Diabetes</label>--}}
                        {{--                            </div>--}}

                        {{--                            <div class="custom-control custom-radio custom-control-inline">--}}
                        {{--                                <input type="radio" name="diseas" class="custom-control-input" id="thyorides_disease" value="thyorides_disease" @if(isset($preanaethestic)) @if($preanaethestic->have_cough == 1) checked @endif @endif>--}}
                        {{--                                <label class="custom-control-label" for="customCheck2">Thyorides Disease</label>--}}
                        {{--                            </div>--}}

                        {{--                            <div class="custom-control custom-radio custom-control-inline">--}}
                        {{--                                <input type="radio" name="diseas" class="custom-control-input" id="kidney_disease" value="kidney_disease" @if(isset($preanaethestic)) @if($preanaethestic->have_cough == 1) checked @endif @endif>--}}
                        {{--                                <label class="custom-control-label" for="customCheck2">Kidney Disease</label>--}}
                        {{--                            </div>--}}

                        {{--                            <div class="custom-control custom-radio custom-control-inline">--}}
                        {{--                                <input type="radio" name="diseas" class="custom-control-input" id="mental_or_nervous_diseas" value="mental_or_nervous_diseas" @if(isset($preanaethestic)) @if($preanaethestic->have_cough == 1) checked @endif @endif>--}}
                        {{--                                <label class="custom-control-label" for="customCheck2">Mental or Nervous Disease</label>--}}
                        {{--                            </div>--}}

                        {{--                            <div class="custom-control custom-radio custom-control-inline">--}}
                        {{--                                <input type="radio" name="diseas" class="custom-control-input" id="muscular_disease" value="muscular_disease" @if(isset($preanaethestic)) @if($preanaethestic->have_cough == 1) checked @endif @endif>--}}
                        {{--                                <label class="custom-control-label" for="customCheck2">Muscular Disease</label>--}}
                        {{--                            </div>--}}

                        {{--                        </div>--}}
                        {{--                    </div>--}}




                        <div class="form-group ">
                            <label class="mt-2"><b>3) Do you smoke ?</b></label>
                            <div class="ml-4">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="smoke" class="custom-control-input" id="smoke_no" value="0" @if(isset($preanaethestic)) @if($preanaethestic->smoke == 0) checked @endif @endif>
                                    <label class="custom-control-label" for="customCheck2">No</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="smoke" class="custom-control-input" id="smoke_yes" value="1" @if(isset($preanaethestic)) @if($preanaethestic->smoke == 1) checked @endif @endif>
                                    <label class="custom-control-label" for="customCheck2">Yes</label>
                                </div>

                                <div class="form-group custom-control-inline">
                                    <!-- <label>Hospital Name </label> -->
                                    <input type="text" placeholder="" class="form-control" name="smoke_description" value="{{ isset($preanaethestic) ? $preanaethestic->smoke_description :'' }}" id="smoke_description">
                                </div>
                            </div>
                        </div>

                        <div class="form-group ">
                            <label class="mt-2"><b>4) Are you having cough or cold at present ?</b></label>
                            <div class="ml-4">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="have_cough" class="custom-control-input" id="cough_no" value="0" @if(isset($preanaethestic)) @if($preanaethestic->have_cough == 0) checked @endif @endif>
                                    <label class="custom-control-label" for="customCheck2">No</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="have_cough" class="custom-control-input" id="cough_yes" value="1" @if(isset($preanaethestic)) @if($preanaethestic->have_cough == 1) checked @endif @endif>
                                    <label class="custom-control-label" for="customCheck2">Yes</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group ">
                            <label class="mt-2"><b>5) Do you take any medicine regularly ?</b></label>
                            <div class="ml-4">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="regular_medicine" class="custom-control-input" id="regular_medicine_no" value="0" @if(isset($preanaethestic)) @if($preanaethestic->regular_medicine == 0) checked @endif @endif>
                                    <label class="custom-control-label" for="customCheck2">No</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="regular_medicine" class="custom-control-input" id="regular_medicine_yes" value="1" @if(isset($preanaethestic)) @if($preanaethestic->regular_medicine == 1) checked @endif @endif>
                                    <label class="custom-control-label" for="customCheck2">Yes</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group ">
                            <label class="mt-2"><b>6) Are you, or have you been a drug user (alcohol, street drug or related drigs)?</b></label>
                            <div class="ml-4">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="drug_user" class="custom-control-input" id="drug_user_no" value="0" @if(isset($preanaethestic)) @if($preanaethestic->drug_user == 0) checked @endif @endif>
                                    <label class="custom-control-label" for="customCheck2">No</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="drug_user" class="custom-control-input" id="drug_user_yes" value="1" @if(isset($preanaethestic)) @if($preanaethestic->drug_user == 1) checked @endif @endif>
                                    <label class="custom-control-label" for="customCheck2">Yes</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group ">
                            <label class="mt-2"><b>7) Do you have any allergies ?</b></label>
                            <div class="ml-4">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="have_allergies" class="custom-control-input" id="have_allergies_no" value="0" @if(isset($preanaethestic)) @if($preanaethestic->have_allergies == 0) checked @endif @endif >
                                    <label class="custom-control-label" for="customCheck2">No</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="have_allergies" class="custom-control-input" id="have_allergies_yes" value="1"  @if(isset($preanaethestic)) @if($preanaethestic->have_allergies == 1) checked @endif @endif>
                                    <label class="custom-control-label" for="customCheck2">Yes</label>
                                </div>

                                <div class="form-group custom-control-inline">
                                    <!-- <label>Hospital Name </label> -->
                                    <input type="text" placeholder="" class="form-control" name="allergy_description" value="{{ isset($preanaethestic) ? $preanaethestic->allergy_description :'' }}" id="allergy_descriptionloose_teeth">
                                </div>
                            </div>
                        </div>

                        <div class="form-group ">
                            <label class="mt-2"><b>8) Do you have loose teeth, capped teeth, caries, dentures or related drugs?</b></label>
                            <div class="ml-4">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="loose_teeth" class="custom-control-input" id="loose_teeth_no" value="0" @if(isset($preanaethestic)) @if($preanaethestic->loose_teeth == 0) checked @endif @endif>
                                    <label class="custom-control-label" for="customCheck2">No</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="loose_teeth" class="custom-control-input" id="loose_teeth_yes" value="1" @if(isset($preanaethestic)) @if($preanaethestic->loose_teeth == 1) checked @endif @endif>
                                    <label class="custom-control-label" for="customCheck2">Yes</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group ">
                            <label class="mt-2"><b>8) Woman only : Are you Pregnant?</b></label>
                            <div class="ml-4">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="has_pregnancy" class="custom-control-input" id="has_pregnancy_no" value="0" @if(isset($preanaethestic)) @if($preanaethestic->has_pregnancy == 0) checked @endif @endif>
                                    <label class="custom-control-label" for="customCheck2">No</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="has_pregnancy" class="custom-control-input" id="has_pregnancy_yes" value="1" @if(isset($preanaethestic)) @if($preanaethestic->has_pregnancy == 1) checked @endif @endif>
                                    <label class="custom-control-label" for="customCheck2">Yes</label>
                                </div>

                                <div class="form-group custom-control-inline">
                                    <label>LMP&nbsp;</label>
                                    <input type="text" placeholder="" class="form-control" name="pregnancy_lmp" value="{{ isset($preanaethestic) ? $preanaethestic->pregnancy_lmp :'' }}" id="pregnancy_lmp">
                                </div>
                            </div>
                        </div>

                        <div class="row ">
                            <div class="col-md-12"><b>9) Physical Examination</b></div>
                            <div class="col-md-4 form-group pl-4">
                                <label>Peripheral Veins</label>
                                <div class="">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" name="physical_examination" class="custom-control-input" id="physical_examination_good" value="good" @if(isset($preanaethestic)) @if($preanaethestic->has_pregnancy == 'good') checked @endif @endif>
                                        <label class="custom-control-label" for="customCheck2">Good</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" name="physical_examination" class="custom-control-input" id="physical_examination_difficult" value="difficult" @if(isset($preanaethestic)) @if($preanaethestic->has_pregnancy == 'difficult') checked @endif @endif>
                                        <label class="custom-control-label" for="customCheck2">Difficult</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 form-group pl-4">
                                <label>TMJ</label>
                                <div class="">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" name="tmj" class="custom-control-input" id="tmj_free" value="free" @if(isset($preanaethestic)) @if($preanaethestic->has_pregnancy == 'free') checked @endif @endif>
                                        <label class="custom-control-label" for="customCheck2">Free</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" name="tmj" class="custom-control-input" id="tmj_restricted" value="restricted" @if(isset($preanaethestic)) @if($preanaethestic->has_pregnancy == 'restricted') checked @endif @endif>
                                        <label class="custom-control-label" for="customCheck2">Restricted</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 form-group pl-4">
                                <label>Neck Mobility</label>
                                <div class="">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" name="neck_mobility" class="custom-control-input" id="neck_mobility_free" value="free" @if(isset($preanaethestic)) @if($preanaethestic->has_pregnancy == 'free') checked @endif @endif>
                                        <label class="custom-control-label" for="customCheck2">Free</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" name="neck_mobility" class="custom-control-input" id="neck_mobility_restricted" value="restricted" @if(isset($preanaethestic)) @if($preanaethestic->has_pregnancy == 'restricted') checked @endif @endif>
                                        <label class="custom-control-label" for="customCheck2">Restricted</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 form-group pl-4">
                                <label>Mallampati Grading</label>
                                <div class="">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" name="neck_mobility_two" class="custom-control-input" id="neck_mobility_two_one" value="1" @if(isset($preanaethestic)) @if($preanaethestic->has_pregnancy == 1) checked @endif @endif>
                                        <label class="custom-control-label" for="customCheck2">(I)</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" name="neck_mobility_two" class="custom-control-input" id="neck_mobility_two_two" value="2" @if(isset($preanaethestic)) @if($preanaethestic->has_pregnancy == 2) checked @endif @endif>
                                        <label class="custom-control-label" for="customCheck2">(II)</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" name="neck_mobility_two" class="custom-control-input" id="neck_mobility_two_three" value="3" @if(isset($preanaethestic)) @if($preanaethestic->has_pregnancy == 3) checked @endif @endif>
                                        <label class="custom-control-label" for="customCheck2">(III)</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" name="neck_mobility_two" class="custom-control-input" id="neck_mobility_two_four" value="4" @if(isset($preanaethestic)) @if($preanaethestic->has_pregnancy == 4) checked @endif @endif>
                                        <label class="custom-control-label" for="customCheck2">(IV)</label>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-3 form-group">
                                <label>Thyromental Distance(in CM)</label>
                                <input type="text" placeholder="" class="form-control" name="thyromental_distance" value="{{ isset($preanaethestic) ? $preanaethestic->thyromental_distance :'' }}" id="thyromental_distance">
                            </div>
                        </div>
                        <!-- Lungs-->
                        <div class="row form-group">
                            <div class="pl-3">
                                <label for=""> <b>10) Lungs:</b></label>
                            </div>
                            <div class="col-md-4">
                                <div class="custom-control custom-checkbox custom-control-inline" id="rt_lung_check">
                                    <input type="checkbox" class="custom-control-input" name="rt_lung_check">
                                    <label class="custom-control-label">RT Lungs</label>
                                </div>

                                <div class="custom-control custom-checkbox custom-control-inline" id="lt_lung_check">
                                    <input type="checkbox" class="custom-control-input" name="lt_lung_check" >
                                    <label class="custom-control-label">LT Lungs</label>
                                </div>
                            </div>
                        </div>
                        <div class="row rtdiv" style="display:none;" id="rt_div">
                            <div class="col-md-3 form-group">
                                <label>Air Entry</label>
                                <input type="text" placeholder="" class="form-control" name="rt_air_entry" value="{{ isset($preanaethestic) ? $preanaethestic->rt_air_entry :'' }}" id="rt_air_entry">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Rales</label>
                                <input type="text" placeholder="" class="form-control" name="rt_rales" value="{{ isset($preanaethestic) ? $preanaethestic->rt_rales :'' }}" id="rt_rales">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Ronchi</label>
                                <input type="text" placeholder="" class="form-control" name="rt_ronchi" value="{{ isset($preanaethestic) ? $preanaethestic->rt_ronchi :'' }}" id="rt_ronchi">
                            </div>

                            <div class="col-md-3 form-group">
                                <label>Wheeze</label>
                                <input type="text" placeholder="" class="form-control" name="rt_wheeze" value="{{ isset($preanaethestic) ? $preanaethestic->rt_wheeze :'' }}" id="rt_wheeze">
                            </div>
                        </div>
                        <div class="row lt-div" style="display:none;" id="lt_div">
                            <div class="col-md-3 form-group">
                                <label>Air Entry</label>
                                <input type="text" placeholder="" class="form-control" name="lt_air_entry" value="{{ isset($preanaethestic) ? $preanaethestic->lt_air_entry :'' }}" id="lt_air_entry">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Rales</label>
                                <input type="text" placeholder="" class="form-control" name="lt_rales" value="{{ isset($preanaethestic) ? $preanaethestic->lt_rales :'' }}" id="lt_rales">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Ronchi</label>
                                <input type="text" placeholder="" class="form-control" name="lt_ronchi" value="{{ isset($preanaethestic) ? $preanaethestic->lt_ronchi :'' }}" id="lt_ronchi">
                            </div>

                            <div class="col-md-3 form-group">
                                <label>Wheeze</label>
                                <input type="text" placeholder="" class="form-control" name="lt_wheeze" value="{{ isset($preanaethestic) ? $preanaethestic->lt_wheeze :'' }}" id="lt_wheeze">
                            </div>

                        </div>

                        <!-- Heart-->
                        <div class="row ">
                            <div class="col-md-12"><b>11) Heart</b></div>
                            <div class="col-md-3 form-group">
                                <label>Rate</label>
                                <input type="text" placeholder="" class="form-control" name="rate" value="{{ isset($preanaethestic) ? $preanaethestic->rate :'' }}" id="rate">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Rhythm</label>
                                <input type="text" placeholder="" class="form-control" name="rhythm" value="{{ isset($preanaethestic) ? $preanaethestic->rhythm :'' }}" id="rhythm">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Additional Sound</label>
                                <input type="text" placeholder="" class="form-control" name="additional_sound" value="{{ isset($preanaethestic) ? $preanaethestic->additional_sound :'' }}" id="additional_sound">
                            </div>

                        </div>

                        <!-- Laboratory-->
                        <div class="row ">
                            <div class="col-md-12"><b>12) Laboratory Investigation</b></div>

                            <div class="col-md-3 form-group">
                                <label>Heamoglobin</label>
                                <input type="text" placeholder="" class="form-control" name="heamoglobin" value="" id="heamoglobin">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>TC/DC</label>
                                <input type="text" placeholder="" class="form-control" name="tc_dc" value="" id="tc_dc">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>P</label>
                                <input type="text" placeholder="" class="form-control" name="p" value="" id="p">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>L</label>
                                <input type="text" placeholder="" class="form-control" name="l" value="" id="l">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>E</label>
                                <input type="text" placeholder="" class="form-control" name="e" value="" id="e">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>M</label>
                                <input type="text" placeholder="" class="form-control" name="m" value="" id="m">
                            </div>

                            <div class="col-md-3 form-group">
                                <label>B</label>
                                <input type="text" placeholder="" class="form-control" name="b" value="" id="b">
                            </div>

                            <div class="col-md-3 form-group">
                                <label>BT/CT</label>
                                <input type="text" placeholder="" class="form-control" name="bt_ct" value="" id="bt_ct">
                            </div>

                            <div class="col-md-3 form-group">
                                <label>PT</label>
                                <input type="text" placeholder="" class="form-control" name="pt" value="" id="pt">
                            </div>

                            <div class="col-md-3 form-group">
                                <label>Toal Protein</label>
                                <input type="text" placeholder="" class="form-control" name="toal_protien" value="" id="toal_protien">
                            </div>

                            <div class="col-md-3 form-group pl-4">
                                <label>Billiribin</label>
                                <div class="">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" name="billiribin" class="custom-control-input" id="billiribin_t" value="t">
                                        <label class="custom-control-label" for="customCheck2">T</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" name="billiribin" class="custom-control-input" id="billiribin_d" value="d">
                                        <label class="custom-control-label" for="customCheck2">D</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Urine Analysis</label>
                                <input type="text" placeholder="" class="form-control" name="urine_analysis" value="" id="urine_analysis">
                            </div>

                            <div class="col-md-3 form-group">
                                <label>Electrolytes Na</label>
                                <input type="text" placeholder="" class="form-control" name="elctorlytes" value="" id="elctorlytes">
                            </div>

                            <div class="col-md-3 form-group">
                                <label>K</label>
                                <input type="text" placeholder="" class="form-control" name="k" value="" id="k">
                            </div>

                            <div class="col-md-3 form-group">
                                <label>Blood Urea</label>
                                <input type="text" placeholder="" class="form-control" name="blood_urea" value="" id="blood_urea">
                            </div>

                            <div class="col-md-3 form-group">
                                <label>Creatioin</label>
                                <input type="text" placeholder="" class="form-control" name="creatioin" value="" id="creatioin">
                            </div>

                            <div class="col-md-3 form-group pl-4">
                                <label>Blood Sugar</label>
                                <div class="">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" name="blood_sugar" class="custom-control-input" id="billiribin_f" value="f">
                                        <label class="custom-control-label" for="customCheck2">F</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" name="blood_sugar" class="custom-control-input" id="billiribin_pp" value="pp">
                                        <label class="custom-control-label" for="customCheck2">PP</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" name="blood_sugar" class="custom-control-input" id="billiribin_r" value="r">
                                        <label class="custom-control-label" for="customCheck2">R</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 form-group">
                                <label>Blood Group</label>
                                <select name="blood_group" class="form-control" id="blood_group">
                                    <option value="A+">A+</option>
                                    <option value="A-">A-</option>
                                    <option value="B+">B+</option>
                                    <option value="B-">B-</option>
                                    <option value="O+">O+</option>
                                    <option value="O-">O-</option>
                                    <option value="AB+">AB+</option>
                                    <option value="AB-">AB-</option>
                                </select>
                            </div>

                            <div class="col-md-3 form-group">
                                <label>RH.Factor</label>
                                <input type="text" placeholder="" class="form-control" name="rh_factor" value="" id="rh_factor">
                            </div>

                            <div class="col-md-3 form-group">
                                <label>Unit of cross-matched blood available</label>
                                <input type="text" placeholder="" class="form-control" name="cross_matched_blood" value="" id="cross_matched_blood">
                            </div>

                            <div class="col-md-3 form-group pl-4">
                                <label>X-ray</label>
                                <div class="">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" name="x_ray" class="custom-control-input" id="x_ray_yes" value="1">
                                        <label class="custom-control-label" for="customCheck2">Yes</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" name="x_ray" class="custom-control-input" id="x_ray_no" value="0">
                                        <label class="custom-control-label" for="customCheck2">No</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Findings</label>
                                <input type="text" placeholder="" class="form-control" name="x_ray_findings" value="" id="x_ray_findings">
                            </div>

                            <div class="col-md-3 form-group pl-4">
                                <label>ECG</label>
                                <div class="">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" name="ecg" class="custom-control-input" id="ecg_yes" value="1">
                                        <label class="custom-control-label" for="customCheck2">Yes</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" name="ecg" class="custom-control-input" id="ecg_no" value="0">
                                        <label class="custom-control-label" for="customCheck2">No</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Findings</label>
                                <input type="text" placeholder="" class="form-control" name="ecg_finding" value="" id="ecg_finding">
                            </div>

                            <div class="col-md-3 form-group pl-4">
                                <label>Echocardiograohy</label>
                                <div class="">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" name="echocardiograohy" class="custom-control-input" id="echocardiograohy_yes" value="1">
                                        <label class="custom-control-label" for="customCheck2">Yes</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" name="echocardiograohy" class="custom-control-input" id="echocardiograohy_no" value="0">
                                        <label class="custom-control-label" for="customCheck2">No</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Findings</label>
                                <input type="text" placeholder="" class="form-control" name="echocardiograohy_finding" value="" id="echocardiograohy_finding">
                            </div>

                            <div class="col-md-4 form-group pl-4">
                                <label>ASA Grading </label>
                                <div class="">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" name="asa_grading" id="asa_grading_one" value="1" class="custom-control-input">
                                        <label class="custom-control-label" for="customCheck2">(I)</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" name="asa_grading" id="asa_grading_two" value="2" class="custom-control-input">
                                        <label class="custom-control-label" for="customCheck2">(II)</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" name="asa_grading" id="asa_grading_three" value="3" class="custom-control-input">
                                        <label class="custom-control-label" for="customCheck2">(III)</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" name="asa_grading" id="asa_grading_four" value="4" class="custom-control-input">
                                        <label class="custom-control-label" for="customCheck2">(IV)</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" name="asa_grading" id="asa_grading_five" value="5" class="custom-control-input">
                                        <label class="custom-control-label" for="customCheck2">(V)</label>
                                    </div>
                                </div>
                            </div>


                        </div>

                        <input type="button" name="submit" id="save_preanaesthic_evaluation" class="btn btn-primary btn-action float-right" value="Submit" >

                    </div>

                </div>
            </div>
        </div>

    </form>
</div>
<script>
    $('#save_preanaesthic_evaluation').click( function () {
        var encounter_id = $('#encounter_id').val();
        if(encounter_id == '0'){
            alert('Choose Encounter');
            return false;
        }
        var formData = new FormData($('#anaethestic_form')[0]);
        formData.append("encounter_id", encounter_id);
        var object = {};
        formData.forEach(function(value, key){
            object[key] = value;
        });

        if(Object.keys(object).length != 58){
            alert("Please fill all the data!");
            return false;
        }
        $.ajax({
            url:"{{ route('inpatient.save.preanaethestic.evaluation') }}",
            method:"POST",
            data: formData,
            contentType: false,
            cache:false,
            processData: false,
            dataType:"json",
            success:function(data){
                if(data.message){
                    showAlert(data.message);
                }else{
                    showAlert("Something went wrong!!", 'Error');
                    return false;
                }
            }
        });

    });

    $('#rt_lung_check').on('click', function() {
        setTimeout(() => {
            if($('input[type="checkbox"][name="rt_lung_check"]').is(':checked'))
                $("#rt_div").show();
            else
                $("#rt_div").hide();
        }, 100);
    });
    $('#lt_lung_check').on('click', function() {
        setTimeout(() => {
            if($('input[type="checkbox"][name="lt_lung_check"]').is(':checked'))
                $("#lt_div").show();
            else
                $("#lt_div").hide();
        }, 100);
    });
</script>
