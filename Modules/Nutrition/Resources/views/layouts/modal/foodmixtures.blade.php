<link rel="stylesheet" href="{{ asset('assets/css/modal.css') }}">
<div class="modal" id="food_mixtures">
    <div class="modal-dialog modal-lg">
        <div class="modal-content ">
            <!-- Modal Header -->
            <div class="modal-header" style="background-color: #333232 !important;">
                <h6 class="modal-title">Food Mixture</h6>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-10">
                        <div class="group__box half_box">
                            <div class="box__label" style="flex: 0 0 31%;">
                                <label style="width: 100%">Mixture Name</label>
                            </div>&nbsp;&nbsp;
                            <div class="box__input" style="flex: 0 0 38%;">
                                <input type="text" name="fldgroup" id="fldgroup">
{{--                                <select class="form-input-food-mix" readonly="">--}}
{{--                                    <option value="" selected=selected></option>--}}
{{--                                    <option value="" selected=selected></option>--}}
{{--                                    <option value="" selected=selected></option>--}} 
{{--                                </select>--}}
                            </div>
                            <div class="box__icon">
                                <a href="#" id="loadfoodmixture"><img src="{{asset('assets/images/refresh.png')}}" width="20px" alt=""></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="group__box half_box">
                            <div class="box__input">
                                <button type="button" class="default-btn" data-dismiss="modal"><i class="fas fa-list"></i>&nbsp;List&nbsp;&nbsp;</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-2" style="margin-top: 0;">
                    <div class="col-md-8">
                        <div class="form-group">
                            @php $foodtypes = \App\Utils\Nutritionhelpers::getFoodtype(); @endphp
                                <div class="box__input" style="flex: 0 0 49%;">
                                    <select name="selectfoodtype" class="form-input-food " style="width: 100%">
                                        <option value=""></option>
                                        @forelse($foodtypes as $foodtype)
                                            <option value="{{ $foodtype->fldid }}" > {{ $foodtype->fldfoodtype }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>&nbsp;&nbsp;
                            <div class="box__input" style="flex: 0 0 49%;">
                                <select name="selectfoodcontent" class="form-input-food-mix" id="selectfoodcontent" style="width: 100%">

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="group__box half_box">
                            <div class="box__input" style="flex: 0 0 27%;">
                                <input type="number" step="any" min="0" name="fooditemamount" id="fooditemamount">
                            </div>&nbsp;&nbsp;
                            <div class="box__label">
                                <label class="col-12">Gram</label>
                            </div>&nbsp;
                            <a type="button" href="javascript:void(0)" class="btn default-btn f-btn-icon-b" id="food_group_submit_button"><i class="fa fa-plus"></i></a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <textarea name="foodmixtureprep" class="textarea-food-mixture" id="foodmixtureprep"></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-food-mixture" id="foodgroup_items">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        setTimeout(function(){
            $('.selectfoodtype').select2({
                placeholder : 'select food type'
            });

            $('#selectfoodcontent').select2({
                'placeholder' : 'select food content'
            });
        }, 3000);

        $('.selectfoodtype').change(function() {
            var fldid = $(this).val();

            $.ajax({
               'type' : 'post',
               'url' : '{{ route('foodcontentfromtype') }}',
               'data' : {
                   '_token' : '{{ csrf_token() }}',
                   'foodtypeid' : fldid
               },
                success: function (res) {
                  $('#selectfoodcontent').html(res);
                }
            });
        });

        $('#food_group_submit_button').click(function(){
            var fldgroup =  $('#fldgroup').val();
            var flditemname = $('#selectfoodcontent').val();
            var flditemamt = $('#fooditemamount').val();
            var fldprep = $('#foodmixtureprep').val();

            $.ajax({
               'type' : 'post',
               'url' : '{{ route('foodgroupsubmit') }}',
               'data' : {
                   '_token' : '{{ csrf_token() }}',
                   'fldgroup' : fldgroup,
                   'flditemname' : flditemname,
                   'flditemamt' : flditemamt,
                   'fldprep' : fldprep
               },
                'success' : function (res) {
                    $('#foodgroup_items').html(res);
                    showAlert('Fooditem successfully inserted');
                }
            });
        });

        $('#loadfoodmixture').click(function() {
            var fldgroup = $('#fldgroup').val();

            $.ajax({
                'type' : 'post',
                'url' : '{{ route('foodmixturetable') }}',
                'data' : {
                    '_token' : '{{ csrf_token() }}',
                    'fldgroup' : fldgroup
                },
                'success' : function (res) {
                    $('#foodgroup_items').html(res);
                }
            });
        });
    });
</script>
