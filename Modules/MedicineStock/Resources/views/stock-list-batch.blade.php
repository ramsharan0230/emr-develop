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
                                                    <option value="{{ $dept->fldcomp }}" {{ app('request')->input('department') == $dept->fldcomp ?"selected":'' }}>{{ $dept->name }} ({{ $dept->branchData?$dept->branchData->name:'' }}) ({{ $dept->fldcomp }})</option>
                                                @empty

                                                @endforelse
                                            @endif
                                        </select>
                                    </div>
                                    {{--<div class="col-1">
                                        <input type="radio" name="stockType" id="sales" value="sales" {{ !Request::get('stockType') || Request::get('stockType')  == 'sales' ? 'checked':'' }}>
                                        <label for="sales">Sales</label>
                                    </div>
                                    <div class="col-1">
                                        <input type="radio" name="stockType" id="purchase" value="purchase" {{ Request::get('stockType')  == 'purchase' ? 'checked':'' }}>
                                        <label for="purchase">Purchase</label>
                                    </div>--}}
                                    <div class="col-2">
                                        <button type="submit" class="btn btn-primary"><i class="fas fa-sync"></i></button>
                                    </div>
                                </div>
                            </form>
                            <table class="table table-sm table-striped">
                                <thead>
                                <tr>
                                    <th style="width: 580px">Item Name</th>
                                    <th style="width: 280px">Batch</th>
                                    <th class="text-center">Opening Stock</th>
                                    {{--        <th><i class="fas fa-arrow-up text-success"></i> Purchase</th>--}}
                                    <th class="text-center"><i class="fas fa-arrow-down text-danger"></i> Sales/Transaction</th>
                                    <th class="text-center"><i class="fas fa-arrow-up text-success"></i> Purchase</th>
                                    <th class="text-center"> Expire</th>
                                    <th class="text-center">Remaining</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($tableEntry)
                                    @php
                                        $stockArray = [];
                                    @endphp
                                    @forelse($tableEntry as $entry)
                                        @php
                                            $tableEntryStockId = $entry->medicine->fldstockid;
                                        @endphp
                                        @if(!in_array($entry->medicine->fldstockid,$stockArray))
                                            @php
                                                array_push($stockArray, $entry->medicine->fldstockid);
                                            @endphp
                                            <tr>
                                                <td>{{ $entry->medicine ? $entry->medicine->fldstockid : '' }}</td>
                                                <td>{{ $entry->medicine ? $entry->medicine->fldbatch : '' }}</td>
                                                @php
                                                    //sales quantity
                                                    $sales = $entry?$entry->flditemqty:0;

                                                    //opening stock, current stock + sales
                                                    $currentQty = $entry->medicine ? $entry->medicine->fldqty:0;
                                                    $openingStock =  $currentQty + $sales;

                                                    /*$bulkSale = $entry->medicine && count($entry->medicine->bulkSale) ? $entry->medicine->bulkSale->fldqtydisp - $entry->medicine->bulkSale->fldqtyret:0;*/
                                                    //dd($entry->medicine->multiplePurchase);
$purchase = ($entry) ? $entry->medicine->multiplePurchase->where('fldstockid', $tableEntryStockId)->where('fldbatch', $entry->medicine->fldbatch)->sum('fldtotalqty'):0;
                                                    $adjustment = $entry->adjustment && count($entry->medicine->adjustment) ? $entry->medicine->adjustment->fldcompqty - $entry->adjustment->fldcurrqty:0;
                                                    if ($departmentComp != 0){
                                                        $transferFrom = $entry->transfer && count($entry->medicine->transfer) ? $entry->medicine->transfer->where('fldfromcomp', $departmentComp)->fldqty:0;
                                                        $transferTo = $entry->transfer && count($entry->medicine->transfer) ? $entry->medicine->transfer->where('fldtocomp', $departmentComp)->fldqty:0;
                                                    //curqty - (purqty + recvqty) + (salqty + bulqty + sentqty + adjqty)
                                                    }else{
                                                        $transferFrom = 0;
                                                        $transferTo = 0;
                                                    }
                                                @endphp
                                                {{--opening stock--}}
                                                <td class="text-center">{{ $openingStock + $transferTo - $transferFrom + $adjustment }}</td>
                                                {{--purchase--}}
                                                {{--                <td class="text-center">{{ $purchase }}</td>--}}
                                                {{--sales/transaction--}}
                                                <td class="text-center">{{ $sales }}</td>
                                                <td class="text-center">{{ $purchase }}</td>
                                                <td class="text-center">
                                                    @if(strtotime($entry->medicine->fldexpiry) > strtotime('1 day') && strtotime($entry->medicine->fldexpiry) < strtotime('-3 months ago'))
                                                        <a href="javascript:;" style="padding: 5px; background-color: #238de7;" title="Less than 3 months"></a>
                                                    @elseif(strtotime($entry->medicine->fldexpiry) > strtotime('-6 months ago'))
                                                        <a href="javascript:;" style="padding: 5px; background-color: #08e794;" title="Greater than 6 months"></a>
                                                    @else
                                                        <a href="javascript:;" style="padding: 5px; background-color: #ff1700;" title="Expired"></a>
                                                    @endif
                                                </td>
                                                {{--remaining--}}
                                                @php
                                                    $remaining = $currentQty+$purchase;
                                                @endphp
                                                <td class='text-center'>{{ $remaining }}</td>
                                            </tr>
                                        @endif
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
                                    @if($tableEntry)
                                        {{ $tableEntry->links('vendor.pagination.bootstrap-4') }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
