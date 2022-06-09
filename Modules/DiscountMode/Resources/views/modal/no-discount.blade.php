<div class="modal fade bd-no-discount-modal-lg show" tabindex="-1" role="dialog" aria-modal="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">No Discount List</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="javascript:;" id="form-add-new-no-discount">
                    <input type="hidden" id="fldtype" name="fldtype" value="">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">

                                {{-- <select class="form-control discountGroup" name="discountGroup" id="discountGroup" onchange="discountModePatient.listByDiscountGroup()"> --}}
                                    <select class="form-control nodiscountGroup" name="discountGroup" id="discountGroup" onchange="discountModePatient.listByDiscountGroup()">
                                    <option>--Select--</option>
                                    @if($noDiscountList)
                                        @forelse($noDiscountList as $disItem)
                                            <option value="{{ $disItem }}">{{ $disItem }}</option>
                                        @empty

                                        @endforelse
                                    @endif
                                </select>
                            </div>

                            <div class="discounttable">
                                {{-- <div class="row"> --}}
                                    <div class="form-group mb-2">
                                        <input type="text" class="form-control" id="no-discount-search" onkeyup="myFunctionSearchPermission()" placeholder="Search for no discount..">
                                    </div>
                                {{-- </div> --}}
                                <table class="table table-striped table-hover table-vcenter" id="nodiscount-table-search">
                                    <tbody id="before-add-list">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <button type="button" class="btn btn-primary mt-top" onclick="discountModePatient.addNoDiscount();"><i class="fa fa-arrow-right" aria-hidden="true"></i></button>
                            <button type="button" class="btn btn-primary mt-top" onclick="discountModePatient.removeNoDiscount();"><i class="fa fa-arrow-left" aria-hidden="true"></i></button>
                        </div>
                        <div class="col-sm-5">
                            {{-- <input type="hidden" name="no_discount_fldtype"> --}}
                            <div class="discounttable2">
                                <table class="table table-striped table-hover table-vcenter">
                                    <tbody id="after-add-list">
                                    {{-- @if($existingNoDiscount)
                                        @forelse($existingNoDiscount as $ExistingDisItem)
                                            <tr>
                                                <td>   <input type="checkbox" name="no_discount_remove[]" value="{{$ExistingDisItem->flditemname}}"> </td>
                                                <td>{{ $ExistingDisItem->flditemname }}</td>

                                                <td><a href="javascript:;" onclick="discountModePatient.deleteNoDiscount('{{ $ExistingDisItem->flditemname }}')"><i class="fa fa-trash text-danger"></i></a></td>
                                            </tr>
                                        @empty

                                        @endforelse
                                    @endif --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                {{--<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>--}}
            </div>
        </div>
    </div>
</div>
