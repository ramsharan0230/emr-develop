@extends('frontend.layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row">
     <button onclick="myFunction()" class="btn btn-primary ml-3 mb-3"><i class="fa fa-bars"></i></button>
     <div class="col-sm-12" id="myDIV">
        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-header d-flex justify-content-between">
                <div class="iq-header-title">
                    <h4 class="card-title">Radio Grouping</h4>
                </div>
            </div>
            <div class="iq-card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group form-row er-input">
                            <div class="col-md-4">
                            @php $billingsets = \App\Utils\Groupinghelpers::getBillingSet(); @endphp
                            <select id="fldgroupselect" class="form-control form-control select2" >
                                <option value="">--select type--</option>
                                @forelse($billingsets as $billingset)
                                <option value="{{ $billingset->fldsetname }}">{{ $billingset->fldsetname }}</option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                            &nbsp;
                            <div class="col-md-5">
                            <select id="fldgroupnameselect" class="form-control form-control select2">
                                <option value="">--select group --</option>
                            </select>
                        </div>
                            &nbsp;
                            <a href="{{ route('radiology.grouping.exportradiogrouptopdf') }}" target="_blank" class="btn btn-primary btn-sm-in" id="exportradiogrouping" title="export Lab Tests Grouping"><i class="fa fa-external-link-square-alt"></i></a>
                        </div>
                        <h5 class="card-title">Components</h5>
                        <div class="form-group form-row er-input">
                            <div class="col-md-4">
                                <select id="datatypeselect" class="form-control select2">
                                    <option value="">--select test type --</option>
                                    <option value="Qualitative">Qualitative</option>
                                    <option value="Quantitative">Quantitative</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <select id="examidselect" class="form-input-food-mix form-control select2">
                                    <option value="">-- select test --</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select id="genderselect" class="form-control select2">
                                    <option value="">-- select gender --</option>
                                    <option value="Both Sex">Both Sex</option>
                                    <option value="Female">Female</option>
                                    <option value="Male">Male</option>
                                </select>
                            </div>
                    </div>
                    <h5 class="card-title">Test Method</h5>
                    <div class="form-group form-row er-input">
                        <input type="text" name="testmethod" id="testmethod" class="form-control col-4" placeholder="Regular" value="Regular"/>
                        <div class="col-md-5">
                            <select id="testmethodselect" class="form-control select2">
                                <option selected="" disabled="">Regular</option>
                            </select>

                        </div>
                        <a href="javascript:void(0)" class="btn btn-primary btn-sm-in btn-sm-in" id="addradiogroup" style="width: 85px;"><i class="ri-add-fill"></i> Add</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-sm-12">
   <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
    <div class="iq-card-body">
        <div class="res-table table-sticky-th mt-3">
         <table class="table table-hovered table-bordered table-striped dietary-table" id="testgroups">
            <thead class="thead-light">
                <tr>
                    <th></th>
                    <th>TestName</th>
                    <th>Type</th>
                    <th>Method</th>
                    <th>Gender</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
</div>
</div>
</div>
</div>
@include('laboratory::layouts.variableModal')

@stop

@push('after-script')
<script src="{{ asset('js/print.js') }}"> </script>
<script>
    $(function () {
        $('#fldgroupselect').change(function () {
            var fldgroup = $(this).val();
                // alert(fldgroup)
                $.ajax({
                    type: 'post',
                    url: '{{ route('radiology.grouping.selectservicecost') }}',
                    dataType: 'json',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'fldgroup': fldgroup,
                    },
                    success: function (res) {
                        if (res.message == 'success') {
                            $('#fldgroupnameselect').html(res.html);
                        } else if (res.message == 'error') {
                            showAlert(res.messagedetail);
                        }
                    }
                });
            });

        $()

        $('#datatypeselect').change(function () {
            var fldtype = $(this).val();
            $.ajax({
                type: 'post',
                url: '{{ route('radiology.grouping.examidselect') }}',
                dataType: 'json',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'fldtype': fldtype,
                },
                success: function (res) {
                    if (res.message == 'success') {
                        $('#examidselect').html(res.html);
                    } else if (res.message == 'error') {
                        showAlert(res.messagedetail);
                    }
                }
            });
        });

        $('#examidselect').change(function () {
            var fldexamid = $(this).val();
            $.ajax({
                type: 'post',
                url: '{{ route('radiology.grouping.testmethod') }}',
                dataType: 'json',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'fldexamid': fldexamid,
                },
                success: function (res) {
                    if (res.message == 'success') {
                        $('#testmethodselect').html(res.html);
                    } else if (res.message == 'error') {
                        showAlert(res.messagedetail);
                    }
                }
            });
        });

        $('#addradiogroup').click(function () {
            var fldgroupname = $('#fldgroupnameselect').val();
            var fldtesttype = $('#datatypeselect').val();
            var fldtestid = $('#examidselect').val();
            var fldptsex = $('#genderselect').val();
            var fldactive = $('#testmethod').val();
            if (fldgroupname != '') {
                if (fldtesttype != '') {
                    if (fldtestid != '') {
                        if (fldptsex != '') {
                            $.ajax({
                                type: 'post',
                                url: '{{ route('radiology.grouping.addradiogroup') }}',
                                dataType: 'json',
                                data: {
                                    '_token': '{{ csrf_token() }}',
                                    'fldgroupname': fldgroupname,
                                    'fldtesttype': fldtesttype,
                                    'fldtestid': fldtestid,
                                    'fldptsex': fldptsex,
                                    'fldactive': fldactive
                                },
                                success: function (res) {
                                    showAlert(res.message);
                                    if (res.message == 'success') {
                                        $('#testgroups').html(res.html);
                                        $('#datatypeselect').val('');
                                        $('#examidselect').val('');
                                        $('#genderselect').val('');

                                    } else if (res.message == 'error') {
                                        showAlert(res.messagedetail);
                                    }
                                }
                            });
                        } else {
                            alert('select gender');
                            return false;
                        }

                    } else {
                        alert('select test');
                        return false;
                    }
                } else {
                    alert('please select test type');
                    return false;
                }
            } else {
                alert('select group');
                return false;
            }
        });

        $('#testgroups').on('click', '.deleteradiogroup', function () {
            var really = confirm("You really want to delete this group?");
            var href = $(this).data('href');
            if (!really) {
                return false
            } else {
                $.ajax({
                    type: 'delete',
                    url: href,
                    dataType: 'json',
                    data: {
                        '_token': '{{ csrf_token() }}',
                    },
                    success: function (res) {
                        if (res.message == 'success') {
                            showAlert('Group deleted successfully.');
                            $(this).parent().parent().remove();
                            $("#testgroups").find(`[data-href='${href}']`).parent().parent().remove();
                        } else if (res.message == 'error') {
                            showAlert(res.error);
                        }
                    }
                });
            }
        });

        $('#fldgroupnameselect').click(function () {
            var fldgroupname = $(this).val();

            if (fldgroupname != '') {
                $.ajax({
                    type: 'post',
                    url: '{{ route('radiology.grouping.loadtestongroupchange') }}',
                    dataType: 'json',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'fldgroupname': fldgroupname
                    },
                    success: function (res) {
                        if (res.message == 'success') {
                            $('#testgroups').html(res.html);
                        } else if (res.message == 'error') {
                            showAlert(res.messagedetail);
                        }
                    }
                });
            }
        });

        $('#testmethodselect').change(function () {
            var testmethod = $(this).val();
            $('#testmethod').val(testmethod);
        });
    });
</script>
@endpush
