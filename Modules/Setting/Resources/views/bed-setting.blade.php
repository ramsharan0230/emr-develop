@extends('frontend.layouts.master')
@push('after-styles')

@endpush

@section('content')

    <div class="container-fluid extra-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Bed Setting</h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <ul class="nav nav-tabs justify-content-center" id="myTab-2" role="tablist">

                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#bedtype" role="tab" aria-controls="profile" aria-selected="false">Bed Type</a>
                            </li>
                            {{-- <li class="nav-item">
                                <a class="nav-link " data-toggle="tab" href="#bedgroup" role="tab" aria-controls="home" aria-selected="true">Bed Group</a>
                            </li> --}}
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#bedfloor" role="tab" aria-controls="contact" aria-selected="false">Bed Floor</a>
                            </li>

                        </ul>
                        <div class="tab-content" id="myTabContent-3">

                            <div class="tab-pane fade show active" id="bedtype" role="tabpanel" aria-labelledby="dicom">
                                {{--<div class="iq-card-header d-flex justify-content-between">
                                    <div class="iq-header-title">
                                        <h4 class="card-title">Bed Type</h4>
                                    </div>
                                </div>--}}
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                                            <div class="iq-card-body">
                                                <form method="POST" id="form-bed-type" action="javascript:;">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="form-group form-row align-items-center">
                                                                <label for="" class="col-sm-2">Name:</label>
                                                                <div class="col-sm-8">
                                                                    <input type="text" name="bedType" id="bedType" placeholder="Bed Type" class="form-control">
                                                                </div>
                                                                <div class="col-sm-2">
                                                                    <button class="btn btn-primary" type="button" onclick="bed.addBedType()">Add</button>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </form>
                                                <div class="res-table">
                                                    <table class="table table-bordered table-striped table-hover">
                                                        <thead class="thead-light">
                                                        <tr>
                                                            <th>SNo</th>
                                                            <th>Bed Type</th>
                                                            <th>Action</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="bed-type-table">
                                                        {!! $bed_type !!}
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- <div class="tab-pane fade" id="bedgroup" role="tabpanel" aria-labelledby="miscellaneous">

                                <div class="col-sm-12">
                                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                                        <div class="iq-card-body">
                                            <form method="POST" id="form-bed-group">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group form-row align-items-center">
                                                            <label for="" class="col-sm-2">Name:</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" name="bedgroup" id="bedgroup" placeholder="Bed Group" class="form-control">
                                                            </div>
                                                            <div class="col-sm-2">
                                                                <button class="btn btn-primary" type="button" onclick="bed.addBedGroup()">Add</button>
                                                            </div>

                                                        </div>

                                                    </div>
                                                </div>

                                            </form>
                                            <div class="res-table">
                                                <table class="table table-bordered table-striped table-hover">
                                                    <thead class="thead-light">
                                                    <tr>
                                                        <th>SNo</th>
                                                        <th>Bed Type</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="bed-group-table">
                                                    {!! $bed_group !!}
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="tab-pane fade" id="bedfloor" role="tabpanel" aria-labelledby="sms-function">

                                <div class="col-sm-12">
                                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                                        <div class="iq-card-body">
                                            <form method="POST" id="form-bed-floor">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group form-row align-items-center">
                                                            <label for="" class="col-sm-2">Name:</label>
                                                            <div class="col-sm-3">
                                                                <input type="text" name="bedfloor" id="bedfloor" placeholder="Bed Floor" class="form-control">
                                                            </div>
                                                            <label for="" class="col-sm-2">Order By:</label>
                                                            <div class="col-sm-3">
                                                                <input type="number" name="bedfloororder" id="bedfloororder" placeholder="Bed Floor Order" class="form-control">
                                                            </div>
                                                            <div class="col-sm-2">
                                                                <button class="btn btn-primary" type="button" onclick="bed.addBedFloor()">Add</button>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </form>
                                            <div class="res-table">
                                                <table class="table table-bordered table-striped table-hover">
                                                    <thead class="thead-light">
                                                    <tr>
                                                        <th>SNo</th>
                                                        <th>Bed Floor</th>
                                                        <th>Order By / Position</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="bed-floor-table">
                                                    {!! $bed_floor !!}
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
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
    <script>
        var bed = {
            addBedType: function () {
                $.ajax({
                    url: '{{ route('setting.bedtype.store') }}',
                    type: "POST",
                    data: $("#form-bed-type").serialize(),
                    success: function (response) {
                        // console.log(response);
                        if (response.success.status) {
                            $("#bed-type-table").empty().append(response.success.html);
                            showAlert('Successfully data inserted.')
                        } else {
                            showAlert("{{ __('messages.error') }}", 'error')
                        }
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                        showAlert("{{ __('messages.error') }}", 'error')
                    }
                });
            },
            deleteBedType:function (id) {
                if(!confirm("Delete?")){
                    return false;
                }
                $.ajax({
                    url: '{{ route('setting.bedtype.delete') }}',
                    type: "POST",
                    data: {id:id},
                    success: function (response) {
                        // console.log(response);
                        if (response.success.status) {
                            $("#bed-type-table").empty().append(response.success.html);
                            showAlert('Successfully data deleted.')
                        } else {
                            showAlert("{{ __('messages.error') }}", 'error')
                        }
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                        showAlert("{{ __('messages.error') }}", 'error')
                    }
                });
            },
            addBedGroup: function () {
                $.ajax({
                    url: '{{ route('setting.bedgroup.store') }}',
                    type: "POST",
                    data: $("#form-bed-group").serialize(),
                    success: function (response) {
                        // console.log(response);
                        if (response.success.status) {
                            $("#bed-group-table").empty().append(response.success.html);
                            showAlert('Successfully data inserted.')
                        } else {
                            showAlert("{{ __('messages.error') }}", 'error')
                        }
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                        showAlert("{{ __('messages.error') }}", 'error')
                    }
                });
            },
            deleteBedGroup:function (id) {
                if(!confirm("Delete?")){
                    return false;
                }
                $.ajax({
                    url: '{{ route('setting.bedgroup.delete') }}',
                    type: "POST",
                    data: {id:id},
                    success: function (response) {
                        // console.log(response);
                        if (response.success.status) {
                            $("#bed-group-table").empty().append(response.success.html);
                            showAlert('Successfully data deleted.')
                        } else {
                            showAlert("{{ __('messages.error') }}", 'error')
                        }
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                        showAlert("{{ __('messages.error') }}", 'error')
                    }
                });
            },
            addBedFloor: function () {
                $.ajax({
                    url: '{{ route('setting.bedfloor.store') }}',
                    type: "POST",
                    data: $("#form-bed-floor").serialize(),
                    success: function (response) {
                        // console.log(response);
                        if (response.success.status) {
                            $("#bed-floor-table").empty().append(response.success.html);
                            showAlert('Successfully data inserted.')
                        } else {
                            showAlert("{{ __('messages.error') }}", 'error')
                        }
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                        showAlert("{{ __('messages.error') }}", 'error')
                    }
                });
            },
            deleteBedFloor:function (id) {
                if(!confirm("Delete?")){
                    return false;
                }
                $.ajax({
                    url: '{{ route('setting.bedfloor.delete') }}',
                    type: "POST",
                    data: {id:id},
                    success: function (response) {
                        // console.log(response);
                        if (response.success.status) {
                            $("#bed-floor-table").empty().append(response.success.html);
                            showAlert('Successfully data deleted.')
                        } else {
                            showAlert("{{ __('messages.error') }}", 'error')
                        }
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                        showAlert("{{ __('messages.error') }}", 'error')
                    }
                });
            }
        }
    </script>
@endpush
