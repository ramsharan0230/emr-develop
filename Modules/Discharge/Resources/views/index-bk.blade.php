@extends('frontend.layouts.master')
<style>
    .text-haemo {
        color: #279faf;
    }
    .Haemodialysis-texarea {
        display: none;
    }
</style>
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
<div class="row">
    <div class="col-sm-12">
        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-header d-flex justify-content-between">
                <select class="form-control col-sm-3 mt-1" name="department"  id="department">
                    <option value="">--Select Department--</option>
                    @if(isset($dischargeDepartment))
                        @forelse($dischargeDepartment as $department)
                            <option value="{{ $department->flddept ?? null }}">{{ $department->flddept ?? null }}</option>
                        @empty
                            <option >No deparments availlable</option>
                        @endforelse
                    @endif
                </select>
{{--                <div class="form-group">--}}
{{--                    <a href="{{ route('discharge.reset-encounter') }}" class="btn btn-primary mt-2">Clear Form</a>--}}
{{--                </div>--}}
                <div class="form-group">
                    <button class="btn btn-primary mt-2"><i class="fa fa-filter"></i>Filter</button>
                </div>

            </div>
        </div>
    </div>

    <!-- includes patient profile -->
    <!-- @include('frontend.common.dischargePatientProfile') -->
    <!-- end patient profile -->
    <div class="col-sm-4">
        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-header d-flex justify-content-between">
                <div class="iq-header-title">
                    <h4 class="card-title">
                        Admitted Patient
                    </h4>
                </div>
            </div>
            <div class="iq-card-body">
                <div class="res-table">
                    <table class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Encounter</th>
                                <th>Name</th>
                                <th>Bed N0.</th>
                                <th>
                                    <button class="btn btn-primary btn-sm">
                                        <i class="fas fa-arrow-right"></i>
                                    </button>
                                </th>
                            </tr>
                        </thead>
                        <tbody id="patient_tbody">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="" class="label-bold">Digonosis</label>
                            <div class="form-row">
                                <div class="col-sm-10">
                                    <textarea class="form-control textarea-scroll" row="7" id="diagnosistext"></textarea>
                                </div>
                                <div class="col-sm-2">
                                    <button class="btn btn-primary btn-sm">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                    <button class="btn btn-primary btn-sm" id="diagnosisBtn">
                                        <i class="fas fa-arrow-left"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="label-bold">Persisting Complaints:</label>
                            <div class="form-row">
                                <div class="col-sm-10">
                                    <textarea class="form-control textarea-scroll" row="7"  id="complaints"></textarea>
                                </div>
                                <div class="col-sm-2">
                                    <button class="btn btn-primary btn-sm">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                    <button class="btn btn-primary btn-sm" id="complaintsBtn">
                                        <i class="fas fa-arrow-left"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="label-bold">on examination:</label>
                            <div class="form-row">
                                <div class="col-sm-10">
                                    <textarea class="form-control textarea-scroll" row="7" id="onExamination"></textarea>
                                </div>
                                <div class="col-sm-2">
                                    <button class="btn btn-primary btn-sm">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                    <button class="btn btn-primary btn-sm" id="onExaminationBtn">
                                        <i class="fas fa-arrow-left"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="label-bold">
{{--                                physical and systemic --}}
                                Surgerical Note:</label>
                            <div class="form-row">
                                <div class="col-sm-10">
                                    <textarea class="form-control textarea-scroll" row="7" id="surgericalNote"></textarea>
                                </div>
                                <div class="col-sm-2">
                                    <button class="btn btn-primary btn-sm">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                    <button class="btn btn-primary btn-sm" id="surgericalNoteBtn">
                                        <i class="fas fa-arrow-left"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="label-bold">Medication:</label>
                            <div class="form-row">
                                <div class="col-sm-10">
                                    <textarea class="form-control textarea-scroll" row="7" id="medicine"></textarea>
                                </div>
                                <div class="col-sm-2">
                                    <button class="btn btn-primary btn-sm">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                    <button class="btn btn-primary btn-sm" id="medicineBtn">
                                        <i class="fas fa-arrow-left"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="label-bold">Past History:</label>
                            <div class="form-row">
                                <div class="col-sm-10">
                                    <textarea class="form-control textarea-scroll" row="7" id="pastHistory"></textarea>
                                </div>
                                <div class="col-sm-2">
                                    <button class="btn btn-primary btn-sm">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                    <button class="btn btn-primary btn-sm" id="pastHistoryBtn">
                                        <i class="fas fa-arrow-left"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="label-bold">PHYSICAL AND SYSTEMIC EXAMINATION:</label>
                            <div class="form-row">
                                <div class="col-sm-10">
                                    <textarea class="form-control textarea-scroll" row="7" id="physicalExamination"></textarea>
                                </div>
                                <div class="col-sm-2">
                                    <button class="btn btn-primary btn-sm">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                    <button class="btn btn-primary btn-sm" id="physicalExaminationBtn">
                                        <i class="fas fa-arrow-left"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

{{--                        <div class="form-group">--}}
{{--                            <label for="" class="label-bold">Apendicities:</label>--}}
{{--                            <div class="form-row">--}}
{{--                                <div class="col-sm-10">--}}
{{--                                    <textarea class="form-control textarea-scroll" row="7"></textarea>--}}
{{--                                </div>--}}
{{--                                <div class="col-sm-2">--}}
{{--                                    <button class="btn btn-primary btn-sm">--}}
{{--                                        <i class="fa fa-plus"></i>--}}
{{--                                    </button>--}}
{{--                                    <button class="btn btn-primary btn-sm">--}}
{{--                                        <i class="fas fa-arrow-left"></i>--}}
{{--                                    </button>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        <div class="form-group">
                            <label for="" class="label-bold">Operation performed:</label>
                            <div class="form-row">
                                <div class="col-sm-10">
                                    <textarea class="form-control textarea-scroll" row="7" id="operation"></textarea>
                                </div>
                                <div class="col-sm-2">
                                    <button class="btn btn-primary btn-sm">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                    <button class="btn btn-primary btn-sm" id="opertionBtn">
                                        <i class="fas fa-arrow-left"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
{{--                        <div class="form-group">--}}
{{--                            <label for="" class="label-bold">Operation Details</label>--}}
{{--                            <div class="form-row">--}}
{{--                                <div class="col-sm-10">--}}
{{--                                    <textarea class="form-control textarea-scroll" row="7"></textarea>--}}
{{--                                </div>--}}
{{--                                <div class="col-sm-2">--}}
{{--                                    <button class="btn btn-primary btn-sm">--}}
{{--                                        <i class="fa fa-plus"></i>--}}
{{--                                    </button>--}}
{{--                                    <button class="btn btn-primary btn-sm">--}}
{{--                                        <i class="fas fa-arrow-left"></i>--}}
{{--                                    </button>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="form-group">--}}
{{--                            <label for="" class="label-bold">Course in hospital:</label>--}}
{{--                            <div class="form-row">--}}
{{--                                <div class="col-sm-10">--}}
{{--                                    <textarea class="form-control textarea-scroll" row="7"></textarea>--}}
{{--                                </div>--}}
{{--                                <div class="col-sm-2">--}}
{{--                                    <button class="btn btn-primary btn-sm">--}}
{{--                                        <i class="fa fa-plus"></i>--}}
{{--                                    </button>--}}
{{--                                    <button class="btn btn-primary btn-sm">--}}
{{--                                        <i class="fas fa-arrow-left"></i>--}}
{{--                                    </button>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                        <div class="form-group">--}}
{{--                            <label for="" class="label-bold">Diet:</label>--}}
{{--                            <div class="form-row">--}}
{{--                                <div class="col-sm-10">--}}
{{--                                    <textarea class="form-control textarea-scroll" row="7"></textarea>--}}
{{--                                </div>--}}
{{--                                <div class="col-sm-2">--}}
{{--                                    <button class="btn btn-primary btn-sm">--}}
{{--                                        <i class="fa fa-plus"></i>--}}
{{--                                    </button>--}}
{{--                                    <button class="btn btn-primary btn-sm">--}}
{{--                                        <i class="fas fa-arrow-left"></i>--}}
{{--                                    </button>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="form-group">--}}
{{--                            <label for="" class="label-bold">Special Instruction:</label>--}}
{{--                            <div class="form-row">--}}
{{--                                <div class="col-sm-10">--}}
{{--                                    <textarea class="form-control textarea-scroll" row="7"></textarea>--}}
{{--                                </div>--}}
{{--                                <div class="col-sm-2">--}}
{{--                                    <button class="btn btn-primary btn-sm">--}}
{{--                                        <i class="fa fa-plus"></i>--}}
{{--                                    </button>--}}
{{--                                    <button class="btn btn-primary btn-sm">--}}
{{--                                        <i class="fas fa-arrow-left"></i>--}}
{{--                                    </button>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="form-group">--}}
{{--                            <label for="" class="label-bold">Advice and discharge:</label>--}}
{{--                            <div class="form-row">--}}
{{--                                <div class="col-sm-10">--}}
{{--                                    <textarea class="form-control textarea-scroll" row="7"></textarea>--}}
{{--                                </div>--}}
{{--                                <div class="col-sm-2">--}}
{{--                                    <button class="btn btn-primary btn-sm">--}}
{{--                                        <i class="fa fa-plus"></i>--}}
{{--                                    </button>--}}
{{--                                    <button class="btn btn-primary btn-sm">--}}
{{--                                        <i class="fas fa-arrow-left"></i>--}}
{{--                                    </button>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="form-group">--}}
{{--                            <label for="" class="label-bold">Discharge summary Prepared by:</label>--}}
{{--                            <div class="form-row">--}}
{{--                                <div class="col-sm-10">--}}
{{--                                    <textarea class="form-control textarea-scroll" row="7"></textarea>--}}
{{--                                </div>--}}
{{--                                <div class="col-sm-2">--}}
{{--                                    <button class="btn btn-primary btn-sm">--}}
{{--                                        <i class="fa fa-plus"></i>--}}
{{--                                    </button>--}}
{{--                                    <button class="btn btn-primary btn-sm">--}}
{{--                                        <i class="fas fa-arrow-left"></i>--}}
{{--                                    </button>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="form-group">--}}
{{--                            <label for="" class="label-bold">Discharge summary submitted by:</label>--}}
{{--                            <div class="form-row">--}}
{{--                                <div class="col-sm-10">--}}
{{--                                    <textarea class="form-control textarea-scroll" row="7"></textarea>--}}
{{--                                </div>--}}
{{--                                <div class="col-sm-2">--}}
{{--                                    <button class="btn btn-primary btn-sm">--}}
{{--                                        <i class="fa fa-plus"></i>--}}
{{--                                    </button>--}}
{{--                                    <button class="btn btn-primary btn-sm">--}}
{{--                                        <i class="fas fa-arrow-left"></i>--}}
{{--                                    </button>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}

                        <div class="d-flex justify-content-center">
                            <button class="btn btn-primary rounded-pill" type="button">
                                Save
                            </button>
                            &nbsp;
                            <button class="btn btn-primary rounded-pill" type="button">
                                PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@push('after-script')

<script type="text/javascript">

        $('#start_date').nepaliDatePicker({
           npdMonth: true,
           npdYear: true,

       });

        $('#type_access_date').nepaliDatePicker({
           npdMonth: true,
           npdYear: true,

       });
        $('#general_next_hddate').nepaliDatePicker({
           npdMonth: true,
           npdYear: true,

       });

        CKEDITOR.replace('haemodialysis_plan_capd',
        {
        height: '100px',
        } );
      CKEDITOR.replace('haemodialysis_plan_planhaemodialysi',
        {
        height: '100px',
        } );
      CKEDITOR.replace('haemodialysis_plan_renaltransplatation',
        {
        height: '100px',
        } );
      CKEDITOR.replace('emergency_attended_center',
        {
        height: '100px',
        } );
      CKEDITOR.replace('emergency_indication',
        {
        height: '100px',
        } );
      CKEDITOR.replace('general_comorbidities',
            {
            height: '200px',
            } );

      CKEDITOR.replace('type_cause',
            {
            height: '200px',
            } );
      CKEDITOR.replace('body_remarks',
            {
            height: '200px',
            } );

      CKEDITOR.replace('treatement_dialysis_number',
            {
            height: '200px',
            } );
      CKEDITOR.replace('blood_predialysis',
            {
            height: '200px',
            } );
      CKEDITOR.replace('blood_postdialysis',
            {
            height: '200px',
            } );
      CKEDITOR.replace('blood_investigation',
            {
            height: '200px',
            } );
      CKEDITOR.replace('blood_doctor_examination',
            {
            height: '200px',
            } );
      var table = $('table.datatable').DataTable({
    "paging": false

});

    function saveHaemodialysy(){
        // alert('save haemo');
        var url = "{{route('saveHaemodialysis')}}";
        var alldata = $("#heamodialysis_data").serialize();
        // alert(alldata);
        for (var i in CKEDITOR.instances) {
            CKEDITOR.instances[i].updateElement();
        };
        $.ajax({
            url: url,
            type: "POST",
            data:  $("#heamodialysis_data").serialize(),"_token": "{{ csrf_token() }}",
            success: function(response) {
                // response.log()
                // console.log(response);
                // $('#select-multiple-diagno').html(response);
                // $('#diagnosis').modal('hide');
                showAlert('Information Saved !!');
                // if ($.isEmptyObject(data.error)) {
                //     showAlert('Data Added !!');
                //     $('#allergy-freetext-modal').modal('hide');
                // } else
                //     showAlert('Something went wrong!!');
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
                });
    }

    $('#department').change( function (){
        var department = $(this).val();
        var url = "{{route('patient-department-wise')}}";

       if(department== '' || typeof department == 'undefined' || typeof department == null){
            return false;
       }

        $.ajax({
            url: url,
            type: "POST",
            data: {
                "_token": "{{ csrf_token() }}",
                department : department,
            },
            success: function(data) {
                var html ='';
                if (data.patients.length === 0)
                {
                    var html ='';
                    html += '<td align="center" colspan="4">No data availlable!</td>';
                    $('#patient_tbody').html("");
                    $('#patient_tbody').append(html);
                }else {
                    $.each(data.patients, function (index, value) {
                        var name = (value.fldptnamefir)+' '+(value.fldmidname !=null ? value.fldmidname : '' )+' '+(value.fldptnamelast) ;
                        var gender = (value.fldptsex==='Male' ? 'M':'F');

                        html += '<tr><td>'+ value.fldencounterval+'</td ><td>'+ name +'/'+gender+'/'+ value.age+' </td> <td>'+value.fldbed+'</td> <td><button type="button"  class="btn btn-primary btn-sm detailBtn" data-encounter="'+value.fldencounterval +'"> <i class="fas fa-arrow-right"></i> </button></td</tr>';
                    });
                    $('#patient_tbody').html("");
                    $('#patient_tbody').append(html);
                }

            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });


    });

    //patientProfile data
        $(document).on('click', '.detailBtn', function () {
           var encounter = $(this).data('encounter');
           var url= "{{ route('populate-patient-profile') }}";
            if(encounter== '' || typeof encounter == 'undefined' || typeof encounter == null){
                return false;
            }

            $.ajax({
                url: url,
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    encounter_id : encounter,
                },
                success: function(data) {
                    console.log(data.billing)
                   if(data.length != 0)
                   {

                       dob = new Date(data.patient.fldptbirday);
                       var today = new Date();
                       var age = Math.floor((today-dob) / (365.25 * 24 * 60 * 60 * 1000));


                       var name= (data.patient.fldfullname)
                       var patID= (data.enpatient.fldpatientval)
                       var encID= (data.enpatient.fldencounterval)
                       var height= (data.height)
                       var heigt_rate = (data.heightrate)
                       var bmi = (data.bmi)
                       var weight = ( data.body_weight != null && data.body_weight.fldrepquali) ? data.body_weight.fldrepquali : '' ;
                       var doReg = (data.enpatient.fldregdate)
                       var location = (data.enpatient.fldcurrlocat==='Discharged') ? 'Discharged' : (data.enpatient.fldcurrlocat)
                       var status = (data.enpatient.fldadmission)
                       var gender = (data.patient.fldptsex)
                       var address = (data.patient.fldptaddvill)+'/'+(data.patient.fldptadddist)

                       $('#selectedEncounter').val(encID);
                       $('#fldencounterval').val(encID);
                       $('#encounter_id').val(encID);

                       $('#patientName').html('');
                       $('#patID').html('');
                       $('#EncID').html('');
                       $('#gender').html('');
                       $('#heightvalue').val('');
                       $('#weight').val('');
                       $('#bmi').html('');
                       $('#age').html('');
                       $('#DOReg').html('');
                       $('#location').html('');
                       $('#admitedstatus').html('');
                       $('#address').html('');
                       $('#heightrate').html('');

                       $('#patientName').html(name);
                       $('#patID').html(patID);
                       $('#EncID').html(encID);
                       $('#gender').html(gender);
                       $('#heightvalue').val(height);
                       $('#weight').val(weight);
                       $('#bmi').html(bmi);
                       $('#address').html(address);
                       $('#age').html(age+' Years/' + gender);
                       $('#DOReg').html(doReg);
                       $('#location').html(location);
                       $('#admitedstatus').html(status);
                       $('#billingmode').empty().append((data.billing) ? data.billing : '')
                       $('#heightrate').html(data.heightrate);
                        if(data.heightrate==='cm')
                        {
                            $html ='<option selected>cm </option>'
                            $('#heightrate').empty().append($html)
                        }
                       if(data.heightrate==='m')
                       {
                           $html ='<option selected>m </option>'
                           $('#heightrate').empty().append($html)

                       }

                   }

                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });

        });


//Diagnosis plot garne script
        $(document).on('click', '#diagnosisBtn', function () {
            var encounter = $('#fldencounterval').val();
            var url = "{{route('populate-patient-diagnosis')}}";
            if(encounter== '' || typeof encounter == 'undefined' || typeof encounter == null){
                return false;
            }
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    encounter_id : encounter,
                },
                success: function(data) {
                    var textarea = document.getElementById('diagnosistext');
                    if (data.length === 0)
                    {
                        textarea.value='';
                        textarea.value += 'No Diagnosis Found';
                    }else {
                        textarea.value='';
                        textarea.value=data;
                    }
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });

        });

        //complaints haru fetch gareko

        $(document).on('click', '#complaintsBtn', function () {
            var encounter = $('#fldencounterval').val();
            var url = "{{route('populate-patient-complaints')}}";
            if(encounter== '' || typeof encounter == 'undefined' || typeof encounter == null){
                return false;
            }
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    encounter_id : encounter,
                },
                success: function(data) {
                    var textarea = document.getElementById('complaints');
                    if (data === 0)
                    {
                        textarea.value='';
                        textarea.value += 'No complaints Found';
                    }else {
                        textarea.value='';
                        textarea.value=data;
                    }
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });

        });

        //on examination fetch gareko
        $(document).on('click', '#onExaminationBtn', function () {
            var encounter = $('#fldencounterval').val();
            var url = "{{route('populate-patient-onExamination')}}";
            if(encounter== '' || typeof encounter == 'undefined' || typeof encounter == null){
                return false;
            }
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    encounter_id : encounter,
                },
                success: function(data) {
                    var textarea = document.getElementById('onExamination');
                    if (data.length === 0)
                    {
                        textarea.value='';
                        textarea.value += 'No Examination Found';
                    }else {
                        textarea.value='';
                        textarea.value=data;
                    }
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });

        });

        //Surgerical Note or operation perfomred fetch gareko

        $(document).on('click', '#surgericalNoteBtn', function () {
            var encounter = $('#fldencounterval').val();
            var url = "{{route('populate-patient-operationPerformed')}}";
            if(encounter== '' || typeof encounter == 'undefined' || typeof encounter == null){
                return false;
            }
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    encounter_id : encounter,
                },
                success: function(data) {
                    var textarea = document.getElementById('surgericalNote');
                    if (data.length === 0)
                    {
                        textarea.value='';
                        textarea.value += 'No Note Found';
                    }else {
                        textarea.value='';
                        textarea.value=data;
                    }
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });

        });

        //Medicine fetch gareko
        $(document).on('click', '#medicineBtn', function () {
            var encounter = $('#fldencounterval').val();
            var url = "{{route('populate-patient-medicine')}}";
            if(encounter== '' || typeof encounter == 'undefined' || typeof encounter == null){
                return false;
            }
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    encounter_id : encounter,
                },
                success: function(data) {
                    var textarea = document.getElementById('medicine');
                    if (data.length === 0)
                    {
                        textarea.value='';
                        textarea.value += 'No Medicine Found';
                    }else {
                        textarea.value='';
                        textarea.value=data;
                    }
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });

        });

        //Past history fetch gareko
        $(document).on('click', '#pastHistoryBtn', function () {
            var encounter = $('#fldencounterval').val();
            var url = "{{route('populate-patient-pastHistory')}}";
            if(encounter== '' || typeof encounter == 'undefined' || typeof encounter == null){
                return false;
            }
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    encounter_id : encounter,
                },
                success: function(data) {
                    var textarea = document.getElementById('pastHistory');
                    if (data.length <= 0 ||  typeof data.length == null)
                    {
                        textarea.value='';
                        textarea.value += 'No History Found';
                    }else {
                        textarea.value='';
                        textarea.value=data;
                    }
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });

        });

        //phyasical and systemica examination ra on examination eutai ho re
        $(document).on('click', '#physicalExaminationBtn', function () {
            var encounter = $('#fldencounterval').val();
            var url = "{{route('populate-patient-onExamination')}}";
            if(encounter== '' || typeof encounter == 'undefined' || typeof encounter == null){
                return false;
            }
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    encounter_id : encounter,
                },
                success: function(data) {
                    var textarea = document.getElementById('physicalExamination');
                    if (data === 0)
                    {
                        textarea.value='';
                        textarea.value += 'No Examination Found';
                    }else {
                        textarea.value='';
                        textarea.value=data;
                    }
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });

        });
//operation performed
        $(document).on('click', '#opertionBtn', function () {
            var encounter = $('#fldencounterval').val();
            var url = "{{route('populate-patient-operationPerformed')}}";
            if(encounter== '' || typeof encounter == 'undefined' || typeof encounter == null){
                return false;
            }
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    encounter_id : encounter,
                },
                success: function(data) {
                    var textarea = document.getElementById('operation');
                    if (data.length === 0)
                    {
                        textarea.value='';
                        textarea.value += 'No Note Found';
                    }else {
                        textarea.value='';
                        textarea.value=data;
                    }
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });

        });
        $(document).ready(function () {
            setTimeout(function () {
                $("#department").select2({
                    
                });
                
            }, 1500);
        });

</script>
@endpush
