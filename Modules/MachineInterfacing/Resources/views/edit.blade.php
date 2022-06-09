@extends('frontend.layouts.master')

@section('content')
    <div class="container-fluid extra-fluid">
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-block">
                <button type="button" class="close text-black-50 float-right" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
            </div>
        @endif

        @if ($message = Session::get('error'))
            <div class="alert alert-danger alert-block">
                <button type="button" class="close text-black-50 float-right" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="row">
            <div class="col-sm-12">

                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Machine Map Edit</h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <div class="col-12">
                            <form action="{{route('machine.interfacing.update')}}" method="post">
                                @csrf
                                <input type="hidden" name="_id" value="{{$test_edit->id}}">
                                <div class="form-group form-row">
                                    <label class="col-sm-2">Test</label>
                                    <div class="col-sm-4">
                                        <select class="form-control" name="test" id="tests_select2">
                                            <option value="">---select---</option>
                                            @if($test_edit->fldtype == "test")
                                                @if(count($tests))
                                                    @forelse($tests as $test)
                                                        <option @if($test->fldtestid == $test_edit->test) selected @endif value="{{ $test->fldtestid }}">{{ $test->fldtestid }}</option>
                                                    @empty
                                                    @endforelse
                                                @endif
                                            @elseif($test_edit->fldtype == "subtest")
                                                @if(count($subtests))
                                                    @forelse($subtests as $subtest)
                                                        <option @if($subtest->fldtestid == $test_edit->test) selected @endif value="{{ $subtest->fldtestid }}">{{ $subtest->fldtestid }}</option>
                                                    @empty
                                                    @endforelse
                                                @endif
                                            @else
                                                @if(count($tests))
                                                    @forelse($tests as $test)
                                                        <option @if($test->fldtestid == $test_edit->test) selected @endif value="{{ $test->fldtestid }}">{{ $test->fldtestid }}</option>
                                                    @empty
                                                    @endforelse
                                                @endif
                                                @if(count($subtests))
                                                    @forelse($subtests as $subtest)
                                                        <option @if($subtest->fldtestid == $test_edit->test) selected @endif value="{{ $subtest->fldtestid }}">{{ $subtest->fldtestid }}</option>
                                                    @empty
                                                    @endforelse
                                                @endif
                                            @endif

                                        </select>
                                    </div>
                                </div>
                                <div class="form-group form-row align-items-center">
                                    <label class="col-sm-2">Code</label>
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <input type="text" name="code" class="form-control" value="{{ $test_edit->code }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-row align-items-center">
                                    <label class="col-sm-2">Machine Name</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="machine_name" class="form-control" value="{{ $test_edit->machinename }}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="" class="col-sm-2"></label>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
@push('after-script')
    <script>
        $(document).ready(function () {
            setTimeout(function () {
                $('#tests_select2').select2();
                $('#tests_select2').val('{{$test_edit->test}}');
                $('#tests_select2').trigger('change.select2');
            }, 1500);
        });
    </script>
@endpush
