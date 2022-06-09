<div class="modal fade" id="ethnic-group" tabindex="-1" role="dialog" aria-labelledby="encounter_listLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="" id="encounter_listLabel">Ethnic Group</h5>
                <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="background-color: #efebe7">
                <div class="container">
                    <div class="row">
                        <div class="col-md-11">
                            <div class="group__box half_box">
                                <div class="box__label" style="flex: 0 0 25%;">
                                    <label class="label-width-food-mixture">Group Name</label>
                                </div>&nbsp;
                                <div class="box__input" style="flex: 0 0 65%;">
                                    <input type="text" name="ethnicgroupname" id="ethnicgroupname" style="width: 85%;"  class="form-input-food-mix">
                                    <a href="javascript:void(0)" id="loadsurnamefromethnicgroup" title="load surnames"><img src="{{asset('assets/images/refresh.png')}}" width="20px" alt=""></a>
                                    @php $ethnicgroups = \App\Utils\Variablehelpers::getAllEthnicGroups(); @endphp
                                    <select name="ethnicgroupselect" id="ethnicgroupselect" class="form-input-food-mix" readonly="" style="width: 85%;">
                                        <option value=""></option>
                                        @forelse($ethnicgroups as $ethnicgroup)
                                            <option value="{{ $ethnicgroup->fldgroupname }}"> {{ $ethnicgroup->fldgroupname }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1">
                                <div class="group__box half_box">
                                    <div class="box__label" style="flex: 1 0 77%;">
                                        <a href="javascript:void(0)" data-toggle="modal" data-target="#add_surname_modal" id="addsurnamestoethnicgroup"><img src="{{asset('assets/images/plus.png')}}" width="16px"></a>
                                    </div>
                                </div>
                            </div>

                        <br><br>
                        <div class="col-md-12 col-sm-12">
                            <div class="dietarytable overflow-auto" style="border: 1px solid #ced4da; height: 300px !important; background-color: white">
                                <table class="dietary-table" id="surnamelists">
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="background-color: #efebe7">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-4">
                            <button class="btn btn-light"  data-toggle="modal" data-target="#duplicate_items" id="duplicateitems" style="border: 1px solid #ced4da;"> <i class="fa fa-square"></i> &nbsp; Dublicates</button>
                        </div>
                        <div class="col-md-3 offset-md-5 text-right">
                            <button class="btn btn-light" data-toggle="modal" data-target="#missing_items" id="missingitems" style="border: 1px solid #ced4da;"> <i class="fa fa-square"></i> &nbsp; Missing</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('variables::layouts.modal.add-surname')
@include('variables::layouts.modal.duplicate')
@include('variables::layouts.modal.missing')
<script>
    $(function() {
       $('#ethnicgroupselect').change(function() {
          var ethnicgroup = $(this).val();

          $('#ethnicgroupname').val(ethnicgroup);
       });

       $('#loadsurnamefromethnicgroup').click(function() {

           var groupname =  $('#ethnicgroupname').val();

           $.ajax({
              type: 'post',
              url: '{{ route('variables.ethnicgroup.getsurname') }}',
              dataType: 'json',
              data: {
                  '_token' : '{{ csrf_token() }}',
                  'fldgroupname' : groupname,
              },
               success: function (res) {
                  if(res.message == 'success') {
                      $('#surnamelists').html(res.html);
                  } else if(res.message == 'error') {
                    showAlert(res.error);
                  }

               }
           });
       })

        $('#addsurnamestoethnicgroup').click(function() {
            var ethnicgroupname = $('#ethnicgroupname').val();

            $('#ethnicgroupvalue').val(ethnicgroupname);
        });

        $('#surnamelists').on('click', '.deletethnicgroup', function() {
            var really = confirm("You really want to delete this ethic group?");
            var href = $(this).data('href');
            if(!really) {
                return false
            } else {
                $.ajax({
                    type : 'delete',
                    url : href,
                    dataType: 'json',
                    data : {
                        '_token': '{{ csrf_token() }}',
                    },
                    success: function (res) {
                        if(res.message == 'success') {
                            // alert('ethnicgroup deleted successfully.');
                            $('#loadsurnamefromethnicgroup').click();
                        } else if(res.message == 'error') {
                            alert(res.error);
                        }
                    }
                });
            }
        });
    });
</script>
