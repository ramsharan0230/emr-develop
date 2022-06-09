@extends('frontend.layouts.master')

@push('after-styles')
    <style>
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
    @if(isset($patient_status_disabled) && $patient_status_disabled == 1 )
        @php
            $disableClass = 'disableInsertUpdate';
        @endphp
    @else
        @php
            $disableClass = '';
        @endphp
    @endif
    @include('menu::common.nav-bar')
    <!-- TOP Nav Bar END -->
    <div class="container-fluid">
        <input type="hidden" id="get_content" value="{{ route('get_content') }}">
        <div class="row">
            <!--Here patient profile -->
            @include('frontend.common.patientProfile')
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Vital Exam</h4>
                        </div>
                        <a href="javascript:;" class="btn btn-primary" data-toggle="modal" data-target=".eeg-modal">EEG</a>
                    </div>
                    <div class="iq-card-body">
                        <div class="row">
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="">Pulse Rate</label>
                                    <input type="text" class="form-control @if(isset($pulse->fldrepquanti) &&  $pulse->fldrepquanti >=  $pulse->fldhigh) highline @endif  @if(isset($pulse->fldrepquanti) &&  $pulse->fldrepquanti <=  $pulse->fldlow) lowline @endif remove_zero_to_empty" id="pulse_rate" high="@if(isset($pulse_range->fldhigh)){{$pulse_range->fldhigh}}@endif"
                                           low="@if(isset($pulse_range->fldlow)){{$pulse_range->fldlow}}@endif" placeholder="" pulse_rate="Pulse Rate"
                                           value="{{ isset($pulse->fldrepquanti) ?  $pulse->fldrepquanti : 0 }}">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="">Syst BP</label>
                                    <input type="text" class="form-control @if(isset($systolic_bp->fldrepquanti) &&  $systolic_bp->fldrepquanti >=  $systolic_bp->fldhigh) highline @endif  @if(isset($systolic_bp->fldrepquanti) &&  $systolic_bp->fldrepquanti <=  $systolic_bp->fldlow) lowline @endif remove_zero_to_empty" id="sys_bp"
                                           high="@if(isset($systolic_bp_range->fldhigh)){{$systolic_bp_range->fldhigh}}@endif" low="@if(isset($systolic_bp_range->fldlow)){{$systolic_bp_range->fldlow}}@endif" placeholder="" sys_bp="Systolic BP"
                                           value="{{ isset($systolic_bp->fldrepquanti) ?  $systolic_bp->fldrepquanti : 0  }}">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="">Diast BP</label>
                                    <input type="text" class="form-control @if(isset($diasioli_bp->fldrepquanti) &&  $diasioli_bp->fldrepquanti >=  $diasioli_bp->fldhigh) highline @endif  @if(isset($diasioli_bp->fldrepquanti) &&  $diasioli_bp->fldrepquanti <=  $diasioli_bp->fldlow) lowline @endif remove_zero_to_empty " id="dia_bp"
                                           high="@if(isset($diasioli_bp_range->fldhigh)){{$diasioli_bp_range->fldhigh}}@endif" low="@if(isset($diasioli_bp_range->fldlow)){{$diasioli_bp_range->fldlow}}@endif" placeholder="" dia_bp="Diastolic BP"
                                           value="{{ isset($diasioli_bp->fldrepquanti) ? $diasioli_bp->fldrepquanti : 0  }}">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="">Resp Rate</label>
                                    <input type="text" class="form-control @if(isset($respiratory_rate->fldrepquanti) &&  $respiratory_rate->fldrepquanti >=  $respiratory_rate->fldhigh) highline @endif  @if(isset($respiratory_rate->fldrepquanti) &&  $respiratory_rate->fldrepquanti <=  $respiratory_rate->fldlow) lowline @endif remove_zero_to_empty" id="respi"
                                           high="@if(isset($respiratory_rate_range->fldhigh)){{$respiratory_rate_range->fldhigh}}@endif" low="@if(isset($respiratory_rate_range->fldlow)){{$respiratory_rate_range->fldlow}}@endif" placeholder="" respi="Respiratory Rate"
                                           value="{{ isset($respiratory_rate->fldrepquanti) ? $respiratory_rate->fldrepquanti : 0 }}">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="">S P O2</label>
                                    <input type="text" class="form-control @if(isset($o2_saturation->fldrepquanti) &&  $o2_saturation->fldrepquanti >=  $o2_saturation->fldhigh) highline @endif  @if(isset($o2_saturation->fldrepquanti) &&  $o2_saturation->fldrepquanti <=  $o2_saturation->fldlow) lowline @endif remove_zero_to_empty" id="saturation"
                                           high="@if(isset($o2_saturation_range->fldhigh)){{$o2_saturation_range->fldhigh}}@endif" low="@if(isset($o2_saturation_range->fldlow)){{$o2_saturation_range->fldlow}}@endif" placeholder="" saturation="O2 Saturation"
                                           value="{{ isset($o2_saturation->fldrepquanti) ? $o2_saturation->fldrepquanti : 0 }}">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="">Temp</label>
                                    <input type="text" class="form-control @if(isset($temperature->fldrepquanti) &&  $temperature->fldrepquanti >=  $temperature->fldhigh) highline @endif  @if(isset($temperature->fldrepquanti) &&  $temperature->fldrepquanti <=  $temperature->fldlow) lowline @endif remove_zero_to_empty" id="pulse_rate_rate"
                                           high="@if(isset($temperature_range->fldhigh)){{$temperature_range->fldhigh}}@endif" low="@if(isset($temperature_range->fldlow)){{$temperature_range->fldlow}}@endif" placeholder="" pulse_rate_rate="Temperature (F)"
                                           value="{{ isset($temperature->fldrepquanti) ?  $temperature->fldrepquanti : 0 }}">
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center">
                            <input type="hidden" id="check_vital" url="{{route('check_vital')}}">
                            <a href="javascript:;" class="btn btn-primary rounded-pill {{$disableClass}}" type="button" url="{{ route('insert_essential_exam') }}" id="save_essential">

                                Vital Save
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Chief Complaints</h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <div class="cheif-complaints">
                            <form action="" class="form-horizontal">
                                <div class="form-group form-row align-items-center">
                                    <div class="col-sm-5">
                                        <select name="flditem" class="form-control flditem select2-chief-complaint" id="select2-chief-complaint">
                                            <option value="">--Select--</option>
                                            @if(isset($complaint))
                                                @foreach($complaint as $com)
                                                    <option value="{{ $com->fldsymptom }}">{{ $com->fldsymptom }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-sm-1">
                                        <input name="duration" class=" form-control  duration" type="numeric" value="0" min="0"/>
                                    </div>
                                    <div class="col-sm-2">
                                        <select name="duration_type" class="form-control duration_type">
                                            <option value="">--Select--</option>
                                            <option value="Hours">Hours</option>
                                            <option value="Days">Days</option>
                                            <option value="Weeks">Weeks</option>
                                            <option value="Months">Months</option>
                                            <option value="Years">Years</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <select name="fldreportquali" class="form-control fldreportquali">
                                            <option value="">--Select--</option>
                                            <option value="Left Side">Left Side</option>
                                            <option value="Right Side">Right Side</option>
                                            <option value="Both Side">Both Side</option>
                                            <option value="Episodes">Episodes</option>
                                            <option value="On/Off">On/Off</option>
                                            <option value="Present">Present</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-1">
                                        <button class="btn btn-primary {{ $disableClass }}" id="insert_complaints" url="{{ route('insert_complaint')}}" type="button">
                                            <i class="ri-add-fill"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <div class="res-table table-responsive">
                                <table class="table table-hovered table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>&nbsp;</th>
                                        <th>Symptoms</th>
                                        <th>Dura</th>
                                        <th>Side</th>
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                        <th>Time</th>
                                        <th>Details</th>
                                        <th>User</th>
                                    </tr>
                                    </thead>
                                    <tbody class="get_cheif_complent_data_table">
                                    @if(isset($examgeneral))
                                        @foreach($examgeneral as $general)
                                            <tr id="com_{{ $general->fldid }}">

                                                <td class="text-center loop_iteration">{{$loop->iteration}}</td>
                                                <td>{{ $general->flditem }}</td>
                                                <td>@if($general->fldreportquanti <= 24) {{ $general->fldreportquanti }} hr @endif @if($general->fldreportquanti > 24 && $general->fldreportquanti <=720 ) {{ round($general->fldreportquanti/24,2) }} Days @endif @if($general->fldreportquanti > 720 && $general->fldreportquanti <8760) {{ round($general->fldreportquanti/720,2) }}
                                                    Months @endif @if($general->fldreportquanti >= 8760) {{ round($general->fldreportquanti/8760) }} Years @endif


                                                </td>
                                                <td>{{ $general->fldreportquali }}</td>
                                                <td><a href="javascript:;" permit_user="{{ $general->flduserid }}" class="delete_complaints {{ $disableClass }}" url="{{ route('delete_complaint',$general->fldid) }}"><i class="far fa-trash-alt"></i></a></td>
                                                <td><a href="javascript:void(0);" permit_user="{{ $general->flduserid }}" data-toggle="modal" old_complaint_detail="{{$general->flddetail}}" class="clicked_edit_complaint {{ $disableClass }}" clicked_flag_val="{{ $general->fldid }}">

                                                        <i class="fas fa-edit"></i></a></td>
                                                <td>{{ $general->fldtime }}</td>
                                                <td class="detail_{{ $general->fldid }}">{{ strip_tags($general->flddetail) }}</td>
                                                <td>{{ $general->flduserid }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                                <div class="modal fade" id="edit_complaint" tabindex="-1" role="dialog" aria-labelledby="edit_complaintLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <input type="hidden" id="complaintfldid" name="fldid" value="">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="edit_complaintLabel" style="text-align: center;">Edit Complaint</h5>
                                                <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">

                                                <div class="row">
                                                    <textarea name="flddetail" class="flddetail_complaint" id="editor"></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary onclose" data-dismiss="modal">Close</button>
                                                <button id="submit_detail_complaint" class="btn btn-primary {{ $disableClass }}" url="{{ route('insert_complaint_detail') }}">Save changes</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12">

                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <div class="examination-tab">
                            <ul class="nav nav-tabs justify-content-center" id="myTab-two" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true">General</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#clinical-finding" role="tab" aria-controls="clinical-finding" aria-selected="false">Clinical Findings</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent-1">
                                <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general">

                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="control-label col-sm-3 mb-0">1. Pallor</label>

                                    <!-- <div class="col-sm-3">
                                            <div class="profile-form custom-control custom-checkbox custom-control-inline">
                                                <input type="checkbox" {{(isset($Pallor) && !empty($Pallor) && $Pallor->fldabnormal != 1) ? 'checked' :''   }} class="custom-control-input normal-flag"/>
                                                <label class="custom-control-label" for="">Abnormal
                                                </label>
                                            </div>
                                        </div> -->
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" name="Pallor-plc" class="custom-control-input Pallor-plc" value="Present" @if(isset($Pallor) && !empty($Pallor) && $Pallor->fldrepquali == 'Present')  checked @endif >
                                                    <label for="" class="custom-control-label">Present</label>
                                                </div>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" name="Pallor-plc" class="custom-control-input Pallor-plc" value="Absent" @if(isset($Pallor) && !empty($Pallor) && $Pallor->fldrepquali == 'Absent')  checked @endif @if(empty($Pallor)) checked @endif>
                                                    <label for="" class="custom-control-label">Absent</label>
                                                </div>
                                            </div>

                                        <!-- <select name="Pallor-plc" id="Pallor-plc" class="form-control">
                                                <option></option>
                                                <option value="Present" @if(isset($Pallor) && !empty($Pallor) && $Pallor->fldrepquali == 'Present')  selected=selected @endif>Present</option>
                                                <option value="Absent" @if(isset($Pallor) && !empty($Pallor) && $Pallor->fldrepquali == 'Absent')  selected=selected @endif>Absent</option>
                                            </select> -->
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="control-label col-sm-3 mb-0">2. Icterus
                                        </label>
                                    <!-- <div class="col-sm-3">
                                            <div class="profile-form custom-control custom-checkbox custom-control-inline">
                                                <input type="checkbox" @if(isset($Icterus) && !empty($Icterus) && $Icterus->fldabnormal != 1)  checked @endif class="custom-control-input normal-flag"/>
                                                <label class="custom-control-label" for="">Abnormal
                                                </label>
                                            </div>
                                        </div> -->
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" name="Icterus-plc" class="custom-control-input Icterus-plc" value="Present" @if(isset($Icterus) && !empty($Icterus) && $Icterus->fldrepquali == 'Present')  checked @endif>
                                                    <label for="" class="custom-control-label">Present</label>
                                                </div>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" name="Icterus-plc" class="custom-control-input Icterus-plc" value="Absent" @if(isset($Icterus) && !empty($Icterus) && $Icterus->fldrepquali == 'Absent')  checked @endif @if(empty($Icterus)) checked @endif>
                                                    <label for="" class="custom-control-label">Absent</label>
                                                </div>
                                            </div>

                                        <!-- <select name="Icterus-plc" id="Icterus-plc" class="form-control">
                                                <option></option>
                                                <option value="Present" @if(isset($Icterus) && !empty($Icterus) && $Icterus->fldrepquali == 'Present')  selected=selected @endif>Present</option>
                                                <option value="Absent" @if(isset($Icterus) && !empty($Icterus) && $Icterus->fldrepquali == 'Absent')  selected=selected @endif>Absent</option>
                                            </select> -->
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="control-label col-sm-3 mb-0">3. Cyanosis</label>
                                    <!-- <div class="col-sm-3">
                                            <div class="profile-form custom-control custom-checkbox custom-control-inline">
                                                <input type="checkbox" @if(isset($Cyanosis) && !empty($Cyanosis) && $Cyanosis->fldabnormal != 1)  checked @endif class="custom-control-input normal-flag"/>
                                                <label class="custom-control-label" for="">Abnormal
                                                </label>
                                            </div>
                                        </div> -->
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" name="Cyanosis-plc" class="custom-control-input Cyanosis-plc" value="Present" @if(isset($Cyanosis) && !empty($Cyanosis) && $Cyanosis->fldrepquali == 'Present')  checked @endif>
                                                    <label for="" class="custom-control-label">Present</label>
                                                </div>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" name="Cyanosis-plc" class="custom-control-input Cyanosis-plc" value="Absent" @if(isset($Cyanosis) && !empty($Cyanosis) && $Cyanosis->fldrepquali == 'Absent')  checked @endif @if(empty($Cyanosis)) checked @endif>
                                                    <label for="" class="custom-control-label">Absent</label>
                                                </div>
                                            </div>


                                        <!-- <select name="Cyanosis-plc" id="Cyanosis-plc" class="form-control">
                                                <option></option>
                                                <option value="Present" @if(isset($Cyanosis) && !empty($Cyanosis) && $Cyanosis->fldrepquali == 'Present')  selected=selected @endif>Present</option>
                                                <option value="Absent" @if(isset($Cyanosis) && !empty($Cyanosis) && $Cyanosis->fldrepquali == 'Absent')  selected=selected @endif>Absent</option>
                                            </select> -->
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="control-label col-sm-3 mb-0">4. Clubbing</label>
                                    <!-- <div class="col-sm-3">
                                            <div class="profile-form custom-control custom-checkbox custom-control-inline">
                                                <input type="checkbox" @if(isset($Clubbing) && !empty($Clubbing) && $Clubbing->fldabnormal != 1)  checked @endif class="custom-control-input normal-flag"/>
                                                <label class="custom-control-label" for="">Abnormal
                                                </label>
                                            </div>
                                        </div> -->
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" name="Clubbing-plc" class="custom-control-input Clubbing-plc" value="Present" @if(isset($Clubbing) && !empty($Clubbing) && $Clubbing->fldrepquali == 'Present')  checked @endif>
                                                    <label for="" class="custom-control-label">Present</label>
                                                </div>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" name="Clubbing-plc" class="custom-control-input Clubbing-plc" value="Absent" @if(isset($Clubbing) && !empty($Clubbing) && $Clubbing->fldrepquali == 'Absent')  checked @endif @if(empty($Clubbing)) checked @endif>
                                                    <label for="" class="custom-control-label">Absent</label>
                                                </div>
                                            </div>

                                        <!-- <select name="Clubbing-plc" id="Clubbing-plc" class="form-control">
                                                <option></option>
                                                <option value="Present" @if(isset($Clubbing) && !empty($Clubbing) && $Clubbing->fldrepquali == 'Present')  selected=selected @endif>Present</option>
                                                <option value="Absent" @if(isset($Clubbing) && !empty($Clubbing) && $Clubbing->fldrepquali == 'Absent')  selected=selected @endif>Absent</option>
                                            </select> -->
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="control-label col-sm-3 mb-0">5. Oedema</label>
                                    <!-- <div class="col-sm-3">
                                            <div class="profile-form custom-control custom-checkbox custom-control-inline">
                                                <input type="checkbox" @if(isset($Oedema) && !empty($Oedema) && $Oedema->fldabnormal != 1)  checked @endif class="custom-control-input normal-flag"/>
                                                <label class="custom-control-label" for="">Abnormal
                                                </label>
                                            </div>
                                        </div> -->
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" name="Oedema-plc" class="custom-control-input Oedema-plc" value="Present" @if(isset($Oedema) && !empty($Oedema) && $Oedema->fldrepquali == 'Present')  checked @endif>
                                                    <label for="" class="custom-control-label">Present</label>
                                                </div>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" name="Oedema-plc" class="custom-control-input Oedema-plc" value="Absent" @if(isset($Oedema) && !empty($Oedema) && $Oedema->fldrepquali == 'Absent')  checked @endif @if(empty($Oedema)) checked @endif>
                                                    <label for="" class="custom-control-label">Absent</label>
                                                </div>
                                            </div>

                                        <!-- <select name="Oedema-plc" id="Oedema-plc" class="form-control">
                                                <option></option>
                                                <option value="Present" @if(isset($Oedema) && !empty($Oedema) && $Oedema->fldrepquali == 'Present')  selected=selected @endif>Present</option>
                                                <option value="Absent" @if(isset($Oedema) && !empty($Oedema) && $Oedema->fldrepquali == 'Absent')  selected=selected @endif>Absent</option>
                                            </select> -->
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="control-label col-sm-3 mb-0">6. Dehydration</label>
                                    <!-- <div class="col-sm-3">
                                            <div class="profile-form custom-control custom-checkbox custom-control-inline">
                                                <input type="checkbox" @if(isset($Dehydration) && !empty($Dehydration) && $Dehydration->fldabnormal != 1)  checked @endif class="custom-control-input normal-flag"/>
                                                <label class="custom-control-label" for="">Abnormal
                                                </label>
                                            </div>
                                        </div> -->
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" name="Dehydration-plc" class="custom-control-input Dehydration-plc" value="Present" @if(isset($Dehydration) && !empty($Dehydration) && $Dehydration->fldrepquali == 'Present')  checked @endif>
                                                    <label for="" class="custom-control-label">Present</label>
                                                </div>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" name="Dehydration-plc" class="custom-control-input Dehydration-plc" value="Absent" @if(isset($Dehydration) && !empty($Dehydration) && $Dehydration->fldrepquali == 'Absent')  checked @endif @if(empty($Dehydration)) checked @endif>
                                                    <label for="" class="custom-control-label">Absent</label>
                                                </div>
                                            </div>

                                        <!-- <select name="Dehydration-plc" id="Dehydration-plc" class="form-control">
                                                <option></option>
                                                <option value="Present" @if(isset($Dehydration) && !empty($Dehydration) && $Dehydration->fldrepquali == 'Present')  selected=selected @endif>Present</option>
                                                <option value="Absent" @if(isset($Dehydration) && !empty($Dehydration) && $Dehydration->fldrepquali == 'Absent')  selected=selected @endif>Absent</option>
                                            </select> -->
                                        </div>
                                    </div>
                                    <div class="form-group top-req">
                                        <input type="hidden" name="tab" value="general" id="tab">
                                        <a href="javascript:;" type="button" class="btn btn-primary btn-action disableInsertUpdate" style="float:right" url="{{ route('insert_general_exam') }}" id="insert_general_exam">
                                            Save</a>
                                    </div>

                                </div>
                                <div class="tab-pane fade " id="clinical-finding" role="tabpanel" aria-labelledby="clinical-finding">
                                    <form action="" class="form-horizontal">
                                        <div class="form-group form-row align-items-center">
                                            <div class="col-md-6">
                                                <select name="fldhead" id="find_fldhead" class="form-control find_fldhead">
                                                    <option value="" selected>---select---</option>
                                                    @if(isset($finding))
                                                        @foreach($finding as $k => $exam)
                                                            <option value="{{ $exam->fldexamid }}" typeoption="{{ $exam->fldoption }}" fldsysconst="{{ $exam->fldsysconst }}" fldtype="{{ $exam->fldtype }}">{{ $exam->fldexamid }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="res-table">
                                        <table class="table table-bordered table-hover table-striped">
                                            <thead>
                                            <tr>
                                                <th>Examination</th>
                                                <th>&nbsp;</th>
                                                <th>Observation</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>Report Time</th>
                                            </tr>
                                            </thead>
                                            @if(isset($patientexam))
                                                <tbody id="js-outpatient-findings-tbody">
                                                @foreach($patientexam as $pexam)
                                                    <tr data-fldid="{{ $pexam->fldid }}">

                                                        <td>{{ $pexam->fldhead}}</td>
                                                        <td>
                                                            <a href="javascript:;" data-toggle="modal" data-target="#findingnormalflag" class="clicked_flag @if($pexam->fldabnormal == 0 ) text-success @elseif($pexam->fldabnormal == 1) text-danger @endif " clicked_flag_val="{{ $pexam->fldid }}">
                                                                <i class="fas fa-square"></i>
                                                            </a></td>
                                                        <?php

                                                        $result_clinical_finding = json_decode($pexam->fldrepquali);
                                                        if (json_last_error() === JSON_ERROR_NONE) {
                                                            $oResult = "";
                                                            if (is_array($result_clinical_finding) || is_object($result_clinical_finding)) {
                                                                foreach ($result_clinical_finding as $key => $val) {
                                                                    $oResult .= $key . ': ' . $val . ', ';
                                                                }
                                                            }
                                                        } else {
                                                            $oResult = $pexam->fldrepquali;
                                                        }

                                                        if ($oResult != '') {
                                                            $observationResult = $oResult;
                                                        } else {
                                                            $observationResult = $pexam->fldrepquali;
                                                        }

                                                        ?>
                                                        <td>{!! $observationResult !!}</td>
                                                        <td>
                                                            <a href="javascript:;" permit_user="{{ $pexam->flduserid }}" class="delete_finding {{ $disableClass }} text-danger" url="{{ route('delete_finding',$pexam->fldid) }}"> <i class="ri-delete-bin-5-fill"></i> </a>
                                                        </td>
                                                        <td>
                                                            <a href="javascript:;" permit_user="{{ $pexam->flduserid }}" data-toggle="modal" data-target="#edit_finding" class="clicked_edit_finding text-info" clicked_flag_val="{{ $pexam->fldid }}">
                                                                <i class="fas fa-edit"></i></a>
                                                        </td>
                                                        <td>{{ $pexam->fldtime}}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            @endif
                                        </table>

                                    <!-- <div class="modal fade" id="edit_finding" tabindex="-1" role="dialog" aria-labelledby="edit_findingLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <form method="post" action="{{ route('insert_finding_detail') }}">
                                                        @csrf
                                        <input type="hidden" id="findingfldid" name="fldid" value="">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="edit_findingLabel" style="text-align: center;">Edit Finding</h5>
                                            <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">

                                                            <div class="row">
                                                                <textarea name="flddetail" id="editor"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary onclose" data-dismiss="modal">Close</button>
                                                            <input type="hidden" name="tab" value="clinical-finding">
                                                            <input type="submit" name="submit" id="submitflag" class="btn btn-primary {{ $disableClass }}" value="Save changes">
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div> -->


                                        <div class="modal fade" id="findingnormalflag" tabindex="-1" role="dialog" aria-labelledby="findingnormalflagLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <form method="post" action="{{ route('update_abnormal') }}">
                                                        @csrf
                                                        <input type="hidden" id="findingfldidabn" name="fldid" value="">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="findingnormalflagLabel" style="text-align: center;">Change Flag</h5>
                                                            <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">

                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <select id="status" name="status" class="form-control">

                                                                        <option value="0">Normal</option>
                                                                        <option value="1">Abnormal</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary onclose" data-dismiss="modal">Close</button>
                                                            <input type="hidden" name="tab" value="clinical-finding">
                                                            <input type="submit" name="submit" id="submitflag" class="btn btn-primary {{ $disableClass }}" value="Save changes">
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title d-flex align-items-center">
                            <h4 class="card-title">Allergy</h4>
                            <!--  <div class="profile-form custom-control custom-checkbox custom-control-inline ml-4">
                                 <input type="checkbox" class="custom-control-input" id="customCheck5"/>
                                 <label class="custom-control-label" for="customCheck5">Abnormal
                                 </label>
                             </div> -->
                        </div>
                        <div class="allergy-add">
                            @if((isset($enable_freetext) and $enable_freetext == 'Yes') || (isset($enable_freetext) and $enable_freetext == '1'))
                                <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#allergyfreetext" onclick="allergyfreetext.displayModal()"><i class="ri-add-fill"></i></a>
                            @else
                                <a href="javascript:void(0);" class="btn btn-primary">Free</a>
                            @endif
                            <a href="javascript:void(0);" data-toggle="modal" data-target="#allergicdrugs" class="btn btn-warning"><i class="ri-add-fill"></i></a>
                            <!-- <a href="javascript:void(0);" class="iq-bg-secondary"><i class="ri-add-fill"></i></a> -->
                            <a href="javascript:void(0);" class="btn-danger btn" id="deletealdrug"><i class="ri-delete-bin-6-line"></i></a>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <form action="" class="form-horizontal">
                            <div class="form-group mb-0">
                                <input type="hidden" name="delete_pat_findings" class="delete_pat_findings" value="{{ route('deletepatfinding') }}"/>
                                <select name="" id="select-multiple-aldrug" class="form-control" multiple>
                                    @if(isset($patdrug) && count($patdrug) >0)
                                        @foreach($patdrug as $pd)
                                            <option value="{{$pd->fldid}}">{{$pd->fldcode}} </option>
                                        @endforeach
                                    @else
                                        <option value="">No Allergic Drugs Found</option>
                                    @endif
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Diagnosis</h4>
                        </div>
                        <div class="allergy-add">

                            @if((isset($enable_freetext) and $enable_freetext == 'Yes') || (isset($enable_freetext) and $enable_freetext == '1'))
                                <a href="javascript:void(0);" class="btn btn-primary" data-toggle="modal" data-target="#diagnosisfreetext" onclick="diagnosisfreetext.displayModal()"><i class="ri-add-fill"></i></a>
                            @else
                                <a href="javascript:void(0);" class="btn btn-primary">Free</a>
                            @endif

                            @if(isset($patient) and $patient->fldptsex == 'Female')
                                <a href="javascript:void(0);" class="btn btn-primary" id="pro_obstetric" data-toggle="modal" data-target="#diagnosis-obstetric-modal" onclick="obstetric.displayModal()">OBS</a>
                            @endif

                            <a href="javascript:void(0);" class="btn btn-warning" data-toggle="modal" data-target="#diagnosis">ICD</a>


                            <a href="javascript:void(0);" class="btn btn-danger" id="deletealdiagno"><i class="ri-delete-bin-6-line"></i></a>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <form action="" class="form-horizontal">
                            <div class="form-group mb-0">
                                <select name="" id="select-multiple-diagno" class="form-control" multiple>
                                    @if(isset($patdiago) and count($patdiago) > 0)
                                        @foreach($patdiago as $patdiag)
                                            <option value="{{$patdiag->fldid}}">{{$patdiag->fldcode}}</option>
                                        @endforeach
                                    @else
                                        <option value="">No Diagnosis Found</option>
                                    @endif
                                </select>
                            </div>
                        </form>

                    </div>
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Past Diagnosis</h4>
                        </div>

                    </div>
                    <div class="iq-card-body">
                        <form action="" class="form-horizontal">
                            <div class="form-group mb-0">
                                <select name="" id="select-multiple-diagno" class="form-control" multiple>
                                    @if(isset($patdiago) and count($patdiago) > 0)
                                        @foreach($patdiago as $patdiag)
                                            <option value="{{$patdiag->fldid}}">{{$patdiag->fldcode}}</option>
                                        @endforeach
                                    @else
                                        <option value="">No Diagnosis Found</option>
                                    @endif
                                </select>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <div class="history-tab">
                            <input type="hidden" name="note_tabs" class="note_tabs" value="{{ route('save_note_tabs') }}"/>
                            <ul class="nav nav-tabs justify-content-center" id="myTab-2" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#history1" role="tab" aria-controls="history" aria-selected="true">History</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#advice1" role="tab" aria-controls="advice" aria-selected="false">Advice</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#sensitive1" role="tab" aria-controls="sensitive1" aria-selected="false">Sensitive Note</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#fluid1" role="tab" aria-controls="fluid" aria-selected="false">Fluid</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="history1" role="tabpanel" aria-labelledby="history">
                                    <div class="iq-card-header d-flex justify-content-between">
                                        <div class="iq-header-title">
                                            <h4 class="card-title">History of Illness</h4>
                                        </div>
                                        <button type="button" class="btn btn-primary btn-sm float-right save_history {{ $disableClass }}" old_id="@if(isset($history)) {{ $history->fldid }} @endif"><i class="ri-check-fill"></i></button>
                                    </div>
                                    <div class="form-group mb-0">
                                            <textarea name="history" id="history" class="form-control">
                                               @if(isset($history)) {{ $history->flddetail }} @endif
                                           </textarea>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="advice1" role="tabpanel" aria-labelledby="advice">
                                    <div class="iq-card-header d-flex justify-content-between">
                                        <div class="iq-header-title">
                                            <h4 class="card-title">Advice</h4>

                                        </div>
                                        <button type="button" class="btn btn-primary btn-sm float-right save_advice {{ $disableClass }} " old_id="@if(isset($notes)) {{ $notes->fldid }} @endif"><i class="ri-check-fill"></i></button>
                                    </div>
                                    <div class="form-group mb-0">
                                        <textarea name="advice" id="advice">@if(isset($notes)) {{ $notes->flddetail }} @endif</textarea>
                                    </div>

                                </div>
                                <div class="tab-pane fade" id="sensitive1" role="tabpanel" aria-labelledby="sensitive">
                                    <div class="iq-card-header d-flex justify-content-between">
                                        <div class="iq-header-title">
                                            <h4 class="card-title">Sensitive </h4>

                                        </div>
                                        <button type="button" id="save_sensitive" class="btn btn-primary btn-sm float-right  {{ $disableClass }} " old_id="@if(isset($sensitive)) {{ $sensitive->fldid }} @endif"><i class="ri-check-fill"></i></button>
                                    </div>
                                    <div class="form-group mb-0">
                                        <textarea name="sensitive" id="sensitive">@if(isset($sensitive)) {{ $sensitive->flddetail }} @endif</textarea>
                                    </div>

                                </div>
                                <div class="tab-pane fade" id="fluid1" role="tabpanel" aria-labelledby="fluid">
                                    <div class="iq-card-header d-flex justify-content-between">
                                        <div class="iq-header-title">
                                            <h4 class="card-title">IV Fluid</h4>
                                        </div>
                                    </div>
                                    <div class="history-tab-content">
                                        <table class="table table-hovered table-bordered table-striped mb-3">
                                            <thead>
                                            <tr>
                                                <th>Start Date</th>
                                                <th>Medicine</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if(isset($fluid_list) && $fluid_list)

                                                @forelse($fluid_list as $fluid)
                                                    <tr>
                                                        <td>{{ $fluid->fldstarttime ?? null }}</td>
                                                        <td>{{ $fluid->flditem ?? null }}</td>
                                                        <td class="text-center"><a type="button "
                                                                                   title="Start"
                                                                                   class="btn check_btn prevent fluid_button {{ $disableClass }}"
                                                                                   data-toggle="modal"
                                                                                   data-id="{{ $fluid->fldid  }}"
                                                                                   data-target="#fluidModal"
                                                                                   id="fluid_start_btn"
                                                                                   data-medicine="{{ $fluid->flditem }}"
                                                                                   data-dose="{{ $fluid->flddose  }}"
                                                                                   data-frequency=" {{ $fluid->fldfreq }}"
                                                                                   data-days=" {{ $fluid->flddays }} "
                                                                                   data-status=" {{ $fluid->fldstatus }} "
                                                                                   data-start_time=" {{ $fluid->fldstarttime }}">
                                                                <i class="fas fa-play"></i>
                                                            </a>
                                                            <a type="button " class="btn check_btn prevent"
                                                               style="display: none;" id="fluid_pause_btn"
                                                               title="Pause">
                                                                <i class="fas fa-pause"></i>
                                                            </a>
                                                            <a type="button " class="btn check_btn prevent"
                                                               style="display: none;" id="fluid_stop_btn"
                                                               title="Stop">
                                                                <i class="fas fa-stop"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="3"> There is no fluid dispensed!!</td>
                                                    </tr>
                                                @endforelse
                                            @else
                                                <tr>
                                                    <td colspan="3"> There is no fluid dispensed!!</td>
                                                </tr>
                                            @endif
                                            </tbody>
                                        </table>
                                        <table class="table table-hovered table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th>Particulars</th>
                                                <th>Rate</th>
                                                <th>Unit</th>
                                                <th>Start</th>
                                                <th>End</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody id="fluid_particulars_body">
                                            @if(isset($fluid_particulars))
                                                @forelse( $fluid_particulars as $particulars)
                                                    <tr>
                                                        <td>{{ $particulars->getName->flditem ?? null }}</td>
                                                        <td>{{ $particulars->fldvalue ?? null }}</td>
                                                        <td>{{ $particulars->fldunit ?? null }}</td>
                                                        <td>{{ $particulars->fldfromtime ?? null }}</td>
                                                        <td>{{ $particulars->fldtotime ?? null }}</td>
                                                        <td>
                                                            @if( $particulars->fldstatus =='ongoing')
                                                                <button type="button" class="fluid_stop_btn {{ $disableClass }}" data-stop_id="{{ $particulars->fldid ?? null }}" data-dose_no="{{ $particulars->flddose ?? null }}"><i class="fas fa-stop"></i></button>
                                                            @elseif( $particulars->fldstatus =='stopped')
                                                                <button type="button"><i class="fas fa-lock"></i></button>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty

                                                @endforelse
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

        </div>

        <div class="modal fade" id="fluidModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title" id="fluid_title"></h4>
                        <button type="button" class="close" data-dismiss="modal">&times;
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <table class="table table-bordered">
                            <thead>
                            <th>Start Date</th>
                            <th>Medicine</th>
                            <th>Dose</th>
                            <th>Frequency</th>
                            <th>Days</th>
                            <th>Status</th>

                            </thead>
                            <tbody id="fluid_table_body"></tbody>


                        </table>
                        <table>
                            <tr>
                                <td><label>Enter rate of Administration in ML/Hour: </label>
                                </td>
                                <td><input type="text" class="form-control" id="fluid_dose">
                                </td>
                                <td><label id="empty_dose_alert" style="color: red;"></label></td>
                            </tr>
                        </table>
                    </div>
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger {{ $disableClass }}" id="fluid_modal_save_btn">Save
                        </button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="allergicdrugs" tabindex="-1" role="dialog" aria-labelledby="allergicdrugsLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="allergicdrugsLabel" style="text-align: center;">Select Drugs</h5>
                        <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="allergyform">
                        <div class="modal-body">

                            <input type="hidden" id="patientID" name="patient_id" value="@if(isset($enpatient)){{ $enpatient->fldencounterval }}@endif">
                            <!-- <div class="form-group form-row align-items-center" style="position: sticky;top: -16px;z-index: 9;background: #fff;padding: 5px 0;">
                                <label for="" class="col-sm-2"><i class="ri-filter-2-fill"></i> Filter</label>
                                <div class="col-sm-10">
                                    <select name="" class="form-control">
                                        <option value="A">A</option>
                                        <option value="B">B</option>
                                        <option value="C">C</option>
                                        <option value="D">D</option>
                                        <option value="E">E</option>
                                        <option value="F">F</option>
                                        <option value="G">G</option>
                                        <option value="H">H</option>
                                        <option value="I">I</option>
                                        <option value="J">J</option>
                                        <option value="K">K</option>
                                        <option value="L">L</option>
                                        <option value="M">M</option>
                                        <option value="N">N</option>
                                        <option value="O">O</option>
                                        <option value="P">P</option>
                                        <option value="Q">Q</option>
                                        <option value="R">R</option>
                                        <option value="S">S</option>
                                        <option value="T">T</option>
                                        <option value="U">U</option>
                                        <option value="V">V</option>
                                        <option value="W">W</option>
                                        <option value="X">X</option>
                                        <option value="Y">Y</option>
                                        <option value="Z">Z</option>
                                    </select>
                                </div>
                            </div> -->
                            <div class="form-group mb-0">
                                <input type="text" id="allergy-input-search" class="form-control" onkeyup="allergySearch()" placeholder="Search for allergy...">
                            </div>
                            <div id="allergicdrugss" class="res-table">
                                <ul class="list-group" id="allergy-javascript-search">
                                    <!-- <div id="searchresult"></div> -->
                                    @if(isset($allergicdrugs) and count($allergicdrugs) > 0)
                                        @foreach($allergicdrugs as $ad)
                                            <li class="list-group-item"><input type="checkbox" value="{{$ad->fldcodename}}" class="fldcodename" name="allergydrugs[]"/> <span>{{$ad->fldcodename}}</span></li>
                                        @endforeach
                                    @else
                                        <li class="list-group-item">No Drugs Available</li>
                                    @endif
                                </ul>
                            </div>
                            <!-- <div class="col-md-2 modal_container">
                                <p>Filter</p>
                                <ul class="list-unstyled side_list" style="width:45px;">
                                    <li><input type="checkbox" name="alpha" value="A" class="alphabet"/>&nbsp;A</li>
                                    <li><input type="checkbox" name="alpha" value="B" class="alphabet"/>&nbsp;B</li>
                                    <li><input type="checkbox" name="alpha" value="C" class="alphabet"/>&nbsp;C</li>
                                    <li><input type="checkbox" name="alpha" value="D" class="alphabet"/>&nbsp;D</li>
                                    <li><input type="checkbox" name="alpha" value="E" class="alphabet"/>&nbsp;E</li>
                                    <li><input type="checkbox" name="alpha" value="F" class="alphabet"/>&nbsp;F</li>
                                    <li><input type="checkbox" name="alpha" value="G" class="alphabet"/>&nbsp;G</li>
                                    <li><input type="checkbox" name="alpha" value="H" class="alphabet"/>&nbsp;H</li>
                                    <li><input type="checkbox" name="alpha" value="I" class="alphabet"/>&nbsp;I</li>
                                    <li><input type="checkbox" name="alpha" value="J" class="alphabet"/>&nbsp;J</li>
                                    <li><input type="checkbox" name="alpha" value="K" class="alphabet"/>&nbsp;K</li>
                                    <li><input type="checkbox" name="alpha" value="L" class="alphabet"/>&nbsp;L</li>
                                    <li><input type="checkbox" name="alpha" value="M" class="alphabet"/>&nbsp;M</li>
                                    <li><input type="checkbox" name="alpha" value="N" class="alphabet"/>&nbsp;N</li>
                                    <li><input type="checkbox" name="alpha" value="O" class="alphabet"/>&nbsp;O</li>
                                    <li><input type="checkbox" name="alpha" value="P" class="alphabet"/>&nbsp;P</li>
                                    <li><input type="checkbox" name="alpha" value="Q" class="alphabet"/>&nbsp;Q</li>
                                    <li><input type="checkbox" name="alpha" value="R" class="alphabet"/>&nbsp;R</li>
                                    <li><input type="checkbox" name="alpha" value="S" class="alphabet"/>&nbsp;S</li>
                                    <li><input type="checkbox" name="alpha" value="T" class="alphabet"/>&nbsp;T</li>
                                    <li><input type="checkbox" name="alpha" value="U" class="alphabet"/>&nbsp;U</li>
                                    <li><input type="checkbox" name="alpha" value="V" class="alphabet"/>&nbsp;V</li>
                                    <li><input type="checkbox" name="alpha" value="W" class="alphabet"/>&nbsp;W</li>
                                    <li><input type="checkbox" name="alpha" value="X" class="alphabet"/>&nbsp;X</li>
                                    <li><input type="checkbox" name="alpha" value="Y" class="alphabet"/>&nbsp;Y</li>
                                    <li><input type="checkbox" name="alpha" value="Z" class="alphabet"/>&nbsp;Z</li>
                                </ul>
                            </div> -->
                        </div>
                        <div class="modal-footer">

                            <button type="button" class="btn btn-primary" onclick="saveAllergyDrugs()"> Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="diagnosis" tabindex="-1" role="dialog" aria-labelledby="allergicdrugsLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="allergicdrugsLabel">ICD10 Database</h5>
                        <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="post" id="opd-diagnosis">
                        @csrf
                        <div class="modal-body">

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-2">Group</label>
                                        <div class="col-sm-8">
                                            <select name="" id="diagnogroup" class="form-control">
                                                <option value="">--Select Group--</option>
                                                @if(isset($diagnosisgroup) and count($diagnosisgroup) > 0)
                                                    @foreach($diagnosisgroup as $dg)
                                                        <option value="{{$dg->fldgroupname}}">{{$dg->fldgroupname}}</option>
                                                    @endforeach
                                                @else
                                                    <option value="">Groups Not Available</option>
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-sm-1">
                                            <a href="javascript:void(0);" class=" button btn btn-primary" id="searchbygroup"><i class="ri-refresh-line"></i></a>
                                        </div>
                                        <div class="col-sm-1">
                                            <a href="#" class="button btn btn-danger" id="closesearchgroup"><i class="ri-close-fill"></i></a>
                                        </div>
                                    </div>
                                    <div id="diagnosiss">
                                        <div class="form-group form-row align-items-center">
                                            <!-- <label for="" class="col-sm-2">Search</label> -->
                                            <!-- <div class="col-sm-10">
                                                <input type="text" name="" palceholder="Search" class="form-control">
                                            </div> -->
                                        </div>
                                        <div class="icd-datatable">
                                            <table class="table table-bordered table-striped table-hover" id="top-req ">
                                                <thead>
                                                <tr>
                                                    <th>S.No</th>
                                                    <th>Code</th>
                                                    <th>Name</th>
                                                </tr>
                                                </thead>
                                                <tbody id="diagnosiscat">
                                                @forelse($diagnosiscategory as $dc)
                                                    <tr>
                                                        <td><input type="checkbox" class="dccat" name="dccat" value="{{$dc['code']}}"></td>
                                                        <td>{{$dc['code']}}</td>
                                                        <td>{{$dc['name']}}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center">
                                                            <em>No data available in table ...</em>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-2">Search</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="search_diagnosis_sublist" id="search_diagnosis_sublist" placeholder="Search" class="form-control">
                                        </div>
                                    </div>
                                    <div class="table-responsive table-scroll-icd">
                                        <table class=" table table-bordered table-striped table-hover" id=" top-req">
                                            <thead>
                                            <tr>
                                                <th>Code</th>
                                                <th>Name</th>
                                            </tr>
                                            </thead>
                                            <tbody id="sublist">

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="form-group form-row align-items-center mt-2">
                                        <label for="" class="col-sm-2">Code</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="code" id="code" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-2">Text</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="diagnosissubname" id="diagnosissubname" class="form-control">
                                            <input type="hidden" name="patient_id" value="@if(isset($enpatient)){{ $enpatient->fldencounterval }}@endif">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="submitallergydrugs" onclick="updateDiagnosis()">Submit</button>
                            <!-- <input type="submit" name="submit" id="submitallergydrugs" class="btn btn-primary" value="Submit"> -->
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <div class="d-flex justify-content-around">
                            <a href="javascript:void(0)" onclick="laboratory.displayModal()" class="btn btn-primary btn-action  {{ $disableClass }}">Laboratory
                            </a>
                            <a href="javascript:void(0)" onclick="radiology.displayModal()" class="btn btn-primary btn-action  {{ $disableClass }}">Radiology
                            </a>

                            <a href="javascript:void(0)" onclick="pharmacy.displayModal()" class="btn btn-primary btn-action  {{ $disableClass }}">Pharmacy
                            </a>
                            <a href="javascript:void(0);" onclick="requestMenu.majorProcedureModal()" class="btn btn-primary btn-action  {{ $disableClass }}">Procedure
                            </a>
                        <!--                            <a href="{{ route('outpatient.history.generate', $patient->fldpatientval??0) }}?opd" target="_blank" class="btn btn-primary btn-action {{ $disableClass }}">History
                            </a>-->
                            @if(isset($enpatient) && $enpatient->fldpatientval)
                                <a href="javascript:;" class="btn btn-primary btn-action {{ $disableClass }}" onclick="showHistoryPopup('{{ $enpatient->fldencounterval ?? '' }}')">History</a>
                            @endif
                            <a @if(isset($enpatient)) href="{{ route('outpatient.pdf.generate.opd.sheet', $enpatient->fldencounterval ?? 0) }}?opd" target="_blank" @else href="#" @endif class="btn btn-primary btn-action  {{ $disableClass }}">OPD Sheet
                            </a>
                            <a href="{{ route('reset.encounter') }}" onclick="return checkFormEmpty();" class="btn btn-primary btn-action  {{ $disableClass }}">Save
                            </a>
                            <a href="javascript:;" data-toggle="modal" onclick="finishBox()" id="finish" class="btn btn-primary btn-action  {{ $disableClass }}">Finish
                            </a>
                            <a href="#" data-toggle="modal" data-target="" class="btn btn-primary btn-action" onclick="showPreviewPopup('{{ $enpatient->fldencounterval ?? '' }}')">Preview
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('outpatient::modal.history')
        @include('outpatient::modal.preview')
        @include('outpatient::modal.finish-boxLabel-modal')
        @include('outpatient::modal.lnr-boxLabel-modal')
        @include('outpatient::modal.text-boxLabel-modal')
        @include('outpatient::modal.scale-boxLabel-modal')
        @include('outpatient::modal.number-boxLabel-modal')
        @include('outpatient::modal.single-selection-box-modal')

        @include('outpatient::modal.laboratory-radiology-modal')
        @include('outpatient::modal.diagnosis-freetext-modal')
        @include('outpatient::modal.allergy-freetext-modal')

        @include('outpatient::modal.diagnosis-obstetric-modal')
        @include('outpatient::modal.opd-history-modal')


        @include('inpatient::layouts.modal.triage')
        @include('inpatient::layouts.modal.demographics', ['module' => 'opd'])
        @include('inpatient::layouts.modal.patient-image')
    </div>

    <div class="modal fade eeg-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">EEG</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="eeg-form">
                        <div class="form-group">
                            <textarea name="eeg-data" id="eeg-textarea-ckeditor" class="form-control">
                               <p><strong>History</strong></p>
                                <p>&nbsp;</p>
                                <p><strong>Medications</strong></p>
                                <p>&nbsp;</p>
                                <p><strong>Technical Description</strong></p>
                                <p>&nbsp;</p>
                                <p><strong>EEG Descritpion</strong></p>
                                <p>&nbsp;</p>
                                <p><strong>Description</strong></p>
                                <p>&nbsp;</p>
                                <p><strong>Some Head cant read in Jira</strong></p>
                                <p>&nbsp;</p>
                                <p><strong>Signature</strong></p>
                                <p><strong>Designation</strong>:&nbsp;</p>
                            </textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="saveEegFormData()">Save</button>
                </div>
            </div>
        </div>
    </div>
@stop
@push('after-script')
    <script src="{{ asset('js/outpatient_form.js')}}"></script>
    <script>
        CKEDITOR.replace('editor',
            {
                height: '350px',
            });
        CKEDITOR.replace('sensitive',
            {
                height: '350px',
            });

        CKEDITOR.replace('history',
            {
                height: '350px',
            });

        CKEDITOR.replace('advice',
            {
                height: '350px',
            });

        CKEDITOR.replace('texttobox',
            {
                height: '350px',
            });
        CKEDITOR.replace('eeg-textarea-ckeditor',
            {
                height: '500px',
            });


        $(document).ready(function () {

            setTimeout(function () {
                $(".flditem").select2({
                    theme: "default select2-chief-complaint"
                });
                $(".find_fldhead").select2({
                    theme: "default find_fldhead"
                });
            }, 1000);

            $(document).on("keydown", ".select2-search__field", function (event) {
                const target = event.target;
                // console.log(target);
                const containerClasses = target.closest('.select2-container').className;
                var keycode = (event.keyCode ? event.keyCode : event.which);

                if (keycode == '13') {
                    if (containerClasses.indexOf("select2-chief-complaint")> -1) {
                        $('#select2-chief-complaint').append('<option value="' + $(this).val() + '" selected >' + $(this).val() + '</option>');
                    } else if (containerClasses.indexOf("find_fldhead")> -1) {
                        // alert('You pressed a "endter" key in textbox');
                        $('#find_fldhead').append('<option value="' + $(this).val() + '" selected >' + $(this).val() + '</option>')
                        var encounter_id = $("#encounter_id").val();
                        $("#text_box").find(".modal_fldencounterval").val(encounter_id);
                        $("#text_box").find(".modal_flditem").val($(this).val());
                        $("#text_box").modal("show");
                    } else {
                        console.log('here');
                    }
                }

            });

            $("#save_sensitive").on("click", function () {
                var sensitive = CKEDITOR.instances.sensitive.getData();
                var url = $(".note_tabs").val();
                var fldencounterval = $("#fldencounterval").val();
                var flduserid = $("#flduserid").val();
                var fldcomp = $("#fldcomp").val();
                var old_id = $(this).attr("old_id");

                var formData = {
                    content: sensitive,
                    fldinput: "Sensitive Note",
                    flduserid: flduserid,
                    fldcomp: fldcomp,
                    fldencounterval: fldencounterval,
                    old_id: old_id
                };

                // console.log(formData);
                $.ajax({
                    url: url,
                    type: "POST",
                    dataType: "json",
                    data: formData,
                    success: function (data) {
                        if ($.isEmptyObject(data.error)) {
                            showAlert("Information saved!!");
                            //location.reload();
                        } else {
                            showAlert("Something went wrong!!", 'error');
                        }
                    }
                });
            });

            $(document).on("keydown", "#find_fldhead", function (e) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    alert('You pressed a "enter13" key in textbox');
                    $('#find_fldhead').append('<option value="' + $(this).val() + '" selected >' + $(this).val() + '</option>')
                    $("#text_box").find(".modal_fldencounterval").val(encounter_id);
                    $("#text_box").find(".modal_flditem").val(item);
                    $("#text_box").find(".modal_fldsysconst").val(fldsysconst);
                    $("#text_box").find(".modal_fldtype").val(fldtype);
                    $("#text_box").modal("show");
                }
            });

            /*$(document).on("keydown", ".select2-search__field", function (e) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    //alert('You pressed a "enter" key in textbox');
                    $('.flditem').append('<option value="' + $(this).val() + '" selected >' + $(this).val() + '</option>')
                }
            });*/

            $(document).on("keydown", "#select2-chief-complaint", function (e) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {

                    $('#select2-chief-complaint').append('<option value="' + $(this).val() + '" selected >' + $(this).val() + '</option>')
                }
            });

            $(document).on("click", ".delete_complaints", function () {

                current_user = $('.current_user').val();
                permit_user = $(this).attr('permit_user');
                if (current_user == permit_user) {
                    var cur = $(this);
                    var url = $(this).attr("url");
                    if (confirm("Are you sure?")) {


                        $.ajax({
                            url: url,
                            type: "GET",
                            dataType: "json",
                            success: function (data) {
                                if ($.isEmptyObject(data.error)) {
                                    cur.closest("tr").remove();
                                } else {
                                    showAlert("Something went wrong!!");
                                }
                            }
                        });

                    }
                } else {
                    showAlert('Authorization with  ' + permit_user);
                }


            });
        });

        $(document).on('click', '#js-outpatient-findings-tbody tr td:nth-child(5)', function () {
            updateExamObservation.displayModal(this, $(this).closest('tr').data('fldid'));
        });


        var diagnosisfreetext = {
            displayModal: function () {
                // alert('obstetric');
                // if($('encounter_id').val() == 0)
                // alert($('#encounter_id').val());
                if ($('#encounter_id').val() == "") {
                    alert('Please select encounter id.');
                    return false;
                }
                $.ajax({
                    url: '{{ route('patient.diagnosis.freetext') }}',
                    type: "POST",
                    data: {
                        encounterId: $('#encounter_id').val()
                    },
                    success: function (response) {
                        // console.log(response);
                        $('.form-data-diagnosis-freetext').html(response);
                        $('#diagnosis-freetext-modal').modal('show');
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });


            },
        }


        var opdHistory = {
            displayModal: function () {
                // if($('encounter_id').val() == 0)
                // alert($('#encounter_id').val());
                if ($('#encounter_id').val() == "") {
                    alert('Please select encounter id.');
                    return false;
                }
                $.ajax({
                    url: '{{ route('patient.opdhistory.form') }}',
                    type: "POST",
                    data: {
                        encounterId: $('#encounter_id').val()
                    },
                    success: function (response) {
                        // console.log(response);
                        $('.form-data-opdhistory').html(response);
                        $('#opd-history-modal').modal('show');
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });


            },
        }


        $(document).on('click', '#fluid_modal_save_btn', function () {
            if ($('#fluid_dose').val() == '') {
                $('#empty_dose_alert').text('Please end dose');
                $('#fluid_dose').focus();
            } else {

                var add_fluid_route = "<?php echo route('store.drug');  ?>";

                var id = $('.fluid_button').data('id');
                var value = $('#fluid_dose').val();
                var data_val = $('#fluid_table_body').find('input').attr('data-val');
                // return false;
                $.ajax({
                    url: add_fluid_route,
                    method: 'post',
                    data: {

                        id: id,
                        type: 'fluid',
                        status: 'ongoing',
                        value: value,
                        encounter: $('#encounter_id').val(),
                    },
                    success: function (data) {
                        console.log(data);
                        var particular_html = "";


                        var endtime = data.data.fldtotime ? data.data.fldtotime : '&nbsp;';
                        var name = data.data.name ? data.data.name : '&nbsp;';
                        particular_html += '<tr class="to_remove">';
                        particular_html += '<td>' + name + '</td>';
                        particular_html += '<td>' + data.data.fldvalue + '</td>';
                        particular_html += '<td>' + data.data.fldunit + '</td>';
                        particular_html += '<td>' + data.data.fldfromtime + '</td>';
                        particular_html += '<td class="endtime_js">' + endtime + '</td>';
                        particular_html += '<td><button type="button" class="fluid_stop_btn" data-stop_id = " ' + data.data.fldid + '" data-dose_no = "' + data.data.flddoseno + '"> <i class="fas fa-stop"></i></button></td>';
                        particular_html += '</tr>';

                        $('#fluid_particulars_body').append(particular_html);
                        $('#fluid_dose').val('');
                        $('#fluidModal').modal('toggle');
                        $('[data-id=' + data_val + ']').hide();

                    },
                    error: function (data) {
                        $('#drug_status_message').empty().text('Cannot Record now something went wrong.').css('color', 'red');

                    },
                })
            }
        });
        /**
         * Actions on stop button
         */
        $(document).on('click', '.fluid_stop_btn', function () {


            var tr_elem = $(this).closest('tr');
            var stop_fluid_route = "<?php echo route('stop.fluid'); ?>";
            var id = $(this).data('stop_id');
            var dose_no = $(this).data('dose_no');
            $.ajax({
                url: stop_fluid_route,
                method: 'post',
                data: {

                    id: id,
                    dose_no: dose_no,
                    encounter: $('#encounter_id').val(),
                },
                success: function (data) {
                    $(tr_elem).find('.endtime_js').text(data.data.fldtotime);
                    var btn_elem = $(tr_elem).find('button.fluid_stop_btn');

                    $(btn_elem).attr('class', '');
                    $(btn_elem).find('i').attr('class', 'fas fa-lock');


                    $(this).closest('.to_remove').remove();
                    return false;
                    $(elem).remove();
                    var particular_html = "";

                    var endtime = data.data.fldtotime ? data.data.fldtotime : '&nbsp;';

                    particular_html += '<td>' + endtime + '</td>';
                    particular_html += '<td><button type="button"><i class="fas fa-lock"></i></button></td>';

                    $('#fluid_particulars_body').append(particular_html);
                    $('#fluid_dose').val('');
                },
                error: function (data) {
                    $('#drug_status_message').empty().text('Cannot Record now something went wrong.').css('color', 'red');
                },
            })
        });

        var minorProcedure = {
            displayModal: function () {
                // if($('encounter_id').val() == 0)
                // alert($('#encounter_id').val());
                if ($('#encounter_id').val() == "") {
                    alert('Please select encounter id.');
                    return false;
                }
                $.ajax({
                    url: '{{ route('patient.minor.procedure.form') }}',
                    type: "POST",
                    data: {
                        encounterId: $('#encounter_id').val()
                    },
                    success: function (response) {
                        console.log(response);
                        $('.file-modal-title').empty();
                        $('.file-modal-title').text('Minor Procedure');
                        $('.file-form-data').html(response);
                        $('#file-modal').modal('handleUpdate');
                        $('#file-modal').modal('show');
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });


            },
        }

        var equipment = {
            displayModal: function () {
                // if($('encounter_id').val() == 0)
                // alert($('#encounter_id').val());
                if ($('#encounter_id').val() == "") {
                    alert('Please select encounter id.');
                    return false;
                }
                $.ajax({
                    url: '{{ route('patient.minor.equipment.form') }}',
                    type: "POST",
                    data: {
                        encounterId: $('#encounter_id').val()
                    },
                    success: function (response) {
                        console.log(response);
                        $('.file-modal-title').empty();
                        $('.file-modal-title').text('Minor Procedure');
                        $('.file-form-data').html(response);
                        $('#file-modal').modal('show');
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });


            },
        }


        var vaccination = {
            displayModal: function () {
                // if($('encounter_id').val() == 0)
                // alert($('#encounter_id').val());
                if ($('#encounter_id').val() == "") {
                    alert('Please select encounter id.');
                    return false;
                }
                $.ajax({
                    url: '{{ route('patient.vaccination.form') }}',
                    type: "POST",
                    data: {
                        encounterId: $('#encounter_id').val()
                    },
                    success: function (response) {
                        // console.log(response);
                        $('.file-modal-title').empty();
                        $('.file-modal-title').text('Vaccination Form');
                        $('.file-form-data').html(response);
                        $('#file-modal').modal('show');
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });
            },
        }

        $("#pulse_rate,#sys_bp,#dia_bp,#respi,#saturation,#pulse_rate_rate").on("focusout", function (e) {

            e.preventDefault();

            var value = $(this).val();
            var vital = $(this).attr('id');
            var $this = $(this);
            var high = $(this).attr('high');
            var low = $(this).attr('low');

            if (value >= high) {

                $this.removeClass('lowline');
                $this.addClass('highline');
            }

            if (value <= low) {
                $this.removeClass('highline');

                $this.addClass('lowline');
            }


        });

        $("#insert_general_exam").click(function () {

            var url = $(this).attr('url');
            var fldencounterval = $("#fldencounterval").val();


            var Pallor = $('.Pallor-plc:checked').val();
            var Icterus = $('.Icterus-plc:checked').val();
            var Cyanosis = $('.Cyanosis-plc:checked').val();
            var Clubbing = $('.Clubbing-plc:checked').val();
            var Oedema = $('.Oedema-plc:checked').val();
            var Dehydration = $('.Dehydration-plc:checked').val();


            var formData = {
                Pallor: Pallor,

                Icterus: Icterus,

                Cyanosis: Cyanosis,

                Clubbing: Clubbing,

                Oedema: Oedema,

                Dehydration: Dehydration,


                fldencounterval: fldencounterval,


            };


            $.ajax({
                url: url,
                type: "POST",
                dataType: "json",
                data: formData,
                success: function (data) {
                    if ($.isEmptyObject(data.error)) {
                        showAlert("Information saved!!");
                        //location.reload();
                    } else {
                        alert("Something went wrong!!");
                    }
                }
            });
        });

        function checkFormEmpty() {
            if ($("#encounter_id").val() === "") {
                showAlert('No encounter is selected.', 'error');
                return false;
            }
        }

        function allergySearch() {
            // Declare variables
            var input, filter, ul, li, a, i, txtValue;
            input = document.getElementById('allergy-input-search');
            filter = input.value.toUpperCase();
            ul = document.getElementById("allergy-javascript-search");
            li = ul.getElementsByTagName('li');

            // Loop through all list items, and hide those who don't match the search query
            for (i = 0; i < li.length; i++) {
                a = li[i].getElementsByTagName("span")[0];
                txtValue = a.textContent || a.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    li[i].style.display = "";
                } else {
                    li[i].style.display = "none";
                }
            }
        }

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

        function saveEegFormData() {
            if ($("#encounter_id").val() === "") {
                showAlert('No encounter is selected.', 'error');
                return false;
            }
            eegData = CKEDITOR.instances['eeg-textarea-ckeditor'].getData();
            encounter = $("#encounter_id").val();

            $.ajax({
                url: "{{ route('eeg.save') }}",
                type: "post",
                data: {eegData: eegData, encounter: encounter},
                success: function (response) {
                    if (response.success) {
                        let route = "{!! route('eeg.print', ['encounter' => ':ENCOUNTER_ID','eeg' => ':EEG_ID']) !!}";
                        route = route.replace(':ENCOUNTER_ID', encounter);
                        route = route.replace(':EEG_ID', response.insertDataId);
                        showAlert('Successfully saved eeg.');
                        window.open(route, '_blank');
                        $(".eeg-modal").modal('hide');
                    } else {
                        showAlert('Something went wrong.', 'error');
                    }

                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }

        function showPreviewPopup(encounter_id) {
            if (encounter_id == "") {
                showAlert('No patient selected.', 'error');
                return false;
            }
            let route = "{!! route('outpatient.preview', ['encounter_id' => ':ENCOUNTER_ID']) !!}";
            route = route.replace(':ENCOUNTER_ID', encounter_id);
            $.ajax({
                url: route,
                type: "GET",
                success: function (response) {
                    // console.log(response);
                    $('.preview-modal-content').empty();
                    $('.preview-modal-content').html(response);
                    $('#preview-modal').modal('show');
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                }
            });
        }

        function finishBox(){
            if ($("#encounter_id").val() === "") {
                showAlert('No encounter is selected.', 'error');
                return false;
            }
            let url ="{!! Request::fullUrl() !!}";
            let url_split=url.split('/');
            let url_segment_first=url_split[3];
            let route = "{!! route('outpatient.finish.box')!!}";
            $.ajax({
                url: route,
                type: "GET",
                data: {url_segment_first},
                success: function (response) {
                    $('.finish-modal-content').empty();
                    $('.finish-modal-content').html(response);
                    $('#finish_box').modal('show');
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                }
            });
        }
        //function to trigger waiting list after clincking the tick when clicking the finsh button
        $(function () {
            if ( sessionStorage.getItem('save_for_waitingform_trigger') ) {
                fileMenu.waitingModalDisplay();
                sessionStorage.removeItem('save_for_waitingform_trigger');
            }
        });
    </script>

@endpush
