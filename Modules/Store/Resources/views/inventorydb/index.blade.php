@extends('frontend.layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">
                                Inventory DB Report
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
                                <div class="col-lg-5 col-md-6">
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
                                            <input type="radio" value="extra-items" name="type" id="js-extra-items-intake-radio" {{ ($type == 'extra-items') ? "checked='checked'" : "" }} class="custom-control-input">
                                            <label class="custom-control-label" for="customRadio3"> Extra Items</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-2 col-md-5">

                                    <select name="department" id="department" class="form-control department">
                                        <option value="%">--Select Department--</option>
                                        @if($hospital_department)
                                            @forelse($hospital_department as $dept)
                                                @if($dept->departmentData && $dept->departmentData->fldcomp)
                                                <option value="{{ $dept->departmentData->fldcomp }}">{{ $dept->departmentData?$dept->departmentData->name:'' }} ({{ $dept->departmentData->branchData?$dept->departmentData->branchData->name:'' }})({{ $dept->departmentData->fldcomp }})</option>
                                                @endif
                                        @empty

                                        @endforelse
                                    @endif
                                    <!-- <option value="Male"></option> -->
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-4">
                                    <div class="form-group er-input">
                                        <div class="custom-control custom-checkbox custom-control-inline">
                                            <input type="checkbox" class="custom-control-input" value="1" name="alldept" id="js-extra-items-intake-check" {{ request('alldept') ? "checked='checked'" : "" }}>
                                            <label class="custom-control-label" for="customCheck5">All Dept</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-5">
                                    <button class="btn btn-primary btn-sm-in"><i class="fa fa-sync"></i>&nbsp;Refresh</button>
                                </div>

                            </div>
                        </form>
                        <div class="d-flex justify-content-center mt-3">
                            <button data-url="{{ route('store.inventorydb.inventory') }}" class="btn btn-primary rounded-pill pdf-btn" type="button">Inventory</button>
                            <!-- <a href="#" type="button" class="btn btn-primary rounded-pill">
                            Inventory</a> -->
                            &nbsp;
                            <!--  <a href="#" type="button" class="btn btn-primary rounded-pill">
                            Export</a> -->
                            <button data-url="{{ route('store.inventorydb.export') }}" class="btn btn-primary rounded-pill pdf-btn" type="button">Export</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <div class="table-responsive mt-2 table-container">
                            <table class="table table-hovered table-bordered  table-striped table-sm inventorydb">
                                <thead class="thead-light">
                                    <tr>
                                        <!-- <th>&nbsp;</th> -->
                                        <th>Generic Name</th>
                                        <th>Batch</th>
                                        <th>Comp</th>
                                        <th>Brand Name</th>
                                        <th>Supplier</th>
                                        <th>Type</th>
                                        <th>QTY</th>
                                        <th>CP</th>
                                        <th>SP</th>
                                        <th>Expiry Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(isset($inventories))
                                    @foreach($inventories as $inventory)

                                        <tr>
                                            <!-- <td>{{ $loop->iteration }}</td> -->
                                            <td>{{ $inventory->fldstockid }}</td>
                                            <td>{{ $inventory->fldbatch }}</td>
                                            <td>{{ $inventory->fldcomp }}</td>
                                            <td>{{ $inventory->fldbrand }}</td>
                                            <td>{{ $inventory->fldsuppname }}</td>
                                            <td>{{ $inventory->fldcategory }}</td>
                                            <td>{{ $inventory->fldqty }}</td>
                                            <td>{{ $inventory->flsuppcost }}</td>
                                            <td>{{ $inventory->fldsellpr }}</td>
                                            <td>{{ date('Y-m-d', strtotime($inventory->fldexpiry)) }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
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
            $('.inventorydb').DataTable();
        });
    </script>
@endpush
