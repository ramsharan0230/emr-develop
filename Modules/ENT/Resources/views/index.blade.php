@extends('frontend.layouts.master')
@push('after-styles')
<style>

    .ent-img {
        display: block;
        position: relative;
    }

    .width-long {
        width: 18%;
    }

    .width-long2 {
        width: 10%;
    }

    .custom-col-audiogram {
        flex: 0 0 25%;
        max-width: 25%;
        position: relative;
        width: 100%;
        padding-right: 10px;
        padding-left: 10px;
    }

    .canvas__img {
        border: 2px solid;
        position: absolute;
        left: 5px;
    }

    .img-audiogram-ear img {
        width:90%;
        /*left: 16px;
        top: 54px;*/
    }

    .img-audiogram-nose img {
        position: absolute;
        width: 90%;
        /*top: 43px;*/
    }

    .img-audiogram-throat img {
        position: absolute;
        width: 90%;
        /*left: 20px;*/
    }

    .img-audiogram-tongue img {
        position: absolute;
        width: 90%;
        /*left: 56px;
        top: 36px;*/
    }

    #history-modal.modal .modal-dialog {
        width: 100%;
        max-width: none;
        height: 100%;
    }

    #history-modal.modal .modal-content {
        height: 100%;
    }

    #history-modal.modal .modal-body {
        overflow-y: auto;
    }

    #history-modal.modal .iq-card-body{
        max-height: 500px;
        overflow: auto;
    }
</style>
@endpush
@section('content')
@php
        $disableClass = (isset($patient_status_disabled) && $patient_status_disabled == 1) ? 'disableInsertUpdate' : '';


    @endphp
@include('menu::common.ent-nav-bar')
<div class="container-fluid">
    <div class="row">
        @include('frontend.common.patientProfile')
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <div id="accordion">
                        <div class="accordion-nav">
                            <ul>
                                <li><a href="#" data-toggle="collapse" data-target="#chief-complaint" aria-expanded="true" aria-controls="collapseOne">Chief Complaints</a></li>
                                <li><a href="#" data-toggle="collapse" data-target="#systemic-illness" aria-expanded="false" aria-controls="collapseOne">Systemic Illness</a></li>
                                <li><a href="#" data-toggle="collapse" data-target="#allergy" aria-expanded="false" aria-controls="collapseOne">Allergy</a></li>
                                <li><a href="#" data-toggle="collapse" data-target="#current-medication" aria-expanded="false" aria-controls="collapseOne">Current Medication</a></li>
                                <li><a href="#" data-toggle="collapse" data-target="#history" aria-expanded="false" aria-controls="collapseOne">History</a></li>
                                <li><a href="#" data-toggle="collapse" data-target="#on-examination" aria-expanded="false" aria-controls="collapseOne">On Examination</a></li>
                                <li><a href="#" data-toggle="collapse" data-target="#procedure" aria-expanded="false" aria-controls="collapseOne">Procedure</a></li>
                                <li><a id="audiogramReqTab" href="#" data-toggle="collapse" data-target="#audiogram-req" aria-expanded="false" aria-controls="collapseOne">Audiogram Request</a></li>
                                <li><a id="audiogramTab" href="#" data-toggle="collapse" data-target="#audiogram" aria-expanded="false" aria-controls="collapseOne">Audiogram</a></li>

                            </ul>
                        </div>
                        @include('ent::modal.chiefComplaints')
                        <div id="systemic-illness" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="iq-card-header d-flex justify-content-between">
                                <div class="iq-header-title">
                                    <h4 class="card-title">Systemic Illness</h4>
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <form method="post" class="js-ent-ajax-form" action="{{ route('ent.examgeneral') }}">
                                    <div class="form-group">
                                        <textarea id="js-systematic-illness-ck-textarea" name="Systemic_Illiness" class="ck-ent">{{ isset($exam['otherData']['systemic_illiness']) ? $exam['otherData']['systemic_illiness'] : ''}}</textarea>
                                        <div class="col-md-12 mt-2 mb-3 text-center">
                                            <button class="js-ent-ajax-save-btn btn btn-primary"><i class="fas fa-check"></i>&nbsp;&nbsp;Save</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @include('ent::modal.allergy')
                        <div id="current-medication" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="iq-card-header d-flex justify-content-between">
                                <div class="iq-header-title">
                                    <h4 class="card-title">Current Medication</h4>
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <form method="post" class="js-ent-ajax-form" action="{{ route('ent.examgeneral') }}">
                                    <div class="form-group">
                                        <textarea id="js-current-medication-ck-textarea" name="Current_Medication" class="ck-ent">{{ isset($exam['otherData']['current_medication']) ? $exam['otherData']['current_medication'] : ''}}</textarea>
                                    </div>
                                    <div class="col-md-12 text-center entbtn">
                                        <button class="js-ent-ajax-save-btn btn btn-primary"><i class="fas fa-check"></i>&nbsp;&nbsp;Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div id="history" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="iq-card-header d-flex justify-content-between">
                                <div class="iq-header-title">
                                    <h4 class="card-title">History</h4>
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <form method="post" class="js-ent-ajax-form form-row" action="{{ route('ent.examgeneral') }}">
                                    <div class="col-sm-6">
                                        <label for="">PAST</label>
                                        <textarea name="History_Past" id="js-history-past" class="td-input">{{ isset($exam['otherData']['history_past']) ? $exam['otherData']['history_past'] : '' }}</textarea>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="">FAMILY</label>
                                        <textarea name="History_Family" id="js-history-family" class="td-input">{{ isset($exam['otherData']['history_family']) ? $exam['otherData']['history_family'] : '' }}</textarea>
                                    </div>
                                    <div class="col-sm-12 text-center">
                                        <button class="js-ent-ajax-save-btn btn btn-primary  mt-3"><i class="fas fa-check"></i>&nbsp;&nbsp;Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div id="on-examination" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="iq-card-header d-flex justify-content-between">
                                <div class="iq-header-title">
                                    <h4 class="card-title">On Examination</h4>
                                </div>
                            </div>
                            <div class="form-group ">
                                <form method="post" class="js-ent-ajax-form form-row" action="{{ route('ent.examgeneral') }}">
                                    <div class="col-sm-6">
                                        <label for="">RIGHT</label>
                                        <textarea name="On_Examination_Right" id="js-onexam-right" class="td-input">{{ isset($exam['otherData']['on_examination_right']) ? $exam['otherData']['on_examination_right'] : '' }}</textarea>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="">LEFT</label>
                                        <textarea name="On_Examination_Left" id="js-onexam-left" class="td-input">{{ isset($exam['otherData']['on_examination_left']) ? $exam['otherData']['on_examination_left'] : '' }}</textarea>
                                    </div>
                                    <div class="col-sm-12 text-center">
                                        <button class="js-ent-ajax-save-btn btn btn-primary"><i class="fas fa-check"></i>&nbsp;&nbsp;Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div id="procedure" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="iq-card-header d-flex justify-content-between">
                                <div class="iq-header-title">
                                    <h4 class="card-title">Procedure</h4>
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <form method="post" class="js-ent-ajax-form" action="{{ route('ent.examgeneral') }}">
                                    <div class="form-group">
                                        <textarea id="js-procedure-ck-textarea" name="Procedure" class="ck-ent">{{ isset($exam['otherData']['procedure']) ? $exam['otherData']['procedure'] : ''}}</textarea>
                                        <div class="col-md-12 mt-2 mb-3 text-center">
                                            <button class="js-ent-ajax-save-btn btn btn-primary"><i class="fas fa-check"></i>&nbsp;&nbsp;Save</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div id="audiogram" class="collapse " aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="mb-2 mt-3">
                                <form action="{{ route('ent.audiogram.save') }}" method="POST" id="saveAudiogramForm">
                                    @csrf
                                    <input type="hidden" name="audiogram_request_id" id="audiogram_request_id"/>
                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                            Request Date: <span id="date_label"></span>
                                        </div>
                                        <div class="col-sm-4">
                                            Request By: <span id="request_by_label"></span>
                                        </div>
                                        <div class="col-sm-4">
                                            Examined Date: <span id="examined_date_label"></span>
                                        </div>
                                         <div class="col-sm-4">
                                            Examined By: <span id="examined_by_label"></span>
                                        </div>
                                         <div class="col-sm-4">
                                           Comments: <span id="comments_label"></span>
                                        </div>
                                         <div class="col-sm-4">
                                            Status: <span id="status_label"></span>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <!-- <p>Request Date: <span id="date_label"></span></p><br>
                                        <p>Request By: <span id="request_by_label"></span></p><br>
                                        <p>Examined Date: <span id="examined_date_label"></span></p><br>
                                        <p>Examined By: <span id="examined_by_label"></span></p><br>
                                        <p>Comments: <span id="comments_label"></span></p><br>
                                        <p>Status: <span id="status_label"></span></p> -->
                                        <table class="table table-bordered">
                                            <thead class="thead-light text-center">
                                                <tr>
                                                    <th colspan="2">Frequency</th>
                                                    <th>125</th>
                                                    <th>250</th>
                                                    <th>500</th>
                                                    <th>750</th>
                                                    <th>1000</th>
                                                    <th>1500</th>
                                                    <th>2000</th>
                                                    <th>3000</th>
                                                    <th>4000</th>
                                                    <th>6000</th>
                                                    <th>8000</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="width-long" rowspan="2">AC, masked if necessary</td>
                                                    <td class="width-long2">Right Ear</td>
                                                    <td><input type="number" name="exam[AC_masked][Right_ear][125]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[AC_masked][Right_ear][250]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[AC_masked][Right_ear][500]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[AC_masked][Right_ear][750]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[AC_masked][Right_ear][1000]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[AC_masked][Right_ear][1500]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[AC_masked][Right_ear][2000]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[AC_masked][Right_ear][3000]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[AC_masked][Right_ear][4000]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[AC_masked][Right_ear][6000]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[AC_masked][Right_ear][8000]" class="td-input" /></td>
                                                </tr>
                                                <tr>
                                                    <td class="width-long2">Left Ear</td>
                                                    <td><input type="number" name="exam[AC_masked][Left_ear][125]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[AC_masked][Left_ear][250]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[AC_masked][Left_ear][500]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[AC_masked][Left_ear][750]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[AC_masked][Left_ear][1000]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[AC_masked][Left_ear][1500]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[AC_masked][Left_ear][2000]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[AC_masked][Left_ear][3000]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[AC_masked][Left_ear][4000]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[AC_masked][Left_ear][6000]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[AC_masked][Left_ear][8000]" class="td-input" /></td>
                                                </tr>
                                                <tr>
                                                    <td class="width-long" rowspan="2">AC, not masked(shadow)</td>
                                                    <td class="width-long2">Right Ear</td>
                                                    <td><input type="number" name="exam[AC_not_masked][Right_ear][125]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[AC_not_masked][Right_ear][250]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[AC_not_masked][Right_ear][500]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[AC_not_masked][Right_ear][750]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[AC_not_masked][Right_ear][1000]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[AC_not_masked][Right_ear][1500]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[AC_not_masked][Right_ear][2000]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[AC_not_masked][Right_ear][3000]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[AC_not_masked][Right_ear][4000]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[AC_not_masked][Right_ear][6000]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[AC_not_masked][Right_ear][8000]" class="td-input" /></td>
                                                </tr>
                                                <tr>
                                                    <td class="width-long2">Left Ear</td>
                                                    <td><input type="number" name="exam[AC_not_masked][Left_ear][125]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[AC_not_masked][Left_ear][250]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[AC_not_masked][Left_ear][500]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[AC_not_masked][Left_ear][750]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[AC_not_masked][Left_ear][1000]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[AC_not_masked][Left_ear][1500]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[AC_not_masked][Left_ear][2000]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[AC_not_masked][Left_ear][3000]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[AC_not_masked][Left_ear][4000]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[AC_not_masked][Left_ear][6000]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[AC_not_masked][Left_ear][8000]" class="td-input" /></td>
                                                </tr>
                                                <tr>
                                                    <td class="width-long" rowspan="2">BC, not masked</td>
                                                    <td class="width-long2">Right Ear</td>
                                                    <td><input type="number" name="exam[BC_not_masked][Right_ear][125]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[BC_not_masked][Right_ear][250]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[BC_not_masked][Right_ear][500]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[BC_not_masked][Right_ear][750]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[BC_not_masked][Right_ear][1000]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[BC_not_masked][Right_ear][1500]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[BC_not_masked][Right_ear][2000]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[BC_not_masked][Right_ear][3000]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[BC_not_masked][Right_ear][4000]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[BC_not_masked][Right_ear][6000]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[BC_not_masked][Right_ear][8000]" class="td-input" /></td>
                                                </tr>
                                                <tr>
                                                    <td class="width-long2">Left Ear</td>
                                                    <td><input type="number" name="exam[BC_not_masked][Left_ear][125]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[BC_not_masked][Left_ear][250]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[BC_not_masked][Left_ear][500]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[BC_not_masked][Left_ear][750]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[BC_not_masked][Left_ear][1000]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[BC_not_masked][Left_ear][1500]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[BC_not_masked][Left_ear][2000]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[BC_not_masked][Left_ear][3000]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[BC_not_masked][Left_ear][4000]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[BC_not_masked][Left_ear][6000]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[BC_not_masked][Left_ear][8000]" class="td-input" /></td>
                                                </tr>
                                                <tr>
                                                    <td class="width-long" rowspan="2">BC, masked</td>
                                                    <td class="width-long2">Right Ear</td>
                                                    <td><input type="number" name="exam[BC_masked][Right_ear][125]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[BC_masked][Right_ear][250]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[BC_masked][Right_ear][500]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[BC_masked][Right_ear][750]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[BC_masked][Right_ear][1000]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[BC_masked][Right_ear][1500]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[BC_masked][Right_ear][2000]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[BC_masked][Right_ear][3000]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[BC_masked][Right_ear][4000]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[BC_masked][Right_ear][6000]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[BC_masked][Right_ear][8000]" class="td-input" /></td>
                                                </tr>
                                                <tr>
                                                    <td class="width-long2">Left Ear</td>
                                                    <td><input type="number" name="exam[BC_masked][Left_ear][125]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[BC_masked][Left_ear][250]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[BC_masked][Left_ear][500]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[BC_masked][Left_ear][750]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[BC_masked][Left_ear][1000]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[BC_masked][Left_ear][1500]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[BC_masked][Left_ear][2000]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[BC_masked][Left_ear][3000]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[BC_masked][Left_ear][4000]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[BC_masked][Left_ear][6000]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[BC_masked][Left_ear][8000]" class="td-input" /></td>
                                                </tr>
                                                <tr>
                                                    <td class="width-long" rowspan="2">ULL</td>
                                                    <td class="width-long2">Right Ear</td>
                                                    <td><input type="number" name="exam[ULL][Right_ear][125]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[ULL][Right_ear][250]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[ULL][Right_ear][500]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[ULL][Right_ear][750]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[ULL][Right_ear][1000]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[ULL][Right_ear][1500]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[ULL][Right_ear][2000]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[ULL][Right_ear][3000]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[ULL][Right_ear][4000]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[ULL][Right_ear][6000]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[ULL][Right_ear][8000]" class="td-input" /></td>
                                                </tr>
                                                <tr>
                                                    <td class="width-long2">Left Ear</td>
                                                    <td><input type="number" name="exam[ULL][Left_ear][125]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[ULL][Left_ear][250]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[ULL][Left_ear][500]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[ULL][Left_ear][750]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[ULL][Left_ear][1000]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[ULL][Left_ear][1500]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[ULL][Left_ear][2000]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[ULL][Left_ear][3000]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[ULL][Left_ear][4000]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[ULL][Left_ear][6000]" class="td-input" /></td>
                                                    <td><input type="number" name="exam[ULL][Left_ear][8000]" class="td-input" /></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <div class="form-group form-row align-items-center">
                                            <label class="col-lg-2 col-sm-4 text-right">Audiometer:</label>
                                            <div class="col-lg-6 col-sm-6">
                                                <input type="text" class="form-control" name="audiometer" id="audiometer" value="{{ (isset($exam['audiogramData']->audiometer)) ? $exam['audiogramData']->audiometer : '' }}" required>
                                            </div>
                                        </div>
                                        <div class="form-group form-row align-items-center">
                                            <label class="col-lg-2 col-sm-4 text-right">Tester:</label>
                                            <div class="col-lg-6 col-sm-6">
                                                <input type="text" class="form-control" name="tester" id="tester" value="{{ (isset($exam['audiogramData']->tester)) ? $exam['audiogramData']->tester : '' }}" required>
                                            </div>
                                        </div>
                                        <div class="form-group form-row align-items-center">
                                            <label class="col-lg-2 col-sm-4 text-right">Remarks:</label>
                                            <div class="col-lg-6 col-sm-6">
                                                <input type="text" class="form-control" name="remarks" id="remarks" value="{{ (isset($exam['audiogramData']->remarks)) ? $exam['audiogramData']->remarks : '' }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-2 mb-3 text-center">
                                            <button @if(!Session::has('ent_encounter_id')) disabled @endif class="btn btn-primary"><i class="fas fa-check"></i>&nbsp;&nbsp;Save</button>
                                            {{-- <button class="btn btn-primary"><i class="fas fa-code"></i>&nbsp;&nbsp;Report</button> --}}
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div id="audiogram-req" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="form-group row p-3 align-items-center">
                                <div class="col-sm-4">
                                    <input type="text" name="audiogram_requested" id="audiogram_requested" class="form-control" placeholder="Audiogram Request" />
                                </div>
                                <div class="col-sm-3">
                                    <button @if(!Session::has('ent_encounter_id')) disabled @endif id="submit_audiogram_request" url="{{ route('ent.audiogram.request') }}" class="btn btn-primary btn-sm">Request <i class="ri-arrow-right-line"></i> </button>
                                </div>
                            </div>
                            <div class="iq-card-header d-flex justify-content-between">
                                <div class="iq-header-title">
                                    <h4 class="card-title">Audiogram Request Status:</h4>
                                </div>
                            </div>
                            <div class="iq-card-body">
                                <div  class="table-responsive table-container">
                                    <table class="table table-bordered table-hover table-striped text-center table-content">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Date</th>
                                                <th>Request By</th>
                                                <th>Comments</th>
                                                <th>Status</th>
                                                <th>Perform</th>
                                            </tr>
                                        </thead>
                                        <tbody id="audiogram-request-table-body">
                                            @if(isset($exam['audiogram_requested']))
                                            @foreach($exam['audiogram_requested'] as $audiogram_requested)
                                                <tr id="req_{{ $audiogram_requested->id }}">
                                                    <td>{{ $audiogram_requested->requested_date }}</td>
                                                    <td>{{ $audiogram_requested->user->getFullNameAttribute() }}</td>
                                                    <td>{{ $audiogram_requested->comments }}</td>
                                                    <td class="request_status">{{ $audiogram_requested->status }}</td>
                                                    <td>
                                                        <i data-request="{{ $audiogram_requested->id }}" class="fa fa-arrow-circle-right perform-audiogram" aria-hidden="true"></i>
                                                        <a href="{{ route('ent.audiogram.report',$audiogram_requested->id) }}" target="_blank"><i class="fas fa-file audiogram-report" aria-hidden="true"></i></a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <form method="post" action="{{ route('ent.store') }}">
            @csrf
            @include("ent::common.ent-draw")
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <div class="iq-card-header d-flex justify-content-between">
                                <div class="iq-header-title">
                                    <h4 class="card-title">Note</h4>
                                </div>
                            </div>
                            <div class="iq-card-body">
                                <div class="form-group">
                                    <textarea id="js-note-ck-textarea" name="examgeneral[Note]" class="form-control ck-ent">{{ isset($exam['otherData']['note']) ? $exam['otherData']['note'] : ''}}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="iq-card-header d-flex justify-content-between">
                                <div class="iq-header-title">
                                    <h4 class="card-title">Advice</h4>
                                </div>
                            </div>
                            <div class="iq-card-body">
                                <div class="form-group">
                                    <textarea id="js-advice-ck-textarea" name="examgeneral[Advice]" class="form-control ck-ent">{{ isset($exam['otherData']['advice']) ? $exam['otherData']['advice'] : ''}}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <div class="d-flex justify-content-around">
                            <button type="button" onclick="laboratory.displayModal()" class="btn btn-primary">Laboratory</button>
                            <button type="button" onclick="radiology.displayModal()" class="btn btn-primary">Radiology</button>
                            <button type="button" onclick="pharmacy.displayModal()" class="btn btn-primary">Pharmacy</button>
                            @if(isset($enpatient) && $enpatient->fldpatientval)
                                <a href="javascript:;" class="btn btn-primary btn-action {{ $disableClass }}" onclick="showHistoryPopup('{{ $enpatient->fldencounterval }}')">History</a>
                            @endif
                            {{-- <a href="{{ route('ent.histry.pdf', $patient->fldpatientval ?? 0) }}?ent" target="_blank">
                                <button type="button" class="btn btn-primary">History</button>
                            </a> --}}
                            <a href="{{ route('outpatient.pdf.generate.opd.sheet', $enpatient->fldencounterval??0) }}?opd" type="button" class="btn-custom-opd" target="_blank">
                                <button type="button" class="btn btn-primary">OPD Sheet</button>
                            </a>
                            <button class="btn btn-primary">Save</button>
                            <a href="javascript:;" data-toggle="modal" data-target="#finish_box" id="finish">
                                <button class="btn btn-primary">Finish</button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@include('ent::diagnosisstoremodal')
@include('outpatient::modal.diagnosis-freetext-modal')
@include('inpatient::layouts.modal.patient-image')
@include('inpatient::layouts.modal.triage')
@include('outpatient::modal.diagnosis-obstetric-modal')
@include('inpatient::layouts.modal.demographics', ['module' => 'ent'])
@include('outpatient::modal.history')

<script src="{{ asset('js/ent_form.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            setTimeout(function () {
                $(".flditem").select2();
                $(".find_fldhead").select2();
            }, 1500);

            $(document).on("keydown", ".select2-search__field", function (e) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    //alert('You pressed a "enter" key in textbox');
                    $('.flditem').append('<option value="' + $(this).val() + '" selected >' + $(this).val() + '</option>')
                }
            });

            $(".save_comment").hide();
            $(".commentArea").hide();
        });

        $(window).ready(function () {
            initEar();
            initNose();
            initThroat();
            initTongue();
        })

        var canvasEar, ctxEar, flagEar = false,
            prevXEar = 0,
            currXEar = 0,
            prevYEar = 0,
            currYEar = 0,
            dot_flag_ear = false;

        var xEar = "red",
            yEar = 2;

        function initEar() {
            canvasEar = document.getElementById('ear-canvas-draw');
            ctxEar = canvasEar.getContext("2d");
            wEar = canvasEar.width;
            hEar = canvasEar.height;

            canvasEar.addEventListener("mousemove", function (e) {
                findxyEar('move', e)
            }, false);
            canvasEar.addEventListener("mousedown", function (e) {
                findxyEar('down', e)
            }, false);
            canvasEar.addEventListener("mouseup", function (e) {
                findxyEar('up', e)
            }, false);
            canvasEar.addEventListener("mouseout", function (e) {
                findxyEar('out', e)
            }, false);
        }

        function colorEar(obj) {
            $('.color-chooser li').css('border', 'none');
            setTimeout(function () {
                $(obj).css('border', '4px solid #807d7d');
            }, 500);
            switch (obj.id) {
                case "green":
                    xEar = "green";
                    break;
                case "blue":
                    xEar = "blue";
                    break;
                case "red":
                    xEar = "red";
                    break;
                case "yellow":
                    xEar = "yellow";
                    break;
                case "orange":
                    xEar = "orange";
                    break;
                case "black":
                    xEar = "black";
                    break;
                case "white":
                    xEar = "white";
                    break;
            }
            if (xEar == "white") yEar = 14;
            else yEar = 2;

        }

        function drawEar() {
            ctxEar.beginPath();
            ctxEar.moveTo(prevXEar, prevYEar);
            ctxEar.lineTo(currXEar, currYEar);
            ctxEar.strokeStyle = xEar;
            ctxEar.lineWidth = yEar;
            ctxEar.stroke();
            ctxEar.closePath();
        }

        function eraseEar() {
            var mEar = confirm("Want to clear");
            if (mEar) {
                ctxEar.clearRect(0, 0, wEar, hEar);
            }
        }

        function saveEar() {
            var dataURL = canvasEar.toDataURL();
            $('.ear-image').val(dataURL);
        }

        function findxyEar(res, e) {
            if (res == 'down') {
                prevXEar = currXEar;
                prevYEar = currYEar;
                currXEar = e.clientX - canvasEar.getBoundingClientRect().left;
                currYEar = e.clientY - canvasEar.getBoundingClientRect().top;

                flagEar = true;
                dot_flag_ear = true;
                if (dot_flag_ear) {
                    ctxEar.beginPath();
                    ctxEar.fillStyle = xEar;
                    ctxEar.fillRect(currXEar, currYEar, 2, 2);
                    ctxEar.closePath();
                    dot_flag_ear = false;
                }
            }
            if (res == 'up' || res == "out") {
                flagEar = false;
            }
            if (res == 'move') {
                if (flagEar) {
                    prevXEar = currXEar;
                    prevYEar = currYEar;
                    currXEar = e.clientX - canvasEar.getBoundingClientRect().left;
                    currYEar = e.clientY - canvasEar.getBoundingClientRect().top;
                    drawEar();
                }
            }
        }

        var canvasNose, ctxNose, flagNose = false,
            prevXNose = 0,
            currXNose = 0,
            prevYNose = 0,
            currYNose = 0,
            dot_flag_nose = false;

        var xNose = "red",
            yNose = 2;

        function initNose() {
            canvasNose = document.getElementById('nose-canvas-draw');
            ctxNose = canvasNose.getContext("2d");
            wNose = canvasNose.width;
            hNose = canvasNose.height;

            canvasNose.addEventListener("mousemove", function (e) {
                findxyNose('move', e)
            }, false);
            canvasNose.addEventListener("mousedown", function (e) {
                findxyNose('down', e)
            }, false);
            canvasNose.addEventListener("mouseup", function (e) {
                findxyNose('up', e)
            }, false);
            canvasNose.addEventListener("mouseout", function (e) {
                findxyNose('out', e)
            }, false);
        }

        function colorNose(obj) {
            $('.color-chooser li').css('border', 'none');
            setTimeout(function () {
                $(obj).css('border', '4px solid #807d7d');
            }, 500);
            switch (obj.id) {
                case "green":
                    xNose = "green";
                    break;
                case "blue":
                    xNose = "blue";
                    break;
                case "red":
                    xNose = "red";
                    break;
                case "yellow":
                    xNose = "yellow";
                    break;
                case "orange":
                    xNose = "orange";
                    break;
                case "black":
                    xNose = "black";
                    break;
                case "white":
                    xNose = "white";
                    break;
            }
            if (xNose == "white") yNose = 14;
            else yNose = 2;

        }

        function drawNose() {
            ctxNose.beginPath();
            ctxNose.moveTo(prevXNose, prevYNose);
            ctxNose.lineTo(currXNose, currYNose);
            ctxNose.strokeStyle = xNose;
            ctxNose.lineWidth = yNose;
            ctxNose.stroke();
            ctxNose.closePath();
        }

        function eraseNose() {
            var mNose = confirm("Want to clear");
            if (mNose) {
                ctxNose.clearRect(0, 0, wNose, hNose);
            }
        }

        function saveNose() {
            var dataURL = canvasNose.toDataURL();
            $('.nose-image').val(dataURL);
        }

        function findxyNose(res, e) {
            if (res == 'down') {
                prevXNose = currXNose;
                prevYNose = currYNose;
                currXNose = e.clientX - canvasNose.getBoundingClientRect().left;
                currYNose = e.clientY - canvasNose.getBoundingClientRect().top;

                flagNose = true;
                dot_flag_nose = true;
                if (dot_flag_nose) {
                    ctxNose.beginPath();
                    ctxNose.fillStyle = xNose;
                    ctxNose.fillRect(currXNose, currYNose, 2, 2);
                    ctxNose.closePath();
                    dot_flag_nose = false;
                }
            }
            if (res == 'up' || res == "out") {
                flagNose = false;
            }
            if (res == 'move') {
                if (flagNose) {
                    prevXNose = currXNose;
                    prevYNose = currYNose;
                    currXNose = e.clientX - canvasNose.getBoundingClientRect().left;
                    currYNose = e.clientY - canvasNose.getBoundingClientRect().top;
                    drawNose();
                }
            }
        }

        var canvasThroat, ctxThroat, flagThroat = false,
            prevXThroat = 0,
            currXThroat = 0,
            prevYThroat = 0,
            currYThroat = 0,
            dot_flag_throat = false;

        var xThroat = "red",
            yThroat = 2;

        function initThroat() {
            canvasThroat = document.getElementById('throat-canvas-draw');
            ctxThroat = canvasThroat.getContext("2d");
            wThroat = canvasThroat.width;
            hThroat = canvasThroat.height;

            canvasThroat.addEventListener("mousemove", function (e) {
                findxyThroat('move', e)
            }, false);
            canvasThroat.addEventListener("mousedown", function (e) {
                findxyThroat('down', e)
            }, false);
            canvasThroat.addEventListener("mouseup", function (e) {
                findxyThroat('up', e)
            }, false);
            canvasThroat.addEventListener("mouseout", function (e) {
                findxyThroat('out', e)
            }, false);
        }

        function colorThroat(obj) {
            $('.color-chooser li').css('border', 'none');
            setTimeout(function () {
                $(obj).css('border', '4px solid #807d7d');
            }, 500);
            switch (obj.id) {
                case "green":
                    xThroat = "green";
                    break;
                case "blue":
                    xThroat = "blue";
                    break;
                case "red":
                    xThroat = "red";
                    break;
                case "yellow":
                    xThroat = "yellow";
                    break;
                case "orange":
                    xThroat = "orange";
                    break;
                case "black":
                    xThroat = "black";
                    break;
                case "white":
                    xThroat = "white";
                    break;
            }
            if (xThroat == "white") yThroat = 14;
            else yThroat = 2;

        }

        function drawThroat() {
            ctxThroat.beginPath();
            ctxThroat.moveTo(prevXThroat, prevYThroat);
            ctxThroat.lineTo(currXThroat, currYThroat);
            ctxThroat.strokeStyle = xThroat;
            ctxThroat.lineWidth = yThroat;
            ctxThroat.stroke();
            ctxThroat.closePath();
        }

        function eraseThroat() {
            var mThroat = confirm("Want to clear");
            if (mThroat) {
                ctxThroat.clearRect(0, 0, wThroat, hThroat);
            }
        }

        function saveThroat() {
            var dataURL = canvasThroat.toDataURL();
            $('.throat-image').val(dataURL);
        }

        function findxyThroat(res, e) {
            if (res == 'down') {
                prevXThroat = currXThroat;
                prevYThroat = currYThroat;
                currXThroat = e.clientX - canvasThroat.getBoundingClientRect().left;
                currYThroat = e.clientY - canvasThroat.getBoundingClientRect().top;

                flagThroat = true;
                dot_flag_throat = true;
                if (dot_flag_throat) {
                    ctxThroat.beginPath();
                    ctxThroat.fillStyle = xThroat;
                    ctxThroat.fillRect(currXThroat, currYThroat, 2, 2);
                    ctxThroat.closePath();
                    dot_flag_throat = false;
                }
            }
            if (res == 'up' || res == "out") {
                flagThroat = false;
            }
            if (res == 'move') {
                if (flagThroat) {
                    prevXThroat = currXThroat;
                    prevYThroat = currYThroat;
                    currXThroat = e.clientX - canvasThroat.getBoundingClientRect().left;
                    currYThroat = e.clientY - canvasThroat.getBoundingClientRect().top;
                    drawThroat();
                }
            }
        }

        var canvasTongue, ctxTongue, flagTongue = false,
            prevXTongue = 0,
            currXTongue = 0,
            prevYTongue = 0,
            currYTongue = 0,
            dot_flag_tongue = false;

        var xTongue = "red",
            yTongue = 2;

        function initTongue() {
            canvasTongue = document.getElementById('tongue-canvas-draw');
            ctxTongue = canvasTongue.getContext("2d");
            wTongue = canvasTongue.width;
            hTongue = canvasTongue.height;

            canvasTongue.addEventListener("mousemove", function (e) {
                findxyTongue('move', e)
            }, false);
            canvasTongue.addEventListener("mousedown", function (e) {
                findxyTongue('down', e)
            }, false);
            canvasTongue.addEventListener("mouseup", function (e) {
                findxyTongue('up', e)
            }, false);
            canvasTongue.addEventListener("mouseout", function (e) {
                findxyTongue('out', e)
            }, false);
        }

        function colorTongue(obj) {
            $('.color-chooser li').css('border', 'none');
            setTimeout(function () {
                $(obj).css('border', '4px solid #807d7d');
            }, 500);
            switch (obj.id) {
                case "green":
                    xTongue = "green";
                    break;
                case "blue":
                    xTongue = "blue";
                    break;
                case "red":
                    xTongue = "red";
                    break;
                case "yellow":
                    xTongue = "yellow";
                    break;
                case "orange":
                    xTongue = "orange";
                    break;
                case "black":
                    xTongue = "black";
                    break;
                case "white":
                    xTongue = "white";
                    break;
            }
            if (xTongue == "white") yTongue = 14;
            else yTongue = 2;

        }

        function drawTongue() {
            ctxTongue.beginPath();
            ctxTongue.moveTo(prevXTongue, prevYTongue);
            ctxTongue.lineTo(currXTongue, currYTongue);
            ctxTongue.strokeStyle = xTongue;
            ctxTongue.lineWidth = yTongue;
            ctxTongue.stroke();
            ctxTongue.closePath();
        }

        function eraseTongue() {
            var mTongue = confirm("Want to clear");
            if (mTongue) {
                ctxTongue.clearRect(0, 0, wTongue, hTongue);
            }
        }

        function saveTongue() {
            var dataURL = canvasTongue.toDataURL();
            $('.tongue-image').val(dataURL);
        }

        function findxyTongue(res, e) {
            if (res == 'down') {
                prevXTongue = currXTongue;
                prevYTongue = currYTongue;
                currXTongue = e.clientX - canvasTongue.getBoundingClientRect().left;
                currYTongue = e.clientY - canvasTongue.getBoundingClientRect().top;

                flagTongue = true;
                dot_flag_tongue = true;
                if (dot_flag_tongue) {
                    ctxTongue.beginPath();
                    ctxTongue.fillStyle = xTongue;
                    ctxTongue.fillRect(currXTongue, currYTongue, 2, 2);
                    ctxTongue.closePath();
                    dot_flag_tongue = false;
                }
            }
            if (res == 'up' || res == "out") {
                flagTongue = false;
            }
            if (res == 'move') {
                if (flagTongue) {
                    prevXTongue = currXTongue;
                    prevYTongue = currYTongue;
                    currXTongue = e.clientX - canvasTongue.getBoundingClientRect().left;
                    currYTongue = e.clientY - canvasTongue.getBoundingClientRect().top;
                    drawTongue();
                }
            }
        }

        $(document).on('click','#submit_audiogram_request',function () {
            var url = $(this).attr('url');
            var audiogram_request = $('#audiogram_requested').val();
            if(audiogram_request != ""){
                var formData = {
                    audiogram_request: audiogram_request,
                };
                $.ajax({
                    url: url,
                    type: "POST",
                    dataType: "json",
                    data: formData,
                    success: function (data) {
                        if ($.isEmptyObject(data.error)) {
                            $("#audiogram-request-table-body").append(data.success.rowview);
                            showAlert("Information saved!!");
                        } else {
                            alert("Something went wrong!!");
                        }
                    }
                });
            }
        });

        $(document).on('click','.perform-audiogram',function () {
            var requestid = $(this).attr('data-request');
            var formData = {
                requestid: requestid
            };
            $.ajax({
                url: "{{ route('ent.audiogram.perform') }}",
                type: "POST",
                dataType: "json",
                data: formData,
                success: function (data) {
                    if ($.isEmptyObject(data.error)) {
                        $('input[name^="exam"]').each(function() {
                            $(this).val("");
                        });
                        $("#audiogram_request_id").val(data.success.audiogramRequestData.id);
                        $("#date_label").html(data.success.audiogramRequestData.requested_date);
                        $("#request_by_label").html(data.success.audiogramRequestData.user.fldfullname);
                        if(data.success.audiogramRequestData.examiner != null){
                            $("#examined_by_label").html(data.success.audiogramRequestData.examiner.fldfullname);
                            $("#examined_date_label").html(data.success.audiogramRequestData.examined_date);
                        }else{
                            $("#examined_by_label").html("");
                            $("#examined_date_label").html("");
                        }
                        $("#comments_label").html(data.success.audiogramRequestData.comments);
                        $("#status_label").html(data.success.audiogramRequestData.status);
                        if(data.success.audiogramData != null){
                            $("#audiometer").val(data.success.audiogramData.audiometer);
                            $("#tester").val(data.success.audiogramData.tester);
                            $("#remarks").val(data.success.audiogramData.remarks);
                        }
                        $.each(data.success.maskingData, function( index, value ) {
                            var masking_type = index;
                            $.each(value, function( index2, value2 ) {
                                var ear_side = index2;
                                $.each(value2, function( index3, value3 ) {
                                    var frequency_key = index3;
                                    $("input[name='exam["+masking_type+"]["+ear_side+"]["+frequency_key+"]']").val(value3);
                                });
                            });
                        });
                        $("#audiogram").addClass("show");
                        $("#audiogramTab").attr("aria-expanded",true);
                        $("#audiogram-req").removeClass("show");
                        $("#audiogramReqTab").attr("aria-expanded",false);
                    } else {
                        alert("Something went wrong!!");
                    }
                }
            });
        });

        $('#saveAudiogramForm').on('submit', function(event){
            event.preventDefault();
            if($("#audiogram_request_id")!=""){
                if($("#audiometer")!="" && $("#tester")!="" && $("#remarks")!=""){
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}'
                        }
                    });
                    $.ajax({
                        url:"{{ route('ent.audiogram.save') }}",
                        method:"POST",
                        data: new FormData(this),
                        contentType: false,
                        cache:false,
                        processData: false,
                        dataType:"json",
                        success:function(data){
                            if(data.result.status){
                                showAlert("Information saved!!");
                                $("#req_"+data.result.audiogram_request_id).find('.request_status').html("Done");
                                $("#examined_by_label").html(data.result.audiogram_request.examiner.fldfullname);
                                $("#examined_date_label").html(data.result.audiogram_request.examined_date);
                                $("#comments_label").html(data.result.audiogram_request.comments);
                                $("#status_label").html(data.result.audiogram_request.status);
                            }else{
                                showAlert('Error occured', 'error');
                            }
                        }
                    });
                }else{
                    showAlert('Please fill all the required details', 'error');
                }
            }
        });

        $(document).on("click",".save_comment",function(){
            var comment = $(this).closest(".custom-col-audiogram").find(".commentArea").val();
            var commentType = $(this).attr("data-type");
            var formData = {
                comment: comment,
                commentType: commentType
            };
            $.ajax({
                url: "{{ route('ent.comment.save') }}",
                type: "POST",
                dataType: "json",
                data: formData,
                success: function (data) {
                    if ($.isEmptyObject(data.error)) {
                        showAlert("Information saved!!");
                    } else {
                        alert("Something went wrong!!");
                    }
                }
            });
        });

        $(document).on("click",".showComment",function(){
            if($(this).closest(".custom-col-audiogram").find(".showComment").hasClass("show")){
                $(this).closest(".custom-col-audiogram").find(".showComment").removeClass("show");
                $(this).closest(".custom-col-audiogram").find(".save_comment").hide();
                $(this).closest(".custom-col-audiogram").find(".commentArea").hide();
            }else{
                $(this).closest(".custom-col-audiogram").find(".showComment").addClass("show");
                $(this).closest(".custom-col-audiogram").find(".save_comment").show();
                $(this).closest(".custom-col-audiogram").find(".commentArea").show();
            }
        });

        function showHistoryPopup($encounter) {
            if ($encounter == "") {
                showAlert('No patient selected.', 'error');
                return false;
            }
            let route = "{!! route('history.by.patient', ['encounter' => ':ENCOUNTER_ID']) !!}";
            route = route.replace(':ENCOUNTER_ID', $encounter);
            $.ajax({
                url: route,
                type: "POST",
                success: function (response) {
                    // console.log(response);
                    $('.history-modal-content').empty();
                    $('.history-modal-content').html(response);
                    $('#history-modal').modal('show');
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }
    </script>
@endsection
