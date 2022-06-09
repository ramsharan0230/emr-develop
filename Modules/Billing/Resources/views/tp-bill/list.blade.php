@extends('frontend.layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between align-items-center">
                        <div class="iq-header-title">
                            <h4 class="card-title tittle-resp"> TP Bill List</h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <div class="registration-list-filter border-bottom mb-3 pb-3" id="js-registration-list-filter">
                            <form id="form-tp-bill-filter">
                                <div class="form-row">

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Encounter no.</label>
                                            <input type="text" name="encounter" value="{{ request('encounter') }}" placeholder="Encounter No." class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Bill no.</label>
                                            <input type="text" name="billno" value="{{ request('billno') }}" placeholder="Bill No." class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>From Date</label>
                                            <input type="text" id="from_date" value="{{ request('from_date') }}" placeholder="YYYY-MM-DD" class="form-control nepaliDatePicker" autocomplete="off" readonly>
                                            <input type="hidden" name="eng_from_date" id="eng_from_date">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>To Date</label>
                                            <input type="text" id="to_date" value="{{ request('to_date') }}" placeholder="YYYY-MM-DD" class="form-control nepaliDatePicker" autocomplete="off" readonly>
                                            <input type="hidden" name="eng_to_date" id="eng_to_date">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group mt-4">
                                            <button type="submit" class="btn btn-primary btn-action">Filter</button>
                                            <button type="button" class="btn btn-primary btn-action" onclick="exportTpBill()">Export</button>
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped">
                                <thead class="thead-light">
                                <tr>
                                    <th>S.N.</th>
                                    <th>Name</th>
                                    <th>Enc ID</th>
                                    <th>Bill No.</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody id="js-tp-bill-list">
                                @if($tpBillList)
                                    @foreach($tpBillList as $bill)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $bill->encounter && $bill->encounter->patientInfo ? $bill->encounter->patientInfo->fullname : '' }}</td>
                                            <td>{{ $bill->fldencounterval }}</td>
                                            <td>{{ $bill->fldtempbillno }}</td>
                                            <td>
                                                <a href="javascript:;" onclick="getTpBillData('{{ $bill->fldtempbillno }}', '{{ $bill->encounter && $bill->encounter->patientInfo ? $bill->encounter->patientInfo->fullname : '' }}')"><i class="fa fa-list"></i></a> |
                                                <a href="javascript:;" onclick="printTpBill('{{ $bill->fldtempbillno }}', '{{ $bill->fldencounterval }}')"><i class="fas fa-print"></i></a>
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


    <div class="modal fade tp_bill_detail_modal" id="tp_bill_detail_modal" tabindex="-1" role="dialog" aria-labelledby="tp_bill_detail_modal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>TP Bill of <span class="tp-modal-title"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Encounter/Bill Number: <strong class="modal-tp-bill-number"></strong></p>

                    <table class="table-sm table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>SNo.</th>
                            <th>Type</th>
                            <th>Name</th>
                            <th>Amount</th>
                        </tr>
                        </thead>
                        <tbody class="tp-bill-modal-body"></tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
@endsection
@push('after-script')
    <script>
        $(function ($) {
            $('#to_date').val(AD2BS('{{Request::get('eng_to_date')?Request::get('eng_to_date'):date('Y-m-d')}}'));
            $('#from_date').val(AD2BS('{{Request::get('eng_from_date')?Request::get('eng_from_date'):date('Y-m-d')}}'));
            $('#eng_to_date').val(BS2AD($('#to_date').val()));
            $('#eng_from_date').val(BS2AD($('#from_date').val()));
            $('#form-tp-bill-filter').submit(function () {
                $('#eng_to_date').val(BS2AD($('#to_date').val()));
                $('#eng_from_date').val(BS2AD($('#from_date').val()));
            });
        });

        function getTpBillData(tpBillNumber, patientName) {
            $.ajax({
                url: "{{ route('tp.bill.list.items') }}",
                type: "POST",
                data: {tpBillNumber: tpBillNumber},
                dataType: "json",
                success: function (response) {
                    $('.tp-modal-title').empty().text(patientName)
                    $('.modal-tp-bill-number').empty().text(response.encounter + ' (' + response.billNumber + ')')
                    $('.tp-bill-modal-body').empty().html(response.html)
                    $('#tp_bill_detail_modal').modal('show');
                }
            });
        }

        function printTpBill(tpBillNumber, encounter_id) {
            var params = {
                tpBillNumber: tpBillNumber,
                encounter_id:encounter_id
            };
            var queryString = $.param(params);

            window.open("{{ route('tp.bill.invoice.items') }}?" + queryString, '_blank');
        }

        function exportTpBill() {
            var params = $("#form-tp-bill-filter").serialize();
            var queryString = $.param(params);
            window.open("{{ route('tp.bill.invoice.export') }}?" + params, '_blank');
        }

    </script>
@endpush
