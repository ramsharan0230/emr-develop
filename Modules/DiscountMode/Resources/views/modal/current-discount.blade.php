<div class="modal fade bd-curr-modal-lg show" tabindex="-1" role="dialog" aria-modal="true">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('patient.discount.mode.custom.type.save') }}" id="custom-discount-form" method="post">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Discount Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group form-row col-12">
                            <label class="col-md-2 control-label">Discount label</label>
                            <div class="col-md-10">
                                <input type="text" name="discountLable" class="form-control fldtype-custom" readonly/>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label class="col-md-5">Laboratory</label>
                                <div class="col-md-6 padding-none">
                                    <input type="number" name="Laboratory" id="custom-Laboratory" class="form-control" placeholder="0"/>
                                </div>
                                <div class="col-sm-1">
                                    <label>%</label>
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <label class="col-md-5">Radiology</label>
                                <div class="col-md-6 padding-none">
                                    <input type="number" name="Radiology" id="custom-Radiology" class="form-control" placeholder="0"/>
                                </div>
                                <div class="col-sm-1">
                                    <label>%</label>
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <label class="col-md-5">Procedures</label>
                                <div class="col-md-6 padding-none">
                                    <input type="number" name="Procedures" id="custom-Procedures" class="form-control" placeholder="0"/>
                                </div>
                                <div class="col-sm-1">
                                    <label>%</label>
                                </div>
                            </div>
<!--                            <div class="form-group form-row">
                                <label class="col-md-5">Registration</label>
                                <div class="col-md-6 padding-none">
                                    <input type="number" name="Registration" id="custom-Registration" class="form-control" placeholder="0"/>
                                </div>
                                <div class="col-sm-1">
                                    <label>%</label>
                                </div>
                            </div>-->
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label class="col-md-5">Equipment</label>
                                <div class="col-md-6 padding-none">
                                    <input type="number" name="Equipment" id="custom-Equipment" class="form-control" placeholder="0"/>
                                </div>
                                <div class="col-sm-1">
                                    <label>%</label>
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <label class="col-md-5">Gen Services</label>
                                <div class="col-md-6 padding-none">
                                    <input type="number" name="GenServices" id="custom-GenServices" class="form-control" placeholder="0"/>
                                </div>
                                <div class="col-sm-1">
                                    <label>%</label>
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <label class="col-md-5">Others</label>
                                <div class="col-md-6 padding-none">
                                    <input type="number" name="Others" id="custom-Others" class="form-control" placeholder="0"/>
                                </div>
                                <div class="col-sm-1">
                                    <label>%</label>
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <button type="button" class="btn btn-primary" onclick="discountModePatient.customDiscountSpecificSave()"><i class="fa fa-edit"></i> update</button>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-row">
                                <label class="col-md-5">Medical</label>
                                <div class="col-md-6 padding-none">
                                    <input type="number" name="Medical" id="custom-Medical" class="form-control" placeholder="0"/>
                                </div>
                                <div class="col-sm-1">
                                    <label>%</label>
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <label class="col-md-5">Surgical</label>
                                <div class="col-md-6 padding-none">
                                    <input type="number" name="Surgical" id="custom-Surgical" class="form-control" placeholder="0"/>
                                </div>
                                <div class="col-sm-1">
                                    <label>%</label>
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <label class="col-md-5">Extra Item</label>
                                <div class="col-md-6 padding-none">
                                    <input type="number" name="ExtraItem" id="custom-ExtraItem" class="form-control" placeholder="0"/>
                                </div>
                                <div class="col-sm-1">
                                    <label>%</label>
                                </div>
                            </div>

                        </div>
                        <div class="form-group form-row col-12">
                            <div class="col-sm-2">
                                <select name="category" id="discount-category" class="form-control" onchange="discountModePatient.customDiscountListByType()">
                                    <option value="">--Select--</option>
                                    <option value="Diagnostic Tests">Diagnostic Tests</option>
                                    <option value="Equipment">Equipment</option>
                                    <option value="General Services">General Services</option>
{{--                                    <option value="medbrand">Medicine Brand</option>--}}
                                    <option value="Other Items">Other Items</option>
                                    <option value="Procedures">Procedures</option>
                                    <option value="Radio Diagnostics">Radio Diagnostics</option>
{{--                                    <option value="surgbrand">Surgical Brand</option>--}}
{{--                                    <option value="extrabrand">Extra Brand</option>--}}
                                </select>
                            </div>
                            <div class="col-md-5">
                                <select name="itemName" id="discount-itemName" class="form-control">
                                    <option value="">--Select--</option>
                                </select>
                            </div>
                            <div class="col-sm-1">
                                {{-- <button type="button" class="btn btn-primary">
                                    <i class="fa fa-ellipsis-v" aria-hidden="true"></i><i class="fa fa-ellipsis-v" aria-hidden="true"></i><i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                </button> --}}
                            </div>
                            <div class="col-md-2">
                                <input type="number" id="customPercentage" name="customPercentage" class="form-control" placeholder="0"/>

                            </div>
                            <div class="col-sm-1">
                                <label>%</label>
                            </div>
                            <div class="col-sm-1">
                                <button type="button" id="customDiscountSaveBtn" class="btn btn-primary" onclick="discountModePatient.customDiscountSave()" disabled><i class="fa fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="table-responsive table-scroll-fiscal mt-3">
                                <table class="table table-bordered table-sm">
                                    <thead>
                                    <tr>
                                        <th>Category</th>
                                        <th>Item Name</th>
                                        <th>Disc %</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                    </thead>
                                    <tbody id="custom-discount-list"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
{{--                    <button type="submit" class="btn btn-primary">Save changes</button>--}}
                </div>
            </div>
        </form>

    </div>
</div>
