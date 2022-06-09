@extends('frontend.layouts.master')
@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <ul class="nav nav-tabs justify-content-center" id="myTab-two" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#sample" role="tab" aria-controls="sample" aria-selected="true">Patient Credit Color</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent-1">
                            <div class="tab-pane fade show active" id="sample" role="tabpanel" aria-labelledby="sample">
                                <div class="row">
                                    <div class="col-lg-7 col-md-12">
                                        <form method="POST" action="{{ route('patient.credit.color.update') }}">
                                            {{ csrf_field() }}
                                            <div class="form-group form row align-items-center">
                                                <label for="" class="control-label col-sm-4">Green Days:</label>
                                                <div class="col-sm-6">
                                                    <input type="text" name="green_day" id="green_day" class="form-control" placeholder="" value="{{$credit_color->green_day ?? ''}}">
                                                    @if ($errors->has('green_day'))
                                                        <span class="help-block">
                                                            <strong style="color: red">{{ $errors->first('green_day') }}</strong>
                                                        </span>
                                                    @endif

                                                </div>
                                            </div>
                                            <div class="form-group form row align-items-center">
                                                <label for="" class="control-label col-sm-4">Yellow Days:</label>
                                                <div class="col-sm-6">
                                                    <input type="text" name="yellow_day" id="yellow_day" class="form-control" placeholder="" value="{{$credit_color->yellow_day ?? ''}}">
                                                    @if ($errors->has('yellow_day'))
                                                        <span class="help-block">
                                                            <strong style="color: red">{{ $errors->first('yellow_day') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group form row align-items-center">
                                                <label for="" class="control-label col-sm-4">Red Days:</label>
                                                <div class="col-sm-6">
                                                    <input type="text" name="red_day" id="red_day" class="form-control" placeholder="" value="{{$credit_color->red_day ?? ''}}">
                                                    @if ($errors->has('red_day'))
                                                        <span class="help-block">
                                                            <strong style="color: red">{{ $errors->first('red_day') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group form row align-items-center">
                                                <div class="col-sm-6">
                                                    <button type="subimt" class="btn btn-info">save</button>
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
        </div>
    </div>
@stop
@push('after-script')
@endpush
