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
                        <h4 class="card-title">Bank List</h4>
                    </div>
                </div>
                <div class="iq-card-body">

                    <form action="javascript:;" id="bank-form" method="post">
                        @csrf

                        <div class="form-horizontal">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group form-row">
                                        <label class="col-sm-3">Search Bank</label>
                                        <div class="col-sm-6">
                                            <div class="input-group">
                                                <input type="text" name="search_bank" id="search_bank" class="form-control" placeholder="search bank....">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group form-row">
                                        <label class="col-sm-3">Bank</label>
                                         <div class="col-sm-5">
                                            <div class="input-group">
                                                <input type="text" name="bank" id="bank" class="form-control" placeholder="Enter bank">
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <button type="button" class="btn btn-primary" onclick="bankList.addBankList()">Add</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="table-reponsive table-container">
                        <table class="table table-striped table-hover table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>SNo</th>
                                    <th>Bank</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="bank-table-list">
                                {!! $bank_list !!}
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

    // for search in table
    $("#search_bank").on("keyup", function() {
        var value = $(this).val();

        $("table tr").each(function (index) {
            if (!index) return;
            $(this).find("td").each(function () {
                var id = $(this).text().toLowerCase().trim();
                var not_found = (id.indexOf(value) == -1);
                $(this).closest('tr').toggle(!not_found);
                return not_found;
            });
        });
    });



    {{--$('#search_bank').on('keyup', function(){--}}
    {{--    var value = $(this).val();--}}
    {{--    $.ajax({--}}
    {{--        url: '{{ route('search.bank') }}',--}}
    {{--        type: "POST",--}}
    {{--        data: {key:value},--}}
    {{--        success: function (response) {--}}
    {{--                // console.log(response);--}}
    {{--                if (response.success.status) {--}}
    {{--                    $("#bank-table-list").empty().append(response.success.html);--}}
    {{--                         --}}{{--showAlert("{{ __('messages.success', ['name' => 'Information']) }}");--}}
    {{--                    } else {--}}
    {{--                        showAlert("{{ __('messages.error') }}", 'error')--}}
    {{--                    }--}}
    {{--                },--}}
    {{--                error: function (xhr, status, error) {--}}
    {{--                    var errorMessage = xhr.status + ': ' + xhr.statusText;--}}
    {{--                    console.log(xhr);--}}
    {{--                    showAlert("{{ __('messages.error') }}")--}}
    {{--                }--}}
    {{--            });--}}
    {{--});--}}

    var bankList = {
        addBankList: function () {
            $.ajax({
                url: '{{ route('store.bank') }}',
                type: "POST",
                data: $("#bank-form").serialize(),
                success: function (response) {
                        // console.log(response);
                        if (response.success.status) {
                            $("#bank-table-list").empty().append(response.success.html);
                            showAlert("{{ __('messages.success', ['name' => 'Information']) }}");
                        } else {
                            showAlert("{{ __('messages.error') }}", 'error')
                        }
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                        showAlert("{{ __('messages.error') }}")
                    }
                });
        },
        deleteBank: function (fldid) {
            if (!confirm("Delete?")) {
                return false;
            }
            $.ajax({
                url: '{{ route('delete.bank') }}',
                type: "POST",
                data: {fldid: fldid},
                success: function (response) {
                        // console.log(response);
                        if (response.success.status) {
                            $("#bank-table-list").empty().append(response.success.html);
                            showAlert("{{ __('messages.success', ['name' => 'Information']) }}");
                        } else {
                            showAlert("{{ __('messages.error') }}", 'error')
                        }
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                        showAlert("{{ __('messages.error') }}")
                    }
                });
        }
    }
</script>
@endpush
