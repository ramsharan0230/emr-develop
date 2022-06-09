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
                        <div class="row">
                            <div class="col-lg-6 col-md-12">
                                <div class="form-group form-row align-items-center">
                                    <label for="" class="col-sm-4">Age Group:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" name="fldagegroup" id="fldagegroup">
                                            <option value=""></option>
                                            <option value="Adolescent" selected=selected>Adolescent</option>
                                            <option value="Adult" selected=selected>Adult</option>
                                            <option value="All age" selected=selected>All Age</option>
                                            <option value="Children" selected=selected>Children</option>
                                            <option value="Elderly" selected=selected>Elderly</option>
                                            <option value="Infant" selected=selected>Infant</option>
                                            <option value="Neonate" selected=selected>Neonate</option>
                                            <option value="Toddler" selected=selected>Toddler</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group form-row align-items-center">
                                    <label for="" class="col-sm-4">Protein(mg/kg):</label>
                                    <div class="col-sm-8">
                                        <input type="number" step="any" min="0" name="fldprotein" id="fldprotein" value="" class="input-nutri form-control" placeholder="0">
                                    </div>
                                </div>
                                <div class="form-group form-row align-items-center">
                                    <label for="" class="col-sm-4">Lipid(mg/kg):</label>
                                    <div class="col-sm-8">
                                        <input type="number" step="any" min="0" name="fldlipid" id="fldlipid" value="" class="input-nutri form-control" placeholder="0">
                                    </div>
                                </div>
                                <div class="form-group form-row align-items-center">
                                    <label for="" class="col-sm-4">Dextrose(mg/kg):</label>
                                    <div class="col-sm-8">
                                        <input type="number" step="any" min="0" name="flddextrose" id="flddextrose" value="" class="input-nutri form-control" placeholder="0">
                                    </div>
                                </div>
                                <div class="form-group form-row align-items-center">
                                    <label for="" class="col-sm-4">Non-N Energy(mg/kg):</label>
                                    <div class="col-sm-8">
                                        <input type="number" step="any" min="0" name="fldnne" id="fldnne" value="" class="input-nutri form-control" placeholder="0">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="form-group form-row align-items-center">
                                    <label for="" class="col-sm-4">Gender:</label>
                                    <div class="col-sm-8">
                                        <select class="form-input-nutri form-control" name="fldptsex" id="fldptsex">
                                            <option value=""></option>
                                            <option value="Both Sex" selected=selected>Both Sex</option>
                                            <option value="Female" selected=selected>Female</option>
                                            <option value="Male" selected=selected>Male</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group form-row align-items-center">
                                    <label for="" class="col-sm-4">Fluid(ml/kg):</label>
                                    <div class="col-sm-8">
                                        <input type="number" step="any" min="0" value="" name="fldfluid" id="fldfluid" class="input-nutri form-control" placeholder="0">
                                    </div>
                                </div>
                                <div class="form-group form-row align-items-center">
                                    <label for="" class="col-sm-4">Sodium(mEq/kg):</label>
                                    <div class="col-sm-8">
                                        <input type="number" name="fldsodium" id="fldsodium" step="any" min="0" value="" class="input-nutri form-control" placeholder="0">
                                    </div>
                                </div>
                                <div class="form-group form-row align-items-center">
                                    <label for="" class="col-sm-4">Potassium(mEq/kg):</label>
                                    <div class="col-sm-8">
                                        <input type="number" name="fldpotassium" id="fldpotassium" step="any" min="0" value="" class="input-nutri form-control" placeholder="0">
                                    </div>
                                </div>
                                <div class="form-group form-row align-items-center">
                                    <label for="" class="col-sm-4">Vitamin(ml/kg):</label>
                                    <div class="col-sm-8">
                                        <input type="number" name="fldvitamin" id="fldvitamin" step="any" min="0" value="" class="input-nutri form-control" placeholder="0">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="form-group form-row align-items-center">
                                    <label for="" class="col-sm-4">Refrence:</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="fldreference" id="fldreference" value="" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <button type="button" class="btn btn-primary" id="addfoodrequirement"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add</button>
                                <button type="button" class="btn btn-primary"><i class="fa fa-edit"></i>&nbsp;&nbsp;Edit</button>
                                <button type="button" class="btn btn-primary"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete</button>
                                <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fas fa-play"></i></button>
                                <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fas fa-play fa-rotate-180"></i></button>
                            </div>
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

            $('#addfoodrequirement').click(function () {
                var fldagegroup = $('#fldagegroup').val();
                var fldptsex = $('#fldptsex').val();
                var fldfluid = $('#fldfluid').val();
                var fldprotein = $('#fldprotein').val();
                var fldlipid = $('#fldlipid').val();
                var flddextrose = $('#flddextrose').val();
                var fldnne = $('#fldnne').val();
                var fldsodium = $('#fldsodium').val();
                var fldpotassium = $('#fldpotassium').val();
                var fldvitamin = $('#fldvitamin').val();
                var fldreference = $('#fldreference').val();

                $.ajax({
                    'type': 'post',
                    'url': '{{ route('addnutrition') }}',
                    'data': {
                        '_token': '{{ csrf_token() }}',
                        'fldagegroup': fldagegroup,
                        'fldptsex': fldptsex,
                        'fldfluid': fldfluid,
                        'fldprotein': fldprotein,
                        'fldlipid': fldlipid,
                        'flddextrose': flddextrose,
                        'fldnne': fldnne,
                        'fldsodium': fldsodium,
                        'fldpotassium': fldpotassium,
                        'fldvitamin': fldvitamin,
                        'fldreference': fldreference
                    },
                    'success': function (res) {

                        showAlert(res);

                        $('#fldagegroup').val('');
                        $('#fldptsex').val('');
                        $('#fldfluid').val('');
                        $('#fldprotein').val('');
                        $('#fldlipid').val('');
                        $('#flddextrose').val('');
                        $('#fldnne').val('');
                        $('#fldsodium').val('');
                        $('#fldpotassium').val('');
                        $('#fldvitamin').val('');
                        $('#fldreference').val('');
                    }
                });
            })
        });
    </script>
@endpush
