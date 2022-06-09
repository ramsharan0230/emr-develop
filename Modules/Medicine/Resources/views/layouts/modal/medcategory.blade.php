<div class="modal fade" id="med_category_modal" tabindex="-1" role="dialog" aria-labelledby="encounter_listLabel" aria-hidden="true">
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
                    <div class="col-md-6">
                        <input type="text" id="categorynamefield" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <div class="form-row">
                            <button type="reset" class="btn btn-primary btn-action" id="resetcategorylistingitems"><i class="fas fa-sync"></i></button>&nbsp;
                            <a href="javascript:void(0)" class="btn btn-primary btn-action" id="categoryaddaddbutton"><i class="fa fa-plus"></i>&nbsp;<u>A</u>dd</a>&nbsp;
                            <a href="javascript:void(0)" class="btn btn-primary btn-action" id="categorydeletebutton"><i class="fa fa-trash"></i>&nbsp;<u>D</u>elete</a>
                            <input type="hidden" id="categorytobedeletedroute" value="">
                            <input type="hidden" id="categoryidtobedeleted" value="">
                        </div>
                    </div>
                    <div class="col-md-12 mt-2">
                        <div class="res-table">
                            <table class="dietary-table">
                                <ul id="categorylistingmodal" class="list-group">
                                    @forelse($medcategories as $medcategory)
                                    <li class="list-group-item"><a href="javascript:void(0)" class="category_item" data-href="{{ route('medicines.deletemedcategory', $medcategory->fldid) }}" data-id="{{ $medcategory->fldid }}">{{ $medcategory->flclass }}</a></li>
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
    .category-list a:focus {
        background-color: #88b9ed;
    }
</style>

<script>
    $(function() {
        $('#categorynamefield').keyup(function() {

            var keyword = $(this).val();
            $.ajax({
                type: 'post',
                url: "{{ route('medicines.medcategorynamefilter')}}",
                dataType: 'json',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'keyword': keyword
                },
                success: function(res) {
                    if (res.message == 'success') {
                        $('#categorylistingmodal').html(res.html);
                    } else if (res.message == 'error') {
                        alert(res.error);
                    }
                }
            });

        });

        // $('#resetcategorylistingitems').click() {

        // }
    });
</script>