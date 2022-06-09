@extends('frontend.layouts.master')
@push('after-styles')

@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">Comp Exam List</h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    @php
                        $hospital_department = Helpers::getDepartmentAndComp();
                    @endphp
                    <div class="form-group form-row align-items-center">
                        <label class="col-sm-1">Target</label>
                        <div class="col-sm-4">
                            <select name="target" class="form-control" id="target">
                                <option value="%">%</option>
                                @if($hospital_department)
                                    @forelse($hospital_department as $dept)
                                        <option value="{{ isset($dept->fldcomp) ? $dept->fldcomp : "%" }}"> {{$dept->name}} ( {{$dept->branch_data ? $dept->branch_data->name : ""}} )</option>
                                    @empty
                                    @endforelse
                                @endif
                            </select>
                        </div>
                        <div class="col-sm-5">
                            <input type="text" name="" readonly="" value="Empty" class="form-control">
                        </div>
                        <div class="col-sm-2">
                            <button class="btn btn-primary">
                                <a href="javascript:void(0);" onclick=""><i class="ri-external-link-line"></i> Export</a>
                            </button>
                        </div>
                    </div>
                    <div class="form-comp mt-2">
                        <div class="modal-nav">
                            <nav>
                                <ul class="nav nav-tabs" id="nav-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="complaints-tab" data-toggle="tab" href="#complaints" role="tab" aria-controls="complaints" aria-selected="true">Complaints</a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link" id="exam-tab" data-toggle="tab" href="#exam" role="tab" aria-controls="exam" aria-selected="false">Examination</a>
                                    </li>

                                </ul>
                            </nav>

                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="complaints" role="tabpanel" aria-labelledby="complaints-tab">
                                    <div class="d-flex mt-2">
                                        <button  class="btn btn-warning mr-2" onclick="listAllComplaints()"><a href="javascript:void(0);" ></a><i class="ri-refresh-line"></i> Refresh </button>
                                        <button class="btn btn-primary" onclick="complaints.listComplaints()"><a href="javascript:void(0);"></a><i class="ri-add-line"></i> Add</button>
                                    </div>
                                    <div class="table-responsive table-container mt-2">
                                        <table class="table table-striped table-bordered table-hover">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Index</th>
                                                    <th>Type</th>
                                                    <th>Symptom</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody id="complaint_list" >

                                            </tbody>
                                        </table>
                                        <div id="bottom_anchor"></div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="exam" role="tabpanel" aria-labelledby="exam-tab">
                                    <div class="form-group form-row align-items-center">
                                        <label for="category" class="col-lg-1 col-md-2">Category:</label>
                                        <div class="col-lg-7 col-md-6">
                                            <select name="examination_category" class="form-control" id="examination_category">
                                                <option value="Triage Examinations">Triage Examinations</option>
                                                <option value="Essential Examinations">Essential Examinations</option>
                                                <option value="Physician Examinations">Physician Examinations</option>
                                                <option value="Nursing Examinations">Nursing Examinations</option>
                                                <option value="Discharge Examinations">Discharge Examinations</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <button  class="btn btn-warning" onclick="listExaminationByCategory()"><a href="javascript:void(0);"></a><i class="ri-refresh-line"></i> Refresh</button>
                                            <button class="btn btn-primary" onclick="compexam.addExamination()"><a href="javascript:void(0);"></a><i class="ri-add-line"></i> Add</button>
                                        </div>
                                    </div>
                                    <div class="table-responsive table-container mt-2">
                                        <table class="table table-striped table-bordered table-hover">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Index</th>
                                                    <th>Type</th>
                                                    <th>Exam Name</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="examination_list" >

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

<script type="text/javascript">
    function listAllComplaints(){
            // alert('ajax');
            $.ajax({
                url: '{{ route('list.complaints.form.activity.consultant') }}',
                type: "POST",
                data: {comp:$('#target').val(),"_token": "{{ csrf_token() }}"},
                success: function (response) {
                    $('#complaint_list').html(response);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }

        function listExaminationByCategory(){
            // alert('ajax');
            var com = $('#target').val();
            var cat = $('#examination_category').val()
            $.ajax({
                url: '{{ route('list.examination.form.activity.consultant') }}',
                type: "POST",
                data: {comp:com,category:cat,"_token": "{{ csrf_token() }}"},
                success: function (response) {
                    $('#examination_list').html(response);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }

        // deleteCompExam()
        function deleteCompExam(val){

            if(val !=''){
                $.ajax({
                    url: '{{ route('delete.complaints.activity.consultant') }}',
                    type: "POST",
                    data: {val:val,comp:$('#target').val(),"_token": "{{ csrf_token() }}"},
                    success: function (response) {
                        $('#complaint_list').empty().html(response);
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });
            }else{
                return false;
            }
        }

        // deleteCompExam()
        function deleteExam(val){

            if(val !=''){
                $.ajax({
                    url: '{{ route('delete.exam.activity.consultant') }}',
                    type: "POST",
                    data: {val:val,comp:$('#target').val(),cat:$('#examination_category').val(),"_token": "{{ csrf_token() }}"},
                    success: function (response) {
                        $('#examination_list').empty().html(response);
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });
            }else{
                return false;
            }
        }

    </script>

    @stop
