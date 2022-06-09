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
                                        <a href="{{ route('admin.user.mac.access.add', $comp) }}" class="btn btn-primary btn-sm float-right addBtnAdminPanel"><i
                                                class="fa fa-plus"></i> Add New</a>
                                    </div>
                                </div>
                                <div class="table-adminMgmtTable top-req">
                                    <table class="table  table-bordered table-adminMgmtTableList">
                                        <thead>
                                        <tr>
                                            <th class="tittle-th">S.N</th>
                                            <th class="tittle-th">Mac Address</th>
                                            <th class="tittle-th">Access Name</th>
                                            <th class="text-center tittle-th">Status</th>
                                            <th class="tittle-th">Operation</th>
                                        </tr>
                                        </thead>

                                        <tbody>

                                        @if( count($mac) > 0 )
                                            <?php $i = 1; ?>
                                            @foreach($mac as $ur)
                                                <tr>
                                                    <td align="center">{{ $i++ }} </td>

                                                    <td>{{ $ur->fldhostmac }}</td>
                                                    <td>{{ $ur->fldcomp }}</td>
                                                    <td style="text-align: center;">
                                                        {!! strtolower($ur->fldaccess) == 'active' ? '<strong style="color:green;">Active</strong>' : '<strong
                                                            style="color:#bf302f;">Inactive</strong>' !!}
                                                    </td>
                                                    <td width="150" style="text-align: center;">
                                                        <a href="{{ route('admin.user.mac.access.edit',[$ur->id]) }}"
                                                           class="btn btn-sm btn-info adminMgmtTableBtn" title="Edit Access Computer"><i
                                                                class="fa fa-edit"></i></a>

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
