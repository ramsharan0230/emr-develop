@extends('frontend.layouts.master')
@push('after-styles')

@endpush

@section('content')
<div class="container-fluid">
    @include('menu::toggleButton')
    <div class="row">
      <div class="col-sm-12">
        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-header d-flex justify-content-between">
                <div class="iq-header-title">
                    <h4 class="card-title">Department Examination</h4>
                </div>
                <button onclick="myFunction()" class="btn btn-primary"><i class="fa fa-bars"></i></button>
            </div>
        </div>
    </div>
    <div class="col-sm-12" id="myDIV">
        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-body">
                <div class="row">
                    <div class="col-lg-6 col-md-12">
                        <div class="form-group form-row align-items-center">
                            <label class="col-sm-2">Department</label>
                            <div class="col-sm-6">
                                <select name="proc_department" id="proc_department" class="form-control" id="proc_department">
                                    <option value="">---select---</option>
                                    <option value="Pre Delivery">Pre Devliery</option>
                                    <option value="On Delivery">On Delivery</option>
                                    <option value="Post Delivery">Post Delivery</option>
                                    <option value="Baby Examination">Baby Examination</option>
                                    <option value="Pre-Operative">Pre-Operative</option>
                                    <option value="Operative">Operative</option>
                                    <option value="Post-Operative">Post-Operative</option>
                                    <option value="Anaesthesia">Anaesthesia</option>
                                    @if(isset($department) and count($department) > 0)
                                    @foreach($department as $d)
                                    <option value="{{$d->fldprocname}}">{{$d->fldprocname}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-sm-1">
                                <button class="btn btn-primary" onclick="deptexam.addProcModal()"><i class="ri-add-box-fill"></i></button>
                            </div>
                            <div class="col-sm-1">
                                <button class="btn btn-primary" onclick="listExamByProcName()"><i class="ri-refresh-line"></i></button>
                            </div>
                            <div class="col-sm-2">
                                <button class="btn btn-warning" onclick="deptExamPdf()">Export</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <div class="form-group form-row align-items-center">
                            <label class="col-sm-3">Exam label</label>
                            <div class="col-sm-9">
                                <input type="text" name="exam_label" id="exam_label" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <div class="form-group form-row align-items-center">
                            <label class="col-lg-4 col-sm-5">Data Type</label>
                            <div class="col-lg-8 col-sm-7">
                                <select name="data_type" id="data_type" class="form-control">
                                    <option value="">---select---</option>
                                    <option value="Qualitative">Qualitative</option>
                                    <option value="Quantitative">Quantitative</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <div class="form-group form-row align-items-center">
                            <label class="col-lg-4 col-sm-5">Option Type</label>                                                      <div class="col-lg-8 col-sm-7">
                                <select name="option" id="option" class="form-control">
                                    <option value="">---select---</option>
                                    <option value="No Selection">No Selection</option>
                                    <option value="Single Selection">Single Selection</option>
                                    <option value="Dichotomous">Dichotomous</option>
                                    <option value="Multiple Selection">Multiple Selection</option>
                                    <option value="Left and Right">Left and Right</option>
                                    <option value="Date and Time">Date and Time</option>
                                    <option value="Text Table">Text Table</option>
                                    <option value="Qualitative">Qualitative</option>
                                    <option value="SysConst">SysConst</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4">
                     <div class="form-group form-row align-items-center">
                        <label class="col-lg-4 col-sm-5">Sys Const</label>
                        <div class="col-lg-8 col-sm-7">
                            <select name="sys_const" id="sys_const" class="form-control">
                                <option value="">---select---</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 ">
                    <div class="d-flex justify-content-center">
                        <button class="btn btn-primary mr-2" onclick="addDepExamination()"><i class="ri-add-line"></i> Add</button>
                        <button class="btn btn-primary mr-2" onclick="deptexam.addOption()"><i class="ri-grid-fill"></i> Option</button>
                        <button class="btn btn-primary" onclick="editExamination()"><i class="ri-edit-2-fill"></i> Edit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="col-sm-12">
    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-body">
            <div class="table-responsive table-container mt-3">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>&nbsp;</th>
                            <th>Variable</th>
                            <th>Examination</th>
                            <th>SysConstant</th>
                            <th>SysConstant</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody id="dept_exam_list">

                    </tbody>
                </table>
                <div id="bottom_anchor"></div>
            </div>
        </div>
    </div>
</div>
</div>
</div>

<script type="text/javascript">
  $(document).on('click', 'input[name="procname_variable"]', function () {
    $('input[name="procname_variable"]').bind('click', function () {
        $('input[name="procname_variable"]').not(this).prop("checked", false);
    });
            // alert($(this).val());
            var dataid = $(this).val();
            $.ajax({
                url: '{{ route('extract.deptexam.activity.consultant') }}',
                type: "POST",
                dataType: "json",
                data: {id:dataid,"_token": "{{ csrf_token() }}"},
                success: function (response) {
                    console.log(response.flddept);
                    $('#proc_department option[value="'+response.result.flddept+'"]').prop('selected', true);
                    $('#data_type option[value="'+response.result.fldtype+'"]').prop('selected', true);
                    $('#option option[value="'+response.result.fldtanswertype+'"]').prop('selected', true);
                    $('#exam_label').val(response.result.fldexamid);
                    $('#sys_const').html(response.html);
                    // $()
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        });


  $('#data_type').on('change', function(){
    var type = $(this).val();
    $.ajax({
        url: '{{ route('list.sysconst.deptexam.activity.consultant') }}',
        type: "POST",
        data: {type:type,"_token": "{{ csrf_token() }}"},
        success: function (response) {
            $('#sys_const').append().html(response);
        },
        error: function (xhr, status, error) {
            var errorMessage = xhr.status + ': ' + xhr.statusText;
            console.log(xhr);
        }
    });
})

  function addDepExamination(){
    var dept = $('#proc_department').val();
    var label = $('#exam_label').val();
    var data_type = $('#data_type').val();
    var syscon = $('#sys_const').val();
    var option = $('#option').val();
    if(dept != '' && label !='' && data_type !=''  && option !=''){
        $.ajax({
            url: '{{ route('add.deptexam.activity.consultant') }}',
            type: "POST",
            data: {dept:dept,label:label,data_type:data_type,syscon:syscon,option:option,"_token": "{{ csrf_token() }}"},
            success: function (response) {
                $('#dept_exam_list').html(response);
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    }else{
        alert('Field Missing !!');
        return false
    }
}

function editExamination(){
            // alert('here');
            var dept = $('#proc_department').val();
            var label = $('#exam_label').val();
            var data_type = $('#data_type').val();
            var syscon = $('#sys_const').val();
            var option = $('#option').val();

            if ($('.procname_variable').is(":checked")){
                var fldid = $('.procname_variable:checked').val();

                if(dept != '' && label !='' && data_type !='' && syscon !='' && option !=''){
                    $.ajax({
                        url: '{{ route('edit.deptexam.activity.consultant') }}',
                        type: "POST",
                        data: {fldid:fldid,dept:dept,label:label,data_type:data_type,syscon:syscon,option:option,"_token": "{{ csrf_token() }}"},
                        success: function (response) {
                            $('#dept_exam_list').empty().html(response);
                        },
                        error: function (xhr, status, error) {
                            var errorMessage = xhr.status + ': ' + xhr.statusText;
                            console.log(xhr);
                        }
                    });
                }else{
                    alert('Field Missing !!');

                }
            }else{
                alert('Please choose examination to update');

            }

        }

        function listExamByProcName(){
            var dept = $('#proc_department').val();
            if(dept !=''){
                $.ajax({
                    url: '{{ route('list.deptexam.activity.consultant') }}',
                    type: "POST",
                    data: {dept:dept,"_token": "{{ csrf_token() }}"},
                    success: function (response) {
                        $('#dept_exam_list').empty().html(response);
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

        // function deptExamPdf(){
        //     if ($('#proc_department').val() == "") {
        //         alert('Please select department.');
        //         return false;
        //     }
        //     $.ajax({
        //         url: '{{ route('export.dept.exam.activity.consultant') }}',
        //         type: "POST",
        //         data: {dept:$('#proc_department').val(),"_token": "{{ csrf_token() }}"},
        //         xhrFields: {
        //             responseType: 'blob'
        //         },
        //         success: function (response, status, xhr) {

        //             var filename = "";
        //             var disposition = xhr.getResponseHeader('Content-Disposition');

        //             if (disposition) {
        //                 var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
        //                 var matches = filenameRegex.exec(disposition);
        //                 if (matches !== null && matches[1]) filename = matches[1].replace(/['"]/g, '');
        //             }
        //             var linkelem = document.createElement('a');
        //             try {
        //                 var blob = new Blob([response], { type: 'application/octet-stream' });

        //                 if (typeof window.navigator.msSaveBlob !== 'undefined') {
        //                     //   IE workaround for "HTML7007: One or more blob URLs were revoked by closing the blob for which they were created. These URLs will no longer resolve as the data backing the URL has been freed."
        //                     window.navigator.msSaveBlob(blob, filename);
        //                 } else {
        //                     var URL = window.URL || window.webkitURL;
        //                     var downloadUrl = URL.createObjectURL(blob);

        //                     if (filename) {
        //                         // use HTML5 a[download] attribute to specify filename
        //                         var a = document.createElement("a");

        //                         // safari doesn't support this yet
        //                         if (typeof a.download === 'undefined') {
        //                             window.location = downloadUrl;
        //                         } else {
        //                             a.href = downloadUrl;
        //                             a.download = filename;
        //                             document.body.appendChild(a);
        //                             a.target = "_blank";
        //                             a.click();
        //                         }
        //                     } else {
        //                         window.location = downloadUrl;
        //                     }
        //                 }

        //             } catch (ex) {
        //                 console.log(ex);
        //             }
        //         },
        //         error: function (xhr, status, error) {
        //             var errorMessage = xhr.status + ': ' + xhr.statusText;
        //             console.log(xhr);
        //         }
        //     });
        // }

        function deptExamPdf(){
            // alert(baseUrl);
            if ($('#proc_department').val() == "") {
                alert('Please select department.');
                return false;
            }
            // $('form').submit(false);
            data = $('#proc_department').val()
           // alert(data);
           var urlReport = baseUrl + "/consultation/export-dept-exam-activity-consultant?data=" + data + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";


           window.open(urlReport, '_blank');
       }
        // deleteDeptExam()
        function deleteDeptExam(val){

            if(val !=''){
                $.ajax({
                    url: '{{ route('delete.deptexam.activity.consultant') }}',
                    type: "POST",
                    data: {val:val,dept:$('#proc_department').val(),"_token": "{{ csrf_token() }}"},
                    success: function (response) {
                        $('#dept_exam_list').empty().html(response);
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
