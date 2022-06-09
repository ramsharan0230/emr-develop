@extends('frontend.layouts.master') @section('content')
{{--navbar--}}
@include('menu::common.nav-bar')
{{--end navbar--}}
<div class="container-fluid">
    <div class="row">
      <div class="col-sm-12">
        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-header d-flex justify-content-between">
                <div class="iq-header-title">
                    <h4 class="card-title">
                        Diagnostic Report/Radiology Report
                    </h4>
                </div>
                <button onclick="myFunction()" class="btn btn-primary"><i class="fa fa-bars"></i></button>
            </div>
        </div>
        </div>
        <div class="col-sm-12"  id="myDIV">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group form-row align-items-center er-input">
                                <label for="" class="col-sm-4 col-lg-3">Status:</label>
                                <div class="col-sm-8 col-lg-9">
                                    <select name="radiology_status" class="form-control form-control-sm" id="radiology_status">
                                        <option value="Sampled">Sampled</option>
                                        <option value="Reported" selected="">Reported</option>
                                        <option value="Verified">Verified</option>
                                        <option value="CheckIn">CheckIn</option>
                                        <option value="Appointment">Appointment</option>
                                        <option value="Ordered">Ordered</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group form-row align-items-center er-input">
                                <label for="" class="col-sm-4 col-lg-3">From:</label>
                                <div class="col-sm-8 col-lg-9">
                                    <input type="text" class="form-control" id="radiology_from_date" name="radiology_from_date" value="{{isset($date) ? $date : ''}}" autocomplete="off" />
                                </div>
                            </div>
                            <div class="form-group form-row align-items-center er-input">
                                <label for="" class="col-sm-4 col-lg-3">To:</label>
                                <div class="col-sm-8 col-lg-9">
                                    <input type="text" class="form-control" name="radiology_to_date" id="radiology_to_date" value="{{isset($date) ? $date : ''}}" autocomplete="off" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group form-row align-items-center er-input">
                                <label for="" class="col-sm-5">Section:</label>
                                <div class="col-sm-7">
                                    <select name="radiology_section" id="radiology_section" class="form-control form-control-sm">
                                        <option value="%">%</option>
                                        <option value="Dental">Dental</option>
                                        <option value="Doppler">Doppler</option>
                                        <option value="ECG">ECG</option>
                                        <option value="Ultrasound">Ultrasound</option>
                                        <option value="Ultrasound+Doppler">Ultrasound+Doppler</option>
                                        <option value="X-ray">X-ray</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group form-row align-items-center er-input">
                                <label for="" class="col-sm-6">Evaluation:</label>
                                <div class="col-sm-6">
                                    <select name="radiology_evaluation" id="radiology_evaluation" class="form-control form-control-sm">
                                        <option value="%">%</option>

                                    </select>
                                </div>
                            </div>
                            <div class="form-group form-row align-items-center er-input">
                                <label for="" class="col-sm-6">Gender:</label>
                                <div class="col-sm-6">
                                    <select name="radiology_gender" class="form-control form-control-sm" id="radiology_gender">
                                        <option value="%">%</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group form-row align-items-center er-input">
                                <label for="" class="col-sm-5">Exam</label>
                                <div class="col-sm-7">
                                    <select name="radiology_exam" id="radiology_exam" class="form-control form-control-sm">
                                        <option value="%">%</option>

                                    </select>

                                </div>
                            </div>
                            <div class="form-group form-row align-items-center er-input">
                                <label for="" class="col-sm-5">Subexam:</label>
                                <div class="col-sm-7">
                                    <select name="radiology_sub_exam" id="radiology_sub_exam" class="form-control form-control-sm">
                                        <option value="%">%</option>

                                    </select>
                                </div>
                            </div>
                            <div class="form-group form-row align-items-center er-input">
                                <label for="" class="col-sm-5 pr-0">Age (yr):</label>
                                <div class="col-sm-4">
                                    <input type="text" name="radiology_age_from" id="radiology_age_from" class="form-control form-control-sm" placeholder="from" />
                                </div>
                                &nbsp;
                                <div class="col-sm-2 p-0">
                                    <input type="text" name="radiology_age_to" id="radiology_age_to" class="form-control form-control-sm" placeholder="to" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group form-row align-items-center er-input">
                                <select name="rtest_normal_type" id="rtest_normal_type" class="form-control form-control-sm">
                                    <option value="%">%</option>
                                    <option value="1">Abnormal</option>
                                    <option value="0">Normal</option>
                                </select>
                            </div>
                            <div class="form-group form-row align-items-center er-input">
                                <select name="rstest_normal_type" id="rstest_normal_type" class="form-control form-control-sm">
                                    <option value="%">%</option>
                                    <option value="1">Abnormal</option>
                                    <option value="0">Normal</option>
                                </select>
                            </div>
                            <div class="form-group form-row align-items-center er-input">
                                <label for="address" class="col-sm-4">Text</label>
                                <div class="col-sm-7">
                                    <input type="text" name="radiology_ex_text" id="radiology_ex_text" class="form-control form-control form-control-sm" />
                                </div>
                                <div class="col-sm-1">
                                    <input type="checkbox" name="enable_txtSearch" value="1" id="enable_txtSearch" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                    <button class="btn btn-primary  rounded-pill" type="button" onclick="showRadiologyResult()"><i class="fa fa-sync"></i>&nbsp;Refresh</button>&nbsp;
                    <button class="btn btn-warning rounded-pill" type="button" onclick="exportRadiologyReport()"><i class="fas fa-external-link-square-alt"></i>&nbsp;&nbsp;Export</button>
                </div>
            </div>
        </div>
   </div>
   <div class="col-sm-12">
    <div class="iq-card">
        <div class="iq-card-body">
            <ul class="nav nav-tabs" id="myTab-1" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Gridview</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Chartview</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent-2">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="table-responsive table-container">
                        <table class="table table-striped table-hover table-bordered ">
                            <thead class="thead-light">
                                <tr>
                                    <th class="tittle-th">Index</th>
                                    <th class="tittle-th">EncID</th>
                                    <th class="tittle-th" width="300">Name</th>
                                    <th class="tittle-th">Age</th>
                                    <th class="tittle-th">Gender</th>
                                    <th class="tittle-th">TestName</th>
                                    <th class="tittle-th">Date</th>
                                    <th class="tittle-th">Status</th>
                                    <th class="tittle-th">Observation</th>
                                </tr>
                            </thead>
                            <tbody id="diagnostic_examination_data"></tbody>
                        </table>
                        <div id="bottom_anchor"></div>
                    </div>
                </div>
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab"></div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
@stop

@push('after-script')
<script type="text/javascript">
    // $('#radiology_to_date').datetimepicker({

    //     changeMonth: true,
    //     changeYear: true,
    //     dateFormat: 'yy-mm-dd',
    //     yearRange: "1600:2032",

    // });
    // $('#radiology_from_date').datetimepicker({

    //     changeMonth: true,
    //     changeYear: true,
    //     dateFormat: 'yy-mm-dd',
    //     yearRange: "1600:2032",

    // });

    $('#radiology_from_date').nepaliDatePicker({
           npdMonth: true,
           npdYear: true,

       });
    $('#radiology_to_date').nepaliDatePicker({
           npdMonth: true,
           npdYear: true,

       });

    $('#radiology_section').on('change', function(){
        var section = $(this).val();
        if(section !=''){
            $.ajax({
                url: '{{ route('list.exams.form.diagnostic.consultant') }}',
                type: "POST",
                data: {section:section,"_token": "{{ csrf_token() }}"},
                success: function (response) {
                    $('#diagnostic_exam').empty().html(response);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }
    });
    $('#diagnostic_exam').on('change', function(){
        var exam = $(this).val();
        if(exam !=''){
            $.ajax({
                url: '{{ route('list.subexams.form.diagnostic.consultant') }}',
                type: "POST",
                data: {exam:exam,"_token": "{{ csrf_token() }}"},
                success: function (response) {

                    $('#diagnostic_sub_exam').empty().html(response);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }
    });

    function showRadiologyResult(){
           // alert('here');
           var fdate = $('#radiology_from_date').val();
           var tdate = $('#radiology_to_date').val();
           var evaluation = $('#radiology_evaluation').val();
           var section = $('#radiology_section').val();
           var gender = $('#radiology_gender').val();
           var fage = $('#radiology_age_from').val();
           var tage = $('#radiology_age_to').val();
           var exam = $('#radiology_exam').val();
           var sexam = $('#radiology_sub_exam').val();
           var ttype = $('#rtest_normal_type').val();
           var sttype = $('#rstest_normal_type').val();
           var extext = $('#radiology_ex_text').val();
           var status = $('#radiology_status').val();
           if($('#enable_txtSearch').is(":checked")){
            var txSearch = 1;
        }else{
            var txSearch = 0;
        }
        $.ajax({
            url: '{{ route('search.radiology.form.diagnostic.consultant') }}',
            type: "POST",
            data: {fdate:fdate,tdate:tdate,evaluation:evaluation,section:section,gender:gender,fage:fage,tage:tage,exam:exam,sexam:sexam,ttype:ttype,sttype:sttype,extext:extext,status:status,txSearch:txSearch,"_token": "{{ csrf_token() }}"},
            success: function (response) {
                       //alert('dfgfdg');
                       $('#diagnostic_examination_data').html(response);
                   },
                   error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
    }

    function exportRadiologyReport(){
            // alert(baseUrl);
            $('form').submit(false);
            data = $('#radiologyForm').serialize();
           // alert(data);
           var urlReport = baseUrl + "/consultation/export-radiology-report?" + data + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";


           window.open(urlReport, '_blank');
       }

       $('#radiology_from_date_bs').nepaliDatePicker({

        npdMonth: true,
        npdYear: true,
        npdYearCount: 100,
            // disableDaysAfter: '1',
            onChange: function () {
                var datebs = $('#radiology_from_date_bs').val();
                $.ajax({
                    type: 'post',
                    url: '{{ route("patient.request.menu.nepalitoenglish") }}',
                    data: {date: datebs,},
                    success: function (response) {
                        $('#radiology_from_date').val(response);
                    }
                });
            }

        });
       $('#radiology_to_date_bs').nepaliDatePicker({

        npdMonth: true,
        npdYear: true,
        npdYearCount: 100,
            // disableDaysAfter: '1',
            onChange: function () {
                var datebs = $('#radiology_from_date_bs').val();
                $.ajax({
                    type: 'post',
                    url: '{{ route("patient.request.menu.nepalitoenglish") }}',
                    data: {date: datebs,},
                    success: function (response) {
                        $('#radiology_to_date').val(response);
                    }
                });
            }

        });
    </script>
    @endpush
