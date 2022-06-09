<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Item Information</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="preview-modal-content">
        <div class="d-flex flex-row align-items-center justify-content-between">
            <h6 class="mb-2">Category: {{ $items->first()->flditemtype }}</h6>
            @if ($items->first()->fldstatus == 'Active')
                <div class=""><i class="fa fa-circle" style="color: green"></i>&nbsp;Active</div>
            @else
                <div class=""><i class="fa fa-circle" style="color: red"></i>&nbsp;Inactive</div>
            @endif
        </div>
        <table style="width: 100%">
            <tr>
                <td class="title">Item Name</td>
                <td class="desc">{{ $items->first()->fldbillitem }}</td>
            </tr>
            <tr>
                <td class="title">Account Ledger</td>
                <td class="desc">{{ $items->first()->account_ledger }}</td>
            </tr>
            <tr>
                <td class="title">Department</td>
                <td class="desc">{{ $items->first()->fldreport }}</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td class="title">target</td>
                <td class="desc">{{ $items->first()->fldtarget }}</td>
            </tr>
            <tr>
                <td class="title">Tax Type:</td>
                <td class="desc"></td>
            </tr>
        </table>
        <hr>
        <h6 class="mb-2">Price Setups</h6>
        <table class="table table-bordered table-striped table-hover">
            <thead class="thead-light">
                <tr>
                    <th style="width: 20%">Billing Mode</th>
                    <th style="width: 50%">Item Name</th>
                    <th style="width: 15%">Price</th>
                    <th style="width: 15%">Status</th>
                </tr>
            </thead>
            <tbody id="">
                @foreach ($items as $item)
                    <tr>
                        <td style="width: 20%">{{ $item->fldgroup }}</td>
                        <td style="width: 50%">{{ $item->flditemname }}</td>
                        <td style="width: 15%">{{ $item->flditemcost }}</td>
                        <td style="width: 15%">{{ $item->fldstatus }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <table style="width: 100%">
            <tr>
                <td style="width: 50%">
                    <table style="width: 100%">
                        <tr>
                            <td>
                                <h6>Fraction Category</h6>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex flex-column">
                                    @foreach ($categories as $category)
                                        <div>{{ $category }}</div>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="width: 50%">
                    <table style="width: 100%">
                        <tr>
                            <td colspan="2">
                                <h6>Description</h6>
                            </td>
                        </tr>
                        <tr>
                            <td>{!! $items->first()->flddescription !!}</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td style="width: 50%">
                    <table style="width: 100%">
                        <tr>
                            <td>
                                <h6>Editable</h6>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex flex-row">
                                    <div class="col-3">
                                        <input class="magic-checkbox" type="checkbox" name="rate" value="1"
                                            {{ $items->first()->rate ? 'checked' : '' }} disabled>
                                        <label for="">Rate</label>
                                    </div>
                                    <div class="col-4">
                                        <input class="magic-checkbox" type="checkbox" name="discount" value="1"
                                            {{ $items->first()->discount ? 'checked' : '' }} disabled>
                                        <label for="">Discount</label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>
