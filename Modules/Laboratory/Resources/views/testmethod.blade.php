@extends('frontend.layouts.master')

@push('after-styles')
<link rel="stylesheet" href="{{ asset('new/css/bootstrap-tagsinput.css') }}"/>
<style>
    .bootstrap-tagsinput {
        width: 100%;
    }
    .label-info {
        background-color: #5bc0de;
    }
</style>
@endpush

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            @if(Session::get('success_message'))
            <div class="alert alert-success containerAlert">
                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span
                        class="sr-only">Close</span></button>
                {{ Session::get('success_message') }}
            </div>
            @endif
        
            @if(Session::get('success_message_special'))
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span
                        class="sr-only">Close</span></button>
                {!! Session::get('success_message_special') !!}
            </div>
            @endif
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">
                            Test Methods
                        </h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    @if(Session::get('error_message'))
                    <div class="alert alert-danger containerAlert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span
                                class="sr-only">Close</span></button>
                        {{ Session::get('error_message') }}
                    </div>
                    @endif

                    <form method="post">
                        @csrf
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="dietarytable">
                                    <select class="form-control" name="fldtestid">
                                        <option value="">--Select--</option>
                                        @if(isset($tests))
                                        @foreach($tests as $test)
                                        <option data-fldmethods="{{ $test->methods ? implode(',', $test->methods->pluck('fldmethod')->toArray()) : '' }}" value="{{ $test->fldtestid }}" {{ request()->get('fldtestid') == $test->fldtestid ? 'selected' : '' }}>{{ $test->fldtestid }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                    @if(isset($form_errors['fldtestid'])) <div class="text-danger">{{ $form_errors['fldtestid'] }} </div>@endif
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="dietarytable">
                                    <input type="text" class="form-control" data-role="tagsinput" value="{{ request()->get('fldmethod') }}" name="fldmethod">
                                    <div style="color: #17a2b8!important;">Seprate test methods with comma(,). </div>
                                    @if(isset($form_errors['fldmethod'])) <div class="text-danger">{{ $form_errors['fldmethod'] }} </div>@endif
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="dietarytable">
                                    <button class="btn btn-primary">Save</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('after-script')
<script src="{{ asset('new/js/bootstrap-tagsinput.min.js') }}"></script>
<script>
    $(document).on('change', 'select[name="fldtestid"]', function() {
        $('input[data-role="tagsinput"]').tagsinput('removeAll');
        var fldmethods = $('select[name="fldtestid"] option:selected').data('fldmethods') || '';
        $.each(fldmethods.split(','), function(i, tag) {
            $('input[data-role="tagsinput"]').tagsinput('add', tag);
        });
    });
</script>
@endpush
