<style>
    .clone-checklist {
        border: 1px solid #ccc;
        padding: 5px;
        height: 200px;
        overflow: auto;
        border-radius: 4px;
        margin-top: 4px;
    }

    .search-item-list {
        width: calc(100% - 83px);
    }
</style>
<div class="modal fade bd-example-modal-xl" id="clone-modal" role="dialog" aria-labelledby="myLargeModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="row">
            <div class="col-sm-12">
                <form action="{{ route('usershare.clone') }}" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Clone Doctor Form</h5>
                            <button type="button" class="close" id="clone-close-btn" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="container">
                            <div class="row">
                                <div class="form-group mb-2 align-items-center col-4">
                                    <label for="" class="control-label mb-0">Clone Doctor</label>
                                    <div class="">
                                        <select class="form-control select2" name="clone_doctor_id"
                                                id="select-clone-doctor-id" required>
                                            <option value="" disabled selected>--Select Doctor To Clone--</option>
                                            @foreach ($doctors as $doctor)
                                                <option value="{{ $doctor->id }}">{{ $doctor->fldfullname }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group mb-2 align-items-center col-4">
                                    <label for="" class="control-label mb-0">Doctor</label>
                                    <div class="">
                                        <select class="form-control select2" name="doctor_id"  id="select-doctor-id" required>
                                            <option value="" disabled selected>--Select Doctor--</option>
                                            @foreach ($doctors as $doctor)
                                                <option value="{{ $doctor->id }}">{{ $doctor->fldfullname }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group mb-2 align-items-center col-4">
                                    <label for="" class="control-label mb-0">Billing Mode</label>
                                    <div class="">
                                        <select id="select-billing-set-clone" class="form-control select2"
                                                name="billing_set[]" multiple required>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group mb-2 align-items-center col-4">
                                    <label for="" class="control-label mb-0">Item Type</label>
                                    <div class="">
                                        <select id="select-item-type-clone" class="form-control select2"
                                                name="item_type[]"
                                                multiple required>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group mb-2 align-items-center col-4">
                                    <label for="" class="control-label mb-0">Category</label>
                                    <div class="">
                                        <select id="select-category-clone" class="form-control select2" name="category"
                                                required>
                                            <option value="" disabled selected>--Select Category--</option>
                                        </select>
                                    </div>
                                </div>


                                <div id="sub-category-container" style="display: none;"
                                     class="form-group mb-2 align-items-center col-4">
                                    <label for="" class="control-label mb-0">Sub Category</label>
                                    <div class="">
                                        <select id="select-sub-category" data-type="sub-category"
                                                class="form-control select2" name="sub_category_id">
                                            <option value="" disabled selected>--Select Sub Category--</option>
                                            @foreach ($sub_categories as $key => $sub_category)
                                                <option value="{{ $sub_category->id }}">{{ ucfirst($sub_category->name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="form-group mb-2 align-items-center col-5">
                                    <label for="" class="control-label mb-0">Item Name</label>
                                    <input type="text" placeholder="search item list..." class="search-item-list"
                                           id="search-item-list">

                                    <div id="select-item-checklist-clone" class="clone-checklist">

                                    </div>
                                </div>
                                <div class="col-2 text-center" style="padding-top: 80px;">
                                    <button type="submit" class="btn btn-primary btn-action" id="clone-btn">Clone <i
                                                class="ri-arrow-right-line"></i>
                                    </button>
                                </div>
                                <div class="col-5">
                                    <label for="" class="control-label">Cloned Item List</label>
                                    <div class="clone-checklist mt-2" id="select-item-clone-list">
                                    </div>
                                </div>

                            </div>

                            <div class="col-3 mt-4">

                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(function () {

        $("#select-clone-doctor-id").on('change', function () {
            let e = $(this);
            let doctor_id = e.val();
            if(doctor_id == null){
                return;
            }
            let billing_mode_options = "";

            let item_list_options = "";

            let billing_modes = getDoctorBillingSet(doctor_id).then(function (res) {
                // populate the option
                $.each(res.data, function (i, v) {
                    billing_mode_options += '<option value="' + v.billing_mode + '">' + v.billing_mode + '</option>'
                });

                $("#select-billing-set-clone").html(billing_mode_options);
            });

        });

        $("#select-doctor-id").on('change', function () {
            if(this.value == $("#select-clone-doctor-id").val()){
                alert('Cannot clone on same doctor.')
            }

        });

        $("#select-billing-set-clone").on('change', function () {
                let e = $(this);
                let doctor_id = $("#select-clone-doctor-id").val();
                let billing_set_id = e.val();
            if(doctor_id == null){
                return;
            }
                let item_type_options = "";
                let item_types = getDoctorItemTypes(doctor_id, billing_set_id).then(function (res) {
                    // populate the option
                    $.each(res.data, function (i, v) {
                        item_type_options += '<option value="' + v.flditemtype + '">' + v.flditemtype + '</option>'
                    });

                    $("#select-item-type-clone").html(item_type_options);
                });
            }
        );
        $("#clone-btn").on('click', function () {
            $(".loader-ajax-start-stop-container").show();
            }
        );
        $("#select-item-type-clone").on('change', function () {
            let e = $(this);
            let doctor_id = $("#select-clone-doctor-id").val();
            let billing_set_id = $("#select-billing-set-clone").val();
            let item_type_id = e.val();
            if(doctor_id == null){
                return;
            }
            let category_list_options = "";
            let category_lists = getDoctorCategoryList(doctor_id, billing_set_id, item_type_id).then(function (res) {
                console.log(res.data);
                // populate the option
                category_list_options = '<option value="" disabled selected>--Select Category--</option>'
                $.each(res.data, function (i, v) {
                    category_list_options += '<option value="' + v.category + '">' + v.category + '</option>'
                });
                $("#select-category-clone").html(category_list_options);
            });

        });

        $("#select-category-clone").on('change', function () {
            let e = $(this);
            let _this = $(this);
            let doctor_id = $("#select-clone-doctor-id").val();
            let billing_set_id = $("#select-billing-set-clone").val();
            let item_type_id = $("#select-item-type-clone").val();
            let category = e.val();
            if(doctor_id == null){
                return;
            }
            let list_options = "";
            let check_list_options = "<ul>";
            let category_lists = getDoctorItemList(doctor_id, billing_set_id, item_type_id, category).then(function (res) {
                console.log(res.data);
                if (res.data.length > 0) {
                    check_list_options = "<ul><li data-flditem='all'><input style='margin-right:4px;' type='checkbox'  id='all-item-name' value=''><label for='all'>Select All</label></option></li>";
                }

                // populate the option
                $.each(res.data, function (i, v) {
                    // list_options += '<option value="'+v.flditemname+'">'+v.flditemname+'</option>'
                    check_list_options += '<li data-flditem="' + v.flditemname + '"><input style="margin-right:4px;" type="checkbox" name="item_name[]" id="' + v.flditemname + '+1" class="add-item-class"  value="' + v.flditemname + '"><label for="' + v.flditemname + '+1">' + v.flditemname + '</label></option></li>'
                });
                check_list_options += "</ul>";
                $("#select-item-checklist-clone").html(check_list_options);
                var cloneArr = [];
                $(".add-item-class").on('click', function () {
                    if ($(this)[0].checked) {
                        cloneArr.push($(this).val());
                        let list_options = "<ul>";
                        $.each(cloneArr, function (key, val) {
                            if(val){
                                list_options += '<li value="' + val + '">' + val + '</li>'
                            }
                        });
                        list_options += "</ul>";
                        $("#select-item-clone-list").html(list_options);
                    } else {
                        var index = cloneArr.indexOf($(this).val());
                        if (index > -1) {
                            cloneArr.splice(index, 1);
                        }
                        let list_options = "<ul>";
                        $.each(cloneArr, function (key, value) {
                            if(value){
                            list_options += '<li value="' + value + '">' + value + '</li>'
                                }
                        });
                        list_options += "</ul>";
                        $("#select-item-clone-list").html(list_options);
                    }
                });
                $('#all-item-name').on('change', function () {
                    console.log("here");
                    var checkboxes = $(this).closest('ul').find(':checkbox');
                    let list_options = "<ul>";
                    checkboxes.prop('checked', $(this).is(':checked'));
                    if( $(this).is(':checked')){
                        console.log('hhh');
                        checkboxes.each(function(){
                            if($(this).val()){
                                cloneArr.push($(this).val());
                                list_options += '<li value="' + $(this).val() + '">' + $(this).val() + '</li>'
                            }
                        });
                        list_options += "</ul>";
                        $("#select-item-clone-list").html(list_options);
                    }else{
                        cloneArr = [];
                        $("#select-item-clone-list ul").remove();
                    }

                });
            });

        });

        $('.select2').select2({
            allowClear: true // This is for clear get the clear button if wanted
        });

        $("#clone-close-btn").on('click', function () {
            $("#select-clone-doctor-id").val("").trigger('change'); ;
            $("#select-doctor-id").val("").trigger('change'); ;
            $("#select-category-clone").val("").trigger('change'); ;
            $("#select-item-type-clone").val("").trigger('change');;
            $("#select-billing-set-clone").val("").trigger('change'); ;
            $('#clone-modal').modal('hide');



            cloneArr = [];
            $("#select-item-clone-list ul").remove();
            $("#select-item-checklist-clone ul").remove();
            }
        );

        async function getDoctorBillingSet(doctor_id) {
            let route = "{!! route('usershare.get-doctor-billing-modes', ':DOCTOR_ID') !!}";
            route = route.replace(':DOCTOR_ID', doctor_id);
            return await $.ajax({
                url: route,
                type: 'GET',
                dataType: 'JSON',
                async: true
            });
        }

        async function getDoctorItemTypes(doctor_id, billing_set_id) {
            let route = "{!! route('usershare.get-doctor-item-types', ['doctor_id'=> ':DOCTOR_ID', 'billing_id' => ':BILLING_SET_ID']) !!}";
            route = route.replace(':DOCTOR_ID', doctor_id);
            route = route.replace(':BILLING_SET_ID', billing_set_id);
            return await $.ajax({
                url: route,
                type: 'GET',
                dataType: 'JSON',
                async: true
            });
        }

        async function getDoctorCategoryList(doctor_id, billing_id, item_type_id) {
            let route = "{!! route('usershare.get-doctor-category-list', ['doctor_id'=> ':DOCTOR_ID', 'billing_id' => ':BILLING_SET_ID','item_type_id' => ':ITEM_TYPE_ID']) !!}";
            route = route.replace(':DOCTOR_ID', doctor_id);
            route = route.replace(':BILLING_SET_ID', billing_id);
            route = route.replace(':ITEM_TYPE_ID', item_type_id);
            return await $.ajax({
                url: route,
                type: 'GET',
                dataType: 'JSON',
                async: true
            });
        }

        async function getDoctorItemList(doctor_id, billing_id, item_type_id, category) {
            let route = "{!! route('usershare.get-doctor-item-list', ['doctor_id'=> ':DOCTOR_ID', 'billing_id' => ':BILLING_SET_ID','item_type_id' => ':ITEM_TYPE_ID','category'=> ':CATEGORY']) !!}";
            route = route.replace(':CATEGORY', category);
            route = route.replace(':ITEM_TYPE_ID', item_type_id);
            route = route.replace(':BILLING_SET_ID', billing_id);
            route = route.replace(':DOCTOR_ID', doctor_id);
            return await $.ajax({
                url: route,
                type: 'GET',
                dataType: 'JSON',
                async: true
            });
        }

    });

</script>


