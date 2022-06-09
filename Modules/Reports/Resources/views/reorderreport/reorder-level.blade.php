@extends('frontend.layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">
                                Reorder Level Report
                            </h4>
                        </div>
                        <button onclick="myFunction()" class="btn btn-primary"><i class="fa fa-bars"></i></button>
                    </div>
                </div>
            </div>
            <div class="col-sm-12" id="myDIV">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    @php
                        $type = request('type');
                        $brand = request('brand');
                    @endphp
                    <div class="iq-card-body">
                        <form method="get" id="inventorydb-form">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group er-input">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" value="medicines" name="type" id="js-medicines-intake-radio" {{ ($type == null || $type == 'medicines' ) ? "checked='checked'" : "" }} class="custom-control-input">
                                            <label class="custom-control-label" for="customRadio6">
                                                Medicines
                                            </label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" value="surgicals" name="type" id="js-surgicals-intake-radio" {{ ($type == 'surgicals') ? "checked='checked'" : "" }} class="custom-control-input">
                                            <label class="custom-control-label" for="customRadio7"> Surgical</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" alue="extra-items" name="type" id="js-extra-items-intake-radio" {{ ($type == 'extra-items') ? "checked='checked'" : "" }} class="custom-control-input">
                                            <label class="custom-control-label" for="customRadio3"> Extra Items</label>
                                        </div>
                                         <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" value="generic" name="brand" id="js-generic-intake-radio" {{ ($brand == null || $brand == 'generic' ) ? "checked='checked'" : "" }} class="custom-control-input">
                                            <label class="custom-control-label" for="customRadio1"> Generic
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" value="brand" name="brand" id="js-brand-intake-radio" {{ ($brand == 'brand') ? "checked='checked'" : "" }} class="custom-control-input">
                                            <label class="custom-control-label" for="customRadio2"> Brand
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-5">

                                    <select name="department" id="department" class="form-control department">
                                        <option value="%">--Select Department--</option>
                                        @if($hospital_department)
                                            @forelse($hospital_department as $dept)
                                                <option value="{{ isset($dept->fldcomp) ? $dept->fldcomp : "%" }}"> {{$dept->name}} ( {{$dept->branch_data ? $dept->branch_data->name : ""}} )</option>
                                            @empty
                                            @endforelse
                                        @endif
                                        {{-- @if($hospital_department)
                                            @forelse($hospital_department as $dept)
                                                <option value="{{ $dept->departmentData->fldcomp }}">{{ $dept->departmentData?$dept->departmentData->name:'' }} ({{ $dept->departmentData->branchData?$dept->departmentData->branchData->name:'' }})({{ $dept->departmentData->fldcomp }})</option>
                                        @empty

                                        @endforelse --}}
                                    {{-- @endif --}}
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group form-row">
                                        <div class="custom-control custom-checkbox custom-control-inline">
                                            <input type="checkbox" class="custom-control-input" value="1" name="alldept" id="js-extra-items-intake-check" {{ request('alldept') ? "checked='checked'" : "" }}>
                                            <label class="custom-control-label" for="customCheck5">All Dept</label>
                                        </div>&nbsp;
                                        <input type="text" name="search" value="{{ request('search') }}" class="form-control col-sm-7">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group text-right">
                                        <button class="btn btn-primary btn-action"><i class="fa fa-sync"></i>&nbsp;Refresh</button>
                                        {{-- <button data-url="{{ route('store.inventorydb.inventory') }}" class="btn btn-primary btn-sm-in pdf-btn" type="button"><i class="fa fa-code"></i>&nbsp;Pdf</button> --}}
                                    </div>
                                </div>
                            </div>
                        </form>
                        {{-- <div class="d-flex justify-content-center mt-3">
                            <button data-url="{{ route('store.inventorydb.inventory') }}" class="btn btn-primary rounded-pill pdf-btn" type="button">Sear</button> --}}
                            <!-- <a href="#" type="button" class="btn btn-primary rounded-pill">
                            Inventory</a> -->
                            {{-- &nbsp; --}}
                            <!--  <a href="#" type="button" class="btn btn-primary rounded-pill">
                            Export</a> -->
                            {{-- <button data-url="{{ route('store.inventorydb.export') }}" class="btn btn-primary rounded-pill pdf-btn" type="button">Export</button> --}}
                        {{-- </div> --}}
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <div class="table-responsive mt-2 table-container">
                            <table class="table table-hovered table-bordered  table-striped">
                                <thead class="thead-light">
                                <tr>
                                    <th>&nbsp;</th>
                                    <th>Generic Name</th>
                                    <th>Brand Name</th>
                                    <th>QTY</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $stock_lead_time = (\App\Utils\Options::get('stock_lead_time') != false) ? \App\Utils\Options::get('stock_lead_time') : 30;
                                    $safety_stock = (\App\Utils\Options::get('safety_stock') != false) ? \App\Utils\Options::get('safety_stock') : 60;
                                    $stock_available_color_code = (\App\Utils\Options::get('stock_available_color_code') != false) ? \App\Utils\Options::get('stock_available_color_code') : "#28a745";
                                    $stock_near_empty_color_code = (\App\Utils\Options::get('stock_near_empty_color_code') != false) ? \App\Utils\Options::get('stock_near_empty_color_code') : "#ffc107";
                                @endphp
                                @if(isset($inventories))
                                    @if(isset($inventories->toArray()['data']))
                                        @foreach($inventories->toArray()['data'] as $inventory)
                                            @php
                                                $dispensedQty = 0;
                                                $dispensedData = \App\PatDosing::select(\DB::raw('SUM(fldqtydisp-fldqtyret) as qnty'))
                                                                ->where('flditem',$inventory['fldbrandid'])
                                                                ->whereDate('fldtime_order', '>', \Carbon\Carbon::now()->subDays(30))
                                                                ->get();
                                                $dispensedQty = ($dispensedData[0]->qnty > $dispensedQty) ? $dispensedData[0]->qnty : $dispensedQty;
                                                if($dispensedQty > 0){
                                                    $reorderLevel = (($dispensedQty / 30) * $stock_lead_time) + $safety_stock;
                                                }else{
                                                    $reorderLevel = $safety_stock;
                                                }
                                                if ($type == 'surgicals') {
                                                    $inv = \App\SurgBrand::select('fldbrandid', 'fldbrand')->where('fldbrandid', $inventory['fldbrandid'])->first();
                                                } elseif ($type == 'extra-items') {
                                                    $inv = \App\ExtraBrand::select('fldbrandid', 'fldbrand')->where('fldbrandid', $inventory['fldbrandid'])->first();
                                                } else {
                                                    $inv = \App\MedicineBrand::select('fldbrandid', 'fldbrand')->where('fldbrandid', $inventory['fldbrandid'])->first();
                                                }
                                                $color = ($inv->qtysum() > $reorderLevel) ? $stock_available_color_code : $stock_near_empty_color_code;
                                            @endphp
                                            <tr>
                                                <td style="color: {{ $color }}">{{ $loop->iteration }}</td>
                                                <td style="color: {{ $color }}">{{ $inventory['fldbrandid'] }}</td>
                                                <td style="color: {{ $color }}">{{ $inventory['fldbrand'] }}</td>
                                                <td style="color: {{ $color }}">{{ $inv->qtysum() }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @endif
                                </tbody>
                            </table>
                            @if(isset($inventories))
                                @isset($inventories->toArray()['data'])
                                {{ $inventories->links() }}
                                @endisset
                            @endif
                            <div id="bottom_anchor"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@push('after-script')
    <script type="text/javascript">
        $(document).ready(function () {
            $('.pdf-btn').click(function () {
                var url = $(this).data('url') + '?' + $('#inventorydb-form').serialize();
                window.open(url, '_blank');
            })
            setTimeout(function () {
                $(".department").select2();

            }, 1500);

        });
    </script>
@endpush
