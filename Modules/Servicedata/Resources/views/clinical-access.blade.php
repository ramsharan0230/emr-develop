@extends('frontend.layouts.master')
@section('content')
<div class="container-fluid extra-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">Clinical Access</h4>
                    </div>
                </div>
                <form action="javascript:;" id="clincal-access-form">
                    @csrf
                    <div class="iq-card-body">

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for=""><strong>User Name</strong></label>
                                    <div class="append-icon" style="display: flex;">
                                        <select name="user_name" class="user_name form-control col-11" required>
                                            <option value="">Choose</option>
                                            @if(count($usersClinicalAccess))
                                            @foreach($usersClinicalAccess as $userCA)
                                            @if(isset($userCA->userName->fldusername))
                                            <option value="{{ $userCA->flduserid }}">{{ $userCA->userName->fldusername??'' }}</option>
                                            @endif
                                            @endforeach
                                            @endif
                                        </select>&nbsp;&nbsp;
                                        <a href="javascript:;" onclick="clinicalAccess.userDisable()" class="btn btn-primary"><i class="fa fa-sync"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                    <!-- <div class="form-group">
                                        <label for="">Target</label>
                                        <select name="target_comp" id="" class="form-control" required>
                                            <option value=""></option>
                                            @if(count($targetscomp))
                                                @foreach($targetscomp as $comp)
                                                    @if(isset($comp->name))
                                                        <option value="{{ $comp->name }}">{{ $comp->name??'' }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </select>
                                    </div> -->
                                </div>
                                <div class="col-12">
                                    <h5 class="card-title"><strong>Disable Components:</strong></h5>
                                </div>
                                <div class="col-md-4">
                                    <div class="res-table">
                                        <table class="table table-hover table-bordered table-striped">
                                            <tbody>
                                                {{--<tr>
                                                    <td>
                                                        <div class="custom-control custom-checkbox custom-control-inline">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck5">
                                                            <label class="custom-control-label" for="customCheck5"> Anesthesia Note</label>
                                                        </div>
                                                    </td>
                                                </tr>--}}
                                                @if(count($disable_component))
                                                @php
                                                $countForId = 1;
                                                @endphp
                                                @foreach($disable_component as $dc)
                                                <tr>
                                                    <td>
                                                        <div class="custom-control custom-checkbox custom-control-inline">
                                                            <input type="checkbox" class="custom-control-input" name="disable_components[]" id="" value="{{ $dc }}">
                                                            <label class="custom-control-label" for=""> {{ $dc }}</label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @php
                                                $countForId++;
                                                @endphp
                                                @endforeach
                                                @endif
                                            </tbody>
                                        </table>

                                    </div>
                                    <button class="btn btn-primary mt-2" onclick="clinicalAccess.userAddComponents()"><i class="fa fa-plus"></i>&nbsp;Add</button>
                                </div>
                                <div class="col-md-8">
                                    <div class="table table-responsive">
                                        <table class="table table-hovered table-bordered table-striped">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>&nbsp;</th>
                                                    <th>User</th>
                                                    <th>Component</th>
                                                    <th>Target</th>
                                                    <th>&nbsp;</th>
                                                </tr>
                                            </thead>
                                            <tbody class="list-clinical-access-user-disabled">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="triage-modal">
        <div class="modal-dialog modal-lg" id="size">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="triage-modal-title">CogentEMR</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="triage-form-container">
                        <div class="triage-form-data"></div>
                    </div>

                </div>
                <i class="glyphicon glyphicon-chevron-left"></i>

            </div>
        </div>
    </div>
    @endsection
    @push('after-script')
    <script>


        var clinicalAccess = {
            userDisable: function () {
                if ($('.user_name').val() === "") {
                    alert('Select User!');
                    return false;
                }

                $.ajax({
                    url: "{{ route('consultant.clinical.access.user.disabled.components') }}",
                    type: "post",
                    data: {userName: $('.user_name').val(), "_token": "{{ csrf_token() }}"},
                    success: function (data) {
                        // console.log(data);
                        $('.list-clinical-access-user-disabled').empty();
                        $('.list-clinical-access-user-disabled').append(data);
                    },
                    error: function (xhr, err) {
                        console.log(xhr);
                    }
                });
            },
            userAddComponents: function () {
                // alert($('.user_name').val())
                if ($('.user_name').val() === "") {
                    alert('Select User!');
                    return false;
                }

                $.ajax({
                    url: "{{ route('consultant.clinical.access.user.add.components') }}",
                    type: "post",
                    data: $('#clincal-access-form').serialize(),
                    success: function (data) {
                        console.log(data);
                        $('.list-clinical-access-user-disabled').empty();
                        $('.list-clinical-access-user-disabled').append(data);
                    },
                    error: function (xhr, err) {
                        console.log(xhr);
                    }
                });
            },
            deleteComponent: function (fldid) {
                var r = confirm("Delete?");
                if (r !== true) {
                    return false;
                }
                $.ajax({
                    url: "{{ route('consultant.clinical.access.user.delete.components') }}",
                    type: "post",
                    data: {userName: $('.user_name').val(), fldid: fldid, "_token": "{{ csrf_token() }}"},
                    success: function (data) {
                        // console.log(data);
                        $('.list-clinical-access-user-disabled').empty();
                        $('.list-clinical-access-user-disabled').append(data);
                    },
                    error: function (xhr, err) {
                        console.log(xhr);
                    }
                });
            }
        }
    </script>
    @endpush
