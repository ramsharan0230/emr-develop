@extends('frontend.layouts.master')
@push('after-styles')

@endpush

@section('content')

<div class="container-fluid">
    <div class="row">
      <div class="col-lg-12 col-md-12 leftdiv">
        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-header d-flex justify-content-between">
                <div class="iq-header-title">
                    <h4 class="card-title">Diet Package</h4>
                </div>
                <button onclick="myFunction()" class="btn btn-primary"><i class="fa fa-bars"></i></button>
            </div>
        </div>
    </div>
    <div class="col-sm-12" id="myDIV">
        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-body" id="extraprocedure">
                <div class="row">
                    <div class="col-lg-8 col-md-12">
                        <form id="dietpackageform">
                            <div class="form-group form-row align-items-center">
                                <label class="col-sm-3">Group Name</label>
                                <div class="col-sm-8">
                                    <input name="group" id="group" type="text" list="groups" class="form-control" />
                                    <datalist id="groups">
                                     @if(isset($groups) && count($groups) > 0)
                                     @foreach($groups as $g)
                                     <option value="{{$g->fldgroup}}">{{$g->fldgroup}}</option>
                                     @endforeach
                                     @endif
                                 </datalist>

                             </div>
                             <div class="col-sm-1">
                                <a href="javascript:void(0);" class="btn btn-primary" onclick="listByGroup()"><i class="ri-refresh-line"></i></a>
                            </div>
                        </div>
                        <div class="form-group form-row align-items-center">
                            <label class="col-sm-3">Therapeutic Type</label>
                            <div class="col-sm-9">
                             <select name="therapeutic_type" id="therapeutic_type" class="therapeutic_type form-control">
                                <option value="">--Select--</option>
                                <option value="High Calorie/Low calorie">High Calorie/Low calorie</option>
                                <option value="High protein">High protein</option>
                                <option value="High sodium/Low sodium">High sodium/Low sodium</option>
                                <option value="High potassium/Low potassium">High potassium/Low potassium</option>
                                <option value="Others">Others</option>
                            </select>

                        </div>

                    </div>
                    <div class="form-group form-row align-items-center">
                        <label class="col-sm-3">Components</label>
                        <div class="col-sm-3">

                            <select name="components" id="components" class="components form-control">
                                <option value="">--Select--</option>
                                @if(isset($components) && count($components) > 0)
                                @foreach($components as $c)
                                <option value="{{$c->fldfoodtype}}">{{$c->fldfoodtype}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-sm-6">

                            <select name="item_type" id="item_type" class="item_type form-control">
                                <option value="">--Select--<option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group form-row align-items-center">
                            <label class="col-sm-3">Quantity</label>
                            <div class="col-sm-3">
                             <input name="quantity" id="quantity" type="number"  class="form-control" />

                         </div>

                         <div class="col-sm-2">
                            <button class="btn btn-primary" onclick="addDietPackage()"><i class="ri-add-line"></i> Add</button>
                        </div>
                        <div class="col-sm-2">
                            <button class="btn btn-warning" onclick="exportDietPackage()"><i class="ri-code-s-slash-line"></i> Export</button>
                        </div>
                                    <!-- <div class="col-sm-2">
                                        <button class="btn btn-danger" onclick="deleteGroupProc()"><i class="ri-delete-bin-5-fill"></i> Delete</button>
                                    </div> -->
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <div class="table-responsive table-container">
                        <table class="table table-bordered table-striped table-hover ">
                            <thead class="thead-light">
                                <tr>

                                    <th>Item</th>
                                    <th>Item Type</th>
                                    <th>Quantity</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="diet_package_list">

                            </tbody>
                        </table>
                        <div id="bottom_anchor"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {

        setTimeout(function() {
            $(".group").select2();
            $(".procedures").select2();
        }, 1500);

    });

    $('#components').on('change', function(){
        $.ajax({
            url: '{{ route('display.diet.item.tyoe.consultant') }}',
            type: "POST",
            data: {component:$(this).val(),"_token": "{{ csrf_token() }}"},
            success: function (response) {
                $('#item_type').empty().html(response);

            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    })
    function addDietPackage(){
        $('form').submit(false);
        var group = $('#group').val();
        var therapeutic_type = $('#therapeutic_type').val();
        var components = $('#components').val();
        var item_type = $('#item_type').val();
        var qty = $('#quantity').val();

        if(group.length < 1){
            alert('Select Group');
            return false;
        }else if(therapeutic_type.length < 1){
            alert('Select Therapeutic Type');
            return false;
        }else if(components.length < 1){
            alert('Select Components');
            return false;
        }else if(item_type.length < 1){
            alert('Select Item Type');
            return false;
        }else if(qty.length < 1){
            alert('Quantity not defined')
            return false;
        }else{
                // alert('here');
                $.ajax({
                    url: '{{ route('add.diet.package.consultant') }}',
                    type: "POST",
                    data: $('#dietpackageform').serialize(),
                    success: function (response) {
                        var groupdata = '';
                        groupdata += '<option value="' + group + '">'+group+'</option>';
                        $('#groups').append().html(groupdata);
                        $('#diet_package_list').empty().html(response);
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });
            }
        }



        function listByGroup(){

            $.ajax({
                url: '{{ route('list.group.diet.package.consultant') }}',
                type: "POST",
                data: {group:$('#group').val(),"_token": "{{ csrf_token() }}"},
                success: function (response) {
                    $('#diet_package_list').empty().html(response);

                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }


        function exportProcGroupToPdf(){
            $('form').submit(false);
            data = $('#procGroupform').serialize();

            var urlReport = baseUrl + "/consultation/group/exportToPdfGroup?" + data + "&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";


            window.open(urlReport, '_blank');

        }

        function deletedietgroup(id){
            var group = $('#group').val();
            if(confirm('Delete Diet ??')){

                $.ajax({
                    url: '{{ route('delete.diet.group.package.consultant') }}',
                    type: "POST",
                    data: {id:id,group:group,"_token": "{{ csrf_token() }}"},
                    success: function (response) {
                        $('#diet_package_list').empty().html(response);

                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });


            }
        }
    </script>

    @stop
