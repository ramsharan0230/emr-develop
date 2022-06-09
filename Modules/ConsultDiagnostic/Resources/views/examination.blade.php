@extends('frontend.layouts.master')
@section('content')
    {{--navbar--}} {{--@include('menu::common.nav-bar')--}} {{--end navbar--}}
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">
                                Clinical Data Master/Examination Report
                            </h4>
                        </div>
                        <button onclick="myFunction()" class="btn btn-primary"><i class="fa fa-bars"></i></button>
                    </div>
                </div>
            </div>
            <div class="col-sm-12" id="myDIV">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <form action="" id="examinationForm">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group form-row align-items-center er-input">
                                        <label for="" class="col-sm-3">Form:</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control form-control-sm" id="from_date" autocomplete="off">
                                            <input type="hidden" name="from_date" id="from_date_eng">
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center er-input">
                                        <label for="" class="col-sm-3">To:</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control form-control-sm" id="to_date" autocomplete="off">
                                            <input type="hidden" name="to_date" id="to_date_eng">
                                        </div>
                                    </div>
                                    @php
                                        $hospital_department = Helpers::getDepartmentAndComp();
                                    @endphp
                                    <div class="form-group form-row align-items-center er-input d-none">
                                        <label for="" class="col-sm-3">Comp:</label>
                                        <div class="col-sm-9">
                                            <select name="ex_comp" id="ex_comp" class="form-control form-control-sm">
                                                <option value="%">%</option>
                                                @if($hospital_department)
                                                    @forelse($hospital_department as $dept)
                                                        <option value="{{ isset($dept->fldcomp) ? $dept->fldcomp : "%" }}"> {{$dept->name}} ( {{$dept->branch_data ? $dept->branch_data->name : ""}} )</option>
                                                    @empty
                                                    @endforelse
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group form-row align-items-center er-input">
                                        <label for="" class="col-sm-4">Section:</label>
                                        <div class="col-sm-8">
                                            <select name="section" id="section" class="form-control form-control-sm">
                                                <option value="%">%</option>
                                                <option value="General Parameters">General Parameters</option>
                                                <option value="Rec Examination">Rec Examination</option>
                                                <option value="Examination">Examination</option>
                                                <option value="OPD Examination">OPD Examination</option>
                                                <option value="IP Monitoring:%">IP Monitoring:%</option>
                                                <optgroup label="---Major Procedure---">
                                                    <option value="Pre-Operative Exam:%">Pre-Operative Exam:%</option>
                                                    <option value="Operative Exam:%">Operative Exam:%</option>
                                                    <option value="Anaesthesia:%">Anaesthesia:%</option>
                                                    <option value="Post-Operative Exam:%">Post-Operative Exam:%</option>
                                                </optgroup>
                                                <optgroup label="----Delivery----">
                                                    <option value="Pre Delivery Exam">Pre Delivery Exam</option>
                                                    <option value="On Delivery Exam">On Delivery Exam</option>
                                                    <option value="Post Delivery Exam">Post Delivery Exam</option>
                                                    <option value="Baby Examination">Baby Examination</option>
                                                </optgroup>
                                                <optgroup label="--Extra Procedure---"></optgroup>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center er-input">
                                        <label for="" class="col-sm-4">Gender:</label>
                                        <div class="col-sm-8">
                                            <select name="gender" class="form-control form-control-sm" id="di_exam_gender">
                                                <option value="%">%</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center er-input">
                                        <label for="" class="col-sm-4">Age:</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="exam_age_from" class="form-control form-control-sm" id="exam_age_from" value="">
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" name="exam_age_to" class="form-control form-control-sm" id="exam_age_to" value="">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group form-row align-items-center er-input">
                                        <label for="" class="col-sm-6">Exam:</label>
                                        <div class="col-sm-6">
                                            <select name="diagnostic_exam" id="diagnostic_exam" class="form-control form-control-sm">
                                                <option value="%">%</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center er-input">
                                        <label for="" class="col-sm-6">Sub Exam:</label>
                                        <div class="col-sm-6">
                                            <select name="diagnostic_sub_exam" id="diagnostic_sub_exam" class="form-control form-control-sm">
                                                <!-- <option value="%">%</option> -->
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center er-input">
                                        <label for="" class="col-sm-6">Test:</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="ex_text" id="ex_text" class="form-control form-control-sm">
                                        </div>
                                        <div class="col-sm-2">
                                            <input type="checkbox" name="enable_txtSearch" value="1" id="enable_txtSearch">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3 p-0">
                                    <div class="form-group form-row align-items-center er-input">
                                        <select name="normal_type" id="normal_type" class="form-control col-sm-11 form-control-sm">
                                            <option value="%">%</option>
                                            <option value="1">Abnormal</option>
                                            <option value="0">Normal</option>
                                        </select>
                                    </div>
                                    <a href="#" class="btn btn-primary  rounded-pill" type="button" onclick="showExaminationResult()"> <i class="fa fa-search"></i>&nbsp;Search</a>
                                    <a href="#" class="btn btn-warning rounded-pill" type="button" onclick="exportExmainationReport()"><i class="fas fa-external-link-square-alt"></i>&nbsp;Export</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="iq-card">
                    <div class="iq-card-body">
                        <div class="tab-content" id="myTabContent-2">
                            <div class="tab-pane fade active show" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <div class="table-responsive table-container">
                                    <table class="table table-striped table-hover table-bordered ">
                                        <thead class="thead-light">
                                        <tr>
                                            <th>Index</th>
                                            <th>EncID</th>
                                            <th width="300">Name</th>
                                            <th>Age</th>
                                            <th>Gender</th>
                                            <th>PatientNo</th>
                                            <th>DateTime</th>
                                            <th>Location</th>
                                            <th>Observation</th>
                                            <th>Flag</th>
                                        </tr>
                                        </thead>
                                        <tbody id="diagnostic_examination_data"></tbody>
                                    </table>
                                    <div id="bottom_anchor"></div>
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
    <script type="text/javascript">
        $(window).ready(function () {
            $('#to_date').val(AD2BS('{{date('Y-m-d')}}'));
            $('#from_date').val(AD2BS('{{date('Y-m-d')}}'));
        })
        $('#to_date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 10 // Options | Number of years to show
        });
        $('#from_date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 10 // Options | Number of years to show
        });

        $('#section').on('change', function () {
            var section = $(this).val();
            if (section != '') {
                $.ajax({
                    url: '{{ route('list.exams.form.diagnostic.consultant') }}',
                    type: "POST",
                    data: {section: section, "_token": "{{ csrf_token() }}"},
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
        $('#diagnostic_exam').on('change', function () {
            var exam = $(this).val();
            if (exam != '') {
                $.ajax({
                    url: '{{ route('list.subexams.form.diagnostic.consultant') }}',
                    type: "POST",
                    data: {exam: exam, "_token": "{{ csrf_token() }}"},
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

        function showExaminationResult() {
            // alert('here');
            $('#to_date_eng').val(BS2AD($('#to_date').val()));
            $('#from_date_eng').val(BS2AD($('#from_date').val()));
            var fdate = $('#from_date_eng').val();
            var tdate = $('#to_date_eng').val();
            var comp = $('#ex_comp').val();
            var section = $('#section').val();
            var gender = $('#di_exam_gender').val();
            var fage = $('#exam_age_from').val();
            var tage = $('#exam_age_to').val();
            var exam = $('#diagnostic_exam').val();
            var sexam = $('#diagnostic_sub_exam').val();
            var ntype = $('#normal_type').val();
            var extext = $('#ex_text').val();
            if ($('#enable_txtSearch').is(":checked")) {
                var txSearch = 1;
            } else {
                var txSearch = 0;
            }
            // alert(txSearch);
            $.ajax({
                url: '{{ route('search.examination.form.diagnostic.consultant') }}',
                type: "POST",
                data: {fdate: fdate, tdate: tdate, comp: comp, section: section, gender: gender, fage: fage, tage: tage, exam: exam, sexam: sexam, ntype: ntype, extext: extext, txtsearch: txSearch, "_token": "{{ csrf_token() }}"},
                success: function (response) {
                    $('#diagnostic_examination_data').empty().append(response);

                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }

        function exportExmainationReport() {
            // alert(baseUrl);
            $('form').submit(false);
            data = $('#examinationForm').serialize();
            // alert(data);
            var urlReport = baseUrl + "/consultation/export-exam-report?" + data + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";


            window.open(urlReport, '_blank');
        }
    </script>
@endpush
