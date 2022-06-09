@extends('frontend.layouts.master')

@section('content')

<style>
.btn-group{
    position:unset;
}
.btn-group-vertical > .btn, .btn-group > .btn{
    position:unset;
}
.dropdown-menu.show{
    z-index:4;
}
.green_day{
    color:green !important;;
}
.yellow_day{
    color: #767600 !important;
}
.red_day{
    color: red !important;;
}


.treetable-indent
{
    position: relative;

    display: inline-block;

    width: 16px;
    height: 16px;
}
.treetable-expander
{
    position: relative;

    display: inline-block;

    width: 16px;
    height: 16px;

    cursor: pointer;
}
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">
                            Month Wise Admission and Discharge Report
                        </h4>
                    </div>
                    <button onclick="myFunction()" class="btn btn-primary"><i class="fa fa-bars"></i></button>
                </div>
            </div>
        </div>

        <div class="col-sm-12" id="myDIV">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <form id="month_filter_data">
                        <div class="col-sm-3">
                            <div class="form-group form-row">
                                <label for="" class="col-sm-3">Form:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="from_date" id="from_date" value="{{isset($_GET['from_date']) ?$_GET['from_date']: $date }}"/>
                                    <input type="hidden" name="eng_from_date" id="eng_from_date">
                                </div>
                                <!--  <div class="col-sm-3">
                                     <button class="btn btn-primary"><i class="fa fa-calendar" aria-hidden="true"></i></button>
                                 </div> -->
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group form-row">
                                <label for="" class="col-sm-3">To:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="to_date" id="to_date" value="{{isset($_GET['to_date']) ? $_GET['to_date']: $date }}"/>
                                    <input type="hidden" name="eng_to_date" id="eng_to_date">
                                </div>
                                <!-- <div class="col-sm-2">
                                    <button class="btn btn-primary"><i class="fa fa-calendar" aria-hidden="true"></i></button>
                                </div> -->
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <button type="submit" class="btn btn-primary btn-action"> <i class="fa fa-filter"></i>&nbsp;Filter</button>
                                    <a href="javascript:void(0);" type="button" btn-action onclick="exportMonthWiseReport()" class="btn btn-primary btn-action"><i class="fas fa-file-pdf"></i>&nbsp;pdf</a>
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-light btn-action" onclick="myFunction()">Cancel</button>
                                    <a href="{{route('month.wise.adminssion.discharge.report')}}" type="button" class="btn btn-light btn-action" ><i class="fa fa-redo"></i>&nbsp;Reset</a>
                                </div>
                            </div>                                                                        
                        </div>
                    </form>
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
                            <div class="table-responsive table-sticky-th">
                                
                                
                                <table class="table table-bordered" id="table">
                                    <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Admission Patient</th>
                                        <th>Discharge Patient</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>BAISHAKH</td>
                                        <td>
                                            {{\App\Utils\Helpers::monthWiseAdmission(1)[0]['admission_data']??0}}
                                        </td>
                                        <td>
                                            {{\App\Utils\Helpers::monthWiseDischarge(1)[0]['discharge_data']??0}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>JESTHA</td>
                                        <td>
                                            {{\App\Utils\Helpers::monthWiseAdmission(2)[0]['admission_data']??0}}
                                        </td>
                                        <td>
                                            {{\App\Utils\Helpers::monthWiseDischarge(2)[0]['discharge_data']??0}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>ASAR</td>
                                        <td>
                                            {{\App\Utils\Helpers::monthWiseAdmission(3)[0]['admission_data']??0}}
                                        </td>
                                        <td>
                                            {{\App\Utils\Helpers::monthWiseDischarge(3)[0]['discharge_data']??0}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>SHRAWAN</td>
                                        <td>
                                            {{\App\Utils\Helpers::monthWiseAdmission(4)[0]['admission_data']??0}}
                                        </td>
                                        <td>
                                            {{\App\Utils\Helpers::monthWiseDischarge(4)[0]['discharge_data']??0}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>BHADRA</td>
                                        <td>
                                            {{\App\Utils\Helpers::monthWiseAdmission(5)[0]['admission_data']??0}}
                                        </td>
                                        <td>
                                            {{\App\Utils\Helpers::monthWiseDischarge(5)[0]['discharge_data']??0}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>ASOJ</td>
                                        <td>
                                            {{\App\Utils\Helpers::monthWiseAdmission(6)[0]['admission_data']??0}}
                                        </td>
                                        <td>
                                            {{\App\Utils\Helpers::monthWiseDischarge(6)[0]['discharge_data']??0}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>KARTIK</td>
                                        <td>
                                            {{\App\Utils\Helpers::monthWiseAdmission(7)[0]['admission_data']??0}}
                                        </td>
                                        <td>
                                            {{\App\Utils\Helpers::monthWiseDischarge(7)[0]['discharge_data']??0}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>MANGSIR</td>
                                        <td>
                                            {{\App\Utils\Helpers::monthWiseAdmission(8)[0]['admission_data']??0}}
                                        </td>
                                        <td>
                                            {{\App\Utils\Helpers::monthWiseDischarge(8)[0]['discharge_data']??0}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>POUSH</td>
                                        <td>
                                            {{\App\Utils\Helpers::monthWiseAdmission(9)[0]['admission_data']??0}}
                                        </td>
                                        <td>
                                            {{\App\Utils\Helpers::monthWiseDischarge(9)[0]['discharge_data']??0}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>MAGH</td>
                                        <td>
                                            {{\App\Utils\Helpers::monthWiseAdmission(10)[0]['admission_data']??0}}
                                        </td>
                                        <td>
                                            {{\App\Utils\Helpers::monthWiseDischarge(10)[0]['discharge_data']??0}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>FALGUN</td>
                                        <td>
                                            {{\App\Utils\Helpers::monthWiseAdmission(11)[0]['admission_data']??0}}
                                        </td>
                                        <td>
                                            {{\App\Utils\Helpers::monthWiseDischarge(11)[0]['discharge_data']??0}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>CHAITRA</td>
                                        <td>
                                            {{\App\Utils\Helpers::monthWiseAdmission(12)[0]['admission_data']??0}}
                                        </td>
                                        <td>
                                            {{\App\Utils\Helpers::monthWiseDischarge(12)[0]['discharge_data']??0}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Grand Total</td>
                                        <td>
                                            {{$admission_count->admission_data??0}}
                                        </td>
                                        <td>
                                            {{$discharge_count->discharge_data??0}}
                                        </td>
                                    </tr>
                                    
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
{{-- @include('reports::patient-credit-report.modal.preview') --}}
@endsection
@push('after-script')
<script>
    $('#eng_from_date').val(BS2AD($('#from_date').val()));
    $('#eng_to_date').val(BS2AD($('#to_date').val()));
    $('#from_date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            onChange: function () {
                $('#eng_from_date').val(BS2AD($('#from_date').val()));
            }
        });
        $('#to_date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            onChange: function () {
                $('#eng_to_date').val(BS2AD($('#to_date').val()));
            }
        });

        function exportMonthWiseReport() {
            // alert(baseUrl)
            // alert('export');
            var data = $("#month_filter_data").serialize();
            // alert(data);
            var urlReport = baseUrl + "/consultation/month-wise-admission-discharge-pdf-report?" + data + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";

            window.open(urlReport, '_blank');
        }
</script>
@endpush
