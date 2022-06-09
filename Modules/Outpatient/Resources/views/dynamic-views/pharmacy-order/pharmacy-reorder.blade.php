<form action="javascript:;" name="pharmacy-reorder" id="pharmacy-reorder-form">
    <input type="hidden" name="encounter" value="{{ $encounterData->fldencounterval }}">
    <input type="hidden" name="flditemtype" value="Radio Diagnostics">
    <input type="hidden" name="med_ortho_msurge" id="med-ortho-surge">
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <select name="request_department_pharmacy" class="form-control" id="request_department_pharmacy_reorder">
                    <option value=""></option>
                    @if(count($departments))
                        @foreach($departments as $department)
                            <option value="{{ $department }}" {{ $encounterData->fldcurrlocat == $department?"selected":'' }}>{{ $department }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group">
                <input type="text" value="{{ $previousOrder->fldroute }}" class="form-control" readonly>
                <input type="hidden" name="route" value="{{ $previousOrder->fldroute }}" id="route_reorder" class="form-control">
                {{--<select name="route" id="pharmacy_route" class="form-control" onchange="pharmacyPopup.selectMedicine()">
                    <option value="">Select Route</option>
                    <option value="oral">oral</option>
                    <option value="liquid">liquid</option>
                    <option value="fluid">fluid</option>
                    <option value="injection">injection</option>
                    <option value="resp">resp</option>
                    <option value="topical">topical</option>
                    <option value="eye/ear">eye/ear</option>
                    <option value="anal/vaginal">anal/vaginal</option>
                    <option value="msurg">msurg</option>
                    <option value="ortho">ortho</option>
                    <option value="extra">extra</option>
                </select>--}}
                {{--<select name="route" id="pharmacy_route" class="form-control" onchange="pharmacyPopup.selectMedicine()">
                    <option value="">Select Route</option>
                    @if(count($newOrders))
                        @foreach($newOrders as $route)
                            <option value="{{ $route->fldroute }}">{{ $route->fldroute }}</option>
                        @endforeach
                    @endif
                    @if(count($newOrdersSurgcat))
                        @foreach($newOrdersSurgcat as $route)
                            @if($route->fldsurgcateg == "msurg" || $route->fldsurgcateg == "ortho")
                                <option value="{{ $route->fldsurgcateg }}">{{ $route->fldsurgcateg }}</option>
                            @endif
                        @endforeach
                    @endif
                </select>--}}
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group">
                <input type="text" value="{{ $previousOrder->flditem }}" class="form-control" readonly>
                <input type="hidden" name="itemName" value="{{ $previousOrder->flditem }}" class="form-control">
                {{--<select id="itemNameToShowDropdown" name="itemName" class="form-control pharmacy_item_new_order" onchange="pharmacyPopup.clickMedSelect()">
                    <option value="">--Select--</option>
                </select>--}}
                {{--<input type="text" id="itemNameToShowDropdown" name="itemNameToShow" class="form-control pharmacy_item_new_order" onclick="pharmacyPopup.selectMedicine()">
                 <input type="hidden" name="itemName" class="form-control pharmacy_item_new_order">--}}
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <input type="text" name="pharnmacy_dose" id="pharnmacy_dose_reorder" class="form-control" value="{{ $previousOrder->flddose }}">
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <select name="pharnmacy_freq" id="pharnmacy_freq_reorder" class="form-control">
                    <option value="1" {{ $previousOrder->fldfreq == '1' ? "selected" : "" }}>1</option>
                    <option value="2" {{ $previousOrder->fldfreq == '2' ? "selected" : "" }}>2</option>
                    <option value="3" {{ $previousOrder->fldfreq == '3' ? "selected" : "" }}>3</option>
                    <option value="4" {{ $previousOrder->fldfreq == '4' ? "selected" : "" }}>4</option>
                    <option value="5" {{ $previousOrder->fldfreq == '5' ? "selected" : "" }}>5</option>
                    <option value="6" {{ $previousOrder->fldfreq == '6' ? "selected" : "" }}>6</option>
                    <option value="PRN" {{ $previousOrder->fldfreq == 'PRN' ? "selected" : "" }}>PRN</option>
                    <option value="SOS" {{ $previousOrder->fldfreq == 'SOS' ? "selected" : "" }}>SOS</option>
                    <option value="stat" {{ $previousOrder->fldfreq == 'stat' ? "selected" : "" }}>stat</option>
                    <option value="AM" {{ $previousOrder->fldfreq == 'AM' ? "selected" : "" }}>AM</option>
                    <option value="HS" {{ $previousOrder->fldfreq == 'HS' ? "selected" : "" }}>HS</option>
                    <option value="Pre" {{ $previousOrder->fldfreq == 'Pre' ? "selected" : "" }}>Pre</option>
                    <option value="Post" {{ $previousOrder->fldfreq == 'Post' ? "selected" : "" }}>Post</option>
                    <option value="Hourly" {{ $previousOrder->fldfreq == 'Hourly' ? "selected" : "" }}>Hourly</option>
                    <option value="Alt day" {{ $previousOrder->fldfreq == 'Alt day' ? "selected" : "" }}>Alt day</option>
                    <option value="Weekly" {{ $previousOrder->fldfreq == 'Weekly' ? "selected" : "" }}>Weekly</option>
                    <option value="Biweekly" {{ $previousOrder->fldfreq == 'Biweekly' ? "selected" : "" }}>Biweekly</option>
                    <option value="Tryweekly" {{ $previousOrder->fldfreq == 'Tryweekly' ? "selected" : "" }}>Tryweekly</option>
                    <option value="Monthly" {{ $previousOrder->fldfreq == 'Monthly' ? "selected" : "" }}>Monthly</option>
                    <option value="Yearly" {{ $previousOrder->fldfreq == 'Yearly' ? "selected" : "" }}>Yearly</option>
                    <option value="Tapering" {{ $previousOrder->fldfreq == 'Tapering' ? "selected" : "" }}>Tapering</option>
                </select>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <input type="text" name="pharnmacy_day" id="pharnmacy_day_reorder" class="form-control" value="{{ $previousOrder->flddays }}" onblur="pharmacyPopup.calculateQuantityReorder()">
            </div>
        </div>
        <div class="col-sm-3">
            <input type="text" name="pharnmacy_qty" id="pharnmacy_qty_reorder" class="form-control" value="{{ $previousOrder->fldqtydisp }}">
        </div>

    </div>
    <div class="row">
        <a href="javascript:void(0)" class="btn btn-info btn-sm disableInsertUpdate" onclick="pharmacyPopup.calculateQuantityReorder()"><i class="fas fa-calculator"></i></a>
        <a href="javascript:void(0)" class="btn btn-success btn-sm disableInsertUpdate" onclick="pharmacyPopup.clickSaveReOrder()">Add</a>
    </div>
</form>
