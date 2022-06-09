@extends('frontend.layouts.master')
@push('after-styles')
<style>
.border-none{
    border: none
}
.full-width{
    width: 100%;
}
</style>
@endpush

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">ABC Analysis Settings</h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <ul class="nav nav-tabs justify-content-center" id="myTab-2" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#item_class" role="tab"
                                   aria-controls="item_class" aria-selected="false">Item Class Setup</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#moving_type" role="tab"
                                   aria-controls="moving_type" aria-selected="false">Moving Type Setup</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent-3">
                            <div class="tab-pane fade show active" id="item_class" role="tabpanel"
                                 aria-labelledby="item_class">
                                <form action="{{route('abcanalysis.saveItemClass')}}" method="POST" id="itemClassForm">
                                    <table class="border-none full-width">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Item Class</th>
                                                <th class="text-center"></th>
                                                <th class="text-center">Standard Values</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td rowspan="2" class="text-center">Class A</td>
                                                <td>
                                                    <div class="row">
                                                        <div class="col-lg-4">
                                                            <label>Consumption %:</label>
                                                        </div>
                                                        <div class="col-lg-8">
                                                            <input type="number" min="0" max="100" name="consumption[a]" value="{{ (Options::get('classA_consumption') != false) ? Options::get('classA_consumption') : 0 }}" placeholder="0" class="form-control consumption numvalidate">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">20 %</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="row">
                                                        <div class="col-lg-4">
                                                            <label>Revenue %:</label>
                                                        </div>
                                                        <div class="col-lg-8">
                                                            <input type="number" min="0" max="100" name="revenue[a]" value="{{ (Options::get('classA_revenue') != false) ? Options::get('classA_revenue') : 0 }}" placeholder="0" class="form-control revenue numvalidate">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">80 %</td>
                                            </tr>
                                            <tr>
                                                <td rowspan="2" class="text-center">Class B</td>
                                                <td>
                                                    <div class="row">
                                                        <div class="col-lg-4">
                                                            <label>Consumption %:</label>
                                                        </div>
                                                        <div class="col-lg-8">
                                                            <input type="number" min="0" max="100" name="consumption[b]" value="{{ (Options::get('classB_consumption') != false) ? Options::get('classB_consumption') : 0 }}" placeholder="0" class="form-control consumption numvalidate">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">30 %</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="row">
                                                        <div class="col-lg-4">
                                                            <label>Revenue %:</label>
                                                        </div>
                                                        <div class="col-lg-8">
                                                            <input type="number" min="0" max="100" name="revenue[b]" value="{{ (Options::get('classB_revenue') != false) ? Options::get('classB_revenue') : 0 }}" placeholder="0" class="form-control revenue numvalidate">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">15 %</td>
                                            </tr>
                                            <tr>
                                                <td rowspan="2" class="text-center">Class C</td>
                                                <td>
                                                    <div class="row">
                                                        <div class="col-lg-4">
                                                            <label>Consumption %:</label>
                                                        </div>
                                                        <div class="col-lg-8">
                                                            <input type="number" min="0" max="100" name="consumption[c]" value="{{ (Options::get('classC_consumption') != false) ? Options::get('classC_consumption') : 0 }}" placeholder="0" class="form-control consumption numvalidate">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">50 %</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="row">
                                                        <div class="col-lg-4">
                                                            <label>Revenue %:</label>
                                                        </div>
                                                        <div class="col-lg-8">
                                                            <input type="number" min="0" max="100" name="revenue[c]" value="{{ (Options::get('classC_revenue') != false) ? Options::get('classC_revenue') : 0 }}" placeholder="0" class="form-control revenue numvalidate">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">5 %</td>
                                            </tr>
                                        </tbody>
                                    </table><br>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <a href="javascript:void(0)" class="btn btn-primary btn-action pull-right" type="button" id="saveItemClass" style="float:right;"><i class="fa fa-check"></i>&nbsp;Save</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="moving_type" role="tabpanel" aria-labelledby="moving_type">
                                <form action="{{route('abcanalysis.saveMovingType')}}" method="POST" id="movingTypeForm">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <table class="border-none full-width">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">Moving Type</th>
                                                        <th class="text-center">Quantity</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="text-center">Fast: >=</td>
                                                        <td>
                                                            <input type="number" min="0" name="quantity[fast]" value="{{ (Options::get('abc_quan_fast') != false) ? Options::get('abc_quan_fast') : 0 }}" placeholder="0" class="form-control quantity">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">Medium: >=</td>
                                                        <td>
                                                            <input type="number" min="0" name="quantity[medium]" value="{{ (Options::get('abc_quan_med') != false) ? Options::get('abc_quan_med') : 0 }}" placeholder="0" class="form-control quantity">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">Slow: >=</td>
                                                        <td>
                                                            <input type="number" min="0" name="quantity[slow]" value="{{ (Options::get('abc_quan_slow') != false) ? Options::get('abc_quan_slow') : 0 }}" placeholder="0" class="form-control quantity">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">Non: >=</td>
                                                        <td>
                                                            <input type="number" min="0" name="quantity[non]" value="{{ (Options::get('abc_quan_non') != false) ? Options::get('abc_quan_non') : 0 }}" placeholder="0" class="form-control quantity">
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-lg-6">
                                            <table class="border-none full-width">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">Value Type</th>
                                                        <th class="text-center">Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="text-center">High: >=</td>
                                                        <td>
                                                            <input type="number" min="0" name="amount[high]" value="{{ (Options::get('abc_amt_high') != false) ? Options::get('abc_amt_high') : 0 }}" placeholder="0" class="form-control amount">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">Medium: >=</td>
                                                        <td>
                                                            <input type="number" min="0" name="amount[medium]" value="{{ (Options::get('abc_amt_med') != false) ? Options::get('abc_amt_med') : 0 }}" placeholder="0" class="form-control amount">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">Low: >=</td>
                                                        <td>
                                                            <input type="number" min="0" name="amount[low]" value="{{ (Options::get('abc_amt_low') != false) ? Options::get('abc_amt_low') : 0 }}" placeholder="0" class="form-control amount">
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <a href="javascript:void(0)" class="btn btn-primary btn-action pull-right" type="button" id="saveMovingType" style="float:right;"><i class="fa fa-check"></i>&nbsp;Save</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@push('after-script')
    <script>
        $(document).on('blur','.numvalidate',function(){
            var value = Number($(this).val() || 0);
            if(value < 0 || value > 100){
                $(this).val(0);
                showAlert('Must be between 0 and 100', 'fail');
            }
        });

        $(document).on('blur','.quantity',function(){
            var value = Number($(this).val() || 0);
            if(value < 0){
                $(this).val(0);
                showAlert('Must be greater than or equal to 0', 'fail');
            }
        });

        $(document).on('blur','.amount',function(){
            var value = Number($(this).val() || 0);
            if(value < 0){
                $(this).val(0);
                showAlert('Must be greater than or equal to 0', 'fail');
            }
        });

        $(document).on('click','#saveItemClass',function(){
            var total_consumption = 0;
            $.each($('.consumption'), function(i, e) {
                var consumption = ($(e).val()) ? parseInt($(e).val()) : 0;
                total_consumption += consumption;
            });

            if(total_consumption != 100){
                showAlert('Total Consumption must be equal to 100','error');
                return false;
            }

            var total_revenue = 0;
            $.each($('.revenue'), function(i, e) {
                var revenue = ($(e).val()) ? parseInt($(e).val()) : 0;
                total_revenue += revenue;
            });

            if(total_revenue != 100){
                showAlert('Total Revenue must be equal to 100','error');
                return false;
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                }
            });
            $.ajax({
                type: 'post',
                url: '{{route("abcanalysis.saveItemClass")}}',
                data: new FormData($('#itemClassForm')[0]),
                contentType: false,
                cache:false,
                processData: false,
                dataType:"json",
                success: function(res) {
                    if (!res.status) {
                        showAlert(res.msg,'error');
                    } else {
                        showAlert(res.msg);
                    }
                }
            });
        });

        $(document).on('click','#saveMovingType',function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                }
            });
            $.ajax({
                type: 'post',
                url: '{{route("abcanalysis.saveMovingType")}}',
                data: new FormData($('#movingTypeForm')[0]),
                contentType: false,
                cache:false,
                processData: false,
                dataType:"json",
                success: function(res) {
                    if (!res.status) {
                        showAlert(res.msg,'error');
                    } else {
                        showAlert(res.msg);
                    }
                }
            });
        });
    </script>
@endpush

