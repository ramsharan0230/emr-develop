<form class="row" id="complaints-sish">
    @csrf
    <input type="hidden" name="tp_color_code" value="{{ $tp_color_code }}">
    <div class="col-6">
        <div class="form-group">
            <label for="tp-target" class="">Target</label>
            <select name="complaint_class" class="form-control complaint-class" onchange="getComplaintsParams()" required>
                <option value=""></option>
                @if(count($complaintClass))
                    @foreach($complaintClass as $class)
                        <option value="{{ $class->flclass }}">{{ $class->flclass }}</option>
                    @endforeach
                @endif
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
            <select name="complaint_param" class="form-control complaint-param" required>
                <option value=""></option>
            </select>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <div class="col-2 custom-control custom-radio">
                <input type="radio" name="complaint_value_range" class="complaint-value custom-control-input" value="comp_value">
                <label class="custom-control-label">Value</label>
            </div>
            <div class="col-4 value-select">
                <select name="complaint_value" class="form-control complaint-value-select">
                    <option value=""></option>
                </select>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <div class="col-2 custom-control custom-radio">
                <input type="radio" name="complaint_value_range" class="complaint-range custom-control-input" value="comp_range">
                <label class="custom-control-label">Range</label>
            </div>
            <div class="col-8 range-select row">
                <select name="complaint_comparision" class="form-control complaint-comparision col-4">
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
                <input type="number" name="complaint_range_number" class="complaint_range_number col-4 form-control" value="0">
                <select name="complaint_unit" class="form-control complaint-unit col-4">
                    <option value=""></option>
                    <option value="Hours">Hours</option>
                    <option value="Days">Days</option>
                    <option value="Weeks">Weeks</option>
                    <option value="Months">Months</option>
                    <option value="Episodes">Episodes</option>
                </select>
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="form-group">
            <label for="" class="base-rate">Base Rate</label>
            <input type="number" name="complaint_base_rate" class="form-control base-rate">
        </div>
    </div>
    <div class="col-4">
        <div class="form-group">
            <label for="" class="hit-rate">Hit Rate</label>
            <input type="number" name="complaint_hit_rate" class="form-control hit-rate">
        </div>
    </div>
    <div class="col-4">
        <div class="form-group">
            <label for="" class="false-alarm-rate">False Alarm Rate</label>
            <input type="number" name="complaint_false_alarm_rate" class="form-control false-alarm-rate">
        </div>
    </div>
    <div class="col-12">
        <a href="javascript:;" class="btn btn-primary btn-sm" onclick="triageComplaints.sishComplaintsAdd()"><img src="{{ asset('images/tick.png') }}" alt="update" style="width: 15px;"> Update</a>
    </div>
</form>
<script>
    $('input:radio[name="complaint_value_range"]').change(function () {
        if ($(this).is(':checked') && $(this).val() === 'comp_range') {
            $('.value-select').hide();
            $('.range-select').show();
        }
        if ($(this).is(':checked') && $(this).val() === 'comp_value') {
            $('.range-select').hide();
            $('.value-select').show();
        }
    });

    function getComplaintsParams() {
        $.ajax({
            url: "{{ route('consultant.triage.parameter.complaints.param') }}",
            type: "post",
            data: {target: $('.complaint-class').val(), "_token": "{{ csrf_token() }}"},
            success: function (data) {
                // console.log(data);
                $(".complaint-range").prop("checked", true);
                $('.value-select').hide();

                $('.complaint-param').html(data);
            },
            error: function (xhr, err) {
                console.log(xhr);
            }
        });
    }
</script>
