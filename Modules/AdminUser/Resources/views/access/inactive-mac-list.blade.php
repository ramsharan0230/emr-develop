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
                    <div class="mt-3">
                        <div class="panel">

                            <div class="panel-body">
                                <div class="textright searchWrapperPosition">

                                </div>
                                <div class="table-adminMgmtTable top-req">
                                    <form name="access" method="POST" action="{{route('approve.mac.access')}}">
                                        <table class="table  table-bordered table-adminMgmtTableList">
                                            <thead>
                                            <tr>
                                                <th class="tittle-th">S.N</th>
                                                <th class="tittle-th">Mac Address</th>
                                                <th class="tittle-th">Name</th>
                                                <th class="tittle-th">Category</th>
                                                <th class="text-center tittle-th">Status</th>

                                            </tr>
                                            </thead>

                                            <tbody>

                                            @csrf
                                            @if( count($mac) > 0 )
                                                <?php $i = 1; ?>
                                                @foreach($mac as $ur)
                                                    <tr>

                                                        <td align="center">{{ $i++ }} <input type="checkbox" name="useraccess[]" value="{{$ur->id}}"/></td>

                                                        <td>{{ $ur->hostmac }}</td>
                                                        <td>{{$ur->flduserid}}</td>
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

                                            </tbody>
                                        </table>
                                        <input type="submit" value="Approve">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
