@extends('frontend.layouts.master') @section('content')
    <div class="container-fluid">
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-block">
                <button type="button" class="close text-black-50 float-right" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
            </div>
        @endif

        @if ($message = Session::get('error'))
            <div class="alert alert-danger alert-block">
                <button type="button" class="close text-black-50 float-right" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
            </div>
        @endif
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex ">
                        <div class="iq-header-title col-sm-8 p-0">
                            <h4 class="card-title">
                                Account Map
                            </h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <form action="{{ route('account.setting.add') }}" method="post">
                            @csrf

                            <div class="form-group form-row">
                                <label for="" class="col-2">Doctor Tax</label>
                                <div class="col-4">
                                    <select name="ledger_tax_doctor_fraction" id="doctor" class="form-control select2">
                                        <option value="">Select</option>
                                        @if($ledgers)
                                            @foreach($ledgers as $ledger)
                                                <option value="{{ $ledger->AccountId }}" {{ Options::get('ledger_tax_doctor_fraction') == $ledger->AccountId ? 'selected' :'' }}>{{ $ledger->AccountName }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <label for="" class="col-2">Default Discount</label>
                                <div class="col-4">
                                    <select name="default_discount" id="default_discount" class="form-control select2">
                                        <option value="">Select</option>
                                        @if($ledgers)
                                            @foreach($ledgers as $ledger)
                                                <option value="{{ $ledger->AccountNo }}" {{ Options::get('default_discount') == $ledger->AccountNo ? 'selected' :'' }}>{{ $ledger->AccountName }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <label for="" class="col-2">Default Cash In hand</label>
                                <div class="col-4">
                                    <select name="default_cash_in_hand" id="default_cash_in_hand" class="form-control select2">
                                        <option value="">Select</option>
                                        @if($ledgers)
                                            @foreach($ledgers as $ledger)
                                                <option value="{{ $ledger->AccountNo }}" {{ Options::get('default_cash_in_hand') == $ledger->AccountNo ? 'selected' :'' }}>{{ $ledger->AccountName }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('after-script')

@endpush
