@extends('queue.layouts.master')

@section('content')

    <div id="wrapper">
        <div id="logo" class="light-version">
            <a href="{{ route('consultants') }}">
                <span>{{ config('constants.hospital_name') }}</span>
            </a>

        </div>
        <style>

            .btn_right {
                text-align: right;
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
                                        <th>Time</th>
                                    </tr>
                                    </thead>
                                    <tbody id="pathology-laboratory-table-list">
                                    @if($lists->count() > 0)
                                        @foreach($lists as $k => $l)
                                            <tr class="{{ $l->fldinside == 1 ? 'active_tr' : '' }}">
                                                <td class="text-center">{{ $loop->iteration }}</td>

                                                <td>{{ $l->fldencounterval }}</td>
                                                <td>{{ $l->encounter && $l->encounter->patientInfo?$l->encounter->patientInfo->fullname:"" }}</td>
                                                <td>{{ $l->department }}</td>
                                                <?php $date = date('Y-m-d', strtotime($l->fldordtime)); ?>
                                                <td><?php $dateNep = Helpers::dateEngToNep_queue($date); echo $dateNep->year . '-' . $dateNep->month . '-' . $dateNep->date . ' ' . date('H:i:s', strtotime($l->fldordtime))  ?></td>

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
                <div class="btn_right">
                    <button id="sidebar-close" class="right-sidebar-toggle sidebar-button btn btn-default m-b-md">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
                <div class="row">
                    <div class="col-md-7">
                        <span class="font-bold no-margins"> All Departments </span>
                    </div>
                    <div class="col-md-5">
                        <span class="font-bold no-margins"> All Targets </span>
                    </div>

                </div>
                <div class="">
                    <form method="get" class="form-horizontal">

                        <div class="hr-line-dashed"></div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    @if($departments)
                                        @foreach($departments as $depart)
                                            <div><label> <input type="checkbox" name="all_departments[]" class="i-checks" @if(in_array($depart->fldadmitlocat,$fldadmitlocat)) checked @endif value="{{ $depart->fldadmitlocat }}"> {{ $depart->fldadmitlocat }} </label></div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    @if($targets)
                                        @foreach($targets as $target)
                                            <div><label> <input type="checkbox" name="all_targets[]" class="i-checks" @if(in_array($target->fldtarget,$fldtarget)) checked @endif value="{{ $target->fldtarget }}"> {{ $target->fldtarget }} </label></div>
                                        @endforeach
                                    @endif
                                </div>

                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-10">
                                <button class="btn btn-primary" type="submit">Filter</button>
                            </div>
                        </div>
                    </form>
                </div>
                <input type='hidden' id="sound" value="{{ $sound }}"/>
                <input type='hidden' id="url" value="{{ route('queue.laboratory') }}"/>
            </div>
        </div>

    </div>
    <script src="{{asset('assets/js/jquery-3.4.1.min.js')}}"></script>
    <script type="text/javascript">
    
   
      
        $(document).ready(function () {
            $('.right-sidebar-toggle').click(function() {
                $('#right-sidebar-content').toggle();
            });
   
            if ($('#sound').val() === 'on') {
                document.getElementById("my_audio").play();
            }

            setInterval(function() {

                location.reload(true);

                }, 120000);

            var currentRequest = null;
            function ajaxCall(){
                currentRequest = $.ajax({
                    url: '{{ route('queue.new.laboratory.get.data') }}?' + window.location.search.substring(1),
                    type: "GET",
                    beforeSend: function () {
                        if (currentRequest != null) {
                            currentRequest.abort();
                        }
                    },
                    success: function (response) {
                        // console.log(response)
                        setTimeout(function(){ ajaxCall(); }, 3000);
                        $('#pathology-laboratory-table-list').empty().append(response);
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });
            }

            setTimeout(function(){ ajaxCall(); }, 3000);
        });

    </script>
    


@stop

