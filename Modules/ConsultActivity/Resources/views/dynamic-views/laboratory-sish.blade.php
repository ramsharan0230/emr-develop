<form class="row" id="lab-sish-form">
    @csrf
    <input type="hidden" name="tp_color_code" value="{{ $tp_color_code }}">
    <div class="col-6">
        <div class="form-group">
            <label for="tp-target" class="">Target</label>
            <select name="lab_class" class="form-control lab-class" onchange="getExamParams()" required>
                <option value=""></option>
                <option value="Qualitative">Qualitative</option>
                <option value="Quantitative">Quantitative</option>
            </select>
        </div>
    </div>
    <div class="col-6 radio-sish">
        <div class="form-group">
            <input type="radio" id="si-unit" name="si-metric-unit" value="SI Unit">
            <label for="si-unit" class="si-unit">SI Unit</label>

            <input type="radio" id="metric-unit" name="si-metric-unit" value="Metric Unit">
            <label for="metric-unit" class="metric-unit">Metric</label>
        </div>
    </div>

    <div class="col-6">
        <div class="form-group">
            <label for="" class="si-unit">Params</label>
            <select name="lab_param" class="form-control lab-param" required>
                <option value=""></option>
            </select>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <div class="col-2 custom-control custom-radio">
                <input type="radio" name="lab_value_range" class="lab-value custom-control-input" id="comp_value" value="comp_value">
                <label for="comp_value" class="custom-control-label"> Value</label>
            </div>
            <div class="value-select col-4">
                <select name="lab_value" class="form-control lab-value-select">
                    <option value=""></option>
                </select>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <div class="col-2 custom-control custom-radio">
                <input type="radio" name="lab_value_range" class="lab-range custom-control-input" id="comp_range" value="comp_range">
                <label for="comp_range" class="custom-control-label"> Range</label>
            </div>
            <div class="range-select col-8 row">
                <select name="lab_comparision" class="form-control lab-comparision col-4">
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
                <input type="number" name="lab_range_number" class="form-control lab_range_number col-4" value="0">
                <select name="lab_unit" class="form-control lab-unit col-4">
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
        <div class="form-group">
            <label for="" class="base-rate">Base Rate</label>
            <input type="number" name="lab_base_rate" class="form-control base-rate">
        </div>
    </div>
    <div class="col-4">
        <div class="form-group">
            <label for="" class="hit-rate">Hit Rate</label>
            <input type="number" name="lab_hit_rate" class="form-control hit-rate">
        </div>
    </div>
    <div class="col-4">
        <div class="form-group">
            <label for="" class="false-alarm-rate">False Alarm Rate</label>
            <input type="number" name="lab_false_alarm_rate" class="form-control false-alarm-rate">
        </div>
    </div>
    <div class="col-4">
        <a href="javascript:;" class="btn btn-primary btn-sm" onclick="triagelab.sishlabAdd()"><img src="{{ asset('images/tick.png') }}" alt="update" style="width: 15px;"> Update</a>
    </div>
</form>
<script>
    $('input:radio[name="lab_value_range"]').change(function () {
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
            url: "{{ route('consultant.triage.parameter.lab.param') }}",
            type: "post",
            data: {target: $('.lab-class').val(), "_token": "{{ csrf_token() }}"},
            success: function (data) {
                // console.log(data);
                if ($('.lab-class').val() === 'Qualitative') {
                    $('.range-select').hide();
                    $('.value-select').show();
                    $(".lab-value").prop("checked", true);
                    $(".lab-range").prop("checked", false);
                } else if ($('.lab-class').val() === 'Quantitative') {
                    $('.value-select').hide();
                    $('.range-select').show();
                    $(".lab-value").prop("checked", false);
                    $(".lab-range").prop("checked", true);
                }

                $('.lab-param').html(data);
            },
            error: function (xhr, err) {
                console.log(xhr);
            }
        });
    }


</script>
