@extends('frontend.layouts.master')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between align-items-center">
                    <div class="iq-header-title">
                        <h4 class="card-title tittle-resp"> Patient Bill List</h4>
                    </div>
                    <form name="patient_list" method="POST" action="{{route('account.list')}}">
                        @csrf
                        <input type="text" class="form-control" id="patient_id" name="patient_id" placeholder="Search" style="width:35%;">
                        <button class="btn btn-primary" id="js-toggle-filter">Hide Filter</button>
                    </form>
                    <!-- <a href="{{ route('discharge.dischargeCsv', Request::query()) }}" target="_blank" class="btn btn-primary">Excel</a>
                    <a href="{{ route('discharge.dischargePdf', Request::query()) }}" target="_blank" class="btn btn-primary">Pdf</a> -->
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
                            <tbody id="js-account-list">
                                @if($bill_patient)
                                @foreach($bill_patient as $bill_patient)
                                <tr data-fldpatientval="{{ $bill_patient['bill']->fldencounterval }}">
                                    <?php $patient_detail = \Helpers::getDetailByEncounter($bill_patient['bill']->fldencounterval); ?>
                                    <td>{{ $loop->iteration }}</td>
                                    <td></td>
                                    <td>{{ $bill_patient['bill']->fldencounterval }}</td>
                                    <td>{{ $bill_patient['bill']->fldbillno }}</td>
                                    <td>{{ $bill_patient['bill']->fldprevdeposit }}</td>
                                    <td>{{ $bill_patient['bill']->flditemamt }}</td>
                                    <td>{{ $bill_patient['bill']->fldreceivedamt }}</td>
                                    <td>{{ $bill_patient['bill']->fldbilltype }}</td>
                                    <td><button class="account_detail">Detail</button></td>
                                    <td class="detail-bill">
                                        @if($bill_patient['billdetail'])
                                        <table class="js-account-detail-list" style="display:none;">
                                            <tr>
                                                <th>Enc ID</th>
                                                <th>Billing Mode </th>
                                                <th>Item Type</th>

                                                <th>Item No.</th>
                                                <th>Item Name</th>
                                                <th>Item Rate</th>
                                                <th>Item Quantity</th>

                                            </tr>
                                            <tbody>
                                                @foreach($bill_patient['billdetail'] as $bd)
                                                <tr>
                                                    <td> {{ $bd->fldencounterval   }}</td>
                                                    <td> {{ $bd->fldbillingmode  }}</td>
                                                    <td> {{ $bd->flditemtype  }}</td>
                                                    <td> {{ $bd->flditemno  }}</td>
                                                    <td> {{ $bd->flditemname  }}</td>
                                                    <td> {{ $bd->flditemrate  }}</td>
                                                    <td> {{ $bd->flditemqty  }}</td>

                                                </tr>

                                                @endforeach
                                            </tbody>

                                        </table>

                                        @endif
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
<div class="modal fade" id="account_detail_modal" tabindex="-1" role="dialog" aria-labelledby="account_detail_modal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" id="detail-content">

        </div>
    </div>
</div>

@endsection
@push('after-script')
<script>
 $(document).on('click', '.account_detail', function(event) {
     var data = $(this).parent('tr').find('.detail-bill').html();
$('#detail-content').html(data);
     $('.account_detail_modal').show();

 });
</script>
@endpush
