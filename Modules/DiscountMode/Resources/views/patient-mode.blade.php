@extends('frontend.layouts.master')
<style>
       .search-input {
            float: left !important;
        }
</style>
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">
                                Discount Mode
                            </h4>
                        </div>
                        <button onclick="myFunction()" class="btn btn-primary"><i class="fa fa-bars"></i></button>
                    </div>
                </div>
            </div>
            <div class="col-sm-12" id="myDIV">
                {{-- @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{{ $message }}</strong>
                    </div>
                @endif --}}

                @if ($message = Session::get('error'))
                    <div class="alert alert-danger alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{{ $message }}</strong>
                    </div>
                @endif
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <form action="{{ route('patient.discount.mode.insert') }}">
                            <input type="hidden" name="__fldtype" id="__fldtype">
                            <div class="row">
                                <div class="col-lg-3 col-md-6">
                                    <div class="form-group form-row">
                                        <label for="" class="col-md-6 control-label">Billing Mode</label>
                                        <div class="col-md-6">
                                            <select name="fldbillingmode" class="form-control" id="billing_mode" required>
                                                <option value="%">%</option>
                                                @if(isset($billingset))
                                                    @foreach($billingset as $b)
                                                        <option value="{{$b->fldsetname}}">{{$b->fldsetname}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group form-row">
                                        <label for="" class="col-md-6 control-label">Discount label</label>
                                        <div class="col-md-6">
                                            <input type="text" name="fldtype" class="form-control fldtype" value="{{ old('fldtype')}}" required/>
                                        </div>
                                    </div>
                                    <div class="form-group form-row">
                                        <label for="" class="col-md-6 control-label">Discount mode</label>
                                        <div class="col-md-6">
                                            <select name="fldmode" id="fldmode-check" class="form-control" value="{{ old('fldmode')}}" required>
                                                <option value="">--Select--</option>
                                                <option value="FixedPercent">Fixed Percent</option>
                                                <option value="CustomValues">Custom Values</option>
                                                <option value="Flexible">Flexible</option>
                                                <option value="FlexibleWithLimit">Flexible With Limit</option>
                                                <option value="None">None</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- flexible starts -->
                                    <div class="form-group form-row flexible-with-limit">
                                        <label for="flddiscountlimit" class="col-md-6 control-label">Discount Limit</label>
                                        <div class="col-md-6">
                                            <input type="number" id="flddiscountlimit" class="form-control" name="flddiscountlimit" 
                                                placeholder="With in range (0-100)" value="{{ old('flddiscountlimit')}}">
                                        </div>
                                    </div>
                                    <!-- flexible ends -->
                                    {{--</div>
                                        <div class="col-md-3">--}}
                                    <div class="form-group form-row">
                                        <label for="" class="col-md-6 control-label">Disc Atm/Year</label>
                                        <div class="col-md-6">
                                            <input id="fldamount" type="number" name="fldamount" placeholder="0" class="form-control" value="{{ old('fldamount')}}"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="form-group form-row">
                                        <label for="" class="col-md-6 control-label">Fix Disc %</label>
                                        <div class="col-md-6">
                                            <input type="number" name="fldpercent" id="fldpercent" placeholder="0" class="form-control" value="{{ old('fldpercent')}}"/>
                                        </div>
                                    </div>
                                    <div class="form-group form-row">
                                        <label for="" class="col-md-6 control-label">Credit AMT</label>
                                        <div class="col-md-6">
                                            <input type="number" id="fldcredit" name="fldcredit" placeholder="0" class="form-control"  value="{{ old('fldcredit')}}"/>
                                        </div>
                                    </div>
                                    <div class="form-group form-row">
                                        <label for="" class="col-md-6 control-label">Department</label>
                                        <div class="col-md-6">
                                            <select name="request_department_pharmacy" class="form-control" id="request_department_pharmacy"  value="{{ old('request_department_pharmacy')}}">
                                                <option value="">--Select--</option>
                                                @if(Session::has('user_hospital_departments'))
                                                @foreach (Session::get('user_hospital_departments') as $hosp_dept)
                                                        <option @if(Session::get('selected_user_hospital_department')->id == $hosp_dept->id) selected @endif value="{{ $hosp_dept->id }}">{{ $hosp_dept->name }}</option>
                                                @endforeach
                                                @endif

                                            </select>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-lg-6 ">
                                    <div class="form-group form-row">
                                        <label for="" class="col-md-3 control-label">Year Start</label>
                                        <div class="col-md-8 padding-none">
                                            <input type="text" name="fldyear" id="year-start" class="form-control nepaliDatePicker" value="{{ old('fldyear') }}" autocomplete="off" required/>
                                        </div>
                                    </div>
                                    <div class="form-group form-row">
                                    {{--                                        <label for="" class="col-md-3 control-label">Discount Label</label>--}}
                                    <!--                                        <div class="col-md-4">
                                            <select id="discountLabelSelect" name="fldtype" class="form-control">
                                                <option value="">&#45;&#45;Select&#45;&#45;</option>
                                                @if($discountData)
                                        @forelse($discountData as $dis)
                                            <option value="{{ $dis->fldtype }}">{{ $dis->fldtype }}</option>
                                                    @empty

                                        @endforelse
                                    @endif
                                        </select>
                                    </div>-->
                                        <div class="col-md-4">
                                            <button type="button" class="btn btn-primary full-width" data-toggle="modal" data-target=".bd-curr-modal-lg" id="list-saved-discounts">View List</button>
                                        </div>
                                    </div>
                                    <div class="form-group form-row">
                                        <label for="" class="col-md-3 control-label">No Discount</label>
                                        <div class="col-md-4">
                                            <button type="button" class="btn btn-primary full-width" data-toggle="modal" data-target=".bd-no-discount-modal-lg">View No Discount List</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 mt-3">
                                    <div class="form-group text-right">
                                        <p class="text-left"><b style="color: red">Note: Please use "General" discount mode for e-appointment</b></p>
                                        <button class="btn btn-primary" type="submit"><i class="fas fa-plus"></i> Add</button>
                                        {{--                                        <button class="btn btn-info"><i class="fa fa-edit"></i> Update</button>--}}
                                        {{-- <button class="btn btn-warning" type="button">
                                            <i class="fa fa-code"></i>
                                        </button> --}}
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
                        <div class="d-flex flex-row justify-content-end">
                            {{-- <input type="text" class="form-control" id="" placeholder="Search" style="width:35%;"> --}}
                            <div class="d-flex flex-row">
                                @if(\App\Utils\Permission::checkPermissionFrontendAdmin(str_replace(' ','-',strtolower('export-billing-pdf-button-billing'))))
                                   <a href="javascript:void(0);" type="button" class="btn btn-primary" onclick="discountModePatient.exportDiscountReportToPdf()"><i class="fa fa-file-pdf"></i>&nbsp;
                                       PDF</a>&nbsp;
                               @endif
                               @if(\App\Utils\Permission::checkPermissionFrontendAdmin(str_replace(' ','-',strtolower('export-billing-excel-button-billing'))))
                                   <a href="javascript:void(0);" type="button" class="btn btn-primary" onclick="discountModePatient.exportDiscountReportToExcel()"><i class="fa fa-file-excel"></i>&nbsp;
                                       Excel</a>
                               @endif
                           </div>
                        </div>
                        <div class="tab-content" id="myTabContent-1">
                                <div class="tab-pane fade show active" id="grid" role="tabpanel" aria-labelledby="home-tab-grid">
                                    <div class="">

                                        <table
                                            class="table expandable-table table-responsive custom-table table-bordered table-striped  mt-c-15 table-sticky-th"
                                            id="myTable1" data-show-columns="true" data-search="true" data-show-toggle="true"
                                            data-pagination="false"
                                            data-resizable="true">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th></th>
                                                    <th>DiscLabel</th>
                                                    <th>DiscMode</th>
                                                    <th>BillingMode</th>
                                                    <th>StartDate</th>
                                                    <th>DiscATM/Year</th>
                                                    <th>CreditAmt</th>
                                                    {{-- <th></th> --}}
                                                    <th>Created By</th>
                                                    <th>Updated By</th>

                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="discountMode">
                                                @if($discountData)
                                                    @forelse($discountData as $dis)
                                                        {{-- <tr class="tr-discount-mode" data-billMode="{{ $dis->fldbillingmode }}" data-billType="{{ $dis->fldtype }}" data-fixDis="{{ $dis->fldpercent }}" data-billDiscount="{{ $dis->fldmode }}" data-yearAmount="{{ $dis->fldamount }}" data-date="{{ date('m/d/Y', strtotime($dis->fldyear)) }}"> --}}
                                                            <tr class="tr-discount-mode" data-fldcredit="{{ $dis->fldcredit }}" data-fldamount="{{ $dis->fldamount }}"  data-billMode="{{ $dis->fldbillingmode }}" data-billType="{{ $dis->fldtype }}" data-fixDis="{{ $dis->fldpercent }}" data-billDiscount="{{ $dis->fldmode }}" data-yearAmount="{{ $dis->fldamount }}" data-date="{{ date('Y-m-d', strtotime($dis->fldyear)) }}">

                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $dis->fldtype }}</td>
                                                            <td>{{ $dis->fldmode }}</td>
                                                            <td>{{ $dis->fldbillingmode }}</td>
                                                            <td>{{ date('Y-m-d', strtotime($dis->fldyear)) }}</td>
                                                            <td>{{ $dis->fldamount }}</td>
                                                            <td>{{ $dis->fldcredit }}</td>

                                                            <td>{{ $dis->flduserid }}</td>
                                                            {{-- <td>{{ !is_null($dis->cogentUser) ? $dis->cogentUser->firstname : null }}</td> --}}
                                                            {{-- @dd($dis->cogentUser); --}}
                                                            <td>{{ !is_null($dis->cogentUser) ? $dis->cogentUser->firstname : null }}</td>
                                                            <td>
                                                                <a href="javascript:;" title="Edit" class="btn btn-link float-left" onclick="discountModePatient.editDiscountMode('{{ $dis->fldtype }}')"><i class="fa fa-edit"></i></a>
                                                                <form action="{{ route('patient.discount.mode.delete.mode') }}" class="float-left" method="post" onsubmit="return confirm('Delete?')">
                                                                    @csrf
                                                                    <input type="hidden" name="fldtype" value="{{ $dis->fldtype }}">
                                                                    <button type="submit" class="btn btn-link text-danger"><i class="fa fa-trash"></i></button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td>No Data</td>
                                                        </tr>
                                                    @endforelse
                                                @endif
                                            </tbody>

                                        </table>
                                    </div>

                                <div class="tab-pane fade" id="chart" role="tabpanel" aria-labelledby="chart-tab-two">
                                    <div id="qty-chart"></div>
                                </div>
                                <div class="tab-pane fade" id="amt-two" role="tabpanel" aria-labelledby="amt-tab-two">
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @include("discountmode::modal.update-discount")

    @include("discountmode::modal.no-discount")
    @include("discountmode::modal.current-discount")
@endsection

@push('after-script')
    <script>
          $(function() {
            // $('#myTable1').bootstrapTable()
        })
    </script>
    <script>
        discountModePatient = {
            listByDiscountGroup: function () {
                // console.log($('.fldtype').val())
                discountGroupName = $('#discountGroup').val();
                discountLabelVal =  $('.fldtype').val();
                $.ajax({
                    url: '{{ route('patient.discount.mode.list.items.by.group') }}',
                    type: "POST",
                    data: {discountGroupName: discountGroupName, discountLabel :discountLabelVal,   '_token': '{{ csrf_token() }}'},
                    success: function (response) {
                        // console.log(response);
                        // $('#custom-discount-list').empty();
                        // $('#custom-discount-list').append(response);
                        $('#before-add-list').html(response.itemlist);
                        $('#after-add-list').html(response.nodiscountlist);

                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });
            },
            addNoDiscount: function () {
                discountGroupName = $('#discountGroup').val();
                $.ajax({
                    url: '{{ route('patient.discount.mode.add.items.by.group') }}',
                    type: "POST",
                    data: $("#form-add-new-no-discount").serialize(),
                    success: function (response) {
                        // console.log(response);
                        $('#after-add-list').empty();
                        $('#after-add-list').append(response);

                        $('input[name="no_discount[]"]:checked').each(function() {
                            // console.log($(this));
                            $(this).closest("tr").remove();
                        })
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });
            },
            removeNoDiscount: function () {
                // confirmTest = confirm("Delete?");
                // if (confirmTest === false) {
                //     return false;
                // }
                discountGroupName = $('#discountGroup').val();
                $.ajax({
                    url: '{{ route('patient.discount.mode.remove.items.by.group') }}',
                    type: "POST",
                    data: $("#form-add-new-no-discount").serialize(),
                    success: function (response) {
                        // console.log(response);

                        $('input[name="no_discount_remove[]"]:checked').each(function() {
                            // console.log($(this));
                            switchtr = $(this).closest("tr").html();
                            console.log('current check tr', switchtr);
                            $('#before-add-list').prepend("<tr>"+ switchtr +"</tr>");
                            // $('#before-add-list').append('gdgdfdgd');
                        })
                        // $('#after-add-list').empty();
                        $('input[name="no_discount_remove[]"]:checked').each(function() {
                            // console.log($(this));
                            $(this).closest("tr").remove();
                        })
                        // $('#after-add-list').append(response);


                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });
            },
            deleteNoDiscount: function (itemToDelete) {
                confirmTest = confirm("Delete?");
                if (confirmTest === false) {
                    return false;
                }
                $.ajax({
                    url: '{{ route('patient.discount.mode.delete.items') }}',
                    type: "POST",
                    data: {itemToDelete: itemToDelete, '_token': '{{ csrf_token() }}'},
                    success: function (response) {
                        // console.log(response);
                        $('#after-add-list').empty();
                        $('#after-add-list').append(response);
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });
            },
            editDiscountMode: function (fldtype) {
                // console.log('hit');
                $.ajax({
                    url: '{{ route('patient.discount.mode.edit') }}',
                    type: "GET",
                    data: {fldtype: fldtype, '_token': '{{ csrf_token() }}'},
                    success: function (response) {
                        console.log(response);
                        $('#discount-modal-title').empty().text('Update Discount Mode');
                        $('#discount-form-data').empty().append(response);
                        $('#discount-modal').modal({show: true});
                        $('#discount-modal').find('#nepaliDatePicker').nepaliDatePicker({
                            npdMonth: true,
                            npdYear: true,
                            npdYearCount: 10
                        });
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });
            },
            customDiscountListByType: function () {
                type = $('#discount-category').val();
                $.ajax({
                    url: '{{ route('patient.discount.mode.custom.type.list') }}',
                    type: "POST",
                    data: {type: type, '_token': '{{ csrf_token() }}'},
                    success: function (response) {
                        // console.log(response);
                        $('#discount-itemName').empty().append(response);
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });
            },
            customDiscountSave: function () {
                $.ajax({
                    url: '{{ route('patient.discount.mode.custom.type.save') }}',
                    type: "POST",
                    data: $('#custom-discount-form').serialize(),
                    success: function (response) {
                        // console.log(response);
                        $('#custom-discount-list').empty().append(response);
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });
            },
            customDiscountSpecificSave: function () {
                $.ajax({
                    url: '{{ route('patient.discount.mode.specific.custom.discount.save') }}',
                    type: "POST",
                    data: $('#custom-discount-form').serialize(),
                    success: function (response) {
                        // console.log(response);
                        alert('Data successfully saved');
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });
            },
            exportDiscountReportToExcel : function(){
                window.open("{{route('patient.discount.mode.export.excel')}}");
            },
            exportDiscountReportToPdf : function(){
                window.open("{{route('patient.discount.mode.export.pdf')}}");
            },
        }
        $(".flexible-with-limit").hide();

        $("#fldmode-check").on('change', function () {
            $(".flexible-with-limit").hide();
            if (this.value === "FixedPercent") {
                $("#fldpercent").prop('disabled', false);
                // $("#discountLabelSelect").prop('disabled', true);
                $("#list-saved-discounts").prop('disabled', true);
            } else if (this.value === "CustomValues") {
                $("#fldpercent").prop('disabled', true);
                // $("#discountLabelSelect").prop('disabled', false);
                $("#list-saved-discounts").prop('disabled', false);
            } else if (this.value === "None") {
                $("#fldpercent").prop('disabled', true);
                // $("#discountLabelSelect").prop('disabled', true);
                $("#list-saved-discounts").prop('disabled', true);
            }else if (this.value === "Flexible") {
                // $("#fldpercent").prop('disabled', true);
                // // $("#discountLabelSelect").prop('disabled', true);
                // $("#list-saved-discounts").prop('disabled', true);
            }else if (this.value === "FlexibleWithLimit") {
                $(".flexible-with-limit").show();
            }
        });

        $('.bd-curr-modal-lg').on('shown.bs.modal', function () {
            if ($('.fldtype').val() === "" || $("#fldmode-check").val() !== "CustomValues") {
                showAlert('Discount Label must be selected and Discount mode must be Custom Values');
                $('.bd-curr-modal-lg').modal('hide');
            } else {
                $('.fldtype-custom').val($('.fldtype').val());
                $.ajax({
                    url: '{{ route('patient.discount.mode.custom.list') }}',
                    type: "POST",
                    data: $('#custom-discount-form').serialize(),
                    success: function (response) {
                        // console.log(response.specifics);
                        specificsData = response.specifics;
                        if (specificsData) {
                            $('#custom-Laboratory').val(specificsData.fldlab);
                            $('#custom-Equipment').val(specificsData.fldequip);
                            $('#custom-ExtraItem').val(specificsData.fldextra);
                            $('#custom-Medical').val(specificsData.fldmedicine);
                            $('#custom-Others').val(specificsData.fldother);
                            $('#custom-Procedures').val(specificsData.fldproc);
                            $('#custom-Radiology').val(specificsData.fldradio);
                            $('#custom-Registration').val(specificsData.fldregist);
                            $('#custom-GenServices').val(specificsData.fldservice);
                            $('#custom-Surgical').val(specificsData.fldsurgical);
                            $('#custom-discount-list').empty().append(response.html);
                        }

                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });
            }
        });



        $(document).ready(function () {

            $('.bd-no-discount-modal-lg').on('hidden.bs.modal', function (e) {
                $('.bd-no-discount-modal-lg').find('#before-add-list').empty();
                $('.bd-no-discount-modal-lg').find('#after-add-list').empty();

            })

            $('.bd-no-discount-modal-lg').on('shown.bs.modal', function (e) {
                $(this).find('#fldtype').val( $('.fldtype').val() )
            })


            $('#myTable1').bootstrapTable({
                stickyHeader: true,
            })
            $("#list-saved-discounts").prop('disabled', true);
            // $(".discountGroup").select2();
            $(".nodiscountGroup").select2({
                dropdownParent: $('.bd-no-discount-modal-lg')
            });
            $("#discount-itemName").select2({
                dropdownParent: $('.bd-curr-modal-lg')
            });
            $("#discount-category").select2({
                dropdownParent: $('.bd-curr-modal-lg')
            });

            $(document).on('keyup', '#customPercentage', function(){
                if($(this).val()){
                    $('#customDiscountSaveBtn').attr('disabled', false)
                }else{
                    $('#customDiscountSaveBtn').attr('disabled', true)
                }
            })


            $(document).on('click', '.tr-discount-mode', function () {
                // alert($(this).attr("data-date"))
                $("#__fldtype").val($(this).attr("data-billdiscount"));
                selected_td('#discountMode tr', this);

                $("#billing_mode").val($(this).attr("data-billMode"));
                $(".fldtype").val($(this).attr("data-billType"));

                $('#fldcredit').val( $(this).attr("data-fldcredit") )

                $('#fldamount').val( $(this).attr("data-fldamount") )

                $("#year-start").val($(this).attr("data-date"));

                $("#fldmode-check").val($(this).attr("data-billdiscount"));
                if ($(this).attr("data-billDiscount") === "CustomValues") {
                    $("#fldpercent").prop('disabled', true);
                    // $("#discountLabelSelect").prop('disabled', false);
                    $("#list-saved-discounts").prop('disabled', false);
                }
                else if ($(this).attr("data-billDiscount") === "FixedPercent") {
                    $("#fldpercent").prop('disabled', false);
                    $("#fldpercent").val($(this).attr("data-fixDis"));
                } else {
                    $("#fldpercent").val($(this).attr("data-fixDis"));
                    $("#list-saved-discounts").prop('disabled', true);
                }
            });
            @if ($message = Session::get('success'))
                showAlert("{{ $message }}")
            @endif

            $(document).on('click', '.checkboxtext', function(){
                $(this).closest('tr').find(':checkbox').focus().click();
            })

        });

        function selected_td(elemId, currentElem) {
            $(elemId).css('background-color', '#ffffff');
            $(currentElem).css('background-color', '#c8dfff');

            $.each($(elemId), function (i, e) {
                $(e).attr('is_selected', 'no');
            });
            $(currentElem).closest('tr').attr('is_selected', 'yes');
        }

        function myFunctionSearchPermission() {
            // Declare variables
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("no-discount-search");
            filter = input.value.toUpperCase();
            table = document.getElementById("nodiscount-table-search");
            tr = table.getElementsByTagName("tr");

            // Loop through all table rows, and hide those who don't match the search query
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[1];
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
        function myFunctionSearchDiscount() {
            // Declare variables
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("discount-search");
            filter = input.value.toUpperCase();
            table = document.getElementById("discount-table-search");
            tr = table.getElementsByTagName("tr");

            // Loop through all table rows, and hide those who don't match the search query
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[1];
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>
@endpush
