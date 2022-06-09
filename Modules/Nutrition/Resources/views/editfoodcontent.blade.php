@extends('frontend.layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <h4 class="mt-2 mb-1">Dietary Info</h4>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <div class="iq-search-bar custom-search">
                            <form action="#" class="searchbox">
                                <input type="text" class="text search-input" placeholder="Type here to search..."/>
                                <a class="search-link" href="#"><i class="ri-search-line"></i></a>
                            </form>
                        </div>
                        {{------------------- food list -----------------------}}
                        @include('nutrition::layouts.includes.foodlisting')
                        {{------------------- end food list -------------------}}
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <form action="{{ route('updatefoodcontent', encrypt($foodcontent->fldfoodid)) }}" class="form-horizontal" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('patch')
                            <div class="form-group form-row align-items-center">
                                <label for="" class="col-sm-2">Food Name:</label>
                                <div class="col-sm-8">
                                    <select name="fldfood" class="form-control form-select-dietary" required>
                                        <option value=""></option>
                                        @forelse($foodlists as $foodlist)
                                            <option value="{{ $foodlist->fldfood }}"  data-id="{{ $foodlist->fldid }}" {{ ($foodcontent->fldfood == $foodlist->fldfood) ? 'selected' : ''}} > {{ $foodlist->fldfood }}</option>
                                        @empty

                                        @endforelse
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <a href="#" data-toggle="modal" data-target="#add_food" class="btn btn-primary btn-sm"><i class="ri-add-fill"></i> Add</a>
                                </div>
                            </div>
                            <div class="form-group form-row align-items-center er-input">
                                <label for="" class="col-sm-2">Source:</label>
                                <div class="col-sm-5">
                                    <input type="text" name="fldsource" class="form-control input-small-dietary" placeholder="" value="{{ $foodcontent->fldsource }}" autocomplete="off" disabled>
                                </div>
                                <label for="" class="col-sm-1">Format:</label>
                                <div class="col-sm-4">
                                    <input type="text" name="fldformat" class="form-control form-control input-small-dietary" placeholder="" value="{{ $foodcontent->fldformat }}" autocomplete="off" disabled>
                                </div>
                            </div>
                            <div class="form-group form-row align-items-center er-input">
                                <label for="" class="col-sm-2">Catogery:</label>
                                <div class="col-sm-3">
                                    <select name="fldfoodtype" class="form-control" required>
                                        <option value=""></option>
                                        @foreach($foodtypes as $foodtype)
                                            <option value="{{ $foodtype->fldfoodtype }}" data-id="{{ $foodtype->fldid }}" {{ ($foodcontent->fldfoodtype == $foodtype->fldfoodtype) ? 'selected' : ''}}> {{ $foodtype->fldfoodtype }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <a href="#" data-toggle="modal" data-target="#add_category" class="btn btn-primary btn-sm"><i class="ri-add-fill"></i> Add</a>
                                </div>
                                <label for="" class="col-sm-1">Status:</label>
                                <div class="col-sm-4">
                                    <select name="fldfoodcode" class="form-control select-3" required>
                                        <option value=""> select status</option>
                                        <option value="Active" {{ ($foodcontent->fldfoodcode == "Active") ? 'selected' : ''}}>Active</option>
                                        <option value="Inactive" {{ ($foodcontent->fldfoodcode == "Inactive") ? 'selected' : ''}}>Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <h5 class="card-title text-center text-primary" style="border-bottom: 1px solid #0aa4b5;">Nutritional Value For 100grams of edible portion</h5>
                            <div class="row mt-4">
                                <div class="col-sm-6">
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-6">Moisture (g)</label>
                                        <div class="col-sm-6">
                                            <input type="number" step="any" name="fldfluid" class="form-control" placeholder="0" min="0" value="{{ $foodcontent->fldfluid }}">
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-6">Protien(g)</label>
                                        <div class="col-sm-6">
                                            <input type="number" step="any" name="fldprotein" class="form-control" placeholder="0" min="0" value="{{ $foodcontent->fldprotein }}">
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-6">Carbohydrate (g)</label>
                                        <div class="col-sm-6">
                                            <input type="number" step="any" name="fldsugar" class="form-control" placeholder="0" min="0" value="{{ $foodcontent->fldsugar }}">
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-6">lipids (g)</label>
                                        <div class="col-sm-6">
                                            <input type="number" step="any" name="fldlipid" class="form-control" placeholder="0" min="0" value="{{ $foodcontent->fldlipid }}">
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-6">Minerals (g)</label>
                                        <div class="col-sm-6">
                                            <input type="number" step="any" name="fldmineral" class="form-control" placeholder="0" min="0" value="{{ $foodcontent->fldmineral }}">
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-6">Iron (mg)</label>
                                        <div class="col-sm-6">
                                            <input type="number" step="any" name="fldiron" class="form-control" placeholder="0" min="0" value="{{ $foodcontent->fldiron }}">
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-6">phosphorous (mg)</label>
                                        <div class="col-sm-6">
                                            <input type="number" step="any" name="fldphosphorous" class="form-control" placeholder="0" min="0" value="{{ $foodcontent->fldphosphorous }}">
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-6">Thiamine (mg)</label>
                                        <div class="col-sm-6">
                                            <input type="number" step="any" name="fldthiamine" class="form-control" placeholder="0" min="0" value="{{ $foodcontent->fldthiamine }}">
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-6">Niacin (g)</label>
                                        <div class="col-sm-6">
                                            <input type="number" step="any" name="fldniacin" class="form-control" placeholder="0" min="0" value="{{ $foodcontent->fldniacin }}">
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-6">Free folic Acid(mcg)</label>
                                        <div class="col-sm-6">
                                            <input type="number" step="any" name="fldfreefolic" class="form-control" placeholder="0" min="0" value="{{ $foodcontent->fldfreefolic }}">
                                        </div>
                                    </div>

                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-6">Energy (KCaL)</label>
                                        <div class="col-sm-6">
                                            <input type="number" step="any" name="fldenergy" class="form-control" placeholder="0" min="0" value="{{ $foodcontent->fldenergy }}">
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-3">Protein Content</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="fldproteincont" class="form-control" placeholder="" value="{{ $foodcontent->fldproteincont }}" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-3">Sugar Content</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="fldsugarcont" class="form-control" placeholder="" value="{{ $foodcontent->fldsugarcont }}" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-3">Lipid Content</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="fldlipidcont" class="form-control" placeholder="" value="{{ $foodcontent->fldlipidcont }}" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-6">Crude Fiber (g)</label>
                                        <div class="col-sm-6">
                                            <input type="number" step="any" name="fldfibre" class="form-control" placeholder="0" min="0" value="{{ $foodcontent->fldfibre }}">
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-6">Calcium(mg)</label>
                                        <div class="col-sm-6">
                                            <input type="number" step="any" name="fldcalcium" class="form-control" placeholder="0" min="0" value="{{ $foodcontent->fldcalcium }}">
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-6">Carotene(mcg)</label>
                                        <div class="col-sm-6">
                                            <input type="number" step="any" name="fldcarotene" class="form-control" placeholder="0" min="0" value="{{ $foodcontent->fldcarotene }}">
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-6">Riboflabin (mg)</label>
                                        <div class="col-sm-6">
                                            <input type="number" step="any" name="fldriboflavin" class="form-control" placeholder="0" min="0" value="{{ $foodcontent->fldriboflavin }}">
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-6">Pyrioxine (mg)</label>
                                        <div class="col-sm-6">
                                            <input type="number" step="any" name="fldpyridoxine" class="form-control" placeholder="0" min="0" value="{{ $foodcontent->fldpyridoxine }}">
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-6">Total folic Acid(mcg)</label>
                                        <div class="col-sm-6">
                                            <input type="number" step="any" name="fldtotalfolic" class="form-control" placeholder="0" min="0" value="{{ $foodcontent->fldtotalfolic }}">
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-6">Vitamin C (mg)</label>
                                        <div class="col-sm-6">
                                            <input type="number" step="any" name="fldvitaminc" class="form-control" placeholder="0" min="0" value="{{ $foodcontent->fldvitaminc }}">
                                        </div>
                                    </div>

                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-6">Preparation:</label>
                                    </div>
                                    <div class="form-group">
                                        <textarea name="fldprep" class="form-control">{!! $foodcontent->fldprep !!}</textarea>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{--modals--}}
    @include('nutrition::layouts.modal.add-food')
    @include('nutrition::layouts.modal.add-category')
    {{--modals--}}
    <form id="delete_form" method="POST">
        @csrf
        @method('delete')
    </form>
@endsection
@push('after-script')
    <script>
        $(function() {

            function select2loading() {
                setTimeout(function() {
                    $('.select2foodname').select2({
                        placeholder : 'select food name'
                    });

                    $('.select2categoryname').select2({
                        placeholder : 'select category Name'
                    });
                }, 3000);
            }

            select2loading();


            // ajax to add foodname to tblfoodlist

            $('#foodnameaddbutton').click(function() {
                var foodname = $('#foodnamefield').val();

                if(foodname != '') {
                    $.ajax({
                        type : 'post',
                        url  : '{{ route('addfoodname') }}',
                        dataType : 'json',
                        data : {
                            '_token': '{{ csrf_token() }}',
                            'foodname': foodname,
                        },
                        success: function (res) {
                            showAlert(res.message);
                            if(res.message == 'Foodname added successfully') {
                                $('#foodnamefield').val('');
                                var deleteroutename = "{{ url('/nutrition/deletefoodname') }}/"+res.fldid;
                                $('#foodnamelistingmodal').append('<li class="food-list" style="border: 1px solid #ced4da;"><a href="javascript:void(0)" class="food_item" data-href="'+deleteroutename+'" data-id="'+res.fldid+'">'+res.fldfood+'</a></li>');
                                $('.select2foodname').append('<option value="'+res.fldfood+'" data-id="'+res.fldid+'">'+res.fldfood+'</option>');
                                select2loading();
                            }
                        }
                    });
                } else {
                    alert('foodname is required');
                }
            });

            // ajax to add foodname to tblfoodtype


            // selecting food item
            $('#foodnamelistingmodal').on('click', '.food_item', function() {
                $('#foodtobedeletedroute').val($(this).data('href'));
                $('#foodidtobedeleted').val($(this).data('id'));
            });

            // deleting selected food item
            $('#foodnamedeletebutton').click(function() {
                var deletefoodnameroute = $('#foodtobedeletedroute').val();
                var foodidtobedeleted = $('#foodidtobedeleted').val();

                if(deletefoodnameroute == '') {
                    alert('no foodname selected, please select the foodname.');
                }

                if(deletefoodnameroute != '') {
                    var really = confirm("You really want to delete this Food?");
                    if(!really) {
                        return false
                    } else {
                        $.ajax({
                            type : 'delete',
                            url : deletefoodnameroute,
                            dataType : 'json',
                            data : {
                                '_token': '{{ csrf_token() }}',
                            },
                            success: function (res) {
                                if(res.message =='success') {
                                    showAlert(res.successmessage);
                                    $("#foodnamelistingmodal").find(`[data-href='${deletefoodnameroute}']`).parent().remove();
                                    $(".select2foodname").find(`[data-id='${foodidtobedeleted}']`).remove();
                                    $('#foodtobedeletedroute').val('');
                                    $('#categoryidtobedeleted').val('');
                                    select2loading();
                                } else if(res.message == 'error') {
                                    showAlert(res.errormessage);
                                }
                            }
                        });
                    }
                }
            });

            $('#categorynameaddbutton').click(function() {
                var categoryname = $('#categorynamefield').val();

                if(categoryname != '') {
                    $.ajax({
                        type : 'post',
                        url  : '{{ route('addfoodtype') }}',
                        dataType : 'json',
                        data : {
                            '_token': '{{ csrf_token() }}',
                            'foodtype': categoryname,
                        },
                        success: function (res) {
                            showAlert(res.message);
                            if(res.message == 'category added successfully') {
                                $('#categorynamefield').val('');
                                var deleteroutename = "{{ url('/nutrition/deletefoodtype') }}/"+res.fldid;
                                $('#categorylistingmodal').append('<li class="foodtype-list" style="border: 1px solid #ced4da;"><a href="javascript:void(0)" class="foodtype_item" data-href="'+deleteroutename+'" data-id="'+res.fldid+'">'+res.fldfoodtype+'</a></li>');
                                $('.select2categoryname').append('<option value="'+res.fldfood+'" data-id="'+res.fldid+'">'+res.fldfoodtype+'</option>');
                                select2loading();
                            }
                        }
                    });
                } else {
                    alert('category name is required');
                }
            });


            // selecting category item
            $('#categorylistingmodal').on('click', '.foodtype_item', function() {
                $('#categorytobedeletedroute').val($(this).data('href'));
                $('#categoryidtobedeleted').val($(this).data('id'));
            });

            // deleting selected category item
            $('#categorydeletebutton').click(function() {
                var deletecategoryroute = $('#categorytobedeletedroute').val();
                var categoryidtobedeleted = $('#categoryidtobedeleted').val();

                if(deletecategoryroute == '') {
                    alert('no category selected, please select the food category.');
                }

                if(deletecategoryroute != '') {
                    var really = confirm("You really want to delete this Food category?");
                    if(!really) {
                        return false
                    } else {
                        $.ajax({
                            type : 'delete',
                            url : deletecategoryroute,
                            dataType : 'json',
                            data : {
                                '_token': '{{ csrf_token() }}',
                            },
                            success: function (res) {
                                if(res.message == 'success') {
                                    showAlert(res.successmessage);
                                    $("#categorylistingmodal").find(`[data-href='${deletecategoryroute}']`).parent().remove();
                                    $(".select2categoryname").find(`[data-id='${categoryidtobedeleted}']`).remove();
                                    $('#categorytobedeletedroute').val('');
                                    $('#categoryidtobedeleted').val('');
                                    select2loading();
                                } else if(res.message == 'error') {
                                    showAlert(res.errormessage);
                                }
                            }
                        });
                    }
                }
            });

                @if(Session::has('success_message'))
            var successmessage = '{{ Session::get('success_message') }}';
            showAlert(successmessage);
                @endif

                @if(Session::has('error_message'))
            var errormessage = '{{ Session::get('error_message') }}';
            showAlert(errormessage);
            @endif

            // validation error message

                @if($errors->any())
            var validation_error = '';

            @foreach($errors->all() as $error)
                validation_error += '{{ $error }} \n';
            @endforeach

            showAlert(validation_error);
            @endif

            // deleting the foodcontent

            $('.deletefood').click(function() {
                var really = confirm("You really want to delete this food?");
                var href = $(this).data('href');
                console.log(href);
                if(!really) {
                    return false
                } else {
                    $('#delete_form').attr('action', href);
                    $('#delete_form').submit();
                }
            });
        });

    </script>
@endpush
