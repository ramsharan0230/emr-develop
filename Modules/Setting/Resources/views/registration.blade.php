@extends('frontend.layouts.master')

@section('content')

    @php
        $patient_credential_setting = Options::get('patient_credential_setting');
    @endphp

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Registration Settings</h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                    <div class="row">
                                    <div class="col-lg-7 col-md-12">
                            <form action="" class="form-horizontal">
                                    {{ csrf_field() }}
                                    <div class="form-group form row align-items-center">
                                        <label for="" class="col-sm-3">Patient Credential Setting*</label>
                                        <div class="col-sm-7">
                                            <select  name="patient_credential_setting" id="patient_credential_setting" class="form-control">
                                                <option value="">--Select--</option>
                                                <option value="Email" {{ ($patient_credential_setting == "Email") ? 'selected' : ''}}>Email</option>
                                                <option value="SMS" {{ ($patient_credential_setting == "SMS") ? 'selected' : ''}}>SMS</option>
                                                <option value="Both" {{ ($patient_credential_setting == "Both") ? 'selected' : ''}}>Both</option>
                                            </select>
                                            <small class="help-block text-danger">{{$errors->first('patient_credential_setting')}}</small>
                                        </div>
                                        <div class="col-sm-2">
                                                    <a href="javascript:;" onclick="labSettings.save('patient_credential_setting')" style="font-size: 20px;"> <i class="ri-check-line"></i></a>
                                                </div>
                                    </div>
                                  

                                    <div class="form-group form row align-items-center">
                                                <label for="" class="control-label col-sm-3">Redirect to Last Encounter Id*:</label>
                                                <div class="col-sm-7">
                                                <select name="redirect_to_last_encounter" id="redirect_to_last_encounter" class="form-control">
                                                        <option value="">---select---</option>
                                                        <option value="Yes" {{ \App\Utils\Helpers::checkRedirectLastEncounter() == 'Yes'?'selected':'' }}>Yes</option>
                                                        <option value="No" {{ \App\Utils\Helpers::checkRedirectLastEncounter() == 'No'?'selected':'' }}>No</option>
                                                        {{-- <option value="Yes" {{ Options::get('redirect_to_last_encounter') == 'Yes'?'selected':'' }}>Yes</option>
                                                        <option value="No" {{ Options::get('redirect_to_last_encounter') == 'No'?'selected':'' }}>No</option> --}}
                                                    </select>
                                                </div>
                                                <div class="col-sm-2">
                                                    {{-- <a href="javascript:;" onclick="labSettings.save('redirect_to_last_encounter')"> <img src="{{asset('assets/images/tick.png')}}" class="tick" alt=""> </a> --}}
                                                    <a href="javascript:;" onclick="saveRedirectEncounter()" style="font-size: 20px;"> <i class="ri-check-line"></i></a>
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

        function saveRedirectEncounter(){
            $.ajax({
                url: '{{ route('setting.redirect-last-encounter.store') }}',
                type: "POST",
                data: {
                    redirect_to_last_encounter: $('#redirect_to_last_encounter').val()
                },
                success: function (response) {
                    showAlert(response.success.message)
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                    showAlert("An Error has occured!")
                }
            });
        }
    </script>
@endpush
