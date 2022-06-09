
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
                <h5 class="modal-title">{{ (isset($header)) ? $header : '' }}</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12 quantative-modal">
                    <div class="form-group">
                        <label for="" class="form-label">Examination</label>
                        <label for="" class="col-8">{{ $examid }}</label>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12">
                    <input type="hidden" id="js-observation-type-hidden" value="{{ $type }}">
                    <input type="hidden" id="js-observation-fldid-hidden" value="{{ $fldid }}">
                    @if($type == 'Clinical Scale')
                        <div class="table-quantative res-table">
                            <table class="table-observ table table-hover table-bordered table-striped">
                                <tbody>
                                    @foreach($options as $key => $option)
                                    <tr>
                                        <td class="title">
                                            <p>{{ $key }}</p>
                                        </td>
                                        <td class="title-input">
                                            <select class="js-observation-scale-select">
                                                <option data-val="0">Select Option</option>
                                                @foreach ($option['options'] as $k => $val)
                                                    <option data-val="{{ $val }}">{{ $val }} - {{ $k }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" class="form-input-small js-observation-scale-text" placeholder="0">
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @elseif($type == 'No Selection')
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group1">
                                    <label for="" class="formlabel">Observation</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" id="js-observation-input-element" class="form-control" value="{{ $patient_exam->fldrepquali }}">
                                </div>
                                <div class="checkbox-container">
                                    <div class="checkbox">
                                        <button type="button" onclick="updateExamObservation.updateObservation(this)" class="btn btn-primary">Save</button>
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif($type == 'Left and Right')
                        @php
                            $d = json_decode(strtolower($patient_exam->fldrepquali));
                        @endphp
                        <div class="leftright-container res-table">
                            <table class="table-left table table-hover table-bordered table-striped">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="heading"style="padding: 4px">Left</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="left-tbody">
                                            <textarea id="js-observation-left-tbody" style="height: 100%;width: 100%;">{{ isset($d->left) ? $d->left : '' }}</textarea>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table class="table-right table table-hover table-bordered table-striped">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="heading" style="padding: 4px">Right</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="right-tbody">
                                            <textarea id="js-observation-right-tbody" style="height: 100%;width: 100%;">{{ isset($d->right) ? $d->right : '' }}</textarea>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @elseif($type == 'Single Selection')
                        <div class="form-group">
                            <select id="js-observation-input-element" class="form-control" style="margin: 15px;">
                                <option value="">-- Select --</option>
                                @foreach($options as $option)
                                <option value="{{ $option->fldanswer }}" {{ ($patient_exam->fldrepquali == $option->fldanswer) ? "selected='selected'" : "" }}>{{ $option->fldanswer }}</option>
                                @endforeach
                            </select>
                        </div>
                    @elseif($type == 'Fixed Components' || $type == 'Text Table' || $type == 'Text Addition' || $type == 'Text Reference')
                        <textarea id="js-observation-textarea-input" class="form-control">{{ $patient_exam->fldrepquali }}</textarea>
                    @else
                        <div class="form-group">
                            <input type="text" id="js-input-element" class="form-control" value="{{ $patient_exam->fldrepquali }}">
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <!-- Modal footer -->
        <div class="modal-footer">
            @if($type == 'Left and Right')
                <button type="button" onclick="updateExamObservation.updateObservation(this)" class="btn save-btn btn-primary">&nbsp;&nbsp;Save</button>
            @elseif($type == 'No Selection')
                <div></div>
            @else
                <input type="submit" name="submit" onclick="updateExamObservation.updateObservation(this)" class="btn btn-primary" value="Save changes">
            @endif
        </div>
    </div>
</div>

@push('after-script')
<script type="text/javascript">

</script>
@endpush
