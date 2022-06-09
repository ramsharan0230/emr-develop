@extends('frontend.layouts.master-stock')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card">
                    <div class="iq-card-body">
                        <div class="table-responsive">
                            <form action="" method="get">
                                <div class="form-group row">
                                    <div class="col-2">
                                        <input type="text" name="itemStockName" id="itemStockName" placeholder="Medicine Name" class="form-control" value="{{ ( Request::get('itemStockName')  != '' )?Request::get('itemStockName'):'' }}">
                                    </div>
                                    <div class="col-sm-4 form-row">
                                        <label for="department" class="col-lg-4 col-sm-5">Dept</label>
                                        <select name="department" id="departmentWiseDataDisplay" class="form-control col-6">
                                            @php
                                                $hospital_department = Helpers::getDepartmentAndComp();
                                            @endphp
                                            <option value="">Select Department</option>
                                            @if($hospital_department)
                                                @forelse($hospital_department as $dept)
                                                    <option value="{{ $dept->fldcomp }}">{{ $dept->name }} ({{ $dept->branchData?$dept->branchData->name:'' }})</option>
                                                @empty

                                                @endforelse
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-1">
                                        <input type="radio" name="stockType" id="sales" value="sales" {{ !Request::get('stockType') || Request::get('stockType')  == 'sales' ? 'checked':'' }}>
                                        <label for="sales">Sales</label>
                                    </div>
                                    <div class="col-1">
                                        <input type="radio" name="stockType" id="purchase" value="purchase" {{ Request::get('stockType')  == 'purchase' ? 'checked':'' }}>
                                        <label for="purchase">Purchase</label>
                                    </div>
                                    <div class="col-2">
                                        <button type="submit" class="btn btn-primary"><i class="fas fa-sync"></i></button>
                                    </div>
                                </div>
                            </form>
                            <table class="table table-sm table-striped">
                                <thead>
                                <tr>
                                    <th style="width: 580px">Item Name</th>
                                    <th class="text-center">Opening Stock</th>
                                    <th class="text-center"><i class="fas fa-arrow-up text-success"></i> Purchase</th>
                                    {{--        <th><i class="fas fa-arrow-down text-danger"></i> Sales/Transaction</th>--}}
                                    <th class="text-center">Remaining</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($tableEntry)
                                    @forelse($tableEntry as $entry)
                                        <tr>
                                            <td>{{ $entry->Entry ? $entry->Entry->fldstockid : '' }}</td>
                                            @php
                                                //sales quantity
                                                $sales = $entry->EntryByStockName->patBillingByName && isset($entry->EntryByStockName->patBillingByName->qty) ? $entry->EntryByStockName->patBillingByName->qty : 0;
                                                //opening stock, current stock + sales
                                                $currentQty = $entry->EntryByStockName ? $entry->EntryByStockName->where('fldstockid', $entry->fldstockid)->where('fldcomp', $entry->fldcomp)->sum('fldqty'):0;
                                                $openingStock =  $currentQty + $sales;
                                                // purchase
                                                $purchase = ($entry) ? $entry->where('fldstockid', $entry->fldstockid)->where('fldcomp', $entry->fldcomp)->sum('fldtotalqty'):0;

                                                $bulkSale = $entry->EntryByStockName && count($entry->EntryByStockName->bulkSale) ? $entry->EntryByStockName->bulkSale->sum('fldqtydisp') - $entry->EntryByStockName->bulkSale->sum('fldqtyret'):0;

                                                $fldcompqty = $entry->EntryByStockName && count($entry->EntryByStockName->adjustment) && isset($entry->EntryByStockName->adjustment->fldcompqty) ? $entry->EntryByStockName->adjustment->sum('fldcompqty'):0;
                                                $fldcurrqty = $entry->EntryByStockName && count($entry->EntryByStockName->adjustment) && isset($entry->EntryByStockName->adjustment->fldcurrqty) ? $entry->EntryByStockName->adjustment->sum('fldcurrqty'):0;

                                                $adjustment = $entry->EntryByStockName && count($entry->EntryByStockName->adjustment) ? $fldcompqty - $fldcurrqty:0;

                                                if ($departmentComp != 0){
                                                    $transferFrom = $entry->EntryByStockName && count($entry->EntryByStockName->transfer) ? $entry->EntryByStockName->transfer->where('fldfromcomp', $departmentComp)->sum('fldqty'):0;
                                                    $transferTo = $entry->EntryByStockName && count($entry->EntryByStockName->transfer) ? $entry->EntryByStockName->transfer->where('fldtocomp', $departmentComp)->sum('fldqty'):0;
                                                //curqty - (purqty + recvqty) + (salqty + bulqty + sentqty + adjqty)
                                                }else{
                                                    $transferFrom = 0;
                                                    $transferTo = 0;
                                                }
                                            @endphp
                                            {{--opening stock--}}
                                            <td class="text-center">{{ $openingStock + $transferTo - $transferFrom + $adjustment }}</td>
                                            {{--purchase--}}
                                            <td class="text-center">{{ $purchase }}</td>
                                            {{--sales/transaction--}}
                                            {{--                <td class="text-center">{{ $sales }}</td>--}}
                                            {{--remaining--}}
                                            @php
                                                $remaining = $currentQty + $purchase;
                                            @endphp
                                            <td class='{{ $purchase > $sales ?"text-success":"text-danger" }} text-center'>{{ $remaining }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5">No data found.</td>
                                        </tr>
                                    @endforelse
                                @endif
                                </tbody>
                            </table>
                            <div class="form-group padding-none">
                                <div class="form-inner">
                                    {{ $tableEntry->links('vendor.pagination.bootstrap-4') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
