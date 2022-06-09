@extends('frontend.layouts.master')
@section('content')
    <div class="container-fluid extra-fluid">
        <div class="row">
            <div class="col-sm-12">
            <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">Create Municipality</h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if(Session::get('success_message'))
                        <div class="alert alert-success containerAlert">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                            {{ Session::get('success_message') }}
                        </div>
                    @endif

                    @if(Session::get('error_message'))
                        <div class="alert alert-success containerAlert">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                            {{ Session::get('error_message') }}
                        </div>
                    @endif
                    <form action="{{ route('municipality.store') }}" method="POST" id="create-municipality-form" class="form-horizontal">
                        @csrf
                        <div class="form-group row">
                            <label class="control-label col-sm-2 align-self-center mb-0" for="">Province:</label>
                            <div class="col-sm-4">
                                <select name="province" id="province" class="form-control">
                                    <option value="">--Select--</option>
                                    @foreach($provinces as $province)
                                        <option value="{{ $province->fldprovince }}">{{ $province->fldprovince }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-sm-2 align-self-center mb-0" for="">District:</label>
                            <div class="col-sm-4">
                                <select name="district" id="district" class="form-control">
                                    <option value="">--Select--</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-sm-2 align-self-center mb-0" for="">Municipality:</label>
                            <div class="col-sm-4">
                                <input type="text" id="municipality" name="municipality" class="form-control" value="{{ old('municipality') }}" placeholder="Enter Municipality Name">
                            </div>

                        </div>

                        <div class="form-group mt-5">
                            <button type="submit" class="btn btn-primary">Create</button>
                            <a href="{{ route('municipality') }}" class="btn iq-bg-danger">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
            </div>
        </div>
    </div>
@endsection

@push('after-script')
    <style>
        .error {
            color: red;
            font-size: 10px;
        }
    </style>
    <script>
        var provinceSelector = 'province';
        var districtSelector = 'district';
        var selectOption = $('<option>',{val:0,text:'--Select--'});

        $('#province').change(function() {
            getDistrict($(this).val(), null);
        });

        function getDistrict(id, districtId) {
            if (id === "" || id === null) {
                $('#' + districtSelector).empty().append(selectOption.clone());
            } else {
                $.ajax({
                    method: "GET",
                    url: baseUrl + '/registrationform/getDistricts/' + id,
                }).done(function(data) {
                    var elems = data.map(function(d) { return $('<option>', {val: d.flddistrict, text: d.flddistrict, selected: (d.flddistrict == districtId) });});
                    $('#' + districtSelector).empty().append(selectOption.clone()).append(elems);
                });
            }
        }
    </script>
@endpush
