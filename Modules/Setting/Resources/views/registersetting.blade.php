@extends('frontend.layouts.master')
@push('after-styles')
    <style type="text/css">
        img.tick {
            width: 30%;
        }
    </style>
@endpush
@php
        $patient_credential_setting = Options::get('patient_credential_setting');
        $followup_check = Options::get('followup_check');
    @endphp
@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <ul class="nav nav-tabs" id="myTab-two" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#card" role="tab" aria-controls="sample" aria-selected="true">Setting</a>
                            </li>



                        </ul>
                        <div class="tab-content" id="myTabContent-1">
                            <div class="tab-pane fade show active" id="card" role="tabpanel" aria-labelledby="card">
                                <div class="row">
                                    <div class="col-lg-7 col-md-12">
                                        <form action="" class="form-horizontal">
                                            <div class="form-group form row align-items-center">
                                                <label for="" class="control-label col-sm-5">Register Card Print</label>
                                                <div class="col-sm-5">
                                                    <select name="reg_card_print" id="reg_card_print" class="form-control">
                                                        <option value="">---select---</option>
                                                        <option value="Yes" {{ Options::get('reg_card_print') == 'Yes'?'selected':'' }}>Card</option>
                                                        <option value="No" {{ Options::get('reg_card_print') == 'No'?'selected':'' }}>Sticker</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-2 p-0">
                                                    <a href="javascript:;" onclick="labSettingsreg.save('reg_card_print')" class="btn-primary btn">&nbsp;&nbsp;<i class="fa fa-check"></i>&nbsp;&nbsp;</a>
                                                </div>
                                            </div>
                                            <div class="form-group form row align-items-center">
                                                <label for="" class="control-label col-sm-5">Seprate number for opd IPD and ER:</label>
                                                <div class="col-sm-5">
                                                <select name="reg_seperate_num" id="reg_seperate_num" class="form-control">
                                                        <option value="">---select---</option>
                                                        <option value="Yes" {{ Options::get('reg_seperate_num') == 'Yes'?'selected':'' }}>Yes</option>
                                                        <option value="No" {{ Options::get('reg_seperate_num') == 'No'?'selected':'' }}>No</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-2 p-0">
                                                    <a href="javascript:;" onclick="labSettingsreg.save('reg_seperate_num')" class="btn-primary btn">&nbsp;&nbsp;<i class="fa fa-check"></i>&nbsp;&nbsp;</a>
                                                </div>
                                            </div>
                                            <div class="form-group form row align-items-center">
                                                <label for="" class="control-label col-sm-5">Watermark:</label>
                                                <div class="col-sm-5">
                                                <select name="watermark" id="watermark" class="form-control">
                                                        <option value="">---select---</option>
                                                        <option value="Yes" {{ Options::get('watermark') == 'Yes'?'selected':'' }}>Yes</option>
                                                        <option value="No" {{ Options::get('watermark') == 'No'?'selected':'' }}>No</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-2 p-0">
                                                    <a href="javascript:;" onclick="labSettingsreg.save('watermark')" class="btn-primary btn">&nbsp;&nbsp;<i class="fa fa-check"></i>&nbsp;&nbsp;</a>
                                                </div>
                                            </div>

                                            <div class="form-group form row align-items-center">
                                                <label for="" class="control-label col-sm-5">Free Text*:</label>
                                                <div class="col-sm-5">
                                                <select name="free_Text" id="free_Text" class="form-control">
                                                        <option value="">---select---</option>
                                                        <option value="Yes" {{ Options::get('free_Text') == 'Yes'?'selected':'' }}>Yes</option>
                                                        <option value="No" {{ Options::get('free_Text') == 'No'?'selected':'' }}>No</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-2 p-0">
                                                    <a href="javascript:;" onclick="labSettingsreg.save('free_Text')" class="btn-primary btn">&nbsp;&nbsp;<i class="fa fa-check"></i>&nbsp;&nbsp;</a>
                                                </div>
                                            </div>

                                            <div class="form-group form row align-items-center">
                                                <label for="" class="control-label col-sm-5">Is Army Police*:</label>
                                                <div class="col-sm-5">
                                                <select name="bookmark" id="is_army_police" class="form-control">
                                                        <option value="">---select---</option>
                                                        <option value="Yes" {{ Options::get('is_army_police') == 'Yes'?'selected':'' }}>Yes</option>
                                                        <option value="No" {{ Options::get('is_army_police') == 'No'?'selected':'' }}>No</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-2 p-0">
                                                    <a href="javascript:;" onclick="labSettingsreg.save('is_army_police')" class="btn-primary btn">&nbsp;&nbsp;<i class="fa fa-check"></i>&nbsp;&nbsp;</a>
                                                </div>
                                            </div>

                                            <div class="form-group form row align-items-center">
                                                <label for="" class="control-label col-sm-5">Issue Card*:</label>
                                                <div class="col-sm-5">
                                                <select name="bookmark" id="issue_card" class="form-control">
                                                        <option value="">---select---</option>
                                                        <option value="Yes" {{ Options::get('issue_card') == 'Yes'?'selected':'' }}>Yes</option>
                                                        <option value="No" {{ Options::get('issue_card') == 'No'?'selected':'' }}>No</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-2 p-0">
                                                    <a href="javascript:;" onclick="labSettingsreg.save('issue_card')" class="btn-primary btn">&nbsp;&nbsp;<i class="fa fa-check"></i>&nbsp;&nbsp;</a>
                                                </div>
                                            </div>

                                            <div class="form-group form row align-items-center">
                                                <label for="" class="control-label col-sm-5">Issue Ticket*:</label>
                                                <div class="col-sm-5">
                                                <select name="bookmark" id="issue_ticket" class="form-control">
                                                        <option value="">---select---</option>
                                                        <option value="Yes" {{ Options::get('issue_ticket') == 'Yes'?'selected':'' }}>Yes</option>
                                                        <option value="No" {{ Options::get('issue_ticket') == 'No'?'selected':'' }}>No</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-2 p-0">
                                                    <a href="javascript:;" onclick="labSettingsreg.save('issue_ticket')" class="btn-primary btn">&nbsp;&nbsp;<i class="fa fa-check"></i>&nbsp;&nbsp;</a>
                                                </div>
                                            </div>
                                            @if(Options::get('issue_ticket') == 'Yes')
                                            <div class="form-group form row align-items-center">
                                                <label for="" class="control-label col-sm-5">Issue Ticket Number:</label>
                                                    <div class="col-sm-5">
                                                        <select name="issue_ticket_number" id="issue_ticket_number" class="form-control" >
                                                            <option value="1" {{ Options::get('issue_ticket_number') == '1'?'selected':'' }} >1</option>
                                                            <option value="2" {{ Options::get('issue_ticket_number') == '2'?'selected':'' }}>2</option>
                                                        </select>
                                                    </div>
                                                <div class="col-sm-2 p-0">
                                                    <a href="javascript:;" onclick="labSettingsreg.save('issue_ticket_number')" class="btn-primary btn">&nbsp;&nbsp;<i class="fa fa-check"></i>&nbsp;&nbsp;</a>
                                                </div>
                                            </div>
                                            @endif
                                            <div class="form-group form row align-items-center">
                                                <label for="" class="control-label col-sm-5">Follow Up Days*:</label>
                                                <div class="col-sm-5">
                                                    <input type="text" name="followup_days" id="followup_days" class="form-control" value="{{ Options::get('followup_days')}}">

                                                </div>
                                                <div class="col-sm-2 p-0">
                                                    <a href="javascript:;" onclick="labSettingsreg.save('followup_days')" class="btn-primary btn">&nbsp;&nbsp;<i class="fa fa-check"></i>&nbsp;&nbsp;</a>
                                                </div>
                                            </div>
                                            <div class="form-group form row align-items-center">
                                                <label for="" class="control-label col-sm-5">Follow Up Patient Type:</label>
                                                <div class="col-sm-5">
                                                <select name="followup_patient_type" id="followup_patient_type" class="form-control">
                                                        <option value="">---select---</option>
                                                        <option value="all" {{ Options::get('followup_patient_type') == 'all'?'selected':'' }}>All</option>
                                                        <option value="same" {{ Options::get('followup_patient_type') == 'same'?'selected':'' }}>Same</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-2 p-0">
                                                    <a href="javascript:;" onclick="labSettingsreg.save('followup_patient_type')" class="btn-primary btn">&nbsp;&nbsp;<i class="fa fa-check"></i>&nbsp;&nbsp;</a>
                                                </div>
                                            </div>
                                            <div class="form-group form row align-items-center">
                                                <label for="" class="control-label col-sm-5">Follow Up Department Type:</label>
                                                <div class="col-sm-5">
                                                <select name="followup_department_type" id="followup_department_type" class="form-control">
                                                        <option value="">---select---</option>
                                                        <option value="all" {{ Options::get('followup_department_type') == 'all'?'selected':'' }}>All</option>
                                                        <option value="same" {{ Options::get('followup_department_type') == 'same'?'selected':'' }}>Same</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-2 p-0">
                                                    <a href="javascript:;" onclick="labSettingsreg.save('followup_department_type')" class="btn-primary btn">&nbsp;&nbsp;<i class="fa fa-check"></i>&nbsp;&nbsp;</a>
                                                </div>
                                            </div>
                                            <div class="form-group form row align-items-center">
                                                <label for="" class="control-label col-sm-5">Print Bill:</label>
                                                <div class="col-sm-5">
                                                    <select name="reg_print_bill" id="reg_print_bill" class="form-control">
                                                        <option value="">---select---</option>
                                                        <option value="Yes" {{ Options::get('reg_print_bill') == 'Yes' ? 'selected' : '' }}>Yes</option>
                                                        <option value="No" {{ Options::get('reg_print_bill') == 'No' ? 'selected' : '' }}>No</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-2 p-0">
                                                    <a href="javascript:;" onclick="labSettingsreg.save('reg_print_bill')" class="btn-primary btn">&nbsp;&nbsp;<i class="fa fa-check"></i>&nbsp;&nbsp;</a>
                                                </div>
                                            </div>

                                            <div class="form-group form row align-items-center">
                                                <label for="" class="control-label col-sm-5">Register Bill*:</label>
                                                <div class="col-sm-5">
                                                <select name="register_bill" id="register_bill" class="form-control">
                                                        <option value="">---select---</option>
                                                        <option value="Save" {{ Options::get('register_bill') == 'Save'?'selected':'' }}>Save</option>
                                                        <option value="SaveAndBill" {{ Options::get('register_bill') == 'SaveAndBill'?'selected':'' }}>Save and Bill</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-2 p-0">
                                                    <a href="javascript:;" onclick="labSettingsreg.save('register_bill')" class="btn-primary btn">&nbsp;&nbsp;<i class="fa fa-check"></i>&nbsp;&nbsp;</a>
                                                </div>
                                            </div>
                                            <div class="form-group form row align-items-center">
                                                <label for="" class="control-label col-sm-5">Patient Credential Setting*: </label>
                                                <div class="col-sm-5">
                                                <select  name="patient_credential_setting" id="patient_credential_setting" class="form-control">
                                                <option value="">--Select--</option>
                                                <option value="Email" {{ ($patient_credential_setting == "Email") ? 'selected' : ''}}>Email</option>
                                                <option value="SMS" {{ ($patient_credential_setting == "SMS") ? 'selected' : ''}}>SMS</option>
                                                <option value="Both" {{ ($patient_credential_setting == "Both") ? 'selected' : ''}}>Both</option>
                                            </select>
                                                </div>
                                                <div class="col-sm-2 p-0">
                                                    <a href="javascript:;" onclick="labSettingsreg.save('patient_credential_setting')" class="btn-primary btn">&nbsp;&nbsp;<i class="fa fa-check"></i>&nbsp;&nbsp;</a>
                                                </div>
                                            </div>
                                            <div class="form-group form row align-items-center">
                                                <label for="" class="control-label col-sm-5">Followup Check*: </label>
                                                <div class="col-sm-5">
                                                    <select  name="followup_check" id="followup_check" class="form-control select2" multiple>
                                                        <option value="">--Select--</option>
                                                        <option value="Dispensing" {{(is_array($followup_check) and in_array("Dispensing", $followup_check)) ? 'selected' : ''}}>Dispensing</option>
                                                        <option value="Cashier" {{(is_array($followup_check) and in_array("Cashier", $followup_check)) ? 'selected' : ''}}>Cashier</option>

                                                    </select>
                                                </div>
                                                <div class="col-sm-2 p-0">
                                                    <a href="javascript:;" onclick="labSettingsreg.save('followup_check')" class="btn-primary btn">&nbsp;&nbsp;<i class="fa fa-check"></i>&nbsp;&nbsp;</a>
                                                </div>
                                            </div>
                                            <div class="form-group form row align-items-center">
                                                <label for="" class="control-label col-sm-5">Shareable Item Setup*: </label>
                                                <div class="col-sm-5">
                                                    <select name="shareable_setup" id="shareable_setup" class="form-control">
                                                        <option value="">---select---</option>
                                                        <option value="grid" {{ Options::get('shareable_setup') == 'grid'?'selected':'' }}>Grid</option>
                                                        <option value="popup" {{ Options::get('shareable_setup') == 'popup'?'selected':'' }}>Pop Up</option>
                                                        <option value="both" {{ Options::get('shareable_setup') == 'both'?'selected':'' }}>Both Grid & PopUp</option>
                                                        
                                                    </select>
                                                </div>
                                                
                                                <div class="col-sm-2 p-0">
                                                    <a href="javascript:;" onclick="labSettingsreg.save('shareable_setup')" class="btn-primary btn">&nbsp;&nbsp;<i class="fa fa-check"></i>&nbsp;&nbsp;</a>
                                                </div>
                                            </div>
                                            <div class="form-group form row align-items-center">
                                                <label for="" class="control-label col-sm-5">Consultation Required*:</label>
                                                <div class="col-sm-5">
                                                <select name="consultation_required" id="consultation_required" class="form-control">
                                                        <option value="">---select---</option>
                                                        <option value="Yes" {{ Options::get('consultation_required') == 'Yes'?'selected':'' }}>Yes</option>
                                                        <option value="No" {{ Options::get('consultation_required') == 'No'?'selected':'' }}>No</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-2 p-0">
                                                    <a href="javascript:;" onclick="labSettingsreg.save('consultation_required')" class="btn-primary btn">&nbsp;&nbsp;<i class="fa fa-check"></i>&nbsp;&nbsp;</a>
                                                </div>
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


@stop
@push('after-script')
    <script>
        $(function () {
            // $("#followup_check").chosen();
        });
        var labSettingsreg = {
            save: function (settingTitle) {
                settingValue = $('#' + settingTitle).val();
                if (settingValue === "") {
                    alert('Selected field is empty.');
                    return false;
                } else {
                    $.ajax({
                        url: '{{ route('setting.lab.save') }}',
                        type: "POST",
                        data: {settingTitle: settingTitle, settingValue: settingValue},
                        success: function (response) {
                            showAlert(response.message)
                            if(settingTitle == 'issue_ticket' && settingValue == 'Yes')
                                location.reload(true);

                        },
                        error: function (xhr, status, error) {
                            var errorMessage = xhr.status + ': ' + xhr.statusText;
                            console.log(xhr);
                        }
                    });
                }
            }
        }
    </script>
@endpush
