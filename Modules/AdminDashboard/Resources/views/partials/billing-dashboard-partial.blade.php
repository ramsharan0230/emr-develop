<div class="col-md-12">
    <div class="iq-card">
        <div class="iq-card-header d-flex justify-content-between">
            <div class="iq-header-title">
                <h4 class="card-title">Patient by Billing Mode</h4>
            </div>
            <select name="" class="paitient-by-billing-mode-count-change float-right form-control col-4" onchange="changePatientByBillingMode()">
                <option value="">Select</option>
                <option value="Day">Day</option>
                <option value="Month">Month</option>
                <option value="Year">Year</option>
            </select>
        </div>
        <div class="iq-card-body">
            <div class="male-female-pie-chart-container">
                <div class="mb-3 mt-3" id="paitient-by-billing-mode-count" style="height: 400px"></div>
            </div>
        </div>
    </div>
</div>
