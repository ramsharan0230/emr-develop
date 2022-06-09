<div class="tab-pane fade" id="pharmacydelivery" role="tabpanel" aria-labelledby="pharmacydelivery-tab">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="dietarytable" style="float: right;">
                    <button class="btn btn-primary" onclick="pharmacy.displayModal()"  data-dismiss="modal" type="button"><i class="fa fa-plus"></i>&nbsp;Request</button>
                    <button class="btn btn-primary" onclick="dosingRecord.displayModal();" data-dismiss="modal" type="button"><i class="fa fa-plus"></i>&nbsp;Dosing</button>
                    <button class="btn btn-primary" type="button" id="js-delivery-pharmacy-showall" datatype="today"><i class="fa fa-list"></i></button>
                    <button class="btn btn-primary" type="button" id="js-delivery-pharmacy-report" datatype="today"><i class="fa fa-code"></i></button>
                </div>
                <div class="res-table mt-5">
                    <table class="table table-hovered table-bordered table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th class="tittle-th">SN</th>
                                <th class="tittle-th">StartDate</th>
                                <th class="tittle-th">Route</th>
                                <th class="tittle-th">Particulars</th>
                                <th class="tittle-th">Dose</th>
                                <th class="tittle-th">Freq</th>
                                <th class="tittle-th">Days</th>
                                <th class="tittle-th">QTY</th>
                                <th class="tittle-th">Status</th>
                            </tr>
                        </thead>
                        <tbody id="js-delivery-pharmacy-tbody"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
