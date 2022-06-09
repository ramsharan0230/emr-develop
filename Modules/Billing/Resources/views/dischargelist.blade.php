@extends('frontend.layouts.master')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between align-items-center">
                    <div class="iq-header-title">
                        <h4 class="card-title tittle-resp"> Discharge Patient List</h4>
                    </div>
                    <input type="text" class="form-control" id="js-patient-global-search" placeholder="Search" style="width:35%;" >
                    <button class="btn btn-primary" id="js-toggle-filter">Hide Filter</button>
                    <a href="{{ route('discharge.dischargeCsv', Request::query()) }}" target="_blank" class="btn btn-primary">Excel</a>
                    <a href="{{ route('discharge.dischargePdf', Request::query()) }}" target="_blank" class="btn btn-primary">Pdf</a>
                </div>
                <div class="iq-card-body">
                    <div class="registration-list-filter border-bottom mb-3 pb-3" id="js-registration-list-filter">
                        <form>
                            <div class="form-row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Bill no.</label>
                                        <input type="text" name="billno" value="{{ request('billno') }}" placeholder="Bill No." class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>From Date</label>
                                        <input type="text" name="from_date" id="from_date" value="{{ request('from_date') }}" placeholder="YYYY-MM-DD" class="form-control nepaliDatePicker" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>To Date</label>
                                        <input type="text" name="to_date" id="to_date" value="{{ request('to_date') }}" placeholder="YYYY-MM-DD" class="form-control nepaliDatePicker" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Department</label>
                                        <select class="form-control" name="department">
                                            <option value="">--Select--</option>
                                            @foreach($departments as $department)
                                            <option value="{{ $department->flddept }}" {{ (request('department') == $department->flddept) ? "selected='selected'" : "" }}>{{ $department->flddept }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </div>
                            <div class="d-flex justify-content-center mt-3">
                                <button class="btn btn-primary rounded-pill">Filter</button>
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <th>S.N.</th>
                                    <th>Name </th>
                                    <th>Enc ID</th>
                                    
                                    <th>Bill No.</th>
                                    <th>Deposit</th>
                                    <th>Total</th>
                                    <th>Received Amount</th>
                                    <th>Payment Mode</th>
                                </tr>
                            </thead>
                            <tbody id="js-registration-list">
                                @foreach($patients as $patient)
                                <tr data-fldpatientval="{{ $patient->fldencounterval }}">
                                <?php $patient_detail = \Helpers::getDetailByEncounter($patient->fldencounterval); ?>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $patient_detail->patientInfo->fullname }}</td>
                                    <td>{{ $patient->fldencounterval }}</td>
                                    <td>{{ $patient->fldbillno }}</td>
                                    <td>{{ $patient->fldprevdeposit }}</td>
                                    <td>{{ $patient->flditemamt }}</td>
                                    <td>{{ $patient->fldreceivedamt }}</td>
                                    <td>{{ $patient->fldbilltype }}</td>
                                    
                                    

                                  
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $patients->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection


