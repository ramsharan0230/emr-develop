<div class="modal-header">
    <h6 class="modal-title">{{ (isset($header)) ? $header : '' }}</h6>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
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
            <input type="hidden" id="js-observation-type-hidden" value="{{ $type }}">
            <input type="hidden" id="js-observation-fldid-hidden" value="{{ $fldid }}">
            <input type="hidden" id="js-observation-examid-hidden" value="{{ $examid }}">
            <input type="hidden" id="js-observation-fldencounterval-hidden" value="{{ $fldencounterval }}">
            @if($examid == 'Culture & Sensitivity')
                <div class="table-quantative res-table">
                    <?php //dd($final_tests); ?>
                    <form id="js-culture-form">
                        @if ($testoptions)
                            <div class="row">
                                <div class="col-sm-8">
                                    <textarea  id="js-reporting-single-selection-textarea">{{ $culture->fldreportquali }}</textarea>
                                </div>
                                <div class="col-sm-4">
                                    <div class="res-table table-sticky-th">
                                        <table class="table table-hover table-striped table-bordered">
                                            <tbody id="js-reporting-single-tbody-modal">
                                                @foreach ($testoptions as $testoption)
                                                <tr><td>{{ $testoption }}</td></tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @else
                            <table class="table table-hover table-striped table-bordered">
                                <tbody id="js-culture-subtest-tbody">
                                    @foreach($culture->subTest as $subTest)
                                    <tr data-fldid="{{ $subTest->fldid }}" data-fldsubtest="{{ $subTest->fldsubtest }}">
                                        <td>{{ $subTest->fldsubtest }}</td>
                                        <td>
                                            <input type="checkbox" id="abnormal_check_{{ $subTest->fldid }}" class="abnormal" name="abnormal[{{ $subTest->fldid }}]" {{ ($subTest->fldabnormal == '1') ? "checked='checked'" : '' }}>
                                            <label for="abnormal_check_{{ $subTest->fldid }}">&nbsp; Abnormal</label>
                                        </td>
                                        <td width="50%;">
                                            <table class="table table-bordered">
                                                <tbody class="js-culture-subtable-tody">
                                                    @foreach($subTest->subtables as $subtables)
                                                    <tr>
                                                        <td>{{ $subtables->fldvariable }}</td>
                                                        <td>{{ $subtables->fldvalue }}</td>
                                                        <td>{{ $subtables->fldcolm2 }}</td>
                                                        <td><button type="button" class="btn btn-danger js-culture-subtables-value-delete" data-fldid="{{ $subtables->fldid }}"><i class="fa fa-trash"></i></button></td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-primary js-culture-subtables-info"><i class="fa fa-info"></i></button>
                                            @if($subTest->subtables->count() == 0)
                                            <button type="button" class="btn btn-danger js-culture-subtables-delete"><i class="fa fa-trash"></i></button>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </form>
                </div>
            @elseif($type == 'Clinical Scale')
                <div class="table-quantative res-table">
                    <table class="table table-bordered table-hover table-striped">
                        <tbody>
                        @foreach($formated_que as $key => $options)
                            <tr>
                                <td class="title">
                                    <p>{{ $key }}</p>
                                </td>
                                <td class="title-input">
                                    <select class="form-control" id="js-input-element" name="clinical_scale">
                                        <option data-val="0">Select Option</option>
                                        @foreach ($options['options'] as $k => $val)
                                            <option data-val="{{ $val }}">{{ $val }} - {{ $k }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="text" class="form-control" placeholder="0">
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @elseif($type == 'Fixed Components')
                <div class="table-quantative res-table">
                    <?php //dd($final_tests); ?>
                    <form id="js-fixed-components-form">
                        <table class="table table-hover table-striped table-bordered">
                            <tbody>
                            @foreach($final_tests as $key => $option)
                                <tr class="js-fixed-components-tr">
                                    <td style="width: 20%;" class="title">
                                        <input type="hidden" class="fldsubtest" name="fldsubtest[{{ $key }}]" value="{{ $option['fldsubtest'] }}">
                                        <input type="hidden" class="fldanswertype" name="fldanswertype[{{ $key }}]" value="{{ $option['fldtanswertype'] }}">
                                        <p>{{ $option['fldsubtest'] }}</p>
                                    </td>
                                    <td style="width: 10%;" class="title">
                                        <input type="checkbox" id="abnormal_check_{{ $key }}" class="abnormal" name="abnormal[{{ $key }}]" {{ (isset($option['pat_abnormal']) && $option['pat_abnormal']) ? "checked='checked'" : '' }}>
                                        <label for="abnormal_check_{{ $key }}">&nbsp; Abnormal</label>
                                    </td>
                                    <td style="width: 70%;" class="title-input">
                                        @if($option['fldtanswertype'] == 'Single Selection' || $option['fldtanswertype'] == 'Multiple Selection')
                                            <select name="answer[{{ $key }}]" class="answer form-control">
                                                @foreach($option['subtests'] as $value)
                                                    <option value="{{ $value['fldanswer'] }}" {{ (isset($option['pat_answers']) && $option['pat_answers'] == $value['fldanswer']) ? 'selected="selected"' : ''  }}>{{ $value['fldanswer'] }}</option>
                                                @endforeach
                                            </select>
                                        @elseif($option['fldtanswertype'] == 'Clinical Scale')

                                            <table id="select-clinical-scale-table">
                                                @php
                                                    $countScale = 0;
                                                @endphp
                                                @foreach($option['option_fldscalegroup'] as $optionClinicalScale => $parentValue)
                                                    @php
                                                        $selectInput = '<select name="clinical_scale_select[]" class="clinical_scale_select">';
                                                    @endphp
                                                    @foreach($parentValue as $scale => $value)
                                                        @php
                                                            $selectInput .= '<option value="'.$value.'">'.$value.'</option>';
                                                        @endphp
                                                    @endforeach
                                                    @php
                                                        $selectInput .= '</select>';
                                                    @endphp
                                                    <tr>
                                                        <td>
                                                            {{ $optionClinicalScale }}
                                                            <input type="hidden" name="clinical_scale_key[]" class="clinical_scale_key" value="{{ $optionClinicalScale }}">
                                                        </td>
                                                        <td>{!! $selectInput !!}</td>
                                                        @if($countScale == 0)
                                                            <td rowspan="10">
                                                                <input type="text" name="clinical_scale_free_text" class="clinical_scale_free_text answer" value="{{ (isset($option['pat_answers']) && $option['pat_answers']) ? $option['pat_answers'] : '' }}">
                                                            </td>
                                                        @endif
                                                    </tr>
                                                    @php
                                                        $countScale++;
                                                    @endphp
                                                @endforeach
                                            </table>

                                        @elseif($option['fldtanswertype'] == 'Quantitative' || $option['fldtanswertype'] == 'Percent Sum')
                                            <input type="number" class="answer form-control" name="answer[{{ $key }}]" value="{{ (isset($option['pat_answers']) && $option['pat_answers']) ? $option['pat_answers'] : '' }}" placeholder="0">
                                        @elseif($option['fldtanswertype'] == 'Left and Right')
                                            @php
                                                $d = (isset($option['pat_answers']) && $option['pat_answers']) ? json_decode(strtolower($option['pat_answers'])) : '';
                                            @endphp
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
                                                            <textarea id="js-observation-left-tbody" style="height: 100%;width: 100%;" name="left">{{ isset($d->left) ? $d->left : '' }}</textarea>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                <table class="table-right table table-striped table-hover table-bordered">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th class="heading">Right</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="right-tbody">
                                                                <textarea id="js-observation-right-tbody" style="height: 100%;width: 100%;" name="right">{{ isset($d->right) ? $d->right : '' }}</textarea>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <textarea class="answer form-control js-textarea" id="js-textarea-{{ $key }}" name="answer[{{ $key }}]">{{ (isset($option['pat_answers']) && $option['pat_answers']) ? $option['pat_answers'] : '' }}</textarea>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </form>
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
                            <div class="input-group">
                                <input type="text" id="js-input-no-selection" class="form-control" name="no_selection" value="{{ $patient_exam->fldreportquali }}">
                                <div class="input-group-append">
                                    <div class="input-group-text"><i class="ri-calculator-line"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($type == 'Left and Right')
                @php
                    $d = json_decode(strtolower($patient_exam->fldreportquali));
                @endphp
                <div class="leftright-container res-table">
                    <table class="table-left table table-hover table-striped table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th class="heading">Left</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="left-tbody">
                                    <textarea id="js-observation-left-tbody" style="height: 100%;width: 100%;" name="left">{{ isset($d->left) ? $d->left : '' }}</textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table-right table table-striped table-hover table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th class="heading">Right</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="right-tbody">
                                    <textarea id="js-observation-right-tbody" style="height: 100%;width: 100%;" name="right">{{ isset($d->right) ? $d->right : '' }}</textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @elseif($type == 'Quantitative')
                <div class="row">
                    <input type="number" id="js-input-element" class="form-control" name="quantitative" style="margin: 15px;" value="{{ $patient_exam->fldreportquali }}">
                </div>
            @elseif($type == 'Single Selection')
                <div class="row">
                    <select id="js-input-element" class="form-control" style="margin: 15px;" name="single_selection">
                        @foreach($options as $option)
                            <option value="{{ $option->fldanswer }}" {{ ($patient_exam->fldreportquali == $option->fldanswer) ? "selected='selected'" : "" }}>{{ $option->fldanswer }}</option>
                        @endforeach
                    </select>
                </div>
            @elseif($type == 'Text Addition')
                <div class="row">
                    <div class="col-sm-8">
                        <textarea  id="js-input-element" class="form-cpntrol js-text-addition-textarea">{{ $patient_exam->fldreportquali }}</textarea>
                    </div>
                </div>
            @endif
            @else
                <div class="row">
                    <input type="text" id="js-input-element" class="form-control" style="margin: 15px;" name="custom_component" value="{{ $patient_exam->fldreportquali }}">
                </div>
            @endif
        </div>
    </div>
</div>
<!-- Modal footer -->
<div class="modal-footer">
    @if($type == 'No Selection')
        <div></div>
    @else
        <input type="button" onclick="quantityObservation.saveQualitativeDataUpdate(this)" id="js-examination-add" class="btn btn-primary" value="Save changes">
    @endif
</div>
<script>
    $(function () {
        $('.clinical_scale_select').change(function () {
            var total = 0;
            $('#select-clinical-scale-table select').each(function () {
                total += +($(this).val());
            });
            $('.clinical_scale_free_text').empty().val(total);
        });
        $('.js-textarea').each(function(e){
            CKEDITOR.replace( this.id, { height: '60px' });
        });
        CKEDITOR.replace('js-reporting-single-selection-textarea', {
            height: '250px',
        });
        CKEDITOR.replace('js-text-addition-textarea', {
            height: '250px',
        });

        $('#js-reporting-single-tbody-modal tr').click(function() {
            var value = CKEDITOR.instances["js-reporting-single-selection-textarea"].getData() + $(this).text().trim();
            CKEDITOR.instances["js-reporting-single-selection-textarea"].setData(value);
        });
    });
</script>
