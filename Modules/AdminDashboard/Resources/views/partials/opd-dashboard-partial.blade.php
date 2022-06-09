<div class="col-md-12 col-lg-12">
    {{--<div class="col-md-12">
        <div class="iq-card">
            <div class="iq-card-header d-flex justify-content-between">
                <div class="iq-header-title">
                    <h4 class="card-title"> Male/Female</h4>
                </div>
                <select name="" class="piechart-male-female-change float-right form-control col-4" onchange="changeMaleFemalePie()">
                    <option value="">Select</option>
                    <option value="Day">Day</option>
                    <option value="Month">Month</option>
                    <option value="Year">Year</option>
                </select>
            </div>
            <div class="iq-card-body">
                <div class="male-female-pie-chart-container">
                    <div id="male-female-pie-chart" style="right:43px"></div>
                </div>
            </div>
        </div>
    </div>--}}
    <div class="iq-card">
        <div class="iq-card-header d-flex justify-content-between">
            <div class="iq-header-title">
                <h4 class="card-title">Patient by OPD</h4>
            </div>
            <select name="" class="paitient-by-opd-count-change float-right form-control col-4" onchange="changePatientByOPD()">
                <option value="">Select</option>
                <option value="Day">Day</option>
                <option value="Month">Month</option>
                <option value="Year">Year</option>
            </select>
            <select name="billingSet" class="paitient-by-opd-billing-set-change float-right form-control col-4" onchange="changePatientByOPD()">
                <option value="">Select</option>
                @if($billingSet)
                    @forelse($billingSet as $set)
                        <option value="{{ $set->fldsetname }}">{{ $set->fldsetname }}</option>
                    @empty

                    @endforelse
                @endif

            </select>
        </div>
        {{-- <div class="iq-card-header d-flex justify-content-between">
            <div class="iq-header-title">
                <h4 class="card-title">{{ $opdNewCount }} New /{{ $opdOldCount }} Old Patient</h4>
            </div>
            <div class="iq-header-title">
                <h4 class="card-title">{{ $opdFollowUpCount }} Follow Up</h4>
            </div>
        </div> --}}
        <div class="iq-card-body">
            <div class="male-female-pie-chart-container">
                <div class="mb-3 mt-3" id="paitient-by-OPD"></div>
            </div>
        </div>
    </div>

</div>
