@extends('frontend.layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">
                                Cashier Payable
                            </h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <form action="javascript:;" id="cashier-payable-form">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group form-row">
                                        <label class="col-sm-3">Billing mode:</label>
                                        <div class="col-sm-9">
                                            <select class="form-control" name="billingmode" id="billingmode">
                                                <option value="">-- Select Item --</option>
                                                @if($billingmode)
                                                    @foreach($billingmode as $mode)
                                                        <option value="{{$mode->fldsetname}}">{{$mode->fldsetname}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group form-row">
                                        <label class="col-sm-3">Group Name:</label>
                                        <div class="col-sm-5">

                                            <input name="group" id="group" type="text" list="groups" class="form-control"/>
                                            <datalist id="groups">
                                                @if(isset($groups) && count($groups) > 0)
                                                    @foreach($groups as $g)
                                                        <option value="{{$g->fldgroup}}">{{$g->fldgroup}}</option>
                                                    @endforeach
                                                @endif
                                            </datalist>
                                        </div>
                                        <div class="col-sm-1">
                                            <button class="btn btn-primary btn-action" onclick="listPacks()"><i class="fa fa-sync" aria-hidden="true"></i></button>
                                        </div>
                                        <div class="col-sm-3">
                                            <button class="btn btn-primary btn-action" onclick="exportAll()"><i class="fa fa-list" aria-hidden="true"></i>&nbsp;View List</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="iq-header-title pl-2">
                                <h6 class="card-title">
                                    Components:&nbsp;
                                    <button onclick="myFunction()" class="btn btn-primary"><i class="fa fa-plus"></i></button>
                                </h6>
                            </div>
                            <div id="myDIV" style="display: none;">
                                <div class="form-group form-row">
                                    <div class="col-sm-2">
                                        <select class="form-control" name="item_type" id="item_type" onchange="listitems()">
                                            <option value="">-- Select Item --</option>
                                            <option value="Test">Test</option>
                                            <option value="Service">Service</option>
                                            <option value="Procedures">Procedures</option>
                                            <option value="Equipment">Equipment</option>
                                            <option value="Radio">Radio</option>
                                            <option value="Others">Others</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <select class="form-control" name="item_name" id="item_name">

                                        </select>
                                    </div>
                                    <div class="col-sm-1 form-row">
                                        <label class="col-sm-3">Rate:</label>
                                        <div class="col-sm-7">
                                            <input type="number" placeholder="0" name="rate" id="rate" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-2 form-row">
                                        <label class="col-sm-4">Qty.:</label>
                                        <div class="col-sm-7">
                                            <input type="number" placeholder="0" name="quantity" id="quantity" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-1 form-row">
                                        <label class="col-sm-4">Disc.:</label>
                                        <div class="col-sm-7">
                                            <input type="number" placeholder="0" name="discount" id="discount" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-2 form-row">
                                        <label class="col-sm-">Total:</label>
                                        <div class="col-sm-7">
                                            <input type="number" placeholder="0" name="total_price" id="total_price" class="form-control" readonly>
                                        </div>
                                    </div>

                                    <div class="col-sm-1 form-row">
                                        <input type="checkbox" id="price_editable" value="1" name="price_editable" >
                                        <label class="ml-1" for="">Edit</label>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class=" float-right" style="margin-left: 80%">
                                        {{-- <div class="col-sm-5">
                                            <div class="form-group form-row">
                                                <div class="col-sm-6"> --}}
                                                    <button class="btn btn-primary btn-action" onclick="addpackage()"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Save</button>&nbsp;
{{--
                                                </div>
                                            </div>
                                        </div> --}}
                                        {{-- <div class="col-sm-5">
                                            <div class="form-group form-row">
                                                <div class="col-sm-6"> --}}
                                                    <button class="btn btn-primary btn-action" onclick="exportGroup()"><i class="fa fa-code" aria-hidden="true"></i>&nbsp;Report</button>

                                                {{-- </div>
                                            </div>
                                        </div> --}}

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <div class="table-responsive table-container">
                            <table class="table table-bordered table-hover table-striped">
                                <thead class="thead-light">
                                <th>&nbsp;</th>
                                <th>Item Type</th>
                                <th>Item Name</th>
                                <th>Price </th>
                                <th>QTY</th>
                                <th>Dis(%)</th>
                                <th>Total Amount </th>
                                <th>&nbsp;</th>
                                </thead>
                                <tbody id="cashier_payable_list"></tbody>
                            </table>
                            <div id="bottom_anchor"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="edit-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="javascript:;" id="edit-modal-form">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title modal-edit-title"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body row">
                        <input type="hidden" class="edit-fldid" name="fldid">
                        <div class="form-group form-row">
                            <label for="" class="col-3">Quantity</label>
                            <div class="col-4">
                                <input type="text" class="edit-quantity form-control" name="qty">
                            </div>
                        </div>

                        <div class="form-group form-row">
                            <label for="" class="col-3">Discount</label>
                            <div class="col-4">
                                <input type="text" class="edit-discount form-control" name="discount">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="saveEdit()">Save changes</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@push('after-script')
    <script type="text/javascript">
        function listitems() {
            if ($("#billingmode").val() === "" || $("#billingmode").val() === undefined || $("#group").val() === "" || $("#group").val() === undefined) {
                showAlert('Select Billing mode and group name.', 'error');
                return false;
            }
            $.ajax({
                url: "{{ route('accountlist.cashier.list.items')}}",
                type: "POST",
                data: {
                    type: $('#item_type').val(),
                    billingmode: $('#billingmode').val(),
                    "_token": "{{ csrf_token() }}"
                },
                success: function (response) {
                    $('#item_name').empty().html(response);
                    $('#item_name').select2();
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }

        function listPacks() {

            $.ajax({
                url: "{{ route('accountlist.cashier.list.packages')}}",
                type: "POST",
                data: {
                    group: $('#group').val(),
                    billingmode: $('#billingmode').val(),
                    "_token": "{{ csrf_token() }}"
                },
                success: function (response) {
                    $('#cashier_payable_list').empty().html(response);

                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }

        function deletepackage(id) {
            if (confirm('Delete Package ?')) {
                $.ajax({
                    url: "{{ route('accountlist.cashier.delete.package') }}",
                    type: "POST",
                    dataType: "json",
                    data: {
                        group: $('#group').val(),
                        billingmode: $('#billingmode').val(),
                        fldid: id,
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        $('#cashier_payable_list').empty().html(response.html);
                        $('#groups').empty().html(response.ghtml);
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });
            }

        }

        function addpackage() {
            var group = $('#group').val();
            var billingmode = $('#billingmode').val();
            var itmetype = $('#item_type').val();
            var itemname = $('#item_name').val();
            var discount = $('#discount').val();
            var editable = ( $('#price_editable').is(':checked') ) ? true : false;

            if ($('#quantity').val().length > 0) {
                var qty = $('#quantity').val();
            } else {
                var qty = 0;
            }


            if (group === '' || itmetype === '' || itemname === '') {
                alert('Field Missing !!');
                return false
            } else {
                $.ajax({
                    url: "{{ route('accountlist.add.cashier.package')}}",
                    type: "POST",
                    dataType: "json",
                    data: {
                        group: group,
                        itemtype: itmetype,
                        itemname: itemname,
                        billingmode: billingmode,
                        qty: qty,
                        discount: discount,
                        editable : editable,
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        $('#cashier_payable_list').empty().html(response.html);
                        $('#groups').empty().html(response.ghtml);

                        //Due to bug list I am again clearing the form (added by anish)
                        $('#item_type').val('');
                        $('#item_name').html('').select2({data: [{id: '', text: ''}]});
                        $('#quantity').val('');
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });
            }
        }

        function exportGroup() {

            data = $('#group').val();
            if ($('#group').val().length > 0) {
                var urlReport = baseUrl + "/accountlist/cashier-packs/exportGroup?group=" + data + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";
                window.open(urlReport, '_blank');
            } else {
                alert('Select Group');
                return false;
            }

        }

        function exportAll() {
            var urlReport = baseUrl + "/accountlist/cashier-packs/exportAll?&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";
            window.open(urlReport, '_blank');
        }

        function editpackage(fldid, itemName) {
            $("#edit-modal").modal('show');
            $(".edit-fldid").empty().val(fldid);
            $(".edit-quantity").empty().val($("#edit-" + fldid).data('qty'));
            $(".edit-discount").empty().val($("#edit-" + fldid).data('discount'));
            $(".modal-edit-title").empty().text(itemName);
        }

        function saveEdit() {
            if ($(".edit-quantity").val() === "" && $(".edit-discount").val() === "") {
                showAlert('Quantity and discount cannot be empty.', 'error');
                return false;
            }
            $.ajax({
                url: "{{ route('accountlist.edit.item')}}",
                type: "POST",
                dataType: "json",
                data: $('#edit-modal-form').serialize(),
                success: function (response) {
                    showAlert('Data update successful.');
                    $('#cashier_payable_list').empty().html(response.html);
                    $("#edit-modal").modal('hide');
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }

        $(document).ready(function(){
            $(document).on('change', '#item_name', function(){
                fldItemtype = $(this).val();
                $.ajax({
                    type : "post",
                    url : "{{ route('ajax.servicecost.cashier.package') }}",
                    data : {
                        '_token' : "{{ csrf_token() }}",
                        'flditemname' : fldItemtype
                    },
                    success : function(response)
                    {
                        $('#rate').val(response.flditemcost);
                    },
                })
            })

            $(document).on('input keyup', '#rate, #quantity, #discount', function(){
                rate = ($('#rate').val()) ? parseFloat( $('#rate').val() ) : 0 ;
                quantity = ($('#quantity').val()) ? parseFloat( $('#quantity').val() ) : 0 ;
                discount = ($('#discount').val()) ? parseFloat( $('#discount').val() ) : 0 ;
                // totalPrice = (parseFloat($('#rate').val()) * parseFloat($('#quantity').val())) -  parseFloat($('#discount').val())
                totalPrice = (rate * quantity) - (discount/100* (rate * quantity) ) ;
                $('#total_price').val(totalPrice);
            })
        })
    </script>
@endpush
