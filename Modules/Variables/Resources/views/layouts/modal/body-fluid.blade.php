<link rel="stylesheet" href="{{ asset('assets/css/modal.css') }}">
<div class="modal fade" id="body_fluid" tabindex="-1" role="dialog" aria-labelledby="encounter_listLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="" id="encounter_listLabel">Variables</h6>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <input type="text" id="bodyfluidname" class="form-control">
                        </div>
                        <br><br>
                        <div class="col-md-12 col-sm-12">
                            <a href="javascript:void(0)" class="btn default-btn f-btn-icon-b pull-left" id="bodyfluidaddbutton" style="border: 1px solid #ced4da; width: 85px; margin-right: 240px;"><i class="fa fa-plus"></i>&nbsp;<u>A</u>dd</a>
                            <a href="javascript:void(0)" class="btn default-btn f-btn-icon-s pull-right" id="bodyfluiddeletebutton" style="border: 1px solid #ced4da; width: 105px;"><i class="fa fa-times"></i> &nbsp;<u>D</u>elete</a>
                            <input type="hidden" id="bodyfluidtobedeleteroute" value="">
                            <input type="hidden" id="bodyfluididtobedeleted" value="">
                        </div>
                        <br><br>
                        <div class="col-md-12 col-sm-12">
                            <div class="dietarytable overflow-auto" style="border: 1px solid #ced4da; height: 300px !important">
                                <table class="dietary-table">
                                    @php $bodyfluids = \App\Utils\Variablehelpers::getAllBodyfluids() @endphp
                                    <ul id="bodyfluidlistingmodal">
                                        @forelse($bodyfluids as $bodyfluid)
                                            <li class="bodyfluid-list" style="border: 1px solid #ced4da;"><a href="javascript:void(0)" class="bodyfluid_item" data-href="{{ route('variables.bodyfluid.delete', $bodyfluid->fldid) }}" data-id="{{ $bodyfluid->fldid }}">{{ $bodyfluid->fldfluid }}</a></li>
                                        @empty
                                        @endforelse
                                    </ul>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-sm-12 group__box">
                        <input type="text"  style="margin-right: 117px;">
                        <button class="btn btn-light" style="border: 1px solid #ced4da;"> <i class="fa fa-paste"></i> &nbsp; Import</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style type="text/css">
    .bodyfluid-list a:focus {
        background-color:#88b9ed;
    }
</style>

<script>

    $('#bodyfluidaddbutton').click(function() {
        var fldfluid = $('#bodyfluidname').val();


        if(fldfluid != '') {
            $.ajax({
                type : 'post',
                url  : '{{ route('variables.bodyfluid.add') }}',
                dataType : 'json',
                data : {
                    '_token': '{{ csrf_token() }}',
                    'fldfluid': fldfluid
                },
                success: function (res) {

                    showAlert(res.alertmsg);
                    if(res.message == 'Success') {
                        $('#bodyfluidname').val('');
                        var deleteroutename = "{{ url('/variables/bodyfluids/delete') }}/"+res.fldid;
                        $('#bodyfluidlistingmodal').append('<li class="bodyfluid-list" style="border: 1px solid #ced4da;"><a href="javascript:void(0)" class="bodyfluid_item" data-href="'+deleteroutename+'" data-id="'+res.fldid+'">'+res.fldfluid+'</li>');
                    }

                }
            });
        } else {
            alert('Body Fluid Name is required');
        }
    });

    $('#bodyfluidlistingmodal').on('click', '.bodyfluid_item', function() {
        $('#bodyfluidtobedeleteroute').val($(this).data('href'));
        $('#bodyfluididtobedeleted').val($(this).data('id'));
    });


    $('#bodyfluiddeletebutton').click(function() {
        var deletebodyfluidroute = $('#bodyfluidtobedeleteroute').val();
        var deletebodyfluidid = $('#bodyfluididtobedeleted').val();

        if(deletebodyfluidroute == '') {
            alert('no body fluid selected, please select the body fluid.');
        }

        if(deletebodyfluidroute != '') {
            var really = confirm("You really want to delete this body fluid?");
            if(!really) {
                return false
            } else {
                $.ajax({
                    type : 'delete',
                    url : deletebodyfluidroute,
                    data : {
                        '_token': '{{ csrf_token() }}',
                    },
                    success: function (res) {
                        showAlert(res);
                        if(res = 'Bodyfluid deleted successfully.') {
                            $("#bodyfluidlistingmodal").find(`[data-href='${deletebodyfluidroute}']`).parent().remove();
                            $('#bodyfluidtobedeleteroute').val('');
                            $('#bodyfluididtobedeleted').val('');
                        }
                    }
                });
            }
        }
    });
</script>
