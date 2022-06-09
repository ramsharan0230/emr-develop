@extends('frontend.layouts.master')
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
{{--navbar--}}
@include('menu::common.emergency-nav-bar')
{{--end navbar--}}

<!-- TOP Nav Bar END -->
<div class="row">
    {{--patient profile--}}
    @include('frontend.common.patientProfile')
    {{--end patient profile--}}

    @include('emergency::includes._trainge')
    @include('emergency::includes.refer-in')

    @include('emergency::includes._essential-exam')

    @include('emergency::includes._allergy')

    @include('emergency::includes._diagnosis')

    @include('emergency::includes._cheif-complaints')
    @include('emergency::includes._examination')
    @include('emergency::includes._history-tabs')
    <div class="col-sm-12">
        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-body">
                <div class="d-flex justify-content-around">
                    <a href="javascript:void(0)" onclick="laboratory.displayModal()">
                        <button class="btn btn-primary">Laboratory</button>
                    </a>
                    <a href="javascript:void(0)" onclick="radiology.displayModal()">
                        <button class="btn btn-primary">Radiology</button>
                    </a>

                    <a href="javascript:void(0)" onclick="pharmacy.displayModal()">
                        <button class="btn btn-primary">Pharmacy</button>
                    </a>
                    <a href="javascript:void(0);" onclick="requestMenu.majorProcedureModal()">
                        <button class="btn btn-primary">Procedure</button>
                    </a>
                    <a href="{{ route('emergency.pdf.generate.opd.sheet', $enpatient->fldencounterval??0) }}" target="_blank">
                        <button class="btn btn-primary">ER sheets</button>
                    </a>
                    <a href="{{ route('emergency.reset.encounter') }}">
                        <button class="btn btn-primary">Save</button>
                    </a>
                    <a href="javascript:;" data-toggle="modal" data-target="#finish_box" id="finish" class="">
                        <button class="btn btn-primary">Finish</button>
                    </a>
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                    Form modal
                    </button>
                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xxl  modal-dialog-scrollable" role="document">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Modal form</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-sm-2">
                                        <div class="form-group form-row">
                                            <label class="col-sm-5"><strong>Patient ID:</strong></label>
                                            <div class="col-sm-7">
                                                <label><strong>12344556</strong></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group form-row">
                                            <label class=""><strong>Inpatient ID:</strong></label>&nbsp;
                                            <div class="">
                                                <label><strong>1233453</strong></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group form-row">
                                            <label class=""><strong>Patient Name:</strong></label>
                                            <div class="col-sm-8">
                                                <label><strong>Pasanga Lama</strong></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group form-row">
                                            <label class=""><strong>Age/Gender:</strong></label>&nbsp;
                                            <div class="">
                                                <label><strong>23Y/M</strong></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group form-row">
                                            <label class=""><strong>DOB:</strong></label>
                                            <div class="col-sm-8">
                                                <label><strong>2054-01-10</strong></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <!-- <div class="col-sm-2">
                                        <div class="form-group">
                                            <label class="col-sm-5">Code no.:</label>
                                            <div class="col-sm-7">
                                                <input type="text" id="" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="col-sm-4">Address:</label>
                                            <div class="col-sm-8">
                                                <input type="text" id="" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="col-sm-4">Education:</label>
                                            <div class="col-sm-8">
                                                <input type="text" id="" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-sm-5">Date and Time of Arrival:</label>
                                            <div class="col-sm-7">
                                            <input type="datetime-local" class="form-control" id="" value="2019-12-19T13:45:00">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label class="col-sm-5">MLC no.:</label>
                                            <div class="col-sm-7">
                                                <input type="text" id="" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="col-sm-4">Brought By:</label>
                                            <div class="col-sm-8">
                                                <input type="text" id="" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="col-sm-4 pr-0">Police Station:</label>
                                            <div class="col-sm-8">
                                                <input type="text" id="" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-sm-5">Date and Time of commencement:</label>
                                            <div class="col-sm-7">
                                            <input type="datetime-local" class="form-control" id="" value="2019-12-19T13:45:00">
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                                <div class="col-sm-2">
                                        <div class="form-group">
                                            <label class="">Code no.:</label>
                                            <div class="">
                                                <input type="text" id="" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="">Address:</label>
                                            <div class="">
                                                <input type="text" id="" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 pl-2">
                                        <div class="form-group">
                                            <label class="">Education:</label>
                                            <div class="">
                                                <input type="text" id="" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="">Date and Time of arrival in hospital:</label>
                                            <div class="">
                                            <input type="datetime-local" class="form-control" id="" value="2019-12-19T13:45:00">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label class="">MLC no.:</label>
                                            <div class="">
                                                <input type="text" id="" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="">Brought By:</label>
                                            <div class="">
                                                <input type="text" id="" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 pl-2">
                                        <div class="form-group">
                                            <label class="">Police Station:</label>
                                            <div class="">
                                                <input type="text" id="" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="">Date and Time of commencement of examination:</label>
                                            <div class="">
                                            <input type="datetime-local" class="form-control" id="" value="2019-12-19T13:45:00">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group form-row">
                                            <label class="col-sm-5">Whether Conscious, Oriented in time place and person:</label>
                                            <div class="col-sm-7">
                                                <input type="txet" class="form-control" id="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group form-row">
                                            <label class="col-sm-5">Any physical/intellectual/psychosocial diability (Interpreters or special educators
                                            wil be needed where the survivor has special needs such as heraing/speech disability,
                                            language barriers, intellectual or psychosocial diasability) :</label>
                                            <div class="col-sm-7">
                                                <textarea class="form-control" rows="4"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group form-row">
                                            <label class="col-sm-5"> Informed Consent/refusal :</label>
                                            <div class="col-sm-7">
                                                <input type="txet" class="form-control" id="">
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                                <hr>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group form-row">
                                            <label class="col-sm-5"><strong>Here by give my consent for :</strong></label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="">Medical examination for treatment:</label>
                                            <div class="">
                                                <select id="" class="form-control">
                                                    <option value="">Yes</option>
                                                    <option value="">No</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="">This medco legal examination:</label>
                                            <div class="">
                                                <select id="" class="form-control">
                                                    <option value="">Yes</option>
                                                    <option value="">No</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="">Sample collection for clinical & forensic examination:</label>
                                            <div class="">
                                                <select id="" class="form-control">
                                                    <option value="">Yes</option>
                                                    <option value="">No</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="">Sharing non identifiable information for responding purposes:</label>
                                            <div class="">
                                                <select id="" class="form-control">
                                                    <option value="">Yes</option>
                                                    <option value="">No</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class=""> To take photographs:</label>
                                            <div class="">
                                                <select id="" class="form-control">
                                                    <option value="">Yes</option>
                                                    <option value="">No</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-12 mb-3">
                                        <label class=""><strong>I would like the information released to the following for service provision (Tick all that apply and specify the name of personnel and organization as applicable.):</strong></label>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="custom-control custom-checkbox custom-control-inline">
                                            <input type="checkbox" class="custom-control-input" id="">
                                            <label class="custom-control-label" for="">Psychosocial services</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-2 p-0">
                                        <div class="custom-control custom-checkbox custom-control-inline">
                                            <input type="checkbox" class="custom-control-input" id="">
                                            <label class="custom-control-label" for="">Health/medical services</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="custom-control custom-checkbox custom-control-inline">
                                            <input type="checkbox" class="custom-control-input" id="">
                                            <label class="custom-control-label" for="">Safe home/shelter</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="custom-control custom-checkbox custom-control-inline">
                                            <input type="checkbox" class="custom-control-input" id="">
                                            <label class="custom-control-label" for="">Legal assistance services (specify)</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="d-flex">
                                            <div class="custom-control custom-checkbox custom-control-inline p-0">
                                                <input type="checkbox" class="custom-control-input" id="">
                                                <label class="custom-control-label" for="">Livelihood services</label>
                                            </div>
                                            <div class="custom-control custom-checkbox custom-control-inline">
                                                <input type="checkbox" class="custom-control-input" id="">
                                                <label class="custom-control-label" for="">Others (specify)</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-12 mb-3 mt-3">
                                        <label class=""><strong>In cases where medical personnel deem injuries to be classified as sexual violence, attempt to murder and life threatening/grievous injuries, it is the duty of the medical personnel to inform it to the police.In other cases except mentioned above:</strong></label>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group form-row">
                                            <label class="col-sm-4">I want the information to be revealed to the police:</label>
                                            <div class="col-sm-8">
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" class="custom-control-input" id="">
                                                    <label class="custom-control-label" for="">Yes</label>
                                                </div>
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" class="custom-control-input" id="">
                                                    <label class="custom-control-label" for="">No</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-12 mt-3">
                                        <label class="">
                                            I have understood the purpose and the procedure of the examination including the risk and benefit, explained to me by the examining doctor. My right to refuse the examination at any stage and the consequence
                                            of such refusal, including that my medical treatment and other referral services will not be afftcted by my refusal, has also been explained and may be recorded.
                                        </label>
                                    </div>
                                    <label class="pl-3">Contents of the above have been explained to me</label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control" id="">
                                    </div>
                                    <div class="p-0">
                                         <label >language with the help of a special educator/interpreter/support person (circle as appropriate)</label >
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group form-row">
                                            <label class="col-sm-8">If special educator/interpreter/support person has helped, then his/her name and signature:</label>
                                            <div class="col-sm-4">
                                                <input type="text" id="" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group form-row">
                                            <label class="col-sm-8">Name & signature of supervisor or parent/Guardain/person in whom the child reposes trust in case of child (c12 yrs)</label>
                                            <div class="col-sm-4">
                                                <input type="text" id="" class="form-control" placeholder="With Date, time and place">
                                            </div>
                                        </div>
                                        <div class="form-group form-row">
                                            <label class="col-sm-8">Name & signature/thumb impression of Witness</label>
                                            <div class="col-sm-4">
                                                <input type="text" id="" class="form-control" placeholder="With Date, time and place">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group form-row">
                                            <label class="col-sm-4">Marks of Identification (Any scar/mole):</label>
                                            <div class="col-sm-4">
                                                <input type="text" id="" class="form-control" placeholder="">
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="text" id="" class="form-control" placeholder="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Save changes</button>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="get_content_emergency" value="{{ route('get_content_emergency') }}">
<input type="hidden" id="intraige" value="@if(isset($intraige)) $intraige @endif">
@include('emergency::modal.finish-boxLabel-modal')
@include('emergency::modal.lnr-boxLabel-modal')
@include('emergency::modal.text-boxLabel-modal')
@include('emergency::modal.scale-boxLabel-modal')
@include('emergency::modal.number-boxLabel-modal')
@include('emergency::modal.single-selection-box-modal')

@include('emergency::modal.triage')
@include('inpatient::layouts.modal.patient-image')
@endsection

@push('after-script')

<script>
    CKEDITOR.replace('editor_emergency', {
        height: '300px',
    });
    CKEDITOR.replace('history_emergency', {
        height: '300px',
    });
    CKEDITOR.replace('advice_emergency', {
        height: '300px',
    });
    CKEDITOR.replace('note_emergency', {
        height: '300px',
    });
    CKEDITOR.replace('texttobox-emergency', {
        height: '300px',
    });

    $(document).ready(function() {

        $("#dropdown_note_emergency").trigger("change");
        // Open Triage Modal On Load
        var intraige = $('#intraige').val();
        if (intraige === 1)
            triageExam.displayModal();

        setTimeout(function() {
            $(".flditem").select2();
            $(".find_fldhead").select2();
        }, 1500);


        $(document).on("keydown", ".select2-search__field", function(e) {
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if (keycode == '13') {
                //alert('You pressed a "enter" key in textbox');
                $('.flditem').append('<option value="' + $(this).val() + '" selected >' + $(this).val() + '</option>')
            }
        });
    });


    // global app configuration object
    var config = {
        routes: {
            triageColor: "{{ route('update.triage.color') }}"
        }
    };

    //complaint

    $(".unauthorised").click(function() {
        permit_user = $(this).attr('permit_user');
        showAlert('Authorization with  ' + permit_user);
    });

    $("#insert_complaints_emergency").click(function() {
        var flditem = $(".flditem option:selected").val();
        var duration = $(".duration").val();
        var duration_type = $(".duration_type option:selected").val();
        var fldreportquali = $(".fldreportquali option:selected").val();

        var fldencounterval = $("#fldencounterval").val();
        var flduserid = $("#flduserid").val();
        var fldcomp = $("#fldcomp").val();
        var url = $(this).attr("url");


        if ($.isNumeric(duration) === true) {
            var formData = {
                fldencounterval: fldencounterval,
                flduserid: flduserid,
                fldcomp: fldcomp,
                flditem: flditem,
                duration: duration,
                duration_type: duration_type,
                fldreportquali: fldreportquali
            };
            // console.log(formData);

            if (flditem == '') {
                alert('Fill all the data');
            } else {
                $.ajax({
                    url: url,
                    type: "POST",
                    dataType: "json",
                    data: formData,
                    success: function(data) {
                        if ($.isEmptyObject(data.error)) {
                            // location.reload();
                            showAlert("Information saved!!");
                            $(".get_cheif_complent_data_table").last().append(data.success.row);
                        } else {
                            showAlert("Something went wrong!!");
                        }
                    }
                });
            }
        } else {
            alert('Duration only numeric value allowed!!');
        }
    });

    $(document).on("click", "#submit_detail_complaint", function() {
        var fldid = $("#complaintfldid").val();
        var flddetail = CKEDITOR.instances.editor_emergency.getData();
        var url = $(this).attr('url');
        // var current = $(this).attr('')
        formData = {
            fldid: fldid,
            flddetail: flddetail
        }
        $.ajax({
            url: url,
            dataType: 'json',
            type: 'POST',
            data: formData,
            success: function(data) {
                if (data.success) {
                    showAlert(data.success.message);
                    $("#com_" + fldid).find('td:last-child').text(data.success.detail);
                    // $(this).children($(".clicked_edit_complaint").attr("old_complaint_detail",data.success.detail));
                    $(".get_cheif_complent_data_table").find('[clicked_flag_val="'+ fldid +'"]').attr("old_complaint_detail",data.success.detail);
                    $("#edit_complaint_emergency").modal("hide");
                } else {
                    showAlert(data.error.message);
                }
            }
        });
    });

    $(".clicked_edit_complaint").click(function() {
        current_user = $('.current_user').val();
        permit_user = $(this).attr('permit_user');
        if (current_user == permit_user) {
            var id = $(this).attr("clicked_flag_val");
            var old_complaint_detail = $(this).attr("old_complaint_detail");
            $("#complaintfldid").val(id);
            var flddetail = CKEDITOR.instances.editor_emergency.setData(old_complaint_detail);
        } else {
            showAlert('Authorization with  ' + permit_user);
            return false;
        }
    });

    $(document).on("click", ".delete_complaints", function() {
        current_user = $('.current_user').val();
        permit_user = $(this).attr('permit_user');

        // Checking for Authorize user
        if (current_user == permit_user) {
            var cur = $(this);
            var url = $(this).attr("url");
            if (confirm("Are you sure?")) {
                $.ajax({
                    url: url,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        if ($.isEmptyObject(data.error)) {
                            cur.closest("tr").remove();
                        } else {
                            alert("Something went wrong!!");
                        }
                    }
                });
            }
        } else {
            showAlert('Authorization with  ' + permit_user);
            return false;
        }
    });

    // History Tabs [History, Notes, Advice, IV Fluid]

    $(".save_history").click(function() {
        var history = CKEDITOR.instances.history_emergency.getData();
        var url = $(".note_tabs").val();
        var fldencounterval = $("#fldencounterval").val();
        var flduserid = $("#flduserid").val();
        var fldcomp = $("#fldcomp").val();
        var old_id = $(this).attr("old_id");

        var formData = {
            content: history,
            fldinput: "History",
            flduserid: flduserid,
            fldcomp: fldcomp,
            fldencounterval: fldencounterval,
            old_id: old_id
        };

        console.log(formData);
        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: formData,
            success: function(data) {
                if ($.isEmptyObject(data.error)) {
                    showAlert("Information saved!!");
                    //location.reload();
                } else {
                    showAlert("Something went wrong!!");
                }
            }
        });
    });

    $(".save_advice").click(function() {
        var advice = CKEDITOR.instances.advice_emergency.getData();
        var url = $(".note_tabs").val();
        var fldencounterval = $("#fldencounterval").val();
        var flduserid = $("#flduserid").val();
        var fldcomp = $("#fldcomp").val();
        var old_id = $(this).attr("old_id");

        var formData = {
            content: advice,
            fldinput: "Notes",
            flduserid: flduserid,
            fldcomp: fldcomp,
            fldencounterval: fldencounterval,
            old_id: old_id
        };
        console.log(formData);

        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: formData,
            success: function(data) {
                if ($.isEmptyObject(data.error)) {
                    showAlert("Information saved!!");
                    //location.reload();
                } else {
                    alert("Something went wrong!!");
                }
            }
        });
    });

    // Essential Exam

    $("#sys_bp_emergency").on("keyup", function() {
        var high = $(this).attr('high');
        var low = $(this).attr('low');

        var curr = $(this).val();
        if (curr > high)
            $(this).addClass('red-highlight');

        if (curr < low)
            $(this).addClass('green-highlight');

    });

    $("#dia_bp_emergency").on("keyup", function() {
        var high = $(this).attr('high');
        var low = $(this).attr('low');

        var curr = $(this).val();
        if (curr > high)
            $(this).addClass('red-highlight');

        if (curr < low)
            $(this).addClass('green-highlight');

    });

    $("#respi_emergency").on("keyup", function() {

        var high = $(this).attr('high');
        var low = $(this).attr('low');

        var curr = $(this).val();
        if (curr > high)
            $(this).addClass('red-highlight');

        if (curr < low)
            $(this).addClass('green-highlight');
    });

    $("#saturation_emergency").on("keyup", function() {
        var high = $(this).attr('high');
        var low = $(this).attr('low');

        var curr = $(this).val();
        if (curr > high)
            $(this).addClass('red-highlight');

        if (curr < low)
            $(this).addClass('green-highlight');

    });

    $("#pulse_rate_rate_emergency").on("keyup", function() {
        var high = $(this).attr('high');
        var low = $(this).attr('low');

        var curr = $(this).val();
        if (curr > high)
            $(this).addClass('red-highlight');

        if (curr < low)
            $(this).addClass('green-highlight');
    });

    $("#grbs").on("keyup", function() {
        var high = $(this).attr('high');
        var low = $(this).attr('low');

        var curr = $(this).val();
        if (curr > high)
            $(this).addClass('red-highlight');

        if (curr < low)
            $(this).addClass('green-highlight');
    });

    $("#save_essential_emergency").click(function() {
        var pulse_rate = $("#pulse_rate_emergency").attr("pulse_rate") + ":" + $("#pulse_rate_emergency").val();
        var sys_bp = $("#sys_bp_emergency").attr("sys_bp") + ":" + $("#sys_bp_emergency").val();
        var dia_bp = $("#dia_bp_emergency").attr("dia_bp") + ":" + $("#dia_bp_emergency").val();
        var respi = $("#respi_emergency").attr("respi") + ":" + $("#respi_emergency").val();
        var saturation = $("#saturation_emergency").attr("saturation") + ":" + $("#saturation_emergency").val();
        var pulse_rate_over = $("#pulse_rate_rate_emergency").attr("pulse_rate_rate") + ":" + $("#pulse_rate_rate_emergency").val();
        var grbs = $("#grbs").attr("grbs") + ":" + $("#grbs").val();
        var fldencounterval = $("#fldencounterval").val();
        var flduserid = $("#flduserid").val();
        var fldcomp = $("#fldcomp").val();

        var url = $(this).attr("url");
        var formData = {
            fldencounterval: fldencounterval,
            flduserid: flduserid,
            fldcomp: fldcomp,
            "essential[]": [
                pulse_rate,
                sys_bp,
                dia_bp,
                respi,
                saturation,
                pulse_rate_over,
                grbs
            ]
        };

        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: formData,
            success: function(data) {
                if ($.isEmptyObject(data.error)) {
                    showAlert("Information saved!!");

                    $('#pulse_rate_rate_emergency').val(null);
                    $('#pulse_rate_emergency').val(null);
                    $('#sys_bp_emergency').val(null);
                    $('#respi_emergency').val(null);
                    $('#saturation_emergency').val(null);
                    $('#grbs').val(null);
                    $('#dia_bp_emergency').val(null);

                    $('#sys_bp_emergency').removeClass('highline');
                    $('#sys_bp_emergency').removeClass('lowline');
                    $('#dia_bp_emergency').removeClass('highline');
                    $('#dia_bp_emergency').removeClass('lowline');
                    $('#pulse_rate_emergency').removeClass('highline');
                    $('#pulse_rate_emergency').removeClass('lowline');
                    $('#pulse_rate_rate_emergency').removeClass('highline');
                    $('#pulse_rate_rate_emergency').removeClass('lowline');
                    $('#respi_emergency').removeClass('highline');
                    $('#respi_emergency').removeClass('lowline');
                    $('#saturation_emergency').removeClass('highline');
                    $('#saturation_emergency').removeClass('lowline');
                    $('#grbs').removeClass('highline');
                    $('#grbs').removeClass('lowline')
                    $.get('emergency/essential_exam/get_essential_exam?fldencounterval=' + fldencounterval, function(data) {
                        console.log(data);
                        $.each(data, function(index, getValue) {
                            if (index == 'systolic_bp') {
                                $('#sys_bp_emergency').val(getValue.fldrepquanti);
                                if (getValue.fldrepquanti >= getValue.fldhigh) {
                                    $('#sys_bp_emergency').addClass('highline');
                                }
                                if (getValue.fldrepquanti <= getValue.fldlow) {
                                    $('#sys_bp_emergency').addClass('lowline');
                                }
                            }

                            if (index == 'diasioli_bp') {
                                $('#dia_bp_emergency').val(getValue.fldrepquanti);
                                if (getValue.fldrepquanti >= getValue.fldhigh) {
                                    $('#dia_bp_emergency').addClass('highline');
                                }
                                if (getValue.fldrepquanti <= getValue.fldlow) {
                                    $('#dia_bp_emergency').addClass('lowline');
                                }
                            }

                            if (index == 'pulse') {
                                $('#pulse_rate_emergency').val(getValue.fldrepquanti);
                                if (getValue.fldrepquanti >= getValue.fldhigh) {
                                    $('#pulse_rate_emergency').addClass('highline');
                                }
                                if (getValue.fldrepquanti <= getValue.fldlow) {
                                    $('#pulse_rate_emergency').addClass('lowline');
                                }
                            }

                            if (index == 'temperature') {
                                $('#pulse_rate_rate_emergency').val(getValue.fldrepquanti);
                                if (getValue.fldrepquanti >= getValue.fldhigh) {
                                    $('#pulse_rate_rate_emergency').addClass('highline');
                                }
                                if (getValue.fldrepquanti <= getValue.fldlow) {
                                    $('#pulse_rate_rate_emergency').addClass('lowline');
                                }
                            }

                            if (index == 'respiratory_rate') {
                                $('#respi_emergency').val(getValue.fldrepquanti);
                                if (getValue.fldrepquanti >= getValue.fldhigh) {
                                    $('#respi_emergency').addClass('highline');
                                }
                                if (getValue.fldrepquanti <= getValue.fldlow) {
                                    $('#respi_emergency').addClass('lowline');
                                }
                            }

                            if (index == 'o2_saturation') {
                                $('#saturation_emergency').val(getValue.fldrepquanti);
                                if (getValue.fldrepquanti >= getValue.fldhigh) {
                                    $('#saturation_emergency').addClass('highline');
                                }
                                if (getValue.fldrepquanti <= getValue.fldlow) {
                                    $('#saturation_emergency').addClass('lowline');
                                }
                            }

                            if (index == 'grbs') {
                                $('#grbs').val(getValue.fldrepquanti);
                                if (getValue.fldrepquanti >= getValue.fldhigh) {
                                    $('#grbs').addClass('highline');
                                }
                                if (getValue.fldrepquanti <= getValue.fldlow) {
                                    $('#grbs').addClass('lowline');
                                }
                            }
                        });
                    });
                    //location.reload();
                } else {
                    alert("Something went wrong!!");
                }
            }
        });
    });

    $(".remove_zero_to_empty").on("focusin", function() {
        var current_val = $(this).val();
        if (current_val == 0) {
            $(this).val(null);
        }
    })

    // Allergy
    // FreeText
    var allergyfreetextEmergency = {
        displayModal: function() {
            if ($('#encounter_id').val() == "") {
                alert('Please select encounter id.');
                return false;
            }
            $.ajax({
                url: "{{ route('emergency.allergy.freetext')}}",
                type: "POST",
                data: {
                    encounterId: $('#encounter_id').val()
                },
                success: function(response) {
                    // console.log(response);
                    $('.form-data-allergy-freetext').html(response);
                    $('#allergy-freetext-modal-emergency').modal('show');
                    setTimeout(function() {
                        $('#custom_allergy').focus();
                    }, 1500);
                },
                error: function(xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        },
    }

    // Allergy Modal Search Drug

    var ckbox = $("input[name='alpha']");
    var chkId = '';

    $('.alphabet').on('click', function() {
        $('input[name="alpha"]').bind('click', function() {
            $('input[name="alpha"]').not(this).prop("checked", false);
        });
        if (ckbox.is(':checked')) {

            $('#searchdrugs-emergency').val($('.alphabet').val());
            chkId = $(this).val() + ",";
            chkId = chkId.slice(0, -1);

            // alert(chkId);
            $("input[name='alpha']").attr('checked', false);
            $('#searchdrugs-emergency').val(chkId);

            var patientid = $('#patientID').val();

            if (chkId.length > 0) {
                $.get("emergency/allergy/searchDrugs", {
                    term: chkId,
                    patient_id: patientid
                }).done(function(data) {
                    // Display the returned data in browser
                    $("#allergicdrugss-emergency").html(data);
                });
            } else {
                $.get("emergency/allergy/getAllDrugs", {
                    term: chkId,
                    patient_id: patientid
                }).done(function(data) {
                    // Display the returned data in browser
                    $("#allergicdrugss-emergency").html(data);
                });
            }
        } else {
            $('#searchdrugs-emergency').val('');
            $.get("emergency/allergy/getAllDrugs", {
                term: chkId,
                patient_id: patientid
            }).done(function(data) {
                // Display the returned data in browser
                $("#allergicdrugss-emergency").html(data);
            });
        }
    });

    $('.adonclose').on('click', function() {
        $('input[name="alpha"]').prop("checked", false);
        $('#searchdrugs-emergency').val('');
        var chkId = '';
        var patientid = $('#patientID').val();
        $.get("emergency/allergy/getAllDrugs", {
            term: chkId,
            patient_id: patientid
        }).done(function(data) {
            // Display the returned data in browser
            $("#allergicdrugss-emergency").html(data);
        });
    });

    // SearchBar

    $("#searchdrugs-emergency").keyup(function() {
        var searchtext = $(this).val();
        var patientid = $('#patientID').val();
        var resultDropdown = $(this).siblings("#allergicdrugss-emergency");
        // $('#allergicdrugss').hide();
        if (searchtext.length > 0) {
            $.get("emergency/allergy/searchDrugs", {
                term: searchtext,
                patient_id: patientid
            }).done(function(data) {
                // Display the returned data in browser
                resultDropdown.html(data);
            });
        } else {
            $.get("emergency/allergy/getAllDrugs", {
                term: searchtext,
                patient_id: patientid
            }).done(function(data) {
                // Display the returned data in browser
                resultDropdown.html(data);
            });
        }
    });

    // Delete Drugs

    // $('#deletealdrug-emergency').on('click', function() {

    //     $('#select-multiple-aldrug-emergency').each(function() {
    //         // alval = [];
    //         var finalval = $(this).val().toString();

    //         var url = $('.delete_pat_findings').val();
    //         $.ajax({
    //             url: url,
    //             type: "POST",
    //             dataType: "json",
    //             data: {
    //                 ids: finalval
    //             },
    //             success: function(data) {
    //                 // console.log(data);
    //                 if ($.isEmptyObject(data.error)) {
    //                     alert('Delete Drug ?');
    //                     location.reload();
    //                 } else {
    //                     showAlert('Something went wrong!!');
    //                 }
    //             }
    //         });
    //     });
    // });

    // Diagnosis
    // FreeText
    var diagnosisfreetextEmergency = {
        displayModal: function() {
            if ($('#encounter_id').val() == "") {
                alert('Please select encounter id.');
                return false;
            }
            $.ajax({
                url: "{{ route('emergency.diagnosis.freetext')}}",
                type: "POST",
                data: {
                    encounterId: $('#encounter_id').val()
                },
                success: function(response) {
                    // console.log(response);
                    $('.form-data-diagnosis-freetext').html(response);
                    $('#diagnosis-freetext-modal-emergency').modal('show');
                    setTimeout(function() {
                        $('#custom_diagnosis').focus();
                    }, 1500);
                },
                error: function(xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                },

            });
        }
    }

    var obstetricEmergency = {
        displayModal: function() {
            if ($('#encounter_id').val() == "") {
                alert('Please select encounter id.');
                return false;
            }
            $.ajax({
                url: '{{ route("emergency.diagnosis.obstetric")}}',
                type: "POST",
                data: {
                    encounterId: $('#encounter_id').val()
                },
                success: function(response) {
                    // console.log(response);
                    $('.form-data-obstetric').html(response);
                },
                error: function(xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
            $('#diagnosis-obstetric-modal-emergency').modal('show');
        },
    }
    // Search Diagnosis
    $('#searchbygroup-emergency').on('click', function() {
        // alert('searchbygroup');
        var groupname = $('#diagnogroup-emergency').val();
        // alert(groupname);
        if (groupname.length > 0) {
            $.get("emergency/diagnosis/getDiagnosisByGroup", {
                term: groupname
            }).done(function(data) {
                // Display the returned data in browser
                $("#diagnosiscat-emergency").html(data);
            });
        }
    });

    $('#closesearchgroup-emergency').on('click', function() {
        $('#diagnogroup-emergency').val('');
        $.get("emergency/diagnosis/getInitialDiagnosisCategoryAjax", {
            term: ''
        }).done(function(data) {
            // Display the returned data in browser
            $("#diagnosiscat-emergency").html(data);
        });
    });

    var table = $('table.datatable').DataTable({
        "paging": false
    });
    var table = $('table.sdatatable').DataTable({
        "paging": false
    });

    $(document).on('click', '.dccat', function() {
        $('input[name="dccat"]').bind('click', function() {
            $('input[name="dccat"]').not(this).prop("checked", false);
        });
        var diagnocode = $("input[name='dccat']");
        $('#code').val($(this).val());
        if (diagnocode.is(':checked')) {
            diagnocode = $(this).val() + ",";
            diagnocode = diagnocode.slice(0, -1);

            $("input[name='dccat']").attr('checked', false);
            if (diagnocode.length > 0) {
                // alert(diagnocode);
                $.get("emergency/diagnosis/getDiagnosisByCode", {
                    term: diagnocode
                }).done(function(data) {
                    // Display the returned data in browser
                    $("#sublist").html(data);
                    var table = $('table.datatable-ajax').DataTable({
                        "paging": false
                    });
                });
            }
        } else {
            $("#sublist").html('');
        }
    });

    $('.onclose').on('click', function() {
        $('input[name="dccat"]').prop("checked", false);
        $('#code').val('');
        $("#diagnosissubname").val('');
        $("#sublist").val('');
    });

    $(document).on('click', '.diagnosissub', function() {
        // alert('click sub bhayo');

        $('input[name="diagnosissub"]').bind('click', function() {
            $('input[name="diagnosissub"]').not(this).prop("checked", false);
        });
        var diagnosub = $("input[name='diagnosissub']");

        if (diagnosub.is(':checked')) {
            var value = $(this).val();
            $('#diagnosissubname').val(value);
        } else {
            $("#diagnosissubname").val('');
        }
    });

    $('#deletealdiagno-emergency').on('click', function() {
        if (confirm('Delete Diagnosis??')) {
            $('#select-multiple-diagno').each(function() {
                // alval = [];
                var finalval = $(this).val().toString();
                // alert(finalval);
                var url = $('.delete_pat_findings').val();
                // alert(finalval);
                $.ajax({
                    url: url,
                    type: "POST",
                    dataType: "json",
                    data: {
                        ids: finalval
                    },
                    success: function(data) {
                        // console.log(data);
                        if ($.isEmptyObject(data.error)) {
                            showAlert('Data Deleted!!');
                            $('#select-multiple-diagno option:selected').remove();
                        } else {
                            showAlert('Something went wrong!!');
                        }
                    }
                });
            });
        }

    });


    $(document).ready(function() {
        $(".find_fldhead").on("change", function() {

            var type = $("option:selected", this).attr("typeoption");
            var item = $("option:selected", this).val();
            var fldsysconst = $("option:selected", this).attr("fldsysconst");
            var fldtype = $("option:selected", this).attr("fldtype");
            var url = $("#get_content_emergency").val();
            var encounter_id = $("#encounter_id").val();

            $.ajax({
                url: url,
                type: "POST",
                dataType: "json",
                data: {
                    type: type,
                    item: item
                },
                success: function(data) {
                    console.log(data);
                    if ($.isEmptyObject(data.error)) {
                        console.log(type);
                        if (type == "Clinical Scale") {
                            $("#scale_box").find(".modal_flditem").val(item);
                            $("#scale_box").find(".modal_fldsysconst").val(fldsysconst);
                            $("#scale_box").find(".modal_fldtype").val(fldtype);
                            $("#scale_box").find(".modal_fldencounterval").val(encounter_id);

                            $("#ajax_response_scale_list").empty();
                            $("#ajax_response_scale_list").html(data.success.options);
                            $("#scale_box").modal("show");
                        }

                        if (type == "Fixed Components") {

                            $("#text_box").find(".modal_fldencounterval").val(encounter_id);
                            $("#text_box").find(".modal_flditem").val(item);
                            $("#text_box").find(".modal_fldsysconst").val(fldsysconst);
                            $("#text_box").find(".modal_fldtype").val(fldtype);
                            $("#text_box").modal("show");
                        }

                        if (type == "Left and Right") {
                            $("#lnr_box").find(".modal_fldencounterval").val(encounter_id);
                            $("#lnr_box").find(".modal_flditem").val(item);
                            $("#lnr_box").find(".modal_fldsysconst").val(fldsysconst);
                            $("#lnr_box").find(".modal_fldtype").val(fldtype);
                            $("#lnr_box").modal("show");
                        }

                        if (type == "No Selection") {
                            $("#number_box").find(".modal_fldencounterval").val(encounter_id);
                            $("#number_box").find(".modal_flditem").val(item);
                            $("#number_box").find(".modal_fldsysconst").val(fldsysconst);
                            $("#number_box").find(".modal_fldtype").val(fldtype);
                            $("#number_box").modal("show");
                        }

                        if (type == "Single Selection") {
                            $("#scale_box").find(".modal_fldencounterval").val(encounter_id);
                            $("#scale_box").find(".modal_flditem").val(item);
                            $("#scale_box").find(".modal_fldsysconst").val(fldsysconst);
                            $("#scale_box").find(".modal_fldtype").val(fldtype);
                            $("#scale_box").modal("show");
                        }

                        if (type == "Text Addition") {
                            $("#text_box").find(".modal_fldencounterval").val(encounter_id);
                            $("#text_box").find(".modal_flditem").val(item);
                            $("#text_box").find(".modal_fldsysconst").val(fldsysconst);
                            $("#text_box").find(".modal_fldtype").val(fldtype);
                            $("#text_box").modal("show");
                        }

                        if (type == "Text Table") {
                            $("#text_box").find(".modal_fldencounterval").val(encounter_id);
                            $("#text_box").find(".modal_flditem").val(item);
                            $("#text_box").find(".modal_fldsysconst").val(fldsysconst);
                            $("#text_box").find(".modal_fldtype").val(fldtype);
                            $("#text_box").modal("show");
                        }
                    } else {
                        showAlert("Something went wrong!!");
                    }
                }
            });
        });
    });

    $(".delete_finding").click(function() {
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
                    success: function(data) {
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

    // Save Referre

    $("#save_referre_location_emergency").click(function() {
        var fldencounterval = $("#fldencounterval").val();
        var fldpatientval = $('#getPatientVal_emergency').val();
        var fldreferreform = $("#fldlocation_emergency option:selected").val();
        var url = $(this).attr("url");
        var formData = {
            fldencounterval: fldencounterval,
            fldreferreform: fldreferreform,
            fldpatientval: fldpatientval
        };

        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: formData,
            success: function(data) {
                if ($.isEmptyObject(data.error)) {
                    showAlert(data.success.message);
                    //location.reload();
                } else {
                    showAlert(data.error.message);
                }
            }
        });
    });

    // Notes Tab
    // On Select
    $('#dropdown_note_emergency').on('change', function(e) {
        var flditem = e.target.value;
        var fldencounterval = $("#fldencounterval").val();
        // ajax
        CKEDITOR.instances.note_emergency.setData('');
        // CKEDITOR.instances.note_emergency.setData();
        $.get('emergency/notes/getRelatedNote?flditem=' + flditem + '&fldencounterval=' + fldencounterval, function(data) {
            $.each(data, function(index, getRelatedNote) {
                CKEDITOR.instances.note_emergency.setData(getRelatedNote.flddetail);
                $('#note_emergency_fldid').val(getRelatedNote.fldid);
            });
        });
    });

    $(".update_note_emergency").click(function() {
        var fldid = $("#note_emergency_fldid").val();
        var fldencounterval = $("#fldencounterval").val();
        var flddetail = CKEDITOR.instances.note_emergency.getData();
        var flditem = $("#dropdown_note_emergency option:selected").val();
        var flduserid = $("#flduserid").val();
        var fldcomp = $("#fldcomp").val();
        var url = $(this).attr("url");
        var formData = {
            fldid: fldid,
            fldencounterval: fldencounterval,
            flddetail: flddetail,
            flditem: flditem,
            flduserid: flduserid,
            fldcomp: fldcomp
        };

        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: formData,
            success: function(data) {
                if ($.isEmptyObject(data.error)) {
                    showAlert(data.success.message);
                    latestModalAllNote();
                    //location.reload();
                } else {
                    showAlert(data.error.message);
                }
            }
        });
    });

    function latestModalAllNote() {
        $.get('emergency/notes/get-all-notes', function(data) {
            $('.append-all-notes').empty();
            // var html = '';
            // $.each(data, function(index, getRelatedNote) {
            //     html += '<div class="col-md-12">';
            //     html += '<h3 class="all-note-title">' + getRelatedNote.flditem + '</h3>';
            //     html += '<p class="all-note-paragraph">' + getRelatedNote.flddetail + '</p>';
            //     html += '</div>';

            $('.append-all-notes').append(data);
            // });
        });
    }

    $(document).on('click', '#js-outpatient-findings-tbody tr td:nth-child(4)', function() {
        updateExamObservation.displayModal(this, $(this).closest('tr').data('fldid'));
    });

    $(".clicked_flag").click(function() {
        var id = $(this).attr("clicked_flag_val");

        $("#findingfldidabn").val(id);
    });


    $(document).on("change", ".examanswer", function() {
        countToIdentify = $(this).attr("count");
        var item = $("option:selected", this).val();
        // alert(countToIdentify);
        $(".scaleindex-" + countToIdentify).val(item);
    });

    $(document).on('click', '#fluid_modal_save_btn', function() {
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
                success: function(data) {
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
                error: function(data) {
                    $('#drug_status_message').empty().text('Cannot Record now something went wrong.').css('color', 'red');

                },
            })
        }
    });
    /**
     * Actions on stop button
     */
    $(document).on('click', '.fluid_stop_btn', function() {


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
            success: function(data) {
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
            error: function(data) {
                $('#drug_status_message').empty().text('Cannot Record now something went wrong.').css('color', 'red');
            },
        })
    });


    $(".change_triage_color").on('click', function() {
        setTimeout(function() {
            var color = $("input[name='triage_color']:checked").val();
            var url = "{{ route('update.triage.color') }}";

            $.ajax({
                url: url,
                type: "POST",
                dataType: "json",
                data: {
                    color: color
                },
                success: function(data) {
                    if ($.isEmptyObject(data.status)) {
                        showAlert(data.message);
                        getCurrentTriageColor();
                        //getPatientProfileColor();
                        //location.reload();
                    } else {
                        showAlert('Something Went Wrong');
                    }
                }
            });
        }, 1000);
    });

    function getCurrentTriageColor() {
        $.get('emergency/triage/get-related-color', function(data) {
            $(".traicolor").css("border:4px solid #" + data);


        });
    }
</script>

@endpush
