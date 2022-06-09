@extends('inpatient::pdf.layout.main')

@section('title')
    Pre- Anaesthetic Evaluation Report
@endsection

@section('report_type')
    Pre- Anaesthetic Evaluation Report
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
            padding-bottom: 80px;
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

        .bottom-container {
            position: absolute;
            bottom: 8px;
        }

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
    </style>
    <hr>
    <div class="checklist-container">
        <form id="anaethestic_form">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="mt-2"><b>1) Have you ever had anaesthetic in the past ?</b></label>
                    </div>

                    <div class="row ml-2">
                        <div class="col-md-4 form-group">
                            <label>Year : {{ isset($preanaethestic) ?  \App\Utils\Helpers::dateNepToEng($preanaethestic->past_anaesthetic_date)->full_date : '' }}</label>

                        </div>

                        <div class="col-md-4 form-group">
                            <label>Surgical Procedure:
                                <b> {{ isset($preanaethestic) ? strtoupper($preanaethestic->surgerical_procedure) :'' }}</b></label>

                        </div>

                        <div class="col-md-4 form-group">
                            <label>Hospital Name :
                                <b> {{ isset($preanaethestic) ? strtoupper($preanaethestic->hospital_name) :'' }} </b>
                            </label>

                        </div>

                        <div class="col-md-4 form-group">
                            <label>Types of Anesthesia(GA/RA/Local):
                                <b> {{ isset($preanaethestic) ? strtoupper($preanaethestic->anesthesia_type) :'' }} </b></label>

                        </div>
                    </div>

                    <div class="form-group">
                        <label class="mt-2"><b>2. (a) Have your any member of your family had reaction to an
                                anaesthetic? :
                                <b>{{ isset($preanaethestic) ? strtoupper($preanaethestic->reaction == 0 ? 'No' : 'Yes') :'' }} </b></b>
                        </label>
                    </div>

                    <div class="form-group ml-3">
                        <label class="mt-2"><b>(b) Has any member of your family had reaction an anaesthestic? :
                                <b>{{ isset($preanaethestic) ? strtoupper($preanaethestic->has_family_reaction == 0 ? 'No' : 'Yes') :'' }}</b></b></label>
                    </div>

                    <div class="form-group ">
                        <label class="mt-2"><b>3) Do you smoke ? :
                                <b>{{ isset($preanaethestic) ? strtoupper($preanaethestic->smoke == 0 ? 'No' : 'Yes') :'' }}</b></b></label>
                    </div>

                    <div class="form-group ">
                        <label class="mt-2"><b>4) Are you having cough or cold at present ?
                                :<b>{{ isset($preanaethestic) ? strtoupper($preanaethestic->have_cough == 0 ? 'No' : 'Yes') :'' }}</b></b></label>
                    </div>

                    <div class="form-group ">
                        <label class="mt-2"><b>5) Do you take any medicine regularly ?
                                :<b>{{ isset($preanaethestic) ? strtoupper($preanaethestic->regular_medicine == 0 ? 'No' : 'Yes') :'' }}</b></b></label>
                    </div>

                    <div class="form-group ">
                        <label class="mt-2"><b>6) Are you, or have you been a drug user (alcohol, street drug or related
                                drigs)?
                                :<b>{{ isset($preanaethestic) ? strtoupper($preanaethestic->drug_user == 0 ? 'No' : 'Yes') :'' }}</b></b></label>
                    </div>

                    <div class="form-group ">
                        <label class="mt-2"><b>7) Do you have any allergies ?
                                :<b>{{ isset($preanaethestic) ? strtoupper($preanaethestic->have_allergies == 0 ? 'No' : 'Yes') :'' }}</b></b></label>
                    </div>

                    <div class="form-group ">
                        <label class="mt-2"><b>8) Do you have loose teeth, capped teeth, caries, dentures or related
                                drugs?
                                :<b>{{ isset($preanaethestic) ? strtoupper($preanaethestic->loose_teeth == 0 ? 'No' : 'Yes') :'' }}</b></b></label>
                    </div>

                    <div class="form-group ">
                        <label class="mt-2"><b>9) Woman only : Are you Pregnant?
                                :<b>{{ isset($preanaethestic) ? strtoupper($preanaethestic->has_pregnancy == 0 ? 'No' : 'Yes') :'' }}</b></b></label>
                    </div>
                    <hr>
                    <br>

                    <div class="form-group ">
                        <label class="col-md-12"><b>Physical Examination</b></label>
                        <hr>
                    </div>

                    <div class="form-group ">
                        <label class="col-md-12"><b>Pulse: ...Min, Reg/irreg.&nbsp;&nbsp;&nbsp;
                                Temp:.................... &nbsp;&nbsp;&nbsp;&nbsp;Blood Pressure: ............... mm of
                                Hg Peripheral Veins
                                : {{ isset($preanaethestic) ? strtoupper($preanaethestic->physical_examination == 'good' ? 'Good' : 'Difficult') :'' }}
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                                TMJ: {{ isset($preanaethestic) ? strtoupper($preanaethestic->tmj_free == 'free' ? 'Free' : 'Restricted') :'' }}
                                &nbsp;&nbsp;&nbsp;Neck
                                Mobility: {{ isset($preanaethestic) ? strtoupper($preanaethestic->neck_mobility == 'free' ? 'Free' : 'Restricted') :'' }}
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                Teeth: {{ isset($preanaethestic) ? strtoupper($preanaethestic->loose_teeth == 0 ? 'No' : 'Yes') :'' }}
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                @php
                                $malampatti ='';
                                if( isset($preanaethestic) && $preanaethestic->neck_mobility_two == 1){
                                     $malampatti ='I';
                                }elseif (isset($preanaethestic) && $preanaethestic->neck_mobility_two == 2){
                                    $malampatti ='II';
                                }elseif (isset($preanaethestic) && $preanaethestic->neck_mobility_two == 3){
                                    $malampatti ='III';
                                }elseif (isset($preanaethestic) && $preanaethestic->neck_mobility_two == 4){
                                    $malampatti ='IV';
                                }else{
                                     $malampatti ='';
                                }
                                @endphp
                                Mallampati Grading: {{ $malampatti }}

                                &nbsp;&nbsp;&nbsp;&nbsp; Thyromental
                                Distance: {{ isset($preanaethestic) ? strtoupper($preanaethestic->thyromental_distance) :'' }}
                                &nbsp;&nbsp;&nbsp;(CM)</b></label>
                    </div>
                    <hr>
                    <div class="form-group ">
                        <label class="col-md-12"><b> Rt Lung:Heart</b></label>
                        <ol type="1">
                            <li> Rate: {{ isset($preanaethestic) ? strtoupper($preanaethestic->rate) :'' }} </li>
                            <li> Rhythm: {{ isset($preanaethestic) ? strtoupper($preanaethestic->rhythm) :'' }} </li>
                            <li>Additional
                                Sound: {{ isset($preanaethestic) ? strtoupper($preanaethestic->additional_sound) :'' }} </li>
                        </ol>

                    </div>

                    <div class="form-group ">
                        <label class="col-md-12"><b> Lt Lung</b></label>
                        <ol type="1">
                            <li> Air
                                Entry: {{ isset($preanaethestic) ? strtoupper($preanaethestic->air_entry) :'' }} </li>
                            <li> Rales: {{ isset($preanaethestic) ? strtoupper($preanaethestic->rales) :'' }} </li>
                            <li>Ronchi: {{ isset($preanaethestic) ? strtoupper($preanaethestic->ronchi) :'' }}  </li>
                            <li>Wheeze:{{ isset($preanaethestic) ? strtoupper($preanaethestic->wheeze) :'' }}   </li>
                        </ol>
                    </div>

                    <div class="form-group ">
                        <label class="col-md-12"><b>Laboratory Investigations</b></label>
                        <hr>
                    </div>
                    <div class="form-group ">
                        <label class="col-md-12">Heamoglobin: {{ isset($preanaethestic) ? strtoupper($preanaethestic->heamoglobin) :'' }}
                            &nbsp;&nbsp;&nbsp; g/100 ML,
                            TC/DC.{{ isset($preanaethestic) ? strtoupper($preanaethestic->tc_dc) :'' }} &nbsp;&nbsp;&nbsp;
                            P: {{ isset($preanaethestic) ? strtoupper($preanaethestic->p) :'' }} &nbsp;&nbsp;&nbsp;&nbsp;L: {{ isset($preanaethestic) ? strtoupper($preanaethestic->l) :'' }}
                            E: {{ isset($preanaethestic) ? strtoupper($preanaethestic->e) :'' }} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                            M: {{ isset($preanaethestic) ? strtoupper($preanaethestic->wheeze) :'' }} &nbsp;&nbsp;&nbsp;B: {{ isset($preanaethestic) ? strtoupper($preanaethestic->wheeze) :'' }}
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            BT/CT: {{ isset($preanaethestic) ? strtoupper($preanaethestic->wheeze) :'' }} &nbsp;&nbsp;&nbsp;&nbsp;
                            PT: {{ isset($preanaethestic) ? strtoupper($preanaethestic->wheeze) :'' }} &nbsp;&nbsp;&nbsp;&nbsp;
                            Toal Protein: {{ isset($preanaethestic) ? strtoupper($preanaethestic->wheeze) :'' }} &nbsp;&nbsp;&nbsp;
                            Billiribin: {{ isset($preanaethestic) ? strtoupper($preanaethestic->billiribin == 't' ? 'T' : 'D') :'' }}
                            &nbsp;&nbsp;&nbsp; Urine
                            Analysis: {{ isset($preanaethestic) ? strtoupper($preanaethestic->urine_analysis) :'' }}
                            &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; Electrolytes
                            Na: {{ isset($preanaethestic) ? strtoupper($preanaethestic->elctorlytes) :'' }} &nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;
                            K: {{ isset($preanaethestic) ? strtoupper($preanaethestic->wheeze) :'' }} &nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp; Blood Urea: &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; Creatioin: &nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp; Blood Sugar: &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; Blood Group: &nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp; RH.Factor: &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
                            Unit of cross-matched blood
                            available: {{ isset($preanaethestic) ? strtoupper($preanaethestic->cross_matched_blood) :'' }}
                            &nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;X-ray: {{ isset($preanaethestic) ? strtoupper($preanaethestic->x_ray ==1 ? 'Yes' :'No') :'' }}
                            &nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;Findings: {{ isset($preanaethestic) ? strtoupper($preanaethestic->x_ray_findings) :'' }}
                            &nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;ECG: {{ isset($preanaethestic) ? strtoupper($preanaethestic->ecg==1 ? 'Yes' : 'No') :'' }}
                            &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
                            Findings: {{ isset($preanaethestic) ? strtoupper($preanaethestic->ecg_finding) :'' }} &nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;
                            Echocardiograohy: {{ isset($preanaethestic) ? strtoupper($preanaethestic->echocardiograohy ==1 ? 'Yes' : 'No') :'' }}
                            &nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;Findings: {{ isset($preanaethestic) ? strtoupper($preanaethestic->echocardiograohy_finding) :'' }}
                            &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;

                            @php
                                $asa ='';
                                if( isset($preanaethestic) && $preanaethestic->asa_grading == 1){
                                     $asa ='I';
                                }elseif (isset($preanaethestic) && $preanaethestic->asa_grading == 2){
                                    $asa ='II';
                                }elseif (isset($preanaethestic) && $preanaethestic->asa_grading == 3){
                                    $asa ='III';
                                }elseif (isset($preanaethestic) && $preanaethestic->asa_grading == 4){
                                    $asa ='IV';
                                }elseif (isset($preanaethestic) && $preanaethestic->asa_grading == 5){
                                    $asa ='V';
                                }
                                else{
                                     $asa ='';
                                }
                            @endphp
                            ASA Grading: {{ $asa }}



                            &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;


                        </label>
                    </div>
                    <hr>
                    <div class="form-group ">
                        <label class="col-md-12"><b>Signature of Anaesthesiologos: &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
                            </b></label>
                        <hr>
                    </div>


                </div>

            </div>
        </form>
    </div>

@endsection

@push('after-script')
    <script src="{{asset('assets/js/jquery-3.4.1.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('#anaethestic_form').unbind("click");
            $('#anaethestic_form').css("pointer-events", "none");
        });
    </script>
@endpush
