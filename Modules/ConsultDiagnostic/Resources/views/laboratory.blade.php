@extends('frontend.layouts.master')
@section('content')
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
                        Diagnostic Report/Laboratory Report
                    </h4>
                </div>
                <button onclick="myFunction()" class="btn btn-primary"><i class="fa fa-bars"></i></button>
            </div>
        </div>
    </div>
    <form id="laboratoryForm">
        <div class="col-sm-12" id="myDIV">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group form-row align-items-center er-input">
                                <label for="" class="col-sm-4 col-lg-3">Status:</label>
                                <div class="col-sm-8 col-lg-9">
                                    <select name="lab_status" class="form-control form-control-sm" id="lab_status">
                                        <option value="Sampled">Sampled</option>
                                        <option value="Reported" selected="">Reported</option>
                                        <option value="Verified">Verified</option>
                                        <option value="Not Done">Not Done</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group form-row align-items-center er-input">
                                <label for="" class="col-sm-4 col-lg-3">From:</label>
                                <div class="col-sm-8 col-lg-9">
                                    <input type="text" name="lab_from_date" class="form-control" id="lab_from_date" value="{{isset($date) ? $date : ''}}" autocomplete="off" />

                                    <!-- <a href="javascript:;"  id="lab_from_date_bs"><img src="{{asset('assets/images/calendar.png')}}" width="16px"></a> -->

                                </div>

                            </div>
                            <div class="form-group form-row align-items-center er-input">
                                <label for="" class="col-sm-4 col-lg-3">To:</label>
                                <div class="col-sm-8 col-lg-9">
                                    <input type="text" class="form-control" name="lab_to_date" id="lab_to_date" value="{{isset($date) ? $date : ''}}" />
                                    <!-- <a href="javascript:;"  id="lab_to_date_bs"><img src="{{asset('assets/images/calendar.png')}}" width="16px"></a> -->
                                </div>
                            </div>
                            <div class="form-group form-row align-items-center er-input">
                                <label for="" class="col-sm-4 col-lg-3">Gender:</label>
                                <div class="col-sm-8 col-lg-9">
                                    <select name="lab_gender" class="form-control form-control-sm" id="lab_gender">
                                        <option value="%">%</option>
                                        <option value="Female">Female</option>
                                        <option value="Male">Male</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group form-row align-items-center er-input">
                                <label for="" class="col-sm-5 p-0">Section:</label>
                                <div class="col-sm-7">
                                    <select name="lab_section" id="lab_section" class="form-control form-control-sm">
                                        <option value="%">%</option>
                                        @if(isset($sectionlist) and count($sectionlist) > 0)
                                        @foreach($sectionlist as $d)
                                        <option value="{{$d->flclass}}">{{$d->flclass}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group form-row align-items-center er-input">
                                <label for="" class="col-sm-5 p-0">Specimen:</label>
                                <div class="col-sm-7">
                                    <select name="lab_specimen" class="form-control form-control-sm" id="lab_specimen">
                                        <option value="%">%</option>
                                        @if(isset($specimen) and count($specimen) > 0)
                                        @foreach($specimen as $s)
                                        <option value="{{$s->fldsampletype}}">{{$s->fldsampletype}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group form-row align-items-center er-input">
                                <label for="" class="col-sm-5 p-0">Condition:</label>
                                <div class="col-sm-7">
                                    <input type="text" name="lab_condition" id="lab_condition" value="" class="form-control form-control-sm" />
                                </div>
                            </div>
                            <div class="form-group form-row align-items-center er-input">
                                <label for="" class="col-sm-5 p-0">Age (yr):</label>
                                <div class="col-sm-4">
                                    <input type="text" name="lab_age_from" id="lab_age_from" class="form-control form-control-sm" placeholder="from" />
                                </div>
                                &nbsp;
                                <div class="col-sm-2 p-0">
                                    <input type="text" name="lab_age_to" id="lab_age_to" class="form-control form-control-sm" placeholder="to" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group form-row align-items-center er-input">
                                <label for="" class="col-sm-4 p-0">Test</label>
                                <div class="col-sm-8">
                                    <select name="lab_test" id="lab_test" class="form-control form-control-sm">
                                        <option value="%">%</option>
                                        @if(isset($tests) and count($tests) > 0)
                                        @foreach($tests as $t)
                                        <option value="{{$t->fldtestid}}">{{$t->fldtestid}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group form-row align-items-center er-input">
                                <label for="" class="col-sm-4 p-0">Subtest:</label>
                                <div class="col-sm-8">
                                    <select name="lab_sub_test" id="lab_sub_test" class="form-control form-control-sm">
                                        <option value="%">%</option>
                                        @if(isset($subtests) and count($subtests) > 0)
                                        @foreach($subtests as $st)
                                        <option value="{{$st->fldsubtest}}">{{$st->fldsubtest}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group form-row align-items-center er-input">
                                <label for="" class="col-sm-4 p-0">Method:</label>
                                <div class="col-sm-8">
                                    <select name="lab_method" id="lab_method" class="form-control form-control-sm">
                                        <option value="%">%</option>
                                        @if(isset($methods) and count($methods)>0)
                                        @foreach($methods as $m)
                                        <option value="{{$m->fldmethod}}">{{$m->fldmethod}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group form-row align-items-center er-input">
                                <label for="address" class="col-sm-4 ">Text</label>
                                <div class="col-sm-7">
                                    <input type="text" name="lab_ex_text" id="lab_ex_text" class="form-control form-control form-control-sm" />
                                </div>
                                <div class="col-sm-1">
                                    <input type="checkbox" name="enable_txtSearch" value="1" id="enable_txtSearch" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group form-row align-items-center er-input">
                                <select name="test_normal_type" id="test_normal_type" class="form-control form-control-sm">
                                    <option value="%">%</option>
                                    <option value="1">Abnormal</option>
                                    <option value="0">Normal</option>
                                </select>
                            </div>
                            <div class="form-group form-row align-items-center er-input">
                                <select name="subtest_normal_type" id="subtest_normal_type" class="form-control form-control-sm">
                                    <option value="%">%</option>
                                    <option value="1">Abnormal</option>
                                    <option value="0">Normal</option>
                                </select>
                            </div>
                            <div class="form-group form-row align-items-center er-input">
                                <button class="btn btn-primary rounded-pill" type="button" onclick="showLaboratoryResult()"><i class="fa fa-sync"></i>&nbsp;Refresh</button>
                            </div>
                            <div class="form-group form-row align-items-center er-input">
                                <button class="btn btn-warning rounded-pill" type="button" onclick="exportLaboratoryReport()"><i class="fas fa-external-link-square-alt"></i>&nbsp;&nbsp;Export</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
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
                                        <th class="tittle-th">SampID</th>
                                        <th class="tittle-th">Specimen</th>
                                        <th class="tittle-th">Date</th>
                                    </tr>
                                </thead>
                                <tbody id="diagnostic_laboratory_data"></tbody>
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
    // $('#lab_to_date').datetimepicker({

    //     changeMonth: true,
    //     changeYear: true,
    //     dateFormat: 'yy-mm-dd',
    //     yearRange: "1600:2032",

    // });
    // $('#lab_from_date').datetimepicker({

    //     changeMonth: true,
    //     changeYear: true,
    //     dateFormat: 'yy-mm-dd',
    //     yearRange: "1600:2032",

    // });
    $('#lab_from_date').nepaliDatePicker({
           npdMonth: true,
           npdYear: true,

       });
    $('#lab_to_date').nepaliDatePicker({
           npdMonth: true,
           npdYear: true,

       });
    $('#lab_section').on('change', function(){
        var section = $(this).val();
        if(section !=''){
            $.ajax({
                url: '{{ route('list.test.form.diagnostic.consultant') }}',
                type: "POST",
                data: {section:section,"_token": "{{ csrf_token() }}"},
                success: function (response) {
                    $('#lab_test').empty().html(response);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }
    });
    $('#lab_test').on('change', function(){
        var test = $(this).val();
        if(test !=''){
            $.ajax({
                url: '{{ route('list.subtests.form.diagnostic.consultant') }}',
                type: "POST",
                data: {test:test,"_token": "{{ csrf_token() }}"},
                success: function (response) {

                    $('#lab_sub_test').empty().html(response);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }
    });

    function showLaboratoryResult(){
          //  alert('here');
          $('form').submit(false);
          var fdate = $('#lab_from_date').val();
          var tdate = $('#lab_to_date').val();
          var method = $('#lab_method').val();
          var status = $('#lab_status').val();
          var specimen = $('#lab_specimen').val();
          var condition = $('#lab_condition').val();
          var section = $('#lab_section').val();
          var gender = $('#lab_gender').val();
          var fage = $('#lab_age_from').val();
          var tage = $('#lab_age_to').val();
          var test = $('#lab_test').val();
          var stest = $('#lab_sub_test').val();
          var ttype = $('#test_normal_type').val();
          var sttype = $('#subtest_normal_type').val();
          var extext = $('#lab_ex_text').val();
            //alert('sdfs');
            if($('#enable_txtSearch').is(":checked")){
                var txSearch = 1;
            }else{
                var txSearch = 0;
            }
            $.ajax({
                url: '{{ route('search.diagnostic.form.diagnostic.consultant') }}',
                type: "POST",
                data: {fdate:fdate,tdate:tdate,section:section,gender:gender,fage:fage,tage:tage,test:test,stest:stest,ttype:ttype,sttype:sttype,extext:extext,status:status,specimen:specimen,condition:condition,method:method,txSearch:txSearch,"_token": "{{ csrf_token() }}"},
                success: function (response) {
                       //alert('dfgfdg');
                       $('#diagnostic_laboratory_data').html(response);
                   },
                   error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }

        function exportLaboratoryReport(){
            // alert(baseUrl);
            $('form').submit(false);
            data = $('#laboratoryForm').serialize();
           // alert(data);
           var urlReport = baseUrl + "/consultation/export-laboratory-report?" + data + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";


           window.open(urlReport, '_blank');
       }

       $('#lab_from_date_bs').nepaliDatePicker({

        npdMonth: true,
        npdYear: true,
        npdYearCount: 100,
            // disableDaysAfter: '1',
            onChange: function () {
                var datebs = $('#lab_from_date_bs').val();
                $.ajax({
                    type: 'post',
                    url: '{{ route("patient.request.menu.nepalitoenglish") }}',
                    data: {date: datebs,},
                    success: function (response) {
                        $('#lab_from_date').val(response);
                    }
                });
            }

        });
       $('#lab_to_date_bs').nepaliDatePicker({

        npdMonth: true,
        npdYear: true,
        npdYearCount: 100,
            // disableDaysAfter: '1',
            onChange: function () {
                var datebs = $('#lab_to_date_bs').val();
                $.ajax({
                    type: 'post',
                    url: '{{ route("patient.request.menu.nepalitoenglish") }}',
                    data: {date: datebs,},
                    success: function (response) {
                        $('#lab_to_date').val(response);
                    }
                });
            }

        });
    </script>
    @endpush
