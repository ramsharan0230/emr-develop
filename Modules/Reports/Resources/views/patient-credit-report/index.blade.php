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
.res-table {
    max-height: unset;
}
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">
                            Credit Report
                        </h4>
                    </div>
                    <button onclick="myFunction()" class="btn btn-primary"><i class="fa fa-bars"></i></button>
                </div>
            </div>
        </div>

        <div class="col-sm-12" id="myDIV">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <form id="patient_credit_report">
                        <div class="row">
                            <div class="col-lg-6 col-sm-6">
                                <div class="form-group">
                                <label for="">Department</label>
                                <select name="department" class="form-control select2">
                                    <option value="">--Department--</option>
                                    @if($hospital_department)
                                        @foreach($hospital_department as $dept)
                                            @if($dept->departmentData)
                                            @dump($dept->departmentData->fldcomp)
                                                <option value="{{ $dept->departmentData->fldcomp }}" @if(isset($_GET['department']) &&  $_GET['department'] ==$dept->departmentData->fldcomp) selected @endif>{{$dept->departmentData->name }} ({{$dept->departmentData->branchData->name}})</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <a href="{{route('patient.credit.report')}}" type="button" class="btn btn-light btn-action float-right" ><i class="fa fa-redo"></i>&nbsp;Reset</a>&nbsp;                                     
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Patient Name</label>
                                    <input type="text" name="patient_name" class="form-control" placeholder="Patient Name" value="{{$patient_name ?? ''}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Patient ID</label>
                                    <input type="text" name="patient_id" class="form-control" placeholder="Patient ID" value="{{$patient_id ?? ''}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Encounter ID</label>
                                    <input type="text" name="encounter_id" class="form-control" placeholder="Encounter ID" value="{{$encounter_id ?? ''}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Patient Phone Number</label>
                                    <input type="text" name="patient_number" class="form-control" placeholder="Patient Number" value="{{$patient_number ?? ''}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="">Amount Type</label>
                                <div class="row" style="margin-left: 4px">
                                    <div class="form-group form-row">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="" value="0" name="amount_type" class="custom-control-input" @if($amount_type==0) checked @endif>
                                            <label class="custom-control-label" for=""> Credit</label>
                                        </div>
                                        &nbsp;&nbsp;
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="" value="1" name="amount_type" class="custom-control-input" @if($amount_type==1) checked @endif>
                                            <label class="custom-control-label" for="">Deposit</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-4 pt-4">
                                    <button type="submit" class="btn btn-primary btn-action float-right"> <i class="fa fa-filter"></i>&nbsp;Filter</button>
                                    <button type="submit" class="btn btn-light btn-action float-right mr-2" onclick="myFunction()">Cancel</button>
                                    {{-- <a href="javascript:void(0);" type="button" id="dropdownMenuLink" class="btn btn-primary btn-action" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-file-pdf"></i>&nbsp;Report</a> --}}
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
                            <a href="javascript:void(0);" type="button" btn-action onclick="patientCreditPdfReport()" class="btn btn-primary btn-action pull-left" ><i class="fas fa-file-pdf"></i>&nbsp;pdf</a>
                            <a href="javascript:void(0);" onclick="patientCreditExcelReport()" type="button" class="btn btn-primary btn-action"><i class="fa fa-code"></i>&nbsp;Export</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent-1">
                        <div class="tab-pane fade show active" id="grid" role="tabpanel" aria-labelledby="home-tab-grid">
                            <div class="table-responsive res-table table-sticky-th">
                                <table class="table table-striped table-hover table-bordered" id="patient_credit_report">
                                    <thead class="thead-light">
                                    <tr>
                                        <th>SN</th>
                                        <th>Date</th>
                                        <th>Days</th>
                                        <th>Patient ID/Encounter ID</th>
                                        <th>Patient Details</th>
                                        @if($amount_type==0)
                                        <th>Credit Amount</th>
                                        @elseif($amount_type==1)
                                        <th>Deposite Amount</th>
                                        @else
                                        <th>Credit Amount</th>
                                        @endif
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody id="billing_result">
                                        <?php 
                                            $count = ($patient_credit_report->currentpage()-1)*$patient_credit_report->perpage()+1; 
                                        ?>
                                        @if(!$patient_credit_report->isEmpty())
                                        @foreach ($patient_credit_report as $pcr)
                                        @php
                                            $days=new \Carbon\Carbon($pcr->fldtime);
                                            $days_exceed=\Carbon\Carbon::now()->diffInDays($days);
                                        @endphp
                                            @if(isset($patient_credit_color))
                                                @if($days_exceed<=$patient_credit_color->green_day)
                                                    <tr>
                                                        <td class="green_day">{{$count++}}</td>
                                                        <td class="green_day">{{$pcr->fldtime ?? ''}}</td>
                                                        <td class="green_day">{{$days_exceed ?? ''}}</td>
                                                        <td class="green_day">{{$pcr->fldpatientval ?? ''}}/{{$pcr->fldencounterval ?? ''}}</td>
                                                        <td class="green_day">
                                                            {{strtoupper($pcr->fldptnamefir) ?? ''}} {{strtoupper($pcr->fldptnamelast) ?? ''}} <br>
                                                            {{Carbon\Carbon::parse($pcr->fldptbirday)->age ?? ''}}/{{$pcr->fldptsex ?? ''}} Y <br> 
                                                            {{$pcr->fldptcontact ?? ''}}
                                                        </td>
                                                        <td class="green_day">{{$pcr->fldcurdeposit ?? ''}}</td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                Bill Details
                                                                </button>
                                                                <div class="dropdown-menu">
                                                                    @if($amount_type==0)
                                                                        <a class="dropdown-item" href="{{route('deposit.credit',['encounter_id'=>$pcr->fldencounterval])}}" target="_blank">Credit Clearance</a>
                                                                    @endif
                                                                <a class="dropdown-item" href="#" id="remarks" onclick="showRemarks('{{ $pcr->fldid ?? '' }}')">Remarks</a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @elseif($days_exceed >= $patient_credit_color->green_day && $days_exceed <= $patient_credit_color->yellow_day)
                                                    <tr>
                                                        <td class="yellow_day">{{$count++}}</td>
                                                        <td class="yellow_day">{{$pcr->fldtime ?? ''}}</td>
                                                        <td class="yellow_day">{{$days_exceed ?? ''}}</td>
                                                        <td class="yellow_day">{{$pcr->fldpatientval ?? ''}}/{{$pcr->fldencounterval ?? ''}}</td>
                                                        <td class="yellow_day">
                                                            {{strtoupper($pcr->fldptnamefir) ?? ''}} {{strtoupper($pcr->fldptnamelast) ?? ''}} <br>
                                                            {{Carbon\Carbon::parse($pcr->fldptbirday)->age ?? ''}} Y/{{$pcr->fldptsex ?? ''}} <br> 
                                                            {{$pcr->fldptcontact ?? ''}}
                                                        </td>
                                                        <td class="yellow_day">{{$pcr->fldcurdeposit ?? ''}}</td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                Bill Details
                                                                </button>
                                                                <div class="dropdown-menu">
                                                                    @if($amount_type==0)
                                                                        <a class="dropdown-item" href="{{route('deposit.credit',['encounter_id'=>$pcr->fldencounterval])}}" target="_blank">Credit Clearance</a>
                                                                    @endif
                                                                <a class="dropdown-item" href="#" id="remarks" onclick="showRemarks('{{ $pcr->fldid ?? '' }}')">Remarks</a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @elseif($days_exceed >= $patient_credit_color->yellow_day && $days_exceed <= $patient_credit_color->red_day)
                                                    <tr>
                                                        <td class="red_day">{{$count++}}</td>
                                                        <td class="red_day">{{$pcr->fldtime ?? ''}}</td>
                                                        <td class="red_day">{{$days_exceed ?? ''}}</td>
                                                        <td class="red_day">{{$pcr->fldpatientval ?? ''}}/{{$pcr->fldencounterval ?? ''}}</td>
                                                        <td class="red_day">{{strtoupper($pcr->fldptnamefir) ?? ''}} {{strtoupper($pcr->fldptnamelast) ?? ''}} <br>
                                                            {{Carbon\Carbon::parse($pcr->fldptbirday)->age ?? ''}} Y/{{$pcr->fldptsex ?? ''}} <br> 
                                                            {{$pcr->fldptcontact ?? ''}}
                                                        </td>
                                                        <td class="red_day">{{$pcr->fldcurdeposit ?? ''}}</td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                Bill Details
                                                                </button>
                                                                <div class="dropdown-menu">
                                                                    @if($amount_type==0)
                                                                        <a class="dropdown-item" href="{{route('deposit.credit',['encounter_id'=>$pcr->fldencounterval])}}" target="_blank">Credit Clearance</a>
                                                                    @endif
                                                                <a class="dropdown-item" href="#" id="remarks" onclick="showRemarks('{{ $pcr->fldid ?? '' }}')">Remarks</a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @else
                                                <tr>
                                                    <td>{{$count++}}</td>
                                                    <td>{{$pcr->fldtime ?? ''}}</td>
                                                    <td>{{$days_exceed ?? ''}}</td>
                                                    <td>{{$pcr->fldpatientval ?? ''}}/{{$pcr->fldencounterval ?? ''}}</td>
                                                    <td>
                                                        {{strtoupper($pcr->fldptnamefir) ?? ''}} {{strtoupper($pcr->fldptnamelast) ?? ''}} <br>
                                                        {{Carbon\Carbon::parse($pcr->fldptbirday)->age ?? ''}} Y/{{$pcr->fldptsex ?? ''}} <br> 
                                                        {{$pcr->fldptcontact ?? ''}} 
                                                    </td>
                                                    <td>{{$pcr->fldcurdeposit ?? ''}}</td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            Bill Details
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                @if($amount_type==0)
                                                                    <a class="dropdown-item" href="{{route('deposit.credit',['encounter_id'=>$pcr->fldencounterval])}}" target="_blank">Credit Clearance</a>
                                                                @endif
                                                            <a class="dropdown-item" href="#" id="remarks" onclick="showRemarks('{{ $pcr->fldid ?? '' }}')">Remarks</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endif
                                            @else
                                            <tr>
                                                <td>{{$count++}}</td>
                                                <td>{{$pcr->fldtime ?? ''}}</td>
                                                <td>{{$days_exceed ?? ''}}</td>
                                                <td>{{$pcr->fldpatientval ?? ''}}/{{$pcr->fldencounterval ?? ''}}</td>
                                                <td>
                                                    {{strtoupper($pcr->fldptnamefir) ?? ''}} {{strtoupper($pcr->fldptnamelast) ?? ''}} <br>
                                                    {{Carbon\Carbon::parse($pcr->fldptbirday)->age ?? ''}} Y/{{$pcr->fldptsex ?? ''}} <br> 
                                                    {{$pcr->fldptcontact ?? ''}}    
                                                </td>
                                                <td>{{$pcr->fldcurdeposit ?? ''}}</td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Bill Details
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            @if($amount_type==0)
                                                                {{-- <a class="dropdown-item" href="{{route('billing.user.report',['billno'=>$pcr->fldbillno])}}" target="_blank">Credit Clearance</a> --}}
                                                                <a class="dropdown-item" href="{{route('deposit.credit',['encounter_id'=>$pcr->fldencounterval])}}" target="_blank">Credit Clearance</a>
                                                            @endif
                                                        <a class="dropdown-item" href="#" id="remarks" onclick="showRemarks('{{ $pcr->fldid ?? '' }}')">Remarks</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endif
                                        @endforeach
                                        @endif                        
                                    </tbody>
                                </table>
                                {{
                                $patient_credit_report->appends(Request::only('department'))
                                                        ->appends(Request::only('patient_name'))
                                                        ->appends(Request::only('patient_id'))
                                                        ->appends(Request::only('encounter_id'))
                                                        ->appends(Request::only('patient_number'))
                                                        ->appends(Request::only('amount_type'))
                                                        ->links()
                                }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('reports::patient-credit-report.modal.preview')
@endsection
@push('after-script')
<script>
    function showRemarks(encounter_id) {
        if (encounter_id == "") {
            showAlert('No patient selected.', 'error');
            return false;
        }
        let route = "{!! route('patient.credit.remarks', ['encounter_id' => ':ENCOUNTER_ID']) !!}";
        route = route.replace(':ENCOUNTER_ID', encounter_id);
        $.ajax({
            url: route,
            type: "GET",
            success: function (response) {
                $('.preview-modal-content').empty();
                $('.preview-modal-content').html(response);
                $('#preview-modal').modal('show');
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
            }
        });
    }

    function patientCreditPdfReport() {
        var data = $("#patient_credit_report").serialize();
        var urlReport = baseUrl + "/patient-credit-pdf-report?" + data + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";

        window.open(urlReport, '_blank');
    }
    function patientCreditExcelReport() {
        var data = $("#patient_credit_report").serialize();
        var urlReport = baseUrl + "/patient-credit-excel-report?" + data + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";
        window.open(urlReport, '_blank');
    }
</script>
@endpush
