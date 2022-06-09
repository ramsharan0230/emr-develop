@extends('frontend.layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <button onclick="myFunction()" class="btn btn-primary ml-3 mb-3"><i class="fa fa-bars"></i></button>
        <div class="col-sm-12" id="myDIV">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">Test Grouping</h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group form-row er-input">
                                @php $billingsets = \App\Utils\Groupinghelpers::getBillingSet(); @endphp
                                <select id="fldgroupselectlab" class="form-control col-12">
                                    <option value="">--select type--</option>
                                    @forelse($billingsets as $billingset)
                                    <option value="{{ $billingset->fldsetname }}">{{ $billingset->fldsetname }}</option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group form-row">
                                <div class="col-sm-10">
                                    <select id="fldgroupnameselectlab" class="form-control select2">
                                        <option value="">--select group --</option>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <a href="{{ route('technologylab.grouping.exportlabgrouptopdf') }}" target="_blank" class="btn btn-primary" id="exportlabgrouping" title="export Radiology Tests Grouping"><i class="fa fa-external-link-square-alt"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <h5 class="card-title mb-1">Components</h5>
                            <div class="form-group form-row er-input">
                                <div class="col-sm-6">
                                    <select id="datatypeselectlab" class="form-control">
                                        <option value="">--select test type --</option>
                                        <option value="Qualitative">Qualitative</option>
                                        <option value="Quantitative">Quantitative</option>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <select id="examidselectlab" class="form-control select2" style="width: 100%;">
                                        <option value="">-- select test --</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <h5 class="card-title mb-1">Test Method</h5>
                            <div class="form-group form-row er-input">
                                <input type="text" name="testmethod" id="testmethodlab" class="form-control col-4" value="Regular"/> &nbsp;

                                <select id="testmethodlabselect" class="form-control col-5" style="width: 100%;">
                                    <option value="Regular">Regular</option>
                                </select>&nbsp;
                                <a href="javascript:void(0)" class="btn btn-primary col-2" id="addtestgroup" style="width: 85px;"><i class="ri-add-fill"></i> Add</a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
           <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-body">
                <div class="table-responsive table-container mt-3">
                    <table class="table table-hovered table-bordered table-striped " id="labtestgroup">
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
                        <tbody id="tablebody"></tbody>
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

    $(document).ready(function () {
        $('#fldgroupselectlab').change(function () {
            var fldgroup = $(this).val();
            console.log(fldgroup);
            $.ajax({
                type: 'post',
                url: '{{ route('technologylab.grouping.selectservicecost') }}',
                dataType: 'json',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'fldgroup': fldgroup,
                },
                success: function (res) {
                    if (res.message == 'success') {
                        $('#fldgroupnameselectlab').html(res.html);
                    } else if (res.message == 'error') {
                        showAlert(res.messagedetail);
                    }
                }
            });
        });

        $(document).on('change','#datatypeselectlab',function () {
            var fldtype = $(this).val();
            $.ajax({
                type: 'post',
                url: '{{ route('technologylab.grouping.examidselect') }}',
                dataType: 'json',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'fldtype': fldtype
                },
                success: function (res) {
                    if (res.message == 'success') {
                        $('#examidselectlab').html(res.html);
                    } else if (res.message == 'error') {
                        showAlert(res.messagedetail);
                    }
                }
            });
        });

        $(document).on('change','#examidselectlab',function () {
            var fldexamid = $(this).val();
            $.ajax({
                type: 'post',
                url: '{{ route('technologylab.grouping.testmethod') }}',
                dataType: 'json',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'fldexamid': fldexamid,
                },
                success: function (res) {
                    if (res.message == 'success') {
                        $('#testmethodlabselect').html(res.html);
                    } else if (res.message == 'error') {
                        showAlert(res.messagedetail);
                    }
                }
            });
        });

        $(document).on('click','#addtestgroup',function () {
            var fldgroupname = $('#fldgroupnameselectlab').val();
            var fldtesttype = $('#datatypeselectlab').val();
            var fldtestid = $('#examidselectlab').val();
            var fldactive = $('#testmethodlab').val();
            if (fldgroupname != '') {
                if (fldtesttype != '') {
                    if (fldtestid != '') {
                        $.ajax({
                            type: 'post',
                            url: '{{ route('technologylab.grouping.addtestgroup') }}',
                            dataType: 'json',
                            data: {
                                '_token': '{{ csrf_token() }}',
                                'fldgroupname': fldgroupname,
                                'fldtesttype': fldtesttype,
                                'fldtestid': fldtestid,
                                'fldactive': fldactive
                            },
                            success: function (res) {
                                showAlert(res.message);
                                if (res.message == 'success') {
                                    $('#tablebody').html(res.html);
                                    $('#datatypeselectlab').val('');
                                    $('#examidselectlab').val('');

                                } else if (res.message == 'error') {
                                    showAlert(res.messagedetail);
                                }
                            }
                        });
                    } else {
                        alert('select test');
                    }
                } else {
                    alert('please select test type')
                }
            } else {
                alert('select group');
            }
        });

        $('#tablebody').on('click', '.deletetestgroup', function () {
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
                            $("#tablebody").find(`[data-href='${href}']`).parent().parent().remove();
                        } else if (res.message == 'error') {
                            showAlert(res.error);
                        }
                    }
                });
            }
        });

        $(document).on('change','#fldgroupnameselectlab',function () {
            var fldgroupname = $(this).val();

            if (fldgroupname != '') {
                $.ajax({
                    type: 'post',
                    url: '{{ route('technologylab.grouping.loadtestongroupchange') }}',
                    dataType: 'json',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'fldgroupname': fldgroupname
                    },
                    success: function (res) {
                        if (res.message == 'success') {
                            $('#tablebody').html(res.html);
                        } else if (res.message == 'error') {
                            showAlert(res.messagedetail);
                        }
                    }
                });
            }
        });

        $(document).on('change','#testmethodlabselect').change(function () {
            var testmethod = $(this).val();
            $('#testmethodlab').val(testmethod);
        });
    });
</script>
@endpush
