@extends('frontend.layouts.master')

@push('after-styles')
    <style>
        .btn-link:hover, .btn-link, .btn-link:focus {
            text-decoration: none;
        }

        .ui-state-active, .ui-widget-content .ui-state-active, .ui-widget-header .ui-state-active, a.ui-button:active, .ui-button:active, .ui-button.ui-state-active:hover {
            border: 1px solid #a5a5a5;
            background: #f3f3f3;
        }

        .btn-link:hover {
            color: #000000;
            text-decoration: none;
        }

        .btn-link {
            color: #000000;
        }

        #accordion .btn {
            font-size: 13px;
        }

        #accordion .fa-plus {
            color: green;
        }

        #accordion .fa-minus {
            color: darkred;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex ">
                        <div class="iq-header-title col-sm-7 p-0">
                            <h4 class="card-title">
                                Account Group
                            </h4>
                        </div>
                        <!-- <div class="accountsearchbox col-sm-4">
                            <input type="text" class="form-control" placeholder="Search account group...">
                            <a class="search-link" id="header-search" href="#"><i class="ri-search-line"></i></a>
                        </div> -->
                        <div class="col-sm-4">

                            <button type="button" class="btn btn-primary float-right" onclick="exportAccountGroupToExcel()">Export To Excel</button>
                            <button type="button" class="btn btn-primary float-right mr-2" data-toggle="modal" data-target="#accountModal"><i class="fa fa-plus"></i>Add</button>
                        </div>

                    </div>
                    <div class="iq-card-body">
                        <div class="form-group">
                            <div class="table-responsive res-table">

                                <div id="accordion">
                                    <div class="card">
                                        @if(isset($groups) and count($groups) > 0)
                                            @foreach($groups as $r)
                                                <div>
                                                    <div class="card-header" id="heading{{ $r->GroupId }}">
                                                        <h4 class="mb-0">
                                                            <button class="btn btn-link" data-toggle="collapse" data-target="#group-{{ $r->GroupId }}" aria-expanded="true" aria-controls="group-{{ $r->GroupId }}" data-id="{{ $r->GroupId }}">
                                                                <i class="fa fa-plus"></i> {{ $r->GroupTree }}. {{ $r->GroupName }}
                                                            </button>
                                                        </h4>
                                                    </div>

                                                    <div id="group-{{ $r->GroupId }}" class="collapse" aria-labelledby="heading{{ $r->GroupId }}" data-parent="#accordion" data-check-open="{{ $r->GroupId }}">
                                                        <div class="card-body">
                                                            <div id="accordion-{{ $r->GroupId }}">
                                                                <div class="card">
                                                                    @if($r->children)
                                                                        @forelse($r->children as $child)
                                                                            <div>
                                                                                <div class="card-header" id="heading{{ $child->GroupId }}">
                                                                                    <h4 class="mb-0">
                                                                                        <button class="btn btn-link click-sub-account-group" data-toggle="collapse" data-target="#group-{{ $child->GroupId }}" aria-expanded="true" aria-controls="group-{{ $child->GroupId }}" data-id="{{ $child->GroupId }}">
                                                                                            <i class="fa fa-plus"></i> {{ $child->GroupTree }}. {{ $child->GroupName }}
                                                                                        </button>
                                                                                    </h4>
                                                                                </div>

                                                                                <div id="group-{{ $child->GroupId }}" class="collapse" aria-labelledby="heading{{ $child->GroupId }}" data-parent="#accordion-{{ $r->GroupId }}" data-check-open="{{ $child->GroupId }}">
                                                                                    <div class="card-body">
                                                                                        <div class="accordion-data-append-{{ $child->GroupId }}"></div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @empty
                                                                        @endforelse
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="accountModal" tabindex="-1" role="dialog" aria-labelledby="accountModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="accountModalLabel">Account SubGroup</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="subgroup-data">
                        <div class="form-group form-row">
                            <label for="" class="col-sm-6">Group Name:<span class="text-danger">*</span></label>
                            <div class="col-sm-6">
                                <input type="text" name="group_name" list="groupname" class="form-control">
                                <datalist id="groupname">
                                    <option value="">--Select Group--</option>
                                    @if(isset($allgroup) and count($allgroup) > 0)
                                        @foreach($allgroup as $g)
                                            <option value="{{$g->GroupName}}">{{$g->GroupName}}</option>
                                        @endforeach
                                    @endif
                                </datalist>
                                <!-- <input type="hidden" name="group_name" id="group_name"> -->
                                <!-- <select class="form-control" name="group_name" id="groupname">

                                </select> -->
                                <!-- <input type="text" class="form-control"> -->
                            </div>
                        </div>
                        <div class="form-group form-row">
                            <label for="" class="col-sm-6">Sub Group Name:<span class="text-danger">*</span></label>
                            <div class="col-sm-6">
                                <input type="text" name="sub_group_name" id="sub_group_name" class="form-control">
                            </div>
                        </div>
                        <div class="form-group form-row">
                            <label for="" class="col-sm-6">Sub Group Name In Nepali:<span class="text-danger">*</span></label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="nepali_sub_group_name">
                            </div>
                        </div>
                        <!-- <div class="form-group form-row">
                            <label for="" class="col-sm-6">Select Nature:</label>
                            <div class="col-sm-5">
                                <select name="" id="" class="form-control">
                                    <option value="">Assests</option>
                                    <option value="">liabilities</option>
                                    <option value="">Expenses</option>
                                    <option value="">Income</option>
                                </select>
                            </div>
                            <div class="col-sm-1">
                                <button onclick="myFunction()" class="btn btn-primary"><i class="fa fa-plus"></i></button>
                            </div>
                            <div id="myDIV" class="col-sm-12 border-top" style="display: none;">
                                <div class="form-row mt-3">
                                    <div class="col-sm-12">
                                        <div class="form-group form-row">
                                            <label for="" class="col-sm-3">Name:</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group form-row">
                                            <label for="" class="col-sm-3">Select Nature:</label>
                                            <div class="col-sm-7">
                                                <select name="" id="" class="form-control">
                                                    <option value="">Assests</option>
                                                    <option value="">liabilities</option>
                                                    <option value="">Expenses</option>
                                                    <option value="">Income</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-2">
                                                <button type="button" class="btn btn-primary">Add</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="addGroup()">Add</button>
                    <button type="button" class="btn btn-primary" onclick="addGroup('new')">Add & New</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('after-script')
    <script>
        // $('#group_name').val($('#groupname').val());
        $(document).ready(function () {
            $('.grouptable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copyHtml5',
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5'
                ]
            });

            $(document).on('click', '.click-sub-account-group', function (event) {
                // $(this).prev(".card-header").find(".fa").addClass("fa-minus").removeClass("fa-plus");
                groupId = $(this).attr('data-id');
                $.ajax({
                    url: "{{ route('get.group.list') }}",
                    type: "POST",
                    data: {groupId: groupId},
                    success: function (response) {
                        // console.log(groupId);
                        $(".accordion-data-append-" + groupId).empty().append(response);
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(errorMessage);
                    }
                });
            });
        });


        function addGroup(newInsert = null) {
            $.ajax({
                url: baseUrl + '/coreaccount/addGroup',
                type: "POST",
                data: $('#subgroup-data').serialize(),
                success: function (response) {
                    // $('#group-list').append().html(response.html);
                    // $('#groupname').append().html(response.grouphtml);
                    // if (newInsert === null) {
                    //     $('#accountModal').modal('hide');
                    // }
                    // $("#sub_group_name").val('');
                    showAlert('Data Added');
                    history.go(0);

                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }

        function exportAccountGroupToExcel() {
            // alert('export group excel');
            var urlReport = baseUrl + "/coreaccount/exportGroup?&action=" + "Report" + "&_token=" + "{{ csrf_token() }}";
            window.open(urlReport);
        }

        $(document).ready(function () {
            $(".collapse").on('show.bs.collapse', function () {
                $(this).prev(".card-header").find(".fa").removeClass("fa-plus").addClass("fa-minus");
            }).on('hide.bs.collapse', function () {
                $(this).prev(".card-header").find(".fa").removeClass("fa-minus").addClass("fa-plus");
            });
        });
    </script>
@endpush
