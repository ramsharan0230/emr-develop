@extends('frontend.layouts.master')
@section('content')
    <div class="container-fluid">
        {{--@include('frontend.common.alert_message')--}}
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">User Share</h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <form action="{{ route('usershare.store') }}" method="POST" class="row">
                                @csrf

                                <div class="form-group mb-2 align-items-center col-3">
                                    <label for="" class="control-label mb-0">Doctor</label>
                                    <div class="">
                                        <select class="form-control select2" name="doctor_id" required>
                                            <option value="" disabled selected>--Select Doctor--</option>
                                            @foreach ($doctors as $doctor)
                                                <option value="{{ $doctor->id }}">{{ $doctor->fldfullname }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group mb-2 align-items-center col-3">
                                    <label for="" class="control-label mb-0">Billing Mode</label>
                                    <div class="">
                                        <select id="select-billing-set" class="form-control select2" name="billing_set[]" required multiple>
                                            @foreach ($billing_types as $type)
                                                <option name="{{ $type->fldsetname }}">{{ $type->fldsetname }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group mb-2 align-items-center col-3">
                                    <label for="" class="control-label mb-0">Item Type</label>
                                    <div class="">
                                        <select id="select-item-type" class="form-control select2" name="item_type[]" required multiple>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group mb-2 align-items-center col-3">
                                    <label for="" class="control-label mb-0">Category</label>
                                    <div class="">
                                        <select id="select-category" class="form-control select2" name="category" required>
                                            <option value="" disabled selected>--Select Category--</option>
                                            @foreach ($categories as $key => $category)
                                                <option value="{{ $category }}">{{ ucfirst($category) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- sub category --}}
                                <div id="sub-category-container" style="display: none;" class="form-group mb-2 align-items-center col-3">
                                    <label for="" class="control-label mb-0">Sub Category</label>
                                    <div class="">
                                        <select id="select-sub-category" data-type="sub-category" class="form-control select2" name="sub_category_id" >
                                            <option value="" disabled selected>--Select Sub Category--</option>
                                            @foreach ($sub_categories as $key => $sub_category)
                                                <option value="{{ $sub_category->id }}">{{ ucfirst($sub_category->name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group mb-2 align-items-center col-3">
                                    <label for="" class="control-label mb-0">Item Name</label>
                                    <div class="">
                                        <select id="select-item-list" class="form-control select2" name="item_name[]" required multiple>

                                        </select>
                                    </div>
                                </div>
                                <div class="form-group mb-2 align-items-center col-3">
                                    <label for="" class="control-label mb-0">Tax Group</label>
                                    <div class="">
                                        <select class="form-control select2" name="tax_group_id" required>
                                            <option value="" disabled selected>--Select Tax Group--</option>
                                            @foreach ($tax_groups as $key => $group)
                                                <option value="{{ $group->fldid }}">{{ $group->fldgroup }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group mb-2 align-items-center col-3">
                                    <label for="" class="control-label mb-0">Share %</label>
                                    <div class="">
                                        <input type="number" step="0.01" max="100" required class="form-control" name="share">
                                    </div>
                                </div>
                                <div class="form-group mb-2 align-items-center col-3">
                                    <label for="" class="control-label mb-0">Is IPD Referable</label>
                                    <div class="">
                                        <input type="checkbox"  class="form-control" name="ipdreferal" id="ipdreferal" value="1">
                                    </div>
                                </div>
                                <div class="form-group mb-2 align-items-center col-3">
                                    <label class="control-label mb-0" for="parttime">is Part Time?</label>
                                    <div class="">
                                        <input class="form-control" type="checkbox" id="parttime" name="parttime" value="1">
                                    </div>
                                </div>
                                <div class="col-3 mt-4">



                                    <button type="submit" class="btn btn-primary">Submit <i class="ri-arrow-right-line"></i></button>
                                </div>
                            </form>
                            <p><strong style="color: red">कृपया OPD Consultation नछानीदिनु होला | <br> Doctor को वास्तविक सेयर % हालिदिनु होला ।</strong></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="iq-header-sub-title ml-2">
                            <button type="button" class="btn btn-primary btn-action" id="open-clone-form" >Clone Doctor</button>
                        </div>
                    </div>
                    <hr>
                    <div class="row" style="margin-top: 30px;">
                        <div class="col-sm-12">
                            <div class="table-responsive table-container">
                                <div class="form-group form-row align-items-center">
                                    <label for="" class="col-sm-1">Search:</label>
                                    <div class="col-sm-9">
                                        <input type="text" id="customSearch" class="form-control" name="search" placeholder="Doctor/Item Name" id="js-laboratory-search-input">
                                    </div>
                                    {{-- <div class="col-sm-2">
                                        <button id="search-btn" class="btn-primary"><i class="ri-search-line"></i></button>
                                    </div> --}}
                                </div>

                                <div class="res-table table-sticky-th">
                                    <table id="user-share-table" class="table table-bordered table-striped table-hover">
                                        <thead class="thead-light">
                                        <tr>
                                            <th>&nbsp;</th>
                                            <th>Doctor</th>
                                            <th>Billing Mode</th>
                                            <th>Item Type</th>
                                            <th>Item Name</th>
                                            <th>Share %</th>
                                            <th>Tax %</th>
                                            <th>Category</th>
                                            <th>OT Dr. Group (Sub Group)</th>
                                        </tr>
                                        </thead>
                                        <tbody id="js-user-share-item-tbody">
                                        @foreach($user_shares as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ ($item->user ? ucfirst($item->user->fldfullname): '--') }}</td>
                                                <td>{{ $item->billing_mode }}</td>
                                                <td>{{ $item->flditemtype }}</td>
                                                <td>{{ ucfirst($item->flditemname) }}</td>
                                                <td class="text-center">{{ $item->flditemshare }}</td>
                                                <td class="text-center">{{ $item->flditemtax }}</td>
                                                <td>{{ ucfirst($item->category) }}</td>
                                                <td>
                                                    {{ $item->sub_category->name??'--' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div id="bottom_anchor">
                                    {{ $user_shares->render() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('frontend.common.clone-form')
@endsection

@push('after-script')
    <script src="{{ asset('js/search-ajax.js')}}"></script>
    <script>
        $(function () {
            document.getElementById("ipdreferal").disabled = true;
            $.ajaxSetup({
                headers: {
                    "X-CSRF-Token": $('meta[name="_token"]').attr("content")
                }
            });

            $("#select-category").on('change', function(event) {
                if ($(this).val() == 'OT Dr. Group') {
                    $("#sub-category-container").show();
                    return;
                }


                $("#sub-category-container").hide();
            });

            $(document).on('keyup', '.select2-search__field' , function (e) {
                var id = $(this).closest('.select2-dropdown').find('.select2-results ul').attr('id');

                if(id == "select2-select-sub-category-results") {
                    if(e.keyCode === 13) {
                        let sub_category = $(this).val();
                        let r = confirm("Proceed to create new category '" + sub_category + "'");

                        if (r) {
                            // save item to sub-category table
                            let url = '{!! route("usershare.store.sub-category") !!}';
                            $.ajax({
                                url: url,
                                type: 'POST',
                                data: {name: sub_category},
                                dataType: 'json',
                                async: true,
                                success: function(res) {
                                    var newOption = new Option(res.data.name, res.data.id, true, true);
                                    $('#select-sub-category').append(newOption).trigger('change');
                                    $('#select-sub-category').val(res.data.id).trigger('change');
                                    $("#select-sub-category").select2("close");
                                    showAlert(res.message);
                                },
                                error: function(error) {
                                    showAlert(error.responseJSON.message, '');
                                }
                            });
                        }
                    }
                }
            });

            $("#customSearch").searchAjax({
                url: '{!! route("usershare.filter") !!}',
                paginate: true,
                paginateId: "bottom_anchor", // anchor tag encapsulated div
                onResult: function(res) {
                    let tbody = $("#js-user-share-item-tbody");
                    let tr_data = "";
                    let sn = res.data.current_page * (res.data.per_page - 1);
                    $.each(res.data.data, function(i, v) {
                        tr_data += '<tr>\
                            <td>'+ sn++ +'</td>\
                            <td>'+((v.user)? v.user.fldfullname : '--') +'</td>\
                            <td>'+((v.billing_mode)? v.billing_mode : '') +'</td>\
                            <td>'+((v.billing_mode)? v.flditemtype : '') +'</td>\
                            <td>'+v.flditemname+'</td>\
                            <td>'+v.flditemshare+'</td>\
                            <td>'+v.flditemtax+'</td>\
                            <td>'+v.category+'</td>\
                            <td>'+((v.sub_category)? v.sub_category.name : '--') +'</td>\
                        </tr>';
                    });

                    tbody.html(tr_data);
                    $("#bottom_anchor").html(res.paginate_view);
                }
            });

            $('#search-item-list').on('keyup', function() {
                if(this.value){
                    console.log(this.value);
                    var val = this.value;
                    if(val){
                        $('#select-item-checklist-clone li').hide().filter(function() {
                            return $(this).data('flditem').includes(val);
                        })
                            .show();
                    } else{
                        $('#select-item-checklist-clone li').show();
                    }

                }else{
                    $('#select-item-checklist-clone li').show();
                }

            })
                .change();

            $("#open-clone-form").on('click', function() {
                $('#clone-modal').modal('show');
            });

            $(".add-item-class").on('click', function() {
                console.log('checked');
                console.log($(this).val());
            });


            $("#select-billing-set").on('change', function() {
                resetOptions();
                let e = $(this);
                let billing_set = e.val();
                let options = "";
                let item_types = getItemTypeFromBillingSet(billing_set).then(function(res) {
                    // populate the option
                    $.each(res.data, function(i, v) {
                        options += '<option value="'+v.flditemtype+'">'+v.flditemtype+'</option>'
                    });

                    $("#select-item-type").html(options);
                });
            });

            $("#select-item-type").on('change', function() {
                let e = $(this);
                let item_type = e.val();
                let options = "";
                $("select[name='category']").val('');
                $("select[name='category']").select2();
            });

            $("#select-category").on('change', function() {

                let e = $(this);
                let category = e.val();
                let item_type = $("#select-item-type").val();
                let options = "";
                let item_types = getItemListFromCategory(category, item_type).then(function(res) {
                    console.log(res.data);
                    // populate the option
                    options += '<option value="all">All</option>';
                    $.each(res.data, function(i, v) {
                        options += '<option value="'+v.flditemname+'">'+v.flditemname+'</option>'
                    });


                    if(category == 'referable'){

                        document.getElementById("ipdreferal").disabled = false;
                    }else{
                        document.getElementById("ipdreferal").disabled = true;
                    }

                    $("#select-item-list").html(options);
                });
            });

            async function getItemTypeFromBillingSet(billing_set) {
                let route = "{!! route('usershare.get-item-types', ':BILLING_SET') !!}";
                route = route.replace(':BILLING_SET', billing_set);
                return await $.ajax({
                    url: route,
                    type: 'GET',
                    dataType: 'JSON',
                    async: true
                });
            }





            async function getItemListFromItemType(item_type) {
                let route = "{!! route('usershare.get-item-list', ':ITEM_TYPE') !!}";
                route = route.replace(':ITEM_TYPE', item_type);
                return await $.ajax({
                    url: route,
                    type: 'GET',
                    dataType: 'JSON',
                    async: true
                });
            }

            async function getItemListFromCategory(category, itemType) {
                let route = "{!! route('usershare.category.item-list', ['category'=> ':CATEGORY', 'itemType' => ':ITEM_TYPE']) !!}";
                route = route.replace(':CATEGORY', category);
                route = route.replace(':ITEM_TYPE', itemType);
                return await $.ajax({
                    url: route,
                    type: 'GET',
                    dataType: 'JSON',
                    async: true
                });
            }

            function resetOptions() {
                // $("#select-item-list").html('<option value="" disabled selected>-- Select Item Name --</option>');
                // $("#select-item-type").html('<option value="" disabled selected>--Select Item Type--</option>');
            }
        });

    </script>
    @if(Session::get('display_generated_invoice'))
        <script>
            var params = {
                encounter_id: "{{Session::get('billing_encounter_id')}}",
                invoice_number: "{{Session::get('invoice_number')}}"
            };
            var queryString = $.param(params);
            window.open("{{ route('billing.display.invoice') }}?" + queryString, '_blank');
        </script>
    @endif
@endpush
