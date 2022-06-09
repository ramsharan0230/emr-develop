<div class="form-group row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="name" class="col-sm-2 col-form-label col-form-label-sm">Target:</label>
            <div class="col-md-10">
                <select name="target" class="form-control form-control-sm" id="target">
                    <option value="%">%</option>
                    <option value="comp01">comp01</option>
                </select>
            </div>


        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <input type="text" name="" readonly="" value="Empty" class="form-control form-control-sm"
                   style="float: right;">
        </div>

    </div>
    <div class="col-md-4">
        <button class="default-btn"><i class="fas fa-external-link-square-alt"></i><a href="javascript:void(0);"
                                                                                      onclick="">&nbsp;&nbsp;Export</a>
        </button>
    </div>
</div>

<div class="profile-form" style="margin-top: 10px;">
    <div class="modal-nav">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a class="nav-item nav-link active" id="complaints-tab" data-toggle="tab" href="#complaints" role="tab" aria-controls="complaints" aria-selected="true">Complaints</a>

                <a class="nav-item nav-link" id="exam-tab" data-toggle="tab" href="#exam" role="tab" aria-controls="exam" aria-selected="false">Examination</a>

            </div>
        </nav>

        <div class="tab-content" id="nav-tabContent" >
            <div class="tab-pane fade show active" id="complaints" role="tabpanel" aria-labelledby="complaints-tab">
                <div class="col-md-12 mt-2">
                    <div class="row" style="margin-top: 10px;">
                        <div class="col-md-2">
                            <button  class="default-btn" onclick="listAllComplaints()"><i class="fas fa-sync"></i><a href="javascript:void(0);" ></a>&nbsp;&nbsp;Refresh </button>
                        </div>
                        <div class="col-md-2">
                            <button class="default-btn f-btn-icon-b" onclick="complaints.listComplaints()"><i class="fa fa-plus"></i><a href="javascript:void(0);"></a>&nbsp;Add</button>
                        </div>
                    </div>
                    <div class="table-responsive table_height table-scroll-consultmodal mt-2">
                            <table class="table table-sm table-bordered" style="margin-bottom: 10px">
                                <thead>
                                <tr>
                                    <th class="tittle-th">Index</th>
                                    <th class="tittle-th">Type</th>
                                    <th class="tittle-th" width="300">Symptom</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody id="complaint_list" >

                                </tbody>
                            </table>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="exam" role="tabpanel" aria-labelledby="exam-tab">
                <div class="row mt-2">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-2">
                                <label for="category" class="col-form-label col-form-label-sm">Category</label>
                            </div>
                            <div class="col-md-4">
                                <select name="examination_category" class="col-form-label col-form-label-sm" id="examination_category">
                                    <option value="Triage Examinations">Triage Examinations</option>
                                    <option value="Essential Examinations">Essential Examinations</option>
                                    <option value="Physician Examinations">Physician Examinations</option>
                                    <option value="Nursing Examinations">Nursing Examinations</option>
                                    <option value="Discharge Examinations">Discharge Examinations</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button  class="default-btn" onclick="listExaminationByCategory()"><i class="fas fa-sync"></i><a href="javascript:void(0);"></a>&nbsp;&nbsp;Refresh</button>
                            </div>
                            <div class="2"></div>
                            <div class="col-md-3">
                                <button class="default-btn fa-btn-icon-b" onclick="compexam.addExamination()"><i class="fa fa-plus"></i><a href="javascript:void(0);"></a>&nbsp;Add</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-2">
                        <div class="table-responsive table_height table-scroll-consultmodal">
                            <table class="table table-sm table-bordered" style="margin-bottom: 10px">
                                <thead>
                                <tr>
                                    <th class="tittle-th">Index</th>
                                    <th class="tittle-th">Type</th>
                                    <th class="tittle-th"width="300">Exam Name</th>

                                </tr>
                                </thead>
                                <tbody id="examination_list" >

                                </tbody>
                            </table>
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
