@extends('frontend.layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">{{$reportData->fldreportname}}</h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <div class="row">
                            <input type="hidden" name="reportslug" id="reportslug" value="{{$reportData->fldreportslug}}">
                            <div class="col-lg-3 col-sm-3">
                                <div class="form-group form-row">
                                    <label for="" class="col-sm-3">From:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="from_date" id="from_date" value="{{isset($date) ? $date : ''}}"/>
                                        <input type="hidden" name="from_date" id="from_date_eng">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-3">
                                <div class="form-group form-row">
                                    <label for="" class="col-sm-3">To:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="to_date" id="to_date" value="{{isset($date) ? $date : ''}}"/>
                                        <input type="hidden" name="to_date" id="to_date_eng">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6">
                                <div class="form-group form-row">
                                    <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="refreshReport()"><i class="fa fa-sync"></i>&nbsp;Refresh</a>&nbsp;
                                    <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="pdfReport()"><i class="fa fa-file-pdf"></i>&nbsp;Pdf</a>&nbsp;
                                    <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="excelReport()"><i class="fa fa-file"></i>&nbsp;Excel</a>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12">
                                <div class="table-responsive" style="max-height: none;">
                                    <table class="table table-striped table-hover table-bordered ">
                                        <thead class="thead-light" id="table_head">
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
@stop
@push('after-script')
<script>
$(window).ready(function () {
    $('#to_date').nepaliDatePicker({
        npdMonth: true,
        npdYear: true,
        npdYearCount: 20 // Options | Number of years to show
    });
    $('#from_date').nepaliDatePicker({
        npdMonth: true,
        npdYear: true,
        npdYearCount: 20 // Options | Number of years to show
    });

})

$(document).on('click', '.pagination a', function (event) {
    event.preventDefault();
    var page = $(this).attr('href').split('page=')[1];
    refreshReport(page);
});

function refreshReport(page){
    var url = "{{route('dynamic.report.filter')}}";
    $.ajax({
        url: url + "?page=" + page,
        type: "GET",
        data: {
                reportslug: $('#reportslug').val(),
                from_date: $('#from_date').val(),
                to_date: $('#to_date').val()
            },
        success: function (response) {
            if (response.status) {
                $('#table_head').html(response.thead);
                $('#table_result').html(response.tbody);
            }
        },
        error: function (xhr, status, error) {
            var errorMessage = xhr.status + ': ' + xhr.statusText;
            console.log(xhr);
        }
    });
}

function pdfReport(){
    var urlReport = baseUrl + "/dynamicreports/report/filter?typePdf=pdf&from_date=" + $('#from_date').val() + "&to_date=" + $('#to_date').val() + "&reportslug=" + $('#reportslug').val();
    window.open(urlReport, '_blank');
}

function excelReport(){
    var urlReport = baseUrl + "/dynamicreports/report/excel?from_date=" + $('#from_date').val() + "&to_date=" + $('#to_date').val() + "&reportslug=" + $('#reportslug').val();
    window.open(urlReport, '_blank');
}
</script>
@endpush
