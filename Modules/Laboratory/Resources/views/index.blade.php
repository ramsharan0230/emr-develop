@extends('backend.layouts.master')
@section('content')


<div class="row">
    <div class="col-lg-8">
        <div class="hpanel">
            <div class="panel-heading">
                <div class="panel-tools">
                    <!-- <a class="showhide"><i class="fa fa-chevron-up"></i></a>
                    <a class="closebox"><i class="fa fa-times"></i></a> -->
                </div>
                <a href="{{ route('admin.laboratory.create') }}">Add</a>
            </div>


            <div class="panel-body">
                <div class="table-responsive">
                    <table cellpadding="1" cellspacing="1" class="table">
                        <thead>
                            <tr>
                                <th>Test</th>
                                <th>Category</th>

                            </tr>
                        </thead>
                        <tbody>
                            @if($tests)
                            @foreach($tests as $t)
                            <tr>
                                <td><a href="{{ route('admin.laboratory.edit',$t->fldtestid) }}">{{ $t->fldtestid }}</a></td>
                                <td>{{ $t->fldcategory }}</td>

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
@endsection
