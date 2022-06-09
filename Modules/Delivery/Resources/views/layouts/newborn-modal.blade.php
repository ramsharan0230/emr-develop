<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
            <div class="head-content">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <button type="button" class="btn-minimize"><i class="fa fa-window-minimize"></i></button>
            </div>

            <h6 class="modal-title">{{ (isset($header)) ? $header : '' }}</h6>
        </div>
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12 quantative-modal">
                    <div class="form-group">
                        <label for="" class="form-label">Examination</label>
                        <label for="" class="form-label label-form-big">{{ $examid }}</label>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12">
                    <input type="hidden" id="js-examination-type-value" value="{{ $type }}">
                @if($type == 'No Selection')
                <div class="form-group form-row align-items-center">
                    <label for="" class="col-md-2">Observation</label>
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" id="js-input-no-selection" class="form-control">
                            <div class="input-group-append">
                                <div class="input-group-text"><i class="ri-calculator-line"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="checkbox-container" style="margin-top: 0px;">
                    <div class="checkbox">
                        <button type="button" id="js-deliveryexamination-examination-save-modal" class="btn btn-success btn-sm">Save</button>
                        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                    </div>
                </div>
                @elseif($type == 'Left and Right')
                <div class="leftright-container res-table">
                    <table class="table-left table table-hover table-bordered table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th class="heading">Left</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="left-tbody">
                                    <textarea id="js-left-tbody" style="height: 100%;width: 100%;"></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table-right table table-hover table-bordered table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th class="heading">Right</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="right-tbody">
                                    <textarea id="js-right-tbody" style="height: 100%;width: 100%;"></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                @elseif($type == 'Single Selection')
                <div class="form-group">
                    <select id="js-input-element" class="form-control">
                        <option value="">-- Select --</option>
                        @foreach($options as $option)
                        <option value="{{ $option->fldanswer }}">{{ $option->fldanswer }}</option>
                        @endforeach
                    </select>
                </div>
                @else
                    <div class="form-group">
                        <input type="text" id="js-input-element" class="form-control">
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="js-abnormal">
                        <label class="custom-control-label" for="js-abnormal">Abnormal</label>
                    </div>
                @endif
                </div>
            </div>
        </div>
        <!-- Modal footer -->
        <div class="modal-footer">
            @if($type == 'Left and Right')
                <button type="button" id="js-newborn-examination-save-modal" class="btn save-btn btn-sm"><img src="{{ asset('assets/images/tick.png') }}" alt="" width="15px;">
                    &nbsp;&nbsp;Save
                </button>
            @elseif($type == 'No Selection')
                <div></div>
            @else
                <input type="submit" name="submit" id="js-newborn-examination-save-modal" class="btn btn-primary" value="Save">
            @endif
        </div>
    </div>
</div>
