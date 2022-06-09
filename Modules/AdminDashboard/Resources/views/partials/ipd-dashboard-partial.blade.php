<div class="col-md-12 col-lg-12">
    <div class="iq-card">
        <div class="iq-card-header d-flex justify-content-between">
            <div class="iq-header-title">
                <h4 class="card-title">Patient by IPD</h4>
            </div>
            {{-- <select name="" class="float-right form-control col-3" id="change-view-IPD">
                <option value="">Select View</option>
                <option value="patient_type">Patient Type</option>
                <option value="bed_occupacy">Bed Occupacy</option>
            </select> --}}
            <select name="" class="paitient-by-IPD-count-change float-right form-control col-4" onchange="changePatientByIPD()">
                <option value="">Select</option>
                <option value="Day">Day</option>
                <option value="Month">Month</option>
                <option value="Year">Year</option>
            </select>
        </div>
        <div class="iq-card-body">
            <div class="male-female-pie-chart-container">
                <div class="mb-3 mt-3" id="paitient-by-IPD" style="height: 420px"></div>
            </div>
        </div>
    </div>
</div>

<div class="col-md-12 col-lg-12">
    <div class="iq-card">
        <div class="iq-card-header d-flex justify-content-between">
            <div class="iq-header-title">
                <h4 class="card-title">Bed Occupacy</h4>
            </div>
        </div>
        <div class="iq-card-body">
            <div class="male-female-pie-chart-container">
                <div class="mb-3 mt-3" id="paitient-by-bed-occupacy" style="height: 320px"></div>
            </div>
        </div>
    </div>
</div>
