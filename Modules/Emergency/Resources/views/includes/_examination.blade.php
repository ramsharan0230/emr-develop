<div class="col-sm-6">
    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-header d-flex justify-content-between">
            <div class="iq-header-title">
                <h4 class="card-title">Examination</h4>
            </div>
        </div>
        <div class="iq-card-body">
            <div class="examination-tab">
                <ul class="nav nav-tabs justify-content-center" id="myTab-two" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#general" role="tab" aria-controls="general"
                           aria-selected="true">General</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#systemic" role="tab" aria-controls="systemic"
                           aria-selected="false">Systemic</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent-1">
                    <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general">
                        <div id="accordion">
                            <div class="card">
                                <div class="card-header">
                                    <a class="card-link" data-toggle="collapse" href="#collapseOne">
                                        General
                                        <button type="button" class="btn btn-sm-f btn-primary float-right btn-sm mb-3">
                                            <i class="fa fa-chevron-down pr-0"></i></button>
                                    </a>
                                </div>
                                <div id="collapseOne" class="collapse" data-parent="#accordion">
                                    <div class="card-body">

                                        <div class="form-group form-row align-items-center">
                                            <label for="" class="control-label col-sm-4 mb-0">1. Pallor</label>
                                            <!-- <div class="col-sm-4">
                                              <div class="profile-form custom-control custom-checkbox custom-control-inline">
                                                <input type="checkbox" id="normal-flag" name="normal-flag" value="0" class="custom-control-input">

                                                <label class="custom-control-label" >Abnormal
                                                </label>
                                              </div>
                                            </div> -->
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" name="Pallor-plc"
                                                               class="custom-control-input Pallor-plc" value="Present"
                                                               @if(isset($Pallor) && !empty($Pallor) && $Pallor->fldrepquali == 'Present')  checked @endif>
                                                        <label for="" class="custom-control-label">Present</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" name="Pallor-plc"
                                                               class="custom-control-input Pallor-plc" value="Absent"
                                                               @if(isset($Pallor) && !empty($Pallor) && $Pallor->fldrepquali == 'Absent')  checked
                                                               @endif @if(empty($Pallor)) checked @endif>
                                                        <label for="" class="custom-control-label">Absent</label>
                                                    </div>
                                                </div>


                                            <!-- <select name="Pallor-plc" id="Pallor-plc" class="form-control">
                         <option></option>
                         <option value="Present" @if(isset($Pallor) && !empty($Pallor) && $Pallor->fldrepquali == 'Present')  checked @endif>Present</option>
                         <option value="Absent" @if(isset($Pallor) && !empty($Pallor) && $Pallor->fldrepquali == 'Absent')  checked @endif>Absent</option>
                       </select> -->
                                            </div>
                                        </div>
                                        <div class="form-group form-row align-items-center">
                                            <label for="" class="control-label col-sm-4 mb-0">2. Icterus
                                            </label>
                                            <!-- <div class="col-sm-4">
                                              <div class="profile-form custom-control custom-checkbox custom-control-inline">
                                                <input type="checkbox" id="normal-flag" name="normal-flag" value="0" class="custom-control-input">

                                                <label class="custom-control-label" >Abnormal
                                                </label>
                                              </div>
                                            </div> -->
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" name="Icterus-plc"
                                                               class="custom-control-input Icterus-plc" value="Present"
                                                               @if(isset($Icterus) && !empty($Icterus) && $Icterus->fldrepquali == 'Present')  checked @endif>
                                                        <label for="" class="custom-control-label">Present</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" name="Icterus-plc"
                                                               class="custom-control-input Icterus-plc" value="Absent"
                                                               @if(isset($Icterus) && !empty($Icterus) && $Icterus->fldrepquali == 'Absent')  checked
                                                               @endif @if(empty($Icterus)) checked @endif>
                                                        <label for="" class="custom-control-label">Absent</label>
                                                    </div>
                                                </div>

                                            <!-- <select name="Icterus-plc" id="Icterus-plc" class="form-control">
                        <option></option>
                        <option value="Present" @if(isset($Icterus) && !empty($Icterus) && $Icterus->fldrepquali == 'Present')  checked @endif>Present</option>
                        <option value="Absent" @if(isset($Icterus) && !empty($Icterus) && $Icterus->fldrepquali == 'Absent')  checked @endif>Absent</option>
                      </select> -->
                                            </div>
                                        </div>
                                        <div class="form-group form-row align-items-center">
                                            <label for="" class="control-label col-sm-4 mb-0">3. Cyanosis</label>
                                            <!-- <div class="col-sm-4">
                                              <div class="profile-form custom-control custom-checkbox custom-control-inline">

                                                <input type="checkbox" id="normal-flag" name="normal-flag" value="0" class="custom-control-input">
                                                <label class="custom-control-label" >Abnormal
                                                </label>
                                              </div>
                                            </div> -->
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" name="Cyanosis-plc"
                                                               class="custom-control-input Cyanosis-plc" value="Present"
                                                               @if(isset($Cyanosis) && !empty($Cyanosis) && $Cyanosis->fldrepquali == 'Present')  checked @endif>
                                                        <label for="" class="custom-control-label">Present</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" name="Cyanosis-plc"
                                                               class="custom-control-input Cyanosis-plc" value="Absent"
                                                               @if(isset($Cyanosis) && !empty($Cyanosis) && $Cyanosis->fldrepquali == 'Absent')  checked
                                                               @endif @if(empty($Cyanosis)) checked @endif>
                                                        <label for="" class="custom-control-label">Absent</label>
                                                    </div>
                                                </div>

                                            <!-- <select name="Cyanosis-plc" id="Cyanosis-plc" class="form-control">
                        <option></option>
                        <option value="Present" @if(isset($Cyanosis) && !empty($Cyanosis) && $Cyanosis->fldrepquali == 'Present')  checked @endif>Present</option>
                        <option value="Absent" @if(isset($Cyanosis) && !empty($Cyanosis) && $Cyanosis->fldrepquali == 'Absent')  checked @endif>Absent</option>
                      </select> -->
                                            </div>
                                        </div>
                                        <div class="form-group form-row align-items-center">
                                            <label for="" class="control-label col-sm-4 mb-0">4. Clubbing</label>
                                            <!-- <div class="col-sm-4">
                                              <div class="profile-form custom-control custom-checkbox custom-control-inline">
                                                <input type="checkbox" id="normal-flag" name="normal-flag" value="0" class="custom-control-input">
                                                <label class="custom-control-label" >Abnormal
                                                </label>
                                              </div>
                                            </div> -->
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" name="Clubbing-plc"
                                                               class="custom-control-input Clubbing-plc" value="Present"
                                                               @if(isset($Clubbing) && !empty($Clubbing) && $Clubbing->fldrepquali == 'Present')  checked @endif>
                                                        <label for="" class="custom-control-label">Present</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" name="Clubbing-plc"
                                                               class="custom-control-input Clubbing-plc" value="Absent"
                                                               @if(isset($Clubbing) && !empty($Clubbing) && $Clubbing->fldrepquali == 'Absent')  checked
                                                               @endif @if(empty($Clubbing)) checked @endif>
                                                        <label for="" class="custom-control-label">Absent</label>
                                                    </div>
                                                </div>

                                            <!-- <select name="Clubbing-plc" id="Clubbing-plc" class="form-control">
                        <option></option>
                        <option value="Present" @if(isset($Clubbing) && !empty($Clubbing) && $Clubbing->fldrepquali == 'Present')  checked @endif>Present</option>
                        <option value="Absent" @if(isset($Clubbing) && !empty($Clubbing) && $Clubbing->fldrepquali == 'Absent')  checked @endif>Absent</option>
                      </select> -->
                                            </div>
                                        </div>
                                        <div class="form-group form-row align-items-center">
                                            <label for="" class="control-label col-sm-4 mb-0">5. Oedema</label>
                                            <!-- <div class="col-sm-4">
                                              <div class="profile-form custom-control custom-checkbox custom-control-inline">
                                                <input type="checkbox" id="normal-flag" name="normal-flag" value="0" class="custom-control-input">
                                                <label class="custom-control-label" >Abnormal
                                                </label>
                                              </div>
                                            </div> -->
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" name="Oedema-plc"
                                                               class="custom-control-input Oedema-plc" value="Present"
                                                               @if(isset($Oedema) && !empty($Oedema) && $Oedema->fldrepquali == 'Present')  checked @endif>
                                                        <label for="" class="custom-control-label">Present</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" name="Oedema-plc"
                                                               class="custom-control-input Oedema-plc" value="Absent"
                                                               @if(isset($Oedema) && !empty($Oedema) && $Oedema->fldrepquali == 'Absent')  checked
                                                               @endif @if(empty($Oedema)) checked @endif>
                                                        <label for="" class="custom-control-label">Absent</label>
                                                    </div>
                                                </div>

                                            <!-- <select name="Oedema-plc" id="Oedema-plc" class="form-control">
                        <option></option>
                        <option value="Present" @if(isset($Oedema) && !empty($Oedema) && $Oedema->fldrepquali == 'Present')  checked @endif>Present</option>
                        <option value="Absent" @if(isset($Oedema) && !empty($Oedema) && $Oedema->fldrepquali == 'Absent')  checked @endif>Absent</option>
                      </select> -->
                                            </div>
                                        </div>
                                        <div class="form-group form-row align-items-center">
                                            <label for="" class="control-label col-sm-4 mb-0">6. Dehydration</label>
                                            <!-- <div class="col-sm-4">
                                              <div class="profile-form custom-control custom-checkbox custom-control-inline">
                                                <input type="checkbox" id="normal-flag" name="normal-flag" value="0" class="custom-control-input">
                                                <label class="custom-control-label" >Abnormal
                                                </label>
                                              </div>
                                            </div> -->
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" name="Dehydration-plc"
                                                               class="custom-control-input Dehydration-plc"
                                                               value="Present"
                                                               @if(isset($Dehydration) && !empty($Dehydration) && $Dehydration->fldrepquali == 'Present')  checked @endif>
                                                        <label for="" class="custom-control-label">Present</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" name="Dehydration-plc"
                                                               class="custom-control-input Dehydration-plc"
                                                               value="Absent"
                                                               @if(isset($Dehydration) && !empty($Dehydration) && $Dehydration->fldrepquali == 'Absent')  checked
                                                               @endif @if(empty($Dehydration)) checked @endif>
                                                        <label for="" class="custom-control-label">Absent</label>
                                                    </div>
                                                </div>

                                            <!-- <select name="Dehydration-plc" id="Dehydration-plc" class="form-control">
                        <option></option>
                        <option value="Present" @if(isset($Dehydration) && !empty($Dehydration) && $Dehydration->fldrepquali == 'Present')  checked @endif>Present</option>
                        <option value="Absent" @if(isset($Dehydration) && !empty($Dehydration) && $Dehydration->fldrepquali == 'Absent')  checked @endif>Absent</option>
                      </select> -->
                                            </div>
                                        </div>
                                        <a href="javascript:;" type="button"
                                           class="btn btn-primary float-right disableInsertUpdate"
                                           url="{{ route('insert_general_exam') }}" id="insert_general_exam">
                                            Save</a>

                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <a class="collapsed card-link headgpc" data-toggle="collapse" href="#collapseTwo">
                                        GCS
                                        <button type="button" class="btn btn-sm-f btn-primary float-right btn-sm mb-3">
                                            <i class="fa fa-chevron-down pr-0"></i></button>
                                    </a>
                                </div>
                                <div id="collapseTwo" class="collapse" data-parent="#accordion">
                                    <div class="card-body">

                                        <div class="form-group row">
                                            <label class="control-label col-sm-2 align-self-center text-center mb-0"
                                                   for="">Eye:</label>
                                            <div class="col-sm-10">
                                                <select class="form-control gcs_class" id="gcs_e" name="e">
                                                    <option value="4" selected>Spontaneous</option>
                                                    <option value="3">To speech</option>
                                                    <option value="2">To pain</option>
                                                    <option value="1">None</option>
                                                </select>

                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="control-label col-sm-2 align-self-center text-center mb-0"
                                                   for="">Verbal:</label>
                                            <div class="col-sm-10">
                                                <select class="form-control gcs_class" id="gcs_v" name="v">
                                                    <option value="5" selected>Oriented</option>
                                                    <option value="4">Confused</option>
                                                    <option value="3">Words</option>
                                                    <option value="2">Sounds</option>
                                                    <option value="1">None</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="control-label col-sm-2 align-self-center text-center mb-0"
                                                   for="">Motor:</label>
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
                                        <div class="form-group row">
                                            <label class="control-label col-sm-2 align-self-center text-center mb-0"
                                                   for="">Total:</label>
                                            <div class="col-sm-10">
                                                <input type="number" class="form-control gcs_class" id="total_gcs"
                                                       placeholder="" name="total_gcs" autocomplete="off" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group text-right">
                                            <a href="javascript:;" type="button"
                                               class="btn btn-primary disableInsertUpdate"
                                               url="{{ route('insert_gcs') }}" id="insert_gcs">
                                                Save</a>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="systemic" role="tabpanel" aria-labelledby="systemic">
                        <form action="" class="form-horizontal">
                            <div class="form-group from-row">
                                <div class="col-sm-12 padding-none">
                                    <select name="fldhead" id="find_fldhead"
                                            class="select-01 full-width form-control find_fldhead" style="width: 100%;">
                                        <option value="" selected></option>
                                        @if(isset($finding))
                                            @foreach($finding as $k => $exam)
                                                <option value="{{ $exam->fldexamid }}"
                                                        typeoption="{{ $exam->fldoption }}"
                                                        fldsysconst="{{ $exam->fldsysconst }}"
                                                        fldtype="{{ $exam->fldtype }}">{{ $exam->fldexamid }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <input type="hidden" name="fldtype" id="find_fldtype"
                                           value="@if(isset($exam)){{$exam->fldtype}}@endif"/>
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
                                    <th>Report Time</th>
                                </tr>
                                </thead>
                                @if(isset($patientexam))
                                    <tbody id="js-outpatient-findings-tbody">
                                    @foreach($patientexam as $pexam)
                                        <tr data-fldid="{{ $pexam->fldid }}">

                                            <td>{{ $pexam->fldhead}}</td>
                                            <td><a href="javascript:;" data-toggle="modal"
                                                   data-target="#findingnormalflag" class="clicked_flag"
                                                   clicked_flag_val="{{ $pexam->fldid }}">
                                                    <i @if($pexam->fldabnormal == 0 ) style="color:green"
                                                       @elseif($pexam->fldabnormal == 1) style="color:red"
                                                       @endif class="fas fa-square"></i>
                                                </a></td>
                                            <td>{!! $pexam->fldrepquali !!}</td>
                                            <td><a href="javascript:;" permit_user="{{ $pexam->flduserid }}"
                                                   class="delete_finding text-danger {{ $disableClass }}"
                                                   url="{{ route('emergency_delete_finding',$pexam->fldid) }}"> <i
                                                            class="ri-delete-bin-5-fill"> </a></td>
                                        <!-- <td><a href="javascript:;" permit_user="{{ $pexam->flduserid }}"  data-toggle="modal" data-target="#edit_finding" class="clicked_edit_finding" clicked_flag_val="{{ $pexam->fldid }}">

                      <i class="fas fa-edit"></i></a></td> -->
                                            <td>{{ $pexam->fldtime}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                @endif

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="findingnormalflag" tabindex="-1" role="dialog" aria-labelledby="findingnormalflagLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post" action="{{ route('er_update_abnormal') }}">
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
                    <input type="submit" name="submit" id="submitflag" class="btn btn-primary" value="Save changes">
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(".group__box__er").children('input').hide();
    $(document).ready(function () {
        $("#Button1").click(function () {
            // show hide paragraph on button click
            var div1 = document.getElementById('Div1');
            var div2 = document.getElementById('Div2');


            if (div2.style.display == 'block') {
                div1.style.display = 'block';
                div2.style.display = 'none';
            } else {
                div1.style.display = 'none';
                div2.style.display = 'block';

            }

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

        $('#total_gcs').val(total);

    });

    /**
     * This is for calculating total GCS.
     */
    $(".gcs_class").change(function () {
        var e = ($('#gcs_e').val() == undefined) ? 0 : Number($('#gcs_e').val());
        var v = ($('#gcs_v').val() == undefined) ? 0 : Number($('#gcs_v').val());
        var m = ($('#gcs_m').val() == undefined) ? 0 : Number($('#gcs_m').val());
        var total = (e + v + m) > 0 ? (e + v + m) : 0;
        $('#total_gcs').val(total);
    });

    $("#insert_gcs").click(function () {
        var url = $(this).attr('url');
        var fldencounterval = $("#fldencounterval").val();
        var e = Number($('#gcs_e').val());
        var v = Number($('#gcs_v').val());
        var m = Number($('#gcs_m').val());
        var total_gcs = $('#total_gcs').val();
        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: {
                e: e,
                v: v,
                m: m,
                fldencounterval: fldencounterval,
                total_gcs: total_gcs
            },
            success: function (data) {

                showAlert("Information saved!!");

            }
        });
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

    $(document).ready(function () {
        $(".group__box__er").children('input').hide();
        $(document).on('change', '.custom-checkbox-er', function () {
            if (this.checked) {
                $(this).siblings(".group__box__er").children('input').show();
                $(this).siblings(".group__box__er").children('select').hide();
            } else {
                $(this).siblings(".group__box__er").children('input').hide();
                $(this).siblings(".group__box__er").children('select').show();
            }
        });

    });


    $(document).on('click', '#submitscale_box', function() {
        var flditem = $("#scale_box").find(".modal_flditem").val();
        var fldsysconst = $("#scale_box").find(".modal_fldsysconst").val();
        var fldtype = $("#scale_box").find(".modal_fldtype").val();
        var fldencounterval = $("#scale_box").find(".modal_fldencounterval").val();
        var content =$("input[name=content]").val();

        if(fldencounterval==''){
            showAlert('Please enter encounter','error');
            return false;
        }
        var url = "{{ route('scale_save_emergency') }}";
        var data = {
            fldencounterval:fldencounterval,
            fldtype:fldtype,
            flditem:flditem,
            fldsysconst:fldsysconst,
            fldrepquali:content
        };
        var modal = "#scale_box"
        saveData(url,data,modal);

    });


    $(document).on('click', '#submitlnr_box', function() {
        var flditem = $("#lnr_box").find(".modal_flditem").val();
        var fldsysconst = $("#lnr_box").find(".modal_fldsysconst").val();
        var fldtype = $("#lnr_box").find(".modal_fldtype").val();
        var fldencounterval = $("#lnr_box").find(".modal_fldencounterval").val();
        var left =$('#lnr_left').val();
        var right =$('#lnr_right').val();

        if($("#fldencounterval").val()==''){
            showAlert('Please enter encounter','error');
            return false;
        }

        var url = "{{ route('lnrsave_emergency') }}";
        var data = {
            fldencounterval:fldencounterval,
            fldtype:fldtype,
            flditem:flditem,
            fldsysconst:fldsysconst,
            left:left,
            right:right,
        };
        console.log(data);
        var modal = "#lnr_box"
        saveData(url,data,modal);

    });

    $(document).on('click', '#submitnumber_box', function() {

        var flditem = $("#number_box").find(".modal_flditem").val();
        var fldsysconst = $("#number_box").find(".modal_fldsysconst").val();
        var fldtype = $("#number_box").find(".modal_fldtype").val();
        var fldencounterval = $("#number_box").find(".modal_fldencounterval").val();
        var content =$("input[name=content]").val();

        if($("#fldencounterval").val()==''){
            showAlert('Please enter encounter','error');
            return false;
        }
        var url = "{{ route('number_save_emergency') }}";
        var data = {
            fldencounterval:fldencounterval,
            fldtype:fldtype,
            flditem:flditem,
            fldsysconst:fldsysconst,
            fldrepquali:content
        };
        var modal = "#number_box"
        saveData(url,data,modal);

    });

    $(document).on('click', '#text_box', function() {

        var flditem = $("#text_box").find(".modal_flditem").val();
        var fldsysconst = $("#text_box").find(".modal_fldsysconst").val();
        var fldtype = $("#text_box").find(".modal_fldtype").val();
        var fldencounterval = $("#text_box").find(".modal_fldencounterval").val();
        var box_content =$("input[name=box_content]").val();

        if($("#fldencounterval").val()==''){
            showAlert('Please enter encounter','error');
            return false;
        }
        var url = "{{ route('text_save_emergency') }}";
        var data = {
            fldencounterval:fldencounterval,
            fldtype:fldtype,
            flditem:flditem,
            fldsysconst:fldsysconst,
            box_content:box_content
        };
        var modal = "#text_box"
        saveData(url,data,modal);

    });

    function saveData(url, data,modal) {
        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: data,
            success: function (data) {
                if ($.isEmptyObject(data.error)) {
                    $('#js-outpatient-findings-tbody').append(data.html);
                    showAlert("Information saved!!");
                    $(modal).modal('hide');
                    //location.reload();
                } else {
                    alert("Something went wrong!!");
                }
            }
        });
    }


</script>