@extends('frontend.layouts.master')

@section('content')
<style type="text/css">
    /* tr:hover, tr.selected {
    background-color: #FFCF8B
} */

.table tr.selected  {
    background-color:  #FFCF8B;
}
</style>
<div class="container-fluid">
    @include('menu::toggleButton')
    <div class="row">
       <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">
                            Receiving
                        </h4>
                    </div>
                    <a type="button" id="btn" class="btn btn-primary text-white" onclick="toggleSideBar(this)" title="Hide"><i class="fa fa-bars" aria-hidden="true"></i></a>
                </div>
            </div>
        </div>


        <div class="col-sm-12 leftdiv" id="myDIV">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <form id="patient_credit_report">
                        <div class="row">
                            
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="">From Date</label>
                                    <input type="text" name="fromdate" value="{{ request()->get('fromdate') ?: $date }}" class="form-control nepaliDatePicker" id="js-fromdate-input-nepaliDatePicker">
                                </div>
                                <div class="col-md-4">
                                    <label for="">To Date</label>
                                    <input type="text" name="todate" value="{{ request()->get('todate') ?: $date }}" class="form-control nepaliDatePicker" id="js-todate-input-nepaliDatePicker">
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Patient Name</label>
                                        <input type="text" name="patient_name" class="form-control" placeholder="Patient Name" value="{{request()->get('patient_name') ?? ''}}">
                                    </div>
                                </div>
                        </div>
                        <div class="row">
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Patient ID</label>
                                    <input type="text" name="patient_id" class="form-control" placeholder="Patient ID" value="{{request()->get('patient_id') ?? ''}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Encounter ID</label>
                                    <input type="text" name="encounter_id" class="form-control" placeholder="Encounter ID" value="{{request()->get('encounter_id') ?? ''}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Patient Phone Number</label>
                                    <input type="text" name="patient_number" class="form-control" placeholder="Patient Number" value="{{request()->get('patient_number') ?? ''}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 float-right">
                            <a href="{{route('laboratory.receiving.index')}}" type="button" class="btn btn-light btn-action float-right" ><i class="fa fa-redo"></i>&nbsp;Reset</a>&nbsp;
                            <button type="submit" class="btn btn-primary btn-action float-right"> <i class="fa fa-filter"></i>&nbsp;Filter</button>
                            <button type="submit" class="btn btn-light btn-action float-right mr-2" onclick="myFunction()">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    <div class="col-lg-5 col-md-12 leftdiv">
        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-body">
                <div class="res-table reporting-table table-sticky-th border">
                    <table class="table table-striped table-hover table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th class="tittle-th">EncID</th>
                                <th class="tittle-th" style="display: none;">Sample ID</th>
                                <th class="tittle-th">Name</th>
                                <th>Department</th>
                                <th>Userid</th>
                                <th>Datetime</th>
                            </tr>
                        </thead>
                        <tbody id="js-receiving-name-tbody" class="js-lab-module-name-search-tbody">
                            @if(!$labTestPatients->isEmpty())
                                @foreach($labTestPatients as $labTestPatient)
                                    @if($labTestPatient->fldencounterval)
                                    <tr data-encounterid="{{ $labTestPatient->fldencounterval }}">
                                        <td>{{ $labTestPatient->fldencounterval }}</td>
                                        <td style="display: none;">{{ $labTestPatient->fldsampleid }}</td>
                                        @if($labTestPatient->patientEncounter && $labTestPatient->patientEncounter->patientInfo)
                                        <td class="js-patient-name">
                                            {{ $labTestPatient->patientEncounter->patientInfo->fldrankfullname }}<br>
                                            {{Carbon\Carbon::parse($labTestPatient->patientEncounter->patientInfo->fldptbirday)->age ?? ''}} Y/{{$labTestPatient->patientEncounter->patientInfo->fldptsex ?? ''}}<br>
                                            {{$labTestPatient->patientEncounter->patientInfo->fldptcontact ?? ''}}
                                        </td>
                                        @else
                                        <td class="js-patient-name"></td>
                                        @endif
                                        <td>{{ ($labTestPatient->patientEncounter && $labTestPatient->patientEncounter->consultant) ? $labTestPatient->patientEncounter->consultant->fldconsultname : '' }}</td>
                                        <td>{{ $labTestPatient->flduserid_sample }}</td>
                                        <td>{{ $labTestPatient->fldtime_sample }}</td>
                                    </tr>
                                    @endif
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-7 col-md-12 rightdiv">
        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-body">
                <div class="res-table table-sticky-th border mb-2" style="min-height: 478px; ">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th class="tittle-th">&nbsp;</th>
                                <th class="tittle-th"><input type="checkbox" id="js-receiving-select-all-checkbox">&nbsp;</th>
                                <th class="tittle-th">Account Name</th>
                                <th class="tittle-th">Test Name</th>
                                <th class="tittle-th">Sample ID</th>
                                <th class="tittle-th">Sample Date</th>
                                <th class="tittle-th">Specimen</th>
                            </tr>
                        </thead>
                        <tbody id="js-receiving-samples-tbody"></tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group col-12">
                            <button id="" class="btn btn-primary btn-sm-in js-receiving-test-update-btn" value="Received">&nbsp;Accept</button>
                            <button id="" class="btn btn-danger btn-sm-in js-receiving-test-update-btn" value="Rejected">&nbsp;Reject</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
</div>
@endsection

@push('after-script')
{{-- <script src="{{asset('js/laboratory_form.js')}}"></script> --}}
<script>
    function receivingTestCheckbox(pat_billing_id){
        let isChecked = $(event.target).prop("checked")
        $(event.target).prop("checked", isChecked ? false : true)
        let id = event.target.getAttribute("patbillingid");
        if ($(`.child-receiving-checkbox-${id}:checked`).length > 0) {
            $(`.child-receiving-checkbox-${id}`).prop('checked', false);
        } else {
            $(`.child-receiving-checkbox-${id}`).prop('checked', true);
        }
    }
    //when click on left side of table js function
    // $(document).off('click', '#js-receiving-patient-tbody tr');
    var encounterid = '';
    $(document).on('click', '#js-receiving-name-tbody tr', function () {
        $(this).addClass("selected").siblings().removeClass("selected");
        $('#js-receiving-name-tbody tr').removeAttr('style');
        if($(".selected")){
            $(".selected").css("background-color", "#c8dfff");
        }
        $('#js-receiving-samples-tbody').empty();
        encounterid = $(this).data('encounterid');
        console.log(encounterid)
        $.ajax({
            // url: baseUrl + '/admin/laboratory/sampling/getTest',
            url: baseUrl + '/admin/laboratory/receiving/getPatientDetail',
            type: "GET",
            data: {encounter_id: encounterid, category: $('#js-sampling-category-select').val()},
            dataType: "json",
            success: function (response) {
                    let trData = '';
                    const checked = $('#js-receiving-select-all-checkbox').prop('checked') ? 'checked' : '';
                    $.each(response.samples, function (i, sample) {
                        trData += `<tr><td>${(i + 1)}</td>`;
                        trData += `<td><input type="checkbox"  ${checked} patbillingid="${sample.fldgroupid}" name="testids[]" class="js-receiving-labtest-checkbox child-receiving-checkbox-${sample.fldgroupid}"" value="${sample.fldid}" data-test="${sample.fldtestid}" onchange="receivingTestCheckbox(event)"></td>`;
                        trData += `<td>${sample.flditemname}</td>`;
                        trData += `<td>${sample.fldtestid}</td>`;
                        trData += `<td>${sample.fldsampleid}</td>`;
                        trData += `<td>${sample.fldtime_sample}</td>`;
                        trData += `<td>${(sample.fldsampletype ? sample.fldsampletype : '')}</td>`;
                        trData += `</tr>`;
                    });
                    $('#js-receiving-samples-tbody').append(trData);
            }
        });
    });

    $('#js-receiving-select-all-checkbox').change(function () {
        if ($(this).prop('checked'))
            $('.js-receiving-labtest-checkbox').prop('checked', true);
        else
            $('.js-receiving-labtest-checkbox').prop('checked', false);
    });

    $('.js-receiving-test-update-btn').click(function (e) {
        const status_value=$(this).val()
        e.preventDefault();
        var testids = [];
        $.each($('.js-receiving-labtest-checkbox:checked'), function (i, ele) {
            testids.push($(ele).val());
        });
        if (testids.length == 0) {
            showAlert("Please select all the test to update.", 'fail');
            return false;
        }
        updateTest(testids,status_value);
    });

    function updateTest(testids,status_value){

        Swal.fire({  
        title: `Do you want to ${status_value=='Received'?'accept':'reject'} the test`,  
        showDenyButton: true,  
        confirmButtonText: `Yes`,  
        denyButtonText: `No`,
        }).then((result) => { 
            if (result.isConfirmed) {  
                $.ajax({
                url: baseUrl + '/admin/laboratory/receiving/update-test',
                type: "POST",
                data: {testids,status_value},
                dataType: 'json',
                success: function (response, status, xhr) {
                    // const encid = $('#js-receiving-name-tbody tr').data('encounterid');
                    if(response.status==true){
                        $.each($('.js-receiving-labtest-checkbox:checked'), function (i, ele) {
                            $(ele).closest('tr').remove();
                        });
                        setTimeout(() => {
                            if ($('#js-receiving-samples-tbody tr').length == 0) {
                                $('#js-receiving-name-tbody tr[data-encounterid="' + encounterid + '"]').remove();
                                // $('#js-receiving-name-tbody tr tr[data-encounterid="' + encounterid + '"]').trigger();
                                
                            }else{
                                console.log('tedasdasst');
                                $('#js-receiving-name-tbody tr[data-encounterid="' + encounterid + '"]').trigger('click');
                            }
                        }, 200);
                        showAlert(response.message);
                    }
                },
                error: function (xhr, status, error) {
                    showAlert(response.message);
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
            } else if (result.isDenied) {    
                Swal.fire('Changes are not saved', '', 'info')  
            }
        });
    }
</script>
@endpush

