@extends('frontend.layouts.master')
@section('content')

<div class="container-fluid extra-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between align-items-center">
                    <div class="iq-header-title">
                        <div class="form-group">
                            <label class="card-title">
                                Sidebar Menu List
                            </label>
                        </div>
                    </div>
                    <a href="{{ route('sidebar.menu.add') }}" class="btn btn-primary"><i class="ri-add-fill"><span class="pl-1">Add New</span></i>
                    </a>
                </div>
                <div class="iq-card-body">

                    <div class="res-table">
                        <table class="table table-bordered table-hover table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <th>Main Menu</th>
                                    <th>Sub Menu</th>
                                    <th>Route</th>
                                    <th>Status</th>
                                   
                                    <th>Action</th>
                                    
                                </tr>
                            </thead>
                            <tbody id="sidebar-table example">

                     @if($sidebars)
                     @foreach($sidebars as $menu)
                     <tr>
                     <td>{{ $menu->mainmenu }}</td>
                     <td>{{ $menu->submenu }}</td>
                     <td>{{ $menu->route }}</td>
                     <td>{{ $menu->status }}</td>
                    
                     <td><a href="{{route('sidebar.menu.edit',$menu->id)}}">Edit</a></td>

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

@endsection

@push('after-script')
<script
            src="http://code.jquery.com/jquery-3.3.1.min.js"
            integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
            crossorigin="anonymous"></script>
    <script
            src="http://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
            integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU="
            crossorigin="anonymous"></script>
<script>


// $(document).on("click", "tr", function(e) {
//     alert('d');
// });



 
        $(document).ready(function () {
           $('table tbody').sortable({
               update: function (event, ui) {
                   $(this).children().each(function (index) {
                        if ($(this).attr('data-position') != (index+1)) {
                            $(this).attr('data-position', (index+1)).addClass('updated');
                        }
                   });

                   saveNewPositions();
               }
           });
        });

        function saveNewPositions() {
            var positions = [];
            $('.updated').each(function () {
               positions.push([$(this).attr('data-index'), $(this).attr('data-position')]);
               $(this).removeClass('updated');
            });

            $.ajax({
               url: 'index.php',
               method: 'POST',
               dataType: 'text',
               data: {
                   update: 1,
                   positions: positions
               }, success: function (response) {
                    console.log(response);
               }
            });
        }
 

 


    var sidebar = {
        editSidebar: function(id) {
            window.location.href = baseUrl + "/setting/sidebar/edit/" + id;
        },
        deleteSidebar: function(id) {
            if (!confirm("Delete?")) {
                return false;
            }
            $.ajax({
                url: '{{ route('sidebar.menu.delete') }}',
                type: "POST",
                data: {
                    id: id
                },
                success: function(response) {
                    if (response.success.status) {
                        $("#sidebar-table").empty().append(response.success.html);
                        showAlert('Successfully data deleted.')
                    } else {
                        showAlert("__('messages.error')", 'error')
                    }
                },
                error: function(xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                    showAlert("__('messages.error')", 'error')
                }
            });
        }
    }
</script>
@endpush