<div id="inout" class="collapse " aria-labelledby="headingOne" data-parent="#accordion">
    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-body">
            <div class="row">
                <div class="col-sm-7">
                    <form action="" class="form-horizontal">
                        <div class="form-group form-row">
                            <div class="col-sm-3">
                                <input type="date" class="form-control" id="js-date-inout">
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control after-date-box" readonly>
                            </div>
                            <div class="col-sm-3">
                             <button class="btn btn-primary btn-sm-in" id="js-list-fluids" datatype="all" type="button">
                                <i class="fa fa-list"></i>
                            </button>
                            <label class="border">In Take</label>
                        </div>
                        <div class="col-sm-2 align-items-right">
                            <div class="custom-control custom-radio custom-control-inline align-items-right">
                                <input type="radio" id="food-radio" name="customRadio-1" value="food" class="custom-control-input js-inout-intake-radio" checked>
                                <label class="custom-control-label" for="food-radio"> Food </label>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="medicine-radio" name="customRadio-1" value="medicine" class="custom-control-input js-inout-intake-radio">
                                <label class="custom-control-label" for="medicine-radio"> Medicine </label>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="res-table">
                    <table class="table table-hovered table-bordered table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th>Intake</th>
                                <th>Dose/Rate</th>
                                <th>Fluid(mL)</th>
                                <th>Energy(KCal)</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody class="js-fluids-intake-tbody"></tbody>
                    </table>
                </div>
                <form class="form-horizontal mt-3">
                    <div class="form-group row">
                        <label class="control-label col-sm-2 align-self-center mb-0" for="">Total(ML):</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="js-inout-total-fluid">
                        </div>
                        <label class="control-label col-sm-2 align-self-center mb-0" for="">Total(KCL):</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="js-inout-total-energy">
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-sm-5">
                <!-- <form action="" class="form-horizontal"> -->
                    <div class="form-group form-row text-center">
                        <div class="col-sm-5 ">
                            <input type="text" name="" class="form-control" disabled="" value="Fluid Output">
                        </div>
                        <div class="col-sm-7 p-0"> 
                            <button id="js-plan-btn" class="btn btn-sm btn-primary btn-sm-in {{ $disableClass }}" data-toggle="tooltip" data-placement="top" title="" data-original-title="Plan"><i class="fas fa-clipboard pr-0"></i>&nbsp;&nbsp;Plan</button>&nbsp;
                            <button id="js-in-btn" class="btn btn-sm btn-primary btn-sm-in {{ $disableClass }}" data-toggle="tooltip" data-placement="top" title="" data-original-title="In"><i class="fa fa-plus pr-0"></i>&nbsp;&nbsp;In</button>&nbsp;
                            <button id="js-out-btn" class="btn btn-sm btn-primary btn-sm-in {{ $disableClass }}" data-toggle="tooltip" data-placement="top" title="" data-original-title="Out"><i class="fas fa-minus pr-0"></i>&nbsp;&nbsp;Out</button>
                        </div>
                    </div>
                    <!-- </form> -->
                    <div class="res-table mt-4">
                        <table class="table table-hovered table-bordered table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <th>Output</th>
                                    <th>Vol(mL)</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody class="js-fluids-out-tbody"></tbody>
                        </table>
                    </div>
                    <form class="form-horizontal mt-3">
                        <div class="form-group row">
                            <label class="control-label col-sm-2 align-self-center mb-0" for="">Total(ML):</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="js-total-ml" placeholder="0">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="outFluid">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Enter Value</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <input type="" name="" value="Output Fluid" disabled class="input-output-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <div class="pulse-nxt-full">
                            <div class="form-group">
                                <div class="form-group-inner custom-9">
                                    <select id="js-out-fluid-option" class="form-input" multiple="" style="height: 203px;overflow: auto;">
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="form-group">
                                <input type="text" id="js-quantative" placeholder="0" class=" col-6 form-input" style="width: 100%;">
                                <div class="col-6">
                                    <input type="text" disabled="disabled" name="" value="mL" class="form-input" style="width: 100%;">
                                </div>
                            </div>
                            <div class="form-group mt-2" style="width: 100%;">
                                <button class="col-6 default-btn f-btn-icon-g" id="js-out-fluid-save" style="width: 100%"><i class="fas fa-check"></i>&nbsp;Save</button>
                                <div class="col-6">
                                    <button class="col-12 default-btn f-btn-icon-s" style="width: 100%"><i class="fas fa-times"></i>&nbsp;Close</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Diet Planning -->
<div class="modal" id="js-intake-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content ">
            <!-- Modal Header -->
            <div class="modal-header">
                <h6 class="modal-title">Oral Intake Form</h6>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row top-req">
                    <div class="col-lg-10 quantative-modal">
                        <div class="form-group form-row align-items-center">
                            <label for="" class="col-md-2">Name</label>
                            <div class="col-md-10">
                                <input type="text" readonly="readonly" value="@if(isset($patient)){{ Options::get('system_patient_rank')  == 1 && (isset($patient)) && (isset($patient->fldrank) ) ?$patient->fldrank:''}} {{ $patient->fldptnamefir }} {{ $patient->fldmidname }}  {{ $patient->fldptnamelast }}@endif" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <button type="button" class="btn btn-primary btn-sm-in" id="js-intake-save-btn"><i class="ri-check-line"></i> Save</button>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-4 intake-form" style="padding-left: 0px;">
                            <div class="modal-nav">
                                <div class="tab-1">
                                    <ul class="nav nav-tabs" id="yourTab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-toggle="tab" href="#priscribed" role="tab" aria-controls="home" aria-selected="true">Prescribed
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#advice" role="tab" aria-controls="profile" aria-selected="false">New Entry
                                            [F]</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="myTabContent">
                                        <div class="tab-pane fade show active" id="priscribed" role="tabpanel" aria-labelledby="home-tab">
                                            <div class="form-group">
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" id="js-packages-intake-radio" name="type" class="custom-control-input" value="packages">
                                                    <label class="custom-control-label" for="js-packages-intake-radio">Packages</label>
                                                </div>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" id="js-nutrition-intake-radio" name="type" class="custom-control-input" value="nutrition">
                                                    <label class="custom-control-label" for="js-nutrition-intake-radio">Nutrition</label>
                                                </div>
                                            </div>
                                            <div class="from-text" style="padding: 11px;">
                                                <div class="form-group">
                                                    <select id="js-intake-list-select" class="form-control" multiple="" style="height: 285px; overflow: auto;">
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="priscribe-btn" style="margin-left: 32px;">
                                                <button type="button" class="btn btn-danger"> Cancel</button>
                                                <button type="button" id="js-intake-continue-btn" class="btn btn-primary btn-sm-in"><i class="ri-check-line"></i> Continue</button>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="advice" role="tabpanel" aria-labelledby="profile-tab">
                                            <div class="form-entry">
                                                <div class="form-group form-row align-items-center">
                                                    <label for="js-intake-type-select" class="col-sm-2">Type</label>
                                                    <div class="col-sm-10">
                                                        <select id="js-intake-type-select" class="form-control"></select>
                                                    </div>
                                                </div>
                                                <div class="form-group form-row align-items-center">
                                                    <label for="js-intake-item-select" class="col-sm-2">Item</label>
                                                    <div class="col-sm-10">
                                                        <select id="js-intake-item-select" class="form-control"></select>
                                                    </div>
                                                </div>
                                                <div class="form-group form-row align-items-center">
                                                    <label for="js-intake-intake-input" class="col-sm-2">Intake</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" id="js-intake-intake-input" class="form-control" placeholder="0">
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="button" id="js-intake-update-button" class="btn btn-primary btn-sm-in"><i class="ri-check-line"></i> Update</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8 intaketable table-responsive">
                            <div class="res-table">
                                <table class="table table-hover table-bordered table-striped">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="tittle-th">DateTime</th>
                                            <th class="tittle-th">Particulars</th>
                                            <th class="tittle-th">Dose</th>
                                        </tr>
                                    </thead>
                                    <tbody id="js-intake-table-tbody"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Intake Modal -->
<div class="modal" id="js-diet-planning-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content ">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title4">Diet Planning</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6 quantative-modal">
                        <div class="form-group form-row align-items-center">
                            <label for="" class="col-md-2">Name</label>
                            <div class="col-md-10">
                                <input type="text" readonly="readonly" value="@if(isset($patient)){{ $patient->fldptnamefir }} {{ $patient->fldmidname }}  {{ $patient->fldptnamelast }}@endif" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group form-row align-items-center">
                            <label for="" class="col-md-2">Gender</label>
                            <div class="col-md-10">
                                <input type="text" readonly="readonly" value="@if(isset($patient)){{ $patient->fldptsex }}@endif" id="js-inout-gender-input" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="row mt-3">
                        <div class="modal-nav">
                            <div class="tab-1">
                                <ul class="nav nav-tabs" id="yourTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="tab" href="#newdiet" role="tab" aria-controls="home" aria-selected="true">New Daily Plan
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#dietsaved" role="tab" aria-controls="profile" aria-selected="false">Saved Daily Plan
                                        </a>
                                    </li>
                                </ul>
                                <div class=" col-lg-12 tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="newdiet" role="tabpanel" aria-labelledby="home-tab">
                                        <div class="col-md-12 mt-3">
                                            <div class="row">
                                                <div class="col-lg-8">
                                                    <div class="form-group form-row align-items-center">
                                                        <label for="" class="col-sm-2">Start</label>
                                                        <div class="col-sm-8">
                                                            <input type="date" id="js-input-date-planned" class="form-control">&nbsp;
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <button id="js-new-daily-plan-btn"><i class="ri-refresh-line"></i></button>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-row align-items-center">
                                                        <div class="input-group clockpicker">
                                                            <input type="text" id="js-input-time-planned" class="form-control" value="09:30">
                                                            <span class="input-group-addon">
                                                                <span class="glyphicon glyphicon-time"></span>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-row align-items-center">
                                                        <label for="" class="col-sm-2">Type</label>
                                                        <div class="col-sm-10">
                                                            <select name="" id="js-input-type-planned" class="form-control"></select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-row align-items-center">
                                                        <label for="" class="col-sm-2">Feeding Route</label>
                                                        <div class="col-sm-10">
                                                            <select name="" id="js-input-type-feeding-route" class="form-control">
                                                                <option value="Oral">Oral</option>
                                                                <option value="NG">NG</option>
                                                                <option value="OG">OG</option>
                                                                <option value="NJ">NJ</option>
                                                                <option value="PEG">PEG</option>
                                                                <option value="PEJ">PEJ</option>
                                                                <option value="Feeding Jejunostomy">Feeding Jejunostomy</option>
                                                                <option value="TPN">TPN</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-row align-items-center">
                                                        <label for="" class="col-sm-2">Fluid Restriction</label>
                                                        <div class="col-sm-10">
                                                            <input type="text" name="fluid_restriction" id="fluid_restriction" class="form-control">
                                                             <!-- <textarea name="fluid_restriction" id="fluid_restriction" class="form-control"></textarea> -->
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-row align-items-center">
                                                        <label for="" class="col-sm-2">Therapeutic Need</label>
                                                        <div class="col-sm-10">
                                                            <select name="" id="js-input-type-feeding-route" class="form-control">
                                                                <option value="High Calorie/Low calorie">High Calorie/Low calorie</option>
                                                                <option value="High protein">High protein</option>
                                                                <option value="High sodium/Low sodium">High sodium/Low sodium</option>
                                                                <option value="High potassium/Low potassium">High potassium/Low potassium</option>
                                                                <option value="Others">Others</option>
                                                                
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-row align-items-center">
                                                        <label for="" class="col-sm-2">Item</label>
                                                        <div class="col-sm-10">
                                                            <select name="" id="js-input-item-planned" class="form-control">
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group mt-2">
                                                        <label for="" class="col-sm-2">Dose</label>
                                                        <div class="col-sm-5">
                                                            <input type="text" name="" id="js-input-dose-planned" class="form-control">
                                                        </div>
                                                        <div class="col-sm-5">
                                                            <input type="text" name="" id="js-input-gram-planned" class="form-control" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-row align-items-center">
                                                        <div class="col-sm-8">
                                                            <input type="number" id="js-input-duration-planned" class="form-control" value="24" min="1" max="100" />
                                                        </div>
                                                        <label for="" class="col-sm-2">Hourly</label>
                                                        <div class="col-sm-2">
                                                            <button type="add" id="js-add-diet-planning-btn" class="btn btn-primary btn-sm-in"><i class="ri-add-line"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="form-group form-row align-items-center">
                                                        <label for="" class="col-sm-3">Energy</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" name="" id="js-input-energy-planned" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-row align-items-center">
                                                        <label for="" class="col-ms-3">Fluid</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" name="" id="js-input-fluid-planned" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="form-group-inner custom-diet">
                                                        <button id="js-save-diet-planning-btn" class="btn btn-primary "><i class="fas fa-check"></i>&nbsp;&nbsp;&nbsp;Save
                                                        </button>
                                                        <button id="js-diet-planning-export-btn-modal" class="btn btn-primary "><img src="{{ asset('assets/images/calculator.png') }}" width="18px" alt=""></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 mt-3">
                                            <div class="res-table">
                                                <table class="table-hover table-bordered table-striped table">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th class="tittle-th">Type</th>
                                                            <th class="tittle-th">Particulars</th>
                                                            <th class="tittle-th">Dose</th>
                                                            <th class="tittle-th">Time</th>
                                                            <th class="tittle-th"></th>
                                                            <th class="tittle-th">Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="js-diet-planning-planned-tbody"></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade col-lg-12" id="dietsaved" role="tabpanel" aria-labelledby="profile-tab">
                                        <div class="row mt-2">
                                            <div class="col-lg-4">
                                                <div class="form-group form-row align-items-center">
                                                    <div class="col-sm-10">
                                                        <input type="date" id="js-input-date-saved" class="form-control">
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <button id="js-saved-daily-plan-btn" class="default-btn"><i class="ri-refresh-line"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group form-row align-items-center">
                                                    <label for="" class="col-sm-3">Energy</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="" id="js-saved-daily-diet-plan-energy-input" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group form-row align-items-center">
                                                    <label for="" class="col-sm-3">Fluid</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" name="" id="js-saved-daily-diet-plan-fluid-input" class="form-control">
                                                    </div>
                                                    <div class="col-sm-1">
                                                        <a><i class="ri-calculator-line"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-12">
                                                <div class="res-table savediet-table">
                                                    <table class="table-striped table-hover table-bordered table">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th class="tittle-th">Type</th>
                                                                <th class="tittle-th">Particulars</th>
                                                                <th class="tittle-th">Dose</th>
                                                                <th class="tittle-th">Time</th>
                                                                <th class="tittle-th">&nbsp;</th>
                                                                <th class="tittle-th">Status</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="js-diet-planning-continued-tbody"></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="js-inout-change-dose-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <div class="head-content">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <button type="button" class="btn-minimize"><i class="fa fa-window-minimize"></i></button>
                </div>
                <h6 class="modal-title">Change Dose</h6>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-7">
                        <label>Dose/Rate</label>
                        <input type="text" id="js-inout-change-dose-input" class="form-input">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <button type="button" id="js-inout-change-dose-btn-modal" class="btn btn-success btn-sm">Save</button>&nbsp;
                        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<div class="modal" id="js-inout-status-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <div class="head-content">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <button type="button" class="btn-minimize"><i class="fa fa-window-minimize"></i></button>
                </div>
                <h6 class="modal-title">Select Current Status</h6>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-7">
                        <div id="js-inout-status-text-display"></div>
                        <select class="form-input" id="js-inout-status-select" style="width: 100%;">
                            <option value="Continue">Continue</option>
                            <option value="Discontinue">Discontinue</option>
                            <option value="Hold">Hold</option>
                            <option value="Change">Change</option>
                            <option value="ReWrite">ReWrite</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <button type="button" id="js-inout-status-save-modal" class="btn btn-success btn-sm">Save</button>&nbsp;
                        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<div class="modal" id="js-diet-change-date-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <div class="head-content">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <button type="button" class="btn-minimize"><i class="fa fa-window-minimize"></i></button>
                </div>
                <h6 class="modal-title">Change Date Time</h6>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-7">
                        <label>Date</label>
                        <input type="date" id="js-diet-change-date-input" class="form-input remove-indicator">
                        <label>Time</label>
                        <input type="text" id="js-diet-change-time-input" class="form-input">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <button type="button" id="js-diet-change-date-btn" class="btn btn-success btn-sm">Save</button>&nbsp;
                        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="js-output-change-volumn-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <div class="head-content">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <button type="button" class="btn-minimize"><i class="fa fa-window-minimize"></i></button>
                </div>
                <h6 class="modal-title">Change Volumn</h6>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-7">
                        <label>Volumn</label>
                        <input type="text" id="js-output-change-volumn-input" class="form-input">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <button type="button" id="js-output-change-volumn-btn-modal" class="btn btn-success btn-sm">Save</button>&nbsp;
                        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
