@extends('frontend.layouts.master')
@push('after-styles')
    <style>
        /* ul {
            list-style-type: none;
            display: contents;
        } */

        /* #top-tab-list li {
            width:33%;
        } */

        /* .tab-list__item{
            width:33%;
            list-style-type: none;
            font-size: 18px;
            float: left;
            text-align: left;
            position: relative;
            font-weight: 400;
        } */

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
    </style>
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <!-- <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">OT</h4>
                        </div>
                    </div> -->
                    <div class="iq-card-body">
                        <!--start form wizard-->
                        <div class="container text-center mt-4">
                            <ul class="tab-list  nav nav-pills ">

                                <li class="tab-list__item">
                                    <a class="tab-list__link" href="#home" data-toggle="tab" aria-expanded="true">
                                        <span class="step"><i class="ri-login-box-line"></i></span>
                                        <span class="desc">Sign In</span>
                                    </a>
                                </li>
                                <li class="tab-list__item">
                                    <a class="tab-list__link" href="#menu1" data-toggle="tab" aria-expanded="false">
                                        <span class="step"><i class="ri-close-circle-fill"></i></span>
                                        <span class="desc">Time Out</span>
                                    </a>
                                </li>
                                <li class="tab-list__item">
                                    <a class="tab-list__link" href="#menu2" data-toggle="tab" aria-expanded="false">
                                        <span class="step"><i class="ri-logout-box-fill"></i></span>
                                        <span class="desc">Sign Out</span>
                                    </a>
                                </li>
                               
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div id="home" class="tab-pane active"><br>
                                    <div class="form-card text-left">
                                        <div class="row">
                                            <div class="col-7">
                                                <h3 class="mb-4">Sign In Checklist:</h3>
                                            </div>
                                            <div class="col-5">
                                                <h2 class="steps">Step 1 - 4</h2>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" id="customCheck1">
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
                                                    <div class="custom-control custom-checkbox custom-checkbox-color">
                                                        <input type="checkbox" class="custom-control-input" id="customCheck2">
                                                        <label class="custom-control-label" for="customCheck2"><b>Site marked/Not applicable</b></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" id="customCheck2">
                                                        <label class="custom-control-label" for="customCheck2"><b>Anesthesia safety check completed</b></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" id="customCheck2">
                                                        <label class="custom-control-label" for="customCheck2"><b>Pulse oximeter on paitent and functioning</b></label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12 mt-2">

                                                <label class="ml-4"><h5>Does patient have a :</h5></label>
                                                <div class="form-group">
                                                    <label class="ml-4 mt-2"><b>Known Allergy?</b></label>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" class="custom-control-input" name="allergy" id="customCheck2">
                                                        <label class="custom-control-label" for="customCheck2">No</label>
                                                    </div>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" class="custom-control-input" id="customCheck2" name="allergy">
                                                        <label class="custom-control-label" for="customCheck2">Yes</label>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="ml-4 mt-2"><b>Difficult airway/aspiration risk?</b></label>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" name="aspiration" class="custom-control-input" id="customCheck2">
                                                        <label class="custom-control-label" for="customCheck2">No</label>
                                                    </div>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" name="aspiration" class="custom-control-input" id="customCheck2">
                                                        <label class="custom-control-label" for="customCheck2">Yes, and equipped/assistance available</label>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="ml-4 mt-2"><b>Risk of > 500Ml Blood Loss(7ML/KG in children)?</b></label>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" name="blood" class="custom-control-input" id="customCheck2">
                                                        <label class="custom-control-label" for="customCheck2">No</label>
                                                    </div>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" name="blood" class="custom-control-input" id="customCheck2">
                                                        <label class="custom-control-label" for="customCheck2">Yes,and adequate intravenous access and fluids planned.</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" name="next" class="btn btn-primary next btn-xl btn-action float-right" value="Next">Next</button>
                                </div>
                                <div id="menu1" class="tab-pane fade"><br>
                                    <div class="form-card text-left ">
                                        <div class="row">
                                            <div class="col-7">
                                                <h3 class="mb-4">Time Out Checklist</h3>
                                            </div>
                                            <div class="col-5">
                                                <h2 class="steps">Step 2 - 4</h2>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" id="customCheck2">
                                                        <label class="custom-control-label" for="customCheck2"><b>Confirm all team members have introduced themeselves by name and role.</b>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" id="customCheck1">
                                                        <label class="custom-control-label" for="customCheck1"><b>Surgeon, Anesthesia Professional and nurse verbally confirm</b></label>
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
                                                        <input type="checkbox" class="custom-control-input" id="customCheck2">
                                                        <label class="custom-control-label" for="customCheck2"><b>Surgeons Regviews:</b> What are the critical or unexpected steps,operative duration,anticipated blood loss?</label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" id="customCheck2">
                                                        <label class="custom-control-label" for="customCheck2"><b>Anesthesia Team Reviews:</b> Are there any patient-specific concerns?</label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" id="customCheck2">
                                                        <label class="custom-control-label" for="customCheck2"><b>Nurshing Team Reviews:</b> Has sterility (including indicators results) been confirmed? Are there equipment issues or any concerns?</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="ml-4 mt-2"><b>Has antibiotic prophylaxis been given within the last 60 minutes?</b></label>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" name="aspiration" class="custom-control-input" id="customCheck2">
                                                        <label class="custom-control-label" for="customCheck2">Yes</label>
                                                    </div>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" name="aspiration" class="custom-control-input" id="customCheck2">
                                                        <label class="custom-control-label" for="customCheck2">Not applicable</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="ml-4 mt-2"><b>Is essential imaging displayed?</b></label>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" name="aspiration" class="custom-control-input" id="customCheck2">
                                                        <label class="custom-control-label" for="customCheck2">Yes</label>
                                                    </div>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" name="aspiration" class="custom-control-input" id="customCheck2">
                                                        <label class="custom-control-label" for="customCheck2">Not applicable</label>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <button type="button" name="next" class="btn btn-primary next action-button btn-action float-right" value="Next">Next</button>
                                    <button type="button" name="previous" class="btn btn-dark previous action-button-previous btn-action float-right mr-3" value="Previous">Previous</button>
                                </div>
                                <div id="menu2" class="tab-pane fade"><br>
                                    <div class="form-card text-left">
                                        <div class="row">
                                            <div class="col-7">
                                                <h3 class="mb-4">Sign Out Checklist:</h3>
                                            </div>
                                            <div class="col-5">
                                                <h2 class="steps">Step 3 - 4</h2>
                                            </div>
                                        </div>
                                        <div class="my-3 ml-4"><h4>Nurse verbally confirms with the team:</h4></div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="customCheck2">
                                                <label class="custom-control-label" for="customCheck2"><b>The name of the procedure recorded.</b></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="customCheck2">
                                                <label class="custom-control-label" for="customCheck2"><b>That instrument,sponge and needle counts are correct</b> (Or not applicable)</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="customCheck2">
                                                <label class="custom-control-label" for="customCheck2"><b>How the speciment is labelled</b> (Including patient name)</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="customCheck2">
                                                <label class="custom-control-label" for="customCheck2"><b>Whether there are any equipment problmes to be addressed</b></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="customCheck2">
                                                <label class="custom-control-label" for="customCheck2"><b>Surgeons, Anesthesia professional and nurse review the key concerns for recovery and management of this patient</b></label>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" name="next" class="btn btn-primary next action-button float-right" value="Submit">Submit</button>
                                    <button type="button" name="previous" class="btn btn-dark previous action-button-previous float-right mr-3" value="Previous">Previous</button>
                                </div>
                               
                            </div>

                        </div>
                        <!--end wizard-->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('after-script')
    <style>
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
            padding: 25px 0;
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
