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
.dropdown-item-marker .send_email_sms_checkbox{
    display: none;
}
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">
                            Email And Sms
                        </h4>
                    </div>
                    <button onclick="myFunction()" class="btn btn-primary"><i class="fa fa-bars"></i></button>
                </div>
            </div>
        </div>

        <div class="col-sm-12" id="myDIV">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <form id="patient_email_sms">
                        <div class="col-sm-3">
                            <div class="form-group form-row">
                                <label for="" class="col-sm-3">Form:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="from_date" id="from_date" value="{{isset($_GET['from_date']) ?$_GET['from_date']: $date }}"/>
                                    <input type="hidden" name="eng_from_date" id="eng_from_date">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group form-row">
                                <label for="" class="col-sm-3">To:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="to_date" id="to_date" value="{{isset($_GET['to_date']) ? $_GET['to_date']: $date }}"/>
                                    <input type="hidden" name="eng_to_date" id="eng_to_date">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="d-flex justify-content-between">
                                <div>
                                    {{-- <button type="submit" class="btn btn-primary btn-action"> <i class="fa fa-filter"></i>&nbsp;Filter</button> --}}
                                    {{-- <a href="javascript:void(0);" type="button" btn-action onclick="pdfTopLabTestReport()" class="btn btn-primary btn-action"><i class="fas fa-file-pdf"></i>&nbsp;pdf</a>
                                    <a href="javascript:void(0);" onclick="exportTopLabTestReport()" type="button" class="btn btn-primary btn-action"><i class="fa fa-code"></i>&nbsp;Export</a> --}}
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-action"> <i class="fa fa-filter"></i>&nbsp;Filter</button>
                                    <button type="submit" class="btn btn-light btn-action" onclick="myFunction()">Cancel</button>
                                    <a href="{{route('sms.email')}}" type="button" class="btn btn-light btn-action" ><i class="fa fa-redo"></i>&nbsp;Reset</a>
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
                    <ul class="nav nav-tabs d-flex justify-content-between" id="myTab-two" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab-grid" data-toggle="tab" href="#grid" role="tab" aria-controls="home" aria-selected="true">Grid View</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" type="button" btn-action onclick="pdfTopLabTestReport()" class="btn btn-primary btn-action pull-left" ><i class="fas fa-file-pdf"></i>&nbsp;pdf</a>
                            <a href="javascript:void(0);" onclick="exportTopLabTestReport()" type="button" class="btn btn-primary btn-action"><i class="fa fa-code"></i>&nbsp;Export</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent-1">
                        <div class="tab-pane fade show active" id="grid" role="tabpanel" aria-labelledby="home-tab-grid">
                            <div class="table-responsive table-sticky-th">
                                {{-- <input type="checkbox" id="send_email_sms_checkbox" onclick="test()"> --}}
                                <a href="javascript:void(0);" type="button" class="btn btn-primary btn-sm" id="send_mail">send mail</a>
                                <a href="javascript:void(0);" type="button" class="btn btn-primary btn-sm" id="send_sms">send sms</a>
                                <table class="table table-bordered" id="myTable1" data-show-columns="true"
                                data-search="true"
                                data-show-toggle="true"
                                data-pagination="true"
                                data-resizable="true">
                                    <thead>
                                    <tr>
                                        <th>S.N</th>
                                        <th> <input type="checkbox" class="send_email_sms_checkbox" onclick="test(this)">Check Box</th>
                                        {{-- <th><input type="checkbox" id="send_email_sms_checkbox" onclick="test()"> Check Box</th> --}}
                                        <th>Patient Detail</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @if(!$record->isEmpty())
                                        @php
                                            $count=1;
                                        @endphp
                                            @foreach ($record as $list)
                                            <tr>
                                                <td>{{$count++}}</td>
                                                <td><input type="checkbox" name="send_sms_and_email[]" class="send_email_sms_for_multiple_checkbox" value="{{$list->fldpatientval}}"></td>
                                                <td>
                                                    {{strtoupper($list->fldptnamefir??'')}} {{strtoupper($list->fldptnamelast??'')}}<br>
                                                    {{\Carbon\Carbon::parse($list->fldptbirday)->age}} Y/ {{($list->fldptsex??'')}} {{($list->fldptcontact??'')}} <br>
                                                    {{strtoupper($list->fldptadddist??'')}}<br>
                                                    
                                                </td>
                                                <td>{{($list->fldptcontact??'')}}</td>
                                                <td>{{($list->fldemail??'')}}</td>
                                                <td>
                                                    <a href="" type="button" class="btn btn-primary btn-sm">send mail</a>
                                                    <a href="" type="button" class="btn btn-primary btn-sm">send sms</a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        @endif
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

    function pdfTopLabTestReport() {
        var data = $("#patient_email_sms").serialize();
        var urlReport = baseUrl + "/admin/laboratory/top/ten-lab-test-pdf?" + data + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";

        window.open(urlReport, '_blank');
    }
    function exportTopLabTestReport() {
        var data = $("#patient_email_sms").serialize();
        var urlReport = baseUrl + "/admin/laboratory/top/ten-lab-test-export?" + data + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";
        window.open(urlReport, '_blank');
    }
    $(function() {
        $('#myTable1').bootstrapTable()
    })

    function test(currentElement){
        if ($(currentElement).prop('checked'))
            $('.send_email_sms_for_multiple_checkbox').prop('checked', true);
        else
            $('.send_email_sms_for_multiple_checkbox').prop('checked', false);
    }

    $('#send_mail').click(function (e) {
        e.preventDefault();
        var patlient_id = [];
        $.each($('.send_email_sms_for_multiple_checkbox:checked'), function (i, ele) {
            patlient_id.push($(ele).val());
        });
        if (patlient_id.length == 0) {
            showAlert("Please select one or more test to update.", 'fail');
            return false;
        }
        console.log(patlient_id);
        $.ajax({
            url: baseUrl + '/patient-list/send-email',
            type: "POST",
            data: {patlient_id:patlient_id},
            success: function (response, status, xhr) {
                console.log(response);
                // alert('kkk')
                // showAlert(response.message);
            },
            error: function (xhr, status, error) {
                console.log(error);
                // showAlert(error);
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });

    });

    $('#send_sms').click(function (e) {
        e.preventDefault();
        var patlient_id = [];
        $.each($('.send_email_sms_for_multiple_checkbox:checked'), function (i, ele) {
            patlient_id.push($(ele).val());
        });
        if (patlient_id.length == 0) {
            showAlert("Please select one or more test to update.", 'fail');
            return false;
        }
        console.log(patlient_id);
        $.ajax({
            url: baseUrl + '/patient-list/send-sms',
            type: "POST",
            data: {patlient_id:patlient_id},
            success: function (response, status, xhr) {
                console.log(response);
                // alert('kkk')
                // showAlert(response.message);
            },
            error: function (xhr, status, error) {
                console.log(error);
                // showAlert(error);
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });

    });
    
</script>
@endpush
