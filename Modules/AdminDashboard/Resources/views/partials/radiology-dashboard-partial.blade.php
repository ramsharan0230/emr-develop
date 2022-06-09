<div class="col-md-12 col-lg-12">
    <div class="iq-card">
        <div class="iq-card-header d-flex justify-content-between">
            <div class="iq-header-title">
                <h4 class="card-title">Radiology Status</h4>
            </div>
            {{-- <select name="" class="float-right form-control col-4" id="change-view-radiology">
                <option value="">Select View</option>
                <option value="order_type">Order Type</option>
                <option value="order_status">Order Status</option>
                <option value="radio_newold_patient">New/Old Patient</option>
                <option value="radio_inpatient_outpatient">Inpatient/Outpatient</option>
            </select> --}}
            <select name="" class="radiology-status-count-change float-right form-control col-4" onchange="changeRadiologyStatus()">
                <option value="">Select</option>
                <option value="Day">Day</option>
                <option value="Month">Month</option>
                <option value="Year">Year</option>
            </select>
        </div>
        {{-- <div class="iq-card-header d-flex justify-content-between">
            <div class="iq-header-title">
                <h4 class="card-title">{{ $radiologyNewCount }} New /{{ $radiologyOldCount }} Old Patient</h4>
            </div>
            <div class="iq-header-title">
                <h4 class="card-title">{{ $radiologyOpdCount }} Outpatient /{{ $radiologyIpdCount }} Inpatient</h4>
            </div>
        </div> --}}
        <div class="iq-card-body">
            <div class="male-female-pie-chart-container">
                <div class="mb-3 mt-3" id="radiology-status"></div>
            </div>
        </div>
    </div>
</div>

<div class="col-md-12 col-lg-12">
    <div class="iq-card">
        <div class="iq-card-header d-flex justify-content-between">
            <div class="iq-header-title">
                <h4 class="card-title">Radiology Order</h4>
            </div>
            <select name="" class="radiology-order-count-change float-right form-control col-4" onchange="changeRadiologyOrder()">
                <option value="">Select</option>
                <option value="Day">Day</option>
                <option value="Month">Month</option>
                <option value="Year">Year</option>
            </select>
        </div>
        <div class="iq-card-body">
            <div class="male-female-pie-chart-container">
                <div class="mb-3 mt-3" id="radiology-order"></div>
            </div>
        </div>
    </div>
</div>
