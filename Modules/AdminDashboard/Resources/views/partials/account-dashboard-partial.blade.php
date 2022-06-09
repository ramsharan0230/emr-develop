<div class="col-md-12 col-lg-12">
    <div class="iq-card">
        <div class="iq-card-header d-flex justify-content-between">
            <div class="iq-header-title">
                <h4 class="card-title">Account</h4>
            </div>
            <select name="" class="lab-status-count-change float-right form-control col-4">
                <option value="">Select</option>
                <option value="Day">Day</option>
                <option value="Month">Month</option>
                <option value="Year">Year</option>
            </select>
        </div>
        <div class="iq-card-header d-flex justify-content-between">
            <div class="iq-header-title">
                <h4 class="card-title">{{ $totalNewPatient }} New /{{ $totalNOldPatient }} Old Patient</h4>
            </div>
            <div class="iq-header-title">
                <h4 class="card-title">{{ $totalNewPatient + $totalNOldPatient }} Registered Patients</h4>
            </div>
        </div>
        <div class="iq-card-body">
            <div class="male-female-pie-chart-container">
                <div class="mb-3 mt-3" id="revenue-statistics"></div>
            </div>
        </div>
    </div>
</div>
