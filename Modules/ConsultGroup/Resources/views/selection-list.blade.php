@extends('frontend.layouts.master')
@section('content')

<div class="container-fluid">
    <div class="row">
     <div class="col-lg-12 col-md-12 leftdiv">
        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-header d-flex justify-content-between">
                <div class="iq-header-title">
                   <h4 class="card-title">Clinical Data Master / Selection Lists</h4>
               </div>
               <button onclick="myFunction()" class="btn btn-primary"><i class="fa fa-bars"></i></button>
           </div>
       </div>
   </div>
   <div class="col-sm-12" id="myDIV">
    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-body">
            <form  onsubmit="return false" id="selection-list-form">
                @csrf
                <div class="row">
                    <div class="col-lg-8 col-md-12">
                        <div class="form-group form-row align-items-center">
                            <label class="col-sm-2">Category</label>
                            <div class="col-sm-5">
                                <select name="test_name" id="test_name" class="form-control" onchange="selectionList.listItems()">
                                    <option value="">---select---</option>
                                    <option value="Test">Test</option>
                                    <option value="Radio">Radio</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group form-row align-items-center">
                            <label class="col-sm-2">Group Name</label>
                            <div class="col-sm-5">
                                <select name="group_name" id="" class="form-control group_name">
                                    <option value="">---select---</option>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" name="group_name_free " id="group_name_free " class="group_name_free form-control">
                            </div>
                            <div class="col-sm-1">
                                <a href="javascript:;" class="btn btn-primary" onclick="selectionList.listTableItems()"><i class="ri-refresh-line"></i></a>
                            </div>
                        </div>
                        <div class="form-group form-row align-items-center">
                            <label class="col-sm-2">Item Name</label>
                            <div class="col-sm-5">
                                <select name="item_name" id="" class="item_name form-control">
                                    <option value="">---select---</option>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <button class="btn btn-primary" onclick="selectionList.addItems()"><i class="ri-add-line"></i> Add</button>&nbsp;

                                <button class="btn btn-warning" onclick="selectionList.exportSelectionGroup()"><i class="ri-code-s-slash-fill"></i> Export</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="col-lg-12 col-md-12">
    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-body">
           <div class="table-responsive table-container">
            <table class="table table-bordered table-striped table-hover ">
                <thead class="thead-light">
                    <tr>
                        <th>&nbsp;</th>
                        <th>Item Type</th>
                        <th>Item Name</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody class="list-selection-list">

                </tbody>
            </table>
            <div id="bottom_anchor"></div>
        </div>
    </div>
</div>
</div>
</div>
</div>

@stop

@push('after-script')
<script>
    var selectionList = {
        listItems: function () {
            if ($('.test_name').val() === "") {
                alert('Select Category!')
            }

            $.ajax({
                url: "{{ route('consultant.selection.group.populate.list') }}",
                type: "post",
                data: $('#selection-list-form').serialize(),
                success: function (data) {
                        // console.log(data);
                        $('.group_name').empty();
                        $('.group_name').append(data.costgroupSelect);
                        $('.item_name').empty();
                        $('.item_name').append(data.serviceCostSelect);
                    },
                    error: function (xhr, err) {
                        console.log(xhr);
                    }
                });
        },
        addItems: function () {
            if ($('.test_name').val() === "" && $('.group_name').val() === "" && $('.item_name').val() === "") {
                alert('Select Category and group!')
            }

            $.ajax({
                url: "{{ route('consultant.selection.group.add') }}",
                type: "post",
                data: $('#selection-list-form').serialize(),
                success: function (data) {
                    console.log(data);
                    $('.list-selection-list').empty();
                    $('.list-selection-list').append(data);
                },
                error: function (xhr, err) {
                    console.log(xhr);
                }
            });
        },
        listTableItems: function () {
            if ($('.test_name').val() === "" && $('.group_name').val() === "") {
                alert('Select Category and group!')
            }

            $.ajax({
                url: "{{ route('consultant.selection.group.display.list') }}",
                type: "post",
                data: $('#selection-list-form').serialize(),
                success: function (data) {
                        // console.log(data);
                        $('.list-selection-list').empty();
                        $('.list-selection-list').append(data);
                    },
                    error: function (xhr, err) {
                        console.log(xhr);
                    }
                });
        },
        deleteSelectionItem: function (fldid) {
            var r = confirm("Delete?");
            if (r !== true) {
                return false;
            }
            $.ajax({
                url: "{{ route('consultant.selection.group.delete') }}",
                type: "post",
                data: {group_name: $('.group_name').val(), fldid: fldid, "_token": "{{ csrf_token() }}"},
                success: function (data) {
                        // console.log(data);
                        $('.list-selection-list').empty();
                        $('.list-selection-list').append(data);
                    },
                    error: function (xhr, err) {
                        console.log(xhr);
                    }
                });
        },
        exportSelectionGroup:function () {
            var urlReport = "{{ route('display.consultation.group.selection.export') }}" + "?group_name=" + $('.group_name').val() + "&_token=" + "{{ csrf_token() }}";
            window.open(urlReport, '_blank');
        }
    }

    $(document).ready(function () {
        $('.group_name').change(function () {

            if ($('.group_name').val() !== "") {
                $('#group_name_free').val('');
                $('#group_name_free').prop("disabled", true);
            } else {
                $('#group_name_free').prop("disabled", false);
            }
        })
    })
</script>
@endpush
