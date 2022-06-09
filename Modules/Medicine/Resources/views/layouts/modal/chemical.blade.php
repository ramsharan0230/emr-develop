<div class="modal fade" id="chemical_modal" tabindex="-1" role="dialog" aria-labelledby="encounter_listLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="" id="encounter_listLabel">Variables</h5>
                <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-7">
                        <input type="text" id="chemicalnamefield" class="form-control">
                    </div>
                    <div class="col-md-5">
                        <a href="javascript:void(0)" class="btn btn-primary btn-action" id="chemicaladdaddbutton"><i class="fa fa-plus"></i> &nbsp;<u>A</u>dd</a>
                        <a href="javascript:void(0)" class="btn btn-primary btn-action" id="chemicaldeletebutton"><i class="fa fa-trash"></i>&nbsp;<u>D</u>elete</a>
                        <input type="hidden" id="chemicalstobedeletedroute" value="">
                        <input type="hidden" id="chemicalidtobedeleted" value="">
                    </div>
                    <div class="col-sm-12">
                        <div class="dietarytable overflow-auto" style="border: 1px solid #ced4da; height: 300px !important">
                            <table class="res-table">
                                <ul id="chemicallistingmodal" class="list-group">
                                    @forelse($chemicals as $chemical)
                                    <li class="chemical-list" style="border: 1px solid #ced4da;"><a href="javascript:void(0)" class="chemical_item" data-href="{{ route('medicines.deletechecmials', $chemical->fldid) }}" data-id="{{ $chemical->fldid }}">{{ $chemical->flclass }}</a></li>
                                    @empty
                                    @endforelse
                                </ul>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-sm-12">
                        {{-- <input type="text"  style="margin-right: 117px;">--}}
                        {{-- <button class="btn btn-light" style="border: 1px solid #ced4da;"> <i class="fa fa-paste"></i> &nbsp; Import</button>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style type="text/css">
    .chemical-list a:focus {
        background-color: #88b9ed;
    }
</style>

<script>
    $(function() {
        $('#chemicalnamefield').keyup(function() {

            var keyword = $(this).val();

            $.ajax({
                type: 'post',
                url: "{{ route('medicines.chemicalnamefilter')}}",
                dataType: 'json',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'keyword': keyword
                },
                success: function(res) {
                    if (res.message == 'success') {
                        $('#chemicallistingmodal').html(res.html);
                    } else if (res.message == 'error') {
                        alert(res.error);
                    }
                }
            });

        });
    });
</script>