@extends('frontend.layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">
                                Billing mode
                            </h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <div class="form-group form-row">
                            <label class="col-sm-1">
                                Name
                            </label>
                            <div class="col-sm-8">
                                <input type="text" name="billingmode" id="billingmode" class="form-control"/>
                            </div>
                            <div class="col-sm-3">
                                <a href="javascript:;" id="add-billing-mode" class="btn btn-primary" url="{{route('add-billingmode')}}"><i class="fas fa-plus"></i> Add</a>
                            </div>
                        </div>
                        <p class="text-left"><b style="color: red">Note: Please use "General" mode for e-appointment</b></p>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <div class="form-group mt-3">
                            <div class="res-table">
                                <table class="table table-hovered table-bordered table-striped">
                                    <thead class="thead-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody id="listing-billing-mode">
                                    @if($billingmode)
                                        @foreach($billingmode as $b)
                                            <tr>
                                                <td>{{$b->fldsetname}}</td>
                                                <td>{{$b->status===1 ? 'Active' : 'Inactive'}}</td>
                                                <td>
                                                    @if($b->status === 1)
                                                        <a href="javascript:;" class="change-status-billing-mode btn btn-warning" onclick="changeBillingModeStatus('{{$b->fldsetname}}', {{$b->status}})">Inactive</a>
                                                    @else
                                                        <a href="javascript:;" class="change-status-billing-mode-{{ $b->fldsetname }} btn btn-success" onclick="changeBillingModeStatus('{{$b->fldsetname}}')">Active</a>

                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('after-script')

    <script>
        $("#add-billing-mode").click(function () {

            var url = $(this).attr('url');
            var mode = $("#billingmode").val();


            var formData = {
                mode: mode,

            };


            $.ajax({
                url: url,
                type: "POST",
                dataType: "json",
                data: formData,
                success: function (data) {
                    if ($.isEmptyObject(data.error)) {
                        $('#listing-billing-mode').append(data.success.html);
                        $('#billingmode').val('');
                        showAlert("Information saved!!");
                        //location.reload();
                    } else {
                        alert("Something went wrong!!");
                    }
                }
            });
        });

        /*$(document).on('click','.delete-billing-mode',function() {

            var url = $(this).attr('url');
            var $this = $(this);
            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",

                success: function(data) {
                    if ($.isEmptyObject(data.error)) {
                        showAlert("Information Deleted!!");
                        $this.parents("tr").remove();

                    } else {
                        showAlert("Something went wrong!!");
                    }
                }
            });
        });*/

        function changeBillingModeStatus(id) {
            $.ajax({
                url: "{{ route('change.billingmode.status') }}",
                type: "post",
                data: {id: id},
                success: function (data) {
                    if (data.success) {
                        location.reload(true);
                    } else {
                        showAlert("Something went wrong!!", 'error');
                    }
                }
            });
        }
    </script>
@endpush
