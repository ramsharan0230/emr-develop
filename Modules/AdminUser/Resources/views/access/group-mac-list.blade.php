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
                    <div name="newmac-concept">
                        <div class="mt-3">
                            <div class="panel">

                                <div class="panel-body">
                                    <div class="textright searchWrapperPosition">

                                    </div>
                                    <div class="table-adminMgmtTable top-req">
                                        <form name="access" method="POST" action="{{route('change.mac.access')}}">
                                            <table class="table  table-bordered table-adminMgmtTableList">
                                                <thead>
                                                <tr>
                                                    <th class="tittle-th">S.N</th>

                                                    <th class="tittle-th">Username</th>
                                                    <th class="tittle-th">Category</th>

                                                </tr>
                                                </thead>

                                                <tbody>
                                                @csrf
                                                <input type="hidden" name="group_id" value="{{$group_id}}">
                                                @if( count($groupmac) > 0 )
                                                    <?php $i = 1; ?>
                                                    @foreach($groupmac as $grp)
                                                        <tr>

                                                            <td align="center">
                                                                {{ $i++ }}
                                                                <input type="checkbox" name="useraccess[]" value="{{$grp->requestid}}"/>
                                                            </td>
                                                            <td>{{ $grp->request?$grp->request->flduserid:'' }}</td>
                                                            <td>{{ $grp->request?$grp->request->category:'' }}</td>

                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td align="center" colspan="10">
                                                            User record not found. &nbsp;
                                                        </td>
                                                    </tr>
                                                @endif
                                                <input type="submit" value="Remove from Group">

                                                </tbody>
                                            </table>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 mt-3">
                            <div class="panel">

                                <div class="panel-body">
                                    <div class="textright searchWrapperPosition">

                                    </div>
                                    <div class="table-adminMgmtTable top-req">
                                        <table class="table  table-bordered table-adminMgmtTableList">
                                            <thead>
                                            <tr>
                                                <th class="tittle-th">S.N</th>
                                                <th class="tittle-th">Name</th>
                                                <th class="tittle-th">Category</th>
                                                <th class="text-center tittle-th">Status</th>

                                            </tr>
                                            </thead>

                                            <tbody>
                                            <form name="access" method="POST" action="{{route('add.mac.to.group.access')}}">

                                                @csrf
                                                <input type="hidden" name="group_id" value="{{$group_id}}">
                                                @if( count($mac) > 0 )
                                                    <?php $i = 1; ?>
                                                    @foreach($mac as $ur)
                                                        <tr>

                                                            <td align="center">{{ $i++ }}
                                                                <input type="checkbox" name="requseraccess[]" value="{{$ur->id}}"/>
                                                            </td>
{{--                                                            <td>{{ $ur->hostmac }}</td>--}}
                                                            <td>{{ $ur->flduserid }}</td>
                                                            <td>{{ $ur->category }}</td>
                                                            <td style="text-align: center;">
                                                                {!! strtolower($ur->status) == 'active' ? '<strong style="color:green;">Active</strong>' : '<strong
                                                                    style="color:#bf302f;">Inactive</strong>' !!}
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
                                                <input type="submit" value="Add to Group">
                                            </form>
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
    </section>
@stop
