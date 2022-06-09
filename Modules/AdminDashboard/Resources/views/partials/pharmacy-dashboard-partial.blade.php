<div class="col-md-12 col-lg-12">
    <div class="iq-card">
        <div class="iq-card-header d-flex justify-content-between">
            <div class="iq-header-title">
                <h4 class="card-title">Pharmacy OP Sales</h4>
            </div>
            {{-- <select name="" class="float-right form-control col-3" id="change-view-pharmacy">
                <option value="op_sales">OP Sales</option>
                <option value="ip_sales">IP Sales</option>
            </select> --}}
            {{-- <select name="" class="pharmacy-status-count-change float-right form-control col-4">
                <option value="">Select</option>
                <option value="Day">Day</option>
                <option value="Month">Month</option>
                <option value="Year">Year</option>
            </select> --}}
            <select name="billingSet" class="opsales-billing-set-change float-right form-control col-4" onchange="opSalesByBilling()">
                <option value="">Select</option>
                @if($billingSet)
                    @forelse($billingSet as $set)
                        <option value="{{ $set->fldsetname }}">{{ $set->fldsetname }}</option>
                    @empty

                    @endforelse
                @endif
            </select>
        </div>
        <div class="iq-card-body">
            <div class="male-female-pie-chart-container">
                <div class="mb-3 mt-3" id="pharmacy-op-sales"></div>
            </div>
        </div>
    </div>
</div>

<div class="col-md-12 col-lg-12">
    <div class="iq-card">
        <div class="iq-card-header d-flex justify-content-between">
            <div class="iq-header-title">
                <h4 class="card-title">Pharmacy IP Sales</h4>
            </div>
            {{-- <select name="" class="pharmacy-status-count-change float-right form-control col-4">
                <option value="">Select</option>
                <option value="Day">Day</option>
                <option value="Month">Month</option>
                <option value="Year">Year</option>
            </select> --}}
            <select name="billingSet" class="ipsales-billing-set-change float-right form-control col-4" onchange="ipSalesByBilling()">
                <option value="">Select</option>
                @if($billingSet)
                    @forelse($billingSet as $set)
                        <option value="{{ $set->fldsetname }}">{{ $set->fldsetname }}</option>
                    @empty

                    @endforelse
                @endif
            </select>
        </div>
        <div class="iq-card-body">
            <div class="male-female-pie-chart-container">
                <div class="mb-3 mt-3" id="pharmacy-ip-sales"></div>
            </div>
        </div>
    </div>
</div>
