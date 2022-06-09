@extends('queue.layouts.master')
 <link rel="stylesheet" href="{{ asset('queue/styles/style.css') }}">

@section('content')

<div id="wrapper">
<div id="logo" class="light-version">
        <a href="{{ route('consultants') }}">
            <span>{{ config('constants.hospital_name') }}</span>
        </a>

    </div>
 <style type="text/css">
    #right-sidebar-content {
        background-color: #fff;
        border-left: 1px solid #eaeaea;
        position: fixed;
        top: 0;
        width: 260px!important;
        z-index: 1009;
        bottom: 0;
        right: 0;
        display: none;
        overflow: auto
    }
    #right-sidebar-content .sidebar-open {
    display: block
}
    </style>

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
                                        <th>Room</th>
                                        <th>Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($lists->count() > 0)
                                    @foreach($lists as $k => $l)
                                    <!-- <tr class="{{ $k < config('constants.max_active_records') ? 'active_tr' : '' }}"> -->
                                    <tr class="{{ $l->patfldinside == 1 ? 'active_tr' : '' }}">
                                        <td class="text-center">{{ $loop->iteration }}</td>

                                        <td>{{ $l->fldencounterval }}</td>
                                        <td>   {{ ($l->encounter) ? $l->encounter->fldrank : '' }} {{ ($l->encounter && $l->encounter->patientInfo) ? $l->encounter->patientInfo->fldfullname : '' }} <br></td>
                                        <td>{{ ($l->encounter) ? $l->encounter->department : '' }}</td>
                                        <td>{{ $l->fldroomno }}</td>
                                        <?php $date = date('Y-m-d', strtotime($l->fldordtime)); ?>
                                        <td> <?php $dateNep =  Helpers::dateEngToNep_queue($date); echo $dateNep->year.'-'.$dateNep->month.'-'.$dateNep->date.' '.date('H:i:s', strtotime($l->fldordtime))  ?></td>


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
    <div id="right-sidebar-content" class="animated fadeInRight">
            <div class="p-m">

                <button id="sidebar-close" class="right-sidebar-toggle sidebar-button btn btn-default m-b-md">
                    <i class="fa fa-times"></i>
                </button>
                <div>

                    <!-- <span class="font-bold no-margins"> All Departments </span> -->
                    <span class="font-bold no-margins"> Category </span>
                </div>
                <form method="get" class="form-horizontal">

                    <div class="hr-line-dashed"></div>

                    <div class="row">
                        <!-- <div class="col-md-7">
                        @if($departments)
                        @foreach($departments as $depart)
                        <div><label> <input type="checkbox" name="all_departments[]" class="i-checks" @if(in_array($depart->fldadmitlocat,$fldadmitlocat)) checked @endif value="{{ $depart->fldadmitlocat }}"> {{ $depart->fldadmitlocat }} </label></div>
                        @endforeach
                        @endif
                        </div>
                        <div class="col-md-5" style="padding: 0">
                        @if($targets)
                            @foreach($targets as $target)
                            <div><label> <input type="checkbox" name="all_targets[]" class="i-checks" @if(in_array($target->fldtarget,$fldtarget)) checked @endif value="{{ $target->fldtarget }}"> {{ $target->fldtarget }} </label></div>
                            @endforeach
                            @endif
                        </div> -->

                        <div class="col-md-5" style="padding: 0">
                        @if($radiotype)
                            @foreach($radiotype as $radioty)
                            <div><label> <input type="checkbox" name="all_radiocategory[]" class="i-checks" @if(in_array($radioty->fldcategory,$fldcategory)) checked @endif value="{{ $radioty->fldcategory }}"> {{ $radioty->fldcategory }} </label></div>
                            @endforeach
                            @endif
                        </div>

                        <div class="col-md-6">
                      
                            <label> Room no. </label> 
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="fldroomno" class="i-checks" value="{{$fldroomno}}"> 
                        </div>
                        

                    </div>
                  <div class="form-group">
                        <div class="col-sm-10">
                            <button class="btn btn-primary" type="submit">Filter</button>
                        </div>
                    </div>
                </form>

                <input type='hidden' id="sound" value="{{ $sound }}"/>
                <input type='hidden' id="url" value="{{ route('queue.new.radiology') }}"/>

            </div>
        </div>

</div>
   <script src="{{asset('assets/js/jquery-3.4.1.min.js')}}"></script>
    <script type="text/javascript">
        $('.right-sidebar-toggle').click(function() {
            $('#right-sidebar-content').toggle();
        });
        $(document).ready(function() {
        setInterval(function() {

            location.reload(true);

            }, 120000);
        });
    </script>
<!-- <script type="text/javascript">
   $(document).ready(function() {
        if ($('#sound').val() === 'on') {
            document.getElementById("my_audio").play();
        }

        var url = $('#url').val();


        setInterval(function() {

            location.reload(true);

        }, 10000);


    });
</script> -->
@stop
