@extends('queue.layouts.master')

@section('content')

<div id="wrapper">
<div id="logo" class="light-version">
        <a href="{{ route('consultants') }}">
            <span>{{ config('constants.hospital_name') }}</span>
        </a>

    </div>

    <div class="content">


        <div class="row">
        @if($sound == 'on')
            <audio src="{{ asset('announcement.mp3')}}" id="my_audio" autoplay="autoplay"></audio>
            @endif
            <div class="col-lg-12">
                <div class="hpanel">
                    {{-- <div class="panel-heading">
                            Consultant
                        </div>--}}
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table cellpadding="1" cellspacing="1" class="table table-bordered">
                                <thead>
                                    <tr style="background: #ca2027;color: #ffffff;">
                                        <th class="text-center">S.N</th>

                                        <th>Encounter Id</th>
                                        <th>Name</th>
                                        <th>Department</th>
                                        <th>Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($lists->count() > 0)
                                    @foreach($lists as $k => $l)
                                    <tr class="{{ $k < config('constants.max_active_records') ? 'active_tr' : '' }}">
                                        <td class="text-center">{{ $loop->iteration }}</td>

                                        <td>{{ $l->fldencounterval }}</td>
                                        <td>{{ Options::get('system_patient_rank')  == 1 && (isset($l)) && (isset($l->fldrank) ) ? $l->fldrank:''}} {{ $l->fldptnamefir }} {{ $l->fldmidname }} {{ $l->fldptnamelast }}</td>
                                        <td>{{ $l->fldcomp_order }}</td>
                                        <td>{{ $l->fldtime_order }}</td>
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

    <!-- Right sidebar -->
    <div id="right-sidebar" class="animated fadeInRight">
        <div class="p-m">
           
                <button id="sidebar-close" class="right-sidebar-toggle sidebar-button btn btn-default m-b-md">
                    <i class="fa fa-times"></i>
                </button>
            
            <div>
                <span class="font-bold no-margins"> All Departments </span>
            </div>
            <div class="row">
                <form method="get" class="form-horizontal">

                    <div class="hr-line-dashed"></div>

                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-1">
                            @if($departments)
                            @foreach($departments as $depart)
                            <div><label> <input type="checkbox" name="all_departments[]" class="i-checks" @if(in_array($depart->fldcomp,$fldcomp_order)) checked @endif value="{{ $depart->fldcomp }}"> {{ $depart->fldcomp }} </label></div>
                            @endforeach
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-1">
                            <button class="btn btn-primary" type="submit">Filter</button>
                        </div>
                    </div>
                </form>
            </div>
            <input type='hidden' id="sound" value="{{ $sound }}" />
            <input type='hidden' id="url" value="{{ route('pharmacy') }}" />
        </div>
    </div>

</div>
<script type="text/javascript">
    $(document).ready(function() {
        if ($('#sound').val() === 'on') {
            document.getElementById("my_audio").play();
        }

        var url = $('#url').val();


        setInterval(function() {

            location.reload(true);

        }, 15000);


    });
</script>
@stop