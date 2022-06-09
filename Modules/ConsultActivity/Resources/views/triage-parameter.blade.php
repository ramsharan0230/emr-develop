@extends('frontend.layouts.master')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">Clinical Data Master / Triage Parameter</h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div class="form-group form-row align-items-center">
                        {{--<label for="tp-target" class="col-sm-1">Target</label>
                        <div class="col-sm-4">
                            <select name="tp_comp" class="form-control" onchange="triageParameter.getComputername(this)">
                                <option value=""></option>
                                @if($compNames = Helpers::getAllCompName())
                                @foreach($compNames as $comp)
                                <option value="{{ $comp }}">{{ $comp }}</option>
                                @endforeach
                                @endif

                            </select>
                        </div>--}}
                        <div class="col-sm-4">
                            <input type="text" name="tp_comp_name" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="tab-1">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="complaint-tab" data-toggle="tab" href="#complaint" role="tab" aria-controls="complaint" aria-selected="true">Complaint</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="examination-tab" data-toggle="tab" href="#examination" role="tab" aria-controls="examination" aria-selected="false">Examination</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="laboratory-tab" data-toggle="tab" href="#laboratory" role="tab" aria-controls="laboratory" aria-selected="false">Laboratory</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="complaint" role="tabpanel" aria-labelledby="complaint-tab">
                                <form class="mt-2" id="triage-parameter-form">
                                    @csrf
                                    <div class="form-group form-row align-items-center mt-2">

                                        <label for="tp-target" class="col-sm-1">Color</label>
                                        <div class="col-sm-5">
                                            <select name="tp_color_code" class="tp-complaint-color-code form-control">
                                                <option value=""></option>
                                                <option value="Red">Red</option>
                                                <option value="Yellow">Yellow</option>
                                                <option value="Green">Green</option>
                                                <option value="Blue">Blue</option>
                                                <option value="Black">Black</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-1">
                                            <a href="javascript:;" class="btn btn-primary" onclick="triageComplaints.getListTriageComplaints()"><i class="ri-refresh-line"></i></a>
                                        </div>
                                        <div class="col-sm-2">
                                            <button class="btn btn-primary" type="button" onclick="triageComplaints.addComplaints()"><i class="ri-add-line"></i> Add</button>
                                        </div>
                                    </div>
                                </form>
                                <div class="table-responsive table-container">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Variable</th>
                                                <th></th>
                                                <th>Value</th>
                                                <th>Unit</th>
                                                <th>BaseRate</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody class="triage-param-complaint-list-by-color">
                                        </tbody>
                                    </table>
                                    <div id="bottom_anchor"></div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="examination" role="tabpanel" aria-labelledby="examination-tab">
                                <div class="container-fluid btm">
                                    <form class="mt-2" id="triage-parameter-form-examination">
                                        @csrf
                                        <div class="form-group form-row align-items-center mt-2">

                                            <label for="tp-target" class="col-sm-1">Color</label>
                                            <div class="col-sm-5">
                                                <select name="tp_color_code" class="tp-exam-color-code form-control">
                                                    <option value=""></option>
                                                    <option value="Red">Red</option>
                                                    <option value="Yellow">Yellow</option>
                                                    <option value="Green">Green</option>
                                                    <option value="Blue">Blue</option>
                                                    <option value="Black">Black</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-1">
                                                <a href="javascript:;" class="btn-primary btn" onclick="triageExam.getListTriageExam()"><i class="ri-refresh-line"></i></a>
                                            </div>
                                            <div class="col-sm-2">
                                                <button class="btn btn-primary" type="button" onclick="triageExam.addExam()"><i class="ri-add-line"></i> Add</button>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="table-responsive table-container">
                                        <table class="table table-striped table-bordered table-hover">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Variable</th>
                                                    <th></th>
                                                    <th>Value</th>
                                                    <th>Unit</th>
                                                    <th>BaseRate</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody class="triage-param-exam-list-by-color">
                                            </tbody>
                                        </table>
                                        <div id="bottom_anchor"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="laboratory" role="tabpanel" aria-labelledby="laboratory-tab">
                                <div class="container-fluid btm">
                                    <form class="mt-2" id="triage-parameter-form-laboratory">
                                        @csrf
                                        <div class="form-group form-row align-items-center mt-2">

                                            <label for="tp-target" class="col-sm-1">Color</label>
                                            <div class="col-sm-5">
                                                <select name="tp_color_code" class="tp-lab-color-code form-control">
                                                    <option value=""></option>
                                                    <option value="Red">Red</option>
                                                    <option value="Yellow">Yellow</option>
                                                    <option value="Green">Green</option>
                                                    <option value="Blue">Blue</option>
                                                    <option value="Black">Black</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-1">
                                                <a href="javascript:;" class="btn-primary btn" onclick="triagelab.getListTriagelab()"><i class="ri-refresh-line"></i></a>
                                            </div>
                                            <div class="col-sm-2">
                                                <button class="btn btn-primary" type="button" onclick="triagelab.addlab()"><i class="ri-add-line"></i> Add</button>
                                            </div>
                                        </div>
                                    </form>

                                    <div class="table-responsive table-container">
                                        <table class="table table-striped table-bordered table-hover">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Variable</th>
                                                    <th></th>
                                                    <th>Value</th>
                                                    <th>Unit</th>
                                                    <th>BaseRate</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody class="triage-param-lab-list-by-color">
                                            </tbody>
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
    </div>
</div>

<div class="modal fade" id="triage-modal">
    <div class="modal-dialog modal-lg" id="size">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="triage-modal-title">CogentEMR</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="triage-form-container">
                    <div class="triage-form-data"></div>
                </div>

            </div>
            <i class="glyphicon glyphicon-chevron-left"></i>

        </div>
    </div>
</div>

@stop

@push('after-script')
<script>
    var triageParameter = {

        getComputername: function () {
            $('.tp_comp_name').val($('.tp-comp').val());
                /*$.ajax({
                    url: "{{ route('consultant.triage.parameter.comp.detail') }}",
                    type: "POST",
                    data: {"_token": "{{ csrf_token() }}", tp_comp: $('.tp-comp').val()},
                    success: function (data) {
                        // console.log(data);
                        $('.tp_comp_name').val(tp-comp);
                    },
                    error: function (xhr, err) {
                        console.log(xhr);
                    }
                });*/
            },

        }

        var triageComplaints = {
            getListTriageComplaints: function () {
                if ($('.tp-complaint-color-code').val() === "") {
                    alert('Select color')
                    return false;
                }
                var triage_type = 'Symptom';
                var tp_color_code = $('.tp-complaint-color-code').val();
                // var tp_comp = $('.tp-comp').val();

                $.ajax({
                    url: "{{ route('consultant.triage.parameter.complaints.list') }}",
                    type: "POST",
                    data: {triage_type: triage_type, tp_color_code: tp_color_code, "_token": "{{ csrf_token() }}"},
                    success: function (data) {
                        // console.log(data);
                        $('.triage-param-complaint-list-by-color').empty();
                        $('.triage-param-complaint-list-by-color').append(data);
                    },
                    error: function (xhr, err) {
                        console.log(xhr);
                    }
                });
            },
            addComplaints: function () {
                if ($('.tp-complaint-color-code').val() === "") {
                    alert('Select color')
                    return false;
                }
                $.ajax({
                    url: "{{ route('consultant.triage.parameter.complaints.add') }}",
                    type: "get",
                    data: $('#triage-parameter-form').serialize(),
                    success: function (data) {
                        // console.log(data);
                        $('.triage-form-data').html(data);
                        $('#triage-modal').modal('show');
                    },
                    error: function (xhr, err) {
                        console.log(xhr);
                    }
                });

            },
            sishComplaintsAdd: function () {
                $.ajax({
                    url: "{{ route('consultant.triage.parameter.complaints.sish.add') }}",
                    type: "post",
                    data: $('#complaints-sish').serialize(),
                    success: function (data) {
                        showAlert("Complaints added successfully.");
                        $('.triage-param-complaint-list-by-color').empty();
                        $('.triage-param-complaint-list-by-color').append(data);
                        $('#complaints-sish')[0].reset();
                        // console.log(data);
                        $('#triage-modal').modal('hide');
                    },
                    error: function (xhr, err) {
                        console.log(xhr);
                    }
                });

            },
            deleteComplaints: function (flid) {
                var r = confirm("Delete?");
                if (r !== true) {
                    return false;
                }
                var triage_type = 'Symptom';
                var tp_color_code = $('.tp-complaint-color-code').val();
                // var tp_comp = $('.tp-comp').val();

                $.ajax({
                    url: "{{ route('consultant.triage.parameter.complaint.delete') }}",
                    type: "POST",
                    data: {triage_type: triage_type, tp_color_code: tp_color_code, "_token": "{{ csrf_token() }}", flid: flid},
                    success: function (data) {
                        console.log(data);
                        $('.triage-param-complaint-list-by-color').empty();
                        $('.triage-param-complaint-list-by-color').append(data);
                    },
                    error: function (xhr, err) {
                        console.log(xhr);
                    }
                });
            }
        }

        var triageExam = {
            getListTriageExam: function () {
                if ($('.tp-comp').val() === "") {
                    alert('Select Comp')
                    return false;
                }
                var triage_type = 'Exam';
                var tp_color_code = $('.tp-exam-color-code').val();
                // var tp_comp = $('.tp-comp').val();

                $.ajax({
                    url: "{{ route('consultant.triage.parameter.exam.list') }}",
                    type: "POST",
                    data: {triage_type: triage_type, tp_color_code: tp_color_code, "_token": "{{ csrf_token() }}"},
                    success: function (data) {
                        console.log(data);
                        $('.triage-param-exam-list-by-color').empty();
                        $('.triage-param-exam-list-by-color').html(data);
                    },
                    error: function (xhr, err) {
                        console.log(xhr);
                    }
                });
            },
            addExam: function () {
                if ($('.tp-exam-color-code').val() === "") {
                    alert('Select color')
                    return false;
                }
                $.ajax({
                    url: "{{ route('consultant.triage.parameter.exams.add') }}",
                    type: "get",
                    data: $('#triage-parameter-form-examination').serialize(),
                    success: function (data) {
                        // console.log(data);
                        $('.triage-form-data').html(data);
                        $('#triage-modal').modal('show');
                    },
                    error: function (xhr, err) {
                        console.log(xhr);
                    }
                });

            },
            sishExamAdd: function () {
                // console.log($('#exam-sish-form').serialize());
                $.ajax({
                    url: "{{ route('consultant.triage.parameter.exams.sish.add') }}",
                    type: "post",
                    data: $('#exam-sish-form').serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    success: function (data) {
                        // console.log(data);
                        showAlert(data.message);
                        $('.triage-param-exam-list-by-color').empty();
                        $('.triage-param-exam-list-by-color').append(data);
                        $('#exam-sish-form')[0].reset();
                        $('#triage-modal').modal('hide');
                    },
                    error: function (xhr, err) {
                        console.log(xhr);
                    }
                });
            },
            deleteexam: function (flid) {
                var r = confirm("Delete?");
                if (r !== true) {
                    return false;
                }
                var triage_type = 'Exam';
                var tp_color_code = $('.tp-exam-color-code').val();
                // var tp_comp = $('.tp-comp').val();

                $.ajax({
                    url: "{{ route('consultant.triage.parameter.exam.delete') }}",
                    type: "POST",
                    data: {triage_type: triage_type, tp_color_code: tp_color_code, "_token": "{{ csrf_token() }}", flid: flid},
                    success: function (data) {
                        // console.log(data);
                        // alert('asdf')
                        $('.triage-param-exam-list-by-color').empty();
                        $('.triage-param-exam-list-by-color').append(data);
                    },
                    error: function (xhr, err) {
                        console.log(xhr);
                    }
                });
            }

        }

        var triagelab = {
            getListTriagelab: function () {
                if ($('.tp-lab-color-code').val() === "") {
                    alert('Select Color')
                    return false;
                }
                var triage_type = 'Test';
                var tp_color_code = $('.tp-lab-color-code').val();
                // var tp_comp = $('.tp-comp').val();

                $.ajax({
                    url: "{{ route('consultant.triage.parameter.lab.list') }}",
                    type: "POST",
                    data: {triage_type: triage_type, tp_color_code: tp_color_code, "_token": "{{ csrf_token() }}"},
                    success: function (data) {
                        console.log(data);
                        $('.triage-param-lab-list-by-color').empty();
                        $('.triage-param-lab-list-by-color').html(data);
                    },
                    error: function (xhr, err) {
                        console.log(xhr);
                    }
                });
            },
            addlab: function () {
                if ($('.tp-lab-color-code').val() === "") {
                    alert('Select color')
                    return false;
                }
                $.ajax({
                    url: "{{ route('consultant.triage.parameter.labs.add') }}",
                    type: "get",
                    data: $('#triage-parameter-form-laboratory').serialize(),
                    success: function (data) {
                        // console.log(data);
                        $('.triage-form-data').html(data);
                        $('#triage-modal').modal('show');
                    },
                    error: function (xhr, err) {
                        console.log(xhr);
                    }
                });

            },
            sishlabAdd: function () {
                $.ajax({
                    url: "{{ route('consultant.triage.parameter.labs.sish.add') }}",
                    type: "post",
                    data: $('#lab-sish-form').serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    success: function (data) {
                        // console.log(data);
                        $('.triage-param-lab-list-by-color').empty();
                        $('.triage-param-lab-list-by-color').html(data);
                        $('#lab-sish-form')[0].reset();
                        showAlert("Lab added successfully.");
                        $('#triage-modal').modal('hide');
                    },
                    error: function (xhr, err) {
                        console.log(xhr);
                    }
                });
            },
            deletelab: function (flid) {
                var r = confirm("Delete?");
                if (r !== true) {
                    return false;
                }
                var triage_type = 'Test';
                var tp_color_code = $('.tp-lab-color-code').val();
                // var tp_comp = $('.tp-comp').val();

                $.ajax({
                    url: "{{ route('consultant.triage.parameter.lab.delete') }}",
                    type: "POST",
                    data: {triage_type: triage_type, tp_color_code: tp_color_code, "_token": "{{ csrf_token() }}", flid: flid},
                    success: function (data) {
                        // console.log(data);
                        $('.triage-param-lab-list-by-color').empty();
                        $('.triage-param-lab-list-by-color').append(data);
                    },
                    error: function (xhr, err) {
                        console.log(xhr);
                    }
                });
            }

        }
    </script>
    @endpush
