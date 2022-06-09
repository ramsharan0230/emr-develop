@extends('frontend.layouts.master')
@push('after-styles')

@endpush

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">Device Settings</h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <ul class="nav nav-tabs justify-content-center" id="myTab-2" role="tablist">

                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#dicom" role="tab" aria-controls="profile" aria-selected="false">DICOM</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#sms-function" role="tab" aria-controls="contact" aria-selected="false">SMS Function</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#hospital-function" role="tab" aria-controls="contact" aria-selected="false">Hospital setup</a>
                        </li>

                      

                    </ul>
                    <div class="tab-content" id="myTabContent-3">

                        <div class="tab-pane fade show active" id="dicom" role="tabpanel" aria-labelledby="dicom">
                            <div class="iq-card-header d-flex justify-content-between">
                                <div class="iq-header-title">
                                    <h4 class="card-title">Remote PAC Server</h4>
                                </div>
                            </div>
                            <form method="POST" action="{{ route('setting.device.store') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group form-row align-items-center">
                                            <label for="" class="col-sm-2">Name:</label>
                                            <div class="col-sm-9">
                                                <select name="pac_server_name" id="pac_server_name" class="form-control">
                                                    <option value="0">---select---</option>
                                                    <option value="PACS1" {{ Options::get('pac_server_name') == 'PACS1' ? 'selected' :'' }}>PACS1</option>
                                                    <option value="PACS2" {{ Options::get('pac_server_name') == 'PACS2' ? 'selected' :'' }}>PACS2</option>
                                                    <option value="PACS3" {{ Options::get('pac_server_name') == 'PACS3' ? 'selected' :'' }}>PACS3</option>
                                                    <option value="PACS4" {{ Options::get('pac_server_name') == 'PACS4' ? 'selected' :'' }}>PACS4</option>
                                                    <option value="PACS5" {{ Options::get('pac_server_name') == 'PACS5' ? 'selected' :'' }}>PACS5</option>
                                                    <option value="PACS6" {{ Options::get('pac_server_name') == 'PACS6' ? 'selected' :'' }}>PACS6</option>
                                                    <option value="PACS7" {{ Options::get('pac_server_name') == 'PACS7' ? 'selected' :'' }}>PACS7</option>
                                                    <option value="PACS8" {{ Options::get('pac_server_name') == 'PACS8' ? 'selected' :'' }}>PACS8</option>
                                                    <option value="PACS9" {{ Options::get('pac_server_name') == 'PACS9' ? 'selected' :'' }}>PACS9</option>
                                                    <option value="PACS10" {{ Options::get('pac_server_name') == 'PACS10' ? 'selected' :'' }}>PACS10 </option>
                                                </select>
                                            </div>
                                            <div class="col-sm-1">
                                                <button class="btn btn-info" type="button" id="cause_detail_store" url="" onclick="showPacData()"><i class="ri-refresh-line"></i></button>
                                            </div>
                                        </div>
                                        <div class="form-group form-row align-items-center">
                                            <label for="" class="col-sm-2">Host:</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="pac_server_host" id="pac_server_host" placeholder="Host" class="form-control" value="{{ Options::get('pac_server_host') != '' ? Options::get('pac_server_host') :'' }}">
                                            </div>
                                        </div>
                                        <div class="form-group form-row align-items-center">
                                            <label for="" class="col-sm-2">AE Title:</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="pac_server_aetitle" id="pac_server_aetitle" placeholder="AE Title" class="form-control" value="{{ Options::get('pac_server_aetitle') != '' ? Options::get('pac_server_aetitle') :'' }}">
                                            </div>
                                        </div>
                                        <div class="form-group form-row align-items-center">
                                            <label for="" class="col-sm-2">C-GET:</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="pac_server_cget" id="pac_server_cget" placeholder="C-GET" class="form-control" value="{{ Options::get('pac_server_cget') != '' ? Options::get('pac_server_cget') :'' }}">
                                            </div>
                                        </div>
                                        <div class="iq-card-header d-flex justify-content-between">
                                            <div class="iq-header-title">
                                                <h4 class="card-title">DICOM Viewer</h4>
                                            </div>
                                        </div>
                                        <div class="form-group form-row align-items-center">
                                            <label for="" class="col-sm-2">Command:</label>
                                            <div class="col-sm-10">
                                                <select name="dicom_command" id="dicom_command" class="form-control">
                                                    <option value="0">---select---</option>
                                                    <option value="aeskulap" {{ Options::get('dicom_command') == 'aeskulap' ? 'selected' :'' }}>aeskulap</option>
                                                    <option value="ginkgocadx" {{ Options::get('dicom_command') == 'ginkgocadx' ? 'selected' :'' }}>ginkgocadx</option>
                                                    <option value="weasis" {{ Options::get('dicom_command') == 'weasis' ? 'selected' :'' }}>weasis</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group form-row align-items-center">
                                            <label for="" class="col-sm-2">APP Path:</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="dicom_apppath" id="dicom_apppath" placeholder="APP Path" class="form-control" value="{{ Options::get('dicom_apppath') != '' ? Options::get('dicom_apppath') :'' }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group form-row align-items-center">
                                            <label for="" class="col-sm-2">Modality:</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="pac_server_modality" id="pac_server_modality" palceholder="Modality" class="form-control" value="{{ Options::get('pac_server_modality') != '' ? Options::get('pac_server_modality') :'' }}">
                                            </div>
                                        </div>
                                        <div class="form-group form-row align-items-center">
                                            <label for="" class="col-sm-2">Query:</label>
                                            <div class="col-sm-10">
                                                <select name="pac_server_query" id="pac_server_query" class="form-control">
                                                    <option value="0">---select---</option>
                                                    <option value="patient" {{ Options::get('pac_server_query') == 'patient' ? 'selected' :'' }}>Patient</option>
                                                    <option value="study" {{ Options::get('pac_server_query') == 'study' ? 'selected' :'' }}>Study</option>
                                                    <option value="worklist" {{ Options::get('pac_server_query') == 'worklist' ? 'selected' :'' }}>Worklist</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group form-row align-items-center">
                                            <label for="" class="col-sm-2">Port:</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="pac_server_port" id="pac_server_port" palceholder="Port" class="form-control" value="{{ Options::get('pac_server_port') != '' ? Options::get('pac_server_port') :'' }}">
                                            </div>
                                        </div>
                                        <div class="form-group form-row align-items-center">
                                            <label for="" class="col-sm-2"></label>
                                            <div class="col-sm-10">
                                                <button class="btn btn-action btn-primary">Update</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade " id="barcode-print" role="tabpanel" aria-labelledby="barcode-print">
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                        </div>
                        <div class="tab-pane fade" id="miscellaneous" role="tabpanel" aria-labelledby="miscellaneous">
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                        </div>
                        <div class="tab-pane fade" id="sms-function" role="tabpanel" aria-labelledby="sms-function">
                            <div class="iq-card-header d-flex justify-content-between">
                                <div class="iq-header-title">
                                    <h4 class="card-title">SMS Setting</h4>
                                </div>
                            </div>
                            <form method="POST" action="{{ route('setting.sms.store') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-8 col-md-12">
                                        <div class="form-group form-row align-items-center">
                                            <label for="" class="col-sm-3">URL:</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="url" value="{{ Options::get('url') }}" id="url" placeholder="URL" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group form-row align-items-center">
                                            <label for="" class="col-sm-3">Token:</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="token" value="{{ Options::get('token') }}" id="token" placeholder="Token" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group form-row align-items-center">
                                            <label for="" class="col-sm-3">Username:</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="username" value="{{ Options::get('username') }}" id="username" placeholder="Username" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group form-row align-items-center">
                                            <label for="" class="col-sm-3">Password:</label>
                                            <div class="col-sm-9">
                                                <input type="password" name="password" value="{{ Options::get('password') }}" id="password" placeholder="Password" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group form-row align-items-center">
                                            <label for="" class="col-sm-3">Registration SMS text:</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="text_messgae" value="{{ Options::get('text_messgae') }}" id="text_messgae" placeholder="Registration SMS text" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group form-row align-items-center">
                                            <label for="" class="col-sm-3">Bulk SMS:</label>
                                            <div class="col-sm-9">
                                                <textarea name="bulk_sms" class="form-control">{{ Options::get('bulk_sms') }}</textarea>
                                            </div>
                                        </div>
                                        <div class="form-group form-row align-items-center">
                                            <label for="" class="col-sm-3">Lab report SMS text:</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="lab_report_text_message" value="{{ Options::get('lab_report_text_message') }}" id="lab_report_text_message" placeholder="Lab report SMS text" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group form-row align-items-center">
                                            <label for="" class="col-sm-3">Radio report SMS text:</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="radio_report_text_message" value="{{ Options::get('radio_report_text_message') }}" id="radio_report_text_message" placeholder="Radio report SMS text" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group form-row align-items-center">
                                            <label for="" class="col-sm-3">OPD report SMS text:</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="opd_report_text_message" value="{{ Options::get('opd_report_text_message') }}" id="opd_report_text_message" placeholder="OPD report SMS text" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group form-row align-items-center">
                                            <label for="" class="col-sm-3">Discharge SMS text:</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="discharge_text_message" value="{{ Options::get('discharge_text_message') }}" id="discharge_text_message" placeholder="Discharge SMS text" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group form-row align-items-center">
                                            <label for="" class="col-sm-3">Low Deposit SMS text:</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="low_deposit_text_message" value="{{ Options::get('low_deposit_text_message') }}" id="low_deposit_text_message" placeholder="Low Deposite SMS text" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-12">
                                        <h4>List of variables</h4>
                                        <ul>
                                            <li>{$name}</li>
                                            <li>{$username}</li>
                                            <li>{$password}</li>
                                            <li>{$systemname}</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="form-group form-row align-items-center">
                                    <label for="" class="col-sm-2"></label>
                                    <div class="col-sm-10">
                                         <button class="btn btn-primary btn-action">Update</button>
                                     </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="hospital-function" role="tabpanel" aria-labelledby="hospital-function">
                            <div class="col-sm-6">
                                <div class="form-group form-row align-items-center">
                                    <label for="" class="col-sm-5 col-lg-3">Setup HMIS URL:</label>
                                    <div class="col-sm-5 col-lg-7">
                                        <input type="text" name="hmis_url" id="hmis_url" value="{{ Options::get('hmis_url') }}" palceholder="Hmis url" class="form-control">
                                    </div>
                                    <div class="col-sm-2">
                                        <a href="javascript:;" class="btn btn-primary" onclick="labSettings.save('hmis_url')"><i class="fa fa-check"></i> </a>
                                    </div>
                                </div>

                                <div class="form-group form-row align-items-center">
                                    <label for="" class="col-sm-5 col-lg-3">Setup IRD API:</label>
                                    <div class="col-sm-5 col-lg-7">
                                        <input type="text" name="IRD" id="IRD" value="{{ Options::get('IRD') }}" palceholder="IRD" class="form-control">
                                    </div>
                                    <div class="col-sm-2">
                                        <a href="javascript:;" class="btn btn-primary" onclick="labSettings.save('IRD')"><i class="fa fa-check"></i> </a>
                                    </div>
                                </div>

                                <div class="form-group form-row align-items-center">
                                    <label for="" class="col-sm-5 col-lg-3">Setup Health Insurance API:</label>
                                    <div class="col-sm-5 col-lg-7">
                                        <input type="text" name="health_insurance_url" value="{{ Options::get('health_insurance_url') }}" id="health_insurance_url" palceholder="Health Insurance URL" class="form-control">
                                    </div>
                                    <div class="col-sm-2">
                                        <a href="javascript:;" class="btn btn-primary" onclick="labSettings.save('health_insurance_url')"><i class="fa fa-check"></i> </a>
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
<script type="text/javascript">
    function showPacData() {
        var pacval = $('#pac_server_name').val();
        if (pacval != '') {
            $.ajax({
                url: '{{ route('pacs.detail') }}',
                type: "POST",
                data: {
                    pacsName: pacval
                },
                success: function(response) {
                    console.log(response);
                    $('#pac_server_host').val(response.pac_server_host);
                    $('#pac_server_aetitle').val(response.pac_server_aetitle);
                    $('#pac_server_cget').val(response.pac_server_cget);
                    $('#pac_server_modality').val(response.pac_server_modality);
                    $('#pac_server_query').val(response.pac_server_query);
                    $('#pac_server_port').val(response.pac_server_port);
                    $('#dicom_command').val(response.dicom_command);
                    $('#dicom_apppath').val(response.dicom_apppath);
                },
                error: function(xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        } else {
            alert('Select Pacs Server Name');
        }
    }
</script>

@stop

@push('after-script')
<script>

    var labSettings = {
        save: function (settingTitle) {
            settingValue = $('#' + settingTitle).val();
            if (settingValue === "") {
                alert('Selected field is empty.')
            }

            $.ajax({
                url: '{{ route('setting.lab.save') }}',
                type: "POST",
                data: {settingTitle: settingTitle, settingValue: settingValue},
                success: function (response) {
                    showAlert(response.message)
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }
    }
</script>
@endpush

