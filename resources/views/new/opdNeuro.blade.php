@extends('frontend.layouts.master') @section('content')
<div class="container-fluid">
    <div class="row">
    @include('frontend.common.patientProfile')
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
                                <th class="width-a">proxim</th>
                                <th class="width-c"><input type="number" class="form-control" placeholder="4" name="proxim_right_heel" id="proxim_right_heel" /></th>
                                <th class="width-c"><i class="fa fa-arrow-up text-success" aria-hidden="true" onclick="increaseValue('proxim_right_heel')" ></i>&nbsp;&nbsp;<i class="fa fa-arrow-down text-danger" onclick="decreaseValue('proxim_right_heel')"></i></th>
                                <th class="table-neuro-th">
                                    L2<br />
                                    L3
                                </th>
                                <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="l_two_right" value="abnormal" />&nbsp;Abnorm</th>
                                <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="l_two_right" value="normal" />&nbsp;Norm</th>
                            </tr>
                            <tr>
                                <th class="width-a">Distal</th>
                                <th class="width-c"><input type="number" class="form-control" placeholder="4" name="distal_right_heel"  id="distal_right_heel"/></th>
                                <th class="width-c"><i class="fa fa-arrow-up text-success" aria-hidden="true" onclick="increaseValue('distal_right_heel')" ></i>&nbsp;&nbsp;<i class="fa fa-arrow-down text-danger" onclick="decreaseValue('distal_right_heel')"></i></th>
                                <th class="table-neuro-th">L4</th>
                                <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="l_four_right" value="abnormal" />&nbsp;Abnorm</th>
                                <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="l_four_right" value="normal" />&nbsp;Norm</th>
                            </tr>
                            <tr>
                                <th class="width-a">EHL</th>
                                <th class="width-c"><input type="number" class="form-control" placeholder="4" name="ehl_right_heel" id="ehl_right_heel" /></th>
                                <th class="width-c"><i class="fa fa-arrow-up text-success" aria-hidden="true" onclick="increaseValue('ehl_right_heel')"></i>&nbsp;&nbsp;<i class="fa fa-arrow-down text-danger" onclick="decreaseValue('ehl_right_heel')"></i></th>
                                <th class="table-neuro-th">L5</th>
                                <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="l_five_right" value="abnormal" />&nbsp;Abnorm</th>
                                <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="l_five_right" value="normal" />&nbsp;Norm</th</tr>
                            <tr>
                                <th class="width-a">FHL</th>
                                <th class="width-c"><input type="number" class="form-control" placeholder="4" name="fhl_right_heel" id="fhl_right_heel" /></th>
                                <th class="width-c"><i class="fa fa-arrow-up text-success" aria-hidden="true" onclick="increaseValue('fhl_right_heel')" ></i>&nbsp;&nbsp;<i class="fa fa-arrow-down text-danger" onclick="decreaseValue('fhl_right_heel')"></i></th>
                                <th class="table-neuro-th">
                                    S1<br />
                                    S2
                                </th>
                                <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="s_one_right" value="abnormal" />&nbsp;Abnorm</th>
                                <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="s_one_right" value="normal" />&nbsp;Norm</th>
                            </tr>
                            <tr>
                                <th colspan="4">Heel-Shin Test</th>
                                <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="" />&nbsp;Abnorm</th>
                                <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="" />&nbsp;Norm</th>
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
                                        <div class="progress-bar progress-bar-striped bg-info" role="progressbar" style="width: 50%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </th>
                                <th colspan="3"><input type="number" class="form-control" placeholder="4" name="" /></th>
                            </tr>
                        </thead>
                    </table>
                    <table>
                       <thead style="color: #585a5c;">
                        <tr>
                            <th class="table-neuro-th">Planter Response</th>
                            <th class="font-color"><input type="radio" class="custom-radio-neuro" name="planter_response_right" value="Downgoing" />&nbsp;Downgoing</th>
                            <th class="font-color"><input type="radio" class="custom-radio-neuro" name="planter_response_right" value="Upgoing" />&nbsp;Upgoing</th>
                        </tr>
                        <tr>
                            <th><button class="btn btn-outline-primary mt-2"><i class="fa fa-image"></i>&nbsp;Draw</button></th>
                            <th><button class="btn btn-outline-primary mt-2"><i class="fa fa-server"></i>&nbsp;Vital</button></th>
                            <th><button class="btn btn-outline-primary mt-2"><i class="fa fa-circle"></i>&nbsp;ICD</button></th>
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
                    <button type="button" class="btn btn-sm btn-primary mt-3" name=""><i class="fa fa-plus pr-0"></i></button>
                </div>
            </div>
            <div class="iq-card-body">
               <form action="" class="form-horizontal">
                <div class="form-group mb-0">
                   <ul class="list-group neuro-listgroup">
                      <li class="list-group-item"></li>
                      <li class="list-group-item"></li>
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
                            MMSE <input type="radio" class="custom-radio-neuro" name="" /> Uncooperative <input type="radio" class="custom-radio-neuro" name="" />&nbsp;Abnormal
                            <input type="number" class="input-neuro" placeholder="4" name="mmse" id="mmse" />
                            <i class="fa fa-arrow-up text-success" aria-hidden="true" onclick="increaseValue('mmse')"></i>&nbsp;&nbsp;<i class="fa fa-arrow-down text-danger" onclick="decreaseValue('mmse')"></i>
                        </th>
                    </tr>
                    <tr>
                        <th class="table-neuro-th">
                            Cranial Nerves:<input type="radio" class="custom-radio-neuro ml-4" name="" />&nbsp;<span class="font-color">Abnormal</span>&nbsp; <input type="radio" class="custom-radio-neuro" name="" />&nbsp;
                            <span class="font-color">Normal</span>
                        </th>
                    </tr>
                    <tr>
                        <th class="table-neuro-th">
                            Nystagmus:<input type="radio" class="custom-radio-neuro ml-5" name="" />&nbsp;<span class="font-color">Absent</span> &nbsp;&nbsp;&nbsp;&nbsp; <input type="radio" class="custom-radio-neuro" name="" />&nbsp;
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
                        <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="c_five_left" value="Abnormal" />&nbsp;Abnorm</th>
                        <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="c_five_left" value="Normal" />&nbsp;Norm</th>
                    </tr><tr>
                        <th class="width-a">Distal</th>
                        <th class="width-c"><input type="number" class="form-control" placeholder="4" name="distal_left" id="distal_left"/></th>
                        <th class="width-c"><i class="fa fa-arrow-up text-success" aria-hidden="true" onclick="increaseValue('distal_left')"></i>&nbsp;&nbsp;<i class="fa fa-arrow-down text-danger" onclick="decreaseValue('distal_left')"></i></th>
                        <th class="table-neuro-th">C6</th>
                        <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="c_six_left" value="Abnormal" />&nbsp;Abnorm</th>
                        <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="c_six_left" value="Normal" />&nbsp;Norm</th>
                    </tr>
                    <tr>
                        <th class="width-a"></th>
                        <th class="width-c"></th>
                        <th class="width-c"></th>
                        <th class="table-neuro-th">C7</th>
                        <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="c_seven_left" value="Abnormal" />&nbsp;Abnorm</th>
                        <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="c_seven_left" value="Normal" />&nbsp;Norm</th>
                    </tr>
                    <tr>
                        <th class="width-a"></th>
                        <th class="width-c"></th>
                        <th class="width-c"></th>
                        <th class="table-neuro-th">C8</th>
                        <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="c_eight_left" value="Abnormal" />&nbsp;Abnorm</th>
                        <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="c_eight_left" value="Normal" />&nbsp;Norm</th>
                    </tr>
                    <tr>
                        <th colspan="4">Finger Nose Test</th>
                        <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="nose_right" value="Abnormal" />&nbsp;Abnorm</th>
                        <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="nose_right" value="Normal" />&nbsp;Norm</th>
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
                        <th class="font-color"><input type="radio" class="custom-radio-neuro" name="flair_left" value="Abnormal" />&nbsp;Abnormal</th>
                        <th class="font-color"><input type="radio" class="custom-radio-neuro" name="flair_left" value="Normal" />&nbsp;Normal</th>
                    </tr>
                    <tr>
                        <th class="table-neuro-th">FABER</th>
                        <th class="font-color"><input type="radio" class="custom-radio-neuro" name="faber_left" value="Abnormal" />&nbsp;Abnormal</th>
                        <th class="font-color"><input type="radio" class="custom-radio-neuro" name="faber_left" value="Normal" />&nbsp;Normal</th>
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
                        <th class="width-c"><input type="number" class="form-control" placeholder="4" name="proxim_left_heel" id="proxim_left_heel" /></th>
                        <th class="width-c"><i class="fa fa-arrow-up text-success" aria-hidden="true" onclick="increaseValue('proxim_left_heel')" ></i>&nbsp;&nbsp;<i class="fa fa-arrow-down text-danger" onclick="decreaseValue('proxim_left_heel')"></i></th>
                        <th class="table-neuro-th">
                            L2<br />
                            L3
                        </th>
                        <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="l_two_left" value="Abnormal" />&nbsp;Abnorm</th>
                        <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="l_two_left" value="Normal" />&nbsp;Norm</th>
                    </tr>
                    <tr>
                        <th class="width-a">Distal</th>
                        <th class="width-c"><input type="number" class="form-control" placeholder="4" name="distal_left_heel" id="distal_left_heel" /></th>
                        <th class="width-c"><i class="fa fa-arrow-up text-success" aria-hidden="true" onclick="increaseValue('distal_left_heel')"></i>&nbsp;&nbsp;<i class="fa fa-arrow-down text-danger" onclick="decreaseValue('distal_left_heel')"></i></th>
                        <th class="table-neuro-th">L4</th>
                        <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="l_four_left" value="Abnormal" />&nbsp;Abnorm</th>
                        <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="l_four_left" value="Normal" />&nbsp;Norm</th>
                    </tr>
                    <tr>
                        <th class="width-a">EHL</th>
                        <th class="width-c"><input type="number" class="form-control" placeholder="4" name="ehl_left_heel" id="ehl_left_heel" /></th>
                        <th class="width-c"><i class="fa fa-arrow-up text-success" aria-hidden="true" onclick="increaseValue('ehl_left_heel')" ></i>&nbsp;&nbsp;<i class="fa fa-arrow-down text-danger" onclick="decreaseValue('ehl_left_heel')"></i></th>
                        <th class="table-neuro-th">L5</th>
                        <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="l_five_left" value="Abnormal" />&nbsp;Abnorm</th>
                        <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="l_five_left" value="Normal" />&nbsp;Norm</th>
                    </tr>
                    <tr>
                        <th class="width-a">FHL</th>
                        <th class="width-c"><input type="number" class="form-control" placeholder="4" name="fhl_left_heel" id="fhl_left_heel" /></th>
                        <th class="width-c"><i class="fa fa-arrow-up text-success" aria-hidden="true" onclick="increaseValue('fhl_left_heel')"></i>&nbsp;&nbsp;<i class="fa fa-arrow-down text-danger" onclick="decreaseValue('fhl_left_heel')" ></i></th>
                        <th class="table-neuro-th">
                            S1<br />
                            S2
                        </th>
                        <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="s_one_left" value="Abnormal" />&nbsp;Abnorm</th>
                        <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="s_one_left" value="Normal" />&nbsp;Norm</th>
                    </tr>
                    <tr>
                        <th colspan="4">Heel-Shin Test</th>
                        <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="heel_left" value="Abnormal" />&nbsp;Abnorm</th>
                        <th class="width-b font-color"><input type="radio" class="custom-radio-neuro" name="heel_left" value="Normal" />&nbsp;Norm</th>
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
                                <div class="progress-bar progress-bar-striped bg-info" role="progressbar" style="width: 50%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </th>
                        <th colspan="3"><input type="number" class="form-control" placeholder="4" name="slr" /></th>
                    </tr>
                </thead>
            </table>
            <table>
               <thead style="color: #585a5c;">
                <tr>
                    <th class="table-neuro-th">Planter Response</th>
                    <th class="font-color"><input type="radio" class="custom-radio-neuro" name="planter_response_left" value="Downgoing" />&nbsp;Downgoing</th>
                    <th class="font-color"><input type="radio" class="custom-radio-neuro" name="planter_response_left" value="Upgoing" />&nbsp;Upgoing</th>
                </tr>
                <tr>
                    <th><button class="btn btn-outline-primary mt-2"><i class="fa fa-plus"></i>&nbsp;Save</button></th>
                    <th><button class="btn btn-outline-primary mt-2"><i class="fa fa-code"></i>&nbsp;Report</button></th>
                    <th><button class="btn btn-outline-primary mt-2"><i class="fa fa-info"></i></button></th>
                </tr>
            </thead>
        </table>
    </div>
</div>
</div>
</div>
</div>
        <script>
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
        </script>
@endsection
