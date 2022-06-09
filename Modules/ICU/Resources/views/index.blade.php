@extends('frontend.layouts.master') @section('content')
    <!-- <div class="iq-top-navbar second-nav"> -->
    @if(isset($patient_status_disabled) && $patient_status_disabled == 1 )
        @php
            $disableClass = 'disableInsertUpdate';
        @endphp
    @else
        @php
            $disableClass = '';
        @endphp
    @endif
    @include('menu::common.icu-nav-bar')

    <div class="container-fluid">
        <script>
            $(document).ready(function () {
                $('[data-tooltip="tooltip"]').tooltip();
            });
        </script>

        {{--patient profile--}} @include('frontend.common.patientProfile')
        {{--end patient profile--}}
        <form method="post" action="{{ route('store') }}">
            @csrf
            <input type="hidden" name="encounter_id" value="{{ $encounter_no }}" id="encounter_no">
            <input type="hidden" name="mode_remarks" value="" id="modeRemarks">
            <div class="row">
                <div class="col-sm-12">
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">Glasgow Coma Scale</h4>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group form-row">
                                        <label for="e" class="lable-gcs col-2">E </label>
                                        <div class="col-sm-10">
                                            <select class="form-control gcs_class" id="gcs_e" name="e">
                                                <option value="4" selected>Spontaneous</option>
                                                <option value="3">To speech</option>
                                                <option value="2">To pain</option>
                                                <option value="1">None</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group form-row">
                                        <label for="e" class="lable-gcs col-2">V</label>
                                        <div class="col-sm-10">
                                            <select class="form-control gcs_class" id="gcs_v" name="v">
                                                <option value="5" selected>Oriented</option>
                                                <option value="4">Confused</option>
                                                <option value="3">Words</option>
                                                <option value="2">Sounds</option>
                                                <option value="T" id="verbal_t">T</option>
                                                <option value="none" id="verbal_none">None</option>

                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2 col-lg-3">
                                    <div class="form-group form-row">
                                        <label for="e" class="lable-gcs col-2">M</label>
                                        <div class="col-sm-10">
                                            <select class="form-control gcs_class" id="gcs_m" name="m">
                                                <option value="6" selected>Obeys Command</option>
                                                <option value="5">Localizes Pain</option>
                                                <option value="4">Normal Flexion</option>
                                                <option value="3">Abnormal Flexion</option>
                                                <option value="2">Extension</option>
                                                <option value="1">None</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3 col-lg-2">
                                    <div class="form-group form-row">
                                        <label for="e" class="lable-gcs col-sm-4 col-lg-3">Total</label>
                                        <div class="col-sm-8 col-lg-9">
                                            <input type="text" step="0.01" min="0.01"
                                                   class="form-control gcs_class full-width" id="total_gcs"
                                                   placeholder="" name="total_gcs" autocomplete="off"
                                                   style="width: 80px;" readonly/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2 col-lg-2">
                                    <div class="form-group form-row">
                                        <label for="e" class="lable-gcs col-4">Vital</label>
                                        <div class="col-sm-8">
                                            <button type="button" class="btn btn-sm btn-primary" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                            <div class="dropdown-menu"
                                                 style="padding: 10px; margin: 0 0 0 0; width: 250px;">
                                                <table style="width: 100%;">
                                                    <tbody>
                                                    <tr>
                                                        <td style="width: 50%;">
                                                            <label for="">Pulse Rate</label>
                                                        </td>
                                                        <td style="width: 50%;">
                                                            <input type="number" step="0.01" min="0.01"
                                                                   class="form-control" placeholder="" name="pulse_rate"
                                                                   autocomplete="off"/>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label for="">Syst BP</label>
                                                        </td>
                                                        <td>
                                                            <input type="number" step="0.01" min="0.01"
                                                                   class="form-control input-blood-pressure"
                                                                   id="input-syst-bp" placeholder="" name="syst_bp"
                                                                   autocomplete="off"/>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label for="">Dyst BP</label>
                                                        </td>
                                                        <td>
                                                            <input type="number" step="0.01" min="0.01"
                                                                   class="form-control input-blood-pressure"
                                                                   id="input-dyst-bp" placeholder="" name="dyst_bp"
                                                                   autocomplete="off"/>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label for="">SPO<small>2</small></label>
                                                        </td>
                                                        <td>
                                                            <input type="number" step="0.01" min="0.01"
                                                                   class="form-control" placeholder="" name="spo"
                                                                   autocomplete="off"/>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label for="">Temp(C)</label>
                                                        </td>
                                                        <td>
                                                            <input type="number" step="0.01" min="0.01"
                                                                   class="form-control" placeholder="" name="temp"
                                                                   autocomplete="off"/>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label for="">Respiratory</label>
                                                        </td>
                                                        <td>
                                                            <input type="number" step="0.01" min="0.01"
                                                                   class="form-control" placeholder=""
                                                                   name="respiratory" autocomplete="off"/>
                                                        </td>
                                                    </tr>
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
                <div class="col-md-4">
                    <div class="iq-card iq-card-block">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">Pupils</h4>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <table style="width: 100%;">
                                <tr>
                                    <td>
                                        <label for="size" class="lable-gcs">Size</label>
                                    </td>
                                    <td>
                                        <select name="right_side_size" class="form-control" autocomplete="off">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                        </select>
                                        {{-- <input type="text" class="form-control" placeholder="" name="right_side_size" autocomplete="off" />--}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="reaction" class="lable-gcs">Reaction</label>
                                    </td>
                                    <td>
                                        <select class="form-control" name="right_side_reaction">
                                            <option value="Normal Reaction">Normal Reaction</option>
                                            <option value="No Reaction">No Reaction</option>
                                            <option value="Sluggish Reaction">Sluggish Reaction</option>
                                        </select>
                                        {{--<input type="text" class="form-control" placeholder="" name="right_side_reaction" autocomplete="off" />--}}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="iq-card iq-card-block">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">Ventilator Parameter</h4>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <div class="form-group form-row">
                                <label for="map" class="lable-mvp col-sm-11 col-lg-6">Mean Arterial Pressure:</label>
                                <div class="col-sm-1 col-lg-6">
                                    <input type="text" class="form-control" id="map" placeholder="" name="map"
                                           autocomplete="off" readonly/>
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <label for="cvp" class="lable-mvp col-sm-11 col-lg-6">Central Venous Pressure:</label>
                                <div class="col-sm-1 col-lg-6">
                                    <input type="number" step="0.01" min="0.01" class="form-control" placeholder=""
                                           name="cvp" autocomplete="off"/>
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <label for="etc" class="lable-mvp col-sm-11 col-lg-6">ETCO<small>2:</small></label>
                                <div class="col-sm-1 col-lg-6">
                                    <input type="number" step="0.01" min="0.01" class="form-control" placeholder=""
                                           name="etc" autocomplete="off"/>
                                </div>
                            </div>
                        <!-- <table style="width: 100%;">
                            <tr>
                            <td>
                                <label for="map" class="lable-mvp">MAP:</label>
                            </td>
                            <td>
                                <input type="text" class="form-control" id="map" placeholder="" name="map" autocomplete="off" style="width: 75px;" readonly />
                            </td>
                            <td>
                                <label for="cvp" class="lable-mvp">CVP:</label>
                            </td>
                            <td>
                                <input type="number" class="form-control" placeholder="" name="cvp" autocomplete="off" />
                            </td>
                            <td>
                                <label for="etc" class="lable-mvp">ETCO<small>2:</small></label>
                            </td>
                            <td>
                                <input type="number" class="form-control" placeholder="" name="etc" autocomplete="off" />
                            </td>
                            </tr>
                            {{--
                            <tr>
                                <td>
                                <label for="cvp" class="">CVP:</label>
                                </td>
                                <td>
                                <input type="text" class="form-control" placeholder="" name="cvp" autocomplete="off" />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                <label for="etc" class="">ETCO:<small>2</small></label>
                                </td>
                                <td>
                                <input type="text" class="form-control" placeholder="" name="etc" autocomplete="off" />
                                </td>
                            </tr>
                            --}}
                                </table> -->
                        </div>
                    </div>
                    <div class="iq-card iq-card-block">
                        <div class="iq-card-body">
                            <table style="width: 100%;">
                                <tr>
                                    <td>
                                        <label for="mode" class="lable-gcs">Mode</label>
                                    </td>
                                    <td>
                                        <select id="mode" class="form-control" name="mode">
                                            <option value="">--Select--</option>
                                            <option value="CMV">CMV</option>
                                            <option value="SIMV">SIMV</option>
                                            <option value="VCAC">VCAC</option>
                                            <option value="CPAP">CPAP</option>
                                            <option value="other" id="modeOtherOption">Other</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="fio" class="lable-gcs">FiO<small>2</small></label>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" min="0.01" class="form-control" placeholder=""
                                               name="fio" autocomplete="off"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="peep" class="lable-gcs">PEEP</label>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" min="0.01" class="form-control" placeholder=""
                                               name="peep" autocomplete="off"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="width-label-td">
                                        <label for="pressure_support" class="lable-gcs">Pressure Support</label>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" min="0.01" class="form-control" placeholder=""
                                               name="pressure_support" autocomplete="off"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="tidal_volume" class="lable-gcs">Tidal Volume</label>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" min="0.01" class="form-control" placeholder=""
                                               name="tidal_volume" autocomplete="off"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="minute_volume" class="lable-gcs">Minute Volume</label>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" min="0.01" class="form-control" placeholder=""
                                               name="minute_volume" autocomplete="off"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="ie" class="lable-gcs">I.E</label>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" min="0.01" class="form-control" placeholder=""
                                               name="ie" autocomplete="off"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="ventilator_extra" class="lable-gcs">Extra</label>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" min="0.01" class="form-control" placeholder=""
                                               name="ventilator_extra" autocomplete="off"/>
                                    </td>
                                </tr>
                            </table>
                            <br>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">Diagnosis</h4>
                            </div>
                            <div class="form-group">
                                <button type="button" class="btn btn-sm btn-primary mt-3" name="add_diagnosis"
                                        id="add_diagnosis" data-toggle="modal" data-placement="top"
                                        data-target="#addDiagnosisModal" title="Add Diagnosis"
                                        data-original-title="Add"><i class="fa fa-plus pr-0"></i></button>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <form action="" class="form-horizontal">
                                <div class="form-group mb-0">
                                    <ul class="list-group" style="height: 80px;overflow-y: scroll;"
                                        id="diagnosis_list_front">
                                        @if(isset($patient_diagnosis) && $patient_diagnosis->count() > 0 )
                                            @foreach( $patient_diagnosis as $patient_diagnos )
                                                <li class="list-group-item"
                                                    style="border-bottom: 1px solid #eae5e5 !important;">
                                                    {{ $patient_diagnos->fldcode }} <a class="delete-diagnosis"
                                                                                       data-fldid=" {{ $patient_diagnos->fldid ?? null }}"
                                                                                       href="#"
                                                                                       style="float: right;color: #dc3545;margin-right: -20px"><i
                                                                class="fa fa-trash"></i></a>
                                                </li>
                                            @endforeach
                                        @else
                                            <li class="list-group-item">
                                                {{--<a href="#">Diagnosis records not found</a>--}}
                                            </li>
                                        @endif
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
                                <img src="{{ asset('new/images/nervous_system.png') }}" alt="" class="body_anatomy"
                                     style="margin-top: -26px;"/>

                                <span class="circle_one" data-toggle="modal" data-target="#myModal"
                                      title="Cervical Spine" data-placement="top" data-tooltip="tooltip"> </span>

                                <!-- The Modal -->
                                <div class="modal" id="myModal">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h4 class="modal-title">Cervical Spine</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;
                                                </button>
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
                                                <button type="button" class="btn btn-success" data-dismiss="modal">
                                                    Save
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <span class="circle_two" data-toggle="modal" data-target="#myModal1"
                                      title="Thoracic Spine" data-placement="top" data-tooltip="tooltip"> </span>
                                <!-- The Modal -->
                                <div class="modal" id="myModal1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h4 class="modal-title">Thoracic Spine</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;
                                                </button>
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
                                                <button type="button" class="btn btn-success" data-dismiss="modal">
                                                    Save
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <span class="circle_three" data-toggle="modal" data-target="#myModal2"
                                      title="Lumber Spine" data-placement="top" data-tooltip="tooltip"> </span>
                                <!-- The Modal
                                                                -->
                                <div class="modal" id="myModal2">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h4 class="modal-title">Lumber Spine</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;
                                                </button>
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
                                                <button type="button" class="btn btn-success" data-dismiss="modal">
                                                    Save
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <span class="circle_four" data-toggle="modal" data-target="#myModal3"
                                      title="sacrococcygeal Spine" data-placement="top" data-tooltip="tooltip"> </span>

                                <!-- The Modal -->
                                <div class="modal" id="myModal3">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h4 class="modal-title">sacrococcygeal Spine</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;
                                                </button>
                                            </div>

                                            <!-- Modal body -->
                                            <div class="modal-body">
                                                <textarea name="sacrococcygeal_Spine" style="width: 89%;"></textarea>
                                            </div>

                                            <!-- Modal footer -->
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-success" data-dismiss="modal">
                                                    Save
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <span class="circle_five" data-toggle="modal" data-target="#myModal4"
                                      title="Right Upper Limbs" data-placement="top" data-tooltip="tooltip"> </span>

                                <!-- The Modal -->
                                <div class="modal" id="myModal4">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h4 class="modal-title">Right Upper Limbs</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;
                                                </button>
                                            </div>

                                            <!-- Modal body -->
                                            <div class="modal-body">
                                                {{-- <input type="number" step="0.1" name="Right_Upper_Limbs" style="width: 89%;" min="0" max="5" />--}} {{-- <textarea name="Right_Upper_Limbs" style="width: 89%;"></textarea>--}}
                                                <select style="width: 89%;" name="Right_Upper_Limbs"
                                                        class="form-control">
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
                                                <button type="button" class="btn btn-success" data-dismiss="modal">
                                                    Save
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <span class="circle_six" data-toggle="modal" data-target="#myModal5"
                                      title="Left Upper Limbs" data-placement="top" data-tooltip="tooltip"> </span>

                                <!-- The Modal -->
                                <div class="modal" id="myModal5">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h4 class="modal-title">Left Upper Limbs</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;
                                                </button>
                                            </div>

                                            <!-- Modal body -->
                                            <div class="modal-body">
                                                {{-- <input type="number" step="0.1" name="Left_Upper_Limbs" style="width: 89%;" min="0" max="5" />--}} {{-- <textarea name="Left_Upper_Limbs" style="width: 89%;"></textarea>--}}
                                                <select style="width: 89%;" name="Left_Upper_Limbs"
                                                        class="form-control">
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
                                                <button type="button" class="btn btn-success" data-dismiss="modal">
                                                    Save
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <span class="circle_seven" data-toggle="modal" data-target="#myModal6"
                                      title="Right Lower Limbs" data-placement="top" data-tooltip="tooltip"> </span>

                                <!-- The Modal -->
                                <div class="modal" id="myModal6">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h4 class="modal-title">Right Lower Limbs</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;
                                                </button>
                                            </div>

                                            <!-- Modal body -->
                                            <div class="modal-body">
                                                {{-- <input type="number" step="0.1" name="Right_Lower_Limbs" style="width: 89%;" min="0" max="5" />--}} {{-- <textarea name="Right_Lower_Limbs" style="width: 89%;"></textarea>--}}
                                                <select style="width: 89%;" name="Right_Lower_Limbs"
                                                        class="form-control">
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
                                                <button type="button" class="btn btn-success" data-dismiss="modal">
                                                    Save
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <span class="circle_eight" data-toggle="modal" data-target="#myModal7"
                                      title="Left Lower Limbs" data-placement="top" data-tooltip="tooltip"> </span>

                                <!-- The Modal -->
                                <div class="modal" id="myModal7">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h4 class="modal-title">Left Lower Limbs</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;
                                                </button>
                                            </div>

                                            <!-- Modal body -->
                                            <div class="modal-body">
                                                {{-- <input type="number" step="0.1" name="Left_Lower_Limbs" style="width: 89%;" min="0" max="5" />--}} {{-- <textarea name="Left_Lower_Limbs" style="width: 89%;"></textarea>--}}
                                                <select style="width: 89%;" name="Left_Lower_Limbs"
                                                        class="form-control">
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
                                                <button type="button" class="btn btn-success" data-dismiss="modal">
                                                    Save
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="iq-card iq-card-block">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">Pupils</h4>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <table style="width: 100%;">
                                <tbody>
                                <tr>
                                    <td>
                                        <label for="size" class="lable-gcs">Size</label>
                                    </td>
                                    <td>
                                        <select name="left_side_size" class="form-control" autocomplete="off">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                        </select>
                                        {{-- <input type="text" class="form-control" placeholder="" name="left_side_size" autocomplete="off" />--}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="reaction" class="lable-gcs">Reaction</label>
                                    </td>
                                    <td>
                                        <select class="form-control" name="left_side_reaction">
                                            <option value="Normal Reaction">Normal Reaction</option>
                                            <option value="No Reaction">No Reaction</option>
                                            <option value="Sluggish Reaction">Sluggish Reaction</option>
                                        </select>
                                        {{--<input type="text" class="form-control" placeholder="" name="left_side_reaction" autocomplete="off" />--}}
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="iq-card iq-card-block">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">Key ABG Parameter</h4>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <table style="width: 100%;">
                                <tr>
                                    <td>
                                        <label for="ph" class="lable-gcs">PH</label>
                                    </td>
                                    <td>

                                        <input type="number" step="0.01" min="0" class="form-control" placeholder=""
                                               name="ph" autocomplete="off"/>


                                    </td>
                                    <td>
                                        <label for="po" class="lable-gcs">PO<small>2</small></label>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" min="0.00" class="form-control" placeholder=""
                                               name="po" autocomplete="off"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="pco" class="lable-gcs">PCO<small>2</small></label>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" min="0.00" class="form-control" placeholder=""
                                               name="pco" autocomplete="off"/>
                                    </td>
                                    <td>
                                        <label for="hco" class="lable-gcs">HCO<small>3</small></label>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" min="0.00" class="form-control" placeholder=""
                                               name="hco" autocomplete="off" id="hco"/>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="iq-card iq-card-block border-none">
                        <div class="iq-card-header d-flex justify-content-between">
                            <h4 class="card-title">Intake</h4>
                            <a href="#collapseExample" class="btn btn-sm btn-primary collapsed mt-3"
                               data-toggle="collapse" role="button" aria-expanded="false"
                               aria-controls="collapseExample"><i class="fa fa-plus pr-0"></i></a>
                        </div>
                    </div>
                    <div class="iq-card-body p-0">
                        <div class="collapse mt-3" id="collapseExample" style="width: 100%;">
                            <div class="p-2">
                                <div class="table-neuro">
                                    <table class="table table-bordered table-responsive" style="width: 100%;">
                                        <thead>
                                        <tr>
                                            <th>Start Date</th>
                                            <th>Medicine</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(isset($fluid_list) && $fluid_list) @forelse($fluid_list as $fluid)
                                            <tr>
                                                <td>{{ $fluid->fldstarttime ?? null }}</td>
                                                <td>{{ $fluid->flditem ?? null }}</td>
                                                <td class="text-center">
                                                    <a
                                                            type="button "
                                                            title="Start"
                                                            class="btn check_btn prevent fluid_button"
                                                            data-toggle="modal"
                                                            data-id="{{ $fluid->fldid  }}"
                                                            data-target="#fluidModal"
                                                            id="fluid_start_btn"
                                                            data-medicine="{{ $fluid->flditem }}"
                                                            data-dose="{{ $fluid->flddose  }}"
                                                            data-frequency=" {{ $fluid->fldfreq }}"
                                                            data-days=" {{ $fluid->flddays }} "
                                                            data-status=" {{ $fluid->fldstatus }} "
                                                            data-start_time=" {{ $fluid->fldstarttime }}"
                                                    >
                                                        <i class="fas fa-play"></i>
                                                    </a>
                                                    <a type="button " class="btn check_btn prevent"
                                                       style="display: none;" id="fluid_pause_btn" title="Pause">
                                                        <i class="fas fa-pause"></i>
                                                    </a>
                                                    <a type="button " class="btn check_btn prevent"
                                                       style="display: none;" id="fluid_stop_btn" title="Stop">
                                                        <i class="fas fa-stop"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3">There is no fluid dispensed!!</td>
                                            </tr>
                                        @endforelse @else
                                            <tr>
                                                <td colspan="3">There is no fluid dispensed!!</td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                                <div class="table-neuro">
                                    <table class="table table-bordered table-responsive">
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
                                        @if(isset($fluid_particulars)) @forelse( $fluid_particulars as $particulars)
                                            <tr>
                                                <td>{{ $particulars->getName->flditem ?? null }}</td>
                                                <td>{{ $particulars->fldvalue ?? null }}</td>
                                                <td>{{ $particulars->fldunit ?? null }}</td>
                                                <td>{{ $particulars->fldfromtime ?? null }}</td>
                                                <td>{{ $particulars->fldtotime ?? null }}</td>
                                                <td>
                                                    @if( $particulars->fldstatus =='ongoing')
                                                        <button type="button" class="fluid_stop_btn"
                                                                data-stop_id="{{ $particulars->fldid ?? null }}"
                                                                data-dose_no="{{ $particulars->flddose ?? null }}"><i
                                                                    class="fas fa-stop"></i></button>
                                                    @elseif( $particulars->fldstatus =='stopped')
                                                        <button type="button"><i class="fas fa-lock"></i></button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty @endforelse @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="iq-card iq-card-block">
                        <div class="iq-card-body">
                            <table style="width: 100%;">
                                <tr>
                                    <td>
                                        <label for="ph" class="lable-gcs">GRBS</label>
                                    </td>
                                    <td>
                                        <input type="text" name="grbs" id="grbs" class="form-control" autocomplete="off"
                                               value=""/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="regular_insulin" class="lable-gcs">Regular Insulin</label>
                                    </td>
                                    <td>
                                        <input type="text" name="regular_insulin" id="regular_insulin"
                                               class="form-control" autocomplete="off" value=""/>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="iq-card iq-card-block border-none">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">Output</h4>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <table style="width: 100%;">
                                <tbody>
                                <tr>
                                    <td>
                                        <label for="urine" class="lable-gcs">Urine</label>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" min="0.01" class="form-control output"
                                               placeholder="" name="urine" autocomplete="off" id="urine"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="evd" class="lable-gcs">EVD</label>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" min="0.01" class="form-control output"
                                               placeholder="" name="evd" autocomplete="off" id="evd"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="drain" class="lable-gcs">Drain/Suction</label>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" min="0.01" class="form-control output"
                                               placeholder="" name="drain" autocomplete="off" id="drain"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="extra" class="lable-gcs">Extra</label>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" min="0.01" class="form-control output"
                                               placeholder="" name="extra" autocomplete="off" id="extra"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="total" class="lable-gcs">Total</label>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" min="0.01" class="form-control" placeholder=""
                                               name="total" autocomplete="off" id="output_total" readonly/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>{{--<button type="button" class="btn btn-danger">Save</button>--}}</td>
                                </tr>
                                </tbody>
                            </table>
                            <br><br>
                        </div>
                    </div>
                    <!-- The Modal -->
                    <div class="modal fade" id="fluidModal">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h4 class="modal-title" id="fluid_title"></h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <!-- Modal body -->
                                <div class="modal-body">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th>Start Date</th>
                                            <th>Medicine</th>
                                            <th>Dose</th>
                                            <th>Frequency</th>
                                            <th>Days</th>
                                            <th>Status</th>
                                            {{--
                                            <th>Action</th>
                                            --}}
                                        </tr>
                                        </thead>
                                        <tbody id="fluid_table_body"></tbody>
                                        {{--
                                        <tr>
                                        --}} {{--
                                <td>--}} {{-- <input type="text" class="form-control" --}} {{-- placeholder="" />--}} {{--</td>
                                --}} {{--
                                <td>--}} {{-- <label for="">ml/Hr</label>--}} {{--</td>
                                --}} {{--
                                </tr>
                                --}}
                                    </table>
                                    <table>
                                        <tr>
                                            <td><label>Enter rate of Administration in ML/Hour: </label></td>
                                            <td><input type="text" class="form-control" id="fluid_dose"/></td>
                                            <td><label id="empty_dose_alert" style="color: red;"></label></td>
                                        </tr>
                                    </table>
                                </div>
                                <!-- Modal footer -->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" id="fluid_modal_save_btn">Save</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Output -->
                <!-- CHESE & EYE SKIN CARE-->
                <div class="col-sm-12">
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">Chest</h4>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <table style="width: 100%;">
                                <tr>
                                    <td>
                                        <div class="form-group mb-2">
                                            <label for="chest_a" class="lable-gcs">Air Entry</label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group mb-2 mr-4">
                                            <select id="inputState" class="form-control" name="chest_a">
                                                <option value="Bilaterally_equal ">Bilaterally equal</option>
                                                <option value="Decreased_on_the_left_side ">Decreased on the left side
                                                </option>
                                                <option value="Decreased_on_the_right ">Decreased on the right</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group mb-2">
                                            <label for="chest_w" class="lable-gcs">Wheeze</label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group mr-4 mb-2">
                                            <select id="inputState" class="form-control" name="chest_w">
                                                <option value="No_wheeze">No wheeze</option>
                                                <option value="Bilateral_wheeze">Bilateral wheeze</option>
                                                <option value="Wheeze_on_the_left_side">Wheeze on the left side</option>
                                                <option value="Wheeze_on_the_right_side">Wheeze on the right side
                                                </option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group mb-2">
                                            <label for="chest_c" class="lable-gcs">Crackles</label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group mr-4 mb-2">
                                            <select id="inputState" class="form-control" name="chest_c">
                                                <option value="No_cracked">No cracked</option>
                                                <option value="Bilateral_crackles">Bilateral crackles</option>
                                                <option value="Crackles_on_the_left_side">Crackles on the left side
                                                </option>
                                                <option value="Crackles_on_the_right_side">Crackles on the right side
                                                </option>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- VAP-->
                <div class="col-sm-12">
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">VAP PROPHYLAXIS</h4>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="sat">Spontaneous Awakening Trial</label>
                                    <select name="sat" class="form-control">
                                        <option value="YES">YES</option>
                                        <option value="NO" selected>NO</option>
                                    </select>
                                    <small class="help-block text-danger">{{$errors->first('sat')}}</small>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="sbt">Spontaneous Breathing Trial</label>
                                    <select name="sbt" class="form-control">
                                        <option value="YES">YES</option>
                                        <option value="NO" selected>NO</option>
                                    </select>
                                    <small class="help-block text-danger">{{$errors->first('sbt')}}</small>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="are">Assessment of Readiness to Extubate</label>
                                    <select name="are" class="form-control">
                                        <option value="YES">YES</option>
                                        <option value="NO" selected>NO</option>
                                    </select>
                                    <small class="help-block text-danger">{{$errors->first('patient_name')}}</small>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="sib">Saline Instillation</label>
                                    <select name="sib" class="form-control">
                                        <option value="YES">YES</option>
                                        <option value="NO" selected>NO</option>
                                    </select>
                                    <small class="help-block text-danger">{{$errors->first('sib')}}</small>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="ehb">Elevation of Head of Bed to 30-45 degrees</label>
                                    <select name="ehb" class="form-control">
                                        <option value="YES">YES</option>
                                        <option value="NO" selected>NO</option>
                                    </select>
                                    <small class="help-block text-danger">{{$errors->first('ehb')}}</small>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="vcc">Ventilatory Circuit Check</label>
                                    <select name="vcc" class="form-control">
                                        <option value="YES">YES</option>
                                        <option value="NO" selected>NO</option>
                                    </select>
                                    <small class="help-block text-danger">{{$errors->first('vcc')}}</small>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="et_suction">ET Suction</label>
                                    <select name="et_suction" class="form-control">
                                        <option value="YES">YES</option>
                                        <option value="NO" selected>NO</option>
                                    </select>
                                    <small class="help-block text-danger">{{$errors->first('et_suction')}}</small>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="sedation">Sedation</label>
                                    <select name="sedation" class="form-control">
                                        <option value="YES">YES</option>
                                        <option value="NO" selected>NO</option>
                                    </select>
                                    <small class="help-block text-danger">{{$errors->first('sedation')}}</small>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="oral_digestive">Oral/Digestive</label>
                                    <select name="oral_digestive" class="form-control">
                                        <option value="YES">YES</option>
                                        <option value="NO" selected>NO</option>
                                    </select>
                                    <small class="help-block text-danger">{{$errors->first('oral_digestive')}}</small>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="oral_care">Oral Care</label>
                                    <select name="oral_care" class="form-control">
                                        <option value="YES">YES</option>
                                        <option value="NO" selected>NO</option>
                                    </select>
                                    <small class="help-block text-danger">{{$errors->first('oral_care')}}</small>
                                </div>
                                <div class="form-group col-md-4 col-lg-3">
                                    <label for="prophylactic_probiotics">Prophylactic Probiotics</label>
                                    <select name="prophylactic_probiotics" class="form-control">
                                        <option value="YES">YES</option>
                                        <option value="NO" selected>NO</option>
                                    </select>
                                    <small
                                            class="help-block text-danger">{{$errors->first('prophylactic_probiotics')}}</small>
                                </div>
                                <div class="form-group col-md-3 col-lg-2">
                                    <label for="et_cuff_pressure">ET Cuff Pressure</label>
                                    <input type="number" step="0.01" min="0.01" name="et_cuff_pressure"
                                           id="et_cuff_pressure" class="form-control"/>
                                    {{--
                                    <select name="et_cuff_pressure" id="et_cuff_pressure" class="form-control">
                                    --}} {{--
                            <option value="YES">YES</option>
                            --}} {{--
                            <option value="NO" selected>NO</option>
                            --}} {{--
                            </select>
                            --}}
                                    <small class="help-block text-danger">{{$errors->first('et_cuff_pressure')}}</small>
                                </div>
                                <div class="form-group col-md-3 col-lg-2">
                                    <label for="et_length">ET Length</label>
                                    <input type="number" step="0.01" min="0.01" name="et_length" id="et_length"
                                           class="form-control"/>
                                    {{--
                                    <select name="et_length" id="et_length" class="form-control">
                                    --}} {{--
                            <option value="YES">YES</option>
                            --}} {{--
                            <option value="NO" selected>NO</option>
                            --}} {{--
                            </select>
                            --}}
                                    <small class="help-block text-danger">{{$errors->first('et_length')}}</small>
                                </div>
                                <div class="form-group col-md-4 col-lg-3">
                                    <label for="stress_ulcer_prophylaxis">Stress Ulcer Prophylaxis</label>
                                    <select name="stress_ulcer_prophylaxis" class="form-control">
                                        <option value="YES">YES</option>
                                        <option value="NO" selected>NO</option>
                                    </select>
                                    <small
                                            class="help-block text-danger">{{$errors->first('stress_ulcer_prophylaxis')}}</small>
                                </div>
                                <div class="form-group col-md-3 col-lg-2">
                                    <label for="nebulization">Nebulization (NS) </label>
                                    <select name="nebulization" class="form-control">
                                        <option value="YES">YES</option>
                                        <option value="NO" selected>NO</option>
                                    </select>
                                    <small class="help-block text-danger">{{$errors->first('nebulization')}}</small>
                                </div>
                                <div class="form-group col-md-3 col-lg-3">
                                    <label for="nebulization_flohale">Nebulization (Flohale) </label>
                                    <select name="nebulization_flohale" class="form-control">
                                        <option value="YES">YES</option>
                                        <option value="NO" selected>NO</option>
                                    </select>
                                    {{--<input type="text" name="nebulization_flohale" id="nebulization_flohale" class="form-control" autocomplete="off" value="" />--}}
                                    <small
                                            class="help-block text-danger">{{$errors->first('nebulization_flohale')}}</small>
                                </div>
                                <div class="form-group col-md-3 col-lg-2">
                                    <label for="nebulization_ains">Nebulization (A:I:NS) </label>
                                    <select name="nebulization_ains" class="form-control">
                                        <option value="YES">YES</option>
                                        <option value="NO" selected>NO</option>
                                    </select>
                                    {{--<input type="text" name="nebulization_ains" id="nebulization_ains" class="form-control" autocomplete="off" value="" />--}}
                                    <small
                                            class="help-block text-danger">{{$errors->first('nebulization_ains')}}</small>
                                </div>
                                <div class="form-group col-md-3 col-lg-2">
                                    <label for="nebulization_nac">Nebulization (NAC) </label>
                                    <select name="nebulization_nac" class="form-control">
                                        <option value="YES">YES</option>
                                        <option value="NO" selected>NO</option>
                                    </select>
                                    {{--<input type="text" name="nebulization_nac" id="nebulization_nac" class="form-control" autocomplete="off" value="" />--}}
                                    <small class="help-block text-danger">{{$errors->first('nebulization_nac')}}</small>
                                </div>
                                {{--                        <div class="form-group col-md-3 col-lg-2">--}}
                                {{--                            <label for="sat">Regular Insulin</label>--}}
                                {{--                            <input type="text" name="regular_insulin" id="regular_insulin" class="form-control" autocomplete="off" value=""/>--}}
                                {{--                            <small class="help-block text-danger">{{$errors->first('regular_insulin')}}</small>--}}
                                {{--                        </div>--}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">Eye & Skin CARE</h4>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <table style="width: 100%;">
                                <tr>
                                    <td class="td-eye-input2">
                                        <div class="form-group mb-2">
                                            <label for="eye_e" class="lable-gcs">Eye</label>
                                        </div>
                                    </td>
                                    <td class="td-eye-input">
                                        <div class="form-group mr-4 mb-2">
                                            <select name="eye_e" id="eye_e" class="form-control">
                                                <option value="YES">YES</option>
                                                <option value="NO" selected>NO</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td class="td-eye-input2">
                                        <div class="form-group mb-2">
                                            <label for="eye_p" class="lable-gcs">Position</label>
                                        </div>
                                    </td>
                                    <td class="td-eye-input1">
                                        <div class="form-group mr-4 mb-2">
                                            <select id="inputState" class="form-control" name="eye_p">
                                                <option value="Supine">Supine</option>
                                                <option value="Left_latera">Left lateral</option>
                                                <option value="Right_lateral">Right lateral</option>
                                                <option value="Prone">Prone</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td class="td-eye">
                                        <div class="form-group mb-2">
                                            <label for="eye_b" class="lable-gcs">Back Care</label>
                                        </div>
                                    </td>
                                    <td class="td-eye-input">
                                        <div class="form-group mr-4 mb-2">
                                            <select name="eye_b" id="eye_b" class="form-control">
                                                <option value="YES">YES</option>
                                                <option value="NO" selected>NO</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td class="td-eye">
                                        <div class="form-group mb-2">
                                            <label for="eye_pp" class="lable-gcs">Pressure Points</label>
                                        </div>
                                    </td>
                                    <td class="td-eye-input">
                                        <div class="form-group mb-2">
                                            <input type="text" class="form-control" placeholder="" name="eye_pp"
                                                   autocomplete="off"
                                                   value=" {{ (isset($userDetail) && $userDetail) ? $userDetail->getFullNameAttribute() :  null }}"/>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Lines & Wound Care -->
                <div class="col-sm-12">
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">Lines & Wound Care</h4>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <table style="width: 100%;">
                                <tr>
                                    <td class="text-left">
                                        <label for="lines_f" class="lable-gcs">Foley’s: </label>
                                    </td>
                                    <td>
                                        <select name="lines_f" id="lines_f" class="form-control-sm form-control">
                                            <option value="YES">YES</option>
                                            <option value="NO" selected>NO</option>
                                        </select>
                                    </td>
                                    <td class="text-right">
                                        <label for="lines_cup" class="lable-lines">CVP</label>
                                    </td>
                                    <td>
                                        <select name="lines_cup" id="lines_cup" class="form-control-sm form-control">
                                            <option value="YES">YES</option>
                                            <option value="NO" selected>NO</option>
                                        </select>
                                    </td>
                                    <td class="text-right">
                                        <label for="lines_t" class="lable-lines">Tracheostomy </label>
                                    </td>
                                    <td>
                                        <select name="lines_t" id="lines_t" class="form-control-sm form-control">
                                            <option value="YES">YES</option>
                                            <option value="NO" selected>NO</option>
                                        </select>
                                    </td>
                                    <td class="text-right">
                                        <label for="lines_w" class="lable-lines">Wound </label>
                                    </td>
                                    <td>
                                        <select name="lines_w" id="lines_w" class="form-control-sm form-control">
                                            <option value="YES">YES</option>
                                            <option value="NO" selected>NO</option>
                                        </select>
                                    </td>
                                    <td class="text-right">
                                        <label for="lines_evd" class="lable-lines" :>EVD</label>
                                    </td>
                                    <td>
                                        <select name="lines_evd" id="lines_evd" class="form-control-sm form-control">
                                            <option value="YES">YES</option>
                                            <option value="NO" selected>NO</option>
                                        </select>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- TP Section -->
                <div class="col-sm-12">
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">Thromboembolic prophylaxis</h4>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <table style="width: 100%;">
                                <tr>
                                    <td style="width: 5%;">
                                        <label for="lines_physical_therapy" class="lable-gcs">T.P:</label>
                                    </td>
                                    <td>
                                        <select name="lines_tp" id="lines_tp" class="form-control col-md-5">
                                            <option value="YES">YES</option>
                                            <option value="NO" selected>NO</option>
                                        </select>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- End TP section -->
                <!-- Physical Therapy -->
                <div class="col-sm-12">
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">Physical Therapy</h4>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <table style="width: 100%;">
                                <tr>
                                    <td style="width: 5%;">
                                        <label for="lines_physical_therapy" class="lable-gcs">Chest:</label>
                                    </td>
                                    <td>
                                        <select name="chest_physical_therapy" id="chest_physical_therapy"
                                                class="form-control">
                                            <option value="YES">YES</option>
                                            <option value="NO" selected>NO</option>
                                        </select>
                                    </td>
                                    <td style="width: 7%;" class="text-right">
                                        <label for="lines_t" class="lable-lines">Limb:</label>
                                    </td>
                                    <td>
                                        <select name="limb_physical_therapy" id="limb_physical_therapy"
                                                class="form-control">
                                            <option value="YES">YES</option>
                                            <option value="NO" selected>NO</option>
                                        </select>
                                    </td>
                                    <td style="width: 13%;" class="text-right">
                                        <label for="" class="lable-lines">Ambulation:</label>
                                    </td>
                                    <td>
                                        <select name="ambulation_physical_therapy" id="ambulation_physical_therapy"
                                                class="form-control">
                                            <option value="wheel_chair">Wheel Chair</option>
                                            <option value="support">Support</option>
                                            <option value="mobilization">Mobilization</option>
                                            <option value="NO" selected>NO</option>
                                        </select>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">Drugs</h4>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <div class="res-table">
                                <table class="table table-bordered table-hover table-striped">
                                    <thead class="thead-light">
                                    <tr>
                                        <th style="margin-left: 10px;">Drug Name</th>
                                        <th style="margin-left: 10px;">Quantity</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody id="drug_list_js">
                                    @if(isset($drugs_list)) @forelse($drugs_list as $drug_list)
                                        <tr>
                                            <td>{{ $drug_list->flditem ?? null }}</td>
                                            <td><input type="text" name="drug_quantity" class="quantity"/></td>
                                            <td class="text-center">
                                                <input type="checkbox" data-id="{{ $drug_list->fldid }}"
                                                       data-name="{{ $drug_list->flditem }}"
                                                       data-dispensed="{{ $drug_list->fldqtydisp }}"
                                                       class="drug_checkbox"/>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center">No data found !! Please insert</td>
                                        </tr>
                                    @endforelse @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="form-group mt-3">
                                <button class="btn btn-primary float-right prevent" id="save_drug">Record Drug</button>
                            </div>
                            <label class="form_title" style="color: green;"><strong id="drug_status_message"> </strong></label>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">Notes</h4>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <div class="drugs_note">
                                <label style="color: green;"><strong id="note_status_message"></strong></label>
                                <div class="notes-body">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <select class="form-control" id="exampleFormControlSelect1" name="note_by">
                                                <option value="Nurses Note"> Nurse's Note</option>
                                                <option value="Nutritionists Note">Nutritionist's Notes</option>
                                                <option value="Physical Therapist Note">Physical Therapist's Notes
                                                </option>
                                                <option value="Medical Officer">Medical Officer's Notes</option>
                                                <option value="Attending Neurosurgeon Note">Attending Neurosurgeon
                                                    Notes
                                                </option>
                                                <option value="Additional Note">Additional Notes</option>
                                            </select>
                                        </div>
                                        <div class="col-md-1 col-md-offset-1 text-right">
                                            <button type="button" class="btn btn-primary" name="save_note"
                                                    id="save_note" title="Save Note"><i class="fa fa-save"></i></button>
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" class="btn btn-primary" name="note_list"
                                                    id="note_list" data-toggle="modal" data-target="#noteListModal"
                                                    title="Show Note list">
                                                <i class="fa fa-notes-medical"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row pt-2">
                                        <div class="col-md-12">
                                            <textarea class="form-control" id="notes_message" rows="3"
                                                      name="message"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mt-3">
                                    <button class="btn btn-primary float-right" type="submit">Create Record</button>
                                </div>
                                <div class="form-group mt-3">
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#exampleModalCenter">
                                        Generate Report
                                    </button>
                                </div>


                                <!-- Mode ko other ko Modal-->
                                <!-- Modal -->
                                <div class="modal fade" id="modeOther" tabindex="-1" role="dialog"
                                     aria-labelledby="modeOther" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">Other</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="text" id="modeOtherInpt" class="form-control">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                    Close
                                                </button>
                                                <button type="button" class="btn btn-primary" id="saveModeOther">Save
                                                    changes
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="modal" id="addDiagnosisModal">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h4 class="modal-title">ICD10 Database</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;
                                                </button>
                                            </div>
                                            <!-- Modal Diagnosis -->
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6" style="overflow: auto; height: 440px;">
                                                        <table class="table-bordered table table-diagnosis"
                                                               id="table-diagnosis" style="margin-bottom: 0px;">
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
                                                        <table class="table table-bordered table-diagnosis"
                                                               id="sub_diagnosis_table" style="max-height: 425px;">
                                                            <tbody id="sub_diagnosis_table_body"></tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Modal footer -->
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-primary" id="save_diagnosis"
                                                        title="Save Diagnosis">
                                                    Save
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Modal Generate Report-->
                                <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
                                     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">Please select
                                                    Date</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{$encounter_no?route('generate.report',$encounter_no):''  }}"
                                                  method="get" target="_blank">
                                                @csrf
                                                <div class="modal-body text-center">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label for="report_date">From</label>
                                                            <input type="text" name="report_date" id="from"
                                                                   class="form-control datepicker" autocomplete="off"
                                                                   required>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="report_date">To</label>
                                                            <input type="text" name="report_date_to" id="to"
                                                                   class="form-control datepicker" autocomplete="off"
                                                                   required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Close
                                                    </button>
                                                    <button class="btn btn-success" type="submit">Generate</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>


                                <div class="modal" id="noteListModal">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h4 class="modal-title">Notes</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;
                                                </button>
                                            </div>
                                            <!-- Modal body -->
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                            <th class="text-center">Note By</th>
                                                            <th class="text-center">Details</th>
                                                            <th class="text-center">Added by</th>
                                                            <th class="text-center">Action</th>
                                                            </thead>
                                                            <tbody id="notes_tbody">
                                                            @if( isset($Notes) && $Notes) @forelse( $Notes as $Note)
                                                                <tr>
                                                                    <td class="text-center">{{ $Note->flditem ?? null }}</td>
                                                                    <td class="text-center">{{ $Note->flddetail ?? null }}</td>
                                                                    <td class="text-center">{{ $Note->flduserid ?? null }}</td>
                                                                    <td class="text-center">
                                                                        @if(\App\Utils\Helpers::getCurrentUserName() == $Note->flduserid)
                                                                            <a href="javascript:void(0);"
                                                                               class="iq-bg-danger deleteNote"
                                                                               data-noteid="{{ $Note->fldid }}"><i
                                                                                        class="ri-delete-bin-5-fill"></i></a>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="8" class="text-center">Sorry No data
                                                                        found!!
                                                                    </td>
                                                                </tr>
                                                            @endforelse @endif
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Modal footer -->
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-primary" data-dismiss="modal"
                                                        title="Close">Close
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
                    <a href="javascript:void(0)" onclick="laboratory.displayModal()"
                       class="btn btn-primary  {{ $disableClass }}">Laboratory
                    </a>
                    <a href="javascript:void(0)" onclick="radiology.displayModal()"
                       class="btn btn-primary  {{ $disableClass }}">Radiology
                    </a>

                    <a href="javascript:void(0)" onclick="pharmacy.displayModal()"
                       class="btn btn-primary  {{ $disableClass }}">Pharmacy
                    </a>
                    <a href="javascript:void(0);" onclick="requestMenu.majorProcedureModal()"
                       class="btn btn-primary  {{ $disableClass }}">Procedure
                    </a>
                    <a href="{{ route('outpatient.history.generate', $patient->fldpatientval??0) }}?opd" target="_blank"
                       class="btn btn-primary {{ $disableClass }}">History
                    </a>
                    <a @if(isset($enpatient)) href="{{ route('outpatient.pdf.generate.opd.sheet', $enpatient->fldencounterval??0) }}?opd"
                       target="_blank" @else href="#" @endif class="btn btn-primary  {{ $disableClass }}">OPD Sheet
                    </a>
                    <a href="{{ route('reset.encounter') }}" onclick="return checkFormEmpty();"
                       class="btn btn-primary  {{ $disableClass }}">Save
                    </a>
                    <a href="javascript:;" data-toggle="modal" data-target="#finish_box" id="finish"
                       class="btn btn-primary  {{ $disableClass }}">Finish
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Buttom ko menu end bhaeko -->
    <!-- Footer -->
    {{--                    @include('frontend.layouts.footer')--}}
@endsection
@push('after-script')
    <script>
        $(document).ready(function () {
            var date = new Date();
            var date_string = date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + ('0' + date.getDate()).slice(-2);
            // var date_string = date.getFullYear() + '-' + ( '0' + date.getMonth() + 1).slice(-2) + '-' + ( '0' + date.getDate()).slice(-2);
            var nepaliDate = AD2BS(date_string);
            (nepaliDate) ? $('#from').val(nepaliDate) : null;
            (nepaliDate) ? $('#to').val(nepaliDate) : null;

        });

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

        $(".datepicker").nepaliDatePicker({});
        $(".prevent").click(function (e) {
            e.preventDefault();
        });
        CKEDITOR.replace('notes_message');
        // CKEDITOR.replace('notes_message', {
        //     removePlugins: ["ImageUpload", "elementspath"],
        // });

        var route = "{{ route('autocomplete') }}";
        $("#drug").keyup(function (e) {
            $.ajax({
                url: route,
                method: "get",
                data: {
                    term: $(this).val(),
                },
                success: function (data) {
                    $("#drug").autocomplete({
                        source: data,
                    });
                },
            });
        });
        /**
         * This is for calculating total GCS.
         */
        $(".gcs_class").change(function () {
            var e = $("#gcs_e").val() == undefined ? 0 : Number($("#gcs_e").val());
            var m = $("#gcs_m").val() == undefined ? 0 : Number($("#gcs_m").val());
            var v = $("#gcs_v").val() === 'T' ? 'T' : Number($("#gcs_v").val());
            // console.log(v);
            var total = (e + m);
            // e + v + m > 0 ?
            if ($("#gcs_v").val() === 'T') {
                total = total + 1 + "T";
                // $('#verbal_t').val(1);
            } else {
                $('#verbal_t').val("T");
                total = (e + m + v);
            }

            if($("#gcs_v").val() === 'none')
            {
                $("#total_gcs").empty('');
                total=0;
                total =(e + m) +1;
                total= isNaN(total) ? '' :total;
            }
            // console.log(total)
            $("#total_gcs").val(total);
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

        $(".input-blood-pressure").keyup(function () {
            var syst_bp = Number($("#input-syst-bp").val());
            var dyst_bp = Number($("#input-dyst-bp").val());

            var map_value = (syst_bp - dyst_bp) / 3 + dyst_bp;
            $("#map").val(map_value);
        });

        /**
         * Script for calculating BMI using Weight and Height
         * formula is  weight (kg) / [height (m)]2
         */

        $(".for_bmi").keyup(function () {
            var height = parseInt($("#height").val()); //converting height to meter for calculations
            var weight = parseInt($("#weight").val());
            var sm = Math.pow(parseFloat(height / 100), 2);
            var bmi = parseFloat(weight / sm).toFixed(2);
            //bmi.toFixed(1);
            if (bmi == NaN || bmi == "" || bmi == null) {
                $("#bmi").val(0);
            } else {
                $("#bmi").val(bmi);
            }
        });

        $("#exampleFormControlSelect1").change(function () {
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }
            CKEDITOR.instances[instance].setData("");
            $("#note_status_message").empty();
        });
        /**
         * Function for removing  diagnosis
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
         * Function for saving Notes and messages
         */
        $(document).on("click", "#save_note", function () {
            var store_notes = "{{ route('store.notes') }}";
            var note_by = $("#exampleFormControlSelect1").val();
            var encounter = $("#encounter_no").val();
            var message = CKEDITOR.instances["notes_message"].getData();
            if (encounter === '' || encounter === null) {
                showAlert('Missing encounter no', 'error');
                return false;
            }
            if (message === '' || message === null) {
                showAlert('Cannot save empty note', 'error');
                return false;
            }
            $.ajax({
                url: store_notes,
                method: "post",
                data: {
                    _token: "{{ csrf_token() }}",
                    note_by: note_by,
                    message: message,
                    encounter: encounter,
                },
                success: function (data) {
                    if (data.error) {
                        showAlert(data.error, 'error');
                    }
                    var notes_html = "";
                    $("#notes_tbody").empty();
                    $.each(data, function (index, value) {
                        var detail = value.flddetail ? value.flddetail : "&nbsp;";
                        notes_html += "<tr>";
                        notes_html += "<td align='center'>" + value.flditem + "</td>";
                        notes_html += "<td align='center'>" + detail + "</td>";
                        notes_html += "<td align='center'>" + value.flduserid + "</td>";
                        if (value.flduserid === "{{ \App\Utils\Helpers::getCurrentUserName() }}") {
                            notes_html += "<td align='center'><a href='javascript:void(0);' class='iq-bg-danger deleteNote' data-noteid=" + value.fldid + "><i class='ri-delete-bin-5-fill'></i></a> </td>";
                        } else {
                            notes_html += '<td></td>';
                        }
                        notes_html += "</tr>";
                    });

                    $("#notes_tbody").html("");
                    $("#notes_tbody").append(notes_html);
                    $("#note_status_message").empty().text("Note Saved Successfully.");
                    for (instance in CKEDITOR.instances) {
                        CKEDITOR.instances[instance].updateElement();
                    }
                    CKEDITOR.instances[instance].setData("");
                },
                error: function (data) {
                    $("#note_status_message").empty().text("Something Went Wrong.").css("color", "red");
                },
            });
        });

        /**
         * Function for saving Notes and messages
         */
        $(document).on("click", ".deleteNote", function () {

            var encounter = $("#encounter_no").val();
            var noteId = $(this).data('noteid');
            if (encounter === '' || encounter === null) {
                showAlert('Missing encounter no', 'error');
                return false;
            }
            if (noteId === '' || noteId === null) {
                showAlert('Something went Wrong', 'error');
                return false;
            }
            var delete_notes = "{{ route('delete.notes',':id') }}";
            delete_notes = delete_notes.replace(':id', noteId);
            $.ajax({
                url: delete_notes,
                method: "post",
                data: {
                    _token: "{{ csrf_token() }}",
                    encounter: encounter,
                },
                success: function (data) {
                    if (data.error) {
                        showAlert(data.error, 'error');
                    }
                    var notes_html = "";
                    $("#notes_tbody").empty();
                    $.each(data, function (index, value) {
                        var detail = value.flddetail ? value.flddetail : "&nbsp;";
                        notes_html += "<tr>";
                        notes_html += "<td align='center'>" + value.flditem + "</td>";
                        notes_html += "<td align='center'>" + detail + "</td>";
                        notes_html += "<td align='center'>" + value.flduserid + "</td>";
                        if (value.flduserid === "{{ \App\Utils\Helpers::getCurrentUserName() }}") {
                            notes_html += "<td align='center'><a href='javascript:void(0);' class='iq-bg-danger deleteNote' data-noteid=" + value.fldid + "><i class='ri-delete-bin-5-fill'></i></a> </td>";
                        } else {
                            notes_html += '<td></td>';
                        }
                        notes_html += "</tr>";
                    });

                    $("#notes_tbody").html("");
                    $("#notes_tbody").append(notes_html);
                    showAlert('Note Deleted Successfully');
                    for (instance in CKEDITOR.instances) {
                        CKEDITOR.instances[instance].updateElement();
                    }
                    CKEDITOR.instances[instance].setData("");
                },
                error: function (data) {
                    $("#note_status_message").empty().text("Something Went Wrong.").css("color", "red");
                },
            });
        });

        /**
         * function for displaying default value on load for GCS
         */
        $(document).ready(function () {
            var test = [];
            $.each($(".gcs_class option:selected"), function () {
                test.push($(this).val());
            });
            var total = 0;
            for (var i = 0; i < test.length; i++) {
                total += test[i] << 0;
            }
            total = total ? total : null;
            $("#total_gcs").val(total);
        });

        /**
         * This fucntion is used for adding drugs
         */
        var add_drug_route = "{{ route('store.drug') }}";
        $("#save_drug").click(function (e) {
            var status = true;
            $(".drug_checkbox").each(function (index, obj) {
                if (this.checked === true) {
                    var sib = $(this).parent().prev().find(".quantity").val();
                    if (sib == undefined || sib == "") {
                        $(this).parent().prev().find(".quantity").focus();
                        $("#drug_status_message").empty().text("Please enter quantity to record").css("color", "red");
                        status = false;
                        return false;
                    }
                }
            });
            var drugs = $(":checkbox:checked")
                .map(function () {
                    var id = $(this).data("id");
                    var name = $(this).data("name");
                    var quantity = $(this).parent().prev().find(".quantity").val();
                    return {
                        id: id,
                        name: name,
                        quantity: quantity,
                    };
                })
                .get();

            if (drugs.length <= 0) {
                $("#drug_status_message").empty().text("Please select drug to record").css("color", "red");
                status = false;
                return false;
            }


            if (status) {
                $("#drug_status_message").empty();
                $.ajax({
                    url: add_drug_route,
                    method: "post",
                    data: {
                        _token: "{{ csrf_token() }}",
                        drug: drugs,
                        encounter: $("#encounter_no").val(),
                    },
                    success: function (data) {
                        $("#drug_status_message").empty().text("Drug Recorded Successfully.").css("color", "green");
                        $("input:checkbox").attr("checked", false);
                        $(".quantity").val("");
                        // $('.quantity').each(function (index, obj){
                        //     $('.quantity').empty();
                        // });
                    },
                    error: function (data) {
                        $("#drug_status_message").empty().text("Cannot record now something went wrong.").css("color", "red");
                        $("input:checkbox").attr("checked", false);
                    },
                });
            } else {
                return false;
            }
        });

        /**
         * Function for displaying details of medicine
         */
        $(".fluid_button").click(function (e) {
            $("#fluid_title").text($(this).data("medicine"));
            var fluid_html = "";
            fluid_html += "<tr>";
            fluid_html += "<td>" + $(this).data("start_time") + "<t/d>";
            fluid_html += "<td>" + $(this).data("medicine") + "</td>";
            fluid_html += "<td>" + $(this).data("dose") + "</td>";
            fluid_html += "<td>" + $(this).data("frequency") + "</td>";
            fluid_html += "<td>" + $(this).data("days") + "</td>";
            fluid_html += "<td>" + $(this).data("status") + "</td>";
            // fluid_html+= '<td><button type="button" id="fluid_play_btn" title="Start Fluid"><i class="fas fa-play" ></i></button> &nbsp;';
            fluid_html += "<input class='data-id' type='hidden' data-val='" + $(this).data("id") + "'></tr>";
            $("#fluid_table_body").empty().html(fluid_html);
        });

        /**
         * Actions on save button and plotting data to particulars table
         */
        $(document).on("click", "#fluid_modal_save_btn", function () {
            if ($("#fluid_dose").val() == "") {
                $("#empty_dose_alert").text("Please end dose");
                $("#fluid_dose").focus();
            } else {
                var add_fluid_route = "{{ route('store.drug') }}";
                var id = $(".fluid_button").data("id");
                var value = $("#fluid_dose").val();
                var data_val = $("#fluid_table_body").find("input").attr("data-val");
                // return false;
                $.ajax({
                    url: add_fluid_route,
                    method: "post",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id,
                        type: "fluid",
                        status: "ongoing",
                        value: value,
                        encounter: $("#encounter_no").val(),
                    },
                    success: function (data) {
                        var particular_html = "";

                        var endtime = data.data.fldtotime ? data.data.fldtotime : "&nbsp;";
                        var name = data.data.name ? data.data.name : "&nbsp;";
                        particular_html += '<tr class="to_remove">';
                        particular_html += "<td>" + name + "</td>";
                        particular_html += "<td>" + data.data.fldvalue + "</td>";
                        particular_html += "<td>" + data.data.fldunit + "</td>";
                        particular_html += "<td>" + data.data.fldfromtime + "</td>";
                        particular_html += '<td class="endtime_js">' + endtime + "</td>";
                        particular_html += '<td><button type="button" class="fluid_stop_btn" data-stop_id = " ' + data.data.fldid + '" data-dose_no = "' + data.data.flddoseno + '"> <i class="fas fa-stop"></i></button></td>';
                        particular_html += "</tr>";
                        $("#fluid_particulars_body").append(particular_html);
                        $("#fluid_dose").val("");
                        $("#fluidModal").modal("toggle");
                        $("[data-id=" + data_val + "]").hide();
                    },
                    error: function (data) {
                        $("#drug_status_message").empty().text("Cannot Record now something went wrong.").css("color", "red");
                    },
                });
            }
        });
        /**
         * Actions on stop button
         */
        $(document).on("click", ".fluid_stop_btn", function () {
            var tr_elem = $(this).closest("tr");
            var stop_fluid_route = "{{ route('stop.fluid') }}";
            var id = $(this).data("stop_id");
            var dose_no = $(this).data("dose_no");
            $.ajax({
                url: stop_fluid_route,
                method: "post",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    dose_no: dose_no,
                    encounter: $("#encounter_no").val(),
                },
                success: function (data) {
                    $(tr_elem).find(".endtime_js").text(data.data.fldtotime);
                    var btn_elem = $(tr_elem).find("button.fluid_stop_btn");

                    $(btn_elem).attr("class", "");
                    $(btn_elem).find("i").attr("class", "fas fa-lock");

                    $(this).closest(".to_remove").remove();
                    return false;
                    $(elem).remove();
                    var particular_html = "";
                    var endtime = data.data.fldtotime ? data.data.fldtotime : "&nbsp;";
                    particular_html += "<td>" + endtime + "</td>";
                    particular_html += '<td><button type="button"><i class="fas fa-lock"></i></button></td>';
                    $("#fluid_particulars_body").append(particular_html);
                    $("#fluid_dose").val("");
                },
                error: function (data) {
                    $("#drug_status_message").empty().text("Cannot Record now something went wrong.").css("color", "red");
                },
            });
        });
        /**
         * For displaying total for  output
         */
        $(".output").keyup(function () {
            var urine = parseInt($("#urine").val()) ? parseInt($("#urine").val()) : 0;
            var evd = parseInt($("#evd").val()) ? parseInt($("#evd").val()) : 0;
            var drain = parseInt($("#drain").val()) ? parseInt($("#drain").val()) : 0;
            var extra = parseInt($("#extra").val()) ? parseInt($("#extra").val()) : 0;
            var total = urine + evd + drain + extra;
            total = total ? total : 0;
            $("#output_total").val(total);
        });

        $(document).ready(function () {
            $('[data-tooltip="tooltip"]').tooltip();

            $("#report_date").datepicker({
                //changeYear: true,
                changeMonth: true,
                dateFormat: "yy-mm-dd",
                autoclose: true,
            });
        });
        // $(document).ready(function() {
        //     $('body').tooltip({
        //         selector: "[data-tooltip=tooltip]",
        //         container: "body"
        //     });
        // });

        $('#mode').change(function () {
            if ($(this).val() === 'other') {
                $('#modeOther').modal('toggle');
            }
        });

        $('#saveModeOther').click(function () {
            var other = $('#modeOtherInpt').val();
            if (typeof other === undefined || other === null || other === '') {
                showAlert("Please enter other reason,Cannot be empty", 'error');
                return false;
            }
            $('#modeRemarks').val(other);
            $('#modeOther').modal('toggle');
        })
    </script>
@endpush
