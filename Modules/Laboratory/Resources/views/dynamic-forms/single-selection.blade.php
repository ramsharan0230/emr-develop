<div class="modal-header">
    <h6 class="modal-title"><span>Examination / {{ $examid }}</span> {{-- ({{ (isset($header)) ? $header : '' }})--}}</h6>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<!-- Modal body -->
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <input type="hidden" id="js-observation-type-hidden" value="{{ $type }}">
            <input type="hidden" id="js-observation-fldid-hidden" value="{{ $fldid }}">
            <input type="hidden" id="js-observation-examid-hidden" value="{{ $examid }}">
            <input type="hidden" id="js-observation-fldencounterval-hidden" value="{{ $fldencounterval }}">
            @if($examid == 'Urine Routine Examination')
                <a href="javascript:;" class="normalcheckurine btn btn-primary">Normal</a>
                <input type="hidden" id="js-observation-normal-hidden-input" value="0">
            @endif <br>
            @if($examid == 'Culture & Sensitivity')
                <input type="text" id="js-culture-serach-input-modal" class="form-control col-3 mb-2 float-right" placeholder="search......">
                <div class="table-quantative res-table">
                    <form id="js-culture-form">
                        @if ($testoptions)
                            <div class="row">
                                <div class="col-sm-8">
                                    <textarea id="js-reporting-single-selection-textarea">{{ $culture->fldreportquali }}</textarea>
                                </div>
                                <div class="col-sm-4">
                                    <div class="res-table table-sticky-th">
                                        <table class="table table-hover table-striped table-bordered">
                                            <tbody id="js-reporting-single-tbody-modal">
                                            @foreach ($testoptions as $testoption)
                                                <tr>
                                                    <td>{{ $testoption }}</td>
                                                </tr>
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
                                        <td width="50%;">
                                            <table class="table table-bordered">
                                                <tbody class="js-culture-subtable-tody">
                                                @foreach($subTest->subtables as $subtables)
                                                    <tr>
                                                        <td>{{ $subtables->fldvariable }}</td>
                                                        <td>{{ $subtables->fldvalue }}</td>
                                                        <td>{{ $subtables->fldcolm2 }}</td>
                                                        <td>
                                                            <button type="button" class="btn btn-danger js-culture-subtables-value-delete" data-fldid="{{ $subtables->fldid }}"><i class="fa fa-trash"></i></button>
                                                        </td>
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
            @elseif($type == 'Fixed Components' || $type == 'Custom Components') 
                @php
                    $conditionCategory = strtolower($category);
                    $allHisto = ['histopathology', 'histo pathology', 'hesto pathology', 'cytology'];
                    $countCkeditor = 0;
                @endphp
                @if (in_array($conditionCategory, $allHisto))
                    <ul class="nav nav-tabs" id="myTab-1" role="tablist">
                        @foreach($final_tests as $option)
                            <li class="nav-item">
                                <a class="nav-link {{ ($loop->iteration == 1) ? 'active' : ''}}" id="problem-{{ $loop->iteration }}-tab" data-toggle="tab" href="#problem-{{ $loop->iteration }}" role="tab" aria-controls="problem-{{ $loop->iteration }}" aria-selected="false">{{ $option['fldsubtest'] }}</a>
                            </li>
                        @endforeach
                    </ul>
                    <div class="tab-content" id="myTabContent-2">
                        @foreach($final_tests as $key => $option)
                            <div class="tab-pane fade {{ ($loop->iteration == 1) ? 'active show' : ''}}" id="problem-{{ $loop->iteration }}" role="tabpanel" aria-labelledby="problem-{{ $loop->iteration }}-tab">
                                <div class="form-group">
                                    <h6 class="card-title pr-5">{{ $option['fldsubtest'] }}</h6>
                                </div>
                                <div class="row">
                                    <div class="col-sm-10">
                                        <div class="form-group js-fixed-components-tr">
                                            <input type="hidden" class="fldsubtest" name="fldsubtest[{{ $key }}]" value="{{ $option['fldsubtest'] }}">
                                            <input type="hidden" class="fldanswertype" name="fldanswertype[{{ $key }}]" value="{{ $option['fldtanswertype'] }}">
                                            <textarea class="answer form-control js-textarea-for-full-page" id="js-textarea-onchange-{{ $countCkeditor }}" name="answer[{{ $key }}]">{{ (isset($option['pat_answers']) && $option['pat_answers']) ? $option['pat_answers'] : '' }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        @php
                                            $templatesLab = isset($option['templates'])?$option['templates']:[];
                                        @endphp
                                        @if($templatesLab)
                                            <ul>
                                                @forelse($templatesLab as $tmplate)
                                                    <li><a href="javascript:;" class="template_for_lab_on_change" data-id="{{ $tmplate['id'] }}" data-count="{{ $countCkeditor }}">{{ $tmplate['title'] }}</a></li>

                                                @empty

                                                @endforelse
                                            </ul>

                                        @endif
                                    </div>
                                </div>
                                @php
                                    $countCkeditor++;
                                @endphp
                            </div>

                        @endforeach
                    </div>
                @else
                    <div class="table-quantative res-table">
                        <form id="js-fixed-components-form">
                            <table class="table table-hover table-striped table-bordered">
                                <tbody>
                                @foreach($final_tests as $key => $option)
                                    <tr class="js-fixed-components-tr">
                                        <td style="width: 15%;" class="title">
                                            <input type="hidden" class="fldsubtest" name="fldsubtest[{{ $key }}]" value="{{ $option['fldsubtest'] }}">
                                            <input type="hidden" class="fldanswertype" name="fldanswertype[{{ $key }}]" value="{{ $option['fldtanswertype'] }}">
                                            <p>{{ $option['fldsubtest'] }}</p>
                                        </td>
                                        @if($option['fldtanswertype'] == 'Single Selection' || $option['fldtanswertype'] == 'Multiple Selection')
                                            <td>
                                                <select name="answer[{{ $key }}]" class="answer form-control">
                                                    @foreach($option['subtests'] as $value)
                                                        <option value="{{ $value['fldanswer'] }}" {{ (isset($option['pat_answers']) && $option['pat_answers'] == $value['fldanswer']) ? 'selected="selected"' : ''  }}>{{ $value['fldanswer'] }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        @elseif($option['fldtanswertype'] == 'Clinical Scale')
                                            <td style="width: 70%;" class="title-input">
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
                                            </td>
                                        @elseif($option['fldtanswertype'] == 'Quantitative' || $option['fldtanswertype'] == 'Percent Sum')
                                            <td style="width: 70%;" class="title-input">
                                                <input type="number" class="answer form-control" name="answer[{{ $key }}]" value="{{ (isset($option['pat_answers']) && $option['pat_answers']) ? $option['pat_answers'] : '' }}" placeholder="0">
                                            </td>
                                        @elseif($option['fldtanswertype'] == 'Left and Right')
                                            @php
                                                $d = (isset($option['pat_answers']) && $option['pat_answers']) ? json_decode(strtolower($option['pat_answers'])) : '';
                                            @endphp
                                            <td>
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
                                            </td>
                                        @else
                                            <td>
                                                <textarea class="answer form-control js-textarea" id="js-textarea-onchange-{{ $countCkeditor }}" name="answer[{{ $key }}]">{{ (isset($option['pat_answers']) && $option['pat_answers']) ? $option['pat_answers'] : '' }}</textarea>
                                            </td>
                                            <td>
                                                @if (isset($option['subtests']) && $option['subtests'])
                                                    <select class="js-reporting-refrance-select form-control">
                                                        <option value="">--Select--</option>
                                                        @foreach ($option['subtests'] as $subtest)
                                                        <option value="{{ $subtest['fldanswer'] }}">{{ $subtest['fldanswer'] }}</option>
                                                        <li class="list-group-item p-0">{{ $subtest['fldanswer'] }}</li>
                                                        @endforeach
                                                    </select>
                                                @endif
                                            </td>
                                        @endif
                                    </tr>
                                    @php
                                        $countCkeditor++;
                                    @endphp
                                @endforeach
                                </tbody>
                            </table>
                        </form>
                    </div>
                @endif
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
                    <div class="col-sm-12">
                        <textarea  id="js-input-element" class="form-cpntrol js-textarea">{{ $patient_exam->fldreportquali }}</textarea>
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-sm-8">
                        <input type="text" id="js-input-element" class="form-control" style="margin: 15px;" name="custom_component" value="{{ $patient_exam->fldreportquali }}">
                    </div>
                    <div class="col-sm-4">
                        @if ($test->testoptions && $test->testoptions->isNotEmpty())
                            <select class="js-reporting-refrance-select form-control">
                                <option value="">--Select--</option>
                                @foreach ($test->testoptions as $testoption)
                                <option value="{{ $testoption->fldanswer }}">{{ $testoption->fldanswer }}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>
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
        <input type="button" onclick="quantityObservation.saveQualitativeData(this)" id="js-examination-add" class="btn btn-primary" value="Save changes">
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
        $('.js-textarea').each(function (e) {
            CKEDITOR.replace(this.id, {height: '50px'});
        });
        var height = screen.height/2.9 + 'px';
        $('.js-textarea-for-full-page').each(function (e) {
            CKEDITOR.replace(this.id, {height: height});
        });
        if ($("#js-reporting-single-selection-textarea").length > 0) {
            CKEDITOR.replace('js-reporting-single-selection-textarea', {
                height: '350px',
            });

            $('#js-reporting-single-tbody-modal tr').click(function () {
                var value = CKEDITOR.instances["js-reporting-single-selection-textarea"].getData() + $(this).text().trim();
                CKEDITOR.instances["js-reporting-single-selection-textarea"].setData(value);
            });
        }
    });

    $(document).on('keyup', '#js-culture-serach-input-modal', function() {
        var searchText = $(this).val().toUpperCase();
        $.each($('#js-culture-subtest-tbody tr td:first-child'), function(i, e) {
            var tdText = $(e).text().trim().toUpperCase();

            if (tdText.search(searchText) >= 0)
                $(e).closest('tr').show();
            else
                $(e).closest('tr').hide();
        });
    });

    $(document).on('change', '.js-reporting-refrance-select', function() {
        var updateableElem = $(this).closest('td').prev('td').find('.js-textarea');
        var id = $(updateableElem).attr('id');
        if (CKEDITOR.instances[id]) {
            var value = CKEDITOR.instances[id].getData() + $(this).val();
            CKEDITOR.instances[id].setData(value);
        } else {
            $(this).closest('div.row').find('input').val($(this).val());
        }
    });

    $(document).ready(function () {
        $('.js-fixed-components-tr select[name="answer[Result]"]').trigger('change');
        if ($(".template_for_lab_on_change").length > 0) {
            $(".template_for_lab_on_change").on('click', function () {
                editorId = "js-textarea-onchange-" + $(this).data('count');

                $.ajax({
                    url: "{{ route('laboratory.template.get.body') }}",
                    type: "POST",
                    data: {id: $(this).data('id')},
                    success: function (response) {
                        CKEDITOR.instances[editorId].setData(response.htmlData)
                    }
                });
            });
        }

        $(document).on( "click", ".abnormalcheckurine", function() {
            $('#js-observation-normal-hidden-input').val('0');
            $(this).removeClass('abnormalcheckurine').addClass('normalcheckurine');
            $(this).removeClass('btn-secondary').addClass('btn-primary');
            $(this).text('Normal')
        });

        $(document).on( "click", ".normalcheckurine", function() {
            $('#js-observation-normal-hidden-input').val('1');
            $(this).removeClass('normalcheckurine').addClass('abnormalcheckurine');
            $(this).removeClass('btn-primary').addClass('btn-secondary');
            $(this).text('Abnormal')

            $('select[name="answer[Colour]"] option[value="Light yellow"]').attr("selected", "selected");
            $('select[name="answer[Transparency]"] option[value="Clear"]').attr("selected", "selected");
            $('select[name="answer[Reaction]"] option[value="Acidic"]').attr("selected", "selected");
            $('select[name="answer[Albumin]"] option[value="Nil"]').attr("selected", "selected");
            $('select[name="answer[Sugar]"] option[value="Nil"]').attr("selected", "selected");
            $('select[name="answer[Pus cells]"] option[value="1-2/HPF"]').attr("selected", "selected");
            $('select[name="answer[RBC]"] option[value="Nil"]').attr("selected", "selected");
            $('select[name="answer[Epithelial cells]"] option[value="1-2/HPF"]').attr("selected", "selected");
            $('select[name="answer[Cast]"] option[value="Not seen"]').attr("selected", "selected");
            $('select[name="answer[Phosphates]"] option[value="Not seen"]').attr("selected", "selected");
            $('select[name="answer[Uric Acid]"] option[value="Not seen"]').attr("selected", "selected");
            $('select[name="answer[Calcium Oxalate]"] option[value="Not seen"]').attr("selected", "selected");
            $('select[name="answer[Urates]"] option[value="Not seen"]').attr("selected", "selected");
            $('select[name="answer[SP-gravity]"] option[value="Not seen"]').attr("selected", "selected");
            $('select[name="answer[Acetone]"] option[value="Not seen"]').attr("selected", "selected");
            $('select[name="answer[Chyle]"] option[value="Not seen"]').attr("selected", "selected");

            $('input[name="answer[Colour]"]').val("Light yellow");
            $('input[name="answer[Transparency]"]').val("Clear");
            $('input[name="answer[Reaction]"]').val("Acidic");
            $('input[name="answer[Albumin]"]').val("Nil");
            $('input[name="answer[Sugar]"]').val("Nil");
            $('input[name="answer[Pus cells]"]').val("1-2/HPF");
            $('input[name="answer[RBC]"]').val("Nil");
            $('input[name="answer[Epithelial cells]"]').val("1-2/HPF");
            $('input[name="answer[Cast]"]').val("Not seen");
            $('input[name="answer[Phosphates]"]').val("Not seen");
            $('input[name="answer[Uric Acid]"]').val("Not seen");
            $('input[name="answer[Calcium Oxalate]"]').val("Not seen");
            $('input[name="answer[Urates]"]').val("Not seen");
            $('input[name="answer[SP-gravity]"]').val("Not seen");
            $('input[name="answer[Acetone]"]').val("Not seen");
            $('input[name="answer[Chyle]"]').val("Not seen");

            var normalvalue = {
                colour: "Light yellow",
                transparency: "Clear",
                reaction: "Acidic",
                albumin: "Nil",
                sugar: "Nil",
                puscells: "1-2/HPF",
                rbc: "Nil",
                epithelialcells: "1-2/HPF",
                cast: "Not seen",
                phosphates: "Not seen",
                uricacid: "Not seen",
                calciumoxalate: "Not seen",
                urates: "Not seen",
                spgravity: "Not seen",
                acetone: "Not seen",
                chyle: "Not seen",
            };
            $.each($('textarea.js-textarea'), function(i, elem) {
                var id = $(elem).attr('id');
                var name = $(elem).attr('name').match(/answer\[(.*?)\]/)[1];
                name = name.toLowerCase().replace(/[- )(]/g,'');
                if (CKEDITOR.instances[id] && normalvalue[name]) {
                    CKEDITOR.instances[id].setData(normalvalue[name]);
                }
            });
        });
    })
</script>
