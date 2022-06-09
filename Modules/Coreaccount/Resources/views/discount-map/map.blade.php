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
                                Discount Map
                            </h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <form action="{{ route('transaction.create.discount.ledger') }}" method="post">
                            @csrf
                            <div class="form-group form-row">
                                <label for="" class="col-1">Ledger</label>
                                <div class="col-4">
                                    <select name="ledger_id" id="ledger_id" class="form-control select2ledger_id">
                                        <option value="">Select</option>
                                        @if($ledgers)
                                            @foreach($ledgers as $ledger)
                                                <option value="{{ $ledger->AccountId }}">{{ $ledger->AccountName }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="form-group form-row">
                                <label for="" class="col-1">Discount</label>
                                <div class="col-4">
                                    <select name="discount_name" id="discount_name" class="form-control select2dis">
                                        <option value="">Select</option>
                                        @if($discounts)
                                            @foreach($discounts as $discount)
                                                <option value="{{ $discount->fldtype }}">{{ $discount->fldtype }}</option>
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


            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <form action="{{ route('transaction.create.general.ledger') }}" method="post">
                            @csrf
                            <div class="form-group form-row">
                                <label for="" class="col-1">Ledger</label>
                                <div class="col-4">
                                    <input type="hidden" name="type" value="tax"/>
                                    <select name="ledger_id" id="ledger_id" class="form-control select2led">
                                        <option value="">Select</option>
                                        @if($ledgers)
                                            @foreach($ledgers as $ledger)
                                                <option value="{{ $ledger->AccountId }}">{{ $ledger->AccountName }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <label for="" class="col-1">Tax</label>
                                <div class="col-4">
                                    <select name="discount_name" id="discount_name" class="form-control select2tax">
                                        <option value="">Select</option>
                                        @if($taxs)
                                            @foreach($taxs as $tax)
                                                <option value="{{ $discount->fldtype }}">{{ $discount->fldtype }}</option>
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

            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <form action="{{ route('transaction.create.general.ledger') }}" method="post">
                            @csrf
                            <div class="form-group form-row">
                            <input type="hidden" name="type" value="Deposit"/>
                                <label for="" class="col-1">Ledger for Deposit</label>
                                <div class="col-4">
                                    <select name="ledger_id" id="ledger_id" class="form-control select2deled">
                                        <option value="">Select</option>
                                        @if($ledgers)
                                            @foreach($ledgers as $ledger)
                                                <option value="{{ $ledger->AccountId }}">{{ $ledger->AccountName }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <input type="hidden" name="discount_name" value="Deposit"/>
                                </div>
                            </div>


                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <form action="{{ route('transaction.create.general.ledger') }}" method="post">
                            @csrf
                            <div class="form-group form-row">
                                <label for="" class="col-1">Ledger for Deposit Refund</label>
                                <div class="col-4">
                                <input type="hidden" name="type" value="DepositRefund"/>
                                    <select name="ledger_id" id="ledger_id" class="form-control select2refund">
                                        <option value="">Select</option>
                                        @if($ledgers)
                                            @foreach($ledgers as $ledger)
                                                <option value="{{ $ledger->AccountId }}">{{ $ledger->AccountName }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <input type="hidden" name="discount_name" value="Deposit Refund"/>
                                </div>
                            </div>


                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>SNo</th>
                                <th>Discount</th>
                                <th>Ledger</th>
                                <th>Ledger Acc No</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($mapped_data)
                                @foreach($mapped_data as $map)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $map->discount_name }}</td>
                                        <td>{{ $map->ledger?$map->ledger->AccountName:'' }}</td>
                                        <td>{{ $map->ledger?$map->ledger->AccountNo:'' }}</td>
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
@endsection

@push('after-script')
<script>
     $(document).ready(function () {
        $(".select2ledger_id").select2({

                });
                $(".select2dis").select2({

});
$(".select2led").select2({

});
$(".select2tax").select2({

});
$(".select2deled").select2({

});
$(".select2refund").select2({

});

     });
</script>

@endpush
