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
                            Patient Test Log Report
                        </h4>
                    </div>
                    <button onclick="myFunction()" class="btn btn-primary"><i class="fa fa-bars"></i></button>
                </div>
            </div>
        </div>

        <div class="col-sm-12" id="myDIV">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <form method="GET" id="patient_wise_log_report_test">
                        <div class="col-sm-3">
                            <div class="form-group form-row">
                                <label for="" class="col-sm-3">From:</label>
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
                                <label for="" class="col-sm-3">Patient ID:</label>
                                <div class="col-sm-9">
                                    <input type="text" name="patient_id" class="form-control" placeholder="Patient ID" value="{{$patient_id ?? ''}}">
                                </div>
                            </div>
                        </div>

                        {{-- <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Patient ID</label>
                                    <input type="text" name="patient_id" class="form-control" placeholder="Patient ID" value="{{$patient_id ?? ''}}">
                                </div>
                            </div>
                        </div> --}}
                        {{-- <div class="row"> --}}
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <button type="submit" class="btn btn-primary btn-action"> <i class="fa fa-filter"></i>&nbsp;Filter</button>
                                        <a href="javascript:void(0);" onclick="patientTestLogPdfReport()" type="button" class="btn btn-primary btn-action"><i class="fas fa-file-pdf"></i>&nbsp;pdf</a>
                                        <a href="javascript:void(0);" onclick="patientTestLogExcelReport()" type="button" class="btn btn-primary btn-action"><i class="fa fa-code"></i>&nbsp;Export</a>
                                    </div>
                                    <div>
                                        <a href="{{route('patient.test.log.report')}}" type="button" class="btn btn-light btn-action" ><i class="fa fa-redo"></i>&nbsp;Reset</a>
                                        <button type="submit" class="btn btn-light btn-action mr-2" >Cancel</button>
                                    </div>                                    
                                </div>                                                                        
                            </div>
                        {{-- </div> --}}
                    </form>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <ul class="nav nav-tabs" id="myTab-two" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab-grid" data-toggle="tab" href="#grid" role="tab" aria-controls="home" aria-selected="true">Grid View</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent-1">
                        <div class="tab-pane fade show active" id="grid" role="tabpanel" aria-labelledby="home-tab-grid">
                            <div class="table-responsive res-table table-sticky-th">
                                

                                <table id="tree-table" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>S.N</th>
                                        <th>Patient Id</th>
                                        <th>Encounter Id</th>
                                        <th>Name</th>
                                        <th>Sample Id</th>
                                        {{-- <th>fldptnamelast</th> --}}
                                        <th>Test Id</th>
                                        <th>Sample by</th>
                                        <th>Sample Time</th>
                                        <th>Reported By</th>
                                        <th>Reported Time</th>
                                        <th>Verified By</th>
                                        <th>Verified Time</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @if(!$records->isEmpty())
                                            <?php 
                                                $count = ($records->currentpage()-1)*$records->perpage()+1; 
                                            ?>
                                            @foreach ($records as $key => $list)
                                                <tr data-id="{{$list->fldpatientval}}" data-parent="0" data-level="1">
                                                    <td>{{$count++}}</td>
                                                    <td data-column="name">{{$list->fldpatientval??''}}</td>
                                                    <td>{{$list->fldencounterval??''}}</td>
                                                    <td>{{strtoupper($list->fldptnamefir)??''}} {{strtoupper($list->fldptnamelast)??''}}</td>
                                                    <td>{{$list->fldsampleid ?? ''}}</td>
                                                    <td>{{$list->fldtestid??''}}</td>
                                                    <td>{{$list->flduserid_sample??''}}</td>
                                                    <td>{{$list->fldtime_sample??''}}</td>
                                                    <td>{{$list->flduserid_report??''}}</td>
                                                    <td>{{$list->fldtime_report??''}}</td>
                                                    <td>{{$list->flduserid_verify??''}}</td>
                                                    <td>{{$list->fldtime_verify??''}}</td>
                                                </tr>
                                            @php
                                            $patient_wise_log=  \App\Utils\Helpers::patientTestLogReport($list->fldpatientval,$list->fldtestid);
                                            @endphp
                                                @if(!$patient_wise_log->isEmpty())
                                                    @foreach ($patient_wise_log as $pwg)
                                                        <tr style="background-color: #fafafa; border: 1px solid #" data-parent="{{$list->fldpatientval}}" data-level="2">
                                                            <!-- <td></td>  -->
                                                            <td colspan="2" style="text-align: right;" data-column="name">{{$pwg->fldpatientval??''}}</td>
                                                            <td>{{$pwg->fldencounterval??''}}</td>
                                                            <td>{{strtoupper($pwg->fldptnamefir)??''}} {{strtoupper($pwg->fldptnamelast)??''}}</td>
                                                            <td>{{$pwg->fldsampleid ?? ''}}</td>
                                                            <td>{{$pwg->fldtestid??''}}</td>
                                                            <td>{{$pwg->flduserid_sample??''}}</td>
                                                            <td>{{$pwg->fldtime_sample??''}}</td>
                                                            <td>{{$pwg->flduserid_report??''}}</td>
                                                            <td>{{$pwg->fldtime_report??''}}</td>
                                                            <td>{{$pwg->flduserid_verify??''}}</td>
                                                            <td>{{$pwg->fldtime_verify??''}}</td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                                {!! $records
                                            ->appends(Request::only('from_date'))
                                            ->appends(Request::only('to_date'))
                                            ->appends(Request::only('eng_from_date'))
                                            ->appends(Request::only('eng_to_date'))
                                            ->appends(Request::only('patient_id'))
                                            ->links() 
                                !!}  
     
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('reports::patient-credit-report.modal.preview')
@endsection
@push('after-script')
<script src="{{asset('js/bootstrap-treefy.min.js')}}"></script>
<script>
    // $(function() {
    //     $("#table").treeFy({
    //         treeColumn: 1,
    //         initStatusClass: 'treetable-collapsed'
    //     });
    // });

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

        function patientTestLogPdfReport() {
            // alert(baseUrl)
            // alert('export');
            var data = $("#patient_wise_log_report_test").serialize();
            // alert(data);
            var urlReport = baseUrl + "/patient-test-log-pdf-report?" + data + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";

            window.open(urlReport, '_blank');
        }

        function patientTestLogExcelReport() {
            // alert(baseUrl)
            // alert('export');
            var data = $("#patient_wise_log_report_test").serialize();
            // alert(data);
            var urlReport = baseUrl + "/patient-test-log-export-report?" + data + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";

            window.open(urlReport, '_blank');
        }

        $(function () {
            var
                $table = $('#tree-table'),
                rows = $table.find('tr');

            rows.each(function (index, row) {
                var
                    $row = $(row),
                    level = $row.data('level'),
                    id = $row.data('id'),
                    $columnName = $row.find('td[data-column="name"]'),
                    children = $table.find('tr[data-parent="' + id + '"]');

                if (children.length) {
                    var expander = $columnName.prepend('' +
                        '<i class="treegrid-expander fas fa-angle-right"></i>' +
                        '');

                    children.hide();

                    expander.on('click', function (e) {
                        var $target = $(e.target);
                        if ($target.hasClass('fa-angle-right')) {
                            $target
                                .removeClass('fa-angle-right')
                                .addClass('fa-angle-down');

                            children.show();
                        } else {
                            $target
                                .removeClass('fa-angle-down')
                                .addClass('fa-angle-right');

                            reverseHide($table, $row);
                        }
                    });
                }

                $columnName.prepend('' +
                    '<span class="treegrid-indent" style="width:' + 15 * level + 'px"></span>' +
                    '');
            });

            // Reverse hide all elements
            reverseHide = function (table, element) {
                var
                    $element = $(element),
                    id = $element.data('id'),
                    children = table.find('tr[data-parent="' + id + '"]');

                if (children.length) {
                    children.each(function (i, e) {
                        reverseHide(table, e);
                    });

                    $element
                        .find('.fa-angle-down')
                        .removeClass('fa-angle-down')
                        .addClass('fa-angle-right');

                    children.hide();
                }
            };
        });

</script>
@endpush
