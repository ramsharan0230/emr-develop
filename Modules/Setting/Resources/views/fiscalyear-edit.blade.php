@extends('frontend.layouts.master')
@section('content')
    <div class="container-fluid extra-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">
                                Fiscal Year
                            </h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        @if(Session::get('success_message'))
                            <div class="alert alert-success containerAlert">
                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span
                                        class="sr-only">Close</span></button>
                                {{ Session::get('success_message') }}
                            </div>
                        @endif

                        @if(Session::get('error_message'))
                            <div class="alert alert-danger containerAlert">
                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span
                                        class="sr-only">Close</span></button>
                                {{ Session::get('error_message') }}
                            </div>
                        @endif
                        <form action="{{ route('updatefiscal') }}" method="post">
                            <input type="hidden" name="__fldname" value="{{ $yearEdit->fldname }}">
                            @csrf
                            <div class="row">
                                <div class="col-sm-3">
                                    <label>Label</label>
                                    <input type="text" class="form-control" id="fiscal_label" name="fiscal_label" placeholder="Label" value="{{ $yearEdit->fldname }}" required>
                                </div>
                                <div class="col-sm-4">
                                    <label>Start Date</label>

                                    <input type="text" class="form-control" id="from_date" name="from_date" placeholder="Start Date" autocomplete="off" required>
                                    <input type="hidden" class="form-control" id="eng_from_date" name="eng_from_date" value="{{date('Y-m-d',strtotime($yearEdit->fldfirst))}}">
                                </div>
                                <div class="col-sm-3">
                                    <label>End Date</label>

                                    <input type="text" class="form-control" id="to_date" name="to_date" placeholder="To Date" autocomplete="off" required>
                                    <input type="hidden" class="form-control" id="eng_to_date" name="eng_to_date" value="{{date('Y-m-d',strtotime($yearEdit->fldlast))}}">
                                </div>
                                <div class="col-sm-2 mt-4">
                                    <button class="btn btn-primary"><i class="fa fa-plus"></i> Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <div class="res-table">
                            <table class="table table-bordered table-striped table-hover">
                                <thead class="thead-light">
                                <tr>

                                    <th>Label</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody id="year-list">
                                @if($year)
                                    @foreach($year as $yr)
                                        <tr>
                                            <td>{{$yr->fldname}}</td>
                                            <td>{{$yr->fldfirst}}</td>
                                            <td>{{$yr->fldlast}}</td>
                                            <td><a href="{{ route('fiscal.setting.edit', encrypt($yr->fldname)) }}"><i class="fas fa-edit"></i></a>
                                                |
                                                <a href="{{ route('deletefiscalyear', encrypt($yr->fldname)) }}" onclick="confirm('Delete?')"><i class="fas fa-trash"></i></a>
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
@endsection

@push('after-script')

    <script>
        $(document).ready(function () {
            $(window).ready(function () {
                $('#to_date').val(AD2BS('{{date('Y-m-d',strtotime($yearEdit->fldlast))}}'));
                $('#from_date').val(AD2BS('{{date('Y-m-d',strtotime($yearEdit->fldfirst))}}'));
            })
            $('#from_date').nepaliDatePicker({
                npdMonth: true,
                npdYear: true,
                onChange: function () {
                    $('#eng_from_date').val(BS2AD($('#from_date').val()));
                }
            });
            $('#to_date').nepaliDatePicker({
                npdMonth: true,
                npdYear: true,
                onChange: function () {
                    $('#eng_to_date').val(BS2AD($('#to_date').val()));
                }
            });

            $('#update-fiscal').on('click', function () {


                var url = $(this).attr('url');
                var label = $("#label").val();
                var from = $("#from").val();
                var to = $("#to").val();

                var formData = {
                    label: label,
                    from: from,
                    to: to,
                };


                $.ajax({
                    url: url,
                    type: "POST",
                    dataType: "json",
                    data: formData,
                    success: function (data) {
                        if ($.isEmptyObject(data.error)) {
                            $('#year-list').html(data.success.html);
                            console.log(data.success.html);
                            showAlert("Information saved!!");
                            //location.reload();
                        } else {
                            alert("Something went wrong!!");
                        }
                    }
                });
            });

            $('.delete-bed').on('click', function () {

                var url = $(this).attr('url');
                var fldid = $(this).attr('fldid');


                var formData = {
                    fldid: fldid,


                };
                var result = confirm("Are you sure you want to delete?");
                if (result) {
                    $.ajax({
                        url: url,
                        type: "POST",
                        dataType: "json",
                        data: formData,
                        success: function (data) {
                            if ($.isEmptyObject(data.error)) {
                                $('#year-list').html(data.success.html);

                                showAlert("Information Deleted!!");
                                //location.reload();
                            } else {
                                alert("Something went wrong!!");
                            }
                        }
                    });
                }


            });
        });


    </script>
@endpush
