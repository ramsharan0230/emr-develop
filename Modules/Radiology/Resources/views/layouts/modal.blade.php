<div class="modal-dialog modal-xl">
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
                        <label for="" class="form-label label-form-big">{{ $testid }}</label>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12">
                    <form id="js-examination-modal-form">
                        <input type="hidden" name="fldoption" value="{{ $type }}">
                        <input type="hidden" name="fldtestid" value="{{ $fldid }}" id="radiology-observation-fldtestid-input-modal">
                        <input type="hidden" name="fldencounterval" id="radiology-observation-encounter-input-modal">
                        @if($type == 'Fixed Components')
                        <div class="table-quantative res-table">
                            <table class="table table-hover table-striped table-bordered">
                                <tbody>
                                    @foreach($options as $key => $option)
                                    <tr>
                                        <td class="title"><p>{{ ($key+1) }}</p></td>
                                        <td class="title"><p>{{ $option->fldsubexam }}</p></td>
                                        <td class="title-input">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="hidden" name="fldid[]" value="{{ $option->fldid }}">
                                                    <input type="hidden" name="fldsubtest[]" value="{{ $option->fldsubexam }}">
                                                    <input type="hidden" name="fldtanswertype[]" value="{{ $option->fldanswertype }}">
                                                    <input type="checkbox" name="fldabnormal[{{ $option->fldid }}]" value="1" {{ ($option->fldabnormal == 1) ? 'checked="checked"' : '' }} style="display: inline;margin: 15px;">
                                                    &nbsp;&nbsp;Abnormal
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <textarea class="form-input full-width" id="js-radio-observation-textarea-{{ $key }}" name="observation[{{ $option->fldid }}]" style="height: 75px;">{{ $option->fldreport }}</textarea>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="form-group">
                            <input type="hidden" name="fldid" value="{{ $fldid }}">
                            <textarea class="form-input full-width js-value-area" id="js-radio-observation-textarea" name="observation">{{ $data->fldreportquali }}</textarea>
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="fldabnormal" value="1" {{ ($data->fldabnormal == 1) ? 'checked="checked"' : '' }}>
                                <label class="custom-control-label" for="customCheck1">Abnormal</label>
                            </div>
                        </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
        <!-- Modal footer -->
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <input type="button" name="type" id="js-examination-save-btn-modal" onclick="changeRadioData.saveData(this)" class="btn btn-primary" value="Save">
            @if($module == 'xray')
            <input type="button" name="type" id="js-examination-saveverify-btn-modal" onclick="changeRadioData.saveData(this)" class="btn btn-primary" value="Save & Verify">
            @endif
        </div>
    </div>
</div>

<script type="text/javascript">
    $('.full-width').each(function(e){
        CKEDITOR.replace( this.id, { height: '60px' });
    });
</script>
