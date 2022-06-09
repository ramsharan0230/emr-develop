@extends('frontend.layouts.master') @section('content')

    @if(isset($patient_status_disabled) && $patient_status_disabled == 1 )
        @php
            $disableClass = 'disableInsertUpdate';
        @endphp
    @else
        @php
            $disableClass = '';
        @endphp
    @endif
    @include('menu::common.opdneuro-nav')



<div class="container-fluid">
    @include('frontend.common.patientProfile')


    <div class="col-sm-12">
        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-body">
                <div id="accordion">
                    <div class="accordion-nav">
                        <ul>
                            <li><a href="#" data-toggle="collapse" data-target="#chief-complaint" aria-expanded="true"
                                   aria-controls="collapseOne">Chief Complaints</a></li>
                        </ul>
                    </div>

                    @include('eye::modal.chiefComplaints')

                </div>
            </div>
        </div>
    </div>












    <form method="post" action="{{ route('opdneuro.store') }}">

        @csrf
        <input type="hidden" name="encounter_id" value="{{ isset($encounter_no) ? $encounter_no : 0 }}" id="encounter_no">

    <div class="row">
        <div class="col-sm-4">

            <div class="iq-card iq-card-block">
                <div class="iq-card-body">
                    <table class="table-neuro">
                        <thead style="color: #585a5c;">
                            <tr>
                                <th class="width-a table-neuro-th">GCS: E</th>
                                <th><input type="number" class="form-control" placeholder="4" name="gcs_e" id="gcs_e"/></th>
                                <th class="width-a"><i class="fa fa-arrow-up text-success" aria-hidden="true" onclick="increaseValue('gcs_e')"></i>&nbsp;&nbsp;<i class="fa fa-arrow-down text-danger" onclick="decreaseValue('gcs_e')"></i>&nbsp;V</th>
                                <th><input type="number" class="form-control" placeholder="5" name="gcs_v" id="gcs_v" /></th>
                                <th class="width-a"><i class="fa arp"></i>&nbsp;&nbsp;<i class="fa fa-arrow-down text-danger" onclick="decreaseValue('gcs_v')"></i>&nbsp;M</th>
                                <th><input type="number" class="form-control" placeholder="6" name="gcs_m" id="gcs_m" /></th>
                                <th class="width-a"><i class="fa fa-arrow-up text-success" onclick="increaseValue('gcs_m')"></i>&nbsp;&nbsp;<i class="fa fa-arrow-down text-danger" onclick="decreaseValue('gcs_m')"></i>&nbsp;</th>
                            </tr>
                            <tr>
                                <th colspan="3" class="table-neuro-th">Cranial Nerves:</th>
                                <th colspan="4" class="font-color"><input type="radio" class="custom-radio-neuro" name="cranial_nerves_right"  value="abnormal"/>&nbsp;Abnormal&nbsp; <input type="radio" class="custom-radio-neuro" name="cranial_nerves_right" value="normal"/>&nbsp;Normal</th>
                            </tr>
                            <tr>
                                <th colspan="3" class="table-neuro-th">Nystagmus:</th>
                                <th colspan="4" class="font-color"><input type="radio" class="custom-radio-neuro" name="nystagmus_right" value="absent" />&nbsp;Absent &nbsp;&nbsp;&nbsp;&nbsp; <input type="radio" class="custom-radio-neuro" name="nystagmus_right" value="present" />&nbsp;Present</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="iq-card iq-card-block">
                <div class="iq-card-body">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="form-row col-12 p-0">
                            <label class="col-sm-8">Motor Power</label>
                            <label class="col-sm-4">Sensory</label>
                        </div>
                    </div>
                    <table class="table-neuro">
                        <thead style="color: #585a5c;">
                            <tr>
                                <th class="width-a table-neuro-th">proxim</th>
                                <th class="width-c"><input type="number" class="form-control" placeholder="4" name="proxim_right" id="proxim_right"/></th>
                                <th class="width-c"><i class="fa fa-arrow-up text-success" aria-hidden="true" onclick="increaseValue('proxim_right')"></i>&nbsp;&nbsp;<i class="fa fa-arrow-down text-danger" onclick="decreaseValue('proxim_right')"></i></th>
                                <th class="table-neuro-th">C5</th>
                                <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="c_five_right" value="abnormal" />&nbsp;Abnorm</th>
                                <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="c_five_right" value="normal" />&nbsp;Norm</th>
                            </tr>
                            <tr>
                                <th class="width-a table-neuro-th">Distal</th>
                                <th class="width-c"><input type="number" class="form-control" placeholder="4" name="distal_right" id="distal_right" /></th>
                                <th class="width-c"><i class="fa fa-arrow-up text-success" aria-hidden="true"  onclick="increaseValue('distal_right')"></i>&nbsp;&nbsp;<i class="fa fa-arrow-down text-danger" onclick="decreaseValue('distal_right')"></i></th>
                                <th class="table-neuro-th">C6</th>
                                <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="c_six_right" value="abnormal" />&nbsp;Abnorm</th>
                                <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="c_six_right" value="normal" />&nbsp;Norm</th>
                            </tr>
                            <tr>
                                <th class="width-a table-neuro-th"></th>
                                <th class="width-c"></th>
                                <th class="width-c"></th>
                                <th class="table-neuro-th">C7</th>
                                <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="c_seven_right" value="abnormal" />&nbsp;Abnorm</th>
                                <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="c_seven_right" value="normal" />&nbsp;Norm</th>
                            </tr>
                            <r>
                                <th class="width-a table-neuro-th"></th>
                                <th class="width-c"></th>
                                <th class="width-c"></th>
                                <th class="table-neuro-th">C8</th>
                                <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="c_eight_right" value="abnormal" />&nbsp;Abnorm</th>
                                <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="c_eight_right" value="normal" />&nbsp;Norm</th>
                            <r>
                            <tr>
                                <th colspan="4" class="table-neuro-th">Finger Nose Test</th>
                                <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="finger_nose_right" value="abnormal" />&nbsp;Abnorm</th>
                                <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="finger_nose_right" value="normal" />&nbsp;Norm</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="iq-card iq-card-block">
                <div class="iq-card-body">
                    <table class="table-neuro">
                        <thead style="color: #585a5c;">
                            <tr>
                                <th class="table-neuro-th">Bicep Jerk</th>
                                <th class="width-c"><input type="number" class="form-control" placeholder="4" name="bicep_jerk_right" id="bicep_jerk_right" /></th>
                                <th class="width-c"><i class="fa fa-arrow-up text-success" aria-hidden="true" onclick="increaseValue('bicep_jerk_right')"></i>&nbsp;&nbsp;<i class="fa fa-arrow-down text-danger" onclick="decreaseValue('bicep_jerk_right')"></i></th>
                                <th class="table-neuro-th">Supi Jerk</th>
                                <th class="width-c"><input type="number" class="form-control" placeholder="4" name="supi_jerk_right"  id="supi_jerk_right"/></th>
                                <th class="width-c"><i class="fa fa-arrow-up text-success" aria-hidden="true" onclick="increaseValue('supi_jerk_right')"></i>&nbsp;&nbsp;<i class="fa fa-arrow-down text-danger" onclick="decreaseValue('supi_jerk_right')"></i></th>
                            </tr>
                            <tr>
                                <th class="table-neuro-th">Tricep Jerk</th>
                                <th class="width-c"><input type="number" class="form-control" placeholder="4" name="tricep_jerk_right"  id="tricep_jerk_right"/></th>
                                <th class="width-c"><i class="fa fa-arrow-up text-success" aria-hidden="true" onclick="increaseValue('tricep_jerk_right')"></i>&nbsp;&nbsp;<i class="fa fa-arrow-down text-danger" onclick="decreaseValue('tricep_jerk_right')"></i></th>
                            </tr>
                        </thead>
                    </table>
                    <table>
                        <thead style="color: #585a5c;">
                            <tr>
                                <th class="table-neuro-th">FLAIR</th>
                                <th class="font-color"><input type="radio" class="custom-radio-neuro" name="flair_right" value="abnormal" />&nbsp;Abnormal</th>
                                <th class="font-color"><input type="radio" class="custom-radio-neuro" name="flair_right" value="normal" />&nbsp;Normal</th>
                            </tr>
                            <tr>
                                <th class="table-neuro-th">FABER</th>
                                <th class="font-color"><input type="radio" class="custom-radio-neuro" name="faber_right" value="abnormal" />&nbsp;Abnormal</th>
                                <th class="font-color"><input type="radio" class="custom-radio-neuro" name="faber_right" value="normal" />&nbsp;Normal</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="iq-card iq-card-block">
                <div class="iq-card-body">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="form-row col-12 p-0">
                            <label class="col-sm-8">Motor Power</label>
                            <label class="col-sm-4">Sensory</label>
                        </div>
                    </div>
                    <table class="table-neuro">
                        <thead style="color: #585a5c;">
                            <tr>
                                <th class="width-a" rowspan="2">proxim</th>
                                <th class="width-c" rowspan="2"><input type="number" class="form-control" placeholder="4" name="proxim_right_heel" id="proxim_right_heel" /></th>
                                <th class="width-c" rowspan="2"><i class="fa fa-arrow-up text-success" aria-hidden="true" onclick="increaseValue('proxim_right_heel')" ></i>&nbsp;&nbsp;<i class="fa fa-arrow-down text-danger" onclick="decreaseValue('proxim_right_heel')"></i></th>
                                <th class="table-neuro-th">L2</th>
                                <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="l_two_right" value="abnormal" />&nbsp;Abnorm</th>
                                <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="l_two_right" value="normal" />&nbsp;Norm</th>
                            </tr>
                            <tr>

                                <th class="table-neuro-th">L3</th>
                                 <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="l_three_right" value="abnormal" />&nbsp;Abnorm</th>
                                <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="l_three_right" value="normal" />&nbsp;Norm</th>
                            </tr>
                            <tr>
                                <th class="width-a">Distal</th>
                                <th class="width-c"><input type="number" class="form-control" placeholder="4" name="distal_right_heel"  id="distal_right_heel"/></th>
                                <th class="width-c"><i class="fa fa-arrow-up text-success" aria-hidden="true" onclick="increaseValue('distal_right_heel')" ></i>&nbsp;&nbsp;<i class="fa fa-arrow-down text-danger" onclick="decreaseValue('distal_right_heel')"></i></th>
                                <th class="table-neuro-th">L4</th>
                                <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="l_four_right" value="abnormal" />&nbsp;Abnorm
                                </th>
                                <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="l_four_right" value="normal" />&nbsp;Norm</th>
                            </tr>
                            <tr>
                                <th class="width-a">EHL</th>
                                <th class="width-c"><input type="number" class="form-control" placeholder="4" name="ehl_right" id="ehl_right_heel" /></th>
                                <th class="width-c"><i class="fa fa-arrow-up text-success" aria-hidden="true" onclick="increaseValue('ehl_right_heel')"></i>&nbsp;&nbsp;<i class="fa fa-arrow-down text-danger" onclick="decreaseValue('ehl_right_heel')"></i></th>
                                <th class="table-neuro-th">L5</th>
                                <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="l_five_right" value="abnormal" />&nbsp;Abnorm</th>
                                <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="l_five_right" value="normal" />&nbsp;Norm</th</tr>
                            <tr>
                                <th class="width-a" rowspan="2">FHL</th>
                                <th class="width-c" rowspan="2"><input type="number" class="form-control" placeholder="4" name="fhl_right" id="fhl_right_heel" /></th>
                                <th class="width-c" rowspan="2"><i class="fa fa-arrow-up text-success" aria-hidden="true" onclick="increaseValue('fhl_right_heel')" ></i>&nbsp;&nbsp;<i class="fa fa-arrow-down text-danger" onclick="decreaseValue('fhl_right_heel')"></i></th>
                                <th class="table-neuro-th">S1</th>
                                <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="s_one_right" value="abnormal" />&nbsp;Abnorm</th>
                                <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="s_one_right" value="normal" />&nbsp;Norm</th>
                            </tr>
                            <tr>
                                <th class="table-neuro-th">S2</th>
                                <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="s_two_right" value="abnormal" />&nbsp;Abnorm</th>
                                <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="s_two_right" value="normal" />&nbsp;Norm</th>
                            </tr>
                            <tr>
                                <th colspan="4">Heel-Shin Test</th>
                                <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="heel_shin_right"  value="abnormal"/>&nbsp;Abnorm</th>
                                <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="heel_shin_right" value="normal" />&nbsp;Norm</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="iq-card iq-card-block">
                <div class="iq-card-body">
                    <table class="table-neuro">
                        <thead style="color: #585a5c;">
                            <tr>
                                <th class="table-neuro-th">Knee Jerk</th>
                                <th class="width-c"><input type="number" class="form-control" placeholder="4" name="knee_jerk_right" id="knee_jerk_right" /></th>
                                <th class="width-c"><i class="fa fa-arrow-up text-success" aria-hidden="true" onclick="increaseValue('knee_jerk_right')"></i>&nbsp;&nbsp;<i class="fa fa-arrow-down text-danger" onclick="decreaseValue('knee_jerk_right')"></i></th>
                                <th class="table-neuro-th">Ankle Jerk</th>
                                <th class="width-c"><input type="number" class="form-control" placeholder="4" name="ankel_jerk_right" id="ankel_jerk_right" /></th>
                                <th class="width-c"><i class="fa fa-arrow-up text-success" aria-hidden="true" onclick="increaseValue('ankel_jerk_right')"></i>&nbsp;&nbsp;<i class="fa fa-arrow-down text-danger" onclick="decreaseValue('ankel_jerk_right')"></i></th>
                            </tr>
                            <tr>
                                <th colspan="1">SLR</th>
                                <th colspan="3">
                                    <div class="progress mt-1">
                                        <div class="progress-bar progress-bar-striped bg-info" role="progressbar" style="width: 50%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </th>
                                <th colspan="3"><input type="number" class="form-control" placeholder="4" name="slr_right" /></th>
                            </tr>
                        </thead>
                    </table>
                    <table>
                       <thead style="color: #585a5c;">
                        <tr>
                            <th class="table-neuro-th">Planter Response</th>
                            <th class="font-color"><input type="radio" class="custom-radio-neuro" name="planter_response_right" value="downgoing" />&nbsp;Downgoing</th>
                            <th class="font-color"><input type="radio" class="custom-radio-neuro" name="planter_response_right" value="upgoing" />&nbsp;Upgoing</th>
                        </tr>
                        <tr>
                            <th><button  type="button" class="btn btn-primary mt-2"><i class="fa fa-image"></i>&nbsp;Draw</button></th>
                            <th><button type="button" class="btn btn-primary mt-2"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" ><i class="fa fa-server"></i>&nbsp;Vital</button>

                                <div class="dropdown-menu" style="padding: 10px; margin: 0 0 0 0; width: 250px;">
                                    <table style="width: 100%;">
                                        <tbody>
                                            <tr>
                                                <td style="width: 50%;">
                                                    <label for="">Pulse Rate</label>
                                                </td>
                                                <td style="width: 50%;">
                                                    <input type="number" class="form-control" placeholder="" name="pulse_rate" autocomplete="off"/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label for="">Syst BP</label>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control input-blood-pressure" id="input-syst-bp" placeholder="" name="syst_bp" autocomplete="off"/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label for="">Dyst BP</label>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control input-blood-pressure" id="input-dyst-bp" placeholder="" name="dyst_bp" autocomplete="off"/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label for="">SPO<small>2</small></label>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control" placeholder="" name="spo" autocomplete="off"/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label for="">Temp</label>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control" placeholder="" name="temp" autocomplete="off"/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label for="">Respiratory</label>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control" placeholder="" name="respiratory" autocomplete="off"/>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </th>
                            <!-- Vitals Popup -->




                            <th><button type="button" class="btn btn-primary mt-2" name="add_diagnosis" id="add_diagnosis" data-toggle="modal" data-placement="top" data-target="#addDiagnosisModal" title="Add Diagnosis" data-original-title="Add"><i class="fa fa-circle"></i>&nbsp;ICD</button></th>

                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-header d-flex justify-content-between">
                <div class="iq-header-title">
                    <h4 class="card-title">Presenting Symptoms</h4>
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-sm btn-primary mt-3" ><i class="fa fa-plus pr-0"></i></button>
                </div>
            </div>
            <div class="iq-card-body">
               <form action="" class="form-horizontal">
                <div class="form-group mb-0">
                   <ul class="list-group neuro-listgroup" id="diagnosis_list_front">
                      <li class="list-group-item"></li>
                  </ul>
              </div>
          </form>
          <div class="row">
            <div class="col-md-6">
                <h1 class="l_r">R</h1>
            </div>
            <div class="col-md-6 text-right">
                <h1 class="l_r">L</h1>
            </div>
        </div>

        <div class="anatomy_section text-center">
            <img src="{{ asset('new/images/nervous_system.png') }}" alt="" class="body_anatomy_form" style="margin-top: -26px;" />
            <span class="circle_cervical" data-toggle="modal" data-target="#myModal" title="Cervical Spine" data-placement="top" data-tooltip="tooltip"> </span>

            <!-- The Modal -->
            <div class="modal" id="myModal">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Cervical Spine</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">
                            <select class="form-control" name="Cervical_Spine">
                                <option value="Normal">Normal</option>
                                <option value="Tenderness">Tenderness</option>
                                <option value="Deformity">Deformity</option>
                            </select>
                            {{-- <textarea name="Cervical_Spine" style="width: 89%;"></textarea>--}}
                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" data-dismiss="modal">Save</button>
                        </div>
                    </div>
                </div>
            </div>
            <span class="circle_thoracic" data-toggle="modal" data-target="#myModal1" title="Thoracic Spine" data-placement="top" data-tooltip="tooltip"> </span>
            <!-- The Modal -->
            <div class="modal" id="myModal1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Thoracic Spine</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">
                            <select class="form-control" name="Thoracic_Spine">
                                <option value="Normal">Normal</option>
                                <option value="Tenderness">Tenderness</option>
                                <option value="Deformity">Deformity</option>
                            </select>
                            {{-- <textarea name="Thoracic_Spine" style="width: 89%;"></textarea>--}}
                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" data-dismiss="modal">Save</button>
                        </div>
                    </div>
                </div>
            </div>
            <span class="circle_lumber" data-toggle="modal" data-target="#myModal2" title="Lumber Spine" data-placement="top" data-tooltip="tooltip"> </span>
            <!-- The Modal
                                            -->
            <div class="modal" id="myModal2">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Lumber Spine</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">
                            <select class="form-control" name="Lumber_Spine">
                                <option value="Normal">Normal</option>
                                <option value="Tenderness">Tenderness</option>
                                <option value="Deformity">Deformity</option>
                            </select>
                            {{-- <textarea name="Lumber_Spine" style="width: 89%;"></textarea>--}}
                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" data-dismiss="modal">Save</button>
                        </div>
                    </div>
                </div>
            </div>
            <span class="circle_sacro" data-toggle="modal" data-target="#myModal3" title="sacrococcygeal Spine" data-placement="top" data-tooltip="tooltip"> </span>

            <!-- The Modal -->
            <div class="modal" id="myModal3">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">sacrococcygeal Spine</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">
                            <textarea name="sacrococcygeal_Spine" style="width: 89%;"></textarea>
                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" data-dismiss="modal">Save</button>
                        </div>
                    </div>
                </div>
            </div>

            <span class="circle_rightupper" data-toggle="modal" data-target="#myModal4" title="Right Upper Limbs" data-placement="top" data-tooltip="tooltip"> </span>

            <!-- The Modal -->
            <div class="modal" id="myModal4">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Right Upper Limbs</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">
                            {{-- <input type="number" step="0.1" name="Right_Upper_Limbs" style="width: 89%;" min="0" max="5" />--}} {{-- <textarea name="Right_Upper_Limbs" style="width: 89%;"></textarea>--}}
                            <select style="width: 89%;" name="Right_Upper_Limbs" class="form-control">
                                <option value="0">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" data-dismiss="modal">Save</button>
                        </div>
                    </div>
                </div>
            </div>

            <span class="circle_leftupper" data-toggle="modal" data-target="#myModal5" title="Left Upper Limbs" data-placement="top" data-tooltip="tooltip"> </span>

            <!-- The Modal -->
            <div class="modal" id="myModal5">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Left Upper Limbs</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">
                            {{-- <input type="number" step="0.1" name="Left_Upper_Limbs" style="width: 89%;" min="0" max="5" />--}} {{-- <textarea name="Left_Upper_Limbs" style="width: 89%;"></textarea>--}}
                            <select style="width: 89%;" name="Left_Upper_Limbs" class="form-control">
                                <option value="0">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" data-dismiss="modal">Save</button>
                        </div>
                    </div>
                </div>
            </div>

            <span class="circle_rightlimb" data-toggle="modal" data-target="#myModal6" title="Right Lower Limbs" data-placement="top" data-tooltip="tooltip"> </span>

            <!-- The Modal -->
            <div class="modal" id="myModal6">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Right Lower Limbs</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">
                            {{-- <input type="number" step="0.1" name="Right_Lower_Limbs" style="width: 89%;" min="0" max="5" />--}} {{-- <textarea name="Right_Lower_Limbs" style="width: 89%;"></textarea>--}}
                            <select style="width: 89%;" name="Right_Lower_Limbs" class="form-control">
                                <option value="0">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                            {{-- <textarea name="Right_Lower_Limbs" style="width: 89%;"></textarea>--}}
                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" data-dismiss="modal">Save</button>
                        </div>
                    </div>
                </div>
            </div>

            <span class="circle_leftlimb" data-toggle="modal" data-target="#myModal7" title="Left Lower Limbs" data-placement="top" data-tooltip="tooltip"> </span>

            <!-- The Modal -->
            <div class="modal" id="myModal7">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Left Lower Limbs</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">
                            {{-- <input type="number" step="0.1" name="Left_Lower_Limbs" style="width: 89%;" min="0" max="5" />--}} {{-- <textarea name="Left_Lower_Limbs" style="width: 89%;"></textarea>--}}
                            <select style="width: 89%;" name="Left_Lower_Limbs" class="form-control">
                                <option value="0">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                            {{-- <textarea name="Left_Lower_Limbs" style="width: 89%;"></textarea>--}}
                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" data-dismiss="modal">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<div class="col-sm-4">
    <div class="iq-card iq-card-block">
        <div class="iq-card-body p-1">
            <table class="table-neuro">
                <thead style="color: #585a5c;">
                    <tr>
                        <th class="table-neuro-th" style="font-size: 13px;">
                            MMSE <input type="radio" class="custom-radio-neuro" name="mmse_radio" value="uncooperative" /> Uncooperative <input type="radio" class="custom-radio-neuro" name="mmse_radio" value="abnormal" />&nbsp;Abnormal
                            <input type="number" class="input-neuro" placeholder="4" name="mmse" id="mmse" />
                            <i class="fa fa-arrow-up text-success" aria-hidden="true" onclick="increaseValue('mmse')"></i>&nbsp;&nbsp;<i class="fa fa-arrow-down text-danger" onclick="decreaseValue('mmse')"></i>
                        </th>
                    </tr>
                    <tr>
                        <th class="table-neuro-th">
                            Cranial Nerves:<input type="radio" class="custom-radio-neuro ml-4" name="cranial_nerves_left" value="abnormal"/>&nbsp;<span class="font-color">Abnormal</span>&nbsp; <input type="radio" class="custom-radio-neuro" name="cranial_nerves_left" value="normal" />&nbsp;
                            <span class="font-color">Normal</span>
                        </th>
                    </tr>
                    <tr>
                        <th class="table-neuro-th">
                            Nystagmus:<input type="radio" class="custom-radio-neuro ml-5" name="nystagmus_left" value="absent" />&nbsp;<span class="font-color">Absent</span> &nbsp;&nbsp;&nbsp;&nbsp; <input type="radio" class="custom-radio-neuro" name="nystagmus_left" value="present" />&nbsp;
                            <span class="font-color">Present</span>
                        </th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div class="iq-card iq-card-block">
        <div class="iq-card-body">
            <div class="iq-card-header d-flex justify-content-between">
                <div class="form-row col-12 p-0">
                    <label class="col-sm-8">Motor Power</label>
                    <label class="col-sm-4">Sensory</label>
                </div>
            </div>
            <table class="table-neuro">
                <thead style="color: #585a5c;">
                    <tr>
                        <th class="width-a">proxim</th>
                        <th class="width-c"><input type="number" class="form-control" placeholder="4" name="proxim_left" id="proxim_left" /></th>
                        <th class="width-c"><i class="fa fa-arrow-up text-success" aria-hidden="true" onclick="increaseValue('proxim_left')"></i>&nbsp;&nbsp;<i class="fa fa-arrow-down text-danger" onclick="decreaseValue('proxim_left')" ></i></th>
                        <th class="table-neuro-th">C5</th>
                        <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="c_five_left" value="abnormal" />&nbsp;Abnorm</th>
                        <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="c_five_left" value="normal" />&nbsp;Norm</th>
                    </tr><tr>
                        <th class="width-a">Distal</th>
                        <th class="width-c"><input type="number" class="form-control" placeholder="4" name="distal_left" id="distal_left"/></th>
                        <th class="width-c"><i class="fa fa-arrow-up text-success" aria-hidden="true" onclick="increaseValue('distal_left')"></i>&nbsp;&nbsp;<i class="fa fa-arrow-down text-danger" onclick="decreaseValue('distal_left')"></i></th>
                        <th class="table-neuro-th">C6</th>
                        <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="c_six_left" value="abnormal" />&nbsp;Abnorm</th>
                        <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="c_six_left" value="normal" />&nbsp;Norm</th>
                    </tr>
                    <tr>
                        <th class="width-a"></th>
                        <th class="width-c"></th>
                        <th class="width-c"></th>
                        <th class="table-neuro-th">C7</th>
                        <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="c_seven_left" value="abnormal" />&nbsp;Abnorm</th>
                        <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="c_seven_left" value="normal" />&nbsp;Norm</th>
                    </tr>
                    <tr>
                        <th class="width-a"></th>
                        <th class="width-c"></th>
                        <th class="width-c"></th>
                        <th class="table-neuro-th">C8</th>
                        <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="c_eight_left" value="abnormal" />&nbsp;Abnorm</th>
                        <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="c_eight_left" value="normal" />&nbsp;Norm</th>
                    </tr>
                    <tr>
                        <th colspan="4">Finger Nose Test</th>
                        <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="finger_nose_left" value="abnormal" />&nbsp;Abnorm</th>
                        <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="finger_nose_left" value="normal" />&nbsp;Norm</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div class="iq-card iq-card-block">
        <div class="iq-card-body">
            <table class="table-neuro">
                <thead style="color: #585a5c;">
                    <tr>
                        <th class="table-neuro-th">Bicep Jerk</th>
                        <th class="width-c"><input type="number" class="form-control" placeholder="4" name="bicep_jerk_left" id="bicep_jerk_left" /></th>
                        <th class="width-c"><i class="fa fa-arrow-up text-success" aria-hidden="true" onclick="increaseValue('bicep_jerk_left')"></i>&nbsp;&nbsp;<i class="fa fa-arrow-down text-danger" onclick="decreaseValue('bicep_jerk_left')"></i></th>
                        <th class="table-neuro-th">Supi Jerk</th>
                        <th class="width-c"><input type="number" class="form-control" placeholder="4" name="supi_jerk_left" id="supi_jerk_left"/></th>
                        <th class="width-c"><i class="fa fa-arrow-up text-success" aria-hidden="true" onclick="increaseValue('supi_jerk_left')"></i>&nbsp;&nbsp;<i class="fa fa-arrow-down text-danger" onclick="decreaseValue('supi_jerk_left')"></i></th>
                    </tr>
                    <tr>
                        <th class="table-neuro-th">Tricep Jerk</th>
                        <th class="width-c"><input type="number" class="form-control" placeholder="4" name="tricep_jerk_left" id="tricep_jerk_left" /></th>
                        <th class="width-c"><i class="fa fa-arrow-up text-success" aria-hidden="true" onclick="increaseValue('tricep_jerk_left')"></i>&nbsp;&nbsp;<i class="fa fa-arrow-down text-danger" onclick="decreaseValue('tricep_jerk_left')"></i></th>
                    </tr>
                </thead>
            </table>
            <table>
                <thead style="color: #585a5c;">
                    <tr>
                        <th class="table-neuro-th">FLAIR</th>
                        <th class="font-color"><input type="radio" class="custom-radio-neuro" name="flair_left" value="abnormal" />&nbsp;Abnormal</th>
                        <th class="font-color"><input type="radio" class="custom-radio-neuro" name="flair_left" value="normal" />&nbsp;Normal</th>
                    </tr>
                    <tr>
                        <th class="table-neuro-th">FABER</th>
                        <th class="font-color"><input type="radio" class="custom-radio-neuro" name="faber_left" value="abnormal" />&nbsp;Abnormal</th>
                        <th class="font-color"><input type="radio" class="custom-radio-neuro" name="faber_left" value="normal" />&nbsp;Normal</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div class="iq-card iq-card-block">
        <div class="iq-card-body">
            <div class="iq-card-header d-flex justify-content-between">
                <div class="form-row col-12 p-0">
                    <label class="col-sm-8">Motor Power</label>
                    <label class="col-sm-4">Sensory</label>
                </div>
            </div>
            <table class="table-neuro">
                <thead style="color: #585a5c;">
                    <tr>
                        <th class="width-a" rowspan="2">proxim</th>
                        <th class="width-c"  rowspan="2"><input type="number" class="form-control" placeholder="4" name="proxim_left_heel" id="proxim_left_heel" /></th>
                        <th class="width-c"  rowspan="2"><i class="fa fa-arrow-up text-success" aria-hidden="true" onclick="increaseValue('proxim_left_heel')" ></i>&nbsp;&nbsp;<i class="fa fa-arrow-down text-danger" onclick="decreaseValue('proxim_left_heel')"></i></th>
                        <th class="table-neuro-th">L3</th>
                        <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="l_two_left" value="abnormal" />&nbsp;Abnorm</th>
                        <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="l_two_left" value="normal" />&nbsp;Norm</th>
                    </tr>
                    <tr>
                        <th class="table-neuro-th">L3</th>
                        <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="l_three_left" value="abnormal" />&nbsp;Abnorm</th>
                        <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="l_three_left" value="normal" />&nbsp;Norm</th>
                    </tr>
                    <tr>
                        <th class="width-a">Distal</th>
                        <th class="width-c"><input type="number" class="form-control" placeholder="4" name="distal_left_heel" id="distal_left_heel" /></th>
                        <th class="width-c"><i class="fa fa-arrow-up text-success" aria-hidden="true" onclick="increaseValue('distal_left_heel')"></i>&nbsp;&nbsp;<i class="fa fa-arrow-down text-danger" onclick="decreaseValue('distal_left_heel')"></i></th>
                        <th class="table-neuro-th">L4</th>
                        <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="l_four_left" value="abnormal" />&nbsp;Abnorm</th>
                        <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="l_four_left" value="normal" />&nbsp;Norm</th>
                    </tr>
                    <tr>
                        <th class="width-a">EHL</th>
                        <th class="width-c"><input type="number" class="form-control" placeholder="4" name="ehl_left" id="ehl_left_heel" /></th>
                        <th class="width-c"><i class="fa fa-arrow-up text-success" aria-hidden="true" onclick="increaseValue('ehl_left_heel')" ></i>&nbsp;&nbsp;<i class="fa fa-arrow-down text-danger" onclick="decreaseValue('ehl_left_heel')"></i></th>
                        <th class="table-neuro-th">L5</th>
                        <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="l_five_left" value="abnormal" />&nbsp;Abnorm</th>
                        <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="l_five_left" value="normal" />&nbsp;Norm</th>
                    </tr>
                    <tr>
                        <th class="width-a" rowspan="2">FHL</th>
                        <th class="width-c"  rowspan="2"><input type="number" class="form-control" placeholder="4" name="fhl_left" id="fhl_left_heel" /></th>
                        <th class="width-c"  rowspan="2"><i class="fa fa-arrow-up text-success" aria-hidden="true" onclick="increaseValue('fhl_left_heel')"></i>&nbsp;&nbsp;<i class="fa fa-arrow-down text-danger" onclick="decreaseValue('fhl_left_heel')" ></i></th>
                        <th class="table-neuro-th">S1</th>
                        <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="s_one_left" value="abnormal" />&nbsp;Abnorm</th>
                        <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="s_one_left" value="normal" />&nbsp;Norm</th>
                    </tr>
                    <tr>
                        <th class="table-neuro-th">S2</th>
                        <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="s_two_left" value="abnormal" />&nbsp;Abnorm</th>
                        <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="s_two_left" value="normal" />&nbsp;Norm</th>
                    </tr>
                    <tr>
                        <th colspan="4">Heel-Shin Test</th>
                        <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="heel_shin_left" value="abnormal" />&nbsp;Abnorm</th>
                        <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="heel_shin_left" value="normal" />&nbsp;Norm</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div class="iq-card iq-card-block">
        <div class="iq-card-body">
            <table class="table-neuro">
                <thead style="color: #585a5c;">
                    <tr>
                        <th class="table-neuro-th">Knee Jerk</th>
                        <th class="width-c"><input type="number" class="form-control" placeholder="4" name="knee_jerk_left" id="knee_jerk_left" /></th>
                        <th class="width-c"><i class="fa fa-arrow-up text-success" aria-hidden="true" onclick="increaseValue('knee_jerk_left')"
                            ></i>&nbsp;&nbsp;<i class="fa fa-arrow-down text-danger" onclick="decreaseValue('knee_jerk_left')"></i></th>
                        <th class="table-neuro-th">Ankle Jerk</th>
                        <th class="width-c"><input type="number" class="form-control" placeholder="4" name="ankel_jerk_left"  id="ankel_jerk_left"/></th>
                        <th class="width-c"><i class="fa fa-arrow-up text-success" aria-hidden="true" onclick="increaseValue('ankel_jerk_left')"
                            ></i>&nbsp;&nbsp;<i class="fa fa-arrow-down text-danger" onclick="decreaseValue('ankel_jerk_left')"></i></th>
                    </tr>
                    <tr>
                        <th colspan="1">SLR</th>
                        <th colspan="3">
                            <div class="progress mt-1">
                                <div class="progress-bar progress-bar-striped bg-info" role="progressbar" style="width: 50%;" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" id="progress_left"></div>
                            </div>
                        </th>
                        <th colspan="3"><input type="number" class="form-control" placeholder="4" name="slr_left" id="slr_left" /></th>
                    </tr>
                </thead>
            </table>
            <table>
               <thead style="color: #585a5c;">
                <tr>
                    <th class="table-neuro-th">Planter Response</th>
                    <th class="font-color"><input type="radio" class="custom-radio-neuro" name="planter_response_left" value="downgoing" />&nbsp;Downgoing</th>
                    <th class="font-color"><input type="radio" class="custom-radio-neuro" name="planter_response_left" value="upgoing" />&nbsp;Upgoing</th>
                </tr>
                <tr>
                    <th><button type="submit" class="btn btn-primary mt-2"><i class="fa fa-plus"></i>&nbsp;Save</button></th>
                    <th><button type="button" class="btn btn-primary mt-2"><i class="fa fa-code"></i>&nbsp;Report</button></th>
                    <th><button type="button" class="btn btn-primary mt-2"><i class="fa fa-info"></i></button></th>
                </tr>
            </thead>
        </table>


            <!-- ICD Modal-->

            <div class="modal" id="addDiagnosisModal">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">ICD10 Database</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <!-- Modal Diagnosis -->
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6" style="overflow: auto; height: 440px;">
                                    <table class="table-bordered table table-diagnosis" id="table-diagnosis" style="margin-bottom: 0px;">
                                        <thead>
                                        <tr style="background: #28a745; color: #ffffff;">
                                            <th class="text-center">Code</th>
                                            <th>Group</th>
                                        </tr>
                                        </thead>
                                        <tbody class="" id="diagnosis_table"></tbody>
                                    </table>
                                </div>
                                <div class="col-md-6" style="overflow: auto; height: 440px;">
                                    <table class="table table-bordered table-diagnosis" id="sub_diagnosis_table" style="max-height: 425px;">
                                        <tbody id="sub_diagnosis_table_body"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" id="save_diagnosis" title="Save Diagnosis">
                                Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- END ICD Modal -->






    </div>
</div>
</div>
        </form>

</div>
    <!-- Buttom ko menu ho -->
    <div class="col-sm-12">
        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-body">
                <div class="d-flex justify-content-around">
                    <a href="javascript:void(0)" onclick="laboratory.displayModal()" class="btn btn-primary  {{ $disableClass }}">Laboratory
                    </a>
                    <a href="javascript:void(0)" onclick="radiology.displayModal()" class="btn btn-primary  {{ $disableClass }}">Radiology
                    </a>

                    <a href="javascript:void(0)" onclick="pharmacy.displayModal()" class="btn btn-primary  {{ $disableClass }}">Pharmacy
                    </a>
                    <a href="javascript:void(0);" onclick="requestMenu.majorProcedureModal()" class="btn btn-primary  {{ $disableClass }}">Procedure
                    </a>
                    <a href="{{ route('outpatient.history.generate', $patient->fldpatientval??0) }}?opd" target="_blank" class="btn btn-primary {{ $disableClass }}">History
                    </a>
                    <a @if(isset($enpatient)) href="{{ route('outpatient.pdf.generate.opd.sheet', $enpatient->fldencounterval??0) }}?opd" target="_blank" @else href="#" @endif class="btn btn-primary  {{ $disableClass }}">OPD Sheet
                    </a>
                    <a href="{{ route('reset.encounter') }}" onclick="return checkFormEmpty();" class="btn btn-primary  {{ $disableClass }}">Save
                    </a>
                    <a href="javascript:;" data-toggle="modal" data-target="#finish_box" id="finish" class="btn btn-primary  {{ $disableClass }}">Finish
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Buttom ko menu end bhaeko -->
</div>



        <script>

            var neuroForm = {
                getDiagnosisList: function () {
                    var url_dignosis_list = "{{ route('diagnosis-list') }}";
                    $.ajax({
                        url: url_dignosis_list,
                        method: "post",
                        data: {
                            _token: "{{ csrf_token() }}",
                        },
                        beforeSend: function () {
                            $("#diagnosis_table").html("");
                            var html = '<tr><td colspan="2" class="text-center">' + "Please Wait....." + "</td></tr>";
                            $("#diagnosis_table").append(html);
                        },
                        success: function (data) {
                            var html_sub_diagnosis_list = '<tr><td colspan="2" class="text-center">' + "Please Wait....." + "</td></tr>";
                            if (data.status == "success") {
                                html_sub_diagnosis_list = "";
                                $.each(data.data, function (index, value) {
                                    html_sub_diagnosis_list += '<tr data-code="' + value.code + '" class="tr_diagnosis">' + '<td class="text-center">' + value.code + "</td>" + "<td>" + value.name + "</td>" + "</tr>";
                                });
                            }
                            $("#diagnosis_table").html("");
                            $("#diagnosis_table").append(html_sub_diagnosis_list);
                            $("#table-diagnosis").DataTable({
                                paging: false,
                                ordering: false,
                                info: false,
                            });
                        },
                    });
                },
            };

            neuroForm.getDiagnosisList();


            /**
             *function for getting diagnosis
             */

            $(document).on("click", ".tr_diagnosis", function () {
                var getdiagnosis = "{{ route('diagnosis-by-code') }}";
                var code = $(this).data("code");
                $("#diagnosis_code").val(code);
                $.ajax({
                    url: getdiagnosis,
                    method: "post",
                    data: {
                        _token: "{{ csrf_token() }}",
                        code: code,
                    },
                    beforeSend: function () {
                        $("#sub_diagnosis_table_body").html("");
                        var html = '<tr ><td colspan="2" class="text-center">' + "Please Wait....." + "</td></tr>";
                        $("#sub_diagnosis_table_body").append(html);
                    },
                    success: function (data) {
                        var html_sub_diagnosis_list = "";
                        $("#sub_diagnosis_table_body").append("");
                        $.each(data, function (index, value) {
                            html_sub_diagnosis_list += '<tr class="tr_sub_diagnosis" data-fldcode="' + value + '" data-fldcodeid ="' + code + '"><td><a href="#" class="btnDiagnosisName">' + value + "<a></td></tr>";
                        });

                        $("#sub_diagnosis_table_body").html("");
                        $("#sub_diagnosis_table_body").append(html_sub_diagnosis_list);
                    },
                });
            });


            $(document).on("click", ".btnDiagnosisName", function () {
                $("#sub_diagnosis_table").find("tr").removeClass("diagnosisSelected");
                $(this).parent().parent().addClass("diagnosisSelected");
            });

            /** Function for removing  diagnosis
            */
            $(document).on("click", ".delete-diagnosis", function () {
                var id = $(this).data("id");
                var remove_diagnosis = "{{ route('remove-diagnosis') }}";
                $.ajax({
                    url: remove_diagnosis,
                    method: "post",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id,
                        encounter: $("#encounter_no").val(),
                    },
                    success: function (data) {
                        var diagnosis_html = "";
                        $("#diagnosis_list_front").empty();
                        $.each(data, function (index, value) {
                            diagnosis_html +=
                                '<li class="list-group-item" style="border-bottom: 1px solid #eae5e5 !important;">\n' +
                                "                                         " +
                                value.fldcode +
                                ' <a class="delete-diagnosis" href="#" style="float: right;color: #dc3545;margin-right: -20px" data-id = " ' +
                                value.fldid +
                                '">  <i class="fa fa-trash"></i></a>\n' +
                                "                                        </li>";
                        });
                        $("#diagnosis_list_front").html("");
                        $("#diagnosis_list_front").append(diagnosis_html);
                    },
                    error: function (data) {
                        //console.log('error');
                    },
                });
            });

            /**
             * Function for gettting the sub group list from csv
             */
            $(document).on("click", "#save_diagnosis", function () {
                var fldcode = $("#sub_diagnosis_table").find("tr.diagnosisSelected").data("fldcode");
                var fldcodeid = $("#sub_diagnosis_table").find("tr.diagnosisSelected").data("fldcodeid");

                if (typeof fldcode != "undefined" && typeof fldcodeid != "undefined" && fldcodeid != "" && fldcode != "") {
                    var add_diagnosis = "{{ route('store-diagnosis') }}";
                    $.ajax({
                        url: add_diagnosis,
                        method: "post",
                        data: {
                            _token: "{{ csrf_token() }}",
                            fldtype: "Provisional Diagnosis",
                            fldcode: fldcode,
                            fldcodeid: fldcodeid,
                            encounter: $("#encounter_no").val(),
                        },
                        success: function (data) {
                            var diagnosis_html = "";
                            $("#diagnosis_list_front").empty();
                            $.each(data, function (index, value) {
                                diagnosis_html +=
                                    '<li class="list-group-item" style="border-bottom: 1px solid #eae5e5 !important;">\n' +
                                    "                                         " +
                                    value.fldcode +
                                    ' <a class="delete-diagnosis" href="#" style="float: right;color: #dc3545;margin-right: -20px" data-id = " ' +
                                    value.fldid +
                                    '">  <i class="fa fa-trash"></i></a>\n' +
                                    "                                        </li>";
                            });
                            $("#diagnosis_list_front").html("");
                            $("#diagnosis_list_front").append(diagnosis_html);
                            $("#addDiagnosisModal").modal("toggle");
                        },
                        error: function (data) {
                        },
                    });
                }
            });


            /**Function for increment */
            function increaseValue(id) {

                var id_param  = id ? id : '';
                var value = parseInt(document.getElementById(id_param).value, 10);
                value = isNaN(value) ? 0 : value;
                value++;
                document.getElementById(id_param).value = value;
            }
            /**Function for decreament */
            function decreaseValue(id) {
                var id_param  = id ? id : '';
                var value = parseInt(document.getElementById(id_param).value, 10);
                value = isNaN(value) ? 0 : value;
                value < 1 ? value = 1 : '';
                value--;
                document.getElementById(id_param).value = value;
            }

            /**Value change for progress bar */

            $('#slr_left').keyup(function(){
                var input = $(this).val();
                if( isNaN(input) || input== '' || input==undefined)
                {
                    return false;
                }
                // console.log((100/input));
                // return false;

                $('#progress_left').css('width', function(index, value){
                    return input;
                    // return "+=2%";
                });
            });
            $(document).ready(function() {
           $('body').tooltip({
               selector: "[data-tooltip=tooltip]",
               container: "body"
           });
       });
        </script>
@endsection
