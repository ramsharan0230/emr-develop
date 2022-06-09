@extends('frontend.layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">
                                Food Mixture
                            </h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <div class="form-group form-row align-items-center">
                            <label for="" class="col-sm-2">Mixture Name</label>
                            <div class="col-sm-8">
                                <input type="text" name="fldgroup" id="fldgroup" class="form-control">
                            </div>
                            <div class="col-sm-1">
                                <a href="javascript:;" id="loadfoodmixture" class="btn btn-primary"><i class="fa fa-sync"></i></a>
                            </div>
                        </div>
                        <div class="form-group form-row align-items-center er-input">
                            <label for="" class="col-sm-2">Food Type</label>
                            <div class="col-sm-4">
                                @php $foodtypes = \App\Utils\Nutritionhelpers::getFoodtype(); @endphp
                                <select name="selectfoodtype" class="form-control form-input-food selectfoodtype" style="width: 100%">
                                    <option value=""></option>
                                    @forelse($foodtypes as $foodtype)
                                        <option value="{{ $foodtype->fldid }}"> {{ $foodtype->fldfoodtype }}</option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>
                            <label for="" class="col-sm-2">Food content</label>
                            <div class="col-sm-4">
                                <select name="selectfoodcontent" class="form-control form-input-food-mix" id="selectfoodcontent" class="full-width">
                                </select>
                            </div>

                        </div>
                        <div class="form-group form-row align-items-center">
                            <label for="" class="col-sm-2">Gram</label>
                            <div class="col-sm-8">
                                <input type="number" step="any" min="0" class="form-control" name="fooditemamount" id="fooditemamount" onkeydown="javascript: return event.keyCode === 8 ||
event.keyCode === 46 ? true : !isNaN(Number(event.key))">
                            </div>
                            <div class="col-sm-1">
                                <a href="javascript:;" class="btn btn-primary" id="food_group_submit_button"><i class="fa fa-plus"></i></a>
                            </div>
                        </div>
                        <div class="form-group">
                            <textarea name="foodmixtureprep" class="form-control textarea-food-mixture" id="foodmixtureprep"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <div class="major-table table-responsive" id="foodgroup_items">
                            
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
            setTimeout(function () {
                $('.selectfoodtype').select2({
                    placeholder: 'select food type'
                });

                $('#selectfoodcontent').select2({
                    'placeholder': 'select food content'
                });
            }, 3000);

            $('.selectfoodtype').change(function () {
                var fldid = $(this).val();

                $.ajax({
                    'type': 'post',
                    'url': '{{ route('foodcontentfromtype') }}',
                    'data': {
                        '_token': '{{ csrf_token() }}',
                        'foodtypeid': fldid
                    },
                    success: function (res) {
                        $('#selectfoodcontent').html(res);
                    }
                });
            });

            $('#food_group_submit_button').click(function () {
                var fldgroup = $('#fldgroup').val();
                var flditemname = $('#selectfoodcontent').val();
                var flditemamt = $('#fooditemamount').val();
                var fldprep = $('#foodmixtureprep').val();

                $.ajax({
                    'type': 'post',
                    'url': '{{ route('foodgroupsubmit') }}',
                    'data': {
                        '_token': '{{ csrf_token() }}',
                        'fldgroup': fldgroup,
                        'flditemname': flditemname,
                        'flditemamt': flditemamt,
                        'fldprep': fldprep
                    },
                    'success': function (res) {
                        $('#foodgroup_items').html(res);
                        showAlert('Fooditem successfully inserted');
                    }
                });
            });

            $('#loadfoodmixture').click(function () {
                var fldgroup = $('#fldgroup').val();

                $.ajax({
                    'type': 'post',
                    'url': '{{ route('foodmixturetable') }}',
                    'data': {
                        '_token': '{{ csrf_token() }}',
                        'fldgroup': fldgroup
                    },
                    'success': function (res) {
                        $('#foodgroup_items').html(res);
                    }
                });
            });
        });
    </script>
@endpush
