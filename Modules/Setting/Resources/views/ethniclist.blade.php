@extends('frontend.layouts.master')
@section('content')
<style type="text/css">
    .highlight { background-color: red; }
</style>
<div class="container-fluid">
    @if ($message = Session::get('success'))
    <div class="alert alert-success alert-block">
        <button type="button" class="close text-black-50 float-right" data-dismiss="alert">×</button>
        <strong>{{ $message }}</strong>
    </div>
    @endif

    @if ($message = Session::get('error'))
    <div class="alert alert-danger alert-block">
        <button type="button" class="close text-black-50 float-right" data-dismiss="alert">×</button>
        <strong>{{ $message }}</strong>
    </div>
    @endif
    <div class="row">
        <div class="col-sm-12">

            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">Ethnic List</h4>
                    </div>
                </div>
                @if(Session::get('success_message'))
                    <div class="alert alert-success containerAlert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                        {{ Session::get('success_message') }}
                    </div>
                @endif

                @if(Session::get('error_message'))
                    <div class="alert alert-danger containerAlert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                        {{ Session::get('error_message') }}
                    </div>
                @endif
                <div class="iq-card-body">

                    <form action="javascript:;" id="ethnic-form" method="post">
                        @csrf

                        <div class="form-horizontal">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group form-row">
                                        <label class="col-sm-3">Ethnics</label>
                                        <div class="col-sm-5">
                                            <div class="input-group">
                                                <input type="text" name="ethnic" id="ethnic" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="hidden" name="updatevalue" id="updatevalue">
                                            <button type="button" class="btn btn-primary" onclick="ethnicList.addEthnic()">Add</button>
                                            <button type="button" class="btn btn-primary" onclick="ethnicList.editEthnic()">Edit</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="table-reponsive">
                        <table class="table table-striped table-hover table-bordered sortable">
                            <thead class="thead-light">
                                <tr>
                                    <th>SNo</th>
                                    <th>Ethnic</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="toArray" class="ethnic-table-list">
                                @forelse($ethnic_list as $list)
                                    <tr>
                                        <td id="{{$list->fldid}}" class="clickable" data-ethnic="{{$list->flditemname}}" data-fldid="{{$list->fldid}}">{{ $loop->iteration }}</td>
                                        <td id="{{$list->fldid}}" class="clickable" data-ethnic="{{$list->flditemname}}" data-fldid="{{$list->fldid}}">{{$list->flditemname}}</td>
                                        <td><a href='javascript:;' onclick='ethnicList.deleteEthnic({{ $list->fldid }})'><i class='fas fa-trash text-danger'></i></a></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center">
                                            <em>No data available in table ...</em>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3">{{ $ethnic_list->links() }}</td>
                                </tr>
                            </tfoot>
                        </table>
                        <div class="sort" style="display:none; margin-top:20px">

                            <form method="post" action="{{route('ethnic-saveorder')}}" name="sort-member">
                                @csrf
                                <input type="hidden" value="" name="order" id="arr-order"/>

                                <input type="submit" name="saveorder" class="button" value="Save Order" />
                            </form>

                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('after-script')
<script>
    $(function () {
        $.ajaxSetup({
            headers: {
                "X-CSRF-Token": $('meta[name="_token"]').attr("content")
            }
        });
    });

    $(".ethnic-table-list").on('click','.clickable',function() {
        tableClicked($(this));
    });

    $(".ethnic-table-list").on('click','.clickable',function() {
        tableClicked($(this));
    });

    function tableClicked(current){
        var ethnic = current.attr("data-ethnic");
        var fldid = current.attr('data-fldid');
        $('#ethnic').val(ethnic);
        $('#updatevalue').val(fldid);
    }

    $('#ethnic').on('keyup', function(){
        var value = $(this).val();
           $.ajax({
            url: '{{ route('search-ethnic') }}',
            type: "POST",
            data: {key:value},
            success: function (response) {
                    // console.log(response);
                    if (response.success.status) {
                            $(".ethnic-table-list").empty().append(response.success.html);
                            // showAlert('Successfully data inserted.')
                    } else {
                        showAlert({{ , 'error' }}, 'error')
                    }
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                    showAlert({{ , 'error' }}, 'error')
                }
            });
    });
    var ethnicList = {
        addEthnic: function () {
            $.ajax({
                url: '{{ route('store-ethnic') }}',
                type: "POST",
                data: $("#ethnic-form").serialize(),
                success: function (response) {
                        // console.log(response);
                        if (response.success.status) {
                            $(".ethnic-table-list").empty().append(response.success.html);
                            if(response.success.html === 'Duplicate Data'){
                                showAlert('Dupulicate Data')
                            }else{
                                showAlert('Successfully data inserted.')
                            }

                        } else {
                            showAlert({{ , 'error' }}, 'error')
                        }
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                        showAlert({{ , 'error' }}, 'error')
                    }
                });
        },
        editEthnic: function () {
            $.ajax({
                url: '{{ route('edit-ethnic') }}',
                type: "POST",
                data: $("#ethnic-form").serialize(),
                success: function (response) {
                        // console.log(response);
                        if (response.success.status) {
                            $(".ethnic-table-list").empty().append(response.success.html);
                            showAlert('Successfully data updated.')

                        } else {
                            showAlert({{ , 'error' }}, 'error')
                        }
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                        showAlert({{ , 'error' }}, 'error')
                    }
                });
        },
        deleteEthnic: function (fldid) {
            if (!confirm("Delete?")) {
                return false;
            }
            $.ajax({
                url: '{{ route('delete-ethnic') }}',
                type: "POST",
                data: {fldid: fldid},
                success: function (response) {
                        // console.log(response);
                        if (response.success.status) {
                            $(".ethnic-table-list").empty().append(response.success.html);
                            showAlert('Successfully data deleted.')
                        } else {
                            showAlert({{ , 'error' }}, 'error')
                        }
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                        showAlert({{ , 'error' }}, 'error')
                    }
                });
        }
    }
</script>
<script type="text/javascript">
  $(function(){
     $('tbody > tr').css({'cursor':'move'});

            var fixHelper = function(e, ui) {
                ui.children().each(function() {
                $(this).width($(this).width());
            });
            return ui;
            };
            $('.sortable tbody').sortable({
            update:updateOrder,
            helper: fixHelper
            });
            function updateOrder(e,ui) {

                $('.sort:hidden').fadeIn();
                var array = $('#toArray').sortable('toArray'),
                    order = '';
                for(var i in array) order += array[i]+'&';
                order = order.substr(0,order.length-1);
                $('#arr-order').val(order);
            }
    $('#content-list').bind('submit',function(){
        if ($(".flag-check:checked").length < 1) {
            $('#checkall').attr('checked',false);
            alert('Nothing selected!');
            return false;
        }
        var really = confirm("Please confirm if you want to apply this batch operation.");
        if(!really) return false;
    });

    $('.delete').bind('click',function(){
        var really = confirm("You really want to remove this content?");
        if(!really) return false;
    });

    $('#checkall').bind('click',function(){
        // alert('checkedall');
        var boxes = $(this).parents('table').find('.flag-check');
        boxes.attr('checked',$(this).is(':checked'));
    });

$('.flag-check').change(function(){
if(this.checked)
$('#update').fadeIn('slow');

else
$('#update').fadeOut('slow');
});
$('#checkall').change(function(){
if(this.checked)
$('#update').fadeIn('slow');

else
$('#update').fadeOut('slow');
});

// $('.checks').change(function(){
//       var id = $(this).val();
//       var inputid = 'input_'+id;


//       if(this.checked){
//         $('#'+inputid).fadeIn('slow');

//       }else{
//         $('#'+inputid).fadeOut('slow');
//       }

// });





})
  </script>
@endpush
