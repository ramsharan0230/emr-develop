@extends('frontend.layouts.master')
@push('after-styles')

@endpush

@section('content')

<div class="container-fluid">
 @include('menu::toggleButton')
 <div class="row">
   <div class="col-lg-12 col-md-12 leftdiv">
    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-header d-flex justify-content-between">
            <div class="iq-header-title">
                <h4 class="card-title">Extra Procedure</h4>
            </div>
            <button onclick="myFunction()" class="btn btn-primary"><i class="fa fa-bars"></i></button>
        </div>
    </div>
</div>
<div class="col-sm-12" id="myDIV">
    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-body" id="extraprocedure">
            <div class="row">
                <div class="col-lg-8 col-md-12">
                    <form id="procGroupform">
                        <div class="form-group form-row align-items-center">
                            <label class="col-sm-2">Group Name</label>
                            <div class="col-sm-9">
                                <input name="group" id="group" type="text" list="groups" class="form-control" />
                                <datalist id="groups">
                                 @if(isset($groups) && count($groups) > 0)
                                 @foreach($groups as $g)
                                 <option value="{{$g->flditemname}}">{{$g->flditemname}}</option>
                                 @endforeach
                                 @endif
                             </datalist>
                                        <!-- <select name="group" id="" class=" group form-control">

                                            @if(isset($groups) && count($groups) > 0)
                                                @foreach($groups as $g)
                                                    <option value="{{$g->flditemname}}">{{$g->flditemname}}</option>
                                                @endforeach
                                            @endif
                                        </select> -->
                                    </div>
                                    <div class="col-sm-1">
                                        <a href="javascript:void(0);" class="btn btn-primary" onclick="listByGroup()"><i class="ri-refresh-line"></i></a>
                                    </div>
                                </div>
                                <div class="form-group form-row align-items-center">
                                    <label class="col-sm-2">Procedures</label>
                                    <div class="col-sm-4">
                                        <!-- <input type="text" name="" id="" class="form-control"> -->
                                        <input name="procedures" id="procedures" type="text" list="procs" class="form-control"   />
                                        <datalist id="procs">
                                            @if(isset($procs) && count($procs) > 0)
                                            @foreach($procs as $p)
                                            <option value="{{$p->fldprocname}}">{{$p->fldprocname}}</option>
                                            @endforeach
                                            @endif
                                        </datalist>
                                        <!-- <select name="procedures" id="procedures" class=" procedures form-control">

                                            @if(isset($procs) && count($procs) > 0)
                                                @foreach($procs as $p)
                                                    <option value="{{$p->fldprocname}}">{{$p->fldprocname}}</option>
                                                @endforeach
                                            @endif
                                        </select> -->
                                    </div>
                                    <div class="col-sm-6">
                                        <button class="btn btn-warning" onclick="exportProcGroupToPdf()"><i class="ri-code-s-slash-line"></i> Export</button>&nbsp;
                                        <button class="btn btn-primary" onclick="addGroupProc()"><i class="ri-add-line"></i> Add</button>&nbsp;
                                        <button class="btn btn-danger" onclick="deleteGroupProc()"><i class="ri-delete-bin-5-fill"></i> Delete</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
               <div class="iq-card-body" id="extraprocedure">
                   <div class="table-responsive table-container">
                    <table class="table table-bordered table-striped table-hover ">
                        <thead class="thead-light">
                            <tr>
                                <th>Group Name</th>
                                <th>Procedures</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="extra_proc_list">
                            @if(isset($groupprocs) and count($groupprocs) > 0)
                            @foreach($groupprocs as $gp)
                            <tr>

                                <td>{{$gp->fldgroupname}}</td>
                                <td>{{$gp->fldprocname}}</td>
                                <td><a href="javascript:void(0)" onclick="deleteprogroup({{$gp->fldid}})"><i class="fa fa-trash text-danger"></i></a></td>
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

<script type="text/javascript">
    $(document).ready(function () {

        setTimeout(function() {
            $(".group").select2();
            $(".procedures").select2();
        }, 1500);

    });
    $(document).on("keydown", ".select2-search__field", function (e) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '13') {
            var gvalue = $('.group').closest('div.col-sm-9').find('input[class="select2-search__field"]').val();
            var pvalue = $('.procedures').val();
            alert(gvalue);
            $('.group').append('<option value="' + gvalue + '" selected >' + gvalue + '</option>')
            $('.procedures').append('<option value="' + pvalue + '" selected >' + pvalue + '</option>')
                // if($('.procedures').val().length > 0 && $('.procedures').val() == ''){

                // }

            }
        });
    function addGroupProc(){
        var group = $('#group').val();
        var proc = $('#procedures').val();
        if($('#group').val().length < 1){
            alert('Select group');
            return false;
        }else if(proc.length < 1){
            alert('Select procedures');
            return false;
        }else{
            $.ajax({
                url: '{{ route('add.group.proc.groups.consultant') }}',
                type: "POST",
                data: {group:group,proc:proc,"_token": "{{ csrf_token() }}"},
                success: function (response) {
                    $('#extra_proc_list').empty().html(response);

                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }


    }
    function deleteGroupProc(){
        if ($("input[name='proc_group_name']:checked").prop('checked')==true){
            var favorite = [];
            $.each($("input[name='proc_group_name']:checked"), function(){
                favorite.push($(this).val());

            });
                // alert("My favourite sports are: " + favorite.join(", "));
                var flds = favorite.join(",");

                $.ajax({
                    url: '{{ route('delete.procgroup.groups.consultant') }}',
                    type: "POST",
                    data: {fldids:flds,"_token": "{{ csrf_token() }}"},
                    success: function (response) {
                        $('#extra_proc_list').empty().html(response);

                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });
            }else{
                alert('Please select procedure to delete');
                return false;
            }
        }

        function listByGroup(){

            $.ajax({
                url: '{{ route('list.by.group.groups.consultant') }}',
                type: "POST",
                data: {group:$('#group').val(),"_token": "{{ csrf_token() }}"},
                success: function (response) {
                    $('#extra_proc_list').empty().html(response);

                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }


        function exportProcGroupToPdf(){
            $('form').submit(false);
            data = $('#procGroupform').serialize();
            // alert(data);
            // if($('#group').val().length > 1){
                var urlReport = baseUrl + "/consultation/group/exportToPdfGroup?" + data + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";


                window.open(urlReport, '_blank');
            // }else{
            //     alert('Select group');
            //     return false;
            // }

            // $.ajax({
            //     url: '{{ route('export.proc.group.groups.consultant') }}',
            //     type: "POST",
            //     data: {group:$('#group').val(),"_token": "{{ csrf_token() }}"},
            //     xhrFields: {
            //         responseType: 'blob'
            //     },
            //     success: function (response, status, xhr) {

            //         var filename = "";
            //         var disposition = xhr.getResponseHeader('Content-Disposition');

            //         if (disposition) {
            //             var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
            //             var matches = filenameRegex.exec(disposition);
            //             if (matches !== null && matches[1]) filename = matches[1].replace(/['"]/g, '');
            //         }
            //         var linkelem = document.createElement('a');
            //         try {
            //             var blob = new Blob([response], { type: 'application/octet-stream' });

            //             if (typeof window.navigator.msSaveBlob !== 'undefined') {
            //                 //   IE workaround for "HTML7007: One or more blob URLs were revoked by closing the blob for which they were created. These URLs will no longer resolve as the data backing the URL has been freed."
            //                 window.navigator.msSaveBlob(blob, filename);
            //             } else {
            //                 var URL = window.URL || window.webkitURL;
            //                 var downloadUrl = URL.createObjectURL(blob);

            //                 if (filename) {
            //                     // use HTML5 a[download] attribute to specify filename
            //                     var a = document.createElement("a");

            //                     // safari doesn't support this yet
            //                     if (typeof a.download === 'undefined') {
            //                         window.location = downloadUrl;
            //                     } else {
            //                         a.href = downloadUrl;
            //                         a.download = filename;
            //                         document.body.appendChild(a);
            //                         a.target = "_blank";
            //                         a.click();
            //                     }
            //                 } else {
            //                     window.location = downloadUrl;
            //                 }
            //             }

            //         } catch (ex) {
            //             console.log(ex);
            //         }
            //     },
            //     error: function (xhr, status, error) {
            //         var errorMessage = xhr.status + ': ' + xhr.statusText;
            //         console.log(xhr);
            //     }
            // });
        }

        function deleteprogroup(id){
            var group = $('#group').val();
            if(confirm('Delete Procedure ??')){

                $.ajax({
                    url: '{{ route('delete.procgroup.groups.consultant') }}',
                    type: "POST",
                    data: {id:id,group:group,"_token": "{{ csrf_token() }}"},
                    success: function (response) {
                        $('#extra_proc_list').empty().html(response);

                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });


            }
        }
    </script>

    @stop
