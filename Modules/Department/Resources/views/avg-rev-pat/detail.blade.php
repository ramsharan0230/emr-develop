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
                            Revenue average per encounter {{$encounter_id}}
                        </h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <div class="tab-content" id="myTabContent-1">
                        <div class="tab-pane fade show active" id="grid" role="tabpanel" aria-labelledby="home-tab-grid">
                            <div class="table-responsive table-sticky-th">

                                <table class="table table-bordered" id="myTable1" data-show-columns="true"
                                data-search="true"
                                data-show-toggle="true"
                                data-pagination="true"
                                data-resizable="true"
                                data-search-align="left"
                                >
                                    <thead>
                                    <tr>
                                        <th>SN</th>
                                        <th>Department Name</th>
                                        <th>Revenue Amount</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(!$avg_rev_person->isEmpty())
                                        <?php
                                        $count = 1;
                                        ?>
                                        @foreach ($avg_rev_person as $list)
                                        <tr>
                                            <td>{{$count++}}</td>
                                            <td>{{\App\HospitalDepartment::where('id',$list->hospital_department_id)->first()->name}} </td>
                                            <td>Rs. {{ \App\Utils\Helpers::numberFormat(($list->itemAmt))}} </td>
                                        </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
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
    $(function() {
        $('#myTable1').bootstrapTable()
    })
</script>
@endpush
