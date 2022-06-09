@extends('frontend.layouts.master')

@section('content')
<style>
    .res-table {
        width: 100%;
        max-height: unset;
        overflow: auto;
    }

</style>
    <!-- TOP Nav Bar END -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h3 class="card-title">
                                Purchase Return Form (Credit Note)
                            </h3>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <div class="row">
                            <div class="col-sm-4 col-lg-4">
                                <div class="form-group form-row">
                                    <label class="col-sm-4">Supplier</label>
                                    <div class="col-sm-8">
                                        <select id="supplier" class="form-control select2" name="supplier">
                                            <option value="">--Select--</option>
                                            @foreach($suppliers as $supplier)
                                                <option
                                                        value="{{ $supplier->fldsuppname }}">{{ $supplier->fldsuppname }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="col-sm-3 col-lg-3">
                                <div class="form-group form-row">
                                    <label class="col-sm-5">Ref Order No</label>
                                    <div class="col-sm-7">
                                        <select id="reference" class="form-control" name="reference">
                                            <option>--Select--</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3 col-lg-3">
                                <div class="form-group form-row">
                                    <label class="col-sm-4">Route</label>
                                    <div class="col-sm-8">
                                        <select id="route" class="form-control" name="route">
                                            <option value="">--Select--</option>
                                            <option value="Medicines">Medicines</option>
                                            <option value="Surgicals">Surgicals</option>
                                            <option value="Extra Items">Extra Items</option>
                                        </select>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="col-sm-4 col-lg-4">
                                <div class="form-group form-row">
                                    <label class="col-sm-4">Medicine</label>
                                    <div class="col-sm-8">
                                        <select id="medicine" class="form-control select2" name="medicine">
                                            <option value="">--Select--</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 col-lg-4">
                                <div class="form-group form-row">
                                    <label class="col-sm-4">Batch</label>
                                    <div class="col-sm-8">
                                        <select id="batch" class="form-control select2" name="batch">
                                            <option value="">--Select--</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="stockNo" id="stockNo" value="">
                            <div class="col-sm-4 col-lg-4">
                                <div class="form-group form-row">
                                    <label class="col-sm-4">Expiry</label>
                                    <div class="col-sm-8">
                                        <input readonly type="text" id="expiry" name="expiry"
                                               class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 col-lg-4">
                                <div class="form-group form-row">
                                    <label class="col-sm-4">Net Cost</label>
                                    <div class="col-sm-8">
                                        <input type="number" id="netcost" min="0" class="form-control" name="netcost"
                                               placeholder="0" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 col-lg-4">
                                <div class="form-group form-row">
                                    <label class="col-sm-4">Carry Cost</label>
                                    <div class="col-sm-8">
                                        <input type="number" id="carcost" min="0" class="form-control" name="carcost"
                                               placeholder="0">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 col-lg-4">
                                <div class="form-group form-row">
                                    <label class="col-sm-4">Vat</label>
                                    <div class="col-sm-8">
                                        <input readonly type="number" id="vatamt" min="0" class="form-control" name="vatamt"
                                               placeholder="0">
                                        <input type="hidden" id="vatamtclone" min="0" class="form-control"
                                               placeholder="0">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 col-lg-4">
                                <div class="form-group form-row">
                                    <label class="col-sm-4">Group Discount Amount</label>
                                    <div class="col-sm-8">
                                        <input readonly type="number" id="disamt" min="0" class="form-control" name="disamt"
                                               placeholder="0">
                                        <input type="hidden" id="disamtclone" min="0" class="form-control"
                                               placeholder="0">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 col-lg-4">
                                <div class="form-group form-row">
                                    <label class="col-sm-4">Cash Discount</label>
                                    <div class="col-sm-8">
                                        <input readonly type="number" id="cashdisamt" min="0" class="form-control" name="cashdisamt"
                                               placeholder="0">
                                        <input type="hidden" id="cashdisamtclone" min="0" class="form-control"
                                               placeholder="0">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 col-lg-4">
                                <div class="form-group form-row">
                                    <label class="col-sm-4">Quantity</label>
                                    <div class="col-sm-8">
                                        <input readonly type="text" id="qty" class="form-control" name="qty"
                                               placeholder="0">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 col-lg-4">
                                <div class="form-group form-row">
                                    <label class="col-sm-4">Bonus Quantity</label>
                                    <div class="col-sm-8">
                                        <input readonly type="text" id="bonusqty" class="form-control" name="bonusqty"
                                               placeholder="0">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 col-lg-4">
                                <div class="form-group form-row">
                                    <label class="col-sm-4">Return Reward Quantity</label>
                                    <div class="col-sm-8">
                                        <input  type="number" id="bonusretqty" class="form-control" name="bonusretqty"
                                               placeholder="0" onkeydown="if(event.key==='.'){event.preventDefault();}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 col-lg-4">
                                <div class="form-group form-row">
                                    <label class="col-sm-4">Return Quantity</label>
                                    <div class="col-sm-8">
                                        <input type="number" id="retqty" class="form-control" name="retqty"
                                               placeholder="0" onkeydown="if(event.key==='.'){event.preventDefault();}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-4">
                                <!-- <div class="form-row"> -->
                                    <div class="d-flex flex-row justify-content-start align-items-center">   
                                        <button class="btn btn-primary btn-sm-in" id="saveBtn" title="Save"><i
                                        class="fa fa-plus" aria-hidden="true"></i>&nbsp;Add</button>
                                    </div>
                                <!-- </div> -->
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <div class="res-table">
                            <table class="table table-bordered table-hover table-striped" id="return-table">
                                <thead class="thead-light">
                                <tr>
                                    <th>DateTime</th>
                                    <th>Stock No</th>
                                    <th>Batch</th>
                                    <th>Supplier</th>
                                    <th>Particulars</th>
                                    <th>Carry Cost</th>
                                    <th>Cash Discount</th>
                                    <th>Vat</th>
                                    <th>Return QTY</th>
                                    <th>Return Reward QTY</th>
                                    <th>Cost</th>
                                    <th>Reference</th>
                                    <th>User</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody id="returnform"></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <input type="text" id="js-stockreturn-input">
                            <button class="btn btn-primary" id="export_button"><i class="ri-check-line"></i>&nbsp;&nbsp;Export</button>
                            <button class="btn btn-success float-right" id="finalSave"><i class="ri-check-line"></i>&nbsp;&nbsp;Final Save</button>
                        </div>
                    </div>
                </div>

            </div>


        </div>
    </div>
@endsection

@push('after-script')
    <script src="{{asset('js/purchase_return.js')}}"></script>
@endpush
