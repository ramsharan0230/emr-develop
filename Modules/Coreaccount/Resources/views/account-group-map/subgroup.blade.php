@extends('frontend.layouts.account')

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

        .ul-style li {
            list-style: none;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex ">
                        <div class="iq-header-title col-sm-8 p-0">
                            <h4 class="card-title">
                                Account Group Map
                            </h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <div class="row">
                            <div class="col-md-12">

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

                            <div class="col-md-6">

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="map-account-service-cost">
        <div class="modal-dialog modal-xl" id="csize">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="map-account-service-cost-title">Account Mapping</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <form action="javascript:;" id="map-form">
                        <div class="form-row">
                            <div class="col-md-4">
                                <input type="hidden" name="account_group_id" id="account_group_id">
                                <input type="radio" name="service-medicine-select" id="service-account" value="service">
                                <label for="service-account">
                                    Service
                                </label>
                                <input type="radio" name="service-medicine-select" id="medicine-account" value="medicine">
                                <label for="medicine-account">
                                    Medicine
                                </label>
                                <input type="radio" name="service-medicine-select" id="surgical-account" value="surgical">
                                <label for="surgical-account">
                                    Surgical
                                </label>
                                <input type="radio" name="service-medicine-select" id="extra-account" value="extra">
                                <label for="extra-account">
                                    Extra
                                </label>
                                <button class="btn btn-primary" onclick="mapAccount.getMapItemType()"><i class="fa fa-sync"></i></button>

                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control col-md-4" id="map-search-input" onkeyup="searchMapItems()" placeholder="Search for names..">
                            </div>
                        </div>
                        <hr>
                        <div class="text-center">
                            <button type="button" onclick="mapAccount.submitAdd()" class="btn btn-primary" id="transfer-button">>></button>
                        </div>
                        <div class="map-account-service-cost-container">
                            <div id="dropdown-items-map"></div>
                        </div>
                    </form>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
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
                    url: "{{ route('get.group.map.list') }}",
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

        $(document).ready(function () {
            $("#transfer-button").hide();
            $(".collapse").on('show.bs.collapse', function () {
                $(this).prev(".card-header").find(".fa").removeClass("fa-plus").addClass("fa-minus");
            }).on('hide.bs.collapse', function () {
                $(this).prev(".card-header").find(".fa").removeClass("fa-minus").addClass("fa-plus");
            });

        });

        var mapAccount = {
            showModalMapping: function (groupId) {
                $("#account_group_id").empty().val(groupId);
                $('#map-account-service-cost').modal('show');
            },
            getMapItemType: function () {
                var mapType = $("input[name='service-medicine-select']:checked").val();

                $.ajax({
                    url: "{{ route('get.group.map.item.type') }}",
                    type: "POST",
                    data: {mapType: mapType},
                    success: function (response) {
                        $("#transfer-button").show();
                        $('#dropdown-items-map').empty().html(response.html);
                        $('#existing-list').empty().html(response.existing);
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(errorMessage);
                    }
                });
            },
            listData: function () {
                var testItem = $("#select-service-list").val();
                $.ajax({
                    url: "{{ route('get.group.map.item.service') }}",
                    type: "POST",
                    data: {testItem: testItem},
                    success: function (response) {
                        $('#service-append').empty().html(response.html);
                        $('#existing-list').empty().html(response.existing);
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(errorMessage);
                    }
                });
            },
            submitAdd: function () {
                $.ajax({
                    url: "{{ route('get.group.map.item.add') }}",
                    type: "POST",
                    data: $("#map-form").serialize(),
                    success: function (response) {
                        $('#dropdown-items-map').empty().html(response.html);
                        $('#existing-list').empty().html(response.existing);
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(errorMessage);
                    }
                });
            }
        }

        function searchMapItems() {
            // Declare variables
            var input, filter, ul, li, a, i, txtValue;
            input = document.getElementById('map-search-input');
            filter = input.value.toUpperCase();
            ul = document.getElementById("map-search");
            li = ul.getElementsByTagName('li');

            // Loop through all list items, and hide those who don't match the search query
            for (i = 0; i < li.length; i++) {
                a = li[i].getElementsByTagName("label")[0];
                txtValue = a.textContent || a.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    li[i].style.display = "";
                } else {
                    li[i].style.display = "none";
                }
            }
        }
    </script>
@endpush
