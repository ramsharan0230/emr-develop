@extends('frontend.layouts.master')

@section('content')
<input type="hidden" id="js-dispensinglist-currentUser-input" value="{{ \App\Utils\Helpers::getCurrentUserName() }}">
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 mt-2">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h3 class="card-title">
                            Dispensing List
                        </h3>
                    </div>
                    @include('menu::toggleButton')
                </div>
            </div>
        </div>
        <div class="col-sm-12 mt-2">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <form id="js-dispenseinglist-form">
                        <div class="row">
                            <div class="col-sm-4 col-lg-3">
                                <div class="form-group form-row align-items-center">
                                    <label for="" class="col-sm-4">From:</label>
                                    <div class="col-sm-8">
                                        <input type="text" value="{{ $date }}" class="form-control nepaliDatePicker" name="fromdate" id="js-dispenseinglist-fromdate-input" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3 col-lg-2">
                                <div class="form-group form-row align-items-center">
                                    <label for="" class="col-sm-4">To:</label>
                                    <div class="col-sm-8">
                                        <input type="text" value="{{ $date }}" class="form-control nepaliDatePicker" name="todate" id="js-dispenseinglist-todate-input" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-5 col-lg-4">
                                @foreach($dispensingDepartments as $dispensing)
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="dispensingDepartment" onclick="getDepartments('{{ $dispensing }}')" id="js-check-{{ $dispensing }}" value="{{ $dispensing }}" class="custom-control-input" @if($dispensing=='InPatient' ) checked @endif>
                                    <label class="custom-control-label" for="js-check-{{ $dispensing }}"> {{ $dispensing }} </label>
                                </div>
                                @endforeach
                                <button type="button" id="js-dispensinglist-refresh-btn" class="btn btn-primary btn-sm-in"><i class="fa fa-sync" aria-hidden="true"></i></button>
                            </div>
                            <div class="col-sm-4 col-lg-3">
                                <div class="form-group form-row align-items-center">
                                    <label for="" class="col-sm-5">Department:</label>
                                    <div class="col-sm-7">
                                        <select class="form-control" id="js-dispensinglist-department-select" name="currentlocation">
                                            <option value="">--Select--</option>
                                            @foreach($departments as $department)
                                            <option value="{{ $department->flddept }}">{{ $department->flddept }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 col-lg-3">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="fldorder" value="UseOwn" class="custom-control-input" >
                                    <label class="custom-control-label">UseOwn</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="fldorder" value="Request" class="custom-control-input" checked>
                                    <label class="custom-control-label">Request</label>
                                </div>
                            </div>
                            <div class="col-sm-4 col-lg-2">
                                <div class="form-group form-row align-items-center">
                                    <label for="" class="col-sm-4">Status:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" id="js-dispensinglist-status-select" name="fldlevel">
                                            <option value="Requested">Requested</option>
                                            <option value="Dispensed">Dispensed</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-5 col-lg-4">
                                <div class="form-group form-row align-items-center">
                                    <label for="" class="col-sm-4">Billing Mode:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" id="js-dispensinglist-bilingmode-select" name="fldbillingmode">
                                            <option value="">--Select--</option>
                                            @foreach($billingsets as $b)
                                            <option value="{{ $b }}">{{ $b }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 col-lg-3">
                                <div class="form-group form-row align-items-center">
                                    <label for="" class="col-sm-5">BranchId:</label>
                                    <div class="col-sm-7">
                                        <select class="form-control" id="js-dispensinglist-compid-select" name="fldcompid">
                                            <option value="">--Select--</option>
                                            @foreach ($userComputers as $comp)
                                            <option value="{{ $comp->fldcomp }}">{{ $comp->name }} ({{ ($comp->branchData) ? $comp->branchData->name : "" }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-12 leftdiv">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <div class="res-table table-sticky-th">
                        <table class="table table-bordered table-hover table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <th>&nbsp;</th>
                                    <th>Location</th>
                                    <th>Encounter</th>
                                    <th>Patient Name</th>
                                </tr>
                            </thead>
                            <tbody id="js-dispensinglist-patientlist-tbody"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-md-12 rightdiv">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <div class="col-sm-12">
                        <div class="form-row">
                            <div class="col-sm-6">
                                <div class="form-group form-row">
                                    <label class="col-sm-4 pr-0">Full Name</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="js-dispensinglist-fullname-input" readonly>
                                    </div>
                                </div>
                                <div class="form-group form-row">
                                    <label class="col-sm-4">Address</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="js-dispensinglist-address-input" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group form-row">
                                    <label class="col-sm-3  ">Gender</label>
                                    <div class="col-sm-4 col-lg-4">
                                        <input type="text" class="form-control" id="js-dispensinglist-gender-input" readonly>
                                    </div>
                                    <div class="col-sm-5 col-lg-5">
                                        <button type="button" class="btn btn-primary" id="js-dispenseinglist-dispensigform-buttom">Dispensing Form</button>
                                    </div>
                                </div>
                                <div class="form-group form-row">
                                    <label class="col-sm-3 ">Location</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="js-dispensinglist-location-input" readonly>
                                        <input type="hidden" name="patient_encounter" id="patient_encounter">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group form-row">
                                    
                                    <div class="col-sm-5 col-lg-5">
                                        <button type="button" class="btn btn-primary" id="js-dispenseinglist-dispensigform-print">Print</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="res-table table-sticky-th"> 
                            <table class="table table-bordered table-hover table-striped">
                                <thead class="thead-light">
                                    <tr>
                                        <th>&nbsp;</th>
                                        <th>DateTime</th>
                                        <th>Route</th>
                                        <th>Particular</th>
                                        <th>Dose</th>
                                        <th>Freq</th>
                                        <th>Day</th>
                                        <th>Qty</th>
                                        <th>&nbsp;</th>
                                        <th>Sender</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="js-dispensinglist-medicinelist-tbody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="js-dispensinglist-change-data">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="js-dispensinglist-modal-title">Update <span id="js-dispensinglist-modal-span"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="js-dispensinglist-modal-type-input">
                <input type="hidden" id="js-dispensinglist-modal-fldid-input">
                <input type="text" class="form-control" id="js-dispensinglist-modal-qty-input">
                <select class="form-control" id="js-dispensinglist-modal-freq-input" style="display: none;" disabled>>
                    <option value="">--Select--</option>
                    @foreach(\App\Utils\Helpers::getFrequencies() as $frequency)
                    <option value="{{ $frequency }}">{{ $frequency }}</option>
                    @endforeach
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="changeQuantity.saveData();">Save changes</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('after-script')
<script src="{{asset('js/dispensing_form.js')}}"></script>
<script type="text/javascript">
    $(document).on('click', '#js-dispenseinglist-dispensigform-print', function () {
        var encounter = $('#patient_encounter').val();
        if(encounter == ''){
            alert('Please select patient first');
            return false;
        }
        var urlReport = baseUrl + "/dispensingList/export-dispensed-medicines?encounter=" + encounter + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";


        window.open(urlReport, '_blank');

    });
</script>
@endpush