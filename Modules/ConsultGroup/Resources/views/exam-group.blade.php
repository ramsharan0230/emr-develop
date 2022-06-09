@extends('frontend.layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Clinical Data Master / Exam Group</h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <form action="javascript:;" id="exam-group-form">
                            <div class="row">
                                <div class="col-lg-8 col-md-12">
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-2">Group Name</label>
                                        <div class="col-sm-4">
                                            <select name="group_dropdown" class="form-control group_dropdown">
                                                <option value="">---select---</option>
                                                @if(count($groupNameList))
                                                    @foreach($groupNameList as $group)
                                                        <option value="{{ $group->fldgroupname }}">{{ $group->fldgroupname }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-sm-5">
                                            <input type="text" name="group_name" id="group_name" class="form-control" placeholder="Create Group Name">
                                        </div>
                                        <div class="col-sm-1">
                                            <a href="javascript:;" class="btn btn-primary" onclick="ExamGroup.listExamGroup()"><i class="ri-refresh-line"></i></a>
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label class="col-sm-2">Examination</label>
                                        <div class="col-sm-6">
                                            <select name="exam_name" id="exam_name" class="form-control exam_name">
                                                <option value="0">---select---</option>
                                                @if(count($examinationList))
                                                    @foreach($examinationList as $listGroup)
                                                        <option value="{{ $listGroup->fldexamid }}">{{ $listGroup->fldexamid }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-sm-4">
                                            <button class="btn btn-primary" onclick="ExamGroup.userAddComponents()"><i class="ri-add-line"></i> Add</button>&nbsp;

                                            <button class="btn btn-warning" onclick="ExamGroup.exportExamGroup()"><i class="ri-code-s-slash-line"></i> Export</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="table-responsive table-container">
                            <table class="table table-bordered table-striped table-hover ">
                                <thead class="thead-light">
                                <tr>
                                    <th>&nbsp;</th>
                                    <th>Group Name</th>
                                    <th>Examination</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody class="list-group-exam-user-disabled">
                                @if(count($groupNameList))
                                    @foreach($groupNameList as $listName)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $listName->fldgroupname }}</td>
                                            <td>{{ $listName->fldexamid }}</td>
                                            <td><a href="javascript:;" onclick="ExamGroup.deleteExamGroup('{{ $listName->fldid }}')"><i class="ri-delete-bin-5-fill text-danger"></i></a></td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                            <div id="bottom_anchor"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@push('after-script')
    <script>
        var ExamGroup = {
            listExamGroup: function () {
                if ($('.group_dropdown').val() === "") {
                    alert('Select group name!')
                    return false;
                }

                $.ajax({
                    url: "{{ route('consultant.exam.group.list') }}",
                    type: "post",
                    data: $('#exam-group-form').serialize(),
                    success: function (data) {
                        // console.log(data);
                        $('.list-group-exam-user-disabled').empty();
                        $('.list-group-exam-user-disabled').append(data);
                    },
                    error: function (xhr, err) {
                        console.log(xhr);
                    }
                });
            },
            userAddComponents: function () {
                //alert('dfsdf');
                if (($('.group_name').val() === "" || $('#group_dropdown').val() === "") && $('.exam_name').val() === "") {
                    alert('Select group and exam name!');
                    return false;
                }

                $.ajax({
                    url: "{{ route('consultant.exam.group.add') }}",
                    type: "post",
                    data: $('#exam-group-form').serialize(),
                    success: function (data) {
                        console.log(data);
                        $('.list-group-exam-user-disabled').empty();
                        $('.list-group-exam-user-disabled').append(data);
                    },
                    error: function (xhr, err) {
                        console.log(xhr);
                    }
                });
            },
            deleteExamGroup: function (fldid) {
                var r = confirm("Delete?");
                if (r !== true) {
                    return false;
                }
                $.ajax({
                    url: "{{ route('consultant.exam.group.delete') }}",
                    type: "post",
                    data: {fldid: fldid, "_token": "{{ csrf_token() }}"},
                    success: function (data) {
                        // console.log(data);
                        $('.list-group-exam-user-disabled').empty();
                        $('.list-group-exam-user-disabled').append(data);
                    },
                    error: function (xhr, err) {
                        console.log(xhr);
                    }
                });
            },
            exportExamGroup: function () {
                var urlReport = "{{ route('consultant.exam.group.export') }}" + "?group_name=" + $('#group_dropdown').val() + "&_token=" + "{{ csrf_token() }}";
                window.open(urlReport, '_blank');
            }
        }

        $(document).ready(function () {
            $('#group_dropdown').change(function () {

                if ($('#group_dropdown').val() !== "") {
                    $('#group_name').val('');
                    $('#group_name').prop("disabled", true);
                } else {
                    $('#group_name').prop("disabled", false);
                }
            })
        })
    </script>
@endpush
