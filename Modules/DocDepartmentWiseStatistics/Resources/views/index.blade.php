@extends('frontend.layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">
                            Department Wise Statistics
                            </h4>
                        </div>
                        <button onclick="myFunction()" class="btn btn-primary"><i class="fa fa-bars"></i></button>
                    </div>
                </div>
            </div>
            <div class="col-md-12" id="myDIV">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Filter</h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <div class="row">
                            {{-- <input type="hidden" name="reportslug" id="reportslug" value="{{$reportData->fldreportslug}}"> --}}
                            <div class="col-sm-3 col-lg-2">
                                <div class="form-group">
                                    <label>From</label>
                                        <input type="text" class="form-control" name="from_date" id="from_date" value="{{isset($date) ? $date : ''}}"/>
                                        <input type="hidden" name="from_date" id="from_date_eng">
                                </div>
                            </div>
                            <div class="col-sm-3 col-lg-2">
                                <div class="form-group">
                                    <label>To</label>
                                        <input type="text" class="form-control" name="to_date" id="to_date" value="{{isset($date) ? $date : ''}}"/>
                                        <input type="hidden" name="to_date" id="to_date_eng">
                                </div>
                            </div>
                            <div class="col-sm-3 col-lg-4">
                                <div class="form-group">
                                    <label>Consultant</label>
                                    <select name="consultant" id="consultant" class="form-control">
                                        <option value="">--Select--</option>
                                        @if (count($consultantList))
                                            @foreach ($consultantList as $con)
                                                @if ($con->nmc)
                                                    <option data-nmc="{{ $con->nmc }}"
                                                        value="{{ $con->username }}" {{ (request('consultant') == $con->username) ? "selected='selected'" : "" }}>
                                                        {{ $con->fldtitlefullname }}</option>
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3 col-lg-4">
                                <div class="form-group">
                                    <label>Department</label>
                                    <select class="form-control" name="department" id="department">
                                        <option value="">--Select--</option>
                                        @foreach($departments as $department)
                                        <option value="{{ $department->flddept }}" {{ (request('department') == $department->flddept) ? "selected='selected'" : "" }}>{{ $department->flddept }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="type" id="ip_patient"
                                        value="IP" class="custom-control-input">
                                    <label class="custom-control-label" for="ip_patient"> IP </label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="type" id="op_patient" class="custom-control-input" value="OP">
                                    <label class="custom-control-label" for="op_patient"> OP </label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group d-flex justify-content-end">
                                    <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="refreshReport()"><i class="fa fa-filter"></i>&nbsp;Filter</a>&nbsp;
                                    <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="pdfReport()"><i class="fa fa-file-pdf"></i>&nbsp;Pdf</a>&nbsp;
                                    <a href="javascript:void(0);" type="button" class="btn btn-primary btn-action" onclick="excelReport()"><i class="fa fa-file"></i>&nbsp;Excel</a>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">Department Wise Statistics Details</h4>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <div class="col-lg-12 col-md-12">
                                <div class="table-responsive" id="table-datas" style="max-height: none;">
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
$(function() {
    $('#myTable1').bootstrapTable()
});
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

function refreshReport(){
    $('#to_date_eng').val(BS2AD($('#to_date').val()));
    $('#from_date_eng').val(BS2AD($('#from_date').val()));
    var fromdate = $('#from_date_eng').val();
    var todate = $('#to_date_eng').val();
    
    const date1 = new Date(fromdate);
    const date2 = new Date(todate);
    const diffTime = Math.abs(date2 - date1);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    if(diffDays > '30') {
        // console.log(diffDays + " days");
        Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: "Cannot select date greater than 30 days"
        })
        return;
    }

    var url = "{{route('dynamic.statistics.filter')}}";
    $.ajax({
        url: url,
        type: "GET",
        data: {
                from_date: $('#from_date').val(),
                to_date: $('#to_date').val(),
                department: $('#department').val(),
                consultant: $('#consultant').val(),
                type: $("input[name=type]:checked").val()
            },
        success: function (response) {
            if (response.status == true) {
                $('#table-datas').html(response.html);
                $('#myTable1').bootstrapTable();
            }
        },
        error: function (xhr, status, error) {
            var errorMessage = xhr.status + ': ' + xhr.statusText;
            console.log(xhr);
        }
    });
}

function pdfReport(){
    $('#to_date_eng').val(BS2AD($('#to_date').val()));
    $('#from_date_eng').val(BS2AD($('#from_date').val()));
    var fromdate = $('#from_date_eng').val();
    var todate = $('#to_date_eng').val();
    
    const date1 = new Date(fromdate);
    const date2 = new Date(todate);
    const diffTime = Math.abs(date2 - date1);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    if(diffDays > '30') {
        // console.log(diffDays + " days");
        Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: "Cannot select date greater than 30 days"
        })
        return;
    }
    var urlReport = baseUrl + "/departmentwisestatistics/report/pdf?typePdf=pdf&from_date=" + $('#from_date').val() + "&to_date=" + $('#to_date').val() + "&department=" + $('#department').val() + "&consultant=" + $('#consultant').val() + "&type=" + $("input[name=type]:checked").val();
    window.open(urlReport, '_blank');
}

function excelReport(){
    $('#to_date_eng').val(BS2AD($('#to_date').val()));
    $('#from_date_eng').val(BS2AD($('#from_date').val()));
    var fromdate = $('#from_date_eng').val();
    var todate = $('#to_date_eng').val();
    
    const date1 = new Date(fromdate);
    const date2 = new Date(todate);
    const diffTime = Math.abs(date2 - date1);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    if(diffDays > '30') {
        // console.log(diffDays + " days");
        Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: "Cannot select date greater than 30 days"
        })
        return;
    }
    var urlReport = baseUrl + "/departmentwisestatistics/report/excel?typePdf=pdf&from_date=" + $('#from_date').val() + "&to_date=" + $('#to_date').val() + "&department=" + $('#department').val() + "&consultant=" + $('#consultant').val() + "&type=" + $("input[name=type]:checked").val();
    window.open(urlReport, '_blank');
}
</script>
@endpush
