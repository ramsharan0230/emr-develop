@extends('frontend.layouts.master')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">
                            Entry Waiting Report
                        </h4>
                    </div>
                    <button onclick="myFunction()" class="btn btn-primary"><i class="fa fa-bars"></i></button>
                </div>
            </div>
        </div>
        <div class="col-sm-12" id="myDIV">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <div class="row">
                        <div class="col-lg-4 col-sm-4">
                            <div class="form-group form-row">
                                <label for="" class="col-sm-4">Type:</label>
                                <div class="col-sm-8">
                                    <select name="type" id="type" class="form-control">
                                        <option value="notSaved">Not Saved</option>
                                        <option value="notBilled">Not Billed</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-sm-4">
                            <div class="form-group form-row">
                                <label for="" class="col-sm-4">Department:</label>
                                <div class="col-sm-8">
                                    <select name="comp" id="comp" class="form-control">
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

                        <div class="col-lg-4 col-sm-2">
                            <div class="form-group form-row">
                                <label for="" class="col-sm-4">User:</label>
                                <div class="col-sm-8">
                                    <input type="text" name="user" id="username" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <a href="javascript:void(0);" type="button" class="btn btn-primary rounded-pill" onclick="searchEntryDetail()"><i class="fa fa-check"></i>&nbsp;
                            Refresh</a>&nbsp;
                            <a href="javascript:void(0);" type="button" class="btn btn-primary rounded-pill" onclick="exportDepositReport()"><i class="fas fa-file-pdf"></i>&nbsp;
                            Pdf</a>
                            <a href="javascript:void(0);" type="button" class="btn btn-primary rounded-pill" onclick="exportDepositReportExcel()"><i class="fa fa-code"></i>&nbsp;
                            Export</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <ul class="nav nav-tabs" id="myTab-two" role="tablist">
                      <li class="nav-item">
                         <a class="nav-link active" id="home-tab-grid" data-toggle="tab" href="#grid" role="tab" aria-controls="home" aria-selected="true">Grid View</a>
                      </li>
                   </ul>
                   <div class="tab-content" id="myTabContent-1">
                        <div class="tab-pane fade show active" id="grid" role="tabpanel" aria-labelledby="home-tab-grid">
                            <div class="table-responsive res-table" style="max-height: none;">
                                <table class="table table-striped table-hover table-bordered ">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>SN.</th>
                                            <th>EncID</th>
                                            <th>Category</th>
                                            <th>Particulars</th>
                                            <th>Rate</th>
                                            <th>Qty</th>
                                            <th>User</th>
                                            <th>Dept</th>
                                            <th>DateTime</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table_result">
                                    </tbody>
                                </table>
                            </div>
                          </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $( document ).ready(function() {
        $(document).on('click', '.pagination a', function(event){
          event.preventDefault();
          var page = $(this).attr('href').split('page=')[1];
          searchEntryDetail(page);
         });
    });

    function exportDepositReport(){
        var urlReport = baseUrl + "/mainmenu/entry-waiting/refresh-data?type=" + $('#type').val() + "&comp=" + $('#comp').val() + "&username=" + $('#username').val() + "&isExport=true";
        window.open(urlReport, '_blank');
    }

    function exportDepositReportExcel(){
        var urlReport = baseUrl + "/mainmenu/entry-waiting/export-excel?type=" + $('#type').val() + "&comp=" + $('#comp').val() + "&username=" + $('#username').val();
        window.open(urlReport);
    }

    $(document).on('keydown','#username',function(e){
        if (e.which == 13) {
            searchEntryDetail(page);
        }
    });

    function searchEntryDetail(page){
        var url = "{{route('entry-waiting.report.refreshdata')}}";
        $.ajax({
            url: url+"?page="+page,
            type: "GET",
            data:  {
                        type: $('#type').val(),
                        comp: $('#comp').val(),
                        username: $('#username').val(),
                        _token: "{{ csrf_token() }}"
                    },
            success: function(response) {
                if(response.data.status){
                    $('#table_result').html(response.data.html)
                }
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    }

</script>
@endsection

