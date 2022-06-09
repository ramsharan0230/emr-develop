@extends('frontend.layouts.master')
@section('content')

    <section class="cogent-nav">
        {{--navbar--}}
        @include('adminuser::common.nav-tab')
        {{--end navbar--}}
        <div class="patient-profile">
            <div class="container">

                <div class="adminMgmtPageContent">
                    @if(Session::get('success_message'))
                        <div class="alert alert-success containerAlert">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span
                                    class="sr-only">Close</span></button>
                            {{ Session::get('success_message') }}
                        </div>
                    @endif

                    @if(Session::get('success_message_special'))
                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span
                                    class="sr-only">Close</span></button>
                            {!! Session::get('success_message_special') !!}
                        </div>
                    @endif

                    @if(Session::get('error_message'))
                        <div class="alert alert-danger containerAlert">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span
                                    class="sr-only">Close</span></button>
                            {{ Session::get('error_message') }}
                        </div>
                    @endif
                    <div class="mt-1">
                        <div class="panel">

                            <div class="panel-body">
                                <div class="textright searchWrapperPosition">
                                    <div class="pageHeading">
                                        <a href="{{ route('admin.user.comp.access.add') }}" class="btn btn-primary btn-sm float-right addBtnAdminPanel"><i
                                                class="fa fa-plus"></i> Add New</a>
                                    </div>
                                </div>
                                <div class="table-adminMgmtTable top-req">
                                    <table class="table table-bordered table-adminMgmtTable3">
                                        <thead>
                                        <tr>
                                            <th class="tittle-th">S.N</th>
                                            <th class="tittle-th">Access Name</th>
                                            <th class="tittle-th">Description</th>
                                            <th class="text-center tittle-th">Status</th>
                                            <th class="tittle-th">Operation</th>
                                        </tr>
                                        </thead>

                                        <tbody>

                                        @if( count($compAccess) > 0 )
                                            <?php $i = 1; ?>
                                            @foreach($compAccess as $ur)
                                                <tr>
                                                    <td align="center">{{ $i++ }} </td>

                                                    <td>{{ $ur->name }}</td>
                                                    <td>{{ $ur->description }}</td>
                                                    <td style="text-align: center;">
                                                        {!! $ur->status == 'active' ? '<strong style="color:green;">Active</strong>' : '<strong
                                                            style="color:#bf302f;">Inactive</strong>' !!}
                                                    </td>
                                                    <td width="150" style="text-align: center;">
                                                        <a href="{{ route('admin.user.comp.access.edit',[$ur->id]) }}"
                                                           class="btn btn-sm btn-info adminMgmtTableBtn" title="Edit Access Computer"><i
                                                                class="fa fa-edit"></i></a>

                                                        <a href="{{ route('admin.user.comp.access.list.mac',[$ur->name]) }}"
                                                           class="btn btn-sm btn-success adminMgmtTableBtn" title="Add Mac"><i
                                                                class="fa fa-plus"></i></a>

                                                        @if ( isset( $ur->user_is_superadmin ) && $ur->user_is_superadmin->count() == 0 )
                                                            <a href="{{ route('admin.user.destroy',[$ur->id]) }}" class="btn btn-danger"
                                                               data-toggle="confirmation"><i class="fa fa-trash"></i></a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td align="center" colspan="10">
                                                    User record not found. &nbsp;
                                                </td>
                                            </tr>
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
    </section>

@stop
