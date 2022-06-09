@extends('frontend.layouts.master')
@section('content')

<div class="container-fluid">
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
    <div class="row">
        <div class="col-sm-12">

            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">Tax Group</h4>
                    </div>
                </div>
                <div class="iq-card-body">

                    <form action="javascript:;" id="tax-group-form" method="post">
                        @csrf

                        <div class="form-horizontal">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group form-row">
                                        <label class="col-sm-3">Tax Group</label>
                                        <div class="col-sm-5">
                                            <div class="input-group">
                                                <input type="text" name="tax_group" id="tax_group" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group form-row">
                                        <label class="col-sm-3">Tax (%)</label>
                                        <div class="col-sm-5">
                                            <input type="number" name="tax" id="tax" class="form-control">
                                        </div>
                                        <div class="col-sm-4">
                                            <button type="button" class="btn btn-primary" onclick="taxGroup.addTaxGroup()">Add</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="table-reponsive table-container">
                        <table class="table table-striped table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>SNo</th>
                                    <th>Tax Group</th>
                                    <th>Tax</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="tax-group-table-list">
                                {!! $tax_list !!}
                            </tbody>
                        </table>
                        <div id="bottom_anchor"></div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('after-script')
<script>
    $(function () {
        $.ajaxSetup({
            headers: {
                "X-CSRF-Token": $('meta[name="_token"]').attr("content")
            }
        });
    });
    var taxGroup = {
        addTaxGroup: function () {
            $.ajax({
                url: '{{ route('store.tax.group') }}',
                type: "POST",
                data: $("#tax-group-form").serialize(),
                success: function (response) {
                        // console.log(response);
                    if(response.error){
                        showAlert(response.error,'error');
                        return false;
                    }
                        if (response.success.status) {
                            $("#tax-group-table-list").empty().append(response.success.html);
                            // showAlert('Saved Successfully');
                             showAlert("{{__('messages.success', ['name' => 'Information'])}}");
                        } else {
                            showAlert("{{ __('messages.error') }}", 'error')
                        }


                    $('#tax-group-form')[0].reset();
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                        showAlert("{{ __('messages.error') }}")
                        $('#tax-group-form')[0].reset();
                    }

                });
        },
        deleteTaxGroup: function (tax_group) {
            if (!confirm("Delete?")) {
                return false;
            }
            $.ajax({
                url: '{{ route('delete.tax.group') }}',
                type: "POST",
                data: {tax_group: tax_group},
                success: function (response) {
                        // console.log(response);
                        if (response.success.status) {
                            $("#tax-group-table-list").empty().append(response.success.html);
                            showAlert("{{__('messages.success', ['name' => 'Information'])}}");
                        } else {
                            showAlert("{{ __('messages.error') }}", 'error')
                        }
                    $('#tax-group-form')[0].reset();
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                        showAlert("{{ __('messages.error') }}")
                        $('#tax-group-form')[0].reset();
                    }

                });
        }
    }
</script>
@endpush
