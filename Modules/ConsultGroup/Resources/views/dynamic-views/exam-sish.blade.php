<form class="row" id="exam-sish-form">
    @csrf
    <input type="hidden" name="tp_color_code" value="{{ $tp_color_code }}">
    <div class="col-6">
        <div class="form-group form-row align-items-center">
            <label for="tp-target" class="col-sm-2">Target</label>
            <div class="col-sm-10">
                <select name="exam_class" class="form-control exam-class" onchange="getExamParams()" required>
                    <option value=""></option>
                    <option value="Qualitative">Qualitative</option>
                    <option value="Quantitative">Quantitative</option>
                </select>
            </div>
        </div>
    </div>
    <div class="col-6 radio-sish">
        <div class="form-group">
            <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" id="si-unit" name="si-metric-unit" class="custom-control-input">
                <label class="custom-control-label" for="si-unit">SI Unit</label>
            </div>
            <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" id="metric-unit" name="si-metric-unit" class="custom-control-input">
                <label class="custom-control-label" for="metric-unit">Metric</label>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group form-row align-items-center">
            <label for="" class="col-sm-2">Params</label>
            <div class="col-sm-10">
                <select name="exam_param" class="exam-param form-control" required>
                    <option value=""></option>
                </select>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <div class="col-2 custom-control custom-radio">
                <input type="radio" name="exam_value_range" class="exam-value custom-control-input" id="comp_value" value="comp_value">
                <label for="comp_value" class="custom-control-label">Value</label>
            </div>
            <div class="value-select col-4">
                <select name="exam_value" class="exam-value-select form-control">
                    <option value=""></option>
                </select>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <div class=" col-2 custom-control custom-radio">
                <input type="radio" name="exam_value_range" class="exam-range custom-control-input" id="comp_range" value="comp_range">
                <label for="comp_range" class="custom-control-label">Range</label>
            </div>
            <div class="col-8 range-select row">
                <select name="exam_comparision" class="form-control exam-comparision col-4">
                    <option value=""></option>
                    <option value="=">=</option>
                    <option value="<"><</option>
                    <option value="<="><=</option>
                    <option value=">">></option>
                    <option value=">=">>=</option>
                    <option value="<>"><></option>
                    <option value="Min">Min</option>
                    <option value="Max">Max</option>
                </select>
                <input type="number" name="exam_range_number" class="exam_range_number col-4 form-control" value="0">
                <select name="exam_unit" class="exam-unit form-control col-4">
                    <option value=""></option>
                    {{--<option value="Hours">Hours</option>
                    <option value="Days">Days</option>
                    <option value="Weeks">Weeks</option>
                    <option value="Months">Months</option>
                    <option value="Episodes">Episodes</option>--}}
                </select>
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="form-group form-row align-items-center">
            <label for="" class="col-sm-4 base-rate">Base Rate</label>
            <div class="col-sm-8">
                <input type="number" name="exam_base_rate" class="form-control base-rate">
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="form-group form-row align-items-center">
            <label for="" class="col-sm-4 hit-rate">Hit Rate</label>
            <div class="col-sm-8">
                <input type="number" name="exam_hit_rate" class="form-control hit-rate">
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="form-group form-row align-items-center">
            <label for="" class="col-sm-4 false-alarm-rate">False Alarm Rate</label>
            <div class="col-sm-8">
                <input type="number" name="exam_false_alarm_rate" class="false-alarm-rate form-control">
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-12">
        <a href="javascript:;" class="btn btn-primary btn-sm" onclick="triageExam.sishExamAdd()"><i class="ri-check-line h5"></i> Update</a>
    </div>
</form>
<script>
    $('input:radio[name="exam_value_range"]').change(function () {
        if ($(this).is(':checked') && $(this).val() === 'comp_range') {
            $('.value-select').hide();
            $('.range-select').show();
        }
        if ($(this).is(':checked') && $(this).val() === 'comp_value') {
            $('.range-select').hide();
            $('.value-select').show();
        }
    });

    function getExamParams() {
        $.ajax({
            url: "{{ route('consultant.triage.parameter.exam.param') }}",
            type: "post",
            data: {target: $('.exam-class').val(), "_token": "{{ csrf_token() }}"},
            success: function (data) {
                // console.log(data);
                if ($('.exam-class').val() === 'Qualitative') {
                    $('.value-select').hide();
                    $('.range-select').show();
                    $(".exam-range").prop("checked", false);
                    $(".exam-value").prop("checked", true);
                } else if ($('.exam-class').val() === 'Quantitative') {
                    $('.range-select').hide();
                    $('.value-select').show();
                    $(".exam-range").prop("checked", true);
                    $(".exam-value").prop("checked", false);
                }

                $('.exam-param').html(data);
            },
            error: function (xhr, err) {
                console.log(xhr);
            }
        });
    }


</script>
