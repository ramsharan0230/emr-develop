@extends('frontend.layouts.master')
@section('content')

    <div class="container-fluid extra-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">
                                All Notifications
                            </h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        @if(Session::get('success_message'))
                            <div class="alert alert-success containerAlert">
                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span
                                            class="sr-only">Close</span></button>
                                {{ Session::get('success_message') }}
                            </div>
                        @endif

                        @if(Session::get('error_message'))
                            <div class="alert alert-danger containerAlert">
                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span
                                            class="sr-only">Close</span></button>
                                {{ Session::get('error_message') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">
                        <div class="res-table">
                            <table class="table table-bordered table-striped table-hover">
                                <thead class="thead-light">
                                <tr>

                                    <th align="center">Notifications</th>
                                    <th align="center">status</th>
                                    <th align="center">Action</th>

                                </tr>
                                </thead>
                                <tbody id="notification-list">
                                @if($notifications)
                                    @foreach($notifications as $notification)
                                        <tr>
                                            <td align="center">{{ (isset($notification->data) && isset($notification->data['data']) &&  isset($notification->data['data']['message'])) ?  $notification->data['data']['message'] :''}}</td>
                                            <td align="center">@if(isset($notification) && $notification->read_at==null) Not Read @else Read @endif</td>
                                            <td align="center"></td>
{{--                                            @if($notification['read_at'] == '')--}}
{{--                                                <td align="center"><a  class="" href="{{ route('notification.mark.read', $notification->id) }}">Mark Read</a>--}}
{{--                                            @else--}}
{{--                                                <td align="center">Marked as read</a>--}}
{{--                                            @endif--}}

                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>

                            </table>
                        </div>
                       {{ $notifications->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('after-script')

    <script>
        // $(document).on('click', '.mark-all-read', function () {
        //     var url = $(this).data('url');
        //     if (url != '') {
        //         $.ajax({
        //             url: url,
        //             type: "GET",
        //             success: function (data) {
        //                 $('#notification-count').empty().append(data.count);
        //                 $('#notification-drop-down').empty().append(data.view);
        //                 if (data.message) {
        //                     showAlert(data.message);
        //                 }
        //                 $(".mark-all-read").remove();
        //             },
        //             error: function (xhr, status, error) {
        //                 var errorMessage = xhr.status + ': ' + xhr.statusText;
        //                 console.log(xhr);
        //             }
        //         });
        //     } else {
        //         showAlert('Something went wrong', 'error');
        //     }
        // })

    </script>
@endpush
