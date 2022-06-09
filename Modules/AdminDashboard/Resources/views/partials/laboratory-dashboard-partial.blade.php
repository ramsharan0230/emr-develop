<div class="col-md-12 col-lg-12">
    <div class="iq-card">
        <div class="iq-card-header d-flex justify-content-between">
            <div class="iq-header-title">
                <h4 class="card-title">Lab Status</h4>
            </div>
            {{-- <select name="" class="float-right form-control col-4" id="change-view-lab">
                <option value="">Select View</option>
                <option value="order_type">Order Type</option>
                <option value="order_status">Order Status</option>
                <option value="lab_newold_patient">New/Old Patients</option>
            </select> --}}
            <select name="" class="lab-status-count-change float-right form-control col-4" onchange="changelabStatus()">
                <option value="">Select</option>
                <option value="Day">Day</option>
                <option value="Month">Month</option>
                <option value="Year">Year</option>
            </select>
        </div>
        {{-- <div class="iq-card-header d-flex justify-content-between">
            <div class="iq-header-title">
                <h4 class="card-title">{{ $labNewCount }} New /{{ $labOldCount }} Old Patient</h4>
            </div>
        </div> --}}
        <div class="iq-card-body">
            <div class="male-female-pie-chart-container">
                <div class="mb-3 mt-3" id="lab-status"></div>
            </div>
        </div>
    </div>
</div>

<div class="col-md-12 col-lg-12">
    <div class="iq-card">
        <div class="iq-card-header d-flex justify-content-between">
            <div class="iq-header-title">
                <h4 class="card-title">Lab Order</h4>
            </div>
            <select name="" class="lab-order-count-change float-right form-control col-4" onchange="changeLabOrder()">
                <option value="">Select</option>
                <option value="Day">Day</option>
                <option value="Month">Month</option>
                <option value="Year">Year</option>
            </select>
        </div>
        <div class="iq-card-body">
            <div class="male-female-pie-chart-container">
                <div class="mb-3 mt-3" id="lab-order"></div>
            </div>
        </div>
    </div>
</div>
