@extends('frontend.layouts.master')

@section('content')

<style>
.btn-group{
    position:unset;
}
.btn-group-vertical > .btn, .btn-group > .btn{
    position:unset;
}
.dropdown-menu.show{
    z-index:4;
}
.green_day{
    color:green !important;;
}
.yellow_day{
    color: #767600 !important;
}
.red_day{
    color: red !important;;
}


.treetable-indent
{
    position: relative;

    display: inline-block;

    width: 16px;
    height: 16px;
}
.treetable-expander
{
    position: relative;

    display: inline-block;

    width: 16px;
    height: 16px;

    cursor: pointer;
}

</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">
                            Top 10 Performing Departments
                        </h4>
                    </div>
                    <button onclick="myFunction()" class="btn btn-primary"><i class="fa fa-bars"></i></button>
                </div>
            </div>
        </div>

        <div class="col-sm-12" id="myDIV" style=" display: none;">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <form id="top_lab_test">
                        <div class="col-sm-3">
                            <div class="form-group form-row">
                                <label for="" class="col-sm-3">Form:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="from_date" id="from_date" value="{{isset($_GET['from_date']) ?$_GET['from_date']: $date }}"/>
                                    <input type="hidden" name="eng_from_date" id="eng_from_date">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group form-row">
                                <label for="" class="col-sm-3">To:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="to_date" id="to_date" value="{{isset($_GET['to_date']) ? $_GET['to_date']: $date }}"/>
                                    <input type="hidden" name="eng_to_date" id="eng_to_date">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group form-row">
                                <label for="" class="col-sm-3">Is IPD?:</label>
                                <div class="col-sm-9">
                                    <input type="checkbox"  name="encounter_type" id="encounter_type" value="1" >
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="d-flex justify-content-between">
                                <div>
                                    {{-- <button type="submit" class="btn btn-primary btn-action"> <i class="fa fa-filter"></i>&nbsp;Filter</button> --}}
                                    {{-- <a href="javascript:void(0);" type="button" btn-action onclick="pdfTopTenDeptReport()" class="btn btn-primary btn-action"><i class="fas fa-file-pdf"></i>&nbsp;pdf</a>
                                    <a href="javascript:void(0);" onclick="exportTopLabTestReport()" type="button" class="btn btn-primary btn-action"><i class="fa fa-code"></i>&nbsp;Export</a> --}}
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-action"> <i class="fa fa-filter"></i>&nbsp;Filter</button>
                                    <button type="button" class="btn btn-light btn-action" onclick="myFunction()">Cancel</button>
                                    <a href="{{route('toptendepartment')}}" type="button" class="btn btn-light btn-action" ><i class="fa fa-redo"></i>&nbsp;Reset</a>
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
                    <ul class="nav nav-tabs d-flex justify-content-between" id="myTab-two" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab-grid" data-toggle="tab" href="#grid" role="tab" aria-controls="home" aria-selected="true">Grid View</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" type="button" btn-action onclick="pdfTopTenDeptReport()" class="btn btn-primary btn-action pull-left" ><i class="fas fa-file-pdf"></i>&nbsp;pdf</a>
                            <a href="javascript:void(0);" onclick="exportTopLabTestReport()" type="button" class="btn btn-primary btn-action"><i class="fa fa-code"></i>&nbsp;Export</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent-1">
                        <div class="tab-pane fade show active" id="grid" role="tabpanel" aria-labelledby="home-tab-grid">
                            <div class="table-responsive table-sticky-th">

                                <table class="table table-bordered" id="myTable1" data-show-columns="true"
                                data-search="true"
                                data-show-toggle="true"
{{--                                data-pagination="true"--}}
                                data-resizable="true"
                                data-search-align="left"
                                >
                                    <thead>
                                    <tr>
                                        <th>SN</th>
                                        <th>Department Name</th>
                                        <th>Numbers of Consultation</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(!$top_ten_dept->isEmpty())
                                        <?php
                                        $count = ($top_ten_dept->currentPage() -1) * ($top_ten_dept->perPage() - 1 );
                                        $count = $count + 1;
                                        ?>
                                        @foreach ($top_ten_dept as $i=>$list)
                                        <tr>
                                            <td>{{ $count ++}}</td>
                                            <td>{{$list->name}} </td>
                                            <td>{{$list->test_count}} </td>
                                        </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                                @if(!$top_ten_dept->isEmpty())
                                <div class="float-right mt-1">
                                    {{$top_ten_dept->appends(request()->all())->links()}}
                                </div>
                                    @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- @include('reports::patient-credit-report.modal.preview') --}}
@endsection
@push('after-script')
<script>
    $('#eng_from_date').val(BS2AD($('#from_date').val()));
    $('#eng_to_date').val(BS2AD($('#to_date').val()));
    $('#from_date').nepaliDatePicker({
        npdMonth: true,
        npdYear: true,
        onChange: function () {
            $('#eng_from_date').val(BS2AD($('#from_date').val()));
        }
    });
    $('#to_date').nepaliDatePicker({
        npdMonth: true,
        npdYear: true,
        onChange: function () {
            $('#eng_to_date').val(BS2AD($('#to_date').val()));
        }
    });

    function pdfTopTenDeptReport() {
        var page = 1;
        if(findGetParameter('page')){
            page = findGetParameter('page');
        }
        var data = $("#top_lab_test").serialize();
        var urlReport = baseUrl + "/department/topTen/report?" + data + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}"+"&page="+page;

        window.open(urlReport, '_blank');
    }
    function exportTopLabTestReport() {
        var page = 1;
        if(findGetParameter('page')){
            page = findGetParameter('page');
        }
        var data = $("#top_lab_test").serialize();
        var urlReport = baseUrl + "/department/topTen/export?" + data + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}"+"&page="+page;
        window.open(urlReport, '_blank');
    }

    function findGetParameter(parameterName) {
        var result = null,
            tmp = [];
        location.search
            .substr(1)
            .split("&")
            .forEach(function (item) {
                tmp = item.split("=");
                if (tmp[0] === parameterName) result = decodeURIComponent(tmp[1]);
            });
        return result;
    }
    $(function() {
        if(findGetParameter('from_date'))
            $('#myDIV').show();
        if(findGetParameter('encounter_type'))
            $('#encounter_type').prop('checked',true);
        $('#myTable1').bootstrapTable()
    })
</script>
@endpush
