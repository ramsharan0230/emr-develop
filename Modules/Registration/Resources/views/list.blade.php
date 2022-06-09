@extends('frontend.layouts.master')
@section('content')

    <template id="js-multi-consultation-tr-template-edit">
        <tr>
            <td>Specialization<span class="text-danger">*</span>:
                <select name="department[]" class="form-control select2 js-registration-department" required>
                    <option value="">--Select--</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->flddept }}">{{ $department->flddept }}</option>
                    @endforeach
                </select>
            </td>
            <td>Consultant Name<span class="text-danger consultant-span">*</span>:
                <input type="hidden" name="consultantid[]" class="js-registration-consultantid">
                <select name="consultant[]" class="form-control js-registration-consultant select2" required>
                    <option value="">--Select--</option>
                </select>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm-in mt-4 js-multi-consultation-edit-remove-btn"><i
                        class="fa fa-times"></i></button>
            </td>
        </tr>
    </template>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">
                            Patient Registration List
                        </h4>
                    </div>
                    <button class="btn btn-primary" id="js-toggle-filter"><i class="fa fa-bars"></i></button>
                </div>
            </div>
        </div>
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

        <div class="col-md-12" id="js-registration-list-filter">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <div class="registration-list-filter">
                        <form>
                            <div class="col-md-12 form-row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" name="name" value="{{ request('name') }}" placeholder="Name" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>From Date</label>
                                        <input type="text" name="from_date" id="from_date" value="{{ request('from_date') }}" placeholder="YYYY-MM-DD" class="form-control nepaliDatePicker" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>To Date</label>
                                        <input type="text" name="to_date" id="to_date" value="{{ request('to_date') }}" placeholder="YYYY-MM-DD" class="form-control nepaliDatePicker" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Department</label>
                                        <select class="form-control" name="department">
                                            <option value="">--Select--</option>
                                            @foreach($departments as $department)
                                            <option value="{{ $department->flddept }}" {{ (request('department') == $department->flddept) ? "selected='selected'" : "" }}>{{ $department->flddept }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 d-flex justify-content-end mt-3">
                                <button class="btn btn-primary btn-action"><i class="fa fa-filter"></i>&nbsp;Filter</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between align-items-center">
                    
                    <input type="text" class="form-control" id="js-patient-global-search" placeholder="Search" style="width:35%;">
                    <!-- <button class="btn btn-primary" id="js-toggle-filter">Hide Filter</button> -->
                    <div>
                        <a href="{{ route('registrationform.registrationCsv', Request::query()) }}" target="_blank" class="btn btn-primary"><i class="fa fa-file-pdf"></i>&nbsp;Excel</a>
                        <a href="{{ route('registrationform.registrationPdf', Request::query()) }}" target="_blank" class="btn btn-primary"><i class="fa fa-file-excel"></i>&nbsp;Pdf</a>
                    </div>
                </div>
                <div class="iq-card-body">
                    <table class="table table-bordered table-hover table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th>S.N.</th>
                                <th>Patient ID/Enc ID</th>
                                <th>Patient Detail</th>
                                <th>Specialization</th>
                                <th>Consultant</th>
                                <th>UserId</th>
                                <!-- <th>Login Credential</th> -->
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="js-registration-list">
                        @if($patients)

                            @foreach($patients as $key=>$patient)
                                @php
                                $getConsultant = Helpers::getConsultant($patient->fldencounterval);
                                $getNameByUsername = Helpers::getNameByUsername($patient->created_by);
                                @endphp
                                @php
                                $diffInHours = \Carbon\Carbon::parse($patient->fldregdate)->diffInHours(\Carbon\Carbon::now());
                                @endphp
                            <tr  data-encounter="{{ ($patient) ? $patient->fldencounterval : '' }}"  data-fldpatientval="{{ ($patient->patientInfo) ? $patient->patientInfo->fldpatientval : '' }}">
                                <td>{{ $loop->iteration }}</td>
                                <td> {{ ($patient->patientInfo) ? $patient->patientInfo->fldpatientval : '' }}/{{ $patient->fldencounterval }}</td>
                                <td>
                                    {{ ($patient->patientInfo) ? $patient->patientInfo->fldfullname : '' }}<br>
                                    {{ (isset($patient->patientInfo->fldagestyle) and is_string($patient->patientInfo->fldagestyle)) ? $patient->patientInfo->fldagestyle : '' }}/{{ ($patient->patientInfo) ? $patient->patientInfo->fldptsex : '' }} {{ ($patient->patientInfo) ? $patient->patientInfo->fldptcontact : '' }}<br>
                                    {{ ($patient->patientInfo) ? implode(', ', (array_filter([$patient->patientInfo->fldptaddvill, $patient->patientInfo->fldptadddist]))) : '' }}
                                </td>
                                <td>{{ ($patient->allConsultant) ? implode(', ', array_filter($patient->allConsultant->pluck('fldconsultname')->toArray())) : '' }}</td>
                                <td>{{ ($getConsultant) ? $getConsultant : '' }}</td>
                                <td>{{ ($getNameByUsername) ? $getNameByUsername : '' }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-primary dropdown-toggle dropdown-toggle" type="button" id="dropdownMenuButton_{{$key}}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Action
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton_{{$key}}">
                                            <a class="dropdown-item js-registration-list-view" target="_blank"><i class="ri-eye-fill"></i>&nbsp;View</a>
                                            <a class="dropdown-item js-registration-list-edit" target="_blank"><i class="ri-edit-box-fill"></i>&nbsp;Edit</a>
                                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'edit-consultant-form-reg' ) )
                                            @if($diffInHours < 12)
                                            <a class="dropdown-item js-registration-list-edit-consultant"><i class="ri-edit-box-fill"></i>&nbsp;Edit Consultant/Department</a>
                                            @endif
                                            @endif
                                            @if(Options::get('issue_card') == 'Yes')
                                            <a href="{{route('registrationform.idcard', [ 'patientId' => $patient->fldpatientval]) }}" class="dropdown-item" target="_blank"><i class="ri-file-text-fill"></i>&nbsp;Print Card</a>
                                            @endif
                                            @if(Options::get('issue_ticket') == 'Yes')
                                            <a href="{{route('print.ticket', [
                                                ($patient->patientInfo) ? $patient->patientInfo->fldpatientval : '',
                                                'fldencounterval' => $patient->fldencounterval,
                                            ]) }}" class="dropdown-item"  target="_blank" ><i class="ri-ticket-2-line"></i>&nbsp; Print Ticket</a>
                                            @endif
                                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'cashier-form' ) )
                                            <a href="{{ route('billing.display.form', ['encounter_id' => $patient->fldencounterval]) }}" class="dropdown-item" target="_blank"><i class="fa fa-registered"></i>&nbsp;Cashier Form</a>
                                            @endif
                                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'dispensing-form' ) )
                                            <a href="{{ route('dispensingForm', ['encounter_id' => $patient->fldencounterval]) }}" class="dropdown-item" target="_blank"><i class="ri-hard-drive-2-line"></i>&nbsp;Dispensing Form</a>
                                            @endif
                                            @if ( \App\Utils\Permission::checkPermissionFrontendAdmin( 'view-cash-billing' ) )
                                            <a href="{{ route('cashier.form.display.invoice', ['encounter_id' => $patient->fldencounterval]) }}" class="dropdown-item" target="_blank" title="Print Bill"><i class="ri-bill-line"></i>&nbsp;Print Bill</a>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                    <tr><td colspan="20">{{ $pagination }}</td></tr>
                </div>
            </div>
        </div>
        
    </div>
</div>

<div class="modal fade" id="js-patient-profile-view-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="patient-modal-title">Patient Info</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" id="closeinfo">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="patient-form-container">
                    <div class="patient-form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group form-row">
                                    <label for="name" class="col-sm-4 col-form-label col-form-label-sm">Encounter:</label>
                                    <div class="col-sm-6">
                                        <label class="col-form-label col-form-label-sm" id="js_view_profile_encounter"></label>
                                    </div>
                                </div>
                                <div class="form-group form-row">
                                    <label for="name" class="col-sm-3 col-form-label col-form-label-sm">Name:</label>
                                    <div class="col-sm-9">
                                        <label class="col-form-label col-form-label-sm" id="js_view_name"></label>
                                    </div>
                                </div>
                                <div class="form-group form-row">
                                    <label for="address" class="col-sm-3 col-form-label col-form-label-sm">Address:</label>
                                    <div class="col-sm-9">
                                        <label class="col-form-label col-form-label-sm" id="js_view_address"></label>
                                    </div>
                                </div>
                                <div class="form-group form-row">
                                    <label for="gender" class="col-sm-3 col-form-label col-form-label-sm">Gender:</label>
                                    <div class="col-sm-9">
                                        <label class="col-form-label col-form-label-sm" id="js_view_gender"></label>
                                    </div>
                                </div>
                                <div class="form-group form-row">
                                    <label for="gender" class="col-sm-3 col-form-label col-form-label-sm">Contact No:</label>
                                    <div class="col-sm-9">
                                        <label class="col-form-label col-form-label-sm" id="js_view_contact"></label>
                                    </div>
                                </div>
                                <div class="form-group form-row">
                                    <label for="guardian" class="col-sm-3 col-form-label col-form-label-sm">Guardian:</label>
                                    <div class="col-sm-9">
                                        <label class="col-form-label col-form-label-sm" id="js_view_guardian"></label>
                                    </div>
                                </div>
                                <div class="form-group form-row">
                                    <label for="comment" class="col-sm-3 col-form-label col-form-label-sm">Comment:</label>
                                    <div class="col-sm-9">
                                        <label class="col-form-label col-form-label-sm" id="js_view_comment"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-row">
                                    <label for="bedno" class="col-sm-3 col-form-label col-form-label-sm">Patient No:</label>
                                    <div class="col-sm-7">
                                        <label class="col-form-label col-form-label-sm" id="js_view_patient_no"></label>
                                    </div>
                                </div>
                                <div class="form-group form-row">
                                    <label for="bedno" class="col-sm-3 col-form-label col-form-label-sm">SurName:</label>
                                    <div class="col-sm-9">
                                        <label class="col-form-label col-form-label-sm" id="js_view_surname"></label>
                                    </div>
                                </div>
                                <div class="form-group form-row">
                                    <label for="bedno" class="col-sm-3 col-form-label col-form-label-sm">District:</label>
                                    <div class="col-sm-9">
                                        <label class="col-form-label col-form-label-sm" id="js_view_district"></label>
                                    </div>
                                </div>
                                <div class="form-group form-row">
                                    <label for="bedno" class="col-sm-1 col-form-label col-form-label-sm">Age:</label>&nbsp;&nbsp;&nbsp;&nbsp;
                                    <label class="col-form-label col-form-label-sm" id="js_view_years">&nbsp;</label>
                                    <label for="bedno" class="col-sm-1 col-form-label col-form-label-sm">Years:</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <label class="col-form-label col-form-label-sm" id="js_view_month">&nbsp;</label>
                                    <label for="bedno" class="col-sm-2 col-form-label col-form-label-sm">Month:</label>
                                </div>
                                <div class="form-group form-row">
                                    <label for="bedno" class="col-sm-3 col-form-label col-form-label-sm">DOB:</label>
                                    <div class="col-sm-9">
                                        <label class="col-form-label col-form-label-sm" id="js_view_dob"></label>
                                    </div>
                                </div>
                                <div class="form-group form-row">
                                    <label for="bedno" class="col-sm-3 col-form-label col-form-label-sm">E-mail:</label>
                                    <div class="col-sm-9">
                                        <label class="col-form-label col-form-label-sm" id="js_view_email"></label>
                                    </div>
                                </div>
                                <div class="form-group form-row">
                                    <label for="bedno" class="col-sm-3 col-form-label col-form-label-sm">Relation:</label>
                                    <div class="col-sm-9">
                                        <label class="col-form-label col-form-label-sm" id="js_view_relation"></label>
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
            <form id="edit-consultant-form" action="{{ route('update-consultant-list') }}">
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
<script type="text/javascript">
    var addresses = JSON.parse('{!! $addresses !!}');
    var initdistricts = JSON.parse('{!! $districts !!}');
</script>
<script src="{{asset('js/registration_form.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        @if(Session::has('reg_patient_id'))
        @if(Options::get('issue_ticket')  == 'Yes')

        var patientId = "{{ Session::get('reg_patient_id') }}";
        window.open(baseUrl + '/registrationform/printticket/' + patientId, '_blank');
        @endif
        @endif
        @if(Session::has('billno'))
        var billno = "{{ Session::get('billno') }}";
        var encounterId = "{{ Session::get('reg_encounter_id') }}";
        window.open(baseUrl + '/billing/service/display-invoice?encounter_id=' + encounterId + '&invoice_number=' + billno, '_blank');
        @endif
    });
    $(document).ready(function () {
            $('.dropdown-toggle').dropdown();
        });

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

    @if(\Illuminate\Support\Facades\Session::has('edit_consult_message'))
        showAlert("{{ \Illuminate\Support\Facades\Session::get('edit_consult_message') }}",'error');
    @endif

</script>
@endpush
