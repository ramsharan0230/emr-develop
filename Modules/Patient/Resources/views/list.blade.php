@extends('frontend.layouts.master')
@section('content')
<style>
    .nav-tabs .nav-link.active {
        background-color: unset;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">

            @if(Session::get('success_message'))
                <div class="alert alert-success containerAlert">
                    <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                    {{ Session::get('success_message') }}
                </div>
            @endif

            @if(Session::get('error_message'))
                <div class="alert alert-success containerAlert">
                    <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                    {{ Session::get('error_message') }}
                </div>
            @endif
            <div class="iq-card iq-card-block iq-card-stretch">
                <div class="iq-card-header d-flex justify-content-between align-items-center">
                    <div class="iq-header-title">
                        <h4 class="card-title">Follow-Up List</h4>
                    </div>
                    <!-- <a href="{{ route('patient.patientListCsv', Request::query()) }}" target="_blank" class="btn btn-primary">Excel</a>
                    <a href="{{ route('patient.patientListPdf', Request::query()) }}" target="_blank" class="btn btn-primary">Pdf</a> -->
                    <button class="btn btn-primary" onclick="myFunction()"><i class="fa fa-bars"></i></button>
                </div>
            </div>
            <div class="iq-card iq-card-block iq-card-stretch" id="myDIV">
                <div class="iq-card-header d-flex justify-content-between align-items-center">
                    <div class="iq-header-title">
                        <h4 class="card-title">Filter</h4>
                    </div>
                    <div>
                        {{-- <button class="btn btn-outline-primary"><i class="fa fa-sync"></i>&nbsp;Reset</button> --}}
                        <a type="button" class="btn btn-outline-primary" href="{{ route('patient.reset') }}"
                                id="reset_sms"><i class="fa fa-sync"></i>&nbsp;Reset</a>
                    </div>
                </div>
                {{-- <input type="text" class="form-control" id="js-patient-global-search" placeholder="Search" style="width:35%;"> --}}
                {{-- <button class="btn btn-primary" id="js-toggle-filter">Hide Filter</button> --}}
                <div class="iq-card-body">
                    <div class="">
                        <form>
                            <div class="d-flex flex-wrap">
                                <div class="col-sm-6 col-lg-2">
                                    <div class="form-group">
                                        <label>From Date</label>
                                        <input type="text" name="from_date" id="from_date" value="{{ request('from_date') }}" placeholder="YYYY-MM-DD" class="form-control nepaliDatePicker" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-2">
                                    <div class="form-group">
                                        <label>To Date</label>
                                        <input type="text" name="to_date" id="to_date" value="{{ request('to_date') }}" placeholder="YYYY-MM-DD" class="form-control nepaliDatePicker" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-3">
                                    <div class="form-group">
                                        <label>Consultant</label>
                                        <select name="consultant" id="consultant" class="form-control">
                                            <option value="">--Select--</option>
                                            @if (count($consultantList))
                                                @foreach ($consultantList as $con)
                                                    @if ($con->nmc)
                                                        <option data-nmc="{{ $con->nmc }}"
                                                            value="{{ $con->username }}" {{ (request('consultant') == $con->username) ? "selected='selected'" : "" }}>
                                                            {{ $con->fldtitlefullname }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-3">
                                    <div class="form-group">
                                        <label>Specialization</label>
                                        <select class="form-control" name="department">
                                            <option value="">--Select--</option>
                                            @foreach($departments as $department)
                                            <option value="{{ $department->flddept }}" {{ (request('department') == $department->flddept) ? "selected='selected'" : "" }}>{{ $department->flddept }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </div>
                            <div class="d-flex justify-content-end mt-3 mr-2">
                                <button class="btn btn-primary btn-action"><i class="fa fa-filter"></i>&nbsp; Filter</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <ul class="nav nav-tabs justify-content-start" id="myTab-two" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#waiting" role="tab" aria-controls="sample" aria-selected="true">Waiting List</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#checkedin" role="tab" aria-controls="sampling_checkbox" aria-selected="true">Checked In</a>
                </li>
            </ul>
            <div class="tab-content" id="myTableContent-1">
                <div class="tab-pane fade show active" id="waiting" role="tabpanel" aria-labelledby="sample">
                    <div class="iq-card iq-card-block iq-card-stretch">
                        <div class="iq-card-header d-flex justify-content-between align-items-center">
                            <div class="iq-header-title">
                                <h4 class="card-title">Waiting Patient List {{$patients_counts}}</h4>
                            </div>
                            <div>
                                <a href="{{ route('patient.patientListCsv', Request::query()) }}" target="_blank" class="btn btn-primary mr-1"><i class="fa fa-file-excel"></i>&nbsp;Excel</a>
                                <a href="{{ route('patient.patientListPdf', Request::query()) }}" target="_blank" class="btn btn-primary"><i class="fa fa-file-pdf"></i>&nbsp;Pdf</a>
                            </div>
                        </div>
                        <div class="iq-card-body pt-0">
                            <div class="">
                                <!-- <p style="margin-bottom: -6em;font-weight: bold;">Total Waiting/Pending: {{$patients_counts}}</p> -->
                                <table id="myTable1" data-show-columns="true"
                                    data-search="true"
                                    data-show-toggle="true"
                                    data-pagination="true"
                                    data-resizable="true"
                                >
                                    <thead>
                                        <tr>
                                            <th>S.N.</th>
                                            <th>Patient ID/Enc ID</th>
                                            <th>Patient Detail</th>
                                            <th>Specialization</th>
                                            <th>Consultant</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @if($patients)

                                        @foreach($patients as $key=>$patient)
                                            @php
                                            $getConsultant = Helpers::getConsultant($patient->fldencounterval);
                                            @endphp
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td> {{ ($patient->patientInfo) ? $patient->patientInfo->fldpatientval : '' }}/{{ $patient->fldencounterval }}</td>
                                            <td>
                                                {{ ($patient->patientInfo) ? $patient->patientInfo->fldfullname : '' }}<br>
                                                {{ (isset($patient->patientInfo->fldagestyle) and is_string($patient->patientInfo->fldagestyle)) ? $patient->patientInfo->fldagestyle : '' }}/{{ ($patient->patientInfo) ? $patient->patientInfo->fldptsex : '' }} {{ ($patient->patientInfo) ? $patient->patientInfo->fldptcontact : '' }}<br>
                                                {{ ($patient->patientInfo) ? implode(', ', (array_filter([$patient->patientInfo->fldptaddvill, $patient->patientInfo->fldptadddist]))) : '' }}
                                            </td>
                                            {{-- <td>{{ (isset($patient->fldfollowdate) ? 'Yes':'No') }}</td> --}}
                                            {{-- <td>{{ (isset($patient->fldfollowdate)) ? \Carbon\Carbon::parse($patient->fldfollowdate)->format('Y-m-d'):'' }}</td> --}}
                                            <td>{{ ($patient->allConsultant) ? implode(', ', array_filter($patient->allConsultant->pluck('fldconsultname')->toArray())) : '' }}</td>
                                            <td>{{ ($getConsultant) ? $getConsultant : '' }}</td>
                                            <td>
                                                <button class="btn btn-primary js-registration-list-edit-consultant" data-encounter="{{$patient->fldencounterval}}" data-followup={{Options::get('followup_department_type')}}>Check In</button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @endif
                                        {{-- <tr>
                                            <td>1</td>
                                            <td>2</td>
                                            <td>3</td>
                                            <td>4</td>
                                            <td>5</td>
                                            <td>
                                                <button class="btn btn-primary" data-toggle="modal" data-target="#checkinmodal">Check In</button>
                                            </td>
                                        </tr> --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="checkedin" role="tabpanel" aria-labelledby="sampling_checkbox">
                    <div class="iq-card iq-card-block iq-card-stretch">
                        <div class="iq-card-header d-flex justify-content-between align-items-center">
                            <div class="iq-header-title">
                                <h4 class="card-title">Checked In Patient List {{$patients_checked_counts}}</h4>
                            </div>
                            <div>
                                <a href="{{ route('patient.patientFollowListCsv', Request::query()) }}" target="_blank" class="btn btn-primary mr-2"><i class="fa fa-file-excel"></i>&nbsp;Excel</a>
                                <a href="{{ route('patient.patientFollowListPdf', Request::query()) }}" target="_blank" class="btn btn-primary"><i class="fa fa-file-pdf"></i>&nbsp;Pdf</a>
                            </div>
                        </div>
                        <div class="iq-card-body pt-0">
                            <div class="">
                                <!-- <p style="margin-bottom: -6em;font-weight: bold;">Total Checked in: {{$patients_counts}}</p> -->
                                <table id="myTable2" data-show-columns="true"
                                            data-search="true"
                                            data-show-toggle="true"
                                            data-pagination="true"
                                            data-resizable="true">
                                    <thead>
                                        <tr>
                                            <th>S.N.</th>
                                            <th>Patient ID/Enc ID</th>
                                            <th>Patient Detail</th>
                                            <th>Specialization</th>
                                            <th>Consultant</th>
                                            <th>Follow Date</th>
                                            <th>Follow Up Deparment</th>
                                            <th>Follow Up Consultant</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @if($patients_checked)

                                        @foreach($patients_checked as $key=>$patient_checked)
                                            @php
                                            $consultant = Helpers::consultant($patient_checked->fldencounterval);
                                            $getLastestFollowup = Helpers::getLastestFollowup($patient_checked->fldencounterval);
                                            $getConsultantFollowup = Helpers::getConsultantFollowup($patient_checked->fldencounterval);
                                            // dd($getLastestFollowup);
                                            @endphp
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td> {{ ($patient_checked->patientInfo) ? $patient_checked->patientInfo->fldpatientval : '' }}/{{ $patient_checked->fldencounterval }}</td>
                                            <td>
                                                {{ ($patient_checked->patientInfo) ? $patient_checked->patientInfo->fldfullname : '' }}<br>
                                                {{ (isset($patient_checked->patientInfo->fldagestyle) and is_string($patient_checked->patientInfo->fldagestyle)) ? $patient_checked->patientInfo->fldagestyle : '' }}/{{ ($patient_checked->patientInfo) ? $patient_checked->patientInfo->fldptsex : '' }} {{ ($patient_checked->patientInfo) ? $patient_checked->patientInfo->fldptcontact : '' }}<br>
                                                {{ ($patient_checked->patientInfo) ? implode(', ', (array_filter([$patient_checked->patientInfo->fldptaddvill, $patient_checked->patientInfo->fldptadddist]))) : '' }}
                                            </td>
                                            <td>{{ ($patient_checked->allConsultant) ? implode(', ', array_filter($patient_checked->allConsultant->pluck('fldconsultname')->toArray())) : '' }}</td>
                                            <td>{{ ($consultant) ? $consultant : '' }}</td>
                                            <td>{{ (isset($patient_checked->fldfollowdate)) ? \Carbon\Carbon::parse($patient_checked->fldfollowdate)->format('Y-m-d'):'' }}</td>
                                            {{-- <td>{{ (isset($patient->fldfollowdate) ? 'Yes':'No') }}</td> --}}
                                            <td>{{ ($getLastestFollowup['fldconsultname']) ? $getLastestFollowup['fldconsultname'] : ''}}</td>
                                            <td>{{ ($getConsultantFollowup) ? $getConsultantFollowup : ''  }}</td>
                                            <td>
                                                <button class="btn" style="background-color: green; color: #ffffff;">Checked</button>
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
</div>

<!-- CheckIn Modal  -->
<div data-backdrop="static" class="modal fade" id="consultant-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" id="consult-form">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="changeDeptModalLabel">Do you want to continue in same department?</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                      <button id="assign-bed-submit-btn" type="button" class="btn btn-primary edit-consultant" data-dismiss="modal">No</button>
                      <button type="button"  class="btn btn-success update-followup" data-encounter="{{$patient->fldencounterval ?? ''}}">Yes</button>
                      {{-- url="{{ route('update.department.bed') }}" --}}
                    </div>
              </div>
        </form>
    </div>
  </div>

    <!-- Edit Consultant Modal -->
<!-- Modal -->
<div class="modal fade" id="edit-consult-modal" tabindex="-1" role="dialog" aria-labelledby="edit-consult-modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Edit Consultant</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="edit-consultant-form" action="{{ route('update-consultant-list-followup') }}">
                <input type="hidden" name="edit-consult-patient" id="edit-consult-patient" value="">
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-12 js-multi-consultation-tbody-edit-consult">
{{--                            <table class="table border mt-2">--}}
{{--                                <thead>--}}
{{--                                <tr>--}}
{{--                                    <td colspan="2">Consultation</td>--}}
{{--                                    <td>--}}
{{--                                        <button type="button" class="btn btn-primary btn-sm-in js-multi-consultation-add-btn-edit">--}}
{{--                                            ADD--}}
{{--                                        </button>--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                                </thead>--}}
{{--                                <tbody class="js-multi-consultation-tbody-edit-consult">--}}
{{--                                <tr>--}}
{{--                                    <td>Specialization<span class="text-danger">*</span>:--}}
{{--                                        <select name="department[]"--}}
{{--                                                class="form-control select2 js-registration-department"--}}
{{--                                                required>--}}
{{--                                            <option value="">--Select--</option>--}}
{{--                                            @foreach($departments as $department)--}}
{{--                                                <option--}}
{{--                                                    value="{{ $department->flddept }}">{{ $department->flddept }}</option>--}}
{{--                                            @endforeach--}}
{{--                                        </select>--}}
{{--                                    </td>--}}
{{--                                    <td>Consultant Name<span--}}
{{--                                            class="text-danger consultant-span">*</span>:--}}
                                        <input type="hidden" name="consultantid[]"
                                               class="js-registration-consultantid">
{{--                                        <select name="consultant[]"--}}
{{--                                                class="form-control js-registration-consultant select2"--}}
{{--                                                required>--}}
{{--                                            <option value="">--Select--</option>--}}
{{--                                        </select>--}}
{{--                                    </td>--}}
{{--                                    <td>--}}
{{--                                        <button type="button"--}}
{{--                                                class="btn btn-danger btn-sm-in mt-4 js-multi-consultation-edit-remove-btn">--}}
{{--                                            <i class="fa fa-times"></i></button>--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                                </tbody>--}}
{{--                            </table>--}}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>

            </form>
        </div>
    </div>
</div>
    <!--End Edit Consultant Modal -->
@endsection



@push('after-script')
    <script src="{{asset('js/registration_form.js')}}"></script>
    <script type="text/javascript">
        $(function() {
            $('#myTable1').bootstrapTable()
        });
        $(function() {
            $('#myTable2').bootstrapTable()
        });
    </script>
    <script>
        var encounterId;
        function myFunction() {
            var x = document.getElementById("myDIV");
            if (x.style.display === "none") {
                x.style.display = "block";
            } else {
                x.style.display = "none";
            }
        }
        $(document).ready(function () {
            $('.dropdown-toggle').dropdown();
        });
    function getConsultants(encounterid) {
    // alert($('#edit-consult-patient').val());
    $.ajax({
        url: baseUrl + '/registrationform/get-edit-consultation/',
        type: "GET",
        data: {encounterId: encounterid},
        success: function (data) {
            $('.js-multi-consultation-tbody-edit-consult').empty().append(data.select);
            $(document).find('.js-registration-department').select2();
            $(document).find('.js-registration-consultant').select2();

            if(data.error){
                showAlert(data.error,'error');
            }
        },
        error: function (xhr, status, error) {
            var errorMessage = xhr.status + ': ' + xhr.statusText;
            console.log(xhr);
        }
    });
    }

    $(document).on('click', '.js-registration-list-edit-consultant', function() {
        encounterId = $(this).data('encounter');
        var followup = $(this).data('followup');
        
        if(followup == 'all'){  
        $('#consultant-modal').modal('show');
        $('#edit-consult-patient').val(encounterId);
        } else{
        updateFollowup(encounterId);
        location.reload();
        }
    });
    $(document).on('click', '.edit-consultant', function () {
        getConsultants(encounterId);
        $('#edit-consult-modal').modal('show');
    });
    
    $(document).on('click', '.update-followup', function () {
        var encounterId = $(this).data('encounter');
        updateFollowup(encounterId);
        $('#consultant-modal').modal('hide');
        location.reload();
    });

    function updateFollowup(encounterId) {

        $.ajax({
            url: baseUrl + '/patient-list/updatePatient',
            type: "POST",
            data: {encounterId: encounterId},
            success: function (response) {

                if(response.status == true){
                    showAlert(response.message,'success');
                } else {
                    showAlert(response.message,'error');
                }
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    }

    // $('.js-registration-list-edit-consultant').click(function () {
    //     console.log('herer');
    //     // $('#edit-consult-patient').val($(this).closest('tr').data('encounter'));
    //     // getConsultants()
    //     $('#edit-consult-modal').modal('show');
    // });

    $('.js-multi-consultation-add-btn-edit').click(function () {
        var trTemplateData = $('#js-multi-consultation-tr-template-edit').html();
        console.log(trTemplateData);
        //
        $('.js-multi-consultation-tbody-edit-consult').append(trTemplateData);
        $.each($(document).find('.js-multi-consultation-tbody-edit-consult tr select'), function (i, elem) {
            if (!$(elem).hasClass('select2-hidden-accessible'))
                $(elem).select2();
        });
    });

    $(document).on('click', '.js-multi-consultation-edit-remove-btn', function () {
        var trCount = $(this).closest('.js-multi-consultation-tbody-edit-consult').find('tr').length;
        if (trCount > 1) {
            $(this).closest('tr').remove();
        }

    });
    </script>
@endpush
