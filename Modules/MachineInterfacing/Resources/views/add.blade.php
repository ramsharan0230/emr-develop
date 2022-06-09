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
                            <h4 class="card-title">Machine Map Add</h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <div class="col-12">
                            <form action="{{route('machine.interfacing.create')}}" method="post" id="machine-mapping-form">
                                @csrf
                                <div class="form-group form-row">
                                    <label class="col-sm-2">Test</label>
                                    <div class="col-sm-4">
                                        <select class="form-control" name="test" id="tests_select2">
                                            <option value="">---select---</option>
                                            @if(count($tests))
                                                @forelse($tests as $test)
                                                    <option value="{{ $test->fldtestid }}">{{ $test->fldtestid }}</option>
                                                @empty
                                                @endforelse
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group form-row">
                                    <label class="col-sm-2">Sub Test</label>
                                    <div class="col-sm-4">
                                        <select class="form-control" name="sub_test" id="tests_select_sub_test">
                                            <option value="">---select---</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group form-row align-items-center">
                                    <label class="col-sm-2">Code</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="code" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group form-row align-items-center">
                                    <label class="col-sm-2">Machine Name</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="machine_name" class="form-control">
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

            }, 1000);
            $('#tests_select2').on('select2:select', function (e) {
                $.ajax({
                    url: "{{ route('machine.interfacing.get.sub.test') }}",
                    type: "POST",
                    data: $("#machine-mapping-form").serialize(),
                    success: function (response) {
                        // console.log(response);
                        $('#tests_select_sub_test').empty().append(response);
                        $('#tests_select_sub_test').select2();
                    }
                });
            });
        });
    </script>
@endpush
